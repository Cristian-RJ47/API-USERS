<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function getAllUsers(Request $request){

        $pag = User::paginate(10);

        if ($pag->isEmpty()) {
            return response()->json([
                'response'=>'error',
                'message'=>'no users found'
            ],404);
        }

        if ($pag->currentPage() > $pag->lastPage()) {
            return response()->json([
                'response'=>'error',
                'message'=>'page not found'
            ],404);
        }

        $totalUsers = User::count();
        if ($totalUsers === 0) {
            return response()->json([
                'response' => 'error',
                'message' => 'no users found'
            ], 404);
        }
        
        return response()->json([
            'response'=>'get all users',
            'data'=>[
                'users' => $pag->items(),
            ],
        ],200);
    }

    public function createUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $email = $request->email;
        $user = User::where('email', $email)->first();

        if ($user) {
            return response()->json([
                'message' => 'email error',
                'error' => 'email already exists'
            ], 400);
        }

        $user = new User();
        $user->name = $request->name;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json([
            'response' => 'User created successfully',
            'data' => $user
        ], 201);
    }
    
    public function getUserById(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
                'error' => 'error description'
            ], 404);
        }

        return response()->json([
            'response' => 'User found',
            'data' => $user
        ], 200);
    }

    public function deleteUser(Request $request, string $id){

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        $user->disabled = true;


        return response()->json([
            'response' => 'User deleted successfully'
        ], 200);
    }

    public function updateUser(Request $request, string $id){

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        $request->validate([
            'name' => 'required',
            'lastname' => 'required',
        ]);

        $user->name = $request->name;
        $user->lastname = $request->lastname;
        $user->save();

        return response()->json([
            'response' => 'User updated successfully',
            'data' => $user
        ], 200);
    }
}
