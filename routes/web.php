<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfilesController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FollowsController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

// Route::get('/home', [App\Http\Controllers\ProfilesController::class, 'index'])->name('home');

Route::post('follow/{user}', [FollowsController::class, 'store']);

Route::get('/', [PostController::class, 'index']);
Route::get('/p/create', 'App\Http\Controllers\PostController@create');
Route::get('/p/{post}', 'App\Http\Controllers\PostController@show');
Route::post('/p', 'App\Http\Controllers\PostController@store');

Route::get('/profile/{user}', [ProfilesController::class, 'index'])->name('profile.show'); // This is laravel 8 syntax
Route::get('/profile/{user}/edit', [ProfilesController::class, 'edit'])->name('profile.edit');
Route::patch('/profile/{user}', [ProfilesController::class, 'update'])->name('profile.update');
// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');