<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModelsController extends Controller
{
    //
    public function getModels(Request $request)
    {
        # code...
        $models = DB::table('models')->where('marque_id', $request->marque_id)->get();
        $html = '';
        foreach ($models as $model) {
            $html .= '<option value="' . $model->id . '">' . $model->name . '</option>';
        }
        return $html;
    }
}
