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
Route::get('/logout', [LoginController::class, 'logout']);

// voitures
Route::get('/admin/cars', [CarsController::class, 'select'])->middleware('auth')->name('voitures');

// ajout de voiture
Route::get('/admin/cars/add', [CarsController::class, 'addform'])->middleware('auth');
Route::post('/admin/cars/add', [CarsController::class, 'add'])->middleware('auth');

// modification de voiture
Route::get('/admin/cars/edit/{id}', [CarsController::class, 'editform'])->middleware('auth');
Route::post('/admin/cars/edit', [CarsController::class, 'edit'])->middleware('auth');

// suppression de voiture
Route::get('/admin/cars/delete/{id}', [CarsController::class, 'delete'])->middleware('auth');

// liste des modele sous formes <option>
Route::get('/getModels', [ModelsController::class, 'getModels'])->name('Marque.getModels')->middleware('auth');
Route::get('/getActualModels', [ModelsController::class, 'getActualModels'])->name('Marque.getActualModels')->middleware('auth');


//Clients
Route::get('/admin/clients', [ClientsController::class, 'select'])->name('clients')->middleware('auth');


//contracts
Route::get('/admin/contracts', function () {
    return view('contracts');
})->middleware('auth');

//dashboard
Route::get('/admin', function () {
    return view('welcome');
})->middleware('auth');

// parametre
Route::get('/admin/settings', [SettingsController::class, 'getSettings'])->middleware('auth');
Route::post('/admin/settings/marque/add', [SettingsController::class, 'addMarque'])->middleware('auth');
Route::get('/admin/settings/marque/delete/{id}', [SettingsController::class, 'delMarque'])->middleware('auth');

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
