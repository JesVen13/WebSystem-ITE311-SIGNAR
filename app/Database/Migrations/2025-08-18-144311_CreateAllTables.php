<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAllTables extends Migration
{
    public function up()
    {
        // Users Table
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'name'        => ['type' => 'VARCHAR', 'constraint' => '100'],
            'email'       => ['type' => 'VARCHAR', 'constraint' => '100', 'unique' => true],
            'password'    => ['type' => 'VARCHAR', 'constraint' => '255'],
            'role'        => ['type' => 'ENUM("student","instructor","admin")', 'default' => 'student'],
            'created_at'  => ['type' => 'DATETIME', 'default' => 'CURRENT_TIMESTAMP'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('users', true);

        // Courses Table
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'title'        => ['type' => 'VARCHAR', 'constraint' => '150'],
            'description'  => ['type' => 'TEXT', 'null' => true],
            'instructor_id'=> ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'created_at'   => ['type' => 'DATETIME', 'default' => 'CURRENT_TIMESTAMP'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('instructor_id', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('courses', true);

        // Enrollments Table
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'user_id'    => ['type' => 'INT', 'unsigned' => true],
            'course_id'  => ['type' => 'INT', 'unsigned' => true],
            'enrolled_at'=> ['type' => 'DATETIME', 'default' => 'CURRENT_TIMESTAMP'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('course_id', 'courses', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('enrollments', true);

        // Lessons Table
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'course_id'  => ['type' => 'INT', 'unsigned' => true],
            'title'      => ['type' => 'VARCHAR', 'constraint' => '150'],
            'content'    => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'default' => 'CURRENT_TIMESTAMP'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('course_id', 'courses', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('lessons', true);

        // Quizzes Table
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'lesson_id'  => ['type' => 'INT', 'unsigned' => true],
            'title'      => ['type' => 'VARCHAR', 'constraint' => '150'],
            'created_at' => ['type' => 'DATETIME', 'default' => 'CURRENT_TIMESTAMP'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('lesson_id', 'lessons', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('quizzes', true);

        // Submissions Table
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'quiz_id'      => ['type' => 'INT', 'unsigned' => true],
            'user_id'      => ['type' => 'INT', 'unsigned' => true],
            'answer'       => ['type' => 'TEXT', 'null' => true],
            'score'        => ['type' => 'INT', 'null' => true],
            'submitted_at' => ['type' => 'DATETIME', 'default' => 'CURRENT_TIMESTAMP'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('quiz_id', 'quizzes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('submissions', true);
    }

    public function down()
    {
        $this->forge->dropTable('submissions', true);
        $this->forge->dropTable('quizzes', true);
        $this->forge->dropTable('lessons', true);
        $this->forge->dropTable('enrollments', true);
        $this->forge->dropTable('courses', true);
        $this->forge->dropTable('users', true);
    }
}
