<template>
  <div class="container">
    <div class="header">
      <h1><i class="fas fa-credit-card"></i> Payment Management</h1>
      <p>Manage your payments with ease</p>
    </div>

    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-value">${{ totalAmount.toFixed(2) }}</div>
        <div class="stat-label">Total Amount</div>
      </div>
      <div class="stat-card">
        <div class="stat-value">{{ completedPayments }}</div>
        <div class="stat-label">Completed</div>
      </div>
      <div class="stat-card">
        <div class="stat-value">{{ pendingPayments }}</div>
        <div class="stat-label">Pending</div>
      </div>
      <div class="stat-card">
        <div class="stat-value">{{ totalPayments }}</div>
        <div class="stat-label">Total Payments</div>
      </div>
    </div>

    <div v-if="alert.show" :class="['alert', alert.type]">
      <i :class="alert.type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-triangle'"></i>
      {{ alert.message }}
    </div>

    <div class="payment-grid">
      <div class="card">
        <div class="card-header">
          <i class="fas fa-plus-circle"></i>
          <h2>{{ editingPayment ? 'Edit Payment' : 'Create Payment' }}</h2>
        </div>

        <form @submit.prevent="submitPayment">
          <div class="form-group">
            <label for="order_id">Order ID</label>
            <input type="number" id="order_id" v-model="paymentForm.order_id" required>
          </div>

          <div class="form-group">
            <label for="payment_method">Payment Method</label>
            <select id="payment_method" v-model="paymentForm.payment_method" required>
              <option value="">Select Method</option>
              <option value="cash">Cash</option>
              <option value="credit_card">Credit Card</option>
              <option value="scan">Scan</option>
            </select>
          </div>

          <div class="form-group">
            <label for="amount_paid">Amount Paid</label>
            <input type="number" step="0.01" id="amount_paid" v-model="paymentForm.amount_paid" required>
          </div>

          <div class="form-group">
            <label for="status">Status</label>
            <select id="status" v-model="paymentForm.status" required>
              <option value="pending">Pending</option>
              <option value="completed">Completed</option>
              <option value="failed">Failed</option>
            </select>
          </div>

          <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn" :disabled="loading">
              <i class="fas fa-save"></i>
              {{ editingPayment ? 'Update' : 'Create' }} Payment
            </button>
            <button v-if="editingPayment" type="button" class="btn btn-danger" @click="cancelEdit">
              <i class="fas fa-times"></i>
              Cancel
            </button>
          </div>
        </form>
      </div>

      <div class="card">
        <div class="card-header">
          <i class="fas fa-list"></i>
          <h2>Payment History</h2>
          <button class="btn" @click="fetchPayments" style="margin-left: auto;">
            <i class="fas fa-refresh"></i>
            Refresh
          </button>
        </div>

        <div v-if="loading" class="loading">
          <div class="spinner"></div>
        </div>

        <div v-else class="payment-list">
          <div v-for="payment in payments" :key="payment.payment_id" class="payment-item" @click="editPayment(payment)">
            <div class="payment-header">
              <span class="payment-id">#{{ payment.payment_id }}</span>
              <span class="payment-amount">${{ parseFloat(payment.amount_paid).toFixed(2) }}</span>
            </div>

            <div class="payment-details">
              <div class="detail-item">
                <i class="fas fa-shopping-cart"></i>
                Order: #{{ payment.order_id }}
              </div>
              <div class="detail-item">
                <i class="fas fa-credit-card"></i>
                {{ payment.payment_method }}
              </div>
              <div class="detail-item">
                <i class="fas fa-calendar"></i>
                {{ formatDate(payment.paid_at) }}
              </div>
              <div class="detail-item">
                <span :class="['status', payment.status]">{{ payment.status }}</span>
              </div>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 15px;">
              <button class="btn btn-success" @click.stop="markAsCompleted(payment)" v-if="payment.status === 'pending'">
                <i class="fas fa-check"></i>
                Complete
              </button>
              <button class="btn btn-danger" @click.stop="deletePayment(payment.payment_id)">
                <i class="fas fa-trash"></i>
                Delete
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';

const payments = ref([]);
const loading = ref(false);
const editingPayment = ref(null);
const alert = ref({
  show: false,
  type: '',
  message: ''
});
const paymentForm = ref({
  order_id: '',
  payment_method: '',
  amount_paid: '',
  status: 'pending'
});

const totalAmount = computed(() => {
  return payments.value.reduce((sum, payment) => sum + parseFloat(payment.amount_paid), 0);
});
const completedPayments = computed(() => {
  return payments.value.filter(p => p.status === 'completed').length;
});
const pendingPayments = computed(() => {
  return payments.value.filter(p => p.status === 'pending').length;
});
const totalPayments = computed(() => {
  return payments.value.length;
});

// Fetch payments from the Laravel API
const fetchPayments = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/payments');
    payments.value = response.data;
  } catch (error) {
    showAlert('error', 'Failed to fetch payments');
    console.error('Error fetching payments:', error);
  } finally {
    loading.value = false;
  }
};

// Submit a new payment or update an existing one
const submitPayment = async () => {
  loading.value = true;
  try {
    if (editingPayment.value) {
      await axios.put(`/api/payments/${editingPayment.value.payment_id}`, paymentForm.value);
      showAlert('success', 'Payment updated successfully!');
    } else {
      await axios.post('/api/payments', paymentForm.value);
      showAlert('success', 'Payment created successfully!');
    }
    await fetchPayments();
    resetForm();
  } catch (error) {
    showAlert('error', 'Failed to save payment');
    console.error('Error saving payment:', error);
  } finally {
    loading.value = false;
  }
};

// Set the form for editing a payment
const editPayment = (payment) => {
  editingPayment.value = payment;
  paymentForm.value = { ...payment };
};

// Cancel editing and reset the form
const cancelEdit = () => {
  editingPayment.value = null;
  resetForm();
};

// Mark a payment as completed
const markAsCompleted = async (payment) => {
  try {
    await axios.put(`/api/payments/${payment.payment_id}`, { ...payment, status: 'completed' });
    showAlert('success', 'Payment marked as completed!');
    await fetchPayments();
  } catch (error) {
    showAlert('error', 'Failed to update payment status');
    console.error('Error updating payment:', error);
  }
};

// Delete a payment
const deletePayment = async (paymentId) => {
  if (confirm('Are you sure you want to delete this payment?')) {
    try {
      await axios.delete(`/api/payments/${paymentId}`);
      showAlert('success', 'Payment deleted successfully!');
      await fetchPayments();
    } catch (error) {
      showAlert('error', 'Failed to delete payment');
      console.error('Error deleting payment:', error);
    }
  }
};

// Reset the payment form
const resetForm = () => {
  paymentForm.value = {
    order_id: '',
    payment_method: '',
    amount_paid: '',
    status: 'pending'
  };
  editingPayment.value = null;
};

// Display an alert message
const showAlert = (type, message) => {
  alert.value = { show: true, type, message };
  setTimeout(() => {
    alert.value.show = false;
  }, 5000);
};

// Format the date for display
const formatDate = (dateString) => {
  if (!dateString) return 'N/A';
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};

onMounted(() => {
  fetchPayments();
});
</script>

<style scoped>
/*
  The original CSS can be copied here.
  Using scoped CSS ensures the styles only apply to this component.
*/
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    color: #333;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.header {
    text-align: center;
    margin-bottom: 30px;
    color: white;
}

.header h1 {
    font-size: 2.5rem;
    margin-bottom: 10px;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

.payment-grid {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 30px;
    margin-bottom: 30px;
}

@media (max-width: 768px) {
    .payment-grid {
        grid-template-columns: 1fr;
    }
}

.card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
}

.card-header {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #f0f0f0;
}

.card-header i {
    font-size: 1.5rem;
    margin-right: 10px;
    color: #667eea;
}

.card-header h2 {
    color: #333;
    font-size: 1.3rem;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #555;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e1e5e9;
    border-radius: 10px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.btn {
    background: linear-gradient(45deg, #667eea, #764ba2);
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 10px;
    cursor: pointer;
    font-size: 16px;
    font-weight: 600;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

.btn-danger {
    background: linear-gradient(45deg, #ff6b6b, #ee5a52);
}

.btn-success {
    background: linear-gradient(45deg, #51cf66, #40c057);
}

.payment-list {
    max-height: 600px;
    overflow-y: auto;
}

.payment-item {
    background: linear-gradient(145deg, #f8f9ff, #ffffff);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 15px;
    border-left: 4px solid #667eea;
    transition: all 0.3s ease;
    cursor: pointer;
}

.payment-item:hover {
    transform: translateX(5px);
    box-shadow: 0 5px 20px rgba(102, 126, 234, 0.15);
}

.payment-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.payment-id {
    font-weight: bold;
    color: #667eea;
    font-size: 1.1rem;
}

.payment-amount {
    font-size: 1.2rem;
    font-weight: bold;
    color: #2d3748;
}

.payment-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 10px;
    margin-top: 10px;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.9rem;
    color: #666;
}

.status {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status.pending {
    background: #fff3cd;
    color: #856404;
}

.status.completed {
    background: #d4edda;
    color: #155724;
}

.status.failed {
    background: #f8d7da;
    color: #721c24;
}

.loading {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 40px;
}

.spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid #667eea;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.alert {
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.alert.success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert.error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    padding: 20px;
    text-align: center;
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.stat-value {
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.9;
}
</style>