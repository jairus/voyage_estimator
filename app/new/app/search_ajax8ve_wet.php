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

if(isset($_GET['num'])){
	if($_GET['num']==1){
		if(isset($_GET['destination_port'])){
			$destination_port = $_GET['destination_port'];
			
			$sql = "SELECT * FROM `_veson_ports` WHERE `name`='".$destination_port."' LIMIT 0, 1";
			$r = dbQuery($sql, $link);
			
			if($r[0]['id']){
				$sqlext = "";
				$sqlext2 = "";
				
				$portid = $r[0]['portid'];
				$port_latitude = $r[0]['latitude'];
				$port_longitude = $r[0]['longitude'];
				$destination_port_from = date('Y-m-d', strtotime($_GET['destination_port_from']));
				$destination_port_to = date('Y-m-d', strtotime($_GET['destination_port_to']));
				
				if(!is_array($_GET['vessel_type'])){
					$vessel_type = trim(mysql_escape_string($_GET['vessel_type']));
				}else{
					$vessel_type = $_GET['vessel_type'];
				}
				
				$dwt_range = $_GET['dwt_range'];
				if($dwt_range=="0|3"){
					$dwt_low = 0;
					$dwt_high = 3000;
				}else if($dwt_range=="3|10"){
					$dwt_low = 3000;
					$dwt_high = 10000;
				}else if($dwt_range=="10|19"){
					$dwt_low = 10000;
					$dwt_high = 19000;
				}else if($dwt_range=="19|30"){
					$dwt_low = 19000;
					$dwt_high = 30000;
				}else if($dwt_range=="30|55"){
					$dwt_low = 30000;
					$dwt_high = 55000;
				}else if($dwt_range=="50|80"){
					$dwt_low = 50000;
					$dwt_high = 80000;
				}else if($dwt_range=="80|120"){
					$dwt_low = 80000;
					$dwt_high = 120000;
				}else if($dwt_range=="120|200"){
					$dwt_low = 120000;
					$dwt_high = 200000;
				}else if($dwt_range=="200|350"){
					$dwt_low = 200000;
					$dwt_high = 350000;
				}else if($dwt_range=="350|550"){
					$dwt_low = 350000;
					$dwt_high = 550000;
				}
				
				$sqlext .= " `siitech_destination`='".$destination_port."' AND ";
				$sqlext .= " (`siitech_eta` BETWEEN '".$destination_port_from."' AND '".$destination_port_to."') and ";
				$sqlext2 .= " (`siitech_eta` BETWEEN '".$destination_port_from."' AND '".$destination_port_to."') and ";
				
				$vtarr = count($vessel_type);
			
				if($vtarr){
					$sqlext .= " ( ";
					$sqlext2 .= " ( ";
				}
				
				for($vti=0; $vti<$vtarr; $vti++){
					$sqlext .= " `xvas_vessel_type`='".$vessel_type[$vti]."' ";
					$sqlext2 .= " `xvas_vessel_type`='".$vessel_type[$vti]."' ";
			
					if(($vti+1)<$vtarr){
						$sqlext .= " or ";
						$sqlext2 .= " or ";
					}
				}
				
				if($vtarr){
					$sqlext .= " ) and ";
					$sqlext2 .= " ) and ";
				}
				
				$sqlext .= " (`xvas_summer_dwt` BETWEEN '".$dwt_low."' AND '".$dwt_high."') ";
				$sqlext2 .= " (`xvas_summer_dwt` BETWEEN '".$dwt_low."' AND '".$dwt_high."') AND ";
				
				$sql_ships = "SELECT * FROM `_xvas_siitech_cache_wet` WHERE ".$sqlext." ORDER BY `dateupdated`";
				$r_ships = dbQuery($sql_ships, $link);
				
				$sql2 = "SELECT * FROM `_other_ports` WHERE `portid`='".$r[0]['portid']."'";
				$r2 = dbQuery($sql2, $link);
				
				$t_op = count($r2);
				
				if($t_op){
					$sqlext2 .= " ( ";
					for($i=0; $i<$t_op; $i++){
						$sqlext2 .= " `siitech_destination`='".$r2[$i]['name']."' ";
						
						if(($i+1)<$t_op){
							$sqlext2 .= " or ";
						}
					}
					$sqlext2 .= " ) ";
					
					$sql_ships2 = "SELECT * FROM `_xvas_siitech_cache_wet` WHERE ".$sqlext2." ORDER BY `dateupdated`";
					$r_ships2 = dbQuery($sql_ships2, $link);
					
					if($r_ships){
						$ships = array_merge($r_ships, $r_ships2);
						$r_ships = array_values($ships);
					}else{
						$r_ships = $r_ships2;
					}
					
					$t_ships = count($r_ships);
				}else{
					$t_ships = count($r_ships);
				}
				
				if($t_ships){
					echo '<ul>
						<li name="fragment-1" style="background: url("images/specs.jpg") no-repeat 5px 5px ; padding-left:30px; width:165px"  ><a><span>&nbsp;&nbsp;fixture management</span></a></li>
						<li name="fragment-2" style="background: url("images/shipeta.jpg") no-repeat 5px 5px ; padding-left:30px; width:165px"><a><span>&nbsp;position report</span></a></li>
						<li name="fragment-3" style="background: url("images/sched.jpg") no-repeat 5px 5px ; padding-left:30px; width:165px"><a><span>&nbsp;fixtures report</span></a></li>
					</ul>
				
					<div id="fragment-1">';
					include_once(dirname(__FILE__)."/includes/shipsearch/specifications_ais_broker_wet.php");
					echo '</div>
				
					<div id="fragment-2">';
					include_once(dirname(__FILE__)."/includes/shipsearch/positions_ais_broker_wet.php");
					echo '</div>
				
					<div id="fragment-3">';
					include_once(dirname(__FILE__)."/includes/shipsearch/schedule_ais_broker_wet.php");
					echo '</div>';
				}else{
					echo 'No results.';
				}
			}else{
				echo 'Destination port is not available';
			}
		}else{
			echo 'Please select a destination port';
		}
	}else if($_GET['num']==2){
		$sqlext = "";
	
		$destination_port_from = date('Y-m-d', strtotime($_GET['destination_port_from2']));
		$destination_port_to = date('Y-m-d', strtotime($_GET['destination_port_to2']));
		
		if(!is_array($_GET['vessel_type2'])){
			$vessel_type = trim(mysql_escape_string($_GET['vessel_type2']));
		}else{
			$vessel_type = $_GET['vessel_type2'];
		}
		
		$dwt_range = $_GET['dwt_range2'];
		if($dwt_range=="0|3"){
			$dwt_low = 0;
			$dwt_high = 3000;
		}else if($dwt_range=="3|10"){
			$dwt_low = 3000;
			$dwt_high = 10000;
		}else if($dwt_range=="10|19"){
			$dwt_low = 10000;
			$dwt_high = 19000;
		}else if($dwt_range=="19|30"){
			$dwt_low = 19000;
			$dwt_high = 30000;
		}else if($dwt_range=="30|55"){
			$dwt_low = 30000;
			$dwt_high = 55000;
		}else if($dwt_range=="50|80"){
			$dwt_low = 50000;
			$dwt_high = 80000;
		}else if($dwt_range=="80|120"){
			$dwt_low = 80000;
			$dwt_high = 120000;
		}else if($dwt_range=="120|200"){
			$dwt_low = 120000;
			$dwt_high = 200000;
		}else if($dwt_range=="200|350"){
			$dwt_low = 200000;
			$dwt_high = 350000;
		}else if($dwt_range=="350|550"){
			$dwt_low = 350000;
			$dwt_high = 550000;
		}
		
		$zone_code = $_GET['zone'];
		
		$sqlext .= " `siitech_eta` BETWEEN '".$destination_port_from."' AND '".$destination_port_to."' and ";
		
		$vtarr = count($vessel_type);
	
		if($vtarr){ $sqlext .= " ( "; }
		
		for($vti=0; $vti<$vtarr; $vti++){
			$sqlext .= " `xvas_vessel_type`='".$vessel_type[$vti]."' ";
	
			if(($vti+1)<$vtarr){ $sqlext .= " or "; }
		}
		
		if($vtarr){ $sqlext .= " ) and "; }
		
		$sqlext .= " (`xvas_summer_dwt` BETWEEN '".$dwt_low."' AND '".$dwt_high."') ";
		
		$sql_ships = "SELECT * FROM `_xvas_siitech_cache_wet` WHERE ".$sqlext." ORDER BY `dateupdated`";
		$r_ships = dbQuery($sql_ships, $link);
		
		$t_ships = count($r_ships);
		
		if($t_ships){
			echo '<ul>
				<li name="fragment-1" style="background: url("images/specs.jpg") no-repeat 5px 5px ; padding-left:30px; width:165px"  ><a><span>&nbsp;&nbsp;fixture management</span></a></li>
				<li name="fragment-2" style="background: url("images/shipeta.jpg") no-repeat 5px 5px ; padding-left:30px; width:165px"><a><span>&nbsp;position report</span></a></li>
				<li name="fragment-3" style="background: url("images/sched.jpg") no-repeat 5px 5px ; padding-left:30px; width:165px"><a><span>&nbsp;fixtures report</span></a></li>
			</ul>
		
			<div id="fragment-1">';
			include_once(dirname(__FILE__)."/includes/shipsearch/specifications_ais_broker2_wet.php");
			echo '</div>
		
			<div id="fragment-2">';
			include_once(dirname(__FILE__)."/includes/shipsearch/positions_ais_broker2_wet.php");
			echo '</div>
		
			<div id="fragment-3">';
			include_once(dirname(__FILE__)."/includes/shipsearch/schedule_ais_broker2_wet.php");
			echo '</div>';
		}else{
			echo 'No results.';
		}
	}
}
?>