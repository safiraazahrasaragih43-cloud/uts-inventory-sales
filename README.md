# 🌸 BlushBoutique — Inventory & Sales Management System

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-10.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 10">
  <img src="https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.1+">
  <img src="https://img.shields.io/badge/Tailwind_CSS-CDN-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/Database-SQLite%20%2F%20MySQL-003B57?style=for-the-badge&logo=sqlite&logoColor=white" alt="SQLite/MySQL">
  <img src="https://img.shields.io/badge/Status-Production%20Ready-success?style=for-the-badge" alt="Status">
</p>

---

## 📖 Tentang Aplikasi

**BlushBoutique** adalah aplikasi *Inventory & Sales Management* yang dirancang khusus untuk butik dan toko pakaian wanita. Mengusung tema visual antarmuka **Blush Pink & Pastel Soft** yang rapi, modern, dan feminin, aplikasi ini memadukan kemudahan operasional kasir harian (*Point of Sales*), manajemen stok pakaian multi-kategori, sistem restock pemasok (*supplier*), serta laporan keuangan laba kotor yang terperinci.

Selain dashboard operasional untuk pemilik dan staff, sistem ini juga menyediakan **Etalase Web Publik** yang dapat diakses oleh pengunjung **tanpa perlu login**, di mana pelanggan dapat menjelajahi koleksi busana dan melakukan pemesanan langsung melalui WhatsApp toko secara otomatis.

---

## ✨ Fitur-Fitur Utama

### 1. 🛍️ Katalog Publik untuk Pengunjung (Tanpa Login)
- **Akses Langsung (`/`)**: Pengunjung dapat melihat etalase koleksi pakaian tanpa harus mendaftar atau login.
- **Pencarian & Filter**: Cari busana berdasarkan nama, kode SKU, atau filter per kategori (*Floral Dresses*, *Blouses*, *Skirts*, *Knitwear*, *Hijabs*).
- **Indikator Ketersediaan Stok**: Status *Ready Stock*, *Sisa Sedikit*, atau *Habis Terjual*.
- **Pemesanan Otomatis via WhatsApp**: Setiap kartu produk dilengkapi tombol **"Pesan via WA"** yang otomatis mengarahkan ke nomor WhatsApp toko dengan pesan pemesanan yang sudah terformat rapi (Nama produk, SKU, dan harga resmi).
- **Profil Butik & Jam Buka**: Informasi lokasi showroom butik fisik dan jam layanan operasional.

### 2. 🔐 Autentikasi Berbasis Peran (*Role-Based Access Control*)
Dilindungi oleh middleware khusus [`RoleMiddleware`](app/Http/Middleware/RoleMiddleware.php):
- **👑 Admin / Pemilik**:
  - Mengelola seluruh Master Data (Kategori, Pemasok/Supplier, dan Produk Pakaian).
  - Menambah, mengubah, mengunggah foto, dan menghapus busana.
  - Mengakses Laporan Analisis Penjualan & Laba Kotor.
  - Mengakses Laporan Valuasi Nilai Persediaan Stok.
- **🛍️ Staff Kasir**:
  - Melakukan transaksi penjualan kasir (*Point of Sales*).
  - Melakukan transaksi pembelian/restock barang masuk dari supplier.
  - Melihat katalog produk dan ketersediaan stok pakaian.
  - Dibatasi secara ketat dari perubahan master data dan laporan laba (*403 Forbidden*).

### 3. 🔒 Logika Transaksi & Konsistensi Stok Ketat
- **Proteksi *Race Condition***: Seluruh proses penjualan (`sales`) dan restock (`purchases`) dibungkus dalam `DB::transaction` dan menggunakan `lockForUpdate()` pada baris produk di database.
- **Validasi Stok Otomatis**: Transaksi penjualan **otomatis ditolak** jika jumlah pembelian melebihi stok fisik yang tersedia di database.
- **Integritas Perhitungan di Sisi Server**: Total harga dan subtotal dihitung murni di server berdasarkan `selling_price` di database, bukan mempercayai input dari sisi browser client.
- **Sinkronisasi Stok**:
  - Transaksi Penjualan (`Sales`) $\rightarrow$ Mengurangi stok produk (`decrement`).
  - Transaksi Pembelian (`Purchases`) $\rightarrow$ Menambah stok produk (`increment`).

### 4. 📸 Manajemen Foto Busana
- Mendukung unggah foto produk pakaian format `JPG`, `PNG`, `WEBP`, dan `GIF` (maksimal 2 MB).
- Fitur **Pratinjau Langsung (*Live Image Preview*)** saat memilih berkas foto di form tambah/edit produk.
- Terhubung otomatis dengan symlink `public/storage`.
- Otomatis membersihkan/menghapus berkas foto lama saat produk diperbarui atau dihapus.

### 5. 📊 Dashboard Ringkas & Peringatan Stok Kritis
- **Kartu Metrik KPI**: Total Penjualan, Pendapatan Hari Ini, Total Unit Stok Busana, dan Nilai Aset Modal Inventory (Khusus Admin).
- **Tabel Peringatan Stok Menipis (*Low Stock Alert*)**: Mendeteksi dan menampilkan daftar pakaian yang stoknya berada di bawah atau sama dengan batas minimum stok (`stock <= minimum_stock`), lengkap dengan tombol *quick restock*.
- **Feed Riwayat Transaksi**: Menampilkan 5 penjualan kasir dan 5 pembelian stok terbaru.

### 6. 🌸 Master Data Pelanggan (Customers) & Integrasi Kasir
- **CRUD Pelanggan Lengkap**: Kelola basis data pelanggan tetap/member (Nama, No. Telepon/WA, Email, dan Alamat).
- **Integrasi Dropdown Kasir POS**: Form kasir penjualan dilengkapi pemilih pelanggan dengan fitur sinkronisasi otomatis nama nota.
- **Dukungan Pembeli Umum (*Walk-in Customer*)**: Kolom relasi bersifat `nullable` sehingga kasir tetap leluasa melayani pembeli langsung tanpa keharusan mendaftarkan akun pelanggan.
- **Badge Member & Riwayat Belanja**: Nota dan riwayat transaksi menampilkan status member dan riwayat total pembelian tiap pelanggan secara akurat.
- **Bebas Masalah N+1 Query**: Eager loading (`with(['customer', 'user', 'items.product'])`) diterapkan di seluruh daftar transaksi penjualan.

### 7. 📑 Laporan Keuangan & Valuasi Persediaan (Khusus Admin)
- **Laporan Penjualan & Laba**: Filter rentang tanggal, perhitungan omset kotor, estimasi Harga Pokok Penjualan (HPP / Modal), margin keuntungan, dan laba bersih.
- **Laporan Valuasi Stok**: Menghitung total nilai modal aset yang tertanam di toko dan estimasi potensi omset jika seluruh busana terjual.
- **Siap Cetak (*Print-Ready*)**: Tampilan nota kasir dan laporan sudah dioptimalkan dengan CSS media print.

---

## 🏛️ Arsitektur & Relasi Database

Aplikasi ini menggunakan relasi Eloquent yang terstruktur:

```
[Categories] 1 ────< N [Products]
                           │
             ┌─────────────┴─────────────┐
             ▼                           ▼
    [PurchaseItems] N            [SaleItems] N
             │                           │
             ▼ 1                         ▼ 1
       [Purchases] N                 [Sales] N ◄──── 1 [Customers] (nullable)
             │   │                       │
             │   └──────┐         ┌──────┘
             ▼ 1        ▼ 1       ▼ 1
        [Suppliers]    [Users (Admin/Staff)]
```

- **`users`**: `id`, `name`, `email`, `password`, `role` (`admin`/`staff`).
- **`customers`**: `id`, `name`, `phone`, `email`, `address`, `timestamps`.
- **`categories`**: `id`, `name`, `slug`, `description`.
- **`suppliers`**: `id`, `name`, `contact_person`, `phone`, `email`, `address`.
- **`products`**: `id`, `category_id`, `name`, `sku` (unik), `purchase_price`, `selling_price`, `stock`, `minimum_stock`, `image`, `description`.
- **`purchases`** & **`purchase_items`**: Faktur restock masuk dari supplier ke inventory toko.
- **`sales`** & **`sale_items`**: Faktur kasir penjualan pakaian (`customer_id` nullable, `customer_name`, `total_amount`, dll).

---

## 🚀 Panduan Instalasi & Menjalankan Aplikasi

### 1. Prasyarat Sistem
- **PHP** versi 8.1 atau lebih tinggi (ekstensi `pdo_sqlite`, `curl`, `mbstring`, `openssl`, `fileinfo` aktif).
- **Composer** versi 2.x.

### 2. Langkah Pemasangan

1. **Buka Terminal / PowerShell** dan arahkan ke direktori project:
   ```bash
   cd "c:\safira\web toko pakaian\Blush_Boutique_Web"
   ```

2. **Pasang Dependensi Composer** (jika belum terpasang):
   ```bash
   composer install
   ```

3. **Konfigurasi Environment (`.env`)**:
   Pastikan konfigurasi database di file `.env` sudah sesuai (secara bawaan menggunakan SQLite yang siap pakai tanpa konfigurasi tambahan):
   ```ini
   APP_NAME="BlushBoutique"
   APP_URL=http://localhost:8000
   DB_CONNECTION=sqlite
   ```

4. **Jalankan Migrasi & Data Awal (Seeder)**:
   Perintah ini akan membuat seluruh tabel dan mengisi data awal akun demo, 5 kategori busana, 3 supplier, serta 10 koleksi pakaian awal:
   ```bash
   php artisan migrate:fresh --seed
   ```

5. **Hubungkan Symlink Penyimpanan Foto**:
   ```bash
   php artisan storage:link
   ```

6. **Jalankan Server Lokal Laravel**:
   ```bash
   php artisan serve --host=0.0.0.0 --port=8000
   ```

Aplikasi sekarang sudah berjalan dan dapat diakses melalui browser Anda!

---

## 🌐 Tautan & Kredensial Akun Pengujian (Demo)

Buka browser dan kunjungi alamat berikut:

| Akses Halaman | Tautan URL | Keterangan |
| :--- | :--- | :--- |
| **Etalase Publik** | [http://localhost:8000](http://localhost:8000) atau [http://127.0.0.1:8000](http://127.0.0.1:8000) | Dapat diakses pengunjung tanpa perlu login. |
| **Portal Login** | [http://localhost:8000/login](http://localhost:8000/login) atau [http://127.0.0.1:8000/login](http://127.0.0.1:8000/login) | Dilengkapi tombol 1-Klik Isi Kredensial Demo. |

### 🔑 Akun Demo:

1. **Admin / Pemilik Butik**:
   - **Email:** `admin@blushboutique.com`
   - **Password:** `password123`
   - *Hak Akses:* Dashboard Lengkap, Master Produk CRUD + Upload Foto, Master Kategori, Master Supplier, Laporan Penjualan & Laba, Laporan Valuasi Stok.

2. **Staff Kasir**:
   - **Email:** `staff@blushboutique.com`
   - **Password:** `password123`
   - *Hak Akses:* Transaksi Kasir Penjualan Baru, Transaksi Restock Pakaian Masuk, dan Melihat Daftar Stok Busana.

---

## 🧪 Pengujian Otomatis (*Automated Feature Testing*)

Proyek ini telah dilengkapi dengan unit & feature testing menyeluruh menggunakan PHPUnit:

```bash
php artisan test
```

### Hasil Pengujian:
```text
   PASS  Tests\Unit\ExampleTest
  ✓ that true is true

   PASS  Tests\Feature\BlushBoutiqueTest
  ✓ public catalog accessible without login
  ✓ public product detail page
  ✓ admin can login and access dashboard
  ✓ staff cannot access categories or reports (Security 403)
  ✓ sale transaction decrements stock and rejects insufficient stock
  ✓ purchase transaction increments stock
  ✓ admin can upload product with image

   PASS  Tests\Feature\CustomerTest
  ✓ authenticated user can view customers list
  ✓ can create new customer
  ✓ can update customer
  ✓ can delete customer
  ✓ sale transaction with registered customer
  ✓ sale transaction with walk in customer

   PASS  Tests\Feature\ExampleTest
  ✓ the application returns a successful response

  Tests:    15 passed (60 assertions)
```

---

## 🎨 Palet Warna Desain (Aesthetic Soft Pink)

- **Blush Rose Primary**: `#F27893` / `#E54D71`
- **Pastel Pink Background**: `#FDF7F8` / `#FDF2F4`
- **Soft Cream & Peach Accent**: `#FFF9F5` / `#FFF0F3`
- **Text & Luxury Slate**: `#372A31` / `#4A3B43`
- **Typography**: `Outfit` (Modern Clean Sans) & `Playfair Display` (Luxury Boutique Serif)

---

## 📄 Lisensi

Aplikasi ini dikembangkan untuk keperluan operasional **BlushBoutique**. Hak cipta &copy; 2026 BlushBoutique. All rights reserved.
