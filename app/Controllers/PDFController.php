<?php

namespace App\Controllers;

use App\Models\QuizResultModel;

class PDFController extends BaseController
{
    /**
     * Export quiz result to PDF
     */
    public function exportResult($resultId)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $quizResultModel = new QuizResultModel();
        $result = $quizResultModel->find($resultId);

        if (!$result) {
            return redirect()->back()->with('error', 'Hasil quiz tidak ditemukan');
        }

        // Decode answers data
        if (isset($result['answers_data']) && is_string($result['answers_data'])) {
            $result['answers_data'] = json_decode($result['answers_data'], true);
        }

        $percentage = round(($result['correct_answers'] / $result['total_questions']) * 100);

        // Generate HTML
        $html = $this->generatePDFHTML($result, $percentage);

        // Use DomPDF or similar library
        // For simplicity, we'll create a print-friendly HTML page
        $data = [
            'title' => 'Hasil Quiz - ' . ucwords(str_replace('_', ' ', $result['quiz_type'])),
            'result' => $result,
            'percentage' => $percentage
        ];

        return view('pdf/quiz_result', $data);
    }

    /**
     * Generate PDF HTML
     */
    private function generatePDFHTML($result, $percentage)
    {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <title>Hasil Quiz Al-Qur\'an</title>
            <style>
                body { font-family: Arial, sans-serif; padding: 20px; }
                .header { text-align: center; margin-bottom: 30px; }
                .stats { display: flex; justify-content: space-around; margin: 20px 0; }
                .stat-box { text-align: center; padding: 15px; border: 1px solid #ddd; }
                .answers { margin-top: 30px; }
                .answer { margin-bottom: 20px; padding: 15px; border-left: 4px solid #ddd; }
                .correct { border-left-color: #27ae60; }
                .incorrect { border-left-color: #e74c3c; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>🕌 Quiz Al-Qur\'an</h1>
                <h2>Hasil Quiz</h2>
            </div>

            <div class="stats">
                <div class="stat-box">
                    <h3>' . $result['score'] . '</h3>
                    <p>Skor</p>
                </div>
                <div class="stat-box">
                    <h3>' . $result['correct_answers'] . '/' . $result['total_questions'] . '</h3>
                    <p>Benar</p>
                </div>
                <div class="stat-box">
                    <h3>' . $percentage . '%</h3>
                    <p>Akurasi</p>
                </div>
            </div>

            <div class="answers">
                <h3>Detail Jawaban</h3>';

        if (!empty($result['answers_data'])) {
            foreach ($result['answers_data'] as $index => $answer) {
                $class = $answer['is_correct'] ? 'correct' : 'incorrect';
                $status = $answer['is_correct'] ? '✓ Benar' : '✗ Salah';

                $html .= '
                <div class="answer ' . $class . '">
                    <p><strong>Soal ' . ($index + 1) . ':</strong> ' . $answer['question']['question'] . '</p>
                    <p><strong>Status:</strong> ' . $status . '</p>
                </div>';
            }
        }

        $html .= '
            </div>

            <div style="margin-top: 40px; text-align: center; color: #888;">
                <p>Quiz Al-Qur\'an - ' . date('d/m/Y H:i') . '</p>
            </div>
        </body>
        </html>';

        return $html;
    }

    /**
     * Export statistics
     */
    public function exportStatistics()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $userId = session()->get('user_id');
        $quizResultModel = new QuizResultModel();
        $stats = $quizResultModel->getUserStats($userId);

        $data = [
            'title' => 'Statistik Quiz',
            'stats' => $stats
        ];

        return view('pdf/statistics', $data);
    }
}
