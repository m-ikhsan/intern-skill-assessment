<script setup>
import { ref, onMounted, computed } from 'vue'
import { employeeService } from '../services/api'
import AttritionBadge from './AttritionBadge.vue'

const emit = defineEmits(['edit', 'create'])

const employees = ref([])
const loading = ref(true)
const errorMessage = ref('')

const searchQuery = ref('')
const departmentFilter = ref('')
const riskFilter = ref('')

// Daftar departemen untuk pilihan dropdown
const departments = ['Engineering', 'Sales', 'HR', 'Finance', 'Marketing']

// Pagination state
const currentPage = ref(1)
const itemsPerPage = ref(6)

// TODO(intern):
// Implementasikan logika penyaringan data karyawan pada computed `filteredEmployees`:
// 1. Filter berdasarkan `searchQuery` (mencocokkan nama karyawan, case-insensitive).
// 2. Filter berdasarkan `departmentFilter` (jika dipilih).
// 3. Filter berdasarkan `riskFilter` (jika dipilih).
const filteredEmployees = computed(() => {
  // TODO(intern): Saat ini mengembalikan seluruh data tanpa filter. Lengkapi logika filternya!
  return employees.value
})

// Pagination computed
const paginatedEmployees = computed(() => {
  // TODO(intern): Potong `filteredEmployees` sesuai `currentPage` dan `itemsPerPage`.
  // Contoh: .slice(start, end)
  const start = (currentPage.value - 1) * itemsPerPage.value
  const end = start + itemsPerPage.value
  return filteredEmployees.value.slice(start, end)
})

const totalPages = computed(() => {
  return Math.ceil(filteredEmployees.value.length / itemsPerPage.value) || 1
})

async function fetchEmployees() {
  loading.value = true
  errorMessage.value = ''
  try {
    const res = await employeeService.getEmployees()
    employees.value = res.data || []
  } catch (err) {
    errorMessage.value = 'Gagal memuat data karyawan: ' + err.message
  } finally {
    loading.value = false
  }
}

async function handleDelete(id) {
  if (!confirm('Apakah Anda yakin ingin menghapus data karyawan ini?')) return
  try {
    await employeeService.deleteEmployee(id)
    employees.value = employees.value.filter(e => e.id !== id)
  } catch (err) {
    alert('Gagal menghapus: ' + err.message)
  }
}

onMounted(() => {
  fetchEmployees()
})

// Expose refresh jika parent ingin memanggil ulang setelah tambah/edit
defineExpose({ fetchEmployees })
</script>

<template>
  <div class="space-y-4">
    <!-- Header Control: Search, Filter, dan Tambah -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
      <div class="flex flex-wrap items-center gap-2 flex-1">
        <!-- Input Search -->
        <div class="relative w-full sm:w-64">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Cari nama karyawan..."
            class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
          />
          <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </div>

        <!-- Filter Departemen -->
        <select
          v-model="departmentFilter"
          class="text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all text-gray-700"
        >
          <option value="">Semua Departemen</option>
          <option v-for="dept in departments" :key="dept" :value="dept">{{ dept }}</option>
        </select>

        <!-- Filter Risiko Attrition -->
        <select
          v-model="riskFilter"
          class="text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all text-gray-700"
        >
          <option value="">Semua Risiko</option>
          <option value="low">Low Risk</option>
          <option value="medium">Medium Risk</option>
          <option value="high">High Risk</option>
        </select>
      </div>

      <!-- Tombol Tambah Karyawan -->
      <button
        @click="emit('create')"
        class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Tambah Karyawan
      </button>
    </div>

    <!-- Error State -->
    <div v-if="errorMessage" class="p-4 bg-red-50 text-red-700 rounded-xl text-sm border border-red-200">
      {{ errorMessage }}
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="p-12 text-center text-gray-400 bg-white rounded-xl shadow-sm border border-gray-100">
      <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-blue-500 border-t-transparent"></div>
      <p class="mt-2 text-sm">Memuat data karyawan...</p>
    </div>

    <!-- Tabel Daftar Karyawan -->
    <div v-else class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
          <thead class="bg-gray-50 border-b border-gray-100 text-gray-600 uppercase text-xs tracking-wider">
            <tr>
              <th class="py-3.5 px-4 font-semibold">Nama</th>
              <th class="py-3.5 px-4 font-semibold">Departemen</th>
              <th class="py-3.5 px-4 font-semibold">Jabatan</th>
              <th class="py-3.5 px-4 font-semibold">Masa Kerja</th>
              <th class="py-3.5 px-4 font-semibold">Gaji Bulanan</th>
              <th class="py-3.5 px-4 font-semibold">Risiko Resign</th>
              <th class="py-3.5 px-4 font-semibold text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 text-gray-700">
            <tr
              v-for="emp in paginatedEmployees"
              :key="emp.id"
              class="hover:bg-gray-50/70 transition-colors"
            >
              <td class="py-3.5 px-4 font-medium text-gray-900">{{ emp.name }}</td>
              <td class="py-3.5 px-4">
                <span class="inline-flex px-2 py-0.5 rounded text-xs bg-slate-100 text-slate-700 font-medium">
                  {{ emp.department }}
                </span>
              </td>
              <td class="py-3.5 px-4">{{ emp.position }}</td>
              <td class="py-3.5 px-4">{{ emp.years_at_company }} tahun</td>
              <td class="py-3.5 px-4 font-mono text-xs">Rp {{ (emp.monthly_salary || 0).toLocaleString('id-ID') }}</td>
              <td class="py-3.5 px-4">
                <!-- Komponen Badge Risiko -->
                <AttritionBadge :risk-level="emp.attrition_risk || 'low'" />
              </td>
              <td class="py-3.5 px-4 text-right space-x-2">
                <button
                  @click="emit('edit', emp)"
                  class="text-blue-600 hover:text-blue-800 font-medium text-xs transition-colors"
                >
                  Edit
                </button>
                <button
                  @click="handleDelete(emp.id)"
                  class="text-rose-600 hover:text-rose-800 font-medium text-xs transition-colors"
                >
                  Hapus
                </button>
              </td>
            </tr>

            <!-- Empty State -->
            <tr v-if="paginatedEmployees.length === 0">
              <td colspan="7" class="py-8 text-center text-gray-400 text-sm">
                Tidak ada data karyawan yang sesuai dengan kriteria pencarian.
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination Footer -->
      <div class="flex items-center justify-between px-4 py-3 border-t border-gray-100 bg-gray-50/50 text-xs text-gray-500">
        <div>
          Menampilkan <span class="font-medium text-gray-700">{{ paginatedEmployees.length }}</span> dari
          <span class="font-medium text-gray-700">{{ filteredEmployees.length }}</span> karyawan
        </div>
        <div class="flex items-center gap-1">
          <button
            :disabled="currentPage <= 1"
            @click="currentPage--"
            class="px-2.5 py-1 border border-gray-200 rounded bg-white hover:bg-gray-100 disabled:opacity-40 disabled:cursor-not-allowed transition-colors"
          >
            Prev
          </button>
          <span class="px-2 text-gray-600 font-medium">{{ currentPage }} / {{ totalPages }}</span>
          <button
            :disabled="currentPage >= totalPages"
            @click="currentPage++"
            class="px-2.5 py-1 border border-gray-200 rounded bg-white hover:bg-gray-100 disabled:opacity-40 disabled:cursor-not-allowed transition-colors"
          >
            Next
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
