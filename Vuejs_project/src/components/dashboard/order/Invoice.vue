<template>
  <div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Header Section -->
      <div class="sm:flex sm:items-center sm:justify-between mb-8">
        <div class="mb-4 sm:mb-0">
          <router-link to="/admin/order/Invoice">
          <h1 class="text-3xl font-bold text-gray-900">Order History</h1>
          <p class="mt-1 text-sm text-gray-500">Invoice #{{ invoiceNumber }}</p>
          </router-link>
        </div>
        <button class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md">
          <i class="fas fa-print mr-2"></i>
          Print Invoice
        </button>
      </div>

      <!-- Invoice Container -->
      <div class="bg-white shadow-sm rounded-lg overflow-hidden p-6 lg:p-8">
        <!-- From/To Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
          <div class="bg-gray-50 p-6 rounded-lg">
            <h3 class="text-sm font-medium text-gray-500 uppercase mb-4">From</h3>
            <div class="space-y-2">
              <p class="text-gray-900 font-medium">Gourmet Haven</p>
              <p class="text-gray-600">123 Restaurant Row</p>
              <p class="text-gray-600">Culinary City, CC 12345</p>
              <p class="text-gray-600">(555) 123-4567</p>
              <p class="text-gray-600">contact@gourmethaven.com</p>
            </div>
          </div>
          
          <div class="bg-gray-50 p-6 rounded-lg">
            <h3 class="text-sm font-medium text-gray-500 uppercase mb-4">Bill To</h3>
            <div class="space-y-2">
              <p class="text-gray-900 font-medium">{{ clientName }}</p>
              <p class="text-gray-600">{{ clientAddress }}</p>
            </div>
          </div>
        </div>

        <!-- Invoice Details -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
          <div class="bg-indigo-50 p-4 rounded-lg">
            <p class="text-xs font-medium text-indigo-600 uppercase">Invoice Number</p>
            <p class="text-gray-900">{{ invoiceNumber }}</p>
          </div>
          <div class="bg-indigo-50 p-4 rounded-lg">
            <p class="text-xs font-medium text-indigo-600 uppercase">Invoice Date</p>
            <p class="text-gray-900">{{ invoiceDate }}</p>
          </div>
          <div class="bg-indigo-50 p-4 rounded-lg">
            <p class="text-xs font-medium text-indigo-600 uppercase">Due Date</p>
            <p class="text-gray-900">{{ dueDate }}</p>
          </div>
          <div class="bg-indigo-50 p-4 rounded-lg">
            <p class="text-xs font-medium text-indigo-600 uppercase">Status</p>
            <p class="text-emerald-600 font-medium">Paid</p>
          </div>
        </div>

        <!-- Items Table -->
        <div class="overflow-x-auto rounded-lg border border-gray-200">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Item Description
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Qty
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Unit Price
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Total
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="(item, index) in items" :key="index" class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ item.description }}</td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ item.quantity }}</td>
                <td class="px-6 py-4 text-sm text-gray-600">${{ item.unitPrice.toFixed(2) }}</td>
                <td class="px-6 py-4 text-sm text-gray-900 font-medium">${{ item.totalPrice.toFixed(2) }}</td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Totals Section -->
        <div class="mt-8 space-y-4 max-w-md ml-auto">
          <div class="flex justify-between items-center">
            <span class="text-gray-600">Subtotal:</span>
            <span class="text-gray-900 font-medium">${{ subtotal.toFixed(2) }}</span>
          </div>
          <div class="flex justify-between items-center">
            <span class="text-gray-600">Tax ({{ taxRate * 100 }}%):</span>
            <span class="text-gray-900 font-medium">${{ taxAmount.toFixed(2) }}</span>
          </div>
          <div class="flex justify-between items-center pt-4 border-t border-gray-200">
            <span class="text-lg font-bold text-gray-900">Grand Total:</span>
            <span class="text-lg font-bold text-indigo-600">${{ grandTotal.toFixed(2) }}</span>
          </div>
        </div>

        <!-- Payment Info -->
        <div class="mt-8 bg-blue-50 p-6 rounded-lg">
          <h3 class="text-sm font-medium text-blue-600 uppercase mb-3">Payment Information</h3>
          <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
              <p class="text-gray-600">Payment Method:</p>
              <p class="text-gray-900 font-medium">Credit Card (**** 4242)</p>
            </div>
            <div>
              <p class="text-gray-600">Payment Date:</p>
              <p class="text-gray-900 font-medium">2024-01-26 14:30</p>
            </div>
          </div>
        </div>

        <!-- Footer Note -->
        <div class="mt-8 text-center text-sm text-gray-500">
          <p>Thank you for choosing Gourmet Haven!</p>
          <p>Please contact us at contact@gourmethaven.com for any inquiries</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed } from 'vue';

export default {
  setup() {
    // Data (same as original)
    const clientName = ref('John Doe');
    const clientAddress = ref('123 Main Street, Anytown, USA');
    const invoiceNumber = ref('INV-2024-001');
    const invoiceDate = ref('2024-01-26');
    const dueDate = ref('2024-02-26');
    const taxRate = ref(0.07);

    const items = ref([
      { description: 'Grilled Salmon with Asparagus', quantity: 2, unitPrice: 25.00, totalPrice: 50.00 },
      { description: 'Caesar Salad', quantity: 1, unitPrice: 12.00, totalPrice: 12.00 },
      { description: 'Catering Service', quantity: 1, unitPrice: 500.00, totalPrice: 500.00 },
      { description: 'Delivery Fee', quantity: 1, unitPrice: 20.00, totalPrice: 20.00 },
    ]);

    // Computed properties (same as original)
    const subtotal = computed(() => items.value.reduce((sum, item) => sum + item.totalPrice, 0));
    const taxAmount = computed(() => subtotal.value * taxRate.value);
    const grandTotal = computed(() => subtotal.value + taxAmount.value);

    return {
      clientName,
      clientAddress,
      invoiceNumber,
      invoiceDate,
      dueDate,
      items,
      subtotal,
      taxRate,
      taxAmount,
      grandTotal,
    };
  },
};
</script>

<style scoped>
@import "font-awesome/css/font-awesome.min.css";
</style>