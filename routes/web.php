<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventSubTaskController;
use App\Http\Controllers\EventTaskController;
use App\Http\Controllers\InventoryCategoryController;
use App\Http\Controllers\InventoryItemController;
use App\Http\Controllers\InventoryServiceController;
use App\Http\Controllers\FrontSite\AboutController;
use App\Http\Controllers\FrontSite\EventsController;
use App\Http\Controllers\FrontSite\GalleryController;
use App\Http\Controllers\FrontSite\NewsController;
use App\Http\Controllers\FrontSite\StoreController;
use App\Http\Controllers\PhotoCategoryController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\StoreCategoryController;
use App\Http\Controllers\StoreItemController;
use App\Http\Controllers\StoreRequestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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

// Home redirect
Route::get('/', function () {
    return redirect(route('about.index'));
})->name('home');

Route::get('/dashboard', function () {
    return redirect(route('about.index'));
})->name('dashboard');

// About
Route::get('/about', [AboutController::class, 'index'])->name('about.index');

// Events
Route::get('/events', [EventsController::class, 'index'])->name('events.index');

// Gallery
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');

// Store
Route::get('/store', [StoreController::class, 'index'])->name('store.index');
Route::get('/store/{store_item}', [StoreController::class, 'show'])->name('store.show');
Route::post('/store', [StoreRequestController::class, 'store'])->name('store.store');

// News
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{post}', [NewsController::class, 'show'])->name('news.show');


// Group Coordinator registration
Route::middleware('signed', 'guest')->get('/registerMember', fn (Request $request) => inertia('Auth/RegisterMember', [
    'email' => $request->email,
    'role' => $request->role
]))->name('registerMember');
Route::middleware('guest')->post('/storeMember', [UserController::class, 'storeMember']);

// Administrator and Coordinators routes
Route::middleware('adminPanel')->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('dashboard', [AdminDashboardController::class, '__invoke'])->name('dashboard');

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
    Route::resource('/inventory_categories', InventoryCategoryController::class)->except(['create', 'edit', 'show'])->parameters(['inventory_categories' => 'category']);

    // InventoryItem
    Route::resource('/inventory_items', InventoryItemController::class)->except(['create', 'edit', 'show']);

    // InventoryService
    Route::put('/inventory_services/finish/{inventory_service}', [InventoryServiceController::class, 'finish'])->name('inventory_services.finish');
    Route::put('/inventory_services/assign/{inventory_service}', [InventoryServiceController::class, 'assign_auth'])->name('inventory_services.assign');
    Route::resource('/inventory_services', InventoryServiceController::class)->except(['create', 'edit', 'show']);

    //Photo
    Route::resource('/photos', PhotoController::class)->except(['create', 'edit', 'show', 'update']);

    // PhotoCategory
    Route::resource('/photo_categories', PhotoCategoryController::class)->except(['create', 'edit', 'show'])->parameters(['photo_categories' => 'category']);

    // Post
    Route::resource('/posts', PostController::class)->except(['create', 'edit']);

    // StoreCategory
    Route::resource('/store_categories', StoreCategoryController::class)->except(['create', 'edit', 'show'])->parameters(['store_categories' => 'category']);
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
