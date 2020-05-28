<?php
namespace OpenDiscard\api\control;

use OpenDiscard\api\common\writer\JSON;
use OpenDiscard\api\model\Message;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Ramsey\Uuid\Uuid;

class MessageController {
    protected $container;

    public function __construct(\Slim\Container $container = null) {
        $this->container = $container;
    }

    /**
     * @api {post} /channels/:id/messages/ Create
     * @apiGroup Messages
     *
     * @apiDescription Creates a Message.
     *
     * @apiParam {String} content The Message's content.
     *
     * @apiParamExample {json} Request-Example:
     *     {
     *       "content": "My Super Cool Message"
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
     *       "message": {
     *         "id": "db0916fa-934b-4981-9980-d53bed190db3",
     *         "content": "My Super Cool Message",
     *         "created_at": "2020-05-28 17:05:47",
     *         "updated_at": "2020-05-28 17:05:47",
     *         "user_id": "db0916fa-934b-4981-9980-d53bed190db3",
     *         "channel_id": "db0916fa-934b-4981-9980-d53bed190db3"
     *       }
     *     }
     *
     * @apiError TextChannelNotFound The UUID of the Text Channel was not found.
     * @apiError NotServerMember A Non-Member tries to create a Message.
     * @apiError InvalidToken The token is not valid.
     *
     * @apiErrorExample ServerNotFound-Response:
     *     HTTP/1.1 404 NOT FOUND
     *     {
     *       "type": "error",
     *       "error": 404,
     *       "message": "Text Channel with ID db0916fa-934b-4981-9980-d53bed190db3 doesn't exist."
     *     }
     *
     * @apiErrorExample NotServerMember-Response:
     *     HTTP/1.1 401 UNAUTHORIZED
     *     {
     *       "type": "error",
     *       "error": 401,
     *       "message": "Only Members can create Messages."
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
        $textChannel = $request->getAttribute('text_channel');
        $token_owner_id = $request->getAttribute('token_owner_id');

        if (!$textChannel->server->members()->where('user_id', '=', $token_owner_id)->exists()) {
            return JSON::errorResponse($response, 401, "Only Members can create Messages.");
        }

        try {
            $message = new Message();
            $message->id = Uuid::uuid4();
            $message->content = $body['content'];
            $message->user_id = $token_owner_id;
            $message->channel_id = $textChannel->id;
            $message->saveOrFail();

            return JSON::successResponse($response, 201, [
                "type" => "resource",
                "message" => $message
            ]);
        } catch (\Throwable $exception) {
            return JSON::errorResponse($response, 500, "The Message creation failed.");
        }
    }

    /**
     * @api {patch} /messages/:id/ Update
     * @apiGroup Messages
     *
     * @apiDescription Updates a Message.
     *
     * @apiParam {String} content The new Message's content.
     *
     * @apiParamExample {json} Request-Example:
     *     {
     *       "content": "My Super Cool Message Updated"
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
     *       "message": {
     *         "id": "db0916fa-934b-4981-9980-d53bed190db3",
     *         "content": "My Super Cool Message Updated",
     *         "created_at": "2020-05-28 17:05:47",
     *         "updated_at": "2020-05-28 17:10:34",
     *         "user_id": "db0916fa-934b-4981-9980-d53bed190db3",
     *         "channel_id": "db0916fa-934b-4981-9980-d53bed190db3"
     *       }
     *     }
     *
     * @apiError MessageNotFound The UUID of the Message was not found.
     * @apiError NotMessageAuthor A Non-Author tries to update the Message.
     * @apiError InvalidToken The token is not valid.
     *
     * @apiErrorExample MessageNotFound-Response:
     *     HTTP/1.1 404 NOT FOUND
     *     {
     *       "type": "error",
     *       "error": 404,
     *       "message": "Message with ID db0916fa-934b-4981-9980-d53bed190db3 doesn't exist."
     *     }
     *
     * @apiErrorExample NotMessageAuthor-Response:
     *     HTTP/1.1 401 UNAUTHORIZED
     *     {
     *       "type": "error",
     *       "error": 401,
     *       "message": "Only the Message Author can modify this Message."
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
        $message = $request->getAttribute('message');
        $token_owner_id = $request->getAttribute('token_owner_id');

        if ($message->user_id !== $token_owner_id) {
            return JSON::errorResponse($response, 401, "Only the Message Author can modify this Message.");
        }

        try {
            $message->content = isset($body['content']) ? $body['content'] : $message->content;
            $message->saveOrFail();

            return JSON::successResponse($response, 200, [
                "type" => "resource",
                "message" => $message
            ]);
        } catch (\Throwable $exception) {
            return JSON::errorResponse($response, 500, "The Message update failed.");
        }
    }

    /**
     * @api {delete} /messages/:id/ Delete
     * @apiGroup Messages
     *
     * @apiDescription Deletes a Message.
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
     *       "message": {
     *         "id": "db0916fa-934b-4981-9980-d53bed190db3",
     *         "content": "My Super Cool Message Updated",
     *         "created_at": "2020-05-28 17:05:47",
     *         "updated_at": "2020-05-28 17:10:34",
     *         "user_id": "db0916fa-934b-4981-9980-d53bed190db3",
     *         "channel_id": "db0916fa-934b-4981-9980-d53bed190db3"
     *       }
     *     }
     *
     * @apiError MessageNotFound The UUID of the Message was not found.
     * @apiError NotMessageAuthor A Non-Author tries to delete the Message.
     * @apiError InvalidToken The token is not valid.
     *
     * @apiErrorExample MessageNotFound-Response:
     *     HTTP/1.1 404 NOT FOUND
     *     {
     *       "type": "error",
     *       "error": 404,
     *       "message": "Message with ID db0916fa-934b-4981-9980-d53bed190db3 doesn't exist."
     *     }
     *
     * @apiErrorExample NotMessageAuthor-Response:
     *     HTTP/1.1 401 UNAUTHORIZED
     *     {
     *       "type": "error",
     *       "error": 401,
     *       "message": "Only the Message Author can delete this Message."
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
        $message = $request->getAttribute('message');
        $token_owner_id = $request->getAttribute('token_owner_id');

        if ($message->user_id !== $token_owner_id) {
            return JSON::errorResponse($response, 401, "Only the Message Author can delete this Message.");
        }

        try {
            $message->delete();

            return JSON::successResponse($response, 200, [
                "type" => "resource",
                "message" => $message
            ]);
        } catch (\Exception $exception) {
            return JSON::errorResponse($response, 500, "The Message failed to delete.");
        }
    }
}