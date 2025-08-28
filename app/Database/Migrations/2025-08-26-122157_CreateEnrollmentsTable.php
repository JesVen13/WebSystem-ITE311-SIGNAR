<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEnrollmentsTable extends Migration
{
   public function up(): void
{
    $this->forge->addField(fields: [
        'id'        => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
        'user_id'   => ['type' => 'INT', 'unsigned' => true],
        'course_id' => ['type' => 'INT', 'unsigned' => true],
        'created_at'=> ['type' => 'DATETIME', 'null' => true],
    ]);
    $this->forge->addKey(key: 'id', primary: true);
    $this->forge->addForeignKey(fieldName: 'user_id', tableName: 'users', tableField: 'id', onUpdate: 'CASCADE', onDelete: 'CASCADE');
    $this->forge->addForeignKey(fieldName: 'course_id', tableName: 'courses', tableField: 'id', onUpdate: 'CASCADE', onDelete: 'CASCADE');
    $this->forge->createTable(table: 'enrollments');
}

public function down(): void
{
    $this->forge->dropTable(tableName: 'enrollments');
}

}
