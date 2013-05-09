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
	//VESSEL TYPES
	$bulk_carrier = array(
		0=>'BULK CARRIER WITH VEHICLE DECKS', 
		1=>'WOOD CHIPS CARRIERS', 
		2=>'ORE CARRIERS', 
		3=>'OBO CARRIERS', 
		4=>'ORE/OIL CARRIERS', 
		5=>'ORE/BULK/OIL CARRIERS', 
		6=>'AGGREGATES BARGES', 
		7=>'BULK CARRIER', 
		8=>'SELF DISCHARGING BULK CARRIERS', 
		9=>'BULK/CONTAINER CARRIERS', 
		10=>'BULK STORAGE BARGES', 
		11=>'AGGREGATES CARRIERS'
	);
	$t1 = count($bulk_carrier);
	
	$cargo = array(
		0=>'CARGO', 
		1=>'DECK CARGO SHIPS', 
		2=>'CARGO/CONTAINERSHIPS', 
		3=>'LIMESTONE CARRIERS', 
		4=>'LIVESTOCK CARRIERS', 
		5=>'PALLET CARRIERS', 
		6=>'GENERAL CARGOES', 
		7=>'CARGO BARGES', 
		8=>'BARGE CARRIERS', 
		9=>'SLUDGE CARRIERS', 
		10=>'HEAVY LOAD CARRIERS', 
		11=>'POWDER CARRIERS', 
		12=>'PASSENGER/CARGO SHIPS', 
		13=>'NUCLEAR FUEL CARRIERS', 
		14=>'MOTOR HOPPERS', 
		15=>'STONE CARRIERS', 
		16=>'REEFER/CONTAINERSHIPS', 
		17=>'TIMBER CARRIERS', 
		18=>'CEMENT BARGES', 
		19=>'CEMENT CARRIERS', 
		20=>'REEFERS'
	);
	$t2 = count($cargo);
	
	$container_ships = array(
		0=>'CONTAINER SHIPS'
	);
	$t3 = count($container_ships);
	
	$ro_ro_cargo = array(
		0=>'RO-RO/PASSENGER SHIPS', 
		1=>'VEHICLES CARRIERS', 
		2=>'RO-RO/CONTAINER CARRIERS', 
		3=>'RAIL/VEHICLES CARRIERS'
	);
	$t4 = count($ro_ro_cargo);
	
	$passenger_ship = array(
		0=>'PASSENGERS LANDING CRAFTS', 
		1=>'YACHTS', 
		2=>'HYDROFOILS', 
		3=>'THEATRE VESSELS', 
		4=>'CREW BOATS', 
		5=>'PADDLE SHIPS', 
		6=>'MUSEUM SHIPS', 
		7=>'ACCOMMODATION BARGES', 
		8=>'HOUSEBOATS', 
		9=>'EXHIBITION SHIPS', 
		10=>'ACCOMMODATION VESSELS', 
		11=>'SAILING VESSELS', 
		12=>'FLOATING HOTEL/RESTAURANTS'
	);
	$t5 = count($passenger_ship);
	
	$supply_vessels = array(
		0=>'TRANS SHIPMENT VESSELS', 
		1=>'TRENCHING SUPPORT VESSELS', 
		2=>'DIVING SUPPORT VESSELS', 
		3=>'MOORING VESSELS', 
		4=>'ARTICULATED PUSHER TUGS', 
		5=>'TOWING VESSELS', 
		6=>'OFFSHORE SAFETY VESSELS', 
		7=>'TUG/SUPPLY VESSELS', 
		8=>'ANCHOR HANDLING VESSELS', 
		9=>'PUSHER TUGS', 
		10=>'SUPPLY TENDERS', 
		11=>'OFFSHORE SUPPLY SHIPS', 
		12=>'STANDBY SAFETY VESSELS', 
		13=>'SALVAGE/RESCUE VESSELS', 
		14=>'POLLUTION CONTROL VESSELS', 
		15=>'TUGS', 
		16=>'FIRE FIGHTING VESSELS', 
		17=>'UTILITY VESSELS', 
		18=>'TUG/ICE BREAKERS', 
		19=>'MULTI PURPOSE OFFSHORE VESSELS', 
		20=>'TRANS SHIPMENT BARGES', 
		21=>'PIPE CARRIERS'
	);
	$t6 = count($supply_vessels);
	
	$special_vessels = array(
		0=>'ACCOMMODATION SHIPS', 
		1=>'PIPELAY BARGES', 
		2=>'CABLE LAYERS', 
		3=>'LANDING CRAFTS', 
		4=>'ICEBREAKERS', 
		5=>'WASTE DISPOSAL VESSELS', 
		6=>'MISSION SHIPS', 
		7=>'PIPE LAYERS', 
		8=>'HOSPITAL SHIPS', 
		9=>'PATROL VESSELS', 
		10=>'PILOT SHIPS', 
		11=>'TENDERS', 
		12=>'TRAINING SHIPS', 
		13=>'WORK VESSELS', 
		14=>'HEAVY LIFT VESSELS', 
		15=>'TANK-CLEANING VESSELS', 
		16=>'WELL STIMULATION VESSELS', 
		17=>'PRODUCTION TESTING VESSELS', 
		18=>'RESEARCH/SURVEY VESSELS', 
		19=>'MINING VESSELS', 
		20=>'REPAIR SHIPS', 
		21=>'BUOY-LAYING VESSELS', 
		22=>'MAINTENANCE VESSELS', 
		23=>'RADIO SHIPS', 
		24=>'POWER STATION VESSELS'
	);
	$t7 = count($special_vessels);
	
	$air_cushion_vessels = array(
		0=>'AIR CUSHION RO-RO/PASSENGER SHIPS', 
		1=>'AIR CUSHION PASSENGER SHIPS', 
		2=>'AIR CUSHION CREW BOATS', 
		3=>'AIR CUSHION WORK VESSELS', 
		4=>'AIR CUSHION PATROL VESSELS', 
		5=>'HOVERCRAFTS', 
		6=>'AIR CUSHION RESEARCH VESSELS', 
		7=>'WING IN GROUND EFFECT VESSELS'
	);
	$t8 = count($air_cushion_vessels);
	
	$inland_vessels = array(
		0=>'INLAND RESEARCH VESSELS', 
		1=>'INLAND RO-RO CARGO SHIPS', 
		2=>'INLAND CARGO/PASSENGER SHIPS', 
		3=>'INLAND CARGOES', 
		4=>'INLAND DREDGERS', 
		5=>'INLAND TANKERS', 
		6=>'INLAND SUPPLY VESSELS', 
		7=>'INLAND PASSENGERS SHIPS', 
		8=>'INLAND FERRIES'
	);
	$t9 = count($inland_vessels);
	//END OF VESSEL TYPES

	if($_GET['num']==1){
		if(isset($_GET['destination_port'])){
			$destination_port = $_GET['destination_port'];
			
			$sql = "SELECT * FROM `_veson_ports` WHERE `name`='".$destination_port."' LIMIT 0, 1";
			$r = dbQuery($sql, $link);
			
			if($r[0]['id']){
				$sqlext = "";
				$sqlext2 = "";
				$sqlext3 = "";
				
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
				if($dwt_range=="0|10"){
					$dwt_low = 0;
					$dwt_high = 9999;
				}else if($dwt_range=="10|40"){
					$dwt_low = 10000;
					$dwt_high = 39999;
				}else if($dwt_range=="40|60"){
					$dwt_low = 40000;
					$dwt_high = 59999;
				}else if($dwt_range=="60|100"){
					$dwt_low = 60000;
					$dwt_high = 99999;
				}else if($dwt_range=="100|220"){
					$dwt_low = 100000;
					$dwt_high = 219999;
				}else if($dwt_range=="220|550"){
					$dwt_low = 220000;
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
					if($vessel_type[$vti]==1){
						for($i1=0; $i1<$t1; $i1++){
							$sqlext .= " `xvas_vessel_type`='".$bulk_carrier[$i1]."' ";
							$sqlext2 .= " `xvas_vessel_type`='".$bulk_carrier[$i1]."' ";
							$sqlext3 .= "a.vessel_type='".$bulk_carrier[$i1]."'";
							
							if(($i1+1)<$t1){
								$sqlext .= " or ";
								$sqlext2 .= " or ";
								$sqlext3 .= " or ";
							}
						}
					}
				
					if($vessel_type[$vti]==2){
						for($i2=0; $i2<$t2; $i2++){
							$sqlext .= " `xvas_vessel_type`='".$cargo[$i2]."' ";
							$sqlext2 .= " `xvas_vessel_type`='".$cargo[$i2]."' ";
							$sqlext3 .= "a.vessel_type='".$cargo[$i2]."'";
							
							if(($i2+1)<$t2){
								$sqlext .= " or ";
								$sqlext2 .= " or ";
								$sqlext3 .= " or ";
							}
						}
					}
					
					if($vessel_type[$vti]==3){
						for($i3=0; $i3<$t3; $i3++){
							$sqlext .= " `xvas_vessel_type`='".$container_ships[$i3]."' ";
							$sqlext2 .= " `xvas_vessel_type`='".$container_ships[$i3]."' ";
							$sqlext3 .= "a.vessel_type='".$container_ships[$i3]."'";
							
							if(($i3+1)<$t3){
								$sqlext .= " or ";
								$sqlext2 .= " or ";
								$sqlext3 .= " or ";
							}
						}
					}
					
					if($vessel_type[$vti]==4){
						for($i4=0; $i4<$t4; $i4++){
							$sqlext .= " `xvas_vessel_type`='".$ro_ro_cargo[$i4]."' ";
							$sqlext2 .= " `xvas_vessel_type`='".$ro_ro_cargo[$i4]."' ";
							$sqlext3 .= "a.vessel_type='".$ro_ro_cargo[$i4]."'";
							
							if(($i4+1)<$t4){
								$sqlext .= " or ";
								$sqlext2 .= " or ";
								$sqlext3 .= " or ";
							}
						}
					}
					
					if($vessel_type[$vti]==5){
						for($i5=0; $i5<$t5; $i5++){
							$sqlext .= " `xvas_vessel_type`='".$passenger_ship[$i5]."' ";
							$sqlext2 .= " `xvas_vessel_type`='".$passenger_ship[$i5]."' ";
							$sqlext3 .= "a.vessel_type='".$passenger_ship[$i5]."'";
							
							if(($i5+1)<$t5){
								$sqlext .= " or ";
								$sqlext2 .= " or ";
								$sqlext3 .= " or ";
							}
						}
					}
					
					if($vessel_type[$vti]==6){
						for($i6=0; $i6<$t6; $i6++){
							$sqlext .= " `xvas_vessel_type`='".$supply_vessels[$i6]."' ";
							$sqlext2 .= " `xvas_vessel_type`='".$supply_vessels[$i6]."' ";
							$sqlext3 .= "a.vessel_type='".$supply_vessels[$i6]."'";
							
							if(($i6+1)<$t6){
								$sqlext .= " or ";
								$sqlext2 .= " or ";
								$sqlext3 .= " or ";
							}
						}
					}
					
					if($vessel_type[$vti]==7){
						for($i7=0; $i7<$t7; $i7++){
							$sqlext .= " `xvas_vessel_type`='".$special_vessels[$i7]."' ";
							$sqlext2 .= " `xvas_vessel_type`='".$special_vessels[$i7]."' ";
							$sqlext3 .= "a.vessel_type='".$special_vessels[$i7]."'";
							
							if(($i7+1)<$t7){
								$sqlext .= " or ";
								$sqlext2 .= " or ";
								$sqlext3 .= " or ";
							}
						}
					}
					
					if($vessel_type[$vti]==8){
						for($i8=0; $i8<$t8; $i8++){
							$sqlext .= " `xvas_vessel_type`='".$air_cushion_vessels[$i8]."' ";
							$sqlext2 .= " `xvas_vessel_type`='".$air_cushion_vessels[$i8]."' ";
							$sqlext3 .= "a.vessel_type='".$air_cushion_vessels[$i8]."'";
							
							if(($i8+1)<$t8){
								$sqlext .= " or ";
								$sqlext2 .= " or ";
								$sqlext3 .= " or ";
							}
						}
					}
					
					if($vessel_type[$vti]==9){
						for($i9=0; $i9<$t9; $i9++){
							$sqlext .= " `xvas_vessel_type`='".$inland_vessels[$i9]."' ";
							$sqlext2 .= " `xvas_vessel_type`='".$inland_vessels[$i9]."' ";
							$sqlext3 .= "a.vessel_type='".$inland_vessels[$i9]."'";
							
							if(($i9+1)<$t9){
								$sqlext .= " or ";
								$sqlext2 .= " or ";
								$sqlext3 .= " or ";
							}
						}
					}
			
					if(($vti+1)<$vtarr){
						$sqlext .= " or ";
						$sqlext2 .= " or ";
						$sqlext3 .= " or ";
					}
				}
				
				if($vtarr){
					$sqlext .= " ) and ";
					$sqlext2 .= " ) and ";
					$sqlext3 .= " and ";
				}
				
				$sqlext .= " (`xvas_summer_dwt` BETWEEN '".$dwt_low."' AND '".$dwt_high."') ";
				$sqlext2 .= " (`xvas_summer_dwt` BETWEEN '".$dwt_low."' AND '".$dwt_high."') AND ";
				$sqlext3 .= " (a.summer_dwt BETWEEN '".$dwt_low."' AND '".$dwt_high."')";
				
				$sql_ships = "SELECT * FROM `_xvas_siitech_cache` WHERE ".$sqlext." ORDER BY `dateupdated`";
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
					
					$sql_ships2 = "SELECT * FROM `_xvas_siitech_cache` WHERE ".$sqlext2." ORDER BY `dateupdated`";
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
				
				$userid = $_SESSION['user']['id'];
				$sql_broker = "SELECT a.imo AS xvas_imo, a.vessel_type, b.id AS message_id, b.imo AS message_imo, b.message FROM _xvas_parsed2_dry AS a INNER JOIN _messages AS b ON a.imo=b.imo WHERE ".$sqlext3." AND b.type='network' AND b.user_email in ( 
						select `email` from `_sbis_users` where 
								`id` in (
									select `userid1` from _network where (`userid1` = '".$userid."' or `userid2` = '".$userid."')
								) or
								`id` in (
									select `userid2` from _network where (`userid1` = '".$userid."' or `userid2` = '".$userid."')
								)
							)";
				$r_broker = dbQuery($sql_broker, $link);
				$t_broker = count($r_broker);
				
				if($t_ships || $t_broker){
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
				echo 'Destination port is not available';
			}
		}else{
			echo 'Please select a destination port';
		}
	}else if($_GET['num']==2){
		$sqlext = "";
		$sqlext3 = "";
	
		$destination_port_from = date('Y-m-d', strtotime($_GET['destination_port_from2']));
		$destination_port_to = date('Y-m-d', strtotime($_GET['destination_port_to2']));
		
		if(!is_array($_GET['vessel_type2'])){
			$vessel_type = trim(mysql_escape_string($_GET['vessel_type2']));
		}else{
			$vessel_type = $_GET['vessel_type2'];
		}
		
		$dwt_range = $_GET['dwt_range2'];
		if($dwt_range=="0|10"){
			$dwt_low = 0;
			$dwt_high = 9999;
		}else if($dwt_range=="10|40"){
			$dwt_low = 10000;
			$dwt_high = 39999;
		}else if($dwt_range=="40|60"){
			$dwt_low = 40000;
			$dwt_high = 59999;
		}else if($dwt_range=="60|100"){
			$dwt_low = 60000;
			$dwt_high = 99999;
		}else if($dwt_range=="100|220"){
			$dwt_low = 100000;
			$dwt_high = 219999;
		}else if($dwt_range=="220|550"){
			$dwt_low = 220000;
			$dwt_high = 550000;
		}
		
		$zone_code = $_GET['zone'];
		
		$sqlext .= " `siitech_eta` BETWEEN '".$destination_port_from."' AND '".$destination_port_to."' and ";
		
		$vtarr = count($vessel_type);
	
		if($vtarr){
			$sqlext .= " ( ";
			$sqlext3 .= " ( ";
		}
		
		for($vti=0; $vti<$vtarr; $vti++){
			if($vessel_type[$vti]==1){
				for($i1=0; $i1<$t1; $i1++){
					$sqlext .= " `xvas_vessel_type`='".$bulk_carrier[$i1]."' ";
					$sqlext3 .= "a.vessel_type='".$bulk_carrier[$i1]."'";
					
					if(($i1+1)<$t1){
						$sqlext .= " or ";
						$sqlext3 .= " or ";
					}
				}
			}
		
			if($vessel_type[$vti]==2){
				for($i2=0; $i2<$t2; $i2++){
					$sqlext .= " `xvas_vessel_type`='".$cargo[$i2]."' ";
					$sqlext3 .= "a.vessel_type='".$cargo[$i2]."'";
					
					if(($i2+1)<$t2){
						$sqlext .= " or ";
						$sqlext3 .= " or ";
					}
				}
			}
			
			if($vessel_type[$vti]==3){
				for($i3=0; $i3<$t3; $i3++){
					$sqlext .= " `xvas_vessel_type`='".$container_ships[$i3]."' ";
					$sqlext3 .= "a.vessel_type='".$container_ships[$i3]."'";
					
					if(($i3+1)<$t3){
						$sqlext .= " or ";
						$sqlext3 .= " or ";
					}
				}
			}
			
			if($vessel_type[$vti]==4){
				for($i4=0; $i4<$t4; $i4++){
					$sqlext .= " `xvas_vessel_type`='".$ro_ro_cargo[$i4]."' ";
					$sqlext3 .= "a.vessel_type='".$ro_ro_cargo[$i4]."'";
					
					if(($i4+1)<$t4){
						$sqlext .= " or ";
						$sqlext3 .= " or ";
					}
				}
			}
			
			if($vessel_type[$vti]==5){
				for($i5=0; $i5<$t5; $i5++){
					$sqlext .= " `xvas_vessel_type`='".$passenger_ship[$i5]."' ";
					$sqlext3 .= "a.vessel_type='".$passenger_ship[$i5]."'";
					
					if(($i5+1)<$t5){
						$sqlext .= " or ";
						$sqlext3 .= " or ";
					}
				}
			}
			
			if($vessel_type[$vti]==6){
				for($i6=0; $i6<$t6; $i6++){
					$sqlext .= " `xvas_vessel_type`='".$supply_vessels[$i6]."' ";
					$sqlext3 .= "a.vessel_type='".$supply_vessels[$i6]."'";
					
					if(($i6+1)<$t6){
						$sqlext .= " or ";
						$sqlext3 .= " or ";
					}
				}
			}
			
			if($vessel_type[$vti]==7){
				for($i7=0; $i7<$t7; $i7++){
					$sqlext .= " `xvas_vessel_type`='".$special_vessels[$i7]."' ";
					$sqlext3 .= "a.vessel_type='".$special_vessels[$i7]."'";
					
					if(($i7+1)<$t7){
						$sqlext .= " or ";
						$sqlext3 .= " or ";
					}
				}
			}
			
			if($vessel_type[$vti]==8){
				for($i8=0; $i8<$t8; $i8++){
					$sqlext .= " `xvas_vessel_type`='".$air_cushion_vessels[$i8]."' ";
					$sqlext3 .= "a.vessel_type='".$air_cushion_vessels[$i8]."'";
					
					if(($i8+1)<$t8){
						$sqlext .= " or ";
						$sqlext3 .= " or ";
					}
				}
			}
			
			if($vessel_type[$vti]==9){
				for($i9=0; $i9<$t9; $i9++){
					$sqlext .= " `xvas_vessel_type`='".$inland_vessels[$i9]."' ";
					$sqlext3 .= "a.vessel_type='".$inland_vessels[$i9]."'";
					
					if(($i9+1)<$t9){
						$sqlext .= " or ";
						$sqlext3 .= " or ";
					}
				}
			}
	
			if(($vti+1)<$vtarr){
				$sqlext .= " or ";
				$sqlext3 .= " or ";
			}
		}
		
		if($vtarr){
			$sqlext .= " ) and ";
			$sqlext3 .= " ) and ";
		}
		
		$sqlext .= " (`xvas_summer_dwt` BETWEEN '".$dwt_low."' AND '".$dwt_high."') ";
		$sqlext3 .= " (a.summer_dwt BETWEEN '".$dwt_low."' AND '".$dwt_high."')";
		
		$sql_ships = "SELECT * FROM `_xvas_siitech_cache` WHERE ".$sqlext." ORDER BY `dateupdated`";
		$r_ships = dbQuery($sql_ships, $link);
		
		$t_ships = count($r_ships);
		
		$userid = $_SESSION['user']['id'];
		$sql_broker = "SELECT a.imo AS xvas_imo, a.vessel_type, b.id AS message_id, b.imo AS message_imo, b.message FROM _xvas_parsed2_dry AS a INNER JOIN _messages AS b ON a.imo=b.imo WHERE ".$sqlext3." AND b.type='network' AND b.user_email in ( 
						select `email` from `_sbis_users` where 
								`id` in (
									select `userid1` from _network where (`userid1` = '".$userid."' or `userid2` = '".$userid."')
								) or
								`id` in (
									select `userid2` from _network where (`userid1` = '".$userid."' or `userid2` = '".$userid."')
								)
							)";
		$r_broker = dbQuery($sql_broker, $link);
		$t_broker = count($r_broker);
		
		if($t_ships || $t_broker){
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
	}
}
?>