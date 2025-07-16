<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\FinancialMovementCategorie;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class FinancialMovementCategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return response()->json(
                FinancialMovementCategorie::select('id', 'name')
                    ->get()
                    ->map(function ($p) {
                        return [
                            'id' => $p->id,
                            'name' => $p->name,
                        ];
                    })
            );
        }

        $financialMovementCategories = FinancialMovementCategorie::select('id', 'name')
            ->orderBy('name', 'asc')
            ->get();

        return view('financial-movement-categories.index', [
            'categories' => $financialMovementCategories,
        ]);
    }

    public function datatable(Request $request)
    {
        if (!$request->ajax()) {
            abort(403);
        }

        $financialMovementCategories = FinancialMovementCategorie::select('id', 'name', 'description')
            ->orderBy('name', 'asc');

        return DataTables::of($financialMovementCategories)
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate(FinancialMovementCategorie::rules());

        $financialMovementCategorie = FinancialMovementCategorie::create($validated);

        return response()->json([
            'message' => 'FinancialMovementCategorie créée avec succès.',
            'data' => $financialMovementCategorie
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(FinancialMovementCategorie $p)
    {
        return response()->json($p);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FinancialMovementCategorie $p)
    {
        $validated = $request->validate(FinancialMovementCategorie::rules($p->id));

        $p->update($validated);

        return response()->json([
            'message' => 'FinancialMovementCategorie mise à jour avec succès.',
            'data' => $p->fresh()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FinancialMovementCategorie $p)
    {
        if ($p->financialMovements()->exists()) {
            return response()->json([
                'message' => 'Impossible de supprimer : financialMovementCategorie liée à des projets.',
                'errors' => ['financialMovementCategorie' => ['Cette financialMovementCategorie est associée d\'autres entitées.']]
            ], 422);
        }

        $p->delete();

        return response()->json([
            'message' => 'FinancialMovementCategorie supprimée avec succès.'
        ]);
    }
}
