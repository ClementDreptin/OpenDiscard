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
     * @api {get} /servers/:id/channels/ Get
     * @apiGroup Text Channels
     *
     * @apiDescription Gets all the Text Channels from a Server.
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
     *       "text_channels": [
     *         {
     *           "id": "db0916fa-934b-4981-9980-d53bed190db3",
     *           "name": "My Super Cool Text Channel",
     *           "server_id": "db0916fa-934b-4981-9980-d53bed190db3"
     *         },
     *         {
     *           "id": "db0916fa-934b-4981-9980-d53bed190db3",
     *           "name": "My Other Super Cool Text Channel",
     *           "server_id": "db0916fa-934b-4981-9980-d53bed190db3"
     *         }
     *       ]
     *     }
     *
     * @apiError ServerNotFound The UUID of the Server was not found.
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
        $tokenOwnerInServer = $server->members()->where('user_id', '=', $token_owner_id)->exists();

        if (!$tokenOwnerInServer) {
            return JSON::errorResponse($response, 401, "Only Members can get Text Channels from this Server.");
        }

        return JSON::successResponse($response, 200, [
            "type" => "resources",
            "text_channels" => $server->textChannels
        ]);
    }

    /**
     * @api {post} /servers/:id/channels/ Create
     * @apiGroup Text Channels
     *
     * @apiDescription Creates a Text Channel.
     *
     * @apiParam {String} name The Text Channel's name.
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
     * @apiError NotServerOwner A Member tries to create a Text Channel.
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

    /**
     * @api {patch} /channels/:id/ Update
     * @apiGroup Text Channels
     *
     * @apiDescription Updates a Text Channel's information.
     *
     * @apiParam {String} [name] The new Text Channel's name.
     *
     * @apiParamExample {json} Request-Example:
     *     {
     *       "name": "My Super Cool Text Channel Updated"
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
     *       "text_channel": {
     *         "id": "db0916fa-934b-4981-9980-d53bed190db3",
     *         "name": "My Super Cool Text Channel Updated",
     *         "server_id": "db0916fa-934b-4981-9980-d53bed190db3"
     *       }
     *     }
     *
     * @apiError TextChannelNotFound The UUID of the Text Channel was not found.
     * @apiError NotServerOwner A Member tries to modify the Text Channel.
     * @apiError InvalidToken The token is not valid.
     *
     * @apiErrorExample TextChannelNotFound-Response:
     *     HTTP/1.1 404 NOT FOUND
     *     {
     *       "type": "error",
     *       "error": 404,
     *       "message": "Text Channel with ID db0916fa-934b-4981-9980-d53bed190db3 doesn't exist."
     *     }
     *
     * @apiErrorExample NotServerOwner-Response:
     *     HTTP/1.1 401 UNAUTHORIZED
     *     {
     *       "type": "error",
     *       "error": 401,
     *       "message": "Only the Server's Owner can modify Text Channels."
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
        $body = $request->getParsedBody();
        $textChannel = $request->getAttribute('text_channel');
        $token_owner_id = $request->getAttribute('token_owner_id');

        if ($textChannel->server->owner_id !== $token_owner_id) {
            return JSON::errorResponse($response, 401, "Only the Server's Owner can modify Text Channels");
        }

        try {
            $textChannel->name = isset($body['name']) ? $body['name'] : $textChannel->name;
            $textChannel->saveOrFail();

            unset($textChannel->server);

            return JSON::successResponse($response, 200, [
                "type" => "resource",
                "text_channel" => $textChannel
            ]);
        } catch (\Throwable $exception) {
            return JSON::errorResponse($response, 500, "The Text Channel update failed.");
        }
    }

    /**
     * @api {delete} /channels/:id/ Delete
     * @apiGroup Text Channels
     *
     * @apiDescription Deletes a Text Channel.
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
     *       "text_channel": {
     *         "id": "db0916fa-934b-4981-9980-d53bed190db3",
     *         "name": "My Super Cool Text Channel Updated",
     *         "server_id": "db0916fa-934b-4981-9980-d53bed190db3"
     *       }
     *     }
     *
     * @apiError TextChannelNotFound The UUID of the Text Channel was not found.
     * @apiError NotServerOwner A Member tries to delete the Text Channel.
     * @apiError InvalidToken The token is not valid.
     *
     * @apiErrorExample TextChannelNotFound-Response:
     *     HTTP/1.1 404 NOT FOUND
     *     {
     *       "type": "error",
     *       "error": 404,
     *       "message": "Text Channel with ID db0916fa-934b-4981-9980-d53bed190db3 doesn't exist."
     *     }
     *
     * @apiErrorExample NotServerOwner-Response:
     *     HTTP/1.1 401 UNAUTHORIZED
     *     {
     *       "type": "error",
     *       "error": 401,
     *       "message": "Only the Server's Owner can delete Text Channels."
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
        $textChannel = $request->getAttribute('text_channel');
        $token_owner_id = $request->getAttribute('token_owner_id');

        if ($textChannel->server->owner_id !== $token_owner_id) {
            return JSON::errorResponse($response, 401, "Only the Server's Owner can delete Text Channels.");
        }

        try {
            $textChannel->delete();

            unset($textChannel->server);

            return JSON::successResponse($response, 200, [
                "type" => "resource",
                "user" => $textChannel
            ]);
        } catch (\Exception $exception) {
            return JSON::errorResponse($response, 500, "The Text Channel failed to delete.");
        }
    }
}