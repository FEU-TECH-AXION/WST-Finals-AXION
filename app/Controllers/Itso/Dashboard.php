<?php

namespace App\Controllers\Itso;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        $data['title'] = 'ITSO Dashboard';
        return view('itso/dashboard', $data);
    }
}