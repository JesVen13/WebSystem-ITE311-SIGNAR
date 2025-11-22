<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SubmissionsSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'quiz_id'      => 1,
                'user_id'      => 3,
                'answer'       => 'to store and manage data',
                'is_correct'   => '15',
                'submitted_at' => date(format: 'Y-m-d H:i:s'),
            ],
            [
                'quiz_id'      => 2,
                'user_id'      => 4,
                'answer'       => 'to create visually appealing and user-friendly websites',
                'is_correct'   => '10',
                'submitted_at' => date(format: 'Y-m-d H:i:s'),
            ],
        ];
        $this->db->table(tableName: 'submissions')->insertBatch(set: $data);
    }
}