@extends('layouts.app')

@section('title', 'Projets')

@section('content')
    <div class="container">
        <h2 class="mb-3">Liste des projets</h2>
        <button class="btn btn-primary mb-3" id="btnAddProject">Nouveau projet</button>

        <table class="table table-bordered" id="projectsTable">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Client</th>
                    <!-- <th>Budget</th>
                            <th>Date de début</th>
                            <th>Date de fin</th> -->
                    <!-- <th>Nombres Chantiers</th> -->
                    <!-- <th>Chantiers</th> -->
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>

    <!-- MODAL FORMULAIRE PROJET -->
    <div class="modal fade" id="projectModal" tabindex="-1" aria-labelledby="projectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="projectForm">
                @csrf
                <input type="hidden" name="id" id="project_id">
                <input type="hidden" name="type" id="type" value="projet">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="projectModalLabel">Nouveau projet</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="client_id" class="form-label">Client</label>
                            <select class="form-control" id="client_id" name="client_id">
                                <option value="">-- Aucun --</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!--
                                <div class="mb-3">
                                    <label for="budget" class="form-label">Budget</label>
                                    <input type="number" class="form-control" id="budget" name="budget" step="0.01">
                                </div>
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Date de début</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" required>
                                </div>
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">Date de fin</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date">
                                </div>
                                -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Enregistrer</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function () {
            let table = $('#projectsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("projects.datatable") }}',
                columns: [
                    { data: 'name', title: 'Nom du projet' },
                    { data: 'client_name', title: 'Client' },
                    // { data: 'budget', title: 'Budget' },
                    // { data: 'start_date', title: 'Début' },
                    // { data: 'end_date', title: 'Fin' },
                    // { data: 'chantier_count', title: 'Nombres chantiers' },
                    // { data: 'chantier_names', title: 'Chantiers' },
                    {
                        data: 'id',
                        orderable: false,
                        render: function (data) {
                            return `

                                                <button class="btn btn-sm btn-outline-primary me-2" onclick="editProject(${data})">
                                                    Modifier <i class="fas fa-edit"></i>
                                                </button>
                                                <a href="/projects/${data}" class="btn btn-sm btn-outline-primary me-2" role="button" aria-disabled="true">
                                                    Chantiers <i class="fas fa-bars"></i>
                                                </a>
                                                <button class="btn btn-sm btn-outline-danger" onclick="deleteProject(${data})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            `;
                        }
                    }
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.11.5/i18n/fr-FR.json"
                }
            });

            $('#btnAddProject').click(() => {
                $('#projectForm')[0].reset();
                $('#project_id').val('');
                $('#projectForm').attr('action', "{{ route('projects.store') }}");
                $('#projectModalLabel').text('Nouveau projet');
                $('#projectModal').modal('show');
            });

            window.editProject = function (id) {
                
                $.getJSON(`/projects/${id}`, function (data) {
                    console.log(data);
                    $('#project_id').val(data.id);
                    $('#name').val(data.name);
                    $('#client_id').val(data.client_id);
                    // $('#budget').val(data.project.budget);
                    // $('#start_date').val(data.project.start_date);
                    // $('#end_date').val(data.project.end_date);
                    $('#description').val(data.description);
                    $('#projectForm').attr('action', `/projects/${id}`);
                    $('#projectModalLabel').text('Modifier projet');
                    $('#projectModal').modal('show');
                });
                //*/
                /*
                $.ajax({
                    url: `/projects/${id}`,
                    type: 'GET',
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $('#project_id').val(data.id);
                        $('#name').val(data.name);
                        $('#client_id').val(data.client_id);
                        // $('#budget').val(data.budget);
                        // $('#start_date').val(data.start_date);
                        // $('#end_date').val(data.end_date);
                        $('#description').val(data.description);
                        $('#projectForm').attr('action', `/projects/${id}`);
                        $('#projectModalLabel').text('Modifier projet');
                        $('#projectModal').modal('show');
                    },
                    error: function (xhr) {
                        let message = 'Une erreur est survenue';

                        if (xhr.status === 404) {
                            message = 'Le projet n’existe plus.';
                        } else if (xhr.status === 403) {
                            message = 'Vous n’êtes pas autorisé à effectuer cette action.';
                        } else if (xhr.status === 500) {
                            message = 'Erreur serveur : ' + xhr.responseJSON?.message;
                        } else if (xhr.responseJSON?.message) {
                            message = xhr.responseJSON.message;
                        }

                        showNotification('Erreur', message, 'danger');
                    }

                })
                    //*/
            }

            window.deleteProject = function (id) {
                if (confirm('Voulez-vous supprimer ce projet ?')) {
                    $.ajax({
                        url: '/projects/' + id,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function () {
                            table.ajax.reload();
                        },
                        error: function (xhr) {
                            let message = 'Une erreur est survenue';

                            if (xhr.status === 404) {
                                message = 'Le projet n’existe plus.';
                            } else if (xhr.status === 403) {
                                message = 'Vous n’êtes pas autorisé à effectuer cette action.';
                            } else if (xhr.status === 500) {
                                message = 'Erreur serveur : ' + xhr.responseJSON?.message;
                            } else if (xhr.responseJSON?.message) {
                                message = xhr.responseJSON.message;
                            }

                            showNotification('Erreur', message, 'danger');
                        }
                    });
                }
            }

            $('#projectForm').submit(function (e) {
                e.preventDefault();
                let form = $(this);
                let url = form.attr('action');
                let method = $('#project_id').val() ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    method: method,
                    data: form.serialize(),
                    success: function () {
                        $('#projectModal').modal('hide');
                        table.ajax.reload();
                    },
                    error: function (xhr) {
                        let message = 'Une erreur est survenue lors de la sauvegarde';

                        if (xhr.status === 404) {
                            message = 'Le projet n’existe plus.';
                        } else if (xhr.status === 403) {
                            message = 'Vous n’êtes pas autorisé à effectuer cette action.';
                        } else if (xhr.status === 500) {
                            message = 'Erreur serveur : ' + xhr.responseJSON?.message;
                        } else if (xhr.responseJSON?.message) {
                            message = xhr.responseJSON.message;
                        }

                        showNotification('Erreur', message, 'danger');
                    }
                });
            });
        });
    </script>
@endpush