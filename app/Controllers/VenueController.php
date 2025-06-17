<?php

namespace App\Controllers;

use App\Models\VenueModel;
use App\Models\VenuePin;
use App\Models\ImageModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class VenueController extends ResourceController
{
    use ResponseTrait;

    protected $venueModel;
    protected $venuePinModel;
    protected $imageModel;
    protected $session;

    public function __construct()
    {
        $this->venueModel = new VenueModel();
        $this->venuePinModel = new VenuePin();
        $this->imageModel = new ImageModel();
        $this->session = \Config\Services::session();
        helper('image');
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
        
        $venues = $this->venueModel->select('venue.*, venue_pin.lat, venue_pin.lon, venue_image.image_path')
            ->join('venue_pin', 'venue_pin.id = venue.pin_id')
            ->join('venue_image', 'venue_image.venue_id = venue.id', 'left')
            ->where('venue.owner_profile', $user_id)
            ->findAll();

        if ($this->request->isAJAX()) {
            return $this->respond(['venues' => $venues]);
        }

        return view('pages/venue_owners/venues', ['venues' => $venues]);
    }

    protected function handleImageUpload($venue_id, $existing_image_id = null)
    {
        $image = $this->request->getFile('venue_image');
        
        // If no new image uploaded, return existing image_id
        if (!$image || !$image->isValid() || $image->hasMoved()) {
            return $existing_image_id;
        }

        // Validate image
        $validationRule = [
            'venue_image' => [
                'label' => 'Venue Image',
                'rules' => [
                    'uploaded[venue_image]',
                    'is_image[venue_image]',
                    'mime_in[venue_image,image/jpg,image/jpeg,image/png,image/webp]',
                    'max_size[venue_image,2048]', // 2MB max
                ],
            ],
        ];

        if (!$this->validate($validationRule)) {
            throw new \Exception(implode(', ', $this->validator->getErrors()));
        }

        // Delete old image if exists
        if ($existing_image_id) {
            $old_image = $this->imageModel->find($existing_image_id);
            if ($old_image) {
                delete_image($old_image['image_path']);
                $this->imageModel->delete($existing_image_id);
            }
        }

        // Save new image
        $image_path = save_image($image->getFileInfo());
        if (!$image_path) {
            throw new \Exception('Failed to save image file');
        }

        // Create image record
        $image_data = [
            'name' => $image->getClientName(),
            'image_path' => $image_path,
            'venue_id' => $venue_id
        ];

        $image_id = $this->imageModel->insert($image_data);
        if (!$image_id) {
            // Clean up the uploaded file if database insert fails
            delete_image($image_path);
            throw new \Exception('Failed to create image record');
        }

        return $image_id;
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
        $required_fields = ['venue_name', 'venue_description', 'street', 'barangay', 'city', 'zip_code', 'rent', 'capacity', 'lat', 'lon'];
        foreach ($required_fields as $field) {
            if (empty($data[$field])) {
                return redirect()->back()->with('error', "Field {$field} is required");
            }
        }

        // Start transaction
        $this->venueModel->db->transStart();

        try {
            // Add Venue Pin
            $venue_pin_data = [
                'lat' => (float)$data['lat'],
                'lon' => (float)$data['lon']
            ];
            $pin_id = $this->venuePinModel->insert($venue_pin_data);
            
            if (!$pin_id) {
                throw new \Exception('Failed to add venue location');
            }

            // Add Venue
            $venue_data = [
                'pin_id' => $pin_id,
                'owner_profile' => $user_id,
                'venue_name' => trim($data['venue_name']),
                'venue_description' => trim($data['venue_description']),
                'street' => trim($data['street']),
                'barangay' => trim($data['barangay']),
                'city' => trim($data['city']),
                'rent' => trim($data['rent']),
                'zip_code' => trim($data['zip_code']),
                'capacity' => trim($data['capacity'])
            ];

            // Validate venue data
            if (!$this->venueModel->validate($venue_data)) {
                throw new \Exception(implode(', ', $this->venueModel->errors()));
            }

            $venue_id = $this->venueModel->insert($venue_data);
            
            if (!$venue_id) {
                throw new \Exception('Failed to add venue');
            }

            // Handle image upload
            try {
                $this->handleImageUpload($venue_id);
            } catch (\Exception $e) {
                log_message('error', 'Image upload error: ' . $e->getMessage());
                // Continue with venue creation even if image upload fails
            }

            $this->venueModel->db->transComplete();

            if ($this->venueModel->db->transStatus() === false) {
                return redirect()->back()->with('error', 'Failed to add venue');
            }

            return redirect()->to('venue/list')->with('success', 'Venue added successfully');
        } catch (\Exception $e) {
            $this->venueModel->db->transRollback();
            log_message('error', 'Venue add error: ' . $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
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
        $required_fields = ['venue_name', 'venue_description', 'street', 'barangay', 'city', 'zip_code', 'rent', 'capacity', 'lat', 'lon'];
        foreach ($required_fields as $field) {
            if (empty($data[$field])) {
                return redirect()->back()->with('error', "Field {$field} is required");
            }
        }

        // Get venue details
        $venue = $this->venueModel->select('venue.*, venue_pin.lat, venue_pin.lon, venue_image.image_path, venue_image.id as image_id')
            ->join('venue_pin', 'venue_pin.id = venue.pin_id')
            ->join('venue_image', 'venue_image.venue_id = venue.id', 'left')
            ->where('venue.id', $id)
            ->where('venue.owner_profile', $user_id)
            ->first();

        if (!$venue) {
            return redirect()->back()->with('error', 'Venue not found');
        }

        // Start transaction
        $this->venueModel->db->transStart();

        try {
            // Handle image upload/update
            if (isset($data['current_image']) && $data['current_image'] === 'delete') {
                // Delete image if requested
                if ($venue['image_id']) {
                    $old_image = $this->imageModel->find($venue['image_id']);
                    if ($old_image) {
                        delete_image($old_image['image_path']);
                        $this->imageModel->delete($venue['image_id']);
                    }
                }
            } else {
                try {
                    $this->handleImageUpload($id, $venue['image_id']);
                } catch (\Exception $e) {
                    log_message('error', 'Image upload error: ' . $e->getMessage());
                    // Continue with venue update even if image upload fails
                }
            }

            // Update venue pin
            $venue_pin_data = [
                'lat' => (float)$data['lat'],
                'lon' => (float)$data['lon']
            ];
            $this->venuePinModel->update($venue['pin_id'], $venue_pin_data);

            // Update venue
            $venue_data = [
                'venue_name' => trim($data['venue_name']),
                'venue_description' => trim($data['venue_description']),
                'street' => trim($data['street']),
                'barangay' => trim($data['barangay']),
                'city' => trim($data['city']),
                'rent' => trim($data['rent']),
                'zip_code' => trim($data['zip_code']),
                'capacity' => trim($data['capacity'])
            ];

            // Validate venue data
            if (!$this->venueModel->validate($venue_data)) {
                throw new \Exception(implode(', ', $this->venueModel->errors()));
            }

            $this->venueModel->update($id, $venue_data);

            $this->venueModel->db->transComplete();

            if ($this->venueModel->db->transStatus() === false) {
                return redirect()->back()->with('error', 'Failed to update venue');
            }

            return redirect()->to('venue/list')->with('success', 'Venue updated successfully');
        } catch (\Exception $e) {
            $this->venueModel->db->transRollback();
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
        
        // Get venue details to check ownership
        $venue = $this->venueModel->select('venue.*, venue_image.image_path, venue_image.id as image_id')
            ->join('venue_image', 'venue_image.venue_id = venue.id', 'left')
            ->where('venue.id', $id)
            ->where('venue.owner_profile', $user_id)
            ->first();

        if (!$venue) {
            return redirect()->back()->with('error', 'Venue not found');
        }

        $this->venueModel->db->transStart();

        try {
            // Delete image if exists
            if ($venue['image_id']) {
                $image = $this->imageModel->find($venue['image_id']);
                if ($image) {
                    delete_image($image['image_path']);
                    $this->imageModel->delete($venue['image_id']);
                }
            }

            // Delete venue (this will cascade delete the venue_pin due to foreign key)
            $this->venueModel->delete($id);

            $this->venueModel->db->transComplete();

            if ($this->venueModel->db->transStatus() === false) {
                return redirect()->back()->with('error', 'Failed to delete venue');
            }

            return redirect()->to('venue/list')->with('success', 'Venue deleted successfully');
        } catch (\Exception $e) {
            $this->venueModel->db->transRollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
} 
