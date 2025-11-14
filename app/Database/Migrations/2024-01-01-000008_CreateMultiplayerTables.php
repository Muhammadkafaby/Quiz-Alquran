<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMultiplayerTables extends Migration
{
    public function up()
    {
        // Multiplayer rooms
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'room_code' => [
                'type' => 'VARCHAR',
                'constraint' => '10',
                'unique' => true,
                'comment' => 'Unique room code',
            ],
            'host_user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'comment' => 'Room creator',
            ],
            'quiz_type' => [
                'type' => 'ENUM',
                'constraint' => ['tebak_lanjutan', 'tebak_surah', 'terjemahan', 'mixed'],
            ],
            'max_players' => [
                'type' => 'INT',
                'constraint' => 2,
                'default' => 4,
            ],
            'questions_count' => [
                'type' => 'INT',
                'constraint' => 2,
                'default' => 10,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['waiting', 'playing', 'finished'],
                'default' => 'waiting',
            ],
            'questions_data' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'started_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'finished_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('room_code');
        $this->forge->addKey('host_user_id');
        $this->forge->addForeignKey('host_user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('multiplayer_rooms');

        // Room participants
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'room_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'score' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'correct_answers' => [
                'type' => 'INT',
                'constraint' => 3,
                'default' => 0,
            ],
            'is_ready' => [
                'type' => 'BOOLEAN',
                'default' => false,
            ],
            'answers_data' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'joined_at' => [
                'type' => 'DATETIME',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('room_id');
        $this->forge->addKey('user_id');
        $this->forge->addUniqueKey(['room_id', 'user_id']);
        $this->forge->addForeignKey('room_id', 'multiplayer_rooms', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('multiplayer_participants');
    }

    public function down()
    {
        $this->forge->dropTable('multiplayer_participants');
        $this->forge->dropTable('multiplayer_rooms');
    }
}
