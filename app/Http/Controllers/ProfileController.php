<?php

namespace App\Http\Controllers;

use App\Models\MyProfile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $profiles=Profile::all();
        return[$profiles];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => ['required', 'string'],
            'lastname' => ['required', 'string'],
            'date_of_birth' => ['required', 'date'],
            'gender' => ['required', 'string', 'in:male,female'],
            'address' => ['required', 'string'],
            'phonenumber' => ['required', 'integer', 'unique:profiles'],
            'education' => ['required', 'string'],
            'skills' => ['required', 'string'],
            'user_id' => ['integer', 'unique:profiles,user_id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors',
                'errors' => $validator->errors(),
                'status_code' => 400
            ], 400);
        }

        $user_id = auth()->user()->id;
        $existingProfile = Profile::where('user_id', $user_id)->first();

        if ($existingProfile) {
            return response()->json([
                'message' => 'Profile creation failed',
                'errors' => ['user_id' => ['You already have a profile']],
                'status_code' => 400
            ], 400);
        }

        $data = $request->all();
        $data['user_id'] = $user_id;
        $profile = Profile::create($data);

        return response()->json([
            'message' => 'Profile created successfully',
            'data' => ['profile' => $profile],
            'status_code' => 201
        ], 201);
    }



    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'string',
            'lastname' => 'string',
            'date_of_birth' => 'date',
            'gender' => 'string',
            'address' => 'string',
            'phonenumber' => ['integer', 'unique:profiles'],
            'Education' => 'string',
            'skills' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors',
                'errors' => $validator->errors(),
                'status_code' => 400
            ], 400);
        }

        $profile = Profile::find($id);

        if (!$profile) {
            return response()->json([
                'message' => 'Profile not found',
                'status_code' => 404
            ], 404);
        }

        $user = auth()->user();

        if ($user->id !== $profile->user_id) {
            return response()->json([
                'message' => 'Unauthorized',
                'status_code' => 403
            ], 403);
        }

        $profile->update($request->all());

        return response()->json([
            'message' => 'Profile updated successfully',
            'data' => ['profile' => $profile],
            'status_code' => 200
        ], 200);
    }


    public function show($id)
{
    $profile = Profile::find($id);

    if (!$profile) {
        return response()->json([
            'message' => 'Profile not found',
            'status_code' => 404
        ], 404);
    }

    return response()->json([
        'message' => 'Profile found',
        'data' => ['profile' => $profile],
        'status_code' => 200
    ], 200);
}





    public function destroy($id)
{
    $z = Profile::find($id);

    // Check if the profile exists
    if (!$z) {
        return response()->json(['message' => 'Profile not found'], 404);
    }

    $user = auth()->user();
    $profile = $user->profile;
    // Check if the authenticated user is the owner of the profile
    if (!$profile) {
        return response()->json(['message' => 'You are not authorized to delete this profile'], 403);
    }

    if($z){

        $profile->delete();

    return response()->json(['message' => 'Profile deleted'], 200);
    }

}
}
