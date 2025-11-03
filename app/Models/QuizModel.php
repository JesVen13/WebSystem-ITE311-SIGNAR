<?php

namespace App\Models;

use CodeIgniter\Model;

class QuizModel extends Model
{
    protected $table = 'quizzes';
    protected $primaryKey = 'id';
    protected $allowedFields = ['lesson_id', 'question', 'answer'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}


