<?php
@session_start();
include_once(dirname(__FILE__)."/../includes/bootstrap.php");
date_default_timezone_set('UTC');

if(isset($_GET['id'])){
	if($_GET['id']!=0){
		$sql_2 = "SELECT * FROM `_port_details` WHERE `id`='".$_GET['id']."' AND `port_name`='".$_GET['portname']."' ORDER BY dateadded DESC LIMIT 0,1";
		$r_2 = dbQuery($sql_2);
		
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
		
		$sql_3 = "SELECT * FROM `_port_agents` WHERE `email_address`='".$ship_agent."' ORDER BY dateadded DESC LIMIT 0,1";
		$r_3 = dbQuery($sql_3);
		
		$company_name = $r_3[0]['company_name'];
		$business_type = $r_3[0]['business_type'];
		$address = $r_3[0]['address'];
		$city = $r_3[0]['city'];
		$postal_code = $r_3[0]['postal_code'];
		$country = $r_3[0]['country'];
		$fax = $r_3[0]['fax'];
		$website = $r_3[0]['website'];
		$first_name = $r_3[0]['first_name'];
		$last_name = $r_3[0]['last_name'];
		$office_number = $r_3[0]['office_number'];
		$mobile_number = $r_3[0]['mobile_number'];
		$fax_number = $r_3[0]['fax_number'];
		$telex = $r_3[0]['telex'];
		$email_address = $r_3[0]['email_address'];
		$skype = $r_3[0]['skype'];
		$yahoo = $r_3[0]['yahoo'];
		$msn = $r_3[0]['msn'];
		
		?>
		<table width="600" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="300">
					<table width="300" border="0" cellspacing="0" cellpadding="0">
						<tr bgcolor="cddee5">
							<td colspan="2"><div style="padding:5px; font-weight:bold; color:#FF0000;"><?php echo $_GET['portname']; ?> &nbsp;&nbsp;&nbsp; <input type="button" value="Create New" class="btn_1" onclick="location.href='port_details.php?portname=<?php echo $_GET['portname']; ?>';" /></div></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td width="100">Ship Agent</td>
							<td> : <?php echo $ship_agent; ?></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Owner</td>
							<td> : <?php echo $owner; ?></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Date</td>
							<td> : <?php echo $date; ?></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Vessel</td>
							<td> : <?php echo $vessel; ?></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Voyage #</td>
							<td> : <?php echo $voyage_number; ?></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Arrived From</td>
							<td> : <?php echo $arrived_from; ?></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Date/Hour</td>
							<td> : <?php echo $date_hour; ?></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>NRT</td>
							<td> : <?php echo $nrt; ?></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>GRT</td>
							<td> : <?php echo $grt; ?></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Sailed For</td>
							<td> : <?php echo $sailed_for; ?></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Cargo Discharged</td>
							<td> : <?php echo $cargo_discharged; ?></td>
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
							<td> : <?php echo $harbour_dues; ?></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Light Dues</td>
							<td> : <?php echo $light_dues; ?></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Pilotage</td>
							<td> : <?php echo $pilotage; ?></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Towage</td>
							<td> : <?php echo $towage; ?></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Mooring/Unmooring</td>
							<td> : <?php echo $mooring_unmooring; ?></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Shifting</td>
							<td> : <?php echo $shifting; ?></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Customs Charges</td>
							<td> : <?php echo $customs_charges; ?></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Launch/Car Hire</td>
							<td> : <?php echo $launch_car_hire; ?></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Agency Remuniration</td>
							<td> : <?php echo $agency_remuniration; ?></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Telex, Postage, Telegrams</td>
							<td> : <?php echo $telex_postage_telegrams; ?></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td><b>Total</b></td>
							<td id="total_port_charges_td"> : <?php echo $total_port_charges; ?></td>
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
							<td> : <?php echo $stevedoring_expenses; ?></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Winchmen/Cranage</td>
							<td> : <?php echo $winchmen_cranage; ?></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Tally</td>
							<td> : <?php echo $tally; ?></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Overtime</td>
							<td> : <?php echo $overtime; ?></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td><b>Total</b></td>
							<td id="total_cargo_charges_td"> : <?php echo $total_cargo_charges; ?></td>
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
							<td> : <?php echo $cash_to_master; ?></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Water</td>
							<td> : <?php echo $water; ?></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Stores/Provisions</td>
							<td> : <?php echo $stores_provisions; ?></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Crew Expenses</td>
							<td> : <?php echo $crew_expenses; ?></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Repairs</td>
							<td> : <?php echo $repairs; ?></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td><b>Total</b></td>
							<td id="total_ship_charges_td"> : <?php echo $total_ship_charges; ?></td>
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
							<td> : <?php echo $credit_to_owners_account; ?></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Balance Due Us/You</td>
							<td> : <?php echo $balance_due_us_you; ?></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td><b>Total</b></td>
							<td id="total_statement_td"> : <?php echo $total_statement; ?></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td><b>Over All Total</b></td>
							<td id="total_over_all_td"> : <?php echo $total_over_all; ?></td>
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
							<td> : <?php echo $company_name; ?></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Business Type</td>
							<td> : <?php echo $business_type; ?></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Address</td>
							<td> : <?php echo $address; ?></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>City</td>
							<td> : <?php echo $city; ?></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Postal Code</td>
							<td> : <?php echo $postal_code; ?></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Country</td>
							<td> : <?php echo $country; ?></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Fax</td>
							<td> : <?php echo $fax; ?></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Website</td>
							<td> : <?php echo $website; ?></td>
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
							<td> : <?php echo $first_name; ?></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Last Name</td>
							<td> : <?php echo $last_name; ?></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Office Number</td>
							<td> : <?php echo $office_number; ?></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Mobile Number</td>
							<td> : <?php echo $mobile_number; ?></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Fax Number</td>
							<td> : <?php echo $fax_number; ?></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Telex</td>
							<td> : <?php echo $telex; ?></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Email Address</td>
							<td> : <?php echo $email_address; ?></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Skype ID</td>
							<td> : <?php echo $skype; ?></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Yahoo ID</td>
							<td> : <?php echo $yahoo; ?></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>MSN ID</td>
							<td> : <?php echo $msn; ?></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<?php
	}else{
		?>
		<table width="600" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="300">
					<table width="300" border="0" cellspacing="0" cellpadding="0">
						<tr bgcolor="cddee5">
							<td colspan="2"><div style="padding:5px; font-weight:bold; color:#FF0000;"><?php echo $_GET['portname']; ?> &nbsp;&nbsp;&nbsp; <input type="button" value="Clear Form" class="btn_1" onclick="clearForm();" /></div></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td width="100">Ship Agent</td>
							<td>
								<input type="text" id="ship_agent_id" name="ship_agent" style="width:150px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $ship_agent; ?>" onblur="getAgentDetails();" />
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
							<td>Owner</td>
							<td><input type="text" id="owner_id" name="owner" style="width:150px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $owner; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Date</td>
							<td><input type="text" id="date_id" name="date" readonly="readonly" onclick="showCalendar('',this,null,'','',0,5,1)" style="width:150px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $date; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Vessel</td>
							<td>
								<input type="text" id="vessel_id" name="vessel" style="width:150px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $vessel; ?>" />
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
							<td><input type="text" id="voyage_number_id" name="voyage_number" style="width:150px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $voyage_number; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Arrived From</td>
							<td><input type="text" id="arrived_from_id" name="arrived_from" style="width:150px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $arrived_from; ?>" /></td>
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
							<td>NRT</td>
							<td><input type="text" id="nrt_id" name="nrt" style="width:150px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $nrt; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>GRT</td>
							<td><input type="text" id="grt_id" name="grt" style="width:150px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $grt; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Sailed For</td>
							<td><input type="text" id="sailed_for_id" name="sailed_for" style="width:150px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $sailed_for; ?>" /></td>
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
							<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="cash_to_master_id" name="cash_to_master" style="width:150px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $cash_to_master; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Water</td>
							<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="water_id" name="water" style="width:150px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $water; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Stores/Provisions</td>
							<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="stores_provisions_id" name="stores_provisions" style="width:150px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $stores_provisions; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Crew Expenses</td>
							<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="crew_expenses_id" name="crew_expenses" style="width:150px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $crew_expenses; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Repairs</td>
							<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="repairs_id" name="repairs" style="width:150px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $repairs; ?>" /></td>
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
							<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="credit_to_owners_account_id" name="credit_to_owners_account" style="width:150px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $credit_to_owners_account; ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" height="5">&nbsp;</td>
						</tr>
						<tr>
							<td>Balance Due Us/You</td>
							<td><input onkeyup="computeForTotal();" onblur="this.value=fNum(this.value);" type="text" id="balance_due_us_you_id" name="balance_due_us_you" style="width:150px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $balance_due_us_you; ?>" /></td>
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
				<td width="20">&nbsp;</td>
				<td width="280" valign="top">
					<div id='agentresults'>
						<div id='records_tab_wrapperonly_agent_details'></div>
					</div>
				</td>
			</tr>
		</table>
		<?php
	}
}
?>