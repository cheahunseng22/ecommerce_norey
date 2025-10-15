<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';
import api from '../../../axios/Axios';
import CreateProduct from './create.vue';
import ConfirmationModal from '../customer/ConfirmationModal.vue';

// --- Mock Data ---
const router = useRouter();
const products = ref([]); // This will be populated from the API

// --- Function to Fetch Products ---
const fetchProducts = async () => {
  try {
        const token = sessionStorage.getItem("auth_token");
        if (!token) {
          alert("No token found. Please log in.");
          return;
        }
        const response = await api.get(
          "/products",
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );
       products.value = response.data.data;

    } catch (error) {
      console.error("Failed to fetch products:", error.response?.data || error.message);
      // alert("Failed to fetch products. Please try again.");
      products.value = [];
    }
};

// --- Filter & Search State ---
const selectedStockFilter = ref([]); // Array for multi-select
const tempSelectedStockFilters = ref([]); // Temporary selection for dropdown
const selectedPublishFilter = ref([]); // Array for multi-select
const tempSelectedPublishFilters = ref([]); // Temporary selection for dropdown
const searchQuery = ref('');

// --- Dropdown visibility states ---
const isStockDropdownOpen = ref(false);
const isPublishDropdownOpen = ref(false);
const showManageColumnsDropdown = ref(false);
const showFiltersDropdown = ref(false);
const showExportDropdown = ref(false);

// --- Refs for click outside ---
const stockDropdownRef = ref(null);
const publishFilterRef = ref(null);
const manageColumnsRef = ref(null);
const filtersDropdownRef = ref(null);
const exportDropdownRef = ref(null);

// --- Column Management State (existing) ---
const availableColumns = [
  { key: 'checkbox', name: 'Checkbox', defaultVisible: true },
  { key: 'product', name: 'Product', defaultVisible: true, sortKey: 'name' },
  { key: 'createdAt', name: 'Create at', defaultVisible: true, sortKey: 'createdAt' },
  { key: 'stock', name: 'Stock', defaultVisible: true, sortKey: 'stock' },
  { key: 'price', name: 'Price', defaultVisible: true, sortKey: 'price' },
  // { key: 'publish', name: 'Publish', defaultVisible: true, sortKey: 'status' },
  { key: 'action', name: 'Action', defaultVisible: true },
];

const initialVisibleColumnKeys = availableColumns
  .filter(col => col.defaultVisible)
  .map(col => col.key);

const visibleColumns = ref([...initialVisibleColumnKeys]);
const tempVisibleColumns = ref([...initialVisibleColumnKeys]);
const columnSearchQuery = ref('');

const allColumnKeys = computed(() => availableColumns.map(col => col.key));

const allColumnsSelected = computed({
  get() {
    const selectableColumns = availableColumns.filter(col => col.key !== 'checkbox' && col.key !== 'action');
    return selectableColumns.every(col => tempVisibleColumns.value.includes(col.key));
  },
  set(value) {
    if (value) {
      tempVisibleColumns.value = [...allColumnKeys.value];
    } else {
      tempVisibleColumns.value = ['checkbox', 'action'].filter(key => allColumnKeys.value.includes(key));
    }
  }
});

const resetVisibleColumns = () => {
  tempVisibleColumns.value = [...initialVisibleColumnKeys];
  applyManageColumns();
};

const filteredAvailableColumns = computed(() => {
  const query = columnSearchQuery.value.toLowerCase();
  return availableColumns.filter(col =>
    col.name.toLowerCase().includes(query)
  );
});

// --- Dynamic Filter State & Logic (existing) ---
const filterColumnOptions = [
  { key: 'name', name: 'Product Name', type: 'string' },
  { key: 'category', name: 'Category', type: 'string' },
  { key: 'createdAt', name: 'Create at', type: 'date' },
  { key: 'stock', name: 'Stock', type: 'number' },
  { key: 'price', name: 'Price', type: 'number' },
  // { key: 'status', name: 'Publish Status', type: 'string' },
];

const operatorGroups = {
  string: [
    { key: 'contains', name: 'contains' },
    { key: 'equals', name: 'equals' },
    { key: 'startsWith', name: 'starts with' },
    { key: 'endsWith', name: 'ends with' }
  ],
  number: [
    { key: 'equals', name: 'equals' },
    { key: 'gt', name: 'greater than' },
    { key: 'lt', name: 'less than' },
    { key: 'gte', name: 'greater than or equal' },
    { key: 'lte', name: 'less than or equal' }
  ],
  date: [
    { key: 'equals', name: 'on' },
    { key: 'before', name: 'before' },
    { key: 'after', name: 'after' }
  ]
};

const activeFilters = ref([]);
const tempActiveFilters = ref([]);

let nextFilterId = 0;

const getOperatorsForColumn = (columnKey) => {
  const column = filterColumnOptions.find(col => col.key === columnKey);
  return column ? operatorGroups[column.type] : [];
};

const addFilterRow = () => {
  const defaultColumn = filterColumnOptions[0]?.key || '';
  const defaultOperator = getOperatorsForColumn(defaultColumn)[0]?.key || '';
  tempActiveFilters.value.push({
    id: nextFilterId++,
    column: defaultColumn,
    operator: defaultOperator,
    value: '',
  });
};

const removeFilterRow = (idToRemove) => {
  tempActiveFilters.value = tempActiveFilters.value.filter(f => f.id !== idToRemove);
};

const toggleFiltersDropdown = () => {
  showFiltersDropdown.value = !showFiltersDropdown.value;
  if (showFiltersDropdown.value) {
    tempActiveFilters.value = activeFilters.value.map(f => ({ ...f }));
    if (tempActiveFilters.value.length === 0) {
      addFilterRow();
    }
    isStockDropdownOpen.value = false;
    isPublishDropdownOpen.value = false;
    showManageColumnsDropdown.value = false;
    showExportDropdown.value = false;
    closeColumnMenu();
  }
};

const applyAllFilters = () => {
  activeFilters.value = tempActiveFilters.value
    .filter(f => f.column && f.operator && (f.value !== null && f.value !== ''))
    .map(f => ({ ...f }));

  showFiltersDropdown.value = false;
  currentPage.value = 1;
};

const clearAllFilters = () => {
  activeFilters.value = [];
  tempActiveFilters.value = [];
  showFiltersDropdown.value = false;
  currentPage.value = 1;
};

// --- Sorting State (existing) ---
const sortBy = ref(null);
const sortDirection = ref('asc');

// --- Column Menu State (existing) ---
const showColumnMenu = ref(false);
const menuPosition = ref({ top: '0px', left: '0px' });
const activeColumnKey = ref(null);

const toggleColumnMenu = (event, key) => {
  if (activeColumnKey.value === key && showColumnMenu.value) {
    closeColumnMenu();
  } else {
    activeColumnKey.value = key;
    const rect = event.target.closest('th').getBoundingClientRect();
    menuPosition.value = {
      top: `${rect.bottom + window.scrollY + 5}px`,
      left: `${rect.left + window.scrollX + rect.width - 150}px`,
    };
    showColumnMenu.value = true;
  }
  isStockDropdownOpen.value = false;
  isPublishDropdownOpen.value = false;
  showManageColumnsDropdown.value = false;
  showFiltersDropdown.value = false;
  showExportDropdown.value = false;
};

const closeColumnMenu = () => {
  showColumnMenu.value = false;
  activeColumnKey.value = null;
};

// --- Custom Dropdown Toggles (Stock) (existing) ---
const toggleStockDropdown = () => {
  isStockDropdownOpen.value = !isStockDropdownOpen.value;
  if (isStockDropdownOpen.value) {
    tempSelectedStockFilters.value = [...selectedStockFilter.value];
    closeColumnMenu();
    isPublishDropdownOpen.value = false;
    showManageColumnsDropdown.value = false;
    showFiltersDropdown.value = false;
    showExportDropdown.value = false;
  }
};

const applyStockFilter = () => {
  selectedStockFilter.value = [...tempSelectedStockFilters.value];
  isStockDropdownOpen.value = false;
  currentPage.value = 1;
};

const clearStockFilter = () => {
  tempSelectedStockFilters.value = [];
  selectedStockFilter.value = [];
  isStockDropdownOpen.value = false;
  currentPage.value = 1;
};

// Custom Publish Dropdown Toggles (existing)
const togglePublishDropdown = () => {
  isPublishDropdownOpen.value = !isPublishDropdownOpen.value;
  if (isPublishDropdownOpen.value) {
    tempSelectedPublishFilters.value = [...selectedPublishFilter.value];
    closeColumnMenu();
    isStockDropdownOpen.value = false;
    showManageColumnsDropdown.value = false;
    showFiltersDropdown.value = false;
    showExportDropdown.value = false;
  }
};

const applyPublishFilter = () => {
  selectedPublishFilter.value = [...tempSelectedPublishFilters.value];
  isPublishDropdownOpen.value = false;
  currentPage.value = 1;
};

const clearPublishFilter = () => {
  tempSelectedPublishFilters.value = [];
  selectedPublishFilter.value = [];
  isPublishDropdownOpen.value = false;
  currentPage.value = 1;
};

// Toggle for Manage Columns Dropdown (existing)
const toggleManageColumns = () => {
  showManageColumnsDropdown.value = !showManageColumnsDropdown.value;
  if (showManageColumnsDropdown.value) {
    tempVisibleColumns.value = [...visibleColumns.value];
    columnSearchQuery.value = '';
    isStockDropdownOpen.value = false;
    isPublishDropdownOpen.value = false;
    showFiltersDropdown.value = false;
    showExportDropdown.value = false;
    closeColumnMenu();
  }
};

const applyManageColumns = () => {
  visibleColumns.value = [...tempVisibleColumns.value];
  showManageColumnsDropdown.value = false;
};

// Export Dropdown Toggles (existing)
const toggleExportDropdown = () => {
  showExportDropdown.value = !showExportDropdown.value;
  if (showExportDropdown.value) {
    isStockDropdownOpen.value = false;
    isPublishDropdownOpen.value = false;
    showManageColumnsDropdown.value = false;
    showFiltersDropdown.value = false;
    closeColumnMenu();
  }
};

// Export actions (existing)
const handlePrint = () => {
  alert('Printing the table...');
  showExportDropdown.value = false;
};

const handleDownloadCsv = () => {
  alert('Downloading as CSV...');
  showExportDropdown.value = false;
};


// --- Click outside handler for all custom dropdowns (UPDATED) ---
const handleClickOutside = (event) => {
  if (showColumnMenu.value && !event.target.closest('.column-menu-container') && !event.target.closest('.column-menu-button')) {
    closeColumnMenu();
  }
  if (isStockDropdownOpen.value && stockDropdownRef.value && !stockDropdownRef.value.contains(event.target)) {
    isStockDropdownOpen.value = false;
  }
  if (isPublishDropdownOpen.value && publishFilterRef.value && !publishFilterRef.value.contains(event.target)) {
    isPublishDropdownOpen.value = false;
  }
  if (showManageColumnsDropdown.value && manageColumnsRef.value && !manageColumnsRef.value.contains(event.target)) {
    showManageColumnsDropdown.value = false;
  }
  if (showFiltersDropdown.value && filtersDropdownRef.value && !filtersDropdownRef.value.contains(event.target)) {
    showFiltersDropdown.value = false;
  }
  if (showExportDropdown.value && exportDropdownRef.value && !exportDropdownRef.value.contains(event.target)) {
    showExportDropdown.value = false;
  }
};

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
  fetchProducts();
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});

// --- Sorting Actions (existing) ---
const handleSort = (key) => {
  const sortProperty = availableColumns.find(col => col.key === key)?.sortKey || key;

  if (sortBy.value === sortProperty) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortBy.value = sortProperty;
    sortDirection.value = 'asc';
  }
  closeColumnMenu();
};

const handleUnsort = () => {
  sortBy.value = null;
  sortDirection.value = 'asc';
  closeColumnMenu();
};

const getCurrentSortIcon = (key) => {
  const sortProperty = availableColumns.find(col => col.key === key)?.sortKey || key;
  if (sortBy.value === sortProperty) {
    return sortDirection.value === 'asc' ? '▲' : '▼';
  }
  return '';
};

const getMenuItemSortTextAndIcon = (columnKey) => {
  const sortProperty = availableColumns.find(col => col.key === columnKey)?.sortKey || columnKey;

  if (sortBy.value === sortProperty && sortDirection.value === 'asc') {
    return { text: 'Sort by DESC', icon: '▼' };
  }
  if (sortBy.value === sortProperty && sortDirection.value === 'desc') {
    return { text: 'Sort by ASC', icon: '▲' };
  }
  return { text: 'Sort by ASC', icon: '▲' };
};

const handleFilterColumn = () => {
  alert(`Filter column: ${activeColumnKey.value}`);
  closeColumnMenu();
};

// --- Action Button Handlers (existing) ---
const handleView = (productId) => {
  alert(`Viewing product ${productId}`);
};

const handleEdit = (productId) => {
  router.push({ name: 'Edit', query: { id: productId } });
};

// Delete confirmation modal state and handlers
const showDeleteModal = ref(false);
const productToDelete = ref(null);
const isDeleting = ref(false);
const deleteError = ref('');

const handleDelete = (productId) => {
  productToDelete.value = productId;
  deleteError.value = '';
  showDeleteModal.value = true;
};

const confirmDelete = async () => {
  if (!productToDelete.value || isDeleting.value) return;
  isDeleting.value = true;
  deleteError.value = '';
  try {
    const token = sessionStorage.getItem('auth_token');
    if (!token) {
      alert('No token found. Please log in.');
      showDeleteModal.value = false;
      return;
    }
    const id = productToDelete.value;
    const response = await api.delete(`/products/${id}`, {
      headers: { Authorization: `Bearer ${token}` },
    });
    if (response.status === 200 || response.status === 204) {
      products.value = products.value.filter((p) => p.id !== id);
      showDeleteModal.value = false;
      productToDelete.value = null;
    } else {
      deleteError.value = `Failed to delete. Status: ${response.status}`;
    }
  } catch (error) {
    console.error('Failed to delete product:', error.response?.data || error.message);
    deleteError.value = 'Error deleting product. Please try again.';
  } finally {
    isDeleting.value = false;
  }
};

// --- Pagination State (existing) ---
const rowsPerPage = ref(10);
const currentPage = ref(1);

// --- Computed Properties for Filtering, Sorting & Pagination (existing) ---
const sortedAndFilteredProducts = computed(() => {
  let tempProducts = [...products.value];

  // Apply search filter (from general search box)
  if (searchQuery.value) {
    const searchLower = searchQuery.value.toLowerCase();
    tempProducts = tempProducts.filter(
      (p) =>
        p.name.toLowerCase().includes(searchLower) ||
        p.category.toLowerCase().includes(searchLower)
    );
  }

  // Apply stock filter
  if (selectedStockFilter.value.length > 0) {
    tempProducts = tempProducts.filter((p) => {
      const productStockStatus =
        p.stock === 0
          ? 'Out of stock'
          : p.stock <= 10
          ? 'Low stock'
          : 'In stock';
      return selectedStockFilter.value.includes(productStockStatus);
    });
  }

  // Apply publish filter
  if (selectedPublishFilter.value.length > 0) {
    tempProducts = tempProducts.filter((p) =>
      selectedPublishFilter.value.includes(p.status)
    );
  }

  // Apply dynamic filters
  if (activeFilters.value.length > 0) {
    tempProducts = tempProducts.filter(p => {
      return activeFilters.value.every(filterRule => {
        const columnData = filterColumnOptions.find(col => col.key === filterRule.column);
        if (!columnData) return true;

        const productValue = p[filterRule.column];
        const filterValue = filterRule.value;
        const operator = filterRule.operator;
        const columnType = columnData.type;

        if (productValue === null || productValue === undefined) {
            return false;
        }

        switch (columnType) {
          case 'string':
            const pValStr = String(productValue).toLowerCase();
            const fValStr = String(filterValue).toLowerCase();
            if (operator === 'contains') return pValStr.includes(fValStr);
            if (operator === 'equals') return pValStr === fValStr;
            if (operator === 'startsWith') return pValStr.startsWith(fValStr);
            if (operator === 'endsWith') return pValStr.endsWith(fValStr);
            break;
          case 'number':
            const pValNum = Number(productValue);
            const fValNum = Number(filterValue);
            if (isNaN(pValNum) || isNaN(fValNum)) return false;
            if (operator === 'equals') return pValNum === fValNum;
            if (operator === 'gt') return pValNum > fValNum;
            if (operator === 'lt') return pValNum < fValNum;
            if (operator === 'gte') return pValNum >= fValNum;
            if (operator === 'lte') return pValNum <= fValNum;
            break;
          case 'date':
            const pValDate = new Date(productValue).setHours(0,0,0,0);
            const fValDate = new Date(filterValue).setHours(0,0,0,0);
            if (isNaN(pValDate) || isNaN(fValDate)) return false;
            if (operator === 'equals') return pValDate === fValDate;
            if (operator === 'before') return pValDate < fValDate;
            if (operator === 'after') return pValDate > fValDate;
            break;
        }
        return false;
      });
    });
  }

  // Apply sorting
  if (sortBy.value) {
    tempProducts.sort((a, b) => {
      let valA, valB;
      if (sortBy.value === 'createdAt') {
        valA = new Date(a.createdAt).getTime();
        valB = new Date(b.createdAt).getTime();
      } else if (sortBy.value === 'name' || sortBy.value === 'status' || sortBy.value === 'category') {
        valA = a[sortBy.value]?.toLowerCase() || '';
        valB = b[sortBy.value]?.toLowerCase() || '';
      } else {
        valA = a[sortBy.value];
        valB = b[sortBy.value];
      }

      if (valA < valB) return sortDirection.value === 'asc' ? -1 : 1;
      if (valA > valB) return sortDirection.value === 'asc' ? 1 : -1;
      return 0;
    });
  }

  return tempProducts;
});

const paginatedProducts = computed(() => {
  const start = (currentPage.value - 1) * rowsPerPage.value;
  const end = start + rowsPerPage.value;
  return sortedAndFilteredProducts.value.slice(start, end);
});

const totalPages = computed(() => {
  return Math.ceil(sortedAndFilteredProducts.value.length / rowsPerPage.value);
});

const currentRangeText = computed(() => {
  const start = (currentPage.value - 1) * rowsPerPage.value + 1;
  const end = Math.min(
    currentPage.value * rowsPerPage.value,
    sortedAndFilteredProducts.value.length
  );
  if (sortedAndFilteredProducts.value.length === 0) {
    return '0-0 of 0';
  }
  return `${start}-${end} of ${sortedAndFilteredProducts.value.length}`;
});


// --- Pagination Actions (existing) ---
const goToPage = (page) => {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page;
  }
};

const nextPage = () => {
  goToPage(currentPage.value + 1);
};

const prevPage = () => {
  goToPage(currentPage.value - 1);
};

// --- Helper Functions for Display (existing) ---
const formatStock = (stock) => {
  if (stock === 0) return { text: 'out of stock', class: 'text-red-500' };
  if (stock > 0 && stock <= 10) return { text: `${stock} low stock`, class: 'text-yellow-500' };
  return { text: `${stock} in stock`, class: 'text-green-500' };
};

const getStockBarColorClass = (stock) => {
    if (stock === 0) return 'bg-red-400';
    if (stock > 0 && stock <= 10) return 'bg-yellow-400';
    return 'bg-green-400';
};

const getPublishStatusClass = (status) => {
  return status === 'Published' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800';
  
};

const formatDate = (isoString) => {
  const date = new Date(isoString);
  return date.toLocaleDateString('en-US', {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
  });
};

const formatTime = (isoString) => {
  const date = new Date(isoString);
  return date.toLocaleTimeString('en-US', {
    hour: '2-digit',
    minute: '2-digit',
    hour12: true,
  });
};

const getInputValueType = (columnKey) => {
    const column = filterColumnOptions.find(col => col.key === columnKey);
    if (!column) return 'text';
    if (column.type === 'number') return 'number';
    if (column.type === 'date') return 'date';
    return 'text';
};

// Modal state for Create Product
const isCreateModalOpen = ref(false);
const handleCloseCreateModal = () => {
  isCreateModalOpen.value = false;
};
const handleProductCreated = async () => {
  isCreateModalOpen.value = false;
  await fetchProducts();
};
</script>

<template>
  <div class="font-sans bg-gray-100 p-5 text-gray-800 min-h-screen relative">
    <!-- Header Section -->
    <header class="flex justify-between items-center mb-6">
      <div>
        <h1 class="text-3xl font-semibold mb-1 text-gray-900"> Product List</h1>
        <nav class="flex items-center text-sm text-gray-600">
          <router-link to="/admin/home" class="hover:underline">Dashboard</router-link>
          <span class="before:content-['•'] before:mx-2 before:text-gray-400"></span>
          <router-link to="/admin/products/list" class="hover:underline">Products</router-link>
          <span class="before:content-['•'] before:mx-2 before:text-gray-400"></span>
          <span> List</span>
        </nav>
      </div>
      <!-- Open Create Product Modal Button -->
      <button @click="isCreateModalOpen = true"
        class="bg-gray-800 text-white px-6 py-3 rounded-md shadow-md hover:bg-gray-700 transition font-semibold flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>
        Create product
      </button>
    </header>

    <!-- Filters and Actions Section -->
    <div class="bg-white rounded-lg p-4 mb-5 flex justify-between items-center shadow-sm">
      <div class="flex gap-4">
        <!-- Stock Filter - Custom Dropdown -->
        <div class="relative stock-dropdown-container" ref="stockDropdownRef">
          <button
            @click="toggleStockDropdown"
            class="flex items-center justify-between w-full bg-white border border-gray-300 text-gray-700 py-2 px-4 rounded-md leading-tight focus:outline-none focus:bg-white focus:border-blue-500 text-sm appearance-none cursor-pointer min-w-[120px]"
            :class="{'border-blue-500 ring ring-blue-200': isStockDropdownOpen}"
          >
            <span>Stock</span>
            <svg class="fill-current h-4 w-4 transform transition-transform duration-200"
                 :class="{'rotate-180': isStockDropdownOpen}"
                 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
              <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
            </svg>
          </button>
          <div
            v-if="isStockDropdownOpen"
            class="absolute top-full left-0 mt-1 w-48 bg-white border border-gray-300 rounded-md shadow-lg z-20 py-2"
          >
            <label class="flex items-center px-4 py-2 hover:bg-gray-50 cursor-pointer text-gray-700 text-sm">
              <input type="checkbox" v-model="tempSelectedStockFilters" value="In stock" class="form-checkbox h-4 w-4 text-blue-600 rounded">
              <span class="ml-2">In stock</span>
            </label>
            <label class="flex items-center px-4 py-2 hover:bg-gray-50 cursor-pointer text-gray-700 text-sm">
              <input type="checkbox" v-model="tempSelectedStockFilters" value="Low stock" class="form-checkbox h-4 w-4 text-blue-600 rounded">
              <span class="ml-2">Low stock</span>
            </label>
            <label class="flex items-center px-4 py-2 hover:bg-gray-50 cursor-pointer text-gray-700 text-sm">
              <input type="checkbox" v-model="tempSelectedStockFilters" value="Out of stock" class="form-checkbox h-4 w-4 text-blue-600 rounded">
              <span class="ml-2">Out of stock</span>
            </label>
            <div class="border-t border-gray-200 mt-2 pt-2 flex justify-end gap-2 px-4">
              <button @click="clearStockFilter" class="px-3 py-1 text-sm text-gray-600 border border-gray-300 rounded-md hover:bg-gray-100">Clear</button>
              <button @click="applyStockFilter" class="px-3 py-1 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700">Apply</button>
            </div>
          </div>
        </div>

        <!-- Publish Filter - Custom Dropdown -->
        <!-- <div class="relative publish-dropdown-container" ref="publishFilterRef">
          <button
            @click="togglePublishDropdown"
            class="flex items-center justify-between w-full bg-white border border-gray-300 text-gray-700 py-2 px-4 rounded-md leading-tight focus:outline-none focus:bg-white focus:border-blue-500 text-sm appearance-none cursor-pointer min-w-[120px]"
            :class="{'border-blue-500 ring ring-blue-200': isPublishDropdownOpen}"
          >
            <span>Publish</span>
            <svg class="fill-current h-4 w-4 transform transition-transform duration-200"
                 :class="{'rotate-180': isPublishDropdownOpen}"
                 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
              <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
            </svg>
          </button>
          <div
            v-if="isPublishDropdownOpen"
            class="absolute top-full left-0 mt-1 w-48 bg-white border border-gray-300 rounded-md shadow-lg z-20 py-2"
          >
            <label class="flex items-center px-4 py-2 hover:bg-gray-50 cursor-pointer text-gray-700 text-sm">
              <input type="checkbox" v-model="tempSelectedPublishFilters" value="Published" class="form-checkbox h-4 w-4 text-blue-600 rounded">
              <span class="ml-2">Published</span>
            </label>
            <label class="flex items-center px-4 py-2 hover:bg-gray-50 cursor-pointer text-gray-700 text-sm">
              <input type="checkbox" v-model="tempSelectedPublishFilters" value="Draft" class="form-checkbox h-4 w-4 text-blue-600 rounded">
              <span class="ml-2">Draft</span>
            </label>
            <div class="border-t border-gray-200 mt-2 pt-2 flex justify-end gap-2 px-4">
              <button @click="clearPublishFilter" class="px-3 py-1 text-sm text-gray-600 border border-gray-300 rounded-md hover:bg-gray-100">Clear</button>
              <button @click="applyPublishFilter" class="px-3 py-1 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700">Apply</button>
            </div>
          </div>
        </div> -->

         <div class="relative w-full md:w-1/3">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
              </svg>
            </div>
            <input 
              v-model="searchQuery"
              type="text" 
              placeholder="Search Product..." 
              class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
            />
          </div>
      </div>
      <div class="flex gap-4">
        <!-- Columns Button and Dropdown -->
        <div class="relative" ref="manageColumnsRef">
            <button
              @click="toggleManageColumns"
              class="flex items-center gap-1.5 p-2 rounded-md hover:bg-gray-100 hover:text-gray-900 transition-colors duration-200 text-gray-600 text-sm cursor-pointer"
              :class="{ 'bg-gray-200 text-gray-900': showManageColumnsDropdown }"
            >
              <span class="text-base">☰</span> Columns
            </button>

            <div
              v-if="showManageColumnsDropdown"
              class="absolute top-full right-0 mt-1 w-64 bg-white border border-gray-300 rounded-lg shadow-lg z-30 py-2"
            >
              <div class="px-4 py-2">
                <div class="relative mb-3">
                  <input
                    type="text"
                    v-model="columnSearchQuery"
                    placeholder="Search"
                    class="w-full pl-8 pr-3 py-1.5 border border-gray-300 rounded-md text-sm focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-200"
                  />
                  <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400">🔍</span>
                </div>

                <div class="border-b border-gray-200 mb-2 pb-2">
                  <label class="flex items-center justify-between px-2 py-1.5 hover:bg-gray-50 cursor-pointer text-gray-700 text-sm rounded-md">
                    <div class="flex items-center">
                        <input type="checkbox" v-model="allColumnsSelected" class="form-checkbox h-4 w-4 text-blue-600 rounded">
                        <span class="ml-2 font-medium">Show/Hide All</span>
                    </div>
                    <button @click.stop="resetVisibleColumns" class="text-xs text-gray-500 hover:text-gray-700">Reset</button>
                  </label>
                </div>

                <div class="max-h-60 overflow-y-auto pr-2">
                  <label v-for="col in filteredAvailableColumns" :key="col.key" class="flex items-center px-2 py-1.5 hover:bg-gray-50 cursor-pointer text-gray-700 text-sm rounded-md">
                    <input type="checkbox" v-model="tempVisibleColumns" :value="col.key" class="form-checkbox h-4 w-4 text-blue-600 rounded" :disabled="col.key === 'checkbox' || col.key === 'action'">
                    <span class="ml-2">{{ col.name }}</span>
                  </label>
                </div>

                <div class="border-t border-gray-200 mt-3 pt-3 flex justify-end">
                  <button @click="applyManageColumns" class="px-4 py-2 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200">Apply</button>
                </div>
              </div>
            </div>
        </div>

        <!-- Filters Button and Dropdown -->
        <div class="relative" ref="filtersDropdownRef">
          <button
            @click="toggleFiltersDropdown"
            class="flex items-center gap-1.5 p-2 rounded-md hover:bg-gray-100 hover:text-gray-900 transition-colors duration-200 text-gray-600 text-sm cursor-pointer"
            :class="{ 'bg-gray-200 text-gray-900': showFiltersDropdown }"
          >
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V19l-4 2v-4.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
            </svg>
            Filters
          </button>

          <div
            v-if="showFiltersDropdown"
            class="absolute top-full right-0 mt-1 w-[500px] bg-white border border-gray-300 rounded-lg shadow-lg z-30 py-4 px-5"
          >
            <!-- Filters List -->
            <div v-for="filter in tempActiveFilters" :key="filter.id" class="flex items-end gap-3 mb-4 last:mb-0">
              <button
                @click="removeFilterRow(filter.id)"
                class="flex-shrink-0 p-1.5 rounded-full bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-700 transition-colors duration-200"
                title="Remove filter"
              >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>

              <div class="flex-grow flex items-end gap-3">
                <div class="flex-1 min-w-[120px]">
                  <label class="block text-gray-600 text-xs font-semibold mb-1">Columns</label>
                  <div class="relative">
                    <select
                      v-model="filter.column"
                      @change="filter.operator = getOperatorsForColumn(filter.column)[0]?.key || ''"
                      class="block w-full bg-white border border-gray-300 text-gray-700 py-2 px-3 pr-8 rounded-md leading-tight focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-200 text-sm appearance-none cursor-pointer"
                    >
                      <option v-for="col in filterColumnOptions" :key="col.key" :value="col.key">
                        {{ col.name }}
                      </option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                      <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                  </div>
                </div>

                <div class="flex-1 min-w-[100px]">
                  <label class="block text-gray-600 text-xs font-semibold mb-1">Operator</label>
                  <div class="relative">
                    <select
                      v-model="filter.operator"
                      class="block w-full bg-white border border-gray-300 text-gray-700 py-2 px-3 pr-8 rounded-md leading-tight focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-200 text-sm appearance-none cursor-pointer"
                    >
                      <option v-for="op in getOperatorsForColumn(filter.column)" :key="op.key" :value="op.key">
                        {{ op.name }}
                      </option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                      <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                  </div>
                </div>

                <div class="flex-1 min-w-[120px]">
                  <label class="block text-gray-600 text-xs font-semibold mb-1">Value</label>
                  <input
                    :type="getInputValueType(filter.column)"
                    v-model="filter.value"
                    placeholder="Filter value"
                    class="block w-full bg-white border border-gray-300 text-gray-700 py-2 px-3 rounded-md leading-tight focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-200 text-sm"
                  />
                </div>
              </div>
            </div>

            <div class="border-t border-gray-200 pt-4 mt-4 flex justify-between items-center">
              <button
                @click="addFilterRow"
                class="px-4 py-2 text-sm text-blue-600 border border-blue-600 rounded-md hover:bg-blue-50 transition-colors duration-200 flex items-center gap-1"
              >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Add Filter
              </button>
              <div class="flex gap-2">
                <button @click="clearAllFilters" class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-md hover:bg-gray-100 transition-colors duration-200">Clear All</button>
                <button @click="applyAllFilters" class="px-4 py-2 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200">Apply Filters</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Export Button and Dropdown -->
        <div class="relative" ref="exportDropdownRef">
          <button
            @click="toggleExportDropdown"
            class="flex items-center gap-1.5 p-2 rounded-md hover:bg-gray-100 hover:text-gray-900 transition-colors duration-200 text-gray-600 text-sm cursor-pointer"
            :class="{ 'bg-gray-200 text-gray-900': showExportDropdown }"
          >
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            Export
          </button>

          <div
            v-if="showExportDropdown"
            class="absolute top-full right-0 mt-1 w-48 bg-white border border-gray-300 rounded-lg shadow-lg z-30 py-2"
          >
            <button
              @click="handlePrint"
              class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200"
            >
              Print
            </button>
            <button
              @click="handleDownloadCsv"
              class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200"
            >
              Download as CSV
            </button>
          </div>
        </div>

        <button class="text-gray-600 text-sm cursor-pointer flex items-center gap-1.5 p-2 rounded-md hover:bg-gray-100 hover:text-gray-900 transition-colors duration-200">
          <span class="text-base">⚙</span> Settings
        </button>
      </div>
    </div>

    <!-- Product Table Section -->
   <div class="bg-white rounded-lg shadow-lg max-h-[450px] overflow-x-auto overflow-y-auto relative">

      <table class="w-full text-sm text-left text-gray-700 border-collapse">
        <thead class="bg-gray-50">
          <tr>
            <th scope="col" class="p-4 rounded-tl-lg sticky top-0 z-10 bg-gray-50" v-if="visibleColumns.includes('checkbox')">
                <div class="flex items-center">
                  <input id="checkbox-all-search" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 focus:ring-2">
                  <label for="checkbox-all-search" class="sr-only">checkbox</label>
                </div>
            </th>
            <th scope="col" class="py-3 px-6 text-gray-700 font-semibold border-b border-gray-200 whitespace-nowrap sticky top-0 z-10 bg-gray-50" v-if="visibleColumns.includes('product')">
              <div class="flex items-center justify-between w-full gap-2">
                Product <span class="text-[10px] text-gray-600 ml-1">{{ getCurrentSortIcon('product') }}</span>
                <button
                  class="column-menu-button text-gray-400 text-lg cursor-pointer px-1 py-0.5 rounded-md leading-none flex items-center ml-auto hover:bg-gray-100 hover:text-gray-600 transition-colors duration-200"
                  @click.stop="toggleColumnMenu($event, 'product')"
                >
                  ...
                </button>
              </div>
            </th>
            <th scope="col" class="py-3 px-6 text-gray-700 font-semibold border-b border-gray-200 whitespace-nowrap sticky top-0 z-10 bg-gray-50" v-if="visibleColumns.includes('createdAt')">
              <div class="flex items-center justify-between w-full gap-2">
                Create at <span class="text-[10px] text-gray-600 ml-1">{{ getCurrentSortIcon('createdAt') }}</span>
                <button
                  class="column-menu-button text-gray-400 text-lg cursor-pointer px-1 py-0.5 rounded-md leading-none flex items-center ml-auto hover:bg-gray-100 hover:text-gray-600 transition-colors duration-200"
                  @click.stop="toggleColumnMenu($event, 'createdAt')"
                >
                  ...
                </button>
              </div>
            </th>
            <th scope="col" class="py-3 px-6 text-gray-700 font-semibold border-b border-gray-200 whitespace-nowrap sticky top-0 z-10 bg-gray-50" v-if="visibleColumns.includes('stock')">
              <div class="flex items-center justify-between w-full gap-2">
                Stock <span class="text-[10px] text-gray-600 ml-1">{{ getCurrentSortIcon('stock') }}</span>
                <button
                  class="column-menu-button text-gray-400 text-lg cursor-pointer px-1 py-0.5 rounded-md leading-none flex items-center ml-auto hover:bg-gray-100 hover:text-gray-600 transition-colors duration-200"
                  @click.stop="toggleColumnMenu($event, 'stock')"
                >
                  ...
                </button>
              </div>
            </th>
            <th scope="col" class="py-3 px-6 text-gray-700 font-semibold border-b border-gray-200 whitespace-nowrap sticky top-0 z-10 bg-gray-50" v-if="visibleColumns.includes('price')">
              <div class="flex items-center justify-between w-full gap-2">
                Price <span class="text-[10px] text-gray-600 ml-1">{{ getCurrentSortIcon('price') }}</span>
                <button
                  class="column-menu-button text-gray-400 text-lg cursor-pointer px-1 py-0.5 rounded-md leading-none flex items-center ml-auto hover:bg-gray-100 hover:text-gray-600 transition-colors duration-200"
                  @click.stop="toggleColumnMenu($event, 'price')"
                >
                  ...
                </button>
              </div>
            </th>
            <th scope="col" class="py-3 px-6 text-gray-700 font-semibold border-b border-gray-200 whitespace-nowrap sticky top-0 z-10 bg-gray-50" v-if="visibleColumns.includes('publish')">
              <div class="flex items-center justify-between w-full gap-2">
                Publish <span class="text-[10px] text-gray-600 ml-1">{{ getCurrentSortIcon('publish') }}</span>
                <button
                  class="column-menu-button text-gray-400 text-lg cursor-pointer px-1 py-0.5 rounded-md leading-none flex items-center ml-auto hover:bg-gray-100 hover:text-gray-600 transition-colors duration-200"
                  @click.stop="toggleColumnMenu($event, 'publish')"
                >
                  ...
                </button>
              </div>
            </th>
            <!-- Action column header - No sort button for actions as it's not a data field to sort by -->
            <th scope="col" class="py-3 px-6 text-gray-700 font-semibold border-b border-gray-200 whitespace-nowrap rounded-tr-lg sticky top-0 z-10 bg-gray-50 text-center" v-if="visibleColumns.includes('action')">
              Action
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="product in paginatedProducts" :key="product.id" class="bg-white border-b border-gray-100 hover:bg-gray-50">
            <td class="p-4" v-if="visibleColumns.includes('checkbox')">
                <div class="flex items-center">
                  <input :id="'checkbox-table-search-' + product.id" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 focus:ring-2">
                  <label :for="'checkbox-table-search-' + product.id" class="sr-only">checkbox</label>
                </div>
            </td>
            <td class="py-4 px-6 flex items-center gap-4" v-if="visibleColumns.includes('product')">
              <div class="w-14 h-14 min-w-[56px] rounded-lg overflow-hidden bg-gray-100 flex items-center justify-center">
                <img class="w-full h-full object-cover rounded-lg" :src="product.image" :alt="product.name">
              </div>
              <div>
                <div class="text-base font-semibold text-gray-900">{{ product.name }}</div>
              </div>
            </td>
            <td class="py-4 px-6" v-if="visibleColumns.includes('createdAt')">
              <div class="font-semibold mb-0.5">{{ formatDate(product.created_at) }}</div>
              <div class="text-xs text-gray-500">{{ formatTime(product.created_at) }}</div>
            </td>
            <td class="py-4 px-6" v-if="visibleColumns.includes('stock')">
              <div class="flex flex-col items-start gap-1">
                <div class="h-1.5 rounded-full w-20 bg-gray-200">
                  <div class="h-full rounded-full" :class="getStockBarColorClass(product.stock)" :style="{ width: product.stock === 0 ? '100%' : `${(product.stock / 72) * 100}%` }"></div>
                </div>
                <span class="text-xs font-medium" :class="formatStock(product.stock).class">
                  {{ formatStock(product.stock).text }}
                </span>
              </div>
            </td>
            <td class="py-4 px-6 font-semibold" v-if="visibleColumns.includes('price')">
              ${{ product.price }}
            </td>
            <td class="py-4 px-6" v-if="visibleColumns.includes('publish')">
              <span :class="['inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium uppercase', getPublishStatusClass(product.status)]">
                {{ product.status }}
              </span>
            </td>
            <!-- Action buttons for each row -->
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
  <div class="inline-flex items-center space-x-4">
    
   

    <!-- Edit Button -->
    <button
      @click="handleEdit(product.id)"
      title="Edit"
    class="text-blue-600 hover:text-blue-800 mr-4 transition duration-150 ease-in-out transform hover:scale-105"
    >
      <i class="fas fa-edit mr-1"></i> Edit
    </button>

    <!-- Delete Button -->
    <button
      @click="handleDelete(product.id)"
      title="Delete"
      class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-150 ease-in-out"
    >
      <i class="fas fa-trash mr-1"></i> Delete
    </button>
  </div>
           </td>
          </tr>
          <tr v-if="paginatedProducts.length === 0">
            <td :colspan="visibleColumns.length" class="text-center text-gray-500 italic py-5">No products found matching your criteria.</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Column Header Context Menu -->
    <div
      v-if="showColumnMenu"
      class="column-menu-container absolute bg-white rounded-lg shadow-lg z-50 min-w-[180px] py-2 border border-gray-200 overflow-hidden"
      :style="menuPosition"
      @click.stop
    >
      <div class="absolute top-0 right-0 bg-gray-900 text-white px-2.5 py-1 rounded-bl-lg text-xs font-medium pointer-events-none">Menu</div>
      <div
        class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-800 cursor-pointer whitespace-nowrap hover:bg-gray-100 hover:text-gray-900 transition-colors duration-200"
        @click="handleSort(activeColumnKey)"
      >
        <span class="text-base w-5 text-center text-gray-600">{{ getMenuItemSortTextAndIcon(activeColumnKey).icon }}</span>
        {{ getMenuItemSortTextAndIcon(activeColumnKey).text }}
      </div>
      <div
        class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-800 cursor-pointer whitespace-nowrap hover:bg-gray-100 hover:text-gray-900 transition-colors duration-200"
        @click="handleUnsort"
        v-if="sortBy === (availableColumns.find(col => col.key === activeColumnKey)?.sortKey || activeColumnKey)"
      >
        <span class="text-base w-5 text-center text-gray-600"></span>
        Unsort
      </div>
      <div class="h-px bg-gray-200 my-2"></div>
      <div class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-800 cursor-pointer whitespace-nowrap hover:bg-gray-100 hover:text-gray-900 transition-colors duration-200" @click="handleFilterColumn">
        <span class="text-base w-5 text-center text-gray-600">Y</span> Filter
      </div>
    </div>

    <!-- Pagination Footer -->
    <div class="flex justify-end items-center gap-5 mt-5 bg-white p-4 rounded-lg shadow-sm text-sm text-gray-700">
      <div class="flex items-center gap-2">
        Rows per page:
        <div class="relative">
          <select v-model="rowsPerPage" class="block bg-white border border-gray-300 text-gray-700 py-1.5 px-2 pr-6 rounded-md leading-tight focus:outline-none focus:bg-white focus:border-blue-500 text-sm min-w-[70px] appearance-none cursor-pointer">
            <option value="5">5</option>
            <option value="10">10</option>
            <option value="20">20</option>
          </select>
          <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
          </div>
        </div>
      </div>
      <div class="ml-auto">{{ currentRangeText }}</div>
      <div class="flex gap-2.5">
        <button @click="prevPage" :disabled="currentPage === 1" class="bg-gray-100 text-gray-700 border border-gray-300 rounded-md px-3 py-2 cursor-pointer text-sm transition-colors duration-200 hover:bg-gray-200 disabled:opacity-50 disabled:cursor-not-allowed">
          <
        </button>
        <button
          @click="nextPage"
          :disabled="currentPage === totalPages"
          class="bg-gray-100 text-gray-700 border border-gray-300 rounded-md px-3 py-2 cursor-pointer text-sm transition-colors duration-200 hover:bg-gray-200 disabled:opacity-50 disabled:cursor-not-allowed"
        >
          >
        </button>
      </div>
    </div>
  </div>

  <transition name="modal-fade">
    <div v-if="isCreateModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
      <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-4 border-b">
          <h3 class="text-lg font-semibold">Create Product</h3>
          <button @click="handleCloseCreateModal" class="text-gray-500 hover:text-gray-700">✕</button>
        </div>
        <div class="p-4">
          <CreateProduct :isModal="true" @close="handleCloseCreateModal" @created="handleProductCreated" />
        </div>
      </div>
    </div>
  </transition>

  <!-- Confirm Delete Modal -->
  <transition name="modal-fade">
    <ConfirmationModal
      v-if="showDeleteModal"
      :title="'Delete Product'"
      :message="'Are you sure you want to delete this product? This action cannot be undone.'"
      @confirm="confirmDelete"
      @cancel="showDeleteModal = false"
    />
  </transition>
</template>

<style scoped>
/* No custom CSS is needed here. All styling is handled via Tailwind CSS utility classes directly in the HTML. */

.modal-fade-enter-active,
.modal-fade-leave-active {
  transition: opacity 0.2s ease;
}
.modal-fade-enter-from,
.modal-fade-leave-to {
  opacity: 0;
}
</style>