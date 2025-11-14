<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUserPreferences extends Migration
{
    public function up()
    {
        $fields = [
            'theme_preference' => [
                'type' => 'ENUM',
                'constraint' => ['light', 'dark', 'auto'],
                'default' => 'light',
                'after' => 'avatar',
            ],
            'daily_quiz_reminder' => [
                'type' => 'BOOLEAN',
                'default' => true,
                'after' => 'theme_preference',
            ],
            'audio_recitation_enabled' => [
                'type' => 'BOOLEAN',
                'default' => true,
                'after' => 'daily_quiz_reminder',
            ],
            'reciter_preference' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'default' => 'ar.alafasy',
                'after' => 'audio_recitation_enabled',
                'comment' => 'Preferred reciter',
            ],
        ];

        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('users', [
            'theme_preference',
            'daily_quiz_reminder',
            'audio_recitation_enabled',
            'reciter_preference'
        ]);
    }
}
