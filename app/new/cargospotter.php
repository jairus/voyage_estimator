<?php
@include_once(dirname(__FILE__)."/includes/bootstrap.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>CargoSpotter</title>
<link rel="stylesheet" href="css/style.css">
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	displayContent('voyage_estimator')
});

function displayContent(content){
	jQuery('#pleasewait').show();
	
	jQuery('#results').hide();
	
	jQuery('#voyage_estimator_id_link').removeClass('content_link_selected');
	jQuery('#fast_search_id_link').removeClass('content_link_selected');
	jQuery('#ship_search_register_id_link').removeClass('content_link_selected');
	jQuery('#fleet_positions_id_link').removeClass('content_link_selected');
	jQuery('#ships_coming_into_ports_id_link').removeClass('content_link_selected');
	jQuery('#live_ship_position_id_link').removeClass('content_link_selected');
	jQuery('#ports_intelligence_id_link').removeClass('content_link_selected');
	jQuery('#piracy_notices_id_link').removeClass('content_link_selected');
	jQuery('#bunker_pricing_id_link').removeClass('content_link_selected');
	jQuery('#weather_id_link').removeClass('content_link_selected');
	
	jQuery('#voyage_estimator_id_link').addClass('content_link');

	jQuery.ajax({
		type: "POST",
		url: "ajax_"+ content +".php",
		data: "",

		success: function(data) {
			jQuery('#' + content + '_id_link').addClass('content_link_selected');
			
			jQuery("#records_tab_wrapperonly").html(data);
			jQuery('#results').fadeIn(200);
			
			jQuery('#pleasewait').hide();
		}
	});
}
</script>
</head>

<body>

<div id="outer">
	<div id="site_content_1" style="padding-bottom:20px;">
        <div style="float:left; width:70px; height:55px;"><img src="images/logo_ve.png" width="44" height="44" border="0" /></div>
        <div style="float:left; width:1230px; height:40px; padding-top:15px;">
            <a onclick="displayContent('voyage_estimator');" id='voyage_estimator_id_link' class="content_link_selected">Voyage Estimator</a> &nbsp; 
            <a onclick="displayContent('fast_search');" id='fast_search_id_link' class="content_link">Fast Search</a> &nbsp; 
            <a onclick="displayContent('ship_search_register');" id='ship_search_register_id_link' class="content_link">Ship Search / Register</a> &nbsp; 
            <a onclick="displayContent('fleet_positions');" id='fleet_positions_id_link' class="content_link">Fleet Positions</a> &nbsp; 
            <a onclick="displayContent('ships_coming_into_ports');" id='ships_coming_into_ports_id_link' class="content_link">Ships Coming Into Ports</a> &nbsp; 
            <a onclick="displayContent('live_ship_position');" id='live_ship_position_id_link' class="content_link">Live Ship Position</a> &nbsp; 
            <a onclick="displayContent('ports_intelligence');" id='ports_intelligence_id_link' class="content_link">Ports Intelligence</a> &nbsp; 
            <a onclick="displayContent('piracy_notices');" id='piracy_notices_id_link' class="content_link">Piracy Notices</a> &nbsp; 
            <a onclick="displayContent('bunker_pricing');" id='bunker_pricing_id_link' class="content_link">Bunker Pricing</a> &nbsp; 
            <a onclick="displayContent('weather');" id='weather_id_link' class="content_link">Weather</a>
        </div>
        <div style="float:left; width:1300px; height:auto; border-bottom:3px dotted #fff;">&nbsp;</div>
	</div>
    <div id="site_content_1">
    	<div id="results">
            <div id="records_tab_wrapperonly"></div>
        </div>
    </div>
</div>

<center>
<table width="100%" height="100%" id="pleasewait" style="display:none; position:fixed; top:0; left:0; z-index:100; background-image:url('images/overlay.png'); background-position:center; background-attachment:scroll; filter:alpha(opacity=90); opacity:0.9;">
    <tr>
        <td style="text-align:center;"><img src="images/loading.gif" /></td>
    </tr>
</table>
</center>

</body>
</html>