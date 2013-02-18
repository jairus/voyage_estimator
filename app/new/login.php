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

if($_GET['login_twice']){
	$login_twice_msg = "<p>YOU ARE LOGGED IN ON AN OTHER COMPUTER. IS SOMEONE ACCESSING YOUR ACCOUNT OR ARE YOU ON ANOTHER COMPUTER?<br />PLEASE NOTE WE TRACK ALL IP ADDRESSES AND COUNTRIES TO PREVENT UNAUTHORISED ACCESS AND MULTIPLE USER USAGE.</p><p>(Our license agreements with our providers require we add this to this LOGOUT PAGE.)</p>";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Ship Brokering Intelligence Solutions | AIS Live Ship Data</title>
<?php include("includehead.php"); ?>
<style>
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

#resendbutt{
	border: 2px solid #69B3E3;
	padding:10px;
	background: #D8EBF8;
	cursor:pointer;
	color:#333333;
}

#loginbutt{
	border: 2px solid #69B3E3;
	padding:5px 10px;
	background: #D8EBF8;
	cursor:pointer;
	color:#333333;
}
</style>
</head>
<body>
<div id="bodytop">
	<?php include("includesignin2.php"); ?>
	<div id="bodytopgradient"></div>
</div>
<div id="container">
	<?php include("includenavbar.php"); ?>
	<div id="maincontent">
		<div id="content">
			<h5>LOG IN</h5>
			<table style='width:100%' id='signupwrap'>
				<tr>
                	<td valign="top">
						<center>
						<table style='width:500px' id='activatesuccess'>
							<tr>
								<td>
								<?php if( $activated ){ ?>
										<div style='font-weight:bold; font-size:18px'>Account Activation Successful!</div><br>
										You may now login to your account.<br><br>
								<?php } ?>
                                
								<form method='post' action='/app/s-bislogin.php'>
                                <div style="padding:35px 0px; border:2px solid #69B3E3; -moz-border-radius:15px; border-radius:15px;">
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
                                        <?php }elseif($_GET['login_twice']){ ?>
                                        	<tr>
                                                <td class='label' colspan=2 style='text-align:left; height:auto; padding-bottom:10px;' align="center">
                                                    <div class='error' style='display:block; font-size:11px; margin:0 auto;'>
                                                        <?php echo $login_twice_msg; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        
                                        <tr>
                                            <td style="text-align:left;">YOUR EMAIL:</td>
                                        </tr>
                                        <tr>
                                            <td class='form' style="padding-bottom:10px; text-align:left;"><input class='tbox' type='text' name='email' value="<?php echo $_GET['cookie_email']; ?>">
                                            <!--<input class='tbox' type='text' name='email' value="<?php //echo htmlentities($_SESSION['emaillogin']); unset($_SESSION['emaillogin']);?>">-->
                                            </td>
                                        </tr>	
                                        <tr>
                                            <td style="text-align:left;">PASSWORD:</td>
                                        </tr>
                                        <tr>
                                            <td class='form' style="padding-bottom:15px; text-align:left;"><input class='tbox' type='password' name='password' value="<?php //echo md5($_GET['cookie_password']); ?>"></td>
                                        </tr>
                                        <tr>
                                            <td style="padding-bottom:15px; text-align:left;"><input type="checkbox" id="setcookie" name="setcookie" /> REMEMBER ME</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:left;"><input type='submit' value='LOG IN' id='loginbutt' > <a href="registration.php?forgotpassword=1">FORGOT PASSWORD?</a></td>
                                        </tr>
                                        <tr>
                                            <td class='label' style="padding:15px 0px;"><hr /></td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:center;">don't have an account? <a href="purchasefree.php">sign up for free trial</a></td>
                                        </tr>
                                    </table>
                                    </center>
                                </div>
								</form>
							</td>
						</tr>
					</table>
					</center>
                </td>
			</tr>
		</table>
		</div>
		<?php include("includesidebarterms.php"); ?>
    </div>
	<?php include("includefooter.php"); ?>
</div>
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