<?php

use App\Http\Controllers\API\ContactController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


/* Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');

Route::resource('contacts', ContactController::class)
    ->only(['index', 'store', 'update', 'destroy'])
    ->middleware(['web']); */
    
Route::get('/contacts/datatable', [ContactController::class, 'datatable'])->name('contacts.datatable');

Route::resource('contacts', ContactController::class);
    // ->only(['index', 'store', 'update', 'destroy'])
    // ->middleware(['web']);

/* Route::get('api/contacts', [ContactController::class, 'index'])
    ->middleware(['api']); */

    
