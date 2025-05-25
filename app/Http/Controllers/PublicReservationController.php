<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Client;
use App\Models\Reservation;
use App\Mail\ReservationConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class PublicReservationController extends Controller
{
    /**
     * Show available cars for public reservation
     */
    public function index(Request $request)
    {
        $query = Car::where('status', 'available');
        
        // Filter by brand
        if ($request->filled('brand')) {
            $query->whereHas('modele.marque', function($q) use ($request) {
                $q->where('name', $request->brand);
            });
        }
        
        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('price_per_day', '>=', $request->min_price);
        }
        
        if ($request->filled('max_price')) {
            $query->where('price_per_day', '<=', $request->max_price);
        }
        
        // Filter by availability dates
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = $request->start_date;
            $endDate = $request->end_date;
            
            $query->whereDoesntHave('reservations', function($q) use ($startDate, $endDate) {
                $q->where(function($subQ) use ($startDate, $endDate) {
                    $subQ->whereBetween('start_date', [$startDate, $endDate])
                         ->orWhereBetween('end_date', [$startDate, $endDate])
                         ->orWhere(function($innerQ) use ($startDate, $endDate) {
                             $innerQ->where('start_date', '<=', $startDate)
                                    ->where('end_date', '>=', $endDate);
                         });
                })->whereIn('status', ['confirmed', 'pending']);
            });
        }
        
        $cars = $query->with(['modele.marque'])->paginate(12);
        $brands = Car::with(['modele.marque'])
            ->get()
            ->pluck('brand')
            ->unique()
            ->sort()
            ->values();
        
        return view('public.cars.index', compact('cars', 'brands'));
    }
    
    /**
     * Show car details and reservation form
     */
    public function show(Car $car, Request $request)
    {
        // Calculate price if dates are provided
        $totalPrice = null;
        $days = null;
        
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);
            $days = $startDate->diffInDays($endDate);
            $totalPrice = $days * $car->price_per_day;
        }
        
        return view('public.cars.show', compact('car', 'totalPrice', 'days'));
    }
    
    /**
     * Show reservation request form
     */
    public function create(Car $car, Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        
        // Validate dates if provided
        if ($startDate && $endDate) {
            $start = Carbon::parse($startDate);
            $end = Carbon::parse($endDate);
            
            if ($start->isPast() || $end->isPast() || $start->gte($end)) {
                return redirect()->route('public.cars.show', $car)
                               ->withErrors(['dates' => 'Les dates sélectionnées ne sont pas valides.']);
            }
            
            // Check availability
            if (!$car->isAvailableForPeriod($startDate, $endDate)) {
                return redirect()->route('public.cars.show', $car)
                               ->withErrors(['dates' => 'Ce véhicule n\'est pas disponible pour la période sélectionnée.']);
            }
        }
        
        return view('public.reservations.create', compact('car', 'startDate', 'endDate'));
    }
    
    /**
     * Store a new reservation request
     */
    public function store(Request $request)
    {
        $request->validate([
            'car_id' => 'required|exists:cars,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'license_number' => 'required|string|max:50',
            'date_of_birth' => 'required|date|before:-18 years',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
        ], [
            'car_id.required' => 'Véhicule requis.',
            'first_name.required' => 'Le prénom est obligatoire.',
            'last_name.required' => 'Le nom est obligatoire.',
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'Format d\'email invalide.',
            'phone.required' => 'Le téléphone est obligatoire.',
            'address.required' => 'L\'adresse est obligatoire.',
            'license_number.required' => 'Le numéro de permis est obligatoire.',
            'date_of_birth.required' => 'La date de naissance est obligatoire.',
            'date_of_birth.before' => 'Vous devez avoir au moins 18 ans.',
            'start_date.required' => 'La date de début est obligatoire.',
            'start_date.after_or_equal' => 'La date de début doit être aujourd\'hui ou dans le futur.',
            'end_date.required' => 'La date de fin est obligatoire.',
            'end_date.after' => 'La date de fin doit être après la date de début.',
        ]);
        
        $car = Car::findOrFail($request->car_id);
        
        // Check car availability again
        if (!$car->isAvailableForPeriod($request->start_date, $request->end_date)) {
            return back()->withErrors(['dates' => 'Ce véhicule n\'est plus disponible pour la période sélectionnée.'])
                        ->withInput();
        }
        
        // Create or find client
        $client = Client::where('email', $request->email)->first();
        
        if (!$client) {
            $client = Client::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'license_number' => $request->license_number,
                'date_of_birth' => $request->date_of_birth,
                'status' => 'active',
            ]);
        } else {
            // Update client information if needed
            $client->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone' => $request->phone,
                'address' => $request->address,
                'license_number' => $request->license_number,
                'date_of_birth' => $request->date_of_birth,
            ]);
        }
        
        // Calculate total price
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $days = $startDate->diffInDays($endDate);
        $totalPrice = $days * $car->price_per_day;
        
        // Create reservation with pending status
        $reservation = Reservation::create([
            'client_id' => $client->id,
            'car_id' => $request->car_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_price' => $totalPrice,
            'status' => 'pending', // Pending until admin confirms
        ]);
        
        // Send confirmation email to client
        try {
            Mail::to($client->email)->send(new ReservationConfirmation($reservation));
        } catch (\Exception $e) {
            \Log::error('Failed to send confirmation email: ' . $e->getMessage());
        }
        
        return redirect()->route('public.reservations.success', $reservation)
                        ->with('success', 'Votre demande de réservation a été soumise avec succès. Vous recevrez une confirmation par email.');
    }
    
    /**
     * Show reservation success page
     */
    public function success(Reservation $reservation)
    {
        $reservation->load(['client', 'car']);
        return view('public.reservations.success', compact('reservation'));
    }
    
    /**
     * Check car availability for given dates (AJAX)
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'car_id' => 'required|exists:cars,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);
        
        $car = Car::findOrFail($request->car_id);
        $available = $car->isAvailableForPeriod($request->start_date, $request->end_date);
        
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $days = $startDate->diffInDays($endDate);
        $totalPrice = $days * $car->price_per_day;
        
        return response()->json([
            'available' => $available,
            'days' => $days,
            'price_per_day' => $car->price_per_day,
            'total_price' => $totalPrice,
            'message' => $available ? 'Véhicule disponible' : 'Véhicule non disponible pour cette période'
        ]);
    }
}
