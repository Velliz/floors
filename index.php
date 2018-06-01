<?php

ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/session'));
ini_set('session.gc_probability', 1);

define('ROOT', __DIR__);

$protocol = 'http';
if (isset($_SERVER['HTTPS'])) {
    $protocol = 'https';
} else if (isset($_SERVER['HTTP_X_SCHEME'])) {
    $protocol = strtolower($_SERVER['HTTP_X_SCHEME']);
} else if (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
    $protocol = strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']);
} else if (isset($_SERVER['SERVER_PORT'])) {
    $serverPort = (int)$_SERVER['SERVER_PORT'];
    if ($serverPort == 80) {
        $protocol = 'http';
    } else if ($serverPort == 443) {
        $protocol = 'https';
    }
}

define('BASE_URL', ($protocol . "://" . $_SERVER['HTTP_HOST'] . "/"));

define('START', microtime(true));

require __DIR__ . '/vendor/autoload.php';

$framework = new \pukoframework\Framework();
$framework->Start();