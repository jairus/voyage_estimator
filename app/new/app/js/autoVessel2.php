<?php
include_once(dirname(__FILE__)."/../includes/bootstrap.php");
$link = dbConnect();

if($user['dry']==1){
	$sql = "select `imo`, `name`, `summer_dwt` from `_xvas_parsed2_dry` where 1";
	$vessel = dbQuery($sql, $link);
}elseif($user['dry']==0){
	$sql = "select `imo`, `name`, `summer_dwt` from `_xvas_parsed2` where 1";
	$vessel = dbQuery($sql, $link);
}
?>
var vessel = [ <?php
$t = count($vessel);
for($i=0; $i<$t; $i++){
	echo "\"".strtoupper($vessel[$i]['name'])." - ".$vessel[$i]['imo']." - ".number_format($vessel[$i]['summer_dwt'])."\"";
	if($i%100==0&&$i!=0){
		echo "\n";
	}
	if(($i+1)<$t){
		echo ",";
	}
}
?>];