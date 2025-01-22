<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BenchKurikulumsController;
use App\Http\Controllers\BentukPembelajaranController;
use App\Http\Controllers\CplController;
use App\Http\Controllers\FormulasiCpaController;
use App\Http\Controllers\IeaController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\MatrixPengetahuanMateriPembelajaranController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KkniController;
use App\Http\Controllers\KurikulumController;
use App\Http\Controllers\MateriPembelajaranController;
use App\Http\Controllers\MisiJurusanController;
use App\Http\Controllers\MisiPolbanController;
use App\Http\Controllers\PeranIndustriController;
use App\Http\Controllers\PpmController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SksuController;
use App\Http\Controllers\TujuanPolbanController;
use App\Http\Controllers\VmtJurusanController;
use App\Http\Controllers\VmtPolbanController;
use App\Http\Controllers\IpteksController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\MatrixCplPpmController;
use App\Http\Controllers\MatrixCplIeaController;
use App\Http\Controllers\MatrixCplMkController;
use App\Http\Controllers\MetodePembelajaranController;
use App\Http\Controllers\PengetahuanController;
use App\Http\Controllers\MatrixCplPController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login'])->name('login');

Route::middleware(['auth:api'])->group(function () {

    /* ------------------------------------ Users API ------------------------------------------------------- */
    Route::get('users', [UserController::class, 'index'])->middleware(['permission:view-users']);
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::post('users', [UserController::class, 'store']);
    Route::put('users/{id}', [UserController::class, 'update']);


    /* ------------------------------------ Kurikulum API --------------------------------------------------- */
    Route::get('kurikulum', [KurikulumController::class, 'index']);
    Route::post('kurikulum', [KurikulumController::class, 'store']);
    Route::put('kurikulum/{id}', [KurikulumController::class, 'update']);


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
    Route::get('jurusans/dropdown', [JurusanController::class, 'dropdown']);
    Route::get('jurusans/dropdown', [JurusanController::class, 'dropdown']);
    Route::post('jurusans', [JurusanController::class, 'store']);
    Route::get('jurusans/{id}', [JurusanController::class, 'show']);
    Route::put('jurusans/{id}', [JurusanController::class, 'update']);
    Route::delete('jurusans/{id}', [JurusanController::class, 'destroy']);

    /* ---------------------------------------SKSU API --------------------------------------------------*/
    Route::get('sksu', [SksuController::class, 'index']);
    Route::post('sksu', [SksuController::class, 'store']);
    Route::delete('sksu/{id}', [SksuController::class, 'destroy']);
    Route::delete('sksu', [SksuController::class, 'destroySksus']);

    /* ---------------------------------------Bench Kurikulums API --------------------------------------------------*/
    Route::get('/bench-kurikulums', [BenchKurikulumsController::class, 'index']);
    Route::post('/bench-kurikulums', [BenchKurikulumsController::class, 'store']);
    Route::delete('/bench-kurikulums/{id}', [BenchKurikulumsController::class, 'destroy']);
    Route::delete('/bench-kurikulums', [BenchKurikulumsController::class, 'destroyBenchKurikulums']);

    /* ---------------------------------------CPL KKNI API --------------------------------------------------*/
    Route::get('/kkni', [KkniController::class, 'index']);
    Route::post('/kkni', [KkniController::class, 'store']);
    Route::delete('/kkni/{id}', [KkniController::class, 'destroy']);
    Route::delete('/kkni', [KkniController::class, 'destroyCpkKknis']);

    /* ---------------------------------------Materi Pembelajaran API --------------------------------------------------*/
    Route::get('/materi-pembelajaran', [MateriPembelajaranController::class, 'index']);
    Route::post('/materi-pembelajaran', [MateriPembelajaranController::class, 'store']);
    Route::delete('/materi-pembelajaran/{id}', [MateriPembelajaranController::class, 'destroy']);
    Route::delete('/materi-pembelajaran', [MateriPembelajaranController::class, 'destroyMateriPembelajarans']);

    /* --------------------------------------Vmt Jurusan API--------------------------------------------------- */
    Route::post('/vmt-jurusans', [VmtJurusanController::class, 'firstOrCreate']);
    Route::put('/vmt-jurusan/{id}', [VmtJurusanController::class, 'update']);

    /* --------------------------------------Misi Jurusan API--------------------------------------------------- */
    Route::post('/misi-jurusan/upsert', [MisiJurusanController::class, 'upsert']);
    Route::delete('/misi-jurusan/delete/{id}', [MisiJurusanController::class, 'delete']);
    /* --------------------------------------Vmt Polban API--------------------------------------------------- */
    Route::post('/vmt-polban', [VmtPolbanController::class, 'firstOrCreate']);
    Route::put('/vmt-polban/{id}', [VmtPolbanController::class, 'update']);


    /* --------------------------------------Tujuan Polban API--------------------------------------------------- */
    Route::post('/tujuan-polban/upsert', [TujuanPolbanController::class, 'upsert']);
    Route::delete('/tujuan-polban/delete/{id}', [TujuanPolbanController::class, 'delete']);

    /* --------------------------------------Misi Polban API--------------------------------------------------- */
    Route::post('/misi-polban/upsert', [MisiPolbanController::class, 'upsert']);
    Route::delete('/misi-polban/delete/{id}', [MisiPolbanController::class, 'delete']);


    /* --------------------------------------CPL API--------------------------------------------------- */
    Route::get('cpls', [CplController::class, 'index']);
    Route::post('cpls/upsert', [CplController::class, 'upsert']);
    Route::delete('cpls/{id}', [CplController::class, 'delete']);

    /* --------------------------------------PPM API--------------------------------------------------- */
    Route::get('ppms', [PpmController::class, 'index']);
    Route::post('ppms/upsert', [PpmController::class, 'upsert']);
    Route::delete('ppms/{id}', [PpmController::class, 'delete']);


    /* --------------------------------------Peran Industri API--------------------------------------------------- */

    Route::get('peran-industri', [PeranIndustriController::class, 'index']);
    Route::post('peran-industri', [PeranIndustriController::class, 'store']);
    Route::put('peran-industri/{id}', [PeranIndustriController::class, 'update']);
    Route::delete('peran-industri/{id}', [PeranIndustriController::class, 'destroy']);
    /* ------------------------------------ Ipteks API ------------------------------------------------------- */
    Route::get('ipteks', [IpteksController::class, 'index']);
    Route::post('ipteks/{type}', [IpteksController::class, 'create']);
    Route::put('ipteks/{type}/{id}', [IpteksController::class, 'update']);
    Route::delete('ipteks/{type}/{id}', [IpteksController::class, 'destroy']);

    /* ------------------------------------ IEA API ------------------------------------------------------- */
    Route::get('iea', [IeaController::class, 'index']);

    /* ------------------------------------ Matrix Cpl Ppm API ------------------------------------------------------- */
    Route::get('matrix-cpl-ppm', [MatrixCplPpmController::class, 'index']);
    Route::put('matrix-cpl-ppm', [MatrixCplPpmController::class, 'update']);

    /* ------------------------------------ Matrix Cpl Iea API ------------------------------------------------------- */
    Route::get('matrix-cpl-iea', [MatrixCplIeaController::class, 'index']);
    Route::put('matrix-cpl-iea', [MatrixCplIeaController::class, 'update']);

    /* ------------------------------------ Matrix P MP API ------------------------------------------------------- */
    Route::get('matrix-p-mp', [MatrixPengetahuanMateriPembelajaranController::class, 'index']);
    Route::put('matrix-p-mp', [MatrixPengetahuanMateriPembelajaranController::class, 'update']);

    /* -------------------------------------Pengetahuan API -------------------------------------------------- */
    Route::get('pengetahuan', [PengetahuanController::class, 'index']);
    Route::post('pengetahuan', [PengetahuanController::class, 'create']);
    Route::put('pengetahuan/{id}', [PengetahuanController::class, 'update']);
    Route::delete('pengetahuan/{id}', [PengetahuanController::class, 'destroy']);

    /* -------------------------------------Mata Kuliah API -------------------------------------------------- */
    Route::get('/mata-kuliah', [MataKuliahController::class, 'index']);
    Route::post('/mata-kuliah', [MataKuliahController::class, 'store']);
    Route::put('/mata-kuliah/{id}', [MataKuliahController::class, 'update']);
    Route::delete('/mata-kuliah/{id}', [MataKuliahController::class, 'destroy']);


    /* -------------------------------------Bentuk Pembelajaran API -------------------------------------------------- */
    Route::get('bentuk-pembelajaran/dropdown', [BentukPembelajaranController::class, 'dropdown']);

    /* -------------------------------------Metode Pembelajaran API -------------------------------------------------- */
    Route::get('metode-pembelajaran/dropdown', [MetodePembelajaranController::class, 'dropdown']);

    /* -------------------------------------Formulasi CPA API -------------------------------------------------- */
    Route::get('formulasi-cpa/dropdown', [FormulasiCpaController::class, 'dropdown']);

    /* -------------------------------------Matrix Cpl P API -------------------------------------------------- */
    Route::get('matrix-cpl-p', [MatrixCplPController::class, 'index']);
    Route::put('matrix-cpl-p', [MatrixCplPController::class, 'update']);


    /* -------------------------------------Matrix Cpl P API -------------------------------------------------- */
    Route::get('matrix-mk-cpl', [MatrixCplMkController::class, 'index']);
    Route::put('matrix-mk-cpl', [MatrixCplMkController::class, 'update']);


    Route::get('me', [AuthController::class, 'me'])->middleware(['permission:view-dashboard']);
});
