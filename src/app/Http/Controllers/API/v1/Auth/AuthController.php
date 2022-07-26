<?php

namespace App\Http\Controllers\API\v1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

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
       $user=resolve(UserRepository::class)->create($request);
       $defualtSuperAdminEmail = config('permission.defualt_super_admin_email');

//       $user->email === $defualtSuperAdminEmail ? $user->assignRole('Super Admin'):$user->assignRole('User');

        return response()->json([
          'message'=>"user created successfully"
       ],Response::HTTP_CREATED);

    }

    /**
     * Login User
     * @method GET
     * @param Request $request
     * @return JsonResponse
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
            return response()->json(Auth::user(),Response::HTTP_OK);
        }

        throw ValidationException::withMessages([
           'email'=>'incorrect credentials.'
        ]);
    }

    public function user()
    {
        $data=[
            Auth::user(),
            'notifications'=>Auth::user()->unreadNotifications(),
        ];
        return response()->json($data,Response::HTTP_OK);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'message'=>"logeed out successfuly"
        ],Response::HTTP_OK);
    }

}
