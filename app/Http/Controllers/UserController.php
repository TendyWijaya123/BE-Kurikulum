<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCreatedMail;
use App\Mail\UserUpdatedMail;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->paginate(10);

        return response()->json($users);
    }


    public function store(StoreUserRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $randomPassword = Str::random(10);

            $payload = [
                'email' => $data['email'],
                'name' => $data['name'],
                'prodi_id' => $data['prodi_id'],
                'password' => Hash::make($randomPassword),
            ];

            $user = User::create($payload);
            $user->assignRole($data['role']);

            Mail::to($user->email)->send(new UserCreatedMail($user, $randomPassword));

            DB::commit();

            return response()->json(['user' => $user, 'message' => 'User Created Successfully'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in Store Method:', ['message' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    public function getRoles()
    {
        $roles = Role::where('guard_name', 'user')->pluck('name');


        return response()->json(['roles' => $roles]);
    }



    public function show($id)
    {
        try {
            Log::info('Show method started');

            $user = User::findOrFail($id);
            $user->role = $user->getRoleNames()->first();

            Log::info('User fetched:', ['user' => $user]);

            return response()->json(['user' => $user]);
        } catch (\Exception $e) {
            Log::error('Error in Show Method:', ['message' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    public function update(UpdateUserRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();

            $user = User::findOrFail($id);

            $user->email = $data['email'];
            $user->name = $data['name'];
            $user->prodi_id = $data['prodi_id'];

            $newPassword = null;
            $passwordChanged = false;

            // Hanya update password jika field password diisi
            if (!empty($data['password'])) {
                if (Hash::check($data['password'], $user->password)) {
                    return response()->json(['error' => 'Password baru tidak boleh sama dengan password sebelumnya.'], 422);
                }
                $newPassword = $data['password'];
                $user->password = Hash::make($newPassword);
                $passwordChanged = true;
            }

            $user->save();
            $user->syncRoles($data['role']);

            // Kirim email jika password diubah
            if ($passwordChanged && $newPassword) {
                try {
                    Mail::to($user->email)->send(new UserUpdatedMail($user, $newPassword));
                } catch (\Exception $emailException) {
                    Log::error('Failed to send password update email: ' . $emailException->getMessage());
                    Log::error('Email error trace: ' . $emailException->getTraceAsString());
                }
            }

            DB::commit();

            $message = 'User Updated Successfully';
            if ($passwordChanged) {
                $message .= ' and password update email has been sent';
            }

            return response()->json([
                'user' => $user->fresh(),
                'message' => $message,
                'password_changed' => $passwordChanged
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in Update Method:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            Log::info('Delete method started');

            // Find user by id
            $user = User::findOrFail($id);

            // Delete the user
            $user->delete();

            Log::info('User deleted:', ['user' => $user]);

            return response()->json(['message' => 'User Deleted Successfully']);
        } catch (\Exception $e) {
            Log::error('Error in Delete Method:', ['message' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
