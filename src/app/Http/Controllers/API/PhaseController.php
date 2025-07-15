<?php

namespace App\Http\Controllers\API;

use App\Models\Phase;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;

class PhaseController extends Controller
{
    /**
     * Affiche la liste des phases (JSON ou vue).
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return response()->json(
                Phase::with('parent:id,name')
                    ->select('id', 'name', 'parent_id', 'order')
                    ->get()
                    ->map(function ($p) {
                        return [
                            'id' => $p->id,
                            'name' => $p->name,
                            'parent_name' => $p->parent->name ?? '-',
                            'parent_id' => $p->parent_id,
                            'order' => $p->order,
                        ];
                    })
            );
        }

        $phases = Phase::select('id', 'name', 'order')
            ->orderBy('order', 'asc')
            ->orderBy('name', 'asc')
            ->get();

        return view('phases.index', [
            'phases' => $phases,
        ]);
    }

    /**
     * Retourne les données pour DataTables.
     */
    public function datatable(Request $request)
    {
        if (!$request->ajax()) {
            abort(403);
        }

        $phases = Phase::with('parent:id,name')
            ->select('id', 'name', 'description', 'parent_id', 'order')
            ->orderBy('order', 'asc')
            ->orderBy('name', 'asc');

        return DataTables::of($phases)
            ->addColumn('parent_name', fn($p) => $p->parent->name ?? '-')
            // ->addColumn('actions', function ($p) {
            //     return '
            //         <button class="btn btn-sm btn-warning" onclick="editPhase(' . $p->id . ')"><i class="fas fa-edit"></i></button>
            //         <button class="btn btn-sm btn-danger" onclick="deletePhase(' . $p->id . ')"><i class="fas fa-trash-alt"></i></button>
            //     ';
            // })
            // ->rawColumns(['actions'])
            ->make(true);
    }

    /**
     * Stocke une nouvelle phase.
     */
    public function store(Request $request)
    {
        $validated = $request->validate(Phase::rules());

        $phase = Phase::create($validated);

        return response()->json([
            'message' => 'Phase créée avec succès.',
            'data' => $phase
        ], 201);
    }

    /**
     * Affiche les données d'une phase (pour édition).
     */
    public function show(Phase $p)
    {
        return response()->json($p);
    }

    /**
     * Met à jour une phase existante.
     */
    public function update(Request $request, Phase $p)
    {
        $validated = $request->validate(Phase::rules($p->id));

        $p->update($validated);

        return response()->json([
            'message' => 'Phase mise à jour avec succès.',
            'data' => $p->fresh()
        ]);
    }

    /**
     * Supprime une phase.
     */
    public function destroy(Phase $p)
    {
        if ($p->projects()->exists()) {
            return response()->json([
                'message' => 'Impossible de supprimer : phase liée à des projets.',
                'errors' => ['phase' => ['Cette phase est associée à des projets.']]
            ], 422);
        }

        if ($p->children()->exists()) {
            return response()->json([
                'message' => 'Impossible de supprimer : phase liée à d\'autres phases.',
                'errors' => ['phase' => ['Cette phase est associée à d’autres phases.']]
            ], 422);
        }

        $p->delete();

        return response()->json([
            'message' => 'Phase supprimée avec succès.'
        ]);
    }

    /**
     * Liste allégée (id + nom) — utile pour les dropdowns.
     */
    public function all()
    {
        return Phase::select('id', 'name', 'order', 'parent_id')
            ->orderBy('order', 'asc')
            ->orderBy('name', 'asc')
            ->get();
    }
}
