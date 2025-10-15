<?php

// use App\Http\Controllers\AddressController as ControllersAddressController;

use App\Http\Controllers\Api\AddressController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ParentCategoryController;
use App\Http\Controllers\Api\CurrentStatusController;
use App\Http\Controllers\Api\KitchenOrderController;
use App\Http\Controllers\Api\OrderCashController;
use App\Http\Controllers\Api\OrderController;
// use App\Http\Controllers\Api\SpecialMenuController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\StripeController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\TranslationController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CartItemController;
use App\Http\Controllers\Api\CustomerController;
// use App\Http\Controllers\AddressController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReviewController;
// use App\Http\Controllers\Api\WishlistController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\FileController;
use App\Http\Controllers\Api\InventoryController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\Api\OrderItemController;
use App\Http\Controllers\BakongController;
use App\Http\Controllers\ProfileController;
// use App\Http\Controllers\Api\AddressController;
// use App\Http\Controllers\AddressController;


// use App\Models\OrderItem;
use App\Models\Payment;
//  use App\Models\Inventory;
use Stripe\Customer;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


//inventory
Route::get('/inventories', [InventoryController::class, 'index']);
Route::post('/inventories', [InventoryController::class, 'store']);
Route::get('/inventories/{inventory}', [InventoryController::class, 'show']);
Route::put('/inventories/{inventory}', [InventoryController::class, 'update']);
Route::delete('/inventories/{inventory}', [InventoryController::class, 'destroy']);
Route::post('/inventories/{inventory}/adjust-stock', [InventoryController::class, 'adjustStock']);
Route::get('/inventories/low-stock', [InventoryController::class, 'lowStock']);
   //supplier
    Route::get('/suppliers', [SupplierController::class, 'index']);
    Route::post('/suppliers', [SupplierController::class, 'store']);
    Route::get('/suppliers/{supplier}', [SupplierController::class, 'show']);
    Route::put('/suppliers/{supplier}', [SupplierController::class, 'update']);
    Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy']);
    //get address by customer id
Route::get('/address/customer/{customer_id}', [AddressController::class, 'getByCustomerId']);
// Protected Routes
Route::middleware('jwt.auth')->group(function () {
    //profile
    Route::get('/profile',  [AuthController::class, 'getProfile']);
    Route::put('/profile',  [AuthController::class, 'updateProfile']);
    // File upload endpoints
    Route::post('files/upload', [FileController::class, 'upload']);
    Route::post('files/upload-multiple', [FileController::class, 'uploadMultiple']);
    Route::delete('files', [FileController::class, 'delete']);

    Route::post('/logout', [AuthController::class, 'logout']);
    //profile

    // Route::get('/profile',  [AuthController::class, 'getProfile']);
    //  Route::put('/profile',  [AuthController::class, 'updateProfile']);

    //User
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/showusers/{user}', [UserController::class, 'show']);
    Route::post('/addusers', [UserController::class, 'store']);
    Route::put('/updateusers/{user}', [UserController::class, 'update']);
    Route::delete('/deleteusers/{user}', [UserController::class, 'destroy']);

    //Category
    Route::put('/updatecategories/{category}', [CategoryController::class, 'update']);
    Route::get('categories', [CategoryController::class, 'index']);
    Route::post('addcategories', [CategoryController::class, 'store']);
    Route::get('showcategories/{category}', [CategoryController::class, 'show']);
    Route::delete('deletecategory/{category}', [CategoryController::class, 'destroy']);

    // Parent Category
    Route::get('parent-categories', [ParentCategoryController::class, 'index']);
    Route::post('parent-categories', [ParentCategoryController::class, 'store']);
    Route::get('parent-categories/{parentCategory}', [ParentCategoryController::class, 'show']);
    Route::put('parent-categories/{parentCategory}', [ParentCategoryController::class, 'update']);
    Route::delete('parent-categories/{parentCategory}', [ParentCategoryController::class, 'destroy']);

    //brand
    Route::put('/updatebrands/{brand}', [BrandController::class, 'update']);
    Route::get('brands', [BrandController::class, 'index']);
    Route::post('addbrands', [BrandController::class, 'store']);
    Route::get('showbrands/{brand}', [BrandController::class, 'show']);
    Route::delete('deletebrand/{brand}', [BrandController::class, 'destroy']);
    //product
    Route::get('/products', [ProductController::class, 'index']);         // Fetch all products
    Route::post('/addproducts', [ProductController::class, 'store']);        // Create a product
    Route::get('showproductds/{products}', [ProductController::class, 'show']);      // Get a single product
    Route::put('updateproducts/{products}', [ProductController::class, 'update']);    // Update a product
    Route::delete('deleteproduct/{products}', [ProductController::class, 'destroy']); // Delete a product
    Route::post('/products/search', [ProductController::class, 'searchProductByName']); // Search products by name

    //customer 
    Route::get('/customers', [CustomerController::class, 'index']);
    Route::post('/customers', [CustomerController::class, 'store']);
    Route::put('/customers/{id}', [CustomerController::class, 'update']);
    //  Route::put('/customers/{id}', [CustomerController::class, 'delete']);
    // Route::delete('/customers/{id}', [CustomerController::class, 'destroy']); // Delete a product
    Route::delete('/customers/{id}', [CustomerController::class, 'destroy']);

    //  Route::get('/customers/search', [CustomerController::class, 'search']);

    //  Route::put('/customers/{id}', [CustomerController::class, 'update']);



    // Deliveries
    Route::get('/deliveries', [DeliveryController::class, 'index']);
    Route::post('/deliveries', [DeliveryController::class, 'store']);
    Route::get('/deliveries/{delivery}', [DeliveryController::class, 'show']);
    Route::put('/deliveries/{delivery}', [DeliveryController::class, 'update']);
    Route::delete('/deliveries/{delivery}', [DeliveryController::class, 'destroy']);
    Route::put('/deliveries/{delivery}/state', [DeliveryController::class, 'updateState']);
    Route::post('/deliveries/{delivery}/mark-shipped', [DeliveryController::class, 'markAsShipped']);
    Route::post('/deliveries/{delivery}/mark-delivered', [DeliveryController::class, 'markAsDelivered']);
    Route::get('/deliveries/tracking/{tracking_number}', [DeliveryController::class, 'getByTrackingNumber']);
    Route::get('/orders/{order}/deliveries', [DeliveryController::class, 'getByOrder']);

    // Shipping
    Route::get('/shipping', [ShippingController::class, 'index']);
    Route::post('/shipping', [ShippingController::class, 'store']);
    Route::get('/shipping/active', [ShippingController::class, 'getActive']);
    Route::get('/shipping/{shipping}', [ShippingController::class, 'show']);
    Route::put('/shipping/{shipping}', [ShippingController::class, 'update']);
    Route::delete('/shipping/{shipping}', [ShippingController::class, 'destroy']);
    Route::post('/shipping/{shipping}/toggle-active', [ShippingController::class, 'toggleActive']);
    Route::post('/shipping/{shipping}/activate', [ShippingController::class, 'activate']);
    Route::post('/shipping/{shipping}/deactivate', [ShippingController::class, 'deactivate']);
    Route::get('/shipping/by-cost-range', [ShippingController::class, 'getByCostRange']);
    Route::get('/shipping/by-estimated-days', [ShippingController::class, 'getByEstimatedDays']);

    // Sizes
    Route::get('/sizes', [SizeController::class, 'index']);
    Route::post('/sizes', [SizeController::class, 'store']);
    Route::post('/sizes/bulk', [SizeController::class, 'bulkStore']);
    Route::get('/sizes/active', [SizeController::class, 'getActive']);
    Route::get('/sizes/{size}', [SizeController::class, 'show']);
    Route::put('/sizes/{size}', [SizeController::class, 'update']);
    Route::delete('/sizes/{size}', [SizeController::class, 'destroy']);
    Route::post('/sizes/{size}/toggle-active', [SizeController::class, 'toggleActive']);
    Route::post('/sizes/{size}/activate', [SizeController::class, 'activate']);
    Route::post('/sizes/{size}/deactivate', [SizeController::class, 'deactivate']);
    Route::get('/sizes/{size}/products', [SizeController::class, 'getProducts']);

    // Promotions
    Route::get('/promotions', [PromotionController::class, 'index']);
    Route::post('/promotions', [PromotionController::class, 'store']);
    Route::get('/promotions/current', [PromotionController::class, 'getCurrent']);
    Route::get('/promotions/upcoming', [PromotionController::class, 'getUpcoming']);
    Route::get('/promotions/expired', [PromotionController::class, 'getExpired']);
    Route::get('/promotions/{promotion}', [PromotionController::class, 'show']);
    Route::put('/promotions/{promotion}', [PromotionController::class, 'update']);
    Route::delete('/promotions/{promotion}', [PromotionController::class, 'destroy']);
    Route::post('/promotions/{promotion}/toggle-active', [PromotionController::class, 'toggleActive']);
    Route::post('/promotions/{promotion}/activate', [PromotionController::class, 'activate']);
    Route::post('/promotions/{promotion}/deactivate', [PromotionController::class, 'deactivate']);
    Route::post('/promotions/{promotion}/extend', [PromotionController::class, 'extendEndDate']);
    Route::get('/promotions/calculate-price', [PromotionController::class, 'calculateDiscountedPrice']);
    Route::get('/products/{product}/promotions', [PromotionController::class, 'getByProduct']);
 

    // Messages
    Route::get('/messages', [MessageController::class, 'index']);
    Route::post('/messages', [MessageController::class, 'store']);
    Route::get('/messages/{message}', [MessageController::class, 'show']);
    Route::put('/messages/{message}', [MessageController::class, 'update']);
    Route::delete('/messages/{message}', [MessageController::class, 'destroy']);
    Route::get('/messages/conversation', [MessageController::class, 'getConversation']);
    Route::post('/messages/{message}/read', [MessageController::class, 'markAsRead']);
    Route::post('/messages/mark-all-read', [MessageController::class, 'markAllAsRead']);
    Route::get('/messages/unread-count', [MessageController::class, 'getUnreadCount']);
    Route::get('/messages/recent-conversations', [MessageController::class, 'getRecentConversations']);
    Route::get('/messages/search', [MessageController::class, 'search']);

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications', [NotificationController::class, 'store']);
    Route::get('/notifications/{notification}', [NotificationController::class, 'show']);
    Route::put('/notifications/{notification}', [NotificationController::class, 'update']);
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy']);
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead']);
    Route::post('/notifications/{notification}/unread', [NotificationController::class, 'markAsUnread']);
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead']);
    Route::post('/notifications/mark-type-read', [NotificationController::class, 'markAllByTypeAsRead']);
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount']);
    Route::get('/notifications/unread-count-by-type', [NotificationController::class, 'getUnreadCountByType']);
    Route::get('/notifications/recent', [NotificationController::class, 'getRecent']);
    Route::delete('/notifications/delete-read', [NotificationController::class, 'deleteRead']);
    Route::delete('/notifications/delete-by-type', [NotificationController::class, 'deleteByType']);
    Route::get('/notifications/search', [NotificationController::class, 'search']);

    // Reviews
    Route::get('/reviews', [ReviewController::class, 'index']);
    Route::post('/reviews', [ReviewController::class, 'store']);
    Route::get('/reviews/{review}', [ReviewController::class, 'show']);
    Route::put('/reviews/{review}', [ReviewController::class, 'update']);
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy']);
    Route::post('/reviews/{review}/approve', [ReviewController::class, 'approve']);
    Route::post('/reviews/{review}/disapprove', [ReviewController::class, 'disapprove']);
    Route::post('/reviews/{review}/toggle-approval', [ReviewController::class, 'toggleApproval']);
    Route::get('/products/{product}/reviews', [ReviewController::class, 'getByProduct']);
    Route::get('/customers/{customer}/reviews', [ReviewController::class, 'getByCustomer']);
    Route::get('/reviews/pending', [ReviewController::class, 'getPendingReviews']);
    Route::get('/reviews/statistics', [ReviewController::class, 'getStatistics']);
    Route::get('/reviews/search', [ReviewController::class, 'search']);

    // Wishlist


    Route::get('/wishlist/{customer_id}', [WishlistController::class, 'index']);   // all wishlist for a customer
    Route::post('/wishlist', [WishlistController::class, 'store']);                // add to wishlist
    Route::delete('/wishlist/{id}', [WishlistController::class, 'destroy']);       // remove by wishlist id



    //inventory

    //brand
    Route::get('/brands', [BrandController::class, 'index']);
    Route::post('/brands', [BrandController::class, 'store']);
    Route::get('/brands/{brand}', [BrandController::class, 'show']);
    Route::put('/brands/{brand}', [BrandController::class, 'update']);
    Route::delete('/brands/{brand}', [BrandController::class, 'destroy']);
    //Order
    // Route::get('/orders', [OrderController::class, 'index']);
    // Route::post('/orders', [OrderController::class, 'store']);
    // Route::get('/orders/{id}', [OrderController::class, 'show']);
    // Route::put('/orders/{id}', [OrderController::class, 'update']);
    // Route::delete('/orders/{id}', [OrderController::class, 'destroy']);
    // Route::get('/getitem', [OrderController::class, 'LastOrder']);
    // Route::post('order/add-items', [OrderController::class, 'addItems']);
    // // Route::get('/cart', [CartController::class,'index']);
    //Orderitem 

    Route::get('/order-items', [OrderItemController::class, 'index']);

    Route::post('/orderitem', [OrderItemController::class, 'store']);

    Route::post('/stripe', [StripeController::class, 'stripePost']);
    Route::post('stripe-confirm', [StripeController::class, 'confirm']);


    // Payment by Scan
    Route::post('/payment/create', [StripeController::class, 'createPaymentLink']);
    // Route::get('/payment/status/{paymentId}', [StripeController::class, 'checkPaymentStatus']);
    Route::post('/payment/webhook', [StripeController::class, 'handleWebhook']);
    Route::get('/payment/success', [StripeController::class, 'success'])->name('payment.success');
    Route::get('/payment/cancel', [StripeController::class, 'cancel'])->name('payment.cancel');

    //ABA Payment
    // Route::post('/payment/checkout', [PaymentController::class, 'checkout']);

    //translate
    Route::post('/switch-language', [TranslationController::class, 'translate']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

//Currentstatus
// Route::get('/status',[CurrentStatusController::class, 'index']);
// Route::post('/status', [CurrentStatusController::class, 'store']);

//product
Route::get('/public/products', [ProductController::class, 'index']);
Route::get('/public/products/{id}', [ProductController::class, 'show']);
//brands
Route::get('/public/brands', [BrandController::class, 'index']);
Route::get('/public/brands/{name}', [BrandController::class, 'show']);
//womens
Route::get('/public/products/category/{id}', [ProductController::class, 'getProductsByCategory']);
Route::get('/public/products/parent-category/{name}', [ProductController::class, 'getProductsByParentCategory']);
Route::get('/public/products/parent-category/{name}/{subcategoryname}', [ProductController::class, 'getProductsByParentCategory']);
Route::get('/public/parent-categories/stats', [ProductController::class, 'getParentCategoriesWithStats']);
Route::get('/public/products/women/{id}', [ProductController::class, 'show']);
Route::get('/public/products/search/{name}', [ProductController::class, 'searchProductByName']);
//parentcategory
Route::get('/public/parent-categories', [ParentCategoryController::class, 'index']);

// User
Route::get('/users', [UserController::class, 'index']);
Route::post('/refresh', [AuthController::class, 'refresh']);


//cart
Route::get('/cart', [CartController::class, 'show']);
Route::post('/cart/items', [CartController::class, 'addItem']);
Route::put('/cart/items/{item}', [CartController::class, 'updateItem']);
Route::delete('/cart/items/{item}', [CartController::class, 'removeItem']);
Route::delete('/cart', [CartController::class, 'clear']);
//cart item
Route::get('/cartitem',  [CartItemController::class, 'show']);
//address
Route::get('/public/address/customer/{customer_id}', [AddressController::class, 'getByCustomerId']);
Route::post('/address/customer/{customer_id}', [AddressController::class, 'storeByCustomerId']);
      
Route::put('/address/{id}', [AddressController::class, 'update']);
// Route::post('/public/address/customer/{customer_id}', [AddressController::class, 'store']);





//address
Route::get('/address', [AddressController::class, 'index']);
Route::post('/address', [AddressController::class, 'store']);
Route::get('/address/{id}', [AddressController::class, 'show']);
Route::delete('/address/{id}', [AddressController::class, 'destroy']);
//bakong
Route::post('/bakong/qr', [BakongController::class, 'generateQr']);
Route::get('/bakong/status/{hash}', [BakongController::class, 'checkStatus']);
