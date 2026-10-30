<?php
/**
 * logout.php
 * Menghancurkan session pengguna dan mengarahkan kembali ke halaman login.
 * Hanya menerima request POST untuk keamanan (mencegah CSRF via link GET).
 */

// Mulai session agar bisa menghancurkannya
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/includes/auth.php';

// Verifikasi token CSRF untuk memastikan logout dilakukan dari form yang sah
if ($_SERVER['REQUEST_METHOD'] === 'POST' && verifyCsrfToken()) {
    // 1. Kosongkan semua data session
    $_SESSION = [];

    // 2. Hapus cookie session dari browser jika ada
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,           // Waktu lampau agar cookie langsung expired
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    // 3. Hancurkan session di server
    session_destroy();
}

// Redirect ke halaman login (selalu, terlepas apakah logout valid atau tidak)
header('Location: /db_wishvault/login.php');
exit;
