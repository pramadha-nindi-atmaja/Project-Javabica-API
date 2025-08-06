<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\LocationStoreController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderDeliveryController;
use App\Http\Controllers\OrderInvoiceController;
use App\Http\Controllers\OrderProductController;
use App\Http\Controllers\PaymentMidtransCallbackController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductCollectionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\ProductPriceController;
use App\Http\Controllers\ProductVariantController;
use App\Http\Controllers\Publics\ShowPublicCategoryController;
use App\Http\Controllers\Publics\ShowPublicCollectionController;
use App\Http\Controllers\Publics\ShowPublicProductController;
use App\Http\Controllers\Publics\ShowPublicVoucherController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\ShippingAddressController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\TaxonomyController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\Publics\ShowPublicBlogsController;
use App\Http\Controllers\Publics\ShowPublicCategoryBlogController;
use App\Http\Controllers\BlogCategoryController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\HistoryVoucherController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => '/v1'], function () {

    Route::group(['prefix' => '/publics'], function () {

         Route::get('category/show', [ShowPublicCategoryController::class, 'show']);
         Route::get('product/show', [ShowPublicProductController::class, 'show']);
         
         Route::get('collection/show', [ShowPublicCollectionController::class, 'show']);
         Route::post('cart/create', [CartController::class, 'create']);
         Route::get('blog/show', [ShowPublicBlogsController::class, 'show']);
         Route::get('blog/hot', [ShowPublicBlogsController::class, 'hot']);
         Route::get('category-blog/show', [ShowPublicCategoryBlogController::class, 'show']);
     
      
        // Route::group(['prefix' => '/invoice'], function () {
        //     Route::get('show', [OrderInvoiceController::class, 'show']);
        // });
        // Route::group(['prefix' => '/order-delivery'], function () {
        //     Route::get('show', [OrderDeliveryController::class, 'show']);
        // });

        Route::group(['prefix' => '/shipping'], function () {
            Route::get('province', [ShippingController::class, 'province']);
            Route::get('city', [ShippingController::class, 'city']);
            Route::get('cost', [ShippingController::class, 'cost']);
        });

        Route::group(['prefix' => '/payment'], function () {
            Route::post('midtrans-callback', [PaymentMidtransCallbackController::class, 'create']);
           
        });

        // Route::group(['prefix' => '/captcha'], function () {
        //     Route::post('validate', [PaymentMidtransCallbackController::class, 'create']);
           
        // });

        Route::group(['prefix' => '/auth'], function () {

            Route::group(['middleware' => ['throttle:authentication']], function () {

                Route::post('login', [AuthController::class, 'login']);
                Route::post('register', [UsersController::class, 'create']);
                Route::post('reset-password', [AuthController::class, 'resetPassword']);
                Route::post('reset-new-password', [AuthController::class, 'resetNewPassword']);
                Route::post('logout', [AuthController::class, 'logout']);
            });
        });


        Route::group(['prefix' => '/store'], function () {

            Route::get('show', [LocationStoreController::class, 'show']);
            
        });

        Route::group(['prefix' => '/province'], function () {

            Route::get('show', [LocationStoreController::class, 'province']);
            
        });

        Route::group(['prefix' => '/blog-category'], function () {
            Route::get('show', [BlogCategoryController::class, 'show']);
            Route::post('create', [BlogCategoryController::class, 'create']);
            Route::post('update', [BlogCategoryController::class, 'update']);
            Route::delete('destroy', [BlogCategoryController::class, 'delete']);
        });
        
        Route::group(['prefix' => '/blog'], function () {
            Route::get('show', [BlogController::class, 'show']);
            Route::post('create', [BlogController::class, 'create']);
            Route::post('update', [BlogController::class, 'update']);
            Route::delete('destroy', [BlogController::class, 'delete']);
        });

    });
    

    Route::group(['prefix' => '/private', 'middleware' => ['auth:sanctum', 'abilities:api_access']], function () {

        Route::post('account-verification-validate', [AuthController::class, 'validateVerificationAccount']);
        Route::post('account-verification-email', [AuthController::class, 'AccountVerificationEmail'])->middleware('throttle:account-email-verification');

        Route::get('voucher/use', [ShowPublicVoucherController::class, 'show']);

        Route::group(['prefix' => '/my-profile'], function () {

            Route::put('change-password', [AuthController::class, 'changePassword']);
            Route::post('show-profile', [AuthController::class, 'profile']);
            Route::put('update-profile', [AuthController::class, 'updateProfile']);
            
        });

        Route::group(['prefix' => '/store'], function () {

            Route::get('show', [LocationStoreController::class, 'show']);
            Route::post('update', [LocationStoreController::class, 'update']);
            Route::post('create', [LocationStoreController::class, 'create']);
            Route::delete('destroy', [LocationStoreController::class, 'delete']);
        
        });

        Route::group(['middleware' => ['verified']], function () {

            Route::group(['prefix' => '/users'], function () {

                Route::get('show', [UsersController::class, 'show']);
                Route::put('update', [UsersController::class, 'update']);
                Route::delete('destroy', [UsersController::class, 'destroy']);

                Route::group(['prefix' => '/shipping-address'], function () {

                    Route::get('show', [ShippingAddressController::class, 'show']);
                    Route::put('update', [ShippingAddressController::class, 'update']);
                    Route::post('destroy', [ShippingAddressController::class, 'destroy']);
                    Route::post('create', [ShippingAddressController::class, 'create']);

                });
            });

            Route::group(['prefix' => '/roles-permission'], function () {

                //give,remove,show role USER
                Route::post('assign-role-user', [RolePermissionController::class, 'assignRoleUser']);
                Route::post('remove-role-user', [RolePermissionController::class, 'removeRoleUser']);
                Route::get('show-users-roles', [RolePermissionController::class, 'showUserRoles']);

                //give and remove direct permission USER
                Route::post('assign-permission-user', [RolePermissionController::class, 'assignPermissionUser']);
                Route::post('remove-permission-user', [RolePermissionController::class, 'removePermissionUser']);

                //show permission
                Route::get('show-permission', [RolePermissionController::class, 'showPermission']);
                Route::get('show-list-permission-from-role', [RolePermissionController::class, 'showPermissionFromRole']);
                
                // tidak di proteksi dengan roles and permission karena butuh melakukan pengecekan user tersebut memiliki permission apa saja 14 nov 2022
                Route::get('show-users-permission', [RolePermissionController::class, 'showUsersPermission']);
                //===========================================================
                
                //show roles
                Route::get('show-role', [RolePermissionController::class, 'showRole']); 
                
                //assign permission to role
                Route::post('give-or-remove-permission-to-role', [RolePermissionController::class, 'giveOrRemovePermissionToRole']);

                //roles
                //tidak usah di gunakan apabila ingin create role dari sistem 14 nov 2022
                //Route::post('create-role', [RolePermissionController::class, 'createRole']);
                //===========================================================
                

            });

            Route::group(['prefix' => '/taxonomy'], function () {

                Route::get('show', [TaxonomyController::class, 'show']);
                Route::post('create', [TaxonomyController::class, 'create']);
                Route::post('update', [TaxonomyController::class, 'update']);
                Route::delete('destroy', [TaxonomyController::class, 'destroy']);

                Route::group(['prefix' => '/type'], function () {
                    Route::get('show', [TaxonomyController::class, 'show_type']);
                });
            });

            Route::group(['prefix' => '/order'], function () {

                Route::get('show', [OrderController::class, 'show']);
                Route::post('create', [OrderController::class, 'create']);
                Route::put('update', [OrderController::class, 'update']);
                Route::delete('destroy', [OrderController::class, 'destroy']);

                Route::group(['prefix' => '/product'], function () {

                    Route::get('show', [OrderProductController::class, 'show']);
                    Route::post('create', [OrderProductController::class, 'create']);
                    Route::put('update', [OrderProductController::class, 'update']);
                    Route::delete('destroy', [OrderProductController::class, 'destroy']);
                });

                Route::group(['prefix' => '/invoice'], function () {
                    Route::get('show', [OrderInvoiceController::class, 'show']);
                });
                Route::group(['prefix' => '/order-delivery'], function () {
                    Route::get('show', [OrderDeliveryController::class, 'show']);
                });
            });
            
            Route::group(['prefix' => '/machine'], function () {
                Route::get('show', [MachineController::class, 'show']);
                Route::post('create', [MachineController::class, 'create']);
                Route::post('update', [MachineController::class, 'update']);
                Route::delete('destroy', [MachineController::class, 'delete']);
                Route::get('show-all-machine', [MachineController::class, 'show_all_machine']);
            });

            Route::group(['prefix' => '/voucher'], function () {
                Route::get('show', [VoucherController::class, 'show']);
                Route::post('create', [VoucherController::class, 'create']);
                Route::post('update', [VoucherController::class, 'update']);
                Route::delete('destroy', [VoucherController::class, 'destroy']);
            });

            Route::group(['prefix' => '/history-voucher'], function () {
                Route::get('show', [HistoryVoucherController::class, 'show']);
            });

            Route::group(['prefix' => '/checkout'], function () {
                Route::post('create', [CheckoutController::class, 'create']); 
            });

            Route::group(['prefix' => '/product'], function () {

                Route::get('show', [ProductController::class, 'show']);
                Route::post('create', [ProductController::class, 'create']);
                Route::put('update', [ProductController::class, 'update']);
                Route::delete('destroy', [ProductController::class, 'destroy']);

                Route::group(['prefix' => '/collection'], function () {

                    Route::get('show', [ProductCollectionController::class, 'show']);
                    Route::post('upsert', [ProductCollectionController::class, 'upsert']);
                    Route::delete('destroy', [ProductCollectionController::class, 'destroy']);
                });

                Route::group(['prefix' => '/images'], function () {

                    Route::get('show', [ProductImageController::class, 'show']);
                    Route::post('upsert', [ProductImageController::class, 'upsert']);
                    Route::delete('destroy', [ProductImageController::class, 'destroy']);
                });

                Route::group(['prefix' => '/category'], function () {

                    Route::get('show', [ProductCategoryController::class, 'show']);
                    Route::post('upsert', [ProductCategoryController::class, 'upsert']);
                    Route::delete('destroy', [ProductCategoryController::class, 'destroy']);
                });

                Route::group(['prefix' => '/variants'], function () {

                    Route::get('show', [ProductVariantController::class, 'show']);
                    Route::post('upsert', [ProductVariantController::class, 'upsert']);
                    Route::delete('destroy', [ProductVariantController::class, 'destroy']);
                });

                Route::group(['prefix' => '/blog'], function () {

                    Route::post('create', [BlogController::class, 'create']);
                   
                });

                

                Route::group(['prefix' => '/price'], function () {

                    Route::get('show', [ProductPriceController::class, 'show']);
                    Route::post('upsert', [ProductPriceController::class, 'upsert']);
                    Route::delete('destroy', [ProductPriceController::class, 'destroy']);
                });

            });
        });
    });
});
