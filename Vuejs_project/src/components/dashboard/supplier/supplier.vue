<template>
  <div class="p-4 sm:p-6 md:p-8 bg-gray-50 min-h-screen">
    <div class="bg-white p-6 rounded-lg shadow-md">
    <!-- Header -->
    <header class="pb-6 border-b border-gray-200">
        <h1 class="text-2xl font-bold text-gray-800">Supplier</h1>
        <div class="mt-4 flex flex-col md:flex-row items-start md:items-center justify-between">
          <!-- Search Bar -->
          <div class="relative w-full md:w-1/3">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <i class="fas fa-search text-gray-400"></i>
            </div>
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Search Supplier..."
              class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
            />
          </div>

          <!-- Add Button -->
          <button
            @click="openCreateModal"
            class="mt-4 md:mt-0 bg-indigo-600 text-white px-6 py-3 rounded-md shadow-md hover:bg-indigo-700 transition font-semibold flex items-center"
          >
            <i class="fas fa-plus mr-2"></i>
            Create Supplier
          </button>
        </div>
      </header>
    
    <!-- Search + Filter -->
    <!-- <div class="mb-6 flex flex-col sm:flex-row gap-4">
      <div class="relative flex-1">
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Search suppliers by name, email, or phone..."
          class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
        />
        <svg
          class="w-5 h-5 text-gray-400 absolute left-3 top-2.5"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
          />
        </svg>
      </div>
    </div> -->

    <!-- Suppliers Table -->
    <div
      v-if="filteredAndSortedSuppliers.length > 0"
      class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden"
    >
      <!-- <div class="overflow-x-auto"> -->
        <!-- <table class="min-w-full divide-y divide-gray-200"> -->
          <table class="min-w-full text-sm text-gray-700">
          <thead class="bg-gray-100 text-gray-700 uppercase text-xs">

            <tr>
              <th
                class="px-4 py-3 text-left"
              >
                Supplier Info
              </th>
              <th
                class="px-4 py-3 text-left"
              >
                Contact
              </th>
              <th
                class="px-4 py-3 text-left"
              >
                Address
              </th>
              <th
                class="px-4 py-3 text-center"
              >
                Actions
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="supplier in paginatedSuppliers" :key="supplier.id">
              <td class="px-6 py-4">
                <div class="font-medium">{{ supplier.name }}</div>
                <div class="text-sm text-gray-500">ID: #{{ supplier.id }}</div>
              </td>
              <td class="px-6 py-4">
                <div v-if="supplier.phone">{{ supplier.phone }}</div>
                <div v-if="supplier.email">{{ supplier.email }}</div>
                <div
                  v-if="!supplier.phone && !supplier.email"
                  class="text-gray-400 italic"
                >
                  No contact
                </div>
              </td>
              <td class="px-6 py-4">{{ supplier.address || "No address" }}</td>
              <td class="px-6 py-4 flex justify-center gap-2">
                <button
                  @click="viewSupplier(supplier)"
                  class="text-orange-600 hover:text-orange-800"
                ><i class="fas fa-eye mr-1"></i>
                  View
                </button>
                <button
                  @click="editSupplier(supplier)"
                  
                 class="text-blue-600 hover:text-blue-800 mr-4 transition duration-150 ease-in-out transform hover:scale-105"
                ><i class="fas fa-edit mr-1"></i>
                  Edit
                </button>
                <button
                  @click="deleteSupplier(supplier.id)"
                  class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                  <i class="fas fa-trash mr-1"></i>
                
                  Delete
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      <!-- </div> -->
    </div>

    <!-- Empty -->
    <div v-else class="text-center py-12 text-gray-500">
      No suppliers found. Add one!
    </div>

    <!-- Create/Edit Modal -->
    <div
      v-if="modalMode !== null"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50"
    >
      <div class="bg-white rounded-xl shadow-xl max-w-lg w-full">
        <div class="flex justify-between items-center p-4 border-b">
          <h2 class="text-xl font-semibold">
            {{
              viewingSupplier
                ? "View Supplier"
                : editingSupplier
                ? "Edit Supplier"
                : "Create Supplier"
            }}
          </h2>
          <button @click="closeModal">✖</button>
        </div>

        <!-- Form -->
        <form
          v-if="!viewingSupplier"
          @submit.prevent="submitForm"
          class="p-4 space-y-3"
        >
          <input
            v-model="form.name"
            type="text"
            placeholder="Supplier Name"
            class="w-full border rounded px-3 py-2"
            required
          />
          <input
            v-model="form.phone"
            type="text"
            placeholder="Phone"
            class="w-full border rounded px-3 py-2"
          />
          <input
            v-model="form.email"
            type="email"
            placeholder="Email"
            class="w-full border rounded px-3 py-2"
          />
          <textarea
            v-model="form.address"
            rows="2"
            placeholder="Address"
            class="w-full border rounded px-3 py-2"
          ></textarea>

          <div class="flex gap-2">
            <button
              type="button"
              @click="closeModal"
              class="flex-1 bg-gray-200 py-2 rounded"
            >
              Cancel
            </button>
            <button
              type="submit"
              class="flex-1 bg-green-600 text-white py-2 rounded"
            >
              {{ editingSupplier ? "Update" : "Create" }}
            </button>
          </div>
        </form>

        <!-- View Mode -->
        <div v-else class="p-4 space-y-2">
          <p><strong>Name:</strong> {{ viewingSupplier.name }}</p>
          <p><strong>Phone:</strong> {{ viewingSupplier.phone || "-" }}</p>
          <p><strong>Email:</strong> {{ viewingSupplier.email || "-" }}</p>
          <p><strong>Address:</strong> {{ viewingSupplier.address || "-" }}</p>

          <div class="flex gap-2 pt-3">
            <button
              @click="editSupplier(viewingSupplier)"
              class="flex-1 bg-green-600 text-white py-2 rounded"
            >
              Edit
            </button>
            <button @click="closeModal" class="flex-1 bg-gray-200 py-2 rounded">
              Close
            </button>
          </div>
        </div>
      </div>
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
  </div>
</div>
</template>

<script setup>
import { ref, computed, onMounted, reactive } from 'vue'
import api from '../../../axios/Axios'

const suppliers = ref([])
const searchQuery = ref('')
const sortKey = ref('id')
const sortOrder = ref('asc')
const currentPage = ref(1)
const itemsPerPage = 5

// 👉 Modal control: "create" | "edit" | "view" | null
const modalMode = ref(null)
const form = reactive({
  id: null,
  name: '',
  phone: '',
  email: '',
  address: ''
})

// 🔄 Fetch suppliers
const fetchSuppliers = async () => {
  try {
    const token = sessionStorage.getItem('auth_token')
    const response = await api.get('/suppliers', {
      headers: { Authorization: `Bearer ${token}` }
    })
    suppliers.value = response.data || []
  } catch (error) {
    console.error('Fetch failed:', error)
    suppliers.value = []
  }
}
onMounted(fetchSuppliers)

// 📌 Modal actions
function openCreateModal() {
  modalMode.value = 'create'
  Object.assign(form, { id: null, name: '', phone: '', email: '', address: '' })
}
function viewSupplier(item) {
  modalMode.value = 'view'
  Object.assign(form, item)
}
function editSupplier(item) {
  modalMode.value = 'edit'
  Object.assign(form, item)
}
function closeModal() {
  modalMode.value = null
}

// 🗑️ Delete supplier
async function deleteSupplier(id) {
  if (!confirm('Are you sure you want to delete this supplier?')) return
  try {
    const token = sessionStorage.getItem('auth_token')
    await api.delete(`/suppliers/${id}`, {
      headers: { Authorization: `Bearer ${token}` }
    })
    suppliers.value = suppliers.value.filter((s) => s.id !== id)
  } catch (err) {
    console.error('Failed to delete supplier', err)
  }
}

// 💾 Submit (create or edit)
async function submitForm() {
  const token = sessionStorage.getItem('auth_token')
  try {
    if (modalMode.value === 'create') {
      await api.post(
        '/suppliers',
        {
          name: form.name,
          phone: form.phone,
          email: form.email,
          address: form.address
        },
        { headers: { Authorization: `Bearer ${token}` } }
      )
    } else if (modalMode.value === 'edit') {
      await api.put(
        `/suppliers/${form.id}`,
        {
          name: form.name,
          phone: form.phone,
          email: form.email,
          address: form.address
        },
        { headers: { Authorization: `Bearer ${token}` } }
      )
    }
    await fetchSuppliers()
    closeModal()
  } catch (err) {
    console.error('Failed to save supplier', err)
  }
}

// 🔎 Filtering + Pagination
const filteredAndSortedSuppliers = computed(() => {
  let result = suppliers.value.filter(
    (supplier) =>
      supplier.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      supplier.email?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      supplier.phone?.includes(searchQuery.value)
  )

  if (sortKey.value) {
    result.sort((a, b) => {
      const valA = a[sortKey.value]
      const valB = b[sortKey.value]
      if (valA < valB) return sortOrder.value === 'asc' ? -1 : 1
      if (valA > valB) return sortOrder.value === 'asc' ? 1 : -1
      return 0
    })
  }
  return result
})

const totalPages = computed(() =>
  Math.ceil(filteredAndSortedSuppliers.value.length / itemsPerPage)
)

const paginatedSuppliers = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  return filteredAndSortedSuppliers.value.slice(start, start + itemsPerPage)
})
</script>



