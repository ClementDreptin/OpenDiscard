<?php
namespace OpenDiscard\api\control;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use OpenDiscard\api\common\writer\JSON;
use OpenDiscard\api\model\Server;
use OpenDiscard\api\model\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Ramsey\Uuid\Uuid;

class ServerController {
    protected $container;

    public function __construct(\Slim\Container $container = null) {
        $this->container = $container;
    }

    /**
     * @api {get} /servers/ Get
     * @apiGroup Servers
     *
     * @apiDescription Gets all the Servers the token Owner is a Member of.
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
     *       "servers": [
     *         {
     *           "id": "db0916fa-934b-4981-9980-d53bed190db3",
     *           "name": "My Super Cool Server",
     *           "image_url": "/images/c29eaa26-3fd1-4b66-aafe-60b571009d0d",
     *           "owner_id": "db0916fa-934b-4981-9980-d53bed190db3"
     *         },
     *         {
     *           "id": "db0916fa-934b-4981-9980-d53bed190db3",
     *           "name": "My Other Super Cool Server",
     *           "image_url": "/images/c29eaa26-3fd1-4b66-aafe-60b571009d0d",
     *           "owner_id": "db0916fa-934b-4981-9980-d53bed190db3"
     *         }
     *       ]
     *     }
     *
     * @apiError InvalidToken The token is not valid.
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
        $token_owner_id = $request->getAttribute('token_owner_id');

        $servers = User::query()
            ->where('id', '=', $token_owner_id)
            ->first()
            ->servers;

        return JSON::successResponse($response, 200, [
            "type" => "resources",
            "servers" => $servers
        ]);
    }

    /**
     * @api {post} /servers/ Create
     * @apiGroup Servers
     *
     * @apiDescription Creates a Server.
     *
     * @apiParam {String} name The Server's name.
     * @apiParam {String} [image_url] The URL of the Server's image.
     *
     * @apiParamExample {json} Request-Example:
     *     {
     *       "name": "My Super Cool Server",
     *       "image_url": "/images/c29eaa26-3fd1-4b66-aafe-60b571009d0d"
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
     *     HTTP/1.1 201 CREATED
     *     {
     *       "type": "resource",
     *       "server": {
     *         "id": "db0916fa-934b-4981-9980-d53bed190db3",
     *         "name": "My Super Cool Server",
     *         "image_url": "/images/c29eaa26-3fd1-4b66-aafe-60b571009d0d",
     *         "owner_id": "db0916fa-934b-4981-9980-d53bed190db3"
     *       }
     *     }
     *
     * @apiError InvalidToken The token is not valid.
     *
     * @apiErrorExample InvalidToken-Response:
     *     HTTP/1.1 401 UNAUTHORIZED
     *     {
     *       "type": "error",
     *       "error": 401,
     *       "message": "Token expired."
     *     }
     */
    public function create(Request $request, Response $response, $args) {
        $body = $request->getParsedBody();
        $token_owner_id = $request->getAttribute('token_owner_id');

        try {
            $server = new Server();
            $server->id = Uuid::uuid4();
            $server->name = $body['name'];
            $server->image_url = isset($body['image_url']) ? $body['image_url'] : null;
            $server->owner_id = $token_owner_id;
            $server->saveOrFail();
            $server->members()->attach($token_owner_id);

            return JSON::successResponse($response, 201, [
                "type" => "resource",
                "server" => $server
            ]);
        } catch (\Throwable $exception) {
            return JSON::errorResponse($response, 500, "The Server creation failed.");
        }
    }

    /**
     * @api {patch} /servers/:id/ Update
     * @apiGroup Servers
     *
     * @apiDescription Updates a Server's information.
     *
     * @apiParam {String} [name] The new Server's name.
     * @apiParam {String} [image_url] The new URL of the Server's image.
     *
     * @apiParamExample {json} Request-Example:
     *     {
     *       "name": "My Super Cool Server Updated",
     *       "image_url": "/images/c29eaa26-3fd1-4b66-aafe-60b571009d0d"
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
     *       "server": {
     *         "id": "db0916fa-934b-4981-9980-d53bed190db3",
     *         "name": "My Super Cool Server Updated",
     *         "image_url": "/images/c29eaa26-3fd1-4b66-aafe-60b571009d0d",
     *         "owner_id": "db0916fa-934b-4981-9980-d53bed190db3"
     *       }
     *     }
     *
     * @apiError ServerNotFound The UUID of the Server was not found.
     * @apiError NotServerOwner A Member tries to modify the Server.
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
     * @apiErrorExample NotServerOwner-Response:
     *     HTTP/1.1 401 UNAUTHORIZED
     *     {
     *       "type": "error",
     *       "error": 401,
     *       "message": "Only the Server's Owner can modify the Server."
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
        $server = $request->getAttribute('server');
        $token_owner_id = $request->getAttribute('token_owner_id');
        $body = $request->getParsedBody();

        if ($server->owner_id !== $token_owner_id) {
            return JSON::errorResponse($response, 401, "Only the Server's Owner can modify the Server.");
        }

        try {
            $server->name = isset($body['name']) ? $body['name'] : $server->name;
            $server->image_url = isset($body['image_url']) ? $body['image_url'] : $server->image_url;
            $server->saveOrFail();

            return JSON::successResponse($response, 200, [
                "type" => "resource",
                "user" => $server
            ]);
        } catch (\Throwable $exception) {
            return JSON::errorResponse($response, 500, "The Server update failed.");
        }
    }

    /**
     * @api {delete} /servers/:id/ Delete
     * @apiGroup Servers
     *
     * @apiDescription Deletes a Server.
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
     *       "server": {
     *         "id": "db0916fa-934b-4981-9980-d53bed190db3",
     *         "name": "My Super Cool Server",
     *         "image_url": "/images/c29eaa26-3fd1-4b66-aafe-60b571009d0d",
     *         "owner_id": "db0916fa-934b-4981-9980-d53bed190db3"
     *       }
     *     }
     *
     * @apiError ServerNotFound The UUID of the Server was not found.
     * @apiError NotServerOwner A Member tries to delete the Server.
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
     * @apiErrorExample NotServerOwner-Response:
     *     HTTP/1.1 401 UNAUTHORIZED
     *     {
     *       "type": "error",
     *       "error": 401,
     *       "message": "Only the Server's Owner can delete the Server."
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
    public function delete(Request $request, Response $response, $args) {
        $server = $request->getAttribute('server');
        $token_owner_id = $request->getAttribute('token_owner_id');

        if ($server->owner_id !== $token_owner_id) {
            return JSON::errorResponse($response, 401, "Only the Server's Owner can delete the Server.");
        }

        try {
            $server->delete();

            unset($server->textChannels);

            return JSON::successResponse($response, 200, [
                "type" => "resource",
                "user" => $server
            ]);
        } catch (\Exception $exception) {
            return JSON::errorResponse($response, 500, "The Server failed to delete.");
        }
    }

    /**
     * @api {put} /servers/:server_id/users/:user_id/ Add User
     * @apiGroup Servers
     *
     * @apiDescription Adds a User to a Server.
     *
     * @apiHeader {String} Authorization The User's token.
     *
     * @apiHeaderExample {json} Bearer Token:
     *     {
     *       "Authorization": "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJhcGlfcGxheWVyIiwic3ViIjoiZ2FtZSIsImF1ZCI6InBsYXllciIsImlhdCI6MTU4NDc0NTQ0NywiZXhwIjoxNTg0NzU2MjQ3fQ.vkaSPuOdb95IHWRFda9RGszEflYh8CGxhaKVHS3vredJSl2WyqqNTg_VUbfkx60A3cdClmcBqmyQdJnV3-l1xA"
     *     }
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 201 CREATED
     *     {
     *       "type": "resource",
     *       "server": {
     *         "id": "db0916fa-934b-4981-9980-d53bed190db3",
     *         "name": "My Super Cool Server",
     *         "image_url": "/images/c29eaa26-3fd1-4b66-aafe-60b571009d0d",
     *         "owner_id": "db0916fa-934b-4981-9980-d53bed190db3"
     *       },
     *       "user": {
     *         "id": "db0916fa-934b-4981-9980-d53bed190db3",
     *         "username": "AlbertEinsteinUpdated",
     *         "email": "albert.einstein@physics.com",
     *         "avatar_url": "/images/c29eaa26-3fd1-4b66-aafe-60b571009d0d"
     *       }
     *     }
     *
     * @apiError ServerNotFound The UUID of the Server was not found.
     * @apiError UserNotFound The UUID of the User was not found.
     * @apiError UserAlreadyInServer The User is already in the Server.
     * @apiError NotMemberOfServer The token Owner is not a Member of the Server.
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
     * @apiErrorExample UserNotFound-Response:
     *     HTTP/1.1 404 NOT FOUND
     *     {
     *       "type": "error",
     *       "error": 404,
     *       "message": "User with ID db0916fa-934b-4981-9980-d53bed190db3 doesn't exist."
     *     }
     *
     * @apiErrorExample UserAlreadyInServer-Response:
     *     HTTP/1.1 422 UNPROCESSABLE ENTITY
     *     {
     *       "type": "error",
     *       "error": 422,
     *       "message": "The User with ID db0916fa-934b-4981-9980-d53bed190db3 is already a Member of the Server with ID db0916fa-934b-4981-9980-d53bed190db3."
     *     }
     *
     * @apiErrorExample NotMemberOfServer-Response:
     *     HTTP/1.1 401 UNAUTHORIZED
     *     {
     *       "type": "error",
     *       "error": 401,
     *       "message": "You are not allowed to add Users to a Server you are not a Member of."
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
    public function addUser(Request $request, Response $response, $args) {
        $server = $request->getAttribute('server');
        $user = $request->getAttribute('user');
        $token_owner_id = $request->getAttribute('token_owner_id');
        $tokenOwnerInServer = $server->members()->where('user_id', '=', $token_owner_id)->exists();
        $userAlreadyInServer = $server->members()->where('user_id', '=', $user->id)->exists();

        if (!$userAlreadyInServer && $tokenOwnerInServer) {
            $server->members()->attach($user->id);

            unset($user->password);
            unset($user->token);
            unset($server->textChannels);

            return JSON::successResponse($response, 201, [
                "type" => "resource",
                "server" => $server,
                "user" => $user
            ]);
        } else if ($userAlreadyInServer && $tokenOwnerInServer) {
            return JSON::errorResponse($response, 422, "The User with ID ".$user->id." is already a Member of the Server with ID ".$server->id.".");
        } else if (!$tokenOwnerInServer) {
            return JSON::errorResponse($response, 401, "You are not allowed to add Users to a Server you are not a Member of.");
        }
    }

    /**
     * @api {delete} /servers/:server_id/users/:user_id/ Remove User
     * @apiGroup Servers
     *
     * @apiDescription Removes a User from a Server.
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
     *       "server": {
     *         "id": "db0916fa-934b-4981-9980-d53bed190db3",
     *         "name": "My Super Cool Server",
     *         "image_url": "/images/c29eaa26-3fd1-4b66-aafe-60b571009d0d",
     *         "owner_id": "db0916fa-934b-4981-9980-d53bed190db3"
     *       },
     *       "user": {
     *         "id": "db0916fa-934b-4981-9980-d53bed190db3",
     *         "username": "AlbertEinsteinUpdated",
     *         "email": "albert.einstein@physics.com",
     *         "avatar_url": "/images/c29eaa26-3fd1-4b66-aafe-60b571009d0d"
     *       }
     *     }
     *
     * @apiError ServerNotFound The UUID of the Server was not found.
     * @apiError UserNotFound The UUID of the User was not found.
     * @apiError UserNotInServer The User is not a Member of the Server.
     * @apiError NotMemberOfServer The token Owner is not a Member of the Server.
     * @apiError ServerOwnerLeaves The Server Owner tries to leave the Server.
     * @apiError MemberKicksMember A Member tries to kick another Member from the Server.
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
     * @apiErrorExample UserNotFound-Response:
     *     HTTP/1.1 404 NOT FOUND
     *     {
     *       "type": "error",
     *       "error": 404,
     *       "message": "User with ID db0916fa-934b-4981-9980-d53bed190db3 doesn't exist."
     *     }
     *
     * @apiErrorExample UserNotInServer-Response:
     *     HTTP/1.1 401 UNAUTHORIZED
     *     {
     *       "type": "error",
     *       "error": 401,
     *       "message": "The User with ID db0916fa-934b-4981-9980-d53bed190db3 is not a Member of the Server with ID db0916fa-934b-4981-9980-d53bed190db3."
     *     }
     *
     * @apiErrorExample NotMemberOfServer-Response:
     *     HTTP/1.1 401 UNAUTHORIZED
     *     {
     *       "type": "error",
     *       "error": 401,
     *       "message": "You are not allowed to kick Members from a Server you are not a Member of."
     *     }
     *
     * @apiErrorExample ServerOwnerLeaves-Response:
     *     HTTP/1.1 401 UNAUTHORIZED
     *     {
     *       "type": "error",
     *       "error": 401,
     *       "message": "Server Owners can't leave their own Servers."
     *     }
     *
     * @apiErrorExample MemberKicksMember-Response:
     *     HTTP/1.1 401 UNAUTHORIZED
     *     {
     *       "type": "error",
     *       "error": 401,
     *       "message": "Only the Server's Owner can kick Members."
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
    public function kickUser(Request $request, Response $response, $args) {
        $server = $request->getAttribute('server');
        $user = $request->getAttribute('user');
        $token_owner_id = $request->getAttribute('token_owner_id');

        if (!$server->members()->where('user_id', '=', $token_owner_id)->exists()) {
            // When the token Owner is not a Member of the Server.
            return JSON::errorResponse($response, 401, "You are not allowed to kick Members from a Server you are not a Member of.");
        }
        if (!$server->members()->where('user_id', '=', $user->id)->exists()) {
            // When the User is not a Member of the Server.
            return JSON::errorResponse($response, 401, "The User with ID ".$user->id." is not a Member of the Server with ID ".$server->id.".");
        }

        if (($user->id === $token_owner_id && $server->owner_id !== $token_owner_id) || ($user->id !== $token_owner_id && $server->owner_id === $token_owner_id)) {
            // When a Member wants to leave the server OR when the Server's Owner wants to kick a Member from the Server
            $server->members()->detach($user->id);

            unset($user->password);
            unset($user->token);
            unset($server->textChannels);

            return JSON::successResponse($response, 200, [
                "type" => "resource",
                "server" => $server,
                "user" => $user
            ]);
        } else if ($server->owner_id === $token_owner_id && $user->id === $token_owner_id) {
            // When the Server's Owner tries to leave their own Server.
            return JSON::errorResponse($response, 401, "Server Owners can't leave their own servers.");

        } else if ($user->id !== $token_owner_id && $server->owner_id !== $token_owner_id) {
            // When a Member of the Server tries to kick someone without being the Server's Owner.
            return JSON::errorResponse($response, 401, "Only the Server's Owner can kick Members.");
        }
    }
}