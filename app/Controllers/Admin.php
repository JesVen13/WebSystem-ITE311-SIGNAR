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
        $data = [
            'name'  => session()->get('name'),
            'role'  => session()->get('role'),
            'users' => $this->userModel->orderBy('id', 'DESC')->findAll(),
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
            'role' => 'required|in_list[admin,teacher,student]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $this->userModel->insert([
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'password_hash' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => $this->request->getPost('role'),
        ]);

        session()->setFlashdata('success', 'User created.');
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

        $emailRule = 'required|valid_email';
        if ($this->request->getPost('email') !== $user['email']) {
            $emailRule .= '|is_unique[users.email]';
        }

        $rules = [
            'name' => 'required|min_length[3]',
            'email' => $emailRule,
            'role' => 'required|in_list[admin,teacher,student]',
        ];

        $password = $this->request->getPost('password');
        if ($password) {
            $rules['password'] = 'min_length[6]';
        }

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $updateData = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'role' => $this->request->getPost('role'),
        ];

        if ($password) {
            $updateData['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $this->userModel->update($id, $updateData);

        session()->setFlashdata('success', 'User updated.');
        return redirect()->to(base_url('/admin/dashboard'));
    }

    // delete user
    public function delete($id = null)
    {
        if (! $this->userModel->find($id)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("User not found: {$id}");
        }

        $this->userModel->delete($id);
        session()->setFlashdata('success', 'User deleted.');
        return redirect()->to(base_url('/admin/dashboard'));
    }
}


