<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLessonsTable extends Migration
{
   public function up(): void
{
    $this->forge->addField(fields: [
        'id'        => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
        'course_id' => ['type' => 'INT', 'unsigned' => true],
        'title'     => ['type' => 'VARCHAR', 'constraint' => 200],
        'content'   => ['type' => 'TEXT'],
        'created_at'=> ['type' => 'DATETIME', 'null' => true],
    ]);
    $this->forge->addKey(key: 'id', primary: true);
    $this->forge->addForeignKey(fieldName: 'course_id', tableName: 'courses', tableField: 'id', onUpdate: 'CASCADE', onDelete: 'CASCADE');
    $this->forge->createTable(table: 'lessons');
}

public function down(): void
{
    $this->forge->dropTable(tableName: 'lessons');
}

}
