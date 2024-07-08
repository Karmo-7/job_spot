<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\jsonResponse;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\HasApiTokens;



class JFcontroller extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:8', 'same:password_confirmation'],
            'password_confirmation' => ['required', 'min:8'],
            'role' => ['required', 'string', 'in:admin,jobseekers,freelancer,company'],
            'license' => ['required_if:role,company', 'string', 'unique:users'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors',
                'data' => $validator->errors(),
                'status_code' => 422
            ], 422);
        }

        if ($request->role == 'admin') {
            $existingAdmin = User::where('role', 'admin')->first();
            if ($existingAdmin) {
                return response()->json([
                    'message' => 'An admin already exists',
                    'data' => null,
                    'status_code' => 403
                ], 403);
            }
        }

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully',
            'data' => [
                'user' => $user,
                'token' => $token
            ],
            'status_code' => 201
        ], 201);
    }



    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors',
                'data' => $validator->errors(),
                'status_code' => 422
            ], 422);
        }

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $token = $user->createToken('MyApp')->plainTextToken;
            $profile = $user->profile;

            return response()->json([
                'message' => 'Login successfully',
                'data' => [
                    'user_name' => $user->name,
                    'user_email'=>$user->email,
                    'user_role'=>$user->role,
                    'user_id' => $user->id,
                    'profile_id' => $profile ? $profile->id : 0,
                    'token' => $token
                ],
                'status_code' => 200
            ], 200);
        }

        return response()->json([
            'message' => 'Bad credentials',
            'data' => null,
            'status_code' => 401
        ], 401);
    }



     public function logout(Request $request)
    {

        $user = Auth::user();
        $user->currentAccessToken()->delete();
        return response(['message' => 'Logged out']);


    }

    public function loginwithToken():jsonResponse{
        return $this->success(auth()->user(),'login successfully');
    }

     public function index(){
         return response('welcome admin', 200);
    }
}





    //     $data = $request->validate([
    //         'email' => ['required', 'string', 'exists:users'],
    //         'password' => 'required'
    //     ]);

    //     $user = User::where('email', $data['email'])->first();

    //     if (!$user || !password_verify($data['password'], $user->password)) {
    //         return response(['message' => 'Bad credentials'], 401);
    //     }
    //       if (Auth::attempt($request->all())){
    //         $user =Auth::user();
    //         $success=$user->createToken('MyApp')->plainTextToken;
    //         return Response(['token'=>$success], 200);

    //       }

    //         return response(['message' => 'Bad credentials'], 401);



        //hh $token = $user->createToken('auth_token')->plainTextToken;
        // return [
        //     'user' => $user,
        //     'token' => $token
        // ];hh





