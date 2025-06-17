<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EventPerformance;
use App\Models\VenueModel;
use App\Models\VenuePin;
use App\Models\BookingModel;

class TalentController extends BaseController
{
    public function home()
    {
        $session = session();
        $userId = $session->get('user_data')['user_id'] ?? null;
        $Id = session()->get('user_data')['id'] ?? null;
        // $userId = 'test';
        // $Id = 1;


        $eventModel = new EventPerformance();
        $events = $eventModel
            ->select('
                event_performance.id,
                event_performance.event_name,
                event_performance.event_description,
                event_performance.event_startdate,
                event_performance.event_status,
                vp.lat,
                vp.lon as lng,
                v.street,
                v.barangay,
                v.city,
                v.zip_code,
                v.rent
            ')
            ->join('venue v', 'v.id = event_performance.venue_id', 'left')
            ->join('venue_pin vp', 'v.pin_id = vp.id', 'left')
            ->where('event_performance.event_startdate >=', date('Y-m-d'))
            ->orderBy('event_performance.event_startdate', 'ASC')
            ->limit(3)
            ->findAll();

        $eventForMap = $eventModel
            ->select('
                event_performance.id,
                event_performance.event_name,
                event_performance.event_description,
                event_performance.event_startdate,
                event_performance.event_status,
                vp.lat,
                vp.lon as lng,
                v.street,
                v.barangay,
                v.city,
                v.zip_code
            ')
            ->join('venue v', 'v.id = event_performance.venue_id', 'left')
            ->join('venue_pin vp', 'v.pin_id = vp.id', 'left')
            ->where('event_performance.event_startdate >=', date('Y-m-d'))
            ->findAll();

        $data = [
            'user_id' => $userId,
            'id' => $Id,
            'events' => $events,
            'eventsForMap' => $eventForMap,
        ];

        return view('pages/talents/home', $data);
    }

    public function events()
    {
            $session = session();
            $userId = $session->get('user_data')['user_id'] ?? null;

            $eventModel = new EventPerformance();
            $builder = $eventModel
                ->select('
                    event_performance.id,
                    event_performance.event_name,
                    event_performance.event_description,
                    event_performance.event_startdate,
                    event_performance.event_status,
                    venue_pin.lat,
                    venue_pin.lon as lng,
                    venue.street,
                    venue.barangay,
                    venue.city,
                    venue.zip_code,
                    venue.rent
                ')
                ->join('venue', 'venue.id = event_performance.venue_id', 'left')
                ->join('venue_pin', 'venue.pin_id = venue_pin.id', 'left')
                ->where('event_performance.organizer_id', $userId)
                ->where('event_performance.event_startdate >=', date('Y-m-d'))
                ->orderBy('event_performance.event_startdate', 'ASC')
                ->limit(3);

            $events = $builder->findAll();
            $venues = [
            [
                'name' => 'Tacloban City Convention Center',
                'lat' => 11.2445,
                'lng' => 125.0036,
                'street' => 'San Jose',
                'barangay' => 'Barangay 2',
                'city' => 'Tacloban City',
                'province' => 'Leyte',
                'zip_code' => '5720',
                'description' => 'A large venue for concerts and events.',
                'rent' => 5000
            ],
            [
                'name' => 'Ormoc Superdome',
                'lat' => 15.0157,
                'lng' => 124.6075,
                'street' => 'San Jose',
                'barangay' => 'Barangay 2',
                'city' => 'Calbalogan City',
                'province' => 'Leyte',
                'zip_code' => '5720',
                'description' => 'Indoor arena for sports and music events.',
                'rent' => 6000
            ],
            [
                'name' => 'Ormoc Grand Pavilion',
                'lat' => 11.0157,
                'lng' => 124.6075,
                'street' => 'Real Street',
                'barangay' => 'Barangay Dolores',
                'city' => 'Calbalogan City',
                'province' => 'Leyte',
                'zip_code' => '5720',
                'description' => 'Indoor arena for sports and music events.',
                'rent' => 7000
            ],

        ];

        $data = [
            'events' => $events,
            'venues' => $venues
        ];  
        return view('pages/talents/events', $data);
    }

    public function profile(){
        return view('pages/talents/profile');
    }

    public function venues()
    {
        // For now, use sample data. Replace with DB query later.
        $venues = [
            [
                'name' => 'Tacloban City Convention Center',
                'lat' => 11.2445,
                'lng' => 125.0036,
                'address' => 'Tacloban City, Leyte',
                'description' => 'A large venue for concerts and events.'
            ],
            [
                'name' => 'Ormoc Superdome',
                'lat' => 11.0157,
                'lng' => 124.6075,
                'address' => 'Ormoc City, Leyte',
                'description' => 'Indoor arena for sports and music events.'
            ]
        ];
        return view('pages/talents/venues', ['venues' => $venues]);
    }

    public function saveEvent(){
        $session = session();
        $userId = $session->get('user_data')['user_id'] ?? null;
        if ($this->request->getMethod() === 'POST') {
            // 1. Save Location
            $locationModel = new VenuePin();
            $locationData = [
                'lon' => $this->request->getPost('lang'),
                'lat'  => $this->request->getPost('lat'),
            ];
            $locationInsert = $locationModel->insert($locationData);
            log_message('debug', 'Location insert result: ' . var_export($locationInsert, true));
            if (!$locationInsert) {
                $session->setFlashdata('error', 'Failed to insert location.');
                return redirect()->back();
            }
            $location_id = $locationModel->getInsertID();

            // 2. Save Venue Information
            $venueModel = new VenueModel();
            $addressData = [
                'venue_name'     => $this->request->getPost('event_name'),
                'pin_id'         => $location_id,
                'owner_profile' => $userId,
                'venue_description'    => $this->request->getPost('description'),
                'street_address' => $this->request->getPost('street_address'),
                'barangay'       => $this->request->getPost('barangay'),
                'city'           => $this->request->getPost('city'),
                'country'        => 'Philippines',
                'zip_code'       => $this->request->getPost('zip_code'),
            ];
            $addressInsert = $venueModel->insert($addressData);


            // 2. Save Event
            $eventModel = new EventPerformance();
            $eventData = [
                'event_name'          => $this->request->getPost('event_name'),
                'event_description'   => $this->request->getPost('description'),
                'organizer_id'        => $userId,
                'event_startdate'     => $this->request->getPost('start_date'),
                'event_enddate'       => $this->request->getPost('end_date'),
                'event_status'        => 'Scheduled',
                'booking_status'      => 'Pending',
            ];
            
            log_message('debug', 'Event description length before insert: ' . strlen($eventData['event_description']));
            $eventInsert = $eventModel->insert($eventData);
            log_message('debug', 'Event insert result: ' . var_export($eventInsert, true));
            if (!$eventInsert) {
                $errors = $eventModel->errors();
                if (!empty($errors)) {
                    $session->setFlashdata('error', 'Failed to insert event: ' . implode(', ', $errors));
                } else {
                    $session->setFlashdata('error', 'Failed to insert event due to an unknown error.');
                }
                log_message('error', 'Event insertion failed: ' . var_export($errors, true));
                return redirect()->back();
            }
            $event_id = $eventModel->getInsertID();

            $booking = new BookingModel();
            $bookingData = [
                'booking_event' => $event_id,
                'artist' => $userId,
                'booking_status' => 'Pending',
            ];

            $bookingInsert = $booking->insert($bookingData);
            $bookingId = $booking->getInsertID();
            
            log_message('debug', 'Address insert result: ' . var_export($addressInsert, true));
            if (!$addressInsert) {
                $errors = $venueModel->errors();
                if (!empty($errors)) {
                    $session->setFlashdata('error', 'Failed to insert address: ' . implode(', ', $errors));
                } else {
                    $session->setFlashdata('error', 'Failed to insert address due to an unknown error.');
                }
                log_message('error', 'Address insertion failed: ' . var_export($errors, true));
                return redirect()->back();
            }

            $session->setFlashdata('success', 'Event created successfully!');
            return redirect()->to('/talents/events');
        }

        $session->setFlashdata('error', 'Failed to create event. Please try again.');
        return redirect()->back()->with('error', 'Invalid request.');
    }

    public function allEvents()
    {
        $eventModel = new EventPerformance();
        $events = $eventModel
            ->select('
                event_performance.id,
                event_performance.event_name,
                event_performance.event_description,
                event_performance.event_startdate,
                event_performance.event_status,
                venue_pin.lat,
                venue_pin.lon as lng,
                venue.street as street,
                venue.barangay,
                venue.city,
                venue.zip_code
            ')
            ->join('venue', 'venue.id = event_performance.venue_id', 'left')
            ->join('venue_pin', 'venue.pin_id = venue_pin.id', 'left')
            ->where('event_performance.event_startdate >=', date('Y-m-d')) // Only future events
            ->orderBy('event_performance.event_startdate', 'ASC') // Soonest first
            ->findAll();

        return view('pages/talents/all_events', ['events' => $events]);
    }

    public function talentsEvents()
    {
        $session = session();
        $userId = $session->get('user_data')['user_id'] ?? null;
        $Id = session()->get('user_data')['id'] ?? null;
        $eventModel = new EventPerformance();
        $events = $eventModel
            ->select('
                event_performance.id,
                event_performance.event_name,
                event_performance.event_description,
                event_performance.event_startdate,
                event_performance.event_status,
                venue_pin.lat,
                venue_pin.lon as lng,
                venue.street,
                venue.barangay,
                venue.city,
                venue.zip_code
            ')
            ->join('venue', 'venue.id = event_performance.venue_id', 'left')
            ->join('venue_pin', 'venue.pin_id = venue_pin.id', 'left')
            ->where('event_performance.organizer_id', $userId)
            ->findAll();

        return view('pages/talents/talents_event', ['events' => $events]);
    }
}
