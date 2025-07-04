<?php

namespace App\Http\Controllers\API;

use App\DataTables\ContactsDataTable;
use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class ContactController extends Controller
{
    /**
     * Affiche la vue des contacts
     */
    /**
     * Display a listing of contacts with optional filters.
     */
    public function index(Request $request)
    {
        $contacts = Contact::query()
            ->when($request->type, fn($q, $type) => $q->where('type', $type))
            ->when(
                $request->search,
                fn($q, $search) =>
                $q->where('name', 'like', "%{$search}%")
                    // ->orWhere('company_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
            )
            ->paginate(15);

        // Réponse pour l'API
        if ($request->wantsJson()) {
            return response()->json($contacts);
        }

        // Reponse pour le web
        return view('contacts.index', [
            'contacts' => $contacts,
            'filters' => $request->only(['type', 'search'])
        ]);

    }


    public function datatable(Request $request)
    {
        if ($request->ajax()) {
            $contacts = Contact::select(['id', 'type', 'name', 'email', 'phone', 'address', 'siret']);

            return DataTables::of($contacts)
                ->addColumn('actions', function ($row) {
                    return '<button class="btn btn-sm btn-warning btn-edit" data-id="' . $row->id . '">Éditer</button>';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
    }



    /**
     * Store a newly created contact.
     */
    public function store(Request $request)
    {
        // Dans store() et update()
        $validatedData = $request->validate(Contact::rules($contact->id ?? null));
        $contact = Contact::create($validatedData); // ou update()

        /*
        $validator = Validator::make($request->all(), [
            'type' => ['required', 'string', Rule::in(['client', 'fournisseur', 'prestataire', 'ouvrier', 'autre'])],
            // 'company_name' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:contacts,email',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'siret' => 'nullable|string|size:14',
            'metadata' => 'nullable|json'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $contact = Contact::create($validator->validated());
        //*/

        return response()->json([
            'message' => 'Contact created successfully',
            'data' => $contact
        ], 201);

    }

    /**
     * Display the specified contact.
     */
    public function show(Contact $contact)
    {
        /*
        return response()->json([
            'data' => $contact->load('projects')
        ]);
        //*/
        return response()->json($contact);
    }

    public function edit(Contact $contact)
    {
        return response()->json($contact);
    }

    /**
     * Update the specified contact.
     */
    public function update(Request $request, Contact $contact)
    {
        $validator = Validator::make($request->all(), [
            'type' => ['sometimes', 'string', Rule::in(['client', 'fournisseur', 'prestataire', 'ouvrier', 'autre'])],
            // 'company_name' => 'sometimes|nullable|string|max:255',
            'name' => 'sometimes|string|max:255',
            'email' => [
                'sometimes',
                'nullable',
                'email',
                Rule::unique('contacts')->ignore($contact->id)
            ],
            'phone' => 'sometimes|string|max:20',
            'address' => 'sometimes|nullable|string',
            'siret' => 'sometimes|nullable|string|size:14',
            'metadata' => 'sometimes|nullable|json'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $contact->update($validator->validated());

        return response()->json([
            'message' => 'Contact updated successfully',
            'data' => $contact->fresh()
        ]);
    }

    /**
     * Remove the specified contact.
     */
    public function destroy(Contact $contact)
    {
        // Vérifier si le contact est utilisé dans des projets
        if ($contact->projects()->exists()) {
            return response()->json([
                'message' => 'Cannot delete contact associated with projects',
                'errors' => ['contact' => ['Ce contact est associé à des projets']]
            ], 422);
        }

        $contact->delete();

        return response()->json([
            'message' => 'Contact deleted successfully'
        ]);
    }

    /**
     * Get contact types for dropdown.
     */
    public function types()
    {
        return response()->json([
            'data' => [
                'client' => 'Client',
                'fournisseur' => 'Fournisseur',
                'ouvrier' => 'Ouvrier',
                'prestataire' => 'Prestataire',
                'autre' => 'Autre'
            ]
        ]);
    }
}