<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'name', 'email', 'password', 'role', 'is_restricted', 'is_deleted'
    ];

    protected $useTimestamps = true;

    // Active users (for dashboard counting)
    public function getActiveUsers()
    {
        return $this->where('is_deleted', 0)
                    ->orderBy('created_at', 'DESC');
    }

    // Restricted only
    public function getRestrictedUsers()
    {
        return $this->where('is_restricted', 1)
                    ->where('is_deleted', 0);
    }

    // Deleted users
    public function getDeletedUsers()
    {
        return $this->where('is_deleted', 1);
    }

    // Search + pagination
    public function searchUsers($keyword)
    {
        return $this->groupStart()
                        ->like('name', $keyword)
                        ->orLike('email', $keyword)
                        ->orLike('role', $keyword)
                    ->groupEnd();
    }

    // Restore
    public function restoreUser($id)
    {
        return $this->update($id, ['is_deleted' => 0]);
    }

    // Soft delete
    public function softDeleteUser($id)
    {
        return $this->update($id, ['is_deleted' => 1]);
    }

    // Restrict / Unrestrict
    public function restrictUser($id)
    {
        return $this->update($id, ['is_restricted' => 1]);
    }

    public function unrestrictUser($id)
    {
        return $this->update($id, ['is_restricted' => 0]);
    }
}
