<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\HebergementController;


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

Route::get('users-all', [UserController::class, 'get_all_users'])
    ->name('get_users');

Route::get('user/{id}', [UserController::class, 'get_user'])
    ->name('get_user');

Route::get('destinations-all', [DestinationController::class, 'get_all_destinations'])
    ->name('get_destinations');

Route::get('destination/{id}', [DestinationController::class, 'get_destination'])
    ->name('get_destination');

Route::get('hebergements-all', [HebergementController::class, 'get_all_hebergements'])
    ->name('get_hebergements');

Route::get('hebergement/{id}', [HebergementController::class, 'get_hebergement'])
    ->name('get_hebergement');

// Posts

Route::post('user', [UserController::class, 'create_user'])
    ->name('create_user');

Route::post('destination', [DestinationController::class, 'create_destination'])
    ->name('create_destination');

Route::post('hebergement', [HebergementController::class, 'create_hebergement'])
    ->name('create_hebergement');

// Puts

Route::put('modify-user/{id}', [UserController::class, 'modify_user'])
    ->name('modify_user');

Route::put('modify-destination/{id}', [DestinationController::class, 'modify_destination'])
    ->name('modify_destination');

Route::put('modify-hebergement/{id}', [HebergementController::class, 'modify_hebergement'])
    ->name('modify_hebergement');

// Deletes

Route::delete('delete-user/{id}', [UserController::class, 'delete_user'])
    ->name('delete_user');

Route::delete('delete-destination/{id}', [DestinationController::class, 'delete_destination'])
    ->name('delete_destination');

Route::delete('delete-hebergement/{id}', [HebergementController::class, 'delete_hebergement'])
    ->name('delete_hebergement');