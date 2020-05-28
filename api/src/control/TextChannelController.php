<?php
namespace OpenDiscard\api\control;

use OpenDiscard\api\common\writer\JSON;
use OpenDiscard\api\model\TextChannel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Ramsey\Uuid\Uuid;

class TextChannelController {
    protected $container;

    public function __construct(\Slim\Container $container = null) {
        $this->container = $container;
    }

    /**
     * @api {post} /servers/:id/channels/ Create
     * @apiGroup Text Channels
     *
     * @apiDescription Creates a Text Channel.
     *
     * @apiParam {String} name The Server's name.
     *
     * @apiParamExample {json} Request-Example:
     *     {
     *       "name": "My Super Cool Text Channel"
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
     *       "text_channel": {
     *         "id": "db0916fa-934b-4981-9980-d53bed190db3",
     *         "name": "My Super Cool Text Channel",
     *         "server_id": "db0916fa-934b-4981-9980-d53bed190db3"
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
     *       "message": "Only the Server's Owner can create Text Channels."
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
    public function create(Request $request, Response $response, $args) {
        $body = $request->getParsedBody();
        $server = $request->getAttribute('server');
        $token_owner_id = $request->getAttribute('token_owner_id');

        if ($server->owner_id !== $token_owner_id) {
            return JSON::errorResponse($response, 401, "Only the Server's Owner can create Text Channels");
        }

        try {
            $textChannel = new TextChannel();
            $textChannel->id = Uuid::uuid4();
            $textChannel->name = $body['name'];
            $textChannel->server_id = $server->id;
            $textChannel->saveOrFail();

            return JSON::successResponse($response, 201, [
                "type" => "resource",
                "text_channel" => $textChannel
            ]);
        } catch (\Throwable $exception) {
            return JSON::errorResponse($response, 500, "The Text Channel creation failed.");
        }
    }
}