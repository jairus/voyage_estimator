<?php
echo "<form id='fixtures' method='POST' style='margin:0px;' action='fixtures.php'>";
echo "<input type='hidden' name='searchtabdata' id='searchtabdata'>";
echo "<div style='text-align:left; padding:5px;'><b>CURRENT DATE/TIME: ".date("M j, Y G:i e", time())."</b></div>";

//T1
if($t){
	echo "<div style='width:990px; text-align:left; padding:5px; background:#c5dc3b; color:white; margin-top:5px;'>
		<table cellpadding='0' cellspacing='0' width='990px'>
			<tr>
				<td><b style='font-size:14px;'>SHIPS WITH CONFIRMED OPEN PORT USING AIS SHORESEARCH</b></td>
				<td align='right' style='text-align:right; vertical-align:top'>
					<a class='clickable' onclick=\"csvIt1('fixture')\"><img src='images/csv.jpg'></a>
					<a class='clickable' onclick=\"printIt1('fixture')\"><img src='images/print.jpg'></a>
					<a class='clickable' onclick=\"mailIt1('fixture')\"><img src='images/email_small.jpg'></a>
					<a href='#params'><img style='border:0px' src='images/up_icon.png' alt='back to top' title='back to top'></a>
				</td>
			</tr>
		</table>
	</div>
	<table id='pgreens11' width='1000px' style='border:1px solid #000;'>
		<tr>
			<th width='20px' style='text-align:center; background-color:#ccc;'><div style='padding:5px;'><input type='checkbox' onclick=\"checkAll('pgreens11', this)\" ></div></th>
			<th width='20px' style='background-color:#ccc; text-align:center;'><div style='padding:5px;'><img src='images/icon_book.png' border='0' /></div></th>
			<th width='125px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Name</div></th>
			<th width='100px' style='background:#BCBCBC; color:#333333; text-align:right;'><div style='padding:5px;'>Load Port</div></th>
			<th width='110px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Load ETA</div></th>
			<th width='50px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Hull</div></th>
			<th width='30px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>DWT</div></th>
			<th width='30px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Built</div></th>";
			
			if($_SESSION['user']['dry']==1 || $_SESSION['user']['dry']==2){
				echo "<th width='180px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Manager / Owner</div></th>";
			}else{
				echo "<th width='180px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Operator</div></th>";
			}
			
			echo "<th width='130px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Category</div></th>
			<th width='80px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Type</div></th>
			<th width='50px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>DRFT</div></th>
			<th width='50px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Speed</div></th>
			<th width='30px' style='background:#BCBCBC; color:#333333; text-align:center;'><div style='padding:5px;'>Flag</div></th>
		</tr>";
		
		for($i=0; $i<$t; $i++){	
			$ships = $shipsA1print[$i];
			
			//if($shipsA1print[$i-1]['IMO #']!=$ships['IMO #']){
				//CHECK IF EXIST
				if($_SESSION['user']['dry']==1){
					$sql  = "SELECT * FROM `_xvas_shipdata_dry` WHERE `imo`='".$ships['IMO #']."'";
					$xvas = dbQuery($sql);
					$xvas = $xvas[0];
					
					if(trim($xvas['data'])){
						$status = getValue($xvas['data'], 'STATUS');
						
						if(trim($status)!="DEAD"){
							//UPDATES
							$sql = "SELECT * FROM `_messages` WHERE `imo`='".$ships['IMO #']."' AND type='network' ORDER BY dateadded DESC LIMIT 0,1";
							$bupdate = dbQuery($sql, $link);
							
							$bupdatearr = unserialize($bupdate[0]['message']);
							$delydate_to = strtotime($bupdatearr['delydate_to']);
							
							$sql = "SELECT * FROM `_operators_update` WHERE `imo`='".$ships['IMO #']."' AND type='op_update' ORDER BY dateadded DESC LIMIT 0,1";
							$oupdate = dbQuery($sql, $link);
							
							$sql = "SELECT * FROM `_blackbox_vessel` WHERE `vessel_name`='".$ships['Ship Name']."' ORDER BY latest_created DESC LIMIT 0,1";
							$eupdate = dbQuery($sql, $link);
							
							if(!empty($bupdate) || !empty($oupdate) || !empty($eupdate)){
								if((time()-$delydate_to)<(60*60*24*15) || (time()-strtotime(date('M d, Y', strtotime($eupdate[0]['from_time']))))<(60*60*24*15) || !empty($oupdate)){
									$updates = "<img src='images/icon_dropdown_warning_shore.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('s_drop1_".$i."', '".$ships['IMO #']."', 'shore');\" id='s_drop1_".$i."_img' />";
								}else{
									$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('s_drop1_".$i."', '".$ships['IMO #']."', 'shore');\" id='s_drop1_".$i."_img' />";
								}
							}else{
								$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('s_drop1_".$i."', '".$ships['IMO #']."', 'shore');\" id='s_drop1_".$i."_img' />";
							}
							//END
							
							//GET SHIP DETAILS
							$hull_type = getValue($xvas['data'], 'HULL_TYPE');
							if($hull_type=='SINGLE HULL'){ $hull_type = 'SH'; }
							else{ $hull_type = 'DH'; }
							
							$owner         = getValue($xvas['data'], 'OWNER');
							$manager_owner = getValue($xvas['data'], 'MANAGER_OWNER');
							$manager       = getValue($xvas['data'], 'MANAGER');
							if(trim($owner)){ $operator = $owner; }
							else if(trim($manager_owner)){ $operator = $manager_owner; }
							else if(trim($manager)){ $operator = $manager; }
							else{ $operator = ""; }
							
							$speed = getValue($xvas['data'], 'SPEED_SERVICE');
							
							if(trim($speed)){ $speed = number_format($speed, 2); }
							else{ $speed = "13.50"; }
							
							$flag     = getValue($xvas['data'], "FLAG");
							$flag_img = getFlagImage($flag);
							//END
							
							echo "<tr style='background:#e5e5e5;'>
								<td style='text-align:center;'><div style='padding:5px;'><input class='pcheck' type='checkbox' name='imos[]'  value='a1_".$ships['IMO #']."'></div></td>
								<td style='text-align:center;'><div style='padding:5px;'>".$updates."</div></td>
								<td>
									<div style='padding:5px;'>
										<table cellpadding='0' cellspacing='0' width='100%'>
											<tr>
												<td width='25'><img src='image.php?b=1&mx=20&p=".$ships['imageb']."'></td>
												<td><a class='clickable' onclick='return showShipDetails(\"".$ships['IMO #']."\")'>".$ships['Ship Name']."</a></td>
											</tr>
										</table>
									</div>
								</td>
								<td style='text-align:right;' class='z_text01'><div style='padding:5px;'>".$ships['LOAD_PORT']."</div></td>
								<td><div style='padding:5px;'><a class='clickable2' alt=\"".$ships['ETA TO LOAD PORT (days)']."\" title=\"".$ships['ETA TO LOAD PORT (days)']."\">".substr($ships['ETA TO LOAD PORT (days)'], 0,11)."</a></div></td>
								<td class='z_text01'><div style='padding:5px;'>".$hull_type."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".number_format(str_replace("tons", "", getValue($xvas['data'], 'SUMMER_DWT')))."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".getValue($xvas['data'], 'BUILD')."</div></td>
								<td><div style='padding:5px;'><a onclick='ownerDetails(\"".urlencode($operator)."\", \"0\")' class='clickable'>".$operator."</a></div></td>
								<td class='z_text01'><div style='padding:5px;'>".getValue($xvas['data'], 'VESSEL_TYPE')."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".$dwt_type."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".str_replace("m", "", getValue($xvas['data'], 'DRAUGHT'))."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".$speed."</div></td>
								<td style='text-align:center;'><div style='padding:5px;'><img alt=\"".htmlentities($flag)."\" title=\"".htmlentities($flag)."\" src='".$flag_img."' width='22' height='15' ></div></td>
							</tr>
							<tr style='width:992px; background:#fff;'>
								<td colspan='14' id='s_drop1_".$i."' style='display:none;'></td>
							</tr>";
						}
					}
				}elseif($_SESSION['user']['dry']==2){
					$sql  = "SELECT * FROM `_xvas_shipdata_container` WHERE `imo`='".$ships['IMO #']."'";
					$xvas = dbQuery($sql);
					$xvas = $xvas[0];
					
					if(trim($xvas['data'])){
						$status = getValue($xvas['data'], 'STATUS');
						
						if(trim($status)!="DEAD"){
							//UPDATES
							$sql = "SELECT * FROM `_messages` WHERE `imo`='".$ships['IMO #']."' AND type='network' ORDER BY dateadded DESC LIMIT 0,1";
							$bupdate = dbQuery($sql, $link);
							
							$bupdatearr = unserialize($bupdate[0]['message']);
							$delydate_to = strtotime($bupdatearr['delydate_to']);
							
							$sql = "SELECT * FROM `_operators_update` WHERE `imo`='".$ships['IMO #']."' AND type='op_update' ORDER BY dateadded DESC LIMIT 0,1";
							$oupdate = dbQuery($sql, $link);
							
							$sql = "SELECT * FROM `_blackbox_vessel` WHERE `vessel_name`='".$ships['Ship Name']."' ORDER BY latest_created DESC LIMIT 0,1";
							$eupdate = dbQuery($sql, $link);
							
							if(!empty($bupdate) || !empty($oupdate) || !empty($eupdate)){
								if((time()-$delydate_to)<(60*60*24*15) || (time()-strtotime(date('M d, Y', strtotime($eupdate[0]['from_time']))))<(60*60*24*15) || !empty($oupdate)){
									$updates = "<img src='images/icon_dropdown_warning_shore.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('s_drop1_".$i."', '".$ships['IMO #']."', 'shore');\" id='s_drop1_".$i."_img' />";
								}else{
									$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('s_drop1_".$i."', '".$ships['IMO #']."', 'shore');\" id='s_drop1_".$i."_img' />";
								}
							}else{
								$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('s_drop1_".$i."', '".$ships['IMO #']."', 'shore');\" id='s_drop1_".$i."_img' />";
							}
							//END
							
							//GET SHIP DETAILS
							$hull_type = getValue($xvas['data'], 'HULL_TYPE');
							if($hull_type=='SINGLE HULL'){ $hull_type = 'SH'; }
							else{ $hull_type = 'DH'; }
							
							$owner         = getValue($xvas['data'], 'OWNER');
							$manager_owner = getValue($xvas['data'], 'MANAGER_OWNER');
							$manager       = getValue($xvas['data'], 'MANAGER');
							if(trim($owner)){ $operator = $owner; }
							else if(trim($manager_owner)){ $operator = $manager_owner; }
							else if(trim($manager)){ $operator = $manager; }
							else{ $operator = ""; }
							
							$speed = getValue($xvas['data'], 'SPEED_SERVICE');
							
							if(trim($speed)){ $speed = number_format($speed, 2); }
							else{ $speed = "13.50"; }
							
							$flag     = getValue($xvas['data'], "FLAG");
							$flag_img = getFlagImage($flag);
							//END
							
							echo "<tr style='background:#e5e5e5;'>
								<td style='text-align:center;'><div style='padding:5px;'><input class='pcheck' type='checkbox' name='imos[]'  value='a1_".$ships['IMO #']."'></div></td>
								<td style='text-align:center;'><div style='padding:5px;'>".$updates."</div></td>
								<td>
									<div style='padding:5px;'>
										<table cellpadding='0' cellspacing='0' width='100%'>
											<tr>
												<td width='25'><img src='image.php?b=1&mx=20&p=".$ships['imageb']."'></td>
												<td><a class='clickable' onclick='return showShipDetails(\"".$ships['IMO #']."\")'>".$ships['Ship Name']."</a></td>
											</tr>
										</table>
									</div>
								</td>
								<td style='text-align:right;' class='z_text01'><div style='padding:5px;'>".$ships['LOAD_PORT']."</div></td>
								<td><div style='padding:5px;'><a class='clickable2' alt=\"".$ships['ETA TO LOAD PORT (days)']."\" title=\"".$ships['ETA TO LOAD PORT (days)']."\">".substr($ships['ETA TO LOAD PORT (days)'], 0,11)."</a></div></td>
								<td class='z_text01'><div style='padding:5px;'>".$hull_type."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".number_format(str_replace("tons", "", getValue($xvas['data'], 'SUMMER_DWT')))."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".getValue($xvas['data'], 'BUILD')."</div></td>
								<td><div style='padding:5px;'><a onclick='ownerDetails(\"".urlencode($operator)."\", \"0\")' class='clickable'>".$operator."</a></div></td>
								<td class='z_text01'><div style='padding:5px;'>".getValue($xvas['data'], 'VESSEL_TYPE')."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".$dwt_type."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".str_replace("m", "", getValue($xvas['data'], 'DRAUGHT'))."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".$speed."</div></td>
								<td style='text-align:center;'><div style='padding:5px;'><img alt=\"".htmlentities($flag)."\" title=\"".htmlentities($flag)."\" src='".$flag_img."' width='22' height='15' ></div></td>
							</tr>
							<tr style='width:992px; background:#fff;'>
								<td colspan='14' id='s_drop1_".$i."' style='display:none;'></td>
							</tr>";
						}
					}
				}elseif($_SESSION['user']['dry']==0){
					$sql  = "SELECT * FROM `_xvas_shipdata` WHERE `imo`='".$ships['IMO #']."'";
					$xvas = dbQuery($sql);
					$xvas = $xvas[0];
					
					if(trim($xvas['data'])){
						$status = getValue($xvas['data'], 'STATUS');
						
						if(trim($status)!="DEAD"){
							//UPDATES
							$sql = "SELECT * FROM `_messages` WHERE `imo`='".$ships['IMO #']."' AND type='network' ORDER BY dateadded DESC LIMIT 0,1";
							$bupdate = dbQuery($sql, $link);
							
							$bupdatearr = unserialize($bupdate[0]['message']);
							$opendate = strtotime($bupdatearr['opendate']);
							
							$sql = "SELECT * FROM `_operators_update` WHERE `imo`='".$ships['IMO #']."' AND type='op_update' ORDER BY dateadded DESC LIMIT 0,1";
							$oupdate = dbQuery($sql, $link);
							
							$sql = "SELECT * FROM `_blackbox_vessel` WHERE `vessel_name`='".$ships['Ship Name']."' ORDER BY latest_created DESC LIMIT 0,1";
							$eupdate = dbQuery($sql, $link);
							
							if(!empty($bupdate) || !empty($oupdate) || !empty($eupdate)){
								if((time()-$opendate)<(60*60*24*15) || (time()-strtotime(date('M d, Y', strtotime($eupdate[0]['from_time']))))<(60*60*24*15) || !empty($oupdate)){
									$updates = "<img src='images/icon_dropdown_warning_shore.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('s_drop1_".$i."', '".$ships['IMO #']."', 'shore');\" id='s_drop1_".$i."_img' />";
								}else{
									$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('s_drop1_".$i."', '".$ships['IMO #']."', 'shore');\" id='s_drop1_".$i."_img' />";
								}
							}else{
								$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('s_drop1_".$i."', '".$ships['IMO #']."', 'shore');\" id='s_drop1_".$i."_img' />";
							}
							//END
							
							//GET SHIP DETAILS
							$hull_type = getValue($xvas['data'], 'HULL_TYPE');
							if($hull_type=='SINGLE HULL'){ $hull_type = 'SH'; }
							else{ $hull_type = 'DH'; }
							
							$owner         = getValue($xvas['data'], 'OWNER');
							$manager_owner = getValue($xvas['data'], 'MANAGER_OWNER');
							$manager       = getValue($xvas['data'], 'MANAGER');
							if(trim($owner)){ $operator = $owner; }
							else if(trim($manager_owner)){ $operator = $manager_owner; }
							else if(trim($manager)){ $operator = $manager; }
							else{ $operator = ""; }
							
							$speed = getValue($xvas['data'], 'SPEED_SERVICE');
							
							if(trim($speed)){ $speed = number_format($speed, 2); }
							else{ $speed = "13.50"; }
							
							$flag     = getValue($xvas['data'], "FLAG");
							$flag_img = getFlagImage($flag);
							//END
							
							echo "<tr style='background:#e5e5e5;'>
								<td style='text-align:center;'><div style='padding:5px;'><input class='pcheck' type='checkbox' name='imos[]'  value='a1_".$ships['IMO #']."'></div></td>
								<td style='text-align:center;'><div style='padding:5px;'>".$updates."</div></td>
								<td>
									<div style='padding:5px;'>
										<table cellpadding='0' cellspacing='0' width='100%'>
											<tr>
												<td width='25'><img src='image.php?b=1&mx=20&p=".$ships['imageb']."'></td>
												<td><a class='clickable' onclick='return showShipDetails(\"".$ships['IMO #']."\")'>".$ships['Ship Name']."</a></td>
											</tr>
										</table>
									</div>
								</td>
								<td style='text-align:right;' class='z_text01'><div style='padding:5px;'>".$ships['LOAD_PORT']."</div></td>
								<td><div style='padding:5px;'><a class='clickable2' alt=\"".$ships['ETA TO LOAD PORT (days)']."\" title=\"".$ships['ETA TO LOAD PORT (days)']."\">".substr($ships['ETA TO LOAD PORT (days)'], 0,11)."</a></div></td>
								<td class='z_text01'><div style='padding:5px;'>".$hull_type."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".number_format(str_replace("tons", "", getValue($xvas['data'], 'SUMMER_DWT')))."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".getValue($xvas['data'], 'BUILD')."</div></td>
								<td><div style='padding:5px;'><a onclick='ownerDetails(\"".urlencode($operator)."\", \"0\")' class='clickable'>".$operator."</a></div></td>
								<td class='z_text01'><div style='padding:5px;'>".getValue($xvas['data'], 'VESSEL_TYPE')."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".$dwt_type."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".str_replace("m", "", getValue($xvas['data'], 'DRAUGHT'))."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".$speed."</div></td>
								<td style='text-align:center;'><div style='padding:5px;'><img alt=\"".htmlentities($flag)."\" title=\"".htmlentities($flag)."\" src='".$flag_img."' width='22' height='15' ></div></td>
							</tr>
							<tr style='width:992px; background:#fff;'>
								<td colspan='14' id='s_drop1_".$i."' style='display:none;'></td>
							</tr>";
						}
					}
				}
				//END
			//}
		}
		
	echo "</table>
	<table cellpadding='0' cellspacing='0' width='1000'>
		<tr>
			<td>&nbsp;</td>
		</tr>
	</table>";
}
//END OF T1

//T2
if($t2){
	echo "<div style='width:990px; text-align:left; padding:5px; background:#c5dc3b; color:white; margin-top:5px;'>
		<table cellpadding='0' cellspacing='0' width='990px'>
			<tr>
				<td><b style='font-size:14px;'>SHIPS WITH LAST KNOWN DESTINATION / POSITIONS USING AIS SHORESEARCH</b></td>
				<td align='right' style='text-align:right; vertical-align:top'>
					<a class='clickable' onclick=\"csvIt1('fixture')\"><img src='images/csv.jpg'></a>
					<a class='clickable' onclick=\"printIt1('fixture')\"><img src='images/print.jpg'></a>
					<a class='clickable' onclick=\"mailIt1('fixture')\"><img src='images/email_small.jpg'></a>
					<a href='#params'><img style='border:0px' src='images/up_icon.png' alt='back to top' title='back to top'></a>
				</td>
			</tr>
		</table>
	</div>
	<table id='pgreens21' width='1000px' style='border:1px solid #000;'>
		<tr>
			<th width='20px' style='text-align:center; background-color:#ccc;'><div style='padding:5px;'><input type='checkbox' onclick=\"checkAll('pgreens21', this)\" ></div></th>
			<th width='20px' style='background-color:#ccc; text-align:center;'><div style='padding:5px;'><img src='images/icon_book.png' border='0' /></div></th>
			<th width='125px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Name</div></th>
			<th width='100px' style='background:#BCBCBC; color:#333333; text-align:right;'><div style='padding:5px;'>Load Port</div></th>
			<th width='110px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Load ETA</div></th>
			<th width='50px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Hull</div></th>
			<th width='30px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>DWT</div></th>
			<th width='30px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Built</div></th>";
			
			if($_SESSION['user']['dry']==1 || $_SESSION['user']['dry']==2){
				echo "<th width='180px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Manager / Owner</div></th>";
			}else{
				echo "<th width='180px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Operator</div></th>";
			}
			
			echo "<th width='130px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Category</div></th>
			<th width='80px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Type</div></th>
			<th width='50px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>DRFT</div></th>
			<th width='50px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Speed</div></th>
			<th width='30px' style='background:#BCBCBC; color:#333333; text-align:center;'><div style='padding:5px;'>Flag</div></th>
		</tr>";
		
		for($i=0; $i<$t2; $i++){	
			$ships = $shipsA2print[$i];
			
			//if($shipsA2print[$i-1]['IMO #']!=$ships['IMO #']){
				//CHECK IF EXIST
				if($_SESSION['user']['dry']==1){
					$sql  = "SELECT * FROM `_xvas_shipdata_dry` WHERE `imo`='".$ships['IMO #']."'";
					$xvas = dbQuery($sql);
					$xvas = $xvas[0];
					
					if(trim($xvas['data'])){
						$status = getValue($xvas['data'], 'STATUS');
						
						if(trim($status)!="DEAD"){
							//UPDATES
							$sql = "SELECT * FROM `_messages` WHERE `imo`='".$ships['IMO #']."' AND type='network' ORDER BY dateadded DESC LIMIT 0,1";
							$bupdate = dbQuery($sql, $link);
							
							$bupdatearr = unserialize($bupdate[0]['message']);
							$delydate_to = strtotime($bupdatearr['delydate_to']);
							
							$sql = "SELECT * FROM `_operators_update` WHERE `imo`='".$ships['IMO #']."' AND type='op_update' ORDER BY dateadded DESC LIMIT 0,1";
							$oupdate = dbQuery($sql, $link);
							
							$sql = "SELECT * FROM `_blackbox_vessel` WHERE `vessel_name`='".$ships['Ship Name']."' ORDER BY latest_created DESC LIMIT 0,1";
							$eupdate = dbQuery($sql, $link);
							
							if(!empty($bupdate) || !empty($oupdate) || !empty($eupdate)){
								if((time()-$delydate_to)<(60*60*24*15) || (time()-strtotime(date('M d, Y', strtotime($eupdate[0]['from_time']))))<(60*60*24*15) || !empty($oupdate)){
									$updates = "<img src='images/icon_dropdown_warning_shore.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('s_drop2_".$i."', '".$ships['IMO #']."', 'shore');\" id='s_drop2_".$i."_img' />";
								}else{
									$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('s_drop2_".$i."', '".$ships['IMO #']."', 'shore');\" id='s_drop2_".$i."_img' />";
								}
							}else{
								$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('s_drop2_".$i."', '".$ships['IMO #']."', 'shore');\" id='s_drop2_".$i."_img' />";
							}
							//END
							
							//GET SHIP DETAILS
							$hull_type = getValue($xvas['data'], 'HULL_TYPE');
							if($hull_type=='SINGLE HULL'){ $hull_type = 'SH'; }
							else{ $hull_type = 'DH'; }
							
							$owner         = getValue($xvas['data'], 'OWNER');
							$manager_owner = getValue($xvas['data'], 'MANAGER_OWNER');
							$manager       = getValue($xvas['data'], 'MANAGER');
							if(trim($owner)){ $operator = $owner; }
							else if(trim($manager_owner)){ $operator = $manager_owner; }
							else if(trim($manager)){ $operator = $manager; }
							else{ $operator = ""; }
							
							$speed = getValue($xvas['data'], 'SPEED_SERVICE');
							
							if(trim($speed)){ $speed = number_format($speed, 2); }
							else{ $speed = "13.50"; }
							
							$flag     = getValue($xvas['data'], "FLAG");
							$flag_img = getFlagImage($flag);
							//END
							
							echo "<tr style='background:#e5e5e5;'>
								<td style='text-align:center;'><div style='padding:5px;'><input class='pcheck' type='checkbox' name='imos[]'  value='a2_".$ships['IMO #']."'></div></td>
								<td style='text-align:center;'><div style='padding:5px;'>".$updates."</div></td>
								<td>
									<div style='padding:5px;'>
										<table cellpadding='0' cellspacing='0' width='100%'>
											<tr>
												<td width='25'><img src='image.php?b=1&mx=20&p=".$ships['imageb']."'></td>
												<td><a class='clickable' onclick='return showShipDetails(\"".$ships['IMO #']."\")'>".$ships['Ship Name']."</a></td>
											</tr>
										</table>
									</div>
								</td>
								<td style='text-align:right;' class='z_text01'><div style='padding:5px;'>".$ships['LOAD_PORT']."</div></td>
								<td><div style='padding:5px;'><a class='clickable2' alt=\"".$ships['ETA TO LOAD PORT (days)']."\" title=\"".$ships['ETA TO LOAD PORT (days)']."\">".substr($ships['ETA TO LOAD PORT (days)'], 0,11)."</a></div></td>
								<td class='z_text01'><div style='padding:5px;'>".$hull_type."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".number_format(str_replace("tons", "", getValue($xvas['data'], 'SUMMER_DWT')))."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".getValue($xvas['data'], 'BUILD')."</div></td>
								<td><div style='padding:5px;'><a onclick='ownerDetails(\"".urlencode($operator)."\", \"0\")' class='clickable'>".$operator."</a></div></td>
								<td class='z_text01'><div style='padding:5px;'>".getValue($xvas['data'], 'VESSEL_TYPE')."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".$dwt_type."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".str_replace("m", "", getValue($xvas['data'], 'DRAUGHT'))."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".$speed."</div></td>
								<td style='text-align:center;'><div style='padding:5px;'><img alt=\"".htmlentities($flag)."\" title=\"".htmlentities($flag)."\" src='".$flag_img."' width='22' height='15' ></div></td>
							</tr>
							<tr style='width:992px; background:#fff;'>
								<td colspan='14' id='s_drop2_".$i."' style='display:none;'></td>
							</tr>";
						}
					}
				}elseif($_SESSION['user']['dry']==2){
					$sql  = "SELECT * FROM `_xvas_shipdata_container` WHERE `imo`='".$ships['IMO #']."'";
					$xvas = dbQuery($sql);
					$xvas = $xvas[0];
					
					if(trim($xvas['data'])){
						$status = getValue($xvas['data'], 'STATUS');
						
						if(trim($status)!="DEAD"){
							//UPDATES
							$sql = "SELECT * FROM `_messages` WHERE `imo`='".$ships['IMO #']."' AND type='network' ORDER BY dateadded DESC LIMIT 0,1";
							$bupdate = dbQuery($sql, $link);
							
							$bupdatearr = unserialize($bupdate[0]['message']);
							$delydate_to = strtotime($bupdatearr['delydate_to']);
							
							$sql = "SELECT * FROM `_operators_update` WHERE `imo`='".$ships['IMO #']."' AND type='op_update' ORDER BY dateadded DESC LIMIT 0,1";
							$oupdate = dbQuery($sql, $link);
							
							$sql = "SELECT * FROM `_blackbox_vessel` WHERE `vessel_name`='".$ships['Ship Name']."' ORDER BY latest_created DESC LIMIT 0,1";
							$eupdate = dbQuery($sql, $link);
							
							if(!empty($bupdate) || !empty($oupdate) || !empty($eupdate)){
								if((time()-$delydate_to)<(60*60*24*15) || (time()-strtotime(date('M d, Y', strtotime($eupdate[0]['from_time']))))<(60*60*24*15) || !empty($oupdate)){
									$updates = "<img src='images/icon_dropdown_warning_shore.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('s_drop2_".$i."', '".$ships['IMO #']."', 'shore');\" id='s_drop2_".$i."_img' />";
								}else{
									$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('s_drop2_".$i."', '".$ships['IMO #']."', 'shore');\" id='s_drop2_".$i."_img' />";
								}
							}else{
								$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('s_drop2_".$i."', '".$ships['IMO #']."', 'shore');\" id='s_drop2_".$i."_img' />";
							}
							//END
							
							//GET SHIP DETAILS
							$hull_type = getValue($xvas['data'], 'HULL_TYPE');
							if($hull_type=='SINGLE HULL'){ $hull_type = 'SH'; }
							else{ $hull_type = 'DH'; }
							
							$owner         = getValue($xvas['data'], 'OWNER');
							$manager_owner = getValue($xvas['data'], 'MANAGER_OWNER');
							$manager       = getValue($xvas['data'], 'MANAGER');
							if(trim($owner)){ $operator = $owner; }
							else if(trim($manager_owner)){ $operator = $manager_owner; }
							else if(trim($manager)){ $operator = $manager; }
							else{ $operator = ""; }
							
							$speed = getValue($xvas['data'], 'SPEED_SERVICE');
							
							if(trim($speed)){ $speed = number_format($speed, 2); }
							else{ $speed = "13.50"; }
							
							$flag     = getValue($xvas['data'], "FLAG");
							$flag_img = getFlagImage($flag);
							//END
							
							echo "<tr style='background:#e5e5e5;'>
								<td style='text-align:center;'><div style='padding:5px;'><input class='pcheck' type='checkbox' name='imos[]'  value='a2_".$ships['IMO #']."'></div></td>
								<td style='text-align:center;'><div style='padding:5px;'>".$updates."</div></td>
								<td>
									<div style='padding:5px;'>
										<table cellpadding='0' cellspacing='0' width='100%'>
											<tr>
												<td width='25'><img src='image.php?b=1&mx=20&p=".$ships['imageb']."'></td>
												<td><a class='clickable' onclick='return showShipDetails(\"".$ships['IMO #']."\")'>".$ships['Ship Name']."</a></td>
											</tr>
										</table>
									</div>
								</td>
								<td style='text-align:right;' class='z_text01'><div style='padding:5px;'>".$ships['LOAD_PORT']."</div></td>
								<td><div style='padding:5px;'><a class='clickable2' alt=\"".$ships['ETA TO LOAD PORT (days)']."\" title=\"".$ships['ETA TO LOAD PORT (days)']."\">".substr($ships['ETA TO LOAD PORT (days)'], 0,11)."</a></div></td>
								<td class='z_text01'><div style='padding:5px;'>".$hull_type."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".number_format(str_replace("tons", "", getValue($xvas['data'], 'SUMMER_DWT')))."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".getValue($xvas['data'], 'BUILD')."</div></td>
								<td><div style='padding:5px;'><a onclick='ownerDetails(\"".urlencode($operator)."\", \"0\")' class='clickable'>".$operator."</a></div></td>
								<td class='z_text01'><div style='padding:5px;'>".getValue($xvas['data'], 'VESSEL_TYPE')."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".$dwt_type."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".str_replace("m", "", getValue($xvas['data'], 'DRAUGHT'))."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".$speed."</div></td>
								<td style='text-align:center;'><div style='padding:5px;'><img alt=\"".htmlentities($flag)."\" title=\"".htmlentities($flag)."\" src='".$flag_img."' width='22' height='15' ></div></td>
							</tr>
							<tr style='width:992px; background:#fff;'>
								<td colspan='14' id='s_drop2_".$i."' style='display:none;'></td>
							</tr>";
						}
					}
				}elseif($_SESSION['user']['dry']==0){
					$sql  = "SELECT * FROM `_xvas_shipdata` WHERE `imo`='".$ships['IMO #']."'";
					$xvas = dbQuery($sql);
					$xvas = $xvas[0];
					
					if(trim($xvas['data'])){
						$status = getValue($xvas['data'], 'STATUS');
						
						if(trim($status)!="DEAD"){
							//UPDATES
							$sql = "SELECT * FROM `_messages` WHERE `imo`='".$ships['IMO #']."' AND type='network' ORDER BY dateadded DESC LIMIT 0,1";
							$bupdate = dbQuery($sql, $link);
							
							$bupdatearr = unserialize($bupdate[0]['message']);
							$opendate = strtotime($bupdatearr['opendate']);
							
							$sql = "SELECT * FROM `_operators_update` WHERE `imo`='".$ships['IMO #']."' AND type='op_update' ORDER BY dateadded DESC LIMIT 0,1";
							$oupdate = dbQuery($sql, $link);
							
							$sql = "SELECT * FROM `_blackbox_vessel` WHERE `vessel_name`='".$ships['Ship Name']."' ORDER BY latest_created DESC LIMIT 0,1";
							$eupdate = dbQuery($sql, $link);
							
							if(!empty($bupdate) || !empty($oupdate) || !empty($eupdate)){
								if((time()-$opendate)<(60*60*24*15) || (time()-strtotime(date('M d, Y', strtotime($eupdate[0]['from_time']))))<(60*60*24*15) || !empty($oupdate)){
									$updates = "<img src='images/icon_dropdown_warning_shore.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('s_drop2_".$i."', '".$ships['IMO #']."', 'shore');\" id='s_drop2_".$i."_img' />";
								}else{
									$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('s_drop2_".$i."', '".$ships['IMO #']."', 'shore');\" id='s_drop2_".$i."_img' />";
								}
							}else{
								$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('s_drop2_".$i."', '".$ships['IMO #']."', 'shore');\" id='s_drop2_".$i."_img' />";
							}
							//END
							
							//GET SHIP DETAILS
							$hull_type = getValue($xvas['data'], 'HULL_TYPE');
							if($hull_type=='SINGLE HULL'){ $hull_type = 'SH'; }
							else{ $hull_type = 'DH'; }
							
							$owner         = getValue($xvas['data'], 'OWNER');
							$manager_owner = getValue($xvas['data'], 'MANAGER_OWNER');
							$manager       = getValue($xvas['data'], 'MANAGER');
							if(trim($owner)){ $operator = $owner; }
							else if(trim($manager_owner)){ $operator = $manager_owner; }
							else if(trim($manager)){ $operator = $manager; }
							else{ $operator = ""; }
							
							$speed = getValue($xvas['data'], 'SPEED_SERVICE');
							
							if(trim($speed)){ $speed = number_format($speed, 2); }
							else{ $speed = "13.50"; }
							
							$flag     = getValue($xvas['data'], "FLAG");
							$flag_img = getFlagImage($flag);
							//END
							
							echo "<tr style='background:#e5e5e5;'>
								<td style='text-align:center;'><div style='padding:5px;'><input class='pcheck' type='checkbox' name='imos[]'  value='a2_".$ships['IMO #']."'></div></td>
								<td style='text-align:center;'><div style='padding:5px;'>".$updates."</div></td>
								<td>
									<div style='padding:5px;'>
										<table cellpadding='0' cellspacing='0' width='100%'>
											<tr>
												<td width='25'><img src='image.php?b=1&mx=20&p=".$ships['imageb']."'></td>
												<td><a class='clickable' onclick='return showShipDetails(\"".$ships['IMO #']."\")'>".$ships['Ship Name']."</a></td>
											</tr>
										</table>
									</div>
								</td>
								<td style='text-align:right;' class='z_text01'><div style='padding:5px;'>".$ships['LOAD_PORT']."</div></td>
								<td><div style='padding:5px;'><a class='clickable2' alt=\"".$ships['ETA TO LOAD PORT (days)']."\" title=\"".$ships['ETA TO LOAD PORT (days)']."\">".substr($ships['ETA TO LOAD PORT (days)'], 0,11)."</a></div></td>
								<td class='z_text01'><div style='padding:5px;'>".$hull_type."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".number_format(str_replace("tons", "", getValue($xvas['data'], 'SUMMER_DWT')))."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".getValue($xvas['data'], 'BUILD')."</div></td>
								<td><div style='padding:5px;'><a onclick='ownerDetails(\"".urlencode($operator)."\", \"0\")' class='clickable'>".$operator."</a></div></td>
								<td class='z_text01'><div style='padding:5px;'>".getValue($xvas['data'], 'VESSEL_TYPE')."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".$dwt_type."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".str_replace("m", "", getValue($xvas['data'], 'DRAUGHT'))."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".$speed."</div></td>
								<td style='text-align:center;'><div style='padding:5px;'><img alt=\"".htmlentities($flag)."\" title=\"".htmlentities($flag)."\" src='".$flag_img."' width='22' height='15' ></div></td>
							</tr>
							<tr style='width:992px; background:#fff;'>
								<td colspan='14' id='s_drop2_".$i."' style='display:none;'></td>
							</tr>";
						}
					}
				}
				//END
			//}
		}
		
	echo "</table>
	<table cellpadding='0' cellspacing='0' width='1000'>
		<tr>
			<td>&nbsp;</td>
		</tr>
	</table>";
}
//END OF T2

//T3
if($t3){
	echo "<div style='width:990px; text-align:left; padding:5px; background:#ffb83a; color:white; margin-top:5px;'>
		<table cellpadding='0' cellspacing='0' width='990px'>
			<tr>
				<td><b style='font-size:14px;'>SHIPS WITH CONFIRMED OPEN PORT USING BROKERSINTELLIGENCE</b></td>
				<td align='right' style='text-align:right; vertical-align:top'>
					<a class='clickable' onclick=\"csvIt1('fixture')\"><img src='images/csv.jpg'></a>
					<a class='clickable' onclick=\"printIt1('fixture')\"><img src='images/print.jpg'></a>
					<a class='clickable' onclick=\"mailIt1('fixture')\"><img src='images/email_small.jpg'></a>
					<a href='#params'><img style='border:0px' src='images/up_icon.png' alt='back to top' title='back to top'></a>
				</td>
			</tr>
		</table>
	</div>
	<table id='poranges11' width='1000px' style='border:1px solid #000;'>
		<tr>
			<th width='20px' style='text-align:center; background-color:#ccc;'><div style='padding:5px;'><input type='checkbox' onclick=\"checkAll('poranges11', this)\" ></div></th>
			<th width='20px' style='background-color:#ccc; text-align:center;'><div style='padding:5px;'><img src='images/icon_book.png' border='0' /></div></th>
			<th width='125px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Name</div></th>
			<th width='100px' style='background:#BCBCBC; color:#333333; text-align:right;'><div style='padding:5px;'>Open Port</div></th>
			<th width='110px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Open ETA</div></th>
			<th width='50px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Hull</div></th>
			<th width='30px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>DWT</div></th>
			<th width='30px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Built</div></th>";
			
			if($_SESSION['user']['dry']==1 || $_SESSION['user']['dry']==2){
				echo "<th width='180px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Manager / Owner</div></th>";
			}else{
				echo "<th width='180px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Operator</div></th>";
			}
			
			echo "<th width='130px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Category</div></th>
			<th width='80px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Type</div></th>
			<th width='50px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>DRFT</div></th>
			<th width='50px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Speed</div></th>
			<th width='30px' style='background:#BCBCBC; color:#333333; text-align:center;'><div style='padding:5px;'>Flag</div></th>
		</tr>";
		
		for($i=0; $i<$t3; $i++){	
			$ships = $shipsA3print[$i];
			
			//if($shipsA3print[$i-1]['IMO #']!=$ships['IMO #']){
				//CHECK IF EXIST
				if($_SESSION['user']['dry']==1){
					$sql  = "SELECT * FROM `_xvas_shipdata_dry` WHERE `imo`='".$ships['IMO #']."'";
					$xvas = dbQuery($sql);
					$xvas = $xvas[0];
					
					if(trim($xvas['data'])){
						$status = getValue($xvas['data'], 'STATUS');
						
						if(trim($status)!="DEAD"){
							//UPDATES
							$sql = "SELECT * FROM `_messages` WHERE `imo`='".$ships['IMO #']."' AND type='network' ORDER BY dateadded DESC LIMIT 0,1";
							$bupdate = dbQuery($sql, $link);
							
							$bupdatearr = unserialize($bupdate[0]['message']);
							$delydate_to = strtotime($bupdatearr['delydate_to']);
							
							if(!empty($bupdate)){
								if((time()-$delydate_to)<(60*60*24*15)){
									$updates = "<img src='images/icon_dropdown_warning_broker.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('s_drop3_".$i."', '".$ships['IMO #']."', 'broker');\" id='s_drop3_".$i."_img' />";
								}else{
									$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('s_drop3_".$i."', '".$ships['IMO #']."', 'broker');\" id='s_drop3_".$i."_img' />";
								}
							}else{
								$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('s_drop3_".$i."', '".$ships['IMO #']."', 'broker');\" id='s_drop3_".$i."_img' />";
							}
							//END
							
							//GET SHIP DETAILS
							$hull_type = getValue($xvas['data'], 'HULL_TYPE');
							if($hull_type=='SINGLE HULL'){ $hull_type = 'SH'; }
							else{ $hull_type = 'DH'; }
							
							$owner         = getValue($xvas['data'], 'OWNER');
							$manager_owner = getValue($xvas['data'], 'MANAGER_OWNER');
							$manager       = getValue($xvas['data'], 'MANAGER');
							if(trim($owner)){ $operator = $owner; }
							else if(trim($manager_owner)){ $operator = $manager_owner; }
							else if(trim($manager)){ $operator = $manager; }
							else{ $operator = ""; }
							
							$speed = getValue($xvas['data'], 'SPEED_SERVICE');
							
							if(trim($speed)){ $speed = number_format($speed, 2); }
							else{ $speed = "13.50"; }
							
							$flag     = getValue($xvas['data'], "FLAG");
							$flag_img = getFlagImage($flag);
							//END
							
							echo "<tr style='background:#e5e5e5;'>
								<td style='text-align:center;'><div style='padding:5px;'><input class='pcheck' type='checkbox' name='imos[]'  value='a3_".$ships['IMO #']."'></div></td>
								<td style='text-align:center;'><div style='padding:5px;'>".$updates."</div></td>
								<td>
									<div style='padding:5px;'>
										<table cellpadding='0' cellspacing='0' width='100%'>
											<tr>
												<td width='25'><img src='image.php?b=1&mx=20&p=".$ships['imageb']."'></td>
												<td><a class='clickable' onclick='return showShipDetails(\"".$ships['IMO #']."\")'>".$ships['Ship Name']."</a></td>
											</tr>
										</table>
									</div>
								</td>
								<td style='text-align:right;' class='z_text01'><div style='padding:5px;'>".$bupdatearr['dely']."</div></td>
								<td><div style='padding:5px;'><a class='clickable2' alt=\"".$bupdatearr['delydate_from']."\" title=\"".$bupdatearr['delydate_from']."\">".$bupdatearr['delydate_from']."</a></div></td>
								<td class='z_text01'><div style='padding:5px;'>".$hull_type."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".number_format(str_replace("tons", "", getValue($xvas['data'], 'SUMMER_DWT')))."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".getValue($xvas['data'], 'BUILD')."</div></td>
								<td><div style='padding:5px;'><a onclick='ownerDetails(\"".urlencode($operator)."\", \"0\")' class='clickable'>".$operator."</a></div></td>
								<td class='z_text01'><div style='padding:5px;'>".getValue($xvas['data'], 'VESSEL_TYPE')."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".$dwt_type."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".str_replace("m", "", getValue($xvas['data'], 'DRAUGHT'))."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".$speed."</div></td>
								<td style='text-align:center;'><div style='padding:5px;'><img alt=\"".htmlentities($flag)."\" title=\"".htmlentities($flag)."\" src='".$flag_img."' width='22' height='15' ></div></td>
							</tr>
							<tr style='width:992px; background:#fff;'>
								<td colspan='14' id='s_drop3_".$i."' style='display:none;'></td>
							</tr>";
						}
					}
				}elseif($_SESSION['user']['dry']==2){
					$sql  = "SELECT * FROM `_xvas_shipdata_container` WHERE `imo`='".$ships['IMO #']."'";
					$xvas = dbQuery($sql);
					$xvas = $xvas[0];
					
					if(trim($xvas['data'])){
						$status = getValue($xvas['data'], 'STATUS');
						
						if(trim($status)!="DEAD"){
							//UPDATES
							$sql = "SELECT * FROM `_messages` WHERE `imo`='".$ships['IMO #']."' AND type='network' ORDER BY dateadded DESC LIMIT 0,1";
							$bupdate = dbQuery($sql, $link);
							
							$bupdatearr = unserialize($bupdate[0]['message']);
							$delydate_to = strtotime($bupdatearr['delydate_to']);
							
							if(!empty($bupdate)){
								if((time()-$delydate_to)<(60*60*24*15)){
									$updates = "<img src='images/icon_dropdown_warning_broker.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('s_drop3_".$i."', '".$ships['IMO #']."', 'broker');\" id='s_drop3_".$i."_img' />";
								}else{
									$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('s_drop3_".$i."', '".$ships['IMO #']."', 'broker');\" id='s_drop3_".$i."_img' />";
								}
							}else{
								$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('s_drop3_".$i."', '".$ships['IMO #']."', 'broker');\" id='s_drop3_".$i."_img' />";
							}
							//END
							
							//GET SHIP DETAILS
							$hull_type = getValue($xvas['data'], 'HULL_TYPE');
							if($hull_type=='SINGLE HULL'){ $hull_type = 'SH'; }
							else{ $hull_type = 'DH'; }
							
							$owner         = getValue($xvas['data'], 'OWNER');
							$manager_owner = getValue($xvas['data'], 'MANAGER_OWNER');
							$manager       = getValue($xvas['data'], 'MANAGER');
							if(trim($owner)){ $operator = $owner; }
							else if(trim($manager_owner)){ $operator = $manager_owner; }
							else if(trim($manager)){ $operator = $manager; }
							else{ $operator = ""; }
							
							$speed = getValue($xvas['data'], 'SPEED_SERVICE');
							
							if(trim($speed)){ $speed = number_format($speed, 2); }
							else{ $speed = "13.50"; }
							
							$flag     = getValue($xvas['data'], "FLAG");
							$flag_img = getFlagImage($flag);
							//END
							
							echo "<tr style='background:#e5e5e5;'>
								<td style='text-align:center;'><div style='padding:5px;'><input class='pcheck' type='checkbox' name='imos[]'  value='a3_".$ships['IMO #']."'></div></td>
								<td style='text-align:center;'><div style='padding:5px;'>".$updates."</div></td>
								<td>
									<div style='padding:5px;'>
										<table cellpadding='0' cellspacing='0' width='100%'>
											<tr>
												<td width='25'><img src='image.php?b=1&mx=20&p=".$ships['imageb']."'></td>
												<td><a class='clickable' onclick='return showShipDetails(\"".$ships['IMO #']."\")'>".$ships['Ship Name']."</a></td>
											</tr>
										</table>
									</div>
								</td>
								<td style='text-align:right;' class='z_text01'><div style='padding:5px;'>".$bupdatearr['dely']."</div></td>
								<td><div style='padding:5px;'><a class='clickable2' alt=\"".$bupdatearr['delydate_from']."\" title=\"".$bupdatearr['delydate_from']."\">".$bupdatearr['delydate_from']."</a></div></td>
								<td class='z_text01'><div style='padding:5px;'>".$hull_type."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".number_format(str_replace("tons", "", getValue($xvas['data'], 'SUMMER_DWT')))."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".getValue($xvas['data'], 'BUILD')."</div></td>
								<td><div style='padding:5px;'><a onclick='ownerDetails(\"".urlencode($operator)."\", \"0\")' class='clickable'>".$operator."</a></div></td>
								<td class='z_text01'><div style='padding:5px;'>".getValue($xvas['data'], 'VESSEL_TYPE')."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".$dwt_type."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".str_replace("m", "", getValue($xvas['data'], 'DRAUGHT'))."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".$speed."</div></td>
								<td style='text-align:center;'><div style='padding:5px;'><img alt=\"".htmlentities($flag)."\" title=\"".htmlentities($flag)."\" src='".$flag_img."' width='22' height='15' ></div></td>
							</tr>
							<tr style='width:992px; background:#fff;'>
								<td colspan='14' id='s_drop3_".$i."' style='display:none;'></td>
							</tr>";
						}
					}
				}elseif($_SESSION['user']['dry']==0){
					$sql  = "SELECT * FROM `_xvas_shipdata` WHERE `imo`='".$ships['IMO #']."'";
					$xvas = dbQuery($sql);
					$xvas = $xvas[0];
					
					if(trim($xvas['data'])){
						$status = getValue($xvas['data'], 'STATUS');
						
						if(trim($status)!="DEAD"){
							//UPDATES
							$sql = "SELECT * FROM `_messages` WHERE `imo`='".$ships['IMO #']."' AND type='network' ORDER BY dateadded DESC LIMIT 0,1";
							$bupdate = dbQuery($sql, $link);
							
							$bupdatearr = unserialize($bupdate[0]['message']);
							$opendate = strtotime($bupdatearr['opendate']);
							
							if(!empty($bupdate)){
								if((time()-$opendate)<(60*60*24*15)){
									$updates = "<img src='images/icon_dropdown_warning_broker.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('s_drop3_".$i."', '".$ships['IMO #']."', 'broker');\" id='s_drop3_".$i."_img' />";
								}else{
									$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('s_drop3_".$i."', '".$ships['IMO #']."', 'broker');\" id='s_drop3_".$i."_img' />";
								}
							}else{
								$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('s_drop3_".$i."', '".$ships['IMO #']."', 'broker');\" id='s_drop3_".$i."_img' />";
							}
							//END
							
							//GET SHIP DETAILS
							$hull_type = getValue($xvas['data'], 'HULL_TYPE');
							if($hull_type=='SINGLE HULL'){ $hull_type = 'SH'; }
							else{ $hull_type = 'DH'; }
							
							$owner         = getValue($xvas['data'], 'OWNER');
							$manager_owner = getValue($xvas['data'], 'MANAGER_OWNER');
							$manager       = getValue($xvas['data'], 'MANAGER');
							if(trim($owner)){ $operator = $owner; }
							else if(trim($manager_owner)){ $operator = $manager_owner; }
							else if(trim($manager)){ $operator = $manager; }
							else{ $operator = ""; }
							
							$speed = getValue($xvas['data'], 'SPEED_SERVICE');
							
							if(trim($speed)){ $speed = number_format($speed, 2); }
							else{ $speed = "13.50"; }
							
							$flag     = getValue($xvas['data'], "FLAG");
							$flag_img = getFlagImage($flag);
							//END
							
							echo "<tr style='background:#e5e5e5;'>
								<td style='text-align:center;'><div style='padding:5px;'><input class='pcheck' type='checkbox' name='imos[]'  value='a3_".$ships['IMO #']."'></div></td>
								<td style='text-align:center;'><div style='padding:5px;'>".$updates."</div></td>
								<td>
									<div style='padding:5px;'>
										<table cellpadding='0' cellspacing='0' width='100%'>
											<tr>
												<td width='25'><img src='image.php?b=1&mx=20&p=".$ships['imageb']."'></td>
												<td><a class='clickable' onclick='return showShipDetails(\"".$ships['IMO #']."\")'>".$ships['Ship Name']."</a></td>
											</tr>
										</table>
									</div>
								</td>
								<td style='text-align:right;' class='z_text01'><div style='padding:5px;'>".$bupdatearr['openport']."</div></td>
								<td><div style='padding:5px;'><a class='clickable2' alt=\"".$bupdatearr['opendate']."\" title=\"".$bupdatearr['opendate']."\">".$bupdatearr['opendate']."</a></div></td>
								<td class='z_text01'><div style='padding:5px;'>".$hull_type."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".number_format(str_replace("tons", "", getValue($xvas['data'], 'SUMMER_DWT')))."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".getValue($xvas['data'], 'BUILD')."</div></td>
								<td><div style='padding:5px;'><a onclick='ownerDetails(\"".urlencode($operator)."\", \"0\")' class='clickable'>".$operator."</a></div></td>
								<td class='z_text01'><div style='padding:5px;'>".getValue($xvas['data'], 'VESSEL_TYPE')."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".$dwt_type."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".str_replace("m", "", getValue($xvas['data'], 'DRAUGHT'))."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".$speed."</div></td>
								<td style='text-align:center;'><div style='padding:5px;'><img alt=\"".htmlentities($flag)."\" title=\"".htmlentities($flag)."\" src='".$flag_img."' width='22' height='15' ></div></td>
							</tr>
							<tr style='width:992px; background:#fff;'>
								<td colspan='14' id='s_drop3_".$i."' style='display:none;'></td>
							</tr>";
						}
					}
				}
				//END
			//}
		}
		
	echo "</table>
	<table cellpadding='0' cellspacing='0' width='1000'>
		<tr>
			<td>&nbsp;</td>
		</tr>
	</table>";
}
//END OF T3

//T4
if($t4){
	echo "<div style='width:990px; text-align:left; padding:5px; background:#ffb83a; color:white; margin-top:5px;'>
		<table cellpadding='0' cellspacing='0' width='990px'>
			<tr>
				<td><b style='font-size:14px;'>SHIPS WITH LAST KNOWN POSITIONS USING BROKERSINTELLIGENCE</b></td>
				<td align='right' style='text-align:right; vertical-align:top'>
					<a class='clickable' onclick=\"csvIt1('fixture')\"><img src='images/csv.jpg'></a>
					<a class='clickable' onclick=\"printIt1('fixture')\"><img src='images/print.jpg'></a>
					<a class='clickable' onclick=\"mailIt1('fixture')\"><img src='images/email_small.jpg'></a>
					<a href='#params'><img style='border:0px' src='images/up_icon.png' alt='back to top' title='back to top'></a>
				</td>
			</tr>
		</table>
	</div>
	<table id='poranges21' width='1000px' style='border:1px solid #000;'>
		<tr>
			<th width='20px' style='text-align:center; background-color:#ccc;'><div style='padding:5px;'><input type='checkbox' onclick=\"checkAll('poranges21', this)\" ></div></th>
			<th width='20px' style='background-color:#ccc; text-align:center;'><div style='padding:5px;'><img src='images/icon_book.png' border='0' /></div></th>
			<th width='125px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Name</div></th>
			<th width='100px' style='background:#BCBCBC; color:#333333; text-align:right;'><div style='padding:5px;'>Open Port</div></th>
			<th width='110px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Open ETA</div></th>
			<th width='50px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Hull</div></th>
			<th width='30px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>DWT</div></th>
			<th width='30px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Built</div></th>";
			
			if($_SESSION['user']['dry']==1 || $_SESSION['user']['dry']==2){
				echo "<th width='180px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Manager / Owner</div></th>";
			}else{
				echo "<th width='180px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Operator</div></th>";
			}
			
			echo "<th width='130px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Category</div></th>
			<th width='80px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Type</div></th>
			<th width='50px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>DRFT</div></th>
			<th width='50px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Speed</div></th>
			<th width='30px' style='background:#BCBCBC; color:#333333; text-align:center;'><div style='padding:5px;'>Flag</div></th>
		</tr>";
		
		for($i=0; $i<$t4; $i++){
			$ships = $shipsA4print[$i];
			
			//if($shipsA4print[$i-1]['IMO #']!=$ships['IMO #']){
				//CHECK IF EXIST
				if($_SESSION['user']['dry']==1){
					$sql  = "SELECT * FROM `_xvas_shipdata_dry` WHERE `imo`='".$ships['IMO #']."'";
					$xvas = dbQuery($sql);
					$xvas = $xvas[0];
					
					if(trim($xvas['data'])){
						$status = getValue($xvas['data'], 'STATUS');
						
						if(trim($status)!="DEAD"){
							//UPDATES
							$sql = "SELECT * FROM `_messages` WHERE `imo`='".$ships['IMO #']."' AND type='network' ORDER BY dateadded DESC LIMIT 0,1";
							$bupdate = dbQuery($sql, $link);
							
							$bupdatearr = unserialize($bupdate[0]['message']);
							$delydate_to = strtotime($bupdatearr['delydate_to']);
							
							if(!empty($bupdate)){
								if((time()-$delydate_to)<(60*60*24*15)){
									$updates = "<img src='images/icon_dropdown_warning_broker.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('s_drop4_".$i."', '".$ships['IMO #']."', 'broker');\" id='s_drop4_".$i."_img' />";
								}else{
									$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('s_drop4_".$i."', '".$ships['IMO #']."', 'broker');\" id='s_drop4_".$i."_img' />";
								}
							}else{
								$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('s_drop4_".$i."', '".$ships['IMO #']."', 'broker');\" id='s_drop4_".$i."_img' />";
							}
							//END
							
							//GET SHIP DETAILS
							$hull_type = getValue($xvas['data'], 'HULL_TYPE');
							if($hull_type=='SINGLE HULL'){ $hull_type = 'SH'; }
							else{ $hull_type = 'DH'; }
							
							$owner         = getValue($xvas['data'], 'OWNER');
							$manager_owner = getValue($xvas['data'], 'MANAGER_OWNER');
							$manager       = getValue($xvas['data'], 'MANAGER');
							if(trim($owner)){ $operator = $owner; }
							else if(trim($manager_owner)){ $operator = $manager_owner; }
							else if(trim($manager)){ $operator = $manager; }
							else{ $operator = ""; }
							
							$speed = getValue($xvas['data'], 'SPEED_SERVICE');
							
							if(trim($speed)){ $speed = number_format($speed, 2); }
							else{ $speed = "13.50"; }
							
							$flag     = getValue($xvas['data'], "FLAG");
							$flag_img = getFlagImage($flag);
							//END
							
							echo "<tr style='background:#e5e5e5;'>
								<td style='text-align:center;'><div style='padding:5px;'><input class='pcheck' type='checkbox' name='imos[]'  value='a4_".$ships['IMO #']."'></div></td>
								<td style='text-align:center;'><div style='padding:5px;'>".$updates."</div></td>
								<td>
									<div style='padding:5px;'>
										<table cellpadding='0' cellspacing='0' width='100%'>
											<tr>
												<td width='25'><img src='image.php?b=1&mx=20&p=".$ships['imageb']."'></td>
												<td><a class='clickable' onclick='return showShipDetails(\"".$ships['IMO #']."\")'>".$ships['Ship Name']."</a></td>
											</tr>
										</table>
									</div>
								</td>
								<td style='text-align:right;' class='z_text01'><div style='padding:5px;'>".$bupdatearr['dely']."</div></td>
								<td><div style='padding:5px;'><a class='clickable2' alt=\"".$bupdatearr['delydate_from']."\" title=\"".$bupdatearr['delydate_from']."\">".$bupdatearr['delydate_from']."</a></div></td>
								<td class='z_text01'><div style='padding:5px;'>".$hull_type."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".number_format(str_replace("tons", "", getValue($xvas['data'], 'SUMMER_DWT')))."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".getValue($xvas['data'], 'BUILD')."</div></td>
								<td><div style='padding:5px;'><a onclick='ownerDetails(\"".urlencode($operator)."\", \"0\")' class='clickable'>".$operator."</a></div></td>
								<td class='z_text01'><div style='padding:5px;'>".getValue($xvas['data'], 'VESSEL_TYPE')."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".$dwt_type."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".str_replace("m", "", getValue($xvas['data'], 'DRAUGHT'))."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".$speed."</div></td>
								<td style='text-align:center;'><div style='padding:5px;'><img alt=\"".htmlentities($flag)."\" title=\"".htmlentities($flag)."\" src='".$flag_img."' width='22' height='15' ></div></td>
							</tr>
							<tr style='width:992px; background:#fff;'>
								<td colspan='14' id='s_drop4_".$i."' style='display:none;'></td>
							</tr>";
						}
					}
				}elseif($_SESSION['user']['dry']==2){
					$sql  = "SELECT * FROM `_xvas_shipdata_container` WHERE `imo`='".$ships['IMO #']."'";
					$xvas = dbQuery($sql);
					$xvas = $xvas[0];
					
					if(trim($xvas['data'])){
						$status = getValue($xvas['data'], 'STATUS');
						
						if(trim($status)!="DEAD"){
							//UPDATES
							$sql = "SELECT * FROM `_messages` WHERE `imo`='".$ships['IMO #']."' AND type='network' ORDER BY dateadded DESC LIMIT 0,1";
							$bupdate = dbQuery($sql, $link);
							
							$bupdatearr = unserialize($bupdate[0]['message']);
							$delydate_to = strtotime($bupdatearr['delydate_to']);
							
							if(!empty($bupdate)){
								if((time()-$delydate_to)<(60*60*24*15)){
									$updates = "<img src='images/icon_dropdown_warning_broker.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('s_drop4_".$i."', '".$ships['IMO #']."', 'broker');\" id='s_drop4_".$i."_img' />";
								}else{
									$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('s_drop4_".$i."', '".$ships['IMO #']."', 'broker');\" id='s_drop4_".$i."_img' />";
								}
							}else{
								$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('s_drop4_".$i."', '".$ships['IMO #']."', 'broker');\" id='s_drop4_".$i."_img' />";
							}
							//END
							
							//GET SHIP DETAILS
							$hull_type = getValue($xvas['data'], 'HULL_TYPE');
							if($hull_type=='SINGLE HULL'){ $hull_type = 'SH'; }
							else{ $hull_type = 'DH'; }
							
							$owner         = getValue($xvas['data'], 'OWNER');
							$manager_owner = getValue($xvas['data'], 'MANAGER_OWNER');
							$manager       = getValue($xvas['data'], 'MANAGER');
							if(trim($owner)){ $operator = $owner; }
							else if(trim($manager_owner)){ $operator = $manager_owner; }
							else if(trim($manager)){ $operator = $manager; }
							else{ $operator = ""; }
							
							$speed = getValue($xvas['data'], 'SPEED_SERVICE');
							
							if(trim($speed)){ $speed = number_format($speed, 2); }
							else{ $speed = "13.50"; }
							
							$flag     = getValue($xvas['data'], "FLAG");
							$flag_img = getFlagImage($flag);
							//END
							
							echo "<tr style='background:#e5e5e5;'>
								<td style='text-align:center;'><div style='padding:5px;'><input class='pcheck' type='checkbox' name='imos[]'  value='a4_".$ships['IMO #']."'></div></td>
								<td style='text-align:center;'><div style='padding:5px;'>".$updates."</div></td>
								<td>
									<div style='padding:5px;'>
										<table cellpadding='0' cellspacing='0' width='100%'>
											<tr>
												<td width='25'><img src='image.php?b=1&mx=20&p=".$ships['imageb']."'></td>
												<td><a class='clickable' onclick='return showShipDetails(\"".$ships['IMO #']."\")'>".$ships['Ship Name']."</a></td>
											</tr>
										</table>
									</div>
								</td>
								<td style='text-align:right;' class='z_text01'><div style='padding:5px;'>".$bupdatearr['dely']."</div></td>
								<td><div style='padding:5px;'><a class='clickable2' alt=\"".$bupdatearr['delydate_from']."\" title=\"".$bupdatearr['delydate_from']."\">".$bupdatearr['delydate_from']."</a></div></td>
								<td class='z_text01'><div style='padding:5px;'>".$hull_type."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".number_format(str_replace("tons", "", getValue($xvas['data'], 'SUMMER_DWT')))."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".getValue($xvas['data'], 'BUILD')."</div></td>
								<td><div style='padding:5px;'><a onclick='ownerDetails(\"".urlencode($operator)."\", \"0\")' class='clickable'>".$operator."</a></div></td>
								<td class='z_text01'><div style='padding:5px;'>".getValue($xvas['data'], 'VESSEL_TYPE')."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".$dwt_type."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".str_replace("m", "", getValue($xvas['data'], 'DRAUGHT'))."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".$speed."</div></td>
								<td style='text-align:center;'><div style='padding:5px;'><img alt=\"".htmlentities($flag)."\" title=\"".htmlentities($flag)."\" src='".$flag_img."' width='22' height='15' ></div></td>
							</tr>
							<tr style='width:992px; background:#fff;'>
								<td colspan='14' id='s_drop4_".$i."' style='display:none;'></td>
							</tr>";
						}
					}
				}elseif($_SESSION['user']['dry']==0){
					$sql  = "SELECT * FROM `_xvas_shipdata` WHERE `imo`='".$ships['IMO #']."'";
					$xvas = dbQuery($sql);
					$xvas = $xvas[0];
					
					if(trim($xvas['data'])){
						$status = getValue($xvas['data'], 'STATUS');
						
						if(trim($status)!="DEAD"){
							//UPDATES
							$sql = "SELECT * FROM `_messages` WHERE `imo`='".$ships['IMO #']."' AND type='network' ORDER BY dateadded DESC LIMIT 0,1";
							$bupdate = dbQuery($sql, $link);
							
							$bupdatearr = unserialize($bupdate[0]['message']);
							$opendate = strtotime($bupdatearr['opendate']);
							
							if(!empty($bupdate)){
								if((time()-$opendate)<(60*60*24*15)){
									$updates = "<img src='images/icon_dropdown_warning_broker.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('s_drop4_".$i."', '".$ships['IMO #']."', 'broker');\" id='s_drop4_".$i."_img' />";
								}else{
									$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('s_drop4_".$i."', '".$ships['IMO #']."', 'broker');\" id='s_drop4_".$i."_img' />";
								}
							}else{
								$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('s_drop4_".$i."', '".$ships['IMO #']."', 'broker');\" id='s_drop4_".$i."_img' />";
							}
							//END
							
							//GET SHIP DETAILS
							$hull_type = getValue($xvas['data'], 'HULL_TYPE');
							if($hull_type=='SINGLE HULL'){ $hull_type = 'SH'; }
							else{ $hull_type = 'DH'; }
							
							$owner         = getValue($xvas['data'], 'OWNER');
							$manager_owner = getValue($xvas['data'], 'MANAGER_OWNER');
							$manager       = getValue($xvas['data'], 'MANAGER');
							if(trim($owner)){ $operator = $owner; }
							else if(trim($manager_owner)){ $operator = $manager_owner; }
							else if(trim($manager)){ $operator = $manager; }
							else{ $operator = ""; }
							
							$speed = getValue($xvas['data'], 'SPEED_SERVICE');
							
							if(trim($speed)){ $speed = number_format($speed, 2); }
							else{ $speed = "13.50"; }
							
							$flag     = getValue($xvas['data'], "FLAG");
							$flag_img = getFlagImage($flag);
							//END
							
							echo "<tr style='background:#e5e5e5;'>
								<td style='text-align:center;'><div style='padding:5px;'><input class='pcheck' type='checkbox' name='imos[]'  value='a4_".$ships['IMO #']."'></div></td>
								<td style='text-align:center;'><div style='padding:5px;'>".$updates."</div></td>
								<td>
									<div style='padding:5px;'>
										<table cellpadding='0' cellspacing='0' width='100%'>
											<tr>
												<td width='25'><img src='image.php?b=1&mx=20&p=".$ships['imageb']."'></td>
												<td><a class='clickable' onclick='return showShipDetails(\"".$ships['IMO #']."\")'>".$ships['Ship Name']."</a></td>
											</tr>
										</table>
									</div>
								</td>
								<td style='text-align:right;' class='z_text01'><div style='padding:5px;'>".$bupdatearr['openport']."</div></td>
								<td><div style='padding:5px;'><a class='clickable2' alt=\"".$bupdatearr['opendate']."\" title=\"".$bupdatearr['opendate']."\">".$bupdatearr['opendate']."</a></div></td>
								<td class='z_text01'><div style='padding:5px;'>".$hull_type."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".number_format(str_replace("tons", "", getValue($xvas['data'], 'SUMMER_DWT')))."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".getValue($xvas['data'], 'BUILD')."</div></td>
								<td><div style='padding:5px;'><a onclick='ownerDetails(\"".urlencode($operator)."\", \"0\")' class='clickable'>".$operator."</a></div></td>
								<td class='z_text01'><div style='padding:5px;'>".getValue($xvas['data'], 'VESSEL_TYPE')."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".$dwt_type."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".str_replace("m", "", getValue($xvas['data'], 'DRAUGHT'))."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".$speed."</div></td>
								<td style='text-align:center;'><div style='padding:5px;'><img alt=\"".htmlentities($flag)."\" title=\"".htmlentities($flag)."\" src='".$flag_img."' width='22' height='15' ></div></td>
							</tr>
							<tr style='width:992px; background:#fff;'>
								<td colspan='14' id='s_drop4_".$i."' style='display:none;'></td>
							</tr>";
						}
					}
				}
				//END
			//}
		}
		
	echo "</table>
	<table cellpadding='0' cellspacing='0' width='1000'>
		<tr>
			<td>&nbsp;</td>
		</tr>
	</table>";
}
//END OF T4

//T5
if($t5){
	echo "<div style='width:990px; text-align:left; padding:5px; background:#ffb83a; color:white; margin-top:5px;'>
		<table cellpadding='0' cellspacing='0' width='990px'>
			<tr>
				<td><b style='font-size:14px;'>SHIPS FOUND USING BROKERSINTELLIGENCE</b></td>
				<td align='right' style='text-align:right; vertical-align:top'>
					<a class='clickable' onclick=\"csvIt1('fixture')\"><img src='images/csv.jpg'></a>
					<a class='clickable' onclick=\"printIt1('fixture')\"><img src='images/print.jpg'></a>
					<a class='clickable' onclick=\"mailIt1('fixture')\"><img src='images/email_small.jpg'></a>
					<a href='#params'><img style='border:0px' src='images/up_icon.png' alt='back to top' title='back to top'></a>
				</td>
			</tr>
		</table>
	</div>
	<table id='poranges31' width='1000px' style='border:1px solid #000;'>
		<tr>
			<th width='20px' style='text-align:center; background-color:#ccc;'><div style='padding:5px;'><input type='checkbox' onclick=\"checkAll('poranges31', this)\" ></div></th>
			<th width='20px' style='background-color:#ccc; text-align:center;'><div style='padding:5px;'><img src='images/icon_book.png' border='0' /></div></th>
			<th width='125px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Name</div></th>
			<th width='100px' style='background:#BCBCBC; color:#333333; text-align:right;'><div style='padding:5px;'>Open Port</div></th>
			<th width='110px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Open ETA</div></th>
			<th width='50px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Hull</div></th>
			<th width='30px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>DWT</div></th>
			<th width='30px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Built</div></th>";
			
			if($_SESSION['user']['dry']==1 || $_SESSION['user']['dry']==2){
				echo "<th width='180px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Manager / Owner</div></th>";
			}else{
				echo "<th width='180px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Operator</div></th>";
			}
			
			echo "<th width='130px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Category</div></th>
			<th width='80px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Type</div></th>
			<th width='50px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>DRFT</div></th>
			<th width='50px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Speed</div></th>
			<th width='30px' style='background:#BCBCBC; color:#333333; text-align:center;'><div style='padding:5px;'>Flag</div></th>
		</tr>";
		
		for($i=0; $i<$t5; $i++){	
			$ships = $shipsA5print[$i];
			
			//if($shipsA5print[$i-1]['IMO #']!=$ships['IMO #']){
				//CHECK IF EXIST
				if($_SESSION['user']['dry']==1){
					$sql  = "SELECT * FROM `_xvas_shipdata_dry` WHERE `imo`='".$ships['IMO #']."'";
					$xvas = dbQuery($sql);
					$xvas = $xvas[0];
					
					if(trim($xvas['data'])){
						$status = getValue($xvas['data'], 'STATUS');
						
						if(trim($status)!="DEAD"){
							//UPDATES
							$sql = "SELECT * FROM `_messages` WHERE `imo`='".$ships['IMO #']."' AND type='network' ORDER BY dateadded DESC LIMIT 0,1";
							$bupdate = dbQuery($sql, $link);
							
							$bupdatearr = unserialize($bupdate[0]['message']);
							$delydate_to = strtotime($bupdatearr['delydate_to']);
							
							if(!empty($bupdate)){
								if((time()-$delydate_to)<(60*60*24*15)){
									$updates = "<img src='images/icon_dropdown_warning_broker.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('s_drop5_".$i."', '".$ships['IMO #']."', 'broker');\" id='s_drop5_".$i."_img' />";
								}else{
									$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('s_drop5_".$i."', '".$ships['IMO #']."', 'broker');\" id='s_drop5_".$i."_img' />";
								}
							}else{
								$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('s_drop5_".$i."', '".$ships['IMO #']."', 'broker');\" id='s_drop5_".$i."_img' />";
							}
							//END
							
							//GET SHIP DETAILS
							$hull_type = getValue($xvas['data'], 'HULL_TYPE');
							if($hull_type=='SINGLE HULL'){ $hull_type = 'SH'; }
							else{ $hull_type = 'DH'; }
							
							$owner         = getValue($xvas['data'], 'OWNER');
							$manager_owner = getValue($xvas['data'], 'MANAGER_OWNER');
							$manager       = getValue($xvas['data'], 'MANAGER');
							if(trim($owner)){ $operator = $owner; }
							else if(trim($manager_owner)){ $operator = $manager_owner; }
							else if(trim($manager)){ $operator = $manager; }
							else{ $operator = ""; }
							
							$speed = getValue($xvas['data'], 'SPEED_SERVICE');
							
							if(trim($speed)){ $speed = number_format($speed, 2); }
							else{ $speed = "13.50"; }
							
							$flag     = getValue($xvas['data'], "FLAG");
							$flag_img = getFlagImage($flag);
							//END
							
							echo "<tr style='background:#e5e5e5;'>
								<td style='text-align:center;'><div style='padding:5px;'><input class='pcheck' type='checkbox' name='imos[]'  value='a5_".$ships['IMO #']."'></div></td>
								<td style='text-align:center;'><div style='padding:5px;'>".$updates."</div></td>
								<td>
									<div style='padding:5px;'>
										<table cellpadding='0' cellspacing='0' width='100%'>
											<tr>
												<td width='25'><img src='image.php?b=1&mx=20&p=".$ships['imageb']."'></td>
												<td><a class='clickable' onclick='return showShipDetails(\"".$ships['IMO #']."\")'>".$ships['Ship Name']."</a></td>
											</tr>
										</table>
									</div>
								</td>
								<td style='text-align:right;' class='z_text01'><div style='padding:5px;'>".$bupdatearr['dely']."</div></td>
								<td><div style='padding:5px;'><a class='clickable2' alt=\"".$bupdatearr['delydate_from']."\" title=\"".$bupdatearr['delydate_from']."\">".$bupdatearr['delydate_from']."</a></div></td>
								<td class='z_text01'><div style='padding:5px;'>".$hull_type."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".number_format(str_replace("tons", "", getValue($xvas['data'], 'SUMMER_DWT')))."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".getValue($xvas['data'], 'BUILD')."</div></td>
								<td><div style='padding:5px;'><a onclick='ownerDetails(\"".urlencode($operator)."\", \"0\")' class='clickable'>".$operator."</a></div></td>
								<td class='z_text01'><div style='padding:5px;'>".getValue($xvas['data'], 'VESSEL_TYPE')."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".$dwt_type."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".str_replace("m", "", getValue($xvas['data'], 'DRAUGHT'))."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".$speed."</div></td>
								<td style='text-align:center;'><div style='padding:5px;'><img alt=\"".htmlentities($flag)."\" title=\"".htmlentities($flag)."\" src='".$flag_img."' width='22' height='15' ></div></td>
							</tr>
							<tr style='width:992px; background:#fff;'>
								<td colspan='14' id='s_drop5_".$i."' style='display:none;'></td>
							</tr>";
						}
					}
				}elseif($_SESSION['user']['dry']==2){
					$sql  = "SELECT * FROM `_xvas_shipdata_container` WHERE `imo`='".$ships['IMO #']."'";
					$xvas = dbQuery($sql);
					$xvas = $xvas[0];
					
					if(trim($xvas['data'])){
						$status = getValue($xvas['data'], 'STATUS');
						
						if(trim($status)!="DEAD"){
							//UPDATES
							$sql = "SELECT * FROM `_messages` WHERE `imo`='".$ships['IMO #']."' AND type='network' ORDER BY dateadded DESC LIMIT 0,1";
							$bupdate = dbQuery($sql, $link);
							
							$bupdatearr = unserialize($bupdate[0]['message']);
							$delydate_to = strtotime($bupdatearr['delydate_to']);
							
							if(!empty($bupdate)){
								if((time()-$delydate_to)<(60*60*24*15)){
									$updates = "<img src='images/icon_dropdown_warning_broker.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('s_drop5_".$i."', '".$ships['IMO #']."', 'broker');\" id='s_drop5_".$i."_img' />";
								}else{
									$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('s_drop5_".$i."', '".$ships['IMO #']."', 'broker');\" id='s_drop5_".$i."_img' />";
								}
							}else{
								$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('s_drop5_".$i."', '".$ships['IMO #']."', 'broker');\" id='s_drop5_".$i."_img' />";
							}
							//END
							
							//GET SHIP DETAILS
							$hull_type = getValue($xvas['data'], 'HULL_TYPE');
							if($hull_type=='SINGLE HULL'){ $hull_type = 'SH'; }
							else{ $hull_type = 'DH'; }
							
							$owner         = getValue($xvas['data'], 'OWNER');
							$manager_owner = getValue($xvas['data'], 'MANAGER_OWNER');
							$manager       = getValue($xvas['data'], 'MANAGER');
							if(trim($owner)){ $operator = $owner; }
							else if(trim($manager_owner)){ $operator = $manager_owner; }
							else if(trim($manager)){ $operator = $manager; }
							else{ $operator = ""; }
							
							$speed = getValue($xvas['data'], 'SPEED_SERVICE');
							
							if(trim($speed)){ $speed = number_format($speed, 2); }
							else{ $speed = "13.50"; }
							
							$flag     = getValue($xvas['data'], "FLAG");
							$flag_img = getFlagImage($flag);
							//END
							
							echo "<tr style='background:#e5e5e5;'>
								<td style='text-align:center;'><div style='padding:5px;'><input class='pcheck' type='checkbox' name='imos[]'  value='a5_".$ships['IMO #']."'></div></td>
								<td style='text-align:center;'><div style='padding:5px;'>".$updates."</div></td>
								<td>
									<div style='padding:5px;'>
										<table cellpadding='0' cellspacing='0' width='100%'>
											<tr>
												<td width='25'><img src='image.php?b=1&mx=20&p=".$ships['imageb']."'></td>
												<td><a class='clickable' onclick='return showShipDetails(\"".$ships['IMO #']."\")'>".$ships['Ship Name']."</a></td>
											</tr>
										</table>
									</div>
								</td>
								<td style='text-align:right;' class='z_text01'><div style='padding:5px;'>".$bupdatearr['dely']."</div></td>
								<td><div style='padding:5px;'><a class='clickable2' alt=\"".$bupdatearr['delydate_from']."\" title=\"".$bupdatearr['delydate_from']."\">".$bupdatearr['delydate_from']."</a></div></td>
								<td class='z_text01'><div style='padding:5px;'>".$hull_type."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".number_format(str_replace("tons", "", getValue($xvas['data'], 'SUMMER_DWT')))."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".getValue($xvas['data'], 'BUILD')."</div></td>
								<td><div style='padding:5px;'><a onclick='ownerDetails(\"".urlencode($operator)."\", \"0\")' class='clickable'>".$operator."</a></div></td>
								<td class='z_text01'><div style='padding:5px;'>".getValue($xvas['data'], 'VESSEL_TYPE')."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".$dwt_type."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".str_replace("m", "", getValue($xvas['data'], 'DRAUGHT'))."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".$speed."</div></td>
								<td style='text-align:center;'><div style='padding:5px;'><img alt=\"".htmlentities($flag)."\" title=\"".htmlentities($flag)."\" src='".$flag_img."' width='22' height='15' ></div></td>
							</tr>
							<tr style='width:992px; background:#fff;'>
								<td colspan='14' id='s_drop5_".$i."' style='display:none;'></td>
							</tr>";
						}
					}
				}elseif($_SESSION['user']['dry']==0){
					$sql  = "SELECT * FROM `_xvas_shipdata` WHERE `imo`='".$ships['IMO #']."'";
					$xvas = dbQuery($sql);
					$xvas = $xvas[0];
					
					if(trim($xvas['data'])){
						$status = getValue($xvas['data'], 'STATUS');
						
						if(trim($status)!="DEAD"){
							//UPDATES
							$sql = "SELECT * FROM `_messages` WHERE `imo`='".$ships['IMO #']."' AND type='network' ORDER BY dateadded DESC LIMIT 0,1";
							$bupdate = dbQuery($sql, $link);
							
							$bupdatearr = unserialize($bupdate[0]['message']);
							$opendate = strtotime($bupdatearr['opendate']);
							
							if(!empty($bupdate)){
								if((time()-$opendate)<(60*60*24*15)){
									$updates = "<img src='images/icon_dropdown_warning_broker.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('s_drop5_".$i."', '".$ships['IMO #']."', 'broker');\" id='s_drop5_".$i."_img' />";
								}else{
									$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('s_drop5_".$i."', '".$ships['IMO #']."', 'broker');\" id='s_drop5_".$i."_img' />";
								}
							}else{
								$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('s_drop5_".$i."', '".$ships['IMO #']."', 'broker');\" id='s_drop5_".$i."_img' />";
							}
							//END
							
							//GET SHIP DETAILS
							$hull_type = getValue($xvas['data'], 'HULL_TYPE');
							if($hull_type=='SINGLE HULL'){ $hull_type = 'SH'; }
							else{ $hull_type = 'DH'; }
							
							$owner         = getValue($xvas['data'], 'OWNER');
							$manager_owner = getValue($xvas['data'], 'MANAGER_OWNER');
							$manager       = getValue($xvas['data'], 'MANAGER');
							if(trim($owner)){ $operator = $owner; }
							else if(trim($manager_owner)){ $operator = $manager_owner; }
							else if(trim($manager)){ $operator = $manager; }
							else{ $operator = ""; }
							
							$speed = getValue($xvas['data'], 'SPEED_SERVICE');
							
							if(trim($speed)){ $speed = number_format($speed, 2); }
							else{ $speed = "13.50"; }
							
							$flag     = getValue($xvas['data'], "FLAG");
							$flag_img = getFlagImage($flag);
							//END
							
							echo "<tr style='background:#e5e5e5;'>
								<td style='text-align:center;'><div style='padding:5px;'><input class='pcheck' type='checkbox' name='imos[]'  value='a5_".$ships['IMO #']."'></div></td>
								<td style='text-align:center;'><div style='padding:5px;'>".$updates."</div></td>
								<td>
									<div style='padding:5px;'>
										<table cellpadding='0' cellspacing='0' width='100%'>
											<tr>
												<td width='25'><img src='image.php?b=1&mx=20&p=".$ships['imageb']."'></td>
												<td><a class='clickable' onclick='return showShipDetails(\"".$ships['IMO #']."\")'>".$ships['Ship Name']."</a></td>
											</tr>
										</table>
									</div>
								</td>
								<td style='text-align:right;' class='z_text01'><div style='padding:5px;'>".$bupdatearr['openport']."</div></td>
								<td><div style='padding:5px;'><a class='clickable2' alt=\"".$bupdatearr['opendate']."\" title=\"".$bupdatearr['opendate']."\">".$bupdatearr['opendate']."</a></div></td>
								<td class='z_text01'><div style='padding:5px;'>".$hull_type."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".number_format(str_replace("tons", "", getValue($xvas['data'], 'SUMMER_DWT')))."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".getValue($xvas['data'], 'BUILD')."</div></td>
								<td><div style='padding:5px;'><a onclick='ownerDetails(\"".urlencode($operator)."\", \"0\")' class='clickable'>".$operator."</a></div></td>
								<td class='z_text01'><div style='padding:5px;'>".getValue($xvas['data'], 'VESSEL_TYPE')."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".$dwt_type."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".str_replace("m", "", getValue($xvas['data'], 'DRAUGHT'))."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".$speed."</div></td>
								<td style='text-align:center;'><div style='padding:5px;'><img alt=\"".htmlentities($flag)."\" title=\"".htmlentities($flag)."\" src='".$flag_img."' width='22' height='15' ></div></td>
							</tr>
							<tr style='width:992px; background:#fff;'>
								<td colspan='14' id='s_drop5_".$i."' style='display:none;'></td>
							</tr>";
						}
					}
				}
				//END
			//}
		}
		
	echo "</table>
	<table cellpadding='0' cellspacing='0' width='1000'>
		<tr>
			<td>&nbsp;</td>
		</tr>
	</table>";
}
//END OF T5

echo "</form>";
?>