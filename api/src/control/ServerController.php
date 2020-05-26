<?php
namespace OpenDiscard\api\control;

use OpenDiscard\api\common\writer\JSON;
use OpenDiscard\api\model\Server;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Ramsey\Uuid\Uuid;

class ServerController {
    protected $container;

    public function __construct(\Slim\Container $container = null) {
        $this->container = $container;
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
     *         "avatar_url": "/images/c29eaa26-3fd1-4b66-aafe-60b571009d0d",
     *         "owner_id": "db0916fa-934b-4981-9980-d53bed190db3"
     *       }
     *     }
     *
     * @apiError InvalidToken The User's token is not valid.
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
     *         "avatar_url": "/images/c29eaa26-3fd1-4b66-aafe-60b571009d0d",
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
     * @apiError UserAlreadyInServer The User is already in the Server.
     * @apiError NotMemberOfServer The token Owner is not a Member of the Server.
     * @apiError InvalidToken The User's token is not valid.
     *
     * @apiErrorExample UserAlreadyInServer-Response:
     *     HTTP/1.1 422 UNPROCESSABLE ENTITY
     *     {
     *       "type": "error",
     *       "error": 422,
     *       "message": "The User with ID db0916fa-934b-4981-9980-d53bed190db3 is already a Member of the Server with ID db0916fa-934b-4981-9980-d53bed190db3"
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

            return JSON::successResponse($response, 201, [
                "type" => "resource",
                "server" => $server,
                "user" => $user
            ]);
        } else if ($userAlreadyInServer && $tokenOwnerInServer) {
            return JSON::errorResponse($response, 422, "The User with ID ".$user->id." is already a Member of the Server with ID ".$server->id);
        } else if (!$tokenOwnerInServer) {
            return JSON::errorResponse($response, 401, "You are not allowed to add Users to a Server you are not a Member of.");
        }
    }
}