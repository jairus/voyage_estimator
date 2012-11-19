<!--FLEET POSITIONS-->
<div id="mapdialogfleet" title="MAP" style='display:none;'>
    <iframe id="mapiframefleet" name='mapname' frameborder=0 height="100%" width="100%" style='border:0px; height:100%; width:100%'></iframe>
</div>

<script type="text/javascript">
jQuery("#mapdialogfleet" ).dialog( { width: '100%', height: jQuery(window).height()*0.9 });
jQuery("#mapdialogfleet").dialog("close");

function showMapFP(){
    jQuery('#pleasewait').show();

    jQuery.ajax({
        type: 'GET',
        url: "../search_ajax3.php",
        data:  jQuery("#fleetpositions").serialize(),

        success: function(data) {
            jQuery("#mapiframefleet")[0].src='../map/index2.php';
            jQuery("#mapdialogfleet").dialog("open");
            
            jQuery('#pleasewait').hide();
        }
    });
}

function fleetPositions(){
	jQuery("#fleetpositionsdetails").hide();
	jQuery('#fleetpositionsresults').hide();

	jQuery('#pleasewait').show();

	jQuery("#sbutton").val("SEARCHING...");

	jQuery.ajax({
		type: 'GET',
		url: "../search_ajax3.php",
		data:  jQuery("#fleetpositions").serialize(),

		success: function(data) {
			jQuery("#fleetpositions_records_tab_wrapperonly").html(data);
			jQuery('#fleetpositionsresults').fadeIn(200);

			jQuery("#sbutton").val("SEARCH");
			
			jQuery('#pleasewait').hide();
		}
	});
}
</script>

<form id='fleetpositions' onsubmit="fleetPositions(); return false;">
<table width="100%" border="0" cellpadding="0" cellspacing="0" style='margin-bottom:5px;'>
    <tr>
        <td align="center">
            <table>
                <tr>
                    <td style="vertical-align:middle;"><div style="padding:2px;">MANAGER / MANAGER OWNER</div></td>
                    <td><div style="padding:2px;"><input type='text' name='operator' class='input_1' style='width:200px'></div></td>
                    <td width="50">&nbsp;</td>
                    <td style="vertical-align:middle;"><div style="padding:2px;">SHIP NAME, IMO, MMSI, CALLSIGN</div></td>
                    <td><div style="padding:2px;"><input type='text' name='ship' class='input_1' style='width:200px'></div></td>
                </tr>
                <tr>
                    <td colspan='5' style="text-align:center; border-bottom:none;"><div style="padding:2px;"><input class='searchbutton' type="button" id='sbutton' name="search" value="SEARCH" style='cursor:pointer;' onclick='fleetPositions();'  /></div></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<div id='fleetpositionsresults'>
    <div id='fleetpositions_records_tab_wrapperonly'></div>
</div>
</form>
<!--END OF FLEET POSITIONS-->