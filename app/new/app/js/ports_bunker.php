<?php
include_once(dirname(__FILE__)."/../includes/database.php");
$link = dbConnect();
$sql_bunker = "select * from `_veson_ports` where 1";
$ports_bunker = dbQuery($sql_bunker, $link);


?>
var ports_bunker = [ <?php
$t_bunker = count($ports_bunker);
for($i_bunker=0; $i_bunker<$t_bunker; $i_bunker++){
	echo "\"".$ports_bunker[$i_bunker]['name']."\"";
	if($i_bunker%100==0&&$i_bunker!=0){
		echo "\n";
	}
	if(($i_bunker+1)<$t_bunker){
		echo ",";
	}
}
?>];