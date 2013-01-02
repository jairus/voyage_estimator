<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>CargoSpotter</title>
<link rel="shortcut icon" href="../images/global/favicon.ico">
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/style_ve.css">
<script type="text/javascript" src="js/jquery.js"></script>
<!--<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>-->
<script type="text/javascript">
$(document).ready(function() {
	var page = '<?php echo $_GET['new_search']; ?>';
	var action = '<?php echo $_GET['action']; ?>';
	
	if(page=='1'){
		displayContent('fast_search');
	}else if(action=='network' || action=='alerts' || action=='account' || action=='accountview'){
		displayContent('account');
	}else{
		displayContent('voyage_estimator');
	}
});

function displayContent(content){
	jQuery('#pleasewait').show();
	
	jQuery('#results').hide();
	
	jQuery('#voyage_estimator_id_link').removeClass('content_link_selected');
	jQuery('#distance_calculator_id_link').removeClass('content_link_selected');
	jQuery('#fast_search_id_link').removeClass('content_link_selected');
	jQuery('#ship_search_register_id_link').removeClass('content_link_selected');
	jQuery('#fleet_positions_id_link').removeClass('content_link_selected');
	jQuery('#ships_coming_into_ports_id_link').removeClass('content_link_selected');
	jQuery('#live_ship_position_id_link').removeClass('content_link_selected');
	jQuery('#ports_intelligence_id_link').removeClass('content_link_selected');
	jQuery('#piracy_notices_id_link').removeClass('content_link_selected');
	jQuery('#bunker_pricing_id_link').removeClass('content_link_selected');
	jQuery('#weather_id_link').removeClass('content_link_selected');
	jQuery('#account_id_link').removeClass('content_link_selected');
	
	jQuery('#voyage_estimator_id_link').addClass('content_link');
	
	var page = '<?php echo $_GET['new_search']; ?>';
	var action = '<?php echo $_GET['action']; ?>';
	var condition = '';
	
	if(page=='1'){
		var tab = '<?php echo $_GET['tab']; ?>';
		var deltab = '<?php echo $_GET['deltab']; ?>';
		
		if(tab!=''){
			var condition = '?tab='+tab;
		}else if(deltab!=''){
			var condition = '?deltab='+deltab;
		}
	}
	
	if(page=='3'){
		var tabid = '<?php echo $_GET['tabid']; ?>';
		
		if(tabid==""){
			var condition = '?new_search=3';
		}else{
			var condition = '?new_search=3&tabid='+tabid;
		}
	}
	
	if(page=='4'){
		var condition = '?new_search=4';
	}
	
	if(action!=''){
		var condition = '?action='+action;
		
		var id = '<?php echo $_GET['id']; ?>';
		
		if(id!=''){
			var condition = '?action='+action+'&id='+id;
		}
	}
	
	jQuery.ajax({
		type: "POST",
		url: "ajax_"+ content +".php"+condition,
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
        <div style="float:left; width:1300px; height:40px; padding-top:40px; text-align:center;">
            <a onclick="displayContent('voyage_estimator');" id='voyage_estimator_id_link' class="content_link_selected">VE</a> &nbsp; 
			<a onclick="displayContent('distance_calculator');" id='distance_calculator_id_link' class="content_link">DC</a> &nbsp; 
            <a onclick="displayContent('fast_search');" id='fast_search_id_link' class="content_link">Search</a> &nbsp; 
            <a onclick="displayContent('ship_search_register');" id='ship_search_register_id_link' class="content_link">Register</a> &nbsp; 
            <a onclick="displayContent('fleet_positions');" id='fleet_positions_id_link' class="content_link">Fleet</a> &nbsp; 
            <a onclick="displayContent('ships_coming_into_ports');" id='ships_coming_into_ports_id_link' class="content_link">Ships In Port</a> &nbsp; 
            <a onclick="displayContent('live_ship_position');" id='live_ship_position_id_link' class="content_link">AIS Position</a> &nbsp; 
            <a onclick="displayContent('ports_intelligence');" id='ports_intelligence_id_link' class="content_link">Port Data</a> &nbsp; 
            <a onclick="displayContent('piracy_notices');" id='piracy_notices_id_link' class="content_link">Piracy</a> &nbsp; 
            <a onclick="displayContent('bunker_pricing');" id='bunker_pricing_id_link' class="content_link">Bunker</a> &nbsp; 
            <a onclick="displayContent('weather');" id='weather_id_link' class="content_link">Weather</a> &nbsp; 
			<a onclick="displayContent('account');" id='account_id_link' class="content_link">Account</a> &nbsp; 
			<a href="cargospotterlogout.php" id='weather_id_link' class="content_link">Logout</a>
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
        <td align="center" valign="middle"><img src="images/loading.gif" /></td>
    </tr>
</table>
</center>

</body>
</html>