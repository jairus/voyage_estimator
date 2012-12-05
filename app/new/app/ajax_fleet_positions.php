<!--FLEET POSITIONS-->
<link rel="stylesheet" href="js/development-bundle/themes/base/jquery.ui.all.css">
<script type="text/javascript" src="js/development-bundle/ui/jquery.ui.core.js"></script>
<script type="text/javascript" src="js/development-bundle/ui/jquery.ui.widget.js"></script>
<script type="text/javascript" src="js/development-bundle/ui/jquery.ui.dialog.js"></script>

<div id="mapdialogfleet" title="MAP" style='display:none;'>
    <iframe id="mapiframefleet" name='mapname' frameborder=0 height="100%" width="100%" style='border:0px; height:100%; width:100%'></iframe>
</div>

<script type="text/javascript">
function showShipDetails(imo){
	jQuery("#shipdetails").dialog("close")
	jQuery('#pleasewait').show();

	jQuery.ajax({
		type: 'POST',
		url: "search_ajax1ve.php?imo="+imo,
		data:  '',

		success: function(data) {
			if(data.indexOf("<b>ERROR")!=0){
				jQuery("#shipdetails_in").html(data);
				jQuery("#shipdetails").dialog("open")
				jQuery('#pleasewait').hide();
			}else{
				alert(data)
			}
		}
	});	
}

function ownerDetails(owner, owner_id){
	var iframe = $("#contactiframe");

	$(iframe).contents().find("body").html("");

	jQuery("#contactiframe")[0].src='search_ajax1ve.php?contact=1&owner='+owner+'&owner_id='+owner_id;
	jQuery("#contactdialog").dialog("open");
}

jQuery("#mapdialogfleet" ).dialog( { autoOpen: false, width: '100%', height: jQuery(window).height()*0.9 });
jQuery("#mapdialogfleet").dialog("close");

function showMapFP(){
    jQuery('#pleasewait').show();

    jQuery.ajax({
        type: 'GET',
        url: "search_ajax3ve.php",
        data:  jQuery("#fleetpositions").serialize(),

        success: function(data) {
            jQuery("#mapiframefleet")[0].src='map/index2.php';
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
		url: "search_ajax3ve.php",
		data:  jQuery("#fleetpositions").serialize(),

		success: function(data) {
			jQuery("#fleetpositions_records_tab_wrapperonly").html(data);
			jQuery('#fleetpositionsresults').fadeIn(200);

			jQuery("#sbutton").val("SEARCH");
			
			jQuery('#pleasewait').hide();
		}
	});
}

jQuery("#shipdetails").dialog( { autoOpen: false, width: '90%', height: jQuery(window).height()*0.9 });
jQuery("#shipdetails").dialog("close");

jQuery("#contactdialog").dialog( { autoOpen: false, width: 900, height: 460 });
jQuery("#contactdialog").dialog("close");
</script>

<div id="shipdetails" title="SHIP DETAILS" style='display:none;'>
	<div id='shipdetails_in'></div>
</div>

<div id="contactdialog" title="CONTACT"  style='display:none'>
	<iframe id='contactiframe' frameborder="0" height="100%" width="100%"></iframe>
</div>

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