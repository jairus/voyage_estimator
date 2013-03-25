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
	
	$dwt = mysql_escape_string($_POST['dwt']);
	$cargo = mysql_escape_string(strtoupper($_POST['cargo']));
	$costs = mysql_escape_string($_POST['costs']);
	$cargo_date = mysql_escape_string(date('Y-m-d', strtotime($_POST['cargo_date'])));
	$load_port = mysql_escape_string(strtoupper($_POST['load_port']));
	$discharge_port = mysql_escape_string(strtoupper($_POST['discharge_port']));
	
	if($r['id']){
		$sql = "update `cargos`
				set `dwt` = '".$dwt."', 
					`cargo` = '".$cargo."', 
					`costs` = '".$costs."', 
					`cargo_date` = '".$cargo_date."', 
					`load_port` = '".$load_port."', 
					`discharge_port` = '".$discharge_port."', 
					`by_agent` = '".$_SESSION['user']['email']."', 
					`dateupdated` = NOW()
				where `id` = '".mysql_escape_string($_POST['id'])."'";
	}else{
		$sql = "insert into `cargos` (`dwt`, `cargo`, `costs`, `cargo_date`, `load_port`, `discharge_port`, `by_agent`, `dateadded`, `dateupdated`) values('".$dwt."', '".$cargo."', '".$costs."', '".$cargo_date."', '".$load_port."', '".$discharge_port."', '".$_SESSION['user']['email']."', NOW(), NOW())";
	}
				
	$result = mysql_query($sql) or die(mysql_error());
	$row_id = mysql_insert_id();
	echo $row_id;
	
	exit;	
}
?>