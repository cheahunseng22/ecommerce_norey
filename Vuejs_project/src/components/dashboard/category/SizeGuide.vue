<template>
  <div v-if="open" class="fixed inset-0 z-50" role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-black/50" @click="emitClose"></div>

    <div class="relative mx-auto my-8 w-[92%] max-w-3xl">
      <div class="bg-white rounded-lg shadow-xl overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b">
          <h3 class="text-lg font-semibold text-gray-900">{{ title }}</h3>
          <button @click="emitClose" aria-label="Close size guide" class="p-2 rounded hover:bg-gray-100">
            <svg class="w-5 h-5 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <div class="p-5">
          <p class="text-sm text-gray-600 mb-4">Measurements are in centimeters (cm). If you are between sizes, we recommend sizing up for comfort.</p>

          <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
              <thead>
                <tr class="text-gray-700">
                  <th class="py-2 pr-6 font-semibold">Size</th>
                  <th class="py-2 pr-6 font-semibold">Bust</th>
                  <th class="py-2 pr-6 font-semibold">Waist</th>
                  <th class="py-2 pr-6 font-semibold">Hips</th>
                </tr>
              </thead>
              <tbody class="align-top">
                <tr class="border-t">
                  <td class="py-2 pr-6 text-gray-900 font-medium">XS</td>
                  <td class="py-2 pr-6 text-gray-700">76–80</td>
                  <td class="py-2 pr-6 text-gray-700">60–64</td>
                  <td class="py-2 pr-6 text-gray-700">84–88</td>
                </tr>
                <tr class="border-t">
                  <td class="py-2 pr-6 text-gray-900 font-medium">S</td>
                  <td class="py-2 pr-6 text-gray-700">81–85</td>
                  <td class="py-2 pr-6 text-gray-700">65–69</td>
                  <td class="py-2 pr-6 text-gray-700">89–93</td>
                </tr>
                <tr class="border-t">
                  <td class="py-2 pr-6 text-gray-900 font-medium">M</td>
                  <td class="py-2 pr-6 text-gray-700">86–90</td>
                  <td class="py-2 pr-6 text-gray-700">70–74</td>
                  <td class="py-2 pr-6 text-gray-700">94–98</td>
                </tr>
                <tr class="border-t">
                  <td class="py-2 pr-6 text-gray-900 font-medium">L</td>
                  <td class="py-2 pr-6 text-gray-700">91–96</td>
                  <td class="py-2 pr-6 text-gray-700">75–80</td>
                  <td class="py-2 pr-6 text-gray-700">99–104</td>
                </tr>
                <tr class="border-t">
                  <td class="py-2 pr-6 text-gray-900 font-medium">XL</td>
                  <td class="py-2 pr-6 text-gray-700">97–103</td>
                  <td class="py-2 pr-6 text-gray-700">81–87</td>
                  <td class="py-2 pr-6 text-gray-700">105–111</td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-pink-50 rounded-md p-4">
              <h4 class="text-sm font-semibold text-pink-800 mb-1">How to measure</h4>
              <ul class="list-disc pl-5 text-sm text-gray-700 space-y-1">
                <li>Bust: Measure around the fullest part of your chest.</li>
                <li>Waist: Measure around your natural waistline.</li>
                <li>Hips: Measure around the fullest part of your hips.</li>
              </ul>
            </div>
            <div class="bg-gray-50 rounded-md p-4">
              <h4 class="text-sm font-semibold text-gray-800 mb-1">Fit tips</h4>
              <ul class="list-disc pl-5 text-sm text-gray-700 space-y-1">
                <li>If between sizes, consider the looser fit for comfort.</li>
                <li>Fabric with stretch can allow a closer fit.</li>
              </ul>
            </div>
          </div>
        </div>

        <div class="px-5 py-4 border-t flex justify-end">
          <button @click="emitClose" class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-50">Close</button>
        </div>
      </div>
    </div>
  </div>
  
</template>

<script setup>
import { onMounted, onBeforeUnmount } from 'vue'

const props = defineProps({
  open: { type: Boolean, default: false },
  title: { type: String, default: 'Size Guide' }
})

const emit = defineEmits(['close'])

const emitClose = () => emit('close')

const onKeydown = (event) => {
  if (event.key === 'Escape' && props.open) emitClose()
}

onMounted(() => {
  document.addEventListener('keydown', onKeydown)
})

onBeforeUnmount(() => {
  document.removeEventListener('keydown', onKeydown)
})
</script>

<style scoped>
</style>


