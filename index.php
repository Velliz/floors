<?php
define('ROOT', __DIR__);
define('BASE_URL', $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['SERVER_NAME'] . '/floors/');

require __DIR__ . '/vendor/autoload.php';

$framework = new \pukoframework\Framework();
$framework->RouteMapping(array(
    'floors/callbacks' => 'floors/main/callbacks',
    'facebook/callbacks' => 'facebook/main/callbacks',
    'google/callbacks' => 'google/main/callbacks',
    'twitter/callbacks' => 'twitter/main/callbacks',

    'profile' => 'main/profile',
    'beranda' => 'main/beranda',
    'logout' => 'main/userlogout',
    'tos' => 'main/tos',
    'policy' => 'main/policy',

    'application/create' => 'app/applications/create',
    'application/edit' => 'app/applications/edit',
    'application/delete' => 'app/applications/delete',
    'applications' => 'app/applications/main',
    'application' => 'app/applications/detail',

    'application/broker' => 'app/broker/main',
    'application/broker/create' => 'app/broker/create',

));
$framework->Start();