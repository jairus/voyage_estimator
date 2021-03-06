<?php
//T3
if($t3){
	echo "<div style='width:990px; text-align:left; padding:5px; background:#ffb83a; color:white; margin-top:5px;'>
		<table cellpadding='0' cellspacing='0' width='990px'>
			<tr>
				<td><b style='font-size:14px;'>SHIPS WITH CONFIRMED OPEN PORT USING BROKERSINTELLIGENCE</b></td>
				<td align='right' style='text-align:right; vertical-align:top'>
					<a class='clickable' onclick=\"csvIt('position')\"><img src='images/csv.jpg'></a>
					<a class='clickable' onclick=\"printIt('position')\"><img src='images/print.jpg'></a>
					<a class='clickable' onclick=\"mailIt('position')\"><img src='images/email_small.jpg'></a>
					<a href='#params'><img style='border:0px' src='images/up_icon.png' alt='back to top' title='back to top'></a>
				</td>
			</tr>
		</table>
	</div>
	<table id='p_poranges1' width='1000px' style='border:1px solid #000;'>
		<tr>
			<th width='20px' style='text-align:center; background-color:#ccc;'><div style='padding:5px;'><input type='checkbox' onclick=\"checkAll('p_poranges1', this)\" ></div></th>
			<th width='20px' style='background-color:#ccc; text-align:center;'><div style='padding:5px;'><img src='images/icon_book.png' border='0' /></div></th>
			<th width='200px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Name</div></th>
			<th width='120px' style='background:#BCBCBC; color:#333333; text-align:right;'><div style='padding:5px;'>Open Port</div></th>
			<th width='110px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Open ETA</div></th>
			<th width='50px' style='background:#BCBCBC; color:#333333; text-align:center;'><div style='padding:5px;'>Nav</div></th>
			<th width='50px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Speed</div></th>
			<th width='50px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>SOG</div></th>
			<th width='60px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>COG</div></th>
			<th width='170px' style='background:#BCBCBC; color:#333333; text-align:right;'><div style='padding:5px;'>AIS Open Port</div></th>
			<th width='100px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>AIS ETA</div></th>
			<th width='20px' style= background-color:#BCBCBC;'><div style='padding:5px;'>HIS</div></th>
			<th width='130px' style='background:#BCBCBC; color:#333333;'><div style='padding:5px;'>Private Message</div></th>
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
							$siitech = $shipsA3[$i];
				
							//UPDATES
							$sql = "SELECT * FROM `_messages` WHERE `imo`='".$ships['IMO #']."' AND type='network' ORDER BY dateadded DESC LIMIT 0,1";
							$bupdate = dbQuery($sql, $link);
							
							$bupdatearr = unserialize($bupdate[0]['message']);
							$delydate_to = strtotime($bupdatearr['delydate_to']);
							
							if(!empty($bupdate)){
								if((time()-$delydate_to)<(60*60*24*15)){
									$updates = "<img src='images/icon_dropdown_warning_broker.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('p_drop3_".$i."', '".$ships['IMO #']."', 'broker');\" id='p_drop3_".$i."_img' />";
								}else{
									$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('p_drop3_".$i."', '".$ships['IMO #']."', 'broker');\" id='p_drop3_".$i."_img' />";
								}
							}else{
								$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('p_drop3_".$i."', '".$ships['IMO #']."', 'broker');\" id='p_drop3_".$i."_img' />";
							}
							//END
							
							//GET NAVIGATION
							$pos = $siitech['siitech_shippos_data'];
							$pos = getXMLtoArr($pos);
							
							if($pos['NavigationalStatus']!=""){
								$nav = "<img title='".navStat($pos['NavigationalStatus'])."' alt='".navStat($pos['NavigationalStatus'])."' src='images/".$pos['NavigationalStatus'].".png' style='height:15px; width: 15px;' />";
							}else{
								$nav = "<img style='height:15px; width:15px;' src='images/alert1.png' alt='No AIS Data Available' title='No AIS Data Available' />";
							}
							
							if($pos['SOG']=="" || $pos['SOG']==0){ $sog = "<img style='height:15px; width:15px;' src='images/alert1.png' alt='N/A' title='N/A' />"; }
							else{ $sog = $pos['SOG']; }
							
							if($pos['COG']==""){ $cog = "<img style='height:15px; width:15px;' src='images/alert1.png' alt='N/A' title='N/A' />"; }
							else{ $cog = $pos['COG']." &deg;"; }
							//END
							
							//GET SHIP DETAILS
							$speed = getValue($xvas['data'], 'SPEED_SERVICE');
							if(trim($speed)){ $speed = number_format($speed, 2); }
							else{ $speed = "13.50"; }
							//END
							
							//SIITECH INFOS
							$destination = $siitech['siitech_destination'];
							if(!trim($destination)){ $destination = 'click'; }
							else{ $destination = $destination; }
							
							$siitech_eta = $siitech['siitech_eta'];
							if($siitech_eta<date('Y-m-d G:i:s')){
								if(!trim($destination)){ $icon1 = '<img style="height:15px; width:15px;" src="images/alert.png" alt="The ship has not updated its AIS location as of '.date("M j, 'y G:i e",  time()).'. The Last Seen AIS Location (Lat & Long) is now used to calculate the ETA to the Load Port you have selected for this Search." title="The ship has not updated its AIS location as of '.date("M j, 'y G:i e",  time()).'. The Last Seen AIS Location (Lat & Long) is now used to calculate the ETA to the Load Port you have selected for this Search."'; }
								else{ $icon1 = '<img style="height:15px; width:15px;" src="images/alert.png" alt="Reported ETA to AIS Destination Port ('.$destination.') that was dated to arrive on '.date("M j, 'y G:i e", str2time($siitech_eta)).' has passed." title="Reported ETA to AIS Destination Port ('.$destination.') that was dated to arrive on '.date("M j, 'y G:i e", str2time($siitech_eta)).' has passed."'; }
							}
							//END
							
							//MAP DETAILS
							$details       = array();
							$details['a']  = 'shipsA3print';
							$details['id'] = $i;
							$details       = base64_encode(serialize($details));
							//END
							
							$private     = getMessageByImo($ships['IMO #'], 'private');
							$mid         = $private['id'];
							$private     = stripslashes($private['message']);
							$private_alt = htmlentities($private);
							$private_msg = word_limit($private, 2);
							
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
								<td style='text-align:center;'><div style='padding:5px;'>".$nav."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".$speed."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".$sog."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".$cog."</div></td>
								<td>
									<div style='padding:5px;'>
										<table width='100%' cellpadding='0' cellspacing='0'>
											<tr>
												<td style='text-align:right;'>".$destination."</td>
												<td style='text-align:center; width:20px;'>".$icon1."</td>
												<td style='text-align:center; width:20px;'><a class='clickable' onclick='openMapVe2(\"broker\", \"".$details."\", \"SHIPS WITH CONFIRMED OPEN PORT USING BROKERSINTELLIGENCE\")'><img title='Map' alt='Map' src='images/map-icon.png'></a></td>
											</tr>
										</table>
									</div>
								</td>";
								
								if(date("M j, 'y", str2time($siitech_eta))!="Jan 1, '70"){
									echo "<td><div style='padding:5px;'><a class='clickable2' alt=\"".date("M j, Y G:i e", str2time($siitech_eta))."\" title=\"".date("M j, Y G:i e", str2time($siitech_eta))."\" >".date("M j, 'y", str2time($siitech_eta))."</a></div></td>";
								}else{
									echo "<td><div style='padding:5px;'><img style='height:15px; width:15px;' src='images/alert1.png' alt='No AIS Data Available' title='No AIS Data Available' /></div></td>";
								}
								
								echo "<td style='text-align:center;'><div style='padding:5px;'><a onclick=\"getHistory('".$ships['IMO #']."', 'openport', '".$details."', 'SHIPS WITH CONFIRMED OPEN PORT USING BROKERSINTELLIGENCE');\" alt='Last Port History' title='Last Port History' class='history_link'><img src='images/icon_plusdown_warning.png' border='0' /></a></div</td>
								<td class='message' style='padding:0px 3px 0px 3px' alt=\"".$private_alt."\" title=\"".$private_alt."\" id=\"".$mid."\" onclick='openMessageDialog(this.id, \"".$ships['IMO #']."\", \"private\")' >
									<input type='hidden' class='pmessages' value=\"private3_".$ships['IMO #']."\" >
									<div id=\"private3_".$ships['IMO #']."\">".$private_msg."</div>
								</td>
							</tr>
							<tr style='width:992px; background:#fff;'>
								<td colspan='15' id='p_drop3_".$i."' style='display:none;'></td>
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
							$siitech = $shipsA3[$i];
				
							//UPDATES
							$sql = "SELECT * FROM `_messages` WHERE `imo`='".$ships['IMO #']."' AND type='network' ORDER BY dateadded DESC LIMIT 0,1";
							$bupdate = dbQuery($sql, $link);
							
							$bupdatearr = unserialize($bupdate[0]['message']);
							$delydate_to = strtotime($bupdatearr['delydate_to']);
							
							if(!empty($bupdate)){
								if((time()-$delydate_to)<(60*60*24*15)){
									$updates = "<img src='images/icon_dropdown_warning_broker.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('p_drop3_".$i."', '".$ships['IMO #']."', 'broker');\" id='p_drop3_".$i."_img' />";
								}else{
									$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('p_drop3_".$i."', '".$ships['IMO #']."', 'broker');\" id='p_drop3_".$i."_img' />";
								}
							}else{
								$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('p_drop3_".$i."', '".$ships['IMO #']."', 'broker');\" id='p_drop3_".$i."_img' />";
							}
							//END
							
							//GET NAVIGATION
							$pos = $siitech['siitech_shippos_data'];
							$pos = getXMLtoArr($pos);
							
							if($pos['NavigationalStatus']!=""){
								$nav = "<img title='".navStat($pos['NavigationalStatus'])."' alt='".navStat($pos['NavigationalStatus'])."' src='images/".$pos['NavigationalStatus'].".png' style='height:15px; width: 15px;' />";
							}else{
								$nav = "<img style='height:15px; width:15px;' src='images/alert1.png' alt='No AIS Data Available' title='No AIS Data Available' />";
							}
							
							if($pos['SOG']=="" || $pos['SOG']==0){ $sog = "<img style='height:15px; width:15px;' src='images/alert1.png' alt='N/A' title='N/A' />"; }
							else{ $sog = $pos['SOG']; }
							
							if($pos['COG']==""){ $cog = "<img style='height:15px; width:15px;' src='images/alert1.png' alt='N/A' title='N/A' />"; }
							else{ $cog = $pos['COG']." &deg;"; }
							//END
							
							//GET SHIP DETAILS
							$speed = getValue($xvas['data'], 'SPEED_SERVICE');
							if(trim($speed)){ $speed = number_format($speed, 2); }
							else{ $speed = "13.50"; }
							//END
							
							//SIITECH INFOS
							$destination = $siitech['siitech_destination'];
							if(!trim($destination)){ $destination = 'click'; }
							else{ $destination = $destination; }
							
							$siitech_eta = $siitech['siitech_eta'];
							if($siitech_eta<date('Y-m-d G:i:s')){
								if(!trim($destination)){ $icon1 = '<img style="height:15px; width:15px;" src="images/alert.png" alt="The ship has not updated its AIS location as of '.date("M j, 'y G:i e",  time()).'. The Last Seen AIS Location (Lat & Long) is now used to calculate the ETA to the Load Port you have selected for this Search." title="The ship has not updated its AIS location as of '.date("M j, 'y G:i e",  time()).'. The Last Seen AIS Location (Lat & Long) is now used to calculate the ETA to the Load Port you have selected for this Search."'; }
								else{ $icon1 = '<img style="height:15px; width:15px;" src="images/alert.png" alt="Reported ETA to AIS Destination Port ('.$destination.') that was dated to arrive on '.date("M j, 'y G:i e", str2time($siitech_eta)).' has passed." title="Reported ETA to AIS Destination Port ('.$destination.') that was dated to arrive on '.date("M j, 'y G:i e", str2time($siitech_eta)).' has passed."'; }
							}
							//END
							
							//MAP DETAILS
							$details       = array();
							$details['a']  = 'shipsA3print';
							$details['id'] = $i;
							$details       = base64_encode(serialize($details));
							//END
							
							$private     = getMessageByImo($ships['IMO #'], 'private');
							$mid         = $private['id'];
							$private     = stripslashes($private['message']);
							$private_alt = htmlentities($private);
							$private_msg = word_limit($private, 2);
							
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
								<td style='text-align:center;'><div style='padding:5px;'>".$nav."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".$speed."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".$sog."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".$cog."</div></td>
								<td>
									<div style='padding:5px;'>
										<table width='100%' cellpadding='0' cellspacing='0'>
											<tr>
												<td style='text-align:right;'>".$destination."</td>
												<td style='text-align:center; width:20px;'>".$icon1."</td>
												<td style='text-align:center; width:20px;'><a class='clickable' onclick='openMapVe2(\"broker\", \"".$details."\", \"SHIPS WITH CONFIRMED OPEN PORT USING BROKERSINTELLIGENCE\")'><img title='Map' alt='Map' src='images/map-icon.png'></a></td>
											</tr>
										</table>
									</div>
								</td>";
								
								if(date("M j, 'y", str2time($siitech_eta))!="Jan 1, '70"){
									echo "<td><div style='padding:5px;'><a class='clickable2' alt=\"".date("M j, Y G:i e", str2time($siitech_eta))."\" title=\"".date("M j, Y G:i e", str2time($siitech_eta))."\" >".date("M j, 'y", str2time($siitech_eta))."</a></div></td>";
								}else{
									echo "<td><div style='padding:5px;'><img style='height:15px; width:15px;' src='images/alert1.png' alt='No AIS Data Available' title='No AIS Data Available' /></div></td>";
								}
								
								echo "<td style='text-align:center;'><div style='padding:5px;'><a onclick=\"getHistory('".$ships['IMO #']."', 'openport', '".$details."', 'SHIPS WITH CONFIRMED OPEN PORT USING BROKERSINTELLIGENCE');\" alt='Last Port History' title='Last Port History' class='history_link'><img src='images/icon_plusdown_warning.png' border='0' /></a></div</td>
								<td class='message' style='padding:0px 3px 0px 3px' alt=\"".$private_alt."\" title=\"".$private_alt."\" id=\"".$mid."\" onclick='openMessageDialog(this.id, \"".$ships['IMO #']."\", \"private\")' >
									<input type='hidden' class='pmessages' value=\"private3_".$ships['IMO #']."\" >
									<div id=\"private3_".$ships['IMO #']."\">".$private_msg."</div>
								</td>
							</tr>
							<tr style='width:992px; background:#fff;'>
								<td colspan='15' id='p_drop3_".$i."' style='display:none;'></td>
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
							$siitech = $shipsA3[$i];
				
							//UPDATES
							$sql = "SELECT * FROM `_messages` WHERE `imo`='".$ships['IMO #']."' AND type='network' ORDER BY dateadded DESC LIMIT 0,1";
							$bupdate = dbQuery($sql, $link);
							
							$bupdatearr = unserialize($bupdate[0]['message']);
							$opendate = strtotime($bupdatearr['opendate']);
							
							if(!empty($bupdate)){
								if((time()-$opendate)<(60*60*24*15)){
									$updates = "<img src='images/icon_dropdown_warning_broker.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('p_drop3_".$i."', '".$ships['IMO #']."', 'broker');\" id='p_drop3_".$i."_img' />";
								}else{
									$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('p_drop3_".$i."', '".$ships['IMO #']."', 'broker');\" id='p_drop3_".$i."_img' />";
								}
							}else{
								$updates = "<img src='images/icon_dropdown.png' width='20' height='18' style='cursor:pointer;' onclick=\"expand('p_drop3_".$i."', '".$ships['IMO #']."', 'broker');\" id='p_drop3_".$i."_img' />";
							}
							//END
							
							//GET NAVIGATION
							$pos = $siitech['siitech_shippos_data'];
							$pos = getXMLtoArr($pos);
							
							if($pos['NavigationalStatus']!=""){
								$nav = "<img title='".navStat($pos['NavigationalStatus'])."' alt='".navStat($pos['NavigationalStatus'])."' src='images/".$pos['NavigationalStatus'].".png' style='height:15px; width: 15px;' />";
							}else{
								$nav = "<img style='height:15px; width:15px;' src='images/alert1.png' alt='No AIS Data Available' title='No AIS Data Available' />";
							}
							
							if($pos['SOG']=="" || $pos['SOG']==0){ $sog = "<img style='height:15px; width:15px;' src='images/alert1.png' alt='N/A' title='N/A' />"; }
							else{ $sog = $pos['SOG']; }
							
							if($pos['COG']==""){ $cog = "<img style='height:15px; width:15px;' src='images/alert1.png' alt='N/A' title='N/A' />"; }
							else{ $cog = $pos['COG']." &deg;"; }
							//END
							
							//GET SHIP DETAILS
							$speed = getValue($xvas['data'], 'SPEED_SERVICE');
							if(trim($speed)){ $speed = number_format($speed, 2); }
							else{ $speed = "13.50"; }
							//END
							
							//SIITECH INFOS
							$destination = $siitech['siitech_destination'];
							if(!trim($destination)){ $destination = 'click'; }
							else{ $destination = $destination; }
							
							$siitech_eta = $siitech['siitech_eta'];
							if($siitech_eta<date('Y-m-d G:i:s')){
								if(!trim($destination)){ $icon1 = '<img style="height:15px; width:15px;" src="images/alert.png" alt="The ship has not updated its AIS location as of '.date("M j, 'y G:i e",  time()).'. The Last Seen AIS Location (Lat & Long) is now used to calculate the ETA to the Load Port you have selected for this Search." title="The ship has not updated its AIS location as of '.date("M j, 'y G:i e",  time()).'. The Last Seen AIS Location (Lat & Long) is now used to calculate the ETA to the Load Port you have selected for this Search."'; }
								else{ $icon1 = '<img style="height:15px; width:15px;" src="images/alert.png" alt="Reported ETA to AIS Destination Port ('.$destination.') that was dated to arrive on '.date("M j, 'y G:i e", str2time($siitech_eta)).' has passed." title="Reported ETA to AIS Destination Port ('.$destination.') that was dated to arrive on '.date("M j, 'y G:i e", str2time($siitech_eta)).' has passed."'; }
							}
							//END
							
							//MAP DETAILS
							$details       = array();
							$details['a']  = 'shipsA3print';
							$details['id'] = $i;
							$details       = base64_encode(serialize($details));
							//END
							
							$private     = getMessageByImo($ships['IMO #'], 'private');
							$mid         = $private['id'];
							$private     = stripslashes($private['message']);
							$private_alt = htmlentities($private);
							$private_msg = word_limit($private, 2);
							
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
								<td style='text-align:center;'><div style='padding:5px;'>".$nav."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".$speed."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".$sog."</div></td>
								<td class='z_text01'><div style='padding:5px;'>".$cog."</div></td>
								<td>
									<div style='padding:5px;'>
										<table width='100%' cellpadding='0' cellspacing='0'>
											<tr>
												<td style='text-align:right;'>".$destination."</td>
												<td style='text-align:center; width:20px;'>".$icon1."</td>
												<td style='text-align:center; width:20px;'><a class='clickable' onclick='openMapVe2(\"broker\", \"".$details."\", \"SHIPS WITH CONFIRMED OPEN PORT USING BROKERSINTELLIGENCE\")'><img title='Map' alt='Map' src='images/map-icon.png'></a></td>
											</tr>
										</table>
									</div>
								</td>";
								
								if(date("M j, 'y", str2time($siitech_eta))!="Jan 1, '70"){
									echo "<td><div style='padding:5px;'><a class='clickable2' alt=\"".date("M j, Y G:i e", str2time($siitech_eta))."\" title=\"".date("M j, Y G:i e", str2time($siitech_eta))."\" >".date("M j, 'y", str2time($siitech_eta))."</a></div></td>";
								}else{
									echo "<td><div style='padding:5px;'><img style='height:15px; width:15px;' src='images/alert1.png' alt='No AIS Data Available' title='No AIS Data Available' /></div></td>";
								}
								
								echo "<td style='text-align:center;'><div style='padding:5px;'><a onclick=\"getHistory('".$ships['IMO #']."', 'openport', '".$details."', 'SHIPS WITH CONFIRMED OPEN PORT USING BROKERSINTELLIGENCE');\" alt='Last Port History' title='Last Port History' class='history_link'><img src='images/icon_plusdown_warning.png' border='0' /></a></div</td>
								<td class='message' style='padding:0px 3px 0px 3px' alt=\"".$private_alt."\" title=\"".$private_alt."\" id=\"".$mid."\" onclick='openMessageDialog(this.id, \"".$ships['IMO #']."\", \"private\")' >
									<input type='hidden' class='pmessages' value=\"private3_".$ships['IMO #']."\" >
									<div id=\"private3_".$ships['IMO #']."\">".$private_msg."</div>
								</td>
							</tr>
							<tr style='width:992px; background:#fff;'>
								<td colspan='15' id='p_drop3_".$i."' style='display:none;'></td>
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
?>