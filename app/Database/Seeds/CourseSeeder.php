<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'title'        => 'Web Development 101',
                'description'  => 'Intro to HTML, CSS, and JavaScript',
                'instructor_id'=> 2, // Instructor One
            ],
            [
                'title'        => 'Database Systems',
                'description'  => 'Learn about SQL and relational databases',
                'instructor_id'=> 2,
            ],
        ];

        $this->db->table('courses')->insertBatch($data);
    }
}
