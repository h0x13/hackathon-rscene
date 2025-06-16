<?php

namespace App\Controllers;

use App\Models\EventPlannerEvent;
use App\Models\UserProfileModel;
use App\Models\EventPlannerLocation;
use App\Models\EventPlannerAddress;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class BookingController extends ResourceController
{
    use ResponseTrait;

    protected $eventModel;
    protected $userProfileModel;
    protected $locationModel;
    protected $addressModel;
    protected $session;

    public function __construct()
    {
        $this->eventModel = new EventPlannerEvent();
        $this->userProfileModel = new UserProfileModel();
        $this->locationModel = new EventPlannerLocation();
        $this->addressModel = new EventPlannerAddress();
        $this->session = \Config\Services::session();
    }

    public function list()
    {
        if (!$this->session->get('user_data')) {
            return redirect()->to('login');
        }

        $filter = $this->request->getGet('filter') ?? 'all';

        $query = $this->eventModel->select('
                event_planner_event.*,
                user_profile.first_name as organizer_first_name,
                user_profile.last_name as organizer_last_name,
                user_credential.email as organizer_email,
                event_planner_location.lat,
                event_planner_location.long,
                event_planner_address.name as venue_name,
                event_planner_address.street_address,
                event_planner_address.barangay,
                event_planner_address.city,
                event_planner_address.country,
                event_planner_address.zip_code
            ')
            ->join('user_profile', 'user_profile.id = event_planner_event.event_organizer_id')
            ->join('user_credential', 'user_credential.user_profile_id = user_profile.id')
            ->join('event_planner_location', 'event_planner_location.id = event_planner_event.location_id')
            ->join('event_planner_address', 'event_planner_address.event_id = event_planner_event.id');

        if ($filter !== 'all') {
            $query->where('event_planner_event.status', $filter);
        }

        $bookings = $query->orderBy('event_planner_event.event_date', 'DESC')->findAll();

        if ($this->request->isAJAX()) {
            return $this->respond(['bookings' => $bookings]);
        }

        return view('pages/venue_owners/bookings', ['bookings' => $bookings]);
    }

    public function view($id)
    {
        if (!$this->session->get('user_data')) {
            return $this->fail('Unauthorized', 401);
        }

        $booking = $this->eventModel->select('
                event_planner_event.*,
                user_profile.first_name as organizer_first_name,
                user_profile.last_name as organizer_last_name,
                user_credential.email as organizer_email,
                event_planner_location.lat,
                event_planner_location.long,
                event_planner_address.name as venue_name,
                event_planner_address.street_address,
                event_planner_address.barangay,
                event_planner_address.city,
                event_planner_address.country,
                event_planner_address.zip_code
            ')
            ->join('user_profile', 'user_profile.id = event_planner_event.event_organizer_id')
            ->join('user_credential', 'user_credential.user_profile_id = user_profile.id')
            ->join('event_planner_location', 'event_planner_location.id = event_planner_event.location_id')
            ->join('event_planner_address', 'event_planner_address.event_id = event_planner_event.id')
            ->where('event_planner_event.id', $id)
            ->first();

        if (!$booking) {
            return $this->fail('Booking not found');
        }

        // Format the data for the view
        $booking['organizer_name'] = $booking['organizer_first_name'] . ' ' . $booking['organizer_last_name'];
        $booking['venue_address'] = implode(', ', array_filter([
            $booking['street_address'],
            $booking['barangay'],
            $booking['city'],
            $booking['country'],
            $booking['zip_code']
        ]));

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

        $booking = $this->eventModel->where('id', $id)->first();

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
            $this->eventModel->update($id, [
                'status' => $json['status']
            ]);

            return $this->respond(['message' => 'Booking status updated successfully']);
        } catch (\Exception $e) {
            return $this->fail('Failed to update booking status: ' . $e->getMessage());
        }
    }

    public function cancel($id)
    {
        if (!$this->session->get('user_data')) {
            return $this->fail('Unauthorized', 401);
        }

        $booking = $this->eventModel->where('id', $id)->first();

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
            $this->eventModel->update($id, [
                'status' => 'cancelled'
            ]);

            return $this->respond(['message' => 'Booking cancelled successfully']);
        } catch (\Exception $e) {
            return $this->fail('Failed to cancel booking: ' . $e->getMessage());
        }
    }
} 