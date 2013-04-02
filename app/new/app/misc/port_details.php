<?php
@session_start();

$_SESSION['portname'] = $_GET['portname'];
$_SESSION['vessel_name'] = $_GET['vessel_name'];
$_SESSION['cargo_type'] = $_GET['cargo_type'];
$_SESSION['dwt'] = $_GET['dwt'];
$_SESSION['gross_tonnage'] = $_GET['gross_tonnage'];
$_SESSION['net_tonnage'] = $_GET['net_tonnage'];
$_SESSION['owner'] = $_GET['owner'];
$_SESSION['date_from'] = $_GET['date_from'];
$_SESSION['date_to'] = $_GET['date_to'];
$_SESSION['num_of_days'] = $_GET['num_of_days'];
?>
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

function clearForm(){
	inputArray = document.getElementsByTagName("input");

	for (var index = 0; index < inputArray.length; index++){
		if(inputArray[index].type == 'text' || inputArray[index].type == 'hidden'){
			inputArray[index].value = "";
		}
	}
	
	jQuery("#total_port_charges_td").text('');
	jQuery("#total_cargo_charges_td").text('');
	jQuery("#total_ship_charges_td").text('');
	jQuery("#total_statement_td").text('');
	jQuery("#total_over_all_td").text('');
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

function showPortDetails(portname, id){
	jQuery('#portresults').hide();

	jQuery('#pleasewait').show();
	
	var counter = 0;
	jQuery(".rows").each(function(){
		if(counter%2==0){
			jQuery(this).css("background-color", "#f5f5f5");
		}else{
			jQuery(this).css("background-color", "#e9e9e9");
		}
		
		counter++;
	});
	jQuery('#row_'+id).css("background-color", "#fffdc3");
	
	var vessel_name = '<?php echo $_GET['vessel_name']; ?>';
	var cargo_type = '<?php echo $_GET['cargo_type']; ?>';
	var dwt = '<?php echo $_GET['dwt']; ?>';
	var gross_tonnage = '<?php echo $_GET['gross_tonnage']; ?>';
	var net_tonnage = '<?php echo $_GET['net_tonnage']; ?>';
	var owner = '<?php echo $_GET['owner']; ?>';
	var date_from = '<?php echo $_GET['date_from']; ?>';
	var date_to = '<?php echo $_GET['date_to']; ?>';
	var num_of_days = '<?php echo $_GET['num_of_days']; ?>';

	jQuery.ajax({
		type: 'GET',
		url: "port_details_ajax.php?portname="+portname+'&vessel_name='+vessel_name+'&cargo_type='+cargo_type+'&dwt='+dwt+'&gross_tonnage='+gross_tonnage+'&net_tonnage='+net_tonnage+'&owner='+owner+'&date_from='+date_from+'&date_to='+date_to+'&num_of_days='+num_of_days+"&id="+id,
		data:  '',

		success: function(data) {
			jQuery("#records_tab_wrapperonly_port_details").html(data);
			jQuery('#portresults').fadeIn(200);
			
			jQuery('#pleasewait').hide();
		}
	});
}

function getAgentDetails(agent_email){
	jQuery('#agentresults').hide();

	//jQuery('#pleasewait').show();

	jQuery.ajax({
		type: 'GET',
		url: "agent_details_ajax.php?agent_email="+agent_email,
		data:  jQuery("#inputfrm_id").serialize(),

		success: function(data) {
			jQuery("#records_tab_wrapperonly_agent_details").html(data);
			jQuery('#agentresults').fadeIn(200);
			
			//jQuery('#pleasewait').hide();
		}
	});
}
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
	font-weight:bold;
	width:200px;
}
</style>
<?php
@session_start();
include_once(dirname(__FILE__)."/../includes/bootstrap.php");
date_default_timezone_set('UTC');

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
	
	$sql = "INSERT INTO `_port_details` (`port_name`, `port_details`, `user_email`, `dateadded`) VALUES('".mysql_escape_string($port_name)."', '".mysql_escape_string($data)."', '".mysql_escape_string($by_user)."', NOW())";
	dbQuery($sql, $link);
	
	redirectjs("port_details.php?portname=".$port_name.'&vessel_name='.$_GET['vessel_name'].'&cargo_type='.$_GET['cargo_type'].'&dwt='.$_GET['dwt'].'&gross_tonnage='.$_GET['gross_tonnage'].'&net_tonnage='.$_GET['net_tonnage'].'&owner='.$_GET['owner'].'&date_from='.$_GET['date_from'].'&date_to='.$_GET['date_to'].'&num_of_days='.$_GET['num_of_days']);
}

if(isset($_GET['portname'])){
	echo '<form id="inputfrm_id" name="inputfrm" method="post" enctype="multipart/form-data">';
	
	$dwt = str_replace(' tons', '', $_GET['dwt']);
	$dwt = intval(str_replace(',', '', $dwt));
	
	if($dwt>=0 && $dwt<=10000){
		$dwt_low = 0;
		$dwt_high = 10000;
	}else if($dwt>=10000 && $dwt<=35000){
		$dwt_low = 10000;
		$dwt_high = 35000;
	}else if($dwt>=35000 && $dwt<=60000){
		$dwt_low = 35000;
		$dwt_high = 60000;
	}else if($dwt>=60000 && $dwt<=75000){
		$dwt_low = 60000;
		$dwt_high = 75000;
	}else if($dwt>=75000 && $dwt<=110000){
		$dwt_low = 75000;
		$dwt_high = 110000;
	}else if($dwt>=110000 && $dwt<=150000){
		$dwt_low = 110000;
		$dwt_high = 150000;
	}else if($dwt>=150000 && $dwt<=555000){
		$dwt_low = 150000;
		$dwt_high = 555000;
	}else{
		$dwt_low = 0;
		$dwt_high = 555000;
	}
	
	$sql = "SELECT * FROM `_port_details` WHERE `port_name`='".$_GET['portname']."' ORDER BY dateadded DESC";
	$r = dbQuery($sql);
	
	$t = count($r);
	
	if($t){
		?>
		<table width="1120" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="500" valign="top">
					<table width="500" border="0" cellspacing="0" cellpadding="0">
						<tr bgcolor="cddee5">
							<td colspan="9"><div style="padding:5px; font-weight:bold;"><?php echo $_GET['portname']; ?></div></td>
						</tr>
						<tr bgcolor="cddee5">
							<td><div style="padding:5px; font-weight:bold;"><img src="../images/icon_book.png" /></div></td>
							<td><div style="padding:5px; font-weight:bold;">AGENT</div></td>
							<td><div style="padding:5px; font-weight:bold;">VESSEL</div></td>
							<td><div style="padding:5px; font-weight:bold;">DWT</div></td>
							<td><div style="padding:5px; font-weight:bold;">GRT</div></td>
							<td><div style="padding:5px; font-weight:bold;">NRT</div></td>
							<td><div style="padding:5px; font-weight:bold;">AMOUNT</div></td>
							<td><div style="padding:5px; font-weight:bold;">DATE</div></td>
							<td><div style="padding:5px; font-weight:bold;">CARGO TYPE</div></td>
						</tr>
						<?php
						for($i=0; $i<$t; $i++){
							$details = unserialize($r[$i]['port_details']);
							$agent = explode(' - ', $details['ship_agent']);
							$agent_name = $agent[0];
							
							$total_over_all = $details['total_over_all'];
							if($total_over_all==0 || $total_over_all==''){
								$total_over_all = $details['quick_total_charges'];
							}
							
							if($i%2==0){
								$bgcolor = 'f5f5f5';
							}else{
								$bgcolor = 'e9e9e9';
							}
							
							$dwt_rec = str_replace(' tons', '', $details['dwt']);
							$dwt_rec = intval(str_replace(',', '', $dwt_rec));
							
							if($dwt_rec>=$dwt_low && $dwt_rec<=$dwt_high){
							?>
							<tr bgcolor="<?php echo $bgcolor; ?>" id="row_<?php echo $r[$i]['id']; ?>" class="rows">
								<td><div style="padding:5px;"><?php echo '<a style="cursor: pointer; color:#FF0000;" onclick="showPortDetails(\''.$_GET['portname'].'\', \''.$r[$i]['id'].'\');"><img src="../images/icon_book.png" /></a>'; ?></div></td>
								<td><div style="padding:5px;"><?php echo $agent_name; ?></div></td>
								<td><div style="padding:5px;"><?php echo $details['vessel']; ?></div></td>
								<td><div style="padding:5px;"><?php echo $details['dwt']; ?></div></td>
								<td><div style="padding:5px;"><?php echo $details['grt']; ?></div></td>
								<td><div style="padding:5px;"><?php echo $details['nrt']; ?></div></td>
								<td><div style="padding:5px;">US$ <?php echo $total_over_all; ?></div></td>
								<td><div style="padding:5px;"><?php echo $details['date']; ?></div></td>
								<td><div style="padding:5px;"><?php echo $details['cargo_type']; ?></div></td>
							</tr>
							<?php
							}
						}
						?>
					</table>
					<div>&nbsp;</div>
					<table width="500" border="0" cellspacing="0" cellpadding="0">
						<tr bgcolor="f5f5f5">
							<td>
								<table id="flexigrid" align="left"></table>
								<script type="text/javascript">
								$("document").ready(function(){
								vars = {
										url: '../js/grid/jquery_post/post_cargos2.php',
										dataType: 'json',
										colModel : [
											{display: '-', name : 'actions', width : 50, sortable : false, searchable: false, align: 'center'},
											{display: '#', name : 'id', width : 50, sortable : true, align: 'center'},
											{display: 'Cargo Qty', name : 'cargo_quantity', width : 50, sortable : true, align: 'left'}, 
											{display: 'Port Costs', name : 'port_costs', width : 50, sortable : true, align: 'left'}, 
											{display: 'AVR Intake', name : 'load_port2', width : 130, sortable : true, align: 'left'}, 
											{display: 'Qty MT', name : 'load_port_quantity', width : 130, sortable : true, align: 'left'}, 
											{display: 'Channel M', name : 'channel', width : 130, sortable : true, align: 'left'}, 
											{display: 'Anchorage M', name : 'anchorage', width : 130, sortable : true, align: 'left'}, 
											{display: 'Cargo Pier M', name : 'cargo_pier', width : 130, sortable : true, align: 'left'}
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
												
								$("#flexigrid").flexigrid( vars );							 
								});
								</script>
							</td>
						</tr>
					</table>
				</td>
				<td width="20">&nbsp;</td>
				<td width="600" valign="top">
					<div id='portresults'>
						<div id='records_tab_wrapperonly_port_details'></div>
					</div>
					<?php
					if(isset($_GET['view'])){
					
					$sql2 = "SELECT * FROM cargos WHERE id='".$_GET['id']."'";
					$data = dbQuery($sql2);
					$data = $data[0];
					
					$load_port = trim(htmlentities($data['load_port']));
					//$discharge_port = trim(htmlentities($data['discharge_port']));
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
					/*$discharge_port2 = trim(htmlentities($data['discharge_port2']));
					$discharge_port_quantity = trim(htmlentities($data['discharge_port_quantity']));
					$channel2 = trim(htmlentities($data['channel2']));
					$anchorage2 = trim(htmlentities($data['anchorage2']));
					$cargo_pier2 = trim(htmlentities($data['cargo_pier2']));*/
					$notes = trim(htmlentities($data['notes']));
					$by_agent = trim(htmlentities($data['by_agent']));
					$dateadded = trim(htmlentities($data['dateadded']));
					$dateupdated = trim(htmlentities($data['dateupdated']));
					?>
					<div>
						<table width="100%" cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td colspan="2" height="50">&nbsp;</td>
							</tr>
							<tr>
								<td colspan="2"><h2>Cargo Card Details:</h2></td>
							</tr>
							<tr>
								<td colspan="2">&nbsp;</td>
							</tr>
							<tr>
								<td class='label'>Load Port:</td>
								<td class='form'><?php echo $load_port; ?></td>
							</tr>
							<!--<tr>
								<td class='label'>Discharge Port:</td>
								<td class='form'><?php //echo $discharge_port; ?></td>
							</tr>-->
							<tr>
								<td class='label'>Date:</td>
								<td class='form'><?php echo $cargo_date; ?></td>
							</tr>
							<tr>
								<td class='label'>DWT or Ship Type:</td>
								<td class='form'><?php echo $dwt_or_ship_type; ?></td>
							</tr>
							<tr>
								<td class='label'>Cargo Type:</td>
								<td class='form'><?php echo $cargo_type; ?></td>
							</tr>
							<tr>
								<td class='label'>Cargo Quantity:</td>
								<td class='form'><?php echo $cargo_quantity; ?></td>
							</tr>
							<tr>
								<td class='label'>Port Costs:</td>
								<td class='form'><?php echo $port_costs; ?></td>
							</tr>
							<tr>
								<td class='label'>AVR Intake:</td>
								<td class='form'><?php echo $load_port2; ?></td>
							</tr>
							<tr>
								<td class='label'> Quantity MT:</td>
								<td class='form'><?php echo $load_port_quantity; ?></td>
							</tr>
							<tr>
								<td class='label'>Channel M:</td>
								<td class='form'><?php echo $channel; ?></td>
							</tr>
							<tr>
								<td class='label'>Anchorage M:</td>
								<td class='form'><?php echo $anchorage; ?></td>
							</tr>
							<tr>
								<td class='label'>Cargo Pier M:</td>
								<td class='form'><?php echo $cargo_pier; ?></td>
							</tr>
							<!--<tr>
								<td class='label'>Discharge Port Name <br />
								AVR Arrival Intake:  </td>
								<td class='form'><?php //echo $discharge_port2; ?></td>
							</tr>
							<tr>
								<td class='label'> Quantity MT:</td>
								<td class='form'><?php //echo $discharge_port_quantity; ?></td>
							</tr>
							<tr>
								<td class='label'>Channel M:</td>
								<td class='form'><?php //echo $channel2; ?></td>
							</tr>
							<tr>
								<td class='label'>Anchorage M:</td>
								<td class='form'><?php //echo $anchorage2; ?></td>
							</tr>
							<tr>
								<td class='label'>Cargo Pier M:</td>
								<td class='form'><?php //echo $cargo_pier2; ?></td>
							</tr>-->
							<tr>
								<td class='label'>Notes:</td>
								<td class='form'><?php echo $notes; ?></td>
							</tr>
							<tr>
								<td class='label'>Agent:</td>
								<td class='form'><?php echo $by_agent; ?></td>
							</tr>
							<tr>
								<td class='label'>Date Added:</td>
								<td class='form'><?php echo $dateadded; ?></td>
							</tr>
							<tr>
								<td class='label'>Date Updated:</td>
								<td class='form'><?php echo $dateupdated; ?></td>
							</tr>
						</table>
					</div>
					<?php } ?>
				</td>
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
		?>
		<table width="1120" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="500" valign="top">
					<table width="500" border="0" cellspacing="0" cellpadding="0">
						<tr bgcolor="f5f5f5">
							<td><div style="padding:5px; font-size:14px; color:#FF0000;">No update available</div></td>
						</tr>
					</table>
					<div>&nbsp;</div>
					<table width="500" border="0" cellspacing="0" cellpadding="0">
						<tr bgcolor="f5f5f5">
							<td>
								<table id="flexigrid" align="left"></table>
								<script type="text/javascript">
								$("document").ready(function(){
								vars = {
										url: '../js/grid/jquery_post/post_cargos2.php',
										dataType: 'json',
										colModel : [
											{display: '-', name : 'actions', width : 50, sortable : false, searchable: false, align: 'center'},
											{display: '#', name : 'id', width : 50, sortable : true, align: 'center'},
											{display: 'Cargo Qty', name : 'cargo_quantity', width : 50, sortable : true, align: 'left'}, 
											{display: 'Port Costs', name : 'port_costs', width : 50, sortable : true, align: 'left'}, 
											{display: 'AVR Intake', name : 'load_port2', width : 130, sortable : true, align: 'left'}, 
											{display: 'Qty MT', name : 'load_port_quantity', width : 130, sortable : true, align: 'left'}, 
											{display: 'Channel M', name : 'channel', width : 130, sortable : true, align: 'left'}, 
											{display: 'Anchorage M', name : 'anchorage', width : 130, sortable : true, align: 'left'}, 
											{display: 'Cargo Pier M', name : 'cargo_pier', width : 130, sortable : true, align: 'left'}
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
												
								$("#flexigrid").flexigrid( vars );							 
								});
								</script>
							</td>
						</tr>
					</table>
				</td>
				<td width="20">&nbsp;</td>
				<td width="600" valign="top">
					<table width="600" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="300" valign="top">
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
											<input type="text" id="ship_agent_id" name="ship_agent" style="width:150px; border:1px solid #CCCCCC; padding:3px;" onblur="getAgentDetails('');" />
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
										<td><input type="text" id="vessel_id" name="vessel" style="width:150px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $_GET['vessel_name']; ?>" readonly="readonly" /></td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td>Cargo Type</td>
										<td><input type="text" id="cargo_type_id" name="cargo_type" style="width:150px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $_GET['cargo_type']; ?>" readonly="readonly" /></td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td>DWT</td>
										<td><input type="text" onblur="this.value=fNum(this.value);" id="dwt_id" name="dwt" style="width:150px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo str_replace(' tons', '', $_GET['dwt']); ?>" /></td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td>GRT</td>
										<td><input type="text" onblur="this.value=fNum(this.value);" id="grt_id" name="grt" style="width:150px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo str_replace(' tons', '', $_GET['gross_tonnage']); ?>" /></td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td>NRT</td>
										<td><input type="text" onblur="this.value=fNum(this.value);" id="nrt_id" name="nrt" style="width:150px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo str_replace(' tons', '', $_GET['net_tonnage']); ?>" /></td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td>Manager</td>
										<td><input type="text" id="owner_id" name="owner" style="width:150px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $_GET['owner']; ?>" /></td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td valign="top">D/A Details</td>
										<td><textarea id="da_details_id" name="da_details" style="width:150px; height:200px; border:1px solid #CCCCCC; padding:3px;"></textarea></td>
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
										<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="quick_total_charges_id" name="quick_total_charges" style="width:150px; border:1px solid #CCCCCC; padding:3px;" /></td>
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
										<td><input type="text" id="voyage_number_id" name="voyage_number" style="width:150px; border:1px solid #CCCCCC; padding:3px;" /></td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td>Arrived From</td>
										<td>
											<input type="text" id="arrived_from_id" name="arrived_from" style="width:150px; border:1px solid #CCCCCC; padding:3px;" />
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
										<td><input type="radio" id="loading_id" name="loading" value="Yes" /> Yes &nbsp;&nbsp;&nbsp; <input type="radio" id="loading_id" name="loading" value="No" checked="checked" /> No</td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td>Discharging</td>
										<td><input type="radio" id="discharging_id" name="discharging" value="Yes" /> Yes &nbsp;&nbsp;&nbsp; <input type="radio" id="discharging_id" name="discharging" value="No" checked="checked" /> No</td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td>Bunkering</td>
										<td><input type="radio" id="bunkering_id" name="bunkering" value="Yes" /> Yes &nbsp;&nbsp;&nbsp; <input type="radio" id="bunkering_id" name="bunkering" value="No" checked="checked" /> No</td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td>Date/Hour</td>
										<td><input type="text" id="date_hour_id" name="date_hour" style="width:150px; border:1px solid #CCCCCC; padding:3px;" /></td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td>Sailed For</td>
										<td>
											<input type="text" id="sailed_for_id" name="sailed_for" style="width:150px; border:1px solid #CCCCCC; padding:3px;" />
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
										<td><input type="text" id="cargo_discharged_id" name="cargo_discharged" style="width:150px; border:1px solid #CCCCCC; padding:3px;" /></td>
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
										<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="harbour_dues_id" name="harbour_dues" style="width:150px; border:1px solid #CCCCCC; padding:3px;" /></td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td>Light Dues</td>
										<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="light_dues_id" name="light_dues" style="width:150px; border:1px solid #CCCCCC; padding:3px;" /></td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td>Pilotage</td>
										<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="pilotage_id" name="pilotage" style="width:150px; border:1px solid #CCCCCC; padding:3px;" /></td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td>Towage</td>
										<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="towage_id" name="towage" style="width:150px; border:1px solid #CCCCCC; padding:3px;" /></td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td>Mooring/Unmooring</td>
										<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="mooring_unmooring_id" name="mooring_unmooring" style="width:150px; border:1px solid #CCCCCC; padding:3px;" /></td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td>Shifting</td>
										<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="shifting_id" name="shifting" style="width:150px; border:1px solid #CCCCCC; padding:3px;" /></td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td>Customs Charges</td>
										<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="customs_charges_id" name="customs_charges" style="width:150px; border:1px solid #CCCCCC; padding:3px;" /></td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td>Launch/Car Hire</td>
										<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="launch_car_hire_id" name="launch_car_hire" style="width:150px; border:1px solid #CCCCCC; padding:3px;" /></td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td>Agency Remuniration</td>
										<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="agency_remuniration_id" name="agency_remuniration" style="width:150px; border:1px solid #CCCCCC; padding:3px;" /></td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td>Telex, Postage, Telegrams</td>
										<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="telex_postage_telegrams_id" name="telex_postage_telegrams" style="width:150px; border:1px solid #CCCCCC; padding:3px;" /></td>
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
										<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="stevedoring_expenses_id" name="stevedoring_expenses" style="width:150px; border:1px solid #CCCCCC; padding:3px;" /></td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td>Winchmen/Cranage</td>
										<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="winchmen_cranage_id" name="winchmen_cranage" style="width:150px; border:1px solid #CCCCCC; padding:3px;" /></td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td>Tally</td>
										<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="tally_id" name="tally" style="width:150px; border:1px solid #CCCCCC; padding:3px;" /></td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td>Overtime</td>
										<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="overtime_id" name="overtime" style="width:150px; border:1px solid #CCCCCC; padding:3px;" /></td>
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
											<input type="hidden" id="total_port_charges_id" name="total_port_charges" />
											<input type="hidden" id="total_cargo_charges_id" name="total_cargo_charges" />
											<input type="hidden" id="total_over_all_id" name="total_over_all" />
											<input type="hidden" name="submitok" value="1"><input type="button" id="btn_save_id" name="btn_save" value="save" class="btn_1" onClick="saveForm();" />
										</td>
									</tr>
								</table>
								<?php
								if(isset($_GET['view'])){
								
								$sql2 = "SELECT * FROM cargos WHERE id='".$_GET['id']."'";
								$data = dbQuery($sql2);
								$data = $data[0];
								
								$load_port = trim(htmlentities($data['load_port']));
								//$discharge_port = trim(htmlentities($data['discharge_port']));
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
								/*$discharge_port2 = trim(htmlentities($data['discharge_port2']));
								$discharge_port_quantity = trim(htmlentities($data['discharge_port_quantity']));
								$channel2 = trim(htmlentities($data['channel2']));
								$anchorage2 = trim(htmlentities($data['anchorage2']));
								$cargo_pier2 = trim(htmlentities($data['cargo_pier2']));*/
								$notes = trim(htmlentities($data['notes']));
								$by_agent = trim(htmlentities($data['by_agent']));
								$dateadded = trim(htmlentities($data['dateadded']));
								$dateupdated = trim(htmlentities($data['dateupdated']));
								?>
								<div>
									<table width="100%" cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td colspan="2" height="50">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="2"><h2>Cargo Card Details:</h2></td>
										</tr>
										<tr>
											<td colspan="2">&nbsp;</td>
										</tr>
										<tr>
											<td class='label'>Load Port:</td>
											<td class='form'><?php echo $load_port; ?></td>
										</tr>
										<tr>
											<td class='label'>Discharge Port:</td>
											<td class='form'><?php echo $discharge_port; ?></td>
										</tr>
										<tr>
											<td class='label'>Date:</td>
											<td class='form'><?php echo $cargo_date; ?></td>
										</tr>
										<tr>
											<td class='label'>DWT or Ship Type:</td>
											<td class='form'><?php echo $dwt_or_ship_type; ?></td>
										</tr>
										<tr>
											<td class='label'>Cargo Type:</td>
											<td class='form'><?php echo $cargo_type; ?></td>
										</tr>
										<tr>
											<td class='label'>Cargo Quantity:</td>
											<td class='form'><?php echo $cargo_quantity; ?></td>
										</tr>
										<tr>
											<td class='label'>Port Costs:</td>
											<td class='form'><?php echo $port_costs; ?></td>
										</tr>
										<tr>
											<td class='label'>AVR Intake:</td>
											<td class='form'><?php echo $load_port2; ?></td>
										</tr>
										<tr>
											<td class='label'> Quantity MT:</td>
											<td class='form'><?php echo $load_port_quantity; ?></td>
										</tr>
										<tr>
											<td class='label'>Channel M:</td>
											<td class='form'><?php echo $channel; ?></td>
										</tr>
										<tr>
											<td class='label'>Anchorage M:</td>
											<td class='form'><?php echo $anchorage; ?></td>
										</tr>
										<tr>
											<td class='label'>Cargo Pier M:</td>
											<td class='form'><?php echo $cargo_pier; ?></td>
										</tr>
										<!--<tr>
											<td class='label'>Discharge Port Name <br />
											AVR Arrival Intake:  </td>
											<td class='form'><?php //echo $discharge_port2; ?></td>
										</tr>
										<tr>
											<td class='label'> Quantity MT:</td>
											<td class='form'><?php //echo $discharge_port_quantity; ?></td>
										</tr>
										<tr>
											<td class='label'>Channel M:</td>
											<td class='form'><?php //echo $channel2; ?></td>
										</tr>
										<tr>
											<td class='label'>Anchorage M:</td>
											<td class='form'><?php //echo $anchorage2; ?></td>
										</tr>
										<tr>
											<td class='label'>Cargo Pier M:</td>
											<td class='form'><?php //echo $cargo_pier2; ?></td>
										</tr>-->
										<tr>
											<td class='label'>Notes:</td>
											<td class='form'><?php echo $notes; ?></td>
										</tr>
										<tr>
											<td class='label'>Agent:</td>
											<td class='form'><?php echo $by_agent; ?></td>
										</tr>
										<tr>
											<td class='label'>Date Added:</td>
											<td class='form'><?php echo $dateadded; ?></td>
										</tr>
										<tr>
											<td class='label'>Date Updated:</td>
											<td class='form'><?php echo $dateupdated; ?></td>
										</tr>
									</table>
								</div>
								<?php } ?>
							</td>
							<td width="20">&nbsp;</td>
							<td width="280" valign="top">
								<div id='agentresults'>
									<div id='records_tab_wrapperonly_agent_details'></div>
								</div>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<?php
	}
	
	echo '</form>';
}else{
	echo 'This PORT is unavailable';
}
?>
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
<script>
$(document).ready(function() {
	showPortDetails('<?php echo $_GET['portname']; ?>', 0);
	getAgentDetails('<?php echo $by_agent; ?>');
	jQuery('#ship_agent_id').focus();
});
</script>