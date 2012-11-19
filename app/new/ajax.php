<?php
@include_once(dirname(__FILE__)."/includes/bootstrap.php");

function floorTs($ts){
	$date = date("Y-m-d 00:00:00",$ts);
	$ts = strtotime($date);

	return $ts;
}

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
	}else if(strtolower($type)=='remark'||strtolower($type)=='openport'||strtolower($type)=='opendate'||strtolower($type)=='network'||strtolower($type)=='user_email'){
		$userid = $_SESSION['user']['id'];		

		$sql = "
		select * from `_messages` where `imo`='".$imo."' and `type`='network' and 
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

	if($hasnum ){ }

	return $r[0];
}

function word_limit($str, $limit){
	$str .= "";
	$str = trim($str);
	$l = strlen($str);
	$s = "";

	for($i=0; $i<$l; $i++){
		$s .= $str[$i];

		if($limit>0&&(preg_match("/\s/", $str[$i]))){  
			if(!preg_match("/\s/", $str[$i+1]))
				$limit--;

			if(!$limit){
				return $s."...";

				break;
			}
		}
	}

	return $s;
}

function cleanXML($data){
	$str = $data;
	$r = "/(<[^\/]{1}[^>]+)\/([^>]+>)/iUs";

	$matches = array();

	preg_match_all($r, $str, $matches);

	$matches = $matches[0];

	$t = count($matches);

	for($i=0; $i<$t; $i++){
		$replacement = str_replace("/", "_", $matches[$i]);
		$str = str_replace($matches[$i], $replacement, $str);
	}

	$r = "/(<\/[^>]+)\/([^>]+>)/iUs";

	$matches = array();

	preg_match_all($r, $str, $matches);

	$matches = $matches[0];

	$t = count($matches);

	for($i=0; $i<$t; $i++){
		$replacement = str_replace("</", "-=jairus=-", $matches[$i]);
		$replacement = str_replace("/", "_", $replacement);
		$replacement = str_replace("-=jairus=-", "</", $replacement);

		$str = str_replace($matches[$i], $replacement, $str);
	}

	return $str;
}

function fetchxvas($imo){
	$vars = array("imo"=>$imo,"mode"=>"ALL");
	$snoopy = new Snoopy();
	
	$snoopy->httpmethod = "GET";
	$snoopy->submit("http://dataservice.grosstonnage.com/S-Bis.php", $vars);

	$contents = $snoopy->results;
	
	return $contents;
}

function updateShipData(&$ship){
	$ts = strtotime($ship['dateupdated']);
	$imo = $ship['imo'];

	if((time()-$ts)>(60*60*24*7)){
		$data = fetchxvas($imo);
		
		if(trim($data)){
			$callsign      = getValue($data, 'CALL_SIGN');
			$mmsi          = getValue($data, 'MMSI_CODE');
			$name          = getValue($data, 'NAME');
			$hull_type     = getValue($data, 'HULL_TYPE');
			$vessel_type   = getValue($data, 'VESSEL_TYPE');
			$owner         = getValue($data, 'OWNER');
			$builder       = getValue($data, 'BUILDER');
			$manager_owner = getValue($data, 'MANAGER_OWNER');
			$manager       = getValue($data, 'MANAGER');
			$summer_dwt    = getValue($data, 'SUMMER_DWT');
			$speed         = getValue($data, 'SPEED_SERVICE');
			
			$sql = "update `_xvas_parsed2_dry`
					set `callsign` = '".$callsign."',
						`mmsi` = '".$mmsi."',
						`name` = '".$name."',
						`hull_type` = '".$hull_type."',
						`vessel_type` = '".$vessel_type."',
						`owner` = '".$owner."',
						`builder` = '".$builder."',
						`manager_owner` = '".$manager_owner."',
						`manager` = '".$manager."',
						`summer_dwt` = '".$summer_dwt."',
						`speed` = '".$speed."',
						`dateupdated` = now()
					where `imo` = '".mysql_escape_string($imo)."'";
			dbQuery($sql, $link);
				
			$sql = "update `_xvas_shipdata_dry` set `dateupdated` = now(), `data`='".mysql_escape_string($data)."' where `imo` = '".mysql_escape_string($imo)."'";
			dbQuery($sql, $link);
			$ship['data'] = $data;
	
			echo "<table width='100%'><tr><td style='font-size:11px; text-align:right;'>Last Update of Data: ".date("F j, Y h:i:s",time())."</td></tr></table>";
		}else{
			echo "<table width='100%'><tr><td style='font-size:11px; text-align:right;'>Last Update of Data: ".date("F j, Y h:i:s",$ts)."</td></tr></table>";
		}
	}else{
		echo "<table width='100%'><tr><td style='font-size:11px; text-align:right;'>Last Update of Data: ".date("F j, Y h:i:s",$ts)."</td></tr></table>";
	}
}

function printVal($value){
	if(is_array($value)){
		$t = count($value);

		for($i=0; $i<$t; $i++){
			if(is_array($value[$i])){
				printVal($value[$i]);
			}else{
				$vars = @get_object_vars($value[$i]);

				if(is_array($vars)){
					foreach($vars as $k=>$v){
						if(is_array($v)){
							echo "<tr><td class='toplabel' colspan=2>".$k.":</td></tr>";

							printVal($v);
						}else{
							$vs = @get_object_vars($v);

							if($vs>0){
								$v = "";
							}

							if(strpos(strtolower($k), "email")!==false){
								$v = "<a href='mailto:".$v."'>".$v."</a>";
							}

							if(strpos(strtolower($k), "website")!==false){
								if(strpos($v, "http://")!==false) $v = "<a target='_blank' href='".$v."'>".$v."</a>";

								else $v = "<a target='_blank' href='http://".$v."'>".$v."</a>";
							}
							
							if($k=="FLAG"){
								$flag = $v;
								$img = getFlagImage($flag);

								$k = str_replace("_", "&nbsp;", $k);							

								if(strpos(trim($img),".png")>0){
									echo "<tr><td class='leftlabel'>".$k.":</td><td class='rightvalue'><img alt=\"".htmlentities($flag)."\" title=\"".htmlentities($flag)."\" src='".$img."' >&nbsp;$v</td></tr>";
								}else{
									echo "<tr><td class='leftlabel'>".$k.":</td><td class='rightvalue'>$v</td></tr>";
								}
							}else if($k=="MANAGER_OWNER"){
								$v = utf8_decode($v);
								$k = str_replace("_", "&nbsp;", $k);

								if($_GET['contact']||$_GET['shipdetails']){
									echo "<tr><td class='leftlabel'>".$k.":</td><td class='rightvalue'><a href='?owner=".urlencode($v)."&owner_id=".$vars['MANAGER_OWNER_ID']."' >".$v."</a></td></tr>";
								}else{
									echo "<tr><td class='leftlabel'>".$k.":</td><td class='rightvalue'><a href='javascript:ownerDetails(\"".urlencode($v)."\", \"".$vars['MANAGER_OWNER_ID']."\")' >".$v."</a></td></tr>";
								}
							}else if($k=="MANAGER"){
								$v = utf8_decode($v);
								$k = str_replace("_", "&nbsp;", $k);

								if($_GET['contact']||$_GET['shipdetails']){
									echo "<tr><td class='leftlabel'>".$k.":</td><td class='rightvalue'><a href='?owner=".urlencode($v)."&owner_id=".$vars['MANAGER_ID']."' >".$v."</a></td></tr>";
								}else{
									echo "<tr><td class='leftlabel'>".$k.":</td><td class='rightvalue'><a href='javascript:ownerDetails(\"".urlencode($v)."\", \"".$vars['MANAGER_ID']."\")' >".$v."</a></td></tr>";
								}
							}else if($k=="OWNER"){
								$v = utf8_decode($v);
								$k = str_replace("_", "&nbsp;", $k);

								if($_GET['contact']||$_GET['shipdetails']){
									echo "<tr><td class='leftlabel'>".$k.":</td><td class='rightvalue'><a href='?owner=".urlencode($v)."&owner_id=".$vars['OWNER_ID']."' >".$v."</a></td></tr>";
								}else{
									echo "<tr><td class='leftlabel'>".$k.":</td><td class='rightvalue'><a href='javascript:ownerDetails(\"".urlencode($v)."\", \"".$vars['OWNER_ID']."\")' >".$v."</a></td></tr>";
								}
							}else if($k=="BUILDER"){
								$v = utf8_decode($v);
								$k = str_replace("_", "&nbsp;", $k);

								if($_GET['contact']||$_GET['shipdetails']){
									echo "<tr><td class='leftlabel'>".$k.":</td><td class='rightvalue'><a href='?owner=".urlencode($v)."&owner_id=0' >".$v."</a></td></tr>";
								}else{
									echo "<tr><td class='leftlabel'>".$k.":</td><td class='rightvalue'><a href='javascript:ownerDetails(\"".urlencode($v)."\", \"0\")' >".$v."</a></td></tr>";
								}
							}else{				
								$v = utf8_decode($v);
								$k = str_replace("_", "&nbsp;", $k);

								echo "<tr><td class='leftlabel'>".$k.":</td><td class='rightvalue'>".$v."</td></tr>";
							}
						}
					}
				}else{
					echo "<tr><td class='bottomvalue' colspan=2>".$value[$i]."</td></tr>";
				}
			}
		}
	}else{
		$vars = @get_object_vars($value);
		
		foreach($vars as $k=>$v){
			if(is_array($v)){
				echo "<tr><td class='toplabel' colspan=2>".$k.":</td></tr>";

				printVal($v);
			}else{
				$vs = @get_object_vars($v);

				if($vs>0){
					$v = "";
				}

				if(strpos(strtolower($k), "email")!==false){
					$v = "<a href='mailto:".$v."'>".$v."</a>";
				}

				if(strpos(strtolower($k), "website")!==false){
					if(strpos($v, "http://")!==false) $v = "<a target='_blank' href='".$v."'>".$v."</a>";

					else $v = "<a target='_blank' href='http://".$v."'>".$v."</a>";
				}

				if($k=="FLAG"){
					$flag = $v;
					$img = getFlagImage($flag);	
					$k = str_replace("_", "&nbsp;", $k);				

					if(strpos(trim($img),".png")>0){
						echo "<tr><td class='leftlabel'>".$k.":</td><td class='rightvalue'><img alt=\"".htmlentities($flag)."\" title=\"".htmlentities($flag)."\" src='".$img."' >&nbsp;$v</td></tr>";
					}else{
						echo "<tr><td class='leftlabel'>".$k.":</td><td class='rightvalue'>$v</td></tr>";
					}
				}else if($k=="MANAGER_OWNER"){
					$v = utf8_decode($v);
					$k = str_replace("_", "&nbsp;", $k);

					if($_GET['contact']||$_GET['shipdetails']){
						echo "<tr><td class='leftlabel'>".$k.":</td><td class='rightvalue'><a href='?owner=".urlencode($v)."&owner_id=".$vars['MANAGER_OWNER_ID']."' >".$v."</a></td></tr>";
					}else{
						echo "<tr><td class='leftlabel'>".$k.":</td><td class='rightvalue'><a href='javascript:ownerDetails(\"".urlencode($v)."\", \"".$vars['MANAGER_OWNER_ID']."\")' >".$v."</a></td></tr>";
					}
				}else if($k=="MANAGER"){
					$v = utf8_decode($v);
					$k = str_replace("_", "&nbsp;", $k);

					if($_GET['contact']||$_GET['shipdetails']){
						echo "<tr><td class='leftlabel'>".$k.":</td><td class='rightvalue'><a href='?owner=".urlencode($v)."&owner_id=".$vars['MANAGER_ID']."' >".$v."</a></td></tr>";
					}else{
						echo "<tr><td class='leftlabel'>".$k.":</td><td class='rightvalue'><a href='javascript:ownerDetails(\"".urlencode($v)."\", \"".$vars['MANAGER_ID']."\")' >".$v."</a></td></tr>";
					}
				}else if($k=="OWNER"){
					$v = utf8_decode($v);
					$k = str_replace("_", "&nbsp;", $k);

					if($_GET['contact']||$_GET['shipdetails']){
						echo "<tr><td class='leftlabel'>".$k.":</td><td class='rightvalue'><a href='?owner=".urlencode($v)."&owner_id=".$vars['OWNER_ID']."' >".$v."</a></td></tr>";
					}else{
						echo "<tr><td class='leftlabel'>".$k.":</td><td class='rightvalue'><a href='javascript:ownerDetails(\"".urlencode($v)."\", \"".$vars['OWNER_ID']."\")' >".$v."</a></td></tr>";
					}
				}else if($k=="BUILDER"){
					$v = utf8_decode($v);
					$k = str_replace("_", "&nbsp;", $k);

					if($_GET['contact']||$_GET['shipdetails']){
						echo "<tr><td class='leftlabel'>".$k.":</td><td class='rightvalue'><a href='?owner=".urlencode($v)."&owner_id=0' >".$v."</a></td></tr>";
					}else{
						echo "<tr><td class='leftlabel'>".$k.":</td><td class='rightvalue'><a href='javascript:ownerDetails(\"".urlencode($v)."\", \"0\")' >".$v."</a></td></tr>";
					}
				}else{				
					$v = utf8_decode($v);
					$k = str_replace("_", "&nbsp;", $k);

					echo "<tr><td class='leftlabel'>".$k.":</td><td class='rightvalue'>".$v."</td></tr>";
				}
			}
		}
	}
}

function printVal2($value){
	if(!is_array($value)){
		$vtemp = array();

		$vtemp[0] = $value;

		$value = $vtemp;
	}

	$t = count($value);

	if($t){
		$extra_array = array();;

		$longest = 0;
		$index = 0;

		for($i=0; $i<$t; $i++){
			$c = 0;

			if(is_array($value[$i])){
				foreach($value[$i] as $v){
					$c++;
				}

				if($c>$longest){
					$longest = $c;
					$index = $i;
				}
			}
		}

		echo "<table>";
		echo "<tr>";

		foreach($value[$index] as $k=>$v){
			if(!is_array($v)){
				$k = str_replace("_", " ", $k);
				
				echo "<td class='leftlabel' style='padding:3px 5px 3px 5px' >";
				echo $k;
				echo "</td>";
			}
		}	

		echo "</tr>";			

		for($i=0; $i<$t; $i++){
			echo "<tr>";

			foreach($value[$index] as $k=>$v){
				if(!is_array($value[$i]->$k)){
					echo "<td style='padding:3px 6px 3px 7px'>";

					if(is_scalar($value[$i]->$k)){
						echo $value[$i]->$k;
					}else{
						echo "";
					}

					echo "</td>";
				}else{
					$extra_array[$k] = $value[$i]->$k;
				}
			}

			echo "</tr>";
		}

		echo "</table>";

		if(count($extra_array)){
			echo "<table>";

			foreach($extra_array as $k=>$v){
				echo "<tr><td class='leftlabel' style='padding:3px 5px 3px 5px'>".$k."</td><tr>";

				$t = count($v);

				echo "<tr><td style='padding:3px 6px 3px 7px'>"; 

				for($i=0; $i<$t; $i++){
					echo $v[$i]."<br>"; 
				}

				echo "</td><tr>";
			}

			echo "<table>";
		}

		echo "<br>";
	}
}

if($_GET['voyage_estimator']){
	if($_GET['imo']){
		$sql = "select * from `_xvas_shipdata_dry` where `imo`='".mysql_escape_string($_GET['imo'])."'";
		$ship = dbQuery($sql, $link);
		$ship = $ship[0];
		
		updateShipData($ship);
	
		$data = trim($ship['data']);
		$data = str_replace("_#", "_NUM", $data);
		$data = str_replace("LENGTH_B/W_PERPENDICULARS", "LENGTH_B_W_PERPENDICULARS", $data);
		$data = str_replace("GRAIN/LIQUID_CAPACITY", "GRAIN_LIQUID_CAPACITY", $data);
		$data = str_replace("LIQUID/OIL", "LIQUID_OIL", $data);
	
		$data = cleanXML($data);
	
		$datan = str_replace("<", "&lt;", $ship['data']);
		$datan = str_replace(">", "&gt;", $datan);
	
		$rdata = parseXML($data);
	
		$str = serialize($rdata);
		$str = str_replace('O:16:"SimpleXMLElement', 'O:8:"stdClass', $str);
	
		$rdata = unserialize($str);	
	
	
		$keys = array();
		?>
	
		<style>
		#dets td{
			font-family:Verdana, Arial, Helvetica, sans-serif;
			font-size:11px;
		}
	
		#dets .dhead{
			font-weight:bold;
			background:#999999;
			color:white;
			margin-bottom:5px;
			padding:5px;
			font-family:Verdana, Arial, Helvetica, sans-serif;
			font-size:11px;
		}
	
		#dets .dval{
			padding:10px 10px 20px 10px;
		}	
	
		#dets .leftlabel, #dets .toplabel{
			font-weight:bold;
		}
	
		#dets .rightvalue, #dets .bottomvalue{
			padding-left:15px;
		}
	
		a:link, a:hover, a:visited{
			color:#3997D9;
		}
		</style>
	
		<?php
		$image = "http://dataservice.grosstonnage.com/S-Bisphoto.php?imo=".$_GET['imo'];
	
		$imageb = base64_encode($image);
	
		echo "<div style='text-align:center;' ><img src='image.php?b=1&mx=500&p=".$imageb."'></div><br>";
	
		foreach($rdata as $key=>$value){
			if($_GET['contact']){
				if($key=='MAIN_DATA'){
					$key = str_replace("_", "&nbsp;", $key);
	
					echo "<div id='dets'><div class='dhead'>".$key."</div>";
					echo "<div class='dval'><table>";
	
					printVal($value);
	
					echo "</table></div></div>";
	
					break;
				}
			}
	
			if($key=='PSC'||$key=='CERTIFICATE'||$key=='INSPECTION'){
				$key = str_replace("_", "&nbsp;", $key);
	
				echo "<div id='dets'><div class='dhead'>".$key."</div>";
				echo "<div class='dval'>";
	
				printVal2($value);
	
				echo "</div></div>";
			}else if($key=='OUTPUT_TIME'){
				echo "";
			}else{
				$key = str_replace("_", "&nbsp;", $key);
	
				echo "<div id='dets'><div class='dhead'>".$key."</div>";
				echo "<div class='dval'><table>";
	
				printVal($value);
	
				if(trim($key)=='FORMER&nbsp;FLAGS'){
					echo "<pre>";
				}			
	
				echo "</table></div></div>";
			}
		}
	
		exit();
	}
}

if($_GET['ship_search_register']){
	?>
    
    <script>
	function openMapRegister(details){
		jQuery("#mapiframe")[0].src='map/register_map.php?details='+details+"&t="+(new Date()).getTime();
		jQuery("#mapdialog").dialog("open");
	}
	
	function oUpdateShipSearch0(id){
		jQuery("#oUpdateShipSearch0"+id).attr("width", "100%");
		jQuery("#oUpdateShipSearch0"+id).toggle();
	}
	
	function oUpdateShipSearch1(id){
		jQuery("#oUpdateShipSearch1"+id).attr("width", "100%");
		jQuery("#oUpdateShipSearch1"+id).toggle();
	}
	
	function oUpdateShipSearch2(id){
		jQuery("#oUpdateShipSearch2"+id).attr("width", "100%");
		jQuery("#oUpdateShipSearch2"+id).toggle();
	}
	</script>
    
    <?php
	$ship = trim($_GET['ship']);
	$operator = trim($_GET['operator']);
	
	if(!$ship&&!$operator){
		echo "Invalid Search Parameters";
	
		exit();
	}
	
	if(!$ship && $operator){
		$operator = "%".mysql_escape_string($operator)."%";

		$sql = "SELECT imo, name FROM `_xvas_parsed2_dry` WHERE owner LIKE '".$operator."' AND owner!='' ORDER BY name";
		$ships_owner = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_dry` WHERE manager_owner LIKE '".$operator."' AND manager_owner!='' ORDER BY name";
		$ships_manager_owner = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_dry` WHERE manager LIKE '".$operator."' AND manager!='' ORDER BY name";
		$ships_manager = dbQuery($sql, $link);
		
		$ships = array_merge($ships_owner, $ships_manager_owner, $ships_manager);
		$ships = array_values($ships);
		
		$t = count($ships);
	}else if($ship && !$operator){
		$ship = "%".mysql_escape_string($ship)."%";

		$sql = "SELECT imo, name FROM `_xvas_parsed2_dry` WHERE name LIKE '".$ship."' AND name!='' ORDER BY name";
		$ships_name = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_dry` WHERE imo LIKE '".$ship."' AND imo!='' ORDER BY name";
		$ships_imo = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_dry` WHERE mmsi LIKE '".$ship."' AND mmsi!='' ORDER BY name";
		$ships_mmsi = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_dry` WHERE callsign LIKE '".$ship."' AND callsign!='' ORDER BY name";
		$ships_callsign = dbQuery($sql, $link);
		
		$ships = array_merge($ships_name, $ships_imo, $ships_mmsi, $ships_callsign);
		$ships = array_values($ships);
		
		$t = count($ships);
	}else{
		$ship     = "%".mysql_escape_string($ship)."%";
		$operator = "%".mysql_escape_string($operator)."%";

		$sql = "SELECT imo, name FROM `_xvas_parsed2_dry` WHERE name LIKE '".$ship."' AND name!='' ORDER BY name";
		$ships_name = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_dry` WHERE imo LIKE '".$ship."' AND imo!='' ORDER BY name";
		$ships_imo = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_dry` WHERE mmsi LIKE '".$ship."' AND mmsi!='' ORDER BY name";
		$ships_mmsi = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_dry` WHERE callsign LIKE '".$ship."' AND callsign!='' ORDER BY name";
		$ships_callsign = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_dry` WHERE owner LIKE '".$operator."' AND owner!='' ORDER BY name";
		$ships_owner = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_dry` WHERE manager_owner LIKE '".$operator."' AND manager_owner!='' ORDER BY name";
		$ships_manager_owner = dbQuery($sql, $link);
		
		$sql = "SELECT imo, name FROM `_xvas_parsed2_dry` WHERE manager LIKE '".$operator."' AND manager!='' ORDER BY name";
		$ships_manager = dbQuery($sql, $link);
		
		$ships = array_merge($ships_name, $ships_imo, $ships_mmsi, $ships_callsign, $ships_owner, $ships_manager_owner, $ships_manager);
		$ships = array_values($ships);
		
		$t = count($ships);
	}
	
	$t = count($ships);
	
	echo "<table id='pblues' width='1300'>
		<tr>
			<th style='background:#BCBCBC; color:#333333; text-align:left; width:250px;'><div style='padding:5px;'>SHIP NAME</div></th>
			<th style='background:#BCBCBC; color:#333333; text-align:left; width:250px;'><div style='padding:5px;'>OPEN PORT</div></th>
			<th style='background:#BCBCBC; color:#333333; text-align:right; width:200px;'><div style='padding:5px;'>LAST SEEN</div></th>
			<th style='background:#BCBCBC; color:#333333; text-align:left; width:200px;'><div style='padding:5px;'>DATE (AIS)</div></th>
			<th style='background:#BCBCBC; color:#333333; text-align:center; width:400px;'><div style='padding:5px;'>BROKER UPDATE</div></th>
		</tr>";
	
	if(trim($t)){
		$shipsA1print = array();
		
		for($i=0; $i<$t; $i++){
			if($ships[$i]['imo']!=$ships[$i-1]['imo']){
				//CHECK IF SHIP EXIST IN DATABASE
				$sql = "SELECT * FROM `_xvas_shipdata_dry` WHERE imo='".$ships[$i]['imo']."'";
				$ship_exist = dbQuery($sql, $link);
				$ship_exist = $ship_exist[0];
				
				if(trim($ship_exist['data'])){
					$status = getValue($ship_exist['data'], 'STATUS');
					
					if(trim($status)!="DEAD"){
						//CHECK IF SHIP EXIST IN SIITECH CACHE
						$sql = "SELECT * FROM `_xvas_siitech_cache` WHERE xvas_imo='".$ships[$i]['imo']."' AND satellite='0' ORDER BY dateupdated DESC";
						$siitech_ships = dbQuery($sql, $link);
						
						$t1 = count($siitech_ships);
						//END
						
						if(trim($t1)){
							$sat_arr = array();
							for($i1=0; $i1<$t1; $i1++){
								$sat_arr[$i1] = $siitech_ships[$i1]['satellite'];
							}
							
							for($i1=0; $i1<$t1; $i1++){
								if($sat_arr[$i1-1]!=$sat_arr[$i1]){
									//MAP DETAILS
									$print = array();
									
									$print['id']        = $siitech_ships[$i1]['id'];
									$print['Ship Name'] = $siitech_ships[$i1]['xvas_name'];
									$print['IMO #']     = $siitech_ships[$i1]['xvas_imo'];
									
									$imageb          = base64_encode("http://dataservice.grosstonnage.com/S-Bisphoto.php?imo=".$print['IMO #']);
									$print['imageb'] = $imageb;
									
									$print['LAT']           = $siitech_ships[$i1]['siitech_latitude'];
									$print['LONG']          = $siitech_ships[$i1]['siitech_longitude'];
									$print['MMSI']          = $siitech_ships[$i1]['xvas_mmsi'];
									$print['VESSEL TYPE']   = $siitech_ships[$i1]['xvas_vessel_type'];
									$print['DWT']           = $siitech_ships[$i1]['xvas_summer_dwt'];
									$print['SPEED']         = $siitech_ships[$i1]['xvas_speed'];
									$print['satellite']     = $siitech_ships[$i1]['satellite'];
									$print['SIITECH_ETA']   = $siitech_ships[$i1]['siitech_eta'];
									$print['DESTINATION']   = $siitech_ships[$i1]['siitech_destination'];
									$print['LASTSEEN_DATE'] = $siitech_ships[$i1]['siitech_lastseen'];
									
									$print['SOG'] = getValue(strtolower($siitech_ships[$i1]['siitech_shippos_data']), "SOG");
									$print['TRUE HEADING'] = getValue(strtolower($siitech_ships[$i1]['siitech_shippos_data']), "trueheading");
								
									if(trim($print['TRUE HEADING'])){
										$print['TRUE HEADING'] .= " degrees";
									}
									
									$print['COG'] = getValue(strtolower($siitech_ships[$i1]['siitech_shippos_data']), "COG");
									$print['B2B'] = getValue(strtolower($siitech_ships[$i1]['siitech_shipstat_data']), "to_bow");
									$print['STERN'] = getValue(strtolower($siitech_ships[$i1]['siitech_shipstat_data']), "to_stern");
									$print['P2P'] = getValue(strtolower($siitech_ships[$i1]['siitech_shipstat_data']), "to_port");
									$print['STARBOARD'] = getValue(strtolower($siitech_ships[$i1]['siitech_shipstat_data']), "to_starboard");
									$print['RADIO'] = getValue(strtolower($siitech_ships[$i1]['siitech_shippos_data']), "radio");
									$print['MANEUVER'] = getValue(strtolower($siitech_ships[$i1]['siitech_shippos_data']), "maneuver");
									$print['NAVSTAT'] = getValue(strtolower($siitech_ships[$i1]['siitech_shippos_data']), "NavigationalStatus");
									$print['ETA'] = getValue(strtolower($siitech_ships[$i1]['siitech_shipstat_data']), "ETA");
									$print['SHIP_TYPE'] = getValue(strtolower($siitech_ships[$i1]['siitech_shipstat_data']), "ShipType");
									$print['UTC'] = getValue(strtolower($siitech_ships[$i1]['siitech_shippos_data']), "UTC");
									
									$shipsA1print[] = $print;
									//END
								}
							}
						}else{
							echo "<tr style='background:#e5e5e5;'>
								<td>
									<div style='padding:5px;'>
										<table cellpadding='0' cellspacing='0' width='100%'>
											<tr>
												<td width='25'><img src='image.php?b=1&mx=20&p=".$imageb."'></td>
												<td><a class='clickable' onclick='return showShipDetails(\"".$ships[$i]['imo']."\")' >".$ships[$i]['name']."</a></td>
												<td width='25' style=' text-align:right;'><a class='clickable' title='Contact' alt='Contact' onclick='contactOwner(\"".$ships[$i]['imo']."\")'><img src='images/contact_icon.png'></a></td>
											</tr>
										</table>
									</div>
								</td>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td style='text-align:right;'><div style='padding:5px;'>&nbsp;</div></td>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td>
									<div style='padding:5px;'>
										<table cellpadding='0' cellspacing='0' width='100%'>
											<tr>
												<td width='50%'><b>Delivery:</b> <input type='button' style='width:125px; cursor:pointer; background:#f0f0f0; border:1px solid #ccc; font-size:10px; padding:2px;' value='".$dely."' alt='".$dely."' title='".$dely."' id='brokersupdate_".$nmid."' onclick='openMessageDialog(this.id, \"".$ships[$i]['imo']."\", \"network\")' /></td>
												<td width='50%'><b>Dely Date:</b> <input type='button' style='width:125px; cursor:pointer; background:#f0f0f0; border:1px solid #ccc; font-size:10px; padding:2px;' value='".$delydate_from."' alt='".$delydate_from."' title='".$delydate_from."' id='brokersupdate_".$nmid."' onclick='openMessageDialog(this.id, \"".$ships[$i]['imo']."\", \"network\")' /></td>
											</tr>
										</table>
									</div>
								</td>
							</tr>
							<tr style='background:#e5e5e5;'>
								<td><div style='padding:5px;'><b>".$operator."</b></div></td>
								<td style='text-align:center;'>
									<div style='padding:5px;'>";
										if(trim($updatearr)){
											echo "<input type='button' class='clickable' style='border:1px solid #c0c0c0; font-weight:normal; height:20px; font-size:10px; color:red;' onclick='oUpdateShipSearch2(".$i.")' value=\"Operator's Update\">";
										}else{
											echo "<input type='button' class='clickable' style='border:1px solid #c0c0c0; font-weight:normal; height:20px; font-size:10px;' onclick='oUpdateShipSearch2(".$i.")' value=\"Operator's Update\">";
										}
									echo "</div>
								</td>
								<td colspan='2'><div style='padding:5px;'><b>Private:</b> <input type='button' style='width:144px; cursor:pointer; background:#f0f0f0; border:1px solid #ccc; font-size:10px; padding:2px;' value='".$private."' alt='".$privatealt."' title='".$privatealt."' id='private_".$mid."' onclick='openMessageDialog(this.id, \"".$ships[$i]['imo']."\", \"private\")' /></div></td>
								<td><div style='padding:5px;'><b>Remarks:</b> <input type='button' style='width:144px; cursor:pointer; background:#f0f0f0; border:1px solid #ccc; font-size:10px; padding:2px;' value='".$remarks."' alt='".$remarksalt."' title='".$remarksalt."' id='brokersupdate_".$nmid."' onclick='openMessageDialog(this.id, \"".$ships[$i]['imo']."\", \"network\")' /> <span style='color:red;'>".$nmessagesuper['user_email']."</span></div></td>
							</tr>";
							
							echo "<tr id='oUpdateShipSearch2".$i."' style='display:none;'>
								<td colspan='5'>
									<table cellpadding='2' cellspacing='2' width='100%' >
										<tr>
											<td style='border:1px solid #f0f0f0; padding:5px; color:#900;'><center><b>OPERATOR'S UPDATE</b></center>
												<table border='0' cellpadding='2' cellspacing='2' width='100%'>
													<tr>
														<th style='background:#BCBCBC; color:#333333; width:150px; text-align:left;'><div style='padding:5px;'>Status</div></th>
														<th style='background:#BCBCBC; color:#333333; width:100px; text-align:left;'><div style='padding:5px;'>Date From</div></th>
														<th style='background:#BCBCBC; color:#333333; width:100px; text-align:left;'><div style='padding:5px;'>Date To</div></th>
														<th style='background:#BCBCBC; color:#333333; width:200px; text-align:left;'><div style='padding:5px;'>Open Port</div></th>
														<th style='background:#BCBCBC; color:#333333; width:100px; text-align:left;'><div style='padding:5px;'>Open Date</div></th>
														<th style='background:#BCBCBC; color:#333333; width:100px; text-align:left;'><div style='padding:5px;'>Last Cargo</div></th>
														<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Remarks</div></th>
														<th style='background:#BCBCBC; color:#333333; width:25px; text-align:center;'><div style='padding:5px;'>F</div></th>
													</tr>
													<tr>
														<td style='background-color:#e5e5e5; text-align:left;'><div style='padding:5px;'>".htmlentities(stripslashes($updatearr['ou_status']))."</div></td>
														<td style='background-color:#e5e5e5; text-align:left;'><div style='padding:5px;'>".htmlentities(stripslashes($updatearr['ou_date_from']))."</div></td>
														<td style='background-color:#e5e5e5; text-align:left;'><div style='padding:5px;'>".htmlentities(stripslashes($updatearr['ou_date_to']))."</div></td>
														<td style='background-color:#e5e5e5; text-align:left;'><div style='padding:5px;'>".htmlentities(stripslashes($updatearr['ou_open_port']))."</div></td>
														<td style='background-color:#e5e5e5; text-align:left;'><div style='padding:5px;'>".htmlentities(stripslashes($updatearr['ou_open_date']))."</div></td>
														<td style='background-color:#e5e5e5; text-align:left;'><div style='padding:5px;'>".htmlentities(stripslashes($updatearr['ou_last_cargo']))."</div></td>
														<td style='background-color:#e5e5e5; text-align:left;'><div style='padding:5px;'>".htmlentities(stripslashes($updatearr['ou_remarks']))."</div></td>";
														
														if(trim(htmlentities(stripslashes($updatearr['filename'])))){
															echo "<td style='background-color:#e5e5e5; text-align:center;'><div style='padding:5px;'><a href='operators_update/".htmlentities(stripslashes($updatearr['filename']))."' target='_blank'><img src='images/icon_excel.png' border='0' /></a></div></td>";
														}else{
															echo "<td style='background-color:#e5e5e5; text-align:center;'><div style='padding:5px;'><img src='images/icon_excel_inactive.png' border='0' /></div></td>";
														}
														
													echo "</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>";
						}
					}
				}
			}
			//END
		}
		
		$t2 = count($shipsA1print);
					
		$_SESSION['shipsReg'] = $shipsA1print;
		
		if($t2){
			for($i2=0; $i2<$t2; $i2++){
				$ship = $shipsA1print[$i2];
				
				$sql = "select * from `_xvas_shipdata_dry` where imo='".$ship['IMO #']."'";
				$ship_data = dbQuery($sql, $link);
				
				//CHECK SHIP IMAGE
				$imageb = base64_encode("http://dataservice.grosstonnage.com/S-Bisphoto.php?imo=".$ship['IMO #']);
				//END
				
				//CHECK OPERATOR
				$owner         = getValue($ship_data[0]['data'], 'OWNER');
				$manager_owner = getValue($ship_data[0]['data'], 'MANAGER_OWNER');
				$manager       = getValue($ship_data[0]['data'], 'MANAGER');
				
				if(trim($owner)){
					$operator = $owner;
				}else if(trim($manager_owner)){
					$operator = $manager_owner;
				}else if(trim($manager)){
					$operator = $manager;
				}else{
					$operator = "&nbsp;";
				}
				//END
				
				//PRIVATE MESSAGE
				$private    = getMessageByImo($ship['IMO #'], 'private');
				$mid        = $private['id'];
				$private    = stripslashes($private['message']);
				$privatealt = htmlentities($private);
				$private    = word_limit($private, 2);
				//END
				
				//BROKERS UPDATES
				$nmessage      = getMessageByImo($ship['IMO #'], 'network');
				$nmessagesuper = $nmessage;
				$nmid          = $nmessage['id'];
				$nmessage      = unserialize($nmessage['message']);
				
				$dely          = $nmessage['dely'];
				$delydate_from = $nmessage['delydate_from'];
				
				$remarksalt = $nmessage['remarks'];
				$remarks    = word_limit($nmessage['remarks'], 2);
				//END
				
				//OPERATORS UPDATE
				$sql = "select * from `_operators_update` where `imo`='".$ship['IMO #']."' ORDER BY dateadded DESC";
				$operators_update = dbQuery($sql, $link);
				
				$updatearr = unserialize($operators_update[0]['updates']);
				//END
				
				//SELECT DESTINATION AND DATE
				$sql = "SELECT * FROM `_xvas_siitech_cache` WHERE id='".$ship['id']."' AND satellite='0' ORDER BY dateupdated DESC";
				$siitech_ships = dbQuery($sql, $link);
				//END
				
				//MAP DETAILS
				$details       = array();
				$details['a']  = 'shipsReg';
				$details['id'] = $i2;
				$details       = base64_encode(serialize($details)); 
				//END
				
				echo "<tr style='background:#c5dc3b;'>
					<td colspan='5'><div style='padding:5px;'><b style='font-size:14px; color:white;'>AIS SHORE</b></div></td>
				</tr>
				<tr style='background:#e5e5e5;'>
					<td>
						<div style='padding:5px;'>
							<table cellpadding='0' cellspacing='0' width='100%'>
								<tr>
									<td width='25'><img src='image.php?b=1&mx=20&p=".$imageb."'></td>
									<td><a class='clickable' onclick='return showShipDetails(\"".$ship['IMO #']."\")' >".$ship['Ship Name']."</a></td>
									<td width='25' style=' text-align:right;'><a class='clickable' title='Contact' alt='Contact' onclick='contactOwner(\"".$ship['IMO #']."\")'><img src='images/contact_icon.png'></a></td>
								</tr>
							</table>
						</div>
					</td>
					<td><div style='padding:5px;'>".$siitech_ships[0]['siitech_destination']."</div></td>
					<td style='text-align:right;'><div style='padding:5px;'><a onclick='openMapRegister(\"".$details."\")' class='clickable'><img src='images/map-icon.png' ></a></div></td>
					<td><div style='padding:5px;'>";
					
					if(date("M d, 'y", strtotime($siitech_ships[0]['siitech_lastseen']))!="Jan 01, '70"){
						echo date("M d, 'y", strtotime($siitech_ships[0]['siitech_lastseen']));
					}else{
						if(date("M d, 'y", strtotime($siitech_ships[0]['siitech_eta']))!="Jan 01, '70"){
							echo date("M d, 'y", strtotime($siitech_ships[0]['siitech_eta']));
						}else{
							echo "&nbsp;";
						}
					}
					
					echo "</div></td>
					<td>
						<div style='padding:5px;'>
							<table cellpadding='0' cellspacing='0' width='100%'>
								<tr>
									<td width='50%'><b>Delivery:</b> <input type='button' style='width:125px; cursor:pointer; background:#f0f0f0; border:1px solid #ccc; font-size:10px; padding:2px;' value='".$dely."' alt='".$dely."' title='".$dely."' id='brokersupdate_".$nmid."' onclick='openMessageDialog(this.id, \"".$ship['IMO #']."\", \"network\")' /></td>
									<td width='50%'><b>Dely Date:</b> <input type='button' style='width:125px; cursor:pointer; background:#f0f0f0; border:1px solid #ccc; font-size:10px; padding:2px;' value='".$delydate_from."' alt='".$delydate_from."' title='".$delydate_from."' id='brokersupdate_".$nmid."' onclick='openMessageDialog(this.id, \"".$ship['IMO #']."\", \"network\")' /></td>
								</tr>
							</table>
						</div>
					</td>
				</tr>
				<tr style='background:#e5e5e5;'>
					<td><div style='padding:5px;'><b>".$operator."</b></div></td>
					<td style='text-align:center;'>
						<div style='padding:5px;'>";
							if(trim($updatearr)){
								echo "<input type='button' class='clickable' style='border:1px solid #c0c0c0; font-weight:normal; height:20px; font-size:10px; color:red;' onclick='oUpdateShipSearch0(".$i2.")' value=\"Operator's Update\">";
							}else{
								echo "<input type='button' class='clickable' style='border:1px solid #c0c0c0; font-weight:normal; height:20px; font-size:10px;' onclick='oUpdateShipSearch0(".$i2.")' value=\"Operator's Update\">";
							}
						echo "</div>
					</td>
					<td colspan='2'><div style='padding:5px;'><b>Private:</b> <input type='button' style='width:144px; cursor:pointer; background:#f0f0f0; border:1px solid #ccc; font-size:10px; padding:2px;' value='".$private."' alt='".$privatealt."' title='".$privatealt."' id='private_".$mid."' onclick='openMessageDialog(this.id, \"".$ship['IMO #']."\", \"private\")' /></div></td>
					<td><div style='padding:5px;'><b>Remarks:</b> <input type='button' style='width:144px; cursor:pointer; background:#f0f0f0; border:1px solid #ccc; font-size:10px; padding:2px;' value='".$remarks."' alt='".$remarksalt."' title='".$remarksalt."' id='brokersupdate_".$nmid."' onclick='openMessageDialog(this.id, \"".$ship['IMO #']."\", \"network\")' /> <span style='color:red;'>".$nmessagesuper['user_email']."</span></div></td>
				</tr>";
				
				echo "<tr id='oUpdateShipSearch0".$i2."' style='display:none;'>
					<td colspan='5'>
						<table cellpadding='2' cellspacing='2' width='100%' >
							<tr>
								<td style='border:1px solid #f0f0f0; padding:5px; color:#900;'><center><b>OPERATOR'S UPDATE</b></center>
									<table border='0' cellpadding='2' cellspacing='2' width='100%'>
										<tr>
											<th style='background:#BCBCBC; color:#333333; width:150px; text-align:left;'><div style='padding:5px;'>Status</div></th>
											<th style='background:#BCBCBC; color:#333333; width:100px; text-align:left;'><div style='padding:5px;'>Date From</div></th>
											<th style='background:#BCBCBC; color:#333333; width:100px; text-align:left;'><div style='padding:5px;'>Date To</div></th>
											<th style='background:#BCBCBC; color:#333333; width:200px; text-align:left;'><div style='padding:5px;'>Open Port</div></th>
											<th style='background:#BCBCBC; color:#333333; width:100px; text-align:left;'><div style='padding:5px;'>Open Date</div></th>
											<th style='background:#BCBCBC; color:#333333; width:100px; text-align:left;'><div style='padding:5px;'>Last Cargo</div></th>
											<th style='background:#BCBCBC; color:#333333; text-align:left;'><div style='padding:5px;'>Remarks</div></th>
											<th style='background:#BCBCBC; color:#333333; width:25px; text-align:center;'><div style='padding:5px;'>F</div></th>
										</tr>
										<tr>
											<td style='background-color:#e5e5e5; text-align:left;'><div style='padding:5px;'>".htmlentities(stripslashes($updatearr['ou_status']))."</div></td>
											<td style='background-color:#e5e5e5; text-align:left;'><div style='padding:5px;'>".htmlentities(stripslashes($updatearr['ou_date_from']))."</div></td>
											<td style='background-color:#e5e5e5; text-align:left;'><div style='padding:5px;'>".htmlentities(stripslashes($updatearr['ou_date_to']))."</div></td>
											<td style='background-color:#e5e5e5; text-align:left;'><div style='padding:5px;'>".htmlentities(stripslashes($updatearr['ou_open_port']))."</div></td>
											<td style='background-color:#e5e5e5; text-align:left;'><div style='padding:5px;'>".htmlentities(stripslashes($updatearr['ou_open_date']))."</div></td>
											<td style='background-color:#e5e5e5; text-align:left;'><div style='padding:5px;'>".htmlentities(stripslashes($updatearr['ou_last_cargo']))."</div></td>
											<td style='background-color:#e5e5e5; text-align:left;'><div style='padding:5px;'>".htmlentities(stripslashes($updatearr['ou_remarks']))."</div></td>";
											
											if(trim(htmlentities(stripslashes($updatearr['filename'])))){
												echo "<td style='background-color:#e5e5e5; text-align:center;'><div style='padding:5px;'><a href='operators_update/".htmlentities(stripslashes($updatearr['filename']))."' target='_blank'><img src='images/icon_excel.png' border='0' /></a></div></td>";
											}else{
												echo "<td style='background-color:#e5e5e5; text-align:center;'><div style='padding:5px;'><img src='images/icon_excel_inactive.png' border='0' /></div></td>";
											}
											
										echo "</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>";
			}
		}
	}else{
		echo "<tr>
			<td style='color:red; text-align:center;' colspan='5'>No Ships</td>
		</tr>";
	}
	
	echo "</table>";
	echo "<div style='font-size:30px; height:30px'>&nbsp;</div>";
}
?>