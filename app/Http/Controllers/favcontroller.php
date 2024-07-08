<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Job;
use App\Models\favorite;

class favcontroller extends Controller
{
    //
    public function add(Request $request)
    {
        // Step 1: Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'job_id' => 'required|exists:jobs,id',
        ]);

        // Step 2: Check if validation fails and return error response
        if ($validator->fails()) {
            return response()->json([
                'status_code' => 400,
                'message' => 'Validation errors',
                'data' => $validator->errors()
            ], 400);
        }

        // Step 3: Retrieve the authenticated user's ID
        $user_id = auth()->user()->id;

        // Step 4: Check if the job is already in the user's favorites
        $existingFavorite = Favorite::where('job_id', $request->input('job_id'))
            ->where('user_id', $user_id)
            ->first();

        if ($existingFavorite) {
            return response()->json([
                'status_code' => 409,
                'message' => 'Already added to favorites',
                'data' => null
            ], 409);
        }

        // Step 5: Add the job to the user's favorites
        $favorite = new Favorite();
        $favorite->user_id = $user_id;
        $favorite->job_id = $request->input('job_id');
        $favorite->save();

        // Step 6: Return a success response
        return response()->json([
            'status_code' => 201,
            'message' => 'Added to favorites',
            'data' => $favorite
        ], 201);
    }


    public function show($id)
    {
        if (auth()->user()) {
            $user_id = Auth::user()->id;
            $fav = Favorite::where('user_id', $user_id)->where('id', $id)->with('favjob')->first();

            if (!$fav) {
                return response()->json([
                    'status_code' => 404,
                    'message' => 'Not found',
                    'data' => null
                ], 404);
            }

            return response()->json([
                'status_code' => 200,
                'message' => 'Favorite retrieved successfully',
                'data' => $fav
            ], 200);
        }

        return response()->json([
            'status_code' => 401,
            'message' => 'User not authenticated',
            'data' => null
        ], 401);
    }




    public function showAllMyfav()
    {
        // Step 1: Check if the user is authenticated
        if (auth()->user()) {
            // Step 2: Retrieve the authenticated user's ID
            $user_id = Auth::user()->id;

            // Step 3: Fetch the user's favorites with related job information
            $favorites = Favorite::where('user_id', $user_id)->with('favjob')->get();

            // Step 4: Load missing relationships
            $favorites->loadMissing('favjob');

            // Step 5: Check if the user has any favorites
            if ($favorites->isEmpty()) {
                return response()->json([
                    'status_code' => 404,
                    'message' => 'You do not have anything in favorites',
                    'data' => null
                ], 404);
            }

            // Step 6: Return a success response with the favorites data
            return response()->json([
                'status_code' => 200,
                'message' => 'Favorites retrieved successfully',
                'data' => $favorites
            ], 200);
        }

        // Step 7: Return an error response if the user is not authenticated
        return response()->json([
            'status_code' => 401,
            'message' => 'User not authenticated',
            'data' => null
        ], 401);
    }




    public function delete($id){
        if(auth()->user()) {
            $user_id = Auth::user()->id;
            $fav = favorite::where('user_id', $user_id)->find($id);
            if (!$fav) {
                return response()->json(['message' => 'not found to remove it'], 404);
            }
            $fav->delete();
            return response()->json(['message' => 'Removed from favorites'], 200);
        }


    }
}
