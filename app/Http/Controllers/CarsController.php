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
    }    public function add(StoreCarRequest $request): RedirectResponse
    {
        Car::create($request->validated());
        return redirect()->route('admin.cars.index')->with('success', 'Car added successfully.');
    }    public function editform(Car $car): View
    {
        // Eager load the modele relationship and its marque
        $car->load('modele.marque');
        $marques = Marque::orderBy('name')->get();
        return view('cars.editform', [
            'car' => $car,
            'marques' => $marques
        ]);
    }    public function edit(UpdateCarRequest $request, Car $car): RedirectResponse
    {
        $validated = $request->validated();
        
        // Update the car with validated data
        $car->update([
            'mat' => $validated['mat'],
            'modele_id' => $validated['modele_id'],
            'dpc' => $validated['dpc'],
            'km' => $validated['km'],
        ]);

        return redirect()->route('admin.cars.index')->with('success', 'Car updated successfully.');
    }    public function delete(Car $car): RedirectResponse
    {
        $car->delete();
        return redirect()->route('admin.cars.index')->with('success', 'Car deleted successfully.');
    }
}
