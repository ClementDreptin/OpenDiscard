<?php
require '../src/vendor/autoload.php';

use OpenDiscard\api\common\database\DatabaseConnection;

$settings = require_once "../src/config/settings.php";
$errorsHandlers = require_once "../src/common/error/errorHandlers.php";
$app_config = array_merge($settings, $errorsHandlers);

$container = new \Slim\Container($app_config);
$app = new \Slim\App($container);

DatabaseConnection::startEloquent(($app->getContainer())->settings['dbconf']);

$app->run();