<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KurikulumController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login'])->name('login');

Route::middleware(['auth:api'])->group(function () {

    /* ------------------------------------ Users API ------------------------------------------------------- */
    Route::get('users', [UserController::class, 'index'])->middleware(['permission:view-users']);
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::post('users', [UserController::class, 'store']);
    Route::put('users/{id}', [UserController::class, 'update']);


    /* ------------------------------------ Kurikulum API --------------------------------------------------- */
    Route::get('kurikulums', [KurikulumController::class, 'index'])->middleware(['permission:view-kurikulums']);
    Route::post('kurikulums', [KurikulumController::class, 'store']);
    Route::put('kurikulums/{id}', [KurikulumController::class, 'update'])->middleware(['permission:update-kurikulums']);


    /* ---------------------------------------Prodi API ------------------------------------------------*/

    Route::get('prodis', [ProdiController::class, 'index']);
    Route::post('prodis', [ProdiController::class, 'store']);
    Route::get('prodis/{id}', [ProdiController::class, 'show']);
    Route::put('prodis/{id}', [ProdiController::class, 'update']);
    Route::delete('prodis/{id}', [ProdiController::class, 'destroy']);
    Route::get('prodi/dropdown', [ProdiController::class, 'getProdiDropdown']);


    /* ---------------------------------------Role API----------------------------------------------------- */

    Route::get('role/dropdown', [RoleController::class, 'getRoleDropdown']);


    /* --------------------------------------Jurusan API--------------------------------------------------- */
    Route::get('jurusans', [JurusanController::class, 'index']);
    Route::post('jurusans', [JurusanController::class, 'store']);
    Route::get('jurusans/{id}', [JurusanController::class, 'show']);
    Route::put('jurusans/{id}', [JurusanController::class, 'update']);
    Route::delete('jurusans/{id}', [JurusanController::class, 'destroy']);


    Route::get('me', [AuthController::class, 'me'])->middleware(['permission:view-dashboard']);
});
