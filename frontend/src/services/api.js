import axios from 'axios'
import { mockEmployees } from '../mock/employees'

// Set USE_MOCK ke false jika ingin menghubungkan ke backend Laravel (http://localhost:8000/api)
export const USE_MOCK = true

const apiClient = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000/api',
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  },
  timeout: 5000,
})

// Simpan state in-memory untuk mock agar penambahan/pengeditan data saat testing UI terasa dinamis
let localMockEmployees = [...mockEmployees]

export const employeeService = {
  /**
   * Mengambil daftar karyawan.
   * Mendukung parameter: { department, risk, page }
   */
  async getEmployees(params = {}) {
    if (USE_MOCK) {
      let filtered = [...localMockEmployees]
      if (params.department) {
        filtered = filtered.filter(e => e.department.toLowerCase() === params.department.toLowerCase())
      }
      if (params.risk) {
        filtered = filtered.filter(e => e.attrition_risk === params.risk)
      }
      return { data: filtered }
    }

    try {
      const response = await apiClient.get('/employees', { params })
      return response.data
    } catch (error) {
      console.warn('Gagal terhubung ke backend Laravel. Beralih ke data mock otomatis.', error.message)
      return { data: localMockEmployees }
    }
  },

  /**
   * Mengambil detail 1 karyawan berdasarkan ID
   */
  async getEmployee(id) {
    if (USE_MOCK) {
      const emp = localMockEmployees.find(e => e.id === Number(id))
      if (!emp) throw new Error('Employee not found')
      return { data: emp }
    }

    const response = await apiClient.get(`/employees/${id}`)
    return response.data
  },

  /**
   * Menambahkan karyawan baru
   */
  async createEmployee(employeeData) {
    if (USE_MOCK) {
      const newEmployee = {
        id: localMockEmployees.length ? Math.max(...localMockEmployees.map(e => e.id)) + 1 : 1,
        attrition: false,
        attrition_risk: 'low',
        ...employeeData,
      }
      localMockEmployees.push(newEmployee)
      return { data: newEmployee }
    }

    const response = await apiClient.post('/employees', employeeData)
    return response.data
  },

  /**
   * Mengupdate data karyawan
   */
  async updateEmployee(id, employeeData) {
    if (USE_MOCK) {
      const index = localMockEmployees.findIndex(e => e.id === Number(id))
      if (index === -1) throw new Error('Employee not found')
      localMockEmployees[index] = { ...localMockEmployees[index], ...employeeData }
      return { data: localMockEmployees[index] }
    }

    const response = await apiClient.put(`/employees/${id}`, employeeData)
    return response.data
  },

  /**
   * Menghapus karyawan
   */
  async deleteEmployee(id) {
    if (USE_MOCK) {
      localMockEmployees = localMockEmployees.filter(e => e.id !== Number(id))
      return { success: true }
    }

    const response = await apiClient.delete(`/employees/${id}`)
    return response.data
  },

  /**
   * Mengambil kalkulasi risiko attrition untuk karyawan
   */
  async getAttritionRisk(id) {
    if (USE_MOCK) {
      const emp = localMockEmployees.find(e => e.id === Number(id))
      return {
        risk_level: emp ? emp.attrition_risk : 'low',
        probability: emp ? (emp.attrition_risk === 'high' ? 0.78 : emp.attrition_risk === 'medium' ? 0.45 : 0.15) : 0.1,
      }
    }

    const response = await apiClient.get(`/employees/${id}/attrition-risk`)
    return response.data
  }
}
