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

<div id="mapdialogbunkerprice" title="BUNKER PRICE" style='display:none'>
    <iframe id='mapiframebunkerprice' name='mapname' frameborder=0 height="100%" width="100%" style='border:0px; height:100%; width:100%'></iframe>
</div>

<script>
function getBunkerPriceHistory(port_code){
	jQuery('#pleasewait').show();
	
	jQuery.ajax({
		type: 'GET',
		url: "bunkerpricehistory.php?port_code="+port_code,
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
            jQuery("#mapiframebunkerprice")[0].src='map/index12.php';
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
    <td style="vertical-align:middle;"><div style="padding:2px;">PORT NAME: <input id='bunkerportname_id' type="text" name="bunkerportname" class="input_1" style='width:200px;' /> &nbsp;&nbsp; <input class='searchbutton' type="button" id='btn_search_bunkerprice_id' name="btn_search_bunkerprice" value="SEARCH" style='cursor:pointer;' onclick='bunkerPriceSubmit();'  /></div></td>
  </tr>
</table>

<div id='bunkerpriceresults'>
    <div id='bunkerprice_tab_wrapperonly'></div>
</div>
</center>
</form>

<script type="text/javascript">
jQuery("#bunkerportname_id").focus().autocomplete(ports);
jQuery("#bunkerportname_id").setOptions({
    scrollHeight: 180
});
</script>
<!--END OF BUNKER PRICE-->