<template>
  <!-- Wrapper for Overall Page Structure -->
  <div
    class="page-center-wrapper w-full overflow-x-hidden flex flex-col min-h-screen font-kantumruy bg-white text-gray-800"
  >
    <!-- Header -->
    <div
      class="header-bar fixed top-0 left-0 w-full shadow-md bg-white h-20 z-50"
    >
      <div
        class="container mx-auto h-full flex flex-row items-center font-bold text-xl justify-between px-6 sm:px-10"
      >
        <!-- Left: Nav Links (Desktop) / Hamburger (Mobile) -->
        <div class="flex items-center">
          <button
            @click="isMobileMenuOpen = !isMobileMenuOpen"
            class="md:hidden text-gray-700 hover:text-pink-700 focus:outline-none mr-4"
            aria-label="Open menu"
          >
            <svg
              class="w-7 h-7"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 6h16M4 12h16m-7 6h7"
              ></path>
            </svg>
          </button>

          <!-- Dropdown Navigation -->
          <ul class="hidden md:flex md:space-x-6 lg:space-x-6">
            <li
              v-for="(navItem, index) in navigationItems"
              :key="index"
              class="relative group"
            >
              <router-link
                :to="navItem.path"
                class="block py-3 px-4 text-gray-700 hover:text-pink-600 transition-colors duration-200 font-bold text-base border-b-2 border-transparent hover:border-pink-500"
                active-class="text-pink-600 border-pink-500"
              >
                {{ navItem.title }}
              </router-link>

              <!-- Dropdown Menu Panel -->

              <div
                v-if="navItem.subItems"
                class="absolute left-1/2 -translate-x-1/3 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-20 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200"
              >
                <!-- Dropdown Header -->
                <!-- <div class="px-4 py-3 border-b border-gray-200 bg-gray-50 rounded-t-lg">
                    <h3 class="text-sm font-semibold text-gray-800">{{ navItem.title }}</h3>
                  </div> -->

                <ul class="py-2">
                  <li
                    v-for="(subItem, subIndex) in navItem.subItems"
                    :key="subIndex"
                  >
                    <router-link
                      :to="subItem.path"
                      class="block px-2 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors duration-150"
                    >
                      {{ subItem.title }}
                    </router-link>
                  </li>
                </ul>
              </div>
            </li>
          </ul>
        </div>

        <router-link
          to="/"
          class="hover:opacity-80 transition-opacity duration-200"
        >
          <img
            src="/public/images/logo10.png"
            alt="CLOTHES - SHOP"
            class="h-12 w-auto"
          />
        </router-link>

        <div class="bar-menu-parent flex items-center space-x-5 md:space-x-2">
          <!-- Search button -->
          <button
            @click="toggleSearchForm"
            class="p-2 hover:bg-gray-100 rounded-full"
          >
            <svg
              class="w-7 h-7 text-gray-700"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
              stroke-width="2"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
              />
            </svg>
          </button>

          <!-- Notification button -->
          <button class="p-2 hover:bg-gray-100 rounded-full">
            <svg
              class="w-7 h-7 text-gray-700"
              viewBox="0 0 25 25"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4a1.5 1.5 0 10-3 0v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"
                fill="currentColor"
              />
            </svg>
          </button>

          <!-- Cart Icon - Only show when logged in -->
          <button
            v-if="isLoggedIn"
            @click="toggleCartDrawer"
            class="p-2 hover:bg-gray-100 rounded-full relative"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-6 w-6"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
              stroke-width="2"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
              />
            </svg>
            <!-- Cart badge -->
            <span
              v-if="cartItemCount > 0"
              class="absolute -top-1 -right-1 bg-pink-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center"
            >
              {{ cartItemCount > 99 ? "99+" : cartItemCount }}
            </span>
          </button>

          <!-- Favorite Icon - Only show when logged in -->
          <button
            v-if="isLoggedIn"
            @click="goToWishlist"
            class="p-2 hover:bg-gray-100 rounded-full relative"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-7 w-7 text-gray-800"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
              stroke-width="2"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.5l1.318-1.182a4.5 4.5 0 116.364 6.364L12 18.75l-7.682-7.682a4.5 4.5 0 010-6.364z"
              />
            </svg>
            <!-- Favorite badge -->
            <span
              v-if="favoriteItemCount > 0"
              class="absolute -top-1 -right-1 bg-pink-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center"
            >
              {{ favoriteItemCount > 99 ? "99+" : favoriteItemCount }}
            </span>
          </button>

          <!-- Profile Dropdown - Only show when logged in -->
          <div v-if="isLoggedIn" class="relative">
            <button
              @click="toggleProfileDropdown"
              class="p-2 hover:bg-gray-100 rounded-full"
            >
              <svg
                class="w-7 h-7 text-gray-700"
                viewBox="0 0 25 25"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  d="M12 12c2.761 0 5-2.239 5-5s-2.239-5-5-5-5 2.239-5 5 2.239 5 5 5zm0 2c-3.866 0-7 3.134-7 7v1h14v-1c0-3.866-3.134-7-7-7z"
                  fill="currentColor"
                />
              </svg>
            </button>

            <!-- Profile Dropdown Menu -->
            <div
              v-if="isProfileDropdownOpen"
              class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 z-50"
            >
              <div class="py-1">
                <router-link
                  to="/profile"
                  class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                >
                  Profile
                </router-link>
                <router-link
                  to="/address"
                  class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                >
                  Address
                </router-link>
                <router-link
                  to="/orders"
                  class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                >
                  My Orders
                </router-link>

                <router-link
                  to="/wishlist"
                  class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                >
                  Wishlist
                </router-link>

                <button
                  @click="handleLogout"
                  class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50"
                >
                  Logout
                </button>
              </div>
            </div>
          </div>

          <!-- Login/Register Button - Only show when not logged in -->
          <button
            v-if="!isLoggedIn"
            @click="showAccountPopup = true"
            class="p-2 hover:bg-gray-100 rounded-full"
          >
            <svg
              class="w-7 h-7 text-gray-700"
              viewBox="0 0 25 25"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                d="M12 12c2.761 0 5-2.239 5-5s-2.239-5-5-5-5 2.239-5 5 2.239 5 5 5zm0 2c-3.866 0-7 3.134-7 7v1h14v-1c0-3.866-3.134-7-7-7z"
                fill="currentColor"
              />
            </svg>
          </button>
        </div>
        <div class="md:hidden w-8"></div>
      </div>

      <!-- Search Form -->
      <div
        v-if="isSearchFormOpen"
        class="fixed top-20 left-0 w-full bg-white shadow-lg border-t border-gray-200 z-40"
        @click.self="closeSearchForm"
      >
        <div class="container mx-auto px-6 py-4">
          <form
            @submit.prevent="handleFormSubmit"
            class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4"
          >
            <div class="flex-1 relative">
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Search for products, categories, or brands..."
                class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent text-lg"
              />
              <svg
                class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                />
              </svg>
            </div>
            <div class="flex gap-2">
              <button
                type="submit"
                class="flex-1 sm:flex-none px-6 py-3 bg-pink-600 text-white font-semibold rounded-lg hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 transition-colors"
              >
                <svg
                  class="w-5 h-5 inline mr-2"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                  />
                </svg>
                Search
              </button>
              <button
                type="button"
                @click="closeSearchForm"
                class="px-4 py-3 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-colors"
              >
                Cancel
              </button>
            </div>
          </form>

          <!-- Search Suggestions (if any) -->
          <div
            v-if="searchSuggestions.length > 0 && !searchResults.length"
            class="mt-4 pt-4 border-t border-gray-200"
          >
            <h4 class="text-sm font-medium text-gray-700 mb-2">
              Popular searches:
            </h4>
            <div class="flex flex-wrap gap-2">
              <button
                v-for="suggestion in searchSuggestions"
                :key="suggestion"
                @click="
                  searchQuery = suggestion;
                  handleSearch();
                "
                class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm hover:bg-pink-100 hover:text-pink-700 transition-colors"
              >
                {{ suggestion }}
              </button>
            </div>
          </div>

          <!-- Search Results -->
          <div
            v-if="searchResults.length > 0"
            class="mt-4 pt-4 border-t border-gray-200"
          >
            <div class="flex justify-between items-center mb-3">
              <h4 class="text-sm font-medium text-gray-700">
                Found {{ searchResults.length }} product{{
                  searchResults.length !== 1 ? "s" : ""
                }}
              </h4>
              <button
                @click="clearSearchResults"
                class="text-sm text-pink-600 hover:text-pink-700"
              >
                Clear results
              </button>
            </div>
            <div
              class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 max-h-96 overflow-y-auto"
            >
              <div
                v-for="product in searchResults"
                :key="product.id"
                @click="goToProduct(product.id)"
                class="bg-white border border-gray-200 rounded-lg p-3 cursor-pointer hover:shadow-md transition-shadow"
              >
                <div class="flex items-center space-x-3">
                  <div
                    class="w-16 h-16 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0"
                  >
                    <img
                      v-if="
                        Array.isArray(product.images) &&
                        product.images.length > 0
                      "
                      :src="product.images[0]"
                      :alt="product.name"
                      class="w-full h-full object-cover"
                      @error="$event.target.src = '/images/placeholder.png'"
                    />
                    <img
                      v-else
                      :src="product.image || '/images/placeholder.png'"
                      :alt="product.name"
                      class="w-full h-full object-cover"
                      @error="$event.target.src = '/images/placeholder.png'"
                    />
                  </div>
                  <div class="flex-1 min-w-0">
                    <h5 class="font-medium text-gray-900 truncate">
                      {{ product.name }}
                    </h5>
                    <p class="text-sm text-pink-600 font-semibold">
                      {{ formatPrice(product.price) }}
                    </p>
                    <p
                      v-if="product.category && product.category.name"
                      class="text-xs text-gray-500 truncate"
                    >
                      {{ product.category.name }}
                    </p>
                  </div>
                </div>
                <!-- Show thumbnails if multiple images -->
                <div
                  v-if="
                    Array.isArray(product.images) && product.images.length > 1
                  "
                  class="flex mt-2 space-x-2"
                >
                  <img
                    v-for="(img, idx) in product.images"
                    :key="img"
                    :src="img"
                    :alt="`Thumbnail ${idx + 1}`"
                    class="w-8 h-8 object-cover rounded border border-gray-200"
                    @error="$event.target.src = '/images/placeholder.png'"
                  />
                </div>
              </div>
            </div>
          </div>

          <!-- No Results Message -->
          <div
            v-if="searchPerformed && searchResults.length === 0"
            class="mt-4 pt-4 border-t border-gray-200 text-center"
          >
            <p class="text-gray-500">
              No products found for "{{ searchQuery }}"
            </p>
            <p class="text-sm text-gray-400 mt-1">
              Try different keywords or check spelling
            </p>
          </div>

          <!-- Loading State -->
          <div
            v-if="isSearching"
            class="mt-4 pt-4 border-t border-gray-200 text-center"
          >
            <div class="flex items-center justify-center space-x-2">
              <div
                class="animate-spin rounded-full h-5 w-5 border-b-2 border-pink-600"
              ></div>
              <span class="text-gray-500">Searching...</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Mobile Menu Dropdown -->
      <div
        v-if="isMobileMenuOpen"
        class="md:hidden fixed top-20 left-0 w-full bg-white shadow-md border-t border-gray-200 z-40"
      >
        <ul class="flex flex-col p-3 space-y-1">
          <li
            v-for="(navItem, index) in navigationItems"
            :key="'mobile-nav-' + index"
          >
            <router-link
              :to="navItem.path"
              @click="isMobileMenuOpen = false"
              class="block py-2 px-3 text-gray-700 rounded hover:bg-gray-50 hover:text-gray-900 transition-colors duration-150"
              active-class="bg-gray-100 text-gray-900 font-medium"
            >
              {{ navItem.title }}
            </router-link>
          </li>
          <li>
            <router-link
              to="/product/list"
              @click="isMobileMenuOpen = false"
              class="block py-2 px-3 text-gray-700 rounded hover:bg-gray-50 hover:text-gray-900 transition-colors duration-150"
              active-class="bg-gray-100 text-gray-900 font-medium"
            >
              List
            </router-link>
          </li>

          <li class="pt-2 border-t border-gray-200">
            <input
              type="text"
              placeholder="Search..."
              class="w-full search-input bg-gray-50 border border-gray-200 rounded py-2 px-3 text-sm focus:ring-1 focus:ring-gray-400 focus:border-gray-400"
            />
          </li>
        </ul>
      </div>
    </div>

    <!-- Cart Drawer -->
    <div v-if="isCartDrawerOpen" class="fixed inset-0 z-50 overflow-hidden">
      <!-- Backdrop -->
      <div
        class="absolute inset-0 bg-black bg-opacity-50 transition-opacity"
        @click="closeCartDrawer"
      ></div>

      <!-- Drawer -->
      <div
        class="absolute right-0 top-0 h-full w-full max-w-md bg-white shadow-xl transform transition-transform duration-300 ease-in-out"
      >
        <!-- Drawer Header -->
        <div
          class="flex items-center justify-between p-6 border-b border-gray-200"
        >
          <h2 class="text-xl font-bold text-gray-900">Shopping Cart</h2>
          <button
            @click="closeCartDrawer"
            class="text-gray-400 hover:text-gray-600"
          >
            <svg
              class="w-6 h-6"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              ></path>
            </svg>
          </button>
        </div>

        <!-- Cart Items -->
        <div class="flex-1 overflow-y-auto p-6">
          <div v-if="cartItems.length === 0" class="text-center py-12">
            <svg
              class="mx-auto h-12 w-12 text-gray-400"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
              />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">
              Your cart is empty
            </h3>
            <p class="mt-1 text-sm text-gray-500">
              Start shopping to add items to your cart.
            </p>
          </div>

         <div class="flex-1 overflow-y-auto">
  <div v-if="!cartItems || cartItems.length === 0" class="text-center py-12">
    <!-- empty cart UI -->
  </div>

  <div v-else class="h-72 space-y-3 overflow-y-auto">

    <div
      v-for="item in cartItems"
      :key="`${item.id}-${item.size}-${item.timestamp}`"
      class="grid grid-cols-3 gap-4"
    >

      <div class="flex-1 rounded-lg overflow-hidden">
        <img :src="item.product.image" alt="Product Image"/>
      </div>

      <div class="flex-1 flex flex-col justify-between col-span-2">
        <div class="flex justify-between items-start">
      
          <div class="space-y-2">
            <p class="text-lg font-medium">{{item.product.name}}</p>
            <p class="text-xl font-semibold">${{item.price}}</p>
            <p>Size {{item.product.size}}</p>
            <div class="flex gap-3">Quantity 
            <button><FontAwesomeIcon :icon="['fas', 'minus']" /></button>
            {{item.quantity}}
            <button><FontAwesomeIcon :icon="['fas', 'plus']" /></button>
            </div>
          </div>

          <button>
            <FontAwesomeIcon :icon="['fas', 'trash']" class="text-red-600"/>
          </button>

        </div>
         
      </div>
    </div>
      
  </div>

</div>

        </div>

        <!-- Cart Footer -->
        <div class="border-t border-gray-200 p-6">
          <div v-if="cartItems.length > 0" class="space-y-4">
            <!-- Pricing Breakdown -->
            <div class="space-y-3 text-sm">
              <!-- Subtotal -->
              <div class="flex justify-between text-gray-600">
                <span>Amount to pay</span>
                <span>US ${{ cartSubtotal.toFixed(2) }}</span>
              </div>

              <!-- Delivery Fee -->
              <div class="flex justify-between text-gray-600">
                <span>Delivery fee</span>
                <span>US ${{ deliveryFee.toFixed(2) }}</span>
              </div>

              <!-- Savings -->
              <div
                v-if="totalSavings > 0"
                class="flex justify-between text-green-600"
              >
                <span>Save</span>
                <span>-US ${{ totalSavings.toFixed(2) }}</span>
              </div>

              <!-- Total -->
              <div
                class="flex justify-between text-base font-bold text-gray-900 border-t border-gray-200 pt-3"
              >
                <span>Total</span>
                <span>US ${{ cartTotal.toFixed(2) }}</span>
              </div>
            </div>

            <!-- Buttons -->
            <div class="space-y-3">
              <button
                @click="checkout"
                class="w-full bg-pink-600 text-white py-3 px-4 rounded-lg hover:bg-pink-700 transition-colors duration-200 font-medium"
              >
                Checkout
              </button>
              <button
                @click="continueShopping"
                class="w-full bg-gray-200 text-gray-900 py-3 px-4 rounded-lg hover:bg-gray-300 transition-colors duration-200 font-medium"
              >
                Continue Shopping
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <main class="pt-20 flex-grow">
      <router-view />
    </main>

    <!-- Footer -->
    <footer class="footer bg-pink-50 py-10 md:py-16 px-4 text-gray-700">
      <div
        class="container mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-8"
      >
        <!-- Column 1: Follow Us -->
        <div class="follow-us-parent">
          <h3 class="text-lg font-semibold text-pink-800 mb-4">Follow Us</h3>
          <ul class="space-y-2">
            <li
              v-for="(social, index) in socialPlatforms"
              :key="'social-' + index"
              class="flex items-center space-x-2 hover:text-pink-600 cursor-pointer"
            >
              <svg
                class="w-5 h-5 fill-current"
                viewBox="0 0 24 24"
                aria-hidden="true"
              >
                <path :d="social.iconPath" />
              </svg>
              <span class="text-sm">{{ social.name }}</span>
            </li>
          </ul>
        </div>

        <!-- Column 2: Customer Services -->
        <div class="customer-services-parent">
          <h3 class="text-lg font-semibold text-pink-800 mb-4">
            CUSTOMER SERVICES
          </h3>
          <ul class="space-y-2">
            <li
              v-for="(service, index) in customerServices"
              :key="'service-' + index"
              class="flex items-center space-x-2 hover:text-pink-600 cursor-pointer"
            >
              <svg
                class="w-5 h-5 fill-current"
                viewBox="0 0 24 24"
                aria-hidden="true"
              >
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
            <li
              v-for="(contact, index) in contactInfo"
              :key="'contact-' + index"
              class="flex items-center space-x-2 hover:text-pink-600 cursor-pointer"
            >
              <svg
                class="w-5 h-5 fill-current"
                viewBox="0 0 24 24"
                aria-hidden="true"
              >
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
            <img
              v-for="(payment, index) in paymentMethods"
              :key="'payment-' + index"
              class="h-10 w-auto object-contain rounded"
              :alt="payment.name"
              :src="payment.image"
            />
          </div>
        </div>

        <!-- Column 5: App QR Code -->
        <div class="zando-app-qr-container text-center p-4">
          <h3 class="text-xl font-semibold mb-3">SN.Fashion APP</h3>
          <img
            src="/images/qr code.png"
            alt="SN.Fashion App QR Code"
            class="qr-code-image w-40 h-40 mx-auto border border-gray-300 p-1"
          />
        </div>
      </div>

      <!-- Bottom Bar -->
      <div
        class="line-parent mt-10 pt-6 border-t-2 border-pink-700 flex flex-col sm:flex-row justify-between items-center text-center sm:text-left"
      >
        <p class="text-xs text-gray-500 mb-4 sm:mb-0">
          © SN.Fashion 2024. All Rights Reserved.
        </p>
        <button
          @click="scrollToTop"
          class="hover:text-pink-600"
          aria-label="Scroll to top"
        >
          <svg
            class="w-7 h-7 stroke-current text-gray-600"
            viewBox="0 0 34 34"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
            aria-hidden="true"
          >
            <path
              d="M16.9999 3.3999L26.0666 12.4666M16.9999 3.3999L7.93327 12.4666M16.9999 3.3999V27.1999M2.2666 30.5999H31.7333"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
            />
          </svg>
        </button>
      </div>
    </footer>

    <div class="text-center text-xs text-gray-500 py-4 bg-gray-50">
      Powered by SN.Fashion © 2024
    </div>

    <!-- Menu Popup (Off-canvas style) -->
    <div
      v-if="isMenuOpen"
      @click="closeMenuOverlay"
      class="popup-overlay fixed inset-0 bg-black/30 flex items-start justify-start z-[100]"
    >
      <div
        @click.stop
        class="menu-popup-content w-64 sm:w-72 bg-white h-full shadow-xl flex flex-col overflow-y-auto p-5"
      >
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-xl font-semibold text-pink-700">Menu</h2>
          <button @click="closeMenu" aria-label="Close menu">
            <svg
              class="w-6 h-6 text-gray-600 hover:text-pink-700"
              fill="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"
              />
            </svg>
          </button>
        </div>

        <nav>
          <ul class="space-y-2">
            <li v-for="(menuItem, index) in menuItems" :key="'menu-' + index">
              <router-link
                :to="menuItem.path"
                @click="closeMenu"
                class="block py-2 px-3 rounded hover:bg-pink-50 hover:text-pink-700"
              >
                {{ menuItem.title }}
              </router-link>
            </li>
          </ul>
        </nav>

        <div class="mt-auto pt-4 border-t border-gray-200 space-y-2">
          <router-link
            v-for="(authLink, index) in authLinks"
            :key="'mobile-auth-' + index"
            :to="authLink.path"
            @click="closeMenu"
            class="block py-2 px-3 rounded hover:bg-pink-50 hover:text-pink-700"
          >
            {{ authLink.title }}
          </router-link>
        </div>
      </div>
    </div>

    <!-- Account Popup -->
    <div
      v-if="showAccountPopup"
      @click="closeAccountPopup"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[200]"
    >
      <div
        @click.stop
        class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4"
      >
        <!-- Header -->
        <div
          class="flex justify-between items-center p-6 border-b border-gray-200"
        >
          <h2 class="text-xl font-semibold text-gray-900">
            {{ isLoginMode ? "Login" : "Register" }}
          </h2>
          <button
            @click="closeAccountPopup"
            class="text-gray-400 hover:text-gray-600"
          >
            <svg
              class="w-6 h-6"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              ></path>
            </svg>
          </button>
        </div>

        <!-- Form -->
        <div class="p-6">
          <!-- Login Form -->
          <form
            v-if="isLoginMode"
            @submit.prevent="submitLoginForm"
            class="space-y-4"
          >
            <div>
              <label
                for="login-email"
                class="block text-sm font-medium text-gray-700 mb-1"
                >Email</label
              >
              <input
                v-model="loginForm.email"
                type="email"
                id="login-email"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                placeholder="Enter your email"
              />
            </div>

            <div>
              <label
                for="login-password"
                class="block text-sm font-medium text-gray-700 mb-1"
                >Password</label
              >
              <input
                v-model="loginForm.password"
                type="password"
                id="login-password"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                placeholder="Enter your password"
              />
            </div>

            <div class="flex items-center justify-between">
              <label class="flex items-center">
                <input
                  type="checkbox"
                  class="rounded border-gray-300 text-pink-600 focus:ring-pink-500"
                />
                <span class="ml-2 text-sm text-gray-600">Remember me</span>
              </label>
              <a href="#" class="text-sm text-pink-600 hover:text-pink-700"
                >Forgot password?</a
              >
            </div>

            <button
              type="submit"
              :disabled="isLoading"
              class="w-full bg-pink-600 text-white py-2 px-4 rounded-md hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              {{ isLoading ? "Logging in..." : "Login" }}
            </button>
          </form>

          <!-- Register Form -->
          <form v-else @submit.prevent="submitRegisterForm" class="space-y-6">
            <!-- Grid Container -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <!-- Full Name -->
              <div>
                <label
                  for="register-name"
                  class="block text-sm font-medium text-gray-700 mb-1"
                  >Full Name</label
                >
                <input
                  v-model="registerForm.name"
                  type="text"
                  id="register-name"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                  placeholder="Enter your full name"
                />
              </div>

              <!-- Email -->
              <div>
                <label
                  for="register-email"
                  class="block text-sm font-medium text-gray-700 mb-1"
                  >Email</label
                >
                <input
                  v-model="registerForm.email"
                  type="email"
                  id="register-email"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                  placeholder="Enter your email"
                />
              </div>

              <!-- Telephone -->
              <div>
                <label
                  for="register-phone"
                  class="block text-sm font-medium text-gray-700 mb-1"
                  >Telephone</label
                >
                <input
                  v-model="registerForm.phone"
                  type="telephone"
                  id="register-phone"
                  required
                  pattern="^[0-9]{8,15}$"
                  title="Please enter a valid phone number (8-15 digits)"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                  placeholder="Enter your phone number"
                />
              </div>

              <!-- Country -->
              <div>
                <label
                  for="register-country"
                  class="block text-sm font-medium text-gray-700 mb-1"
                >
                  Country
                </label>
                <select
                  v-model="registerForm.country"
                  id="register-country
    "
                  placeholder="Cambodia"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                >
                  <option value="" disabled>Select your country</option>
                  <option value="Cambodia">Cambodia</option>
                </select>
              </div>
              <!-- city -->
              <div>
                <label
                  for="register-city"
                  class="block text-sm font-medium text-gray-700 mb-1"
                >
                  Province / City
                </label>
                <select
                  v-model="registerForm.city"
                  id="register-city"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent max-h-60 overflow-y-auto"
                >
                  <option value="" disabled>Select your province</option>
                  <option value="phnom-penh">Phnom Penh</option>
                  <option value="banteay-meanchey">Banteay Meanchey</option>
                  <option value="battambang">Battambang</option>
                  <option value="kampong-cham">Kampong Cham</option>
                  <option value="kampong-chhnang">Kampong Chhnang</option>
                  <option value="kampong-speu">Kampong Speu</option>
                  <option value="kampong-thom">Kampong Thom</option>
                  <option value="kampot">Kampot</option>
                  <option value="kandal">Kandal</option>
                  <option value="kep">Kep</option>
                  <option value="koh-kong">Koh Kong</option>
                  <option value="kratie">Kratie</option>
                  <option value="mondulkiri">Mondulkiri</option>
                  <option value="oddar-meanchey">Oddar Meanchey</option>
                  <option value="pailin">Pailin</option>
                  <option value="preah-sihanouk">Preah Sihanouk</option>
                  <option value="preah-vihear">Preah Vihear</option>
                  <option value="pursat">Pursat</option>
                  <option value="prey-veng">Prey Veng</option>
                  <option value="ratanakiri">Ratanakiri</option>
                  <option value="siem-reap">Siem Reap</option>
                  <option value="stung-treng">Stung Treng</option>
                  <option value="svay-rieng">Svay Rieng</option>
                  <option value="takeo">Takeo</option>
                  <option value="tboung-khmum">Tboung Khmum</option>
                </select>
              </div>

              <!-- Password -->
              <div>
                <label
                  for="register-password"
                  class="block text-sm font-medium text-gray-700 mb-1"
                  >Password</label
                >
                <input
                  v-model="registerForm.password"
                  type="password"
                  id="register-password"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                  placeholder="Enter your password"
                />
              </div>

              <!-- Confirm Password -->
              <div>
                <label
                  for="register-confirm-password"
                  class="block text-sm font-medium text-gray-700 mb-1"
                  >Confirm Password</label
                >
                <input
                  v-model="registerForm.confirmPassword"
                  type="password"
                  id="register-confirm-password"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                  placeholder="Confirm your password"
                />
              </div>
            </div>

            <!-- Terms & Privacy -->
            <div class="flex items-center">
              <input
                type="checkbox"
                required
                class="rounded border-gray-300 text-pink-600 focus:ring-pink-500"
              />
              <span class="ml-2 text-sm text-gray-600">
                I agree to the
                <a href="#" class="text-pink-600 hover:text-pink-700"
                  >Terms of Service</a
                >
                and
                <a href="#" class="text-pink-600 hover:text-pink-700"
                  >Privacy Policy</a
                >
              </span>
            </div>

            <!-- Submit Button -->
            <button
              type="submit"
              :disabled="isLoading"
              class="w-full bg-pink-600 text-white py-2 px-4 rounded-md hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              {{ isLoading ? "Creating account..." : "Create Account" }}
            </button>
          </form>

          <!-- Toggle between Login and Register -->
          <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
              {{
                isLoginMode
                  ? "Don't have an account?"
                  : "Already have an account?"
              }}
              <button
                @click="toggleMode"
                class="text-pink-600 hover:text-pink-700 font-medium"
              >
                {{ isLoginMode ? "Register here" : "Login here" }}
              </button>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref } from "vue";
import api from "../../axios/Axios";

const parentCategories = ref([]);
const navigationItems = ref([]);

const fetchParentCategories = async () => {
  try {
    const response = await api.get("/public/parent-categories");
    const data = response.data?.data ?? [];
    parentCategories.value = Array.isArray(data) ? data : [];

    // Transform the API data into navigation items
    navigationItems.value = parentCategories.value.map((category) => ({
      title: category.name,
      path: `/category/${category.name.toLowerCase()}`,
      subItems:
        category.categories?.map((subCategory) => ({
          title: subCategory.name,
          path: `/category/${category.name.toLowerCase()}/${subCategory.name
            .toLowerCase()
            .replace(/\s+/g, "-")}`,
          image: subCategory.image,
          description: subCategory.description,
        })) || [],
    }));

    // Add static navigation items
  } catch (error) {
    console.error(
      "Failed to fetch parent categories:",
      error.response?.data || error.message
    );
    parentCategories.value = [];
    navigationItems.value = [];
  }
};

onMounted(() => {
  fetchParentCategories();
});

// Expose data to the script section
defineExpose({
  parentCategories,
  navigationItems,
});
</script>

<script>
export default {
  data() {
    return {
      isMobileMenuOpen: false,
      isMenuOpen: false,
      isSearchFormOpen: false,
      searchQuery: "",
      searchSuggestions: [
        "T-Shirts",
        "Dresses",
        "Jeans",
        "Shoes",
        "Accessories",
      ],
      searchResults: [],
      isSearching: false,
      searchPerformed: false,
      searchTimeout: null,

      // Account popup functionality
      showAccountPopup: false,
      isLoginMode: true,
      isLoading: false,

      // Authentication state
      isLoggedIn: false,
      userInfo: null,
      isProfileDropdownOpen: false,
      cartItemCount: Number(sessionStorage.getItem("totalCartCount") || 0),
      favoriteItemCount: localStorage.getItem("wishCount") || 0,
      countRefreshInterval: null,

      // Cart drawer state
      isCartDrawerOpen: false,
      cartItems: [],

      // Login form data
      loginForm: {
        email: "",
        password: "",
      },

      // Register form data
  registerForm: {
      name: "",
      email: "",
      phone: "",
      password: "",
      confirmPassword: "",
      country: "",
      city: "",
    },

      // Navigation data - will be populated from API
      navigationItems: [],

      mobileNavigationItems: [{ title: "list", path: "/product/list" }],

      headerActions: [
        {
          iconPath:
            "M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4a1.5 1.5 0 10-3 0v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z",
        },
        {
          iconPath:
            "M12 12c2.761 0 5-2.239 5-5s-2.239-5-5-5-5 2.239-5 5 2.239 5 5 5zm0 2c-3.866 0-7 3.134-7 7v1h14v-1c0-3.866-3.134-7-7-7z",
        },
      ],

      // Footer data
      socialPlatforms: [
        {
          name: "Facebook",
          iconPath:
            "M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z",
        },
        {
          name: "Instagram",
          iconPath:
            "M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z",
        },
        {
          name: "Twitter",
          iconPath:
            "M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z",
        },
        {
          name: "YouTube",
          iconPath:
            "M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z",
        },
      ],

      customerServices: [
        {
          name: "Privacy Policy",
          iconPath:
            "M12 1.5L3 5.5V11.5C3 17.05 6.84 22.24 12 23.5C17.16 22.24 21 17.05 21 11.5V5.5L12 1.5ZM11 7.5H13V9.5H11V7.5ZM11 11.5H13V17.5H11V11.5Z",
        },
        {
          name: "Online exchange policy",
          iconPath:
            "M12 2.75C10.0716 2.75 8.18657 3.32183 6.58319 4.39317C4.97982 5.46452 3.73013 6.98726 2.99218 8.76884C2.25422 10.5504 2.06114 12.5108 2.43735 14.4021C2.81355 16.2934 3.74215 18.0307 5.10571 19.3943C6.46928 20.7579 8.20656 21.6865 10.0979 22.0627C11.9892 22.4389 13.9496 22.2458 15.7312 21.5078C17.5127 20.7699 19.0355 19.5202 20.1068 17.9168C21.1782 16.3134 21.75 14.4284 21.75 12.5C21.7473 9.91498 20.7192 7.43661 18.8913 5.60872C17.0634 3.78084 14.585 2.75273 12 2.75ZM12 18.5C11.7775 18.5 11.56 18.434 11.375 18.3104C11.19 18.1868 11.0458 18.0111 10.9606 17.8055C10.8755 17.6 10.8532 17.3738 10.8966 17.1555C10.94 16.9373 11.0472 16.7368 11.2045 16.5795C11.3618 16.4222 11.5623 16.315 11.7805 16.2716C11.9988 16.2282 12.225 16.2505 12.4305 16.3356C12.6361 16.4208 12.8118 16.565 12.9354 16.75C13.059 16.935 13.125 17.1525 13.125 17.375C13.125 17.6734 13.0065 17.9595 12.7955 18.1705C12.5845 18.3815 12.2984 18.5 12 18.5ZM12.75 13.9325V14C12.75 14.1989 12.671 14.3897 12.5303 14.5303C12.3897 14.671 12.1989 14.75 12 14.75C11.8011 14.75 11.6103 14.671 11.4697 14.5303C11.329 14.3897 11.25 14.1989 11.25 14V13.25C11.25 13.0511 11.329 12.8603 11.4697 12.7197C11.6103 12.579 11.8011 12.5 12 12.5C13.2403 12.5 14.25 11.6562 14.25 10.625C14.25 9.59375 13.2403 8.75 12 8.75C10.7597 8.75 9.75 9.59375 9.75 10.625V11C9.75 11.1989 9.67099 11.3897 9.53033 11.5303C9.38968 11.671 9.19892 11.75 9 11.75C8.80109 11.75 8.6103 11.671 8.46967 11.5303C8.32902 11.3897 8.25 11.1989 8.25 11V10.625C8.25 8.76406 9.93188 7.25 12 7.25C14.0681 7.25 15.75 8.76406 15.75 10.625C15.75 12.2544 14.46 13.6184 12.75 13.9325Z",
        },
      ],

      contactInfo: [
        {
          type: "email",
          value: "infome@gmail.com",
          iconPath:
            "M12 1.5L3 5.5V11.5C3 17.05 6.84 22.24 12 23.5C17.16 22.24 21 17.05 21 11.5V5.5L12 1.5ZM19.3334 7.5L12 12.5L4.6666 7.5V6.5L12 11.5L19.3334 6.5V7.5Z",
        },
        {
          type: "phone",
          value: "(+855) 016 356624",
          iconPath:
            "M12 1.5L3 5.5V11.5C3 17.05 6.84 22.24 12 23.5C17.16 22.24 21 17.05 21 11.5V5.5L12 1.5ZM11 7.5H13V9.5H11V7.5ZM11 11.5H13V17.5H11V11.5Z",
        },
      ],
    };
  },

  mounted() {
    // Check authentication status on mount
    this.checkAuthStatus();
    window.addEventListener("auth-required", () => {
      console.log("require auth");
      this.showAccountPopup = !this.showAccountPopup;
    });

    // Add event listeners
    document.addEventListener("click", this.closeProfileDropdown);
    document.addEventListener("keydown", this.handleEscapeKey);

    // Listen for storage changes to update cart/favorite counts
    window.addEventListener("storage", this.handleStorageChange);

    // Listen for custom events from other components
    window.addEventListener("cart-updated", this.onCartUpdate);
    window.addEventListener("wishlist-updated", this.onWishlistUpdate);
    window.addEventListener("open-cart-drawer", this.openCartDrawer);

    // Listen for custom localStorage change events (real-time updates)
    window.addEventListener("localStorage-changed", this.onLocalStorageChanged);

    // Initialize localStorage change detection
    this.handleLocalStorageChange();
    this.handleLocalStorageClear();

    // Load initial cart count from localStorage
    this.updateCartCount();

    // Load initial cart items
    this.loadCartItems();

    // Expose cart update methods globally for other components
    window.updateLayoutCartCount = this.updateCartCount.bind(this);
    window.refreshLayoutCartCount = this.refreshCartCount.bind(this);
    window.setLayoutCartCount = this.setCartCount.bind(this);
    window.incrementLayoutCartCount = this.incrementCartCount.bind(this);
    window.decrementLayoutCartCount = this.decrementCartCount.bind(this);
    window.testLayoutCartCountChange = this.testCartCountChange.bind(this);

    // Start count refresh if user is logged in
    if (this.isLoggedIn) {
      this.startCountRefresh();
    }
  },

  beforeUnmount() {
    // Clean up event listeners
    document.removeEventListener("click", this.closeProfileDropdown);
    document.removeEventListener("keydown", this.handleEscapeKey);
    window.removeEventListener("storage", this.handleStorageChange);

    // Remove custom event listeners
    window.removeEventListener("cart-updated", this.onCartUpdate);
    window.removeEventListener("wishlist-updated", this.onWishlistUpdate);
    window.removeEventListener("open-cart-drawer", this.openCartDrawer);
    window.removeEventListener(
      "localStorage-changed",
      this.onLocalStorageChanged
    );

    // Clean up global methods
    delete window.updateLayoutCartCount;
    delete window.refreshLayoutCartCount;
    delete window.setLayoutCartCount;
    delete window.incrementLayoutCartCount;
    delete window.decrementLayoutCartCount;
    delete window.testLayoutCartCountChange;

    // Stop count refresh
    this.stopCountRefresh();
  },

  watch: {
    searchQuery(newQuery) {
      // Clear any existing timeout
      if (this.searchTimeout) {
        clearTimeout(this.searchTimeout);
      }

      // Set a new timeout for search
      this.searchTimeout = setTimeout(() => {
        if (newQuery.trim()) {
          this.handleSearch();
        } else {
          this.searchResults = [];
          this.searchPerformed = false;
        }
      }, 500);
    },

    // Watch for authentication state changes
    isLoggedIn(newValue) {
      if (newValue) {
        // User just logged in, refresh counts
        this.loadCartAndFavoriteCounts();
      } else {
        this.favoriteItemCount = 0;
      }
    },

    // Watch for route changes
    $route(to, from) {
      this.onRouteChange();
    },

    // Watch for cart count changes to ensure reactivity
    cartItemCount(newValue, oldValue) {
      if (newValue !== oldValue) {
        // Emit cart update event when cart count changes
        this.emitCartUpdate();
      }
    },
  },

  computed: {
    // Generate mobile navigation items from parent categories
    dynamicMobileNavigationItems() {
      const mobileItems = [{ title: "list", path: "/product/list" }];

      // Add parent categories to mobile navigation
      // This will be populated from the API via script setup
      return mobileItems;
    },

    // Calculate cart subtotal (before delivery and savings)
    cartSubtotal() {
      return this.cartItems.reduce((total, item) => {
        return total + item.price * item.quantity;
      }, 0);
    },

    // Calculate delivery fee
    deliveryFee() {
      return this.cartSubtotal > 0 ? 1.0 : 0;
    },

    // Calculate total savings (you can customize this logic)
    totalSavings() {
      // Example: 10% discount on orders over $50
      if (this.cartSubtotal >= 50) {
        return this.cartSubtotal * 0.1;
      }
      return 0;
    },

    // Calculate cart total (subtotal + delivery - savings)
    cartTotal() {
      return this.cartSubtotal + this.deliveryFee - this.totalSavings;
    },
  },

  _methods: {
    scrollToTop() {
      window.scrollTo({ top: 0, behavior: "smooth" });
    },
    closeMenu() {
      this.isMenuOpen = false;
    },
    closeMenuOverlay() {
      this.isMenuOpen = false;
    },

    // Account popup methods
    closeAccountPopup() {
      this.showAccountPopup = false;
      this.resetForms();
    },

    toggleMode() {
      this.isLoginMode = !this.isLoginMode;
      this.resetForms();
    },

    resetForms() {
      this.loginForm = {
        email: "",
        password: "",
      };
      this.registerForm = {
        name: "",
        email: "",
        password: "",
        confirmPassword: "",
      };
      this.isLoading = false;
    },

    async handleLogin() {
      if (!this.loginForm.email || !this.loginForm.password) {
        alert("Please fill in all fields");
        return;
      }

      this.isLoading = true;

      try {
        // Call your login API here
        const response = await api.post("/login", {
          email: this.loginForm.email,
          password: this.loginForm.password,
        });

        // Handle successful login
        console.log("Login successful:", response.data);

        // Store token in localStorage
        sessionStorage.setItem("auth_token", response.data.token);
        sessionStorage.setItem("user_id", JSON.stringify(response.data.id));

        // Use the centralized login success handler
        this.onLoginSuccess(response.data.user);
      } catch (error) {
        console.error("Login error:", error);
        alert(
          error.response?.data?.message || "Login failed. Please try again."
        );
      } finally {
        this.isLoading = false;
      }
    },

  async handleRegister() {
  // Reset errors
  const nameRegex = /^[A-Za-z\s]{2,255}$/;
  const phoneRegex = /^[0-9]{8,15}$/;
  const countryRegex = /^[A-Za-z\s]{2,50}$/;
  const cityRegex = /^[A-Za-z\s.\-]{2,50}$/;

  // 1️⃣ Required Fields
  if (
    !this.registerForm.name ||
    !this.registerForm.email ||
    !this.registerForm.password ||
    !this.registerForm.confirmPassword ||
    !this.registerForm.phone ||
    !this.registerForm.country ||
    !this.registerForm.city
  ) {
    alert("Please fill in all required fields");
    return;
  }

  // 2️⃣ Name Validation
  if (!nameRegex.test(this.registerForm.name)) {
    alert("Name can only contain letters and spaces (2–255 characters).");
    return;
  }

  // 3️⃣ Email Format (basic browser validation already covers this)
  const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailPattern.test(this.registerForm.email)) {
    alert("Please enter a valid email address.");
    return;
  }

  // 4️⃣ Password Match + Length (min 8)
  if (this.registerForm.password !== this.registerForm.confirmPassword) {
    alert("Passwords do not match");
    return;
  }
  if (this.registerForm.password.length < 8) {
    alert("Password must be at least 8 characters long");
    return;
  }

  // 5️⃣ Phone Validation
  if (!phoneRegex.test(this.registerForm.phone)) {
    alert("Please enter a valid phone number (8–15 digits).");
    return;
  }

  // 6️⃣ Country Validation
  if (!countryRegex.test(this.registerForm.country)) {
    alert("Country must contain only letters and spaces (2–50 characters).");
    return;
  }

  // 7️⃣ City Validation
  if (!cityRegex.test(this.registerForm.city)) {
    alert("City must contain only letters, dots, or dashes (2–50 characters).");
    return;
  }

  // 8️⃣ Send Data to API
  this.isLoading = true;

  try {
    const response = await api.post("/register", {
      name: this.registerForm.name,
      email: this.registerForm.email,
      password: this.registerForm.password,
      phone: this.registerForm.phone,
      country: this.registerForm.country,
      city: this.registerForm.city,
    });

    console.log("Registration successful:", response.data);

    this.closeAccountPopup();
    alert("Registration successful! Please log in with your new account.");

    // Switch to login mode
    this.isLoginMode = true;
    this.resetForms();

    // Optional auto-login if token is returned
    if (response.data.token) {
      sessionStorage.setItem("auth_token", response.data.token);
      sessionStorage.setItem("user", JSON.stringify(response.data.user));
      this.onLoginSuccess(response.data.user);
    }
  } catch (error) {
    console.error("Registration error:", error);
    if (error.response?.data?.errors) {
      // Laravel validation errors
      const messages = Object.values(error.response.data.errors).flat().join("\n");
      alert(messages);
    } else {
      alert(error.response?.data?.message || "Registration failed. Please try again.");
    }
  } finally {
    this.isLoading = false;
  }
},


    handleEscapeKey(event) {
      if (event.key === "Escape" && this.showAccountPopup) {
        this.closeAccountPopup();
      }
      if (event.key === "Escape" && this.isSearchFormOpen) {
        this.closeSearchForm();
      }
      if (event.key === "Escape" && this.isProfileDropdownOpen) {
        this.isProfileDropdownOpen = false;
      }
    },

    // Search functionality
    toggleSearchForm() {
      this.isSearchFormOpen = !this.isSearchFormOpen;
      if (this.isSearchFormOpen) {
        // Close mobile menu if open
        this.isMobileMenuOpen = false;
        // Focus on search input after a short delay
        this.$nextTick(() => {
          const searchInput = document.querySelector(
            'input[placeholder*="Search"]'
          );
          if (searchInput) {
            searchInput.focus();
          }
        });
      }
    },

    closeSearchForm() {
      this.isSearchFormOpen = false;
      this.searchQuery = "";
      this.searchResults = [];
      this.searchPerformed = false;
    },

    // Search for products using the new API endpoint
    // Search for products using the new API endpoint
    async handleSearch() {
      if (!this.searchQuery.trim()) {
        return;
      }

      try {
        this.isSearching = true;
        this.searchPerformed = true;
        this.searchResults = [];

        console.log("Searching for:", this.searchQuery);

        // Add to search suggestions
        this.addToSuggestions(this.searchQuery);

        // Fetch products from new search API endpoint
        const response = await api.get(
          `/public/products/search/${encodeURIComponent(this.searchQuery)}`
        );
        let searchResults = [];
        if (response && response.data && Array.isArray(response.data.data)) {
          searchResults = response.data.data;
        }
        this.searchResults = searchResults;

        console.log("Search results:", searchResults);
      } catch (error) {
        console.error("Search error:", error);
        this.searchResults = [];
        // Don't show alert for every search error, just log it
        if (this.searchQuery.trim()) {
          console.log("Search failed for:", this.searchQuery);
        }
      } finally {
        this.isSearching = false;
      }
    },

    // Add search query to suggestions if it's new
    addToSuggestions(query) {
      if (query && !this.searchSuggestions.includes(query)) {
        this.searchSuggestions.unshift(query);
        // Keep only the last 10 suggestions
        if (this.searchSuggestions.length > 10) {
          this.searchSuggestions = this.searchSuggestions.slice(0, 10);
        }
        // Save to localStorage for persistence
        localStorage.setItem(
          "searchSuggestions",
          JSON.stringify(this.searchSuggestions)
        );
      }
    },

    // Load search suggestions from localStorage
    loadSearchSuggestions() {
      const saved = localStorage.getItem("searchSuggestions");
      if (saved) {
        try {
          this.searchSuggestions = JSON.parse(saved);
        } catch (e) {
          console.error("Failed to parse search suggestions:", e);
        }
      }
    },

    // Search products by name
    async searchProducts(query) {
      try {
        // Fetch all products from API
        const response = await api.get("/public/products");
        const products = response.data?.data || response.data || [];

        if (!Array.isArray(products)) {
          console.error("Invalid products data format:", products);
          return [];
        }

        // Filter products by name (case-insensitive)
        const searchTerm = query.toLowerCase().trim();
        const filteredProducts = products.filter((product) => {
          if (!product.name) return false;

          const productName = product.name.toLowerCase();
          const productDescription = (product.description || "").toLowerCase();
          const productCategory = (product.category || "").toLowerCase();

          return (
            productName.includes(searchTerm) ||
            productDescription.includes(searchTerm) ||
            productCategory.includes(searchTerm)
          );
        });

        // Sort by relevance (exact matches first, then partial matches)
        filteredProducts.sort((a, b) => {
          const aName = a.name.toLowerCase();
          const bName = b.name.toLowerCase();

          const aExact = aName === searchTerm;
          const bExact = bName === searchTerm;

          if (aExact && !bExact) return -1;
          if (!aExact && bExact) return 1;

          const aStartsWith = aName.startsWith(searchTerm);
          const bStartsWith = bName.startsWith(searchTerm);

          if (aStartsWith && !bStartsWith) return -1;
          if (!aStartsWith && bStartsWith) return 1;

          return 0;
        });

        return filteredProducts.slice(0, 20); // Limit to 20 results
      } catch (error) {
        console.error("Failed to fetch products for search:", error);
        throw error;
      }
    },

    // Clear search results
    clearSearchResults() {
      this.searchResults = [];
      this.searchPerformed = false;
      this.searchQuery = "";
    },

    // Navigate to product detail page
    goToProduct(productId) {
      if (!productId) return;

      // Close search form
      this.closeSearchForm();

      // Navigate to product detail page
      this.$router.push(`/product/${productId}`);
    },

    // Format price for display
    formatPrice(price) {
      if (price == null || price === undefined) return "";
      return new Intl.NumberFormat("en-US", {
        style: "currency",
        currency: "USD",
      }).format(price);
    },

    // Handle form submission
    handleFormSubmit() {
      // Clear any existing timeout
      if (this.searchTimeout) {
        clearTimeout(this.searchTimeout);
      }

      // Perform immediate search
      this.handleSearch();
    },

    // Authentication methods
    checkAuthStatus() {
      const token =
        localStorage.getItem("token") || sessionStorage.getItem("auth_token");
      if (token) {
        this.isLoggedIn = true;
        // Try to get user info from localStorage
        const userStr = localStorage.getItem("user");
        if (userStr) {
          try {
            this.userInfo = JSON.parse(userStr);
          } catch (e) {
            console.error("Failed to parse user info:", e);
            this.userInfo = null;
          }
        }
        // Load cart and favorite counts
        this.loadCartAndFavoriteCounts();
        // Start count refresh if user is logged in
        this.startCountRefresh();
      } else {
        this.isLoggedIn = false;
        this.userInfo = null;
        this.favoriteItemCount = 0;
      }
    },

    loadCartAndFavoriteCounts() {
      const oldWishlistCount = this.favoriteItemCount;
      const oldCartCount = this.cartItemCount;

      // Load cart count from localStorage
      try {
        const cartStr = localStorage.getItem("cart");
        if (cartStr) {
          const cart = JSON.parse(cartStr);
        }
      } catch (e) {
        console.error("Failed to parse cart data:", e);
      }

      // Load totalCartCount from localStorage
      try {
        const totalCartCount = sessionStorage.getItem("totalCartCount");
        if (totalCartCount) {
          this.cartItemCount = Number(totalCartCount);
        } else {
          this.cartItemCount = 0;
        }
      } catch (e) {
        console.error("Failed to parse totalCartCount data:", e);
        this.cartItemCount = 0;
      }

      // Load favorite count from localStorage
      try {
        const wishlistStr = localStorage.getItem("wishCount");
        if (wishlistStr) {
          this.favoriteItemCount = Number(wishlistStr);
        }
      } catch (e) {
        console.error("Failed to parse wishlist data:", e);
        this.favoriteItemCount = 0;
      }

      // Emit events if counts changed
      if (oldWishlistCount !== this.favoriteItemCount) {
        this.emitWishlistUpdate();
      }

      if (oldCartCount !== this.cartItemCount) {
        this.emitCartUpdate();
      }
    },

    // Manually refresh counts (can be called from other components)
    refreshCounts() {
      if (this.isLoggedIn) {
        this.loadCartAndFavoriteCounts();
      }
    },

    // Manually refresh cart count (can be called from other components)
    refreshCartCount() {
      this.updateCartCount();
    },

    toggleProfileDropdown() {
      this.isProfileDropdownOpen = !this.isProfileDropdownOpen;
      
    },

    // Close profile dropdown when clicking outside
    closeProfileDropdown(event) {
      // Check if the click is outside the profile dropdown
      const profileDropdown = event.target.closest(".relative");
      if (!profileDropdown) {
        this.isProfileDropdownOpen = false;
      }
    },

    handleLogout() {
      // Clear authentication data
      sessionStorage.removeItem("token");
      sessionStorage.removeItem("user");
      sessionStorage.removeItem("auth_token");

      // Reset state
      this.isLoggedIn = false;
      this.userInfo = null;
      this.isProfileDropdownOpen = false;
      this.favoriteItemCount = 0;
      this.cartItemCount = 0;

      // Stop count refresh
      this.stopCountRefresh();

      // Close profile dropdown
      this.isProfileDropdownOpen = false;

      // Show success message
      alert("Logged out successfully!");

      // Optionally redirect to home page
      if (this.$router.currentRoute.value.path !== "/") {
        this.$router.push("/");
      }
    },

    // Handle storage changes for cart/favorite counts (from other tabs/windows)
    handleStorageChange(event) {
      if (event.key === "cart" || event.key === "totalCartCount") {
        this.loadCartAndFavoriteCounts();
      } else if (event.key === "wishlist") {
        this.loadCartAndFavoriteCounts();
      }
    },

    // Handle localStorage changes within the same tab (real-time updates)
    handleLocalStorageChange() {
      // Create a custom event listener for localStorage changes
      const originalSetItem = localStorage.setItem;
      const originalRemoveItem = localStorage.removeItem;

      // Override setItem to detect changes
      localStorage.setItem = function (key, value) {
        originalSetItem.call(this, key, value);

        // Dispatch custom event for same-tab changes
        if (key === "totalCartCount") {
          window.dispatchEvent(
            new CustomEvent("localStorage-changed", {
              detail: { key, value, action: "set" },
            })
          );
        }
      };

      // Override removeItem to detect changes
      localStorage.removeItem = function (key) {
        originalRemoveItem.call(this, key);

        // Dispatch custom event for same-tab changes
        if (key === "totalCartCount") {
          window.dispatchEvent(
            new CustomEvent("localStorage-changed", {
              detail: { key, value, action: "remove" },
            })
          );
        }
      };
    },

    // Handle custom localStorage change events (real-time updates)
    onLocalStorageChanged(event) {
      const { key, value, action } = event.detail;

      if (key === "totalCartCount") {
        if (action === "set") {
          this.cartItemCount = Number(value);
          console.log("Cart count updated in real-time:", this.cartItemCount);
        } else if (action === "remove") {
          this.cartItemCount = 0;
          console.log("Cart count removed, set to 0");
        }

        // Emit cart update event
        this.emitCartUpdate();
      } else if (key === "all" && action === "clear") {
        // Handle localStorage clear
        this.cartItemCount = 0;
        this.favoriteItemCount = 0;
        console.log("localStorage cleared, resetting counts");

        // Emit both events
        this.emitCartUpdate();
        this.emitWishlistUpdate();
      }
    },

    // Handle localStorage clear event
    handleLocalStorageClear() {
      // Override clear method to detect when localStorage is cleared
      const originalClear = localStorage.clear;
      localStorage.clear = function () {
        originalClear.call(this);

        // Dispatch custom event for clear
        window.dispatchEvent(
          new CustomEvent("localStorage-changed", {
            detail: { key: "all", value: null, action: "clear" },
          })
        );
      };
    },

    // Handle cart updates (can be called from other components)
    onCartUpdate() {
      this.loadCartAndFavoriteCounts();
      this.loadCartItems(); // Also reload cart items
    },

    // Update cart count from totalCartCount (can be called from other components)
    updateCartCount() {
      const totalCartCount = localStorage.getItem("totalCartCount");
      if (totalCartCount) {
        this.cartItemCount = Number(totalCartCount);
      } else {
        this.cartItemCount = 0;
      }
    },

    // Set cart count directly (can be called from other components)
    setCartCount(count) {
      const newCount = Number(count);
      this.cartItemCount = newCount;

      // Update localStorage (this will trigger the real-time update)
      localStorage.setItem("totalCartCount", newCount);

      // Also emit the cart update event immediately
      this.emitCartUpdate();

      console.log(`Cart count set to: ${newCount}`);
    },

    // Test method to simulate cart count change (for debugging)
    testCartCountChange() {
      const currentCount = this.cartItemCount;
      const newCount = currentCount + 1;
      this.setCartCount(newCount);
      console.log(`Cart count changed from ${currentCount} to ${newCount}`);
    },

    // Increment cart count (can be called from other components)
    incrementCartCount(amount = 1) {
      const newCount = this.cartItemCount + Number(amount);
      this.setCartCount(newCount);
      return newCount;
    },

    // Decrement cart count (can be called from other components)
    decrementCartCount(amount = 1) {
      const newCount = Math.max(0, this.cartItemCount - Number(amount));
      this.setCartCount(newCount);
      return newCount;
    },

    // Cart drawer methods
    async toggleCartDrawer() {
    this.isCartDrawerOpen = !this.isCartDrawerOpen;
      if (this.isCartDrawerOpen) {
        this.loadCartItems();
      }
      const token = sessionStorage.getItem("auth_token");
      const response = await api.get("/cart", {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });
      this.cartItems = response.data.data.items;
      console.log(response.data.data.items);
    },

    openCartDrawer() {
      this.isCartDrawerOpen = true;
      this.loadCartItems();
    },

    closeCartDrawer() {
      this.isCartDrawerOpen = false;
    },

    loadCartItems() {
      const cartItems = JSON.parse(localStorage.getItem("cartItems") || "[]");
      this.cartItems = cartItems;
    },

    removeFromCart(item) {
      const cartItems = JSON.parse(sessionStorage.getItem("cartItems") || "[]");
      const itemIndex = cartItems.findIndex(
        (cartItem) =>
          cartItem.id === item.id &&
          cartItem.size === item.size &&
          cartItem.timestamp === item.timestamp
      );

      if (itemIndex !== -1) {
        const removedQuantity = cartItems[itemIndex].quantity;
        cartItems.splice(itemIndex, 1);

        // Update localStorage
        sessionStorage.setItem("cartItems", JSON.stringify(cartItems));

        // Update cart count
        const newCount = Math.max(0, this.cartItemCount - removedQuantity);
        this.setCartCount(newCount);

        // Reload cart items
        this.loadCartItems();
      }
    },

    increaseQuantity(item) {
      const cartItems = JSON.parse(sessionStorage.getItem("cartItems") || "[]");
      const itemIndex = cartItems.findIndex(
        (cartItem) =>
          cartItem.id === item.id &&
          cartItem.size === item.size &&
          cartItem.timestamp === item.timestamp
      );

      if (itemIndex !== -1) {
        cartItems[itemIndex].quantity += 1;
        sessionStorage.setItem("cartItems", JSON.stringify(cartItems));
        this.setCartCount(this.cartItemCount + 1);
        this.loadCartItems();
      }
    },

    decreaseQuantity(item) {
      const cartItems = JSON.parse(localStorage.getItem("cartItems") || "[]");
      const itemIndex = cartItems.findIndex(
        (cartItem) =>
          cartItem.id === item.id &&
          cartItem.size === item.size &&
          cartItem.timestamp === item.timestamp
      );

      if (itemIndex !== -1) {
        if (cartItems[itemIndex].quantity > 1) {
          cartItems[itemIndex].quantity -= 1;
          localStorage.setItem("cartItems", JSON.stringify(cartItems));
          this.setCartCount(this.cartItemCount - 1);
          this.loadCartItems();
        } else {
          // Remove item if quantity becomes 0
          this.removeFromCart(item);
        }
      }
    },

    checkout() {
      // Navigate to checkout page
      console.log("Proceeding to checkout with items:", this.cartItems);
      this.closeCartDrawer();
      this.$router.push("/checkout");
    },

    continueShopping() {
      this.closeCartDrawer();
      // You can add navigation logic here
      this.$router.push('/');
    },

    clearCart() {
      sessionStorage.removeItem("cartItems");
      sessionStorage.removeItem("totalCartCount");
      this.cartItems = [];
      this.cartItemCount = 0;
      this.closeCartDrawer();
    },

    // Handle wishlist updates (can be called from other components)
    onWishlistUpdate() {
      this.loadCartAndFavoriteCounts();
    },

    // Emit wishlist update event for other components
    emitWishlistUpdate() {
      window.dispatchEvent(
        new CustomEvent("wishlist-updated", {
          detail: { count: this.favoriteItemCount },
        })
      );
    },

    // Emit cart update event for other components
    emitCartUpdate() {
      window.dispatchEvent(
        new CustomEvent("cart-updated", {
          detail: { count: this.cartItemCount },
        })
      );
    },

    // Refresh counts periodically (every 5 seconds)
    startCountRefresh() {
      if (this.countRefreshInterval) {
        clearInterval(this.countRefreshInterval);
      }

      this.countRefreshInterval = setInterval(() => {
        if (this.isLoggedIn) {
          this.loadCartAndFavoriteCounts();
        }
      }, 5000);
    },

    // Stop count refresh
    stopCountRefresh() {
      if (this.countRefreshInterval) {
        clearInterval(this.countRefreshInterval);
        this.countRefreshInterval = null;
      }
    },

    //  Navigate to cart page
    goToCart() {
      if (this.isLoggedIn) {
        this.$router.push("/cart");
      } else {
        alert("Please log in to view your cart.");
        this.showAccountPopup = true; // Open login popup
      }
    },

    // Navigate to wishlist page
    goToWishlist() {
      if (this.isLoggedIn) {
        this.$router.push("/wishlist");
      }
    },

    // Handle router navigation and update counts
    onRouteChange() {
      // Refresh counts when route changes
      if (this.isLoggedIn) {
        this.loadCartAndFavoriteCounts();
      }
    },

    // Check if token is still valid
    async checkTokenValidity() {
      const token =
        sessionStorage.getItem("token") || sessionStorage.getItem("auth_token");
      if (!token) {
        this.handleLogout();
        return false;
      }

      try {
        // You can add an API call here to verify token validity
        // For now, we'll just check if the token exists
        return true;
      } catch (error) {
        console.error("Token validation failed:", error);
        this.handleLogout();
        return false;
      }
    },

    // Handle successful login
    onLoginSuccess(userData) {
      this.isLoggedIn = true;
      this.userInfo = userData;
      this.loadCartAndFavoriteCounts();
      this.startCountRefresh();
      this.closeAccountPopup();

      // Show success message
      alert("Login successful!");

      // Emit event for parent components
      this.$emit("user-logged-in", userData);
    },

    // Handle login form submission
    async submitLoginForm() {
      if (!this.loginForm.email || !this.loginForm.password) {
        alert("Please fill in all fields");
        return;
      }

      await this.handleLogin();
    },

    // Handle register form submission
 async submitRegisterForm() {
  try {
    // ✅ Basic Required Fields Check
    const { name, email, password, confirmPassword, phone, country, city } = this.registerForm;

    if (!name || !email || !password || !confirmPassword) {
      alert("Please fill in all required fields");
      return;
    }

    // ✅ Phone Validation (only numbers & 8-15 digits)
    const phoneRegex = /^[0-9]{8,15}$/;
    if (!phoneRegex.test(phone)) {
      alert("Please enter a valid phone number (8-15 digits)");
      return;
    }

    // ✅ Country Validation (letters & spaces only, min 2 chars)
    const countryRegex = /^[A-Za-z\s]{2,50}$/;
    if (!countryRegex.test(country)) {
      alert("Please enter a valid country name (letters only, min 2 characters)");
      return;
    }

    // ✅ City Validation (letters, spaces, dots & dashes allowed, min 2 chars)
    const cityRegex = /^[A-Za-z\s\.\-]{2,50}$/;
    if (!cityRegex.test(city)) {
      alert("Please enter a valid city name (letters only, min 2 characters)");
      return;
    }

    // ✅ Password Match
    if (password !== confirmPassword) {
      alert("Passwords do not match");
      return;
    }

    // ✅ Password Length
    if (password.length < 6) {
      alert("Password must be at least 6 characters long");
      return;
    }

    // ✅ Proceed to register
    await this.handleRegister();

  } catch (error) {
    console.error("Registration error:", error);
    alert("Something went wrong during registration. Please try again.");
  }
}


  },
  get methods() {
    return this._methods;
  },
  set methods(value) {
    this._methods = value;
  },
};
</script>

<style>
/* Add any custom styles here if needed */
</style>

<style>
.font-lexend-mega {
  font-family: "Lexend Mega", sans-serif;
}

.font-kantumruy {
  font-family: "Kantumruy Pro", "Kantumruy", sans-serif;
}

.font-mervale-script {
  font-family: "Mervale Script", cursive;
}

html {
  scroll-behavior: smooth;
}
</style>
