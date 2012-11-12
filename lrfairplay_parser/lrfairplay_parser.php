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
	
	$filename = dirname(__FILE__)."/data/201211120122_AISData.xml";
	$string = simplexml_load_file($filename);
	
	$data = $string->AISData;
	
	$t = count($data);
	
	if($t){
		for($i=0; $i<$t; $i++){
			$string = xml2array($data[$i]);
			
			//SHIP DATA
			$imo = trim($string['LRIMOShipNo']);
			$callsign = trim($string['CallSign']);
			$mmsi = trim($string['MMSI']);
			$name = trim($string['ShipName']);
			$vessel_type = trim($string['ShipType']);
			$speed = trim($string['Speed']);
			//END OF SHIP DATA
			
			//SHIP LOCATION
			$Latitude = trim($string['Latitude']);
			$Longitude = trim($string['Longitude']);
			$AdditionalInfo = trim($string['AdditionalInfo']);
			$Heading = trim($string['Heading']);
			$MovementDateTime = trim($string['MovementDateTime']);
			$MovementID = trim($string['MovementID']);
			$Draught = trim($string['Draught']);
			$Length = trim($string['Length']);
			$Destination = trim($string['Destination']);
			$ETA = trim($string['ETA']);
			$MoveStatus = trim($string['MoveStatus']);
			//END OF SHIP LOCATION
			
			$shippos = '
<MMSI>'.$mmsi.'</MMSI>
<Latitude>'.$Latitude.'</Latitude>
<Longitude>'.$Longitude.'</Longitude>
<TrueHeading>'.$Heading.'</TrueHeading>
<Second>'.date('s').'</Second>
';
				
				$shipstat = '
<MMSI>'.$mmsi.'</MMSI>
<IMO>'.$imo.'</IMO>
<CallSign>'.$callsign.'</CallSign>
<Name>'.$name.'</Name>
<ShipType>'.$vessel_type.'</ShipType>
<month>'.date('m').'</month>
<day>'.date('d').'</day>
<hour>'.date('H').'</hour>
<minute>'.date('m').'</minute>
<ETA>'.$ETA.'</ETA>
<draught>'.$Draught.'</draught>
<Destination>'.$Destination.'</Destination>
';
			
			$sql = "SELECT xvas_imo FROM `_xvas_siitech_cache` WHERE `xvas_imo`='".mysql_escape_string($imo)."'";
			$r = dbQuery($sql);
			
			if($r['xvas_imo']){
				logStr("Updating #".($i+1)." - ".$name." - ".$imo." ... ");
				
				$lrfairplay_update = "UPDATE _xvas_siitech_cache
								SET xvas_mmsi = '".mysql_escape_string($mmsi)."', 
									xvas_callsign = '".mysql_escape_string($callsign)."', 
									xvas_name = '".mysql_escape_string($name)."', 
									xvas_vessel_type = '".mysql_escape_string($vessel_type)."', 
									xvas_speed = '".mysql_escape_string($speed)."', 
									siitech_eta = '".mysql_escape_string($ETA)."', 
									siitech_destination = '".mysql_escape_string($Destination)."', 
									siitech_latitude = '".mysql_escape_string($Latitude)."', 
									siitech_longitude = '".mysql_escape_string($Longitude)."', 
									siitech_shippos_data = '".mysql_escape_string(addslashes($shippos))."', 
									siitech_shipstat_data = '".mysql_escape_string(addslashes($shipstat))."', 
									dateupdated = NOW()
								WHERE xvas_imo = '".$imo."'";
					dbQuery($lrfairplay_update);
				
				logStr("Updated\n");
			}else{
				logStr("Inserting #".($i+1)." - ".$name." - ".$imo." ... ");
				
				$lrfairplay_update = "INSERT INTO _xvas_siitech_cache (
						xvas_imo, 
						xvas_callsign, 
						xvas_mmsi, 
						xvas_name, 
						xvas_vessel_type, 
						xvas_speed, 
						siitech_eta, 
						siitech_destination, 
						siitech_latitude, 
						siitech_longitude, 
						siitech_shippos_data, 
						siitech_shipstat_data, 
						dateadded,
						dateupdated
					) VALUES (
						'".mysql_escape_string($imo)."', 
						'".mysql_escape_string($callsign)."', 
						'".mysql_escape_string($mmsi)."', 
						'".mysql_escape_string($name)."', 
						'".mysql_escape_string($vessel_type)."', 
						'".mysql_escape_string($speed)."',
						'".mysql_escape_string($ETA)."',
						'".mysql_escape_string($Destination)."',
						'".mysql_escape_string($Latitude)."',
						'".mysql_escape_string($Longitude)."',
						'".mysql_escape_string(addslashes($shippos))."',
						'".mysql_escape_string(addslashes($shipstat))."',
						NOW(), 
						NOW()
					)";
				dbQuery($lrfairplay_update);
				
				logStr("Inserted\n");
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