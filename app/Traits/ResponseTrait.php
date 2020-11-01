<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Laravel\Lumen\Http\ResponseFactory;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

trait ResponseTrait
{

    public function responseSuccess(string $image, int $status = 200): BinaryFileResponse
    {
        return new BinaryFileResponse($image, $status , ['Content-Type' => 'image/png']);
    }

    public function responseError(array $errors, int $status = 400): JsonResponse {
        return (new ResponseFactory)->json($errors, $status);
    }

}
