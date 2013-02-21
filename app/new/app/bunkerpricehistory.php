<?php
@session_start;
include_once(dirname(__FILE__)."/includes/bootstrap.php");

$sql = "SELECT * FROM `bunker_price_his` WHERE `port_code`='".$_GET['port_code']."' AND `grade`='".$_GET['grade']."'  ORDER BY dateupdated DESC LIMIT 0,1000";
$history = dbQuery($sql);
$t = count($history);

echo '<table width="660" border="1" cellspacing="0" cellpadding="0">
  <tr>
	<th align="left"><div style="padding:5px">PORT CODE</div></th>
	<th align="left"><div style="padding:5px">PORT NAME</div></th>
	<th align="left"><div style="padding:5px">GRADE</div></th>
	<th align="left"><div style="padding:5px">AVERAGE PRICE</div></th>
	<th align="left"><div style="padding:5px">LAST UPDATED</div></th>
  </tr>';

if(trim($t)){
	for($i=0; $i<$t; $i++){
		echo '<tr>
			<td><div style="padding:5px">'.$history[$i]['port_code'].'</div></td>
			<td><div style="padding:5px">'.$history[$i]['port_name'].'</div></td>
			<td><div style="padding:5px">'.$history[$i]['grade'].'</div></td>
			<td><div style="padding:5px">US $ '.$history[$i]['average_price'].'</div></td>
			<td><div style="padding:5px">'.$history[$i]['dateupdated'].'</div></td>
		  </tr>';
	}
	
	echo '</table>';
}else{
	echo '<tr>
		<td colspan="5" align="center"><div style="padding:5px">No history</div></td>
	  </tr>';
}
?>