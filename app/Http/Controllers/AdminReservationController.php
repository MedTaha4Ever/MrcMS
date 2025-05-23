<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Car; // For filter dropdown
use App\Models\Client; // For filter dropdown
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationConfirmed;
use App\Mail\ReservationCancelled;

class AdminReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Reservation::with(['client', 'car.modele.marque'])->latest();

        if ($request->filled('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('end_date', '<=', $request->end_date);
        }
        
        if ($request->filled('car_id')) {
            $query->where('car_id', $request->car_id);
        }

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        $reservations = $query->paginate(15)->appends($request->query());
        
        $cars = Car::orderBy('mat')->get(); // For filter dropdown
        $clients = Client::orderBy('f_name')->orderBy('l_name')->get(); // For filter dropdown
        $statuses = ['pending', 'confirmed', 'active', 'completed', 'cancelled']; // For status filter dropdown

        // Replace with view once created
        return view('admin.reservations.index', compact('reservations', 'cars', 'clients', 'statuses')); 
    }

    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation)
    {
        $reservation->load(['client', 'car.modele.marque']);
        // Replace with view once created
        return view('admin.reservations.show', compact('reservation'));
    }

    /**
     * Confirm the specified reservation.
     */
    public function confirm(Reservation $reservation)
    {
        if ($reservation->status === 'pending') {
            $reservation->status = 'confirmed';
            $reservation->save();

            // Send email notification
            if ($reservation->client && $reservation->client->email) {
                try {
                    Mail::to($reservation->client->email)->send(new ReservationConfirmed($reservation->load(['client', 'car.modele.marque'])));
                } catch (\Exception $e) {
                    // Log error or flash a message that email sending failed
                    // Log::error('Failed to send reservation confirmation email: ' . $e->getMessage());
                    return redirect()->route('admin.reservations.index')
                                     ->with('success', 'Reservation ID ' . $reservation->id . ' confirmed, but failed to send confirmation email.')
                                     ->with('email_error', 'Email sending failed: ' . $e->getMessage());
                }
            }

            return redirect()->route('admin.reservations.index')->with('success', 'Reservation ID ' . $reservation->id . ' confirmed successfully.');
        }
        return redirect()->route('admin.reservations.index')->with('error', 'Reservation ID ' . $reservation->id . ' cannot be confirmed or is already confirmed.');
    }

    /**
     * Cancel the specified reservation.
     */
    public function cancel(Reservation $reservation)
    {
        // Allow cancellation if pending or confirmed (business rule dependent)
        if (in_array($reservation->status, ['pending', 'confirmed', 'active'])) { // Added 'active' as a cancellable state
            $reservation->status = 'cancelled';
            $reservation->save();

            // Send email notification
            if ($reservation->client && $reservation->client->email) {
                 try {
                    Mail::to($reservation->client->email)->send(new ReservationCancelled($reservation->load(['client', 'car.modele.marque'])));
                } catch (\Exception $e) {
                    // Log error or flash a message that email sending failed
                    // Log::error('Failed to send reservation cancellation email: ' . $e->getMessage());
                     return redirect()->route('admin.reservations.index')
                                     ->with('success', 'Reservation ID ' . $reservation->id . ' cancelled, but failed to send cancellation email.')
                                     ->with('email_error', 'Email sending failed: ' . $e->getMessage());
                }
            }

            return redirect()->route('admin.reservations.index')->with('success', 'Reservation ID ' . $reservation->id . ' cancelled successfully.');
        }
        return redirect()->route('admin.reservations.index')->with('error', 'Reservation ID ' . $reservation->id . ' cannot be cancelled or is in a state that prevents cancellation.');
    }
}
