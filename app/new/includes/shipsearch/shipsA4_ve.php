<?php
$t = count($shipsA4);

$shipsA4print = array();
$shipsA4temp  = array();

$shipcount = 0;

for($i=0; $i<$t; $i++){
	$print = array();
	
	if(trim($shipsA4[$i]['xvas_imo'])==""){
		unset($shipsA4[$i]);

		continue;
	}

	$print['Ship Name']   = $shipsA4[$i]['xvas_name'];
	$print['IMO #']       = $shipsA4[$i]['xvas_imo'];
	$print['MMSI']        = $shipsA4[$i]['siitech_mmsi'];
	$print['VESSEL TYPE'] = $shipsA4[$i]['xvas_vessel_type'];
	
	$print['DWT'] = $shipsA4[$i]['xvas_summer_dwt'];
	$print['DWT'] = number_format($print['DWT'], 2, ".", ",");

	$print['SPEED'] = $shipsA4[$i]['xvas_speed'];
	if($print['SPEED']<=0){
		$print['SPEED'] = "13.50";
	}
	$speed = $print['SPEED'];
	$print['SPEED'] = number_format($print['SPEED'], 2);
	$print['SPEED'] .= " kn";

	$destport = $shipsA4[$i]['destport'];

	$print['DEST PORT LAT2']  = $destport['latitude'];
	$print['DEST PORT LONG2'] = $destport['longitude'];
	$print['DEST PORT NAME']  = $destport['name'];
	$print['DEST PORT ID']    = $destport['portid'];	

	$print['DESTINATION']  = $shipsA4[$i]['siitech_destination'];

	$print['dateadded']  = $shipsA4[$i]['dateadded'];

	$dtd = number_format((($shipsA4[$i]['siitech_eta_ts']-time())/60/60/24), 1);
	$print['ETA TS'] =  $shipsA4[$i]['siitech_eta_ts'];
	$print['ETA TO DESTINATION (days)']  = date("M j, 'y G:i e", $shipsA4[$i]['siitech_eta_ts'])." ($dtd d)";
	$print['BROKER ETA LP TS'] = $shipsA4[$i]['nmessage']['opendate_ts'];

	$destport  = $shipsA4[$i]['destport'];
	$destport2 = $shipsA4[$i]['destport2'];

	$print['BROKER DEST PORT LAT']  = $destport2['latitude'];
	$print['BROKER DEST PORT LONG'] = $destport2['longitude'];	
	$print['BROKER DEST PORT NAME'] = $destport2['name'];
	$print['BROKER DEST PORT ID']   = $destport2['portid'];	

	$print['LAT']  = $shipsA4[$i]['siitech_latitude'];
	$print['LONG'] = $shipsA4[$i]['siitech_longitude'];

	$print['LAST SEEN DATE'] = date("M j, Y G:i e", str2time($shipsA4[$i]['siitech_lastseen']));

	$zones = explode(",", $zone);

	$breakit = false;

	if($zones[0]){
		$zt = count($zones);

		for($zi=0; $zi<$zt; $zi++){
			if((trim($zones[$zi])=='12a'||trim($zones[$zi])=='12')&&$shipsA4[$i]['xvas_summer_dwt']>80000){
				//Disable Panama
				$prefs['disabledRegions'] = array();
				$prefs['disabledRegions'][] = 4040;
			}else if((trim($zones[$zi])=='5'||trim($zones[$zi])=='5')&&$shipsA4[$i]['xvas_summer_dwt']>80000){
				$breakit = true;
			}else if((trim($zones[$zi])=='5a')&&$shipsA4[$i]['xvas_summer_dwt']>80000){
				//Disable Panama
				$prefs['disabledRegions'] = array();
				$prefs['disabledRegions'][] = 4040;
			}else if(trim($zones[$zi])=='9'){
				//Disable Canals
				$prefs['disabledRegions'] = array();
				$prefs['disabledRegions'][] = 128; // suez canal
			}else if(trim($zones[$zi])=='7'&&$shipsA4[$i]['xvas_summer_dwt']>100000){
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
		unset($shipsA4[$i]);

		continue;
	}

	$distance = $dc->getDistancePortToPort($destport2['portid'], $load_portid, $prefs, 0);

	$dtd       = 0; //because it has no destination
	$pluseta   = $distance/$speed/24;
	$etatolp   = $dtd + $pluseta; //days
	$etatolpts = time() + ($etatolp*60*60*24);

	if(!($etatolpts>=$lpfts&&$etatolpts<=$lptts)){
		unset($shipsA4[$i]);

		continue;
	}

	$detatolp = number_format($etatolp, 2);
	$print['ETS LP TS'] = $etatolpts;
	$print['ETA TO LOAD PORT (days)']  = date("M j, 'y G:i e", $etatolpts)." ($detatolp d)";
	$print['etatolpts'] = $etatolpts;
	$shipsA4[$i]['eta_a2'] = $print['ETA TO LOAD PORT (days)'];
	$shipsA4[$i]['etatolpts'] = $etatolpts;

	$print['ALERTS'] = $shipsA4[$i]['alerts'];

	$print['LOAD_PORT']      = $load_port;
	$print['LOAD_PORT_ID']   = $load_portid;
	$print['LOAD_PORT_LAT']  = $load_portlat;
	$print['LOAD_PORT_LONG'] = $load_portlong;

	$print['SOG'] = getValue(strtolower($shipsA4[$i]['siitech_shippos_data']), "SOG");
	
	$print['TRUE HEADING'] = getValue(strtolower($shipsA4[$i]['siitech_shippos_data']), "trueheading");
	if(trim($print['TRUE HEADING'])){
		$print['TRUE HEADING'] .= " degrees";
	}
	
	$print['siitech_shippos_data'] = $shipsA4[$i]['siitech_shippos_data'];
	$print['siitech_shipstat_data'] = $shipsA4[$i]['siitech_shipstat_data'];
	
	$print['COG']       = getValue(strtolower($shipsA4[$i]['siitech_shippos_data']), "COG");
	$print['B2B']       = getValue(strtolower($shipsA4[$i]['siitech_shipstat_data']), "to_bow");
	$print['STERN']     = getValue(strtolower($shipsA4[$i]['siitech_shipstat_data']), "to_stern");
	$print['P2P']       = getValue(strtolower($shipsA4[$i]['siitech_shipstat_data']), "to_port");
	$print['STARBOARD'] = getValue(strtolower($shipsA4[$i]['siitech_shipstat_data']), "to_starboard");
	$print['RADIO']     = getValue(strtolower($shipsA4[$i]['siitech_shippos_data']), "radio");
	$print['MANEUVER']  = getValue(strtolower($shipsA4[$i]['siitech_shippos_data']), "maneuver");
	$print['NAVSTAT']   = getValue(strtolower($shipsA4[$i]['siitech_shippos_data']), "NavigationalStatus");
	$print['ETA']       = getValue(strtolower($shipsA4[$i]['siitech_shipstat_data']), "ETA");
	$print['LAST_PORT'] = $shipsA4[$i]['siitech_destination'];
	
	$print['siitech_receivetime'] = $shipsA4[$i]['siitech_receivetime'];
	
	$print['DESTINATION_ETA'] = date("M j, Y G:i e", str2time($shipsA4[$i]['siitech_eta']));
	$print['SHIP_TYPE']       = getValue(strtolower($shipsA4[$i]['siitech_shipstat_data']), "ShipType");
	$print['UTC']             = getValue(strtolower($shipsA4[$i]['siitech_shippos_data']), "UTC");

	if(!$distance&&strtolower($destport2['name'])!=strtolower($load_port)){
		unset($shipsA4[$i]);

		continue;
	}	

	if(strtolower($destport2['name'])==strtolower($load_port)){
		$etatolpts = $shipsA4[$i]['nmessage']['opendate_ts'];

		if(!($etatolpts>=$lpfts&&$etatolpts<=$lptts)){
			unset($shipsA4[$i]);

			continue;
		}else{
			$dtd = number_format((($shipsA4[$i]['nmessage']['opendate_ts']-time())/60/60/24), 1);
			$print['BROKER ETA TO LOAD PORT (days)'] = date("M j, 'y G:i e", $shipsA4[$i]['nmessage']['opendate_ts'])." ($dtd d)";
			$print['BROKER ETA LP TS'] = $shipsA4[$i]['nmessage']['opendate_ts'];
			$shipsA4[$i]['eta_a4'] = $print['ETA TO LOAD PORT (days)'];
		}
	}else{
		$dtd = number_format((($shipsA4[$i]['nmessage']['opendate_ts']-time())/60/60/24), 1);
		$pluseta = $distance/$speed/24;
		$etatolp = $dtd + $pluseta; //days
		$etatolpts = time() + ($etatolp*60*60*24);

		if(!($etatolpts>=$lpfts&&$etatolpts<=$lptts)){
			unset($shipsA4[$i]);

			continue;
		}

		$detatolp = number_format($etatolp, 2);
		$print['BROKER ETA LP TS'] = $etatolpts;
		$print['BROKER ETA TO LOAD PORT (days)']  = date("M j, 'y G:i e", $etatolpts)." ($detatolp d)";
		$shipsA4[$i]['eta_A4'] = $print['BROKER ETA TO LOAD PORT (days)'];
		$print['etatolpts'] = $etatolpts;
		$shipsA4[$i]['etatolpts'] = $etatolpts;		
	}

	$print['CALC'] = "D: ".$distance." S: ".$speed." days: ".$detatolp;
	$print['DISTANCE'] = $distance;

	$shipsA4[$i]['distance'] = $distance ;

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
			$shipsA4print[] = $print;
			$shipsA4temp[]  = $shipsA4[$i];
			
			$shipcount++;
		}else{
			unset($shipsA4[$i]);
		}	
	}else{
		$shipsA4print[] = $print;
		$shipsA4temp[]  = $shipsA4[$i];

		$shipcount++;
	}

	if($shipcount>$shiplimit){
		break;
	}
}

$shipsA4 = $shipsA4temp;
$shipsA4 = array_values($shipsA4);
$shipsA4 = bbsort($shipsA4);

$shipsA4print = bbsort($shipsA4print);
?>