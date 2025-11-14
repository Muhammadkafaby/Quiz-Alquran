<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <link rel="stylesheet" href="/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-icon">📝</div>
                <h1>Daftar</h1>
                <p>Buat akun Quiz Al-Qur'an baru</p>
            </div>

            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger">
                    <ul style="margin: 0; padding-left: 1.5rem;">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="post" action="/auth/register" class="auth-form">
                <div class="form-group">
                    <label for="full_name">Nama Lengkap</label>
                    <input type="text" id="full_name" name="full_name" class="form-control" value="<?= old('full_name') ?>" required>
                </div>

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control" value="<?= old('username') ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?= old('email') ?>" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                    <small>Minimal 6 karakter</small>
                </div>

                <div class="form-group">
                    <label for="password_confirm">Konfirmasi Password</label>
                    <input type="password" id="password_confirm" name="password_confirm" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Daftar</button>
            </form>

            <div class="auth-footer">
                <p>Sudah punya akun? <a href="/auth/login">Login di sini</a></p>
                <p><a href="/">← Kembali ke Beranda</a></p>
            </div>
        </div>
    </div>

    <style>
    .auth-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        padding: 20px;
    }

    .auth-card {
        background: white;
        padding: 3rem;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-lg);
        max-width: 450px;
        width: 100%;
    }

    .auth-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .auth-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
    }

    .auth-header h1 {
        font-size: 2rem;
        margin-bottom: 0.5rem;
        color: var(--text-dark);
    }

    .auth-header p {
        color: var(--text-light);
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: var(--text-dark);
    }

    .form-group small {
        color: var(--text-light);
        font-size: 0.85rem;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid var(--border-color);
        border-radius: var(--radius-sm);
        font-size: 1rem;
        font-family: 'Poppins', sans-serif;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-color);
    }

    .alert {
        padding: 1rem;
        border-radius: var(--radius-sm);
        margin-bottom: 1.5rem;
    }

    .alert-danger {
        background-color: #fee;
        color: #c33;
        border: 1px solid #fcc;
    }

    .auth-footer {
        text-align: center;
        margin-top: 2rem;
    }

    .auth-footer p {
        margin: 0.5rem 0;
        color: var(--text-light);
    }

    .auth-footer a {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 600;
    }

    .auth-footer a:hover {
        text-decoration: underline;
    }
    </style>
</body>
</html>
