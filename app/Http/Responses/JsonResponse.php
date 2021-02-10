<?php

declare(strict_types = 1);

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse as BaseJsonResponse;
use Symfony\Component\HttpFoundation\Response;

use const null;

/**
 * Class ApiResponse
 *
 * @package App\Http\Responses
 */
class JsonResponse
{
    /**
     * @param mixed $data
     * @param array $headers
     * @param int $options
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ok($data, array $headers = [], int $options = 0): BaseJsonResponse
    {
        return new BaseJsonResponse($data, Response::HTTP_OK, $headers, $options);
    }

    /**
     * @param mixed $data
     * @param array $headers
     * @param int $options
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function created($data, array $headers = [], int $options = 0): BaseJsonResponse
    {
        return new BaseJsonResponse($data, Response::HTTP_CREATED, $headers, $options);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function noContent(): BaseJsonResponse
    {
        return new BaseJsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param mixed $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function badRequest($data = []): BaseJsonResponse
    {
        return new BaseJsonResponse($data, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param mixed $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function unauthorized($data = []): BaseJsonResponse
    {
        return new BaseJsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @param mixed $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function forbidden($data = []): BaseJsonResponse
    {
        return new BaseJsonResponse($data, Response::HTTP_FORBIDDEN);
    }

    /**
     * @param mixed $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function notFound($data = []): BaseJsonResponse
    {
        return new BaseJsonResponse($data, Response::HTTP_NOT_FOUND);
    }

    /**
     * @param mixed $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function internalServerError($data = []): BaseJsonResponse
    {
        return new BaseJsonResponse($data, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
