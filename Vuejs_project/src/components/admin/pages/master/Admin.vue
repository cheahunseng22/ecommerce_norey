<template>
  <div class="flex h-screen bg-pink-900">
    <!-- Sidebar -->
    <aside
      class="bg-pink-900 shadow-md transition-all duration-300 ease-in-out overflow-hidden"
      :class="showSide ? 'w-44' : 'w-12'"
    >
      <!-- Sidebar Header -->
     

      <!-- Navigation -->

      <nav
      
        class="flex-1 h-screen overflow-y-auto overflow-x-hidden px-4 py-8 bg-pink-900 scroll-smooth scrollbar-thin scrollbar-thumb-pink-500 scrollbar-track-pink-300"
      >
       <div
  class="flex items-center justify-center mb-10 transition-all duration-500 ease-in-out"
  :class="showSide ? 'opacity-100' : 'opacity-50 md:opacity-100'"
>
  <img
    src="/images/photo_2025-07-05_09-23-34-removebg-preview.png"
    alt="Admin logo"
    class="h-16 w-16 md:h-24 md:w-24 lg:h-32 lg:w-32 object-contain"
  />
</div>

        <ul class="space-y-2">
          <!-- Dashboard -->
          <li>
            <router-link
              to="/admin/home"
              class="flex items-center p-3 rounded-lg text-white hover:text-white transition-colors"
              :class="{ 'justify-center': !showSide }"
              active-class=" text-white"
            >
              <i class="fas fa-tv text-xl w-6 text-center"></i>
              <span v-if="showSide" class="ml-3 font-bold">Dashboard</span>
            </router-link>
          </li>

          <!-- Products Dropdown -->
          <li>
            <router-link
              to="/admin/product/list"
              class="flex items-center p-3 rounded-lg transition-colors text-white hover:bg-pink-800"
              :class="{ 'justify-center': !showSide }"
              active-class="bg-orange-300 text-white"
            >
              <i class="fas fa-user text-xl w-6 text-center"></i>
              <span v-if="showSide" class="ml-3 font-bold">Product</span>
            </router-link>
          </li>


             <!-- brand -->
             <li>
            <router-link
              to="/admin/brand"
              class="flex items-center p-3 rounded-lg transition-colors text-white hover:bg-pink-800"
              :class="{ 'justify-center': !showSide }"
              active-class="bg-orange-300 text-white"
            >
              <i class="fas fa-user text-xl w-6 text-center"></i>
              <span v-if="showSide" class="ml-3 font-bold">Brand</span>
            </router-link>
          </li>
           <!-- supplier-->
           <li>
            <router-link
              to="/admin/supplier"
              class="flex items-center p-3 rounded-lg transition-colors text-white hover:bg-pink-800"
              :class="{ 'justify-center': !showSide }"
              active-class="bg-orange-300 text-white"
            >
              <i class="fas fa-user text-xl w-6 text-center"></i>
              <span v-if="showSide" class="ml-3 font-bold">Supplier</span>
            </router-link>
          </li>
          
          
          <!-- <li>
            <button @click="toggleProductsDropdown"
              class="flex items-center w-full p-3 rounded-lg text-white  hover:text-white transition-colors"
              :class="{ 'justify-center': !showSide }">
              <i class="fas fa-box text-2xl w-6 text-center"></i>
              <span v-if="showSide" class="flex-1 ml-3 font-bold text-left">Products</span>
              <span v-if="showSide" class="transform transition-transform duration-200"
                :class="{ 'rotate-90': productsDropdownOpen }">
                <i class="fas fa-chevron-right text-xs"></i>
              </span>
            </button>

            <transition name="slide">
              <ul v-if="productsDropdownOpen && showSide"
                class="ml-2 mt-1 space-y-1 pl-8 border-l-2 border-orange-400">
                <li>
                  <router-link to="/admin/products/list"
                    class="block py-2 text-white  transition-colors"
                    active-class="text-orange-600 font-medium">
                    List
                  </router-link>
                </li>
                <li>
                  <router-link to="/admin/products/create"
                    class="block py-2 text-white  transition-colors"
                    active-class="text-orange-600 font-medium">
                    Create
                  </router-link>
                </li>
                <li>
                  <router-link to="/admin/products/edit"
                    class="block py-2 text-white  transition-colors"
                    active-class="text-orange-600 font-medium">
                    Edit
                  </router-link>
                </li>
              </ul>
            </transition>
          </li> -->

          <!-- Orders Dropdown -->
          <li>
            <button
              @click="toggleOrderDropdown"
              class="flex items-center w-full p-3 rounded-lg text-white transition-colors"
              :class="{ 'justify-center': !showSide }"
            >
              <i class="fas fa-list text-xl w-6 text-center"></i>
              <span v-if="showSide" class="flex-1 ml-3 font-bold text-left"
                >Orders</span
              >
              <span
                v-if="showSide"
                class="transform transition-transform duration-200"
                :class="{ 'rotate-90': orderDropdownOpen }"
              >
                <i class="fas fa-chevron-right text-xs"></i>
              </span>
            </button>

            <transition name="slide">
              <ul
                v-if="orderDropdownOpen && showSide"
                class="ml-2 mt-1 space-y-1 pl-8 border-l-2 border-orange-400"
              >
                <li>
                  <router-link
                    to="/admin/order/orderList"
                    class="block py-2 text-white transition-colors"
                    active-class="text-orange-600 font-medium"
                  >
                    List
                  </router-link>
                </li>
                <li>
                  <router-link
                    to="/admin/order/orderDetail"
                    class="block py-2 text-white hove transition-colors"
                    active-class="text-orange-600 font-medium"
                  >
                    Detail
                  </router-link>
                </li>
                <li>
                  <router-link
                    to="/admin/order/Invoice"
                    class="block py-2 text-white hove transition-colors"
                    active-class="text-orange-600 font-medium"
                  >
                    Invoice
                  </router-link>
                </li>
              </ul>
            </transition>
          </li>

          <!-- Other Menu Items (Category, Inventory, Users, Settings) -->
          <li>
            <router-link
              to="/admin/categorys/categorylist"
              class="flex items-center p-3 rounded-lg transition-colors text-white hover:bg-pink-800"
              :class="{ 'justify-center': !showSide }"
              active-class="bg-orange-300 text-white"
            >
              <i class="fas fa-tags text-xl w-6 text-center"></i>
              <span v-if="showSide" class="ml-3 font-bold">Category</span>
            </router-link>
          </li>
          <li>
            <router-link
              to="/admin/parent/parentcategorylist"
              class="flex items-center p-3 rounded-lg transition-colors text-white hover:bg-pink-800"
              :class="{ 'justify-center': !showSide }"
              active-class="bg-orange-300 text-white"
            >
              <i class="fas fa-sitemap text-xl w-6 text-center"></i>
              <span v-if="showSide" class="ml-3 font-bold">Parent Category</span>
            </router-link>
          </li>
          <!-- customer -->
          <li>
            <router-link
              to="/admin/customer/CustomerList"
              class="flex items-center p-3 rounded-lg transition-colors text-white hover:bg-pink-800"
              :class="{ 'justify-center': !showSide }"
              active-class="bg-orange-300 text-white"
            >
              <i class="fas fa-user text-xl w-6 text-center"></i>
              <span v-if="showSide" class="ml-3 font-bold">Customer</span>
            </router-link>
          </li>
          <!-- ACCOUNT -->
          <li>
            <router-link
              to="/admin/user/Uselist"
              class="flex items-center p-3 rounded-lg transition-colors text-white hover:bg-pink-800"
              :class="{ 'justify-center': !showSide }"
              active-class="bg-orange-300 text-white"
            >
              <i class="fas fa-user text-xl w-6 text-center"></i>
              <span v-if="showSide" class="ml-3 font-bold">Account</span>
            </router-link>
          </li>
          <!-- sale&report-->
          <li>
            <router-link
              to="/admin/salereport"
              class="flex items-center p-3 rounded-lg transition-colors text-white hover:bg-pink-800"
              :class="{ 'justify-center': !showSide }"
              active-class="bg-orange-300 text-white"
            >
              <i class="fas fa-tag text-xl w-6 text-center"></i>
              <span v-if="showSide" class="ml-3 font-bold">Sale & Report</span>
            </router-link>
          </li>
          <!-- calender -->
           <li>
            <router-link
              to="/admin/inventory"
              class="flex items-center p-3 rounded-lg transition-colors text-white hover:bg-pink-800"
              :class="{ 'justify-center': !showSide }"
              active-class="bg-orange-300 text-white"
            >
             <i class="fas fa-boxes text-xl w-6 text-center"></i>

              <span v-if="showSide" class="ml-3 font-bold">Inventory</span>
            </router-link>
          </li>
          <!-- <li>
            <router-link
              to="/admin/calendar"
              class="flex items-center p-3 rounded-lg transition-colors text-white hover:bg-pink-800"
              :class="{ 'justify-center': !showSide }"
              active-class="bg-orange-300 text-white"
            >
              <i class="fas fa-calendar-alt text-xl w-6 text-center"></i>
              <span v-if="showSide" class="ml-3 font-bold">Calendar</span>
            </router-link>
          </li> -->
         

          <li>
            <router-link
              to="/admin/settings"
              class="flex items-center p-3 rounded-lg text-white transition-colors"
              :class="{ 'justify-center': !showSide }"
              active-class="bg-orange-300 text-white"
            >
              <i class="fas fa-cog text-xl w-6 text-center"></i>
              <span v-if="showSide" class="ml-3 font-bold">Settings</span>
            </router-link>
          </li>
        </ul>
      </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- Top Navigation -->
      <header class="bg-pink-900 shadow-sm z-10">
        <div class="flex items-center justify-between h-16 px-4">
          <button
            @click="toggleSideBar"
            class="p-2 rounded-md text-gray-700 hover:text-orange-900 hover:bg-pink-800 focus:outline-none"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              height="24px"
              viewBox="0 -960 960 960"
              width="24px"
              fill="#FFFFFF"
            >
              <path
                d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z"
              />
            </svg>
            <!-- <svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="40px" fill="#FFFFFF"><path d="M120-240v-66.67h720V-240H120Zm0-206.67v-66.66h720v66.66H120Zm0-206.66V-720h720v66.67H120Z"/></svg> -->
          </button>

          <!-- User Controls -->
          <div class="flex items-center space-x-4">
            <div class="relative">
              <button
                @click="toggleMessage"
                class="p-2 rounded-full text-orange-600 hover:text-orange-800 hover:bg-pink-800 focus:outline-none relative"
              >
                <i class="fas fa-bell text-white text-lg"></i>

                <span
                  v-if="hasNotifications"
                  class="absolute top-0 right-0 h-2 w-2 rounded-full bg-red-500"
                ></span>
              </button>
              <transition name="fade">
                <div
                  v-if="showMessage"
                  class="absolute right-0 mt-2 w-72 bg-white rounded-md shadow-lg overflow-hidden z-20"
                >
                  <div
                    class="py-2 px-4 bg-pink-800 text-white text-sm font-medium"
                  >
                    Notifications
                  </div>
                  <div class="p-4 text-sm text-black">
                    Welcome back to your dashboard!
                  </div>
                </div>
              </transition>
            </div>
            <div class="relative">
              <button
                @click="toggleDrop"
                class="flex items-center space-x-2 focus:outline-none"
              >
                <div
                  class="h-8 w-8 rounded-full bg-pink-600 flex items-center justify-center text-orange-800"
                >
                  <i class="fas fa-user text-white"></i>
                </div>
                <span
                  v-if="userData?.name"
                  class="text-sm font-medium text-white hidden md:inline-block"
                >
                  {{ userData.name }}
                </span>
              </button>
              <transition name="fade">
                <div
                  v-show="showDropDown"
                  class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-20"
                >
                  <div class="py-1">
                    <a
                      href="#"
                      class="block px-4 py-2 text-sm text-black hover:bg-pink-600"
                      >Account Settings</a
                    >
                    <a
                      href="#"
                      class="block px-4 py-2 text-sm text-black hover:bg-pink-600"
                      >Support</a
                    >
                    <button
                      @click="logout"
                      class="block w-full text-left px-4 py-2 text-sm text-black hover:bg-pink-600"
                    >
                      Sign out
                    </button>
                  </div>
                </div>
              </transition>
            </div>
          </div>
        </div>
      </header>

      <!-- Main Content Area -->
      <main class="flex-1 overflow-y-auto p-4 bg-orange-50">
        <router-view></router-view>
      </main>
    </div>
  </div>
</template>

<script>
import { useRouter } from "vue-router";
import api from "../../../../axios/Axios";

export default {
  data() {
    return {
      showDropDown: false,
      showSide: true,
      showMessage: false,
      userData: null,
      productsDropdownOpen: false,
      orderDropdownOpen: false,
      sellerDropdownOpen: false,
      categoryDropdownOpen: false,
      hasNotifications: true,
    };
  },
  setup() {
    const router = useRouter();
    return { router };
  },
  mounted() {
    this.loadUserData();
  },
  methods: {
    toggleMessage() {
      this.showMessage = !this.showMessage;
    },
    toggleProductsDropdown() {
      this.productsDropdownOpen = !this.productsDropdownOpen;
    },
    toggleOrderDropdown() {
      this.orderDropdownOpen = !this.orderDropdownOpen;
    },
    toggleSellerDropdown() {
      this.sellerDropdownOpen = !this.sellerDropdownOpen;
    },
    toggleCategoryDropdown() {
      this.categoryDropdownOpen = !this.categoryDropdownOpen;
    },
    toggleSideBar() {
      this.showSide = !this.showSide;
    },
    toggleDrop() {
      this.showDropDown = !this.showDropDown;
    },
    async logout() {
      try {
        const token = sessionStorage.getItem("auth_token");
        if (!token) {
          alert("No token found. Please log in.");
          return;
        }
        await api.post(
          "/logout",
          {},
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );
        localStorageStorage.clear();
        this.router.push("/");
        alert("Logged out successfully!");
      } catch (error) {
        console.error("Logout failed:", error.response?.data || error.message);
        alert("Failed to logout. Please try again.");
      }
    },
    loadUserData() {
      const storedName = sessionStorage.getItem("name");
      const storedRole = sessionStorage.getItem("role");

      this.userData = {
        name: storedName,
        role: storedRole,
      };

      if (!this.userData.name) {
        console.log("No valid data found in localStorage");
      }
    },
  },
};
</script>

<style scoped>
@import "font-awesome/css/font-awesome.min.css";

/* Transition effects */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s;
}

.fade-enter,
.fade-leave-to {
  opacity: 0;
}

.slide-enter-active,
.slide-leave-active {
  transition: all 0.3s ease;
}

.slide-enter,
.slide-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}

/* Custom scrollbar for sidebar */
nav::-webkit-scrollbar {
  width: 4px;
}

nav::-webkit-scrollbar-track {
  background: #f1f1f1;
}

nav::-webkit-scrollbar-thumb {
  background: #ddd;
  border-radius: 4px;
}

nav::-webkit-scrollbar-thumb:hover {
  background: #ccc;
}
</style>
