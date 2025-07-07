@extends('layouts.app')

@section('title', 'Phases')

@section('content')
    <div class="container pt-4">

        <button class="btn btn-primary mb-3 mt-4" id="btnAddphase">Nouvelle phase</button>

        <table class="table table-bordered" id="phasesTable">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Parent</th>
                    <th>ParentID</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>

    <!-- MODAL FORMULAIRE PROJET -->
    <div class="modal fade" id="phaseModal" tabindex="-1" aria-labelledby="phaseModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="phaseForm">
                @csrf
                <input type="hidden" name="id" id="phase_id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="phaseModalLabel">Nouvelle phase</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="parent_id" class="form-label">Parent</label>
                            <select class="form-control" id="parent_id" name="parent_id">
                                <option value="">-- Aucun --</option>
                                @foreach($phases as $phase)
                                    <option value="{{ $phase->id }}">{{ $phase->name }}</option>
                                @endforeach
                            </select>
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
            let table = $('#phasesTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("phases.datatable") }}',
                columns: [
                    { data: 'name', title: 'Nom' },
                    { data: 'description', title: 'Description' },
                    { data: 'parent_name', title: 'Parent' },
                    { data: 'parent_id', title: 'Parent ID' },
                    {
                        data: 'id',
                        orderable: false,
                        render: function (data) {
                            return `

                                                <button class="btn btn-sm btn-outline-primary me-2" onclick="editphase(${data})">
                                                    Modifier <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" onclick="deletephase(${data})">
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

            $('#btnAddphase').click(() => {
                $('#phaseForm')[0].reset();
                $('#phase_id').val('');
                $('#phaseForm').attr('action', "{{ route('phases.store') }}");
                $('#phaseModalLabel').text('Nouveau projet');
                $('#phaseModal').modal('show');
            });

            window.editphase = function (id) {
                
                $.getJSON(`/phases/${id}`, function (data) {
                    console.log(data);
                    $('#phase_id').val(data.id);
                    $('#name').val(data.name);
                    $('#parent_id').val(data.parent_id);
                    $('#description').val(data.description);
                    $('#phaseForm').attr('action', `/phases/${id}`);
                    $('#phaseModalLabel').text('Modifier projet');
                    $('#phaseModal').modal('show');
                });
                //*/
            }

            window.deletephase = function (id) {
                if (confirm('Voulez-vous supprimer ce projet ?')) {
                    $.ajax({
                        url: '/phases/' + id,
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

            $('#phaseForm').submit(function (e) {
                e.preventDefault();
                let form = $(this);
                let url = form.attr('action');
                let method = $('#phase_id').val() ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    method: method,
                    data: form.serialize(),
                    success: function () {
                        $('#phaseModal').modal('hide');
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