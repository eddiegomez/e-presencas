<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\ParticipantController;
use App\Models\Participant;
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

Route::middleware(['auth', 'web'])->group(function () {
  Route::get('/events', [EventController::class, 'index'])->name('events');
  // Route::resource('event', EventController::class);
  Route::post('/event', [EventController::class, 'store'])->name('event.store');
  Route::get('/event/{id}', [EventController::class, 'show'])->name('event');
  Route::post('/event/{id}', [EventController::class, 'update'])->name('event.update');
  Route::post('/event/delete/{id}', [EventController::class, 'destroy'])->name('event.destroy');

  Route::post('/schedule/store/{id}', [EventController::class, 'createSchedule'])->name('schedule.create');

  // Participants Controllers
  Route::get(
    '/participants',
    [ParticipantController::class, 'index']
  )->name('participant.index');

  Route::post(
    '/participant/create',
    [ParticipantController::class, 'store']
  )->name('participant.store');

  Route::get('/participant/{id}', [ParticipantController::class, 'show'])->name('participant.show');
  Route::post(
    '/participant/{id}/edit',
    [ParticipantController::class, 'update']
  )->name('participant.update');
  Route::post(
    '/participant/{id}/delete',
    [ParticipantController::class, 'destroy']
  )->name('participant.destroy');


  // Invite Controllers
  Route::get(
    '/confirm/entrance/{encryptedevent}/{encryptedparticipant}',
    [InviteController::class, 'confirmEntrance']
  )->name('participant.entrance');

  Route::post(
    'confirm/entrance/{encryptedevent}/{encryptedparticipant}',
    [InviteController::class, 'confirmEntrancePost']
  )->name('confirmEntranceUpdate');

  Route::post(
    '/inviteParticipant/{id}',
    [EventController::class, 'inviteParticipant']
  )->name('inviteParticipant');
  
  Route::post('/invite/destroy', [InviteController::class, 'destroy'])->name('invite.destroy');

  Route::delete('/removerParticipante', [InviteController::class, 'removerParticipante'])->name('removerParticipante');


  // Confirm Presence Controller
  // Route::post('/confirm/{encryptedevent}/{encryptedparticipant}', [InviteController::class, 'confirmPresence'])->name('confirmPresence');
});

Route::get('/confirm/presence/{encryptedevent}/{encryptedparticipant}', [InviteController::class, 'show'])->name('confirmPresenceShow');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
