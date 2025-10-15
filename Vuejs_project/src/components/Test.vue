<template>
    <div>
        <button @click="payNow" :disabled="isLoading">
            <span v-if="isLoading">Processing...</span>
            <span v-else>Pay with ABA Payway</span>
        </button>
    </div>
</template>

<script>
import api from '../axios/Axios';

export default {
    props: {
        amount: { type: Number, required: true }
    },
    data() {
        return {
            isLoading: false,
        };
    },
    methods: {
        async payNow() {
            this.isLoading = true;
            try {
                const token = sessionStorage.getItem("auth_token");
                const response = await api.post(`/payment/checkout`, { amount: this.amount }, {
                    headers: { Authorization: `bearer ${token}` }
                });
                window.location.href = response.data.url;
            } catch (error) {
                console.error('payment error: ', error);
                this.$emit('payment-error', "An error occurred during payment."); // Or use improved handling
            } finally {
                this.isLoading = false; // Always set isLoading back to false
            }
        }
    }
}
</script>