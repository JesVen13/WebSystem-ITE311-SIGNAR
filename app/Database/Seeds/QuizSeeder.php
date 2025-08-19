<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class QuizSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'lesson_id' => 1, // HTML Lesson
                'title'     => 'HTML Basics Quiz',
            ],
            [
                'lesson_id' => 2, // CSS Lesson
                'title'     => 'CSS Basics Quiz',
            ],
            [
                'lesson_id' => 3, // Database Lesson
                'title'     => 'Database Concepts Quiz',
            ],
        ];

        $this->db->table('quizzes')->insertBatch($data);
    }
}
