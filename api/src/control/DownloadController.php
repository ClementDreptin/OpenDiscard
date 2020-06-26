<?php
namespace OpenDiscard\api\control;

use OpenDiscard\api\common\writer\JSON;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class DownloadController {
    protected $container;

    public function __construct(\Slim\Container $container = null) {
        $this->container = $container;
    }

    /**
     * @api {get} /download?platform=:platform&format=:format Get
     * @apiGroup Download
     *
     * @apiDescription Gets a File.
     *
     * @apiParam {String=win,linux} platform The platform.
     * @apiParam {String=msi,deb,tar.gz} format The format.
     *
     * @apiSuccess {File} file The File.
     *
     * @apiError IncorrectValue Incorrect value in the parameters.
     *
     * @apiErrorExample ImageNotFound-Response:
     *     HTTP/1.1 400 BAD REQQUEST
     *     {
     *       "type": "error",
     *       "error": 400,
     *       "message": "Incorrect value in at least one parameter. Please check the docs for the possible values."
     *     }
     */
    public function download(Request $request, Response $response, $args) {
        $platform = $request->getQueryParam('platform');
        $format = $request->getQueryParam('format');

        if (!isset($platform) || !isset($format)) {
            return JSON::errorResponse($response, 400, "You need to provide a platform and a format in the respective parameters.");
        }

        $extension = "";

        if ($platform === "win") {
            if ($format === "msi") {
                $extension = "msi";
            } else {
                return JSON::errorResponse($response, 400, "Incorrect value in at least one parameter. Please check the docs for the possible values.");
            }
        } else if ($platform === "linux") {
            if ($format === "deb") {
                $extension = "deb";
            } else if ($format === "tar.gz") {
                $extension = "tar.gz";
            } else {
                return JSON::errorResponse($response, 400, "Incorrect value in at least one parameter. Please check the docs for the possible values.");
            }
        } else {
            return JSON::errorResponse($response, 400, "Incorrect value in at least one parameter. Please check the docs for the possible values.");
        }

        $match = glob($this->container->settings['download_dir'].'/*.'.$extension);
        if (empty($match)) {
            return JSON::errorResponse($response, 500, "Couldn't get the file.");
        }

        $file = file_get_contents($match[0]);
        $type = mime_content_type($match[0]);

        $response = $response->withStatus(200)
            ->withHeader("Content-Type", $type);

        $response->getBody()->write($file);

        return $response;
    }
}