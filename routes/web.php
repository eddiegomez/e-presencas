<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\ProtocolosController;
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
  return redirect()->route('login');
});

Route::middleware(['auth', 'web'])->group(function () {
  Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


  // Get all events Controller
  Route::get('/events', [EventController::class, 'index'])->name('events');

  // Create a new event Controller
  Route::post('/event', [EventController::class, 'store'])->name('event.store');

  // Get a single event Controller
  Route::get('/event/{id}', [EventController::class, 'show'])->name('event');

  // Edit Event Controller
  Route::post('/event/{id}', [EventController::class, 'update'])->name('event.update');

  // Delete Event Controller
  Route::post('/event/delete/{id}', [EventController::class, 'destroy'])->name('event.destroy');

  // Create Schedule
  Route::post('/schedule/store/{id}', [EventController::class, 'createSchedule'])->name('schedule.create');

  // Participants Controllers

  // Get all participants controller
  Route::get(
    '/participants',
    [ParticipantController::class, 'index']
  )->name('participant.index');


  // Create a new Participant Controller
  Route::post(
    '/participant/create',
    [ParticipantController::class, 'store']
  )->name('participant.store');

  // Get a single Participant Controller
  Route::get('/participant/{id}', [
    ParticipantController::class,
    'show'
  ])->name('participant.show');


  // Edit a Participant Controller
  Route::post(
    '/participant/{id}/edit',
    [ParticipantController::class, 'update']
  )->name('participant.update');

  // Delete a participant Controller
  Route::post(
    '/participant/{id}/delete',
    [ParticipantController::class, 'destroy']
  )->name('participant.destroy');


  // Invite Controllers

  // Confirmar Entrada Controllers
  Route::get(
    '/confirm/entrance/{encryptedevent}/{encryptedparticipant}',
    [InviteController::class, 'confirmEntrance']
  )->name('participant.entrance');


  // 
  Route::post(
    'confirm/entrance/{encryptedevent}/{encryptedparticipant}',
    [InviteController::class, 'confirmEntrancePost']
  )->name('confirmEntranceUpdate');


  // Create a new Invite controller
  Route::post(
    '/inviteParticipant/{id}',
    [EventController::class, 'inviteParticipant']
  )->name('inviteParticipant');

  // Delete a new Invite controller
  Route::post(
    '/invite/destroy',
    [InviteController::class, 'destroy']
  )->name('invite.destroy');

  Route::delete(
    '/removerParticipante',
    [InviteController::class, 'removerParticipante']
  )->name('removerParticipante');


  Route::middleware(
    'isAdmin'
  )->group(function () {
    // Get all staff members Controller
    Route::get(
      '/protocolos',
      [ProtocolosController::class, 'index']
    )->name('protocolos.index');

    // Create a new staff member Controller
    Route::post(
      '/protocolo/create',
      [ProtocolosController::class, 'store']
    )->name('protocolo.store');

    // Delete a Staff Member Controller
    Route::delete(
      '/protocolo/delete',
      [ProtocolosController::class, 'destroy']
    )->name('protocolo.delete');

    Route::post(
      '/protocolo/edit',
      [ProtocolosController::class, 'update']
    )->name('protocolo.edit');
  });
});
// Confirmar registro de protocolo!
Route::get(
  '/protocolo/{encryptedId}/confirmation/',
  [ProtocolosController::class, 'emailConfirmation']
)->name('protocolo.confirmation');

Route::post(
  '/protocolo/{encryptedId}/confirmation',
  [ProtocolosController::class, 'confirmRegister']
)->name('protocolo.confirmRegister');


// Confirm Presence Controller
Route::get('/confirm/presence/{encryptedevent}/{encryptedparticipant}', [InviteController::class, 'show'])->name('confirmPresenceShow');

Auth::routes();
