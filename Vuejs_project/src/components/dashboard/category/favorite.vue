<template>
  <div class="bg-slate-100 min-h-screen font-sans">
    <div
      class="header-bar fixed top-0 left-0 w-full shadow-md bg-white h-20 z-50"
    >
      <div
        class="container mx-auto h-full flex flex-row items-center justify-between px-4 sm:px-6"
      >
        <!-- Left: Nav Links (Desktop) / Hamburger (Mobile) -->
        <div class="flex items-center">
          <button
            @click="isMobileMenuOpen = !isMobileMenuOpen"
            class="md:hidden text-gray-700 hover:text-pink-700 focus:outline-none mr-3"
            aria-label="Open menu"
          >
            <svg
              class="w-6 h-6"
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
          <ul
            class="hidden md:flex md:space-x-6 lg:space-x-8 font-medium text-sm"
          >
            <!-- The `relative group` classes on the <li> are essential for the dropdown to work -->
            <li class="relative group">
              <!-- Main Navigation Link -->
              <router-link
                to="/women"
                class="flex items-center gap-1 py-2 text-gray-900 hover:text-pink-700"
                active-class="text-pink-700 font-semibold"
              >
                <span>Women</span>
                <!-- Dropdown Arrow -->
                <svg
                  class="w-4 h-4 transition-transform duration-200 group-hover:rotate-180"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    fill-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                    clip-rule="evenodd"
                  ></path>
                </svg>
              </router-link>
              <!-- Dropdown Menu Panel -->
              <div
                class="absolute left-1/2 -translate-x-1/2 mt-1 w-48 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-20 invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-200"
              >
                <ul class="py-1">
                  <li>
                    <router-link
                      to="/women/new"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-pink-700"
                      >New Arrivals</router-link
                    >
                  </li>
                  <li>
                    <router-link
                      to="/women/dresses"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-pink-700"
                      >Dresses</router-link
                    >
                  </li>
                  <li>
                    <router-link
                      to="/women/tops"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-pink-700"
                      >Tops</router-link
                    >
                  </li>
                </ul>
              </div>
            </li>

            <li class="relative group">
              <router-link
                to="/men"
                class="flex items-center gap-1 py-2 text-gray-900 hover:text-pink-700"
                active-class="text-pink-700 font-khmer-moul"
              >
                <span>Men</span>
                <svg
                  class="w-4 h-4 transition-transform duration-200 group-hover:rotate-180"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    fill-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                    clip-rule="evenodd"
                  ></path>
                </svg>
              </router-link>
              <div
                class="absolute left-1/2 -translate-x-1/2 mt-1 w-48 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-20 invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-200"
              >
                <ul class="py-1">
                  <li>
                    <router-link
                      to="/men/shirts"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-pink-700"
                      >Shirts</router-link
                    >
                  </li>
                  <li>
                    <router-link
                      to="/men/pants"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-pink-700"
                      >Pants</router-link
                    >
                  </li>
                  <li>
                    <router-link
                      to="/men/jackets"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-pink-700"
                      >Jackets</router-link
                    >
                  </li>
                </ul>
              </div>
            </li>

            <li class="relative group">
              <router-link
                to="/boy"
                class="flex items-center gap-1 py-2 text-gray-900 hover:text-pink-700"
                active-class="text-pink-700 font-khmer-moul"
              >
                <span>Boy</span>
                <svg
                  class="w-4 h-4 transition-transform duration-200 group-hover:rotate-180"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    fill-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                    clip-rule="evenodd"
                  ></path>
                </svg>
              </router-link>
              <div
                class="absolute left-1/2 -translate-x-1/2 mt-1 w-48 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-20 invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-200"
              >
                <ul class="py-1">
                  <li>
                    <router-link
                      to="/boy/t-shirts"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-pink-700"
                      >T-Shirts</router-link
                    >
                  </li>
                  <li>
                    <router-link
                      to="/boy/shorts"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-pink-700"
                      >Shorts</router-link
                    >
                  </li>
                </ul>
              </div>
            </li>

            <li class="relative group">
              <router-link
                to="/girl"
                class="flex items-center gap-1 py-2 text-gray-900 hover:text-pink-700"
                active-class="text-pink-700 font-khmer-moul"
              >
                <span>Girl</span>
                <svg
                  class="w-4 h-4 transition-transform duration-200 group-hover:rotate-180"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    fill-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                    clip-rule="evenodd"
                  ></path>
                </svg>
              </router-link>
              <div
                class="absolute left-1/2 -translate-x-1/2 mt-1 w-48 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-20 invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-200"
              >
                <ul class="py-1">
                  <li>
                    <router-link
                      to="/girl/dresses"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-pink-700"
                      >Dresses</router-link
                    >
                  </li>
                  <li>
                    <router-link
                      to="/girl/skirts"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-pink-700"
                      >Skirts</router-link
                    >
                  </li>
                </ul>
              </div>
            </li>
          </ul>
        </div>
        <router-link
          to="/dashboard"
          class="font-mervale-script text-3xl text-pink-800 hover:text-pink-900 whitespace-nowrap"
          style="font-family: 'Mervale Script', cursive"
          >Clothes Shop</router-link
        >
        <div class="hidden md:flex items-center gap-3 lg:gap-4">
          <div class="relative">
            <input
              type="text"
              placeholder="Search..."
              class="search-input bg-gray-100 border border-gray-300 rounded-md py-1.5 px-3 pl-10 text-sm focus:ring-pink-500 focus:border-pink-500"
              style="width: 150px"
            />
            <img
              class="w-4 h-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"
              alt="Search Icon"
              src="/images/material-symbols-light-search.svg"
            />
          </div>
          <div class="bar-menu-parent flex items-center space-x-3 md:space-x-4">
            <button class="p-1.5 hover:bg-gray-100 rounded-full">
              <svg
                class="w-6 h-6 text-gray-700"
                viewBox="0 0 25 25"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <g clip-path="url(#clip0_1_1046)">
                  <path
                    d="M22.0067 19.0531C21.3638 18.48 20.801 17.823 20.3334 17.0998C19.8223 16.1017 19.5163 15.0114 19.4334 13.8931V10.5998C19.4378 8.84351 18.8007 7.14609 17.6419 5.82642C16.483 4.50675 14.8821 3.65568 13.14 3.43311V2.57311C13.14 2.33706 13.0463 2.11069 12.8794 1.94378C12.7124 1.77687 12.4861 1.68311 12.25 1.68311C12.014 1.68311 11.7876 1.77687 11.6207 1.94378C11.4538 2.11069 11.36 2.33706 11.36 2.57311V3.44644C9.63354 3.68506 8.05201 4.54128 6.90839 5.85651C5.76476 7.17174 5.13652 8.85687 5.14003 10.5998V13.8931C5.05707 15.0114 4.75106 16.1017 4.24003 17.0998C3.78062 17.8213 3.22683 18.4783 2.59337 19.0531C2.52225 19.1156 2.46526 19.1925 2.42618 19.2787C2.38709 19.3649 2.36682 19.4584 2.3667 19.5531V20.4598C2.3667 20.6366 2.43694 20.8062 2.56196 20.9312C2.68699 21.0562 2.85656 21.1264 3.03337 21.1264H21.5667C21.7435 21.1264 21.9131 21.0562 22.0381 20.9312C22.1631 20.8062 22.2334 20.6366 22.2334 20.4598V19.5531C22.2332 19.4584 22.213 19.3649 22.1739 19.2787C22.1348 19.1925 22.0778 19.1156 22.0067 19.0531ZM3.75337 19.7931C4.3735 19.1938 4.91961 18.5223 5.38003 17.7931C6.02389 16.5877 6.39915 15.2573 6.48003 13.8931V10.5998C6.45359 9.81846 6.58465 9.03982 6.8654 8.31022C7.14614 7.58061 7.57084 6.91496 8.1142 6.35291C8.65755 5.79085 9.30846 5.34388 10.0282 5.03862C10.7478 4.73336 11.5216 4.57604 12.3034 4.57604C13.0851 4.57604 13.8589 4.73336 14.5786 5.03862C15.2983 5.34388 15.9492 5.79085 16.4925 6.35291C17.0359 6.91496 17.4606 7.58061 17.7413 8.31022C18.0221 9.03982 18.1531 9.81846 18.1267 10.5998V13.8931C18.2076 15.2573 18.5828 16.5877 19.2267 17.7931C19.6871 18.5223 20.2332 19.1938 20.8534 19.7931H3.75337Z"
                    fill="currentColor"
                  />
                  <path
                    d="M12.3329 23.353C12.7529 23.3433 13.1559 23.1855 13.4707 22.9073C13.7854 22.6291 13.9917 22.2486 14.0529 21.833H10.5463C10.6092 22.2599 10.8251 22.6493 11.1538 22.9289C11.4825 23.2085 11.9015 23.3592 12.3329 23.353Z"
                    fill="currentColor"
                  />
                </g>
                <defs>
                  <clipPath id="clip0_1_1046">
                    <rect
                      width="24"
                      height="24"
                      fill="white"
                      transform="translate(0.333374 0.5)"
                    />
                  </clipPath>
                </defs>
              </svg>
            </button>

            <button class="p-1.5 hover:bg-gray-100 rounded-full">
              <svg
                class="w-6 h-6 text-gray-700"
                viewBox="0 0 25 25"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  d="M17.0209 4.25C15.0849 4.25 13.3899 5.0825 12.3334 6.48969C11.2768 5.0825 9.58181 4.25 7.64587 4.25C6.10484 4.25174 4.62741 4.86468 3.53773 5.95436C2.44806 7.04404 1.83511 8.52146 1.83337 10.0625C1.83337 16.625 11.5637 21.9369 11.9781 22.1562C12.0873 22.215 12.2094 22.2458 12.3334 22.2458C12.4574 22.2458 12.5795 22.215 12.6887 22.1562C13.1031 21.9369 22.8334 16.625 22.8334 10.0625C22.8316 8.52146 22.2187 7.04404 21.129 5.95436C20.0393 4.86468 18.5619 4.25174 17.0209 4.25ZM12.3334 20.6375C10.6215 19.64 3.33337 15.0959 3.33337 10.0625C3.33486 8.91921 3.78969 7.82317 4.59812 7.01475C5.40655 6.20632 6.50258 5.75149 7.64587 5.75C9.46931 5.75 11.0002 6.72125 11.6396 8.28125C11.6961 8.41881 11.7923 8.53646 11.9158 8.61926C12.0393 8.70207 12.1847 8.74627 12.3334 8.74627C12.4821 8.74627 12.6274 8.70207 12.751 8.61926C12.8745 8.53646 12.9706 8.41881 13.0271 8.28125C13.6665 6.71844 15.1974 5.75 17.0209 5.75C18.1642 5.75149 19.2602 6.20632 20.0686 7.01475C20.8771 7.82317 21.3319 8.91921 21.3334 10.0625C21.3334 15.0884 14.0434 19.6391 12.3334 20.6375Z"
                  fill="currentColor"
                />
              </svg>
            </button>
            <router-link to="/cart">
              <button class="p-1.5 hover:bg-gray-100 rounded-full">
                <svg
                  class="w-6 h-6 text-gray-700"
                  viewBox="0 0 25 25"
                  fill="none"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    d="M19.3334 7.5H16.3334V6.5C16.3334 5.43913 15.9119 4.42172 15.1618 3.67157C14.4117 2.92143 13.3942 2.5 12.3334 2.5C11.2725 2.5 10.2551 2.92143 9.50495 3.67157C8.7548 4.42172 8.33337 5.43913 8.33337 6.5V7.5H5.33337C5.06816 7.5 4.8138 7.60536 4.62627 7.79289C4.43873 7.98043 4.33337 8.23478 4.33337 8.5V19.5C4.33337 20.2956 4.64944 21.0587 5.21205 21.6213C5.77466 22.1839 6.53772 22.5 7.33337 22.5H17.3334C18.129 22.5 18.8921 22.1839 19.4547 21.6213C20.0173 21.0587 20.3334 20.2956 20.3334 19.5V8.5C20.3334 8.23478 20.228 7.98043 20.0405 7.79289C19.8529 7.60536 19.5986 7.5 19.3334 7.5ZM10.3334 6.5C10.3334 5.96957 10.5441 5.46086 10.9192 5.08579C11.2942 4.71071 11.8029 4.5 12.3334 4.5C12.8638 4.5 13.3725 4.71071 13.7476 5.08579C14.1227 5.46086 14.3334 5.96957 14.3334 6.5V7.5H10.3334V6.5ZM18.3334 19.5C18.3334 19.7652 18.228 20.0196 18.0405 20.2071C17.8529 20.3946 17.5986 20.5 17.3334 20.5H7.33337C7.06816 20.5 6.8138 20.3946 6.62627 20.2071C6.43873 20.0196 6.33337 19.7652 6.33337 19.5V9.5H8.33337V10.5C8.33337 10.7652 8.43873 11.0196 8.62627 11.2071C8.8138 11.3946 9.06816 11.5 9.33337 11.5C9.59859 11.5 9.85294 11.3946 10.0405 11.2071C10.228 11.0196 10.3334 10.7652 10.3334 10.5V9.5H14.3334V10.5C14.3334 10.7652 14.4387 11.0196 14.6263 11.2071C14.8138 11.3946 15.0682 11.5 15.3334 11.5C15.5986 11.5 15.8529 11.3946 16.0405 11.2071C16.228 11.0196 16.3334 10.7652 16.3334 10.5V9.5H18.3334V19.5Z"
                    fill="currentColor"
                  />
                </svg>
              </button>
            </router-link>

            <router-link
              to="/register"
              class="bar-menu1 px-3 py-2 rounded-md hover:bg-gray-100 text-sm"
            >
              Register
            </router-link>
            <a
              href="#"
              class="bar-menu1 px-3 py-2 rounded-md hover:bg-gray-100 text-sm"
              >Login</a
            >
          </div>
        </div>
        <div class="md:hidden w-6"></div>
        <!-- Placeholder for mobile layout balance -->
      </div>
      <!-- Mobile Menu Dropdown -->

      <div
        v-if="isMobileMenuOpen"
        class="md:hidden fixed top-20 left-0 w-full bg-white shadow-lg border-t border-gray-200 z-40"
      >
        <ul class="flex flex-col p-4 space-y-2">
          <li>
            <router-link
              to="/category/women"
              @click="isMobileMenuOpen = false"
              class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 hover:text-pink-700"
              active-class="bg-pink-50 text-pink-700 font-semibold"
              >WOMEN</router-link
            >
          </li>
          <li>
            <router-link
              to="/category/men"
              @click="isMobileMenuOpen = false"
              class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 hover:text-pink-700"
              active-class="bg-pink-50 text-pink-700 font-semibold"
              >MEN</router-link
            >
          </li>
          <li>
            <router-link
              to="/category/boys"
              @click="isMobileMenuOpen = false"
              class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 hover:text-pink-700"
              active-class="bg-pink-50 text-pink-700 font-semibold"
              >BOY</router-link
            >
          </li>
          <li>
            <router-link
              to="/category/girls"
              @click="isMobileMenuOpen = false"
              class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 hover:text-pink-700"
              active-class="bg-pink-50 text-pink-700 font-semibold"
              >GIRL</router-link
            >
          </li>
          <li>
            <router-link
              to="/product/list"
              @click="isMobileMenuOpen = false"
              class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 hover:text-pink-700"
              active-class="bg-pink-50 text-pink-700 font-semibold"
              >list</router-link
            >
          </li>

          <li class="pt-2 border-t border-gray-200">
            <input
              type="text"
              placeholder="Search..."
              class="w-full search-input bg-gray-100 border border-gray-300 rounded-md py-2 px-3 text-sm focus:ring-pink-500 focus:border-pink-500"
            />
          </li>
        </ul>
      </div>
    </div>
    <div class="pt-20">
      <div class="container mx-auto p-4 sm:p-8">
        <!-- Header Section -->
        <header
          class="flex flex-col sm:flex-row justify-between items-center mb-8"
        >
          <h1 class="text-3xl font-bold text-slate-800 mb-4 sm:mb-0">
            My Favorites
          </h1>
          <div class="flex items-center gap-2 text-md">
            <label for="sort" class="text-slate-600">Sort by:</label>
            <select
              v-model="sortBy"
              id="sort"
              class="border-gray-400 text-gray-500 bg-gray-200 w-[150px] h-[40px] rounded-md shadow-md focus:ring-sky-500 text-center focus:border-sky-500"
            >
              <option value="date_desc">Newest First</option>
              <option value="price_asc">Price: Low to High</option>
              <option value="price_desc">Price: High to Low</option>
            </select>
          </div>
        </header>

        <!-- Favorites Grid -->
        <div
          v-if="sortedFavorites.length > 0"
          class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6"
        >
          <div
            v-for="item in sortedFavorites"
            :key="item.id"
            class="bg-white rounded-lg shadow-md overflow-hidden group flex flex-col transition-all duration-300 hover:shadow-xl hover:-translate-y-1"
          >
            <!-- Image Section with Remove Button -->
            <div class="relative">
              <img
                :src="item.imageUrl"
                :alt="item.name"
                class="w-full h-[500px] object-cover transition-transform duration-300 group-hover:scale-105"
              />
              <button
                @click="removeFromFavorites(item.id)"
                title="Remove from favorites"
                class="absolute top-3 right-3 bg-white/80 backdrop-blur-sm rounded-full p-1.5 text-slate-500 hover:bg-white hover:text-red-500 transition-all duration-300"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-5 w-5"
                  viewBox="0 0 20 20"
                  fill="currentColor"
                >
                  <path
                    fill-rule="evenodd"
                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                    clip-rule="evenodd"
                  />
                </svg>
              </button>
            </div>

            <!-- Content Section -->
            <div class="p-4 flex flex-col flex-grow">
              <h3
                class="text-base font-semibold text-slate-800 mb-2 h-12 line-clamp-2"
              >
                {{ item.name }}
              </h3>
              <div class="flex items-baseline gap-2 mb-4">
                <span class="text-xl font-bold text-slate-900"
                  >${{ item.price.toFixed(2) }}</span
                >
                <span
                  v-if="item.originalPrice"
                  class="text-sm text-slate-400 line-through"
                  >${{ item.originalPrice.toFixed(2) }}</span
                >
              </div>

              <!-- Add to Cart Button (pushed to the bottom) -->
              <button
                @click="addToCart(item)"
                class="mt-auto w-full bg-gray-200 text-black font-semibold py-2 px-4 rounded-lg hover:bg-gray-400 transition-colors"
              >
                Add to Cart
              </button>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-else class="text-center py-20 bg-white rounded-lg shadow-md">
          <div
            class="mx-auto bg-slate-200 rounded-full h-16 w-16 flex items-center justify-center"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-8 w-8 text-slate-500"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
              stroke-width="2"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
              />
            </svg>
          </div>
          <h2 class="mt-4 text-xl font-semibold text-slate-800">
            Your Favorites List is Empty
          </h2>
          <p class="mt-2 text-slate-500">
            Looks like you haven't added anything yet. Click the heart icon on
            products to save them here.
          </p>
          <button
            class="mt-6 bg-slate-800 text-white font-semibold py-2 px-5 rounded-lg hover:bg-slate-900 transition-colors"
          >
            Continue Shopping
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { ref, computed } from 'vue';

// --- STATE MANAGEMENT ---

// A ref to hold the list of favorited items.
// In a real app, you would fetch this from an API on component mount.
const favorites = ref([
  {
    id: 1,
    name: 'Minimalist Ceramic Vase',
    imageUrl:
      'https://assets.vogue.com/photos/64c2bbe1d9567128b71301f8/master/w_2560%2Cc_limit/NAP_HSCampaign_FinalRetouch_CrisFragkou_7.jpg',
    price: 45.0,
    originalPrice: 60.0,
    dateAdded: '2023-10-26',
  },
  {
    id: 2,
    name: 'Modern Wooden Desk Chair',
    imageUrl:
      'https://www.next.co.il/cms/resource/blob/1043186/7a10019e91cab63aadbbcee8a3487676/dresses-data.jpg',
    price: 180.0,
    dateAdded: '2023-10-25',
  },
  {
    id: 3,
    name: 'Scented Soy Wax Candle',
    imageUrl:
      'https://images.unsplash.com/photo-1614036134371-c2b0b5b314b3?q=80&w=400',
    price: 25.0,
    originalPrice: 30.0,
    dateAdded: '2023-10-24',
  },
  {
    id: 4,
    name: 'Abstract Wall Art Print',
    imageUrl:
      'https://images.unsplash.com/photo-1579783902614-a3fb3927b6a5?q=80&w=400',
    price: 75.5,
    dateAdded: '2023-10-22',
  },
  {
    id: 5,
    name: 'Linen Throw Pillow Set',
    imageUrl:
      'https://images.unsplash.com/photo-1618221319912-eff75d481a5f?q=80&w=400',
    price: 65.0,
    dateAdded: '2023-10-28',
  },
]);

// Ref for the selected sort option
const sortBy = ref('date_desc');

// A computed property to reactively sort the favorites list
const sortedFavorites = computed(() => {
  const sorted = [...favorites.value];
  switch (sortBy.value) {
    case 'price_asc':
      return sorted.sort((a, b) => a.price - b.price);
    case 'price_desc':
      return sorted.sort((a, b) => b.price - a.price);
    case 'date_desc':
    default:
      return sorted.sort(
        (a, b) => new Date(b.dateAdded) - new Date(a.dateAdded)
      );
  }
});

// --- METHODS ---

function removeFromFavorites(itemId) {
  // In a real app, you'd also make an API call here to update the backend.
  favorites.value = favorites.value.filter((item) => item.id !== itemId);
  console.log(`Removed item ${itemId} from favorites.`);
}

function addToCart(item) {
  // Logic to add the item to the shopping cart.
  alert(`Added "${item.name}" to your cart!`);
  console.log('Adding to cart:', item);
}
</script>
