<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDailyQuizTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'quiz_date' => [
                'type' => 'DATE',
                'unique' => true,
                'comment' => 'Tanggal quiz',
            ],
            'questions_data' => [
                'type' => 'JSON',
                'comment' => 'Data soal quiz (array of questions)',
            ],
            'total_questions' => [
                'type' => 'INT',
                'constraint' => 3,
                'default' => 10,
                'comment' => 'Jumlah soal',
            ],
            'theme' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
                'comment' => 'Tema quiz harian',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('quiz_date');
        $this->forge->createTable('daily_quiz');
    }

    public function down()
    {
        $this->forge->dropTable('daily_quiz');
    }
}
