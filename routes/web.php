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
Route::get('/admin/cars/models', [ModelsController::class, 'getModels'])->name('Marque.getModels')->middleware('auth');


//Clients
Route::get('/admin/clients', [ClientsController::class, 'select'])->name('clients')->middleware('auth');



Route::get('/admin/contracts', function () {
    return view('contracts');
})->middleware('auth');



// parametre
Route::get('/admin/settings', [SettingsController::class, 'getSettings'])->middleware('auth');
Route::post('/admin/settings/marque/add', [SettingsController::class, 'addMarque'])->middleware('auth');
Route::get('/admin/settings/marque/delete/{id}', [SettingsController::class, 'delMarque'])->middleware('auth');
