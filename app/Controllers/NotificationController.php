<?php

namespace App\Controllers;

use App\Models\NotificationModel;

class NotificationController extends BaseController
{
    protected $notificationModel;

    public function __construct()
    {
        $this->notificationModel = new NotificationModel();
    }

    /**
     * List all notifications
     */
    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $userId = session()->get('user_id');
        $notifications = $this->notificationModel->getUserNotifications($userId, 50);

        $data = [
            'title' => 'Notifikasi',
            'notifications' => $notifications
        ];

        return view('notifications/index', $data);
    }

    /**
     * Mark as read (AJAX)
     */
    public function markAsRead($notificationId)
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['success' => false]);
        }

        $result = $this->notificationModel->markAsRead($notificationId);

        return $this->response->setJSON([
            'success' => $result
        ]);
    }

    /**
     * Mark all as read
     */
    public function markAllAsRead()
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['success' => false]);
        }

        $userId = session()->get('user_id');
        $result = $this->notificationModel->markAllAsRead($userId);

        return $this->response->setJSON([
            'success' => $result
        ]);
    }

    /**
     * Get unread count (AJAX)
     */
    public function getUnreadCount()
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['count' => 0]);
        }

        $userId = session()->get('user_id');
        $count = $this->notificationModel->getUnreadCount($userId);

        return $this->response->setJSON([
            'count' => $count
        ]);
    }
}
