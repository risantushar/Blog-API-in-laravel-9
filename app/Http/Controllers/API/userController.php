<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class userController extends Controller
{
    public function login(Request $request)
    {
        $credintial = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        if(!Auth::attempt($credintial))
        {
            return response()->json([
                "message"=>"Credintial Error"
            ],422);
        }
        else
        {
            return response([
                "user"=> auth()->user(),
                "token"=> auth()->user()->createToken('secret')->plainTextToken
               ]);
        }
    }

    public function register(Request $request)
    {
       $validated = Validator::make($request->all(),[
           'name' => 'required',
           'email' => 'required',
           'password' => 'required',
       ]);

      if($validated->fails())
      {
          return response()->json($validated->errors(),422);
      }

      else
       {
            $checkEmail = DB::table('Users')->where('email',$request->email)->first();

               if($checkEmail == true)
               {
                   return response('Email Alredy Exists');
               }
               else
               {
                   $request['password'] = Hash::make($request->password);

                   $data = $request->all();

                   $registerNewOne = User::create($data);

                   return response([
                    "user"=> $registerNewOne,
                    "token"=> $registerNewOne->createToken('secret')->plainTextToken
                   ],200);
               }
       }

    }

    public function logout(Request $request)
    {
        // auth()->user()->token()->delete();

        auth('sanctum')->user()->tokens()->delete();

        return response([
            "message" => 'Logout Success'
        ]);
    }
    public function user()
    {
        return response([
            "user" => auth()->user()
        ]);
    }
}
