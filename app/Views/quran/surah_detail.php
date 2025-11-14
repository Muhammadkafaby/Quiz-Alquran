<?= view('layout/header') ?>

<div class="page-header surah-header">
    <div class="container">
        <div class="surah-header-content">
            <div class="surah-number-large"><?= $surah['number'] ?></div>
            <div>
                <h1 class="surah-title"><?= esc($surah['name_latin']) ?></h1>
                <p class="surah-arabic-title"><?= $surah['name_arabic'] ?></p>
                <p class="surah-meta">
                    <?= esc($surah['name_translation']) ?> •
                    <?= $surah['number_of_verses'] ?> Ayat •
                    <?= esc($surah['revelation']) ?>
                </p>
            </div>
        </div>

        <?php if (!empty($surah['tafsir'])): ?>
        <div class="surah-tafsir">
            <h3>Tafsir Singkat</h3>
            <p><?= nl2br(esc($surah['tafsir'])) ?></p>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="container">
    <!-- Bismillah (except for Surah At-Taubah) -->
    <?php if ($surah['number'] != 9): ?>
    <div class="bismillah">
        بِسْمِ ٱللَّهِ ٱلرَّحْمَٰنِ ٱلرَّحِيمِ
    </div>
    <?php endif; ?>

    <!-- Verses -->
    <div class="verses-container">
        <?php if (!empty($verses)): ?>
            <?php foreach ($verses as $verse): ?>
            <div class="verse-card" id="verse-<?= $verse['number_in_surah'] ?>">
                <div class="verse-number-badge"><?= $verse['number_in_surah'] ?></div>
                <div class="verse-content">
                    <p class="verse-arabic"><?= $verse['text_arabic'] ?></p>

                    <?php if (!empty($verse['text_latin'])): ?>
                    <p class="verse-latin"><?= esc($verse['text_latin']) ?></p>
                    <?php endif; ?>

                    <p class="verse-translation">
                        <strong>Artinya:</strong> <?= esc($verse['translation_id']) ?>
                    </p>

                    <?php if (!empty($verse['tafsir'])): ?>
                    <div class="verse-tafsir">
                        <details>
                            <summary>Tafsir</summary>
                            <p><?= nl2br(esc($verse['tafsir'])) ?></p>
                        </details>
                    </div>
                    <?php endif; ?>

                    <div class="verse-meta">
                        <span>Juz <?= $verse['juz'] ?></span>
                        <?php if (!empty($verse['page'])): ?>
                        <span>Halaman <?= $verse['page'] ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">Ayat tidak tersedia</p>
        <?php endif; ?>
    </div>

    <!-- Navigation -->
    <div class="surah-navigation">
        <?php if ($surah['number'] > 1): ?>
        <a href="/quran/surah/<?= $surah['number'] - 1 ?>" class="btn btn-outline">
            ← Surah Sebelumnya
        </a>
        <?php endif; ?>

        <a href="/quran" class="btn btn-primary">
            Daftar Surah
        </a>

        <?php if ($surah['number'] < 114): ?>
        <a href="/quran/surah/<?= $surah['number'] + 1 ?>" class="btn btn-outline">
            Surah Selanjutnya →
        </a>
        <?php endif; ?>
    </div>
</div>

<?= view('layout/footer') ?>
