<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateQuizzesTable extends Migration
{
   public function up(): void
{
    $this->forge->addField(fields: [
        'id'        => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
        'lesson_id' => ['type' => 'INT', 'unsigned' => true],
        'question'  => ['type' => 'TEXT'],
        'answer'    => ['type' => 'VARCHAR', 'constraint' => 255],
    ]);
    $this->forge->addKey(key: 'id', primary: true);
    $this->forge->addForeignKey(fieldName: 'lesson_id', tableName: 'lessons', tableField: 'id', onUpdate: 'CASCADE', onDelete: 'CASCADE');
    $this->forge->createTable(table: 'quizzes');
}

public function down(): void
{
    $this->forge->dropTable(tableName: 'quizzes');
}

}
