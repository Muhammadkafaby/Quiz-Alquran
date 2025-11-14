<?php

namespace App\Models;

use CodeIgniter\Model;

class SurahModel extends Model
{
    protected $table = 'surah';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'number',
        'name_arabic',
        'name_latin',
        'name_translation',
        'number_of_verses',
        'revelation',
        'tafsir'
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
     * Get all surahs ordered by number
     */
    public function getAllSurah()
    {
        return $this->orderBy('number', 'ASC')->findAll();
    }

    /**
     * Get surah by number
     */
    public function getSurahByNumber($number)
    {
        return $this->where('number', $number)->first();
    }

    /**
     * Get surah with verse count
     */
    public function getSurahWithVerses($surahNumber)
    {
        $surah = $this->getSurahByNumber($surahNumber);

        if ($surah) {
            $ayahModel = new AyahModel();
            $surah['verses'] = $ayahModel->where('surah_id', $surah['id'])->findAll();
        }

        return $surah;
    }

    /**
     * Search surah by name
     */
    public function searchSurah($keyword)
    {
        return $this->like('name_latin', $keyword)
                    ->orLike('name_translation', $keyword)
                    ->findAll();
    }

    /**
     * Get random surah
     */
    public function getRandomSurah()
    {
        return $this->orderBy('RAND()', '', false)->first();
    }
}
