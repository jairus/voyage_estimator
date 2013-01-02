<?php
include_once(dirname(__FILE__)."/includes/bootstrap.php");

$account = new account();

$dbhost = 's-bis.cfclysrb91of.us-east-1.rds.amazonaws.com';
$dbuser = 'sbis';
$dbpass = 'roysbis';
$dbname = 'sbis';

$conn   = mysql_connect($dbhost,$dbuser,$dbpass) or die('Error connecting to mysql');
mysql_select_db($dbname, $conn);

$sql = mysql_query("SELECT * FROM _sbis_users WHERE id = '".$_SESSION['user']['id']."' LIMIT 1 ");
$row = mysql_fetch_assoc($sql);

$fax = explode("-",$row['fax']);

$ext = array('.jpg', '.gif', '.png');
foreach($ext as $value){
	if( file_exists("images/user_images/company_".$_GET['id'].$value) ){
		$photo1 = "company_".$_GET['id'].$value;
		//break;
	}
	
	if( file_exists("images/user_images/".$_GET['id'].$value) ){
		$photo = $_GET['id'].$value;
		//break;
	}
}

$photo1 = empty($photo1) ? 'default.jpg' : $photo1;
$photo  = empty($photo) ? 'default.jpg' : $photo;

$arr_memberships = array('bim', 'imo', 'gmsc', 'iaito', 'issa', 'imo2', 'intertanko', 'ics');
$arr_member_names = array(	'bim'	=> 'Baltic International Maritime',
							'imo'	=> 'International Maritime Organization',
							'gmsc'	=> 'Global Mobile Satellite Communcations',
							'iaito'	=> 'The International Association of Independent Tanker Owners',
							'issa'	=> 'International Ship Suppliers Association',
							'imo2'	=> 'IMO',
							'intertanko' => 'INTERTANKO',
							'ics'	=> 'Institute of Chartered ShipBrokers');
							
$arr_member_img = array(	'bim'	=> 'logo_baltic_international_maritime.gif',
							'imo'	=> 'logo_international_maritime_organization.gif',
							'gmsc'	=> 'logo_global_mobile_satelite_communication.gif',
							'iaito'	=> 'logo_the_international_association_of_independent_tanker_owner.gif',
							'issa'	=> 'logo_internation_ship_suppliers_association.gif',
							'imo2'	=> 'logo_imo2.jpg',
							'intertanko' => 'logo_intertanko.JPG',
							'ics'	=> 'logo_institute_of_chartered_shipbrokers.JPG');
							
$m_sql = mysql_query("SELECT * FROM _sbis_users WHERE id = '".$_GET['id']."' LIMIT 1");
$m_row = mysql_fetch_assoc($m_sql);

echo $m_t;

if($_GET['ajax']){
	$action = $_GET['action'];
	if($action=='upload'){
		if($_FILES['image']['tmp_name']){
			$dest = dirname(__FILE__)."/images/user_images/".$_SESSION['user']['id'].".jpg";
			createThumb($_FILES['image']['tmp_name'], $dest, 500, 500);
			?>
			<script>
			window.parent.jQuery('#theimage').attr("src", "images/user_images/<?php echo $_SESSION['user']['id'].".jpg?t=".time(); ?>");
			window.parent.jQuery("#uploading").hide();
			window.parent.jQuery("#imageuploadform").show();
			window.parent.jQuery("#fileimage").val("");
			</script>
			<? 
		}
	}
	else if($action=='edit'){
		$post = $_POST;
		$account->updateAccount($post);
	}
	else if($action=='adduser'){
		$post = $_POST;
		$r = $account->addUser($post['adduserid']);
		if($r=='awaiting'){
			echo "Awaiting Network Confirmation";
		}
	}
	else if($action=='confirmuser'){
		$post = $_POST;
		$r = $account->confirmUser($post['adduserid']);
		if($r=='confirmed'){
			echo "Network Connection Confirmed";
		}
	}	
	else if($action=='removeuser'){
		$post = $_POST;
		$r = $account->removeUser($post['adduserid']);
		if(!$r){
			echo "<input type='button' value='Add to my Network' onclick='".$_GET['alpha']."addUser(".$post['adduserid'].")' style='border:1px solid #47a5e7; background-color:white; padding:3px 11px; cursor:pointer;'>";
		}
	}
	else if($action=='searchuser'){
		$post = $_POST;
		
		if(strlen(trim($post['searchfor']))<3){
			?>Invalid Search Parameter<?
			exit();
		}
		
		$r = $account->searchUsers($post['search_cat'], $post['country'], $post['searchfor']);
		$t = count($r);
		
		if($t){
			for($i=0; $i<$t; $i++){
				echo "<form id='adduser".$r[$i]['id']."'><table cellpadding='0' cellspacing='0'>";
				echo "<input type='hidden' name='adduserid' value=\"".htmlentities($r[$i]['id'])."\">";
				$in = $account->inMyNetwork($r[$i]['id']);
				$imagefile = dirname(__FILE__)."/images/user_images/".$r[$i]['id'].".jpg";
				if(file_exists($imagefile)){
					$image = "<img src='image.php?p=".base64_encode($imagefile)."&mx=50&account=1'>";
				}
				else{
					$imagefile = dirname(__FILE__)."/images/user_images/default.jpg";
					$image = "<img src='image.php?p=".base64_encode($imagefile)."&mx=50&account=1'>";
				}
				
				if(!$in){
					echo "<tr>
						<td>
							<table width='100%' border='0' cellspacing='0' cellpadding='0'>
								<tr>
									<td width='60'>".$image."</td>
									<td class='person' width='140'><a href='cargospotter.php?action=accountview&id=".$r[$i]['id']."' class='clickable2' target='_blank'>".$r[$i]['firstname']." ".$r[$i]['lastname']."</a></td>
									<td class='status' width='260' align='right'>
										<div id='addbutt".$r[$i]['id']."'><input type='button' value='add to my network' onclick='addUser(".$r[$i]['id'].")' style='border:1px solid #47a5e7; background-color:white; padding:3px 11px; cursor:pointer;'></div>
									</td>
								</tr>
								<tr>
									<td colspan='3' style='padding-top:5px;'></td>
								</tr>
							</table>
						</td>
					</tr>";
				}else if($in=='confirmed'){
					echo "<tr>
						<td>
							<table width='100%' border='0' cellspacing='0' cellpadding='0'>
								<tr>
									<td width='60'>".$image."</td>
									<td class='person' width='140'><a href='cargospotter.php?action=accountview&id=".$r[$i]['id']."' class='clickable2' target='_blank'>".$r[$i]['firstname']." ".$r[$i]['lastname']."</a></td>
									<td class='status' width='260' align='right'>
										<div id='addbutt".$r[$i]['id']."'>In Network <input type='button' value='remove from network' onclick='removeUser(".$r[$i]['id'].")' style='border:1px solid #47a5e7; background-color:white; padding:3px; cursor:pointer;'></div>
									</td>
								</tr>
								<tr>
									<td colspan='3' style='padding-top:5px;'></td>
								</tr>
							</table>
						</td>
					</tr>";
				}else if($in=='awaiting'){
					echo "<tr>
						<td>
							<table width='100%' border='0' cellspacing='0' cellpadding='0'>
								<tr>
									<td width='60'>".$image."</td>
									<td class='person' width='140'><a href='cargospotter.php?action=accountview&id=".$r[$i]['id']."' class='clickable2' target='_blank'>".$r[$i]['firstname']." ".$r[$i]['lastname']."</a></td>
									<td class='status' width='260' align='right'>
										<div id='addbutt".$r[$i]['id']."'>Awaiting Network Confirmation</div>
									</td>
								</tr>
								<tr>
									<td colspan='3' style='padding-top:5px;'></td>
								</tr>
							</table>
						</td>
					</tr>";
				}else if($in=='confirm'){
					echo "<tr>
						<td>
							<table width='100%' border='0' cellspacing='0' cellpadding='0'>
								<tr>
									<td width='60'>".$image."</td>
									<td class='person' width='140'><a href='cargospotter.php?action=accountview&id=".$r[$i]['id']."' class='clickable'>".$r[$i]['firstname']." ".$r[$i]['lastname']."</a></td>
									<td class='status' width='260' align='right'>
										<div id='addbutt".$r[$i]['id']."'><input type='button' value='confirm to add' onclick='confirmUser(".$r[$i]['id'].")' style='border:1px solid #47a5e7; background-color:white; padding:3px; cursor:pointer;'></div>
									</td>
								</tr>
								<tr>
									<td colspan='3' style='padding-top:5px;'></td>
								</tr>
							</table>
						</td>
					</tr>";
				}
				echo "</table></form>";
			}
			
		}
		else{
			echo "No Results";
		}
	}
	exit();
}
?>
<script>
function editAccount(){
	jQuery.ajax({
	  type: 'POST',
	  url: "account_ext_ve.php?ajax=1&action=edit",
	  data:  jQuery("#accountform").serialize(),
	  success: function(data) {
			jQuery("#savena").hide();
			jQuery("#savena").fadeIn(200);
	  }
	});
}
function searchUser(){
	jQuery('#searchresults').html("Searching...");
	jQuery.ajax({
	  type: 'POST',
	  url: "account_ext_ve.php?ajax=1&action=searchuser",
	  data: jQuery("#usersearchform").serialize(),
	  success: function(data) {
		jQuery('#searchresults').html(data);
	  }
	});
}
function addUser(id){
	jQuery('#addbutt'+id).html("Adding...");
	jQuery('#addbutt2'+id).html("Adding...");
	jQuery.ajax({
	  type: 'POST',
	  url: "account_ext_ve.php?ajax=1&action=adduser",
	  data: jQuery("#adduser"+id).serialize(),
	  success: function(data) {
		jQuery('#addbutt'+id).html(data);
		jQuery('#addbutt2'+id).html(data);
	  }
	});
}
function confirmUser(id){
	jQuery('#addbutt'+id).html("Confirming...");
	jQuery('#addbutt2'+id).html("Confirming...");
	jQuery.ajax({
	  type: 'POST',
	  url: "account_ext_ve.php?ajax=1&action=confirmuser",
	  data: jQuery("#adduser"+id).serialize(),
	  success: function(data) {
		jQuery('#addbutt'+id).html(data);
		jQuery('#addbutt2'+id).html(data);
	  }
	});
}
function nconfirmUser(id){
	jQuery('#naddbutt'+id).html("Confirming...");
	jQuery('#naddbutt'+id).html("Confirming...");
	jQuery.ajax({
	  type: 'POST',
	  url: "account_ext_ve.php?ajax=1&action=confirmuser&alpha=n",
	  data: jQuery("#nadduser"+id).serialize(),
	  success: function(data) {
		jQuery('#naddbutt'+id).html(data);
	  }
	});
}
function removeUser(id){
	jQuery('#addbutt'+id).html("Removing...");
	jQuery('#addbutt2'+id).html("Removing...");
	jQuery.ajax({
	  type: 'POST',
	  url: "account_ext_ve.php?ajax=1&action=removeuser",
	  data: jQuery("#adduser"+id).serialize(),
	  success: function(data) {
		jQuery('#addbutt'+id).html(data);
		jQuery('#addbutt2'+id).html(data);
	  }
	});
}
function nremoveUser(id){
	jQuery('#naddbutt'+id).html("Removing...");
	jQuery.ajax({
	  type: 'POST',
	  url: "account_ext_ve.php?ajax=1&action=removeuser&alpha=n",
	  data: jQuery("#nadduser"+id).serialize(),
	  success: function(data) {
		jQuery('#naddbutt'+id).html(data);
	  }
	});
}
function naddUser(id){
	jQuery('#naddbutt'+id).html("Adding...");
	jQuery.ajax({
	  type: 'POST',
	  url: "account_ext_ve.php?ajax=1&action=adduser&alpha=n",
	  data: jQuery("#nadduser"+id).serialize(),
	  success: function(data) {
		jQuery('#naddbutt'+id).html(data);
	  }
	});
}
function aconfirmUser(id){
	jQuery('#aaddbutt'+id).html("Confirming...");
	jQuery.ajax({
	  type: 'POST',
	  url: "account_ext_ve.php?ajax=1&action=confirmuser&alpha=a",
	  data: jQuery("#aadduser"+id).serialize(),
	  success: function(data) {
	  	checkAlerts_init();
		jQuery('#aaddbutt'+id).html(data);
	  }
	});
}
function aremoveUser(id){
	jQuery('#aaddbutt'+id).html("Declining...");
	jQuery.ajax({
	  type: 'POST',
	  url: "account_ext_ve.php?ajax=1&action=removeuser&alpha=a",
	  data: jQuery("#aadduser"+id).serialize(),
	  success: function(data) {
		//jQuery('#aaddbutt'+id).html(data);
		checkAlerts_init();
		jQuery('#aadduser'+id).fadeOut(200);
	  }
	});
}
</script>
<script type="text/javascript" src="js/lightbox.js"></script>
<style>
#account{
	margin-top:30px;
}
#accountform .label{
	padding:10px;
	width:100px;
}
#accountform .value{
	padding:10px;
}
#savena{
	display:none;
	padding:3px;
	background:green;
	color:white;
	margin-left:10px;
}
#mynetwork{
	
}
#network{
	margin-top:20px;
}
#mynetlabel{
	font-weight:bold;
	margin-bottom:20px;
}
#usersearchform{
	font-weight:bold;
	margin-bottom:20px;
}
.person{
	font-weight:bold;
	padding:5px;
	width:200px;
}
.status{
	padding:5px;
}

#alerts{
	margin-top:20px;
}
#alertslabel{
	font-weight:bold;
	margin-bottom:10px;
}


#profile{
	font: 10pt Arial, Helvetica, sans-serif;
}

#profile h1{
	padding: 0;
}

#profile .box{
	float: left;
	width: 450px;
}

#profile .profile-table{
	font-size: 10pt;
}

#profile .field{
	padding-top: 2px;
	padding-bottom: 2px;
	font-weight: bold;
	font-size: 10pt;
}

#profile .tbox, #profile .sbox, #profile .tbox50, #profile .tbox155{
	border: 1px solid #69B3E3;
	padding: 4px;
}

#profile .tbox{
	width: 250px;
}

#profile .tbox50{
	width: 30px;
}

#profile .tbox155{
	width: 145px;
}


#profile .sbox{
	width: 260px;
}
	
#profile hr{
	height: 1px;
}
		
#profile .profile-head{
	font-size: 13.5pt;
	font-weight: bold;
	line-height: 3em;
}

#profile .action-bt, #profile .delete-bt{
	cursor: pointer;
	color: #0000ff;
	text-decoration: underline;
}

#profile .change-pw-bt, #profile .save-bt, #profile .cancel-bt, #profile .upload-bt{
	font-size: 11px;
	font-weight: bold;
	margin-top: 15px;
}

#profile .edit-profile-table{
	display: none;
}	

#table-my-searches{
	border-bottom: 1px solid #000000;
}

#table-password-details td, .edit-profile-table td, #table-my-searches td{
	padding: 3px;
}

#edit-miscellaneous-details .field, #view-miscellaneous-details .field{
	line-height: 1.8em;
}

#profile .required{
	color: #ff0000;
}

.container1 {
	display: block;
}
#countryField {
	display: block;
}
#helper {
	display: block;
	position: absolute;
	left: 0;
	top: 0;
}
#helper a {
	display: block;
	width: 100%;
	padding: 10px;
	border-bottom: solid 1px #999;
	border-left: solid 1px #999;
	border-right: solid 1px #999;
	font-size: 12px;
	color: #000;
	text-decoration: none;
	background: #FFF;
}
#helper a:hover {
	background: #999;
	color: #FFF;
}

#modal-overlay{
	left: 0;
	top: 0;
	padding:0;
	margin:0;
	position:absolute; 
	background: #ffffff; 
	opacity: 0.3; 
	filter:Alpha(Opacity=30);	
	text-align: center;
	display: none;
	font-size: 2em;
	z-index:12;
}

#lightbox{
	background-color:#eee;
	padding:10px;
	border-bottom:2px solid #666;
	border-right:2px solid #666;
}
#lightboxDetails{font-size:0.8em; padding-top: 0.4em;}	
#lightboxCaption{float:left;}
#keyboardMsg{float:right;}
#closeButton{top:5px; right: 5px;}
#lightbox img{border:none; clear: both;} 
#overlay img{border:none;}
#overlay{background-image:url(images/overlay.png);}
* html #overlay{
	background-color:#333;
	background-color:transparent;
	background-image:url(images/blank.gif);
	filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src="images/overlay.png", sizingMethod="scale");
}
</style>
<table border=0>
    <tr>
        <td style='padding:20px;'>
            <div id="content_wrapper" style='margin-bottom:50px'>
                <div id="content_main">
                    <?php
					if($action=='admin'){
						include_once('admin_ext.php');
					}else if($action=='account'){
                        include_once('account_ext_ve_1.php');
                    }else if($action=='network'){
					?>
                    <div id='mynetwork'>
                    	<table width="980" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td style="padding-bottom:5px;"><h1><b>My Network</b></h1></td>
                            </tr>
                            <tr>
                                <td style="padding-bottom:10px;"><b>For security purpose, the data sharing process is as follows:</b></td>
                            </tr>
                            <tr>
                                <td style="padding-bottom:15px;">
                                	<div>1. Select the user you wish to share with.</div>
                                    <div>2. Double click the name of the user and to add your network.</div>
                                    <div>3. The user will now appear in your "My Connections" and you are now sharing all of your data with that user.</div>
                                    <div>4. If you wish to recieve their data as well, they will have to add you to their "My Connections" list.</div>
                                    <div>5. If you wish to stop sharing data with a particular user, double click the name of the user to remove from your network.</div>
                                    <div>6. Data being shared is everything except Private Remarks (which are notes to self only).</div>
                                    <div>7. This process ensure complete security and allows each user to control who see what data.</div>
                                </td>
                            </tr>
                            <tr>
                                <td><h2><b>Search</b></h2></td>
                            </tr>
                            <tr>
                                <td style="padding-bottom:10px;">
                                	<form id='usersearchform' onsubmit='searchUser(); return false;'>
                                        <div><input type="radio" name="search_cat" value="company_name" /> Company Name</div>
                                        <div style="padding-bottom:5px;"><input type="radio" name="search_cat" value="name" checked="checked" /> Individual Name</div>
                                        <div style="padding-bottom:5px;">
                                        	<select name="country">
                                            	<option value="">ALL COUNTRIES</option>
                                            	<?php
												$sql = mysql_query("SELECT name FROM _countries ORDER BY name");
												while($row = mysql_fetch_assoc($sql)){
													echo "<option value=".trim($row['name']).">".$row['name']."</option>";
												}
												?>
                                            </select>
                                        </div>
                                        <div><input type='text' name='searchfor' style="width:200px;"> <input type='button' onclick='searchUser()' value='submit' style='border:1px solid #47a5e7; background-color:white; padding:3px; cursor:pointer;'></div>
                                    </form>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-bottom:10px;">
                                	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    	<tr>
                                        	<td width="490" valign="top" style="padding-right:10px;">
                                            	<div><h2><b>Search Results</b></h2></div>
                                                <div id='searchresults'>search for results...</div>
                                            </td>
                                            <td width="490" valign="top" style="padding-left:10px;">
                                            	<div><h2><b>Current Network</b></h2></div>
                                                <?php
												$r = $account->getNetwork();
												$t = count($r);
												
												if($t){
													$title = false;
												
													for($i=0; $i<$t; $i++){
														echo "<form id='nadduser".$r[$i]['id']."'>
														<input type='hidden' name='adduserid' value=\"".htmlentities($r[$i]['id'])."\">";
														
														$in = $account->inMyNetwork($r[$i]['id']);
														$imagefile = dirname(__FILE__)."/images/user_images/".$r[$i]['id'].".jpg";
														
														if(file_exists($imagefile)){
															$image = "<img src='image.php?p=".base64_encode($imagefile)."&mx=50&account=1'>";
														}else{
															$imagefile = dirname(__FILE__)."/images/user_images/default.jpg";
															$image = "<img src='image.php?p=".base64_encode($imagefile)."&mx=50&account=1'>";
														}
														
														if($in=='confirmed'){
															echo "<table width='100%' border='0' cellspacing='0' cellpadding='0'>
																<tr>
																	<td width='60'>".$image."</td>
																	<td class='person' width='140'><a href='cargospotter.php?action=accountview&id=".$r[$i]['id']."' class='clickable2' target='_blank'>".$r[$i]['firstname']." ".$r[$i]['lastname']."</a></td>
																	<td class='status' width='290' align='right'>
																		<div id='naddbutt".$r[$i]['id']."'>In Network <input type='button' value='remove from network' onclick='nremoveUser(".$r[$i]['id'].")' style='border:1px solid #47a5e7; background-color:white; padding:3px; cursor:pointer;'></div>
																	</td>
																</tr>
																<tr>
																	<td colspan='3' style='padding-top:5px;'></td>
																</tr>
															</table>";
														}
													}
												}else{
													echo 'No Network';
												}
												?>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <?php
                    }else if($action=='alerts'){
						?>
						<div id='alerts'>
						<?php
						$r = $account->getNetworkRequests();
						$t = count($r);
						
						if($t){
							echo "<div id='alertslabel'>Network Requests</div>";
							for($i=0; $i<$t; $i++){
								$imagefile = dirname(__FILE__)."/images/user_images/".$r[$i]['id'].".jpg";
								
								if(file_exists($imagefile)){
									$image = "<img src='image.php?p=".base64_encode($imagefile)."&mx=50&account=1'>";
								}else{
									$imagefile = dirname(__FILE__)."/images/user_images/default.jpg";
									$image = "<img src='image.php?p=".base64_encode($imagefile)."&mx=50&account=1'>";
								}	
								
								echo "<form id='aadduser".$r[$i]['id']."'>
									<table cellpadding='0' cellspacing='2'>
										<input type='hidden' name='adduserid' value=\"".htmlentities($r[$i]['id'])."\">
										<tr>
											<td valign='top'>$image</td>
											<td valign='top' class='person'><a href='cargospotter.php?action=accountview&id=".$r[$i]['id']."'>".$r[$i]['firstname']." ".$r[$i]['lastname']."</a></td>
											<td valign='top' class='status'>
												<div id='aaddbutt".$r[$i]['id']."'>
													<input type='button' value='Confirm to Add' onclick='aconfirmUser(".$r[$i]['id'].")'>
													<input type='button' value='Decline' onclick='aremoveUser(".$r[$i]['id'].")'>
												</div>
											</td>
										</tr>
									</table>
								</form>";
							}
						}else{
							echo "You have no alerts.";
						}
						?></div><?php
                    }else if($action=='accountview'){
						$in = $account->inMyNetwork($_GET['id']);
						
						if($in=='confirmed'){
						?>
						<div id="profile">
                        	<h1><strong>Profile</strong></h1>
                        	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td width="50%" valign="top">
                                	<div class="box">
                                    <div id="modal-overlay"></div>
                                        <p><span class="profile-head">Company Details</span></p>
                                        <div class="profile-table" id="view-company-details">
                                            <table border="0" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td width="145" class="field">Company Name:</td>
                                                    <td><?=$m_row['company_name']?></td>
                                                </tr>
                                                <tr>
                                                    <td width="145" class="field">Company Name2:</td>
                                                    <td><?=$m_row['company_name2']?></td>
                                                </tr>                
                                                <tr>
                                                    <td width="145" class="field">Business Type:</td>
                                                    <td><?=$m_row['company_type']?></td>
                                                </tr>
                                                <tr>
                                                    <td width="145" class="field">Address1:</td>
                                                    <td><?=$m_row['address1']?></td>
                                                </tr>
                                                <tr>
                                                    <td width="145" class="field">Address2:</td>
                                                    <td><?=$m_row['address2']?></td>
                                                </tr>
                                                <tr>
                                                    <td width="145" class="field">Address3:</td>
                                                    <td><?=$m_row['address3']?></td>
                                                </tr>
                                                <tr>
                                                    <td width="145" class="field">City:</td>
                                                    <td><?=$m_row['city']?></td>
                                                </tr>
                                                <tr>
                                                    <td width="145" class="field">Postal Code: </td>
                                                    <td><?=$m_row['postal_code']?></td>
                                                </tr>
                                                <tr>
                                                    <td width="145" class="field">Country:</td>
                                                    <td><?=$m_row['country']?></td>
                                                </tr>
                                                <tr>
                                                  <td class="field">Fax:</td>
                                                  <td><?=$m_row['fax']?></td>
                                              </tr>
                                                <tr>
                                                  <td class="field">Website:</td>
                                                  <td><?=$m_row['website']?></td>
                                              </tr>
                                                <tr>
                                                  <td class="field">Number of Licences:</td>
                                                  <td><?=$m_row['licenses']?></td>
                                              </tr>
                                                <tr>
                                                  <td class="field">Years of Experience:</td>
                                                  <td><?=$m_row['work_experience']?></td>
                                              </tr>
                                            </table>
                                        </div>
                                        <p><span class="profile-head">Contact Details</span></p>
                                        <div class="profile-table" id="view-contact-details">
                                            <table border="0" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td width="145" class="field">Title:</td>
                                                    <td><?=$m_row['title']?></td>
                                                </tr>			
                                                <tr>
                                                    <td width="145" class="field">First Name:</td>
                                                    <td><?=$m_row['firstname']?></td>
                                                </tr>
                                                <tr>
                                                    <td width="145" class="field">Last Name:</td>
                                                    <td><?=$m_row['lastname']?></td>
                                                </tr>				
                                                <tr>
                                                    <td width="145" class="field">Gender:</td>
                                                    <td><?=$m_row['gender']?></td>
                                                </tr>
                                                <tr>
                                                    <td width="145" class="field">Date of Birth:</td>
                                                    <td><?=$m_row['date_of_birth']?></td>
                                                </tr>				
                                                <tr>
                                                    <td width="145" class="field">Email Address: </td>
                                                    <td><?=$m_row['email']?></td>
                                                </tr>
                                                <tr>
                                                    <td width="145" class="field">Position:</td>
                                                    <td><?=$m_row['position']?></td>
                                                </tr>		
                                                <tr>
                                                    <td width="145" class="field">Department</td>
                                                    <td><?=$m_row['department']?></td>
                                                </tr>		
                                                <tr>
                                                    <td width="145" class="field">Skype ID</td>
                                                    <td><?=$m_row['skype']?></td>
                                                </tr>								
                                                <tr>
                                                    <td width="145" class="field">Yahoo ID</td>
                                                    <td><?=$m_row['yahoo']?></td>
                                                </tr>								
                                                <tr>
                                                    <td width="145" class="field">MSN ID</td>
                                                    <td><?=$m_row['msn']?></td>
                                                </tr>																		
                                            </table>
                                        </div>
                                        <p><span class="profile-head">Contact Numbers </span></p>
                                        <div class="profile-table" id="view-contact-numbers">
                                            <?php
                                                $ex_phone_nos = explode("|~~|", $m_row['contact_nos']);
                                                echo '<table border="0" cellpadding="0" cellspacing="0">';
                                                foreach($ex_phone_nos as $key => $value){
                                                    $ex_phone = explode("-", $value);
                                                    if( !empty($abbr_code[$ex_phone[0]]) ){
                                                        $flag_code = strtolower($abbr_code[str_replace(" ", "", $ex_phone[0])]);
                                                        if( file_exists("images/flags_png/".$flag_code.".png") )
                                                            $img_flag = '<img src="images/flags_png/'.$flag_code.'.png" />';
                                                        else
                                                            $img_flag = '&nbsp;';
                                                    }
                                                    else
                                                        $img_flag = '&nbsp;';
                                                    echo '<tr>
                                                            <td width="30" class="field">'.$img_flag.'</td>
                                                            <td>'.$value.'</td>
                                                         </tr>';
                                                }
                                                echo '</table>';
                                            ?>
                                        </div>
                                    </div>
                                </td>
                                <td width="50%" valign="top">
                                	<div class="box">
                                    <p><span class="profile-head">Company Logo</span></p>
                                    <div class="profile-table" id="view-upload-image1">
                                        <?php
                                        if( file_exists("images/user_images/".$photo1."") ){
                                            $imgsize1   = getimagesize("images/user_images/".$photo1."");
                                            $imgwidth1  = $imgsize1[0];
                                            $imgheight1 = $imgsize1[1];
                                        }
                                        ?>
                                        <p>
                                            <?php
                                            if($imgwidth1>=$imgheight1){
                                                echo '<a href="images/user_images/'.$photo1.'" rel="lightbox" title="photo" style="outline:none;"><img id="your-photo" src="images/user_images/'.$photo1.'" width="150" alt="photo" border="0" /></a>';
                                            }else{
                                                echo '<a href="images/user_images/'.$photo1.'" rel="lightbox" title="photo" style="outline:none;"><img id="your-photo" src="images/user_images/'.$photo1.'"height="150" alt="photo" border="0" /></a>';
                                            }
                                            ?>
                                        </p>
                                    </div>
                                    <p><span class="profile-head">Profile Photo</span></p>
                                    <div class="profile-table" id="view-upload-image">
                                        <?php
                                        if( file_exists("images/user_images/".$photo."") ){
                                            $imgsize   = getimagesize("images/user_images/".$photo."");
                                            $imgwidth  = $imgsize[0];
                                            $imgheight = $imgsize[1];
                                        }
                                        ?>
                                        <p>
                                            <?php
                                            if($imgwidth>=$imgheight){
                                                echo '<a href="images/user_images/'.$photo.'" rel="lightbox" title="photo" style="outline:none;"><img id="your-photo" src="images/user_images/'.$photo.'" width="150" alt="photo" border="0" /></a>';
                                            }else{
                                                echo '<a href="images/user_images/'.$photo.'" rel="lightbox" title="photo" style="outline:none;"><img id="your-photo" src="images/user_images/'.$photo.'"height="150" alt="photo" border="0" /></a>';
                                            }
                                            ?>
                                        </p>
                                    </div>
                                    <p><span class="profile-head">Membership</span></p>
                                    <div class="profile-table" id="view-membership-details">
                                        <?php
                                        $count = 0;
    
                                        $table_memberships = '<table border="0" cellspacing="0">';	
                                        foreach($arr_memberships as $value){
                                            if( $m_row['member_'.$value] == 'yes' ){
                                                $table_memberships .= '<tr>
                                                                <td width="50" valign="center"><img width="30" height="30" src="images/'.$arr_member_img[$value].'" align="absmiddle" /></td>
                                                                <td class="field">'.$arr_member_names[$value].'</td>
                                                            </tr>';
                                                $count++;
                                            }
                                        }
                                        $table_memberships .= '</table>';
                                        
                                        echo $table_memberships;
                                        ?>
                                    </div>
                                </td>
                              </tr>
                            </table>
                        </div>
						<?php
						}else{
						?>
                        <div id="profile">
                        	<h1><strong>Profile</strong></h1>
                        	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td width="50%" valign="top">
                                	<div class="box">
                                    <div id="modal-overlay"></div>
                                        <p><span class="profile-head">Company Details</span></p>
                                        <div class="profile-table" id="view-company-details">
                                            <table border="0" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td width="145" class="field">Company Name:</td>
                                                    <td><?=$m_row['company_name']?></td>
                                                </tr>
                                                <tr>
                                                    <td width="145" class="field">Company Name2:</td>
                                                    <td><?=$m_row['company_name2']?></td>
                                                </tr>
                                            </table>
                                        </div>
                                        <p><span class="profile-head">Contact Details</span></p>
                                        <div class="profile-table" id="view-contact-details">
                                            <table border="0" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td width="145" class="field">Title:</td>
                                                    <td><?=$m_row['title']?></td>
                                                </tr>			
                                                <tr>
                                                    <td width="145" class="field">First Name:</td>
                                                    <td><?=$m_row['firstname']?></td>
                                                </tr>
                                                <tr>
                                                    <td width="145" class="field">Last Name:</td>
                                                    <td><?=$m_row['lastname']?></td>
                                                </tr>				
                                                <tr>
                                                    <td width="145" class="field">Gender:</td>
                                                    <td><?=$m_row['gender']?></td>
                                                </tr>																		
                                            </table>
                                        </div>
                                    </div>
                                </td>
                                <td width="50%" valign="top">
                                	<div class="box">
                                    <p><span class="profile-head">Company Logo</span></p>
                                    <div class="profile-table" id="view-upload-image1">
                                        <?php
                                        if( file_exists("images/user_images/".$photo1."") ){
                                            $imgsize1   = getimagesize("images/user_images/".$photo1."");
                                            $imgwidth1  = $imgsize1[0];
                                            $imgheight1 = $imgsize1[1];
                                        }
                                        ?>
                                        <p>
                                            <?php
                                            if($imgwidth1>=$imgheight1){
                                                echo '<img id="your-photo" src="images/user_images/'.$photo1.'" width="150" alt="photo" />';
                                            }else{
                                                echo '<img id="your-photo" src="images/user_images/'.$photo1.'"height="150" alt="photo" />';
                                            }
                                            ?>
                                        </p>
                                    </div>
                                    <p><span class="profile-head">Profile Photo</span></p>
                                    <div class="profile-table" id="view-upload-image">
                                        <?php
                                        if( file_exists("images/user_images/".$photo."") ){
                                            $imgsize   = getimagesize("images/user_images/".$photo."");
                                            $imgwidth  = $imgsize[0];
                                            $imgheight = $imgsize[1];
                                        }
                                        ?>
                                        <p>
                                            <?php
                                            if($imgwidth>=$imgheight){
                                                echo '<img id="your-photo" src="images/user_images/'.$photo.'" width="150" alt="photo" />';
                                            }else{
                                                echo '<img id="your-photo" src="images/user_images/'.$photo.'"height="150" alt="photo" />';
                                            }
                                            ?>
                                        </p>
                                    </div>
                                </td>
                              </tr>
                            </table>
                        </div>
                        <?php
						}
                    }
                    ?>
				</div>
			</div>
		</td>
	</tr>
</table>