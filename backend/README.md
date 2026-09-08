# Backend Track - EMS REST API (Laravel)

## Tujuan
Membangun REST API CRUD untuk resource `Employee`, lengkap dengan validasi request, query filtering, pagination, dan endpoint prediksi risiko attrition.

## Tech Stack
- Laravel 11.x (PHP 8.5.10)
- SQLite (sudah terkonfigurasi *out-of-the-box*)
- PHPUnit untuk automated test

## Instalasi & Menjalankan Proyek
```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan serve
```
Server API backend aktif di `http://localhost:8000`.

## Status Pengerjaan
Seluruh TODO pada `app/Http/Controllers/EmployeeController.php` telah diselesaikan. Ringkasan implementasi:

### 1. Validasi Request `store()` & `update()`
- Menambahkan `$request->validate([...])` pada kedua method.
- Field `name` wajib diisi pada `store()`; request tanpa `name` otomatis ditolak dengan **HTTP 422**.
- Field `department` divalidasi dengan rule `in:Engineering,Sales,HR,Finance,Marketing` sesuai skema data di PRD Section 5.4.
- Field numerik (`satisfaction_score`, `last_evaluation`) dibatasi rentang 0–1 sesuai skema.
- Hasil validasi (`$validated`) digunakan langsung pada `Employee::create()` / `$employee->update()`, bukan `$request->all()`, untuk mencegah field yang tidak divalidasi ikut tersimpan ke database.

### 2. Query Filtering & Pagination `index()`
- `?department=` menyaring karyawan berdasarkan kolom `department`.
- `?risk=` menyaring karyawan berdasarkan hasil kalkulasi risiko attrition (`low`/`medium`/`high`), divalidasi dengan rule `in:low,medium,high`.
- Pagination tetap dipertahankan (10 data per halaman).

### 3. Logika Risiko `attritionRisk()`
- Endpoint `GET /api/employees/{id}/attrition-risk` menggunakan simulasi rule-based (belum terhubung ke modul ML di `http://localhost:5000/predict`), sesuai izin PRD BE-4 yang membolehkan nilai simulasi selama integrasi ML belum tersedia.
- Aturan: semakin rendah `satisfaction_score` dan semakin pendek `years_at_company`, semakin tinggi probabilitas attrition.
- Logika kalkulasi dipisah ke method privat `calculateAttritionRisk()` agar dapat dipakai ulang oleh filter `?risk=` di `index()`, sehingga hasil keduanya selalu konsisten.

## Keputusan Teknis
- **Validasi department dibatasi ke 5 nilai tetap** (`Engineering, Sales, HR, Finance, Marketing`) mengikuti skema data resmi di PRD Section 5.4, bukan string bebas.
- **Filter `?risk=` menghitung ulang risiko secara real-time** menggunakan `calculateAttritionRisk()`, bukan hanya membaca kolom `attrition_risk` di database. Ini menghindari data stale bila kolom tersebut belum pernah disinkronkan.
- **Endpoint `attrition-risk` tidak melakukan HTTP call ke modul ML** - mengikuti opsi simulasi yang diizinkan PRD, karena integrasi ML belum tersedia pada saat pengerjaan.

## Kendala yang Dihadapi
- Starter code awal memiliki bug mass-assignment (`Employee::create($request->all())` / `$employee->update($request->all())`) yang membuat validasi tidak berefek, kemudia saya perbaiki dengan menggunakan hasil `$request->validate()`.
- Ditemukan potensi inkonsistensi antara kolom `attrition_risk` di database dan hasil kalkulasi real-time endpoint `attrition-risk`, jika kolom tersebut tidak pernah disinkronkan maka diselesaikan dengan memusatkan logika kalkulasi pada satu method yang dipakai bersama.

## Menjalankan Automated Test
```bash
php artisan test
# atau
php artisan test tests/Feature/EmployeeApiTest.php
```

**Hasil Akhir:** seluruh **10 test PASS**.