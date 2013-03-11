<?php
include_once(dirname(__FILE__)."/../includes/database.php");
$link = dbConnect();
$sql = "select * from `_veson_ports` where 1";
$ports = dbQuery($sql, $link);


?>
var ports = [ <?php
$t = count($ports);
for($i=0; $i<$t; $i++){
	echo "\"".$ports[$i]['name']."\"";
	if($i%100==0&&$i!=0){
		echo "\n";
	}
	if(($i+1)<$t){
		echo ",";
	}
}
?>];