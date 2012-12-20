<?php
@session_start();
include_once(dirname(__FILE__)."/../includes/bootstrap.php");
date_default_timezone_set('UTC');
?>
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

echo "<div class='landScape'>
<table width='100%' border='0' cellpadding='0' cellspacing='0'>
	<tr>
		<td style='border:0px;' width='460'><img src='http://".$_SERVER['HTTP_HOST']."/app/images/logo_cargospotter1.png'></td>
		<td style='border:0px; text-align:right;' width='540'><img src='http://".$_SERVER['HTTP_HOST']."/app/images/user_images/".$photo1."' width='80' alt='photo' border='0' /><br>Sent by <a href='mailto:".$rows[0]['email']."'>".$rows[0]['email']."</a></td>
	</tr>
</table>
<div style='text-align:left; padding:15px 5px 5px 5px;'><b>CURRENT DATE/TIME: ".date("d-m-Y")."</b></div>
<div>&nbsp;</div>";

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
									echo "<tr><td class='leftlabel'>".$k.":</td><td class='rightvalue'><img alt=\"".htmlentities($flag)."\" title=\"".htmlentities($flag)."\" src='../".$img."' >&nbsp;$v</td></tr>";
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

$sql = "select * from `_xvas_shipdata_dry` where `imo`='".mysql_escape_string($_GET['imo'])."'";
$ship = dbQuery($sql, $link);
$ship = $ship[0];

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

echo "<div style='text-align:center;' ><img src='../image.php?b=1&mx=500&p=".$imageb."'></div><br>";

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

echo "<table cellpadding='0' cellspacing='0' width='100%'>
	<tr>
		<td style='border:0px; text-align:right;'>Powered by <img src='http://".$_SERVER['HTTP_HOST']."/app/images/logo_cargospotter1.png' width='20'> <b>CargoSpotter</b></td>
	</tr>
</table>
<div>&nbsp;</div>";
?>
<script>
window.print();
</script>