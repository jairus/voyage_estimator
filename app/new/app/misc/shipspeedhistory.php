<?php
@include_once(dirname(__FILE__)."/../includes/bootstrap.php");
date_default_timezone_set('UTC');

$sql = "select * from `_ship_history` where `xvas_imo`='".$_GET['imo']."' order by `dateupdated` desc";
$ships = dbQuery($sql, $link);

$t = count($ships);

echo '<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr bgcolor="cddee5">
    <td colspan="3"><div style="padding:5px;"><b>'.$ships[0]['xvas_name'].' - '.$_GET['imo'].'</b></div></td>
  </tr>
  <tr bgcolor="333333">
  	<td width="200"><div style="padding:5px; color:#fff;"><b>AIS DESTINATION</b></div></td>
    <td width="150"><div style="padding:5px; color:#fff;"><b>AIS SPEED</b></div></td>
    <td><div style="padding:5px; color:#fff;"><b>DATE UPDATED</b></div></td>
  </tr>';

if($t){
	for($i=0; $i<$t; $i++){
		if($i%2==0){
			$bg_color = 'f5f5f5';
		}else{
			$bg_color = 'e9e9e9';
		}
		
		echo '<tr bgcolor="'.$bg_color.'">
			<td><div style="padding:5px;">'.$ships[$i]['siitech_destination'].'</div></td>
			<td><div style="padding:5px;">'.getValue($ships[$i]['siitech_shipstat_data'], 'speed_ais').'</div></td>
			<td><div style="padding:5px;">'.date("M j, Y G:i e", strtotime($ships[$i]['dateupdated'])).'</div></td>
		  </tr>';
	}
}

echo '</table>';
?>