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

        try {
            $server = new Server();
            $server->id = Uuid::uuid4();
            $server->name = $body['name'];
            $server->image_url = isset($body['image_url']) ? $body['image_url'] : null;
            $server->owner_id = $request->getAttribute('user_id');
            $server->saveOrFail();

            return JSON::successResponse($response, 201, [
                "type" => "resource",
                "server" => $server
            ]);
        } catch (\Throwable $exception) {
            return JSON::errorResponse($response, 500, "The Server creation failed.");
        }
    }
}