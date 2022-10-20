<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class CarsController extends Controller
{
    //
    public function select()
    {
        $cars = DB::table('cars')
            ->join('models', 'cars.model', '=', 'models.id')
            ->select('cars.*', 'models.name as modName', 'models.year')
            ->get();
        // dump($cars);
        return view('cars', ['cars' => $cars]);
    }

    public function addform()
    {
        $marques = DB::table('marques')->get();
        // $marques = $marques->toArray();
        // $models = $models->toArray();
        // dump($marques);
        return view('cars.addform', ['marques' => $marques]);
    }

    public function add(Request $req)
    {
        $arr = $req->toArray();
        // print_r($arr);
        array_shift($arr);
        $arr['created_at'] = Carbon::now();
        $cars = DB::table('cars')->insert($arr);
        return redirect('/admin/cars');
    }

    public function editform($id)
    {
        $cars = DB::table('cars')
            ->where('id', "=", $id)
            ->get();

        $marques = DB::table('marques')->get();
        // $cars = $cars->toArray();
        // $status = DB::table('contracts')->where('car_id', $id)->where('date_r', '<', date('c'))->get();
        // $status = $status->toArray();
        // print_r($status);
        // return view('cars.editform', ['cars' => $cars], ['status' => $status]); 
        // dump($cars);
        return view('cars.editform', ['cars' => $cars], ['marques' => $marques]);
    }

    public function edit(Request $req)
    {
        $arr = $req->toArray();
        array_shift($arr);
        $arr['updated_at'] = Carbon::now();
        $cars = DB::table('cars')->where('id', $arr['id'])->update($arr);
        return redirect('/admin/cars');
    }

    public function delete(Request $req)
    {
        $cars = DB::table('cars')->where('id', $req->id)->delete();
        return redirect('/admin/cars');
    }
}
