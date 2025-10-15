<template>
  <div class="p-6">
    <!-- Title -->
    <h1 class="text-2xl font-bold mb-2">List</h1>
    <nav class="text-sm text-gray-500 mb-6">Dashboard • Order • List</nav>

    <!-- Filter Tabs -->
    <div class="flex items-center space-x-2 mb-4">
      <button
        v-for="tab in tabs"
        :key="tab.label"
        @click="activeTab = tab.status"
        :class="[
          'px-4 py-2 rounded-full border',
          activeTab === tab.status
            ? 'bg-black text-white'
            : 'bg-white text-gray-700 border-gray-300'
        ]"
      >
        {{ tab.label }}
        <span class="ml-1 text-sm text-gray-500">({{ tab.count }})</span>
      </button>
    </div>

    <!-- Date + Search -->
    <div class="flex items-center space-x-2 mb-4">
      <input
        type="date"
        v-model="startDate"
        class="border rounded-lg px-3 py-2 text-sm w-40"
      />
      <input
        type="date"
        v-model="endDate"
        class="border rounded-lg px-3 py-2 text-sm w-40"
      />
      <input
        type="text"
        v-model="search"
        placeholder="Search customer or order number..."
        class="flex-1 border rounded-lg px-3 py-2 text-sm"
      />
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-xl shadow">
      <table class="w-full text-sm">
        <thead>
          <tr class="text-left border-b">
            <th class="p-3">Order</th>
            <th class="p-3">Customer</th>
            <th class="p-3">Date</th>
            <th class="p-3">Items</th>
            <th class="p-3">Price</th>
            <th class="p-3">Status</th>
            <th class="p-3"></th>
          </tr>
        </thead>
        <tbody>
          <template v-for="order in filteredOrders" :key="order.order_id">
            <!-- Order Row -->
            <tr class="border-b hover:bg-gray-50">
              <td class="p-3 text-blue-500 font-medium">#{{ order.order_id }}</td>
              <td class="p-3 flex items-center space-x-3">
                <img
                  :src="order.customer.avatar"
                  alt="avatar"
                  class="w-8 h-8 rounded-full"
                />
                <div>
                  <div class="font-medium">{{ order.customer.name }}</div>
                  <div class="text-xs text-gray-500">{{ order.customer.email }}</div>
                </div>
              </td>
              <td class="p-3">
                <div>{{ formatDate(order.created_at) }}</div>
                <div class="text-xs text-gray-500">{{ formatTime(order.created_at) }}</div>
              </td>
              <td class="p-3">{{ order.items.length }}</td>
              <td class="p-3">${{ order.total_amount.toFixed(2) }}</td>
              <td class="p-3">{{ order.status }}</td>
              <td class="p-3 text-right">
                <button class="text-red-500 hover:underline">Delete</button>
              </td>
            </tr>

            <!-- Product Rows -->
            <tr
              v-for="item in order.items"
              :key="item.sku"
              class="border-b hover:bg-gray-50"
            >
              <td class="p-3 pl-12" colspan="2">
                <div class="flex items-center space-x-3">
                  <img :src="item.image" class="w-10 h-10 rounded" />
                  <div>
                    <div>{{ item.name }}</div>
                    <div class="text-xs text-gray-500">{{ item.sku }}</div>
                  </div>
                </div>
              </td>
              <td></td>
              <td class="p-3">x{{ item.qty }}</td>
              <td class="p-3">${{ item.price.toFixed(2) }}</td>
              <td></td>
              <td></td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      tabs: [
        { label: "All", status: "all", count: 20 },
        { label: "Pending", status: "pending", count: 6 },
        { label: "Completed", status: "completed", count: 10 },
        { label: "Cancelled", status: "cancelled", count: 2 },
        { label: "Refunded", status: "refunded", count: 2 },
      ],
      activeTab: "all",
      startDate: "",
      endDate: "",
      search: "",
      orders: [],
    };
  },
  computed: {
    filteredOrders() {
      return this.orders.filter((o) => {
        const matchStatus =
          this.activeTab === "all" || o.status === this.activeTab;
        const matchSearch =
          o.customer.name.toLowerCase().includes(this.search.toLowerCase()) ||
          o.order_id.toString().includes(this.search);
        return matchStatus && matchSearch;
      });
    },
  },
  methods: {
    async fetchOrders() {
      const res = await fetch("https://api.example.com/orders");
      this.orders = await res.json();
    },
    formatDate(date) {
      return new Date(date).toLocaleDateString("en-GB", {
        day: "2-digit",
        month: "short",
        year: "numeric",
      });
    },
    formatTime(date) {
      return new Date(date).toLocaleTimeString([], {
        hour: "2-digit",
        minute: "2-digit",
      });
    },
  },
  mounted() {
    this.fetchOrders();
  },
};
</script>

<style>
body {
  font-family: 'Inter', sans-serif;
}
</style>
