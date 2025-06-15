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

## 🛠️ Cara Menjalankan

## 💻 Menjalankan Laravel Secara Lokal (Tanpa Docker)

### ✅ 1. Pastikan Prasyarat Terinstal
- PHP >= 8.2
- Composer
- MySQL / MariaDB
- Ekstensi PHP: `pdo`, `mbstring`, `bcmath`, `zip`, `sockets`, `pcntl`
- Node.js (opsional untuk pengembangan dengan Vite)

### 📦 2. Clone & Install Dependency

```bash
git clone https://github.com/TendyWijaya123/BE-Kurikulum.git
cd kurikulum-backend
composer install
```

### ⚙️ 3. Setup File `.env` serta konfigurasi firebase dan Gemini API

```bash
cp .env.example .env
```

Edit `.env` sesuai konfigurasi lokal, termasuk:

- Database (`DB_*`)
- Firebase (`FIREBASE_CREDENTIALS`)
- Gemini API (`GEMINI_API_KEY`)

Salin konfigurasi `.env` sesuai kebutuhan. Contoh isi:

```env
# Aplikasi
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kurikulum
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=youremail@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=youremail@gmail.com
MAIL_FROM_NAME="Kurikulum App"

# Firebase
FIREBASE_PROJECT_ID=Nama_Project
FIREBASE_CREDENTIALS=app/firebase/firebase_credentials.json

# Gemini (Google Generative AI)
GEMINI_API_KEY=your_api_key_here

# JWT Auth
JWT_SECRET=

# Queue & Schedule
QUEUE_CONNECTION=database

# Session
SESSION_DRIVER=database
SESSION_DOMAIN=localhost

# Reverb / Broadcasting (Opsional, jika digunakan)
REVERB_HOST=localhost
REVERB_PORT=6001
REVERB_SCHEME=http

# Frontend Dev Server (Sanctum)
SANCTUM_STATEFUL_DOMAINS=localhost:5173

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
2. Buat project: `Nama_Project`
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
## 🔐 Konfigurasi JWT (JSON Web Token)

Autentikasi pengguna dilakukan dengan JWT agar komunikasi tetap aman dan tanpa sesi (stateless). Berikut langkah konfigurasi lengkap:

### Generate Secret Key
```bash
php artisan jwt:secret
```
Perintah ini akan menulis nilai `JWT_SECRET` otomatis ke dalam file `.env`.

### 4. Konfigurasi `.env`
Pastikan variabel berikut ada dan telah diisi:
```env
JWT_SECRET=isi_dari_artisan_jwt:secret
JWT_TTL=60
```

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

### 🌐 8. Akses Aplikasi

Buka di browser:

```
http://127.0.0.1:8000/api
```


## 🐳 Menjalankan via Docker (Recommendasi karena bisa menggunakan octane)

### 1. Dockerfile

```dockerfile
# Gunakan FrankenPHP sebagai base image
FROM dunglas/frankenphp

# Install PHP extensions yang diperlukan
RUN install-php-extensions \
  pcntl gd mbstring pdo pdo_mysql xml bcmath zip sockets

# Install dependencies tambahan
RUN apt-get update && apt-get install -y \
  git \
  unzip \
  curl \
  libpng-dev \
  libjpeg-dev \
  libfreetype6-dev \
  libonig-dev \
  libxml2-dev \
  zip \
  supervisor procps \
  && rm -rf /var/lib/apt/lists/*

# Set working directory
WORKDIR /app/backend

# Copy Composer dari official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy semua file project
COPY . .

# Install Laravel Octane
RUN composer require laravel/octane --no-interaction

# Install Octane dengan FrankenPHP server
RUN php artisan octane:install --server=frankenphp --no-interaction

RUN composer install --no-dev --optimize-autoloader

RUN php artisan key:generate

RUN php artisan storage:link

RUN chmod -R 775 storage bootstrap/cache && \
  chown -R www-data:www-data storage bootstrap/cache && \
  php artisan storage:link

RUN chmod +x artisan

# Copy konfigurasi Supervisor
COPY ./supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Expose port untuk Laravel Octane
EXPOSE 8000

# Jalankan Supervisor (agar Octane & Queue berjalan bersamaan)
ENTRYPOINT ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

```

### 2. Supervisor Config (`supervisord.conf`)

```ini
[supervisord]
nodaemon=true
user=root
logfile=/var/log/supervisor/supervisord.log
pidfile=/var/run/supervisord.pid
childlogdir=/var/log/supervisor

[unix_http_server]
file=/var/run/supervisor.sock
chmod=0700

[supervisorctl]
serverurl=unix:///var/run/supervisor.sock

[rpcinterface:supervisor]
supervisor.rpcinterface_factory = supervisor.rpcinterface:make_main_rpcinterface

[program:octane]
command=php artisan octane:start --server=frankenphp --host=0.0.0.0 --port=8000
autostart=true
autorestart=true
stderr_logfile=/var/log/supervisor/octane.err.log
stdout_logfile=/var/log/supervisor/octane.out.log

[program:queue]
command=php artisan queue:work --tries=3
autostart=true
autorestart=true
stderr_logfile=/var/log/supervisor/queue.err.log
stdout_logfile=/var/log/supervisor/queue.out.log

[program:schedule]
command=php artisan schedule:work
autostart=true
autorestart=true
stderr_logfile=/var/log/supervisor/schedule.err.log
stdout_logfile=/var/log/supervisor/schedule.out.log

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
- `app/Mail`: Tempat penyimpanan queue Mail
- `routes/api.php`: Seluruh endpoint REST API

---

## ✅ Checklist Produksi

- [ ] Ganti `APP_ENV=production`
- [ ] Nonaktifkan `APP_DEBUG`
- [ ] Tambahkan validasi & rate-limiting pada endpoint publik
- [ ] Monitor logs dari Supervisor (`/var/log/supervisor/*.log`)

---


