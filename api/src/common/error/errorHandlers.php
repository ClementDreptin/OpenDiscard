<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use OpenDiscard\api\common\writer\JSON;

return [
    'notFoundHandler' => function ($container) {
        return function (Request $request, Response $response) use ($container){
            return JSON::errorResponse($response, 400, "Error in request format.");
        };
    },
    'notAllowedHandler' => function ($container) {
        return function (Request $request, Response $response, $allowed_methods) use ($container){
            return JSON::errorResponse($response, 405, "Unauthorized method. Allowed methods: ".implode(', ', $allowed_methods));
        };
    },
    'phpErrorHandler' => function ($container) {
        return function (Request $request, Response $response, \Error $exception) use ($container){
            return JSON::errorResponse($response, 500, "Internal Server Error."/* Error : ".$exception->getMessage()." in the file: ".$exception->getFile()." at line: ".$exception->getLine()*/);
        };
    }
];