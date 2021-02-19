<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(){
        
        $user = User::where('email', request('email'))->first();
        if ($user == null) {
            return response()->json(['status' => false, 'msg' => 'User Not Found']);
        } else {
            if ($user->provider != null) {
                return response()->json(['status' => false, 'msg' => 'This Email Login Via '.$user->provider]);
            }
        }
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
            $user = Auth::user();
            $success['api_token'] =  $user->createToken('nApp')->accessToken;
            return response()->json(['status'=> true, 'data' => $success]);
        }
        else{
            return response()->json(['status' => false, 'msg'=>'Unauthorised'], 401);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);            
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        
        $success['api_token'] =  $user->createToken('nApp')->accessToken;
        $success['name'] =  $user->name;

        return response()->json(['success'=>$success]);
    }

    public function details()
    {
        $user = Auth::user();
        return response()->json(['success' => $user]);
    }
}
