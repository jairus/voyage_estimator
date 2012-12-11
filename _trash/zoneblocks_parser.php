<?php
include_once(dirname(__FILE__)."/includes/bootstrap.php");

$sql = "SELECT zone_code FROM _sbis_zoneblocks";
$r = dbQuery($sql, $link);

$t = count($r);

if($t){
	for($i=0; $i<$t; $i++){
		$sql = "UPDATE _sbis_zoneblocks
				SET zone_code = 'z".mysql_escape_string($r[$i]['zone_code'])."'
				WHERE zone_code != ''
			";
		dbQuery($sql, $link);
		
		echo $r[$i]['zone_code'].' - z'.$r[$i]['zone_code'].'<br>';
	}
}
?>