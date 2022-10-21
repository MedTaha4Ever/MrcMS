@extends('sections.layout')

@section('title', 'Modifier Voiture')
@section('cars-active', 'active')

@section('scripts')
    <script defer type="text/javascript">
        $("#marque").change(function() {
            $.ajax({
                url: "{{ route('Marque.getModels') }}?marque_id=" + $(this).val(),
                method: 'GET',
                success: function(data) {
                    $('#model').html(data);
                }
            });
        });

        $(document).ready(function() {
            $.ajax({
                url: "{{ route('Marque.getActualModels') }}?model_id={{ $cars[0]->model_id }}",
                method: 'GET',
                success: function(data) {
                    $('#model').html(data);
                }
            });
        });
    </script>
@endsection

@section('content')
    <div class="container-fluid">

        @foreach ($cars as $car)
            <!-- Page Heading -->
            <h1 class="h3 mb-2 text-gray-800">Modifier : {{ $car->mat }}</h1>

            <div class="card shadow mb-4">
                <div class="card-body">
                    <form action="{{ url('admin/cars/edit') }}" method="post" id="editform">
                        @csrf
                        <input type="text" value="{{ $car->id }}" name="id" hidden>
                        <label for="mat">Immatriculation</label><br>
                        <input type="text" class="form-control" name="mat" id="mat" value="{{ $car->mat }}"
                            required><br><br>

                        <label for="marque">marque</label><br>
                        <select class="form-control" id="marque">
                            @if (empty($marques))
                                <option value="0">Pas de Marques</option>
                            @else
                                @foreach ($marques as $marque)
                                    @if ($marque->id == $car->marque_id)
                                        <option value="{{ $marque->id }}" selected>{{ $marque->name }}</option>
                                    @else
                                        <option value="{{ $marque->id }}">{{ $marque->name }}</option>
                                    @endif
                                @endforeach
                            @endif
                        </select><br><br>
                        {{-- <input type="text" class="form-control" name="marque" id="marque" required><br><br> --}}

                        <label for="model">Modele</label><br>
                        <select class="form-control" name="model" id="model">
                            {{-- <option value="{{ $model->id }}">{{ $model->name }}</option> --}}
                        </select><br><br>
                        {{-- <input type="text" class="form-control" name="model" id="model" required><br><br> --}}

                        <label for="date_cir">Date de premier circulation</label><br>
                        <input type="date" class="form-control" name="date_cir" id="date_cir"
                            value="{{ $car->date_cir }}" required><br><br>

                        <label for="km">Kilometrage</label><br>
                        <input type="number" class="form-control" name="km" id="km"
                            value="{{ $car->km }}" required><br><br>

                        <button type="submit" class="btn btn-primary">Modifier</button>
                        <a type="button" href="{{ url()->previous() }}" class="btn btn-secondary">Retour</a>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@stop
