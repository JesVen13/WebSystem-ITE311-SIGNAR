<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateQuizzesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'lesson_id' => ['type' => 'INT', 'unsigned' => true],
            'question' => ['type' => 'TEXT'],
            'answer' => ['type' => 'VARCHAR', 'constraint' => 255],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('lesson_id');
        $this->forge->addForeignKey('lesson_id', 'lessons', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('quizzes', false, ['ENGINE' => 'InnoDB']);
    }

    public function down()
    {
        $this->forge->dropTable('quizzes');
    }
}
