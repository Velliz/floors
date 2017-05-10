<?php
ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/session'));
ini_set('session.gc_probability', 1);

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

    'application/create' => 'manage/applications/create',
    'application/edit' => 'manage/applications/edit',
    'application/delete' => 'manage/applications/delete',
    'application/detail' => 'manage/applications/detail',
    'applications' => 'manage/applications/main',

    'broker/create' => 'manage/broker/create',
    'broker/edit' => 'manage/broker/edit',
    'broker/delete' => 'manage/broker/delete',

    'permissions/create' => 'manage/permissions/create',
    'permissions/edit' => 'manage/permissions/edit',
    'permissions/delete' => 'manage/permissions/delete',

    'user/create' => 'manage/users/create',
    'user/edit' => 'manage/users/edit',
    'user/delete' => 'manage/users/delete',
    'user/detail' => 'manage/users/detail',
    'users' => 'manage/users/main',

    'authorization/create' => 'manage/authorization/create',
    'authorization/delete' => 'manage/authorization/delete',

    'operators' => 'manage/operator/main',
    'settings' => 'manage/settings/main',

    'account/authorization' => 'account/authorization',
    'account/history' => 'account/history',
    'account' => 'account/profile',

    'register' => 'main/register',
    'recovery' => 'main/recovery'

));
$framework->Start();