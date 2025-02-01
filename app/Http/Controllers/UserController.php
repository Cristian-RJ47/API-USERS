<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * @OA\Info(title="User API", version="1.0")
 */
class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/users",
     *     summary="Get all users",
     *     @OA\Response(response=200, description="List of users"),
     *     @OA\Response(response=404, description="No users found")
     * )
     */
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

        return response()->json([
            'response'=>'get all users',
            'data'=>[
                'users' => $pag->items(),
            ],
        ],200);
    }

    /**
     * @OA\Post(
     *     path="/users",
     *     summary="Create a new user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "lastname", "email", "password"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="lastname", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string", format="password")
     *         )
     *     ),
     *     @OA\Response(response=201, description="User created successfully"),
     *     @OA\Response(response=400, description="Email already exists")
     * )
     */
    public function createUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

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

    /**
     * @OA\Get(
     *     path="/users/{id}",
     *     summary="Get user by ID",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="User found"),
     *     @OA\Response(response=404, description="User not found")
     * )
     */
    public function getUserById(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'response' => 'User found',
            'data' => $user
        ], 200);
    }

    /**
     * @OA\Delete(
     *     path="/users/{id}",
     *     summary="Delete user",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="User deleted successfully"),
     *     @OA\Response(response=404, description="User not found")
     * )
     */
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

    /**
     * @OA\Put(
     *     path="/users/{id}",
     *     summary="Update user",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "lastname"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="lastname", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="User updated successfully"),
     *     @OA\Response(response=404, description="User not found")
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/login",
     *     summary="User login",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string", format="password")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Login successful"),
     *     @OA\Response(response=401, description="Invalid credentials")
     * )
     */
    public function login(Request $request){
        $credentials = $request->only('email','password');

        $user = User::where('email', $credentials['email'])->first();

        if(!$user){
            return response()->json([
                'response' => 'invalid credentials',
            ], 401);
        }

        if(!password_verify($credentials['password'], $user->password)){
            return response()->json([
                'response' => 'invalid credentials',
            ], 401);
        }

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'response' => 'login successful',
            'data' => 'token: '.$token
        ], 201);
    }
}
