<?php

use App\Exports\PenyusunanKurikulum\PenyusunanKurikulumExport;
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
use App\Http\Controllers\NotifikasiController;
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
use App\Http\Controllers\IlmuPengetahuanController;
use App\Http\Controllers\SeniController;
use App\Http\Controllers\TeknologiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JejaringMataKuliahController;
use App\Http\Controllers\PenyusunanKurikulumController;
use App\Http\Controllers\ProfileController;
use App\Imports\MataKuliahImport;
use App\Models\MataKuliah;
use App\Http\Controllers\PetaKompetensiController;
use Illuminate\Support\Facades\Route;
use Mockery\Matcher\Not;

Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('login-dosen', [DosenAuthController::class, 'login'])->name('login_dosen');
Route::get('rps/template/{mataKuliahId}', [RpsMataKuliahController::class, 'exportTemplate']);
Route::post('rps/import/{mataKuliahId}', [RpsMataKuliahController::class, 'import']);
Route::get('penyusunan-kurikulum/export/{kurikulumId}', [PenyusunanKurikulumController::class, 'export']);


Route::get('prodi/dropdown', [ProdiController::class, 'getProdiDropdown']);


Route::middleware(['auth:api'])->group(function () {

    /* ------------------------------------ Users API ------------------------------------------------------- */
    Route::get('users', [UserController::class, 'index'])->middleware('role:P2MPP');
    Route::get('users/roles-dropdown', [UserController::class, 'getRoles'])->middleware('role:P2MPP');
    Route::get('users/{id}', [UserController::class, 'show'])->middleware('role:P2MPP');
    Route::post('users', [UserController::class, 'store'])->middleware('role:P2MPP');
    Route::put('users/{id}', [UserController::class, 'update'])->middleware('role:P2MPP');
    Route::delete('users/{id}', [UserController::class, 'destroy'])->middleware('role:P2MPP');


    /* ------------------------------------ Kurikulum API --------------------------------------------------- */
    Route::get('kurikulum', [KurikulumController::class, 'index'])->middleware('role:P2MPP');
    Route::post('kurikulum', [KurikulumController::class, 'store'])->middleware('role:P2MPP');
    Route::put('kurikulum/{id}', [KurikulumController::class, 'update'])->middleware('role:P2MPP');


    /* ---------------------------------------Prodi API ------------------------------------------------*/

    Route::get('prodis', [ProdiController::class, 'index'])->middleware('role:P2MPP');
    Route::post('prodis', [ProdiController::class, 'store'])->middleware('role:P2MPP');
    Route::get('prodis/{id}', [ProdiController::class, 'show'])->middleware('role:P2MPP');
    Route::put('prodis/{id}', [ProdiController::class, 'update'])->middleware('role:P2MPP');
    Route::delete('prodis/{id}', [ProdiController::class, 'destroy'])->middleware('role:P2MPP');
    Route::get('prodi/dropdown-prodi-kurikulum', [ProdiController::class, 'getProdiWithKurikulumDropdown']);


    /* --------------------------------------Jurusan API--------------------------------------------------- */
    Route::get('jurusans/dropdown', [JurusanController::class, 'dropdown']);
    Route::get('jurusans', [JurusanController::class, 'index'])->middleware('role:P2MPP');
    Route::get('jurusans/dropdown', [JurusanController::class, 'dropdown']);
    Route::post('jurusans', [JurusanController::class, 'store'])->middleware('role:P2MPP');
    Route::get('jurusans/{id}', [JurusanController::class, 'show'])->middleware('role:P2MPP');
    Route::put('jurusans/{id}', [JurusanController::class, 'update'])->middleware('role:P2MPP');
    Route::delete('jurusans/{id}', [JurusanController::class, 'destroy'])->middleware('role:P2MPP');



    /* ---------------------------------------SKSU API --------------------------------------------------*/
    Route::get('sksu', [SksuController::class, 'index'])->middleware('role:P2MPP|Penyusun Kurikulum');
    Route::post('sksu', [SksuController::class, 'store'])->middleware('role:Penyusun Kurikulum');
    Route::get('sksu/template', [SksuController::class, 'downloadTemplate'])->middleware('role:Penyusun Kurikulum');
    Route::post('sksu/import', [SksuController::class, 'import'])->middleware('role:Penyusun Kurikulum');
    Route::delete('sksu/{id}', [SksuController::class, 'destroy'])->middleware('role:Penyusun Kurikulum');
    Route::delete('sksu', [SksuController::class, 'destroySksus'])->middleware('role:Penyusun Kurikulum');

    /* ---------------------------------------Bench Kurikulums API --------------------------------------------------*/
    Route::get('/bench-kurikulums', [BenchKurikulumsController::class, 'index'])->middleware('role:P2MPP|Penyusun Kurikulum');
    Route::post('/bench-kurikulums', [BenchKurikulumsController::class, 'store'])->middleware('role:Penyusun Kurikulum');
    Route::get('bench-kurikulums/template', [BenchKurikulumsController::class, 'downloadTemplate'])->middleware('role:Penyusun Kurikulum');
    Route::post('bench-kurikulums/import', [BenchKurikulumsController::class, 'import'])->middleware('role:Penyusun Kurikulum');
    Route::delete('/bench-kurikulums/{id}', [BenchKurikulumsController::class, 'destroy'])->middleware('role:Penyusun Kurikulum');
    Route::delete('/bench-kurikulums', [BenchKurikulumsController::class, 'destroyBenchKurikulums'])->middleware('role:Penyusun Kurikulum');

    /* ---------------------------------------CPL KKNI API --------------------------------------------------*/
    Route::get('/kkni', [KkniController::class, 'index'])->middleware('role:P2MPP|Penyusun Kurikulum');
    Route::post('/kkni', [KkniController::class, 'store'])->middleware('role:Penyusun Kurikulum');
    Route::get('kkni/template', [KkniController::class, 'downloadTemplate'])->middleware('role:Penyusun Kurikulum');
    Route::get('/kkni/auto', [KkniController::class, 'autoCpl'])->middleware('role:Penyusun Kurikulum');
    Route::post('kkni/import', [KkniController::class, 'import'])->middleware('role:Penyusun Kurikulum');
    Route::delete('/kkni/{id}', [KkniController::class, 'destroy'])->middleware('role:Penyusun Kurikulum');
    Route::delete('/kkni', [KkniController::class, 'destroyCpkKknis'])->middleware('role:Penyusun Kurikulum');

    /* ---------------------------------------Materi Pembelajaran API --------------------------------------------------*/
    Route::get('/materi-pembelajaran', [MateriPembelajaranController::class, 'index'])->middleware('role:P2MPP|Penyusun Kurikulum');
    Route::post('/materi-pembelajaran', [MateriPembelajaranController::class, 'store'])->middleware('role:Penyusun Kurikulum');
    Route::get('materi-pembelajaran/template', [MateriPembelajaranController::class, 'downloadTemplate'])->middleware('role:Penyusun Kurikulum');
    Route::post('materi-pembelajaran/import', [MateriPembelajaranController::class, 'import'])->middleware('role:Penyusun Kurikulum');
    Route::delete('/materi-pembelajaran/{id}', [MateriPembelajaranController::class, 'destroy'])->middleware('role:Penyusun Kurikulum');
    Route::delete('/materi-pembelajaran', [MateriPembelajaranController::class, 'destroyMateriPembelajarans'])->middleware('role:Penyusun Kurikulum');

    /* --------------------------------------Vmt Jurusan API--------------------------------------------------- */
    Route::post('/vmt-jurusans', [VmtJurusanController::class, 'firstOrCreate'])->middleware('role:P2MPP|Penyusun Kurikulum');
    Route::put('/vmt-jurusan/{id}', [VmtJurusanController::class, 'update'])->middleware('role:Penyusun Kurikulum');

    /* --------------------------------------Misi Jurusan API--------------------------------------------------- */
    Route::post('/misi-jurusan/upsert', [MisiJurusanController::class, 'upsert'])->middleware('role:P2MPP|Penyusun Kurikulum');
    Route::delete('/misi-jurusan/delete/{id}', [MisiJurusanController::class, 'delete'])->middleware('role:Penyusun Kurikulum');
    /* --------------------------------------Vmt Polban API--------------------------------------------------- */
    Route::post('/vmt-polban', [VmtPolbanController::class, 'firstOrCreate'])->middleware('role:P2MPP|Penyusun Kurikulum');
    Route::put('/vmt-polban/{id}', [VmtPolbanController::class, 'update'])->middleware('role:Penyusun Kurikulum');


    /* --------------------------------------Tujuan Polban API--------------------------------------------------- */
    Route::post('/tujuan-polban/upsert', [TujuanPolbanController::class, 'upsert'])->middleware('role:P2MPP|Penyusun Kurikulum');
    Route::delete('/tujuan-polban/delete/{id}', [TujuanPolbanController::class, 'delete'])->middleware('role:Penyusun Kurikulum');

    /* --------------------------------------Misi Polban API--------------------------------------------------- */
    Route::post('/misi-polban/upsert', [MisiPolbanController::class, 'upsert'])->middleware('role:P2MPP|Penyusun Kurikulum');
    Route::delete('/misi-polban/delete/{id}', [MisiPolbanController::class, 'delete'])->middleware('role:Penyusun Kurikulum');


    /* --------------------------------------CPL API--------------------------------------------------- */
    Route::get('cpls', [CplController::class, 'index'])->middleware('role:P2MPP|Penyusun Kurikulum');
    Route::post('cpls/upsert', [CplController::class, 'upsert'])->middleware('role:Penyusun Kurikulum');
    Route::get('cpls/template', [CplController::class, 'downloadTemplate'])->middleware('role:Penyusun Kurikulum');
    Route::post('cpls/import', [CplController::class, 'import'])->middleware('role:Penyusun Kurikulum');
    Route::delete('cpls/multiple-delete', [CplController::class, 'destroyCpls'])->middleware('role:Penyusun Kurikulum');
    Route::delete('cpls/{id}', [CplController::class, 'delete'])->middleware('role:Penyusun Kurikulum');


    /* --------------------------------------PPM API--------------------------------------------------- */
    Route::get('ppms', [PpmController::class, 'index'])->middleware('role:P2MPP|Penyusun Kurikulum');
    Route::post('ppms/upsert', [PpmController::class, 'upsert'])->middleware('role:Penyusun Kurikulum');
    Route::get('ppms/template', [PpmController::class, 'downloadTemplate'])->middleware('role:Penyusun Kurikulum');
    Route::post('ppms/import', [PpmController::class, 'import'])->middleware('role:Penyusun Kurikulum');
    Route::delete('ppms/multiple-delete', [PpmController::class, 'destroyPpms'])->middleware('role:Penyusun Kurikulum');
    Route::delete('ppms/{id}', [PpmController::class, 'delete'])->middleware('role:Penyusun Kurikulum');


    /* --------------------------------------Peran Industri API--------------------------------------------------- */

    Route::get('peran-industri', [PeranIndustriController::class, 'index'])->middleware('role:P2MPP|Penyusun Kurikulum');
    Route::post('peran-industri/upsert', [PeranIndustriController::class, 'upsert'])->middleware('role:Penyusun Kurikulum');
    Route::get('peran-industri/template', [PeranIndustriController::class, 'downloadTemplate'])->middleware('role:Penyusun Kurikulum');
    Route::post('peran-industri/import', [PeranIndustriController::class, 'import'])->middleware('role:Penyusun Kurikulum');
    Route::delete('peran-industri/multiple-delete', [PeranIndustriController::class, 'destroyPeranIndustris'])->middleware('role:Penyusun Kurikulum');
    Route::delete('peran-industri/{id}', [PeranIndustriController::class, 'delete'])->middleware('role:Penyusun Kurikulum');

    /* ------------------------------------ IEA API ------------------------------------------------------- */
    Route::get('iea', [IeaController::class, 'index'])->middleware('role:P2MPP|Penyusun Kurikulum')->middleware('role:Penyusun Kurikulum');

    /* ------------------------------------ Matrix Cpl Ppm API ------------------------------------------------------- */
    Route::get('matrix-cpl-ppm', [MatrixCplPpmController::class, 'index'])->middleware('role:P2MPP|Penyusun Kurikulum');
    Route::put('matrix-cpl-ppm', [MatrixCplPpmController::class, 'update'])->middleware('role:Penyusun Kurikulum');

    /* ------------------------------------ Matrix Cpl Iea API ------------------------------------------------------- */
    Route::get('matrix-cpl-iea', [MatrixCplIeaController::class, 'index'])->middleware('role:P2MPP|Penyusun Kurikulum');
    Route::put('matrix-cpl-iea', [MatrixCplIeaController::class, 'update'])->middleware('role:Penyusun Kurikulum');

    /* ------------------------------------ Matrix P MP API ------------------------------------------------------- */
    Route::get('matrix-p-mp', [MatrixPengetahuanMateriPembelajaranController::class, 'index'])->middleware('role:P2MPP|Penyusun Kurikulum');
    Route::put('matrix-p-mp', [MatrixPengetahuanMateriPembelajaranController::class, 'update'])->middleware('role:Penyusun Kurikulum');

    /* ------------------------------------ Matrix MP P MK API ------------------------------------------------------- */
    Route::get('matrix-mp-p-mk', [matriksMpPMkController::class, 'index'])->middleware('role:P2MPP|Penyusun Kurikulum');
    Route::put('matrix-mp-p-mk', [matriksMpPMkController::class, 'update'])->middleware('role:Penyusun Kurikulum');

    /* -------------------------------------Pengetahuan API -------------------------------------------------- */
    Route::get('pengetahuan', [PengetahuanController::class, 'index'])->middleware('role:P2MPP|Penyusun Kurikulum');
    Route::post('pengetahuan/upsert', [PengetahuanController::class, 'upsert'])->middleware('role:Penyusun Kurikulum');
    Route::get('pengetahuan/template', [PengetahuanController::class, 'downloadTemplate'])->middleware('role:Penyusun Kurikulum');
    Route::post('pengetahuan/import', [PengetahuanController::class, 'import'])->middleware('role:Penyusun Kurikulum');
    Route::delete('pengetahuan/{id}', [PengetahuanController::class, 'destroy'])->middleware('role:Penyusun Kurikulum');

    /* -------------------------------------Mata Kuliah API -------------------------------------------------- */
    Route::get('/mata-kuliah', [MataKuliahController::class, 'index'])->middleware('role:P2MPP|Penyusun Kurikulum');
    Route::get('/mata-kuliah/dropdown-by-kurikulum', [MataKuliahController::class, 'dropdownByKurikulum'])->middleware('role:Penyusun Kurikulum');
    Route::post('/mata-kuliah', [MataKuliahController::class, 'store'])->middleware('role:Penyusun Kurikulum');
    Route::put('/mata-kuliah/{id}', [MataKuliahController::class, 'update'])->middleware('role:Penyusun Kurikulum');
    Route::get('mata-kuliah/template', [MataKuliahController::class, 'exportTemplate'])->middleware('role:Penyusun Kurikulum');
    Route::post('mata-kuliah/import', [MataKuliahController::class, 'import'])->middleware('role:Penyusun Kurikulum');
    Route::delete('/mata-kuliah/{id}', [MataKuliahController::class, 'destroy'])->middleware('role:Penyusun Kurikulum');




    /* -------------------------------------Jejaring Mata Kuliah API -------------------------------------------------- */
    Route::get('/jejaring-matakuliah', [JejaringMataKuliahController::class, 'index'])->middleware('role:P2MPP|Penyusun Kurikulum');
    Route::get('/jejaring-matakuliah/jejaring-prasyarat', [JejaringMataKuliahController::class, 'getJejaringData'])->middleware('role:P2MPP|Penyusun Kurikulum');
    Route::post('/jejaring-matakuliah/upload', [JejaringMataKuliahController::class, 'uploadJejaringMKDiagram'])->middleware('role:Penyusun Kurikulum');
    Route::post('/jejaring-matakuliah/assign/{id}', [JejaringMataKuliahController::class, 'updateJejaringMataKuliah'])->middleware('role:Penyusun Kurikulum');


    /* -------------------------------------Bentuk Pembelajaran API -------------------------------------------------- */
    Route::get('bentuk-pembelajaran/dropdown', [BentukPembelajaranController::class, 'dropdown']);

    /* -------------------------------------Metode Pembelajaran API -------------------------------------------------- */
    Route::get('metode-pembelajaran/dropdown', [MetodePembelajaranController::class, 'dropdown']);

    /* -------------------------------------Formulasi CPA API -------------------------------------------------- */
    Route::get('formulasi-cpa/dropdown', [FormulasiCpaController::class, 'dropdown']);

    /* -------------------------------------Matrix Cpl P API -------------------------------------------------- */
    Route::get('matrix-cpl-p', [MatrixCplPController::class, 'index'])->middleware('role:P2MPP|Penyusun Kurikulum');
    Route::put('matrix-cpl-p', [MatrixCplPController::class, 'update'])->middleware('role:Penyusun Kurikulum');


    /* -------------------------------------Matrix Cpl P API -------------------------------------------------- */
    Route::get('matrix-mk-cpl', [MatrixCplMkController::class, 'index'])->middleware('role:P2MPP|Penyusun Kurikulum');
    Route::put('matrix-mk-cpl', [MatrixCplMkController::class, 'update']);

    /* ---------------------------------------Dosen Has Makul API ------------------------------------------------*/

    Route::get('dosen-has-matkul', [DosenHasMatkulController::class, 'index'])->middleware('role:P2MPP|Penyusun Kurikulum');
    Route::post('dosen-has-matkul', [DosenHasMatkulController::class, 'store'])->middleware('role:P2MPP|Penyusun Kurikulum');
    // Route::delete('dosen/{id}', [DosenController::class, 'destroy']);
    // Route::delete('dosen', [DosenController::class, 'destroyDosens']);

    /* --------------------------------------- ipteks API ------------------------------------------------*/

    Route::prefix('ilmu-pengetahuan')->group(function () {
        Route::get('/', [IlmuPengetahuanController::class, 'index'])->middleware('role:P2MPP|Penyusun Kurikulum');
        Route::post('/', [IlmuPengetahuanController::class, 'store'])->middleware('role:Penyusun Kurikulum');
        Route::delete('/{id}', [IlmuPengetahuanController::class, 'destroy'])->middleware('role:Penyusun Kurikulum');
        Route::delete('/', [IlmuPengetahuanController::class, 'destroyMultiple'])->middleware('role:Penyusun Kurikulum');
        Route::delete('/', [IlmuPengetahuanController::class, 'destroyMultiple'])->middleware('role:Penyusun Kurikulum');
        Route::get('/template', [IlmuPengetahuanController::class, 'downloadTemplate'])->middleware('role:Penyusun Kurikulum');
        Route::post('/import', [IlmuPengetahuanController::class, 'import'])->middleware('role:Penyusun Kurikulum');
    });

    Route::prefix('teknologi')->group(function () {
        Route::get('/', [TeknologiController::class, 'index'])->middleware('role:P2MPP|Penyusun Kurikulum');
        Route::post('/', [TeknologiController::class, 'store'])->middleware('role:Penyusun Kurikulum');
        Route::delete('/{id}', [TeknologiController::class, 'destroy'])->middleware('role:Penyusun Kurikulum');
        Route::delete('/', [TeknologiController::class, 'destroyMultiple'])->middleware('role:Penyusun Kurikulum');
        Route::get('/template', [TeknologiController::class, 'downloadTemplate'])->middleware('role:Penyusun Kurikulum');
        Route::post('/import', [TeknologiController::class, 'import'])->middleware('role:Penyusun Kurikulum');
    });

    Route::prefix('seni')->group(function () {
        Route::get('/', [SeniController::class, 'index'])->middleware('role:P2MPP|Penyusun Kurikulum');
        Route::post('/', [SeniController::class, 'store'])->middleware('role:Penyusun Kurikulum');
        Route::delete('/{id}', [SeniController::class, 'destroy'])->middleware('role:Penyusun Kurikulum');
        Route::delete('/', [SeniController::class, 'destroyMultiple'])->middleware('role:Penyusun Kurikulum');
        Route::get('/template', [SeniController::class, 'downloadTemplate'])->middleware('role:Penyusun Kurikulum');
        Route::post('/import', [SeniController::class, 'import'])->middleware('role:Penyusun Kurikulum');
    });

    /* ---------------------------------------Dashboard-----------------------------------------------*/
    Route::get('dashboard/jurusans', [DashboardController::class, 'getJurusans']);
    Route::get('dashboard/prodis', [DashboardController::class, 'getProdis']);
    Route::get('dashboard/proses-curriculum-data', [DashboardController::class, 'getCurriculumData']);
    Route::get('dashboard/get-curriculum-data', [DashboardController::class, 'getProcessedData']);
    Route::get('dashboard/refresh-curriculum-data', [DashboardController::class, 'refreshCache']);
    Route::get('dashboard/progres-curriculum-data', [DashboardController::class, 'getBatchStatus']);
    Route::get('dashboard/get-matakuliah', [DashboardController::class, 'getMatakuliah']);
    Route::get('dashboard/get-matakuliah-detail/{id}', [DashboardController::class, 'getMatakuliahDetail']);

    /* ---------------------------------------------Notifikasi------------------------------------------------------*/
    // Route::get('notifikasi',[NotifikasiController::class, 'index']);
    Route::get('notifikasi',[NotifikasiController::class, 'show']);
    Route::post('notifikasi',[NotifikasiController::class, 'store']);
    Route::put('notifikasi/{id}',[NotifikasiController::class, 'changeStatus']);
    Route::put('notifikasi/change-status',[NotifikasiController::class, 'changeStatus']);

    Route::post('chat/send', [NotifikasiController::class, 'sendChat']);


    Route::get('rps/matkul-dropdown/{id}', [RpsController::class, 'dropdownMatkul']);
    Route::get('me', [AuthController::class, 'me']);

    /* --------------------------------------- Peta Kompetensi API ------------------------------------------------*/
    Route::get('/peta-kompetensi', [PetaKompetensiController::class, 'getByProdi']);
    Route::post('/peta-kompetensi', [PetaKompetensiController::class, 'uploadGambar']);
    Route::delete('/peta-kompetensi/{id}', [PetaKompetensiController::class, 'deleteGambar']);
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
        Route::get('/template', [BukuReferensiController::class, 'downloadTemplate']);
        Route::post('/import', [BukuReferensiController::class, 'import']);
        Route::get('/{id}', [BukuReferensiController::class, 'show']);
        Route::put('/{id}', [BukuReferensiController::class, 'update']);
        Route::delete('/{id}', [BukuReferensiController::class, 'destroy']);
    });

    /* --------------------------------------- MataKuliah(Dosen) API ------------------------------------------------*/

    Route::prefix('mata-kuliah')->group(function () {
        Route::get('/pengampu', [MataKuliahController::class, 'showMataKuliahByDosenPengampu']);
        Route::put('/pengampu/{id}', [MataKuliahController::class, 'updateDeskripsiSingkat']);
        Route::get('/show-jurusan', [MataKuliahController::class, 'showMataKuliahByJurusan']);
        Route::post('/assign-referensi', [MataKuliahController::class, 'assignReferensiKeMataKuliah']);
    });

    Route::prefix('rps')->group(function () {
        Route::get('/{id}', [RpsMataKuliahController::class, 'showRpsMataKuliah']);
        Route::post('', [RpsMataKuliahController::class, 'store']);
        Route::put('/bulk-update', [RpsMataKuliahController::class, 'bulkUpdate']);
        Route::put('/{id}', [RpsMataKuliahController::class, 'update']);
        Route::delete('/{id}', [RpsMataKuliahController::class, 'destroy']);
    });
});

/* --------------------------------------- Profile API ------------------------------------------------*/
Route::group(['middleware' => ['auth:api,dosen']], function () {
    Route::get('profile', [ProfileController::class, 'getProfile']);
    Route::post('profile/update-password', [ProfileController::class, 'updatePassword']);
});

