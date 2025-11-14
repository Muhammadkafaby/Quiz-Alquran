<?php

namespace App\Models;

use CodeIgniter\Model;

class QuizResultModel extends Model
{
    protected $table = 'quiz_results';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'user_id',
        'quiz_type',
        'total_questions',
        'correct_answers',
        'score',
        'time_taken',
        'quiz_date',
        'answers_data'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Save quiz result
     */
    public function saveResult($data)
    {
        // Ensure quiz_date is set
        if (!isset($data['quiz_date'])) {
            $data['quiz_date'] = date('Y-m-d');
        }

        // Convert answers_data to JSON if it's an array
        if (isset($data['answers_data']) && is_array($data['answers_data'])) {
            $data['answers_data'] = json_encode($data['answers_data']);
        }

        return $this->insert($data);
    }

    /**
     * Get user quiz history
     */
    public function getUserHistory($userId, $limit = 10)
    {
        return $this->where('user_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Get leaderboard by quiz type
     */
    public function getLeaderboard($quizType = null, $limit = 10, $date = null)
    {
        $builder = $this->select('quiz_results.*, users.username, users.full_name, users.avatar')
                        ->join('users', 'users.id = quiz_results.user_id', 'left')
                        ->orderBy('score', 'DESC')
                        ->limit($limit);

        if ($quizType) {
            $builder->where('quiz_type', $quizType);
        }

        if ($date) {
            $builder->where('quiz_date', $date);
        }

        return $builder->findAll();
    }

    /**
     * Get daily quiz leaderboard
     */
    public function getDailyLeaderboard($date = null, $limit = 10)
    {
        if (!$date) {
            $date = date('Y-m-d');
        }

        return $this->getLeaderboard('daily', $limit, $date);
    }

    /**
     * Get user statistics
     */
    public function getUserStats($userId)
    {
        $results = $this->where('user_id', $userId)->findAll();

        $stats = [
            'total_quiz' => count($results),
            'total_questions' => 0,
            'total_correct' => 0,
            'average_score' => 0,
            'best_score' => 0,
            'by_type' => []
        ];

        $totalScore = 0;

        foreach ($results as $result) {
            $stats['total_questions'] += $result['total_questions'];
            $stats['total_correct'] += $result['correct_answers'];
            $totalScore += $result['score'];

            if ($result['score'] > $stats['best_score']) {
                $stats['best_score'] = $result['score'];
            }

            // Group by type
            if (!isset($stats['by_type'][$result['quiz_type']])) {
                $stats['by_type'][$result['quiz_type']] = [
                    'count' => 0,
                    'total_score' => 0,
                    'avg_score' => 0
                ];
            }

            $stats['by_type'][$result['quiz_type']]['count']++;
            $stats['by_type'][$result['quiz_type']]['total_score'] += $result['score'];
        }

        if (count($results) > 0) {
            $stats['average_score'] = round($totalScore / count($results), 2);
        }

        // Calculate average by type
        foreach ($stats['by_type'] as $type => &$data) {
            if ($data['count'] > 0) {
                $data['avg_score'] = round($data['total_score'] / $data['count'], 2);
            }
        }

        return $stats;
    }

    /**
     * Check if user has completed daily quiz today
     */
    public function hasCompletedDailyQuiz($userId, $date = null)
    {
        if (!$date) {
            $date = date('Y-m-d');
        }

        $result = $this->where('user_id', $userId)
                       ->where('quiz_type', 'daily')
                       ->where('quiz_date', $date)
                       ->first();

        return !empty($result);
    }
}
