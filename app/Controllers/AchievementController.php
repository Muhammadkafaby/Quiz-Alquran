<?php

namespace App\Controllers;

use App\Models\AchievementModel;

class AchievementController extends BaseController
{
    protected $achievementModel;

    public function __construct()
    {
        $this->achievementModel = new AchievementModel();
    }

    /**
     * List all achievements
     */
    public function index()
    {
        $userId = session()->get('user_id');
        $achievements = $userId
            ? $this->achievementModel->getAllWithStatus($userId)
            : $this->achievementModel->findAll();

        $data = [
            'title' => 'Pencapaian',
            'achievements' => $achievements
        ];

        return view('achievements/index', $data);
    }

    /**
     * Get user achievements
     */
    public function userAchievements($userId)
    {
        $achievements = $this->achievementModel->getUserAchievements($userId);

        $data = [
            'title' => 'Pencapaian Saya',
            'achievements' => $achievements
        ];

        return view('achievements/user', $data);
    }
}
