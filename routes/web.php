<?php

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
    return Inertia::render('About');
})->name('about');

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::middleware('adminPanel')->prefix('admin')->group(function () {
    Route::resource('/users', UserController::class, ['names' => ['index' => 'users.index']]);
});


// Route::get('/reg', function (Request $request) {
//     if (! $request->hasValidSignature()) {
//         abort(401);
//     }
//     else
//     echo 'xd';
// })->name('reg');


// Route::get('/gen', function () {
//     return URL::temporarySignedRoute('reg', now()->addMinutes(30));
// });