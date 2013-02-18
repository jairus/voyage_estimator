<?php
@session_start();
include_once(dirname(__FILE__)."/includes/bootstrap.php");
setcookie ("cookie_email", "", time() - 60*60*24*100);
setcookie ("cookie_password", "", time() - 60*60*24*100);
unset($_SESSION['user']);
redirectjs("/login.php");
?>