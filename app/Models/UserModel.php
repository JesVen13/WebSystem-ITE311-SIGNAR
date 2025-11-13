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

    public function getActiveUsers()
    {
        return $this->where('is_restricted', 0)
                    ->where('is_deleted', 0)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    public function getRestrictedUsers()
    {
        return $this->where('is_restricted', 1)
                    ->where('is_deleted', 0)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }
}
