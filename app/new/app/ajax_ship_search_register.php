<!--SHIP SEARCH ONLY-->
<script type="text/javascript" src="js/jscript.js"></script>
<link rel="stylesheet" href="js/development-bundle/themes/base/jquery.ui.all.css">
<script type="text/javascript" src="js/development-bundle/ui/jquery.ui.core.js"></script>
<script type="text/javascript" src="js/development-bundle/ui/jquery.ui.widget.js"></script>
<script type="text/javascript" src="js/development-bundle/ui/jquery.ui.dialog.js"></script>
<script>
function openMessageDialog(mid, imo, type){
	jQuery("#messageiframeshipsearchonly")[0].src="search_ajax1ve.php?action=getmessages&type="+type+"&mid="+mid+"&imo="+imo+"&t="+(new Date()).getTime();
	jQuery("#messagedialogshipsearchonly").dialog( { autoOpen: false, width: '920', height: jQuery(window).height()*0.9 });
	jQuery("#messagedialogshipsearchonly").dialog("open");
}

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

function shipSearchOnly(){
	jQuery("#shipdetails").hide();
	jQuery('#shipsearchonlyresults').hide();

	jQuery('#pleasewait').show();

	jQuery("#sbutton").val("SEARCHING...");

	jQuery.ajax({
		type: 'GET',
		url: "search_ajax2ve.php",
		data:  jQuery("#shipsearchonly").serialize(),

		success: function(data) {
			jQuery("#records_tab_wrapperonly_shipsearchonly").html(data);
			jQuery('#shipsearchonlyresults').fadeIn(200);

			jQuery("#sbutton").val("SEARCH");
			
			jQuery('#pleasewait').hide();
		}
	});
}

function mailItVe_2(imo){
	jQuery("#misciframe")[0].src="misc/email_ve_2.php?imo="+imo;
	jQuery("#miscdialog").dialog("open");
}

function printItVe_2(imo){
	jQuery("#misciframe")[0].src="misc/print_ve_2.php?imo="+imo;
	jQuery("#miscdialog").dialog("open");
}

jQuery("#shipdetails").dialog( { autoOpen: false, width: '90%', height: jQuery(window).height()*0.9 });
jQuery("#shipdetails").dialog("close");

jQuery( "#mapdialog" ).dialog( { autoOpen: false, width: '90%', height: jQuery(window).height()*0.9 });
jQuery("#mapdialog").dialog("close");

jQuery("#contactdialog").dialog( { autoOpen: false, width: 900, height: 460 });
jQuery("#contactdialog").dialog("close");

jQuery( "#miscdialog" ).dialog( { autoOpen: false, width: 1100, height: 500 });
jQuery( "#miscdialog" ).dialog("close");
</script>

<div id="shipdetails" title="SHIP DETAILS" style='display:none;'>
	<div id='shipdetails_in'></div>
</div>

<div id="contactdialog" title="CONTACT"  style='display:none'>
	<iframe id='contactiframe' frameborder="0" height="100%" width="100%"></iframe>
</div>

<div id="mapdialog" title="MAP - CLICK ON THE SHIP IMAGE BELOW TO SHOW DETAILS" style='display:none'>
	<iframe id='mapiframe' name='mapname' frameborder=0 height="100%" width="100%" style='border:0px; height:100%; width:100%'></iframe>
</div>

<div id="messagedialogshipsearchonly" title="MESSAGES"  style='display:none'>
	<iframe id='messageiframeshipsearchonly' frameborder=0 height="100%" width="100%" style='border:0px; height:100%; width:100%'></iframe>
</div>

<div id="miscdialog" title=""  style='display:none'>
	<iframe id='misciframe' frameborder='0' height="100%" width="1100px" style='border:0px; height:100%; width:1050px;'></iframe>
</div>

<form id='shipsearchonly' onsubmit="shipSearchOnly(); return false;">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td align="center">
            <table>
                <tr>
                    <td style="vertical-align:middle;"><div style="padding:2px;">SHIP NAME, IMO, MMSI, CALLSIGN</div></td>
                    <td><div style="padding:2px;"><input type='text' name='ship' class='input_1' style='width:200px'></div></td>
                    <td width="50">&nbsp;</td>
                    <td style="vertical-align:middle;"><div style="padding:2px;">MANAGER / MANAGER OWNER</div></td>
                    <td><div style="padding:2px;"><input type='text' name='operator' class='input_1' style='width:200px'></div></td>
                </tr>
                <tr>
                    <td colspan='5' style="text-align:center; border-bottom:none;"><div style="padding:2px;"><input class='searchbutton' type="button" id='sbutton' name="search" value="SEARCH" style='cursor:pointer;' onclick='shipSearchOnly();' /></div></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<div id='shipsearchonlyresults'>
    <div id='records_tab_wrapperonly_shipsearchonly'></div>
</div>
</form>
<!--END OF SHIP SEARCH ONLY-->