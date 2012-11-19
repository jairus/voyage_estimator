<!--SHIPS COMING INTO PORTS-->
<script type='text/javascript' src='../js/jquery-autocomplete/lib/jquery.bgiframe.min.js'></script>
<script type='text/javascript' src='../js/jquery-autocomplete/lib/jquery.ajaxQueue.js'></script>
<script type='text/javascript' src='../js/jquery-autocomplete/lib/thickbox-compressed.js'></script>
<script type='text/javascript' src='../js/jquery-autocomplete/jquery.autocomplete.js'></script>
<script type='text/javascript' src='../js/ports.php'></script>
<link rel="stylesheet" type="text/css" href="../js/jquery-autocomplete/jquery.autocomplete.css" />
<link rel="stylesheet" type="text/css" href="../js/jquery-autocomplete/lib/thickbox.css" />

<script type="text/javascript" src="../js/calendar/xc2_default.js"></script>
<script type="text/javascript" src="../js/calendar/xc2_inpage.js"></script>
<link type="text/css" rel="stylesheet" href="../js/calendar/xc2_default.css" />

<script>
function shipsComingIntoPorts(){
	jQuery("#shipscomingintoportsdetails").hide();
	jQuery('#shipscomingintoportsresults').hide();

	jQuery('#pleasewait').show();

	jQuery("#sbutton").val("SEARCHING...");

	jQuery.ajax({
		type: 'GET',
		url: "../search_ajax4.php",
		data:  jQuery("#shipscomingintoports").serialize(),

		success: function(data) {
			jQuery("#shipscomingintoports_records_tab_wrapperonly").html(data);
			jQuery('#shipscomingintoportsresults').fadeIn(200);

			jQuery("#sbutton").val("SEARCH");
			
			jQuery('#pleasewait').hide();
		}
	});
}
</script>

<form id='shipscomingintoports' onsubmit="shipsComingIntoPorts(); return false;">
<table width="100%" border="0" cellpadding="0" cellspacing="0" style='margin-bottom:5px;'>
    <tr>
        <td align="center">
            <table>
                <tr>
                    <td style="vertical-align:middle; border-bottom:none;"><div style="padding:3px;"><b>PORT NAME</b></div></td>
                    <td width="5" style="border-bottom:none;">&nbsp;</td>
                    <td style="border-bottom:none;">
                    	<div style="padding:3px;">
                        <input type='text' id="suggest" name='port_name' class='input_1' style='width:200px;'>
                        
                        <script type="text/javascript">
                        jQuery("#suggest").focus().autocomplete(ports);
                        jQuery("#suggest").setOptions({
                            scrollHeight: 180
                        });
                        </script>
                        </div>
                    </td>
                    <td style="border-bottom:none;" width="50">&nbsp;</td>
                    <td style="vertical-align:middle; border-bottom:none;"><div style="padding:3px;"><b>DATE FROM</b></div></td>
                    <td style="border-bottom:none;" width="5">&nbsp;</td>
                    <td style="vertical-align:middle; border-bottom:none;">
                    	<div style="padding:3px;">
                        <input type="text" name="date_from" class='input_1' value="<?php echo date("M d, Y", time()); ?>" readonly="readonly" onclick="showCalendar('',this,null,'','',0,5,1)" style="width:90px;" />
            
                        <b>TO</b>
            
                        <input type="text" name="date_to" class='input_1' value="<?php echo date("M d, Y", time()+(7*24*60*60)); ?>" readonly="readonly" onclick="showCalendar('',this,null,'','',0,5,1)" style="width:90px;" />
                        </div>
                    </td>
                </tr>
                <tr>
                	<td colspan="7">&nbsp;</td>
                </tr>
                <tr>
                    <td style="border-bottom:none;" valign="top"><div style="padding:3px;"><b>SHIP TYPE</b></div></td>
                    <td style="border-bottom:none;" width="5">&nbsp;</td>
                    <td style="border-bottom:none;">
                    	<div style="padding:3px;">
                        <select name="p_vessel_type[]" multiple="multiple" size="16" id='p_vessel_type_id' style="width:200px;">
                            <optgroup label="BULK CARRIER">
                                <option value="ORE CARRIER">ORE CARRIER</option>
                                <option value="WOOD CHIPS CARRIER">WOOD CHIPS CARRIER</option>
                            </optgroup>
                            <optgroup label="CARGO">
                                <option value="BARGE CARRIER">BARGE CARRIER</option>
                                <option value="CARGO/PASSENGER SHIP">CARGO/PASSENGER SHIP</option>
                                <option value="HEAVY LOAD CARRIER">HEAVY LOAD CARRIER</option>
                                <option value="LIVESTOCK CARRIER">LIVESTOCK CARRIER</option>
                                <option value="MOTOR HOPPER">MOTOR HOPPER</option>
                                <option value="NUCLEAR FUEL CARRIER">NUCLEAR FUEL CARRIER</option>
                                <option value="SLUDGE CARRIER">SLUDGE CARRIER</option>
                            </optgroup>
                            <optgroup label="CEMENT CARRIER">
                                <option value="CEMENT CARRIER">CEMENT CARRIER</option>
                            </optgroup>
                            <optgroup label="OBO CARRIER">
                                <option value="OBO CARRIER">OBO CARRIER</option>
                            </optgroup>
                            <optgroup label="RO-RO CARGO">
                                <option value="RO-RO/CONTAINER CARRIER">RO-RO/CONTAINER CARRIER</option>
                                <option value="RO-RO/PASSENGER SHIP">RO-RO/PASSENGER SHIP</option>
                            </optgroup>
                        </select>
                        </div>
                    </td>
                    <td style="border-bottom:none;" width="10">&nbsp;</td>
                    <td style="border-bottom:none;"><b>&nbsp;</b></td>
                    <td style="border-bottom:none;" width="5">&nbsp;</td>
                    <td style="border-bottom:none;">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="7" style="text-align:center; border-bottom:none; border-top:1px solid #fff;"><input class='searchbutton' type="button" id='sbutton' name="search" value="SEARCH" style='cursor:pointer;' onclick='shipsComingIntoPorts();'  /></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<div id='shipscomingintoportsresults'>
    <div id='shipscomingintoports_records_tab_wrapperonly'></div>
</div>
</form>
<!--END OF SHIPS COMING INTO PORTS-->