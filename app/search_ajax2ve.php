<?php
@session_start();
date_default_timezone_set('UTC');
?>

<script>
function openMapRegister(details){
	jQuery("#mapiframe")[0].src='map/register_map.php?details='+details+"&t="+(new Date()).getTime();
	jQuery("#mapdialog").dialog("open");
}

function oUpdateShipSearch0(id){
	jQuery("#oUpdateShipSearch0"+id).attr("width", "100%");
	jQuery("#oUpdateShipSearch0"+id).toggle();
}

function oUpdateShipSearch1(id){
	jQuery("#oUpdateShipSearch1"+id).attr("width", "100%");
	jQuery("#oUpdateShipSearch1"+id).toggle();
}

function oUpdateShipSearch2(id){
	jQuery("#oUpdateShipSearch2"+id).attr("width", "100%");
	jQuery("#oUpdateShipSearch2"+id).toggle();
}
</script>

<?php
include_once(dirname(__FILE__)."/includes/bootstrap.php");

$link = dbConnect();

function floorTs($ts){
	$date = date("Y-m-d 00:00:00",$ts);
	$ts = strtotime($date);

	return $ts;
}

function getMessageByImo($imo, $type){
	$hasnum = preg_match("/[0-9]/iUs", $type);
	$type = preg_replace("/[0-9]/iUs", "", $type);

	global $link;

	$imo = mysql_escape_string($imo);

	if(strtolower($type)=='private'){
		$userid = $_SESSION['user']['id'];
		
		$sql = "select `email` from `_sbis_users` where `id`='".$userid."'";
		
		$email = dbQuery($sql, $link);
		$email = $email[0]['email'];

		$sql = "select * from `_messages` where `imo`='".$imo."' and `type`='private' and `user_email` = '".$email."' order by `id` desc  limit 1";
	}else if(strtolower($type)=='remark'||strtolower($type)=='openport'||strtolower($type)=='opendate'||strtolower($type)=='network'||strtolower($type)=='user_email'){
		$userid = $_SESSION['user']['id'];		

		$sql = "
		select * from `_messages` where `imo`='".$imo."' and `type`='network' and 
		`user_email` in ( 
			select `email` from `_sbis_users` where 
			`id` in (
				select `userid1` from _network where (`userid1` = '".$userid."' or `userid2` = '".$userid."')
			) or
			`id` in (
				select `userid2` from _network where (`userid1` = '".$userid."' or `userid2` = '".$userid."')
			)
		)
		order by `id` desc limit 1";	
	}

	$r = dbQuery($sql, $link);

	if($hasnum ){ }

	return $r[0];
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

$ship = trim($_GET['ship']);
$operator = trim($_GET['operator']);

if(!$ship&&!$operator){
	echo "Invalid Search Parameters";

	exit();
}


if($user['dry']==1){
	if(!$ship && $operator){
		$operator = "%".mysql_escape_string($operator)."%";

		$sql = "SELECT imo, name FROM `_xvas_parsed2_dry` WHERE owner LIKE '".$operator."' AND owner!='' ORDER BY name";
		$ships_owner = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_dry` WHERE manager_owner LIKE '".$operator."' AND manager_owner!='' ORDER BY name";
		$ships_manager_owner = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_dry` WHERE manager LIKE '".$operator."' AND manager!='' ORDER BY name";
		$ships_manager = dbQuery($sql, $link);
		
		$ships = array_merge($ships_owner, $ships_manager_owner, $ships_manager);
		$ships = array_values($ships);
		
		$t = count($ships);
	}else if($ship && !$operator){
		$ship = "%".mysql_escape_string($ship)."%";

		$sql = "SELECT imo, name FROM `_xvas_parsed2_dry` WHERE name LIKE '".$ship."' AND name!='' ORDER BY name";
		$ships_name = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_dry` WHERE imo LIKE '".$ship."' AND imo!='' ORDER BY name";
		$ships_imo = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_dry` WHERE mmsi LIKE '".$ship."' AND mmsi!='' ORDER BY name";
		$ships_mmsi = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_dry` WHERE callsign LIKE '".$ship."' AND callsign!='' ORDER BY name";
		$ships_callsign = dbQuery($sql, $link);
		
		$ships = array_merge($ships_name, $ships_imo, $ships_mmsi, $ships_callsign);
		$ships = array_values($ships);
		
		$t = count($ships);
	}else{
		$ship     = "%".mysql_escape_string($ship)."%";
		$operator = "%".mysql_escape_string($operator)."%";

		$sql = "SELECT imo, name FROM `_xvas_parsed2_dry` WHERE name LIKE '".$ship."' AND name!='' ORDER BY name";
		$ships_name = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_dry` WHERE imo LIKE '".$ship."' AND imo!='' ORDER BY name";
		$ships_imo = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_dry` WHERE mmsi LIKE '".$ship."' AND mmsi!='' ORDER BY name";
		$ships_mmsi = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_dry` WHERE callsign LIKE '".$ship."' AND callsign!='' ORDER BY name";
		$ships_callsign = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_dry` WHERE owner LIKE '".$operator."' AND owner!='' ORDER BY name";
		$ships_owner = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_dry` WHERE manager_owner LIKE '".$operator."' AND manager_owner!='' ORDER BY name";
		$ships_manager_owner = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_dry` WHERE manager LIKE '".$operator."' AND manager!='' ORDER BY name";
		$ships_manager = dbQuery($sql, $link);
		
		$ships = array_merge($ships_name, $ships_imo, $ships_mmsi, $ships_callsign, $ships_owner, $ships_manager_owner, $ships_manager);
		$ships = array_values($ships);
		
		$t = count($ships);
	}
}elseif($user['dry']==2){
	if(!$ship && $operator){
		$operator = "%".mysql_escape_string($operator)."%";

		$sql = "SELECT imo, name FROM `_xvas_parsed2_container` WHERE owner LIKE '".$operator."' AND owner!='' ORDER BY name";
		$ships_owner = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_container` WHERE manager_owner LIKE '".$operator."' AND manager_owner!='' ORDER BY name";
		$ships_manager_owner = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_container` WHERE manager LIKE '".$operator."' AND manager!='' ORDER BY name";
		$ships_manager = dbQuery($sql, $link);
		
		$ships = array_merge($ships_owner, $ships_manager_owner, $ships_manager);
		$ships = array_values($ships);
		
		$t = count($ships);
	}else if($ship && !$operator){
		$ship = "%".mysql_escape_string($ship)."%";

		$sql = "SELECT imo, name FROM `_xvas_parsed2_container` WHERE name LIKE '".$ship."' AND name!='' ORDER BY name";
		$ships_name = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_container` WHERE imo LIKE '".$ship."' AND imo!='' ORDER BY name";
		$ships_imo = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_container` WHERE mmsi LIKE '".$ship."' AND mmsi!='' ORDER BY name";
		$ships_mmsi = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_container` WHERE callsign LIKE '".$ship."' AND callsign!='' ORDER BY name";
		$ships_callsign = dbQuery($sql, $link);
		
		$ships = array_merge($ships_name, $ships_imo, $ships_mmsi, $ships_callsign);
		$ships = array_values($ships);
		
		$t = count($ships);
	}else{
		$ship     = "%".mysql_escape_string($ship)."%";
		$operator = "%".mysql_escape_string($operator)."%";

		$sql = "SELECT imo, name FROM `_xvas_parsed2_container` WHERE name LIKE '".$ship."' AND name!='' ORDER BY name";
		$ships_name = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_container` WHERE imo LIKE '".$ship."' AND imo!='' ORDER BY name";
		$ships_imo = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_container` WHERE mmsi LIKE '".$ship."' AND mmsi!='' ORDER BY name";
		$ships_mmsi = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_container` WHERE callsign LIKE '".$ship."' AND callsign!='' ORDER BY name";
		$ships_callsign = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_container` WHERE owner LIKE '".$operator."' AND owner!='' ORDER BY name";
		$ships_owner = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_container` WHERE manager_owner LIKE '".$operator."' AND manager_owner!='' ORDER BY name";
		$ships_manager_owner = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_container` WHERE manager LIKE '".$operator."' AND manager!='' ORDER BY name";
		$ships_manager = dbQuery($sql, $link);
		
		$ships = array_merge($ships_name, $ships_imo, $ships_mmsi, $ships_callsign, $ships_owner, $ships_manager_owner, $ships_manager);
		$ships = array_values($ships);
		
		$t = count($ships);
	}
}elseif($user['dry']==3){
	if(!$ship && $operator){
		$operator = "%".mysql_escape_string($operator)."%";

		$sql = "SELECT imo, name FROM `_xvas_parsed2_osv` WHERE owner LIKE '".$operator."' AND owner!='' ORDER BY name";
		$ships_owner = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_osv` WHERE manager_owner LIKE '".$operator."' AND manager_owner!='' ORDER BY name";
		$ships_manager_owner = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_osv` WHERE manager LIKE '".$operator."' AND manager!='' ORDER BY name";
		$ships_manager = dbQuery($sql, $link);
		
		$ships = array_merge($ships_owner, $ships_manager_owner, $ships_manager);
		$ships = array_values($ships);
		
		$t = count($ships);
	}else if($ship && !$operator){
		$ship = "%".mysql_escape_string($ship)."%";

		$sql = "SELECT imo, name FROM `_xvas_parsed2_osv` WHERE name LIKE '".$ship."' AND name!='' ORDER BY name";
		$ships_name = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_osv` WHERE imo LIKE '".$ship."' AND imo!='' ORDER BY name";
		$ships_imo = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_osv` WHERE mmsi LIKE '".$ship."' AND mmsi!='' ORDER BY name";
		$ships_mmsi = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_osv` WHERE callsign LIKE '".$ship."' AND callsign!='' ORDER BY name";
		$ships_callsign = dbQuery($sql, $link);
		
		$ships = array_merge($ships_name, $ships_imo, $ships_mmsi, $ships_callsign);
		$ships = array_values($ships);
		
		$t = count($ships);
	}else{
		$ship     = "%".mysql_escape_string($ship)."%";
		$operator = "%".mysql_escape_string($operator)."%";

		$sql = "SELECT imo, name FROM `_xvas_parsed2_osv` WHERE name LIKE '".$ship."' AND name!='' ORDER BY name";
		$ships_name = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_osv` WHERE imo LIKE '".$ship."' AND imo!='' ORDER BY name";
		$ships_imo = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_osv` WHERE mmsi LIKE '".$ship."' AND mmsi!='' ORDER BY name";
		$ships_mmsi = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_osv` WHERE callsign LIKE '".$ship."' AND callsign!='' ORDER BY name";
		$ships_callsign = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_osv` WHERE owner LIKE '".$operator."' AND owner!='' ORDER BY name";
		$ships_owner = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_osv` WHERE manager_owner LIKE '".$operator."' AND manager_owner!='' ORDER BY name";
		$ships_manager_owner = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_osv` WHERE manager LIKE '".$operator."' AND manager!='' ORDER BY name";
		$ships_manager = dbQuery($sql, $link);
		
		$ships = array_merge($ships_name, $ships_imo, $ships_mmsi, $ships_callsign, $ships_owner, $ships_manager_owner, $ships_manager);
		$ships = array_values($ships);
		
		$t = count($ships);
	}
}elseif($user['dry']==4){
	if(!$ship && $operator){
		$operator = "%".mysql_escape_string($operator)."%";

		$sql = "SELECT imo, name FROM `_xvas_parsed2_gas` WHERE owner LIKE '".$operator."' AND owner!='' ORDER BY name";
		$ships_owner = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_gas` WHERE manager_owner LIKE '".$operator."' AND manager_owner!='' ORDER BY name";
		$ships_manager_owner = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_gas` WHERE manager LIKE '".$operator."' AND manager!='' ORDER BY name";
		$ships_manager = dbQuery($sql, $link);
		
		$ships = array_merge($ships_owner, $ships_manager_owner, $ships_manager);
		$ships = array_values($ships);
		
		$t = count($ships);
	}else if($ship && !$operator){
		$ship = "%".mysql_escape_string($ship)."%";

		$sql = "SELECT imo, name FROM `_xvas_parsed2_gas` WHERE name LIKE '".$ship."' AND name!='' ORDER BY name";
		$ships_name = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_gas` WHERE imo LIKE '".$ship."' AND imo!='' ORDER BY name";
		$ships_imo = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_gas` WHERE mmsi LIKE '".$ship."' AND mmsi!='' ORDER BY name";
		$ships_mmsi = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_gas` WHERE callsign LIKE '".$ship."' AND callsign!='' ORDER BY name";
		$ships_callsign = dbQuery($sql, $link);
		
		$ships = array_merge($ships_name, $ships_imo, $ships_mmsi, $ships_callsign);
		$ships = array_values($ships);
		
		$t = count($ships);
	}else{
		$ship     = "%".mysql_escape_string($ship)."%";
		$operator = "%".mysql_escape_string($operator)."%";

		$sql = "SELECT imo, name FROM `_xvas_parsed2_gas` WHERE name LIKE '".$ship."' AND name!='' ORDER BY name";
		$ships_name = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_gas` WHERE imo LIKE '".$ship."' AND imo!='' ORDER BY name";
		$ships_imo = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_gas` WHERE mmsi LIKE '".$ship."' AND mmsi!='' ORDER BY name";
		$ships_mmsi = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_gas` WHERE callsign LIKE '".$ship."' AND callsign!='' ORDER BY name";
		$ships_callsign = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_gas` WHERE owner LIKE '".$operator."' AND owner!='' ORDER BY name";
		$ships_owner = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_gas` WHERE manager_owner LIKE '".$operator."' AND manager_owner!='' ORDER BY name";
		$ships_manager_owner = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_gas` WHERE manager LIKE '".$operator."' AND manager!='' ORDER BY name";
		$ships_manager = dbQuery($sql, $link);
		
		$ships = array_merge($ships_name, $ships_imo, $ships_mmsi, $ships_callsign, $ships_owner, $ships_manager_owner, $ships_manager);
		$ships = array_values($ships);
		
		$t = count($ships);
	}
}elseif($user['dry']==5){
	if(!$ship && $operator){
		$operator = "%".mysql_escape_string($operator)."%";

		$sql = "SELECT imo, name FROM `_xvas_parsed2_passenger` WHERE owner LIKE '".$operator."' AND owner!='' ORDER BY name";
		$ships_owner = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_passenger` WHERE manager_owner LIKE '".$operator."' AND manager_owner!='' ORDER BY name";
		$ships_manager_owner = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_passenger` WHERE manager LIKE '".$operator."' AND manager!='' ORDER BY name";
		$ships_manager = dbQuery($sql, $link);
		
		$ships = array_merge($ships_owner, $ships_manager_owner, $ships_manager);
		$ships = array_values($ships);
		
		$t = count($ships);
	}else if($ship && !$operator){
		$ship = "%".mysql_escape_string($ship)."%";

		$sql = "SELECT imo, name FROM `_xvas_parsed2_passenger` WHERE name LIKE '".$ship."' AND name!='' ORDER BY name";
		$ships_name = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_passenger` WHERE imo LIKE '".$ship."' AND imo!='' ORDER BY name";
		$ships_imo = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_passenger` WHERE mmsi LIKE '".$ship."' AND mmsi!='' ORDER BY name";
		$ships_mmsi = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_passenger` WHERE callsign LIKE '".$ship."' AND callsign!='' ORDER BY name";
		$ships_callsign = dbQuery($sql, $link);
		
		$ships = array_merge($ships_name, $ships_imo, $ships_mmsi, $ships_callsign);
		$ships = array_values($ships);
		
		$t = count($ships);
	}else{
		$ship     = "%".mysql_escape_string($ship)."%";
		$operator = "%".mysql_escape_string($operator)."%";

		$sql = "SELECT imo, name FROM `_xvas_parsed2_passenger` WHERE name LIKE '".$ship."' AND name!='' ORDER BY name";
		$ships_name = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_passenger` WHERE imo LIKE '".$ship."' AND imo!='' ORDER BY name";
		$ships_imo = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_passenger` WHERE mmsi LIKE '".$ship."' AND mmsi!='' ORDER BY name";
		$ships_mmsi = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_passenger` WHERE callsign LIKE '".$ship."' AND callsign!='' ORDER BY name";
		$ships_callsign = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_passenger` WHERE owner LIKE '".$operator."' AND owner!='' ORDER BY name";
		$ships_owner = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_passenger` WHERE manager_owner LIKE '".$operator."' AND manager_owner!='' ORDER BY name";
		$ships_manager_owner = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_passenger` WHERE manager LIKE '".$operator."' AND manager!='' ORDER BY name";
		$ships_manager = dbQuery($sql, $link);
		
		$ships = array_merge($ships_name, $ships_imo, $ships_mmsi, $ships_callsign, $ships_owner, $ships_manager_owner, $ships_manager);
		$ships = array_values($ships);
		
		$t = count($ships);
	}
}elseif($user['dry']==6){
	if(!$ship && $operator){
		$operator = "%".mysql_escape_string($operator)."%";

		$sql = "SELECT imo, name FROM `_xvas_parsed2_others` WHERE owner LIKE '".$operator."' AND owner!='' ORDER BY name";
		$ships_owner = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_others` WHERE manager_owner LIKE '".$operator."' AND manager_owner!='' ORDER BY name";
		$ships_manager_owner = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_others` WHERE manager LIKE '".$operator."' AND manager!='' ORDER BY name";
		$ships_manager = dbQuery($sql, $link);
		
		$ships = array_merge($ships_owner, $ships_manager_owner, $ships_manager);
		$ships = array_values($ships);
		
		$t = count($ships);
	}else if($ship && !$operator){
		$ship = "%".mysql_escape_string($ship)."%";

		$sql = "SELECT imo, name FROM `_xvas_parsed2_others` WHERE name LIKE '".$ship."' AND name!='' ORDER BY name";
		$ships_name = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_others` WHERE imo LIKE '".$ship."' AND imo!='' ORDER BY name";
		$ships_imo = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_others` WHERE mmsi LIKE '".$ship."' AND mmsi!='' ORDER BY name";
		$ships_mmsi = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_others` WHERE callsign LIKE '".$ship."' AND callsign!='' ORDER BY name";
		$ships_callsign = dbQuery($sql, $link);
		
		$ships = array_merge($ships_name, $ships_imo, $ships_mmsi, $ships_callsign);
		$ships = array_values($ships);
		
		$t = count($ships);
	}else{
		$ship     = "%".mysql_escape_string($ship)."%";
		$operator = "%".mysql_escape_string($operator)."%";

		$sql = "SELECT imo, name FROM `_xvas_parsed2_others` WHERE name LIKE '".$ship."' AND name!='' ORDER BY name";
		$ships_name = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_others` WHERE imo LIKE '".$ship."' AND imo!='' ORDER BY name";
		$ships_imo = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_others` WHERE mmsi LIKE '".$ship."' AND mmsi!='' ORDER BY name";
		$ships_mmsi = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_others` WHERE callsign LIKE '".$ship."' AND callsign!='' ORDER BY name";
		$ships_callsign = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_others` WHERE owner LIKE '".$operator."' AND owner!='' ORDER BY name";
		$ships_owner = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_others` WHERE manager_owner LIKE '".$operator."' AND manager_owner!='' ORDER BY name";
		$ships_manager_owner = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_others` WHERE manager LIKE '".$operator."' AND manager!='' ORDER BY name";
		$ships_manager = dbQuery($sql, $link);
		
		$ships = array_merge($ships_name, $ships_imo, $ships_mmsi, $ships_callsign, $ships_owner, $ships_manager_owner, $ships_manager);
		$ships = array_values($ships);
		
		$t = count($ships);
	}
}elseif($user['dry']==0){
	if(!$ship && $operator){
		$operator = "%".mysql_escape_string($operator)."%";

		$sql = "SELECT imo, name FROM `_xvas_parsed2` WHERE owner LIKE '".$operator."' AND owner!='' ORDER BY name";
		$ships_owner = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2` WHERE manager_owner LIKE '".$operator."' AND manager_owner!='' ORDER BY name";
		$ships_manager_owner = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2` WHERE manager LIKE '".$operator."' AND manager!='' ORDER BY name";
		$ships_manager = dbQuery($sql, $link);
		
		$ships = array_merge($ships_owner, $ships_manager_owner, $ships_manager);
		$ships = array_values($ships);
		
		$t = count($ships);
	}else if($ship && !$operator){
		$ship = "%".mysql_escape_string($ship)."%";

		$sql = "SELECT imo, name FROM `_xvas_parsed2` WHERE name LIKE '".$ship."' AND name!='' ORDER BY name";
		$ships_name = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2` WHERE imo LIKE '".$ship."' AND imo!='' ORDER BY name";
		$ships_imo = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2` WHERE mmsi LIKE '".$ship."' AND mmsi!='' ORDER BY name";
		$ships_mmsi = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2` WHERE callsign LIKE '".$ship."' AND callsign!='' ORDER BY name";
		$ships_callsign = dbQuery($sql, $link);
		
		$ships = array_merge($ships_name, $ships_imo, $ships_mmsi, $ships_callsign);
		$ships = array_values($ships);
		
		$t = count($ships);
	}else{
		$ship     = "%".mysql_escape_string($ship)."%";
		$operator = "%".mysql_escape_string($operator)."%";

		$sql = "SELECT imo, name FROM `_xvas_parsed2` WHERE name LIKE '".$ship."' AND name!='' ORDER BY name";
		$ships_name = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2` WHERE imo LIKE '".$ship."' AND imo!='' ORDER BY name";
		$ships_imo = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2` WHERE mmsi LIKE '".$ship."' AND mmsi!='' ORDER BY name";
		$ships_mmsi = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2` WHERE callsign LIKE '".$ship."' AND callsign!='' ORDER BY name";
		$ships_callsign = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2` WHERE owner LIKE '".$operator."' AND owner!='' ORDER BY name";
		$ships_owner = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2` WHERE manager_owner LIKE '".$operator."' AND manager_owner!='' ORDER BY name";
		$ships_manager_owner = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2` WHERE manager LIKE '".$operator."' AND manager!='' ORDER BY name";
		$ships_manager = dbQuery($sql, $link);
		
		$ships = array_merge($ships_name, $ships_imo, $ships_mmsi, $ships_callsign, $ships_owner, $ships_manager_owner, $ships_manager);
		$ships = array_values($ships);
		
		$t = count($ships);
	}
}

$t = count($ships);

echo "<table id='pblues' width='1300'>
	<tr>
		<th style='background:#BCBCBC; color:#333333; text-align:left; width:250px;'><div style='padding:5px;'>SHIP NAME</div></th>
		<th style='background:#BCBCBC; color:#333333; text-align:left; width:250px;'><div style='padding:5px;'>OPEN PORT</div></th>
		<th style='background:#BCBCBC; color:#333333; text-align:right; width:200px;'><div style='padding:5px;'>LAST SEEN</div></th>
		<th style='background:#BCBCBC; color:#333333; text-align:left; width:200px;'><div style='padding:5px;'>DATE (AIS)</div></th>
		<th style='background:#BCBCBC; color:#333333; text-align:center; width:400px;'><div style='padding:5px;'>BROKER UPDATE</div></th>
	</tr>";

if(trim($t)){
	$shipsA1print = array();
	
	for($i=0; $i<$t; $i++){
		if($ships[$i]['imo']!=$ships[$i-1]['imo']){
			//CHECK IF SHIP EXIST IN DATABASE
			if($user['dry']==1){
				$sql = "SELECT * FROM `_xvas_shipdata_dry` WHERE imo='".$ships[$i]['imo']."'";
				$ship_exist = dbQuery($sql, $link);
				$ship_exist = $ship_exist[0];
			}elseif($user['dry']==2){
				$sql = "SELECT * FROM `_xvas_shipdata_container` WHERE imo='".$ships[$i]['imo']."'";
				$ship_exist = dbQuery($sql, $link);
				$ship_exist = $ship_exist[0];
			}elseif($user['dry']==0){
				$sql = "SELECT * FROM `_xvas_shipdata` WHERE imo='".$ships[$i]['imo']."'";
				$ship_exist = dbQuery($sql, $link);
				$ship_exist = $ship_exist[0];
			}
			
			if(trim($ship_exist['data'])){
				$status = getValue($ship_exist['data'], 'STATUS');
				
				if(trim($status)!="DEAD"){
					//CHECK IF SHIP EXIST IN SIITECH CACHE
					$sql = "SELECT * FROM `_xvas_siitech_cache` WHERE xvas_imo='".$ships[$i]['imo']."' AND satellite='0' ORDER BY dateupdated DESC";
					$siitech_ships = dbQuery($sql, $link);
					
					$t1 = count($siitech_ships);
					//END
					
					if(trim($t1)){
						$sat_arr = array();
						for($i1=0; $i1<$t1; $i1++){
							$sat_arr[$i1] = $siitech_ships[$i1]['satellite'];
						}
						
						for($i1=0; $i1<$t1; $i1++){
							if($sat_arr[$i1-1]!=$sat_arr[$i1]){
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
								//END
							}
						}
					}else{
						echo "<tr style='background:#e5e5e5;'>
							<td>
								<div style='padding:5px;'>
									<table cellpadding='0' cellspacing='0' width='100%'>
										<tr>
											<td width='25'><img src='image.php?b=1&mx=20&p=".$imageb."'></td>
											<td><a class='clickable' onclick='return showShipDetails(\"".$ships[$i]['imo']."\")' >".$ships[$i]['name']."</a></td>
											<td width='25' style=' text-align:right;'><a class='clickable' title='Contact' alt='Contact' onclick='contactOwner(\"".$ships[$i]['imo']."\")'><img src='images/contact_icon.png'></a></td>
										</tr>
									</table>
								</div>
							</td>
							<td><div style='padding:5px;'>&nbsp;</div></td>
							<td style='text-align:right;'><div style='padding:5px;'>&nbsp;</div></td>
							<td><div style='padding:5px;'>&nbsp;</div></td>";
							
							if($user['dry']==1 || $user['dry']==2){
								echo "<td>
									<div style='padding:5px;'>
										<table cellpadding='0' cellspacing='0' width='100%'>
											<tr>
												<td width='50%'><b>Delivery:</b> <input type='button' style='width:125px; cursor:pointer; background:#f0f0f0; border:1px solid #ccc; font-size:10px; padding:2px;' value='".$dely."' alt='".$dely."' title='".$dely."' id='brokersupdate_".$nmid."' onclick='openMessageDialog(this.id, \"".$ships[$i]['imo']."\", \"network\")' /></td>
												<td width='50%'><b>Dely Date:</b> <input type='button' style='width:125px; cursor:pointer; background:#f0f0f0; border:1px solid #ccc; font-size:10px; padding:2px;' value='".$delydate_from."' alt='".$delydate_from."' title='".$delydate_from."' id='brokersupdate_".$nmid."' onclick='openMessageDialog(this.id, \"".$ships[$i]['imo']."\", \"network\")' /></td>
											</tr>
										</table>
									</div>
								</td>";
							}else{
								echo "<td>
									<div style='padding:5px;'>
										<table cellpadding='0' cellspacing='0' width='100%'>
											<tr>
												<td width='50%'><b>Open Port:</b> <input type='button' style='width:125px; cursor:pointer; background:#f0f0f0; border:1px solid #ccc; font-size:10px; padding:2px;' value='".$openport."' alt='".$openport."' title='".$openport."' id='brokersupdate_".$nmid."' onclick='openMessageDialog(this.id, \"".$ships[$i]['imo']."\", \"network\")' /></td>
												<td width='50%'><b>Open Date:</b> <input type='button' style='width:125px; cursor:pointer; background:#f0f0f0; border:1px solid #ccc; font-size:10px; padding:2px;' value='".$opendate."' alt='".$opendate."' title='".$opendate."' id='brokersupdate_".$nmid."' onclick='openMessageDialog(this.id, \"".$ships[$i]['imo']."\", \"network\")' /></td>
											</tr>
										</table>
									</div>
								</td>";
							}
							
						echo "</tr>
						<tr style='background:#e5e5e5;'>
							<td><div style='padding:5px;'><b>".$operator."</b></div></td>
							<td style='text-align:center;'>
								<div style='padding:5px;'>";
									if(trim($updatearr)){
										echo "<input type='button' class='clickable' style='border:1px solid #c0c0c0; font-weight:normal; height:20px; font-size:10px; color:red;' onclick='oUpdateShipSearch2(".$i.")' value=\"Operator's Update\">";
									}else{
										echo "<input type='button' class='clickable' style='border:1px solid #c0c0c0; font-weight:normal; height:20px; font-size:10px;' onclick='oUpdateShipSearch2(".$i.")' value=\"Operator's Update\">";
									}
								echo "</div>
							</td>
							<td colspan='2'><div style='padding:5px;'><b>Private:</b> <input type='button' style='width:144px; cursor:pointer; background:#f0f0f0; border:1px solid #ccc; font-size:10px; padding:2px;' value='".$private."' alt='".$privatealt."' title='".$privatealt."' id='private_".$mid."' onclick='openMessageDialog(this.id, \"".$ships[$i]['imo']."\", \"private\")' /></div></td>
							<td><div style='padding:5px;'><b>Remarks:</b> <input type='button' style='width:144px; cursor:pointer; background:#f0f0f0; border:1px solid #ccc; font-size:10px; padding:2px;' value='".$remarks."' alt='".$remarksalt."' title='".$remarksalt."' id='brokersupdate_".$nmid."' onclick='openMessageDialog(this.id, \"".$ships[$i]['imo']."\", \"network\")' /> <span style='color:red;'>".$nmessagesuper['user_email']."</span></div></td>
						</tr>";
						
						echo "<tr id='oUpdateShipSearch2".$i."' style='display:none;'>
							<td colspan='5'>
								<table cellpadding='2' cellspacing='2' width='100%' >
									<tr>
										<td style='border:1px solid #f0f0f0; padding:5px; color:#900;'><center><b>OPERATOR'S UPDATE</b></center>
											<table border='0' cellpadding='2' cellspacing='2' width='100%'>
												<tr>
													<th style='background:#BCBCBC; color:#333333; width:150px; text-align:left;'><div style='padding:5px;'>Status</div></th>
													<th style='background:#BCBCBC; color:#333333; width:100px; text-align:left;'><div style='padding:5px;'>Date From</div></th>
													<th style='background:#BCBCBC; color:#333333; width:100px; text-align:left;'><div style='padding:5px;'>Date To</div></th>
													<th style='background:#BCBCBC; color:#333333; width:200px; text-align:left;'><div style='padding:5px;'>Open Port</div></th>
													<th style='background:#BCBCBC; color:#333333; width:100px; text-align:left;'><div style='padding:5px;'>Open Date</div></th>
													<th style='background:#BCBCBC; color:#333333; width:100px; text-align:left;'><div style='padding:5px;'>Last Cargo</div></th>
													<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Remarks</div></th>
													<th style='background:#BCBCBC; color:#333333; width:25px; text-align:center;'><div style='padding:5px;'>F</div></th>
												</tr>
												<tr>
													<td style='background-color:#e5e5e5; text-align:left;'><div style='padding:5px;'>".htmlentities(stripslashes($updatearr['ou_status']))."</div></td>
													<td style='background-color:#e5e5e5; text-align:left;'><div style='padding:5px;'>".htmlentities(stripslashes($updatearr['ou_date_from']))."</div></td>
													<td style='background-color:#e5e5e5; text-align:left;'><div style='padding:5px;'>".htmlentities(stripslashes($updatearr['ou_date_to']))."</div></td>
													<td style='background-color:#e5e5e5; text-align:left;'><div style='padding:5px;'>".htmlentities(stripslashes($updatearr['ou_open_port']))."</div></td>
													<td style='background-color:#e5e5e5; text-align:left;'><div style='padding:5px;'>".htmlentities(stripslashes($updatearr['ou_open_date']))."</div></td>
													<td style='background-color:#e5e5e5; text-align:left;'><div style='padding:5px;'>".htmlentities(stripslashes($updatearr['ou_last_cargo']))."</div></td>
													<td style='background-color:#e5e5e5; text-align:left;'><div style='padding:5px;'>".htmlentities(stripslashes($updatearr['ou_remarks']))."</div></td>";
													
													if(trim(htmlentities(stripslashes($updatearr['filename'])))){
														echo "<td style='background-color:#e5e5e5; text-align:center;'><div style='padding:5px;'><a href='operators_update/".htmlentities(stripslashes($updatearr['filename']))."' target='_blank'><img src='images/icon_excel.png' border='0' /></a></div></td>";
													}else{
														echo "<td style='background-color:#e5e5e5; text-align:center;'><div style='padding:5px;'><img src='images/icon_excel_inactive.png' border='0' /></div></td>";
													}
													
												echo "</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>";
					}
				}
			}
		}
		//END
	}
	
	$t2 = count($shipsA1print);
				
	$_SESSION['shipsReg'] = $shipsA1print;
	
	if($t2){
		for($i2=0; $i2<$t2; $i2++){
			$ship = $shipsA1print[$i2];
			
			if($user['dry']==1){
				//DRY
				$sql = "select * from `_xvas_shipdata_dry` where imo='".$ship['IMO #']."'";
				$ship_data = dbQuery($sql, $link);
				//END
			}elseif($user['dry']==2){
				//CONTAINER
				$sql = "select * from `_xvas_shipdata_container` where imo='".$ship['IMO #']."'";
				$ship_data = dbQuery($sql, $link);
				//END
			}elseif($user['dry']==0){
				//WET
				$sql = "select * from `_xvas_shipdata` where imo='".$ship['IMO #']."'";
				$ship_data = dbQuery($sql, $link);
				//END
			}
			
			//CHECK SHIP IMAGE
			$imageb = base64_encode("http://dataservice.grosstonnage.com/S-Bisphoto.php?imo=".$ship['IMO #']);
			//END
			
			//CHECK OPERATOR
			$owner         = getValue($ship_data[0]['data'], 'OWNER');
			$manager_owner = getValue($ship_data[0]['data'], 'MANAGER_OWNER');
			$manager       = getValue($ship_data[0]['data'], 'MANAGER');
			
			if(trim($owner)){
				$operator = $owner;
			}else if(trim($manager_owner)){
				$operator = $manager_owner;
			}else if(trim($manager)){
				$operator = $manager;
			}else{
				$operator = "&nbsp;";
			}
			//END
			
			//PRIVATE MESSAGE
			$private    = getMessageByImo($ship['IMO #'], 'private');
			$mid        = $private['id'];
			$private    = stripslashes($private['message']);
			$privatealt = htmlentities($private);
			$private    = word_limit($private, 2);
			//END
			
			//BROKERS UPDATES
			$nmessage      = getMessageByImo($ship['IMO #'], 'network');
			$nmessagesuper = $nmessage;
			$nmid          = $nmessage['id'];
			$nmessage      = unserialize($nmessage['message']);
			
			if($user['dry']==1 || $user['dry']==2 || $user['dry']==3){
				$dely          = $nmessage['dely'];
				$delydate_from = $nmessage['delydate_from'];
				
				$remarksalt = $nmessage['remarks'];
				$remarks    = word_limit($nmessage['remarks'], 2);
			}else{
				$openport = $nmessage['openport'];
				$opendate = $nmessage['opendate'];
				
				$remarksalt = $nmessage['remark'];
				$remarks    = word_limit($nmessage['remark'], 2);
			}
			//END
			
			//OPERATORS UPDATE
			$sql = "select * from `_operators_update` where `imo`='".$ship['IMO #']."' ORDER BY dateadded DESC";
			$operators_update = dbQuery($sql, $link);
			
			$updatearr = unserialize($operators_update[0]['updates']);
			//END
			
			//SELECT DESTINATION AND DATE
			$sql = "SELECT * FROM `_xvas_siitech_cache` WHERE id='".$ship['id']."' AND satellite='0' ORDER BY dateupdated DESC";
			$siitech_ships = dbQuery($sql, $link);
			//END
			
			//MAP DETAILS
			$details       = array();
			$details['a']  = 'shipsReg';
			$details['id'] = $i2;
			$details       = base64_encode(serialize($details)); 
			//END
			
			echo "<tr style='background:#c5dc3b;'>
				<td colspan='5'><div style='padding:5px;'><b style='font-size:14px; color:white;'>AIS SHORE</b></div></td>
			</tr>
			<tr style='background:#e5e5e5;'>
				<td>
					<div style='padding:5px;'>
						<table cellpadding='0' cellspacing='0' width='100%'>
							<tr>
								<td width='25'><img src='image.php?b=1&mx=20&p=".$imageb."'></td>
								<td><a class='clickable' onclick='return showShipDetails(\"".$ship['IMO #']."\")' >".$ship['Ship Name']."</a></td>
								<td width='25' style=' text-align:right;'><a class='clickable' title='Contact' alt='Contact' onclick='contactOwner(\"".$ship['IMO #']."\")'><img src='images/contact_icon.png'></a></td>
							</tr>
						</table>
					</div>
				</td>
				<td><div style='padding:5px;'>".$siitech_ships[0]['siitech_destination']."</div></td>
				<td style='text-align:right;'><div style='padding:5px;'><a onclick='openMapRegister(\"".$details."\")' class='clickable'><img src='images/map-icon.png' ></a></div></td>
				<td><div style='padding:5px;'>";
				
				if(date("M d, 'y", strtotime($siitech_ships[0]['siitech_lastseen']))!="Jan 01, '70"){
					echo date("M d, 'y", strtotime($siitech_ships[0]['siitech_lastseen']));
				}else{
					if(date("M d, 'y", strtotime($siitech_ships[0]['siitech_eta']))!="Jan 01, '70"){
						echo date("M d, 'y", strtotime($siitech_ships[0]['siitech_eta']));
					}else{
						echo "&nbsp;";
					}
				}
				
				echo "</div></td>";
				
				if($user['dry']==1 || $user['dry']==2 || $user['dry']==3){
					echo "<td>
						<div style='padding:5px;'>
							<table cellpadding='0' cellspacing='0' width='100%'>
								<tr>
									<td width='50%'><b>Delivery:</b> <input type='button' style='width:125px; cursor:pointer; background:#f0f0f0; border:1px solid #ccc; font-size:10px; padding:2px;' value='".$dely."' alt='".$dely."' title='".$dely."' id='brokersupdate_".$nmid."' onclick='openMessageDialog(this.id, \"".$ship['IMO #']."\", \"network\")' /></td>
									<td width='50%'><b>Dely Date:</b> <input type='button' style='width:125px; cursor:pointer; background:#f0f0f0; border:1px solid #ccc; font-size:10px; padding:2px;' value='".$delydate_from."' alt='".$delydate_from."' title='".$delydate_from."' id='brokersupdate_".$nmid."' onclick='openMessageDialog(this.id, \"".$ship['IMO #']."\", \"network\")' /></td>
								</tr>
							</table>
						</div>
					</td>";
				}else{
					echo "<td>
						<div style='padding:5px;'>
							<table cellpadding='0' cellspacing='0' width='100%'>
								<tr>
									<td width='50%'><b>Open Port:</b> <input type='button' style='width:125px; cursor:pointer; background:#f0f0f0; border:1px solid #ccc; font-size:10px; padding:2px;' value='".$openport."' alt='".$openport."' title='".$openport."' id='brokersupdate_".$nmid."' onclick='openMessageDialog(this.id, \"".$ship['IMO #']."\", \"network\")' /></td>
									<td width='50%'><b>Open Date:</b> <input type='button' style='width:125px; cursor:pointer; background:#f0f0f0; border:1px solid #ccc; font-size:10px; padding:2px;' value='".$opendate."' alt='".$opendate."' title='".$opendate."' id='brokersupdate_".$nmid."' onclick='openMessageDialog(this.id, \"".$ship['IMO #']."\", \"network\")' /></td>
								</tr>
							</table>
						</div>
					</td>";
				}
				
			echo "</tr>
			<tr style='background:#e5e5e5;'>
				<td><div style='padding:5px;'><b>".$operator."</b></div></td>
				<td style='text-align:center;'>
					<div style='padding:5px;'>";
						if(trim($updatearr)){
							echo "<input type='button' class='clickable' style='border:1px solid #c0c0c0; font-weight:normal; height:20px; font-size:10px; color:red;' onclick='oUpdateShipSearch0(".$i2.")' value=\"Operator's Update\">";
						}else{
							echo "<input type='button' class='clickable' style='border:1px solid #c0c0c0; font-weight:normal; height:20px; font-size:10px;' onclick='oUpdateShipSearch0(".$i2.")' value=\"Operator's Update\">";
						}
					echo "</div>
				</td>
				<td colspan='2'><div style='padding:5px;'><b>Private:</b> <input type='button' style='width:144px; cursor:pointer; background:#f0f0f0; border:1px solid #ccc; font-size:10px; padding:2px;' value='".$private."' alt='".$privatealt."' title='".$privatealt."' id='private_".$mid."' onclick='openMessageDialog(this.id, \"".$ship['IMO #']."\", \"private\")' /></div></td>
				<td><div style='padding:5px;'><b>Remarks:</b> <input type='button' style='width:144px; cursor:pointer; background:#f0f0f0; border:1px solid #ccc; font-size:10px; padding:2px;' value='".$remarks."' alt='".$remarksalt."' title='".$remarksalt."' id='brokersupdate_".$nmid."' onclick='openMessageDialog(this.id, \"".$ship['IMO #']."\", \"network\")' /> <span style='color:red;'>".$nmessagesuper['user_email']."</span></div></td>
			</tr>";
			
			echo "<tr id='oUpdateShipSearch0".$i2."' style='display:none;'>
				<td colspan='5'>
					<table cellpadding='2' cellspacing='2' width='100%' >
						<tr>
							<td style='border:1px solid #f0f0f0; padding:5px; color:#900;'><center><b>OPERATOR'S UPDATE</b></center>
								<table border='0' cellpadding='2' cellspacing='2' width='100%'>
									<tr>
										<th style='background:#BCBCBC; color:#333333; width:150px; text-align:left;'><div style='padding:5px;'>Status</div></th>
										<th style='background:#BCBCBC; color:#333333; width:100px; text-align:left;'><div style='padding:5px;'>Date From</div></th>
										<th style='background:#BCBCBC; color:#333333; width:100px; text-align:left;'><div style='padding:5px;'>Date To</div></th>
										<th style='background:#BCBCBC; color:#333333; width:200px; text-align:left;'><div style='padding:5px;'>Open Port</div></th>
										<th style='background:#BCBCBC; color:#333333; width:100px; text-align:left;'><div style='padding:5px;'>Open Date</div></th>
										<th style='background:#BCBCBC; color:#333333; width:100px; text-align:left;'><div style='padding:5px;'>Last Cargo</div></th>
										<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Remarks</div></th>
										<th style='background:#BCBCBC; color:#333333; width:25px; text-align:center;'><div style='padding:5px;'>F</div></th>
									</tr>
									<tr>
										<td style='background-color:#e5e5e5; text-align:left;'><div style='padding:5px;'>".htmlentities(stripslashes($updatearr['ou_status']))."</div></td>
										<td style='background-color:#e5e5e5; text-align:left;'><div style='padding:5px;'>".htmlentities(stripslashes($updatearr['ou_date_from']))."</div></td>
										<td style='background-color:#e5e5e5; text-align:left;'><div style='padding:5px;'>".htmlentities(stripslashes($updatearr['ou_date_to']))."</div></td>
										<td style='background-color:#e5e5e5; text-align:left;'><div style='padding:5px;'>".htmlentities(stripslashes($updatearr['ou_open_port']))."</div></td>
										<td style='background-color:#e5e5e5; text-align:left;'><div style='padding:5px;'>".htmlentities(stripslashes($updatearr['ou_open_date']))."</div></td>
										<td style='background-color:#e5e5e5; text-align:left;'><div style='padding:5px;'>".htmlentities(stripslashes($updatearr['ou_last_cargo']))."</div></td>
										<td style='background-color:#e5e5e5; text-align:left;'><div style='padding:5px;'>".htmlentities(stripslashes($updatearr['ou_remarks']))."</div></td>";
										
										if(trim(htmlentities(stripslashes($updatearr['filename'])))){
											echo "<td style='background-color:#e5e5e5; text-align:center;'><div style='padding:5px;'><a href='operators_update/".htmlentities(stripslashes($updatearr['filename']))."' target='_blank'><img src='images/icon_excel.png' border='0' /></a></div></td>";
										}else{
											echo "<td style='background-color:#e5e5e5; text-align:center;'><div style='padding:5px;'><img src='images/icon_excel_inactive.png' border='0' /></div></td>";
										}
										
									echo "</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
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