# Frontend Track — Employee Directory (Vue 3 + Tailwind CSS)

## Tujuan
Membangun dashboard direktori karyawan yang menampilkan daftar karyawan beserta indikator risiko attrition.

## Tech Stack
- Vue.js 3 (Composition API `<script setup>`)
- Tailwind CSS
- Vite
- Axios

## Instalasi & Menjalankan Proyek
```bash
npm install
npm run dev
```
Aplikasi langsung dapat dibuka di `http://localhost:5173`.

> **Catatan Mode Standalone:**
> Secara default, aplikasi berjalan dalam **Standalone Mode (Mock Data)** menggunakan data di `src/mock/employees.js`. Anda tidak perlu menunggu backend dinyalakan untuk mengerjakan track ini.
> Jika ingin menghubungkan ke backend Laravel sungguhan, ubah `export const USE_MOCK = false` di file `src/services/api.js`.

## Tugas Anda (TODO)
Komponen di `src/components/` sudah terhubung ke dashboard utama. Lengkapi bagian yang ditandai `// TODO(intern)`:

1. **`src/components/EmployeeList.vue`**
   - Implementasikan logika filter pada computed `filteredEmployees` (pencarian nama, filter departemen, filter risiko).
   - Implementasikan pagination pada computed `paginatedEmployees`.
   - Pastikan badge risiko ditampilkan menggunakan `<AttritionBadge :risk-level="..." />`.

2. **`src/components/EmployeeForm.vue`**
   - Implementasikan fungsi `validate()` client-side (nama wajib, departemen wajib, angka non-negatif, skor antara 0.0 - 1.0).
   - Tampilkan pesan error validasi per field jika input tidak sesuai.

3. **`src/components/AttritionBadge.vue`**
   - Implementasikan computed `badgeClass` yang memetakan `riskLevel`:
     - `'low'` -> hijau (`bg-emerald-100 text-emerald-800 border border-emerald-200`)
     - `'medium'` -> kuning (`bg-amber-100 text-amber-800 border border-amber-200`)
     - `'high'` -> merah (`bg-rose-100 text-rose-800 border border-rose-200`)

## Build & Validasi
Pastikan kode tidak memiliki error syntax dan dapat di-build dengan:
```bash
npm run build
```

## Kriteria Penilaian
Lihat Section 5.1 dan Section 8 di `../PRD.md`.
