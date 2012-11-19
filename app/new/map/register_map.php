<?php
@session_start();
include_once(dirname(__FILE__)."/../includes/bootstrap.php");
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
	font-size:11px;
}
</style>
</head>
<body>
<?php
$details = trim(base64_decode($_GET['details']));
$details = unserialize($details);
$ship    = $_SESSION[$details['a']][$details['id']];

$imageb = base64_encode("http://dataservice.grosstonnage.com/S-Bisphoto.php?imo=".$ship['IMO #']);

$sql  = "SELECT * FROM `_xvas_shipdata` WHERE `imo`='".$ship['IMO #']."'";
$xvas = dbQuery($sql);
$xvas = $xvas[0];

if(!trim($xvas['data'])){
	$sql  = "SELECT * FROM `_xvas_shipdata_dry` WHERE `imo`='".$ship['IMO #']."'";
	$xvas = dbQuery($sql);
	$xvas = $xvas[0];
}

$speed = getValue($xvas['data'], 'SPEED_SERVICE');
if(trim($speed)){ $speed = number_format($speed, 2); }
else{ $speed = "13.50"; }

if(trim($ship['TRUE HEADING'])){
	$true_heading = $ship['TRUE HEADING'];
	$true_heading2 = str_replace(' degrees', '', $ship['TRUE HEADING']);
}else{
	$true_heading = "N/A";
	$true_heading2 = 0;
}

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

if($ship['SHIP_TYPE']==20){
	$ship_type = "Ship & Cargo Classification";
}else if($ship['SHIP_TYPE']==21){
	$ship_type = "Wing in ground (WIG), all ships of this type";
}else if($ship['SHIP_TYPE']==22){
	$ship_type = "Wing in ground (WIG), Hazardous category A";
}else if($ship['SHIP_TYPE']==23){
	$ship_type = "Wing in ground (WIG), Hazardous category B";
}else if($ship['SHIP_TYPE']==24){
	$ship_type = "Wing in ground (WIG), Hazardous category C";
}else if($ship['SHIP_TYPE']==25){
	$ship_type = "Wing in ground (WIG), Hazardous category D";
}else if($ship['SHIP_TYPE']==26 || $ship['SHIP_TYPE']==27 || $ship['SHIP_TYPE']==28 || $ship['SHIP_TYPE']==29 || $ship['SHIP_TYPE']==30){
	$ship_type = "Wing in ground (WIG), Reserved for future use";
}else if($ship['SHIP_TYPE']==31){
	$ship_type = "Fishing";
}else if($ship['SHIP_TYPE']==32){
	$ship_type = "Towing";
}else if($ship['SHIP_TYPE']==33){
	$ship_type = "Towing: length exceeds 200m or breadth exceeds 25m";
}else if($ship['SHIP_TYPE']==34){
	$ship_type = "Dredging or underwater ops";
}else if($ship['SHIP_TYPE']==35){
	$ship_type = "Diving ops";
}else if($ship['SHIP_TYPE']==36){
	$ship_type = "Military Ops";
}else if($ship['SHIP_TYPE']==37){
	$ship_type = "Sailing";
}else if($ship['SHIP_TYPE']==38){
	$ship_type = "Pleasure Craft";
}else if($ship['SHIP_TYPE']==39 || $ship['SHIP_TYPE']==40){
	$ship_type = "Reserved";
}else if($ship['SHIP_TYPE']==41){
	$ship_type = "High speed craft (HSC), all ships of this type";
}else if($ship['SHIP_TYPE']==42){
	$ship_type = "High speed craft (HSC), Hazardous category A";
}else if($ship['SHIP_TYPE']==43){
	$ship_type = "High speed craft (HSC), Hazardous category B";
}else if($ship['SHIP_TYPE']==44){
	$ship_type = "High speed craft (HSC), Hazardous category C";
}else if($ship['SHIP_TYPE']==45){
	$ship_type = "High speed craft (HSC), Hazardous category D";
}else if($ship['SHIP_TYPE']==46 || $ship['SHIP_TYPE']==47 || $ship['SHIP_TYPE']==48 || $ship['SHIP_TYPE']==49){
	$ship_type = "High speed craft (HSC), Reserved for future use";
}else if($ship['SHIP_TYPE']==50){
	$ship_type = "High speed craft (HSC), No additional information";
}else if($ship['SHIP_TYPE']==51){
	$ship_type = "Pilot Vessel";
}else if($ship['SHIP_TYPE']==52){
	$ship_type = "Search and Rescue vessel";
}else if($ship['SHIP_TYPE']==53){
	$ship_type = "Tug";
}else if($ship['SHIP_TYPE']==54){
	$ship_type = "Port Tender";
}else if($ship['SHIP_TYPE']==55){
	$ship_type = "Anti-pollution equipment";
}else if($ship['SHIP_TYPE']==56){
	$ship_type = "Law Enforcement";
}else if($ship['SHIP_TYPE']==57 || $ship['SHIP_TYPE']==58){
	$ship_type = "Spare - Local Vessel";
}else if($ship['SHIP_TYPE']==59){
	$ship_type = "Medical Transport";
}else if($ship['SHIP_TYPE']==60){
	$ship_type = "Ship according to RR Resolution No. 18";
}else if($ship['SHIP_TYPE']==61){
	$ship_type = "Passenger, all ships of this type";
}else if($ship['SHIP_TYPE']==62){
	$ship_type = "Passenger, Hazardous category A";
}else if($ship['SHIP_TYPE']==63){
	$ship_type = "Passenger, Hazardous category B";
}else if($ship['SHIP_TYPE']==64){
	$ship_type = "Passenger, Hazardous category C";
}else if($ship['SHIP_TYPE']==65){
	$ship_type = "Passenger, Hazardous category D";
}else if($ship['SHIP_TYPE']==66 || $ship['SHIP_TYPE']==67 || $ship['SHIP_TYPE']==68 || $ship['SHIP_TYPE']==69){
	$ship_type = "Passenger, Reserved for future use";
}else if($ship['SHIP_TYPE']==70){
	$ship_type = "Passenger, No additional information";
}else if($ship['SHIP_TYPE']==71){
	$ship_type = "Cargo, all ships of this type";
}else if($ship['SHIP_TYPE']==72){
	$ship_type = "Cargo, Hazardous category A";
}else if($ship['SHIP_TYPE']==73){
	$ship_type = "Cargo, Hazardous category B";
}else if($ship['SHIP_TYPE']==74){
	$ship_type = "Cargo, Hazardous category C";
}else if($ship['SHIP_TYPE']==75){
	$ship_type = "Cargo, Hazardous category D";
}else if($ship['SHIP_TYPE']==76 || $ship['SHIP_TYPE']==77 || $ship['SHIP_TYPE']==78 || $ship['SHIP_TYPE']==79){
	$ship_type = "Cargo, Reserved for future use";
}else if($ship['SHIP_TYPE']==80){
	$ship_type = "Cargo, No additional information";
}else if($ship['SHIP_TYPE']==81){
	$ship_type = "Tanker, all ships of this type";
}else if($ship['SHIP_TYPE']==82){
	$ship_type = "Tanker, Hazardous category A";
}else if($ship['SHIP_TYPE']==83){
	$ship_type = "Tanker, Hazardous category B";
}else if($ship['SHIP_TYPE']==84){
	$ship_type = "Tanker, Hazardous category C";
}else if($ship['SHIP_TYPE']==85){
	$ship_type = "Tanker, Hazardous category D";
}else if($ship['SHIP_TYPE']==86 || $ship['SHIP_TYPE']==87 || $ship['SHIP_TYPE']==88 || $ship['SHIP_TYPE']==89){
	$ship_type = "Tanker, Reserved for future use";
}else if($ship['SHIP_TYPE']==90){
	$ship_type = "Tanker, No additional information";
}else if($ship['SHIP_TYPE']==91){
	$ship_type = "Other Type, all ships of this type";
}else if($ship['SHIP_TYPE']==92){
	$ship_type = "Other Type, Hazardous category A";
}else if($ship['SHIP_TYPE']==93){
	$ship_type = "Other Type, Hazardous category B";
}else if($ship['SHIP_TYPE']==94){
	$ship_type = "Other Type, Hazardous category C";
}else if($ship['SHIP_TYPE']==95){
	$ship_type = "Other Type, Hazardous category D";
}else if($ship['SHIP_TYPE']==96 || $ship['SHIP_TYPE']==97 || $ship['SHIP_TYPE']==98 || $ship['SHIP_TYPE']==99){
	$ship_type = "Other Type, Reserved for future use";
}else{
	$ship_type = "Other Type, No additional information";
}

if($ship['STERN']!=""){ $stern = $ship['STERN']; }
else{ $stern = "<img style='height:15px; width:15px;' src='../images/alert1.png' alt='No AIS Data Available' title='No AIS Data Available' />"; }

if($ship['B2B']!=""){ $b2b = $ship['B2B']; }
else{ $b2b = "<img style='height:15px; width:15px;' src='../images/alert1.png' alt='No AIS Data Available' title='No AIS Data Available' />"; }

if($ship['P2P']!=""){ $p2p = $ship['P2P']; }
else{ $p2p = "<img style='height:15px; width:15px;' src='../images/alert1.png' alt='No AIS Data Available' title='No AIS Data Available' />"; }

if($ship['STARBOARD']!=""){ $starboard = $ship['STARBOARD']; }
else{ $starboard = "<img style='height:15px; width:15px;' src='../images/alert1.png' alt='No AIS Data Available' title='No AIS Data Available' />"; }

if($ship['COG']!=""){ $cog = $ship['COG']." degrees"; }
else{ $cog = "<img style='height:15px; width:15px;' src='../images/alert1.png' alt='No AIS Data Available' title='No AIS Data Available' />"; }

if($ship['MANEUVER']!=""){ $maneuver = $ship['MANEUVER']; }
else{ $maneuver = "<img style='height:15px; width:15px;' src='../images/alert1.png' alt='No AIS Data Available' title='No AIS Data Available' />"; }

if($ship['UTC']!=""){ $utc = $ship['UTC']; }
else{ $utc = "<img style='height:15px; width:15px;' src='../images/alert1.png' alt='No AIS Data Available' title='No AIS Data Available' />"; }

if($ship['NAVSTAT']!=""){
	$nav = "<img title='".navStat($ship['NAVSTAT'])."' alt='".navStat($ship['NAVSTAT'])."' src='../images/".$ship['NAVSTAT'].".png' style='height:15px; width: 15px;' />";
}else{
	$nav = "<img style='height:15px; width:15px;' src='../images/alert1.png' alt='No AIS Data Available' title='No AIS Data Available' />";
}

if($ship['LAST_PORT']!=""){
	$last_port = $ship['LAST_PORT'];
}else{
	$last_port = "<img style='height:15px; width:15px;' src='../images/alert1.png' alt='No AIS Data Available' title='No AIS Data Available' />";
}

if($ship['DESTINATION_ETA']!="Jan 1, 1970 0:00 UTC"){
	$destination_eta = "<a class='clickable2' alt='".date("M j, 'y G:i e", str2time($ship['DESTINATION_ETA']))."' title='".date("M j, 'y G:i e", str2time($ship['DESTINATION_ETA']))."'>".substr(date("M j, 'y G:i e", str2time($ship['DESTINATION_ETA'])), 0,12)."</a>";
}else{
	$destination_eta = "<img style='height:15px; width:15px;' src='../images/alert1.png' alt='No AIS Data Available' title='No AIS Data Available' />";
}

$xstring = "
	<table width='660'>
		<tr>";
			
			if($ship['DESTINATION']==""){
				$xstring .= "<td style='width:50%; background:#AAFFAA;' class='green'>
					<div style='padding:5px;'>
						<table style='font-family:verdana; font-size:11px;'>
							<tr>
								<td><b>Last Seen Date:</b></td>
								<td width='185'><a class='clickable2' alt='".date("M j, 'y G:i e", str2time($ship['LASTSEEN_DATE']))."' title='".date("M j, 'y G:i e", str2time($ship['LASTSEEN_DATE']))."'>".substr(date("M j, 'y G:i e", str2time($ship['LASTSEEN_DATE'])), 0,12)."</a></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
						</table>
					</div>
				</td>";
			}else{
				$xstring .= "<td style='width:50%; background:#AAFFAA;' class='green'>
					<div style='padding:5px;'>
						<table style='font-family:verdana; font-size:11px;'>
							<tr>
								<td><b>Open Port:</b></td>
								<td width='185'>".$ship['DESTINATION']."</td>
							</tr>
							<tr>
								<td><b>Open Port ETA:</b></td>
								<td><a class='clickable2' alt='".date("M j, 'y G:i e", str2time($ship['SIITECH_ETA']))."' title='".date("M j, 'y G:i e", str2time($ship['SIITECH_ETA']))."'>".substr(date("M j, 'y G:i e", str2time($ship['SIITECH_ETA'])), 0,12)."</a></td>
							</tr>
						</table>
					</div>
				</td>";
			}
			
			$xstring .= "<td style='width:50%; background:#FFFF99;' class='green'>
				<div style='padding:5px;'>
					<table style='font-family:verdana; font-size:11px;'>
						<tr>
							<td><b>Last Seen Date:</b></td>
							<td width='185'><a class='clickable2' alt='".date("M j, 'y G:i e", str2time($ship['LASTSEEN_DATE']))."' title='".date("M j, 'y G:i e", str2time($ship['LASTSEEN_DATE']))."'>".substr(date("M j, 'y G:i e", str2time($ship['LASTSEEN_DATE'])), 0,12)."</a></td>
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
							<table border='0' cellspacing='0' cellpadding='0' style='font-family:verdana; font-size:11px;'>
								<tr>
									<td valign='top'><b>Name:</b></td>
									<td valign='top'>".$ship['Ship Name']."</td>
								</tr>
								<tr>
									<td valign='top'><b>IMO:</b></td>
									<td valign='top'>".$ship['IMO #']."</td>
								</tr>
								<tr>
									<td valign='top'><b>Call Sign:</b></td>
									<td valign='top'>".getValue($xvas['data'], 'CALL_SIGN')."</td>
								</tr>
								<tr>
									<td valign='top'><b>MMSI:</b></td>
									<td valign='top'>".$ship['MMSI']."</td>
								</tr>
								<tr>
									<td valign='top'><b>Country:</b></td>
									<td valign='top'>".getValue($xvas['data'], 'FLAG')."</td>
								</tr>
								<tr>
									<td valign='top'><b>Draught:</b></td>
									<td valign='top'>".getValue($xvas['data'], 'DRAUGHT')."</td>
								</tr>
								<tr>
									<td valign='top'><b>Ship Type:</b></td>
									<td valign='top'>".$ship_type."</td>
								</tr>
								<tr>
									<td valign='top'><b>Ship Type:</b></td>
									<td valign='top'>".$ship['VESSEL TYPE']."</td>
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
									<td>".$ship['LAT']."</td>
								</tr>
								<tr>
									<td valign='top'><b>Longitude:</b></td>
									<td>".$ship['LONG']."</td>
								</tr>
								<tr>
									<td valign='top'><b>SOG:</b></td>
									<td valign='top'>".$ship['SOG']." kn</td>
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
<div id="mapdiv"></div>
<script src="http://www.openlayers.org/api/OpenLayers.js"></script>
<script>
map = new OpenLayers.Map("mapdiv");
map.addLayer(new OpenLayers.Layer.OSM());

epsg4326 =  new OpenLayers.Projection("EPSG:4326");
projectTo = map.getProjectionObject();

var lonLat = new OpenLayers.LonLat( <?php echo $ship['LONG']; ?> , <?php echo $ship['LAT']; ?> ).transform(epsg4326, projectTo);

var zoom = 3;
map.setCenter (lonLat, zoom);

var vectorLayer = new OpenLayers.Layer.Vector("Overlay");

var feature = new OpenLayers.Feature.Vector(
	new OpenLayers.Geometry.Point( <?php echo $ship['LONG']; ?> , <?php echo $ship['LAT']; ?> ).transform(epsg4326, projectTo),
	{description:"<?php echo $xstring; ?>"} ,
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