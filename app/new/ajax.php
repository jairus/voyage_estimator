<?php
@include_once(dirname(__FILE__)."/includes/bootstrap.php");

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
?>