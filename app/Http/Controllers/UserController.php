<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->paginate(10);

        return response()->json($users);
    }

    public function store(CreateUserRequest $request)
    {
        try {
            $payload = [
                'email' => $request->email,
                'name' => $request->name,
                'prodi_id' => $request->prodi_id,
            ];
            $payload['password'] = Hash::make($request->password);
            $user = User::create($payload);
            return response()->json(['user' => $user, 'message' => 'User Created Successfully'], 201);
        } catch (\Exception $e) {
            Log::error('Error in Store Method:', ['message' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    /**
     * Show user by ID.
     */
    public function show($id)
    {
        try {
            Log::info('Show method started');

            $user = User::findOrFail($id);

            Log::info('User fetched:', ['user' => $user]);

            return response()->json(['user' => $user]);
        } catch (\Exception $e) {
            Log::error('Error in Show Method:', ['message' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update an existing user.
     */
    public function update(Request $request, $id)
    {
        try {
            Log::info('Update method started');

            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'name' => 'required|string',
                'prodi_id' => 'required|exists:prodis,id',
                'password' => 'nullable|min:8',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $user = User::findOrFail($id);

            $user->email = $request->email;
            $user->name = $request->name;
            $user->prodi_id = $request->prodi_id;

            if ($request->password) {
                $user->password = Hash::make($request->password); // Hash the password if provided
            }

            // Save the updated user
            $user->save();

            Log::info('User updated:', ['user' => $user]);

            return response()->json(['user' => $user, 'message' => 'User Updated Successfully']);
        } catch (\Exception $e) {
            Log::error('Error in Update Method:', ['message' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete a user.
     */
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
