<?php

use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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
  return view('welcome');
});

Route::middleware(['auth'])->group(function () {
  Route::get('/events', [EventController::class, 'index'])->name('events');
  // Route::resource('event', EventController::class);
  Route::post('/event', [EventController::class, 'store'])->name('event.store');
  Route::get('/event/{id}', [EventController::class, 'show'])->name('event');
  Route::post('/event/{id}', [EventController::class, 'update'])->name('event.update');
  Route::post('/event/delete/{id}', [EventController::class, 'destroy'])->name('event.destroy');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
