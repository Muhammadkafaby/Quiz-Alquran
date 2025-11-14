<?= view('layout/header') ?>

<div class="page-header">
    <div class="container">
        <h1 class="page-title">Al-Qur'an Digital</h1>
        <p class="page-subtitle">30 Juz, 114 Surah, 6,236 Ayat</p>
    </div>
</div>

<div class="container">
    <!-- Search Box -->
    <div class="search-box">
        <form action="/quran/search" method="get">
            <input type="text" name="q" placeholder="Cari ayat atau terjemahan..." class="search-input">
            <button type="submit" class="btn btn-primary">Cari</button>
        </form>
    </div>

    <!-- Surah List -->
    <div class="surah-list">
        <?php if (!empty($surahs)): ?>
            <?php foreach ($surahs as $surah): ?>
            <a href="/quran/surah/<?= $surah['number'] ?>" class="surah-list-item">
                <div class="surah-number-badge"><?= $surah['number'] ?></div>
                <div class="surah-details">
                    <div class="surah-names">
                        <h3 class="surah-name-latin"><?= esc($surah['name_latin']) ?></h3>
                        <p class="surah-name-translation"><?= esc($surah['name_translation']) ?></p>
                    </div>
                    <div class="surah-meta-info">
                        <span class="verses-count"><?= $surah['number_of_verses'] ?> Ayat</span>
                        <span class="revelation-type"><?= esc($surah['revelation']) ?></span>
                    </div>
                </div>
                <div class="surah-arabic-name"><?= $surah['name_arabic'] ?></div>
            </a>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <p>Data surah tidak tersedia. Silakan import data terlebih dahulu.</p>
                <a href="/admin/import/quran" class="btn btn-primary">Import Data</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= view('layout/footer') ?>
