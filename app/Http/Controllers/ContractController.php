<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Client;
use App\Models\Reservation;
use App\Mail\ReservationConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class ContractController extends Controller
{
    /**
     * Display contracts/reservations listing
     */
    public function index(Request $request)
    {
        $query = Reservation::with(['client', 'car.modele.marque']);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('client', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhereHas('car.modele.marque', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('car.modele', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }
        
        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Date filter
        if ($request->filled('date_from')) {
            $query->where('start_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->where('end_date', '<=', $request->date_to);
        }
        
        $reservations = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('contracts.index', compact('reservations'));
    }
    
    /**
     * Show the form for creating a new contract/reservation
     */
    public function create()
    {
        $cars = Car::where('status', 'available')->with(['modele.marque'])->get();
        $clients = Client::where('status', 'active')->get();
        
        return view('contracts.create', compact('cars', 'clients'));
    }
    
    /**
     * Store a newly created contract/reservation
     */
    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'car_id' => 'required|exists:cars,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
        ], [
            'client_id.required' => 'Veuillez sélectionner un client.',
            'car_id.required' => 'Veuillez sélectionner un véhicule.',
            'start_date.required' => 'La date de début est obligatoire.',
            'start_date.after_or_equal' => 'La date de début doit être aujourd\'hui ou dans le futur.',
            'end_date.required' => 'La date de fin est obligatoire.',
            'end_date.after' => 'La date de fin doit être après la date de début.',
        ]);
        
        // Check car availability
        $car = Car::findOrFail($request->car_id);
        if (!$car->isAvailableForPeriod($request->start_date, $request->end_date)) {
            return back()->withErrors(['car_id' => 'Ce véhicule n\'est pas disponible pour la période sélectionnée.']);
        }
        
        // Calculate total price
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $days = $startDate->diffInDays($endDate);
        $totalPrice = $days * $car->price_per_day;
        
        // Create reservation
        $reservation = Reservation::create([
            'client_id' => $request->client_id,
            'car_id' => $request->car_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_price' => $totalPrice,
            'status' => 'confirmed',
        ]);
        
        // Update car status
        $car->update(['status' => 'rented']);
        
        // Send confirmation email
        $client = Client::findOrFail($request->client_id);
        try {
            Mail::to($client->email)->send(new ReservationConfirmation($reservation));
        } catch (\Exception $e) {
            // Log error but don't fail the reservation
            \Log::error('Failed to send confirmation email: ' . $e->getMessage());
        }
        
        return redirect()->route('contracts.show', $reservation)
                        ->with('success', 'Contrat créé avec succès. Email de confirmation envoyé.');
    }
    
    /**
     * Display the specified contract/reservation
     */
    public function show(Reservation $reservation)
    {
        $reservation->load(['client', 'car']);
        return view('contracts.show', compact('reservation'));
    }
    
    /**
     * Show the form for editing the specified contract/reservation
     */
    public function edit(Reservation $reservation)
    {
        $cars = Car::where('status', 'available')
                   ->orWhere('id', $reservation->car_id)
                   ->get();
        $clients = Client::where('status', 'active')->get();
        
        return view('contracts.edit', compact('reservation', 'cars', 'clients'));
    }
    
    /**
     * Update the specified contract/reservation
     */
    public function update(Request $request, Reservation $reservation)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'car_id' => 'required|exists:cars,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:pending,confirmed,cancelled,completed',
        ]);
        
        // If car is changed, check availability
        if ($request->car_id != $reservation->car_id) {
            $car = Car::findOrFail($request->car_id);
            if (!$car->isAvailableForPeriod($request->start_date, $request->end_date, $reservation->id)) {
                return back()->withErrors(['car_id' => 'Ce véhicule n\'est pas disponible pour la période sélectionnée.']);
            }
            
            // Update old car status
            $oldCar = $reservation->car;
            $oldCar->update(['status' => 'available']);
            
            // Update new car status
            $car->update(['status' => 'rented']);
        }
        
        // Recalculate total price if dates or car changed
        $car = Car::findOrFail($request->car_id);
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $days = $startDate->diffInDays($endDate);
        $totalPrice = $days * $car->price_per_day;
        
        $reservation->update([
            'client_id' => $request->client_id,
            'car_id' => $request->car_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_price' => $totalPrice,
            'status' => $request->status,
        ]);
        
        return redirect()->route('contracts.show', $reservation)
                        ->with('success', 'Contrat mis à jour avec succès.');
    }
    
    /**
     * Remove the specified contract/reservation
     */
    public function destroy(Reservation $reservation)
    {
        // Update car status to available
        $reservation->car->update(['status' => 'available']);
        
        $reservation->delete();
        
        return redirect()->route('contracts.index')
                        ->with('success', 'Contrat supprimé avec succès.');
    }
}
