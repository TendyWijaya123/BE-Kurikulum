<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notifikasi;
use App\Models\Prodi;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifikasi = Notifikasi::all();
        return response()->json($notifikasi);
    }

    public function show()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $notifikasi = Notifikasi::whereHas('users', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with(['users' => function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->select('status');
        }])->get();

        if ($notifikasi->isEmpty()) {
            return response()->json(['message' => 'No notifications found for the given user'], 404);
        }

        $notifikasiWithStatus = $notifikasi->map(function ($notif) use ($user) {
            $notif->status = $notif->users->first()->status ?? null;
            unset($notif->users); // Remove the users relation to clean up the response
            return $notif;
        });

        return response()->json($notifikasiWithStatus);
    }

    public function store(Request $request)
    {
        try{
            DB::beginTransaction();
            $request->validate([
                'prodiName' => 'required|string|max:255',
                'cpl' => 'required|array',
                'cpl.*.kode' => 'required|string|max:255',
                'cpl.*.keterangan' => 'required|string|max:255',
                'cpl.*.issues' => 'nullable|string',
            ]);
    
            $prodi = Prodi::where('name', $request->prodiName)->first();
            if (!$prodi) {
                return response()->json(['message' => 'Prodi not found'], 404);
            }

            $users = User::where('prodi_id', $prodi->id)->get();
            if ($users->isEmpty()) {
                return response()->json(['message' => 'No users found for this prodi'], 404);
            }
    
            $notifikasiList = [];
            foreach ($request->cpl as $cpl) {
                $notifikasi = Notifikasi::create([
                    'kode' => $cpl['kode'],
                    'deskripsi' => $cpl['issues'] ?? null,
                    'kategori' => 'CPL',
                    'prodi_id' => $prodi->id,
                ]);
                $notifikasi->users()->attach($users->pluck('id')->toArray());
                $notifikasiList[] = $notifikasi;
            }
            DB::commit();
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to create notifications', 'error' => $e->getMessage()], 500);
        }

        return response()->json($notifikasiList, 201);
    }

    public function destroy($id)
    {
        $notifikasi = Notifikasi::find($id);
        if (!$notifikasi) {
            return response()->json(['message' => 'Notifikasi not found'], 404);
        }
        
        $notifikasi->delete();
        return response()->json(['message' => 'Notifikasi deleted successfully']);
    }

    public function changeStatus(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            $notifikasi = DB::table('notifikasi_user')
                ->where('notifikasi_id', $id)
                ->where('user_id', $user->id)
                ->first();

            if (!$notifikasi) {
                return response()->json(['message' => 'Notifikasi not found for the given user'], 404);
            }

            DB::table('notifikasi_user')
                ->where('notifikasi_id', $id)
                ->where('user_id', $user->id)
                ->update(['status' => 'read']);
        }


        return response()->json(['message' => 'Status updated successfully', 'notifikasi' => $notifikasi]);
    }
}
