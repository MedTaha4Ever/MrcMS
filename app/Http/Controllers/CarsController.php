<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Marque;
use App\Http\Requests\StoreCarRequest;
use App\Http\Requests\UpdateCarRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CarsController extends Controller
{    public function select(): View
    {
        // Assuming Car model has a 'modele' relationship
        $cars = Car::with('modele')->get();
        return view('cars', ['cars' => $cars]);
    }

    public function addform(): View
    {
        $marques = Marque::all();
        return view('cars.addform', ['marques' => $marques]);
    }

    public function add(StoreCarRequest $request): RedirectResponse
    {
        Car::create($request->validated());
        return redirect('/admin/cars')->with('success', 'Car added successfully.');
    }

    public function editform(Car $car): View
    {
        // Eager load the modele relationship to avoid N+1 issues in the view if accessed
        // Also, the 'modele' relationship name was corrected in the Car model
        $car->load('modele'); 
        $marques = Marque::all();
        // The view expects 'cars' variable, so we pass $car as 'cars'
        // It also expects $marques.
        // If the view was expecting a collection for 'cars', it might need adjustment.
        // Based on the original code, it was $cars->get(), which returns a collection.
        // However, for an edit form of a single car, passing a single model instance is more common.
        // For now, I'll pass it as a single item in an array to maintain original view structure if it iterates.
        // Or better, pass the single $car object and adjust the view if needed.
        // Let's assume the view can handle a single $car object for 'cars'.
        // If $cars was a collection of one item: view('cars.editform', ['cars' => [$car]], ['marques' => $marques]);
        return view('cars.editform', ['car' => $car, 'marques' => $marques]);
    }

    public function edit(UpdateCarRequest $request, Car $car): RedirectResponse
    {
        // The 'id' is implicitly handled by Route Model Binding with $car
        // UpdateCarRequest should ensure 'id' is not part of validated data unless specifically needed and handled.
        $car->update($request->validated());
        return redirect('/admin/cars')->with('success', 'Car updated successfully.');
    }

    public function delete(Car $car): RedirectResponse
    {
        $car->delete();
        return redirect('/admin/cars')->with('success', 'Car deleted successfully.');
    }
}
