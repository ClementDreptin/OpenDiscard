<?php
namespace OpenDiscard\api\control;

use OpenDiscard\api\common\writer\JSON;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Ramsey\Uuid\Uuid;

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
     * @apiParam {File} image The Image file.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 201 CREATED
     *     {
     *       "type": "resource",
     *       "url": "/images/db0916fa-934b-4981-9980-d53bed190db3.png"
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
        if (!isset($request->getUploadedFiles()['image'])) {
            return JSON::errorResponse($response, 400, "Incorrect format in parameters. Details: You must provide an image parameter.");
        }

        $file = $request->getUploadedFiles()['image'];

        if ($file->getError() !== UPLOAD_ERR_OK) return JSON::errorResponse($response, 500, "An error occurred.");

        if (substr(mime_content_type($file->file), 0, 5) !== 'image') return JSON::errorResponse($response, 415, "You must upload an image.");

        if ($file->getSize() > 8 * 1024 * 1024) return JSON::errorResponse($response, 413, "Image is too large, you can upload Images up to 8MB.");

        $upload_dir = $this->container->settings['upload_dir'];
        $filename = Uuid::uuid4()->__toString();
        $extension = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);
        $file->moveTo("$upload_dir/$filename.$extension");

        return JSON::successResponse($response, 201, [
            "type" => "resource",
            "url" => "/images/$filename"
        ]);
    }

    /**
     * @api {get} /images/:id Get
     * @apiGroup Images
     *
     * @apiDescription Gets an Image encoded in Base64.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "type": "resource",
     *       "image": "iVBORw0KGgoAAAANSUhEUgAAAZAAAAGQCAYAAACA",
     *       "mimetype": "image/png"
     *     }
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

        return JSON::successResponse($response, 200, [
            "type" => "resource",
            "image" => base64_encode($image),
            "mimetype" => $type
        ]);
    }
}