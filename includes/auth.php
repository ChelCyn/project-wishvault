<?php
/**
 * includes/auth.php
 * Memulai session dan memverifikasi apakah user sudah login.
 * Sertakan file ini di semua halaman yang memerlukan autentikasi.
 */

// Mulai session jika belum dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Fungsi untuk memeriksa apakah user sudah login.
 * Jika belum, redirect ke halaman login.
 */
function requireLogin(): void
{
    if (empty($_SESSION['user_id'])) {
        header('Location: /db_wishvault/login.php');
        exit;
    }
}

/**
 * Fungsi untuk menghasilkan token CSRF dan menyimpannya di session.
 * Jika token sudah ada, kembalikan token yang sama.
 */
function generateCsrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        // Buat token acak yang aman secara kriptografi
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Fungsi untuk memverifikasi token CSRF dari form POST.
 * Menggunakan hash_equals() untuk mencegah timing attack.
 */
function verifyCsrfToken(): bool
{
    $tokenFromForm    = $_POST['csrf_token'] ?? '';
    $tokenFromSession = $_SESSION['csrf_token'] ?? '';

    if (empty($tokenFromSession) || empty($tokenFromForm)) {
        return false;
    }

    // hash_equals() aman terhadap timing attack
    return hash_equals($tokenFromSession, $tokenFromForm);
}

/**
 * Fungsi untuk menyimpan flash message ke session.
 * Flash message akan ditampilkan sekali setelah redirect (PRG pattern).
 */
function setFlash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

/**
 * Fungsi untuk mengambil dan menghapus flash message dari session.
 * Mengembalikan array ['type', 'message'] atau null jika tidak ada.
 */
function getFlash(): ?array
{
    if (!empty($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']); // Hapus setelah diambil
        return $flash;
    }
    return null;
}
