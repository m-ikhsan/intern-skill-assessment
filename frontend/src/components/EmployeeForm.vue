<script setup>
import { reactive, watch } from 'vue'

const props = defineProps({
  employee: {
    type: Object,
    default: null,
  },
})

const emit = defineEmits(['submit', 'cancel'])

const departments = ['Engineering', 'Sales', 'HR', 'Finance', 'Marketing']

const form = reactive({
  name: '',
  department: 'Engineering',
  position: '',
  years_at_company: 0,
  monthly_salary: 5000000,
  satisfaction_score: 0.5,
  last_evaluation: 0.5,
})

const errors = reactive({
  name: '',
  department: '',
  position: '',
  years_at_company: '',
  monthly_salary: '',
  satisfaction_score: '',
  last_evaluation: '',
})

// Isi data form jika dalam mode edit
watch(
  () => props.employee,
  (newVal) => {
    if (newVal) {
      Object.assign(form, {
        name: newVal.name || '',
        department: newVal.department || 'Engineering',
        position: newVal.position || '',
        years_at_company: newVal.years_at_company ?? 0,
        monthly_salary: newVal.monthly_salary ?? 5000000,
        satisfaction_score: newVal.satisfaction_score ?? 0.5,
        last_evaluation: newVal.last_evaluation ?? 0.5,
      })
    }
  },
  { immediate: true }
)

function clearErrors() {
  Object.keys(errors).forEach((key) => {
    errors[key] = ''
  })
}

// TODO(intern):
// Implementasikan logika validasi client-side:
// 1. `name`: wajib diisi (tidak boleh kosong / whitespace saja)
// 2. `department`: wajib dipilih salah satu dari daftar departemen
// 3. `position`: wajib diisi
// 4. `years_at_company`: angka >= 0
// 5. `monthly_salary`: angka > 0
// 6. `satisfaction_score`: float antara 0.0 sampai 1.0
// 7. `last_evaluation`: float antara 0.0 sampai 1.0
// Jika ada error, isi `errors[field] = 'Pesan error'` dan kembalikan `false`.
function validate() {
  clearErrors()
  let isValid = true

  // TODO(intern): Hapus baris di bawah dan buat validasi sesungguhnya
  if (!form.name || form.name.trim() === '') {
    errors.name = 'Nama karyawan wajib diisi.'
    isValid = false
  }

  // TODO(intern): Lengkapi validasi field lainnya di sini...

  return isValid
}

function handleSubmit() {
  if (!validate()) return

  // Format data payload sebelum dikirim
  const payload = {
    ...form,
    years_at_company: Number(form.years_at_company),
    monthly_salary: Number(form.monthly_salary),
    satisfaction_score: parseFloat(form.satisfaction_score),
    last_evaluation: parseFloat(form.last_evaluation),
  }

  emit('submit', payload)
}
</script>

<template>
  <div class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 w-full max-w-lg overflow-hidden animate-in fade-in zoom-in-95 duration-150">
      <!-- Header Modal -->
      <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gray-50/50">
        <h3 class="text-base font-semibold text-gray-800">
          {{ props.employee ? 'Edit Data Karyawan' : 'Tambah Karyawan Baru' }}
        </h3>
        <button
          @click="emit('cancel')"
          class="text-gray-400 hover:text-gray-600 transition-colors text-lg font-bold"
        >
          &times;
        </button>
      </div>

      <!-- Form Body -->
      <form @submit.prevent="handleSubmit" class="p-6 space-y-4 text-sm">
        <!-- Field Nama -->
        <div>
          <label class="block font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
          <input
            v-model="form.name"
            type="text"
            placeholder="mis. Budi Santoso"
            class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none transition-all"
            :class="errors.name ? 'border-red-400 bg-red-50/30' : 'border-gray-200'"
          />
          <p v-if="errors.name" class="mt-1 text-xs text-red-600">{{ errors.name }}</p>
        </div>

        <!-- Field Departemen & Jabatan -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="block font-medium text-gray-700 mb-1">Departemen <span class="text-red-500">*</span></label>
            <select
              v-model="form.department"
              class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none transition-all bg-white"
            >
              <option v-for="dept in departments" :key="dept" :value="dept">{{ dept }}</option>
            </select>
            <p v-if="errors.department" class="mt-1 text-xs text-red-600">{{ errors.department }}</p>
          </div>

          <div>
            <label class="block font-medium text-gray-700 mb-1">Jabatan <span class="text-red-500">*</span></label>
            <input
              v-model="form.position"
              type="text"
              placeholder="mis. Backend Engineer"
              class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none transition-all"
              :class="errors.position ? 'border-red-400 bg-red-50/30' : 'border-gray-200'"
            />
            <p v-if="errors.position" class="mt-1 text-xs text-red-600">{{ errors.position }}</p>
          </div>
        </div>

        <!-- Field Masa Kerja & Gaji -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="block font-medium text-gray-700 mb-1">Masa Kerja (Tahun)</label>
            <input
              v-model.number="form.years_at_company"
              type="number"
              min="0"
              class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none transition-all"
              :class="errors.years_at_company ? 'border-red-400' : 'border-gray-200'"
            />
            <p v-if="errors.years_at_company" class="mt-1 text-xs text-red-600">{{ errors.years_at_company }}</p>
          </div>

          <div>
            <label class="block font-medium text-gray-700 mb-1">Gaji Bulanan (Rp)</label>
            <input
              v-model.number="form.monthly_salary"
              type="number"
              step="500000"
              class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none transition-all"
              :class="errors.monthly_salary ? 'border-red-400' : 'border-gray-200'"
            />
            <p v-if="errors.monthly_salary" class="mt-1 text-xs text-red-600">{{ errors.monthly_salary }}</p>
          </div>
        </div>

        <!-- Field Skor Kepuasan & Evaluasi Terakhir -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="block font-medium text-gray-700 mb-1">
              Skor Kepuasan ({{ form.satisfaction_score }})
            </label>
            <input
              v-model.number="form.satisfaction_score"
              type="range"
              min="0"
              max="1"
              step="0.05"
              class="w-full accent-blue-600"
            />
            <p v-if="errors.satisfaction_score" class="mt-1 text-xs text-red-600">{{ errors.satisfaction_score }}</p>
          </div>

          <div>
            <label class="block font-medium text-gray-700 mb-1">
              Evaluasi Terakhir ({{ form.last_evaluation }})
            </label>
            <input
              v-model.number="form.last_evaluation"
              type="range"
              min="0"
              max="1"
              step="0.05"
              class="w-full accent-blue-600"
            />
            <p v-if="errors.last_evaluation" class="mt-1 text-xs text-red-600">{{ errors.last_evaluation }}</p>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-end gap-2 pt-4 border-t border-gray-100">
          <button
            type="button"
            @click="emit('cancel')"
            class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors font-medium text-xs"
          >
            Batal
          </button>
          <button
            type="submit"
            class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors font-medium text-xs shadow-sm"
          >
            {{ props.employee ? 'Simpan Perubahan' : 'Tambah Karyawan' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
