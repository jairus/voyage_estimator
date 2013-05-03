<?php
@include_once(dirname(__FILE__)."/includes/bootstrap.php");
@session_start();
date_default_timezone_set('UTC');

//DELETE FILE FROM DIRECTORY
function deleteFile($file){
	$success = FALSE;
	if (file_exists( $file ) && $file != "" && $file != "n/a"){
		unlink ( $file );
		$success = TRUE;
	}
	return $success;	
}
//END DELETE FILE FROM DIRECTORY

//CREATE IMAGE FROM UPLOAD
function createThumb2($src, $dest, $thumbWidth, $thumbHeight){
	$info       = pathinfo($src);
	$img        = imagecreatefromjpeg($src);
	$width      = imagesx($img);
	$height     = imagesy($img);
	$new_width  = $width;
	$new_height = $height;
	
	if($width > $height){
		if($thumbWidth < $width){
			$new_width  = $thumbWidth;
			$new_height = floor($height*($thumbWidth/$width));
		}
	}else{
		if($thumbHeight < $height){
			$new_height = $thumbHeight;
			$new_width  = floor($width*($thumbHeight/$height));
		}
	}
	
	$tmp_img = imagecreatetruecolor($new_width, $new_height);
	imagecopyresampled($tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
	imagejpeg($tmp_img, $dest);
}
//CREATE IMAGE FROM UPLOAD

if($_POST['submitok'] == 1){
	if((!empty($_FILES["company_logo"])) && ($_FILES['company_logo']['error']==0)){
		$company_logo = basename($_FILES['company_logo']['name']);
		$image_ext  = substr($company_logo, strrpos($company_logo, '.') + 1);

		
		if(($image_ext == "jpg" || $image_ext == "JPG" || $image_ext == "png" || $image_ext == "PNG" || $image_ext == "jpeg" || $image_ext == "JPEG" || $image_ext == "gif" || $image_ext == "GIF") && ($_FILES["company_logo"]["size"] < 62914560)){
			$ext = array('.jpg', '.JPG', '.gif', '.GIF', '.png', '.PNG', '.JPEG', '.jpeg');
			foreach($ext as $value){
				if( file_exists("images/agents/".$_SESSION['user']['id'].$value) ){
					$file = 'images/agents/'.$_SESSION['user']['id'].$value;
				}
			}
			deleteFile($file);
			
			$company_logo = str_replace($company_logo, $_SESSION['user']['id'].'.'.$image_ext, $company_logo);
			$newimagename = dirname(__FILE__).'/images/agents/'.$company_logo;
			
			if((move_uploaded_file($_FILES['company_logo']['tmp_name'], $newimagename))){
				$thumbWidth  = "600";
				$thumbHeight = "600";
				createThumb2($newimagename, $newimagename, $thumbWidth, $thumbHeight);
				
				header('Location: portagents.php?msg_alert=Update successful.');
			}else{
				header('Location: portagents.php?msg_alert=Update successful. But no image file to upload!');
			}
		}else{
			header('Location: portagents.php?msg_alert=Update successful. But invalid image file format or image file too large.');
		}
	}else{
		header('Location: portagents.php?msg_alert=Update successful. But no image file to upload!');
	}
}

$sql = "SELECT * FROM _port_agents WHERE id = '".$_SESSION['user']['id']."' LIMIT 1";
$r = dbQuery($sql, $link);
?>
<link href="../app/js/ui.css" rel="stylesheet" />
<style>
#signup .label{
	text-align: right;
	padding-top:5px;
	vertical-align:top;
}

#signup .error{
	border:1px solid red;
	width:300px;
	padding:4px;
	font-size:10px;
	background:#FFD4D4;
	display:none;
}

#signup .required{
	color: #ff0000;
}


#signup .tbox50, #signup .tbox155, #signup .tbox{
	border: 1px solid #69B3E3;
	height:20px;
	padding:4px;
}

#signup .tbox{
	width: 300px;
}

#signup .tbox50{
	width: 50px;
}

#signup .tbox155{
	width: 150px;
}


#signup .sbox{
	border: 1px solid #69B3E3;
	width:310px;
	padding:4px;
}

#signup .form{
	text-align:left;
	padding-left:10px;
}

#signup .signme{
	padding:10px 10px 10px 4px;
}

#signup #signmebutt{
	border: 2px solid #69B3E3;
	padding:10px;
	background: #D8EBF8;
	cursor:pointer;
	color:#333333;
}

#signup #forgotbutt{
	border: 2px solid #69B3E3;
	padding:10px;
	background: #D8EBF8;
	cursor:pointer;
	color:#333333;
}	

#signup #forgotchangebutt{
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
	padding:10px;
	background: #D8EBF8;
	cursor:pointer;
	color:#333333;
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

.z_links{
	font-family:Arial, Helvetica, sans-serif;
	font-size:14px;
	color:#69B3E3;
	text-decoration:none;
}
.z_links:hover{
	color:#03F;
	text-decoration:underline;
}

td{
	border-bottom:0px;
}

h2{
	font-size:18px;
}
</style>
<script type="text/javascript" language="javascript" src="../js/AutoCountry.js"></script>
<script language="javascript" src="../app/jquery_ui/jquery.js"></script>
<script language="javascript" src="../app/jquery_ui/ui.js"></script>
<script language="javascript">
	var country_codes = {"AFGHANISTAN" : "93", 
	"ALBANIA" : "355", 
	"ALGERIA" : "213", 
	"ANDORRA" : "376", 
	"ANGOLA" : "244", 
	"ANGUILLA" : "1 264", 
	"ANTARCTIC AUS TERRITORY" : "672", 
	"ANTIGUA AND BARBUDA" : "1 268", 
	"ANTILLES" : "599", 
	"ARGENTINA" : "54", 
	"ARMENIA" : "374", 
	"ARUBA" : "297", 
	"ASCENSION ISLAND" : "247", 
	"AUSTRALIA" : "61", 
	"AUSTRIA" : "43", 
	"AZERBAIJAN" : "994", 
	"AZORES" : "351", 
	"BAHAMAS" : "1 242", 
	"BAHRAIN" : "973", 
	"BANGLADESH" : "880", 
	"BARBADOS" : "1 246", 
	"BARBUDA AND ANTIGUA" : "1 268", 
	"BELARUS" : "375", 
	"BELGIUM" : "32", 
	"BELIZE" : "501", 
	"BENIN" : "229", 
	"BERMUDA" : "1 441", 
	"BHUTAN" : "975", 
	"BOLIVIA" : "591", 
	"BOSNIA HERCEGOVINA" : "387", 
	"BOTSWANA" : "267", 
	"BRAZIL" : "55", 
	"BRUNEI DARUSSALAM" : "673", 
	"BULGARIA" : "359", 
	"BUKINA FASO" : "226", 
	"BURMA (MYANMAR)" : "95", 
	"BURUNDI" : "257", 
	"CAMBODIA" : "855", 
	"CAMEROON" : "237", 
	"CANADA" : "1", 
	"CAPE VERDE ISLANDS" : "238", 
	"CAYMAN ISLANDS" : "1 345", 
	"C.I.S. (OLD U.S.S.R.)" : "7", 
	"CENTRAL AFRICAN REPUBLIC" : "236", 
	"CHAD" : "235", 
	"CHILE" : "56", 
	"CHINA" : "86", 
	"CHRISTMAS ISLAND" : "672", 
	"COCOS ISLAND" : "672", 
	"COLOMBIA" : "57", 
	"COMOROS" : "269", 
	"CONGO" : "242", 
	"COOK ISLANDS" : "682", 
	"COSTA RICA" : "506", 
	"COTE D'IVORIE (IVORY COAST)" : "225", 
	"CROATIA" : "385", 
	"CUBA" : "53", 
	"CYPRUS" : "357", 
	"CZECH REPUBLIC" : "42", 
	"DENMARK" : "45", 
	"DIEGO GARCIA" : "246", 
	"DJIBOUTI" : "253", 
	"DOMINICA" : "1 767", 
	"DOMINICAN REBUBLIC" : "1 809", 
	"ECUADOR" : "593", 
	"EGYPT" : "20", 
	"EL SALVADOR" : "503", 
	"EQUATORIAL GUINEA" : "240", 
	"ERITREA" : "291", 
	"ESTONIA" : "372", 
	"ETHIOPIA" : "251", 
	"FALKLAND ISLANDS" : "500", 
	"FAROE ISLANDS" : "298", 
	"FIJI" : "679", 
	"FINLAND" : "358", 
	"FRANCE" : "33", 
	"FRENCH GUIANA" : "594", 
	"FRENCH POLYNESIA" : "689", 
	"GABON" : "241", 
	"GAMBIA" : "220", 
	"GEORGIA" : "995", 
	"GERMANY" : "49", 
	"GHANA" : "233", 
	"GIBRALTAR" : "350", 
	"GREAT BRITAIN" : "44", 
	"GREECE" : "30", 
	"GREENLAND" : "299", 
	"GRENADA" : "1 473", 
	"GRENADINES" : "1 784", 
	"GUADELOUPE" : "590", 
	"GUAM" : "671", 
	"GUATEMALA" : "502", 
	"GUINEA" : "224", 
	"GUINEA - BISSAU" : "245", 
	"GUYANA" : "592", 
	"HAITI" : "509", 
	"HOLLAND (NETHERLANDS)" : "31", 
	"HONDURAS" : "504", 
	"HONG KONG" : "852", 
	"HUNGARY" : "36", 
	"ICELAND" : "354", 
	"INDIA" : "91", 
	"INDONESIA" : "62", 
	"IRAN" : "98", 
	"IRAQ" : "964", 
	"IRELAND" : "353", 
	"ISRAEL" : "972", 
	"ITALY" : "39", 
	"IVORY COAST (COTE D'IVORIE)" : "225", 
	"JAMAICA" : "1 876", 
	"JAPAN" : "81", 
	"JORDAN" : "962", 
	"KAZAKHSTAN" : "7", 
	"KENYA" : "254", 
	"KIRGHIZSTAN" : "7", 
	"KIRIBATI" : "686", 
	"KOREA (NORTH)" : "850", 
	"KOREA (SOUTH)" : "82", 
	"KUWAIT" : "965", 
	"LAOS" : "856", 
	"LATVIA" : "371", 
	"LEBANON" : "961", 
	"LESOTHO" : "266", 
	"LIBERIA" : "231", 
	"LIBYA" : "218", 
	"LICHTENSTEIN" : "423", 
	"LITHUANIA" : "370", 
	"LUXEMBOURG" : "352", 
	"MACAO" : "853", 
	"MACEDONIA" : "389", 
	"MADAGASCAR" : "261", 
	"MALAWI" : "265", 
	"MALAYSIA" : "60", 
	"MALDIVES" : "960", 
	"MALI" : "223", 
	"MALTA" : "356", 
	"MARSHALL ISLANDS" : "692", 
	"MARTINIQUE" : "596", 
	"MAURITANIA" : "222", 
	"MAURITIUS" : "230", 
	"MAYOTTE" : "269", 
	"MEXICO" : "52", 
	"MICRONESIA" : "691", 
	"MOLDOVIA" : "373", 
	"MONACO" : "377", 
	"MONGOLIA" : "976", 
	"MONTSERRAT" : "1 664", 
	"MOROCCO" : "212", 
	"MOZAMBIQUE" : "258", 
	"MYANMAR (BURMA)" : "95", 
	"NAMIBIA" : "264", 
	"NAURU" : "674", 
	"NAPAL" : "977", 
	"NETHERLANDS (HOLLAND)" : "31", 
	"NETHERLANDS ANTILLES" : "599", 
	"NEVIS (ST KITTS)" : "1 869", 
	"NEW CALEDONIA" : "687", 
	"NEW GUINEA (PAPUA)" : "675", 
	"NEW ZEALAND" : "64", 
	"NICARAGUA" : "505", 
	"NIGER REPUBLIC" : "227", 
	"NIGERIA" : "234", 
	"NORWAY" : "47", 
	"OMAN" : "968", 
	"PAKISTAN" : "92", 
	"PANAMA" : "507", 
	"PAPUA NEW GUINEA" : "675", 
	"PARAGUAY" : "595", 
	"PERU" : "51", 
	"PHILIPPINES" : "63", 
	"PITCAIN ISLAND" : "649", 
	"POLAND" : "48", 
	"PORTUGAL" : "351", 
	"PUERTO RICO" : "1 787", 
	"QATAR" : "974", 
	"ROMANIA" : "40", 
	"RUSSIA" : "7", 
	"RWANDA" : "250", 
	"ST HELENA" : "290", 
	"ST KITTS AND NEVIS" : "1 869", 
	"ST LUCIA" : "1 758", 
	"ST VINCENT" : "1 784", 
	"SAMOA (USA)" : "685", 
	"SAMOA (WESTERN)" : "685", 
	"SAN MARINO" : "378", 
	"SAUDI ARABIA" : "966", 
	"SENEGAL" : "221", 
	"SEYCHELLES" : "248", 
	"SIERRA LEONE" : "232", 
	"SINGAPORE" : "65", 
	"SLOVAKIA" : "42", 
	"SLOVENIA" : "386", 
	"SOLOM ISLANDS" : "677", 
	"SOMALIA" : "252", 
	"SOUTH AFRICA" : "27", 
	"SPAIN" : "349", 
	"SRI LANKA" : "94", 
	"SUDAN" : "249", 
	"SURINAM" : "597", 
	"SWAZILAND" : "268", 
	"SWEDEN" : "46", 
	"SWITZERLAND" : "41", 
	"SYRIA" : "963", 
	"TAIWAN" : "886", 
	"TAJIKISTAN" : "7", 
	"TANZANIA" : "255", 
	"THAILAND" : "66", 
	"TOGO" : "228", 
	"TONGA" : "676", 
	"TRINIDAD & TOBAGO" : "1 868", 
	"TUNISIA" : "21", 
	"TURKEY" : "90", 
	"TURKMENISTAN" : "7", 
	"TURKS & CAICOS ISLANDS" : "1 649", 
	"TUVALU" : "688", 
	"UGANDA" : "256", 
	"UKRAINE" : "380", 
	"UNITED ARAB EMIRATES" : "971", 
	"UNITED KINGDOM" : "44", 
	"URAGUAY" : "598", 
	"UNITED STATES OF AMERICA" : "1", 
	"UZBEKISTAN" : "998", 
	"VANUATU" : "678", 
	"VATICAN CITY" : "39", 
	"VENEZUELA" : "58", 
	"VIETNAM" : "84", 
	"VIRGIN ISLANDS (BVI)" : "1 284", 
	"VIRGIN ISLANDS (USVI)" : "1 340", 
	"YEMEN" : "967", 
	"YUGOSLAVIA" : "381", 
	"ZAIRE (CONGO)" : "243", 
	"ZAMBIA" : "260", 
	"ZIMBABWE" : "263"};		
	$(function(){				
		$('#dob').datepicker({
			changeMonth: true,
			changeYear: true,
			height: 200,
			minDate: '1/1/1930',
			maxDate: '12/31/1983'
		});
		$('#show-signup-success-dialog').dialog({
			width:600,
			autoOpen: false,
			modal: true,
			close: function(){
				location.href = 'index.php';
			},
			buttons: {
				Ok: function(){
					location.href = 'index.php';		
				}
			}
		});	
		$('#signmebutt').click(function(){
			var error_count = 0;
			var email_pattern = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
			
			//fields validation
			arr_fields = ['first_name', 'last_name', 'email', 'pass1', 'pass2', 'company_name', 'address', 'city', 'postal_code', 'countryField', 'services'];
			$.each(arr_fields, function(index, value){
				if( $('#' + value).val() == '' ){
					if( value == 'email' )
						$('#error_' + value).text('Please Input Email');
					$('#error_' + value).show();
					error_count++;
				}
				else
					$('#error_' + value).hide();
			});
			
			//email validation
			if( $('#email').val() != '' && !(email_pattern.test($('#email').val())) ){
				$('#error_email').text('Invalid Email');
				$('#error_email').show();
				error_count++;
			}
			else if( $('#email').val() != '' ){
				check_email = $.ajax({
					url: 'signup_ajax2.php',
					data: {"trigger": "email_check", "email": $('#email').val()},
					async: false
				}).responseText;
				
				if( check_email == 'email found' ){
					$('#error_email').text('Email is already registered in our system');
					$('#error_email').show();
					error_count++;
				}
			}
			
			//password validation
			if( $('#pass1').val() != $('#pass2').val() ){
				$('#error_pass2').text('Passwords does not match');
				$('#error_pass2').show();
				error_count++;
			}
			
			//phone number validation
			phone_fields = ['p_country_code', 'p_area_code', 'phone_number'];
			var phone_error_count = 0;
			$.each(phone_fields, function(index, value){
				if( $('#' + value).val() == '' )
					phone_error_count++;
			});
			
			if( phone_error_count > 0 ){
				$('#error_phone').show();
				error_count++;
			}
			else
				$('#error_phone').hide();
			
			//change position of autocomplete country	
			var c = $('#countryField');
			var offset = c.offset();
			var c_height = $('#countryField').innerHeight();
			$('#helper').css('top', function(index){
				return (offset.top + c_height + 2);
			});
			
			//validate captcha
			if( $('#captcha_code').val() == '' ){
				$('#error_captcha_code').text('Please Input Captcha Code');
				$('#error_captcha_code').show();
				$('#captcha_image').attr('src', '../captcha/CaptchaSecurityImages.php?width=100&height=40&characters=5');								
				error_count++;
			}
			else{
				if( error_count == 0 ){
					check_captcha = $.ajax({
						url: 'signup_ajax2.php',
						data: {"trigger": "validate_captcha", "code": $('#captcha_code').val()},
						async: false
					}).responseText;
					
				}
				else{
					$('#captcha_image').attr('src', '../captcha/CaptchaSecurityImages.php?width=100&height=40&characters=5');
					$('#captcha_code').val('');
					$('#error_captcha_code').text('Please type the code from image above');
					$('#error_captcha_code').show();
				}
			}
			//alert('Errors found: ' + error_count);
			if( error_count == 0 ){
				jQuery('#pleasewait').show();
				//saving to dabase && send to email;
				$.post('signup_ajax2.php', $('#signupform').serializeArray(), function(data){
					var submitok = 1;
				
					document.signupform.submit();
					//alert('You have successfully updated your account');
				});
			}
			else
				alert('Errors found: ' + error_count);
			return false;
		});	
		
		$('#p_area_code').keypress(function(){
			if( this.value.length == 4 )
				$('#phone_number').focus();
		});
		
		$('#p_area_code, #f_area_code').keyup(function(){
			phone_pattern = /([^0-9]+)/i;
			current = this.value.length;
			if( phone_pattern.test(this.value.substr( (current-1) )) ){
				str = this.value.substr(0, (current-1));
				$(this).val( str );
			}						
		});
		
		$('#f_area_code').keypress(function(){
			if( this.value.length == 4 )
				$('#fax_number').focus();
		});				
		
		$('#print_form').click(function(){
			window.print();
		});
		
		AC.init("countryField");											
		
	});	
	
	function getCountryCode(fieldvalue){
		var c_value = fieldvalue;
		//var c_code = country_codes[c_value];
		var c_code = '';
		for(key in country_codes){
			if( key == c_value )
				c_code = country_codes[key];
		}					
		//alert(c_code);
		$('#p_country_code').val(c_code);
		$('#f_country_code').val(c_code);				
		return true;
	}			
</script>
<script type='text/javascript' src='../app/js/jquery-autocomplete/lib/jquery.bgiframe.min.js'></script>
<script type='text/javascript' src='../app/js/jquery-autocomplete/lib/jquery.ajaxQueue.js'></script>
<script type='text/javascript' src='../app/js/jquery-autocomplete/lib/thickbox-compressed.js'></script>
<script type='text/javascript' src='../app/js/jquery-autocomplete/jquery.autocomplete.js'></script>
<script type='text/javascript' src='../app/js/ports.php'></script>
<link rel="stylesheet" type="text/css" href="../app/js/jquery-autocomplete/jquery.autocomplete.css" />
<link rel="stylesheet" type="text/css" href="../app/js/jquery-autocomplete/lib/thickbox.css" />
<form id='signupform' name='signupform' method="post" enctype="multipart/form-data">
<input type="hidden" name="trigger" value="save_new_user"  />
<input type='hidden' name='id' id="id" value="<?php echo $r[0]['id']; ?>">
<div style="float:left; width:500px; height:auto;">                                
<table style='width:500px' id="signup">
	<?php if(isset($_GET['msg_alert'])){ ?>
	<tr>
		<td colspan="2" style="color:#FF0000; font-size:16px; font-weight:bold;"><?php echo $_GET['msg_alert']; ?></td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<?php } ?>						
	<tr>
		<td colspan="2"><h2>Personal  Details</h2></td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td class='label'><span class="required">*</span>First Name:</td>
		<td class='form'>
			<input class='tbox' type='text' name='first_name' id="first_name" value="<?php echo $r[0]['first_name']; ?>">
			<div class='error' id='error_first_name'>Please Input First Name</div></td>
	</tr>					
	<tr>
		<td class='label'><span class="required">*</span>Last Name:</td>
		<td class='form'>
			<input class='tbox' type='text' name='last_name' id="last_name" value="<?php echo $r[0]['last_name']; ?>">
			<div class='error' id='error_last_name'>Please Input Last Name</div></td>
	</tr>
	<tr>
		<td class='label'>Office Number:</td>
		<td class='form'><input class='tbox' type='text' name='office_number' id="office_number" value="<?php echo $r[0]['office_number']; ?>"></td>
	</tr>
	<tr>
		<td class='label'>Mobile Number:</td>
		<td class='form'><input class='tbox' type='text' name='mobile_number' id="mobile_number" value="<?php echo $r[0]['mobile_number']; ?>"></td>
	</tr>
	<tr>
		<td class='label'>Fax Number:</td>
		<td class='form'><input class='tbox' type='text' name='fax_number1' id="fax_number1" value="<?php echo $r[0]['fax_number']; ?>"></td>
	</tr>
	<tr>
		<td class='label'>Telex:</td>
		<td class='form'><input class='tbox' type='text' name='telex_number' id="telex_number" value="<?php echo $r[0]['telex']; ?>"></td>
	</tr>
	<tr>
		<td class='label'><span class="required">*</span>Email</td>
		<td class='form'>
			<input class='tbox' type='text' name='email' id="email" value="<?php echo $r[0]['email']; ?>" />
			<div class='error' id='error_email'>Please Input Email</div></td>
	</tr>
	<tr>
		<td class='label'><span class="required">*</span>Password</td>
		<td class='form'>
			<input class='tbox' type='password' name='pass1' id="pass1" value="<?php echo $r[0]['password']; ?>" />
			<div class='error' id='error_pass1'>Please Input Password</div></td>
	</tr>
	<tr>
		<td class='label'><span class="required">*</span>Confirm Password</td>
		<td class='form'>
			<input class='tbox' type='password' name='pass2' id="pass2" value="<?php echo $r[0]['password']; ?>" />
			<div class='error' id='error_pass2'>Please Confirm Password</div></td>
	</tr>
	<tr>
		<td class='label'>Skype:</td>
		<td class='form'><input class='tbox' type='text' name='skype' id="skype" value="<?php echo $r[0]['skype']; ?>"></td>
	</tr>
	<tr>
		<td class='label'>Yahoo:</td>
		<td class='form'><input class='tbox' type='text' name='yahoo' id="yahoo" value="<?php echo $r[0]['yahoo']; ?>"></td>
	</tr>
	<tr>
		<td class='label'>MSN:</td>
		<td class='form'><input class='tbox' type='text' name='msn' id="msn" value="<?php echo $r[0]['msn']; ?>"></td>
	</tr>
</table>
</div>
<div style="float:left; width:500px; height:auto;">
<table style='width:500px' id="signup">
	<tr>
		<td colspan="2"><h2>Company Details</h2></td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td class='label'><span class="required">*</span>Company Name:</td>
		<td class='form'>
			<input name="company_name" type='text' class='tbox' id="company_name" value="<?php echo $r[0]['company_name']; ?>">
			<div class='error' id='error_company_name'>Please Input Company Name</div></td>
	</tr>
	<tr>
		<td class='label'><span class="required">*</span>Address:</td>
		<td class='form'>
			<input class='tbox' type='text' name="address" id="address" value="<?php echo $r[0]['address']; ?>">
			<div class='error' id='error_address'>Please Input Address</div></td>
	</tr>
	<tr>
		<td class='label'><span class="required">*</span>City:</td>
		<td class='form'>
			<input class='tbox' type='text' name="city" id="city" value="<?php echo $r[0]['city']; ?>">
			
			<script type="text/javascript">
			jQuery("#city").focus().autocomplete(ports);
			jQuery("#city").setOptions({
				scrollHeight: 180
			});
			</script>
			<div class='error' id='error_city'>Please Input City</div></td>
	</tr>
	<tr>
		<td class='label'><span class="required">*</span>Zip / Postal Code:</td>
		<td class='form'>
			<input class='tbox' type='text' name="postal_code" id="postal_code" value="<?php echo $r[0]['postal_code']; ?>">
			<div class='error' id='error_postal_code'>Please Input Zip / Postal Code</div></td>
	</tr>
	<tr>
		<td class='label'><span class="required">*</span>Country:</td>
		<td class='form'>
			<div class="container1"><input class='tbox' type='text' id="countryField" name="countryField" value="<?php echo $r[0]['country']; ?>"></div>
			<div class='error' id='error_countryField'>Please Input Country</div></td>
	</tr>
	<tr>
		<td class='label'>Fax Number</span></td>
		<td class='form'><input name='fax_number2' type='text' class='tbox155' id="fax_number2" value="<?php echo $r[0]['fax']; ?>" /></td>
	</tr>
	<tr>
		<td class='label'>Website</span></td>
		<td class='form'><input name='website' type='text' class='tbox155' id="website" value="<?php echo $r[0]['website']; ?>" /></td>
	</tr>
	<tr>
		<td class='label' valign="top"><span class="required">*</span>Services:</td>
		<td class='form'>
			<textarea class='tbox' name="services" id="services" style="height:50px;"><?php echo $r[0]['services']; ?></textarea>
			<div class='error' id='error_services'>Please Input Services</div></td>
	</tr>
	<tr>
		<td class='label'>Company Logo</td>
		<td class='form'><input name='company_logo' type='file' class='tbox' id="company_logo_id" /></td>
	</tr>
	<tr>
		<td class='label'>&nbsp;</td>
		<td class='form'>
			<?php
			$ext = array('.jpg', '.JPG', '.gif', '.GIF', '.png', '.PNG', '.JPEG', '.jpeg');
			foreach($ext as $value){
				if( file_exists("images/agents/".$_SESSION['user']['id'].$value) ){
					$company_logo = 'images/agents/'.$_SESSION['user']['id'].$value;
				}
			}
			?>
			<img src="<?php echo $company_logo; ?>" width="300" />
		</td>
	</tr>
	<tr>
		<td class='label'>&nbsp;</td>
		<td class='form'></td>
	</tr>										
	<tr>
		<td class='label'>&nbsp;</td>
		<td class='form'><img src="../captcha/CaptchaSecurityImages.php?width=100&height=40&characters=5" id="captcha_image" /></td>
	</tr>
	<tr>
		<td class='label'>&nbsp;</td>
		<td class='form'><b>This code is case sensitive</b></td>
	</tr>
	<tr>
		<td class='label'>Image code:</td>
		<td class='form'>
			<input class='tbox' type='text' name="captcha_code" id="captcha_code">
			<div class='error' id='error_captcha_code'>Please Input Captcha Code</div></td>
	</tr>									
	<tr>
		<td class='signme'>&nbsp;</td>
		<td class='signme'>
			<span class="signme">
				<input type="hidden" name="submitok" value="1">
				<input type='button' name="signmebutt" value='Update' id='signmebutt' />
			</span></td>
	</tr>
</table>
</div>
</form>