<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\applyController;
use App\Http\Controllers\jobcontroller;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
// Route::get('showMyRequest',[applyController::class, 'showMyRequest']);
// Route::group(['middleware'=>['auth:sanctum','job']],function (){
// Route::post('/job/create', [jobcontroller::class, 'create']);
// Route::delete('job/delete/{id}',[jobcontroller::class, 'destroy']);
// Route::post('job/update/{id}',[jobcontroller::class, 'update']);
// Route::get('showmyjob',[jobcontroller::class, 'show_my_job']);
// Route::post('showMyRequest',[applyController::class, 'showMyRequest']);
// Route::get('/download-cv/{id}', [applyController::class, 'downloadCV'])->name('download.cv');

// //



// Route::post('apply/update/{id}',[applyController::class, 'update']);
// });



