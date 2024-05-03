<?php

namespace App\Utils;

class Responses
{
    static function getJsonResponse($data)
    {
        return response()->json($data, $data['status']);
    }

    static function getResponseData($statusCode, $message)
    {
        return array(
            'status' => $statusCode,
            'message' => $message,
            'cat' => "https://http.cat/$statusCode.png"
        );
    }

    //
    // 200 status codes
    //
    public static function ok($message, $data, $dataKey = 'data')
    {
        $responseData = self::getResponseData(200, $message);

        if (isset($data)) {
            $responseData[$dataKey] = $data;
        }

        return self::getJsonResponse($responseData);
    }

    public static function created($message, $dataKey, $data)
    {
        $responseData = self::getResponseData(201, $message);
        $responseData[$dataKey] = $data;

        return self::getJsonResponse($responseData);
    }

    //
    // 400 status codes
    //
    public static function badRequest($message)
    {
        $responseData = self::getResponseData(400, $message);
        return self::getJsonResponse($responseData);
    }

    public static function notFound($message)
    {
        $responseData = self::getResponseData(404, $message);
        return self::getJsonResponse($responseData);
    }

    public static function notAcceptable($message, $dataKey, $data)
    {
        $responseData = self::getResponseData(406, $message);
        
        if (isset($dataKey)) {
            $responseData[$dataKey] = $data;
        }
        return self::getJsonResponse($responseData);
    }
} 