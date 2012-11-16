<?php @include_once(dirname(__FILE__)."/includes/bootstrap.php"); ?>

<link rel="stylesheet" href="js/development-bundle/themes/base/jquery.ui.all.css">
<script type="text/javascript" src="js/development-bundle/ui/jquery.ui.core.js"></script>
<script type="text/javascript" src="js/development-bundle/ui/jquery.ui.widget.js"></script>
<script type="text/javascript" src="js/development-bundle/ui/jquery.ui.dialog.js"></script>

<!--PIRACY NOTICES-->
<div id="mapdialogpiracyalert" title="MAP - PIRACY NOTICE" style="display:none;">
    <iframe id="mapiframepiracyalert" name="mapname_single" frameborder="0" height="100%" width="100%"></iframe>
</div>

<script type="text/javascript">
jQuery("#mapdialogpiracyalert").dialog( { autoOpen: false, width: '99%', height: jQuery(window).height()*0.9 } );
jQuery("#mapdialogpiracyalert").dialog("close");

function openMapPiracyAlert(date, lat, long, text){
	jQuery("#mapiframepiracyalert")[0].src='map/map_piracy_notices.php?date='+date+'&lat='+lat+'&long='+long+'&text='+text;
	jQuery("#mapdialogpiracyalert").dialog("open");
}
</script>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td align="center" colspan="2"><div style='padding:3px;'><a onclick="showMap();" class="clickable">view larger map</a></div></td>
	</tr>
	<tr style='background:#999;'>
		<td align="center" colspan="2"><div style='padding:3px;'><iframe src='map/map_piracy_notices_all.php' id="map_iframe" width='100%' height='500' frameborder="0"></iframe></div></td>
	</tr>
	<tr style='background:#666;'>
		<th width="200" align="left"><div style='padding:3px;'>DATE</div></th>
		<th><div style='padding:3px;'>ALERT</div></th>
	</tr>
	
	<?php
	$sql = "SELECT * FROM _sbis_piracy_alerts ORDER BY dateadded DESC LIMIT 0,10";
	$data = dbQuery($sql);
	$t = count($data);

	for($i1=0; $i1<$t; $i1++){
		if($data[$i1]['alert']!=$data[$i1-1]['alert']){
			$lines = explode("<ALERT>", $data[$i1]['alert']);
			
			if($lines){
				$i = 1;
				foreach($lines as $line){
					if(getValue($lines[$i], 'TEXT')!=""){
						echo "<tr style='background:#e5e5e5;'>
							<td align='left'><div style='padding:3px;'>".date("M d, Y", strtotime(getValue($lines[$i], 'DATE')))."</div></td>
							<td align='left'><div style='padding:3px;'><a onclick='openMapPiracyAlert(\"".date("M d, Y G:i:s", strtotime(getValue($lines[$i], 'DATE')))." UTC\", \"".getValue($lines[$i], 'LATITUDE')."\", \"".getValue($lines[$i], 'LONGITUDE')."\", \"".addslashes(getValue($lines[$i], 'TEXT'))."\")' class='clickable'>".getValue($lines[$i], 'TEXT')."</a></div></td>
						</tr>";
					}
					
					$i++;
				}
			}
		}
	}
	?>
	
</table>

<div id="mapdialog" title="MAP" style="display:none;">
    <iframe id="mapiframe" name="mapname" frameborder="0" height="100%" width="100%"></iframe>
</div>

<script type="text/javascript">
jQuery("#mapdialog").dialog( { autoOpen: false, width: '99%', height: jQuery(window).height()*0.9 } );
jQuery("#mapdialog").dialog("close");

function showMap(){
    jQuery("#mapiframe")[0].src = 'map/map_piracy_notices_all.php';
    jQuery("#mapdialog").dialog("open");
}
</script>
<!--END OF PIRACY NOTICES-->