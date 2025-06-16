<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EventPlannerAddress;
use App\Models\EventPlannerEvent;
use App\Models\EventPlannerLocation;

class TalentController extends BaseController
{
    public function home()
    {
        $eventModel = new EventPlannerEvent();
        $events = $eventModel
            ->select('
                event_planner_event.id,
                event_planner_event.event_name,
                event_planner_event.event_description,
                event_planner_event.event_date,
                event_planner_event.status,
                event_planner_location.lat,
                event_planner_location.long as lng,
                event_planner_address.street_address as street,
                event_planner_address.barangay,
                event_planner_address.city,
                event_planner_address.country,
                event_planner_address.zip_code
            ')
            ->join('event_planner_location', 'event_planner_location.id = event_planner_event.location_id', 'left')
            ->join('event_planner_address', 'event_planner_address.event_id = event_planner_event.id', 'left')
            ->where('event_planner_event.event_date >=', date('Y-m-d')) // Only future events
            ->orderBy('event_planner_event.event_date', 'ASC') // Soonest first
            ->limit(3)
            ->findAll();

        return view('pages/talents/home', ['events' => $events]);
    }

    public function events()
    {
            $userId = session()->get('user_id'); // Adjust this to your session key

            // // Query: Join event, location, and address tables
            $eventModel = new EventPlannerEvent();
            $builder = $eventModel
                ->select('
                    event_planner_event.id,
                    event_planner_event.event_name,
                    event_planner_event.event_description,
                    event_planner_event.event_date,
                    event_planner_event.status,
                    event_planner_location.lat,
                    event_planner_location.long as lng,
                    event_planner_address.street_address as street,
                    event_planner_address.barangay,
                    event_planner_address.city,
                    event_planner_address.country,
                    event_planner_address.zip_code,
                ')
                ->join('event_planner_location', 'event_planner_location.id = event_planner_event.location_id', 'left')
                ->join('event_planner_address', 'event_planner_address.event_id = event_planner_event.id', 'left')
                ->where('event_planner_event.event_organizer_id', $userId);

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
                'description' => 'A large venue for concerts and events.'
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
                'description' => 'Indoor arena for sports and music events.'
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
                'description' => 'Indoor arena for sports and music events.'
            ],
            [
                'name' => 'Calbayog Arena',
                'lat' => 11.7745,
                'lng' => 124.8927,
                'street' => 'Rizal Avenue',
                'barangay' => 'Barangay Obrero',
                'city' => 'Calbalogan City',
                'province' => 'Leyte',
                'zip_code' => '5720',
                'description' => 'Indoor arena for sports and music events.'
            ],
            [
                'name' => 'Eastern Visayas Expo Center',
                'lat' => 11.7782,
                'lng' => 124.8927,
                'street' => 'Samar Street',
                'barangay' => 'Barangay Capoocan',
                'city' => 'Calbalogan City',
                'province' => 'Leyte',
                'zip_code' => '5720',
                'description' => 'Indoor arena for sports and music events.'
            ],
            [
                'name' => 'Tacloban Event Hall',
                'lat' => 11.7693,
                'lng' => 124.8824,
                'street' => 'Justice Romualdez St.',
                'barangay' => 'Barangay Payapay',
                'city' => 'Calbalogan City',
                'province' => 'Leyte',
                'zip_code' => '5720',
                'description' => 'Indoor arena for sports and music events.'
            ],
            [
                'name' => 'Baybay City Coliseum',
                'lat' => 11.7718,
                'lng' => 124.8899,
                'street' => 'Bonifacio Street',
                'barangay' => 'Barangay Guinsorongan',
                'city' => 'Calbalogan City',
                'province' => 'Leyte',
                'zip_code' => '5720',
                'description' => 'Indoor arena for sports and music events.'
            ],
            [
                'name' => 'Palo Convention Hall',
                'lat' => 11.7760,
                'lng' => 124.8942,
                'street' => 'J. Rizal Street',
                'barangay' => 'Barangay Rawis',
                'city' => 'Calbalogan City',
                'province' => 'Leyte',
                'zip_code' => '5720',
                'description' => 'Indoor arena for sports and music events.'
            ],
            [
                'name' => 'Leyte Civic Center',
                'lat' => 11.7671,
                'lng' => 124.8878,
                'street' => 'Magsaysay Blvd',
                'barangay' => 'Barangay Maulong',
                'city' => 'Calbalogan City',
                'province' => 'Leyte',
                'zip_code' => '5720',
                'description' => 'Indoor arena for sports and music events.'
            ],
            [
                'name' => 'Samar Sports Dome',
                'lat' => 11.7733,
                'lng' => 124.8805,
                'street' => 'Pedro Rosales St.',
                'barangay' => 'Barangay Mercedes',
                'city' => 'Calbalogan City',
                'province' => 'Leyte',
                'zip_code' => '5720',
                'description' => 'Indoor arena for sports and music events.'
            ],
            [
                'name' => 'Visayas Performance Hall',
                'lat' => 11.7702,
                'lng' => 124.8913,
                'street' => 'Arellano Street',
                'barangay' => 'Barangay San Andres',
                'city' => 'Calbalogan City',
                'province' => 'Leyte',
                'zip_code' => '5720',
                'description' => 'Indoor arena for sports and music events.'
            ],
            [
                'name' => 'Ormoc Event Center',
                'lat' => 11.7742,
                'lng' => 124.8847,
                'street' => 'Sta. Margarita Road',
                'barangay' => 'Barangay Central',
                'city' => 'Calbalogan City',
                'province' => 'Leyte',
                'zip_code' => '5720',
                'description' => 'Indoor arena for sports and music events.'
            ],
            [
                'name' => 'Super Arena Leyte',
                'lat' => 11.7738,
                'lng' => 124.8863,
                'street' => 'San Francisco Street',
                'barangay' => 'Barangay Rizal',
                'city' => 'Calbalogan City',
                'province' => 'Leyte',
                'zip_code' => '5720',
                'description' => 'Indoor arena for sports and music events.'
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
    if ($this->request->getMethod() === 'POST') {
        // 1. Save Location
        $locationModel = new EventPlannerLocation();
        $locationData = [
            'long' => $this->request->getPost('lang'),
            'lat'  => $this->request->getPost('lat'),
        ];
        $locationInsert = $locationModel->insert($locationData);
        log_message('debug', 'Location insert result: ' . var_export($locationInsert, true));
        if (!$locationInsert) {
            $session->setFlashdata('error', 'Failed to insert location.');
            return redirect()->back();
        }
        $location_id = $locationModel->getInsertID();

        // 2. Save Event
        $eventModel = new EventPlannerEvent();
        $eventData = [
            'location_id'         => $location_id,
            'event_name'          => $this->request->getPost('event_name'),
            'event_description'   => $this->request->getPost('description'),
            'event_organizer_id'  => 1,
            'event_date'          => $this->request->getPost('event_date'),
            'status'              => 'pending',
        ];
        $eventInsert = $eventModel->insert($eventData);
        log_message('debug', 'Event insert result: ' . var_export($eventInsert, true));
        if (!$eventInsert) {
            $session->setFlashdata('error', 'Failed to insert event.');
            return redirect()->back();
        }
        $event_id = $eventModel->getInsertID();

        // 3. Save Address
        $addressModel = new EventPlannerAddress();
        $addressData = [
            'event_id'       => $event_id,
            'street_address' => $this->request->getPost('street_address'),
            'barangay'       => $this->request->getPost('barangay'),
            'city'           => $this->request->getPost('city'),
            'country'        => 'Philippines',
            'zip_code'       => $this->request->getPost('zip_code'),
        ];
        $addressInsert = $addressModel->insert($addressData);
        log_message('debug', 'Address insert result: ' . var_export($addressInsert, true));
        if (!$addressInsert) {
            $session->setFlashdata('error', 'Failed to insert address.');
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
        $eventModel = new EventPlannerEvent();
        $events = $eventModel
            ->select('
                event_planner_event.id,
                event_planner_event.event_name,
                event_planner_event.event_description,
                event_planner_event.event_date,
                event_planner_event.status,
                event_planner_location.lat,
                event_planner_location.long as lng,
                event_planner_address.street_address as street,
                event_planner_address.barangay,
                event_planner_address.city,
                event_planner_address.country,
                event_planner_address.zip_code
            ')
            ->join('event_planner_location', 'event_planner_location.id = event_planner_event.location_id', 'left')
            ->join('event_planner_address', 'event_planner_address.event_id = event_planner_event.id', 'left')
            ->where('event_planner_event.event_date >=', date('Y-m-d')) // Only future events
            ->orderBy('event_planner_event.event_date', 'ASC') // Soonest first
            ->findAll();

        return view('pages/talents/all_events', ['events' => $events]);
    }

    public function talentsEvents()
    {
        $eventModel = new EventPlannerEvent();
        $events = $eventModel
            ->select('
                event_planner_event.id,
                event_planner_event.event_name,
                event_planner_event.event_description,
                event_planner_event.event_date,
                event_planner_event.status,
                event_planner_location.lat,
                event_planner_location.long as lng,
                event_planner_address.street_address as street,
                event_planner_address.barangay,
                event_planner_address.city,
                event_planner_address.country,
                event_planner_address.zip_code
            ')
            ->join('event_planner_location', 'event_planner_location.id = event_planner_event.location_id', 'left')
            ->join('event_planner_address', 'event_planner_address.event_id = event_planner_event.id', 'left')
            ->where('event_planner_event.event_organizer_id', 1) // Assuming 1 is the logged-in user ID
            ->findAll();

        return view('pages/talents/talents_event', ['events' => $events]);
    }
}