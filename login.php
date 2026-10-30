<?php
/**
 * login.php
 * Halaman autentikasi pengguna WishVault.
 * Menangani form login dengan prepared statement dan verifikasi bcrypt.
 */

// Mulai session sebelum operasi apapun
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Muat dependensi
require_once __DIR__ . '/includes/lang.php';
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/config/db.php';

// Jika user sudah login, langsung redirect ke halaman utama
if (!empty($_SESSION['user_id'])) {
    header('Location: /db_wishvault/index.php');
    exit;
}

// Inisialisasi variabel
$error        = '';
$oldUsername  = '';

// Proses form login hanya jika metode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Verifikasi token CSRF untuk mencegah CSRF attack
    if (!verifyCsrfToken()) {
        $error = t('err_csrf');
    } else {
        // 2. Ambil dan bersihkan input dari form
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';   // Jangan trim password
        $oldUsername = $username;               // Simpan untuk sticky form

        // 3. Cari user di database menggunakan prepared statement
        try {
            $pdo  = getPDO();
            $stmt = $pdo->prepare('SELECT id, username, password FROM users WHERE username = :username LIMIT 1');
            $stmt->execute([':username' => $username]);
            $user = $stmt->fetch();

            // 4. Verifikasi password dengan password_verify() (bcrypt-safe)
            if ($user && password_verify($password, $user['password'])) {
                // Login berhasil: regenerasi session ID untuk mencegah session fixation
                session_regenerate_id(true);

                // Simpan data user ke session
                $_SESSION['user_id']  = $user['id'];
                $_SESSION['username'] = $user['username'];

                // Set bahasa default jika belum ada
                if (empty($_SESSION['lang'])) {
                    $_SESSION['lang'] = 'id';
                }

                // Redirect ke halaman utama (PRG pattern)
                header('Location: /db_wishvault/index.php');
                exit;
            } else {
                // Login gagal: tampilkan pesan umum (tidak memberi tahu mana yang salah)
                $error = t('login_error');
            }
        } catch (PDOException $e) {
            // Catat error database ke log, tampilkan pesan umum ke user
            error_log('[WishVault Login Error] ' . $e->getMessage());
            $error = t('err_db');
        }
    }
}

// Buat token CSRF untuk form
$csrfToken = generateCsrfToken();
$lang      = currentLang();
?>
<!DOCTYPE html>
<html lang="<?= e($lang) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= e(t('app_tagline')) ?> - Halaman Login">
    <title><?= e(t('login_title')) ?></title>
    <link rel="stylesheet" href="/db_wishvault/assets/css/style.css">
</head>
<body>

<div class="login-page">
    <div class="login-card">

        <!-- Logo dan nama aplikasi -->
        <div class="login-logo">
            <span class="login-logo-icon">&#10024;</span>
            <div class="login-logo-title"><?= e(t('app_name')) ?></div>
            <div class="login-logo-subtitle"><?= e(t('app_tagline')) ?></div>
        </div>

        <!-- Pilihan bahasa di halaman login -->
        <div class="login-lang">
            <a href="/db_wishvault/set_lang.php?lang=id"
               class="lang-btn <?= $lang === 'id' ? 'lang-active' : '' ?>">
                <?= e(t('lang_id')) ?>
            </a>
            <span class="lang-sep">|</span>
            <a href="/db_wishvault/set_lang.php?lang=en"
               class="lang-btn <?= $lang === 'en' ? 'lang-active' : '' ?>">
                <?= e(t('lang_en')) ?>
            </a>
        </div>

        <h1 class="login-heading"><?= e(t('login_heading')) ?></h1>
        <p class="login-subheading"><?= e(t('login_subheading')) ?></p>

        <!-- Tampilkan pesan error jika ada -->
        <?php if ($error): ?>
        <div class="alert alert-error" role="alert">
            &#9888; <?= e($error) ?>
        </div>
        <?php endif; ?>

        <!-- Form login -->
        <form method="POST" action="/db_wishvault/login.php" class="login-form" novalidate>
            <!-- Token CSRF tersembunyi -->
            <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">

            <!-- Field Username -->
            <div class="form-group">
                <label for="username"><?= e(t('login_username')) ?></label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    value="<?= e($oldUsername) ?>"
                    placeholder="<?= e(t('login_placeholder_username')) ?>"
                    autocomplete="username"
                    required
                >
            </div>

            <!-- Field Password -->
            <div class="form-group">
                <label for="password"><?= e(t('login_password')) ?></label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="<?= e(t('login_placeholder_password')) ?>"
                    autocomplete="current-password"
                    required
                >
            </div>

            <!-- Tombol submit -->
            <button type="submit" class="btn-login" id="btn-login">
                <?= e(t('login_btn')) ?>
            </button>
        </form>

    </div>
</div>

</body>
</html>
