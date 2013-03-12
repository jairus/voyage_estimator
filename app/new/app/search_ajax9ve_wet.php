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

$sql = "SELECT imo, name FROM `_xvas_parsed2` WHERE name='".mysql_escape_string($ship_name)."' AND imo='".mysql_escape_string($ship_imo)."' ORDER BY dateupdated DESC LIMIT 0,1";
$ships = dbQuery($sql, $link);

$t = count($ships);
//<td><div style='padding:5px;'><img src='image.php?b=1&mx=20&p=".$imageb."'> <a class='clickable' onclick='return showShipDetails(\"".$ship['xvas_imo']."\")' >".$ship['Ship Name']."</a></div></td>
echo "<table id='pblues' width='1300'>
	<tr>
		<th style='background:#BCBCBC; color:#333333; text-align:left; width:200px;'><div style='padding:5px;'>ETA</div></th>
		<th style='background:#BCBCBC; color:#333333; text-align:left; width:200px;'><div style='padding:5px;'>PORT NAME</div></th>
		<th style='background:#BCBCBC; color:#333333; text-align:left; width:100px;'><div style='padding:5px;'>POSITION</div></th>
		<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>MAP</div></th>
	</tr>";

if(trim($t)){
	$shipsA1print = array();
	
	//CHECK IF SHIP EXIST IN DATABASE
	$sql = "SELECT * FROM `_xvas_shipdata` WHERE imo='".$ships[0]['imo']."'";
	$ship_exist = dbQuery($sql, $link);
	$ship_exist = $ship_exist[0];
	//END OF CHECK IF SHIP EXIST IN DATABASE
	
	if(trim($ship_exist['data'])){
		$status = getValue($ship_exist['data'], 'STATUS');
		
		if(trim($status)!="DEAD"){
			//CHECK IF SHIP EXIST IN SIITECH CACHE
			$sql = "SELECT * FROM `_ship_history` WHERE xvas_imo='".$ship_exist['imo']."' AND `siitech_eta` BETWEEN '".$destination_port_from."' AND '".$destination_port_to."' ORDER BY siitech_eta DESC";
			$siitech_ships = dbQuery($sql, $link);
			
			$t1 = count($siitech_ships);
			//END
			
			if(trim($t1)){
				for($i1=0; $i1<$t1; $i1++){
					//MAP DETAILS
					$print = array();
					
					$print['id']        = $siitech_ships[$i1]['id'];
					$print['xvas_name'] = $ships[$i]['xvas_name'];
					$print['xvas_imo']  = $siitech_ships[$i1]['xvas_imo'];
					
					$imageb = base64_encode("http://dataservice.grosstonnage.com/S-Bisphoto.php?imo=".$print['xvas_imo']);
					$print['imageb'] = $imageb;
					
					$print['siitech_latitude'] = $siitech_ships[$i1]['siitech_latitude'];
					$print['siitech_longitude'] = $siitech_ships[$i1]['siitech_longitude'];
					$print['xvas_mmsi'] = $siitech_ships[$i1]['xvas_mmsi'];
					$print['xvas_vessel_type'] = $siitech_ships[$i1]['xvas_vessel_type'];
					$print['xvas_summer_dwt'] = $siitech_ships[$i1]['xvas_summer_dwt'];
					$print['xvas_speed'] = $siitech_ships[$i1]['xvas_speed'];
					$print['siitech_eta'] = $siitech_ships[$i1]['siitech_eta'];
					$print['siitech_destination'] = $siitech_ships[$i1]['siitech_destination'];
					$print['siitech_lastseen'] = $siitech_ships[$i1]['siitech_lastseen'];
					
					$print['sog'] = getValue(strtolower($siitech_ships[$i1]['siitech_shippos_data']), "SOG");
					$print['true_heading'] = getValue(strtolower($siitech_ships[$i1]['siitech_shippos_data']), "trueheading");
				
					if(trim($print['true_heading'])){
						$print['true_heading'] .= " degrees";
					}
					
					$print['cog'] = getValue(strtolower($siitech_ships[$i1]['siitech_shippos_data']), "COG");
					$print['b2b'] = getValue(strtolower($siitech_ships[$i1]['siitech_shipstat_data']), "to_bow");
					$print['stern'] = getValue(strtolower($siitech_ships[$i1]['siitech_shipstat_data']), "to_stern");
					$print['p2p'] = getValue(strtolower($siitech_ships[$i1]['siitech_shipstat_data']), "to_port");
					$print['starboard'] = getValue(strtolower($siitech_ships[$i1]['siitech_shipstat_data']), "to_starboard");
					$print['radio'] = getValue(strtolower($siitech_ships[$i1]['siitech_shippos_data']), "radio");
					$print['maneuver'] = getValue(strtolower($siitech_ships[$i1]['siitech_shippos_data']), "maneuver");
					$print['navstat'] = getValue(strtolower($siitech_ships[$i1]['siitech_shippos_data']), "NavigationalStatus");
					$print['eta'] = getValue(strtolower($siitech_ships[$i1]['siitech_shipstat_data']), "ETA");
					$print['ship_type'] = getValue(strtolower($siitech_ships[$i1]['siitech_shipstat_data']), "ShipType");
					$print['utc'] = getValue(strtolower($siitech_ships[$i1]['siitech_shippos_data']), "UTC");
					
					$shipsA1print[] = $print;
					//END OF MAP DETAILS
				}
			}
		}
	}
			
	$_SESSION['shipsReg'] = $shipsA1print;
	
	$t2 = count($shipsA1print);
	
	if($t2){
		for($i2=0; $i2<$t2; $i2++){
			$ship = $shipsA1print[$i2];
			
			echo "<tr style='background:#e5e5e5;'>
				<td><div style='padding:5px;'>".date("M j, 'y G:i e", str2time($ship['siitech_eta']))."</div></td>
				<td><div style='padding:5px;'>".$ship['siitech_destination']."</div></td>
				<td><div style='padding:5px;'><a onclick='showMapSHSingle(\"".$ship['id']."\");' class='clickable'>view position</a></div></td>";
				
				if($i2==0){
					echo "<td rowspan='".$t2."' align='center' valign='top'>
						<div style='padding:5px;'><a onclick='showMapSH();' class='clickable'>view larger map</a></div>
						<div style='padding:5px;'><iframe src='map/map_ship_his_all.php' width='750' height='700' frameborder='0'></iframe></div>
					</td>";
				}
				
			echo "</tr>";
		}
	}else{
		echo "<tr>
			<td colspan='4' style='color:red; text-align:center;' colspan='5'>No Ships</td>
		</tr>";
	}
}else{
	echo "<tr>
		<td colspan='4' style='color:red; text-align:center;' colspan='5'>No Ships</td>
	</tr>";
}

echo "</table>";
echo "<div style='font-size:30px; height:30px'>&nbsp;</div>";
?>