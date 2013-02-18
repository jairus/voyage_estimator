<?php
@ob_start();
@session_start();

include_once(dirname(__FILE__)."/globals.php");
include_once(dirname(__FILE__)."/functions.php");
include_once(dirname(__FILE__)."/tabsystem.php");

include_once(dirname(__FILE__)."/database.php");
include_once(dirname(__FILE__)."/distanceCalc.class.php");
include_once(dirname(__FILE__)."/account.class.php");
include_once(dirname(__FILE__)."/../misc/emailer/email.php");

date_default_timezone_set('UTC'); 
?>