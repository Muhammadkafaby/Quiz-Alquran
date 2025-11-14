<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AchievementSeeder extends Seeder
{
    public function run()
    {
        $achievements = [
            // Beginner Achievements
            [
                'code' => 'first_quiz',
                'name' => 'Langkah Pertama',
                'description' => 'Menyelesaikan quiz pertama Anda',
                'icon' => '🎯',
                'badge_color' => '#3498db',
                'points' => 10,
                'requirement' => json_encode(['total_quiz' => 1])
            ],
            [
                'code' => 'perfect_score',
                'name' => 'Sempurna!',
                'description' => 'Mendapatkan skor 100 dalam satu quiz',
                'icon' => '💯',
                'badge_color' => '#f39c12',
                'points' => 50,
                'requirement' => json_encode(['perfect_score' => true])
            ],

            // Quiz Count Achievements
            [
                'code' => 'quiz_master_10',
                'name' => 'Quiz Master',
                'description' => 'Menyelesaikan 10 quiz',
                'icon' => '🌟',
                'badge_color' => '#9b59b6',
                'points' => 25,
                'requirement' => json_encode(['total_quiz' => 10])
            ],
            [
                'code' => 'quiz_master_50',
                'name' => 'Quiz Champion',
                'description' => 'Menyelesaikan 50 quiz',
                'icon' => '🏅',
                'badge_color' => '#e74c3c',
                'points' => 100,
                'requirement' => json_encode(['total_quiz' => 50])
            ],
            [
                'code' => 'quiz_master_100',
                'name' => 'Quiz Legend',
                'description' => 'Menyelesaikan 100 quiz',
                'icon' => '👑',
                'badge_color' => '#c0392b',
                'points' => 250,
                'requirement' => json_encode(['total_quiz' => 100])
            ],

            // Score Achievements
            [
                'code' => 'high_scorer',
                'name' => 'Pengumpul Poin',
                'description' => 'Mengumpulkan 1000 total poin',
                'icon' => '💰',
                'badge_color' => '#16a085',
                'points' => 50,
                'requirement' => json_encode(['total_score' => 1000])
            ],
            [
                'code' => 'legendary_scorer',
                'name' => 'Legenda Skor',
                'description' => 'Mengumpulkan 10000 total poin',
                'icon' => '💎',
                'badge_color' => '#1abc9c',
                'points' => 200,
                'requirement' => json_encode(['total_score' => 10000])
            ],

            // Daily Quiz Achievements
            [
                'code' => 'daily_warrior_7',
                'name' => 'Pejuang Mingguan',
                'description' => 'Menyelesaikan quiz harian 7 hari berturut-turut',
                'icon' => '🔥',
                'badge_color' => '#e67e22',
                'points' => 75,
                'requirement' => json_encode(['daily_streak' => 7])
            ],
            [
                'code' => 'daily_warrior_30',
                'name' => 'Pejuang Bulanan',
                'description' => 'Menyelesaikan quiz harian 30 hari berturut-turut',
                'icon' => '🚀',
                'badge_color' => '#d35400',
                'points' => 300,
                'requirement' => json_encode(['daily_streak' => 30])
            ],

            // Special Achievements
            [
                'code' => 'quran_lover',
                'name' => 'Pencinta Al-Qur\'an',
                'description' => 'Menyimpan 10 ayat favorit',
                'icon' => '📚',
                'badge_color' => '#27ae60',
                'points' => 30,
                'requirement' => json_encode(['bookmarks' => 10])
            ],
            [
                'code' => 'early_bird',
                'name' => 'Pengguna Awal',
                'description' => 'Salah satu dari 100 pengguna pertama',
                'icon' => '🐦',
                'badge_color' => '#3498db',
                'points' => 100,
                'requirement' => json_encode(['early_user' => true])
            ],
            [
                'code' => 'social_butterfly',
                'name' => 'Kupu-Kupu Sosial',
                'description' => 'Berbagi hasil quiz 5 kali',
                'icon' => '🦋',
                'badge_color' => '#9b59b6',
                'points' => 25,
                'requirement' => json_encode(['shares' => 5])
            ],
            [
                'code' => 'multiplayer_pro',
                'name' => 'Multiplayer Pro',
                'description' => 'Memenangkan 10 game multiplayer',
                'icon' => '🎮',
                'badge_color' => '#e74c3c',
                'points' => 100,
                'requirement' => json_encode(['multiplayer_wins' => 10])
            ],

            // Type-specific Achievements
            [
                'code' => 'lanjutan_master',
                'name' => 'Master Lanjutan Ayat',
                'description' => 'Menyelesaikan 20 quiz tebak lanjutan',
                'icon' => '📖',
                'badge_color' => '#1abc9c',
                'points' => 50,
                'requirement' => json_encode(['quiz_type' => 'tebak_lanjutan', 'count' => 20])
            ],
            [
                'code' => 'surah_expert',
                'name' => 'Expert Nama Surah',
                'description' => 'Menyelesaikan 20 quiz tebak surah',
                'icon' => '🕌',
                'badge_color' => '#16a085',
                'points' => 50,
                'requirement' => json_encode(['quiz_type' => 'tebak_surah', 'count' => 20])
            ],
            [
                'code' => 'translation_guru',
                'name' => 'Guru Terjemahan',
                'description' => 'Menyelesaikan 20 quiz terjemahan',
                'icon' => '🌙',
                'badge_color' => '#2980b9',
                'points' => 50,
                'requirement' => json_encode(['quiz_type' => 'terjemahan', 'count' => 20])
            ]
        ];

        $this->db->table('achievements')->insertBatch($achievements);
    }
}
