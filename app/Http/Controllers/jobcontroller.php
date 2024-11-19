<?php

namespace App\Http\Controllers;

use App\Notifications\NewJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Job;
use App\Models\User;
use Illuminate\Support\Facades\Notification;

use Illuminate\Support\Facades\Auth;


class jobcontroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $job= Job::all();

        return response()->json($job,200);
    }


    public function show( $id)
    {
        $job=job::find($id);
        if(!$job){
            return response()->json(['message'=>'job not found'], 404);
        }
        return ['job'=>$job];
    }



    public function create(Request $request)
{
    $validator = Validator::make($request->all(), [
        'title' => ['required', 'string'],
        'description' => ['required', 'string'],
        'PostedAt' => ['required', 'date'],
        'category' => ['required', 'string'],
        'employmentType' => ['required', 'string'],
        'years_experience' => ['required', 'integer'],
        'required_age' => ['required', 'integer'],
        'gender' => ['required', 'string'],
        'location' => ['required', 'string'],
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Validation errors',
            'data' => $validator->errors(),
        ], 422);
    }

    $jobData = $request->all();
    $jobData['user_id'] = auth()->id();
    $job = Job::create($jobData);
    return response()->json([
        'status' => 'success',
        'message' => 'Job created successfully',
        'data' => $job,
    ], 201);
}


public function update(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        'title' => 'string',
        'description' => 'string',
        'PostedAt' => 'date',
        'category' => 'string',
        'employmentType' => 'string',
        'years_experience' => 'integer',
        'required_age' => 'integer',
        'gender' => 'string',
        'location' => 'string',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Validation errors',
            'data' => $validator->errors(),
        ], 400);
    }

    $job = Job::find($id);

    if (!$job) {
        return response()->json([
            'status' => 'error',
            'message' => 'Job not found',
        ], 404);
    }


    $user = auth()->user();
    if (!$user || $job->user_id != $user->id) {
        return response()->json([
            'status' => 'error',
            'message' => 'You are not authorized to update this job',
        ], 403);
    }


    $job->update($request->all());

    return response()->json([
        'status' => 'success',
        'message' => 'Job updated successfully',
        'data' => $job,
    ]);
}



public function destroy($id)
{
    $job = Job::find($id);

    if (!$job) {
        return response()->json([
            'status' => 'error',
            'message' => 'Job not found',
        ], 404);
    }

    $user = auth()->user();

    // Check if the user is an admin or the owner of the job
    if (!$user || ($user->role !== 'admin' && $job->user_id != $user->id)) {
        return response()->json([
            'status' => 'error',
            'message' => 'You are not authorized to delete this job',
        ], 403);
    }

    // Delete the job
    $job->delete();

    return response()->json([
        'status' => 'success',
        'message' => 'Job deleted successfully',
    ], 200);
}





    public function show_my_job(){
        if(auth()->user()){
            $user_id=auth()->user()['id'];
            $job=Job::where('user_id',$user_id)->get();
            return ['job'=>$job];
        }
    }
}
