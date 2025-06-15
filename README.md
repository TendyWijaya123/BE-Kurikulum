# 🚀 Backend Aplikasi Penyusunan Kurikulum – Politeknik Negeri Bandung

Backend ini dibangun menggunakan **Laravel 11** dan ditujukan untuk menangani seluruh proses logika bisnis serta API untuk frontend aplikasi penyusunan kurikulum di Politeknik Negeri Bandung. Backend ini terintegrasi dengan Firebase untuk fitur chatting dan Google Gemini API untuk AI support.

---

## 📌 Teknologi yang Digunakan

- Laravel 11
- PHP >= 8.2
- Laravel Octane (FrankenPHP)
- Firebase SDK (Realtime Chat)
- Google Gemini API
- MySQL
- Docker + Supervisor

---

## ⚙️ Fitur Utama

- Queue (Job)
- Schedule (Penjadwalan)
- API untuk frontend React.js
- Chat real-time (Firebase)
- Integrasi AI via Gemini API
- Terintegrasi dengan sistem penyusunan kurikulum (konsideran → desain → konstruksi & pra-uji)

---

## 🛠️ Cara Menjalankan (Docker Recommended)

### 1. Clone Project & Setup `.env`

Salin konfigurasi `.env` sesuai kebutuhan. Contoh isi:

```env
[isi .env seperti yang sudah Anda berikan, potong untuk ringkas]
```

Pastikan variabel penting berikut telah disesuaikan:

- `DB_*`: Konfigurasi database
- `FIREBASE_CREDENTIALS`: Path file kredensial Firebase
- `GEMINI_API_KEY`: Kunci akses Gemini
- `FLASK_URL`: URL endpoint NLP Flask (jika digunakan)
- `QUEUE_CONNECTION`: Gunakan `database` untuk sistem antrian

---

## 🔐 Konfigurasi Firebase

### Langkah-langkah:
1. Buka [Firebase Console](https://console.firebase.google.com/)
2. Buat project: `penyusunankurikulum-7787c`
3. Masuk ke `Project Settings > Service Accounts`
4. Klik `Generate new private key`
5. Simpan file JSON ke: `app/firebase/firebase_credentials.json`
6. Pastikan path ini sesuai dengan `.env` di bagian:

```env
FIREBASE_CREDENTIALS=app/firebase/firebase_credentials.json
```

---

## 🤖 Konfigurasi Gemini API

1. Masuk ke Google Cloud Console.
2. Aktifkan Gemini API / Generative Language API.
3. Dapatkan API key dan masukkan ke `.env`:

```env
GEMINI_API_KEY=your_api_key_here
```

---

## 💻 Menjalankan Laravel Secara Lokal (Tanpa Docker)

### ✅ 1. Pastikan Prasyarat Terinstal
- PHP >= 8.2
- Composer
- MySQL / MariaDB
- Ekstensi PHP: `pdo`, `mbstring`, `bcmath`, `zip`, `sockets`, `pcntl`
- Node.js (opsional untuk pengembangan dengan Vite)

### 📦 2. Clone & Install Dependency

```bash
git clone https://github.com/nama-akun/kurikulum-backend.git
cd kurikulum-backend
composer install
```

### ⚙️ 3. Setup File `.env`

```bash
cp .env.example .env
```

Edit `.env` sesuai konfigurasi lokal, termasuk:

- Database (`DB_*`)
- Firebase (`FIREBASE_CREDENTIALS`)
- Gemini API (`GEMINI_API_KEY`)

### 🔐 4. Generate App Key

```bash
php artisan key:generate
```

### 🧪 5. Migrasi Database

```bash
php artisan migrate
# php artisan db:seed  # jika Anda memiliki seeder
```

### 🚀 6. Jalankan Queue dan Scheduler

**Terminal 1** (Queue Worker):
```bash
php artisan queue:work
```

**Terminal 2** (Scheduler):
```bash
php artisan schedule:work
```

> Pastikan `QUEUE_CONNECTION=database` di file `.env`

### ⚡ 7. Jalankan Server Laravel

**Tanpa Octane:**
```bash
php artisan serve
```

**Dengan Octane:**
```bash
php artisan octane:start --server=frankenphp --host=127.0.0.1 --port=8000
```

### 🌐 8. Akses Aplikasi

Buka di browser:

```
http://127.0.0.1:8000/api
```


## 🐳 Deployment via Docker

### 1. Dockerfile

```dockerfile
[isi Dockerfile seperti yang Anda berikan, potong untuk ringkas]
```

### 2. Supervisor Config (`supervisord.conf`)

```ini
[isi supervisord.conf seperti yang Anda berikan]
```

### 3. Build dan Jalankan Container

```bash
docker build -t kurikulum-backend .
docker run -d -p 8000:8000 --name kurikulum-backend kurikulum-backend
```

Akses API di: `http://localhost:8000/api`

---

## 🔄 Schedule dan Queue

Pastikan tabel queue (`jobs`) dan `failed_jobs` telah dibuat dengan:

```bash
php artisan queue:table
php artisan schedule:work
```

---

## 📂 Struktur Penting

- `app/Jobs`: Tempat penyimpanan queue jobs
- `app/Console/Kernel.php`: Penjadwalan otomatis (schedule)
- `routes/api.php`: Seluruh endpoint REST API
- `config/octane.php`: Konfigurasi Laravel Octane

---

## ✅ Checklist Produksi

- [ ] Ganti `APP_ENV=production`
- [ ] Nonaktifkan `APP_DEBUG`
- [ ] Tambahkan validasi & rate-limiting pada endpoint publik
- [ ] Monitor logs dari Supervisor (`/var/log/supervisor/*.log`)

---


