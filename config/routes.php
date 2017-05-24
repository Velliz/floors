<?php
$data['page'] = array(

    'exchange' => array(
        'controller' => 'service',
        'function' => 'exchange',
        'accept' => ['GET', 'POST'],
    ),

    'floors/callbacks' => array(
        'controller' => 'floors\main',
        'function' => 'callbacks',
        'accept' => ['GET', 'POST', 'PUT', 'PATCH'],
    ),

    'facebook/callbacks' => array(
        'controller' => 'facebook\main',
        'function' => 'callbacks',
        'accept' => ['GET', 'POST', 'PUT', 'PATCH'],
    ),

    'google/callbacks' => array(
        'controller' => 'google\main',
        'function' => 'callbacks',
        'accept' => ['GET', 'POST', 'PUT', 'PATCH'],
    ),

    'twitter/callbacks' => array(
        'controller' => 'twitter\main',
        'function' => 'callbacks',
        'accept' => ['GET', 'POST', 'PUT', 'PATCH'],
    ),

    'profile' => array(
        'controller' => 'main',
        'function' => 'profile',
        'accept' => ['GET', 'POST'],
    ),

    'beranda' => array(
        'controller' => 'admin',
        'function' => 'beranda',
        'accept' => ['GET', 'POST'],
    ),

    'admin/logout' => array(
        'controller' => 'admin',
        'function' => 'userlogout',
        'accept' => ['GET', 'POST'],
    ),

    'account/logout' => array(
        'controller' => 'account',
        'function' => 'userlogout',
        'accept' => ['GET', 'POST'],
    ),

    'tos' => array(
        'controller' => 'main',
        'function' => 'tos',
        'accept' => ['GET'],
    ),

    'policy' => array(
        'controller' => 'main',
        'function' => 'policy',
        'accept' => ['GET'],
    ),

    '' => array(
        'controller' => 'main',
        'function' => 'main',
        'accept' => ['GET'],
    ),

    'application/create' => array(
        'controller' => 'manage\applications',
        'function' => 'create',
        'accept' => ['GET', 'POST'],
    ),

    'application/edit' => array(
        'controller' => 'manage\applications',
        'function' => 'edit',
        'accept' => ['GET', 'POST'],
    ),

    'application/delete' => array(
        'controller' => 'manage\applications',
        'function' => 'delete',
        'accept' => ['GET', 'POST'],
    ),

    'application/detail' => array(
        'controller' => 'manage\applications',
        'function' => 'detail',
        'accept' => ['GET', 'POST'],
    ),

    'applications' => array(
        'controller' => 'manage\applications',
        'function' => 'main',
        'accept' => ['GET', 'POST'],
    ),

    'broker/create' => array(
        'controller' => 'manage\broker',
        'function' => 'create',
        'accept' => ['GET', 'POST'],
    ),

    'broker/edit' => array(
        'controller' => 'manage\broker',
        'function' => 'edit',
        'accept' => ['GET', 'POST'],
    ),

    'broker/delete' => array(
        'controller' => 'manage\broker',
        'function' => 'delete',
        'accept' => ['GET', 'POST'],
    ),

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

);

$data['error'] = array(
    'controller' => 'main',
    'function' => 'error',
    'accept' => ['GET']
);

$data['not_found'] = array(
    'controller' => 'main',
    'function' => 'not_found',
    'accept' => ['GET']
);

return $data;