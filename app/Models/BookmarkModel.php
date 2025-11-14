<?php

namespace App\Models;

use CodeIgniter\Model;

class BookmarkModel extends Model
{
    protected $table = 'bookmarks';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'user_id',
        'ayah_id',
        'note'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get user bookmarks with ayah and surah info
     */
    public function getUserBookmarks($userId, $limit = null)
    {
        $builder = $this->select('bookmarks.*, ayah.text_arabic, ayah.translation_id, ayah.number_in_surah, surah.name_latin, surah.number as surah_number')
                        ->join('ayah', 'ayah.id = bookmarks.ayah_id')
                        ->join('surah', 'surah.id = ayah.surah_id')
                        ->where('bookmarks.user_id', $userId)
                        ->orderBy('bookmarks.created_at', 'DESC');

        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->findAll();
    }

    /**
     * Check if ayah is bookmarked
     */
    public function isBookmarked($userId, $ayahId)
    {
        return $this->where('user_id', $userId)
                    ->where('ayah_id', $ayahId)
                    ->first() !== null;
    }

    /**
     * Toggle bookmark
     */
    public function toggleBookmark($userId, $ayahId, $note = null)
    {
        $existing = $this->where('user_id', $userId)
                         ->where('ayah_id', $ayahId)
                         ->first();

        if ($existing) {
            // Remove bookmark
            return $this->delete($existing['id']);
        } else {
            // Add bookmark
            return $this->insert([
                'user_id' => $userId,
                'ayah_id' => $ayahId,
                'note' => $note
            ]);
        }
    }

    /**
     * Get bookmark count
     */
    public function getBookmarkCount($userId)
    {
        return $this->where('user_id', $userId)->countAllResults();
    }
}
