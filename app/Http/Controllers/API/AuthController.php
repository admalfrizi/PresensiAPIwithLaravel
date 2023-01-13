<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Helpers\ResponseFormatter;


class AuthController extends Controller
{
    public function fetch(Request $request)
    {
        return ResponseFormatter::success($request->user(),"Data anda sebagai berikut");
    }
    
    public function daftarkan(Request $request)
    {
        try {
            $data = $request->validate([
                'name'=> 'required|max:255',
                'email'=> 'email|required|unique:users',
                'password'=> 'required',
            ]);

            $data['password'] = bcrypt($request->password);
            $createData = User::create($data);

            $accessToken = $createData->createToken('authToken')->plainTextToken;

            return ResponseFormatter::success([
                'access_token' => $accessToken,
                'token_type' => 'Bearer',
                'user' => $createData
            ], 'User Has Been Registered');

        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Ada Kesalahan',
                'error' => $error
            ],"Gagal Login", 500);
        }
    }

    public function masuk(Request $request){
        try {
            $validateData = $request->validate([
                'email' => 'email|required',
                'password' => 'required'
            ]);

            if(!auth()->attempt($validateData)){
                return ResponseFormatter::errorUnauthorized("Invalid  User");
            }

            $accessToken = auth()->user()->createToken('authToken')->plainTextToken;
            $user = User::where('email', $request->email)->first();
            
            return ResponseFormatter::success([
                'access_token' => $accessToken,
                'token_type' => 'Bearer',
                'user' => $user
            ], 'Successfully Authenticated');

        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something Went Wrong',
                'error' => $error,
            ], 'Authentication Failed', 500);
        }

    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return ResponseFormatter::logoutSuccess([
            'message' => 'Your Account Has Been Logout'
        ], "Successfully Logout");
    }
}
