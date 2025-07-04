<?php

use App\Http\Controllers\API\ContactController;
use App\Http\Controllers\API\ProjectController;
use App\Http\Controllers\API\ProjectContactController;
use App\Http\Controllers\API\FinancialMovementCategorieController;
use App\Http\Controllers\API\FinancialMovementController;

Route::apiResource('api/contacts', ContactController::class);
Route::get('contact-types', [ContactController::class, 'types']);

Route::apiResource('api/projects', ProjectController::class);
Route::apiResource('api/project-contacts', ProjectContactController::class);
Route::apiResource('api/financial-movement-categories', FinancialMovementCategorieController::class);
Route::apiResource('api/financial-movements', FinancialMovementController::class);
