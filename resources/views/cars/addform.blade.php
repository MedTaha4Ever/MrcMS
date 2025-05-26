@extends('sections.layout')

@section('title', 'Ajouter Voiture')
@section('cars-active', 'active')

@section('scripts')
    {{-- Inline script moved to public/js/cars_form.js --}}
    {{-- Ensure this script is loaded in the main layout, potentially via a stack --}}
    <script src="{{ asset('js/cars_form.js') }}" defer></script>
@endsection

@section('content')
    {{-- Removed commented out dump: {{ dump($marques)}} --}}
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Ajouter une Voitures</h1>

        <div class="card shadow mb-4">
            <div class="card-body">
                <form action="{{ url('admin/cars/add') }}" method="post">
                    @csrf

                    <label for="mat">Immatriculation</label><br>
                    <input type="text" class="form-control" name="mat" id="mat" required><br><br>

                    <label for="marque">marque</label><br>                    <select class="form-control" id="marque" data-models-url="{{ route('admin.models.getModels') }}">
                        @if (empty($marques))
                            <option value="0">Pas de Marques</option>
                        @else
                            @foreach ($marques as $marque)
                                <option value="{{ $marque->id }}">{{ $marque->name }}</option>
                            @endforeach
                        @endif
                    </select><br><br>
                    {{-- Removed commented out input: <input type="text" class="form-control" name="marque" id="marque" required><br><br> --}}                    <label for="modele">Modele</label><br>
                    <select class="form-control" name="modele_id" id="modele">
                        <option value="">Sélectionnez d'abord une marque</option>
                    </select><br><br>
                    {{-- Removed commented out input: <input type="text" class="form-control" name="model" id="model" required><br><br> --}}                    <div class="form-group">
                        <label for="date_cir">Date de premier circulation</label>
                        <input type="date" class="form-control @error('dpc') is-invalid @enderror" 
                               name="dpc" id="date_cir" 
                               value="{{ old('dpc', date('Y-m-d')) }}" required>
                        @error('dpc')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="km">Kilometrage</label>
                        <input type="number" class="form-control @error('km') is-invalid @enderror" 
                               name="km" id="km" 
                               value="{{ old('km', 0) }}" min="0" required>
                        @error('km')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="price_per_day">Prix par jour (DT)*</label>
                        <input type="number" class="form-control @error('price_per_day') is-invalid @enderror" 
                               id="price_per_day" name="price_per_day" 
                               step="0.01" min="0" 
                               value="{{ old('price_per_day', 100.00) }}" required>
                        @error('price_per_day')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </form>
            </div>
        </div>
    </div>

@stop
