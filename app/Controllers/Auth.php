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
        return view('auth/register');
    }

    // =========================
    // Handle Registration
    // =========================
    public function store()
    {
        $session      = session();
        $userModel    = new UserModel();

        $validation = \Config\Services::validation();
        $validation->setRules([
            'name' => [
                'label' => 'Full Name',
                'rules' => 'required|regex_match[/^[A-Za-z ]+$/]',
                'errors' => [
                    'required'    => 'Full Name is required.',
                    'regex_match' => 'Full Name can only contain letters and spaces.'
                ]
            ],
            'email' => 'required|valid_email|is_unique[users.email]', // ✅ changed to users.email
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return view('auth/register', ['validation' => $validation]);
        }

        $userModel->save([
            'name'     => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => 'user',
        ]);

        $session->setFlashdata('success', 'Registration successful! You can login now.');
        return redirect()->to('/login');
    }

    // =========================
    // Login Page
    // =========================
    public function login()
    {
        return view('auth/login');
    }

    // =========================
    // Handle Login
    // =========================
    public function attemptLogin()
    {
        $session   = session();
        $userModel = new UserModel();

        $validation = \Config\Services::validation();
        $validation->setRules([
            'email' => 'required|valid_email',
            'password' => 'required'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return view('auth/login', ['validation' => $validation]);
        }

        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $userModel->where('email', $email)->first();

        if ($user && password_verify($password, $user['password'])) {
            $session->set([
                'user_id'    => $user['id'],
                'name'       => $user['name'],
                'email'      => $user['email'],
                'role'       => $user['role'],
                'isLoggedIn' => true,
            ]);
            return redirect()->to('/dashboard');
        }

        $session->setFlashdata('error', 'Invalid email or password!');
        return redirect()->back()->withInput();
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

        $data = [
            'name' => $session->get('name'),
            'role' => $session->get('role'),
        ];

        return view('Views/dashboard', $data);
    }

    // =========================
    // Logout
    // =========================
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
