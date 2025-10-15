<template>
  <div class="px-6 py-4">
    <div class="mb-6 flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-semibold tracking-tight text-gray-900">Create Parent Category</h1>
        <p class="mt-1 text-sm text-gray-500">Static form placeholder for product parent category.</p>
      </div>
      <router-link to="/admin/parent/parentcategorylist" class="text-sm font-medium text-indigo-600 hover:text-indigo-700">Back to list</router-link>
    </div>

    <div class="max-w-3xl rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
          <label class="mb-1 block text-sm font-medium text-gray-700">Category name</label>
          <input type="text" class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500" placeholder="Enter category name" />
        </div>
      </div>
      <div class="mt-6 flex justify-end gap-2">
        <router-link to="/admin/parent/parentcategorylist" class="rounded-md border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</router-link>
        <button class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Save</button>
      </div>
    </div>
  </div>
</template>

<script setup>
// Static placeholder only
</script>

<style scoped></style>

