<?php

use App\Http\Controllers\admin\CalanderController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\LoginController;
use App\Http\Controllers\admin\PaymentController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\auth\ForgotpasswordController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SellerEarningController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\TranslationController;
use App\Http\Controllers\user\auth\UserLoginRegisterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\UserSettingController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Modules\GeneralSetting\Http\Controllers\Admin\LanguageController;
use Modules\Gigs\Http\Controllers\FileUploadController;
use Modules\Gigs\Http\Controllers\GigsController;
use Modules\Gigs\Http\Controllers\OrdersController;
use Modules\Page\Http\Controllers\PageController;

Route::get('/storage-link', function () {
    Artisan::call('storage:link');
    return Artisan::output();
})->name('storage-link');

Route::get('/storage-linkadmin', function () {
    Artisan::call('storage:link');
    return redirect()->route('login');
})->name('storage-linkadmin');

Route::group(['middleware' => 'setLocale'], function () {
    Route::get('/db-backup', function () {
        try {
            Artisan::call('backup:database');
            Session::flash('success', 'Database backup completed successfully.');
        } catch (\Exception $e) {
            Session::flash('error', 'Backup failed: ' . $e->getMessage());
        }
        return redirect()->route('admin.database-settings');
    })->name('backup');

    Route::get('/system-backup', function () {
        try {
            Artisan::call('backup:system');
            Session::flash('success', 'Database backup completed successfully.');
        } catch (\Exception $e) {
            Session::flash('error', 'Backup failed: ' . $e->getMessage());
        }

        return redirect()->route('admin.system-backup-settings');
    })->name('system-backup');

    Route::get('/clear', function () {
        Artisan::call('cache:clear');
        Artisan::call('route:clear');
        Artisan::call('config:clear');
        Artisan::call('optimize:clear');
        return 'Cleared!';
    });

    Route::get('/admin', [DashboardController::class, 'index'])->middleware(['admin', 'permission'])->name('dashboard');
    Route::get('admin/login', [LoginController::class, 'index'])->name('admin-login');
    Route::post('admin/verify-login', [LoginController::class, 'verifyLogin'])->name('verify-login');
    Route::get('admin-logout', [LoginController::class, 'logout'])->middleware('admin')->name('admin.logout');
    Route::get('forgot-password', [ForgotpasswordController::class, 'index'])->name('forgot-password');
    Route::post('forgot-password/send-otp', [ForgotpasswordController::class, 'sendOtp'])->name('send-otp');
    Route::get('forgot-password/verify-otp', [ForgotpasswordController::class, 'verifyOtp'])->name('verify-otp');
    Route::post('forgot-password/resend-otp', [ForgotpasswordController::class, 'resendOtp'])->name('send-otp');
    Route::post('forgot-password/confirm-otp', [ForgotpasswordController::class, 'confirmOtp'])->name('confirm-otp');
    Route::get('reset-password', [ForgotpasswordController::class, 'resetPassword'])->name('reset-password');
    Route::post('forgot-password/update-password', [ForgotpasswordController::class, 'updatePassword'])->name('update-password');
    Route::get('admin/translations/{file}/{module}', [TranslationController::class, 'getFileTranslations'])->name('admin.translations');

    Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function () {
        Route::post('get-income', [DashboardController::class, 'getIncome'])->name('admin.get-income');
        //Country
        Route::get('country', [CountryController::class, 'index'])->name('country.index')->middleware('permission');
        Route::post('country/store', [CountryController::class, 'store'])->name('country.store');
        Route::get('country/datatable', [CountryController::class, 'list'])->name('country.list');
        Route::get('country/edit/{id}', [CountryController::class, 'edit'])->name('country.edit');
        Route::post('country/update', [CountryController::class, 'update'])->name('country.update');
        Route::post('country/delete', [CountryController::class, 'delete'])->name('country.delete');
        Route::post('country/delete-bulk', [CountryController::class, 'bulkDelete'])->name('country.bulkDelete');
        //State
        Route::get('state', [StateController::class, 'index'])->name('state.index')->middleware('permission');
        Route::post('state/store', [StateController::class, 'store'])->name('state.store');
        Route::get('state/datatable', [StateController::class, 'list'])->name('state.list');
        Route::get('state/edit/{id}', [StateController::class, 'edit'])->name('state.edit');
        Route::post('state/update', [StateController::class, 'update'])->name('state.update');
        Route::post('state/delete', [StateController::class, 'delete'])->name('state.delete');
        Route::post('state/delete-bulk', [StateController::class, 'bulkDelete'])->name('state.bulkDelete');
        //city
        Route::get('city', [CityController::class, 'index'])->name('city.index')->middleware('permission');
        Route::post('city/store', [CityController::class, 'store'])->name('city.store');
        Route::get('city/datatable', [CityController::class, 'list'])->name('city.list');
        Route::get('city/edit/{id}', [CityController::class, 'edit'])->name('city.edit');
        Route::post('city/update', [CityController::class, 'update'])->name('city.update');
        Route::post('city/delete', [CityController::class, 'delete'])->name('city.delete');
        Route::post('city/delete-bulk', [CityController::class, 'bulkDelete'])->name('city.bulkDelete');
       
        // Users
        Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users')->middleware('permission');
        Route::prefix('user')->group(function () {
            Route::post('/save', [AdminUserController::class, 'store'])->name('admin.save-user');
            Route::post('/list', [AdminUserController::class, 'list'])->name('admin.user-list');
            Route::get('/edit/{id}', [AdminUserController::class, 'edit'])->name('admin.user-edit');
            Route::post('/delete', [AdminUserController::class, 'delete'])->name('admin.user-delete');
        });

        Route::post('/flag-change-language', [LanguageController::class, 'changeLanguage']);

        // Users
        Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users')->middleware('permission');
        Route::prefix('user')->group(function () {
            Route::post('/save', [AdminUserController::class, 'store'])->name('admin.save-user');
            Route::post('/list', [AdminUserController::class, 'list'])->name('admin.user-list');
            Route::get('/edit/{id}', [AdminUserController::class, 'edit'])->name('admin.user-edit');
            Route::post('/delete', [AdminUserController::class, 'delete'])->name('admin.user-delete');
        });

        Route::get('fetch-notifications', [AdminUserController::class, 'notifications'])->name('admin.fetch-notifications');
        Route::post('mark-all-as-read', [AdminUserController::class, 'markAllAsRead'])->name('admin.markAllAsRead');
    });
    Route::post('admin/send-message', [MessageController::class, 'sendMessage'])->middleware('admin')->name('user.send-message');
    Route::post('admin/fetch-messages', [MessageController::class, 'fetchMessages'])->middleware('admin')->name('fetch-messages');

    Route::prefix('admin')->middleware('admin')->controller(MessageController::class)->group(function () {
        Route::get('messages', 'adminMessages')->name('admin.messages');
    });
});

// USER ROUTES //

Route::group(['middleware' => ['checkInstallerStatus', 'setLocaleUser']], function () {
    Route::get('/user/translations/{file}/{module}', [TranslationController::class, 'getFileTranslations'])->name('translations');
    Route::get('/', [PageController::class, 'pageBuilderApi'])->middleware('maintenance')->name('home');

    Route::middleware('maintenance')->group(function () {
        Route::get('/login', [UserLoginRegisterController::class, 'userLogin'])->name('user-login');
        Route::get('/register', [UserLoginRegisterController::class, 'userRegister'])->name('user-register');
        Route::get('/user/forgot-password', [UserLoginRegisterController::class, 'forgotPassword'])->name('user-forgot-password');
        Route::get('/user/reset-password', [UserLoginRegisterController::class, 'resetPassword'])->name('user-reset-password');
        Route::post('/user/check-current-password-reset', [UserController::class, 'checkCurrentPassword']);
        Route::post('/user/reset-password-update', [UserLoginRegisterController::class, 'resetPasswordUpdate']);

        Route::post('/user/register', [UserLoginRegisterController::class, 'register'])->name('user-registersave');
        Route::post('/user/login', [UserLoginRegisterController::class, 'login'])->name('user-logincheck');
        Route::get('/user-logout', [UserLoginRegisterController::class, 'userlogout'])->name('user.logout');
        Route::post('/otp-settings', [UserLoginRegisterController::class, 'getOtpSettings']);
        Route::post('/verify-otp', [UserLoginRegisterController::class, 'verifyOtp']);
        Route::post('/validate-email', [UserLoginRegisterController::class, 'validateEmail']);
        Route::get('/contact-us', [PageController::class, 'contactUs'])->name('contact-us');
        Route::get('/categories', [PageController::class, 'categories'])->name('categories');
        Route::post('home/fetch-categories', [PageController::class, 'fetchCategories'])->name('fetch-categories');
    });
    Route::group(['middleware' => 'customer', 'maintenance'], function () {
        //Messages
        Route::get('/seller/messages', [MessageController::class, 'sellerMessages'])->name('seller.messages');
        Route::post('seller/send-message', [MessageController::class, 'sellerSendMessage'])->name('seller.send-message');
        Route::post('seller/fetch-messages', [MessageController::class, 'sellerFetchMessages'])->name('seller.fetch-messages');
        Route::get('buyer/messages', [MessageController::class, 'buyerMessages'])->name('buyer.messages');
        Route::post('buyer/send-message', [MessageController::class, 'buyerSendMessage'])->name('buyer.send-message');
        Route::post('buyer/fetch-messages', [MessageController::class, 'buyerFetchMessages'])->name('buyer.fetch-messages');
        Route::post('user/search-users', [MessageController::class, 'searchUsers'])->name('user.search-users');
    });
    Route::post('user/add-to-favourite', [HomeController::class, 'addToFavourite'])->middleware('customer', 'maintenance')->name('user.addToFavourite');
    Route::prefix('user')->middleware(['customer', 'maintenance'])->controller(WalletController::class)->group(function () {
        Route::get('wallet', 'wallet')->name('user.wallet');
        Route::post('addwallet', 'addWallet')->name('user.addwallet');
        Route::get('wallet-list', 'walletHistoryList')->name('user.walletHistoryList');
        Route::get('paypal-payment-success-wallet', 'paypalPaymentSuccessWallet')->name('user.paypalPaymentSuccessWallet');
        Route::get('stripe-payment-success', 'stripePaymentSuccessWallet')->name('user.stripe.payment.success.wallet');
        Route::get('payment-failed', 'paymentFailed')->name('payment-failed');
    });
    //ServiceController Routes
    Route::get('gigs', [ServiceController::class, 'index'])->name('index.services');
    Route::get('service-details/{slug}', [ServiceController::class, 'serviceDetail'])->name('service.detail');
    Route::get('search-gigs', [ServiceController::class, 'searchGigs'])->name('search.gigs');

    Route::middleware(['customer', 'maintenance'])->group(function () {
        Route::get('buyer/dashboard', [UserDashboardController::class, 'buyerDashboard'])->name('buyer.dashboard');
        Route::get('seller/dashboard', [UserDashboardController::class, 'sellerDashboard'])->name('seller.dashboard');
        Route::post('seller/get-payments-sale-statistics', [UserDashboardController::class, 'getPaymentsSaleStatistics']);
        Route::post('seller/get-gigs-sale-statistics', [UserDashboardController::class, 'getGigsSalesStatistics']);

        // Settings
        Route::get('buyer/settings', [UserSettingController::class, 'userSettings'])->name('buyer.settings');
        Route::get('seller/settings', [UserSettingController::class, 'userSettings'])->name('seller.settings');
        Route::post('user/save-profile', [UserSettingController::class, 'saveProfile'])->name('user.save-profile');
        Route::post('/user/save-account-settings', [UserSettingController::class, 'saveAccountSettings'])->name('user.save-settings');
        Route::post('/user/update-password', [UserSettingController::class, 'changePassword'])->name('user.update-password');
        Route::get('/user/delete-account', [UserSettingController::class, 'deleteAccount'])->name('user.delete-account');
        Route::get('user/devices', [UserSettingController::class, 'getUserDevices'])->name('user.devices');
        Route::post('user/logout-device', [UserSettingController::class, 'logoutDevice'])->name('user.logout-device');
        // My Buyers
        Route::get('buyer/my-sellers', [UserController::class, 'mySellers'])->name('buyer.my-sellers');
        Route::get('buyer/my-sellers-list', [UserController::class, 'mySellerList'])->name('buyer.my-seller-list');
        // My Sellers
        Route::get('seller/my-buyers', [UserController::class, 'myBuyers'])->name('seller.my-buyers');
        Route::get('seller/my-buyers-list', [UserController::class, 'myBuyerList'])->name('seller.my-buyer-list');
        Route::get('buyer/profile', [UserController::class, 'userProfile'])->name('buyerprofile');
        Route::get('seller/profile', [UserController::class, 'userProfile'])->name('sellerprofile');

        Route::get('buyer/reviews', [ReviewController::class, 'buyerreviewlist'])->name('buyer.reviews');
        Route::get('seller/reviews', [ReviewController::class, 'sellerreviewlist'])->name('seller.reviews');
        Route::post('/reviews/{reviewId}/delete', [ReviewController::class, 'deleteReview'])->name('reviews.delete');
        Route::post('/reviews/{reviewId}/sellerreviewdelete', [ReviewController::class, 'deleteSellerReview'])->name('reviews.sellerdelete');

        Route::get('seller/earning', [SellerEarningController::class, 'sellerEarning'])->name('seller.earning');
        Route::get('seller/earning/list', [SellerEarningController::class, 'sellerEarningList'])->name('seller.earningList');
        Route::get('/seller/earnings/chart', [SellerEarningController::class, 'sellerEarningChartData'])->name('seller.sellerEarningChartData');
        Route::get('buyer/transaction', [SellerEarningController::class, 'buyerTransaction'])->name('buyer.buyerTransaction');
        Route::get('buyer/transaction/list', [SellerEarningController::class, 'getRecentPayments'])->name('buyer.getRecentPayments');
        // My Files
        Route::get('seller/seller-files', [FileUploadController::class, 'index'])->name('seller.file-index');
        Route::get('seller/seller-uploaded-list', [FileUploadController::class, 'uploadedList'])->name('seller.uploaded-list');
        // My Purchase
        Route::get('buyer/buyer-purchase', [OrdersController::class, 'indexBuyser'])->name('buyer.purchase-index');
        Route::get('buyer/buyer-purchase-list', [OrdersController::class, 'purchaseList'])->name('buyer.purchase-list');
        Route::post('buyer/buyer-purchase-details', [OrdersController::class, 'purchaseDetails'])->name('buyer.purchase-details');
        Route::post('buyer/buyer-purchase-delete', [OrdersController::class, 'purchaseDelate'])->name('buyer.purchase-delete');
        Route::post('buyer/seller-list', [OrdersController::class, 'sellerList'])->name('buyer.seller-list');
        // My Orders
        Route::get('seller/seller-orders', [OrdersController::class, 'indexSeller'])->name('seller.order-index');
        Route::get('seller/seller-orders-list', [OrdersController::class, 'orderList'])->name('seller.order-list');
        Route::post('seller/seller-orders-details', [OrdersController::class, 'orderDetails'])->name('seller.order-details');
        Route::post('seller/seller-orders-file', [OrdersController::class, 'orderfile'])->name('seller.order-file');
        Route::post('seller/update-order-status', [OrdersController::class, 'orderStatus'])->name('seller.order-status');
        Route::post('seller/buyer-list', [OrdersController::class, 'buyerList'])->name('seller.buyer-list');
        // My Gigs
        Route::get('seller/seller-gigs', [GigsController::class, 'indexGigs'])->name('seller.seller-gigs');
        Route::get('seller/seller-gigs-lists', [GigsController::class, 'indexGigsList'])->name('seller.seller-gigs-list');
        Route::get('seller/seller-gigs-edit/{slug}', [GigsController::class, 'indexGigsEdit'])->name('seller.seller-gigs-edit');
        Route::get('/gigs/get-addons', [GigsController::class, 'getAddons']);
        Route::get('/gigs/get-images', [GigsController::class, 'getImage']);
        Route::get('/gigs/get-faq', [GigsController::class, 'getFaq']);
        Route::post('/gigs/delete', [GigsController::class, 'deleteGigs'])->name('gigs.delete');

        Route::get('user/get-notifications', [UserController::class, 'getNotifications'])->name('user.get-notifications');
        Route::post('user/mark-all-notifications-as-read', [UserController::class, 'markAllNotificationsAsRead'])->name('user.mark-all-notifications-as-read');
        Route::post('user/delete-all-notifications', [UserController::class, 'deleteAllNotifications'])->name('user.delete-all-notifications');
        Route::post('user/delete-notification', [UserController::class, 'deleteNotification'])->name('user.delete-notification');
        Route::post('user/mark-notification-as-read', [UserController::class, 'markNotificationAsRead'])->name('user.mark-notification-as-read');

        Route::get('seller/notifications', [UserController::class, 'sellerNotifications'])->name('seller.notifications');
        Route::get('buyer/notifications', [UserController::class, 'buyerNotifications'])->name('buyer.notifications');
        Route::get('user-notifications', [UserController::class, 'userNotifications'])->name('user.notifications');
        Route::get('seller/earning', [SellerEarningController::class, 'sellerEarning'])->name('seller.earning');
        Route::get('seller/earning/list', [SellerEarningController::class, 'sellerEarningList'])->name('seller.earningList');
        // My Files
        Route::get('seller/seller-files', [FileUploadController::class, 'index'])->name('seller.file-index');
        Route::get('seller/seller-uploaded-list', [FileUploadController::class, 'uploadedList'])->name('seller.uploaded-list');
        Route::post('seller/seller-gigs-list', [FileUploadController::class, 'gigsList'])->name('seller.gigs-list');
        Route::post('order/types', [FileUploadController::class, 'orderType'])->name('order.type');
        Route::post('order/order-delete', [FileUploadController::class, 'orderDelete'])->name('order.order-delete');

        // My Purchase
        Route::get('buyer/buyer-purchase', [OrdersController::class, 'indexBuyser'])->name('buyer.purchase-index');
        Route::get('buyer/buyer-purchase-list', [OrdersController::class, 'purchaseList'])->name('buyer.purchase-list');
        Route::post('buyer/buyer-purchase-details', [OrdersController::class, 'purchaseDetails'])->name('buyer.purchase-details');
        Route::post('buyer/buyer-orders-file', [OrdersController::class, 'buyerOrderfile'])->name('buyer.order-file');
        // My Orders
        Route::get('seller/seller-orders', [OrdersController::class, 'indexSeller'])->name('seller.order-index');
        Route::get('seller/seller-orders-list', [OrdersController::class, 'orderList'])->name('seller.order-list');
        Route::post('seller/seller-orders-details', [OrdersController::class, 'orderDetails'])->name('seller.order-details');
        Route::post('seller/seller-orders-file', [OrdersController::class, 'orderfile'])->name('seller.order-file');
        // Favourites
        Route::get('buyer/favorites', [UserController::class, 'buyerfavoritelist'])->name('buyer.favorites');
        Route::post('/favorites/remove', [UserController::class, 'removeFavorite'])->name('favorites.remove');
        Route::delete('/buyer/remove-all-favorites', [UserController::class, 'removeAllFavorites'])->name('favorite.removeAll');
        // Reviews
        Route::post('user/add-review', [ReviewController::class, 'addreview'])->name('reviews.add');
        Route::post('user/add-reply-review', [ReviewController::class, 'addReply'])->name('reviews.add-reply');
    });

    Route::get('blogs', [BlogController::class, 'blogList'])->name('blogs.list');
    Route::get('blog-details/{id}', [BlogController::class, 'blogDetail'])->name('blogs.detail');
    Route::post('/blog-review', [BlogController::class, 'storeReview'])->name('blogs.review.store');
    Route::get('maintenance', [HomeController::class, 'maintenance'])->name('maintenance');
    Route::get('/pages/{slug}', [PageController::class, 'getPage'])->name('pages');
    Route::post('user/flag-change-language', [LanguageController::class,'userFlagChangeLanguage'])->name('user.flag-change-language');
    Route::post('/user/reviews-list', [ReviewController::class, 'reviewsList'])->name('reviews.list');
});

Route::get('gigs-booking-details', [GigsController::class, 'bookingdetails'])->name('index.bookingdetails');
Route::post('/gigs/delete-image', [GigsController::class, 'deleteImage'])->name('gigs.image.delete');
