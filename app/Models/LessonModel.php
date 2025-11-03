<?php

namespace App\Models;

use CodeIgniter\Model;

class LessonModel extends Model
{
    protected $table = 'lessons';
    protected $primaryKey = 'id';
    protected $allowedFields = ['course_id', 'title', 'content', 'created_at', 'updated_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}


