<?php

use App\Http\Controllers\API\ContactController;
use App\Http\Controllers\API\FinancialMovementCategorieController;
use App\Http\Controllers\API\PhaseController;
use App\Http\Controllers\API\ProjectController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
Route::get('/', function () {
    return view('welcome');
});
//*/
Route::resource('/', ProjectController::class);
// Route::resource('/', ContactController::class);

Route::get('/contacts/datatable', [ContactController::class, 'datatable'])->name('contacts.datatable');
Route::resource('contacts', ContactController::class);

// Route::get('/phases/datatable', [PhaseController::class, 'datatable'])->name('phases.datatable');
// Route::resource('phases', PhaseController::class);
Route::prefix('phases')->controller(PhaseController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/datatable', 'datatable')->name('phases.datatable');
    Route::get('/all', 'all')->name('phases.all');
    Route::get('/{p}', 'show');
    Route::post('/', 'store')->name('phases.store');
    Route::put('/{p}', 'update');
    Route::delete('/{p}', 'destroy');
});

// Route::get('/financial-movement-categories/datatable', [FinancialMovementCategorieController::class, 'datatable'])->name('financial-movement-categories.datatable');
// Route::resource('financial-movement-categories', FinancialMovementCategorieController::class);
Route::prefix('financial-movement-categories')->controller(FinancialMovementCategorieController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/datatable', 'datatable')->name('financial-movement-categories.datatable');
    Route::get('/{p}', 'show');
    Route::post('/', 'store')->name('financial-movement-categories.store');
    Route::put('/{p}', 'update');
    Route::delete('/{p}', 'destroy');
});

Route::get('/projects/datatable', [ProjectController::class, 'datatable'])->name('projects.datatable');
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
