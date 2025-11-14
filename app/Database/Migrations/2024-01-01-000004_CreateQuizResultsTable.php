<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateQuizResultsTable extends Migration
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
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'ID user (null jika guest)',
            ],
            'quiz_type' => [
                'type' => 'ENUM',
                'constraint' => ['tebak_lanjutan', 'tebak_surah', 'terjemahan', 'daily'],
                'comment' => 'Jenis quiz',
            ],
            'total_questions' => [
                'type' => 'INT',
                'constraint' => 3,
                'comment' => 'Total soal',
            ],
            'correct_answers' => [
                'type' => 'INT',
                'constraint' => 3,
                'comment' => 'Jawaban benar',
            ],
            'score' => [
                'type' => 'INT',
                'constraint' => 5,
                'comment' => 'Skor yang didapat',
            ],
            'time_taken' => [
                'type' => 'INT',
                'constraint' => 11,
                'comment' => 'Waktu pengerjaan (detik)',
            ],
            'quiz_date' => [
                'type' => 'DATE',
                'comment' => 'Tanggal quiz (untuk daily quiz)',
            ],
            'answers_data' => [
                'type' => 'JSON',
                'null' => true,
                'comment' => 'Data jawaban lengkap',
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
        $this->forge->addKey('user_id');
        $this->forge->addKey('quiz_type');
        $this->forge->addKey('quiz_date');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('quiz_results');
    }

    public function down()
    {
        $this->forge->dropTable('quiz_results');
    }
}
