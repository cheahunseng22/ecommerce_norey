<template>
  <div class="p-6 bg-gray-50 min-h-screen font-sans">
    <!-- Header: Back Button, Order Info, Action Buttons -->
    <div class="flex items-center justify-between mb-6">
      <div class="flex items-center">
        <router-link to="/admin/order/list" class="flex items-center text-gray-600 hover:text-gray-900 transition-colors duration-200">
          <i class="fas fa-chevron-left text-xl mr-2"></i>
          <span class="text-xl font-semibold">Order #{{ order.id }}</span>
        </router-link>
        <OrderStatusBadge :status="order.status" class="ml-4" />
        <span class="ml-4 text-sm text-gray-500">{{ formatDateTime(order.date) }}</span>
      </div>

      <div class="flex items-center space-x-3">
        <!-- Status Dropdown (Simulated) -->
        <div class="relative inline-block text-left" ref="statusDropdownRef">
          <button @click="toggleStatusDropdown"
            class="inline-flex justify-center items-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            {{ order.status }}
            <i class="fas fa-chevron-down -mr-1 ml-2 h-4 w-4 text-gray-400"></i>
          </button>
          <div v-if="isStatusDropdownOpen"
            class="origin-top-right absolute right-0 mt-2 w-36 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10">
            <div class="py-1">
              <a href="#" @click.prevent="updateStatus('Pending')"
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Pending</a>
              <a href="#" @click.prevent="updateStatus('Completed')"
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Completed</a>
              <a href="#" @click.prevent="updateStatus('Cancelled')"
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Cancelled</a>
              <a href="#" @click.prevent="updateStatus('Refunded')"
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Refunded</a>
            </div>
          </div>
        </div>

        <!-- Print Button -->
        <button @click="handlePrint"
          class="inline-flex items-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
          <i class="fas fa-print mr-2"></i>
          Print
        </button>

        <!-- Edit Button -->
        <button @click="handleEditOrder"
          class="inline-flex items-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-gray-800 text-sm font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
          <i class="fas fa-edit mr-2"></i>
          Edit
        </button>
      </div>
    </div>

    <!-- Main Content: Details, History (Left) and Customer, Delivery, Shipping, Payment (Right) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Left Column -->
      <div class="lg:col-span-2 space-y-6">
        <!-- Details Card -->
        <div class="bg-white p-6 rounded-lg shadow-sm">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Details</h2>
            <button @click="handleEditSection('Details')" class="text-gray-400 hover:text-gray-600">
              <i class="fas fa-pencil-alt"></i>
            </button>
          </div>
          <div class="space-y-4">
            <div v-for="product in order.products" :key="product.id" class="flex items-start justify-between pb-4 border-b border-dashed border-gray-200 last:border-b-0 last:pb-0">
              <div class="flex items-start">
                <img :src="product.image || 'https://via.placeholder.com/64x64?text=No+Image'" :alt="product.name" class="w-16 h-16 object-cover rounded-md mr-4" />
                <div>
                  <p class="text-base font-medium text-gray-900">{{ product.name }}</p>
                  <p class="text-sm text-gray-500">{{ product.sku }}</p>
                </div>
              </div>
              <div class="text-right">
                <p class="text-base text-gray-700">x{{ product.quantity }}</p>
                <p class="text-base font-semibold text-gray-900">${{ product.price.toFixed(2) }}</p>
              </div>
            </div>
            <div v-if="!order.products || order.products.length === 0" class="text-gray-500 text-center py-4">
                No products in this order.
            </div>
          </div>

          <div class="grid grid-cols-2 gap-y-2 text-sm text-gray-700 mt-6">
            <div class="text-right pr-4">Subtotal</div><div class="text-right font-semibold">${{ order.summary.subtotal.toFixed(2) }}</div>
            <div class="text-right pr-4">Shipping</div><div :class="['text-right font-semibold', order.summary.shipping < 0 ? 'text-red-600' : '']">${{ order.summary.shipping.toFixed(2) }}</div>
            <div class="text-right pr-4">Discount</div><div :class="['text-right font-semibold', order.summary.discount < 0 ? 'text-red-600' : '']">${{ order.summary.discount.toFixed(2) }}</div>
            <div class="text-right pr-4">Taxes</div><div class="text-right font-semibold">${{ order.summary.taxes.toFixed(2) }}</div>
            <div class="col-span-2 border-t border-gray-200 mt-2 pt-2"></div>
            <div class="text-right pr-4 text-base font-bold text-gray-900">Total</div><div class="text-right text-base font-bold text-gray-900">${{ order.summary.total.toFixed(2) }}</div>
          </div>
        </div>

        <!-- History Card -->
        <div class="bg-white p-6 rounded-lg shadow-sm">
          <h2 class="text-lg font-semibold text-gray-800 mb-4">History</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Timeline (Left Half) -->
            <div>
              <ol class="relative border-l border-gray-200">
                <li v-for="(event, index) in order.history" :key="index" class="mb-6 ml-6">
                  <span class="absolute flex items-center justify-center w-3 h-3 rounded-full -left-1.5 ring-4 ring-white"
                        :class="index === 0 ? 'bg-green-500' : 'bg-gray-300'">
                  </span>
                  <h3 class="text-sm font-semibold text-gray-900">{{ event.status }}</h3>
                  <time class="block mb-1 text-xs font-normal leading-none text-gray-500">{{ formatDateTime(event.date) }}</time>
                </li>
              </ol>
            </div>
            <!-- Details (Right Half) -->
            <div>
              <div class="mb-4">
                <p class="text-sm text-gray-500">Order time</p>
                <p class="text-sm font-medium text-gray-800">{{ formatDateTime(order.extraHistoryDetails.orderTime) }}</p>
              </div>
              <div class="mb-4">
                <p class="text-sm text-gray-500">Payment time</p>
                <p class="text-sm font-medium text-gray-800">{{ formatDateTime(order.extraHistoryDetails.paymentTime) }}</p>
              </div>
              <div class="mb-4">
                <p class="text-sm text-gray-500">Delivery time for the carrier</p>
                <p class="text-sm font-medium text-gray-800">{{ formatDateTime(order.extraHistoryDetails.deliveryTimeCarrier) }}</p>
              </div>
              <div class="mb-4">
                <p class="text-sm text-gray-500">Completion time</p>
                <p class="text-sm font-medium text-gray-800">{{ formatDateTime(order.extraHistoryDetails.completionTime) }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Column -->
      <div class="lg:col-span-1 space-y-6">
        <!-- Customer Info Card -->
        <div class="bg-white p-6 rounded-lg shadow-sm">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Customer info</h2>
            <button @click="handleEditSection('Customer Info')" class="text-gray-400 hover:text-gray-600">
              <i class="fas fa-pencil-alt"></i>
            </button>
          </div>
          <div class="flex items-center mb-3">
            <img :src="order.customer.avatar || `https://ui-avatars.com/api/?name=${encodeURIComponent(order.customer.name)}&background=random&color=fff`" :alt="order.customer.name" class="w-12 h-12 rounded-full object-cover mr-4" />
            <div>
              <p class="text-base font-medium text-gray-900">{{ order.customer.name }}</p>
              <p class="text-sm text-gray-500">{{ order.customer.email }}</p>
            </div>
          </div>
          <p class="text-xs text-gray-500 mb-4">IP address: {{ order.customer.ipAddress }}</p>
          <button @click="handleAddToBlacklist" class="flex items-center text-red-600 hover:text-red-800 text-sm font-medium">
            <i class="fas fa-plus-circle mr-2"></i> Add to Blacklist
          </button>
        </div>

        <!-- Delivery Card -->
        <div class="bg-white p-6 rounded-lg shadow-sm">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Delivery</h2>
            <button @click="handleEditSection('Delivery')" class="text-gray-400 hover:text-gray-600">
              <i class="fas fa-pencil-alt"></i>
            </button>
          </div>
          <div class="grid grid-cols-2 gap-y-2 text-sm">
            <div class="text-gray-500">Ship by</div><div class="font-medium text-gray-800">{{ order.delivery.shipBy }}</div>
            <div class="text-gray-500">Speedy</div><div class="font-medium text-gray-800">{{ order.delivery.speedy }}</div>
            <div class="text-gray-500">Tracking No.</div>
            <a :href="`https://track.example.com?id=${order.delivery.trackingNo}`" target="_blank" class="text-blue-600 hover:underline font-medium">
              {{ order.delivery.trackingNo }}
            </a>
          </div>
        </div>

        <!-- Shipping Card -->
        <div class="bg-white p-6 rounded-lg shadow-sm">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Shipping</h2>
            <button @click="handleEditSection('Shipping')" class="text-gray-400 hover:text-gray-600">
              <i class="fas fa-pencil-alt"></i>
            </button>
          </div>
          <div class="grid grid-cols-2 gap-y-2 text-sm">
            <div class="text-gray-500">Address</div><div class="font-medium text-gray-800">{{ order.shipping.address }}</div>
            <div class="text-gray-500">Phone number</div><div class="font-medium text-gray-800">{{ order.shipping.phoneNumber }}</div>
          </div>
        </div>

        <!-- Payment Card -->
        <div class="bg-white p-6 rounded-lg shadow-sm">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Payment</h2>
            <button @click="handleEditSection('Payment')" class="text-gray-400 hover:text-gray-600">
              <i class="fas fa-pencil-alt"></i>
            </button>
          </div>
          <div class="flex items-center justify-between text-sm">
            <span class="font-medium text-gray-800">{{ order.payment.cardNumberMasked }}</span>
            <!-- Mastercard Logo Placeholder -->
            <div class="w-8 h-5 flex items-center justify-center rounded bg-orange-500 text-white font-bold text-xs"
                 v-if="order.payment.cardType === 'Mastercard'">
              M
            </div>
            <!-- More realistic card icon (example - would need proper asset) -->
            <!-- <img v-if="order.payment.cardType === 'Mastercard'" src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Mastercard-logo.svg/800px-Mastercard-logo.svg.png" alt="Mastercard" class="w-8 h-auto" /> -->
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
// import OrderStatusBadge from '../components/OrderStatusBadge.vue'; // Adjust path as needed

// --- Mock Order Data ---
// In a real application, this would come from an API call
// based on the order ID from the route params (e.g., /admin/order/details/:id)
const order = ref({
  id: '6011',
  status: 'Completed',
  date: '2025-06-13T19:07:00', // ISO string for easy date parsing

  products: [
    {
      id: 1,
      image: '/public/images/pic5.jpg', // Example image
      name: 'Urban Explorer Sneakers',
      sku: '16H9UR0',
      quantity: 1,
      price: 83.74,
    },
  ],
  summary: {
    subtotal: 83.74,
    shipping: -10.00,
    discount: -10.00,
    taxes: 10.00,
    total: 73.74, // 83.74 - 10 - 10 + 10 = 73.74
  },

  history: [
    { status: 'Delivery successful', date: '2025-06-13T19:07:00' },
    { status: 'Transporting to [2]', date: '2025-06-12T18:07:00' },
    { status: 'Transporting to [1]', date: '2025-06-11T17:07:00' },
    { status: 'The shipping unit has picked up the goods', date: '2025-06-10T16:07:00' },
    { status: 'Order has been created', date: '2025-06-09T15:07:00' },
  ],
  extraHistoryDetails: {
      orderTime: '2025-06-13T19:07:00',
      paymentTime: '2025-06-13T19:07:00',
      deliveryTimeCarrier: '2025-06-13T19:07:00',
      completionTime: '2025-06-13T19:07:00',
  },

  customer: {
    avatar: 'https://randomuser.me/api/portraits/men/50.jpg', // Example avatar
    name: 'Lucian Obrien',
    email: 'ashlynn.ohara62@gmail.com',
    ipAddress: '192.158.1.38',
  },

  delivery: {
    shipBy: 'DHL',
    speedy: 'Standard',
    trackingNo: 'SPX037739199373',
  },

  shipping: {
    address: '19034 Verna Unions Apt. 164 -Honolulu, RI / 87535',
    phoneNumber: '365-374-4961',
  },

  payment: {
    cardNumberMasked: '**** **** **** 5678',
    cardType: 'Mastercard',
  },
});

// --- State for Status Dropdown ---
const isStatusDropdownOpen = ref(false);
const statusDropdownRef = ref(null); // Ref for click outside logic

const toggleStatusDropdown = () => {
  isStatusDropdownOpen.value = !isStatusDropdownOpen.value;
};

const updateStatus = (newStatus) => {
  order.value.status = newStatus;
  isStatusDropdownOpen.value = false;
  alert(`Order status updated to: ${newStatus}`);
  // In a real app, you'd send this update to your backend API
};

// --- Click outside handler for dropdown ---
const handleClickOutside = (event) => {
  if (statusDropdownRef.value && !statusDropdownRef.value.contains(event.target)) {
    isStatusDropdownOpen.value = false;
  }
};

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});


// --- Action Handlers (simulated) ---
const handlePrint = () => {
  alert('Printing order details...');
  // Implement actual print functionality here
};

const handleEditOrder = () => {
  alert('Editing entire order...');
  // Navigate to an edit page or open a modal
};

const handleEditSection = (sectionName) => {
  alert(`Editing ${sectionName} section`);
  // Open a specific modal for this section
};

const handleAddToBlacklist = () => {
  alert(`Adding ${order.value.customer.name} to blacklist`);
  // Implement backend call to add to blacklist
};

// --- Utility Functions ---
const formatDateTime = (isoString) => {
  if (!isoString) return '';
  const date = new Date(isoString);
  return date.toLocaleDateString('en-US', {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
  }) + ' ' + date.toLocaleTimeString('en-US', {
    hour: '2-digit',
    minute: '2-digit',
    hour12: true,
  }).toLowerCase();
};
</script>

<style scoped>
/* No specific scoped styles needed as we are using Tailwind classes. */
/* If you need custom styles for the timeline dots/lines, you'd add them here. */
/* For example, the border-l on the ol and absolute positioning of span for dots are key. */
</style>