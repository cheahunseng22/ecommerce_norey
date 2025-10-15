<template>
  <div class="font-kantumruy bg-gray-50 min-h-screen py-8">
    <div class="container mx-auto px-4">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Checkout</h1>
        <p class="text-gray-600 mt-2">Complete your purchase</p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Checkout Form -->
        <div class="lg:col-span-2 space-y-6">
          <!-- Address Section -->
          <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
              <!-- <h2 class="text-xl font-semibold text-gray-900">Address</h2> -->
                             <button 
                 @click="openAddressForm" 
                 class="px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition-colors duration-200"
               >
                 Add New Address
               </button>
            </div>
            
            <!-- Selected Address Display -->
            <div v-if="selectedAddress" class="border border-gray-200 rounded-lg p-4 bg-gray-50">
              <div class="flex items-start justify-between">
                <div>
                  <p class="font-medium text-gray-900">{{ selectedAddress.firstName }} {{ selectedAddress.lastName }}</p>
                  <p class="text-sm text-gray-600">+855 {{ selectedAddress.mobileNumber }}</p>
                  <p class="text-sm text-gray-600">{{ selectedAddress.address }}</p>
                  <p class="text-sm text-gray-600">{{ selectedAddress.city }}, {{ selectedAddress.country }}</p>
                  <span v-if="selectedAddress.isDefault" class="inline-block mt-2 px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Default Address</span>
                </div>
                <button @click="editAddress(selectedAddress)" class="text-pink-600 hover:text-pink-700 text-sm">
                  Edit
                </button>
              </div>
            </div>
            
            <!-- No Address Selected -->
            
          </div>

          <!-- Payment Method -->
         

          <!-- Order Notes -->
          <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Order Notes (Optional)</h2>
            <textarea 
              v-model="orderNotes" 
              rows="3" 
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-pink-500 focus:border-pink-500"
              placeholder="Add any special instructions or notes for your order..."
            ></textarea>
          </div>
        </div>

        <!-- Order Summary Sidebar -->
        <div class="lg:col-span-1">
          <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sticky top-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Order Summary</h2>
            
                         <!-- Cart Items -->
             <div class="space-y-3 mb-6">
               <div v-for="item in cartItems" :key="`${item.id}-${item.size}-${item.timestamp}`" class="flex items-center space-x-3">
                 <img :src="item.product.image" :alt="item.name" class="w-12 h-12 object-cover rounded-md"/>
                 <div class="flex-1 min-w-0">
                   <p class="text-sm font-medium text-gray-900 truncate">{{ item.name }}</p>
                   <p class="text-xs text-gray-500">Size: {{ item.size }} | Qty: {{ item.quantity }}</p>
                 </div>
                 <p class="text-sm font-medium text-gray-900">${{ (item.price * item.quantity).toFixed(2) }}</p>
               </div>
             </div>
             
             <!-- Selected Address Summary -->
             <div v-if="selectedAddress" class="border-t border-gray-200 pt-4 mb-6">
               <!-- <h3 class="text-sm font-medium text-gray-900 mb-2">Address</h3> -->
               <div class="text-xs text-gray-600 space-y-1">
                 <p>{{ selectedAddress.firstName }} {{ selectedAddress.lastName }}</p>
                 <p>+855 {{ selectedAddress.mobileNumber }}</p>
                 <p>{{ selectedAddress.address }}</p>
                 <p>{{ selectedAddress.city }}, {{ selectedAddress.country }}</p>
               </div>
             </div>

            <!-- Pricing Breakdown -->
            <div class="border-t border-gray-200 pt-4 space-y-3">
              <div class="flex justify-between text-sm text-gray-600">
                <span>Subtotal</span>
                <span>${{ cartSubtotal.toFixed(2) }}</span>
              </div>
              <div class="flex justify-between text-sm text-gray-600">
                <span>Delivery</span>
                <span>${{ deliveryFee.toFixed(2) }}</span>
              </div>
              <div v-if="totalSavings > 0" class="flex justify-between text-sm text-green-600">
                <span>Savings</span>
                <span>-${{ totalSavings.toFixed(2) }}</span>
              </div>
              <div class="flex justify-between text-lg font-bold text-gray-900 border-t border-gray-200 pt-3">
                <span>Total</span>
                <span>${{ cartTotal.toFixed(2) }}</span>
              </div>
            </div>

            <!-- Place Order Button -->
            <button 
              @click="placeOrder" 
              :disabled="!isFormValid || isProcessing"
              class="w-full mt-6 bg-pink-600 text-white py-3 px-4 rounded-lg hover:bg-pink-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition-colors duration-200 font-medium"
            >
              <span v-if="isProcessing">Processing...</span>
              <span v-else>Place Order</span>
            </button>

            <!-- Back to Cart -->
            <button 
              @click="goBackToCart" 
              class="w-full mt-3 bg-gray-200 text-gray-900 py-3 px-4 rounded-lg hover:bg-gray-300 transition-colors duration-200 font-medium"
            >
              Back to Cart
            </button>
          </div>
        </div>
      </div>
     </div>
     
     <!-- Address Form Modal -->
     <div v-if="showAddressForm" class="fixed inset-0 z-50 overflow-hidden">
       <div class="absolute inset-0 bg-black bg-opacity-50 transition-opacity" @click="closeAddressForm"></div>
       <div class="absolute inset-0 overflow-hidden">
         <div class="absolute inset-y-0 right-0 pl-10 max-w-full flex">
           <div class="w-screen max-w-md">
             <div class="h-full flex flex-col bg-white shadow-xl">
               <!-- Modal Header -->
               <div class="px-6 py-4 border-b border-gray-200">
                 <div class="flex items-center justify-between">
                   <h2 class="text-lg font-semibold text-gray-900">{{ editingAddress ? 'Edit Address' : 'Add New Address' }}</h2>
                   <button @click="closeAddressForm" class="text-gray-400 hover:text-gray-600">
                     <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                     </svg>
                   </button>
                 </div>
               </div>
               
               <!-- Address Form -->
               <div class="flex-1 overflow-y-auto p-6">
                 <form @submit.prevent="saveAddress" class="space-y-4">
                   <!-- First Name -->
                   <div>
                     <label class="block text-sm font-medium text-gray-700 mb-2">First name</label>
                     <input 
                       v-model="addressForm.firstName" 
                       type="text" 
                       required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-pink-500 focus:border-pink-500"
                       placeholder="First name"
                     >
                   </div>
                   
                   <!-- Last Name -->
                   <div>
                     <label class="block text-sm font-medium text-gray-700 mb-2">Last name</label>
                     <input 
                       v-model="addressForm.lastName" 
                       type="text" 
                       required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-pink-500 focus:border-pink-500"
                       placeholder="Last name"
                     >
                   </div>
                   
                   <!-- Mobile Number -->
                   <div>
                     <label class="block text-sm font-medium text-gray-700 mb-2">
                       <span class="text-gray-500">+ 855</span> Mobile number *
                     </label>
                     <input 
                       v-model="addressForm.mobileNumber" 
                       type="tel" 
                       required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-pink-500 focus:border-pink-500"
                       placeholder="Mobile number"
                     >
                   </div>
                   
                   <!-- Address -->
                   <div>
                     <label class="block text-sm font-medium text-gray-700 mb-2">Address *</label>
                     <textarea 
                       v-model="addressForm.address" 
                       required
                       rows="3"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-pink-500 focus:border-pink-500"
                       placeholder="Enter your address"
                     ></textarea>
                   </div>
                   
                   <!-- Country -->
                   <div>
                     <label class="block text-sm font-medium text-gray-700 mb-2">Available country</label>
                     <select 
                       v-model="addressForm.country" 
                       required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-pink-500 focus:border-pink-500"
                     >
                       <option value="">Country</option>
                       <option value="Cambodia">Cambodia</option>
                     </select>
                   </div>
                   
                   <!-- City/Province -->
                   <div>
                     <label class="block text-sm font-medium text-gray-700 mb-2">City / province *</label>
                     <select 
                       v-model="addressForm.city" 
                       required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-pink-500 focus:border-pink-500"
                     >
                       <option value="">-- Select city / province *</option>
                       <option value="Phnom Penh">Phnom Penh</option>
                       <option value="Siem Reap">Siem Reap</option>
                       <option value="Battambang">Battambang</option>
                       <option value="Sihanoukville">Sihanoukville</option>
                       <option value="Kampot">Kampot</option>
                       <option value="Kep">Kep</option>
                       <option value="Takeo">Takeo</option>
                       <option value="Kandal">Kandal</option>
                       <option value="Kampong Cham">Kampong Cham</option>
                       <option value="Kampong Thom">Kampong Thom</option>
                       <option value="Preah Sihanouk">Preah Sihanouk</option>
                       <option value="Koh Kong">Koh Kong</option>
                       <option value="Pursat">Pursat</option>
                       <option value="Kampong Chhnang">Kampong Chhnang</option>
                       <option value="Kampong Speu">Kampong Speu</option>
                       <option value="Prey Veng">Prey Veng</option>
                       <option value="Svay Rieng">Svay Rieng</option>
                       <option value="Tbong Khmum">Tbong Khmum</option>
                       <option value="Kratie">Kratie</option>
                       <option value="Mondulkiri">Mondulkiri</option>
                       <option value="Ratanakiri">Ratanakiri</option>
                       <option value="Stung Treng">Stung Treng</option>
                       <option value="Preah Vihear">Preah Vihear</option>
                       <option value="Oddar Meanchey">Oddar Meanchey</option>
                       <option value="Banteay Meanchey">Banteay Meanchey</option>
                       <option value="Pailin">Pailin</option>
                     </select>
                   </div>
                   
                   <!-- Set as Default -->
                   <div class="flex items-center space-x-2">
                     <input 
                       v-model="addressForm.isDefault" 
                       type="checkbox" 
                       id="setAsDefault"
                       class="text-pink-600 focus:ring-pink-500"
                     >
                     <label for="setAsDefault" class="text-sm font-medium text-gray-700">Set As Default</label>
                   </div>
                   
                   <!-- Action Buttons -->
                   <div class="flex space-x-3 pt-4">
                     <button 
                       type="button"
                       @click="closeAddressForm" 
                       class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50"
                     >
                       Cancel
                     </button>
                     <button 
                       type="submit" 
                       class="flex-1 px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700"
                     >
                       Save
                     </button>
                   </div>
                 </form>
               </div>
             </div>
           </div>
         </div>
       </div>
     </div>
   </div>
 </template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import api from "../../axios/Axios";

const router = useRouter();

const cartItems = ref([]);
const isProcessing = ref(false);

// Address state
const showAddressForm = ref(false);
const editingAddress = ref(null);
const selectedAddress = ref(null);
const addresses = ref([]);

const addressForm = ref({
  firstName: '',
  lastName: '',
  mobileNumber: '',
  address: '',
  country: 'Cambodia',
  city: '',
  isDefault: false
});

// Payment + order
const paymentMethod = ref('creditCard');
const cardInfo = ref({ number: '', expiry: '', cvv: '', name: '' });
const orderNotes = ref('');

// Cart totals
const cartSubtotal = computed(() =>
  cartItems.value.reduce((t, i) => t + (i.price * i.quantity), 0)
);
const deliveryFee = computed(() => (cartSubtotal.value > 0 ? 1.0 : 0));
const totalSavings = computed(() => (cartSubtotal.value >= 50 ? cartSubtotal.value * 0.1 : 0));
const cartTotal = computed(() => cartSubtotal.value + deliveryFee.value - totalSavings.value);

const isFormValid = computed(() => {
  const isAddressValid = selectedAddress.value !== null;
  let isPaymentValid = true;
  if (paymentMethod.value === 'creditCard') {
    const required = ['number', 'expiry', 'cvv', 'name'];
    isPaymentValid = required.every(f => cardInfo.value[f]?.trim());
  }
  return isAddressValid && isPaymentValid && cartItems.value.length > 0;
});

// Load cart
const loadCartItems = async () => {
  const token = sessionStorage.getItem("auth_token");
  const response = await api.get("/cart", { headers: { Authorization: `Bearer ${token}` } });
  cartItems.value = response.data.data.items;
};

// Place order
const placeOrder = async () => {
  if (!isFormValid.value) return;
  isProcessing.value = true;
  try {
    await new Promise(r => setTimeout(r, 2000));
    const order = {
      id: Date.now().toString(),
      items: cartItems.value,
      shipping: selectedAddress.value,
      payment: {
        method: paymentMethod.value,
        cardInfo: paymentMethod.value === 'creditCard' ? cardInfo.value : null
      },
      notes: orderNotes.value,
      subtotal: cartSubtotal.value,
      deliveryFee: deliveryFee.value,
      savings: totalSavings.value,
      total: cartTotal.value,
      status: 'pending',
      createdAt: new Date().toISOString()
    };

    const existingOrders = JSON.parse(localStorage.getItem('orders') || '[]');
    existingOrders.push(order);
    localStorage.setItem('orders', JSON.stringify(existingOrders));

    localStorage.removeItem('cartItems');
    localStorage.removeItem('totalCartCount');

    window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: 0 } }));

    alert('Order placed successfully! Order number: ' + order.id);
    router.push('/dashboard');
  } catch (e) {
    console.error(e);
    alert('Error placing order.');
  } finally {
    isProcessing.value = false;
  }
};

const goBackToCart = () => {
  window.dispatchEvent(new CustomEvent('open-cart-drawer'));
};

// Address management
const loadAddresses = () => {
  const saved = localStorage.getItem('userAddresses');
  if (saved) {
    addresses.value = JSON.parse(saved);
    const def = addresses.value.find(a => a.isDefault);
    if (def) selectedAddress.value = def;
  }
};

const openAddressForm = () => {
  editingAddress.value = null;
  resetAddressForm();
  showAddressForm.value = true;
};

const editAddress = (address) => {
  editingAddress.value = address;
  addressForm.value = { ...address };
  showAddressForm.value = true;
};

const deleteAddress = (addressId) => {
  addresses.value = addresses.value.filter(addr => addr.id !== addressId);
  localStorage.setItem('userAddresses', JSON.stringify(addresses.value));

  if (selectedAddress.value?.id === addressId) {
    selectedAddress.value = addresses.value.find(a => a.isDefault) || null;
  }
};

const closeAddressForm = () => {
  showAddressForm.value = false;
  editingAddress.value = null;
  resetAddressForm();
};

const resetAddressForm = () => {
  addressForm.value = {
    firstName: '',
    lastName: '',
    mobileNumber: '',
    address: '',
    country: 'Cambodia',
    city: '',
    isDefault: false
  };
};

const saveAddress = () => {
  let newAddress = null;

  if (editingAddress.value) {
    const idx = addresses.value.findIndex(a => a.id === editingAddress.value.id);
    if (idx !== -1) {
      addresses.value[idx] = { ...addressForm.value, id: editingAddress.value.id };
    }
  } else {
    newAddress = { ...addressForm.value, id: Date.now().toString() };
    addresses.value.push(newAddress);
  }

  // Handle default
  if (addressForm.value.isDefault) {
    addresses.value.forEach(a => {
      if (a.id !== (editingAddress.value?.id || newAddress?.id)) a.isDefault = false;
    });
  }

  // Save
  localStorage.setItem('userAddresses', JSON.stringify(addresses.value));

  // Select default or new
  if (addressForm.value.isDefault) {
    selectedAddress.value = addresses.value.find(a => a.isDefault);
  } else if (!selectedAddress.value) {
    selectedAddress.value = newAddress || editingAddress.value;
  }

  closeAddressForm();
};

onMounted(() => {
  loadCartItems();
  loadAddresses();
});
</script>


<style scoped>
/* Add any custom styles here */
</style>
