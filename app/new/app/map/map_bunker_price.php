<?php
@session_start();
include_once(dirname(__FILE__)."/../includes/database.php");

$sql1 = "SELECT DISTINCT(`port_name`) FROM `bunker_price` ORDER BY `dateupdated` DESC";
$ports = dbQuery($sql1, $link);

$t = count($ports);

$bunker_ports = array();
for($i=0; $i<$t; $i++){
	$sql2 = "SELECT name, latitude, longitude FROM `_veson_ports` WHERE name='".trim($ports[$i]['port_name'])."' ORDER BY `id` DESC LIMIT 0,1";
	$sbis_port = dbQuery($sql2, $link);
	
	if(trim($sbis_port[0]['name']) && trim($sbis_port[0]['latitude']) && trim($sbis_port[0]['longitude'])){
		$print = array();
	
		$port_latitude = $sbis_port[0]['latitude'];
		$port_longitude = $sbis_port[0]['longitude'];
		
		$sql3 = "SELECT * FROM `bunker_price` WHERE `port_name`='".trim($sbis_port[0]['name'])."' ORDER BY `dateupdated` DESC";
		$r1 = dbQuery($sql3, $link);
		
		$xstring = "<table width='300' border='0' cellspacing='0' cellpadding='0' style='font-family:verdana; font-size:11px;'>
			<tr>
				<td valign='top'><b>Port Code:</b></td>
				<td valign='top'>".$r1[0]['port_code']."</td>
			</tr>
			<tr>
				<td valign='top'><b>Port Name:</b></td>
				<td valign='top'>".$r1[0]['port_name']."</td>
			</tr>
			<tr>
				<td valign='top'><b>Port Latitude:</b></td>
				<td valign='top'>".number_format($port_latitude, 2, '.', '')."</td>
			</tr>
			<tr>
				<td valign='top'><b>Port Longitude:</b></td>
				<td valign='top'>".number_format($port_longitude, 2, '.', '')."</td>
			</tr>";
			
			$t1 = count($r1); 
			
			for($i1=0; $i1<$t1; $i1++){
				$xstring .= "<tr>
					<td valign='top'><b>".$r1[$i1]['grade'].":</b></td>
					<td valign='top'>".$r1[$i1]['average_price']."</td>
				</tr>";
			}
			
		$xstring .= "</table>";
			
		$xstring = str_replace("\n", "", $xstring);
		$xstring = str_replace("\r", "", $xstring);

		$print['port_latitude'] = $port_latitude;
		$print['port_longitude'] = $port_longitude;
		$print['xstring'] = $xstring;
		
		$bunker_ports[] = $print;
	}
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
	font-size:11px;
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
$t1 = count($bunker_ports);

for($i1=0; $i1<$t1; $i1++){
    ?> var lonLat = new OpenLayers.LonLat( <?php echo $bunker_ports[0]['port_longitude']; ?>, <?php echo $bunker_ports[0]['port_latitude']; ?> ).transform(epsg4326, projectTo); <?php
}
?>

var zoom = 5;
map.setCenter (lonLat, zoom);

var vectorLayer = new OpenLayers.Layer.Vector("Overlay");

<?php
for($i1=0; $i1<$t1; $i1++){
    ?>
	var feature = new OpenLayers.Feature.Vector(
		new OpenLayers.Geometry.Point( <?php echo $bunker_ports[$i1]['port_longitude']; ?>, <?php echo $bunker_ports[$i1]['port_latitude']; ?> ).transform(epsg4326, projectTo),
		{description:"<?php echo $bunker_ports[$i1]['xstring']; ?>"} ,
		{externalGraphic: 'icon_oilbarrel.png', graphicHeight: 30, graphicWidth: 30, graphicXOffset:-12, graphicYOffset:-25  }
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