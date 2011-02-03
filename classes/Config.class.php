<?php
//include_once("db/MySQLIDB.class.php");
include_once("db/MySQLNative.class.php");

/**
 * All configuration parameters are set here.
 */
class Config {
	//DB connection
	public static $dbInstance = null; //Initialise ASAP
	
	//Image
	const IMAGE_QUALITY = 85;
	const FOLDER_LOGOS = "logos/";
	
	//Crawler (audioscobber)
	const REQUEST_METHOD = "fopen"; //(cUrl,fopen,file_get_contents)
	const API_KEY = "db109383394640f2b5e174b4514f0014"; //Api key
	
	//Default parameters
	const DEFAULT_BANNER_NB = 10;
	const DEFAULT_BANNER_TYPE = "overall";
	const DEFAULT_BANNER_COLOR = "white";
	const DEFAULT_BANNER_LAYOUT = "OneCol";
	const NB_GENERATION_ALLOW = 1337;
	
	//Delay for browser cache (in sec.)
	const CACHE_DELAY = 432000; //(3600 * 24 * 5)
	
	//Banner Location (use by the link.php page)
	const LINK_BANNER = "http://localhost/bandlogos/banner.php";
}

//Config::$dbInstance = MySQLIDB::getInstance();
Config::$dbInstance = MySQLNative::getInstance();

?>