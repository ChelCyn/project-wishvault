# WishVault 🌟
**Sistem Informasi Manajemen Wishlist**

Aplikasi web berbasis PHP Murni (tanpa framework, tanpa Composer) dengan MariaDB untuk mengelola daftar keinginan (wishlist) secara terorganisir.

---

## ⚙️ Cara Menjalankan di XAMPP

### Prasyarat
- XAMPP (Apache + MariaDB aktif)
- PHP 8.x
- Browser modern

### Langkah-langkah

1. **Clone / Salin folder proyek**
   Pastikan folder bernama `db_wishvault` berada di:
   ```
   C:\xampp\htdocs\db_wishvault\
   ```

2. **Import Database**
   - Buka phpMyAdmin: `http://localhost/phpmyadmin`
   - Buat database baru bernama **`db_wishvault`**
   - Pilih database tersebut → klik tab **Import**
   - Pilih file `database/db_wishvault.sql` → klik **Go**

3. **Akses Aplikasi**
   Buka browser dan kunjungi:
   ```
   http://localhost/db_wishvault/
   ```
   atau langsung ke:
   ```
   http://localhost/db_wishvault/login.php
   ```

4. **Konfigurasi Database** *(jika diperlukan)*
   Edit `config/db.php` jika host/user/password berbeda:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'db_wishvault');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   ```

---

## 👤 Akun Demo

| Username    | Password   |
|-------------|------------|
| `chel_demo` | `password` |

> **Catatan:** Jika login gagal, jalankan skrip reset password:
> `http://localhost/db_wishvault/database/reset_password.php`
> Hapus file tersebut setelah digunakan.

---

## ✨ Daftar Fitur

### Keamanan
- ✅ Autentikasi dengan bcrypt (`password_verify`)
- ✅ Session regenerasi saat login (cegah session fixation)
- ✅ CSRF token di semua form POST
- ✅ Semua query menggunakan PDO Prepared Statement
- ✅ Output di-escape dengan `htmlspecialchars` (cegah XSS)
- ✅ Halaman protected redirect ke login jika belum auth
- ✅ Data wishlist difilter per `user_id` (cegah akses lintas user)
- ✅ Logout hanya via POST (cegah CSRF via GET link)
- ✅ Delete hanya via POST (cegah penghapusan via URL)

### CRUD Wishlist
- ✅ **Read**: Daftar wishlist dengan tabel rapi + JOIN kategori
- ✅ **Create**: Form tambah item dengan validasi lengkap
- ✅ **Update**: Form edit dengan data pre-fill (sticky)
- ✅ **Delete**: Konfirmasi JavaScript + POST form + CSRF
- ✅ Post/Redirect/Get (PRG) pattern setelah operasi berhasil
- ✅ Flash message sukses/error

### Validasi Form
- ✅ `nama_item`: wajib, maks 150 karakter
- ✅ `kategori_id`: wajib, integer valid, harus ada di DB
- ✅ `harga_estimasi`: wajib, numerik, ≥ 0, maks decimal(12,2)
- ✅ `prioritas`: wajib, switch enum (Rendah/Sedang/Tinggi)
- ✅ `status`: wajib, switch enum (Belum Tercapai/Tercapai)
- ✅ `target_tanggal`: opsional, validasi `DateTime::createFromFormat`
- ✅ `deskripsi`: opsional, maks 1000 karakter
- ✅ Sticky form: data tersimpan saat validasi gagal
- ✅ Pesan error per field

### Tampilan
- ✅ Dual bahasa: Indonesia 🇮🇩 & Inggris 🇬🇧
- ✅ Badge warna untuk prioritas (Merah/Kuning/Hijau)
- ✅ Badge warna untuk status (Hijau/Abu-abu)
- ✅ Kartu ringkasan: total item, total harga, jumlah tercapai
- ✅ Tabel responsif dengan hover effect
- ✅ Desain modern (gradient, shadows, micro-animations)
- ✅ Responsif untuk mobile
- ✅ Header sticky dengan navigasi lengkap
- ✅ Google Fonts (Inter)

---

## 📁 Struktur File

```
db_wishvault/
├── config/
│   └── db.php              # Koneksi PDO (singleton, exception mode, utf8mb4)
├── lang/
│   ├── id.php              # Terjemahan Bahasa Indonesia
│   └── en.php              # Terjemahan Bahasa Inggris
├── includes/
│   ├── auth.php            # Session, requireLogin(), CSRF, flash message
│   ├── lang.php            # Fungsi t() untuk terjemahan
│   ├── helpers.php         # e(), formatRupiah(), formatDate(), badge helpers
│   ├── header.php          # Template header HTML (nav, lang, user, flash)
│   └── footer.php          # Template footer HTML
├── assets/
│   └── css/
│       └── style.css       # CSS vanilla, responsif, modern
├── database/
│   ├── db_wishvault.sql    # Skema dan data awal database
│   └── reset_password.php  # Skrip reset password sekali pakai
├── index.php               # READ: Daftar wishlist + statistik
├── create.php              # CREATE: Form tambah item
├── edit.php                # UPDATE: Form edit item
├── delete.php              # DELETE: Handler hapus (POST only)
├── login.php               # Halaman login
├── logout.php              # Handler logout (POST only)
├── set_lang.php            # Handler ganti bahasa
└── README.md               # Dokumentasi ini
```

---

## 🛠️ Teknologi

- **Backend**: PHP 8.x (murni, tanpa framework)
- **Database**: MariaDB / MySQL via PDO
- **Frontend**: HTML5, CSS3 Vanilla (tanpa framework)
- **Server**: Apache via XAMPP
- **Font**: Google Fonts - Inter

---

*Dibuat untuk keperluan tugas kuliah Sistem Informasi.*
