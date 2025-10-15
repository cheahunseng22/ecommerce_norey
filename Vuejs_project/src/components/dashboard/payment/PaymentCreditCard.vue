<template>
    <div v-if="showPaymentCard" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
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
                    <h2 class="text-3xl font-bold text-gray-900">Credit Card Payment</h2>
                    <p class="text-gray-600">Secure payment processed through Stripe</p>
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

                    <!-- Payment Form -->
                    <div class="space-y-4">
                        <form @submit.prevent="handleSubmit" class="space-y-4">
                            <!-- Cardholder Name -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Cardholder Name</label>
                                <input v-model="cardholderName"
                                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    type="text"
                                    required
                                    :disabled="loading"
                                    placeholder="John Doe">
                            </div>

                            <!-- Stripe Card Element -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Card Details</label>
                                <div id="card-element" class="p-3 rounded-lg border border-gray-300 bg-white"></div>
                            </div>

                            <!-- Form Error -->
                            <div v-if="formError" class="mt-2 p-3 rounded-lg bg-red-50 border border-red-200 flex items-start gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <p class="text-red-700 text-sm">{{ formError }}</p>
                            </div>

                            <!-- Submit Button -->
                            <button
                                class="w-full py-3.5 px-6 flex items-center justify-center gap-2 text-lg font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                type="submit"
                                :disabled="loading">
                                <svg v-if="loading" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>{{ loading ? 'Processing Payment...' : `Pay ${formatPrice(total)}` }}</span>
                            </button>
                        </form>

                        <!-- Security Badges -->
                        <div class="flex items-center justify-center gap-3 pt-4 border-t border-gray-200">
                            <svg class="h-8 w-auto" viewBox="0 0 38 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M35 0H3C1.3 0 0 1.3 0 3v18c0 1.7 1.4 3 3 3h32c1.7 0 3-1.3 3-3V3c0-1.7-1.4-3-3-3z" fill="#000" opacity=".07"/>
                                <path d="M35 1c1.1 0 2 .9 2 2v18c0 1.1-.9 2-2 2H3c-1.1 0-2-.9-2-2V3c0-1.1.9-2 2-2h32" fill="#fff"/>
                                <path d="M8.9 6.2c.1.1.1.2.1.3v3.4h1.4V6.6c0-.2 0-.4-.1-.5-.1-.1-.3-.2-.4-.2-.2 0-.4.1-.5.2-.1.1-.2.3-.2.5v.9H8.3v-.9c0-.6.2-1.1.6-1.4.4-.3.9-.4 1.4-.3.5.1.9.4 1.1.8.2.5.2 1 0 1.5v3.3h1.4V6.4c0-.2 0-.4-.1-.5s-.3-.2-.4-.2c-.2 0-.4.1-.5.2s-.2.3-.2.5v3.3h1.4V6.4c0-.6-.1-1.1-.5-1.4-.4-.3-.9-.4-1.4-.3-.5.1-.9.4-1.1.8-.2.5-.2 1 0 1.5v.9H8.3v-.9c0-.3.1-.5.2-.6.1-.1.3-.2.4-.2s.3.1.4.2zM17.4 8.3c0 .6-.2 1.1-.5 1.5-.4.4-.9.6-1.5.6s-1.1-.2-1.5-.6c-.4-.4-.6-.9-.6-1.5s.2-1.1.6-1.5c.4-.4.9-.6 1.5-.6s1.1.2 1.5.6c.3.4.5.9.5 1.5zm-2.7 0c0 .4.1.7.3 1 .2.3.5.4.8.4s.6-.1.8-.4c.2-.3.3-.6.3-1s-.1-.7-.3-1c-.2-.3-.5-.4-.8-.4s-.6.1-.8.4c-.2.3-.3.6-.3 1zM22.1 4.7h-1.4v6.2h1.4V4.7zM25.7 8.3c0 .6-.2 1.1-.5 1.5-.4.4-.9.6-1.5.6s-1.1-.2-1.5-.6c-.4-.4-.6-.9-.6-1.5s.2-1.1.6-1.5c.4-.4.9-.6 1.5-.6s1.1.2 1.5.6c.3.4.5.9.5 1.5zm-2.7 0c0 .4.1.7.3 1 .2.3.5.4.8.4s.6-.1.8-.4c.2-.3.3-.6.3-1s-.1-.7-.3-1c-.2-.3-.5-.4-.8-.4s-.6.1-.8.4c-.2.3-.3.6-.3 1zM30.2 4.7h-1.4v6.2h1.4V4.7z" fill="#697386"/>
                                <path d="M34.6 16.9c-.1.3-.3.5-.5.7l-2.7 2.7-2.7-2.7c-.2-.2-.4-.4-.5-.7-.1-.3 0-.6.2-.8.2-.2.5-.3.8-.2.3.1.5.3.7.5l1.3 1.3v-6.3h1.4v6.3l1.3-1.3c.2-.2.4-.4.7-.5.3-.1.6 0 .8.2.2.2.3.5.2.8z" fill="#24B47E"/>
                            </svg>
                            <span class="text-xs text-gray-500">Secure SSL Encryption</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { loadStripe } from '@stripe/stripe-js';
import api from "../../../axios/Axios";

const stripeStyle = {
    base: {
        color: '#1a1f36',
        fontFamily: 'system-ui, -apple-system, sans-serif',
        fontSize: '16px',
        '::placeholder': {
            color: '#6b7280'
        }
    },
    invalid: {
        color: '#dc2626'
    }
};

export default {
    name: "StripePayment",
    props: {
        selectedItems: { type: Array, required: true },
        showPaymentCard: { type: Boolean, required: true },
    },
    emits: ["close-modal", "payment-success"],
    data() {
        return {
            stripe: null,
            card: null,
            cardholderName: '',
            total: 0,
            loading: false,
            formError: null,
        };
    },
    watch: {
        selectedItems: {
            handler() { this.calculateTotal(); },
            deep: true,
        },
        showPaymentCard(newValue) {
            if (newValue) {
                this.initializeStripe();
            }
        },
    },
    async mounted() {
        this.calculateTotal();
        await this.$nextTick();
        if (this.showPaymentCard) {
            this.initializeStripe();
        }
    },
    methods: {
        async initializeStripe() {
            if (!this.stripe) {
                this.stripe = await loadStripe("pk_test_51Pi4i22N83kttsNCYZG4bxJ3NQlMxCNDtBBaAJsDO81Uzo23s7doQ9NzSUlTObFx9doP0C6N52VmcfqwXSK6tBQ000xRKbD3Iu");
            }
            
            const elements = this.stripe.elements();
            if (this.card) this.card.destroy();
            
            this.card = elements.create("card", { style: stripeStyle });
            await this.$nextTick();
            
            const cardElement = document.getElementById("card-element");
            if (cardElement && !cardElement.children.length) {
                this.card.mount(cardElement);
            }
        },
        calculateTotal() {
            this.total = this.selectedItems.reduce((sum, item) => sum + item.price * item.quantity, 0);
        },
        async handleSubmit() {
            if (this.loading) return;
            this.loading = true;
            this.formError = null;

            const { paymentMethod, error } = await this.stripe.createPaymentMethod({
                type: "card",
                card: this.card,
                billing_details: { name: this.cardholderName },
            });

            if (error) {
                this.formError = error.message;
                this.loading = false;
                return;
            }

            try {
                const response = await api.post('/stripe', {
                    paymentMethodId: paymentMethod.id,
                    amount: this.total,
                    description: "Order Payment",
                });

                if (response.data.requires_action) {
                    const { paymentIntent, error: confirmError } = await this.stripe.confirmCardPayment(
                        response.data.payment_intent_client_secret
                    );

                    if (confirmError) throw confirmError;
                    if (paymentIntent.status !== "succeeded") throw new Error("Payment not completed");
                }

                await this.handleOrder();
                this.$emit("payment-success");
                this.closeModal();
            } catch (err) {
                this.formError = err.message || "Payment failed. Please try again.";
                console.error("Payment error:", err);
            } finally {
                this.loading = false;
            }
        },
        async handleOrder() {
            try {
                const userId = sessionStorage.getItem("id");
                const token = sessionStorage.getItem("auth_token");
                
                const orderPayload = {
                    user_id: parseInt(userId, 10),
                    amount: this.total,
                    payment_type: "credit_card",
                    items: this.selectedItems.map(item => ({
                        product_id: item.id,
                        product_name: item.name,
                        quantity: item.quantity,
                        amount: item.price * item.quantity,
                        size: item.size || null,
                    }))
                };

                await api.post("/orders", orderPayload, {
                    headers: { Authorization: `Bearer ${token}` }
                });
                
            } catch (error) {
                console.error("Order creation failed:", error);
                throw new Error("Failed to create order record");
            }
        },
        closeModal() {
            this.card?.unmount();
            this.$emit("close-modal");
        },
        formatPrice(value) {
            return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(value);
        },
    },
};
</script>