"""
Training script untuk model prediksi attrition karyawan.
Track 3: Machine Learning.

Jalankan:
    python src/train_model.py

Akan menghasilkan file model.joblib di root folder machine-learning/.
TODO(intern): Lengkapi langkah 1 s.d. 5 di bawah ini.
"""

import os
import sys
import joblib
from sklearn.model_selection import train_test_split
from sklearn.metrics import accuracy_score, f1_score, classification_report

# Pastikan module local src/ dapat diimport
CURRENT_DIR = os.path.dirname(os.path.abspath(__file__))
if CURRENT_DIR not in sys.path:
    sys.path.insert(0, CURRENT_DIR)

from data_preprocessing import load_data, preprocess

# Path konfigurasi file dataset dan output model
DATA_PATH = os.path.join(CURRENT_DIR, "..", "data", "employee_attrition.csv")
MODEL_PATH = os.path.join(CURRENT_DIR, "..", "model.joblib")


def main():
    print("=" * 60)
    print("EMS: Training Model Prediksi Attrition Karyawan")
    print("=" * 60)

    # 1. Load data
    print(f"[1/5] Memuat dataset dari: {DATA_PATH}...")
    df = load_data(DATA_PATH)
    print(f"      Data berhasil dimuat: {len(df)} baris, {len(df.columns)} kolom.")

    # 2. Preprocess data
    print("[2/5] Menjalankan fungsi preprocess()...")
    X, y = preprocess(df)
    print(f"      Fitur siap: {X.shape[0]} baris, target biner unik: {set(y)}")

    # 3. Split dataset train & test (random_state=42 agar reproducible)
    print("[3/5] Membagi dataset train/test (80/20, random_state=42)...")
    X_train, X_test, y_train, y_test = train_test_split(
        X, y, test_size=0.2, random_state=42, stratify=y
    )
    print(f"      Ukuran Train: {len(X_train)}, Ukuran Test: {len(X_test)}")

    # 4. Inisialisasi & Latih Model
    print("[4/5] Melatih model klasifikasi...")
    # TODO(intern): Pilih dan inisialisasi model klasifikasi Anda di sini
    # Contoh pilihan:
    # from sklearn.ensemble import RandomForestClassifier
    # model = RandomForestClassifier(n_estimators=100, random_state=42)
    # model.fit(X_train, y_train)
    model = None  # TODO(intern): Ganti dengan model klasifikasi terlatih

    if model is None:
        raise NotImplementedError("TODO(intern): Inisialisasi dan latih model klasifikasi pilihan Anda!")

    # 5. Evaluasi & Simpan Model
    print("[5/5] Mengevaluasi model pada test-set...")
    y_pred = model.predict(X_test)
    acc = accuracy_score(y_test, y_pred)
    f1 = f1_score(y_test, y_pred, average="weighted")

    print("\n--- Hasil Evaluasi ---")
    print(f"Akurasi  : {acc:.4f} ({acc * 100:.1f}%)")
    print(f"F1-Score : {f1:.4f}")
    print("\nLaporan Klasifikasi:")
    print(classification_report(y_test, y_pred))

    joblib.dump(model, MODEL_PATH)
    print(f"\nModel berhasil disimpan ke: {MODEL_PATH}")
    print("Automated test sekarang dapat dijalankan via: pytest tests/test_model.py -v\n")


if __name__ == "__main__":
    main()
