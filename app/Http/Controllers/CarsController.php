<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Marque;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CarsController extends Controller
{
    //
    public function select()
    {
        $cars = Car::join('modeles', 'cars.modele_id', '=', 'modeles.id')
            ->select('cars.*', 'modeles.name as modName', 'modeles.year')
            ->get();
        // dump($cars);
        return view('cars', ['cars' => $cars]);
    }

    public function addform()
    {
        $marques = Marque::get();
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
        $cars = Car::insert($arr);
        return redirect('/admin/cars');
    }

    public function editform($id)
    {
        $cars = Car::where('cars.id', "=", $id)
            ->join('modeles', 'cars.modele_id', '=', 'modeles.id')
            ->select('cars.*', 'modeles.id as model_id', 'modeles.name as modName', 'modeles.marque_id as marque_id', 'modeles.year')
            ->get();

        $marques = Marque::get();
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
        $cars = Car::where('id', $arr['id'])->update($arr);
        return redirect('/admin/cars');
    }

    public function delete(Request $req)
    {
        $cars = Car::where('id', $req->id)->delete();
        return redirect('/admin/cars');
    }
}
