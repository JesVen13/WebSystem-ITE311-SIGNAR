<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class EnrollmentsSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'user_id'   => 3,
                'course_id' => 5,
            ],
            [
                'user_id'   => 4,
                'course_id' => 6,
            ],
        ];
        $this->db->table(tableName: 'enrollments')->insertBatch(set: $data);
    }
}
