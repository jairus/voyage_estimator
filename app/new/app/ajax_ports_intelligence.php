<!--PORT INTELLIGENCE-->
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
<script type='text/javascript' src='js/wpi_ports.php'></script>
<script type='text/javascript' src='js/wpi_countries.php'></script>
<link rel="stylesheet" type="text/css" href="js/jquery-autocomplete/jquery.autocomplete.css" />
<link rel="stylesheet" type="text/css" href="js/jquery-autocomplete/lib/thickbox.css" />

<div id="mapdialogportintelligence" title="MAP" style='display:none;'>
    <iframe id="mapiframeportintelligence" name='mapname' frameborder=0 height="100%" width="100%" style='border:0px; height:100%; width:100%'></iframe>
</div>

<script>
jQuery("#mapdialogportintelligence" ).dialog( { autoOpen: false, width: '100%', height: jQuery(window).height()*0.9 });
jQuery("#mapdialogportintelligence").dialog("close");

function showMapPI(){
    jQuery('#pleasewait').show();

    jQuery.ajax({
        type: 'GET',
        url: "search_ajax5ve.php",
        data:  jQuery("#portintelligence_form").serialize(),

        success: function(data) {
            jQuery("#mapiframeportintelligence")[0].src='map/index11.php';
            jQuery("#mapdialogportintelligence").dialog("open");
            
            jQuery('#pleasewait').hide();
        }
    });
}

function portIntelligenceSubmit(){
    jQuery('#portintelligenceresults').hide();

    jQuery('#pleasewait').show();

    jQuery("#btn_search_portintelligence_id").val("SEARCHING...");

    jQuery.ajax({
        type: 'GET',
        url: "search_ajax5ve.php",
        data:  jQuery("#portintelligence_form").serialize(),

        success: function(data) {
            jQuery("#portintelligence_tab_wrapperonly").html(data);
            jQuery('#portintelligenceresults').fadeIn(200);

            jQuery("#btn_search_portintelligence_id").val("SEARCH");	
            
            jQuery('#pleasewait').hide();
        }
    });
}
</script>
<form id='portintelligence_form' onsubmit="portIntelligenceSubmit(); return false;">
<center>
<table>
  <tr>
    <td style="vertical-align:middle;"><div style="padding:2px;">PORT NAME: <input id='portname_id' type="text" name="portname" class="input_1" style='width:200px;' /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>OR</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; COUNTRY NAME: <input id='countryname_id' type="text" name="countryname" class="input_1" style='width:200px;' /></div></td>
  </tr>
  <tr>
    <td style="padding:2px 0px; border-bottom:none;" align="center"><input class='searchbutton' type="button" id='btn_search_portintelligence_id' name="btn_search_portintelligence" value="SEARCH" style='cursor:pointer;' onclick='portIntelligenceSubmit();'  /></td>
  </tr>
</table>

<div id='portintelligenceresults'>
    <div id='portintelligence_tab_wrapperonly'></div>
</div>
</center>
</form>

<script type="text/javascript">
jQuery("#portname_id").focus().autocomplete(wpi_ports);
jQuery("#portname_id").setOptions({
    scrollHeight: 180
});

jQuery("#countryname_id").focus().autocomplete(wpi_countries);
jQuery("#countryname_id").setOptions({
    scrollHeight: 180
});
</script>
<!--END OF PORT INTELLIGENCE-->