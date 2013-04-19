<?php
@session_start();
include_once(dirname(__FILE__)."/../includes/bootstrap.php");

$t = count($_SESSION['shipsReg2']);

$shipsA1print = array();
for($i=0; $i<$t; $i++){
	$ship1 = $_SESSION['shipsReg2'][$i];
	
	$details = trim(base64_decode($ship1['details']));
	$details = unserialize($details);
	$ship    = $_SESSION[$details['a']][$details['id']];
	
	$print = array();
	
	$imageb = base64_encode("http://dataservice.grosstonnage.com/S-Bisphoto.php?imo=".$ship['IMO #']);
	
	if($user['dry']==1){
		$sql  = "SELECT * FROM `_xvas_shipdata_dry` WHERE `imo`='".$ship['IMO #']."'";
		$xvas = dbQuery($sql);
		$xvas = $xvas[0];
	}elseif($user['dry']==0){
		$sql  = "SELECT * FROM `_xvas_shipdata` WHERE `imo`='".$ship['IMO #']."'";
		$xvas = dbQuery($sql);
		$xvas = $xvas[0];
	}
	
	$xvasflag = getValue($xvas['data'], 'LAST_KNOWN_FLAG');
	if(!trim($xvasflag)){
		$xvasflag = getValue($xvas['data'], 'FLAG');
	}
	$xvasflag_img = getFlagImage($xvasflag);
	
	if($user['dry']==1){
		$sql_pos  = "SELECT * FROM `_xvas_siitech_cache` WHERE `xvas_imo`='".$ship['IMO #']."' ORDER BY `dateupdated` DESC LIMIT 0,1";
		$xvas_pos = dbQuery($sql_pos);
		$xvas_pos = $xvas_pos[0];
	}elseif($user['dry']==0){
		$sql_pos  = "SELECT * FROM `_xvas_siitech_cache_wet` WHERE `xvas_imo`='".$ship['IMO #']."' ORDER BY `dateupdated` DESC LIMIT 0,1";
		$xvas_pos = dbQuery($sql_pos);
		$xvas_pos = $xvas_pos[0];
	}
	
	$siitech_destination = trim($xvas_pos['siitech_destination']);
	if($siitech_destination==""){ $siitech_destination = "<img style='height:15px; width:15px;' src='../images/alert1.png'; alt='No AIS Data Available' title='No AIS Data Available' />"; }
	
	$siitech_eta = $xvas_pos['siitech_eta'];
	if($siitech_eta=="" || $siitech_eta==0 || $siitech_eta=="0000-00-00 00:00:00"){ $siitech_eta = "<img style='height:15px; width:15px;' src='../images/alert1.png'; alt='No AIS Data Available' title='No AIS Data Available' />"; }
	else{ $siitech_eta = date("M j, 'y G:i e", str2time($xvas_pos['siitech_eta'])); }
	
	$siitech_lastseen = $xvas_pos['siitech_lastseen'];
	if($siitech_lastseen=="" || $siitech_lastseen==0 || $siitech_lastseen=="0000-00-00 00:00:00"){ $siitech_lastseen = "<img style='height:15px; width:15px;' src='../images/alert1.png'; alt='No AIS Data Available' title='No AIS Data Available' />"; }
	else{ $siitech_lastseen = date("M j, 'y G:i e", str2time($xvas_pos['siitech_lastseen'])); }
	
	$speed = $xvas_pos['xvas_speed'];
	if(trim($speed)){ $speed = number_format($speed, 2); }
	else{ $speed = "13.50"; }
	
	$speed_ais = getValue($xvas_pos['siitech_shipstat_data'], 'speed_ais');
	if(trim($speed_ais)){ $speed_ais = number_format($speed_ais, 2); }
	else{ $speed_ais = "13.50"; }
	
	$true_heading = getValue($xvas_pos['siitech_shippos_data'], 'TrueHeading');
	$true_heading2 = getValue($xvas_pos['siitech_shippos_data'], 'TrueHeading');
	if(!trim($true_heading)){
		$true_heading = "N/A";
	}
	
	$ship_type = $xvas_pos['xvas_vessel_type'];
	
	$stern = getValue($xvas_pos['siitech_shipstat_data'], 'to_stern');
	if($stern=="" || $stern==0){ $stern = "<img style='height:15px; width:15px;' src='../images/alert1.png'; alt='No AIS Data Available' title='No AIS Data Available' />"; }
	
	$beam = getValue($xvas_pos['siitech_shipstat_data'], 'Beam');
	if($stern=="" || $stern==0){ $stern = "<img style='height:15px; width:15px;' src='../images/alert1.png'; alt='No AIS Data Available' title='No AIS Data Available' />"; }
	
	$utc = getValue($xvas_pos['siitech_shippos_data'], 'UTC');
	if($utc=="" || $utc==0){ $utc = "<img style='height:15px; width:15px;' src='../images/alert1.png'; alt='No AIS Data Available' title='No AIS Data Available' />"; }
	else{ $utc = date("M j, 'y G:i e", str2time($utc)); }
	
	$nav = trim(getValue($xvas_pos['siitech_shippos_data'], 'NavigationalStatus'));
	if($nav==""){ $nav = "<img style='height:15px; width:15px;' src='../images/alert1.png'; alt='No AIS Data Available' title='No AIS Data Available' />"; }
	
	$userid = $_SESSION['user']['id'];
	$sql_bi  = "SELECT * FROM `_messages` WHERE `imo`='".$ship['IMO #']."' AND type='network' AND user_email in ( 
				select `email` from `_sbis_users` where 
						`id` in (
							select `userid1` from _network where (`userid1` = '".$userid."' or `userid2` = '".$userid."')
						) or
						`id` in (
							select `userid2` from _network where (`userid1` = '".$userid."' or `userid2` = '".$userid."')
						)
					)";
	$r_bi = dbQuery($sql_bi);
	$r_bi = $r_bi[0];
	
	$xstring = "
		<table width='600'>
			<tr>
				<td style='width:50%; background:#92d050;'>
					<div style='padding:5px;'>
						<table style='font-family:verdana; font-size:10px;'>
							<tr>
								<td width='100'><b>DESTINATION:</b></td>
								<td>".$siitech_destination."</td>
							</tr>
							<tr>
								<td><b>ETA:</b></td>
								<td>".$siitech_eta."</td>
							</tr>
						</table>
					</div>
				</td>";
			
				if(trim($r_bi)){
					$message = unserialize($r_bi['message']);
				
					$xstring .= "<td style='width:50%; background:#ffc000;'>
						<div style='padding:5px;'>
							<table style='font-family:verdana; font-size:10px;'>
								<tr>
									<td colspan='2'><b>BROKERS INTELLIGENCE</b></td>
								</tr>
								<tr>
									<td width='130'><b>DESTINATION:</b></td>
									<td>".$message['dely']."</td>
								</tr>
								<tr>
									<td valign='top'><b>LAYCAN:</b></td>
									<td>".date("M j, 'y G:i e", str2time($message['delydate_from']))." to ".date("M j, 'y G:i e", str2time($message['delydate_to']))."</td>
								</tr>
							</table>
						</div>
					</td>";
				}else{
					$xstring .= "<td style='width:50%; background:#ffff00;'>
						<div style='padding:5px;'>
							<table style='font-family:verdana; font-size:10px;'>
								<tr>
									<td width='130'><b>AIS LAST SEEN DATE:</b></td>
									<td>".$siitech_lastseen."</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
							</table>
						</div>
					</td>";
				}
			
		$xstring .= "</tr>
		<tr>
			<td colspan='3'>
				<table border='0' cellspacing='0' cellpadding='0'>
					<tr>
						<td valign='top' style='padding-right:10px;'><img src='../image.php?b=1&mx=250&p=".$imageb."'></td>
						<td valign='top' class='green'>
							<table border='0' cellspacing='0' cellpadding='0' style='font-family:verdana; font-size:10px;'>
								<tr>
									<td valign='top' width='130'><b>Name:</b></td>
									<td valign='top'>".$xvas_pos['xvas_name']."</td>
								</tr>
								<tr>
									<td valign='top'><b>IMO:</b></td>
									<td valign='top'>".$xvas_pos['xvas_imo']."</td>
								</tr>
								<tr>
									<td valign='top'><b>Call Sign:</b></td>
									<td valign='top'>".$xvas_pos['xvas_callsign']."</td>
								</tr>
								<tr>
									<td valign='top'><b>MMSI:</b></td>
									<td valign='top'>".$xvas_pos['xvas_mmsi']."</td>
								</tr>
								<tr>
									<td valign='top'><b>Flag:</b></td>
									<td valign='top'><img alt='".htmlentities($xvasflag)."' title='".htmlentities($xvasflag)."' src='../".$xvasflag_img."' width='22' height='15' /></td>
								</tr>
								<tr>
									<td valign='top'><b>Draught:</b></td>
									<td valign='top'>".getValue($xvas_pos['siitech_shipstat_data'], 'draught')."</td>
								</tr>
								<tr>
									<td valign='top'><b>Type:</b></td>
									<td valign='top'>".$ship_type."</td>
								</tr>
								<tr>
									<td valign='top'><b>Length:</b></td>
									<td valign='top'>".$stern."</td>
								</tr>
								<tr>
									<td valign='top'><b>Beam:</b></td>
									<td valign='top'>".$beam."</td>
								</tr>
								<tr>
									<td valign='top'><b>Latitude:</b></td>
									<td>".$xvas_pos['siitech_latitude']."</td>
								</tr>
								<tr>
									<td valign='top'><b>Longitude:</b></td>
									<td>".$xvas_pos['siitech_longitude']."</td>
								</tr>
								<tr>
									<td valign='top'><b>Current Speed:</b></td>
									<td valign='top'>".$speed." kn</td>
								</tr>
								<tr>
									<td valign='top'><b>AIS Speed:</b></td>
									<td valign='top'>".$speed_ais." kn</td>
								</tr>
								<tr>
									<td valign='top'><b>Heading:</b></td>
									<td valign='top'>".$true_heading."</td>
								</tr>
								<tr>
									<td valign='top'><b>Movement Status:</b></td>
									<td valign='top'>".$nav."</td>
								</tr>
								<tr>
									<td valign='top'><b>UTC:</b></td>
									<td valign='top'>".$utc."</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>";
		
	$xstring = str_replace("\n", "", $xstring);
	$xstring = str_replace("\r", "", $xstring);
	
	$print['xstring'] = $xstring;
	
	$shipsA1print[] = $print;
}
?>
<html>
<head>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
<style type="text/css">
html, body, #mapdiv {
	width:100%;
	height:100%;
	margin:0;
	font-family:verdana;
	font-size:10px;
}
</style>
</head>
<body>
<div id="mapdiv"></div>
<script src="http://www.openlayers.org/api/OpenLayers.js"></script>
<script>
map = new OpenLayers.Map("mapdiv");
map.addLayer(new OpenLayers.Layer.OSM());

epsg4326 =  new OpenLayers.Projection("EPSG:4326");
projectTo = map.getProjectionObject();

<?php
$t = count($_SESSION['shipsReg2']);

for($i=0; $i<$t; $i++){
	$ship1 = $_SESSION['shipsReg2'][$i];
	
	$details = trim(base64_decode($ship1['details']));
	$details = unserialize($details);
	$ship    = $_SESSION[$details['a']][$details['id']];
    
    ?> var lonLat = new OpenLayers.LonLat( <?php echo $ship['LONG']; ?>, <?php echo $ship['LAT']; ?> ).transform(epsg4326, projectTo); <?php
}
?>

var zoom = 3;
map.setCenter (lonLat, zoom);

var vectorLayer = new OpenLayers.Layer.Vector("Overlay");

<?php
for($i=0; $i<$t; $i++){
	$ship1 = $_SESSION['shipsReg2'][$i];
	
	$details = trim(base64_decode($ship1['details']));
	$details = unserialize($details);
	$ship    = $_SESSION[$details['a']][$details['id']];
	$xstring = $shipsA1print[$i];
	
	if(trim($ship['TRUE HEADING'])){
		$true_heading2 = str_replace(' degrees', '', $ship['TRUE HEADING']);
	}else{
		$true_heading2 = 0;
	}
        
	?>
	var feature = new OpenLayers.Feature.Vector(
		new OpenLayers.Geometry.Point( <?php echo $ship['LONG']; ?>, <?php echo $ship['LAT']; ?> ).transform(epsg4326, projectTo),
		{description:"<?php echo $xstring['xstring']; ?>"} ,
		{externalGraphic: '<?php
		if(0>=$true_heading2){
			echo 'ship0.png';
		}else if(15>=$true_heading2){
			echo 'ship15.png';
		}else if(30>=$true_heading2){
			echo 'ship30.png';
		}else if(45>=$true_heading2){
			echo 'ship45.png';
		}else if(60>=$true_heading2){
			echo 'ship60.png';
		}else if(75>=$true_heading2){
			echo 'ship75.png';
		}else if(90>=$true_heading2){
			echo 'ship90.png';
		}else if(105>=$true_heading2){
			echo 'ship105.png';
		}else if(120>=$true_heading2){
			echo 'ship120.png';
		}else if(135>=$true_heading2){
			echo 'ship135.png';
		}else if(150>=$true_heading2){
			echo 'ship150.png';
		}else if(165>=$true_heading2){
			echo 'ship165.png';
		}else if(180>=$true_heading2){
			echo 'ship180.png';
		}else if(195>=$true_heading2){
			echo 'ship195.png';
		}else if(210>=$true_heading2){
			echo 'ship210.png';
		}else if(225>=$true_heading2){
			echo 'ship225.png';
		}else if(240>=$true_heading2){
			echo 'ship240.png';
		}else if(255>=$true_heading2){
			echo 'ship255.png';
		}else if(270>=$true_heading2){
			echo 'ship270.png';
		}else if(285>=$true_heading2){
			echo 'ship285.png';
		}else if(300>=$true_heading2){
			echo 'ship300.png';
		}else if(315>=$true_heading2){
			echo 'ship315.png';
		}else if(330>=$true_heading2){
			echo 'ship330.png';
		}else if(345>=$true_heading2){
			echo 'ship345.png';
		}else if(360>=$true_heading2){
			echo 'ship360.png';
		}else{
			echo 'ship270.png';
		}
		?>', graphicHeight: 70, graphicWidth: 70, graphicXOffset:-12, graphicYOffset:-25  }
	);    
	vectorLayer.addFeatures(feature);
	<?php
}
?>

map.addLayer(vectorLayer);

var controls = {
    selector: new OpenLayers.Control.SelectFeature(vectorLayer, { onSelect: createPopup, onUnselect: destroyPopup })
};

function createPopup(feature) {
    feature.popup = new OpenLayers.Popup.FramedCloud("pop",
        feature.geometry.getBounds().getCenterLonLat(),
        null,
        '<div class="markerContent">'+feature.attributes.description+'</div>',
        null,
        true,
        function() { controls['selector'].unselectAll(); }
    );
    
    map.addPopup(feature.popup);
}

function destroyPopup(feature) {
    feature.popup.destroy();
    feature.popup = null;
}

map.addControl(controls['selector']);
controls['selector'].activate();
</script>
</body>
</html>