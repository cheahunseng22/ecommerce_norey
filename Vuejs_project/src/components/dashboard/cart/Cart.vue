<template>
  <div class="container mx-auto py-8 px-4 font-kantumruy">
    <h1 class="text-3xl font-bold mb-6">Shopping Cart</h1>

    <!-- Loading -->
    <div v-if="isLoading" class="text-center py-20">Loading cart...</div>

    <!-- Error -->
    <div v-else-if="error" class="text-red-500 text-center py-20">{{ error }}</div>

    <!-- Empty Cart -->
    <div v-else-if="cartItems.length === 0" class="text-center py-20">
      Your cart is empty
      <router-link to="/products" class="text-pink-600 hover:underline">Shop Now</router-link>
    </div>

    <!-- Cart Items & Summary -->
    <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Cart Items -->
      <div class="lg:col-span-2">
        <div
          v-for="item in cartItems"
          :key="item.id + '-' + item.size"
          class="flex items-center border rounded-xl p-4 mb-4"
        >
          <img :src="item.image" class="w-24 h-24 rounded-lg object-cover" />
          <div class="flex-1 ml-4">
            <h3 class="font-semibold">{{ item.product_name }}</h3>
            <p>Size: {{ item.size }}</p>
            <p class="text-pink-600 font-bold">{{ formatPrice(item.price) }}</p>
            <div class="flex gap-2 mt-2">
              <button @click="decreaseQuantity(item)" class="px-2 py-1 border rounded">-</button>
              <span>{{ item.quantity }}</span>
              <button @click="increaseQuantity(item)" class="px-2 py-1 border rounded">+</button>
            </div>
          </div>
          <button @click="removeFromCart(item)" class="text-red-500 ml-4">Remove</button>
        </div>
      </div>

      <!-- Order Summary -->
      <div class="bg-gray-50 p-6 rounded-xl border">
        <h2 class="font-semibold text-lg mb-4">Order Summary</h2>
        <div class="flex justify-between mb-2">Subtotal: <span>{{ formatPrice(subtotal) }}</span></div>
        <div class="flex justify-between mb-2">Shipping Fee: <span>{{ formatPrice(shippingFee) }}</span></div>
        <div class="border-t my-4"></div>
        <div class="flex justify-between font-bold text-lg">Total: <span>{{ formatPrice(orderTotal) }}</span></div>
        <button @click="proceedToCheckout" class="mt-6 w-full bg-pink-600 text-white py-3 rounded-xl hover:bg-pink-700">Checkout</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import api from '../../../axios/Axios';

const router = useRouter();

const cartItems = ref([]);
const subtotal = ref(0);
const shippingFee = ref(5.00);
const isLoading = ref(true);
const error = ref(null);

const totalItems = computed(() => cartItems.value.reduce((sum, item) => sum + item.quantity, 0));
const orderTotal = computed(() => subtotal.value + shippingFee.value);

// -------------------------
// Fetch Cart Items
// -------------------------
const fetchCart = async () => {
  isLoading.value = true;
  try {
    const token = localStorage.getItem('token');
    const customerId = localStorage.getItem('customer_id');
    if (!token || !customerId) throw new Error('User not authenticated');

    const res = await api.get(`/cart?customer_id=${customerId}`, {
      headers: { Authorization: `Bearer ${token}` }
    });

    const data = res.data.data;

    cartItems.value = data.items.map(item => ({
      id: item.id,
      product_id: item.product_id,
      product_name: item.product?.name ?? 'Unknown',
      image: item.product?.image ?? '',
      size: item.size ?? '',
      price: parseFloat(item.price),
      quantity: item.quantity
    }));

    updateTotals();
  } catch (err) {
    console.error(err);
    error.value = err.response?.data?.message ?? err.message ?? 'Failed to load cart';
  } finally {
    isLoading.value = false;
  }
};



// -------------------------
// Cart Operations
// -------------------------
const updateTotals = () => {
  subtotal.value = cartItems.value.reduce((sum, item) => sum + item.price * item.quantity, 0);
};

const removeFromCart = async (item) => {
  const token = localStorage.getItem('token');
  const customerId = localStorage.getItem('customer_id');

  await api.delete(`/cart/items/${item.id}?customer_id=${customerId}`, {
    headers: { Authorization: `Bearer ${token}` }
  });

  cartItems.value = cartItems.value.filter(i => i.id !== item.id);
  updateTotals();
};


const increaseQuantity = async (item) => {
  const token = localStorage.getItem('token');
  const customerId = localStorage.getItem('customer_id');

  const newQty = item.quantity + 1;

  await api.put(
    `/cart/items/${item.id}`,
    { quantity: newQty, customer_id: customerId },
    { headers: { Authorization: `Bearer ${token}` } }
  );

  item.quantity = newQty;
  updateTotals();
};

const decreaseQuantity = async (item) => {
  if (item.quantity <= 1) return;

  const token = localStorage.getItem('token');
  const customerId = localStorage.getItem('customer_id');
  const newQty = item.quantity - 1;

  await api.put(
    `/cart/items/${item.id}`,
    { quantity: newQty, customer_id: customerId },
    { headers: { Authorization: `Bearer ${token}` } }
  );

  item.quantity = newQty;
  updateTotals();
};

// -------------------------
// Format Price & Checkout
// -------------------------
const formatPrice = (price) => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(price);

const proceedToCheckout = () => {
  if (!cartItems.value.length) {
    alert('Cart is empty');
    return;
  }
  router.push('/checkout');
};

// -------------------------
// On Mounted
// -------------------------
onMounted(fetchCart);
</script>
