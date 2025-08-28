<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCoursesTable extends Migration
{
   public function up(): void
{
    $this->forge->addField(fields: [
        'id'          => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
        'title'       => ['type' => 'VARCHAR', 'constraint' => 200],
        'description' => ['type' => 'TEXT'],
        'instructor_id' => ['type' => 'INT', 'unsigned' => true],
        'created_at'  => ['type' => 'DATETIME', 'null' => true],
        'updated_at'  => ['type' => 'DATETIME', 'null' => true],
    ]);
    $this->forge->addKey(key: 'id', primary: true);
    $this->forge->addForeignKey(fieldName: 'instructor_id', tableName: 'users', tableField: 'id', onUpdate: 'CASCADE', onDelete: 'CASCADE');
    $this->forge->createTable(table: 'courses');
}

public function down()
{
    $this->forge->dropTable('courses');
}

}
