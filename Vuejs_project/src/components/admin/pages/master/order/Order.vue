<script>
    const { createApp, ref, computed, onMounted, onUnmounted } = Vue;

    createApp({
        setup() {
            // Reactive State
            const orders = ref([]);
            const loading = ref(true);
            const error = ref(null);
            const activeTab = ref('all');
            const searchQuery = ref('');
            const startDate = ref('');
            const endDate = ref('');
            const selectedOrders = ref([]);
            const showDropdown = ref(false);
            const orderDropdowns = ref({});
            const selectedOrderForDetails = ref(null);

            // Tabs for filtering
            const tabs = ref([
                { id: 'all', name: 'All', count: 0 },
                { id: 'pending', name: 'Pending', count: 0 },
                { id: 'completed', name: 'Completed', count: 0 },
                { id: 'cancelled', name: 'Cancelled', count: 0 },
                { id: 'refunded', name: 'Refunded', count: 0 }
            ]);

            // API Endpoint (Using a placeholder JSON API)
            const API_BASE_URL = 'https://jsonplaceholder.typicode.com';
            
            // Methods
            const fetchOrders = async () => {
                loading.value = true;
                error.value = null;
                try {
                    const [usersResponse, postsResponse] = await Promise.all([
                        fetch(`${API_BASE_URL}/users`),
                        fetch(`${API_BASE_URL}/posts?_limit=20`)
                    ]);

                    if (!usersResponse.ok || !postsResponse.ok) {
                        throw new Error('Network response was not ok.');
                    }

                    const users = await usersResponse.json();
                    const posts = await postsResponse.json();
                    const usersMap = new Map(users.map(user => [user.id, user]));
                    const statuses = ['completed', 'pending', 'cancelled', 'refunded'];

                    orders.value = posts.map(post => {
                        const user = usersMap.get(post.userId) || { name: 'Unknown User', email: 'unknown@example.com' };
                        const status = statuses[post.id % statuses.length];
                        const total = parseFloat((Math.random() * 200 + 50).toFixed(2));
                        const quantity = Math.floor(Math.random() * 3) + 1;

                        return {
                            id: post.id,
                            customer: { name: user.name, email: user.email.toLowerCase() },
                            status: status,
                            date: new Date(Date.now() - Math.random() * 365 * 24 * 60 * 60 * 1000).toISOString(),
                            items: [{
                                id: `prod-${post.id}`,
                                name: post.title.split(' ').slice(0, 3).join(' '),
                                sku: `SKU${post.id}${post.userId}`,
                                quantity: quantity,
                                price: parseFloat((total / quantity).toFixed(2))
                            }],
                            total: total
                        };
                    });
                    updateTabCounts();
                } catch (err) {
                    error.value = 'Failed to fetch orders from the API. Please try again.';
                } finally {
                    loading.value = false;
                }
            };

            const deleteOrderAPI = async (orderId) => {
                try {
                    // Simulate API call and success
                    orders.value = orders.value.filter(o => o.id !== orderId);
                    selectedOrders.value = selectedOrders.value.filter(id => id !== orderId);
                    updateTabCounts();
                } catch (err) {
                    error.value = 'Failed to delete order.';
                }
            };
            
            // Computed Properties
            const filteredOrders = computed(() => {
                let filtered = orders.value;
                if (activeTab.value !== 'all') {
                    filtered = filtered.filter(order => order.status === activeTab.value);
                }
                if (searchQuery.value) {
                    const query = searchQuery.value.toLowerCase();
                    filtered = filtered.filter(order => 
                        order.id.toString().includes(query) ||
                        order.customer.name.toLowerCase().includes(query) ||
                        order.customer.email.toLowerCase().includes(query)
                    );
                }
                if (startDate.value) filtered = filtered.filter(order => new Date(order.date) >= new Date(startDate.value));
                if (endDate.value) filtered = filtered.filter(order => new Date(order.date) <= new Date(endDate.value));
                return filtered;
            });

            const paginatedOrders = computed(() => filteredOrders.value);
            
            const isAllSelected = computed({
                get: () => filteredOrders.value.length > 0 && selectedOrders.value.length === filteredOrders.value.length,
                set: (value) => {
                    selectedOrders.value = value ? filteredOrders.value.map(order => order.id) : [];
                }
            });

            // Helper Functions
            const updateTabCounts = () => {
                tabs.value.forEach(tab => {
                    tab.count = tab.id === 'all' ? orders.value.length : orders.value.filter(o => o.status === tab.id).length;
                });
            };

            const setActiveTab = (tabId) => activeTab.value = tabId;
            const toggleDropdown = () => showDropdown.value = !showDropdown.value;
            const toggleOrderDropdown = (orderId) => {
                const currentState = !!orderDropdowns.value[orderId];
                orderDropdowns.value = {};
                if (!currentState) orderDropdowns.value[orderId] = true;
            };
            const getInitials = (name) => !name ? '' : name.split(' ').map(n => n[0]).join('').toUpperCase();
            const formatDate = (dateString) => new Date(dateString).toLocaleDateString('en-US', { day: '2-digit', month: 'short', year: 'numeric' });
            const formatTime = (dateString) => new Date(dateString).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
            
            // Action Handlers
            const navigateTo = (route) => console.log(`Navigating to: ${route}`);
            const printOrders = () => window.print();
            const importOrders = () => alert("Import functionality would be implemented here.");
            const exportOrders = () => {
                const data = filteredOrders.value;
                const headers = ['Order ID', 'Customer Name', 'Email', 'Date', 'Status', 'Total'];
                const rows = data.map(o => [o.id, o.customer.name, o.customer.email, formatDate(o.date), o.status, o.total.toFixed(2)]);
                const csv = [headers, ...rows].map(row => row.join(',')).join('\n');
                const blob = new Blob([csv], { type: 'text/csv' });
                const a = document.createElement('a');
                a.href = window.URL.createObjectURL(blob);
                a.download = 'orders.csv';
                a.click();
                window.URL.revokeObjectURL(a.href);
            };
            const viewOrder = (orderId) => {
                selectedOrderForDetails.value = orders.value.find(o => o.id === orderId);
                orderDropdowns.value[orderId] = false;
            };
            const editOrder = (orderId) => {
                alert(`Editing order: ${orderId}`);
                orderDropdowns.value[orderId] = false;
            };
            const deleteOrder = (orderId) => {
                if (confirm('Are you sure you want to delete this order?')) deleteOrderAPI(orderId);
                orderDropdowns.value[orderId] = false;
            };
            const closeOrderDetails = () => selectedOrderForDetails.value = null;
            
            const handleClickOutside = (event) => {
                if (!event.target.closest('.dropdown')) {
                    showDropdown.value = false;
                    orderDropdowns.value = {};
                }
            };
            
            // Lifecycle Hooks
            onMounted(() => {
                fetchOrders();
                window.addEventListener('click', handleClickOutside);
            });
            
            onUnmounted(() => {
                window.removeEventListener('click', handleClickOutside);
            });

            // Return values to be used in the template
            return {
                loading, error, activeTab, searchQuery, startDate, endDate,
                selectedOrders, showDropdown, orderDropdowns, selectedOrderForDetails,
                tabs, filteredOrders, paginatedOrders, isAllSelected,
                setActiveTab, toggleDropdown, toggleOrderDropdown, getInitials,
                formatDate, formatTime, navigateTo, printOrders, importOrders,
                exportOrders, viewOrder, editOrder, deleteOrder, closeOrderDetails
            };
        }
    }).mount('#app');
</script>