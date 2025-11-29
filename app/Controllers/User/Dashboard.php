<?php

// File: app/Controllers/User/Dashboard.php

namespace App\Controllers\User;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        $data['title'] = 'User Dashboard - ITSO';
        return view('user/dashboard', $data);
    }
}