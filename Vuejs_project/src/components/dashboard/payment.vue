<template>
  <div class="checkout-payment">
    <!-- Amount input -->
    <div class="mb-4">
      <input 
        v-model="amount" 
        type="number" 
        placeholder="Amount (KHR)" 
        class="border px-3 py-2 rounded-md w-full"
      />
    </div>

    <!-- Generate QR Button -->
    <button 
      @click="generateQr" 
      class="px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700"
    >
      Pay with KHQR
    </button>

    <!-- Show QR -->
    <div v-if="qrPayload" class="mt-6 text-center">
      <qrcode-vue :value="qrPayload" :size="200" /> 
      <!-- qrcode-vue is a Vue component (npm install qrcode.vue) -->
      <p class="mt-2 text-sm text-gray-600">Expires at: {{ expiresAt }}</p>
    </div>

    <!-- Status -->
    <div v-if="statusChecking" class="mt-4 text-center">
      <p class="font-semibold">Status: {{ status }}</p>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import QrcodeVue from 'qrcode.vue';

export default {
  components: { QrcodeVue },
  data() {
    return {
      amount: null,
      currency: 'KHR',
      qrPayload: null,
      hash: null,
      expiresAt: null,
      status: null,
      statusChecking: false,
      pollInterval: null,
    };
  },
  methods: {
    async generateQr() {
      if (!this.amount || this.amount <= 0) {
        alert("Please enter a valid amount");
        return;
      }
      try {
        const resp = await axios.post('/api/bakong/qr', {
          amount: this.amount,
          currency: this.currency,
          city: 'Phnom Penh',
        });

        this.qrPayload = resp.data.qr_payload;
        this.hash = resp.data.hash;
        this.expiresAt = new Date(resp.data.expires_at * 1000);
        this.status = 'Waiting for payment';
        this.statusChecking = true;
        this.startPolling();
      } catch (err) {
        console.error('Failed to generate QR:', err);
        alert('Cannot generate QR: ' + (err.response?.data?.message || err.message));
      }
    },
    async checkStatusOnce() {
      if (!this.hash) return;
      try {
        const resp = await axios.get(`/api/bakong/status/${this.hash}`);
        if (resp.data.paid) {
          this.status = '✅ Payment Confirmed';
          this.stopPolling();
          // TODO: trigger backend order confirmation
        } else {
          this.status = '⏳ Not yet paid';
        }
      } catch (err) {
        console.error('Status check failed:', err);
      }
    },
    startPolling() {
      this.pollInterval = setInterval(() => {
        this.checkStatusOnce();
      }, 3000); // poll every 3 sec
    },
    stopPolling() {
      clearInterval(this.pollInterval);
      this.statusChecking = false;
    }
  },
  beforeUnmount() {
    this.stopPolling();
  }
};
</script>
