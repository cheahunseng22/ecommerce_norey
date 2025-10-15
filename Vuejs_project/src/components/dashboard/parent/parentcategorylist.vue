<template>

  <div class="px-6 py-4">

    <!-- Page header -->

    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

      <div>

        <h1 class="text-2xl font-semibold tracking-tight text-gray-900">Parent Category List</h1>

        <p class="mt-1 text-sm text-gray-500">Manage product parent categories.</p>

      </div>

      <div class="flex items-center gap-2">

        <button
          @click="openCreateDialog"
          class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"

        >

          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">

            <path fill-rule="evenodd" d="M12 3.75a.75.75 0 01.75.75v6.75H19.5a.75.75 0 010 1.5h-6.75V19.5a.75.75 0 01-1.5 0v-6.75H4.5a.75.75 0 010-1.5h6.75V4.5a.75.75 0 01.75-.75z" clip-rule="evenodd" />

          </svg>

          Add Category

        </button>
      </div>

    </div>



    <!-- Toolbar -->

    <div class="mb-4 grid grid-cols-1 gap-3 sm:grid-cols-3">

      <div class="sm:col-span-2">

        <div class="relative">

          <input

            v-model.trim="searchQuery"

            type="text"

            placeholder="Search by category name..."

            class="w-full rounded-lg border border-gray-200 bg-white py-2.5 pl-10 pr-3 text-sm text-gray-900 placeholder:text-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"

          />

          <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">

            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />

          </svg>

        </div>

      </div>



      <div class="flex gap-2">

        <select v-model.number="pageSize" class="w-full rounded-lg border border-gray-200 bg-white py-2.5 px-3 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">

          <option :value="5">5 / page</option>

          <option :value="10">10 / page</option>

          <option :value="20">20 / page</option>

        </select>

        <button

          @click="clearFilters"

          class="whitespace-nowrap rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"

        >

          Reset

        </button>

      </div>

    </div>



    <!-- Table card -->

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

      <div class="overflow-x-auto">

        <table class="min-w-full divide-y divide-gray-200">

          <thead class="bg-gray-50">

            <tr>

              <th class="w-10 px-3 py-3 text-left">

                <input type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" :checked="isAllVisibleSelected" @change="toggleSelectAllVisible" />

              </th>

              <th class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">

                <button class="inline-flex items-center gap-1 hover:text-gray-900" @click="toggleSort('name')">

                  Name

                  <SortIcon :active="sortBy === 'name'" :direction="sortDirection" />

                </button>

              </th>

              <th class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">

                <button class="inline-flex items-center gap-1 hover:text-gray-900" @click="toggleSort('createdAt')">

                  Created At

                  <SortIcon :active="sortBy === 'createdAt'" :direction="sortDirection" />

                </button>

              </th>

              <th class="px-3 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">Actions</th>
            </tr>

          </thead>



          <tbody class="divide-y divide-gray-100 bg-white">

            <tr v-for="category in paginatedCategories" :key="category.id" class="hover:bg-gray-50/60">

              <td class="px-3 py-3">

                <input type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" :checked="selectedCategoryIds.has(category.id)" @change="toggleSelect(category.id)" />

              </td>

              <td class="px-3 py-3">

                <div class="flex items-center gap-3">

                  <div :class="avatarColor(category.name)" class="flex h-9 w-9 items-center justify-center rounded-full text-sm font-semibold text-white">

                    {{ initials(category.name) }}

                  </div>

                  <div>

                    <div class="font-medium text-gray-900">{{ category.name }}</div>

                    <div class="text-xs text-gray-500">ID: {{ category.id }}</div>

                  </div>

                </div>

              </td>

              <td class="px-3 py-3 text-gray-700">{{ formatDate(category.createdAt) }}</td>

              <td class="px-3 py-3 text-center">
                <div class="inline-flex items-center gap-2">

                  <button @click="openEditDialog(category)"
                    title="Edit"
                    class="text-blue-600 hover:text-blue-800 mr-4 transition duration-150 ease-in-out transform hover:scale-105">
      <i class="fas fa-edit mr-1"></i> Edit
                  </button>

                  <button @click="confirmDelete(category)" 
                    title="Delete"
                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
      <i class="fas fa-trash mr-1"></i> Delete
                  </button>

                </div>

              </td>

            </tr>



            <tr v-if="paginatedCategories.length === 0">

              <td colspan="8" class="px-3 py-12 text-center">

                <div class="mx-auto max-w-md">

                  <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400">

                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6">

                      <path fill-rule="evenodd" d="M12 3.75a8.25 8.25 0 100 16.5 8.25 8.25 0 000-16.5zM11.25 7.5a.75.75 0 011.5 0v4.19l2.28 2.28a.75.75 0 11-1.06 1.06l-2.5-2.5a.75.75 0 01-.22-.53V7.5z" clip-rule="evenodd" />

                    </svg>

                  </div>

                  <h3 class="text-sm font-medium text-gray-900">No results</h3>

                  <p class="mt-1 text-sm text-gray-500">Try adjusting your filters or search term.</p>

                </div>

              </td>

            </tr>

          </tbody>

        </table>

      </div>



      <!-- Bulk action bar -->

      <div v-if="selectedCategoryIds.size > 0" class="flex items-center justify-between gap-3 border-t border-gray-100 bg-gray-50 px-4 py-3 text-sm">

        <div class="text-gray-700"><span class="font-medium">{{ selectedCategoryIds.size }}</span> selected</div>

        <div class="flex items-center gap-2">

          <button @click="clearSelection" class="rounded-md border border-gray-200 bg-white px-3 py-2 hover:bg-gray-50">Clear</button>

          <button @click="bulkDelete" class="rounded-md bg-rose-600 px-3 py-2 font-medium text-white hover:bg-rose-700">Delete</button>

        </div>

      </div>



      <!-- Pagination -->

      <div class="flex items-center justify-between border-t border-gray-100 px-4 py-3">

        <p class="text-sm text-gray-600">Showing <span class="font-medium">{{ pageFirst + 1 }}</span> to <span class="font-medium">{{ pageLast }}</span> of <span class="font-medium">{{ filteredCategories.length }}</span> results</p>

        <div class="inline-flex items-center gap-1">

          <button :disabled="currentPage === 1" @click="goToPage(currentPage - 1)" class="rounded-md border border-gray-200 bg-white p-2 text-gray-600 enabled:hover:bg-gray-50 disabled:opacity-50">

            <span class="sr-only">Previous</span>

            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">

              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />

            </svg>

          </button>



          <button v-for="pageNumber in visiblePageNumbers" :key="pageNumber" @click="goToPage(pageNumber)" :class="[

              'rounded-md px-3 py-1.5 text-sm',

              currentPage === pageNumber ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50'

            ]">

            {{ pageNumber }}

          </button>



          <button :disabled="currentPage === totalPages || totalPages === 0" @click="goToPage(currentPage + 1)" class="rounded-md border border-gray-200 bg-white p-2 text-gray-600 enabled:hover:bg-gray-50 disabled:opacity-50">

            <span class="sr-only">Next</span>

            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">

              <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />

            </svg>

          </button>

        </div>

      </div>

    </div>

  </div>

  
  <!-- Create dialog -->
  <div v-if="createDialog.visible" class="fixed inset-0 z-40 flex items-center justify-center bg-black/40 p-4">
    <div class="w-full max-w-lg rounded-2xl bg-white shadow-xl">
      <div class="border-b px-5 py-4">
        <h3 class="text-base font-semibold text-gray-900">Create parent category</h3>
      </div>
      <div class="px-5 py-4">
        <div class="space-y-2">
          <label class="block text-sm font-medium text-gray-700">Category name</label>
          <input
            v-model.trim="createDialog.name"
            type="text"
            placeholder="Enter category name"
            class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
            :disabled="createDialog.saving"
          />
          <p v-if="createDialog.error" class="text-sm text-rose-600">{{ createDialog.error }}</p>
        </div>
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
    <div class="w-full max-w-lg rounded-2xl bg-white shadow-xl">
      <div class="border-b px-5 py-4 flex items-center justify-between">
        <h3 class="text-base font-semibold text-gray-900">Edit parent category</h3>
        <button @click="closeEditDialog" :disabled="editDialog.saving" class="text-gray-500 hover:text-gray-700">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <div class="px-5 py-4 space-y-2">
        <label class="block text-sm font-medium text-gray-700">Category name</label>
        <input
          v-model.trim="editDialog.name"
          type="text"
          placeholder="Enter category name"
          class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
          :disabled="editDialog.saving"
        />
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


  <!-- Delete confirmation -->

  <div v-if="deleteDialog.visible" class="fixed inset-0 z-40 flex items-center justify-center bg-black/40 p-4">

    <div class="w-full max-w-sm rounded-2xl bg-white shadow-xl">

      <div class="border-b px-5 py-4">

        <h3 class="text-base font-semibold text-gray-900">Delete parent</h3>

      </div>

      <div class="px-5 py-4">

        <p class="text-sm text-gray-600">Are you sure you want to delete <span class="font-medium text-gray-900">{{ deleteDialog.target?.name }}</span>? This action cannot be undone.</p>
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

</template>



<script setup>

import { computed, reactive, ref, defineComponent, onMounted } from 'vue'

import api from '../../../axios/Axios'



// Data from API: GET /parent-categories returns { data: [{ id, name, created_at, ... }] }

const allCategories = ref([])



async function fetchParentCategories() {

  try {

    const response = await api.get('/parent-categories')

    const items = response?.data?.data ?? []

    allCategories.value = items.map((item) => ({

      id: item.id,

      name: item.name,

      createdAt: item.created_at,

    }))

  } catch (error) {

    console.error('Failed to load parent categories', error)

    allCategories.value = []

  }

}



onMounted(fetchParentCategories)



const searchQuery = ref('')

const sortBy = ref('createdAt')

const sortDirection = ref('desc')

const currentPage = ref(1)

const pageSize = ref(10)

const selectedCategoryIds = reactive(new Set())



const filteredCategories = computed(() => {

  const query = searchQuery.value.toLowerCase()

  return allCategories.value.filter((c) => {

    const matchesQuery = !query || c.name.toLowerCase().includes(query)

    return matchesQuery

  })

})



const sortedCategories = computed(() => {

  const list = [...filteredCategories.value]

  const key = sortBy.value

  const dir = sortDirection.value === 'asc' ? 1 : -1

  return list.sort((a, b) => {

    const av = a[key]

    const bv = b[key]

    if (key === 'createdAt') {

      return (new Date(av) - new Date(bv)) * dir

    }

    if (typeof av === 'string' && typeof bv === 'string') {

      return av.localeCompare(bv) * dir

    }

    return ((av ?? 0) - (bv ?? 0)) * dir

  })

})



const totalPages = computed(() => Math.ceil(sortedCategories.value.length / pageSize.value))

const pageFirst = computed(() => (currentPage.value - 1) * pageSize.value)

const pageLast = computed(() => Math.min(currentPage.value * pageSize.value, filteredCategories.value.length))

const paginatedCategories = computed(() => sortedCategories.value.slice(pageFirst.value, pageFirst.value + pageSize.value))



const isAllVisibleSelected = computed(() => paginatedCategories.value.length > 0 && paginatedCategories.value.every(p => selectedCategoryIds.has(p.id)))



function toggleSort(key) {

  if (sortBy.value === key) {

    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc'

  } else {

    sortBy.value = key

    sortDirection.value = 'asc'

  }

}



function goToPage(pageNumber) {

  const target = Math.max(1, Math.min(totalPages.value || 1, pageNumber))

  currentPage.value = target

}



function visibleRange(current, total, delta = 1) {

  const range = []

  const start = Math.max(1, current - delta)

  const end = Math.min(total, current + delta)

  for (let i = start; i <= end; i += 1) range.push(i)

  return range

}



const visiblePageNumbers = computed(() => visibleRange(currentPage.value, totalPages.value, 2))



function clearFilters() {

  searchQuery.value = ''

  pageSize.value = 10

  currentPage.value = 1

}



function toggleSelectAllVisible() {

  const allSelected = isAllVisibleSelected.value

  paginatedCategories.value.forEach((p) => {

    if (allSelected) {

      selectedCategoryIds.delete(p.id)

    } else {

      selectedCategoryIds.add(p.id)

    }

  })

}



function toggleSelect(id) {

  if (selectedCategoryIds.has(id)) selectedCategoryIds.delete(id)

  else selectedCategoryIds.add(id)

}



function clearSelection() {

  selectedCategoryIds.clear()

}



const deleteDialog = reactive({ visible: false, target: null, deleting: false, error: '' })
function confirmDelete(category) {

  deleteDialog.visible = true

  deleteDialog.target = category

  deleteDialog.error = ''
}

function performDelete() {

  if (!deleteDialog.target || deleteDialog.deleting) return
  deleteDialog.deleting = true
  deleteDialog.error = ''
  const id = deleteDialog.target.id
  api.delete(`/parent-categories/${id}`)
    .then(() => {
      const idx = allCategories.value.findIndex((p) => p.id === id)
  if (idx !== -1) allCategories.value.splice(idx, 1)

      selectedCategoryIds.delete(id)
  deleteDialog.visible = false

  deleteDialog.target = null

    })
    .catch((err) => {
      console.error('Failed to delete parent category', err)
      deleteDialog.error = 'Failed to delete. Please try again.'
    })
    .finally(() => {
      deleteDialog.deleting = false
    })
}

function bulkDelete() {

  const idsToDelete = Array.from(selectedCategoryIds)
  if (idsToDelete.length === 0) return
  const deletionPromises = idsToDelete.map((id) =>
    api.delete(`/parent-categories/${id}`).then(() => id).catch(() => null)
  )
  Promise.all(deletionPromises).then((results) => {
    const successIds = new Set(results.filter((r) => r !== null))
    if (successIds.size > 0) {
      allCategories.value = allCategories.value.filter((p) => !successIds.has(p.id))
      for (const id of successIds) selectedCategoryIds.delete(id)
    }
  })
}

// Create dialog state and actions
const createDialog = reactive({ visible: false, name: '', saving: false, error: '' })
function openCreateDialog() {
  createDialog.visible = true
  createDialog.name = ''
  createDialog.error = ''
}
function closeCreateDialog() {
  if (createDialog.saving) return
  createDialog.visible = false
  createDialog.name = ''
  createDialog.error = ''
}
async function saveNewCategory() {
  if (!createDialog.name) {
    createDialog.error = 'Name is required.'
    return
  }
  createDialog.saving = true
  createDialog.error = ''
  try {
    const response = await api.post('/parent-categories', { name: createDialog.name })
    const item = response?.data?.data ?? response?.data
    if (item && (item.id || item.name)) {
      const newCategory = {
        id: item.id,
        name: item.name ?? createDialog.name,
        createdAt: item.created_at ?? new Date().toISOString(),
      }
      allCategories.value.unshift(newCategory)
    } else {
      await fetchParentCategories()
    }
    // Ensure popup closes immediately on success
    createDialog.saving = false
    closeCreateDialog()
  } catch (error) {
    console.error('Failed to create parent category', error)
    createDialog.error = 'Failed to create. Please try again.'
  } finally {
    createDialog.saving = false
  }
}

// Edit dialog state and actions
const editDialog = reactive({ visible: false, id: null, name: '', saving: false, error: '' })

function openEditDialog(category) {
  editDialog.visible = true
  editDialog.id = category.id
  editDialog.name = category.name
  editDialog.error = ''
}

function closeEditDialog() {
  if (editDialog.saving) return
  editDialog.visible = false
}

async function saveEditCategory() {
  if (!editDialog.name) {
    editDialog.error = 'Name is required.'
    return
  }
  editDialog.saving = true
  editDialog.error = ''
  let updated = false
  try {
    const response = await api.put(`/parent-categories/${editDialog.id}`, { name: editDialog.name })
    const updatedItem = response?.data?.data ?? null
    if (updatedItem) {
      const idx = allCategories.value.findIndex((c) => c.id === editDialog.id)
      if (idx !== -1) {
        allCategories.value[idx] = {
          ...allCategories.value[idx],
          name: updatedItem.name ?? editDialog.name,
          createdAt: updatedItem.created_at ?? allCategories.value[idx].createdAt,
        }
      } else {
        await fetchParentCategories()
      }
    } else {
      await fetchParentCategories()
    }
    updated = true
  } catch (error) {
    console.error('Failed to update parent category', error)
    editDialog.error = 'Failed to update. Please try again.'
  } finally {
    editDialog.saving = false
    if (updated) closeEditDialog()
  }
}



function initials(name) {

  return name

    .split(' ')

    .filter(Boolean)

    .slice(0, 2)

    .map((n) => n[0]?.toUpperCase() ?? '')

    .join('')

}



function avatarColor(name) {

  const colors = [

    'bg-indigo-500',

    'bg-blue-500',

    'bg-sky-500',

    'bg-teal-500',

    'bg-emerald-500',

    'bg-violet-500',

    'bg-fuchsia-500',

    'bg-rose-500',

    'bg-amber-500',

  ]

  let hash = 0

  for (let i = 0; i < name.length; i += 1) hash = name.charCodeAt(i) + ((hash << 5) - hash)

  const idx = Math.abs(hash) % colors.length

  return colors[idx]

}



function formatDate(iso) {

  const d = new Date(iso)

  return d.toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' })

}



// Inline SortIcon component for header sort indicators

const SortIcon = defineComponent({

  name: 'SortIcon',

  props: {

    active: { type: Boolean, default: false },

    direction: { type: String, default: 'asc' },

  },

  template: `

    <span class="inline-flex flex-col text-gray-400">

      <svg v-if="active && direction === 'asc'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-3.5 w-3.5">

        <path fill-rule="evenodd" d="M5.22 12.28a.75.75 0 001.06 0L10 8.56l3.72 3.72a.75.75 0 001.06-1.06l-4.25-4.25a.75.75 0 00-1.06 0l-4.25 4.25a.75.75 0 000 1.06z" clip-rule="evenodd" />

      </svg>

      <svg v-else-if="active && direction === 'desc'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-3.5 w-3.5">

        <path fill-rule="evenodd" d="M14.78 7.72a.75.75 0 00-1.06 0L10 11.44 6.28 7.72a.75.75 0 10-1.06 1.06l4.25 4.25a.75.75 0 001.06 0l4.25-4.25a.75.75 0 000-1.06z" clip-rule="evenodd" />

      </svg>

      <svg v-else xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-3.5 w-3.5 opacity-50">

        <path fill-rule="evenodd" d="M5.22 12.28a.75.75 0 001.06 0L10 8.56l3.72 3.72a.75.75 0 001.06-1.06l-4.25-4.25a.75.75 0 00-1.06 0l-4.25 4.25a.75.75 0 000 1.06z" clip-rule="evenodd" />

      </svg>

    </span>

  `,

})

</script>



<style scoped>

/* No additional styles; Tailwind handles styling */

</style>




