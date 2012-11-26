<?php
$t = count($shipsA2);

$shipsA2temp = array();

$shipcount = 0;

$shipsA2print = array();

for($i=0; $i<$t; $i++){
	$t2 = count($imoprint2);
	
	for($i2=0;$i2<$t2;$i2++){
		if($shipsA2[$i]['xvas_imo']==$imoprint2[$i2]['imos']){
			unset($shipsA2[$i]);
			
			continue;
		}
	}

	if($shipsA2[$i]['satellite']){

		unset($shipsA2[$i]);

		continue;

	}

	if(trim($shipsA2[$i]['xvas_imo'])==""){

		unset($shipsA2[$i]);

		continue;

	}

	$print = array();
	$imoarr = array();
	
	$imoarr['imos']   = $shipsA2[$i]['xvas_imo'];
	$print['Ship Name']  = $shipsA2[$i]['xvas_name'];

	$print['IMO #']   = $shipsA2[$i]['xvas_imo'];

	$print['MMSI']  = $shipsA2[$i]['siitech_mmsi'];

	//$print['CALLSIGN']  = $shipsA2[$i]['xvas_callsign'];	

	$print['VESSEL TYPE']  = $shipsA2[$i]['xvas_vessel_type'];

	$print['DWT']  = $shipsA2[$i]['xvas_summer_dwt'];

	$print['DWT'] = number_format($print['DWT'], 2, ".", ",");

	$print['SPEED'] = $shipsA2[$i]['xvas_speed'];

	if($print['SPEED']<=0){

		//$shipsA2[$i]['alerts'][] = "Speed is defaulted to 13.50 knots";

		$print['SPEED'] = "13.50";

	}

	$speed = $print['SPEED'];

	$print['SPEED'] = number_format($print['SPEED'], 2);

	$print['SPEED'].=" kn";





	$destport = $shipsA2[$i]['destport'];

	$print['DEST PORT LAT2']   = $destport['latitude'];

	$print['DEST PORT LONG2']  = $destport['longitude'];	

	$print['DEST PORT NAME']  = $destport['name'];

	$print['DEST PORT ID']    = $destport['portid'];	

	

	$print['DESTINATION']  = $shipsA2[$i]['siitech_destination'];
	$DESTINATION = getPortId($print['DESTINATION'], 1);
	$print['dest_lat'] = $DESTINATION['latitude'];
	$print['dest_lng'] = $DESTINATION['longitude'];
	

	$print['dateadded']  = $shipsA2[$i]['dateadded'];

	$dtd = number_format((($shipsA2[$i]['siitech_eta_ts']-time())/60/60/24), 1);

	$print['ETA TS'] =  $shipsA2[$i]['siitech_eta_ts'];

	$print['ETA TO DESTINATION (days)']  = date("M j, 'y G:i e", $shipsA2[$i]['siitech_eta_ts'])." ($dtd d)";

	



	$print['LAT']   = $shipsA2[$i]['siitech_latitude'];
	$print['LONG']  = $shipsA2[$i]['siitech_longitude'];
	$print['LAST SEEN DATE'] = date("M j, Y G:i e", str2time($shipsA2[$i]['siitech_lastseen']));
	
	$sql = "SELECT * FROM `_messages` WHERE imo='".$print['IMO #']."' ORDER BY dateadded DESC LIMIT 0,1";
	$broker = dbQuery($sql, $link);
	
	$nmessage = unserialize($broker[0]['message']);
	
	$print['BROKER LOAD_PORT'] = strtoupper(trim($nmessage['openport']));
	$load_portxx = getPortId($print['BROKER LOAD_PORT'], 1);
	$print['openport_lat'] = $load_portxx['latitude'];
	$print['openport_lng'] = $load_portxx['longitude'];
	$print['BROKER ETA TO LOAD PORT (days)'] = $nmessage['opendate'];
	
	$sql = "SELECT * FROM `_blackbox_vessel` WHERE vessel_name='".$print['Ship Name']."' ORDER BY latest_received DESC LIMIT 0,1";
	$email = dbQuery($sql, $link);
	
	$print['EMAIL LOAD_PORT'] = $email[0]['location_name'];
	$print['location_lat'] = $email[0]['location_lat'];
	$print['location_lng'] = $email[0]['location_lng'];
	$print['EMAIL ETA TO LOAD PORT (days)'] = date('M d, Y G:i:s', strtotime($email[0]['from_time']));
	$print['to_time'] = date('M d, Y G:i:s', strtotime($email[0]['to_time']));
	$print['from_address'] = $email[0]['from_address'];

	//zones

	$zones = explode(",", $zone);

	$breakit = false;

	if($zones[0]){

		$zt = count($zones);

		for($zi=0; $zi<$zt; $zi++){

			if((trim($zones[$zi])=='12a'||trim($zones[$zi])=='12')&&$shipsA2[$i]['xvas_summer_dwt']>80000){

				//Disable Panama

				$prefs['disabledRegions'] = array();

				$prefs['disabledRegions'][] = 4040;

			}

			else if((trim($zones[$zi])=='5'||trim($zones[$zi])=='5')&&$shipsA2[$i]['xvas_summer_dwt']>80000){

				$breakit = true;

			}

			else if((trim($zones[$zi])=='5a')&&$shipsA2[$i]['xvas_summer_dwt']>80000){

				//Disable Panama

				$prefs['disabledRegions'] = array();

				$prefs['disabledRegions'][] = 4040;

			}

			else if(trim($zones[$zi])=='9'){

				//Disable Canals

				$prefs['disabledRegions'] = array();

				$prefs['disabledRegions'][] = 128; // suez canal

			}				

			else if(trim($zones[$zi])=='7'&&$shipsA2[$i]['xvas_summer_dwt']>100000){

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

		unset($shipsA2[$i]);

		continue;

	}

	

	//$d_time_start = microtime_float();

	

	$distance = $dc->getDistancePointToPort($print['LAT'], $print['LONG'], $load_portid, $prefs);

	

	//$distance_calc_time = microtime_float() - $d_time_start;	

	//$distance_calc_time_total = microtime_float() - $a1_time_start;	

	//echo "<font color='red'>".$print['Ship Name']." distance calc time ".$distance_calc_time." / ".$distance_calc_time_total."</font><br>";

	

	

	



	$dtd = 0; //because it has no destination

	$pluseta = $distance/$speed/24;

	$etatolp = $dtd + $pluseta; //days

	$etatolpts = time() + ($etatolp*60*60*24);

	

	if(!($etatolpts>=$lpfts&&$etatolpts<=$lptts)){

		unset($shipsA2[$i]);

		continue;

	}

	

		

	$detatolp = number_format($etatolp, 2);

	$print['ETS LP TS'] = $etatolpts;

	$print['ETA TO LOAD PORT (days)']  = date("M j, 'y G:i e", $etatolpts)." ($detatolp d)";

	$print['etatolpts'] = $etatolpts;

	$shipsA2[$i]['eta_a2'] = $print['ETA TO LOAD PORT (days)'];

	$shipsA2[$i]['etatolpts'] = $etatolpts;

	

	$print['ALERTS'] = $shipsA2[$i]['alerts'];



	$print['LOAD_PORT'] = $load_port;

	$print['LOAD_PORT_ID'] = $load_portid;

	$print['LOAD_PORT_LAT'] = $load_portlat;

	$print['LOAD_PORT_LONG'] = $load_portlong;

	$print['SOG'] = getValue(strtolower($shipsA2[$i]['siitech_shippos_data']), "SOG");
	$print['TRUE HEADING'] = getValue(strtolower($shipsA2[$i]['siitech_shippos_data']), "trueheading");

	if(trim($print['TRUE HEADING'])){
		$print['TRUE HEADING'] .= " degrees";
	}
	
	$print['siitech_shippos_data'] = $shipsA2[$i]['siitech_shippos_data'];
	$print['siitech_shipstat_data'] = $shipsA2[$i]['siitech_shipstat_data'];
	
	$print['COG'] = getValue(strtolower($shipsA2[$i]['siitech_shippos_data']), "COG");
	$print['B2B'] = getValue(strtolower($shipsA2[$i]['siitech_shipstat_data']), "to_bow");
	$print['STERN'] = getValue(strtolower($shipsA2[$i]['siitech_shipstat_data']), "to_stern");
	$print['P2P'] = getValue(strtolower($shipsA2[$i]['siitech_shipstat_data']), "to_port");
	$print['STARBOARD'] = getValue(strtolower($shipsA2[$i]['siitech_shipstat_data']), "to_starboard");
	$print['RADIO'] = getValue(strtolower($shipsA2[$i]['siitech_shippos_data']), "radio");
	$print['MANEUVER'] = getValue(strtolower($shipsA2[$i]['siitech_shippos_data']), "maneuver");
	$print['NAVSTAT'] = getValue(strtolower($shipsA2[$i]['siitech_shippos_data']), "NavigationalStatus");
	$print['ETA'] = getValue(strtolower($shipsA2[$i]['siitech_shipstat_data']), "ETA");
	
	$print['LAST_PORT'] = $shipsA2[$i]['siitech_destination'];
	
	$print['siitech_receivetime'] = $shipsA2[$i]['siitech_receivetime'];
	
	$print['DESTINATION_ETA'] = date("M j, Y G:i e", str2time($shipsA2[$i]['siitech_eta']));
	$print['SHIP_TYPE'] = getValue(strtolower($shipsA2[$i]['siitech_shipstat_data']), "ShipType");
	$print['UTC'] = getValue(strtolower($shipsA2[$i]['siitech_shippos_data']), "UTC");

	

	$print['CALC'] = "D: ".$distance." S: ".$speed." days: ".$detatolp;

	$print['DISTANCE'] = $distance;

	

	$shipsA2[$i]['distance'] = $distance ;

	

	

	

	if(!$distance){

		unset($shipsA2[$i]);

		continue;

	}

	/*

	if($shipsA2[$i]['siitech_receivetime_ts']>=$shipsA2[$i]['siitech_lastseen_ts']){

		$print['dataTime'] = $shipsA2[$i]['siitech_receivetime'];

	}

	else{

		$print['dataTime'] = $shipsA2[$i]['siitech_lastseen'];

	}



	$distance = $dc->getDistancePointToPort($print['lat'], $print['long'], $load_portid);

	$print['distanceToLoadPort'] = $distance;

	$print['daysToLoadPort'] = $distance/$print['speed']/24;

	$print['distanceToLoadPort'].=" Nm";

	$print['alerts'] = $shipsA2[$i]['alerts'];

	

	*/

	//print_r($print);

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

			$shipsA2print[] = $print;

			$shipsA2temp[] = $shipsA2[$i];

			$shipcount++;

		}

		else{

			unset($shipsA2[$i]);

		}	

	}	

	else{

		$shipsA2print[] = $print;

		$shipsA2temp[] = $shipsA2[$i];

		$shipcount++;

	}

	if($shipcount>$shiplimit){

		break;

	}

	

	//$distance = $dc->getDistancePointToPort($lat, $long, $load_portid);

	//echo $lat." ".$long." ".$distance."<br>";
	$imoprint2[] = $imoarr;
}



$shipsA2 = $shipsA2temp;

$shipsA2 = array_values($shipsA2);

$shipsA2 = bbsort($shipsA2);

$shipsA2print = bbsort($shipsA2print);



?>