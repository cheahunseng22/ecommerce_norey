<template>
  <div class="p-6 space-y-4">
    <div class="flex justify-between items-center">
      <h2 class="text-2xl font-bold">Sales Report</h2>
      <input type="date" v-model="dateStart" />
      <input type="date" v-model="dateEnd" />
      <button @click="fetchData" class="btn bg-blue-500 text-white px-4 py-2 rounded">Filter</button>
    </div>

    <div class="grid grid-cols-4 gap-4">
      <div class="bg-white p-4 shadow rounded">Total Sales: ${{ report.total_sales }}</div>
      <div class="bg-white p-4 shadow rounded">Orders: {{ report.order_count }}</div>
      <div class="bg-white p-4 shadow rounded">Items Sold: {{ report.items_sold }}</div>
      <div class="bg-white p-4 shadow rounded">Avg. Order: ${{ report.average_order }}</div>
    </div>

    <div>
      <SalesChart :data="report.sales" />
    </div>

    <div class="mt-4">
      <table class="min-w-full bg-white">
        <thead>
          <tr>
            <th class="p-2">Date</th>
            <th class="p-2">Customer</th>
            <th class="p-2">Total</th>
            <th class="p-2">Items</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="sale in report.sales" :key="sale.id">
            <td class="p-2">{{ sale.created_at }}</td>
            <td class="p-2">{{ sale.customer_name }}</td>
            <td class="p-2">${{ sale.total_amount }}</td>
            <td class="p-2">{{ sale.items_count }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
// /

const report = ref({})
const dateStart = ref('')
const dateEnd = ref('')

const fetchData = async () => {
  const res = await axios.get('/api/reports/sales', {
    params: { start: dateStart.value, end: dateEnd.value }
  })
  report.value = res.data
}

onMounted(fetchData)
</script>
