{{-- resources/views/client/cars/available.blade.php --}}
@extends('sections.layout') {{-- Assuming a common layout --}}

@section('title', 'Available Cars')

@section('content')
<div class="container mt-4">
    <h1>Available Cars</h1>

    <form method="GET" action="{{ route('cars.available') }}" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <label for="start_date">Start Date:</label>
                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $startDate ?? old('start_date') }}">
            </div>
            <div class="col-md-3">
                <label for="end_date">End Date:</label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $endDate ?? old('end_date') }}">
            </div>
            <div class="col-md-3">
                <label for="marque_id">Marque:</label>
                <select name="marque_id" id="marque_id" class="form-control">
                    <option value="">All Marques</option>
                    @isset($marques) {{-- Check if $marques is passed --}}
                        @foreach ($marques as $marque)
                            <option value="{{ $marque->id }}" {{ (isset($currentMarqueId) && $currentMarqueId == $marque->id) ? 'selected' : '' }}>
                                {{ $marque->name }}
                            </option>
                        @endforeach
                    @endisset
                </select>
            </div>
            <div class="col-md-3 align-self-end">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>

    @isset($cars) {{-- Check if $cars is passed --}}
        @if($cars->isEmpty())
            <p>No cars available for the selected criteria.</p>
        @else
            <div class="row">
                @foreach ($cars as $car)
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <img src="{{ $car->image_url ?? asset('img/default_car_icon.png') }}" class="card-img-top" alt="{{ $car->modele->marque->name }} {{ $car->modele->name }}" style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title">{{ $car->modele->marque->name }} {{ $car->modele->name }}</h5>
                                <p class="card-text">Matricule: {{ $car->mat }}</p>
                                <p class="card-text">KM: {{ $car->km }}</p>
                                <a href="{{ route('cars.showDetails', $car) }}" class="btn btn-info btn-sm">Details & Reserve</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="d-flex justify-content-center">
                {{ $cars->appends(request()->query())->links() }} {{-- Pagination links --}}
            </div>
        @endif
    @else
        <p>Please filter to see available cars.</p>
    @endisset
</div>
@endsection
