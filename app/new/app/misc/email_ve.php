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

echo "<div class='landScape'>
<table width='1300' border='0' cellpadding='0' cellspacing='0'>
	<tr>
		<td style='border:0px;' width='460'><img src='http://".$_SERVER['HTTP_HOST']."/app/images/logo_cargospotter1.png'></td>
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
					<td class="text_1"><div style="padding:3px;"><b>VESSEL NAME / IMO #</b> &nbsp; xxxx &nbsp; <span id='shipdetailshref' style="color:#F00;"></span></div></td>
				  </tr>
				</table>
				<div id="ship_info" style="display:none;">
					<table width="1000" border="0" cellspacing="0" cellpadding="0">
					  <tr bgcolor="f5f5f5">
						<td width="110" valign="top"><div style="padding:3px;"><b>IMO</b> #</div></td>
						<td width="160" valign="top" style="padding:3px;" id="ship_imo"></td>
						<td width="105" valign="top"><div style="padding:3px;"><b>LOA</b></div></td>
						<td width="100" valign="top" style="padding:3px;" id="ship_loa"></td>
						<td width="145" valign="top"><div style="padding:3px;"><b>Grain</b></div></td>
						<td width="160" valign="top" style="padding:3px;" id="ship_grain"></td>
						<td width="120" valign="top"><div style="padding:3px;"><b>Class Notation</b></div></td>
						<td width="100" valign="top" style="padding:3px;" id="ship_class_notation"></td>
					  </tr>
					  <tr bgcolor="e9e9e9">
						<td valign="top"><div style="padding:3px;"><b>Summer DWT</b></div></td>
						<td valign="top" style="padding:3px;" id="ship_summer_dwt"></td>
						<td valign="top"><div style="padding:3px;"><b>Draught</b></div></td>
						<td valign="top" style="padding:3px;" id="ship_draught"></td>
						<td valign="top"><div style="padding:3px;"><b>Lifting Equipment</b></div></td>
						<td valign="top" style="padding:3px;" id="ship_lifting_equipment"></td>
						<td valign="top"><div style="padding:3px;"><b>Fuel Oil</b></div></td>
						<td valign="top" style="padding:3px;" id="ship_fuel_oil"></td>
					  </tr>
					  <tr bgcolor="f5f5f5">
						<td valign="top"><div style="padding:3px;"><b>Gross Tonnage</b></div></td>
						<td valign="top" style="padding:3px;" id="ship_gross_tonnage"></td>
						<td valign="top"><div style="padding:3px;"><b>Speed</b></div></td>
						<td valign="top" style="padding:3px;" id="ship_speed"></td>
						<td valign="top"><div style="padding:3px;"><b>Cargo Handling</b></div></td>
						<td valign="top" style="padding:3px;" id="ship_cargo_handling"></td>
						<td valign="top"><div style="padding:3px;"><b>Fuel</b></div></td>
						<td valign="top" style="padding:3px;" id="ship_fuel"></td>
					  </tr>
					  <tr bgcolor="e9e9e9">
						<td valign="top"><div style="padding:3px;"><b>Built Year</b></div></td>
						<td valign="top" style="padding:3px;" id="ship_built_year"></td>
						<td valign="top"><div style="padding:3px;"><b>Breadth</b></div></td>
						<td valign="top" style="padding:3px;" id="ship_breadth"></td>
						<td valign="top"><div style="padding:3px;"><b>Decks Number</b></div></td>
						<td valign="top" style="padding:3px;" id="ship_decks_number"></td>
						<td valign="top"><div style="padding:3px;"><b>Fuel Consumption</b></div></td>
						<td valign="top" style="padding:3px;" id="ship_fuel_consumption"></td>
					  </tr>
					  <tr bgcolor="f5f5f5">
						<td valign="top"><div style="padding:3px;"><b>Bale</b></div></td>
						<td valign="top" style="padding:3px;" id="ship_bale"></td>
						<td valign="top"><div style="padding:3px;"><b>Cranes</b></div></td>
						<td valign="top" style="padding:3px;" id="ship_cranes"></td>
						<td valign="top"><div style="padding:3px;"><b>Bulkheads</b></div></td>
						<td valign="top" style="padding:3px;" id="ship_bulkheads"></td>
						<td valign="top"><div style="padding:3px;"><b>Fuel Type</b></div></td>
						<td valign="top" style="padding:3px;" id="ship_fuel_type"></td>
					  </tr>
					  <tr bgcolor="e9e9e9">
						<td valign="top"><div style="padding:3px;"><b>Manager Owner</b></div></td>
						<td valign="top" style="padding:3px;" id="ship_manager_owner"></td>
						<td valign="top"><div style="padding:3px;"><b>Manager Owner Email</b></div></td>
						<td valign="top" style="padding:3px;" id="ship_manager_owner_email"></td>
						<td valign="top"><div style="padding:3px;"><b>Class Society</b></div></td>
						<td valign="top" style="padding:3px;" id="ship_class_society"></td>
						<td valign="top"><div style="padding:3px;"><b>Largest Hatch</b></div></td>
						<td valign="top" style="padding:3px;" id="ship_largest_hatch"></td>
					  </tr>
					  <tr bgcolor="f5f5f5">
						<td valign="top"><div style="padding:3px;"><b>Holds</b></div></td>
						<td valign="top" style="padding:3px;" id="ship_holds"></td>
						<td valign="top"><div style="padding:3px;"><b>Flag</b></div></td>
						<td valign="top" style="padding:3px;" id="ship_flag"></td>
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
					<td class='input'><div style="padding:3px;"><input type='text' class='input_1 general c31' id="c31" name="c31" value="<?php echo $c31; ?>" style="max-width:190px;" /></div></td>
					<td class="input"><div style="padding:3px;"><input type='text' class='input_1 general d31' name="d31" value="<?php echo $d31; ?>" style="max-width:170px;" /></div></td>
					<td class='input'><div style="padding:3px;"><input type='text' class='input_1 general e31' name="e31" value="<?php echo $e31; ?>" style="max-width:190px;" /></div></td>
					<td class='calculated general f31' style="padding:3px;"></td>
					<td class='input'><div style="padding:3px;"><input type='text' class='input_1 number g31' name="g31" value="<?php echo $g31; ?>" style="max-width:90px;" /></div></td>
					<td class="calculated number h31" style="padding:3px;"></td>
				  </tr>
				  <tr id='loading1' bgcolor="e9e9e9">
					<td class='general b32' style="padding:3px;"><strong>Loading</strong></td>
					<td class='general c32' style="padding:3px;"></td>
					<td class='general d32' style="padding:3px;"></td>
					<td class='general e32' style="padding:3px;"></td>
					<td class="calculated f32" style="padding:3px;"></td>
					<td class='number g32' style="padding:3px;"></td>
					<td class="number h32" style="padding:3px;"></td>
				  </tr>
				  <tr id='bunkerstop1' bgcolor="f5f5f5">
					<td class='general b33' style="padding:3px;"><strong>Bunker Stop</strong></td>
					<td class='input general c33' style="padding:3px;"></td>
					<td class='general d33' style="padding:3px;"></td>
					<td class='input' style="padding:3px;"><input type='text' class='input_1 general e33' name="e33" value="<?php echo $e33; ?>"  style="max-width:190px;" /></td>
					<td class="calculated f33" style="padding:3px;"></td>
					<td class='input' style="padding:3px;"><input type='text' class='input_1 number g33' name="g33" value="<?php echo $g33; ?>"  style="max-width:90px;" /></td>
					<td class="calculated h33" style="padding:3px;"></td>
				  </tr>
				  <tr id='laden1' bgcolor="e9e9e9">
					<td class='general b34' style="padding:3px;"><strong>Laden</strong></td>
					<td class='input general c34' style="padding:3px;"></td>
					<td class='general d34' style="padding:3px;"></td>
					<td class='input' style="padding:3px;"><input type='text' class='input_1 general e34' name="e34" value="<?php echo $e34; ?>" style="max-width:190px;" /></td>
					<td class="calculated f34" style="padding:3px;"></td>
					<td class='input' style="padding:3px;"><input type='text' class='input_1 number g34' name="g34" value="<?php echo $g34; ?>" style="max-width:90px;" /></td>
					<td class="calculated number h34" style="padding:3px;"></td>
				  </tr>
				  <tr id='discharging1' bgcolor="f5f5f5">
					<td class='general b35' style="padding:3px;"><strong>Discharging</strong></td>
					<td class='input general c35' style="padding:3px;"></td>
					<td class='general d35' style="padding:3px;"></td>
					<td class='general e35' style="padding:3px;"></td>
					<td class="calculated f35" style="padding:3px;"></td>
					<td class='number g35' style="padding:3px;"></td>
					<td class="number h35" style="padding:3px;"></td>
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
					<td class="calculated number r31" style="padding:3px;"></td>
					<td class='empty' style="padding:3px;"><input type='text' class='input_1 number s31' name="s31" value="<?php echo $s31; ?>" style="max-width:50px;" /></td>
					<td class='empty' style="padding:3px;"><input type='text' class='input_1 number t31' name="t31" value="<?php echo $t31; ?>" style="max-width:50px;" /></td>
				  </tr>
				  <tr id='loading1' bgcolor="e9e9e9">
					<td class='general b32' style="padding:3px;"><strong>Loading</strong></td>
					<td class='input' style="padding:3px;"><input type='text' class='input_1 general i32' name="i32" value="<?php echo $i32; ?>" style="max-width:140px;" /></td>
					<td class='number j32' style="padding:3px;"></td>
					<td class='input' style="padding:3px;"><input type='text' class='input_1 number k32' name="k32" value="<?php echo $k32; ?>" style="max-width:70px;" /></td>
					<td class='calculated number l32' style="padding:3px;"></td>
					<td class='input' style="padding:3px;"><input type='text' class='input_1 number m32' name="m32" value="<?php echo $m32; ?>" style="max-width:70px;" /></td>
					<td class='input' style="padding:3px;">
						<?php
						$n32arr = array(
									1=>"SHINC", 
									2=>"SATSHINC or SSHINC", 
									3=>"SHEX", 
									4=>"SA/SHEX or SATPMSHEX", 
									5=>"SHEXEIU or SHEXEIUBE or SHEXUU", 
									6=>"FHINC", 
									7=>"FHEX"
								);
								
						$n32t = count($n32arr);
						?>
						<select class='input_1 general n32' name="n32" style="max-width:100px; min-width:100px;">
							<?php
							for($n32i=1; $n32i<=$n32t; $n32i++){
								if($n32arr[$n32i]==$n32){
									echo '<option value="'.$n32arr[$n32i].'" selected="selected">'.$n32arr[$n32i].'</option>';
								}else{
									echo '<option value="'.$n32arr[$n32i].'">'.$n32arr[$n32i].'</option>';
								}
							}
							?>
						</select>
					</td>
					<td class="calculated number o32" style="padding:3px;"></td>
					<td class='input' style="padding:3px;"><input type='text' class='input_1 number p32' name="p32" value="<?php echo $p32; ?>" style="max-width:70px;" /></td>
					<td class='input' style="padding:3px;"><input type='text' class='input_1 number q32' name="q32" value="<?php echo $q32; ?>" style="max-width:70px;" /></td>
					<td class="number r32" style="padding:3px;"></td>
					<td class='empty' style="padding:3px;"><input type='text' class='input_1 number s32' name="s32" value="<?php echo $s32; ?>" style="max-width:50px;" /></td>
					<td class='empty' style="padding:3px;"><input type='text' class='input_1 number t32' name="t32" value="<?php echo $t32; ?>" style="max-width:50px;" /></td>
				  </tr>
				  <tr id='bunkerstop1' bgcolor="f5f5f5">
					<td class='general b33' style="padding:3px;"><strong>Bunker Stop</strong></td>
					<td class='number i33' style="padding:3px;"></td>
					<td class='number j33' style="padding:3px;"></td>
					<td class='number k33' style="padding:3px;"></td>
					<td class='input' style="padding:3px;"><input type='text' class='input_1 number l33' name="l33" value="<?php echo $l33; ?>" style="max-width:70px;"  /></td>
					<td class='input' style="padding:3px;"><input type='text' class='input_1 number m33' name="m33" value="<?php echo $m33; ?>" style="max-width:70px;" /></td>
					<td class='input' style="padding:3px;">
						<?php
						$n33arr = array(
									1=>"SHINC", 
									2=>"SATSHINC or SSHINC", 
									3=>"SHEX", 
									4=>"SA/SHEX or SATPMSHEX", 
									5=>"SHEXEIU or SHEXEIUBE or SHEXUU", 
									6=>"FHINC", 
									7=>"FHEX"
								);
								
						$n33t = count($n33arr);
						?>
						<select class='input_1 general n33' name="n33" style="max-width:100px; min-width:100px;">
							<?php
							for($n33i=1; $n33i<=$n33t; $n33i++){
								if($n33arr[$n33i]==$n33){
									echo '<option value="'.$n33arr[$n33i].'" selected="selected">'.$n33arr[$n33i].'</option>';
								}else{
									echo '<option value="'.$n33arr[$n33i].'">'.$n33arr[$n33i].'</option>';
								}
							}
							?>
						</select>
					</td>
					<td class="calculated o33" style="padding:3px;"></td>
					<td class='input' style="padding:3px;"><input type='text' class='input_1 number p33' name="p33" value="<?php echo $p33; ?>" style="max-width:70px;" /></td>
					<td class='input' style="padding:3px;"><input type='text'  class='input_1 number q33' name="q33" value="<?php echo $q33; ?>" style="max-width:70px;"  /></td>
					<td class="calculated number r33" style="padding:3px;"></td>
					<td class='empty' style="padding:3px;"><input type='text'  class='input_1 number s33' name="s33" value="<?php echo $s33; ?>" style="max-width:50px;" /></td>
					<td class='empty' style="padding:3px;"><input type='text'  class='input_1 number t33' name="t33" value="<?php echo $t33; ?>" style="max-width:50px;" /></td>
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
					<td class="calculated number r34" style="padding:3px;"></td>
					<td class='empty' style="padding:3px;"><input type='text' class='input_1 number s34' name="s34" value="<?php echo $s34; ?>" style="max-width:50px;" /></td>
					<td class='empty' style="padding:3px;"><input type='text' class='input_1 number t34' name="t34" value="<?php echo $t34; ?>" style="max-width:50px;" /></td>
				  </tr>
				  <tr id='discharging1' bgcolor="f5f5f5">
					<td class='general b35' style="padding:3px;"><strong>Discharging</strong></td>
					<td class='input' style="padding:3px;"><input type='text' class='input_1 general i35' name="i35" value="<?php echo $i35; ?>" style="max-width:140px;" /></td>
					<td class='number j35' style="padding:3px;"></td>
					<td class='input' style="padding:3px;"><input type='text' class='input_1 number k35' name="k35" value="<?php echo $k35; ?>" style="max-width:70px;" /></td>
					<td class='calculated number l35' style="padding:3px;"></td>
					<td class='input' style="padding:3px;"><input type='text'  class='input_1 number m35' name="m35" value="<?php echo $m35; ?>" style="max-width:70px;" /></td>
					<td class='input' style="padding:3px;">
						<?php
						$n35arr = array(
									1=>"SHINC", 
									2=>"SATSHINC or SSHINC", 
									3=>"SHEX", 
									4=>"SA/SHEX or SATPMSHEX", 
									5=>"SHEXEIU or SHEXEIUBE or SHEXUU", 
									6=>"FHINC", 
									7=>"FHEX"
								);
								
						$n35t = count($n35arr);
						?>
						<select class='input_1 general n35' name="n35" style="max-width:100px; min-width:100px;">
							<?php
							for($n35i=1; $n35i<=$n35t; $n35i++){
								if($n35arr[$n35i]==$n35){
									echo '<option value="'.$n35arr[$n35i].'" selected="selected">'.$n35arr[$n35i].'</option>';
								}else{
									echo '<option value="'.$n35arr[$n35i].'">'.$n35arr[$n35i].'</option>';
								}
							}
							?>
						</select>
					</td>
					<td class="calculated number o35" style="padding:3px;"></td>
					<td class='input' style="padding:3px;"><input type='text' class='input_1 number p35' name="p35" value="<?php echo $p35; ?>" style="max-width:70px;" /></td>
					<td class='input' style="padding:3px;"><input type='text'  class='input_1 number q35' name="q35" value="<?php echo $q35; ?>" style="max-width:70px;" /></td>
					<td class="number r35" style="padding:3px;"></td>
					<td class='empty' style="padding:3px;"><input type='text' class='input_1 number s35' name="s35" value="<?php echo $s35; ?>" style="max-width:50px;" /></td>
					<td class='empty' style="padding:3px;"><input type='text'  class='input_1 number t35' name="t35" value="<?php echo $t35; ?>" style="max-width:50px;" /></td>
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
					<td colspan="3" class="label calculated" id='o36' style="padding:3px;">&nbsp;</td>
					<td colspan="3" class="label calculated" id='r36' style="padding:3px;">&nbsp;</td>
				  </tr>
				  <tr>
					<td colspan="7" class="label" style="padding:3px;"><strong>TOTAL VOYAGE DAYS</strong></td>
					<td colspan="6" class="label calculated" id='o37' style="padding:3px;">&nbsp;</td>
				  </tr>
				</table>
				
				<div style="border-bottom:3px dotted #fff;">&nbsp;</div>
				<div>&nbsp;</div>
				
				<table width="1000" border="0" cellspacing="0" cellpadding="0">
				  <tr bgcolor="cddee5">
					<td class="text_1" colspan="8"><div style="padding:3px;"><b>BUNKER PRICING - Data from Bunkerworld</b></div></td>
				  </tr>
				  <tr bgcolor="f5f5f5">
					<td width="100" style="padding:3px;"><b>FO Type</b></td>
					<td width="450" colspan="3" style="padding:3px;"></td>
					<td width="200" style="padding:3px;"><b>DO Type</b></td>
					<td width="250" colspan="3" style="padding:3px;"></td>
				  </tr>
				  <tr bgcolor="e9e9e9">
					<td style="padding:3px;"><b>FO Price ($)</b></td>
					<td colspan="3" class="input" style="padding:3px;"><input type='text'  id='d42' name="d42" value="<?php echo $d42; ?>" class='input_1 number' style="max-width:150px;" /></td>
					<td style="padding:3px;"><b>DO Price ($)</b></td>
					<td colspan="3" class="input" style="padding:3px;"><input type='text'  id='h42' name="h42" value="<?php echo $h42; ?>" class='input_1 number' style="max-width:150px;" /></td>
				  </tr>
				  <tr>
					<td class="text_1 label" style="padding:3px;"><b><i>&nbsp;</i></b></td>
					<td class="text_1 label" style="padding:3px;"><b><i>FO/Ballast</i></b></td>
					<td class="text_1 label" style="padding:3px;"><b><i>FO/Laden</i></b></td>
					<td class="text_1 label" style="padding:3px;"><b><i>FO/Port</i></b></td>
					<td class="text_1 label" style="padding:3px;"><b><i>FO/Reserve</i></b></td>
					<td class="text_1 label" style="padding:3px;"><b><i>DO/Sea</i></b></td>
					<td class="text_1 label" style="padding:3px;"><b><i>DO/Port</i></b></td>
					<td class="text_1 label" style="padding:3px;" colspan="2"><b><i>DO/Reserve</i></b></td>
				  </tr>
				  <tr bgcolor="f5f5f5">
					<td style="padding:3px;"><b>Consumption (MT/day)</b></td>
					<td class='input' style="padding:3px;"><input type='text'  id='c44' name="c44" value="<?php echo $c44; ?>" class='input_1 number' style="max-width:100px;" /></td>
					<td class='input' style="padding:3px;"><input type='text'  id='d44' name="d44" value="<?php echo $d44; ?>" class='input_1 number' style="max-width:100px;" /></td>
					<td class='input' style="padding:3px;"><input type='text'  id='e44' name="e44" value="<?php echo $e44; ?>" class='input_1 number' style="max-width:100px;" /></td>
					<td class='input number' id='f44' style="padding:3px;"></td>
					<td class='input' style="padding:3px;"><input type='text'  id='g44' name="g44" value="<?php echo $g44; ?>" class='input_1 number' style="max-width:70px;" /></td>
					<td class='input' style="padding:3px;"><input type='text'  id='h44' name="h44" value="<?php echo $h44; ?>" class='input_1 number' style="max-width:70px;" /></td>
					<td class='general' id='i44' style="padding:3px;"></td>
				  </tr>
				  <tr>
					<td class="label" style="padding:3px;"><strong>Total Consumption (MT)</strong></td>
					<td class="label calculated" id='c45' style="padding:3px;"></td>
					<td class="label calculated" id='d45' style="padding:3px;"></td>
					<td class="label calculated" id='e45' style="padding:3px;"></td>
					<td class='label input' style="padding:3px;"><input type='text' id='f45' name="f45" value="<?php echo $f45; ?>" class='input_1 number' style="max-width:100px;" /></td>
					<td class="label calculated" id='g45' style="padding:3px;"></td>
					<td class="label calculated" id='h45' style="padding:3px;"></td>
					<td class='label input' style="padding:3px;"><input type='text' id='i45' name="i45" value="<?php echo $i45; ?>" class='input_1 number' style="max-width:70px;" /></td>
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
					<td class="label calculated" id='c46' style="padding:3px;">&nbsp;</td>
					<td class="label calculated" id='d46' style="padding:3px;">&nbsp;</td>
					<td class="label calculated" id='e46' style="padding:3px;">&nbsp;</td>
					<td class="label calculated" id='f46' style="padding:3px;">&nbsp;</td>
					<td class="label calculated" id='g46' style="padding:3px;">&nbsp;</td>
					<td class="label calculated" id='h46' style="padding:3px;">&nbsp;</td>
					<td class="label calculated" id='i46' style="padding:3px;">&nbsp;</td>
				  </tr>
				  <tr>
					<td class="label" style="padding:3px;"><strong>Total ($)</strong></td>
					<td colspan="4" class="label calculated" id='c47' style="padding:3px;">&nbsp;</td>
					<td colspan="4" class="label calculated" id='g47' style="padding:3px;">&nbsp;</td>
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
							<td width="105" class='calculated number' id='d18' style="padding:3px;"></td>
							<td width="180" style="padding:3px;"><strong>Calculated Amount  </strong></td>
						  </tr>
						  <tr bgcolor="e9e9e9">
							<td width="100" height="34" rowspan="2" style="padding:3px;"><b>Consumption (MT)</b></td>
							<td width="30" style="padding:3px;"><b>FO</b></td>
							<td height="12" class='input' style="padding:3px;"><input type='text' class='input_1 number' id='d19' name="d19" value="<?php echo $d19; ?>" style="max-width:100px;" /></td>
							<td class='calculated general' id='d19b' style="padding:3px;"></td>
						  </tr>
						  <tr bgcolor="f5f5f5">
							<td style="padding:3px;"><b>DO</b></td>
							<td class='input' style="padding:3px;"><input type='text' class='input_1 number' id='d20' name="d20" value="<?php echo $d20; ?>" style="max-width:100px;" /></td>
							<td class='calculated general' id='d20b' style="padding:3px;"></td>
						  </tr>
						  <tr bgcolor="e9e9e9">
							<td rowspan="2" style="padding:3px;"><b>Reserve (MT)</b></td>
							<td style="padding:3px;"><b>FO</b></td>
							<td class='input' style="padding:3px;"><input type='text' class='input_1 number' id='d21' name="d21" value="<?php echo $d21; ?>" style="max-width:100px;" /></td>
							<td class='calculated general' id='d21b' style="padding:3px;"></td>
						  </tr>
						  <tr bgcolor="f5f5f5">
							<td style="padding:3px;"><b>DO</b></td>
							<td class='input' style="padding:3px;"><input type='text' class='input_1 number' id='d22' name="d22" value="<?php echo $d22; ?>" style="max-width:100px;" /></td>
							<td class='calculated general' id='d22b' style="padding:3px;"></td>
						  </tr>
						  <tr bgcolor="e9e9e9">
							<td height="17" colspan="2" style="padding:3px;"><b>FW (MT)</b></td>
							<td class='input' style="padding:3px;"><input type='text' class='input_1 number' id='d23' name="d23" value="<?php echo $d23; ?>" style="max-width:100px;" /></td>
							<td class='calculated general' id='d23b' style="padding:3px;"></td>
						  </tr>
						  <tr bgcolor="f5f5f5">
							<td height="18" colspan="2" style="padding:3px;"><b>Constant (MT)</b></td>
							<td class='input' style="padding:3px;"><input type='text' class='input_1 number' id='d24' name="d24" value="<?php echo $d24; ?>" style="max-width:100px;" /></td>
							<td class='calculated general' id='d24b' style="padding:3px;"></td>
						  </tr>
						  <tr>
							<td colspan="2" class="label" style="padding:3px;"><strong>Used DW (MT)</strong></td>
							<td colspan="2" class='label calculated number' id='d25' style="padding:3px;"></td>
						  </tr>
						  <tr>
							<td colspan="2" class="label" style="padding:3px;"><strong>DWCC (MT)</strong></td>
							<td colspan="2" class='label calculated number' id='d26' style="padding:3px;"></td>
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
							<td width="122" class='input' style="padding:3px;"><input type='text' id='c51' name="c51" value="<?php echo $c51; ?>" class='input_1 number' style="max-width:100px;" /></td>
							<td width="123" style="padding:3px;"></td>
							<td width="123" style="padding:3px;"></td>
						  </tr>
						  <tr bgcolor="e9e9e9">
							<td style="padding:3px;"><strong>Dem ($/day)</strong></td>
							<td class='input' style="padding:3px;"><input type='text' id='c52' name="c52" value="<?php echo $c52; ?>" class='input_1 number' style="max-width:100px;" /></td>
							<td style="padding:3px;"><strong>Pro rated</strong></td>
							<td style="padding:3px;"></td>
						  </tr>
						  <tr bgcolor="f5f5f5">
							<td style="padding:3px;"><strong>Term</strong></td>
							<td style="padding:3px;">
								<?php
								$termarr = array(
											1=>"DHDLTSBENDS", 
											2=>"DHDATSBENDS", 
											3=>"DHDWTSBENDS"
										);
										
								$termt = count($termarr);
								?>
								<select id='term' name="term" class="input_1" style="max-width:100px;">
									<?php
									for($termi=1; $termi<=$termt; $termi++){
										if($termarr[$termi]==$term){
											echo '<option value="'.$termarr[$termi].'" selected="selected">'.$termarr[$termi].'</option>';
										}else{
											echo '<option value="'.$termarr[$termi].'">'.$termarr[$termi].'</option>';
										}
									}
									?>
								</select>
							</td>
							<td style="padding:3px;"></td>
							<td style="padding:3px;"></td>
						  </tr>
						  <tr bgcolor="e9e9e9">
							<td style="padding:3px;"><strong>Des ($/day)</strong></td>
							<td class="calculated" id='c54' style="padding:3px;">&nbsp;</td>
							<td style="padding:3px;"></td>
							<td style="padding:3px;"></td>
						  </tr>
						  <tr bgcolor="f5f5f5">
							<td style="padding:3px;"><strong>Liner Terms</strong></td>
							<td style="padding:3px;">
								<?php
								$linertermsarr = array(
											1=>"FILO", 
											2=>"FILTD", 
											3=>"FIOLS",
											4=>"FIOSLSD",
											5=>"FIOSPT",
											6=>"FIOST",
											7=>"LIFO",
											8=>"BTBT"
										);
										
								$linertermst = count($linertermsarr);
								?>
								<select id='linerterms' name="linerterms" class="input_1" style="max-width:100px;">
									<?php
									for($linertermsi=1; $linertermsi<=$linertermst; $linertermsi++){
										if($linertermsarr[$linertermsi]==$linerterms){
											echo '<option value="'.$linertermsarr[$linertermsi].'" selected="selected">'.$linertermsarr[$linertermsi].'</option>';
										}else{
											echo '<option value="'.$linertermsarr[$linertermsi].'">'.$linertermsarr[$linertermsi].'</option>';
										}
									}
									?>
								</select>
							</td>
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
							<td class='input port1' style="padding:3px;"><input type='text' class='input_1 number dues' name="dues1" value="<?php echo $dues1; ?>" style="max-width:100px;" /></td>
							<td class='input port2' style="padding:3px;"><input type='text' class='input_1 number dues' name="dues2" value="<?php echo $dues2; ?>" style="max-width:100px;" /></td>
							<td class='input port3' style="padding:3px;"><input type='text' class='input_1 number dues' name="dues3" value="<?php echo $dues3; ?>" style="max-width:100px;" /></td>
						  </tr>
						  <tr bgcolor="e9e9e9">
							<td style="padding:3px;"><strong>Pilotage ($)</strong></td>
							<td class='input port1' style="padding:3px;"><input type='text' class='input_1 number pilotage' name="pilotage1" value="<?php echo $pilotage1; ?>" style="max-width:100px;" /></td>
							<td class='input port2' style="padding:3px;"><input type='text' class='input_1 number pilotage' name="pilotage2" value="<?php echo $pilotage2; ?>" style="max-width:100px;" /></td>
							<td class='input port3' style="padding:3px;"><input type='text' class='input_1 number pilotage' name="pilotage3" value="<?php echo $pilotage3; ?>" style="max-width:100px;" /></td>
						  </tr>
						  <tr bgcolor="f5f5f5">
							<td style="padding:3px;"><strong>Tugs ($)</strong></td>
							<td class='input port1' style="padding:3px;"><input type='text' class='input_1 number tugs' name="tugs1" value="<?php echo $tugs1; ?>" style="max-width:100px;" /></td>
							<td class='input port2' style="padding:3px;"><input type='text' class='input_1 number tugs' name="tugs2" value="<?php echo $tugs2; ?>" style="max-width:100px;" /></td>
							<td class='input port3' style="padding:3px;"><input type='text' class='input_1 number tugs' name="tugs3" value="<?php echo $tugs3; ?>" style="max-width:100px;" /></td>
						  </tr>
						  <tr bgcolor="e9e9e9">
							<td style="padding:3px;"><strong>Bunker Adjustment ($)</strong></td>
							<td class='input port1' style="padding:3px;"><input type='text' class='input_1 number bunkeradjustment' name="bunkeradjustment1" value="<?php echo $bunkeradjustment1; ?>" style="max-width:100px;" /></td>
							<td class='input port2' style="padding:3px;"><input type='text' class='input_1 number bunkeradjustment' name="bunkeradjustment2" value="<?php echo $bunkeradjustment2; ?>" style="max-width:100px;" /></td>
							<td class='input port3' style="padding:3px;"><input type='text' class='input_1 number bunkeradjustment' name="bunkeradjustment3" value="<?php echo $bunkeradjustment3; ?>" style="max-width:100px;" /></td>
						  </tr>
						  <tr bgcolor="f5f5f5">
							<td style="padding:3px;"><strong>Mooring ($)</strong></td>
							<td class='input port1' style="padding:3px;"><input type='text' class='input_1 number mooring' name="mooring1" value="<?php echo $mooring1; ?>" style="max-width:100px;" /></td>
							<td class='input port2' style="padding:3px;"><input type='text' class='input_1 number mooring' name="mooring2" value="<?php echo $mooring2; ?>" style="max-width:100px;" /></td>
							<td class='input port3' style="padding:3px;"><input type='text' class='input_1 number mooring' name="mooring3" value="<?php echo $mooring3; ?>" style="max-width:100px;" /></td>
						  </tr>
						  <tr bgcolor="e9e9e9">
							<td style="padding:3px;"><strong>Dockage ($)</strong></td>
							<td class='input port1' style="padding:3px;"><input type='text' class='input_1 number dockage' name="dockage1" value="<?php echo $dockage1; ?>" style="max-width:100px;" /></td>
							<td class='input port2' style="padding:3px;"><input type='text' class='input_1 number dockage' name="dockage2" value="<?php echo $dockage2; ?>" style="max-width:100px;" /></td>
							<td class='input port3' style="padding:3px;"><input type='text' class='input_1 number dockage' name="dockage3" value="<?php echo $dockage3; ?>" style="max-width:100px;" /></td>
						  </tr>
						  <tr bgcolor="f5f5f5">
							<td style="padding:3px;"><strong>Load/Discharge ($)</strong></td>
							<td class='input port1' style="padding:3px;"><input type='text' class='input_1 number loaddischarge' name="loaddischarge1" value="<?php echo $loaddischarge1; ?>" style="max-width:100px;" /></td>
							<td class='input port2' style="padding:3px;"><input type='text' class='input_1 number loaddischarge' name="loaddischarge2" value="<?php echo $loaddischarge2; ?>" style="max-width:100px;" /></td>
							<td height="12" class='input port3' style="height: 12px; padding:3px;"><span class="input port3" style="padding:3px;"><input type='text' class='input_1 number loaddischarge' name="loaddischarge3" value="<?php echo $loaddischarge3; ?>" style="max-width:100px;" /></span></td>
						  </tr>
						  <tr bgcolor="e9e9e9">
							<td style="padding:3px;"><strong>Agency Fee ($)</strong></td>
							<td class='input port1' style="padding:3px;"><input type='text' class='input_1 number agencyfee' name="agencyfee1" value="<?php echo $agencyfee1; ?>" style="max-width:100px;" /></td>
							<td class='input port2' style="padding:3px;"><input type='text' class='input_1 number agencyfee' name="agencyfee2" value="<?php echo $agencyfee2; ?>" style="max-width:100px;" /></td>
							<td class='input port3' style="padding:3px;"><input type='text' class='input_1 number agencyfee' name="agencyfee3" value="<?php echo $agencyfee3; ?>" style="max-width:100px;" /></td>
						  </tr>
						  <tr bgcolor="f5f5f5">
							<td style="padding:3px;"><strong>Miscellaneous ($)</strong></td>
							<td class='input port1' style="padding:3px;"><input type='text' class='input_1 number miscellaneous' name="miscellaneous1" value="<?php echo $miscellaneous1; ?>" style="max-width:100px;" /></td>
							<td class='input port2' style="padding:3px;"><input type='text' class='input_1 number miscellaneous' name="miscellaneous2" value="<?php echo $miscellaneous2; ?>" style="max-width:100px;" /></td>
							<td class='input port3' style="padding:3px;"><input type='text' class='input_1 number miscellaneous' name="miscellaneous3" value="<?php echo $miscellaneous3; ?>" style="max-width:100px;" /></td>
						  </tr>
						  <tr>
							<td class="label" style="padding:3px;"><strong>Demurrage ($)</strong></td>
							<td colspan="3" class="label calculated" id='c66' style="padding:3px;"><strong>0.00</strong></td>
						  </tr>
						  <tr>
							<td class="label" style="padding:3px;"><strong>Despatch ($)</strong></td>
							<td colspan="3" class="label calculated" id='c67' style="padding:3px;"><strong>48,849.31</strong></td>
						  </tr>
						  <tr>
							<td class="label" style="padding:3px;"><strong>Total ($)</strong></td>
							<td colspan="3" class="label calculated" id='c68' style="padding:3px;"></td>
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
							<td width="125" style="padding:3px;">
								<?php
								$canalarr = array(
											1=>"White Sea - Baltic Canal", 
											2=>"Rhine - Main- Danube Canal", 
											3=>"Volga - Don Canal",
											4=>"Kiel Canal",
											5=>"Houston Ship Channel",
											6=>"Alphonse Xlll Canal",
											7=>"Panama Canal",
											8=>"Danube Black - Sea Canal",
											9=>"Manchester Ship Canal",
											10=>"Welland Canal",
											11=>"Saint Lawrence Seaway",
											12=>"Suez Canal"
										);
										
								$canalt = count($canalarr);
								?>
								<select id='canal' name="canal" class="input_1" style="max-width:100px;">
									<?php
									for($canali=1; $canali<=$canalt; $canali++){
										if($canalarr[$canali]==$canal){
											echo '<option value="'.$canalarr[$canali].'" selected="selected">'.$canalarr[$canali].'</option>';
										}else{
											echo '<option value="'.$canalarr[$canali].'">'.$canalarr[$canali].'</option>';
										}
									}
									?>
								</select>
							</td>
						  </tr>
						  <tr bgcolor="e9e9e9">
							<td style="padding:3px;"><b>Booking Fee ($)</b></td>
							<td class='empty' style="padding:3px;"><input type='text' id='cbook1' name="cbook1" value="<?php echo $cbook1; ?>"  class='input_1 number' style="max-width:200px;" /></td>
							<td class='empty' style="padding:3px;"><input type='text' id='cbook2' name="cbook2" value="<?php echo $cbook2; ?>"  class='input_1 number' style="max-width:200px;" /></td>
						  </tr>
						  <tr bgcolor="f5f5f5">
							<td style="padding:3px;"><b>Tugs ($)</b></td>
							<td class='empty' style="padding:3px;"><input type='text' id='ctug1' name="ctug1" value="<?php echo $ctug1; ?>" class='input_1 number' style="max-width:200px;" /></td>
							<td class='empty' style="padding:3px;"><input type='text' id='ctug2' name="ctug2" value="<?php echo $ctug2; ?>" class='input_1 number' style="max-width:200px;" /></td>
						  </tr>
						  <tr bgcolor="e9e9e9">
							<td style="padding:3px;"><b>Line Handlers ($)</b></td>
							<td class='empty' style="padding:3px;"><input type='text' id='cline1' name="cline1" value="<?php echo $cline1; ?>" class='input_1 number' style="max-width:200px;" /></td>
							<td class='empty' style="padding:3px;"><span class="empty" style="padding:3px;"><input type='text' id='cline2' name="cline2" value="<?php echo $cline2; ?>" class='input_1 number' style="max-width:200px;" /></span></td>
						  </tr>
						  <tr bgcolor="f5f5f5">
							<td style="padding:3px;"><b>Miscellaneous ($)</b></td>
							<td class='empty' style="padding:3px;"><input type='text' id='cmisc1' name="cmisc1" value="<?php echo $cmisc1; ?>" class='input_1 number' style="max-width:200px;" /></td>
							<td class='empty' style="padding:3px;"><input type='text' id='cmisc2' name="cmisc2" value="<?php echo $cmisc2; ?>" class='input_1 number' style="max-width:200px;" /></td>
						  </tr>
						  <tr bgcolor="e9e9e9">
							<td class="label" style="padding:3px;"><strong>Total ($)</strong></td>
							<td class="label calculated" id='ctotal1' style="padding:3px;"></td>
							<td class="label calculated" id='ctotal2' style="padding:3px;"></td>
						  </tr>
						</table>
					
						<div style="border-bottom:3px dotted #fff;">&nbsp;</div>
						<div>&nbsp;</div>
					
						<table width="490" height='460' border="0" cellspacing="0" cellpadding="0">
						  <tr>
							<td bgcolor="#000000"><iframe src='' id="map_iframeve" width='490' height='460' frameborder="0"></iframe></td>
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
					<td class="calculated" id='b74' style="padding:3px;"></td>
					<td class="calculated" id='c74' style="padding:3px;"><strong>161,150.69</strong></td>
					<td class="calculated" id='d74' style="padding:3px;"><strong>150,000.00</strong></td>
					<td class='input' style="padding:3px;"><input type='text' class='input_1 number' id='e74' name="e74" value="<?php echo $e74; ?>" style="max-width:70px;" /></td>
					<td class='input' style="padding:3px;"><input type='text' class='input_1 number' id='f74' name="f74" value="<?php echo $f74; ?>" style="max-width:70px;" /></td>
					<td class='input' style="padding:3px;"><input type='text' class='input_1 number' id='g74' name="g74" value="<?php echo $g74; ?>" style="max-width:70px;" /></td>
					<td class='input' style="padding:3px;"><input type='text' class='input_1 number' id='h74' name="h74" value="<?php echo $h74; ?>" style="max-width:70px;" /></td>
					<td class='input' style="padding:3px;"><input type='text' class='input_1 number' id='i74' name="i74" value="<?php echo $i74; ?>" style="max-width:70px;" /></td>
					<td class='input' style="padding:3px;"><input type='text' class='input_1 number' id='j74' name="j74" value="<?php echo $j74; ?>" style="max-width:70px;" /></td>
				  </tr>
				  <tr>
					<td colspan="9" class="label calculated" id='b75' style="padding:3px;"></td>
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
									<td class='empty' style="padding:3px;"><input type='text' class='input_1 number' id='b80' name="b80" value="<?php echo $b80; ?>" style="max-width:100px;" /></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td height="5"></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td class="label" style="padding:3px;"><strong>Gross Freight ($)</strong></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td class="calculated" id='c80' style="padding:3px;"></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td height="5"></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td class="label" style="padding:3px;"><strong>Brok. Comm ($)</strong></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td style="padding:3px;"><input type='text' class='input_1 number' id='d80' name="b80" value="<?php echo $b80; ?>" style="max-width:100px;" /></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td height="5"></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td class="label" style="padding:3px;"><strong>Add. Comm ($)</strong></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td style="padding:3px;"><input type='text' class='input_1 number' id='e80' name='e80' value="<?php echo $e80; ?>" style="max-width:100px;" /></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td height="5"></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td class="label" style="padding:3px;"><strong>Gross Income ($)</strong></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td class="calculated" id='f80' style="padding:3px;"></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td height="5"></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td class="label" style="padding:3px;"><strong>TCE ($/day)</strong></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td class="calculated" id='g80' style="padding:3px;"></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td height="5"></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td class="label" style="padding:3px;"><strong>Total</strong></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td class="label calculated" id='d81' style="padding:3px;"></td>
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
									<td class="calculated" id='b85' style="padding:3px;"></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td height="5"></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td class="label" style="padding:3px;"><strong>Gross Freight ($)</strong></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td class="calculated"  id='c85' style="padding:3px;"></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td height="5"></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td class="label" style="padding:3px;"><strong>Brok. Comm ($)</strong></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td style="padding:3px;"><input type='text' class='input_1 number' id='d85' name='d85' value="<?php echo $d85; ?>" style="max-width:100px;" /></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td height="5"></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td class="label" style="padding:3px;"><strong>Add. Comm ($)</strong></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td style="padding:3px;"><input type='text' class='input_1 number' id='e85' name='e85' value="<?php echo $e85; ?>" style="max-width:100px;" /></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td height="5"></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td class="label" style="padding:3px;"><strong>Gross Income ($)</strong></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td class="calculated"  id='f85' style="padding:3px;"></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td height="5"></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td class="label" style="padding:3px;"><strong>TCE ($/day)</strong></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td class='empty' style="padding:3px;"><input type='text' class='input_1 number' id='g85' name='g85' value="<?php echo $g85; ?>" style="max-width:100px;" /></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td height="5"></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td class="label" style="padding:3px;"><strong>Total</strong></td>
								</tr>
								<tr bgcolor="f5f5f5">
									<td class="label calculated"  id='d86' style="padding:3px;"></td>
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
		<td style='border:0px; text-align:right;'>Powered by <img src='http://".$_SERVER['HTTP_HOST']."/app/images/logo_cargospotter1.png' width='20'> <b>Cargospotter</b></td>
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
$fromname = "Cargospotter Mailer";
$bouncereturn = "tools@cargospotter.no"; //where the email will forward in cases of bounced email
$subject = "Cargospotter Position List";
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