<?php
/**
 * includes/header.php
 * Template header yang digunakan di semua halaman (setelah login).
 * Menampilkan navigasi, nama user, pilihan bahasa, dan flash message.
 *
 * Variabel yang diharapkan sudah tersedia sebelum include:
 * - $pageTitle (string) : Judul halaman untuk tag <title>
 */
?>
<!DOCTYPE html>
<html lang="<?= e(currentLang()) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= e(t('app_tagline')) ?> - <?= e(t('app_name')) ?>">
    <title><?= e($pageTitle ?? t('app_name')) ?></title>
    <link rel="stylesheet" href="/db_wishvault/assets/css/style.css">
</head>
<body>

<header class="site-header">
    <div class="header-container">
        <!-- Logo dan nama aplikasi -->
        <div class="header-brand">
            <a href="/db_wishvault/index.php" class="brand-link">
                <span class="brand-icon">&#10024;</span>
                <span class="brand-name"><?= e(t('app_name')) ?></span>
            </a>
        </div>

        <!-- Navigasi tengah -->
        <nav class="header-nav">
            <a href="/db_wishvault/index.php" class="nav-link"><?= e(t('nav_home')) ?></a>
            <a href="/db_wishvault/create.php" class="nav-link nav-link-add"><?= e(t('nav_add')) ?></a>
        </nav>

        <!-- Area kanan: info user, bahasa, logout -->
        <div class="header-right">
            <!-- Pilihan bahasa -->
            <div class="lang-switcher">
                <a href="/db_wishvault/set_lang.php?lang=id"
                   class="lang-btn <?= currentLang() === 'id' ? 'lang-active' : '' ?>">
                    <?= e(t('lang_id')) ?>
                </a>
                <span class="lang-sep">|</span>
                <a href="/db_wishvault/set_lang.php?lang=en"
                   class="lang-btn <?= currentLang() === 'en' ? 'lang-active' : '' ?>">
                    <?= e(t('lang_en')) ?>
                </a>
            </div>

            <!-- Info user yang login -->
            <span class="user-info">
                <span class="user-icon">&#128100;</span>
                <?= e(t('logged_in_as')) ?>: <strong><?= e($_SESSION['username'] ?? '') ?></strong>
            </span>

            <!-- Tombol logout -->
            <form action="/db_wishvault/logout.php" method="POST" class="logout-form">
                <input type="hidden" name="csrf_token" value="<?= e(generateCsrfToken()) ?>">
                <button type="submit" class="btn btn-logout"><?= e(t('nav_logout')) ?></button>
            </form>
        </div>
    </div>
</header>

<main class="main-content">
    <div class="container">
        <?php
        // Tampilkan flash message jika ada (hanya sekali setelah redirect)
        $flash = getFlash();
        if ($flash !== null):
        ?>
        <div class="alert alert-<?= e($flash['type']) ?>" role="alert">
            <?= e($flash['message']) ?>
        </div>
        <?php endif; ?>
