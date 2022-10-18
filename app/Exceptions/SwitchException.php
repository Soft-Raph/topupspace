<?php

namespace App\Exceptions;

use App\Helpers\ResponseHelper;
use Exception;
use Illuminate\Http\JsonResponse;

class SwitchException  extends Exception
{

    /**
     * @param $request
     * @return JsonResponse
     */
    public function render($request): JsonResponse
    {
        return ResponseHelper::error(400, $this->getMessage());
    }
}
