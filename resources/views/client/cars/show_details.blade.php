{{-- resources/views/client/cars/show_details.blade.php --}}
@extends('sections.layout')

@section('title', 'Car Details - ' . ($car->modele->marque->name ?? '') . ' ' . ($car->modele->name ?? ''))

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-6">
            <img src="{{ $car->image_url ?? asset('img/default_car_icon.png') }}" class="img-fluid rounded" alt="{{ $car->modele->marque->name ?? '' }} {{ $car->modele->name ?? '' }}">
        </div>
        <div class="col-md-6">
            <h1>{{ $car->modele->marque->name ?? '' }} {{ $car->modele->name ?? '' }}</h1>
            <p><strong>Matricule:</strong> {{ $car->mat }}</p>
            <p><strong>KM:</strong> {{ number_format($car->km ?? 0) }} km</p>
            <p><strong>Date de première circulation:</strong> {{ $car->dpc ? $car->dpc->format('d M Y') : 'N/A' }}</p>
            <p><strong>Age:</strong> {{ $car->age }} years</p>
            <p><strong>Price per day:</strong> <span class="text-success fw-bold">€{{ number_format($car->price_per_day, 2) }}</span></p>
            
            <div class="alert alert-info">
                <strong>Estimated Total:</strong> <span id="estimated-total">Select dates to see total price</span>
            </div>


            <hr>
            <h3>Request Reservation</h3>
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('cars.reserve', $car) }}" method="POST">
                @csrf
                <div class="form-group mb-2">
                    <label for="start_date">Start Date:</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date', request('start_date', \Carbon\Carbon::today()->toDateString())) }}" required>
                </div>
                <div class="form-group mb-2">
                    <label for="end_date">End Date:</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date', request('end_date', \Carbon\Carbon::today()->addDay()->toDateString())) }}" required>
                </div>
                
                {{-- Simplified client_id input for now. In a real app, this would be handled by authentication. --}}
                @if (!Auth::check())
                <div class="form-group mb-3">
                    <label for="client_id">Client ID (for testing):</label>
                    <input type="number" name="client_id" id="client_id" class="form-control" value="{{ old('client_id', '1') }}" placeholder="Enter your Client ID (e.g., 1)">
                    <small class="form-text text-muted">This will be automatic if you are logged in.</small>
                </div>
                @endif

                <button type="submit" class="btn btn-primary">Submit Reservation Request</button>
            </form>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('cars.available') }}" class="btn btn-secondary">&laquo; Back to Available Cars</a>
    </div>
</div>
@endsection

@section('scripts')
    @parent
    <script>
        const pricePerDay = {{ $car->price_per_day }};
        
        function calculateTotal() {
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            
            if (startDate && endDate) {
                const start = new Date(startDate);
                const end = new Date(endDate);
                const timeDiff = end.getTime() - start.getTime();
                const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));
                
                if (daysDiff > 0) {
                    const total = daysDiff * pricePerDay;
                    document.getElementById('estimated-total').innerHTML = 
                        `€${total.toFixed(2)} (${daysDiff} day${daysDiff > 1 ? 's' : ''})`;
                } else {
                    document.getElementById('estimated-total').innerHTML = 'Invalid date range';
                }
            }
        }
        
        document.getElementById('start_date').addEventListener('change', calculateTotal);
        document.getElementById('end_date').addEventListener('change', calculateTotal);
        
        // Calculate on page load if dates are pre-filled
        calculateTotal();
    </script>
@endsection
