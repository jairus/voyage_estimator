<?php
@ob_start();
@session_start();

include_once(dirname(__FILE__)."/globals.php");
include_once(dirname(__FILE__)."/functions.php");
include_once(dirname(__FILE__)."/database.php");
include_once(dirname(__FILE__)."/distanceCalc.class.php");

date_default_timezone_set('UTC');
?>