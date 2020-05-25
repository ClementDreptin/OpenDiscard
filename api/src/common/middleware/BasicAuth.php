<?php
namespace OpenDiscard\api\common\middleware;

use OpenDiscard\api\common\writer\JSON;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class BasicAuth {
    public function decodeBasicAuth(Request $request, Response $response, callable $next) {
        $authHeader = isset($request->getHeader("Authorization")[0]) ? $request->getHeader("Authorization")[0] : null;

        if (!empty($authHeader) && strpos($authHeader, "Basic") !== false) {
            $authHeaderValue = substr($authHeader, 6);
            $authHeaderValueDecoded = base64_decode($authHeaderValue);
            $email = substr($authHeaderValueDecoded, 0, strpos($authHeaderValueDecoded, ':'));
            $password = substr($authHeaderValueDecoded, strpos($authHeaderValueDecoded, ':') + 1);

            $request = $request->withAttribute('email', $email);
            $request = $request->withAttribute('password', $password);

            return $next($request, $response);
        } else {
            return JSON::errorResponse($response, 401, "You need to use Basic Auth to sign in.");
        }
    }
}