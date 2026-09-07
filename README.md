# Intern Skill Assessment Project — EMS: Employee Management & Attrition Insight System

Proyek ini digunakan untuk memvalidasi skill teknis anak magang (*intern*) di 3 jalur kompetensi secara objektif dan terstandarisasi:

- [`frontend/`](file:///home/karimdevalio/Downloads/intern-skill-assessment/frontend) — UI/UX (Vue.js 3 Composition API + Tailwind CSS + Vite)
- [`backend/`](file:///home/karimdevalio/Downloads/intern-skill-assessment/backend) — Backend REST API (Laravel 11 + SQLite + PHPUnit)
- [`machine-learning/`](file:///home/karimdevalio/Downloads/intern-skill-assessment/machine-learning) — Machine Learning (Python + scikit-learn + pytest)

Lihat [`PRD.md`](file:///home/karimdevalio/Downloads/intern-skill-assessment/PRD.md) untuk product requirement lengkap, skema data bersama, dan rubrik penilaian.

---

## Karakteristik Proyek: "Utuh Lingkungannya, Rumpang Logikanya"

Setiap track bersifat **100% mandiri (independen)** dan sudah dikonfigurasi secara *turnkey*. Seluruh infrastruktur (database lokal, mock data, test runner, script server) sudah dapat dijalankan langsung sejak awal:
- **Frontend** dapat langsung dijalankan (`npm run dev`) tanpa perlu menyalakan backend (menggunakan data mock bawaan).
- **Backend** dapat langsung dimigrate dan di-seed (`php artisan migrate:fresh --seed`) serta diuji (`php artisan test`) menggunakan SQLite bawaan.
- **Machine Learning** dapat langsung diuji (`pytest tests/test_model.py -v`) dan dijalankan servernya (`python src/serve.py`).

Bagian yang menjadi tugas anak magang sengaja dibuat **rumpang (fill-in-the-blank)** dan ditandai dengan komentar `// TODO(intern)` atau `# TODO(intern)`.

---

## Quick Start per Track

### 1. Frontend Track
```bash
cd frontend
npm install
npm run dev        # Buka http://localhost:5173 di browser
```

### 2. Backend Track
```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan serve  # Server REST API aktif di http://localhost:8000
php artisan test   # Jalankan automated grading test
```

### 3. Machine Learning Track
```bash
cd machine-learning
python -m venv venv
source venv/bin/activate   # Windows: venv\Scripts\activate
pip install -r requirements.txt
python src/predict.py      # Test inferensi CLI
pytest tests/test_model.py -v  # Jalankan automated grading test
```
