<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Panel\WalletsController;
use App\Http\Controllers\Panel\CategoryController;
use App\Http\Controllers\Panel\CustomerController;
use App\Http\Controllers\Panel\FactorController;
use App\Http\Controllers\Panel\PanelController;
use App\Http\Controllers\Panel\PermissionController;
use App\Http\Controllers\Panel\PermissionGroupController;
use App\Http\Controllers\Panel\ProductChangeController;
use App\Http\Controllers\Panel\Inventory\ProductChanges\ProductChangeController as NewProductChangeController;
use App\Http\Controllers\Panel\Inventory\ProductChanges\ExitProductChangeContoller;
use App\Http\Controllers\Panel\Inventory\ProductChanges\EnterProductChangeController;
use App\Http\Controllers\Panel\Inventory\ProductChanges\ReturnProductChangeController;
use App\Http\Controllers\Panel\ProductController;
use App\Http\Controllers\Panel\RoleController;
use App\Http\Controllers\Panel\UserController;
use App\Http\Controllers\Panel\CostFactorController;
use App\Http\Controllers\Panel\ExitDocumentController;
use App\Http\Controllers\Panel\FactorPaymentController;
use App\Http\Controllers\Panel\PaymentsController;
use App\Http\Controllers\Panel\ProductChangeReturnController;
use Illuminate\Support\Facades\Route;

Route::get('/',function(){
    return redirect()->route('panel.dashboard');
});


Route::get('/dashboard', [HomeController::class, 'dashboard'])
    ->middleware('auth')
    ->name('dashboard');

Route::prefix('/panel')->group(function () {
    Route::name('panel.')->group(function () {
        Route::middleware(['auth'])->group(function () {

            Route::get('/', [PanelController::class, 'dashboard'])->name('dashboard');
            Route::post('/changewalletchart',[PanelController::class,'changeWalletChart'])->name('changeWalletChart');

            Route::resource('/users', UserController::class);
            Route::get('change_active/{user}', [UserController::class, 'changeActive'])->name('users.changeActive');

            Route::prefix('user/{user}')->group(function () {
                Route::name('users.')->group(function () {
                    Route::get('changeActive', [UserController::class, 'changeActive'])->name('changeActive');
                    Route::post('addRole', [UserController::class, 'addRole'])->name('addRole');
                    Route::get('removeRole/{role}', [UserController::class, 'removeRole'])->name('removeRole');
                    Route::post('addPermission', [UserController::class, 'addPermission'])->name('addPermission');
                    Route::get('revokePermission/{permission}', [UserController::class, 'revokePermission'])->name('revokePermission');
                });
            });

            

            Route::prefix('/inventory')->group(function () {
                Route::name('inventory.')->group(function () {

                    Route::resource('categories', CategoryController::class);
                    Route::resource('products', ProductController::class);

                    Route::prefix('/product-changes')->group(function () {
                        Route::name('productChanges.')->group(function () {

                            Route::get('/', [NewProductChangeController::class, 'index'])->name('index');
                            Route::delete('/delete/{product_change}', [NewProductChangeController::class, 'destroy'])->name('destroy');
                            Route::get('/{product_change}/history', [NewProductChangeController::class,'history'])->name('history');
                            Route::delete('/{product_change}/history/{history}', [NewProductChangeController::class,'deleteHistory'])->name('deleteHistory');

                            Route::resource('enter', EnterProductChangeController::class)->except('index','destory');
                            Route::post('/enter/productChange', [EnterProductChangeController::class, 'addItem'])->name('enter.addItem');
                            Route::prefix('/enter/items')->group(function () {
                            // Route::prefix('/enter/{productChange}/items')->group(function () {
                                Route::name('enter.')->group(function () {
                                    Route::get('increase/{rowId}', [EnterProductChangeController::class, 'increaseItemAmount'])->name('increaseItemAmount');
                                    Route::get('decrease/{rowId}', [EnterProductChangeController::class, 'decreaseItemAmount'])->name('decreaseItemAmount');
                                    Route::get('remove/{rowId}', [EnterProductChangeController::class, 'removeItem'])->name('removeItem');
                                });
                            });

                            Route::prefix('/enter/{productChange}/items')->group(function () {
                                Route::name('enter.')->group(function () {
                                    Route::post('add', [EnterProductChangeController::class, 'add'])->name('add');
                                    Route::get('increase/{rowId}', [EnterProductChangeController::class, 'increase'])->name('increase');
                                    Route::get('decrease/{rowId}', [EnterProductChangeController::class, 'decrease'])->name('decrease');
                                    Route::get('remove/{rowId}', [EnterProductChangeController::class, 'remove'])->name('remove');
                                });
                            });

                            Route::resource('exit', ExitProductChangeContoller::class)->except('index','destory');
                            Route::prefix('/exit/items')->group(function () {
                                    Route::name('exit.')->group(function () {
                                        Route::post('add', [ExitProductChangeContoller::class, 'addItem'])->name('add');
                                        Route::get('increase/{rowId}', [ExitProductChangeContoller::class, 'increaseItemAmount'])->name('increaseItemAmount');
                                        Route::get('decrease/{rowId}', [ExitProductChangeContoller::class, 'decreaseItemAmount'])->name('decreaseItemAmount');
                                        Route::get('remove/{rowId}', [ExitProductChangeContoller::class, 'removeItem'])->name('removeItem');
                                    });
                            });


                            Route::prefix('/exit/{productChange}/items')->group(function () {
                                Route::name('exit.')->group(function () {
                                    Route::post('/', [ExitProductChangeContoller::class, 'add'])->name('addItem');
                                    Route::get('/increase/{rowId}/', [ExitProductChangeContoller::class, 'increase'])->name('increase');
                                    Route::get('/decrease/{rowId}', [ExitProductChangeContoller::class, 'decrease'])->name('decrease');
                                    Route::get('/remove/{rowId}', [ExitProductChangeContoller::class, 'remove'])->name('remove');
                                });
                            });


                            Route::resource('return', ReturnProductChangeController::class)->except('index','destory');
                            Route::prefix('/return/items')->group(function () {
                                Route::name('return.')->group(function () {
                                    Route::post('add', [ReturnProductChangeController::class, 'add'])->name('add');
                                    Route::get('increase/{rowId}', [ReturnProductChangeController::class, 'increaseItemAmount'])->name('increaseItemAmount');
                                    Route::get('decrease/{rowId}', [ReturnProductChangeController::class, 'decreaseItemAmount'])->name('decreaseItemAmount');
                                    Route::get('remove/{rowId}', [ReturnProductChangeController::class, 'removeItem'])->name('removeItem');
                                });
                            });

                            Route::prefix('/return/{productChange}/items')->group(function () {
                                Route::name('return.')->group(function () {
                                    Route::post('/addItemList', [ReturnProductChangeController::class, 'addItemList'])->name('addItemList');
                                    Route::get('/{rowId}/increase', [ReturnProductChangeController::class, 'increase'])->name('increase');
                                    Route::get('/{rowId}/decrease', [ReturnProductChangeController::class, 'decrease'])->name('decrease');
                                    Route::get('/{rowId}/remove', [ReturnProductChangeController::class, 'remove'])->name('remove');
                                });
                            });


                            Route::prefix('/return/{productChange}/return-items')->group(function () {
                                Route::name('return.')->group(function () {
                                    Route::post('/addReturnItem', [ReturnProductChangeController::class, 'addReturnItem'])->name('addReturnItem');
                                    Route::post('/', [ReturnProductChangeController::class, 'updateReturnItem'])->name('updateReturnItem');
                                    Route::delete('/{returnItem}', [ReturnProductChangeController::class, 'removeReturnItem'])->name('removeReturnItem');
                                });
                            });

                            
                        });
                    });
                    Route::get('reports', [NewProductChangeController::class, 'report'])->name('reports');

                    
                });
            });


            Route::resource('roles', RoleController::class)->except('show');
            Route::resource('permissions', PermissionController::class)->except('show');
            Route::resource('permissionGroups', PermissionGroupController::class)->except('show');
            Route::resource('customers', CustomerController::class);
            Route::get('customer/reports', [CustomerController::class,'reports'])->name('customerReports');
            Route::resource('categories', CategoryController::class);
            Route::resource('products', ProductController::class);
            Route::resource('product_changes', ProductChangeController::class);

            
            Route::delete('product_changes/delete_history/{log}', [ProductChangeController::class , 'delete_reports'])->name('product_changes.delete_reports');

            Route::post('product_changes/getcustomer', [ProductChangeController::class , 'getCustomer'])->name('getCustomer');
            Route::get('reports', [ProductChangeController::class, 'report'])->name('reports');

            
            Route::resource('/factors', FactorController::class);
            Route::get('factors/history/{factor}', [FactorController::class , 'history'])->name('factors.history');
            Route::delete('factors/delete_history/{log}', [FactorController::class , 'delete_reports'])->name('factors.delete_reports');
            

            Route::resource('/temporary_rent ', FactorController::class);
            Route::resource('/cost_factors', CostFactorController::class)->only(['create','store']);

            //costfactor
            Route::post('/cost_factors/addItem', [CostFactorController::class, 'addItem'])->name('costFactor.addItem');
            Route::post('/cost_factors/removeItem', [CostFactorController::class, 'removeItem'])->name('costFactor.removeItem');

            Route::post('/cost_factors/addPayment', [CostFactorController::class, 'addPayment'])->name('costFactor.addPayment');
            Route::post('/cost_factors/removePayment', [CostFactorController::class, 'removePayment'])->name('costFactor.removePayment');
            
            Route::get('/cost_factors/decrease', [CostFactorController::class, 'decrease'])->name('costFactor.decrease');
            Route::get('/cost_factors/increase', [CostFactorController::class, 'increase'])->name('costFactor.increase');

            /////factor
            Route::post('/factors/addItem', [FactorController::class, 'addItem'])->name('CreateFactor.addItem');
            Route::post('/factors/removeItem', [FactorController::class, 'removeItem'])->name('CreateFactor.removeItem');

            Route::post('/factors/addPayment', [FactorController::class, 'addPayment'])->name('CreateFactor.addPayment');
            Route::post('/factors/removePayment', [FactorController::class, 'removePayment'])->name('CreateFactor.removePayment');
            
            Route::get('/factor/decrease', [FactorController::class, 'decrease'])->name('createFactor.decrease');
            Route::get('/factor/increase', [FactorController::class, 'increase'])->name('createFactor.increase');

            //editfactor
           
            
            Route::post('/factors/edit/{factor}/addItem', [FactorController::class, 'EditAddItem'])->name('EditFactor.addItem');
            Route::get('/factors/edit/{factor}/removeItem/{rowId}', [FactorController::class, 'EditRemoveItem'])->name('EditFactor.removeItem');

            Route::post('/factors/{factor}/addPayment', [FactorController::class, 'EditAddPayment'])->name('EditFactor.addPayment');
            Route::post('/factors/{factor}/EditPayment', [FactorController::class, 'EditPayment'])->name('EditFactor.editPayment');
            Route::get('/factors/{factor}/payments/{rowId}', [FactorController::class, 'EditRemovePayment'])->name('EditFactor.removePayment');
            
            
            Route::get('/factors/{factor}/edit/items/{rowId}/decrease', [FactorController::class, 'EditFactorDecrease'])->name('EditFactor.decrease');
            Route::get('/factors/{factor}/edit/items/{rowId}/increase', [FactorController::class, 'EditFactorIncrease'])->name('EditFactor.increase');



            ///
            
            Route::get('/factors/taeedPayment/{id}', [FactorPaymentController::class, 'taeedPayment'])->name('factor.taeedPayment');


            Route::get('/factors/editPayment/{id}', [FactorController::class, 'editPayment'])->name('factor.editPayment');
            Route::patch('/factors/editPayment/{id}', [FactorController::class, 'editPayment'])->name('factor.updatePaymentFactor');


            Route::post('factors/getProduct', [ProductController::class , 'getProduct'])->name('getProduct');

            Route::resource('/wallets', WalletsController::class);
            Route::get('wallets_report/reports', [WalletsController::class , 'showReport'])->name('wallets.reports');
            Route::post('wallets/reports', [WalletsController::class , 'storeReport'])->name('wallets.storeReport');
            Route::resource('manage_payments', PaymentsController::class);


            Route::get('product_changes/return/create',[ProductChangeReturnController::class , 'create'])->name('returnProductChange.create');
            Route::post('product_changes/return/store',[ProductChangeReturnController::class , 'store'])->name('returnProductChange.store');
            Route::post('product_changes/return/add', [ProductChangeReturnController::class, 'add'])->name('returnProductChange.add');
            Route::get('product_changes/return/remove', [ProductChangeReturnController::class, 'remove'])->name('returnProductChange.remove');
            Route::get('product_changes/edit/return_item', [ProductChangeReturnController::class, 'delete_item'])->name('product_change.delete_item');
            Route::post('/product_changes/return/edit/{product_change}/addItem', [ProductChangeController::class, 'returnEditProductChange'])->name('EditProductChange.returnEditProductChange');

        });
    });
});
