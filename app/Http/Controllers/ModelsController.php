<?php

namespace App\Http\Controllers;

use App\Models\Modele;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class ModelsController extends Controller
{
    /**
     * Get models for a given marque_id and return as HTML options.
     * Likely used for AJAX calls to populate dropdowns.
     */    public function getModels(Request $request): string
    {
        $validated = $request->validate([
            'marque_id' => 'required|integer|exists:marques,id',
        ]);

        $models = Modele::where('marque_id', $validated['marque_id'])->get();
        
        $html = '';
        if ($models->isEmpty()) {
            $html = '<option value="">Aucun modèle trouvé</option>';
        } else {
            foreach ($models as $model) {
                $html .= sprintf(
                    '<option value="%s">%s (%s)</option>', 
                    e($model->id), 
                    e($model->name),
                    e($model->year)
                );
            }
        }
        return $html;
    }

    /**
     * Get a specific model by model_id and return as HTML option.
     * This method's utility is a bit specific, might be for pre-selecting an option.
     */
    public function getActualModels(Request $request): string
    {
        $validated = $request->validate([
            'model_id' => 'required|integer|exists:modeles,id',
        ]);

        // Fetch the specific model. findOrFail would throw an error if not found,
        // which might be desired, or use find() and handle null.
        $model = Modele::find($validated['model_id']);
        
        $html = '';
        if ($model) {
            // If it's meant to be a single selected option, ensure it's marked as selected.
            // However, the original code just creates an option, so replicating that.
            $html .= '<option value="' . e($model->id) . '">' . e($model->name) . '</option>';
        } else {
            // Optionally return a default or empty if model not found
            // $html = '<option value="">Model not found</option>';
        }
        return $html;
    }

    /**
     * Add a new model.
     */
    public function addModel(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'marque_id' => 'required|exists:marques,id',
            'year' => 'required|string|max:255',
        ]);

        Modele::create($validated);
        return redirect()->route('admin.settings.index')->with('success', 'Model added successfully');
    }

    /**
     * Delete a model by its ID.
     */
    public function deleteModel($id): RedirectResponse
    {
        $model = Modele::findOrFail($id);
        
        // Check if model is used by any cars
        if ($model->cars()->exists()) {
            return redirect()->route('admin.settings.index')
                ->with('error', 'Cannot delete model that is used by cars');
        }

        $model->delete();
        return redirect()->route('admin.settings.index')
                ->with('success', 'Model deleted successfully');
    }
}
