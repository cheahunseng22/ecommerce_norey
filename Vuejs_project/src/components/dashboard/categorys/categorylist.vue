<template>
  <div class="p-4 sm:p-6 md:p-8 bg-gray-50 min-h-screen">
    <div class="bg-white p-6 rounded-lg shadow-md">
      <!-- Header -->
      <header class="pb-6 border-b border-gray-200">
        <h1 class="text-2xl font-bold text-gray-800">Product Categories</h1>
        <div class="mt-4 flex flex-col md:flex-row items-start md:items-center justify-between">
          <!-- Search Bar -->
          <div class="relative w-full md:w-1/3">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <i class="fas fa-search text-gray-400"></i>
            </div>
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Search Category..."
              class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
            />
          </div>

          <!-- Add Button -->
          <button
            @click="openCreateDialog"
            class="mt-4 md:mt-0 bg-indigo-600 text-white px-6 py-3 rounded-md shadow-md hover:bg-indigo-700 transition font-semibold flex items-center"
          >
            <i class="fas fa-plus mr-2"></i>
            Create Category
          </button>
        </div>
      </header>

<div class="overflow-x-auto mt-6">
  <table class="w-full text-sm text-gray-500">
    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
      <tr>
        <th v-for="header in headers" :key="header.value" class="px-6 py-3 cursor-pointer text-center" @click="sortBy(header.value)">
          <div class="flex items-center justify-center">
            {{ header.text }}
            <svg v-if="sortKey === header.value" class="w-3 h-3 ml-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="sortOrder === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'" />
            </svg>
          </div>
        </th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="item in paginatedCategories" :key="item.id" class="bg-white border-b hover:bg-gray-50">
        <td class="px-6 py-4 text-center font-medium text-gray-900">{{ item.id }}</td>
        <td class="px-6 py-4 text-center">
          <span class="inline-block px-3 py-1 text-xs font-medium text-gray-700 bg-gray-100 rounded-full">{{ item.name }}</span>
        </td>
        <td class="px-6 py-4 text-center whitespace-nowrap text-sm font-medium">
          <div class="flex items-center justify-center space-x-2">
            <button @click="openEditDialog(item)" class="text-blue-600 hover:text-blue-800 transition duration-150 ease-in-out transform hover:scale-105">
              <i class="fas fa-edit mr-1"></i> Edit
            </button>
            <button @click="confirmDelete(item)" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
              <i class="fas fa-trash mr-1"></i>
              Delete
            </button>
          </div>
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
    </div>
    
    <!-- Delete confirmation -->
    <div v-if="deleteDialog.visible" class="fixed inset-0 z-40 flex items-center justify-center bg-black/40 p-4">
      <div class="w-full max-w-sm rounded-2xl bg-white shadow-xl">
        <div class="border-b px-5 py-4">
          <h3 class="text-base font-semibold text-gray-900">Delete category</h3>
        </div>
        <div class="px-5 py-4">
          <p class="text-sm text-gray-600">
            Are you sure you want to delete
            <span class="font-medium text-gray-900">{{ deleteDialog.target?.name || `ID ${deleteDialog.target?.id}` }}</span>?
            This action cannot be undone.
          </p>
          <p v-if="deleteDialog.error" class="mt-2 text-sm text-rose-600">{{ deleteDialog.error }}</p>
        </div>
        <div class="flex justify-end gap-2 border-t px-5 py-4">
          <button @click="deleteDialog.visible = false" class="rounded-md border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50" :disabled="deleteDialog.deleting">Cancel</button>
          <button @click="performDelete" class="rounded-md bg-rose-600 px-4 py-2 text-sm font-medium text-white hover:bg-rose-700 disabled:opacity-60" :disabled="deleteDialog.deleting">
            <span v-if="!deleteDialog.deleting">Delete</span>
            <span v-else>Deleting...</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Create dialog -->
    <div v-if="createDialog.visible" class="fixed inset-0 z-40 flex items-center justify-center bg-black/40 p-4">
      <div class="w-full max-w-xl rounded-2xl bg-white shadow-xl">
        <div class="border-b px-5 py-4 flex items-center justify-between">
          <h3 class="text-base font-semibold text-gray-900">Create category</h3>
          <button @click="closeCreateDialog" :disabled="createDialog.saving" class="text-gray-500 hover:text-gray-700">
            <i class="fas fa-times"></i>
          </button>
        </div>
        <div class="px-5 py-4 space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Category name</label>
            <input
              v-model.trim="createDialog.name"
              type="text"
              placeholder="Enter category name"
              class="mt-1 w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
              :disabled="createDialog.saving"
            />
          </div>
         
         
          <div>
            <div class="flex items-center justify-between">
              <label class="block text-sm font-medium text-gray-700">Parent category</label>
              <button @click="refreshParents" :disabled="parents.loading || createDialog.saving" class="text-xs text-indigo-600 hover:text-indigo-700">
                <span v-if="!parents.loading">Refresh</span>
                <span v-else>Loading...</span>
              </button>
            </div>
            <select
              v-model.number="createDialog.parentId"
              class="mt-1 w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 disabled:opacity-60"
              :disabled="parents.loading || createDialog.saving"
            >
              <option :value="null">Select parent...</option>
              <option v-for="p in parents.items" :key="p.id" :value="p.id">{{ p.name }}</option>
            </select>
            <p v-if="parents.error" class="mt-1 text-sm text-rose-600">{{ parents.error }}</p>
          </div>
          <p v-if="createDialog.error" class="text-sm text-rose-600">{{ createDialog.error }}</p>
        </div>
        <div class="flex justify-end gap-2 border-t px-5 py-4">
          <button @click="closeCreateDialog" class="rounded-md border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50" :disabled="createDialog.saving">Cancel</button>
          <button @click="saveNewCategory" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-60" :disabled="createDialog.saving">
            <span v-if="!createDialog.saving">Save</span>
            <span v-else>Saving...</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Edit dialog -->
    <div v-if="editDialog.visible" class="fixed inset-0 z-40 flex items-center justify-center bg-black/40 p-4">
      <div class="w-full max-w-xl rounded-2xl bg-white shadow-xl">
        <div class="border-b px-5 py-4 flex items-center justify-between">
          <h3 class="text-base font-semibold text-gray-900">Edit category</h3>
          <button @click="closeEditDialog" :disabled="editDialog.saving" class="text-gray-500 hover:text-gray-700">
            <i class="fas fa-times"></i>
          </button>
        </div>
        <div class="px-5 py-4 space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Category name</label>
            <input
              v-model.trim="editDialog.name"
              type="text"
              placeholder="Enter category name"
              class="mt-1 w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
              :disabled="editDialog.saving"
            />
          </div>
         
        
          <div>
            <div class="flex items-center justify-between">
              <label class="block text-sm font-medium text-gray-700">Parent category</label>
              <button @click="refreshParents" :disabled="parents.loading || editDialog.saving" class="text-xs text-indigo-600 hover:text-indigo-700">
                <span v-if="!parents.loading">Refresh</span>
                <span v-else>Loading...</span>
              </button>
            </div>
            <select
              v-model.number="editDialog.parentId"
              class="mt-1 w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 disabled:opacity-60"
              :disabled="parents.loading || editDialog.saving"
            >
              <option :value="null">Select parent...</option>
              <option v-for="p in parents.items" :key="p.id" :value="p.id">{{ p.name }}</option>
            </select>
            <p v-if="parents.error" class="mt-1 text-sm text-rose-600">{{ parents.error }}</p>
          </div>
          <p v-if="editDialog.error" class="text-sm text-rose-600">{{ editDialog.error }}</p>
        </div>
        <div class="flex justify-end gap-2 border-t px-5 py-4">
          <button @click="closeEditDialog" class="rounded-md border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50" :disabled="editDialog.saving">Cancel</button>
          <button @click="saveEditCategory" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-60" :disabled="editDialog.saving">
            <span v-if="!editDialog.saving">Save</span>
            <span v-else>Saving...</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, reactive } from 'vue';
import api from '../../../axios/Axios';
// import ToggleSwitch from './ToggleSwitch.vue';

const headers = [
  { text: 'ID', value: 'id', sortable: true },
  { text: 'Name', value: 'name', sortable: true },

 
  { text: 'Action', value: 'action', sortable: false },
];

const categories = ref([]);
const searchQuery = ref('');
const sortKey = ref('id');
const sortOrder = ref('asc');
const currentPage = ref(1);
const itemsPerPage = 7;

const fetchCategories = async () => {
  try {
    const token = sessionStorage.getItem("auth_token");
    const response = await api.get("/categories", {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    });
    categories.value = response.data.data || [];
    console.log(response.data.data)
  } catch (error) {
    console.error("Fetch failed:", error);
    categories.value = [];
  }
};

onMounted(fetchCategories);
const deleteDialog = reactive({ visible: false, target: null, deleting: false, error: '' })

function confirmDelete(item) {
  deleteDialog.visible = true
  deleteDialog.target = item
  deleteDialog.error = ''
}

async function performDelete() {
  if (!deleteDialog.target || deleteDialog.deleting) return
  deleteDialog.deleting = true
  deleteDialog.error = ''
  const id = deleteDialog.target.id
  try {
    await api.delete(`/deletecategory/${id}`)
    categories.value = categories.value.filter((c) => c.id !== id)
    deleteDialog.visible = false
    deleteDialog.target = null
  } catch (err) {
    console.error('Failed to delete category', err)
    deleteDialog.error = 'Failed to delete. Please try again.'
  } finally {
    deleteDialog.deleting = false
  }
}


// Create dialog state
const createDialog = reactive({ visible: false, name: '', parentId: null, saving: false, error: '' })
const parents = reactive({ items: [], loading: false, error: '' })
const editDialog = reactive({
  visible: false,
  id: null,
  name: '',

  parentId: null,
  currentImage: null,

  saving: false,
  error: ''
})

function openCreateDialog() {
  createDialog.visible = true
  createDialog.name = ''
  createDialog.description = ''
  createDialog.imageFile = null
  createDialog.imagePreview = null
  createDialog.parentId = null
  createDialog.saving = false
  createDialog.error = ''
  refreshParents()
}

function closeCreateDialog() {
  if (createDialog.saving) return
  createDialog.visible = false
}

function openEditDialog(item) {
  editDialog.visible = true
  editDialog.id = item.id
  editDialog.name = item.name || ''

  editDialog.parentId = item.parent_id ?? null
  editDialog.currentImage = item.image || null

  editDialog.error = ''
  refreshParents()
}

function closeEditDialog() {
  if (editDialog.saving) return
  editDialog.visible = false
}

async function refreshParents() {
  parents.loading = true
  parents.error = ''
  try {
    const response = await api.get('/parent-categories')
    const items = response?.data?.data ?? response?.data ?? []
    parents.items = items.map((it) => ({ id: it.id, name: it.name }))
  } catch (err) {
    console.error('Failed to load parent categories', err)
    parents.error = 'Failed to load parent categories.'
    parents.items = []
  } finally {
    parents.loading = false
  }
}

function handleCreateImageUpload(event) {
  const file = event?.target?.files?.[0]
  if (!file) return
  createDialog.imageFile = file
  try {
    createDialog.imagePreview = URL.createObjectURL(file)
  } catch (_e) {
    createDialog.imagePreview = null
  }
}

function clearCreateImage() {
  createDialog.imageFile = null
  if (createDialog.imagePreview) {
    try { URL.revokeObjectURL(createDialog.imagePreview) } catch (_e) {}
  }
  createDialog.imagePreview = null
}

function handleEditImageUpload(event) {
  const file = event?.target?.files?.[0]
  if (!file) return
  editDialog.imageFile = file
  try {
    editDialog.imagePreview = URL.createObjectURL(file)
  } catch (_e) {
    editDialog.imagePreview = null
  }
}

function clearEditImage() {
  editDialog.imageFile = null
  if (editDialog.imagePreview) {
    try { URL.revokeObjectURL(editDialog.imagePreview) } catch (_e) {}
  }
  editDialog.imagePreview = null
}

async function saveNewCategory() {
  const token = sessionStorage.getItem('auth_token')

  if (!createDialog.name) {
    createDialog.error = 'Name is required.'
    return
  }

  createDialog.saving = true
  createDialog.error = ''
 
  let created = false
  try {
    const formData = new FormData();
    let imageUri = '';
    if (createDialog.imageFile) {
      formData.append('file', createDialog.imageFile)

        const res = await api.post(`/files/upload`, formData, {
      headers: {
        Authorization: `Bearer ${token}`,
        'Content-Type': 'multipart/form-data'
      },
    })

    imageUri = res.data.data.uri;
    }

    await api.post(`/addcategories`, {
      "name": createDialog.name,
      "description": createDialog.description,
      "parent_id": createDialog.parentId,
      "image": imageUri
    }, {
      headers: {
        Authorization: `Bearer ${token}`,
        'Content-Type': 'application/json'
      },
    })

    created = true
    await fetchCategories()
  } catch (err) {
    console.error('Failed to create category', err)
    createDialog.error = 'Failed to create. Please try again.'
  } finally {
    createDialog.saving = false
    if (created) closeCreateDialog()
  }
}

async function saveEditCategory() {
  const token = sessionStorage.getItem('auth_token')
  if (!editDialog.name) {
    editDialog.error = 'Name is required.'
    return
  }
  editDialog.saving = true
  editDialog.error = ''
  let updated = false
  let imageUri = '';
  try {
    const formData = new FormData()
    if (editDialog.imageFile) {
      formData.append('file', editDialog.imageFile)

      const res = await api.post(`/files/upload`, formData, {
      headers: {
        Authorization: `Bearer ${token}`,
        'Content-Type': 'multipart/form-data'
      },
    })

    imageUri = res.data.data.uri;

    }

    await api.put(`/updatecategories/${editDialog.id}`, {
      "name": editDialog.name,
      "description": editDialog.description,
      "parent_id": editDialog.parentId,
      "image": imageUri ? imageUri : editDialog.currentImage
    }, {
      headers: {
        Authorization: `Bearer ${token}`,
        'Content-Type': 'application/json'
      },
    })

   
    updated = true
    await fetchCategories()
  } catch (err) {
    console.error('Failed to update category', err)
    editDialog.error = 'Failed to update. Please try again.'
  } finally {
    editDialog.saving = false
    if (updated) closeEditDialog()
  }
}

const filteredAndSortedCategories = computed(() => {
  let result = categories.value.filter(category =>
    category.name.toLowerCase().includes(searchQuery.value.toLowerCase())
  );

  if (sortKey.value) {
    result.sort((a, b) => {
      const valA = a[sortKey.value];
      const valB = b[sortKey.value];
      if (valA < valB) return sortOrder.value === 'asc' ? -1 : 1;
      if (valA > valB) return sortOrder.value === 'asc' ? 1 : -1;
      return 0;
    });
  }

  return result;
});

const totalPages = computed(() =>
  Math.ceil(filteredAndSortedCategories.value.length / itemsPerPage)
);

const paginatedCategories = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage;
  return filteredAndSortedCategories.value.slice(start, start + itemsPerPage);
});

const sortBy = (key) => {
  const header = headers.find(h => h.value === key);
  if (!header || header.sortable === false) return;

  if (sortKey.value === key) {
    sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortKey.value = key;
    sortOrder.value = 'asc';
  }
};

const changePage = (page) => {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page;
  }
};
</script>