<?php
@session_start();
date_default_timezone_set('UTC');

include_once(dirname(__FILE__)."/includes/bootstrap.php");

$link = dbConnect();

echo '<table width="100%" border="1" cellspacing="5" cellpadding="5">
  <tr>
	<th align="left"><div style="padding:5px">PORT CODE</div></th>
	<th align="left"><div style="padding:5px">PORT NAME</div></th>
	<th align="left"><div style="padding:5px">GRADE</div></th>
	<th align="left"><div style="padding:5px">AVERAGE PRICE</div></th>
	<th align="left"><div style="padding:5px">LAST UPDATED</div></th>
	<th align="left" width=""><div style="padding:5px">HIS</div></th>
  </tr>';

$bunkerportname = $_GET['bunkerportname'];
$date_from = date('Y-m-d', strtotime($_GET['date_from']));
$date_to = date('Y-m-d', strtotime($_GET['date_to']));

if(trim($bunkerportname)!=''){
	$sql = "SELECT * FROM `bunker_price` WHERE port_name='".trim($bunkerportname)."' AND dateupdated BETWEEN '".$date_from."' AND '".$date_to."' ORDER BY `dateupdated` DESC";
}else{
	$sql = "SELECT * FROM `bunker_price` WHERE dateupdated BETWEEN '".$date_from."' AND '".$date_to."' ORDER BY `dateupdated` DESC";
}

$ports = dbQuery($sql, $link);

$t = count($ports);

if(trim($t)){
	$prices = array();
	for($i=0;$i<$t;$i++){
		$print = array();
		
		$print['port_code'] = $ports[$i]['port_code'];
		$print['port_name'] = $ports[$i]['port_name'];
		$print['grade'] = $ports[$i]['grade'];
		$print['average_price'] = $ports[$i]['average_price'];
		$print['dateupdated'] = $ports[$i]['dateupdated'];
		
		$prices[] = $print;
		
		echo '<tr>
			<td><div style="padding:5px">'.$ports[$i]['port_code'].'</div></td>
			<td><div style="padding:5px">'.$ports[$i]['port_name'].'</div></td>
			<td><div style="padding:5px">'.$ports[$i]['grade'].'</div></td>
			<td><div style="padding:5px">US $ '.$ports[$i]['average_price'].'</div></td>
			<td><div style="padding:5px">'.$ports[$i]['dateupdated'].'</div></td>
			<td width="20"><div style="padding:5px"><a onclick="getBunkerPriceHistory(\''.$ports[$i]['port_code'].'\');" alt="Bunker Price History" title="Bunker Price History" style="cursor:pointer;"><img src="images/icon_plusdown_warning.png" border="0" /></a></div></td>
		  </tr>';
	}
	
	$_SESSION['prices'] = $prices;
	
	echo '</table>
	<div>&nbsp;</div>
	<table width="100%">
		<tr style="background:#e5e5e5; padding:10px 0px;">
			<td><div style="padding:5px; text-align:center;"><a onclick="showMapBP();" class="clickable">view larger map</a></div></td>
		</tr>
		<tr style="background:#e5e5e5;">
			<td><div style="padding:5px; text-align:center;"><iframe src="map/index12.php" width="990" height="700"></iframe></div></td>
		</tr>
	</table>';
}else{
	echo '<tr>
		<td colspan="6"><div style="padding:5px"><b><center>NO RECORD OF BUNKER PRICE YET.</center></b></div></td>
	  </tr>';
}
?>