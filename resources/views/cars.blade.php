@extends('sections.layout')

@section('title', 'Voitures')
@section('cars-active', 'active')

@section('content')
    <div class="container">
        <div class="container">
            <div class="container-fluid p-0">
                <h1 class="h3 mb-3">Cars</h1>
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-body">
                                <table id="c_table" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>#</th>
                                            <th>Serie</th>
                                            <th>Modele</th>
                                            <th>Kilometrage</th>
                                            <th>Status</th>
                                            <th>Modofication</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cars as $car)
                                            <tr
                                                data-child-value="{{ $diff =
                                                    Carbon\Carbon::parse($car->date_cir)->diffInYears(Carbon\Carbon::now()) .
                                                    ' ans </li><li><b>D.P.C</b>: ' .
                                                    date('d M Y', strtotime($car->date_cir)) .
                                                    '</li></ul>' }}">
                                                <td class="details-control"></td>
                                                <td><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/65/Circle-icons-car.svg/1200px-Circle-icons-car.svg.png"
                                                        width="32" height="32" class="rounded-circle my-n1"
                                                        alt="Avatar"></td>
                                                <td>{{ $car->mat }}</td>
                                                <td>{{ $car->modName }}</td>
                                                <td>{{ $car->km }} Km</td>
                                                @if ($car->contract_id > 0)
                                                    <td><span class="badge bg-danger">{{ $car->contract_id }}</span></td>
                                                @else
                                                    <td><span class="badge bg-success">Pas de Contrat</span></td>
                                                @endif
                                                <td>
                                                    <a href="{{ url('admin/cars/edit/' . $car->id) }}"
                                                        class="btn btn-primary">Modifier</a>
                                                    <button type="button" value="{{ $car->id }}"
                                                        class="btn btn-danger delete" data-toggle="modal"
                                                        data-target="#deleteModal">Supprimer</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModal"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmer</h5>
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
    @stop
    @section('scripts')
        <script defer>
            function format(value) {
                return '<div><ul><li><b>Age</b>: ' + value + '</div>';
            }

            $(document).ready(function() {
                let table = $('#c_table').DataTable({
                    language: {
                        url: '{{ asset('local/fr-FR.json') }}'
                    }
                });

                // Add event listener for opening and closing details
                $('#c_table').on('click', 'td.details-control', function() {
                    let tr = $(this).closest('tr');
                    let row = table.row(tr);

                    if (row.child.isShown()) {
                        // This row is already open - close it
                        row.child.hide();
                        tr.removeClass('shown');
                    } else {
                        // Open this row
                        row.child(format(tr.data('child-value'))).show();
                        tr.addClass('shown');
                    }
                });
            });

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
    @stop
    @section('css')
        <style>
            td.details-control {
                background: url('http://www.datatables.net/examples/resources/details_open.png') no-repeat center center;
                cursor: pointer;
            }

            tr.shown td.details-control {
                background: url('http://www.datatables.net/examples/resources/details_close.png') no-repeat center center;
            }
        </style>
    @stop
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
