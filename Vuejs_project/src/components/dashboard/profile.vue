<template>
  <div class="font-kantumruy bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen py-8 md:py-12">
    <div class="container mx-auto px-4 max-w-7xl">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8">

        <!-- Sidebar -->
        <aside class="lg:col-span-3">
          <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sticky top-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">My Account</h2>
            <nav class="space-y-1" role="navigation" aria-label="Account navigation">
              <!-- Personal Details Tab -->
              <button 
                @click="setTab('personal')"
                :class="[
                  'w-full text-left px-4 py-3 rounded-xl font-medium transition-all duration-200 flex items-center gap-3',
                  activeTab==='personal' 
                    ? 'bg-gradient-to-r from-pink-500 to-purple-500 text-white shadow-md' 
                    : 'hover:bg-gray-50 text-gray-700'
                ]"
                :aria-current="activeTab==='personal' ? 'page' : undefined">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Personal Details
              </button>

              <!-- Address Tab -->
              <button 
                @click="goToAddress"
                class="w-full text-left px-4 py-3 rounded-xl font-medium hover:bg-gray-50 text-gray-700 transition-all duration-200 flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Address
              </button>
            </nav>

            <!-- Preferences -->
            <div class="mt-8 pt-6 border-t border-gray-200">
              <h3 class="text-sm font-semibold text-gray-700 mb-3">Shop Preference</h3>
              <div class="flex gap-2">
                <button 
                  @click="setShop('women')"
                  :class="[
                    'flex-1 px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200',
                    preferences.shop==='women' 
                      ? 'bg-pink-600 text-white shadow-md' 
                      : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                  ]"
                  :aria-pressed="preferences.shop==='women'">
                  Women
                </button>
                <button 
                  @click="setShop('men')"
                  :class="[
                    'flex-1 px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200',
                    preferences.shop==='men' 
                      ? 'bg-pink-600 text-white shadow-md' 
                      : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                  ]"
                  :aria-pressed="preferences.shop==='men'">
                  Men
                </button>
              </div>
            </div>

            <!-- Dark Mode -->
            <div class="mt-6 pt-6 border-t border-gray-200">
              <h3 class="text-sm font-semibold text-gray-700 mb-3">Appearance</h3>
              <div class="flex gap-2">
                <button 
                  @click="setDarkMode(true)"
                  :class="[
                    'flex-1 px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200',
                    preferences.darkMode 
                      ? 'bg-gray-900 text-white shadow-md' 
                      : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                  ]"
                  :aria-pressed="preferences.darkMode">
                  Dark
                </button>
                <button 
                  @click="setDarkMode(false)"
                  :class="[
                    'flex-1 px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200',
                    !preferences.darkMode 
                      ? 'bg-gray-900 text-white shadow-md' 
                      : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                  ]"
                  :aria-pressed="!preferences.darkMode">
                  Light
                </button>
              </div>
            </div>

            <!-- Logout -->
            <div class="mt-6 pt-6 border-t border-gray-200">
              <button 
                @click="logout" 
                class="w-full px-4 py-3 rounded-xl text-red-600 font-medium hover:bg-red-50 transition-all duration-200 flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Logout
              </button>
            </div>
          </div>
        </aside>

        <!-- Main Content -->
        <section class="lg:col-span-9">
          <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 md:p-8">
            <!-- Header -->
            <div class="mb-8">
              <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">Edit Profile</h1>
              <p class="text-gray-600">Manage your personal information and preferences</p>
            </div>

            <!-- Success/Error Messages -->
            <transition name="fade">
              <div v-if="successMessage" class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl flex items-start gap-3">
                <svg class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                  <p class="text-sm font-medium text-green-800">{{ successMessage }}</p>
                </div>
              </div>
            </transition>

            <transition name="fade">
              <div v-if="errorMessage" class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl flex items-start gap-3">
                <svg class="w-5 h-5 text-red-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                  <p class="text-sm font-medium text-red-800">{{ errorMessage }}</p>
                </div>
              </div>
            </transition>

            <!-- Personal Information Form -->
            <div v-if="activeTab==='personal'" class="space-y-6">
              <!-- Full Name -->
              <div>
                <label for="fullName" class="block text-sm font-semibold text-gray-700 mb-2">
                  Full Name <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                  </div>
                  <input 
                    id="fullName"
                    v-model="form.firstName" 
                    type="text" 
                    placeholder="Enter your full name"
                    :disabled="loading"
                    class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-transparent transition-all outline-none disabled:bg-gray-50 disabled:cursor-not-allowed"
                    required
                    aria-required="true"/>
                </div>
              </div>

              <!-- Contact Information Grid -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Phone -->
                <div>
                  <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                    Phone Number <span class="text-red-500">*</span>
                  </label>
                  <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                      <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                      </svg>
                    </div>
                    <input 
                      id="phone"
                      v-model="form.phone" 
                      type="tel" 
                      placeholder="+855 12 345 678"
                      :disabled="loading"
                      class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-transparent transition-all outline-none disabled:bg-gray-50 disabled:cursor-not-allowed"
                      required
                      aria-required="true"/>
                  </div>
                </div>

                <!-- Email -->
                <div>
                  <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                    Email Address <span class="text-red-500">*</span>
                  </label>
                  <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                      <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                      </svg>
                    </div>
                    <input 
                      id="email"
                      v-model="form.email" 
                      type="email" 
                      placeholder="you@example.com"
                      :disabled="loading"
                      class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-transparent transition-all outline-none disabled:bg-gray-50 disabled:cursor-not-allowed"
                      required
                      aria-required="true"/>
                  </div>
                </div>
              </div>

              <!-- Action Buttons -->
              <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-gray-200">
                <p class="text-sm text-gray-500 flex items-center gap-2">
                  <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                  </svg>
                  Your information is secure and encrypted
                </p>
                <button 
                  @click="saveProfile" 
                  :disabled="loading || !isFormValid"
                  class="w-full sm:w-auto px-8 py-3 bg-gradient-to-r from-pink-600 to-purple-600 text-white rounded-xl font-semibold hover:from-pink-700 hover:to-purple-700 transform hover:scale-105 transition-all shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none flex items-center justify-center gap-2">
                  <svg v-if="loading" class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                  </svg>
                  {{ loading ? 'Saving...' : 'Save Changes' }}
                </button>
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '../../axios/Axios' // centralized Axios instance

const router = useRouter()

// Tabs and loading state
const activeTab = ref('personal')
const loading = ref(false)

// Messages
const successMessage = ref('')
const errorMessage = ref('')

// Form data
const form = ref({
  firstName: '',
  lastName: '',
  phone: '',
  email: '',
  dob: '',
  status: 'active'
})

// User preferences
const preferences = ref({ 
  shop: 'women', 
  darkMode: false 
})

// Form validation
const isFormValid = computed(() => {
  return form.value.firstName.trim() !== '' &&
         form.value.email.trim() !== '' &&
         form.value.phone.trim() !== ''
})

// Clear messages after 5 seconds
const clearMessages = () => {
  setTimeout(() => {
    successMessage.value = ''
    errorMessage.value = ''
  }, 5000)
}

// ✅ Load user profile
const loadProfile = async () => {
  loading.value = true
  try {
    const token = sessionStorage.getItem("auth_token")
    if (!token) {
      errorMessage.value = "No token found. Please log in."
      clearMessages()
      return
    }

    const response = await api.get("/profile", {
      headers: { Authorization: `Bearer ${token}` }
    })

    if (response.data.success) {
      const user = response.data.data.user || response.data.data
      const nameParts = user.name ? user.name.split(' ') : ['', '']
      form.value = {
        firstName: nameParts[0] || '',
        lastName: nameParts[1] || '',
        phone: user.phone || '',
        email: user.email || '',
        dob: user.dob || '',
        status: user.status || 'active'
      }
    } else {
      throw new Error(response.data.message || 'Failed to fetch profile')
    }
  } catch (error) {
    console.error(error)
    errorMessage.value = "Failed to fetch profile. Please try again."
    clearMessages()
  } finally {
    loading.value = false
  }
}

// ✅ Save profile updates
const saveProfile = async () => {
  if (!isFormValid.value) return

  const payload = {
    name: `${form.value.firstName} ${form.value.lastName}`.trim(),
    email: form.value.email,
    phone: form.value.phone,
    dob: form.value.dob,
    status: form.value.status
  }

  loading.value = true
  errorMessage.value = ''
  successMessage.value = ''

  try {
    const token = sessionStorage.getItem("auth_token")
    const response = await api.put("/profile", payload, {
      headers: { Authorization: `Bearer ${token}` }
    })

    if (response.data.success) {
      successMessage.value = "Profile updated successfully!"
      clearMessages()
    } else {
      throw new Error(response.data.message || "Failed to update profile")
    }
  } catch (err) {
    console.error(err)
    errorMessage.value = err.message || "Failed to update profile. Please try again."
    clearMessages()
  } finally {
    loading.value = false
  }
}

// ✅ Tabs
const setTab = (tab) => activeTab.value = tab

// ✅ Preferences
const setShop = (val) => {
  preferences.value.shop = val
  sessionStorage.setItem('userPreferences', JSON.stringify(preferences.value))
}

const setDarkMode = (val) => {
  preferences.value.darkMode = val
  document.documentElement.classList.toggle('dark', preferences.value.darkMode)
  sessionStorage.setItem('userPreferences', JSON.stringify(preferences.value))
}

// ✅ Logout
const logout = () => {
  if (confirm('Are you sure you want to logout?')) {
    sessionStorage.clear()
    router.push('/login')
  }
}

// ✅ Navigate to address page
const goToAddress = () => router.push('/address')

// ✅ On component mount
onMounted(() => {
  loadProfile()

  // Load saved preferences
  const saved = sessionStorage.getItem('userPreferences')
  if (saved) {
    preferences.value = JSON.parse(saved)
    document.documentElement.classList.toggle('dark', preferences.value.darkMode)
  }
})
</script>


<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>