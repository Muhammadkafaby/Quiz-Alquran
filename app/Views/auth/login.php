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
                <div class="auth-icon">🕌</div>
                <h1>Login</h1>
                <p>Masuk ke akun Quiz Al-Qur'an Anda</p>
            </div>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <form method="post" action="/auth/login" class="auth-form">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?= old('email') ?>" required>
                    <?php if (isset($errors['email'])): ?>
                        <span class="error-text"><?= $errors['email'] ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                    <?php if (isset($errors['password'])): ?>
                        <span class="error-text"><?= $errors['password'] ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember" value="1">
                        <span>Ingat saya</span>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Masuk</button>
            </form>

            <div class="auth-footer">
                <p>Belum punya akun? <a href="/auth/register">Daftar sekarang</a></p>
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

    .checkbox-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }

    .checkbox-label input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .error-text {
        color: var(--danger-color);
        font-size: 0.85rem;
        display: block;
        margin-top: 0.25rem;
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

    .alert-success {
        background-color: #efe;
        color: #3c3;
        border: 1px solid #cfc;
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
