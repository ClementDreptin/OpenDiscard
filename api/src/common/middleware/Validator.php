<?php
namespace OpenDiscard\api\common\middleware;

use OpenDiscard\api\commons\writer\JSON;
use Respect\Validation\Validator as RespectValidator;
use DavidePastore\Slim\Validation\Validation;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Validator {
    const ACCENTS = "À à Â â Ä ä Ç ç É é È è Ê ê Ë ë Î î Ï ï Ô ô Ö ö Ù ù Û û Ü ü";
    const PONCTUATION = ". ; : ! ? , - _ \" / ' ( ) [ ] { } + = % * $ € £ & # @";

    public static function createUserValidator() {
        $validator = [
            'firstname' => RespectValidator::alpha(self::ACCENTS),
            'lastname' => RespectValidator::alpha(self::ACCENTS),
            'email' => RespectValidator::email(),
            'password' => RespectValidator::notOptional(),
        ];

        return new Validation($validator);
    }

    public static function dataFormatErrorHandler(Request $request, Response $response, callable $next) {
        if ($request->getAttribute('has_errors')) {
            return JSON::errorResponse($response, 400, "Incorrect format in parameters.");
        } else {
            return $next($request, $response);
        }
    }
}