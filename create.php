<?php
/**
 * create.php
 * Halaman untuk menambahkan item wishlist baru.
 * Fitur: form POST, validasi server-side, sanitasi, CSRF, sticky form, PRG pattern.
 */

// Muat autentikasi dan dependensi
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/lang.php';
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/config/db.php';

// Cek apakah user sudah login
requireLogin();

$userId = (int)$_SESSION['user_id'];

// Muat daftar kategori dari database untuk dropdown
try {
    $pdo      = getPDO();
    $stmtKat  = $pdo->query('SELECT id, nama_kategori FROM kategori ORDER BY nama_kategori ASC');
    $kategoriList = $stmtKat->fetchAll();
} catch (PDOException $e) {
    error_log('[WishVault Create - Load Kategori Error] ' . $e->getMessage());
    $kategoriList = [];
}

// Inisialisasi array error dan data form (sticky form)
$errors = [];
$form   = [
    'nama_item'       => '',
    'kategori_id'     => '',
    'deskripsi'       => '',
    'harga_estimasi'  => '',
    'prioritas'       => '',
    'target_tanggal'  => '',
    'status'          => 'Belum Tercapai', // Default status
];

// Nilai enum yang valid (harus cocok dengan definisi di database)
$validPrioritas = ['Rendah', 'Sedang', 'Tinggi'];
$validStatus    = ['Belum Tercapai', 'Tercapai'];

// Proses form hanya jika request POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Verifikasi token CSRF
    if (!verifyCsrfToken()) {
        $errors['csrf'] = t('err_csrf');
    } else {

        // 2. Ambil input dari form dan trim whitespace
        $form['nama_item']      = trim($_POST['nama_item'] ?? '');
        $form['kategori_id']    = trim($_POST['kategori_id'] ?? '');
        $form['deskripsi']      = trim($_POST['deskripsi'] ?? '');
        $form['harga_estimasi'] = trim($_POST['harga_estimasi'] ?? '');
        $form['prioritas']      = trim($_POST['prioritas'] ?? '');
        $form['target_tanggal'] = trim($_POST['target_tanggal'] ?? '');
        $form['status']         = trim($_POST['status'] ?? '');

        // =====================================================
        // VALIDASI: Kumpulkan semua error ke array $errors
        // =====================================================

        // Validasi: nama_item (wajib, maks 150 karakter)
        if ($form['nama_item'] === '') {
            $errors['nama_item'] = t('val_name_required');
        } elseif (mb_strlen($form['nama_item']) > 150) {
            $errors['nama_item'] = t('val_name_max');
        }

        // Validasi: kategori_id (wajib, integer valid, harus ada di DB)
        $kategoriId = filter_var($form['kategori_id'], FILTER_VALIDATE_INT);
        if ($kategoriId === false || $kategoriId <= 0) {
            $errors['kategori_id'] = t('val_cat_required');
        } else {
            // Cek apakah kategori ada di database
            try {
                $stmtCek = $pdo->prepare('SELECT id FROM kategori WHERE id = :id LIMIT 1');
                $stmtCek->execute([':id' => $kategoriId]);
                if (!$stmtCek->fetch()) {
                    $errors['kategori_id'] = t('val_cat_invalid');
                }
            } catch (PDOException $e) {
                error_log('[WishVault Create - Cek Kategori Error] ' . $e->getMessage());
                $errors['kategori_id'] = t('err_db');
            }
        }

        // Validasi: harga_estimasi (wajib, numerik, >= 0, maks decimal(12,2))
        if ($form['harga_estimasi'] === '') {
            $errors['harga_estimasi'] = t('val_price_required');
        } elseif (!is_numeric($form['harga_estimasi'])) {
            $errors['harga_estimasi'] = t('val_price_numeric');
        } elseif ((float)$form['harga_estimasi'] < 0) {
            $errors['harga_estimasi'] = t('val_price_min');
        } elseif ((float)$form['harga_estimasi'] > 9999999999.99) {
            $errors['harga_estimasi'] = t('val_price_max');
        }

        // Validasi: prioritas (wajib, harus salah satu nilai enum yang valid)
        if (empty($form['prioritas'])) {
            $errors['prioritas'] = t('val_priority_required');
        } else {
            switch ($form['prioritas']) {
                case 'Rendah':
                case 'Sedang':
                case 'Tinggi':
                    // Nilai valid, tidak ada error
                    break;
                default:
                    $errors['prioritas'] = t('val_priority_invalid');
            }
        }

        // Validasi: status (wajib, harus salah satu nilai enum yang valid)
        if (empty($form['status'])) {
            $errors['status'] = t('val_status_required');
        } else {
            switch ($form['status']) {
                case 'Belum Tercapai':
                case 'Tercapai':
                    // Nilai valid
                    break;
                default:
                    $errors['status'] = t('val_status_invalid');
            }
        }

        // Validasi: target_tanggal (opsional; jika diisi harus format Y-m-d yang valid)
        $targetTanggal = null; // Default NULL untuk database
        if ($form['target_tanggal'] !== '') {
            $dateObj   = DateTime::createFromFormat('Y-m-d', $form['target_tanggal']);
            $dateError = DateTime::getLastErrors();
            // Cek apakah tanggal valid dan tidak ada peringatan/error parsing
            if (!$dateObj || ($dateError['warning_count'] > 0) || ($dateError['error_count'] > 0)) {
                $errors['target_tanggal'] = t('val_date_invalid');
            } else {
                $targetTanggal = $dateObj->format('Y-m-d');
            }
        }

        // Validasi: deskripsi (opsional, maks 1000 karakter)
        if (mb_strlen($form['deskripsi']) > 1000) {
            $errors['deskripsi'] = t('val_desc_max');
        }

        // =====================================================
        // SIMPAN KE DATABASE jika tidak ada error validasi
        // =====================================================
        if (empty($errors)) {
            try {
                $stmt = $pdo->prepare(
                    'INSERT INTO wishlist
                        (user_id, kategori_id, nama_item, deskripsi, harga_estimasi, prioritas, target_tanggal, status)
                     VALUES
                        (:user_id, :kategori_id, :nama_item, :deskripsi, :harga_estimasi, :prioritas, :target_tanggal, :status)'
                );

                $stmt->execute([
                    ':user_id'         => $userId,
                    ':kategori_id'     => $kategoriId,
                    ':nama_item'       => $form['nama_item'],
                    ':deskripsi'       => $form['deskripsi'] !== '' ? $form['deskripsi'] : null,
                    ':harga_estimasi'  => (float)$form['harga_estimasi'],
                    ':prioritas'       => $form['prioritas'],
                    ':target_tanggal'  => $targetTanggal,
                    ':status'          => $form['status'],
                ]);

                // Set flash message sukses dan redirect (PRG pattern)
                setFlash('success', t('msg_created'));
                header('Location: /db_wishvault/index.php');
                exit;

            } catch (PDOException $e) {
                error_log('[WishVault Create - Insert Error] ' . $e->getMessage());
                $errors['db'] = t('err_db');
            }
        }
    }
}

$pageTitle = t('create_title');
$csrfToken = generateCsrfToken();

require_once __DIR__ . '/includes/header.php';
?>

<div class="form-card">
    <h1 class="form-heading">
        <span>&#127775;</span>
        <?= e(t('create_heading')) ?>
    </h1>

    <!-- Tampilkan error CSRF atau database jika ada -->
    <?php if (!empty($errors['csrf'])): ?>
        <div class="alert alert-error"><?= e($errors['csrf']) ?></div>
    <?php endif; ?>
    <?php if (!empty($errors['db'])): ?>
        <div class="alert alert-error"><?= e($errors['db']) ?></div>
    <?php endif; ?>

    <form method="POST" action="/db_wishvault/create.php" novalidate>
        <!-- Token CSRF -->
        <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">

        <div class="form-grid">

            <!-- Nama Item -->
            <div class="form-group full-width">
                <label for="nama_item">
                    <?= e(t('form_item_name')) ?> <span class="required">*</span>
                </label>
                <input
                    type="text"
                    id="nama_item"
                    name="nama_item"
                    value="<?= e($form['nama_item']) ?>"
                    maxlength="150"
                    placeholder="<?= e(t('form_placeholder_name')) ?>"
                    class="<?= !empty($errors['nama_item']) ? 'is-invalid' : '' ?>"
                >
                <?php if (!empty($errors['nama_item'])): ?>
                    <span class="field-error">&#9888; <?= e($errors['nama_item']) ?></span>
                <?php endif; ?>
            </div>

            <!-- Kategori -->
            <div class="form-group">
                <label for="kategori_id">
                    <?= e(t('form_category')) ?> <span class="required">*</span>
                </label>
                <select
                    id="kategori_id"
                    name="kategori_id"
                    class="<?= !empty($errors['kategori_id']) ? 'is-invalid' : '' ?>"
                >
                    <option value=""><?= e(t('form_select')) ?></option>
                    <?php foreach ($kategoriList as $kat): ?>
                    <option
                        value="<?= e($kat['id']) ?>"
                        <?= (string)$form['kategori_id'] === (string)$kat['id'] ? 'selected' : '' ?>
                    >
                        <?= e(t(categoryLangKey($kat['nama_kategori']))) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <?php if (!empty($errors['kategori_id'])): ?>
                    <span class="field-error">&#9888; <?= e($errors['kategori_id']) ?></span>
                <?php endif; ?>
            </div>

            <!-- Harga Estimasi -->
            <div class="form-group">
                <label for="harga_estimasi">
                    <?= e(t('form_price')) ?> <span class="required">*</span>
                </label>
                <input
                    type="number"
                    id="harga_estimasi"
                    name="harga_estimasi"
                    value="<?= e($form['harga_estimasi']) ?>"
                    min="0"
                    step="0.01"
                    placeholder="<?= e(t('form_placeholder_price')) ?>"
                    class="<?= !empty($errors['harga_estimasi']) ? 'is-invalid' : '' ?>"
                >
                <?php if (!empty($errors['harga_estimasi'])): ?>
                    <span class="field-error">&#9888; <?= e($errors['harga_estimasi']) ?></span>
                <?php endif; ?>
            </div>

            <!-- Prioritas -->
            <div class="form-group">
                <label for="prioritas">
                    <?= e(t('form_priority')) ?> <span class="required">*</span>
                </label>
                <select
                    id="prioritas"
                    name="prioritas"
                    class="<?= !empty($errors['prioritas']) ? 'is-invalid' : '' ?>"
                >
                    <option value=""><?= e(t('form_select')) ?></option>
                    <option value="Rendah" <?= $form['prioritas'] === 'Rendah' ? 'selected' : '' ?>>
                        <?= e(t('priority_rendah')) ?>
                    </option>
                    <option value="Sedang" <?= $form['prioritas'] === 'Sedang' ? 'selected' : '' ?>>
                        <?= e(t('priority_sedang')) ?>
                    </option>
                    <option value="Tinggi" <?= $form['prioritas'] === 'Tinggi' ? 'selected' : '' ?>>
                        <?= e(t('priority_tinggi')) ?>
                    </option>
                </select>
                <?php if (!empty($errors['prioritas'])): ?>
                    <span class="field-error">&#9888; <?= e($errors['prioritas']) ?></span>
                <?php endif; ?>
            </div>

            <!-- Status -->
            <div class="form-group">
                <label for="status">
                    <?= e(t('form_status')) ?> <span class="required">*</span>
                </label>
                <select
                    id="status"
                    name="status"
                    class="<?= !empty($errors['status']) ? 'is-invalid' : '' ?>"
                >
                    <option value="Belum Tercapai" <?= $form['status'] === 'Belum Tercapai' ? 'selected' : '' ?>>
                        <?= e(t('status_belum')) ?>
                    </option>
                    <option value="Tercapai" <?= $form['status'] === 'Tercapai' ? 'selected' : '' ?>>
                        <?= e(t('status_tercapai')) ?>
                    </option>
                </select>
                <?php if (!empty($errors['status'])): ?>
                    <span class="field-error">&#9888; <?= e($errors['status']) ?></span>
                <?php endif; ?>
            </div>

            <!-- Target Tanggal (Opsional) -->
            <div class="form-group">
                <label for="target_tanggal"><?= e(t('form_target_date')) ?></label>
                <input
                    type="date"
                    id="target_tanggal"
                    name="target_tanggal"
                    value="<?= e($form['target_tanggal']) ?>"
                    class="<?= !empty($errors['target_tanggal']) ? 'is-invalid' : '' ?>"
                >
                <?php if (!empty($errors['target_tanggal'])): ?>
                    <span class="field-error">&#9888; <?= e($errors['target_tanggal']) ?></span>
                <?php endif; ?>
            </div>

            <!-- Deskripsi (Opsional) -->
            <div class="form-group full-width">
                <label for="deskripsi"><?= e(t('form_description')) ?></label>
                <textarea
                    id="deskripsi"
                    name="deskripsi"
                    maxlength="1000"
                    placeholder="<?= e(t('form_placeholder_desc')) ?>"
                    class="<?= !empty($errors['deskripsi']) ? 'is-invalid' : '' ?>"
                ><?= e($form['deskripsi']) ?></textarea>
                <?php if (!empty($errors['deskripsi'])): ?>
                    <span class="field-error">&#9888; <?= e($errors['deskripsi']) ?></span>
                <?php endif; ?>
            </div>

            <!-- Tombol Aksi -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary" id="btn-save">
                    <?= e(t('form_save')) ?>
                </button>
                <a href="/db_wishvault/index.php" class="btn btn-secondary">
                    <?= e(t('form_cancel')) ?>
                </a>
            </div>

        </div><!-- /.form-grid -->
    </form>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
