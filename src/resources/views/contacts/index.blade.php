@extends('layouts.app')

@section('title', 'Contacts')
@push('styles')
    <style>
        .badge-client {
            background-color: #d1fae5;
            color: #065f46;
        }

        .badge-fournisseur {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .badge-prestataire {
            background-color: #ede9fe;
            color: #5b21b6;
        }

        .badge-ouvrier {
            background-color: #fef3c7;
            color: #92400e;
        }

        .badge-autre {
            background-color: #e5e7eb;
            color: #4b5563;
        }
    </style>
@endpush

@section('content')
    <div class="container pt-4">
        <h2 class="mt-4">Liste des contacts</h2>
        <button class="btn btn-primary mb-3" id="btnAddContact">Nouveau contact</button>

        <table class="table table-bordered" id="contactsTable">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Adresse</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>

    {{-- MODAL DE FORMULAIRE --}}
    <div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="contactForm">
                @csrf
                <input type="hidden" name="id" id="contact_id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="contactModalLabel">Nouveau contact</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="type" class="form-label">Type</label>
                            <select class="form-control" id="type" name="type">
                                <option value="client">Client</option>
                                <option value="fournisseur">Fournisseur</option>
                                <option value="prestataire">Prestataire</option>
                                <option value="ouvrier">Ouvrier</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Téléphone</label>
                            <input type="text" class="form-control" id="phone" name="phone" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Adresse</label>
                            <input type="text" class="form-control" id="address" name="address">
                        </div>
                        <div class="mb-3">
                            <label for="siret" class="form-label">SIRET</label>
                            <input type="text" class="form-control" id="siret" name="siret">
                        </div>
                        <div class="mb-3">
                            <label for="metadata" class="form-label">Métadonnées (JSON)</label>
                            <textarea class="form-control" id="metadata" name="metadata" rows="2"
                                placeholder='{"note": "important"}'></textarea>
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
            let table = $('#contactsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("contacts.datatable") }}',
                columns: [
                    {
                        data: 'type',
                        render: function (data, type, row) {
                            return '<span class="badge badge-' + data + '">' +
                                data.charAt(0).toUpperCase() + data.slice(1) + '</span>';
                        }
                    },
                    { data: 'name' },
                    { data: 'phone' },
                    {
                        data: 'email',
                        render: function (data) {
                            return data || '-';
                        }
                    },
                    { data: 'address' },
                    {
                        data: 'id',
                        orderable: false,
                        render: function (data, type, row) {
                            return `
                                    <button class="btn btn-sm btn-outline-primary me-2 btn-edit" data-id="${data}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteContact(${data})">
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

            // Initialisation des toast
            var toastEl = document.getElementById('notification');
            var toast = new bootstrap.Toast(toastEl, { autohide: true, delay: 3000 });

            // Fonction pour afficher les notifications
            function showNotification(title, message, type = 'success') {
                $('#toast-title').text(title);
                $('#toast-message').text(message);
                $('.toast-header').removeClass('bg-success bg-danger bg-warning')
                    .addClass('bg-' + type);
                toast.show();
            }

            $('#btnAddContact').click(() => {
                $('#contactForm')[0].reset();
                $('#contact_id').val('');
                $('#modalTitle').text('Nouveau');
                $('#contactForm').attr('action', "{{ route('contacts.store') }}");
                $('#contactModal').modal('show');
            });

            $('#contactsTable').on('click', '.btn-edit', function () {
                const id = $(this).data('id');
                $.get(`/contacts/${id}`, function (data) {
                    $('#contact_id').val(data.id);
                    $('#name').val(data.name);
                    $('#type').val(data.type);
                    $('#phone').val(data.phone);
                    $('#email').val(data.email);
                    $('#address').val(data.address);
                    $('#siret').val(data.siret);
                    $('#metadata').val(JSON.stringify(data.metadata ?? {}, null, 2));
                    $('#contactForm').attr('action', '/contacts/' + id);
                    $('#contactModal').modal('show');
                });
            });

            $('#contactForm').submit(function (e) {
                e.preventDefault();
                var form = $(this);
                var url = form.attr('action');
                var method = $('#contact_id').val() ? 'PUT' : 'POST';
                $.ajax({
                    url: url,
                    method: method,
                    data: $(this).serialize(),
                    success: function (response) {
                        table.ajax.reload();
                        $('#contactModal').modal('hide');
                        showNotification('Succès', response.message);
                    },
                    error: function (xhr) {
                        let message = 'Une erreur est survenue lors de la sauvegarde';

                        if (xhr.status === 404) {
                            message = 'Le contact n’existe plus.';
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

        function deleteContact(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer ce contact ?')) {
                $.ajax({
                    url: '/contacts/' + id,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function () {
                        $('#contactsTable').DataTable().ajax.reload();
                    },
                    error: function (xhr) {
                        let message = 'Une erreur est survenue';

                        if (xhr.status === 404) {
                            message = 'Le contact n’existe plus.';
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
    </script>
@endpush