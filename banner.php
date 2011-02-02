<?php

error_reporting(E_ALL);

$_PATH['classes'] = 'classes/';
include_once($_PATH['classes'].'CrawlerLastFM.class.php');
include_once($_PATH['classes'].'OneColumnLayout.class.php');
include_once($_PATH['classes'].'TwoColumnsLayout.class.php');
include_once($_PATH['classes'].'DatabaseCache.class.php');
include_once($_PATH['classes'].'Errors.class.php');
include_once($_PATH['classes'].'Config.class.php');

header("Content-type: image/jpeg");
header("Author: h3xStream");

//Errors handler
ob_start();
set_error_handler(array('Errors','catchError'));
set_exception_handler(array('Errors','catchException'));

//--Initialisation
//Inputs
$user = isset($_GET['user'])?$_GET['user']:'';
$nb = isset($_GET['nb'])?$_GET['nb']:10; //Default 10 artists
$type = isset($_GET['type'])?$_GET['type']:'overall';
$color = isset($_GET['color'])?$_GET['color']:'white';
$layout = isset($_GET['layout'])?$_GET['layout']:'OneCol';

$cacheDelay = Config::CACHE_DELAY; //Period to keep in cache

////Validations
//Checking for invalid number
if(!($nb == 5 || $nb == 10 || $nb == 15 || $nb == 20 || $nb == 25))
	$nb = Config::DEFAULT_BANNER_NB;

//Checking for invalid type
if(!($type == '3month' || $type == '6month' ||	$type == '12month' ||
	$type == 'overall'))
	$type = Config::DEFAULT_BANNER_TYPE;

//Colors..
if(!($color == 'white' || $color == 'black' || $color == 'gray' || 
	$color == 'blue' || $color == 'red' || $color == 'orange' || 
	$color == 'turquoise' || $color == 'trans'))
	$color = Config::DEFAULT_BANNER_COLOR;
	
//Layouts
if(!($layout == 'OneCol' || $layout == 'TwoCols'))
	$layout = Config::DEFAULT_BANNER_LAYOUT;


////Cache initialisation
$cacheHandler = new DatabaseCache();

$cacheHandler->setUser($user);
$cacheHandler->setNbArtists($nb);
$cacheHandler->setPeriodType($type);
$cacheHandler->setColor($color);

////Layout selection
$layout = null;

switch ($layout) {
	case 'TwoCols':
		$layout = new TwoColumnsLayout();
		break;
	default:
	case 'OneCol':
		$layout = new OneColumnLayout();
		break;
}

////Output process
//header("Expires: ".(gmdate("D, d M Y H:i:s", time() + $cacheDelay))." GMT");
$cacheHandler->setLayout($layout);
$cacheHandler->generate();

//ob_start("ob_gzhandler"); //Optionnal
$cacheHandler->outputResult();

Config::$dbInstance->disconnect();

?>