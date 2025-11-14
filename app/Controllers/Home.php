<?php

namespace App\Controllers;

use App\Models\SurahModel;
use App\Models\QuizResultModel;

class Home extends BaseController
{
    public function index()
    {
        $surahModel = new SurahModel();
        $quizResultModel = new QuizResultModel();

        $data = [
            'title' => 'Beranda - Quiz Al-Qur\'an',
            'total_surah' => $surahModel->countAll(),
            'featured_surahs' => $surahModel->orderBy('RAND()')->limit(3)->findAll(),
            'recent_results' => $quizResultModel->orderBy('created_at', 'DESC')->limit(5)->findAll(),
            'top_scorers' => $quizResultModel->getLeaderboard(null, 5)
        ];

        return view('home/index', $data);
    }

    public function about()
    {
        $data = [
            'title' => 'Tentang - Quiz Al-Qur\'an'
        ];

        return view('home/about', $data);
    }
}
