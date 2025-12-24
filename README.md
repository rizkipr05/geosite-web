# Geo Explore - Probolinggo Geopark Information System

Geo Explore adalah platform berbasis web yang menyediakan informasi komprehensif mengenai destinasi geowisata (Geopark) di Kabupaten Probolinggo. Aplikasi ini dirancang untuk memudahkan wisatawan dalam menemukan, mempelajari, dan menavigasi lokasi-lokasi geosite yang tersebar di wilayah tersebut.

Platform ini tidak hanya menyediakan informasi statis, tetapi juga dilengkapi dengan fitur **Admin Panel** yang kuat untuk mengelola data, termasuk integrasi otomatis dengan **OpenStreetMap (OSM)** dan **Wikidata** untuk memperkaya konten (gambar dan deskripsi) secara real-time.

---

## 🚀 Fitur Utama

### 🌍 Guest (Pengunjung)
- **Interactive Map**: Peta interaktif berbasis LeafletJS yang menampilkan sebaran geosite dengan marker kustom.
- **Advanced Search & Filter**: Pencarian destinasi berdasarkan nama, kategori (Pantai, Air Terjun, Perbukitan, dll), dan wilayah.
- **Detail Geosite**: Halaman detail yang menyajikan deskripsi mendalam, galeri foto, video, informasi tiket, jam operasional, dan navigasi langsung ke Google Maps.
- **Modern UI/UX**: Desain antarmuka premium dengan nuansa gelap (glassmorphism), responsif untuk mobile dan desktop.
- **Optimized Performance**: Penggunaan ikon SVG inline untuk kecepatan interaksi yang lebih baik.

### 🛡️ Admin (Pengelola)
- **Dashboard Analitik**: Ringkasan statistik jumlah geosite, kategori, dan aktivitas.
- **Manajemen Geosite (CRUD)**: Tambah, edit, dan hapus data geosite dengan mudah.
- **Import Otomatis dari OSM**: Fitur unggulan untuk mengimpor data wisata secara massal dari OpenStreetMap.
  - **Auto-Fetch Images**: Mengambil gambar berkualitas tinggi secara otomatis dari **Wikidata**.
  - **Auto-Address Parsing**: Melengkapi alamat dan deskripsi secara otomatis.
  - **Duplicate Handling**: Memperbarui data yang sudah ada tanpa duplikasi.
- **Manajemen Kategori**: Pengelompokan destinasi wisata.
- **Manajemen Media**: Upload dan kelola galeri foto/video untuk setiap geosite.
- **Secure Authentication**: Sistem login aman untuk admin.

---

## 🛠️ Tech Stack

Aplikasi ini dibangun menggunakan teknologi web modern untuk memastikan performa, keamanan, dan kemudahan pengembangan.

### Backend
- **Framework**: [Laravel](https://laravel.com) (PHP Framework)
  - Arsitektur MVC yang kokoh.
  - Eloquent ORM untuk manajemen database.
  - RESTful API untuk komunikasi data internal.
- **Database**: MySQL

### Frontend
- **Templating**: Blade Template Engine
- **Styling**: [Tailwind CSS](https://tailwindcss.com) (Utility-first CSS framework via CDN/Build)
- **Icons**: Heroicons (Inline SVG)
- **Map Library**: [Leaflet.js](https://leafletjs.com) (Open-source JavaScript library for mobile-friendly interactive maps)
- **Data Source**: OpenStreetMap (via Overpass API) & Wikidata API

### External Services & APIs
- **Overpass API**: Digunakan untuk mengambil data geospasial (titik koordinat, nama, metadata) dari OpenStreetMap.
- **Wikidata API**: Digunakan untuk mengambil gambar dan metadata tambahan yang terhubung dengan entitas OSM.

---

## 📂 Struktur Folder Utama

Berikut adalah struktur direktori penting dalam proyek ini:

```
geo-web/
├── app/
│   ├── Http/Controllers/       # Logika aplikasi (Admin & Guest)
│   │   ├── Api/Admin/ImportController.php  # Logika Import OSM & Wikidata
│   ├── Models/                 # Model database (Geosite, Media, Category)
│   ├── Services/               # Service khusus (OverpassService)
├── resources/
│   ├── views/
│   │   ├── admin/              # Tampilan halaman admin (Dashboard, Login, dll)
│   │   ├── guest/              # Tampilan halaman pengunjung (Home, Explore, About)
│   │   └── layouts/            # Template utama blade
├── routes/
│   ├── web.php                 # Rute halaman web
│   ├── api.php                 # Rute API (Login, Import)
├── database/                   # Migrasi dan Seeder
```

---

## ⚡ Instalasi & Jalankan Lokal

Ikuti langkah-langkah berikut untuk menjalankan proyek di komputer lokal Anda:

### Prasyarat
- PHP >= 8.1
- Composer
- MySQL

### Langkah-langkah

1. **Clone Repository**
   ```bash
   git clone https://github.com/rizkipr05/geosite-web.git
   cd geo-web
   ```

2. **Install Dependencies**
   ```bash
   composer install
   ```

3. **Konfigurasi Environment**
   Salin file `.env.example` menjadi `.env` dan sesuaikan dengan konfigurasi database Anda.
   ```bash
   cp .env.example .env
   ```
   Atur DB_DATABASE, DB_USERNAME, dan DB_PASSWORD di file `.env`.

4. **Generate Key & Migrasi Database**
   ```bash
   php artisan key:generate
   php artisan migrate --seed
   ```
   *Note: Gunakan seed untuk membuat akun admin default.*

5. **Jalankan Server**
   ```bash
   php artisan serve
   ```

6. **Akses Aplikasi**
   - Halaman Tamu: `http://127.0.0.1:8000`
   - Login Admin: `http://127.0.0.1:8000/admin/login`

---

## 📝 Lisensi

Proyek ini adalah perangkat lunak open-source di bawah lisensi [MIT](https://opensource.org/licenses/MIT).
