<script setup>
import { ref } from 'vue'
import EmployeeList from './components/EmployeeList.vue'
import EmployeeForm from './components/EmployeeForm.vue'
import { employeeService, USE_MOCK } from './services/api'

const listRef = ref(null)
const showForm = ref(false)
const selectedEmployee = ref(null)

function handleOpenCreate() {
  selectedEmployee.value = null
  showForm.value = true
}

function handleOpenEdit(employee) {
  selectedEmployee.value = employee
  showForm.value = true
}

function handleCloseForm() {
  showForm.value = false
  selectedEmployee.value = null
}

async function handleFormSubmit(payload) {
  try {
    if (selectedEmployee.value) {
      await employeeService.updateEmployee(selectedEmployee.value.id, payload)
    } else {
      await employeeService.createEmployee(payload)
    }
    handleCloseForm()
    if (listRef.value) {
      listRef.value.fetchEmployees()
    }
  } catch (error) {
    alert('Gagal menyimpan data karyawan: ' + error.message)
  }
}
</script>

<template>
  <div class="min-h-screen bg-slate-50 text-slate-800 antialiased font-sans">
    <!-- Navbar Header -->
    <header class="bg-white border-b border-slate-200/80 sticky top-0 z-30 shadow-xs">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 rounded-xl bg-blue-600 flex items-center justify-center text-white font-bold text-lg shadow-sm">
            E
          </div>
          <div>
            <h1 class="text-base font-bold text-slate-900 leading-tight">EMS Insight Dashboard</h1>
            <p class="text-xs text-slate-500">Employee Management & Attrition System</p>
          </div>
        </div>

        <!-- Mode Indicator Badge -->
        <div class="flex items-center gap-2">
          <span
            :class="[
              'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium',
              USE_MOCK ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200'
            ]"
          >
            <span :class="['w-2 h-2 rounded-full', USE_MOCK ? 'bg-amber-500' : 'bg-emerald-500 animate-pulse']"></span>
            {{ USE_MOCK ? 'Standalone Mode (Mock Data)' : 'Live API Mode' }}
          </span>
        </div>
      </div>
    </header>

    <!-- Main Container -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
      <!-- Welcome & Instructions Card -->
      <div class="bg-gradient-to-r from-blue-900 to-indigo-800 rounded-2xl p-6 text-white shadow-md">
        <div class="max-w-3xl space-y-2">
          <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-white/20 text-white backdrop-blur-xs">
            Frontend Assessment Track
          </span>
          <h2 class="text-xl sm:text-2xl font-bold tracking-tight">
            Direktori Karyawan & Analisis Risiko Resign
          </h2>
          <p class="text-sm text-blue-100/90 leading-relaxed">
            Aplikasi ini sudah berjalan dalam mode standalone. Selesaikan tugas yang ditandai dengan komentar
            <code class="px-1.5 py-0.5 bg-black/20 rounded font-mono text-xs text-amber-200">// TODO(intern)</code>
            pada komponen <code class="px-1.5 py-0.5 bg-black/20 rounded font-mono text-xs">EmployeeList.vue</code>,
            <code class="px-1.5 py-0.5 bg-black/20 rounded font-mono text-xs">EmployeeForm.vue</code>, dan
            <code class="px-1.5 py-0.5 bg-black/20 rounded font-mono text-xs">AttritionBadge.vue</code>.
          </p>
        </div>
      </div>

      <!-- Employee List Component -->
      <EmployeeList
        ref="listRef"
        @create="handleOpenCreate"
        @edit="handleOpenEdit"
      />
    </main>

    <!-- Modal Form Component -->
    <EmployeeForm
      v-if="showForm"
      :employee="selectedEmployee"
      @submit="handleFormSubmit"
      @cancel="handleCloseForm"
    />
  </div>
</template>
