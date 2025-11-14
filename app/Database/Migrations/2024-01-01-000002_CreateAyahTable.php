<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAyahTable extends Migration
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
            'surah_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'comment' => 'ID surah',
            ],
            'number_in_surah' => [
                'type' => 'INT',
                'constraint' => 3,
                'comment' => 'Nomor ayat dalam surah',
            ],
            'number_in_quran' => [
                'type' => 'INT',
                'constraint' => 4,
                'comment' => 'Nomor ayat dalam Al-Quran (1-6236)',
            ],
            'text_arabic' => [
                'type' => 'TEXT',
                'comment' => 'Teks ayat dalam bahasa Arab',
            ],
            'text_latin' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Transliterasi latin',
            ],
            'translation_id' => [
                'type' => 'TEXT',
                'comment' => 'Terjemahan bahasa Indonesia',
            ],
            'tafsir' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Tafsir ayat',
            ],
            'juz' => [
                'type' => 'INT',
                'constraint' => 2,
                'comment' => 'Juz ke berapa',
            ],
            'page' => [
                'type' => 'INT',
                'constraint' => 3,
                'null' => true,
                'comment' => 'Halaman dalam mushaf',
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
        $this->forge->addKey('surah_id');
        $this->forge->addUniqueKey(['surah_id', 'number_in_surah']);
        $this->forge->addForeignKey('surah_id', 'surah', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('ayah');
    }

    public function down()
    {
        $this->forge->dropTable('ayah');
    }
}
