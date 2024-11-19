<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\job;
use App\Models\review;

use Illuminate\Http\Request;

class reviewController extends Controller
{
    public function create(Request $request,User $user,Job $job){
        $validator=Validator::make($request->all(),[
        'job_id' => 'required|exists:jobs,id',
        'rating' => 'required|numeric|min:1|max:5',
        'comment' => 'string|max:500',
        ]);
        if($validator->fails()){
            return response()->json(['error'=>$validator->errors()],400);
        }

         $review = new Review();
    $review->user_id = Auth::user()->id;
    $review->job_id = $request->input('job_id');
    $review->rating = $request->input('rating');
    $review->comment = $request->input('comment');
    $review->save();

    return response()->json(['message' => 'Review created successfully','review'=>$review], 201);

    }

    public function update(Request $request,$id){
        $validator=Validator::make($request->all(),[

            'job_id' =>'exists:jobs,id',
            'rating' =>'|numeric|min:1|max:5',
            'comment' =>'string|max:500',
        ]);
        if($validator->fails()){
            return response()->json(['error'=>$validator->errors()],400);
        }

        $user_id=auth()->user()->id;
        $review=review::where('user_id',$user_id)->find($id);
        if(!$review){
        return response()->json(['message' => 'You are not authorized to update this review'], 403);
        }
        else{
        $review->user_id = Auth::user()->id;
        $review->job_id = $request->input('job_id');
        $review->rating = $request->input('rating');
        $review->comment = $request->input('comment');
        $review->save();
        return response()->json(['message' => 'Review updated successfully','new review'=>$review], 200);
        }
    }
    public function showallReviews($id){
        $reviews=review::where('job_id',$id)->with('usser','joob')->get();
        return response()->json(['reviews'=>$reviews],200);
    }

}
