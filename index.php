<?php
define('ROOT', __DIR__);
define('BASE_URL', $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['SERVER_NAME'] . '/floors/');

require __DIR__ . '/vendor/autoload.php';

$framework = new \pukoframework\Framework();
$framework->RouteMapping(array(

    'exchange' => 'service/exchange',

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
    'application/detail' => 'app/applications/detail',
    'applications' => 'app/applications/main',

    'broker/create' => 'app/broker/create',
    'broker/edit' => 'app/broker/edit',
    'broker/delete' => 'app/broker/delete',

    'permissions/create' => 'app/permissions/create',
    'permissions/edit' => 'app/permissions/edit',
    'permissions/delete' => 'app/permissions/delete',

    'user/create' => 'app/users/create',
    'user/edit' => 'app/users/edit',
    'user/delete' => 'app/users/delete',
    'user/detail' => 'app/users/detail',
    'users' => 'app/users/main',

    'operators' => 'app/operator/main',
    'settings' => 'app/settings/main',

    'account/profile' => 'account/profile',
    'account' => 'account/main',

));
$framework->Start();