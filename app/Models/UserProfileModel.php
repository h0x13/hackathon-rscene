<?php

namespace App\Models;

use CodeIgniter\Model;

class UserProfileModel extends Model
{
    protected $table = 'user_profile';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'first_name',
        'middle_name',
        'last_name',
        'birthdate',
        'image_path'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'first_name' => 'required|min_length[2]|max_length[100]',
        'last_name' => 'required|min_length[2]|max_length[100]',
        'birthdate' => 'required|valid_date'
    ];

    protected $validationMessages = [
        'first_name' => [
            'required' => 'First name is required',
            'min_length' => 'First name must be at least 2 characters long',
            'max_length' => 'First name cannot exceed 100 characters'
        ],
        'last_name' => [
            'required' => 'Last name is required',
            'min_length' => 'Last name must be at least 2 characters long',
            'max_length' => 'Last name cannot exceed 100 characters'
        ],
        'birthdate' => [
            'required' => 'Birthdate is required',
            'valid_date' => 'Please enter a valid date'
        ]
    ];
} 