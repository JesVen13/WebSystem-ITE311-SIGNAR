<?php

namespace App\Models;

use CodeIgniter\Model;

class SubmissionModel extends Model
{
    protected $table = 'submissions';
    protected $primaryKey = 'id';
    protected $allowedFields = ['quiz_id', 'user_id', 'answer', 'is_correct', 'submitted_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}


