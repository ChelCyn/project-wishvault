<?php
/**
 * database/reset_password.php
 * Skrip sekali pakai untuk mereset password user demo 'chel_demo'.
 * Jalankan sekali via browser: http://localhost/db_wishvault/database/reset_password.php
 * HAPUS atau LINDUNGI file ini setelah digunakan!
 */

require_once __DIR__ . '/../config/db.php';

// Password baru yang akan di-hash
$newPassword     = 'password';
$targetUsername  = 'chel_demo';

// Buat hash bcrypt baru
$hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

try {
    $pdo  = getPDO();
    $stmt = $pdo->prepare('UPDATE users SET password = :password WHERE username = :username');
    $stmt->execute([
        ':password' => $hashedPassword,
        ':username' => $targetUsername,
    ]);

    if ($stmt->rowCount() > 0) {
        echo '<h2 style="color:green;">Password berhasil diperbarui!</h2>';
        echo '<p>Username: <strong>' . htmlspecialchars($targetUsername) . '</strong></p>';
        echo '<p>Password baru: <strong>' . htmlspecialchars($newPassword) . '</strong></p>';
        echo '<p>Hash baru: <code>' . htmlspecialchars($hashedPassword) . '</code></p>';
        echo '<hr>';
        echo '<p style="color:red;"><strong>PENTING: Hapus file ini setelah digunakan!</strong></p>';

        // Verifikasi ulang untuk konfirmasi
        $verify = password_verify($newPassword, $hashedPassword);
        echo '<p>Verifikasi password_verify(): <strong>' . ($verify ? 'BERHASIL ✓' : 'GAGAL ✗') . '</strong></p>';
    } else {
        echo '<p style="color:orange;">User "' . htmlspecialchars($targetUsername) . '" tidak ditemukan di database.</p>';
    }

} catch (PDOException $e) {
    echo '<p style="color:red;">Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
}
