<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Marque;
use App\Models\Reservation;
use App\Models\Client;
use App\Mail\ReservationConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Or session-based client ID
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class ClientReservationController extends Controller
{
    /**
     * Display a listing of available cars, possibly filtered by date.
     */
    public function index(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date|after_or_equal:today',
            'end_date' => 'nullable|date|after:start_date',
            'marque_id' => 'nullable|integer|exists:marques,id',
        ]);

        $startDate = $request->input('start_date', Carbon::today()->toDateString());
        $endDate = $request->input('end_date', Carbon::today()->addDays(7)->toDateString()); // Default to 1 week

        $cars = Car::with('modele.marque')
            ->availableForDates($startDate, $endDate)
            ->when($request->filled('marque_id'), function ($q) use ($request) {
                $q->whereHas('modele', function($q_modele) use ($request) {
                    $q_modele->where('marque_id', $request->input('marque_id'));
                });
            })
            ->orderBy('mat')
            ->paginate(10);

        $marques = Marque::orderBy('name')->get();
        $currentMarqueId = $request->input('marque_id');

        // In a real scenario, you would return a view:
        return view('client.cars.available', compact('cars', 'startDate', 'endDate', 'marques', 'currentMarqueId'));
    }

    /**
     * Show the details for a specific car and a reservation form.
     */
    public function showCarDetails(Car $car)
    {
        // In a real scenario, you would return a view:
        return view('client.cars.show_details', compact('car'));
    }

    /**
     * Store a newly created reservation in storage.
     */
    public function storeReservation(Request $request, Car $car)
    {
        $validated = $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'client_id' => 'nullable|exists:clients,id', // In a real app, this would likely come from Auth::id()
        ]);

        // Check availability using the Car model method
        if (!$car->isAvailableForDates($validated['start_date'], $validated['end_date'])) {
            return back()->withErrors(['availability' => 'Car is not available for the selected dates.'])->withInput();
        }
        
        $clientId = $request->input('client_id');
        if (Auth::check()) { // If a user system is in place
            $user = Auth::user();
            // Assuming your User model has a 'client_id' or is the client itself
            // This part depends on how Users and Clients are related.
            // For now, if a user is logged in and no client_id is passed, try to use user's ID as client_id.
            // This assumes users are clients or directly linked.
            $clientId = $clientId ?? $user->client_id ?? $user->id; // Adjust based on your User/Client setup
        }

        if (!$clientId) {
            return back()->withErrors(['client_error' => 'Client identification is required.'])->withInput();
        }

        // Calculate price using the Car model method
        $totalPrice = $car->calculatePrice($validated['start_date'], $validated['end_date']);

        $reservation = Reservation::create([
            'client_id' => $clientId, 
            'car_id' => $car->id,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'status' => 'pending',
            'total_price' => $totalPrice,
        ]);

        // Send email confirmation if client has email
        $client = Client::find($clientId);
        if ($client && $client->email) {
            try {
                Mail::to($client->email)->send(new ReservationConfirmation($reservation));
            } catch (\Exception $e) {
                // Log the error but don't fail the reservation
                \Log::error('Failed to send reservation confirmation email: ' . $e->getMessage());
            }
        }

        return redirect()->route('reservations.mine', ['client_id' => $clientId])->with('success', 'Reservation request submitted successfully! Your reservation ID is ' . $reservation->id . '. A confirmation email has been sent.');
    }

    /**
     * Display the current client's reservations.
     */
    public function myReservations(Request $request)
    {
        $client = null;
        $reservations = collect(); // Default to an empty collection

        $clientId = $request->input('client_id'); 
        
        if (Auth::check()) {
             $user = Auth::user();
             // Assuming User model might have a direct client_id or a relationship to a Client model
             // This logic might need adjustment based on the actual User-Client relationship
             $authClientId = $user->client_id ?? ($user->client ? $user->client->id : null);
             if ($authClientId) {
                 $clientId = $authClientId; // Prioritize authenticated user's client ID
             }
        }

        if ($clientId) {
            $client = \App\Models\Client::find($clientId); // Fetch client details if needed for the view
            if ($client) {
                $reservations = Reservation::where('client_id', $clientId)
                    ->with(['car.modele.marque']) // Removed 'client' from with() as we already have $client
                    ->latest()
                    ->paginate(10);
            } else {
                // If a client_id is given but not found, it's an error or show no reservations
                // For simplicity, we'll fall through and $reservations will be empty.
                // A flash message could be useful here.
                session()->flash('error', 'Client ID not found.');
            }
        } elseif (!Auth::check()) {
            // Not logged in and no client_id provided, so can't fetch reservations.
            // The view will show a form to input client_id.
        }


        return view('client.reservations.my_reservations', compact('reservations', 'client'));
    }
}
