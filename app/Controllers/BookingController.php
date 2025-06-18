<?php

namespace App\Controllers;

use App\Models\BookingModel;
use App\Models\EventPerformance;
use App\Models\UserProfileModel;
use App\Models\ArtistModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Database\BaseConnection;

class BookingController extends ResourceController
{
    use ResponseTrait;

    protected $eventModel;
    protected $bookingModel;
    protected $artistModel;
    protected $userProfileModel;
    protected $session;
    protected $db;

    public function __construct()
    {
        $this->eventModel = new EventPerformance();
        $this->bookingModel = new BookingModel();
        $this->artistModel = new ArtistModel();
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

        // Debug log
        log_message('debug', 'User data: ' . json_encode($userData));

        if ($userData['user_type'] === 'venue_owner') {
            // Debug log
            log_message('debug', 'Fetching bookings for venue owner ID: ' . $userData['id']);
            $bookings = $this->bookingModel->getBookingsByVenueOwner($userData['id'], $filter);
            // Debug log
            log_message('debug', 'Found bookings: ' . json_encode($bookings));
        } else if ($userData['user_type'] === 'artist') {
            $artist = $this->artistModel->where('performer', $userData['id'])->first();
            // Debug log
            log_message('debug', 'Artist data: ' . json_encode($artist));
            
            if ($artist) {
                $bookings = $this->bookingModel->getArtistBookings($artist['id'], $filter);
                // Debug log
                log_message('debug', 'Found artist bookings: ' . json_encode($bookings));
            } else {
                $bookings = [];
            }
        } else {
            return $this->fail('Unauthorized access', 403);
        }

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

        // Debug log
        log_message('debug', 'Creating booking with data: ' . json_encode($json));

        // Validate required fields
        if (!isset($json['event_id'])) {
            return $this->fail('Missing required fields');
        }

        // Get artist ID for the current user
        $artist = $this->artistModel->where('performer', $userData['id'])->first();
        if (!$artist) {
            return $this->fail('Artist profile not found');
        }

        // Check if event exists
        $event = $this->eventModel->find($json['event_id']);
        if (!$event) {
            return $this->fail('Event not found');
        }

        // Check if booking already exists
        $existingBooking = $this->bookingModel->checkBookingExists($json['event_id'], $artist['id']);
        if ($existingBooking) {
            return $this->fail('You have already booked this event');
        }

        try {
            $bookingId = $this->bookingModel->createBooking($json['event_id'], $artist['id']);

            return $this->respondCreated([
                'message' => 'Booking created successfully',
                'booking_id' => $bookingId
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Failed to create booking: ' . $e->getMessage());
            return $this->fail('Failed to create booking: ' . $e->getMessage());
        }
    }

    public function view($id)
    {
        if (!$this->session->get('user_data')) {
            return $this->fail('Unauthorized', 401);
        }

        $booking = $this->bookingModel->getBookingDetails($id);

        if (!$booking) {
            return $this->fail('Booking not found');
        }

        // Check if user has permission to view this booking
        $userData = $this->session->get('user_data');
        if ($userData['user_type'] === 'venue_owner') {
            $venue = $this->db->table('venue')
                ->where('id', $booking['venue_id'])
                ->where('owner_profile', $userData['id'])
                ->get()
                ->getRowArray();
            
            if (!$venue) {
                return $this->fail('Unauthorized access to this booking', 403);
            }
        } else if ($userData['user_type'] === 'artist') {
            $artist = $this->artistModel->where('performer', $userData['id'])->first();
            if (!$artist || $artist['id'] !== $booking['artist']) {
                return $this->fail('Unauthorized access to this booking', 403);
            }
        }

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

        // Check if user has permission to update this booking
        $userData = $this->session->get('user_data');
        if ($userData['user_type'] === 'venue_owner') {
            $venue = $this->db->table('venue v')
                ->join('event_performance ep', 'ep.venue_id = v.id')
                ->where('ep.id', $booking['booking_event'])
                ->where('v.owner_profile', $userData['id'])
                ->get()
                ->getRowArray();
            
            if (!$venue) {
                return $this->fail('Unauthorized access to update this booking', 403);
            }
        } else {
            return $this->fail('Only venue owners can update booking status', 403);
        }

        // Don't allow status changes for cancelled bookings
        if ($booking['booking_status'] === 'cancelled') {
            return $this->fail('Cannot update status of a cancelled booking');
        }

        try {
            $this->bookingModel->updateBookingStatus($id, $json['status']);

            return $this->respond(['message' => 'Booking status updated successfully']);
        } catch (\Exception $e) {
            log_message('error', 'Failed to update booking status: ' . $e->getMessage());
            return $this->fail('Failed to update booking status: ' . $e->getMessage());
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

        // Check if user has permission to cancel this booking
        $userData = $this->session->get('user_data');
        if ($userData['user_type'] === 'venue_owner') {
            $venue = $this->db->table('venue v')
                ->join('event_performance ep', 'ep.venue_id = v.id')
                ->where('ep.id', $booking['booking_event'])
                ->where('v.owner_profile', $userData['id'])
                ->get()
                ->getRowArray();
            
            if (!$venue) {
                return $this->fail('Unauthorized access to cancel this booking', 403);
            }
        } else if ($userData['user_type'] === 'artist') {
            $artist = $this->artistModel->where('performer', $userData['id'])->first();
            if (!$artist || $artist['id'] !== $booking['artist']) {
                return $this->fail('Unauthorized access to cancel this booking', 403);
            }
        }

        // Don't allow cancelling already cancelled bookings
        if ($booking['booking_status'] === 'cancelled') {
            return $this->fail('Booking is already cancelled');
        }

        try {
            $this->bookingModel->updateBookingStatus($id, 'cancelled');

            return $this->respond(['message' => 'Booking cancelled successfully']);
        } catch (\Exception $e) {
            log_message('error', 'Failed to cancel booking: ' . $e->getMessage());
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
        $bookings = $this->bookingModel->where('booking_event', $eventId)
            ->where('DATE(date_created)', $date)
            ->where('booking_status !=', 'cancelled')
            ->where('booking_status !=', 'rejected')
            ->findAll();

        // Create a list of unavailable time slots
        $unavailableSlots = [];
        foreach ($bookings as $booking) {
            $startTime = date('H:i', strtotime($booking['date_created']));
            $unavailableSlots[] = $startTime;
        }

        return $this->respond([
            'unavailable_slots' => $unavailableSlots
        ]);
    }
} 