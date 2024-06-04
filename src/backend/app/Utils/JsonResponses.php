<?php

namespace App\Utils;

class JsonResponses
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
            'cat' => "https://http.cat/$statusCode"
        );
    }

    //
    // 200 status codes
    //
    public static function ok($message, $data = null)
    {
        $responseData = self::getResponseData(200, $message);

        if (isset($data)) {
            $responseData['data'] = $data;
        }

        return self::getJsonResponse($responseData);
    }

    public static function created($message, $data)
    {
        $responseData = self::getResponseData(201, $message);
        $responseData['data'] = $data;

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

    public static function unauthorized($message)
    {
        $responseData = self::getResponseData(401, $message);
        return self::getJsonResponse($responseData);
    }

    public static function forbidden($message)
    {
        $responseData = self::getResponseData(403, $message);
        return self::getJsonResponse($responseData);
    }

    public static function notFound($message)
    {
        $responseData = self::getResponseData(404, $message);
        return self::getJsonResponse($responseData);
    }

    public static function notAcceptable($message, $dataKey = null, $data = null)
    {
        $responseData = self::getResponseData(406, $message);
        
        if (isset($dataKey)) {
            $responseData[$dataKey] = $data;
        }
        return self::getJsonResponse($responseData);
    }
} 