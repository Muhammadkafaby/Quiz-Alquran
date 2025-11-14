<?php

namespace App\Models;

use CodeIgniter\Model;

class AchievementModel extends Model
{
    protected $table = 'achievements';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'code',
        'name',
        'description',
        'icon',
        'badge_color',
        'points',
        'requirement'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = null;

    /**
     * Get user achievements
     */
    public function getUserAchievements($userId)
    {
        return $this->select('achievements.*, user_achievements.unlocked_at')
                    ->join('user_achievements', 'user_achievements.achievement_id = achievements.id')
                    ->where('user_achievements.user_id', $userId)
                    ->orderBy('user_achievements.unlocked_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get all achievements with unlock status
     */
    public function getAllWithStatus($userId)
    {
        $achievements = $this->findAll();
        $unlocked = $this->getUserAchievements($userId);

        $unlockedIds = array_column($unlocked, 'id');

        foreach ($achievements as &$achievement) {
            $achievement['is_unlocked'] = in_array($achievement['id'], $unlockedIds);
            $achievement['requirement'] = json_decode($achievement['requirement'], true);
        }

        return $achievements;
    }

    /**
     * Check and unlock achievement
     */
    public function checkAndUnlock($userId, $achievementCode)
    {
        $achievement = $this->where('code', $achievementCode)->first();

        if (!$achievement) {
            return false;
        }

        // Check if already unlocked
        $db = \Config\Database::connect();
        $builder = $db->table('user_achievements');

        $existing = $builder->where('user_id', $userId)
                           ->where('achievement_id', $achievement['id'])
                           ->get()
                           ->getRowArray();

        if ($existing) {
            return false; // Already unlocked
        }

        // Unlock achievement
        $result = $builder->insert([
            'user_id' => $userId,
            'achievement_id' => $achievement['id'],
            'unlocked_at' => date('Y-m-d H:i:s')
        ]);

        if ($result) {
            // Update user points
            $userModel = new UserModel();
            $user = $userModel->find($userId);
            $userModel->update($userId, [
                'total_score' => $user['total_score'] + $achievement['points']
            ]);

            // Send notification
            $this->sendAchievementNotification($userId, $achievement);
        }

        return $result;
    }

    /**
     * Send achievement notification
     */
    private function sendAchievementNotification($userId, $achievement)
    {
        $notificationModel = new NotificationModel();
        $notificationModel->insert([
            'user_id' => $userId,
            'type' => 'achievement',
            'title' => 'Achievement Unlocked!',
            'message' => 'Anda mendapatkan achievement: ' . $achievement['name'],
            'icon' => $achievement['icon'],
            'link' => '/auth/profile#achievements'
        ]);
    }

    /**
     * Check user progress for all achievements
     */
    public function checkUserProgress($userId)
    {
        $userModel = new UserModel();
        $quizResultModel = new QuizResultModel();

        $user = $userModel->find($userId);
        $stats = $quizResultModel->getUserStats($userId);

        $achievements = [
            // Quiz achievements
            'first_quiz' => $stats['total_quiz'] >= 1,
            'quiz_master_10' => $stats['total_quiz'] >= 10,
            'quiz_master_50' => $stats['total_quiz'] >= 50,
            'quiz_master_100' => $stats['total_quiz'] >= 100,

            // Score achievements
            'perfect_score' => $stats['best_score'] >= 100,
            'high_scorer' => $user['total_score'] >= 1000,
            'legendary_scorer' => $user['total_score'] >= 10000,

            // Streak achievements
            'daily_warrior_7' => $this->checkDailyStreak($userId, 7),
            'daily_warrior_30' => $this->checkDailyStreak($userId, 30),

            // Special achievements
            'quran_lover' => $this->checkBookmarkCount($userId, 10),
            'early_bird' => true, // First 100 users
        ];

        foreach ($achievements as $code => $unlocked) {
            if ($unlocked) {
                $this->checkAndUnlock($userId, $code);
            }
        }
    }

    /**
     * Check daily quiz streak
     */
    private function checkDailyStreak($userId, $days)
    {
        $quizResultModel = new QuizResultModel();

        $results = $quizResultModel->where('user_id', $userId)
                                   ->where('quiz_type', 'daily')
                                   ->orderBy('quiz_date', 'DESC')
                                   ->limit($days)
                                   ->findAll();

        if (count($results) < $days) {
            return false;
        }

        $currentDate = date('Y-m-d');
        for ($i = 0; $i < $days; $i++) {
            $expectedDate = date('Y-m-d', strtotime("-$i days"));
            if (!isset($results[$i]) || $results[$i]['quiz_date'] !== $expectedDate) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check bookmark count
     */
    private function checkBookmarkCount($userId, $count)
    {
        $bookmarkModel = new BookmarkModel();
        return $bookmarkModel->getBookmarkCount($userId) >= $count;
    }
}
