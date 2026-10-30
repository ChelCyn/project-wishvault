<?php
/**
 * delete.php
 * Menghapus item wishlist milik user yang sedang login.
 * HANYA menerima request POST (bukan GET) untuk keamanan.
 * Dilindungi dengan token CSRF dan verifikasi kepemilikan data.
 */

// Muat autentikasi dan dependensi
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/lang.php';
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/config/db.php';

// Cek apakah user sudah login
requireLogin();

$userId = (int)$_SESSION['user_id'];

// Tolak semua request selain POST (cegah penghapusan via link GET)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    setFlash('error', t('err_invalid_method'));
    header('Location: /db_wishvault/index.php');
    exit;
}

// Verifikasi token CSRF sebelum melanjutkan
if (!verifyCsrfToken()) {
    setFlash('error', t('err_csrf'));
    header('Location: /db_wishvault/index.php');
    exit;
}

// Ambil dan validasi ID item yang akan dihapus
$itemId = filter_var($_POST['id'] ?? 0, FILTER_VALIDATE_INT);

if ($itemId === false || $itemId <= 0) {
    setFlash('error', t('err_not_found'));
    header('Location: /db_wishvault/index.php');
    exit;
}

// Hapus item dari database: pastikan WHERE menyertakan user_id
// Ini mencegah user menghapus data milik user lain meskipun tahu ID-nya
try {
    $pdo  = getPDO();
    $stmt = $pdo->prepare(
        'DELETE FROM wishlist WHERE id = :id AND user_id = :user_id'
    );
    $stmt->execute([
        ':id'      => $itemId,
        ':user_id' => $userId,
    ]);

    // Cek apakah ada baris yang benar-benar terhapus
    if ($stmt->rowCount() > 0) {
        // Berhasil dihapus
        setFlash('success', t('msg_deleted'));
    } else {
        // Tidak ada baris yang terhapus: item tidak ditemukan atau bukan milik user ini
        setFlash('error', t('err_not_found'));
    }

} catch (PDOException $e) {
    error_log('[WishVault Delete Error] ' . $e->getMessage());
    setFlash('error', t('err_db'));
}

// Redirect kembali ke index (PRG pattern)
header('Location: /db_wishvault/index.php');
exit;
