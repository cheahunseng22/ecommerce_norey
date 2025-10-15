<template>
  <div class="font-sans bg-gray-100 p-5 text-gray-800 min-h-screen relative">
    <!-- Success Notification -->
    <div
      v-if="notification.show"
      :class="[
        'fixed top-5 right-5 z-50 p-4 rounded-md shadow-lg text-sm transition-transform duration-300',
        notification.type === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
      ]"
    >
      {{ notification.message }}
    </div>

    <!-- Header Section -->
    <header class="flex justify-between items-center mb-6">
      <div>
        <h1 class="text-3xl font-semibold mb-1 text-gray-900">User List</h1>
        <nav class="flex items-center text-sm text-gray-600">
          <router-link to="/admin/home" class="hover:underline">Dashboard</router-link>
          <span class="before:content-['•'] before:mx-2 before:text-gray-400"></span>
          <router-link to="/admin/user/Uselist" class="hover:underline">User</router-link>
          <span class="before:content-['•'] before:mx-2 before:text-gray-400"></span>
          <span>List</span>
        </nav>
      </div>
      <router-link to="/admin/user/Usercreate"
        class="bg-gray-800 text-white px-6 py-3 rounded-md shadow-md hover:bg-gray-700 transition font-semibold flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>
        Create User
      </router-link>
    </header>

    <!-- Tabs for User Status -->
    <div class="bg-white rounded-lg p-4 mb-5 shadow-sm">
      <div class="flex space-x-4 border-b border-gray-200 mb-4">
        <button
          v-for="tab in statusTabs"
          :key="tab.value"
          @click="selectStatusTab(tab.value)"
          :class="[
            'pb-3 px-2 text-sm font-medium transition-colors duration-200',
            selectedTab === tab.value
              ? 'text-blue-600 border-b-2 border-blue-600'
              : 'text-gray-600 hover:text-gray-900 hover:border-b-2 hover:border-gray-300'
          ]"
        >
          {{ tab.label }} <span v-if="tab.count !== undefined" class="ml-1 px-2 py-0.5 text-xs font-semibold rounded-full" :class="getTabCountClass(tab.value)">{{ tab.count }}</span>
        </button>
      </div>

      <!-- Filters and Actions Section -->
      <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="flex flex-wrap items-center gap-4 w-full md:w-auto">
          <!-- Role Filter Dropdown -->
          <div class="relative w-full md:w-auto min-w-[150px]" ref="roleDropdownRef">
            <button
              @click="toggleRoleDropdown"
              class="flex items-center justify-between w-full bg-white border border-gray-300 text-gray-700 py-2 px-4 rounded-md leading-tight focus:outline-none focus:bg-white focus:border-blue-500 text-sm appearance-none cursor-pointer"
              :class="{'border-blue-500 ring ring-blue-200': isRoleDropdownOpen}"
            >
              <span>{{ selectedRoleFilter || 'Role' }}</span>
              <svg class="fill-current h-4 w-4 transform transition-transform duration-200"
                   :class="{'rotate-180': isRoleDropdownOpen}"
                   xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
              </svg>
            </button>
            <div
              v-if="isRoleDropdownOpen"
              class="absolute top-full left-0 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg z-20 py-2 max-h-48 overflow-y-auto"
            >
              <button type="button" @click="selectRoleFilter('Admin')" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700">Admin</button>
              <button type="button" @click="selectRoleFilter('User')" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700">User</button>
              <button type="button" @click="selectRoleFilter('Seller')" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700">Seller</button>
            </div>
          </div>

          <!-- Search Bar -->
          <div class="relative flex-grow md:flex-grow-0 w-full md:w-auto">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
              </svg>
            </div>
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Search..."
              class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
            />
          </div>
        </div>

        <div class="flex gap-4">
          <!-- More Actions Dropdown (Example) -->
          <div class="relative">
            <button
              @click="toggleMoreActionsDropdown"
              class="flex items-center gap-1.5 p-2 rounded-md hover:bg-gray-100 hover:text-gray-900 transition-colors duration-200 text-gray-600 text-sm cursor-pointer"
            >
              <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v.01M12 12v.01M12 18v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 6a1 1 0 110-2 1 1 0 010 2zm0 6a1 1 0 110-2 1 1 0 010 2z" />
              </svg>
            </button>
            <div
              v-if="showMoreActionsDropdown"
              class="absolute top-full right-0 mt-1 w-40 bg-white border border-gray-300 rounded-lg shadow-lg z-30 py-2"
            >
              <button @click="handleExport" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Export</button>
              <button @click="handleBulkDelete" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Bulk Delete</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              <input type="checkbox" class="form-checkbox h-4 w-4 text-blue-600 rounded">
            </th>
            <th
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
              
            >
              <div class="flex items-center">
                Name
                <span v-if="sortBy === 'name'" class="ml-1">
                  {{ sortDirection === 'asc' ? '▲' : '▼' }}
                </span>
              </div>
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Phone number
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Role
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Status
            </th>
            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
              Actions
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <template v-if="paginatedUsers.length > 0">
            <tr v-for="user in paginatedUsers" :key="user.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap">
                <input type="checkbox" class="form-checkbox h-4 w-4 text-blue-600 rounded">
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <img :src="user.avatarUrl || 'https://placehold.co/40x40'" :alt="user.name" class="w-10 h-10 rounded-full object-cover mr-3">
                  <div>
                    <div class="font-medium text-gray-900">{{ user.name }}</div>
                    <div class="text-sm text-gray-600">{{ user.email }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                {{ user.phone }}
              </td>
               <td class="px-6 py-4 whitespace-nowrap">
                <span :class="['px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full', getRoleClass(user.type)]">
                  {{ user.type }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="['px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full', getUserStatusClass(user.status)]">
                  {{ user.status }}
                </span>
              </td>
              <td class=" py-4 flex justify-center gap-2">
                <button
                  @click="viewSupplier(supplier)"
                  class="text-orange-600 hover:text-orange-800"
                ><i class="fas fa-eye mr-1"></i>
                  View
                </button>
                <button
                  @click="editSupplier(supplier)"
                  
                 class="text-blue-600 hover:text-blue-800 mr-4 transition duration-150 ease-in-out transform hover:scale-105"
                ><i class="fas fa-edit mr-1"></i>
                  Edit
                </button>
                <button
                  @click="deleteSupplier(supplier.id)"
                  class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                  <i class="fas fa-trash mr-1"></i>
                
                  Delete
                </button>
              </td>
            </tr>
          </template>
          <tr v-else>
            <td :colspan="6" class="text-center py-8 text-gray-500">
              No users found.
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- User Action Dropdown -->
    <div v-if="showUserActionsDropdown" class="column-menu-container absolute bg-white border border-gray-300 rounded-lg shadow-lg z-40 py-2 w-36" :style="userActionMenuPosition">
      <button @click="handleViewUser(activeUserId)" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">View</button>
      <button @click="handleDeleteUser(activeUserId)" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Delete</button>
    </div>

    <!-- Pagination -->
    <div class="mt-5 flex justify-between items-center text-sm text-gray-600">
        <div class="flex items-center space-x-4">
            <label class="flex items-center cursor-pointer">
                <input type="checkbox" v-model="isDense" class="form-checkbox h-4 w-4 text-blue-600 rounded">
                <span class="ml-2">Dense</span>
            </label>
            <div>Rows per page:</div>
            <select v-model="rowsPerPage" class="border border-gray-300 rounded-md p-1 text-sm">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="20">20</option>
            </select>
        </div>
        <div class="flex items-center space-x-2">
            <span>{{ currentRangeText }}</span>
            <button @click="prevPage" :disabled="currentPage === 1" class="p-2 rounded-md hover:bg-gray-200 disabled:opacity-50"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg></button>
            <button @click="nextPage" :disabled="currentPage >= totalPages" class="p-2 rounded-md hover:bg-gray-200 disabled:opacity-50"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg></button>
        </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router'; // Import useRoute
import api from '../../../../../axios/Axios';

const router = useRouter();
const route = useRoute(); // Get current route information

// --- Notification State ---
const notification = ref({
  show: false,
  message: '',
  type: 'success', // 'success' or 'error'
});

const showNotification = (message, type = 'success') => {
  notification.value = { show: true, message, type };
  setTimeout(() => {
    notification.value.show = false;
  }, 4000); // Hide after 4 seconds
};

// --- User Data State ---
const users = ref([]);

// --- Function to Fetch Users ---
const fetchUsers = async () => {
    try {
        const token = sessionStorage.getItem("auth_token");
        if (!token) {
            showNotification("No token found. Please log in.", "error");
            return;
        }
        const response = await api.get("/users", {
            headers: { Authorization: `Bearer ${token}` },
        });
        users.value = response.data.data;
        updateTabCounts();
    } catch (error) {
        console.error("Failed to fetch users:", error.response?.data || error.message);
        showNotification("Failed to fetch users.", "error");
        users.value = [];
    }
};

// --- Filter & Search State ---
const selectedRoleFilter = ref(null);
const searchQuery = ref('');

// --- Tabs for User Status ---
const statusTabs = ref([
  { label: 'All', value: 'All', count: 0 },
  { label: 'Active', value: 'Active', count: 0 },
  { label: 'Pending', value: 'Pending', count: 0 },
  { label: 'Banned', value: 'Banned', count: 0 },
  { label: 'Rejected', value: 'Rejected', count: 0 },
]);
const selectedTab = ref('All');

const getRoleClass = (role) => {
  switch (role?.toLowerCase()) {
    case 'admin': return 'bg-purple-200 text-purple-800';
    case 'seller': return 'bg-blue-200 text-blue-800';
    case 'user': return 'bg-orange-200 text-orange-800';
    default: return 'bg-gray-200 text-gray-800';
  }
};

// --- Dropdown visibility states ---
const isRoleDropdownOpen = ref(false);
const showMoreActionsDropdown = ref(false);
const showUserActionsDropdown = ref(false);

// --- Refs for click outside ---
const roleDropdownRef = ref(null);
const activeUserId = ref(null);
const userActionMenuPosition = ref({ top: '0px', left: '0px' });

// --- Toggle functions for dropdowns ---
const toggleRoleDropdown = () => isRoleDropdownOpen.value = !isRoleDropdownOpen.value;
const selectRoleFilter = (role) => {
  selectedRoleFilter.value = role;
  isRoleDropdownOpen.value = false;
  currentPage.value = 1;
};
const toggleMoreActionsDropdown = () => showMoreActionsDropdown.value = !showMoreActionsDropdown.value;

const toggleUserActionsDropdown = (event, userId) => {
  if (activeUserId.value === userId && showUserActionsDropdown.value) {
    showUserActionsDropdown.value = false;
    activeUserId.value = null;
  } else {
    activeUserId.value = userId;
    const rect = event.target.closest('button').getBoundingClientRect();
    userActionMenuPosition.value = {
      top: `${rect.bottom + window.scrollY + 5}px`,
      left: `${rect.left + window.scrollX - 120}px`,
    };
    showUserActionsDropdown.value = true;
  }
};

const closeUserActionsDropdown = () => {
    showUserActionsDropdown.value = false;
    activeUserId.value = null;
};

// --- Click outside handler ---
const handleClickOutside = (event) => {
  if (isRoleDropdownOpen.value && roleDropdownRef.value && !roleDropdownRef.value.contains(event.target)) {
    isRoleDropdownOpen.value = false;
  }
  if (showUserActionsDropdown.value && !event.target.closest('.column-menu-container') && !event.target.closest('.column-menu-button')) {
      closeUserActionsDropdown();
  }
};

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
  fetchUsers();
  
  // Check for success query parameter from creation page
  if (route.query.created === 'true') {
    showNotification('User created successfully!');
    // Clean the URL to remove the query parameter
    router.replace({ query: {} });
  }
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});

// --- Tab Selection Logic ---
const selectStatusTab = (status) => {
  selectedTab.value = status;
  currentPage.value = 1;
};

const getTabCountClass = (status) => {
  // ... (same as your original code)
};

const getUserStatusClass = (status) => {
  // ... (same as your original code)
};

// --- Sorting State ---
const sortBy = ref('name');
const sortDirection = ref('asc');


// --- Action Button Handlers ---
const handleEditUser = (userId) => {
    router.push(`/admin/user/edit/${userId}`); // Example route
};

const handleViewUser = (userId) => {
    router.push(`/admin/user/view/${userId}`); // Example route
    closeUserActionsDropdown();
};

const handleDeleteUser = async (userId) => {
  if (confirm(`Are you sure you want to delete user ${userId}?`)) {
    try {
      const token = sessionStorage.getItem("auth_token");
      await api.delete(`/users/${userId}`, { headers: { Authorization: `Bearer ${token}` } });
      showNotification(`User ${userId} deleted successfully!`);
      fetchUsers(); // Refresh list
    } catch (error) {
      console.error("Failed to delete user:", error);
      showNotification(`Error deleting user ${userId}.`, "error");
    }
  }
  closeUserActionsDropdown();
};

const handleExport = () => showNotification('Exporting user data...');
const handleBulkDelete = () => showNotification('Bulk delete initiated...');

// --- Pagination State ---
const rowsPerPage = ref(5);
const currentPage = ref(1);
const isDense = ref(false);

// --- Computed Properties for Filtering, Sorting & Pagination ---
const filteredAndSortedUsers = computed(() => {
  let tempUsers = users.value;

  if (selectedTab.value !== 'All') {
    tempUsers = tempUsers.filter(user => user.status === selectedTab.value);
  }
  if (selectedRoleFilter.value) {
    tempUsers = tempUsers.filter(user => user.type === selectedRoleFilter.value);
  }
  if (searchQuery.value) {
    const searchLower = searchQuery.value.toLowerCase();
    tempUsers = tempUsers.filter(user =>
      user.name.toLowerCase().includes(searchLower) ||
      user.email.toLowerCase().includes(searchLower)
    );
  }
  
  return tempUsers;
});

const paginatedUsers = computed(() => {
  const start = (currentPage.value - 1) * rowsPerPage.value;
  const end = start + rowsPerPage.value;
  return filteredAndSortedUsers.value.slice(start, end);
});

const totalPages = computed(() => Math.ceil(filteredAndSortedUsers.value.length / rowsPerPage.value));
const currentRangeText = computed(() => {
    // ... (same as your original code)
});

// Update tab counts whenever users data changes
const updateTabCounts = () => {
    // ... (same as your original code)
};

watch(users, updateTabCounts, { deep: true });
watch([selectedTab, selectedRoleFilter, searchQuery], () => { currentPage.value = 1; });

// --- Pagination Actions ---
const prevPage = () => { if (currentPage.value > 1) currentPage.value--; };
const nextPage = () => { if (currentPage.value < totalPages.value) currentPage.value++; };
</script>

<style scoped>
/* Scoped styles remain the same */
</style>