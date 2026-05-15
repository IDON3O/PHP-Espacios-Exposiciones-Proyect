<?php

use App\Http\Controllers\Web\EventController;
use App\Http\Controllers\Web\VenueController;
use App\Http\Controllers\Public\SpaceController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// ── Rutas públicas ──────────────────────────────────────────────────────────
Route::get('/', [SpaceController::class, 'index'])->name('home');
Route::get('/spaces/{slug}', [SpaceController::class, 'show'])
    ->where('slug', '^(?!create|edit|store|update|destroy|index)[a-zA-Z0-9\-]+$')
    ->name('spaces.show');
Route::get('/reservations/new', [SpaceController::class, 'reservationForm'])->name('reservations.form');
Route::post('/reservations', [SpaceController::class, 'storeReservation'])->name('reservations.store');
// Public reservation tracking - must be before admin routes to avoid conflict
Route::get('/reservations/{slug}', [SpaceController::class, 'showReservation'])
    ->where('slug', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}')
    ->name('reservations.show');

// ── Panel admin (con auth) ───────────────────────────────────────────────────
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])
        ->name('dashboard');

    Route::resource('venues', \App\Http\Controllers\Web\VenueController::class);
    Route::resource('events', \App\Http\Controllers\Web\EventController::class);

    // Nuevas rutas admin
    Route::resource('spaces', \App\Http\Controllers\Admin\SpaceController::class);
    Route::resource('reservations', \App\Http\Controllers\Admin\ReservationController::class)
        ->only(['index', 'show', 'destroy']);
    Route::post('reservations/{reservation}/accept', [\App\Http\Controllers\Admin\ReservationController::class, 'accept'])
        ->name('reservations.accept');
    Route::post('reservations/{reservation}/reject', [\App\Http\Controllers\Admin\ReservationController::class, 'reject'])
        ->name('reservations.reject');
    Route::post('reservations/{reservation}/cancel', [\App\Http\Controllers\Admin\ReservationController::class, 'cancel'])
        ->name('reservations.cancel');

    Route::get('calendar', [\App\Http\Controllers\Admin\CalendarController::class, 'index'])
        ->name('calendar.index');

    Route::resource('spaces.availabilities', \App\Http\Controllers\Admin\AvailabilityController::class)
        ->only(['index', 'store', 'destroy']);
    Route::resource('spaces.blocked-slots', \App\Http\Controllers\Admin\BlockedSlotController::class)
        ->only(['index', 'store', 'destroy']);
});
