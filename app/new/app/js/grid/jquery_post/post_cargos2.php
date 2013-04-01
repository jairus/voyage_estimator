<?php
include_once(dirname(__FILE__)."../../jquery_database/database.php");

$where = '';

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
	
	if( !empty($w) ){$where = "WHERE ".implode(' AND ',$w);}
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
	$res[$key]["actions"] = '<a href="port_details.php?view=1&amp;id='.$res[$key]["id"].'&amp;portname='.$_GET['portname'].'&amp;vessel_name='.$_GET['vessel_name'].'&amp;cargo_type='.$_GET['cargo_type'].'&amp;dwt='.$_GET['dwt'].'&amp;gross_tonnage='.$_GET['gross_tonnage'].'&amp;net_tonnage='.$_GET['net_tonnage'].'&amp;owner='.$_GET['owner'].'&amp;date_from='.$_GET['date_from'].'&amp;date_to='.$_GET['date_to'].'&amp;num_of_days='.$_GET['num_of_days'].'">view</a>';
	
	$res[$key]["id"] = $res[$key]["id"];
	$res[$key]["cargo_quantity"] = stripslashes($res[$key]["cargo_quantity"]);
	$res[$key]["load_port2"] = stripslashes($res[$key]["load_port2"]);
	$res[$key]["load_port_quantity"] = stripslashes($res[$key]["load_port_quantity"]);
	$res[$key]["channel"] = stripslashes($res[$key]["channel"]);
	$res[$key]["anchorage"] = stripslashes($res[$key]["anchorage"]);
	$res[$key]["cargo_pier"] = stripslashes($res[$key]["cargo_pier"]);
	
	$rows[] = $res[$key];
}

$flexfields   = array('actions', 'id', 'cargo_quantity', 'load_port2', 'load_port_quantity', 'channel', 'anchorage', 'cargo_pier');
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