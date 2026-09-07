"""
Automated grading test — Machine Learning track.

Test ini SENGAJA akan gagal (NotImplementedError) sebelum intern melengkapi
src/data_preprocessing.py, src/train_model.py, dan src/predict.py.

Cara menjalankan:
    1. python src/train_model.py     (hasilkan model.joblib)
    2. pytest tests/test_model.py -v

Semua test WAJIB berstatus PASS sebagai syarat kelulusan track ML.
"""

import os
import sys

import pandas as pd
import pytest

sys.path.insert(0, os.path.join(os.path.dirname(__file__), "..", "src"))

from data_preprocessing import load_data, preprocess  # noqa: E402

DATA_PATH = os.path.join(os.path.dirname(__file__), "..", "data", "employee_attrition.csv")
MODEL_PATH = os.path.join(os.path.dirname(__file__), "..", "model.joblib")


class TestDataPreprocessing:
    def test_load_data_returns_dataframe(self):
        df = load_data(DATA_PATH)
        assert isinstance(df, pd.DataFrame)
        assert len(df) > 0

    def test_load_data_has_expected_columns(self):
        df = load_data(DATA_PATH)
        expected_columns = {
            "department", "position", "years_at_company",
            "monthly_salary", "satisfaction_score", "last_evaluation", "attrition",
        }
        assert expected_columns.issubset(set(df.columns))

    def test_preprocess_returns_features_and_target(self):
        df = load_data(DATA_PATH)
        X, y = preprocess(df)

        assert len(X) == len(y)
        assert len(X) > 0

    def test_preprocess_output_is_fully_numeric(self):
        """Fitur hasil preprocessing harus numerik (kategorikal sudah di-encode)."""
        df = load_data(DATA_PATH)
        X, y = preprocess(df)

        X_df = pd.DataFrame(X)
        for col in X_df.columns:
            assert pd.api.types.is_numeric_dtype(X_df[col]), (
                f"Kolom '{col}' pada fitur X harus numerik setelah preprocessing "
                f"(kategorikal seperti 'department'/'position' wajib di-encode)."
            )

    def test_target_is_binary(self):
        df = load_data(DATA_PATH)
        _, y = preprocess(df)

        unique_values = set(pd.Series(y).unique())
        assert unique_values.issubset({0, 1}), "Target 'attrition' harus berupa label biner 0/1"


class TestModelTrainingArtifact:
    def test_model_file_exists(self):
        assert os.path.exists(MODEL_PATH), (
            "model.joblib tidak ditemukan. Jalankan `python src/train_model.py` "
            "terlebih dahulu untuk melatih & menyimpan model."
        )

    def test_model_has_minimum_accuracy(self):
        """
        Model dievaluasi ulang di sini terhadap keseluruhan dataset sebagai sanity check
        (bukan pengganti evaluasi test-set yang harus dilakukan intern di train_model.py).
        """
        import joblib
        from sklearn.metrics import accuracy_score

        if not os.path.exists(MODEL_PATH):
            pytest.skip("model.joblib belum ada — jalankan train_model.py dulu")

        model = joblib.load(MODEL_PATH)
        df = load_data(DATA_PATH)
        X, y = preprocess(df)

        y_pred = model.predict(X)
        acc = accuracy_score(y, y_pred)

        # Ambang batas longgar — tujuan utamanya memastikan model belajar sesuatu,
        # bukan sekadar menebak mayoritas kelas secara ekstrem.
        assert acc >= 0.60, f"Akurasi model terlalu rendah: {acc:.3f} (minimum 0.60)"


class TestPredictFunction:
    def test_predict_returns_expected_shape(self):
        from predict import predict

        if not os.path.exists(MODEL_PATH):
            pytest.skip("model.joblib belum ada — jalankan train_model.py dulu")

        sample_employee = {
            "department": "Engineering",
            "position": "Software Engineer",
            "years_at_company": 2,
            "monthly_salary": 8000000,
            "satisfaction_score": 0.4,
            "last_evaluation": 0.6,
        }

        result = predict(sample_employee)

        assert "risk_level" in result
        assert "probability" in result
        assert result["risk_level"] in {"low", "medium", "high"}
        assert 0.0 <= float(result["probability"]) <= 1.0

    def test_predict_risk_level_matches_probability_mapping(self):
        from predict import predict

        if not os.path.exists(MODEL_PATH):
            pytest.skip("model.joblib belum ada — jalankan train_model.py dulu")

        sample_employee = {
            "department": "Sales",
            "position": "Sales Executive",
            "years_at_company": 0,
            "monthly_salary": 5000000,
            "satisfaction_score": 0.2,
            "last_evaluation": 0.4,
        }

        result = predict(sample_employee)
        prob = float(result["probability"])

        if prob < 0.33:
            assert result["risk_level"] == "low"
        elif prob <= 0.66:
            assert result["risk_level"] == "medium"
        else:
            assert result["risk_level"] == "high"
