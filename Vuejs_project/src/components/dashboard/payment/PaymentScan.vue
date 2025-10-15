<template>
    <div v-if="showPaymentScan" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-3xl relative transition-all duration-300">
            <!-- Close Button -->
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
                    <h2 class="text-3xl font-bold text-gray-900">Scan to Pay</h2>
                    <p class="text-gray-600">Secure mobile payment via Stripe</p>
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
                                <span class="font-medium">{{ formatPrice(item.price * item.quantity) }}</span>
                            </div>
                        </div>
                        <div class="border-t border-gray-200 pt-4 mt-4">
                            <div class="flex justify-between items-center text-lg font-bold text-gray-900">
                                <span>Total Amount:</span>
                                <span>{{ formatPrice(total) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- QR Payment Section -->
                    <div class="space-y-6">
                        <div class="bg-blue-50 p-6 rounded-lg border border-blue-200 text-center">
                            <div class="mb-4 flex items-center justify-center gap-3">
                                <div class="p-2 bg-blue-100 rounded-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h4M4 8h16M4 16h16" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900">Scan to Pay</h3>
                            </div>
                            
                            <button @click="generatePaymentLink" 
                                :disabled="loading"
                                class="w-full py-3 px-6 flex items-center justify-center gap-2 text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                <svg v-if="loading" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>{{ loading ? 'Generating QR Code...' : 'Generate Payment QR' }}</span>
                            </button>

                            <div v-if="qrCodeImage" class="mt-6 space-y-4">
                                <div class="p-4 bg-white rounded-lg border-2 border-dashed border-blue-200">
                                    <img :src="qrCodeImage" alt="Payment QR Code" class="mx-auto w-48 h-48 object-contain" />
                                </div>
                                <p class="text-sm text-gray-600">
                                    Scan this QR code with your mobile payment app
                                    <br>to complete the transaction
                                </p>
                            </div>
                        </div>

                        <!-- Status Messages -->
                        <div v-if="resultMessage" 
                            :class="['p-4 rounded-lg border flex items-start gap-3', 
                                   resultMessage.includes('success') ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200']">
                            <svg v-if="resultMessage.includes('success')" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <p :class="['text-sm', resultMessage.includes('success') ? 'text-green-700' : 'text-red-700']">
                                {{ resultMessage }}
                            </p>
                        </div>

                        <!-- Security Footer -->
                        <div class="pt-4 border-t border-gray-200 text-center">
                            <div class="flex items-center justify-center gap-2 text-sm text-gray-500">
                                <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Secure TLS encrypted payment processing</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import api from "../../../axios/Axios";
import QRCode from 'qrcode';

export default {
    name: "PaymentScan",
    props: {
        selectedItems: { type: Array, required: true },
        showPaymentScan: { type: Boolean, required: true },
    },
    emits: ["close-modal", "payment-success"],
    data() {
        return {
            total: 0,
            loading: false,
            resultMessage: "",
            paymentUrl: "",
            qrCodeImage: "",
            paymentId: null,
            pollingInterval: null,
        };
    },
    watch: {
        selectedItems: {
            handler() { this.calculateTotal(); },
            deep: true,
        },
    },
    beforeUnmount() {
        if (this.pollingInterval) clearInterval(this.pollingInterval);
    },
    mounted() {
        this.calculateTotal();
    },
    methods: {
        formatPrice(value) {
            return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(value);
        },
        calculateTotal() {
            this.total = this.selectedItems.reduce((sum, item) => sum + item.price * item.quantity, 0);
        },
        closeModal() {
            if (this.pollingInterval) clearInterval(this.pollingInterval);
            this.$emit("close-modal");
        },
        async generatePaymentLink() {
            this.loading = true;
            this.resultMessage = "";
            
            try {
                const response = await api.post('/payment/create', {
                    items: this.selectedItems.map(item => ({
                        product_name: item.name,
                        quantity: item.quantity,
                        price: item.price
                    })),
                    currency: 'usd',
                }, {
                    headers: { Authorization: `Bearer ${sessionStorage.getItem("auth_token")}` }
                });

                this.paymentUrl = response.data.payment_url;
                this.paymentId = response.data.payment_id;
                
                this.qrCodeImage = await QRCode.toDataURL(this.paymentUrl, {
                    errorCorrectionLevel: 'H',
                    margin: 2,
                    width: 400,
                    color: {
                        dark: '#1E40AF',
                        light: '#FFFFFF'
                    }
                });

                this.resultMessage = "QR code generated successfully. Scan to complete payment.";
                this.startPaymentPolling();

            } catch (error) {
                console.error('Payment error:', error);
                this.resultMessage = "Failed to generate payment QR. Please try again.";
            } finally {
                this.loading = false;
            }
        },
        startPaymentPolling() {
            this.pollingInterval = setInterval(async () => {
                try {
                    const response = await api.get(`/payment/status/${this.paymentId}`, {
                        headers: { Authorization: `Bearer ${sessionStorage.getItem("auth_token")}` }
                    });

                    if (response.data.paymentStatus === 'success') {
                        clearInterval(this.pollingInterval);
                        await this.handleScanOrder();
                        this.$emit("payment-success");
                        this.closeModal();
                    }
                } catch (error) {
                    console.error('Polling error:', error);
                    clearInterval(this.pollingInterval);
                }
            }, 5000);
        },
        async handleScanOrder() {
            try {
                const orderPayload = {
                    user_id: parseInt(sessionStorage.getItem("id"), 10),
                    amount: this.total,
                    payment_type: "scan",
                    items: this.selectedItems.map(item => ({
                        product_id: item.id,
                        product_name: item.name,
                        quantity: item.quantity,
                        amount: item.price * item.quantity,
                        size: item.size || null,
                    }))
                };

                await api.post("/orders", orderPayload, {
                    headers: { Authorization: `Bearer ${sessionStorage.getItem("auth_token")}` }
                });

            } catch (error) {
                console.error("Order creation failed:", error);
                throw new Error("Failed to create order record");
            }
        },
    },
};
</script>