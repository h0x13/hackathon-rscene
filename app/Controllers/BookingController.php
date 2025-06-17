<?php

namespace App\Controllers;

use App\Models\BookingModel;
use App\Models\EventPerformance;
use App\Models\UserProfileModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Database\BaseConnection;

class BookingController extends ResourceController
{
    use ResponseTrait;

    protected $eventModel;
    protected $bookingModel;
    protected $paymentModel;
    protected $userProfileModel;
    protected $locationModel;
    protected $addressModel;
    protected $session;
    protected $db;

    public function __construct()
    {
        $this->eventModel = new EventPerformance();
        $this->bookingModel = new BookingModel();
        $this->paymentModel = new BookingModel();
        $this->userProfileModel = new UserProfileModel();
        $this->session = \Config\Services::session();
        $this->db = \Config\Database::connect();
    }

    public function list()
    {
        if (!$this->session->get('user_data')) {
            return redirect()->to('login');
        }

        $filter = $this->request->getGet('filter') ?? 'all';
        $userData = $this->session->get('user_data');

        $query = $this->bookingModel->select('
                event_planner_booking.*,
                event_planner_event.event_name,
                event_planner_event.event_description,
                user_profile.first_name as booker_first_name,
                user_profile.last_name as booker_last_name,
                user_credential.email as booker_email,
                event_planner_address.name as venue_name,
                event_planner_address.street_address,
                event_planner_address.barangay,
                event_planner_address.city,
                event_planner_address.country,
                event_planner_address.zip_code
            ')
            ->join('event_planner_event', 'event_planner_event.id = event_planner_booking.event_id')
            ->join('user_profile', 'user_profile.id = event_planner_booking.booker_id')
            ->join('user_credential', 'user_credential.user_profile_id = user_profile.id')
            ->join('event_planner_address', 'event_planner_address.event_id = event_planner_event.id');

        // Filter based on user type
        if ($userData['user_type'] === 'artist') {
            $query->where('event_planner_booking.booker_id', $userData['id']);
        } else {
            $query->where('event_planner_event.event_organizer_id', $userData['id']);
        }

        if ($filter !== 'all') {
            $query->where('event_planner_booking.status', $filter);
        }

        $bookings = $query->orderBy('event_planner_booking.created_at', 'DESC')->findAll();

        if ($this->request->isAJAX()) {
            return $this->respond(['bookings' => $bookings]);
        }

        return view('pages/venue_owners/bookings', ['bookings' => $bookings]);
    }

    public function create()
    {
        if (!$this->session->get('user_data')) {
            return $this->fail('Unauthorized', 401);
        }

        $json = $this->request->getJSON(true);
        $userData = $this->session->get('user_data');

        // Validate required fields
        if (!isset($json['event_id']) || !isset($json['start_time']) || !isset($json['end_time'])) {
            return $this->fail('Missing required fields');
        }

        // Check if the time slot is available
        if (!$this->bookingModel->isAvailable($json['event_id'], $json['start_time'], $json['end_time'])) {
            return $this->fail('Selected time slot is not available');
        }

        // Calculate total amount
        $totalAmount = $this->bookingModel->calculateTotalAmount(
            $json['event_id'],
            $json['start_time'],
            $json['end_time']
        );

        // Create booking
        $bookingData = [
            'event_id' => $json['event_id'],
            'booker_id' => $userData['id'],
            'booking_date' => date('Y-m-d'),
            'start_time' => $json['start_time'],
            'end_time' => $json['end_time'],
            'status' => 'pending',
            'total_amount' => $totalAmount,
            'payment_status' => 'pending',
            'notes' => $json['notes'] ?? null
        ];

        try {
            $this->db->transStart();

            $bookingId = $this->bookingModel->insert($bookingData);

            // Create initial payment record
            $paymentData = [
                'booking_id' => $bookingId,
                'amount' => $totalAmount,
                'payment_method' => $json['payment_method'] ?? 'pending',
                'payment_date' => date('Y-m-d H:i:s'),
                'status' => 'pending'
            ];

            $this->paymentModel->insert($paymentData);

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                return $this->fail('Failed to create booking');
            }

            return $this->respondCreated([
                'message' => 'Booking created successfully',
                'booking_id' => $bookingId
            ]);
        } catch (\Exception $e) {
            return $this->fail('Failed to create booking: ' . $e->getMessage());
        }
    }

    public function view($id)
    {
        if (!$this->session->get('user_data')) {
            return $this->fail('Unauthorized', 401);
        }

        $booking = $this->bookingModel->select('
                event_planner_booking.*,
                event_planner_event.event_name,
                event_planner_event.event_description,
                user_profile.first_name as booker_first_name,
                user_profile.last_name as booker_last_name,
                user_credential.email as booker_email,
                event_planner_address.name as venue_name,
                event_planner_address.street_address,
                event_planner_address.barangay,
                event_planner_address.city,
                event_planner_address.country,
                event_planner_address.zip_code
            ')
            ->join('event_planner_event', 'event_planner_event.id = event_planner_booking.event_id')
            ->join('user_profile', 'user_profile.id = event_planner_booking.booker_id')
            ->join('user_credential', 'user_credential.user_profile_id = user_profile.id')
            ->join('event_planner_address', 'event_planner_address.event_id = event_planner_event.id')
            ->where('event_planner_booking.id', $id)
            ->first();

        if (!$booking) {
            return $this->fail('Booking not found');
        }

        // Get payment history
        $payments = $this->paymentModel->where('booking_id', $id)->findAll();
        $booking['payments'] = $payments;

        return $this->respond(['booking' => $booking]);
    }

    public function updateStatus($id)
    {
        if (!$this->session->get('user_data')) {
            return $this->fail('Unauthorized', 401);
        }

        $json = $this->request->getJSON(true);
        
        if (!isset($json['status']) || !in_array($json['status'], ['pending', 'approved', 'rejected', 'cancelled'])) {
            return $this->fail('Invalid status');
        }

        $booking = $this->bookingModel->find($id);

        if (!$booking) {
            return $this->fail('Booking not found');
        }

        // Don't allow status changes for cancelled bookings
        if ($booking['status'] === 'cancelled') {
            return $this->fail('Cannot update status of a cancelled booking');
        }

        // Don't allow cancelling already approved/rejected bookings
        if ($json['status'] === 'cancelled' && in_array($booking['status'], ['approved', 'rejected'])) {
            return $this->fail('Cannot cancel an already ' . $booking['status'] . ' booking');
        }

        try {
            $this->bookingModel->update($id, [
                'status' => $json['status']
            ]);

            return $this->respond(['message' => 'Booking status updated successfully']);
        } catch (\Exception $e) {
            return $this->fail('Failed to update booking status: ' . $e->getMessage());
        }
    }

    public function updatePayment($id)
    {
        if (!$this->session->get('user_data')) {
            return $this->fail('Unauthorized', 401);
        }

        $json = $this->request->getJSON(true);
        
        if (!isset($json['status']) || !in_array($json['status'], ['pending', 'completed', 'failed', 'refunded'])) {
            return $this->fail('Invalid payment status');
        }

        $booking = $this->bookingModel->find($id);

        if (!$booking) {
            return $this->fail('Booking not found');
        }

        try {
            $this->db->transStart();

            // Update booking payment status
            $this->bookingModel->update($id, [
                'payment_status' => $json['status']
            ]);

            // Create new payment record
            $paymentData = [
                'booking_id' => $id,
                'amount' => $booking['total_amount'],
                'payment_method' => $json['payment_method'] ?? 'manual',
                'payment_date' => date('Y-m-d H:i:s'),
                'transaction_id' => $json['transaction_id'] ?? null,
                'status' => $json['status']
            ];

            $this->paymentModel->insert($paymentData);

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                return $this->fail('Failed to update payment status');
            }

            return $this->respond(['message' => 'Payment status updated successfully']);
        } catch (\Exception $e) {
            return $this->fail('Failed to update payment status: ' . $e->getMessage());
        }
    }

    public function cancel($id)
    {
        if (!$this->session->get('user_data')) {
            return $this->fail('Unauthorized', 401);
        }

        $booking = $this->bookingModel->find($id);

        if (!$booking) {
            return $this->fail('Booking not found');
        }

        // Don't allow cancelling already cancelled bookings
        if ($booking['status'] === 'cancelled') {
            return $this->fail('Booking is already cancelled');
        }

        // Don't allow cancelling already approved/rejected bookings
        if (in_array($booking['status'], ['approved', 'rejected'])) {
            return $this->fail('Cannot cancel an already ' . $booking['status'] . ' booking');
        }

        try {
            $this->bookingModel->update($id, [
                'status' => 'cancelled'
            ]);

            return $this->respond(['message' => 'Booking cancelled successfully']);
        } catch (\Exception $e) {
            return $this->fail('Failed to cancel booking: ' . $e->getMessage());
        }
    }

    public function checkAvailability($eventId)
    {
        if (!$this->session->get('user_data')) {
            return $this->fail('Unauthorized', 401);
        }

        $date = $this->request->getGet('date');
        if (!$date) {
            return $this->fail('Date is required');
        }

        // Get all bookings for the event on the specified date
        $bookings = $this->bookingModel->where('event_id', $eventId)
            ->where('DATE(start_time)', $date)
            ->where('status !=', 'cancelled')
            ->where('status !=', 'rejected')
            ->findAll();

        // Create a list of unavailable time slots
        $unavailableSlots = [];
        foreach ($bookings as $booking) {
            $startTime = date('H:i', strtotime($booking['start_time']));
            $unavailableSlots[] = $startTime;
        }

        return $this->respond([
            'unavailable_slots' => $unavailableSlots
        ]);
    }
} 