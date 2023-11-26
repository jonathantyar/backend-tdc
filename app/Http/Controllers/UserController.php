<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $users = User::all();
            return response()->json([
                'status' => true,
                'message' => 'Successfully list user',
                'data' => $users
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'user.index.err' . $th->getMessage(),
                'error' => $th->getCode(),
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
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
            return response()->json([
                'status' => true,
                'message' => 'Successfully store user',
                'data' => $user
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'user.store.err' . $th->getMessage(),
                'error' => $th->getCode(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $user = User::find($id);
            return response()->json([
                'status' => true,
                'message' => 'Successfully show user',
                'data' => $user
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'user.show.err' . $th->getMessage(),
                'error' => $th->getCode(),
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $validateRequest = Validator::make($request->all(), [
                'email' => ['required', 'email', 'unique:users,email,' . $id],
                'name' => ['required'],
                'password' => ['nullable', 'min:8'],
            ]);
            if ($validateRequest->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => implode(",", $validateRequest->messages()->all()),
                    'error' => $validateRequest->errors(),
                ], 401);
            }

            $user = User::find($id);
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Successfully update user',
                'data' => $user
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'user.update.err' . $th->getMessage(),
                'error' => $th->getCode(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $authenticatedUser = auth('api')->user();
            if ($authenticatedUser->id == $id) {
                return response()->json([
                    'status' => false,
                    'message' => 'user.destroy.err : trying to delete your own user',
                    'error' => [],
                ]);
            }

            $user = User::destroy($id);
            return response()->json([
                'status' => true,
                'message' => 'Successfully destroy user',
                'data' => $user
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'user.destroy.err' . $th->getMessage(),
                'error' => $th->getCode(),
            ], 500);
        }
    }
}
