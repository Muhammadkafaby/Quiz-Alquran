<?php

namespace App\Models;

use CodeIgniter\Model;

class MultiplayerModel extends Model
{
    protected $table = 'multiplayer_rooms';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'room_code',
        'host_user_id',
        'quiz_type',
        'max_players',
        'questions_count',
        'status',
        'questions_data',
        'started_at',
        'finished_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = null;

    /**
     * Generate unique room code
     */
    public function generateRoomCode()
    {
        do {
            $code = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
        } while ($this->where('room_code', $code)->first());

        return $code;
    }

    /**
     * Create new room
     */
    public function createRoom($hostUserId, $quizType, $maxPlayers = 4, $questionsCount = 10)
    {
        $roomCode = $this->generateRoomCode();

        $roomId = $this->insert([
            'room_code' => $roomCode,
            'host_user_id' => $hostUserId,
            'quiz_type' => $quizType,
            'max_players' => $maxPlayers,
            'questions_count' => $questionsCount,
            'status' => 'waiting'
        ]);

        if ($roomId) {
            // Add host as participant
            $this->addParticipant($roomId, $hostUserId);
            return $this->find($roomId);
        }

        return false;
    }

    /**
     * Add participant to room
     */
    public function addParticipant($roomId, $userId)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('multiplayer_participants');

        $room = $this->find($roomId);

        // Check if room is full
        $currentCount = $builder->where('room_id', $roomId)->countAllResults();

        if ($currentCount >= $room['max_players']) {
            return false;
        }

        return $builder->insert([
            'room_id' => $roomId,
            'user_id' => $userId,
            'joined_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Get room participants
     */
    public function getParticipants($roomId)
    {
        $db = \Config\Database::connect();

        return $db->table('multiplayer_participants')
                  ->select('multiplayer_participants.*, users.username, users.avatar')
                  ->join('users', 'users.id = multiplayer_participants.user_id')
                  ->where('room_id', $roomId)
                  ->get()
                  ->getResultArray();
    }

    /**
     * Start game
     */
    public function startGame($roomId)
    {
        $room = $this->find($roomId);

        if ($room['status'] !== 'waiting') {
            return false;
        }

        // Generate questions
        $questions = $this->generateQuestions($room['quiz_type'], $room['questions_count']);

        return $this->update($roomId, [
            'status' => 'playing',
            'questions_data' => json_encode($questions),
            'started_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Generate questions for multiplayer
     */
    private function generateQuestions($quizType, $count)
    {
        $quizController = new \App\Controllers\QuizController();

        // Use reflection to call private methods
        $reflection = new \ReflectionClass($quizController);

        switch ($quizType) {
            case 'tebak_lanjutan':
                $method = $reflection->getMethod('generateTebakLanjutanQuestions');
                $method->setAccessible(true);
                return $method->invoke($quizController, $count);

            case 'tebak_surah':
                $method = $reflection->getMethod('generateTebakSurahQuestions');
                $method->setAccessible(true);
                return $method->invoke($quizController, $count);

            case 'terjemahan':
                $method = $reflection->getMethod('generateTerjemahanQuestions');
                $method->setAccessible(true);
                return $method->invoke($quizController, $count);

            default:
                // Mixed questions
                $questions = [];
                $perType = ceil($count / 3);

                $method1 = $reflection->getMethod('generateTebakLanjutanQuestions');
                $method1->setAccessible(true);
                $questions = array_merge($questions, $method1->invoke($quizController, $perType));

                $method2 = $reflection->getMethod('generateTebakSurahQuestions');
                $method2->setAccessible(true);
                $questions = array_merge($questions, $method2->invoke($quizController, $perType));

                $method3 = $reflection->getMethod('generateTerjemahanQuestions');
                $method3->setAccessible(true);
                $questions = array_merge($questions, $method3->invoke($quizController, $perType));

                shuffle($questions);
                return array_slice($questions, 0, $count);
        }
    }

    /**
     * Submit player answer
     */
    public function submitAnswer($roomId, $userId, $answers)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('multiplayer_participants');

        $room = $this->find($roomId);
        $questions = json_decode($room['questions_data'], true);

        // Calculate score
        $correctCount = 0;
        foreach ($answers as $index => $answer) {
            if (isset($questions[$index]) && $answer == $questions[$index]['correct_answer']) {
                $correctCount++;
            }
        }

        $score = round(($correctCount / count($questions)) * 100);

        return $builder->where('room_id', $roomId)
                      ->where('user_id', $userId)
                      ->update([
                          'answers_data' => json_encode($answers),
                          'correct_answers' => $correctCount,
                          'score' => $score
                      ]);
    }

    /**
     * Get room by code
     */
    public function getRoomByCode($code)
    {
        return $this->where('room_code', $code)->first();
    }

    /**
     * Get room with participants
     */
    public function getRoomWithParticipants($roomId)
    {
        $room = $this->find($roomId);

        if ($room) {
            $room['participants'] = $this->getParticipants($roomId);
            if ($room['questions_data']) {
                $room['questions_data'] = json_decode($room['questions_data'], true);
            }
        }

        return $room;
    }

    /**
     * Finish game
     */
    public function finishGame($roomId)
    {
        return $this->update($roomId, [
            'status' => 'finished',
            'finished_at' => date('Y-m-d H:i:s')
        ]);
    }
}
