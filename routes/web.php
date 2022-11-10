<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('user/{id}', [HomeController::class, 'user']);
Route::get('search', [HomeController::class, 'searchUser']);

Auth::routes();

Route::group(['middleware' => 'auth'], function(){
    // MY PROFILE
    Route::get('profile', [ProfileController::class, 'profile'])->name('my-profile');
    Route::post('profile/save', [ProfileController::class, 'saveProfile']);

    // POST
    Route::get('post', [PostController::class, 'index']); // view add post
    // Route::get('post/edit/{id}', [PostController::class, 'edit']);
    Route::post('post/save', [PostController::class, 'save']);

    // save LIKE
    Route::get('like/{id}', [PostController::class, 'likePost']);
    
    // COMMENT
    Route::post('comment/save/{id}', [CommentController::class, 'save']);

    // FOLLOW
    Route::get('follow/{id}', [FollowController::class, 'follow']);
    Route::get('unfollow/{id}', [FollowController::class, 'unfollow']);
});
