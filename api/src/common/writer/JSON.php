<?php
namespace OpenDiscard\api\common\writer;

use Psr\Http\Message\ResponseInterface as Response;

class JSON {
    public static function successResponse(Response $response, $status, $json_array) {
        $response = $response->withStatus($status)
            ->withHeader("Content-Type", "application/json;charset=utf-8");

        $response->getBody()
            ->write(json_encode($json_array));

        return $response;
    }

    public static function errorResponse(Response $response, $error_code, $message) {
        $response = $response->withStatus($error_code)
            ->withHeader("Content-Type", "application/json;charset=utf-8");

        $response->getBody()
            ->write(json_encode([
                "type" => "error",
                "error" => $error_code,
                "message" => $message
            ]));

        return $response;
    }
}