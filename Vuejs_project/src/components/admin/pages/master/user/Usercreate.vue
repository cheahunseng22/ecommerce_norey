<template>
  <div class="font-sans bg-gray-100 p-5 text-gray-800 min-h-screen">
    <h1 class="text-3xl font-semibold mb-6 text-gray-900">Account</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <!-- Left Profile Card -->
      <div
        class="md:col-span-1 bg-white rounded-lg shadow-md p-6 flex flex-col items-center"
      >
        <div class="relative w-24 h-24 mb-4">
          <!-- The 'image' is now from userProfile.image -->
          <img
            :src="
              userProfile.image ||
              'https://placehold.co/96x96/E0F2F7/000000?text=Profile'
            "
            alt="User Avatar"
            class="w-full h-full rounded-full object-cover border-4 border-white shadow-sm"
            onerror="this.onerror=null;this.src='https://placehold.co/96x96/E0F2F7/000000?text=Error';"
          />
        </div>
        <h2 class="text-xl font-semibold text-gray-900 mb-1">
          {{ userProfile.firstName }} {{ userProfile.lastName }}
        </h2>

        <!-- Display user role -->
        <p
          class="text-sm font-medium text-indigo-600 bg-indigo-100 px-2 py-0.5 rounded-full"
        >
          {{ userProfile.role }}
        </p>
        <button
          @click="handleUploadPicture"
          class="text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors duration-200 mt-4"
        >
          Upload picture
        </button>
        <input
          type="file"
          ref="fileInput"
          @change="onFileChange"
          accept="image/*"
          style="display: none"
        />
      </div>

      <!-- Right Profile Edit Form -->
      <div class="md:col-span-2 bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-semibold text-gray-900 mb-2">Profile</h3>
        <p class="text-gray-600 text-sm mb-6">The information can be edited</p>

        <div
          v-if="message"
          :class="[
            'p-3 mb-4 rounded-md text-sm',
            messageType === 'success'
              ? 'bg-green-100 text-green-800'
              : 'bg-red-100 text-red-800',
          ]"
        >
          {{ message }}
        </div>

        <form
          @submit.prevent="saveDetails"
          class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4"
        >
          <!-- First Name -->
          <div>
            <label
              for="firstName"
              class="block text-xs font-semibold text-gray-600 mb-1"
            >
              Full Name</label
            >
            <input
              type="text"
              id="firstName"
              v-model="userProfile.firstName"
              class="form-input"
              placeholder="user"
              :class="{ 'border-red-500': errors.firstName }"
              required
            />
            <p v-if="errors.firstName" class="error-message">
              {{ errors.firstName }}
            </p>
          </div>

          <!-- Email Address -->
          <div>
            <label
              for="email"
              class="block text-xs font-semibold text-gray-600 mb-1"
              >Email Address</label
            >
            <input
              type="email"
              id="email"
              v-model="userProfile.email"
              class="form-input"
              placeholder="user@gmail.com"
              :class="{ 'border-red-500': errors.email }"
              required
            />
            <p v-if="errors.email" class="error-message">{{ errors.email }}</p>
          </div>
          <!-- Last Name -->

          <!-- Password -->
          <div>
            <label
              for="password"
              class="block text-xs font-semibold text-gray-600 mb-1"
              >Password</label
            >
            <input
              type="password"
              id="password"
              v-model="userProfile.password"
              placeholder="Leave blank to keep current password"
              class="form-input"
              :class="{ 'border-red-500': errors.password }"
            />
            <p v-if="errors.password" class="error-message">
              {{ errors.password }}
            </p>
          </div>
          <!-- Phone Number -->
          <div>
            <label
              for="phone"
              class="block text-xs font-semibold text-gray-600 mb-1"
              >Phone number</label
            >
            <input
              type="tel"
              id="phone"
              v-model="userProfile.phone"
              placeholder="098765432"
              class="form-input"
            />
          </div>

          <!-- Role Dropdown -->
          <div>
            <label
              for="role"
              class="block text-xs font-semibold text-gray-600 mb-1"
              >Role *</label
            >
            <select
              id="role"
              v-model="userProfile.role"
              class="form-input"
              :class="{ 'border-red-500': errors.role }"
              required
            >
              <option
                v-for="roleOption in roleOptions"
                :key="roleOption"
                :value="roleOption"
              >
                {{ roleOption }}
              </option>
            </select>
            <p v-if="errors.role" class="error-message">{{ errors.role }}</p>
          </div>
 <!-- status -->
   <div>
            <label
              for="status"
              class="block text-xs font-semibold text-gray-600 mb-1"
            >
              Status</label
            >
            <input
              type="text"
              id="status"
              v-model="userProfile.status"
              class="form-input"
              placeholder=""
              :class="{ 'border-red-500': errors.status }"
              required
            />
            <p v-if="errors.status" class="error-message">
              {{ errors.status }}
            </p>
          </div>
          <!-- Save Details Button -->
          <div class="md:col-span-2 flex justify-end mt-4">
            <button
              type="submit"
              class="bg-blue-600 text-white px-6 py-3 rounded-md shadow-md hover:bg-blue-700 transition font-semibold"
            >
              create user
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onUnmounted } from "vue";
import api from "../../../../../axios/Axios";

// Reactive state for user profile data, including new fields
const userProfile = ref({
  firstName: "",
  email: "",
  phone: "",
  country:"",
  city:"",
  image: null, // Holds the blob URL for the image preview
  password: "", // For changing the password
  type: "",
  status:"",
});

// To store the actual file object for upload
const imageFileToUpload = ref(null);

// State for form validation errors
const errors = ref({});

// State for success/error messages
const message = ref("");
const messageType = ref(""); // 'success' or 'error'

// Options for the Role dropdown
const roleOptions = ref(["admin", "user"]);

// Ref for the hidden file input
const fileInput = ref(null);

// Trigger the hidden file input click
const handleUploadPicture = () => {
  fileInput.value.click();
};

// Handle file selection from the input
const onFileChange = (event) => {
  const file = event.target.files[0];
  if (file) {
    if (!file.type.startsWith("image/")) {
      showMessage("Please select a valid image file.", "error");
      return;
    }
    // Revoke the old object URL to prevent memory leaks
    if (
      userProfile.value.image &&
      userProfile.value.image.startsWith("blob:")
    ) {
      URL.revokeObjectURL(userProfile.value.image);
    }
    // Store the file object for later upload
    imageFileToUpload.value = file;
    // Create a temporary URL for the preview
    userProfile.value.image = URL.createObjectURL(file);
    showMessage('Image selected. Click "Save details" to upload.', "success");
  }
};

// Form validation logic
const validateForm = () => {
  errors.value = {};
  const profile = userProfile.value;

  if (!profile.firstName.trim())
    errors.value.firstName = "First name is required.";

  if (!profile.role) errors.value.role = "Role is required.";

  // Email validation
  if (!profile.email.trim()) {
    errors.value.email = "Email is required.";
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(profile.email)) {
    errors.value.email = "Invalid email format.";
  }

  // Password validation (only if a new password is being set)
  if (profile.password) {
    if (profile.password.length < 8) {
      errors.value.password = "Password must be at least 8 characters long.";
    }
  }

  return Object.keys(errors.value).length === 0;
};

// Show a temporary message
const showMessage = (msg, type) => {
  message.value = msg;
  messageType.value = type;
  setTimeout(() => {
    message.value = "";
    messageType.value = "";
  }, 4000);
};

// Handle form submission
const saveDetails = async () => {
  const formData = new FormData();
  
  console.log({ user: userProfile });
  // // Append all your form fields to FormData
  formData.append("name", userProfile.value.firstName);
  formData.append("email", userProfile.value.email);
  formData.append("password", userProfile.value.password);
  formData.append("phone", userProfile.value.phone);
  formData.append("type", userProfile.value.role);
  formData.append("status",userProfile.value.status);
  formData.append("country", userProfile.value.country);
formData.append("city", userProfile.value.city);

  try {
    const token = sessionStorage.getItem("auth_token");
    if (!token) {
      alert("No token found. Please log in.");
      return;
    }

    const response = await api.post(
      "/addusers", // Correct endpoint for creating products
      formData, // Send the FormData object as the request body
      {
        headers: {
          Authorization: `Bearer ${token}`,
          "Content-Type": "multipart/form-data",
        },
      }
    );

    console.log("User created successfully:", response.data);
    alert("User created successfully!");
  } catch (error) {
    console.error(
      "Error creating user:",
      error.response?.data || error.message
    );
    alert("Failed to create product. Please check console for details.");
    // Optionally, handle specific error messages from error.response.data.errors
  }
};

// Clean up the object URL when the component is unmounted
onUnmounted(() => {
  if (userProfile.value.image && userProfile.value.image.startsWith("blob:")) {
    URL.revokeObjectURL(userProfile.value.image);
  }
});
</script>

<style>
/* 
  Using Tailwind's @apply directive to create reusable utility classes.
  This requires your CSS build process (like PostCSS) to be configured to handle @apply.
*/
.form-input {
  @apply w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm;
}
.error-message {
  @apply text-red-500 text-xs mt-1;
}
</style>
