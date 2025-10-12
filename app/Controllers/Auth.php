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
        if ($this->request->getMethod() === 'post') {
            return $this->store();
        }

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
            'password' => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]',
            'role' => 'required|in_list[admin,teacher,student]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return view('auth/register', ['validation' => $validation]);
        }

        // Save the selected role directly (for lab testing of multiple roles)
        $postedRole = (string) $this->request->getPost('role');
        $allowedRoles = ['admin', 'teacher', 'student'];
        $roleToSave = in_array($postedRole, $allowedRoles, true) ? $postedRole : 'student';

        $userModel->save([
            'name'     => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => $roleToSave,
        ]);

        $session->setFlashdata('success', 'Registration successful! You can login now.');
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

        // Simple login throttling: limit repeated attempts per IP+email
        $ip = $this->request->getIPAddress();
        $cache = \Config\Services::cache();
        $key = 'login_attempts_' . md5($ip . '_' . strtolower((string) $email));
        $attempts = (int) ($cache->get($key) ?? 0);
        if ($attempts >= 5) {
            $session->setFlashdata('error', 'Too many login attempts. Try again in 10 minutes.');
            return redirect()->back()->withInput();
        }

        $user = $userModel->where('email', $email)->first();

        if ($user && password_verify($password, $user['password'])) {
            // Reset attempts on success
            $cache->delete($key);
            // Regenerate session ID to prevent fixation
            $session->regenerate();
            $session->set([
                'user_id'    => $user['id'],
                'name'       => $user['name'],
                'email'      => $user['email'],
                'role'       => $user['role'],
                'isLoggedIn' => true,
            ]);
            $session->setFlashdata('success', 'Welcome back, ' . $user['name'] . '!');
            return redirect()->to('/dashboard');
        }

        // Increment attempts on failure (10-minute decay)
        $cache->save($key, $attempts + 1, 600);
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

        $role   = (string) $session->get('role');
        $userId = (int) $session->get('user_id');

        $db = \Config\Database::connect();
        $roleData = [];

        if ($role === 'admin') {
            $roleData = [
                'totalUsers'       => (int) $db->table('users')->countAllResults(),
                'totalCourses'     => (int) $db->table('courses')->countAllResults(),
                'totalEnrollments' => (int) $db->table('enrollments')->countAllResults(),
            ];
        } elseif ($role === 'teacher') {//Teacher
            $roleData = [
                'myCourses' => (int) $db->table('courses')->where('instructor_id', $userId)->countAllResults(),
            ];
        } else { // student
            $roleData = [
                // enrollments table uses user_id (not student_id)
                'myEnrollments' => (int) $db->table('enrollments')->where('user_id', $userId)->countAllResults(),
            ];
        }

        $data = [
            'name'     => $session->get('name'),
            'role'     => $role,
            'roleData' => $roleData,
        ];

        return view('dashboard', $data);
    }

    // =========================
    // Logout
    // =========================
    public function logout()
    {
        // Regenerate before destroying to mitigate fixation
        $session = session();
        $session->regenerate(true);
        $session->destroy();
        return redirect()->to('/login');
    }
}
