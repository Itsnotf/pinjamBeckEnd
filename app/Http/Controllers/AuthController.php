<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
  public function registerUser(Request $request){
    $user = new User();

    $rules = [
        'name' => 'required',
        'badge' => 'required',
        'email' => 'required|email|unique:users,email',
        'password'=>'required'
    ];

    $validator = Validator::make($request->all(),$rules);

    if($validator->fails()){
        return response()->json([
            'status' => false,
            'message' => 'proses validasi gagal',
            'data' => $validator->errors()
        ], 401);
    }

    $user->name = $request->name;
    $user->email = $request->email;
    $user->badge = $request->badge;
    $user->id_role = 2;
    $user->password = Hash::make($request->password);
    $user->save();

    return response()->json([
        'status' => true,
        'message' => 'user berhasil dibuat',
        'data' => $user
    ]);
  }

  public function loginUser(Request $request) {
    $rules = [
        'email' => 'required|email',
        'password'=>'required'
    ];

    $validator = Validator::make($request->all(),$rules);

    if($validator->fails()){
        return response()->json([
            'status' => false,
            'message' => 'proses login gagal',
            'data' => $validator->errors()
        ], 401);
    }

    if(!Auth::attempt($request->only(['email','password']))){
        return response()->json([
            'status' => false,
            'message' => 'email atau password yang dimasukan salah',
        ]);
    }

    $data = User::where('email',$request->email)->first();

    return response()->json([
        'status' => true,
        'message' => 'login berhasil',
        'data' => $data,
        'token' => $data->createToken('barang_token')->plainTextToken
    ]);

  }
}
