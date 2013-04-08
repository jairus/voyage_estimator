<?php
@session_start();
include_once(dirname(__FILE__)."../../jquery_database/database.php");

$portname = $_GET['portname'];
$dwt = $_GET['dwt'];
$dwt = intval(str_replace(',', '', $dwt));

if($dwt>=0 && $dwt<=10000){
	$dwt_low = 0;
	$dwt_high = 9999;
}else if($dwt>=10000 && $dwt<=39999){
	$dwt_low = 10000;
	$dwt_high = 39999;
}else if($dwt>=40000 && $dwt<=59999){
	$dwt_low = 40000;
	$dwt_high = 59999;
}else if($dwt>=60000 && $dwt<=99999){
	$dwt_low = 60000;
	$dwt_high = 99999;
}else if($dwt>=100000 && $dwt<=219999){
	$dwt_low = 100000;
	$dwt_high = 219999;
}else if($dwt>=220000 && $dwt<=550000){
	$dwt_low = 220000;
	$dwt_high = 550000;
}else{
	$dwt_low = 0;
	$dwt_high = 555000;
}

$where = "WHERE a.load_port='".$portname."' ";

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
	
	if( !empty($w) ){$where = "WHERE a.load_port='".$portname."' AND ".implode(' AND ',$w); }
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

$sql   = "SELECT COUNT(a.id) AS count FROM cargos AS a INNER JOIN _port_agents AS b ON a.by_agent = b.email ".$where;
$count = dbQuery($sql); $count = $count[0];

$sql = "SELECT a.id, a.cargo_quantity, a.port_costs, a.load_port2, a.load_port_quantity, a.channel, a.anchorage, a.cargo_pier, a.dwt_or_ship_type, b.company_name, b.first_name, b.last_name FROM cargos AS a INNER JOIN _port_agents AS b ON a.by_agent = b.email ".$where." ".$order." LIMIT ".( ($page-1) * $_POST['rp'] ).",".$_POST['rp'];
$res = dbQuery($sql);

foreach($res as $key => $value) {
	$dwt_rec = $res[$key]["dwt_or_ship_type"];
	$dwt_rec = intval(str_replace(',', '', $dwt_rec));
	
	if($dwt_rec>=$dwt_low && $dwt_rec<=$dwt_high){
		$res[$key]["actions"] = '<a href="port_details.php?view=1&amp;id='.$res[$key]["id"].'&amp;portname='.$_GET["portname"].'&amp;vessel_name='.$_GET["vessel_name"].'&amp;cargo_type='.$_GET["cargo_type"].'&amp;dwt='.$_GET["dwt"].'&amp;gross_tonnage='.$_GET["gross_tonnage"].'&amp;net_tonnage='.$_GET["net_tonnage"].'&amp;owner='.$_GET["owner"].'&amp;date_from='.$_GET["date_from"].'&amp;date_to='.$_GET["date_to"].'&amp;num_of_days='.$_GET["num_of_days"].'">view</a>';
		$res[$key]["id"] = $res[$key]["id"];
		$res[$key]["ship_agent"] = stripslashes($res[$key]["company_name"]).' / '.stripslashes($res[$key]["first_name"]).' '.stripslashes($res[$key]["last_name"]);
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

$flexfields   = array('actions', 'id', 'ship_agent', 'cargo_quantity', 'port_costs', 'load_port2', 'load_port_quantity', 'channel', 'anchorage', 'cargo_pier');
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