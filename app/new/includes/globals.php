<?php
@session_start();

$user = array();
$user = $_SESSION['user']; 

include_once(dirname(__FILE__)."/Snoopy.class.php");
$snoopy = new Snoopy();
?>