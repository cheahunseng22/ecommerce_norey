<template>
  <Transition name="modal-fade">
    <div v-if="show" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
      <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md transform transition-all scale-100 opacity-100">
        <h3 class="text-xl font-semibold mb-4 text-gray-800">
          {{ customerData ? 'Edit Customer' : 'Add New Customer' }}
        </h3>

        <form @submit.prevent="submitForm">
          <div class="mb-4">
            <label class="block text-gray-700 mb-2">Profile Image</label>
            <div class="flex items-center gap-4">
              <img
                :src="form.avatar || defaultAvatar"
                class="w-16 h-16 rounded-full object-cover border border-gray-200"
                alt="Preview"
              >
              <input
                type="file"
                @change="handleImageUpload"
                accept="image/*"
                class="hidden"
                ref="fileInput"
                id="avatarUpload"
              >
              <button
                type="button"
                @click="$refs.fileInput.click()"
                class="px-3 py-1 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 text-sm transition duration-200"
              >
                Choose Image
              </button>
            </div>
          </div>

          <div class="space-y-4">
            <div>
              <label class="block text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
              <input
                v-model="form.name"
                type="text"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                required
              >
            </div>

            <div>
              <label class="block text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
              <input
                v-model="form.email"
                type="email"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                required
              >
            </div>

            <div>
              <label class="block text-gray-700 mb-1">Phone <span class="text-red-500">*</span></label>
              <input
                v-model="form.phone"
                type="text"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                required
              >
            </div>

           

            <div>
              <label class="block text-gray-700 mb-1">Status</label>
              <select v-model="form.status" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
            </div>

            <div>
              <label class="block text-gray-700 mb-1">Orders Count</label>
              <input
                v-model.number="form.orders_count"
                type="number"
                min="0"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 transition duration-200"
              >
            </div>
          </div>

          <div class="flex justify-end gap-3 mt-6">
            <button
              type="button"
              @click="$emit('close')"
              class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 transition duration-200"
              :disabled="loading"
            >
              Cancel
            </button>
            <button
              type="submit"
              class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200"
              :disabled="loading"
            >
              <span v-if="loading" class="flex items-center">
                <i class="fas fa-spinner fa-spin mr-2"></i> Processing...
              </span>
              <span v-else>
                {{ customerData ? 'Update' : 'Save' }}
              </span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </Transition>
</template>

<script setup>
import { ref, watch, defineProps, defineEmits } from 'vue';
// Removed 'api' import here, as the modal should only emit data, not make API calls directly.

const props = defineProps({
  show: {
    type: Boolean,
    default: false // Crucial: The default state is hidden
  },
  customer: {
    type: Object,
    default: null
  },
  loading: { // Prop for disabling submit button during API call (controlled by parent)
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['close', 'submit-form']);

const form = ref({
  name: '',
  email: '',
  phone: '',

  status: 'active',
  orders_count: 0,
  avatar: ''
});
const selectedAvatarFile = ref(null);
const defaultAvatar = 'https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&f=y'; // Changed to a more generic gravatar placeholder

// Use a ref to hold customer data for the form, and watch the prop
const customerData = ref(null);

watch(() => props.customer, (newCustomer) => {
  if (newCustomer) {
    customerData.value = { ...newCustomer }; // Store a copy
    form.value = { ...newCustomer }; // Populate form fields
    if (newCustomer.avatar) {
      form.value.avatar = newCustomer.avatar;
    } else {
      form.value.avatar = ''; // Clear if no avatar
    }
  } else {
    // Reset form for a new customer
    customerData.value = null;
    form.value = {
      name: '',
      email: '',
      phone: '',
    
      status: 'active',
      orders_count: 0,
      avatar: ''
    };
    selectedAvatarFile.value = null;
  }
}, { immediate: true }); // Run immediately to populate on initial load if customer is provided

const handleImageUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    selectedAvatarFile.value = file;
    const reader = new FileReader();
    reader.onload = (e) => {
      form.value.avatar = e.target.result;
    };
    reader.readAsDataURL(file);
  } else {
    selectedAvatarFile.value = null;
    if (!props.customer) { // Only clear preview if it's a new customer form
      form.value.avatar = '';
    }
  }
};

const submitForm = () => {
  const formData = new FormData();
  formData.append('name', form.value.name);
  formData.append('email', form.value.email);
  formData.append('phone', form.value.phone);
  formData.append('status', form.value.status);
  formData.append('orders_count', form.value.orders_count);

  // if (selectedAvatarFile.value[0]) {
  //   // If a new avatar file has been selected, append it to FormData
  //   console.log("selectedFiles.value[0]", selectedAvatarFile.value[0]);
  //   formData.append('image', selectedAvatarFile.value[0]);
  // }
  //  else if (avatarRemoved.value) {
  //   // If the 'Remove Image' button was clicked, send a flag to the backend
  //   console.log("Flagging avatar for removal.");
  //   formData.append('avatar_removed', 'true');
  // }
  // No need for 'else if' or 'else' for avatar.
  // If selectedAvatarFile is null, 'avatar' won't be appended.
  // The backend should then handle existing avatars (keep if not sent) or default for new customers.

  // Emit the FormData object along with flags indicating if it's an update
  // and the customer ID if applicable. The parent component will handle the API call.
  emit('submit-form', {
    formData: formData,
    isUpdate: !!customerData.value,
    customerId: customerData.value ? customerData.value.id : null
  });

  // The 'loading' state is now controlled by the parent component via the 'loading' prop.
  // No direct API calls or loading state management here.
};
</script>

<style scoped>
/* Modal transition styles for a smoother appearance */
.modal-fade-enter-active,
.modal-fade-leave-active {
  transition: opacity 0.3s ease;
}
.modal-fade-enter-from,
.modal-fade-leave-to {
  opacity: 0;

}

/* Optional: Add a slight transform for the modal content */
.modal-fade-enter-active > div,
.modal-fade-leave-active > div {
  transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}
.modal-fade-enter-from > div,
.modal-fade-leave-to > div {
  transform: translateY(-20px) scale(0.95);
  opacity: 0;
}
</style>