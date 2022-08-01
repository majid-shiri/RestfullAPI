<?php

namespace App\Http\Controllers\API\V01\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register New User
     * @method Post
     * @param Request $request
     */
    public function register(Request $request)
    {
        //validate form parameter
       $request->validate([
          'name'=>['required'],
          'email'=>['required','email','unique:users'],
          'password'=>['required'],
       ]);


       //insert User Into Database
        resolve(UserRepository::class)->create($request);

        return response()->json([
          'message'=>"user created successfully"
       ],201);



    }

    /**
     * Login User
     * @method GET
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */
    public function login(Request $request){
        //validate form parameter
        $request->validate([
            'email'=>['required','email'],
            'password'=>['required'],
        ]);

        //Check User Credentials
        if(Auth::attempt($request->only(['email','password']))){
            return response()->json(Auth::user(),200);
        }

        throw ValidationException::withMessages([
           'email'=>'incorrect credentials.'
        ]);
    }

    public function user()
    {
        return response()->json(Auth::user(),200);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'message'=>"logeed out successfuly"
        ],200);
    }

}
