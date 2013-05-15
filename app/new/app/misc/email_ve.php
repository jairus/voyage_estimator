<?php
@ob_start();
@session_start();

include_once(dirname(__FILE__)."/../includes/bootstrap.php");
include_once(dirname(__FILE__)."/emailer/email.php");
date_default_timezone_set('UTC'); 
?>
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/jquery-1.7.2.min.js"></script>
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

.dp{
	padding:3px;
}
</style>
<script>
function setValue(elem, value){
	if(elem.prop("tagName")=="TD"){
		elem.html(value);
	}else{
		elem.val(value);
	}
}

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

$imo = 0;
if(isset($_GET['vessel_name_or_imo'])){
	$imo = explode(' - ', $_GET['vessel_name_or_imo']);
	$imo = $imo[0];
}

$sql_ship = "SELECT * FROM _xvas_parsed2_dry WHERE imo = '".$imo."' LIMIT 0,1";
$r_ship = dbQuery($sql_ship);

$sql_xvax = "SELECT * FROM _xvas_shipdata_dry WHERE imo = '".$imo."' LIMIT 0,1";
$r_xvax = dbQuery($sql_xvax);
?>

<?php
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
		  	<td style="border:1px solid #333333;">
				<div class="dp">
					Vessel - IMO# <?php echo $r_ship[0]['imo']; ?> - <?php echo $r_ship[0]['name']; ?> DWT - <?php echo $r_ship[0]['summer_dwt']; ?> Built - <?php echo getValue($r_xvax[0]['data'], 'BUILD'); ?>
				</div>
			</td>
		  </tr>
		</table>
		
		<div>&nbsp;</div>
		
		<!-- CARGO -->
		<table width="1300" border="0" cellspacing="0" cellpadding="0">
		  <tr style="background-color:#000000; color:#FFFFFF;">
		  	<td width="260"><div class="dp">CARGO - Summary</div></td>
			<td width="260"><div class="dp">Port/Region</div></td>
			<td width="260"><div class="dp">DWT</div></td>
			<td width="260"><div class="dp">Load Days mt/d</div></td>
			<td width="260"><div class="dp">Terms</div></td>
		  </tr>
		  
		  <?php
		  if(isset($_GET['seq_count'])){
		  	for($seq=1; $seq<=$_GET['seq_count']; $seq++){
				if($_GET['voyage_type'.$seq]=='Loading' || $_GET['voyage_type'.$seq]=='Discharging'){
				?>
				  <tr>
					<td><div class="dp"><?php echo $_GET['voyage_type'.$seq]; ?></div></td>
					<td><div class="dp"><?php echo $_GET['port_to'.$seq]; ?></div></td>
					<td><div class="dp">&nbsp;</div></td>
					<td><div class="dp">&nbsp;</div></td>
					<td><div class="dp"><?php echo $_GET['wdt'.$seq]; ?></div></td>
				  </tr>
				<?php
				}
			}
		  }
		  ?>
		</table>
		<!-- END OF CARGO -->
		
		<div>&nbsp;</div>
		
		<!-- DISTANCE -->
		<table width="1300" border="0" cellspacing="0" cellpadding="0">
		  <tr style="background-color:#4f81bd; color:#FFFFFF;">
		  	<td width="260"><div class="dp">DISTANCE - Summary</div></td>
			<td width="260"><div class="dp">Port</div></td>
			<td width="260"><div class="dp">Distance NM</div></td>
			<td width="260"><div class="dp">Sea Margin %</div></td>
			<td width="260"><div class="dp">Distance NM</div></td>
		  </tr>
		  
		  <?php
		  if(isset($_GET['seq_count'])){
		  	for($seq=1; $seq<=$_GET['seq_count']; $seq++){
				if($_GET['voyage_type'.$seq]=='Ballast' || $_GET['voyage_type'.$seq]=='Bunker Stop' || $_GET['voyage_type'.$seq]=='Laden' || $_GET['voyage_type'.$seq]=='Repositioning'){
				?>
				  <tr>
					<td><div class="dp">&nbsp;</div></td>
					<td><div class="dp"><?php echo $_GET['port_to'.$seq]; ?></div></td>
					<td><div class="dp">&nbsp;</div></td>
					<td><div class="dp"><?php echo $_GET['input_percent'.$seq]; ?></div></td>
					<td><div class="dp">&nbsp;</div></td>
				  </tr>
				<?php
				}
			}
		  }
		  ?>
		</table>
		<!-- END OF DISTANCE -->
		
		<div>&nbsp;</div>
		
		<!-- BUNKER -->
		<table width="1300" border="0" cellspacing="0" cellpadding="0">
		  <tr style="background-color:#c0504d; color:#FFFFFF;">
		  	<td width="260"><div class="dp">Bunkers - Summary</div></td>
			<td width="260"><div class="dp">Ballast mt/d</div></td>
			<td width="260"><div class="dp">Laden mt/d</div></td>
			<td width="260"><div class="dp">Load Port mt/d</div></td>
			<td width="260"><div class="dp">Discharge Port  mt/d</div></td>
		  </tr>
		  <tr>
		  	<td><div class="dp">&nbsp;</div></td>
			<td><div class="dp">&nbsp;</div></td>
			<td><div class="dp">&nbsp;</div></td>
			<td><div class="dp">&nbsp;</div></td>
			<td><div class="dp">&nbsp;</div></td>
		  </tr>
		</table>
		<!-- END OF BUNKER -->
		
		<div>&nbsp;</div>
			
		<!-- TIME -->
		<table width="1300" border="0" cellspacing="0" cellpadding="0">
		  <tr style="background-color:#9bbb59; color:#FFFFFF;">
		  	<td width="260"><div class="dp">Time - Summary</div></td>
			<td width="260"><div class="dp">Port</div></td>
			<td width="260"><div class="dp">Sea Days</div></td>
			<td width="260"><div class="dp">Loading /Extra Days</div></td>
			<td width="260"><div class="dp">Extra Time - Canal</div></td>
		  </tr>
		  <tr>
		  	<td><div class="dp">&nbsp;</div></td>
			<td><div class="dp">&nbsp;</div></td>
			<td><div class="dp">&nbsp;</div></td>
			<td><div class="dp">&nbsp;</div></td>
			<td><div class="dp">&nbsp;</div></td>
		  </tr>
		</table>
		<!-- END OF TIME -->
		
		<div>&nbsp;</div>
		
		<!-- FIXED COSTS -->
		<table width="1300" border="0" cellspacing="0" cellpadding="0">
		  <tr style="background-color:#8064a2; color:#FFFFFF;">
		  	<td width="325"><div class="dp">Fixed Costs - Summary</div></td>
			<td width="325"><div class="dp">Reason</div></td>
			<td width="325"><div class="dp">Type</div></td>
			<td width="325"><div class="dp">Amount US$</div></td>
		  </tr>
		  <tr>
		  	<td><div class="dp">&nbsp;</div></td>
			<td><div class="dp">&nbsp;</div></td>
			<td><div class="dp">&nbsp;</div></td>
			<td><div class="dp">&nbsp;</div></td>
		  </tr>
		</table>
		<!-- END OF FIXED COSTS -->
		
		<div>&nbsp;</div>
		
		<!-- MAIN COSTS -->
		<table width="1300" border="0" cellspacing="0" cellpadding="0">
		  <tr style="background-color:#f79646; color:#FFFFFF;">
		  	<td width="325"><div class="dp">Misc Costs - Summary</div></td>
			<td width="325"><div class="dp">Amount US$</div></td>
			<td width="325"><div class="dp">Misc Costs - Summary</div></td>
			<td width="325"><div class="dp">Amount US$</div></td>
		  </tr>
		  <tr>
		  	<td><div class="dp">&nbsp;</div></td>
			<td><div class="dp">&nbsp;</div></td>
			<td><div class="dp">&nbsp;</div></td>
			<td><div class="dp">&nbsp;</div></td>
		  </tr>
		</table>
		<!-- END OF MAIN COSTS -->
		
		<div>&nbsp;</div>
		
		<!-- OTHERS -->
		<table width="1300" border="0" cellspacing="0" cellpadding="0">
		  <tr>
		  	<td width="645" valign="top">
				<table width="645" border="1" bordercolor="#333333" cellspacing="0" cellpadding="0">
				  <tr style="background-color:#000000; color:#FFFFFF;">
					<td width="215"><div class="dp">TIME</div></td>
					<td width="215"><div class="dp">TIME DAYS</div></td>
					<td width="215"><div class="dp">%  DATA</div></td>
				  </tr>
				  <tr>
					<td><div class="dp">BALLAST</div></td>
					<td><div class="dp">&nbsp;</div></td>
					<td><div class="dp">&nbsp;</div></td>
				  </tr>
				  <tr>
					<td><div class="dp">LADEN</div></td>
					<td><div class="dp">&nbsp;</div></td>
					<td><div class="dp">&nbsp;</div></td>
				  </tr>
				  <tr>
					<td><div class="dp">IN PORT /CANAL</div></td>
					<td><div class="dp">&nbsp;</div></td>
					<td><div class="dp">&nbsp;</div></td>
				  </tr>
				  <tr>
					<td><div class="dp">EXTRA</div></td>
					<td><div class="dp">&nbsp;</div></td>
					<td><div class="dp">&nbsp;</div></td>
				  </tr>
				  <tr>
					<td><div class="dp"><b>TOTAL</b></div></td>
					<td><div class="dp"><b>&nbsp;</b></div></td>
					<td><div class="dp"><b>&nbsp;</b></div></td>
				  </tr>
				</table>
				
				<div>&nbsp;</div>
				
				<table width="645" border="1" bordercolor="#333333" cellspacing="0" cellpadding="0">
				  <tr style="background-color:#000000; color:#FFFFFF;">
					<td width="215"><div class="dp">COSTS</div></td>
					<td width="215"><div class="dp">AMOUNT US$</div></td>
					<td width="215"><div class="dp">%  DATA</div></td>
				  </tr>
				  <tr>
					<td><div class="dp">BUNKER</div></td>
					<td><div class="dp">&nbsp;</div></td>
					<td><div class="dp">&nbsp;</div></td>
				  </tr>
				  <tr>
					<td><div class="dp">COMMISSION 1</div></td>
					<td><div class="dp">&nbsp;</div></td>
					<td><div class="dp">&nbsp;</div></td>
				  </tr>
				  <tr>
					<td><div class="dp">COMMISSION 2</div></td>
					<td><div class="dp">&nbsp;</div></td>
					<td><div class="dp">&nbsp;</div></td>
				  </tr>
				  <tr>
					<td><div class="dp">IN PORT</div></td>
					<td><div class="dp">&nbsp;</div></td>
					<td><div class="dp">&nbsp;</div></td>
				  </tr>
				  <tr>
					<td><div class="dp">CANAL</div></td>
					<td><div class="dp">&nbsp;</div></td>
					<td><div class="dp">&nbsp;</div></td>
				  </tr>
				  <tr>
					<td><div class="dp">MISCELLANEOUS</div></td>
					<td><div class="dp">&nbsp;</div></td>
					<td><div class="dp">&nbsp;</div></td>
				  </tr>
				  <tr>
					<td><div class="dp">VESSEL</div></td>
					<td><div class="dp">&nbsp;</div></td>
					<td><div class="dp">&nbsp;</div></td>
				  </tr>
				  <tr>
					<td><div class="dp"><b>TOTAL</b></div></td>
					<td><div class="dp"><b>&nbsp;</b></div></td>
					<td><div class="dp"><b>&nbsp;</b></div></td>
				  </tr>
				</table>
			</td>
			<td width="10"></td>
			<td width="645" valign="top">
				<table width="645" border="1" bordercolor="#333333" cellspacing="0" cellpadding="0">
				  <tr style="background-color:#000000; color:#FFFFFF;">
					<td colspan="4"><div class="dp">PROFITABILITY $/MT</div></td>
				  </tr>
				  <tr>
					<td width="161"><div class="dp">NET FREIGHT</div></td>
					<td width="161"><div class="dp">QUANTITY MT</div></td>
					<td width="161"><div class="dp">TOTAL COST US$</div></td>
					<td width="162"><div class="dp" style="color:#FF0000;">COST / MT</div></td>
				  </tr>
				  <tr>
					<td><div class="dp"><b>&nbsp;</b></div></td>
					<td><div class="dp"><b>&nbsp;</b></div></td>
					<td><div class="dp"><b>&nbsp;</b></div></td>
					<td><div class="dp" style="color:#FF0000;"><b>&nbsp;</b></div></td>
				  </tr>
				</table>
				
				<div>&nbsp;</div>
				
				<table width="645" border="1" bordercolor="#333333" cellspacing="0" cellpadding="0">
				  <tr style="background-color:#000000; color:#FFFFFF;">
					<td colspan="2"><div class="dp">PROFITABILITY NTCE</div></td>
				  </tr>
				  <tr>
					<td width="300"><div class="dp">TOTAL COST US$</div></td>
					<td width="345"><div class="dp">&nbsp;</div></td>
				  </tr>
				  <tr>
					<td><div class="dp">FIXED COST</div></td>
					<td><div class="dp">&nbsp;</div></td>
				  </tr>
				  <tr>
					<td><div class="dp">BUNKER COST</div></td>
					<td><div class="dp">&nbsp;</div></td>
				  </tr>
				  <tr>
					<td><div class="dp"><b>TOTAL</b></div></td>
					<td><div class="dp"><b>&nbsp;</b></div></td>
				  </tr>
				</table>
				
				<div>&nbsp;</div>
				
				<table width="645" border="1" bordercolor="#333333" cellspacing="0" cellpadding="0">
				  <tr style="background-color:#000000; color:#FFFFFF;">
					<td colspan="4"><div class="dp">NTCE $/DAY</div></td>
				  </tr>
				  <tr>
					<td width="161"><div class="dp">TOTAL COST</div></td>
					<td width="161"><div class="dp">FIXED COSTS</div></td>
					<td width="161"><div class="dp">TOTAL DAYS</div></td>
					<td width="162"><div class="dp" style="color:#FF0000;">NTCE/DAY</div></td>
				  </tr>
				  <tr>
					<td><div class="dp"><b>&nbsp;</b></div></td>
					<td><div class="dp"><b>&nbsp;</b></div></td>
					<td><div class="dp"><b>&nbsp;</b></div></td>
					<td><div class="dp" style="color:#FF0000;"><b>&nbsp;</b></div></td>
				  </tr>
				</table>
			</td>
		  </tr>
		</table>
		<!-- END OF OTHERS -->
			
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