<?php
session_start();
require_once("captcha/securimage.php");
require_once("app/misc/emailer/email.php");

$img = new Securimage();

$dbhost = 's-bis.cfclysrb91of.us-east-1.rds.amazonaws.com';
$dbuser = 'sbis';
$dbpass = 'roysbis';
$dbname = 'sbis';

$conn   = mysql_connect($dbhost,$dbuser,$dbpass) or die('Error connecting to mysql');
mysql_select_db($dbname, $conn);

if( $_GET['trigger'] == 'email_check' ){
	$sql = mysql_query("SELECT COUNT(id) AS cnt FROM _port_agents WHERE email= '".$_GET['email']."' ");
	$row = mysql_fetch_assoc($sql);
	if( $row['cnt'] > 0 )
		echo 'email found';
	else
		echo 'not found';
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
	foreach($_POST as $key => $value)
		$post[$key] = trim($value);
	
	$arr_data = array(	'id' => $post['agent_id'],
						'first_name' => $post['first_name'],
						'last_name' => $post['last_name'],
						'office_number' => $post['office_number'],
						'mobile_number' => $post['mobile_number'],
						'fax_number' => $post['fax_number1'],
						'telex' => $post['telex_number'],
						'email' => $post['email'],
						'password' => md5($post['pass1']),
						'skype' => $post['skype'],
						'yahoo' => $post['yahoo'],
						'msn' => $post['msn'],
						'company_name' => $post['company_name'],
						'business_type' => 'Port Agents',
						'address' => $post['address'],
						'city' => $post['city'],
						'postal_code' => $post['postal_code'],
						'country' => $post['countryField'],
						'fax' => $post['fax_number2'],
						'website' => $post['website'],
						'services' => $post['services'],
						'dateadded' => date('Y-m-d H-i-s'));
						
	foreach($arr_data as $field => $data){
		$arr_fields[] = $field;
		$arr_datas[] = $data;
	}
	
	$sql = "INSERT INTO _port_agents(".implode(", ",$arr_fields).") VALUES ('".implode("', '", $arr_datas)."') ";
	$result = mysql_query($sql) or die(mysql_error());
	$row_id = mysql_insert_id();
	//mail_it($post, $row_id);
	echo $row_id;
		
	exit;	
}

function mail_it($post, $id){
	$from_1 = trim($post['email']);
	$fromname_1 = trim($post['lastname']).", ".trim($post['firstname']);	
	$bouncereturn = "mailer@s-bisonline.com"; //where the email will forward in cases of bounced email
	$subject_1 = "S-BIS Purchase Request";
	$emails_1[0]['email'] = "roydevlin@yahoo.com";
	$emails_1[0]['name'] = "Roy Devlin";
	$message_1 = "
	Hello Roy,
	
	
	I'm ".trim($post['firstname'])." ".trim($post['lastname'])."
	
	
	<b><u>Here is what I want to purchase</u>:</b>
	
	<i>Category</i>: ".$post['category']."
	
	<i>Type</i>: ".$post['type']."
	
	<i>Purchase</i>: ".$post['purchase']."
	
	
	
	<b><u>Other details</u>:</b>
		
	<i>Company</i>: ".trim($post['company_name'])."
	
	<i>Position</i>: ".trim($post['position'])."	
	
	<i>Email</i>: ".trim($post['email'])."	
	
	<i>Department</i>: ".trim($post['department'])."		
	
	<i>Address 1</i>: ".trim($post['address_1'])."
	
	<i>Address 2</i>: ".trim($post['address_2'])."
	
	<i>City</i>: ".trim($post['city'])."
	
	<i>Zip / Postal Code</i>: ".trim($post['postal_code'])."
	
	<i>Country</i>: ".trim($post['countryField'])."
			
	<i>Phone Number</i>: ".trim($post['p_country_code']."-".$post['p_area_code']."-".$post['phone_number'])."
	
	<i>Number of License</i>: ".trim($post['num_of_license'])."	
	
	<i>Years of Experience</i>: ".trim($post['work_experience'])."
	
	Thank You!



	";
	
	$message_1 = nl2br($message_1);
	emailBlast($from_1, $fromname_1, $subject_1, $message_1, $emails_1, $bouncereturn, 0);
	
	
	$from_2 = "billing@s-bis.com";
	$fromname_2 = "S-Bisonline.com";	
	$bouncereturn = "mailer@s-bisonline.com"; //where the email will forward in cases of bounced email
	$subject_2 = "Welcom to S-BIS";
	$emails_2[0]['email'] = trim($post['email']);
	$emails_2[0]['name'] = trim($post['lastname']).", ".trim($post['firstname']);
	
	$arr_message_2['trial'] = "<p style=\"font-size: 12px; font-family: Arial;\"><img src=\"http://www.s-bisonline.com/images/global/logo_sbis.png\" id=\"logo\" /></p><p style=\"font-size: 12px; font-family: Arial;\">Dear ".ucwords(trim($post['firstname'])." ".trim($post['lastname']))."
	
	
	Welcome to S-BIS. Please click or visit the activation button or link below to activate your account;
	

	<a target=\"_blank\" href=\"http://www.s-bisonline.com/login.php?activate=".base64_encode(md5($id))."\"><span style=\"border: 2px solid #69B3E3; padding:10px; background: #D8EBF8; cursor:pointer; color:#333333; text-align: center; font-size:12px; font-family: Arial, Verdana; font-size: 14px; text-decoration:none;\">Activate Account</span></a>
	
	
	http://www.s-bisonline.com/login.php?activate=".base64_encode(md5($id))."
	
	
	The information you provided has been processed and you have been granted FULL access to the S-BIS portal - The trial will expire in SEVEN days.
	
	Please use the E-mail address and password that you registered to log in the system.	
	
	If you would like to trial as a group, please e-mail <a href=\"mailto:roydevlin@s-bis.com\">roydevlin@s-bis.com</a>.
	
	As I'm sure you appreciate data-protection and the security of personal and business intelligence is an absolute priority and we use the latest data-tagging technology to ensure the information we provide is not abused in any way. You will be reassured to learn that any attempt to compromise this portal can be dealt with swiftly and effectively. Access to the S-BIS portal is provided on trust and the integrity of the user is expected and respected.
	
	I am confident you will find the service provided by S-BIS a refreshing change from the norm and would appreciate any feedback or suggestions you may have. Enjoy the trial and we look forward to hearing from you.
	
	
	With kind regards.
	
	
	Roy Devlin 
	CEO S-BIS a brand of  Maritime Infosys Pte Ltd
	Contact me any time roydevlin@s-bis.com</p>";
	
	$arr_message_2['purchase'] = "<pstyle=\"font-size: 12px; font-family: Arial;\"><img src=\"http://www.s-bisonline.com/images/global/logo_sbis.png\" id=\"logo\" /></p><p style=\"font-size: 12px; font-family: Arial;\">Hi ".ucwords(trim($post['title'].". ".$post['firstname']." ".$post['lastname']))."
		
		
	Congratulations! Your S-BIS membership has been created. 
	
	
	This account represents and is specific to you. We strongly recommend you don't share it with others, and encourage other users set up their own S-BIS account.
	
	
	Your membership includes:
	<ul><li>Basic Module
	(functions, benefits, etc)</li><li>Mobile
	(functions, benefits, etc)</li><li>Voyage Estimator
	(functions, benefits, etc)</li><li>You'll receive S-BIS related monthly newsletters</li></ul>
	
	
	<a target=\"_blank\" href=\"http://www.s-bisonline.com/login.php\"><span style=\"border: 2px solid #69B3E3; padding:10px; background: #D8EBF8; cursor:pointer; color:#333333; text-align: center; font-size:12px; font-family: Arial, Verdana; font-size: 14px; text-decoration:none;\">Click here to Login</span></a>
	
	
	Username: ".$post['email']."
	Password: XXXXXX
	
	
	With kind regards.
	
	
	Roy Devlin 
	CEO S-BIS a brand of  Maritime Infosys Pte Ltd
	Contact me any time roydevlin@s-bis.com</p>";

	$message_2 = stristr($post['purchase'], 'trial') ? nl2br($arr_message_2['trial']) : nl2br($arr_message_2['purchase']);
	emailBlast($from_2, $fromname_2, $subject_2, $message_2, $emails_2, $bouncereturn, 0);
}


?>