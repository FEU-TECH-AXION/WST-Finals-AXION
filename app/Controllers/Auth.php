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
        
        if ($this->request->getMethod() === 'post') {
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

        if ($this->request->getMethod() === 'post') {
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
                    'role' => 'Student',
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

    // FORGOT PASSWORD
    public function forgotPassword()
    {
        $data['title'] = 'Forgot Password - ITSO';

        if ($this->request->getMethod() === 'post') {
            $rules = ['email' => 'required|valid_email'];

            if (!$this->validate($rules)) {
                $data['validation'] = $this->validator;
            } else {
                $email = $this->request->getPost('email');
                $user = $this->userModel->where('email', $email)->first();

                if ($user) {
                    $token = bin2hex(random_bytes(32));
                    $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

                    $this->passwordResetModel->insert([
                        'email' => $email,
                        'token' => $token,
                        'expires_at' => $expiresAt
                    ]);

                    $this->sendPasswordResetEmail($email, $token);
                    $data['success'] = 'Password reset link has been sent to your email.';
                } else {
                    $data['success'] = 'If that email exists, a password reset link has been sent.';
                }
            }
        }

        return view('auth/forgot_password', $data);
    }

    // RESET PASSWORD
    public function resetPassword($token = null)
    {
        $data['title'] = 'Reset Password - ITSO';
        $data['token'] = $token;

        if (!$token) {
            return redirect()->to('/forgot-password');
        }

        $resetToken = $this->passwordResetModel
            ->where('token', $token)
            ->where('used', 0)
            ->where('expires_at >', date('Y-m-d H:i:s'))
            ->first();

        if (!$resetToken) {
            $data['error'] = 'Invalid or expired reset token.';
            return view('auth/reset_password', $data);
        }

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'password' => 'required|min_length[6]',
                'confirm_password' => 'required|matches[password]'
            ];

            if (!$this->validate($rules)) {
                $data['validation'] = $this->validator;
            } else {
                $newPassword = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);

                $this->userModel->where('email', $resetToken['email'])
                    ->set(['password' => $newPassword, 'date_updated' => date('Y-m-d H:i:s')])
                    ->update();

                $this->passwordResetModel->update($resetToken['id'], ['used' => 1]);

                $this->session->setFlashdata('success', 'Password reset successful! Please login.');
                return redirect()->to('/login');
            }
        }

        return view('auth/reset_password', $data);
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
            'ITSO' => '/admin/dashboard',
            'Associate' => '/associate/dashboard',
            'Student' => '/user/dashboard',
            default => '/login'
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
}