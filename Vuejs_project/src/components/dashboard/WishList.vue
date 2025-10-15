<template>
  <div class="container mx-auto py-8 px-4 font-kantumruy">
    <!-- Header -->
    <div class="text-center mb-10">
      <h1 class="text-3xl font-bold text-gray-900">✨ My Wish List</h1>
      <p class="text-gray-600 mt-2">
        <span v-if="loading">Loading your wish list...</span>
        <span v-else>{{ wishlist.length }} item(s) waiting for you</span>
      </p>
    </div>

    <!-- Wishlist Grid -->
    <div
      v-if="!loading && wishlist.length"
      class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6"
    >
      <div
        v-for="item in wishlist"
        :key="item.id"
        class="bg-white rounded-2xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1"
      >
        <!-- Product Image -->
        <div class="relative group">
          <img
            :src="item.product.image"
            :alt="item.product.title"
            class="w-full h-56 object-cover rounded-t-2xl"
          />
          <div
            class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 rounded-t-2xl transition"
          ></div>
          <button
            @click="removeItem(item.id)"
            class="absolute top-3 right-3 bg-white p-2 rounded-full shadow hover:bg-red-100 transition"
            title="Remove from wishlist"
          >
            🗑️
          </button>
        </div>

        <!-- Product Info -->
        <div class="p-4">
          <h2 class="text-lg font-semibold text-gray-800 truncate">
            {{ item.product.title }}
          </h2>
          <p class="text-sm text-gray-500">Code: {{ item.product.code }}</p>

          <!-- Price -->
          <div class="flex items-center gap-2 mt-2">
            <span class="text-lg font-bold text-green-600">
              ${{ item.product.current_price }}
            </span>
            <span class="text-sm text-gray-400 line-through">
              ${{ item.product.original_price }}
            </span>
            <span class="text-xs text-red-500 font-medium">
              {{ item.product.discount }}% OFF
            </span>
          </div>

          <!-- Options -->
          <div class="mt-3 space-y-2">
            <div>
              <label class="text-sm font-medium text-gray-600">Color:</label>
              <select
                v-model="item.selectedColor"
                class="w-full border rounded-lg p-2 text-sm"
              >
                <option
                  v-for="color in item.product.colors"
                  :key="color"
                  :value="color"
                >
                  {{ color }}
                </option>
              </select>
            </div>
            <div>
              <label class="text-sm font-medium text-gray-600">Size:</label>
              <select
                v-model="item.selectedSize"
                class="w-full border rounded-lg p-2 text-sm"
              >
                <option value="">Select size</option>
                <option
                  v-for="size in item.product.sizes"
                  :key="size"
                  :value="size"
                >
                  {{ size }}
                </option>
              </select>
            </div>
          </div>

          <!-- Actions -->
          <div class="mt-4 flex gap-3">
            <button
              @click="moveToCart(item)"
              class="flex-1 py-2 px-4 rounded-xl text-white font-medium transition"
              :class="
                cartAdded === item.id
                  ? 'bg-green-500'
                  : 'bg-gradient-to-r from-indigo-500 to-purple-500 hover:opacity-90'
              "
            >
              {{ cartAdded === item.id ? "✨ Added!" : "🛒 Move to Cart" }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-else-if="!loading" class="text-center py-20 text-gray-500">
      <h3 class="text-xl font-semibold">✨ Your wish list is empty</h3>
      <p class="mt-2">Start adding items you love!</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

// Base API URL
const API_BASE = "http://localhost:8000/api";

const wishlist = ref([]);
const loading = ref(true);
const cartAdded = ref(null);
const customerId = 1; // mock logged-in user

// Fetch wishlist
async function fetchWishlist() {
  loading.value = true;
  try {
    const res = await axios.get(`${API_BASE}/wishlist/${customerId}`);
    wishlist.value = res.data.data || [];
  } catch (err) {
    console.error("❌ Failed to fetch wishlist:", err.response?.data || err.message);
  } finally {
    loading.value = false;
  }
}

// Add product to wishlist
async function addToWishlist(productId) {
  try {
    await axios.post(`${API_BASE}/wishlist`, {
      customer_id: customerId,
      product_id: productId,
    });
    await fetchWishlist();
  } catch (err) {
    console.error("❌ Failed to add:", err.response?.data || err.message);
  }
}

// Remove from wishlist
async function removeItem(itemId) {
  if (confirm("Remove this item from your wishlist?")) {
    try {
      await axios.delete(`${API_BASE}/wishlist/${itemId}`);
      wishlist.value = wishlist.value.filter((item) => item.id !== itemId);
    } catch (err) {
      console.error("❌ Failed to remove:", err.response?.data || err.message);
    }
  }
}

// Move to cart
async function moveToCart(item) {
  cartAdded.value = item.id;
  try {
    await axios.post(`${API_BASE}/order`, {
      customer_id: customerId,
      product_id: item.product.id,
      color: item.selectedColor,
      size: item.selectedSize,
    });
  } catch (err) {
    console.error("❌ Failed to move to cart:", err.response?.data || err.message);
  } finally {
    setTimeout(() => (cartAdded.value = null), 2000);
  }
}

// Load wishlist on page mount
onMounted(fetchWishlist);
</script>
