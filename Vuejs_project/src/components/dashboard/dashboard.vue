<template>
  <!-- Wrapper for Overall Page Structure -->
  <div class="page-center-wrapper w-full overflow-x-hidden flex flex-col min-h-screen font-kantumruy bg-white text-gray-800">

    <!-- Main Content -->
    <div class="home-content-wrapper pt-20 flex-grow">
      <!-- Hero Section -->
      <section class="relative overflow-hidden py-16 md:py-24">
        <div class="container mx-auto px-4 sm:px-6">
          <div class="grid md:grid-cols-2 gap-8 items-center">
            <!-- Welcome Title Section -->
            <div class="welcome-title-content text-center md:text-left">
              <div class="text-4xl sm:text-5xl md:text-6xl tracking-[0.2em] sm:tracking-[0.3em] md:tracking-[0.4em] leading-tight uppercase font-black font-lexend-mega mb-4">
                WELCOME
              </div>
              <b class="block text-3xl sm:text-4xl text-pink-700 mb-2">SN. FASHION</b>
              <div class="border-t border-gray-300 w-full max-w-md mx-auto md:mx-0 my-3"></div>
              <b class="block text-xl sm:text-2xl underline text-gray-700 mb-6">FREEDOM & STYLE</b>
              <p class="text-sm sm:text-base text-gray-600 max-w-md mx-auto md:mx-0 mb-10">
                Lorem ipsum, dolor sit amet consectetur adipisicing elit. Autem
                dolore quibusdam sed natus placeat? Soluta, sunt minus?
              </p>
             <div class="flex flex-wrap gap-4">
                <router-link v-for="(shopLink, index) in shopLinks" :key="'shop-'+index" :to="shopLink.path"
                  class="shop-now-button inline-flex items-center justify-center rounded-full border-4 border-pink-700 px-8 py-3 gap-2 text-pink-700 hover:bg-pink-50 transition-colors">
                  <b class="text-lg font-semibold relative underline">{{ shopLink.title }}</b>
                  <img class="w-7 h-7" :alt="shopLink.altText" :src="shopLink.icon" />
                </router-link>
              </div>
            </div>

            <!-- Poster Section -->
            <div class="poster-content relative h-[400px] sm:h-[500px] md:h-[600px] lg:h-[700px] mt-10 md:mt-0">
              <!-- Main Portrait -->
              <img
                class="absolute top-0 left-1/2 transform -translate-x-1/2 md:translate-x-0 md:left-[10%] rounded-lg shadow-xl z-10 w-[60%] sm:w-[50%] md:w-[300px] lg:w-[350px] h-auto object-cover"
                alt="Main fashion model" src="/images/portrait-young-woman-wearing-2000-s-fashion-style-1.png" />
              
              <!-- Secondary Portrait -->
              <img
                class="absolute bottom-0 left-[15%] md:left-0 rounded-lg shadow-lg w-[45%] sm:w-[35%] md:w-[220px] lg:w-[280px] h-auto object-cover"
                alt="Secondary fashion model" src="/images/portrait-young-woman-wearing-2000-s-fashion-style.png" />
              
              <!-- Background Box -->
              <div
                class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-gray-200/50 rounded-lg w-[80%] h-[70%] md:w-[350px] md:h-[450px] lg:w-[400px] lg:h-[500px]">
              </div>

              <img class="absolute top-[10%] left-[5%] w-10 h-10 opacity-50" alt="" src="/images/frame-79.png" />
              <img class="absolute bottom-[5%] right-[5%] w-24 h-24 object-contain opacity-30" alt=""
                src="/images/group-1171276595.svg" />
              <img class="absolute top-[5%] right-[10%] w-20 h-20 object-contain opacity-30" alt=""
                src="/images/group-1171276596.svg" />
            </div>

          </div>
        </div>
        
      </section>
    
      <div class="container mx-auto px-4 sm:px-6">
  <b class="block text-left text-2xl md:text-3xl underline text-gray-800 mb-12">
    Shop by Brand
  </b>

  <div class="flex flex-nowrap justify-evenly overflow-auto w-full">
  <router-link
    v-for="(brand, index) in brands"
    :key="`brand-${index}`"
    :to="brand.path ?? `/brand/${brand.name}`"
    
  >
      <img class="h-auto w-32" :src="brand.image" :alt="brand.name" />
  </router-link>
</div>

</div>
<!-- Top Products Section -->
      <section class="top-products-section py-16 md:py-24">
        <div class="container mx-auto px-4 sm:px-6">
          <b class="block text-left text-2xl md:text-3xl underline text-gray-800 mb-12">Deals of the Season - Women!</b>
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <router-link v-for="product in products" :key="`product-${product.id}`" :to="`/product/${product.id}`"
              class="product-item group bg-white rounded-lg shadow-md overflow-hidden flex flex-col hover:shadow-lg transition-shadow">
              <div class="relative h-96 overflow-hidden"> <!-- shorter height -->
  <img
    :alt="`Product ${product.id} image`"
    :src="product.image"
    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
  />
</div>

              <div class="p-4 flex flex-col flex-grow">
                <b class="text-lg font-semibold text-pink-700">${{ product.price }}</b>
                <div class="text-xs text-green-600 font-medium my-1" v-if="product.is_active">
                  IN STOCK
                </div>
                <p class="text-sm text-gray-600 leading-relaxed flex-grow mb-2 h-10 overflow-hidden text-ellipsis group-hover:whitespace-normal group-hover:overflow-visible group-hover:h-auto"
                  style="display: -webkit-box; line-clamp: 2; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                  {{ product.name }}
                </p>
               
              </div>
            </router-link>
          </div>
        </div>
      </section>
    </div>
   </div>
 </template>

<script setup>
import { onMounted, ref } from 'vue';
import api from '../../axios/Axios';

const products = ref([]);
const brands = ref([]);
const fetchProducts = async () => {
  try {
    const response = await api.get('/public/products');
    const data = response.data?.data ?? [];
    products.value = Array.isArray(data) ? data : [];
  } catch (error) {
    console.error('Failed to fetch products:', error.response?.data || error.message);
    products.value = [];
  }
};

onMounted(() => {
  fetchProducts();
});


// Fetch brand list from API
const fetchBrands = async () => {
  try {
    const response = await api.get('/public/brands');
    const data = response.data?.data ?? [];
    brands.value = Array.isArray(data) ? data : [];
  } catch (error) {
    console.error('Failed to fetch brands:', error.response?.data || error.message);
    brands.value = [];
  }
};

onMounted(() => {
  fetchBrands();
});
const API_BASE = "http://127.0.0.1:8000/storage/"; // adjust if your Laravel uses /storage or /uploads

const getImageUrl = (path) => {
  if (!path) return "/images/Adidas.png"; // fallback if missing
  return path.startsWith("http") ? path : API_BASE + path;
};

</script>

<script>
 export default {
  data() {
    return {
      // Shop links used in the hero section
      shopLinks: [
        {
          title: "SHOP WOMEN",
          path: "/category/women",
          altText: "Shop Women",
          icon: "/images/female_30dp_880E4F_FILL0_wght400_GRAD0_opsz24.svg"
        },
        {
          title: "SHOP MEN",
          path: "/category/men",
          altText: "Shop Men",
          icon: "/images/male_30dp_880E4F_FILL0_wght400_GRAD0_opsz24.svg"
                  }
      ],
    };
  },
};
</script>

<style>
/* Add any custom styles here if needed */
</style>


<style>
.font-lexend-mega {
  font-family: 'Lexend Mega', sans-serif;
}

.font-kantumruy {
  font-family: 'Kantumruy Pro', 'Kantumruy', sans-serif;
}

.font-mervale-script {
  font-family: 'Mervale Script', cursive;
}

.product-item p {
  display: -webkit-box;
  line-clamp: 2;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
}

.product-item.group:hover p {
  display: block;
  line-clamp: unset;
  -webkit-line-clamp: unset;
}

html {
  scroll-behavior: smooth;
}
</style>