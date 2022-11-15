<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function successResponse($data, $message = null, $code = 200): JsonResponse
    {
        return response()->json([
            'status'=> 'Success',
            'data' => $data,
            'message' => $message,
        ], $code);
    }

    protected function errorResponse($code, $message = null): JsonResponse
    {
        return response()->json([
            'status'=>'Error',
            'data' => [],
            'message' => $message,
        ], $code);
    }
}
