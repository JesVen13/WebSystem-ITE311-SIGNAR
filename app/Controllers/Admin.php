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

    // ======================
    // Admin Dashboard
    // ======================
    public function dashboard()
    {
        // Active (non-deleted, unrestricted) users
        $activeUsers = $this->userModel
            ->where('is_deleted', 0)
            ->where('is_restricted', 0)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        // Restricted users
        $restrictedUsers = $this->userModel
            ->where('is_deleted', 0)
            ->where('is_restricted', 1)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        // Count users by role
        $totalUsers     = $this->userModel->where('is_deleted', 0)->countAllResults(false);
        $totalTeachers  = $this->userModel->where('role', 'teacher')->where('is_deleted', 0)->countAllResults(false);
        $totalStudents  = $this->userModel->where('role', 'student')->where('is_deleted', 0)->countAllResults(false);
        $totalAdmins    = $this->userModel->where('role', 'admin')->where('is_deleted', 0)->countAllResults(false);

        $data = [
            'name'            => session()->get('name'),
            'role'            => session()->get('role'),
            'users'           => $activeUsers,
            'restrictedUsers' => $restrictedUsers,
            'totalUsers'      => $totalUsers,
            'totalTeachers'   => $totalTeachers,
            'totalStudents'   => $totalStudents,
            'totalAdmins'     => $totalAdmins,
            'validation'      => \Config\Services::validation(),
        ];

        return view('admin/dashboard', $data);
    }

    // ======================
    // Create User
    // ======================
    public function create()
    {
        return view('admin/user_form', [
            'name' => session()->get('name'),
            'role' => session()->get('role'),
            'user' => null,
            'validation' => \Config\Services::validation(),
        ]);
    }

    // ======================
    // Store User
    // ======================
    public function store()
    {
        $rules = [
            'name'     => 'required|min_length[3]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'role'     => 'required|in_list[teacher,student]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $role = $this->request->getPost('role');
        if ($role === 'admin') {
            session()->setFlashdata('error', 'Cannot create additional admin accounts.');
            return redirect()->back()->withInput();
        }

        $this->userModel->insert([
            'name'          => $this->request->getPost('name'),
            'email'         => $this->request->getPost('email'),
            'password'      => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'          => $role,
            'is_restricted' => 0,
            'is_deleted'    => 0,
        ]);

        session()->setFlashdata('success', 'User created successfully.');
        return redirect()->to(base_url('/admin/dashboard'));
    }

    // ======================
    // Edit User
    // ======================
    public function edit($id = null)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("User not found: {$id}");
        }

        return view('admin/user_form', [
            'name' => session()->get('name'),
            'role' => session()->get('role'),
            'user' => $user,
            'validation' => \Config\Services::validation(),
        ]);
    }

    // ======================
    // Update User
    // ======================
    public function update($id = null)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("User not found: {$id}");
        }

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
            'name'  => 'required|min_length[3]',
            'email' => $emailRule,
            'role'  => 'required|in_list[teacher,student]',
        ];

        $password = $this->request->getPost('password');
        if ($password) {
            $rules['password'] = 'min_length[6]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $newRole = $this->request->getPost('role');
        if ($newRole === 'admin') {
            session()->setFlashdata('error', 'Cannot assign admin role.');
            return redirect()->back()->withInput();
        }

        $updateData = [
            'name'  => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'role'  => $newRole,
        ];

        if ($password) {
            $updateData['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $this->userModel->update($id, $updateData);
        session()->setFlashdata('success', 'User updated successfully.');
        return redirect()->to(base_url('/admin/dashboard'));
    }

    // ======================
    // Restrict / Unrestrict
    // ======================
    public function restrict($id = null)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            session()->setFlashdata('error', 'User not found.');
            return redirect()->to(base_url('/admin/dashboard'));
        }

        if ($user['role'] === 'admin') {
            session()->setFlashdata('error', 'Cannot restrict admin account.');
            return redirect()->to(base_url('/admin/dashboard'));
        }

        $newStatus = $user['is_restricted'] ? 0 : 1;
        $this->userModel->update($id, ['is_restricted' => $newStatus]);

        $msg = $newStatus ? 'User restricted successfully.' : 'User unrestricted successfully.';
        session()->setFlashdata('success', $msg);

        return redirect()->to(base_url('/admin/dashboard'));
    }

    // ======================
    // Soft Delete User
    // ======================
    public function delete($id = null)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            session()->setFlashdata('error', 'User not found.');
            return redirect()->to(base_url('/admin/dashboard'));
        }

        if ($user['role'] === 'admin') {
            session()->setFlashdata('error', 'Cannot delete admin account.');
            return redirect()->to(base_url('/admin/dashboard'));
        }

        $this->userModel->update($id, ['is_deleted' => 1]);
        session()->setFlashdata('success', 'User removed from dashboard view.');

        return redirect()->to(base_url('/admin/dashboard'));
    }
}
