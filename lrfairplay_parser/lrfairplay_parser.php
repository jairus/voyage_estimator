<?php
@set_time_limit(0);
date_default_timezone_set('UTC');

include_once(dirname(__FILE__)."/../app/includes/database.php");

$sql = "SELECT `id` FROM `_cron` WHERE `flag`='0' AND `parser`='lrfairplay' ORDER BY `id` DESC LIMIT 0,1";
$r   = dbQuery($sql, $link);

if($r[0]){
	echo "AIS HUB UPDATER CRON IS STILL RUNNING!\n";
}else{
	$sql = "INSERT INTO `_cron` (`parser`, `dateadded`, `dateupdated`) VALUES('lrfairplay', NOW(), NOW())";
	dbQuery($sql, $link);
	
	$log = "";
	if(!function_exists("logStr")){
		function logStr($str){
			if($_SERVER['argv'][1]){ return 0; }
	
			global $log;
	
			echo $str;
			
			flush();
	
			$log .= $str;
		}
	}
	
	if(!function_exists("microtime_float")){
		function microtime_float(){
			list($usec, $sec) = explode(" ", microtime());
	
			return ((float)$usec + (float)$sec);
		}
	}
	
	$time_start = microtime_float();
	
	function xml2array($xml) {
	  $arr = array();
	  foreach ($xml as $element) {
		$tag = $element->getName();
		$e = get_object_vars($element);
		if (!empty($e)) {
		  $arr[$tag] = $element instanceof SimpleXMLElement ? xml2array($element) : $e;
		}
		else {
		  $arr[$tag] = trim($element);
		}
	  }
	  return $arr;
	}
	
	//PARSER STARTS HERE
	echo "<pre>";
	logStr("
	/*********************************************************************/
	
	LRFAIRPLAY PARSER 1.1
	Filename: lrfairplay_parser.php
	Date: ".date("M d, Y H:i:s")."
	
	/*********************************************************************/
	\n\n");
	
	logStr("About to parse file ... ");
	
	$filename = dirname(__FILE__)."/data/201210130128_AISData.xml";
	$string = simplexml_load_file($filename);
	
	$data = $string->AISData;
	
	$t = count($data);
	
	if($t){
		for($i=0; $i<$t; $i++){
			$string = xml2array($data[$i]);
			
			$imo = trim($string['LRIMOShipNo']);
			
			if($imo){
				//SHIP DATA
				$sql_xvas = "SELECT `imo`, `callsign`, `mmsi`, `name`, `hull_type`, `vessel_type`, `summer_dwt`, `speed` FROM `_xvas_parsed2_dry` WHERE `imo`='".$imo."' ORDER BY `imo` DESC LIMIT 0,1";
				$r_xvas   = dbQuery($sql_xvas, $link);
				
				if($r_xvas[0]['imo']){
					if($r_xvas[0]['callsign']){
						$callsign = trim($r_xvas[0]['callsign']);
					}else{
						$callsign = trim($string['CallSign']);
					}
					
					if($r_xvas[0]['mmsi']){
						$mmsi = trim($r_xvas[0]['mmsi']);
					}else{
						$mmsi = trim($string['MMSI']);
					}
					
					if($r_xvas[0]['name']){
						$name = strtoupper(trim($r_xvas[0]['name']));
					}else{
						$name = strtoupper(trim($string['ShipName']));
					}
					
					if($r_xvas[0]['vessel_type']){
						$vessel_type = strtoupper(trim($r_xvas[0]['vessel_type']));
					}else{
						$vessel_type = strtoupper(trim($string['ShipType']));
					}
					
					$hull_type = strtoupper(trim($r_xvas[0]['hull_type']));
					$summer_dwt = trim($r_xvas[0]['summer_dwt']);
					$speed_xvas = trim($r_xvas[0]['speed']);
					$speed_ais = trim($string['Speed']);
					//END OF SHIP DATA
					
					//SHIP LOCATION
					$Latitude = trim($string['Latitude']);
					$Longitude = trim($string['Longitude']);
					$AdditionalInfo = trim($string['AdditionalInfo']);
					$Heading = trim($string['Heading']);
					$MovementDateTime = date('Y-m-d H:i:s', strtotime(trim($string['MovementDateTime'])));
					$MovementID = trim($string['MovementID']);
					$Draught = trim($string['Draught']);
					$Length = trim($string['Length']);
					$Destination = strtoupper(trim($string['Destination']));
					
					$ETA1 = trim($string['ETA']);
					$eta_ex = explode('/', $ETA1);
					$time_ex = explode(' ', $eta_ex[2]);
					$ETA = $time_ex[0].'-'.$eta_ex[1].'-'.$eta_ex[0].' '.$time_ex[1];
					
					$MoveStatus = trim($string['MoveStatus']);
					//END OF SHIP LOCATION
					
$shippos = '<MMSI>'.$mmsi.'</MMSI>
<Latitude>'.$Latitude.'</Latitude>
<Longitude>'.$Longitude.'</Longitude>
<SOG>0</SOG>
<TrueHeading>'.$Heading.'</TrueHeading>
<COG>0</COG>
<NavigationalStatus>0</NavigationalStatus>
<Second>'.date('s').'</Second>
<UTC>'.$ETA.'</UTC>
<RecvTime>'.$MovementDateTime.'</RecvTime>
<LocalRecvTime>'.$MovementDateTime.'</LocalRecvTime>';
			
$shipstat = '<MMSI>'.$mmsi.'</MMSI>
<IMO>'.$imo.'</IMO>
<CallSign>'.$callsign.'</CallSign>
<Name>'.$name.'</Name>
<ShipType>'.$vessel_type.'</ShipType>
<to_bow>0</to_bow>
<to_stern>'.$Length.'</to_stern>
<to_port>0</to_port>
<to_starboard>16</to_starboard>
<month>'.date('m').'</month>
<day>'.date('d').'</day>
<hour>'.date('H').'</hour>
<minute>'.date('m').'</minute>
<ETA>'.$ETA.'</ETA>
<draught>'.$Draught.'</draught>
<speed_ais>'.$speed_ais.'</speed_ais>
<Destination>'.$Destination.'</Destination>
<RecvTime>'.$MovementDateTime.'</RecvTime>
<LocalRecvTime>'.$MovementDateTime.'</LocalRecvTime>';
					
					$sql2 = "SELECT `xvas_imo` FROM `_xvas_siitech_cachexxx` WHERE `xvas_imo`='".mysql_escape_string($imo)."' ORDER BY `xvas_imo` LIMIT 0,1";
					$rx = dbQuery($sql2, $link);
		
					if($rx[0]['xvas_imo']){
						logStr("Updating #".($i+1)." - ".$name." - ".$imo." ... ");
						
						$lrfairplay_update = "UPDATE _xvas_siitech_cachexxx
										SET xvas_mmsi = '".mysql_escape_string($mmsi)."', 
											xvas_callsign = '".mysql_escape_string($callsign)."', 
											xvas_name = '".mysql_escape_string($name)."', 
											xvas_hull_type = '".mysql_escape_string($hull_type)."', 
											xvas_vessel_type = '".mysql_escape_string($vessel_type)."', 
											xvas_summer_dwt = '".mysql_escape_string($summer_dwt)."', 
											xvas_speed = '".mysql_escape_string($speed_xvas)."', 
											siitech_eta = '".mysql_escape_string($ETA)."', 
											siitech_destination = '".mysql_escape_string($Destination)."', 
											siitech_lastseen = '".mysql_escape_string($MovementDateTime)."', 
											siitech_latitude = '".mysql_escape_string($Latitude)."', 
											siitech_longitude = '".mysql_escape_string($Longitude)."', 
											siitech_shippos_data = '".mysql_escape_string(addslashes($shippos))."', 
											siitech_shipstat_data = '".mysql_escape_string(addslashes($shipstat))."', 
											siitech_database = 'LFP', 
											dateupdated = NOW()
										WHERE xvas_imo = '".$imo."'";
							dbQuery($lrfairplay_update);
						
						logStr("Updated\n");
					}else{
						logStr("Inserting #".($i+1)." - ".$name." - ".$imo." ... ");
						
						$lrfairplay_update = "INSERT INTO _xvas_siitech_cachexxx (
								xvas_imo, 
								xvas_callsign, 
								xvas_mmsi, 
								xvas_name, 
								xvas_hull_type, 
								xvas_vessel_type, 
								xvas_summer_dwt, 
								xvas_speed, 
								siitech_eta, 
								siitech_destination, 
								siitech_lastseen, 
								siitech_latitude, 
								siitech_longitude, 
								siitech_shippos_data, 
								siitech_shipstat_data, 
								siitech_database, 
								dateadded,
								dateupdated
							) VALUES (
								'".mysql_escape_string($imo)."', 
								'".mysql_escape_string($callsign)."', 
								'".mysql_escape_string($mmsi)."', 
								'".mysql_escape_string($name)."', 
								'".mysql_escape_string($hull_type)."', 
								'".mysql_escape_string($vessel_type)."', 
								'".mysql_escape_string($summer_dwt)."', 
								'".mysql_escape_string($speed_xvas)."',
								'".mysql_escape_string($ETA)."',
								'".mysql_escape_string($Destination)."',
								'".mysql_escape_string($MovementDateTime)."',
								'".mysql_escape_string($Latitude)."',
								'".mysql_escape_string($Longitude)."',
								'".mysql_escape_string(addslashes($shippos))."',
								'".mysql_escape_string(addslashes($shipstat))."',
								'LFP',
								NOW(), 
								NOW()
							)";
						dbQuery($lrfairplay_update);
						
						logStr("Inserted\n");
					}
				}
			}
		}
	}
	//PARSER ENDS HERE
	
	$sql = "SELECT `id` FROM `_cron` WHERE `flag`='0' AND `parser`='lrfairplay' ORDER BY `id` DESC LIMIT 0,1";
	$r2  = dbQuery($sql, $link);
	
	$sql = "UPDATE `_cron` SET 
			`dateupdated` = NOW(),
			`flag`        = '1'
		WHERE `id`='".$r2[0]['id']."'";
	dbQuery($sql, $link);
	
	$time_end = microtime_float();
	$time     = $time_end - $time_start;
	
	logStr("\n\nLRFAIRPLAY parser elapsed time $time seconds\n");
}
?>