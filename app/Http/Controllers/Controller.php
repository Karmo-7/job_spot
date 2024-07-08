<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use illuminate\Http\JsonResponse;



class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests,DispatchesJobs;

    public function success(mixed $data,string $message="okay",int $statuscode=200):JsonResponse
    {
        return response()->json([
            'data'=>$data,
            'success'=>true,
            'message'=>$message,
    ],$statuscode);

    }

    public function error(string $message,int $statuscode=400):JsonResponse
    {
        return response()->json([
            'data'=>null,
            'success'=>false,
            'message'=>$message,
    ],$statuscode);

    }

}
