<?php

namespace App\Http\Controllers;

use App\Models\Modele;
use Illuminate\Http\Request;
use Illuminate\Http\Response; // Consider for explicit HTML response, though not strictly necessary

class ModelsController extends Controller
{
    /**
     * Get models for a given marque_id and return as HTML options.
     * Likely used for AJAX calls to populate dropdowns.
     */
    public function getModels(Request $request): string
    {
        $validated = $request->validate([
            'marque_id' => 'required|integer|exists:marques,id',
        ]);

        $models = Modele::where('marque_id', $validated['marque_id'])->get();
        
        $html = '';
        if ($models->isEmpty()) {
            // Optionally return a default option or an empty string
            // $html = '<option value="">No models found</option>';
        } else {
            foreach ($models as $model) {
                $html .= '<option value="' . e($model->id) . '">' . e($model->name) . '</option>';
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
}
