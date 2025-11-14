<?php

namespace App\Controllers;

use App\Models\SurahModel;
use App\Models\AyahModel;

class QuranController extends BaseController
{
    protected $surahModel;
    protected $ayahModel;

    public function __construct()
    {
        $this->surahModel = new SurahModel();
        $this->ayahModel = new AyahModel();
    }

    /**
     * Quran main page
     */
    public function index()
    {
        $data = [
            'title' => 'Al-Qur\'an Digital',
            'surahs' => $this->surahModel->getAllSurah()
        ];

        return view('quran/index', $data);
    }

    /**
     * List all surahs
     */
    public function surahList()
    {
        $search = $this->request->getGet('search');

        if ($search) {
            $surahs = $this->surahModel->searchSurah($search);
        } else {
            $surahs = $this->surahModel->getAllSurah();
        }

        $data = [
            'title' => 'Daftar Surah - Al-Qur\'an',
            'surahs' => $surahs,
            'search' => $search
        ];

        return view('quran/surah_list', $data);
    }

    /**
     * Surah detail with all verses
     */
    public function surahDetail($surahNumber)
    {
        $surah = $this->surahModel->getSurahByNumber($surahNumber);

        if (!$surah) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'Surah tidak ditemukan'
            );
        }

        $verses = $this->ayahModel->getVersesBySurah($surah['id']);

        $data = [
            'title' => $surah['name_latin'] . ' - Al-Qur\'an',
            'surah' => $surah,
            'verses' => $verses,
            'total_verses' => count($verses)
        ];

        return view('quran/surah_detail', $data);
    }

    /**
     * Single ayah detail
     */
    public function ayahDetail($surahNumber, $verseNumber)
    {
        $surah = $this->surahModel->getSurahByNumber($surahNumber);

        if (!$surah) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'Surah tidak ditemukan'
            );
        }

        $verse = $this->ayahModel->getVerse($surah['id'], $verseNumber);

        if (!$verse) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'Ayat tidak ditemukan'
            );
        }

        // Get previous and next verse for navigation
        $prevVerse = null;
        $nextVerse = null;

        if ($verseNumber > 1) {
            $prevVerse = $this->ayahModel->getVerse($surah['id'], $verseNumber - 1);
        }

        if ($verseNumber < $surah['number_of_verses']) {
            $nextVerse = $this->ayahModel->getVerse($surah['id'], $verseNumber + 1);
        }

        $data = [
            'title' => $surah['name_latin'] . ' Ayat ' . $verseNumber . ' - Al-Qur\'an',
            'surah' => $surah,
            'verse' => $verse,
            'prevVerse' => $prevVerse,
            'nextVerse' => $nextVerse
        ];

        return view('quran/ayah_detail', $data);
    }

    /**
     * Search verses
     */
    public function search()
    {
        $keyword = $this->request->getGet('q');

        if (!$keyword) {
            return redirect()->to('/quran');
        }

        $results = $this->ayahModel->searchByTranslation($keyword);

        $data = [
            'title' => 'Hasil Pencarian: ' . $keyword,
            'keyword' => $keyword,
            'results' => $results,
            'total' => count($results)
        ];

        return view('quran/search_results', $data);
    }
}
