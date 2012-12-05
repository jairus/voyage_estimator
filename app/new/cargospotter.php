<?php
$dbhost = 's-bis.cfclysrb91of.us-east-1.rds.amazonaws.com';
$dbuser = 'sbis';
$dbpass = 'roysbis';
$dbname = 'sbis';

$conn   = mysql_connect($dbhost,$dbuser,$dbpass) or die('Error connecting to mysql');
mysql_select_db($dbname);

$activated = false;

if($_GET['activate']){
	$sql = "select * from `_sbis_users` where md5(`id`)='".base64_decode($_GET['activate'])."'";
	$r = mysql_fetch_assoc(mysql_query($sql));

	if($r['email']){
		$sql = "update `_sbis_users` set `activated`=1 where `id`='".$r['id']."'";
		mysql_query($sql);
		
		if($r['dry']==1){
			$sql1 = "INSERT INTO _network(userid1, userid2, confirmed, dateadded) VALUES ('".$r['id']."', '129', '1', '".date('Y-m-d H-i-s')."') ";
			$result1 = mysql_query($sql1) or die(mysql_error());
		}else{
			$sql1 = "INSERT INTO _network(userid1, userid2, confirmed, dateadded) VALUES ('".$r['id']."', '58', '1', '".date('Y-m-d H-i-s')."') ";
			$result1 = mysql_query($sql1) or die(mysql_error());
		}

		$activated = true;
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>CargoSpotter</title>
<?php include("includehead.php"); ?>
<style>
body{
	background-color:#262324 !important;
	font-size:11px;
}

.tbox_z{
	font-size:11px;
	background-color:#e2e1e1;
	border:1px solid #c0bfbf;
	padding:2px 3px;
	color:#414243;
	width:150px;
}

#signup .label{
	text-align: right;
	padding-top:5px;
	vertical-align:top;
}

#signup .error{
	border:1px solid red;
	width:300px;
	height:auto;
	padding:4px;
	font-size:10px;
	background-color:#FFD4D4;
	display:none;
}

#signup .tbox{
	border: 1px solid #69B3E3;
	width:300px;
	height:20px;
	padding:4px;
}

#signup .signme{
	padding:10px;
}

#signup #signmebutt{
	border: 2px solid #69B3E3;
	padding:10px;
	background: #D8EBF8;
	cursor:pointer;
	color:#333333;
}												

#signup .tbox focus{
	border: 2px solid #3997D9;
	width:300px;
	height:20px;
}

#signupwrap{
	height: 400px;
	vertical-align:top;
}

#signupsuccess{
	display:none;
}

#signupsuccess td{
	text-align:center;
}
</style>
</head>
<body>
<div style="width:581px; height:283px; background-image:url(images/bg_login.png); margin:0 auto;">
	<table width="581" cellpadding="0" cellspacing="0" border="0" style="margin-left:-5px; margin-top:50px;">
		<tr>
			<td>
			<?php if( $activated ){ ?>
					<div style='font-weight:bold; font-size:18px'>Account Activation Successful!</div><br>
					You may now login to your account.<br><br>
			<?php } ?>
			
			<form id="form_target" method='post' action='/app/cargospotterlogin.php'>
			<div style="padding:65px 0px;">
				<center>
				<table id='signup'>
					<?php if($_SESSION['loginerror']){ ?>
						<tr>
							<td class='label' colspan=2 style='text-align:center; height:auto; padding-bottom:10px;' align="center">
								<div class='error' style='display:block; font-size:11px; margin:0 auto;'>
									<?php
									echo $_SESSION['loginerror'];
									unset($_SESSION['loginerror']);
									?>
								</div>
							</td>
						</tr>
					<?php } ?>
					
					<tr>
						<td class='form' style="padding-bottom:10px; text-align:left;"><input class='tbox_z' type='text' name='email' value="email" onfocus="if(this.value=='email'){ this.value=''; }" onblur="if(this.value==''){ this.value='email'; }" />
						</td>
					</tr>
					<tr>
						<td class='form' style="padding-bottom:15px; text-align:left;"><input class='tbox_z' type='password' name='password' value="password" onfocus="if(this.value=='password'){ this.value=''; }" onblur="if(this.value==''){ this.value='password'; }" /></td>
					</tr>
				</table>
				</center>
			</div>
			</form>
			</td>
		</tr>
	</table>
</div>
<script>
$(".tbox_z").keypress(function(event) {
  if ( event.which == 13 ) {
  	$('#form_target').submit();
  }
});
</script>
<div align="center">
	<p align="left">
    	<?php
		if($_GET['cookie_email']&&$_GET['cookie_password']){
			signUpSuccess();
		}
		?>
        <script type="text/javascript">
		var insertid;

		function signUpSuccess(str){
			r = str.split("|");
			insertid = r[1];

			jQuery("#signupform").hide();
			jQuery("#signupsuccess").fadeIn(200);
		}

		function resendEmail(){
			jQuery.ajax({
			  type: 'POST',
			  url: "registration.php?resend="+insertid,
			  data:  jQuery("#signupform").serialize(),

			  success: function(data) {
				if(data.indexOf("success")!=0){

				}else{
					alert("Email Resent!");
				}

				jQuery("#resendbutt").val("Resend Activation Email");
				jQuery("#resendbutt").attr("disabled", false);					
			  }
			});	
		}

		function signUp(obj){
			jQuery.ajax({
			  type: 'POST',
			  url: "registration.php",
			  data:  jQuery("#signupform").serialize(),

			  success: function(data) {
				if(data.indexOf("success")!=0){
					eval(data);
				}else{
					signUpSuccess(data);
				}

				jQuery("#signmebutt").val("Sign Me Up");
				jQuery("#signmebutt").attr("disabled", false);					
			  }
			});
		}

		jQuery("#signmebutt").click(
			function(){
				signUp();

				jQuery("#signmebutt").val("Signing up...");
				jQuery("#signmebutt").attr("disabled", true);
			}
		);

		jQuery("#resendbutt").click(
			function(){
				resendEmail();

				jQuery("#resendbutt").val("Resending Activation Email...");
				jQuery("#resendbutt").attr("disabled", true);
			}
		);
	  </script>
</div>
</body>
</html>