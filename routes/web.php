<?php

use App\Http\Controllers\CarsController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ModelsController;
use App\Http\Controllers\PublicReservationController;
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
    Route::get('/clients', [ClientsController::class, 'index'])->name('clients.index');
    Route::get('/clients/create', [ClientsController::class, 'create'])->name('clients.create');
    Route::post('/clients', [ClientsController::class, 'store'])->name('clients.store');
    Route::get('/clients/{client}', [ClientsController::class, 'show'])->name('clients.show');
    Route::get('/clients/{client}/edit', [ClientsController::class, 'edit'])->name('clients.edit');
    Route::put('/clients/{client}', [ClientsController::class, 'update'])->name('clients.update');
    Route::delete('/clients/{client}', [ClientsController::class, 'destroy'])->name('clients.destroy');    // Contracts routes
    Route::prefix('contracts')->name('contracts.')->group(function() {
        Route::get('/', [ContractController::class, 'index'])->name('index');
        Route::get('/create', [ContractController::class, 'create'])->name('create');
        Route::post('/', [ContractController::class, 'store'])->name('store');
        Route::get('/{reservation}', [ContractController::class, 'show'])->name('show');
        Route::get('/{reservation}/edit', [ContractController::class, 'edit'])->name('edit');
        Route::put('/{reservation}', [ContractController::class, 'update'])->name('update');
        Route::delete('/{reservation}', [ContractController::class, 'destroy'])->name('destroy');
    });

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
| Public Reservation Routes (French Interface)
|--------------------------------------------------------------------------
*/

// Public car browsing and reservation system
Route::get('/voitures', [PublicReservationController::class, 'index'])->name('public.cars.index');
Route::get('/voitures/{car}', [PublicReservationController::class, 'show'])->name('public.cars.show');
Route::get('/voitures/{car}/reserver', [PublicReservationController::class, 'create'])->name('public.reservations.create');
Route::post('/reservations', [PublicReservationController::class, 'store'])->name('public.reservations.store');
Route::get('/reservations/{reservation}/succes', [PublicReservationController::class, 'success'])->name('public.reservations.success');

// Keep existing routes for backward compatibility
Route::get('/cars/available', [PublicReservationController::class, 'index'])->name('cars.available');
Route::get('/cars/{car}/details', [PublicReservationController::class, 'show'])->name('cars.showDetails');

/*
|--------------------------------------------------------------------------
| Admin Reservation Management Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Admin API endpoints
    Route::post('/api/check-availability', [PublicReservationController::class, 'checkAvailability'])->name('api.check.availability');

    // Admin reservation management
    Route::get('/reservations', [\App\Http\Controllers\AdminReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/{reservation}', [\App\Http\Controllers\AdminReservationController::class, 'show'])->name('reservations.show');
    Route::patch('/reservations/{reservation}/confirm', [\App\Http\Controllers\AdminReservationController::class, 'confirm'])->name('reservations.confirm');
    Route::patch('/reservations/{reservation}/cancel', [\App\Http\Controllers\AdminReservationController::class, 'cancel'])->name('reservations.cancel');
});
