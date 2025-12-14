<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class EnrollmentSeeder extends Seeder
{
    public function run(): void
    {
        // Sample enrollments - students enrolling in courses
        $data = [
            [
                'user_id' => 3, // Student One (user_id 3)
                'course_id' => 1, // Websystem 101
                'enrollment_date' => date('Y-m-d H:i:s'),
            ],
            [
                'user_id' => 4, // Student Two (user_id 4)
                'course_id' => 1, // Websystem 101
                'enrollment_date' => date('Y-m-d H:i:s'),
            ],
            [
                'user_id' => 3, // Student One
                'course_id' => 2, // Database 101
                'enrollment_date' => date('Y-m-d H:i:s'),
            ],
            [
                'user_id' => 4, // Student Two
                'course_id' => 2, // Database 101
                'enrollment_date' => date('Y-m-d H:i:s'),
            ],
        ];
        
        $this->db->table('enrollments')->insertBatch($data);
    }
}
