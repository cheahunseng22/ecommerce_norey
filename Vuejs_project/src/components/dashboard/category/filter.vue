<template>
  <div class="filter-popup-container">
    <!-- The filter popup content itself -->
    <div class="filter1 bg-white w-[516px] max-w-[90vw] h-full shadow-xl flex flex-col relative overflow-y-auto">
      <div class="p-6 flex justify-between items-center border-b">
        <b class="filter2 text-2xl font-semibold text-gray-800">Filter</b>
        <button @click="closeFilterPopup" class="text-gray-500 hover:text-gray-800">
          <svg class="vector-icon w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
      </div>

      <div class="filter-inner flex-grow p-6 space-y-6">
        <!-- Sort by Section -->
        <div>
          <h3 class="sort-by text-lg font-semibold mb-3">Sort by</h3>
          <div class="sort-by1 space-y-2 text-sm">
            <label v-for="sort in sortByOptions" :key="sort.id" class="component-85-parent flex items-center space-x-3 cursor-pointer">
              <input type="radio" name="sort" :value="sort.id" class="form-radio text-pink-600 focus:ring-pink-500">
              <span class="one-size">{{ sort.label }}</span>
            </label>
          </div>
        </div>

        <!-- Size Section -->
        <div>
          <h3 class="size text-lg font-semibold mb-3">Size</h3>
          <div class="grid grid-cols-3 sm:grid-cols-4 gap-2 text-sm">
            <button v-for="s in sizes" :key="s" class="component-54 border border-gray-300 rounded py-1.5 px-2 hover:border-pink-500 hover:text-pink-500 focus:outline-none focus:border-pink-600 focus:ring-1 focus:ring-pink-600">
              {{ s }}
            </button>
          </div>
        </div>
        
        <!-- Price Range Section -->
        <div>
          <h3 class="price-range text-lg font-semibold mb-3">Price Range</h3>
          <div class="price-range1 relative pt-1">
            <input type="range" min="10" max="500" value="150" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-pink-600">
            <div class="flex justify-between text-xs text-gray-500 mt-1">
              <span>$10</span>
              <span>$500</span>
            </div>
          </div>
        </div>

        <!-- Color Section -->
        <div>
          <h3 class="color text-lg font-semibold mb-3">Color</h3>
          <div class="grid grid-cols-3 sm:grid-cols-4 gap-3 text-sm">
            <button v-for="c in colors" :key="c.name" class="color-option flex items-center space-x-2 border border-gray-300 rounded py-1.5 px-3 hover:border-pink-500 group">
              <span class="color-swatch w-4 h-4 rounded-sm border border-gray-300" :style="{ backgroundColor: c.hex }"></span>
              <span class="color-name group-hover:text-pink-500">{{ c.name }}</span>
            </button>
          </div>
        </div>

        <!-- Fit Section -->
        <div>
          <h3 class="fit text-lg font-semibold mb-3">Fit</h3>
          <div class="grid grid-cols-3 sm:grid-cols-4 gap-2 text-sm">
            <button v-for="f in fits" :key="f" class="component-72 border border-gray-300 rounded py-1.5 px-2 hover:border-pink-500 hover:text-pink-500 focus:outline-none focus:border-pink-600 focus:ring-1 focus:ring-600">
              {{ f }}
            </button>
          </div>
        </div>
      </div>

      <!-- Footer Buttons -->
      <div class="p-6 border-t mt-auto flex space-x-3">
        <button class="clear flex-1 py-2.5 px-4 border border-pink-700 text-pink-700 rounded-md hover:bg-pink-50 text-sm font-medium">
          CLEAR
        </button>
        <button class="apply flex-1 py-2.5 px-4 bg-pink-700 text-white rounded-md hover:bg-pink-800 text-sm font-medium" @click="applyFilters">
          APPLY
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
// This component assumes it's part of a parent that manages `showFilterPopup` state.
// You might emit events to the parent to open/close/apply filters.

const emit = defineEmits(['close', 'apply-filters']);

const closeFilterPopup = () => {
  emit('close'); // Emit a 'close' event to the parent
};

const applyFilters = () => {
    // In a real application, you'd gather selected filter values here
    // and then emit them to the parent component or dispatch to a store.
    console.log("Applying filters...");
    emit('apply-filters', { /* your filter data here */ }); // Emit an 'apply-filters' event
    closeFilterPopup();
}

// Data for Filter Options
const sortByOptions = ref([
  { id: 'new', label: 'New items' },
  { id: 'priceHigh', label: 'Price (High First)' },
  { id: 'priceLow', label: 'Price (Low First)' },
  { id: 'discount', label: 'Discount' },
]);

// Simplified sizes array
const sizes = ref(['One size', '24', '32', '36', '38', '40', '41', '42', '44', 'S', 'M', 'L', 'XS', 'XXS', 'XL', '2XL', 'Free size', 'XS-XL']);

// Simplified colors array with hex codes for styling
const colors = ref([
  { name: 'Black', hex: '#000000' },
  { name: 'Blue', hex: '#1f43ff' },
  { name: 'Brown', hex: '#6f4c37' },
  { name: 'Cream', hex: '#f8ebe0' },
  { name: 'Orange', hex: '#ff730e' },
  { name: 'Gray', hex: '#d9d9d9' },
  { name: 'Green', hex: '#7dd27c' },
  { name: 'Purple', hex: '#9747ff' },
  { name: 'Red', hex: '#ff0404' },
  { name: 'White', hex: '#ffffff' },
  { name: 'Yellow', hex: '#ffe925' },
]);

// Simplified fits array
const fits = ref(['Regular', 'Relax', 'Loose', 'Straight', 'Oversize', 'Wide', 'Slim', 'Skinny']);

</script>

<style scoped>
/*
  The original Filter.css and global.css might contain styles for .filter1, .filter-inner, etc.
  If you're using Tailwind CSS primarily, many of these might be redundant or overridden by Tailwind utilities.
  Keep these imports only if they provide essential base styles or specific non-Tailwind layouts.
*/
/* @import './global.css'; */
/* @import './Filter.css'; */

/* Custom scrollbar for the filter popup */
.filter1::-webkit-scrollbar {
  width: 6px;
}
.filter1::-webkit-scrollbar-track {
  background: #f1f1f1;
}
.filter1::-webkit-scrollbar-thumb {
  background: #fda4af; /* A pinkish color, adjust to your theme */
  border-radius: 3px;
}
.filter1::-webkit-scrollbar-thumb:hover {
  background: #f472b6; /* Darker pink on hover */
}
</style>