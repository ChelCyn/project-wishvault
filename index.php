<?php
/**
 * index.php
 * Halaman utama WishVault - Menampilkan daftar wishlist milik user yang login.
 * Fitur: READ (tabel dengan JOIN), ringkasan statistik, flash message, badge warna.
 */

// Muat autentikasi: session_start + cek login
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/lang.php';
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/config/db.php';

// Pastikan user sudah login (redirect ke login jika belum)
requireLogin();

// ID user yang sedang login (digunakan di semua query)
$userId = (int)$_SESSION['user_id'];

// Ambil data wishlist milik user yang login, JOIN dengan tabel kategori
// Diurutkan dari yang terbaru (ORDER BY created_at DESC)
try {
    $pdo  = getPDO();
    $stmt = $pdo->prepare(
        'SELECT w.id, w.nama_item, w.deskripsi, w.harga_estimasi,
                w.prioritas, w.target_tanggal, w.status, w.created_at,
                k.nama_kategori
         FROM wishlist w
         INNER JOIN kategori k ON w.kategori_id = k.id
         WHERE w.user_id = :user_id
         ORDER BY w.created_at DESC'
    );
    $stmt->execute([':user_id' => $userId]);
    $wishlistItems = $stmt->fetchAll();

    // Hitung ringkasan statistik
    $totalItems    = count($wishlistItems);
    $totalPrice    = 0.0;
    $totalAchieved = 0;

    foreach ($wishlistItems as $item) {
        $totalPrice += (float)$item['harga_estimasi'];
        if ($item['status'] === 'Tercapai') {
            $totalAchieved++;
        }
    }

} catch (PDOException $e) {
    error_log('[WishVault Index Error] ' . $e->getMessage());
    $wishlistItems = [];
    $totalItems    = 0;
    $totalPrice    = 0.0;
    $totalAchieved = 0;
}

$pageTitle = t('index_title');
$csrfToken = generateCsrfToken();

// Include header (menampilkan flash message secara otomatis)
require_once __DIR__ . '/includes/header.php';
?>

<!-- Header halaman -->
<div class="page-header">
    <h1 class="page-title">
        <span class="page-title-icon">&#10024;</span>
        <?= e(t('index_heading')) ?>
    </h1>
    <a href="/db_wishvault/create.php" class="btn btn-primary" id="btn-add-item">
        <?= e(t('index_add_btn')) ?>
    </a>
</div>

<!-- Kartu ringkasan statistik -->
<div class="summary-grid">
    <div class="summary-card">
        <span class="summary-card-label"><?= e(t('index_summary_total')) ?></span>
        <span class="summary-card-value value-primary"><?= e($totalItems) ?></span>
    </div>
    <div class="summary-card">
        <span class="summary-card-label"><?= e(t('index_summary_price')) ?></span>
        <span class="summary-card-value value-warning"><?= e(formatRupiah($totalPrice)) ?></span>
    </div>
    <div class="summary-card">
        <span class="summary-card-label"><?= e(t('index_summary_achieved')) ?></span>
        <span class="summary-card-value value-success"><?= e($totalAchieved) ?></span>
    </div>
</div>

<!-- Tabel daftar wishlist -->
<?php if (empty($wishlistItems)): ?>
    <!-- Tampilkan pesan jika data kosong -->
    <div class="table-wrapper">
        <div class="empty-state">
            <span class="empty-state-icon">&#128173;</span>
            <p class="empty-state-text"><?= e(t('index_empty')) ?></p>
        </div>
    </div>
<?php else: ?>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th><?= e(t('tbl_no')) ?></th>
                    <th><?= e(t('tbl_item_name')) ?></th>
                    <th><?= e(t('tbl_category')) ?></th>
                    <th><?= e(t('tbl_price')) ?></th>
                    <th><?= e(t('tbl_priority')) ?></th>
                    <th><?= e(t('tbl_target_date')) ?></th>
                    <th><?= e(t('tbl_status')) ?></th>
                    <th><?= e(t('tbl_action')) ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($wishlistItems as $index => $item): ?>
                <tr>
                    <!-- Nomor urut -->
                    <td class="td-number"><?= e($index + 1) ?></td>

                    <!-- Nama item -->
                    <td>
                        <strong><?= e($item['nama_item']) ?></strong>
                        <?php if (!empty($item['deskripsi'])): ?>
                            <br>
                            <small class="text-muted"><?= e(mb_substr($item['deskripsi'], 0, 60)) ?><?= strlen($item['deskripsi']) > 60 ? '...' : '' ?></small>
                        <?php endif; ?>
                    </td>

                    <!-- Kategori (diterjemahkan) -->
                    <td><?= e(t(categoryLangKey($item['nama_kategori']))) ?></td>

                    <!-- Harga estimasi (format Rupiah) -->
                    <td class="td-price"><?= e(formatRupiah($item['harga_estimasi'])) ?></td>

                    <!-- Prioritas dengan badge warna -->
                    <td>
                        <span class="<?= e(priorityBadgeClass($item['prioritas'])) ?>">
                            <?= e(t(priorityLangKey($item['prioritas']))) ?>
                        </span>
                    </td>

                    <!-- Target tanggal (format d/m/Y) -->
                    <td class="td-date"><?= e(formatDate($item['target_tanggal'])) ?></td>

                    <!-- Status dengan badge warna -->
                    <td>
                        <span class="<?= e(statusBadgeClass($item['status'])) ?>">
                            <?= e(t(statusLangKey($item['status']))) ?>
                        </span>
                    </td>

                    <!-- Aksi: Edit dan Hapus -->
                    <td class="td-actions">
                        <!-- Tombol Edit -->
                        <a href="/db_wishvault/edit.php?id=<?= e($item['id']) ?>"
                           class="btn btn-edit"
                           id="btn-edit-<?= e($item['id']) ?>">
                            <?= e(t('btn_edit')) ?>
                        </a>

                        <!-- Form Hapus (POST, bukan GET link) -->
                        <form method="POST" action="/db_wishvault/delete.php"
                              style="display:inline;"
                              id="form-delete-<?= e($item['id']) ?>">
                            <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
                            <input type="hidden" name="id" value="<?= e($item['id']) ?>">
                            <button type="submit"
                                    class="btn btn-delete"
                                    id="btn-delete-<?= e($item['id']) ?>"
                                    onclick="return confirm('<?= e(t('delete_confirm')) ?>')">
                                <?= e(t('btn_delete')) ?>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
