<?php
include_once(dirname(__FILE__)."/../includes/database.php");
$link = dbConnect();
$sql = "select * from `_veson_ports` where 1";
$veson_ports = dbQuery($sql, $link);


?>
var veson_ports = [ <?php
$t = count($veson_ports);
for($i=0; $i<$t; $i++){
	echo "\"".strtoupper($veson_ports[$i]['name'])."\"";
	if($i%100==0&&$i!=0){
		echo "\n";
	}
	if(($i+1)<$t){
		echo ",";
	}
}
?>];