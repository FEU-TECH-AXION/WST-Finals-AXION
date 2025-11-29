<?php
// File: app/Controllers/Admin/Dashboard.php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        $data['title'] = 'Admin Dashboard - ITSO';
        return view('admin/dashboard', $data);
    }
}