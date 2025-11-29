<?php

namespace App\Controllers\Student;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        $data['title'] = 'Student Dashboard';
        return view('student/dashboard', $data);
    }
}