<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSurahTable extends Migration
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
            'number' => [
                'type' => 'INT',
                'constraint' => 3,
                'comment' => 'Nomor surah (1-114)',
            ],
            'name_arabic' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'comment' => 'Nama surah dalam bahasa Arab',
            ],
            'name_latin' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'comment' => 'Nama surah dalam latin',
            ],
            'name_translation' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'comment' => 'Arti nama surah',
            ],
            'number_of_verses' => [
                'type' => 'INT',
                'constraint' => 3,
                'comment' => 'Jumlah ayat dalam surah',
            ],
            'revelation' => [
                'type' => 'ENUM',
                'constraint' => ['Makkiyah', 'Madaniyah'],
                'comment' => 'Tempat turunnya surah',
            ],
            'tafsir' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Tafsir ringkas surah',
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
        $this->forge->addUniqueKey('number');
        $this->forge->createTable('surah');
    }

    public function down()
    {
        $this->forge->dropTable('surah');
    }
}
