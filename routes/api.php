<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BenchKurikulumsController;
use App\Http\Controllers\BentukPembelajaranController;
use App\Http\Controllers\BukuReferensiController;
use App\Http\Controllers\CplController;
use App\Http\Controllers\DosenAuthController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\DosenHasMatkulController;
use App\Http\Controllers\FormulasiCpaController;
use App\Http\Controllers\IeaController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\matriksMpPMkController;
use App\Http\Controllers\MatrixPengetahuanMateriPembelajaranController;
use App\Http\Controllers\RpsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KkniController;
use App\Http\Controllers\KurikulumController;
use App\Http\Controllers\MateriPembelajaranController;
use App\Http\Controllers\MisiJurusanController;
use App\Http\Controllers\MisiPolbanController;
use App\Http\Controllers\PeranIndustriController;
use App\Http\Controllers\PpmController;
use App\Http\Controllers\ProdiController;
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
use App\Http\Controllers\RpsMataKuliahController;
use App\Imports\PeranIndustriImport;
use App\Models\BenchKurikulum;
use App\Models\PeranIndustri;
use App\Models\RpsMataKuliah;
use App\Http\Controllers\IlmuPengetahuanController;
use App\Http\Controllers\SeniController;
use App\Http\Controllers\TeknologiController;
use App\Http\Controllers\DashboardController;

use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('login-dosen', [DosenAuthController::class, 'login'])->name('login_dosen');

Route::middleware(['auth:api'])->group(function () {

    /* ------------------------------------ Users API ------------------------------------------------------- */
    Route::get('users', [UserController::class, 'index']);
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::post('users', [UserController::class, 'store']);
    Route::put('users/{id}', [UserController::class, 'update']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);


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
    Route::get('sksu/template', [SksuController::class, 'downloadTemplate']);
    Route::post('sksu/import', [SksuController::class, 'import']);
    Route::delete('sksu/{id}', [SksuController::class, 'destroy']);
    Route::delete('sksu', [SksuController::class, 'destroySksus']);

    /* ---------------------------------------Bench Kurikulums API --------------------------------------------------*/
    Route::get('/bench-kurikulums', [BenchKurikulumsController::class, 'index']);
    Route::post('/bench-kurikulums', [BenchKurikulumsController::class, 'store']);
    Route::get('bench-kurikulums/template', [BenchKurikulumsController::class, 'downloadTemplate']);
    Route::post('bench-kurikulums/import', [BenchKurikulumsController::class, 'import']);
    Route::delete('/bench-kurikulums/{id}', [BenchKurikulumsController::class, 'destroy']);
    Route::delete('/bench-kurikulums', [BenchKurikulumsController::class, 'destroyBenchKurikulums']);

    /* ---------------------------------------CPL KKNI API --------------------------------------------------*/
    Route::get('/kkni', [KkniController::class, 'index']);
    Route::post('/kkni', [KkniController::class, 'store']);
    Route::get('kkni/template', [KkniController::class, 'downloadTemplate']);
    Route::get('/kkni/auto', [KkniController::class, 'autoCpl']);
    Route::post('kkni/import', [KkniController::class, 'import']);
    Route::delete('/kkni/{id}', [KkniController::class, 'destroy']);
    Route::delete('/kkni', [KkniController::class, 'destroyCpkKknis']);

    /* ---------------------------------------Materi Pembelajaran API --------------------------------------------------*/
    Route::get('/materi-pembelajaran', [MateriPembelajaranController::class, 'index']);
    Route::post('/materi-pembelajaran', [MateriPembelajaranController::class, 'store']);
    Route::get('materi-pembelajaran/template', [MateriPembelajaranController::class, 'downloadTemplate']);
    Route::post('materi-pembelajaran/import', [MateriPembelajaranController::class, 'import']);
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
    Route::get('cpls/template', [CplController::class, 'downloadTemplate']);
    Route::post('cpls/import', [CplController::class, 'import']);
    Route::delete('cpls/multiple-delete', [CplController::class, 'destroyCpls']);

    Route::delete('cpls/{id}', [CplController::class, 'delete']);


    /* --------------------------------------PPM API--------------------------------------------------- */
    Route::get('ppms', [PpmController::class, 'index']);
    Route::post('ppms/upsert', [PpmController::class, 'upsert']);
    Route::get('ppms/template', [PpmController::class, 'downloadTemplate']);
    Route::post('ppms/import', [PpmController::class, 'import']);
    Route::delete('ppms/multiple-delete', [PpmController::class, 'destroyPpms']);
    Route::delete('ppms/{id}', [PpmController::class, 'delete']);


    /* --------------------------------------Peran Industri API--------------------------------------------------- */

    Route::get('peran-industri', [PeranIndustriController::class, 'index']);
    Route::post('peran-industri/upsert', [PeranIndustriController::class, 'upsert']);
    Route::get('peran-industri/template', [PeranIndustriController::class, 'downloadTemplate']);
    Route::post('peran-industri/import', [PeranIndustriController::class, 'import']);
    Route::delete('peran-industri/multiple-delete', [PeranIndustriController::class, 'destroyPeranIndustris']);
    Route::delete('peran-industri/{id}', [PeranIndustriController::class, 'delete']);

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

    /* ------------------------------------ Matrix MP P MK API ------------------------------------------------------- */
    Route::get('matrix-mp-p-mk', [matriksMpPMkController::class, 'index']);
    Route::put('matrix-mp-p-mk', [matriksMpPMkController::class, 'update']);

    /* -------------------------------------Pengetahuan API -------------------------------------------------- */
    Route::get('pengetahuan', [PengetahuanController::class, 'index']);
    Route::post('pengetahuan', [PengetahuanController::class, 'create']);
    Route::get('pengetahuan/template', [PengetahuanController::class, 'downloadTemplate']);
    Route::post('pengetahuan/import', [PengetahuanController::class, 'import']);
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

    /* ---------------------------------------Dosen Has Makul API ------------------------------------------------*/

    Route::get('dosen-has-matkul', [DosenHasMatkulController::class, 'index']);
    Route::post('dosen-has-matkul', [DosenHasMatkulController::class, 'store']);
    // Route::delete('dosen/{id}', [DosenController::class, 'destroy']);
    // Route::delete('dosen', [DosenController::class, 'destroyDosens']);

    /* --------------------------------------- ipteks API ------------------------------------------------*/

    Route::prefix('ilmu-pengetahuan')->group(function () {
        Route::get('/', [IlmuPengetahuanController::class, 'index']);
        Route::post('/', [IlmuPengetahuanController::class, 'store']);
        Route::delete('/{id}', [IlmuPengetahuanController::class, 'destroy']);
        Route::delete('/', [IlmuPengetahuanController::class, 'destroyMultiple']);
        Route::delete('/', [IlmuPengetahuanController::class, 'destroyMultiple']);
        Route::get('/template', [IlmuPengetahuanController::class, 'downloadTemplate']);
        Route::post('/import', [IlmuPengetahuanController::class, 'import']);
    });

    Route::prefix('teknologi')->group(function () {
        Route::get('/', [TeknologiController::class, 'index']);
        Route::post('/', [TeknologiController::class, 'store']);
        Route::delete('/{id}', [TeknologiController::class, 'destroy']);
        Route::delete('/', [TeknologiController::class, 'destroyMultiple']);
        Route::get('/template', [TeknologiController::class, 'downloadTemplate']);
        Route::post('/import', [TeknologiController::class, 'import']);
    });

    Route::prefix('seni')->group(function () {
        Route::get('/', [SeniController::class, 'index']);
        Route::post('/', [SeniController::class, 'store']);
        Route::delete('/{id}', [SeniController::class, 'destroy']);
        Route::delete('/', [SeniController::class, 'destroyMultiple']);
        Route::get('/template', [SeniController::class, 'downloadTemplate']);
        Route::post('/import', [SeniController::class, 'import']);
    });

    /* ---------------------------------------Dashboard-----------------------------------------------*/
    // Route::get('dashboard/jurusans', [DashboardController::class, 'getJurusans']);
    // Route::get('dashboard/prodis', [DashboardController::class, 'getProdis']);
    // Route::get('dashboard/curriculum-data', [DashboardController::class, 'getCurriculumData']);


    Route::get('rps/matkul-dropdown/{id}', [RpsController::class, 'dropdownMatkul']);


    Route::get('me', [AuthController::class, 'me']);
});
Route::middleware(['auth:dosen'])->group(function () {
    /* ---------------------------------------Dosen API ------------------------------------------------*/

    Route::get('dosen', [DosenController::class, 'index']);
    Route::post('dosen', [DosenController::class, 'store']);
    Route::put('dosen', [DosenController::class, 'edit']);
    Route::delete('dosen/{id}', [DosenController::class, 'destroy']);
    Route::delete('dosen', [DosenController::class, 'destroyDosens']);

    Route::get('rps/prodi-dropdown/{id}', [ProdiController::class, 'show']);
    /* --------------------------------------- Buku referensi(Dosen) API ------------------------------------------------*/
    Route::prefix('buku-referensi')->group(function () {
        Route::get('/', [BukuReferensiController::class, 'index']);
        Route::post('/', [BukuReferensiController::class, 'store']);
        Route::get('/dropdown-by-jurusan', [BukuReferensiController::class, 'dropdownBuku']);
        Route::get('/{id}', [BukuReferensiController::class, 'show']);
        Route::put('/{id}', [BukuReferensiController::class, 'update']);
        Route::delete('/{id}', [BukuReferensiController::class, 'destroy']);
    });

    /* --------------------------------------- MataKuliah(Dosen) API ------------------------------------------------*/

    Route::prefix('mata-kuliah')->group(function () {
        Route::get('/pengampu', [MataKuliahController::class, 'showMataKuliahByDosenPengampu']);
        Route::get('/show-jurusan', [MataKuliahController::class, 'showMataKuliahByJurusan']);
        Route::post('/assign-referensi', [MataKuliahController::class, 'assignReferensiKeMataKuliah']);
    });

    Route::prefix('rps')->group(function () {
        Route::get('/{id}', [RpsMataKuliahController::class, 'showRpsMataKuliah']);
        Route::post('', [RpsMataKuliahController::class, 'store']);
        Route::put('/{id}', [RpsMataKuliahController::class, 'update']); // Menambahkan route untuk update
        Route::delete('/{id}', [RpsMataKuliahController::class, 'destroy']); // Menambahkan route untuk delete
    });


});
