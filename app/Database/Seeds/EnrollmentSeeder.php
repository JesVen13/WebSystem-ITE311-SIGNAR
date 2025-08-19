<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class EnrollmentSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'user_id'   => 3, // Student One
                'course_id' => 1, // Web Development 101
            ],
            [
                'user_id'   => 3,
                'course_id' => 2, // Database Systems
            ],
        ];

        $this->db->table('enrollments')->insertBatch($data);
    }
}
