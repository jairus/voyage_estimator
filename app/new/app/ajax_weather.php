<link rel="stylesheet" href="js/development-bundle/themes/base/jquery.ui.all.css">
<script type="text/javascript" src="js/development-bundle/ui/jquery.ui.core.js"></script>
<script type="text/javascript" src="js/development-bundle/ui/jquery.ui.widget.js"></script>
<script type="text/javascript" src="js/development-bundle/ui/jquery.ui.dialog.js"></script>

<!--WEATHER-->
<table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td align="center"><div style='padding:3px;'><a onclick="showMap();" class="clickable">view larger map</a></div></td>
    </tr>
    <tr style='background:#999;'>
        <td align="center"><div style='padding:3px;'><iframe src="http://map.openseamap.org/map/weather.php" id="map_iframe" width="100%" height="500" frameborder="0"></iframe></div></td>
    </tr>
</table>

<div id="mapdialog" title="MAP" style="display:none;">
    <iframe id="mapiframe" name="mapname" frameborder="0" height="100%" width="100%"></iframe>
</div>

<script type="text/javascript">
jQuery("#mapdialog").dialog( { autoOpen: false, width: '99%', height: jQuery(window).height()*0.9 } );
jQuery("#mapdialog").dialog("close");

function showMap(){
    jQuery("#mapiframe")[0].src = 'http://map.openseamap.org/map/weather.php';
    jQuery("#mapdialog").dialog("open");
}
</script>
<!--END OF WEATHER-->