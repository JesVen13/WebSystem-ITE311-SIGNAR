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
                'description'  => 'Basic WebSystem Development',
                'instructor_id'=> 2, 
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'title'        => 'Database 101',
                'description'  => 'Basic Database Management',
                'instructor_id'=> 2, 
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
        ];
        
        $this->db->table('courses')->insertBatch($data);
    }
}
