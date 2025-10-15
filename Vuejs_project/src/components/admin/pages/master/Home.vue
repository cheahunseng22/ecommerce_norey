<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    <header class="bg-white/80 backdrop-blur-md shadow-lg border-b border-white/20 sticky top-0 z-50">
      <div class="container mx-auto px-6 py-4">
        <div class="flex justify-between items-center">
          <div class="flex items-center space-x-4">
            <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
              </svg>
            </div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
              Analytics Dashboard
            </h1>
          </div>
          <div class="flex items-center space-x-4">
            <div class="relative">
              <input
                type="text"
                placeholder="Search..."
                class="pl-10 pr-4 py-2 bg-white/50 backdrop-blur border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 w-64"
              />
              <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
              </svg>
            </div>
            <button class="relative p-2 bg-white/50 backdrop-blur rounded-xl hover:bg-white/80 transition-all duration-300">
              <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5v-12"></path>
              </svg>
            </button>
          </div>
        </div>
      </div>
    </header>

    <div class="container mx-auto px-6 py-8">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="group relative bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6 hover:shadow-2xl hover:scale-105 transition-all duration-500 overflow-hidden">
          <div class="absolute inset-0 bg-gradient-to-br from-purple-500/10 to-indigo-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
          <div class="relative z-10">
            <div class="flex justify-between items-start mb-4">
              <div>
                <p class="text-slate-500 text-sm font-medium">Total Products</p>
                <h3 class="text-3xl font-bold text-slate-800 mt-2">1,540</h3>
              </div>
              <div class="p-3 bg-gradient-to-r from-purple-500 to-indigo-500 rounded-xl shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
              </div>
            </div>
            <div class="flex items-center">
              <span class="flex items-center text-sm font-semibold text-purple-600">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
                8.1%
              </span>
              <span class="text-slate-500 text-sm ml-2">vs last month</span>
            </div>
          </div>
        </div>

        <div class="group relative bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6 hover:shadow-2xl hover:scale-105 transition-all duration-500 overflow-hidden">
          <div class="absolute inset-0 bg-gradient-to-br from-green-500/10 to-teal-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
          <div class="relative z-10">
            <div class="flex justify-between items-start mb-4">
              <div>
                <p class="text-slate-500 text-sm font-medium">Total Users</p>
                <h3 class="text-3xl font-bold text-slate-800 mt-2">12,100</h3>
              </div>
              <div class="p-3 bg-gradient-to-r from-green-500 to-teal-500 rounded-xl shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
              </div>
            </div>
            <div class="flex items-center">
              <span class="flex items-center text-sm font-semibold text-green-600">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
                12.5%
              </span>
              <span class="text-slate-500 text-sm ml-2">vs last month</span>
            </div>
          </div>
        </div>
        
        <div class="group relative bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6 hover:shadow-2xl hover:scale-105 transition-all duration-500 overflow-hidden">
          <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/10 to-blue-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
          <div class="relative z-10">
            <div class="flex justify-between items-start mb-4">
              <div>
                <p class="text-slate-500 text-sm font-medium">Total Categories</p>
                <h3 class="text-3xl font-bold text-slate-800 mt-2">15</h3>
              </div>
              <div class="p-3 bg-gradient-to-r from-cyan-500 to-blue-500 rounded-xl shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v2a2 2 0 01-2 2H5a2 2 0 01-2-2v-2a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
              </div>
            </div>
            <div class="flex items-center">
              <span class="flex items-center text-sm font-semibold text-cyan-600">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6-6"></path>
                </svg>
                4.2%
              </span>
              <span class="text-slate-500 text-sm ml-2">vs last month</span>
            </div>
          </div>
        </div>

        <div class="group relative bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6 hover:shadow-2xl hover:scale-105 transition-all duration-500 overflow-hidden">
          <div class="absolute inset-0 bg-gradient-to-br from-pink-500/10 to-red-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
          <div class="relative z-10">
            <div class="flex justify-between items-start mb-4">
              <div>
                <p class="text-slate-500 text-sm font-medium">Total Inventory</p>
                <h3 class="text-3xl font-bold text-slate-800 mt-2">78,920</h3>
              </div>
              <div class="p-3 bg-gradient-to-r from-pink-500 to-red-500 rounded-xl shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v2a2 2 0 01-2 2H5a2 2 0 01-2-2v-2a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
              </div>
            </div>
            <div class="flex items-center">
              <span class="flex items-center text-sm font-semibold text-pink-600">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6-6"></path>
                </svg>
                1.7%
              </span>
              <span class="text-slate-500 text-sm ml-2">vs last month</span>
            </div>
          </div>
        </div>
      </div>
      
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="group relative bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6 hover:shadow-2xl hover:scale-105 transition-all duration-500 overflow-hidden">
          <div class="absolute inset-0 bg-gradient-to-br from-red-500/10 to-pink-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
          <div class="relative z-10">
            <div class="flex justify-between items-start mb-4">
              <div>
                <p class="text-slate-500 text-sm font-medium">Total Sales</p>
                <h3 class="text-3xl font-bold text-slate-800 mt-2 animate-pulse">82</h3>
              </div>
              <div class="p-3 bg-gradient-to-r from-red-500 to-pink-500 rounded-xl shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
              </div>
            </div>
            <div class="flex items-center">
              <span class="flex items-center text-sm font-semibold text-red-600">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                </svg>
                3.65%
              </span>
              <span class="text-slate-500 text-sm ml-2">vs last week</span>
            </div>
          </div>
        </div>

        <div class="group relative bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6 hover:shadow-2xl hover:scale-105 transition-all duration-500 overflow-hidden">
          <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/10 to-green-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
          <div class="relative z-10">
            <div class="flex justify-between items-start mb-4">
              <div>
                <p class="text-slate-500 text-sm font-medium">Total Earnings</p>
                <h3 class="text-3xl font-bold text-slate-800 mt-2">$21,300</h3>
              </div>
              <div class="p-3 bg-gradient-to-r from-emerald-500 to-green-500 rounded-xl shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                </svg>
              </div>
            </div>
            <div class="flex items-center">
              <span class="flex items-center text-sm font-semibold text-emerald-600">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
                6.65%
              </span>
              <span class="text-slate-500 text-sm ml-2">vs last week</span>
            </div>
          </div>
        </div>

        <div class="group relative bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6 hover:shadow-2xl hover:scale-105 transition-all duration-500 overflow-hidden">
          <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-indigo-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
          <div class="relative z-10">
            <div class="flex justify-between items-start mb-4">
              <div>
                <p class="text-slate-500 text-sm font-medium">Total Visitors</p>
                <h3 class="text-3xl font-bold text-slate-800 mt-2">14,212</h3>
              </div>
              <div class="p-3 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-xl shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
              </div>
            </div>
            <div class="flex items-center">
              <span class="flex items-center text-sm font-semibold text-blue-600">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
                5.25%
              </span>
              <span class="text-slate-500 text-sm ml-2">vs last week</span>
            </div>
          </div>
        </div>

        <div class="group relative bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6 hover:shadow-2xl hover:scale-105 transition-all duration-500 overflow-hidden">
          <div class="absolute inset-0 bg-gradient-to-br from-amber-500/10 to-orange-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
          <div class="relative z-10">
            <div class="flex justify-between items-start mb-4">
              <div>
                <p class="text-slate-500 text-sm font-medium">Total Orders</p>
                <h3 class="text-3xl font-bold text-slate-800 mt-2">1,847</h3>
              </div>
              <div class="p-3 bg-gradient-to-r from-amber-500 to-orange-500 rounded-xl shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
              </div>
            </div>
            <div class="flex items-center">
              <span class="flex items-center text-sm font-semibold text-orange-600">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6-6"></path>
                </svg>
                2.25%
              </span>
              <span class="text-slate-500 text-sm ml-2">vs last week</span>
            </div>
          </div>
        </div>
      </div>
      
      <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 mb-8">
        <div class="xl:col-span-2 bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6 hover:shadow-2xl transition-all duration-500">
          <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-slate-800">Revenue Analytics</h2>
            <div class="flex space-x-2">
              <button class="px-4 py-2 bg-blue-100 text-blue-600 rounded-lg text-sm font-medium hover:bg-blue-200 transition-colors">
                Monthly
              </button>
              <button class="px-4 py-2 text-slate-600 rounded-lg text-sm font-medium hover:bg-slate-100 transition-colors">
                Yearly
              </button>
            </div>
          </div>
          <div class="h-80">
            <canvas ref="lineChart"></canvas>
          </div>
        </div>

        <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6 hover:shadow-2xl transition-all duration-500">
          <h2 class="text-xl font-bold text-slate-800 mb-6">Browser Usage</h2>
          <div class="space-y-6">
            <div class="h-48">
              <canvas ref="pieChart" class="w-full"></canvas>
            </div>
            <div class="space-y-3">
              <div class="flex items-center justify-between p-3 bg-blue-50 rounded-xl">
                <div class="flex items-center">
                  <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                  <span class="text-sm font-medium text-slate-700">Chrome</span>
                </div>
                <span class="font-bold text-slate-800">4,306</span>
              </div>
              <div class="flex items-center justify-between p-3 bg-amber-50 rounded-xl">
                <div class="flex items-center">
                  <div class="w-3 h-3 bg-amber-500 rounded-full mr-3"></div>
                  <span class="text-sm font-medium text-slate-700">Firefox</span>
                </div>
                <span class="font-bold text-slate-800">3,801</span>
              </div>
              <div class="flex items-center justify-between p-3 bg-red-50 rounded-xl">
                <div class="flex items-center">
                  <div class="w-3 h-3 bg-red-500 rounded-full mr-3"></div>
                  <span class="text-sm font-medium text-slate-700">Safari</span>
                </div>
                <span class="font-bold text-slate-800">1,689</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6 hover:shadow-2xl transition-all duration-500">
          <h2 class="text-xl font-bold text-slate-800 mb-6">Monthly Sales</h2>
          <div class="h-64">
            <canvas ref="barChart"></canvas>
          </div>
        </div>

        <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6 hover:shadow-2xl transition-all duration-500">
          <h2 class="text-xl font-bold text-slate-800 mb-6">Calendar</h2>
          <div ref="calendar"></div>
        </div>
      </div>

      <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6 hover:shadow-2xl transition-all duration-500">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-xl font-bold text-slate-800">Latest Projects</h2>
          <button
            class="px-6 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl font-medium hover:from-blue-700 hover:to-indigo-700 transform hover:scale-105 transition-all duration-300 shadow-lg"
            @click="fetchProjects"
          >
            {{ loading ? 'Loading...' : 'Refresh Data' }}
          </button>
        </div>
        
        <div class="overflow-x-auto">
          <table class="min-w-full">
            <thead>
              <tr class="border-b border-slate-200">
                <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">Project Name</th>
                <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700 hidden xl:table-cell">Start Date</th>
                <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700 hidden xl:table-cell">End Date</th>
                <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">Status</th>
                <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700 hidden md:table-cell">Assignee</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              <tr v-if="loading">
                <td colspan="5" class="px-6 py-8 text-center">
                  <div class="flex items-center justify-center">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    <span class="ml-3 text-slate-600">Loading projects...</span>
                  </div>
                </td>
              </tr>
              
              <tr v-else-if="error" class="hover:bg-slate-50 transition-colors">
                <td colspan="5" class="px-6 py-4 text-center text-red-600">
                  {{ error }}
                  <button
                    class="ml-2 text-blue-600 underline hover:text-blue-800"
                    @click="fetchProjects"
                  >
                    Retry
                  </button>
                </td>
              </tr>
              
              <tr v-else-if="projects.length === 0" class="hover:bg-slate-50 transition-colors">
                <td colspan="5" class="px-6 py-4 text-center text-slate-500">
                  No projects found
                </td>
              </tr>
              
              <tr v-for="project in projects" :key="project.id" class="hover:bg-slate-50 transition-colors">
                <td class="px-6 py-4">
                  <div class="font-medium text-slate-800">{{ project.name }}</div>
                </td>
                <td class="px-6 py-4 text-slate-600 hidden xl:table-cell">
                  {{ formatDate(project.start_date) }}
                </td>
                <td class="px-6 py-4 text-slate-600 hidden xl:table-cell">
                  {{ formatDate(project.end_date) }}
                </td>
                <td class="px-6 py-4">
                  <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full" :class="statusClass(project.status)">
                    {{ project.status }}
                  </span>
                </td>
                <td class="px-6 py-4 text-slate-600 hidden md:table-cell">
                  <div class="flex items-center">
                    <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full flex items-center justify-center text-white text-xs font-medium mr-3">
                      {{ project.assignee?.name ? project.assignee.name.charAt(0) : 'U' }}
                    </div>
                    {{ project.assignee?.name || 'Unassigned' }}
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
          
          <div v-if="pagination && projects.length > 0" class="flex items-center justify-between mt-6 p-4 bg-slate-50 rounded-xl">
            <button
              :disabled="!pagination.prev_page_url"
              @click="fetchProjects(pagination.current_page - 1)"
              class="px-4 py-2 text-sm rounded-lg transition-all duration-300"
              :class="{
                'bg-slate-300 text-slate-500 cursor-not-allowed': !pagination.prev_page_url,
                'bg-blue-600 text-white hover:bg-blue-700 transform hover:scale-105 shadow-md': pagination.prev_page_url
              }"
            >
              Previous
            </button>
            <span class="text-sm font-medium text-slate-700">
              Page {{ pagination.current_page }} of {{ pagination.last_page }}
            </span>
            <button
              :disabled="!pagination.next_page_url"
              @click="fetchProjects(pagination.current_page + 1)"
              class="px-4 py-2 text-sm rounded-lg transition-all duration-300"
              :class="{
                'bg-slate-300 text-slate-500 cursor-not-allowed': !pagination.next_page_url,
                'bg-blue-600 text-white hover:bg-blue-700 transform hover:scale-105 shadow-md': pagination.next_page_url
              }"
            >
              Next
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Chart from 'chart.js/auto';
import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.min.css';

export default {
  data() {
    return {
      projects: [],
      loading: false,
      error: null,
      pagination: null,
      lineChart: null,
      pieChart: null,
      barChart: null,
      calendar: null
    }
  },
  mounted() {
    this.fetchProjects();
    this.initCharts();
    this.initCalendar();
  },
  beforeDestroy() {
    this.destroyCharts();
    if (this.calendar) {
      this.calendar.destroy();
    }
  },
  methods: {
    formatDate(dateString) {
      if (!dateString) return '';
      const date = new Date(dateString);
      return date.toLocaleDateString('en-GB', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
      });
    },
    fetchProjects(page = 1) {
      this.loading = true;
      this.error = null;
      
      setTimeout(() => {
        try {
          const fetchedProjects = [
            { id: 1, name: 'Project Apollo', start_date: '2023-01-01', end_date: '2023-06-31', status: 'Done', assignee: { name: 'Vanessa Tucker' } },
            { id: 2, name: 'Project Fireball', start_date: '2023-01-01', end_date: '2023-06-31', status: 'Cancelled', assignee: { name: 'William Harris' } },
            { id: 3, name: 'Project Hades', start_date: '2023-01-01', end_date: '2023-06-31', status: 'Done', assignee: { name: 'Sharon Lessman' } },
            { id: 4, name: 'Project Nitro', start_date: '2023-01-01', end_date: '2023-06-31', status: 'In progress', assignee: { name: 'Vanessa Tucker' } },
            { id: 5, name: 'Project Phoenix', start_date: '2023-01-01', end_date: '2023-06-31', status: 'Done', assignee: { name: 'William Harris' } },
            { id: 6, name: 'Project Titan', start_date: '2023-01-01', end_date: '2023-06-31', status: 'Done', assignee: { name: 'Vanessa Tucker' } },
            { id: 7, name: 'Project Comet', start_date: '2023-01-01', end_date: '2023-06-31', status: 'In progress', assignee: { name: 'Sharon Lessman' } },
            { id: 8, name: 'Project Genesis', start_date: '2023-01-01', end_date: '2023-06-31', status: 'Cancelled', assignee: { name: 'William Harris' } },
          ].slice((page - 1) * 5, page * 5); // Simple client-side pagination simulation

          this.projects = fetchedProjects;
          this.pagination = {
            current_page: page,
            last_page: 5,
            next_page_url: page < 5 ? `http://example.com/api/projects?page=${page + 1}` : null,
            prev_page_url: page > 1 ? `http://example.com/api/projects?page=${page - 1}` : null
          };
          this.loading = false;
        } catch (err) {
          this.error = 'Failed to fetch projects. Please try again.';
          this.loading = false;
        }
      }, 1000);
    },
    statusClass(status) {
      return {
        'bg-emerald-100 text-emerald-800 border border-emerald-200': status === 'Done',
        'bg-red-100 text-red-800 border border-red-200': status === 'Cancelled',
        'bg-amber-100 text-amber-800 border border-amber-200': status === 'In progress'
      }
    },
    initCharts() {
      // Line Chart
      const lineCtx = this.$refs.lineChart.getContext('2d');
      const lineGradient = lineCtx.createLinearGradient(0, 0, 0, 300);
      lineGradient.addColorStop(0, "rgba(59, 130, 246, 0.2)");
      lineGradient.addColorStop(1, "rgba(59, 130, 246, 0)");
      
      this.lineChart = new Chart(lineCtx, {
        type: 'line',
        data: {
          labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
          datasets: [{
            label: 'Revenue',
            data: [65000, 59000, 80000, 81000, 56000, 55000, 70000, 85000, 72000, 85000, 90000, 95000],
            backgroundColor: lineGradient,
            borderColor: '#3B82F6',
            borderWidth: 3,
            tension: 0.4,
            fill: true,
            pointBackgroundColor: '#3B82F6',
            pointBorderColor: '#ffffff',
            pointBorderWidth: 2,
            pointRadius: 5,
            pointHoverRadius: 8
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: false }
          },
          scales: {
            x: { 
              grid: { display: false },
              ticks: { color: '#64748b' }
            },
            y: {
              grid: { color: '#e2e8f0' },
              ticks: {
                color: '#64748b',
                callback: function(value) {
                  return '$' + (value / 1000) + 'k';
                }
              }
            }
          }
        }
      });

      // Pie Chart
      const pieCtx = this.$refs.pieChart.getContext('2d');
      this.pieChart = new Chart(pieCtx, {
        type: 'doughnut',
        data: {
          labels: ['Chrome', 'Firefox', 'Safari'],
          datasets: [{
            data: [4306, 3801, 1689],
            backgroundColor: ['#3B82F6', '#F59E0B', '#EF4444'],
            borderWidth: 0,
            cutout: '70%'
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: false }
          }
        }
      });

      // Bar Chart
      const barCtx = this.$refs.barChart.getContext('2d');
      this.barChart = new Chart(barCtx, {
        type: 'bar',
        data: {
          labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
          datasets: [{
            label: 'Sales',
            data: [540, 670, 410, 550, 620, 450, 550, 730, 600, 760, 480, 790],
            backgroundColor: '#3B82F6',
            borderRadius: 8,
            borderSkipped: false,
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: false }
          },
          scales: {
            x: { 
              grid: { display: false },
              ticks: { color: '#64748b' }
            },
            y: {
              grid: { color: '#e2e8f0' },
              ticks: { color: '#64748b' }
            }
          }
        }
      });
    },
    destroyCharts() {
      if (this.lineChart) this.lineChart.destroy();
      if (this.pieChart) this.pieChart.destroy();
      if (this.barChart) this.barChart.destroy();
    },
    initCalendar() {
      if (this.calendar) {
        this.calendar.destroy();
      }
      
      this.calendar = flatpickr(this.$refs.calendar, {
        inline: true,
        altInput: true,
        altFormat: "F j, Y",
        dateFormat: "Y-m-d",
      });
    }
  }
}
</script>