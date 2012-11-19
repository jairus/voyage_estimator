<!--BUNKER PRICE-->
<div id="mapdialogbunkerprice" title="BUNKER PRICE" style='display:none'>
    <iframe id='mapiframebunkerprice' name='mapname' frameborder=0 height="100%" width="100%" style='border:0px; height:100%; width:100%'></iframe>
</div>

<script>
jQuery("#mapdialogbunkerprice" ).dialog( { autoOpen: false, width: '100%', height: jQuery(window).height()*0.9 });
jQuery("#mapdialogbunkerprice").dialog("close");

function showMapBP(){
    jQuery('#pleasewait').show();

    jQuery.ajax({
        type: 'GET',
        url: "../search_ajax_bunkerprice.php",
        data:  jQuery("#bunkerprice_form").serialize(),

        success: function(data) {
            jQuery("#mapiframebunkerprice")[0].src='../map/index12.php';
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
        url: "../search_ajax_bunkerprice.php",
        data:  jQuery("#bunkerprice_form").serialize(),

        success: function(data) {
            jQuery("#bunkerprice_tab_wrapperonly").html(data);
            jQuery('#bunkerpriceresults').fadeIn(200);

            jQuery("#btn_search_bunkerprice_id").val("SEARCH");
            
            jQuery('#pleasewait').hide();
        }
    });
}
</script>
<form id='bunkerprice_form' onsubmit="bunkerPriceSubmit(); return false;">
<center>
<table>
  <tr>
    <td style="vertical-align:middle;"><div style="padding:2px;">PORT NAME: <input id='bunkerportname_id' type="text" name="bunkerportname" class="input_1" style='width:200px;' /> &nbsp;&nbsp; <input class='searchbutton' type="button" id='btn_search_bunkerprice_id' name="btn_search_bunkerprice" value="SEARCH" style='cursor:pointer;' onclick='bunkerPriceSubmit();'  /></div></td>
  </tr>
  <tr>
    <td colspan="2" style="border-bottom:none;">
        <div style="padding:2px;">
            <div id='bunkerpriceresults'>
                <div id='bunkerprice_tab_wrapperonly'></div>
            </div>
        </div>
    </td>
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