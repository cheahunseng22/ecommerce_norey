<template>
    <div v-if="showPaymentCash"
        class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-3xl relative transition-all duration-300">
            <!-- Close button -->
            <button @click="closeModal"
                class="absolute -top-2 -right-2 p-2 bg-white rounded-full shadow-lg hover:scale-105 transition-transform"
                aria-label="Close payment modal">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <div class="p-6 md:p-8 space-y-6">
                <!-- Header -->
                <div class="space-y-2">
                    <h2 class="text-3xl font-bold text-gray-900">Cash Payment</h2>
                    <p class="text-gray-600">Please review your order before confirming</p>
                </div>

                <!-- Content Container -->
                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Order Summary -->
                    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Order Summary</h3>
                        <div class="space-y-4">
                            <div v-for="(item, index) in selectedItems" :key="index"
                                class="flex justify-between items-center text-gray-700">
                                <div>
                                    <p class="font-medium">{{ item.name }}</p>
                                    <p class="text-sm text-gray-500">Qty: {{ item.quantity }} • Size: {{ item.selectedSize }}</p>
                                </div>
                                <span class="font-medium">${{ (item.price * item.quantity).toFixed(2) }}</span>
                            </div>
                        </div>
                        <div class="border-t border-gray-200 pt-4 mt-4">
                            <div class="flex justify-between items-center text-lg font-bold text-gray-900">
                                <span>Total Amount:</span>
                                <span>${{ total.toFixed(2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Instructions -->
                    <div class="space-y-6">
                        <div class="bg-blue-50 p-6 rounded-lg border border-blue-200">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="p-2 bg-blue-100 rounded-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900">Cash Payment Instructions</h3>
                            </div>
                            <ul class="space-y-3 text-gray-600 list-disc pl-5">
                                <li>Please prepare exact amount</li>
                                <li>Payment will be verified by staff</li>
                                <li>Keep cash ready for collection</li>
                            </ul>
                        </div>

                        <button @click="handlePayment" :disabled="isPaidAlready || processing"
                            class="w-full py-3.5 px-6 flex items-center justify-center gap-2 text-lg font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg v-if="processing" class="animate-spin h-5 w-5 text-white"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <span>{{ processing ? 'Processing...' : 'Confirm Cash Payment' }}</span>
                        </button>
                    </div>
                </div>

                <!-- Result Message -->
                <div v-if="resultMessage"
                    class="mt-4 p-4 rounded-lg bg-green-50 border border-green-200 flex items-start gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600 mt-0.5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <p class="text-green-700">{{ resultMessage }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import api from '../../../axios/Axios';

export default {
    name: "PaymentCash",
    props: {
        selectedItems: {
            type: Array,
            required: true,
        },
        showPaymentCash: {
            type: Boolean,
            required: true,
        },
    },
    emits: ["close-modal"],
    data() {
        return {
            total: 0,
            resultMessage: "",
            processing: false,
        };
    },
    watch: {
        selectedItems: {
            handler() {
                this.calculateTotal();
            },
            deep: true,
        },
    },
    mounted() {
        this.generateGroupKey();
        this.generateOrderSessionKey();
        this.calculateTotal();
    },
    computed: {
        isPaidAlready() {
            return sessionStorage.getItem('order_paid') === 'true';
        }
    },
    methods: {
        calculateTotal() {
            this.total = this.selectedItems.reduce((sum, item) => sum + item.price * item.quantity, 0);
        },
        closeModal() {
            this.resultMessage = "";
            this.$emit("close-modal");
        },
        generateOrderSessionKey() {
            if (!sessionStorage.getItem('order_session_key')) {
                const uniqueKey = `sess_${Date.now()}_${Math.floor(Math.random() * 10000)}`;
                sessionStorage.setItem('session_key', uniqueKey);
            }
        },
        generateGroupKey() {
            const userId = sessionStorage.getItem('id');
            if (!sessionStorage.getItem('group_key') && userId) {
                const groupKey = `group_${userId}`;
                sessionStorage.setItem('group_key', groupKey);
            }
        },
        async handlePayment() {
            this.processing = true;
            try {
                const userId = sessionStorage.getItem("id");
                const orderPayload = {
                    user_id: userId,
                    amount: this.total,
                    payment_type: "cash",
                    session_key: sessionStorage.getItem("session_key"),
                    group_key: sessionStorage.getItem("group_key"),
                    items: this.selectedItems.map(item => ({
                        product_id: item.id,
                        product_name: item.name,
                        quantity: item.quantity,
                        amount: item.price,
                        size: item.selectedSize
                    }))
                };
                const token = sessionStorage.getItem("auth_token");
                const response = await api.post("/pending-orders", orderPayload, {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                });

                if (response.status === 201) {
                    this.resultMessage = "Order successfully placed! Awaiting cashier confirmation.";
                    sessionStorage.setItem('order_paid', 'true');
                    setTimeout(() => this.closeModal(), 2500);
                }
            } catch (error) {
                console.error("Payment error:", error);
                this.resultMessage = "Payment processing failed. Please try again.";
            } finally {
                this.processing = false;
            }
        }
    },
};
</script>