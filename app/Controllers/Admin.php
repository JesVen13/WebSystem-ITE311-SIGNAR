<?php

namespace App\Controllers;

use App\Models\UserModel;

class Admin extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        helper(['url', 'form']);
        $this->userModel = new UserModel();
    }

    // ===========================
    // ACCESS CHECK (ADMIN ONLY)
    // ===========================
    private function adminOnly()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login')->send();
        }
    }

    // ===========================
    // CHECK IF USER IS SELF
    // ===========================
    private function isSelf($userId)
    {
        return session()->get('user_id') == $userId;
    }

    // ======================
    // DASHBOARD
    // ======================
    public function dashboard()
    {
        $this->adminOnly();

        $activeUsers = $this->userModel
            ->where('is_deleted', 0)
            ->where('is_restricted', 0)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $restrictedUsers = $this->userModel
            ->where('is_deleted', 0)
            ->where('is_restricted', 1)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $totalUsers     = $this->userModel->where('is_deleted', 0)->countAllResults(false);
        $totalTeachers  = $this->userModel->where('role', 'teacher')->where('is_deleted', 0)->countAllResults(false);
        $totalStudents  = $this->userModel->where('role', 'student')->where('is_deleted', 0)->countAllResults(false);
        $totalAdmins    = $this->userModel->where('role', 'admin')->where('is_deleted', 0)->countAllResults(false);

        return view('admin/dashboard', [
            'name'            => session()->get('name'),
            'role'            => session()->get('role'),
            'users'           => $activeUsers,
            'restrictedUsers' => $restrictedUsers,
            'totalUsers'      => $totalUsers,
            'totalTeachers'   => $totalTeachers,
            'totalStudents'   => $totalStudents,
            'totalAdmins'     => $totalAdmins,
            'currentUserId'   => session()->get('user_id')
        ]);
    }

    // ======================
    // USERS LIST 
    // ======================
    public function users()
    {
        $this->adminOnly();

        $keyword = $this->request->getGet('search');
        $query = $this->userModel->where('is_deleted', 0);

        if ($keyword) {
            $query->groupStart()
                ->like('name', $keyword)
                ->orLike('email', $keyword)
                ->orLike('role', $keyword)
                ->groupEnd();
        }

        return view('admin/users', [
            'name'          => session()->get('name'),
            'role'          => session()->get('role'),
            'users'         => $query->paginate(10),
            'pager'         => $this->userModel->pager,
            'search'        => $keyword,
            'currentUserId' => session()->get('user_id')
        ]);
    }

    // ======================
    // DELETED USERS LIST
    // ======================
    public function deletedUsers()
    {
        $this->adminOnly();

        $deletedUsers = $this->userModel
            ->where('is_deleted', 1)
            ->orderBy('updated_at', 'DESC')
            ->paginate(10);

        return view('admin/deleted_users', [
            'name'         => session()->get('name'),
            'role'         => session()->get('role'),
            'deletedUsers' => $deletedUsers,
            'pager'        => $this->userModel->pager
        ]);
    }

    // ======================
    // RESTORE USER
    // ======================
    public function restore($id)
    {
        $this->adminOnly();

        $user = $this->userModel->find($id);

        if (!$user) {
            session()->setFlashdata('error', 'User not found.');
        } elseif ($user['is_deleted'] != 1) {
            session()->setFlashdata('error', 'This user is not deleted.');
        } else {
            $this->userModel->update($id, ['is_deleted' => 0]);
            session()->setFlashdata('success', 'User restored successfully.');
        }

        return redirect()->to('/admin/deleted-users');
    }

    // ======================
    // PERMANENT DELETE USER
    // ======================
    public function permanentDelete($id)
    {
        $this->adminOnly();

        $user = $this->userModel->find($id);

        if (!$user) {
            session()->setFlashdata('error', 'User not found.');
            return redirect()->to('/admin/deleted-users');
        }

        if ($this->isSelf($id)) {
            session()->setFlashdata('error', 'Cannot permanently delete your own account.');
            return redirect()->to('/admin/deleted-users');
        }

        if ($user['is_deleted'] != 1) {
            session()->setFlashdata('error', 'Only deleted users can be permanently removed.');
            return redirect()->to('/admin/deleted-users');
        }

        // Hard delete from database
        if ($this->userModel->delete($id, true)) {
            session()->setFlashdata('success', 'User permanently deleted from database.');
        } else {
            session()->setFlashdata('error', 'Failed to permanently delete user.');
        }

        return redirect()->to('/admin/deleted-users');
    }

    // ======================
    // PURGE ALL DELETED USERS
    // ======================
    public function purgeAll()
    {
        $this->adminOnly();

        $deletedUsers = $this->userModel->where('is_deleted', 1)->findAll();

        if (empty($deletedUsers)) {
            session()->setFlashdata('error', 'No deleted users to purge.');
            return redirect()->to('/admin/deleted-users');
        }

        $currentUserId = session()->get('user_id');
        $count = 0;

        foreach ($deletedUsers as $user) {
            // Skip if somehow current user is in deleted list
            if ($user['id'] == $currentUserId) {
                continue;
            }

            if ($this->userModel->delete($user['id'], true)) {
                $count++;
            }
        }

        if ($count > 0) {
            session()->setFlashdata('success', "Successfully purged {$count} user(s) permanently.");
        } else {
            session()->setFlashdata('error', 'No users were purged.');
        }

        return redirect()->to('/admin/deleted-users');
    }

    // ======================
    // CREATE USER FORM
    // ======================
    public function create()
    {
        $this->adminOnly();

        return view('admin/user_form', [
            'name'       => session()->get('name'),
            'role'       => session()->get('role'),
            'user'       => null,
            'validation' => \Config\Services::validation()
        ]);
    }

    // ======================
    // STORE USER
    // ======================
    public function store()
    {
        $this->adminOnly();

        $rules = [
            'name'     => 'required|min_length[3]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'role'     => 'required|in_list[teacher,student,admin]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $this->userModel->insert([
            'name'          => $this->request->getPost('name'),
            'email'         => $this->request->getPost('email'),
            'password'      => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'          => $this->request->getPost('role'),
            'is_restricted' => 0,
            'is_deleted'    => 0
        ]);

        session()->setFlashdata('success', 'User created successfully.');
        return redirect()->to('/admin/users');
    }

    // ======================
    // EDIT USER FORM
    // ======================
    public function edit($id)
    {
        $this->adminOnly();

        $user = $this->userModel->find($id);

        if (!$user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("User not found");
        }

        return view('admin/user_form', [
            'name'       => session()->get('name'),
            'role'       => session()->get('role'),
            'user'       => $user,
            'validation' => \Config\Services::validation(),
            'isSelf'     => $this->isSelf($id)
        ]);
    }

    // ======================
    // UPDATE USER
    // ======================
    public function update($id)
    {
        $this->adminOnly();

        $user = $this->userModel->find($id);
        if (!$user) {
            session()->setFlashdata('error', 'User not found.');
            return redirect()->back();
        }

        $emailRule = 'required|valid_email';
        if ($user['email'] !== $this->request->getPost('email')) {
            $emailRule .= '|is_unique[users.email]';
        }

        $rules = [
            'name'  => 'required|min_length[3]',
            'email' => $emailRule,
            'role'  => 'required|in_list[teacher,student,admin]'
        ];

        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[6]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $updateData = [
            'name'  => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'role'  => $this->request->getPost('role')
        ];

        if ($this->request->getPost('password')) {
            $updateData['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        $this->userModel->update($id, $updateData);

        session()->setFlashdata('success', 'User updated successfully.');
        return redirect()->to('/admin/users');
    }

    // ======================
    // RESTRICT / UNRESTRICT
    // ======================
    public function restrict($id)
    {
        $this->adminOnly();

        $user = $this->userModel->find($id);

        if (!$user) {
            session()->setFlashdata('error', 'User not found.');
            return redirect()->back();
        }

        if ($this->isSelf($id)) {
            session()->setFlashdata('error', 'Cannot restrict your own account.');
            return redirect()->back();
        }

        $newStatus = $user['is_restricted'] ? 0 : 1;

        $this->userModel->update($id, ['is_restricted' => $newStatus]);

        session()->setFlashdata(
            'success',
            $newStatus ? 'User restricted.' : 'User unrestricted.'
        );

        // Smart redirect
        $referer = $this->request->getServer('HTTP_REFERER');
        if ($referer && (strpos($referer, '/admin/dashboard') !== false)) {
            return redirect()->to('/admin/dashboard');
        }
        return redirect()->to('/admin/users');
    }

    // ======================
    // SOFT DELETE USER
    // ======================
    public function delete($id)
    {
        $this->adminOnly();

        $user = $this->userModel->find($id);

        if (!$user) {
            session()->setFlashdata('error', 'User not found.');
            return redirect()->back();
        }

        if ($this->isSelf($id)) {
            session()->setFlashdata('error', 'Cannot delete your own account.');
            return redirect()->back();
        }

        $this->userModel->update($id, ['is_deleted' => 1]);

        session()->setFlashdata('success', 'User deleted successfully.');
        
        // Smart redirect
        $referer = $this->request->getServer('HTTP_REFERER');
        if ($referer && (strpos($referer, '/admin/dashboard') !== false)) {
            return redirect()->to('/admin/dashboard');
        }
        return redirect()->to('/admin/users');
    }
}