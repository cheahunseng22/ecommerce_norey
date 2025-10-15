<template>
  <div class="p-4 sm:p-6 md:p-8 bg-gray-50 min-h-screen">
    <div class="bg-white p-6 rounded-lg shadow-md">
      <!-- Header -->
      <header class="pb-6 border-b border-gray-200 flex flex-col md:flex-row justify-between items-start md:items-center">
        <h1 class="text-2xl font-bold text-gray-800">Brand Management</h1>

        <!-- Search Bar -->
        <div class="relative w-full md:w-1/3 mt-4 md:mt-0">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search brands..."
            class="block w-full pl-3 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
          />
        </div>

        <!-- Add Button -->
        <button
          @click="openCreateModal"
          class="mt-4 md:mt-0 bg-indigo-600 text-white px-6 py-3 rounded-md shadow-md hover:bg-indigo-700 transition font-semibold flex items-center"
        >
          <i class="fas fa-plus mr-2"></i>
          Create Brand
        </button>
      </header>

      <!-- Brand Table -->
      <div v-if="paginatedBrands.length > 0" class="mt-6 overflow-x-auto bg-white rounded-xl shadow-sm border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
            <tr>
              <th class="px-4 py-3 text-left">Brand Name</th>
              <th class="px-4 py-3 text-left">Description</th>
              <th class="px-4 py-3 text-left">Image</th>
              <th class="px-4 py-3 text-center">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="brand in paginatedBrands" :key="brand.brand_id">
              <td class="px-6 py-4 ">{{ brand.name }}</td>
              <td class="px-6 py-4 ">{{ brand.description || 'No description' }}</td>
              <td class="px-6 py-4 ">
                <img v-if="brand.image" 
                :src="brand.image" 
                alt="brand" 
                class="h-12 w-12 object-cover rounded" />
                <span v-else class="text-gray-400 text-sm">No image</span>
              </td>
              <td class="px-6 py-4 flex justify-center gap-2">
                <button @click="editBrand(brand)" class="text-blue-600 hover:text-blue-800  flex justify-center items-center mr-4 transition duration-150 ease-in-out transform hover:scale-105"> 
                  <i class="fas fa-edit mr-1"></i>Edit
                </button>
                <button @click="deleteBrand(brand.brand_id)"  class="px-4 py-2 bg-red-600 flex justify-center items-center text-white rounded-md hover:bg-red-700">
                  <i class="fas fa-trash mr-1"></i>Delete
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-12 text-gray-500">No brands found. Add one!</div>

      <!-- Modal -->
      <div v-if="modalMode !== null" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-xl shadow-xl max-w-lg w-full p-6">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">
              {{ editingBrand ? 'Edit Brand' : 'Create Brand' }}
            </h2>
            <button @click="closeModal">✖</button>
          </div>

          <form @submit.prevent="submitForm" class="space-y-3">
            <input
              v-model="form.name"
              type="text"
              placeholder="Brand Name"
              class="w-full border rounded px-3 py-2"
              required
            />
            <textarea
              v-model="form.description"
              rows="3"
              placeholder="Description"
              class="w-full border rounded px-3 py-2"
            ></textarea>

            <!-- Image Upload -->
            <div>
              <input type="file" @change="handleImageUpload" accept="image/*" class="w-full text-sm" />
              <div v-if="previewImage" class="mt-2">
                <img :src="previewImage" alt="Preview" class="h-24 rounded object-cover border" />
              </div>
            </div>

            <div class="flex gap-2 pt-2">
              <button type="button" @click="closeModal" class="flex-1 bg-gray-200 py-2 rounded">Cancel</button>
              <button type="submit" class="flex-1 bg-blue-600 text-white py-2 rounded">
                {{ editingBrand ? 'Update' : 'Create' }}
              </button>
            </div>
          </form>
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
import { ref, reactive, computed, onMounted } from 'vue'
import api from '../../../axios/Axios'

const brands = ref([])
const searchQuery = ref('')
const currentPage = ref(1)
const itemsPerPage = 5

// Modal states
const modalMode = ref(null) 
const editingBrand = ref(null)
const form = reactive({ brand_id: null, name: '', description: '', image: null })
const previewImage = ref(null)

// Fetch brands
const fetchBrands = async () => {
  try {
    const token = sessionStorage.getItem('auth_token')
    const res = await api.get('/brands', { headers: { Authorization: `Bearer ${token}` } })
    brands.value = res.data.data || []
  } catch (err) {
    console.error('Failed to fetch brands', err)
  }
}
onMounted(fetchBrands)

// Modal actions
const openCreateModal = () => {
  modalMode.value = 'create'
  editingBrand.value = null
  Object.assign(form, { brand_id: null, name: '', description: '', image: null })
  previewImage.value = null
}
const editBrand = (brand) => {
  modalMode.value = 'edit'
  editingBrand.value = brand

  Object.assign(form, {
    brand_id: brand.brand_id || brand.id,  // ✅ handle both cases
    name: brand.name || '',
    description: brand.description || '',
    image: brand.image || ''
  })

  previewImage.value = brand.image || null
}

const closeModal = () => { 
  modalMode.value = null
  editingBrand.value = null
  previewImage.value = null
}

// Handle image
const handleImageUpload = (e) => {
  const file = e.target.files[0]
  if (file) {
    form.image = file
    previewImage.value = URL.createObjectURL(file)
  }
}

const uploadSingleImage = async (file) => {
  const token = sessionStorage.getItem("auth_token");
  const form = new FormData();
  form.append('file', file);
  const res = await api.post('/files/upload', form, {
    headers: {
      Authorization: `Bearer ${token}`,
      'Content-Type': 'multipart/form-data'
    }
  });
  return res?.data?.data?.uri || '';
};

// Submit form with FormData
const submitForm = async () => {
  const token = sessionStorage.getItem('auth_token')
  let uploadedImageUri = form.image

  // If it's a File object, upload it first
  if (form.image instanceof File) {
    uploadedImageUri = await uploadSingleImage(form.image)
  }

  const payload = {
    name: form.name,
    description: form.description,
    image: uploadedImageUri
  }

  try {
    if (modalMode.value === 'create') {
      await api.post('/brands', payload, {
        headers: { Authorization: `Bearer ${token}` }
      })
    } else if (modalMode.value === 'edit') {
      await api.put(`/brands/${form.brand_id}`, payload, {
        headers: { Authorization: `Bearer ${token}` }
      })
    }

    await fetchBrands()
    closeModal()
  } catch (err) {
    console.error('Failed to save brand', err)
  }
}


// Delete
const deleteBrand = async (id) => {
  if (!confirm('Delete this brand?')) return
  try {
    const token = sessionStorage.getItem('auth_token')
    await api.delete(`/brands/${id}`, { headers: { Authorization: `Bearer ${token}` } })
    brands.value = brands.value.filter(b => b.brand_id !== id)
  } catch (err) { console.error(err) }
}

// Filtering & Pagination
const filteredBrands = computed(() => {
  return brands.value.filter(b =>
    b.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    (b.description && b.description.toLowerCase().includes(searchQuery.value.toLowerCase()))
  )
})

const totalPages = computed(() => Math.ceil(filteredBrands.value.length / itemsPerPage))
const paginatedBrands = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  return filteredBrands.value.slice(start, start + itemsPerPage)
})
const changePage = (page) => {
  if (page < 1 || page > totalPages.value) return
  currentPage.value = page
}
</script>
