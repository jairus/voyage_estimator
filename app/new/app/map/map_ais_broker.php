<?php
@session_start();

include_once(dirname(__FILE__)."/../includes/bootstrap.php");

$details = trim(base64_decode($_GET['details']));
$details = unserialize($details);

$ship = $_SESSION[$details['a']][$details['id']];
$portid = $details['portid'];
$port_name = $ship['siitech_destination'];
$port_latitude = $details['port_latitude'];
$port_longitude = $details['port_longitude'];
$eta = $ship['siitech_eta'];
$destination_eta = date("M j, 'y G:i e", str2time($eta));

//DESTINATION PORT
if(trim($port_name) && trim($port_latitude) && trim($port_longitude)){
	$string_destination = "
		<style>
		.loadport .in td{
			font-family: verdana; font-size:11px;
		}
	
		.loadport .in td.label{
			font-weight:bold;
			width:150px;
		}
		</style>
	
		<table width='400' class='loadport'>
			<tr>		
				<td style='font-family: verdana; font-size:14px; font-weight:bold; background:#c5dc3b; color:black;'>DESTINATION: ".$port_name."</td>
			</tr>
			<tr>		
				<td>
					<table class='in'>
						<tr>
							<td class='label'>Latitude</td>
							<td valign='top'>".$port_latitude."</td>
						</tr>
						<tr>
							<td class='label'>Longitude</td>
							<td valign='top'>".$port_longitude."</td>			
						</tr>
						<tr>
							<td class='label'>Destination ETA</td>
							<td valign='top'>".$destination_eta."</td>			
						</tr>
					</table>
				</td>		
			</tr>
		</table>";
		
	$string_destination = str_replace("\n", "", $string_destination);
	$string_destination = str_replace("\r", "", $string_destination);
}
//END OF DESTINATION PORT

//SHIP
if($eta=="" || $eta==0 || $eta=="0000-00-00 00:00:00"){ $eta = "<img style='height:15px; width:15px;' src='../images/alert1.png'; alt='No AIS Data Available' title='No AIS Data Available' />"; }
else{ $eta = date("M j, 'y G:i e", str2time($eta)); }

$siitech_lastseen = $ship['siitech_lastseen'];
if($siitech_lastseen=="" || $siitech_lastseen==0 || $siitech_lastseen=="0000-00-00 00:00:00"){ $siitech_lastseen = "<img style='height:15px; width:15px;' src='../images/alert1.png'; alt='No AIS Data Available' title='No AIS Data Available' />"; }
else{ $siitech_lastseen = date("M j, 'y G:i e", str2time($ship['siitech_lastseen'])); }

$imo = $ship['xvas_imo'];
$imageb = base64_encode("http://dataservice.grosstonnage.com/S-Bisphoto.php?imo=".$imo);

$sql  = "SELECT * FROM `_xvas_shipdata_dry` WHERE `imo`='".$imo."'";
$xvas = dbQuery($sql);
$xvas = $xvas[0];

$ship_name = getValue($xvas['data'], 'NAME');
$callsign = $ship['xvas_callsign'];
$mmsi = $ship['xvas_mmsi'];

$xvasflag = getValue($xvas['data'], 'LAST_KNOWN_FLAG');
if(!trim($xvasflag)){
	$xvasflag = getValue($xvas['data'], 'FLAG');
}
$xvasflag_img = getFlagImage($xvasflag);

$draught = getValue($ship['siitech_shipstat_data'], 'draught');
$ship_type = $ship['xvas_vessel_type'];

$stern = getValue($ship['siitech_shipstat_data'], 'to_stern');
if($stern=="" || $stern==0){ $stern = "<img style='height:15px; width:15px;' src='../images/alert1.png'; alt='No AIS Data Available' title='No AIS Data Available' />"; }

$beam = getValue($ship['siitech_shipstat_data'], 'Beam');
if($stern=="" || $stern==0){ $stern = "<img style='height:15px; width:15px;' src='../images/alert1.png'; alt='No AIS Data Available' title='No AIS Data Available' />"; }

$ship_latitude = $ship['siitech_latitude'];
$ship_longitude = $ship['siitech_longitude'];

$speed = $ship['xvas_speed'];
if(trim($speed)){ $speed = number_format($speed, 2); }
else{ $speed = "13.50"; }

$speed_ais = getValue($ship['siitech_shipstat_data'], 'speed_ais');
if(trim($speed_ais)){ $speed_ais = number_format($speed_ais, 2); }
else{ $speed_ais = "13.50"; }

$true_heading = getValue($ship['siitech_shippos_data'], 'TrueHeading');
$true_heading2 = getValue($ship['siitech_shippos_data'], 'TrueHeading');
if(!trim($true_heading)){
	$true_heading = "N/A";
}

$utc = getValue($ship['siitech_shippos_data'], 'UTC');
if($utc=="" || $utc==0){ $utc = "<img style='height:15px; width:15px;' src='../images/alert1.png'; alt='No AIS Data Available' title='No AIS Data Available' />"; }
else{ $utc = date("M j, 'y G:i e", str2time($utc)); }

$nav = trim(getValue($ship['siitech_shippos_data'], 'NavigationalStatus'));
if($nav==""){ $nav = "<img style='height:15px; width:15px;' src='../images/alert1.png'; alt='No AIS Data Available' title='No AIS Data Available' />"; }

$string = "
	<table width='600'>
		<tr>
			<td style='width:50%; background:#92d050;'>
				<div style='padding:5px;'>
					<table style='font-family:verdana; font-size:10px;'>
						<tr>
							<td width='100'><b>DESTINATION:</b></td>
							<td>".$port_name."</td>
						</tr>
						<tr>
							<td><b>ETA:</b></td>
							<td>".$eta."</td>
						</tr>
					</table>
				</div>
			</td>
			<td style='width:50%; background:#ffff00;'>
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
								<td valign='top' width='130'><b>Name:</b></td>
								<td valign='top'>".$ship_name."</td>
							</tr>
							<tr>
								<td valign='top'><b>IMO:</b></td>
								<td valign='top'>".$imo."</td>
							</tr>
							<tr>
								<td valign='top'><b>Call Sign:</b></td>
								<td valign='top'>".$callsign."</td>
							</tr>
							<tr>
								<td valign='top'><b>MMSI:</b></td>
								<td valign='top'>".$mmsi."</td>
							</tr>
							<tr>
								<td valign='top'><b>Flag:</b></td>
								<td valign='top'><img alt='".htmlentities($xvasflag)."' title='".htmlentities($xvasflag)."' src='../".$xvasflag_img."' width='22' height='15' /></td>
							</tr>
							<tr>
								<td valign='top'><b>Draught:</b></td>
								<td valign='top'>".$draught."</td>
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
								<td>".$ship_latitude."</td>
							</tr>
							<tr>
								<td valign='top'><b>Longitude:</b></td>
								<td>".$ship_longitude."</td>
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
	
$string = str_replace("\n", "", $string);
$string = str_replace("\r", "", $string);
//END OF SHIP
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
<div style="width:100%; height:20px; background-color:#06F; color:#FFF; font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:bold; text-align:center; padding:10px 0px; z-index:1;">FOR SECURITY THE ROUTE LINES AND ACTUAL POSITION ARE ONLY PICTORIAL. THEY DO NOT REPRESENT THE SHIP ROUTE OR THE EXACT SHIP LOCATION.</div>
<div id="mapdiv"></div>
<script src="http://www.openlayers.org/api/OpenLayers.js"></script>
<script>
map = new OpenLayers.Map("mapdiv");
map.addLayer(new OpenLayers.Layer.OSM());

epsg4326 =  new OpenLayers.Projection("EPSG:4326");
projectTo = map.getProjectionObject();

var lonLat = new OpenLayers.LonLat( <?php echo $ship['siitech_longitude']; ?>, <?php echo $ship['siitech_latitude']; ?> ).transform(epsg4326, projectTo);
var zoom = 3;
map.setCenter (lonLat, zoom);

var vectorLayer = new OpenLayers.Layer.Vector("Overlay");

//DESTINATION PORT
<?php if(trim($port_name) && trim($port_latitude) && trim($port_longitude)){ ?>
	var feature = new OpenLayers.Feature.Vector(
		new OpenLayers.Geometry.Point( <?php echo $port_longitude; ?>, <?php echo $port_latitude; ?> ).transform(epsg4326, projectTo),
		{description:"<?php echo $string_destination; ?>"} ,
		{externalGraphic: 'openport.png', graphicHeight: 45, graphicWidth: 65, graphicXOffset:-12, graphicYOffset:-25  }
	);    
	vectorLayer.addFeatures(feature);
<?php } ?>
//END OF DESTINATION PORT

<?php
if(trim(getValue($ship['siitech_shippos_data'], 'TrueHeading'))){
	$true_heading2 = str_replace(' degrees', '', getValue($ship['siitech_shippos_data'], 'TrueHeading'));
}else{
	$true_heading2 = 0;
}
?>

//SHIP
var feature = new OpenLayers.Feature.Vector(
	new OpenLayers.Geometry.Point( <?php echo $ship['siitech_longitude']; ?>, <?php echo $ship['siitech_latitude']; ?> ).transform(epsg4326, projectTo),
	{description:"<?php echo $string; ?>"} ,
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
	?>', graphicHeight: 45, graphicWidth: 45, graphicXOffset:-12, graphicYOffset:-25  }
);    
vectorLayer.addFeatures(feature);
//END OF SHIP

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