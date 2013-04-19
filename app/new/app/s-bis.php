<?php
@session_start();
include_once(dirname(__FILE__)."/includes/bootstrap.php");
date_default_timezone_set('UTC');

$page = '';
if($user['dry']==0){
	$page = '_wet';
}else if($user['dry']==9){
	$page = 'agent';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>S-BIS - Ship Broker Intelligence Solutions</title>
<link rel="shortcut icon" href="../images/global/favicon.ico">
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/style_ve.css">
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>-->
<script type="text/javascript">
var agentpage = '<?php echo $page; ?>';
var agentpage2 = '<?php echo $_GET['page']; ?>';
var agentaction = '<?php echo $_GET['id']; ?>';
var edit = '<?php echo $_GET['edit']; ?>';
var del = '<?php echo $_GET['del']; ?>';
var page = '<?php echo $_GET['new_search']; ?>';
var action = '<?php echo $_GET['action']; ?>';
var tabid = '<?php echo $_GET['tabid']; ?>';
var condition = '';
	
$(document).ready(function() {
	if(page=='3'){
		displayContent('voyage_estimator'+'<?php echo $page; ?>');
	}else if(page=='1'){
		displayContent('ais_broker'+'<?php echo $page; ?>');
	}else if(agentpage=='agent'){
		displayContent('cargo');
	}else if(action=='network' || action=='alerts' || action=='account' || action=='accountview'){
		displayContent('account');
	}else{
		displayContent('voyage_estimator'+'<?php echo $page; ?>');
	}
});

function displayContent(content){
	jQuery('#pleasewait').show();
	
	jQuery('#results').hide();
	
	<?php
	if($user['dry']==1){
		?>
		jQuery('#ais_broker_id_link').removeClass('content_link_selected');
		jQuery('#ship_his_id_link').removeClass('content_link_selected');
		jQuery('#voyage_estimator_id_link').removeClass('content_link_selected');
		jQuery('#distance_calculator_id_link').removeClass('content_link_selected');
		jQuery('#ship_search_register_id_link').removeClass('content_link_selected');
		jQuery('#fleet_positions_id_link').removeClass('content_link_selected');
		//jQuery('#ships_coming_into_ports_id_link').removeClass('content_link_selected');
		//jQuery('#live_ship_position_id_link').removeClass('content_link_selected');
		jQuery('#ports_intelligence_id_link').removeClass('content_link_selected');
		//jQuery('#piracy_notices_id_link').removeClass('content_link_selected');
		jQuery('#bunker_pricing_id_link').removeClass('content_link_selected');
		//jQuery('#weather_id_link').removeClass('content_link_selected');
		jQuery('#account_id_link').removeClass('content_link_selected');
		
		jQuery('#voyage_estimator_id_link').addClass('content_link');
		<?php
	}elseif($user['dry']==9){
		?>
		jQuery('#cargo_id_link').removeClass('content_link_selected');
		jQuery('#agentaccount_id_link').removeClass('content_link_selected');
		
		jQuery('#cargo_id_link').addClass('content_link');
		<?php
	}elseif($user['dry']==0){
		?>
		jQuery('#ais_broker_wet_id_link').removeClass('content_link_selected');
		jQuery('#ship_his_wet_id_link').removeClass('content_link_selected');
		jQuery('#voyage_estimator_wet_id_link').removeClass('content_link_selected');
		jQuery('#distance_calculator_id_link').removeClass('content_link_selected');
		jQuery('#ship_search_register_wet_id_link').removeClass('content_link_selected');
		jQuery('#fleet_positions_wet_id_link').removeClass('content_link_selected');
		jQuery('#ships_coming_into_ports_wet_id_link').removeClass('content_link_selected');
		jQuery('#live_ship_position_wet_id_link').removeClass('content_link_selected');
		jQuery('#ports_intelligence_id_link').removeClass('content_link_selected');
		jQuery('#piracy_notices_id_link').removeClass('content_link_selected');
		jQuery('#bunker_pricing_id_link').removeClass('content_link_selected');
		jQuery('#weather_id_link').removeClass('content_link_selected');
		jQuery('#account_id_link').removeClass('content_link_selected');
		
		jQuery('#voyage_estimator_wet_id_link').addClass('content_link');
		<?php
	}
	?>
	
	//AIS BROKER
	if(page=='0'){
		condition = '?new_search='+page+'&action='+action;
	}
	
	if(page=='1'){
		if(action!=''){
			condition = '?action='+action;
		}
		
		if(tabid!=''){
			condition = '?action='+action+'&tabid='+tabid;
		}
	}
	//END OF AIS BROKER
	
	if(page=='3'){
		if(tabid==""){
			condition = '?new_search=3';
		}else{
			condition = '?new_search=3&tabid='+tabid;
		}
	}
	
	if(page=='4'){
		condition = '?new_search=4';
	}
	
	if(page!='0' && page!='1' && page!='3' && page!='4'){
		if(action!=''){
			condition = '?action='+action;
			
			var id = '<?php echo $_GET['id']; ?>';
			
			if(id!=''){
				condition = '?action='+action+'&id='+id;
			}
		}
	}
	
	if(agentpage2=='10'){
		if(edit){
			condition = '?id='+agentaction+'&edit='+edit;
		}
		
		if(del){
			condition = '?id='+agentaction+'&del='+del;
		}
	}
	
	if(agentpage2=='11'){
		condition = '?confirmdelete=1&id='+agentaction;
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

<?php
if($user['dry']==1){
	include_once(dirname(__FILE__)."/ext_dry.php");
}else if($user['dry']==9){
	include_once(dirname(__FILE__)."/ext_agent.php");
}elseif($user['dry']==0){
	include_once(dirname(__FILE__)."/ext_wet.php");
}
?>

<center>
<table width="100%" height="100%" id="pleasewait" style="display:none; position:fixed; top:0; left:0; z-index:100; background-image:url('images/overlay.png'); background-position:center; background-attachment:scroll; filter:alpha(opacity=90); opacity:0.9;">
	<tr>
        <td height="50" style="border-bottom:none;"></td>
    </tr>
    <tr>
        <td align="center" valign="middle"><img src="images/loading.gif" /></td>
    </tr>
</table>
</center>

</body>
</html>