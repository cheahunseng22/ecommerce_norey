<template>
  <div class="p-4 sm:p-6 md:p-8 bg-gray-50 min-h-screen">
    <header class="mb-8">
      <h1 class="text-2xl font-bold text-gray-800">Create Category</h1>
    </header>

    <div class="bg-white p-6 sm:p-8 rounded-lg shadow-md">
      <form @submit.prevent="saveCategory">
        <div class="space-y-8">

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label for="category-name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
              <input
                v-model="category.name"
                type="text"
                id="category-name"
                placeholder="Category Name"
                class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
              />
            </div>

            <div>
              <label for="category-level" class="block text-sm font-medium text-gray-700 mb-1">Level</label>
              <input
                v-model.number="category.level"
                type="number"
                id="category-level"
                placeholder="Category Level (e.g., 1, 2, 3)"
                class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                min="1"
              />
            </div>

            <div class="col-span-1 md:col-span-2">
              <label for="parent-category" class="block text-sm font-medium text-gray-700 mb-1">Parent Category (Optional)</label>
              <select
                v-model="category.parentId"
                id="parent-category"
                class="block w-full px-4 py-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
              >
                <option :value="null">No Parent Category</option>
                <option v-for="parent in parentCategories" :key="parent.id" :value="parent.id">
                  {{ parent.name }}
                </option>
              </select>
            </div>
          </div>

          <div>
            <label for="category-image-upload" class="block text-sm font-medium text-gray-700 mb-1">Category Image</label>
            <input
              type="file"
              id="category-image-upload"
              @change="handleImageUpload"
              accept="image/*"
              class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
            />
            <div v-if="category.imageUrlPreview" class="mt-4">
              <p class="text-sm text-gray-500 mb-2">Image Preview:</p>
              <img :src="category.imageUrlPreview" alt="Category Image Preview" class="w-24 h-24 object-cover rounded-lg shadow-sm">
            </div>
          </div>

          <div class="flex items-center">
            <input
              v-model="category.isFeatured"
              id="featured-category"
              type="checkbox"
              class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
            />
            <label for="featured-category" class="ml-2 block text-sm text-gray-900">
              Featured Category
            </label>
          </div>

          <div class="flex justify-end space-x-4">
            <button
              type="button"
              @click="cancelCreate"
              class="px-5 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              Cancel
            </button>
            <button
              type="submit"
              class="px-5 py-2 bg-blue-600 text-white rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              Save Category
            </button>
          </div>

        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import api from '../../../axios/Axios';

const router = useRouter();

// --- Form State ---
const category = ref({
  name: '',
  parentId: null,
  imageFile: null, // To store the actual File object for upload
  imageUrlPreview: '', // To store the URL for image preview
  level: 1,
  isFeatured: false,
});

// --- Mock Data for Parent Categories (replace with your actual API call) ---
const parentCategories = ref([
  { id: 1, name: 'Men\'s Fashion' },
  { id: 2, name: 'Women\'s Fashion' },

]);

// --- Method to handle file selection and preview ---
const handleImageUpload = (event) => {
  const file = event.target.files[0];
  if (!file) return;

  try {
    // Validate file
    const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    const maxSize = 2 * 1024 * 1024; // 2MB

    if (!validTypes.includes(file.type)) {
      throw new Error('Invalid file type. Please upload an image (JPEG, PNG, GIF)');
    }

    if (file.size > maxSize) {
      throw new Error('File size exceeds 2MB limit');
    }

    // Create preview
    category.value.imageFile = file;
    category.value.imageUrlPreview = URL.createObjectURL(file);

  } catch (error) {
    alert(error.message);
    event.target.value = ''; // Reset input
  }
};

// --- Form Submission Handler ---
const saveCategory = async () => {
  const formData = new FormData();
  formData.append('name', category.value.name);
  formData.append('level', category.value.level);
  formData.append('isFeatured', category.value.isFeatured);
  
  if (category.value.parentId !== null) {
    formData.append('parentId', category.value.parentId);
  }

  if (category.value.imageFile) {
    formData.append('image', category.value.imageFile);
  }

  try {
    const token = sessionStorage.getItem("auth_token");
    if (!token) {
      alert("No token found. Please log in.");
      return;
    }

    const response = await api.post(
      "/addcategories",
      formData,
      {
        headers: {
          Authorization: `Bearer ${token}`,
          "Content-Type": "multipart/form-data"
        },
      }
    );

    alert('Category created successfully!');
    if (category.value.imageUrlPreview) {
      URL.revokeObjectURL(category.value.imageUrlPreview);
    }
    router.push({ name: 'categorylist' });

  } catch (error) {
    console.error("Error creating category:", error.response?.data || error.message);
    alert("Failed to create category.");
  }
};


  // Example of how you would send it to a backend API:
  /*
  fetch('/api/categories', {
    method: 'POST',
    // Do NOT set Content-Type header manually when using FormData,
    // the browser will set it correctly including the boundary.
    body: formData,
  })
  .then(response => {
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    return response.json();
  })
  .then(data => {
    console.log('Category saved successfully:', data);
    alert('Category saved successfully!');
    // After successful save, navigate back to the list
    router.push({ name: 'CategoryList' });
  })
  .catch(error => {
    console.error('Error saving category:', error);
    alert(`Error saving category: ${error.message}`);
  });
  */

  // For demonstration, we'll just log FormData content and navigate
  // for (let [key, value] of formData.entries()) {
  //   console.log(`${key}:`, value);
  // }
  // alert('Category saved (mock)! Check console for FormData content.');
  // router.push({ name: 'CategoryList' });

  // Clean up the object URL after navigation or successful submission if not needed anymore


// --- Cancel Button Handler ---
// const cancelCreate = () => {
//   if (confirm('Are you sure you want to cancel? Any unsaved changes will be lost.')) {
//     // Clean up the object URL before navigating away
//     if (category.value.imageUrlPreview) {
//       URL.revokeObjectURL(category.value.imageUrlPreview);
//     }
//     router.push({ name: 'categorylist' });
//   }
// };
</script>