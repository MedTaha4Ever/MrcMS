@extends('sections.layout')

@section('title', 'Voitures')
@section('cars-active', 'active')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Voitures</h1>
        <input type="text" id="myFilter" class="form-control" onkeyup="search()" placeholder="Filtrer le Voitures.."><br>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5" id="CarCards">
            @foreach ($cars as $car)
                <div class="col mb-4 containerX">
                    <div class="card">
                        <div class="col">
                            <img src="{{ asset('img/car.jpg') }}" class="card-img"
                                alt="{{ 'image de la voiture modele: ' . $car->model }}">
                        </div>
                        <div class="col">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <b>{{ $car->marqName }}</b>
                                    :
                                    {{ $car->modName }}
                                </h5>
                                <p class="card-text">
                                <ul>
                                    <li><b>Matricule:</b> {{ $car->mat }}</li>
                                    <li><b>Date de circulation:</b> {{ date('d/m/Y', strtotime($car->date_cir)) }}</li>
                                    <li><b>Kilometrage:</b> {{ $car->km }}</li>
                                    <li><b>Status:</b> {{ $car->status }}</li>
                                </ul>
                                </p>
                                <a href="{{ url('admin/cars/edit/' . $car->id) }}" class="btn btn-primary">Modifier</a>
                                <button type="button" value="{{ $car->id }}" class="btn btn-danger delete"
                                    data-toggle="modal" data-target="#deleteModal">Supprimer</button>

                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModal">Confirmer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Supprimer la voiture :
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <a href="{{ url('admin/cars/delete/') }}" class="btn btn-danger delete_id">Supprimer</a>
                </div>
            </div>
        </div>
    </div>


    <!-- End of Main Content -->
@endsection

@section('scripts')
    <script defer type="text/javascript">
        $('.delete').click(function() {
            let id = $(this).val();
            $('.delete_id').attr('href', `{{ url('admin/cars/delete/') }}/` + id);
        })

        function search() {
            let input, filter, cards, cardContainer, h5, title, i;
            input = document.getElementById("myFilter");
            filter = input.value.toUpperCase();
            cardContainer = document.getElementById("CarCards");
            cards = cardContainer.getElementsByClassName("card");
            cardC = cardContainer.getElementsByClassName("containerX");;
            for (i = 0; i < cards.length; i++) {
                title = cards[i].querySelector(".card-body");
                if (title.innerText.toUpperCase().indexOf(filter) > -1) {
                    cardC[i].style.display = "";
                } else {
                    cardC[i].style.display = "none";
                }
            }
        }
    </script>
@endsection

@section('addcar')
    <div class="clearfix">
        <a href="{{ url('admin/cars/add') }}" class="btn btn-primary btn-lg float-right mr-5" id="addcar"
            aria-label="addcar">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
                <path
                    d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z" />
            </svg>
            Ajouter une voiture
        </a>
    </div>


@stop
