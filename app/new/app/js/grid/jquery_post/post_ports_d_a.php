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

$where = "WHERE `port_name`='".$portname."' ";

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
	
	if( !empty($w) ){$where = "WHERE port_name='".$portname."' AND ".implode(' AND ',$w); }
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

$sql   = "SELECT COUNT(id) AS count FROM _port_details ".$where;
$count = dbQuery($sql); $count = $count[0];

$sql = "SELECT * FROM _port_details ".$where." ".$order." LIMIT ".( ($page-1) * $_POST['rp'] ).",".$_POST['rp'];
$res = dbQuery($sql);

foreach($res as $key => $value) {
	$dwt_rec = $res[$key]["dwt"];
	$dwt_rec = intval(str_replace(',', '', $dwt_rec));
	
	if($dwt_rec>=$dwt_low && $dwt_rec<=$dwt_high){
		$res[$key]["actions"] = '<a href="port_details.php?edit=1&amp;id='.$res[$key]["id"].'&amp;portname='.$_GET["portname"].'&amp;vessel_name='.$_GET["vessel_name"].'&amp;cargo_type='.$_GET["cargo_type"].'&amp;dwt='.$_GET["dwt"].'&amp;gross_tonnage='.$_GET["gross_tonnage"].'&amp;net_tonnage='.$_GET["net_tonnage"].'&amp;owner='.$_GET["owner"].'&amp;date_from='.$_GET["date_from"].'&amp;date_to='.$_GET["date_to"].'&amp;num_of_days='.$_GET["num_of_days"].'">edit</a> | <a href="port_details.php?del=1&amp;id='.$res[$key]["id"].'&amp;portname='.$_GET["portname"].'&amp;vessel_name='.$_GET["vessel_name"].'&amp;cargo_type='.$_GET["cargo_type"].'&amp;dwt='.$_GET["dwt"].'&amp;gross_tonnage='.$_GET["gross_tonnage"].'&amp;net_tonnage='.$_GET["net_tonnage"].'&amp;owner='.$_GET["owner"].'&amp;date_from='.$_GET["date_from"].'&amp;date_to='.$_GET["date_to"].'&amp;num_of_days='.$_GET["num_of_days"].'">del</a>';
		$res[$key]["id"] = $res[$key]["id"];
		$res[$key]["ship_agent"] = stripslashes($res[$key]["ship_agent"]);
		$res[$key]["vessel"] = stripslashes($res[$key]["vessel"]);
		$res[$key]["dwt"] = stripslashes($res[$key]["dwt"]);
		$res[$key]["grt"] = stripslashes($res[$key]["grt"]);
		$res[$key]["nrt"] = stripslashes($res[$key]["nrt"]);
		$res[$key]["total_over_all"] = stripslashes($res[$key]["total_over_all"]);
		$res[$key]["date"] = stripslashes($res[$key]["date"]);
		$res[$key]["cargo_type"] = stripslashes($res[$key]["cargo_type"]);
		
		$rows[] = $res[$key];
	}
}

$flexfields   = array('actions', 'id', 'ship_agent', 'vessel', 'dwt', 'grt', 'nrt', 'total_over_all', 'date', 'cargo_type');
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