<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\EventSubTaskController;
use App\Http\Controllers\EventTaskController;
use App\Http\Controllers\InventoryCategoryController;
use App\Http\Controllers\InventoryItemController;
use App\Http\Controllers\InventoryServiceController;
use App\Http\Controllers\PhotoCategoryController;
use App\Http\Controllers\StoreCategoryController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\StoreItemController;
use App\Http\Controllers\StoreRequestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SitePostController;
use App\Models\EventTask;
use App\Models\Post;
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

Route::get('/posts', [SitePostController::class, '__invoke'])->name('posts');

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
    // Dashboard
    Route::get('dashboard', fn () => inertia('Admin/Dashboard'))->name('dashboard');

    // Event
    Route::put('events/finish/{event}', [EventController::class, 'finish'])->name('events.finish');
    Route::put('events/participation/{event}', [EventController::class, 'confirm_participation'])->name('events.participation');
    Route::get('events/task_manager/{event}', [EventController::class, 'taskManager'])->name('events.task_manager');
    Route::resource('/events', EventController::class)->except(['create', 'edit']);

    // EventTask
    Route::put('event_tasks/change_state/{eventTask}/{eventTaskState}', [EventTaskController::class, 'change_state'])->name('event_tasks.change_state');
    Route::resource('/event_tasks', EventTaskController::class)->only(['store', 'update', 'destroy']);

    // EventSubTask
    Route::put('event_sub_tasks/finish/{event_sub_task}', [EventSubTaskController::class, 'finish'])->name('event_sub_tasks.finish');
    Route::resource('/event_sub_tasks', EventSubTaskController::class)->only(['store', 'update', 'destroy']);

    // InventoryCategory
    Route::resource('/inventory_categories', InventoryCategoryController::class)->except(['create', 'edit', 'show']);

    // InventoryItem
    Route::resource('/inventory_items', InventoryItemController::class)->except(['create', 'edit', 'show']);

    // InventoryService
    Route::put('/inventory_services/finish/{inventory_service}', [InventoryServiceController::class, 'finish'])->name('inventory_services.finish');
    Route::put('/inventory_services/assign/{inventory_service}', [InventoryServiceController::class, 'assign_auth'])->name('inventory_services.assign');
    Route::resource('/inventory_services', InventoryServiceController::class)->except(['create', 'edit', 'show']);

    // PhotoCategory
    Route::resource('/photo_categories', PhotoCategoryController::class)->except(['create', 'edit', 'show']);

    // Post
    Route::resource('/posts', PostController::class)->except(['create', 'edit']);

    // StoreCategory
    Route::resource('/store_categories', StoreCategoryController::class)->except(['create', 'edit', 'show']);

    // StoreItem
    Route::resource('/store_items', StoreItemController::class)->except(['create', 'edit', 'show']);

    // StoreRequest
    Route::put('/store_requests/accept/{store_request}', [StoreRequestController::class, 'accept'])->name('store_requests.accept');
    Route::put('/store_requests/finish/{store_request}', [StoreRequestController::class, 'finish'])->name('store_requests.finish');
    Route::resource('/store_requests', StoreRequestController::class)->except(['create', 'edit', 'show']);

    // User
    Route::post('/users/generate_link', [UserController::class, 'generateLink'])->name('users.generate_link');
    Route::resource('/users', UserController::class)->except(['create', 'edit']);
});
