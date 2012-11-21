<?php
@session_start();
include_once(dirname(__FILE__)."/../includes/database.php");

function getValue($data, $id){

	$reg = "/<".$id.".*>(.*)<\/".$id.">/iUs";

	$matches = array();

	preg_match_all($reg, $data, $matches);

	return $matches[1][0];

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

$t = count($_SESSION['liveShipPositionReg']);

$xvassA1print = array();
for($i=0; $i<$t; $i++){
	$print = array();
	
	$xvas = $_SESSION['liveShipPositionReg'][$i];
	
	$imageb = base64_encode("http://dataservice.grosstonnage.com/S-Bisphoto.php?imo=".$xvas['xvas_imo']);
	
	$sql  = "SELECT * FROM `_xvas_shipdata` WHERE `imo`='".$xvas['xvas_imo']."'";
	$xvas2 = dbQuery($sql);
	$xvas2 = $xvas2[0];
	
	if(!trim($xvas2['data'])){
		$sql  = "SELECT * FROM `_xvas_shipdata_dry` WHERE `imo`='".$xvas['xvas_imo']."'";
		$xvas2 = dbQuery($sql);
		$xvas2 = $xvas2[0];
	}
	
	$speed = $xvas['xvas_speed'];
	if(trim($speed)){ $speed = number_format($speed, 2); }
	else{ $speed = "13.50"; }
	
	$true_heading = getValue($xvas['siitech_shippos_data'], 'TrueHeading');
	if(!trim($true_heading)){
		$true_heading = "N/A";
	}
	
	$shiptype = getValue($xvas['siitech_shipstat_data'], 'ShipType');
	if($shiptype==20){
		$ship_type = "Ship & Cargo Classification";
	}else if($shiptype==21){
		$ship_type = "Wing in ground (WIG), all ships of this type";
	}else if($shiptype==22){
		$ship_type = "Wing in ground (WIG), Hazardous category A";
	}else if($shiptype==23){
		$ship_type = "Wing in ground (WIG), Hazardous category B";
	}else if($shiptype==24){
		$ship_type = "Wing in ground (WIG), Hazardous category C";
	}else if($shiptype==25){
		$ship_type = "Wing in ground (WIG), Hazardous category D";
	}else if($shiptype==26 || $shiptype==27 || $shiptype==28 || $shiptype==29 || $shiptype==30){
		$ship_type = "Wing in ground (WIG), Reserved for future use";
	}else if($shiptype==31){
		$ship_type = "Fishing";
	}else if($shiptype==32){
		$ship_type = "Towing";
	}else if($shiptype==33){
		$ship_type = "Towing: length exceeds 200m or breadth exceeds 25m";
	}else if($shiptype==34){
		$ship_type = "Dredging or underwater ops";
	}else if($shiptype==35){
		$ship_type = "Diving ops";
	}else if($shiptype==36){
		$ship_type = "Military Ops";
	}else if($shiptype==37){
		$ship_type = "Sailing";
	}else if($shiptype==38){
		$ship_type = "Pleasure Craft";
	}else if($shiptype==39 || $shiptype==40){
		$ship_type = "Reserved";
	}else if($shiptype==41){
		$ship_type = "High speed craft (HSC), all ships of this type";
	}else if($shiptype==42){
		$ship_type = "High speed craft (HSC), Hazardous category A";
	}else if($shiptype==43){
		$ship_type = "High speed craft (HSC), Hazardous category B";
	}else if($shiptype==44){
		$ship_type = "High speed craft (HSC), Hazardous category C";
	}else if($shiptype==45){
		$ship_type = "High speed craft (HSC), Hazardous category D";
	}else if($shiptype==46 || $shiptype==47 || $shiptype==48 || $shiptype==49){
		$ship_type = "High speed craft (HSC), Reserved for future use";
	}else if($shiptype==50){
		$ship_type = "High speed craft (HSC), No additional information";
	}else if($shiptype==51){
		$ship_type = "Pilot Vessel";
	}else if($shiptype==52){
		$ship_type = "Search and Rescue vessel";
	}else if($shiptype==53){
		$ship_type = "Tug";
	}else if($shiptype==54){
		$ship_type = "Port Tender";
	}else if($shiptype==55){
		$ship_type = "Anti-pollution equipment";
	}else if($shiptype==56){
		$ship_type = "Law Enforcement";
	}else if($shiptype==57 || $shiptype==58){
		$ship_type = "Spare - Local Vessel";
	}else if($shiptype==59){
		$ship_type = "Medical Transport";
	}else if($shiptype==60){
		$ship_type = "Ship according to RR Resolution No. 18";
	}else if($shiptype==61){
		$ship_type = "Passenger, all ships of this type";
	}else if($shiptype==62){
		$ship_type = "Passenger, Hazardous category A";
	}else if($shiptype==63){
		$ship_type = "Passenger, Hazardous category B";
	}else if($shiptype==64){
		$ship_type = "Passenger, Hazardous category C";
	}else if($shiptype==65){
		$ship_type = "Passenger, Hazardous category D";
	}else if($shiptype==66 || $shiptype==67 || $shiptype==68 || $shiptype==69){
		$ship_type = "Passenger, Reserved for future use";
	}else if($shiptype==70){
		$ship_type = "Passenger, No additional information";
	}else if($shiptype==71){
		$ship_type = "Cargo, all ships of this type";
	}else if($shiptype==72){
		$ship_type = "Cargo, Hazardous category A";
	}else if($shiptype==73){
		$ship_type = "Cargo, Hazardous category B";
	}else if($shiptype==74){
		$ship_type = "Cargo, Hazardous category C";
	}else if($shiptype==75){
		$ship_type = "Cargo, Hazardous category D";
	}else if($shiptype==76 || $shiptype==77 || $shiptype==78 || $shiptype==79){
		$ship_type = "Cargo, Reserved for future use";
	}else if($shiptype==80){
		$ship_type = "Cargo, No additional information";
	}else if($shiptype==81){
		$ship_type = "Tanker, all ships of this type";
	}else if($shiptype==82){
		$ship_type = "Tanker, Hazardous category A";
	}else if($shiptype==83){
		$ship_type = "Tanker, Hazardous category B";
	}else if($shiptype==84){
		$ship_type = "Tanker, Hazardous category C";
	}else if($shiptype==85){
		$ship_type = "Tanker, Hazardous category D";
	}else if($shiptype==86 || $shiptype==87 || $shiptype==88 || $shiptype==89){
		$ship_type = "Tanker, Reserved for future use";
	}else if($shiptype==90){
		$ship_type = "Tanker, No additional information";
	}else if($shiptype==91){
		$ship_type = "Other Type, all ships of this type";
	}else if($shiptype==92){
		$ship_type = "Other Type, Hazardous category A";
	}else if($shiptype==93){
		$ship_type = "Other Type, Hazardous category B";
	}else if($shiptype==94){
		$ship_type = "Other Type, Hazardous category C";
	}else if($shiptype==95){
		$ship_type = "Other Type, Hazardous category D";
	}else if($shiptype==96 || $shiptype==97 || $shiptype==98 || $shiptype==99){
		$ship_type = "Other Type, Reserved for future use";
	}else{
		$ship_type = "Other Type, No additional information";
	}
	
	if(getValue($xvas['siitech_shipstat_data'], 'to_stern')!=""){ $stern = getValue($xvas['siitech_shipstat_data'], 'to_stern'); }
	else{ $stern = "<img style='height:15px; width:15px;' src='../images/alert1.png'; alt='No AIS Data Available' title='No AIS Data Available' />"; }
	
	if(getValue($xvas['siitech_shipstat_data'], 'to_bow')!=""){ $b2b = getValue($xvas['siitech_shipstat_data'], 'to_bow'); }
	else{ $b2b = "<img style='height:15px; width:15px;' src='../images/alert1.png'; alt='No AIS Data Available' title='No AIS Data Available' />"; }
	
	if(getValue($xvas['siitech_shipstat_data'], 'to_port')!=""){ $p2p = getValue($xvas['siitech_shipstat_data'], 'to_port'); }
	else{ $p2p = "<img style='height:15px; width:15px;' src='../images/alert1.png'; alt='No AIS Data Available' title='No AIS Data Available' />"; }
	
	if(getValue($xvas['siitech_shipstat_data'], 'to_starboard')!=""){ $starboard = getValue($xvas['siitech_shipstat_data'], 'to_starboard'); }
	else{ $starboard = "<img style='height:15px; width:15px;' src='../images/alert1.png'; alt='No AIS Data Available' title='No AIS Data Available' />"; }
	
	if(getValue($xvas['siitech_shippos_data'], 'COG')!=""){ $cog = getValue($xvas['siitech_shippos_data'], 'COG')." degrees"; }
	else{ $cog = "<img style='height:15px; width:15px;' src='../images/alert1.png'; alt='No AIS Data Available' title='No AIS Data Available' />"; }
	
	if(getValue($xvas['siitech_shippos_data'], 'maneuver')!=""){ $maneuver = getValue($xvas['siitech_shippos_data'], 'maneuver'); }
	else{ $maneuver = "<img style='height:15px; width:15px;' src='../images/alert1.png'; alt='No AIS Data Available' title='No AIS Data Available' />"; }
	
	if(getValue($xvas['siitech_shippos_data'], 'UTC')!=""){ $utc = getValue($xvas['siitech_shippos_data'], 'UTC'); }
	else{ $utc = "<img style='height:15px; width:15px;' src='../images/alert1.png'; alt='No AIS Data Available' title='No AIS Data Available' />"; }
	
	if(getValue($xvas['siitech_shippos_data'], 'NavigationalStatus')!=""){
		$nav = "<img title='".navStat(getValue($xvas['siitech_shippos_data'], 'NavigationalStatus'))."' alt='".navStat(getValue($xvas['siitech_shippos_data'], 'NavigationalStatus'))."' src='../images/".getValue($xvas['siitech_shippos_data'], 'NavigationalStatus').".png'; style='height:15px; width: 15px;' />";
	}else{
		$nav = "<img style='height:15px; width:15px;' src='../images/alert1.png'; alt='No AIS Data Available' title='No AIS Data Available' />";
	}
	
	$xstring = "
		<table width='660'>
			<tr>
				<td colspan='3' style='padding:10px 0px; font-family:verdana; font-size:10px;'><b>Data Last Updated:</b> ".date("M j, 'y G:i e", str2time($xvas['dateupdated']))."</td>
			</tr>
			<tr>";
				
				if($xvas['siitech_destination']==""){
					$xstring .= "<td style='width:50%; background:#AAFFAA;'>
						<div style='padding:5px;'>
							<table style='font-family:verdana; font-size:10px;'>
								<tr>
									<td><b>Last Seen Date:</b></td>
									<td width='185'><a class='clickable2' alt='".date("M j, 'y G:i e", str2time($xvas['siitech_lastseen']))."' title='".date("M j, 'y G:i e", str2time($xvas['siitech_lastseen']))."'>".substr(date("M j, 'y G:i e", str2time($xvas['siitech_lastseen'])), 0,11)."</a></td>
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
									<td width='185'>".$xvas['siitech_destination']."</td>
								</tr>
								<tr>
									<td><b>ETA:</b></td>
									<td><a class='clickable2' alt='".date("M j, 'y G:i e", str2time($xvas['siitech_eta']))."' title='".date("M j, 'y G:i e", str2time($xvas['siitech_eta']))."'>".substr(date("M j, 'y G:i e", str2time($xvas['siitech_eta'])), 0,11)."</a></td>
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
								<td width='185'><a class='clickable2' alt='".date("M j, 'y G:i e", str2time($xvas['siitech_lastseen']))."' title='".date("M j, 'y G:i e", str2time($xvas['siitech_lastseen']))."'>".substr(date("M j, 'y G:i e", str2time($xvas['siitech_lastseen'])), 0,11)."</a></td>
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
										<td valign='top'>".$xvas['xvas_name']."</td>
									</tr>
									<tr>
										<td valign='top'><b>IMO:</b></td>
										<td valign='top'>".$xvas['xvas_imo']."</td>
									</tr>
									<tr>
										<td valign='top'><b>Call Sign:</b></td>
										<td valign='top'>".$xvas['xvas_callsign']."</td>
									</tr>
									<tr>
										<td valign='top'><b>MMSI:</b></td>
										<td valign='top'>".$xvas['xvas_mmsi']."</td>
									</tr>
									<tr>
										<td valign='top'><b>Country:</b></td>
										<td valign='top'>".getValue($xvas2['data'], 'FLAG')."</td>
									</tr>
									<tr>
										<td valign='top'><b>Draught:</b></td>
										<td valign='top'>".getValue($xvas2['data'], 'DRAUGHT')."</td>
									</tr>
									<tr>
										<td valign='top'><b>Ship Type:</b></td>
										<td valign='top'>".$ship_type."</td>
									</tr>
									<tr>
										<td valign='top'><b>Ship Type:</b></td>
										<td valign='top'>".$xvas['xvas_vessel_type']."</td>
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
										<td>".$xvas['siitech_latitude']."</td>
									</tr>
									<tr>
										<td valign='top'><b>Longitude:</b></td>
										<td>".$xvas['siitech_longitude']."</td>
									</tr>
									<tr>
										<td valign='top'><b>SOG:</b></td>
										<td valign='top'>".getValue($xvas['siitech_shippos_data'], 'SOG')." kn</td>
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
	
	$print['xvas_vessel_type']  = $xvas['xvas_vessel_type'];
	$print['siitech_latitude']  = $xvas['siitech_latitude'];
	$print['siitech_longitude'] = $xvas['siitech_longitude'];
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