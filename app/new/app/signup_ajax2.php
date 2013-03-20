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

if( $_GET['trigger'] == 'email_check' ){
	$sql = mysql_query("SELECT * FROM _port_agents WHERE id = '".$_SESSION['user']['id']."' LIMIT 1");
	$r =  mysql_fetch_assoc($sql);
	
	if($r['email']!=$_GET['email']){
		$sql = mysql_query("SELECT COUNT(id) AS cnt FROM _port_agents WHERE email= '".$_GET['email']."' ");
		$row = mysql_fetch_assoc($sql);
		if( $row['cnt'] > 0 )
			echo 'email found';
		else
			echo 'not found';
	}
	
	exit;
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

if( $_POST['trigger'] == 'save_new_user' ){
	$sql = mysql_query("SELECT password FROM _port_agents WHERE id = '".mysql_escape_string($_POST['id'])."' LIMIT 1");
	$r =  mysql_fetch_assoc($sql);
	
	if($r['password']!=$_POST['pass1']){
		$sql = "update `_port_agents`
				set `first_name` = '".mysql_escape_string($_POST['first_name'])."', 
					`last_name` = '".mysql_escape_string($_POST['last_name'])."', 
					`office_number` = '".mysql_escape_string($_POST['office_number'])."', 
					`mobile_number` = '".mysql_escape_string($_POST['mobile_number'])."', 
					`fax_number` = '".mysql_escape_string($_POST['fax_number1'])."', 
					`telex` = '".mysql_escape_string($_POST['telex_number'])."', 
					`email` = '".mysql_escape_string($_POST['email'])."', 
					`password` = '".md5(mysql_escape_string($_POST['pass1']))."', 
					`skype` = '".mysql_escape_string($_POST['skype'])."', 
					`yahoo` = '".mysql_escape_string($_POST['yahoo'])."', 
					`msn` = '".mysql_escape_string($_POST['msn'])."', 
					`company_name` = '".mysql_escape_string($_POST['company_name'])."', 
					`address` = '".mysql_escape_string($_POST['address'])."', 
					`city` = '".mysql_escape_string($_POST['city'])."', 
					`postal_code` = '".mysql_escape_string($_POST['postal_code'])."', 
					`country` = '".mysql_escape_string($_POST['countryField'])."', 
					`fax` = '".mysql_escape_string($_POST['fax_number2'])."', 
					`website` = '".mysql_escape_string($_POST['website'])."', 
					`services` = '".mysql_escape_string($_POST['services'])."', 
					`dateadded` = '".date('Y-m-d H-i-s')."'
				where `id` = '".mysql_escape_string($_POST['id'])."'";
	}else{
		$sql = "update `_port_agents`
				set `first_name` = '".mysql_escape_string($_POST['first_name'])."', 
					`last_name` = '".mysql_escape_string($_POST['last_name'])."', 
					`office_number` = '".mysql_escape_string($_POST['office_number'])."', 
					`mobile_number` = '".mysql_escape_string($_POST['mobile_number'])."', 
					`fax_number` = '".mysql_escape_string($_POST['fax_number1'])."', 
					`telex` = '".mysql_escape_string($_POST['telex_number'])."', 
					`email` = '".mysql_escape_string($_POST['email'])."', 
					`skype` = '".mysql_escape_string($_POST['skype'])."', 
					`yahoo` = '".mysql_escape_string($_POST['yahoo'])."', 
					`msn` = '".mysql_escape_string($_POST['msn'])."', 
					`company_name` = '".mysql_escape_string($_POST['company_name'])."', 
					`address` = '".mysql_escape_string($_POST['address'])."', 
					`city` = '".mysql_escape_string($_POST['city'])."', 
					`postal_code` = '".mysql_escape_string($_POST['postal_code'])."', 
					`country` = '".mysql_escape_string($_POST['countryField'])."', 
					`fax` = '".mysql_escape_string($_POST['fax_number2'])."', 
					`website` = '".mysql_escape_string($_POST['website'])."', 
					`services` = '".mysql_escape_string($_POST['services'])."', 
					`dateadded` = '".date('Y-m-d H-i-s')."' 
				where `id` = '".mysql_escape_string($_POST['id'])."'";
	}
				
	$result = mysql_query($sql) or die(mysql_error());
	$row_id = mysql_insert_id();
	echo $row_id;
	
	exit;	
}
?>