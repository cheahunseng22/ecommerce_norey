<template>
  <div v-if="open" class="fixed inset-0 z-[60]">
    <div class="absolute inset-0 bg-black/40" @click="$emit('close')"></div>

    <aside class="absolute right-0 top-0 h-full w-full sm:w-[420px] bg-white shadow-xl flex flex-col">
      <header class="flex items-center justify-between px-5 py-4 border-b">
        <h3 class="text-lg font-semibold text-gray-900">Added to bag</h3>
        <button @click="$emit('close')" class="p-2 rounded hover:bg-gray-100" aria-label="Close">
          <svg class="w-5 h-5 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </header>

      <div class="p-5 space-y-4 overflow-y-auto">
        <div class="rounded-md border">
          <table class="w-full text-sm">
            <tbody>
              <tr class="border-b">
                <td class="px-4 py-2 text-gray-600 w-40">cart_item_id</td>
                <td class="px-4 py-2 font-medium text-gray-900">{{ item?.id }}</td>
              </tr>
              <tr class="border-b">
                <td class="px-4 py-2 text-gray-600">cart_id</td>
                <td class="px-4 py-2 font-medium text-gray-900">{{ item?.cart_id }}</td>
              </tr>
              <tr class="border-b">
                <td class="px-4 py-2 text-gray-600">product_id</td>
                <td class="px-4 py-2 font-medium text-gray-900">{{ item?.product_id }}</td>
              </tr>
              <tr class="border-b">
                <td class="px-4 py-2 text-gray-600">qty</td>
                <td class="px-4 py-2 font-medium text-gray-900">{{ item?.qty }}</td>
              </tr>
              <tr>
                <td class="px-4 py-2 text-gray-600">price</td>
                <td class="px-4 py-2 font-medium text-gray-900">{{ formatPrice(item?.price) }}</td>
              </tr>
            </tbody>
          </table>
        </div>

        <div v-if="thumbnail || name" class="flex items-center gap-3 p-3 rounded-md bg-gray-50">
          <img v-if="thumbnail" :src="thumbnail" alt="Product image" class="w-16 h-16 object-cover rounded" />
          <div>
            <p class="text-sm font-medium text-gray-900" v-if="name">{{ name }}</p>
            <p class="text-xs text-gray-600">This item has been added to your bag.</p>
          </div>
        </div>
      </div>

      <footer class="mt-auto p-4 border-t flex gap-3">
        <button @click="$emit('close')"
          class="flex-1 px-4 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-50">Continue
          shopping</button>
        <router-link to="/cart"
          class="flex-1 text-center px-4 py-2 rounded-md bg-pink-700 text-white font-semibold hover:bg-pink-800">View
          cart</router-link>
      </footer>
    </aside>
  </div>
</template>

<script setup>
const props = defineProps({
  open: { type: Boolean, default: false },
  item: { type: Object, default: null },
  name: { type: String, default: '' },
  thumbnail: { type: String, default: '' }
})

const formatPrice = (value) => {
  if (!value) return ''
  return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(Number(value))
}
</script>
