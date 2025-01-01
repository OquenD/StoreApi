<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthControlller extends Controller
{
    public function register(Request $request) 
    {
        //Data validation
        $validatedData = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8'
        ]);

        //Save user
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']) //Encrypt password
        ]);

        return response()->json(
            [
                'user' => $user,
                'message' => 'User Registered Successfully'
            ], 201);
    }

    public function login(Request $request) 
    {
        //Data validation
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        //Get user by email
        $user = User::where('email', $request->email)->first();

        //Check if the user does not exist and if the passwords received are not equal to the one hashed in the database.
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'The provided credentials are incorrect'], 401);
        }

        // If the credentials are correct, the token is generated
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'token' => $token
        ]);
    }

    /**
     * Get authenticated user profile
     * GET /profile
     */
    public function profile(Request $request)
    {
        try {
            $user = $request->user();
            
            // You can load any additional relationships here
            // $user->load('orders', 'addresses', etc);

            return response()->json([
                'status' => 'success',
                'data' => $user
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener perfil: ' . $e->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request) 
    {
        // Revoke all tokens...
		$request->user()->tokens()->delete();

		// Revoke the current token
		$request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}
