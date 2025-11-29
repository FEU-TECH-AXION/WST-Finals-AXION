<?php
// File: app/Controllers/Profile.php

namespace App\Controllers;

use App\Models\Model_users;

class Profile extends BaseController
{
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new Model_users();
        $this->session = session();
        helper(['form', 'url']);
    }

    public function index()
    {
        if (!$this->session->get('logged_in')) {
            return redirect()->to('/login');
        }

        $data['title'] = 'My Profile - ITSO';

        // Set back link based on role
        $role = $this->session->get('role');
        $data['backLink'] = match($role) {
            'admin' => '/admin/dashboard',
            'associate' => '/associate/dashboard',
            default => '/user/dashboard'
        };

        return view('profile', $data);
    }

    public function update()
    {
        if (!$this->session->get('logged_in')) {
            return redirect()->to('/login');
        }

        if ($this->request->getMethod() === 'post') {
            $userId = $this->session->get('user_id');
            $user = $this->userModel->find($userId);

            $rules = [
                'name' => 'required|min_length[3]|max_length[100]',
                'profile_photo' => 'permit_empty|uploaded[profile_photo]|max_size[profile_photo,2048]|is_image[profile_photo]|mime_in[profile_photo,image/jpg,image/jpeg,image/png]'
            ];

            // If changing password, validate password fields
            if ($this->request->getPost('current_password')) {
                $rules['current_password'] = 'required';
                $rules['new_password'] = 'required|min_length[6]';
                $rules['confirm_new_password'] = 'required|matches[new_password]';
            }

            if (!$this->validate($rules)) {
                $data['validation'] = $this->validator;
                $data['title'] = 'My Profile - ITSO';
                return view('profile', $data);
            }

            // Verify current password if trying to change password
            if ($this->request->getPost('current_password')) {
                if (!password_verify($this->request->getPost('current_password'), $user['password'])) {
                    $data['error'] = 'Current password is incorrect.';
                    $data['title'] = 'My Profile - ITSO';
                    return view('profile', $data);
                }
            }

            $updateData = [
                'name' => $this->request->getPost('name'),
                'date_updated' => date('Y-m-d H:i:s')
            ];

            // Handle photo upload
            $photo = $this->request->getFile('profile_photo');
            if ($photo && $photo->isValid() && !$photo->hasMoved()) {
                // Delete old photo if exists
                if ($user['profile_photo']) {
                    $oldPhotoPath = ROOTPATH . 'public/uploads/profiles/' . $user['profile_photo'];
                    if (file_exists($oldPhotoPath)) {
                        unlink($oldPhotoPath);
                    }
                }

                $newName = $photo->getRandomName();
                $photo->move(ROOTPATH . 'public/uploads/profiles', $newName);
                $updateData['profile_photo'] = $newName;
            }

            // Update password if provided
            if ($this->request->getPost('new_password')) {
                $updateData['password'] = password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT);
            }

            if ($this->userModel->update($userId, $updateData)) {
                // Update session data
                $this->session->set('name', $updateData['name']);
                if (isset($updateData['profile_photo'])) {
                    $this->session->set('profile_photo', $updateData['profile_photo']);
                }

                $this->session->setFlashdata('success', 'Profile updated successfully!');
                return redirect()->to('/profile');
            } else {
                $data['error'] = 'Failed to update profile. Please try again.';
                $data['title'] = 'My Profile - ITSO';
                return view('profile', $data);
            }
        }

        return redirect()->to('/profile');
    }
}