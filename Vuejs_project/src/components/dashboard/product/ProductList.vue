<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useRouter } from 'vue-router'; // Import useRouter for navigation
import api from '../../../axios/Axios'; // Ensure this path is correct for your project

const router = useRouter(); // Initialize router for navigation

// --- Mock Data (This will be populated from the API) ---
const products = ref([]);

// --- Function to Fetch Products ---
const fetchProducts = async () => {
    try {
        const token = sessionStorage.getItem("auth_token");
        if (!token) {
            alert("No token found. Please log in.");
            // Optionally, redirect to login page
            // router.push('/login');
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
        // alert("Failed to fetch products. Please try again."); // Use a custom modal instead of alert in production
        products.value = []; // Clear products on error
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

// --- Column Management State ---
const availableColumns = [
    { key: 'checkbox', name: 'Checkbox', defaultVisible: true },
    { key: 'product', name: 'Product', defaultVisible: true, sortKey: 'name' },
    { key: 'createdAt', name: 'Create at', defaultVisible: true, sortKey: 'createdAt' },
    { key: 'stock', name: 'Stock', defaultVisible: true, sortKey: 'stock' },
    { key: 'price', name: 'Price', defaultVisible: true, sortKey: 'price' },
    { key: 'publish', name: 'Publish', defaultVisible: true, sortKey: 'status' },
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

// --- Dynamic Filter State & Logic ---
const filterColumnOptions = [
    { key: 'name', name: 'Product Name', type: 'string' },
    { key: 'category', name: 'Category', type: 'string' },
    { key: 'createdAt', name: 'Create at', type: 'date' },
    { key: 'stock', name: 'Stock', type: 'number' },
    { key: 'price', name: 'Price', type: 'number' },
    { key: 'status', name: 'Publish Status', type: 'string' },
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

// --- Sorting State ---
const sortBy = ref(null);
const sortDirection = ref('asc');

// --- Column Menu State ---
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
    // closeColumnMenu(); // This might close it immediately after opening if not careful
};

const closeColumnMenu = () => {
    showColumnMenu.value = false;
    activeColumnKey.value = null;
};

// --- Custom Dropdown Toggles (Stock) ---
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

// Custom Publish Dropdown Toggles
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

// Toggle for Manage Columns Dropdown
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

// Export Dropdown Toggles
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

// Export actions
const handlePrint = () => {
    alert('Printing the table...'); // Replace with a custom modal/dialog
    showExportDropdown.value = false;
};

const handleDownloadCsv = () => {
    alert('Downloading as CSV...'); // Replace with actual CSV export logic
    showExportDropdown.value = false;
};


// --- Click outside handler for all custom dropdowns ---
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
    fetchProducts(); // Fetch products when component mounts
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
});

// --- Sorting Actions ---
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
    alert(`Filter column: ${activeColumnKey.value}`); // Replace with a custom modal/dialog
    closeColumnMenu();
};

// --- Action Button Handlers (FIXED) ---
const handleView = (productId) => {
    alert(`Viewing product ${productId}`); // Replace with navigation to product detail page
    // Example: router.push({ name: 'ProductDetail', params: { id: productId } });
};

const handleEdit = (productId) => {
    alert(`Editing product ${productId}`); // Replace with navigation to product edit page
    router.push({ name: 'ProductEdit', params: { id: productId } }); // Assuming 'ProductEdit' is a named route
};

const handleDelete = async (productId) => {
    // Replace confirm with a custom modal/dialog in production
    if (confirm(`Are you sure you want to delete product ${productId}?`)) {
        try {
            const token = sessionStorage.getItem("auth_token");
            if (!token) {
                alert("No token found. Please log in.");
                // router.push('/login');
                return;
            }

            // Perform the API call to delete the product
            const response = await api.delete(`/products/${productId}`, {
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            });

            if (response.status === 200 || response.status === 204) { // Check for successful deletion status codes
                // Optimistically remove from UI after successful API call
                products.value = products.value.filter(p => p.id !== productId);
                alert(`Product ${productId} deleted successfully!`); // Replace with a custom modal/toast
            } else {
                alert(`Failed to delete product ${productId}. Server responded with status: ${response.status}`);
            }
        } catch (error) {
            console.error("Failed to delete product:", error.response?.data || error.message);
            alert(`Error deleting product ${productId}. Please try again.`); // Replace with a custom modal/toast
        }
    }
};

// --- Pagination State ---
const rowsPerPage = ref(10);
const currentPage = ref(1);

// --- Computed Properties for Filtering, Sorting & Pagination ---
const sortedAndFilteredProducts = computed(() => {
    let tempProducts = [...products.value];

    // Apply search filter (from general search box)
    if (searchQuery.value) {
        const searchLower = searchQuery.value.toLowerCase();
        tempProducts = tempProducts.filter(
            (p) =>
                p.name.toLowerCase().includes(searchLower) ||
                p.category.toLowerCase().includes(searchLower) // Assuming 'category' exists on product objects
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


// --- Pagination Actions ---
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

// --- Helper Functions for Display ---
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
</script>
