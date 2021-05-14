<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class IndexController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(['message' => 'Nothing to do here'], Response::HTTP_I_AM_A_TEAPOT);
    }
}
