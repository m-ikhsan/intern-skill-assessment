"""
Data preprocessing untuk EMS Attrition Prediction.
Track 3: Machine Learning.
"""

import os
from typing import Tuple
import pandas as pd
import numpy as np


def load_data(path: str) -> pd.DataFrame:
    """
    Membaca file CSV dataset karyawan menjadi pandas DataFrame.
    """
    if not os.path.exists(path):
        raise FileNotFoundError(f"File dataset tidak ditemukan di: {path}")
    return pd.read_csv(path)


def preprocess(df: pd.DataFrame) -> Tuple[pd.DataFrame, pd.Series]:
    """
    Melakukan preprocessing pada DataFrame karyawan:
      - Menangani missing values (mis. dropna atau fillna, jelaskan pilihan Anda di README)
      - Drop kolom identitas yang tidak boleh masuk ke fitur model (misalnya 'id', 'name')
      - Memisahkan target (kolom 'attrition') menjadi y (nilai biner 0 atau 1)
      - Meng-encode kolom kategorikal ('department', 'position') menjadi numerik
        (misal: pd.get_dummies / OneHotEncoder / LabelEncoder)
      - Melakukan scaling pada kolom numerik (mis. StandardScaler / MinMaxScaler) — opsional tapi disarankan

    TODO(intern): Implementasikan fungsi ini.

    Returns:
        X (pd.DataFrame atau np.ndarray): fitur numerik siap dilatih
        y (pd.Series atau np.ndarray): target label biner (0/1)
    """
    # TODO(intern): lengkapi implementasi di bawah ini
    raise NotImplementedError("TODO(intern): implementasikan fungsi preprocess() di data_preprocessing.py")
