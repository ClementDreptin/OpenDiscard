<?php
namespace OpenDiscard\api\common\middleware;

use OpenDiscard\api\common\writer\JSON;
use Respect\Validation\Validator as RespectValidator;
use DavidePastore\Slim\Validation\Validation;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Validator {
    const ACCENTS = "À à Â â Ä ä Ç ç É é È è Ê ê Ë ë Î î Ï ï Ô ô Ö ö Ù ù Û û Ü ü";
    const PUNCTUATION = ". ; : ! ? , - _ \" / ' ( ) [ ] { } + = % * $ € £ & # @";

    // Users validators
    public static function createUserValidator() {
        $validator = [
            'username' => RespectValidator::alnum(),
            'email' => RespectValidator::email(),
            'password' => RespectValidator::notOptional(),
            'avatar_url' => RespectValidator::optional(RespectValidator::url())
        ];

        return new Validation($validator);
    }

    public static function updateUserValidator() {
        $validator = [
            'username' => RespectValidator::optional(RespectValidator::alnum()),
            'avatar_url' => RespectValidator::optional(RespectValidator::url())
        ];

        return new Validation($validator);
    }

    // Servers validators
    public static function createServerValidator() {
        $validator = [
            'name' => RespectValidator::alnum(self::ACCENTS." ".self::PUNCTUATION),
            'image_url' => RespectValidator::optional(RespectValidator::url())
        ];

        return new Validation($validator);
    }

    public static function updateServerValidator() {
        $validator = [
            'name' => RespectValidator::optional(RespectValidator::alnum(self::ACCENTS." ".self::PUNCTUATION)),
            'avatar_url' => RespectValidator::optional(RespectValidator::url())
        ];

        return new Validation($validator);
    }

    // TextChannels validators
    public static function createTextChannelValidator() {
        $validator = [
            'name' => RespectValidator::alnum(self::ACCENTS." ".self::PUNCTUATION)
        ];

        return new Validation($validator);
    }

    public static function updateTextChannelValidator() {
        $validator = [
            'name' => RespectValidator::optional(RespectValidator::alnum(self::ACCENTS." ".self::PUNCTUATION))
        ];

        return new Validation($validator);
    }

    // Messages Validators
    public static function createMessageValidator() {
        $validator = [
            'content' => RespectValidator::alnum(self::ACCENTS." ".self::PUNCTUATION)
        ];

        return new Validation($validator);
    }

    public static function dataFormatErrorHandler(Request $request, Response $response, callable $next) {
        if ($request->getAttribute('has_errors')) {
            $errors = $request->getAttribute('errors');
            $error_string = "";

            foreach ($errors as $field => $field_errors) {
                $field_errors_string = "";
                foreach ($field_errors as $field_error) {
                    $field_errors_string .= "$field_error, ";
                }
                $error_string .= "$field: $field_errors_string | ";
            }
            $error_string = substr($error_string, 0, -3);

            return JSON::errorResponse($response, 400, "Incorrect format in parameters. Details: $error_string");
        } else {
            return $next($request, $response);
        }
    }
}