<?php

ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/session'));
ini_set('session.gc_probability', 1);

define('ROOT', __DIR__);
define('BASE_URL', $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['SERVER_NAME'] . ':1414/floors/');
define('START', microtime(true));

require __DIR__ . '/vendor/autoload.php';

$framework = new \pukoframework\Framework();
$framework->Start();