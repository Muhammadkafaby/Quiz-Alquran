<?php

namespace App\Models;

use CodeIgniter\Model;

class DailyQuizModel extends Model
{
    protected $table = 'daily_quiz';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'quiz_date',
        'questions_data',
        'total_questions',
        'theme'
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
     * Get today's quiz
     */
    public function getTodayQuiz()
    {
        $today = date('Y-m-d');
        $quiz = $this->where('quiz_date', $today)->first();

        // Decode JSON data
        if ($quiz && isset($quiz['questions_data'])) {
            $quiz['questions_data'] = json_decode($quiz['questions_data'], true);
        }

        return $quiz;
    }

    /**
     * Get quiz by date
     */
    public function getQuizByDate($date)
    {
        $quiz = $this->where('quiz_date', $date)->first();

        if ($quiz && isset($quiz['questions_data'])) {
            $quiz['questions_data'] = json_decode($quiz['questions_data'], true);
        }

        return $quiz;
    }

    /**
     * Create daily quiz
     */
    public function createDailyQuiz($date = null)
    {
        if (!$date) {
            $date = date('Y-m-d');
        }

        // Check if already exists
        if ($this->getQuizByDate($date)) {
            return false;
        }

        // Generate questions
        $questions = $this->generateQuestions(10);

        $data = [
            'quiz_date' => $date,
            'questions_data' => json_encode($questions),
            'total_questions' => count($questions),
            'theme' => $this->generateTheme()
        ];

        return $this->insert($data);
    }

    /**
     * Generate quiz questions
     */
    private function generateQuestions($count = 10)
    {
        $ayahModel = new AyahModel();
        $surahModel = new SurahModel();
        $questions = [];

        // Mix of different question types
        $types = ['tebak_lanjutan', 'tebak_surah', 'terjemahan'];

        for ($i = 0; $i < $count; $i++) {
            $type = $types[array_rand($types)];

            switch ($type) {
                case 'tebak_lanjutan':
                    $questions[] = $this->generateTebakLanjutanQuestion($ayahModel);
                    break;
                case 'tebak_surah':
                    $questions[] = $this->generateTebakSurahQuestion($ayahModel, $surahModel);
                    break;
                case 'terjemahan':
                    $questions[] = $this->generateTerjemahanQuestion($ayahModel);
                    break;
            }
        }

        return $questions;
    }

    /**
     * Generate "Tebak Lanjutan Ayat" question
     */
    private function generateTebakLanjutanQuestion($ayahModel)
    {
        $verse = $ayahModel->getRandomVerse();

        // Get next verse
        $nextVerse = $ayahModel->getVerse($verse['surah_id'], $verse['number_in_surah'] + 1);

        if (!$nextVerse) {
            // If no next verse, get a random one
            return $this->generateTebakLanjutanQuestion($ayahModel);
        }

        // Generate wrong options
        $wrongOptions = $ayahModel->getRandomVerses(3);
        $options = [$nextVerse];

        foreach ($wrongOptions as $option) {
            if ($option['id'] != $nextVerse['id']) {
                $options[] = $option;
            }
        }

        shuffle($options);

        return [
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

    /**
     * Generate "Tebak Surah" question
     */
    private function generateTebakSurahQuestion($ayahModel, $surahModel)
    {
        $verse = $ayahModel->getRandomVerse();
        $correctSurah = $surahModel->find($verse['surah_id']);

        // Get random surahs for options
        $allSurahs = $surahModel->getAllSurah();
        $wrongSurahs = array_filter($allSurahs, function($s) use ($correctSurah) {
            return $s['id'] != $correctSurah['id'];
        });

        $wrongSurahs = array_values($wrongSurahs);
        shuffle($wrongSurahs);
        $wrongSurahs = array_slice($wrongSurahs, 0, 3);

        $options = array_merge([$correctSurah], $wrongSurahs);
        shuffle($options);

        return [
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

    /**
     * Generate "Terjemahan" question
     */
    private function generateTerjemahanQuestion($ayahModel)
    {
        $verse = $ayahModel->getRandomVerse();

        // Get random verses for wrong options
        $wrongVerses = $ayahModel->getRandomVerses(3);
        $options = [$verse];

        foreach ($wrongVerses as $option) {
            if ($option['id'] != $verse['id']) {
                $options[] = $option;
            }
        }

        shuffle($options);

        return [
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

    /**
     * Generate quiz theme
     */
    private function generateTheme()
    {
        $themes = [
            'Quiz Harian Al-Qur\'an',
            'Mengenal Ayat-Ayat Suci',
            'Pahala Menghapal Al-Qur\'an',
            'Tadabbur Al-Qur\'an',
            'Renungan Ayat Pilihan'
        ];

        return $themes[array_rand($themes)];
    }
}
