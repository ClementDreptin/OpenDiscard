<?php
namespace OpenDiscard\api\common\middleware;

use OpenDiscard\api\common\writer\JSON;
use OpenDiscard\api\model\Message;
use OpenDiscard\api\model\Server;
use OpenDiscard\api\model\TextChannel;
use OpenDiscard\api\model\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Ramsey\Uuid\Uuid;

class Checker {
    protected $container;

    public function __construct(\Slim\Container $container = null) {
        $this->container = $container;
    }

    public function userExists(Request $request, Response $response, callable $next) {
        $user_id = isset($request->getAttribute('routeInfo')[2]['id']) ? $request->getAttribute('routeInfo')[2]['id'] : $request->getAttribute('routeInfo')[2]['user_id'];

        try {
            $user = User::query()->where('id', '=', $user_id)->firstOrFail();
            $request = $request->withAttribute('user', $user);

            return $next($request, $response);
        } catch (ModelNotFoundException $exception) {
            return JSON::errorResponse($response, 404, "User with ID ".$user_id." doesn't exist.");
        }
    }

    public function messageExists(Request $request, Response $response, callable $next) {
        $message_id = isset($request->getAttribute('routeInfo')[2]['id']) ? $request->getAttribute('routeInfo')[2]['id'] : $request->getAttribute('routeInfo')[2]['message_id'];

        try {
            $message = Message::query()->where('id', '=', $message_id)->firstOrFail();
            $request = $request->withAttribute('message', $message);

            return $next($request, $response);
        } catch (ModelNotFoundException $exception) {
            return JSON::errorResponse($response, 404, "Message with ID ".$message_id." doesn't exist.");
        }
    }

    public function serverExists(Request $request, Response $response, callable $next) {
        $server_id = isset($request->getAttribute('routeInfo')[2]['id']) ? $request->getAttribute('routeInfo')[2]['id'] : $request->getAttribute('routeInfo')[2]['server_id'];

        try {
            $server = Server::query()
                ->where('id', '=', $server_id)
                ->with('textChannels')
                ->firstOrFail();
            $request = $request->withAttribute('server', $server);

            return $next($request, $response);
        } catch (ModelNotFoundException $exception) {
            return JSON::errorResponse($response, 404, "Server with ID ".$server_id." doesn't exist.");
        }
    }

    public function textChannelExists(Request $request, Response $response, callable $next) {
        $text_channel_id = isset($request->getAttribute('routeInfo')[2]['id']) ? $request->getAttribute('routeInfo')[2]['id'] : $request->getAttribute('routeInfo')[2]['text_channel_id'];

        try {
            $text_channel = TextChannel::query()
                ->where('id', '=', $text_channel_id)
                ->with('server')
                ->firstOrFail();
            $request = $request->withAttribute('text_channel', $text_channel);

            return $next($request, $response);
        } catch (ModelNotFoundException $exception) {
            return JSON::errorResponse($response, 404, "Text Channel with ID ".$text_channel_id." doesn't exist.");
        }
    }

    public function imageExists(Request $request, Response $response, callable $next) {
        $image_id = isset($request->getAttribute('routeInfo')[2]['id']) ? $request->getAttribute('routeInfo')[2]['id'] : $request->getAttribute('routeInfo')[2]['image_id'];

        $match = glob($this->container->settings['upload_dir'].'/'.$image_id);
        if (empty($match)) {
            return JSON::errorResponse($response, 404, "Image with ID ".$image_id." doesn't exist.");
        }

        $image = file_get_contents($match[0]);
        $type = mime_content_type($match[0]);

        $request = $request->withAttribute('image', $image);
        $request = $request->withAttribute('type', $type);

        return $next($request, $response);
    }

    public function isBase64Image(Request $request, Response $response, callable $next) {
        $body = $request->getParsedBody();

        if (!isset($body['image'])) {
            return JSON::errorResponse($response, 400, "Incorrect format in parameters. Details: You must provide an image parameter.");
        }

        $decoded = base64_decode($body['image'], true);
        $encoded = base64_encode($decoded);

        if (!preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $body['image']) || $decoded === false || $encoded !== $body['image']) {
            return JSON::errorResponse($response, 400, "Incorrect format in parameters. Details: You must provide a base64 encoded image.");
        }

        $upload_dir = $this->container->settings['upload_dir'];
        $filename = Uuid::uuid4();
        $fullpath = "$upload_dir/$filename";

        file_put_contents($fullpath, $decoded);

        if (substr(mime_content_type($fullpath), 0, 5) !== 'image') {
            unlink($fullpath);
            return JSON::errorResponse($response, 415, "You must upload an image.");
        }

        if (filesize($fullpath) > 8 * 1024 * 1024) {
            unlink($fullpath);
            return JSON::errorResponse($response, 413, "Image is too large, you can upload Images up to 8MB.");
        }

        $request = $request->withAttribute('image_name', $filename);

        return $next($request, $response);
    }

    public function withAuthors(Request $request, Response $response, callable $next) {
        if (!isset($request->getQueryParams()['authors'])) {
            $request = $request->withAttribute('with_authors', false);
            return $next($request, $response);
        }

        $with_image = $request->getQueryParams()['authors'];
        $true_options = [true, 'true', 'on', 'yes', "1", 1];
        $false_options = [false, 'false', 'off', 'no', "0", 0];

        if (in_array($with_image, $true_options, true)) {
            $request = $request->withAttribute('with_authors', true);
        } else if (in_array($with_image, $false_options, true)) {
            $request = $request->withAttribute('with_authors', false);
        }

        return $next($request, $response);
    }
}