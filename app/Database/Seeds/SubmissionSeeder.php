<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SubmissionSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'quiz_id'  => 1,
                'user_id'  => 3, // Student
                'answer'   => 'HTML stands for HyperText Markup Language',
                'score'    => 90,
            ],
            [
                'quiz_id'  => 2,
                'user_id'  => 3,
                'answer'   => 'CSS is used for styling',
                'score'    => 85,
            ],
        ];

        $this->db->table('submissions')->insertBatch($data);
    }
}
