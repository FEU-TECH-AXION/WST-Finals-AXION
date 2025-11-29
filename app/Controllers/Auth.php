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

    // FORGOT PASSWORD - Show form and process submission
    public function forgotPassword()
    {
        // If already logged in, redirect to dashboard
        if ($this->session->get('logged_in')) {
            return redirect()->to($this->getDashboardByRole($this->session->get('role')));
        }

        // If GET request, show the form
        if ($this->request->getMethod() !== 'post') {
            return view('auth/forgot_password');
        }

        // Process POST request
        $validation = \Config\Services::validation();
        
        $rules = [
            'email' => 'required|valid_email'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $validation);
        }

        $email = $this->request->getPost('email');

        // Check if email exists - using Model_users
        $user = $this->userModel->where('email', $email)->first();

        if (!$user) {
            // Don't reveal if email exists or not (security best practice)
            return redirect()->back()
                        ->with('success', 'If that email exists in our system, you will receive reset instructions shortly.');
        }

        // Generate token
        $token = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Store in database - using Model_password_reset
        // Delete any existing unused tokens for this email
        $this->passwordResetModel->where('email', $email)->where('used', 0)->delete();
        
        $this->passwordResetModel->insert([
            'email' => $email,
            'token' => $token,
            'expiry' => $expiry,
            'used' => 0
        ]);

        // Send email
        $sent = $this->sendResetEmail($email, $token);

        if ($sent) {
            log_message('info', 'Password reset email sent to: ' . $email);
            return redirect()->back()
                        ->with('success', 'Reset instructions have been sent to your email address.');
        } else {
            log_message('error', 'Failed to send password reset email to: ' . $email);
            return redirect()->back()
                        ->with('error', 'Failed to send email. Please try again later.');
        }
    }

    // SHOW RESET PASSWORD FORM
    public function showResetPassword()
    {
        $token = $this->request->getGet('token');
        
        if (!$token) {
            return redirect()->to(base_url('forgot-password'))
                        ->with('error', 'Invalid reset link.');
        }

        // Verify token exists and is valid
        $resetData = $this->passwordResetModel->where('token', $token)
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

    // PROCESS RESET PASSWORD
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
        $resetData = $this->passwordResetModel->where('token', $token)
                            ->where('expiry >=', date('Y-m-d H:i:s'))
                            ->where('used', 0)
                            ->first();

        if (!$resetData) {
            return redirect()->to(base_url('forgot-password'))
                        ->with('error', 'This reset link has expired or is invalid.');
        }

        // Update password - using Model_users
        $user = $this->userModel->where('email', $resetData['email'])->first();

        if ($user) {
            $this->userModel->update($user['user_id'], [
                'password' => password_hash($password, PASSWORD_DEFAULT)
            ]);

            // Mark token as used
            $this->passwordResetModel->update($resetData['id'], ['used' => 1]);

            log_message('info', 'Password reset successful for: ' . $resetData['email']);

            return redirect()->to(base_url('login'))
                        ->with('success', 'Your password has been reset successfully. Please login with your new password.');
        }

        return redirect()->to(base_url('forgot-password'))
                    ->with('error', 'An error occurred. Please try again.');
    }

    // SEND RESET EMAIL
    private function sendResetEmail($email, $token)
    {
        $emailConfig = new \Config\Email();
        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = $emailConfig->SMTPHost;
            $mail->SMTPAuth   = true;
            $mail->Username   = $emailConfig->SMTPUser;
            $mail->Password   = $emailConfig->SMTPPass;
            $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $emailConfig->SMTPPort;

            // Recipients
            $mail->setFrom($emailConfig->fromEmail, $emailConfig->fromName);
            $mail->addAddress($email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';

            $resetLink = base_url('reset-password?token=' . $token);

            $mail->Body = "
                <!DOCTYPE html>
                <html>
                <head>
                    <style>
                        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
                        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
                        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
                        .button { display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white !important; padding: 15px 30px; text-decoration: none; border-radius: 5px; margin: 20px 0; font-weight: bold; }
                        .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #666; }
                        .warning { background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; border-radius: 5px; }
                        .link-box { background: white; padding: 10px; border-radius: 5px; word-break: break-all; border: 1px solid #ddd; margin: 10px 0; }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <div class='header'>
                            <h1 style='margin: 0;'>🔒 Password Reset Request</h1>
                        </div>
                        <div class='content'>
                            <p>Hello,</p>
                            <p>We received a request to reset your password. Click the button below to create a new password:</p>
                            
                            <div style='text-align: center;'>
                                <a href='{$resetLink}' class='button'>Reset Password</a>
                            </div>
                            
                            <p>Or copy and paste this link into your browser:</p>
                            <div class='link-box'>
                                <a href='{$resetLink}' style='color: #667eea;'>{$resetLink}</a>
                            </div>
                            
                            <div class='warning'>
                                <strong>⏰ Important:</strong> This link will expire in 1 hour for security reasons.
                            </div>
                            
                            <p><strong>⚠️ If you didn't request this password reset, please ignore this email.</strong> Your password will remain unchanged and secure.</p>
                            
                            <hr style='border: none; border-top: 1px solid #ddd; margin: 30px 0;'>
                            
                            <p style='margin-bottom: 5px;'>Best regards,</p>
                            <p style='margin-top: 0;'><strong>ITSO Team</strong></p>
                        </div>
                        <div class='footer'>
                            <p>This is an automated email. Please do not reply to this message.</p>
                            <p>&copy; " . date('Y') . " ITSO. All rights reserved.</p>
                        </div>
                    </div>
                </body>
                </html>
            ";

            $mail->AltBody = "Password Reset Request\n\n" .
                            "Hello,\n\n" .
                            "We received a request to reset your password.\n\n" .
                            "Click this link to reset your password:\n" .
                            "{$resetLink}\n\n" .
                            "This link will expire in 1 hour.\n\n" .
                            "If you didn't request this, please ignore this email.\n\n" .
                            "Best regards,\nITSO Team";

            $mail->send();
            return true;
            
        } catch (\Exception $e) {
            log_message('error', 'Email sending failed: ' . $mail->ErrorInfo);
            return false;
        }
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
}