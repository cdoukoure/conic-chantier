<?php

use App\Http\Controllers\API\ContactController;
use App\Http\Controllers\API\ProjectController;
use Illuminate\Support\Facades\Route;

/*
Route::get('/', function () {
    return view('welcome');
});
//*/


/* Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');

Route::resource('contacts', ContactController::class)
    ->only(['index', 'store', 'update', 'destroy'])
    ->middleware(['web']); */


Route::resource('/', ContactController::class);
Route::get('/contacts/datatable', [ContactController::class, 'datatable'])->name('contacts.datatable');

Route::resource('contacts', ContactController::class);
    // ->only(['index', 'store', 'update', 'destroy'])
    // ->middleware(['web']);

/* Route::get('api/contacts', [ContactController::class, 'index'])
    ->middleware(['api']); */

Route::get('/projects/datatable', [ProjectController::class, 'datatable'])->name('projects.datatable');
// Route::get('/projects/{project}/chantiers/datatable', [ProjectController::class, 'projectChantiersDatatable'])->name('projects.projectChantiersDatatable');
// Route::get('/projects/{project}/contacts/datatable', [ProjectController::class, 'projectContactsDatatable'])->name('projects.projectContactsDatatable');
// Route::get('/projects/{project}/financial-movements/datatable', [ProjectController::class, 'projectFinancesDatatable'])->name('projects.projectFinancesDatatable');
Route::prefix('projects/{project}')->group(function () {
    Route::get('chantiers/datatable', [ProjectController::class, 'projectChantiersDatatable'])->name('projects.projectChantiersDatatable');

    Route::get('contacts/datatable', [ProjectController::class, 'projectContactsDatatable']);
    Route::post('contacts', [ProjectController::class, 'attachContact'])->name('projects.contacts.attach');
    Route::delete('contacts/{contact}', [ProjectController::class, 'detachContact']);

    Route::get('financial-movements/datatable', [ProjectController::class, 'projectFinancesDatatable']);
    Route::post('financial-movements', [ProjectController::class, 'storeFinancialMovement']);
    Route::get('financial-movements/{movement}', [ProjectController::class, 'showFinancialMovement']);
    Route::put('financial-movements/{movement}', [ProjectController::class, 'updateFinancialMovement']);
    Route::delete('financial-movements/{movement}', [ProjectController::class, 'deleteFinancialMovement']);
});

Route::resource('projects', ProjectController::class);

    
