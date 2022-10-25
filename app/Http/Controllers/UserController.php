<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Resources\UserResources;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchUserInfo(Request $request)
    {
        $user = $request->user();
        if (!$user){
            return ResponseHelper::error(500, 'no user found');
        }
        return ResponseHelper::success(UserResources::make($user), 'Users information fetched Successfully', 201);
    }
}
