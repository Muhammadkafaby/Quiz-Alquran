<?= view('layout/header') ?>

<div class="page-header">
    <div class="container">
        <h1 class="page-title">Status Import Data Al-Qur'an</h1>
        <p class="page-subtitle">Monitoring data yang sudah diimport</p>
    </div>
</div>

<div class="container">
    <div class="import-status-container">
        <!-- Database Status -->
        <div class="status-card">
            <h2>Status Database</h2>

            <div class="status-grid">
                <div class="status-item">
                    <div class="status-icon">📚</div>
                    <div class="status-info">
                        <div class="status-label">Total Surah</div>
                        <div class="status-value"><?= $total_surah ?> / <?= $expected_surah ?></div>
                        <?php if ($total_surah >= $expected_surah): ?>
                            <span class="badge-success">✓ Lengkap</span>
                        <?php else: ?>
                            <span class="badge-warning">⚠ Belum Lengkap</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="status-item">
                    <div class="status-icon">📝</div>
                    <div class="status-info">
                        <div class="status-label">Total Ayat</div>
                        <div class="status-value"><?= $total_ayah ?> / <?= $expected_ayah ?></div>
                        <?php if ($total_ayah >= $expected_ayah): ?>
                            <span class="badge-success">✓ Lengkap</span>
                        <?php else: ?>
                            <span class="badge-warning">⚠ Belum Lengkap</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="progress-container">
                <h3>Progress Import Surah</h3>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: <?= round(($total_surah / $expected_surah) * 100) ?>%">
                        <?= round(($total_surah / $expected_surah) * 100) ?>%
                    </div>
                </div>

                <h3>Progress Import Ayat</h3>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: <?= round(($total_ayah / $expected_ayah) * 100) ?>%">
                        <?= round(($total_ayah / $expected_ayah) * 100) ?>%
                    </div>
                </div>
            </div>
        </div>

        <!-- Import Actions -->
        <div class="import-actions">
            <?php if ($total_surah < $expected_surah || $total_ayah < $expected_ayah): ?>
                <div class="alert alert-warning">
                    <p><strong>Data belum lengkap!</strong></p>
                    <p>Silakan jalankan import untuk melengkapi data Al-Qur'an.</p>
                </div>

                <button onclick="startImport()" class="btn btn-primary btn-lg">
                    Mulai Import Data
                </button>

                <div id="import-progress" style="display: none;">
                    <div class="import-log">
                        <h3>Import Log:</h3>
                        <div id="log-content"></div>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-success">
                    <p><strong>✓ Data Al-Qur'an sudah lengkap!</strong></p>
                    <p>Aplikasi siap digunakan.</p>
                </div>

                <a href="/" class="btn btn-primary btn-lg">
                    Kembali ke Beranda
                </a>
            <?php endif; ?>
        </div>

        <!-- Additional Info -->
        <div class="info-card">
            <h3>Informasi</h3>
            <ul>
                <li>Al-Qur'an memiliki 114 surah</li>
                <li>Total 6,236 ayat</li>
                <li>30 Juz</li>
                <li>Data diambil dari API Quran Indonesia</li>
                <li>Proses import memakan waktu sekitar 5-10 menit</li>
            </ul>
        </div>
    </div>
</div>

<style>
.import-status-container {
    max-width: 800px;
    margin: 0 auto;
}

.status-card {
    background: white;
    padding: 2rem;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-md);
    margin-bottom: 2rem;
}

.status-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    margin: 2rem 0;
}

.status-item {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.status-icon {
    font-size: 3rem;
}

.status-label {
    font-size: 0.9rem;
    color: var(--text-light);
    margin-bottom: 0.5rem;
}

.status-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
}

.badge-success {
    display: inline-block;
    background-color: var(--success-color);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: var(--radius-sm);
    font-size: 0.85rem;
    font-weight: 600;
}

.badge-warning {
    display: inline-block;
    background-color: var(--warning-color);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: var(--radius-sm);
    font-size: 0.85rem;
    font-weight: 600;
}

.progress-container {
    margin-top: 2rem;
}

.progress-container h3 {
    font-size: 1rem;
    margin-bottom: 0.5rem;
    color: var(--text-dark);
}

.progress-bar {
    background-color: #e0e0e0;
    border-radius: var(--radius-sm);
    height: 30px;
    overflow: hidden;
    margin-bottom: 1.5rem;
}

.progress-fill {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    transition: width 0.3s ease;
}

.import-actions {
    text-align: center;
    padding: 2rem;
}

.alert {
    padding: 1.5rem;
    border-radius: var(--radius-md);
    margin-bottom: 2rem;
}

.alert-warning {
    background-color: #fff3cd;
    border: 1px solid #ffc107;
    color: #856404;
}

.alert-success {
    background-color: #d4edda;
    border: 1px solid #28a745;
    color: #155724;
}

.import-log {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: var(--radius-md);
    padding: 1.5rem;
    margin-top: 2rem;
    text-align: left;
}

#log-content {
    font-family: monospace;
    font-size: 0.9rem;
    max-height: 400px;
    overflow-y: auto;
    white-space: pre-wrap;
}

.info-card {
    background: var(--bg-light);
    padding: 1.5rem;
    border-radius: var(--radius-md);
    margin-top: 2rem;
}

.info-card h3 {
    margin-bottom: 1rem;
}

.info-card ul {
    list-style: none;
    padding-left: 0;
}

.info-card li {
    padding: 0.5rem 0;
    border-bottom: 1px solid var(--border-color);
}

.info-card li:last-child {
    border-bottom: none;
}

.info-card li:before {
    content: '✓ ';
    color: var(--success-color);
    font-weight: bold;
}
</style>

<script>
function startImport() {
    if (!confirm('Proses import akan memakan waktu 5-10 menit. Lanjutkan?')) {
        return;
    }

    const progressDiv = document.getElementById('import-progress');
    const logContent = document.getElementById('log-content');

    progressDiv.style.display = 'block';
    logContent.textContent = 'Memulai import...\n';

    fetch('/admin/import/quran')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                logContent.textContent += '\n✓ Import berhasil!\n';
                logContent.textContent += `Total ${data.imported} surah telah diimport.\n`;
                logContent.textContent += '\nSilakan refresh halaman untuk melihat status terbaru.';

                setTimeout(() => {
                    location.reload();
                }, 3000);
            } else {
                logContent.textContent += '\n✗ Import gagal!\n';
                logContent.textContent += 'Error: ' + data.message;
            }
        })
        .catch(error => {
            logContent.textContent += '\n✗ Error!\n';
            logContent.textContent += error.toString();
        });
}
</script>

<?= view('layout/footer') ?>
