<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterUsersRoleEnum extends Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE `users` MODIFY `role` ENUM('admin','teacher','student') NOT NULL DEFAULT 'student'");
    }

    public function down()
    {
        $this->db->query("ALTER TABLE `users` MODIFY `role` ENUM('admin','user') NOT NULL DEFAULT 'user'");
    }
}