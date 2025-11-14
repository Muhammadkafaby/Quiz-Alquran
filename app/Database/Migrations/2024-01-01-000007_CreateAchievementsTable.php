<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAchievementsTable extends Migration
{
    public function up()
    {
        // Achievements definition table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'code' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'unique' => true,
                'comment' => 'Achievement code',
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'comment' => 'Achievement name',
            ],
            'description' => [
                'type' => 'TEXT',
                'comment' => 'Achievement description',
            ],
            'icon' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'comment' => 'Icon emoji or class',
            ],
            'badge_color' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'comment' => 'Badge color',
            ],
            'points' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'comment' => 'Points rewarded',
            ],
            'requirement' => [
                'type' => 'JSON',
                'comment' => 'Requirements to unlock',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('achievements');

        // User achievements (unlocked)
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
            ],
            'achievement_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'unlocked_at' => [
                'type' => 'DATETIME',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->addUniqueKey(['user_id', 'achievement_id']);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('achievement_id', 'achievements', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('user_achievements');
    }

    public function down()
    {
        $this->forge->dropTable('user_achievements');
        $this->forge->dropTable('achievements');
    }
}
