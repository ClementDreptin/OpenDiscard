<?php
require '../src/vendor/autoload.php';

use OpenDiscard\api\common\database\DatabaseConnection;
use OpenDiscard\api\common\middleware\BasicAuth;
use OpenDiscard\api\common\middleware\Checker;
use OpenDiscard\api\common\middleware\CORS;
use OpenDiscard\api\common\middleware\JWT;
use OpenDiscard\api\common\middleware\Validator;
use OpenDiscard\api\control\DocsController;
use OpenDiscard\api\control\ServerController;
use OpenDiscard\api\control\UserController;

$settings = require_once "../src/config/settings.php";
$errorsHandlers = require_once "../src/common/error/errorHandlers.php";
$app_config = array_merge($settings, $errorsHandlers);

$container = new \Slim\Container($app_config);
$app = new \Slim\App($container);

DatabaseConnection::startEloquent(($app->getContainer())->settings['dbconf']);

$app->post('/users/signup[/]', UserController::class.':signUp')
    ->add(Validator::class.':dataFormatErrorHandler')
    ->add(Validator::createUserValidator())
    ->add(CORS::class.':addCORSHeaders');

$app->post('/users/signin[/]', UserController::class.':signIn')
    ->add(BasicAuth::class.':decodeBasicAuth')
    ->add(CORS::class.':addCORSHeaders');

$app->patch('/users/{id}[/]', UserController::class.':update')
    ->add(JWT::class.':checkJWT')
    ->add(Checker::class.':userExists')
    ->add(Validator::class.':dataFormatErrorHandler')
    ->add(Validator::updateUserValidator())
    ->add(CORS::class.':addCORSHeaders');

$app->post('/servers[/]', ServerController::class.':create')
    ->add(JWT::class.':checkJWT')
    ->add(Validator::class.':dataFormatErrorHandler')
    ->add(Validator::createServerValidator())
    ->add(CORS::class.':addCORSHeaders');

$app->patch('/servers/{id}[/]', ServerController::class.':update')
    ->add(JWT::class.':checkJWT')
    ->add(Checker::class.':serverExists')
    ->add(Validator::class.':dataFormatErrorHandler')
    ->add(Validator::updateServerValidator())
    ->add(CORS::class.':addCORSHeaders');

$app->delete('/servers/{id}[/]', ServerController::class.':delete')
    ->add(JWT::class.':checkJWT')
    ->add(Checker::class.':serverExists')
    ->add(CORS::class.':addCORSHeaders');

$app->put('/servers/{server_id}/users/{user_id}', ServerController::class.':addUser')
    ->add(JWT::class.':checkJWT')
    ->add(Checker::class.':userExists')
    ->add(Checker::class.':serverExists')
    ->add(CORS::class.':addCORSHeaders');

$app->delete('/servers/{server_id}/users/{user_id}', ServerController::class.':kickUser')
    ->add(JWT::class.':checkJWT')
    ->add(Checker::class.':userExists')
    ->add(Checker::class.':serverExists')
    ->add(CORS::class.':addCORSHeaders');

$app->options('/{routes:.+}', function ($request, $response, $args) { return $response; })
    ->add(CORS::class.':addCORSHeaders');

$app->get('/docs[/]', DocsController::class.':renderDocsHtmlFile')
    ->add(CORS::class.':addCORSHeaders');

$app->get('/', DocsController::class.':redirectTowardsDocs')
    ->add(CORS::class.':addCORSHeaders');

$app->run();