<?php
@session_start();
include_once(dirname(__FILE__)."/../includes/bootstrap.php");
date_default_timezone_set('UTC'); 

$sql1 = "select * from `_sbis_users` where `id`='".$_SESSION['user']['uid']."'";
$r1 = dbQuery($sql1);

$shipsA1print = $_SESSION['shipsA1print'];
$shipsA2print = $_SESSION['shipsA2print'];
$shipsA3print = $_SESSION['shipsA3print'];
$shipsA4print = $_SESSION['shipsA4print'];
$shipsA5print = $_SESSION['shipsA5print'];

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

$imos = $_GET['imo'];

$t = count($shipsA1print);
$t2 = count($shipsA2print);
$t3 = count($shipsA3print);
$t4 = count($shipsA4print);
$t5 = count($shipsA5print);

$ships = array();
for($i=0; $i<$t; $i++){
	if(in_array("a1_".$shipsA1print[$i]['IMO #'], $imos)){
		$ships[] = $shipsA1print[$i];
	}
}
$shipsA1print = $ships;

$ships = array();
for($i=0; $i<$t2; $i++){
	if(in_array("a2_".$shipsA2print[$i]['IMO #'], $imos)){
		$ships[] = $shipsA2print[$i];
	}
}
$shipsA2print = $ships;

$ships = array();
for($i=0; $i<$t3; $i++){
	
	if(in_array("a3_".$shipsA3print[$i]['IMO #'], $imos)){
		$ships[] = $shipsA3print[$i];
	}
}
$shipsA3print = $ships;

$ships = array();
for($i=0; $i<$t4; $i++){
	if(in_array("a4_".$shipsA4print[$i]['IMO #'], $imos)){
		$ships[] = $shipsA4print[$i];
	}
}
$shipsA4print = $ships;


$ships = array();
for($i=0; $i<$t5; $i++){
	if(in_array("a5_".$shipsA5print[$i]['IMO #'], $imos)){
		$ships[] = $shipsA5print[$i];
	}
}
$shipsA5print = $ships;

$t = count($shipsA1print);
$t2 = count($shipsA2print);
$t3 = count($shipsA3print);
$t4 = count($shipsA4print);
$t5 = count($shipsA5print);

if($r1[0]['purchase']=="Trial Account (7 Days Trial Account)"){
	?><script>alert('As you are using a \'Trial Account\' you are only allowed to mail, print or export 5 ships to excel, email or print. A Subscription account allows unlimited access and facilities to export, print or email.');</script><?php
}

if($t||$t2||$t3||$t4||$t5){
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
	
	$sql = "SELECT * FROM _sbis_users WHERE id = '".$_SESSION['user']['id']."' LIMIT 1";
	$rows = dbQuery($sql);

	$ext = array('.jpg', '.gif', '.png');
	foreach($ext as $value){
		if( file_exists("../images/user_images/company_".$rows[0]['id'].$value) ){
			$photo1 = "company_".$rows[0]['id'].$value;
		}
	}
	
	$photo1 = empty($photo1) ? 'default.jpg' : $photo1;
	
	echo "<table width='1000px'>
		<tr>
			<td style='border:0px;' width='460'><img src='http://".$_SERVER['HTTP_HOST']."/app/images/logo_ve2.png'></td>
			<td style='border:0px; text-align:right;' width='540'><img src='http://".$_SERVER['HTTP_HOST']."/app/images/user_images/".$photo1."' width='80' alt='photo' border='0' /><br>Sent by <a href='mailto:".$rows[0]['email']."'>".$rows[0]['email']."</a></td>
		</tr>
	</table>
	<div style='text-align:left; padding:15px 5px 5px 5px;'><b>CURRENT DATE/TIME: ".date("d-m-Y")."</b></div>
	<div style='text-align:left; padding:0px 5px 15px;'><b>".$_SESSION['searchcriteria']."</b></div>
	<div style='text-align:left; padding:0px 5px;'>
		<table width='100%' border='0' cellpadding='0' cellspacing='0'>
			<tr>
				<td width='30' style='border:0px;'><img src='http://".$_SERVER['HTTP_HOST']."/app/images/sched.jpg' /></td>
				<td style='border:0px;'><b style='font-size:14px;'>FIXTURE REPORT</b></td>
			</tr>
		</table>
	</div>";
	
	/****************************************************************************************/
	
	if($t){
		if($r1[0]['purchase']=="Trial Account (7 Days Trial Account)" && $t>5){ $t = 5; }
		
		echo "<div style='width:990px; text-align:left; padding:5px; background:#c5dc3b; color:white; margin-top:5px;'>
			<table cellpadding='0' cellspacing='0' width='990px'>
				<tr>
					<td style='border:0px;'><b style='font-size:14px;'>SHIPS WITH AIS DESTINATIONS & ETA</b></td>
				</tr>
			</table>
		</div>
		<table width='1000px' style='border:1px solid #000;'>
			<tr>
				<th width='125px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Name</div></th>
				<th width='100px' style='background:#BCBCBC; color:#333333; text-align:right;'><div style='padding:5px;'>Load Port</div></th>
				<th width='110px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Load ETA</div></th>
				<th width='50px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Hull</div></th>
				<th width='30px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>DWT</div></th>
				<th width='30px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Built</div></th>";
				
				if($_SESSION['user']['dry']==1){
					echo "<th width='180px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Manager / Owner</div></th>";
				}else{
					echo "<th width='180px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Operator</div></th>";
				}
				
				echo "<th width='150px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Category</div></th>
				<th width='80px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Type</div></th>
				<th width='60px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>DRFT</div></th>
				<th width='60px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Speed</div></th>
				<th width='30px' style='background:#BCBCBC; color:#333333; text-align:center;'><div style='padding:5px;'>Flag</div></th>
			</tr>";
			
			for($i=0; $i<$t; $i++){
				$ships = $shipsA1print[$i];
				
				$sql  = "SELECT * FROM `_xvas_shipdata` WHERE `imo`='".$ships['IMO #']."'";
				$xvas = dbQuery($sql);
				$xvas = $xvas[0];
		
				if(!trim($xvas['data'])){
					$sql  = "SELECT * FROM `_xvas_shipdata_dry` WHERE `imo`='".$ships['IMO #']."'";
					$xvas = dbQuery($sql);
					$xvas = $xvas[0];
				}
				
				//HULL TYPE
				$hull_type = getValue($xvas['data'], 'HULL_TYPE');
				if($hull_type=='SINGLE HULL'){ $hull_type = 'SH'; }
				else{ $hull_type = 'DH'; }
				//END OF HULL TYPE
				
				//OPERATOR
				$owner         = getValue($xvas['data'], 'OWNER');
				$manager_owner = getValue($xvas['data'], 'MANAGER_OWNER');
				$manager       = getValue($xvas['data'], 'MANAGER');
				if(trim($owner)){ $operator = $owner; }
				else if(trim($manager_owner)){ $operator = $manager_owner; }
				else if(trim($manager)){ $operator = $manager; }
				else{ $operator = ""; }
				//END OF OPERATOR
				
				//SPEED
				$speed = getValue($xvas['data'], 'SPEED_SERVICE');
				if(trim($speed)){ $speed = number_format($speed, 2); }
				else{ $speed = "13.50"; }
				//END OF SPEED
				
				//FLAG
				$flag     = getValue($xvas['data'], "FLAG");
				$flag_img = getFlagImage($flag);
				//END OF FLAG
				
				echo "<tr style='background:#e5e5e5;'>
					<td>
						<div style='padding:5px;'>
							<table cellpadding='0' cellspacing='0' width='100%'>
								<tr>
									<td width='25' style='border:0px;'><img src='http://".$_SERVER['HTTP_HOST']."/app/image.php?b=1&mx=20&p=".$ships['imageb']."'></td>
									<td class='z_text01' style='border:0px;'><b>".$ships['Ship Name']."</b></td>
								</tr>
							</table>
						</div>
					</td>
					<td style='text-align:right;' class='z_text01'><div style='padding:5px;'>".$ships['LOAD_PORT']."</div></td>
					<td class='z_text01'><div style='padding:5px;'>".$ships['ETA TO LOAD PORT (days)']."</div></td>
					<td class='z_text01'><div style='padding:5px;'>".$hull_type."</div></td>
					<td class='z_text01'><div style='padding:5px;'>".number_format(str_replace("tons", "", getValue($xvas['data'], 'SUMMER_DWT')))."</div></td>
					<td class='z_text01'><div style='padding:5px;'>".getValue($xvas['data'], 'BUILD')."</div></td>
					<td class='z_text01'><div style='padding:5px;'>".$operator."</div></td>
					<td class='z_text01'><div style='padding:5px;'>".getValue($xvas['data'], 'VESSEL_TYPE')."</div></td>
					<td class='z_text01'><div style='padding:5px;'>".$_SESSION['dwt_type']."</div></td>
					<td class='z_text01'><div style='padding:5px;'>".str_replace("m", "", getValue($xvas['data'], 'DRAUGHT'))."</div></td>
					<td class='z_text01'><div style='padding:5px;'>".$speed."</div></td>
					<td style='text-align:center;'><div style='padding:5px;'><img src='http://".$_SERVER['HTTP_HOST']."/app/".$flag_img."' width='22' height='15' ></div></td>
				</tr>
				<tr style='background:#fff;'>
					<td colspan='12'>";
						
						//BROKER UPDATE
						$sql = "SELECT * FROM `_messages` WHERE `imo`='".$ships['IMO #']."' AND type='network' ORDER BY dateadded DESC LIMIT 0,1";
						$nmessage = dbQuery($sql, $link);
						$nmessagearr = unserialize($nmessage[0]['message']);
						
						if($nmessagearr['kind']=="dry"){
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
						}else{
							$openport          = $nmessagearr['openport'];
							$opendate          = $nmessagearr['opendate'];
							$destinationregion = $nmessagearr['destinationregion'];
							$destinationdate   = $nmessagearr['destinationdate'];
							$charterer         = $nmessagearr['charterer'];
							$remarks           = $nmessagearr['remark'];
							$cargotype         = $nmessagearr['cargotype'];
							$status            = $nmessagearr['status'];
							$cbm               = $nmessagearr['cbm'];
							$rate              = $nmessagearr['rate'];
							$tce               = $nmessagearr['tce'];
							$ws                = $nmessagearr['ws'];
						}
						//END OF BROKER UPDATE
						
						//OPERATOR UPDATE
						$sql = "SELECT * FROM `_operators_update` WHERE `imo`='".$ships['IMO #']."' AND type='op_update' ORDER BY dateadded DESC LIMIT 0,1";
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
							if($nmessagearr['kind']=="dry"){
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
							}else{
								if((time()-strtotime($opendate))<(60*60*24*15)){
									echo "<table cellspacing='0' cellpadding='0' border='1' width='990'>
										<tr>
											<th style='background:#BCBCBC; color:#333333; text-align:right;'><div style='padding:5px;'>Open Port</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>ETA</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:right;'><div style='padding:5px;'>Destination</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>ETA</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Charterer</div></th>
										</tr>
										<tr>
											<td style='width:296px; text-align:right;'><div style='padding:5px;'>&nbsp;".$openport."&nbsp;</div></td>
											<td style='width:98px; text-align:left;'><div style='padding:5px;'>&nbsp;".$opendate."&nbsp;</div></td>
											<td style='width:296px; text-align:right;'><div style='padding:5px;'>&nbsp;".$destinationregion."&nbsp;</div></td>
											<td style='width:98px; text-align:left;'><div style='padding:5px;'>&nbsp;".$destinationdate."&nbsp;</div></td>
											<td style='width:202px; text-align:left;'><div style='padding:5px;'>&nbsp;".$charterer."&nbsp;</div></td>
										</tr>
									</table>
									<table cellspacing='0' cellpadding='0' border='1' width='990'>
										<tr>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Cargo Type</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Quantity</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Status</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>CBM</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Rate</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>TCE</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>WS</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Remarks - by: <font color='red'>".$nmessage[0]['user_email']."</font></div></th>
										</tr>
										<tr>
											<td style='width:90px; text-align:left;'><div style='padding:5px;'>&nbsp;".$cargotype."&nbsp;</div></td>
											<td style='width:85px; text-align:left;'><div style='padding:5px;'>&nbsp;".$quantity."&nbsp;</div></td>
											<td style='width:85px; text-align:left;'><div style='padding:5px;'>&nbsp;".$status."&nbsp;</div></td>
											<td style='width:85px; text-align:left;'><div style='padding:5px;'>&nbsp;".$cbm."&nbsp;</div></td>
											<td style='width:85px; text-align:left;'><div style='padding:5px;'>&nbsp;".$rate."&nbsp;</div></td>
											<td style='width:85px; text-align:left;'><div style='padding:5px;'>&nbsp;".$tce."&nbsp;</div></td>
											<td style='width:85px; text-align:left;'><div style='padding:5px;'>&nbsp;".$ws."&nbsp;</div></td>
											<td style='width:390px; text-align:left;'><div style='padding:5px;'>&nbsp;".$remark."&nbsp;</div></td>
										</tr>
									</table>";
								}
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
			
		echo "</table>
		<table cellpadding='0' cellspacing='0' width='1000'>
			<tr>
				<td style='border:0px;'>&nbsp;</td>
			</tr>
		</table>";
	}
	
	/****************************************************************************************/
	
	if($t2){
		if($r1[0]['purchase']=="Trial Account (7 Days Trial Account)" && $t2>5){ $t2 = 5; }
		
		echo "<div style='width:990px; text-align:left; padding:5px; background:#c5dc3b; color:white; margin-top:5px;'>
			<table cellpadding='0' cellspacing='0' width='990px'>
				<tr>
					<td style='border:0px;'><b style='font-size:14px;'>SHIPS WITH AIS DESTINATIONS & ETA (ACRONYMS OR SPELLING ISSUES)</b></td>
				</tr>
			</table>
		</div>
		<table width='1000px' style='border:1px solid #000;'>
			<tr>
				<th width='125px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Name</div></th>
				<th width='100px' style='background:#BCBCBC; color:#333333; text-align:right;'><div style='padding:5px;'>Load Port</div></th>
				<th width='110px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Load ETA</div></th>
				<th width='50px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Hull</div></th>
				<th width='30px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>DWT</div></th>
				<th width='30px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Built</div></th>";
				
				if($_SESSION['user']['dry']==1){
					echo "<th width='180px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Manager / Owner</div></th>";
				}else{
					echo "<th width='180px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Operator</div></th>";
				}
				
				echo "<th width='150px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Category</div></th>
				<th width='80px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Type</div></th>
				<th width='60px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>DRFT</div></th>
				<th width='60px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Speed</div></th>
				<th width='30px' style='background:#BCBCBC; color:#333333; text-align:center;'><div style='padding:5px;'>Flag</div></th>
			</tr>";
			
			for($i=0; $i<$t2; $i++){
				$ships = $shipsA2print[$i];
				
				$sql  = "SELECT * FROM `_xvas_shipdata` WHERE `imo`='".$ships['IMO #']."'";
				$xvas = dbQuery($sql);
				$xvas = $xvas[0];
		
				if(!trim($xvas['data'])){
					$sql  = "SELECT * FROM `_xvas_shipdata_dry` WHERE `imo`='".$ships['IMO #']."'";
					$xvas = dbQuery($sql);
					$xvas = $xvas[0];
				}
				
				//HULL TYPE
				$hull_type = getValue($xvas['data'], 'HULL_TYPE');
				if($hull_type=='SINGLE HULL'){ $hull_type = 'SH'; }
				else{ $hull_type = 'DH'; }
				//END OF HULL TYPE
				
				//OPERATOR
				$owner         = getValue($xvas['data'], 'OWNER');
				$manager_owner = getValue($xvas['data'], 'MANAGER_OWNER');
				$manager       = getValue($xvas['data'], 'MANAGER');
				if(trim($owner)){ $operator = $owner; }
				else if(trim($manager_owner)){ $operator = $manager_owner; }
				else if(trim($manager)){ $operator = $manager; }
				else{ $operator = ""; }
				//END OF OPERATOR
				
				//SPEED
				$speed = getValue($xvas['data'], 'SPEED_SERVICE');
				if(trim($speed)){ $speed = number_format($speed, 2); }
				else{ $speed = "13.50"; }
				//END OF SPEED
				
				//FLAG
				$flag     = getValue($xvas['data'], "FLAG");
				$flag_img = getFlagImage($flag);
				//END OF FLAG
				
				echo "<tr style='background:#e5e5e5;'>
					<td>
						<div style='padding:5px;'>
							<table cellpadding='0' cellspacing='0' width='100%'>
								<tr>
									<td width='25' style='border:0px;'><img src='http://".$_SERVER['HTTP_HOST']."/app/image.php?b=1&mx=20&p=".$ships['imageb']."'></td>
									<td class='z_text01' style='border:0px;'><b>".$ships['Ship Name']."</b></td>
								</tr>
							</table>
						</div>
					</td>
					<td style='text-align:right;' class='z_text01'><div style='padding:5px;'>".$ships['LOAD_PORT']."</div></td>
					<td class='z_text01'><div style='padding:5px;'>".$ships['ETA TO LOAD PORT (days)']."</div></td>
					<td class='z_text01'><div style='padding:5px;'>".$hull_type."</div></td>
					<td class='z_text01'><div style='padding:5px;'>".number_format(str_replace("tons", "", getValue($xvas['data'], 'SUMMER_DWT')))."</div></td>
					<td class='z_text01'><div style='padding:5px;'>".getValue($xvas['data'], 'BUILD')."</div></td>
					<td class='z_text01'><div style='padding:5px;'>".$operator."</div></td>
					<td class='z_text01'><div style='padding:5px;'>".getValue($xvas['data'], 'VESSEL_TYPE')."</div></td>
					<td class='z_text01'><div style='padding:5px;'>".$_SESSION['dwt_type']."</div></td>
					<td class='z_text01'><div style='padding:5px;'>".str_replace("m", "", getValue($xvas['data'], 'DRAUGHT'))."</div></td>
					<td class='z_text01'><div style='padding:5px;'>".$speed."</div></td>
					<td style='text-align:center;'><div style='padding:5px;'><img src='http://".$_SERVER['HTTP_HOST']."/app/".$flag_img."' width='22' height='15' ></div></td>
				</tr>
				<tr style='background:#fff;'>
					<td colspan='12'>";
						
						//BROKER UPDATE
						$sql = "SELECT * FROM `_messages` WHERE `imo`='".$ships['IMO #']."' AND type='network' ORDER BY dateadded DESC LIMIT 0,1";
						$nmessage = dbQuery($sql, $link);
						$nmessagearr = unserialize($nmessage[0]['message']);
						
						if($nmessagearr['kind']=="dry"){
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
						}else{
							$openport          = $nmessagearr['openport'];
							$opendate          = $nmessagearr['opendate'];
							$destinationregion = $nmessagearr['destinationregion'];
							$destinationdate   = $nmessagearr['destinationdate'];
							$charterer         = $nmessagearr['charterer'];
							$remarks           = $nmessagearr['remark'];
							$cargotype         = $nmessagearr['cargotype'];
							$status            = $nmessagearr['status'];
							$cbm               = $nmessagearr['cbm'];
							$rate              = $nmessagearr['rate'];
							$tce               = $nmessagearr['tce'];
							$ws                = $nmessagearr['ws'];
						}
						//END OF BROKER UPDATE
						
						//OPERATOR UPDATE
						$sql = "SELECT * FROM `_operators_update` WHERE `imo`='".$ships['IMO #']."' AND type='op_update' ORDER BY dateadded DESC LIMIT 0,1";
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
							if($nmessagearr['kind']=="dry"){
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
							}else{
								if((time()-strtotime($opendate))<(60*60*24*15)){
									echo "<table cellspacing='0' cellpadding='0' border='1' width='990'>
										<tr>
											<th style='background:#BCBCBC; color:#333333; text-align:right;'><div style='padding:5px;'>Open Port</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>ETA</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:right;'><div style='padding:5px;'>Destination</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>ETA</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Charterer</div></th>
										</tr>
										<tr>
											<td style='width:296px; text-align:right;'><div style='padding:5px;'>&nbsp;".$openport."&nbsp;</div></td>
											<td style='width:98px; text-align:left;'><div style='padding:5px;'>&nbsp;".$opendate."&nbsp;</div></td>
											<td style='width:296px; text-align:right;'><div style='padding:5px;'>&nbsp;".$destinationregion."&nbsp;</div></td>
											<td style='width:98px; text-align:left;'><div style='padding:5px;'>&nbsp;".$destinationdate."&nbsp;</div></td>
											<td style='width:202px; text-align:left;'><div style='padding:5px;'>&nbsp;".$charterer."&nbsp;</div></td>
										</tr>
									</table>
									<table cellspacing='0' cellpadding='0' border='1' width='990'>
										<tr>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Cargo Type</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Quantity</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Status</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>CBM</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Rate</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>TCE</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>WS</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Remarks - by: <font color='red'>".$nmessage[0]['user_email']."</font></div></th>
										</tr>
										<tr>
											<td style='width:90px; text-align:left;'><div style='padding:5px;'>&nbsp;".$cargotype."&nbsp;</div></td>
											<td style='width:85px; text-align:left;'><div style='padding:5px;'>&nbsp;".$quantity."&nbsp;</div></td>
											<td style='width:85px; text-align:left;'><div style='padding:5px;'>&nbsp;".$status."&nbsp;</div></td>
											<td style='width:85px; text-align:left;'><div style='padding:5px;'>&nbsp;".$cbm."&nbsp;</div></td>
											<td style='width:85px; text-align:left;'><div style='padding:5px;'>&nbsp;".$rate."&nbsp;</div></td>
											<td style='width:85px; text-align:left;'><div style='padding:5px;'>&nbsp;".$tce."&nbsp;</div></td>
											<td style='width:85px; text-align:left;'><div style='padding:5px;'>&nbsp;".$ws."&nbsp;</div></td>
											<td style='width:390px; text-align:left;'><div style='padding:5px;'>&nbsp;".$remark."&nbsp;</div></td>
										</tr>
									</table>";
								}
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
			
		echo "</table>
		<table cellpadding='0' cellspacing='0' width='1000'>
			<tr>
				<td style='border:0px;'>&nbsp;</td>
			</tr>
		</table>";
	}
	
	/****************************************************************************************/
	
	if($t3){
		if($r1[0]['purchase']=="Trial Account (7 Days Trial Account)" && $t3>5){ $t3 = 5; }
		
		echo "<div style='width:990px; text-align:left; padding:5px; background:#ffb83a; color:white; margin-top:5px;'>
			<table cellpadding='0' cellspacing='0' width='990px'>
				<tr>
					<td style='border:0px;'><b style='font-size:14px;'>SHIPS WITH CONFIRMED OPEN PORT USING BROKERSINTELLIGENCE</b></td>
				</tr>
			</table>
		</div>
		<table width='1000px' style='border:1px solid #000;'>
			<tr>
				<th width='125px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Name</div></th>
				<th width='100px' style='background:#BCBCBC; color:#333333; text-align:right;'><div style='padding:5px;'>Open Port</div></th>
				<th width='110px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Open ETA</div></th>
				<th width='50px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Hull</div></th>
				<th width='30px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>DWT</div></th>
				<th width='30px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Built</div></th>";
				
				if($_SESSION['user']['dry']==1){
					echo "<th width='180px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Manager / Owner</div></th>";
				}else{
					echo "<th width='180px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Operator</div></th>";
				}
				
				echo "<th width='150px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Category</div></th>
				<th width='80px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Type</div></th>
				<th width='60px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>DRFT</div></th>
				<th width='60px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Speed</div></th>
				<th width='30px' style='background:#BCBCBC; color:#333333; text-align:center;'><div style='padding:5px;'>Flag</div></th>
			</tr>";
			
			for($i=0; $i<$t3; $i++){
				$ships = $shipsA3print[$i];
				
				$sql  = "SELECT * FROM `_xvas_shipdata` WHERE `imo`='".$ships['IMO #']."'";
				$xvas = dbQuery($sql);
				$xvas = $xvas[0];
		
				if(!trim($xvas['data'])){
					$sql  = "SELECT * FROM `_xvas_shipdata_dry` WHERE `imo`='".$ships['IMO #']."'";
					$xvas = dbQuery($sql);
					$xvas = $xvas[0];
				}
				
				//HULL TYPE
				$hull_type = getValue($xvas['data'], 'HULL_TYPE');
				if($hull_type=='SINGLE HULL'){ $hull_type = 'SH'; }
				else{ $hull_type = 'DH'; }
				//END OF HULL TYPE
				
				//OPERATOR
				$owner         = getValue($xvas['data'], 'OWNER');
				$manager_owner = getValue($xvas['data'], 'MANAGER_OWNER');
				$manager       = getValue($xvas['data'], 'MANAGER');
				if(trim($owner)){ $operator = $owner; }
				else if(trim($manager_owner)){ $operator = $manager_owner; }
				else if(trim($manager)){ $operator = $manager; }
				else{ $operator = ""; }
				//END OF OPERATOR
				
				//SPEED
				$speed = getValue($xvas['data'], 'SPEED_SERVICE');
				if(trim($speed)){ $speed = number_format($speed, 2); }
				else{ $speed = "13.50"; }
				//END OF SPEED
				
				//FLAG
				$flag     = getValue($xvas['data'], "FLAG");
				$flag_img = getFlagImage($flag);
				//END OF FLAG
				
				//BROKER UPDATE
				$sql = "SELECT * FROM `_messages` WHERE `imo`='".$ships['IMO #']."' AND type='network' ORDER BY dateadded DESC LIMIT 0,1";
				$nmessage = dbQuery($sql, $link);
				$nmessagearr = unserialize($nmessage[0]['message']);
				
				if($nmessagearr['kind']=="dry"){
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
					
					$b_port = $nmessagearr['dely'];
					$b_date = $nmessagearr['delydate_from'];
				}else{
					$openport          = $nmessagearr['openport'];
					$opendate          = $nmessagearr['opendate'];
					$destinationregion = $nmessagearr['destinationregion'];
					$destinationdate   = $nmessagearr['destinationdate'];
					$charterer         = $nmessagearr['charterer'];
					$remarks           = $nmessagearr['remark'];
					$cargotype         = $nmessagearr['cargotype'];
					$status            = $nmessagearr['status'];
					$cbm               = $nmessagearr['cbm'];
					$rate              = $nmessagearr['rate'];
					$tce               = $nmessagearr['tce'];
					$ws                = $nmessagearr['ws'];
					
					$b_port = $nmessagearr['openport'];
					$b_date = $nmessagearr['opendate'];
				}
				//END OF BROKER UPDATE
				
				echo "<tr style='background:#e5e5e5;'>
					<td>
						<div style='padding:5px;'>
							<table cellpadding='0' cellspacing='0' width='100%'>
								<tr>
									<td width='25' style='border:0px;'><img src='http://".$_SERVER['HTTP_HOST']."/app/image.php?b=1&mx=20&p=".$ships['imageb']."'></td>
									<td class='z_text01' style='border:0px;'><b>".$ships['Ship Name']."</b></td>
								</tr>
							</table>
						</div>
					</td>
					<td style='text-align:right;' class='z_text01'><div style='padding:5px;'>".$b_port."</div></td>
					<td class='z_text01'><div style='padding:5px;'>".$b_date."</div></td>
					<td class='z_text01'><div style='padding:5px;'>".$hull_type."</div></td>
					<td class='z_text01'><div style='padding:5px;'>".number_format(str_replace("tons", "", getValue($xvas['data'], 'SUMMER_DWT')))."</div></td>
					<td class='z_text01'><div style='padding:5px;'>".getValue($xvas['data'], 'BUILD')."</div></td>
					<td class='z_text01'><div style='padding:5px;'>".$operator."</div></td>
					<td class='z_text01'><div style='padding:5px;'>".getValue($xvas['data'], 'VESSEL_TYPE')."</div></td>
					<td class='z_text01'><div style='padding:5px;'>".$_SESSION['dwt_type']."</div></td>
					<td class='z_text01'><div style='padding:5px;'>".str_replace("m", "", getValue($xvas['data'], 'DRAUGHT'))."</div></td>
					<td class='z_text01'><div style='padding:5px;'>".$speed."</div></td>
					<td style='text-align:center;'><div style='padding:5px;'><img src='http://".$_SERVER['HTTP_HOST']."/app/".$flag_img."' width='22' height='15' ></div></td>
				</tr>
				<tr style='background:#fff;'>
					<td colspan='12'>";
						
						if($nmessagearr){
							if($nmessagearr['kind']=="dry"){
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
							}else{
								if((time()-strtotime($opendate))<(60*60*24*15)){
									echo "<table cellspacing='0' cellpadding='0' border='1' width='990'>
										<tr>
											<th style='background:#BCBCBC; color:#333333; text-align:right;'><div style='padding:5px;'>Open Port</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>ETA</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:right;'><div style='padding:5px;'>Destination</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>ETA</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Charterer</div></th>
										</tr>
										<tr>
											<td style='width:296px; text-align:right;'><div style='padding:5px;'>&nbsp;".$openport."&nbsp;</div></td>
											<td style='width:98px; text-align:left;'><div style='padding:5px;'>&nbsp;".$opendate."&nbsp;</div></td>
											<td style='width:296px; text-align:right;'><div style='padding:5px;'>&nbsp;".$destinationregion."&nbsp;</div></td>
											<td style='width:98px; text-align:left;'><div style='padding:5px;'>&nbsp;".$destinationdate."&nbsp;</div></td>
											<td style='width:202px; text-align:left;'><div style='padding:5px;'>&nbsp;".$charterer."&nbsp;</div></td>
										</tr>
									</table>
									<table cellspacing='0' cellpadding='0' border='1' width='990'>
										<tr>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Cargo Type</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Quantity</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Status</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>CBM</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Rate</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>TCE</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>WS</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Remarks - by: <font color='red'>".$nmessage[0]['user_email']."</font></div></th>
										</tr>
										<tr>
											<td style='width:90px; text-align:left;'><div style='padding:5px;'>&nbsp;".$cargotype."&nbsp;</div></td>
											<td style='width:85px; text-align:left;'><div style='padding:5px;'>&nbsp;".$quantity."&nbsp;</div></td>
											<td style='width:85px; text-align:left;'><div style='padding:5px;'>&nbsp;".$status."&nbsp;</div></td>
											<td style='width:85px; text-align:left;'><div style='padding:5px;'>&nbsp;".$cbm."&nbsp;</div></td>
											<td style='width:85px; text-align:left;'><div style='padding:5px;'>&nbsp;".$rate."&nbsp;</div></td>
											<td style='width:85px; text-align:left;'><div style='padding:5px;'>&nbsp;".$tce."&nbsp;</div></td>
											<td style='width:85px; text-align:left;'><div style='padding:5px;'>&nbsp;".$ws."&nbsp;</div></td>
											<td style='width:390px; text-align:left;'><div style='padding:5px;'>&nbsp;".$remark."&nbsp;</div></td>
										</tr>
									</table>";
								}
							}
						}
					
					echo "</td>
				</tr>";
			}
			
		echo "</table>
		<table cellpadding='0' cellspacing='0' width='1000'>
			<tr>
				<td style='border:0px;'>&nbsp;</td>
			</tr>
		</table>";
	}
	
	/****************************************************************************************/
	
	if($t4){
		if($r1[0]['purchase']=="Trial Account (7 Days Trial Account)" && $t4>5){ $t4 = 5; }
		
		echo "<div style='width:990px; text-align:left; padding:5px; background:#ffb83a; color:white; margin-top:5px;'>
			<table cellpadding='0' cellspacing='0' width='990px'>
				<tr>
					<td style='border:0px;'><b style='font-size:14px;'>SHIPS WITH LAST KNOWN POSITIONS USING BROKERSINTELLIGENCE</b></td>
				</tr>
			</table>
		</div>
		<table width='1000px' style='border:1px solid #000;'>
			<tr>
				<th width='125px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Name</div></th>
				<th width='100px' style='background:#BCBCBC; color:#333333; text-align:right;'><div style='padding:5px;'>Open Port</div></th>
				<th width='110px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Open ETA</div></th>
				<th width='50px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Hull</div></th>
				<th width='30px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>DWT</div></th>
				<th width='30px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Built</div></th>";
				
				if($_SESSION['user']['dry']==1){
					echo "<th width='180px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Manager / Owner</div></th>";
				}else{
					echo "<th width='180px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Operator</div></th>";
				}
				
				echo "<th width='150px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Category</div></th>
				<th width='80px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Type</div></th>
				<th width='60px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>DRFT</div></th>
				<th width='60px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Speed</div></th>
				<th width='30px' style='background:#BCBCBC; color:#333333; text-align:center;'><div style='padding:5px;'>Flag</div></th>
			</tr>";
			
			for($i=0; $i<$t4; $i++){
				$ships = $shipsA4print[$i];
				
				$sql  = "SELECT * FROM `_xvas_shipdata` WHERE `imo`='".$ships['IMO #']."'";
				$xvas = dbQuery($sql);
				$xvas = $xvas[0];
		
				if(!trim($xvas['data'])){
					$sql  = "SELECT * FROM `_xvas_shipdata_dry` WHERE `imo`='".$ships['IMO #']."'";
					$xvas = dbQuery($sql);
					$xvas = $xvas[0];
				}
				
				//HULL TYPE
				$hull_type = getValue($xvas['data'], 'HULL_TYPE');
				if($hull_type=='SINGLE HULL'){ $hull_type = 'SH'; }
				else{ $hull_type = 'DH'; }
				//END OF HULL TYPE
				
				//OPERATOR
				$owner         = getValue($xvas['data'], 'OWNER');
				$manager_owner = getValue($xvas['data'], 'MANAGER_OWNER');
				$manager       = getValue($xvas['data'], 'MANAGER');
				if(trim($owner)){ $operator = $owner; }
				else if(trim($manager_owner)){ $operator = $manager_owner; }
				else if(trim($manager)){ $operator = $manager; }
				else{ $operator = ""; }
				//END OF OPERATOR
				
				//SPEED
				$speed = getValue($xvas['data'], 'SPEED_SERVICE');
				if(trim($speed)){ $speed = number_format($speed, 2); }
				else{ $speed = "13.50"; }
				//END OF SPEED
				
				//FLAG
				$flag     = getValue($xvas['data'], "FLAG");
				$flag_img = getFlagImage($flag);
				//END OF FLAG
				
				//BROKER UPDATE
				$sql = "SELECT * FROM `_messages` WHERE `imo`='".$ships['IMO #']."' AND type='network' ORDER BY dateadded DESC LIMIT 0,1";
				$nmessage = dbQuery($sql, $link);
				$nmessagearr = unserialize($nmessage[0]['message']);
				
				if($nmessagearr['kind']=="dry"){
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
					
					$b_port = $nmessagearr['dely'];
					$b_date = $nmessagearr['delydate_from'];
				}else{
					$openport          = $nmessagearr['openport'];
					$opendate          = $nmessagearr['opendate'];
					$destinationregion = $nmessagearr['destinationregion'];
					$destinationdate   = $nmessagearr['destinationdate'];
					$charterer         = $nmessagearr['charterer'];
					$remarks           = $nmessagearr['remark'];
					$cargotype         = $nmessagearr['cargotype'];
					$status            = $nmessagearr['status'];
					$cbm               = $nmessagearr['cbm'];
					$rate              = $nmessagearr['rate'];
					$tce               = $nmessagearr['tce'];
					$ws                = $nmessagearr['ws'];
					
					$b_port = $nmessagearr['openport'];
					$b_date = $nmessagearr['opendate'];
				}
				//END OF BROKER UPDATE
				
				echo "<tr style='background:#e5e5e5;'>
					<td>
						<div style='padding:5px;'>
							<table cellpadding='0' cellspacing='0' width='100%'>
								<tr>
									<td width='25' style='border:0px;'><img src='http://".$_SERVER['HTTP_HOST']."/app/image.php?b=1&mx=20&p=".$ships['imageb']."'></td>
									<td class='z_text01' style='border:0px;'><b>".$ships['Ship Name']."</b></td>
								</tr>
							</table>
						</div>
					</td>
					<td style='text-align:right;' class='z_text01'><div style='padding:5px;'>".$b_port."</div></td>
					<td class='z_text01'><div style='padding:5px;'>".$b_date."</div></td>
					<td class='z_text01'><div style='padding:5px;'>".$hull_type."</div></td>
					<td class='z_text01'><div style='padding:5px;'>".number_format(str_replace("tons", "", getValue($xvas['data'], 'SUMMER_DWT')))."</div></td>
					<td class='z_text01'><div style='padding:5px;'>".getValue($xvas['data'], 'BUILD')."</div></td>
					<td class='z_text01'><div style='padding:5px;'>".$operator."</div></td>
					<td class='z_text01'><div style='padding:5px;'>".getValue($xvas['data'], 'VESSEL_TYPE')."</div></td>
					<td class='z_text01'><div style='padding:5px;'>".$_SESSION['dwt_type']."</div></td>
					<td class='z_text01'><div style='padding:5px;'>".str_replace("m", "", getValue($xvas['data'], 'DRAUGHT'))."</div></td>
					<td class='z_text01'><div style='padding:5px;'>".$speed."</div></td>
					<td style='text-align:center;'><div style='padding:5px;'><img src='http://".$_SERVER['HTTP_HOST']."/app/".$flag_img."' width='22' height='15' ></div></td>
				</tr>
				<tr style='background:#fff;'>
					<td colspan='12'>";
						
						if($nmessagearr){
							if($nmessagearr['kind']=="dry"){
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
							}else{
								if((time()-strtotime($opendate))<(60*60*24*15)){
									echo "<table cellspacing='0' cellpadding='0' border='1' width='990'>
										<tr>
											<th style='background:#BCBCBC; color:#333333; text-align:right;'><div style='padding:5px;'>Open Port</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>ETA</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:right;'><div style='padding:5px;'>Destination</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>ETA</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Charterer</div></th>
										</tr>
										<tr>
											<td style='width:296px; text-align:right;'><div style='padding:5px;'>&nbsp;".$openport."&nbsp;</div></td>
											<td style='width:98px; text-align:left;'><div style='padding:5px;'>&nbsp;".$opendate."&nbsp;</div></td>
											<td style='width:296px; text-align:right;'><div style='padding:5px;'>&nbsp;".$destinationregion."&nbsp;</div></td>
											<td style='width:98px; text-align:left;'><div style='padding:5px;'>&nbsp;".$destinationdate."&nbsp;</div></td>
											<td style='width:202px; text-align:left;'><div style='padding:5px;'>&nbsp;".$charterer."&nbsp;</div></td>
										</tr>
									</table>
									<table cellspacing='0' cellpadding='0' border='1' width='990'>
										<tr>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Cargo Type</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Quantity</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Status</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>CBM</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Rate</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>TCE</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>WS</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Remarks - by: <font color='red'>".$nmessage[0]['user_email']."</font></div></th>
										</tr>
										<tr>
											<td style='width:90px; text-align:left;'><div style='padding:5px;'>&nbsp;".$cargotype."&nbsp;</div></td>
											<td style='width:85px; text-align:left;'><div style='padding:5px;'>&nbsp;".$quantity."&nbsp;</div></td>
											<td style='width:85px; text-align:left;'><div style='padding:5px;'>&nbsp;".$status."&nbsp;</div></td>
											<td style='width:85px; text-align:left;'><div style='padding:5px;'>&nbsp;".$cbm."&nbsp;</div></td>
											<td style='width:85px; text-align:left;'><div style='padding:5px;'>&nbsp;".$rate."&nbsp;</div></td>
											<td style='width:85px; text-align:left;'><div style='padding:5px;'>&nbsp;".$tce."&nbsp;</div></td>
											<td style='width:85px; text-align:left;'><div style='padding:5px;'>&nbsp;".$ws."&nbsp;</div></td>
											<td style='width:390px; text-align:left;'><div style='padding:5px;'>&nbsp;".$remark."&nbsp;</div></td>
										</tr>
									</table>";
								}
							}
						}
					
					echo "</td>
				</tr>";
			}
			
		echo "</table>
		<table cellpadding='0' cellspacing='0' width='1000'>
			<tr>
				<td style='border:0px;'>&nbsp;</td>
			</tr>
		</table>";
	}
	
	/****************************************************************************************/
	
	if($t5){
		if($r1[0]['purchase']=="Trial Account (7 Days Trial Account)" && $t5>5){ $t5 = 5; }
		
		echo "<div style='width:990px; text-align:left; padding:5px; background:#ffb83a; color:white; margin-top:5px;'>
			<table cellpadding='0' cellspacing='0' width='990px'>
				<tr>
					<td style='border:0px;'><b style='font-size:14px;'>SHIPS FOUND USING BROKERSINTELLIGENCE</b></td>
				</tr>
			</table>
		</div>
		<table width='1000px' style='border:1px solid #000;'>
			<tr>
				<th width='125px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Name</div></th>
				<th width='100px' style='background:#BCBCBC; color:#333333; text-align:right;'><div style='padding:5px;'>Open Port</div></th>
				<th width='110px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Open ETA</div></th>
				<th width='50px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Hull</div></th>
				<th width='30px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>DWT</div></th>
				<th width='30px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Built</div></th>";
				
				if($_SESSION['user']['dry']==1){
					echo "<th width='180px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Manager / Owner</div></th>";
				}else{
					echo "<th width='180px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Operator</div></th>";
				}
				
				echo "<th width='150px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Category</div></th>
				<th width='80px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Type</div></th>
				<th width='60px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>DRFT</div></th>
				<th width='60px' style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Speed</div></th>
				<th width='30px' style='background:#BCBCBC; color:#333333; text-align:center;'><div style='padding:5px;'>Flag</div></th>
			</tr>";
			
			for($i=0; $i<$t5; $i++){
				$ships = $shipsA5print[$i];
				
				$sql  = "SELECT * FROM `_xvas_shipdata` WHERE `imo`='".$ships['IMO #']."'";
				$xvas = dbQuery($sql);
				$xvas = $xvas[0];
		
				if(!trim($xvas['data'])){
					$sql  = "SELECT * FROM `_xvas_shipdata_dry` WHERE `imo`='".$ships['IMO #']."'";
					$xvas = dbQuery($sql);
					$xvas = $xvas[0];
				}
				
				//HULL TYPE
				$hull_type = getValue($xvas['data'], 'HULL_TYPE');
				if($hull_type=='SINGLE HULL'){ $hull_type = 'SH'; }
				else{ $hull_type = 'DH'; }
				//END OF HULL TYPE
				
				//OPERATOR
				$owner         = getValue($xvas['data'], 'OWNER');
				$manager_owner = getValue($xvas['data'], 'MANAGER_OWNER');
				$manager       = getValue($xvas['data'], 'MANAGER');
				if(trim($owner)){ $operator = $owner; }
				else if(trim($manager_owner)){ $operator = $manager_owner; }
				else if(trim($manager)){ $operator = $manager; }
				else{ $operator = ""; }
				//END OF OPERATOR
				
				//SPEED
				$speed = getValue($xvas['data'], 'SPEED_SERVICE');
				if(trim($speed)){ $speed = number_format($speed, 2); }
				else{ $speed = "13.50"; }
				//END OF SPEED
				
				//FLAG
				$flag     = getValue($xvas['data'], "FLAG");
				$flag_img = getFlagImage($flag);
				//END OF FLAG
				
				//BROKER UPDATE
				$sql = "SELECT * FROM `_messages` WHERE `imo`='".$ships['IMO #']."' AND type='network' ORDER BY dateadded DESC LIMIT 0,1";
				$nmessage = dbQuery($sql, $link);
				$nmessagearr = unserialize($nmessage[0]['message']);
				
				if($nmessagearr['kind']=="dry"){
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
					
					$b_port = $nmessagearr['dely'];
					$b_date = $nmessagearr['delydate_from'];
				}else{
					$openport          = $nmessagearr['openport'];
					$opendate          = $nmessagearr['opendate'];
					$destinationregion = $nmessagearr['destinationregion'];
					$destinationdate   = $nmessagearr['destinationdate'];
					$charterer         = $nmessagearr['charterer'];
					$remarks           = $nmessagearr['remark'];
					$cargotype         = $nmessagearr['cargotype'];
					$status            = $nmessagearr['status'];
					$cbm               = $nmessagearr['cbm'];
					$rate              = $nmessagearr['rate'];
					$tce               = $nmessagearr['tce'];
					$ws                = $nmessagearr['ws'];
					
					$b_port = $nmessagearr['openport'];
					$b_date = $nmessagearr['opendate'];
				}
				//END OF BROKER UPDATE
				
				echo "<tr style='background:#e5e5e5;'>
					<td>
						<div style='padding:5px;'>
							<table cellpadding='0' cellspacing='0' width='100%'>
								<tr>
									<td width='25' style='border:0px;'><img src='http://".$_SERVER['HTTP_HOST']."/app/image.php?b=1&mx=20&p=".$ships['imageb']."'></td>
									<td class='z_text01' style='border:0px;'><b>".$ships['Ship Name']."</b></td>
								</tr>
							</table>
						</div>
					</td>
					<td style='text-align:right;' class='z_text01'><div style='padding:5px;'>".$b_port."</div></td>
					<td class='z_text01'><div style='padding:5px;'>".$b_date."</div></td>
					<td class='z_text01'><div style='padding:5px;'>".$hull_type."</div></td>
					<td class='z_text01'><div style='padding:5px;'>".number_format(str_replace("tons", "", getValue($xvas['data'], 'SUMMER_DWT')))."</div></td>
					<td class='z_text01'><div style='padding:5px;'>".getValue($xvas['data'], 'BUILD')."</div></td>
					<td class='z_text01'><div style='padding:5px;'>".$operator."</div></td>
					<td class='z_text01'><div style='padding:5px;'>".getValue($xvas['data'], 'VESSEL_TYPE')."</div></td>
					<td class='z_text01'><div style='padding:5px;'>".$_SESSION['dwt_type']."</div></td>
					<td class='z_text01'><div style='padding:5px;'>".str_replace("m", "", getValue($xvas['data'], 'DRAUGHT'))."</div></td>
					<td class='z_text01'><div style='padding:5px;'>".$speed."</div></td>
					<td style='text-align:center;'><div style='padding:5px;'><img src='http://".$_SERVER['HTTP_HOST']."/app/".$flag_img."' width='22' height='15' ></div></td>
				</tr>
				<tr style='background:#fff;'>
					<td colspan='12'>";
						
						if($nmessagearr){
							if($nmessagearr['kind']=="dry"){
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
							}else{
								if((time()-strtotime($opendate))<(60*60*24*15)){
									echo "<table cellspacing='0' cellpadding='0' border='1' width='990'>
										<tr>
											<th style='background:#BCBCBC; color:#333333; text-align:right;'><div style='padding:5px;'>Open Port</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>ETA</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:right;'><div style='padding:5px;'>Destination</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>ETA</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Charterer</div></th>
										</tr>
										<tr>
											<td style='width:296px; text-align:right;'><div style='padding:5px;'>&nbsp;".$openport."&nbsp;</div></td>
											<td style='width:98px; text-align:left;'><div style='padding:5px;'>&nbsp;".$opendate."&nbsp;</div></td>
											<td style='width:296px; text-align:right;'><div style='padding:5px;'>&nbsp;".$destinationregion."&nbsp;</div></td>
											<td style='width:98px; text-align:left;'><div style='padding:5px;'>&nbsp;".$destinationdate."&nbsp;</div></td>
											<td style='width:202px; text-align:left;'><div style='padding:5px;'>&nbsp;".$charterer."&nbsp;</div></td>
										</tr>
									</table>
									<table cellspacing='0' cellpadding='0' border='1' width='990'>
										<tr>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Cargo Type</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Quantity</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Status</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>CBM</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Rate</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>TCE</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>WS</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Remarks - by: <font color='red'>".$nmessage[0]['user_email']."</font></div></th>
										</tr>
										<tr>
											<td style='width:90px; text-align:left;'><div style='padding:5px;'>&nbsp;".$cargotype."&nbsp;</div></td>
											<td style='width:85px; text-align:left;'><div style='padding:5px;'>&nbsp;".$quantity."&nbsp;</div></td>
											<td style='width:85px; text-align:left;'><div style='padding:5px;'>&nbsp;".$status."&nbsp;</div></td>
											<td style='width:85px; text-align:left;'><div style='padding:5px;'>&nbsp;".$cbm."&nbsp;</div></td>
											<td style='width:85px; text-align:left;'><div style='padding:5px;'>&nbsp;".$rate."&nbsp;</div></td>
											<td style='width:85px; text-align:left;'><div style='padding:5px;'>&nbsp;".$tce."&nbsp;</div></td>
											<td style='width:85px; text-align:left;'><div style='padding:5px;'>&nbsp;".$ws."&nbsp;</div></td>
											<td style='width:390px; text-align:left;'><div style='padding:5px;'>&nbsp;".$remark."&nbsp;</div></td>
										</tr>
									</table>";
								}
							}
						}
					
					echo "</td>
				</tr>";
			}
			
		echo "</table>
		<table cellpadding='0' cellspacing='0' width='1000'>
			<tr>
				<td style='border:0px;'>&nbsp;</td>
			</tr>
		</table>";
	}
	
	/****************************************************************************************/
	
}

echo "<table cellpadding='0' cellspacing='0' width='1000px'>
	<tr>
		<td style='border:0px; text-align:right;'>Powered by <img src='http://".$_SERVER['HTTP_HOST']."/app/images/logo_ve2.png' width='50'></td>
	</tr>
</table>";
?>
<script>
window.print();
</script>