<!--ACCOUNT-->
<?php
include_once(dirname(__FILE__)."/includes/bootstrap.php");

$action = $_GET['action'];

if(!$action && $user['email']=='admin@s-bisonline.com'){ $action='admin'; }
else if(!$action){ $action='account'; }

$active = 'style="border: 1px solid rgb(211, 211, 211); padding: 7px 5px 7px 10px; -moz-border-radius-topleft: 8px; -moz-border-radius-bottomleft: 8px; color:#69b6eb;" class="tab active"';

$inactive = 'style="border: 1px solid rgb(211, 211, 211); padding: 7px 0px 7px 10px; width: 142px; -moz-border-radius-topleft: 8px; -moz-border-radius-bottomleft: 8px; color:#333;" class="tab"';
?>
  <div id="bodycontainer">
    <div id="leftmenu">
      <div class="block block-sbis" id="block-sbis-0">
        <h2 class="title"></h2>
        <div class="content">
			<ul class="menu sbis-tabmenu">
            	<?php if($user['email']=='admin@s-bisonline.com'){ ?>
                <li class="sbis-tab">
				  <div <?php if($action=='admin') echo $active; else echo $inactive; ?> >
					<div id="tabtitle" class="middle" onclick="self.location='?action=admin'" style="padding:10px 0px;">Admin</div>
				  </div>
				</li>
				<p style="padding:6px 0px;"></p>
                <?php } ?>
				<li class="sbis-tab">
				  <div <?php if($action=='account') echo $active; else echo $inactive; ?> >
					<div id="tabtitle" class="middle" onclick="self.location='?action=account'" style="padding:10px 0px;">Account Settings</div>
				  </div>
				</li>
				<p style="padding:6px 0px;"></p>
				<li class="sbis-tab">
				  <div <?php if($action=='network') echo $active; else echo $inactive; ?> >
					<div class="middle" onclick="self.location='?action=network'" style="padding:10px 0px;">My Network</div>
				  </div>
				</li>
				<p style="padding:6px 0px;"></p>
				<li class="sbis-tab">
				  <div <?php if($action=='alerts') echo $active; else echo $inactive; ?> >
					<div class="middle" onclick="self.location='?action=alerts'" style="padding:10px 0px;">My Alerts</div>
				  </div>
				</li>									
			</ul>
        </div>
      </div>
    </div>
    <div id="contentarea"><?php include_once(dirname(__FILE__)."/account_ext_ve.php"); ?></div>
</div>
<!--END OF ACCOUNT-->