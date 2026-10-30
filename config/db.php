<?php
/**
 * config/db.php
 * Konfigurasi dan koneksi database menggunakan PDO.
 * Mode exception diaktifkan agar error SQL dapat ditangkap dengan try-catch.
 */

// Konfigurasi koneksi database
define('DB_HOST', 'localhost');
define('DB_NAME', 'db_wishvault');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

/**
 * Fungsi untuk mendapatkan koneksi PDO (singleton sederhana).
 * Mengembalikan instance PDO yang sudah dikonfigurasi.
 */
function getPDO(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,   // Lempar exception saat error SQL
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,         // Hasil fetch sebagai array asosiatif
            PDO::ATTR_EMULATE_PREPARES   => false,                    // Gunakan prepared statement asli
        ];

        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // Catat error ke log server, jangan tampilkan ke user
            error_log('[WishVault DB Error] ' . $e->getMessage());
            // Tampilkan pesan umum ke user
            die('Koneksi database gagal. Silakan coba lagi nanti.');
        }
    }

    return $pdo;
}
