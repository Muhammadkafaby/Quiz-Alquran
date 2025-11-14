<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'user_id',
        'type',
        'title',
        'message',
        'icon',
        'link',
        'is_read',
        'read_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = null;

    /**
     * Get user notifications
     */
    public function getUserNotifications($userId, $limit = 10, $unreadOnly = false)
    {
        $builder = $this->where('user_id', $userId);

        if ($unreadOnly) {
            $builder->where('is_read', false);
        }

        return $builder->orderBy('created_at', 'DESC')
                       ->limit($limit)
                       ->findAll();
    }

    /**
     * Get unread count
     */
    public function getUnreadCount($userId)
    {
        return $this->where('user_id', $userId)
                    ->where('is_read', false)
                    ->countAllResults();
    }

    /**
     * Mark as read
     */
    public function markAsRead($notificationId)
    {
        return $this->update($notificationId, [
            'is_read' => true,
            'read_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Mark all as read
     */
    public function markAllAsRead($userId)
    {
        return $this->where('user_id', $userId)
                    ->where('is_read', false)
                    ->set([
                        'is_read' => true,
                        'read_at' => date('Y-m-d H:i:s')
                    ])
                    ->update();
    }

    /**
     * Send daily quiz reminder
     */
    public function sendDailyQuizReminder($userId)
    {
        // Check if user has daily_quiz_reminder enabled
        $userModel = new UserModel();
        $user = $userModel->find($userId);

        if (!$user || !$user['daily_quiz_reminder']) {
            return false;
        }

        // Check if already completed today
        $quizResultModel = new QuizResultModel();
        $hasCompleted = $quizResultModel->hasCompletedDailyQuiz($userId);

        if ($hasCompleted) {
            return false;
        }

        return $this->insert([
            'user_id' => $userId,
            'type' => 'daily_quiz',
            'title' => 'Quiz Harian Tersedia!',
            'message' => 'Quiz harian hari ini sudah tersedia. Yuk ikuti dan raih skor tertinggi!',
            'icon' => '⭐',
            'link' => '/quiz/daily'
        ]);
    }

    /**
     * Send bulk notifications
     */
    public function sendBulkNotification($userIds, $data)
    {
        $notifications = [];

        foreach ($userIds as $userId) {
            $notifications[] = array_merge($data, [
                'user_id' => $userId,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        return $this->insertBatch($notifications);
    }
}
