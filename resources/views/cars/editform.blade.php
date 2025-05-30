@extends('sections.layout')

@section('title', 'Modifier Voiture')
@section('cars-active', 'active')

@section('scripts')
    {{-- Inline script moved to public/js/cars_form.js --}}
    {{-- This script is now designed to handle both add and edit form scenarios --}}
    <script src="{{ asset('js/cars_form.js') }}" defer></script>
@endsection

@section('content')
    <div class="container-fluid">
        {{-- Removed @foreach loop, using $car directly as passed by controller --}}
        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Modifier : {{ $car->mat }}</h1>

        <div class="card shadow mb-4">
            <div class="card-body">
                {{-- Assuming route for edit uses PUT and includes car ID, e.g., route('cars.update', $car) --}}
                {{-- For now, keeping existing action and method, but this could be improved with RESTful routing --}}                <form action="{{ route('admin.cars.update', $car->id) }}" method="post" id="editform">
                    @csrf
                    @method('PUT')
                    {{-- Car ID is now in the route, no need for hidden input --}}
                    
                    <label for="mat">Immatriculation</label><br>
                    <input type="text" class="form-control" name="mat" id="mat" value="{{ old('mat', $car->mat) }}"
                        required><br><br>

                    <label for="marque">Marque</label><br>
                    {{-- The data-models-url is used by cars_form.js to fetch models --}}                    <select class="form-control" id="marque" name="marque_selector_for_js_hook" data-models-url="{{ route('admin.models.getModels') }}">
                        @if ($marques->isEmpty())
                            <option value="">Pas de Marques</option>
                        @else
                            @foreach ($marques as $marque)
                                <option value="{{ $marque->id }}" {{ old('marque_selector_for_js_hook', $car->modele->marque_id ?? '') == $marque->id ? 'selected' : '' }}>
                                    {{ $marque->name }}
                                </option>
                            @endforeach
                        @endif
                    </select><br><br>
                    {{-- Removed commented out input: <input type="text" class="form-control" name="marque" id="marque" required><br><br> --}}

                    <label for="modele">Modele</label><br>
                    {{-- cars_form.js will populate this. data-initial-model-id helps preselect the current model --}}
                    <select class="form-control" name="modele_id" id="modele" data-initial-model-id="{{ old('modele_id', $car->modele_id ?? '') }}">
                        {{-- Options will be populated by JavaScript. Provide a fallback or currently selected one if possible --}}
                        @if(old('modele_id', $car->modele_id ?? ''))
                            <option value="{{ old('modele_id', $car->modele_id) }}" selected>{{-- Try to show current model name if possible, JS will override --}} {{ $car->modele->name ?? 'Chargement...' }}</option>
                        @else
                            <option value="">Sélectionnez d'abord une marque</option>
                        @endif
                    </select><br><br>
                    {{-- Removed commented out input: <input type="text" class="form-control" name="model" id="model" required><br><br> --}}                    <label for="date_cir">Date de premier circulation</label><br>
                    <input type="date" class="form-control" name="dpc" id="date_cir"
                        value="{{ old('dpc', $car->dpc ? $car->dpc->format('Y-m-d') : '') }}" required><br><br>

                    <label for="km">Kilometrage</label><br>
                    <input type="number" class="form-control" name="km" id="km"
                        value="{{ old('km', $car->km) }}" required><br><br>

                    <div class="form-group">
                        <label for="price_per_day">Prix par jour (DT)*</label>
                        <input type="number" class="form-control @error('price_per_day') is-invalid @enderror" 
                               id="price_per_day" name="price_per_day" step="0.01" min="0" 
                               value="{{ old('price_per_day', $car->price_per_day) }}" required>
                        @error('price_per_day')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Modifier</button>
                    <a type="button" href="{{ url()->previous() }}" class="btn btn-secondary">Retour</a>
                </form>
            </div>
        </div>
    </div>
@stop
