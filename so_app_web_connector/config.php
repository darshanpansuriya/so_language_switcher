<?php

 
// We need to make sure the correct timezone is set, or some PHP installations will complain
if (function_exists('date_default_timezone_set'))
{
	// * MAKE SURE YOU SET THIS TO THE CORRECT TIMEZONE! *
	// List of valid timezones is here: http://us3.php.net/manual/en/timezones.php
	date_default_timezone_set('America/New_York');
}

// I always program in E_STRICT error mode... 
error_reporting(E_ALL | E_STRICT);

// Require the framework
require_once dirname(__FILE__) . '/../qb_library/QuickBooks.php';

// Your .QWC file username/password
$qbwc_user = 'QuickBooks';
$qbwc_pass = 'password';

// * MAKE SURE YOU CHANGE THE DATABASE CONNECTION STRING BELOW TO A VALID MYSQL USERNAME/PASSWORD/HOSTNAME *
$dsn = 'mysql://dbo562806560:9bpSTW-_O@d!@localhost:/tmp/mysql5.sock/db562806560';
if (!QuickBooks_Utilities::initialized($dsn))
{
	// Initialize creates the neccessary database schema for queueing up requests and logging
	QuickBooks_Utilities::initialize($dsn);
	
	// This creates a username and password which is used by the Web Connector to authenticate
	QuickBooks_Utilities::createUser($dsn, $qbwc_user, $qbwc_pass);
	
	// Create our test table
	mysql_query("CREATE TABLE my_customer_table (
	  id int(10) unsigned NOT NULL AUTO_INCREMENT,
	  name varchar(64) NOT NULL,
	  fname varchar(64) NOT NULL,
	  lname varchar(64) NOT NULL,
	  quickbooks_listid varchar(255) DEFAULT NULL,
	  quickbooks_editsequence varchar(255) DEFAULT NULL,
	  quickbooks_errnum varchar(255) DEFAULT NULL,
	  quickbooks_errmsg varchar(255) DEFAULT NULL,
	  PRIMARY KEY (id)
	) ENGINE=MyISAM");
}
