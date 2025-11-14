<?php

namespace App\Controllers;

use App\Models\BookmarkModel;
use App\Models\AyahModel;

class BookmarkController extends BaseController
{
    protected $bookmarkModel;
    protected $ayahModel;

    public function __construct()
    {
        $this->bookmarkModel = new BookmarkModel();
        $this->ayahModel = new AyahModel();
    }

    /**
     * List all bookmarks
     */
    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $userId = session()->get('user_id');
        $bookmarks = $this->bookmarkModel->getUserBookmarks($userId);

        $data = [
            'title' => 'Ayat Tersimpan',
            'bookmarks' => $bookmarks
        ];

        return view('bookmark/index', $data);
    }

    /**
     * Toggle bookmark (AJAX)
     */
    public function toggle()
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu'
            ]);
        }

        $userId = session()->get('user_id');
        $ayahId = $this->request->getPost('ayah_id');
        $note = $this->request->getPost('note');

        $result = $this->bookmarkModel->toggleBookmark($userId, $ayahId, $note);

        if ($result) {
            $isBookmarked = $this->bookmarkModel->isBookmarked($userId, $ayahId);

            return $this->response->setJSON([
                'success' => true,
                'is_bookmarked' => $isBookmarked,
                'message' => $isBookmarked ? 'Ayat disimpan' : 'Bookmark dihapus'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Gagal menyimpan bookmark'
        ]);
    }

    /**
     * Add note to bookmark
     */
    public function updateNote($bookmarkId)
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu'
            ]);
        }

        $userId = session()->get('user_id');
        $note = $this->request->getPost('note');

        $bookmark = $this->bookmarkModel->find($bookmarkId);

        if (!$bookmark || $bookmark['user_id'] != $userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Bookmark tidak ditemukan'
            ]);
        }

        $result = $this->bookmarkModel->update($bookmarkId, ['note' => $note]);

        return $this->response->setJSON([
            'success' => $result,
            'message' => $result ? 'Catatan berhasil disimpan' : 'Gagal menyimpan catatan'
        ]);
    }

    /**
     * Delete bookmark
     */
    public function delete($bookmarkId)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $userId = session()->get('user_id');
        $bookmark = $this->bookmarkModel->find($bookmarkId);

        if (!$bookmark || $bookmark['user_id'] != $userId) {
            return redirect()->back()->with('error', 'Bookmark tidak ditemukan');
        }

        if ($this->bookmarkModel->delete($bookmarkId)) {
            return redirect()->back()->with('success', 'Bookmark berhasil dihapus');
        }

        return redirect()->back()->with('error', 'Gagal menghapus bookmark');
    }
}
