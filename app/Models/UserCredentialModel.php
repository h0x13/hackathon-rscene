<?php

namespace App\Models;

use CodeIgniter\Model;

class UserCredentialModel extends Model
{
    protected $table = 'user_credential';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'user_profile_id',
        'email',
        'password',
        'user_type'
    ];

    protected $validationRules = [
        'email' => 'required|valid_email|is_unique[user_credential.email,id,{id}]',
        'password' => 'required|min_length[8]',
        'user_type' => 'required|in_list[venue_owner,artist,admin]'
    ];

    protected $validationMessages = [
        'email' => [
            'required' => 'Email is required',
            'valid_email' => 'Please enter a valid email address',
            'is_unique' => 'This email is already registered'
        ],
        'password' => [
            'required' => 'Password is required',
            'min_length' => 'Password must be at least 8 characters long'
        ],
        'user_type' => [
            'required' => 'User type is required',
            'in_list' => 'Invalid user type'
        ]
    ];

    public function with($relation)
    {
        if ($relation === 'user_profile') {
            return $this->select('user_credential.*, user_profile.first_name, user_profile.middle_name, user_profile.last_name, user_profile.birthdate, user_profile.image_path')
                       ->join('user_profile', 'user_profile.id = user_credential.user_profile_id');
        }
        return $this;
    }
} 
