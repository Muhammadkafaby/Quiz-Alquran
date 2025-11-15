<?= $this->include('layout/header_new') ?>

<style>
.admin-dashboard {
    padding: 30px;
    max-width: 1400px;
    margin: 0 auto;
}

.admin-header {
    margin-bottom: 30px;
}

.admin-header h1 {
    color: var(--primary-color);
    font-size: 2em;
    margin-bottom: 10px;
}

.admin-nav {
    display: flex;
    gap: 10px;
    margin-bottom: 30px;
    flex-wrap: wrap;
}

.admin-nav-link {
    padding: 10px 20px;
    background: var(--card-bg);
    color: var(--text-color);
    text-decoration: none;
    border-radius: 5px;
    transition: all 0.3s ease;
    border: 1px solid var(--border-color);
}

.admin-nav-link:hover {
    background: var(--primary-color);
    color: white;
    transform: translateY(-2px);
}

.admin-nav-link.active {
    background: var(--primary-color);
    color: white;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    color: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.stat-card.purple {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.stat-card.orange {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.stat-card.green {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.stat-card.red {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
}

.stat-value {
    font-size: 2.5em;
    font-weight: bold;
    margin-bottom: 5px;
}

.stat-label {
    opacity: 0.9;
    font-size: 0.95em;
}

.section {
    background: var(--card-bg);
    border-radius: 10px;
    padding: 25px;
    margin-bottom: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.section-header {
    font-size: 1.4em;
    margin-bottom: 20px;
    color: var(--primary-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

.data-table th {
    background: var(--secondary-color);
    color: white;
    padding: 12px;
    text-align: left;
    font-weight: 600;
}

.data-table td {
    padding: 12px;
    border-bottom: 1px solid var(--border-color);
}

.data-table tr:hover {
    background: rgba(26, 127, 100, 0.05);
}

.badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 0.85em;
    font-weight: 600;
}

.badge.success {
    background: #d4edda;
    color: #155724;
}

.badge.warning {
    background: #fff3cd;
    color: #856404;
}

.badge.danger {
    background: #f8d7da;
    color: #721c24;
}

.chart-container {
    position: relative;
    height: 300px;
    margin-top: 20px;
}

.activity-bar {
    display: flex;
    align-items: flex-end;
    justify-content: space-around;
    height: 200px;
    margin-top: 20px;
    border-bottom: 2px solid var(--border-color);
}

.bar {
    flex: 1;
    background: var(--primary-color);
    margin: 0 5px;
    border-radius: 5px 5px 0 0;
    position: relative;
    transition: all 0.3s ease;
}

.bar:hover {
    background: var(--secondary-color);
    transform: translateY(-5px);
}

.bar-label {
    text-align: center;
    font-size: 0.8em;
    margin-top: 5px;
}

.btn-export {
    background: var(--primary-color);
    color: white;
    padding: 8px 16px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 0.9em;
    transition: all 0.3s ease;
}

.btn-export:hover {
    background: var(--secondary-color);
    transform: translateY(-2px);
}
</style>

<div class="admin-dashboard">
    <div class="admin-header">
        <h1>🎯 Admin Dashboard</h1>
        <p>Selamat datang di panel administrator Quiz Al-Qur'an</p>
    </div>

    <div class="admin-nav">
        <a href="/admin" class="admin-nav-link active">Dashboard</a>
        <a href="/admin/users" class="admin-nav-link">Manajemen User</a>
        <a href="/admin/quizzes" class="admin-nav-link">Daftar Quiz</a>
        <a href="/admin/content" class="admin-nav-link">Konten Al-Qur'an</a>
        <a href="/admin/achievements" class="admin-nav-link">Achievement</a>
        <a href="/admin/analytics" class="admin-nav-link">Analytics</a>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value"><?= number_format($stats['total_users']) ?></div>
            <div class="stat-label">Total Users</div>
        </div>
        <div class="stat-card purple">
            <div class="stat-value"><?= number_format($stats['total_quizzes']) ?></div>
            <div class="stat-label">Total Quiz Diselesaikan</div>
        </div>
        <div class="stat-card orange">
            <div class="stat-value"><?= number_format($stats['total_surahs']) ?></div>
            <div class="stat-label">Total Surah</div>
        </div>
        <div class="stat-card green">
            <div class="stat-value"><?= number_format($stats['total_ayahs']) ?></div>
            <div class="stat-label">Total Ayat</div>
        </div>
        <div class="stat-card red">
            <div class="stat-value"><?= number_format($stats['total_achievements']) ?></div>
            <div class="stat-label">Total Achievement</div>
        </div>
    </div>

    <!-- Daily Activity Chart -->
    <div class="section">
        <div class="section-header">
            <span>📊 Aktivitas Quiz 7 Hari Terakhir</span>
        </div>
        <div class="activity-bar">
            <?php if (!empty($dailyActivity)): ?>
                <?php $maxCount = max(array_column($dailyActivity, 'total_quizzes')); ?>
                <?php foreach ($dailyActivity as $day): ?>
                    <div style="flex: 1;">
                        <div class="bar" style="height: <?= ($day['total_quizzes'] / $maxCount * 100) ?>%"
                             title="<?= $day['total_quizzes'] ?> quiz">
                        </div>
                        <div class="bar-label"><?= date('d/m', strtotime($day['date'])) ?></div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center; width: 100%; color: #999;">Belum ada data aktivitas</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Quiz Statistics by Type -->
    <div class="section">
        <div class="section-header">
            <span>📈 Statistik per Tipe Quiz</span>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Tipe Quiz</th>
                    <th>Total Quiz</th>
                    <th>Rata-rata Skor</th>
                    <th>Skor Tertinggi</th>
                    <th>Rata-rata Waktu (detik)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($quizStats)): ?>
                    <?php foreach ($quizStats as $stat): ?>
                        <tr>
                            <td><strong><?= ucfirst(str_replace('_', ' ', $stat['quiz_type'])) ?></strong></td>
                            <td><?= $stat['total_quizzes'] ?></td>
                            <td><?= round($stat['avg_score'], 1) ?>%</td>
                            <td>
                                <span class="badge <?= $stat['max_score'] >= 80 ? 'success' : ($stat['max_score'] >= 60 ? 'warning' : 'danger') ?>">
                                    <?= $stat['max_score'] ?>%
                                </span>
                            </td>
                            <td><?= round($stat['avg_time'], 0) ?> detik</td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center; color: #999;">Belum ada data quiz</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Recent Quiz Activity -->
    <div class="section">
        <div class="section-header">
            <span>🎮 Aktivitas Quiz Terbaru</span>
            <a href="/admin/export?type=quizzes" class="btn-export">📥 Export CSV</a>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Tipe Quiz</th>
                    <th>Skor</th>
                    <th>Waktu</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($recentQuizzes)): ?>
                    <?php foreach (array_slice($recentQuizzes, 0, 10) as $quiz): ?>
                        <tr>
                            <td><strong><?= esc($quiz['username']) ?></strong></td>
                            <td><?= ucfirst(str_replace('_', ' ', $quiz['quiz_type'])) ?></td>
                            <td>
                                <span class="badge <?= $quiz['score'] >= 80 ? 'success' : ($quiz['score'] >= 60 ? 'warning' : 'danger') ?>">
                                    <?= $quiz['score'] ?>%
                                </span>
                            </td>
                            <td><?= $quiz['time_taken'] ?>s</td>
                            <td><?= date('d/m/Y H:i', strtotime($quiz['created_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center; color: #999;">Belum ada aktivitas quiz</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Top Users Leaderboard -->
    <div class="section">
        <div class="section-header">
            <span>🏆 Top 10 Users</span>
            <a href="/admin/export?type=users" class="btn-export">📥 Export CSV</a>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Username</th>
                    <th>Total Skor</th>
                    <th>Quiz Selesai</th>
                    <th>Bergabung</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($topUsers)): ?>
                    <?php foreach ($topUsers as $index => $user): ?>
                        <tr>
                            <td>
                                <?php if ($index === 0): ?>
                                    🥇
                                <?php elseif ($index === 1): ?>
                                    🥈
                                <?php elseif ($index === 2): ?>
                                    🥉
                                <?php else: ?>
                                    #<?= $index + 1 ?>
                                <?php endif; ?>
                            </td>
                            <td><strong><?= esc($user['username']) ?></strong></td>
                            <td><?= number_format($user['total_score']) ?></td>
                            <td><?= $user['quiz_completed'] ?></td>
                            <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center; color: #999;">Belum ada user terdaftar</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->include('layout/footer') ?>
