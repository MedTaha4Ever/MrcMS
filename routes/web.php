<?php

use App\Http\Controllers\CarsController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ModelsController;
use App\Http\Controllers\SettingsController;
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

// Login
Route::get('/', [LoginController::class, 'LoginView'])->name('login');
Route::post('/', [LoginController::class, 'authenticate']);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', function () {
        return view('welcome');
    })->name('dashboard');    // Cars management routes
    Route::get('/cars', [CarsController::class, 'select'])->name('cars.index');
    Route::get('/cars/add', [CarsController::class, 'addform'])->name('cars.add');
    Route::post('/cars/add', [CarsController::class, 'add'])->name('cars.store');    
    Route::get('/cars/{car}/edit', [CarsController::class, 'editform'])->name('cars.edit');
    Route::put('/cars/{car}', [CarsController::class, 'edit'])->name('cars.update');
    Route::delete('/cars/{car}', [CarsController::class, 'delete'])->name('cars.delete');

    // Clients routes
    Route::get('/clients', [ClientsController::class, 'select'])->name('clients.index');

    // Contracts routes
    Route::get('/contracts', function () {
        return view('contracts');
    })->name('contracts.index');

    // Settings routes
    Route::get('/settings', [SettingsController::class, 'getSettings'])->name('settings.index');
    
    // Marque routes
    Route::post('/settings/marque/add', [SettingsController::class, 'addMarque'])->name('settings.marque.add');
    Route::get('/settings/marque/delete/{id}', [SettingsController::class, 'delMarque'])->name('settings.marque.delete');
    
    // Models routes
    Route::get('/models', [ModelsController::class, 'getModels'])->name('models.getModels');
    Route::get('/models/actual', [ModelsController::class, 'getActualModels'])->name('models.getActualModels');
    Route::post('/settings/model/add', [ModelsController::class, 'addModel'])->name('settings.model.add');
    Route::get('/settings/model/delete/{id}', [ModelsController::class, 'deleteModel'])->name('settings.model.delete');
});

/*
|--------------------------------------------------------------------------
| Client-Facing Reservation Routes
|--------------------------------------------------------------------------
*/

// Assuming a new controller for these actions, e.g., ClientReservationController
// Route::get('/cars/available', [ClientReservationController::class, 'index'])->name('cars.available');
// Route::get('/cars/{car}/details', [ClientReservationController::class, 'showCarDetails'])->name('cars.showDetails');
// Route::post('/cars/{car}/reserve', [ClientReservationController::class, 'storeReservation'])->name('cars.reserve');
// Route::get('/my-reservations', [ClientReservationController::class, 'myReservations'])->name('reservations.mine');
// For now, let's use existing controllers if possible or create placeholders if the controller doesn't exist yet.
// Since ClientReservationController doesn't exist yet, I will comment these out for now
// and create the controller in the next step.
// For the purpose of this step (adding routes), I'll define them but they won't work until the controller is made.

// Placeholder: Define routes with a temporary controller or closure if needed for testing route definition
// For now, I will assume 'ClientReservationController' will be created.
// To make the file syntactically valid if the controller doesn't exist yet,
// I'll use a string reference that Laravel can defer resolving.
Route::get('/cars/available', [\App\Http\Controllers\ClientReservationController::class, 'index'])->name('cars.available');
Route::get('/cars/{car}/details', [\App\Http\Controllers\ClientReservationController::class, 'showCarDetails'])->name('cars.showDetails');
Route::post('/cars/{car}/reserve', [\App\Http\Controllers\ClientReservationController::class, 'storeReservation'])->name('cars.reserve');
Route::get('/my-reservations', [\App\Http\Controllers\ClientReservationController::class, 'myReservations'])->name('reservations.mine');

/*
|--------------------------------------------------------------------------
| Admin Reservation Management Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/reservations', [\App\Http\Controllers\AdminReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/{reservation}', [\App\Http\Controllers\AdminReservationController::class, 'show'])->name('reservations.show');
    Route::patch('/reservations/{reservation}/confirm', [\App\Http\Controllers\AdminReservationController::class, 'confirm'])->name('reservations.confirm');
    Route::patch('/reservations/{reservation}/cancel', [\App\Http\Controllers\AdminReservationController::class, 'cancel'])->name('reservations.cancel');
});
