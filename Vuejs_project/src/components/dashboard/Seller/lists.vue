
  <template>
    <div class="sellers-page">
      <div class="bg-white p-6 rounded-lg shadow-md">
        <header class="pb-6 border-b border-gray-200">
          <h1 class="text-2xl font-bold text-gray-800">Seller</h1>
          <div class="mt-4 flex flex-col md:flex-row items-start md:items-center justify-between">
            
            <!-- Search Bar -->
            <div class="relative w-full md:w-1/3">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
              </div>
              <input 
                v-model="searchQuery"
                type="text" 
                placeholder="Search Seller..." 
                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
              />
            </div>

            <!-- Add Category Button -->
      <button class="add-btn" @click="isModalOpen = true">
              <!-- <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15" fill="none" stroke="white" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg> -->
              <span>Add New Seller</span>
            </button>

          </div>
        </header>

        <div class="table-wrapper">
          <table class="sellers-table">
            <thead>
              <tr>
                <th>Seller Name</th>
                <th>Shop Name</th>
                <th>Current Package</th>
                <th>Current Balance</th>
                <th>Shop Published</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="seller in sellers" :key="seller.id">
                <td>
                  <div class="seller-info">
                    <img :src="seller.avatar" alt="Seller Avatar" class="avatar" />
                    <div>
                      <div class="seller-name">{{ seller.name }}</div>
                      <div class="seller-phone">{{ seller.phone }}</div>
                    </div>
                  </div>
                </td>
                <td>{{ seller.shopName }}</td>
                <td>{{ seller.package }}</td>
                <td>${{ seller.balance.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}</td>
                <td>
                  <label class="toggle-switch">
                    <input type="checkbox" :checked="seller.published" />
                    <span class="slider"></span>
                  </label>
                </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                 <div class="inline-flex items-center space-x-4">
    <!-- View Button -->
    <button
      @click="handleView(product.id)"
      title="View"
      class="text-blue-600 hover:text-blue-800 transition duration-150 ease-in-out transform hover:scale-105"
    >
      <i class="fas fa-eye mr-1"></i> View
    </button>

    <!-- Edit Button -->
    <button
      @click="handleEdit(product.id)"
      title="Edit"
      class="text-yellow-600 hover:text-yellow-800 transition duration-150 ease-in-out transform hover:scale-105"
    >
      <i class="fas fa-edit mr-1"></i> Edit
    </button>

    <!-- Delete Button -->
    <button
      @click="handleDelete(product.id)"
      title="Delete"
      class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-150 ease-in-out"
    >
      <i class="fas fa-trash mr-1"></i> Delete
    </button>
  </div>
              </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="flex items-center justify-center pt-6">
          <nav class="flex items-center space-x-2">
            <button @click="changePage(currentPage - 1)" :disabled="currentPage === 1" class="p-2 rounded-md disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-100">
              <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
            </button>
            <span class="px-4 py-2 text-sm font-medium bg-blue-500 text-white rounded-md">{{ currentPage }}</span>
            <button @click="changePage(currentPage + 1)" :disabled="currentPage >= totalPages" class="p-2 rounded-md disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-100">
              <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
            </button>
          </nav>
        </div>
      </div>

      <!-- Add New Seller Modal -->
      <div v-if="isModalOpen" class="modal-overlay" @click.self="closeModal">
        <div class="modal-content">
          <h2 class="modal-title">Create New Seller</h2>
                      <form @submit.prevent="addSeller" enctype="multipart/form-data">
  <div class="form-group">
    <label for="image">Seller Image</label>
    <input 
      type="file" 
      id="image" 
      accept="image/*"
      @change="handleImageUpload"
    >
    <div v-if="imagePreview" class="image-preview">
      <img :src="imagePreview" alt="Preview">
    </div>
  </div>
  <div class="form-group">
    <label for="name">Seller Name</label>
    <input type="text" id="name" v-model="newSeller.name" required>
  </div>
  <div class="form-group">
    <label for="phone">Phone Number</label>
    <input type="tel" id="phone" v-model="newSeller.phone" placeholder="+855" required>
  </div>
  <div class="form-group">
    <label for="shopName">Shop Name</label>
    <input type="text" id="shopName" v-model="newSeller.shopName" required>
  </div>
  <div class="form-group">
    <label for="balance">Balance ($)</label>
    <input 
      type="number" 
      id="balance" 
      v-model.number="newSeller.balance" 
      min="0"
      step="0.10"
      placeholder="0.00"
      required
      @input="formatBalance"
    >
  </div>
  <div class="form-group">
    <label for="package">Package</label>
    <select id="package" v-model="newSeller.package">
      <option>Premium</option>
      <option>Gold</option>
      <option>Silver</option>
    </select>
  </div>
  <div class="modal-actions">
    <button type="button" class="btn-cancel" @click="closeModal">Cancel</button>
    <button type="submit" class="btn-submit">Add Seller</button>
  </div>
</form>
        </div>
      </div>

    </div>
  </template>

  <script setup>
  import { ref, reactive } from 'vue';

  // --- STATE ---

  // This ref controls the visibility of the "Add New Seller" modal
  const isModalOpen = ref(false);

  // This holds the list of sellers, prepopulated with data from the image
  const sellers = ref([
      { id: 1, name: 'Dominic Moss', phone: '+12345678910', shopName: 'Tech Tube', package: 'Premium', balance: 11345.25, published: true, avatar: 'https://i.pravatar.cc/150?img=1' },
      { id: 2, name: 'Ethan Booth', phone: '+12345678910', shopName: 'The Beauty', package: 'Premium', balance: 10350.25, published: true, avatar: 'https://i.pravatar.cc/150?img=2' },
      { id: 3, name: 'Finley Henry', phone: '+12345678910', shopName: 'Beauty Shop', package: 'Gold', balance: 7250.36, published: true, avatar: 'https://i.pravatar.cc/150?img=3' },
      { id: 4, name: 'Gabriel McKenzie', phone: '+12345678910', shopName: 'The Fashion', package: 'Gold', balance: 4370.55, published: true, avatar: 'https://i.pravatar.cc/150?img=4' },
      { id: 5, name: 'James Davey', phone: '+12345678910', shopName: 'Icon Boy', package: 'Premium', balance: 2458.15, published: true, avatar: 'https://i.pravatar.cc/150?img=5' },
      { id: 6, name: 'Lola Ryan', phone: '+12345678910', shopName: 'Febrick Fashion', package: 'Silver', balance: 8356.34, published: true, avatar: 'https://i.pravatar.cc/150?img=6' },
      { id: 7, name: 'Sofia Hall', phone: '+12343458910', shopName: 'Mobile Gadgets', package: 'Gold', balance: 12350.45, published: true, avatar: 'https://i.pravatar.cc/150?img=7' },
      { id: 8, name: 'Tily Thomson', phone: '+12345678910', shopName: 'Beyond Threads', package: 'Silver', balance: 9540.47, published: true, avatar: 'https://i.pravatar.cc/150?img=8' },
  ]);

  // This reactive object is bound to the form inputs using v-model
  const newSeller = reactive({
    name: '',
    phone: '',
    shopName: '',
    package: 'Silver', // Default package
  });

  // --- METHODS ---

  // Function to close the modal and reset the form
  const closeModal = () => {
    isModalOpen.value = false;
    // Reset form fields
    Object.assign(newSeller, {
      name: '',
      phone: '',
      shopName: '',
      package: 'Silver',
    });
  };

  // Function to add the new seller to the list
  const addSeller = () => {
    // Simple validation
    if (!newSeller.name || !newSeller.phone || !newSeller.shopName) {
      alert('Please fill out all required fields.');
      return;
    }
    
    // Create a new seller object
    const sellerToAdd = {
      id: Date.now(), // Use a timestamp for a unique ID
      name: newSeller.name,
      phone: newSeller.phone,
      shopName: newSeller.shopName,
      package: newSeller.package,
      balance: 0.00, // New sellers start with a zero balance
      published: false, // New shops are unpublished by default
      avatar: `https://i.pravatar.cc/150?img=${Math.floor(Math.random() * 70)}`, // Random avatar
    };

    // Add the new seller to the beginning of the array
    sellers.value.unshift(sellerToAdd);
    
    // Close the modal
    closeModal();
  };
  </script>

  <style scoped>
  /* General Page Styling */
  .sellers-page {
    background-color: #f8fafc;
    padding: 2rem;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    color: #334155;
  }

  .sellers-container {
    background-color: #ffffff;
    border-radius: 0.75rem;
    box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    overflow: hidden;
  }

  /* Header */
  .sellers-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
  }

  .title {
    font-size: 1.875rem;
    font-weight: 700;
    color: #1e293b;
  }

  .actions {
    display: flex;
    align-items: center;
    gap: 1rem;
  }

  .search-wrapper {
    position: relative;
  }

  .search-icon {
    position: absolute;
    top: 50%;
    left: 0.75rem;
    transform: translateY(-50%);
    color: #94a3b8;
  }

  .search-input {
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    padding: 0.65rem 0.75rem 0.65rem 2.5rem;
    width: 250px;
    outline: none;
    transition: border-color 0.2s;
  }

  .search-input:focus {
    border-color: #3b82f6;
  }

  .add-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background-color: #3b82f6;
    color: white;
    font-weight: 600;
    padding: 0.65rem 1.25rem;
    border: none;
    border-radius: 0.5rem;
    cursor: pointer;
    transition: background-color 0.2s;
  }

  .add-btn:hover {
    background-color: #2563eb;
  }

  /* Table */
  .table-wrapper {
    overflow-x: auto;
  }

  .sellers-table {
    width: 100%;
    border-collapse: collapse;
    text-align: left;
  }

  .sellers-table th, .sellers-table td {
    padding: 1rem 1.5rem;
    white-space: nowrap;
  }

  .sellers-table thead {
    background-color: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
  }

  .sellers-table th {
    font-size: 0.875rem;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }

  .sellers-table tbody tr {
    border-bottom: 1px solid #f1f5f9;
  }

  .sellers-table tbody tr:last-child {
    border-bottom: none;
  }

  /* Table Cells */
  .seller-info {
    display: flex;
    align-items: center;
    gap: 1rem;
  }

  .avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
  }

  .seller-name {
    font-weight: 600;
  }

  .seller-phone {
    font-size: 0.875rem;
    color: #64748b;
  }

  .action-icons {
    display: flex;
    align-items: center;
    gap: 1rem;
  }

  .icon {
    color: #94a3b8;
    cursor: pointer;
    transition: color 0.2s;
  }
  .icon:hover {
    color: #3b82f6;
  }

  /* Toggle Switch */
  .toggle-switch {
    position: relative;
    display: inline-block;
    width: 44px;
    height: 24px;
  }

  .toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
  }

  .slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: .4s;
    border-radius: 24px;
  }

  .slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
  }

  input:checked + .slider {
    background-color: #3b82f6;
  }

  input:checked + .slider:before {
    transform: translateX(20px);
  }

  /* Pagination */
  .pagination-footer {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 1.5rem;
    gap: 0.5rem;
  }
  .pagination-btn {
    border: 1px solid #e2e8f0;
    background: white;
    border-radius: 0.5rem;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-weight: 600;
  }
  .pagination-btn.active, .pagination-btn:hover {
    background-color: #3b82f6;
    color: white;
    border-color: #3b82f6;
  }


  /* Modal Styles */
  .modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(15, 23, 42, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
  }

  .modal-content {
    background: white;
    padding: 2rem;
    border-radius: 0.75rem;
    width: 100%;
    max-width: 500px;
    box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
  }

  .modal-title {
    margin-top: 0;
    margin-bottom: 1.5rem;
    font-size: 1.25rem;
    font-weight: 600;
    color: #1e293b;
  }

  .form-group {
    margin-bottom: 1rem;
  }

  .form-group label {
    display: block;
    font-weight: 500;
    margin-bottom: 0.5rem;
    color: #334155;
  }

  .form-group input, .form-group select {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #cbd5e1;
    border-radius: 0.375rem;
    box-sizing: border-box;
  }
  .form-group input:focus, .form-group select:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 1px #3b82f6;
  }

  .modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 0.75rem;
    margin-top: 2rem;
  }

  .btn-cancel, .btn-submit {
    padding: 0.65rem 1.25rem;
    border: none;
    border-radius: 0.375rem;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.2s, color 0.2s;
  }

  .btn-cancel {
    background-color: #f1f5f9;
    color: #475569;
    border: 1px solid #e2e8f0;
  }
  .btn-cancel:hover {
    background-color: #e2e8f0;
  }

  .btn-submit {
    background-color: #3b82f6;
    color: white;
  }
  .btn-submit:hover {
    background-color: #2563eb;
  }

  </style>