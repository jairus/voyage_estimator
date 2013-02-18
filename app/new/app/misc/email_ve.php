<?php
@ob_start();
@session_start();

include_once(dirname(__FILE__)."/../includes/bootstrap.php");
include_once(dirname(__FILE__)."/emailer/email.php");
date_default_timezone_set('UTC'); 
?>
<style>
*{
	font-size:11px;
	font-family:Verdana, Arial, Helvetica, sans-serif;
}
td,th{
	/*border: 1px solid gray;*/
}

.z_text01{
	font-family:Arial, Helvetica, sans-serif;
	font-size:12px;
	color:#000;
	text-decoration:none;
}

.landScape{
	width: 100%;
	height: 100%;
	margin: 0% 0% 0% 0%;
	filter: progid:DXImageTransform.Microsoft.BasicImage(Rotation=3);
}
</style>
<script>
function getValue(elem){
	if(elem.prop("tagName")=="TD"){
		return elem.html();
	}else{
		return elem.val();
	}
}
</script>
<?php
$sql = "SELECT * FROM _sbis_users WHERE id = '".$_SESSION['user']['id']."' LIMIT 1";
$rows = dbQuery($sql);

$ext = array('.jpg', '.gif', '.png');
foreach($ext as $value){
	if( file_exists("../images/user_images/company_".$rows[0]['id'].$value) ){
		$photo1 = "company_".$rows[0]['id'].$value;
	}
}

$photo1 = empty($photo1) ? 'default.jpg' : $photo1;

$sql_ship = "SELECT * FROM _xvas_parsed2_dry WHERE imo = '".$_GET['imo']."' LIMIT 0,1";
$r_ship = dbQuery($sql_ship);

$sql_xvax = "SELECT * FROM _xvas_shipdata_dry WHERE imo = '".$_GET['imo']."' LIMIT 0,1";
$r_xvax = dbQuery($sql_xvax);

$name = $r_ship[0]['imo']." - ".$r_ship[0]['name'];
$mmsi = $r_ship[0]['mmsi'];
$imo = $r_ship[0]['imo'];
$summer_dwt = $r_ship[0]['summer_dwt'];
$gross_tonnage = getValue($r_xvax[0]['data'], 'GROSS_TONNAGE');
$built_year = getValue($r_xvax[0]['data'], 'BUILD');

$flag = getValue($r_xvax[0]['data'], 'LAST_KNOWN_FLAG');
if($flag==""){
	$flag = getValue($r_xvax[0]['data'], 'FLAG');
	$flag_image = getFlagImage($flag);
}else{
	$flag = $flag;
	$flag_image = getFlagImage($flag);
}

$loa = getValue($r_xvax[0]['data'], 'LENGTH_OVERALL');
$draught = getValue($r_xvax[0]['data'], 'DRAUGHT');
$speed = $r_ship[0]['speed'];
$breadth = getValue($r_xvax[0]['data'], 'BREADTH_EXTREME');
$cranes = getValue($r_xvax[0]['data'], 'CRANES');
$grain = getValue($r_xvax[0]['data'], 'GRAIN');
$cargo_handling = getValue($r_xvax[0]['data'], 'CARGO_HANDLING');
$decks_number = getValue($r_xvax[0]['data'], 'DECKS_NUMBER');
$bulkheads = getValue($r_xvax[0]['data'], 'BULKHEADS');
$class_notation = getValue($r_xvax[0]['data'], 'CLASS_NOTATION');
$lifting_equipment = getValue($r_xvax[0]['data'], 'LIFTING_EQUIPMENT');
$bale = getValue($r_xvax[0]['data'], 'BALE');
$fuel_oil = getValue($r_xvax[0]['data'], 'FUEL_OIL');
$fuel = getValue($r_xvax[0]['data'], 'FUEL');
$fuel_consumption = getValue($r_xvax[0]['data'], 'FUEL_CONSUMPTION');
$fuel_type = getValue($r_xvax[0]['data'], 'FUEL_TYPE');
$manager_owner = getValue($r_xvax[0]['data'], 'MANAGER_OWNER');
$manager_owner_email = getValue($r_xvax[0]['data'], 'MANAGER_OWNER_EMAIL');
$class_society = htmlentities(getValue($r_xvax[0]['data'], 'CLASS_SOCIETY'));
$holds = htmlentities(getValue($r_xvax[0]['data'], 'HOLDS'));
$largest_hatch = htmlentities(getValue($r_xvax[0]['data'], 'LARGEST_HATCH'));

$c31 = $_GET['c31'];
$d31 = $_GET['d31'];
$e31 = $_GET['e31'];
$g31 = $_GET['g31'];
$e33 = $_GET['e33'];
$g33 = $_GET['g33'];
$e34 = $_GET['e34'];
$g34 = $_GET['g34'];
$s31 = $_GET['s31'];
$t31 = $_GET['t31'];
$i32 = $_GET['i32'];
$k32 = $_GET['k32'];
$m32 = $_GET['m32'];
$n32 = $_GET['n32'];
$p32 = $_GET['p32'];
$q32 = $_GET['q32'];
$s32 = $_GET['s32'];
$t32 = $_GET['t32'];
$l33 = $_GET['l33'];
$m33 = $_GET['m33'];
$n33 = $_GET['n33'];
$p33 = $_GET['p33'];
$q33 = $_GET['q33'];
$s33 = $_GET['s33'];
$t33 = $_GET['t33'];
$s34 = $_GET['s34'];
$t34 = $_GET['t34'];
$i35 = $_GET['i35'];
$k35 = $_GET['k35'];
$m35 = $_GET['m35'];
$n35 = $_GET['n35'];
$p35 = $_GET['p35'];
$q35 = $_GET['q35'];
$s35 = $_GET['s35'];
$t35 = $_GET['t35'];
$bunker_price_dateupdated = $_GET['bunker_price_dateupdated'];
$d42 = $_GET['d42'];
$d42_180 = $_GET['d42_180'];
$d42_lsifo380 = $_GET['d42_lsifo380'];
$d42_lsifo180 = $_GET['d42_lsifo180'];
$h42 = $_GET['h42'];
$h42_mgo = $_GET['h42_mgo'];
$h42_lsmgo = $_GET['h42_lsmgo'];
$c44 = $_GET['c44'];
$d44 = $_GET['d44'];
$e44 = $_GET['e44'];
$g44 = $_GET['g44'];
$h44 = $_GET['h44'];
$f45 = $_GET['f45'];
$i45 = $_GET['i45'];
$d19 = $_GET['d19'];
$d20 = $_GET['d20'];
$d21 = $_GET['d21'];
$d22 = $_GET['d22'];
$d23 = $_GET['d23'];
$d24 = $_GET['d24'];
$c51 = $_GET['c51'];
$c52 = $_GET['c52'];
$term = $_GET['term'];
$linerterms = $_GET['linerterms'];
$dues1 = $_GET['dues1'];
$dues2 = $_GET['dues2'];
$dues3 = $_GET['dues3'];
$pilotage1 = $_GET['pilotage1'];
$pilotage2 = $_GET['pilotage2'];
$pilotage3 = $_GET['pilotage3'];
$tugs1 = $_GET['tugs1'];
$tugs2 = $_GET['tugs2'];
$tugs3 = $_GET['tugs3'];
$bunkeradjustment1 = $_GET['bunkeradjustment1'];
$bunkeradjustment2 = $_GET['bunkeradjustment2'];
$bunkeradjustment3 = $_GET['bunkeradjustment3'];
$mooring1 = $_GET['mooring1'];
$mooring2 = $_GET['mooring2'];
$mooring3 = $_GET['mooring3'];
$dockage1 = $_GET['dockage1'];
$dockage2 = $_GET['dockage2'];
$dockage3 = $_GET['dockage3'];
$loaddischarge1 = $_GET['loaddischarge1'];
$loaddischarge2 = $_GET['loaddischarge2'];
$loaddischarge3 = $_GET['loaddischarge3'];
$agencyfee1 = $_GET['agencyfee1'];
$agencyfee2 = $_GET['agencyfee2'];
$agencyfee3 = $_GET['agencyfee3'];
$miscellaneous1 = $_GET['miscellaneous1'];
$miscellaneous2 = $_GET['miscellaneous2'];
$miscellaneous3 = $_GET['miscellaneous3'];
$canal = $_GET['canal'];
$cbook1 = $_GET['cbook1'];
$cbook2 = $_GET['cbook2'];
$ctug1 = $_GET['ctug1'];
$ctug2 = $_GET['ctug2'];
$cline1 = $_GET['cline1'];
$cline2 = $_GET['cline2'];
$cmisc1 = $_GET['cmisc1'];
$cmisc2 = $_GET['cmisc2'];
$e74 = $_GET['e74'];
$f74 = $_GET['f74'];
$g74 = $_GET['g74'];
$h74 = $_GET['h74'];
$i74 = $_GET['i74'];
$j74 = $_GET['j74'];
$b80 = $_GET['b80'];
$d80 = $_GET['d80'];
$e80 = $_GET['e80'];
$d85 = $_GET['d85'];
$e85 = $_GET['e85'];
$g85 = $_GET['g85'];

//CALCULATED
$f31 = $_GET['f31'];
$h31 = $_GET['h31'];
$c32 = $_GET['c32'];
$d32 = $_GET['d32'];
$e32 = $_GET['e32'];
$f32 = $_GET['f32'];
$g32 = $_GET['g32'];
$h32 = $_GET['h32'];
$c33 = $_GET['c33'];
$d33 = $_GET['d33'];
$f33 = $_GET['f33'];
$h33 = $_GET['h33'];
$c34 = $_GET['c34'];
$d34 = $_GET['d34'];
$f34 = $_GET['f34'];
$h34 = $_GET['h34'];
$c35 = $_GET['c35'];
$d35 = $_GET['d35'];
$e35 = $_GET['e35'];
$f35 = $_GET['f35'];
$g35 = $_GET['g35'];
$h35 = $_GET['h35'];
$r31 = $_GET['r31'];
$j32 = $_GET['j32'];
$l32 = $_GET['l32'];
$o32 = $_GET['o32'];
$o33 = $_GET['o33'];
$r33 = $_GET['r33'];
$r34 = $_GET['r34'];
$j35 = $_GET['j35'];
$l35 = $_GET['l35'];
$o35 = $_GET['o35'];
$o36 = $_GET['o36'];
$r36 = $_GET['r36'];
$o37 = $_GET['o37'];
$c45 = $_GET['c45'];
$d45 = $_GET['d45'];
$e45 = $_GET['e45'];
$g45 = $_GET['g45'];
$h45 = $_GET['h45'];
$c46 = $_GET['c46'];
$d46 = $_GET['d46'];
$e46 = $_GET['e46'];
$f46 = $_GET['f46'];
$g46 = $_GET['g46'];
$h46 = $_GET['h46'];
$i46 = $_GET['i46'];
$c47 = $_GET['c47'];
$g47 = $_GET['g47'];
$d18 = $_GET['d18'];
$d19b = $_GET['d19b'];
$d20b = $_GET['d20b'];
$d21b = $_GET['d21b'];
$d22b = $_GET['d22b'];
$d25 = $_GET['d25'];
$d26 = $_GET['d26'];
$ctotal1 = $_GET['ctotal1'];
$ctotal2 = $_GET['ctotal2'];
$c54 = $_GET['c54'];
$c66 = $_GET['c66'];
$c67 = $_GET['c67'];
$c68 = $_GET['c68'];
$b74 = $_GET['b74'];
$c74 = $_GET['c74'];
$d74 = $_GET['d74'];
$b75 = $_GET['b75'];
$b85 = $_GET['b85'];
$c85 = $_GET['c85'];
$f85 = $_GET['f85'];
$d86 = $_GET['d86'];
$c80 = $_GET['c80'];
$f80 = $_GET['f80'];
$g80 = $_GET['g80'];
$d81 = $_GET['d81'];
//END OF CALCULATED

echo "<div class='landScape'>
<table width='1300' border='0' cellpadding='0' cellspacing='0'>
	<tr>
		<td style='border:0px;' width='460'><img src='http://".$_SERVER['HTTP_HOST']."/app/images/logo_ve2.png'></td>
		<td style='border:0px; text-align:right;' width='540'><img src='http://".$_SERVER['HTTP_HOST']."/app/images/user_images/".$photo1."' width='80' alt='photo' border='0' /><br>Sent by <a href='mailto:".$rows[0]['email']."'>".$rows[0]['email']."</a></td>
	</tr>
</table>
<div style='text-align:left; padding:15px 5px 5px 5px;'><b>CURRENT DATE/TIME: ".date("d-m-Y")."</b></div>
<table width='1300' border='0' cellpadding='0' cellspacing='0'>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<th>";
		
		?>
		<table width="1300" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="1000">
				<table width="1000" border="0" cellspacing="0" cellpadding="0">
				  <tr bgcolor="cddee5">
					<td class="text_1"><div style="padding:3px;"><b>VESSEL NAME / IMO #</b> &nbsp; <?php echo $name; ?></div></td>
				  </tr>
				</table>
				<div id="ship_info">
					<table width="1000" border="0" cellspacing="0" cellpadding="0">
					  <tr bgcolor="f5f5f5">
						<td width="110" valign="top"><div style="padding:3px;"><b>IMO</b> #</div></td>
						<td width="160" valign="top" style="padding:3px;" id="ship_imo"><?php echo $imo; ?></td>
						<td width="105" valign="top"><div style="padding:3px;"><b>LOA</b></div></td>
						<td width="100" valign="top" style="padding:3px;" id="ship_loa"><?php echo $loa; ?></td>
						<td width="145" valign="top"><div style="padding:3px;"><b>Grain</b></div></td>
						<td width="160" valign="top" style="padding:3px;" id="ship_grain"><?php echo $grain; ?></td>
						<td width="120" valign="top"><div style="padding:3px;"><b>Class Notation</b></div></td>
						<td width="100" valign="top" style="padding:3px;" id="ship_class_notation"><?php echo $class_notation; ?></td>
					  </tr>
					  <tr bgcolor="e9e9e9">
						<td valign="top"><div style="padding:3px;"><b>Summer DWT</b></div></td>
						<td valign="top" style="padding:3px;" id="ship_summer_dwt"><?php echo $summer_dwt; ?></td>
						<td valign="top"><div style="padding:3px;"><b>Draught</b></div></td>
						<td valign="top" style="padding:3px;" id="ship_draught"><?php echo $draught; ?></td>
						<td valign="top"><div style="padding:3px;"><b>Lifting Equipment</b></div></td>
						<td valign="top" style="padding:3px;" id="ship_lifting_equipment"><?php echo $lifting_equipment; ?></td>
						<td valign="top"><div style="padding:3px;"><b>Fuel Oil</b></div></td>
						<td valign="top" style="padding:3px;" id="ship_fuel_oil"><?php echo $fuel_oil; ?></td>
					  </tr>
					  <tr bgcolor="f5f5f5">
						<td valign="top"><div style="padding:3px;"><b>Gross Tonnage</b></div></td>
						<td valign="top" style="padding:3px;" id="ship_gross_tonnage"><?php echo $gross_tonnage; ?></td>
						<td valign="top"><div style="padding:3px;"><b>Speed</b></div></td>
						<td valign="top" style="padding:3px;" id="ship_speed"><?php echo $speed; ?></td>
						<td valign="top"><div style="padding:3px;"><b>Cargo Handling</b></div></td>
						<td valign="top" style="padding:3px;" id="ship_cargo_handling"><?php echo $cargo_handling; ?></td>
						<td valign="top"><div style="padding:3px;"><b>Fuel</b></div></td>
						<td valign="top" style="padding:3px;" id="ship_fuel"><?php echo $fuel; ?></td>
					  </tr>
					  <tr bgcolor="e9e9e9">
						<td valign="top"><div style="padding:3px;"><b>Built Year</b></div></td>
						<td valign="top" style="padding:3px;" id="ship_built_year"><?php echo $built_year; ?></td>
						<td valign="top"><div style="padding:3px;"><b>Breadth</b></div></td>
						<td valign="top" style="padding:3px;" id="ship_breadth"><?php echo $breadth; ?></td>
						<td valign="top"><div style="padding:3px;"><b>Decks Number</b></div></td>
						<td valign="top" style="padding:3px;" id="ship_decks_number"><?php echo $decks_number; ?></td>
						<td valign="top"><div style="padding:3px;"><b>Fuel Consumption</b></div></td>
						<td valign="top" style="padding:3px;" id="ship_fuel_consumption"><?php echo $fuel_consumption; ?></td>
					  </tr>
					  <tr bgcolor="f5f5f5">
						<td valign="top"><div style="padding:3px;"><b>Bale</b></div></td>
						<td valign="top" style="padding:3px;" id="ship_bale"><?php echo $bale; ?></td>
						<td valign="top"><div style="padding:3px;"><b>Cranes</b></div></td>
						<td valign="top" style="padding:3px;" id="ship_cranes"><?php echo $cranes; ?></td>
						<td valign="top"><div style="padding:3px;"><b>Bulkheads</b></div></td>
						<td valign="top" style="padding:3px;" id="ship_bulkheads"><?php echo $bulkheads; ?></td>
						<td valign="top"><div style="padding:3px;"><b>Fuel Type</b></div></td>
						<td valign="top" style="padding:3px;" id="ship_fuel_type"><?php echo $fuel_type; ?></td>
					  </tr>
					  <tr bgcolor="e9e9e9">
						<td valign="top"><div style="padding:3px;"><b>Manager Owner</b></div></td>
						<td valign="top" style="padding:3px;" id="ship_manager_owner"><?php echo $manager_owner; ?></td>
						<td valign="top"><div style="padding:3px;"><b>Manager Owner Email</b></div></td>
						<td valign="top" style="padding:3px;" id="ship_manager_owner_email"><?php echo $manager_owner_email; ?></td>
						<td valign="top"><div style="padding:3px;"><b>Class Society</b></div></td>
						<td valign="top" style="padding:3px;" id="ship_class_society"><?php echo $class_society; ?></td>
						<td valign="top"><div style="padding:3px;"><b>Largest Hatch</b></div></td>
						<td valign="top" style="padding:3px;" id="ship_largest_hatch"><?php echo $largest_hatch; ?></td>
					  </tr>
					  <tr bgcolor="f5f5f5">
						<td valign="top"><div style="padding:3px;"><b>Holds</b></div></td>
						<td valign="top" style="padding:3px;" id="ship_holds"><?php echo $holds; ?></td>
						<td valign="top"><div style="padding:3px;"><b>Flag</b></div></td>
						<td valign="top" style="padding:3px;" id="ship_flag"><?php echo '<img src="../'.$flag_image.'" alt="'.$flag.'" title="'.$flag.'">'; ?></td>
						<td valign="top"><div style="padding:3px;"><b>&nbsp;</b></div></td>
						<td valign="top" style="padding:3px;"></td>
						<td valign="top"><div style="padding:3px;"><b>&nbsp;</b></div></td>
						<td valign="top" style="padding:3px;"></td>
					  </tr>
					</table>
				</div>
				
				<div style="border-bottom:3px dotted #fff;">&nbsp;</div>
				<div>&nbsp;</div>
				
				<table width="1000" border="0" cellspacing="0" cellpadding="0">
				  <tr bgcolor="cddee5">
					<td width="120" class="text_1"><div style="padding:3px;"><b>VOYAGE LEGS</b></div></td>
					<td width="200"></td>
					<td width="190"></td>
					<td width="100"></td>
					<td width="190"></td>
					<td width="100"></td>
					<td width="100"></td>
				  </tr>
				  <tr>
					<td class="text_1 label"><div style="padding:3px;"><i><strong>Type</strong></i></div></td>
					<td class="text_1 label"><div style="padding:3px;"><i><strong> Port</strong></i></div></td>
					<td class="text_1 label"><div style="padding:3px;"><i><strong>Date</strong></i></div></td>
					<td class="text_1 label"><div style="padding:3px;"><i><strong> Port</strong></i></div></td>
					<td class="text_1 label"><div style="padding:3px;"><i><strong>Date</strong></i></div></td>
					<td class="text_1 label"><div style="padding:3px;"><i><strong>Speed (knts)</strong></i></div></td>
					<td class="text_1 label"><div style="padding:3px;"><i><strong>Distance (miles)</strong></i></div></td>
				  </tr>
				  <tr id='ballast1' bgcolor="f5f5f5">
					<td class='general b31' style="padding:3px;"><strong>Ballast</strong></td>
					<td class='input'><div style="padding:3px;"><?php echo $c31; ?></div></td>
					<td class="input"><div style="padding:3px;"><?php echo $d31; ?></div></td>
					<td class='input'><div style="padding:3px;"><?php echo $e31; ?></div></td>
					<td class='calculated general f31' style="padding:3px;"><?php echo $f31; ?></td>
					<td class='input'><div style="padding:3px;"><?php echo $g31; ?></div></td>
					<td class="calculated number h31" style="padding:3px;"><?php echo $h31; ?></td>
				  </tr>
				  <tr id='loading1' bgcolor="e9e9e9">
					<td class='general b32' style="padding:3px;"><strong>Loading</strong></td>
					<td class='general c32' style="padding:3px;"><?php echo $c32; ?></td>
					<td class='general d32' style="padding:3px;"><?php echo $d32; ?></td>
					<td class='general e32' style="padding:3px;"><?php echo $e32; ?></td>
					<td class="calculated f32" style="padding:3px;"><?php echo $f32; ?></td>
					<td class='number g32' style="padding:3px;"><?php echo $g32; ?></td>
					<td class="number h32" style="padding:3px;"><?php echo $h32; ?></td>
				  </tr>
				  <tr id='bunkerstop1' bgcolor="f5f5f5">
					<td class='general b33' style="padding:3px;"><strong>Bunker Stop</strong></td>
					<td class='input general c33' style="padding:3px;"><?php echo $c33; ?></td>
					<td class='general d33' style="padding:3px;"><?php echo $d33; ?></td>
					<td class='input' style="padding:3px;"><?php echo $e33; ?></td>
					<td class="calculated f33" style="padding:3px;"><?php echo $f33; ?></td>
					<td class='input' style="padding:3px;"><?php echo $g33; ?></td>
					<td class="calculated h33" style="padding:3px;"><?php echo $h33; ?></td>
				  </tr>
				  <tr id='laden1' bgcolor="e9e9e9">
					<td class='general b34' style="padding:3px;"><strong>Laden</strong></td>
					<td class='input general c34' style="padding:3px;"><?php echo $c34; ?></td>
					<td class='general d34' style="padding:3px;"><?php echo $d34; ?></td>
					<td class='input' style="padding:3px;"><?php echo $e34; ?></td>
					<td class="calculated f34" style="padding:3px;"><?php echo $f34; ?></td>
					<td class='input' style="padding:3px;"><?php echo $g34; ?></td>
					<td class="calculated number h34" style="padding:3px;"><?php echo $h34; ?></td>
				  </tr>
				  <tr id='discharging1' bgcolor="f5f5f5">
					<td class='general b35' style="padding:3px;"><strong>Discharging</strong></td>
					<td class='input general c35' style="padding:3px;"><?php echo $c35; ?></td>
					<td class='general d35' style="padding:3px;"><?php echo $d35; ?></td>
					<td class='general e35' style="padding:3px;"><?php echo $e35; ?></td>
					<td class="calculated f35" style="padding:3px;"><?php echo $f35; ?></td>
					<td class='number g35' style="padding:3px;"><?php echo $g35; ?></td>
					<td class="number h35" style="padding:3px;"><?php echo $h35; ?></td>
				  </tr>
				</table>
				
				<div style="border-bottom:3px dotted #fff;">&nbsp;</div>
				<div>&nbsp;</div>
				
				<table width="1000" border="0" cellspacing="0" cellpadding="0">
				  <tr bgcolor="cddee5">
					<td class="text_1" colspan="2"><div style="padding:3px;"><b>CARGO LEGS</b></div></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td class="text_1" colspan="2"><div style="padding:3px;"><b>* Option to Load &amp; Bunker concurrently</b></div></td>
					<td class="text_1" colspan="3"><div style="padding:3px;"><b>Port Days</b></div></td>
					<td class="text_1" colspan="3"><div style="padding:3px;"><b>Sea Days</b></div></td>
				  </tr>
				  <tr>
					<td width="71" class="text_1 label"><div style="padding:3px;"><i><strong>Type</strong></i></div></td>
					<td width="102" class="text_1 label"><div style="padding:3px;"><i><strong>Cargo</strong></i></div></td>
					<td width="18" class="text_1 label"><div style="padding:3px;"><i><strong>SF</strong></i></div></td>
					<td width="102" class="text_1 label"><div style="padding:3px;"><i><strong>Quantity (MT)</strong></i></div></td>
					<td width="102" class="text_1 label"><div style="padding:3px;"><i><strong>Volume (M3)</strong></i></div></td>
					<td width="102" class="text_1 label"><div style="padding:3px;"><i><strong>L/D Rate (MT/day)</strong></i></div></td>
					<td width="167" class="text_1 label"><div style="padding:3px;"><i><strong>Working Days</strong></i></div></td>
					<td width="45" class="text_1 label"><div style="padding:3px;"><i><strong>L/D</strong></i></div></td>
					<td width="102" class="text_1 label"><div style="padding:3px;"><i><strong>Turn Time</strong></i></div></td>
					<td width="102" class="text_1 label"><div style="padding:3px;"><i><strong>Idle/Extra Days Sea</strong></i></div></td>
					<td width="7" class="text_1 label"><div style="padding:3px;"><i><strong>&nbsp;</strong></i></div></td>
					<td width="102" class="text_1 label"><div style="padding:3px;"><i><strong>Canal Days</strong></i></div></td>
					<td width="108" class="text_1 label"><div style="padding:3px;"><i><strong>Weather/Extra Days</strong></i></div></td>
				  </tr>
				  <tr id='ballast1' bgcolor="f5f5f5">
					<td class='general b31' style="padding:3px;"><strong>Ballast</strong></td>
					<td class='number i31' style="padding:3px;"></td>
					<td class='number j31' style="padding:3px;"></td>	
					<td class='number k31' style="padding:3px;"></td>
					<td class='number l31' style="padding:3px;"></td>
					<td class='number m31' style="padding:3px;"></td>
					<td class='number n31' style="padding:3px;"></td>
					<td class="number o31" style="padding:3px;"></td>
					<td class='number p31' style="padding:3px;"></td>
					<td class='number q31' style="padding:3px;"></td>
					<td class="calculated number r31" style="padding:3px;"><?php echo $r31; ?></td>
					<td class='empty' style="padding:3px;"><?php echo $s31; ?></td>
					<td class='empty' style="padding:3px;"><?php echo $t31; ?></td>
				  </tr>
				  <tr id='loading1' bgcolor="e9e9e9">
					<td class='general b32' style="padding:3px;"><strong>Loading</strong></td>
					<td class='input' style="padding:3px;"><?php echo $i32; ?></td>
					<td class='number j32' style="padding:3px;"><?php echo $j32; ?></td>
					<td class='input' style="padding:3px;"><?php echo $k32; ?></td>
					<td class='calculated number l32' style="padding:3px;"><?php echo $l32; ?></td>
					<td class='input' style="padding:3px;"><?php echo $m32; ?></td>
					<td class='input' style="padding:3px;"><?php echo $n32; ?></td>
					<td class="calculated number o32" style="padding:3px;"><?php echo $o32; ?></td>
					<td class='input' style="padding:3px;"><?php echo $p32; ?></td>
					<td class='input' style="padding:3px;"><?php echo $q32; ?></td>
					<td class="number r32" style="padding:3px;"><?php echo $r32; ?></td>
					<td class='empty' style="padding:3px;"><?php echo $s32; ?></td>
					<td class='empty' style="padding:3px;"><?php echo $t32; ?></td>
				  </tr>
				  <tr id='bunkerstop1' bgcolor="f5f5f5">
					<td class='general b33' style="padding:3px;"><strong>Bunker Stop</strong></td>
					<td class='number i33' style="padding:3px;"></td>
					<td class='number j33' style="padding:3px;"></td>
					<td class='number k33' style="padding:3px;"></td>
					<td class='input' style="padding:3px;"><?php echo $l33; ?></td>
					<td class='input' style="padding:3px;"><?php echo $m33; ?></td>
					<td class='input' style="padding:3px;"><?php echo $n33; ?></td>
					<td class="calculated o33" style="padding:3px;"><?php echo $o33; ?></td>
					<td class='input' style="padding:3px;"><?php echo $p33; ?></td>
					<td class='input' style="padding:3px;"><?php echo $q33; ?></td>
					<td class="calculated number r33" style="padding:3px;"><?php echo $r33; ?></td>
					<td class='empty' style="padding:3px;"><?php echo $s33; ?></td>
					<td class='empty' style="padding:3px;"><?php echo $t33; ?></td>
				  </tr>
				  <tr id='laden1' bgcolor="e9e9e9">
					<td class='general b34' style="padding:3px;"><strong>Laden</strong></td>
					<td class='number i34' style="padding:3px;"></td>
					<td class='number j34' style="padding:3px;"></td>
					<td class='number k34' style="padding:3px;"></td>
					<td class='number l34' style="padding:3px;"></td>
					<td class='number m34' style="padding:3px;"></td>
					<td class='number n34' style="padding:3px;"></td>
					<td class="number o34" style="padding:3px;"></td>
					<td class='number p34' style="padding:3px;"></td>
					<td class='number q34' style="padding:3px;"></td>
					<td class="calculated number r34" style="padding:3px;"><?php echo $r34; ?></td>
					<td class='empty' style="padding:3px;"><?php echo $s34; ?></td>
					<td class='empty' style="padding:3px;"><?php echo $t34; ?></td>
				  </tr>
				  <tr id='discharging1' bgcolor="f5f5f5">
					<td class='general b35' style="padding:3px;"><strong>Discharging</strong></td>
					<td class='input' style="padding:3px;"><?php echo $i35; ?></td>
					<td class='number j35' style="padding:3px;"><?php echo $j35; ?></td>
					<td class='input' style="padding:3px;"><?php echo $k35; ?></td>
					<td class='calculated number l35' style="padding:3px;"><?php echo $l35; ?></td>
					<td class='input' style="padding:3px;"><?php echo $m35; ?></td>
					<td class='input' style="padding:3px;"><?php echo $n35; ?></td>
					<td class="calculated number o35" style="padding:3px;"><?php echo $o35; ?></td>
					<td class='input' style="padding:3px;"><?php echo $p35; ?></td>
					<td class='input' style="padding:3px;"><?php echo $q35; ?></td>
					<td class="number r35" style="padding:3px;"></td>
					<td class='empty' style="padding:3px;"><?php echo $s35; ?></td>
					<td class='empty' style="padding:3px;"><?php echo $t35; ?></td>
				  </tr>
				</table>
				
				<div style="border-bottom:3px dotted #fff;">&nbsp;</div>
				<div>&nbsp;</div>
				
				<table width="1000" border="0" cellspacing="0" cellpadding="0">
				  <tr bgcolor="cddee5">
					<td width="100" class="text_1"><div style="padding:3px;"><b>VOYAGE TIME</b></div></td>
					<td width="132" class="text_1"><div style="padding:3px;">&nbsp;</div></td>
					<td width="18" class="text_1"><div style="padding:3px;">&nbsp;</div></td>
					<td width="122" class="text_1"><div style="padding:3px;">&nbsp;</div></td>
					<td width="102" class="text_1"><div style="padding:3px;">&nbsp;</div></td>
					<td width="102" class="text_1"><div style="padding:3px;">&nbsp;</div></td>
					<td width="100" class="text_1"><div style="padding:3px;">&nbsp;</div></td>
					<td width="45" class="text_1"><div style="padding:3px;">&nbsp;</div></td>
					<td width="132" class="text_1"><div style="padding:3px;">&nbsp;</div></td>
					<td width="100" class="text_1"><div style="padding:3px;">&nbsp;</div></td>
					<td width="7" class="text_1"><div style="padding:3px;">&nbsp;</div></td>
					<td width="132" class="text_1"><div style="padding:3px;">&nbsp;</div></td>
					<td width="38" class="text_1"><div style="padding:3px;">&nbsp;</div></td>
				  </tr>
				  <tr>
					<td colspan="7" class="label" style="padding:3px;"><strong>PORT/SEA DAYS</strong></td>
					<td colspan="3" class="label calculated" id='o36' style="padding:3px;"><?php echo $o36; ?></td>
					<td colspan="3" class="label calculated" id='r36' style="padding:3px;"><?php echo $r36; ?></td>
				  </tr>
				  <tr>
					<td colspan="7" class="label" style="padding:3px;"><strong>TOTAL VOYAGE DAYS</strong></td>
					<td colspan="6" class="label calculated" id='o37' style="padding:3px;"><?php echo $o37; ?></td>
				  </tr>
				</table>
				
				<div style="border-bottom:3px dotted #fff;">&nbsp;</div>
				<div>&nbsp;</div>
				
				<table width="1000" border="0" cellspacing="0" cellpadding="0">
				  <tr bgcolor="cddee5">
					<td class="text_1"><div style="padding:3px;"><b>BUNKER PRICING - Data from Bunkerworld</b> <span id="bunker_price_dateupdated"><?php echo $bunker_price_dateupdated; ?></span></div></td>
				  </tr>
				</table>
				<table width="1000" border="0" cellspacing="0" cellpadding="0">
				  <tr bgcolor="f5f5f5">
					<td width="500" colspan="5" style="padding:3px;"><b>IFO Type</b></td>
					<td width="500" colspan="4" style="padding:3px;"><b>MDO Type</b></td>
				  </tr>
				  <tr bgcolor="e9e9e9">
					<td style="padding:3px;"><b>IFO 380 Price ($)</b></td>
					<td colspan="4" class="input" style="padding:3px;"><?php echo $d42; ?></td>
					<td style="padding:3px;"><b>MDO Price ($)</b></td>
					<td colspan="3" class="input" style="padding:3px;"><?php echo $h42; ?></td>
				  </tr>
				  <tr bgcolor="e9e9e9">
					<td style="padding:3px;"><b>IFO 180 Price ($)</b></td>
					<td colspan="4" class="input" style="padding:3px;"><?php echo $d42_180; ?></td>
					<td style="padding:3px;"><b>MGO Price ($)</b></td>
					<td colspan="3" class="input" style="padding:3px;"><?php echo $h42_mgo; ?></td>
				  </tr>
				  <tr bgcolor="e9e9e9">
					<td style="padding:3px;"><b>LS IFO 380 1% Price ($)</b></td>
					<td colspan="4" class="input" style="padding:3px;"><?php echo $d42_lsifo380; ?></td>
					<td style="padding:3px;"><b>LS MGO 1% Price ($)</b></td>
					<td colspan="3" class="input" style="padding:3px;"><?php echo $h42_lsmgo; ?></td>
				  </tr>
				  <tr bgcolor="e9e9e9">
					<td style="padding:3px;"><b>LS IFO 180 1% Price ($)</b></td>
					<td colspan="4" class="input" style="padding:3px;"><?php echo $d42_lsifo180; ?></td>
					<td style="padding:3px;">&nbsp;</td>
					<td colspan="3" class="input" style="padding:3px;">&nbsp;</td>
				  </tr>
				  <tr>
					<td class="text_1 label" style="padding:3px;"><b><i>&nbsp;</i></b></td>
					<td class="text_1 label" style="padding:3px;"><b><i>IFO/Ballast</i></b></td>
					<td class="text_1 label" style="padding:3px;"><b><i>IFO/Laden</i></b></td>
					<td class="text_1 label" style="padding:3px;"><b><i>IFO/Port</i></b></td>
					<td class="text_1 label" style="padding:3px;"><b><i>IFO/Reserve</i></b></td>
					<td class="text_1 label" style="padding:3px;"><b><i>&nbsp;</i></b></td>
					<td class="text_1 label" style="padding:3px;"><b><i>MDO/Laden</i></b></td>
					<td class="text_1 label" style="padding:3px;"><b><i>MDO/Port</i></b></td>
					<td class="text_1 label" style="padding:3px;"><b><i>MDO/Reserve</i></b></td>
				  </tr>
				  <tr bgcolor="f5f5f5">
					<td style="padding:3px;"><b>Consumption (MT/day)</b></td>
					<td class='input' style="padding:3px;"><?php echo $c44; ?></td>
					<td class='input' style="padding:3px;"><?php echo $d44; ?></td>
					<td class='input' style="padding:3px;"><?php echo $e44; ?></td>
					<td class="input" style="padding:3px;">&nbsp;</td>
					<td class="input" style="padding:3px;">&nbsp;</td>
					<td class='input' style="padding:3px;"><?php echo $g44; ?></td>
					<td class='input' style="padding:3px;"><?php echo $h44; ?></td>
					<td class='general' id='i44' style="padding:3px;"></td>
				  </tr>
				  <tr>
					<td class="label" style="padding:3px;"><strong>Total Consumption (MT)</strong></td>
					<td class="label calculated" id='c45' style="padding:3px;"><?php echo $c45; ?></td>
					<td class="label calculated" id='d45' style="padding:3px;"><?php echo $d45; ?></td>
					<td class="label calculated" id='e45' style="padding:3px;"><?php echo $e45; ?></td>
					<td class='label' style="padding:3px;"><?php echo $f45; ?></td>
					<td class="label" style="padding:3px;"></td>
					<td class="label calculated" id='g45' style="padding:3px;"><?php echo $g45; ?></td>
					<td class="label calculated" id='h45' style="padding:3px;"><?php echo $h45; ?></td>
					<td class='label input' style="padding:3px;"><?php echo $i45; ?></td>
				  </tr>
				</table>
				
				<div style="border-bottom:3px dotted #fff;">&nbsp;</div>
				<div>&nbsp;</div>
				
				<table width="1000" border="0" cellspacing="0" cellpadding="0">
				  <tr bgcolor="cddee5">
					<td class="text_1" colspan="8"><div style="padding:3px;"><b>VOYAGE EXPENSES</b></div></td>
				  </tr>
				  <tr>
					<td class="label" style="padding:3px;"><strong>Expense ($)</strong></td>
					<td class="label calculated" id='c46' style="padding:3px;"><?php echo $c46; ?></td>
					<td class="label calculated" id='d46' style="padding:3px;"><?php echo $d46; ?></td>
					<td class="label calculated" id='e46' style="padding:3px;"><?php echo $e46; ?></td>
					<td class="label calculated" id='f46' style="padding:3px;"><?php echo $f46; ?></td>
					<td class="label calculated" id='g46' style="padding:3px;"><?php echo $g46; ?></td>
					<td class="label calculated" id='h46' style="padding:3px;"><?php echo $h46; ?></td>
					<td class="label calculated" id='i46' style="padding:3px;"><?php echo $i46; ?></td>
				  </tr>
				  <tr>
					<td class="label" style="padding:3px;"><strong>Total ($)</strong></td>
					<td colspan="4" class="label calculated" id='c47' style="padding:3px;"><?php echo $c47; ?></td>
					<td colspan="4" class="label calculated" id='g47' style="padding:3px;"><?php echo $g47; ?></td>
				  </tr>
				</table>
				
				<div style="border-bottom:3px dotted #fff;">&nbsp;</div>
				<div>&nbsp;</div>
				
				<div style="float:left; width:1000px; height:auto;">
					<div style="float:left; width:490px; height:auto; padding-right:10px;">
						<table width="490" border="0" cellspacing="0" cellpadding="0">
						  <tr bgcolor="cddee5">
							<td class="text_1" colspan="8"><div style="padding:3px;"><b>DWCC</b></div></td>
						  </tr>
						  <tr bgcolor="f5f5f5">
							<td colspan="2" style="padding:3px;"><strong>DW (MT)</strong></td>
							<td width="105" class='calculated number' id='d18' style="padding:3px;"><?php echo $d18; ?></td>
							<td width="180" style="padding:3px;"><strong>Calculated Amount  </strong></td>
						  </tr>
						  <tr bgcolor="e9e9e9">
							<td width="100" height="34" rowspan="2" style="padding:3px;"><b>Consumption (MT)</b></td>
							<td width="30" style="padding:3px;"><b>FO</b></td>
							<td height="12" class='input' style="padding:3px;"><?php echo $d19; ?></td>
							<td class='calculated general' id='d19b' style="padding:3px;"><?php echo $d19b; ?></td>
						  </tr>
						  <tr bgcolor="f5f5f5">
							<td style="padding:3px;"><b>DO</b></td>
							<td class='input' style="padding:3px;"><?php echo $d20; ?></td>
							<td class='calculated general' id='d20b' style="padding:3px;"><?php echo $d20b; ?></td>
						  </tr>
						  <tr bgcolor="e9e9e9">
							<td rowspan="2" style="padding:3px;"><b>Reserve (MT)</b></td>
							<td style="padding:3px;"><b>FO</b></td>
							<td class='input' style="padding:3px;"><?php echo $d21; ?></td>
							<td class='calculated general' id='d21b' style="padding:3px;"><?php echo $d21b; ?></td>
						  </tr>
						  <tr bgcolor="f5f5f5">
							<td style="padding:3px;"><b>DO</b></td>
							<td class='input' style="padding:3px;"><?php echo $d22; ?></td>
							<td class='calculated general' id='d22b' style="padding:3px;"><?php echo $d22b; ?></td>
						  </tr>
						  <tr bgcolor="e9e9e9">
							<td height="17" colspan="2" style="padding:3px;"><b>FW (MT)</b></td>
							<td class='input' style="padding:3px;"><?php echo $d23; ?></td>
							<td class='calculated general' id='d23b' style="padding:3px;"></td>
						  </tr>
						  <tr bgcolor="f5f5f5">
							<td height="18" colspan="2" style="padding:3px;"><b>Constant (MT)</b></td>
							<td class='input' style="padding:3px;"><?php echo $d24; ?></td>
							<td class='calculated general' id='d24b' style="padding:3px;"></td>
						  </tr>
						  <tr>
							<td colspan="2" class="label" style="padding:3px;"><strong>Used DW (MT)</strong></td>
							<td colspan="2" class='label calculated number' id='d25' style="padding:3px;"><?php echo $d25; ?></td>
						  </tr>
						  <tr>
							<td colspan="2" class="label" style="padding:3px;"><strong>DWCC (MT)</strong></td>
							<td colspan="2" class='label calculated number' id='d26' style="padding:3px;"><?php echo $d26; ?></td>
						  </tr>
						</table>
					
						<div style="border-bottom:3px dotted #fff;">&nbsp;</div>
						<div>&nbsp;</div>
					
						<table width="490" border="0" cellspacing="0" cellpadding="0">
						  <tr bgcolor="cddee5">
							<td class="text_1" colspan="5"><div style="padding:3px;"><b>PORT/S</b></div></td>
						  </tr>
						  <tr bgcolor="f5f5f5">
							<td width="122" style="padding:3px;"><strong>Laytime (hrs)</strong></td>
							<td width="122" class='input' style="padding:3px;"><?php echo $c51; ?></td>
							<td width="123" style="padding:3px;"></td>
							<td width="123" style="padding:3px;"></td>
						  </tr>
						  <tr bgcolor="e9e9e9">
							<td style="padding:3px;"><strong>Dem ($/day)</strong></td>
							<td class='input' style="padding:3px;"><?php echo $c52; ?></td>
							<td style="padding:3px;"><strong>Pro rated</strong></td>
							<td style="padding:3px;"></td>
						  </tr>
						  <tr bgcolor="f5f5f5">
							<td style="padding:3px;"><strong>Term</strong></td>
							<td style="padding:3px;"><?php echo $term; ?></td>
							<td style="padding:3px;"></td>
							<td style="padding:3px;"></td>
						  </tr>
						  <tr bgcolor="e9e9e9">
							<td style="padding:3px;"><strong>Des ($/day)</strong></td>
							<td class="calculated" id='c54' style="padding:3px;"><?php echo $c54; ?></td>
							<td style="padding:3px;"></td>
							<td style="padding:3px;"></td>
						  </tr>
						  <tr bgcolor="f5f5f5">
							<td style="padding:3px;"><strong>Liner Terms</strong></td>
							<td style="padding:3px;"><?php echo $linerterms; ?></td>
							<td style="padding:3px;"></td>
							<td style="padding:3px;"></td>
						  </tr>
						  <tr bgcolor="e9e9e9">
							<td style="padding:3px;"><strong>Port</strong></td>
							<td class='port1' id='port1' style="padding:3px;"><strong>Port 1</strong></td>
							<td class='port2' id='port2' style="padding:3px;"><strong>Port 2</strong></td>
							<td class='port3' id='port3' style="padding:3px;"><strong>Port 3 </strong></td>
						  </tr>
						  <tr bgcolor="f5f5f5">
							<td style="padding:3px;"><strong>Dues ($)</strong></td>
							<td class='input port1' style="padding:3px;"><?php echo $dues1; ?></td>
							<td class='input port2' style="padding:3px;"><?php echo $dues2; ?></td>
							<td class='input port3' style="padding:3px;"><?php echo $dues3; ?></td>
						  </tr>
						  <tr bgcolor="e9e9e9">
							<td style="padding:3px;"><strong>Pilotage ($)</strong></td>
							<td class='input port1' style="padding:3px;"><?php echo $pilotage1; ?></td>
							<td class='input port2' style="padding:3px;"><?php echo $pilotage2; ?></td>
							<td class='input port3' style="padding:3px;"><?php echo $pilotage3; ?></td>
						  </tr>
						  <tr bgcolor="f5f5f5">
							<td style="padding:3px;"><strong>Tugs ($)</strong></td>
							<td class='input port1' style="padding:3px;"><?php echo $tugs1; ?></td>
							<td class='input port2' style="padding:3px;"><?php echo $tugs2; ?></td>
							<td class='input port3' style="padding:3px;"><?php echo $tugs3; ?></td>
						  </tr>
						  <tr bgcolor="e9e9e9">
							<td style="padding:3px;"><strong>Bunker Adjustment ($)</strong></td>
							<td class='input port1' style="padding:3px;"><?php echo $bunkeradjustment1; ?></td>
							<td class='input port2' style="padding:3px;"><?php echo $bunkeradjustment2; ?></td>
							<td class='input port3' style="padding:3px;"><?php echo $bunkeradjustment3; ?></td>
						  </tr>
						  <tr bgcolor="f5f5f5">
							<td style="padding:3px;"><strong>Mooring ($)</strong></td>
							<td class='input port1' style="padding:3px;"><?php echo $mooring1; ?></td>
							<td class='input port2' style="padding:3px;"><?php echo $mooring2; ?></td>
							<td class='input port3' style="padding:3px;"><?php echo $mooring3; ?></td>
						  </tr>
						  <tr bgcolor="e9e9e9">
							<td style="padding:3px;"><strong>Dockage ($)</strong></td>
							<td class='input port1' style="padding:3px;"><?php echo $dockage1; ?></td>
							<td class='input port2' style="padding:3px;"><?php echo $dockage2; ?></td>
							<td class='input port3' style="padding:3px;"><?php echo $dockage3; ?></td>
						  </tr>
						  <tr bgcolor="f5f5f5">
							<td style="padding:3px;"><strong>Load/Discharge ($)</strong></td>
							<td class='input port1' style="padding:3px;"><?php echo $loaddischarge1; ?></td>
							<td class='input port2' style="padding:3px;"><?php echo $loaddischarge2; ?></td>
							<td height="12" class='input port3' style="height: 12px; padding:3px;"><?php echo $loaddischarge3; ?></span></td>
						  </tr>
						  <tr bgcolor="e9e9e9">
							<td style="padding:3px;"><strong>Agency Fee ($)</strong></td>
							<td class='input port1' style="padding:3px;"><?php echo $agencyfee1; ?></td>
							<td class='input port2' style="padding:3px;"><?php echo $agencyfee2; ?></td>
							<td class='input port3' style="padding:3px;"><?php echo $agencyfee3; ?></td>
						  </tr>
						  <tr bgcolor="f5f5f5">
							<td style="padding:3px;"><strong>Miscellaneous ($)</strong></td>
							<td class='input port1' style="padding:3px;"><?php echo $miscellaneous1; ?></td>
							<td class='input port2' style="padding:3px;"><?php echo $miscellaneous2; ?></td>
							<td class='input port3' style="padding:3px;"><?php echo $miscellaneous3; ?></td>
						  </tr>
						  <tr>
							<td class="label" style="padding:3px;"><strong>Demurrage ($)</strong></td>
							<td colspan="3" class="label calculated" id='c66' style="padding:3px;"><strong><?php echo $c66; ?></strong></td>
						  </tr>
						  <tr>
							<td class="label" style="padding:3px;"><strong>Despatch ($)</strong></td>
							<td colspan="3" class="label calculated" id='c67' style="padding:3px;"><strong><?php echo $c67; ?></strong></td>
						  </tr>
						  <tr>
							<td class="label" style="padding:3px;"><strong>Total ($)</strong></td>
							<td colspan="3" class="label calculated" id='c68' style="padding:3px;"><?php echo $c68; ?></td>
						  </tr>
						</table>
					</div>
					<div style="float:left; width:490px; height:auto; padding-left:10px;">
						<table width="490" border="0" cellspacing="0" cellpadding="0">
						  <tr bgcolor="cddee5">
							<td class="text_1" colspan="8"><div style="padding:3px;"><b>CANAL</b></div></td>
						  </tr>
						  <tr bgcolor="f5f5f5">
							<td width="115" style="padding:3px;"><b>Canal</b></td>
							<td width="100" style="padding:3px;">&nbsp;</td>
							<td width="125" style="padding:3px;"><?php echo $canal; ?></td>
						  </tr>
						  <tr bgcolor="e9e9e9">
							<td style="padding:3px;"><b>Booking Fee ($)</b></td>
							<td class='empty' style="padding:3px;"><?php echo $cbook1; ?></td>
							<td class='empty' style="padding:3px;"><?php echo $cbook2; ?></td>
						  </tr>
						  <tr bgcolor="f5f5f5">
							<td style="padding:3px;"><b>Tugs ($)</b></td>
							<td class='empty' style="padding:3px;"><?php echo $ctug1; ?></td>
							<td class='empty' style="padding:3px;"><?php echo $ctug2; ?></td>
						  </tr>
						  <tr bgcolor="e9e9e9">
							<td style="padding:3px;"><b>Line Handlers ($)</b></td>
							<td class='empty' style="padding:3px;"><?php echo $cline1; ?></td>
							<td class='empty' style="padding:3px;"><span class="empty" style="padding:3px;"><?php echo $cline2; ?></span></td>
						  </tr>
						  <tr bgcolor="f5f5f5">
							<td style="padding:3px;"><b>Miscellaneous ($)</b></td>
							<td class='empty' style="padding:3px;"><?php echo $cmisc1; ?></td>
							<td class='empty' style="padding:3px;"><?php echo $cmisc2; ?></td>
						  </tr>
						  <tr bgcolor="e9e9e9">
							<td class="label" style="padding:3px;"><strong>Total ($)</strong></td>
							<td class="label calculated" id='ctotal1' style="padding:3px;"><?php echo $ctotal1; ?></td>
							<td class="label calculated" id='ctotal2' style="padding:3px;"><?php echo $ctotal2; ?></td>
						  </tr>
						</table>
					
						<div style="border-bottom:3px dotted #fff;">&nbsp;</div>
						<div>&nbsp;</div>
					
						<table width="490" height='460' border="0" cellspacing="0" cellpadding="0">
						  <tr>
							<td align="center">Map will not show on email</td>
						  </tr>
						  <tr>
							<td bgcolor="#000000"><iframe src='http://www.s-bisonline.com/app/map/map_voyage_estimator_2.php?imo=<?php echo $imo; ?>' id="map_iframeve" width='490' height='460' frameborder="0"></iframe></td>
						  </tr>
						</table>
					</div>
				</div>
				
				<div style="float:left; width:100%; height:auto; border-bottom:3px dotted #fff;">&nbsp;</div>
				<div style="float:left; width:100%; height:auto;">&nbsp;</div>
				
				<table width="" border="0" cellspacing="0" cellpadding="0">
				  <tr bgcolor="cddee5">
					<td width="148" class="text_1"><div style="padding:3px;"><b>VOYAGE DISBURSMENTS</b></div></td>
					<td width="124"></td>
					<td width="104"></td>
					<td width="104"></td>
					<td width="104" class="text_1"><div style="padding:3px;"><b>VOYAGE</b></div></td>
					<td width="104"></td>
					<td width="104"></td>
					<td width="104"></td>
					<td width="104"></td>
				  </tr>
				  <tr bgcolor="f5f5f5">
					<td class="label" style="padding:3px;"><strong>Bunker ($)</strong></td>
					<td class="label" style="padding:3px;"><strong>Port ($)</strong></td>
					<td class="label" style="padding:3px;"><strong>Canal($)</strong></td>
					<td class="label" style="padding:3px;"><strong>Add. Insurance ($)</strong></td>
					<td class="label" style="padding:3px;"><strong>ILOHC</strong></td>
					<td class="label" style="padding:3px;"><strong>ILOW</strong></td>
					<td class="label" style="padding:3px;"><strong>CVE</strong></td>
					<td class="label" style="padding:3px;"><strong>Ballast Bonus</strong></td>
					<td class="label" style="padding:3px;"><strong>Miscellaneous</strong></td>
				  </tr>
				  <tr bgcolor="e9e9e9">
					<td class="calculated" id='b74' style="padding:3px;"><?php echo $b74; ?></td>
					<td class="calculated" id='c74' style="padding:3px;"><strong><?php echo $c74; ?></strong></td>
					<td class="calculated" id='d74' style="padding:3px;"><strong><?php echo $d74; ?></strong></td>
					<td class='input' style="padding:3px;"><?php echo $e74; ?></td>
					<td class='input' style="padding:3px;"><?php echo $f74; ?></td>
					<td class='input' style="padding:3px;"><?php echo $g74; ?></td>
					<td class='input' style="padding:3px;"><?php echo $h74; ?></td>
					<td class='input' style="padding:3px;"><?php echo $i74; ?></td>
					<td class='input' style="padding:3px;"><?php echo $j74; ?></td>
				  </tr>
				  <tr>
					<td colspan="9" class="label calculated" id='b75' style="padding:3px;"><?php echo $b75; ?></td>
				  </tr>
				</table>
			</td>
			<td width="300" valign="top">
				<table width="300" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td width="150" style="border:none;">
						<div style="padding-left:10px;">
							<table width="140" border="0" cellspacing="0" cellpadding="0">
								<tr bgcolor="cddee5">
									<td class="text_1"><div style="padding:3px;"><b>FREIGHT RATE</b></div></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td height="5"></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td class="label" style="padding:3px;"><strong>Freight Rate ($/MT)</strong></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td class='empty' style="padding:3px;"><?php echo $b80; ?></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td height="5"></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td class="label" style="padding:3px;"><strong>Gross Freight ($)</strong></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td class="calculated" id='c80' style="padding:3px;"><?php echo $c80; ?></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td height="5"></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td class="label" style="padding:3px;"><strong>Brok. Comm ($)</strong></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td style="padding:3px;"><?php echo $b80; ?></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td height="5"></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td class="label" style="padding:3px;"><strong>Add. Comm ($)</strong></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td style="padding:3px;"><?php echo $e80; ?></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td height="5"></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td class="label" style="padding:3px;"><strong>Gross Income ($)</strong></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td class="calculated" id='f80' style="padding:3px;"><?php echo $f80; ?></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td height="5"></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td class="label" style="padding:3px;"><strong>TCE ($/day)</strong></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td class="calculated" id='g80' style="padding:3px;"><?php echo $g80; ?></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td height="5"></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td class="label" style="padding:3px;"><strong>Total</strong></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td class="label calculated" id='d81' style="padding:3px;"><?php echo $d81; ?></td>
								</tr>
							</table>
						</div>
					</td>
					<td width="150" style="border:none;">
						<div style="padding-left:10px;">
							<table width="140" border="0" cellspacing="0" cellpadding="0">
								<tr bgcolor="cddee5">
									<td class="text_1"><div style="padding:3px;"><b>TCE</b></div></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td height="5"></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td style="padding:3px;"><strong>Freight Rate ($/MT)</strong></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td class="calculated" id='b85' style="padding:3px;"><?php echo $b85; ?></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td height="5"></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td class="label" style="padding:3px;"><strong>Gross Freight ($)</strong></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td class="calculated"  id='c85' style="padding:3px;"><?php echo $c85; ?></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td height="5"></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td class="label" style="padding:3px;"><strong>Brok. Comm ($)</strong></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td style="padding:3px;"><?php echo $d85; ?></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td height="5"></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td class="label" style="padding:3px;"><strong>Add. Comm ($)</strong></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td style="padding:3px;"><?php echo $e85; ?></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td height="5"></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td class="label" style="padding:3px;"><strong>Gross Income ($)</strong></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td class="calculated"  id='f85' style="padding:3px;"><?php echo $f85; ?></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td height="5"></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td class="label" style="padding:3px;"><strong>TCE ($/day)</strong></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td class='empty' style="padding:3px;"><?php echo $g85; ?></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td height="5"></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td class="label" style="padding:3px;"><strong>Total</strong></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td class="label calculated"  id='d86' style="padding:3px;"><?php echo $d86; ?></td>
								</tr>
							</table>
						</div>
					</td>
				  </tr>
				</table>
			</td>
		  </tr>
		</table>
		<?php
		
		echo "</th>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
</table>
<table cellpadding='0' cellspacing='0' width='1300'>
	<tr>
		<td style='border:0px; text-align:right;'>Powered by <img src='http://".$_SERVER['HTTP_HOST']."/app/images/logo_ve2.png' width='50'></td>
	</tr>
</table>
</div>";

$message = ob_get_contents();
@ob_end_clean();


if(!$_POST['email']){
	?>
	<style>
	*{
		font-size:11px;
		font-family:Verdana, Arial, Helvetica, sans-serif;
	}
	</style>	
	<center>
	<form method='post'>
	Please enter the Email(s) you want to send to:<br>
	<textarea name='email' style='width:400px; height:200px;'></textarea>
	<br>
	(New line separated for multiple emails)
	<br>
	<input type='submit' value='Send Email'>
	</form>
	</center>
	<?php
	echo $message;
	exit();
}


$from = "tools@cargospotter.no";
$fromname = "CargoSpotter Mailer";
$bouncereturn = "tools@cargospotter.no"; //where the email will forward in cases of bounced email
$subject = "Voyage Estimation";
$emailsp = explode("\n",$_POST['email']);
$emails = array();
$t = count($emailsp);
for($i=0; $i<$t; $i++){
	$email = array();
	$email['email'] = trim($emailsp[$i]);
	$email['name'] = trim($emailsp[$i]);
	$emails[] = $email;
}
$r = emailBlast($from, $fromname, $subject, $message, $emails, $bouncereturn, 0); //last parameter for running debug
?>
<style>
*{
	font-size:11px;
	font-family:Verdana, Arial, Helvetica, sans-serif;
}
</style>	
<center>
<?php
if($r||1){
	echo "Email Sent!";
}
else{
	
}
?>
</center>