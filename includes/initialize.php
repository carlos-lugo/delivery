<?php

// show php errors for debugging
// ini_set("display_startup_errors", "1");
// ini_set("display_errors", "1");
// error_reporting(E_ALL);

date_default_timezone_set("Europe/Madrid");

// Define the core paths
// Define them as absolute paths to make sure that require_once works as expected

// DIRECTORY_SEPARATOR is a PHP pre-defined constant
// (\ for Windows, / for Unix)
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);

defined('SITE_ROOT') ? null : define('SITE_ROOT', 'C:'.DS.'Users'.DS.'Asus'.DS.'Dropbox'.DS.'projects'.DS.'motos'.DS.'motos_cclugo');
//defined('SITE_ROOT') ? null : define('SITE_ROOT', DS.'home'.DS.'u429327371'.DS.'public_html'.DS.'delivery');

defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT.DS.'includes');

// load config file first
require_once(LIB_PATH.DS.'config.php');

// load basic functions next so that everything after can use them
require_once(LIB_PATH.DS.'functions.php');

// load core objects
require_once(LIB_PATH.DS.'database.php');
require_once(LIB_PATH.DS.'databaseobject.php');
require_once(LIB_PATH.DS.'session.php');
require_once(LIB_PATH.DS.'pagination.php');
//require_once(LIB_PATH.DS."phpMailer".DS."class.phpmailer.php");
//require_once(LIB_PATH.DS."phpMailer".DS."class.smtp.php");
//require_once(LIB_PATH.DS."phpMailer".DS."language".DS."phpmailer.lang-en.php");

// load database-related classes
require_once(LIB_PATH.DS.'user.php');
//require_once(LIB_PATH.DS.'photograph.php');
//require_once(LIB_PATH.DS.'comment.php');
require_once(LIB_PATH.DS.'fattax.php');

?>