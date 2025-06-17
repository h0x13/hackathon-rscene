<?php

namespace App\Controllers;

use App\Models\EventPlannerLocation;
use App\Models\EventPlannerAddress;
use App\Models\EventPlannerEvent;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class VenueController extends ResourceController
{
    use ResponseTrait;

    protected $locationModel;
    protected $addressModel;
    protected $eventModel;
    protected $session;

    public function __construct()
    {
        $this->locationModel = new EventPlannerLocation();
        $this->addressModel = new EventPlannerAddress();
        $this->eventModel = new EventPlannerEvent();
        $this->session = \Config\Services::session();
    }

    public function dashboard()
    {
        if (!$this->session->get('user_data')) {
            return redirect()->to('login');
        }

        return view('pages/venue_owners/dashboard');
    }

    public function list()
    {
        if (!$this->session->get('user_data')) {
            return redirect()->to('login');
        }

        $user_id = $this->session->get('user_data')['user_id'];
        
        $venues = $this->locationModel->select('
                event_planner_location.id,
                event_planner_location.lat,
                event_planner_location.long,
                event_planner_address.name,
                event_planner_address.description,
                event_planner_address.street_address,
                event_planner_address.barangay,
                event_planner_address.city,
                event_planner_address.country,
                event_planner_address.zip_code
            ')
            ->join('event_planner_address', 'event_planner_address.event_id = event_planner_location.id')
            ->findAll();

        if ($this->request->isAJAX()) {
            return $this->respond(['venues' => $venues]);
        }

        return view('pages/venue_owners/venues', ['venues' => $venues]);
    }

    public function add()
    {
        if (!$this->session->get('user_data')) {
            return redirect()->to('login')->with('error', 'Unauthorized');
        }

        $user_id = $this->session->get('user_data')['user_id'];

        // Get form data
        $data = $this->request->getPost();

        // Validate required fields
        $required_fields = ['name', 'description', 'lat', 'long', 'street_address', 'barangay', 'city', 'country', 'zip_code'];
        foreach ($required_fields as $field) {
            if (empty($data[$field])) {
                return redirect()->back()->with('error', "Field {$field} is required");
            }
        }

        // Start transaction
        $this->locationModel->db->transStart();

        try {
            // Add location
            $location_data = [
                'lat' => (float)$data['lat'],
                'long' => (float)$data['long']
            ];
            $location_id = $this->locationModel->insert($location_data);
            
            if (!$location_id) {
                throw new \Exception('Failed to add venue location');
            }

            // Add address
            $address_data = [
                'event_id' => $location_id,
                'name' => trim($data['name']),
                'description' => trim($data['description']),
                'street_address' => trim($data['street_address']),
                'barangay' => trim($data['barangay']),
                'city' => trim($data['city']),
                'country' => trim($data['country']),
                'zip_code' => trim($data['zip_code'])
            ];

            // Validate address data
            if (!$this->addressModel->validate($address_data)) {
                throw new \Exception(implode(', ', $this->addressModel->errors()));
            }

            $address_id = $this->addressModel->insert($address_data);
            
            if (!$address_id) {
                throw new \Exception('Failed to add venue address');
            }

            $this->locationModel->db->transComplete();

            if ($this->locationModel->db->transStatus() === false) {
                return redirect()->back()->with('error', 'Failed to add venue');
            }

            return redirect()->to('venue/list')->with('success', 'Venue added successfully');
        } catch (\Exception $e) {
            $this->locationModel->db->transRollback();
            log_message('error', 'Venue add error: ' . $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function view($id)
    {
        if (!$this->session->get('user_data')) {
            return $this->fail('Unauthorized', 401);
        }

        $user_id = $this->session->get('user_data')['user_id'];
        
        $venue = $this->eventModel->select('
                event_planner_event.id,
                event_planner_event.event_name as name,
                event_planner_event.event_description as description,
                event_planner_event.status,
                event_planner_location.lat,
                event_planner_location.long,
                event_planner_address.name as venue_name,
                event_planner_address.street_address,
                event_planner_address.barangay,
                event_planner_address.city,
                event_planner_address.country,
                event_planner_address.zip_code
            ')
            ->join('event_planner_location', 'event_planner_location.id = event_planner_event.location_id')
            ->join('event_planner_address', 'event_planner_address.event_id = event_planner_event.id')
            ->where('event_planner_event.id', $id)
            ->where('event_planner_event.event_organizer_id', $user_id)
            ->first();

        if (!$venue) {
            return $this->fail('Venue not found');
        }

        return $this->respond(['venue' => $venue]);
    }

    public function edit($id = null)
    {
        if (!$this->session->get('user_data')) {
            return redirect()->to('login')->with('error', 'Unauthorized');
        }

        if ($id === null) {
            return redirect()->back()->with('error', 'Venue ID is required');
        }

        $user_id = $this->session->get('user_data')['user_id'];
        $data = $this->request->getPost();

        // Validate required fields
        $required_fields = ['name', 'description', 'lat', 'long', 'street_address', 'barangay', 'city', 'country', 'zip_code'];
        foreach ($required_fields as $field) {
            if (empty($data[$field])) {
                return redirect()->back()->with('error', "Field {$field} is required");
            }
        }

        // Get venue details
        $venue = $this->locationModel->find($id);

        if (!$venue) {
            return redirect()->back()->with('error', 'Venue not found');
        }

        // Start transaction
        $this->locationModel->db->transStart();

        try {
            // Update location
            $location_data = [
                'lat' => (float)$data['lat'],
                'long' => (float)$data['long']
            ];
            $this->locationModel->update($id, $location_data);

            // Update address
            $address_data = [
                'name' => trim($data['name']),
                'description' => trim($data['description']),
                'street_address' => trim($data['street_address']),
                'barangay' => trim($data['barangay']),
                'city' => trim($data['city']),
                'country' => trim($data['country']),
                'zip_code' => trim($data['zip_code'])
            ];

            // Validate address data
            if (!$this->addressModel->validate($address_data)) {
                throw new \Exception(implode(', ', $this->addressModel->errors()));
            }

            // Update address using location_id instead of event_id
            $this->addressModel->where('event_id', $id)->set($address_data)->update();

            $this->locationModel->db->transComplete();

            if ($this->locationModel->db->transStatus() === false) {
                return redirect()->back()->with('error', 'Failed to update venue');
            }

            return redirect()->to('venue/list')->with('success', 'Venue updated successfully');
        } catch (\Exception $e) {
            $this->locationModel->db->transRollback();
            log_message('error', 'Venue update error: ' . $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function delete($id = null)
    {
        if (!$this->session->get('user_data')) {
            return redirect()->to('login')->with('error', 'Unauthorized');
        }

        if ($id === null) {
            return redirect()->back()->with('error', 'Venue ID is required');
        }

        $user_id = $this->session->get('user_data')['user_id'];
        $venue = $this->locationModel->find($id);

        if (!$venue) {
            return redirect()->back()->with('error', 'Venue not found');
        }

        $this->locationModel->db->transStart();

        try {
            $this->addressModel->where('event_id', $id)->delete();
            $this->locationModel->delete($id);

            $this->locationModel->db->transComplete();

            if ($this->locationModel->db->transStatus() === false) {
                return redirect()->back()->with('error', 'Failed to delete venue');
            }

            return redirect()->to('venue/list')->with('success', 'Venue deleted successfully');
        } catch (\Exception $e) {
            $this->locationModel->db->transRollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
} 