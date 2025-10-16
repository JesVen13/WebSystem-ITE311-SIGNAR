<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Not logged in → redirect to login
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = (string) ($session->get('role') ?? '');
        $path = ltrim((string) $request->getUri()->getPath(), '/');

        // Allow students: /student/* and /announcements
        if ($role === 'student') {
            if (str_starts_with($path, 'student') || $path === 'announcements') {
                return null;
            }
            $session->setFlashdata('error', 'Access Denied: Insufficient Permissions');
            return redirect()->to('/announcements');
        }

        // Allow teachers: /teacher/*
        if ($role === 'teacher') {
            if (str_starts_with($path, 'teacher')) {
                return null;
            }
            $session->setFlashdata('error', 'Access Denied: Insufficient Permissions');
            return redirect()->to('/announcements');
        }

        // Allow admins: /admin/*
        if ($role === 'admin') {
            if (str_starts_with($path, 'admin')) {
                return null;
            }
            $session->setFlashdata('error', 'Access Denied: Insufficient Permissions');
            return redirect()->to('/announcements');
        }

        // Unknown role → deny
        $session->setFlashdata('error', 'Access Denied: Insufficient Permissions');
        return redirect()->to('/announcements');
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // no-op
    }
}


