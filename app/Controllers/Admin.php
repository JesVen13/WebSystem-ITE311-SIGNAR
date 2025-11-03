<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class Admin extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    // show dashboard and users list
    public function dashboard()
    {
        // Get all registered users (excluding the current admin for safety)
        $allUsers = $this->userModel->orderBy('created_at', 'DESC')->findAll();
        
        // Count users by role
        $totalUsers = $this->userModel->countAll();
        $totalTeachers = $this->userModel->where('role', 'teacher')->countAllResults();
        $totalStudents = $this->userModel->where('role', 'student')->countAllResults();
        $totalAdmins = $this->userModel->where('role', 'admin')->countAllResults();
        
        $data = [
            'name'  => session()->get('name'),
            'role'  => session()->get('role'),
            'users' => $allUsers,
            'totalUsers' => $totalUsers,
            'totalTeachers' => $totalTeachers,
            'totalStudents' => $totalStudents,
            'totalAdmins' => $totalAdmins,
            'validation' => \Config\Services::validation(),
        ];

        return view('admin/dashboard', $data);
    }

    // create form
    public function create()
    {
        return view('admin/user_form', [
            'name' => session()->get('name'),
            'role' => session()->get('role'),
            'user' => null,
            'validation' => \Config\Services::validation(),
        ]);
    }

    // store new user
    public function store()
    {
        $rules = [
            'name' => 'required|min_length[3]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'role' => 'required|in_list[teacher,student]', // Only allow teacher and student
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        // Ensure only one admin exists - prevent creating admin accounts
        $role = $this->request->getPost('role');
        if ($role === 'admin') {
            session()->setFlashdata('error', 'Cannot create additional admin accounts. Only one admin is allowed.');
            return redirect()->back()->withInput();
        }

        $this->userModel->insert([
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => $role,
        ]);

        session()->setFlashdata('success', 'User created successfully.');
        return redirect()->to(base_url('/admin/dashboard'));
    }

    // edit form
    public function edit($id = null)
    {
        $user = $this->userModel->find($id);
        if (! $user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("User not found: {$id}");
        }

        return view('admin/user_form', [
            'name' => session()->get('name'),
            'role' => session()->get('role'),
            'user' => $user,
            'validation' => \Config\Services::validation(),
        ]);
    }

    // update existing user
    public function update($id = null)
    {
        $user = $this->userModel->find($id);
        if (! $user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("User not found: {$id}");
        }

        // Prevent modifying the current admin account's role
        $currentAdminId = session()->get('user_id');
        if ($user['id'] == $currentAdminId && $user['role'] === 'admin') {
            session()->setFlashdata('error', 'Cannot modify your own admin account.');
            return redirect()->to(base_url('/admin/dashboard'));
        }

        $emailRule = 'required|valid_email';
        if ($this->request->getPost('email') !== $user['email']) {
            $emailRule .= '|is_unique[users.email]';
        }

        $rules = [
            'name' => 'required|min_length[3]',
            'email' => $emailRule,
            'role' => 'required|in_list[teacher,student]', // Only allow teacher and student
        ];

        $password = $this->request->getPost('password');
        if ($password) {
            $rules['password'] = 'min_length[6]';
        }

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        // Prevent changing role to admin
        $newRole = $this->request->getPost('role');
        if ($newRole === 'admin') {
            session()->setFlashdata('error', 'Cannot change user role to admin. Only one admin account is allowed.');
            return redirect()->back()->withInput();
        }

        // Prevent changing admin user's role
        if ($user['role'] === 'admin' && $newRole !== 'admin') {
            session()->setFlashdata('error', 'Cannot change admin account role.');
            return redirect()->back()->withInput();
        }

        $updateData = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'role' => $newRole,
        ];

        if ($password) {
            $updateData['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $this->userModel->update($id, $updateData);

        session()->setFlashdata('success', 'User updated successfully.');
        return redirect()->to(base_url('/admin/dashboard'));
    }

    // delete user
    public function delete($id = null)
    {
        $user = $this->userModel->find($id);
        if (! $user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("User not found: {$id}");
        }

        // Prevent deleting admin account
        if ($user['role'] === 'admin') {
            session()->setFlashdata('error', 'Cannot delete admin account.');
            return redirect()->to(base_url('/admin/dashboard'));
        }

        // Prevent deleting yourself
        $currentUserId = session()->get('user_id');
        if ($user['id'] == $currentUserId) {
            session()->setFlashdata('error', 'Cannot delete your own account.');
            return redirect()->to(base_url('/admin/dashboard'));
        }

        $this->userModel->delete($id);
        session()->setFlashdata('success', 'User deleted successfully.');
        return redirect()->to(base_url('/admin/dashboard'));
    }
}


