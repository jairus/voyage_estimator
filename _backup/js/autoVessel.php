<?php
include_once(dirname(__FILE__)."/../includes/database.php");
$link = dbConnect();
$sql = "select * from `_xvas_parsed2_dry` where 1";
$vessel = dbQuery($sql, $link);


?>
var vessel = [ <?php
$t = count($vessel);
for($i=0; $i<$t; $i++){
	echo "\"".strtoupper($vessel[$i]['name'])." - ".strtoupper($vessel[$i]['imo'])."\"";
	if($i%100==0&&$i!=0){
		echo "\n";
	}
	if(($i+1)<$t){
		echo ",";
	}
}
?>];