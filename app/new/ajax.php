<?php
include_once(dirname(__FILE__)."/includes/bootstrap.php");

global $user;

if($_GET['new_search']==1){
	$sql = "INSERT INTO `_user_tabs` (`uid`, `page`, `tabname`, `tabdata`, `dateadded`) VALUES('".$user['uid']."', 'shipsearch', 'New Tab', 'a:0:{}', NOW())";
	dbQuery($sql, $link);
}
?>