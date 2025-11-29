<?php
// ==============================================
// File: app/Filters/AuthFilter.php
// ==============================================

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Check if user is logged in (your system uses 'logged_in', not 'isLoggedIn')
        if (!$session->get('logged_in')) {
            return redirect()->to(base_url('login'))
                           ->with('error', 'Please login to access this page.');
        }

        // Get current URI path
        $uri = $request->getUri();
        $path = $uri->getPath();

        // Check for ITSO-specific routes
        if (strpos($path, 'itso') !== false) {
            if ($session->get('role') !== 'itso') {
                return redirect()->to(base_url('/'))
                               ->with('error', 'Access denied. ITSO personnel only.');
            }
        }

        // Check role-based access if specific roles are provided as arguments
        if (!empty($arguments)) {
            $userRole = $session->get('role');

            // If specific roles are required and user doesn't have the right role
            if (!in_array($userRole, $arguments)) {
                return redirect()->back()
                               ->with('error', 'You do not have permission to access this page.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}