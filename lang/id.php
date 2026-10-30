<?php
/**
 * lang/id.php
 * Array terjemahan Bahasa Indonesia untuk seluruh antarmuka WishVault.
 */
return [
    // Umum
    'app_name'           => 'WishVault',
    'app_tagline'        => 'Sistem Informasi Manajemen Wishlist',
    'loading'            => 'Memuat...',
    'lang_id'            => 'ID',
    'lang_en'            => 'EN',

    // Navigasi & Header
    'nav_home'           => 'Beranda',
    'nav_add'            => 'Tambah Wishlist',
    'nav_logout'         => 'Keluar',
    'logged_in_as'       => 'Masuk sebagai',

    // Halaman Login
    'login_title'        => 'Masuk - WishVault',
    'login_heading'      => 'Selamat Datang',
    'login_subheading'   => 'Masuk ke akun WishVault Anda',
    'login_username'     => 'Nama Pengguna',
    'login_password'     => 'Kata Sandi',
    'login_btn'          => 'Masuk',
    'login_error'        => 'Nama pengguna atau kata sandi salah.',
    'login_placeholder_username' => 'Masukkan nama pengguna',
    'login_placeholder_password' => 'Masukkan kata sandi',

    // Halaman Index (Daftar Wishlist)
    'index_title'        => 'Daftar Wishlist - WishVault',
    'index_heading'      => 'Wishlist Saya',
    'index_add_btn'      => '+ Tambah Item',
    'index_empty'        => 'Belum ada item wishlist. Mulai tambahkan sekarang!',
    'index_summary_total'=> 'Total Item',
    'index_summary_price'=> 'Total Harga Estimasi',
    'index_summary_achieved' => 'Sudah Tercapai',

    // Header Tabel
    'tbl_no'             => 'No',
    'tbl_item_name'      => 'Nama Item',
    'tbl_category'       => 'Kategori',
    'tbl_price'          => 'Harga Estimasi',
    'tbl_priority'       => 'Prioritas',
    'tbl_target_date'    => 'Target Tanggal',
    'tbl_status'         => 'Status',
    'tbl_action'         => 'Aksi',

    // Form (Create & Edit)
    'create_title'       => 'Tambah Wishlist - WishVault',
    'create_heading'     => 'Tambah Item Wishlist',
    'edit_title'         => 'Edit Wishlist - WishVault',
    'edit_heading'       => 'Edit Item Wishlist',
    'form_item_name'     => 'Nama Item',
    'form_category'      => 'Kategori',
    'form_description'   => 'Deskripsi',
    'form_price'         => 'Harga Estimasi (Rp)',
    'form_priority'      => 'Prioritas',
    'form_target_date'   => 'Target Tanggal (Opsional)',
    'form_status'        => 'Status',
    'form_save'          => 'Simpan',
    'form_update'        => 'Perbarui',
    'form_cancel'        => 'Batal',
    'form_select'        => '-- Pilih --',
    'form_placeholder_name'  => 'Contoh: Laptop Gaming',
    'form_placeholder_desc'  => 'Deskripsi singkat tentang item ini...',
    'form_placeholder_price' => 'Contoh: 15000000',

    // Prioritas (tampilan, nilai DB tetap bahasa Indonesia)
    'priority_rendah'    => 'Rendah',
    'priority_sedang'    => 'Sedang',
    'priority_tinggi'    => 'Tinggi',

    // Status (tampilan)
    'status_belum'       => 'Belum Tercapai',
    'status_tercapai'    => 'Tercapai',

    // Kategori (tampilan)
    'cat_buku'           => 'Buku',
    'cat_elektronik'     => 'Elektronik',
    'cat_fashion'        => 'Fashion',
    'cat_gaming'         => 'Gaming',
    'cat_hobi'           => 'Hobi',
    'cat_kendaraan'      => 'Kendaraan',
    'cat_lainnya'        => 'Lainnya',
    'cat_perjalanan'     => 'Perjalanan',

    // Pesan Sukses
    'msg_created'        => 'Item wishlist berhasil ditambahkan!',
    'msg_updated'        => 'Item wishlist berhasil diperbarui!',
    'msg_deleted'        => 'Item wishlist berhasil dihapus!',

    // Pesan Error
    'err_not_found'      => 'Item tidak ditemukan atau Anda tidak memiliki akses.',
    'err_db'             => 'Terjadi kesalahan pada database. Silakan coba lagi.',
    'err_csrf'           => 'Token keamanan tidak valid. Silakan coba lagi.',
    'err_invalid_method' => 'Metode permintaan tidak valid.',

    // Validasi Form
    'val_name_required'  => 'Nama item wajib diisi.',
    'val_name_max'       => 'Nama item maksimal 150 karakter.',
    'val_cat_required'   => 'Kategori wajib dipilih.',
    'val_cat_invalid'    => 'Kategori yang dipilih tidak valid.',
    'val_price_required' => 'Harga estimasi wajib diisi.',
    'val_price_numeric'  => 'Harga estimasi harus berupa angka.',
    'val_price_min'      => 'Harga estimasi tidak boleh negatif.',
    'val_price_max'      => 'Harga estimasi terlalu besar (maks. 9.999.999.999,99).',
    'val_priority_required' => 'Prioritas wajib dipilih.',
    'val_priority_invalid'  => 'Nilai prioritas tidak valid.',
    'val_status_required'   => 'Status wajib dipilih.',
    'val_status_invalid'    => 'Nilai status tidak valid.',
    'val_date_invalid'   => 'Format tanggal tidak valid. Gunakan format YYYY-MM-DD.',
    'val_desc_max'       => 'Deskripsi maksimal 1000 karakter.',

    // Konfirmasi Hapus
    'delete_confirm'     => 'Apakah Anda yakin ingin menghapus item ini?',
    'btn_delete'         => 'Hapus',
    'btn_edit'           => 'Edit',

    // Footer
    'footer_text'        => '&copy; 2026 WishVault. Dibuat untuk keperluan tugas kuliah.',
];
