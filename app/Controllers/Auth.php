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

        $rules = [
            'name'              => 'required|min_length[3]',
            'email'             => 'required|valid_email|is_unique[users.email]',
            'password'          => 'required|min_length[6]',
            'password_confirm'  => 'required|matches[password]',
            'role'              => 'required|in_list[student,teacher]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('validation', $this->validator);
        }

        // Safe role
        $chosenRole = $this->request->getPost('role');
        if (!in_array($chosenRole, ['student', 'teacher'], true)) {
            $chosenRole = 'student';
        }

        $data = [
            'name'          => $this->request->getPost('name'),
            'email'         => $this->request->getPost('email'),
            'password'      => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'          => $chosenRole,
            'is_restricted' => 0,
            'is_deleted'    => 0
        ];

        $userModel->save($data);

        $session->setFlashdata('success', 'Registration successful. Please login.');
        return redirect()->to('/login');
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
    // HANDLE LOGIN
    // =========================
    public function attemptLogin()
    {
        $session = session();
        $userModel = new UserModel();

        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $userModel->where('email', $email)->first();

        // No account found
        if (!$user) {
            return redirect()->back()->with('error', 'Invalid login credentials.');
        }

        // Check if soft-deleted
        if ($user['is_deleted'] == 1) {
            return redirect()->back()->with('error', 'This account has been deleted.');
        }

        // Check password
        if (!password_verify($password, $user['password'])) {
            return redirect()->back()->with('error', 'Invalid login credentials.');
        }

        // 🔥 BLOCK RESTRICTED USERS FROM LOGGING IN
        if ($user['is_restricted'] == 1) {
            return redirect()->back()->with('error', 'Your account is restricted. Please contact the administrator.');
        }

        // ================================
        // LOGIN SUCCESS → SET SESSION
        // ================================
        $session->set([
            'user_id'    => $user['id'],
            'email'      => $user['email'],
            'name'       => $user['name'],
            'role'       => $user['role'],
            'isLoggedIn' => true
        ]);

        // Redirect by role
        switch ($user['role']) {
            case 'admin':
                return redirect()->to(base_url('admin/dashboard'));
            case 'teacher':
                return redirect()->to(base_url('teacher/dashboard'));
            default:
                return redirect()->to(base_url('student/dashboard'));
        }
    }

    // =========================
    // Dashboard (Role-Aware)
    // =========================
    public function dashboard()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $data = [
            'name' => session()->get('name'),
            'role' => session()->get('role')
        ];

        return view('auth/dashboard', $data);
    }

    // =========================
    // LOGOUT
    // =========================
    public function logout()
    {
        session()->destroy();

        $response = service('response');
        $response->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        $response->setHeader('Pragma', 'no-cache');

        return redirect()->to('/login')->with('message', 'You have been logged out successfully.');
    }
}
