<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $rules = [
            'name' => ['required','string','max:255'],
            'email' => 'required|string|email|max:255|unique:users', 
            'password' => 'required|string|min:8|confirmed',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['status' => 'error','message' =>$validator->errors()], 200);
        }
        try{

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $user->save();
        $token = $user->createToken('registration_token')->plainTextToken;
        return response()->json([
            'message' => 'User Registered Successfully',
            'user' => $user,
            'token' => $token,
        ], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error','message'=> $e->getMessage()], 500);
        }
    }

    public function login(Request $request){

        $rules=array(
            'email' => 'required',
            'password' => 'required|string'
        );

        $validator=Validator::make($request->all(), $rules);
        
        if($validator->fails()){
            return response()->json(['status' => 'error','message' =>$validator->errors()], 200);
        }


        // Check email or username
        $user = User::where('email', $request->email)->first();

        if(!$user)
        {
            return response([
                'status' => 'error',
                'message' => 'This email does not exist'
            ], 401);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response([
                'status' => 'error',
                'message' => 'Password is wrong'
            ], 401);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'status' => 'success',
            'data' => $user,
            'token' => $token
        ];

        return response($response, 200);
    }

}
