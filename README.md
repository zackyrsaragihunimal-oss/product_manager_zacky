# Product Manager

Aplikasi pengelolaan inventaris produk berbasis PHP dan MySQL.

## Teknologi

PHP 8+, MySQL/MariaDB, PDO, HTML5, CSS3, dan XAMPP.

## Fitur

- Dashboard ringkasan produk, stok, dan nilai persediaan
- Tambah, lihat, edit, dan hapus produk
- Pencarian berdasarkan nama atau kategori
- Validasi server-side, prepared statement, escaping output, CSRF, dan flash message

## Menjalankan

1. Salin folder `product_manager_zacky` ke `C:/xampp/htdocs/`.
2. Nyalakan Apache dan MySQL di XAMPP.
3. Buka `http://localhost/phpmyadmin`.
4. Import `database/store_db.sql`.
5. Buka `http://localhost/product_manager_zacky/public/`.

Konfigurasi database XAMPP ada di `config/db.php` dan menggunakan user `root` tanpa password.
