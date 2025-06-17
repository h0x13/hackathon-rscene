<?php

namespace App\Models;

use CodeIgniter\Model;

class ImageModel extends Model
{
    protected $table            = 'venue_image';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'image_path', 'venue_id'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'name' => 'required|min_length[1]|max_length[100]',
        'image_path' => 'required|min_length[1]|max_length[255]',
        'venue_id' => 'required|integer'
    ];
    
    protected $validationMessages = [
        'name' => [
            'required' => 'Image name is required',
            'min_length' => 'Image name must be at least 1 character long',
            'max_length' => 'Image name cannot exceed 100 characters'
        ],
        'image_path' => [
            'required' => 'Image path is required',
            'min_length' => 'Image path must be at least 1 character long',
            'max_length' => 'Image path cannot exceed 255 characters'
        ],
        'venue_id' => [
            'required' => 'Venue ID is required',
            'integer' => 'Venue ID must be a valid integer'
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
