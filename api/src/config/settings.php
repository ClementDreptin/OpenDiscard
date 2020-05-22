<?php
return [
    'settings' => [
        'displayErrorDetails' => true,
        'dbconf' => '../src/config/config.ini',
        'JWT_secret' => parse_ini_file('../src/config/config.ini')['jwtsecret'],
        'determineRouteBeforeAppMiddleware' => true
    ]
];
