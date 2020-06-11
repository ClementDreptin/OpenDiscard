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
     * @api {get} /channels/:id/messages?page=:page&size=:size&order=:order&authors=:authors Get
     * @apiGroup Messages
     *
     * @apiDescription Gets Messages from a Text Channel.
     *
     * @apiParam {Number} [page] The page number.
     * @apiParam {Number} [size] The amount of Messages per page.
     * @apiParam {String=asc,desc} [order] The order.
     * @apiParam {Bool=true,false} [authors] Whether to get the Messages' Authors or not.
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
     *       "links": {
     *         "next": {
     *           "href": "/channels/db0916fa-934b-4981-9980-d53bed190db3/messages?page=2&size=2&order=desc"
     *         },
     *         "prev": {
     *           "href": "/channels/db0916fa-934b-4981-9980-d53bed190db3/messages?page=1&size=2&order=desc"
     *         },
     *         "last": {
     *           "href": "/channels/db0916fa-934b-4981-9980-d53bed190db3/messages?page=5&size=2&order=desc"
     *         },
     *         "first": {
     *           "href": "/channels/db0916fa-934b-4981-9980-d53bed190db3/messages?page=1&size=2&order=desc"
     *         }
     *       },
     *       "messages": [
     *         {
     *           "id": "db0916fa-934b-4981-9980-d53bed190db3",
     *           "content": "My Other Super Cool Message",
     *           "created_at": "2020-05-28 17:07:30",
     *           "updated_at": "2020-05-28 17:07:30",
     *           "user_id": "db0916fa-934b-4981-9980-d53bed190db3",
     *           "channel_id": "db0916fa-934b-4981-9980-d53bed190db3"
     *           "author": {
     *             "id": "db0916fa-934b-4981-9980-d53bed190db3",
     *             "username": "AlbertEinstein",
     *             "email": "albert.einstein@physics.com",
     *             "avatar_url": "/images/c29eaa26-3fd1-4b66-aafe-60b571009d0d"
     *           }
     *         },
     *         {
     *           "id": "db0916fa-934b-4981-9980-d53bed190db3",
     *           "content": "My Super Cool Message",
     *           "created_at": "2020-05-28 17:05:47",
     *           "updated_at": "2020-05-28 17:05:47",
     *           "user_id": "db0916fa-934b-4981-9980-d53bed190db3",
     *           "channel_id": "db0916fa-934b-4981-9980-d53bed190db3",
     *           "author": {
     *             "id": "db0916fa-934b-4981-9980-d53bed190db3",
     *             "username": "AlbertEinstein",
     *             "email": "albert.einstein@physics.com",
     *             "avatar_url": "/images/c29eaa26-3fd1-4b66-aafe-60b571009d0d"
     *           }
     *         }
     *       ]
     *     }
     *
     * @apiError TextChannelNotFound The UUID of the Text Channel was not found.
     * @apiError NotServerMember A Non-Member tries to get Messages.
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
     * @apiErrorExample NotServerMember-Response:
     *     HTTP/1.1 401 UNAUTHORIZED
     *     {
     *       "type": "error",
     *       "error": 401,
     *       "message": "Only Members can get Messages."
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
        $textChannel = $request->getAttribute('text_channel');
        $token_owner_id = $request->getAttribute('token_owner_id');
        $with_authors = $request->getAttribute('with_authors');

        if (!$textChannel->server->members()->where('user_id', '=', $token_owner_id)->exists()) {
            return JSON::errorResponse($response, 401, "Only Members can get Messages.");
        }

        $page = $request->getQueryParam('page', 1);
        $size = $request->getQueryParam('size', 10);
        $order = $request->getQueryParam('order', 'desc');

        $order = $order !== 'asc' || $order !== 'desc' ? 'desc' : $order;

        $messages = Message::query()->where('channel_id', '=', $textChannel->id);

        if ($with_authors) {
            $messages->with([
                'author' => function ($query) {
                    $query->select('id', 'username', 'email', 'avatar_url');
                }
            ]);
        }

        $total = $messages->count();

        if ($size > $total) {
            $page = 1;
        } else if (($page * $size) > $total) {
            $page = intdiv($total, $size) + 1;
        }

        $messages = $messages->offset(($page-1)*$size)
            ->limit($size)
            ->orderBy('created_at', $order)
            ->get();

        return JSON::successResponse($response, 200, [
            "type" => "resources",
            "links" => [
                "next" => ["href" => "/channels/".$textChannel->id."/messages?page=".(ceil($total / $size) > $page ? $page + 1 : ceil($total / $size))."&size=$size"],
                "prev" => ["href" => "/channels/".$textChannel->id."/messages?page=".($page > 1 ? $page-1 : $page)."&size=$size"],
                "last" => ["href" => "/channels/".$textChannel->id."/messages?page=".(ceil($total / $size))."&size=$size"],
                "first" => ["href" => "/channels/".$textChannel->id."/messages?page=1&size=$size"]
            ],
            "messages" => $messages
        ]);
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
     * @apiErrorExample TextChannelNotFound-Response:
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
     * @apiParam {String} [content] The new Message's content.
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