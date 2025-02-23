<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BukuReferensi;
use App\Exports\BukuReferensiTemplateExport;
use App\Imports\BukuReferensiImport;
use Maatwebsite\Excel\Facades\Excel;
class BukuReferensiController extends Controller
{
    /**
     * Menampilkan semua buku referensi sesuai jurusan dosen yang login.
     */
    public function index()
    {
        $user = Auth::guard('dosen')->user();

        if (!$user) {
            return response()->json(['message' => 'User belum login'], 401);
        }

        // Ambil buku hanya dari jurusan user yang login
        $bukuReferensi = BukuReferensi::with('jurusan')
            ->where('jurusan_id', $user->jurusan_id)
            ->get();

        return response()->json($bukuReferensi);
    }

    /**
     * Menyimpan buku referensi baru berdasarkan jurusan user yang login.
     */
    public function store(Request $request)
    {
        $user = Auth::guard('dosen')->user();

        if (!$user) {
            return response()->json(['message' => 'User belum login'], 401);
        }

        $validatedData = $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'penerbit' => 'nullable|string|max:255',
            'tahun_terbit' => 'nullable|integer|min:1900|max:' . date('Y'),
            'isbn' => 'nullable|string|unique:buku_referensi,isbn',
            'bahasa' => 'nullable|string|max:50',
        ]);

        // Tambahkan jurusan_id berdasarkan dosen yang login
        $validatedData['jurusan_id'] = $user->jurusan_id;

        $buku = BukuReferensi::create($validatedData);

        return response()->json(['message' => 'Buku referensi berhasil ditambahkan', 'data' => $buku], 201);
    }

    /**
     * Menampilkan detail buku referensi hanya jika milik jurusan user yang login.
     */
    public function show($id)
    {
        $user = Auth::guard('dosen')->user();

        if (!$user) {
            return response()->json(['message' => 'User belum login'], 401);
        }

        $buku = BukuReferensi::where('id', $id)
            ->where('jurusan_id', $user->jurusan_id)
            ->first();

        if (!$buku) {
            return response()->json(['message' => 'Buku tidak ditemukan atau tidak memiliki akses'], 404);
        }

        return response()->json($buku);
    }

    public function dropdownBuku()
    {
        $user = Auth::guard('dosen')->user();

        if (!$user) {
            return response()->json(['message' => 'User belum login'], 401);
        }

        $buku = BukuReferensi::where('jurusan_id', $user->jurusan_id)
            ->select('id', 'judul')
            ->get();

        return response()->json(['buku' => $buku]);
    }

    /**
     * Memperbarui buku referensi hanya jika milik jurusan user yang login.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::guard('dosen')->user();

        if (!$user) {
            return response()->json(['message' => 'User belum login'], 401);
        }

        $buku = BukuReferensi::where('id', $id)
            ->where('jurusan_id', $user->jurusan_id)
            ->first();

        if (!$buku) {
            return response()->json(['message' => 'Buku tidak ditemukan atau tidak memiliki akses'], 404);
        }

        $validatedData = $request->validate([
            'judul' => 'sometimes|string|max:255',
            'penulis' => 'sometimes|string|max:255',
            'penerbit' => 'nullable|string|max:255',
            'tahun_terbit' => 'nullable|integer|min:1900|max:' . date('Y'),
            'isbn' => 'nullable|string|unique:buku_referensi,isbn,' . $id,
            'bahasa' => 'nullable|string|max:50',
        ]);

        $buku->update($validatedData);

        return response()->json(['message' => 'Buku referensi berhasil diperbarui', 'data' => $buku]);
    }

    /**
     * Menghapus buku referensi hanya jika milik jurusan user yang login.
     */
    public function destroy($id)
    {
        $user = Auth::guard('dosen')->user();

        if (!$user) {
            return response()->json(['message' => 'User belum login'], 401);
        }

        $buku = BukuReferensi::where('id', $id)
            ->where('jurusan_id', $user->jurusan_id)
            ->first();

        if (!$buku) {
            return response()->json(['message' => 'Buku tidak ditemukan atau tidak memiliki akses'], 404);
        }

        $buku->delete();

        return response()->json(['message' => 'Buku referensi berhasil dihapus']);
    }

    public function downloadTemplate()
    {
        $fileName = 'buku_referensi_template.xlsx';
        return Excel::download(new BukuReferensiTemplateExport, $fileName);
    }


    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv'
        ]);

        try {
            Excel::import(new BukuReferensiImport, $request->file('file'));
            return response()->json(['message' => 'Data buku referensi berhasil diimport.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}
