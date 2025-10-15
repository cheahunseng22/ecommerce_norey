<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="bg-white shadow-sm border-b">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-4">
            <!-- Dynamic Brand Logo -->
            <div class="h-10 w-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
              <span class="text-white font-bold text-lg">{{ brandInfo?.name?.charAt(0) || 'B' }}</span>
            </div>
            <div>
              <h1 class="text-2xl font-bold text-gray-900">{{ brandInfo?.name || 'Brand' }} Products</h1>
              <p class="text-sm text-gray-600">Discover the latest collection</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
              {{ totalProducts }} items
            </span>
          </div>
          
          <!-- Quick Category Links -->
          <div class="hidden md:flex items-center space-x-4">
            <router-link
              :to="`/brand/${brandId}/women`"
              class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 hover:text-pink-600 transition-colors duration-200"
              :class="{ 'bg-pink-50 text-pink-600 border-pink-300': selectedGender === 'women' }"
            >
              <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 2C7.79 2 6 3.79 6 6c0 2.21 1.79 4 4 4s4-1.79 4-4c0-2.21-1.79-4-4-4zm0 6c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
              </svg>
              Women
              <span class="ml-2 bg-pink-100 text-pink-600 text-xs px-2 py-1 rounded-full">{{ getGenderCount('women') }}</span>
            </router-link>
            <router-link
              :to="`/brand/${brandId}/men`"
              class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 hover:text-blue-600 transition-colors duration-200"
              :class="{ 'bg-blue-50 text-blue-600 border-blue-300': selectedGender === 'men' }"
            >
              <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 2C7.79 2 6 3.79 6 6c0 2.21 1.79 4 4 4s4-1.79 4-4c0-2.21-1.79-4-4-4zm0 6c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
              </svg>
              Men
              <span class="ml-2 bg-blue-100 text-blue-600 text-xs px-2 py-1 rounded-full">{{ getGenderCount('men') }}</span>
            </router-link>
          </div>

          <!-- Search Input -->
          <div class="flex items-center space-x-4">
            <div class="relative">
              <input
                v-model="searchQuery"
                type="text"
                :placeholder="`Search ${brandInfo?.name || 'brand'} products...`"
                class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                @input="debouncedSearch"
              />
              <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
              <button
                v-if="searchQuery"
                @click="searchQuery = ''"
                class="absolute right-3 top-2.5 h-5 w-5 text-gray-400 hover:text-gray-600"
              >
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
          </div>
        </div>

        <!-- Mobile Category Links -->
        <div class="md:hidden mt-4 flex space-x-2">
          <router-link
            :to="`/brand/${brandId}/women`"
            class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50"
            :class="{ 'bg-pink-50 text-pink-600 border-pink-300': selectedGender === 'women' }"
          >
            Women ({{ getGenderCount('women') }})
          </router-link>
          <router-link
            :to="`/brand/${brandId}/men`"
            class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50"
            :class="{ 'bg-blue-50 text-blue-600 border-blue-300': selectedGender === 'men' }"
          >
            Men ({{ getGenderCount('men') }})
          </router-link>
        </div>

        <!-- Active Filters Display -->
        <div v-if="hasActiveFilters" class="mt-4 flex flex-wrap gap-2">
          <span class="text-sm text-gray-600">Active filters:</span>
          <span v-if="selectedGender" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
            {{ selectedGender === 'women' ? 'Women' : 'Men' }}
            <button @click="selectedGender = ''" class="ml-1 hover:text-gray-600">×</button>
          </span>
          <span v-if="selectedParentCategory" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
            {{ getParentCategoryName(selectedParentCategory) }}
            <button @click="selectedParentCategory = ''" class="ml-1 hover:text-gray-600">×</button>
          </span>
          <span v-if="selectedPriceRange" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
            {{ getPriceRangeLabel(selectedPriceRange) }}
            <button @click="selectedPriceRange = ''" class="ml-1 hover:text-gray-600">×</button>
          </span>
          <span v-if="selectedRating" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
            {{ selectedRating }}+ stars
            <button @click="selectedRating = ''" class="ml-1 hover:text-gray-600">×</button>
          </span>
        </div>
      </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="lg:grid lg:grid-cols-4 lg:gap-8">
        <!-- Filters Sidebar -->
        <div class="lg:col-span-1">
          <div class="bg-white rounded-lg shadow-sm p-6 sticky top-6">
            <div class="flex items-center justify-between mb-6">
              <h2 class="text-lg font-semibold text-gray-900">Filters</h2>
              <button
                @click="clearFilters"
                class="text-sm text-blue-600 hover:text-blue-800 disabled:text-gray-400 disabled:cursor-not-allowed"
                :disabled="!hasActiveFilters"
              >
                Clear All
              </button>
            </div>
            
            <!-- Gender Filter -->
            <div class="mb-6">
              <h3 class="text-sm font-medium text-gray-900 mb-3">Gender</h3>
              <div class="space-y-2">
                <label class="flex items-center cursor-pointer">
                  <input
                    type="radio"
                    name="gender"
                    value=""
                    v-model="selectedGender"
                    @change="applyFilters"
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                  />
                  <span class="ml-3 text-sm text-gray-600">All</span>
                  <span class="ml-auto text-xs text-gray-500">({{ totalProducts }})</span>
                </label>
                <label class="flex items-center cursor-pointer">
                  <input
                    type="radio"
                    name="gender"
                    value="women"
                    v-model="selectedGender"
                    @change="applyFilters"
                    class="h-4 w-4 text-pink-600 focus:ring-pink-500 border-gray-300"
                  />
                  <span class="ml-3 text-sm text-gray-600">Women</span>
                  <span class="ml-auto text-xs text-gray-500">({{ getGenderCount('women') }})</span>
                </label>
                <label class="flex items-center cursor-pointer">
                  <input
                    type="radio"
                    name="gender"
                    value="men"
                    v-model="selectedGender"
                    @change="applyFilters"
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                  />
                  <span class="ml-3 text-sm text-gray-600">Men</span>
                  <span class="ml-auto text-xs text-gray-500">({{ getGenderCount('men') }})</span>
                </label>
              </div>
            </div>

            <!-- Parent Categories Filter -->
            <div class="mb-6">
              <h3 class="text-sm font-medium text-gray-900 mb-3">Categories</h3>
              <div class="space-y-2 max-h-40 overflow-y-auto">
                <label class="flex items-center cursor-pointer">
                  <input
                    type="radio"
                    name="parentCategory"
                    value=""
                    v-model="selectedParentCategory"
                    @change="applyFilters"
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                  />
                  <span class="ml-3 text-sm text-gray-600">All Categories</span>
                  <span class="ml-auto text-xs text-gray-500">({{ totalProducts }})</span>
                </label>
                <label 
                  v-for="category in parentCategories" 
                  :key="category.id"
                  class="flex items-center cursor-pointer"
                >
                  <input
                    type="radio"
                    name="parentCategory"
                    :value="category.id"
                    v-model="selectedParentCategory"
                    @change="applyFilters"
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                  />
                  <span class="ml-3 text-sm text-gray-600 truncate">{{ category.name }}</span>
                  <span class="ml-auto text-xs text-gray-500">({{ getCategoryProductCount(category.id) }})</span>
                </label>
              </div>
            </div>

            <!-- Price Range Filter -->
            <div class="mb-6">
              <h3 class="text-sm font-medium text-gray-900 mb-3">Price Range</h3>
              <div class="space-y-2">
                <label class="flex items-center cursor-pointer">
                  <input
                    type="radio"
                    name="priceRange"
                    value=""
                    v-model="selectedPriceRange"
                    @change="applyFilters"
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                  />
                  <span class="ml-3 text-sm text-gray-600">All Prices</span>
                </label>
                <label 
                  v-for="range in priceRanges" 
                  :key="range.value"
                  class="flex items-center cursor-pointer"
                >
                  <input
                    type="radio"
                    name="priceRange"
                    :value="range.value"
                    v-model="selectedPriceRange"
                    @change="applyFilters"
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                  />
                  <span class="ml-3 text-sm text-gray-600">{{ range.label }}</span>
                  <span class="ml-auto text-xs text-gray-500">({{ getPriceRangeCount(range.value) }})</span>
                </label>
              </div>
            </div>

            <!-- Rating Filter -->
            <div class="mb-6">
              <h3 class="text-sm font-medium text-gray-900 mb-3">Rating</h3>
              <div class="space-y-2">
                <label class="flex items-center cursor-pointer">
                  <input
                    type="radio"
                    name="rating"
                    value=""
                    v-model="selectedRating"
                    @change="applyFilters"
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                  />
                  <span class="ml-3 text-sm text-gray-600">All Ratings</span>
                </label>
                <label v-for="rating in [5,4,3,2,1]" :key="rating" class="flex items-center cursor-pointer">
                  <input
                    type="radio"
                    name="rating"
                    :value="rating"
                    v-model="selectedRating"
                    @change="applyFilters"
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                  />
                  <span class="ml-3 flex items-center">
                    <div class="flex items-center">
                      <span v-for="star in rating" :key="`filled-${star}`" class="text-yellow-400 text-sm">⭐</span>
                      <span v-for="emptyStar in (5-rating)" :key="`empty-${emptyStar}`" class="text-gray-300 text-sm">⭐</span>
                    </div>
                    <span class="ml-2 text-sm text-gray-600">{{ rating }}+ stars</span>
                  </span>
                  <span class="ml-auto text-xs text-gray-500">({{ getRatingCount(rating) }})</span>
                </label>
              </div>
            </div>
          </div>
        </div>

        <!-- Products Grid -->
        <div class="lg:col-span-3 mt-6 lg:mt-0">
          <!-- Sort and View Options -->
          <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 bg-white rounded-lg shadow-sm p-4 space-y-4 sm:space-y-0">
            <div class="flex items-center space-x-4">
              <span class="text-sm text-gray-600">
                Showing {{ filteredProducts.length }} of {{ totalProducts }} products
              </span>
              <span v-if="selectedGender" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                    :class="selectedGender === 'women' ? 'bg-pink-100 text-pink-800' : 'bg-blue-100 text-blue-800'">
                {{ selectedGender === 'women' ? 'Women' : 'Men' }}
              </span>
            </div>
            <div class="flex items-center space-x-4">
              <select
                v-model="sortBy"
                @change="applySortAndFilter"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="name">Sort by Name</option>
                <option value="price-low">Price: Low to High</option>
                <option value="price-high">Price: High to Low</option>
                <option value="rating">Highest Rated</option>
                <option value="newest">Newest First</option>
              </select>
              <!-- Grid/List View Toggle -->
              <div class="flex items-center border border-gray-300 rounded-lg">
                <button
                  @click="viewMode = 'grid'"
                  :class="[
                    'p-2 text-sm',
                    viewMode === 'grid' ? 'bg-blue-500 text-white' : 'text-gray-600 hover:text-gray-900'
                  ]"
                  class="rounded-l-lg"
                >
                  <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                  </svg>
                </button>
                <button
                  @click="viewMode = 'list'"
                  :class="[
                    'p-2 text-sm',
                    viewMode === 'list' ? 'bg-blue-500 text-white' : 'text-gray-600 hover:text-gray-900'
                  ]"
                  class="rounded-r-lg"
                >
                  <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                  </svg>
                </button>
              </div>
            </div>
          </div>

          <!-- Loading State -->
          <div v-if="loading" class="flex justify-center items-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
          </div>

          <!-- Products Grid/List -->
          <div v-else-if="filteredProducts.length > 0">
            <!-- Grid View -->
            <div v-if="viewMode === 'grid'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
              <div
                v-for="product in sortedFilteredProducts"
                :key="product.id"
                class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden group cursor-pointer"
                @click="viewProduct(product)"
              >
                <div class="relative">
                  <img
                    :src="getLaravelImageUrl(product.image)"
                    :alt="product.name"
                    class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-200"
                    @error="handleImageError"
                  />
                  <div class="absolute top-2 right-2">
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-white/90 backdrop-blur-sm text-gray-800">
                      {{ brandInfo?.name || 'Brand' }}
                    </span>
                  </div>
                  <div class="absolute top-2 left-2">
                    <span v-if="getProductGender(product)" 
                          class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                          :class="getProductGender(product) === 'women' ? 'bg-pink-100 text-pink-800' : 'bg-blue-100 text-blue-800'">
                      {{ getProductGender(product) === 'women' ? 'Women' : 'Men' }}
                    </span>
                  </div>
                  <div v-if="product.rating" class="absolute bottom-2 left-2 flex items-center bg-white/90 backdrop-blur-sm rounded-full px-2 py-1">
                    <span class="text-yellow-400 text-sm">⭐</span>
                    <span class="ml-1 text-xs font-medium">{{ formatRating(product.rating) }}</span>
                  </div>
                </div>
                
                <div class="p-4">
                  <div class="flex items-start justify-between">
                    <div class="flex-1">
                      <h3 class="text-lg font-medium text-gray-900 group-hover:text-blue-600 transition-colors duration-200 line-clamp-1">
                        {{ product.name }}
                      </h3>
                      <p class="text-sm text-gray-500 mt-1">{{ getParentCategoryNameById(product.category_id) }}</p>
                    </div>
                  </div>
                  
                  <p class="text-sm text-gray-600 mt-2 line-clamp-2">{{ product.description }}</p>
                  
                  <div class="mt-4 flex items-center justify-between">
                    <div class="flex items-center">
                      <span class="text-xl font-bold text-gray-900">${{ formatPrice(product.price) }}</span>
                      <span v-if="product.size" class="ml-2 text-sm text-gray-500">{{ product.size }}</span>
                    </div>
                    <button
                      @click.stop="viewProduct(product)"
                      class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200 text-sm font-medium"
                    >
                      View Details
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- List View -->
            <div v-else class="space-y-4">
              <div
                v-for="product in sortedFilteredProducts"
                :key="product.id"
                class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden cursor-pointer"
                @click="viewProduct(product)"
              >
                <div class="flex">
                  <div class="flex-shrink-0">
                    <img
                      :src="getLaravelImageUrl(product.image)"
                      :alt="product.name"
                      class="h-32 w-32 object-cover"
                      @error="handleImageError"
                    />
                  </div>
                  <div class="flex-1 p-4">
                    <div class="flex items-start justify-between">
                      <div class="flex-1">
                        <h3 class="text-lg font-medium text-gray-900 hover:text-blue-600">{{ product.name }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ getParentCategoryNameById(product.category_id) }}</p>
                        <p class="text-sm text-gray-600 mt-2">{{ product.description }}</p>
                        
                        <div class="flex items-center mt-3 space-x-4">
                          <span v-if="getProductGender(product)" 
                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                                :class="getProductGender(product) === 'women' ? 'bg-pink-100 text-pink-800' : 'bg-blue-100 text-blue-800'">
                            {{ getProductGender(product) === 'women' ? 'Women' : 'Men' }}
                          </span>
                          <div v-if="product.rating" class="flex items-center">
                            <span class="text-yellow-400 text-sm">⭐</span>
                            <span class="ml-1 text-xs font-medium">{{ formatRating(product.rating) }}</span>
                          </div>
                          <span v-if="product.size" class="text-sm text-gray-500">Size: {{ product.size }}</span>
                        </div>
                      </div>
                      <div class="flex flex-col items-end space-y-2">
                        <span class="text-xl font-bold text-gray-900">${{ formatPrice(product.price) }}</span>
                        <button
                          @click.stop="viewProduct(product)"
                          class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200 text-sm font-medium"
                        >
                          View Details
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- No Products Found -->
          <div v-else class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2 2v-5m16 0h-6m6 0h-6m0 0l-5 5m5-5l-5-5" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No {{ brandInfo?.name || 'brand' }} products found</h3>
            <p class="mt-1 text-sm text-gray-500">Try adjusting your filters or search terms.</p>
            <button
              @click="clearFilters"
              class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-blue-600 bg-blue-100 hover:bg-blue-200"
            >
              Clear all filters
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import api from '../axios/Axios';

const router = useRouter();
const route = useRoute();

// Props
const props = defineProps({
  brandId: {
    type: [String, Number],
    default: null
  },
  gender: {
    type: String,
    default: ''
  }
});

// Reactive data
const products = ref([]);
const brandInfo = ref(null);
const parentCategories = ref([]);
const categories = ref([]);
const loading = ref(true);
const searchQuery = ref('');
const selectedParentCategory = ref('');
const selectedPriceRange = ref('');
const selectedRating = ref('');
const selectedGender = ref(props.gender || '');
const sortBy = ref('name');
const viewMode = ref('grid');

// Price ranges for filtering
const priceRanges = [
  { value: '0-50', label: '$0 - $50' },
  { value: '50-100', label: '$50 - $100' },
  { value: '100-200', label: '$100 - $200' },
  { value: '200+', label: '$200+' }
];

// Watch for route changes
watch(() => route.params, (newParams) => {
  if (newParams.gender !== selectedGender.value) {
    selectedGender.value = newParams.gender || '';
  }
}, { immediate: true });

// Computed properties
const totalProducts = computed(() => products.value.length);

const hasActiveFilters = computed(() => {
  return selectedGender.value || 
         selectedParentCategory.value || 
         selectedPriceRange.value || 
         selectedRating.value ||
         searchQuery.value;
});

const filteredProducts = computed(() => {
  let filtered = [...products.value];

  // Search filter
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    filtered = filtered.filter(product => 
      product.name.toLowerCase().includes(query) ||
      product.description?.toLowerCase().includes(query)
    );
  }

  // Gender filter (based on parent category)
  if (selectedGender.value) {
    filtered = filtered.filter(product => {
      const category = categories.value.find(cat => cat.id === product.category_id);
      if (!category) return false;
      
      const parentCategory = parentCategories.value.find(parent => parent.id === category.parent_category_id);
      if (!parentCategory) return false;
      
      const categoryName = parentCategory.name.toLowerCase();
      return categoryName.includes(selectedGender.value);
    });
  }

  // Parent category filter
  if (selectedParentCategory.value) {
    filtered = filtered.filter(product => {
      const category = categories.value.find(cat => cat.id === product.category_id);
      return category?.parent_category_id == selectedParentCategory.value;
    });
  }

  // Price range filter
  if (selectedPriceRange.value) {
    filtered = filtered.filter(product => {
      const price = parseFloat(product.price);
      switch (selectedPriceRange.value) {
        case '0-50':
          return price >= 0 && price <= 50;
        case '50-100':
          return price > 50 && price <= 100;
        case '100-200':
          return price > 100 && price <= 200;
        case '200+':
          return price > 200;
        default:
          return true;
      }
    });
  }

  // Rating filter
  if (selectedRating.value) {
    const minRating = parseInt(selectedRating.value);
    filtered = filtered.filter(product => 
      product.rating && product.rating >= minRating
    );
  }

  return filtered;
});

const sortedFilteredProducts = computed(() => {
  const sorted = [...filteredProducts.value];
  
  switch (sortBy.value) {
    case 'price-low':
      return sorted.sort((a, b) => parseFloat(a.price) - parseFloat(b.price));
    case 'price-high':
      return sorted.sort((a, b) => parseFloat(b.price) - parseFloat(a.price));
    case 'rating':
      return sorted.sort((a, b) => (b.rating || 0) - (a.rating || 0));
    case 'newest':
      return sorted.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
    default: // name
      return sorted.sort((a, b) => a.name.localeCompare(b.name));
  }
});

// Methods
const getLaravelImageUrl = (imagePath) => {
  if (!imagePath) return '/placeholder-product.jpg';
  
  if (imagePath.startsWith('http://') || imagePath.startsWith('https://')) {
    return imagePath;
  }
  
  if (imagePath.startsWith('blob:')) {
    return imagePath;
  }
  
  const baseUrl = api.defaults.baseURL || 'http://localhost:8000';
  const storageBaseUrl = baseUrl.replace('/api', '');
  
  if (imagePath.startsWith('/')) {
    return `${storageBaseUrl}${imagePath}`;
  } else if (imagePath.startsWith('storage/')) {
    return `${storageBaseUrl}/${imagePath}`;
  } else {
    return `${storageBaseUrl}/storage/${imagePath}`;
  }
};

const handleImageError = (event) => {
  event.target.src = '/placeholder-product.jpg';
};

const formatPrice = (price) => {
  return parseFloat(price).toFixed(2);
};

const formatRating = (rating) => {
  return parseFloat(rating).toFixed(1);
};

const fetchBrandProducts = async () => {
  try {
    loading.value = true;
    
    // Fetch all products
    const response = await api.get('/public/products');
    const allProducts = response.data.data || response.data || [];
    
    // Filter for specific brand if brandId is provided
    if (props.brandId) {
      products.value = allProducts.filter(product => product.brand_id == props.brandId);
    } else {
      products.value = allProducts;
    }
    
  } catch (error) {
    console.error('Error fetching brand products:', error);
    products.value = [];
  } finally {
    loading.value = false;
  }
};

const fetchBrandInfo = async () => {
  if (!props.brandId) return;
  
  try {
    const response = await api.get(`/public/brands/{name}`);
    const brands = response.data.data || response.data || [];
    brandInfo.value = brands.find(brand => brand.id == props.brandId);
  } catch (error) {
    console.error('Error fetching brand info:', error);
    brandInfo.value = null;
  }
};

const fetchParentCategories = async () => {
  try {
    const response = await api.get('/public/parent-categories');
    parentCategories.value = response.data.data || response.data || [];
  } catch (error) {
    console.error('Error fetching parent categories:', error);
    parentCategories.value = [];
  }
};

const fetchCategories = async () => {
  try {
    const response = await api.get('/categories');
    categories.value = response.data.data || response.data || [];
  } catch (error) {
    console.error('Error fetching categories:', error);
    categories.value = [];
  }
};

const getGenderCount = (gender) => {
  return products.value.filter(product => {
    const category = categories.value.find(cat => cat.id === product.category_id);
    if (!category) return false;
    
    const parentCategory = parentCategories.value.find(parent => parent.id === category.parent_category_id);
    if (!parentCategory) return false;
    
    const categoryName = parentCategory.name.toLowerCase();
    return categoryName.includes(gender);
  }).length;
};

const getCategoryProductCount = (parentCategoryId) => {
  const categoryProducts = products.value.filter(product => {
    const category = categories.value.find(cat => cat.id === product.category_id);
    return category?.parent_category_id == parentCategoryId;
  });
  return categoryProducts.length;
};

const getPriceRangeCount = (rangeValue) => {
  return products.value.filter(product => {
    const price = parseFloat(product.price);
    switch (rangeValue) {
      case '0-50':
        return price >= 0 && price <= 50;
      case '50-100':
        return price > 50 && price <= 100;
      case '100-200':
        return price > 100 && price <= 200;
      case '200+':
        return price > 200;
      default:
        return true;
    }
  }).length;
};

const getRatingCount = (minRating) => {
  return products.value.filter(product => 
    product.rating && product.rating >= minRating
  ).length;
};

const getParentCategoryName = (parentCategoryId) => {
  const parentCategory = parentCategories.value.find(parent => parent.id == parentCategoryId);
  return parentCategory?.name || '';
};

const getParentCategoryNameById = (categoryId) => {
  const category = categories.value.find(cat => cat.id === categoryId);
  if (!category) return '';
  
  const parentCategory = parentCategories.value.find(parent => parent.id === category.parent_category_id);
  return parentCategory?.name || '';
};

const getPriceRangeLabel = (rangeValue) => {
  const range = priceRanges.find(r => r.value === rangeValue);
  return range?.label || '';
};

const getProductGender = (product) => {
  const category = categories.value.find(cat => cat.id === product.category_id);
  if (!category) return '';
  
  const parentCategory = parentCategories.value.find(parent => parent.id === category.parent_category_id);
  if (!parentCategory) return '';
  
  const categoryName = parentCategory.name.toLowerCase();
  if (categoryName.includes('women')) return 'women';
  if (categoryName.includes('men')) return 'men';
  return '';
};

const applyFilters = () => {
  // Filters are reactive through computed property
  console.log('Filters applied');
};

const applySortAndFilter = () => {
  // Sort and filter are reactive through computed properties
  console.log('Sort and filter applied');
};

const clearFilters = () => {
  searchQuery.value = '';
  selectedParentCategory.value = '';
  selectedPriceRange.value = '';
  selectedRating.value = '';
  selectedGender.value = '';
  sortBy.value = 'name';
};

const viewProduct = (product) => {
  router.push(`/product/${product.id}`);
};

// Debounced search
let searchTimeout;
const debouncedSearch = () => {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    // Search is reactive through computed property
  }, 300);
};

// Lifecycle
onMounted(async () => {
  await Promise.all([
    fetchBrandProducts(),
    fetchBrandInfo(),
    fetchParentCategories(),
    fetchCategories()
  ]);
});
</script>

<style scoped>
.line-clamp-1 {
  display: -webkit-box;
  -webkit-line-clamp: 1;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.animate-spin {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}

.router-link-active {
  @apply bg-blue-50 text-blue-600 border-blue-300;
}

.router-link-active.text-pink-600 {
  @apply bg-pink-50 text-pink-600 border-pink-300;
}

/* Custom scrollbar for category list */
.max-h-40::-webkit-scrollbar {
  width: 4px;
}

.max-h-40::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 2px;
}

.max-h-40::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 2px;
}

.max-h-40::-webkit-scrollbar-thumb:hover {
  background: #a1a1a1;
}

/* Smooth transitions */
* {
  transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 150ms;
}
</style>