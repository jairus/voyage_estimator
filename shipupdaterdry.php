<?php
if(!function_exists("microtime_float")){
	function microtime_float(){
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}
}
$log = "";
if(!function_exists("logStr")){
	function logStr($str){
		global $log;
		echo $str;
		flush();
		$log .= $str;
	}
}

$all_time_start = microtime_float();

$dbhost = 's-bis.cfclysrb91of.us-east-1.rds.amazonaws.com';
$dbuser = 'sbis';
$dbpass = 'roysbis';
$dbname = 'sbis';

$conn = mysql_connect($dbhost,$dbuser,$dbpass) or die('Error connecting to mysql');
mysql_select_db($dbname);

include('app/includes/Snoopy.class.php');

//FUNCTIONS
function fetchxvas($imo){
	$vars = array("imo"=>$imo,"mode"=>"ALL");
	$snoopy = new Snoopy();
	
	$snoopy->httpmethod = "GET";
	$snoopy->submit("http://dataservice.grosstonnage.com/S-Bis.php", $vars);

	$contents = $snoopy->results;
	
	return $contents;
}

function getValue($data, $id){
	$reg = "/<".$id.".*>(.*)<\/".$id.">/iUs";

	$matches = array();

	preg_match_all($reg, $data, $matches);

	return $matches[1][0];
}
//END

$vars = array("where"=>"idtype=402 OR idtype=407 OR idtype=404 OR idtype=1723 OR idtype=413 OR idtype=5839 OR idtype=411 OR idtype=414 OR idtype=416 OR idtype=5895 OR idtype=405 OR idtype=408 OR idtype=413","mode"=>"STAT");
$snoopy = new Snoopy();

$snoopy->httpmethod = "GET";
$snoopy->submit("http://dataservice.grosstonnage.com/S-Bis.php", $vars);

$str = $snoopy->results;

$lines = explode( "\n", $str);
$counter = 0;
if ($lines) {
	foreach ($lines as $line) {
		$imo = explode(";",$line);

		if($imo[0]!="" && strlen($imo[0])==7){
			$query = mysql_query("SELECT imo FROM _xvas_parsed2_dry WHERE imo='".$imo[0]."'") or die('Error querying from _xvas_parsed2_dry');
			$rows = mysql_num_rows($query);
			
			$data = fetchxvas($imo[0]);
			
			$callsign      = getValue($data, 'CALL_SIGN');
			$mmsi          = getValue($data, 'MMSI_CODE');
			$name          = getValue($data, 'NAME');
			$hull_type     = getValue($data, 'HULL_TYPE');
			$vessel_type   = getValue($data, 'VESSEL_TYPE');
			$owner         = getValue($data, 'OWNER');
			$builder       = getValue($data, 'BUILDER');
			$manager_owner = getValue($data, 'MANAGER_OWNER');
			$manager       = getValue($data, 'MANAGER');
			$summer_dwt    = getValue($data, 'SUMMER_DWT');
			$speed         = getValue($data, 'SPEED_SERVICE');
			
			if($rows==0){
				logStr("Inserting #".$counter." - ".$name." - ".$imo[0]." ... ");
				
				$updates = "INSERT INTO _xvas_shipdata_dry (imo, data, dateadded, dateupdated) VALUES ('".$imo[0]."', '".mysql_escape_string($data)."', NOW(), NOW())";
				mysql_query ($updates);
				
				$updates2 = "INSERT INTO _xvas_parsed2_dry (imo, callsign, mmsi, name, hull_type, vessel_type, owner, builder, manager_owner, manager, summer_dwt, speed, dateadded, dateupdated) VALUES ('".$imo[0]."', '".$callsign."', '".$mmsi."', '".$name."', '".$hull_type."', '".$vessel_type."', '".$owner."', '".$builder."', '".$manager_owner."', '".$manager."', '".$summer_dwt."', '".$speed."', NOW(), NOW())";
				mysql_query ($updates2);
				
				logStr("Inserted\n");
			}else{
				logStr("Updating #".$counter." - ".$name." - ".$imo[0]." ... ");
				
				$updates = "UPDATE _xvas_shipdata_dry
							SET data = '".mysql_escape_string($data)."',
								dateupdated = NOW()
							WHERE imo = '".$imo[0]."'";
				mysql_query ($updates);
				
				$updates2 = "UPDATE _xvas_parsed2_dry
							SET callsign = '".$callsign."',
								mmsi = '".$mmsi."',
								name = '".$name."',
								hull_type = '".$hull_type."',
								vessel_type = '".$vessel_type."',
								owner = '".$owner."',
								builder = '".$builder."',
								manager_owner = '".$manager_owner."',
								manager = '".$manager."',
								summer_dwt = '".$summer_dwt."',
								speed = '".$speed."',
								dateupdated = NOW()
							WHERE imo = '".$imo[0]."'";
				mysql_query ($updates2);
				
				logStr("Updated\n");
			}
		}
		
		$counter++;
	}
}

$all_time_end = microtime_float();
$all_time = $all_time_end - $all_time_start;
echo "\n\nDRY parser Elapsed time $all_time seconds\n";
?>