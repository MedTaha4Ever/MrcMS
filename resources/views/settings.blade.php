@extends('sections.layout')

@section('title', 'Parametre')
@section('settings-active', 'active')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Parametre de voitures</h1>

        <div class="card shadow mb-4">
            {{-- parametre de marques --}}
            <div class="card-body">
                <h5 class="card-title">Marques</h5>
                <a href="#" data-toggle="modal" data-target="#addMarqueModal" class="btn btn-primary">
                    <li class="fas fa-fw fa-plus-square"></li>
                </a>
                <br><br>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Nom</th>
                            <th scope="col">Modifier</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($marques as $marque)
                            <tr>
                                <th scope="row">{{ $marque->name }}</th>
                                <td>
                                    <a class="btn btn-primary"
                                        href="{{ url('admin/settings/marque/edit/') . '/' . $marque->id }}">
                                        <li class="fas fa-fw fa-edit"></li>
                                    </a>
                                    <button type="button" class="btn btn-danger delete" data-toggle="modal"
                                        data-target="#deleteMarqueModal" value="{{ $marque->id }}">
                                        <li class="fas fa-fw fa-times"></li>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card shadow mb-4">
            {{-- parametre de modeles --}}
            <div class="card-body">
                <h5 class="card-title">Modeles</h5>
                <a href="{{ url('admin/settings/model/add/') }}" class="btn btn-primary">
                    <li class="fas fa-fw fa-plus-square"></li>
                </a>
                <br><br>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Nom</th>
                            <th scope="col">Marque</th>
                            <th scope="col">Année</th>
                            <th scope="col">Modifier</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($models as $model)
                            <tr>
                                <th scope="row">{{ $model->name }}</th>
                                @foreach ($marques as $marque)
                                    @if ($marque->id == $model->marque_id)
                                        <td scope="row">{{ $marque->name }}</td>
                                    @endif
                                @endforeach
                                <td scope="row">{{ $model->year }}</td>
                                <td>
                                    <a class="btn btn-primary"
                                        href="{{ url('admin/settings/marque/edit/') . '/' . $model->id }}">
                                        <li class="fas fa-fw fa-edit"></li>
                                    </a>
                                    <a class="btn btn-danger"
                                        href="{{ url('admin/settings/marque/delete/') . '/' . $model->id }}">
                                        <li class="fas fa-fw fa-times"></li>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Marque add-->
    <div class="modal fade" id="addMarqueModal" tabindex="-1" role="dialog" aria-labelledby="addMarqueModal"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMarqueModal">Confirmer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ url('/admin/settings/marque/add') }}" method="post">
                        @csrf
                        <label for="name">Nom de Marque:</label>
                        <input type="text" name="name" id="name">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-primary" value="Ajouter">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Marque delete-->
    <div class="modal fade" id="deleteMarqueModal" tabindex="-1" role="dialog" aria-labelledby="deleteMarqueModal"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteMarqueModal">Confirmer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <a href="{{ url('admin/settings/marque/delete/') }}" type="button"
                        class="btn btn-danger delete_id">Supprimer</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script defer type="text/javascript">
        $('.delete').click(function() {
            let id = $(this).val();
            $('.delete_id').attr('href', `{{ url('admin/settings/marque/delete/') }}/` + id);
        })
    </script>


@stop
