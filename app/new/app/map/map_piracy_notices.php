<?php
@session_start();

$xstring = "
	<table width='400' style='font-family:verdana; font-size:11px;'>
		<tr>
			<td style='width:50%; background:#AAFFAA;' class='green'><div style='padding:3px;'><b>Latitude: </b>".$_GET['lat']."</div></td>
			<td style='width:50%; background:#AAFFAA;' class='green'><div style='padding:3px;'><b>Longitude: </b>".$_GET['long']."</div></td>
		<tr>
		<tr>
			<td colspan='2' style='width:100%; background:#AAFFAA;' class='green'><div style='padding:3px;'><b>".$_GET['date']."</b></div></td>
		<tr>
		<tr>
			<td colspan='2' style='width:100%; background:#FFFF99;' class='green'><div style='padding:3px;'>".addslashes($_GET['text'])."</div></td>
		<tr>
	</table>";
	
$xstring = str_replace("\n", "", $xstring);
$xstring = str_replace("\r", "", $xstring);
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

var lonLat = new OpenLayers.LonLat( <?php echo $_GET['long']; ?> , <?php echo $_GET['lat']; ?> ).transform(epsg4326, projectTo);

var zoom = 3;
map.setCenter (lonLat, zoom);

var vectorLayer = new OpenLayers.Layer.Vector("Overlay");

var feature = new OpenLayers.Feature.Vector(
	new OpenLayers.Geometry.Point( <?php echo $_GET['long']; ?> , <?php echo $_GET['lat']; ?> ).transform(epsg4326, projectTo),
	{description:"<?php echo $xstring; ?>"} ,
	{externalGraphic: 'icons/skull.png', graphicHeight: 25, graphicWidth: 25, graphicXOffset:-12, graphicYOffset:-25  }
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