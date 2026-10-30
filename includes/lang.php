<?php
/**
 * includes/lang.php
 * Fungsi helper untuk sistem multi-bahasa WishVault.
 * Memuat file bahasa dan menyediakan fungsi t() untuk terjemahan.
 */

/**
 * Memuat array terjemahan berdasarkan bahasa aktif di session.
 * Menggunakan static variable agar file bahasa hanya dimuat sekali.
 */
function loadTranslations(): array
{
    static $translations = null;

    if ($translations === null) {
        // Ambil bahasa dari session, default ke 'id'
        $lang = $_SESSION['lang'] ?? 'id';

        // Whitelist bahasa yang tersedia
        $availableLangs = ['id', 'en'];

        if (!in_array($lang, $availableLangs, true)) {
            $lang = 'id';
        }

        // Muat file bahasa yang sesuai
        $langFile = __DIR__ . '/../lang/' . $lang . '.php';

        if (file_exists($langFile)) {
            $translations = require $langFile;
        } else {
            $translations = [];
        }
    }

    return $translations;
}

/**
 * Fungsi terjemahan utama.
 * Mengembalikan teks terjemahan berdasarkan kunci.
 * Jika kunci tidak ditemukan, kembalikan kuncinya sendiri.
 *
 * @param string $key Kunci terjemahan
 * @return string Teks terjemahan
 */
function t(string $key): string
{
    $translations = loadTranslations();
    return $translations[$key] ?? $key;
}

/**
 * Mengembalikan kode bahasa aktif saat ini.
 */
function currentLang(): string
{
    return $_SESSION['lang'] ?? 'id';
}
