<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use Illuminate\Http\Request;
use App\Models\Dosen;
use App\Models\Prodi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DosenController extends Controller
{
    public function index()
    {
        $jurusan = Jurusan::all(['id', 'nama']);
        $prodi = Prodi::all(['id', 'name', 'jurusan_id']);
        $dosen = Dosen::with(['prodi' => function ($query) {
            $query->select('prodis.id', 'prodis.name');
        }])->get(); 

        return response()->json([
            'prodis' => $prodi,
            'dosens' => $dosen,
            'jurusans' => $jurusan
        ]);
    }


    public function store(Request $request){
        try {
                DB::beginTransaction();

                $data = $request->all();
                $dosen = Dosen::Create(
                    [
                        'kode' => $data['kode'],
                        'nip' => $data['nip'],
                        'nama' => $data['nama'],
                        'email' => $data['email'],
                        'password' => Hash::make('password123'),
                        'jenis_kelamin' => $data['jenisKelamin'],
                        'is_active' => true,
                        'jurusan_id' => $data['jurusan']
                    ]
                );

                $dosen->prodi()->syncWithoutDetaching($data['prodi']);

                DB::commit();

                return response()->json([
                    'success' => 'Data berhasil disimpan',
                ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Terjadi kesalahan saat menyimpan data',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function edit(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validasi request
            $validatedData = $request->validate([
                'id' => 'required|exists:dosens,id',
                'kode' => 'required|string|size:6|unique:dosens,kode,' . $request->id,
                'nip' => 'required|string|unique:dosens,nip,' . $request->id,
                'nama' => 'required|string|max:50',
                'email' => 'required|email|max:50|unique:dosens,email,' . $request->id,
                'jenisKelamin' => 'required|in:L,P',
                'jurusan' => 'required|exists:jurusans,id',
                'prodi' => 'nullable|array',
                'prodi.*' => 'exists:prodis,id',
                'password' => 'nullable|string|min:8',
            ]);

            // Cari dosen berdasarkan ID
            $dosen = Dosen::findOrFail($validatedData['id']);

            // Update data dosen
            $dosen->update([
                'kode' => $validatedData['kode'],
                'nip' => $validatedData['nip'],
                'nama' => $validatedData['nama'],
                'email' => $validatedData['email'],
                'jenis_kelamin' => $validatedData['jenisKelamin'],
                'jurusan_id' => $validatedData['jurusan'],
                // 'is_active' => $validatedData['is_active'] ?? $dosen->is_active, // Default tidak berubah
            ]);

            // Update password jika dikirim dalam request
            if (!empty($validatedData['password'])) {
                $dosen->update([
                    'password' => Hash::make($validatedData['password']),
                ]);
            }

            // Jika ada program studi yang dikirim, sinkronisasi
            if (isset($validatedData['prodi'])) {
                $dosen->prodi()->sync($validatedData['prodi']); // Hapus yang lama, tambahkan yang baru
            }

            DB::commit();

            return response()->json([
                'success' => 'Data dosen berhasil diperbarui',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Terjadi kesalahan saat memperbarui data',
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    public function destroy($id){
        $sksu = Dosen::find($id);
        if (!$sksu) {
            return response()->json([
                'message' => 'role not found.',
            ], 404);
        }
        $sksu->delete();

        return response()->json([
            'message' => `role berhasil dihapus`
        ], 200);
    }

    public function destroyPermissions(Request $request)
    {
        try {
            // Ambil daftar ID dari request
            $data = $request->all();

            if (empty($data) || !is_array($data)) {
                return response()->json([
                    'data' => $data,
                    'message' => 'Harap sertakan daftar ID yang valid untuk dihapus',
                ], 400);
            }

            $ids = array_column($data, '_id');

            // Cari SKSU berdasarkan ID
            $dosens = Dosen::whereIn('id', $ids)->get();

            if ($dosens->isEmpty()) {
                return response()->json([
                    'data' => $ids,
                    'message' => 'Data tidak ditemukan untuk ID yang diberikan',
                ], 404);
            }

            // Loop untuk menghapus data terkait, jika ada
            foreach ($dosens as $dosen){
                // Hapus data SKSU
                $dosen->delete();
            }

            return response()->json([
                'message' => 'Data berhasil dihapus',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'data' => $ids,
                'message' => 'Terjadi kesalahan saat menghapus data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
