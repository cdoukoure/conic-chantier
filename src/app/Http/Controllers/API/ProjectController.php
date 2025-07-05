<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Project;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $projects = Project::with([
            'client:id,name',
            'children' => function ($q) {
                $q->where('type', 'chantier')->select('id', 'name', 'parent_id');
            }
        ])
            ->where('type', 'projet')
            ->when($request->filled('client_name'), function ($query) use ($request) {
                $query->whereHas('client', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->client_name . '%');
                });
            })
            ->when($request->filled('start_after'), fn($q) => $q->where('start_date', '>=', $request->start_after))
            ->when($request->filled('start_before'), fn($q) => $q->where('start_date', '<=', $request->start_before))
            ->when($request->filled('end_after'), fn($q) => $q->where('end_date', '>=', $request->end_after))
            ->when($request->filled('end_before'), fn($q) => $q->where('end_date', '<=', $request->end_before))
            ->select('id', 'name', 'start_date', 'end_date', 'budget', 'client_id') // sélection légère
            ->latest()
            ->paginate(15)
            ->appends($request->all());

        if ($request->wantsJson()) {
            // Format JSON enrichi pour API
            $projects->getCollection()->transform(function ($project) {
                return [
                    'id' => $project->id,
                    'name' => $project->name,
                    'client_name' => $project->client->name ?? '-',
                    'start_date' => $project->start_date,
                    'end_date' => $project->end_date,
                    'budget' => $project->budget,
                    'chantier_count' => $project->children->count(),
                    'chantiers' => $project->children->pluck('name')->toArray(),
                ];
            });

            return response()->json($projects);
        }



        $clients = Contact::where('type', 'client')->select('id', 'name')->get();

        return view('projects.index', [
            'clients' => $clients,
            'projects' => $projects,
            'filters' => $request->only([
                'client_name',
                'start_after',
                'start_before',
                'end_after',
                'end_before',
            ]),
        ]);
    }

    public function datatable(Request $request)
    {
        if ($request->ajax()) {
            // $projects = Project::with(['client:id,name', 'chantiers:id,name,parent_id'])
            $projects = Project::with(['client:id,name'])
                ->where('type', 'projet')
                ->select(['id', 'name', 'budget', 'start_date', 'end_date', 'client_id']);

            return DataTables::of($projects)
                ->addColumn('client_name', function ($project) {
                    return $project->client->name ?? '-';
                })
                /*
                ->addColumn('chantier_count', function ($project) {
                    return $project->chantiers->count();
                })
                ->addColumn('chantier_names', function ($project) {
                    return $project->chantiers->pluck('name')->implode(', ');
                })
                //*/
                // ->rawColumns(['chantier_names']) // si HTML dans noms, sinon optionnel
                ->make(true);
        }

        abort(403);
    }

    public function projectChantiersDatatable(Project $project)
    {
        $chantiers = $project->chantiers(); // ->with('client');

        return DataTables::of($chantiers)->make(true);
    }
    public function projectContactsDatatable(Project $project)
    {
        $contacts = $project->contacts()->get();

        return DataTables::of($contacts)
            ->addColumn('role', fn($contact) => ucfirst($contact->pivot->role))
            ->addColumn('hourly_rate', fn($contact) => $contact->pivot->hourly_rate)
            ->addColumn('actions', function ($contact) {
                return view('components.contact-actions', compact('contact'))->render();
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function projectFinancesDatatable(Project $project)
    {
        $finances = $project->financialMovements()->get(); // ->with('client');

        return DataTables::of($finances)->make(true);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Dans store() et update()
        $validatedData = $request->validate(Project::rules($project->id ?? null));
        $project = Project::create($validatedData); // ou update()

        return response()->json([
            'message' => 'Project created successfully',
            'data' => $project
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Project $project)
    {
        // Réponse pour l'API
        if ($request->wantsJson()) {
            return response()->json($project);
        }

        // Réponse pour le web

        if ($project->type == 'chantier') {
            $clients = Contact::whereNot('type', 'client')->select('id', 'name')->get();
            $project->load(['parent.client', 'contacts', 'financialMovements']);

            return view('projects.chantier', [
                'project' => $project,
                'clients' => $clients
            ]);
        }

        return view('projects.detail', [
            'project' => $project
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $validatedData = $request->validate(Project::rules($project->id ?? null));

        $project->update($validatedData);

        return response()->json([
            'message' => 'Project updated successfully',
            'data' => $project->fresh()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        // Vérifier si le contact est utilisé dans des projets
        if ($project->financialMovements()->exists()) {
            return response()->json([
                'message' => 'Cannot delete project associated with financials movements',
                'errors' => ['project' => ['Ce projet est associé à des mouvements financiers']]
            ], 422);
        }

        $project->delete();

        return response()->json([
            'message' => 'Project deleted successfully'
        ]);
    }
}
