<?php
include_once(dirname(__FILE__)."/../includes/bootstrap.php");
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
<link href="http://code.google.com/apis/maps/documentation/javascript/examples/default.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
var map;
var zonebgcolor;
function putBox(pointy, pointx, inc){
	var zoneCoords = [
	
			new google.maps.LatLng(pointy, pointx),
			new google.maps.LatLng(pointy, (pointx+inc)),
			new google.maps.LatLng((pointy-inc), (pointx+inc)),
			new google.maps.LatLng((pointy-inc), pointx)
		];
		
		zone = new google.maps.Polygon({
		paths: zoneCoords,
		strokeColor: zonebgcolor,
		strokeOpacity: 0.3,
		strokeWeight: 1,
		fillColor: zonebgcolor,
		fillOpacity: 0.3
	  });
	  zone.setMap(map);
}
function initialize() {
    var myLatLng = new google.maps.LatLng(0, 0);
	var myOptions = {
      zoom: 2,
	  center: myLatLng,
      mapTypeId: google.maps.MapTypeId.HYBRID 
    }

   map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

	<?php
	
	$zone = $_GET['zone'];
	$zones = explode(",", $zone);
	if($zones[0]){
		$ztx = count($zones);
		for($zix=0; $zix<$ztx; $zix++){	
			$zone = $zones[$zix];
			if(trim($zone)!=""){
				//$zone = 6;
				$sql = "select `b`.`zone_bgcolor`,  `a`.* from `_sbis_zoneblocks` as `a`, `_ts_zone` as `b` where `a`.`zone_code`='".$zone."' and `a`.`zone_code`=`b`.`zone_code`";
				$zonearr = dbQuery($sql);
				$zt = count($zonearr);
			}	
			if($zt){	
				echo "zonebgcolor='".$zonearr[0]['zone_bgcolor']."';\n";			
				for($i=0; $i<$zt; $i++){
					echo "\nputBox(".$zonearr[$i]['lat1'].", ".$zonearr[$i]['long1'].", ".abs($zonearr[$i]['long1']-$zonearr[$i]['long2']).");";
				}
			}
		}
	}
	?>  
}

</script>
</head>
<body onLoad="initialize()">
  <div id="map_canvas"></div>
</body>

</html>
