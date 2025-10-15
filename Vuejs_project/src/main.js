import { createApp } from 'vue'
import './style.css'
import App from './App.vue'
import { createRouter, createWebHistory } from 'vue-router'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
// import DashboardCard from '@/components/DashboardCard.vue'
// import LineChart from '@/components/Charts/LineChart.vue'
// import PieChart from '@/components/Charts/PieChart.vue'
// import WorldMap from '@/components/WorldMap.vue'
// import Calendar from '@/components/Calendar.vue'
// import ProjectsTable from '@/components/ProjectsTable.vue'
// import BarChart from '@/components/Charts/BarChart.vue'

// Import Components
import Login from './components/Login.vue'
// import Dashboard from './components/dashboard/Dashboard.vue'
import Admin from './components/admin/pages/master/Admin.vue'
import Home from './components/admin/pages/master/Home.vue'
// import User from './components/admin/pages/master/user/Usercreate.vue'
// import Menu from './components/admin/pages/master/Menu.vue'
// import Food from './components/admin/pages/master/Food.vue'

// import SpecialMenu from './components/admin/pages/master/SpecialMenu.vue'
import Unauthorized from './components/Authorized/Unauthorized.vue'
import NotFound from './components/Authorized/NotFound.vue'
// import OrderHistory from './components/admin/pages/master/order/OrderHistory.vue'
// import Order from './components/admin/pages/master/order/Order.vue'
// Import Chart.js and other required libraries
import Chart from 'chart.js/auto'
// import 'jsvectormap'


import { library } from '@fortawesome/fontawesome-svg-core';
import { faHouse, faDownload, faTrash, faPlus, faMinus } from '@fortawesome/free-solid-svg-icons';
// Font Awesome CSS
import '@fortawesome/fontawesome-free/css/all.min.css';
// import Invoice from './components/admin/pages/master/order/Invoice.vue'
import Kithchen from './components/kitchen/Kithchen.vue'
import Test from './components/Test.vue'
import WomenCollectionPage from './components/dashboard/category/women.vue'
import MenCollectionPage from './components/dashboard/category/men.vue'
import BoyCollectionPage from './components/dashboard/category/boy.vue'
import GirlCollectionPage from './components/dashboard/category/girl.vue'
// import Filter from './components/dashboard/category/filter.vue'
//product_list
// import Productdetailpage from './components/dashboard/product/productdetailpage.vue'
import List from './components/dashboard/product/list.vue'
import Create from './components/dashboard/product/create.vue'
import Edit from './components/dashboard/product/edit.vue'


//order_list
import orderList from './components/dashboard/order/orderList.vue'
import OrderDetail from './components/dashboard/order/orderDetail.vue'
// import calender from './components/dashboard/calender.vue'
import Categorylist from './components/dashboard/categorys/categorylist.vue'

import Productdetailpage from './components/dashboard/category/productdetailpage.vue'
import Dashboard from './components/dashboard/dashboard.vue'

import Lists from './components/dashboard/Seller/lists.vue'
import CustomerList from './components/dashboard/customer/CustomerList.vue'
import ParentCategoryList from './components/dashboard/parent/parentcategorylist.vue'
import ParentCreate from './components/dashboard/parent/parentcreate.vue'
import CategoryEdit from './components/dashboard/categorys/edit.vue'

import Invoice from './components/dashboard/order/Invoice.vue'
import Uselist from './components/admin/pages/master/user/Uselist.vue'
import Usercreate from './components/admin/pages/master/user/Usercreate.vue'
import Sale from './components/dashboard/sale/sale.vue'
import Inventory from './components/dashboard/inventory/inventory.vue'
// import AddToBag from './components/dashboard/category/AddToBag.vue'
// import AddToBagPage from './components/dashboard/category/AddToBagPage.vue'
import WomenNewArrivals from './components/dashboard/category/women-new-arrivals.vue'
import LayoutUser from './components/dashboard/LayoutUser.vue'
import Profile from './components/dashboard/profile.vue'
// import Checkout from './components/dashboard/checkout.vue'
import Brand from './components/dashboard/brand/brand.vue'
import Supplier from './components/dashboard/supplier/supplier.vue'
import Filter from './components/dashboard/category/filter.vue'
import WishList from './components/dashboard/WishList.vue'
import Name from './components/dashboard/brand/name.vue'


import Cart from './components/dashboard/cart/Cart.vue'
import Adidas from './components/dashboard/brand/adidas.vue'
import HM from './components/dashboard/brand/h&m.vue'
import Zara from './components/dashboard/brand/zara.vue'
import Puma from './components/dashboard/brand/puma.vue'
import Checkout from './components/dashboard/checkout.vue'
import Address from './components/dashboard/address.vue'



// import Brandnike from './components/dashboard/brand/brandnike.vue'

// import Brandname from './components/dashboard/category/Brandname.vue'
// import Brand from './components/dashboard/brand/brand.vue'
// import Brand from './components/dashboard/category/Brandname.vue'
// import WomenNewArrivals from './components/dashboard/category/women-new-arrivals.vue'
// import WomenNewArrivals from './components/dashboard/category/women-new-arrivals.vue'
// import DashboardCard from './components/admin/pages/master/DashboardCard.vue'



// import Home from './components/dashboard/dashboard.vue'
//category

// Define Routes
const routes = [
    {
        path: '/login',
        component: Login,
        name: 'Login',
    },
    {
        path: '/test',
        component: Test,
        name: 'Test',
    },
    {
        path: '/women-new-arrivals',
        component: WomenNewArrivals,
        name: 'WomenNewArrivals',
        meta: { requiresAuth: false },
    },
    {
        path: '/women',
        component: WomenCollectionPage,
        name: 'Women',
        meta: { requiresAuth: false },
    },
    {
        path: '/men',
        component: MenCollectionPage,
        name: 'Men',
        meta: { requiresAuth: false },
    },
    // {
    //     path: '/women-new-arrivals',
    //     component: WomenNewArrivals,
    //     name: 'WomenNewArrivals',
    //     meta: { requiresAuth: true, roles: ['user']  }
    // },
    // {
    //     path: '/product/:id',
    //     name: 'ProductDetail',
    //     component: Productdetailpage,
    //     props: true

    // },
    //  //cart
    //  {
    //     path: '/admin/category/AddToBag',
    //     component: AddToBagPage,
    //     name: 'cart',
    //     meta: { requiresAuth: false },
    // },
    // {
    //     path: '/men',
    //     component: MenCollectionPage,
    //     name: 'MenCollectionPage',
    //     meta: { requiresAuth: true, roles: ['user', 'admin'] }
    // },
    // {
    //     path: '/boy',
    //     component: BoyCollectionPage,
    //     name: 'BoyCollectionPage',
    //     meta: { requiresAuth: true, roles: ['user', 'admin'] }
    // },
    // {
    //     path: '/girl',
    //     component: GirlCollectionPage,
    //     name: 'GirlCollectionPage',
    //     meta: { requiresAuth: true, roles: ['user', 'admin'] }
    // },
//brands



    {
        path: '/filter',
        component: Filter,
        name: 'Filter',
        meta: { requiresAuth: true, roles: ['user'] }
    },
    // {
    //     path: '/kitchen',
    //     component: Kithchen,
    //     name: 'Kitchen',
    //     meta: { requiresAuth: true, roles: ['seller', 'admin'] }
    // },

    // //seller/sellerlist
    // {
    //     path: '/seller',
    //     component: Sellerlist,
    //     name: 'Sellerlist',
    //     meta: { requiresAuth: true, roles: ['seller','admin' ] },
    // },
    // {
    //     path: '/createsellerlist',
    //     component: Createsellerlist,
    //     name: 'CreateSellerlist',
    //     meta: {requiresAuth: true, roles: ['seller', 'admin']}
    // },
    //user
    {
        path: '/',
        component: LayoutUser,
        name: 'LayoutUser',
        meta: { requiresAuth: true,role:['user'] },
        children: [
            {
                path: '',
                component: Dashboard,
                name: 'Dashboard',
                meta: { requiresAuth: false },
            },
            // {
            //     path: '/category/women',
            //     component: WomenCollectionPage,
            //     name: 'Women',
            //     meta: { requiresAuth: false },
            // },
            // {
            //     path: '/category/men',
            //     component:MenCollectionPage,
            //     name: 'Men',
            //     meta: { requiresAuth: false },
            // },
            // {
            //     path: '/brandname',
            //     component:Brandname,
            //     name: 'brandname',
            //     meta: { requiresAuth:false }
            // },
           
            {
                path: '/men',
                component: MenCollectionPage,
                name: 'Men',
                meta: { requiresAuth: false },
            },
            //wishlist
      
            {
                path: '/boy',
                component: BoyCollectionPage,
                name: 'Boys',
                meta: { requiresAuth: false },
            },
            {
                path: '/girl',
                component: GirlCollectionPage,
                name: 'Girls',
                meta: { requiresAuth: false },
            },
            {
                path: '/product/:id',
                component: Productdetailpage,
                name: 'Productdetailpage',
                meta: { requiresAuth: false },
                props: true,
            },
           {
                path: '/checkout',
                component: Checkout,
                name: 'Checkout',
                meta: { requiresAuth: false },
            },
 
            {
                path: '/profile',
                component: Profile,
                name: 'Profile',
                meta: { requiresAuth: false },
            },
                 {
                path: '/address',
                component: Address,
                name: 'Address',
                meta: { requiresAuth: false },
            },
            // {
            //     path: '/checkout',
            //     component: Checkout,
            //     name: 'Checkout',
            //     meta: { requiresAuth: false },
            // },
            //cart
     {
        path: '/cart',
        component: Cart,
       name: 'cart',
        meta: { requiresAuth: false },
    },
    
             {
                path: '/wishlist',
                component: WishList,
                name: 'Wishlist',
                meta: { requiresAuth: false },
            },
              {
                path: '/brand/Nike',
                component: Name,
                name: 'Name',
                meta: { requiresAuth: false },
            },
              {
                path: '/brand/Adidas',
                component: Adidas,
                name: 'Adidas',
                meta: { requiresAuth: false },
            },
            {
                path: '/brand/puma',
                component: Puma,
                name: 'puma',
                meta: { requiresAuth: false },
            },
            {
                path: '/brand/zara',
                component: Zara,
                name: 'Zara',
                meta: { requiresAuth: false },
            },
            {
                path: '/brand/h&m',
                component: HM,
                name: 'HM',
                meta: { requiresAuth: false },
            },
            //women
            {
                path: '/category/:parentName',
                component: WomenCollectionPage,
                name: 'parentCategory',
                meta: { requiresAuth: false },
                props: true,
            },
            {
                path: '/category/:parentName/:childName',
                component: WomenCollectionPage,
                name: 'childCategory',
                meta: { requiresAuth: false },
                props: true,
            },
            
            // Men category routes
            {
                path: '/category/Men',
                component: MenCollectionPage,
                name: 'MenCategory',
                meta: { requiresAuth: false },
            },
            {
                path: '/category/men/:childName',
                component: MenCollectionPage,
                name: 'MenChildCategory',
                meta: { requiresAuth: false },
                props: true,
            },

            // Boys category routes
            {
                path: '/category/boy',
                component: BoyCollectionPage,
                name: 'BoysCategory',
                meta: { requiresAuth: false },
            },
            {
                path: '/category/boys/:childName',
                component: BoyCollectionPage,
                name: 'BoysChildCategory',
                meta: { requiresAuth: false },
                props: true,
            },
            // Girls category routes
            {
                path: '/category/girl',
                component: GirlCollectionPage,
                name: 'GirlsCategory',
                meta: { requiresAuth: false },
            },
            {
                path: '/category/girls/:childName',
                component: GirlCollectionPage,
                name: 'GirlsChildCategory',
                meta: { requiresAuth: false },
                props: true,
            },

        ]
    },

    //admin 
    {
        path: '/admin/',
        component: Admin,
        name: 'Admin',
        meta: { requiresAuth: true, roles: ['admin', 'seller'] },
        children: [
            {
                path: '/admin/home',
                component: Home,
                name: 'Home',
                meta: { requiresAuth: true, roles: ['admin'] },
            },
         
           //product
            {
                path: '/admin/product/list',
                component: List,
                name: 'List',
                meta: { requiresAuth: true, roles: ['admin'] },
            },
            {
                path: '/admin/products/create',
                component: Create,
                name: 'Create',
                meta: { requiresAuth: true, roles: ['admin'] },
            },
            {
                path: '/admin/products/edit',
                component: Edit,
                name: 'Edit',
                props: (route) => ({ productId: route.query.id }),
                meta: { requiresAuth: true, roles: ['admin'] },
            },
           //brand
           {
            path: '/admin/brand',
            component: Brand,
            name: 'Brand',
            meta: { requiresAuth: true, roles: ['admin'] },
        },
        //supplier
        {
            path: '/admin/supplier',
            component: Supplier,
            name: 'Supplier',
            meta: { requiresAuth: true, roles: ['admin'] },
        },

            // {
            //     path: '/admin/product/ProductList',
            //     component: Productlist,
            //     name: 'Product',
            //     meta: { requiresAuth: true, roles: ['admin'] },
            // },
              
            //order
            {
                path: '/admin/order/orderList',
                component: orderList,
                name: 'orderlist',
                meta: { requiresAuth: true, roles: ['admin'] },
            },
            {
                path: '/admin/order/orderDetail',
                component: OrderDetail,
                name: 'orderdetail',
                meta: { requiresAuth: true, roles: ['admin'] },
            },
            {
                path: '/admin/order/Invoice',
                component: Invoice,
                name: 'invoice',
                meta: { requiresAuth: true, roles: ['admin'] },
            },
            // //category
            // {
            //     path: '/admin/calendar',
            //     component: calender,
            //     name: 'calendar',

            // },
//         {
//               name: 'Dashboard',
//   components: {
 
//     LineChart,
//     PieChart,
//     WorldMap,
//     Calendar,
//     ProjectsTable,
//     BarChart
//   }},
            {
                path: '/admin/salereport',
                component: Sale,
                name: 'Sale',
                meta: { requiresAuth: true, roles: ['admin'] },
            },
            //inventory
             {
                path: '/admin/inventory',
                component: Inventory,
                name: 'Inventory',
                meta: { requiresAuth: true, roles: ['admin'] },
            },
            //customer
            {
                path: '/admin/customer/CustomerList',
                component: CustomerList,
                name: 'CustomerList',
                meta: { requiresAuth: true, roles: ['admin'] },
            },
            // {
            //     path: '/admin/customer/createcustomer',
            //     component: Createcustomer,
            //     name: 'Createcustomer',
            //     meta: { requiresAuth: true, roles: ['admin'] },
            // },
            
            {
                path: '/admin/categorys/categorylist',
                component: Categorylist,
                name: 'categorylist',
                meta: { requiresAuth: true, roles: ['admin'] },
            },
            {
                path: '/admin/categorys/edit',
                component: CategoryEdit,
                name: 'CategoryEdit',
                meta: { requiresAuth: true, roles: ['admin'] },
            },
            
            // parent
            {
                path: '/admin/parent/parentcategorylist',
                component: ParentCategoryList,
                name: 'ParentCategoryList',
                meta: { requiresAuth: true, roles: ['admin'] },
            },
            {
                path: '/admin/parent/parentcreate',
                component: ParentCreate,
                name: 'ParentCreate',
                meta: { requiresAuth: true, roles: ['admin'] },
            },
            //seller
  {
                path: '/admin/Seller/lists',
                component: Lists,
                name: 'list',
                meta: { requiresAuth: true, roles: ['admin','seller'] },
            }, 

               
            //     {
            //     path: '/seller/sellerlist',
            //     component: Sellerlist,
            //     name: 'Seller',
            //     meta: { requiresAuth: true, roles: ['admin'] },
            // },
            {
                path: '/admin/user/Uselist',
                component: Uselist,
                name: 'User',
                meta: { requiresAuth: true, roles: ['admin'] },
            },
             {
                path: '/admin/user/Usercreate',
                component: Usercreate,
                name: 'Usercreate',
                meta: { requiresAuth: true, roles: ['admin'] },
            },
            {
                path: 'invoice',
                component: Invoice,
                name: 'Invoice',
                meta: { requiresAuth: true, role: ['admin'] },
            },

        ]
    },

    {
        path: '/unauthorized',
        component: Unauthorized,
        name: 'Unauthorized'
    },
    {
        path: '/:pathMatch(.*)*',
        component: NotFound,
        name: 'NotFound'
    }
]

// Create Router
const router = createRouter({
    history: createWebHistory(),
    routes: routes
})

// Global variable to control navigation
let allowNavigation = true;

// Function to allow navigation
export const allowRouteNavigation = () => {
    allowNavigation = true;
    router.push(router.currentRoute); // Trigger navigation
};

// Function to deny navigation
export const preventRouteNavigation = () => {
    allowNavigation = false;
};

router.beforeEach((to, _from, next) => {
    const role = sessionStorage.getItem('role');

    // Handle the allowNavigation flag first
    if (!allowNavigation) {
        console.log("Navigation cancelled by route guard (due to preventRouteNavigation)");
        // IMPORTANT: Do NOT call router.push here.
        // Just abort the current navigation.
        next(false);
        // Reset allowNavigation if you intend for the next navigation to be allowed
        // (This might need to be triggered by the user action that calls allowRouteNavigation)
        // allowNavigation = true; // Consider if this reset should happen here or with allowRouteNavigation()
        return;
    }

    // If allowNavigation is true, proceed with authentication and role checks
    if (to.meta.requiresAuth) {
        if (!role) {
            console.log('User not authenticated. Redirecting to login.');
            // Check if the target route is already the login page to prevent another redirect
            if (to.path !== '/login') {
                next('/login');
            } else {
                next(); // Allow access to login page
            }
        } else if (to.meta.roles && !to.meta.roles.includes(role)) {
            console.log(`Role '${role}' is not authorized for this route.`);
            next('/unauthorized');
        } else {
            next(); // User authenticated and authorized
        }
    } else {
        next(); // Route does not require authentication
    }
});

library.add(faHouse, faDownload, faTrash, faPlus, faMinus);
// Create App Instance
const app = createApp(App)

// Register Font Awesome Icon Component
app.component('FontAwesomeIcon', FontAwesomeIcon)

// Use Router
app.use(router)

// Mount App
app.mount('#app')