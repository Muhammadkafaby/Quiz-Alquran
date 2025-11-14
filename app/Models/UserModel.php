<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'username',
        'email',
        'password',
        'full_name',
        'avatar',
        'total_score',
        'quiz_completed',
        'last_active'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'username' => 'required|min_length[3]|max_length[100]|is_unique[users.username]',
        'email' => 'required|valid_email|is_unique[users.email]',
        'password' => 'required|min_length[6]',
    ];

    protected $validationMessages = [
        'username' => [
            'required' => 'Username harus diisi',
            'min_length' => 'Username minimal 3 karakter',
            'is_unique' => 'Username sudah digunakan',
        ],
        'email' => [
            'required' => 'Email harus diisi',
            'valid_email' => 'Email tidak valid',
            'is_unique' => 'Email sudah terdaftar',
        ],
        'password' => [
            'required' => 'Password harus diisi',
            'min_length' => 'Password minimal 6 karakter',
        ],
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }

    /**
     * Update user score
     */
    public function updateScore($userId, $score)
    {
        $user = $this->find($userId);
        if ($user) {
            return $this->update($userId, [
                'total_score' => $user['total_score'] + $score,
                'quiz_completed' => $user['quiz_completed'] + 1,
                'last_active' => date('Y-m-d H:i:s')
            ]);
        }
        return false;
    }

    /**
     * Get top users (leaderboard)
     */
    public function getTopUsers($limit = 10)
    {
        return $this->orderBy('total_score', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Get user rank
     */
    public function getUserRank($userId)
    {
        $user = $this->find($userId);
        if (!$user) return null;

        $rank = $this->where('total_score >', $user['total_score'])->countAllResults() + 1;
        return $rank;
    }
}
