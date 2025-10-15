<template>
  <div class="min-h-screen bg-gradient-to-b from-gray-100 to-gray-50">
    <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 py-8 h-screen flex flex-col">
      <!-- Header Section -->
      <header class="mb-8">
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
          <div class="flex items-center justify-between mb-4">
            <div>
              <h1 class="text-4xl font-bold text-gray-900 mb-2">Kitchen Dashboard</h1>
              <div class="flex items-center text-gray-600">
                <i class="fas fa-clock text-lg mr-2"></i>
                <p class="text-lg">Real-time order management system</p>
              </div>
            </div>
            <div class="text-right text-gray-600">
              <p class="text-sm">{{ currentDate }}</p>
              <p class="text-lg font-medium">{{ currentTime }}</p>
            </div>
          </div>
        </div>
      </header>

      <!-- Tabs Navigation -->
      <nav class="bg-white rounded-xl shadow-sm mb-6 p-2 border border-gray-100">
        <div class="flex space-x-2">
          <button v-for="tab in tabs" :key="tab" @click="activeTab = tab" :class="{
            'bg-indigo-500 text-white shadow-md': activeTab === tab,
            'text-gray-600 hover:bg-gray-50': activeTab !== tab
          }" class="px-6 py-3 rounded-lg font-semibold text-sm transition-all duration-200 transform hover:scale-95">
            {{ tab }}
            <span v-if="activeTab === tab" class="ml-2 bg-white/20 px-2 py-1 rounded-full text-xs">
              {{ tabCounts[tab.toLowerCase()] }}
            </span>
          </button>
        </div>
      </nav>

      <!-- Orders Grid -->
      <div class="flex-1 overflow-y-auto pr-2">
        <transition-group name="list" tag="div" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
          <!-- Order Card -->
          <div v-for="order in filteredOrders" :key="order.id"
            class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100">

            <!-- Card Header -->
            <div class="p-6 border-b border-gray-100 relative">
              <div class="absolute top-4 right-4">
                <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-gray-100 text-gray-600">
                  T{{ order.user_id }}
                </span>
              </div>
              <h3 class="text-2xl font-bold text-gray-900 mb-2">#{{ order.id }}</h3>
              <div class="flex items-center space-x-2">
                <span :class="statusClasses(order)"
                  class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium">
                  <i :class="statusIcons[normalizeStatus(order.status)]" class="mr-2"></i>
                  {{ normalizeStatus(order.status) }}
                </span>
                <span class="text-sm text-gray-500">{{ orderTimestamp(order) }}</span>
              </div>
            </div>

            <!-- Order Items -->
            <div class="p-6">
              <div class="mb-4">
                <div class="flex items-center mb-4 text-gray-600">
                  <i class="fas fa-utensils mr-2"></i>
                  <span class="font-medium">{{ order.items.length }} items</span>
                </div>
                <div class="space-y-3">
                  <div v-for="item in order.items" :key="item.product_id"
                    class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                      <p class="font-medium text-gray-900">{{ item.product_name }}</p>
                    </div>
                    <div>
                      <p class="text-sm text-gray-500">{{ item.size }}</p>
                    </div>
                    <span class="bg-white px-3 py-1 rounded-full shadow-sm text-gray-900 font-medium">
                      ×{{ item.quantity }}
                    </span>
                  </div>
                </div>
              </div>

              <!-- Action Button -->
              <button v-if="normalizeStatus(order.status) !== 'completed'" @click="nextStatus(order)"
                :class="actionButtonClasses(order)"
                class="w-full py-3 px-6 rounded-xl font-bold text-white transition-all duration-200 transform hover:scale-[0.98]">
                <i :class="actionIcons[normalizeStatus(order.status)]" class="mr-2"></i>
                {{ getButtonLabel(order.status) }}
              </button>
            </div>
          </div>
        </transition-group>

        <!-- Empty State -->
        <div v-if="filteredOrders.length === 0" class="h-full flex items-center justify-center py-12">
          <div class="text-center max-w-md">
            <div class="mb-6 text-6xl text-gray-300">
              <i class="fas fa-concierge-bell"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-500 mb-2">No orders available</h3>
            <p class="text-gray-400">New orders will appear here in real-time</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import api from '../../axios/Axios';
import { echo } from '../../services/echo';

export default {
  data() {
    return {
      tabs: ["All", "Pending", "Accepted", "Preparing", "Completed"],
      activeTab: "All",
      orders: [],
      statusIcons: {
        pending: "fas fa-clock",
        accepted: "fas fa-check-circle",
        preparing: "fas fa-blender",
        completed: "fas fa-check-double",
      },
      actionIcons: {
        pending: "fas fa-check",
        accepted: "fas fa-utensil-spoon",
        preparing: "fas fa-check-double",
      },
      currentTime: '',
      currentDate: '',
    };
  },
  computed: {
    filteredOrders() {
      if (this.activeTab === "All") {
        return this.orders.filter(
          (o) => this.normalizeStatus(o.status) !== "completed"
        );
      }
      return this.orders.filter(
        (o) => this.normalizeStatus(o.status) === this.activeTab.toLowerCase()
      );
    },
    tabCounts() {
      return {
        all: this.orders.filter((o) => this.normalizeStatus(o.status) !== "completed").length,
        pending: this.orders.filter((o) => this.normalizeStatus(o.status) === "pending").length,
        accepted: this.orders.filter((o) => this.normalizeStatus(o.status) === "accepted").length,
        preparing: this.orders.filter((o) => this.normalizeStatus(o.status) === "preparing").length,
        completed: this.orders.filter((o) => this.normalizeStatus(o.status) === "completed").length,
      };
    },
  },
  watch: {
    activeTab() {
      this.$nextTick(() => {
        this.$refs.scrollContainer?.scrollTo({ top: 0, behavior: "smooth" });
      });
    },
  },
  mounted() {
    this.updateTime();
    setInterval(this.updateTime, 1000);
    this.fetchKitchenOrders();
    this.listenOrderToKitchen();
    this.listenCardToKitchen();
    this.listenForCallRobot();
  },
  methods: {
    updateTime() {
      const now = new Date();
      this.currentTime = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
      this.currentDate = now.toLocaleDateString([], { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
    },
    normalizeStatus(status) {
      return status ? status.toLowerCase() : "";
    },
    statusClasses(order) {
      const status = this.normalizeStatus(order.status);
      return {
        pending: "bg-blue-100 text-blue-800",
        accepted: "bg-purple-100 text-purple-800",
        preparing: "bg-amber-100 text-amber-800",
        completed: "bg-green-100 text-green-800",
      }[status];
    },
    actionButtonClasses(order) {
      const status = this.normalizeStatus(order.status);
      return {
        pending: "bg-gradient-to-r from-blue-500 to-blue-600",
        accepted: "bg-gradient-to-r from-purple-500 to-purple-600",
        preparing: "bg-gradient-to-r from-amber-500 to-amber-600",
      }[status];
    },
    orderTimestamp(order) {
      return new Date(order.created_at).toLocaleTimeString([], {
        hour: "2-digit",
        minute: "2-digit",
      });
    },
    getButtonLabel(status) {
      const normalized = this.normalizeStatus(status);
      return {
        pending: "Accept",
        accepted: "Preparing",
        preparing: "Complete",
      }[normalized] || "";
    },
    nextStatus(order) {
      const current = this.normalizeStatus(order.status);
      const next = {
        pending: "accepted",
        accepted: "preparing",
        preparing: "completed",
      }[current];
      if (next) this.updateStatus(order.id, next);
    },
    async fetchKitchenOrders() {
      try {
        const token = sessionStorage.getItem("auth_token");
        const response = await api.get("/kitchen/orders", {
          headers: { Authorization: `Bearer ${token}` },
        });
        this.orders = response.data;
      } catch (error) {
        console.error("Error fetching orders:", error);
      }
    },
    async updateStatus(id, newStatus) {
      try {
        const token = sessionStorage.getItem("auth_token");
        const response = await api.put(
          `/kitchen/orders/${id}/status`,
          { status: newStatus },
          {
            headers: {
              "Content-Type": "application/json",
              Authorization: `Bearer ${token}`,
            },
          }
        );

        if (newStatus === "completed") {
          this.orders = this.orders.filter((o) => o.id !== id);
        } else {
          const index = this.orders.findIndex((o) => o.id === id);
          if (index !== -1) this.orders[index].status = newStatus;
        }
      } catch (error) {
        console.error("Error updating status:", error.response?.data || error);
      }
    },
    listenOrderToKitchen() {
      echo.channel("kitchen-orders").listen("OrderSentToKitchen", (event) => {
        if (!this.orders.find((o) => o.id === event.order.id)) {
          this.orders.unshift(event.order);
          this.scrollToTop();
        }
      });
    },
    listenCardToKitchen() {
      echo.channel("Card-Kitchen").listen("CreditCardToKitchen", (event) => {
        if (!this.orders.find((o) => o.id === event.order.id)) {
          this.orders.unshift(event.order);
          this.scrollToTop();
        }
      });
    },
    listenForCallRobot() {
      echo.channel("robot-channel").listen("EventForRobot", (event) => {
        console.log("Robot Event Received:", event.robot);
      });
    },
    scrollToTop() {
      this.$nextTick(() => {
        this.$refs.scrollContainer?.scrollTo({ top: 0, behavior: "smooth" });
      });
    },
  },
};
</script>

<style>
.list-enter-active,
.list-leave-active {
  transition: all 0.4s ease;
}

.list-enter-from,
.list-leave-to {
  opacity: 0;
  transform: translateY(30px);
}

::-webkit-scrollbar {
  width: 10px;
  background: transparent;
}

::-webkit-scrollbar-thumb {
  background: rgba(203, 213, 225, 0.5);
  border-radius: 8px;
  border: 2px solid white;
}

::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}

.fa-utensil-spoon {
  transform: rotate(-45deg);
}
</style>