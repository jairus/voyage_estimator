<? @session_start(); ?>
<div id="show-success-dialog" title="International Law" style="display:none;">Sorry due to International Law we are prohibited from supplying data to your countries.</div>
<style>
.customer_login_link{
	font-family:Arial, Helvetica, sans-serif;
	font-size:14px;
	color:#FFF;
	width:auto;
	height:auto;
	padding:5px 10px;
	background-color:#c0d92c;
	text-decoration:none;
	-moz-border-radius:5px;
	border-radius:5px;
}

.customer_login_link:hover{
	color:#333;
	background-color:#c5d957;
}

.free_trial_link{
	font-family:Arial, Helvetica, sans-serif;
	font-size:14px;
	color:#FFF;
	width:auto;
	height:auto;
	padding:5px 30px;
	background-color:#d97357;
	text-decoration:none;
	-moz-border-radius:5px;
	border-radius:5px;
}

.free_trial_link:hover{
	color:#333;
	background-color:#d93305;
}

.live_ship_position_link{
	font-family:Arial, Helvetica, sans-serif;
	font-size:14px;
	color:#FFF;
	width:auto;
	height:auto;
	padding:5px 10px;
	background-color:#0CF;
	text-decoration:none;
	-moz-border-radius:5px;
	border-radius:5px;
}

.live_ship_position_link:hover{
	color:#333;
	background-color:#0FF;
}
</style>
<div id="signin">
    <?php if(!$_SESSION['user']){ ?>
    	<div style='padding-top:60px;'>
        	<a href="login.php" class="customer_login_link">CUSTOMER LOGIN</a>
            &nbsp;
            <a href="signup.php" class="free_trial_link">FREE TRIAL</a>
            &nbsp;
            <a href="portagents.php" class="live_ship_position_link">PORT AGENTS</a>
        </div>
	<?php } ?>
</div>