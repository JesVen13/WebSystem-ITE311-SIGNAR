<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CoursesSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'title'        => 'Websystem 101',
                'description'  => 'Basic WebSystem',
                'instructor_id'=> 3,
            ],
            [
                'title'        => 'DataBase 101',
                'description'  => 'Basic Database',
                'instructor_id'=> 4,
            ],
        ];
        $this->db->table(tableName: 'courses')->insertBatch(set: $data);
    }
}
