<?php
namespace OpenDiscard\api\common\middleware;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use OpenDiscard\api\common\writer\JSON;
use OpenDiscard\api\model\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Firebase\JWT\JWT as FirebaseJWT;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;

class JWT {
    protected $container;

    public function __construct(\Slim\Container $container = null) {
        $this->container = $container;
    }

    public function checkJWT(Request $request, Response $response, callable $next) {
        $authHeader = !empty($request->getHeader("Authorization")) ? $request->getHeader("Authorization")[0] : null;

        if (!empty($authHeader) and strpos($authHeader, "Bearer") !== false) {
            try {
                $tokenString = sscanf($authHeader, "Bearer %s")[0];
                $token = FirebaseJWT::decode($tokenString, $this->container->settings['JWT_secret'], ['HS512']);

                User::query()->select('id')->where('id', '=', $token->aud)->firstOrFail();

                $request = $request->withAttribute('token_owner_id', $token->aud);

                return $next($request, $response);
            } catch (ModelNotFoundException $e) {
                return JSON::errorResponse($response, 401, "The ID in the token doesn't match with any registered User.");

            } catch (ExpiredException $e) {
                return JSON::errorResponse($response, 401, "Token expired.");

            } catch (SignatureInvalidException $e) {
                return JSON::errorResponse($response, 401, "Invalid signature in the token.");

            } catch (BeforeValidException $e) {
                return JSON::errorResponse($response, 401, "Token not valid yet.");

            } catch (\UnexpectedValueException $e) {
                return JSON::errorResponse($response, 401, "Unexpected value in the token.");

            }
        } else {
            return JSON::errorResponse($response, 401, "No token was provided in Authorization or it's not formatted correctly. Format: Bearer <token>");
        }
    }
}