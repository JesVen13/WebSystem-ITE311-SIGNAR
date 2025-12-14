<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'name'        => 'Admin User',
                'email'       => 'admin@gmail.com',
                'password'    => password_hash('admin123', PASSWORD_DEFAULT),
                'role'        => 'admin',
                'is_deleted'  => 0,
                'is_restricted' => 0,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'name'        => 'Teacher the kupal',
                'email'       => 'teacher@gmail.com',
                'password'    => password_hash('teacher123', PASSWORD_DEFAULT),
                'role'        => 'teacher',
                'is_deleted'  => 0,
                'is_restricted' => 0,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'name'        => 'Student One',
                'email'       => 'student1@gmail.com',
                'password'    => password_hash('student123', PASSWORD_DEFAULT),
                'role'        => 'student',
                'is_deleted'  => 0,
                'is_restricted' => 0,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'name'        => 'Student Two',
                'email'       => 'student2@gmail.com',
                'password'    => password_hash('student123', PASSWORD_DEFAULT),
                'role'        => 'student',
                'is_deleted'  => 0,
                'is_restricted' => 0,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
        ];
        
        $this->db->table('users')->insertBatch($data);
    }
}
