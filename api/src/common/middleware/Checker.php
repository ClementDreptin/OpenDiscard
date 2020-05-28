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

class Checker {
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
            $server = Server::query()->where('id', '=', $server_id)->firstOrFail();
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
}