<?php
@include_once(dirname(__FILE__)."/../includes/bootstrap.php");

function navStat($n){
	$nav = array();

	$nav[0] = "under way using engine";
	$nav[1] = "at anchor";
	$nav[2] = "not under command";
	$nav[3] = "restricted maneuverability";
	$nav[4] = "constrained by her draught";
	$nav[5] = "moored";
	$nav[6] = "aground";
	$nav[7] = "engaged in fishing";
	$nav[8] = "under way sailing";
	$nav[9] = "reserved for future amendment of navigational status for ships carrying DG HS, or MP, or IMO hazard or pollutant category C, high speed craft (HSC)";
	$nav[10] = "reserved for future amendment of navigational status for ships carrying dangerous goods (DG), harmful substances (HS) or marine pollutants (MP), or IMO hazard or pollutant category A, wing in grand (WIG)";
	$nav[11] = "reserved for future use";
	$nav[12] = "reserved for future use";
	$nav[13] = "reserved for future use";
	$nav[14] = "reserved for future use";
	$nav[15] = "not defined, default";

	return strtoupper($nav[$n]);
}

$imageb = base64_encode("http://dataservice.grosstonnage.com/S-Bisphoto.php?imo=".$_GET['imo']);

$sql  = "SELECT * FROM `_xvas_shipdata_dry` WHERE `imo`='".$_GET['imo']."'";
$xvas = dbQuery($sql);
$xvas = $xvas[0];

$xvasflag = getValue($xvas['data'], 'LAST_KNOWN_FLAG');
if(!trim($xvasflag)){
	$xvasflag = getValue($xvas['data'], 'FLAG');
}

$sql_pos  = "SELECT * FROM `_xvas_siitech_cache` WHERE `xvas_imo`='".$_GET['imo']."' ORDER BY `dateupdated` DESC LIMIT 0,1";
$xvas_pos = dbQuery($sql_pos);
$xvas_pos = $xvas_pos[0];

$speed = $xvas_pos['xvas_speed'];
if(trim($speed)){ $speed = number_format($speed, 2); }
else{ $speed = "13.50"; }

$true_heading = getValue($xvas_pos['siitech_shippos_data'], 'TrueHeading');
$true_heading2 = getValue($xvas_pos['siitech_shippos_data'], 'TrueHeading');
if(!trim($true_heading)){
	$true_heading = "N/A";
}

$ship_type = $xvas_pos['xvas_vessel_type'];

$stern = getValue($xvas_pos['siitech_shipstat_data'], 'to_stern');
if($stern=="" || $stern==0){ $stern = "<img style='height:15px; width:15px;' src='../images/alert1.png'; alt='No AIS Data Available' title='No AIS Data Available' />"; }

$b2b = getValue($xvas_pos['siitech_shipstat_data'], 'to_bow');
if($b2b=="" || $b2b==0){ $b2b = "<img style='height:15px; width:15px;' src='../images/alert1.png'; alt='No AIS Data Available' title='No AIS Data Available' />"; }

$p2p = getValue($xvas_pos['siitech_shipstat_data'], 'to_port');
if($p2p=="" || $p2p==0){ $p2p = "<img style='height:15px; width:15px;' src='../images/alert1.png'; alt='No AIS Data Available' title='No AIS Data Available' />"; }

$starboard = getValue($xvas_pos['siitech_shipstat_data'], 'to_starboard');
if($starboard=="" || $starboard==0){ $starboard = "<img style='height:15px; width:15px;' src='../images/alert1.png'; alt='No AIS Data Available' title='No AIS Data Available' />"; }

$cog = getValue($xvas_pos['siitech_shippos_data'], 'COG');
if($cog=="" || $cog==0){ $cog = "<img style='height:15px; width:15px;' src='../images/alert1.png'; alt='No AIS Data Available' title='No AIS Data Available' />"; }

$maneuver = "<img style='height:15px; width:15px;' src='../images/alert1.png'; alt='No AIS Data Available' title='No AIS Data Available' />";

$utc = getValue($xvas_pos['siitech_shippos_data'], 'UTC');
if($utc=="" || $utc==0){ $utc = "<img style='height:15px; width:15px;' src='../images/alert1.png'; alt='No AIS Data Available' title='No AIS Data Available' />"; }

$nav = getValue($xvas_pos['siitech_shippos_data'], 'NavigationalStatus');
if($nav!=""){
	$nav = "<img title='".navStat($nav)."' alt='".navStat($nav)."' src='../images/".$nav.".png'; style='height:15px; width: 15px;' />";
}else{
	$nav = "<img style='height:15px; width:15px;' src='../images/alert1.png'; alt='No AIS Data Available' title='No AIS Data Available' />";
}

$last_port = "<img style='height:15px; width:15px;' src='../images/alert1.png'; alt='No AIS Data Available' title='No AIS Data Available' />";

if($xvas_pos['siitech_eta']!="Jan 1, 1970 0:00 UTC"){
	$destination_eta = "<a class='clickable2' alt='".date("M j, 'y G:i e", str2time($xvas_pos['siitech_eta']))."' title='".date("M j, 'y G:i e", str2time($xvas_pos['siitech_eta']))."'>".substr(date("M j, 'y G:i e", str2time($xvas_pos['siitech_eta'])), 0,11)."</a>";
}else{
	$destination_eta = "<img style='height:15px; width:15px;' src='../images/alert1.png'; alt='No AIS Data Available' title='No AIS Data Available' />";
}

$xstring = "
	<table width='660'>
		<tr>";
			
			if($xvas_pos['siitech_destination']==""){
				$xstring .= "<td style='width:50%; background:#AAFFAA;'>
					<div style='padding:5px;'>
						<table style='font-family:verdana; font-size:10px;'>
							<tr>
								<td><b>Last Seen Date:</b></td>
								<td width='185'><a class='clickable2' alt='".date("M j, 'y G:i e", str2time($xvas_pos['siitech_lastseen']))."' title='".date("M j, 'y G:i e", str2time($xvas_pos['siitech_lastseen']))."'>".substr(date("M j, 'y G:i e", str2time($xvas_pos['siitech_lastseen'])), 0,11)."</a></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
						</table>
					</div>
				</td>";
			}else{
				$xstring .= "<td style='width:50%; background:#AAFFAA;'>
					<div style='padding:5px;'>
						<table style='font-family:verdana; font-size:10px;'>
							<tr>
								<td><b>DESTINATION:</b></td>
								<td width='185'>".$xvas_pos['siitech_destination']."</td>
							</tr>
							<tr>
								<td><b>ETA:</b></td>
								<td><a class='clickable2' alt='".date("M j, 'y G:i e", str2time($xvas_pos['siitech_eta']))."' title='".date("M j, 'y G:i e", str2time($xvas_pos['siitech_eta']))."'>".substr(date("M j, 'y G:i e", str2time($xvas_pos['siitech_eta'])), 0,11)."</a></td>
							</tr>
						</table>
					</div>
				</td>";
			}
			
			$xstring .= "<td style='width:50%; background:#FFFF99;'>
				<div style='padding:5px;'>
					<table style='font-family:verdana; font-size:10px;'>
						<tr>
							<td><b>AIS LAST SEEN DATE:</b></td>
							<td width='185'><a class='clickable2' alt='".date("M j, 'y G:i e", str2time($xvas_pos['siitech_lastseen']))."' title='".date("M j, 'y G:i e", str2time($xvas_pos['siitech_lastseen']))."'>".substr(date("M j, 'y G:i e", str2time($xvas_pos['siitech_lastseen'])), 0,11)."</a></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
					</table>
				</div>
			</td>
		</tr>
		<tr>
			<td colspan='3'>
				<table border='0' cellspacing='0' cellpadding='0'>
					<tr>
						<td valign='top' style='padding-right:10px;'><img src='../image.php?b=1&mx=250&p=".$imageb."'></td>
						<td valign='top' class='green'>
							<table border='0' cellspacing='0' cellpadding='0' style='font-family:verdana; font-size:10px;'>
								<tr>
									<td valign='top'><b>Name:</b></td>
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
									<td valign='top'><b>Country:</b></td>
									<td valign='top'>".$xvasflag."</td>
								</tr>
								<tr>
									<td valign='top'><b>Draught:</b></td>
									<td valign='top'>".getValue($xvas_pos['siitech_shipstat_data'], 'draught')."</td>
								</tr>
								<tr>
									<td valign='top'><b>Ship Type:</b></td>
									<td valign='top'>".$ship_type."</td>
								</tr>
								<tr>
									<td valign='top'><b>Ship Type:</b></td>
									<td valign='top'>".$xvas_pos['xvas_vessel_type']."</td>
								</tr>
								<tr>
									<td valign='top'><b>Length to Stern to Stern:</b></td>
									<td valign='top'>".$stern."</td>
								</tr>
								<tr>
									<td valign='top'><b>From Bow to Bow:</b></td>
									<td valign='top'>".$b2b."</td>
								</tr>
								<tr>
									<td valign='top'><b>From Port to Port:</b></td>
									<td valign='top'>".$p2p."</td>
								</tr>
								<tr>
									<td valign='top'><b>From Starboard to Starboard:</b></td>
									<td valign='top'>".$starboard."</td>
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
									<td valign='top'><b>SOG:</b></td>
									<td valign='top'>".getValue($xvas_pos['siitech_shippos_data'], 'SOG')." kn</td>
								</tr>
								<tr>
									<td valign='top'><b>Stated Speed:</b></td>
									<td valign='top'>".$speed." kn</td>
								</tr>
								<tr>
									<td valign='top'><b>True Heading:</b></td>
									<td valign='top'>".$true_heading."</td>
								</tr>
								<tr>
									<td valign='top'><b>COG:</b></td>
									<td valign='top'>".$cog."</td>
								</tr>
								<tr>
									<td valign='top'><b>NAV:</b></td>
									<td valign='top'>".$nav."</td>
								</tr>
								<tr>
									<td valign='top'><b>Maneuver:</b></td>
									<td valign='top'>".$maneuver."</td>
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
?>
<!DOCTYPE html>
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

var lonLat = new OpenLayers.LonLat( <?php echo $xvas_pos['siitech_longitude']; ?>, <?php echo $xvas_pos['siitech_latitude']; ?> ).transform(epsg4326, projectTo);

var zoom = 3;
map.setCenter (lonLat, zoom);

var vectorLayer = new OpenLayers.Layer.Vector("Overlay");

var feature = new OpenLayers.Feature.Vector(
	new OpenLayers.Geometry.Point( <?php echo $xvas_pos['siitech_longitude']; ?>, <?php echo $xvas_pos['siitech_latitude']; ?> ).transform(epsg4326, projectTo),
	{description:"<?php echo $xstring; ?>"} ,
	{externalGraphic: 'icons/<?php
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