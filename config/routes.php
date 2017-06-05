<?php
$data['page'] = array(

    'select' => array(
        'controller' => 'select',
        'function' => 'select',
        'accept' => ['GET'],
    ),

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

    'application/edit/{!}' => array(
        'controller' => 'manage\applications',
        'function' => 'edit',
        'accept' => ['GET', 'POST'],
    ),

    'application/delete' => array(
        'controller' => 'manage\applications',
        'function' => 'delete',
        'accept' => ['GET', 'POST'],
    ),

    'application/detail/{!}' => array(
        'controller' => 'manage\applications',
        'function' => 'detail',
        'accept' => ['GET', 'POST'],
    ),

    'applications' => array(
        'controller' => 'manage\applications',
        'function' => 'main',
        'accept' => ['GET', 'POST'],
    ),

    'broker/create/{!}' => array(
        'controller' => 'manage\broker',
        'function' => 'create',
        'accept' => ['GET', 'POST'],
    ),

    'broker/edit/{!}' => array(
        'controller' => 'manage\broker',
        'function' => 'edit',
        'accept' => ['GET', 'POST'],
    ),

    'broker/delete' => array(
        'controller' => 'manage\broker',
        'function' => 'delete',
        'accept' => ['GET', 'POST'],
    ),

    'permissions/create/{!}' => array(
        'controller' => 'manage\permissions',
        'function' => 'create',
        'accept' => ['GET', 'POST'],
    ),

    'permissions/edit' => array(
        'controller' => 'manage\permissions',
        'function' => 'edit',
        'accept' => ['GET', 'POST'],
    ),

    'permissions/delete' => array(
        'controller' => 'manage\permissions',
        'function' => 'delete',
        'accept' => ['GET', 'POST'],
    ),

    'user/create' => array(
        'controller' => 'manage\users',
        'function' => 'create',
        'accept' => ['GET', 'POST'],
    ),

    'user/edit/{!}' => array(
        'controller' => 'manage\users',
        'function' => 'edit',
        'accept' => ['GET', 'POST'],
    ),

    'user/delete/{!}' => array(
        'controller' => 'manage\users',
        'function' => 'delete',
        'accept' => ['GET', 'POST'],
    ),

    'user/detail/{!}' => array(
        'controller' => 'manage\users',
        'function' => 'detail',
        'accept' => ['GET', 'POST'],
    ),
    'users' => array(
        'controller' => 'manage\users',
        'function' => 'main',
        'accept' => ['GET', 'POST'],
    ),

    'authorization/create' => array(
        'controller' => 'manage\authorization',
        'function' => 'create',
        'accept' => ['GET', 'POST'],
    ),

    'authorization/delete' => array(
        'controller' => 'manage\authorization',
        'function' => 'delete',
        'accept' => ['GET', 'POST'],
    ),

    'operators' => array(
        'controller' => 'manage\operator',
        'function' => 'main',
        'accept' => ['GET', 'POST'],
    ),

    'settings' => array(
        'controller' => 'manage\settings',
        'function' => 'main',
        'accept' => ['GET', 'POST'],
    ),

    'account/authorization' => array(
        'controller' => 'account',
        'function' => 'authorization',
        'accept' => ['GET', 'POST'],
    ),

    'account/history' => array(
        'controller' => 'account',
        'function' => 'history',
        'accept' => ['GET', 'POST'],
    ),
    'account' => array(
        'controller' => 'account',
        'function' => 'profile',
        'accept' => ['GET', 'POST'],
    ),

    'register' => array(
        'controller' => 'floors\main',
        'function' => 'register',
        'accept' => ['GET', 'POST'],
    ),

    'recovery' => array(
        'controller' => 'floors\main',
        'function' => 'recovery',
        'accept' => ['GET', 'POST'],
    ),

    'operator/detail/{!}' => array(
        'controller' => 'manage\operator',
        'function' => 'detail',
        'accept' => ['GET', 'POST'],
    ),

    'operator/addnew' => array(
        'controller' => 'manage\operator',
        'function' => 'addnew',
        'accept' => ['GET', 'POST'],
    ),

    'api/permission' => array(
        'controller' => 'api',
        'function' => 'permission',
        'accept' => ['GET', 'POST'],
    ),

    'api/authorization/{!}' => array(
        'controller' => 'api',
        'function' => 'authorization',
        'accept' => ['GET', 'POST'],
    ),

    'api/avatar/{!}/{!}' => array(
        'controller' => 'api',
        'function' => 'avatar',
        'accept' => ['GET', 'POST'],
    ),
    'api/uploadavatar/{!}' => array(
        'controller' => 'api',
        'function' => 'uploadavatar',
        'accept' => ['GET', 'POST'],
    ),

    'api/changeavatar/{!}/{!}' => array(
        'controller' => 'api',
        'function' => 'changeavatar',
        'accept' => ['GET', 'POST'],
    ),

    'api/credentialpic/{!}/{!}' => array(
        'controller' => 'api',
        'function' => 'credentialpic',
        'accept' => ['GET', 'POST'],
    ),

    'api/user' => array(
        'controller' => 'api',
        'function' => 'user',
        'accept' => ['GET', 'POST'],
    ),

    'resume' => array(
        'controller' => 'resume',
        'function' => 'main',
        'accept' => ['GET', 'POST'],
    ),
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