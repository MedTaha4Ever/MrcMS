{{-- resources/views/client/reservations/my_reservations.blade.php --}}
@extends('sections.layout')

@section('title', 'My Reservations')

@section('content')
<div class="container mt-4">
    <h1>My Reservations</h1>

    {{-- Simplified client ID input for now if not relying on auth fully yet --}}
    @if (!Auth::check() && !isset($client)) {{-- If not logged in and no specific client data passed --}}
    <form method="GET" action="{{ route('reservations.mine') }}" class="mb-3 alert alert-info p-3">
        <p>To view reservations, please enter your Client ID. If you are logged in, your reservations will be shown automatically.</p>
        <div class="row align-items-end">
            <div class="col-md-4">
                <label for="client_id_input" class="form-label">Enter Client ID:</label>
                <input type="number" name="client_id" id="client_id_input" class="form-control" value="{{ request('client_id') }}" placeholder="Your Client ID">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">View My Reservations</button>
            </div>
        </div>
    </form>
    @endif

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if(isset($reservations) && $reservations->count() > 0)
        @if(isset($client))
            <h4 class="mb-3">Reservations for Client: {{ $client->f_name ?? '' }} {{ $client->l_name ?? '' }} (ID: {{ $client->id }})</h4>
        @elseif(Auth::check() && isset(Auth::user()->client)) {{-- Assuming User has a 'client' relationship or client_id property --}}
             <h4 class="mb-3">Your Reservations (Client: {{ Auth::user()->client->f_name ?? Auth::user()->name }})</h4>
        @elseif(Auth::check())
             <h4 class="mb-3">Your Reservations (User ID: {{ Auth::id() }})</h4>
        @endif
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Car</th>
                        <th>Matricule</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Requested On</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reservations as $reservation)
                        <tr>
                            <td>{{ $reservation->id }}</td>
                            <td>{{ $reservation->car->modele->marque->name ?? 'N/A' }} {{ $reservation->car->modele->name ?? 'N/A' }}</td>
                            <td>{{ $reservation->car->mat ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($reservation->start_date)->format('d M Y, H:i') }}</td>
                            <td>{{ \Carbon\Carbon::parse($reservation->end_date)->format('d M Y, H:i') }}</td>
                            <td>{{ $reservation->total_price ? number_format($reservation->total_price, 2) . ' EUR' : 'N/A' }}</td>
                            <td>
                                @php
                                    $statusClass = 'secondary'; // Default
                                    if ($reservation->status == 'confirmed') $statusClass = 'success';
                                    elseif ($reservation->status == 'pending') $statusClass = 'warning';
                                    elseif ($reservation->status == 'active') $statusClass = 'primary'; // Example
                                    elseif ($reservation->status == 'completed') $statusClass = 'info';  // Example
                                    elseif ($reservation->status == 'cancelled') $statusClass = 'danger'; // Example
                                @endphp
                                <span class="badge bg-{{ $statusClass }} text-dark">{{ ucfirst($reservation->status) }}</span>
                            </td>
                            <td>{{ $reservation->created_at->format('d M Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($reservations->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{ $reservations->appends(request()->query())->links() }}
            </div>
        @endif
    @elseif(request('client_id') || Auth::check()) {{-- If client_id was provided or user is logged in, but no reservations found --}}
        <p>You currently have no reservations matching your criteria.</p>
    @elseif(!Auth::check() && !request('client_id'))
        {{-- No client_id provided and not logged in, so no message needed other than the form above.
             Or, you could add a generic prompt to search or login. --}}
    @endif

    <div class="mt-4">
        <a href="{{ route('cars.available') }}" class="btn btn-secondary">&laquo; Find Available Cars</a>
    </div>
</div>
@endsection
