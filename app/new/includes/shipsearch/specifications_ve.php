<?php
echo "<div style='text-align:left; padding:5px;'><b>CURRENT DATE/TIME: ".date("M j, Y G:i e", time())."</b></div>";

//T1
if($t){
	echo "<div style='width:990px; text-align:left; padding:5px; background:#c5dc3b; color:white; margin-top:5px;'>
		<table cellpadding='0' cellspacing='0' width='990px'>
			<tr>
				<td><b style='font-size:14px;'>SHIPS WITH AIS DESTINATIONS & ETA</b></td>
				<td align='right' style='text-align:right; vertical-align:top'><a href='#params'><img style='border:0px' src='images/up_icon.png' alt='back to top' title='back to top'></a>
</td>
			</tr>
		</table>
	</div>
	<table width='1000' style='border:1px solid #000;'>
		<tr>
			<th width='20' style='background-color:#ccc; text-align:center;'><div style='padding:5px;'><img src='images/icon_pencil.png' border='0' /></div></th>
			<th width='110' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Load ETA</div></th>
			<th width='30' style='background:#BCBCBC; color:#333333; text-align:center;'><div style='padding:5px;'>Map</div></th>
			<th width='190' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Name</div></th>
			<th width='50' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Hull</div></th>
			<th width='30' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>DWT</div></th>
			<th width='30' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Built</div></th>
			<th width='200' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Manager / Owner</div></th>
			<th width='130' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Category</div></th>
			<th width='80' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Type</div></th>
			<th width='50' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>DRFT</div></th>
			<th width='50' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Speed</div></th>
			<th width='30' style='background:#BCBCBC; color:#333333; text-align:center;'><div style='padding:5px;'>Flag</div></th>
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
									$updates = "<img src='images/icon_dropdown_warning_shore.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('drop1_".$i."', '".$ships['IMO #']."', 'shore');\" id='drop1_".$i."_img' />";
								}else{
									$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('drop1_".$i."', '".$ships['IMO #']."', 'shore');\" id='drop1_".$i."_img' />";
								}
							}else{
								$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('drop1_".$i."', '".$ships['IMO #']."', 'shore');\" id='drop1_".$i."_img' />";
							}
							//END
							
							//MAP DETAILS
							$details       = array();
							$details['a']  = 'shipsA1print';
							$details['id'] = $i;
							$details       = base64_encode(serialize($details));
							//END
							
							//GET SHIP DETAILS
							$sql  = "SELECT * FROM `_xvas_shipdata` WHERE `imo`='".$ships['IMO #']."'";
							$xvas = dbQuery($sql);
							$xvas = $xvas[0];
					
							if(!trim($xvas['data'])){
								$sql  = "SELECT * FROM `_xvas_shipdata_dry` WHERE `imo`='".$ships['IMO #']."'";
								$xvas = dbQuery($sql);
								$xvas = $xvas[0];
								
								if(!trim($xvas['data'])){
									$sql  = "SELECT * FROM `_xvas_shipdata_container` WHERE `imo`='".$ships['IMO #']."'";
									$xvas = dbQuery($sql);
									$xvas = $xvas[0];
								}
							}
							
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
								<td style='text-align:center;'><div style='padding:5px;'>".$updates."</div></td>
								<td><div style='padding:5px;'><a class='clickable2' alt=\"".$ships['ETA TO LOAD PORT (days)']."\" title=\"".$ships['ETA TO LOAD PORT (days)']."\">".substr($ships['ETA TO LOAD PORT (days)'], 0,11)."</a></div></td>
								<td style='text-align:center;'><div style='padding:5px;'><a class='clickable' onclick='openMapVe(\"shore\", \"".$details."\", \"SHIPS WITH AIS DESTINATIONS & ETA\")'><img title='Map' alt='Map' src='images/map-icon.png'></a></div></td>
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
							<tr style='width:992; background:#fff;'>
								<td colspan='13' id='drop1_".$i."' style='display:none;'></td>
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
									$updates = "<img src='images/icon_dropdown_warning_shore.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('drop1_".$i."', '".$ships['IMO #']."', 'shore');\" id='drop1_".$i."_img' />";
								}else{
									$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('drop1_".$i."', '".$ships['IMO #']."', 'shore');\" id='drop1_".$i."_img' />";
								}
							}else{
								$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('drop1_".$i."', '".$ships['IMO #']."', 'shore');\" id='drop1_".$i."_img' />";
							}
							//END
							
							//MAP DETAILS
							$details       = array();
							$details['a']  = 'shipsA1print';
							$details['id'] = $i;
							$details       = base64_encode(serialize($details));
							//END
							
							//GET SHIP DETAILS
							$sql  = "SELECT * FROM `_xvas_shipdata` WHERE `imo`='".$ships['IMO #']."'";
							$xvas = dbQuery($sql);
							$xvas = $xvas[0];
					
							if(!trim($xvas['data'])){
								$sql  = "SELECT * FROM `_xvas_shipdata_dry` WHERE `imo`='".$ships['IMO #']."'";
								$xvas = dbQuery($sql);
								$xvas = $xvas[0];
								
								if(!trim($xvas['data'])){
									$sql  = "SELECT * FROM `_xvas_shipdata_container` WHERE `imo`='".$ships['IMO #']."'";
									$xvas = dbQuery($sql);
									$xvas = $xvas[0];
								}
							}
							
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
								<td style='text-align:center;'><div style='padding:5px;'>".$updates."</div></td>
								<td><div style='padding:5px;'><a class='clickable2' alt=\"".$ships['ETA TO LOAD PORT (days)']."\" title=\"".$ships['ETA TO LOAD PORT (days)']."\">".substr($ships['ETA TO LOAD PORT (days)'], 0,11)."</a></div></td>
								<td style='text-align:center;'><div style='padding:5px;'><a class='clickable' onclick='openMapVe(\"shore\", \"".$details."\", \"SHIPS WITH AIS DESTINATIONS & ETA\")'><img title='Map' alt='Map' src='images/map-icon.png'></a></div></td>
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
							<tr style='width:992; background:#fff;'>
								<td colspan='13' id='drop1_".$i."' style='display:none;'></td>
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
									$updates = "<img src='images/icon_dropdown_warning_shore.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('drop1_".$i."', '".$ships['IMO #']."', 'shore');\" id='drop1_".$i."_img' />";
								}else{
									$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('drop1_".$i."', '".$ships['IMO #']."', 'shore');\" id='drop1_".$i."_img' />";
								}
							}else{
								$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('drop1_".$i."', '".$ships['IMO #']."', 'shore');\" id='drop1_".$i."_img' />";
							}
							//END
							
							//MAP DETAILS
							$details       = array();
							$details['a']  = 'shipsA1print';
							$details['id'] = $i;
							$details       = base64_encode(serialize($details));
							//END
							
							//GET SHIP DETAILS
							$sql  = "SELECT * FROM `_xvas_shipdata` WHERE `imo`='".$ships['IMO #']."'";
							$xvas = dbQuery($sql);
							$xvas = $xvas[0];
					
							if(!trim($xvas['data'])){
								$sql  = "SELECT * FROM `_xvas_shipdata_dry` WHERE `imo`='".$ships['IMO #']."'";
								$xvas = dbQuery($sql);
								$xvas = $xvas[0];
								
								if(!trim($xvas['data'])){
									$sql  = "SELECT * FROM `_xvas_shipdata_container` WHERE `imo`='".$ships['IMO #']."'";
									$xvas = dbQuery($sql);
									$xvas = $xvas[0];
								}
							}
							
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
								<td style='text-align:center;'><div style='padding:5px;'>".$updates."</div></td>
								<td><div style='padding:5px;'><a class='clickable2' alt=\"".$ships['ETA TO LOAD PORT (days)']."\" title=\"".$ships['ETA TO LOAD PORT (days)']."\">".substr($ships['ETA TO LOAD PORT (days)'], 0,11)."</a></div></td>
								<td style='text-align:center;'><div style='padding:5px;'><a class='clickable' onclick='openMapVe(\"shore\", \"".$details."\", \"SHIPS WITH AIS DESTINATIONS & ETA\")'><img title='Map' alt='Map' src='images/map-icon.png'></a></div></td>
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
							<tr style='width:992; background:#fff;'>
								<td colspan='13' id='drop1_".$i."' style='display:none;'></td>
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
				<td><b style='font-size:14px;'>SHIPS WITH AIS DESTINATIONS & ETA (ACRONYMS OR SPELLING ISSUES)</b></td>
				<td align='right' style='text-align:right; vertical-align:top'><a href='#params'><img style='border:0px' src='images/up_icon.png' alt='back to top' title='back to top'></a>
</td>
			</tr>
		</table>
	</div>
	<table width='1000' style='border:1px solid #000;'>
		<tr>
			<th width='20' style='background-color:#ccc; text-align:center;'><div style='padding:5px;'><img src='images/icon_pencil.png' border='0' /></div></th>
			<th width='110' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Load ETA</div></th>
			<th width='30' style='background:#BCBCBC; color:#333333; text-align:center;'><div style='padding:5px;'>Map</div></th>
			<th width='190' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Name</div></th>
			<th width='50' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Hull</div></th>
			<th width='30' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>DWT</div></th>
			<th width='30' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Built</div></th>
			<th width='200' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Manager / Owner</div></th>
			<th width='130' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Category</div></th>
			<th width='80' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Type</div></th>
			<th width='50' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>DRFT</div></th>
			<th width='50' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Speed</div></th>
			<th width='30' style='background:#BCBCBC; color:#333333; text-align:center;'><div style='padding:5px;'>Flag</div></th>
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
									$updates = "<img src='images/icon_dropdown_warning_shore.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('drop2_".$i."', '".$ships['IMO #']."', 'shore');\" id='drop2_".$i."_img' />";
								}else{
									$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('drop2_".$i."', '".$ships['IMO #']."', 'shore');\" id='drop2_".$i."_img' />";
								}
							}else{
								$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('drop2_".$i."', '".$ships['IMO #']."', 'shore');\" id='drop2_".$i."_img' />";
							}
							//END
							
							//MAP DETAILS
							$details       = array();
							$details['a']  = 'shipsA2print';
							$details['id'] = $i;
							$details       = base64_encode(serialize($details));
							//END
							
							//GET SHIP DETAILS
							$sql  = "SELECT * FROM `_xvas_shipdata` WHERE `imo`='".$ships['IMO #']."'";
							$xvas = dbQuery($sql);
							$xvas = $xvas[0];
					
							if(!trim($xvas['data'])){
								$sql  = "SELECT * FROM `_xvas_shipdata_dry` WHERE `imo`='".$ships['IMO #']."'";
								$xvas = dbQuery($sql);
								$xvas = $xvas[0];
								
								if(!trim($xvas['data'])){
									$sql  = "SELECT * FROM `_xvas_shipdata_container` WHERE `imo`='".$ships['IMO #']."'";
									$xvas = dbQuery($sql);
									$xvas = $xvas[0];
								}
							}
							
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
								<td style='text-align:center;'><div style='padding:5px;'>".$updates."</div></td>
								<td><div style='padding:5px;'><a class='clickable2' alt=\"".$ships['ETA TO LOAD PORT (days)']."\" title=\"".$ships['ETA TO LOAD PORT (days)']."\">".substr($ships['ETA TO LOAD PORT (days)'], 0,11)."</a></div></td>
								<td style='text-align:center;'><div style='padding:5px;'><a class='clickable' onclick='openMapVe(\"shore\", \"".$details."\", \"SHIPS WITH AIS DESTINATIONS & ETA (ACRONYMS OR SPELLING ISSUES)\")'><img title='Map' alt='Map' src='images/map-icon.png'></a></div></td>
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
							<tr style='width:992; background:#fff;'>
								<td colspan='13' id='drop2_".$i."' style='display:none;'></td>
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
									$updates = "<img src='images/icon_dropdown_warning_shore.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('drop2_".$i."', '".$ships['IMO #']."', 'shore');\" id='drop2_".$i."_img' />";
								}else{
									$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('drop2_".$i."', '".$ships['IMO #']."', 'shore');\" id='drop2_".$i."_img' />";
								}
							}else{
								$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('drop2_".$i."', '".$ships['IMO #']."', 'shore');\" id='drop2_".$i."_img' />";
							}
							//END
							
							//MAP DETAILS
							$details       = array();
							$details['a']  = 'shipsA2print';
							$details['id'] = $i;
							$details       = base64_encode(serialize($details));
							//END
							
							//GET SHIP DETAILS
							$sql  = "SELECT * FROM `_xvas_shipdata` WHERE `imo`='".$ships['IMO #']."'";
							$xvas = dbQuery($sql);
							$xvas = $xvas[0];
					
							if(!trim($xvas['data'])){
								$sql  = "SELECT * FROM `_xvas_shipdata_dry` WHERE `imo`='".$ships['IMO #']."'";
								$xvas = dbQuery($sql);
								$xvas = $xvas[0];
								
								if(!trim($xvas['data'])){
									$sql  = "SELECT * FROM `_xvas_shipdata_container` WHERE `imo`='".$ships['IMO #']."'";
									$xvas = dbQuery($sql);
									$xvas = $xvas[0];
								}
							}
							
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
								<td style='text-align:center;'><div style='padding:5px;'>".$updates."</div></td>
								<td><div style='padding:5px;'><a class='clickable2' alt=\"".$ships['ETA TO LOAD PORT (days)']."\" title=\"".$ships['ETA TO LOAD PORT (days)']."\">".substr($ships['ETA TO LOAD PORT (days)'], 0,11)."</a></div></td>
								<td style='text-align:center;'><div style='padding:5px;'><a class='clickable' onclick='openMapVe(\"shore\", \"".$details."\", \"SHIPS WITH AIS DESTINATIONS & ETA (ACRONYMS OR SPELLING ISSUES)\")'><img title='Map' alt='Map' src='images/map-icon.png'></a></div></td>
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
							<tr style='width:992; background:#fff;'>
								<td colspan='13' id='drop2_".$i."' style='display:none;'></td>
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
									$updates = "<img src='images/icon_dropdown_warning_shore.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('drop2_".$i."', '".$ships['IMO #']."', 'shore');\" id='drop2_".$i."_img' />";
								}else{
									$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('drop2_".$i."', '".$ships['IMO #']."', 'shore');\" id='drop2_".$i."_img' />";
								}
							}else{
								$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('drop2_".$i."', '".$ships['IMO #']."', 'shore');\" id='drop2_".$i."_img' />";
							}
							//END
							
							//MAP DETAILS
							$details       = array();
							$details['a']  = 'shipsA2print';
							$details['id'] = $i;
							$details       = base64_encode(serialize($details));
							//END
							
							//GET SHIP DETAILS
							$sql  = "SELECT * FROM `_xvas_shipdata` WHERE `imo`='".$ships['IMO #']."'";
							$xvas = dbQuery($sql);
							$xvas = $xvas[0];
					
							if(!trim($xvas['data'])){
								$sql  = "SELECT * FROM `_xvas_shipdata_dry` WHERE `imo`='".$ships['IMO #']."'";
								$xvas = dbQuery($sql);
								$xvas = $xvas[0];
								
								if(!trim($xvas['data'])){
									$sql  = "SELECT * FROM `_xvas_shipdata_container` WHERE `imo`='".$ships['IMO #']."'";
									$xvas = dbQuery($sql);
									$xvas = $xvas[0];
								}
							}
							
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
								<td style='text-align:center;'><div style='padding:5px;'>".$updates."</div></td>
								<td><div style='padding:5px;'><a class='clickable2' alt=\"".$ships['ETA TO LOAD PORT (days)']."\" title=\"".$ships['ETA TO LOAD PORT (days)']."\">".substr($ships['ETA TO LOAD PORT (days)'], 0,11)."</a></div></td>
								<td style='text-align:center;'><div style='padding:5px;'><a class='clickable' onclick='openMapVe(\"shore\", \"".$details."\", \"SHIPS WITH AIS DESTINATIONS & ETA (ACRONYMS OR SPELLING ISSUES)\")'><img title='Map' alt='Map' src='images/map-icon.png'></a></div></td>
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
							<tr style='width:992; background:#fff;'>
								<td colspan='13' id='drop2_".$i."' style='display:none;'></td>
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
				<td align='right' style='text-align:right; vertical-align:top'><a href='#params'><img style='border:0px' src='images/up_icon.png' alt='back to top' title='back to top'></a>
</td>
			</tr>
		</table>
	</div>
	<table width='1000' style='border:1px solid #000;'>
		<tr>
			<th width='20' style='background-color:#ccc; text-align:center;'><div style='padding:5px;'><img src='images/icon_pencil.png' border='0' /></div></th>
			<th width='110' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Open ETA</div></th>
			<th width='30' style='background:#BCBCBC; color:#333333; text-align:center;'><div style='padding:5px;'>Map</div></th>
			<th width='190' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Name</div></th>
			<th width='50' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Hull</div></th>
			<th width='30' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>DWT</div></th>
			<th width='30' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Built</div></th>
			<th width='200' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Manager / Owner</div></th>
			<th width='130' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Category</div></th>
			<th width='80' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Type</div></th>
			<th width='50' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>DRFT</div></th>
			<th width='50' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Speed</div></th>
			<th width='30' style='background:#BCBCBC; color:#333333; text-align:center;'><div style='padding:5px;'>Flag</div></th>
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
									$updates = "<img src='images/icon_dropdown_warning_broker.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('drop3_".$i."', '".$ships['IMO #']."', 'broker');\" id='drop3_".$i."_img' />";
								}else{
									$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('drop3_".$i."', '".$ships['IMO #']."', 'broker');\" id='drop3_".$i."_img' />";
								}
							}else{
								$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('drop3_".$i."', '".$ships['IMO #']."', 'broker');\" id='drop3_".$i."_img' />";
							}
							//END
							
							//MAP DETAILS
							$details       = array();
							$details['a']  = 'shipsA3print';
							$details['id'] = $i;
							$details       = base64_encode(serialize($details));
							//END
							
							//GET SHIP DETAILS
							$sql  = "SELECT * FROM `_xvas_shipdata` WHERE `imo`='".$ships['IMO #']."'";
							$xvas = dbQuery($sql);
							$xvas = $xvas[0];
					
							if(!trim($xvas['data'])){
								$sql  = "SELECT * FROM `_xvas_shipdata_dry` WHERE `imo`='".$ships['IMO #']."'";
								$xvas = dbQuery($sql);
								$xvas = $xvas[0];
								
								if(!trim($xvas['data'])){
									$sql  = "SELECT * FROM `_xvas_shipdata_container` WHERE `imo`='".$ships['IMO #']."'";
									$xvas = dbQuery($sql);
									$xvas = $xvas[0];
								}
							}
							
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
								<td style='text-align:center;'><div style='padding:5px;'>".$updates."</div></td>
								<td><div style='padding:5px;'><a class='clickable2' alt=\"".$bupdatearr['delydate_from']."\" title=\"".$bupdatearr['delydate_from']."\">".$bupdatearr['delydate_from']."</a></div></td>
								<td style='text-align:center;'><div style='padding:5px;'><a class='clickable' onclick='openMapVe2(\"broker\", \"".$details."\", \"SHIPS WITH CONFIRMED OPEN PORT USING BROKERSINTELLIGENCE\")'><img title='Map' alt='Map' src='images/map-icon.png'></a></div></td>
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
							<tr style='width:992; background:#fff;'>
								<td colspan='13' id='drop3_".$i."' style='display:none;'></td>
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
									$updates = "<img src='images/icon_dropdown_warning_broker.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('drop3_".$i."', '".$ships['IMO #']."', 'broker');\" id='drop3_".$i."_img' />";
								}else{
									$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('drop3_".$i."', '".$ships['IMO #']."', 'broker');\" id='drop3_".$i."_img' />";
								}
							}else{
								$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('drop3_".$i."', '".$ships['IMO #']."', 'broker');\" id='drop3_".$i."_img' />";
							}
							//END
							
							//MAP DETAILS
							$details       = array();
							$details['a']  = 'shipsA3print';
							$details['id'] = $i;
							$details       = base64_encode(serialize($details));
							//END
							
							//GET SHIP DETAILS
							$sql  = "SELECT * FROM `_xvas_shipdata` WHERE `imo`='".$ships['IMO #']."'";
							$xvas = dbQuery($sql);
							$xvas = $xvas[0];
					
							if(!trim($xvas['data'])){
								$sql  = "SELECT * FROM `_xvas_shipdata_dry` WHERE `imo`='".$ships['IMO #']."'";
								$xvas = dbQuery($sql);
								$xvas = $xvas[0];
								
								if(!trim($xvas['data'])){
									$sql  = "SELECT * FROM `_xvas_shipdata_container` WHERE `imo`='".$ships['IMO #']."'";
									$xvas = dbQuery($sql);
									$xvas = $xvas[0];
								}
							}
							
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
								<td style='text-align:center;'><div style='padding:5px;'>".$updates."</div></td>
								<td><div style='padding:5px;'><a class='clickable2' alt=\"".$bupdatearr['delydate_from']."\" title=\"".$bupdatearr['delydate_from']."\">".$bupdatearr['delydate_from']."</a></div></td>
								<td style='text-align:center;'><div style='padding:5px;'><a class='clickable' onclick='openMapVe2(\"broker\", \"".$details."\", \"SHIPS WITH CONFIRMED OPEN PORT USING BROKERSINTELLIGENCE\")'><img title='Map' alt='Map' src='images/map-icon.png'></a></div></td>
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
							<tr style='width:992; background:#fff;'>
								<td colspan='13' id='drop3_".$i."' style='display:none;'></td>
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
									$updates = "<img src='images/icon_dropdown_warning_broker.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('drop3_".$i."', '".$ships['IMO #']."', 'broker');\" id='drop3_".$i."_img' />";
								}else{
									$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('drop3_".$i."', '".$ships['IMO #']."', 'broker');\" id='drop3_".$i."_img' />";
								}
							}else{
								$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('drop3_".$i."', '".$ships['IMO #']."', 'broker');\" id='drop3_".$i."_img' />";
							}
							//END
							
							//MAP DETAILS
							$details       = array();
							$details['a']  = 'shipsA3print';
							$details['id'] = $i;
							$details       = base64_encode(serialize($details));
							//END
							
							//GET SHIP DETAILS
							$sql  = "SELECT * FROM `_xvas_shipdata` WHERE `imo`='".$ships['IMO #']."'";
							$xvas = dbQuery($sql);
							$xvas = $xvas[0];
					
							if(!trim($xvas['data'])){
								$sql  = "SELECT * FROM `_xvas_shipdata_dry` WHERE `imo`='".$ships['IMO #']."'";
								$xvas = dbQuery($sql);
								$xvas = $xvas[0];
								
								if(!trim($xvas['data'])){
									$sql  = "SELECT * FROM `_xvas_shipdata_container` WHERE `imo`='".$ships['IMO #']."'";
									$xvas = dbQuery($sql);
									$xvas = $xvas[0];
								}
							}
							
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
								<td style='text-align:center;'><div style='padding:5px;'>".$updates."</div></td>
								<td><div style='padding:5px;'><a class='clickable2' alt=\"".$bupdatearr['opendate']."\" title=\"".$bupdatearr['opendate']."\">".$bupdatearr['opendate']."</a></div></td>
								<td style='text-align:center;'><div style='padding:5px;'><a class='clickable' onclick='openMapVe2(\"broker\", \"".$details."\", \"SHIPS WITH CONFIRMED OPEN PORT USING BROKERSINTELLIGENCE\")'><img title='Map' alt='Map' src='images/map-icon.png'></a></div></td>
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
							<tr style='width:992; background:#fff;'>
								<td colspan='13' id='drop3_".$i."' style='display:none;'></td>
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
				<td align='right' style='text-align:right; vertical-align:top'><a href='#params'><img style='border:0px' src='images/up_icon.png' alt='back to top' title='back to top'></a>
</td>
			</tr>
		</table>
	</div>
	<table width='1000' style='border:1px solid #000;'>
		<tr>
			<th width='20' style='background-color:#ccc; text-align:center;'><div style='padding:5px;'><img src='images/icon_pencil.png' border='0' /></div></th>
			<th width='110' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Open ETA</div></th>
			<th width='30' style='background:#BCBCBC; color:#333333; text-align:center;'><div style='padding:5px;'>Map</div></th>
			<th width='190' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Name</div></th>
			<th width='50' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Hull</div></th>
			<th width='30' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>DWT</div></th>
			<th width='30' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Built</div></th>
			<th width='200' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Manager / Owner</div></th>
			<th width='130' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Category</div></th>
			<th width='80' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Type</div></th>
			<th width='50' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>DRFT</div></th>
			<th width='50' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Speed</div></th>
			<th width='30' style='background:#BCBCBC; color:#333333; text-align:center;'><div style='padding:5px;'>Flag</div></th>
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
									$updates = "<img src='images/icon_dropdown_warning_broker.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('drop4_".$i."', '".$ships['IMO #']."', 'broker');\" id='drop4_".$i."_img' />";
								}else{
									$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('drop4_".$i."', '".$ships['IMO #']."', 'broker');\" id='drop4_".$i."_img' />";
								}
							}else{
								$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('drop4_".$i."', '".$ships['IMO #']."', 'broker');\" id='drop4_".$i."_img' />";
							}
							//END
							
							//MAP DETAILS
							$details       = array();
							$details['a']  = 'shipsA4print';
							$details['id'] = $i;
							$details       = base64_encode(serialize($details));
							//END
							
							//GET SHIP DETAILS
							$sql  = "SELECT * FROM `_xvas_shipdata` WHERE `imo`='".$ships['IMO #']."'";
							$xvas = dbQuery($sql);
							$xvas = $xvas[0];
					
							if(!trim($xvas['data'])){
								$sql  = "SELECT * FROM `_xvas_shipdata_dry` WHERE `imo`='".$ships['IMO #']."'";
								$xvas = dbQuery($sql);
								$xvas = $xvas[0];
								
								if(!trim($xvas['data'])){
									$sql  = "SELECT * FROM `_xvas_shipdata_container` WHERE `imo`='".$ships['IMO #']."'";
									$xvas = dbQuery($sql);
									$xvas = $xvas[0];
								}
							}
							
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
								<td style='text-align:center;'><div style='padding:5px;'>".$updates."</div></td>
								<td><div style='padding:5px;'><a class='clickable2' alt=\"".$bupdatearr['delydate_from']."\" title=\"".$bupdatearr['delydate_from']."\">".$bupdatearr['delydate_from']."</a></div></td>
								<td style='text-align:center;'><div style='padding:5px;'><a class='clickable' onclick='openMapVe2(\"broker\", \"".$details."\", \"SHIPS WITH LAST KNOWN POSITIONS USING BROKERSINTELLIGENCE\")'><img title='Map' alt='Map' src='images/map-icon.png'></a></div></td>
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
							<tr style='width:992; background:#fff;'>
								<td colspan='13' id='drop4_".$i."' style='display:none;'></td>
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
									$updates = "<img src='images/icon_dropdown_warning_broker.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('drop4_".$i."', '".$ships['IMO #']."', 'broker');\" id='drop4_".$i."_img' />";
								}else{
									$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('drop4_".$i."', '".$ships['IMO #']."', 'broker');\" id='drop4_".$i."_img' />";
								}
							}else{
								$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('drop4_".$i."', '".$ships['IMO #']."', 'broker');\" id='drop4_".$i."_img' />";
							}
							//END
							
							//MAP DETAILS
							$details       = array();
							$details['a']  = 'shipsA4print';
							$details['id'] = $i;
							$details       = base64_encode(serialize($details));
							//END
							
							//GET SHIP DETAILS
							$sql  = "SELECT * FROM `_xvas_shipdata` WHERE `imo`='".$ships['IMO #']."'";
							$xvas = dbQuery($sql);
							$xvas = $xvas[0];
					
							if(!trim($xvas['data'])){
								$sql  = "SELECT * FROM `_xvas_shipdata_dry` WHERE `imo`='".$ships['IMO #']."'";
								$xvas = dbQuery($sql);
								$xvas = $xvas[0];
								
								if(!trim($xvas['data'])){
									$sql  = "SELECT * FROM `_xvas_shipdata_container` WHERE `imo`='".$ships['IMO #']."'";
									$xvas = dbQuery($sql);
									$xvas = $xvas[0];
								}
							}
							
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
								<td style='text-align:center;'><div style='padding:5px;'>".$updates."</div></td>
								<td><div style='padding:5px;'><a class='clickable2' alt=\"".$bupdatearr['delydate_from']."\" title=\"".$bupdatearr['delydate_from']."\">".$bupdatearr['delydate_from']."</a></div></td>
								<td style='text-align:center;'><div style='padding:5px;'><a class='clickable' onclick='openMapVe2(\"broker\", \"".$details."\", \"SHIPS WITH LAST KNOWN POSITIONS USING BROKERSINTELLIGENCE\")'><img title='Map' alt='Map' src='images/map-icon.png'></a></div></td>
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
							<tr style='width:992; background:#fff;'>
								<td colspan='13' id='drop4_".$i."' style='display:none;'></td>
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
									$updates = "<img src='images/icon_dropdown_warning_broker.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('drop4_".$i."', '".$ships['IMO #']."', 'broker');\" id='drop4_".$i."_img' />";
								}else{
									$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('drop4_".$i."', '".$ships['IMO #']."', 'broker');\" id='drop4_".$i."_img' />";
								}
							}else{
								$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('drop4_".$i."', '".$ships['IMO #']."', 'broker');\" id='drop4_".$i."_img' />";
							}
							//END
							
							//MAP DETAILS
							$details       = array();
							$details['a']  = 'shipsA4print';
							$details['id'] = $i;
							$details       = base64_encode(serialize($details));
							//END
							
							//GET SHIP DETAILS
							$sql  = "SELECT * FROM `_xvas_shipdata` WHERE `imo`='".$ships['IMO #']."'";
							$xvas = dbQuery($sql);
							$xvas = $xvas[0];
					
							if(!trim($xvas['data'])){
								$sql  = "SELECT * FROM `_xvas_shipdata_dry` WHERE `imo`='".$ships['IMO #']."'";
								$xvas = dbQuery($sql);
								$xvas = $xvas[0];
								
								if(!trim($xvas['data'])){
									$sql  = "SELECT * FROM `_xvas_shipdata_container` WHERE `imo`='".$ships['IMO #']."'";
									$xvas = dbQuery($sql);
									$xvas = $xvas[0];
								}
							}
							
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
								<td style='text-align:center;'><div style='padding:5px;'>".$updates."</div></td>
								<td><div style='padding:5px;'><a class='clickable2' alt=\"".$bupdatearr['opendate']."\" title=\"".$bupdatearr['opendate']."\">".$bupdatearr['opendate']."</a></div></td>
								<td style='text-align:center;'><div style='padding:5px;'><a class='clickable' onclick='openMapVe2(\"broker\", \"".$details."\", \"SHIPS WITH LAST KNOWN POSITIONS USING BROKERSINTELLIGENCE\")'><img title='Map' alt='Map' src='images/map-icon.png'></a></div></td>
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
							<tr style='width:992; background:#fff;'>
								<td colspan='13' id='drop4_".$i."' style='display:none;'></td>
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
				<td align='right' style='text-align:right; vertical-align:top'><a href='#params'><img style='border:0px' src='images/up_icon.png' alt='back to top' title='back to top'></a>
</td>
			</tr>
		</table>
	</div>
	<table width='1000' style='border:1px solid #000;'>
		<tr>
			<th width='20' style='background-color:#ccc; text-align:center;'><div style='padding:5px;'><img src='images/icon_pencil.png' border='0' /></div></th>
			<th width='110' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Open ETA</div></th>
			<th width='30' style='background:#BCBCBC; color:#333333; text-align:center;'><div style='padding:5px;'>Map</div></th>
			<th width='190' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Name</div></th>
			<th width='50' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Hull</div></th>
			<th width='30' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>DWT</div></th>
			<th width='30' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Built</div></th>
			<th width='200' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Manager / Owner</div></th>
			<th width='130' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Category</div></th>
			<th width='80' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Type</div></th>
			<th width='50' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>DRFT</div></th>
			<th width='50' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Speed</div></th>
			<th width='30' style='background:#BCBCBC; color:#333333; text-align:center;'><div style='padding:5px;'>Flag</div></th>
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
									$updates = "<img src='images/icon_dropdown_warning_broker.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('drop5_".$i."', '".$ships['IMO #']."', 'broker');\" id='drop5_".$i."_img' />";
								}else{
									$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('drop5_".$i."', '".$ships['IMO #']."', 'broker');\" id='drop5_".$i."_img' />";
								}
							}else{
								$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('drop5_".$i."', '".$ships['IMO #']."', 'broker');\" id='drop5_".$i."_img' />";
							}
							//END
							
							//MAP DETAILS
							$details       = array();
							$details['a']  = 'shipsA5print';
							$details['id'] = $i;
							$details       = base64_encode(serialize($details));
							//END
							
							//GET SHIP DETAILS
							$sql  = "SELECT * FROM `_xvas_shipdata` WHERE `imo`='".$ships['IMO #']."'";
							$xvas = dbQuery($sql);
							$xvas = $xvas[0];
					
							if(!trim($xvas['data'])){
								$sql  = "SELECT * FROM `_xvas_shipdata_dry` WHERE `imo`='".$ships['IMO #']."'";
								$xvas = dbQuery($sql);
								$xvas = $xvas[0];
								
								if(!trim($xvas['data'])){
									$sql  = "SELECT * FROM `_xvas_shipdata_container` WHERE `imo`='".$ships['IMO #']."'";
									$xvas = dbQuery($sql);
									$xvas = $xvas[0];
								}
							}
							
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
								<td style='text-align:center;'><div style='padding:5px;'>".$updates."</div></td>
								<td><div style='padding:5px;'><a class='clickable2' alt=\"".$bupdatearr['delydate_from']."\" title=\"".$bupdatearr['delydate_from']."\">".$bupdatearr['delydate_from']."</a></div></td>
								<td style='text-align:center;'><div style='padding:5px;'><a class='clickable' onclick='openMapVe2(\"broker\", \"".$details."\", \"SHIPS FOUND USING BROKERSINTELLIGENCE\")'><img title='Map' alt='Map' src='images/map-icon.png'></a></div></td>
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
							<tr style='width:992; background:#fff;'>
								<td colspan='13' id='drop5_".$i."' style='display:none;'></td>
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
									$updates = "<img src='images/icon_dropdown_warning_broker.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('drop5_".$i."', '".$ships['IMO #']."', 'broker');\" id='drop5_".$i."_img' />";
								}else{
									$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('drop5_".$i."', '".$ships['IMO #']."', 'broker');\" id='drop5_".$i."_img' />";
								}
							}else{
								$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('drop5_".$i."', '".$ships['IMO #']."', 'broker');\" id='drop5_".$i."_img' />";
							}
							//END
							
							//MAP DETAILS
							$details       = array();
							$details['a']  = 'shipsA5print';
							$details['id'] = $i;
							$details       = base64_encode(serialize($details));
							//END
							
							//GET SHIP DETAILS
							$sql  = "SELECT * FROM `_xvas_shipdata` WHERE `imo`='".$ships['IMO #']."'";
							$xvas = dbQuery($sql);
							$xvas = $xvas[0];
					
							if(!trim($xvas['data'])){
								$sql  = "SELECT * FROM `_xvas_shipdata_dry` WHERE `imo`='".$ships['IMO #']."'";
								$xvas = dbQuery($sql);
								$xvas = $xvas[0];
								
								if(!trim($xvas['data'])){
									$sql  = "SELECT * FROM `_xvas_shipdata_container` WHERE `imo`='".$ships['IMO #']."'";
									$xvas = dbQuery($sql);
									$xvas = $xvas[0];
								}
							}
							
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
								<td style='text-align:center;'><div style='padding:5px;'>".$updates."</div></td>
								<td><div style='padding:5px;'><a class='clickable2' alt=\"".$bupdatearr['delydate_from']."\" title=\"".$bupdatearr['delydate_from']."\">".$bupdatearr['delydate_from']."</a></div></td>
								<td style='text-align:center;'><div style='padding:5px;'><a class='clickable' onclick='openMapVe2(\"broker\", \"".$details."\", \"SHIPS FOUND USING BROKERSINTELLIGENCE\")'><img title='Map' alt='Map' src='images/map-icon.png'></a></div></td>
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
							<tr style='width:992; background:#fff;'>
								<td colspan='13' id='drop5_".$i."' style='display:none;'></td>
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
									$updates = "<img src='images/icon_dropdown_warning_broker.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('drop5_".$i."', '".$ships['IMO #']."', 'broker');\" id='drop5_".$i."_img' />";
								}else{
									$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('drop5_".$i."', '".$ships['IMO #']."', 'broker');\" id='drop5_".$i."_img' />";
								}
							}else{
								$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('drop5_".$i."', '".$ships['IMO #']."', 'broker');\" id='drop5_".$i."_img' />";
							}
							//END
							
							//MAP DETAILS
							$details       = array();
							$details['a']  = 'shipsA5print';
							$details['id'] = $i;
							$details       = base64_encode(serialize($details));
							//END
							
							//GET SHIP DETAILS
							$sql  = "SELECT * FROM `_xvas_shipdata` WHERE `imo`='".$ships['IMO #']."'";
							$xvas = dbQuery($sql);
							$xvas = $xvas[0];
					
							if(!trim($xvas['data'])){
								$sql  = "SELECT * FROM `_xvas_shipdata_dry` WHERE `imo`='".$ships['IMO #']."'";
								$xvas = dbQuery($sql);
								$xvas = $xvas[0];
								
								if(!trim($xvas['data'])){
									$sql  = "SELECT * FROM `_xvas_shipdata_container` WHERE `imo`='".$ships['IMO #']."'";
									$xvas = dbQuery($sql);
									$xvas = $xvas[0];
								}
							}
							
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
								<td style='text-align:center;'><div style='padding:5px;'>".$updates."</div></td>
								<td><div style='padding:5px;'><a class='clickable2' alt=\"".$bupdatearr['opendate']."\" title=\"".$bupdatearr['opendate']."\">".$bupdatearr['opendate']."</a></div></td>
								<td style='text-align:center;'><div style='padding:5px;'><a class='clickable' onclick='openMapVe2(\"broker\", \"".$details."\", \"SHIPS FOUND USING BROKERSINTELLIGENCE\")'><img title='Map' alt='Map' src='images/map-icon.png'></a></div></td>
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
							<tr style='width:992; background:#fff;'>
								<td colspan='13' id='drop5_".$i."' style='display:none;'></td>
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
?>