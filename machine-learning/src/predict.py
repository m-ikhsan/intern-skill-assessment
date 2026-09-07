"""
Modul prediksi risiko attrition per karyawan.
Track 3: Machine Learning.

Dipakai oleh:
- Automated test (tests/test_model.py)
- Mini server HTTP (src/serve.py)
- Eksekusi CLI langsung (python src/predict.py --sample)
"""

import os
import sys
import json
import joblib

CURRENT_DIR = os.path.dirname(os.path.abspath(__file__))
MODEL_PATH = os.path.join(CURRENT_DIR, "..", "model.joblib")

_model = None  # Cache model agar tidak perlu load ulang dari disk setiap kali dipanggil


def _get_model():
    """
    Memuat model dari file model.joblib (singleton cache).
    """
    global _model
    if _model is None:
        if not os.path.exists(MODEL_PATH):
            raise FileNotFoundError(
                f"File model '{MODEL_PATH}' belum ditemukan. "
                "Jalankan `python src/train_model.py` terlebih dahulu untuk melatih dan menyimpan model."
            )
        _model = joblib.load(MODEL_PATH)
    return _model


def _risk_level_from_probability(probability: float) -> str:
    """
    Mapping probabilitas attrition -> kategori risk_level sesuai PRD Section 5.4:
      - < 0.33      : low
      - 0.33 - 0.66 : medium
      - > 0.66      : high
    """
    if probability < 0.33:
        return "low"
    elif probability <= 0.66:
        return "medium"
    else:
        return "high"


def predict(employee: dict) -> dict:
    """
    Menerima 1 dict data profil karyawan (tanpa field 'attrition'), contoh:
        {
            "department": "Engineering",
            "position": "Software Engineer",
            "years_at_company": 2,
            "monthly_salary": 8000000,
            "satisfaction_score": 0.40,
            "last_evaluation": 0.60
        }

    Mengembalikan:
        {
            "risk_level": "low" | "medium" | "high",
            "probability": float (0.0 - 1.0)
        }

    TODO(intern):
      1. Panggil _get_model() untuk mendapatkan model terlatih.
      2. Lakukan transformasi data pada `employee` agar memiliki format dan urutan fitur
         yang identik dengan fitur hasil preprocess() saat training.
      3. Panggil model.predict_proba(...) untuk mendapatkan probabilitas kelas positif (attrition=1).
      4. Gunakan _risk_level_from_probability(probability) untuk menentukan risk_level.
      5. Kembalikan dict dengan key 'risk_level' dan 'probability'.
    """
    # TODO(intern): Implementasikan logika inferensi di sini
    raise NotImplementedError("TODO(intern): implementasikan fungsi predict() di src/predict.py")


if __name__ == "__main__":
    sample_employee = {
        "name": "Budi Santoso",
        "department": "Engineering",
        "position": "Software Engineer",
        "years_at_company": 2,
        "monthly_salary": 8000000,
        "satisfaction_score": 0.35,
        "last_evaluation": 0.70,
    }

    print("Contoh data input karyawan:")
    print(json.dumps(sample_employee, indent=2))
    print("\nMenjalankan fungsi predict()...")

    try:
        result = predict(sample_employee)
        print("\nHasil Prediksi Attrition:")
        print(json.dumps(result, indent=2))
    except NotImplementedError as e:
        print(f"\n[INFO] {e}")
    except FileNotFoundError as e:
        print(f"\n[PERINGATAN] {e}")
