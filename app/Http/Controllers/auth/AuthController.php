<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'type_user' => 'required',
            'password' => 'required'            
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->type_user = $request->type_user;
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            "status" => 1,
            "msg" => "¡Registro de usuario exitoso!",
        ]);    
    }

    public function login(Request $request) {

        $request->validate([
            "name" => "required",
            "password" => "required"
        ]);

        $user = User::where("name", "=", $request->name)->first();

        if( isset($user->id) ){
            if(Hash::check($request->password, $user->password)){
                //creamos el token
                return  $user->createToken("auth_token")->plainTextToken;
                //si está todo ok
                        
            }else{
                return response()->json([
                    "status" => 0,
                    "msg" => "La password es incorrecta",
                ], 404);    
            }

        }
    }

}
