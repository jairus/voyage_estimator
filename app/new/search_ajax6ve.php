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

if(trim($_GET['bunkerportname'])){
	$_SESSION['bunkerportname'] = $_GET['bunkerportname'];
	
	$sql = "SELECT * FROM `bunker_price` WHERE port_name='".trim($_GET['bunkerportname'])."' ORDER BY `dateupdated` DESC LIMIT 0,4";
	$ports = dbQuery($sql, $link);
	
	$t = count($ports);
	
	if(trim($t)){
		for($i=0;$i<$t;$i++){
            echo '<tr>
                <td><div style="padding:5px">'.$ports[$i]['port_code'].'</div></td>
                <td><div style="padding:5px">'.$ports[$i]['port_name'].'</div></td>
                <td><div style="padding:5px">'.$ports[$i]['grade'].'</div></td>
                <td><div style="padding:5px">US $ '.$ports[$i]['average_price'].'</div></td>
				<td><div style="padding:5px">'.$ports[$i]['dateupdated'].'</div></td>
				<td width="20"><div style="padding:5px"><a onclick="getBunkerPriceHistory(\''.$ports[$i]['port_code'].'\');" alt="Bunker Price History" title="Bunker Price History" style="cursor:pointer;"><img src="images/icon_plusdown_warning.png" border="0" /></a></div></td>
              </tr>';
		}
		
		echo '</table>
		<div>&nbsp;</div>
		<table width="100%">
			<tr style="background:#e5e5e5; padding:10px 0px;">
				<td><div style="padding:5px; text-align:center;"><a onclick="showMapBP();" class="clickable">view larger map</a></div></td>
			</tr>
			<tr style="background:#e5e5e5;">
				<td><div style="padding:5px; text-align:center;"><iframe src="map/index12.php?bunkerportname='.$_GET['bunkerportname'].'" width="990" height="700"></iframe></div></td>
			</tr>
		</table>';
	}else{
		echo '<tr>
			<td colspan="6"><div style="padding:5px"><b><center>NO RECORD OF BUNKER PRICE YET.</center></b></div></td>
		  </tr>';
	}
}else{
	echo '<tr>
		<td colspan="6"><div style="padding:5px"><b><center>PORT NAME FIELD IS EMPTY.</center></b></div></td>
	  </tr>';
}
?>