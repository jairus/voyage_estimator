<!--SHIP SEARCH ONLY-->
<script>
function shipSearchOnly(){
	jQuery("#shipdetails").hide();
	jQuery('#shipsearchonlyresults').hide();

	jQuery('#pleasewait').show();

	jQuery("#sbutton").val("SEARCHING...");

	jQuery.ajax({
		type: 'GET',
		url: "../search_ajax2ve.php",
		data:  jQuery("#shipsearchonly").serialize(),

		success: function(data) {
			jQuery("#records_tab_wrapperonly").html(data);
			jQuery('#shipsearchonlyresults').fadeIn(200);

			jQuery("#sbutton").val("SEARCH");
			
			jQuery('#pleasewait').hide();
		}
	});
}
</script>

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
    <div id='records_tab_wrapperonly'></div>
</div>
</form>
<!--END OF SHIP SEARCH ONLY-->