<?php

namespace App\Models;

use CodeIgniter\Model;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';
    protected $allowedFields = ['user_id', 'activity_type', 'description', 'created_at'];
    
    public function logActivity($userId, $type, $description)
    {
        return $this->insert([
            'user_id' => $userId,
            'activity_type' => $type,
            'description' => $description
        ]);
    }
}