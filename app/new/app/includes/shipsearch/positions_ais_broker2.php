<?php
echo "<div style='text-align:left; padding:5px;'><b>CURRENT DATE/TIME: ".date("M j, Y G:i e", time())."</b></div>";

if($t_ships){
	echo "<form id='positions' method='POST' style='margin:0px;'>";
	echo "<div style='width:990px; text-align:left; padding:5px; background:#c5dc3b; color:white; margin-top:5px;'>
		<table cellpadding='0' cellspacing='0' width='990px'>
			<tr>
				<td><b style='font-size:14px;'>SHIPS WITH AIS DESTINATIONS & ETA</b></td>
				<td align='right' style='text-align:right; vertical-align:top'>
					<a class='clickable' onclick=\"printIt('position')\"><img src='images/print.jpg'></a>
					<a class='clickable' onclick=\"mailIt('position')\"><img src='images/email_small.jpg'></a>
					<a href='#params'><img style='border:0px' src='images/up_icon.png' alt='back to top' title='back to top'></a>
				</td>
			</tr>
		</table>
	</div>
	<table id='p_pgreens1' width='1000px' style='border:1px solid #000;'>
		<tr>
			<th width='20px' style='text-align:center; background-color:#ccc;'><div style='padding:5px;'><input type='checkbox' onclick=\"checkAll('p_pgreens1', this)\" ></div></th>
			<th width='20px' style='background-color:#ccc; text-align:center;'><div style='padding:5px;'><img src='images/icon_book.png' border='0' /></div></th>
			<th width='220px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Name</div></th>
			<th width='250px' style='background:#BCBCBC; color:#333333; text-align:right;'><div style='padding:5px;'>Destination</div></th>
			<th width='130px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>ETA</div></th>
			<th width='90px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Heading</div></th>
			<th width='90px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>AIS Speed</div></th>
			<th width='110px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Stated Speed</div></th>
			<th width='160px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Private Message</div></th>
		</tr>";
		
		for($i_ships=0; $i_ships<$t_ships; $i_ships++){
			$ships = $r_ships[$i_ships];
			
			$sql_vp = "SELECT * FROM `_veson_ports` WHERE `name`='".$ships['siitech_destination']."' LIMIT 0, 1";
			$r_vp = dbQuery($sql_vp, $link);
			
			if(!trim($r_vp[0]['id'])){
				$sql_vp = "SELECT * FROM `_other_ports` WHERE `name`='".$ships['siitech_destination']."' LIMIT 0, 1";
				$r_vp = dbQuery($sql_vp, $link);
			}
			
			if($r_vp[0]['id']){
				$portid = $r_vp[0]['portid'];
				$port_latitude = $r_vp[0]['latitude'];
				$port_longitude = $r_vp[0]['longitude'];
				
				$sql_zb = "SELECT * FROM `_sbis_zoneblocks` WHERE `zone_code`='".$zone_code."' and ".$port_longitude.">=`long1` and ".$port_longitude."<=`long2` and ".$port_latitude."<=`lat1` and ".$port_latitude.">=`lat4` LIMIT 0, 1";
				$r_zb = dbQuery($sql_zb, $link);
				
				if($r_zb[0]['id']){
					$_SESSION['r_ships'][$i_ships] = $ships;
					
					$imo = $ships['xvas_imo'];
					
					$sql  = "SELECT * FROM `_xvas_shipdata_dry` WHERE `imo`='".$imo."'";
					$xvas = dbQuery($sql);
					$xvas = $xvas[0];
					
					if(trim($xvas['data'])){
						$name = getValue($xvas['data'], 'NAME');
						$destination_eta = date("M j, 'y G:i e", strtotime($ships['siitech_eta']));
						$imageb = base64_encode("http://dataservice.grosstonnage.com/S-Bisphoto.php?imo=".$ships['xvas_imo']);
						
						$ship_img = "<img src='image.php?b=1&mx=20&p=".$imageb."'>";
						$ship_name = "<a class='clickable' onclick='return showShipDetails(\"".$imo."\")'>".$name."</a>";
						
						$destination = $ships['siitech_destination'];
						if(!trim($destination)){ $destination = "<img style='height:15px; width:15px;' src='images/alert1.png' alt='No AIS Data Available' title='No AIS Data Available' />"; }
						
						$destination_eta = "<a class='clickable2' alt=\"".$destination_eta."\" title=\"".$destination_eta."\" >".substr($destination_eta, 0,11)."</a>";
						
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
						
						//UPDATES
						$sql = "SELECT * FROM `_messages` WHERE `imo`='".$imo."' AND type='network' ORDER BY dateadded DESC LIMIT 0,1";
						$bupdate = dbQuery($sql, $link);
						
						$bupdatearr = unserialize($bupdate[0]['message']);
						$delydate_to = strtotime($bupdatearr['delydate_to']);
						
						$sql = "SELECT * FROM `_operators_update` WHERE `imo`='".$imo."' AND type='op_update' ORDER BY dateadded DESC LIMIT 0,1";
						$oupdate = dbQuery($sql, $link);
						
						$sql = "SELECT * FROM `_blackbox_vessel` WHERE `vessel_name`='".$name."' ORDER BY latest_created DESC LIMIT 0,1";
						$eupdate = dbQuery($sql, $link);
						
						if(!empty($bupdate) || !empty($oupdate) || !empty($eupdate)){
							if((time()-$delydate_to)<(60*60*24*15) || (time()-strtotime(date('M d, Y', strtotime($eupdate[0]['from_time']))))<(60*60*24*15) || !empty($oupdate)){
								$updates = "<img src='images/icon_dropdown_warning_shore.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('p_drop1_".$i_ships."', '".$imo."', 'shore');\" id='p_drop1_".$i_ships."_img' />";
							}else{
								$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('p_drop1_".$i_ships."', '".$imo."', 'shore');\" id='p_drop1_".$i_ships."_img' />";
							}
						}else{
							$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('p_drop1_".$i_ships."', '".$imo."', 'shore');\" id='p_drop1_".$i_ships."_img' />";
						}
						//END
						
						//MAP DETAILS
						$details = array();
						$details['a'] = 'r_ships';
						$details['id'] = $i_ships;
						$details['portid'] = $portid;
						$details['port_latitude'] = $port_latitude;
						$details['port_longitude'] = $port_longitude;
						$details = base64_encode(serialize($details));
						//END
						
						$private     = getMessageByImo($imo, 'private');
						$mid         = $private['id'];
						$private     = stripslashes($private['message']);
						$private_alt = htmlentities($private);
						$private_msg = word_limit($private, 2);
						
						echo "<tr style='background:#e5e5e5;'>
							<td style='text-align:center;'><div style='padding:5px;'><input class='pcheck' type='checkbox' name='imos[]'  value='a1_".$imo."'></div></td>
							<td style='text-align:center;'><div style='padding:5px;'>".$updates."</div></td>
							<td>
								<div style='padding:5px;'>
									<table cellpadding='0' cellspacing='0' width='100%'>
										<tr>
											<td width='25'>".$ship_img."</td>
											<td>".$ship_name."</td>
										</tr>
									</table>
								</div>
							</td>
							<td>
								<div style='padding:5px;'>
									<table width='100%' cellpadding='0' cellspacing='0'>
										<tr>
											<td style='text-align:right;'>".$destination."</td>
											<td style='text-align:center; width:20px;'><a class='clickable' onclick='openMapVe2(\"".$details."\")'><img title='Map' alt='Map' src='images/map-icon.png'></a></td>
										</tr>
									</table>
								</div>
							</td>
							<td><div style='padding:5px;'>".$destination_eta."</div></td>
							<td class='z_text01'><div style='padding:5px;'>".$heading."</div></td>
							<td class='z_text01'><div style='padding:5px;'>".$ais_speed."</div></td>
							<td class='z_text01'><div style='padding:5px;'>".$stated_speed."</div></td>
							<td class='message' style='padding:0px 3px 0px 3px' alt=\"".$private_alt."\" title=\"".$private_alt."\" id=\"".$mid."\" onclick='openMessageDialog(this.id, \"".$imo."\", \"private\")' >
								<input type='hidden' class='pmessages' value=\"private3_".$imo."\" >
								<div id=\"private3_".$imo."\">".$private_msg."</div>
							</td>
						</tr>
						<tr style='width:992px; background:#fff;'>
							<td colspan='15' id='p_drop1_".$i_ships."' style='display:none;'></td>
						</tr>";
					}
				}
			}
		}
		
	echo '</table>';
	echo '</form>';
}

if($t_broker){
	$counter = 1;
	for($i_broker=0; $i_broker<$t_broker; $i_broker++){
		$nmessage = unserialize($r_broker[$i_broker]['message']);
	
		$sql_vp = "SELECT * FROM `_veson_ports` WHERE `name`='".$nmessage['dely']."' LIMIT 0, 1";
		$r_vp = dbQuery($sql_vp, $link);
		
		if(!trim($r_vp[0]['id'])){
			$sql_vp = "SELECT * FROM `_other_ports` WHERE `name`='".$nmessage['dely']."' LIMIT 0, 1";
			$r_vp = dbQuery($sql_vp, $link);
		}
		
		if($r_vp[0]['id']){
			$portid = $r_vp[0]['portid'];
			$port_latitude = $r_vp[0]['latitude'];
			$port_longitude = $r_vp[0]['longitude'];
			
			$sql_zb = "SELECT * FROM `_sbis_zoneblocks` WHERE `zone_code`='".$zone_code."' and ".$port_longitude.">=`long1` and ".$port_longitude."<=`long2` and ".$port_latitude."<=`lat1` and ".$port_latitude.">=`lat4` LIMIT 0, 1";
			$r_zb = dbQuery($sql_zb, $link);
			
			if($r_zb[0]['id']){
				if(date('Ymd', strtotime($destination_port_from))<=date('Ymd', strtotime($nmessage['delydate_from'])) && date('Ymd', strtotime($destination_port_to))>=date('Ymd', strtotime($nmessage['delydate_from']))){
					if($counter==1){
						echo "</table>
						<table cellpadding='0' cellspacing='0' width='1000'>
							<tr>
								<td>&nbsp;</td>
							</tr>
						</table>";
						echo "<div style='width:990px; text-align:left; padding:5px; background:#ffc000; color:white; margin-top:5px;'>
							<table cellpadding='0' cellspacing='0' width='990px'>
								<tr>
									<td><b style='font-size:14px;'>BROKER INTELLIGENCE</b></td>
									<td align='right' style='text-align:right; vertical-align:top'>
										<a class='clickable' onclick=\"printIt('position')\"><img src='images/print.jpg'></a>
										<a class='clickable' onclick=\"mailIt('position')\"><img src='images/email_small.jpg'></a>
										<a href='#params'><img style='border:0px' src='images/up_icon.png' alt='back to top' title='back to top'></a>
									</td>
								</tr>
							</table>
						</div>
						<table id='p_porange1' width='1000px' style='border:1px solid #000;'>
							<tr>
								<th width='20px' style='text-align:center; background-color:#ccc;'><div style='padding:5px;'><input type='checkbox' onclick=\"checkAll('p_porange1', this)\" ></div></th>
								<th width='20px' style='background-color:#ccc; text-align:center;'><div style='padding:5px;'><img src='images/icon_book.png' border='0' /></div></th>
								<th width='220px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Name</div></th>
								<th width='250px' style='background:#BCBCBC; color:#333333; text-align:right;'><div style='padding:5px;'>Destination</div></th>
								<th width='130px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>ETA</div></th>
								<th width='110px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Stated Speed</div></th>
								<th width='160px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Private Message</div></th>
							</tr>";
					}
					
					$ships = $r_broker[$i_broker];
					$_SESSION['r_broker'][$i_broker] = $ships;
					
					$imo = $ships['xvas_imo'];
					
					$sql  = "SELECT * FROM `_xvas_shipdata_dry` WHERE `imo`='".$imo."'";
					$xvas = dbQuery($sql);
					$xvas = $xvas[0];
					
					if(trim($xvas['data'])){
						$name = getValue($xvas['data'], 'NAME');
						$imageb = base64_encode("http://dataservice.grosstonnage.com/S-Bisphoto.php?imo=".$ships['xvas_imo']);
						
						$ship_img = "<img src='image.php?b=1&mx=20&p=".$imageb."'>";
						$ship_name = "<a class='clickable' onclick='return showShipDetails(\"".$imo."\")'>".$name."</a>";
						
						$stated_speed = $ships['SPEED'];
						if(trim($stated_speed)){ $stated_speed = number_format($stated_speed, 2); }
						else{ $stated_speed = "13.50"; }
						
						//UPDATES
						$sql = "SELECT * FROM `_messages` WHERE `id`='".$ships['message_id']."' AND type='network' ORDER BY dateadded DESC LIMIT 0,1";
						$bupdate = dbQuery($sql, $link);
						
						$bupdatearr = unserialize($bupdate[0]['message']);
						$delydate_to = strtotime($bupdatearr['delydate_to']);
						
						$bupdatearr = unserialize($bupdate[0]['message']);
						$delydate_to = strtotime($bupdatearr['delydate_to']);
						
						$sql = "SELECT * FROM `_operators_update` WHERE `imo`='".$imo."' AND type='op_update' ORDER BY dateadded DESC LIMIT 0,1";
						$oupdate = dbQuery($sql, $link);
						
						$sql = "SELECT * FROM `_blackbox_vessel` WHERE `vessel_name`='".$name."' ORDER BY latest_created DESC LIMIT 0,1";
						$eupdate = dbQuery($sql, $link);
						
						if(!empty($bupdate) || !empty($oupdate) || !empty($eupdate)){
							if((time()-$delydate_to)<(60*60*24*15) || (time()-strtotime(date('M d, Y', strtotime($eupdate[0]['from_time']))))<(60*60*24*15) || !empty($oupdate)){
								$updates = "<img src='images/icon_dropdown_warning_shore.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('p_drop1_".$i_broker."', '".$imo."', 'shore');\" id='p_drop1_".$i_broker."_img' />";
							}else{
								$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('p_drop1_".$i_broker."', '".$imo."', 'shore');\" id='p_drop1_".$i_broker."_img' />";
							}
						}else{
							$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('p_drop1_".$i_broker."', '".$imo."', 'shore');\" id='p_drop1_".$i_broker."_img' />";
						}
						//END
						
						//MAP DETAILS
						$details = array();
						$details['a'] = 'r_broker';
						$details['id'] = $i_broker;
						$details['portid'] = $portid;
						$details['port_latitude'] = $port_latitude;
						$details['port_longitude'] = $port_longitude;
						$details = base64_encode(serialize($details));
						//END
						
						$bupdate_nmessage = unserialize($bupdate[0]['message']);
						$destination_eta = date("M j, 'y G:i e", strtotime($bupdate_nmessage['delydate_from']));
						$destination = $bupdate_nmessage['dely'];
						
						$private     = getMessageByImo($imo, 'private');
						$mid         = $private['id'];
						$private     = stripslashes($private['message']);
						$private_alt = htmlentities($private);
						$private_msg = word_limit($private, 2);
						
						echo "<tr style='background:#e5e5e5;'>
							<td style='text-align:center;'><div style='padding:5px;'><input class='pcheck' type='checkbox' name='imos[]'  value='a1_".$imo."'></div></td>
							<td style='text-align:center;'><div style='padding:5px;'>".$updates."</div></td>
							<td>
								<div style='padding:5px;'>
									<table cellpadding='0' cellspacing='0' width='100%'>
										<tr>
											<td width='25'>".$ship_img."</td>
											<td>".$ship_name."</td>
										</tr>
									</table>
								</div>
							</td>
							<td>
								<div style='padding:5px;'>
									<table width='100%' cellpadding='0' cellspacing='0'>
										<tr>
											<td style='text-align:right;'>".$destination."</td>
										</tr>
									</table>
								</div>
							</td>
							<td><div style='padding:5px;'>".$destination_eta."</div></td>
							<td class='z_text01'><div style='padding:5px;'>".$stated_speed."</div></td>
							<td class='message' style='padding:0px 3px 0px 3px' alt=\"".$private_alt."\" title=\"".$private_alt."\" id=\"".$mid."\" onclick='openMessageDialog(this.id, \"".$imo."\", \"private\")' >
								<input type='hidden' class='pmessages' value=\"private3_".$imo."\" >
								<div id=\"private3_".$imo."\">".$private_msg."</div>
							</td>
						</tr>
						<tr style='width:992px; background:#fff;'>
							<td colspan='15' id='p_drop1_".$i_broker."' style='display:none;'></td>
						</tr>";
					}
					
					$counter++;
				}
			}
		}
	}
}

echo "</table>
<table cellpadding='0' cellspacing='0' width='1000'>
	<tr>
		<td>&nbsp;</td>
	</tr>
</table>";
?>