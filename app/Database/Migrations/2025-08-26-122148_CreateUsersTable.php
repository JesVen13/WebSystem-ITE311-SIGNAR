<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up(): void
{
    $this->forge->addField(fields: [
        'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
        'name'       => ['type' => 'VARCHAR', 'constraint' => 100],
        'email'      => ['type' => 'VARCHAR', 'constraint' => 150, 'unique' => true],
        'password'   => ['type' => 'VARCHAR', 'constraint' => 255],
        'role'       => ['type' => 'ENUM("student","instructor","admin")', 'default' => 'student'],
        'created_at' => ['type' => 'DATETIME', 'null' => true],
        'updated_at' => ['type' => 'DATETIME', 'null' => true],
    ]);
    $this->forge->addKey(key: 'id', primary: true);
    $this->forge->createTable(table: 'users');
}

public function down(): void
{
    $this->forge->dropTable(tableName: 'users');
}

}
