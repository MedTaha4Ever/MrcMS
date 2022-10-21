<?php

namespace App\Http\Controllers;

use App\Models\Modele;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModelsController extends Controller
{
    //
    public function getModels(Request $request)
    {
        # code...
        $models = Modele::where('marque_id', $request->marque_id)->get();
        $html = '';
        foreach ($models as $model) {
            $html .= '<option value="' . $model->id . '">' . $model->name . '</option>';
        }
        return $html;
    }

    public function getActualModels(Request $request)
    {
        # code..
        $models = Modele::where('id', $request->model_id)->get();
        $html = '';
        foreach ($models as $model) {
            $html .= '<option value="' . $model->id . '">' . $model->name . '</option>';
        }
        return $html;
    }
}
