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
	$by_user = $user['email'];
	
	$sql = "INSERT INTO `_port_details` (`port_name`, `user_email`, `dateadded`) VALUES('".mysql_escape_string($port_name)."', '".mysql_escape_string($by_user)."', NOW())";
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
		<table width="1000" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="600" valign="top">
					<table width="600" border="0" cellspacing="0" cellpadding="0">
						<tr bgcolor="cddee5">
							<td><div style="padding:5px; font-weight:bold;">PORT NAME</div></td>
							<td><div style="padding:5px; font-weight:bold;">BY USER</div></td>
						</tr>
						<?php
						for($i=0; $i<$t; $i++){
						
						if($i%2==0){
							$bgcolor = 'f5f5f5';
						}else{
							$bgcolor = 'e9e9e9';
						}
						?>
						<tr bgcolor="<?php echo $bgcolor; ?>">
							<td><div style="padding:5px;"><?php echo '<a style="cursor: pointer;">'.$r[$i]['port_name'].'</a>'; ?></div></td>
							<td><div style="padding:5px;"><?php echo $r[$i]['user_email']; ?></div></td>
						</tr>
						<?php } ?>
					</table>
				</td>
				<td width="20">&nbsp;</td>
				<td width="380" valign="top">
					<table width="380" border="0" cellspacing="0" cellpadding="0">
						<tr bgcolor="cddee5">
							<td colspan="2"><div style="padding:5px; font-weight:bold;"><?php echo $_GET['portname']; ?></div></td>
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
							<td><input type="text" id="date_id" name="date" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $date; ?>" /></td>
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
							<td><input type="text" id="harbour_dues_id" name="harbour_dues" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $harbour_dues; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Light Dues</td>
							<td><input type="text" id="light_dues_id" name="light_dues" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $light_dues; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Pilotage</td>
							<td><input type="text" id="pilotage_id" name="pilotage" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $pilotage; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Towage</td>
							<td><input type="text" id="towage_id" name="towage" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $towage; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Mooring/Unmooring</td>
							<td><input type="text" id="mooring_unmooring_id" name="mooring_unmooring" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $mooring_unmooring; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Shifting</td>
							<td><input type="text" id="shifting_id" name="shifting" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $shifting; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Customs Charges</td>
							<td><input type="text" id="customs_charges_id" name="customs_charges" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $customs_charges; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Launch/Car Hire</td>
							<td><input type="text" id="launch_car_hire_id" name="launch_car_hire" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $launch_car_hire; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Agency Remuniration</td>
							<td><input type="text" id="agency_remuniration_id" name="agency_remuniration" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $agency_remuniration; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Telex, Postage, Telegrams</td>
							<td><input type="text" id="telex_postage_telegrams_id" name="telex_postage_telegrams" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $telex_postage_telegrams; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td><b>Total</b></td>
							<td id="total"></td>
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
							<td><input type="text" id="stevedoring_expenses_id" name="stevedoring_expenses" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $stevedoring_expenses; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Winchmen/Cranage</td>
							<td><input type="text" id="winchmen_cranage_id" name="winchmen_cranage" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $winchmen_cranage; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Tally</td>
							<td><input type="text" id="tally_id" name="tally" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $tally; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Overtime</td>
							<td><input type="text" id="overtime_id" name="overtime" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $overtime; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td><b>Total</b></td>
							<td id="total"></td>
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
							<td><input type="text" id="cash_to_master_id" name="cash_to_master" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $cash_to_master; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Water</td>
							<td><input type="text" id="water_id" name="water" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $water; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Stores/Provisions</td>
							<td><input type="text" id="stores_provisions_id" name="stores_provisions" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $stores_provisions; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Crew Expenses</td>
							<td><input type="text" id="crew_expenses_id" name="crew_expenses" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $crew_expenses; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Repairs</td>
							<td><input type="text" id="repairs_id" name="repairs" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $repairs; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td><b>Total</b></td>
							<td id="total"></td>
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
							<td><input type="text" id="credit_to_owners_account_id" name="credit_to_owners_account" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $credit_to_owners_account; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Balance Due Us/You</td>
							<td><input type="text" id="balance_due_us_you_id" name="balance_due_us_you" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $balance_due_us_you; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td><b>Total</b></td>
							<td id="total"></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td><b>Over All Total</b></td>
							<td id="total"></td>
						</tr>
						<tr>
							<td colspan="2">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2"><input type="hidden" name="submitok" value="1"><input type="button" id="btn_save_id" name="btn_save" value="save" class="btn_1" onClick="saveForm();" /></td>
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
							<td><div style="padding:5px;"><?php echo $_GET['portname']; ?></div></td>
						</tr>
					</table>
				</td>
				<td width="20">&nbsp;</td>
				<td width="380" valign="top">
					<table width="380" border="0" cellspacing="0" cellpadding="0">
						<tr bgcolor="cddee5">
							<td colspan="2"><div style="padding:5px; font-weight:bold;"><?php echo $_GET['portname']; ?></div></td>
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
							<td><input type="text" id="date_id" name="date" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $date; ?>" /></td>
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
							<td><input type="text" id="harbour_dues_id" name="harbour_dues" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $harbour_dues; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Light Dues</td>
							<td><input type="text" id="light_dues_id" name="light_dues" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $light_dues; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Pilotage</td>
							<td><input type="text" id="pilotage_id" name="pilotage" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $pilotage; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Towage</td>
							<td><input type="text" id="towage_id" name="towage" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $towage; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Mooring/Unmooring</td>
							<td><input type="text" id="mooring_unmooring_id" name="mooring_unmooring" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $mooring_unmooring; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Shifting</td>
							<td><input type="text" id="shifting_id" name="shifting" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $shifting; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Customs Charges</td>
							<td><input type="text" id="customs_charges_id" name="customs_charges" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $customs_charges; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Launch/Car Hire</td>
							<td><input type="text" id="launch_car_hire_id" name="launch_car_hire" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $launch_car_hire; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Agency Remuniration</td>
							<td><input type="text" id="agency_remuniration_id" name="agency_remuniration" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $agency_remuniration; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Telex, Postage, Telegrams</td>
							<td><input type="text" id="telex_postage_telegrams_id" name="telex_postage_telegrams" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $telex_postage_telegrams; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td><b>Total</b></td>
							<td id="total"></td>
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
							<td><input type="text" id="stevedoring_expenses_id" name="stevedoring_expenses" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $stevedoring_expenses; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Winchmen/Cranage</td>
							<td><input type="text" id="winchmen_cranage_id" name="winchmen_cranage" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $winchmen_cranage; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Tally</td>
							<td><input type="text" id="tally_id" name="tally" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $tally; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Overtime</td>
							<td><input type="text" id="overtime_id" name="overtime" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $overtime; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td><b>Total</b></td>
							<td id="total"></td>
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
							<td><input type="text" id="cash_to_master_id" name="cash_to_master" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $cash_to_master; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Water</td>
							<td><input type="text" id="water_id" name="water" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $water; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Stores/Provisions</td>
							<td><input type="text" id="stores_provisions_id" name="stores_provisions" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $stores_provisions; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Crew Expenses</td>
							<td><input type="text" id="crew_expenses_id" name="crew_expenses" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $crew_expenses; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Repairs</td>
							<td><input type="text" id="repairs_id" name="repairs" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $repairs; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td><b>Total</b></td>
							<td id="total"></td>
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
							<td><input type="text" id="credit_to_owners_account_id" name="credit_to_owners_account" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $credit_to_owners_account; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Balance Due Us/You</td>
							<td><input type="text" id="balance_due_us_you_id" name="balance_due_us_you" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $balance_due_us_you; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td><b>Total</b></td>
							<td id="total"></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td><b>Over All Total</b></td>
							<td id="total"></td>
						</tr>
						<tr>
							<td colspan="2">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2"><input type="hidden" name="submitok" value="1"><input type="button" id="btn_save_id" name="btn_save" value="save" class="btn_1" onClick="saveForm();" /></td>
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