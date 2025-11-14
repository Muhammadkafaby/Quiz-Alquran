<?php

namespace App\Controllers;

use App\Models\MultiplayerModel;

class MultiplayerController extends BaseController
{
    protected $multiplayerModel;

    public function __construct()
    {
        $this->multiplayerModel = new MultiplayerModel();
    }

    /**
     * Multiplayer lobby
     */
    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $data = [
            'title' => 'Multiplayer Quiz'
        ];

        return view('multiplayer/lobby', $data);
    }

    /**
     * Create room
     */
    public function createRoom()
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu'
            ]);
        }

        $userId = session()->get('user_id');
        $quizType = $this->request->getPost('quiz_type');
        $maxPlayers = $this->request->getPost('max_players') ?: 4;
        $questionsCount = $this->request->getPost('questions_count') ?: 10;

        $room = $this->multiplayerModel->createRoom($userId, $quizType, $maxPlayers, $questionsCount);

        if ($room) {
            return $this->response->setJSON([
                'success' => true,
                'room' => $room
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Gagal membuat room'
        ]);
    }

    /**
     * Join room
     */
    public function joinRoom()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $roomCode = $this->request->getPost('room_code') ?: $this->request->getGet('code');
        $room = $this->multiplayerModel->getRoomByCode($roomCode);

        if (!$room) {
            return redirect()->to('/multiplayer')->with('error', 'Room tidak ditemukan');
        }

        if ($room['status'] !== 'waiting') {
            return redirect()->to('/multiplayer')->with('error', 'Room sudah dimulai atau selesai');
        }

        $userId = session()->get('user_id');
        $result = $this->multiplayerModel->addParticipant($room['id'], $userId);

        if ($result) {
            return redirect()->to('/multiplayer/room/' . $room['room_code']);
        }

        return redirect()->to('/multiplayer')->with('error', 'Room sudah penuh');
    }

    /**
     * Room page
     */
    public function room($roomCode)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $room = $this->multiplayerModel->getRoomByCode($roomCode);

        if (!$room) {
            return redirect()->to('/multiplayer')->with('error', 'Room tidak ditemukan');
        }

        $roomWithParticipants = $this->multiplayerModel->getRoomWithParticipants($room['id']);

        $data = [
            'title' => 'Room ' . $roomCode,
            'room' => $roomWithParticipants
        ];

        return view('multiplayer/room', $data);
    }

    /**
     * Start game (AJAX)
     */
    public function startGame()
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized'
            ]);
        }

        $roomId = $this->request->getPost('room_id');
        $userId = session()->get('user_id');

        $room = $this->multiplayerModel->find($roomId);

        if (!$room || $room['host_user_id'] != $userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Hanya host yang bisa memulai game'
            ]);
        }

        $result = $this->multiplayerModel->startGame($roomId);

        return $this->response->setJSON([
            'success' => $result,
            'message' => $result ? 'Game dimulai!' : 'Gagal memulai game'
        ]);
    }

    /**
     * Submit answers (AJAX)
     */
    public function submitAnswers()
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized'
            ]);
        }

        $roomId = $this->request->getPost('room_id');
        $userId = session()->get('user_id');
        $answers = $this->request->getPost('answers');

        $result = $this->multiplayerModel->submitAnswer($roomId, $userId, $answers);

        return $this->response->setJSON([
            'success' => $result
        ]);
    }

    /**
     * Get room status (AJAX for polling)
     */
    public function getRoomStatus($roomId)
    {
        $room = $this->multiplayerModel->getRoomWithParticipants($roomId);

        return $this->response->setJSON([
            'success' => true,
            'room' => $room
        ]);
    }

    /**
     * Leave room
     */
    public function leaveRoom($roomId)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $userId = session()->get('user_id');
        $db = \Config\Database::connect();

        $db->table('multiplayer_participants')
           ->where('room_id', $roomId)
           ->where('user_id', $userId)
           ->delete();

        return redirect()->to('/multiplayer')->with('success', 'Anda telah keluar dari room');
    }
}
