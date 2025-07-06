@extends('layouts.app')

@section('title', 'Détails du chantier')

@section('content')
<div class="container pt-4">
    <h2 class="mt-4">Détails du chantier</h2>
    <div class="row">
        {{-- Bloc gauche --}}
        <div class="col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-3">Client</dt>
                        <dd class="col-sm-9">{{ optional($project->parent->client)->name ?? '-' }}</dd>

                        <dt class="col-sm-3">Projet</dt>
                        <dd class="col-sm-9">{{ optional($project->parent)->name ?? '-' }}</dd>

                        <dt class="col-sm-3">Chantier</dt>
                        <dd class="col-sm-9">{{ $project->name }}</dd>

                        <dt class="col-sm-3">Début</dt>
                        <dd class="col-sm-9">{{ $project->start_date }}</dd>

                        <dt class="col-sm-3">Fin</dt>
                        <dd class="col-sm-9">{{ $project->end_date ?? 'Non définie' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        {{-- Bloc droit --}}
        <div class="col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-3">Budget</dt>
                        <dd class="col-sm-9">{{ number_format($project->budget, 2, ',', ' ') }} €</dd>

                        <dt class="col-sm-3">Total entrées</dt>
                        <dd class="col-sm-9">{{ number_format($project->totalFinancialMovementsIn(), 0, ',', ' ') }} FCFA</dd>

                        <dt class="col-sm-3">Total sorties</dt>
                        <dd class="col-sm-9">{{ number_format($project->totalFinancialMovementsOut(), 0, ',', ' ') }} FCFA</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    {{-- Contacts --}}
    <div class="mb-5">
        <div class="d-flex justify-content-between mb-2">
            <h4>Contacts associés</h4>
            <button class="btn btn-success btn-sm" onclick="openContactModal()">Ajouter contact</button>
        </div>
        <table id="contactsTable" class="table table-bordered w-100">
            <thead>
                <tr>
                    <th>Rôle</th><th>Nom</th><th>Téléphone</th><th>Email</th><th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>

    {{-- Mouvements financiers --}}
    <div>
        <div class="d-flex justify-content-between mb-2">
            <h4>Mouvements financiers</h4>
            <button class="btn btn-success btn-sm" onclick="openMovementModal()">Ajouter mouvement</button>
        </div>
        <table id="movementsTable" class="table table-striped w-100">
            <thead>
                <tr>
                    <th>Date op.</th><th>Type</th><th>Montant</th><th>Contact</th><th>Motif</th><th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

{{-- Modal Contact --}}
<div class="modal fade" id="contactModal" tabindex="-1">
  <div class="modal-dialog">
    <form id="contactForm">
      @csrf
      <input type="hidden" name="contact_id" id="contact_id">
      <div class="modal-content">
        <div class="modal-header">
          <h5 id="contactModalTitle">Ajouter contact</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
              <label>Contact</label>
              <select id="contact_selector" name="contact_id" class="form-select" required>
                @foreach(\App\Models\Contact::where('type', '!=', 'client')->get() as $ct)
                  <option value="{{ $ct->id }}">{{ $ct->name }} ({{ $ct->type }})</option>
                @endforeach
              </select>
          </div>
          <div class="mb-3">
              <label>Rôle</label>
              <select name="role" id="role" class="form-select">
                <option value="fournisseur">Fournisseur</option>
                <option value="prestataire">Prestataire</option>
                <option value="ouvrier">Ouvrier</option>
                <option value="autre">Autre</option>
              </select>
          </div>
          <div class="mb-3">
              <label>Tarif horaire</label>
              <input type="number" step="0.01" name="hourly_rate" id="hourly_rate" class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Enregistrer</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        </div>
      </div>
    </form>
  </div>
</div>

{{-- Modal Mouvement --}}
<div class="modal fade" id="movementModal" tabindex="-1">
  <div class="modal-dialog">
    <form id="movementForm">
      @csrf
      <input type="hidden" name="id" id="movement_id">
      <div class="modal-content">
        <div class="modal-header">
          <h5 id="movementModalTitle">Ajouter mouvement</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="project_id" value="{{ $project->id }}">
          <div class="mb-3"><label>Date opération</label><input type="date" name="operation_date" id="operation_date" class="form-control" required></div>
          <div class="mb-3"><label>Type</label><select name="flow_type" id="flow_type" class="form-select"><option value="in">Entrée</option><option value="out">Sortie</option></select></div>
          <div class="mb-3"><label>Methode de paiement</label><select name="payment_method" id="payment_method" class="form-select"><option value="cash">Espèce</option><option value="check">Chèque</option><option value="transfer">Transfert</option><option value="card">Carte</option></select></div>
          <div class="mb-3"><label>Montant</label><input type="number" step="0.01" name="amount" id="amount" class="form-control" required></div>
          <div class="mb-3"><label>Référence</label><input type="text" name="reference" id="reference" class="form-control"></div>
          <div class="mb-3"><label>Categorie de transaction</label><select name="category_id" id="category_id" class="form-select"><option value="">—</option>@foreach($categories as $ct)<option value="{{ $ct->id }}">{{ $ct->name }}</option>@endforeach</select></div>
          <div class="mb-3"><label>Contact (optionnel)</label><select name="contact_id" id="mov_contact_id" class="form-select"><option value="">—</option>@foreach($project->contacts as $ct)<option value="{{ $ct->id }}">{{ $ct->name }}</option>@endforeach</select></div>
          
          <div class="mb-3"><label>Description</label><textarea name="description" id="description" class="form-control"></textarea></div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Enregistrer</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
const projectId = {{ $project->id }};
let contactsTable, movementsTable;
const contactModal = new bootstrap.Modal(document.getElementById('contactModal'));
const movementModal = new bootstrap.Modal(document.getElementById('movementModal'));

function refreshContacts() { contactsTable.ajax.reload(); }
function refreshMovements() { movementsTable.ajax.reload(); }

$(function(){
  contactsTable = $('#contactsTable').DataTable({
    processing:true,serverSide:true,
    ajax:`/projects/${projectId}/contacts/datatable`,
    columns:[
      {data:'pivot.role'},
      {data:'name'},
      {data:'phone'},
      {data:'email'},
      {data:'id',orderable:false,render:id=>`
        <button class="btn btn-sm btn-danger" onclick="detachContact(${id})"><i class="fas fa-trash-alt"></i></button>
      `}
    ],
    language:{url:'//cdn.datatables.net/plug-ins/1.11.5/i18n/fr-FR.json'}
  });

  $('#contactForm').submit(function(e){
    e.preventDefault(); const data = $(this).serialize();
    $.post(`/projects/${projectId}/contacts`, data).done(()=>{
      contactModal.hide(); refreshContacts();
    });
  });

  movementsTable = $('#movementsTable').DataTable({
    processing:true,serverSide:true,
    ajax:`/projects/${projectId}/financial-movements/datatable`,
    columns:[
      {data:'operation_date'},
      {data:'flow_type'},
      {data:'amount',render:d=>`${d} €`},
      {data:'contact_name'},
      {data:'description'},
      {data:'id',orderable:false,render:id=>`
        <button class="btn btn-sm btn-warning me-1" onclick="editMovement(${id})"><i class="fas fa-edit"></i></button>
        <button class="btn btn-sm btn-danger" onclick="deleteMovement(${id})"><i class="fas fa-trash-alt"></i></button>
      `}
    ],
    language:{url:'//cdn.datatables.net/plug-ins/1.11.5/i18n/fr-FR.json'}
  });

  $('#movementForm').submit(function(e){
    e.preventDefault(); const form = $(this), id=$('#movement_id').val();
    const method = id? 'PUT': 'POST';
    const url = id ? `/projects/${projectId}/financial-movements/${id}` : `/projects/${projectId}/financial-movements`;
    $.ajax({url,method,data:form.serialize()}).done(()=>{
      movementModal.hide(); refreshMovements();
    });
  });
});

function openContactModal(){ $('#contactForm')[0].reset();contactModal.show(); }
function detachContact(contactId){
  if(confirm('Retirer ce contact ?')){
    $.ajax({url:`/projects/${projectId}/contacts/${contactId}`,type:'DELETE',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}})
      .done(refreshContacts);
  }
}

function openMovementModal(){
  $('#movementForm')[0].reset();$('#movement_id').val('');
  $('#movementModalTitle').text('Ajouter mouvement');
  movementModal.show();
}

function editMovement(id){
  $.getJSON(`/projects/${projectId}/financial-movements/${id}`,data=>{
    $('#movement_id').val(data.id);
    $('#operation_date').val(data.operation_date);
    $('#flow_type').val(data.flow_type);
    $('#amount').val(data.amount);
    $('#mov_contact_id').val(data.contact_id);
    $('#category_id').val(data.category_id);
    $('#description').val(data.description);
    $('#movementModalTitle').text('Modifier mouvement');
    movementModal.show();
  });
}

function deleteMovement(id){
  if(confirm('Supprimer ce mouvement ?')){
    $.ajax({url:`/projects/${projectId}/financial-movements/${id}`,type:'DELETE',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}})
      .done(refreshMovements);
  }
}
</script>
@endpush
