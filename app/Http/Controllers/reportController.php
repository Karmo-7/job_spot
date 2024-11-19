<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\report;
use App\Models\User;
use App\Models\Job;
use Illuminate\Support\Facades\Auth;



class reportController extends Controller
{
    //
    public function create(Request $request){

        $validator = Validator::make($request->all(), [
            'job_id' => ['required'],
            'is_report' => ['numeric', 'min:0', 'max:1'], // is_report should be a number between 0-1
            'reason' => ['required_if:is_report,1', 'string', 'max:500'] // reason is required only if is_report is 1, and must be a string
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 400);
        }
        $user_id=Auth::user()->id;
        $job_id=$request->input('job_id');

        $report=report::where('user_id',$user_id)->
        where('job_id',$job_id)->first();
        if($report){
        return response()->json(['message'=>'you reported on this post before'],400);

        }



        $report = new report();
        $report->user_id = $user_id;
        $report->job_id=$job_id;
        $report->is_report=$request->input('is_report');
        $report->reason=$request->input('reason');



        $report->save();

        $report = $report->load('reportuser', 'reportjob');




        return response()->json(['message'=>'report created succesfuly','report:'=>$report],201);
    }
    public function show(Request $request ,$id){
        $report =report::find($id);
        if(!$report){
            return response()->json(['error'=>'report not found'],404);
        }
        return response()->json(['report:'=>$report],200);
    }
    public function index(){
        $reports =report::all();
        $reports->load('reportjob', 'reportuser');
        return response()->json(['reports:'=>$reports],200);
    }
}
