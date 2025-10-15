<template>
  <!-- Wrapper for Overall Page Structure -->
  <div class="page-center-wrapper w-full overflow-x-hidden flex flex-col min-h-screen font-kantumruy bg-gray-50 text-gray-800">
    
    <!-- Main Content -->
    <div class="home-content-wrapper flex-grow pt-20">
      <!-- Top Products Section -->
      <section class="top-products-section py-12 md:py-16">
        <div class="container mx-auto px-4 sm:px-6">

          <!-- Enhanced Header Layout -->
          <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-6 mb-12">
            <div class="text-center lg:text-left">
              <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4">{{ pageTitle }}</h1>
              <p class="text-lg leading-relaxed text-gray-600 max-w-2xl lg:max-w-none">
                Discover our latest men's fashion collection featuring trendy styles, premium quality, and comfortable fits for every occasion.
              </p>
            </div>
            
            <div class="flex justify-center lg:justify-end">
              <button 
                @click="isFilterOpen = true" 
                class="inline-flex items-center bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 focus:outline-none">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 2v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                Filters
                <span v-if="activeFiltersCount > 0" class="ml-2 bg-blue-800 text-xs px-2 py-1 rounded-full">
                  {{ activeFiltersCount }}
                </span>
              </button>
            </div>
          </div>

          <!-- Enhanced Filter/Sort Header -->
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 p-4 bg-white rounded-lg shadow-sm">
            <div class="flex items-center space-x-4">
              <span class="text-sm text-gray-600">{{ products.length }} items</span>
              <div v-if="activeFiltersCount > 0" class="flex items-center space-x-2">
                <span class="text-sm font-medium text-gray-700">{{ activeFiltersCount }} filters applied</span>
                <button @click="clearAllFilters" class="text-xs text-blue-600 hover:underline focus:outline-none">
                  Clear all
                </button>
              </div>
            </div>
            
            <div class="flex items-center space-x-4">
              <!-- Sort Dropdown -->
              <select v-model="sortBy" @change="handleSort" 
                class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="featured">Featured</option>
                <option value="price-low">Price: Low to High</option>
                <option value="price-high">Price: High to Low</option>
                <option value="newest">Newest</option>
                <option value="rating">Best Rating</option>
              </select>
            </div>
          </div>

          <!-- Loading State with Skeleton -->
          <div v-if="loading" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <div v-for="n in 8" :key="n" class="product-skeleton bg-white rounded-xl shadow-sm overflow-hidden">
              <div class="skeleton-image bg-gray-200 h-64 animate-pulse"></div>
              <div class="p-4 space-y-3">
                <div class="skeleton-line bg-gray-200 h-4 w-3/4 animate-pulse rounded"></div>
                <div class="skeleton-line bg-gray-200 h-4 w-1/2 animate-pulse rounded"></div>
                <div class="skeleton-line bg-gray-200 h-6 w-1/4 animate-pulse rounded"></div>
              </div>
            </div>
          </div>

          <!-- Error State -->
          <div v-else-if="error" class="text-center py-20 bg-white rounded-xl shadow-sm">
            <div class="text-red-500 text-xl mb-4 flex items-center justify-center">
              <svg class="w-8 h-8 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              {{ error }}
            </div>
            <button @click="fetchProducts" 
              class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
              Try Again
            </button>
          </div>

          <!-- Enhanced Products Grid -->
          <div v-else-if="products.length > 0" class="products-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <router-link v-for="(product, index) in products" 
              :key="`product-${product.id}`" 
              :to="`/product/${product.id}`"
              class="product-card group bg-white rounded-xl shadow-sm hover:shadow-xl overflow-hidden flex flex-col transition-all duration-300 hover:-translate-y-2 border border-gray-100 hover:border-blue-200"
              :style="{ animationDelay: `${index * 0.1}s` }">

              <!-- Product Image with Quick Actions -->
              <div class="relative overflow-hidden">
                <img :src="product.image" 
                     :alt="`${product.name} - Men's Fashion`" 
                     class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-300"
                     loading="lazy">
                
                <!-- Stock Badge -->
                <div class="absolute top-3 left-3">
                  <span class="bg-green-500 text-white text-xs font-semibold px-2 py-1 rounded-full shadow-sm">
                    IN STOCK
                  </span>
                </div>

                <!-- Quick Actions -->
                <div class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                  <button @click.prevent="toggleWishlist(product.id)" 
                    class="bg-white/90 backdrop-blur-sm p-2 rounded-full shadow-md hover:bg-white transition-colors"
                    :aria-label="`Add ${product.name} to wishlist`">
                    <svg class="w-4 h-4 text-gray-600 hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                  </button>
                </div>

                <!-- Sale Badge -->
                <div v-if="product.oldPrice" class="absolute bottom-3 left-3">
                  <span class="bg-red-500 text-white text-xs font-semibold px-2 py-1 rounded-full shadow-sm">
                    SALE
                  </span>
                </div>
              </div>

              <!-- Enhanced Product Info -->
              <div class="p-4 space-y-3 flex-grow flex flex-col">
                <div class="flex-grow">
                  <h3 class="font-semibold text-gray-900 line-clamp-2 mb-2">{{ product.name }}</h3>
                  
                  <!-- Rating & Reviews (if available) -->
                  <div v-if="product.rating" class="flex items-center space-x-2 mb-2">
                    <div class="flex items-center">
                      <svg v-for="star in 5" :key="star" 
                        class="w-4 h-4" 
                        :class="star <= product.rating ? 'text-yellow-400' : 'text-gray-300'"
                        fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                      </svg>
                    </div>
                    <span class="text-sm text-gray-600">({{ product.reviewCount || 0 }})</span>
                  </div>

                  <!-- Color Options Preview (if available) -->
                  <div v-if="product.colors && product.colors.length > 0" class="flex items-center space-x-2 mb-2">
                    <div v-for="(color, colorIndex) in product.colors.slice(0, 4)" 
                         :key="colorIndex"
                         class="w-4 h-4 rounded-full border-2 border-white shadow-sm"
                         :style="{ backgroundColor: color.hex || color }"
                         :title="color.name || color">
                    </div>
                    <span v-if="product.colors.length > 4" class="text-xs text-gray-500">
                      +{{ product.colors.length - 4 }}
                    </span>
                  </div>
                </div>

                <!-- Price Section -->
                <div class="flex items-center justify-between mt-auto">
                  <div class="flex items-center space-x-2">
                    <span class="text-xl font-bold text-gray-900">${{ product.price }}</span>
                    <span v-if="product.oldPrice" class="text-sm text-gray-500 line-through">
                      ${{ product.oldPrice }}
                    </span>
                  </div>
                  <div v-if="product.oldPrice" class="text-sm font-semibold text-green-600">
                    Save {{ Math.round((1 - product.price / product.oldPrice) * 100) }}%
                  </div>
                </div>
              </div>
            </router-link>
          </div>

          <!-- Enhanced Empty State -->
          <div v-else class="empty-state text-center py-20 bg-white rounded-xl shadow-sm">
            <div class="empty-state-icon mb-6">
              <svg class="w-16 h-16 text-gray-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
              </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No products found</h3>
            <p class="text-gray-600 mb-6">Try adjusting your filters or search terms</p>
            <button @click="clearAllFilters" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
              Clear Filters
            </button>
          </div>
        </div>
      </section>
    </div>

    <!-- Router View -->
    <router-view />

    <!-- Enhanced Filter Drawer -->
    <transition name="slide">
      <div v-if="isFilterOpen" class="fixed inset-0 bg-black bg-opacity-50 flex justify-end z-50" @click.self="isFilterOpen = false">
        <div class="bg-white w-80 h-full shadow-2xl flex flex-col">
          <!-- Header -->
          <div class="flex justify-between items-center px-6 py-4 border-b bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-900">Filters</h2>
            <button @click="isFilterOpen = false" 
              class="text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded p-1"
              aria-label="Close filters">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>

          <!-- Filter Content -->
          <div class="p-6 flex-1 overflow-y-auto space-y-6">
            <!-- Price Range -->
            <div class="filter-section">
              <label class="block text-sm font-medium text-gray-700 mb-3">Price Range</label>
              <input 
                type="range" 
                min="0" 
                max="50" 
                v-model="filters.maxPrice" 
                class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer slider" />
                <div class="flex justify-between text-sm text-gray-600 mt-2">
  <span>$0</span>
  <span class="font-medium">Up to ${{ filters.maxPrice }}</span>

  <span>$50</span>
</div>

            </div>

            <!-- Category Filter -->
            <div class="filter-section">
              <label class="block text-sm font-medium text-gray-700 mb-3">Category</label>
              <div class="space-y-2">
                <label v-for="category in availableCategories" :key="category" class="flex items-center">
                  <input type="checkbox" 
                    :value="category" 
                    v-model="filters.categories"
                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" />
                  <span class="ml-2 text-sm text-gray-700">{{ category }}</span>
                </label>
              </div>
            </div>

            <!-- Size Filter -->
            <div class="filter-section">
              <label class="block text-sm font-medium text-gray-700 mb-3">Size</label>
              <div class="grid grid-cols-4 gap-2">
                <button v-for="size in availableSizes" :key="size"
                  @click="toggleSize(size)"
                  :class="[
                    'border-2 py-2 px-3 text-sm font-medium rounded transition-colors',
                    filters.sizes.includes(size) 
                      ? 'border-blue-600 bg-blue-50 text-blue-600' 
                      : 'border-gray-300 text-gray-700 hover:border-gray-400'
                  ]">
                  {{ size }}
                </button>
              </div>
            </div>
          </div>

          <!-- Footer Actions -->
          <div class="p-6 border-t bg-gray-50 space-y-3">
            <button 
              @click="applyFilters" 
              class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
              Apply Filters
            </button>
            <button 
              @click="resetFilters" 
              class="w-full border border-gray-300 text-gray-700 py-3 rounded-lg hover:bg-gray-50 transition-colors">
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

// Reactive data
const products = ref([]);
const loading = ref(false);
const error = ref(null);
const route = useRoute();
const pageTitle = ref("Girl's Collection");
const sortBy = ref('featured');

// Filter drawer state
const isFilterOpen = ref(false);
const filters = ref({
  maxPrice: 25,
  categories: [],
  sizes: []
});

// Available filter options
const availableCategories = ref(['Shirts', 'Pants', 'Shoes', 'Accessories']);
const availableSizes = ref(['XS', 'S', 'M', 'L', 'XL', 'XXL']);

// Computed properties
const activeFiltersCount = computed(() => {
  let count = 0;
  if (filters.value.maxPrice < 25) count++;
  if (filters.value.categories.length > 0) count++;
  if (filters.value.sizes.length > 0) count++;
  return count;
});

// Utility function: only for display (NOT for API)
const toTitleCase = (str) => {
  if (!str) return '';
  return String(str)
    .replace(/[-_]+/g, ' ')
    .replace(/\s+/g, ' ')
    .trim()
    .replace(/\b\w/g, (c) => c.toUpperCase());
};

// Main functions
const fetchProducts = async () => {
  try {
    loading.value = true;
    error.value = null;
    
    const parentSlug = route.params.parentName || 'men';
    const childSlug = route.params.childName || null;

    // Use slugs for API, but pretty names for UI
    pageTitle.value = childSlug 
      ? toTitleCase(childSlug) 
      : `${toTitleCase(parentSlug)} Collection`;

    const base = '/public/products/parent-category';
    let endpoint = childSlug
      ? `${base}/${encodeURIComponent(parentSlug)}/${encodeURIComponent(childSlug)}`
      : `${base}/${encodeURIComponent(parentSlug)}`;

    // Build query parameters
    const params = new URLSearchParams();
    params.append('maxPrice', filters.value.maxPrice);
    
    if (filters.value.categories.length > 0) {
      params.append('categories', filters.value.categories.join(','));
    }
    
    if (filters.value.sizes.length > 0) {
      params.append('sizes', filters.value.sizes.join(','));
    }
    
    if (sortBy.value !== 'featured') {
      params.append('sort', sortBy.value);
    }

    endpoint += `?${params.toString()}`;

    const response = await api.get(endpoint);

    // Handle backend "subcategory not found" gracefully
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

// Filter functions
const applyFilters = () => {
  isFilterOpen.value = false;
  fetchProducts();
};

const clearAllFilters = () => {
  filters.value = {
    maxPrice: 25,
    categories: [],
    sizes: []
  };
  sortBy.value = 'featured';
  fetchProducts();
};

const resetFilters = () => {
  clearAllFilters();
  isFilterOpen.value = false;
};

const toggleSize = (size) => {
  const index = filters.value.sizes.indexOf(size);
  if (index > -1) {
    filters.value.sizes.splice(index, 1);
  } else {
    filters.value.sizes.push(size);
  }
};

const handleSort = () => {
  fetchProducts();
};

const toggleWishlist = (productId) => {
  // Implement wishlist functionality
  console.log('Toggle wishlist for product:', productId);
};

// Lifecycle hooks
onMounted(fetchProducts);
watch(() => [route.params.parentName, route.params.childName], fetchProducts);
</script>

<style scoped>
/* Product Card Animations */
.product-card {
  opacity: 0;
  animation: fadeInUp 0.6s ease-out forwards;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Line clamp utility */
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Slider styling */
.slider::-webkit-slider-thumb {
  appearance: none;
  height: 20px;
  width: 20px;
  border-radius: 50%;
  background: #2563eb;
  cursor: pointer;
  box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.slider::-moz-range-thumb {
  height: 20px;
  width: 20px;
  border-radius: 50%;
  background: #2563eb;
  cursor: pointer;
  border: none;
  box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

/* Slide transition for filter drawer */
.slide-enter-from,
.slide-leave-to {
  transform: translateX(100%);
}

.slide-enter-to,
.slide-leave-from {
  transform: translateX(0);
}

.slide-enter-active,
.slide-leave-active {
  transition: transform 0.3s ease;
}

/* Focus styles */
.focus-ring:focus {
  outline: none;
  ring: 2px;
  ring-color: #3b82f6;
  ring-offset: 2px;
}

/* Skeleton loading styles */
.skeleton-image,
.skeleton-line {
  background: linear-gradient(90deg, #f0f0f0 25%, transparent 37%, #f0f0f0 63%);
  background-size: 400% 100%;
  animation: skeleton-loading 1.4s ease-in-out infinite;
}

@keyframes skeleton-loading {
  0% {
    background-position: 100% 50%;
  }
  100% {
    background-position: -100% 50%;
  }
}

/* Responsive improvements */
@media (max-width: 640px) {
  .container {
    padding-left: 1rem;
    padding-right: 1rem;
  }
  
  .filter-drawer {
    width: 100vw;
  }
}
</style>