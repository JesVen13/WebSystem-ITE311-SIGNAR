<?php

namespace App\Controllers;
 
use App\Models\UserModel;
use CodeIgniter\Controller;

class Auth extends Controller
{
   
    public function register()
    {
        return view('auth/register'); // app/Views/auth/register.php
    }

    public function store()
    {
        $session       = session();
        $accountModel  = new UserModel();

        $name     = $this->request->getPost('name');
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $confirm  = $this->request->getPost('confirm_password');

        if ($password !== $confirm) {
            $session->setFlashdata('error', 'Passwords do not match!');
            return redirect()->back()->withInput();
        }

        $accountModel->save([
            'name'     => $name,
            'email'    => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role'     => 'user',
        ]);

        $session->setFlashdata('success', 'Registration successful! You can login now.');
        return redirect()->to('/login');
    }

    public function login()
    {
        return view('auth/login'); // app/Views/auth/login.php
    }

    public function attemptLogin()
    {
        $session       = session();
        $accountModel  = new UserModel();

        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $accountModel->where('email', $email)->first();

        if ($user && password_verify($password, $user['password'])) {
            $session->set([
                'user_id'    => $user['id'],
                'name'       => $user['name'],
                'email'      => $user['email'],
                'role'       => $user['role'], // ✅ role saved in session
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

        return view('auth/dashboard', $data); // pass data to dashboard
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
