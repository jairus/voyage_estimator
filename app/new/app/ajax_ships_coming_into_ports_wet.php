<!--SHIPS COMING INTO PORTS-->
<style>
body{
	margin-top:10px;
}
</style>

<link rel="stylesheet" href="js/development-bundle/themes/base/jquery.ui.all.css">
<script type="text/javascript" src="js/development-bundle/ui/jquery.ui.core.js"></script>
<script type="text/javascript" src="js/development-bundle/ui/jquery.ui.widget.js"></script>
<script type="text/javascript" src="js/development-bundle/ui/jquery.ui.dialog.js"></script>

<script type='text/javascript' src='js/jquery-autocomplete/lib/jquery.bgiframe.min.js'></script>
<script type='text/javascript' src='js/jquery-autocomplete/lib/jquery.ajaxQueue.js'></script>
<script type='text/javascript' src='js/jquery-autocomplete/lib/thickbox-compressed.js'></script>
<script type='text/javascript' src='js/jquery-autocomplete/jquery.autocomplete.js'></script>
<script type='text/javascript' src='js/ports.php'></script>
<link rel="stylesheet" type="text/css" href="js/jquery-autocomplete/jquery.autocomplete.css" />
<link rel="stylesheet" type="text/css" href="js/jquery-autocomplete/lib/thickbox.css" />

<script type="text/javascript" src="js/calendar/xc2_default.js"></script>
<script type="text/javascript" src="js/calendar/xc2_inpage.js"></script>
<link type="text/css" rel="stylesheet" href="js/calendar/xc2_default.css" />

<script>
function showShipDetails(imo){
	jQuery("#shipdetails").dialog("close")
	jQuery('#pleasewait').show();

	jQuery.ajax({
		type: 'POST',
		url: "search_ajax.php?imo="+imo,
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

	jQuery("#contactiframe")[0].src='search_ajax.php?contact=1&owner='+owner+'&owner_id='+owner_id;
	jQuery("#contactdialog").dialog("open");
}

function shipsComingIntoPorts(){
	jQuery("#shipscomingintoportsdetails").hide();
	jQuery('#shipscomingintoportsresults').hide();

	jQuery('#pleasewait').show();

	jQuery("#sbutton").val("SEARCHING...");

	jQuery.ajax({
		type: 'GET',
		url: "search_ajax4ve_wet.php",
		data:  jQuery("#shipscomingintoports").serialize(),

		success: function(data) {
			jQuery("#shipscomingintoports_records_tab_wrapperonly").html(data);
			jQuery('#shipscomingintoportsresults').fadeIn(200);

			jQuery("#sbutton").val("SEARCH");
			
			jQuery('#pleasewait').hide();
		}
	});
}

jQuery("#shipdetails").dialog( { autoOpen: false, width: '90%', height: jQuery(window).height()*0.9 });
jQuery("#shipdetails").dialog("close");

jQuery("#contactdialog").dialog( { autoOpen: false, width: 900, height: 460 });
jQuery("#contactdialog").dialog("close");

jQuery( "#mapdialog" ).dialog( { autoOpen: false, width: '90%', height: jQuery(window).height()*0.9 });
jQuery("#mapdialog").dialog("close");
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
						<?php
						$vessels = array(
							0=>'ASPHALT/BITUMEN TANKER', 
							1=>'BUNKERING TANKER', 
							2=>'CHEMICAL TANKER', 
							3=>'CRUDE OIL TANKER', 
							4=>'FRUIT JUICE TANKER', 
							5=>'OIL PRODUCTS TANKER', 
							6=>'OIL/CHEMICAL TANKER', 
							7=>'TANKER', 
							8=>'VEGETABLE/ANIMAL OIL TANKER', 
							9=>'WATER TANKER', 
							10=>'WINE TANKER'
						);
						$t1 = count($vessels);
						?>
					  
						<select name="p_vessel_type[]" multiple="multiple" size="12" id='p_vessel_type_id' class="input_1" style="width:220px;">
							<optgroup label="TANKER">
								<?php
								for($i1=0; $i1<$t1; $i1++){
									if(in_array($vessels[$i1], $vessel_type)){
										echo '<option value="'.$vessels[$i1].'" selected="selected">'.$vessels[$i1].'</option>';
									}else{
										echo '<option value="'.$vessels[$i1].'">'.$vessels[$i1].'</option>';
									}
								}
								?>
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