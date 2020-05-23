<?php
require '../src/vendor/autoload.php';

use OpenDiscard\api\common\database\DatabaseConnection;
use OpenDiscard\api\common\middleware\CORS;
use OpenDiscard\api\common\middleware\Validator;
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

$app->options('/{routes:.+}', function ($request, $response, $args) { return $response; })
    ->add(CORS::class.':addCORSHeaders');

$app->run();