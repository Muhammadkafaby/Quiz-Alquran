<?php

namespace App\Models;

use CodeIgniter\Model;

class AyahModel extends Model
{
    protected $table = 'ayah';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'surah_id',
        'number_in_surah',
        'number_in_quran',
        'text_arabic',
        'text_latin',
        'translation_id',
        'tafsir',
        'juz',
        'page'
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
     * Get verses by surah ID
     */
    public function getVersesBySurah($surahId)
    {
        return $this->where('surah_id', $surahId)
                    ->orderBy('number_in_surah', 'ASC')
                    ->findAll();
    }

    /**
     * Get specific verse
     */
    public function getVerse($surahId, $verseNumber)
    {
        return $this->where('surah_id', $surahId)
                    ->where('number_in_surah', $verseNumber)
                    ->first();
    }

    /**
     * Get random verse
     */
    public function getRandomVerse()
    {
        return $this->orderBy('RAND()', '', false)->first();
    }

    /**
     * Get random verses (multiple)
     */
    public function getRandomVerses($limit = 5)
    {
        return $this->orderBy('RAND()', '', false)
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Get verse with surah info
     */
    public function getVerseWithSurah($surahId, $verseNumber)
    {
        return $this->select('ayah.*, surah.name_arabic, surah.name_latin, surah.number as surah_number')
                    ->join('surah', 'surah.id = ayah.surah_id')
                    ->where('ayah.surah_id', $surahId)
                    ->where('ayah.number_in_surah', $verseNumber)
                    ->first();
    }

    /**
     * Search verses by translation
     */
    public function searchByTranslation($keyword)
    {
        return $this->select('ayah.*, surah.name_latin, surah.number as surah_number')
                    ->join('surah', 'surah.id = ayah.surah_id')
                    ->like('translation_id', $keyword)
                    ->findAll();
    }

    /**
     * Get verses by juz
     */
    public function getVersesByJuz($juzNumber)
    {
        return $this->where('juz', $juzNumber)
                    ->orderBy('number_in_quran', 'ASC')
                    ->findAll();
    }

    /**
     * Get total verses count
     */
    public function getTotalVerses()
    {
        return $this->countAll();
    }
}
