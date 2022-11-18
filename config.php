<?php
$server = strtolower($_SERVER['SERVER_NAME']);
//echo $server;
//phpinfo();
$dbpref = "";
function p($x="",$y="",$z=""){ // p(var, line, file)
	echo "<div style='overflow:auto; background: red; color: #FFFFFF; text-align:left;'><pre>";
	echo "<b>Line: $y File: W:".stristr($z,"/nefloral.com/")."</b><br>";
	if ($x == "") { echo "<b>POST</b><br />"; print_r($_POST); }
	else print_r($x);
	echo "</pre></div>";
}
	
if (str_replace(array("localhost","192.168","webhop","127.0.0.1"),"",$server) != $server) {

	// LOCAL
	include $_SERVER['DOCUMENT_ROOT']."/fpm_intranet/general/config.php";
	
	$dbdriver = "mysql";
	$host = "localhost";
	$user = $webhop_user;
	$pass = $webhop_pass;
	$db = "so_ocart_alt";

	$folder ='/var/www/html/soundorthotics.com';
	$website = 'https://soundorthotics.com';
	$websiteS= 'https://soundorthotics.com';
} else {
	
	// LIVE
	$dbdriver = "mysql";
	$host = "localhost";
	$user = "soundort_app";
	$pass = "C$~_O^T-6z+~";
	$db = "soundort_app";

	$folder ='/home/soundorthotics/clients.soundorthotics.com';
	$website = 'https://clients.soundorthotics.com';
	$websiteS = 'https://clients.soundorthotics.com';
}

// $website = str_replace("/admin","",$_SERVER['SERVER_NAME']. dirname($_SERVER['PHP_SELF']));
// $folder = str_replace("/admin","",dirname($_SERVER["SCRIPT_FILENAME"]));


// HTTP
define('HTTP_SERVER', $website . '/');
define('HTTP_CATALOG', $website . '/');
define('HTTP_IMAGE', $website . '/images/');

// HTTPS
define('HTTPS_SERVER', $websiteS . '/');
define('HTTPS_IMAGE', $websiteS . '/images/');

// DIR
define('DIR_APPLICATION', $folder . '/');
define('DIR_SYSTEM', $folder . '/system/');
define('DIR_DATABASE', $folder . '/system/database/');
define('DIR_LANGUAGE', $folder . '/language/');
define('DIR_TEMPLATE', $folder . '/view/template/');
define('DIR_CONFIG', $folder . '/system/config/');
define('DIR_IMAGE', $folder . '/images/');
define('DIR_CACHE', $folder . '/system/cache/');
define('DIR_DOWNLOAD', $folder . '/download/');
define('DIR_LOGS', $folder . '/system/logs/');
// define('DIR_CATALOG', $folder . '/catalog/');

// DB
define('DB_DRIVER', $dbdriver);
define('DB_HOSTNAME', $host);
define('DB_USERNAME', $user);
define('DB_PASSWORD', $pass);
define('DB_DATABASE', $db);
define('DB_PREFIX', $dbpref);

//EOF
