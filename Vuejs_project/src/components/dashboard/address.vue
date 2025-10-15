<template>
  <div
    class="font-kantumruy bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen py-8 md:py-12"
  >
    <div class="container mx-auto px-4 max-w-7xl">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8">
        <!-- Sidebar -->
        <aside class="lg:col-span-3">
          <div
            class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sticky top-6"
          >
            <h2 class="text-xl font-bold text-gray-900 mb-6">My Account</h2>
            <nav
              class="space-y-1"
              role="navigation"
              aria-label="Account navigation"
            >
              <!-- Personal Details Tab → Navigate to profile.vue -->
              <button
                @click="$router.push({ name: 'profile' })"
                class="w-full text-left px-4 py-3 rounded-xl font-medium transition-all duration-200 flex items-center gap-3 hover:bg-gray-50 text-gray-700"
              >
                <svg
                  class="w-5 h-5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                  />
                </svg>
                Personal Details
              </button>

              <!-- Address Tab → stays in-page -->
              <button
                @click="setTab('address')"
                :class="[
                  'w-full text-left px-4 py-3 rounded-xl font-medium transition-all duration-200 flex items-center gap-3',
                  activeTab === 'address'
                    ? 'bg-gradient-to-r from-pink-500 to-purple-500 text-white shadow-md'
                    : 'hover:bg-gray-50 text-gray-700',
                ]"
                :aria-current="activeTab === 'address' ? 'page' : undefined"
              >
                <svg
                  class="w-5 h-5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                  />
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                  />
                </svg>
                Address
              </button>
            </nav>

            <!-- Preferences -->
            <div class="mt-8 pt-6 border-t border-gray-200">
              <h3 class="text-sm font-semibold text-gray-700 mb-3">
                Shop Preference
              </h3>
              <div class="flex gap-2">
                <button
                  @click="setShop('women')"
                  :class="[
                    'flex-1 px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200',
                    preferences.shop === 'women'
                      ? 'bg-pink-600 text-white shadow-md'
                      : 'bg-gray-100 text-gray-700 hover:bg-gray-200',
                  ]"
                  :aria-pressed="preferences.shop === 'women'"
                >
                  Women
                </button>
                <button
                  @click="setShop('men')"
                  :class="[
                    'flex-1 px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200',
                    preferences.shop === 'men'
                      ? 'bg-pink-600 text-white shadow-md'
                      : 'bg-gray-100 text-gray-700 hover:bg-gray-200',
                  ]"
                  :aria-pressed="preferences.shop === 'men'"
                >
                  Men
                </button>
              </div>
            </div>

            <!-- Dark Mode -->
            <div class="mt-6 pt-6 border-t border-gray-200">
              <h3 class="text-sm font-semibold text-gray-700 mb-3">
                Appearance
              </h3>
              <div class="flex gap-2">
                <button
                  @click="setDarkMode(true)"
                  :class="[
                    'flex-1 px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200',
                    preferences.darkMode
                      ? 'bg-gray-900 text-white shadow-md'
                      : 'bg-gray-100 text-gray-700 hover:bg-gray-200',
                  ]"
                  :aria-pressed="preferences.darkMode"
                >
                  Dark
                </button>
                <button
                  @click="setDarkMode(false)"
                  :class="[
                    'flex-1 px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200',
                    !preferences.darkMode
                      ? 'bg-gray-900 text-white shadow-md'
                      : 'bg-gray-100 text-gray-700 hover:bg-gray-200',
                  ]"
                  :aria-pressed="!preferences.darkMode"
                >
                  Light
                </button>
              </div>
            </div>

            <!-- Logout -->
            <div class="mt-6 pt-6 border-t border-gray-200">
              <button
                @click="logout"
                class="w-full px-4 py-3 rounded-xl text-red-600 font-medium hover:bg-red-50 transition-all duration-200 flex items-center justify-center gap-2"
              >
                <svg
                  class="w-5 h-5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                  />
                </svg>
                Logout
              </button>
            </div>
          </div>
        </aside>

        <!-- Main Content -->
        <section class="lg:col-span-9">
          <div
            class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 md:p-8"
          >
            <!-- Header -->
            <div class="mb-8">
              <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">
                {{ activeTab === "address" ? "My Addresses" : "" }}
              </h1>
              <p class="text-gray-600">
                {{
                  activeTab === "address"
                    ? "Manage your delivery addresses"
                    : ""
                }}
              </p>
            </div>

            <!-- Success/Error Messages -->
            <transition name="fade">
              <div
                v-if="successMessage"
                class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl flex items-start gap-3"
              >
                <svg
                  class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                  />
                </svg>
                <div>
                  <p class="text-sm font-medium text-green-800">
                    {{ successMessage }}
                  </p>
                </div>
              </div>
            </transition>

            <transition name="fade">
              <div
                v-if="errorMessage"
                class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl flex items-start gap-3"
              >
                <svg
                  class="w-5 h-5 text-red-600 mt-0.5 flex-shrink-0"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                  />
                </svg>
                <div>
                  <p class="text-sm font-medium text-red-800">
                    {{ errorMessage }}
                  </p>
                </div>
              </div>
            </transition>

            <!-- Address Management -->
            <div v-if="activeTab === 'address'" class="space-y-6">
              <!-- Add New Address Button -->
              <div class="flex justify-end mb-4">
                <button
                  @click="openAddressModal('create')"
                  class="px-6 py-3 bg-gradient-to-r from-pink-600 to-purple-600 text-white rounded-xl font-semibold hover:from-pink-700 hover:to-purple-700 transform hover:scale-105 transition-all shadow-lg hover:shadow-xl flex items-center gap-2"
                >
                  <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M12 4v16m8-8H4"
                    />
                  </svg>
                  Add New Address
                </button>
              </div>

              <!-- Address List -->
              <div v-if="addresses.length === 0" class="text-center py-12">
                <svg
                  class="w-16 h-16 mx-auto text-gray-300 mb-4"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                  />
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                  />
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">
                  No addresses yet
                </h3>
                <p class="text-gray-600 mb-6">
                  Add your first delivery address to get started
                </p>
              </div>

              <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div
                  v-for="address in addresses"
                  :key="address.id"
                  class="bg-gradient-to-br from-white to-gray-50 rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-all duration-200"
                >
                  <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                      <div
                        class="w-10 h-10 bg-gradient-to-r from-pink-500 to-purple-500 rounded-lg flex items-center justify-center"
                      >
                        <svg
                          class="w-5 h-5 text-white"
                          fill="none"
                          stroke="currentColor"
                          viewBox="0 0 24 24"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                          />
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                          />
                        </svg>
                      </div>
                      <div>
                        <h3 class="font-semibold text-gray-900">
                          {{ address.name }}
                        </h3>
                        <span
                          v-if="address.isDefault"
                          class="inline-block mt-1 px-2 py-1 bg-pink-100 text-pink-700 text-xs font-medium rounded-full"
                        >
                          Default
                        </span>
                      </div>
                    </div>
                  </div>

                  <div class="space-y-2 mb-4">
                    <p class="text-gray-700 flex items-center gap-2">
                      <svg
                        class="w-5 h-5 text-gray-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                        />
                      </svg>
                      {{ address.city }}
                    </p>
                    <p class="text-gray-700 flex items-start gap-2">
                      <svg
                        class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                        />
                      </svg>
                      {{ address.country }}, {{ address.address_detail }}
                    </p>
                  </div>

                  <div class="flex gap-2 pt-4 border-t border-gray-200">
                    <button
                      @click="openAddressModal('edit', address)"
                      class="flex-1 px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-all duration-200 flex items-center justify-center gap-2"
                    >
                      <svg
                        class="w-4 h-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                        />
                      </svg>
                      Edit
                    </button>
                    <button
                      @click="deleteAddress(address.id)"
                      class="flex-1 px-4 py-2 bg-white border border-red-300 text-red-600 rounded-lg font-medium hover:bg-red-50 transition-all duration-200 flex items-center justify-center gap-2"
                    >
                      <svg
                        class="w-4 h-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                        />
                      </svg>
                      Delete
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>

    <!-- Address Modal -->
    <transition name="modal">
      <div
        v-if="showAddressModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
      >
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 md:p-8">
          <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">
              {{ modalMode === "create" ? "Add New Address" : "Edit Address" }}
            </h2>
            <button
              @click="closeAddressModal"
              class="text-gray-400 hover:text-gray-600 transition-colors"
            >
              <svg
                class="w-6 h-6"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M6 18L18 6M6 6l12 12"
                />
              </svg>
            </button>
          </div>

          <form @submit.prevent="saveAddress" class="space-y-4">
            <div>
              <label
                for="addressName"
                class="block text-sm font-semibold text-gray-700 mb-2"
              >
                User Name <span class="text-red-500">*</span>
              </label>
              <input
                type="text"
                id="addressName"
                v-model="addressForm.name"
                required
                class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500"
              />
            </div>
            <div>
              <label
                for="phone"
                class="block text-sm font-semibold text-gray-700 mb-2"
              >
                Phone Number <span class="text-red-500">*</span>
              </label>
              <input
                type="tel"
                id="phone"
                v-model="addressForm.phone"
                required
                class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500"
                placeholder="+855 12 345 678"
              />
            </div>
            <div>
              <label
                for="addressDetail"
                class="block text-sm font-semibold text-gray-700 mb-2"
              >
                Address <span class="text-red-500">*</span>
              </label>
              <input
                type="text"
                id="addressDetail"
                v-model="addressForm.address_detail"
                required
                class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500"
              />
            </div>
            <div>
              <label
                for="city"
                class="block text-sm font-semibold text-gray-700 mb-2"
              >
                City/Province <span class="text-red-500">*</span>
              </label>
              <select
                id="city"
                v-model="addressForm.city"
                required
                class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 bg-white"
              >
                <option value="" disabled>Select a city/province</option>
                <option value="Phnom Penh">Phnom Penh</option>
                <option value="Banteay Meanchey">Banteay Meanchey</option>
                <option value="Battambang">Battambang</option>
                <option value="Kampong Cham">Kampong Cham</option>
                <option value="Kampong Chhnang">Kampong Chhnang</option>
                <option value="Kampong Speu">Kampong Speu</option>
                <option value="Kampong Thom">Kampong Thom</option>
                <option value="Kampot">Kampot</option>
                <option value="Kandal">Kandal</option>
                <option value="Kep">Kep</option>
                <option value="Koh Kong">Koh Kong</option>
                <option value="Kratié">Kratié</option>
                <option value="Mondulkiri">Mondulkiri</option>
                <option value="Oddar Meanchey">Oddar Meanchey</option>
                <option value="Pailin">Pailin</option>
                <option value="Preah Vihear">Preah Vihear</option>
                <option value="Prey Veng">Prey Veng</option>
                <option value="Pursat">Pursat</option>
                <option value="Ratanakiri">Ratanakiri</option>
                <option value="Siem Reap">Siem Reap</option>
                <option value="Sihanoukville">Sihanoukville</option>
                <option value="Stung Treng">Stung Treng</option>
                <option value="Svay Rieng">Svay Rieng</option>
                <option value="Takéo">Takéo</option>
                <option value="Tboung Khmum">Tboung Khmum</option>
              </select>
            </div>
            <div>
              <label
                for="country"
                class="block text-sm font-semibold text-gray-700 mb-2"
              >
                Country <span class="text-red-500">*</span>
              </label>
              <select
                id="country"
                v-model="addressForm.country"
                required
                class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 bg-white"
              >
                <option value="Cambodia">Cambodia</option>
              </select>
            </div>

            <div class="flex justify-end gap-2 pt-4">
              <button
                type="button"
                @click="closeAddressModal"
                class="px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-50 transition-all"
              >
                Cancel
              </button>
              <button
                type="submit"
                class="px-6 py-2 bg-pink-600 hover:bg-pink-700 text-white rounded-lg font-semibold transition-all"
              >
                Save
              </button>
            </div>
          </form>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import api from "../../axios/Axios";

export default {
  data() {
    return {
      activeTab: "address",
      preferences: {
        shop: "women",
        darkMode: false,
      },
      addresses: [],
      showAddressModal: false,
      modalMode: "create",
      addressForm: {
        id: null,
        name: "",
        phone: "",
        address_detail: "",
        city: "",
        country: "Cambodia",
      },

      successMessage: "",
      errorMessage: "",
      loading: false,
    };
  },

  mounted() {
    this.fetchAddresses();
  },

  methods: {
    // ✅ Clear messages
    clearMessages() {
      setTimeout(() => {
        this.successMessage = "";
        this.errorMessage = "";
      }, 3000);
    },

    // ✅ Get all addresses
    async fetchAddresses() {
      const token = sessionStorage.getItem("auth_token");
      this.errorMessage = "";
      this.successMessage = "";
      this.loading = true;

      try {
        const res = await api.get("/address", {
          headers: { Authorization: `Bearer ${token}` },
        });

        const data = res.data;

        this.addresses = Array.isArray(data) ? data : data.data || [];
        this.successMessage = "Addresses loaded successfully!";
        this.clearMessages();
      } catch (err) {
        console.error(err);
        this.errorMessage =
          err.response?.data?.message ||
          "Failed to load addresses. Please try again.";
        this.clearMessages();
      } finally {
        this.loading = false;
      }
    },

    // ✅ Open modal
  openAddressModal(address = null) {
  if (address) {
    this.modalMode = 'edit';
    this.addressForm = { ...address }; // populate form with existing address
  } else {
    this.modalMode = 'create';
    this.addressForm = {
      id: null,
      name: '',
      phone: '',
      address_detail: '',
      city: '',
      country: 'Cambodia',
    };
  }
  this.showAddressModal = true;
},


    // ✅ Close modal
    closeAddressModal() {
      this.showAddressModal = false;
    },

    // ✅ Save or update address
  async saveAddress() {
  const token = sessionStorage.getItem("auth_token");
  const customer = JSON.parse(sessionStorage.getItem("customer_data"));
  const customerId = customer?.id;

  if (!customerId) {
    this.errorMessage = "Customer ID not found. Please log in again.";
    this.clearMessages();
    return;
  }

  try {
    let url = '';
    let method = '';

    if (this.modalMode === 'create') {
      url = `/api/public/address/customer/${customerId}`;
      method = 'post';
    } else {
      // For update, assuming your Laravel route is PUT /api/address/{id}
      url = `/api/address/${this.addressForm.id}`;
      method = 'put';
    }

    const res = await api({
      method,
      url,
      headers: { Authorization: `Bearer ${token}` },
      data: this.addressForm,
    });

    if (res.data.success) {
      this.successMessage = res.data.message;
      this.fetchAddresses(); // refresh list
      this.closeAddressModal();
      this.clearMessages();
    } else {
      throw new Error(res.data.message || "Failed to save address.");
    }
  } catch (error) {
    console.error(error);
    this.errorMessage =
      error.response?.data?.message || error.message || "Failed to save address.";
    this.clearMessages();
  }
},

    // ✅ Delete address
    async deleteAddress(id) {
      if (!confirm("Are you sure you want to delete this address?")) return;

      const token = sessionStorage.getItem("auth_token");
      try {
        const res = await api.delete(`/address/${id}`, {
          headers: { Authorization: `Bearer ${token}` },
        });

        if (res.data.success) {
          this.successMessage = res.data.message;
          this.fetchAddresses();
          this.clearMessages();
        } else {
          throw new Error(res.data.message || "Failed to delete address.");
        }
      } catch (error) {
        console.error(error);
        this.errorMessage = error.message || "Failed to delete address.";
        this.clearMessages();
      }
    },
  },
};
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.25s ease;
}
.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}
</style>
