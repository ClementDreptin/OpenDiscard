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
     * @api {get} /servers/:id/users/ Get Members
     * @apiGroup Users
     *
     * @apiDescription Gets all the Users from a Server.
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
     *       "type": "resources",
     *       "members": [
     *         {
     *           "id": "db0916fa-934b-4981-9980-d53bed190db3",
     *           "username": "AlbertEinstein",
     *           "email": "albert.einstein@physics.com",
     *           "avatar_url": "/images/c29eaa26-3fd1-4b66-aafe-60b571009d0d"
     *         },
     *         {
     *           "id": "db0916fa-934b-4981-9980-d53bed190db3",
     *           "username": "IsaacNewton",
     *           "email": "isaac.newton@physics.com",
     *           "avatar_url": "/images/c29eaa26-3fd1-4b66-aafe-60b571009d0d"
     *         }
     *       ]
     *     }
     *
     * @apiError ServerNotFound The UUID of the Server was not found.
     * @apiError NotServerMember A Non-Member tries to get the Server's Members.
     * @apiError InvalidToken The token is not valid.
     *
     * @apiErrorExample ServerNotFound-Response:
     *     HTTP/1.1 404 NOT FOUND
     *     {
     *       "type": "error",
     *       "error": 404,
     *       "message": "Server with ID db0916fa-934b-4981-9980-d53bed190db3 doesn't exist."
     *     }
     *
     * @apiErrorExample NotServerMember-Response:
     *     HTTP/1.1 401 UNAUTHORIZED
     *     {
     *       "type": "error",
     *       "error": 401,
     *       "message": "Only Members can get Members from this Server."
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
    public function get(Request $request, Response $response, $args) {
        $server = $request->getAttribute('server');
        $token_owner_id = $request->getAttribute('token_owner_id');
        $tokenOwnerInServer = false;

        $members = $server
            ->members()
            ->orderBy('username', 'asc')
            ->get();

        // I didn't do $server->members()->where('user_id', '=', $token_owner_id)->exists()
        // this time not to make another request to the database.
        foreach ($members as $member) {
            unset($member->token);
            unset($member->password);
            if ($member->id === $token_owner_id) {
                $tokenOwnerInServer = true;
            }
        }

        if (!$tokenOwnerInServer) {
            return JSON::errorResponse($response, 401, "Only Members can get Members from this Server.");
        } else {
            return JSON::successResponse($response, 200, [
                "type" => "resources",
                "members" => $members
            ]);
        }
    }

    /**
     * @api {get} /users?elem=:elem Get
     * @apiGroup Users
     *
     * @apiDescription Gets Users with a username that starts with <code>elem</code>.
     *
     * @apiParam {String} elem The string to search for.
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
     *       "type": "resources",
     *       "users": [
     *         {
     *           "id": "db0916fa-934b-4981-9980-d53bed190db3",
     *           "username": "AlbertEinstein",
     *           "email": "albert.einstein@physics.com",
     *           "avatar_url": "/images/c29eaa26-3fd1-4b66-aafe-60b571009d0d"
     *         },
     *         {
     *           "id": "db0916fa-934b-4981-9980-d53bed190db3",
     *           "username": "AlbertEinsteinJR",
     *           "email": "albert.einstein.jr@physics.com",
     *           "avatar_url": "/images/c29eaa26-3fd1-4b66-aafe-60b571009d0d"
     *         },
     *       ]
     *     }
     *
     * @apiError InvalidElem The elem parameter is empty or a string with only spaces.
     * @apiError InvalidToken The token is not valid.
     *
     * @apiErrorExample InvalidElem-Response:
     *     HTTP/1.1 400 BAD REQUEST
     *     {
     *       "type": "error",
     *       "error": 400,
     *       "message": "You need to provide a string to search for in the elem parameter."
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
    public function search(Request $request, Response $response, $args) {
        $elem_to_search = trim($request->getQueryParam('elem'));

        if (!isset($elem_to_search) || $elem_to_search === '') {
            return JSON::errorResponse($response, 400, "You need to provide a string to search for in the elem parameter.");
        }

        $users = User::query()
            ->select('id', 'username', 'email', 'avatar_url')
            ->where('username', 'like', "$elem_to_search%")
            ->orderBy('username', 'asc')
            ->get();

        return JSON::successResponse($response, 200, [
            "type" => "resources",
            "users" => $users
        ]);
    }

    /**
     * @api {post} /users/signup/ Sign up
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
     * @apiError InvalidToken The token is not valid.
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
        $token_owner_id = $request->getAttribute('token_owner_id');
        $body = $request->getParsedBody();

        if ($user->id !== $token_owner_id) {
            return JSON::errorResponse($response, 401, "The User ID and the token don't match.");
        }

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