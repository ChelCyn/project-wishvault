<?php
/**
 * set_lang.php
 * Mengatur bahasa aktif aplikasi dan menyimpannya ke session.
 * Mengembalikan user ke halaman sebelumnya (dengan validasi redirect).
 */

// Mulai session agar bisa menyimpan pilihan bahasa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Whitelist bahasa yang tersedia
$availableLangs = ['id', 'en'];

// Ambil parameter lang dari URL dan validasi dengan whitelist
$requestedLang = $_GET['lang'] ?? 'id';

if (in_array($requestedLang, $availableLangs, true)) {
    $_SESSION['lang'] = $requestedLang;
}

// Ambil halaman sebelumnya dari HTTP_REFERER untuk redirect kembali
$referer = $_SERVER['HTTP_REFERER'] ?? '';

// Validasi redirect: hanya izinkan ke halaman internal (host yang sama)
// Mencegah open redirect ke situs eksternal
$siteBase = '/db_wishvault/';
$isInternal = false;

if (!empty($referer)) {
    $parsedReferer = parse_url($referer);
    $parsedCurrent = parse_url('http://' . ($_SERVER['HTTP_HOST'] ?? 'localhost'));

    // Cek apakah host sama dengan server saat ini
    if (isset($parsedReferer['host']) && $parsedReferer['host'] === ($parsedCurrent['host'] ?? '')) {
        $isInternal = true;
    }
}

// Redirect ke halaman sebelumnya jika internal, atau ke index jika tidak
if ($isInternal) {
    header('Location: ' . $referer);
} else {
    header('Location: /db_wishvault/index.php');
}
exit;
