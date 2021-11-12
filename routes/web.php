<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\InventoryCategoryController;
use App\Http\Controllers\UserController;
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

Route::get('/about', function () {
    return inertia('About');
})->name('about');

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

});


Route::middleware('signed', 'guest')->get('/registerMember', fn (Request $request) => inertia('Auth/RegisterMember', [
    'email' => $request->email,
    'role' => $request->role
]))->name('registerMember');

Route::middleware('guest')->post('/storeMember', [UserController::class, 'storeMember']);