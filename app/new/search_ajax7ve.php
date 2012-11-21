<?php
@session_start();

include_once(dirname(__FILE__)."/includes/bootstrap.php");

$last90days = date('Y-m-d', strtotime(date('Y-m-d'))-(60*60*24*90));
$last60days = date('Y-m-d', strtotime(date('Y-m-d'))-(60*60*24*60));
$last30days = date('Y-m-d', strtotime(date('Y-m-d'))-(60*60*24*30));
$next1day = date('Y-m-d', strtotime(date('Y-m-d'))+(60*60*24*1));
$next7days = date('Y-m-d', strtotime(date('Y-m-d'))+(60*60*24*7));
$next30days = date('Y-m-d', strtotime(date('Y-m-d'))+(60*60*24*30));
$next60days = date('Y-m-d', strtotime(date('Y-m-d'))+(60*60*24*60));
$next90days = date('Y-m-d', strtotime(date('Y-m-d'))+(60*60*24*90));

if(!is_array($_GET['pos_vessel_type'])){
	$vessel_type = trim(mysql_escape_string($_GET['pos_vessel_type']));
}else{
	$vessel_type = $_GET['pos_vessel_type'];
}

if(!is_array($_GET['pos_daterange'])){
	$daterange = trim(mysql_escape_string($_GET['pos_daterange']));
}else{
	$daterange = $_GET['pos_daterange'];
}
		
if($vessel_type && $daterange){
	//VESSEL TYPE
	$vtarr = count($vessel_type);

	if($vtarr){ $sqlext .= " ( "; }
	
	for($vti=0; $vti<$vtarr; $vti++){
		$sqlext .= " xvas.vessel_type = '".$vessel_type[$vti]."' ";

		if(($vti+1)<$vtarr){
			$sqlext .= " OR ";
		}
	}
	
	if($vtarr){ $sqlext .= " ) "; }
	//END OF VESSEL TYPE
	
	//DATE RANGE
	$dtarr = count($daterange);

	if($dtarr){ $sqlext .= " AND ( "; }
	
	for($dti=0; $dti<$dtarr; $dti++){
		if($daterange[$dti]=="bd90"){
			$sqlext .= " siitech.dateupdated BETWEEN '".$last90days."' AND '".date('Y-m-d')."' ";
		}elseif($daterange[$dti]=="bd60"){
			$sqlext .= " siitech.dateupdated BETWEEN '".$last60days."' AND '".date('Y-m-d')."' ";
		}elseif($daterange[$dti]=="bd30"){
			$sqlext .= " siitech.dateupdated BETWEEN '".$last30days."' AND '".date('Y-m-d')."' ";
		}elseif($daterange[$dti]=="t"){
			$sqlext .= " siitech.dateupdated LIKE '%".date('Y-m-d')."%' ";
		}elseif($daterange[$dti]=="fd1"){
			$sqlext .= " siitech.dateupdated BETWEEN '".date('Y-m-d')."' AND '".$next1day."' ";
		}elseif($daterange[$dti]=="fd7"){
			$sqlext .= " siitech.dateupdated BETWEEN '".date('Y-m-d')."' AND '".$next7days."' ";
		}elseif($daterange[$dti]=="fd30"){
			$sqlext .= " siitech.dateupdated BETWEEN '".date('Y-m-d')."' AND '".$next30days."' ";
		}elseif($daterange[$dti]=="fd60"){
			$sqlext .= " siitech.dateupdated BETWEEN '".date('Y-m-d')."' AND '".$next60days."' ";
		}elseif($daterange[$dti]=="fd90"){
			$sqlext .= " siitech.dateupdated BETWEEN '".date('Y-m-d')."' AND '".$next90days."' ";
		}

		if(($dti+1)<$dtarr){
			$sqlext .= " OR ";
		}
	}
	
	if($dtarr){ $sqlext .= " ) "; }
	//END OF DATE RANGE
}
	
if($sqlext){
	$sql = "SELECT 
		siitech.xvas_imo, 
		siitech.xvas_callsign, 
		siitech.xvas_mmsi, 
		siitech.xvas_name, 
		siitech.xvas_hull_type, 
		siitech.xvas_vessel_type, 
		siitech.xvas_summer_dwt, 
		siitech.xvas_speed, 
		siitech.siitech_eta, 
		siitech.siitech_destination, 
		siitech.siitech_lastseen, 
		siitech.siitech_latitude, 
		siitech.siitech_longitude, 
		siitech_shippos_data, 
		siitech_shipstat_data, 
		siitech.dateupdated
		
		FROM 
		
		_xvas_siitech_cache AS siitech INNER JOIN _xvas_parsed2_dry AS xvas ON siitech.xvas_imo=xvas.imo 
		
		WHERE 
		
		siitech.xvas_name!='' AND 
		siitech.siitech_latitude!='' AND 
		siitech.siitech_longitude!='' AND 
		siitech.satellite='0' AND 
		".$sqlext."";
	
	$sql .= " ORDER BY siitech.dateupdated DESC LIMIT 0,500";

	$xvas = dbQuery($sql);

	$t = count($xvas);
	
	if(trim($t)){
		$liveshipposition = array();
		
		for($i=0; $i<$t; $i++){
			$print = array();
			
			$print['xvas_imo']              = $xvas[$i]['xvas_imo'];
			$print['xvas_callsign']         = $xvas[$i]['xvas_callsign'];
			$print['xvas_mmsi']             = $xvas[$i]['xvas_mmsi'];
			$print['xvas_name']             = $xvas[$i]['xvas_name'];
			$print['xvas_hull_type']        = $xvas[$i]['xvas_hull_type'];
			$print['xvas_vessel_type']      = $xvas[$i]['xvas_vessel_type'];
			$print['xvas_summer_dwt']       = $xvas[$i]['xvas_summer_dwt'];
			$print['xvas_speed']            = $xvas[$i]['xvas_speed'];
			$print['siitech_eta']           = $xvas[$i]['siitech_eta'];
			$print['siitech_destination']   = $xvas[$i]['siitech_destination'];
			$print['siitech_lastseen']      = $xvas[$i]['siitech_lastseen'];
			$print['siitech_latitude']      = $xvas[$i]['siitech_latitude'];
			$print['siitech_longitude']     = $xvas[$i]['siitech_longitude'];
			$print['siitech_shippos_data']  = $xvas[$i]['siitech_shippos_data'];
			$print['siitech_shipstat_data'] = $xvas[$i]['siitech_shipstat_data'];
			$print['dateupdated']           = $xvas[$i]['dateupdated'];
			
			$liveshipposition[] = $print;
		}
	}
	
	$_SESSION['liveShipPositionReg'] = $liveshipposition;
	
	if($_SESSION['liveShipPositionReg']){
		echo "<table width='100%'>
			<tr style='background:#e5e5e5; padding:10px 0px;'>
				<td><div style='padding:5px; text-align:center;'><a onclick='showMap();' class='clickable'>view larger map</a></td>
			</tr>
			<tr style='background:#e5e5e5;'>
				<td><div style='padding:5px; text-align:center;'><iframe src='map/index10_online.php' width='990' height='700' frameborder='0'></iframe></div></td>
			</tr>
		</table>";
	}else{
		echo "<table width='100%'>
			<tr style='background:#e5e5e5;'>
				<td><div style='padding:5px; text-align:center;'>No Result</div></td>
			</tr>
		</table>";
	}
}else{
	echo "<table width='100%'>
		<tr style='background:#e5e5e5;'>
			<td><div style='padding:5px; text-align:center;'>Please choose a category</div></td>
		</tr>
	</table>";
}
?>