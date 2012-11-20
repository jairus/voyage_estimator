<script>
function openMapRegister(details){
	jQuery("#mapiframe")[0].src='map/register_map.php?details='+details+"&t="+(new Date()).getTime();
	jQuery("#mapdialog").dialog("open");
}
</script>
<?php
@session_start();
date_default_timezone_set('UTC');

include_once(dirname(__FILE__)."/includes/bootstrap.php");

$link = dbConnect();

$dc = new distanceCalc();

//FUNCTIONS
function getPortId($name, $exact=false){
	global $link;

	$namex = $name;
	$name = trim(mysql_escape_string(stripslashes($name)));

	if(!$name){ return false; }

	$sql = "SELECT '".$name."' as `given`, `name`, `portid`, `latitude`, `longitude`, if( `name` = '".$name."', 1, 0 ) as `exact`, if( `name` like '%".$name."%' , 1, 0 ) as `soundslike`
	FROM `_veson_ports`
	WHERE if( `name` = '".$name."', 1, 0 )=1 or if( `name` like '%".$name."%' , 1, 0 )=1  order by if( `name` = '".$name."', 1, 0 )=1 desc limit 1";	
	$r = dbQuery($sql, $link);
	$r = $r[0];

	$p = 0;

	similar_text($namex, $r['name'], $p);

	$r['percent'] = $p;

	if($r['exact']=='1'){
		return $r;
	}else if(!$exact){
		return $r;
	}
}

function get_zones($lat, $long){
	global $link;

	$sql = "select distinct `zone_code` from `_sbis_zoneblocks` where 
		$long>=`long1` and $long<=`long2` and 
		
		$lat<=`lat1` and
		
		$lat>=`lat4`
	 ";	 

	 $r = dbQuery($sql, $link);

	 $nr = array();

	 foreach($r as $value){ $nr[] = $value['zone_code']; }
	 
	 return $nr;
}
//END OF FUNCTIONS

if(trim($_GET['port_name'])){
	$port_name     = strtoupper(trim($_GET['port_name']));
	$port_namex    = getPortId($port_name, 1);
	$port_nameid   = $port_namex['portid'];
	$port_namelat  = $port_namex['latitude'];
	$port_namelong = $port_namex['longitude'];
	
	$zones = get_zones($port_namelat, $port_namelong);
	
	$zcount = count($zones);
	
	for($zoni=0; $zoni<$zcount; $zoni++){ $value = $zones[$zoni]; }
	
	$zones = array_values($zones);
	
	$zone = "5a";
	
	if($_GET['p_vessel_type']){
		$vtarr = count($_GET['p_vessel_type']);
	
		if($vtarr){ $sqlext .= " ( "; }
		
		for($vti=0; $vti<$vtarr; $vti++){
			$sqlext .= " `xvas_vessel_type`='".$_GET['p_vessel_type'][$vti]."' ";
	
			if(($vti+1)<$vtarr){ $sqlext .= " or "; }
		}
		
		if($vtarr){ $sqlext .= " ) "; }
		
		$sql = "SELECT * FROM `_xvas_siitech_cache` WHERE `siitech_destination`='".$port_name."' AND ".$sqlext." AND `siitech_eta` BETWEEN '".date('Y-m-d', strtotime($_GET['date_from']))."' AND '".date('Y-m-d', strtotime($_GET['date_to']))."' ORDER BY siitech_eta DESC";
		$ships = dbQuery($sql, $link);
		
		$t = count($ships);
		
		if(trim($t)){
			$shipsprint = array();
			
			for($i=0; $i<$t; $i++){
				$t3 = count($imoprint);
		
				for($i3=0;$i3<$t3;$i3++){
					if($ships[$i]['xvas_imo']==$imoprint[$i3]['imos']){
						unset($ships[$i]);
						
						continue;
					}
				}
				
				$print = array();
				$imoarr = array();
				
				$imoarr['imos']     = $ships[$i]['xvas_imo'];
				$print['id']        = $ships[$i]['id'];
				$print['Ship Name'] = $ships[$i]['xvas_name'];
				$print['IMO #']     = $ships[$i]['xvas_imo'];
				
				$imageb          = base64_encode("http://dataservice.grosstonnage.com/S-Bisphoto.php?imo=".$print['IMO #']);
				$print['imageb'] = $imageb;
				
				$print['LAT']           = $ships[$i]['siitech_latitude'];
				$print['LONG']          = $ships[$i]['siitech_longitude'];
				$print['MMSI']          = $ships[$i]['xvas_mmsi'];
				$print['VESSEL TYPE']   = $ships[$i]['xvas_vessel_type'];
				$print['DWT']           = $ships[$i]['xvas_summer_dwt'];
				$print['SPEED']         = $ships[$i]['xvas_speed'];
				$print['satellite']     = $ships[$i]['satellite'];
				$print['SIITECH_ETA']   = $ships[$i]['siitech_eta'];
				$print['DESTINATION']   = $ships[$i]['siitech_destination'];
				$print['LASTSEEN_DATE'] = $ships[$i]['siitech_lastseen'];
				
				$print['SOG'] = getValue(strtolower($ships[$i]['siitech_shippos_data']), "SOG");
				$print['TRUE HEADING'] = getValue(strtolower($ships[$i]['siitech_shippos_data']), "trueheading");
			
				if(trim($print['TRUE HEADING'])){
					$print['TRUE HEADING'] .= " degrees";
				}
				
				$print['COG'] = getValue(strtolower($ships[$i]['siitech_shippos_data']), "COG");
				$print['B2B'] = getValue(strtolower($ships[$i]['siitech_shipstat_data']), "to_bow");
				$print['STERN'] = getValue(strtolower($ships[$i]['siitech_shipstat_data']), "to_stern");
				$print['P2P'] = getValue(strtolower($ships[$i]['siitech_shipstat_data']), "to_port");
				$print['STARBOARD'] = getValue(strtolower($ships[$i]['siitech_shipstat_data']), "to_starboard");
				$print['RADIO'] = getValue(strtolower($ships[$i]['siitech_shippos_data']), "radio");
				$print['MANEUVER'] = getValue(strtolower($ships[$i]['siitech_shippos_data']), "maneuver");
				$print['NAVSTAT'] = getValue(strtolower($ships[$i]['siitech_shippos_data']), "NavigationalStatus");
				$print['ETA'] = getValue(strtolower($ships[$i]['siitech_shipstat_data']), "ETA");
				$print['SHIP_TYPE'] = getValue(strtolower($ships[$i]['siitech_shipstat_data']), "ShipType");
				$print['UTC'] = getValue(strtolower($ships[$i]['siitech_shippos_data']), "UTC");
				
				$shipsprint[] = $print;
				$imoprint[] = $imoarr;
				//END OF MAP DETAILS
			}
		}
		
		echo "<table width='100%'>
			<tr>
				<th style='background:#BCBCBC; color:#333333; text-align:left; width:26px;'><div style='padding:3px;'>&nbsp;</div></th>
				<th style='background:#BCBCBC; color:#333333; text-align:left; width:200px;'><div style='padding:3px;'>SHIP NAME</div></th>
				<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:3px;'>SHIP TYPE</div></th></th>
				<th style='background:#BCBCBC; color:#333333; text-align:left; width:100px;'><div style='padding:3px;'>DWT</div></th></th>
				<th style='background:#BCBCBC; color:#333333; text-align:left; width:60px;'><div style='padding:3px;'>SPEED</div></th></th>
				<th style='background:#BCBCBC; color:#333333; text-align:left; width:124px;'><div style='padding:3px;'>COMING TO</div></th></th>
				<th style='background:#BCBCBC; color:#333333; text-align:left; width:120px;'><div style='padding:3px;'>ETA</div></th></th>
				<th style='background:#BCBCBC; color:#333333; text-align:left; width:120px;'><div style='padding:3px;'>LAST SEEN</div></th></th>
			</tr>";
			//<th style='background:#BCBCBC; color:#333333; text-align:left; width:150px;'><div style='padding:3px;'>SHIP TYPE</div></th></th>
			
			$t2 = count($shipsprint);
						
			$_SESSION['shipsReg'] = $shipsprint;
			
			if($t2){
				for($i2=0; $i2<$t2; $i2++){
					$ship = $shipsprint[$i2];
					
					if(trim($ship['Ship Name'])){
						//MAP DETAILS
						$details       = array();
						$details['a']  = 'shipsReg';
						$details['id'] = $i2;
						$details       = base64_encode(serialize($details));
						//END
						
						echo "<tr style='background:#e5e5e5;'>
							<td><div style='padding:3px;'><a class='clickable' onclick='openMapRegister(\"".$details."\")'><img title='Map' alt='Map' src='images/map-icon.png'></a></div></td>
							<td><div style='padding:3px;'><img src='image.php?b=1&mx=20&p=".$ship['imageb']."'> <a class='clickable' onclick='return showShipDetails(\"".$ship['IMO #']."\")' >".$ship['Ship Name']."</a></div></td>
							<td><div style='padding:3px;'>".$ship['VESSEL TYPE']."</div></td>
							<td><div style='padding:3px;'>".$ship['DWT']."</div></td>
							<td><div style='padding:3px;'>".$ship['SPEED']."</div></td>
							<td><div style='padding:3px;'>".$ship['DESTINATION']."</div></td>
							<td><div style='padding:3px;'>".date('M d, y / G:i:s', strtotime($ship['SIITECH_ETA']))."</div></td>
							<td><div style='padding:3px;'>".date('M d, y / G:i:s', strtotime($ship['LASTSEEN_DATE']))."</div></td>
						</tr>";
					}
				}
			}else{
				echo "<tr>
					<td style='color:red; text-align:center;' colspan='8'>No Ships</td>
				</tr>";
			}
			
		echo "</table>";
	}else{
		echo "<table width='100%'>
			<tr>
				<td style='color:red; text-align:center;'>Please Select A Ship Type</td>
			</tr>
		</table>";
	}
}else{
	echo "<table width='100%'>
		<tr>
			<td style='color:red; text-align:center;'>Please Select A Port</td>
		</tr>
	</table>";
}
?>