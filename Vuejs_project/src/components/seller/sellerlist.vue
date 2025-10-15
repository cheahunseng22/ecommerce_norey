<template>
  <div class="flex h-screen bg-pink-900">
    <!-- Sidebar -->
    <aside class="bg-pink-900 shadow-md transition-all duration-300 ease-in-out overflow-hidden"
      :class="showSide ? 'w-44' : 'w-12'">
      <!-- Sidebar Header -->
      <div class="px-1 py-1 border-b border- flex items-center">
        <div class="flex items-center transition-all duration-200"
          :class="showSide ? 'opacity-100' : 'opacity-0 md:opacity-100 justify-center w-full'">
          <img src="/public/images/photo_2025-07-05_09-23-34-removebg-preview.png">
        </div>
      </div>

      <!-- Navigation -->
     <nav class="flex-1 h-screen overflow-y-auto overflow-x-hidden px-4 py-8 bg-pink-900 scroll-smooth scrollbar-thin scrollbar-thumb-pink-500 scrollbar-track-pink-300">
  <ul class="space-y-2">
    <li>
      <router-link to="#" class="flex items-center w-full p-3 rounded-lg text-white transition-colors" :class="{ 'justify-center': !showSide }">
        <i class="fas fa-user-tie text-xl w-6 text-center"></i>
        <span v-if="showSide" class="flex-1 ml-3 font-bold text-left">Seller</span>
      </router-link>

      <ul class="ml-2 mt-1 space-y-1 pl-8 border-l-2 border-orange-400">
        <li>
          <router-link to="/admin/order/orderDetail"
            class="block py-2 text-white hover:text-orange-400 transition-colors"
            active-class="text-orange-600 font-medium">
            Manage Order
          </router-link>
        </li>
        <li>
          <router-link to="/admin/order/orderDetail"
            class="block py-2 text-white hover:text-orange-400 transition-colors"
            active-class="text-orange-600 font-medium">
            View Inventory
          </router-link>
        </li>
        <li>
          <router-link to="/admin/order/orderDetail"
            class="block py-2 text-white hover:text-orange-400 transition-colors"
            active-class="text-orange-600 font-medium">
            View Sale Report
          </router-link>
        </li>
      </ul>
    </li>
  </ul>
</nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- Top Navigation -->
      <header class="bg-pink-900 shadow-sm z-10">
        <div class="flex items-center justify-between h-16 px-4">
          <button @click="toggleSideBar"
            class="p-2 rounded-md text-black-700 hover:text-orange-900 hover:bg-pink-800 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z"/></svg>
          </button>
        
          <!-- User Controls -->
          <div class="flex items-center space-x-4">
            <div class="relative">
              <button @click="toggleMessage"
                class="p-2 rounded-full text-orange-600 hover:text-orange-800 hover:bg-pink-800 focus:outline-none relative">
                <i class="fas fa-bell text-white text-lg"></i>
                <span v-if="hasNotifications"
                  class="absolute top-0 right-0 h-2 w-2 rounded-full bg-red-500"></span>
              </button>
              <transition name="fade">
                <div v-if="showMessage"
                  class="absolute right-0 mt-2 w-72 bg-white rounded-md shadow-lg overflow-hidden z-20">
                  <div class="py-2 px-4 bg-pink-800 text-white text-sm font-medium">
                    Notifications
                  </div>
                  <div class="p-4 text-sm text-black">
                    Welcome back to your dashboard!
                  </div>
                </div>
              </transition>
            </div>
            <div class="relative">
              <button @click="toggleDrop" class="flex items-center space-x-2 focus:outline-none">
                <div class="h-8 w-8 rounded-full bg-pink-600 flex items-center justify-center text-orange-800">
                  <i class="fas fa-user text-white"></i>
                </div>
                <span v-if="userData?.name"
                  class="text-sm font-medium text-white hidden md:inline-block">
                  {{ userData.name }}
                </span>
              </button>
              <transition name="fade">
                <div v-show="showDropDown"
                  class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-20">
                  <div class="py-1">
                    <a he="#" class="block px-4 py-2 text-sm text-black hover:bg-pink-600">Account Settings</a>
                    <a href="#" class="block px-4 py-2 text-sm text-black hover:bg-pink-600">Support</a>
                    <button @click="logout"
                      class="block w-full text-left px-4 py-2 text-sm text-black hover:bg-pink-600">
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
        <!-- Sellers Page Content -->
        <div class="sellers-page">
          <div class="sellers-container">
            <header class="sellers-header">
              <h1 class="title">Sellers</h1>
              <div class="actions">
                <div class="search-wrapper">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="search-icon"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                  <input type="text" placeholder="Search Seller..." class="search-input" />
                </div>
                <button class="add-btn" @click="isModalOpen = true">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                  <span>Add New Seller</span>
                </button>
              </div>
            </header>

            <div class="table-wrapper">
              <table class="sellers-table">
                <thead>
                  <tr>
                    <th>Seller Name</th>
                    <th>Shop Name</th>
                    <th>Current Package</th>
                    <th>Current Balance</th>
                    <th>Shop Published</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="seller in sellers" :key="seller.id">
                    <td>
  <div class="seller-info">
    <img :src="seller.avatar" alt="Seller Avatar" class="avatar" />
    <div>
      <div class="seller-name">{{ seller.name }}</div>
      <div class="seller-phone">{{ seller.phone }}</div>
    </div>
  </div>
</td>
                    <td>{{ seller.shopName }}</td>
                    <td>{{ seller.package }}</td>
                    <td>${{ seller.balance.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}</td>
                    <td>
                      <label class="toggle-switch">
                        <input type="checkbox" :checked="seller.published" />
                        <span class="slider"></span>
                      </label>
                    </td>
                    <td>
                      <div class="action-icons">
                        <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <footer class="pagination-footer">
              <button class="pagination-btn"><</button>
              <button class="pagination-btn active">1</button>
              <button class="pagination-btn">></button>
            </footer>
          </div>

          <!-- Add New Seller Modal -->
          <div v-if="isModalOpen" class="modal-overlay" @click.self="closeModal">
            <div class="modal-content">
              <h2 class="modal-title">Create New Seller</h2>
            <form @submit.prevent="addSeller" enctype="multipart/form-data">
  <div class="form-group">
    <label for="image">Seller Image</label>
    <input 
      type="file" 
      id="image" 
      accept="image/*"
      @change="handleImageUpload"
    >
    <div v-if="imagePreview" class="image-preview">
      <img :src="imagePreview" alt="Preview">
    </div>
  </div>
  <div class="form-group">
    <label for="name">Seller Name</label>
    <input type="text" id="name" v-model="newSeller.name" required>
  </div>
  <div class="form-group">
    <label for="phone">Phone Number</label>
    <input type="tel" id="phone" v-model="newSeller.phone" placeholder="+855" required>
  </div>
  <div class="form-group">
    <label for="shopName">Shop Name</label>
    <input type="text" id="shopName" v-model="newSeller.shopName" required>
  </div>
  <div class="form-group">
    <label for="balance">Balance ($)</label>
    <input 
      type="number" 
      id="balance" 
      v-model.number="newSeller.balance" 
      min="0"
      step="0.10"
      placeholder="0.00"
      required
      @input="formatBalance"
    >
  </div>
  <div class="form-group">
    <label for="package">Package</label>
    <select id="package" v-model="newSeller.package">
      <option>Premium</option>
      <option>Gold</option>
      <option>Silver</option>
    </select>
  </div>
  <div class="modal-actions">
    <button type="button" class="btn-cancel" @click="closeModal">Cancel</button>
    <button type="submit" class="btn-submit">Add Seller</button>
  </div>
</form>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script>
import { useRouter } from "vue-router";

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
      isModalOpen: false,
      sellers: [
        { id: 1, name: 'Dominic Moss', phone: '+12345678910', shopName: 'Tech Tube', package: 'Premium', balance: 11345.25, published: true, avatar: 'https://i.pravatar.cc/150?img=1' },
        { id: 2, name: 'Ethan Booth', phone: '+12345678910', shopName: 'The Beauty', package: 'Premium', balance: 10350.25, published: true, avatar: 'https://i.pravatar.cc/150?img=2' },
        { id: 3, name: 'Finley Henry', phone: '+12345678910', shopName: 'Beauty Shop', package: 'Gold', balance: 7250.36, published: true, avatar: 'https://i.pravatar.cc/150?img=3' },
        { id: 4, name: 'Gabriel McKenzie', phone: '+12345678910', shopName: 'The Fashion', package: 'Gold', balance: 4370.55, published: true, avatar: 'https://i.pravatar.cc/150?img=4' },
        { id: 5, name: 'James Davey', phone: '+12345678910', shopName: 'Icon Boy', package: 'Premium', balance: 2458.15, published: true, avatar: 'https://i.pravatar.cc/150?img=5' },
        { id: 6, name: 'Lola Ryan', phone: '+12345678910', shopName: 'Febrick Fashion', package: 'Silver', balance: 8356.34, published: true, avatar: 'https://i.pravatar.cc/150?img=6' },
        { id: 7, name: 'Sofia Hall', phone: '+12343458910', shopName: 'Mobile Gadgets', package: 'Gold', balance: 12350.45, published: true, avatar: 'https://i.pravatar.cc/150?img=7' },
        { id: 8, name: 'Tily Thomson', phone: '+12345678910', shopName: 'Beyond Threads', package: 'Silver', balance: 9540.47, published: true, avatar: 'https://i.pravatar.cc/150?img=8' },
      ],
      newSeller: {
        name: '',
        phone: '',
        shopName: '',
        package: 'Silver',
      }
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
          "/logout", {},
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );
        sessionStorage.clear();
        this.router.push("/");
        alert("Logged out successfully!");
      } catch (error) {
        console.error("Logout failed:", error.response?.data || error.message);
        alert("Failed to logout. Please try again.");
      }
    },
    loadUserData() {
      const storedName = sessionStorage.getItem('name');
      const storedRole = sessionStorage.getItem('role');

      this.userData = {
        name: storedName,
        role: storedRole,
      };

      if (!this.userData.name) {
        console.log('No valid data found in localStorage');
      }
    },
    closeModal() {
      this.isModalOpen = false;
      this.newSeller = {
        name: '',
        phone: '',
        shopName: '',
        package: 'Silver',
      };
    },
    addSeller() {
      if (!this.newSeller.name || !this.newSeller.phone || !this.newSeller.shopName) {
        alert('Please fill out all required fields.');
        return;
      }
      
      const sellerToAdd = {
        id: Date.now(),
        name: this.newSeller.name,
        phone: this.newSeller.phone,
        shopName: this.newSeller.shopName,
        package: this.newSeller.package,
        balance: 0.00,
        published: false,
        avatar: `https://i.pravatar.cc/150?img=${Math.floor(Math.random() * 70)}`,
      };

      this.sellers.unshift(sellerToAdd);
      this.closeModal();
    }
  }
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

/* Sellers Page Styles */
.sellers-page {
  background-color: #f8fafc;
  padding: 2rem;
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
  color: #334155;
}

.sellers-container {
  background-color: #ffffff;
  border-radius: 0.75rem;
  box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
  overflow: hidden;
}

.sellers-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.5rem;
}

.title {
  font-size: 1.875rem;
  font-weight: 700;
  color: #1e293b;
}

.actions {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.search-wrapper {
  position: relative;
}

.search-icon {
  position: absolute;
  top: 50%;
  left: 0.75rem;
  transform: translateY(-50%);
  color: #94a3b8;
}

.search-input {
  border: 1px solid #e2e8f0;
  border-radius: 0.5rem;
  padding: 0.65rem 0.75rem 0.65rem 2.5rem;
  width: 250px;
  outline: none;
  transition: border-color 0.2s;
}

.search-input:focus {
  border-color: #3b82f6;
}

.add-btn {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  background-color: #3b82f6;
  color: white;
  font-weight: 600;
  padding: 0.65rem 1.25rem;
  border: none;
  border-radius: 0.5rem;
  cursor: pointer;
  transition: background-color 0.2s;
}

.add-btn:hover {
  background-color: #2563eb;
}

.table-wrapper {
  overflow-x: auto;
}

.sellers-table {
  width: 100%;
  border-collapse: collapse;
  text-align: left;
}

.sellers-table th, .sellers-table td {
  padding: 1rem 1.5rem;
  white-space: nowrap;
}

.sellers-table thead {
  background-color: #f8fafc;
  border-bottom: 1px solid #e2e8f0;
}

.sellers-table th {
  font-size: 0.875rem;
  font-weight: 600;
  color: #64748b;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.sellers-table tbody tr {
  border-bottom: 1px solid #f1f5f9;
}

.sellers-table tbody tr:last-child {
  border-bottom: none;
}

.seller-info {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  object-fit: cover;
}

.seller-name {
  font-weight: 600;
}

.seller-phone {
  font-size: 0.875rem;
  color: #64748b;
}

.action-icons {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.icon {
  color: #94a3b8;
  cursor: pointer;
  transition: color 0.2s;
}
.icon:hover {
  color: #3b82f6;
}

.toggle-switch {
  position: relative;
  display: inline-block;
  width: 44px;
  height: 24px;
}

.toggle-switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  transition: .4s;
  border-radius: 24px;
}

.slider:before {
  position: absolute;
  content: "";
  height: 18px;
  width: 18px;
  left: 3px;
  bottom: 3px;
  background-color: white;
  transition: .4s;
  border-radius: 50%;
}

input:checked + .slider {
  background-color: #3b82f6;
}

input:checked + .slider:before {
  transform: translateX(20px);
}

.pagination-footer {
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 1.5rem;
  gap: 0.5rem;
}
.pagination-btn {
  border: 1px solid #e2e8f0;
  background: white;
  border-radius: 0.5rem;
  width: 36px;
  height: 36px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  font-weight: 600;
}
.pagination-btn.active, .pagination-btn:hover {
  background-color: #3b82f6;
  color: white;
  border-color: #3b82f6;
}

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(15, 23, 42, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-content {
  background: white;
  padding: 2rem;
  border-radius: 0.75rem;
  width: 100%;
  max-width: 500px;
  box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
}

.modal-title {
  margin-top: 0;
  margin-bottom: 1.5rem;
  font-size: 1.25rem;
  font-weight: 600;
  color: #1e293b;
}

.form-group {
  margin-bottom: 1rem;
}

.form-group label {
  display: block;
  font-weight: 500;
  margin-bottom: 0.5rem;
  color: #334155;
}

.form-group input, .form-group select {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #cbd5e1;
  border-radius: 0.375rem;
  box-sizing: border-box;
}
.form-group input:focus, .form-group select:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 1px #3b82f6;
}

.modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 0.75rem;
  margin-top: 2rem;
}

.btn-cancel, .btn-submit {
  padding: 0.65rem 1.25rem;
  border: none;
  border-radius: 0.375rem;
  font-weight: 600;
  cursor: pointer;
  transition: background-color 0.2s, color 0.2s;
}

.btn-cancel {
  background-color: #f1f5f9;
  color: #475569;
  border: 1px solid #e2e8f0;
}
.btn-cancel:hover {
  background-color: #e2e8f0;
}

.btn-submit {
  background-color: #3b82f6;
  color: white;
}
.btn-submit:hover {
  background-color: #2563eb;
}
</style>