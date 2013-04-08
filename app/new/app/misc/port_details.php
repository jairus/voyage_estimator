<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/jquery-1.7.2.min.js"></script>

<script type='text/javascript' src='../js/jquery-autocomplete/lib/jquery.bgiframe.min.js'></script>
<script type='text/javascript' src='../js/jquery-autocomplete/lib/jquery.ajaxQueue.js'></script>
<script type='text/javascript' src='../js/jquery-autocomplete/lib/thickbox-compressed.js'></script>
<script type='text/javascript' src='../js/jquery-autocomplete/jquery.autocomplete.js'></script>
<script type='text/javascript' src='../js/autoAgent.php?portname=<?php echo $_GET['portname']; ?>'></script>
<script type='text/javascript' src='../js/autoPorts.php'></script>
<link rel="stylesheet" type="text/css" href="../js/jquery-autocomplete/jquery.autocomplete.css" />
<link rel="stylesheet" type="text/css" href="../js/jquery-autocomplete/lib/thickbox.css" />

<link rel="stylesheet" media="all" type="text/css" href="../js/jquery-ui.css" />
<script type="text/javascript" src="../js/jquery-ui.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="../js/jquery-ui-sliderAccess.js"></script>

<link type="text/css" href="../js/grid/jquery_css/flexigrid.css" rel="stylesheet" />
<script type="text/javascript" src="../js/grid/jquery_javascript/flexigrid.js"></script>

<script language="JavaScript">
function expand(){
	if($('#arrow1').attr('src')=='../images/icon_pullup_warning_shore.png'){
		$('#arrow1').attr('src', '../images/icon_dropdown_warning_shore.png');
		
		jQuery('#other_details_table_id').hide();
	}else{
		$('#arrow1').attr('src', '../images/icon_pullup_warning_shore.png');
		
		jQuery('#other_details_table_id').show();
	}
	
	computeForTotal();
}

$(function() { $('#date_id').datetimepicker(); });
$(function() { $('#date_to_id').datetimepicker(); });
$(function() { $('#date_hour_id').datetimepicker(); });

function saveForm(){
	var submitok = 1;
	
	alertmsg = "";
	
	if(document.inputfrm.ship_agent.value==""){ 
		alertmsg="Please enter the SHIP AGENT\n"; submitok = 0; 
		document.inputfrm.submitok.value=0
	}else{
		jQuery('#pleasewait').show();
		document.inputfrm.submitok.value=1
	}
	
	if(submitok==1){document.inputfrm.submit();}
	else{alert(alertmsg);}
}

function updateForm(){
	var submitok02 = 1;
	
	alertmsg = "";
	
	if(document.inputfrm.ship_agent.value==""){ 
		alertmsg="Please enter the SHIP AGENT\n"; submitok02 = 0; 
		document.inputfrm.submitok02.value=0
	}else{
		jQuery('#pleasewait').show();
		document.inputfrm.submitok02.value=1
	}
	
	if(submitok02==1){document.inputfrm.submit();}
	else{alert(alertmsg);}
}

function deleteitem(){
	<?php
	if(isset($_GET['del'])){
		echo 'if(confirm("Are you sure you want to DELETE this record?\n Record Number : '.$_GET['id'].'")){'."\n";
		echo "location = 'port_details.php?confirmdelete=1&id=".$_GET['id']."&portname=".$_GET['portname'].'&vessel_name='.$_GET['vessel_name'].'&cargo_type='.$_GET['cargo_type'].'&dwt='.$_GET['dwt'].'&gross_tonnage='.$_GET['gross_tonnage'].'&net_tonnage='.$_GET['net_tonnage'].'&owner='.$_GET['owner'].'&date_from='.$_GET['date_from'].'&date_to='.$_GET['date_to'].'&num_of_days='.$_GET['num_of_days']."';"."\n";
		echo '}'."\n";
	}
	?>
}

function addCommas(nStr){
	nStr += '';

	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';

	var rgx = /(\d+)(\d{3})/;

	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}

	return x1 + x2;
}

function fNum(num){
	num = uNum(num);

	if(num==0){
		return "";
	}

	num = num.toFixed(2);

	return addCommas(num);
}

function uNum(num){
	if(!num){
		num = 0;
	}else if(isNaN(num)){
		num = num.replace(/[^0-9\.]/g, "");

		if(isNaN(num)){
			num = 0;
		}
	}

	return num*1;
}

//COMPUTATIONS
function computeForTotal(){
	//QUICK TOTAL CHARGES
	var quick_total_charges = uNum(jQuery("#quick_total_charges_id").val());
	//END OF QUICK TOTAL CHARGES

	if($('#arrow1').attr('src')!='../images/icon_dropdown_warning_shore.png'){
		//PORT CHARGES
		var harbour_dues = jQuery("#harbour_dues_id").val();
		var light_dues = jQuery("#light_dues_id").val();
		var pilotage = jQuery("#pilotage_id").val();
		var towage = jQuery("#towage_id").val();
		var mooring_unmooring = jQuery("#mooring_unmooring_id").val();
		var shifting = jQuery("#shifting_id").val();
		var customs_charges = jQuery("#customs_charges_id").val();
		var launch_car_hire = jQuery("#launch_car_hire_id").val();
		var agency_remuniration = jQuery("#agency_remuniration_id").val();
		var telex_postage_telegrams = jQuery("#telex_postage_telegrams_id").val();
	
		var total_port_charges = uNum(harbour_dues) + uNum(light_dues) + uNum(pilotage) + uNum(towage) + uNum(mooring_unmooring) + uNum(shifting) + uNum(customs_charges) + uNum(launch_car_hire) + uNum(agency_remuniration) + uNum(telex_postage_telegrams);
		
		jQuery("#total_port_charges_td").text(fNum(total_port_charges));
		jQuery("#total_port_charges_id").val(fNum(total_port_charges));
		//END OF PORT CHARGES
		
		//CARGO CHARGES
		var stevedoring_expenses = jQuery("#stevedoring_expenses_id").val();
		var winchmen_cranage = jQuery("#winchmen_cranage_id").val();
		var tally = jQuery("#tally_id").val();
		var overtime = jQuery("#overtime_id").val();
	
		var total_cargo_charges = uNum(stevedoring_expenses) + uNum(winchmen_cranage) + uNum(tally) + uNum(overtime);
		
		jQuery("#total_cargo_charges_td").text(fNum(total_cargo_charges));
		jQuery("#total_cargo_charges_id").val(fNum(total_cargo_charges));
		//END OF CARGO CHARGES
		
		//TOTAL
		var total_over_all = uNum(total_port_charges) + uNum(total_cargo_charges) + quick_total_charges;
		jQuery("#total_over_all_td").text(fNum(total_over_all));
		jQuery("#total_over_all_id").val(fNum(total_over_all));
		//END OF TOTAL
	}
}
//END OF COMPUTATIONS

function getAgentDetails(){
	jQuery('#agentresults').hide();

	jQuery.ajax({
		type: 'GET',
		url: "agent_details_ajax.php",
		data:  jQuery("#inputfrm_id").serialize(),

		success: function(data) {
			jQuery("#records_tab_wrapperonly_agent_details").html(data);
			jQuery('#agentresults').fadeIn(200);
		}
	});
}

$(document).ready(function() {
	jQuery('#vessel_id').focus();
	getAgentDetails();
	computeForTotal();
	deleteitem();
});
</script>
<style>
*{
	font-size:10px;
	font-family:Verdana, Arial, Helvetica, sans-serif;
}

.main_title{
	font-weight:bold;
	font-size:12px;
	color:#8596fa;
}

.title{
	font-weight:bold;
	font-size:10px;
}

.btn_1{
	border:1px solid #333333;
	background-color:#000000;
	color:#CCCCCC;
	padding:5px 30px;
	cursor:pointer;
	font-size:12px;
}

.label{
	vertical-align:top;
	font-weight:bold;
	width:130px;
}

.form{
	vertical-align:top;
}

.link_1{
	font-size:14px;
	font-weight:bold;
	color:#0033FF;
	text-decoration:none;
}
.link_1:hover{
	color:#000099;
	text-decoration:underline;
}
</style>
<?php
@session_start();
include_once(dirname(__FILE__)."/../includes/bootstrap.php");
date_default_timezone_set('UTC');

if(isset($_GET['confirmdelete'])){
	dbQuery("DELETE FROM _port_details WHERE id='".$_GET['id']."'");
	
	redirectjs("port_details.php?portname=".$_GET['portname'].'&vessel_name='.$_GET['vessel_name'].'&cargo_type='.$_GET['cargo_type'].'&dwt='.$_GET['dwt'].'&gross_tonnage='.$_GET['gross_tonnage'].'&net_tonnage='.$_GET['net_tonnage'].'&owner='.$_GET['owner'].'&date_from='.$_GET['date_from'].'&date_to='.$_GET['date_to'].'&num_of_days='.$_GET['num_of_days']);
}

if($_POST['submitok']==1){
	$port_name = $_GET['portname'];
	
	$print = array();
	
	$print['date'] = $_POST['date'];
	$print['date_to'] = $_POST['date_to'];
	$print['ship_agent'] = $_POST['ship_agent'];
	$print['vessel'] = $_POST['vessel'];
	$print['cargo_type'] = $_POST['cargo_type'];
	$print['dwt'] = $_POST['dwt'];
	$print['grt'] = $_POST['grt'];
	$print['nrt'] = $_POST['nrt'];
	$print['owner'] = $_POST['owner'];
	$print['da_details'] = $_POST['da_details'];
	$print['quick_total_charges'] = $_POST['quick_total_charges'];
	$print['voyage_number'] = $_POST['voyage_number'];
	$print['arrived_from'] = $_POST['arrived_from'];
	$print['loading'] = $_POST['loading'];
	$print['discharging'] = $_POST['discharging'];
	$print['bunkering'] = $_POST['bunkering'];
	$print['date_hour'] = $_POST['date_hour'];
	$print['sailed_for'] = $_POST['sailed_for'];
	$print['cargo_discharged'] = $_POST['cargo_discharged'];
	$print['quick_total_charges'] = $_POST['quick_total_charges'];
	$print['harbour_dues'] = $_POST['harbour_dues'];
	$print['light_dues'] = $_POST['light_dues'];
	$print['pilotage'] = $_POST['pilotage'];
	$print['towage'] = $_POST['towage'];
	$print['mooring_unmooring'] = $_POST['mooring_unmooring'];
	$print['shifting'] = $_POST['shifting'];
	$print['customs_charges'] = $_POST['customs_charges'];
	$print['launch_car_hire'] = $_POST['launch_car_hire'];
	$print['agency_remuniration'] = $_POST['agency_remuniration'];
	$print['telex_postage_telegrams'] = $_POST['telex_postage_telegrams'];
	$print['total_port_charges'] = $_POST['total_port_charges'];
	$print['stevedoring_expenses'] = $_POST['stevedoring_expenses'];
	$print['winchmen_cranage'] = $_POST['winchmen_cranage'];
	$print['tally'] = $_POST['tally'];
	$print['overtime'] = $_POST['overtime'];
	$print['total_cargo_charges'] = $_POST['total_cargo_charges'];
	$print['total_over_all'] = $_POST['total_over_all'];
	
	$data = serialize($print);
	
	$by_user = $user['email'];
	
	//FOR THE GRID
	$agent = explode(' = ', $print['ship_agent']);
	$ship_agent = $agent[0];
	$vessel = $print['vessel'];
	$dwt = $print['dwt'];
	$grt = $print['grt'];
	$nrt = $print['nrt'];
	
	$quick_total_charges = str_replace(',', '', $print['quick_total_charges']);
	$total_over_all = str_replace(',', '', $print['total_over_all']);
	$total_over_all = $total_over_all+$quick_total_charges;
	
	$date = $print['date'];
	$cargo_type = $print['cargo_type'];
	//END OF FOR THE GRID
	
	$sql = "INSERT INTO `_port_details` (`port_name`, `port_details`, `user_email`, `ship_agent`, `vessel`, `dwt`, `grt`, `nrt`, `total_over_all`, `date`, `cargo_type`, `dateadded`) VALUES('".mysql_escape_string($port_name)."', '".mysql_escape_string($data)."', '".mysql_escape_string($by_user)."', '".mysql_escape_string($ship_agent)."', '".mysql_escape_string($vessel)."', '".mysql_escape_string($dwt)."', '".mysql_escape_string($grt)."', '".mysql_escape_string($nrt)."', '".mysql_escape_string($total_over_all)."', '".mysql_escape_string($date)."', '".mysql_escape_string($cargo_type)."', NOW())";
	dbQuery($sql, $link);
	
	redirectjs("port_details.php?portname=".$port_name.'&vessel_name='.$_GET['vessel_name'].'&cargo_type='.$_GET['cargo_type'].'&dwt='.$_GET['dwt'].'&gross_tonnage='.$_GET['gross_tonnage'].'&net_tonnage='.$_GET['net_tonnage'].'&owner='.$_GET['owner'].'&date_from='.$_GET['date_from'].'&date_to='.$_GET['date_to'].'&num_of_days='.$_GET['num_of_days']);
}

if($_POST['submitok02']==1){
	$port_name = $_GET['portname'];
	
	$print = array();
	
	$print['date'] = $_POST['date'];
	$print['date_to'] = $_POST['date_to'];
	$print['ship_agent'] = $_POST['ship_agent'];
	$print['vessel'] = $_POST['vessel'];
	$print['cargo_type'] = $_POST['cargo_type'];
	$print['dwt'] = $_POST['dwt'];
	$print['grt'] = $_POST['grt'];
	$print['nrt'] = $_POST['nrt'];
	$print['owner'] = $_POST['owner'];
	$print['da_details'] = $_POST['da_details'];
	$print['quick_total_charges'] = $_POST['quick_total_charges'];
	$print['voyage_number'] = $_POST['voyage_number'];
	$print['arrived_from'] = $_POST['arrived_from'];
	$print['loading'] = $_POST['loading'];
	$print['discharging'] = $_POST['discharging'];
	$print['bunkering'] = $_POST['bunkering'];
	$print['date_hour'] = $_POST['date_hour'];
	$print['sailed_for'] = $_POST['sailed_for'];
	$print['cargo_discharged'] = $_POST['cargo_discharged'];
	$print['quick_total_charges'] = $_POST['quick_total_charges'];
	$print['harbour_dues'] = $_POST['harbour_dues'];
	$print['light_dues'] = $_POST['light_dues'];
	$print['pilotage'] = $_POST['pilotage'];
	$print['towage'] = $_POST['towage'];
	$print['mooring_unmooring'] = $_POST['mooring_unmooring'];
	$print['shifting'] = $_POST['shifting'];
	$print['customs_charges'] = $_POST['customs_charges'];
	$print['launch_car_hire'] = $_POST['launch_car_hire'];
	$print['agency_remuniration'] = $_POST['agency_remuniration'];
	$print['telex_postage_telegrams'] = $_POST['telex_postage_telegrams'];
	$print['total_port_charges'] = $_POST['total_port_charges'];
	$print['stevedoring_expenses'] = $_POST['stevedoring_expenses'];
	$print['winchmen_cranage'] = $_POST['winchmen_cranage'];
	$print['tally'] = $_POST['tally'];
	$print['overtime'] = $_POST['overtime'];
	$print['total_cargo_charges'] = $_POST['total_cargo_charges'];
	$print['total_over_all'] = $_POST['total_over_all'];
	
	$data = serialize($print);
	
	$by_user = $user['email'];
	
	//FOR THE GRID
	$agent = explode(' = ', $print['ship_agent']);
	$ship_agent = $agent[0];
	$vessel = $print['vessel'];
	$dwt = $print['dwt'];
	$grt = $print['grt'];
	$nrt = $print['nrt'];
	
	$quick_total_charges = str_replace(',', '', $print['quick_total_charges']);
	$total_over_all = str_replace(',', '', $print['total_over_all']);
	$total_over_all = $total_over_all+$quick_total_charges;
	
	$date = $print['date'];
	$cargo_type = $print['cargo_type'];
	//END OF FOR THE GRID
	
	$sql = "UPDATE `_port_details` SET
				`port_name`='".mysql_escape_string($port_name)."', 
				`port_details`='".mysql_escape_string($data)."', 
				`user_email`='".mysql_escape_string($by_user)."', 
				`ship_agent`='".mysql_escape_string($ship_agent)."', 
				`vessel`='".mysql_escape_string($vessel)."', 
				`dwt`='".mysql_escape_string($dwt)."', 
				`grt`='".mysql_escape_string($grt)."', 
				`nrt`='".mysql_escape_string($nrt)."', 
				`total_over_all`='".mysql_escape_string($total_over_all)."', 
				`date`='".mysql_escape_string($date)."', 
				`cargo_type`='".mysql_escape_string($cargo_type)."'
			WHERE `id`='".$_GET['id']."'";
	dbQuery($sql, $link);
	
	redirectjs("port_details.php?portname=".$port_name.'&vessel_name='.$_GET['vessel_name'].'&cargo_type='.$_GET['cargo_type'].'&dwt='.$_GET['dwt'].'&gross_tonnage='.$_GET['gross_tonnage'].'&net_tonnage='.$_GET['net_tonnage'].'&owner='.$_GET['owner'].'&date_from='.$_GET['date_from'].'&date_to='.$_GET['date_to'].'&num_of_days='.$_GET['num_of_days']);
}
?>

<form id="inputfrm_id" name="inputfrm" method="post" enctype="multipart/form-data">
<div style="width:100%; height:auto; padding:20px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="500" valign="top">
		<div style="padding-bottom:20px;">
			<table id="flexigrid1" align="left"></table>
			<script type="text/javascript">
			$("document").ready(function(){
			vars = {
					url: '../js/grid/jquery_post/post_ports_d_a.php?portname=<?php echo $_GET['portname']; ?>&vessel_name=<?php echo $_GET['vessel_name']; ?>&cargo_type=<?php echo $_GET['cargo_type']; ?>&dwt=<?php echo $_GET['dwt']; ?>&gross_tonnage=<?php echo $_GET['gross_tonnage']; ?>&net_tonnage=<?php echo $_GET['net_tonnage']; ?>&owner=<?php echo $_GET['owner']; ?>&date_from=<?php echo $_GET['date_from']; ?>&date_to=<?php echo $_GET['date_to']; ?>&num_of_days=<?php echo $_GET['num_of_days']; ?>',
					dataType: 'json',
					colModel : [
						{display: '-', name : 'actions', width : 50, sortable : false, searchable: false, align: 'center'},
						{display: '#', name : 'id', width : 50, sortable : true, align: 'center'},
						{display: 'Agent', name : 'ship_agent', width : 200, sortable : true, align: 'left'}, 
						{display: 'Vessel', name : 'vessel', width : 200, sortable : true, align: 'left'}, 
						{display: 'DWT', name : 'dwt', width : 60, sortable : true, align: 'left'}, 
						{display: 'GRT', name : 'grt', width : 60, sortable : true, align: 'left'}, 
						{display: 'NRT', name : 'nrt', width : 60, sortable : true, align: 'left'}, 
						{display: 'Amount', name : 'total_over_all', width : 130, sortable : true, align: 'left'}, 
						{display: 'Date', name : 'date', width : 130, sortable : true, align: 'left'}, 
						{display: 'Cargo Type', name : 'cargo_type', width : 130, sortable : true, align: 'left'}
					],
					buttons : [],
					resizable: false,
					sortname: "id",
					sortorder: "asc",
					usepager: true,
					title: "D/A Charges",
					useRp: true,
					rp: 20,
					showTableToggleBtn: false,
					autoload: true,
					width: 500,
					height: 400,
					singleSelect: true,
					useInlineSearch: true
					};
							
			$("#flexigrid1").flexigrid( vars );							 
			});
			</script>
		</div>
		<div>
			<table id="flexigrid2" align="left"></table>
			<script type="text/javascript">
			$("document").ready(function(){
			vars = {
					url: '../js/grid/jquery_post/post_cargos2.php?portname=<?php echo $_GET['portname']; ?>&vessel_name=<?php echo $_GET['vessel_name']; ?>&cargo_type=<?php echo $_GET['cargo_type']; ?>&dwt=<?php echo $_GET['dwt']; ?>&gross_tonnage=<?php echo $_GET['gross_tonnage']; ?>&net_tonnage=<?php echo $_GET['net_tonnage']; ?>&owner=<?php echo $_GET['owner']; ?>&date_from=<?php echo $_GET['date_from']; ?>&date_to=<?php echo $_GET['date_to']; ?>&num_of_days=<?php echo $_GET['num_of_days']; ?>',
					dataType: 'json',
					colModel : [
						{display: '-', name : 'actions', width : 50, sortable : false, searchable: false, align: 'center'},
						{display: '#', name : 'a.id', width : 50, sortable : true, align: 'center'},
						{display: 'Agent', name : 'b.ship_agent', width : 200, sortable : true, align: 'left'}, 
						{display: 'Cargo Qty', name : 'a.cargo_quantity', width : 80, sortable : true, align: 'left'}, 
						{display: 'Port Costs', name : 'a.port_costs', width : 80, sortable : true, align: 'left'}, 
						{display: 'AVR Intake', name : 'a.load_port2', width : 130, sortable : true, align: 'left'}, 
						{display: 'Qty MT', name : 'a.load_port_quantity', width : 130, sortable : true, align: 'left'}, 
						{display: 'Channel M', name : 'a.channel', width : 130, sortable : true, align: 'left'}, 
						{display: 'Anchorage M', name : 'a.anchorage', width : 130, sortable : true, align: 'left'}, 
						{display: 'Cargo Pier M', name : 'a.cargo_pier', width : 130, sortable : true, align: 'left'}
					],
					buttons : [],
					resizable: false,
					sortname: "id",
					sortorder: "asc",
					usepager: true,
					title: "Cargo Card Lists",
					useRp: true,
					rp: 20,
					showTableToggleBtn: false,
					autoload: true,
					width: 500,
					height: 400,
					singleSelect: true,
					useInlineSearch: true
					};
							
			$("#flexigrid2").flexigrid( vars );							 
			});
			</script>
		</div>
	</td>
    <td width="20">&nbsp;</td>
    <td width="300" valign="top">
		<?php
		if(isset($_GET['view'])){
		
		$sql2 = "SELECT * FROM cargos WHERE id='".$_GET['id']."'";
		$data = dbQuery($sql2);
		$data = $data[0];
		
		$load_port = trim(htmlentities($data['load_port']));
		$cargo_date = date('M d, Y', strtotime($data['cargo_date']));
		$dwt_or_ship_type = trim(htmlentities($data['dwt_or_ship_type']));
		$cargo_type = trim(htmlentities($data['cargo_type']));
		$cargo_quantity = trim(htmlentities($data['cargo_quantity']));
		$port_costs = trim(htmlentities($data['port_costs']));
		$load_port2 = trim(htmlentities($data['load_port2']));
		$load_port_quantity = trim(htmlentities($data['load_port_quantity']));
		$channel = trim(htmlentities($data['channel']));
		$anchorage = trim(htmlentities($data['anchorage']));
		$cargo_pier = trim(htmlentities($data['cargo_pier']));
		$notes = trim(htmlentities($data['notes']));
		$by_agent = trim(htmlentities($data['by_agent']));
		$dateadded = date('M d, Y h:i:s', strtotime($data['dateadded']));
		$dateupdated = date('M d, Y h:i:s', strtotime($data['dateupdated']));
		?>
		<table width="300" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td colspan="3" height="50"><a href="port_details.php?portname=<?php echo $_GET['portname']; ?>&vessel_name=<?php echo $_GET['vessel_name']; ?>&cargo_type=<?php echo $_GET['cargo_type']; ?>&dwt=<?php echo $_GET['dwt']; ?>&gross_tonnage=<?php echo $_GET['gross_tonnage']; ?>&net_tonnage=<?php echo $_GET['net_tonnage']; ?>&owner=<?php echo $_GET['owner']; ?>&date_from=<?php echo $_GET['date_from']; ?>&date_to=<?php echo $_GET['date_to']; ?>&num_of_days=<?php echo $_GET['num_of_days']; ?>" class="link_1">&laquo; show ports D/A form</a></td>
			</tr>
			<tr bgcolor="cddee5">
				<td colspan="3"><div style="padding:5px; font-weight:bold;">CARGO CARD DETAILS</div></td>
			</tr>
			<tr>
				<td colspan="3" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td class='label'>Load Port:</td>
				<td width="10" align="center" valign="top">:</td>
				<td class='form'><?php echo $load_port; ?></td>
			</tr>
			<tr>
				<td colspan="3" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td class='label'>Date:</td>
				<td width="10" align="center" valign="top">:</td>
				<td class='form'><?php echo $cargo_date; ?></td>
			</tr>
			<tr>
				<td colspan="3" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td class='label'>DWT or Ship Type:</td>
				<td width="10" align="center" valign="top">:</td>
				<td class='form'><?php echo $dwt_or_ship_type; ?></td>
			</tr>
			<tr>
				<td colspan="3" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td class='label'>Cargo Type:</td>
				<td width="10" align="center" valign="top">:</td>
				<td class='form'><?php echo $cargo_type; ?></td>
			</tr>
			<tr>
				<td colspan="3" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td class='label'>Cargo Quantity:</td>
				<td width="10" align="center" valign="top">:</td>
				<td class='form'><?php echo $cargo_quantity; ?></td>
			</tr>
			<tr>
				<td colspan="3" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td class='label'>Port Costs:</td>
				<td width="10" align="center" valign="top">:</td>
				<td class='form'><?php echo $port_costs; ?></td>
			</tr>
			<tr>
				<td colspan="3" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td class='label'>AVR Intake:</td>
				<td width="10" align="center" valign="top">:</td>
				<td class='form'><?php echo $load_port2; ?></td>
			</tr>
			<tr>
				<td colspan="3" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td class='label'> Quantity MT:</td>
				<td width="10" align="center" valign="top">:</td>
				<td class='form'><?php echo $load_port_quantity; ?></td>
			</tr>
			<tr>
				<td colspan="3" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td class='label'>Channel M:</td>
				<td width="10" align="center" valign="top">:</td>
				<td class='form'><?php echo $channel; ?></td>
			</tr>
			<tr>
				<td colspan="3" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td class='label'>Anchorage M:</td>
				<td width="10" align="center" valign="top">:</td>
				<td class='form'><?php echo $anchorage; ?></td>
			</tr>
			<tr>
				<td colspan="3" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td class='label'>Cargo Pier M:</td>
				<td width="10" align="center" valign="top">:</td>
				<td class='form'><?php echo $cargo_pier; ?></td>
			</tr>
			<tr>
				<td colspan="3" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td class='label'>Notes:</td>
				<td width="10" align="center" valign="top">:</td>
				<td class='form'><?php echo $notes; ?></td>
			</tr>
			<tr>
				<td colspan="3" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td class='label'>Agent:</td>
				<td width="10" align="center" valign="top">:</td>
				<td class='form'><?php echo $by_agent; ?></td>
			</tr>
			<tr>
				<td colspan="3" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td class='label'>Date Added:</td>
				<td width="10" align="center" valign="top">:</td>
				<td class='form'><?php echo $dateadded; ?></td>
			</tr>
			<tr>
				<td colspan="3" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td class='label'>Date Updated:</td>
				<td width="10" align="center" valign="top">:</td>
				<td class='form'><?php echo $dateupdated; ?></td>
			</tr>
		</table>
		<?php
		}else{
		
		$date_from = $_GET['date_from'];
		$date_from = explode('/', $date_from);
		$date_from = $date_from[1].'/'.$date_from[0].'/'.$date_from[2];
		
		
		$date_to = $_GET['date_to'];
		$date_to = explode('/', $date_to);
		$date_to = $date_to[1].'/'.$date_to[0].'/'.$date_to[2];
		$date_to = date('m/d/Y H:i', strtotime($date_to. ' + '.$_GET['num_of_days'].' days'));
		
		$vessel_name = $_GET['vessel_name'];
		$cargo_type = $_GET['cargo_type'];
		$dwt = str_replace(' tons', '', $_GET['dwt']);
		$grt = str_replace(' tons', '', $_GET['gross_tonnage']);
		$nrt = str_replace(' tons', '', $_GET['nrt']);
		$owner = $_GET['owner'];
		
		if(isset($_GET['edit'])){
			$sql_da = "SELECT * FROM _port_details WHERE id='".$_GET['id']."'";
			$data = dbQuery($sql_da);
		
			$id = $data[0]['id'];
			
			$result = unserialize($data[0]['port_details']);
			
			$date_from = trim(htmlentities($result['date']));
			$date_to = trim(htmlentities($result['date_to']));
			$ship_agent = trim(htmlentities($result['ship_agent']));
			$vessel_name = trim(htmlentities($result['vessel']));
			$cargo_type = trim(htmlentities($result['cargo_type']));
			$dwt = trim(htmlentities($result['dwt']));
			$grt = trim(htmlentities($result['grt']));
			$nrt = trim(htmlentities($result['nrt']));
			$owner = trim(htmlentities($result['owner']));
			$da_details = trim(htmlentities($result['da_details']));
			$quick_total_charges = trim(htmlentities($result['quick_total_charges']));
			$voyage_number = trim(htmlentities($result['voyage_number']));
			$arrived_from = trim(htmlentities($result['arrived_from']));
			$loading = trim(htmlentities($result['loading']));
			$discharging = trim(htmlentities($result['discharging']));
			$bunkering = trim(htmlentities($result['bunkering']));
			$date_hour = trim(htmlentities($result['date_hour']));
			$sailed_for = trim(htmlentities($result['sailed_for']));
			$cargo_discharged = trim(htmlentities($result['cargo_discharged']));
			$quick_total_charges = trim(htmlentities($result['quick_total_charges']));
			$harbour_dues = trim(htmlentities($result['harbour_dues']));
			$light_dues = trim(htmlentities($result['light_dues']));
			$pilotage = trim(htmlentities($result['pilotage']));
			$towage = trim(htmlentities($result['towage']));
			$mooring_unmooring = trim(htmlentities($result['mooring_unmooring']));
			$shifting = trim(htmlentities($result['shifting']));
			$customs_charges = trim(htmlentities($result['customs_charges']));
			$launch_car_hire = trim(htmlentities($result['launch_car_hire']));
			$agency_remuniration = trim(htmlentities($result['agency_remuniration']));
			$telex_postage_telegrams = trim(htmlentities($result['telex_postage_telegrams']));
			$total_port_charges = trim(htmlentities($result['total_port_charges']));
			$stevedoring_expenses = trim(htmlentities($result['stevedoring_expenses']));
			$winchmen_cranage = trim(htmlentities($result['winchmen_cranage']));
			$tally = trim(htmlentities($result['tally']));
			$overtime = trim(htmlentities($result['overtime']));
			$total_cargo_charges = trim(htmlentities($result['total_cargo_charges']));
			$total_over_all = trim(htmlentities($result['total_over_all']));
		}
		?>
		<table width="300" border="0" cellspacing="0" cellpadding="0">
			<tr bgcolor="cddee5">
				<td colspan="2"><div style="padding:5px; font-weight:bold; color:#FF0000;"><?php echo $_GET['portname']; ?></div></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td width="130">Laycan</td>
				<td><input type="text" id="date_id" name="date" readonly="readonly" style="width:150px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo date('m/d/Y H:i', strtotime($date_from)); ?>" /></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input type="text" id="date_to_id" name="date_to" readonly="readonly" style="width:150px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $date_to; ?>" /></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td>Ship Agent</td>
				<td>
					<input type="text" id="ship_agent_id" name="ship_agent" style="width:150px; border:1px solid #CCCCCC; padding:3px;" onblur="getAgentDetails();" value="<?php echo $ship_agent; ?>" />
					<script type="text/javascript">
					jQuery("#ship_agent_id").focus().autocomplete(agent);
					jQuery("#ship_agent_id").setOptions({
						scrollHeight: 180
					});
					</script>
				</td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td>Vessel</td>
				<td><input type="text" id="vessel_id" name="vessel" style="width:150px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $vessel_name; ?>" readonly="readonly" /></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td>Cargo Type</td>
				<td><input type="text" id="cargo_type_id" name="cargo_type" style="width:150px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $cargo_type; ?>" readonly="readonly" /></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td>DWT</td>
				<td><input type="text" onblur="this.value=fNum(this.value);" id="dwt_id" name="dwt" style="width:150px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $dwt; ?>" /></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td>GRT</td>
				<td><input type="text" onblur="this.value=fNum(this.value);" id="grt_id" name="grt" style="width:150px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $grt; ?>" /></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td>NRT</td>
				<td><input type="text" onblur="this.value=fNum(this.value);" id="nrt_id" name="nrt" style="width:150px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $nrt; ?>" /></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td>Manager</td>
				<td><input type="text" id="owner_id" name="owner" style="width:150px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $owner; ?>" /></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td valign="top">D/A Details</td>
				<td><textarea id="da_details_id" name="da_details" style="width:150px; height:200px; border:1px solid #CCCCCC; padding:3px;"><?php echo $da_details; ?></textarea></td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr bgcolor="cddee5">
				<td colspan="2"><div style="padding:5px; font-weight:bold;">QUICK TOTAL CHARGES</div></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td>Quick Total Charges</td>
				<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="quick_total_charges_id" name="quick_total_charges" style="width:150px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $quick_total_charges; ?>" /></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr bgcolor="cddee5">
				<td colspan="2">
					<div style="float:left; width:auto; height:auto; padding:5px;"><img src='../images/icon_dropdown_warning_shore.png' width='20' height='18' style='cursor:pointer;' onclick="expand();" id="arrow1" /></div>
					<div style="float:left; width:auto; height:auto; padding:8px 5px 5px; font-weight:bold;">OTHER DETAILS</div>
				</td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			</table>
			<table width="300" border="0" cellspacing="0" cellpadding="0" id="other_details_table_id" style="display:none;">
			<tr>
				<td width="130">Voyage #</td>
				<td><input type="text" id="voyage_number_id" name="voyage_number" style="width:150px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $voyage_number; ?>" /></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td>Arrived From</td>
				<td>
					<input type="text" id="arrived_from_id" name="arrived_from" style="width:150px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $arrived_from; ?>" />
					<script type="text/javascript">
					jQuery("#arrived_from_id").focus().autocomplete(veson_ports);
					jQuery("#arrived_from_id").setOptions({
						scrollHeight: 180
					});
					</script>
				</td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td>Loading</td>
				<td>
				<?php if($loading=='Yes'){ ?>
					<input type="radio" id="loading_id" name="loading" value="Yes" checked="checked" /> Yes &nbsp;&nbsp;&nbsp; <input type="radio" id="loading_id" name="loading" value="No" /> No
				<?php }else{ ?>
					<input type="radio" id="loading_id" name="loading" value="Yes" /> Yes &nbsp;&nbsp;&nbsp; <input type="radio" id="loading_id" name="loading" value="No" checked="checked" /> No
				<?php } ?>
				</td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td>Discharging</td>
				<td>
				<?php if($discharging=='Yes'){ ?>
					<input type="radio" id="discharging_id" name="discharging" value="Yes" checked="checked" /> Yes &nbsp;&nbsp;&nbsp; <input type="radio" id="discharging_id" name="discharging" value="No" /> No
				<?php }else{ ?>
					<input type="radio" id="discharging_id" name="discharging" value="Yes" /> Yes &nbsp;&nbsp;&nbsp; <input type="radio" id="discharging_id" name="discharging" value="No" checked="checked" /> No
				<?php } ?>
				</td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td>Bunkering</td>
				<td>
				<?php if($bunkering=='Yes'){ ?>
					<input type="radio" id="bunkering_id" name="bunkering" value="Yes" checked="checked" /> Yes &nbsp;&nbsp;&nbsp; <input type="radio" id="bunkering_id" name="bunkering" value="No" /> No
				<?php }else{ ?>
					<input type="radio" id="bunkering_id" name="bunkering" value="Yes" /> Yes &nbsp;&nbsp;&nbsp; <input type="radio" id="bunkering_id" name="bunkering" value="No" checked="checked" /> No
				<?php } ?>
				</td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td>Date/Hour</td>
				<td><input type="text" id="date_hour_id" name="date_hour" style="width:150px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $date_hour; ?>" /></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td>Sailed For</td>
				<td>
					<input type="text" id="sailed_for_id" name="sailed_for" style="width:150px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $sailed_for; ?>" />
					<script type="text/javascript">
					jQuery("#sailed_for_id").focus().autocomplete(veson_ports);
					jQuery("#sailed_for_id").setOptions({
						scrollHeight: 180
					});
					</script>
				</td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td>Cargo Discharged</td>
				<td><input type="text" id="cargo_discharged_id" name="cargo_discharged" style="width:150px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $cargo_discharged; ?>" /></td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr bgcolor="cddee5">
				<td colspan="2"><div style="padding:5px; font-weight:bold;">PORT CHARGES</div></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td>Harbour Dues</td>
				<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="harbour_dues_id" name="harbour_dues" style="width:150px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $harbour_dues; ?>" /></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td>Light Dues</td>
				<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="light_dues_id" name="light_dues" style="width:150px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $light_dues; ?>" /></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td>Pilotage</td>
				<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="pilotage_id" name="pilotage" style="width:150px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $pilotage; ?>" /></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td>Towage</td>
				<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="towage_id" name="towage" style="width:150px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $towage; ?>" /></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td>Mooring/Unmooring</td>
				<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="mooring_unmooring_id" name="mooring_unmooring" style="width:150px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $mooring_unmooring; ?>" /></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td>Shifting</td>
				<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="shifting_id" name="shifting" style="width:150px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $shifting; ?>" /></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td>Customs Charges</td>
				<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="customs_charges_id" name="customs_charges" style="width:150px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $customs_charges; ?>" /></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td>Launch/Car Hire</td>
				<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="launch_car_hire_id" name="launch_car_hire" style="width:150px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $launch_car_hire; ?>" /></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td>Agency Remuniration</td>
				<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="agency_remuniration_id" name="agency_remuniration" style="width:150px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $agency_remuniration; ?>" /></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td>Telex, Postage, Telegrams</td>
				<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="telex_postage_telegrams_id" name="telex_postage_telegrams" style="width:150px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $telex_postage_telegrams; ?>" /></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td><b>Total</b></td>
				<td id="total_port_charges_td">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr bgcolor="cddee5">
				<td colspan="2"><div style="padding:5px; font-weight:bold;">CARGO CHARGES</div></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td>Stevedoring Expenses</td>
				<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="stevedoring_expenses_id" name="stevedoring_expenses" style="width:150px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $stevedoring_expenses; ?>" /></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td>Winchmen/Cranage</td>
				<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="winchmen_cranage_id" name="winchmen_cranage" style="width:150px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $winchmen_cranage; ?>" /></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td>Tally</td>
				<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="tally_id" name="tally" style="width:150px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $tally; ?>" /></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td>Overtime</td>
				<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="overtime_id" name="overtime" style="width:150px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $overtime; ?>" /></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td><b>Total</b></td>
				<td id="total_cargo_charges_td">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr>
				<td><b>Over All Total</b></td>
				<td id="total_over_all_td">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
			</table>
			<table width="300" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td>
					<input type="hidden" id="total_port_charges_id" name="total_port_charges" value="<?php echo $total_port_charges; ?>" />
					<input type="hidden" id="total_cargo_charges_id" name="total_cargo_charges" value="<?php echo $total_cargo_charges; ?>" />
					<input type="hidden" id="total_over_all_id" name="total_over_all" value="<?php echo $total_over_all; ?>" />
					
					<?php if(isset($_GET['edit'])){ ?>
						<input type="button" name="btn_cancel" value="cancel" onClick="location.href='port_details.php?portname=<?php echo $_GET['portname']; ?>&vessel_name=<?php echo $_GET['vessel_name']; ?>&cargo_type=<?php echo $_GET['cargo_type']; ?>&dwt=<?php echo $_GET['dwt']; ?>&gross_tonnage=<?php echo $_GET['gross_tonnage']; ?>&net_tonnage=<?php echo $_GET['net_tonnage']; ?>&owner=<?php echo $_GET['owner']; ?>&date_from=<?php echo $_GET['date_from']; ?>&date_to=<?php echo $_GET['date_to']; ?>&num_of_days=<?php echo $_GET['num_of_days']; ?>';" class="btn_1" /> &nbsp;&nbsp;&nbsp;&nbsp; <input type="hidden" name="submitok02" value="1"><input type="button" id="btn_update_id" name="btn_update" value="update" onClick="updateForm();" class="btn_1" />
					<?php }else{ ?>
						<input type="hidden" name="submitok" value="1"><input type="button" id="btn_save_id" name="btn_save" value="save" class="btn_1" onClick="saveForm();" />
					<?php } ?>
				</td>
			</tr>
		</table>
		<?php } ?>
	</td>
	<td width="20">&nbsp;</td>
	<td valign="top">
		<?php
		if(isset($_GET['view'])){
		
		$sql = "SELECT * FROM `_port_agents` WHERE `email`='".$by_agent."' ORDER BY dateadded DESC LIMIT 0,1";
		$r = dbQuery($sql);
		?>
		<table width="280" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td colspan="2" height="50">&nbsp;</td>
			</tr>
			<tr bgcolor="cddee5">
				<td colspan="2"><div style="padding:5px; font-weight:bold;">AGENT'S MAIN DETAILS</div></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td width="110" class='label'>Company Name</td>
				<td> : <?php echo $r[0]['company_name']; ?></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td class='label'>Business Type</td>
				<td> : <?php echo $r[0]['business_type']; ?></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td class='label'>Address</td>
				<td> : <?php echo $r[0]['address']; ?></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td class='label'>City</td>
				<td> : <?php echo $r[0]['city']; ?></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td class='label'>Postal Code</td>
				<td> : <?php echo $r[0]['postal_code']; ?></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td class='label'>Country</td>
				<td> : <?php echo $r[0]['country']; ?></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td class='label'>Fax</td>
				<td> : <?php echo $r[0]['fax']; ?></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td class='label'>Website</td>
				<td> : <?php echo $r[0]['website']; ?></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr bgcolor="cddee5">
				<td colspan="2"><div style="padding:5px; font-weight:bold;">CONTACT DETAILS</div></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td class='label'>First Name</td>
				<td> : <?php echo $r[0]['first_name']; ?></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td class='label'>Last Name</td>
				<td> : <?php echo $r[0]['last_name']; ?></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td class='label'>Office Number</td>
				<td> : <?php echo $r[0]['office_number']; ?></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td class='label'>Mobile Number</td>
				<td> : <?php echo $r[0]['mobile_number']; ?></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td class='label'>Fax Number</td>
				<td> : <?php echo $r[0]['fax_number']; ?></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td class='label'>Telex</td>
				<td> : <?php echo $r[0]['telex']; ?></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td class='label'>Email Address</td>
				<td> : <?php echo $r[0]['email']; ?></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td class='label'>Skype ID</td>
				<td> : <?php echo $r[0]['skype']; ?></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td class='label'>Yahoo ID</td>
				<td> : <?php echo $r[0]['yahoo']; ?></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td class='label'>MSN ID</td>
				<td> : <?php echo $r[0]['msn']; ?></td>
			</tr>
		</table>
		<?php }else{ ?>
		<div id='agentresults'>
			<div id='records_tab_wrapperonly_agent_details'></div>
		</div>
		<?php } ?>
	</td>
  </tr>
</table>
</div>
</form>

<center>
<table width="100%" height="100%" id="pleasewait" style="display:none; position:fixed; top:0; left:0; z-index:100; background-image:url('../images/overlay.png'); background-position:center; background-attachment:scroll; filter:alpha(opacity=90); opacity:0.9;">
	<tr>
        <td height="50" style="border-bottom:none;"></td>
    </tr>
    <tr>
        <td align="center" valign="middle"><img src="../images/loading.gif" /></td>
    </tr>
</table>
</center>