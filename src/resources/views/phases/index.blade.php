@extends('layouts.app')

@section('title', 'Phases')

@section('content')
    <div class="container pt-4">

        <button class="btn btn-primary mb-3 mt-4" id="btnAddPhase">Nouvelle phase</button>

        <table class="table table-bordered" id="phasesTable">
            <thead>
                <tr>
                    <th>Nom</th>
                    <!--<th>Description</th>-->
                    <th>Parent</th>
                    <th>Order</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>

    <!-- MODAL -->
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
                            <label for="name" class="form-label">Ordre</label>
                            <input type="number" step="1" class="form-control" id="order" name="order" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="parent_id" class="form-label">Phase parente</label>
                            <select class="form-control" id="parent_id" name="parent_id">
                                <option value="">-- Aucune --</option>
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
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: '{{ route("phases.datatable") }}',
                columns: [
                    { data: 'name' },
                    // { data: 'description' },
                    { data: 'parent_name' },
                    { data: 'order' },
                    {
                        data: 'id',
                        orderable: false,
                        render: function (id) {
                            return `
                            <button class="btn btn-sm btn-outline-primary me-2" onclick="editPhase(${id})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="deletePhase(${id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        `;
                        }
                    }
                ],
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.11.5/i18n/fr-FR.json"
                }
            });

            $('#btnAddPhase').click(() => {
                $('#phaseForm')[0].reset();
                $('#phase_id').val('');
                $('#phaseForm').attr('action', '{{ route("phases.store") }}');
                $('#phaseModalLabel').text('Nouvelle phase');
                $('#phaseModal').modal('show');
            });

            window.editPhase = function (id) {
                $.getJSON(`/phases/${id}`, function (data) {
                    $('#phase_id').val(data.id);
                    $('#name').val(data.name);
                    $('#order').val(data.order);
                    $('#description').val(data.description);
                    $('#parent_id').val(data.parent_id);
                    $('#phaseForm').attr('action', `/phases/${id}`);
                    $('#phaseModalLabel').text('Modifier la phase');
                    $('#phaseModal').modal('show');
                });
            };

            window.deletePhase = function (id) {
                if (confirm('Voulez-vous supprimer cette phase ?')) {
                    $.ajax({
                        url: `/phases/${id}`,
                        type: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        success: function () {
                            table.ajax.reload();
                        },
                        error: function (xhr) {
                            alert(xhr.responseJSON?.message || 'Erreur lors de la suppression');
                        }
                    });
                }
            };

            $('#phaseForm').submit(function (e) {
                e.preventDefault();
                const form = $(this);
                const url = form.attr('action');
                const method = $('#phase_id').val() ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    method: method,
                    data: form.serialize(),
                    success: function () {
                        $('#phaseModal').modal('hide');
                        table.ajax.reload();
                    },
                    error: function (xhr) {
                        alert(xhr.responseJSON?.message || 'Erreur lors de l’enregistrement');
                    }
                });
            });
        });
    </script>
@endpush