<?php
if(!function_exists("microtime_float")){
	function microtime_float(){
		list($usec, $sec) = explode(" ", microtime());

		return ((float)$usec + (float)$sec);
	}
}

@session_start();

$t = count($shipsA1);

$shipsA1print = array();
$shipsA1temp = array();

$shipcount = 0;

for($i=0; $i<$t; $i++){
	$print = array();

	if(trim($shipsA1[$i]['xvas_imo'])==""){
		unset($shipsA1[$i]);

		continue;
	}
	
	$print['Ship Name']   = $shipsA1[$i]['xvas_name'];
	$print['IMO #']       = $shipsA1[$i]['xvas_imo'];
	$print['MMSI']        = $shipsA1[$i]['siitech_mmsi'];
	$print['VESSEL TYPE'] = $shipsA1[$i]['xvas_vessel_type'];
	$print['DWT']         = $shipsA1[$i]['xvas_summer_dwt'];
	$print['DWT']         = number_format($print['DWT'], 2, ".", ",");
	$print['SPEED']       = $shipsA1[$i]['xvas_speed'];

	if($print['SPEED']<=0){
		$print['SPEED'] = 13.50;
	}

	$speed = number_format($print['SPEED'], 2);
	$print['SPEED'] = $speed;
	$print['SPEED'].=" kn";
	$print['DESTINATION']  = $shipsA1[$i]['siitech_destination'];
	$DESTINATION = getPortId($print['DESTINATION'], 1);
	$print['dest_lat'] = $DESTINATION['latitude'];
	$print['dest_lng'] = $DESTINATION['longitude'];
	
	$print['dateadded']  = $shipsA1[$i]['dateadded'];
	$dtd = number_format((($shipsA1[$i]['siitech_eta_ts']-time())/60/60/24), 1);
	$print['ETA TS'] = str2time($shipsA1[$i]['siitech_eta']);
	$print['ETA TO DESTINATION (days)'] = date("M j, 'y G:i e", str2time($shipsA1[$i]['siitech_eta']))." ($dtd d)";
	$print['ETA LP TS'] = str2time($shipsA1[$i]['siitech_eta']);
	$destport = $shipsA1[$i]['destport'];
	$print['DEST PORT LAT']   = $destport['latitude'];
	$print['DEST PORT LONG']  = $destport['longitude'];	
	$print['DEST PORT NAME']  = $destport['name'];
	$print['DEST PORT ID']    = $destport['portid'];	

	//zones
	$zones = explode(",", $zone);

	if($zones[0]){
		$zt = count($zones);
		$breakit = false;

		for($zi=0; $zi<$zt; $zi++){
			if((trim($zones[$zi])=='12a'||trim($zones[$zi])=='12')&&$shipsA1[$i]['xvas_summer_dwt']>80000){
				//Disable Panama
				$prefs['disabledRegions'] = array();
				$prefs['disabledRegions'][] = 4040;
			}else if((trim($zones[$zi])=='5')&&$shipsA1[$i]['xvas_summer_dwt']>80000){
				$breakit = true;
			}else if((trim($zones[$zi])=='5a')&&$shipsA1[$i]['xvas_summer_dwt']>80000){
				//Disable Panama
				$prefs['disabledRegions'] = array();
				$prefs['disabledRegions'][] = 4040;
			}else if(trim($zones[$zi])=='9'){
				//Disable Canals
				$prefs['disabledRegions'] = array();
				$prefs['disabledRegions'][] = 128; // suez canal
			}else if(trim($zones[$zi])=='7'&&$shipsA1[$i]['xvas_summer_dwt']>100000){
				//Disable Canals
				$prefs['disabledRegions'] = array();
				$prefs['disabledRegions'][] = 4040; //panama canal
				$prefs['disabledRegions'][] = 128; // suez canal
				$prefs['disabledRegions'][] = 3557; // kiel canal
				$prefs['disabledRegions'][] = 4541; // corinth canal
				$prefs['disabledRegions'][] = 8156; // c and d canal
				$prefs['disabledRegions'][] = 8159; // capecod canal
				$prefs['disabledRegions'][] = 2932; // caledonian canal
				$prefs['disabledRegions'][] = 317; // welland canal
				$prefs['disabledRegions'][] = 5679; // gota canal
			}
		}
	}

	if($breakit){
		unset($shipsA1[$i]);

		continue;
	}	

	$distance = $dc->getDistancePortToPort($destport['portid'], $load_portid, $prefs);

	$print['LOAD_PORT'] = $load_port;
	$print['LOAD_PORT_ID'] = $load_portid;
	$print['LOAD_PORT_LAT'] = $load_portlat;
	$print['LOAD_PORT_LONG'] = $load_portlong;
	$print['SOG'] = getValue(strtolower($shipsA1[$i]['siitech_shippos_data']), "SOG");
	$print['TRUE HEADING'] = getValue(strtolower($shipsA1[$i]['siitech_shippos_data']), "trueheading");

	if(trim($print['TRUE HEADING'])){
		$print['TRUE HEADING'] .= " degrees";
	}
	
	$print['siitech_shippos_data'] = $shipsA1[$i]['siitech_shippos_data'];
	$print['siitech_shipstat_data'] = $shipsA1[$i]['siitech_shipstat_data'];
	
	$print['COG'] = getValue(strtolower($shipsA1[$i]['siitech_shippos_data']), "COG");
	$print['B2B'] = getValue(strtolower($shipsA1[$i]['siitech_shipstat_data']), "to_bow");
	$print['STERN'] = getValue(strtolower($shipsA1[$i]['siitech_shipstat_data']), "to_stern");
	$print['P2P'] = getValue(strtolower($shipsA1[$i]['siitech_shipstat_data']), "to_port");
	$print['STARBOARD'] = getValue(strtolower($shipsA1[$i]['siitech_shipstat_data']), "to_starboard");
	$print['RADIO'] = getValue(strtolower($shipsA1[$i]['siitech_shippos_data']), "radio");
	$print['MANEUVER'] = getValue(strtolower($shipsA1[$i]['siitech_shippos_data']), "maneuver");
	$print['NAVSTAT'] = getValue(strtolower($shipsA1[$i]['siitech_shippos_data']), "NavigationalStatus");
	$print['ETA'] = getValue(strtolower($shipsA1[$i]['siitech_shipstat_data']), "ETA");
	
	$print['LAST_PORT'] = $shipsA1[$i]['siitech_destination'];
	
	$print['siitech_receivetime'] = $shipsA1[$i]['siitech_receivetime'];
	
	$print['DESTINATION_ETA'] = date("M j, Y G:i e", str2time($shipsA1[$i]['siitech_eta']));
	$print['SHIP_TYPE'] = getValue(strtolower($shipsA1[$i]['siitech_shipstat_data']), "ShipType");
	$print['UTC'] = getValue(strtolower($shipsA1[$i]['siitech_shippos_data']), "UTC");

	$shipsA1[$i]['distance'] = $distance ;

	$print['LAT']   = $shipsA1[$i]['siitech_latitude'];
	$print['LONG']  = $shipsA1[$i]['siitech_longitude'];
	$print['LAST SEEN DATE'] = date("M j, Y G:i e", str2time($shipsA1[$i]['siitech_lastseen']));
	
	$sql = "SELECT * FROM `_messages` WHERE imo='".$print['IMO #']."' ORDER BY dateadded DESC LIMIT 0,1";
	$broker = dbQuery($sql, $link);
	
	$nmessage = unserialize($broker[0]['message']);
	
	$print['BROKER LOAD_PORT'] = strtoupper(trim($nmessage['dely']));
	$load_portxx = getPortId($print['BROKER LOAD_PORT'], 1);
	$print['openport_lat'] = $load_portxx['latitude'];
	$print['openport_lng'] = $load_portxx['longitude'];
	$print['BROKER ETA TO LOAD PORT (days)'] = $nmessage['delydate_from'];
	
	$sql = "SELECT * FROM `_blackbox_vessel` WHERE vessel_name='".$print['Ship Name']."' ORDER BY latest_received DESC LIMIT 0,1";
	$email = dbQuery($sql, $link);
	
	$print['EMAIL LOAD_PORT'] = $email[0]['location_name'];
	$print['location_lat'] = $email[0]['location_lat'];
	$print['location_lng'] = $email[0]['location_lng'];
	$print['EMAIL ETA TO LOAD PORT (days)'] = date('M d, Y G:i:s', strtotime($email[0]['from_time']));
	$print['to_time'] = date('M d, Y G:i:s', strtotime($email[0]['to_time']));
	$print['from_address'] = $email[0]['from_address'];
	
	if(!$distance&&strtolower($destport['name'])!=strtolower($load_port)){
		unset($shipsA1[$i]);

		continue;
	}

	if(strtolower($destport['name'])==strtolower($load_port)){
		$datetemp = date("Y-m-d 00:00:00", $shipsA1[$i]['siitech_eta_ts']);
		$etatolpts = str2time($datetemp);

		if(!($etatolpts>=$lpfts&&$etatolpts<=$lptts)){
			unset($shipsA1[$i]);

			continue;
		}else{		
			$print['ETA TO LOAD PORT (days)'] = $print['ETA TO DESTINATION (days)'];
			$print['ETA LP TS'] = $print['ETA TS'];
			$shipsA1[$i]['eta_a1'] = $print['ETA TO LOAD PORT (days)'];
		}
	}else{
		$pluseta = $distance/$speed/24;
		$etatolp = $dtd + $pluseta; //days
		$etatolpts = time() + ($etatolp*60*60*24);

		if(!($etatolpts>=$lpfts&&$etatolpts<=$lptts)){
			unset($shipsA1[$i]);

			continue;
		}

		$detatolp = number_format($etatolp, 2);
		$print['ETA LP TS'] = $etatolpts;
		$print['ETA TO LOAD PORT (days)']  = date("M j, 'y G:i e", $etatolpts)." ($detatolp d)";
		$shipsA1[$i]['eta_a1'] = $print['ETA TO LOAD PORT (days)'];
		$print['etatolpts'] = $etatolpts;
		$shipsA1[$i]['etatolpts'] = $etatolpts;
	}
	
	$print['CALC'] = "D: ".$distance." S: ".$speed." days: ".$detatolp;
	$print['DISTANCE'] = $distance;	
	$print['ALERTS'] = $shipsA1[$i]['alerts'];

	$imageb = base64_encode("http://dataservice.grosstonnage.com/S-Bisphoto.php?imo=".$print['IMO #']);
	$print['imageb'] = $imageb;
	$print['zone'] = $zone;
	$print['prefs'] = $prefs;

	$zones = explode(",", $zone);
	
	if($zones[0]){
		$zt = count($zones);
		$inzone = false;

		for($zi=0; $zi<$zt; $zi++){
			if(in_zone($print, $zones[$zi])){
				$inzone = true;
			}
		}

		if($inzone){
			$shipsA1print[] = $print;
			$shipsA1temp[] = $shipsA1[$i];
			$shipcount++;
		}else{
			unset($shipsA1[$i]);
		}	
	}else{
		$shipsA1print[] = $print;
		$shipsA1temp[] = $shipsA1[$i];

		$shipcount++;
	}

	if($shipcount>$shiplimit){
		break;
	}
}

$shipsA1 = $shipsA1temp;
$shipsA1 = array_values($shipsA1);
$shipsA1 = bbsort($shipsA1);
$shipsA1print = bbsort($shipsA1print);
?>