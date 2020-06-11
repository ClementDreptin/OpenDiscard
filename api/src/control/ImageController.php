<?php
namespace OpenDiscard\api\control;

use OpenDiscard\api\common\writer\JSON;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ImageController {
    protected $container;

    public function __construct(\Slim\Container $container = null) {
        $this->container = $container;
    }

    /**
     * @api {post} /images/ Upload
     * @apiGroup Images
     *
     * @apiDescription Uploads an Image.
     *
     * @apiParam {String} image The base64 encoded Image.
     *
     * @apiParamExample {json} Request-Example:
     *     {
     *       "image": "iVBORw0KGgoAAAANSUhEUgAAAZAAAAGQCAYAAACA..."
     *     }
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 201 CREATED
     *     {
     *       "type": "resource",
     *       "url": "/images/db0916fa-934b-4981-9980-d53bed190db3"
     *     }
     *
     * @apiError WrongMIMEType The MIME type of the file doesn't correspond to an Image.
     * @apiError ImageTooLarge The Image is larger than 8MB.
     *
     * @apiErrorExample WrongMIMEType-Response:
     *     HTTP/1.1 415 UNSUPPORTED MEDIA TYPE
     *     {
     *       "type": "error",
     *       "error": 415,
     *       "message": "You must upload an image."
     *     }
     *
     * @apiErrorExample ImageTooLarge-Response:
     *     HTTP/1.1 413 PAYLOAD TOO LARGE
     *     {
     *       "type": "error",
     *       "error": 413,
     *       "message": "Image is too large, you can upload Images up to 8MB."
     *     }
     */
    public function upload(Request $request, Response $response, $args) {
        $image_name = $request->getAttribute('image_name');

        return JSON::successResponse($response, 201, [
            "type" => "resource",
            "url" => "/images/$image_name"
        ]);
    }

    /**
     * @api {get} /images/:id Get
     * @apiGroup Images
     *
     * @apiDescription Gets an Image.
     *
     * @apiSuccess {Image} image The Image.
     *
     * @apiError ImageNotFound The UUID of the Image was not found.
     *
     * @apiErrorExample ImageNotFound-Response:
     *     HTTP/1.1 404 NOT FOUND
     *     {
     *       "type": "error",
     *       "error": 404,
     *       "message": "Image with ID db0916fa-934b-4981-9980-d53bed190db3 doesn't exist."
     *     }
     */
    public function get(Request $request, Response $response, $args) {
        $image = $request->getAttribute('image');
        $type = $request->getAttribute('type');

        $response = $response->withStatus(200)
            ->withHeader("Content-Type", $type);

        $response->getBody()->write($image);

        return $response;
    }
}