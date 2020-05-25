<?php
namespace OpenDiscard\api\control;

use Firebase\JWT\JWT;
use OpenDiscard\api\common\writer\JSON;
use OpenDiscard\api\model\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController {
    protected $container;

    public function __construct(\Slim\Container $container = null) {
        $this->container = $container;
    }

    /**
     * @api {post} /users/signup/ Create
     * @apiGroup Users
     *
     * @apiDescription Creates a User.
     *
     * @apiParam {String} username The User's username.
     * @apiParam {String} email The User's e-mail address.
     * @apiParam {String} password The User's password.
     * @apiParam {String} [avatar_url] The URL of the User's avatar.
     *
     * @apiParamExample {json} Request-Example:
     *     {
     *       "username": "AlbertEinstein",
     *       "email": "albert.einstein@physics.com",
     *       "password": "physics",
     *       "avatar_url": "/images/c29eaa26-3fd1-4b66-aafe-60b571009d0d"
     *     }
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 201 CREATED
     *     {
     *       "type": "resource",
     *       "user": {
     *         "id": "db0916fa-934b-4981-9980-d53bed190db3",
     *         "username": "AlbertEinstein",
     *         "email": "albert.einstein@physics.com",
     *         "avatar_url": "/images/c29eaa26-3fd1-4b66-aafe-60b571009d0d",
     *         "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJhcGlfcGxheWVyIiwic3ViIjoiZ2FtZSIsImF1ZCI6InBsYXllciIsImlhdCI6MTU4NDc0NTQ0NywiZXhwIjoxNTg0NzU2MjQ3fQ.vkaSPuOdb95IHWRFda9RGszEflYh8CGxhaKVHS3vredJSl2WyqqNTg_VUbfkx60A3cdClmcBqmyQdJnV3-l1xA"
     *       }
     *     }
     *
     * @apiError EmailAlreadyTaken The e-mail address is already taken.
     *
     * @apiErrorExample EmailAlreadyTaken-Response:
     *     HTTP/1.1 401 UNAUTHORIZED
     *     {
     *       "type": "error",
     *       "error": 401,
     *       "message": "This e-mail address is already taken."
     *     }
     */
    public function signUp(Request $request, Response $response, $args) {
        $body = $request->getParsedBody();

        try {
            $existingUser = User::query()->where('email', '=', $body['email'])->first();
            if (isset($existingUser)) {
                return JSON::errorResponse($response, 401, "This e-mail address is already taken.");
            }

            $user = new User();
            $user->id = Uuid::uuid4();
            $user->username = $body['username'];
            $user->email = $body['email'];
            $user->password = password_hash($body['password'], PASSWORD_DEFAULT);
            $user->avatar_url = isset($body['avatar_url']) ? $body['avatar_url'] : null;
            $user->token = JWT::encode([
                "aud" => $user->id,
                "iat" => time(), // Current timestamp
                "exp" => time() + (3 * 60 * 60), // Current timestamp + 3 hours
            ], $this->container->settings['JWT_secret'], "HS512");
            $user->saveOrFail();

            unset($user->password);

            return JSON::successResponse($response, 201, [
                "type" => "resource",
                "user" => $user
            ]);
        } catch (\Throwable $exception) {
            return JSON::errorResponse($response, 500, "Your account creation failed.");
        }
    }

    /**
     * @api {post} /users/signin/ Sign in
     * @apiGroup Users
     *
     * @apiDescription Signs a User in.
     *
     * @apiHeader {String} Authorization The e-mail address and password in the Basic Auth format.
     *
     * @apiHeaderExample {json} Basic Auth:
     *     {
     *       "Authorization": "Basic QWxhZGRpbjpPcGVuU2VzYW1l"
     *     }
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "type": "resource",
     *       "user": {
     *         "id": "db0916fa-934b-4981-9980-d53bed190db3",
     *         "username": "AlbertEinstein",
     *         "email": "albert.einstein@physics.com",
     *         "avatar_url": "/images/c29eaa26-3fd1-4b66-aafe-60b571009d0d",
     *         "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJhcGlfcGxheWVyIiwic3ViIjoiZ2FtZSIsImF1ZCI6InBsYXllciIsImlhdCI6MTU4NDc0NTQ0NywiZXhwIjoxNTg0NzU2MjQ3fQ.vkaSPuOdb95IHWRFda9RGszEflYh8CGxhaKVHS3vredJSl2WyqqNTg_VUbfkx60A3cdClmcBqmyQdJnV3-l1xA"
     *       }
     *     }
     *
     * @apiError InvalidCredentials The e-mail address or password are incorrect.
     *
     * @apiErrorExample InvalidCredentials-Response:
     *     HTTP/1.1 401 UNAUTHORIZED
     *     {
     *       "type": "error",
     *       "error": 401,
     *       "message": "Email or password are incorrect."
     *     }
     */
    public function signIn(Request $request, Response $response, $args) {
        $email = $request->getAttribute('email');
        $password = $request->getAttribute('password');

        try {
            $user = User::query()->where('email', '=', $email)->firstOrFail();

            if (password_verify($password, $user->password)) {
                $user->token = JWT::encode([
                    "aud" => $user->id,
                    "iat" => time(), // Current timestamp
                    "exp" => time() + (3 * 60 * 60), // Current timestamp + 3 hours
                ], $this->container->settings['JWT_secret'], "HS512");
                $user->saveOrFail();

                unset($user->password);

                return JSON::successResponse($response, 200, [
                    "type" => "resource",
                    "user" => $user
                ]);
            } else {
                return JSON::errorResponse($response, 401, "Email or password are incorrect.");
            }
        } catch (ModelNotFoundException $exception) {
            return JSON::errorResponse($response, 401, "Email or password are incorrect.");
        } catch (\Throwable $exception) {
            return JSON::errorResponse($response, 500, "The user update failed.");
        }
    }

    /**
     * @api {patch} /users/:id/ Update
     * @apiGroup Users
     *
     * @apiDescription Updates a User's information.
     *
     * @apiParam {String} [username] The new User's username.
     * @apiParam {String} [avatar_url] The new URL of the User's avatar.
     *
     * @apiParamExample {json} Request-Example:
     *     {
     *       "username": "AlbertEinsteinUpdated",
     *       "avatar_url": "/images/c29eaa26-3fd1-4b66-aafe-60b571009d0d"
     *     }
     *
     * @apiHeader {String} Authorization The User's token.
     *
     * @apiHeaderExample {json} Bearer Token:
     *     {
     *       "Authorization": "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJhcGlfcGxheWVyIiwic3ViIjoiZ2FtZSIsImF1ZCI6InBsYXllciIsImlhdCI6MTU4NDc0NTQ0NywiZXhwIjoxNTg0NzU2MjQ3fQ.vkaSPuOdb95IHWRFda9RGszEflYh8CGxhaKVHS3vredJSl2WyqqNTg_VUbfkx60A3cdClmcBqmyQdJnV3-l1xA"
     *     }
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "type": "resource",
     *       "user": {
     *         "id": "db0916fa-934b-4981-9980-d53bed190db3",
     *         "username": "AlbertEinsteinUpdated",
     *         "email": "albert.einstein@physics.com",
     *         "avatar_url": "/images/c29eaa26-3fd1-4b66-aafe-60b571009d0d",
     *         "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJhcGlfcGxheWVyIiwic3ViIjoiZ2FtZSIsImF1ZCI6InBsYXllciIsImlhdCI6MTU4NDc0NTQ0NywiZXhwIjoxNTg0NzU2MjQ3fQ.vkaSPuOdb95IHWRFda9RGszEflYh8CGxhaKVHS3vredJSl2WyqqNTg_VUbfkx60A3cdClmcBqmyQdJnV3-l1xA"
     *       }
     *     }
     *
     * @apiError UserNotFound The UUID of the User was not found.
     * @apiError InvalidToken The User's token is not valid.
     *
     * @apiErrorExample UserNotFound-Response:
     *     HTTP/1.1 404 NOT FOUND
     *     {
     *       "type": "error",
     *       "error": 404,
     *       "message": "User with ID db0916fa-934b-4981-9980-d53bed190db3 doesn't exist."
     *     }
     *
     * @apiErrorExample InvalidToken-Response:
     *     HTTP/1.1 401 UNAUTHORIZED
     *     {
     *       "type": "error",
     *       "error": 401,
     *       "message": "Token expired."
     *     }
     */
    public function update(Request $request, Response $response, $args) {
        $user = $request->getAttribute('user');
        $body = $request->getParsedBody();

        try {
            $user->username = isset($body['username']) ? $body['username'] : $user->username;
            $user->avatar_url = isset($body['avatar_url']) ? $body['avatar_url'] : $user->avatar_url;
            $user->saveOrFail();

            unset($user->password);

            return JSON::successResponse($response, 200, [
                "type" => "resource",
                "user" => $user
            ]);
        } catch (\Throwable $exception) {
            return JSON::errorResponse($response, 500, "The user update failed.");
        }
    }
}