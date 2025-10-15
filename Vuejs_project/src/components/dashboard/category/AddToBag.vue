<template>
  <div class="container mx-auto py-8 px-4 font-kantumruy">
    <div v-if="loading" class="text-center py-40">Loading...</div>
    <div v-else-if="error" class="text-center text-red-500 py-40">{{ error }}</div>

    <div v-else class="max-w-xl mx-auto">
      <!-- Product info -->
      <img :src="product.image_url" class="w-full h-80 object-cover rounded-xl mb-6" />
      <h1 class="text-2xl font-bold mb-2">{{ product.name }}</h1>
      <p class="text-lg text-gray-700 mb-4">{{ formatPrice(product.price) }}</p>
      <p class="text-gray-600 mb-6">{{ product.description }}</p>

      <!-- Add to Bag -->
      <button
        @click="onAddToBag"
        class="bg-pink-700 text-white px-6 py-3 rounded-md font-semibold hover:bg-pink-800"
        :disabled="adding"
      >
        <span v-if="adding">Adding...</span>
        <span v-else>ADD TO BAG</span>
      </button>
    </div>

    <!-- AddedToBagDrawer Component -->
    <AddedToBagDrawer
      :open="showDrawer"
      :item="addedItem"
      :name="product.name"
      :thumbnail="product.image_url"
      @close="showDrawer = false"
    />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import AddedToBagDrawer from './AddedToBagDrawer.vue'

const product = ref({})
const loading = ref(true)
const error = ref(null)
const adding = ref(false)

const addedItem = ref(null)
const showDrawer = ref(false)

// Fetch product (dummy id = 1 for demo)
onMounted(async () => {
  try {
    const res = await axios.get(`${import.meta.env.VITE_API_BASE_URL}/products/1`)
    product.value = res.data
  } catch (err) {
    error.value = 'Failed to load product'
  } finally {
    loading.value = false
  }
})

const onAddToBag = async () => {
  adding.value = true
  try {
    const res = await axios.post(`${import.meta.env.VITE_API_BASE_URL}/cart/items`, {
      product_id: product.value.id,
      qty: 1,
      price: product.value.price
    })
    addedItem.value = res.data
    showDrawer.value = true
  } catch (err) {
    console.error(err)
    alert('Failed to add to bag.')
  } finally {
    adding.value = false
  }
}

const formatPrice = (value) =>
  new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(Number(value))
</script>
