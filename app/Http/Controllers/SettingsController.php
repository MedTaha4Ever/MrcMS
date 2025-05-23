<?php

namespace App\Http\Controllers;

use App\Models\Marque;
use App\Models\Modele; // Kept for the getSettings method, though not ideal without relation
use App\Http\Requests\StoreMarqueRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
// Removed: use Illuminate\Http\Request; // Replaced by FormRequest or not needed
// Removed: use Illuminate\Support\Facades\DB; // Not used

class SettingsController extends Controller
{
    public function getSettings(): View
    {
        // Fetching all marques, and then all modeles separately.
        // If modeles are displayed grouped by marque, consider Marque::with('modeles')->get()
        // and adjust the view accordingly. For now, maintaining current data structure.
        $marques = Marque::all(); 
        $modeles = Modele::all(); // Changed variable name from $models to $modeles for clarity
        return view('settings', ['marques' => $marques, 'models' => $modeles]); // Kept 'models' key for view compatibility
    }

    public function addMarque(StoreMarqueRequest $request): RedirectResponse
    {
        Marque::create($request->validated());
        return redirect('/admin/settings')->with('success', 'Marque added successfully.');
    }

    public function delMarque(Marque $marque): RedirectResponse
    {
        // Add check if marque has associated models before deleting if necessary
        // For example: if ($marque->modeles()->exists()) { ... return error ... }
        $marque->delete();
        return redirect('/admin/settings')->with('success', 'Marque deleted successfully.');
    }
}
