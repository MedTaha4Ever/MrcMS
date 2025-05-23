<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\View\View; // Added import

class ClientsController extends Controller
{
    public function select(): View // Added return type
    {
        $clients = Client::all(); // Changed from Get() to all() for brevity
        return view('clients', ['clients' => $clients]);
    }
}
