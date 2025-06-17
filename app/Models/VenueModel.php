<?php

namespace App\Models;

use CodeIgniter\Model;

class VenueModel extends Model
{
    protected $table            = 'venue';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'venue_name',
        'pin_id',
        'owner_profile',
        'venue_description',
        'street',
        'barangay',
        'city',
        'zip_code',
        'rent',
        'capacity',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';

    // Validation
    protected $validationRules = [
        'venue_name'     => 'required|min_length[3]|max_length[255]',
        'pin_id'        => 'required|integer',
        'owner_profile' => 'required|integer',
        'venue_description'   => 'required|min_length[3]',
        'street'=> 'required|min_length[3]|max_length[255]',
        'barangay'      => 'required|min_length[2]|max_length[255]',
        'city'          => 'required|min_length[2]|max_length[255]',
        'zip_code'      => 'required|min_length[2]|max_length[20]',
        'rent'          => 'required|decimal',
        'capacity'      => 'required|integer'
    ];

    protected $validationMessages = [
        'venue_name' => [
            'required' => 'Venue name is required',
            'min_length' => 'Venue name must be at least 3 characters long',
            'max_length' => 'Venue name cannot exceed 255 characters'
        ],
        'pin_id' => [
            'required' => 'PIN ID is required',
            'integer' => 'PIN ID must be a valid number'
        ],
        'venue_description' => [
            'required' => 'Description is required',
            'min_length' => 'Description must be at least 3 characters long'
        ],
        'street' => [
            'required' => 'Street address is required',
            'min_length' => 'Street address must be at least 3 characters long',
            'max_length' => 'Street address cannot exceed 255 characters'
        ],
        'barangay' => [
            'required' => 'Barangay is required',
            'min_length' => 'Barangay must be at least 2 characters long',
            'max_length' => 'Barangay cannot exceed 255 characters'
        ],
        'city' => [
            'required' => 'City is required',
            'min_length' => 'City must be at least 2 characters long',
            'max_length' => 'City cannot exceed 255 characters'
        ],
        'zip_code' => [
            'required' => 'ZIP code is required',
            'min_length' => 'ZIP code must be at least 2 characters long',
            'max_length' => 'ZIP code cannot exceed 20 characters'
        ],
        'rent' => [
            'required' => 'Rent is required',
            'decimal' => 'Rent must be a valid decimal number'
        ],
        'capacity' => [
            'required' => 'Capacity is required',
            'integer' => 'Capacity must be a valid number'
        ]
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
