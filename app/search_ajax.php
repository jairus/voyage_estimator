<?php
@session_start();

include_once(dirname(__FILE__)."/includes/bootstrap.php");

date_default_timezone_set('UTC'); 

if(!function_exists("microtime_float")){
	function microtime_float(){
		list($usec, $sec) = explode(" ", microtime());

		return ((float)$usec + (float)$sec);
	}
}

$time_start = microtime_float();

$link = dbConnect();

//jairus
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


function updateShipData(&$ship, $table){
	$ts = strtotime($ship['dateupdated']);
	$imo = $ship['imo'];

	if((time()-$ts)>(60*60*24*7)){ //1 week
		//jairus double
		//file_get_contents("http://s-bisonline.com/ws/grosstonnage.php?imo=".$imo);

		//$qs = "imo=".$imo."&mode=ALL";

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
			
			if($table=="_xvas_shipdata"){
				$sql = "update `_xvas_parsed2`
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
			}else if($table=="_xvas_shipdata_dry"){
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
			}else if($table=="_xvas_shipdata_container"){
				$sql = "update `_xvas_parsed2_container`
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
			}else if($table=="_xvas_shipdata_osv"){
				$sql = "update `_xvas_parsed2_osv`
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
			}else if($table=="_xvas_shipdata_gas"){
				$sql = "update `_xvas_parsed2_gas`
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
			}else if($table=="_xvas_shipdata_passenger"){
				$sql = "update `_xvas_parsed2_passenger`
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
			}else if($table=="_xvas_shipdata_others"){
				$sql = "update `_xvas_parsed2_others`
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
			}
			
			$sql = "update `$table` set `dateupdated` = now(), `data`='".mysql_escape_string($data)."' where `imo` = '".mysql_escape_string($imo)."'";
			dbQuery($sql, $link);
			$ship['data'] = $data;
	
			echo "<table width='100%'><tr><td style='font-size:11px; text-align:right;'>Last Update of Data: ".date("F j, Y h:i:s",time())."</td></tr></table>";
		}else{
			echo "<table width='100%'><tr><td style='font-size:11px; text-align:right;'>Last Update of Data: ".date("F j, Y h:i:s",$ts)."</td></tr></table>";
		}
	}else{
		echo "<table width='100%'><tr><td style='font-size:11px; text-align:right;'>Last Update of Data: ".date("F j, Y h:i:s",$ts)."</td></tr></table>";
	}
	
	/*$imo = $ship['imo'];
	
	file_get_contents("http://s-bisonline.com/ws/grosstonnage.php?imo=".$imo);

	$qs = "imo=".$imo."&mode=ALL";
	
	echo "<table width='100%'><tr><td style='font-size:11px; text-align:right;'>Last Update of Data: ".date("F j, Y h:i:s",$ts)."</td></tr></table>";*/
}

function getPortId($name, $exact=false){
	global $link;

	$namex = $name;
	$name = trim(mysql_escape_string(stripslashes($name)));

	if(!$name){
		return false;
	}

	/*
	$sql = "SELECT '".$name."' as `given`, `name`, `portid`, `latitude`, `longitude`, if( `name` = '".$name."', 1, 0 ) as `exact`, if( `name` like '%".$name."%' , 1, 0 ) as `soundslike`
	FROM `_veson_ports`
	WHERE if( `name` = '".$name."', 1, 0 )=1 or if( `name` like '%".$name."%' , 1, 0 )=1  order by if( `name` = '".$name."', 1, 0 )=1 desc limit 1";	
	*/
	$sql = "SELECT 
		'".$name."' as `given`,
		`name`, `portid`, 
		`latitude`, 
		`longitude`, 
		if( `name` = '".$name."', 1, 0 ) as `exact`, 
		if( `name` like '%".$name."%' , 1, 0 ) as `soundslike`
	FROM `_veson_ports`
	WHERE 
		if( `name` = '".$name."', 1, 0 )=1 or 
		if( `name` like '%".$name."%' , 1, 0 )=1  
	order by 
		if( `name` = '".$name."', 1, 0 )=1 desc 
	limit 1";	
	
	
	$r = dbQuery($sql, $link);
	$r = $r[0];

	$p = 0;

	similar_text($namex, $r['name'], $p);

	$r['percent'] = $p;

	if($r['exact']=='1'){
		return $r;
	}else if(!$exact){
		return $r;
	}
}

function getPortDetails($portId){
	global $link;

	$sql = "select * from `_veson_ports` where `portid`='".mysql_escape_string($portId)."'";
	$r = dbQuery($sql, $link);

	return $r[0];
}

function navStat($n){
	$nav = array();

	$nav[0] = "under way using engine";
	$nav[1] = "at anchor";
	$nav[2] = "not under command";
	$nav[3] = "restricted maneuverability";
	$nav[4] = "constrained by her draught";
	$nav[5] = "moored";
	$nav[6] = "aground";
	$nav[7] = "engaged in fishing";
	$nav[8] = "under way sailing";
	$nav[9] = "reserved for future amendment of navigational status for ships carrying DG HS, or MP, or IMO hazard or pollutant category C, high speed craft (HSC)";
	$nav[10] = "reserved for future amendment of navigational status for ships carrying dangerous goods (DG), harmful substances (HS) or marine pollutants (MP), or IMO hazard or pollutant category A, wing in grand (WIG)";
	$nav[11] = "reserved for future use";
	$nav[12] = "reserved for future use";
	$nav[13] = "reserved for future use";
	$nav[14] = "reserved for future use";
	$nav[15] = "not defined, default";

	return strtoupper($nav[$n]);
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

function printShipCell($r, $t){
	global $link;
	
	echo '<table width="100%" border="0" cellspacing="0" cellpadding="0">';
		echo '<tr>
			<td style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000; padding:5px 0px; width:80px;"><b>IMO</b></td>
			<td style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000; padding:5px 0px;"><b>NAME</b></td>
			<td style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000; padding:5px 0px; width:200px;"><b>VESSEL TYPE</b></td>
			<td style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000; padding:5px 0px; width:150px;"><b>GROSS TONNAGE</b></td>
			<td style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000; padding:5px 0px; width:80px;"><b>BUILD</b></td>
			<td style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000; padding:5px 0px; width:30px;"><b>FLAG</b></td>
		</tr>';
		
		for($i=0; $i<$t; $i++){
			$imo = $r[$i]['imo'];
	
			$sql = "select * from `_xvas_shipdata` where `imo`='".mysql_escape_string($imo)."'";
			$ship = dbQuery($sql, $link);
			$ship = $ship[0];		
	
			$data = $ship['data'];
	
			if(!trim($data)){
				$sql = "select * from `_xvas_shipdata_dry` where `imo`='".mysql_escape_string($imo)."'";
				$ship = dbQuery($sql, $link);
				$ship = $ship[0];		
	
				$data = $ship['data'];
				
				if(!trim($data)){
					$sql = "select * from `_xvas_shipdata_container` where `imo`='".mysql_escape_string($imo)."'";
					$ship = dbQuery($sql, $link);
					$ship = $ship[0];		
		
					$data = $ship['data'];
					
					if(!trim($data)){
						$sql = "select * from `_xvas_shipdata_osv` where `imo`='".mysql_escape_string($imo)."'";
						$ship = dbQuery($sql, $link);
						$ship = $ship[0];		
			
						$data = $ship['data'];
						
						if(!trim($data)){
							$sql = "select * from `_xvas_shipdata_gas` where `imo`='".mysql_escape_string($imo)."'";
							$ship = dbQuery($sql, $link);
							$ship = $ship[0];		
				
							$data = $ship['data'];
							
							if(!trim($data)){
								$sql = "select * from `_xvas_shipdata_passenger` where `imo`='".mysql_escape_string($imo)."'";
								$ship = dbQuery($sql, $link);
								$ship = $ship[0];		
					
								$data = $ship['data'];
								
								if(!trim($data)){
									$sql = "select * from `_xvas_shipdata_others` where `imo`='".mysql_escape_string($imo)."'";
									$ship = dbQuery($sql, $link);
									$ship = $ship[0];		
						
									$data = $ship['data'];
								}
							}
						}
					}
				}
			}
	
			$data = str_replace("_#", "_NUM", $data);
			$data = str_replace("LENGTH_B/W_PERPENDICULARS", "LENGTH_B_W_PERPENDICULARS", $data);
			$data = str_replace("GRAIN/LIQUID_CAPACITY", "GRAIN_LIQUID_CAPACITY", $data);
			$data = str_replace("LIQUID/OIL", "LIQUID_OIL", $data);
			$data = cleanXML($data);
	
			$rdata = parseXML($data);
	
			$str = serialize($rdata);
			$str = str_replace('O:16:"SimpleXMLElement', 'O:8:"stdClass', $str);
	
			$shipd = unserialize($str);
			
			echo "<tr>";
				echo "<td style='font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000; padding:5px 0px;'>".$shipd->MAIN_DATA->IMO_NUMBER."</td>";
				echo "<td style='font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000; padding:5px 0px;'>".$shipd->MAIN_DATA->NAME."</td>";
				echo "<td style='font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000; padding:5px 0px;'>".$shipd->MAIN_DATA->VESSEL_TYPE."</td>";
				echo "<td style='font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000; padding:5px 0px;'>".$shipd->MAIN_DATA->GROSS_TONNAGE."</td>";	
				echo "<td style='font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000; padding:5px 0px;'>".$shipd->MAIN_DATA->BUILD."</td>";	
		
				$flag = $shipd->MAIN_DATA->FLAG;
				
				$flagimage = getFlagImage($flag);
		
				if($flagimage){
					echo "<td style='font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000; padding:5px 0px;'><img src='".$flagimage."' alt='".$flag."' title='".$flag."' ></td>";	
				}else{
					echo "<td style='font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000; padding:5px 0px;'>$flag</td>";
				}
	
			echo "</tr>";
		}
	
	echo "</table>";
}

function get_zones($lat, $long){
	global $link;

	$sql = "select distinct `zone_code` from `_sbis_zoneblocks` where 
		$long>=`long1` and $long<=`long2` and 
		
		$lat<=`lat1` and
		
		$lat>=`lat4`
	 ";	 

	 $r = dbQuery($sql, $link);

	 $nr = array();

	 foreach($r as $value){
	 	$nr[] = $value['zone_code'];
	 }
	 
	 return $nr;
}

function in_zone_ll($lat, $long, $zone_code){
	global $link;

	$sql = "select * from `_sbis_zoneblocks` where 

		$long>=`long1` and $long<=`long2` and 

		$lat<=`lat1` and

		$lat>=`lat4` and

		`zone_code` = '".$zone_code."' limit 1
	 ";

	 $r = dbQuery($sql, $link);

	 if($r[0]){
	 	return true;
	 }

	 return false;
}

function in_zone($ship, $zonecode){
	global $load_portlang, $load_portlong;

	if($ship['BROKER DEST PORT LAT']!=""){
		return in_zone_ll($ship['BROKER DEST PORT LAT'], $ship['BROKER DEST PORT LONG'], $zonecode);
	}else if($ship['DEST PORT LAT']!=""){
		return in_zone_ll($ship['DEST PORT LAT'], $ship['DEST PORT LONG'], $zonecode);
	}else if($ship['LAT']){
		return in_zone_ll($ship['LAT'], $ship['LONG'], $zonecode);
	}

	return false;
}

//messaging...
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

function getMessage($messageid){
	global $link;

	$messageid = mysql_escape_string($messageid);

	$sql = "select * from `_messages` where `id`='".$messageid."'";
	$r = dbQuery($sql, $link, 1); //last parameter say that take value from write db

	return $r;
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

		$sql = "select * from `_messages` where `imo`='".$imo."' and `type`='private' and `user_email` = '".$email."' order by `id` desc limit 1";
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

function insertMessage($imo, $type, $message){
	global $link;

	$imo = mysql_escape_string($imo);
	$type = mysql_escape_string($type);
	$message = mysql_escape_string($message);
	
	$sql = "insert into `_messages` (`type`, `imo`, `message`, `user_email`, `dateadded`) values

	(

		'".$type."',

		'".$imo."',

		'".$message."',

		'".mysql_escape_string($_SESSION['user']['email'])."',

		NOW()

	)

	";

	$r = dbQuery($sql, $link);

	return $r;
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

function deleteMessage($mid){
	global $link;

	$sql = "delete from `_messages` where `id`='".$mid."' and `user_email`='".$_SESSION['user']['email']."'";
	$r = dbQuery($sql, $link);
}

function messageOpened($mid){
	global $link;

	$sql = "select * from `_message_opened` where `message_id`='".$mid."' and `user_id`='".$_SESSION['user']['id']."'";
	$r = dbQuery($sql, $link);

	if($r[0]){
		return true;
	}else{
		return false;
	}
}

function processMids($mids){
	global $link;

	$t = count($mids);

	$ret = array();

	for($i=0; $i<$t; $i++){
		if(trim($mids[$i])){
			list($type, $imo) = explode("_", $mids[$i]);

			if($type=='useremail'||$type=='useremail2'||$type=='useremail3'||$type=='useremail4'||$type=='useremail5'||$type=='useremail6'||$type=='useremail7'){
				$type = "user_email";
			}

			$type = mysql_escape_string($type);
			$imo = mysql_escape_string($imo);
			
			$r = getMessageByImo($imo, $type);

			$ret[$mids[$i]] = array();

			if($type=='private'||$type=='private2'||$type=='private3'||$type=='private4'||$type=='private5'||$type=='private6'||$type=='private7'){
				$r['message'] = stripslashes($r['message']);

				$ret[$mids[$i]]['opened'] = messageOpened($r['id']);
				$ret[$mids[$i]]['short'] = word_limit($r['message'], 2);
				$ret[$mids[$i]]['long'] = htmlentities($r['message']);
				$ret[$mids[$i]]['mid'] = htmlentities($r['id']);
			}else if($type=='user_email'||$type=='user_email2'||$type=='user_email3'||$type=='user_email4'||$type=='user_email5'||$type=='user_email6'||$type=='user_email7'){
				$ret[$mids[$i]]['user_email'] = $r['user_email'];
				$ret[$mids[$i]]['opened'] = messageOpened($r['id']);
			}else if($type=='openport'||$type=='openport2'||$type=='openport3'||$type=='openport4'||$type=='openport5'||$type=='openport6'||$type=='openport7'||$type=='opendate'||$type=='opendate2'||$type=='opendate3'||$type=='opendate4'||$type=='opendate5'||$type=='opendate6'||$type=='opendate7'||$type=='destinationregion'||$type=='destinationregion2'||$type=='destinationregion3'||$type=='destinationregion4'||$type=='destinationregion5'||$type=='destinationregion6'||$type=='destinationregion7'||$type=='destinationdate'||$type=='destinationdate2'||$type=='destinationdate3'||$type=='destinationdate4'||$type=='destinationdate5'||$type=='destinationdate6'||$type=='destinationdate7'||$type=='charterer'||$type=='charterer2'||$type=='charterer3'||$type=='charterer4'||$type=='charterer5'||$type=='charterer6'||$type=='charterer7'||$type=='remark'||$type=='remark2'||$type=='remark3'||$type=='remark4'||$type=='remark5'||$type=='remark6'||$type=='remark7'||$type=='cargotype'||$type=='cargotype2'||$type=='cargotype3'||$type=='cargotype4'||$type=='cargotype5'||$type=='cargotype6'||$type=='cargotype7'||$type=='quantity'||$type=='quantity2'||$type=='quantity3'||$type=='quantity4'||$type=='quantity5'||$type=='quantity6'||$type=='quantity7'||$type=='status'||$type=='status2'||$type=='status3'||$type=='status4'||$type=='status5'||$type=='status6'||$type=='status7'||$type=='cbm'||$type=='cbm2'||$type=='cbm3'||$type=='cbm4'||$type=='cbm5'||$type=='cbm6'||$type=='cbm7'||$type=='rate'||$type=='rate2'||$type=='rate3'||$type=='rate4'||$type=='rate5'||$type=='rate6'||$type=='rate7'||$type=='tce'||$type=='tce2'||$type=='tce3'||$type=='tce4'||$type=='tce5'||$type=='tce6'||$type=='tce7'||$type=='ws'||$type=='ws2'||$type=='ws3'||$type=='ws4'||$type=='ws5'||$type=='ws6'||$type=='ws7'){
				$messagearr = unserialize($r['message']);

				$ret[$mids[$i]]['opened'] = messageOpened($r['id']);

				if($type=='opendate'||$type=='opendate2'||$type=='opendate3'||$type=='opendate4'||$type=='opendate5'||$type=='opendate6'||$type=='opendate7'){
					$date = $messagearr['opendate'];
					$date = explode("/",$date);

					if($date[2]){
						$date = $date[2]."-".$date[0]."-".$date[1]." 00:00:00";
						$date = strtotime($date);

						$ret[$mids[$i]]['short'] = date("M, d 'y", $date);
						$ret[$mids[$i]]['long'] = htmlentities(date("M, d 'y", $date));
					}
				}else{
					$type = preg_replace("/[0-9]/iUs", "", $type);

					$ret[$mids[$i]]['short'] = stripslashes($messagearr[$type]);
					$ret[$mids[$i]]['long'] = htmlentities(stripslashes($messagearr[$type]));
					$ret[$mids[$i]]['mid'] = htmlentities($type.$r['id']);
				}

				$ret[$mids[$i]]['mid'] = htmlentities($type.$r['id']);
			}
		}
	}

	return $ret;
}

function convertDateToTs($date){
	if(strpos($date, "/")!==false){
		$date = trim($date);
		$date = explode("/", $date);
		$date = $date[2]."-".$date[0]."-".$date[1]." 00:00:00";
	}

	return strtotime($date);
}

function floorTs($ts){
	$date = date("Y-m-d 00:00:00",$ts);
	$ts = strtotime($date);
	
	return $ts;
}

function bbsort($ran){
	$array_size = count($ran);

	for($x = 0; $x < $array_size; $x++) {
	  for($y = 0; $y < $array_size; $y++) {
		if($ran[$x]['siitech_receivetime'] > $ran[$y]['siitech_receivetime']) {
		  $hold = $ran[$x];
		  $ran[$x] = $ran[$y];
		  $ran[$y] = $hold;
		}
	  }
	}

	return $ran;
}

if($_GET['action']=='noop'){
	exit();
}else if($_GET['action']=='getmessages'){
	if($_GET['task']=='fetchmessages'){
		return false;
		
		$mids = explode("|", $_POST['mids']);

		$r = processMids($mids);

		echo json_encode($r);

		exit();
	}else if($_GET['task']=='deletemessage'){
		$r = array();

		$sql = "delete from `_messaged`";

		deleteMessage($_POST['messageid']);

		$r['message'] = "Successfully Deleted";
		$r['messageid'] = $_POST['messageid'];

		echo json_encode($r);

		exit();
	}else if($_GET['task']=='addmessage'){
		if(trim($_POST['message'])){
			$message = $_POST['message'];

			$r = insertMessage($_POST['imo'], $_POST['type'], $message);
			$r = getMessage($r['mysql_insert_id']);

			$r[0]['dateadded'] = date("M d, 'y", strtotime($r[0]['dateadded']));
			$r[0]['message'] = stripslashes($r[0]['message']);
			
			echo json_encode($r[0]);
		}else if(!trim($_POST['message'])&&($_POST['openport']||$_POST['opendate']||$_POST['destinationregion']||$_POST['destinationdate']||$_POST['charterer']||$_POST['remark']||$_POST['cargotype']||$_POST['quantity']||$_POST['status']||$_POST['cbm']||$_POST['rate']||$_POST['tce']||$_POST['ws'])){
			//for network
			$messagearr = array();

			$messagearr['openport']          = $_POST['openport'];
			$messagearr['opendate']          = $_POST['opendate'];
			$messagearr['destinationregion'] = $_POST['destinationregion'];
			$messagearr['destinationdate']   = $_POST['destinationdate'];
			$messagearr['charterer']         = $_POST['charterer'];
			$messagearr['remark']            = $_POST['remark'];
			$messagearr['cargotype']         = $_POST['cargotype'];
			$messagearr['quantity']          = $_POST['quantity'];
			$messagearr['status']            = $_POST['status'];
			$messagearr['cbm']               = $_POST['cbm'];
			$messagearr['rate']              = $_POST['rate'];
			$messagearr['tce']               = $_POST['tce'];
			$messagearr['ws']                = $_POST['ws'];

			$message = serialize($messagearr);

			$r = insertMessage($_POST['imo'], $_POST['type'], $message);
			$r = getMessage($r['mysql_insert_id']);

			$r[0]['dateadded'] = date("M d, 'y", strtotime($r[0]['dateadded']));
			$r[0]['messagearr'] = unserialize($r[0]['message']);

			$messagearr = $r[0]['messagearr'];

			$date = $messagearr['opendate'];
			$date = convertDateToTs($date);

			$r[0]['messagearr']['openport'] = stripslashes($r[0]['messagearr']['openport']);
			$r[0]['messagearr']['opendate'] = date("M, d 'y", $date);
			$r[0]['messagearr']['destinationregion'] = stripslashes($r[0]['messagearr']['destinationregion']);
			$r[0]['messagearr']['destinationdate'] = stripslashes($r[0]['messagearr']['destinationdate']);
			$r[0]['messagearr']['charterer'] = stripslashes($r[0]['messagearr']['charterer']);
			$r[0]['messagearr']['remark']   = stripslashes($r[0]['messagearr']['remark']);
			$r[0]['messagearr']['cargotype'] = stripslashes($r[0]['messagearr']['cargotype']);
			$r[0]['messagearr']['quantity'] = stripslashes($r[0]['messagearr']['quantity']);
			$r[0]['messagearr']['status'] = stripslashes($r[0]['messagearr']['status']);
			$r[0]['messagearr']['cbm'] = stripslashes($r[0]['messagearr']['cbm']);
			$r[0]['messagearr']['rate'] = stripslashes($r[0]['messagearr']['rate']);
			$r[0]['messagearr']['tce'] = stripslashes($r[0]['messagearr']['tce']);
			$r[0]['messagearr']['ws'] = stripslashes($r[0]['messagearr']['ws']);

			$r[0]['message'] = "";

			echo json_encode($r[0]);
		}else{
			$r['message'] = "";

			echo json_encode($r);
		}

		exit();
	}else if($_GET['task']=='addmessagedry'){
		if(trim($_POST['message'])){
			$message = $_POST['message'];

			$r = insertMessage($_POST['imo'], $_POST['type'], $message);
			$r = getMessage($r['mysql_insert_id']);

			$r[0]['dateadded'] = date("M d, 'y", strtotime($r[0]['dateadded']));
			$r[0]['message'] = stripslashes($r[0]['message']);
			
			echo json_encode($r[0]);
		}else if(!trim($_POST['message'])&&($_POST['dely']||$_POST['delydate_from']||$_POST['delydate_to']||$_POST['redely1']||$_POST['redelydate1']||$_POST['redely2']||$_POST['redelydate2']||$_POST['redely3']||$_POST['redelydate3']||$_POST['redely4']||$_POST['redelydate4']||$_POST['rate']||$_POST['charterer']||$_POST['period']||$_POST['dur_min']||$_POST['dur_max']||$_POST['relet']||$_POST['remarks'])){
			//for network
			$messagearr = array();

			$messagearr['kind']          = "dry";
			$messagearr['dely']          = $_POST['dely'];
			$messagearr['delydate_from'] = $_POST['delydate_from'];
			$messagearr['delydate_to']   = $_POST['delydate_to'];
			$messagearr['redely1']       = $_POST['redely1'];
			$messagearr['redelydate1']   = $_POST['redelydate1'];
			$messagearr['redely2']       = $_POST['redely2'];
			$messagearr['redelydate2']   = $_POST['redelydate2'];
			$messagearr['redely3']       = $_POST['redely3'];
			$messagearr['redelydate3']   = $_POST['redelydate3'];
			$messagearr['redely4']       = $_POST['redely4'];
			$messagearr['redelydate4']   = $_POST['redelydate4'];
			$messagearr['rate']          = $_POST['rate'];
			$messagearr['charterer']     = $_POST['charterer'];
			$messagearr['period']        = $_POST['period'];
			$messagearr['dur_min']       = $_POST['dur_min'];
			$messagearr['dur_max']       = $_POST['dur_max'];
			$messagearr['relet']         = $_POST['relet'];
			$messagearr['remarks']       = $_POST['remarks'];

			$message = serialize($messagearr);

			$r = insertMessage($_POST['imo'], $_POST['type'], $message);
			$r = getMessage($r['mysql_insert_id']);

			$r[0]['dateadded'] = date("M d, 'y", strtotime($r[0]['dateadded']));
			$r[0]['messagearr'] = unserialize($r[0]['message']);

			$messagearr = $r[0]['messagearr'];

			$r[0]['messagearr']['kind']          = stripslashes($r[0]['messagearr']['kind']);
			$r[0]['messagearr']['dely']          = stripslashes($r[0]['messagearr']['dely']);
			$r[0]['messagearr']['delydate_from'] = stripslashes($r[0]['messagearr']['delydate_from']);
			$r[0]['messagearr']['delydate_to']   = stripslashes($r[0]['messagearr']['delydate_to']);
			$r[0]['messagearr']['redely1']       = stripslashes($r[0]['messagearr']['redely1']);
			$r[0]['messagearr']['redelydate1']   = stripslashes($r[0]['messagearr']['redelydate1']);
			$r[0]['messagearr']['redely2']       = stripslashes($r[0]['messagearr']['redely2']);
			$r[0]['messagearr']['redelydate2']   = stripslashes($r[0]['messagearr']['redelydate2']);
			$r[0]['messagearr']['redely3']       = stripslashes($r[0]['messagearr']['redely3']);
			$r[0]['messagearr']['redelydate3']   = stripslashes($r[0]['messagearr']['redelydate3']);
			$r[0]['messagearr']['redely4']       = stripslashes($r[0]['messagearr']['redely4']);
			$r[0]['messagearr']['redelydate4']   = stripslashes($r[0]['messagearr']['redelydate4']);
			$r[0]['messagearr']['rate']          = stripslashes($r[0]['messagearr']['rate']);
			$r[0]['messagearr']['charterer']     = stripslashes($r[0]['messagearr']['charterer']);
			$r[0]['messagearr']['period']        = stripslashes($r[0]['messagearr']['period']);
			$r[0]['messagearr']['dur_min']       = stripslashes($r[0]['messagearr']['dur_min']);
			$r[0]['messagearr']['dur_max']       = stripslashes($r[0]['messagearr']['dur_max']);
			$r[0]['messagearr']['relet']         = stripslashes($r[0]['messagearr']['relet']);
			$r[0]['messagearr']['remarks']       = stripslashes($r[0]['messagearr']['remarks']);

			$r[0]['message'] = "";

			echo json_encode($r[0]);
		}else{
			$r['message'] = "";

			echo json_encode($r);
		}

		exit();
	}

	include_once(dirname(__FILE__)."/includes/shipsearch/messages.php");

	exit();
}else if($_GET['action']=='getemail'){
	?>
    <script type='text/javascript' src='js/jquery-ui-1.8.4.custom/js/jquery-1.4.2.min.js'></script>
    <script>
	function sendEmailReply(){
		jQuery('#pleasewait_emailreply').show();
		jQuery('#emailreplyresults').hide();
		
		jQuery("#sendbutt").attr("disabled", true);

		jQuery.ajax({
			type: 'GET',
			url: "send_email.php",
			data:  jQuery("#email_reply_form").serialize(),

			success: function(data) {
				jQuery("#emailreply_wrapperonly").html(data);
				jQuery('#emailreplyresults').fadeIn(200);
				
				jQuery('#pleasewait_emailreply').hide();

				jQuery("#sendbutt").attr("disabled", false);
			}
		});
	}
	</script>
    <?php
	if($_GET['email_category']=="vessel"){
		$sql = "SELECT * FROM `_blackbox_vessel` WHERE `id`='".$_GET['email_id']."'";
		$eupdate = dbQuery($sql, $link);
		$eupdate = $eupdate[0];
		
		echo '<form id="email_reply_form" onsubmit="sendEmailReply(); return false;">';
		echo "<table width='100%' cellpading='0' cellspacing='0' border='0'>
			<tr>
				<td style='padding-bottom:20px;' colspan='2'>";
				
				echo '
				<input type="hidden" id="email_category" name="email_category" value="vessel" />
				<input type="hidden" id="from_address" name="from_address" value="'.$eupdate['from_address'].'" />
				<input type="hidden" id="vessel_name" name="vessel_name" value="'.$eupdate['vessel_name'].'" />
				<input type="hidden" id="location_name" name="location_name" value="'.$eupdate['location_name'].'" />
				<input type="hidden" id="location_lat" name="location_lat" value="'.$eupdate['location_lat'].'" />
				<input type="hidden" id="location_lng" name="location_lng" value="'.$eupdate['location_lng'].'" />
				<input type="hidden" id="from_time" name="from_time" value="'.$eupdate['from_time'].'" />
				<input type="hidden" id="to_time" name="to_time" value="'.$eupdate['to_time'].'" />
				';
				
				echo "<b>FROM:</b> ".$eupdate['from_address']."<br>";
				echo "<b>VESSEL NAME:</b> ".$eupdate['vessel_name']."<br>";
				echo "<b>LOCATION:</b> ".$eupdate['location_name']."<br>";
				echo "<b>LAT:</b> ".$eupdate['location_lat']."<br>";
				echo "<b>LONG:</b> ".$eupdate['location_lng']."<br>";
				echo "<b>FROM TIME:</b> ".$eupdate['from_time']."<br>";
				echo "<b>TO TIME:</b> ".$eupdate['to_time'];
				
				echo "</td>
			</tr>
			<tr>
				<td style='vertical-align:top'><textarea name='email_reply' id='email_reply' style='width:650px; height:150px;'></textarea></td>
				<td style='vertical-align:top'><input type='button' id='sendbutt' value='Submit' onclick='sendEmailReply();' style='width:100px; height:50px;'></td>
			</tr>
			<tr>
				<td style='padding-top:20px;' colspan='2'>";
				
					echo "
					<div id='pleasewait_emailreply' style='display:none; text-align:center'>
						<center>
						<table>
							<tr>
								<td style='text-align:center'><img src='images/searching.gif' ></td>
							</tr>
						</table>
						</center>
					</div>
					";
				
				echo "</td>
			</tr>
			<tr>
				<td style='padding-top:20px;' colspan='2'>";
				
					echo "
					<div id='emailreplyresults'>
						<div id='emailreply_wrapperonly'></div>
					</div>
					";
				
				echo "</td>
			</tr>
		</table>";
		echo '</form>';
	}else if($_GET['email_category']=="cargo"){
		$sql = "SELECT * FROM `_blackbox_cargo` WHERE `id`='".$_GET['email_id']."'";
		$eupdate = dbQuery($sql, $link);
		$eupdate = $eupdate[0];
		
		echo '<form id="email_reply_form" onsubmit="sendEmailReply(); return false;">';
		echo "<table width='100%' cellpading='0' cellspacing='0' border='0'>
			<tr>
				<td style='padding-bottom:20px;' colspan='2'>";
				
				echo '
				<input type="hidden" id="email_category" name="email_category" value="vessel" />
				<input type="hidden" id="from_address" name="from_address" value="'.$eupdate['from_address'].'" />
				<input type="hidden" id="load_name" name="load_name" value="'.$eupdate['load_name'].'" />
				<input type="hidden" id="load_lat" name="load_lat" value="'.$eupdate['load_lat'].'" />
				<input type="hidden" id="load_lng" name="load_lng" value="'.$eupdate['load_lng'].'" />
				<input type="hidden" id="discharge_name" name="discharge_name" value="'.$eupdate['discharge_name'].'" />
				<input type="hidden" id="discharge_lat" name="discharge_lat" value="'.$eupdate['discharge_lat'].'" />
				<input type="hidden" id="discharge_lng" name="discharge_lng" value="'.$eupdate['discharge_lng'].'" />
				<input type="hidden" id="from_time" name="from_time" value="'.$eupdate['from_time'].'" />
				<input type="hidden" id="to_time" name="to_time" value="'.$eupdate['to_time'].'" />
				';
				
				echo "<b>FROM:</b> ".$eupdate['from_address']."<br>";
				echo "<b>LOAD PORT:</b> ".$eupdate['load_name']."<br>";
				echo "<b>LOAD LAT:</b> ".$eupdate['load_lat']."<br>";
				echo "<b>LOAD LONG:</b> ".$eupdate['load_lng']."<br>";
				echo "<b>DISCHARGE PORT:</b> ".$eupdate['discharge_name']."<br>";
				echo "<b>DISCHARGE LAT:</b> ".$eupdate['discharge_lat']."<br>";
				echo "<b>DISCHARGE LONG:</b> ".$eupdate['discharge_lng']."<br>";
				echo "<b>FROM TIME:</b> ".$eupdate['from_time']."<br>";
				echo "<b>TO TIME:</b> ".$eupdate['to_time'];
				
				echo "</td>
			</tr>
			<tr>
				<td style='vertical-align:top'><textarea name='email_reply' id='email_reply' style='width:650px; height:150px;'></textarea></td>
				<td style='vertical-align:top'><input type='button' id='sendbutt' value='Submit' onclick='sendEmailReply();' style='width:100px; height:50px;'></td>
			</tr>
			<tr>
				<td style='padding-top:20px;' colspan='2'>";
				
					echo "
					<div id='pleasewait_emailreply' style='display:none; text-align:center'>
						<center>
						<table>
							<tr>
								<td style='text-align:center'><img src='images/searching.gif' ></td>
							</tr>
						</table>
						</center>
					</div>
					";
				
				echo "</td>
			</tr>
			<tr>
				<td style='padding-top:20px;' colspan='2'>";
				
					echo "
					<div id='emailreplyresults'>
						<div id='emailreply_wrapperonly'></div>
					</div>
					";
				
				echo "</td>
			</tr>
		</table>";
		echo '</form>';
	}
	
	exit();
}



//load port and date range
$load_port = strtoupper(trim($_GET['load_port']));
$load_portx = getPortId($load_port, 1);
$load_portid = $load_portx['portid'];
$load_portlat = $load_portx['latitude'];
$load_portlong = $load_portx['longitude'];

$lpf = $_GET['load_port_from'];
$lpf = explode("/", $lpf);

$lpt = $_GET['load_port_to'];
$lpt = explode("/", $lpt);

$lpfts = convertDateToTs($_GET['load_port_from']);
$lptts = convertDateToTs($_GET['load_port_to']);

$lpff = date("M j, 'y", $lpfts);
$lptf = date("M j, 'y", $lptts);



if($_GET['action']=='getzones'){
	$zones = get_zones($load_portlat, $load_portlong);

	if($_GET['dwt_range']){
		$dwtr = trim($_GET['dwt_range']);

		if($dwtr=="0|3"){
			$dwt_low = 500;
			$dwt_high = 3000;
			$dwt_type = "Others";
		}else if($dwtr=="3|10"){
			$dwt_low = 3001;
			$dwt_high = 10000;	
			$dwt_type = "Coastal";
		}else if($dwtr=="10|19"){
			$dwt_low = 10001;
			$dwt_high = 19000;	
			$dwt_type = "Small";
		}else if($dwtr=="19|30"){
			$dwt_low = 19001;
			$dwt_high = 30000;	
			$dwt_type = "Handy";
		}else if($dwtr=="30|55"){
			$dwt_low = 30001;
			$dwt_high = 55000;
			$dwt_type = "MR";
		}else if($dwtr=="50|80"){
			$dwt_low = 50001;
			$dwt_high = 80000;	
			$dwt_type = "Panamax / LR1";	
		}else if($dwtr=="80|120"){
			$dwt_low = 80001;
			$dwt_high = 120000;
			$dwt_type = "Aframax";
		}else if($dwtr=="120|200"){
			$dwt_low = 120001;
			$dwt_high = 200000;
			$dwt_type = "Suezmax";
		}else if($dwtr=="200|350"){
			$dwt_low = 200001;
			$dwt_high = 350000;	
			$dwt_type = "VLCC / VLOC";
		}else if($dwtr=="350|550"){
			$dwt_low = 350001;
			$dwt_high = 550000;	
			$dwt_type = "ULCC";
		}

		$zcount = count($zones);

		if($dwt_low>180000){
			for($zoni=0; $zoni<$zcount; $zoni++){
				$value = $zones[$zoni];

				if($value=='5'){
					unset($zones[$zoni]);
				}
			}

			$zones = array_values($zones);
		}

		if($dwt_low>100000){
			for($zoni=0; $zoni<$zcount; $zoni++){
				$value = $zones[$zoni];

				if($value=='7'||$value=='8'||$value=='9'){
					unset($zones[$zoni]);
				}
			}

			$zones = array_values($zones);
		}

		if($dwt_low>80000){
			for($zoni=0; $zoni<$zcount; $zoni++){
				$value = $zones[$zoni];

				if($value=='12'){
					unset($zones[$zoni]);
				}
			}

			$zones = array_values($zones);
		}
	}
	?>

	<select name='zone' id='zones_id' onchange='showMinimap(this.value)' style="width:440px;">
    	<?php if($_GET['dry']==1){ ?>
        <!-- DRY -->
        <option value='CT1' <?php if(!in_array('CT1', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[CT1] C2 CAPESIZE TUBARO (BRAZIL) TO ROTTERDAM (NETHERLANDS) IRON ORE</option>
        <option value='CT2' <?php if(!in_array('CT2', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[CT2] C3 CAPESIZE TUBARO (BRAZIL) TO BEILUN - BAOSHAN (CHINA) IRON ORE</option>
        <option value='NE' <?php if(!in_array('NE', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[NE] C5 NEWCASTLE (AUSTRALIA) TO BEILUN - BOASHAN (CHINA) IRON ORE</option>
        <option value='CB' <?php if(!in_array('CB', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[CB] C4 CAPESIZE RICHARDS BAY (SOUTH AFRICA) TO ROTTERDAM (NETHERLANDS) COAL</option>
        <option value='PG' <?php if(!in_array('PG', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[PG] C12 PANAMAX GLADSTONE (AUSTRALIA) TO ROTTERDAM (NETHERLANDS) COAL</option>
        <option value='PT' <?php if(!in_array('PT', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[PT] P1A PANAMAX TRANSATLANTIC (PRIMARILY LATIN AMERICA TO EUROPE) IRON ORE, COAL, GRAIN</option>
        <option value='PF' <?php if(!in_array('PF', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[PF] P2A PANAMAX EUROPE TO FAR EAST (PRIMARILY CHINA) via SUEZ CANAL IRON ORE, COAL, GRAIN</option>
        <option value='PP' <?php if(!in_array('PP', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[PP] PA3A PANAMAX PACIFIC ROUND TRIP (AUSTRALI TO CHINA) IRON ORE, COAL, GRAIN</option>
        <option value='PV' <?php if(!in_array('PV', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[PV] PA4A PANAMAX FAR E. TO EUROPE VIA PANAMA CANAL (Japan eastbound) COAL, IRON ORE, GRAIN</option>
        <option value='PE' <?php if(!in_array('PE', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[PE] PERSIAN GULF, BAY OF BENGAL AND ASIA</option>
        <option value='UA' <?php if(!in_array('UA', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[UA] USA TO ASIA COAL, GRAIN</option>
        <option value='CO' <?php if(!in_array('CO', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[CO] WEST COAST NORTH & SOUTH AMERICAN GRAIN</option>
        <option value='EU' <?php if(!in_array('EU', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[EU] EUROPE TO US EAST COAST</option>
        <option value='CV' <?php if(!in_array('CV', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[CV] C7 CAPESIZE BOLIVAR (VENEZUELA) TO ROTTERDAM (NETHERLANDS) COAL</option>
        <option value='NS' <?php if(!in_array('NS', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[NS] NORTH & SOUTH AMERICA</option>
        <option value='AF' <?php if(!in_array('AF', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[AF] AFRICA, MEG & INDIA</option>
        <option value='AU' <?php if(!in_array('AU', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[AU] AUSTRALIA & ASIA</option>
        <option value='BA' <?php if(!in_array('BA', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[BA] BALTIC TO ASIA</option>
        <!-- END DRY -->
        <?php }else if($_GET['dry']==2 || $_GET['dry']==3 || $_GET['dry']==4 || $_GET['dry']==5 || $_GET['dry']==6){ ?>
        <!-- OTHER TYPES -->
        <option value='5a' <?php if(!in_array('5a', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[E1] ALL COASTAL PORT(S) THROUGHOUT THE WORLD (OVER 80K NO PANAMA CANAL)</option>
		<option value='7' <?php if(!in_array('7', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>  [G] ALL COASTAL PORT(S) THROUGHOUT THE WORLD (NO CANALS)</option>
        <!-- END OTHER TYPES -->
        <?php }else{ ?>
        <!-- WET -->
    	<option value='IA' <?php if(!in_array('IA', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[IA] INDIA & ASIA</option>
        <option value='IM' <?php if(!in_array('IM', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[IM] INDIA & MEG</option>
        <option value='AG1' <?php if(!in_array('AG1', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[AG1] TD1 VLCC AG TO US GULF RAS TANURA TO LOOP - NO SUEZ</option>
        <option value='AG2' <?php if(!in_array('AG2', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[AG2] TD1 VLCC AG TO US GULF RAS TANURA TO LOOP VIA SUEZ CANAL</option>
        <option value='AG3' <?php if(!in_array('AG3', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[AG3] TD2 VLCC AG TO SINGAPORE RAS TANURA TO SINGAPORE</option>
        <option value='AG4' <?php if(!in_array('AG4', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[AG4] TD3 VLCC AG TO JAPAN RAS TANURA TO CHIBA</option>
        <option value='WE' <?php if(!in_array('WE', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[WE] TD4 VLCC WEST AFRICA TO US GULF BONNY TO LOOP</option>
        <option value='BL' <?php if(!in_array('BL', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[BL] TD6 SUEZMAX BLACK SEA TO MEDITERRANEAN NOVOROSSIYSK TO AUGUSTA</option>
        <option value='KW' <?php if(!in_array('KW', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[KW] TD8 AFRAMAX KUWAIT TO SINGAPORE MENA AL AHMADI TO SINGAPORE</option>
        <option value='CA1' <?php if(!in_array('CA1', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[CA1] TD9 PANAMAX CARRIBEAN TO US GULF PUERTO LA CRUZ TO CORPUS CHRISTI</option>
        <option value='CA2' <?php if(!in_array('CA2', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[CA2] TD10D PANAMAX CARRIBEAN TO USAC ARUBA TO NEW YORK</option>
        <option value='CR' <?php if(!in_array('CR', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[CR] TD11 AFRAMAX CROSS MEDITERRANEAN BANAIS TO LAVERA</option>
        <option value='AR' <?php if(!in_array('AR', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[AR] TD12 PANAMAX ARA TO US GULF ANTWERP TO HOUSTON</option>
        <option value='SE' <?php if(!in_array('SE', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[SE] TD14 AFRAMAX SE ASIA TO EC AUSTRALIA SERIA TO SYDNEY</option>
        <option value='WA' <?php if(!in_array('WA', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[WA] TD 15 VLCC WEST AFRICA TO CHINA BONNY TO NINGBO</option>
        <option value='BS' <?php if(!in_array('BS', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[BS] TD 16 HANDYMAX BLACK SEA TO MEDITERRANEAN ODESSA TO AUGUSTA</option>
        <option value='PA' <?php if(!in_array('PA', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[PA] TC1 PANAMAX MEG TO JAPAN RAS TANURA TO YOKOHAMA</option>
        <option value='MR1' <?php if(!in_array('MR1', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[MR1] TC2 MR CONTINENT TO USAC ROTTERDAM TO NEW YORK</option>
        <option value='MR2' <?php if(!in_array('MR2', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[MR2] TC3 MR2 CARIBBEAN TO USAC ARUBA TO NEW YORK</option>
        <option value='SI' <?php if(!in_array('SI', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[SI] TC4 SINGAPORE TO JAPAN SINGAPORE TO CHIBA</option>
        <option value='ME' <?php if(!in_array('ME', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[ME] TC5 MEG TO JAPAN RAS TANURS TO YOKOHAMA</option>
        <option value='MA' <?php if(!in_array('MA', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[MA] TC6 MR ALGERIA TO EUROMED SKIKDA TO LAVERA</option>
        <option value='MH' <?php if(!in_array('MH', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[MH] TC6 MR AND HANDY ALGERIA TO EUROMED SKIKDA TO LAVERA</option>
        <option value='MS' <?php if(!in_array('MS', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[MS] TC7 MR OR HANDY SINGAPORE TO EC AUSTRALIA</option>
        <option value='PU' <?php if(!in_array('PU', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[PU] TC8 PANAMAX AG TO UK AND CONTINENT JUBAIL TO ROTTERDAM</option>
        <option value='HA' <?php if(!in_array('HA', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[HA] TC9 HANDY BALTIC TO UK AND CONTINENT VENTSPILS TO LE HAVRE</option>
		<!-- END WET -->
        <?php } ?>
        
        <?php if($_GET['dry']==0 || $_GET['dry']==1){ ?>
        <!-- OTHERS -->
		<option value='1' <?php if(!in_array('1', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>  [A] NORTH EAST ASIA TO WEST COAST OF NORTH AMERICA</option>
		<option value='3' <?php if(!in_array('3', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>  [C] EAST INDIA TO AUSTRALIA TO ASIA TO WEST COAST OF NORTH AMERICA</option>
		<option value='5' <?php if(!in_array('5', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>  [E] EAST AFRICA TO AG/MEG TO ASIA TO AUSTRALIA TO WC OF NORTH AMERICA</option>
		<option value='5a' <?php if(!in_array('5a', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[E1] ALL COASTAL PORT(S) THROUGHOUT THE WORLD (OVER 80K NO PANAMA CANAL)</option>
		<option value='6' <?php if(!in_array('6', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>  [F] WEST AND EAST AFRICA TO AG/MEG TO SEA TO AUSTRALIA</option>
		<option value='7' <?php if(!in_array('7', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>  [G] ALL COASTAL PORT(S) THROUGHOUT THE WORLD (NO CANALS)</option>
		<option value='8' <?php if(!in_array('8', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>  [H] WITHIN EUROPE, BLACK SEA, MEDITERRANEAN SEA, NORTH SEA, BALTIC SEA</option>
		<option value='9' <?php if(!in_array('9', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>  [I] EC OF NORTH AND SOUTH AMERICA TO WC AFRICA AND EUROPE (NO SUEZ)</option>
		<option value='11' <?php if(!in_array('11', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[K] EAST COAST OF N.AMERICA TO WEST COAST OF EUROPE</option>
		<option value='12' <?php if(!in_array('12', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[L] EAST AND WEST COAST OF NORTH AND SOUTH AMERICA</option>
		<option value='12a' <?php if(!in_array('12a', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[L1] EAST COAST OF NORTH AND SOUTH AMERICA</option>
		<option value='13' <?php if(!in_array('13', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[M] WEST COAST OF NORTH AMERICA TO EAST COAST OF RUSSIA</option>
		<option value='14' <?php if(!in_array('14', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[N] WEST COAST OF NORTH AND SOUTH AMERICA TO EAST COAST OF RUSSIA</option>
		<option value='15' <?php if(!in_array('15', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[O] WEST COAST OF NORTH AND SOUTH AMERICA AND EAST AUSTRALIA</option>
		<option value='16' <?php if(!in_array('16', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[P] EC OF AUSTRALIA TO NORTH EAST ASIA TO WC OF NORTH AMERICA</option>
		<option value='AG' <?php if(!in_array('AG', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[AG] AFRICA TO MEDITERRANEAN SEA, BLACK SEA, BALTIC SEA TO ARABIAN GULF</option>
		<option value='AS' <?php if(!in_array('AS', $zones)) echo "style='color:#909090' "; else { echo "class='blackzone'"; } ?>>[AS] INDIA TO ASIA TO AUSTRALIA</option>
        <!-- END OTHERS -->
        <?php } ?>
	</select>
    
	<?php

	exit();
}

//zoilo get contact owner
$owneretc = trim($_GET['owner']);

function printShips($value, $t){
	$vars = @get_object_vars($value);

	foreach($vars as $k=>$v){
		if(is_array($v)){
			echo '<tr>
				<td style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000; padding:5px 0px; width:80px;"><b>IMO</b></td>
				<td style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000; padding:5px 0px;"><b>NAME</b></td>
				<td style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000; padding:5px 0px; width:200px;"><b>VESSEL TYPE</b></td>
				<td style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000; padding:5px 0px; width:150px;"><b>GROSS TONNAGE</b></td>
				<td style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000; padding:5px 0px; width:80px;"><b>BUILD</b></td>
				<td style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000; padding:5px 0px; width:30px;"><b>FLAG</b></td>
			</tr>';
			
			for($i=0; $i<$t; $i++){
				$imo = $v[$i]->IMO;
				
				$sql = "select * from `_xvas_shipdata` where `imo`='".mysql_escape_string($imo)."'";
				$ship = dbQuery($sql, $link);
				$ship = $ship[0];	
		
				$data = $ship['data'];
		
				if(!trim($data)){
					$sql = "select * from `_xvas_shipdata_dry` where `imo`='".mysql_escape_string($imo)."'";
					$ship = dbQuery($sql, $link);
					$ship = $ship[0];		
		
					$data = $ship['data'];
					
					if(!trim($data)){
						$sql = "select * from `_xvas_shipdata_container` where `imo`='".mysql_escape_string($imo)."'";
						$ship = dbQuery($sql, $link);
						$ship = $ship[0];		
			
						$data = $ship['data'];
						
						if(!trim($data)){
							$sql = "select * from `_xvas_shipdata_osv` where `imo`='".mysql_escape_string($imo)."'";
							$ship = dbQuery($sql, $link);
							$ship = $ship[0];		
				
							$data = $ship['data'];
							
							if(!trim($data)){
								$sql = "select * from `_xvas_shipdata_gas` where `imo`='".mysql_escape_string($imo)."'";
								$ship = dbQuery($sql, $link);
								$ship = $ship[0];		
					
								$data = $ship['data'];
								
								if(!trim($data)){
									$sql = "select * from `_xvas_shipdata_passenger` where `imo`='".mysql_escape_string($imo)."'";
									$ship = dbQuery($sql, $link);
									$ship = $ship[0];		
						
									$data = $ship['data'];
									
									if(!trim($data)){
										$sql = "select * from `_xvas_shipdata_others` where `imo`='".mysql_escape_string($imo)."'";
										$ship = dbQuery($sql, $link);
										$ship = $ship[0];		
							
										$data = $ship['data'];
									}
								}
							}
						}
					}
				}
		
				$data = str_replace("_#", "_NUM", $data);
				$data = str_replace("LENGTH_B/W_PERPENDICULARS", "LENGTH_B_W_PERPENDICULARS", $data);
				$data = str_replace("GRAIN/LIQUID_CAPACITY", "GRAIN_LIQUID_CAPACITY", $data);
				$data = str_replace("LIQUID/OIL", "LIQUID_OIL", $data);
				$data = cleanXML($data);
		
				$rdata = parseXML($data);
		
				$str = serialize($rdata);
				$str = str_replace('O:16:"SimpleXMLElement', 'O:8:"stdClass', $str);
		
				$shipd= unserialize($str);
				
				echo '<tr>
					<td style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000; padding:5px 0px;">'.$shipd->MAIN_DATA->IMO_NUMBER.'</td>
					<td style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000; padding:5px 0px;">'.$shipd->MAIN_DATA->NAME.'</td>
					<td style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000; padding:5px 0px;">'.$shipd->MAIN_DATA->VESSEL_TYPE.'</td>
					<td style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000; padding:5px 0px;">'.$shipd->MAIN_DATA->GROSS_TONNAGE.'</td>
					<td style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000; padding:5px 0px;">'.$shipd->MAIN_DATA->BUILD.'</td>';
					
					$flag = $v[$i]->FLAG;
					$img  = getFlagImage($flag);							

					if(strpos(trim($img),".png")>0){
						echo "<td style='font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000; padding:5px 0px;'><img alt=\"".htmlentities($flag)."\" title=\"".htmlentities($flag)."\" src='".$img."' ></td>";
					}else{
						echo "<td style='font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000; padding:5px 0px;'>&nbsp;</td>";
					}
					
				echo '</tr>';
			}
		}
	}
}

if($owneretc!=""){
	$owneretc = urldecode($owneretc);

	$sql = "select `imo` from `_xvas_parsed2` where `owner`='".mysql_escape_string($owneretc)."'";
	$owner = dbQuery($sql, $link);

	$sql = "select `imo` from `_xvas_parsed2` where `manager_owner`='".mysql_escape_string($owneretc)."'";
	$manager_owner = dbQuery($sql, $link);	

	$sql = "select `imo` from `_xvas_parsed2` where `manager`='".mysql_escape_string($owneretc)."'";
	$manager = dbQuery($sql, $link);

	$sql = "select `imo` from `_xvas_parsed2` where `builder`='".mysql_escape_string($owneretc)."'";
	$builder = dbQuery($sql, $link);	

	$sql = "select `imo` from `_xvas_parsed2_dry` where `owner`='".mysql_escape_string($owneretc)."'";
	$owner_dry = dbQuery($sql, $link);

	$sql = "select `imo` from `_xvas_parsed2_dry` where `manager_owner`='".mysql_escape_string($owneretc)."'";
	$manager_owner_dry = dbQuery($sql, $link);	

	$sql = "select `imo` from `_xvas_parsed2_dry` where `manager`='".mysql_escape_string($owneretc)."'";
	$manager_dry = dbQuery($sql, $link);

	$sql = "select `imo` from `_xvas_parsed2_dry` where `builder`='".mysql_escape_string($owneretc)."'";
	$builder_dry = dbQuery($sql, $link);
	
	$sql = "select `imo` from `_xvas_parsed2_container` where `owner`='".mysql_escape_string($owneretc)."'";
	$owner_container = dbQuery($sql, $link);

	$sql = "select `imo` from `_xvas_parsed2_container` where `manager_owner`='".mysql_escape_string($owneretc)."'";
	$manager_owner_container = dbQuery($sql, $link);	

	$sql = "select `imo` from `_xvas_parsed2_container` where `manager`='".mysql_escape_string($owneretc)."'";
	$manager_container = dbQuery($sql, $link);

	$sql = "select `imo` from `_xvas_parsed2_container` where `builder`='".mysql_escape_string($owneretc)."'";
	$builder_container = dbQuery($sql, $link);
	
	$sql = "select `imo` from `_xvas_parsed2_osv` where `owner`='".mysql_escape_string($owneretc)."'";
	$owner_osv = dbQuery($sql, $link);

	$sql = "select `imo` from `_xvas_parsed2_osv` where `manager_owner`='".mysql_escape_string($owneretc)."'";
	$manager_owner_osv = dbQuery($sql, $link);	

	$sql = "select `imo` from `_xvas_parsed2_osv` where `manager`='".mysql_escape_string($owneretc)."'";
	$manager_osv = dbQuery($sql, $link);

	$sql = "select `imo` from `_xvas_parsed2_osv` where `builder`='".mysql_escape_string($owneretc)."'";
	$builder_osv = dbQuery($sql, $link);
	
	$sql = "select `imo` from `_xvas_parsed2_gas` where `owner`='".mysql_escape_string($owneretc)."'";
	$owner_gas = dbQuery($sql, $link);

	$sql = "select `imo` from `_xvas_parsed2_gas` where `manager_owner`='".mysql_escape_string($owneretc)."'";
	$manager_owner_gas = dbQuery($sql, $link);	

	$sql = "select `imo` from `_xvas_parsed2_gas` where `manager`='".mysql_escape_string($owneretc)."'";
	$manager_gas = dbQuery($sql, $link);

	$sql = "select `imo` from `_xvas_parsed2_gas` where `builder`='".mysql_escape_string($owneretc)."'";
	$builder_gas = dbQuery($sql, $link);
	
	$sql = "select `imo` from `_xvas_parsed2_passenger` where `owner`='".mysql_escape_string($owneretc)."'";
	$owner_passenger = dbQuery($sql, $link);

	$sql = "select `imo` from `_xvas_parsed2_passenger` where `manager_owner`='".mysql_escape_string($owneretc)."'";
	$manager_owner_passenger = dbQuery($sql, $link);	

	$sql = "select `imo` from `_xvas_parsed2_passenger` where `manager`='".mysql_escape_string($owneretc)."'";
	$manager_passenger = dbQuery($sql, $link);

	$sql = "select `imo` from `_xvas_parsed2_passenger` where `builder`='".mysql_escape_string($owneretc)."'";
	$builder_passenger = dbQuery($sql, $link);
	
	$sql = "select `imo` from `_xvas_parsed2_others` where `owner`='".mysql_escape_string($owneretc)."'";
	$owner_others = dbQuery($sql, $link);

	$sql = "select `imo` from `_xvas_parsed2_others` where `manager_owner`='".mysql_escape_string($owneretc)."'";
	$manager_owner_others = dbQuery($sql, $link);	

	$sql = "select `imo` from `_xvas_parsed2_others` where `manager`='".mysql_escape_string($owneretc)."'";
	$manager_others = dbQuery($sql, $link);

	$sql = "select `imo` from `_xvas_parsed2_others` where `builder`='".mysql_escape_string($owneretc)."'";
	$builder_others = dbQuery($sql, $link);
	
	$owner = array_merge($owner, $owner_dry, $owner_container, $owner_osv, $owner_gas, $owner_passenger, $owner_others);
	$owner = array_values($owner);

	$manager_owner = array_merge($manager_owner, $manager_owner_dry, $manager_owner_container, $manager_owner_osv, $manager_owner_gas, $manager_owner_passenger, $manager_owner_others);
	$manager_owner = array_values($manager_owner);

	$manager = array_merge($manager, $manager_dry, $manager_container, $manager_osv, $manager_gas, $manager_passenger, $manager_others);
	$manager = array_values($manager);

	$builder = array_merge($builder, $builder_dry, $builder_container, $builder_osv, $builder_gas, $builder_passenger, $builder_others);
	$builder = array_values($builder);

	//get details
	if(trim($oetc_loc)==""&&$t=count($owner)){
		for($i=0; $i<$t; $i++){
			$imo = $owner[$i]['imo'];

			$sql = "select * from `_xvas_shipdata` where `imo`='".mysql_escape_string($imo)."'";
			$ship = dbQuery($sql, $link);		
			$ship = $ship[0];

			if(!trim($ship['data'])){
				$sql = "select * from `_xvas_shipdata_dry` where `imo`='".mysql_escape_string($imo)."'";
				$ship = dbQuery($sql, $link);
				$ship = $ship[0];
				
				if(!trim($ship['data'])){
					$sql = "select * from `_xvas_shipdata_container` where `imo`='".mysql_escape_string($imo)."'";
					$ship = dbQuery($sql, $link);
					$ship = $ship[0];
					
					if(!trim($ship['data'])){
						$sql = "select * from `_xvas_shipdata_osv` where `imo`='".mysql_escape_string($imo)."'";
						$ship = dbQuery($sql, $link);
						$ship = $ship[0];
						
						if(!trim($ship['data'])){
							$sql = "select * from `_xvas_shipdata_gas` where `imo`='".mysql_escape_string($imo)."'";
							$ship = dbQuery($sql, $link);
							$ship = $ship[0];
							
							if(!trim($ship['data'])){
								$sql = "select * from `_xvas_shipdata_passenger` where `imo`='".mysql_escape_string($imo)."'";
								$ship = dbQuery($sql, $link);
								$ship = $ship[0];
								
								if(!trim($ship['data'])){
									$sql = "select * from `_xvas_shipdata_others` where `imo`='".mysql_escape_string($imo)."'";
									$ship = dbQuery($sql, $link);
									$ship = $ship[0];
								}
							}
						}
					}
				}
			}

			$data = $ship['data'];

			$oetc_loc = getValue($data, "OWNER_LOCATION");
			$oetc_town = getValue($data, "OWNER_TOWN");
			$oetc_country = getValue($data, "OWNER_COUNTRY");
			$oetc_email = getValue($data, "OWNER_EMAIL");
			$oetc_website = getValue($data, "OWNER_WEBSITE");	

			if(trim($oetc_loc)!=""){
				break;
			}
		}
	}

	if(trim($oetc_loc)==""&&$t=count($manager_owner)){
		for($i=0; $i<$t; $i++){
			$imo = $manager_owner[$i]['imo'];

			$sql = "select * from `_xvas_shipdata` where `imo`='".mysql_escape_string($imo)."'";
			$ship = dbQuery($sql, $link);		
			$ship = $ship[0];

			if(!trim($ship['data'])){
				$sql = "select * from `_xvas_shipdata_dry` where `imo`='".mysql_escape_string($imo)."'";
				$ship = dbQuery($sql, $link);
				$ship = $ship[0];
				
				if(!trim($ship['data'])){
					$sql = "select * from `_xvas_shipdata_container` where `imo`='".mysql_escape_string($imo)."'";
					$ship = dbQuery($sql, $link);
					$ship = $ship[0];
					
					if(!trim($ship['data'])){
						$sql = "select * from `_xvas_shipdata_osv` where `imo`='".mysql_escape_string($imo)."'";
						$ship = dbQuery($sql, $link);
						$ship = $ship[0];
						
						if(!trim($ship['data'])){
							$sql = "select * from `_xvas_shipdata_gas` where `imo`='".mysql_escape_string($imo)."'";
							$ship = dbQuery($sql, $link);
							$ship = $ship[0];
							
							if(!trim($ship['data'])){
								$sql = "select * from `_xvas_shipdata_passenger` where `imo`='".mysql_escape_string($imo)."'";
								$ship = dbQuery($sql, $link);
								$ship = $ship[0];
								
								if(!trim($ship['data'])){
									$sql = "select * from `_xvas_shipdata_others` where `imo`='".mysql_escape_string($imo)."'";
									$ship = dbQuery($sql, $link);
									$ship = $ship[0];
								}
							}
						}
					}
				}
			}

			$data = $ship['data'];

			$oetc_loc = getValue($data, "MANAGER_OWNER_LOCATION");
			$oetc_town = getValue($data, "MANAGER_OWNER_TOWN");
			$oetc_country = getValue($data, "MANAGER_OWNER_COUNTRY");
			$oetc_email = getValue($data, "MANAGER_OWNER_EMAIL");
			$oetc_website = getValue($data, "MANAGER_OWNER_WEBSITE");	

			if(trim($oetc_loc)!=""){
				break;
			}		
		}
	}

	if(trim($oetc_loc)==""&&$t=count($manager)){
		for($i=0; $i<$t; $i++){
			$imo = $manager[$i]['imo'];

			$sql = "select * from `_xvas_shipdata` where `imo`='".mysql_escape_string($imo)."'";
			$ship = dbQuery($sql, $link);
			$ship = $ship[0];

			if(!trim($ship['data'])){
				$sql = "select * from `_xvas_shipdata_dry` where `imo`='".mysql_escape_string($imo)."'";
				$ship = dbQuery($sql, $link);
				$ship = $ship[0];
				
				if(!trim($ship['data'])){
					$sql = "select * from `_xvas_shipdata_container` where `imo`='".mysql_escape_string($imo)."'";
					$ship = dbQuery($sql, $link);
					$ship = $ship[0];
					
					if(!trim($ship['data'])){
						$sql = "select * from `_xvas_shipdata_osv` where `imo`='".mysql_escape_string($imo)."'";
						$ship = dbQuery($sql, $link);
						$ship = $ship[0];
						
						if(!trim($ship['data'])){
							$sql = "select * from `_xvas_shipdata_gas` where `imo`='".mysql_escape_string($imo)."'";
							$ship = dbQuery($sql, $link);
							$ship = $ship[0];
							
							if(!trim($ship['data'])){
								$sql = "select * from `_xvas_shipdata_passenger` where `imo`='".mysql_escape_string($imo)."'";
								$ship = dbQuery($sql, $link);
								$ship = $ship[0];
								
								if(!trim($ship['data'])){
									$sql = "select * from `_xvas_shipdata_others` where `imo`='".mysql_escape_string($imo)."'";
									$ship = dbQuery($sql, $link);
									$ship = $ship[0];
								}
							}
						}
					}
				}
			}

			$data = $ship['data'];
			
			$oetc_loc = getValue($data, "MANAGER_LOCATION");
			$oetc_town = getValue($data, "MANAGER_TOWN");
			$oetc_country = getValue($data, "MANAGER_COUNTRY");
			$oetc_email = getValue($data, "MANAGER_EMAIL");
			$oetc_website = getValue($data, "MANAGER_WEBSITE");	

			if(trim($oetc_loc)!=""){
				break;
			}
		}
	}
	
	if(trim($oetc_loc)==""&&$t=count($builder)){
		for($i=0; $i<$t; $i++){
			$imo = $builder[$i]['imo'];

			$sql = "select * from `_xvas_shipdata` where `imo`='".mysql_escape_string($imo)."'";
			$ship = dbQuery($sql, $link);
			$ship = $ship[0];

			if(!trim($ship['data'])){
				$sql = "select * from `_xvas_shipdata_dry` where `imo`='".mysql_escape_string($imo)."'";
				$ship = dbQuery($sql, $link);
				$ship = $ship[0];
				
				if(!trim($ship['data'])){
					$sql = "select * from `_xvas_shipdata_container` where `imo`='".mysql_escape_string($imo)."'";
					$ship = dbQuery($sql, $link);
					$ship = $ship[0];
					
					if(!trim($ship['data'])){
						$sql = "select * from `_xvas_shipdata_osv` where `imo`='".mysql_escape_string($imo)."'";
						$ship = dbQuery($sql, $link);
						$ship = $ship[0];
						
						if(!trim($ship['data'])){
							$sql = "select * from `_xvas_shipdata_gas` where `imo`='".mysql_escape_string($imo)."'";
							$ship = dbQuery($sql, $link);
							$ship = $ship[0];
							
							if(!trim($ship['data'])){
								$sql = "select * from `_xvas_shipdata_passenger` where `imo`='".mysql_escape_string($imo)."'";
								$ship = dbQuery($sql, $link);
								$ship = $ship[0];
								
								if(!trim($ship['data'])){
									$sql = "select * from `_xvas_shipdata_others` where `imo`='".mysql_escape_string($imo)."'";
									$ship = dbQuery($sql, $link);
									$ship = $ship[0];
								}
							}
						}
					}
				}
			}

			$data = $ship['data'];

			$oetc_loc = getValue($data, "BUILDER_LOCATION");
			$oetc_town = getValue($data, "BUILDER_TOWN");
			$oetc_country = getValue($data, "BUILDER_COUNTRY");
			$oetc_email = getValue($data, "BUILDER_EMAIL");
			$oetc_website = getValue($data, "BUILDER_WEBSITE");	

			if(trim($oetc_loc)!=""){
				break;
			}
		}
	}
	?>

	<style>
	#owneretc{
		width:100%;
	}

	#owneretc td{
		font-family:Verdana, Arial, Helvetica, sans-serif;
		font-size:11px;
		padding-right:10px;
	}

	#owneretc .tcellhead{
		font-weight:bold;
	}

	#owneretc .thead{
		font-weight:bold;
		background:#999999;
		color:white;
		margin-bottom:5px;
		padding:5px;
	}

	a:link, a:hover, a:visited{
		color:#3997D9;
	}
	</style>

	<?php
	echo "<table id='owneretc'>";
		echo "<tr>
			<td colspan=6>";
				echo "<table width='100%'>
					<tr>";
						echo "<td width='50%'  valign='top'>";
							echo "<a style='font-size:15px'><b>".$owneretc."</b></a><br>";
							echo $oetc_loc."<br>";
							echo $oetc_town;
			
							if(trim($oetc_town)){
								echo "-";
							}
							
							echo $oetc_country;
	
						echo "</td>";
						echo "<td width='50%' align='right' valign='top'>";
	
							if(trim($oetc_email)){
								echo "<a href='mailto:$oetc_email'  target='_blank'><img title='E-mail' alt='E-mail' style='border:0px' src='images/email.jpg'></a>";
							}
	
							if(trim($oetc_website)){
								if(strpos($oetc_website, "http://")===false){
									$oetc_website = "http://".$oetc_website;
								}
					
								echo "<a href='$oetc_website' target='_blank'><img title='Website' alt='Website' style='border:0px' src='images/www.jpg'></a>";
							}
	
						echo "</td>";
					echo "</tr>
				</table>";
			echo "</td>
		</tr>
	</table>";

	if($_GET['owner_id']==0){
		$r = $owner;
		$t = count($r);
		if($t){	
			echo '<table width="100%" border="0" cellspacing="0" cellpadding="0">';
				echo "<tr>
					<td colspan='6' style='background-color:#999999; font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#FFF;'><div style='padding:5px;'><b>AS OWNER ";
					echo ($t==1)?"($t ship)":"($t ships)";
					echo "</b></div></td>
				</tr>";
			echo '</table>';
	
			printShipCell($r, $t);
		}
		
		$r = $manager_owner;
		$t = count($r);
		if($t){
			echo '<table width="100%" border="0" cellspacing="0" cellpadding="0">';
				echo "<tr>
					<td colspan='6' style='background-color:#999999; font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#FFF;'><div style='padding:5px;'><b>AS MANAGER / OWNER ";
					echo ($t==1)?"($t ship)":"($t ships)";
					echo "</b></div></td>
				</tr>";
			echo '</table>';
			
			printShipCell($r, $t);
		}	
		
		$r = $manager;
		$t = count($r);
		if($t){
			echo '<table width="100%" border="0" cellspacing="0" cellpadding="0">';
				echo "<tr>
					<td colspan='6' style='background-color:#999999; font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#FFF;'><div style='padding:5px;'><b>AS MANAGER ";
					echo ($t==1)?"($t ship)":"($t ships)";
					echo "</b></div></td>
				</tr>";
			echo '</table>';
			
			printShipCell($r, $t);
		}	
			
		$r = $builder;
		$t = count($r);
		if($t){
			echo '<table width="100%" border="0" cellspacing="0" cellpadding="0">';
				echo "<tr>
					<td colspan='6' style='background-color:#999999; font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#FFF;'><div style='padding:5px;'><b>AS BUILDER ";
					echo ($t==1)?"($t ship)":"($t ships)";
					echo "</b></div></td>
				</tr>";
			echo '</table>';
			
			printShipCell($r, $t);
		}
	}else{
		$vars = array("id"=>$_GET['owner_id'],"mode"=>"SUBJECT");
		$snoopy = new Snoopy();
		
		$snoopy->httpmethod = "GET";
		$snoopy->submit("http://dataservice.grosstonnage.com/S-Bis.php", $vars);
		
		$values = $snoopy->results;
		
		$rdata = parseXML($values);
		
		foreach($rdata as $key=>$value){
			if($key=='MANAGED_VESSELS'){
				$t_managed = count($value);
				
				echo '<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td colspan="6" style="background-color:#999999; font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#FFF;"><div style="padding:5px;"><b>AS MANAGER / OWNER ('.$t_managed.' ships)</b></div></td>
					</tr>';
					
					printShips($value, $t_managed);
					
				echo '</table>';
			}else if($key=='OWNED_VESSELS'){
				$t_owned = count($value);
				
				echo '<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td colspan="6" style="background-color:#999999; font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#FFF;"><div style="padding:5px;"><b>AS OWNER ('.$t_owned.' ships)</b></div></td>
					</tr>';
					
					printShips($value, $t_owned);
					
				echo '</table>';
			}
		}
	}

	exit();
}
//zoilo update ship data
else if($_GET['imo']){
	$sql = "select * from `_xvas_shipdata` where `imo`='".mysql_escape_string($_GET['imo'])."'";
	$ship = dbQuery($sql, $link);
	$ship = $ship[0];
	
	if(!trim($ship)){
		$sql = "select * from `_xvas_shipdata_dry` where `imo`='".mysql_escape_string($_GET['imo'])."'";
		$ship = dbQuery($sql, $link);
		$ship = $ship[0];
		
		if(!trim($ship)){
			$sql = "select * from `_xvas_shipdata_container` where `imo`='".mysql_escape_string($_GET['imo'])."'";
			$ship = dbQuery($sql, $link);
			$ship = $ship[0];
			
			if(!trim($ship)){
				$sql = "select * from `_xvas_shipdata_osv` where `imo`='".mysql_escape_string($_GET['imo'])."'";
				$ship = dbQuery($sql, $link);
				$ship = $ship[0];
				
				if(!trim($ship)){
					$sql = "select * from `_xvas_shipdata_gas` where `imo`='".mysql_escape_string($_GET['imo'])."'";
					$ship = dbQuery($sql, $link);
					$ship = $ship[0];
					
					if(!trim($ship)){
						$sql = "select * from `_xvas_shipdata_passenger` where `imo`='".mysql_escape_string($_GET['imo'])."'";
						$ship = dbQuery($sql, $link);
						$ship = $ship[0];
						
						if(!trim($ship)){
							$sql = "select * from `_xvas_shipdata_others` where `imo`='".mysql_escape_string($_GET['imo'])."'";
							$ship = dbQuery($sql, $link);
							$ship = $ship[0];
					
							updateShipData($ship, "_xvas_shipdata_others");
						}else{
							updateShipData($ship, "_xvas_shipdata_passenger");
						}
					}else{
						updateShipData($ship, "_xvas_shipdata_gas");
					}
				}else{
					updateShipData($ship, "_xvas_shipdata_osv");
				}
			}else{
				updateShipData($ship, "_xvas_shipdata_container");
			}
		}else{
			updateShipData($ship, "_xvas_shipdata_dry");
		}
	}else{
		updateShipData($ship, "_xvas_shipdata");
	}

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

$dc = new distanceCalc(); 

if(!$load_portid){
	echo "<b>ERROR</b>: Invalid Load Port. Please key in a valid Load Port.";

	exit();
}



//save tab
$tabid = $_GET['tabid'];

$tabdata = serialize($_GET);

$tabsys->updateTab("shipsearch", $tabid, $tabdata, $_GET['load_port']."<div style='font-size:9px;'>$lpff-$lptf</div>");

if(!$_GET['options']){
	if($user['dry']==2 || $user['dry']==3 || $user['dry']==4 || $user['dry']==5 || $user['dry']==6){
		$zone = "7";
	}
	else
	{
		$zone = $_GET['zone'];
		if($zone==""){
			$zone = $_GET['zone2'];
		}
	}
	$prefs['avoidInshore'] = true;
	$prefs['avoidRivers'] = true;
	$prefs['deepWaterFactor'] = 1;
	$prefs['minDepth'] = 0;
	$prefs['minHeight'] = 0;	

	//get ship specifics
	// match specs with HULL TYPE / CATEGORY / DWT RANGE
	$hull_type = trim(mysql_escape_string($_GET['hull_type']));

	if(!is_array($_GET['vessel_type'])){
		$vessel_type = trim(mysql_escape_string($_GET['vessel_type']));
	}else{
		$vessel_type = $_GET['vessel_type'];
	}

	$dwt_type = "";
	if($_GET['dwt_low']&&$_GET['dwt_high']){
		$dwt_low  = $_GET['dwt_low']*1;
		$dwt_high  = $_GET['dwt_high']*1;
		$dwt_type = "Others";
	}
	else if($_GET['dwt_range']){
		$dwtr = trim($_GET['dwt_range']);

		if($_GET['dry']){
			if($dwtr=="5|35"){
				$dwt_low = 5000;
				$dwt_high = 35000;
				$dwt_type = "Handysize";
			}else if($dwtr=="40|50"){
				$dwt_low = 40000;
				$dwt_high = 50000;	
				$dwt_type = "Handymax";
			}else if($dwtr=="50|60"){
				$dwt_low = 50000;
				$dwt_high = 60000;	
				$dwt_type = "Supramax";
			}else if($dwtr=="60|90"){
				$dwt_low = 60000;
				$dwt_high = 90000;	
				$dwt_type = "Panamax";
			}else if($dwtr=="90|120"){
				$dwt_low = 90000;
				$dwt_high = 120000;
				$dwt_type = "Post Panamax";
			}else if($dwtr=="120|350"){
				$dwt_low = 120000;
				$dwt_high = 350000;
				$dwt_type = "Capesize";
			}
		}
		else{
			if($dwtr=="0|3"){
				$dwt_low = 500;
				$dwt_high = 3000;
				$dwt_type = "Others";
			}else if($dwtr=="3|10"){
				$dwt_low = 3001;
				$dwt_high = 10000;	
				$dwt_type = "Coastal";
			}else if($dwtr=="10|19"){
				$dwt_low = 10001;
				$dwt_high = 19000;	
				$dwt_type = "Small";
			}else if($dwtr=="19|30"){
				$dwt_low = 19001;
				$dwt_high = 30000;	
				$dwt_type = "Handy";
			}else if($dwtr=="30|55"){
				$dwt_low = 30001;
				$dwt_high = 55000;
				$dwt_type = "MR";
			}else if($dwtr=="50|80"){
				$dwt_low = 50001;
				$dwt_high = 80000;	
				$dwt_type = "Panamax / LR1";	
			}else if($dwtr=="80|120"){
				$dwt_low = 80001;
				$dwt_high = 120000;
				$dwt_type = "Aframax";
			}else if($dwtr=="120|200"){
				$dwt_low = 120001;
				$dwt_high = 200000;
				$dwt_type = "Suezmax";
			}else if($dwtr=="200|350"){
				$dwt_low = 200001;
				$dwt_high = 350000;	
				$dwt_type = "VLCC / VLOC";
			}else if($dwtr=="350|550"){
				$dwt_low = 350001;
				$dwt_high = 550000;	
				$dwt_type = "ULCC";
			}
		}
		if($dwtr&&$dwt_low==""){
			list($dwt_low, $dwt_high) = explode("|",$dwtr);
			$dwt_low *= 1000;
			$dwt_low += 1;
			$dwt_high *= 1000;
			$dwt_type = "Others";
		}
	}
	else{
		$dwt_low  = 50000;
		$dwt_high  = 60000;
		$dwt_type = "Others";
	}
}

$sqlext = "";

if($user['dry']==1){
	$_xvas_parsed2 = "_xvas_parsed2_dry";
}elseif($user['dry']==2){
	$_xvas_parsed2 = "_xvas_parsed2_container";
}elseif($user['dry']==3){
	$_xvas_parsed2 = "_xvas_parsed2_osv";
}elseif($user['dry']==4){
	$_xvas_parsed2 = "_xvas_parsed2_gas";
}elseif($user['dry']==5){
	$_xvas_parsed2 = "_xvas_parsed2_passenger";
}elseif($user['dry']==6){
	$_xvas_parsed2 = "_xvas_parsed2_others";
}else{
	$_xvas_parsed2 = "_xvas_parsed2";
}

if($user['dry']==2 || $user['dry']==3 || $user['dry']==4 || $user['dry']==5 || $user['dry']==6){
	$sqlext .= " and `xvas_hull_type`='SINGLE HULL' and ";
	$sqlext2 .= " and `hull_type`='SINGLE HULL' and ";
	$sqlext3 .= " and ".$_xvas_parsed2.".hull_type='SINGLE HULL' and ";
	
	if($vessel_type){
		$vtarr = count($vessel_type);
	
		if($vtarr){
			$sqlext .= " ( ";
			$sqlext2 .= " ( ";
			$sqlext3 .= " ( ";
		}
		
		for($vti=0; $vti<$vtarr; $vti++){
			$sqlext .= " `xvas_vessel_type`='".$vessel_type[$vti]."' ";
			$sqlext2 .= " `vessel_type`='".$vessel_type[$vti]."' ";
			$sqlext3 .= " ".$_xvas_parsed2.".vessel_type='".$vessel_type[$vti]."' ";
	
			if(($vti+1)<$vtarr){
				$sqlext .= " or ";
				$sqlext2 .= " or ";	
				$sqlext3 .= " or ";	
			}
		}
		
		if($vtarr){
			$sqlext .= " ) ";
			$sqlext2 .= " ) ";
			$sqlext3 .= " ) ";
		}
	}
	if($dwt_low){
		$sqlext .= " `xvas_summer_dwt`>='".$dwt_low."'  and ";
		$sqlext2 .= " `summer_dwt`>='".$dwt_low."'  and ";
		$sqlext3 .= " ".$_xvas_parsed2.".summer_dwt>='".$dwt_low."'  and ";
	}
	
	if($dwt_high){
		$sqlext .= " `xvas_summer_dwt`<='".$dwt_high."' and "; 
		$sqlext2 .= " `summer_dwt`<='".$dwt_high."' and "; 
		$sqlext3 .= " ".$_xvas_parsed2.".summer_dwt<='".$dwt_high."' and "; 
	}
	
	if($sqlext){
		$sqlext = " and ( ".$sqlext." 1 )";
	}
	
	if($sqlext2){
		$sqlext2 = " and ( ".$sqlext2." 1 )";
	}
	
	if($sqlext3){
		$sqlext3 = " and ( ".$sqlext3." 1 )";
	}
}
else
{
	if($hull_type=="DOUBLE HULL"){
		$sqlext .= " (`xvas_hull_type`='DOUBLE HULL' or `xvas_hull_type`='DOUBLE BOTTOM' or `xvas_hull_type`='DOUBLE SIDED')  and ";
	}else if($hull_type=="SINGLE HULL"){
		$sqlext .= " `xvas_hull_type`='SINGLE HULL'  and ";
		$sqlext2 .= " `hull_type`='SINGLE HULL'  and ";
		$sqlext3 .= " ".$_xvas_parsed2.".hull_type='SINGLE HULL'  and ";
	}
	
	if($vessel_type){
		$vtarr = count($vessel_type);
	
		if($vtarr){
			$sqlext .= " ( ";
			$sqlext2 .= " ( ";
			$sqlext3 .= " ( ";
		}
		
		for($vti=0; $vti<$vtarr; $vti++){
			$sqlext .= " `xvas_vessel_type`='".$vessel_type[$vti]."' ";
			$sqlext2 .= " `vessel_type`='".$vessel_type[$vti]."' ";
			$sqlext3 .= " ".$_xvas_parsed2.".vessel_type='".$vessel_type[$vti]."' ";
	
			if(($vti+1)<$vtarr){
				$sqlext .= " or ";
				$sqlext2 .= " or ";	
				$sqlext3 .= " or ";	
			}
		}
		
		if($vtarr){
			$sqlext .= " ) ";
			$sqlext2 .= " ) ";
			$sqlext3 .= " ) ";
		}
		
		$sqlext .= " and ";
		$sqlext2 .= " and ";
		$sqlext3 .= " and ";
	}
	
	if($dwt_low){
		$sqlext .= " `xvas_summer_dwt`>='".$dwt_low."'  and ";
		$sqlext2 .= " `summer_dwt`>='".$dwt_low."'  and ";
		$sqlext3 .= " ".$_xvas_parsed2.".summer_dwt>='".$dwt_low."'  and ";
	}
	
	if($dwt_high){
		$sqlext .= " `xvas_summer_dwt`<='".$dwt_high."' and "; 
		$sqlext2 .= " `summer_dwt`<='".$dwt_high."' and "; 
		$sqlext3 .= " ".$_xvas_parsed2.".summer_dwt<='".$dwt_high."' and "; 
	}
	
	if($sqlext){
		$sqlext = " and ( ".$sqlext." 1 )";
	}
	
	if($sqlext2){
		$sqlext2 = " and ( ".$sqlext2." 1 )";
	}
	
	if($sqlext3){
		$sqlext3 = " and ( ".$sqlext3." 1 )";
	}
}

global $adjusthours;

$sql = "select 

`id`,
`siitech_receivetime`,
`siitech_destination`,
`qc_color`,
`siitech_eta`,
`xvas_imo`,


(unix_timestamp(siitech_eta)+$adjusthours) as `siitech_eta_ts`,
(unix_timestamp(siitech_lastseen)+$adjusthours) as `siitech_lastseen_ts`, 
(unix_timestamp(siitech_receivetime)+$adjusthours) as `siitech_receivetime_ts`

 from `_xvas_siitech_cache` where `qc_color`='green' ";

if($sqlext){
	$sql .= " ".$sqlext;
}

$shipssql =  $sql."";

//echo $shipssql;
//exit();

$logfile = dirname(__FILE__)."/includes/searchcache/logs/".date("Ymd")."_".microtime_float().".txt";
$logfile = dirname(__FILE__)."/includes/searchcache/logs/log.txt";
file_put_contents($logfile, "");

if($user['dry']==1){
	//network ships
	$sql2 = "select * from `_xvas_parsed2_dry` where 1 ";

	$userid = $_SESSION['user']['id'];

	$sqlext2 .= " and `imo` in (

		select `imo` from `_messages` where `type`='network' and 

		`user_email` in ( 

			select `email` from `_sbis_users` where 

			`id` in (

				select `userid1` from _network where (`userid1` = '".$userid."' or `userid2` = '".$userid."')

			) or

			`id` in (

				select `userid2` from _network where (`userid1` = '".$userid."' or `userid2` = '".$userid."')

			)

		)

		and `imo` not in (

			select distinct `xvas_imo` from `_xvas_siitech_cache`

		)

	)";
}elseif($user['dry']==2){
	//network ships
	$sql2 = "select * from `_xvas_parsed2_container` where 1 ";

	$userid = $_SESSION['user']['id'];

	$sqlext2 .= " and `imo` in (

		select `imo` from `_messages` where `type`='network' and 

		`user_email` in ( 

			select `email` from `_sbis_users` where 

			`id` in (

				select `userid1` from _network where (`userid1` = '".$userid."' or `userid2` = '".$userid."')

			) or

			`id` in (

				select `userid2` from _network where (`userid1` = '".$userid."' or `userid2` = '".$userid."')

			)

		)

		and `imo` not in (

			select distinct `xvas_imo` from `_xvas_siitech_cache`

		)

	)";
}elseif($user['dry']==3){
	//network ships
	$sql2 = "select * from `_xvas_parsed2_osv` where 1 ";

	$userid = $_SESSION['user']['id'];

	$sqlext2 .= " and `imo` in (

		select `imo` from `_messages` where `type`='network' and 

		`user_email` in ( 

			select `email` from `_sbis_users` where 

			`id` in (

				select `userid1` from _network where (`userid1` = '".$userid."' or `userid2` = '".$userid."')

			) or

			`id` in (

				select `userid2` from _network where (`userid1` = '".$userid."' or `userid2` = '".$userid."')

			)

		)

		and `imo` not in (

			select distinct `xvas_imo` from `_xvas_siitech_cache`

		)

	)";
}elseif($user['dry']==4){
	//network ships
	$sql2 = "select * from `_xvas_parsed2_gas` where 1 ";

	$userid = $_SESSION['user']['id'];

	$sqlext2 .= " and `imo` in (

		select `imo` from `_messages` where `type`='network' and 

		`user_email` in ( 

			select `email` from `_sbis_users` where 

			`id` in (

				select `userid1` from _network where (`userid1` = '".$userid."' or `userid2` = '".$userid."')

			) or

			`id` in (

				select `userid2` from _network where (`userid1` = '".$userid."' or `userid2` = '".$userid."')

			)

		)

		and `imo` not in (

			select distinct `xvas_imo` from `_xvas_siitech_cache`

		)

	)";
}elseif($user['dry']==5){
	//network ships
	$sql2 = "select * from `_xvas_parsed2_passenger` where 1 ";

	$userid = $_SESSION['user']['id'];

	$sqlext2 .= " and `imo` in (

		select `imo` from `_messages` where `type`='network' and 

		`user_email` in ( 

			select `email` from `_sbis_users` where 

			`id` in (

				select `userid1` from _network where (`userid1` = '".$userid."' or `userid2` = '".$userid."')

			) or

			`id` in (

				select `userid2` from _network where (`userid1` = '".$userid."' or `userid2` = '".$userid."')

			)

		)

		and `imo` not in (

			select distinct `xvas_imo` from `_xvas_siitech_cache`

		)

	)";
}elseif($user['dry']==6){
	//network ships
	$sql2 = "select * from `_xvas_parsed2_others` where 1 ";

	$userid = $_SESSION['user']['id'];

	$sqlext2 .= " and `imo` in (

		select `imo` from `_messages` where `type`='network' and 

		`user_email` in ( 

			select `email` from `_sbis_users` where 

			`id` in (

				select `userid1` from _network where (`userid1` = '".$userid."' or `userid2` = '".$userid."')

			) or

			`id` in (

				select `userid2` from _network where (`userid1` = '".$userid."' or `userid2` = '".$userid."')

			)

		)

		and `imo` not in (

			select distinct `xvas_imo` from `_xvas_siitech_cache`

		)

	)";
}else{
	//network ships
	$sql2 = "select * from `_xvas_parsed2` where 1 ";

	$userid = $_SESSION['user']['id'];

	$sqlext2 .= " and `imo` in (

		select `imo` from `_messages` where `type`='network' and 

		`user_email` in ( 

			select `email` from `_sbis_users` where 

			`id` in (

				select `userid1` from _network where (`userid1` = '".$userid."' or `userid2` = '".$userid."')

			) or

			`id` in (

				select `userid2` from _network where (`userid1` = '".$userid."' or `userid2` = '".$userid."')

			)

		)

		and `imo` not in (

			select distinct `xvas_imo` from `_xvas_siitech_cache`

		)

	)";
}

if($sqlext2){ $sql2 .= " ".$sqlext2; }


//exit();


$shipsA1 = array();
$shipsA2 = array();

if($_GET['sbroker']){
	$shipsA3 = array();
	$shipsA4 = array();
	$shipsA5 = array();

	//ships from brokers intelligence
	file_put_contents($logfile, "retrieving ships from brokers intelligence ", FILE_APPEND);
	$shipsA5 = dbQuery($sql2, $link);
	$time_end = microtime_float();
	$time = $time_end - $time_start;
	file_put_contents($logfile, "($time)\n", FILE_APPEND);
}

$sql3 = "SELECT _blackbox_vessel.from_time, _blackbox_vessel.to_time, _blackbox_vessel.latest_received, _blackbox_vessel.location_name, _blackbox_vessel.location_lat, _blackbox_vessel.location_lng, _blackbox_vessel.vessel_name, _blackbox_vessel.from_address, ".$_xvas_parsed2.".imo, ".$_xvas_parsed2.".callsign, ".$_xvas_parsed2.".mmsi, ".$_xvas_parsed2.".vessel_type, ".$_xvas_parsed2.".summer_dwt, ".$_xvas_parsed2.".speed, _xvas_siitech_cache.siitech_eta, _xvas_siitech_cache.siitech_destination, _xvas_siitech_cache.siitech_lastseen, _xvas_siitech_cache.siitech_latitude, _xvas_siitech_cache.siitech_longitude, _xvas_siitech_cache.siitech_receivetime, _xvas_siitech_cache.siitech_shippos_data, _xvas_siitech_cache.siitech_shipstat_data FROM (`_blackbox_vessel` INNER JOIN `".$_xvas_parsed2."` ON _blackbox_vessel.vessel_name=".$_xvas_parsed2.".name) INNER JOIN _xvas_siitech_cache ON _blackbox_vessel.vessel_name=_xvas_siitech_cache.xvas_name WHERE 1 AND _blackbox_vessel.location_name='".strtoupper(trim($_GET['load_port']))."' AND _xvas_siitech_cache.satellite='0' ";

if($sqlext3){ $sql3 .= " ".$sqlext3; }

if($_GET['semail']){
	$shipsA8 = array();
	
	file_put_contents($logfile, "retrieving ships from email ", FILE_APPEND);
	$shipsA8 = dbQuery($sql3, $link);
	$time_end = microtime_float();
	$time = $time_end - $time_start;
	file_put_contents($logfile, "($time)\n", FILE_APPEND);
}

$imoin = array();
file_put_contents($logfile, "retrieving ships from database ", FILE_APPEND);
$ships = dbQuery($shipssql, $link);
$time_end = microtime_float();
$time = $time_end - $time_start;
file_put_contents($logfile, "($time)\n", FILE_APPEND);
$t = count($ships);
//second phase of algorithm
for($i=0; $i<$t; $i++){
	
	if(trim($ships[$i]['xvas_imo'])==""){
		continue;
	}

	$etadiff = floorTs(strtotime($ships[$i]['siitech_eta']))-floorTs(time()); 

	if($ships[$i]['qc_color']!="red"){
		$alerts = array();
		$destport = strtoupper(trim($ships[$i]['siitech_destination']));
		$destportx = $destport;
		$destport = getPortId($destport);
		$ships[$i]['destport'] = $destport;
		$portid = $destport['portid'];
		$percent = $destport['percent'];

		if(1&&(trim($ships[$i]['siitech_destination'])&&$etadiff>=0)){
			if($portid){
				$percent = number_format($percent, 2);

				if($percent<100){
					$alerts[] = "We matched destination port '".$ships[$i]['siitech_destination']."' to port '".$destport['name']."' a $percent% match based on the Name inputted by the ship.";
				}

				$twomonths = 60 * 24 * 60 * 60;

				if($etadiff>$twomonths){
					$alerts[] = "ETA to destination is greater than 2 Months"; 
				}

				$ships[$i]['destination_port'] = $portid;

				if(count($alerts)){
					$ships[$i]['alerts'] = $alerts;
				}
				
				$sql = "select * from `_xvas_siitech_cache` where `id`='".$ships[$i]['id']."'";
				$shiptemp = dbQuery($sql, $link);
				foreach($shiptemp as $ks=>$kv){
					$ships[$i][$ks] = $kv;
				}
				
				$shipsA1[] = $ships[$i];
			}else if(strtotime($ships[$i]['siitech_receivetime'])>0){
				$alerts[] = "Unknown port destination: $destportx";

				if(count($alerts)){
					$ships[$i]['alerts'] = $alerts;
				}
				
				$sql = "select * from `_xvas_siitech_cache` where `id`='".$ships[$i]['id']."'";
				$shiptemp = dbQuery($sql, $link);
				foreach($shiptemp as $ks=>$kv){
					$ships[$i][$ks] = $kv;
				}
				$shipsA2[] = $ships[$i];
			}
		}
		else if(1&&(strtotime($ships[$i]['siitech_receivetime'])>0)){
			$alerts[] = "Reported ETA to AIS Destination Port (".$ships[$i]['siitech_destination'].") that was dated to arrive on ".date("M j, 'y G:i e",  $ships[$i]['siitech_eta_ts'])." has passed. The ship has not updated its AIS location as of ".date("M j, 'y G:i e",  time()).". The Last Seen AIS Location (Lat & Long) is now used to calculate the ETA to the Load Port you have selected for this Search.";

			if(count($alerts)){
				$ships[$i]['alerts'] = $alerts;
			}
			
			$sql = "select * from `_xvas_siitech_cache` where `id`='".$ships[$i]['id']."'";
			$shiptemp = dbQuery($sql, $link);
			foreach($shiptemp as $ks=>$kv){
				$ships[$i][$ks] = $kv;
			}
			$shipsA2[] = $ships[$i];
		}

		if(1&&($_GET['includebrokermessages']||1)){
			//get message from network
			$nmessage = getMessageByImo($ships[$i]['xvas_imo'], 'network');

			$nmid = $nmessage['id'];

			$nmessage = unserialize($nmessage['message']);

			if($nmessage['openport']){
				$nmessage['openport'] = strtoupper($nmessage['openport']);
				$nmessage['opendate_ts'] = convertDateToTs($nmessage['opendate']);

				$ots = floorTs($ships[$i]['siitech_eta_ts']);

				$tsdiff = $ots-time(); 

				$destport = strtoupper(trim($nmessage['openport']));

				$destportx = $destport;

				$destport = getPortId($destport);

				$ships[$i]['destport2'] = $destport;
				$ships[$i]['nmessage'] = $nmessage;				

				if(($tsdiff)>=0){
					$sql = "select * from `_xvas_siitech_cache` where `id`='".$ships[$i]['id']."'";
					$shiptemp = dbQuery($sql, $link);
					foreach($shiptemp as $ks=>$kv){
						$ships[$i][$ks] = $kv;
					}
					$shipsA3[] = $ships[$i];
				}else{
					$sql = "select * from `_xvas_siitech_cache` where `id`='".$ships[$i]['id']."'";
					$shiptemp = dbQuery($sql, $link);
					foreach($shiptemp as $ks=>$kv){
						$ships[$i][$ks] = $kv;
					}
					$shipsA4[] = $ships[$i];
				}
			}
		}
	}
	if($i%1000==0&&$i!=0){
		$time_end = microtime_float();
		$time = $time_end - $time_start;
		file_put_contents($logfile, "".($i+1)." - ".($i+1000)." of ".$t." ($time)\n", FILE_APPEND);
	}
}

$time_end = microtime_float();
$time = $time_end - $time_start;
file_put_contents($logfile, "finished populating ship arrays ($time)\n", FILE_APPEND);

//add message to t5
$t5 = count($shipsA5);

for($i=0; $i<$t5; $i++){
	$nmessage = getMessageByImo($shipsA5[$i]['imo'], 'network');

	$nmid = $nmessage['id'];

	$nmessage = unserialize($nmessage['message']);

	if($nmessage['openport']){
		$nmessage['openport'] = strtoupper($nmessage['openport']);
		$nmessage['opendate_ts'] = convertDateToTs($nmessage['opendate']);

		$ots = floorTs($ships[$i]['siitech_eta_ts']);

		$tsdiff = $ots-time(); 

		$destport = strtoupper(trim($nmessage['openport']));

		$destportx = $destport;

		$destport = getPortId($destport);

		$shipsA5[$i]['destport2'] = $destport;
		$shipsA5[$i]['nmessage'] = $nmessage;
	}
}

$lpf = $_GET['load_port_from'];
$lpf = explode("/", $lpf);

$lpt = $_GET['load_port_to'];
$lpt = explode("/", $lpt);

$lpfts = convertDateToTs($_GET['load_port_from']);
$lptts = convertDateToTs($_GET['load_port_to']);

if($_GET['slimit']){
	$dlimit = $_GET['slimit']; //display limit
}else{
	$dlimit = 5000;
}

$shiplimit = 5000;

if($_GET['sshore']){
	//process shipsA1
	include_once(dirname(__FILE__)."/includes/shipsearch/shipsA1.php");

	//process shipsA2
	include_once(dirname(__FILE__)."/includes/shipsearch/shipsA2.php");
}

if($_GET['sbroker']){
	//process shipsA3
	include_once(dirname(__FILE__)."/includes/shipsearch/shipsA3.php");

	//process shipsA4
	include_once(dirname(__FILE__)."/includes/shipsearch/shipsA4.php");

	//process shipsA5
	include_once(dirname(__FILE__)."/includes/shipsearch/shipsA5.php");
}

if($_GET['semail']){
	//process shipsA8
	include_once(dirname(__FILE__)."/includes/shipsearch/shipsA8.php");
}

$t = count($shipsA1print);
$t2 = count($shipsA2print);	
$t3 = count($shipsA3print);	
$t4 = count($shipsA4print);
$t5 = count($shipsA5print);
$t8 = count($shipsA8print);

if($t || $t2 || $t3 || $t4 || $t5 || $t8){
?>
	<table width='100%'>
    	<tr>
			<td style='height:auto; font-size:5px; text-align:left; padding-bottom:10px;' id='strip'>
            	<div style="width:100%; float:left; height:auto; padding-bottom:20px;">
                
				<?php
                echo "<span style=\"font-size:14px; color:#F00;\">FOUND ".($t + $t2 + $t3 + $t4 + $t5 + $t8)." SHIP(S) AND TOOK <span id='exectime' style=\"font-size:14px; color:#F00;\"></span> SECONDS TO SEARCH <img src=\"images/contact_icon.png\" alt=\"To calculate each ship requires the following calculation.The location of the ship from its Latitude & Longitude and then either calculating the accurate distance using Distance Tables using waypoints and calculated routes to the port the ships is travelling to from its current location and then to the desired Load Port. Once those distances are determined and the time is calculated on the stated ships speed then that is matched to then determine if the ship can arrive during the required Laycan window.Or if the next port is unknown the ship uses the Last Known position and Load Port and the calculations are the same.The average time varies due to the number of ships located during the Search. The calculations run into the millions so it takes sometimes 12 seconds to 4 minutes for a very wide Search and 2200 ships found.The offset to that is how long would it take you to find 2200 ships that could possible be a candidate for your charter? The four minutes seems like a little time!



            \" title=\"To calculate each ship requires the following calculation.The location of the ship from its Latitude & Longitude and then either calculating the accurate distance using Distance Tables using waypoints and calculated routes to the port the ships is travelling to from its current location and then to the desired Load Port. Once those distances are determined and the time is calculated on the stated ships speed then that is matched to then determine if the ship can arrive during the required Laycan window.Or if the next port is unknown the ship uses the Last Known position and Load Port and the calculations are the same.The average time varies due to the number of ships located during the Search. The calculations run into the millions so it takes sometimes 12 seconds to 4 minutes for a very wide Search and 2200 ships found.The offset to that is how long would it take you to find 2200 ships that could possible be a candidate for your charter? The four minutes seems like a little time!

            \" /></span>";
                ?>
                
                </div>
                <div style="width:100%; float:left; height:auto; background: #47a5e7; padding:5px;">
				<span style='font-size:14px; font-weight:bold; color: white;'>
					<?php
                    $_SESSION['searchcriteria'] = $_GET['load_port']." - "; 
                    $_SESSION['searchcriteria'] .= $_GET['hull_type']." - "; 

                    $vt = $_GET['vessel_type'];
                    $vtt = count($vt);

                    for($i=0; $i<$vtt; $i++){
                        $value = $vt[$i];

                        if($i+1>=$vtt) $_SESSION['searchcriteria'] .= $value;

                        else $_SESSION['searchcriteria'] .= $value.", ";
                    }

                    $_SESSION['searchcriteria'] .= " ".date("M d 'y", convertDateToTs($_GET['load_port_from']))." - ".date("M d 'y", convertDateToTs($_GET['load_port_to']))." - ";

                    $_SESSION['searchcriteria'] .= $dwt_type;
					$_SESSION['dwt_type'] = $dwt_type;

                    echo $_SESSION['searchcriteria'];
                    ?>
                    
				</span>
                </div>
			</td>
    	</tr>
    </table>
    <script type="text/javascript">
	function expand(tid, imo, type){
		if(type=='shore'){
			if($('#'+tid+'_img').attr('src')=='images/icon_pullup_warning_shore.png'){
				$('#'+tid+'_img').attr('src', 'images/icon_dropdown_warning_shore.png');
				
				jQuery('#'+tid).hide();
				
				return 0;
			}else if($('#'+tid+'_img').attr('src')=='images/icon_pullup.png'){
				$('#'+tid+'_img').attr('src', 'images/icon_dropdown.png');
				
				jQuery('#'+tid).hide();
				
				return 0;
			}
		}else if(type=='broker'){
			if($('#'+tid+'_img').attr('src')=='images/icon_pullup_warning_broker.png'){
				$('#'+tid+'_img').attr('src', 'images/icon_dropdown_warning_broker.png');
				
				jQuery('#'+tid).hide();
				
				return 0;
			}else if($('#'+tid+'_img').attr('src')=='images/icon_pullup.png'){
				$('#'+tid+'_img').attr('src', 'images/icon_dropdown.png');
				
				jQuery('#'+tid).hide();
				
				return 0;
			}
		}else if(type=='email'){
			if($('#'+tid+'_img').attr('src')=='images/icon_pullup_warning_email.png'){
				$('#'+tid+'_img').attr('src', 'images/icon_dropdown_warning_email.png');
				
				jQuery('#'+tid).hide();
				
				return 0;
			}else if($('#'+tid+'_img').attr('src')=='images/icon_pullup.png'){
				$('#'+tid+'_img').attr('src', 'images/icon_dropdown.png');
				
				jQuery('#'+tid).hide();
				
				return 0;
			}
		}
		
		jQuery('#pleasewait2').show();
		
		jQuery.ajax({
			type: 'GET',
			url: 'updates_ajax.php?imo='+imo+'&type='+type,
			data: '',
	
			success: function(data) {
				jQuery('#pleasewait2').hide();
				
				if(type=='shore'){
					if($('#'+tid+'_img').attr('src')=='images/icon_dropdown_warning_shore.png'){
						$('#'+tid+'_img').attr('src', 'images/icon_pullup_warning_shore.png');
					}else if($('#'+tid+'_img').attr('src')=='images/icon_dropdown.png'){
						$('#'+tid+'_img').attr('src', 'images/icon_pullup.png');
					}
				}else if(type=='broker'){
					if($('#'+tid+'_img').attr('src')=='images/icon_dropdown_warning_broker.png'){
						$('#'+tid+'_img').attr('src', 'images/icon_pullup_warning_broker.png');
					}else if($('#'+tid+'_img').attr('src')=='images/icon_dropdown.png'){
						$('#'+tid+'_img').attr('src', 'images/icon_pullup.png');
					}
				}else if(type=='email'){
					if($('#'+tid+'_img').attr('src')=='images/icon_dropdown_warning_email.png'){
						$('#'+tid+'_img').attr('src', 'images/icon_pullup_warning_email.png');
					}else if($('#'+tid+'_img').attr('src')=='images/icon_dropdown.png'){
						$('#'+tid+'_img').attr('src', 'images/icon_pullup.png');
					}
				}
				
				jQuery('#'+tid).html(data);
				jQuery('#'+tid).show();
				jQuery('#'+tid).fadeIn(200);
			}
		});
	}
	</script>
    
    <?php
	
	//LIMIT DISPLAY
	if($dlimit<=$t){ $t = $dlimit; }
	if($dlimit<=$t2){ $t2 = $dlimit; }
	if($dlimit<=$t3){ $t3 = $dlimit; }
	if($dlimit<=$t4){ $t4 = $dlimit; }
	if($dlimit<=$t5){ $t5 = $dlimit; }
	if($dlimit<=$t8){ $t8 = $dlimit; }
	//END
	
	$_SESSION['shipsA1print'] = $shipsA1print;
	$_SESSION['shipsA2print'] = $shipsA2print;
	$_SESSION['shipsA3print'] = $shipsA3print;
	$_SESSION['shipsA4print'] = $shipsA4print;
	$_SESSION['shipsA5print'] = $shipsA5print;
	$_SESSION['shipsA8print'] = $shipsA8print;
	?>
    
	<ul>
		<li name='fragment-1' style='background: url("images/specs.jpg") no-repeat 5px 5px ; padding-left:30px; width:165px'  ><a><span>&nbsp;&nbsp;fixture management</span></a></li>
		<li name='fragment-2' style='background: url("images/shipeta.jpg") no-repeat 5px 5px ; padding-left:30px; width:165px'><a><span>&nbsp;position report</span></a></li>
        <li name='fragment-3' style='background: url("images/sched.jpg") no-repeat 5px 5px ; padding-left:30px; width:165px'><a><span>&nbsp;fixtures report</span></a></li>
	</ul>

	<div id="fragment-1">
	<?php include_once(dirname(__FILE__)."/includes/shipsearch/specifications.php"); ?>
	</div>

	<div id="fragment-2">
	<?php include_once(dirname(__FILE__)."/includes/shipsearch/positions.php"); ?>
	</div>

	<div id="fragment-3">
    <?php include_once(dirname(__FILE__)."/includes/shipsearch/schedule.php"); ?>
	</div>

	<?php
	$time_end = microtime_float();
	$time = $time_end - $time_start;
	$time = round($time);
	
	?>
    <script>
	jQuery("#exectime").html("<?php echo $time;?>");
	</script>
    <?php
	
	exit();
}else{
	echo "<center>No Results</center>";
}
?>