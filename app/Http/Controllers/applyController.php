<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\job;
use App\Models\User;
use App\Models\user_job;



class applyController extends Controller
{

    public function apply(Request $request,Job $job, User $user)
    {
        $data=validator::make($request->all(),[

            'id_job'=>['integer'],
            'status' => ['string'],
            'resume'=>['string'],
             'cv' => 'required|mimes:pdf|max:2048',
        ]);
         if($data->fails()){
            return response()->json(['error'=>$data->errors()],401);
        }
        $user_id=auth()->user()->id;
        $existingApplication = user_job::where('id_user', $user_id)
        ->where('id_job', $request->input('id_job'))
        ->first();

    if ($existingApplication) {
        return response()->json(['error' => 'You have already applied for this job'], 401);
    }
        $cv=$request->file('cv');
        $cvPath=$cv->store('cv','public');
        $apply=new user_job();
        $apply->id_user=Auth::user()->id;
        $apply->id_job=$request->input('id_job');
        $apply['status']='pending';
        $apply->resume=$request->input('resume');
        $apply->cv=$cvPath;
        $apply->save();
        return response()->json(['The process was successfully completed','your ask'=>$apply],200);


    }
    public function index(){

        $userJob=user_job::all();
        return response()->json($userJob,200);
    }





    public function show($id)
{
    $user_id = Auth::user()->id;

    // Check if the authenticated user applied for the job
    $jobRequest = user_job::where('id', $id)
        ->where('id_user', $user_id)
        ->first();

    if ($jobRequest) {
        // If the user applied for the job, return the job details and the job request
        $job = $jobRequest->job;
        return response()->json([
            // 'job' => $job,
            'jobRequest' => $jobRequest
        ], 200);
    } else {
        // If the user didn't apply for the job, return a 404 error
        return response()->json(['message' => 'You are not authorized to show this ask'], 403);
    }
}


public function showMyRequest(Request $request)
{
    $data = Validator::make($request->all(), [
        'id_job' => ['required', 'integer'],
    ]);

    if ($data->fails()) {
        return response()->json(['error' => $data->errors()], 401);
    }

    $job_id = $request->input('id_job');

    if (auth()->user()) {
        $user_id = DB::table('jobs')
            ->where('id', $job_id)
            ->value('user_id');

        if ($user_id != auth()->user()['id']) {
            return response()->json(['message' => 'You are not authorized to show this details'], 403);
        }

        $jobId = $request->input('id_job');
        $applicantCount = user_job::where('id_job', $jobId)
            ->count();

        $IdRequest = user_job::where('id_job', $jobId)
            ->get()
            ->map(function ($application) {
                return [
                    'user' => $application->use,
                    'applications_count' => 1,
                    'IdRequest' => $application->id,
                    'cv' => $application->cv,
                    'cv_url' => route('download.cv', ['id' => $application->id]),
                    'cv_urvl' => Storage::url($application->cv),

                ];
            })
            ->values()
            ->toArray();

        return response()->json([
            'total_applicants' => $applicantCount,
            'applicants' => $IdRequest
        ]);
    }
}

public function downloadCV($id)
{
    try {
        $application = user_job::findOrFail($id);
        $cvPath = $application->cv;

        if ($cvPath) {
            return Storage::disk('public')->download($cvPath);
        } else {
            return response()->json(['message' => 'CV not found'], 404);
        }
    } catch (\Exception $e) {
        return response()->json(['message' => 'Error downloading CV: ' . $e->getMessage()], 500);
    }
}




        public function update(Request $request,$id){
            $validator=validator::make($request->all(),[
                'status' => 'required|in:pending,accepted,unacceptable',
            ]);
            if($validator->fails()){
                return response()->json(['error'=>$validator->errors()],401);
            }
            $userJob=user_job::find($id);
            if(!$userJob){
                return response()->json(['the ask dos not exist for process it'],401);

            }
            $job_id=$userJob['id_job'];
            if(auth()->user()){
                 $user_id=DB::table('jobs')->
                 where('id',$job_id)->
                 value('user_id');

                // $user_id=$userJob['id_job'];
                if($user_id != auth()->user()['id']){
                return response()->json(['message' => 'You are not authorized to process this job'], 403);
                }
                $userJob->update($request->all());
                return response()->json($userJob);

            }
            else return response('user not found');




        }

        public function showmyask($id){
    $asks = user_job::where('id_user', $id)
              ->with(['use', 'job'])
              ->get();

    if ($asks->count() > 0) {
        $cvUrls = [];
        foreach ($asks as $ask) {
            $cvUrls[] = Storage::url($ask->cv);
        }
        return response()->json([
            'my_asks' => $asks,
            'cv_urls' => $cvUrls,
        ], 200);
    } else {
        return response()->json(['message' => 'You did not offer any chances'], 403);
    }
}
}



















