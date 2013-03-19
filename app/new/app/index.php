<?php
@session_start();
include_once(dirname(__FILE__)."/includes/bootstrap.php");

if($_POST['email']){
	$sql = "select * from `_sbis_users` where `email`='".mysql_escape_string($_POST['email'])."'
	and `password`='".md5($_POST['password'])."'";
	$r = dbQuery($sql);
	
	if( !is_array($r) || count($r) == 0 ){
		$result = mysql_query($sql);
		$r = array();
		while( $row = mysql_fetch_assoc($result) )
			array_push($r, $row);
	}
	
	if($r[0]['email']){
		if($r[0]['activated']){
			$_SESSION['user'] = $r[0];
			$_SESSION['user']['uid'] = $r[0]['id'];

			if(isset($_POST['setcookie'])){
				setcookie("cookie_email", $r[0]['email'], time()+60*60*24*100, "/");
      			setcookie("cookie_password", $r[0]['password'], time()+60*60*24*100, "/");
			}

			redirectjs("search.php");
			exit();
		}else{
			$from = "mailer@s-bisonline.com";
			$fromname = "S-BIS Mailer";
			$bouncereturn = "mailer@s-bisonline.com"; //where the email will forward in cases of bounced email
			$subject = "S-BIS Account Activation";
			$emails = array();
			$email = array();
			$email['email'] = $r[0]['email'];
			$email['name'] = $r[0]['firstname']." ".$r[0]['lastname'];
			$emails[] = $email;
			$email['email'] = "roydevlin@s-bis.com";
			$email['name'] = "Roy Devlin";
			$emails[] = $email;
			
			$message = "
			Dear ".strtoupper($r[0]['firstname'])."

			Welcome to S-BIS. Please click or visit the link below to activate your account;
			
			<a href='http://www.s-bisonline.com/registration.php?activate=".base64_encode(md5($r[0]['id']))."'>http://www.s-bisonline.com/registration.php?activate=".base64_encode(md5($r[0]['id']))."</a>
	
			The information you provided has been processed and you have been granted FULL access to the S-BIS portal - The trial will expire in SEVEN days.
			
			Please use the E-mail address and password that you registered to log in the system.
			
			If you would like to trial as a group, please e-mail roydevlin@s-bis.com.
			 
			As I’m sure you appreciate data-protection and the security of personal and business intelligence is an absolute priority and we use the latest data-tagging technology to ensure the information we provide is not abused in any way. You will be reassured to learn that any attempt to compromise this portal can be dealt with swiftly and effectively. Access to the S-BIS portal is provided on trust and the integrity of the user is expected and respected.
			 
			I am confident you will find the service provided by S-BIS a refreshing change from the norm and would appreciate any feedback or suggestions you may have. Enjoy the trial and we look forward to hearing from you.
			 
			With kind regards.
			 
			Roy Devlin
			CEO
			roydevlin@s-bis.com
			";

			$message = nl2br($message);			
			
			$r = emailBlast($from, $fromname, $subject, $message, $emails, $bouncereturn, 0); //last parameter for running debug	
			
			$_SESSION['loginerror'] = "Account is not activated. Please see your email and click the activation link.";
			$_SESSION['emaillogin'] = $_POST['email'];
			redirectjs("/login.php");
			exit();
		}
	}else{
		$_SESSION['loginerror'] = "Invalid Login.";
		$_SESSION['emaillogin'] = $_POST['email'];
		redirectjs("/login.php");
		exit();
	}
}else{
	redirectjs("search.php");
}
?>