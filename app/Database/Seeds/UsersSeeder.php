<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name'  => 'Signar kupal',
                'email' => 'Arayko@example.com',
                'role'  => 'student',
            ],
            [
                'name'  => 'Kenneth Anderson',
                'email' => 'Kupal@gmail.com',
                'role'  => 'instructor',
            ],
        ];
        $this->db->table(tableName: 'users')->insertBatch(set: $data);
    }
}
