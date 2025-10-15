<template>
    <div class="container mx-auto p-4 lg:p-6">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800 lg:text-3xl">Order History</h1>
            <p class="mt-2 text-gray-600">View and manage customer orders</p>
        </div>

        <!-- Status Indicators -->
        <div class="mb-6 space-y-4">
            <div v-if="loading" class="flex items-center justify-center p-4 bg-blue-50 rounded-lg">
                <svg class="animate-spin h-6 w-6 text-blue-600 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-blue-700 font-medium">Loading orders...</span>
            </div>

            <div v-if="error" class="p-4 bg-red-50 rounded-lg flex items-center">
                <svg class="h-5 w-5 text-red-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <span class="text-red-700 font-medium">{{ error }}</span>
            </div>
        </div>

        <!-- Orders Table -->
        <div v-if="orders.data?.length" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Order ID</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">User</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Amount</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Payment</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Date</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-for="order in paginatedOrders" :key="order.id" class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-900 font-medium">#{{ order.id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">User {{ order.user_id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 font-medium">
                                ${{ Number(order.amount).toFixed(2) }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-sm" 
                                      :class="paymentTypeClasses(order.payment_type)">
                                    {{ order.payment_type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ formatDate(order.created_at) }}</td>
                            <td class="px-6 py-4 text-right">
                                <button @click="showViewModal(order)" 
                                        class="p-2 text-gray-400 hover:text-blue-600 rounded-lg transition-colors"
                                        aria-label="View order details">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-100">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="text-sm text-gray-600">
                        Showing <span class="font-medium">{{ from }}</span> to 
                        <span class="font-medium">{{ to }}</span> of 
                        <span class="font-medium">{{ total }}</span> results
                    </p>
                    
                    <div class="flex items-center gap-2"> 
                        <template v-for="link in links" :key="link.label">
                            <button v-if="link.url" 
                                    @click="goToPage(link)"
                                    :class="link.active ? 
                                        'bg-blue-600 text-white' : 
                                        'text-gray-600 hover:bg-gray-50'"
                                    class="px-3 py-1.5 text-sm rounded-md transition-colors"
                                    v-html="link.label"
                                    aria-label="Go to page">
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div v-else class="text-center p-12 bg-white rounded-xl border border-dashed border-gray-200">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No orders found</h3>
            <p class="mt-1 text-sm text-gray-500">All orders will appear here once placed</p>
        </div>

        <!-- Order Details Modal -->
        <Modal :isVisible="modalVisible" @close="closeModal">
            <div class="p-6 max-w-2xl">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">Order #{{ currentOrder?.id }}</h2>
                    <button @click="closeModal" class="text-gray-400 hover:text-gray-500">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="space-y-6">
                    <!-- Order Summary -->
                    <div class="grid grid-cols-2 gap-4 p-4 bg-gray-50 rounded-lg">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Order Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ formatDate(currentOrder?.created_at) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Payment Method</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ currentOrder?.payment_type }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Total Amount</dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900">
                                ${{ Number(currentOrder?.amount).toFixed(2) }}
                            </dd>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Items</h3>
                        <div v-if="currentOrder?.items?.length" class="border rounded-lg divide-y divide-gray-200">
                            <div v-for="item in currentOrder.items" :key="item.id" class="p-4 flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-gray-900">Product #{{ item.product_id }}</p>
                                    <p class="text-sm text-gray-500">{{ item.size }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-900">x{{ item.quantity }}</p>
                                    <p class="text-sm text-gray-500">${{ Number(item.amount).toFixed(2) }}</p>
                                </div>
                            </div>
                        </div>
                        <div v-else class="p-4 text-center text-gray-500">
                            No items found in this order
                        </div>
                    </div>
                </div>
            </div>
        </Modal>
    </div>
</template>

<script>
import Modal from "../user/Modal.vue";
import api from "../../../../../axios/Axios";

export default {
    components: { Modal },
    data() {
        return {
            orders: {},
            loading: false,
            error: null,
            modalVisible: false,
            currentOrder: null,
        };
    },
    computed: {
        paginatedOrders() { return this.orders.data || [] },
        current_page() { return this.orders.current_page },
        last_page() { return this.orders.last_page },
        links() { return this.orders.links || [] },
        from() { return this.orders.from },
        to() { return this.orders.to },
        total() { return this.orders.total }
    },
    mounted() {
        this.fetchOrders();
    },
    methods: {
        paymentTypeClasses(type) {
            const styles = {
                'credit_card': 'bg-green-100 text-green-800',
                'paypal': 'bg-blue-100 text-blue-800',
                'cash': 'bg-yellow-100 text-yellow-800'
            };
            return styles[type.toLowerCase()] || 'bg-gray-100 text-gray-800';
        },
        async fetchOrders() {
            this.loading = true;
            this.error = null;
            try {
                const token = sessionStorage.getItem("auth_token");
                const response = await api.get("/orders", {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                });
                this.orders = response.data;
            } catch (err) {
                this.error = "Error fetching orders: " + err.message;
                console.error("Error fetching orders", err);
            } finally {
                this.loading = false;
            }
        },
        showViewModal(order) {
            this.modalType = "view";
            this.modalVisible = true;
            this.currentOrder = { ...order };
        },
        closeModal() {
            this.modalVisible = false;
            this.currentOrder = null;
        },
        formatDate(date) {
            if (!date) return "N/A";
            const options = { year: "numeric", month: "short", day: "numeric" };
            return new Date(date).toLocaleDateString(undefined, options);
        },
        prevPage() {
            if (this.orders.prev_page_url) {
                this.fetchOrdersFromUrl(this.orders.prev_page_url);
            }
        },
        nextPage() {
            if (this.orders.next_page_url) {
                this.fetchOrdersFromUrl(this.orders.next_page_url);
            }
        },
        goToPage(link) {
            if (link.url) {
                this.fetchOrdersFromUrl(link.url);
            }
        },
        async fetchOrdersFromUrl(url) {
            this.loading = true;
            this.error = null;
            try {
                const token = sessionStorage.getItem("auth_token");
                const response = await api.get(url, {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                });
                this.orders = response.data;
            } catch (err) {
                this.error = "Error fetching orders: " + err.message;
                console.error("Error fetching orders", err);
            } finally {
                this.loading = false;
            }
        }
    }
};
</script>
<style scoped>
/* Import Font Awesome for Icons */
@import "font-awesome/css/font-awesome.min.css";
</style>