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
    protected $artistModel;
    protected $session;
    protected $email;

    public function __construct()
    {
        $this->userProfileModel = new UserProfileModel();
        $this->userCredentialModel = new UserCredentialModel();
        $this->artistModel = new ArtistModel();
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
                    venue.rent,
                    venue.id
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

    public function saveEvent()
{
    $session = session();
    $userId = $session->get('user_data')['user_id'] ?? null;

    if ($this->request->getMethod() === 'POST') {
        if (empty(session()->get('artist_data'))) {
            $session->setFlashdata('error', 'Please update your artist information before proceeding with bookings.');
            return redirect()->back();
        }

        // Load models
        $eventModel = new EventPerformance();
        $bookingModel = new BookingModel();

        // Get database connection
        $db = \Config\Database::connect();
        $db->transStart(); // Start Transaction

        // Prepare event data
        $eventData = [
            'venue_id'           => $this->request->getPost('venue_id'),
            'organizer_id'       => $userId,
            'event_name'         => $this->request->getPost('event_name'),
            'event_description'  => $this->request->getPost('description'),
            'event_startdate'    => $this->request->getPost('start_date'),
            'event_enddate'      => $this->request->getPost('end_date'),
            'event_status'       => 'Scheduled',
            'booking_status'     => 'Pending',
        ];

        // Insert event
        $eventInsert = $eventModel->insert($eventData);
        if (!$eventInsert) {
            $db->transRollback(); // Rollback on failure
            $errors = $eventModel->errors();
            log_message('error', 'Event insertion failed: ' . var_export($errors, true));
            $session->setFlashdata('error', 'Failed to insert event: ' . implode(', ', $errors));
            return redirect()->back();
        }

        $event_id = $eventModel->getInsertID();

        // Prepare booking data
        $bookingData = [
            'booking_event'   => $event_id,
            'artist'          => $userId,
            'booking_status'  => 'Pending',
        ];

        // Insert booking
        $bookingInsert = $bookingModel->insert($bookingData);
        if (!$bookingInsert) {
            $db->transRollback(); // Rollback on failure
            $errors = $bookingModel->errors();
            log_message('error', 'Booking insertion failed: ' . var_export($errors, true));
            $session->setFlashdata('error', 'Failed to insert booking: ' . implode(', ', $errors));
            return redirect()->back();
        }

        // Commit transaction if everything is successful
        $db->transComplete();

        if ($db->transStatus() === false) {
            // Something went wrong even after trying to complete
            $session->setFlashdata('error', 'Transaction failed. Please try again.');
            return redirect()->back();
        }

        $session->setFlashdata('success', 'Event created successfully!');
        return redirect()->to('/talents/events');
    }

    // Invalid method
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

        if ($this->request->getMethod() === 'POST') {
            $user_id = $this->session->get('user_data')['user_id'];
            $user = $this->userCredentialModel->find($user_id);
            
            if (!$user) {
                return $this->respond([
                    'success' => false,
                    'message' => 'User not found'
                ]);
            }

            // Get the raw input and decode it
            $json = $this->request->getJSON(true);
            
            if (!$json) {
                return $this->respond([
                    'success' => false,
                    'message' => 'Invalid request data'
                ]);
            }

            // Start database transaction
            $db = \Config\Database::connect();
            $db->transStart();

            try {
                // Update user profile
                $profileData = [
                    'first_name' => $json['first_name'],
                    'middle_name' => $json['middle_name'] ?? '',
                    'last_name' => $json['last_name'],
                    'birthdate' => $json['birthdate']
                ];

                $profileUpdated = $this->userProfileModel->update($user['user_profile_id'], $profileData);
                
                if (!$profileUpdated) {
                    throw new \Exception('Failed to update profile information');
                }

                // Update artist info
                $artistModel = new ArtistModel();
                $artist = $artistModel->where('performer', $user_id)->first();
                
                $artistData = [
                    'artist_name' => $json['artist_name'],
                    'price_range' => $json['talent_fee'],
                    'hours' => $json['base_rate'],
                    'payment_option' => $json['mode_of_payments'],
                    'performer' => $user_id
                ];

                if ($artist) {
                    $artistUpdated = $artistModel->update($artist['id'], $artistData);
                } else {
                    $artistUpdated = $artistModel->insert($artistData);
                }

                if (!$artistUpdated) {
                    throw new \Exception('Failed to update artist information');
                }

                $db->transComplete();

                if ($db->transStatus() === false) {
                    throw new \Exception('Transaction failed');
                }

                $user_profile = $this->userProfileModel->find($user['id']);

                $artistData = [];
                if ($user['user_type'] === 'artist') {
                    $artistData = $this->artistModel->where('performer', $user['id'])->first();
                    log_message('debug', json_encode($artistData));
                }
                
                $this->session->set('user_data', [
                    'id' => $user['user_profile_id'],
                    'user_id' => $user_profile['id'],
                    'email' => $user['email'],
                    'user_type' => $user['user_type'],
                    'artist_data' => $artistData,
                ]);

                return $this->respond([
                    'success' => true,
                    'message' => 'Profile updated successfully!'
                ]);

            } catch (\Exception $e) {
                $db->transRollback();
                return $this->respond([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
        }

        $user_id = $this->session->get('user_data')['user_id'];
        
        // Get user credential with profile information
        $user_credential = $this->userCredentialModel->find($user_id);
        if (!$user_credential) {
            return redirect()->to('login');
        }

        // Get user profile information
        $user_profile = $this->userProfileModel->find($user_credential['user_profile_id']);
        if (!$user_profile) {
            return redirect()->to('login');
        }

        // Get artist information
        $artistModel = new ArtistModel();
        $artist = $artistModel->where('performer', $user_id)->first();

        $data = [
            'user_credential' => [
                'id' => $user_credential['id'],
                'email' => $user_credential['email'],
                'user_type' => $user_credential['user_type'],
                'user_profile' => [
                    'first_name' => $user_profile['first_name'],
                    'middle_name' => $user_profile['middle_name'],
                    'last_name' => $user_profile['last_name'],
                    'birthdate' => $user_profile['birthdate'],
                    'image_path' => $user_profile['image_path']
                ],
                'artist' => $artist ? [
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
