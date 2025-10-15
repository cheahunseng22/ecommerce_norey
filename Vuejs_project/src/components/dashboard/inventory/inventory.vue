<template>
 <div class="p-4 sm:p-6 md:p-8 bg-gray-50 min-h-screen">
  <div class="bg-white p-6 rounded-lg shadow-md">
    <!-- Header + Create Button -->
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-3xl font-bold text-gray-800">Inventory Management</h2>
       <!-- Search Bar -->
         <button
        @click="openCreateModal"
        class="bg-green-600 text-white px-5 py-2.5 rounded-xl hover:bg-green-700 flex items-center gap-2 shadow-md"
      >
        <i class="fas fa-plus"></i>
        Create Inventory
      </button>

    </div>

    <!-- Inventory Table -->
    <div  class="mt-6 overflow-x-auto bg-white rounded-xl shadow-sm border border-gray-200">
      <table class="min-w-full text-sm text-gray-700">
        <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
          <tr>
            <th class="px-4 py-3 text-left">ID</th>
            <th class="px-4 py-3 text-left">Product Name</th>
            <th class="px-4 py-3 text-left">Supplier Name</th>
            <th class="px-4 py-3 text-center">In Stock</th>
            <th class="px-4 py-3 text-center">Last Restock</th>
            <th class="px-4 py-3 text-center">Reorder Point</th>
            <th class="px-4 py-3 text-center">Reorder Qty</th>
            <th class="px-4 py-3 text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="item in paginatedData"
            :key="item.id"
            class="border-b hover:bg-gray-50"
          >
            <td class="px-4 py-3">{{ item.id }}</td>
            <td class="px-4 py-3">{{ item.product.name }}</td>
            <td class="px-4 py-3">{{ item.supplier.name }}</td>
            <td class="px-4 py-3 text-center">{{ item.quantity_in_stock }}</td>
            <td class="px-4 py-3 text-center">{{ item.last_restock_date }}</td>
            <td class="px-4 py-3 text-center">{{ item.reorder_point }}</td>
            <td class="px-4 py-3 text-center">{{ item.reorder_quantity }}</td>
            <td class="px-4 py-3 flex justify-center gap-3">
              <button
                @click="openEditModal(item)"
                class="text-blue-600 hover:text-blue-800 flex items-center gap-1"
              >
                <i class="fas fa-edit"></i> Edit
              </button>
              <button
                @click="deleteInventory(item.id)"
                class="text-red-600 hover:text-red-800 flex items-center gap-1"
              >
                <i class="fas fa-trash"></i> Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

  <!-- Pagination -->
  <div class="flex items-center justify-center pt-6">
        <nav class="flex items-center space-x-2">
          <button @click="changePage(currentPage - 1)" :disabled="currentPage === 1" class="p-2 rounded-md hover:bg-gray-100 disabled:opacity-50">
            <i class="fas fa-chevron-left"></i>
          </button>
          <span class="px-4 py-2 text-sm font-medium bg-blue-500 text-white rounded-md">{{ currentPage }}</span>
          <button @click="changePage(currentPage + 1)" :disabled="currentPage >= totalPages" class="p-2 rounded-md hover:bg-gray-100 disabled:opacity-50">
            <i class="fas fa-chevron-right"></i>
          </button>
        </nav>
      </div>

    <!-- Create/Edit Modal -->
    <div
      v-if="showModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50"
    >
      <div class="bg-white rounded-xl shadow-xl max-w-lg w-full p-6">
        <h3 class="text-2xl font-semibold mb-6 text-gray-800">
          {{ editingInventory ? 'Edit Inventory' : 'Create Inventory' }}
        </h3>

        <form @submit.prevent="submitForm" class="space-y-4">
          <!-- Product -->
          <div>
            <label class="block text-gray-600 mb-1">Product</label>
            <select
              v-model="form.product_id"
              required
              class="w-full border rounded px-3 py-2"
            >
              <option disabled value="">Select Product</option>
              <option v-for="p in products" :key="p.id" :value="p.id">
                {{ p.name }}
              </option>
            </select>
          </div>

          <!-- Supplier -->
          <div>
            <label class="block text-gray-600 mb-1">Supplier</label>
            <select
              v-model="form.supplier_id"
              required
              class="w-full border rounded px-3 py-2"
            >
              <option disabled value="">Select Supplier</option>
              <option v-for="s in suppliers" :key="s.id" :value="s.id">
                {{ s.name }}
              </option>
            </select>
          </div>

          <!-- Stock & Restock -->
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-gray-600 mb-1">Quantity in Stock</label>
              <input
                v-model="form.quantity_in_stock"
                type="number"
                required
                class="w-full border rounded px-3 py-2"
              />
            </div>
            <div>
              <label class="block text-gray-600 mb-1">Last Restock Date</label>
              <input
                v-model="form.last_restock_date"
                type="date"
                required
                class="w-full border rounded px-3 py-2"
              />
            </div>
          </div>

          <!-- Reorder -->
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-gray-600 mb-1">Reorder Point</label>
              <input
                v-model="form.reorder_point"
                type="number"
                required
                class="w-full border rounded px-3 py-2"
              />
            </div>
            <div>
              <label class="block text-gray-600 mb-1">Reorder Quantity</label>
              <input
                v-model="form.reorder_quantity"
                type="number"
                required
                class="w-full border rounded px-3 py-2"
              />
            </div>
          </div>

          <!-- Buttons -->
          <div class="flex gap-3 pt-4">
            <button
              type="button"
              @click="closeModal"
              class="flex-1 bg-gray-200 py-2 rounded hover:bg-gray-300"
            >
              Cancel
            </button>
            <button
              type="submit"
              class="flex-1 bg-green-600 text-white py-2 rounded hover:bg-green-700"
            >
              {{ editingInventory ? 'Update' : 'Create' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
  </div>
</template>

<script setup>
import { onMounted, ref, computed } from "vue";
import api from "../../../axios/Axios";

const inventories = ref([]);
const products = ref([]);
const suppliers = ref([]);

const showModal = ref(false);
const editingInventory = ref(false);
const form = ref({
  id: null,
  product_id: "",
  supplier_id: "",
  quantity_in_stock: "",
  last_restock_date: "",
  reorder_point: "",
  reorder_quantity: "",
});

// Pagination state
const currentPage = ref(1);
const perPage = ref(5);

const totalPages = computed(() =>
  Math.ceil(inventories.value.length / perPage.value)
);
const paginatedData = computed(() => {
  const start = (currentPage.value - 1) * perPage.value;
  return inventories.value.slice(start, start + perPage.value);
});

function nextPage() {
  if (currentPage.value < totalPages.value) currentPage.value++;
}
function prevPage() {
  if (currentPage.value > 1) currentPage.value--;
}
function goToPage(page) {
  currentPage.value = page;
}

// Fetch data
onMounted(async () => {
  await fetchDropdownData();
  await fetchInventories();
});

async function fetchDropdownData() {
  try {
    const [prodRes, supRes] = await Promise.all([
      api.get("/products"),
      api.get("/suppliers"),
    ]);
    products.value = prodRes.data.data;
    suppliers.value = supRes.data;
  } catch (err) {
    console.error("Error loading products or suppliers:", err);
  }
}

async function fetchInventories() {
  try {
    const res = await api.get("/inventories");
    inventories.value = res.data.data.data;
  } catch (err) {
    console.error("Error loading inventories:", err);
  }
}

function openCreateModal() {
  editingInventory.value = false;
  Object.assign(form.value, {
    id: "",
    product_id: "",
    supplier_id: "",
    quantity_in_stock: "",
    last_restock_date: "",
    reorder_point: "",
    reorder_quantity: "",
  });
  showModal.value = true;
}

function openEditModal(item) {
  editingInventory.value = true;
  Object.assign(form.value, item);
  showModal.value = true;
}

function closeModal() {
  showModal.value = false;
}

async function submitForm() {
  const token = sessionStorage.getItem("auth_token");
  const headers = token ? { Authorization: `Bearer ${token}` } : {};

  try {
    if (editingInventory.value) {
      await api.put(`/inventory/${form.value.id}`, form.value, { headers });
    } else {
      await api.post("/inventories", form.value, { headers });
    }
    await fetchInventories();
    closeModal();
  } catch (err) {
    console.error("Failed to save inventory", err);
  }
}

async function deleteInventory(id) {
  if (!confirm("Confirm deletion?")) return;
  try {
    await api.delete(`/inventory/${id}`);
    inventories.value = inventories.value.filter((i) => i.id !== id);
  } catch (err) {
    console.error("Failed to delete:", err);
  }
}
</script>
