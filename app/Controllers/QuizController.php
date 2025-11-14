<?php

namespace App\Controllers;

use App\Models\AyahModel;
use App\Models\SurahModel;
use App\Models\QuizResultModel;
use App\Models\DailyQuizModel;

class QuizController extends BaseController
{
    protected $ayahModel;
    protected $surahModel;
    protected $quizResultModel;
    protected $dailyQuizModel;

    public function __construct()
    {
        $this->ayahModel = new AyahModel();
        $this->surahModel = new SurahModel();
        $this->quizResultModel = new QuizResultModel();
        $this->dailyQuizModel = new DailyQuizModel();
    }

    /**
     * Quiz main page
     */
    public function index()
    {
        $data = [
            'title' => 'Quiz Al-Qur\'an',
            'quiz_types' => [
                [
                    'name' => 'Tebak Lanjutan Ayat',
                    'description' => 'Tebak kelanjutan dari ayat yang ditampilkan',
                    'icon' => '📖',
                    'url' => '/quiz/tebak-lanjutan'
                ],
                [
                    'name' => 'Tebak Nama Surah',
                    'description' => 'Tebak nama surah dari ayat yang ditampilkan',
                    'icon' => '🕌',
                    'url' => '/quiz/tebak-surah'
                ],
                [
                    'name' => 'Quiz Terjemahan',
                    'description' => 'Cocokkan ayat dengan terjemahannya',
                    'icon' => '🌙',
                    'url' => '/quiz/terjemahan'
                ],
                [
                    'name' => 'Quiz Harian',
                    'description' => 'Quiz harian dengan soal bervariasi',
                    'icon' => '⭐',
                    'url' => '/quiz/daily'
                ]
            ]
        ];

        return view('quiz/index', $data);
    }

    /**
     * Tebak Lanjutan Ayat Quiz
     */
    public function tebakLanjutan()
    {
        $questions = $this->generateTebakLanjutanQuestions(10);

        $data = [
            'title' => 'Quiz: Tebak Lanjutan Ayat',
            'quiz_type' => 'tebak_lanjutan',
            'questions' => $questions,
            'total_questions' => count($questions)
        ];

        // Store in session
        session()->set('current_quiz', [
            'type' => 'tebak_lanjutan',
            'questions' => $questions,
            'start_time' => time()
        ]);

        return view('quiz/quiz_page', $data);
    }

    /**
     * Tebak Surah Quiz
     */
    public function tebakSurah()
    {
        $questions = $this->generateTebakSurahQuestions(10);

        $data = [
            'title' => 'Quiz: Tebak Nama Surah',
            'quiz_type' => 'tebak_surah',
            'questions' => $questions,
            'total_questions' => count($questions)
        ];

        session()->set('current_quiz', [
            'type' => 'tebak_surah',
            'questions' => $questions,
            'start_time' => time()
        ]);

        return view('quiz/quiz_page', $data);
    }

    /**
     * Terjemahan Quiz
     */
    public function terjemahan()
    {
        $questions = $this->generateTerjemahanQuestions(10);

        $data = [
            'title' => 'Quiz: Terjemahan Ayat',
            'quiz_type' => 'terjemahan',
            'questions' => $questions,
            'total_questions' => count($questions)
        ];

        session()->set('current_quiz', [
            'type' => 'terjemahan',
            'questions' => $questions,
            'start_time' => time()
        ]);

        return view('quiz/quiz_page', $data);
    }

    /**
     * Daily Quiz
     */
    public function dailyQuiz()
    {
        // Get or create today's quiz
        $todayQuiz = $this->dailyQuizModel->getTodayQuiz();

        if (!$todayQuiz) {
            $this->dailyQuizModel->createDailyQuiz();
            $todayQuiz = $this->dailyQuizModel->getTodayQuiz();
        }

        $data = [
            'title' => 'Quiz Harian Al-Qur\'an',
            'quiz_type' => 'daily',
            'questions' => $todayQuiz['questions_data'],
            'total_questions' => $todayQuiz['total_questions'],
            'theme' => $todayQuiz['theme'],
            'quiz_date' => $todayQuiz['quiz_date']
        ];

        session()->set('current_quiz', [
            'type' => 'daily',
            'questions' => $todayQuiz['questions_data'],
            'start_time' => time(),
            'quiz_date' => $todayQuiz['quiz_date']
        ]);

        return view('quiz/quiz_page', $data);
    }

    /**
     * Submit Quiz
     */
    public function submitQuiz()
    {
        $currentQuiz = session()->get('current_quiz');

        if (!$currentQuiz) {
            return redirect()->to('/quiz')->with('error', 'Quiz tidak ditemukan');
        }

        $answers = $this->request->getPost('answers');
        $questions = $currentQuiz['questions'];
        $startTime = $currentQuiz['start_time'];

        // Calculate score
        $correctCount = 0;
        $results = [];

        foreach ($questions as $index => $question) {
            $userAnswer = isset($answers[$index]) ? (int)$answers[$index] : null;
            $correctAnswer = (int)$question['correct_answer'];
            $isCorrect = ($userAnswer === $correctAnswer);

            if ($isCorrect) {
                $correctCount++;
            }

            $results[] = [
                'question' => $question,
                'user_answer' => $userAnswer,
                'correct_answer' => $correctAnswer,
                'is_correct' => $isCorrect
            ];
        }

        $totalQuestions = count($questions);
        $score = round(($correctCount / $totalQuestions) * 100);
        $timeTaken = time() - $startTime;

        // Save result
        $resultData = [
            'user_id' => null, // TODO: Add user authentication
            'quiz_type' => $currentQuiz['type'],
            'total_questions' => $totalQuestions,
            'correct_answers' => $correctCount,
            'score' => $score,
            'time_taken' => $timeTaken,
            'quiz_date' => $currentQuiz['quiz_date'] ?? date('Y-m-d'),
            'answers_data' => $results
        ];

        $resultId = $this->quizResultModel->saveResult($resultData);

        // Clear session
        session()->remove('current_quiz');

        // Redirect to result page
        return redirect()->to('/quiz/result/' . $resultId);
    }

    /**
     * Show Quiz Result
     */
    public function result($resultId)
    {
        $result = $this->quizResultModel->find($resultId);

        if (!$result) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'Hasil quiz tidak ditemukan'
            );
        }

        // Decode answers data
        if (isset($result['answers_data']) && is_string($result['answers_data'])) {
            $result['answers_data'] = json_decode($result['answers_data'], true);
        }

        $data = [
            'title' => 'Hasil Quiz',
            'result' => $result,
            'percentage' => round(($result['correct_answers'] / $result['total_questions']) * 100)
        ];

        return view('quiz/result', $data);
    }

    /**
     * Leaderboard
     */
    public function leaderboard($type = null)
    {
        $validTypes = ['tebak_lanjutan', 'tebak_surah', 'terjemahan', 'daily'];

        if ($type && !in_array($type, $validTypes)) {
            $type = null;
        }

        $leaderboard = $this->quizResultModel->getLeaderboard($type, 20);

        $data = [
            'title' => 'Leaderboard Quiz Al-Qur\'an',
            'leaderboard' => $leaderboard,
            'current_type' => $type,
            'types' => [
                null => 'Semua Quiz',
                'tebak_lanjutan' => 'Tebak Lanjutan',
                'tebak_surah' => 'Tebak Surah',
                'terjemahan' => 'Terjemahan',
                'daily' => 'Quiz Harian'
            ]
        ];

        return view('quiz/leaderboard', $data);
    }

    // ========== Helper Methods ==========

    /**
     * Generate Tebak Lanjutan questions
     */
    private function generateTebakLanjutanQuestions($count = 10)
    {
        $questions = [];

        for ($i = 0; $i < $count; $i++) {
            $verse = $this->ayahModel->getRandomVerse();
            $nextVerse = $this->ayahModel->getVerse($verse['surah_id'], $verse['number_in_surah'] + 1);

            if (!$nextVerse) {
                $i--;
                continue;
            }

            $wrongOptions = $this->ayahModel->getRandomVerses(3);
            $options = [$nextVerse];

            foreach ($wrongOptions as $option) {
                if ($option['id'] != $nextVerse['id']) {
                    $options[] = $option;
                }
            }

            shuffle($options);

            $questions[] = [
                'type' => 'tebak_lanjutan',
                'question' => 'Apa lanjutan dari ayat berikut?',
                'verse_text' => $verse['text_arabic'],
                'verse_translation' => $verse['translation_id'],
                'options' => array_map(function($opt) {
                    return [
                        'id' => $opt['id'],
                        'text' => $opt['text_arabic'],
                        'translation' => $opt['translation_id']
                    ];
                }, $options),
                'correct_answer' => $nextVerse['id']
            ];
        }

        return $questions;
    }

    /**
     * Generate Tebak Surah questions
     */
    private function generateTebakSurahQuestions($count = 10)
    {
        $questions = [];

        for ($i = 0; $i < $count; $i++) {
            $verse = $this->ayahModel->getRandomVerse();
            $correctSurah = $this->surahModel->find($verse['surah_id']);

            $allSurahs = $this->surahModel->getAllSurah();
            $wrongSurahs = array_filter($allSurahs, function($s) use ($correctSurah) {
                return $s['id'] != $correctSurah['id'];
            });

            $wrongSurahs = array_values($wrongSurahs);
            shuffle($wrongSurahs);
            $wrongSurahs = array_slice($wrongSurahs, 0, 3);

            $options = array_merge([$correctSurah], $wrongSurahs);
            shuffle($options);

            $questions[] = [
                'type' => 'tebak_surah',
                'question' => 'Ayat berikut berasal dari surah?',
                'verse_text' => $verse['text_arabic'],
                'verse_translation' => $verse['translation_id'],
                'options' => array_map(function($opt) {
                    return [
                        'id' => $opt['id'],
                        'name' => $opt['name_latin'],
                        'arabic' => $opt['name_arabic']
                    ];
                }, $options),
                'correct_answer' => $correctSurah['id']
            ];
        }

        return $questions;
    }

    /**
     * Generate Terjemahan questions
     */
    private function generateTerjemahanQuestions($count = 10)
    {
        $questions = [];

        for ($i = 0; $i < $count; $i++) {
            $verse = $this->ayahModel->getRandomVerse();
            $wrongVerses = $this->ayahModel->getRandomVerses(3);

            $options = [$verse];
            foreach ($wrongVerses as $option) {
                if ($option['id'] != $verse['id']) {
                    $options[] = $option;
                }
            }

            shuffle($options);

            $questions[] = [
                'type' => 'terjemahan',
                'question' => 'Apa terjemahan dari ayat berikut?',
                'verse_text' => $verse['text_arabic'],
                'options' => array_map(function($opt) {
                    return [
                        'id' => $opt['id'],
                        'translation' => $opt['translation_id']
                    ];
                }, $options),
                'correct_answer' => $verse['id']
            ];
        }

        return $questions;
    }
}
