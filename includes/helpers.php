<?php
/**
 * includes/helpers.php
 * Fungsi-fungsi pembantu umum untuk WishVault.
 * Berisi sanitasi output, format angka, dan utilitas lainnya.
 */

/**
 * Fungsi sanitasi output HTML (escape).
 * Membungkus htmlspecialchars() agar lebih singkat digunakan.
 * WAJIB digunakan pada semua output ke HTML untuk mencegah XSS.
 *
 * @param mixed $value Nilai yang akan di-escape
 * @return string Nilai yang sudah aman untuk ditampilkan di HTML
 */
function e(mixed $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Memformat angka menjadi format mata uang Rupiah.
 * Contoh: 20000000 -> Rp 20.000.000
 *
 * @param float|int|string $amount Jumlah yang akan diformat
 * @return string Format mata uang Rupiah
 */
function formatRupiah(float|int|string $amount): string
{
    return 'Rp ' . number_format((float)$amount, 0, ',', '.');
}

/**
 * Memformat tanggal dari format Y-m-d ke format d/m/Y.
 * Jika null atau kosong, kembalikan strip (-).
 *
 * @param string|null $date Tanggal dalam format Y-m-d
 * @return string Tanggal terformat atau '-'
 */
function formatDate(?string $date): string
{
    if (empty($date)) {
        return '-';
    }
    $dt = DateTime::createFromFormat('Y-m-d', $date);
    return $dt ? $dt->format('d/m/Y') : '-';
}

/**
 * Mengembalikan kunci terjemahan prioritas berdasarkan nilai DB.
 * Nilai DB: Rendah, Sedang, Tinggi
 *
 * @param string $prioritas Nilai prioritas dari database
 * @return string Kunci terjemahan untuk fungsi t()
 */
function priorityLangKey(string $prioritas): string
{
    return match($prioritas) {
        'Rendah' => 'priority_rendah',
        'Sedang' => 'priority_sedang',
        'Tinggi' => 'priority_tinggi',
        default  => $prioritas,
    };
}

/**
 * Mengembalikan kunci terjemahan status berdasarkan nilai DB.
 * Nilai DB: Belum Tercapai, Tercapai
 *
 * @param string $status Nilai status dari database
 * @return string Kunci terjemahan untuk fungsi t()
 */
function statusLangKey(string $status): string
{
    return match($status) {
        'Belum Tercapai' => 'status_belum',
        'Tercapai'       => 'status_tercapai',
        default          => $status,
    };
}

/**
 * Mengembalikan kunci terjemahan kategori berdasarkan nama kategori dari DB.
 *
 * @param string $namaKategori Nama kategori dari database
 * @return string Kunci terjemahan untuk fungsi t()
 */
function categoryLangKey(string $namaKategori): string
{
    return match($namaKategori) {
        'Buku'      => 'cat_buku',
        'Elektronik'=> 'cat_elektronik',
        'Fashion'   => 'cat_fashion',
        'Gaming'    => 'cat_gaming',
        'Hobi'      => 'cat_hobi',
        'Kendaraan' => 'cat_kendaraan',
        'Lainnya'   => 'cat_lainnya',
        'Perjalanan'=> 'cat_perjalanan',
        default     => $namaKategori,
    };
}

/**
 * Mengembalikan kelas CSS badge berdasarkan nilai prioritas.
 *
 * @param string $prioritas Nilai prioritas dari database
 * @return string Nama kelas CSS badge
 */
function priorityBadgeClass(string $prioritas): string
{
    return match($prioritas) {
        'Tinggi' => 'badge badge-high',
        'Sedang' => 'badge badge-medium',
        'Rendah' => 'badge badge-low',
        default  => 'badge',
    };
}

/**
 * Mengembalikan kelas CSS badge berdasarkan nilai status.
 *
 * @param string $status Nilai status dari database
 * @return string Nama kelas CSS badge
 */
function statusBadgeClass(string $status): string
{
    return match($status) {
        'Tercapai'       => 'badge badge-achieved',
        'Belum Tercapai' => 'badge badge-not-achieved',
        default          => 'badge',
    };
}
