<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientsController extends Controller
{
    public function select()
    {
        $clients = DB::table('clients')->get();
        return view('clients', ['clients' => $clients]);
    }
}
