<script type="text/javascript" src="../js/jquery.js"></script>

<script type="text/javascript" src="../js/calendar/xc2_default.js"></script>
<script type="text/javascript" src="../js/calendar/xc2_inpage.js"></script>
<link type="text/css" rel="stylesheet" href="../js/calendar/xc2_default.css" />

<script type='text/javascript' src='../js/jquery-autocomplete/lib/jquery.bgiframe.min.js'></script>
<script type='text/javascript' src='../js/jquery-autocomplete/lib/jquery.ajaxQueue.js'></script>
<script type='text/javascript' src='../js/jquery-autocomplete/lib/thickbox-compressed.js'></script>
<script type='text/javascript' src='../js/jquery-autocomplete/jquery.autocomplete.js'></script>
<script type='text/javascript' src='../js/autoVessel.php'></script>
<link rel="stylesheet" type="text/css" href="../js/jquery-autocomplete/jquery.autocomplete.css" />
<link rel="stylesheet" type="text/css" href="../js/jquery-autocomplete/lib/thickbox.css" />

<script language="JavaScript">
function saveForm(){
	var submitok = 1;
	
	alertmsg = "";
	
	if(document.inputfrm.ship_agent.value==""){ 
		alertmsg="Please enter the SHIP AGENT\n"; submitok = 0; 
		document.inputfrm.submitok.value=0
	}else{
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
	jQuery("#total_port_charges_id").val(total_port_charges);
	//END OF PORT CHARGES
	
	//CARGO CHARGES
	var stevedoring_expenses = jQuery("#stevedoring_expenses_id").val();
	var winchmen_cranage = jQuery("#winchmen_cranage_id").val();
	var tally = jQuery("#tally_id").val();
	var overtime = jQuery("#overtime_id").val();

	var total_cargo_charges = uNum(stevedoring_expenses) + uNum(winchmen_cranage) + uNum(tally) + uNum(overtime);
	
	jQuery("#total_cargo_charges_td").text(fNum(total_cargo_charges));
	jQuery("#total_cargo_charges_id").val(total_cargo_charges);
	//END OF CARGO CHARGES
	
	//SHIP CHARGES
	var cash_to_master = jQuery("#cash_to_master_id").val();
	var water = jQuery("#water_id").val();
	var stores_provisions = jQuery("#stores_provisions_id").val();
	var crew_expenses = jQuery("#crew_expenses_id").val();
	var repairs = jQuery("#repairs_id").val();

	var total_ship_charges = uNum(cash_to_master) + uNum(water) + uNum(stores_provisions) + uNum(crew_expenses) + uNum(repairs);
	
	jQuery("#total_ship_charges_td").text(fNum(total_ship_charges));
	jQuery("#total_ship_charges_id").val(total_ship_charges);
	//END OF SHIP CHARGES
	
	//STATEMENT CHARGES
	var credit_to_owners_account = jQuery("#credit_to_owners_account_id").val();
	var balance_due_us_you = jQuery("#balance_due_us_you_id").val();

	var total_statement = uNum(credit_to_owners_account) + uNum(balance_due_us_you);
	
	jQuery("#total_statement_td").text(fNum(total_statement));
	jQuery("#total_statement_id").val(total_statement);
	//END OF STATEMENT CHARGES
	
	//TOTAL
	var total_over_all = uNum(total_port_charges) + uNum(total_cargo_charges) + uNum(total_ship_charges) + uNum(total_statement);
	jQuery("#total_over_all_td").text(fNum(total_over_all));
	jQuery("#total_over_all_id").val(total_over_all);
	//END OF TOTAL
}
//END OF COMPUTATIONS

function showPortDetails(portname, id){
	jQuery('#portresults').hide();

	jQuery('#pleasewait').show();

	jQuery.ajax({
		type: 'GET',
		url: "port_details_ajax.php?portname="+portname+"&id="+id,
		data:  jQuery("#inputfrm").serialize(),

		success: function(data) {
			jQuery("#records_tab_wrapperonly_port_details").html(data);
			jQuery('#portresults').fadeIn(200);
			
			jQuery('#pleasewait').hide();
		}
	});
}

$(document).ready(function() {
	showPortDetails('<?php echo $_GET['portname']; ?>', 0);
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
</style>
<?php
@session_start();
include_once(dirname(__FILE__)."/../includes/bootstrap.php");
date_default_timezone_set('UTC');

if($_POST['submitok']==1){
	$port_name = $_GET['portname'];
	
	$print = array();
	
	$print['ship_agent'] = $_POST['ship_agent'];
	$print['owner'] = $_POST['owner'];
	$print['date'] = $_POST['date'];
	$print['vessel'] = $_POST['vessel'];
	$print['voyage_number'] = $_POST['voyage_number'];
	$print['arrived_from'] = $_POST['arrived_from'];
	$print['date_hour'] = $_POST['date_hour'];
	$print['nrt'] = $_POST['nrt'];
	$print['grt'] = $_POST['grt'];
	$print['sailed_for'] = $_POST['sailed_for'];
	$print['cargo_discharged'] = $_POST['cargo_discharged'];
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
	$print['cash_to_master'] = $_POST['cash_to_master'];
	$print['water'] = $_POST['water'];
	$print['stores_provisions'] = $_POST['stores_provisions'];
	$print['crew_expenses'] = $_POST['crew_expenses'];
	$print['repairs'] = $_POST['repairs'];
	$print['total_ship_charges'] = $_POST['total_ship_charges'];
	$print['credit_to_owners_account'] = $_POST['credit_to_owners_account'];
	$print['balance_due_us_you'] = $_POST['balance_due_us_you'];
	$print['total_statement'] = $_POST['total_statement'];
	$print['total_over_all'] = $_POST['total_over_all'];
	
	$data = serialize($print);
	
	$by_user = $user['email'];
	
	$sql = "INSERT INTO `_port_details` (`port_name`, `port_details`, `user_email`, `dateadded`) VALUES('".mysql_escape_string($port_name)."', '".mysql_escape_string($data)."', '".mysql_escape_string($by_user)."', NOW())";
	dbQuery($sql, $link);
	
	redirectjs("port_details.php?portname=".$port_name);
}

if(isset($_GET['portname'])){
	echo '<form id="inputfrm_id" name="inputfrm" method="post" enctype="multipart/form-data">';
	
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
							<td><div style="padding:5px; font-weight:bold;">AGENT</div></td>
							<td><div style="padding:5px; font-weight:bold;">OWNER/MANAGER</div></td>
							<td><div style="padding:5px; font-weight:bold;">PORT NAME</div></td>
							<td><div style="padding:5px; font-weight:bold;">VESSEL</div></td>
							<td><div style="padding:5px; font-weight:bold;">NRT</div></td>
							<td><div style="padding:5px; font-weight:bold;">GRT</div></td>
							<td><div style="padding:5px; font-weight:bold;">AMOUNT</div></td>
							<td><div style="padding:5px; font-weight:bold;">CURRENCY</div></td>
							<td><div style="padding:5px; font-weight:bold;">DATE</div></td>
						</tr>
						<?php
						for($i=0; $i<$t; $i++){
						
						$details = unserialize($r[$i]['port_details']);
						
						if($i%2==0){
							$bgcolor = 'f5f5f5';
						}else{
							$bgcolor = 'e9e9e9';
						}
						?>
						<tr bgcolor="<?php echo $bgcolor; ?>">
							<td><div style="padding:5px;"><?php echo $details['ship_agent']; ?></div></td>
							<td><div style="padding:5px;"><?php echo $details['owner']; ?></div></td>
							<td><div style="padding:5px;"><?php echo '<a style="cursor: pointer; color:#FF0000;" onclick="showPortDetails(\''.$_GET['portname'].'\', \''.$r[$i]['id'].'\');">'.$r[$i]['port_name'].'</a>'; ?></div></td>
							<td><div style="padding:5px;"><?php echo $details['vessel']; ?></div></td>
							<td><div style="padding:5px;"><?php echo $details['nrt']; ?></div></td>
							<td><div style="padding:5px;"><?php echo $details['grt']; ?></div></td>
							<td><div style="padding:5px;"><?php echo $details['total_over_all']; ?></div></td>
							<td><div style="padding:5px;">$</div></td>
							<td><div style="padding:5px;"><?php echo $details['date']; ?></div></td>
						</tr>
						<?php } ?>
					</table>
				</td>
				<td width="20">&nbsp;</td>
				<td width="600" valign="top">
					<div id='portresults'>
						<div id='records_tab_wrapperonly_port_details'></div>
					</div>
				</td>
			</tr>
		</table>
		<?php
	}else{
		?>
		<table width="1120" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="500" valign="top">
					<table width="500" border="0" cellspacing="0" cellpadding="0">
						<tr bgcolor="f5f5f5">
							<td><div style="padding:5px; font-size:14px; color:#FF0000;">No update available</div></td>
						</tr>
					</table>
				</td>
				<td width="20">&nbsp;</td>
				<td width="600" valign="top">
					<table width="600" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="300">
								<table width="300" border="0" cellspacing="0" cellpadding="0">
									<tr bgcolor="cddee5">
										<td colspan="2"><div style="padding:5px; font-weight:bold; color:#FF0000;"><?php echo $_GET['portname']; ?></div></td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td width="100">Ship Agent</td>
										<td><input type="text" id="ship_agent_id" name="ship_agent" style="width:150px; border:1px solid #CCCCCC; padding:3px;" /></td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td>Owner</td>
										<td><input type="text" id="owner_id" name="owner" style="width:150px; border:1px solid #CCCCCC; padding:3px;" /></td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td>Date</td>
										<td><input type="text" id="date_id" name="date" readonly="readonly" onclick="showCalendar('',this,null,'','',0,5,1)" style="width:150px; border:1px solid #CCCCCC; padding:3px;" /></td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td>Vessel</td>
										<td>
											<input type="text" id="vessel_id" name="vessel" style="width:150px; border:1px solid #CCCCCC; padding:3px;" />
											<script type="text/javascript">
											jQuery("#vessel_id").focus().autocomplete(vessel);
											jQuery("#vessel_id").setOptions({
												scrollHeight: 180
											});
											</script>
										</td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td>Voyage #</td>
										<td><input type="text" id="voyage_number_id" name="voyage_number" style="width:150px; border:1px solid #CCCCCC; padding:3px;" /></td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td>Arrived From</td>
										<td><input type="text" id="arrived_from_id" name="arrived_from" style="width:150px; border:1px solid #CCCCCC; padding:3px;" /></td>
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
										<td>NRT</td>
										<td><input type="text" id="nrt_id" name="nrt" style="width:150px; border:1px solid #CCCCCC; padding:3px;" /></td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td>GRT</td>
										<td><input type="text" id="grt_id" name="grt" style="width:150px; border:1px solid #CCCCCC; padding:3px;" /></td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td>Sailed For</td>
										<td><input type="text" id="sailed_for_id" name="sailed_for" style="width:150px; border:1px solid #CCCCCC; padding:3px;" /></td>
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
									<tr bgcolor="cddee5">
										<td colspan="2"><div style="padding:5px; font-weight:bold;">SHIP CHARGES</div></td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td>Cash To Master</td>
										<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="cash_to_master_id" name="cash_to_master" style="width:150px; border:1px solid #CCCCCC; padding:3px;" /></td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td>Water</td>
										<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="water_id" name="water" style="width:150px; border:1px solid #CCCCCC; padding:3px;" /></td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td>Stores/Provisions</td>
										<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="stores_provisions_id" name="stores_provisions" style="width:150px; border:1px solid #CCCCCC; padding:3px;" /></td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td>Crew Expenses</td>
										<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="crew_expenses_id" name="crew_expenses" style="width:150px; border:1px solid #CCCCCC; padding:3px;" /></td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td>Repairs</td>
										<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="repairs_id" name="repairs" style="width:150px; border:1px solid #CCCCCC; padding:3px;" /></td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td><b>Total</b></td>
										<td id="total_ship_charges_td">&nbsp;</td>
									</tr>
									<tr>
										<td colspan="2">&nbsp;</td>
									</tr>
									<tr bgcolor="cddee5">
										<td colspan="2"><div style="padding:5px; font-weight:bold;">STATEMENT</div></td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td>Credit To Owners Account</td>
										<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="credit_to_owners_account_id" name="credit_to_owners_account" style="width:150px; border:1px solid #CCCCCC; padding:3px;" /></td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td>Balance Due Us/You</td>
										<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="balance_due_us_you_id" name="balance_due_us_you" style="width:150px; border:1px solid #CCCCCC; padding:3px;" /></td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td><b>Total</b></td>
										<td id="total_statement_td">&nbsp;</td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td><b>Over All Total</b></td>
										<td id="total_over_all_td">&nbsp;</td>
									</tr>
									<tr>
										<td colspan="2">&nbsp;</td>
									</tr>
									<tr>
										<td colspan="2">
											<input type="hidden" id="total_port_charges_id" name="total_port_charges" />
											<input type="hidden" id="total_cargo_charges_id" name="total_cargo_charges" />
											<input type="hidden" id="total_ship_charges_id" name="total_ship_charges" />
											<input type="hidden" id="total_statement_id" name="total_statement" />
											<input type="hidden" id="total_over_all_id" name="total_over_all" />
											<input type="hidden" name="submitok" value="1"><input type="button" id="btn_save_id" name="btn_save" value="save" class="btn_1" onClick="saveForm();" />
										</td>
									</tr>
								</table>
							</td>
							<td width="20">&nbsp;</td>
							<td width="280" valign="top">
								<table width="280" border="0" cellspacing="0" cellpadding="0">
									<tr bgcolor="cddee5">
										<td colspan="2"><div style="padding:5px; font-weight:bold;">AGENT'S MAIN DETAILS</div></td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td width="100">Company Name</td>
										<td><input type="text" id="company_name_id" name="company_name" style="width:150px; border:1px solid #CCCCCC; padding:3px;" /></td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td>Business Type</td>
										<td><input type="text" id="business_type_id" name="business_type" style="width:150px; border:1px solid #CCCCCC; padding:3px;" /></td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td>Address</td>
										<td><input type="text" id="address_id" name="address" style="width:150px; border:1px solid #CCCCCC; padding:3px;" /></td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td>City</td>
										<td><input type="text" id="city_id" name="city" style="width:150px; border:1px solid #CCCCCC; padding:3px;" /></td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td>Postal Code</td>
										<td><input type="text" id="postal_code_id" name="postal_code" style="width:150px; border:1px solid #CCCCCC; padding:3px;" /></td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td>Country</td>
										<td><input type="text" id="country_id" name="country" style="width:150px; border:1px solid #CCCCCC; padding:3px;" /></td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td>Fax</td>
										<td><input type="text" id="fax_id" name="fax" style="width:150px; border:1px solid #CCCCCC; padding:3px;" /></td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td>Website</td>
										<td><input type="text" id="website_id" name="website" style="width:150px; border:1px solid #CCCCCC; padding:3px;" /></td>
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
										<td>First Name</td>
										<td><input type="text" id="first_name_id" name="first_name" style="width:150px; border:1px solid #CCCCCC; padding:3px;" /></td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td>Last Name</td>
										<td><input type="text" id="last_name_id" name="last_name" style="width:150px; border:1px solid #CCCCCC; padding:3px;" /></td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td>Email Address</td>
										<td><input type="text" id="email_address_id" name="email_address" style="width:150px; border:1px solid #CCCCCC; padding:3px;" /></td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td>Skype ID</td>
										<td><input type="text" id="skype_id" name="skype" style="width:150px; border:1px solid #CCCCCC; padding:3px;" /></td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td>Yahoo ID</td>
										<td><input type="text" id="yahoo_id" name="yahoo" style="width:150px; border:1px solid #CCCCCC; padding:3px;" /></td>
									</tr>
									<tr>
										<td colspan="2" height="5">&nbsp;</td>
									</tr>
									<tr>
										<td>MSN ID</td>
										<td><input type="text" id="msn_id" name="msn" style="width:150px; border:1px solid #CCCCCC; padding:3px;" /></td>
									</tr>
								</table>
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