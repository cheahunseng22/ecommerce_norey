<template>
  <transition name="fade">
    <div v-if="showStatus" class="fixed inset-0 z-50 flex items-center justify-center p-4 backdrop-blur-sm">
      <!-- Modal Container - Larger size with max-w-2xl -->
      <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden transition-all duration-300">
        <!-- Close Button -->
        <button 
          @click="closeModal"
          class="absolute top-6 right-6 p-2 rounded-full hover:bg-gray-100 transition-colors text-gray-500 hover:text-gray-700"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>

        <!-- Header - Larger padding and text -->
        <div class="px-8 pt-8 pb-6 border-b border-gray-100">
          <h2 class="text-2xl font-semibold text-gray-800">Order Status</h2>
          <p class="text-base text-gray-500 mt-2">Track your order progress</p>
        </div>

        <!-- Progress Steps - Larger elements -->
        <div class="p-8">
          <div class="relative">
            <!-- Progress Line - Thicker line -->
            <div class="absolute left-8 top-5 h-2 w-[calc(100%-64px)] bg-gray-200 rounded-full">
              <div 
                class="h-full bg-emerald-500 rounded-full transition-all duration-500 ease-out"
                :style="{ width: `${(currentStep / (steps.length - 1)) * 100}%` }"
              ></div>
            </div>

            <!-- Steps - Larger circles and text -->
            <div class="flex justify-between">
              <div 
                v-for="(step, index) in steps" 
                :key="index"
                class="flex flex-col items-center z-10"
              >
                <div 
                  :class="[
                    'w-12 h-12 rounded-full flex items-center justify-center transition-colors text-lg',
                    index <= currentStep ? 'bg-emerald-500 text-white' : 'bg-gray-200 text-gray-400'
                  ]"
                >
                  <template v-if="index < currentStep">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                  </template>
                  <template v-else>
                    {{ index + 1 }}
                  </template>
                </div>
                <span 
                  :class="[
                    'text-sm mt-3 font-medium text-center max-w-[100px]',
                    index <= currentStep ? 'text-emerald-600' : 'text-gray-400'
                  ]"
                >
                  {{ step.label }}
                </span>
              </div>
            </div>
          </div>

          <!-- Status Message - Larger with more padding -->
          <div 
            v-if="resultMessage"
            class="mt-10 p-5 rounded-lg bg-emerald-50 border border-emerald-100 text-emerald-700 text-base"
          >
            <div class="flex items-start">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 mt-0.5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
              </svg>
              <p>{{ resultMessage }}</p>
            </div>
          </div>
        </div>

        <!-- Actions - Larger button -->
        <div class="px-8 pb-8 flex justify-end">
          <button
            @click="closeModal"
            class="px-6 py-3 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg font-medium text-base transition-colors"
          >
            Close
          </button>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
export default {
    name: "Status",
    props: {
        showStatus: Boolean,
        resultMessage: String
    },
    data() {
        return {
            steps: [
                { label: "Free", active: true },
                { label: "Cashier Approve", active: false },
                { label: "At Kitchen", active: false },
                { label: "Cooking", active: false },
                { label: "Preparing", active: false },
                { label: "Ready", active: false }
            ],
            currentStep: 0
        };
    },
    computed: {
        isReady() {
            return this.currentStep === this.steps.length - 1;
        }
    },
    methods: {
        moveToStep(label) {
            const index = this.steps.findIndex(s => s.label === label);
            if (index !== -1) {
                this.currentStep = index;
                this.steps.forEach((s, i) => s.active = i <= index);
            }
        },
        closeModal() {
            this.$emit('close-modal');
        }
    }
};
</script>

<style scoped>
button:disabled {
    background-color: #ccc;
    cursor: not-allowed;
}
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s;
}
.fade-enter, .fade-leave-to {
  opacity: 0;
}
</style>
