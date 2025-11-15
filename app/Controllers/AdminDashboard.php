<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\QuizResultModel;
use App\Models\SurahModel;
use App\Models\AyahModel;
use App\Models\AchievementModel;

class AdminDashboard extends BaseController
{
    protected $userModel;
    protected $quizResultModel;
    protected $surahModel;
    protected $ayahModel;
    protected $achievementModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->quizResultModel = new QuizResultModel();
        $this->surahModel = new SurahModel();
        $this->ayahModel = new AyahModel();
        $this->achievementModel = new AchievementModel();
    }

    public function index()
    {
        // Get overall statistics
        $stats = [
            'total_users' => $this->userModel->countAll(),
            'total_quizzes' => $this->quizResultModel->countAll(),
            'total_surahs' => $this->surahModel->countAll(),
            'total_ayahs' => $this->ayahModel->countAll(),
            'total_achievements' => $this->achievementModel->countAll(),
        ];

        // Get recent activity
        $recentQuizzes = $this->quizResultModel
            ->select('quiz_results.*, users.username')
            ->join('users', 'users.id = quiz_results.user_id')
            ->orderBy('quiz_results.created_at', 'DESC')
            ->limit(10)
            ->findAll();

        // Get top users
        $topUsers = $this->userModel
            ->orderBy('total_score', 'DESC')
            ->limit(10)
            ->findAll();

        // Get quiz statistics by type
        $quizStats = $this->getQuizStatsByType();

        // Get daily quiz activity (last 7 days)
        $dailyActivity = $this->getDailyQuizActivity();

        $data = [
            'title' => 'Admin Dashboard',
            'stats' => $stats,
            'recentQuizzes' => $recentQuizzes,
            'topUsers' => $topUsers,
            'quizStats' => $quizStats,
            'dailyActivity' => $dailyActivity,
        ];

        return view('admin/dashboard', $data);
    }

    private function getQuizStatsByType()
    {
        $db = \Config\Database::connect();
        $query = $db->query("
            SELECT
                quiz_type,
                COUNT(*) as total_quizzes,
                AVG(score) as avg_score,
                MAX(score) as max_score,
                AVG(time_taken) as avg_time
            FROM quiz_results
            GROUP BY quiz_type
        ");

        return $query->getResultArray();
    }

    private function getDailyQuizActivity()
    {
        $db = \Config\Database::connect();
        $query = $db->query("
            SELECT
                DATE(created_at) as date,
                COUNT(*) as total_quizzes,
                COUNT(DISTINCT user_id) as unique_users
            FROM quiz_results
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
            GROUP BY DATE(created_at)
            ORDER BY date ASC
        ");

        return $query->getResultArray();
    }

    public function users()
    {
        $users = $this->userModel
            ->select('users.*, COUNT(quiz_results.id) as total_quizzes_taken')
            ->join('quiz_results', 'quiz_results.user_id = users.id', 'left')
            ->groupBy('users.id')
            ->orderBy('users.created_at', 'DESC')
            ->findAll();

        $data = [
            'title' => 'Manajemen User',
            'users' => $users,
        ];

        return view('admin/users', $data);
    }

    public function quizzes()
    {
        $page = $this->request->getGet('page') ?? 1;
        $perPage = 20;

        $quizzes = $this->quizResultModel
            ->select('quiz_results.*, users.username, users.email')
            ->join('users', 'users.id = quiz_results.user_id')
            ->orderBy('quiz_results.created_at', 'DESC')
            ->paginate($perPage);

        $data = [
            'title' => 'Daftar Quiz',
            'quizzes' => $quizzes,
            'pager' => $this->quizResultModel->pager,
        ];

        return view('admin/quizzes', $data);
    }

    public function content()
    {
        $surahs = $this->surahModel->findAll();

        // Get count of ayahs for each surah
        foreach ($surahs as &$surah) {
            $surah['ayah_count'] = $this->ayahModel
                ->where('surah_id', $surah['id'])
                ->countAllResults();
        }

        $data = [
            'title' => 'Manajemen Konten Al-Qur\'an',
            'surahs' => $surahs,
        ];

        return view('admin/content', $data);
    }

    public function achievements()
    {
        $achievements = $this->achievementModel->findAll();

        // Get count of users who unlocked each achievement
        foreach ($achievements as &$achievement) {
            $db = \Config\Database::connect();
            $query = $db->query("
                SELECT COUNT(DISTINCT user_id) as total_users
                FROM user_achievements
                WHERE achievement_id = ?
            ", [$achievement['id']]);

            $result = $query->getRow();
            $achievement['unlocked_by'] = $result->total_users ?? 0;
        }

        $data = [
            'title' => 'Manajemen Achievement',
            'achievements' => $achievements,
        ];

        return view('admin/achievements', $data);
    }

    public function analytics()
    {
        // Comprehensive analytics data
        $analytics = [
            'user_growth' => $this->getUserGrowth(),
            'quiz_completion_rate' => $this->getQuizCompletionRate(),
            'popular_surahs' => $this->getPopularSurahs(),
            'peak_hours' => $this->getPeakHours(),
            'achievement_progress' => $this->getAchievementProgress(),
        ];

        $data = [
            'title' => 'Analytics Dashboard',
            'analytics' => $analytics,
        ];

        return view('admin/analytics', $data);
    }

    private function getUserGrowth()
    {
        $db = \Config\Database::connect();
        $query = $db->query("
            SELECT
                DATE_FORMAT(created_at, '%Y-%m') as month,
                COUNT(*) as new_users
            FROM users
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
            GROUP BY DATE_FORMAT(created_at, '%Y-%m')
            ORDER BY month ASC
        ");

        return $query->getResultArray();
    }

    private function getQuizCompletionRate()
    {
        $db = \Config\Database::connect();
        $query = $db->query("
            SELECT
                quiz_type,
                COUNT(*) as total,
                SUM(CASE WHEN score >= 70 THEN 1 ELSE 0 END) as passed,
                ROUND(SUM(CASE WHEN score >= 70 THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 2) as pass_rate
            FROM quiz_results
            GROUP BY quiz_type
        ");

        return $query->getResultArray();
    }

    private function getPopularSurahs()
    {
        $db = \Config\Database::connect();
        $query = $db->query("
            SELECT
                s.name_latin,
                s.name_translation,
                COUNT(b.id) as bookmark_count
            FROM surah s
            LEFT JOIN ayah a ON a.surah_id = s.id
            LEFT JOIN bookmarks b ON b.ayah_id = a.id
            GROUP BY s.id
            ORDER BY bookmark_count DESC
            LIMIT 10
        ");

        return $query->getResultArray();
    }

    private function getPeakHours()
    {
        $db = \Config\Database::connect();
        $query = $db->query("
            SELECT
                HOUR(created_at) as hour,
                COUNT(*) as quiz_count
            FROM quiz_results
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            GROUP BY HOUR(created_at)
            ORDER BY hour ASC
        ");

        return $query->getResultArray();
    }

    private function getAchievementProgress()
    {
        $db = \Config\Database::connect();
        $query = $db->query("
            SELECT
                a.name,
                a.code,
                COUNT(DISTINCT ua.user_id) as users_unlocked,
                (SELECT COUNT(*) FROM users) as total_users,
                ROUND(COUNT(DISTINCT ua.user_id) * 100.0 / (SELECT COUNT(*) FROM users), 2) as unlock_rate
            FROM achievements a
            LEFT JOIN user_achievements ua ON ua.achievement_id = a.id
            GROUP BY a.id
            ORDER BY unlock_rate DESC
        ");

        return $query->getResultArray();
    }

    public function deleteUser($id)
    {
        if ($this->request->getMethod() === 'post') {
            $this->userModel->delete($id);
            return redirect()->to('/admin/users')->with('message', 'User berhasil dihapus');
        }

        return redirect()->to('/admin/users');
    }

    public function export()
    {
        $type = $this->request->getGet('type') ?? 'users';

        switch ($type) {
            case 'users':
                return $this->exportUsers();
            case 'quizzes':
                return $this->exportQuizzes();
            case 'analytics':
                return $this->exportAnalytics();
            default:
                return redirect()->back();
        }
    }

    private function exportUsers()
    {
        $users = $this->userModel->findAll();

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="users_export_' . date('Y-m-d') . '.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Username', 'Email', 'Total Score', 'Quiz Completed', 'Created At']);

        foreach ($users as $user) {
            fputcsv($output, [
                $user['id'],
                $user['username'],
                $user['email'],
                $user['total_score'],
                $user['quiz_completed'],
                $user['created_at'],
            ]);
        }

        fclose($output);
        exit;
    }

    private function exportQuizzes()
    {
        $quizzes = $this->quizResultModel
            ->select('quiz_results.*, users.username')
            ->join('users', 'users.id = quiz_results.user_id')
            ->findAll();

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="quizzes_export_' . date('Y-m-d') . '.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Username', 'Quiz Type', 'Score', 'Total Questions', 'Time Taken', 'Created At']);

        foreach ($quizzes as $quiz) {
            fputcsv($output, [
                $quiz['id'],
                $quiz['username'],
                $quiz['quiz_type'],
                $quiz['score'],
                $quiz['total_questions'],
                $quiz['time_taken'],
                $quiz['created_at'],
            ]);
        }

        fclose($output);
        exit;
    }

    private function exportAnalytics()
    {
        $stats = $this->getQuizStatsByType();

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="analytics_export_' . date('Y-m-d') . '.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Quiz Type', 'Total Quizzes', 'Average Score', 'Max Score', 'Average Time']);

        foreach ($stats as $stat) {
            fputcsv($output, [
                $stat['quiz_type'],
                $stat['total_quizzes'],
                round($stat['avg_score'], 2),
                $stat['max_score'],
                round($stat['avg_time'], 2),
            ]);
        }

        fclose($output);
        exit;
    }
}
