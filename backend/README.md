# Backend Track — EMS REST API (Laravel)

## Tujuan
Membangun REST API CRUD untuk resource `Employee`, lengkap dengan validasi request, query filtering, pagination, dan endpoint prediksi risiko attrition.

## Tech Stack
- Laravel 11.x (PHP 8.2+)
- SQLite (sudah terkonfigurasi *out-of-the-box*)
- PHPUnit untuk automated test

## Instalasi & Menjalankan Proyek
Project ini sudah dikonfigurasi secara utuh dan siap pakai:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan serve
```
Server API backend akan langsung aktif di `http://localhost:8000`.

## Tugas Anda (TODO)
Selesaikan tugas yang ditandai dengan komentar `// TODO(intern)` pada file:
👉 **`app/Http/Controllers/EmployeeController.php`**

1. **Validasi Request `store()`**:
   - Implementasikan `$request->validate([...])` pada method `store()`.
   - Field `name` wajib diisi. Request yang tidak lengkap harus otomatis ditolak dengan **HTTP 422 (Unprocessable Entity)**.
2. **Validasi Request `update()`**:
   - Implementasikan validasi input saat update data karyawan.
3. **Query Filtering & Pagination `index()`**:
   - Support query parameter `?department=` untuk menyaring karyawan berdasarkan departemen.
   - Support query parameter `?risk=` untuk menyaring berdasarkan tingkat risiko (`low`, `medium`, `high`).
4. **Logika Risiko `attritionRisk()`**:
   - Tingkatkan kalkulasi estimasi risiko attrition karyawan (bisa berbasis rule skor kepuasan & masa kerja, atau menghubungkannya via HTTP ke modul Machine Learning di `http://localhost:5000/predict`).

## Menjalankan Automated Test (TDD)
```bash
php artisan test
# atau
./vendor/bin/phpunit tests/Feature/EmployeeApiTest.php
```

> **Catatan Pengujian Awal:**
> Saat pertama kali menjalankan test, Anda akan melihat status **8 PASSED dan 2 FAILED**:
> - `test_cannot_create_employee_without_required_fields` (karena validasi 422 belum diimplementasikan)
> - `test_can_filter_employees_by_department` (karena filter query belum diimplementasikan)
>
> **Kriteria Kelulusan:** Selesaikan kedua tugas di atas hingga seluruh 10 test berstatus **100% PASS**.

## Kriteria Penilaian
Lihat Section 5.2 dan Section 8 di `../PRD.md`.
