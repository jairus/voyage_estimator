<?php
include_once(dirname(__FILE__)."/../includes/database.php");
$link = dbConnect();
$sql = "select * from `_port_agents` where 1 ORDER BY `id`";
$agent = dbQuery($sql, $link);


?>
var agent = [ <?php
$t = count($agent);
for($i=0; $i<$t; $i++){
	echo "\"".trim($agent[$i]['first_name'])." ".trim($agent[$i]['last_name'])." - ".trim($agent[$i]['id'])."\"";
	if($i%100==0&&$i!=0){
		echo "\n";
	}
	if(($i+1)<$t){
		echo ",";
	}
}
?>];