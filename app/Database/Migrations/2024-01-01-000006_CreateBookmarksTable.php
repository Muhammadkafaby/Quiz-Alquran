<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBookmarksTable extends Migration
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
                'comment' => 'ID user',
            ],
            'ayah_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'comment' => 'ID ayat',
            ],
            'note' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Catatan pribadi',
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
        $this->forge->addKey('ayah_id');
        $this->forge->addUniqueKey(['user_id', 'ayah_id']);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('ayah_id', 'ayah', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('bookmarks');
    }

    public function down()
    {
        $this->forge->dropTable('bookmarks');
    }
}
