<template>
  <div v-if="open" class="fixed inset-0 z-50">
    <div class="absolute inset-0 bg-black/40" @click="$emit('close')"></div>
    <aside class="absolute right-0 top-0 h-full w-full sm:w-[420px] bg-white shadow-xl flex flex-col">
      <header class="flex items-center justify-between px-5 py-4 border-b">
        <h3 class="text-lg font-semibold text-gray-900">Your Cart</h3>
        <button @click="$emit('close')" class="p-2 rounded hover:bg-gray-100">
          ✕
        </button>
      </header>

      <div class="p-5 space-y-4 overflow-y-auto flex-1">
        <div v-if="items.length === 0" class="text-gray-500">Cart is empty</div>

        <div v-for="item in items" :key="item.id" class="flex items-center justify-between border-b py-3">
          <div class="flex items-center gap-4">
            <img v-if="item.product?.image" :src="item.product.image" class="w-16 h-16 object-cover rounded-lg" />
            <div>
              <p class="font-semibold text-gray-800">{{ item.product?.name }}</p>
              <p class="text-sm text-gray-500">{{ formatPrice(item.price) }} × {{ item.quantity }}</p>
            </div>
          </div>
          <div class="flex items-center gap-3">
            <input
              type="number"
              min="1"
              class="w-16 border rounded-lg text-center"
              v-model.number="item.quantity"
              @change="updateItem(item)"
            />
            <button @click="removeItem(item)" class="text-red-500 hover:text-red-700">Remove</button>
          </div>
        </div>
      </div>

      <footer class="p-4 border-t flex justify-between">
        <p class="font-bold">Total:</p>
        <p class="font-bold text-green-600">{{ formatPrice(cartTotal) }}</p>
      </footer>
    </aside>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import api from '../axios/Axios'

const props = defineProps({
  open: Boolean,
  items: { type: Array, default: () => [] }
})

const emit = defineEmits(['close'])

// Format currency
const formatPrice = (value) =>
  new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(value || 0)

// Compute total
const cartTotal = computed(() => props.items.reduce((sum, i) => sum + i.price * i.quantity, 0))

// Update item quantity
const updateItem = async (item) => {
  try {
    await api.put(`/cart/items/${item.id}`, { quantity: item.quantity })
  } catch (err) {
    console.error(err)
  }
}

// Remove item
const removeItem = async (item) => {
  try {
    await api.delete(`/cart/items/${item.id}`)
    // optionally reload drawer items
    emit('close') // close drawer after remove
  } catch (err) {
    console.error(err)
  }
}
</script>
