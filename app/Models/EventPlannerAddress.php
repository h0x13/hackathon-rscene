<?php

namespace App\Models;

use CodeIgniter\Model;

class EventPlannerAddress extends Model
{
    protected $table            = 'event_planner_address';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'event_id',
        'name',
        'description',
        'street_address',
        'barangay',
        'city',
        'country',
        'zip_code'
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
        'event_id'       => 'required|integer',
        'name'          => 'required|min_length[3]|max_length[255]',
        'description'   => 'required|min_length[3]',
        'street_address'=> 'required|min_length[3]|max_length[255]',
        'barangay'      => 'required|min_length[2]|max_length[255]',
        'city'          => 'required|min_length[2]|max_length[255]',
        'country'       => 'required|min_length[2]|max_length[255]',
        'zip_code'      => 'required|min_length[2]|max_length[20]'
    ];

    protected $validationMessages = [
        'event_id' => [
            'required' => 'Event ID is required',
            'integer' => 'Event ID must be a valid number'
        ],
        'name' => [
            'required' => 'Venue name is required',
            'min_length' => 'Venue name must be at least 3 characters long',
            'max_length' => 'Venue name cannot exceed 255 characters'
        ],
        'description' => [
            'required' => 'Description is required',
            'min_length' => 'Description must be at least 3 characters long'
        ],
        'street_address' => [
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
        'country' => [
            'required' => 'Country is required',
            'min_length' => 'Country must be at least 2 characters long',
            'max_length' => 'Country cannot exceed 255 characters'
        ],
        'zip_code' => [
            'required' => 'ZIP code is required',
            'min_length' => 'ZIP code must be at least 2 characters long',
            'max_length' => 'ZIP code cannot exceed 20 characters'
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
