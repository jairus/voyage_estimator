<?php
@session_start();
@include_once(dirname(__FILE__)."/../includes/database.php");

function getValue($data, $id){
	$reg = "/<".$id.".*>(.*)<\/".$id.">/iUs";

	$matches = array();

	preg_match_all($reg, $data, $matches);

	return $matches[1][0];
}

$sql = "SELECT * FROM _sbis_piracy_alerts ORDER BY dateadded DESC LIMIT 0,10";
$data = dbQuery($sql);
$t = count($data);

$shipsAprint = array();
for($i1=0; $i1<$t; $i1++){
	if($data[$i1]['alert']!=$data[$i1-1]['alert']){
		$lines = explode("<ALERT>", $data[$i1]['alert']);
		
		if($lines){
			$t_patheships = array();
			
			$i_pa = 1;
			foreach($lines as $line){
				if(getValue($lines[$i_pa], 'TEXT')!=""){
					$print = array();
					
					$print['date'] = date("M d, Y G:i:s", strtotime(getValue($lines[$i_pa], 'DATE')));
					$print['lat']  = getValue($lines[$i_pa], 'LATITUDE');
					$print['long'] = getValue($lines[$i_pa], 'LONGITUDE');
					$print['text'] = getValue($lines[$i_pa], 'TEXT');
					
					$xstring = "
						<table width='400' style='font-family:verdana; font-size:11px;'>
							<tr>
								<td style='width:50%; background:#AAFFAA;' class='green'><div style='padding:3px;'><b>Latitude: </b>".$print['lat']."</div></td>
								<td style='width:50%; background:#AAFFAA;' class='green'><div style='padding:3px;'><b>Longitude: </b>".$print['long']."</div></td>
							<tr>
							<tr>
								<td colspan='2' style='width:100%; background:#AAFFAA;' class='green'><div style='padding:3px;'><b>".$print['date']."</b></div></td>
							<tr>
							<tr>
								<td colspan='2' style='width:100%; background:#FFFF99;' class='green'><div style='padding:3px;'>".addslashes($print['text'])."</div></td>
							<tr>
						</table>";
						
					$xstring = str_replace("\n", "", $xstring);
					$xstring = str_replace("\r", "", $xstring);
					
					$print['xstring'] = $xstring;
					
					$t_patheships[] = $print;
				}
				
				$i_pa++;
			}
		}
	}
	
	$shipsAprint[] = $t_patheships;
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
$t_pa = count($shipsAprint);

for($i_pa=0; $i_pa<$t_pa; $i_pa++){
    $piracy_alert_ship = $shipsAprint[$i_pa];
    
    ?> var lonLat = new OpenLayers.LonLat( <?php echo $piracy_alert_ship[0]['long']; ?>, <?php echo $piracy_alert_ship[0]['lat']; ?> ).transform(epsg4326, projectTo); <?php
}
?>

var zoom = 3;
map.setCenter (lonLat, zoom);

var vectorLayer = new OpenLayers.Layer.Vector("Overlay");

<?php
for($i_pa=0; $i_pa<$t_pa; $i_pa++){
    $piracy_alert_ship = $shipsAprint[$i_pa];
    
    $t_pa1 = count($piracy_alert_ship);
    
    for($i_pa1=0; $i_pa1<$t_pa1; $i_pa1++){
        $piracy_alert_ship1 = $piracy_alert_ship[$i_pa1];
        
        ?>
        var feature = new OpenLayers.Feature.Vector(
            new OpenLayers.Geometry.Point( <?php echo $piracy_alert_ship1['long']; ?>, <?php echo $piracy_alert_ship1['lat']; ?> ).transform(epsg4326, projectTo),
            {description:"<?php echo $piracy_alert_ship1['xstring']; ?>"} ,
            {externalGraphic: 'icons/skull.png', graphicHeight: 25, graphicWidth: 25, graphicXOffset:-12, graphicYOffset:-25  }
        );    
        vectorLayer.addFeatures(feature);
        <?php
    }
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