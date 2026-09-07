<script setup>
import { computed } from 'vue'

// Props riskLevel: 'low' | 'medium' | 'high'
const props = defineProps({
  riskLevel: {
    type: String,
    default: 'low',
    validator: (v) => ['low', 'medium', 'high'].includes(v),
  },
})

// TODO(intern): Implementasikan pemetaan warna badge dinamis sesuai tingkat risiko:
// - 'low'    -> 'bg-emerald-100 text-emerald-800 border border-emerald-200'
// - 'medium' -> 'bg-amber-100 text-amber-800 border border-amber-200'
// - 'high'   -> 'bg-rose-100 text-rose-800 border border-rose-200'
const badgeClass = computed(() => {
  // TODO(intern): ganti fallback default ini dengan logika pemetaan di atas
  return 'bg-gray-100 text-gray-700 border border-gray-200'
})
</script>

<template>
  <span :class="['inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold capitalize transition-colors', badgeClass]">
    {{ riskLevel }}
  </span>
</template>
