<?php
require_once 'library/Config.php';
require_once 'library/ApiCall.php';

// Basispfad ermitteln
$path = explode(DIRECTORY_SEPARATOR, __FILE__);
// Eine Ebene entfernen, damit faellt /public weg
$path = array_slice($path, 0, count($path) - 2);
$frapiPath = array_slice($path, 0, count($path) - 1);

$path = implode(DIRECTORY_SEPARATOR, $path);

define('BASEPATH', $path);
define('CUSTOM_MODEL', $path . DIRECTORY_SEPARATOR . '/Model');

// Frapi dependencies
$frapiPath = implode(DIRECTORY_SEPARATOR, $frapiPath) . '/library/Frapi';
$_SERVER['HTTP_HOST'] = 'api.score11.de';
require_once $frapiPath . '/AllFiles.php';