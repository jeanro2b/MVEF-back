<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\HebergementController;
use App\Http\Controllers\PlanningController;
use App\Http\Controllers\PeriodController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// TODO créer les fonctions dans les controlleurs
// Fonctions users a faire quand au point sur le planning


// TODO ajouter le middleware admin aux routes admin après les 1ers tests
// ->middleware('admin')

// Gets

Route::get('destinations-all', [DestinationController::class, 'get_all_destinations'])
    ->name('get_destinations');

Route::get('destination/{id}', [DestinationController::class, 'get_destination'])
    ->name('get_destination');

Route::get('hebergements-all', [HebergementController::class, 'get_all_hebergements'])
    ->name('get_hebergements');

Route::get('hebergement/{id}', [HebergementController::class, 'get_hebergement'])
    ->name('get_hebergement');


Route::get('clients-all', [UserController::class, 'get_all_clients'])
    ->name('get_clients');

Route::get('client/{id}', [UserController::class, 'get_client'])
    ->name('get_client');


Route::get('plannings-all', [PlanningController::class, 'get_all_plannings'])
    ->name('get_plannings');

Route::get('planning/{id}', [PlanningController::class, 'get_planning'])
    ->name('get_planning');

Route::get('planning-periods/{id}', [PeriodController::class, 'get_planning_periods'])
    ->name('get_planning_periods');

// Posts

Route::post('client', [UserController::class, 'create_client'])
    ->name('create_client');

Route::post('destination', [DestinationController::class, 'create_destination'])
    ->name('create_destination');

Route::post('hebergement', [HebergementController::class, 'create_hebergement'])
    ->name('create_hebergement');

Route::post('planning', [PlanningController::class, 'create_planning'])
    ->name('create_planning');

Route::post('planning-period/{id}', [PeriodController::class, 'create_planning_period'])
    ->name('create_planning_period');

// Puts

Route::put('modify-destination', [DestinationController::class, 'modify_destination'])
    ->name('modify_destination');

Route::put('modify-hebergement', [HebergementController::class, 'modify_hebergement'])
    ->name('modify_hebergement');

Route::put('modify-client', [UserController::class, 'modify_client'])
    ->name('modify_client');

Route::put('modify-planning', [PlanningController::class, 'modify_planning'])
    ->name('modify_planning');

Route::put('modify-planning-period', [PeriodController::class, 'modify_planning_period'])
    ->name('modify_planning_period');

// Deletes

Route::delete('delete-destination/{id}', [DestinationController::class, 'delete_destination'])
    ->name('delete_destination');

Route::delete('delete-hebergement/{id}', [HebergementController::class, 'delete_hebergement'])
    ->name('delete_hebergement');

Route::delete('delete-client/{id}', [UserController::class, 'delete_client'])
    ->name('delete_client');

Route::delete('delete-planning/{id}', [PlanningController::class, 'delete_planning'])
    ->name('delete_planning');

Route::delete('delete-planning-periods/{id}', [PeriodController::class, 'delete_planning_period'])
    ->name('delete_planning_periods');
