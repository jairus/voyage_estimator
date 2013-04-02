<?php
@session_start();
include_once(dirname(__FILE__)."../../jquery_database/database.php");

$dwt = str_replace(' tons', '', $_SESSION['dwt']);
$dwt = intval(str_replace(',', '', $dwt));

if($dwt>=0 && $dwt<=10000){
	$dwt_low = 0;
	$dwt_high = 10000;
}else if($dwt>=10000 && $dwt<=35000){
	$dwt_low = 10000;
	$dwt_high = 35000;
}else if($dwt>=35000 && $dwt<=60000){
	$dwt_low = 35000;
	$dwt_high = 60000;
}else if($dwt>=60000 && $dwt<=75000){
	$dwt_low = 60000;
	$dwt_high = 75000;
}else if($dwt>=75000 && $dwt<=110000){
	$dwt_low = 75000;
	$dwt_high = 110000;
}else if($dwt>=110000 && $dwt<=150000){
	$dwt_low = 110000;
	$dwt_high = 150000;
}else if($dwt>=150000 && $dwt<=555000){
	$dwt_low = 150000;
	$dwt_high = 555000;
}else{
	$dwt_low = 0;
	$dwt_high = 555000;
}

$where = "WHERE `load_port`='".$_SESSION['portname']."' ";

if( $_POST['query'] != '' ){
	$w  = array();
	$sq = explode('&',$_POST['query']);	
	
	foreach( $sq as $q ){
		list( $k, $v ) = explode( '=',$q);
		
		$table = '';
		
		if( $v != '' ){
			if( strpos($k,'.') > -1 ){
				list( $t, $key ) = explode('.',$k);
				$table = '`'.$t.'`.';
			}else{
				$key = $k;				
			}
			
			$w[] = $table."`".$key."` LIKE '%".urldecode(trim($v))."%' ";
		}			
	}
	
	if( !empty($w) ){$where = "WHERE load_port='".$_SESSION['portname']."' AND ".implode(' AND ',$w); }
}

$order = '';

if( isset($_POST['sortname']) ){
	$table = '';
	
	if( strpos($_POST['sortname'],'.') > -1 ){
		list( $t, $key ) = explode('.',$_POST['sortname']);
		$table = '`'.$t.'`.';
	}else{$key = $_POST['sortname'];}
	
	$order = "ORDER BY ".$table."`".$key."` ".$_POST['sortorder']." ";
}

$count['count'] = 0;
$page = isset($_POST['page']) ? $_POST['page'] : 1;

$sql   = "SELECT COUNT(id) AS count FROM cargos ".$where;
$count = dbQuery($sql); $count = $count[0];

$sql = "SELECT * FROM cargos ".$where." ".$order." LIMIT ".( ($page-1) * $_POST['rp'] ).",".$_POST['rp'];
$res = dbQuery($sql);

foreach($res as $key => $value) {
	$dwt_rec = str_replace(' tons', '', $res[$key]["dwt_or_ship_type"]);
	$dwt_rec = intval(str_replace(',', '', $dwt_rec));
	
	if($dwt_rec>=$dwt_low && $dwt_rec<=$dwt_high){
		$res[$key]["actions"] = '<a href="port_details.php?view=1&amp;id='.$res[$key]["id"].'&amp;portname='.$_SESSION["portname"].'&amp;vessel_name='.$_SESSION["vessel_name"].'&amp;cargo_type='.$_SESSION["cargo_type"].'&amp;dwt='.$_SESSION["dwt"].'&amp;gross_tonnage='.$_SESSION["gross_tonnage"].'&amp;net_tonnage='.$_SESSION["net_tonnage"].'&amp;owner='.$_SESSION["owner"].'&amp;date_from='.$_SESSION["date_from"].'&amp;date_to='.$_SESSION["date_to"].'&amp;num_of_days='.$_SESSION["num_of_days"].'">view</a>';
		$res[$key]["id"] = $res[$key]["id"];
		$res[$key]["cargo_quantity"] = stripslashes($res[$key]["cargo_quantity"]);
		$res[$key]["port_costs"] = stripslashes($res[$key]["port_costs"]);
		$res[$key]["load_port2"] = stripslashes($res[$key]["load_port2"]);
		$res[$key]["load_port_quantity"] = stripslashes($res[$key]["load_port_quantity"]);
		$res[$key]["channel"] = stripslashes($res[$key]["channel"]);
		$res[$key]["anchorage"] = stripslashes($res[$key]["anchorage"]);
		$res[$key]["cargo_pier"] = stripslashes($res[$key]["cargo_pier"]);
		
		$rows[] = $res[$key];
	}
}

$flexfields   = array('actions', 'id', 'cargo_quantity', 'port_costs', 'load_port2', 'load_port_quantity', 'channel', 'anchorage', 'cargo_pier');
$arr['page']  = $page;
$arr['total'] = $count['count'];

$arr['rows'] = array();

if( !empty($rows) ){
	foreach ( $rows as $row ) {			
		$row     = array_map(utf8_encode, $row);
		$thisrow = array();

		foreach( $flexfields as $i ){$thisrow[] = $row[$i];}

		$arr['rows'][] = array( 'id'=>$row['id'], 'cell'=>$thisrow );
	}
}

echo json_encode($arr);
?>