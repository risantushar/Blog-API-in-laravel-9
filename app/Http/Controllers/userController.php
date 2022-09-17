<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class userController extends Controller
{
    public function register(Request $request)
    {
        return $request->all();
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

                   $registerNewOne = DB::table("Users")->insert($data);
                   return response('Registration Successfull');
               }
       }

    }
}
