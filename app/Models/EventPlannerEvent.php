<?php

namespace App\Models;

use CodeIgniter\Model;

class EventPlannerEvent extends Model
{
    protected $table            = 'event_planner_event';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['location_id', 'event_name', 'event_description', 'event_organizer_id', 'event_date', 'status'];



    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';

    // Validation
    protected $validationRules = [
        'location_id' => 'required|integer',
        'event_name' => 'required|min_length[3]|max_length[255]',
        'event_description' => 'required|min_length[10]',
        'event_organizer_id' => 'required|integer',
        'event_date' => 'required|valid_date',
        'status' => 'required|in_list[pending,approved,rejected,cancelled]'
    ];

    protected $validationMessages = [
        'location_id' => [
            'required' => 'Location is required',
            'integer' => 'Invalid location ID'
        ],
        'event_name' => [
            'required' => 'Event name is required',
            'min_length' => 'Event name must be at least 3 characters long',
            'max_length' => 'Event name cannot exceed 255 characters'
        ],
        'event_description' => [
            'required' => 'Event description is required',
            'min_length' => 'Event description must be at least 10 characters long'
        ],
        'event_organizer_id' => [
            'required' => 'Organizer ID is required',
            'integer' => 'Invalid organizer ID'
        ],
        'event_date' => [
            'required' => 'Event date is required',
            'valid_date' => 'Invalid event date format'
        ],
        'status' => [
            'required' => 'Status is required',
            'in_list' => 'Invalid status value'
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
