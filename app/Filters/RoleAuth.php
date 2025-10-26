<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class Auth extends Controller
{
    // =========================
    // Register Page
    // =========================
    public function register()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/register');
    }

    // =========================
    // Handle Registration
    // =========================
    public function store()
    {
        $session = session();
        $userModel = new UserModel();

        // Validate input
        $rules = [
            'name' => 'required|min_length[3]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'password_confirm' => 'required|matches[password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('validation', $this->validator);
        }

        // Hash password before saving
        $passwordHash = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);

        $data = [
            'name'     => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'password' => $passwordHash,
            'role'     => 'student' // default role
        ];

        if ($userModel->insert($data)) {
            $session->setFlashdata('success', 'Registration successful. Please log in.');
            return redirect()->to('/login');
        } else {
            $session->setFlashdata('error', 'Registration failed. Try again.');
            return redirect()->back()->withInput();
        }
    }

    // =========================
    // Login Page
    // =========================
    public function login()
    {
        if ($this->request->getMethod() === 'post') {
            return $this->attemptLogin();
        }

        return view('auth/login');
    }

    // =========================
    // Handle Login
    // =========================
    private function attemptLogin()
    {
        $session = session();
        $userModel = new UserModel();

        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $userModel->where('email', $email)->first();

        if ($user && password_verify($password, $user['password'])) {
            $session->set([
                'user_id'   => $user['id'],
                'email'     => $user['email'],
                'name'      => $user['name'],
                'role'      => $user['role'],
                'isLoggedIn'=> true
            ]);

            // Redirect based on role
            if ($user['role'] === 'admin') {
                return redirect()->to('admin/dashboard');
            } elseif ($user['role'] === 'teacher') {
                return redirect()->to('teacher/dashboard');
            } else {
                return redirect()->to('student/dashboard');
            }
        }

        return redirect()->back()->with('error', 'Invalid login credentials.');
    }

    // =========================
    // Dashboard
    // =========================
    public function dashboard()
    {
        $session = session();

        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userModel = new UserModel();
        $data = [
            'name' => $session->get('name'),
            'role' => $session->get('role'),
            'roleData' => []
        ];

        switch ($session->get('role')) {
            case 'admin':
                $data['roleData'] = [
                    'totalUsers' => $userModel->countAll(),
                    'totalCourses' => 10,
                    'totalEnrollments' => 50
                ];
                break;
            case 'teacher':
                $data['roleData'] = ['myCourses' => 5];
                break;
            case 'student':
                $data['roleData'] = ['myEnrollments' => 3];
                break;
        }

        return view('auth/dashboard', $data);
    }

    // =========================
    // Logout
    // =========================
    public function logout()
    {
        $session = session();
        $session->destroy();

        $response = service('response');
        $response->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        $response->setHeader('Cache-Control', 'post-check=0, pre-check=0');
        $response->setHeader('Pragma', 'no-cache');

        return redirect()->to('/login')->with('message', 'You have been logged out successfully.');
    }
}
