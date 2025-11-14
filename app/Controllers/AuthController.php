<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        helper(['form']);
    }

    /**
     * Login page
     */
    public function login()
    {
        // Redirect if already logged in
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/');
        }

        $data = [
            'title' => 'Login - Quiz Al-Qur\'an',
            'validation' => \Config\Services::validation()
        ];

        return view('auth/login', $data);
    }

    /**
     * Process login
     */
    public function attemptLogin()
    {
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required|min_length[6]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $remember = $this->request->getPost('remember');

        $user = $this->userModel->where('email', $email)->first();

        if (!$user) {
            return redirect()->back()->withInput()->with('error', 'Email tidak terdaftar');
        }

        if (!password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Password salah');
        }

        // Update last active
        $this->userModel->update($user['id'], [
            'last_active' => date('Y-m-d H:i:s')
        ]);

        // Set session
        $sessionData = [
            'user_id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'full_name' => $user['full_name'],
            'avatar' => $user['avatar'],
            'isLoggedIn' => true
        ];

        session()->set($sessionData);

        // Set cookie if remember me
        if ($remember) {
            $this->response->setCookie('remember_token', base64_encode($user['id'] . ':' . $user['email']), 30 * 24 * 60 * 60);
        }

        return redirect()->to('/')->with('success', 'Selamat datang, ' . $user['username'] . '!');
    }

    /**
     * Register page
     */
    public function register()
    {
        // Redirect if already logged in
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/');
        }

        $data = [
            'title' => 'Register - Quiz Al-Qur\'an',
            'validation' => \Config\Services::validation()
        ];

        return view('auth/register', $data);
    }

    /**
     * Process registration
     */
    public function attemptRegister()
    {
        $rules = [
            'username' => 'required|min_length[3]|max_length[100]|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'full_name' => 'required|min_length[3]',
            'password' => 'required|min_length[6]',
            'password_confirm' => 'required|matches[password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'full_name' => $this->request->getPost('full_name'),
            'password' => $this->request->getPost('password'),
        ];

        $userId = $this->userModel->insert($data);

        if ($userId) {
            // Auto login after register
            $user = $this->userModel->find($userId);

            $sessionData = [
                'user_id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'full_name' => $user['full_name'],
                'avatar' => $user['avatar'],
                'isLoggedIn' => true
            ];

            session()->set($sessionData);

            return redirect()->to('/')->with('success', 'Registrasi berhasil! Selamat datang di Quiz Al-Qur\'an');
        }

        return redirect()->back()->with('error', 'Registrasi gagal, silakan coba lagi');
    }

    /**
     * Logout
     */
    public function logout()
    {
        session()->destroy();
        $this->response->deleteCookie('remember_token');

        return redirect()->to('/')->with('success', 'Anda telah logout');
    }

    /**
     * User profile
     */
    public function profile()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        // Get user statistics
        $quizResultModel = new \App\Models\QuizResultModel();
        $stats = $quizResultModel->getUserStats($userId);

        // Get bookmarks
        $bookmarkModel = new \App\Models\BookmarkModel();
        $bookmarks = $bookmarkModel->getUserBookmarks($userId, 5);

        // Get achievements
        $achievementModel = new \App\Models\AchievementModel();
        $achievements = $achievementModel->getUserAchievements($userId);

        $data = [
            'title' => 'Profil Saya',
            'user' => $user,
            'stats' => $stats,
            'bookmarks' => $bookmarks,
            'achievements' => $achievements
        ];

        return view('auth/profile', $data);
    }

    /**
     * Update profile
     */
    public function updateProfile()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $userId = session()->get('user_id');

        $rules = [
            'full_name' => 'required|min_length[3]',
            'username' => 'required|min_length[3]|is_unique[users.username,id,' . $userId . ']',
        ];

        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[6]';
            $rules['password_confirm'] = 'matches[password]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'full_name' => $this->request->getPost('full_name'),
            'theme_preference' => $this->request->getPost('theme_preference'),
            'daily_quiz_reminder' => $this->request->getPost('daily_quiz_reminder') ? 1 : 0,
            'audio_recitation_enabled' => $this->request->getPost('audio_recitation_enabled') ? 1 : 0,
            'reciter_preference' => $this->request->getPost('reciter_preference'),
        ];

        if ($this->request->getPost('password')) {
            $data['password'] = $this->request->getPost('password');
        }

        // Handle avatar upload
        $avatar = $this->request->getFile('avatar');
        if ($avatar && $avatar->isValid() && !$avatar->hasMoved()) {
            $newName = $avatar->getRandomName();
            $avatar->move(WRITEPATH . 'uploads/avatars', $newName);
            $data['avatar'] = $newName;
        }

        if ($this->userModel->update($userId, $data)) {
            // Update session
            session()->set([
                'username' => $data['username'],
                'full_name' => $data['full_name'],
                'avatar' => $data['avatar'] ?? session()->get('avatar')
            ]);

            return redirect()->to('/auth/profile')->with('success', 'Profil berhasil diupdate');
        }

        return redirect()->back()->with('error', 'Gagal update profil');
    }
}
