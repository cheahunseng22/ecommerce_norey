<template>
  <div class="p-4 sm:p-6 md:p-8 bg-gray-100 min-h-screen font-sans">
    <header class="mb-8" v-if="!props.isModal">
      <h1 class="text-3xl font-extrabold text-gray-900">{{ isEditing ? 'Edit Product' : 'Create New Product' }}</h1>
      <p class="mt-1 text-sm text-gray-600">{{ isEditing ? 'Update the details for this product.' : 'Fill out the details below to add a new product to your inventory.' }}</p>
    </header>

    <form @submit.prevent="saveProduct" class="space-y-8">
      <div class="bg-white p-6 sm:p-8 rounded-xl shadow-lg border border-gray-200">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Product Details</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label for="product-name" class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
            <input
              v-model="product.name"
              type="text"
              id="product-name"
              placeholder="e.g., Wireless Ergonomic Mouse"
              class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm"
              required
            />
          </div>

          <div>
            <label for="product-price" class="block text-sm font-medium text-gray-700 mb-1">Price ($)</label>
            <input
              v-model.number="product.price"
              type="number"
              id="product-price"
              placeholder="e.g., 99.99"
              class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm"
              min="0"
              step="0.01"
              required
            />
          </div>

          <div class="col-span-1 md:col-span-2">
            <label for="product-description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea
              v-model="product.description"
              id="product-description"
              rows="4"
              placeholder="A detailed description of the product and its features."
              class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm"
            ></textarea>
          </div>
          <div class="col-span-1 md:col-span-2">
            <label for="product-gallery-upload" class="block text-sm font-medium text-gray-700 mb-1">Gallery Images (Optional)</label>
            <div
              @dragover.prevent
              @drop.prevent="handleMultipleImagesDrop"
              class="min-h-40 border-2 border-dashed border-gray-300 rounded-lg hover:border-blue-500 transition-colors duration-200 p-4"
            >
              <input
                type="file"
                ref="multiFileInput"
                @change="handleMultipleImagesUpload"
                accept="image/*"
                multiple
                class="hidden"
              />
              <div v-if="product.imagesPreviews.length === 0" 
                   @click="openMultipleSelector"
                   class="flex flex-col items-center justify-center cursor-pointer text-center text-gray-500 h-32">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                </svg>
                <p class="mt-2 text-sm">Drag and drop or click to upload up to 6 images</p>
                <p class="text-xs text-gray-400">PNG, JPG, GIF up to 2MB each</p>
              </div>
              <div v-else class="w-full">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 w-full">
                  <div v-for="(preview, index) in product.imagesPreviews.slice(0, 6)" :key="`preview-${index}`" class="relative group">
                    <img :src="getLaravelImageUrl(preview)" alt="Gallery Image Preview" class="h-28 w-full object-cover rounded-md border border-gray-200" />
                    <button type="button" @click.stop="removeGalleryImage(index)" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs shadow-md opacity-90 group-hover:opacity-100">×</button>
                  </div>
                  <!-- Add more button when less than 6 images -->
                  <div v-if="product.imagesPreviews.length < 6" 
                       @click="openMultipleSelector"
                       class="h-28 w-full border-2 border-dashed border-gray-300 rounded-md flex items-center justify-center cursor-pointer hover:border-blue-500 transition-colors duration-200">
                    <div class="text-center text-gray-500">
                      <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-8 w-8 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                      </svg>
                      <p class="text-xs mt-1">Add</p>
                    </div>
                  </div>
                </div>
                <p class="mt-2 text-xs text-gray-500">{{ product.imagesPreviews.length >= 6 ? 'Maximum 6 images reached' : `Click the + button or drop more images to add (${product.imagesPreviews.length}/6)` }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-white p-6 sm:p-8 rounded-xl shadow-lg border border-gray-200">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Categorization & Sourcing</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label for="product-category" class="block text-sm font-medium text-gray-700 mb-1">SupCategory</label>
            <select
              v-model="product.category_id"
              id="product-category"
              class="block w-full px-4 py-3 border border-gray-300 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm"
              required
            >
              <option value="" disabled>Select a category</option>
              <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                {{ cat.name }}
              </option>
            </select>
          </div>
          <div>
            <label for="product-brand" class="block text-sm font-medium text-gray-700 mb-1">Brand (Optional)</label>
            <div v-if="loadingBrands">Loading brands...</div>
            <div v-if="errorBrands" class="text-red-500">{{ errorBrands }}</div>
            <select
              v-else
              v-model="product.brand_id"
              id="product-brand"
              class="block w-full px-4 py-3 border border-gray-300 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm"
            >
              <option value="">Select Brand</option>
              <option
                v-for="brand in brands"
                :key="brand.id"
                :value="brand.id"
              >
                {{ brand.name }}
              </option>
            </select>
          </div>
          <!-- <div>
            <label for="product_inventory" class="block text-sm font-medium text-gray-700 mb-1">Inventory (Optional)</label>
           <select
              v-model="product.inventory_id"
              id="product-inventory"
              class="block w-full px-4 py-3 border border-gray-300 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm"
            >
              <option value="">Select Inventory</option>
              <option 
                v-for="inventories in inventories" 
                :key="inventories.id" 
                :value="inventories.id"
              >
                {{ inventories.name }}
              </option>
            </select>
          </div> -->
          <!-- <div class="col-span-1 md:col-span-2">
            <label for="product-supplier" class="block text-sm font-medium text-gray-700 mb-1">Supplier (Optional)</label>
            <select
              v-model="product.supplier_id"
              id="product-supplier"
              class="block w-full px-4 py-3 border border-gray-300 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm"
            >
              <option :value="null">  Select Supplier</option>
              <option
              
               v-for="supplier in suppliers" 
              :key="supplier.id" 
              :value="supplier.id">
                {{ supplier.name }}
              </option>
            </select>
          </div> -->
        </div>
      </div>
      
      <div class="bg-white p-6 sm:p-8 rounded-xl shadow-lg border border-gray-200">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Visuals & Status</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label for="product-image-upload" class="block text-sm font-medium text-gray-700 mb-1">Product Image (Optional)</label>
            <div 
              @click="openImageSelector"
              @dragover.prevent
              @drop.prevent="handleImageDrop"
              class="flex flex-col items-center justify-center h-40 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-blue-500 transition-colors duration-200"
            >
              <input
                type="file"
                ref="fileInput"
                @change="handleImageUpload"
                accept="image/*"
                class="hidden"
              />
              <div v-if="!product.imageUrlPreview" class="text-center text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                </svg>
                <p class="mt-2 text-sm">Drag and drop or click to upload</p>
                <p class="text-xs text-gray-400">PNG, JPG, GIF up to 2MB</p>
              </div>
              <div v-else class="flex flex-col items-center justify-center">
                <img :src="getLaravelImageUrl(product.imageUrlPreview)" alt="Product Image Preview" class="h-32 w-32 object-contain rounded-lg">
                <button type="button" @click.stop="removeImage" class="mt-2 text-red-500 hover:text-red-700 text-sm">Remove</button>
              </div>
            </div>
          </div>
          <div>
            <label for="product-rating" class="block text-sm font-medium text-gray-700 mt-6 mb-1">Rating</label>
            <select
              v-model="product.rating"
              id="product-rating"
              class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm"
            >
              <option value="">Select a rating...</option>
              <option value="1">⭐ 1 star</option>
              <option value="2">⭐⭐ 2 stars</option>
              <option value="3">⭐⭐⭐ 3 stars</option>
              <option value="4">⭐⭐⭐⭐ 4 stars</option>
              <option value="5">⭐⭐⭐⭐⭐ 5 stars</option>
            </select>
            <label for="product-size" class="block text-sm font-medium text-gray-700 mt-6 mb-1">Size (Optional)</label>
            <input
              v-model="product.size"
              type="text"
              id="product-size"
              placeholder="e.g., M, L, XL or 10x10x5cm"
              class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm"
            />
            <div class="flex items-center mt-6">
              <input
                v-model="product.is_active"
                id="product-active"
                type="checkbox"
                class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
              />
              <label for="product-active" class="ml-2 block text-sm text-gray-900">
                Is Active
              </label>
            </div>
          </div>
        </div>
      </div>
      
      <div v-if="errorMessage" class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-md" role="alert">
        <p class="font-bold">Error</p>
        <p>{{ errorMessage }}</p>
      </div>
      <div v-if="successMessage" class="bg-green-50 border border-green-200 text-green-700 p-4 rounded-md" role="alert">
        <p class="font-bold">Success</p>
        <p>{{ successMessage }}</p>
      </div>

      <div class="flex justify-end space-x-4 pt-4">
        <button
          type="button"
          @click="cancelCreate"
          class="px-6 py-3 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200"
        >
          Cancel
        </button>
        <button
          type="submit"
          :disabled="isLoading"
          class="px-6 py-3 bg-blue-600 text-white rounded-lg shadow-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:bg-blue-300 disabled:cursor-not-allowed transition-colors duration-200"
        >
          {{ isLoading ? (isEditing ? 'Updating...' : 'Creating...') : (isEditing ? 'Update Product' : 'Create Product') }}
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import api from '../../../axios/Axios';

const router = useRouter();
const route = useRoute();
const fileInput = ref(null);
const multiFileInput = ref(null);

// PROPS and EMITS
const props = defineProps({
  isModal: { type: Boolean, default: false },
  productId: { type: [String, Number], default: null } // New prop to handle product ID
});
const emit = defineEmits(['close', 'created', 'updated']);

// REACTIVE STATE
const product = ref({
  name: '',
  description: '',
  price: null,
  rating: null,
  size: '',
  category_id: '',
  brand_id: '',
  imageFile: null,
  imageUrl: '', // Holds the URI from the API
  imageUrlPreview: '', // Holds the local blob URL for display
  imagesFiles: [],
  images: [], // Holds the URIs from the API
  imagesPreviews: [], // Holds local blob URLs for display
  is_active: true,
});

const isLoading = ref(false);
const errorMessage = ref('');
const successMessage = ref('');
const categories = ref([]);
const brands = ref([]);
const loadingBrands = ref(false);
const errorBrands = ref('');
// const inventory = ref([]);

const isEditing = computed(() => !!props.productId);
watch(
  () => props.productId,
  (newId) => {
    if (newId) {
      fetchProductData(newId);
    } else {
      resetForm();
    }
  },
  
);

// optional onMounted logic
onMounted(() => {
  fetchDropdownData();
  if (isEditing.value) {
    fetchProductData(props.productId);
  }
});
// --- HELPER FUNCTIONS ---
const getLaravelImageUrl = (imagePath) => {
  if (!imagePath) return '';
  
  // If it's already a full URL, return as-is
  if (imagePath.startsWith('http://') || imagePath.startsWith('https://')) {
    return imagePath;
  }
  
  // If it's a blob URL (newly uploaded), return as-is
  if (imagePath.startsWith('blob:')) {
    return imagePath;
  }
  
  // Construct Laravel storage URL
  const baseUrl = api.defaults.baseURL || 'http://localhost:8000';
  
  // Remove '/api' from base URL if present for storage URLs
  const storageBaseUrl = baseUrl.replace('/api', '');
  
  // Handle different path formats
  if (imagePath.startsWith('/')) {
    return `${storageBaseUrl}${imagePath}`;
  } else if (imagePath.startsWith('storage/')) {
    return `${storageBaseUrl}/${imagePath}`;
  } else {
    return `${storageBaseUrl}/storage/${imagePath}`;
  }
};

// --- API FETCHING FUNCTIONS ---
const fetchDropdownData = async () => {
  try {
    const categoriesResponse = await api.get('/categories');
    categories.value = categoriesResponse.data.data;
     const brandsResponse = await api.get('/brands');
    brands.value =brandsResponse.data.data;
    // const inventoryResponse = await api.get('/inventory');
    // inventory.value = inventoryResponse.data;
    // brands.value = [
    //   { id: 101, name: 'TechCo' },
    //   { id: 102, name: 'Fashionista' },
    //   { id: 103, name: 'HomeLux' },
    // ];
    // suppliers.value = [
    //   { id: 201, name: 'Global Supply' },
    //   { id: 202, name: 'Local Distributors' },
    // ];
  } catch (error) {
    console.error("Failed to fetch dropdown data:", error);
    errorMessage.value = "Failed to load necessary data for dropdowns.";
  }
};

const fetchProductData = async (id) => {
  isLoading.value = true;
  errorMessage.value = '';
  try {
    const response = await api.get(`/showproductds/${id}`);
    const fetchedProduct = response.data.data;

    // Populate the form with fetched data
    product.value.name = fetchedProduct.name;
    product.value.description = fetchedProduct.description || '';
    product.value.price = fetchedProduct.price;
    product.value.rating = fetchedProduct.rating;
    product.value.size = fetchedProduct.size || '';
    product.value.category_id = fetchedProduct.category_id;
    product.value.brand_id = fetchedProduct.brand_id;
    product.value.is_active = fetchedProduct.is_active;

    // Handle single product image
    product.value.imageUrl = fetchedProduct.image || '';
    if (product.value.imageUrl) {
      product.value.imageUrlPreview = getLaravelImageUrl(product.value.imageUrl);
    } else {
      product.value.imageUrlPreview = '';
    }

    // Handle gallery images - convert Laravel paths to full URLs
    product.value.images = fetchedProduct.images || [];
    product.value.imagesPreviews = (fetchedProduct.images || []).map(imagePath => getLaravelImageUrl(imagePath));

  } catch (error) {
    console.error("Error fetching product data:", error);
    errorMessage.value = "Failed to load product data.";
  } finally {
    isLoading.value = false;
  }
};

// --- IMAGE HANDLING LOGIC ---
// Image handling remains largely the same, but now it deals with existing URLs as well.
const openImageSelector = () => fileInput.value.click();
const handleImageDrop = (event) => processFile(event.dataTransfer.files[0]);
const handleImageUpload = (event) => processFile(event.target.files[0]);

const processFile = (file) => {
  if (!file) return;
  const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/svg+xml'];
  const maxSize = 2 * 1024 * 1024; // 2MB

  try {
    if (!validTypes.includes(file.type)) {
      throw new Error('Invalid file type.');
    }
    if (file.size > maxSize) {
      throw new Error('File size exceeds the 2MB limit.');
    }
    
    product.value.imageFile = file;
    product.value.imageUrlPreview = URL.createObjectURL(file);
    errorMessage.value = '';
  } catch (error) {
    alert(error.message);
    if (fileInput.value) fileInput.value.value = '';
    product.value.imageFile = null;
    product.value.imageUrlPreview = '';
  }
};

const removeImage = () => {
  if (product.value.imageFile) URL.revokeObjectURL(product.value.imageUrlPreview);
  product.value.imageFile = null;
  product.value.imageUrl = '';
  product.value.imageUrlPreview = '';
  if (fileInput.value) fileInput.value.value = '';
};

const MAX_GALLERY_IMAGES = 6;
const validImageTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/svg+xml'];
const maxImageSize = 2 * 1024 * 1024; // 2MB

const openMultipleSelector = () => multiFileInput.value && multiFileInput.value.click();

const handleMultipleImagesDrop = (event) => {
  const files = Array.from(event.dataTransfer.files || []);
  processMultipleFiles(files);
};

const handleMultipleImagesUpload = (event) => {
  const files = Array.from(event.target.files || []);
  processMultipleFiles(files);
  if (multiFileInput.value) multiFileInput.value.value = '';
};

const processMultipleFiles = (files) => {
  if (!files || files.length === 0) return;
  
  // Calculate total current images (existing + new files)
  const totalCurrentImages = product.value.images.length + product.value.imagesFiles.length;
  const remainingSlots = MAX_GALLERY_IMAGES - totalCurrentImages;
  
  if (remainingSlots <= 0) {
    alert(`You can upload up to ${MAX_GALLERY_IMAGES} images total.`);
    return;
  }

  const filesToAdd = files.slice(0, remainingSlots);
  

  for (const file of filesToAdd) {
    if (!validImageTypes.includes(file.type)) {
      alert('One or more files have an invalid type. Only JPEG, PNG, JPG, GIF, SVG are allowed.');
      continue;
    }
    if (file.size > maxImageSize) {
      alert('One or more files exceed the 2MB size limit.');
      continue;
    }
    product.value.imagesFiles.push(file);
    product.value.imagesPreviews.push(URL.createObjectURL(file));
  }
};

const removeGalleryImage = (index) => {
  const previewUrl = product.value.imagesPreviews[index];
  
  // Clean up blob URL if it exists
  if (previewUrl && previewUrl.startsWith('blob:')) {
    try { 
      URL.revokeObjectURL(previewUrl); 
    } catch {}
  }
  
  // Determine if this is an existing image or a new file
  const existingImagesCount = product.value.images.length;
  
  if (index < existingImagesCount) {
    // Removing an existing image from the server
    product.value.images.splice(index, 1);
  } else {
    // Removing a newly uploaded file
    const fileIndex = index - existingImagesCount;
    if (fileIndex >= 0 && fileIndex < product.value.imagesFiles.length) {
      product.value.imagesFiles.splice(fileIndex, 1);
    }
  }
  
  // Always remove from the preview array
  product.value.imagesPreviews.splice(index, 1);
};

const revokeAllGalleryPreviews = () => {
  for (const url of product.value.imagesPreviews) {
    if (url && url.startsWith('blob:')) {
      try { 
        URL.revokeObjectURL(url); 
      } catch {}
    }
  }
  product.value.imagesPreviews = [];
  product.value.imagesFiles = [];
};

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

const normalizeMultipleUris = (data) => {
  if (!data) return [];
  if (Array.isArray(data)) {
    return data.map((item) => (typeof item === 'string' ? item : item?.uri)).filter(Boolean);
  }
  if (Array.isArray(data?.uris)) {
    return data.uris.filter(Boolean);
  }
  if (typeof data?.uri === 'string') {
    return [data.uri];
  }
  return [];
};

const uploadMultipleImages = async (files) => {
  const token = sessionStorage.getItem("auth_token");
  const form = new FormData();
  for (const f of files) {
    form.append('files[]', f);
  }
  const res = await api.post('/files/upload-multiple', form, {
    headers: {
      Authorization: `Bearer ${token}`,
      'Content-Type': 'multipart/form-data'
    }
  });
  return normalizeMultipleUris(res?.data?.data);
};

// --- FORM SUBMISSION LOGIC ---
const saveProduct = async () => {
  isLoading.value = true;
  errorMessage.value = '';
  successMessage.value = '';

  let uploadedImageUri = product.value.imageUrl; // Start with existing image URI
  let uploadedGalleryUris = [...product.value.images]; // Start with existing gallery URIs

  try {
    const token = sessionStorage.getItem("auth_token");
    if (!token) {
      errorMessage.value = "Authentication token not found. Please log in.";
      isLoading.value = false;
      return;
    }

    if (product.value.imageFile) {
      uploadedImageUri = await uploadSingleImage(product.value.imageFile);
    }
    if (product.value.imagesFiles && product.value.imagesFiles.length > 0) {
      const newGalleryUris = await uploadMultipleImages(product.value.imagesFiles);
      uploadedGalleryUris = [...uploadedGalleryUris, ...newGalleryUris];
    }

    const payload = {
      name: product.value.name,
      price: product.value.price,
      category_id: product.value.category_id,
      description: product.value.description || undefined,
      rating: product.value.rating !== null ? product.value.rating : undefined,
      size: product.value.size || undefined,
      brand_id: product.value.brand_id,
      image: uploadedImageUri || undefined,
      images: uploadedGalleryUris.length ? uploadedGalleryUris : undefined,
      is_active: product.value.is_active ? 1 : 0,
    };

    if (isEditing.value) {
      // Logic for editing an existing product
      await api.put(`/updateproducts/${props.productId}`, payload, {
        headers: {
          Authorization: `Bearer ${token}`,
          "Content-Type": "application/json"
        },
      });
      successMessage.value = 'Product updated successfully!';
    } else {
      // Logic for creating a new product
      await api.post("/addproducts", payload, {
        headers: {
          Authorization: `Bearer ${token}`,
          "Content-Type": "application/json"
        },
      });
      successMessage.value = 'Product created successfully!';
    }

    if (product.value.imageUrlPreview && product.value.imageUrlPreview.startsWith('blob:')) URL.revokeObjectURL(product.value.imageUrlPreview);
    revokeAllGalleryPreviews();

    if (props.isModal) {
      emit(isEditing.value ? 'updated' : 'created');
    } else {
      setTimeout(() => {
        router.push({ name: 'product-list' });
      }, 1500);
    }

  } catch (error) {
    console.error("Error saving product:", error.response?.data || error.message);
    const apiError = error.response?.data?.message || 'An unexpected error occurred.';
    errorMessage.value = "Failed to save product: " + apiError;
  } finally {
    isLoading.value = false;
  }
};

const resetForm = () => {
  product.value = {
    name: '',
    description: '',
    price: null,
    rating: null,
    size: '',
    category_id: '',
    brand_id: '',
    imageFile: null,
    imageUrl: '',
    imageUrlPreview: '',
    imagesFiles: [],
    images: [],
    imagesPreviews: [],
    is_active: true,
  };
  errorMessage.value = '';
  successMessage.value = '';
};

const cancelCreate = () => {
  if (confirm('Are you sure you want to cancel? Any unsaved changes will be lost.')) {
    if (product.value.imageUrlPreview && product.value.imageUrlPreview.startsWith('blob:')) URL.revokeObjectURL(product.value.imageUrlPreview);
    revokeAllGalleryPreviews();
    if (props.isModal) {
      emit('close');
    } else {
      router.push({ name: 'product-list' });
    }
  }
};
</script>