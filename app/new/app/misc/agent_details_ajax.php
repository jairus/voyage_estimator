<?php
@session_start();
include_once(dirname(__FILE__)."/../includes/bootstrap.php");
date_default_timezone_set('UTC');

if(isset($_GET['ship_agent'])){
	$agent = explode(' = ', $_GET['ship_agent']);
	
	$id = $agent[1];

	$sql = "SELECT * FROM `_port_agents` WHERE `id`='".$id."' ORDER BY dateadded DESC LIMIT 0,1";
	$r = dbQuery($sql);
	
	if($r[0]['id']){
		?>
		<table width="280" border="0" cellspacing="0" cellpadding="0">
			<tr bgcolor="cddee5">
				<td colspan="2"><div style="padding:5px; font-weight:bold;">AGENT'S MAIN DETAILS</div></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td width="100">Company Name</td>
				<td> : <?php echo $r[0]['company_name']; ?></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td>Business Type</td>
				<td> : <?php echo $r[0]['business_type']; ?></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td>Address</td>
				<td> : <?php echo $r[0]['address']; ?></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td>City</td>
				<td> : <?php echo $r[0]['city']; ?></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td>Postal Code</td>
				<td> : <?php echo $r[0]['postal_code']; ?></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td>Country</td>
				<td> : <?php echo $r[0]['country']; ?></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td>Fax</td>
				<td> : <?php echo $r[0]['fax']; ?></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td>Website</td>
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
				<td>First Name</td>
				<td> : <?php echo $r[0]['first_name']; ?></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td>Last Name</td>
				<td> : <?php echo $r[0]['last_name']; ?></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td>Office Number</td>
				<td> : <?php echo $r[0]['office_number']; ?></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td>Mobile Number</td>
				<td> : <?php echo $r[0]['mobile_number']; ?></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td>Fax Number</td>
				<td> : <?php echo $r[0]['fax_number']; ?></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td>Telex</td>
				<td> : <?php echo $r[0]['telex']; ?></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td>Email Address</td>
				<td> : <?php echo $r[0]['email_address']; ?></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td>Skype ID</td>
				<td> : <?php echo $r[0]['skype']; ?></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td>Yahoo ID</td>
				<td> : <?php echo $r[0]['yahoo']; ?></td>
			</tr>
			<tr>
				<td colspan="2" height="5">&nbsp;</td>
			</tr>
			<tr>
				<td>MSN ID</td>
				<td> : <?php echo $r[0]['msn']; ?></td>
			</tr>
		</table>
		<?php
	}
}
?>