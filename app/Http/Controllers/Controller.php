<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param $data
     * @param int $statusCode
     * @param array $headers
     * @return JsonResponse
     */
    public function json($data, $statusCode = 200, $headers = [])
    {
        return JsonResponse::create([
            'data' => $data
        ], $statusCode, $headers);
    }
}
