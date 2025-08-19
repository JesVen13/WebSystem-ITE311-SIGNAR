<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LessonSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'course_id' => 1,
                'title'     => 'Introduction to HTML',
                'content'   => 'Learn the basics of HTML structure.',
            ],
            [
                'course_id' => 1,
                'title'     => 'Introduction to CSS',
                'content'   => 'Learn how to style web pages.',
            ],
            [
                'course_id' => 2,
                'title'     => 'Relational Databases',
                'content'   => 'Understand tables, keys, and relations.',
            ],
        ];

        $this->db->table('lessons')->insertBatch($data);
    }
}
