<template>
  <div class="p-6 bg-gray-50 min-h-screen font-sans">
    <div class="bg-white rounded-lg shadow-xl p-6">
      <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
          <h1 class="text-3xl font-extrabold text-gray-900">Customers</h1>
          <p class="text-gray-600 mt-1">Efficiently manage your customer database.</p>
        </div>
        <button
          @click="openCreateModal"
          class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg flex items-center gap-2 text-lg font-medium shadow-md transition duration-300 ease-in-out transform hover:scale-105"
        >
          <i class="fas fa-plus"></i> Add Customer
        </button>
      </header>

      <div class="mb-6 flex flex-col md:flex-row gap-4 items-center">
        <div class="relative flex-1 w-full">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search customers by name or email..."
            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-200 text-gray-700"
            @input="debounceFetchCustomers"
          >
          <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
        </div>
        <select
          v-model="statusFilter"
          class="w-full md:w-auto border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-blue-500 focus:border-blue-500 transition duration-200 text-gray-700"
          @change="fetchCustomers"
        >
          <option value="">All Statuses</option>
          <option value="active">Active</option>
          <option value="inactive">Inactive</option>
        </select>
      </div>

      <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-sm">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Customer</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Contact</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Orders</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-if="isLoadingTable">
              <td colspan="5" class="px-6 py-12 text-center text-gray-500 text-lg">
                <i class="fas fa-spinner fa-spin mr-2"></i> Loading customers...
              </td>
            </tr>
            <tr v-else-if="customers.length === 0">
              <td colspan="5" class="px-6 py-12 text-center text-gray-500 text-lg">
                No customers found.
              </td>
            </tr>
            <tr v-else v-for="customer in customers" :key="customer.id" class="hover:bg-gray-50 transition duration-150 ease-in-out">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-10 w-10">
                    <img class="h-10 w-10 rounded-full object-cover border border-gray-200" :src="customer.avatar || defaultAvatar" :alt="customer.name">
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">{{ customer.name }}</div>
                    <div class="text-sm text-gray-500">{{ customer.email }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ customer.phone }}</div>
                <div class="text-sm text-gray-500">{{ customer.address || 'N/A' }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span
                  class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full capitalize"
                  :class="customer.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
                >
                  {{ customer.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                {{ customer.orders_count }} orders
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <button
                 @click="editCustomer(customer)" 
                 class="text-blue-600 hover:text-blue-800 mr-4 transition duration-150 ease-in-out transform hover:scale-105">
                  <i class="fas fa-edit mr-1"></i> Edit
                </button>
             <button
            @click="deleteCustomer"
            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700"
          >
            Delete
          </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="mt-6 flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="text-sm text-gray-700">
          Showing <span class="font-semibold">{{ pagination.from }}</span> to <span class="font-semibold">{{ pagination.to }}</span> of <span class="font-semibold">{{ pagination.total }}</span> customers
        </div>
        <div class="flex space-x-2">
          <button
            @click="prevPage"
            :disabled="pagination.current_page === 1"
            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 transition duration-200"
            :class="pagination.current_page === 1 ? 'bg-gray-100 cursor-not-allowed' : 'hover:bg-gray-100'"
          >
            Previous
          </button>
          <button
            @click="nextPage"
            :disabled="pagination.current_page === pagination.last_page"
            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 transition duration-200"
            :class="pagination.current_page === pagination.last_page ? 'bg-gray-100 cursor-not-allowed' : 'hover:bg-gray-100'"
          >
            Next
          </button>
        </div>
      </div>
    </div>

    <CustomerFormModal
      :show="showCreateModal || showEditModal"
      :customer="editingCustomer"
      :loading="isGlobalLoading"
      @close="closeModal"
      @submit-form="handleCustomerSubmit"
    />

    <ConfirmationModal
      :show="showDeleteModal"
      title="Delete Customer"
      message="Are you sure you want to delete this customer? This action cannot be undone."
      @confirm="deleteCustomer"
      @cancel="showDeleteModal = false"
    />

    <div v-if="isGlobalLoading" class="fixed inset-0 bg-black bg-opacity-75 flex justify-center items-center z-[110] text-white text-2xl">
      <i class="fas fa-spinner fa-spin mr-3"></i> Processing...
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '../../../axios/Axios'; // Import the custom axios instance
import CustomerFormModal from './CustomerFormModal.vue';
// import ConfirmationModal from './ConfirmationModal.vue'; // Make sure this path is correct

const customers = ref([]);
const searchQuery = ref('');
const statusFilter = ref('');
const showCreateModal = ref(false);
const showEditModal = ref(false);
const showDeleteModal = ref(false);
const editingCustomer = ref(null);
const customerToDelete = ref(null);
const isLoadingTable = ref(false);
const isGlobalLoading = ref(false);
let searchTimeout = null; // For debouncing

const pagination = ref({
  current_page: 1,
  last_page: 1,
  from: 0,
  to: 0,
  total: 0,
  per_page: 10
});
const defaultAvatar = 'https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&f=y';

// --- Debounce Search Input ---
const debounceFetchCustomers = () => {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    pagination.value.current_page = 1; // Reset to first page on search
    fetchCustomers();
  }, 300); // 300ms debounce time
};

// --- Fetch Customers ---
const fetchCustomers = async () => {
  isLoadingTable.value = true;
  try {
   
    const response = await api.get('/customers');

    customers.value = response.data.data;
    pagination.value = {
      current_page: response.data.current_page,
      last_page: response.data.last_page,
      from: response.data.from,
      to: response.data.to,
      total: response.data.total,
      per_page: response.data.per_page
    };
  } catch (error) {
    console.error('Error fetching customers:', error.response?.data || error.message);
    // The interceptor should handle 401, so other errors are network or server-side validation
    alert('Failed to fetch customers. Please check console for details.');
  } finally {
    isLoadingTable.value = false;
  }
};

// --- Pagination ---
const prevPage = () => {
  if (pagination.value.current_page > 1) {
    pagination.value.current_page--;
    fetchCustomers();
  }
};

const nextPage = () => {
  if (pagination.value.current_page < pagination.value.last_page) {
    pagination.value.current_page++;
    fetchCustomers();
  }
};

// --- Customer Actions (Modal Control & API Calls) ---
const openCreateModal = () => {
  editingCustomer.value = null;
  showCreateModal.value = true;
};

const editCustomer = (customer) => {
  editingCustomer.value = { ...customer };
  showEditModal.value = true;
};

function confirmDelete(id) {
  customerToDelete.value = id;
  showDeleteModal.value = true;
}

const deleteCustomer = async (id) => {
  // Ensure we have a customer ID to delete
  console.log(id)
   if (confirm(`Are you sure you want to delete customer ${customers}?`)) {
    // customerToDelete.value = customers.value.filter(p => p.customers !== customers);
  }
  

  isGlobalLoading.value = true;
  try {
    await api.delete(`/customers/${customerToDelete.value.id}`);
    alert('Customer deleted successfully!');
    showDeleteModal.value = false;

    // After deletion, we need to refresh the list.
    // Check if the current page will become empty after deletion
    // If it's the last item on the current page AND not the first page,
    // decrement the current page to prevent showing an empty page.
    if (customers.value.length === 1 && pagination.value.current_page > 1) {
      pagination.value.current_page--;
    }
    
    // Always refetch customers to ensure the UI is in sync with the backend
    await fetchCustomers();

  } catch (error) {
    console.error('Error deleting customer:', error.response?.data || error.message);
    alert('Failed to delete customer. Please check console for details.');
  } finally {
    isGlobalLoading.value = false;
    // It's good practice to clear the customerToDelete after the operation is complete
    // to prevent accidental re-deletions if the modal is reopened or component re-rendered.
    customerToDelete.value = null; 
  }
};

// Handle data submitted from CustomerFormModal
const handleCustomerSubmit = async ({ formData, isUpdate, customerId }) => {
  isGlobalLoading.value = true;

  try {
    let response;
    if (isUpdate) {
      formData.append('_method', 'PUT'); // Important for Laravel with FormData PUT/PATCH for update
      response = await api.post(`/customers/${customerId}`, formData);
    } else {
      // Create customer using JSON payload as requested, while preserving existing fields
      const payload = {
        // Original customer fields
        name: formData.get('name') || '',
        email: formData.get('email') || '',
        phone: formData.get('phone') || '',
        status: formData.get('status') || 'active',
        orders_count: formData.get('orders_count') ? Number(formData.get('orders_count')) : 0,
        // Additional fields from the provided JSON shape
        full_name: formData.get('name') || '',
        terms: true
      };
      const password = formData.get('password');
      const passwordConfirmation = formData.get('password_confirmation');
      if (password) {
        payload.password = password;
        payload.password_confirmation = passwordConfirmation || password;
      }
      response = await api.post('/customers', payload);
    }

    console.log('Customer saved successfully:', response.data);
    alert(`Customer ${isUpdate ? 'updated' : 'created'} successfully!`);
    closeModal();
    await fetchCustomers();

  } catch (error) {
    console.error("Error saving customer:", error.response?.data || error.message);
    const errorMessage = error.response?.data?.message || 'Failed to save customer. Please check console for details.';
    alert(`Failed to save customer: ${errorMessage}`);
  } finally {
    isGlobalLoading.value = false;
  }
};

const closeModal = () => {
  showCreateModal.value = false;
  showEditModal.value = false;
  editingCustomer.value = null;
};

// --- Initialization ---
onMounted(fetchCustomers);
</script>

<style scoped>
/* Scoped styles are handled well by TailwindCSS.
   Additional animations for specific elements (like table rows)
   would require more complex JavaScript or `:deep()` selectors if not in a transition group. */
</style>