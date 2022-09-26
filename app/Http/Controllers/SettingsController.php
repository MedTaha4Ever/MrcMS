<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    //
    public function getSettings()
    {
        $marques = DB::table('marques')->get();
        $models = DB::table('models')->get();
        return view('settings', ['marques' => $marques, 'models' => $models]);
    }

    public function addMarque(Request $req)
    {
        $arr = $req->toArray();
        array_shift($arr);
        $marques = DB::table('marques')->insert($arr);
        return redirect('/admin/settings');
    }

    public function delMarque($id)
    {
        $marques = DB::table('marques')->where('id', $id)->delete();
        return redirect('/admin/settings');
    }
}
