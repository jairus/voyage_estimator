<?php
session_start();
$dbhost = 's-bis.cfclysrb91of.us-east-1.rds.amazonaws.com';
$dbuser = 'sbis';
$dbpass = 'roysbis';
$dbname = 'sbis';

$conn   = mysql_connect($dbhost,$dbuser,$dbpass) or die('Error connecting to mysql');
mysql_select_db($dbname, $conn);

$abbr_code = array("93" => "AF", 
"355" => "AL", 
"213" => "DZ", 
"376" => "AD", 
"244" => "AO", 
"1 264" => "AI", 
"1 268" => "AG", 
"54" => "AR", 
"374" => "AM", 
"297" => "AW", 
"61" => "AU", 
"43" => "AT", 
"994" => "AZ", 
"1 242" => "BS", 
"973" => "BH", 
"880" => "BD", 
"1 246" => "BB", 
"375" => "BY", 
"32" => "BE", 
"501" => "BZ", 
"229" => "BJ", 
"1 441" => "BM", 
"975" => "BT", 
"591" => "BO", 
"267" => "BW", 
"55" => "BR", 
"673" => "BN", 
"359" => "BG", 
"257" => "BI", 
"855" => "KH", 
"237" => "CM", 
"1" => "CA", 
"1 345" => "KY", 
"236" => "CF", 
"235" => "TD", 
"56" => "CL", 
"86" => "CN", 
"672" => "CX", 
"57" => "CO", 
"269" => "KM", 
"242" => "CG", 
"682" => "CK", 
"506" => "CR", 
"385" => "HR", 
"53" => "CU", 
"357" => "CY", 
"42" => "CZ", 
"45" => "DK", 
"253" => "DJ", 
"1 767" => "DM", 
"593" => "EC", 
"20" => "EG", 
"503" => "SV", 
"240" => "GQ", 
"291" => "ER", 
"372" => "EE", 
"251" => "ET", 
"298" => "FO", 
"679" => "FJ", 
"358" => "FI", 
"33" => "FR", 
"594" => "GF", 
"689" => "PF", 
"241" => "GA", 
"220" => "GM", 
"995" => "GE", 
"49" => "DE", 
"233" => "GH", 
"350" => "GI", 
"30" => "GR", 
"299" => "GL", 
"1 473" => "GD", 
"590" => "GP", 
"671" => "GU", 
"502" => "GT", 
"224" => "GN", 
"592" => "GY", 
"509" => "HT", 
"504" => "HN", 
"852" => "HK", 
"36" => "HU", 
"354" => "IS", 
"91" => "IN", 
"62" => "ID", 
"964" => "IQ", 
"353" => "IE", 
"972" => "IL", 
"39" => "IT", 
"1 876" => "JM", 
"81" => "JP", 
"962" => "JO", 
"254" => "KE", 
"686" => "KI", 
"965" => "KW", 
"371" => "LV", 
"961" => "LB", 
"266" => "LS", 
"231" => "LR", 
"370" => "LT", 
"352" => "LU", 
"261" => "MG", 
"265" => "MW", 
"60" => "MY", 
"960" => "MV", 
"223" => "ML", 
"356" => "MT", 
"692" => "MH", 
"596" => "MQ", 
"222" => "MR", 
"230" => "MU", 
"269" => "YT", 
"52" => "MX", 
"377" => "MC", 
"976" => "MN", 
"1 664" => "MS", 
"212" => "MA", 
"258" => "MZ", 
"264" => "NA", 
"674" => "NR", 
"599" => "AN", 
"687" => "NC", 
"64" => "NZ", 
"505" => "NI", 
"234" => "NG", 
"47" => "NO", 
"968" => "OM", 
"92" => "PK", 
"507" => "PA", 
"675" => "PG", 
"595" => "PY", 
"51" => "PE", 
"63" => "PH", 
"48" => "PL", 
"351" => "PT", 
"1 787" => "PR", 
"974" => "QA", 
"40" => "RO", 
"250" => "RW", 
"378" => "SM", 
"966" => "SA", 
"221" => "SN", 
"248" => "SC", 
"232" => "SL", 
"65" => "SG", 
"42" => "SK", 
"386" => "SI", 
"252" => "SO", 
"27" => "ZA", 
"349" => "ES", 
"94" => "LK", 
"249" => "SD", 
"268" => "SZ", 
"46" => "SE", 
"41" => "CH", 
"7" => "TJ", 
"66" => "TH", 
"228" => "TG", 
"676" => "TO", 
"21" => "TN", 
"90" => "TR", 
"7" => "TM", 
"688" => "TV", 
"256" => "UG", 
"380" => "UA", 
"971" => "AE", 
"44" => "GB", 
"998" => "UZ", 
"678" => "VU", 
"58" => "VE", 
"967" => "YE", 
"381" => "YU", 
"260" => "ZM", 
"263" => "ZW");

foreach($_POST as $key => $value)
	$post[$key] = trim($value);

if( $_POST['trigger'] == 'update_company_details'){
	$fax_nos = $post['f_country_code']."-".$post['fax_number'];	
	$arr_data = array(	'company_name' => $post['company_name'],
						'company_name2' => $post['company_name2'],
						'company_type' => $post['company_type'],
						'address1' => $post['address1'],
						'address2' => $post['address2'],
						'address3' => $post['address3'],
						'city' => $post['city'],
						'postal_code' => $post['postal_code'],						
						'country' => $post['countryField'],
						'fax' => $fax_nos,
						'website' => $post['website'],
						'licenses' => $post['licenses'],
						'work_experience' => $post['work_experience']);
	foreach($arr_data as $key => $value){
		if( !empty($value) )
			$arr_upd_data[] = $key." = '".$value."' ";
	}
	
	$update = mysql_query("UPDATE _sbis_users SET ".implode(", ", $arr_upd_data)." WHERE id = '".$_SESSION['user']['id']."' ");
	
	$sql = mysql_query("SELECT * FROM _sbis_users WHERE id = '".$_SESSION['user']['id']."' LIMIT 1");
	$row = mysql_fetch_assoc($sql);
	$output = '<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="145" class="field">Company Name:</td>
					<td>'.$row['company_name'].'</td>
				</tr>
				<tr>
					<td width="145" class="field">Company Name2:</td>
					<td>'.$row['company_name2'].'</td>
				</tr>                
				<tr>
					<td width="145" class="field">Business Type:</td>
					<td>'.$row['company_type'].'</td>
				</tr>
				<tr>
					<td width="145" class="field">Address1:</td>
					<td>'.$row['address1'].'</td>
				</tr>
				<tr>
					<td width="145" class="field">Address2:</td>
					<td>'.$row['address2'].'</td>
				</tr>
				<tr>
					<td width="145" class="field">Address3:</td>
					<td>'.$row['address3'].'</td>
				</tr>
				<tr>
					<td width="145" class="field">City:</td>
					<td>'.$row['city'].'</td>
				</tr>
				<tr>
					<td width="145" class="field">Postal Code: </td>
					<td>'.$row['postal_code'].'</td>
				</tr>
				<tr>
					<td width="145" class="field">Country:</td>
					<td>'.$row['country'].'</td>
				</tr>
				<tr>
                  <td class="field">Fax:</td>
				  <td>'.$row['fax'].'</td>
			  </tr>
				<tr>
                  <td class="field">Website:</td>
				  <td>'.$row['website'].'</td>
			  </tr>
				<tr>
                  <td class="field">Number of Licences:</td>
				  <td>'.$row['licenses'].'</td>
			  </tr>
				<tr>
                  <td class="field">Years of Experience:</td>
				  <td>'.$row['work_experience'].'</td>
			  </tr>
			</table>';
	echo $output;
	exit;
}

if( $_POST['trigger'] == 'update_contact_details'){
	$dob = $post['month']." ".$post['day'].", ".$post['year'];
	$arr_data = array(	'title' => $post['title'],
						'firstname' => $post['firstname'],
						'lastname' => $post['lastname'],
						'gender' => $post['gender'],
						'date_of_birth' => $dob,
						'position' => $post['position'],
						'department' => $post['department'],
						'skype' => $post['skype'],
						'yahoo' => $post['yahoo'],
						'msn' => $post['msn']);
						
	foreach($arr_data as $key => $value){
		if( !empty($value) )
			$arr_upd_data[] = $key." = '".$value."' ";
	}
	
	$update = mysql_query("UPDATE _sbis_users SET ".implode(", ", $arr_upd_data)." WHERE id = '".$_SESSION['user']['id']."' ");
	
	$sql = mysql_query("SELECT * FROM _sbis_users WHERE id = '".$_SESSION['user']['id']."' LIMIT 1");
	$row = mysql_fetch_assoc($sql);
	
	$output = '<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="145" class="field">Title:</td>
					<td>'.$row['title'].'</td>
				</tr>			
				<tr>
					<td width="145" class="field">First Name:</td>
					<td>'.$row['firstname'].'</td>
				</tr>
				<tr>
					<td width="145" class="field">Last Name:</td>
					<td>'.$row['lastname'].'</td>
				</tr>				
				<tr>
					<td width="145" class="field">Gender:</td>
					<td>'.$row['gender'].'</td>
				</tr>
				<tr>
					<td width="145" class="field">Date of Birth:</td>
					<td>'.$row['date_of_birth'].'</td>
				</tr>				
				<tr>
					<td width="145" class="field">Email Address: </td>
					<td>'.$row['email'].'</td>
				</tr>
				<tr>
					<td width="145" class="field">Position:</td>
					<td>'.$row['position'].'</td>
				</tr>		
				<tr>
					<td width="145" class="field">Department</td>
					<td>'.$row['department'].'</td>
				</tr>	
				<tr>
					<td width="145" class="field">Skype ID</td>
					<td>'.$row['skype'].'</td>
				</tr>								
				<tr>
					<td width="145" class="field">Yahoo ID</td>
					<td>'.$row['yahoo'].'</td>
				</tr>								
				<tr>
					<td width="145" class="field">MSN ID</td>
					<td>'.$row['msn'].'</td>
				</tr>																													
			</table>';	
	echo $output;			
	exit;
}

if( $_POST['trigger'] == 'update_contact_numbers'){
	
	$sql = "SELECT * FROM _sbis_users WHERE id = '".$_SESSION['user']['id']."' LIMIT 1";
	$result = mysql_query($sql);
	$row = mysql_fetch_assoc($result);
	
	$phone_compress = $post['p_country_code']."-".$post['phone_number'];
	$upd_data = $row['contact_nos']."|~~|".$phone_compress;
	$update = mysql_query("UPDATE _sbis_users SET contact_nos = '".$upd_data."' WHERE id = '".$_SESSION['user']['id']."' ");
	
	$result = mysql_query($sql);
	$row = mysql_fetch_assoc($result);
	
	$ex_phone_nos = explode("|~~|", $row['contact_nos']);
	
	$output = '<table border="0" cellpadding="0" cellspacing="0">';
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
			
		if($value!=''){
		$output .= '<tr>
				<td width="30" class="field">'.$img_flag.'</td>
				<td>'.$value.' (<a class="delete-bt" onclick="phoneDelete('.($key+1).')">delete</a>)</td>
			 </tr>';
		}
	}
	$output .= '</table>';
	
	echo $output;			
	exit;
}

if( $_POST['trigger'] == 'delete_phone_number' ){
	$sql = "SELECT * FROM _sbis_users WHERE id = '".$_SESSION['user']['id']."' LIMIT 1";
	$result = mysql_query($sql);
	$row = mysql_fetch_assoc($result);
	
	$ex_phone_nos = explode("|~~|", $row['contact_nos']);
	
	foreach($ex_phone_nos as $key => $value){
		if( $key != $post['phone_key'] - 1 )
			$arr_upd_data[] = $value;
	}
	
	$update = mysql_query("UPDATE _sbis_users SET contact_nos = '".implode("|~~|", $arr_upd_data)."' WHERE id = '".$_SESSION['user']['id']."' ");
	
	$result = mysql_query($sql);
	$row = mysql_fetch_assoc($result);
	
	$ex_phone_nos = explode("|~~|", $row['contact_nos']);
	
	$output = '<table border="0" cellpadding="0" cellspacing="0">';
	$count_phones = count($ex_phone_nos);
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
		$output .= '<tr>
				<td width="30" class="field">'.$img_flag.'</td>
				<td>'.$value.' '.($count_phones > 1 ? '(<a class="delete-bt" onclick="phoneDelete('.($key+1).')">delete</a>)' : '').'</td>
			 </tr>';
	}
	$output .= '</table>';
	
	echo $output;			
	exit;	
}

if( $_POST['trigger'] == 'check_password_details' ){

	$sql = "SELECT * FROM _sbis_users WHERE id = '".$_SESSION['user']['id']."' LIMIT 1";
	$result = mysql_query($sql);
	$row = mysql_fetch_assoc($result);
	
	if( md5($post['cur_password']) == $row['password'] )
		echo 'checked';
	else
		echo 'Invalid Current Password';
		
	exit;
}

if( $_POST['trigger'] == 'update_password_details' ){

	$new_pass = mysql_query("UPDATE _sbis_users SET password = '".md5(trim($post['new_password']))."' WHERE id = '".$_SESSION['user']['id']."' LIMIT 1");
	
	echo 'Your password has been changed';		
	exit;
}

if( $_POST['trigger'] == 'upload_image1' ){

	$max_file_size = 1024 * 1024;
	
	if( $_FILES['user_photo1']['error'] == 0 && $_FILES['user_photo1']['size'] > 0 ){
	
		$dir 		= "images/user_images/";
		$validext 	= array( 'image/jpeg' => '.jpg', 'image/pjpeg' => '.jpg', 'image/gif' => '.gif', 'image/png' => '.png' );
		$mime 		= $_FILES['user_photo1']['type'];
		$ext 		= $validext[$mime]; 
	
		$filename 	= "company_".$_SESSION['user']['id'].$ext;
		$selectedfile = basename($_FILES['user_photo1']['name']);
		
		if( in_array($ext, $validext) ){
			
			if( $_FILES['user_photo1']['size'] < $max_file_size ){
	
				if( is_uploaded_file($_FILES['user_photo1']['tmp_name']) ){
					
					if( move_uploaded_file($_FILES['user_photo1']['tmp_name'], $dir.$filename) ){
						$imgsize1   = getimagesize("images/user_images/".$filename."");
						$imgwidth1  = $imgsize1[0];
						$imgheight1 = $imgsize1[1];
						
						if($imgwidth1>=$imgheight1){
							echo '<img src="images/user_images/'.$filename.'"  alt="photo" width="150" />';						
						}else{
							echo '<img src="images/user_images/'.$filename.'"  alt="photo" height="150 "/>';						
						}
					}else{
						echo 'Failed: Can\'t move uploaded file ('.$selectedfile.')';
					}
				}
				else
					echo 'Failed: Invalid file upload ('.$selectedfile.')';
					
			}
			else
				echo 'Failed: Maximum file size of 1MB exceeded!';
			
		}
		else
			echo 'Failed: Invalid file format ('.$selectedfile.')';
		
	}// end if no errors found in uploaded image
	else
		echo 'Failed: Errors found on file upload ('.$selectedfile.')';
	
	exit;
}

if( $_POST['trigger'] == 'upload_image' ){

	$max_file_size = 1024 * 1024;
	
	if( $_FILES['user_photo']['error'] == 0 && $_FILES['user_photo']['size'] > 0 ){
	
		$dir 		= "images/user_images/";
		$validext 	= array( 'image/jpeg' => '.jpg', 'image/pjpeg' => '.jpg', 'image/gif' => '.gif', 'image/png' => '.png' );
		$mime 		= $_FILES['user_photo']['type'];
		$ext 		= $validext[$mime]; 
	
		$filename 	= $_SESSION['user']['id'].$ext;
		$selectedfile = basename($_FILES['user_photo']['name']);
		
		if( in_array($ext, $validext) ){
			
			if( $_FILES['user_photo']['size'] < $max_file_size ){
	
				if( is_uploaded_file($_FILES['user_photo']['tmp_name']) ){
					
					if( move_uploaded_file($_FILES['user_photo']['tmp_name'], $dir.$filename) ){
						$imgsize   = getimagesize("images/user_images/".$filename."");
						$imgwidth  = $imgsize[0];
						$imgheight = $imgsize[1];
						
						if($imgwidth>=$imgheight){
							echo '<img src="images/user_images/'.$filename.'"  alt="photo" width="150" />';						
						}else{
							echo '<img src="images/user_images/'.$filename.'"  alt="photo" height="150 "/>';						
						}
					}else{
						echo 'Failed: Can\'t move uploaded file ('.$selectedfile.')';
					}
				}
				else
					echo 'Failed: Invalid file upload ('.$selectedfile.')';
					
			}
			else
				echo 'Failed: Maximum file size of 1MB exceeded!';
			
		}
		else
			echo 'Failed: Invalid file format ('.$selectedfile.')';
		
	}// end if no errors found in uploaded image
	else
		echo 'Failed: Errors found on file upload ('.$selectedfile.')';
	
	exit;
}

if( $_POST['trigger'] == 'update_miscellaneous_details' ){

	$upd_data = isset($post['newsletters']) ? 'yes' : 'no';
	$update = mysql_query("UPDATE _sbis_users SET subscribe_newsletter = '".$upd_data."' WHERE id = '".$_SESSION['user']['id']."' ");
	
	$sql = "SELECT * FROM _sbis_users WHERE id = '".$_SESSION['user']['id']."' LIMIT 1";
	$result = mysql_query($sql);
	$row = mysql_fetch_assoc($result);
	
	$output = '<table border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td width="220" class="field">Subscribe to our Newsletter:</td>
						<td>'.ucfirst($row['subscribe_newsletter']).'</td>
					</tr>			
				</table>';
	echo $output;
	exit;           
}

if( $_POST['trigger'] == 'update_membership_details' ){
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
								
	foreach($arr_memberships as $value){
		if( isset($post['member_'.$value]) )
			$arr_data['member_'.$value] = 'yes';
		else
			$arr_data['member_'.$value] = 'no';
	}
	
	foreach($arr_data as $key => $value){
		if( !empty($value) )
			$arr_upd_data[] = $key." = '".$value."' ";
	}
	
	$update = mysql_query("UPDATE _sbis_users SET ".implode(", ", $arr_upd_data)." WHERE id = '".$_SESSION['user']['id']."' ");
	
	$sql = mysql_query("SELECT * FROM _sbis_users WHERE id = '".$_SESSION['user']['id']."' LIMIT 1");
	$row = mysql_fetch_assoc($sql);
	$count = 0;
	
	$output = '<table border="0" cellpadding="0">';	
	foreach($arr_memberships as $value){
		if( $row['member_'.$value] == 'yes' ){
			$output .= '<tr>
							<td width="50" valign="center"><img width="30" height="30" src="images/'.$arr_member_img[$value].'" align="absmiddle" /></td>
							<td class="field">'.$arr_member_names[$value].'</td>
						</tr>';
			$count++;
		}
	}
	$output .= '</table>';
	
	if( $count == 0 )
		$output = 'no memberships';	
	
	echo $output;
	exit;
}

echo 'Failed: Nothing happened';
exit;
?>
