<?php
@session_start();

include_once(dirname(__FILE__)."/includes/bootstrap.php");

$load_port = $_GET['load_port'];
$load_port_from = date('Y-m-d', strtotime($_GET['load_port_from']));
$load_port_to = date('Y-m-d', strtotime($_GET['load_port_to']));
$sqlext = "";

if(!is_array($_GET['vessel_type'])){
	$vessel_type = trim(mysql_escape_string($_GET['vessel_type']));
}else{
	$vessel_type = $_GET['vessel_type'];
}

$dwt_range = $_GET['dwt_range'];
$slimit = $_GET['slimit'];

$sqlext .= " `siitech_destination`='".$load_port."' AND ";
$sqlext .= " (`siitech_eta` BETWEEN '".$load_port_from."' AND '".$load_port_to."') ";

if($vessel_type){
	$vtarr = count($vessel_type);
	
	$sqlext .= " and ";

	if($vtarr){
		$sqlext .= " ( ";
	}
	
	for($vti=0; $vti<$vtarr; $vti++){
		$sqlext .= " `xvas_vessel_type`='".$vessel_type[$vti]."' ";

		if(($vti+1)<$vtarr){
			$sqlext .= " or ";
		}
	}
	
	if($vtarr){
		$sqlext .= " ) ";
	}
	
	$sqlext .= " and ";
}

if($dwt_range=="0|5"){
	$dwt_low = 0;
	$dwt_high = 5000;
}else if($dwt_range=="5|10"){
	$dwt_low = 5000;
	$dwt_high = 10000;
}else if($dwt_range=="0|10"){
	$dwt_low = 0;
	$dwt_high = 10000;
}else if($dwt_range=="10|15"){
	$dwt_low = 10000;
	$dwt_high = 15000;
}else if($dwt_range=="15|20"){
	$dwt_low = 15000;
	$dwt_high = 20000;
}else if($dwt_range=="20|25"){
	$dwt_low = 20000;
	$dwt_high = 25000;
}else if($dwt_range=="25|30"){
	$dwt_low = 25000;
	$dwt_high = 30000;
}else if($dwt_range=="30|35"){
	$dwt_low = 30000;
	$dwt_high = 35000;
}else if($dwt_range=="10|35"){
	$dwt_low = 10000;
	$dwt_high = 35000;
}else if($dwt_range=="35|40"){
	$dwt_low = 35000;
	$dwt_high = 40000;
}else if($dwt_range=="40|45"){
	$dwt_low = 40000;
	$dwt_high = 45000;
}else if($dwt_range=="45|50"){
	$dwt_low = 45000;
	$dwt_high = 50000;
}else if($dwt_range=="50|55"){
	$dwt_low = 50000;
	$dwt_high = 55000;
}else if($dwt_range=="55|60"){
	$dwt_low = 55000;
	$dwt_high = 60000;
}else if($dwt_range=="35|60"){
	$dwt_low = 35000;
	$dwt_high = 60000;
}else if($dwt_range=="60|65"){
	$dwt_low = 60000;
	$dwt_high = 65000;
}else if($dwt_range=="65|70"){
	$dwt_low = 65000;
	$dwt_high = 70000;
}else if($dwt_range=="70|75"){
	$dwt_low = 70000;
	$dwt_high = 75000;
}else if($dwt_range=="60|75"){
	$dwt_low = 60000;
	$dwt_high = 75000;
}else if($dwt_range=="75|110"){
	$dwt_low = 75000;
	$dwt_high = 110000;
}else if($dwt_range=="110|150"){
	$dwt_low = 110000;
	$dwt_high = 150000;
}else if($dwt_range=="150|550"){
	$dwt_low = 150000;
	$dwt_high = 555000;
}

$sqlext .= " (`xvas_summer_dwt` BETWEEN '".$dwt_low."' AND '".$dwt_high."') ";

if(trim($load_port) && trim($load_port_from) && trim($load_port_to) && trim($vessel_type) && trim($dwt_range) && trim($slimit)){
	$sql = "SELECT * FROM `_xvas_siitech_cache` WHERE ".$sqlext." ORDER BY `dateupdated` LIMIT 0,".$slimit."";
	$shipsA1print = dbQuery($sql, $link);
	
	$t = count($shipsA1print);
	
	if($t){
		echo '<ul>
			<li name="fragment-1" style="background: url("images/specs.jpg") no-repeat 5px 5px ; padding-left:30px; width:165px"  ><a><span>&nbsp;&nbsp;fixture management</span></a></li>
			<li name="fragment-2" style="background: url("images/shipeta.jpg") no-repeat 5px 5px ; padding-left:30px; width:165px"><a><span>&nbsp;position report</span></a></li>
			<li name="fragment-3" style="background: url("images/sched.jpg") no-repeat 5px 5px ; padding-left:30px; width:165px"><a><span>&nbsp;fixtures report</span></a></li>
		</ul>
	
		<div id="fragment-1">';
		include_once(dirname(__FILE__)."/includes/shipsearch/specifications_ais_broker.php");
		echo '</div>
	
		<div id="fragment-2">';
		include_once(dirname(__FILE__)."/includes/shipsearch/positions_ais_broker.php");
		echo '</div>
	
		<div id="fragment-3">';
		include_once(dirname(__FILE__)."/includes/shipsearch/schedule_ais_broker.php");
		echo '</div>';
	}else{
		echo 'No results.';
	}
}else{
	echo 'Please complete the parameters.';
}
?>