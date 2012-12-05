<?php
$t = count($shipsA5);

$shipsA5print = array();
$shipsA5temp  = array();

$shipcount = 0;

for($i=0; $i<$t; $i++){
	$print = array();

	if(trim($shipsA5[$i]['imo'])==""){
		unset($shipsA5[$i]);

		continue;
	}
	
	$print['Ship Name']   = $shipsA5[$i]['name'];
	$print['IMO #']       = $shipsA5[$i]['imo'];
	$print['MMSI']        = $shipsA5[$i]['mmsi'];
	$print['VESSEL TYPE'] = $shipsA5[$i]['vessel_type'];

	$print['DWT'] = $shipsA5[$i]['summer_dwt'];
	$print['DWT'] = number_format($print['DWT'], 2, ".", ",");

	$print['SPEED'] = $shipsA5[$i]['speed'];
	if($print['SPEED']<=0){
		$print['SPEED'] = 13.50;
	}
	$speed = number_format($print['SPEED'], 2);
	$print['SPEED'] = $speed;
	$print['SPEED'].=" kn";

	$print['dateadded']  = $shipsA5[$i]['dateadded'];

	$print['BROKER ETA LP TS'] = $shipsA5[$i]['nmessage']['opendate_ts'];
	
	$destport  = $shipsA5[$i]['destport'];
	$destport2 = $shipsA5[$i]['destport2'];

	$print['DEST PORT LAT']  = $destport['latitude'];
	$print['DEST PORT LONG'] = $destport['longitude'];	
	$print['DEST PORT NAME'] = $destport['name'];
	$print['DEST PORT ID']   = $destport['portid'];	

	$print['BROKER DEST PORT LAT']  = $destport2['latitude'];
	$print['BROKER DEST PORT LONG'] = $destport2['longitude'];	
	$print['BROKER DEST PORT NAME'] = $destport2['name'];
	$print['BROKER DEST PORT ID']   = $destport2['portid'];	
	
	$print['LAT']  = $shipsA4[$i]['siitech_latitude'];
	$print['LONG'] = $shipsA4[$i]['siitech_longitude'];

	$zones = explode(",", $zone);

	if($zones[0]){
		$zt = count($zones);
		$breakit = false;

		for($zi=0; $zi<$zt; $zi++){
			if((trim($zones[$zi])=='12a'||trim($zones[$zi])=='12')&&$shipsA5[$i]['summer_dwt']>80000){
				//Disable Panama
				$prefs['disabledRegions'] = array();
				$prefs['disabledRegions'][] = 4040;
			}else if((trim($zones[$zi])=='5')&&$shipsA5[$i]['summer_dwt']>80000){
				$breakit = true;
			}else if((trim($zones[$zi])=='5a')&&$shipsA5[$i]['summer_dwt']>80000){
				//Disable Panama
				$prefs['disabledRegions'] = array();
				$prefs['disabledRegions'][] = 4040;
			}else if(trim($zones[$zi])=='9'){
				//Disable Canals
				$prefs['disabledRegions'] = array();
				$prefs['disabledRegions'][] = 128; // suez canal
			}else if(trim($zones[$zi])=='7'&&$shipsA5[$i]['summer_dwt']>100000){
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
		unset($shipsA5[$i]);

		continue;
	}	

	$distance = $dc->getDistancePortToPort($destport2['portid'], $load_portid, $prefs);

	$print['LOAD_PORT']      = $load_port;
	$print['LOAD_PORT_ID']   = $load_portid;
	$print['LOAD_PORT_LAT']  = $load_portlat;
	$print['LOAD_PORT_LONG'] = $load_portlong;
	
	$print['SOG'] = getValue(strtolower($shipsA5[$i]['siitech_shippos_data']), "SOG");
	
	$print['TRUE HEADING'] = getValue(strtolower($shipsA5[$i]['siitech_shippos_data']), "trueheading");
	if(trim($print['TRUE HEADING'])){
		$print['TRUE HEADING'] .= " degrees";
	}
	
	$print['siitech_shippos_data'] = $shipsA5[$i]['siitech_shippos_data'];
	$print['siitech_shipstat_data'] = $shipsA5[$i]['siitech_shipstat_data'];
	
	$print['COG']       = getValue(strtolower($shipsA5[$i]['siitech_shippos_data']), "COG");
	$print['B2B']       = getValue(strtolower($shipsA5[$i]['siitech_shipstat_data']), "to_bow");
	$print['STERN']     = getValue(strtolower($shipsA5[$i]['siitech_shipstat_data']), "to_stern");
	$print['P2P']       = getValue(strtolower($shipsA5[$i]['siitech_shipstat_data']), "to_port");
	$print['STARBOARD'] = getValue(strtolower($shipsA5[$i]['siitech_shipstat_data']), "to_starboard");
	$print['RADIO']     = getValue(strtolower($shipsA5[$i]['siitech_shippos_data']), "radio");
	$print['MANEUVER']  = getValue(strtolower($shipsA5[$i]['siitech_shippos_data']), "maneuver");
	$print['NAVSTAT']   = getValue(strtolower($shipsA5[$i]['siitech_shippos_data']), "NavigationalStatus");
	$print['ETA']       = getValue(strtolower($shipsA5[$i]['siitech_shipstat_data']), "ETA");
	$print['LAST_PORT'] = $shipsA5[$i]['siitech_destination'];
	
	$print['siitech_receivetime'] = $shipsA5[$i]['siitech_receivetime'];
	
	$print['DESTINATION_ETA'] = date("M j, Y G:i e", str2time($shipsA5[$i]['siitech_eta']));
	$print['SHIP_TYPE']       = getValue(strtolower($shipsA5[$i]['siitech_shipstat_data']), "ShipType");
	$print['UTC']             = getValue(strtolower($shipsA5[$i]['siitech_shippos_data']), "UTC");

	$shipsA5[$i]['distance'] = $distance ;

	if(!$distance&&strtolower($destport2['name'])!=strtolower($load_port)){
		unset($shipsA5[$i]);

		continue;
	}	

	if(strtolower($destport2['name'])==strtolower($load_port)){
		$etatolpts = $shipsA5[$i]['nmessage']['opendate_ts'];

		if(!($etatolpts>=$lpfts&&$etatolpts<=$lptts)){
			unset($shipsA5[$i]);

			continue;
		}else{
			$dtd = number_format((($shipsA5[$i]['nmessage']['opendate_ts']-time())/60/60/24), 1);

			$print['BROKER ETA TO LOAD PORT (days)'] = date("M j, 'y G:i e", $shipsA5[$i]['nmessage']['opendate_ts'])." ($dtd d)";
			$print['BROKER ETA LP TS'] = $shipsA5[$i]['nmessage']['opendate_ts'];

			$shipsA5[$i]['eta_A5'] = $print['ETA TO LOAD PORT (days)'];
		}
	}else{	
		$dtd = number_format((($shipsA5[$i]['nmessage']['opendate_ts']-time())/60/60/24), 1);
		$pluseta = $distance/$speed/24;
		$etatolp = $dtd + $pluseta; //days
		$etatolpts = time() + ($etatolp*60*60*24);

		if(!($etatolpts>=$lpfts&&$etatolpts<=$lptts)){
			unset($shipsA5[$i]);

			continue;
		}

		$detatolp = number_format($etatolp, 2);

		$print['BROKER ETA LP TS'] = $etatolpts;
		$print['BROKER ETA TO LOAD PORT (days)'] = date("M j, 'y G:i e", $etatolpts)." ($detatolp d)";

		$shipsA5[$i]['eta_A5'] = $print['BROKER ETA TO LOAD PORT (days)'];

		$print['etatolpts'] = $etatolpts;

		$shipsA5[$i]['etatolpts'] = $etatolpts;
	}

	$print['CALC'] = "D: ".$distance." S: ".$speed." days: ".$detatolp;
	$print['DISTANCE'] = $distance;	
	$print['ALERTS'] = $shipsA5[$i]['alerts'];
	
	$imageb = base64_encode("http://dataservice.grosstonnage.com/S-Bisphoto.php?imo=".$print['IMO #']);
	$print['imageb'] = $imageb;

	$print['zone']  = $zone;
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
			$shipsA5print[] = $print;
			$shipsA5temp[]  = $shipsA5[$i];

			$shipcount++;
		}else{
			unset($shipsA5[$i]);
		}	
	}else{
		$shipsA5print[] = $print;
		$shipsA5temp[]  = $shipsA5[$i];

		$shipcount++;
	}

	if($shipcount>$shiplimit){
		break;
	}
}

$shipsA5 = $shipsA5temp;
$shipsA5 = array_values($shipsA5);
$shipsA5 = bbsort($shipsA5);

$shipsA5print = bbsort($shipsA5print);
?>