<?php
// File: app/Filters/AuthFilter.php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Check if user is logged in
        if (!$session->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Please login to access this page.');
        }

        // Check role-based access
        if (!empty($arguments)) {
            $userRole = $session->get('role');

            // If specific roles are required and user doesn't have the right role
            if (!in_array($userRole, $arguments)) {
                return redirect()->back()->with('error', 'You do not have permission to access this page.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}