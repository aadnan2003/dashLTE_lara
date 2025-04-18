<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AgeCheck;
use App\Mail\UserWelcomeEmail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Models\Admin;
// Route::get('/test-mail', function () {
//     $admin = Admin::first();
//     $admin->sendEmailVerificationNotification();
// });

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

Route::prefix('cms/admin')->middleware('guest:admin')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('auth.login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [ResetPasswordController::class, 'requestPasswordRest'])->name('password.request');
    Route::post('/forgot-password', [ResetPasswordController::class, 'sendResetEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'resetPassword'])->name('password.reset');
    Route::post('reset-password', [ResetPasswordController::class, 'updatePassword'])->name('password.update');
});

Route::prefix('email')->middleware('auth:admin')->group(function () {
    //verification.notice
    Route::get('verify', [EmailVerificationController::class, 'notice'])->name('verification.notice');
    Route::get('verify/send', [EmailVerificationController::class, 'send'])->middleware('throttle:6,2')->name('verification.send');
    Route::get('verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify');



});

Route::prefix('cms/admin')->middleware('auth:admin', 'verified')->group(function () {
    Route::view('/', 'cms.starter')->name('cms.home');
    Route::resource('users', UserController::class);
    Route::resource('categories', CategoryController::class);
    Route::get('logout', [AuthController::class, 'logout'])->name('auth.logout')->withoutMiddleware('verified');
});

// Route::prefix('cms/admin')->middleware('auth:admin')->group(function () {
//     Route::get('logout', [AuthController::class, 'logout'])->name('auth.logout');
// });

Route::get('email', function () {
    $user = User::first();
    // return new UserWelcomeEmail($user);
    Mail::to($user)->send(new UserWelcomeEmail($user));
});

// Route::prefix('cms/admin')->group(function () {
//     Route::view('/', 'cms.starter');

//     Route::get('/users', [UserController::class, 'index'])->name('users.index');

//     Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
//     Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');

//     Route::post('/users', [UserController::class, 'store'])->name('users.store');

//     Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
//     Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');

//     Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
// });

// Route::get('/news', function () {
//     echo "News Content - Success";
// })->middleware('age:18');

// Route::middleware('age')->get('/news', function () {
//     echo "News Content - Success";
// });

// Route::middleware(AgeCheck::class)->get('/news', function () {
//     echo "News Content - Success";
// });

// Route::get('/news', function () {
//     echo "News Content - Success";
// });
