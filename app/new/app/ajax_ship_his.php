<!--SHIP HISTORY-->
<link rel="stylesheet" href="js/development-bundle/themes/base/jquery.ui.all.css">
<script type="text/javascript" src="js/development-bundle/ui/jquery.ui.core.js"></script>
<script type="text/javascript" src="js/development-bundle/ui/jquery.ui.widget.js"></script>
<script type="text/javascript" src="js/development-bundle/ui/jquery.ui.dialog.js"></script>

<script type='text/javascript' src='js/jquery-autocomplete/lib/jquery.bgiframe.min.js'></script>
<script type='text/javascript' src='js/jquery-autocomplete/lib/jquery.ajaxQueue.js'></script>
<script type='text/javascript' src='js/jquery-autocomplete/lib/thickbox-compressed.js'></script>
<script type='text/javascript' src='js/jquery-autocomplete/jquery.autocomplete.js'></script>
<script type='text/javascript' src='js/autoVessel.php'></script>
<link rel="stylesheet" type="text/css" href="js/jquery-autocomplete/jquery.autocomplete.css" />
<link rel="stylesheet" type="text/css" href="js/jquery-autocomplete/lib/thickbox.css" />

<script type="text/javascript" src="js/calendar/xc2_default.js"></script>
<script type="text/javascript" src="js/calendar/xc2_inpage.js"></script>
<link type="text/css" rel="stylesheet" href="js/calendar/xc2_default.css" />

<div id="mapdialogship" title="MAP" style='display:none;'>
    <iframe id="mapiframeship" name='mapname' frameborder=0 height="100%" width="100%" style='border:0px; height:100%; width:100%'></iframe>
</div>

<div id="mapdialogshiphis" title="MAP" style='display:none;'>
    <iframe id="mapiframeshiphis" name='mapnamesingle' frameborder=0 height="100%" width="100%" style='border:0px; height:100%; width:100%'></iframe>
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

jQuery("#mapdialogship" ).dialog( { autoOpen: false, width: '100%', height: jQuery(window).height()*0.9 });
jQuery("#mapdialogship").dialog("close");

jQuery("#mapdialogshiphis" ).dialog( { autoOpen: false, width: '100%', height: jQuery(window).height()*0.9 });
jQuery("#mapdialogshiphis").dialog("close");

function showMapSH(){
    jQuery("#mapiframeship")[0].src='map/map_ship_his_all.php';
    jQuery("#mapdialogship").dialog("open");
}

function showMapSHSingle(ais_id){
    jQuery("#mapiframeshiphis")[0].src='map/map_ship_his.php?ais_id='+ais_id;
    jQuery("#mapdialogshiphis").dialog("open");
}

function shipHis(){
	jQuery("#shiphisdetails").hide();
	jQuery('#shiphisresults').hide();

	jQuery('#pleasewait').show();

	jQuery("#sbutton").val("SEARCHING...");

	jQuery.ajax({
		type: 'GET',
		url: "search_ajax9ve.php",
		data:  jQuery("#shiphis").serialize(),

		success: function(data) {
			jQuery("#shiphis_records_tab_wrapperonly").html(data);
			jQuery('#shiphisresults').fadeIn(200);

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

<form id='shiphis' onsubmit="shipHis(); return false;">
<table width="100%" border="0" cellpadding="0" cellspacing="0" style='margin-bottom:5px;'>
    <tr>
        <td align="center">
            <table>
                <tr>
                    <td style="border:0px; vertical-align:middle;">SHIP NAME AND IMO&nbsp;&nbsp;</td>
					<td style="border:0px;">
						<input type='text' id='ship' name='ship' class='input_1' style='width:200px;'>
						<script type="text/javascript">
                        jQuery("#ship").focus().autocomplete(vessel);
                        jQuery("#ship").setOptions({
                            scrollHeight: 180
                        });
                        </script>
					</td>
					<td width="50" style="border:0px;"></td>
					<td style="border:0px; vertical-align:middle;">LAYCAN&nbsp;&nbsp;</td>
					<td style="border:0px;">
						<input type="text" name="destination_port_from" value="<?php echo date("M d, Y", time()); ?>" readonly="readonly" onclick="showCalendar('',this,null,'','',0,5,1)" class="input_1" style="width:100px;" />
						
						to
						
						<input type="text" name="destination_port_to" value="<?php echo date("M d, Y", time()+(7*24*60*60)); ?>" readonly="readonly" onclick="showCalendar('',this,null,'','',0,5,1)" class="input_1" style="width:100px;" />
					</td>
                </tr>
				<tr>
                    <td style="border:0px;" colspan="5">&nbsp;</td>
                </tr>
                <tr>
                    <td style="text-align:center; border-bottom:none;" colspan="5"><div style="padding:2px;"><input class='searchbutton' type="button" id='sbutton' name="search" value="SEARCH" style='cursor:pointer;' onclick='shipHis();'  /></div></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<div id='shiphisresults'>
    <div id='shiphis_records_tab_wrapperonly'></div>
</div>
</form>
<!--END OF SHIP HISTORY-->