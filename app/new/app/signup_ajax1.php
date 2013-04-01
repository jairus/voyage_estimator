<?php
session_start();
require_once("../captcha/securimage.php");

$img = new Securimage();

$dbhost = 's-bis.cfclysrb91of.us-east-1.rds.amazonaws.com';
$dbuser = 'sbis';
$dbpass = 'roysbis';
$dbname = 'sbis';

$conn   = mysql_connect($dbhost,$dbuser,$dbpass) or die('Error connecting to mysql');
mysql_select_db($dbname, $conn);

if( $_GET['trigger'] == 'get_port_depth' ){
	if($_GET['type']==1){
		$portname = $_POST['load_port2'];
	}else{
		$portname = $_POST['discharge_port2'];
	}

	$sql = mysql_query("SELECT channel_depth, anchorage_depth, cargo_pier_depth FROM wpi_data WHERE main_port_name = '".mysql_escape_string(strtoupper($portname))."' LIMIT 1");
	$r =  mysql_fetch_assoc($sql);
	
	$sql1 = mysql_query("SELECT meters FROM wpi_depth_code_lut WHERE depth_code = '".mysql_escape_string(strtoupper($r['channel_depth']))."' LIMIT 1");
	$channel_depth =  mysql_fetch_assoc($sql1);
	
	$sql2 = mysql_query("SELECT meters FROM wpi_depth_code_lut WHERE depth_code = '".mysql_escape_string(strtoupper($r['anchorage_depth']))."' LIMIT 1");
	$anchorage_depth =  mysql_fetch_assoc($sql2);
	
	$sql3 = mysql_query("SELECT meters FROM wpi_depth_code_lut WHERE depth_code = '".mysql_escape_string(strtoupper($r['cargo_pier_depth']))."' LIMIT 1");
	$cargo_pier_depth =  mysql_fetch_assoc($sql3);
	
	$depths = array(
		'channel_depth' => $channel_depth['meters'],
		'anchorage_depth' => $anchorage_depth['meters'],
		'cargo_pier_depth' => $cargo_pier_depth['meters'],
	);
    echo json_encode($depths);

	exit();
}

if( $_POST['trigger'] == 'validate_captcha' ){
	$valid = $img->check($_POST['code']);
	if( $valid == true )
		echo 'code accepted';
	else{
		echo 'code error: '.$_POST['code']."\n";
	}
	exit;
}

if( $_POST['trigger'] == 'save_new_cargo' ){
	$sql = mysql_query("SELECT id FROM cargos WHERE id = '".mysql_escape_string(strtoupper($_POST['id']))."' LIMIT 1");
	$r =  mysql_fetch_assoc($sql);
	
	$load_port = mysql_escape_string(strtoupper($_POST['load_port']));
	$discharge_port = mysql_escape_string(strtoupper($_POST['discharge_port']));
	$cargo_date = mysql_escape_string(date('Y-m-d', strtotime($_POST['cargo_date'])));
	$dwt_or_ship_type = mysql_escape_string($_POST['dwt_or_ship_type']);
	$cargo_type = mysql_escape_string(strtoupper($_POST['cargo_type']));
	$cargo_quantity = mysql_escape_string($_POST['cargo_quantity']);
	$port_costs = mysql_escape_string($_POST['port_costs']);
	$load_port2 = mysql_escape_string(strtoupper($_POST['load_port2']));
	$load_port_quantity = mysql_escape_string(strtoupper($_POST['load_port_quantity']));
	$channel = mysql_escape_string(strtoupper($_POST['channel']));
	$anchorage = mysql_escape_string(strtoupper($_POST['anchorage']));
	$cargo_pier = mysql_escape_string(strtoupper($_POST['cargo_pier']));
	$discharge_port2 = mysql_escape_string(strtoupper($_POST['discharge_port2']));
	$discharge_port_quantity = mysql_escape_string(strtoupper($_POST['discharge_port_quantity']));
	$channel2 = mysql_escape_string(strtoupper($_POST['channel2']));
	$anchorage2 = mysql_escape_string(strtoupper($_POST['anchorage2']));
	$cargo_pier2 = mysql_escape_string(strtoupper($_POST['cargo_pier2']));
	$notes = mysql_escape_string($_POST['notes']);
	$by_agent = $_SESSION['user']['email'];
	
	if($r['id']){
		$sql = "update `cargos`
				set `load_port` = '".$load_port."', 
					`discharge_port` = '".$discharge_port."', 
					`cargo_date` = '".$cargo_date."', 
					`dwt_or_ship_type` = '".$dwt_or_ship_type."', 
					`cargo_type` = '".$cargo_type."', 
					`cargo_quantity` = '".$cargo_quantity."', 
					`port_costs` = '".$port_costs."', 
					`load_port2` = '".$load_port2."', 
					`load_port_quantity` = '".$load_port_quantity."', 
					`channel` = '".$channel."', 
					`anchorage` = '".$anchorage."', 
					`cargo_pier` = '".$cargo_pier."', 
					`discharge_port2` = '".$discharge_port2."', 
					`discharge_port_quantity` = '".$discharge_port_quantity."', 
					`channel2` = '".$channel2."', 
					`anchorage2` = '".$anchorage2."', 
					`cargo_pier2` = '".$cargo_pier2."', 
					`notes` = '".$notes."', 
					`by_agent` = '".$_SESSION['user']['email']."', 
					`dateupdated` = NOW()
				where `id` = '".mysql_escape_string($_POST['id'])."'";
	}else{
		$sql = "insert into `cargos` (`load_port`, `discharge_port`, `cargo_date`, `dwt_or_ship_type`, `cargo_type`, `cargo_quantity`, `port_costs`, `load_port2`, `load_port_quantity`, `channel`, `anchorage`, `cargo_pier`, `discharge_port2`, `discharge_port_quantity`, `channel2`, `anchorage2`, `cargo_pier2`, `notes`, `by_agent`, `dateadded`, `dateupdated`) values('".$load_port."', '".$discharge_port."', '".$cargo_date."', '".$dwt_or_ship_type."', '".$cargo_type."', '".$cargo_quantity."', '".$port_costs."', '".$load_port2."', '".$load_port_quantity."', '".$channel."', '".$anchorage."', '".$cargo_pier."', '".$discharge_port2."', '".$discharge_port_quantity."', '".$channel2."', '".$anchorage2."', '".$cargo_pier2."', '".$notes."', '".$by_agent."', NOW(), NOW())";
	}
				
	$result = mysql_query($sql) or die(mysql_error());
	$row_id = mysql_insert_id();
	echo $row_id;
	
	exit;	
}
?>