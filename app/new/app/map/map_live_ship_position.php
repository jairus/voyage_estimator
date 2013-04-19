<?php
@session_start();
include_once(dirname(__FILE__)."/../includes/database.php");

function getValue($data, $id){
	$reg = "/<".$id.".*>(.*)<\/".$id.">/iUs";

	$matches = array();

	preg_match_all($reg, $data, $matches);

	return $matches[1][0];
}

function getFlagImage($name){
	global $link;

	$name = mysql_escape_string(trim($name));

	$sql = "select * from `_country_flag` where `country_desc`='".$name."'";
	$country = dbQuery($sql, $link);

	$country = $country[0];

	$id = strtolower($country['flag_id']);

	$f = dirname(__FILE__)."/../images/flags/".$id.".png";

	if(file_exists($f)){
		return "images/flags/".$id.".png";	
	}else{
		return false;
	}
}

$t = count($_SESSION['liveShipPositionReg']);

$xvassA1print = array();
for($i=0; $i<$t; $i++){
	$print = array();
	
	$xvas1 = $_SESSION['liveShipPositionReg'][$i];
	
	$imageb = base64_encode("http://dataservice.grosstonnage.com/S-Bisphoto.php?imo=".$xvas1['xvas_imo']);
	
	if($user['dry']==1){
		$sql  = "SELECT * FROM `_xvas_shipdata_dry` WHERE `imo`='".$xvas1['xvas_imo']."'";
		$xvas = dbQuery($sql);
		$xvas = $xvas[0];
	}elseif($user['dry']==0){
		$sql  = "SELECT * FROM `_xvas_shipdata` WHERE `imo`='".$xvas1['xvas_imo']."'";
		$xvas = dbQuery($sql);
		$xvas = $xvas[0];
	}
	
	$xvasflag = getValue($xvas['data'], 'LAST_KNOWN_FLAG');
	if(!trim($xvasflag)){
		$xvasflag = getValue($xvas['data'], 'FLAG');
	}
	$xvasflag_img = getFlagImage($xvasflag);
	
	if($user['dry']==1){
		$sql_pos  = "SELECT * FROM `_xvas_siitech_cache` WHERE `xvas_imo`='".$xvas1['xvas_imo']."' ORDER BY `dateupdated` DESC LIMIT 0,1";
		$xvas_pos = dbQuery($sql_pos);
		$xvas_pos = $xvas_pos[0];
	}elseif($user['dry']==0){
		$sql_pos  = "SELECT * FROM `_xvas_siitech_cache_wet` WHERE `xvas_imo`='".$xvas1['xvas_imo']."' ORDER BY `dateupdated` DESC LIMIT 0,1";
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
	$sql_bi  = "SELECT * FROM `_messages` WHERE `imo`='".$xvas1['xvas_imo']."' AND type='network' AND user_email in ( 
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
	
	$print['xvas_vessel_type']  = $ship_type;
	$print['siitech_latitude']  = $xvas_pos['siitech_latitude'];
	$print['siitech_longitude'] = $xvas_pos['siitech_longitude'];
	$print['xstring']           = $xstring;
	
	$xvassA1print[] = $print;
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

var lonLat = new OpenLayers.LonLat( <?php echo $xvassA1print[0]['siitech_longitude']; ?>, <?php echo $xvassA1print[0]['siitech_latitude']; ?> ).transform(epsg4326, projectTo);

var zoom = 3;
map.setCenter (lonLat, zoom);

var vectorLayer = new OpenLayers.Layer.Vector("Overlay");

<?php
$t_pa = count($xvassA1print);

for($i_pa=0; $i_pa<$t_pa; $i_pa++){
	$live_ship_position = $xvassA1print[$i_pa];
        
	?>
	var feature = new OpenLayers.Feature.Vector(
		new OpenLayers.Geometry.Point( <?php echo $live_ship_position['siitech_longitude']; ?>, <?php echo $live_ship_position['siitech_latitude']; ?> ).transform(epsg4326, projectTo),
		{description:"<?php echo $live_ship_position['xstring']; ?>"} ,
		{externalGraphic: 'icons/<?php echo str_replace('/', 'slash', $live_ship_position['xvas_vessel_type']).'.png'; ?>', graphicHeight: 20, graphicWidth: 20, graphicXOffset:-12, graphicYOffset:-25  }
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