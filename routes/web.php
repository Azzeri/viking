<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\InventoryCategoryController;
use App\Http\Controllers\InventoryItemController;
use App\Http\Controllers\InventoryServiceController;
use App\Http\Controllers\StoreCategoryController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\StoreItemController;
use App\Http\Controllers\StoreRequestController;
use App\Http\Controllers\UserController;
use App\Models\StoreCategory;
use App\Models\StoreItem;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect(route('about'));
});

/* GENERAL ROUTES */
Route::get('/about', fn () => inertia('About'))->name('about');
Route::get('/store', [StoreController::class, 'index'])->name('store');
Route::get('/storeItem/{id}', [StoreController::class, 'itemDetails'])->name('item.details');
Route::post('/storeRequestCreate', [StoreRequestController::class, 'store']);


//TODELETE
Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return inertia('Dashboard');
})->name('dashboard');

Route::middleware('adminPanel')->prefix('admin')->name('admin.')->group(function () {
    Route::post('/generateRegistrationLink', [UserController::class, 'generateRegistrationLink'])->name('generateRegistrationLink');

    Route::get('dashboard', fn () => inertia('Admin/Dashboard'))->name('dashboard');

    Route::resource('/users', UserController::class, ['names' => ['index' => 'users.index']]);
    Route::resource('/events', EventController::class, ['names' => ['index' => 'events.index']]);

    Route::resource('/inventorycategories', InventoryCategoryController::class, ['names' => ['index' => 'inventory.category.index']]);
    Route::post('/inventoryCategories/StorePhoto/{id}', [InventoryCategoryController::class, 'storePhoto']);
    Route::post('/inventoryCategories/DeletePhoto/{id}', [InventoryCategoryController::class, 'deletePhoto']);

    Route::resource('/inventoryitems', InventoryItemController::class, ['names' => ['index' => 'inventory.items.index']]);
    Route::post('/inventoryItems/StorePhoto/{id}', [InventoryItemController::class, 'storePhoto']);
    Route::post('/inventoryItems/DeletePhoto/{id}', [InventoryItemController::class, 'deletePhoto']);

    Route::resource('/inventoryservices', InventoryServiceController::class, ['names' => ['index' => 'inventory.services.index']]);
    Route::post('/inventoryservicesfinish', [InventoryServiceController::class, 'finish']);

    Route::resource('/storecategories', StoreCategoryController::class, ['names' => ['index' => 'store.category.index']]);
    Route::post('/storeCategories/StorePhoto/{id}', [StoreCategoryController::class, 'storePhoto']);
    Route::post('/storeCategories/DeletePhoto/{id}', [StoreCategoryController::class, 'deletePhoto']);

    Route::resource('/storeitems', StoreItemController::class, ['names' => ['index' => 'store.items.index']]);
    Route::post('/storeItems/StorePhoto/{id}', [StoreItemController::class, 'storePhoto']);
    Route::post('/storeItems/DeletePhoto/{id}', [StoreItemController::class, 'deletePhoto']);

    Route::resource('/storerequests', StoreRequestController::class, ['names' => ['index' => 'store.requests.index']]);
    Route::post('/storeRequests/accept/{id}', [StoreRequestController::class, 'accept']);
    Route::post('/storeRequests/finish/{id}', [StoreRequestController::class, 'finish']);
});


Route::middleware('signed', 'guest')->get('/registerMember', fn (Request $request) => inertia('Auth/RegisterMember', [
    'email' => $request->email,
    'role' => $request->role
]))->name('registerMember');

Route::middleware('guest')->post('/storeMember', [UserController::class, 'storeMember']);
