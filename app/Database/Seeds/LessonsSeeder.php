<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LessonsSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'course_id'   => 5,
                'title'       => 'Lesson 1',
                'content'     => 'Introduction to Database',
            ],
            [
                'course_id'   => 6,
                'title'       => 'Lesson 1',
                'content'     => 'Introduction to Web system',
            ],
        ];
        $this->db->table(tableName: 'lessons')->insertBatch(set: $data);
    }
}
