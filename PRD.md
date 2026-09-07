# Product Requirements Document (PRD)

## Intern Technical Skill Validation Project — "EMS: Employee Management & Attrition Insight System"

|             |                                       |
| ----------- | ------------------------------------- |
| **Dokumen** | PRD - Intern Skill Assessment Project |
| **Pemilik** | Engineering / People Development Team |
| **Status**  | Draft untuk review                    |

---

## 1. Non-Tujuan (Out of Scope)

- Proyek ini **bukan** aplikasi production-ready; fokus pada validasi skill dasar-menengah.
- Tidak mencakup deployment ke server/cloud (opsional sebagai nilai tambah, bukan syarat wajib).
- Tidak mencakup automated test untuk frontend (opsional/nice-to-have, lihat bagian 9).

## 2. Target Pengguna

| Peran                 | Kebutuhan                                                                          |
| --------------------- | ---------------------------------------------------------------------------------- |
| **Intern**            | Mendapatkan instruksi jelas, starter code, dan kriteria sukses yang terukur        |
| **Mentor / Reviewer** | Mendapatkan rubrik dan test otomatis untuk mempercepat & mengobjektifkan penilaian |
| **People Dev / HR**   | Mendapatkan laporan hasil (pass/fail per kriteria) untuk keputusan lanjutan magang |

## 3. Studi Kasus (Case Study)

**"EMS – Employee Management & Attrition Insight System"**

Sebuah sistem internal sederhana untuk mengelola data karyawan, dengan tambahan modul prediksi risiko resign (_attrition_) berbasis Machine Learning.

- **Backend** menyediakan REST API CRUD data karyawan + endpoint yang mengonsumsi hasil model ML (prediksi risiko resign per karyawan).
- **Frontend** menampilkan dashboard direktori karyawan (list, search, filter, tambah/edit/hapus) serta indikator visual risiko attrition per karyawan.
- **Machine Learning** membangun model klasifikasi biner (resign / tidak resign) dari dataset historis karyawan, lalu diekspos sebagai fungsi/skema prediksi yang bisa diintegrasikan ke backend.

Ketiga track dapat dikerjakan **secara independen** — intern tidak wajib menyelesaikan ketiganya, sesuai dengan jalur kompetensi yang sedang dinilai (frontend saja, backend saja, atau ML saja).

## 4. Struktur Folder Proyek

```
intern-skill-assessment/
├── PRD.md                     # dokumen ini
├── README.md                  # panduan umum
├── frontend/                  # Track 1: UI/UX
│   ├── README.md              # instruksi & tugas
│   ├── package.json
│   ├── tailwind.config.js
│   ├── vite.config.js
│   ├── index.html
│   └── src/
│       ├── main.js
│       ├── App.vue
│       ├── style.css
│       ├── mock/
│       │   └── employees.js         # data mock bawaan untuk standalone mode
│       ├── services/
│       │   └── api.js               # adapter API / mock toggle
│       └── components/
│           ├── EmployeeList.vue     # TODO oleh intern
│           ├── EmployeeForm.vue     # TODO oleh intern
│           └── AttritionBadge.vue   # TODO oleh intern
├── backend/                   # Track 2: Backend (Laravel 11, SQLite)
│   ├── README.md
│   ├── composer.json
│   ├── artisan
│   ├── routes/api.php
│   ├── app/
│   │   ├── Models/Employee.php
│   │   └── Http/Controllers/EmployeeController.php   # TODO oleh intern
│   ├── database/
│   │   ├── database.sqlite
│   │   ├── migrations/2026_09_07_000000_create_employees_table.php
│   │   └── seeders/EmployeeSeeder.php
│   └── tests/Feature/EmployeeApiTest.php   # automated grading test
└── machine-learning/          # Track 3: Machine Learning
    ├── README.md
    ├── requirements.txt
    ├── data/employee_attrition.csv
    ├── src/
    │   ├── data_preprocessing.py   # TODO oleh intern
    │   ├── train_model.py          # TODO oleh intern
    │   ├── predict.py              # TODO oleh intern
    │   └── serve.py                # mini HTTP server inferensi (port 5000)
    └── tests/test_model.py         # automated grading test
```

## 5. Functional Requirements per Track

### 5.1 Frontend (UI/UX)

| ID   | Requirement                                                                                                                       |
| ---- | --------------------------------------------------------------------------------------------------------------------------------- |
| FE-1 | Menampilkan daftar karyawan dalam bentuk tabel/grid (nama, departemen, jabatan, lama bekerja, status risiko resign)               |
| FE-2 | Fitur pencarian & filter (per departemen / status risiko)                                                                         |
| FE-3 | Form tambah & edit karyawan dengan validasi input di sisi client                                                                  |
| FE-4 | Konsumsi REST API dari backend (axios/fetch) — bisa memakai mock data jika backend belum tersedia                                 |
| FE-5 | Menampilkan badge/indikator visual (warna) untuk level risiko attrition: Low/Medium/High                                          |
| FE-6 | Responsive design (mobile & desktop) menggunakan Tailwind CSS **atau** Bootstrap                                                  |
| FE-7 | Implementasi memakai **Vue.js 3 (Composition API)** _atau_ **Laravel Blade Component** — pilih salah satu sesuai instruksi mentor |
| FE-8 | State management dasar (reactive state / props-emit antar komponen)                                                               |

### 5.2 Backend (Laravel)

| ID   | Requirement                                                                                                                                                                           |
| ---- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| BE-1 | REST API CRUD penuh untuk resource `Employee` (`GET/POST/PUT/DELETE /api/employees`)                                                                                                  |
| BE-2 | Validasi request (Form Request / validasi inline) dengan pesan error yang jelas                                                                                                       |
| BE-3 | Migration & Model `Employee` sesuai skema data (lihat 7.4)                                                                                                                            |
| BE-4 | Endpoint `GET /api/employees/{id}/attrition-risk` yang mengembalikan skor/level risiko (boleh memanggil model ML via file/HTTP, atau nilai simulasi jika integrasi ML belum tersedia) |
| BE-5 | Pagination & filtering pada endpoint list (`?department=`, `?risk=`)                                                                                                                  |
| BE-6 | HTTP status code & format response JSON konsisten (mengikuti konvensi REST)                                                                                                           |
| BE-7 | Unit/Feature test lulus (lihat Section 9)                                                                                                                                             |
| BE-8 | (Opsional nilai tambah) Autentikasi API menggunakan Laravel Sanctum                                                                                                                   |

### 5.3 Machine Learning (Python)

| ID   | Requirement                                                                                                                                                      |
| ---- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| ML-1 | Melakukan data cleaning & preprocessing (handling missing values, encoding kategorikal, scaling) pada `employee_attrition.csv`                                   |
| ML-2 | Split data train/test (mis. 80/20) dengan `random_state` tetap agar reproducible                                                                                 |
| ML-3 | Melatih minimal 1 model klasifikasi (Logistic Regression / Decision Tree / Random Forest, dsb.) untuk memprediksi kolom target `Attrition`                       |
| ML-4 | Evaluasi model menggunakan minimal 2 metrik (accuracy, precision, recall, atau F1-score)                                                                         |
| ML-5 | Menyediakan fungsi `predict(employee_dict) -> {"risk_level": str, "probability": float}` yang bisa dipanggil ulang (importable, bukan hanya script sekali jalan) |
| ML-6 | Model tersimpan (serialize, mis. `joblib`/`pickle`) agar tidak perlu retraining setiap pemanggilan                                                               |
| ML-7 | Automated test (pytest) lulus (lihat Section 9)                                                                                                                  |

### 5.4 Skema Data `Employee` (acuan bersama lintas track)

```
id                 : integer, auto increment
name               : string
department         : string   (Engineering, Sales, HR, Finance, Marketing)
position           : string
years_at_company   : integer
monthly_salary     : integer
satisfaction_score : float (0-1)
last_evaluation    : float (0-1)
attrition          : boolean  (target label, hanya relevan untuk dataset training ML)
attrition_risk     : enum('low','medium','high')  (hasil prediksi, ditampilkan di frontend)
```

## 6. Non-Functional Requirements

- **Konsistensi kode**: mengikuti konvensi framework masing-masing (PSR-12 untuk PHP, PEP8 untuk Python, Vue Style Guide untuk JS).
- **Dokumentasi**: setiap track wajib memiliki README singkat berisi cara instalasi & menjalankan.
- **Reproducibility**: ML wajib deterministic (fixed random seed) agar hasil review konsisten.
- **Waktu pengerjaan**: disarankan 3–5 hari kerja per track (disesuaikan kebijakan program magang).

## 7. Testing Requirements (Automated Grading)

| Track            | Framework                                                                        | Lokasi                                      | Yang divalidasi                                                                |
| ---------------- | -------------------------------------------------------------------------------- | ------------------------------------------- | ------------------------------------------------------------------------------ |
| Backend          | PHPUnit                                                                          | `backend/tests/Feature/EmployeeApiTest.php` | CRUD endpoint, status code, struktur JSON, validasi input                      |
| Machine Learning | Pytest                                                                           | `machine-learning/tests/test_model.py`      | Bentuk output preprocessing, akurasi minimum model, kontrak fungsi `predict()` |
| Frontend         | Manual review + checklist (opsional: Vitest/Vue Test Utils sebagai nilai tambah) | —                                           | Kesesuaian UI dengan requirement, responsivitas, kualitas komponen             |

Test disediakan **sebagai starter/kerangka** dan sengaja akan **gagal (fail)** sebelum intern mengimplementasikan kode — ini menjadi acuan objektif "selesai" ketika seluruh test berubah menjadi **pass**.

## 8. Rubrik Penilaian (Ringkasan)

| Kriteria                                                        | Bobot |
| --------------------------------------------------------------- | ----- |
| Fungsionalitas (semua requirement terpenuhi)                    | 40%   |
| Automated test pass (backend/ML)                                | 25%   |
| Kualitas & struktur kode (readability, konvensi, komponenisasi) | 20%   |
| UI/UX & responsivitas (khusus frontend)                         | 10%   |
| Dokumentasi (README, komentar kode)                             | 5%    |

Skala nilai per kriteria: 1 (kurang) – 5 (sangat baik). Total skor menjadi dasar rekomendasi lanjut/tidaknya program magang.

## 9. Deliverables

1. Repository/folder proyek berisi kode hasil pengerjaan intern (fork dari starter project ini).
2. Laporan singkat (README update) berisi: cara menjalankan, keputusan teknis, kendala yang dihadapi.
3. Hasil automated test (screenshot atau output CLI) menunjukkan status pass.

## 10. Milestone / Timeline (contoh)

| Aktivitas                                       |
| ----------------------------------------------- |
| Onboarding, penjelasan PRD & starter project    |
| Implementasi sesuai track                       |
| Self-testing & perbaikan (memastikan automated) |
| Submission + review bersama mentor              |

## 11. Risiko & Mitigasi

| Risiko                                        | Mitigasi                                                                                 |
| --------------------------------------------- | ---------------------------------------------------------------------------------------- |
| Intern belum familiar dengan salah satu stack | Sediakan link learning resource di README masing-masing folder                           |
| Test environment berbeda antar laptop intern  | Sertakan `requirements.txt` / `composer.json` / `package.json` dengan versi terkunci     |
| Scope terlalu luas untuk waktu magang         | Track dapat dikerjakan parsial sesuai requirement prioritas (ditandai wajib vs opsional) |

## 12. Lampiran

- Lihat `frontend/README.md`, `backend/README.md`, `machine-learning/README.md` untuk instruksi teknis detail per track.
