<?php

use Illuminate\Http\JsonResponse;

if (!function_exists('generateUniqueNumber')) {
    function generateUniqueNumber(int $length): int
    {
        $digits = range(1, 9);
        shuffle($digits);

        // Take the first 'length' digits to form a unique number
        $uniqueNumber = implode('', array_slice($digits, 0, $length));

        return (int)$uniqueNumber;
    }
}

if (!function_exists('api_response')) {

    /**
     * @param bool $success
     * @param $data
     * @param $response_code
     * @param $massage
     * @param $errors
     * @return JsonResponse
     *
     */
    function api_response(bool $success, $data = null, $response_code = 200, $massage = null, $errors = null): JsonResponse
    {
        return response()->json([
            'success' => $success,
            'message' => $massage,
            'data' => $data,
            'errors' => $errors,
        ], $response_code);
    }
}
