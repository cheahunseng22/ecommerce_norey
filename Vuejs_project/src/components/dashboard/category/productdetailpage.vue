<template>
  <div class="font-kantumruy bg-white text-gray-800">
    <!-- Loading and Error States -->
    <div v-if="loading" class="text-center py-40">
      <p class="text-xl text-gray-500">Loading Product...</p>
    </div>

    <div v-else-if="error" class="text-center py-40">
      <p class="text-xl text-gray-700 mb-4">{{ error }}</p>
      <router-link to="/dashboard" class="text-pink-600 hover:underline">
        Back to Shop
      </router-link>
    </div>

    <div v-else-if="!product" class="text-center py-40">
      <p class="text-xl text-gray-700 mb-4">Product not found</p>
      <router-link to="/dashboard" class="text-pink-600 hover:underline">
        Back to Shop
      </router-link>
    </div>

    <div v-else>
      <!-- Main Content -->
      <div class="container mx-auto px-4 py-8">
        <!-- Breadcrumbs -->
        <nav aria-label="Breadcrumb" class="mb-6 text-sm text-gray-500">
          <router-link to="/" class="hover:text-pink-700">Home</router-link>
          <span class="mx-2">/</span>
          <span class="text-gray-700">{{ product?.name }}</span>
        </nav>

        <!-- Product Details -->
        <main class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 mt-10">
          <!-- Image Gallery -->
          <div class="flex flex-col-reverse md:flex-row gap-4">
            <div class="flex md:flex-col gap-3 justify-center md:justify-start">
              <button v-for="(image, index) in product.images" :key="index" @click="activeImage = image"
                class="w-16 h-20 rounded-md overflow-hidden border-2 transition"
                :class="activeImage === image ? 'border-pink-600' : 'border-transparent hover:border-gray-300'">
                <img :src="image" :alt="`${product.name} view ${index + 1}`" class="w-full h-full object-cover" />
              </button>
            </div>
            <div class="flex-1 flex justify-center items-center rounded-lg overflow-hidden">
              <img :src="activeImage || product.image" :alt="product.name" class="w-full h-full object-cover" />
            </div>
          </div>

          <!-- Product Info -->
          <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">{{ product.name }}</h1>
            <p class="text-3xl font-bold text-pink-700 mb-6">{{ formatPrice(product.price) }}</p>

            <!-- Color Selector -->
            <div class="mb-6" v-if="product?.colors?.length > 0">
              <div class="flex items-center">
                <label class="w-24 text-sm font-medium text-gray-700">Color</label>
                <div class="flex items-center gap-3">
                  <div class="flex space-x-2">
                    <button v-for="color in product.colors" :key="color.name" @click="selectedColor = color"
                      class="w-8 h-8 rounded-full border-2"
                      :class="selectedColor.name === color.name ? 'border-pink-600' : 'border-gray-300 hover:border-gray-400'"
                      :style="{ backgroundColor: color.hex }" :aria-label="`Select color ${color.name}`"></button>
                  </div>
                  <span class="text-sm text-gray-600">Selected: <span class="font-semibold">{{ selectedColor.name
                      }}</span></span>
                </div>
              </div>
            </div>

            <!-- Size Selector -->
            <div class="mb-6" v-if="product?.size?.length > 0">
              <div class="flex items-start">
                <label class="w-24 text-sm font-medium text-gray-700">Size</label>
                <div class="flex-1">
                  <div class="flex items-center gap-3 mb-3 text-sm">
                    <button type="button" @click="isSizeGuideOpen = true" class="text-pink-600 hover:underline">Size
                      Guide</button>
                    <span v-if="selectedSize" class="text-gray-600">Selected: <span class="font-semibold uppercase">{{
                        selectedSize }}</span></span>
                  </div>
                  <div class="flex flex-wrap gap-2">
                    <button v-for="size in product.size" :key="size" @click="selectedSize = size"
                      class="px-4 py-2 border rounded-md text-sm font-medium uppercase"
                      :class="selectedSize === size ? 'bg-pink-700 text-white border-pink-700' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'">
                      {{ size }}
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Quantity and Actions -->
            <div class="mb-6">
              <div class="flex items-center">
                <label class="w-24 text-sm font-medium text-gray-700">Qty</label>
                <div class="flex items-stretch gap-4 my-2 flex-1">
                  <div class="flex items-center border border-gray-300 rounded-md">
                    <button @click="decreaseQuantity"
                      class="px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-l-md">-</button>
                    <span class="px-4 text-center w-12">{{ quantity }}</span>
                    <button @click="increaseQuantity"
                      class="px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-r-md">+</button>
                  </div>
                  <button @click="onAddToBag"
                    class="flex-1 bg-pink-700 text-white font-bold py-3 px-6 rounded-md hover:bg-pink-800 disabled:opacity-60 disabled:cursor-not-allowed"
                    :disabled="isSizeSelectionRequired && !selectedSize">
                    ADD TO BAG
                  </button>
                </div>
              </div>
            </div>

            <!-- Accordion for Details -->
            <div class="border-t">
              <div class="border-b">
                <button @click="toggleAccordion('description')" class="w-full flex justify-between items-center py-4">
                  <span class="font-semibold text-gray-800">Product Description</span>
                  <svg class="w-5 h-5 transition-transform" :class="{ 'rotate-180': activeAccordion === 'description' }"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                  </svg>
                </button>
                <div v-if="activeAccordion === 'description'" class="pb-4 text-gray-600 text-sm space-y-2">
                  <p>{{ product.description }}</p>
                  <ul class="list-disc list-inside pl-2" v-if="product?.details?.length > 0">
                    <li v-for="(detail, i) in product.details" :key="i">{{ detail }}</li>
                  </ul>
                </div>
              </div>
              <div class="border-b">
                <button @click="toggleAccordion('delivery')" class="w-full flex justify-between items-center py-4">
                  <span class="font-semibold text-gray-800">Delivery & Returns</span>
                  <svg class="w-5 h-5 transition-transform" :class="{ 'rotate-180': activeAccordion === 'delivery' }"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                  </svg>
                </button>
                <div v-if="activeAccordion === 'delivery'" class="pb-4 text-gray-600 text-sm">
                  <p>Standard delivery within 3–5 working days. Free returns within 30 days of purchase.</p>
                </div>
              </div>
            </div>
          </div>
        </main>
      </div>
    </div>


    <!-- Size Guide Modal -->
    <div v-if="isSizeGuideOpen" class="fixed inset-0 z-50">
      <div class="fixed inset-0 bg-black bg-opacity-50" @click="isSizeGuideOpen = false"></div>
      <div
        class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white p-6 rounded-lg shadow-lg max-w-md w-full mx-4">
        <h3 class="text-lg font-semibold mb-4">Size Guide</h3>
        <div class="space-y-2 text-sm">
          <div class="flex justify-between"><span>XS:</span><span>Extra Small (30-32")</span></div>
          <div class="flex justify-between"><span>S:</span><span>Small (32-34")</span></div>
          <div class="flex justify-between"><span>M:</span><span>Medium (34-36")</span></div>
          <div class="flex justify-between"><span>L:</span><span>Large (36-38")</span></div>
          <div class="flex justify-between"><span>XL:</span><span>Extra Large (38-40")</span></div>
          <div class="flex justify-between"><span>XXL:</span><span>Double Extra Large (40-42")</span></div>
        </div>
        <button @click="isSizeGuideOpen = false"
          class="mt-4 px-4 py-2 bg-pink-600 text-white rounded hover:bg-pink-700">
          Close
        </button>
      </div>
    </div>

    <!-- Add to Bag Success Modal -->
    <div v-if="isAddToBagOpen" class="fixed inset-0 z-50">
      <div class="fixed inset-0 bg-black bg-opacity-50" @click="closeAddToBagModal"></div>
      <div
        class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white p-6 rounded-lg shadow-lg max-w-md w-full mx-4">
        <div class="text-center">
          <!-- Success Icon -->
          <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
          </div>

          <button @click="addToCart(product)">Add to Cart</button>
          <p class="text-sm text-gray-600 mb-6">
            <span class="font-medium">{{ addedCartItem?.name }}</span> has been added to your cart.
          </p>

        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { ref, onMounted, computed, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '../../../axios/Axios'; // Assuming axios instance is configured

// --- Component Props ---
const props = defineProps({
  id: {
    type: [String, Number],
    required: true,
  },
});

// --- Reactive State ---
const route = useRoute();
const router = useRouter();

const product = ref(null);
const loading = ref(true);
const error = ref(null);

const isSizeGuideOpen = ref(false);
const isAddToBagOpen = ref(false);
const addedCartItem = ref(null);

const activeImage = ref('');
const selectedColor = ref({});
const selectedSize = ref('');
const quantity = ref(1);
const activeAccordion = ref('description');
const wishlist = ref([]);

const emit = defineEmits(['require-auth']);

// --- Computed Properties ---
const isInWishlist = computed(() => {
  return wishlist.value.some(item => item.id === product.value?.id);
});

const isSizeSelectionRequired = computed(() => Array.isArray(product.value?.size) && product.value.size.length > 0);

// --- Methods ---
const fetchProductData = async () => {
  loading.value = true;
  error.value = null;
  try {
    const res = await api.get(`/public/products/${props.id}`);
    const apiData = res.data?.data ?? res.data;

    if (!apiData) {
      throw new Error('No product data returned');
    }

    const images = (() => {
      if (Array.isArray(apiData.images)) return apiData.images.filter(Boolean);
      if (typeof apiData.images === 'string') {
        try {
          const parsed = JSON.parse(apiData.images);
          if (Array.isArray(parsed)) return parsed.filter(Boolean);
        } catch (_) {
          return apiData.images.split(',').map(s => s.trim()).filter(Boolean);
        }
      }
      return apiData.image ? [apiData.image] : [];
    })();

    const sizeArray = Array.isArray(apiData.size)
      ? apiData.size
      : (typeof apiData.size === 'string'
        ? apiData.size.split(',').map(s => s.trim()).filter(Boolean)
        : []);

    // Assuming colors from API are already in the correct format { name: 'ColorName', hex: '#HEXCODE' }
    const colorsArray = Array.isArray(apiData.colors) ? apiData.colors : [];


    product.value = {
      ...apiData,
      images,
      size: sizeArray,
      colors: colorsArray, // Assign the processed colors array
    };

    activeImage.value = images[0] || apiData.image || '';
    selectedColor.value = product.value.colors?.[0] || {};
    selectedSize.value = product.value.size?.[0] || '';
  } catch (err) {
    console.error("Failed to fetch product:", err);
    error.value = "Failed to load product details. Please try again.";
  } finally {
    loading.value = false;
  }
};

const formatPrice = (value) => {
  if (value == null) return '';
  return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(value);
};

const decreaseQuantity = () => {
  if (quantity.value > 1) quantity.value--;
};

const increaseQuantity = () => {
  quantity.value++;
};

const toggleAccordion = (section) => {
  activeAccordion.value = activeAccordion.value === section ? null : section;
};

const onAddToBag = async() => {
  if (!product.value) return;
  const token = sessionStorage.getItem("auth_token");
  if (!token) {
    window.dispatchEvent(new CustomEvent("auth-required"));
    return;
  }

  const payload = {
    customer_id : sessionStorage.getItem("user_id"),
    product_id : product.value.id,
    quantity : quantity.value,
    price: product.value.price
  }

  try {
    await api.post(
      "/cart/items",
      payload,
      {
        headers: {
          Authorization: `Bearer ${token}`,
          "Content-Type": "application/json"
        },
      }
    );
    console.log('Response:', res.data)
  } catch (err) {
    console.error('API error:', err.response?.data || err.message)
  }


  // Get existing cart items from localStorage
  const existingCart = JSON.parse(sessionStorage.getItem("cartItems") || "[]");
  let totalCartCount = sessionStorage.getItem("totalCartCount") || 0;

  const cartItem = {
    id: product.value.id,
    name: product.value.name,
    price: product.value.price,
    image: activeImage.value,
    color: selectedColor.value.name, // Include selected color
    size: selectedSize.value,
    quantity: quantity.value,
    timestamp: Date.now() // Add timestamp for unique identification
  };

  // Check if item already exists in cart (same id, color, and size)
  const existingItemIndex = existingCart.findIndex(item =>
    item.id === cartItem.id && item.color === cartItem.color && item.size === cartItem.size
  );

  if (existingItemIndex !== -1) {
    // Update existing item quantity
    existingCart[existingItemIndex].quantity += cartItem.quantity;
  } else {
    // Add new item
    existingCart.push(cartItem);
  }

  // Save updated cart to localStorage
  sessionStorage.setItem("cartItems", JSON.stringify(existingCart));
  sessionStorage.setItem("totalCartCount", Number(totalCartCount) + quantity.value);

  // Emit cart update event for LayoutUser.vue
  window.dispatchEvent(new CustomEvent('cart-updated', {
    detail: { count: Number(totalCartCount) + quantity.value }
  }));

  console.log("Added to Bag:", cartItem);

  addedCartItem.value = cartItem;
  isAddToBagOpen.value = true;
};

const loadWishlist = () => {
  const savedWishlist = localStorage.getItem('wishlist');
  wishlist.value = savedWishlist ? JSON.parse(savedWishlist) : [];
};

const saveWishlist = () => {
  localStorage.setItem('wishlist', JSON.stringify(wishlist.value));
};

const toggleWishlist = () => {
  if (!product.value) return;
  const index = wishlist.value.findIndex(item => item.id === product.value.id);
  if (index > -1) {
    wishlist.value.splice(index, 1);
  } else {
    wishlist.value.push({
      id: product.value.id,
      name: product.value.name,
      price: product.value.price,
      image: activeImage.value
    });
  }
  saveWishlist();
};

const handleProductClick = () => {
  window.scrollTo(0, 0);
  quantity.value = 1;
  activeAccordion.value = 'description';
};

const closeAddToBagModal = () => {
  isAddToBagOpen.value = false;
  addedCartItem.value = null;
};

const goToCart = () => {
  // Emit event to open cart drawer in LayoutUser.vue
  window.dispatchEvent(new CustomEvent('open-cart-drawer'));
  isAddToBagOpen.value = false;
  addedCartItem.value = null;
};

// --- Lifecycle Hooks ---
onMounted(() => {
  fetchProductData();
  loadWishlist();
});

watch(() => props.id, () => {
  fetchProductData();
  handleProductClick();
});

</script>

<style scoped>
.rotate-180 {
  transform: rotate(180deg);
}

/* Add any additional styles you need */
</style>