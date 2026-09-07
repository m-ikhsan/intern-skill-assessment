# Machine Learning Track — Employee Attrition Prediction (Python)

## Tujuan
Membangun model klasifikasi biner untuk memprediksi kemungkinan seorang karyawan resign
(`Attrition`) berdasarkan data historis di `data/employee_attrition.csv`.

## Tech Stack
- Python 3.10+
- pandas, scikit-learn, joblib
- pytest untuk automated test

## Instalasi Lingkungan Virtual
```bash
python -m venv venv
source venv/bin/activate    # Windows: venv\Scripts\activate
pip install -r requirements.txt
```

## Struktur Track
```
machine-learning/
├── data/employee_attrition.csv     # dataset historis (200 data karyawan)
├── src/
│   ├── data_preprocessing.py       # [TODO intern: fungsi preprocess]
│   ├── train_model.py              # [TODO intern: training model & simpan model.joblib]
│   ├── predict.py                  # [TODO intern: inferensi data karyawan tunggal]
│   └── serve.py                    # Mini HTTP inference server (port 5000)
└── tests/test_model.py             # Automated grading test (pytest)
```

## Tugas Anda (TODO)

1. **`src/data_preprocessing.py`**
   - Fungsi `load_data(path)` sudah diimplementasikan untuk memuat CSV.
   - Lengkapi fungsi `preprocess(df)`:
     - Bersihkan missing values (dropna atau imputasi).
     - Drop kolom identitas non-fitur (`id`, `name`).
     - Encode kolom kategorikal (`department`, `position`) menjadi numerik.
     - Lakukan scaling fitur numerik (opsional tapi disarankan).
     - Kembalikan pasangan `(X, y)` dengan target `y` berupa label biner 0/1.

2. **`src/train_model.py`**
   - Lengkapi inisialisasi model klasifikasi (Logistic Regression, Random Forest, Gradient Boosting, dll.).
   - Evaluasi akurasi dan F1-score pada test set (minimal akurasi **60%**).
   - Simpan model terlatih ke `model.joblib`.
   - Jalankan script via:
     ```bash
     python src/train_model.py
     ```

3. **`src/predict.py`**
   - Lengkapi fungsi `predict(employee: dict) -> dict`.
   - Lakukan transformasi format data input karyawan agar selaras dengan fitur model.
   - Panggil `model.predict_proba()` dan kembalikan output sesuai format:
     `{"risk_level": "low|medium|high", "probability": float}`.
   - Uji coba langsung via CLI:
     ```bash
     python src/predict.py
     ```

## Menjalankan Automated Test
```bash
pytest tests/test_model.py -v
```
Setelah Anda menyelesaikan `data_preprocessing.py`, menjalankan `train_model.py` (sehingga `model.joblib` terbuat), dan melengkapi `predict.py`, semua test WAJIB berstatus **PASSED**.

## Opsional: Menjalankan Mini HTTP Server
Anda dapat menjalankan server inferensi HTTP bawaan:
```bash
python src/serve.py
```
Server akan aktif di `http://localhost:5000` dengan endpoint `POST /predict`.

## Kriteria Penilaian
Lihat Section 5.3 dan Section 8 di `../PRD.md`.
