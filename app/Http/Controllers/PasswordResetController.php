<?php

namespace App\Http\Controllers;

use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
    public function reset(Request $request)
    {
        $email = $request->email;
        $token = $request->token;
        $password = $request->password;

        if (!$this->validateToken($email, $token)) {
            return $this->invalidTokenResponse();
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return $this->userNotFoundResponse();
        }

        $user->password = Hash::make($password);
        $user->save();

        $this->deleteToken($email);

        return $this->successResponse();
    }

    public function validateToken($email, $token)
    {
        $savedToken = DB::table('password_resets')->where('email', $email)->first();

        if (!$savedToken) {
            return false;
        }

        return $savedToken->token === $token;
    }

    public function deleteToken($email)
    {
        DB::table('password_resets')->where('email', $email)->delete();
    }

    public function invalidTokenResponse()
    {
        return response()->json([
            'error' => 'Invalid token or email.'
        ], Response::HTTP_BAD_REQUEST);
    }

    public function userNotFoundResponse()
    {
        return response()->json([
            'error' => 'User not found.'
        ], Response::HTTP_NOT_FOUND);
    }

    public function successResponse()
    {
        return response()->json([
            'message' => 'Password updated successfully.'
        ], Response::HTTP_OK);
    }
}