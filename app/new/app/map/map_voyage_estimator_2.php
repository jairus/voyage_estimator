<?php
$dbhost = 's-bis.cfclysrb91of.us-east-1.rds.amazonaws.com';
$dbuser = 'sbis';
$dbpass = 'roysbis';
$dbname = 'sbis';

$conn = mysql_connect($dbhost,$dbuser,$dbpass) or die('Error connecting to mysql');
mysql_select_db($dbname);

function getValue($data, $id){

	$reg = "/<".$id.".*>(.*)<\/".$id.">/iUs";

	$matches = array();

	preg_match_all($reg, $data, $matches);

	return $matches[1][0];
}

$sql = mysql_query("SELECT * FROM `_xvas_siitech_cache` WHERE `xvas_imo`='".$_GET['imo']."' ORDER BY `dateupdated` DESC LIMIT 0,1");
$r_count = mysql_num_rows($sql);

if($r_count!=0){
	$r = mysql_fetch_assoc($sql);
	
	$true_heading = getValue($r['siitech_shippos_data'], 'TrueHeading');
}
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

var lonLat = new OpenLayers.LonLat( <?php echo $r['siitech_longitude']; ?>, <?php echo $r['siitech_latitude']; ?> ).transform(epsg4326, projectTo);

var zoom = 3;
map.setCenter (lonLat, zoom);

var vectorLayer = new OpenLayers.Layer.Vector("Overlay");

var feature = new OpenLayers.Feature.Vector(
	new OpenLayers.Geometry.Point( <?php echo $r['siitech_longitude']; ?>, <?php echo $r['siitech_latitude']; ?> ).transform(epsg4326, projectTo),
	{description:"Ship Position"} ,
	{externalGraphic: 'http://www.s-bisonline.com/app/map/icons/<?php
		if(0>=$true_heading){
			echo 'ship0.png';
		}else if(15>=$true_heading){
			echo 'ship15.png';
		}else if(30>=$true_heading){
			echo 'ship30.png';
		}else if(45>=$true_heading){
			echo 'ship45.png';
		}else if(60>=$true_heading){
			echo 'ship60.png';
		}else if(75>=$true_heading){
			echo 'ship75.png';
		}else if(90>=$true_heading){
			echo 'ship90.png';
		}else if(105>=$true_heading){
			echo 'ship105.png';
		}else if(120>=$true_heading){
			echo 'ship120.png';
		}else if(135>=$true_heading){
			echo 'ship135.png';
		}else if(150>=$true_heading){
			echo 'ship150.png';
		}else if(165>=$true_heading){
			echo 'ship165.png';
		}else if(180>=$true_heading){
			echo 'ship180.png';
		}else if(195>=$true_heading){
			echo 'ship195.png';
		}else if(210>=$true_heading){
			echo 'ship210.png';
		}else if(225>=$true_heading){
			echo 'ship225.png';
		}else if(240>=$true_heading){
			echo 'ship240.png';
		}else if(255>=$true_heading){
			echo 'ship255.png';
		}else if(270>=$true_heading){
			echo 'ship270.png';
		}else if(285>=$true_heading){
			echo 'ship285.png';
		}else if(300>=$true_heading){
			echo 'ship300.png';
		}else if(315>=$true_heading){
			echo 'ship315.png';
		}else if(330>=$true_heading){
			echo 'ship330.png';
		}else if(345>=$true_heading){
			echo 'ship345.png';
		}else if(360>=$true_heading){
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