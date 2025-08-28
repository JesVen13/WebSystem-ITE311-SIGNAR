<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSubmissionsTable extends Migration
{
   public function up(): void
{
    $this->forge->addField(fields: [
        'id'        => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
        'quiz_id'   => ['type' => 'INT', 'unsigned' => true],
        'user_id'   => ['type' => 'INT', 'unsigned' => true],
        'answer'    => ['type' => 'VARCHAR', 'constraint' => 255],
        'is_correct'=> ['type' => 'BOOLEAN', 'default' => false],
        'submitted_at' => ['type' => 'DATETIME', 'null' => true],
    ]);
    $this->forge->addKey(key: 'id', primary: true);
    $this->forge->addForeignKey(fieldName: 'quiz_id', tableName: 'quizzes', tableField: 'id', onUpdate: 'CASCADE', onDelete: 'CASCADE');
    $this->forge->addForeignKey(fieldName: 'user_id', tableName: 'users', tableField: 'id', onUpdate: 'CASCADE', onDelete: 'CASCADE');
    $this->forge->createTable(table: 'submissions');
}

public function down(): void
{
    $this->forge->dropTable(tableName: 'submissions');
}

}
