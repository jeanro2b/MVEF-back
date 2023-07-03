<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\HebergementController;
use App\Http\Controllers\PeriodController;
use App\Http\Controllers\PlanningController;
use App\Http\Controllers\UserController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

// TODO ajouter le middleware admin aux routes admin après les 1ers tests
// ->middleware('admin')

// Gets

Route::get('/destinations-all', [DestinationController::class, 'get_all_destinations'])
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

Route::get('plannings-client/{id}', [PlanningController::class, 'get_planning_client'])
    ->name('get_planning_client');

Route::get('planning/{id}', [PlanningController::class, 'get_planning'])
    ->name('get_planning');

Route::get('planning-periods/{id}', [PeriodController::class, 'get_planning_periods'])
    ->name('get_planning_periods');

Route::get('planning-periods-all', [PeriodController::class, 'get_planning_periods_all'])
    ->name('get_planning_periods_all');


Route::get('files-client/{id}', [DocumentController::class, 'get_files_client'])
    ->name('get_files_client');


Route::get('download/{path}', [DocumentController::class, 'download_file'])
    ->name('download_file');

// Posts

Route::post('client', [UserController::class, 'create_client'])
    ->name('create_client');

Route::post('client-files/{id}', [DocumentController::class, 'post_client_files'])
    ->name('post_client_files');


Route::post('destination', [DestinationController::class, 'create_destination'])
    ->name('create_destination');

Route::post('hebergement', [HebergementController::class, 'create_hebergement'])
    ->name('create_hebergement');

Route::post('planning', [PlanningController::class, 'create_planning'])
    ->name('create_planning');

Route::post('planning-period/{id}', [PeriodController::class, 'create_planning_period'])
    ->name('create_planning_period');


Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->name('login');

// Puts

Route::post('modify-destination', [DestinationController::class, 'modify_destination'])
    ->name('modify_destination');

Route::post('modify-hebergement', [HebergementController::class, 'modify_hebergement'])
    ->name('modify_hebergement');

Route::put('modify-client', [UserController::class, 'modify_client'])
    ->name('modify_client');

Route::put('modify-planning', [PlanningController::class, 'modify_planning'])
    ->name('modify_planning');

Route::put('modify-planning-period', [PeriodController::class, 'modify_planning_period'])
    ->name('modify_planning_period');


Route::put('admin-modify-planning-period', [PeriodController::class, 'admin_modify_planning_period'])
    ->name('admin_modify_planning_period');

// Deletes

Route::delete('/delete-destination/{id}', [DestinationController::class, 'delete_destination'])
    ->name('delete_destination');

Route::delete('delete-hebergement/{id}', [HebergementController::class, 'delete_hebergement'])
    ->name('delete_hebergement');

Route::delete('delete-client/{id}', [UserController::class, 'delete_client'])
    ->name('delete_client');

Route::delete('delete-planning/{id}', [PlanningController::class, 'delete_planning'])
    ->name('delete_planning');

Route::delete('delete-planning-periods/{id}', [PeriodController::class, 'delete_planning_period'])
    ->name('delete_planning_periods');

Route::delete('delete-file-client/{fileId}/{id}', [DocumentController::class, 'delete_file_client'])
    ->name('delete_file_client');



Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // Route protégée nécessitant une authentification
    Route::post('/logout', [UserController::class, 'logout']);
    Route::get('/user', [UserController::class, 'getUser']);
});
