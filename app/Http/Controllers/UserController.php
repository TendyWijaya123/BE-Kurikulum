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
use Mail;
use App\Mail\UserCreatedMail;
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
            Log:
            info($request->all());
            $randomPassword = Str::random(10);

            $payload = [
                'email' => $request->email,
                'name' => $request->name,
                'prodi_id' => $request->prodi_id,
                'password' => Hash::make($randomPassword),
            ];

            $user = User::create($payload);



            $user->assignRole($request->role);

            Mail::to($user->email)->send(new UserCreatedMail($user, $randomPassword));

            // Commit transaksi jika semua berhasil
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
        try {
            Log::info('Update request data:', $request->all());

            $user = User::findOrFail($id);

            $user->email = $request->email;
            $user->name = $request->name;
            $user->prodi_id = $request->prodi_id;

            if ($request->password) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            $user->syncRoles($request->role);


            return response()->json(['user' => $user, 'message' => 'User Updated Successfully']);
        } catch (\Exception $e) {
            Log::error('Error in Update Method:', ['message' => $e->getMessage()]);
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
