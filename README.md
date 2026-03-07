# E-Canteen

Sistem Pre-Order Kantin Digital berbasis web, dibangun dengan Laravel 12.

## Fitur

- **3 Role**: Super Admin (sekolah), Admin/Penjual, User (siswa/guru)
- **Pre-Order Menu**: Pilih waktu pengambilan (Istirahat 1 / Istirahat 2)
- **Saldo Virtual**: Top-up via transfer bank (dikonfirmasi Super Admin), tarik saldo untuk penjual
- **Dashboard Masing-masing Role**: Statistik, antrian pesanan, laporan harian
- **Jurusan**: RPL, DKV, AK, MP, BR — NIS 5 digit
- **UI Responsif**: Soft-pink theme, collapsible sidebar (Alpine.js), Phosphor Icons

## Tech Stack

- Laravel 12 + PHP 8.3
- MySQL
- Tailwind CSS 3 + Vite 5
- Alpine.js
- Phosphor Icons

## Instalasi

```bash
git clone https://github.com/zakysef/E-Canteen.git
cd E-Canteen

composer install
npm install

cp .env.example .env
php artisan key:generate

# Konfigurasi database di .env, lalu:
php artisan migrate --seed

npm run build
php artisan storage:link
```

## Akun Default (Seeder)

| Role        | Email                    | Password |
|-------------|--------------------------|----------|
| Super Admin | superadmin@ecanteen.test | password |
| Admin       | admin@ecanteen.test      | password |
| User        | siswa@ecanteen.test      | password |
