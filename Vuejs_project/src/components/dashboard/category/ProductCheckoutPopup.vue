<template>
    <!-- Overlay to cover the page -->
    <div
      v-if="open"
      @click="closePopup"
      class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 transition-opacity"
      aria-labelledby="modal-title"
      role="dialog"
      aria-modal="true"
    >
      <!-- Modal Panel -->
      <div
        @click.stop
        class="bg-white rounded-lg shadow-xl w-full max-w-lg mx-4 overflow-hidden transform transition-all"
      >
        <!-- Header with Icons -->
        <div class="flex justify-between items-center p-4 border-b border-gray-200">
          <!-- Bookmark Icon -->
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
          </svg>
          <!-- Close Icon -->
          <button @click="closePopup" class="text-gray-500 hover:text-gray-800" aria-label="Close">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <!-- Main Content -->
        <div class="p-6">
          <!-- Product Details Section -->
          <div class="flex flex-col sm:flex-row gap-6 mb-6">
            <!-- Image & Wishlist Link -->
            <div class="flex-shrink-0 text-center">
              <div class="w-32 h-40 bg-gray-100 rounded-lg overflow-hidden mx-auto">
                <img :src="productData.image" :alt="productData.name" class="w-full h-full object-cover">
              </div>
              <button class="mt-3 flex items-center gap-2 text-sm text-gray-600 hover:text-blue-600 mx-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                   <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                </svg>
                Move to wishlist
              </button>
            </div>
            
            <!-- Product Info & Pricing -->
            <div class="flex-1">
              <h2 id="modal-title" class="text-xl font-semibold text-gray-900">{{ productData.name }}</h2>
              <p class="text-sm text-gray-500 mt-1">Code. {{ productData.id }} - {{ productData.color }}</p>
  
              <!-- Size & Quantity Dropdowns -->
              <div class="flex items-center gap-6 mt-4">
                <div>
                  <label for="size" class="block text-sm font-medium text-gray-700">Size</label>
                  <select v-model="selectedSize" id="size" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-pink-500 focus:border-pink-500 sm:text-sm rounded-md">
                    <option v-for="size in productData.sizes" :key="size" :value="size">{{ size }}</option>
                  </select>
                </div>
                <div>
                  <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                  <select v-model="quantity" id="quantity" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-pink-500 focus:border-pink-500 sm:text-sm rounded-md">
                    <option v-for="n in 10" :key="n" :value="n">{{ n }}</option>
                  </select>
                </div>
              </div>
  
              <!-- Pricing Details -->
              <div class="text-right mt-6">
                <p class="text-lg text-gray-500 line-through">${{ originalSubtotal.toFixed(2) }}</p>
                <p class="text-sm text-gray-600">(30% off) - ${{ savings.toFixed(2) }}</p>
                <p class="text-2xl font-bold text-pink-600 mt-1">${{ discountedSubtotal.toFixed(2) }}</p>
              </div>
            </div>
          </div>
          
          <!-- Order Summary -->
          <div class="border-t border-gray-200 pt-4 space-y-2">
            <div class="flex justify-between items-center text-lg font-bold">
              <span class="text-gray-900">Amount to pay</span>
              <span class="text-gray-900">US ${{ totalAmountToPay.toFixed(2) }}</span>
            </div>
            <div class="flex justify-between text-sm text-gray-600">
              <span>Total</span>
              <span>US ${{ originalSubtotal.toFixed(2) }}</span>
            </div>
            <div class="flex justify-between text-sm text-gray-600">
              <span>Save</span>
              <span class="text-green-600">-US ${{ savings.toFixed(2) }}</span>
            </div>
            <div class="flex justify-between text-sm text-gray-600">
              <span>Delivery fee</span>
              <span>US ${{ productData.deliveryFee.toFixed(2) }}</span>
            </div>
          </div>
        </div>
        
        <!-- Action Buttons in Footer -->
        <div class="bg-gray-50 p-4 flex gap-4">
          <button @click="handleCheckout" class="flex-1 bg-gray-900 text-white py-3 px-4 rounded-md font-semibold hover:bg-gray-800 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900">
            Checkout
          </button>
          <button @click="handleShopMore" class="flex-1 bg-gray-900 text-white py-3 px-4 rounded-md font-semibold hover:bg-gray-800 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900">
            Shop more
          </button>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, computed, watch } from 'vue';
  
  // --- Define Props and Emits ---
  const props = defineProps({
    open: {
      type: Boolean,
      default: false,
    },
    productData: {
      type: Object,
      required: true,
    },
  });
  
  const emit = defineEmits(['close']);
  
  // --- Reactive State ---
  const selectedSize = ref('XS');
  const quantity = ref(2);
  
  // --- Initialize state when productData changes ---
  // This ensures the dropdowns reset if a different product is opened
  watch(() => props.productData, (newData) => {
    if (newData && newData.sizes && newData.sizes.length > 0) {
      selectedSize.value = newData.sizes[0];
    }
    quantity.value = 2; // Reset quantity to default
  }, { immediate: true });
  
  
  // --- Computed Properties for Dynamic Pricing ---
  const originalSubtotal = computed(() => {
    return (props.productData.originalPrice || 0) * quantity.value;
  });
  
  const discountedSubtotal = computed(() => {
    return (props.productData.discountedPrice || 0) * quantity.value;
  });
  
  const savings = computed(() => {
    return originalSubtotal.value - discountedSubtotal.value;
  });
  
  const totalAmountToPay = computed(() => {
    return discountedSubtotal.value + (props.productData.deliveryFee || 0);
  });
  
  // --- Methods ---
  const closePopup = () => {
    emit('close');
  };
  
  const handleCheckout = () => {
    // Logic for checkout can be added here
    console.log('Proceeding to checkout with:', {
      product: props.productData.name,
      size: selectedSize.value,
      quantity: quantity.value,
      total: totalAmountToPay.value.toFixed(2)
    });
    alert('Checkout button clicked! See console for details.');
    closePopup();
  };
  
  const handleShopMore = () => {
    // Logic to navigate to another page can be added here
    console.log('Navigating to shop more...');
    alert('Shop more button clicked!');
    closePopup();
  };
  </script>