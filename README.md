# Backend Project

## Deskripsi
API REST untuk aplikasi berbasis web dengan fitur login, pendaftaran pasien, dan kunjungan.

## Endpoint
- **Login**: `POST /api/login`
- **Logout**: `GET /api/logout`
- **Pendaftaran Pasien**: `POST /api/patients`
- **List Data Pasien**: `GET /api/patients`
- **Buat Kunjungan**: `POST /api/visits`

## Setup
1. **Instalasi**:
   - `composer install`
   - `cp .env.example .env`
   - Atur database di `.env`
   - `php artisan migrate`
   - `php artisan db:seed`

2. **Pengujian**:
   - Import koleksi Postman dari file `Medisin API.postman_collection.json`.
