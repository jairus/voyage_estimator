<?php
include_once(dirname(__FILE__)."/../includes/database.php");
$link = dbConnect();
$sql = "select * from `_port_agents` where 1";
$agent = dbQuery($sql, $link);


?>
var agent = [ <?php
$t = count($agent);
for($i=0; $i<$t; $i++){
	echo "\"".trim($agent[$i]['email_address'])."\"";
	if($i%100==0&&$i!=0){
		echo "\n";
	}
	if(($i+1)<$t){
		echo ",";
	}
}
?>];