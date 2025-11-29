<?php
// ==============================================
// File: app/Controllers/ItsoController.php
// ==============================================

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class ItsoController extends Controller
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        helper(['form', 'url']);
    }

    // Display users management page
    public function users()
    {
        // Check if user is logged in and is ITSO (use 'logged_in' not 'isLoggedIn')
        if (!session()->get('logged_in') || session()->get('role') !== 'itso') {
            return redirect()->to(base_url('login'))->with('error', 'Access denied. ITSO personnel only.');
        }

        // Order by name (not fullname) - ascending order
        $data['users'] = $this->userModel->orderBy('name', 'ASC')->findAll();
        
        return view('itso/users', $data);
    }

    // Create new user
    public function createUser()
    {
        // Check if user is logged in and is ITSO
        if (!session()->get('logged_in') || session()->get('role') !== 'itso') {
            return redirect()->to(base_url('login'))->with('error', 'Access denied.');
        }

        $validation = \Config\Services::validation();
        
        $rules = [
            'user_id' => 'required|is_unique[users.user_id]|max_length[20]',
            'name' => 'required|max_length[100]',
            'email' => 'required|valid_email|is_unique[users.email]|max_length[100]',
            'password' => 'required|min_length[6]',
            'role' => 'required|in_list[itso,associate,student]',
            'profile_photo' => 'if_exist|uploaded[profile_photo]|max_size[profile_photo,2048]|is_image[profile_photo]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $validation->listErrors());
        }

        $data = [
            'user_id' => $this->request->getPost('user_id'),
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => $this->request->getPost('role'),
            'status' => 'active'
        ];

        // Handle profile photo upload
        $file = $this->request->getFile('profile_photo');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'public/uploads/profiles', $newName);
            $data['profile_photo'] = $newName;
        } else {
            $data['profile_photo'] = 'default-avatar.png';
        }

        if ($this->userModel->insert($data)) {
            return redirect()->to(base_url('itso/users'))->with('success', 'User created successfully!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to create user.');
        }
    }

    // Update user
    public function updateUser()
    {
        // Check if user is logged in and is ITSO
        if (!session()->get('logged_in') || session()->get('role') !== 'itso') {
            return redirect()->to(base_url('login'))->with('error', 'Access denied.');
        }

        $userId = $this->request->getPost('user_id');
        
        $validation = \Config\Services::validation();
        
        $rules = [
            'name' => 'required|max_length[100]',
            'email' => "required|valid_email|max_length[100]",
            'role' => 'required|in_list[itso,associate,student]',
            'profile_photo' => 'if_exist|uploaded[profile_photo]|max_size[profile_photo,2048]|is_image[profile_photo]'
        ];

        // Check if email is unique (excluding current user)
        $existingUser = $this->userModel->where('email', $this->request->getPost('email'))
                                         ->where('user_id !=', $userId)
                                         ->first();
        
        if ($existingUser) {
            return redirect()->back()->with('error', 'Email already exists.');
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', $validation->listErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'role' => $this->request->getPost('role')
        ];

        // Update password if provided
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        // Handle profile photo upload
        $file = $this->request->getFile('profile_photo');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Delete old photo if exists
            $oldUser = $this->userModel->find($userId);
            if ($oldUser && $oldUser['profile_photo'] !== 'default-avatar.png') {
                $oldPhotoPath = FCPATH . 'public/uploads/profiles/' . $oldUser['profile_photo'];
                if (file_exists($oldPhotoPath)) {
                    unlink($oldPhotoPath);
                }
            }

            $newName = $file->getRandomName();
            $file->move(FCPATH . 'public/uploads/profiles', $newName);
            $data['profile_photo'] = $newName;
        }

        if ($this->userModel->update($userId, $data)) {
            return redirect()->to(base_url('itso/users'))->with('success', 'User updated successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to update user.');
        }
    }

    // Deactivate user
    public function deactivateUser($userId)
    {
        // Check if user is logged in and is ITSO
        if (!session()->get('logged_in') || session()->get('role') !== 'itso') {
            return redirect()->to(base_url('login'))->with('error', 'Access denied.');
        }

        // Prevent deactivating yourself - user_id is empty string in your session
        $currentUserId = session()->get('user_id');
        if (!empty($currentUserId) && $userId === $currentUserId) {
            return redirect()->back()->with('error', 'You cannot deactivate your own account.');
        }

        $data = ['status' => 'inactive'];
        
        if ($this->userModel->update($userId, $data)) {
            return redirect()->to(base_url('itso/users'))->with('success', 'User deactivated successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to deactivate user.');
        }
    }

    // Activate user
    public function activateUser($userId)
    {
        // Check if user is logged in and is ITSO
        if (!session()->get('logged_in') || session()->get('role') !== 'itso') {
            return redirect()->to(base_url('login'))->with('error', 'Access denied.');
        }

        $data = ['status' => 'active'];
        
        if ($this->userModel->update($userId, $data)) {
            return redirect()->to(base_url('itso/users'))->with('success', 'User activated successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to activate user.');
        }
    }

    // Dashboard view
    public function dashboard()
    {
        // Check if user is logged in and is ITSO
        if (!session()->get('logged_in') || session()->get('role') !== 'itso') {
            return redirect()->to(base_url('login'))->with('error', 'Access denied. ITSO personnel only.');
        }

        return view('itso/dashboard');
    }
}