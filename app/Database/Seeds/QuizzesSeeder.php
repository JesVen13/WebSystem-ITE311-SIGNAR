<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class QuizzesSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'lesson_id' => 5, 
                'question'  => 'What is the database for you?',
                'answer'    => 'to store and manage data',
            ],
            [
                'lesson_id' => 6, 
                'question'  => 'What is the main purpose of WebDesigning?',
                'answer'    => 'to create visually appealing and user-friendly websites',
            ],
        ];
        $this->db->table('quizzes')->insertBatch($data);
    }
}