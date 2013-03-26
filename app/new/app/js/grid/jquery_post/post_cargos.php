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
	$res[$key]["actions"] = '<a href="s-bis.php?edit=1&amp;id='.$res[$key]["id"].'">edit</a> - <a href="s-bis.php?del=1&amp;id='.$res[$key]["id"].'">del</a>';
	
	$res[$key]["id"]          = $res[$key]["id"];
	$res[$key]["cargo_type"]  = stripslashes($res[$key]["cargo_type"]);
	$res[$key]["port_costs"]  = stripslashes($res[$key]["port_costs"]);
	$res[$key]["by_agent"]    = stripslashes($res[$key]["by_agent"]);
	$res[$key]["dateupdated"] = stripslashes($res[$key]["dateupdated"]);
	
	$rows[] = $res[$key];
}

$flexfields   = array('actions', 'id', 'cargo_type', 'port_costs', 'by_agent', 'dateupdated');
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