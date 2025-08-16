<?php
// app\Http\Controllers\AuthController.php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Throwable;

class AuthController extends Controller
{
    /**
     * POST /api/register
     * Crea el usuario (password cifrada) y devuelve un JWT.
     */
    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'     => ['required','string','max:100'],
            'email'    => ['required','email','max:150','unique:users,email'],
            'password' => ['required','string','min:8'],
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        try {
            $token = auth('api')->login($user);
        } catch (Throwable $e) {
            // Si hay un problema con el guard o JWT
            return response()->json([
                'message' => 'Usuario creado, pero no se pudo emitir el token.',
                'user'    => $user->only(['id','name','email']),
                'error'   => 'JWT error',
            ], 201);
        }

        return response()->json([
            'message' => 'Usuario registrado',
            'user'    => $user->only(['id','name','email']),
            'token'   => $token,
            'type'    => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ], 201);
    }

    /**
     * POST /api/login
     * Valida credenciales y devuelve un JWT.
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email'    => ['required','email'],
            'password' => ['required','string'],
        ]);

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }

        return response()->json([
            'message'    => 'Login OK',
            'token'      => $token,
            'type'       => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ]);
    }
}


