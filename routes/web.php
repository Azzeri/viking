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
use App\Models\User;
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
Route::get('/about', function () {
    return inertia('About', [
        'users' => User::where('role', '!=', 'null')->orderBy('name')->limit(6)->get()->map(fn ($user) => [
            'name' => $user->name,
            'surname' => $user->surname,
            'nickname' => $user->nickname,
            'role' => $user->role,
            'photo_path' => $user->profile_photo_path
        ])
    ]);
})->name('about');

Route::get('/store', [StoreController::class, 'index'])->name('store');
Route::get('/storeItem/{id}', [StoreController::class, 'itemDetails'])->name('item.details');
Route::post('/storeRequestCreate', [StoreRequestController::class, 'store']);


//TODELETE
Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return inertia('Dashboard');
})->name('dashboard');


// Group Coordinator registration
Route::middleware('signed', 'guest')->get('/registerMember', fn (Request $request) => inertia('Auth/RegisterMember', [
    'email' => $request->email,
    'role' => $request->role
]))->name('registerMember');
Route::middleware('guest')->post('/storeMember', [UserController::class, 'storeMember']);

// Administrator and Coordinators routes
Route::middleware('adminPanel')->prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', fn () => inertia('Admin/Dashboard'))->name('dashboard');

    Route::post('/inventoryCategories/StorePhoto/{id}', [InventoryCategoryController::class, 'storePhoto']);
    Route::post('/inventoryCategories/DeletePhoto/{id}', [InventoryCategoryController::class, 'deletePhoto']);



    Route::post('/inventoryservicesfinish', [InventoryServiceController::class, 'finish']);

    Route::post('/storeCategories/StorePhoto/{id}', [StoreCategoryController::class, 'storePhoto']);
    Route::post('/storeCategories/DeletePhoto/{id}', [StoreCategoryController::class, 'deletePhoto']);

    Route::post('/storeItems/StorePhoto/{id}', [StoreItemController::class, 'storePhoto']);
    Route::post('/storeItems/DeletePhoto/{id}', [StoreItemController::class, 'deletePhoto']);

    Route::post('/storeRequests/accept/{id}', [StoreRequestController::class, 'accept']);
    Route::post('/storeRequests/finish/{id}', [StoreRequestController::class, 'finish']);


    // Event
    Route::put('/events/finish/{event}', [EventController::class, 'finish'])->name('events.finish');
    Route::put('/events/participation/{event}', [EventController::class, 'confirm_participation'])->name('events.participation');
    Route::resource('/events', EventController::class)->except(['create', 'edit']);

    // User
    Route::post('/users/generate_link', [UserController::class, 'generateLink'])->name('users.generate_link');
    Route::resource('/users', UserController::class)->except(['create', 'edit']);


    // Inventory
    Route::prefix('inventory')->name('inventory.')->group(function () {
        Route::resource('/categories', InventoryCategoryController::class)->except(['create', 'edit', 'show']);
        
        Route::post('/items/photo_store/{item}', [InventoryItemController::class, 'storePhoto'])->name('items.photo.store');
        Route::post('/items/photo_delete/{item}', [InventoryItemController::class, 'deletePhoto'])->name('items.photo.delete');
        Route::resource('/items', InventoryItemController::class)->except(['create', 'edit', 'show']);

        Route::resource('/services', InventoryServiceController::class)->except(['create', 'edit', 'show']);
    });
    
    // Store
    Route::prefix('store')->name('store.')->group(function () {
        Route::resource('/categories', StoreCategoryController::class)->except(['create', 'edit', 'show']);
        
        Route::resource('/items', StoreItemController::class)->except(['create', 'edit', 'show']);
        Route::resource('/requests', StoreRequestController::class)->except(['create', 'edit', 'show']);
    });
});
