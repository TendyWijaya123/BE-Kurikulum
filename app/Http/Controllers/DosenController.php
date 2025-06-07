<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use Illuminate\Http\Request;
use App\Models\Dosen;
use App\Models\Prodi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\DosenCreatedMail;
use Illuminate\Support\Facades\Auth;

class DosenController extends Controller
{
    public function index()
    {
        $dosen = Auth::guard('dosen')->user();

        if (!$dosen) {
            return response()->json(['message' => 'Dosen tidak ditemukan'], 404);
        }

        $jurusan = Jurusan::where('id', $dosen->jurusan_id)->get(['id', 'nama']);
        $prodi = Prodi::where('jurusan_id', $dosen->jurusan_id)->get(['id', 'name', 'jurusan_id']);

        $dosens = Dosen::with([
            'prodi' => function ($query) {
                $query->select('prodis.id', 'prodis.name');
            }
        ])->with('jurusan')->where('jurusan_id', $dosen->jurusan_id)->get();

        return response()->json([
            'prodis' => $prodi,
            'dosens' => $dosens,
            'jurusans' => $jurusan
        ]);
    }


    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'kode' => 'required|string|max:10|unique:dosens,kode',
                'nip' => 'required|string|max:20|unique:dosens,nip',
                'nama' => 'required|string|max:255',
                'email' => 'required|email|unique:dosens,email',
                'jenisKelamin' => 'required|in:L,P',
                'jurusan' => 'required|exists:jurusans,id',
                'prodi' => 'required|array',
                'prodi.*' => 'exists:prodis,id',
                'username' => 'required|string'
            ]);

            $data = $validated;
            $password = Str::random(8);

            $dosen = Dosen::create([
                'kode' => $data['kode'],
                'nip' => $data['nip'],
                'nama' => $data['nama'],
                'email' => $data['email'],
                'username' => $data['username'],
                'password' => Hash::make($password),
                'jenis_kelamin' => $data['jenisKelamin'],
                'is_active' => true,
                'jurusan_id' => $data['jurusan'],
            ]);

            $dosen->prodi()->syncWithoutDetaching($data['prodi']);

            Mail::to($dosen->email)->send(new DosenCreatedMail($dosen, $password));

            DB::commit();

            return response()->json([
                'success' => 'Data berhasil disimpan dan email telah dikirim',
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
                'nip' => 'required|string|size:18|unique:dosens,nip,' . $request->id,
                'nama' => 'required|string|max:50',
                'email' => 'required|max:50|unique:dosens,email,' . $request->id,
                'username' => 'required|string',
                'jenisKelamin' => 'required|in:L,P',
                'jurusan' => 'required|exists:jurusans,id',
                'prodi' => 'nullable|array',
                'prodi.*' => 'exists:prodis,id',
                'password' => 'nullable|string|min:8',
                'isActive' => 'boolean'
            ]);

            // Cari dosen berdasarkan ID
            $dosen = Dosen::findOrFail($validatedData['id']);

            // Update data dosen
            $dosen->update([
                'kode' => $validatedData['kode'],
                'nip' => $validatedData['nip'],
                'nama' => $validatedData['nama'],
                'email' => $validatedData['email'],
                'username' => $validatedData['username'],
                'jenis_kelamin' => $validatedData['jenisKelamin'],
                'jurusan_id' => $validatedData['jurusan'],
                'is_active' => $validatedData['isActive'] ?? $dosen->is_active,
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


    public function destroy($id)
    {
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
            foreach ($dosens as $dosen) {
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
