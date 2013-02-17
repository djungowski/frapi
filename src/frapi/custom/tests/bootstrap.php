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

// Frapi dependencies
$frapiPath = implode(DIRECTORY_SEPARATOR, $frapiPath) . '/library/Frapi';
$_SERVER['HTTP_HOST'] = 'api.score11.de';
require_once $frapiPath . '/AllFiles.php';

// Nyan Cat phpunit Printer
require_once 'vendor/whatthejeff/fab/src/Fab/Factory.php';
require_once 'vendor/whatthejeff/fab/src/Fab/Fab.php';
require_once 'vendor/whatthejeff/fab/src/Fab/SuperFab.php';