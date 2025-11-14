<?php

namespace App\Controllers;

use App\Models\SurahModel;
use App\Models\AyahModel;

class AdminController extends BaseController
{
    /**
     * Import Quran data from API
     */
    public function importQuran()
    {
        $surahModel = new SurahModel();
        $ayahModel = new AyahModel();

        // Check if already imported
        if ($surahModel->countAll() > 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data Al-Qur\'an sudah ada. Hapus data terlebih dahulu jika ingin import ulang.'
            ]);
        }

        $apiUrl = getenv('quran.api.url') ?: 'https://api.quran.gading.dev';
        $imported = 0;
        $errors = [];

        try {
            // Get all surahs
            $response = file_get_contents($apiUrl . '/surah');
            $data = json_decode($response, true);

            if (!isset($data['data'])) {
                throw new \Exception('Invalid API response');
            }

            $surahs = $data['data'];

            foreach ($surahs as $surahData) {
                // Import surah
                $surahId = $surahModel->insert([
                    'number' => $surahData['number'],
                    'name_arabic' => $surahData['name']['short'] ?? $surahData['name']['transliteration']['id'],
                    'name_latin' => $surahData['name']['transliteration']['id'],
                    'name_translation' => $surahData['name']['translation']['id'],
                    'number_of_verses' => $surahData['numberOfVerses'],
                    'revelation' => $surahData['revelation']['id'] === 'makkah' ? 'Makkiyah' : 'Madaniyah',
                    'tafsir' => $surahData['tafsir']['id'] ?? null
                ]);

                // Get verses for this surah
                $verseResponse = file_get_contents($apiUrl . '/surah/' . $surahData['number']);
                $verseData = json_decode($verseResponse, true);

                if (isset($verseData['data']['verses'])) {
                    foreach ($verseData['data']['verses'] as $verse) {
                        $ayahModel->insert([
                            'surah_id' => $surahId,
                            'number_in_surah' => $verse['number']['inSurah'],
                            'number_in_quran' => $verse['number']['inQuran'],
                            'text_arabic' => $verse['text']['arab'],
                            'text_latin' => $verse['text']['transliteration']['en'] ?? null,
                            'translation_id' => $verse['translation']['id'],
                            'tafsir' => $verse['tafsir']['id']['short'] ?? null,
                            'juz' => $verse['meta']['juz'],
                            'page' => $verse['meta']['page'] ?? null
                        ]);
                    }
                }

                $imported++;

                // Prevent timeout - flush output
                if ($imported % 10 == 0) {
                    echo "Imported $imported surahs...\n";
                    flush();
                }
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => "Berhasil import $imported surah dari Al-Qur'an",
                'imported' => $imported
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'imported' => $imported
            ]);
        }
    }

    /**
     * Import status
     */
    public function importStatus()
    {
        $surahModel = new SurahModel();
        $ayahModel = new AyahModel();

        $data = [
            'title' => 'Status Import Data',
            'total_surah' => $surahModel->countAll(),
            'total_ayah' => $ayahModel->countAll(),
            'expected_surah' => 114,
            'expected_ayah' => 6236
        ];

        return view('admin/import_status', $data);
    }
}
