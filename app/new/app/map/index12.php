<?php
@session_start();
include_once(dirname(__FILE__)."/../includes/database.php");

$prices = $_SESSION['prices'];

$t = count($prices);

$bunker_price = array();
for($i=0; $i<$t; $i++){
	$print = array();
	
	$xstring = "
		<table width='300' border='0' cellspacing='0' cellpadding='0' style='font-family:verdana; font-size:11px;'>
			<tr>
				<td valign='top'><b>Port Code:</b></td>
				<td valign='top'>".$prices[$i]['port_code']."</td>
			</tr>
			<tr>
				<td valign='top'><b>Port Name:</b></td>
				<td valign='top'>".$prices[$i]['port_name']."</td>
			</tr>";
			
			$sql = "SELECT latitude, longitude FROM `_veson_ports` WHERE name='".trim($prices[$i]['port_name'])."' ORDER BY `id` DESC LIMIT 0,1";
			$sbis_port = dbQuery($sql, $link);
			$sbis_port = $sbis_port[0];
			
			$xstring .= "<tr>
				<td valign='top'><b>Port Latitude:</b></td>
				<td valign='top'>".number_format($sbis_port['latitude'], 2, '.', '')."</td>
			</tr>
			<tr>
				<td valign='top'><b>Port Longitude:</b></td>
				<td valign='top'>".number_format($sbis_port['longitude'], 2, '.', '')."</td>
			</tr>";
			
			//for($i=0;$i<$t;$i++){
				$xstring .= "<tr>
					<td valign='top'><b>".$prices[$i]['grade'].":</b></td>
					<td valign='top'>".$prices[$i]['average_price']."</td>
				</tr>";
			//}
			
		echo "</table>";
		
	$xstring = str_replace("\n", "", $xstring);
	$xstring = str_replace("\r", "", $xstring);
	
	$print['xstring'] = $xstring;
	$print['port_latitude'] = number_format($sbis_port['latitude'], 2, '.', '');
	$print['port_longitude'] = number_format($sbis_port['longitude'], 2, '.', '');
	
	$bunker_price[] = $print;
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
$t1 = count($bunker_price);

for($i1=0; $i1<$t1; $i1++){
    ?> var lonLat = new OpenLayers.LonLat( <?php echo $bunker_price[0]['port_longitude']; ?>, <?php echo $bunker_price[0]['port_latitude']; ?> ).transform(epsg4326, projectTo); <?php
}
?>

var zoom = 5;
map.setCenter (lonLat, zoom);

var vectorLayer = new OpenLayers.Layer.Vector("Overlay");

<?php
for($i1=0; $i1<$t1; $i1++){
    ?>
	var feature = new OpenLayers.Feature.Vector(
		new OpenLayers.Geometry.Point( <?php echo $bunker_price[$i1]['port_longitude']; ?>, <?php echo $bunker_price[$i1]['port_latitude']; ?> ).transform(epsg4326, projectTo),
		{description:"<?php echo $bunker_price[$i1]['xstring']; ?>"} ,
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