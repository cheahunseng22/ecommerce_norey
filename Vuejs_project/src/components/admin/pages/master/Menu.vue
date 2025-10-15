<template>
  <div class="min-h-screen bg-gray-50 font-sans">
    <div class="container mx-auto px-4 py-8">
      <!-- Header Section -->
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
          <h1 class="text-3xl font-bold text-gray-800">Category Management</h1>
          <p class="text-gray-600 mt-1">Manage your product categories efficiently</p>
        </div>
        <button 
          @click="showAddModal"
          class="flex items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow-md transition-all duration-200"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
          </svg>
          Add Category
        </button>
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-500 text-sm font-medium">Total Categories</p>
              <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ categories.length }}</h3>
            </div>
            <div class="p-3 rounded-full bg-blue-50 text-blue-600">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
              </svg>
            </div>
          </div>
        </div>
        
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-500 text-sm font-medium">Active Categories</p>
              <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ categories.length }}</h3>
            </div>
            <div class="p-3 rounded-full bg-green-50 text-green-600">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
            </div>
          </div>
        </div>
        
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-500 text-sm font-medium">Categories per Page</p>
              <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ categoriesPerPage }}</h3>
            </div>
            <div class="p-3 rounded-full bg-purple-50 text-purple-600">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Table Section -->
      <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
        <!-- Table Header with Search -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center p-4 border-b border-gray-100">
          <div class="relative mb-4 md:mb-0 w-full md:w-auto">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
              </svg>
            </div>
            <input 
              type="text" 
              v-model="searchQuery"
              class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
              placeholder="Search categories..."
            >
          </div>
          <div class="flex items-center gap-2">
            <label class="text-sm text-gray-600">Show:</label>
            <select v-model="categoriesPerPage" class="border border-gray-300 rounded-md px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option value="5">5</option>
              <option value="10">10</option>
              <option value="20">20</option>
              <option value="50">50</option>
            </select>
          </div>
        </div>
        
        <!-- Table -->
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="category in filteredCategories" :key="category.id" class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ category.id }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ category.name }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex-shrink-0 h-10 w-10">
                    <img v-if="category.image" :src="category.image" alt="Category" class="h-10 w-10 rounded-full object-cover">
                    <div v-else class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                      </svg>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <div class="flex justify-end gap-2">
                    <button @click="showViewModal(category)" class="text-blue-600 hover:text-blue-900 p-1 rounded-full hover:bg-blue-50">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                      </svg>
                    </button>
                    <button @click="showEditModal(category)" class="text-yellow-600 hover:text-yellow-900 p-1 rounded-full hover:bg-yellow-50">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                      </svg>
                    </button>
                    <button @click="showDeleteModal(category)" class="text-red-600 hover:text-red-900 p-1 rounded-full hover:bg-red-50">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                      </svg>
                    </button>
                  </div>
                </td>
              </tr>
              <tr v-if="filteredCategories.length === 0">
                <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                  No categories found
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        
        <!-- Pagination -->
        <div class="bg-white px-4 py-3 flex flex-col sm:flex-row items-center justify-between border-t border-gray-200 sm:px-6">
          <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
              <p class="text-sm text-gray-700">
                Showing <span class="font-medium">{{ startIndex + 1 }}</span> to <span class="font-medium">{{ endIndex }}</span> of <span class="font-medium">{{ filteredCategories.length }}</span> results
              </p>
            </div>
            <div>
              <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                <button 
                  @click="prevPage" 
                  :disabled="currentPage === 1"
                  :class="{'opacity-50 cursor-not-allowed': currentPage === 1, 'hover:bg-gray-50': currentPage > 1}"
                  class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500"
                >
                  <span class="sr-only">Previous</span>
                  <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                  </svg>
                </button>
                
                <button 
                  v-for="page in visiblePages" 
                  :key="page"
                  @click="goToPage(page)"
                  :class="{'z-10 bg-blue-50 border-blue-500 text-blue-600': currentPage === page, 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50': currentPage !== page}"
                  class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                >
                  {{ page }}
                </button>
                
                <button 
                  @click="nextPage" 
                  :disabled="currentPage === totalPages"
                  :class="{'opacity-50 cursor-not-allowed': currentPage === totalPages, 'hover:bg-gray-50': currentPage < totalPages}"
                  class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500"
                >
                  <span class="sr-only">Next</span>
                  <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                  </svg>
                </button>
              </nav>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal -->
      <Modal :isVisible="modalVisible" :title="modalTitle" @close="closeModal">
        <div class="p-6">
          <template v-if="modalType === 'add'">
            <form @submit.prevent="handleCreateCategory" class="space-y-6">
              <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Category Name</label>
                <input 
                  type="text" 
                  id="name" 
                  v-model="newCategory.name"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                  placeholder="Enter category name"
                  required
                >
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category Image</label>
                <div class="mt-1 flex items-center">
                  <label for="image-upload" class="cursor-pointer">
                    <div class="flex flex-col items-center justify-center px-6 py-8 border-2 border-dashed border-gray-300 rounded-lg hover:border-gray-400 transition">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                      </svg>
                      <p class="mt-2 text-sm text-gray-600">Click to upload an image</p>
                      <p class="text-xs text-gray-500">PNG, JPG up to 2MB</p>
                    </div>
                    <input id="image-upload" type="file" @change="handleImageUpload" class="sr-only" accept="image/*">
                  </label>
                </div>
                <div v-if="newCategory.image" class="mt-2 flex items-center">
                  <span class="text-sm text-gray-600">{{ newCategory.image.name }}</span>
                  <button @click="newCategory.image = null" type="button" class="ml-2 text-red-500 hover:text-red-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </button>
                </div>
              </div>
              
              <div class="flex justify-end space-x-3 pt-4">
                <button 
                  @click="closeModal"
                  type="button"
                  class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                  Cancel
                </button>
                <button 
                  type="submit"
                  class="px-4 py-2 bg-blue-600 rounded-lg text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                  Add Category
                </button>
              </div>
            </form>
          </template>

          <template v-else-if="modalType === 'view'">
            <div class="space-y-6">
              <div class="flex flex-col items-center">
                <div class="mb-4 h-32 w-32 rounded-full bg-gray-100 overflow-hidden border-4 border-white shadow-md">
                  <img v-if="currentCategory.image" :src="currentCategory.image" alt="Category" class="h-full w-full object-cover">
                  <div v-else class="h-full w-full flex items-center justify-center text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                  </div>
                </div>
                <h2 class="text-xl font-semibold text-gray-800">{{ currentCategory.name }}</h2>
                <p class="text-sm text-gray-500">Category ID: {{ currentCategory.id }}</p>
              </div>
              
              <div class="border-t border-gray-200 pt-4">
                <dl class="space-y-4">
                  <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-gray-500">Created At</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ formatDate(currentCategory.created_at) || 'N/A' }}</dd>
                  </div>
                  <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-gray-500">Updated At</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ formatDate(currentCategory.updated_at) || 'N/A' }}</dd>
                  </div>
                </dl>
              </div>
              
              <div class="flex justify-end pt-4">
                <button 
                  @click="closeModal"
                  class="px-4 py-2 bg-blue-600 rounded-lg text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                  Close
                </button>
              </div>
            </div>
          </template>

          <template v-else-if="modalType === 'edit'">
            <form @submit.prevent="handleUpdateCategory" enctype="multipart/form-data" class="space-y-6">
              <div>
                <label for="edit-name" class="block text-sm font-medium text-gray-700 mb-1">Category Name</label>
                <input 
                  type="text" 
                  id="edit-name" 
                  v-model="editCategory.name"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                  placeholder="Enter category name"
                  required
                >
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category Image</label>
                <div class="flex items-center gap-4">
                  <div class="flex-shrink-0 h-16 w-16 rounded-full overflow-hidden border-2 border-gray-200">
                    <img v-if="editCategory.image" :src="typeof editCategory.image === 'string' ? editCategory.image : URL.createObjectURL(editCategory.image)" alt="Current" class="h-full w-full object-cover">
                    <div v-else class="h-full w-full bg-gray-100 flex items-center justify-center text-gray-400">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                      </svg>
                    </div>
                  </div>
                  <label for="edit-image-upload" class="cursor-pointer">
                    <div class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                      Change Image
                    </div>
                    <input id="edit-image-upload" type="file" @change="handleImageEditUpload" class="sr-only" accept="image/*">
                  </label>
                </div>
                <div v-if="editCategory.image && typeof editCategory.image !== 'string'" class="mt-2 flex items-center">
                  <span class="text-sm text-gray-600">{{ editCategory.image.name }}</span>
                  <button @click="editCategory.image = currentCategory.image" type="button" class="ml-2 text-red-500 hover:text-red-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </button>
                </div>
              </div>
              
              <div class="flex justify-end space-x-3 pt-4">
                <button 
                  @click="closeModal"
                  type="button"
                  class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                  Cancel
                </button>
                <button 
                  type="submit"
                  class="px-4 py-2 bg-blue-600 rounded-lg text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                  Save Changes
                </button>
              </div>
            </form>
          </template>

          <template v-else-if="modalType === 'delete'">
            <div class="text-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-16 w-16 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
              <h3 class="mt-4 text-lg font-medium text-gray-900">Delete category</h3>
              <p class="mt-2 text-sm text-gray-500">
                Are you sure you want to delete <span class="font-semibold">"{{ currentCategory.name }}"</span>? This action cannot be undone.
              </p>
              <div class="mt-6 flex justify-center space-x-4">
                <button 
                  @click="closeModal"
                  type="button"
                  class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                  Cancel
                </button>
                <button 
                  @click="handleDeleteCategory(currentCategory.id)"
                  type="button"
                  class="px-4 py-2 bg-red-600 rounded-lg text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                >
                  Delete
                </button>
              </div>
            </div>
          </template>
        </div>
      </Modal>
    </div>
  </div>
</template>

<script>
import api from "../../../../axios/Axios";
import Modal from "./user/Modal.vue";

export default {
  components: { Modal },
  data() {
    return {
      categories: [],
      loading: false,
      error: null,
      modalVisible: false,
      modalType: null,
      modalTitle: "",
      currentCategory: null,
      newCategory: {
        name: "",
        image: null
      },
      editCategory: {
        id: null,
        name: "",
        image: null
      },
      currentPage: 1,
      categoriesPerPage: 5,
      searchQuery: "",
      maxVisiblePages: 5
    };
  },
  computed: {
    filteredCategories() {
      if (!this.searchQuery) {
        return this.categories;
      }
      const query = this.searchQuery.toLowerCase();
      return this.categories.filter(category => 
        category.name.toLowerCase().includes(query) ||
        category.id.toString().includes(query)
      );
    },
    totalPages() {
      return Math.ceil(this.filteredCategories.length / this.categoriesPerPage);
    },
    startIndex() {
      return (this.currentPage - 1) * this.categoriesPerPage;
    },
    endIndex() {
      return Math.min(this.startIndex + this.categoriesPerPage, this.filteredCategories.length);
    },
    paginatedCategories() {
      return this.filteredCategories.slice(this.startIndex, this.endIndex);
    },
    visiblePages() {
      const pages = [];
      let startPage = Math.max(1, this.currentPage - Math.floor(this.maxVisiblePages / 2));
      let endPage = Math.min(this.totalPages, startPage + this.maxVisiblePages - 1);
      
      if (endPage - startPage + 1 < this.maxVisiblePages) {
        startPage = Math.max(1, endPage - this.maxVisiblePages + 1);
      }
      
      for (let i = startPage; i <= endPage; i++) {
        pages.push(i);
      }
      
      return pages;
    }
  },
  methods: {
    async fetchCategories() {
      this.loading = true;
      this.error = null;
      try {
        const token = sessionStorage.getItem("auth_token");
        const response = await api.get("/categories", {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        this.categories = response.data.data;
      } catch (err) {
        console.error("Fetch error:", err);
        this.error = "Error fetching categories!";
      } finally {
        this.loading = false;
      }
    },
    showAddModal() {
      this.modalType = 'add';
      this.modalVisible = true;
      this.modalTitle = 'Add New Category';
      this.newCategory = { name: "", image: null };
      this.currentCategory = null;
    },
    showEditModal(category) {
      this.modalType = 'edit';
      this.modalVisible = true;
      this.modalTitle = 'Edit Category';
      this.editCategory = { ...category };
      this.currentCategory = { ...category };
    },
    showViewModal(category) {
      this.modalType = 'view';
      this.modalVisible = true;
      this.modalTitle = 'Category Details';
      this.currentCategory = { ...category };
    },
    showDeleteModal(category) {
      this.modalType = 'delete';
      this.modalVisible = true;
      this.modalTitle = 'Confirm Deletion';
      this.currentCategory = { ...category };
    },
    async handleCreateCategory() {
      try {
        const token = sessionStorage.getItem("auth_token");
        const formData = new FormData();
        formData.append("name", this.newCategory.name);
        if (this.newCategory.image) formData.append("image", this.newCategory.image);

        const response = await api.post("/addcategories", formData, {
          headers: {
            Authorization: `Bearer ${token}`,
            "Content-Type": "multipart/form-data"
          },
        });
        
        this.$toast.success('Category added successfully!', {
          position: 'top-right',
          duration: 3000
        });
        
        this.closeModal();
        this.fetchCategories();

      } catch (error) {
        console.error('Error adding category:', error);
        this.$toast.error('Failed to add category. Please try again.', {
          position: 'top-right',
          duration: 3000
        });
      }
    },
    async handleUpdateCategory() {
      try {
        const formData = new FormData();
        formData.append("name", this.editCategory.name);
        if (this.editCategory.image instanceof File) {
          formData.append("image", this.editCategory.image);
        }
        formData.append("_method", "PUT");

        const response = await api.post(
          `/updatecategories/${this.editCategory.id}`,
          formData,
          {
            headers: {
              Authorization: `Bearer ${sessionStorage.getItem('auth_token')}`,
              "Content-Type": "multipart/form-data",
            },
          }
        );

        this.$toast.success('Category updated successfully!', {
          position: 'top-right',
          duration: 3000
        });
        
        this.closeModal();
        this.fetchCategories();
      } catch (error) {
        console.error('Error updating category:', error.response || error);
        this.$toast.error(
          error.response?.data?.message || 'Failed to update category. Please try again.',
          {
            position: 'top-right',
            duration: 3000
          }
        );
      }
    },
    async handleDeleteCategory(categoryId) {
      try {
        const token = sessionStorage.getItem("auth_token");
        await api.delete(`/deletecategory/${categoryId}`, {
          headers: {
            Authorization: `Bearer ${token}`
          }
        });
        
        this.$toast.success('Category deleted successfully!', {
          position: 'top-right',
          duration: 3000
        });
        
        this.closeModal();
        this.fetchCategories();
      } catch (error) {
        console.error('Error deleting category:', error);
        this.$toast.error('Failed to delete category. Please try again.', {
          position: 'top-right',
          duration: 3000
        });
      }
    },
    closeModal() {
      this.modalVisible = false;
      this.modalType = null;
      this.currentCategory = null;
      this.editCategory = { id: null, name: '', image: null };
    },
    goToPage(page) {
      this.currentPage = page;
    },
    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
      }
    },
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
      }
    },
    handleImageUpload(event) {
      const file = event.target.files[0];
      if (file && file.type.startsWith('image/')) {
        this.newCategory.image = file;
      } else {
        this.$toast.warning('Please select a valid image file.', {
          position: 'top-right',
          duration: 3000
        });
      }
    },
    handleImageEditUpload(event) {
      const file = event.target.files[0];
      if (file && file.type.startsWith('image/')) {
        this.editCategory.image = file;
      } else {
        this.$toast.warning('Please select a valid image file.', {
          position: 'top-right',
          duration: 3000
        });
      }
    },
    formatDate(dateString) {
      if (!dateString) return '';
      const options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
      return new Date(dateString).toLocaleDateString(undefined, options);
    }
  },
  watch: {
    categoriesPerPage() {
      this.currentPage = 1; // Reset to first page when changing items per page
    },
    searchQuery() {
      this.currentPage = 1; // Reset to first page when searching
    }
  },
  mounted() {
    this.fetchCategories();
  },
};
</script>

<style scoped>
/* Custom transitions */
.transition {
  transition: all 0.2s ease-in-out;
}

/* Custom scrollbar for table */
::-webkit-scrollbar {
  height: 6px;
  width: 6px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}

::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
  background: #a1a1a1;
}
</style>