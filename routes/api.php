<?php

use App\Http\Controllers\InvitationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::post('/user/register', [UserController::class, 'register']);
Route::post('/user/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('user/store_device_key', [UserController::class, 'storeDeviceKey']);
    Route::get('/user', [UserController::class, 'show']);
    Route::get('/user/name/{name}', [UserController::class, 'getUserByNameOrEmail']);
    Route::get('/team', [TeamController::class, 'getTeamsByName']);
    Route::post('/team', [TeamController::class, 'create']);
    Route::post('/user/invitation', [InvitationController::class, 'createFromUser']);
    Route::put('/user/invitation/{id}', [InvitationController::class, 'manageTeamInvitation']);
});

Route::middleware(['auth:sanctum', 'isTeamMember'])->group(function () {
    Route::get('/team/{id}', [TeamController::class, 'show']);
    Route::delete('/user/team/{id}', [UserController::class, 'leaveTeam']);
    Route::post('/team/{id}/post', [PostController::class, 'create']);
    Route::delete('/post/{id}', [PostController::class, 'delete']);
});

Route::middleware(['auth:sanctum', 'isTeamAdmin'])->group(function () {
    Route::put('/team/{id}', [TeamController::class, 'update']);
    Route::put('/team/{id}/admin', [TeamController::class, 'manageAdmin']);
    Route::delete('/team/{id}/member', [TeamController::class, 'removeMember']);
    Route::delete('/team/{id}', [TeamController::class, 'delete']);
    Route::post('/team/{id}/invitation', [InvitationController::class, 'createFromTeam']);
    Route::put('/team/{id}/invitation', [InvitationController::class, 'manageUserInvitation']);
});
