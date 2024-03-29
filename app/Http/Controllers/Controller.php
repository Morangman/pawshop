<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Responses\JsonResponse;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * Class Controller
 *
 * @package App\Http\Controllers
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @return \App\Http\Responses\JsonResponse
     */
    protected function json(): JsonResponse
    {
        return new JsonResponse();
    }
}
