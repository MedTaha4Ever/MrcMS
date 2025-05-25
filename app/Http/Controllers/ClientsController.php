<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class ClientsController extends Controller
{
    /**
     * Display a listing of clients.
     */
    public function index(Request $request): View
    {
        $query = Client::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('f_name', 'like', "%{$search}%")
                  ->orWhere('l_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('cin', 'like', "%{$search}%")
                  ->orWhere('permis', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $clients = $query->orderBy('f_name')->orderBy('l_name')->paginate(15);
        
        return view('clients', compact('clients'));
    }

    /**
     * Show the form for creating a new client.
     */
    public function create(): View
    {
        return view('clients.create');
    }

    /**
     * Store a newly created client in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'f_name' => 'required|string|max:255',
            'l_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:clients,email',
            'phone' => 'nullable|string|max:20',
            'cin' => 'required|string|max:20|unique:clients,cin',
            'adrs' => 'nullable|string|max:500',
            'b_date' => 'required|date|before:today',
            'permis' => 'required|string|max:50|unique:clients,permis',
            'date_permis' => 'required|date|before_or_equal:today',
            'notes' => 'nullable|string|max:1000',
            'status' => 'required|in:active,inactive,suspended'
        ]);

        $validated['contract_id'] = 0; // Default value

        Client::create($validated);

        return redirect()->route('admin.clients.index')
                        ->with('success', 'Client created successfully.');
    }

    /**
     * Display the specified client.
     */
    public function show(Client $client): View
    {
        $client->load('reservations.car.modele.marque');
        return view('clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified client.
     */
    public function edit(Client $client): View
    {
        return view('clients.edit', compact('client'));
    }

    /**
     * Update the specified client in storage.
     */
    public function update(Request $request, Client $client): RedirectResponse
    {
        $validated = $request->validate([
            'f_name' => 'required|string|max:255',
            'l_name' => 'required|string|max:255',
            'email' => ['nullable', 'email', Rule::unique('clients')->ignore($client->id)],
            'phone' => 'nullable|string|max:20',
            'cin' => ['required', 'string', 'max:20', Rule::unique('clients')->ignore($client->id)],
            'adrs' => 'nullable|string|max:500',
            'b_date' => 'required|date|before:today',
            'permis' => ['required', 'string', 'max:50', Rule::unique('clients')->ignore($client->id)],
            'date_permis' => 'required|date|before_or_equal:today',
            'notes' => 'nullable|string|max:1000',
            'status' => 'required|in:active,inactive,suspended'
        ]);

        $client->update($validated);

        return redirect()->route('admin.clients.index')
                        ->with('success', 'Client updated successfully.');
    }

    /**
     * Remove the specified client from storage.
     */
    public function destroy(Client $client): RedirectResponse
    {
        // Check if client has active reservations
        if ($client->reservations()->whereIn('status', ['pending', 'confirmed', 'active'])->exists()) {
            return redirect()->route('admin.clients.index')
                            ->with('error', 'Cannot delete client with active reservations.');
        }

        $client->delete();

        return redirect()->route('admin.clients.index')
                        ->with('success', 'Client deleted successfully.');
    }

    /**
     * Legacy method for backward compatibility.
     */
    public function select(): View
    {
        return $this->index(request());
    }
}
