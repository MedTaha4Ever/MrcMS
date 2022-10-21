<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientsController extends Controller
{
    public function select()
    {
        $clients = Client::get();
        return view('clients', ['clients' => $clients]);
    }
}
