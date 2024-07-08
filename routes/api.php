<?php

use App\Http\Controllers\JFcontroller;
use App\Http\Controllers\jobcontroller;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\applyController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ChatMessageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\reviewController;
use App\Http\Controllers\reportController;
use App\Http\Controllers\favcontroller;
use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/register_jF', [JFcontroller::class,'register']);
Route::post('/login', [JFcontroller::class, 'login']);


Route::group(['middleware'=>'auth:sanctum'],function (){

 Route::post('apply/create',[applyController::class, 'apply']);
 Route::get('apply/show/{id}',[applyController::class, 'show']);
Route::post('logout',[JFcontroller::class, 'logout']);
Route::post('/loginToken',[JFcontroller::class, 'loginwithToken']);
Route::apiResource('/chat',ChatController::class)->only(['index','store','show']);
// Route::apiResource('/chat_message',ChatMessageController::class)->only(['index','store']);
Route::get('/chat_message',[ChatMessageController::class, 'index']);
Route::post('/chat_messagee',[ChatMessageController::class, 'store']);



Route::apiResource('/user',UserController::class)->only(['index']);
// Route::get('/chat/{id}',[ChatController::class, 'index']);
// Route::post('/chatt',[ChatController::class, 'store']);
// Route::get('/chat/show/{id}',[ChatController::class,'show']);


});


Route::group(['middleware'=>['auth:sanctum','type_user']],function (){
    Route::get('profile',[ProfileController::class, 'index']);
    Route::post('profile/create',[ProfileController::class, 'create']);
    Route::post('profile/update/{id}',[ProfileController::class, 'update']);
    Route::delete('profile/delete/{id}',[ProfileController::class, 'destroy']);
    Route::get('profile/show/{id}',[ProfileController::class,'show']);
    //
    Route::post('review/create',[reviewController::class, 'create']);
    Route::post('review/update/{id}',[reviewController::class, 'update']);
    //
    Route::post('report/create',[reportController::class, 'create']);
  //fav
  //
  Route::post('addtofav',[favcontroller::class, 'add']);
  Route::get('showAllMyfav',[favcontroller::class, 'showAllMyfav']);
  Route::delete('fav/delete/{id}',[favcontroller::class, 'delete']);
  Route::get('fav/show/{id}',[favcontroller::class,'show']);
  //
Route::post('apply/create',[applyController::class, 'apply']);

Route::get('apply/{id}/show/',[applyController::class, 'show']);



});

Route::group(['middleware'=>['auth:sanctum','job']],function (){
Route::post('/job/create', [jobcontroller::class, 'create']);
Route::delete('job/delete/{id}',[jobcontroller::class, 'destroy']);
Route::post('job/update/{id}',[jobcontroller::class, 'update']);
Route::get('showmyjob',[jobcontroller::class, 'show_my_job']);
Route::post('showMyRequest',[applyController::class, 'showMyRequest']);


//



Route::post('apply/update/{id}',[applyController::class, 'update']);
});
Route::get('/admin/dashboard', [AdminController::class, 'getDashboardData']);









Route::get('job/index',[jobcontroller::class, 'index']);
Route::get('job/show/{id}',[jobcontroller::class,'show']);
Route::get('profile/search',[applyController::class,'search']);
//
Route::get('report/show/{id}',[reportController::class,'show']);
Route::get('report/index',[reportController::class, 'index']);





Route::get('apply/index',[applyController::class, 'index']);
Route::get('apply/downloadcv/{id}', [applyController::class, 'downloadCV']);



























