@extends('sections.layout')

@section('title', 'Ajouter Voiture')
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
    </script>
@endsection

@section('content')
    {{-- {{ dump($marques)}} --}}
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Ajouter une Voitures</h1>

        <div class="card shadow mb-4">
            <div class="card-body">
                <form action="{{ url('admin/cars/add') }}" method="post">
                    @csrf

                    <label for="mat">Immatriculation</label><br>
                    <input type="text" class="form-control" name="mat" id="mat" required><br><br>

                    <label for="marque">marque</label><br>
                    <select class="form-control" id="marque">
                        @if (empty($marques))
                            <option value="0">Pas de Marques</option>
                        @else
                            @foreach ($marques as $marque)
                                <option value="{{ $marque->id }}">{{ $marque->name }}</option>
                            @endforeach
                        @endif
                    </select><br><br>
                    {{-- <input type="text" class="form-control" name="marque" id="marque" required><br><br> --}}

                    <label for="model">Modele</label><br>
                    <select class="form-control" name="modele_id" id="model">

                    </select><br><br>
                    {{-- <input type="text" class="form-control" name="model" id="model" required><br><br> --}}

                    <label for="date_cir">Date de premier circulation</label><br>
                    <input type="date" class="form-control" name="dpc" id="date_cir" required><br><br>

                    <label for="km">Kilometrage</label><br>
                    <input type="number" class="form-control" name="km" id="km" required><br><br>

                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </form>
            </div>
        </div>
    </div>

@stop
