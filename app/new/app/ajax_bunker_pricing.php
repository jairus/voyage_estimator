<!--BUNKER PRICE-->
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

<div id="mapdialogbunkerprice" title="BUNKER PRICE" style='display:none'>
    <iframe id='mapiframebunkerprice' name='mapname' frameborder=0 height="100%" width="100%" style='border:0px; height:100%; width:100%'></iframe>
</div>

<script>
function getBunkerPriceHistory(port_code, grade){
	jQuery('#pleasewait').show();
	
	jQuery.ajax({
		type: 'GET',
		url: "bunkerpricehistory.php?port_code="+port_code+"&grade="+grade,
		data:  "",

		success: function(data) {
			jQuery('#pleasewait').hide();
			
			jQuery('#bunkerpricecontent').html(data);
			jQuery( "#bunkerpricedialog" ).dialog("open"); 
		}
	});
}

jQuery("#mapdialogbunkerprice" ).dialog( { autoOpen: false, width: '100%', height: jQuery(window).height()*0.9 });
jQuery("#mapdialogbunkerprice").dialog("close");

function showMapBP(){
    jQuery('#pleasewait').show();

    jQuery.ajax({
        type: 'GET',
        url: "search_ajax6ve.php",
        data:  jQuery("#bunkerprice_form").serialize(),

        success: function(data) {
            jQuery("#mapiframebunkerprice")[0].src='map/map_bunker_price.php';
            jQuery("#mapdialogbunkerprice").dialog("open");
            
            jQuery('#pleasewait').hide();
        }
    });
}

function bunkerPriceSubmit(){
    jQuery('#bunkerpriceresults').hide();

    jQuery('#pleasewait').show();

    jQuery("#btn_search_bunkerprice_id").val("SEARCHING...");

    jQuery.ajax({
        type: 'GET',
        url: "search_ajax6ve.php",
        data:  jQuery("#bunkerprice_form").serialize(),

        success: function(data) {
            jQuery("#bunkerprice_tab_wrapperonly").html(data);
            jQuery('#bunkerpriceresults').fadeIn(200);

            jQuery("#btn_search_bunkerprice_id").val("SEARCH");
            
            jQuery('#pleasewait').hide();
        }
    });
}

jQuery( "#bunkerpricedialog" ).dialog( { autoOpen: false, width: 700, height: 600 });
jQuery( "#bunkerpricedialog" ).dialog("close");
</script>

<div id="bunkerpricedialog" title="BUNKER PRICE HISTORY"  style='display:none'>
	<div id='bunkerpricecontent'></div>
</div>

<form id='bunkerprice_form' onsubmit="bunkerPriceSubmit(); return false;">
<center>
<table>
  <tr>
    <td style="vertical-align:middle; border:0px;">
		<div style="padding:2px;">
			PORT NAME: <input id='bunkerportname_id' type="text" name="bunkerportname" class="input_1" style='width:200px;' />
			
			&nbsp;&nbsp;&nbsp;&nbsp;
			
			or
			
			&nbsp;&nbsp;&nbsp;&nbsp;
			
			DATE: <input type="text" name="date_from" value="<?php echo date("M d, Y", time()); ?>" readonly="readonly" onclick="showCalendar('',this,null,'','',0,5,1)" class="input_1" style="width:100px;" />
			
			to
			
			<input type="text" name="date_to" value="<?php echo date("M d, Y", time()+(7*24*60*60)); ?>" readonly="readonly" onclick="showCalendar('',this,null,'','',0,5,1)" class="input_1" style="width:100px;" />
			
			&nbsp;&nbsp;
			
			<input class='searchbutton' type="button" id='btn_search_bunkerprice_id' name="btn_search_bunkerprice" value="SEARCH" style='cursor:pointer;' onclick='bunkerPriceSubmit();'  />
		</div>
	</td>
  </tr>
</table>
<div id='bunkerpriceresults'>
    <div id='bunkerprice_tab_wrapperonly'></div>
</div>
<div>&nbsp;</div>
<table width="100%" id="map_bunker_price">
	<tr style="background:#e5e5e5; padding:10px 0px;">
		<td><div style="padding:5px; text-align:center;"><a onclick="showMapBP();" class="clickable">view larger map</a></div></td>
	</tr>
	<tr style="background:#e5e5e5;">
		<td><div style="padding:5px; text-align:center;"><iframe src="map/map_bunker_price.php" width="990" height="700"></iframe></div></td>
	</tr>
</table>
</center>
</form>

<script type="text/javascript">
jQuery("#bunkerportname_id").focus().autocomplete(ports);
jQuery("#bunkerportname_id").setOptions({
    scrollHeight: 180
});
</script>
<!--END OF BUNKER PRICE-->