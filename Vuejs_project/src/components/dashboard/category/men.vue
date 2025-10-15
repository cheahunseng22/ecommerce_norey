<template>
  <div class="page-center-wrapper w-full overflow-x-hidden flex flex-col min-h-screen font-kantumruy bg-gray-50 text-gray-800">

    <!-- Main Content -->
    <div class="home-content-wrapper flex-grow pt-20">
      <section class="top-products-section py-12 md:py-16">
        <div class="container mx-auto px-4 sm:px-6">

          <!-- Header -->
          <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-6 mb-12">
            <div class="text-center lg:text-left">
              <h1 class="text-xl text-gray-900 mb-4">{{ pageTitle }}</h1>
              <p class="text-l leading-relaxed text-gray-600 max-w-2xl">
                Discover our latest men's fashion collection featuring trendy styles, premium quality, and comfortable fits for every occasion.
              </p>
            </div>

            <div class="flex justify-center lg:justify-end">
              <button 
                @click="isFilterOpen = true" 
                class="inline-flex items-center bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 2v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                Filters
                <span v-if="activeFiltersCount > 0" class="ml-2 bg-blue-800 text-xs px-2 py-1 rounded-full">
                  {{ activeFiltersCount }}
                </span>
              </button>
            </div>
          </div>

          <!-- Filter/Sort Bar -->
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 p-4 bg-white rounded-lg shadow-sm">
            <div class="flex items-center space-x-4">
              <span class="text-sm text-gray-600">{{ products.length }} items</span>
              <div v-if="activeFiltersCount > 0" class="flex items-center space-x-2">
                <span class="text-sm font-medium text-gray-700">{{ activeFiltersCount }} filters applied</span>
                <button @click="clearAllFilters" class="text-xs text-blue-600 hover:underline">
                  Clear all
                </button>
              </div>
            </div>

            <div class="flex items-center space-x-4">
              <select v-model="sortBy" @change="handleSort" 
                class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                <option value="featured">Featured</option>
                <option value="price-low">Price: Low to High</option>
                <option value="price-high">Price: High to Low</option>
                <option value="newest">Newest</option>
                <option value="rating">Best Rating</option>
              </select>
            </div>
          </div>

          <!-- Loading -->
          <div v-if="loading" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <div v-for="n in 8" :key="n" class="bg-white rounded-xl shadow-sm overflow-hidden">
              <div class="bg-gray-200 h-64 animate-pulse"></div>
              <div class="p-4 space-y-3">
                <div class="bg-gray-200 h-4 w-3/4 animate-pulse rounded"></div>
                <div class="bg-gray-200 h-4 w-1/2 animate-pulse rounded"></div>
                <div class="bg-gray-200 h-6 w-1/4 animate-pulse rounded"></div>
              </div>
            </div>
          </div>

          <!-- Error -->
          <div v-else-if="error" class="text-center py-20 bg-white rounded-xl shadow-sm">
            <div class="text-red-500 text-xl mb-4">{{ error }}</div>
            <button @click="fetchProducts" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
              Try Again
            </button>
          </div>

          <!-- Product Grid -->
          <div v-else-if="products.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <router-link v-for="(product, index) in products" :key="product.id"
              :to="`/product/${product.id}`"
              class="group bg-white rounded-xl shadow-sm hover:shadow-xl overflow-hidden flex flex-col transition hover:-translate-y-2 border border-gray-100 hover:border-blue-200"
              :style="{ animationDelay: `${index * 0.1}s` }">

              <div class="relative overflow-hidden">
                <img :src="product.image" :alt="product.name"
                     class="w-full h-64 object-cover group-hover:scale-105 transition" loading="lazy">
                <div class="absolute top-3 left-3">
                  <span class="bg-green-500 text-white text-xs px-2 py-1 rounded-full shadow-sm">IN STOCK</span>
                </div>
              </div>

              <div class="p-4 space-y-3 flex-grow flex flex-col">
                <h3 class="font-semibold text-gray-900 line-clamp-2 mb-2">{{ product.name }}</h3>
                <div class="flex items-center justify-between mt-auto">
                  <div class="flex items-center space-x-2">
                    <span class="text-xl font-bold text-gray-900">${{ product.price }}</span>
                    <span v-if="product.oldPrice" class="text-sm text-gray-500 line-through">${{ product.oldPrice }}</span>
                  </div>
                </div>
              </div>
            </router-link>
          </div>

          <!-- Empty -->
          <div v-else class="text-center py-20 bg-white rounded-xl shadow-sm">
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No products found</h3>
            <button @click="clearAllFilters" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
              Clear Filters
            </button>
          </div>
        </div>
      </section>
    </div>

    <!-- Filter Drawer -->
    <transition name="slide">
      <div v-if="isFilterOpen" class="fixed inset-0 z-50 flex justify-end " @click.self="isFilterOpen = false">
        <div class="w-80 max-w-sm h-full bg-white flex flex-col shadow-xl">

          <!-- Header -->
          <div class="flex items-center justify-between px-4 py-3 border-b">
            <h2 class="text-lg font-semibold">Filters</h2>
            <button @click="isFilterOpen = false" class="text-gray-500 hover:text-gray-700">✕</button>
          </div>

          <!-- Content -->
          <div class="flex-1 overflow-y-auto px-4 py-5 space-y-6">

            <!-- Sort -->
            <div>
              <h3 class="text-sm font-medium mb-2">Sort By</h3>
              <select v-model="filters.sort" class="w-full border rounded-md px-2 py-2 text-sm">
                <option v-for="option in sortOptions" :key="option">{{ option }}</option>
              </select>
            </div>

            <!-- Price -->
            <div>
              <h3 class="text-sm font-medium mb-2">Max Price: ${{ filters.maxPrice }}</h3>
              <input type="range" min="0" max="50" v-model="filters.maxPrice" class="w-full">
              <div class="flex justify-between text-xs text-gray-500 mt-1">
                <span>$0</span><span>$50</span>
              </div>
            </div>

            <!-- Categories -->
            <div>
              <h3 class="text-sm font-medium mb-2">Categories</h3>
              <div class="space-y-1">
                <label v-for="cat in availableCategories" :key="cat" class="flex items-center text-sm">
                  <input type="checkbox" v-model="filters.categories" :value="cat" class="mr-2">
                  {{ cat }}
                </label>
              </div>
            </div>

            <!-- Sizes -->
            <div>
              <h3 class="text-sm font-medium mb-2">Sizes</h3>
              <div class="grid grid-cols-4 gap-2">
                <button 
                  v-for="size in availableSizes" :key="size"
                  @click="toggleSize(size)"
                  :class="[
                    'border rounded py-1 text-sm',
                    filters.sizes.includes(size) ? 'bg-blue-50 border-blue-500 text-blue-600' : 'border-gray-300 text-gray-700'
                  ]">
                  {{ size }}
                </button>
              </div>
            </div>
          </div>

          <!-- Footer -->
          <div class="px-4 py-3 border-t bg-gray-50 space-y-2">
            <button @click="applyFilters" class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700">
              Apply Filters
            </button>
            <button @click="resetFilters" class="w-full border border-gray-300 text-gray-700 py-2 rounded-md hover:bg-gray-100">
              Reset All
            </button>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { onMounted, ref, watch, computed } from 'vue';
import { useRoute } from 'vue-router';
import api from '../../../axios/Axios';

const products = ref([]);
const loading = ref(false);
const error = ref(null);
const route = useRoute();
const pageTitle = ref("Women's Collection");
const sortBy = ref('featured');

const isFilterOpen = ref(false);
const filters = ref({
  sort: 'Product Recommend',
  maxPrice: 25,
  categories: [],
  sizes: []
});
const sortOptions = [
  'Product Recommend',
  'New Items',
  'Price (High First)',
  'Price (Low First)',
  'Discount (High First)',
  'Discount (Low First)'
];
const availableCategories = ref(['Shirts', 'Pants', 'Shoes', 'Accessories']);
const availableSizes = ref(['XS', 'S', 'M', 'L', 'XL', 'XXL']);

const activeFiltersCount = computed(() => {
  let count = 0;
  if (filters.value.maxPrice < 25) count++;
  if (filters.value.categories.length > 0) count++;
  if (filters.value.sizes.length > 0) count++;
  return count;
});

const toTitleCase = (str) => str?.replace(/[-_]+/g, ' ').replace(/\b\w/g, c => c.toUpperCase());

const fetchProducts = async () => {
  try {
    loading.value = true;
    error.value = null;

    const parentSlug = route.params.parentName || 'men';
    const childSlug = route.params.childName || null;

    pageTitle.value = childSlug ? toTitleCase(childSlug) : `${toTitleCase(parentSlug)} Collection`;

    const base = '/public/products/parent-category';
    let endpoint = childSlug
      ? `${base}/${encodeURIComponent(parentSlug)}/${encodeURIComponent(childSlug)}`
      : `${base}/${encodeURIComponent(parentSlug)}`;

    const params = new URLSearchParams();
    params.append('maxPrice', filters.value.maxPrice);
    if (filters.value.categories.length > 0) params.append('categories', filters.value.categories.join(','));
    if (filters.value.sizes.length > 0) params.append('sizes', filters.value.sizes.join(','));
    if (sortBy.value !== 'featured') params.append('sort', sortBy.value);

    endpoint += `?${params.toString()}`;
    const response = await api.get(endpoint);

    if (response.data?.success === false) {
      error.value = response.data.message || 'No products found.';
      products.value = [];
      return;
    }

    products.value = response.data?.data || [];
    if (products.value.length === 0 && !activeFiltersCount.value) {
      error.value = 'No products found in this category.';
    }
  } catch (err) {
    console.error('Error fetching products:', err);
    error.value = 'Failed to load products. Please try again.';
  } finally {
    loading.value = false;
  }
};

const applyFilters = () => {
  isFilterOpen.value = false;
  fetchProducts();
};

const clearAllFilters = () => {
  filters.value = { maxPrice: 25, categories: [], sizes: [] };
  sortBy.value = 'featured';
  fetchProducts();
};

const resetFilters = () => {
  clearAllFilters();
  isFilterOpen.value = false;
};

const toggleSize = (size) => {
  const index = filters.value.sizes.indexOf(size);
  if (index > -1) filters.value.sizes.splice(index, 1);
  else filters.value.sizes.push(size);
};

const handleSort = () => fetchProducts();

onMounted(fetchProducts);
watch(() => [route.params.parentName, route.params.childName], fetchProducts);
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
.slide-enter-from,
.slide-leave-to { transform: translateX(100%); }
.slide-enter-to,
.slide-leave-from { transform: translateX(0); }
.slide-enter-active,
.slide-leave-active { transition: transform 0.3s ease; }
</style>
