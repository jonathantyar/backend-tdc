<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        try {
            $validateRequest = Validator::make($request->all(), [
                'email' => ['required', 'email', 'unique:users'],
                'name' => ['required'],
                'password' => ['required', 'min:8'],
            ]);
            if ($validateRequest->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => implode(",", $validateRequest->messages()->all()),
                    'error' => $validateRequest->errors(),
                ], 401);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);
            return response()->json($user);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'auth.register.err' . $th->getMessage(),
                'error' => $th->getCode(),
            ], 500);
        }
    }

    public function login(Request $request): JsonResponse
    {
        try {
            $validateRequest = Validator::make($request->all(), [
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);
            if ($validateRequest->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => implode(",", $validateRequest->messages()->all()),
                    'error' => $validateRequest->errors(),
                ], 401);
            }

            $token = Auth::attempt($validateRequest->validated());

            if (!$token) {
                return response()->json([
                    'status' => false,
                    'message' => 'Credentials is invalid',
                    'error' => $request->all(),
                ], 401);
            }

            return response()->json([
                'status' => true,
                'message' => 'Successfully authenticated',
                'data' => [
                    'token' => $token
                ]
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'auth.authenticate.err' . $th->getMessage(),
                'error' => $th->getCode(),
            ], 500);
        }
    }

    public function refresh(): JsonResponse
    {
        try {
            return response()->json([
                'status' => true,
                'message' => "Successfully refresh token",
                'data' => [
                    'token' => Auth::refresh()
                ]
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'auth.refresh.err' . $th->getMessage(),
                'error' => $th->getCode(),
            ], 500);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            Auth::forgetUser();

            return response()->json([
                'status' => true,
                'message' => 'Successfully logged out',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'auth.logout.err' . $th->getMessage(),
                'error' => $th->getCode(),
            ], 500);
        }
    }
}
