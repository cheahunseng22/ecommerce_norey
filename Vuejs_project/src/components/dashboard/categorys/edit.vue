<template>
  <div class="p-4 sm:p-6 md:p-8 bg-gray-100 min-h-screen font-sans">
    <header class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900">Edit Category</h1>
      <p class="text-sm text-gray-600">Update category details.</p>
    </header>

    <form @submit.prevent="saveCategory" class="space-y-6 bg-white p-6 rounded-xl shadow border border-gray-200 max-w-2xl">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">ID</label>
        <input
          type="text"
          v-model="form.id"
          disabled
          class="w-full rounded-lg border border-gray-300 bg-gray-100 px-3 py-2 text-sm text-gray-700"
        />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
        <input
          type="text"
          v-model.trim="form.name"
          required
          placeholder="Category name"
          class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
        />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
        <textarea
          v-model.trim="form.description"
          rows="3"
          placeholder="Short description"
          class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
        ></textarea>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Image</label>
        <div class="flex items-center gap-4">
          <div class="w-20 h-20 rounded-lg bg-gray-100 overflow-hidden flex items-center justify-center border border-gray-200">
            <img v-if="form.imagePreview || form.currentImage" :src="form.imagePreview || form.currentImage" alt="Preview" class="w-full h-full object-cover" />
            <span v-else class="text-gray-400 text-xs">No image</span>
          </div>
          <div class="flex items-center gap-2">
            <input type="file" accept="image/*" @change="onFileChange" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
            <button v-if="form.imageFile || form.imagePreview" type="button" @click="clearImage" class="px-3 py-2 text-sm rounded-md border border-gray-200 text-gray-700 hover:bg-gray-50">Remove</button>
          </div>
        </div>
      </div>

      <p v-if="error" class="text-sm text-rose-600">{{ error }}</p>
      <p v-if="success" class="text-sm text-emerald-600">{{ success }}</p>

      <div class="flex justify-end gap-2 pt-2">
        <button type="button" @click="goBack" class="rounded-md border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</button>
        <button type="submit" :disabled="saving" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-60">
          <span v-if="!saving">Save</span>
          <span v-else>Saving...</span>
        </button>
      </div>
    </form>
  </div>
  
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import api from '../../../axios/Axios'

const router = useRouter()
const route = useRoute()

const form = reactive({
  id: null,
  name: '',
  description: '',
  currentImage: null,
  imageFile: null,
  imagePreview: null,
})

const saving = ref(false)
const error = ref('')
const success = ref('')

onMounted(loadCategory)

async function loadCategory() {
  try {
    const categoryId = route.query.id || route.params.id
    if (!categoryId) {
      error.value = 'Missing category id.'
      return
    }
    const res = await api.get(`/categories/${categoryId}`)
    const data = res?.data?.data ?? res?.data
    form.id = data.id
    form.name = data.name ?? ''
    form.description = data.description ?? ''
    form.currentImage = data.image ?? null
  } catch (e) {
    console.error('Failed to load category', e)
    error.value = 'Failed to load category.'
  }
}

function onFileChange(event) {
  const file = event?.target?.files?.[0]
  if (!file) return
  form.imageFile = file
  try {
    form.imagePreview = URL.createObjectURL(file)
  } catch {
    form.imagePreview = null
  }
}

function clearImage() {
  form.imageFile = null
  if (form.imagePreview) {
    try { URL.revokeObjectURL(form.imagePreview) } catch {}
  }
  form.imagePreview = null
}

async function saveCategory() {
  if (!form.name) {
    error.value = 'Name is required.'
    return
  }
  error.value = ''
  saving.value = true
  success.value = ''
  try {
    const fd = new FormData()
    fd.append('name', form.name)
    fd.append('description', form.description || '')
    if (form.imageFile) fd.append('image', form.imageFile)
    fd.append('_method', 'PUT')
    await api.post(`/categories/${form.id}`, fd, { headers: { 'Content-Type': 'multipart/form-data' } })
    success.value = 'Updated successfully'
    setTimeout(() => router.back(), 800)
  } catch (e) {
    console.error('Failed to save category', e)
    error.value = 'Failed to update. Please try again.'
  } finally {
    saving.value = false
  }
}

function goBack() { router.back() }
</script>

<style scoped>
</style>


