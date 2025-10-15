<template>
  <div class="min-h-screen bg-gray-50">
    

    <!-- Sale Banner -->
    <div class="bg-gradient-to-r from-orange-400 via-red-400 to-yellow-400 relative overflow-hidden">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 text-center">
        <div class="relative z-10">
          <h2 class="text-white text-sm font-medium mb-2">PCHUM BEN SALE UP TO</h2>
          <div class="text-white font-bold text-6xl sm:text-8xl mb-4">80%OFF</div>
          <p class="text-white text-sm">T&amp;Cs APPLY</p>
        </div>
        <div class="absolute inset-0 opacity-10">
          <div class="grid grid-cols-12 gap-1 h-full">
            <div v-for="i in 144" :key="i" class="border border-white/20"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Product Filters & Sorting -->
    <div class="bg-white border-b">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between space-y-4 sm:space-y-0">
          <!-- Category & Count -->
          <div class="flex items-center space-x-6">
            <h3 class="text-lg font-medium">{{ activeCategory }} ({{ filteredProducts.length }} Items)</h3>

            <button
              @click="showFilters = !showFilters"
              class="flex items-center space-x-2 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50"
            >
              <Filter class="h-4 w-4" />
              <span>Filter</span>
            </button>

            <!-- Gender Filters -->
            <div class="hidden sm:flex items-center space-x-4">
              <button
                @click="selectedGender = ''"
                :class="[
                  'px-3 py-1 rounded-full text-sm',
                  !selectedGender ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
                ]"
              >
                ALL ({{ products.length }})
              </button>
              <button
                @click="selectedGender = 'women'"
                :class="[
                  'px-3 py-1 rounded-full text-sm',
                  selectedGender === 'women' ? 'bg-pink-100 text-pink-800' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
                ]"
              >
                WOMEN ({{ getGenderCount('women') }})
              </button>
              <button
                @click="selectedGender = 'men'"
                :class="[
                  'px-3 py-1 rounded-full text-sm',
                  selectedGender === 'men' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
                ]"
              >
                MEN ({{ getGenderCount('men') }})
              </button>
            </div>
          </div>

          <!-- Sort Dropdown -->
          <div class="relative">
            <select
              v-model="sortBy"
              class="appearance-none bg-white border border-gray-300 rounded-lg px-4 py-2 pr-8 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              <option value="recommended">Sort by: Product Recommend</option>
              <option value="price-low">Price: Low to High</option>
              <option value="price-high">Price: High to Low</option>
              <option value="rating">Highest Rated</option>
              <option value="newest">Newest First</option>
            </select>
            <ChevronDown class="absolute right-2 top-2.5 h-4 w-4 text-gray-400 pointer-events-none" />
          </div>
        </div>
      </div>
    </div>

    <!-- Products Grid -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div v-if="loading" class="flex justify-center items-center py-20">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
      </div>
       <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <router-link v-for="product in products" :key="`product-${product.id}`" :to="`/product/${product.id}`"
              class="product-item group bg-white rounded-lg shadow-md overflow-hidden flex flex-col hover:shadow-lg transition-shadow">
              <div class="relative h-96 overflow-hidden"> <!-- shorter height -->
  <img
    :alt="`Product ${product.id} image`"
    :src="product.image"
    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
  />
</div>

              <div class="p-4 flex flex-col flex-grow">
                <b class="text-lg font-semibold text-pink-700">${{ product.price }}</b>
                <div class="text-xs text-green-600 font-medium my-1" v-if="product.is_active">
                  IN STOCK
                </div>
                <p class="text-sm text-gray-600 leading-relaxed flex-grow mb-2 h-10 overflow-hidden text-ellipsis group-hover:whitespace-normal group-hover:overflow-visible group-hover:h-auto"
                  style="display: -webkit-box; line-clamp: 2; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                  {{ product.name }}
                </p>
               
              </div>
            </router-link>
          </div>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import { Search, Filter, ShoppingCart, Heart, Bell, ChevronDown } from "lucide-vue-next";
import api from "../../../axios/Axios";


const products = ref([]);
const loading = ref(true);
const activeCategory = ref("Zara"); // brand name
const selectedGender = ref("");
const searchQuery = ref("");
const sortBy = ref("recommended");
const showFilters = ref(false);

const wishlist = ref(new Set());

// ✅ Fetch products by brand
const fetchProducts = async () => {
  loading.value = true;
  try {
    const response = await api.get(`/public/brands/${activeCategory.value}`);
    const data = response.data?.data ?? [];
    products.value = Array.isArray(data) ? data : [];
  } catch (error) {
    console.error("Failed to fetch products:", error.response?.data || error.message);
    products.value = [];
  } finally {
    loading.value = false;
  }
};

// Run when component mounts
onMounted(fetchProducts);

// Computed filtered products
const filteredProducts = computed(() => {
  let filtered = [...products.value];

  if (selectedGender.value) {
    filtered = filtered.filter((p) => p.gender === selectedGender.value);
  }

  if (searchQuery.value) {
    filtered = filtered.filter(
      (p) =>
        p.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        p.brand.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
  }

  switch (sortBy.value) {
    case "price-low":
      filtered.sort((a, b) => a.price - b.price);
      break;
    case "price-high":
      filtered.sort((a, b) => b.price - a.price);
      break;
    case "rating":
      filtered.sort((a, b) => b.rating - a.rating);
      break;
    case "newest":
      filtered.sort((a, b) => Number(b.isNew) - Number(a.isNew));
      break;
    default:
      filtered.sort(
        (a, b) =>
          b.rating * (b.isSale ? 1.2 : 1) - a.rating * (a.isSale ? 1.2 : 1)
      );
  }

  return filtered;
});




function getGenderCount(gender) {
  return products.value.filter((p) => p.gender === gender).length;
}

function setGender(category) {
  if (category.toLowerCase() === "women" || category.toLowerCase() === "men") {
    selectedGender.value = category.toLowerCase();
  } else {
    selectedGender.value = "";
  }
}
</script>

