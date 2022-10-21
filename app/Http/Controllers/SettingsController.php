<?php

namespace App\Http\Controllers;

use App\Models\Marque;
use App\Models\Modele;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    //
    public function getSettings()
    {
        $marques = Marque::get();
        $models = Modele::get();
        return view('settings', ['marques' => $marques, 'models' => $models]);
    }

    public function addMarque(Request $req)
    {
        $arr = $req->toArray();
        array_shift($arr);
        $marques = Marque::insert($arr);
        return redirect('/admin/settings');
    }

    public function delMarque($id)
    {
        $marques = Marque::where('id', $id)->delete();
        return redirect('/admin/settings');
    }
}
