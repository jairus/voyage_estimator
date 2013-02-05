<?php
@session_start();

include_once(dirname(__FILE__)."/includes/bootstrap.php");

function getMessageByImo($imo, $type){
	$hasnum = preg_match("/[0-9]/iUs", $type);
	$type = preg_replace("/[0-9]/iUs", "", $type);

	global $link;

	$imo = mysql_escape_string($imo);

	if(strtolower($type)=='private'){
		$userid = $_SESSION['user']['id'];
		$sql = "select `email` from `_sbis_users` where `id`='".$userid."'";
		$email = dbQuery($sql, $link);
		$email = $email[0]['email'];

		$sql = "select * from `_messages` where `imo`='".$imo."' and `type`='private' and `user_email` = '".$email."' order by `id` desc limit 1";
	}else if(strtolower($type)=='remark'||strtolower($type)=='openport'||strtolower($type)=='opendate'||strtolower($type)=='destinationregion'||strtolower($type)=='destinationdate'||strtolower($type)=='charterer'||strtolower($type)=='cargotype'||strtolower($type)=='quantity'||strtolower($type)=='status'||strtolower($type)=='cbm'||strtolower($type)=='rate'||strtolower($type)=='tce'||strtolower($type)=='ws'||strtolower($type)=='network'||strtolower($type)=='user_email'){
		$userid = $_SESSION['user']['id'];		

		$sql = "select * from `_messages` where `imo`='".$imo."' and `type`='network' and 

		`user_email` in ( 

			select `email` from `_sbis_users` where 

			`id` in (

				select `userid1` from _network where (`userid1` = '".$userid."' or `userid2` = '".$userid."')

			) or

			`id` in (

				select `userid2` from _network where (`userid1` = '".$userid."' or `userid2` = '".$userid."')

			)
		)

		order by `id` desc limit 1";
	}

	$r = dbQuery($sql, $link);

	if($hasnum ){}

	return $r[0];
}

function word_limit($str, $limit){
    $str .= "";

    $str = trim($str);

    $l = strlen($str);

    $s = "";

    for($i=0; $i<$l; $i++){
        $s .= $str[$i];

        if($limit>0&&(preg_match("/\s/", $str[$i]))){
            if(!preg_match("/\s/", $str[$i+1]))
                $limit--;

            if(!$limit){
                return $s."...";

                break;
            }
        }
    }

    return $s;
}

if($_GET['num']==1){
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
	
	if(trim($load_port) && trim($load_port_from) && trim($load_port_to) && trim($vessel_type) && trim($dwt_range)){
		$sql = "SELECT * FROM `_xvas_siitech_cache` WHERE ".$sqlext." ORDER BY `dateupdated`";
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
}else if($_GET['num']==2){
	$load_port_from = date('Y-m-d', strtotime($_GET['load_port_from2']));
	$load_port_to = date('Y-m-d', strtotime($_GET['load_port_to2']));
	$zone = $_GET['zone'];
	$sqlext = "";
	
	if(!is_array($_GET['vessel_type2'])){
		$vessel_type = trim(mysql_escape_string($_GET['vessel_type2']));
	}else{
		$vessel_type = $_GET['vessel_type2'];
	}
	
	$dwt_range = $_GET['dwt_range2'];
	
	$sqlext .= " `siitech_eta` BETWEEN '".$load_port_from."' AND '".$load_port_to."' ";
	
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
	
	if(trim($load_port_from) && trim($load_port_to) && trim($vessel_type) && trim($dwt_range) && trim($zone)){
		$sql = "SELECT * FROM `_xvas_siitech_cache` WHERE ".$sqlext." ORDER BY `dateupdated`";
		$shipsA1print = dbQuery($sql, $link);
		
		$t = count($shipsA1print);
		
		if($t){
			echo '<ul>
				<li name="fragment-1" style="background: url("images/specs.jpg") no-repeat 5px 5px ; padding-left:30px; width:165px"  ><a><span>&nbsp;&nbsp;fixture management</span></a></li>
				<li name="fragment-2" style="background: url("images/shipeta.jpg") no-repeat 5px 5px ; padding-left:30px; width:165px"><a><span>&nbsp;position report</span></a></li>
				<li name="fragment-3" style="background: url("images/sched.jpg") no-repeat 5px 5px ; padding-left:30px; width:165px"><a><span>&nbsp;fixtures report</span></a></li>
			</ul>
		
			<div id="fragment-1">';
			include_once(dirname(__FILE__)."/includes/shipsearch/specifications_ais_broker2.php");
			echo '</div>
		
			<div id="fragment-2">';
			include_once(dirname(__FILE__)."/includes/shipsearch/positions_ais_broker2.php");
			echo '</div>
		
			<div id="fragment-3">';
			include_once(dirname(__FILE__)."/includes/shipsearch/schedule_ais_broker2.php");
			echo '</div>';
		}else{
			echo 'No results.';
		}
	}else{
		echo 'Please complete the parameters.';
	}
}
?>