<?php
@session_start();
date_default_timezone_set('UTC');

include_once(dirname(__FILE__)."/includes/bootstrap.php");

$link = dbConnect();

function floorTs($ts){
	$date = date("Y-m-d 00:00:00",$ts);
	$ts = strtotime($date);

	return $ts;
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



$ship = explode(' - ', trim($_GET['ship']));
$ship_name = $ship[0];
$ship_imo = $ship[1];

$destination_port_from = date('Y-m-d', strtotime($_GET['destination_port_from']));
$destination_port_to = date('Y-m-d', strtotime($_GET['destination_port_to']));

if(!$ship){
	echo "Invalid Search Parameters";

	exit();
}

$sql = "SELECT imo, name FROM `_xvas_parsed2_dry` WHERE name='".mysql_escape_string($ship_name)."' AND imo='".mysql_escape_string($ship_imo)."' ORDER BY dateupdated DESC LIMIT 0,1";
$ships = dbQuery($sql, $link);

$t = count($ships);

echo "<table id='pblues' width='1300'>
	<tr>
		<th style='background:#BCBCBC; color:#333333; text-align:left; width:200px;'><div style='padding:5px;'>SHIP NAME</div></th>
	</tr>";

if(trim($t)){
	$shipsA1print = array();
	
	for($i=0; $i<$t; $i++){
		//CHECK IF SHIP EXIST IN DATABASE
		$sql = "SELECT * FROM `_xvas_shipdata_dry` WHERE imo='".$ships[$i]['imo']."'";
		$ship_exist = dbQuery($sql, $link);
		$ship_exist = $ship_exist[0];
		//END OF CHECK IF SHIP EXIST IN DATABASE
		
		if(trim($ship_exist['data'])){
			$status = getValue($ship_exist['data'], 'STATUS');
			
			if(trim($status)!="DEAD"){
				//CHECK IF SHIP EXIST IN SIITECH CACHE
				$sql = "SELECT * FROM `_ship_history` WHERE xvas_imo='".$ships[$i]['imo']."' AND `siitech_eta` BETWEEN '".$destination_port_from."' AND '".$destination_port_to."' ORDER BY dateupdated DESC";
				$siitech_ships = dbQuery($sql, $link);
				
				$t1 = count($siitech_ships);
				//END
				
				if(trim($t1)){
					for($i1=0; $i1<$t1; $i1++){
						if($siitech_ships[$i1-1]['xvas_name']!=$siitech_ships[$i1]['xvas_name']){
							//MAP DETAILS
							$print = array();
							
							$print['id']        = $siitech_ships[$i1]['id'];
							$print['Ship Name'] = $siitech_ships[$i1]['xvas_name'];
							$print['IMO #']     = $siitech_ships[$i1]['xvas_imo'];
							
							$imageb          = base64_encode("http://dataservice.grosstonnage.com/S-Bisphoto.php?imo=".$print['IMO #']);
							$print['imageb'] = $imageb;
							
							$print['LAT']           = $siitech_ships[$i1]['siitech_latitude'];
							$print['LONG']          = $siitech_ships[$i1]['siitech_longitude'];
							$print['MMSI']          = $siitech_ships[$i1]['xvas_mmsi'];
							$print['VESSEL TYPE']   = $siitech_ships[$i1]['xvas_vessel_type'];
							$print['DWT']           = $siitech_ships[$i1]['xvas_summer_dwt'];
							$print['SPEED']         = $siitech_ships[$i1]['xvas_speed'];
							$print['satellite']     = $siitech_ships[$i1]['satellite'];
							$print['SIITECH_ETA']   = $siitech_ships[$i1]['siitech_eta'];
							$print['DESTINATION']   = $siitech_ships[$i1]['siitech_destination'];
							$print['LASTSEEN_DATE'] = $siitech_ships[$i1]['siitech_lastseen'];
							
							$print['SOG'] = getValue(strtolower($siitech_ships[$i1]['siitech_shippos_data']), "SOG");
							$print['TRUE HEADING'] = getValue(strtolower($siitech_ships[$i1]['siitech_shippos_data']), "trueheading");
						
							if(trim($print['TRUE HEADING'])){
								$print['TRUE HEADING'] .= " degrees";
							}
							
							$print['COG'] = getValue(strtolower($siitech_ships[$i1]['siitech_shippos_data']), "COG");
							$print['B2B'] = getValue(strtolower($siitech_ships[$i1]['siitech_shipstat_data']), "to_bow");
							$print['STERN'] = getValue(strtolower($siitech_ships[$i1]['siitech_shipstat_data']), "to_stern");
							$print['P2P'] = getValue(strtolower($siitech_ships[$i1]['siitech_shipstat_data']), "to_port");
							$print['STARBOARD'] = getValue(strtolower($siitech_ships[$i1]['siitech_shipstat_data']), "to_starboard");
							$print['RADIO'] = getValue(strtolower($siitech_ships[$i1]['siitech_shippos_data']), "radio");
							$print['MANEUVER'] = getValue(strtolower($siitech_ships[$i1]['siitech_shippos_data']), "maneuver");
							$print['NAVSTAT'] = getValue(strtolower($siitech_ships[$i1]['siitech_shippos_data']), "NavigationalStatus");
							$print['ETA'] = getValue(strtolower($siitech_ships[$i1]['siitech_shipstat_data']), "ETA");
							$print['SHIP_TYPE'] = getValue(strtolower($siitech_ships[$i1]['siitech_shipstat_data']), "ShipType");
							$print['UTC'] = getValue(strtolower($siitech_ships[$i1]['siitech_shippos_data']), "UTC");
							
							$shipsA1print[] = $print;
							//END OF MAP DETAILS
						}
					}
				}
			}
		}
	}
	
	$t2 = count($shipsA1print);
				
	$_SESSION['shipsReg'] = $shipsA1print;
	
	if($t2){
		$shipsA2print = array();
		
		for($i2=0; $i2<$t2; $i2++){
			$print1 = array();
			
			$details           = array();
			$details['a']      = 'shipsReg';
			$details['id']     = $i2;
			$print1['details'] = base64_encode(serialize($details));
			
			$shipsA2print[] = $print1;
		}
	}
	
	$t3 = count($shipsA2print);
				
	$_SESSION['shipsReg2'] = $shipsA2print;
	
	if($t3){
		for($i3=0; $i3<$t3; $i3++){
			$ship = $shipsA1print[$i3];
			
			$sql = "select * from `_xvas_shipdata_dry` where imo='".$ship['IMO #']."'";
			$ship_data = dbQuery($sql, $link);
			
			//CHECK SHIP IMAGE
			$imageb = base64_encode("http://dataservice.grosstonnage.com/S-Bisphoto.php?imo=".$ship['IMO #']);
			//END
			
			echo "<tr style='background:#e5e5e5;'>
				<td><div style='padding:5px;'><img src='image.php?b=1&mx=20&p=".$imageb."'> <a class='clickable' onclick='return showShipDetails(\"".$ship['IMO #']."\")' >".$ship['Ship Name']."</a></div></td>
			</tr>";
		}
	}
	
}else{
	echo "<tr>
		<td style='color:red; text-align:center;' colspan='5'>No Ships</td>
	</tr>";
}

echo "</table>";
echo "<div style='font-size:30px; height:30px'>&nbsp;</div>";
?>