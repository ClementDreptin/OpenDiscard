<?php
namespace OpenDiscard\api\control;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class DocsController {
    public function renderDocsHtmlFile(Request $request, Response $response, $args) {
        return $response->write(file_get_contents('docs/index.html'));
    }

    public function redirectTowardsDocs(Request $request, Response $response, $args) {
        $scheme = $request->getUri()->getScheme();
        $host = $request->getUri()->getHost();
        $port = $request->getUri()->getPort();
        $path = $request->getUri()->getPath();
        $docURL = "$scheme://$host:$port$path"."docs/";

        $response = $response->withHeader("Location", $docURL);
        return $response;
    }
}