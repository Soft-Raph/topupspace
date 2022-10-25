<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $user = User::where('email', $request->email)->first();
            if (! $user || ! Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'The provided credentials are incorrect.',
                ]);
            }
            $tokenData = $user->createToken('API TOKEN')->plainTextToken;
            return ResponseHelper::success($user,$tokenData, 'User LogIn successfully');
        } catch (\Exception $exception) {
            return ResponseHelper::error(500, $exception->getMessage());
        }
    }
    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
                $create_user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password)
                ]);
                if (! $create_user) {
                    return ResponseHelper::error(500, 'An error occurred, try again.');
                }
            $tokenData = $create_user->createToken('API TOKEN')->plainTextToken;
            return ResponseHelper::success($tokenData, 'User registration successfully');
        } catch (\Exception $exception) {
            return ResponseHelper::error(500, $exception->getMessage());
        }
    }
}
