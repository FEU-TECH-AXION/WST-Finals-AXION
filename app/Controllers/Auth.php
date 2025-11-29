<?php
// File: app/Controllers/Auth.php

namespace App\Controllers;

use App\Models\Model_users;
use App\Models\Model_password_reset;

class Auth extends BaseController
{
    protected $userModel;
    protected $passwordResetModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new Model_users();
        $this->passwordResetModel = new Model_password_reset();
        $this->session = session();
        helper(['form', 'url']);
    }

    // LOGIN
    public function login()
    {
        if ($this->session->get('logged_in')) {
            return redirect()->to($this->getDashboardByRole($this->session->get('role')));
        }

        $data['title'] = 'Login - ITSO';

        if ($this->request->is('post')) {
            $rules = [
                'email' => 'required|valid_email',
                'password' => 'required|min_length[6]'
            ];

            if (!$this->validate($rules)) {
                $data['validation'] = $this->validator;
            } else {
                $email = $this->request->getPost('email');
                $password = $this->request->getPost('password');
                $user = $this->userModel->where('email', $email)->first();

                if ($user && password_verify($password, $user['password'])) {
                    if ($user['status'] === 'active') {
                        $this->session->set([
                            'user_id' => $user['user_id'],
                            'name' => $user['name'],
                            'email' => $user['email'],
                            'profile_photo' => $user['profile_photo'],
                            'role' => $user['role'],
                            'logged_in' => true
                        ]);
                        return redirect()->to($this->getDashboardByRole($user['role']));
                    } else {
                        $data['error'] = 'Your account is inactive. Please contact the administrator.';
                    }
                } else {
                    $data['error'] = 'Invalid email or password.';
                }
            }
        }

        return view('auth/login', $data);
    }

    // SIGNUP
    public function signup()
    {
        if ($this->session->get('logged_in')) {
            return redirect()->to($this->getDashboardByRole($this->session->get('role')));
        }

        $data['title'] = 'Sign Up - ITSO';

        if ($this->request->is('post')) {
            $rules = [
                'name' => 'required|min_length[3]|max_length[100]',
                'email' => 'required|valid_email|is_unique[users.email]',
                'password' => 'required|min_length[6]',
                'confirm_password' => 'required|matches[password]',
                'profile_photo' => 'permit_empty|uploaded[profile_photo]|max_size[profile_photo,2048]|is_image[profile_photo]|mime_in[profile_photo,image/jpg,image/jpeg,image/png]'
            ];

            if (!$this->validate($rules)) {
                $data['validation'] = $this->validator;
            } else {
                $profilePhoto = null;

                // Handle photo upload
                $photo = $this->request->getFile('profile_photo');
                if ($photo && $photo->isValid() && !$photo->hasMoved()) {
                    $newName = $photo->getRandomName();
                    $photo->move(ROOTPATH . 'public/uploads/profiles', $newName);
                    $profilePhoto = $newName;
                }

                $userData = [
                    'name' => $this->request->getPost('name'),
                    'email' => $this->request->getPost('email'),
                    'profile_photo' => $profilePhoto,
                    'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                    'role' => 'student',
                    'status' => 'active',
                    'date_created' => date('Y-m-d H:i:s')
                ];

                if ($this->userModel->insert($userData)) {
                    $this->session->setFlashdata('success', 'Account created successfully! Please login.');
                    return redirect()->to('/login');
                } else {
                    $data['error'] = 'Failed to create account. Please try again.';
                }
            }
        }

        return view('auth/signup', $data);
    }


    // LOGOUT
    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/');
    }

    // HELPER: Get dashboard by role
    private function getDashboardByRole($role)
    {
        return match($role) {
            'itso' => '/itso/dashboard',
            'associate' => '/associate/dashboard',
            'student' => '/student/dashboard',
            default => '/student/dashboard'
        };
    }

    // HELPER: Send password reset email
    private function sendPasswordResetEmail($email, $token)
    {
        $resetLink = base_url("reset-password/$token");

        $emailService = \Config\Services::email();
        $emailService->setFrom('noreply@itso.com', 'ITSO Support');
        $emailService->setTo($email);
        $emailService->setSubject('Password Reset Request');

        $message = "Click the link below to reset your password:\n\n";
        $message .= "$resetLink\n\n";
        $message .= "This link will expire in 1 hour.\n\n";
        $message .= "If you didn't request this, please ignore this email.";

        $emailService->setMessage($message);
        $emailService->send();
    }

    // Show forgot password form
public function forgotPassword()
{
    // If already logged in, redirect to appropriate dashboard
    if (session()->get('logged_in')) {
        $role = session()->get('role');
        if ($role === 'itso') {
            return redirect()->to(base_url('itso/dashboard'));
        }
        return redirect()->to(base_url('/'));
    }

    return view('auth/forgot_password');
}

// Process forgot password request
public function processForgotPassword()
{
    $validation = \Config\Services::validation();
    
    $rules = [
        'email' => 'required|valid_email'
    ];

    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()->with('validation', $validation);
    }

    $email = $this->request->getPost('email');
    
    // Load user model
    $userModel = new \App\Models\UserModel();
    $user = $userModel->where('email', $email)->first();

    // Always show success message for security (don't reveal if email exists)
    if ($user) {
        // Generate reset token
        $token = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        // Store token in database (you'll need to create a password_reset table)
        $resetModel = new \App\Models\PasswordResetModel();
        $resetModel->insert([
            'email' => $email,
            'token' => $token,
            'expiry' => $expiry,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        // Send email with reset link
        $resetLink = base_url('reset-password/' . $token);
        
        // Email sending code here
        $emailService = \Config\Services::email();
        $emailService->setTo($email);
        $emailService->setFrom('noreply@yourdomain.com', 'ITSO System');
        $emailService->setSubject('Password Reset Request');
        $emailService->setMessage("
            <h3>Password Reset Request</h3>
            <p>Hello {$user['name']},</p>
            <p>You requested to reset your password. Click the link below to proceed:</p>
            <p><a href='{$resetLink}'>Reset Password</a></p>
            <p>This link will expire in 1 hour.</p>
            <p>If you didn't request this, please ignore this email.</p>
        ");

        if ($emailService->send()) {
            log_message('info', 'Password reset email sent to: ' . $email);
        } else {
            log_message('error', 'Failed to send password reset email to: ' . $email);
        }
    }

    // Always return success for security
    return redirect()->back()
                     ->with('success', 'If your email is registered, you will receive password reset instructions shortly.');
}

// Show reset password form
public function resetPassword($token = null)
{
    if (!$token) {
        return redirect()->to(base_url('forgot-password'))
                       ->with('error', 'Invalid reset link.');
    }

    // Verify token
    $resetModel = new \App\Models\PasswordResetModel();
    $resetData = $resetModel->where('token', $token)
                           ->where('expiry >=', date('Y-m-d H:i:s'))
                           ->where('used', 0)
                           ->first();

    if (!$resetData) {
        return redirect()->to(base_url('forgot-password'))
                       ->with('error', 'This reset link has expired or is invalid.');
    }

    $data['token'] = $token;
    return view('auth/reset_password', $data);
}

    // Process password reset
    public function processResetPassword()
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'token' => 'required',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $validation);
        }

        $token = $this->request->getPost('token');
        $password = $this->request->getPost('password');

        // Verify token again
        $resetModel = new \App\Models\PasswordResetModel();
        $resetData = $resetModel->where('token', $token)
                            ->where('expiry >=', date('Y-m-d H:i:s'))
                            ->where('used', 0)
                            ->first();

        if (!$resetData) {
            return redirect()->to(base_url('forgot-password'))
                        ->with('error', 'This reset link has expired or is invalid.');
        }

        // Update password
        $userModel = new \App\Models\UserModel();
        $user = $userModel->where('email', $resetData['email'])->first();

        if ($user) {
            $userModel->update($user['user_id'], [
                'password' => password_hash($password, PASSWORD_DEFAULT)
            ]);

            // Mark token as used
            $resetModel->update($resetData['id'], ['used' => 1]);

            log_message('info', 'Password reset successful for: ' . $resetData['email']);

            return redirect()->to(base_url('login'))
                        ->with('success', 'Your password has been reset successfully. Please login with your new password.');
        }

        return redirect()->to(base_url('forgot-password'))
                    ->with('error', 'An error occurred. Please try again.');
    }

}

