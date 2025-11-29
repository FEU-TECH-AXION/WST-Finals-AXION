<?php
// File: app/Controllers/Associate/Dashboard.php

namespace App\Controllers\Associate;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        $data['title'] = 'Associate Dashboard - ITSO';
        return view('associate/dashboard', $data);
    }
}
