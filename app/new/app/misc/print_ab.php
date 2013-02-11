<?php
@session_start();

include_once(dirname(__FILE__)."/../includes/bootstrap.php");
include_once(dirname(__FILE__)."/emailer/email.php");
date_default_timezone_set('UTC');

//FUNCTIONS
function getMessageByImo($imo, $type){
	$hasnum = preg_match("/[0-9]/iUs", $type);
	$type = preg_replace("/[0-9]/iUs", "", $type);
	
	global $link;
	
	$imo = mysql_escape_string($imo);
	
	if(strtolower($type)=='private'){
		$userid = $_SESSION['user']['id'];
		
		$sql = "select `email` from `_sbis_users` where `id`='".$userid."'";
		$email = dbQuery($sql, $link);

		$email = $email[0]['email'];
		$sql = "select * from `_messages` where `imo`='".$imo."' and `type`='private' and `user_email` = '".$email."' order by `id` desc  limit 1";
	}else if(strtolower($type)=='remark'||strtolower($type)=='openport'||strtolower($type)=='opendate'||strtolower($type)=='destinationregion'||strtolower($type)=='destinationdate'||strtolower($type)=='charterer'||strtolower($type)=='cargotype'||strtolower($type)=='quantity'||strtolower($type)=='status'||strtolower($type)=='cbm'||strtolower($type)=='rate'||strtolower($type)=='tce'||strtolower($type)=='ws'||strtolower($type)=='network'||strtolower($type)=='user_email'){

		$userid = $_SESSION['user']['id'];
		
		$sql = "select * from `_messages` where `imo`='".$imo."' and `type`='network' and 
		`user_email` in ( 
			select `email` from `_sbis_users` where 
			`id` in (
				select `userid1` from _network where (`userid1` = '".$userid."' or `userid2` = '".$userid."')
			) or
			`id` in (
				select `userid2` from _network where (`userid1` = '".$userid."' or `userid2` = '".$userid."')
			)

		)
		order by `id` desc limit 1";
	}
	
	$r = dbQuery($sql, $link);
	
	if($hasnum ){}
	
	return $r[0];
}

function getMessages($imo, $type){

	global $link;
	$imo = mysql_escape_string($imo);
	if(strtolower($type)=='private'){

		$userid = $_SESSION['user']['id'];
		$sql = "select `email` from `_sbis_users` where `id`='".$userid."'";
		$email = dbQuery($sql, $link);

		$email = $email[0]['email'];
		$sql = "select * from `_messages` where `imo`='".$imo."' and `type`='private' and `user_email` = '".$email."' order by `id` desc  limit 1000";

	}else if(strtolower($type)=='remarks'||strtolower($type)=='openport'||strtolower($type)=='opendate'||strtolower($type)=='destinationregion'||strtolower($type)=='destinationdate'||strtolower($type)=='charterer'||strtolower($type)=='cargotype'||strtolower($type)=='quantity'||strtolower($type)=='status'||strtolower($type)=='cbm'||strtolower($type)=='rate'||strtolower($type)=='tce'||strtolower($type)=='ws'||strtolower($type)=='dely'||strtolower($type)=='delydate_from'||strtolower($type)=='delydate_to'||strtolower($type)=='redely1'||strtolower($type)=='redelydate1'||strtolower($type)=='redely2'||strtolower($type)=='redelydate2'||strtolower($type)=='redely3'||strtolower($type)=='redelydate3'||strtolower($type)=='redely4'||strtolower($type)=='redelydate4'||strtolower($type)=='rate'||strtolower($type)=='charterer'||strtolower($type)=='preriod'||strtolower($type)=='dur_min'||strtolower($type)=='dur_max'||strtolower($type)=='relet'||strtolower($type)=='network'){

		$userid = $_SESSION['user']['id'];		
		$sql = "select * from `_messages` where `imo`='".$imo."' and `type`='network' and 
		`user_email` in ( 
			select `email` from `_sbis_users` where 
			`id` in (
				select `userid1` from _network where (`userid1` = '".$userid."' or `userid2` = '".$userid."')
			) or
			`id` in (
				select `userid2` from _network where (`userid1` = '".$userid."' or `userid2` = '".$userid."')

			)

		)
		order by `id` desc limit 1000";	

	}
	$r = dbQuery($sql, $link);
	return $r;

}
//END OF FUNCTIONS

$sql1 = "select * from `_sbis_users` where `id`='".$_SESSION['user']['uid']."'";
$r1 = dbQuery($sql1);

$r_ships = $_SESSION['r_ships'];

$imos = $_GET['imo'];

$t = count($r_ships);

$ships = array();
for($i=0; $i<$t; $i++){
	if(in_array("a1_".$r_ships[$i]['xvas_imo'], $imos)){
		$ships[] = $r_ships[$i];
	}
}
$r_ships = $ships;

$t = count($r_ships);

if($r1[0]['purchase']=="Trial Account (7 Days Trial Account)"){
	?><script>alert('As you are using a \'Trial Account\' you are only allowed to mail, print or export 5 ships to excel, email or print. A Subscription account allows unlimited access and facilities to export, print or email.');</script><?php
}

?>
<style>
*{
	font-size:11px;
	font-family:Verdana, Arial, Helvetica, sans-serif;
}
td,th{
	border: 1px solid gray;
}

.z_text01{
	font-family:Arial, Helvetica, sans-serif;
	font-size:12px;
	color:#000;
	text-decoration:none;
}
</style>
<?php

$ext = array('.jpg', '.gif', '.png');
foreach($ext as $value){
	if( file_exists("../images/user_images/company_".$r1[0]['id'].$value) ){
		$photo1 = "company_".$r1[0]['id'].$value;
	}
}

$photo1 = empty($photo1) ? 'default.jpg' : $photo1;

echo "<table width='1000px'>
	<tr>
		<td style='border:0px;' width='460'><img src='http://".$_SERVER['HTTP_HOST']."/app/images/logo_cargospotter1.png'></td>
		<td style='border:0px; text-align:right;' width='540'><img src='http://".$_SERVER['HTTP_HOST']."/app/images/user_images/".$photo1."' width='80' alt='photo' border='0' /><br>Sent by <a href='mailto:".$r1[0]['email']."'>".$r1[0]['email']."</a></td>
	</tr>
</table>
<div style='text-align:left; padding:15px 5px 5px 5px;'><b>CURRENT DATE/TIME: ".date("d-m-Y")."</b></div>
<div style='text-align:left; padding:0px 5px 15px;'><b>".$_SESSION['searchcriteria']."</b></div>
<div style='text-align:left; padding:0px 5px;'>
	<table width='100%' border='0' cellpadding='0' cellspacing='0'>
		<tr>
			<td width='30' style='border:0px;'><img src='http://".$_SERVER['HTTP_HOST']."/app/images/shipeta.jpg' /></td>
			<td style='border:0px;'><b style='font-size:14px;'>POSITION REPORT</b></td>
		</tr>
	</table>
</div>";

if($t){
	/****************************************************************************************/
	
	if($r1[0]['purchase']=="Trial Account (7 Days Trial Account)" && $t>5){ $t = 5; }
	
	echo "<div style='width:990px; text-align:left; padding:5px; background:#c5dc3b; color:white; margin-top:5px;'>
		<table cellpadding='0' cellspacing='0' width='990px'>
			<tr>
				<td style='border:0px;'><b style='font-size:14px;'>SHIPS WITH AIS DESTINATIONS & ETA</td>
			</tr>
		</table>
	</div>
	<table width='1000' style='border:1px solid #000; background:#BCBCBC; color:#333333;'>
		<tr>
			<th width='220px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Name</div></th>
			<th width='200px' style='background:#BCBCBC; color:#333333; text-align:right;'><div style='padding:5px;'>Destination</div></th>
			<th width='180px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>ETA</div></th>
			<th width='110px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Heading</div></th>
			<th width='110px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>AIS Speed</div></th>
			<th width='110px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Stated Speed</div></th>
			<th width='160px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Private Message</div></th>
		</tr>";
	
	for($i=0; $i<$t; $i++){
		$ships = $r_ships[$i];
		
		$imo = $ships['xvas_imo'];
		
		$sql  = "SELECT * FROM `_xvas_shipdata_dry` WHERE `imo`='".$imo."'";
		$xvas = dbQuery($sql);
		$xvas = $xvas[0];
		
		if(trim($xvas['data'])){
			$imageb = base64_encode("http://dataservice.grosstonnage.com/S-Bisphoto.php?imo=".$imo);
			$ship_img = "<img src='http://".$_SERVER['HTTP_HOST']."/app/image.php?b=1&mx=20&p=".$imageb."'>";
			$ship_name = getValue($xvas['data'], 'NAME');
			
			$destination = $ships['siitech_destination'];
			if(!trim($destination)){ $destination = "<img style='height:15px; width:15px;' src='http://".$_SERVER['HTTP_HOST']."/app/images/alert1.png' alt='No AIS Data Available' title='No AIS Data Available' />"; }
			
			$destination_eta = date("M j, 'y G:i e", strtotime($ships['siitech_eta']));
			
			$siitech_shippos_data = $ships['siitech_shippos_data'];
			$siitech_shippos_data = getXMLtoArr($siitech_shippos_data);
			
			$heading = str_replace(' degrees', '', $siitech_shippos_data['TrueHeading']).'&deg;';
			
			$siitech_shipstat_data = $ships['siitech_shipstat_data'];
			$siitech_shipstat_data = getXMLtoArr($siitech_shipstat_data);
			
			$ais_speed = $siitech_shipstat_data['speed_ais'];
			if(trim($ais_speed)){ $ais_speed = number_format($ais_speed, 2); }
			else{ $ais_speed = "13.50"; }
			
			$stated_speed = $ships['SPEED'];
			if(trim($stated_speed)){ $stated_speed = number_format($stated_speed, 2); }
			else{ $stated_speed = "13.50"; }
			
			//PRIVATE MESSAGE
			$private     = getMessageByImo($imo, 'private');
			$private     = stripslashes($private['message']);
			$private_msg = htmlentities($private);
			//END
			
			echo "<tr style='background:#e5e5e5;'>
				<td>
					<div style='padding:5px;'>
						<table border='0' cellpadding='0' cellspacing='0' width='100%'>
							<tr>
								<td width='25' style='border:none;'>".$ship_img."</td>
								<td style='border:none;'>".$ship_name."</td>
							</tr>
						</table>
					</div>
				</td>
				<td style='text-align:right;'><div style='padding:5px;'>".$destination."</div></td>
				<td><div style='padding:5px;'>".$destination_eta."</div></td>
				<td class='z_text01'><div style='padding:5px;'>".$heading."</div></td>
				<td class='z_text01'><div style='padding:5px;'>".$ais_speed."</div></td>
				<td class='z_text01'><div style='padding:5px;'>".$stated_speed."</div></td>
				<td style='text-align:left;' class='z_text01'><div style='padding:5px;'>".$private_msg."</div></td>
			</tr>
			<tr style='background:#fff;'>
				<td colspan='10'>";
					
					//BROKER UPDATE
					$sql = "SELECT * FROM `_messages` WHERE `imo`='".$imo."' AND type='network' ORDER BY dateadded DESC LIMIT 0,1";
					$nmessage = dbQuery($sql, $link);
					$nmessagearr = unserialize($nmessage[0]['message']);
					
					$dely          = $nmessagearr['dely'];
					$delydate_from = $nmessagearr['delydate_from'];
					$delydate_to   = $nmessagearr['delydate_to'];
					$redely1       = $nmessagearr['redely1'];
					$redelydate1   = $nmessagearr['redelydate1'];
					$redely2       = $nmessagearr['redely2'];
					$redelydate2   = $nmessagearr['redelydate2'];
					$redely3       = $nmessagearr['redely3'];
					$redelydate3   = $nmessagearr['redelydate3'];
					$redely4       = $nmessagearr['redely4'];
					$redelydate4   = $nmessagearr['redelydate4'];
					$rate          = $nmessagearr['rate'];
					$charterer     = $nmessagearr['charterer'];
					$period        = $nmessagearr['period'];
					$dur_min       = $nmessagearr['dur_min'];
					$dur_max       = $nmessagearr['dur_max'];
					$relet         = $nmessagearr['relet'];
					$remarks       = $nmessagearr['remarks'];
					//END OF BROKER UPDATE
					
					//OPERATOR UPDATE
					$sql = "SELECT * FROM `_operators_update` WHERE `imo`='".$imo."' AND type='op_update' ORDER BY dateadded DESC LIMIT 0,1";
					$oupdate = dbQuery($sql, $link);
					$oupdatearr = unserialize($oupdate[0]['message']);
					
					$ou_status     = $oupdatearr['ou_status'];
					$ou_date_from  = $oupdatearr['ou_date_from'];
					$ou_date_to    = $oupdatearr['ou_date_to'];
					$ou_open_port  = $oupdatearr['ou_open_port'];
					$ou_open_date  = $oupdatearr['ou_open_date'];
					$ou_last_cargo = $oupdatearr['ou_last_cargo'];
					$ou_remarks    = $oupdatearr['ou_remarks'];
					//END OF OPERATOR UPDATE
					
					if($nmessagearr){
						if((time()-strtotime($delydate_to))<(60*60*24*15)){
							echo "<table cellspacing='0' cellpadding='0' border='1' width='990'>
								<tr>
									<th style='background:#BCBCBC; color:#333333; text-align:right;'><div style='padding:5px;'>Dely</div></th>
									<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Date From</div></th>
									<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Date To</div></th>
									<th style='background:#BCBCBC; color:#333333; text-align:right;'><div style='padding:5px;'>Redely 1</div></th>
									<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Date</div></th>
									<th style='background:#BCBCBC; color:#333333; text-align:right;'><div style='padding:5px;'>Redely 2</div></th>
									<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Date</div></th>
									<th style='background:#BCBCBC; color:#333333; text-align:right;'><div style='padding:5px;'>Redely 3</div></th>
									<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Date</div></th>
									<th style='background:#BCBCBC; color:#333333; text-align:right;'><div style='padding:5px;'>Redely 4</div></th>
									<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Date</div></th>
								</tr>
								<tr>
									<td style='width:100px; text-align:right;'><div style='padding:5px;'>&nbsp;".$dely."&nbsp;</div></td>
									<td style='width:83px; text-align:left;'><div style='padding:5px;'>&nbsp;".$delydate_from."&nbsp;</div></td>
									<td style='width:83px; text-align:left;'><div style='padding:5px;'>&nbsp;".$delydate_to."&nbsp;</div></td>
									<td style='width:100px; text-align:right;'><div style='padding:5px;'>&nbsp;".$redely1."&nbsp;</div></td>
									<td style='width:81px; text-align:left;'><div style='padding:5px;'>&nbsp;".$redelydate1."&nbsp;</div></td>
									<td style='width:100px; text-align:right;'><div style='padding:5px;'>&nbsp;".$redely2."&nbsp;</div></td>
									<td style='width:81px; text-align:left;'><div style='padding:5px;'>&nbsp;".$redelydate2."&nbsp;</div></td>
									<td style='width:100px; text-align:right;'><div style='padding:5px;'>&nbsp;".$redely3."&nbsp;</div></td>
									<td style='width:81px; text-align:left;'><div style='padding:5px;'>&nbsp;".$redelydate3."&nbsp;</div></td>
									<td style='width:100px; text-align:right;'><div style='padding:5px;'>&nbsp;".$redely4."&nbsp;</div></td>
									<td style='width:81px; text-align:left;'><div style='padding:5px;'>&nbsp;".$redelydate4."&nbsp;</div></td>
								</tr>
							</table>
							<table cellspacing='0' cellpadding='0' border='1' width='990'>
								<tr>
									<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Rate</div></th>
									<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Charterer</div></th>
									<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Period</div></th>
									<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Dur Min</div></th>
									<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Dur Max</div></th>
									<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Relet</div></th>
									<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Remarks - by: <font color='red'>".$nmessage[0]['user_email']."</font></div></th>
								</tr>
								<tr>
									<td style='width:90px; text-align:left;'><div style='padding:5px;'>&nbsp;".$rate."&nbsp;</div></td>
									<td style='width:90px; text-align:left;'><div style='padding:5px;'>&nbsp;".$charterer."&nbsp;</div></td>
									<td style='width:90px; text-align:left;'><div style='padding:5px;'>&nbsp;".$period."&nbsp;</div></td>
									<td style='width:90px; text-align:left;'><div style='padding:5px;'>&nbsp;".$dur_min."&nbsp;</div></td>
									<td style='width:90px; text-align:left;'><div style='padding:5px;'>&nbsp;".$dur_max."&nbsp;</div></td>
									<td style='width:90px; text-align:left;'><div style='padding:5px;'>&nbsp;".$relet."&nbsp;</div></td>
									<td style='width:450px; text-align:left;'><div style='padding:5px;'>&nbsp;".$remarks."&nbsp;</div></td>
								</tr>
							</table>";
						}
					}
					
					if(trim($oupdatearr)){
						echo "<table cellspacing='0' cellpadding='0' border='1' width='990'>
							<tr>
								<th style='background:#BCBCBC; color:#333333; text-align:right;'><div style='padding:5px;'>Status</div></th>
								<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Date From</div></th>
								<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Date To</div></th>
								<th style='background:#BCBCBC; color:#333333; text-align:right;'><div style='padding:5px;'>Open Port</div></th>
								<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Open Date</div></th>
								<th style='background:#BCBCBC; color:#333333; text-align:right;'><div style='padding:5px;'>Last Cargo</div></th>
								<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Remarks</div></th>
							</tr>
							<tr>
								<td style='width:100px; text-align:right;'><div style='padding:5px;'>&nbsp;".$ou_status."&nbsp;</div></td>
								<td style='width:81px; text-align:left;'><div style='padding:5px;'>&nbsp;".$ou_date_from."&nbsp;</div></td>
								<td style='width:81px; text-align:left;'><div style='padding:5px;'>&nbsp;".$ou_date_to."&nbsp;</div></td>
								<td style='width:186px; text-align:right;'><div style='padding:5px;'>&nbsp;".$ou_open_port."&nbsp;</div></td>
								<td style='width:81px; text-align:left;'><div style='padding:5px;'>&nbsp;".$ou_open_date."&nbsp;</div></td>
								<td style='width:175px; text-align:right;'><div style='padding:5px;'>&nbsp;".$ou_last_cargo."&nbsp;</div></td>
								<td style='width:286px; text-align:left;'><div style='padding:5px;'>&nbsp;".$ou_remarks."&nbsp;</div></td>
							</tr>
						</table>";
					}
					
					if(trim($ships['EMAIL LOAD_PORT'])){
						if((time()-strtotime($ships['EMAIL ETA TO LOAD PORT (days)']))<(60*60*24*15)){
							echo "<table cellspacing='0' cellpadding='0' border='1' width='990'>
								<tr>
									<th style='background:#BCBCBC; color:#333333;'>Location Name</th>
									<th style='background:#BCBCBC; color:#333333;'>Location Lat</th>
									<th style='background:#BCBCBC; color:#333333;'>Location Long</th>
									<th style='background:#BCBCBC; color:#333333;'>From Time</th>
									<th style='background:#BCBCBC; color:#333333;'>To Time</th>
									<th style='background:#BCBCBC; color:#333333;'>Broker</th>
								</tr>
								<tr>
									<td class='message' style='width:165px; padding: 0px 3px 0px 3px; text-align:right;'>".$ships['EMAIL LOAD_PORT']."</td>
									<td class='message' style='width:160px; padding: 0px 3px 0px 3px; text-align:right;'>".$ships['location_lat']."</td>
									<td class='message' style='width:160px; padding: 0px 3px 0px 3px; text-align:right;'>".$ships['location_lng']."</td>
									<td class='message' style='width:165px; padding: 0px 3px 0px 3px; text-align:right;'>".$ships['EMAIL ETA TO LOAD PORT (days)']."</td>
									<td class='message' style='width:165px; padding: 0px 3px 0px 3px; text-align:right;'>".date('M d, Y G:i:s', strtotime($ships['to_time']))."</td>
									<td class='message' style='width:175px; padding: 0px 3px 0px 3px; text-align:right;'>".$ships['from_address']."</td>
								</tr>
							</table>";
						}
					}
				
				echo "</td>
			</tr>";
		}
	}
	
	echo "</table>
		<table cellpadding='0' cellspacing='0' width='1000'>
			<tr>
				<td style='border:0px;'>&nbsp;</td>
			</tr>
		</table>";
	
	/****************************************************************************************/
}

echo "<table cellpadding='0' cellspacing='0' width='1000px'>
	<tr>
		<td style='border:0px; text-align:right;'>Powered by <img src='http://".$_SERVER['HTTP_HOST']."/app/images/logo_cargospotter1.png' width='20'> <b>CargoSpotter</b></td>
	</tr>
</table>";
?>
<script>window.print();</script>