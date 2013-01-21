<script type="text/javascript" src="../js/jquery.js"></script>

<script type="text/javascript" src="../js/calendar/xc2_default.js"></script>
<script type="text/javascript" src="../js/calendar/xc2_inpage.js"></script>
<link type="text/css" rel="stylesheet" href="../js/calendar/xc2_default.css" />

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
		if(isset($_GET['id'])){
			$sql_2 = "SELECT * FROM `_port_details` WHERE `id`='".$_GET['id']."' AND `port_name`='".$_GET['portname']."' ORDER BY dateadded DESC LIMIT 0,1";
			$r_2 = dbQuery($sql_2);
		}else{
			$sql_2 = "SELECT * FROM `_port_details` WHERE `port_name`='".$_GET['portname']."' ORDER BY dateadded DESC LIMIT 0,1";
			$r_2 = dbQuery($sql_2);
		}
		
		$data = unserialize($r_2[0]['port_details']);

		$ship_agent = $data['ship_agent'];
		$owner = $data['owner'];
		$date = $data['date'];
		$vessel = $data['vessel'];
		$voyage_number = $data['voyage_number'];
		$arrived_from = $data['arrived_from'];
		$date_hour = $data['date_hour'];
		$nrt = $data['nrt'];
		$grt = $data['grt'];
		$sailed_for = $data['sailed_for'];
		$cargo_discharged = $data['cargo_discharged'];
		$harbour_dues = $data['harbour_dues'];
		$light_dues = $data['light_dues'];
		$pilotage = $data['pilotage'];
		$towage = $data['towage'];
		$mooring_unmooring = $data['mooring_unmooring'];
		$shifting = $data['shifting'];
		$customs_charges = $data['customs_charges'];
		$launch_car_hire = $data['launch_car_hire'];
		$agency_remuniration = $data['agency_remuniration'];
		$telex_postage_telegrams = $data['telex_postage_telegrams'];
		$total_port_charges = number_format($data['total_port_charges'], 2, '.', ',');
		$stevedoring_expenses = $data['stevedoring_expenses'];
		$winchmen_cranage = $data['winchmen_cranage'];
		$tally = $data['tally'];
		$overtime = $data['overtime'];
		$total_cargo_charges = number_format($data['total_cargo_charges'], 2, '.', ',');
		$cash_to_master = $data['cash_to_master'];
		$water = $data['water'];
		$stores_provisions = $data['stores_provisions'];
		$crew_expenses = $data['crew_expenses'];
		$repairs = $data['repairs'];
		$total_ship_charges = number_format($data['total_ship_charges'], 2, '.', ',');
		$credit_to_owners_account = $data['credit_to_owners_account'];
		$balance_due_us_you = $data['balance_due_us_you'];
		$total_statement = number_format($data['total_statement'], 2, '.', ',');
		$total_over_all = number_format($data['total_over_all'], 2, '.', ',');
		?>
		<table width="1000" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="600" valign="top">
					<table width="600" border="0" cellspacing="0" cellpadding="0">
						<tr bgcolor="cddee5">
							<td><div style="padding:5px; font-weight:bold;">PORT NAME</div></td>
							<td><div style="padding:5px; font-weight:bold;">DATE</div></td>
							<td><div style="padding:5px; font-weight:bold;">VESSEL</div></td>
							<td><div style="padding:5px; font-weight:bold;">TOTAL</div></td>
							<td><div style="padding:5px; font-weight:bold;">BY USER</div></td>
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
							<td><div style="padding:5px;"><?php echo '<a href="port_details.php?portname='.$_GET['portname'].'&id='.$r[$i]['id'].'">'.$r[$i]['port_name'].'</a>'; ?></div></td>
							<td><div style="padding:5px;"><?php echo $details['date']; ?></div></td>
							<td><div style="padding:5px;"><?php echo $details['vessel']; ?></div></td>
							<td><div style="padding:5px;"><?php echo $details['total_over_all']; ?></div></td>
							<td><div style="padding:5px;"><?php echo $r[$i]['user_email']; ?></div></td>
						</tr>
						<?php } ?>
					</table>
				</td>
				<td width="20">&nbsp;</td>
				<td width="380" valign="top">
					<table width="380" border="0" cellspacing="0" cellpadding="0">
						<tr bgcolor="cddee5">
							<td colspan="2"><div style="padding:5px; font-weight:bold; color:#FF0000;"><?php echo $_GET['portname']; ?> &nbsp;&nbsp;&nbsp; <input type="button" value="Clear Form" class="btn_1" onclick="clearForm();" /></div></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td width="100">Ship Agent</td>
							<td><input type="text" id="ship_agent_id" name="ship_agent" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $ship_agent; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Owner</td>
							<td><input type="text" id="owner_id" name="owner" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $owner; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Date</td>
							<td><input type="text" id="date_id" name="date" readonly="readonly" onclick="showCalendar('',this,null,'','',0,5,1)" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $date; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Vessel</td>
							<td><input type="text" id="vessel_id" name="vessel" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $vessel; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Voyage #</td>
							<td><input type="text" id="voyage_number_id" name="voyage_number" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $voyage_number; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Arrived From</td>
							<td><input type="text" id="arrived_from_id" name="arrived_from" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $arrived_from; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Date/Hour</td>
							<td><input type="text" id="date_hour_id" name="date_hour" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $date_hour; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>NRT</td>
							<td><input type="text" id="nrt_id" name="nrt" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $nrt; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>GRT</td>
							<td><input type="text" id="grt_id" name="grt" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $grt; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Sailed For</td>
							<td><input type="text" id="sailed_for_id" name="sailed_for" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $sailed_for; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Cargo Discharged</td>
							<td><input type="text" id="cargo_discharged_id" name="cargo_discharged" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $cargo_discharged; ?>" /></td>
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
							<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="harbour_dues_id" name="harbour_dues" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $harbour_dues; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Light Dues</td>
							<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="light_dues_id" name="light_dues" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $light_dues; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Pilotage</td>
							<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="pilotage_id" name="pilotage" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $pilotage; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Towage</td>
							<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="towage_id" name="towage" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $towage; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Mooring/Unmooring</td>
							<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="mooring_unmooring_id" name="mooring_unmooring" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $mooring_unmooring; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Shifting</td>
							<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="shifting_id" name="shifting" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $shifting; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Customs Charges</td>
							<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="customs_charges_id" name="customs_charges" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $customs_charges; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Launch/Car Hire</td>
							<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="launch_car_hire_id" name="launch_car_hire" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $launch_car_hire; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Agency Remuniration</td>
							<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="agency_remuniration_id" name="agency_remuniration" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $agency_remuniration; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Telex, Postage, Telegrams</td>
							<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="telex_postage_telegrams_id" name="telex_postage_telegrams" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $telex_postage_telegrams; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td><b>Total</b></td>
							<td id="total_port_charges_td"><?php echo $total_port_charges; ?></td>
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
							<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="stevedoring_expenses_id" name="stevedoring_expenses" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $stevedoring_expenses; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Winchmen/Cranage</td>
							<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="winchmen_cranage_id" name="winchmen_cranage" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $winchmen_cranage; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Tally</td>
							<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="tally_id" name="tally" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $tally; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Overtime</td>
							<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="overtime_id" name="overtime" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $overtime; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td><b>Total</b></td>
							<td id="total_cargo_charges_td"><?php echo $total_cargo_charges; ?></td>
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
							<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="cash_to_master_id" name="cash_to_master" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $cash_to_master; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Water</td>
							<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="water_id" name="water" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $water; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Stores/Provisions</td>
							<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="stores_provisions_id" name="stores_provisions" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $stores_provisions; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Crew Expenses</td>
							<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="crew_expenses_id" name="crew_expenses" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $crew_expenses; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Repairs</td>
							<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="repairs_id" name="repairs" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $repairs; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td><b>Total</b></td>
							<td id="total_ship_charges_td"><?php echo $total_ship_charges; ?></td>
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
							<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="credit_to_owners_account_id" name="credit_to_owners_account" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $credit_to_owners_account; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Balance Due Us/You</td>
							<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="balance_due_us_you_id" name="balance_due_us_you" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $balance_due_us_you; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td><b>Total</b></td>
							<td id="total_statement_td"><?php echo $total_statement; ?></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td><b>Over All Total</b></td>
							<td id="total_over_all_td"><?php echo $total_over_all; ?></td>
						</tr>
						<tr>
							<td colspan="2">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2">
								<input type="hidden" id="total_port_charges_id" name="total_port_charges" value="<?php echo $total_port_charges; ?>" />
								<input type="hidden" id="total_cargo_charges_id" name="total_cargo_charges" value="<?php echo $total_cargo_charges; ?>" />
								<input type="hidden" id="total_ship_charges_id" name="total_ship_charges" value="<?php echo $total_ship_charges; ?>" />
								<input type="hidden" id="total_statement_id" name="total_statement" value="<?php echo $total_statement; ?>" />
								<input type="hidden" id="total_over_all_id" name="total_over_all" value="<?php echo $total_over_all; ?>" />
								<input type="hidden" name="submitok" value="1"><input type="button" id="btn_save_id" name="btn_save" value="save" class="btn_1" onClick="saveForm();" />
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<?php
	}else{
		?>
		<table width="1000" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="600" valign="top">
					<table width="600" border="0" cellspacing="0" cellpadding="0">
						<tr bgcolor="f5f5f5">
							<td><div style="padding:5px; font-size:14px; color:#FF0000;">No update available</div></td>
						</tr>
					</table>
				</td>
				<td width="20">&nbsp;</td>
				<td width="380" valign="top">
					<table width="380" border="0" cellspacing="0" cellpadding="0">
						<tr bgcolor="cddee5">
							<td colspan="2"><div style="padding:5px; font-weight:bold; color:#FF0000;"><?php echo $_GET['portname']; ?></div></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td width="100">Ship Agent</td>
							<td><input type="text" id="ship_agent_id" name="ship_agent" style="width:250px; border:1px solid #CCCCCC; padding:3px;" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Owner</td>
							<td><input type="text" id="owner_id" name="owner" style="width:250px; border:1px solid #CCCCCC; padding:3px;" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Date</td>
							<td><input type="text" id="date_id" name="date" readonly="readonly" onclick="showCalendar('',this,null,'','',0,5,1)" style="width:250px; border:1px solid #CCCCCC; padding:3px;" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Vessel</td>
							<td><input type="text" id="vessel_id" name="vessel" style="width:250px; border:1px solid #CCCCCC; padding:3px;" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Voyage #</td>
							<td><input type="text" id="voyage_number_id" name="voyage_number" style="width:250px; border:1px solid #CCCCCC; padding:3px;" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Arrived From</td>
							<td><input type="text" id="arrived_from_id" name="arrived_from" style="width:250px; border:1px solid #CCCCCC; padding:3px;" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Date/Hour</td>
							<td><input type="text" id="date_hour_id" name="date_hour" style="width:250px; border:1px solid #CCCCCC; padding:3px;" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>NRT</td>
							<td><input type="text" id="nrt_id" name="nrt" style="width:250px; border:1px solid #CCCCCC; padding:3px;" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>GRT</td>
							<td><input type="text" id="grt_id" name="grt" style="width:250px; border:1px solid #CCCCCC; padding:3px;" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Sailed For</td>
							<td><input type="text" id="sailed_for_id" name="sailed_for" style="width:250px; border:1px solid #CCCCCC; padding:3px;" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Cargo Discharged</td>
							<td><input type="text" id="cargo_discharged_id" name="cargo_discharged" style="width:250px; border:1px solid #CCCCCC; padding:3px;" /></td>
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
							<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="harbour_dues_id" name="harbour_dues" style="width:250px; border:1px solid #CCCCCC; padding:3px;" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Light Dues</td>
							<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="light_dues_id" name="light_dues" style="width:250px; border:1px solid #CCCCCC; padding:3px;" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Pilotage</td>
							<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="pilotage_id" name="pilotage" style="width:250px; border:1px solid #CCCCCC; padding:3px;" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Towage</td>
							<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="towage_id" name="towage" style="width:250px; border:1px solid #CCCCCC; padding:3px;" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Mooring/Unmooring</td>
							<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="mooring_unmooring_id" name="mooring_unmooring" style="width:250px; border:1px solid #CCCCCC; padding:3px;" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Shifting</td>
							<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="shifting_id" name="shifting" style="width:250px; border:1px solid #CCCCCC; padding:3px;" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Customs Charges</td>
							<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="customs_charges_id" name="customs_charges" style="width:250px; border:1px solid #CCCCCC; padding:3px;" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Launch/Car Hire</td>
							<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="launch_car_hire_id" name="launch_car_hire" style="width:250px; border:1px solid #CCCCCC; padding:3px;" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Agency Remuniration</td>
							<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="agency_remuniration_id" name="agency_remuniration" style="width:250px; border:1px solid #CCCCCC; padding:3px;" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Telex, Postage, Telegrams</td>
							<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="telex_postage_telegrams_id" name="telex_postage_telegrams" style="width:250px; border:1px solid #CCCCCC; padding:3px;" /></td>
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
							<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="stevedoring_expenses_id" name="stevedoring_expenses" style="width:250px; border:1px solid #CCCCCC; padding:3px;" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Winchmen/Cranage</td>
							<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="winchmen_cranage_id" name="winchmen_cranage" style="width:250px; border:1px solid #CCCCCC; padding:3px;" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Tally</td>
							<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="tally_id" name="tally" style="width:250px; border:1px solid #CCCCCC; padding:3px;" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Overtime</td>
							<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="overtime_id" name="overtime" style="width:250px; border:1px solid #CCCCCC; padding:3px;" /></td>
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
							<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="cash_to_master_id" name="cash_to_master" style="width:250px; border:1px solid #CCCCCC; padding:3px;" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Water</td>
							<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="water_id" name="water" style="width:250px; border:1px solid #CCCCCC; padding:3px;" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Stores/Provisions</td>
							<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="stores_provisions_id" name="stores_provisions" style="width:250px; border:1px solid #CCCCCC; padding:3px;" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Crew Expenses</td>
							<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="crew_expenses_id" name="crew_expenses" style="width:250px; border:1px solid #CCCCCC; padding:3px;" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Repairs</td>
							<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="repairs_id" name="repairs" style="width:250px; border:1px solid #CCCCCC; padding:3px;" /></td>
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
							<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="credit_to_owners_account_id" name="credit_to_owners_account" style="width:250px; border:1px solid #CCCCCC; padding:3px;" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Balance Due Us/You</td>
							<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="balance_due_us_you_id" name="balance_due_us_you" style="width:250px; border:1px solid #CCCCCC; padding:3px;" /></td>
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
			</tr>
		</table>
		<?php
	}
	
	echo '</form>';
}else{
	echo 'This PORT is unavailable';
}
?>