<template>
  <!-- Wrapper for Overall Page Structure -->
  <div class="page-center-wrapper w-full overflow-x-hidden flex flex-col min-h-screen font-kantumruy bg-white text-gray-800">
    <!-- Header -->
    <div class="header-bar fixed top-0 left-0 w-full shadow-md bg-white h-20 z-50">
      <div class="container mx-auto h-full flex flex-row items-center justify-between px-4 sm:px-6">
        <!-- Left: Nav Links (Desktop) / Hamburger (Mobile) -->
        <div class="flex items-center">
          <button @click="isMobileMenuOpen = !isMobileMenuOpen"
            class="md:hidden text-gray-700 hover:text-pink-700 focus:outline-none mr-3" aria-label="Open menu">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
            </svg>
          </button>
          
          <!-- Dropdown Navigation -->
          <ul class="hidden md:flex md:space-x-6 lg:space-x-8 font-medium text-sm">
            <li v-for="(navItem, index) in navigationItems" :key="index" class="relative group">
              <router-link :to="navItem.path" class="flex items-center gap-1 py-2 text-gray-900 hover:text-pink-700"
                active-class="text-pink-700 font-semibold">
                <span>{{ navItem.title }}</span>
                <svg v-if="navItem.subItems" class="w-4 h-4 transition-transform duration-200 group-hover:rotate-180" fill="currentColor"
                  viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                    clip-rule="evenodd"></path>
                </svg>
              </router-link>
              
              <!-- Dropdown Menu Panel -->
              <div v-if="navItem.subItems"
                class="absolute left-1/2 -translate-x-1/2 mt-1 w-48 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-20 invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-200">
                <ul class="py-1">
                  <li v-for="(subItem, subIndex) in navItem.subItems" :key="subIndex">
                    <router-link :to="subItem.path"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-pink-700">
                      {{ subItem.title }}
                    </router-link>
                  </li>
                </ul>
              </div>
            </li>
          </ul>
        </div>
        
        <router-link to="/"
          class="font-mervale-script text-3xl text-pink-800 hover:text-pink-900 whitespace-nowrap"
          style="font-family: 'Mervale Script', cursive">Clothes Shop</router-link>
          
        <div class="hidden md:flex items-center gap-3 lg:gap-4">
          <div class="relative">
            <input type="text" placeholder="Search..."
              class="search-input bg-gray-100 border border-gray-300 rounded-md py-1.5 px-3 pl-10 text-sm focus:ring-pink-500 focus:border-pink-500"
              style="width: 150px" />
            <img class="w-4 h-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" alt="Search Icon"
              src="/images/material-symbols-light-search.svg" />
          </div>
          
          <div class="bar-menu-parent flex items-center space-x-3 md:space-x-4">
            <button v-for="(action, index) in headerActions" :key="index" class="p-1.5 hover:bg-gray-100 rounded-full">
              <svg class="w-6 h-6 text-gray-700" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path :d="action.iconPath" fill="currentColor" />
              </svg>
            </button>
            
            <a v-for="(authLink, index) in authLinks" :key="'auth-'+index" :href="authLink.path" 
              class="bar-menu1 px-3 py-2 rounded-md hover:bg-gray-100 text-sm">
              {{ authLink.title }}
            </a>
          </div>
        </div>
        <div class="md:hidden w-6"></div>
      </div>
      
      <!-- Mobile Menu Dropdown -->
    
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
    <!-- Main Content -->
 

    <!-- Footer -->
    <footer class="footer bg-pink-50 py-10 md:py-16 px-4 text-gray-700">
      <div class="container mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-8">
        <!-- Column 1: Follow Us -->
        <div class="follow-us-parent">
          <h3 class="text-lg font-semibold text-pink-800 mb-4">Follow Us</h3>
          <ul class="space-y-2">
            <li v-for="(social, index) in socialPlatforms" :key="'social-'+index"
              class="flex items-center space-x-2 hover:text-pink-600 cursor-pointer">
              <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24" aria-hidden="true">
                <path :d="social.iconPath" />
              </svg>
              <span class="text-sm">{{ social.name }}</span>
            </li>
          </ul>
        </div>

        <!-- Column 2: Customer Services -->
        <div class="customer-services-parent">
          <h3 class="text-lg font-semibold text-pink-800 mb-4">CUSTOMER SERVICES</h3>
          <ul class="space-y-2">
            <li v-for="(service, index) in customerServices" :key="'service-'+index"
              class="flex items-center space-x-2 hover:text-pink-600 cursor-pointer">
              <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24" aria-hidden="true">
                <path :d="service.iconPath" />
              </svg>
              <span class="text-sm">{{ service.name }}</span>
            </li>
          </ul>
        </div>

        <!-- Column 3: Contact Us -->
        <div class="contact-us-parent">
          <h3 class="text-lg font-semibold text-pink-800 mb-4">Contact Us</h3>
          <ul class="space-y-2">
            <li v-for="(contact, index) in contactInfo" :key="'contact-'+index"
              class="flex items-center space-x-2 hover:text-pink-600 cursor-pointer">
              <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24" aria-hidden="true">
                <path :d="contact.iconPath" />
              </svg>
              <span class="text-sm">{{ contact.value }}</span>
            </li>
          </ul>
        </div>

        <!-- Column 4: We Accept -->
        <div class="we-accept-parent lg:col-span-2">
          <h3 class="text-lg font-semibold text-pink-800 mb-4">WE ACCEPT</h3>
          <div class="flex flex-wrap gap-2 mb-4">
            <img v-for="(payment, index) in paymentMethods" :key="'payment-'+index" 
              class="h-10 w-auto object-contain rounded" :alt="payment.name" :src="payment.image">
          </div>
        </div>

        <!-- Column 5: App QR Code -->
        <div class="zando-app-qr-container text-center p-4">
          <h3 class="text-xl font-semibold mb-3">SN.Fashion APP</h3>
          <img src="/images/qr code.png" alt="SN.Fashion App QR Code"
            class="qr-code-image w-40 h-40 mx-auto border border-gray-300 p-1" />
        </div>
      </div>

      <!-- Bottom Bar -->
      <div class="line-parent mt-10 pt-6 border-t-2 border-pink-700 flex flex-col sm:flex-row justify-between items-center text-center sm:text-left">
        <p class="text-xs text-gray-500 mb-4 sm:mb-0">© SN.Fashion 2024. All Rights Reserved.</p>
        <button @click="scrollToTop" class="hover:text-pink-600" aria-label="Scroll to top">
          <svg class="w-7 h-7 stroke-current text-gray-600" viewBox="0 0 34 34" fill="none"
            xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path
              d="M16.9999 3.3999L26.0666 12.4666M16.9999 3.3999L7.93327 12.4666M16.9999 3.3999V27.1999M2.2666 30.5999H31.7333"
              stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </button>
      </div>
    </footer>
    
    <div class="text-center text-xs text-gray-500 py-4 bg-gray-50">
      Powered by SN.Fashion © 2024
    </div>

    <!-- Menu Popup (Off-canvas style) -->
    <div v-if="isMenuOpen" @click="closeMenuOverlay"
      class="popup-overlay fixed inset-0 bg-black/30 flex items-start justify-start z-[100]">
      <div @click.stop
        class="menu-popup-content w-64 sm:w-72 bg-white h-full shadow-xl flex flex-col overflow-y-auto p-5">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-xl font-semibold text-pink-700">Menu</h2>
          <button @click="closeMenu" aria-label="Close menu">
            <svg class="w-6 h-6 text-gray-600 hover:text-pink-700" fill="currentColor" viewBox="0 0 24 24">
              <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
            </svg>
          </button>
        </div>
        
        <nav>
          <ul class="space-y-2">
            <li v-for="(menuItem, index) in menuItems" :key="'menu-'+index">
              <router-link :to="menuItem.path" @click="closeMenu"
                class="block py-2 px-3 rounded hover:bg-pink-50 hover:text-pink-700">
                {{ menuItem.title }}
              </router-link>
            </li>
          </ul>
        </nav>
        
        <div class="mt-auto pt-4 border-t border-gray-200 space-y-2">
          <router-link v-for="(authLink, index) in authLinks" :key="'mobile-auth-'+index" 
            :to="authLink.path" @click="closeMenu"
            class="block py-2 px-3 rounded hover:bg-pink-50 hover:text-pink-700">
            {{ authLink.title }}
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
 export default {
  data() {
    return {
      isMobileMenuOpen: false,
      isMenuOpen: false,
      
      // Navigation data
      navigationItems: [
        {
          title: "Women",
          path: "/women",
          subItems: [
            { title: "New Arrivals", path: "/Women-New-Arrivals" },
            { title: "Dresses", path: "/women/dresses" },
            { title: "Tops", path: "/women/tops" }
          ]
        },

        {
          title: "Men",
          path: "/men",
          subItems: [
            { title: "Shirts", path: "/men/shirts" },
            { title: "Pants", path: "/men/pants" },
            { title: "Jackets", path: "/men/jackets" }
          ]
        },
        {
          title: "Boy",
          path: "/boy",
          subItems: [
            { title: "T-Shirts", path: "/boy/t-shirts" },
            { title: "Shorts", path: "/boy/shorts" }
          ]
        },
        {
          title: "Girl",
          path: "/girl",
          subItems: [
            { title: "Dresses", path: "/girl/dresses" },
            { title: "Skirts", path: "/girl/skirts" }
          ]
        }
      ],
      
      mobileNavigationItems: [
        { title: "WOMEN", path: "/category/women" },
        { title: "MEN", path: "/category/men" },
        { title: "BOY", path: "/category/boys" },
        { title: "GIRL", path: "/category/girls" },
        { title: "list", path: "/product/list" }
      ],
      
      headerActions: [
        {
          iconPath: "M22.0067 19.0531C21.3638 18.48 20.801 17.823 20.3334 17.0998C19.8223 16.1017 19.5163 15.0114 19.4334 13.8931V10.5998C19.4378 8.84351 18.8007 7.14609 17.6419 5.82642C16.483 4.50675 14.8821 3.65568 13.14 3.43311V2.57311C13.14 2.33706 13.0463 2.11069 12.8794 1.94378C12.7124 1.77687 12.4861 1.68311 12.25 1.68311C12.014 1.68311 11.7876 1.77687 11.6207 1.94378C11.4538 2.11069 11.36 2.33706 11.36 2.57311V3.44644C9.63354 3.68506 8.05201 4.54128 6.90839 5.85651C5.76476 7.17174 5.13652 8.85687 5.14003 10.5998V13.8931C5.05707 15.0114 4.75106 16.1017 4.24003 17.0998C3.78062 17.8213 3.22683 18.4783 2.59337 19.0531C2.52225 19.1156 2.46526 19.1925 2.42618 19.2787C2.38709 19.3649 2.36682 19.4584 2.3667 19.5531V20.4598C2.3667 20.6366 2.43694 20.8062 2.56196 20.9312C2.68699 21.0562 2.85656 21.1264 3.03337 21.1264H21.5667C21.7435 21.1264 21.9131 21.0562 22.0381 20.9312C22.1631 20.8062 22.2334 20.6366 22.2334 20.4598V19.5531C22.2332 19.4584 22.213 19.3649 22.1739 19.2787C22.1348 19.1925 22.0778 19.1156 22.0067 19.0531ZM3.75337 19.7931C4.3735 19.1938 4.91961 18.5223 5.38003 17.7931C6.02389 16.5877 6.39915 15.2573 6.48003 13.8931V10.5998C6.45359 9.81846 6.58465 9.03982 6.8654 8.31022C7.14614 7.58061 7.57084 6.91496 8.1142 6.35291C8.65755 5.79085 9.30846 5.34388 10.0282 5.03862C10.7478 4.73336 11.5216 4.57604 12.3034 4.57604C13.0851 4.57604 13.8589 4.73336 14.5786 5.03862C15.2983 5.34388 15.9492 5.79085 16.4925 6.35291C17.0359 6.91496 17.4606 7.58061 17.7413 8.31022C18.0221 9.03982 18.1531 9.81846 18.1267 10.5998V13.8931C18.2076 15.2573 18.5828 16.5877 19.2267 17.7931C19.6871 18.5223 20.2332 19.1938 20.8534 19.7931H3.75337ZM12.3329 23.353C12.7529 23.3433 13.1559 23.1855 13.4707 22.9073C13.7854 22.6291 13.9917 22.2486 14.0529 21.833H10.5463C10.6092 22.2599 10.8251 22.6493 11.1538 22.9289C11.4825 23.2085 11.9015 23.3592 12.3329 23.353Z"
        },
        {
          iconPath: "M17.0209 4.25C15.0849 4.25 13.3899 5.0825 12.3334 6.48969C11.2768 5.0825 9.58181 4.25 7.64587 4.25C6.10484 4.25174 4.62741 4.86468 3.53773 5.95436C2.44806 7.04404 1.83511 8.52146 1.83337 10.0625C1.83337 16.625 11.5637 21.9369 11.9781 22.1562C12.0873 22.215 12.2094 22.2458 12.3334 22.2458C12.4574 22.2458 12.5795 22.215 12.6887 22.1562C13.1031 21.9369 22.8334 16.625 22.8334 10.0625C22.8316 8.52146 22.2187 7.04404 21.129 5.95436C20.0393 4.86468 18.5619 4.25174 17.0209 4.25ZM12.3334 20.6375C10.6215 19.64 3.33337 15.0959 3.33337 10.0625C3.33486 8.91921 3.78969 7.82317 4.59812 7.01475C5.40655 6.20632 6.50258 5.75149 7.64587 5.75C9.46931 5.75 11.0002 6.72125 11.6396 8.28125C11.6961 8.41881 11.7923 8.53646 11.9158 8.61926C12.0393 8.70207 12.1847 8.74627 12.3334 8.74627C12.4821 8.74627 12.6274 8.70207 12.751 8.61926C12.8745 8.53646 12.9706 8.41881 13.0271 8.28125C13.6665 6.71844 15.1974 5.75 17.0209 5.75C18.1642 5.75149 19.2602 6.20632 20.0686 7.01475C20.8771 7.82317 21.3319 8.91921 21.3334 10.0625C21.3334 15.0884 14.0434 19.6391 12.3334 20.6375Z"
        },
        {
          iconPath: "M19.3334 7.5H16.3334V6.5C16.3334 5.43913 15.9119 4.42172 15.1618 3.67157C14.4117 2.92143 13.3942 2.5 12.3334 2.5C11.2725 2.5 10.2551 2.92143 9.50495 3.67157C8.7548 4.42172 8.33337 5.43913 8.33337 6.5V7.5H5.33337C5.06816 7.5 4.8138 7.60536 4.62627 7.79289C4.43873 7.98043 4.33337 8.23478 4.33337 8.5V19.5C4.33337 20.2956 4.64944 21.0587 5.21205 21.6213C5.77466 22.1839 6.53772 22.5 7.33337 22.5H17.3334C18.129 22.5 18.8921 22.1839 19.4547 21.6213C20.0173 21.0587 20.3334 20.2956 20.3334 19.5V8.5C20.3334 8.23478 20.228 7.98043 20.0405 7.79289C19.8529 7.60536 19.5986 7.5 19.3334 7.5ZM10.3334 6.5C10.3334 5.96957 10.5441 5.46086 10.9192 5.08579C11.2942 4.71071 11.8029 4.5 12.3334 4.5C12.8638 4.5 13.3725 4.71071 13.7476 5.08579C14.1227 5.46086 14.3334 5.96957 14.3334 6.5V7.5H10.3334V6.5ZM18.3334 19.5C18.3334 19.7652 18.228 20.0196 18.0405 20.2071C17.8529 20.3946 17.5986 20.5 17.3334 20.5H7.33337C7.06816 20.5 6.8138 20.3946 6.62627 20.2071C6.43873 20.0196 6.33337 19.7652 6.33337 19.5V9.5H8.33337V10.5C8.33337 10.7652 8.43873 11.0196 8.62627 11.2071C8.8138 11.3946 9.06816 11.5 9.33337 11.5C9.59859 11.5 9.85294 11.3946 10.0405 11.2071C10.228 11.0196 10.3334 10.7652 10.3334 10.5V9.5H14.3334V10.5C14.3334 10.7652 14.4387 11.0196 14.6263 11.2071C14.8138 11.3946 15.0682 11.5 15.3334 11.5C15.5986 11.5 15.8529 11.3946 16.0405 11.2071C16.228 11.0196 16.3334 10.7652 16.3334 10.5V9.5H18.3334V19.5Z"
        }
      ],
      
      authLinks: [
        { title: "Register", path: "/register" },
        { title: "Login", path: "/login" }
      ],
      
      // Shop links
      shopLinks: [
        {
          title: "SHOP WOMEN",
          path: "/women",
          altText: "Shop Women",
          icon: "/images/female_30dp_880E4F_FILL0_wght400_GRAD0_opsz24.svg"
        },
        {
          title: "SHOP MEN",
          path: "/men",
          altText: "Shop Men",
          icon: "/images/male_30dp_880E4F_FILL0_wght400_GRAD0_opsz24.svg"
        }
      ],
      
      // Products are provided by <script setup> via API fetch
      
      // Footer data
      socialPlatforms: [
        {
          name: "Facebook",
          iconPath: "M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"
        },
        {
          name: "Instagram",
          iconPath: "M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"
        },
        {
          name: "Twitter",
          iconPath: "M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"
        },
        {
          name: "YouTube",
          iconPath: "M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"
        }
      ],
      
      customerServices: [
        {
          name: "Privacy Policy",
          iconPath: "M12 1.5L3 5.5V11.5C3 17.05 6.84 22.24 12 23.5C17.16 22.24 21 17.05 21 11.5V5.5L12 1.5ZM11 7.5H13V9.5H11V7.5ZM11 11.5H13V17.5H11V11.5Z"
        },
        {
          name: "Online exchange policy",
          iconPath: "M12 2.75C10.0716 2.75 8.18657 3.32183 6.58319 4.39317C4.97982 5.46452 3.73013 6.98726 2.99218 8.76884C2.25422 10.5504 2.06114 12.5108 2.43735 14.4021C2.81355 16.2934 3.74215 18.0307 5.10571 19.3943C6.46928 20.7579 8.20656 21.6865 10.0979 22.0627C11.9892 22.4389 13.9496 22.2458 15.7312 21.5078C17.5127 20.7699 19.0355 19.5202 20.1068 17.9168C21.1782 16.3134 21.75 14.4284 21.75 12.5C21.7473 9.91498 20.7192 7.43661 18.8913 5.60872C17.0634 3.78084 14.585 2.75273 12 2.75ZM12 18.5C11.7775 18.5 11.56 18.434 11.375 18.3104C11.19 18.1868 11.0458 18.0111 10.9606 17.8055C10.8755 17.6 10.8532 17.3738 10.8966 17.1555C10.94 16.9373 11.0472 16.7368 11.2045 16.5795C11.3618 16.4222 11.5623 16.315 11.7805 16.2716C11.9988 16.2282 12.225 16.2505 12.4305 16.3356C12.6361 16.4208 12.8118 16.565 12.9354 16.75C13.059 16.935 13.125 17.1525 13.125 17.375C13.125 17.6734 13.0065 17.9595 12.7955 18.1705C12.5845 18.3815 12.2984 18.5 12 18.5ZM12.75 13.9325V14C12.75 14.1989 12.671 14.3897 12.5303 14.5303C12.3897 14.671 12.1989 14.75 12 14.75C11.8011 14.75 11.6103 14.671 11.4697 14.5303C11.329 14.3897 11.25 14.1989 11.25 14V13.25C11.25 13.0511 11.329 12.8603 11.4697 12.7197C11.6103 12.579 11.8011 12.5 12 12.5C13.2403 12.5 14.25 11.6562 14.25 10.625C14.25 9.59375 13.2403 8.75 12 8.75C10.7597 8.75 9.75 9.59375 9.75 10.625V11C9.75 11.1989 9.67099 11.3897 9.53033 11.5303C9.38968 11.671 9.19892 11.75 9 11.75C8.80109 11.75 8.61033 11.671 8.46967 11.5303C8.32902 11.3897 8.25 11.1989 8.25 11V10.625C8.25 8.76406 9.93188 7.25 12 7.25C14.0681 7.25 15.75 8.76406 15.75 10.625C15.75 12.2544 14.46 13.6184 12.75 13.9325Z"
        }
      ],
      
      contactInfo: [
        {
          type: "email",
          value: "infome@gmail.com",
          iconPath: "M12 1.5L3 5.5V11.5C3 17.05 6.84 22.24 12 23.5C17.16 22.24 21 17.05 21 11.5V5.5L12 1.5ZM19.3334 7.5L12 12.5L4.6666 7.5V6.5L12 11.5L19.3334 6.5V7.5Z"
        },
        {
          type: "phone",
          value: "(+855) 016 356624",
          iconPath: "M17.0209 4.25C15.0849 4.25 13.3899 5.0825 12.3334 6.48969C11.2768 5.0825 9.58181 4.25 7.64587 4.25C6.10484 4.25174 4.62741 4.86468 3.53773 5.95436C2.44806 7.04404 1.83511 8.52146 1.83337 10.0625C1.83337 16.625 11.5637 21.9369 11.9781 22.1562C12.0873 22.215 12.2094 22.2458 12.3334 22.2458C12.4574 22.2458 12.5795 22.215 12.6887 22.1562C13.1031 21.9369 22.8334 16.625 22.8334 10.0625C22.8316 8.52146 22.2187 7.04404 21.129 5.95436C20.0393 4.86468 18.5619 4.25174 17.0209 4.25ZM12.3334 20.6375C10.6215 19.64 3.33337 15.0959 3.33337 10.0625C3.33486 8.91921 3.78969 7.82317 4.59812 7.01475C5.40655 6.20632 6.50258 5.75149 7.64587 5.75C9.46931 5.75 11.0002 6.72125 11.6396 8.28125C11.6961 8.41881 11.7923 8.53646 11.9158 8.61926C12.0393 8.70207 12.1847 8.74627 12.3334 8.74627C12.4821 8.74627 12.6274 8.70207 12.751 8.61926C12.8745 8.53646 12.9706 8.41881 13.0271 8.28125C13.6665 6.71844 15.1974 5.75 17.0209 5.75C18.1642 5.75149 19.2602 6.20632 20.0686 7.01475C20.8771 7.82317 21.3319 8.91921 21.3334 10.0625C21.3334 15.0884 14.0434 19.6391 12.3334 20.6375Z"
        },
        {
          type: "telegram",
          value: "@vann_norey",
          iconPath: "M12 2.5C6.48 2.5 2 6.98 2 12.5C2 18.02 6.48 22.5 12 22.5C17.52 22.5 22 18.02 22 12.5C22 6.98 17.52 2.5 12 2.5ZM16.64 9.3C16.49 10.88 15.84 14.72 15.51 16.49C15.37 17.24 15.09 17.49 14.83 17.52C14.25 17.57 13.81 17.14 13.25 16.77C12.37 16.19 11.87 15.83 11.02 15.27C10.03 14.62 10.67 14.26 11.24 13.68C11.39 13.53 13.95 11.2 14 10.99C14.0069 10.9582 14.006 10.9252 13.9973 10.8938C13.9886 10.8624 13.9724 10.8337 13.95 10.81C13.89 10.76 13.81 10.78 13.74 10.79C13.65 10.81 12.25 11.74 9.52 13.58C9.12 13.85 8.76 13.99 8.44 13.98C8.08 13.97 7.4 13.78 6.89 13.61C5.77 13.3 5.81 12.95 5.83 12.77C6.08 12.59 6.55 12.4 9.47 11.13 11.41 10.29 12.38 9.89 15.16 8.73 15.73 8.53 16.11 8.53 16.19 8.53 16.38 8.55 16.5 8.65 16.6 8.73 16.63 8.84 16.64 8.92 16.63 8.98 16.65 9.16 16.64 9.3Z"
        }
      ],
      
      paymentMethods: [
        { name: "Visa", image: "/images/visa.jpg" },
        { name: "Payaba", image: "/images/payaba.png" },
        { name: "UnionPay", image: "/images/UnionPay_logo.svg.png" },
        { name: "AC", image: "/images/rectangle-73.png" },
        { name: "Mastercard", image: "/images/3e4c39c969d4d6d2ed446dd7091f7c66.jpg" },
        { name: "ABA", image: "/images/chipnpng.png" },
        { name: "Banktransfer", image: "/images/rectangle-72.png" },
        { name: "JCB", image: "/images/JCB_logo.svg.png" },
        { name: "Bank Transfer", image: "/images/BankTransfer_logo_1200x600.png" },
        { name: "Cash on Delivery", image: "/images/cash-on-delivery.png" }
      ],
      
      // Menu items
      menuItems: [
        {
          title: "Women Collection",
          path: "/category/women"
        },
        {
          title: "Men Collection",
          path: "/category/men"
        },
        {
          title: "Boys Collection",
          path: "/category/boys"
        },
        {
          title: "Girls Collection",
          path: "/category/girls"
        },
        {
          title: "Wome Collection",
          path: "/category/women-new-arrivals"
        }
      ]
    };
  },

  
  methods: {
    scrollToTop() {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    },
    closeMenu() {
      this.isMenuOpen = false;
    },
    closeMenuOverlay() {
      this.isMenuOpen = false;
    }
  }
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