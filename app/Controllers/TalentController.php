<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EventPerformance;
use App\Models\VenueModel;
use App\Models\VenuePin;
use App\Models\BookingModel;
use App\Models\UserProfileModel;
use App\Models\UserCredentialModel;
use App\Models\ArtistModel;
use CodeIgniter\API\ResponseTrait;


class TalentController extends BaseController
{
    use ResponseTrait;

    protected $userProfileModel;
    protected $userCredentialModel;
    protected $session;
    protected $email;

    public function __construct()
    {
        $this->userProfileModel = new UserProfileModel();
        $this->userCredentialModel = new UserCredentialModel();
        $this->session = \Config\Services::session();
        $this->email = \Config\Services::email();
    }
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

            $venueModel = new VenueModel();
            $venues = $venueModel
                ->select('
                    venue.id,
                    venue.venue_name,
                    venue.venue_description,
                    venue.street,
                    venue.barangay,
                    venue.city,
                    venue.zip_code,
                    venue.rent,
                    venue.capacity,
                    venue.owner_profile,
                    venue_pin.lat,
                    venue_pin.lon as lng
                ')
                ->join('venue_pin', 'venue.pin_id = venue_pin.id')
                ->findAll();

        $data = [
            'events' => $events,
            'venues' => $venues
        ];  
        return view('pages/talents/events', $data);
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
            
            // 1. Save Event
            $eventModel = new EventPerformance();
            $eventData = [
                'venue_id'           => $this->request->getPost('venue_id'),
                'organizer_id'        => $userId,
                'event_name'          => $this->request->getPost('event_name'),
                'event_description'   => $this->request->getPost('description'),
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

    public function profile(){
        if (!$this->session->get('user_data')) {
            return redirect()->to('login');
        }
        log_message('debug', 'Profile update POST data: ');

        if ($this->request->getMethod() === 'POST') {
            $json = $this->request->getJSON(true);
            $user_id = $this->session->get('user_data')['user_id'];
            $user = $this->userCredentialModel->find($user_id);
            
            // Debug: Log received JSON
            log_message('debug', 'Profile update POST data: ' . json_encode($json));


            $this->userProfileModel->update($user['user_profile_id'], [
                'first_name' => $json['first_name'],
                'middle_name' => $json['middle_name'] ?? null,
                'last_name' => $json['last_name'],
                'birthdate' => $json['birthdate']
            ]);

            log_message('debug', 'Updated user_profile_id: ' . $user['user_profile_id']);


            // Update artist info (artist_name, talent_fee, base_rate, mode_of_payments)
            // Adjust model/fields as per your schema
            if (isset($json['artist_name']) || isset($json['talent_fee']) || isset($json['base_rate']) || isset($json['mod'])) {
                $artistModel = new ArtistModel();
                $artist = $artistModel->where('performer', $user_id)->first();
                $artistData = [
                    'artist_name' => $json['artist_name'] ?? null,
                    'price_range' => $json['talent_fee'] ?? null,
                    'hours' => $json['base_rate'] ?? null,
                    'payment_option' => $json['mod'] ?? null,
                    'performer' => $user_id
                ];
                
                log_message('debug', 'Artist data to save: ' . json_encode($artistData));

                if ($artist) {
                    // Update existing artist record
                    $artistModel->update($artist['id'], $artistData);
                } else {
                    // Insert new artist record
                    $artistModel->insert($artistData);
                }
            }

            return $this->respond([
                'success' => true,
                'message' => 'Profile updated successfully!'
            ]);
        }

        $user_id = $this->session->get('user_data')['user_id'];
        $user_credential = $this->userCredentialModel->with('user_profile')->find($user_id);

        if (!$user_credential) {
            return redirect()->to('login');
        }

        $artistModel = new ArtistModel();
        $artist = $artistModel->where('performer', $user_id)->first();

        // Format the data for the view
        $data = [
            'user_credential' => [
                'id' => $user_credential['id'],
                'email' => $user_credential['email'],
                'user_type' => $user_credential['user_type'],
                'user_profile' => [
                    'first_name' => $user_credential['first_name'],
                    'middle_name' => $user_credential['middle_name'],
                    'last_name' => $user_credential['last_name'],
                    'birthdate' => $user_credential['birthdate'],
                    'image_path' => $user_credential['image_path']
                ],
                // Add artist info to the view data
                'artist' => isset($artist) ? [
                    'artist_name' => $artist['artist_name'],
                    'talent_fee' => $artist['price_range'],
                    'base_rate' => $artist['hours'],
                    'mode_of_payments' => $artist['payment_option']
                ] : null
            ]
        ];
        
        return view('pages/talents/profile', $data);
    }
}
