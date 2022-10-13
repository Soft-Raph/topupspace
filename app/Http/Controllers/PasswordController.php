<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\ResetCodePassword;
use App\Models\User;
use Illuminate\Http\Request;

class PasswordController extends Controller
{
    public function forgetPassword(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        // Delete all old code that user send before.
        ResetCodePassword::where('email', $request->email)->delete();

        // Generate random code
        $data['code'] = mt_rand(100000, 999999);

        // Create a new code
        $codeData = ResetCodePassword::create($data);

        // Send email to user will come in here

        return ResponseHelper::success($codeData, 'Reset Code Sent successfully');
    }
    public function resetPassword(Request $request)
    {
        $request->validate([
            'code' => 'required|exists:reset_code_passwords',
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password',
        ]);

        // find the code
        $passwordReset = ResetCodePassword::firstWhere('code', $request->code);

        // check if it does not expired: the time is one hour
        if ($passwordReset->created_at > now()->addHour()) {
            $passwordReset->delete();
            return ResponseHelper::success('','password has been successfully reset');
        }

        // find user's email
        $user = User::firstWhere('email', $passwordReset->email);

        // update user password
        $user->update($request->only('password'));

        // delete current code
        $passwordReset->delete();

        return ResponseHelper::success('','password has been successfully reset');
    }
}
