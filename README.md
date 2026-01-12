# 🏥 E-Resep — Sistem Peresepan Obat Digital

Aplikasi web peresepan obat digital berbasis **Laravel 12** untuk mendukung proses pelayanan medis, mulai dari pembuatan resep oleh dokter hingga pembayaran di apotek.

---

## 🚀 Instalasi & Setup

### 📌 Persyaratan Sistem
Pastikan environment sudah memenuhi kebutuhan berikut:

- PHP **8.2+**
- Composer
- Node.js & NPM
- MySQL **8.0+**
- Git

---

### 1️⃣ Clone Repository
```bash
git clone https://github.com/hidayatrepo/e-resep.git
cd e-resep
2️⃣ Install Dependency

composer install
npm install
3️⃣ Konfigurasi Environment
cp .env.example .env
php artisan key:generate
Sesuaikan pengaturan database dan credential di file .env:

APP_NAME=E-Resep
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=eresep_db
DB_USERNAME=root
DB_PASSWORD=

4️⃣ Setup Database
Jalankan perintah berikut:
mysql -u root -p -e "CREATE DATABASE eresep_db;"
php artisan migrate
php artisan db:seed

5️⃣ Setup Storage
php artisan storage:link

6️⃣ Jalankan Aplikasi
php artisan serve
npm run dev
Akses aplikasi melalui browser di:

http://127.0.0.1:8000

____________________________

🔐 Akun Default
Gunakan akun ini untuk login setelah instalasi:

Dokter
Username: dokter
Password: password123

Apoteker
Username: apoteker
Password: password123

Administrator
Username: admin
Password: admin123