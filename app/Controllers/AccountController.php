<?php

namespace App\Controllers;

use App\Models\UserProfileModel;
use App\Models\UserCredentialModel;
use App\Models\ArtistModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;


class AccountController extends ResourceController
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

    private function validatePassword($password)
    {
        if (strlen($password) < 8) {
            return [false, "Password must be at least 8 characters long"];
        }
        return [true, null];
    }

    public function index()
    {
        if (!$this->session->get('user_data')) {
            return redirect()->to('login');
        }

        $user_id = $this->session->get('user_data')['user_id'];
        $user_credential = $this->userCredentialModel->with('user_profile')->find($user_id);

        if (!$user_credential) {
            return redirect()->to('login');
        }

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
                ]
            ]
        ];

        return view('accounts/profile', $data);
    }

    public function login()
    {
        if ($this->request->getMethod() === 'POST') {
            $json = $this->request->getJSON(true);
            $rules = [
                'email' => 'required|valid_email',
                'password' => 'required'
            ];

            if (!$this->validate($rules)) {
                return $this->fail($this->validator->getErrors());
            }

            $email = $json['email'];
            $password = $json['password'];

            $user = $this->userCredentialModel->where('email', $email)->first();

            if (!$user || !password_verify($password, $user['password'])) {
                return $this->fail('Invalid email or password', 401);
            }

            $user_profile = $this->userProfileModel->find($user['user_profile_id']);
            
            $artistData = [];
            if ($user['user_type'] === 'artist') {
                $artistData = $this->artistModel->where('performer', $user['id'])->first();
            }
            
            $this->session->set('user_data', [
                'id' => $user['user_profile_id'],
                'user_id' => $user_profile['id'],
                'email' => $email,
                'user_type' => $user['user_type'],
                'artist_data' => $artistData,
            ]);

            return $this->respond([
                'success' => true,
                'message' => 'Login successful',
                'user' => [
                    'email' => $user['email'],
                    'first_name' => $user_profile['first_name'],
                    'last_name' => $user_profile['last_name'],
                    'user_type' => $user['user_type']
                ],
            ]);
        }

        return view('accounts/login');
    }

    public function logout()
    {
        $this->session->remove('user_data');
        return redirect()->to('login');
    }

    public function register()
    {
        if ($this->request->getMethod() === 'POST') {
            // Get JSON input
            $json = $this->request->getJSON(true);
            
            $rules = [
                'email' => 'required|valid_email|is_unique[user_credential.email]',
                'password' => 'required|min_length[8]',
                'first_name' => 'required',
                'last_name' => 'required',
                'birthdate' => 'required|valid_date'
            ];

            if (!$this->validate($rules)) {
                return $this->fail($this->validator->getErrors());
            }

            $email = $json['email'] ?? null;
            if (empty($email)) {
                return $this->fail('Email is required');
            }

            $verification_code = bin2hex(random_bytes(3));
            
            $this->session->set('verification_data', [
                'email' => $email,
                'password' => $json['password'],
                'first_name' => $json['first_name'],
                'last_name' => $json['last_name'],
                'birthdate' => $json['birthdate'],
                'verification_code' => $verification_code
            ]);

            // Send verification email
            try {
                $this->email->setTo($email);
                $this->email->setSubject('Email Verification');
                $this->email->setMailType('html');
                $this->email->setMessage(view('accounts/email', [
                    'is_password_reset' => false,
                    'verification_code' => $verification_code
                ]));
                
                if (!$this->email->send()) {
                    return $this->fail('Failed to send verification email');
                }
            } catch (\Exception $e) {
                return $this->fail('Failed to send verification email');
            }

            return $this->respond([
                'success' => true,
                'message' => 'Verification code sent to your email'
            ]);
        }

        return view('accounts/register');
    }

    public function verifyCode()
    {
        if ($this->request->getMethod() === 'POST') {
            $json = $this->request->getJSON(true);
            $verification_data = $this->session->get('verification_data');
            
            if (!$verification_data) {
                return $this->fail('No verification data found. Please register again.');
            }

            if ($json['verification_code'] !== $verification_data['verification_code']) {
                return $this->fail('Invalid verification code');
            }

            // Create user profile
            $user_profile_id = $this->userProfileModel->insert([
                'first_name' => $verification_data['first_name'],
                'last_name' => $verification_data['last_name'],
                'birthdate' => $verification_data['birthdate']
            ]);

            // Create user credential
            $this->userCredentialModel->insert([
                'user_profile_id' => $user_profile_id,
                'email' => $verification_data['email'],
                'password' => password_hash($verification_data['password'], PASSWORD_DEFAULT),
                'user_type' => 'artist'
            ]);

            $this->session->remove('verification_data');

            return $this->respond([
                'success' => true,
                'message' => 'Registration completed successfully'
            ]);
        }

        return view('accounts/verify');
    }

    public function forgotPassword()
    {
        if ($this->request->getMethod() === 'POST') {
            $email = $this->request->getPost('email');
            
            $user = $this->userCredentialModel->where('email', $email)->first();
            
            if (!$user) {
                return $this->fail('Email not found', 404);
            }

            $verification_code = bin2hex(random_bytes(3));
            
            $this->session->set('password_reset_data', [
                'email' => $email,
                'verification_code' => $verification_code
            ]);

            // Send verification email
            $this->email->setTo($email);
            $this->email->setSubject('Password Reset Verification');
            $this->email->setMessage(view('accounts/email', [
                'is_password_reset' => true,
                'verification_code' => $verification_code
            ]));
            $this->email->send();

            return $this->respond([
                'success' => true,
                'message' => 'Verification code sent to your email'
            ]);
        }

        return view('accounts/forgot_password');
    }

    public function verifyPasswordReset()
    {
        if ($this->request->getMethod() === 'POST') {
            $json = $this->request->getJSON(true);
            $reset_data = $this->session->get('password_reset_data');
            
            if (!$reset_data) {
                return $this->fail('No password reset request found. Please try again.');
            }

            if ($json['verification_code'] !== $reset_data['verification_code']) {
                return $this->fail('Invalid verification code');
            }

            $new_password = $json['new_password'];
            list($is_valid, $error_message) = $this->validatePassword($new_password);
            
            if (!$is_valid) {
                return $this->fail($error_message);
            }

            $user = $this->userCredentialModel->where('email', $reset_data['email'])->first();
            $this->userCredentialModel->update($user['id'], [
                'password' => password_hash($new_password, PASSWORD_DEFAULT)
            ]);

            $this->session->remove('password_reset_data');

            return $this->respond([
                'success' => true,
                'message' => 'Password updated successfully'
            ]);
        }

        return view('accounts/verify_password_reset');
    }

    public function changePassword()
    {
        if (!$this->session->get('user_data')) {
            return redirect()->to('login');
        }

        if ($this->request->getMethod() === 'POST') {
            $json = $this->request->getJSON(true);
            $user_id = $this->session->get('user_data')['user_id'];
            $user = $this->userCredentialModel->find($user_id);

            if (!password_verify($json['current_password'], $user['password'])) {
                return $this->fail('Current password is incorrect');
            }

            $new_password = $json['new_password'];
            list($is_valid, $error_message) = $this->validatePassword($new_password);
            
            if (!$is_valid) {
                return $this->fail($error_message);
            }

            $this->userCredentialModel->update($user_id, [
                'password' => password_hash($new_password, PASSWORD_DEFAULT)
            ]);

            return $this->respond([
                'success' => true,
                'message' => 'Password updated successfully'
            ]);
        }

        return view('accounts/change_password');
    }

    public function updateProfile()
    {
        if (!$this->session->get('user_data')) {
            return redirect()->to('login');
        }

        if ($this->request->getMethod() === 'POST') {
            $json = $this->request->getJSON(true);
            $user_id = $this->session->get('user_data')['user_id'];
            $user = $this->userCredentialModel->find($user_id);
            
            $this->userProfileModel->update($user['user_profile_id'], [
                'first_name' => $json['first_name'],
                'middle_name' => $json['middle_name'] ?? null,
                'last_name' => $json['last_name'],
                'birthdate' => $json['birthdate']
            ]);

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
                ]
            ]
        ];
        
        return view('accounts/profile', $data);
    }
}
