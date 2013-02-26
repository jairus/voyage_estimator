<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>S-BIS | Sign-up</title>
		<?php include("includehead.php"); ?>
        <link href="app/js/ui.css" rel="stylesheet" />
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
		</style>
        <script type="text/javascript" language="javascript" src="js/AutoCountry.js"></script>
		<script language="javascript" src="app/jquery_ui/jquery.js"></script>
        <script language="javascript" src="app/jquery_ui/ui.js"></script>
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
					/*
					$('.tbox:lt(5)').each(function(index){
						if( this.value == '' ){
							$('#error' + index).show();
							error_count++;
						}
						else if( index == 4 && !(email_pattern.test(this.value)) ){
							$('#error4').text('Invalid Email');
							$('#error4').show();
							error_count++;
						}
						else
							$('#error' + index).hide();
					});
					*/
					
					//fields validation
					arr_fields = ['firstname', 'lastname', 'email', 'pass1', 'pass2', 'title', 'position', 'company_name', 'company_type', 'address_1', 'city', 'postal_code', 'countryField', 'agreement'];
					$.each(arr_fields, function(index, value){
						if( $('#' + value).val() == '' ){
							if( value == 'email' )
								$('#error_' + value).text('Please Input Email');
							if( value == 'pass2' )
								$('#error_' + value).text('Please Confirm Password');
							$('#error_' + value).show();
							error_count++;
						}
						else
							$('#error_' + value).hide();
					});
					
					if($('input[@name=agreement]:checked').size() == 0){
						$('#error_agreement').show();
						error_count++;
					}else{
						$('#error_agreement').hide();
					}
					
					//email validation
					if( $('#email').val() != '' && !(email_pattern.test($('#email').val())) ){
						$('#error_email').text('Invalid Email');
						$('#error_email').show();
						error_count++;
					}
					else if( $('#email').val() != '' ){
						check_email = $.ajax({
							url: 'signup_ajax.php',
							data: {"trigger": "email_check", "email": $('#email').val()},
							async: false
						}).responseText;
						
						if( check_email == 'email found' ){
							$('#error_email').text('Email is already registered in our system');
							$('#error_email').show();
							error_count++;
						}
						/*
						$.get('signup_ajax.php', {"trigger": "email_check", "email" : $('#email').val()}, function(data){
							if( data == 'email found' ){
								$('#error_email').text('Email is already registered in our system');
								$('#error_email').show();
								error_count++;
							}
						});
						*/
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
						$('#captcha_image').attr('src', 'captcha/securimage_show.php?sid=' + Math.random());								
						error_count++;
					}
					else{
						if( error_count == 0 ){
							check_captcha = $.ajax({
								url: 'signup_ajax.php',
								data: {"trigger": "validate_captcha", "code": $('#captcha_code').val()},
								async: false
							}).responseText;
							
							/*
							$.post('signup_ajax.php', {"trigger" : "validate_captcha", "code" : $('#captcha_code').val()}, function(data){
								if( data != 'code accepted' ){
									$('#error_captcha_code').text('Invalid Code');
									$('#error_captcha_code').show();
									$('#captcha_image').attr('src', 'captcha/securimage_show.php?sid=' + Math.random());								
									error_count++;
								}
							});
							*/
						}
						else{
							$('#captcha_image').attr('src', 'captcha/securimage_show.php?sid=' + Math.random());
							$('#captcha_code').val('');
							$('#error_captcha_code').text('Please type the code from image above');
							$('#error_captcha_code').show();
						}
					}
					//alert('Errors found: ' + error_count);
					if( error_count == 0 ){
						//saving to dabase && send to email;
						$.post('signup_ajax.php', $('#signupform').serializeArray(), function(data){
							$('#show-signup-success-dialog').dialog("open");
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
	</head>
	<body>
		<div id="bodytop">
		  <?php include("includesignin.php"); ?>
			<div id="bodytopgradient"></div>
		</div>
		<div id="container">
			<?php include("includenavbar.php"); ?>
            <div id="show-signup-success-dialog" title="Purchase Request" style="display:none;">
                <p style="padding-bottom:20px;">Thank You for choosing Ship Brokering Intelligence Solutions</p>
                <p style="padding-bottom:20px;">You will be receiving our invoice shortly and in the meantime please make the most of S-BIS and find the Right Ship at the Right Rate at the Right Time. Right now!</p>
                <p style="padding-bottom:30px;">
                Sincerely,<br />
                Roy Devlin <br />
                CEO S-BIS a brand of  Maritime Infosys Pte Ltd<br />
                Contact me any time roydevlin@s-bis.com
                </p>
                <p>Your request has been sent.<br />You will be redirected to home page...</p>
            </div>
			<div id="maincontent">
                <!--<table width='880'>
                    <tr>
                      <td valign="top" style='padding-top:20px; padding-bottom:20px;' class="label"><b><?php //echo $_GET['purchase']." ".$_GET['category']." - ".$_GET['type']."<sup>TM</sup>"; ?></b></td>
                    </tr>
                </table>-->
                <table width='880'>
                    <tr>
                      <td valign="top" class="label">&nbsp;</td>
                    </tr>
                </table>
                <div style="width:836px; height:auto; padding:20px 20px 10px; border:2px solid #69B3E3; -moz-border-radius:15px; border-radius:15px;"><h6 style="color:#000;"><b><p>No downloads. No software to install.</p><p>Register now and for 7 days you will have access to all the features and functions of the most comprehensive ship search intelligence tool on the market today. See for yourself why S-BIS is the talk of the industry.</b></h6></div>
                <table width='880'>
                    <tr>
                        <td valign="top" style='padding-top:20px' >
                            <div style="float:left; width:500px; height:auto;">
                                <form id='signupform' method="post">
                                    <input type="hidden" name="category" value="<?=$_GET['category']?>"  />
                                    <input type="hidden" name="type" value="<?=$_GET['type']?>"  />
                                    <input type="hidden" name="purchase" value="<?=$_GET['purchase']?>"  />
                                    <input type="hidden" name="trigger" value="save_new_user"  />                                    
                                    <table style='width:500px' id="signup">
                                        <tr>
                                            <td class="label">&nbsp;</td>
                                            <td class='form'><span id="print_form" style="float: right; margin-right: 15px; font-size: 11px; color: #0000ff; cursor:pointer;"><img src="app/images/print.jpg" align="absmiddle" alt="print this form" title="print this form" /></span></td>
                                        </tr>									
                                        <tr>
                                            <td class="label"><strong>Personal  Details </strong></td>
                                            <td class='form'>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td class='label'><span class="required">*</span>First Name:</td>
                                            <td class='form'>
                                                <input class='tbox' type='text' name='firstname' id="firstname">
                                                <div class='error' id='error_firstname'>Please Input First Name</div></td>
                                        </tr>					
                                        <tr>
                                            <td class='label'><span class="required">*</span>Last Name:</td>
                                            <td class='form'>
                                                <input class='tbox' type='text' name='lastname' id="lastname">
                                                <div class='error' id='error_lastname'>Please Input Last Name</div></td>
                                        </tr>
                                        <tr>
                                            <td class='label'><span class="required">*</span>Title</td>
                                            <td class='form'>
                                                <select class="sbox" name="title" id="title">
                                                    <option value="">Select Title</option>                                                
                                                    <option value="Mr">Mr.</option>
                                                    <option value="Mrs">Mrs.</option>
                                                    <option value="Mrs">Ms.</option>     
                                                    <option value="Sir">Sir</option>                                                         
                                                    <option value="Dr">Dr.</option>
                                                    <option value="Capt">Capt.</option>
                                                </select>
                                                <div class='error' id='error_title'>Please Input Title</div></td>
                                        </tr>
                                        <tr>
                                            <td class='label'><span class="required">*</span>Position</td>
                                            <td class='form'>
                                                <input class='tbox' type='text' name='position' id="position" />
                                                <div class='error' id='error_position'>Please Input Position</div></td>								
                                        </tr>
                                        <tr>
                                            <td class='label'><span class="required">*</span>Email</td>
                                            <td class='form'>
                                                <input class='tbox' type='text' name='email' id="email" />
                                                <div class='error' id='error_email'>Please Input Email</div></td>
                                        </tr>
                                        <tr>
                                            <td class='label'><span class="required">*</span>Password</td>
                                            <td class='form'>
                                                <input class='tbox' type='password' name='pass1' id="pass1" />
                                                <div class='error' id='error_pass1'>Please Input Password</div></td>
                                        </tr>
										<tr>
                                            <td class='label'><span class="required">*</span>Confirm Password</td>
                                            <td class='form'>
                                                <input class='tbox' type='password' name='pass2' id="pass2" />
                                                <div class='error' id='error_pass2'>Please Confirm Password</div></td>
                                        </tr>
                                        <?php if($_GET['purchase']!="Trial Account (7 Days Trial Account)"){ ?>										
                                        <tr>
                                            <td class='label'>Department</td>
                                            <td class='form'>
                                                <select id="department" name="department" class="sbox">
                                                    <option value="Accounting">Accounting</option>
                                                    <option value="Accounts and HR">Accounts and HR</option>
                                                    <option value="Administration">Administration</option>
                                                    <option value="Administration and Finance">Administration and Finance</option>
                                                    <option value="Agency">Agency</option>
                                                    <option value="Agency & Chartering Department">Agency & Chartering Department</option>
                                                    <option value="Agency and Operations">Agency and Operations</option>
                                                    <option value="Auditing Department">Auditing Department</option>
                                                    <option value="Back Office">Back Office</option>
                                                    <option value="Break Bulk">Break Bulk</option>
                                                    <option value="Bulk carriers">Bulk carriers</option>
                                                    <option value="Bulk Chartering">Bulk Chartering</option>
                                                    <option value="Bulk Control">Bulk Control</option>
                                                    <option value="Bulk Department">Bulk Department</option>
                                                    <option value="Bulk Operation Team">Bulk Operation Team</option>
                                                    <option value="Bulk operations">Bulk operations</option>
                                                    <option value="Bulk Planning Team">Bulk Planning Team</option>
                                                    <option value="Bulk Sales">Bulk Sales</option>
                                                    <option value="Bunker Department ">Bunker Department </option>
                                                    <option value="Business">Business</option>
                                                    <option value="Business Administration">Business Administration</option>
                                                    <option value="Business Affairs Team">Business Affairs Team</option>
                                                    <option value="Business Development">Business Development</option>
                                                    <option value="Chartering">Chartering</option>
                                                    <option value="Chartering and Operations">Chartering and Operations</option>
                                                    <option value="Chartering and Project">Chartering and Project</option>
                                                    <option value="Claims">Claims</option>
                                                    <option value="Claims and Insurance">Claims and Insurance</option>
                                                    <option value="Claims and Legal">Claims and Legal</option>
                                                    <option value="Commercial">Commercial</option>
                                                    <option value="Commercial & Operations">Commercial & Operations</option>
                                                    <option value="Commercial & Strategy">Commercial & Strategy</option>
                                                    <option value="commercial and claims handling">commercial and claims handling</option>
                                                    <option value="Commercial and Marketing">Commercial and Marketing</option>
                                                    <option value="Commercial Management">Commercial Management</option>
                                                    <option value="Commercial Shipping">Commercial Shipping</option>
                                                    <option value="Commercials and Trading">Commercials and Trading</option>
                                                    <option value="Consulting Department">Consulting Department</option>
                                                    <option value="Container">Container</option>
                                                    <option value="Container Business Group">Container Business Group</option>
                                                    <option value="Container Chartering">Container Chartering</option>
                                                    <option value="Container Department">Container Department</option>
                                                    <option value="Container Freight Station">Container Freight Station</option>
                                                    <option value="Container Logistics">Container Logistics</option>
                                                    <option value="container Operations">container Operations</option>
                                                    <option value="Contracting Dept.">Contracting Dept.</option>
                                                    <option value="Contracts">Contracts</option>
                                                    <option value="Contracts & Legal">Contracts & Legal</option>
                                                    <option value="Contracts Admin">Contracts Admin</option>
                                                    <option value="Control Department">Control Department</option>
                                                    <option value="control/operations">control/operations</option>
                                                    <option value="Controlling">Controlling</option>
                                                    <option value="Controlling Department">Controlling Department</option>
                                                    <option value="Course Department">Course Department</option>
                                                    <option value="Crewing">Crewing</option>
                                                    <option value="Crisis Management">Crisis Management</option>
                                                    <option value="Cruise Departmente">Cruise Departmente</option>
                                                    <option value="Customer Service">Customer Service</option>
                                                    <option value="Deck Department">Deck Department</option>
                                                    <option value="Dedicated Biz ">Dedicated Biz </option>
                                                    <option value="Dedicated Business Team">Dedicated Business Team</option>
                                                    <option value="Defence">Defence</option>
                                                    <option value="Defence Management">Defence Management</option>
                                                    <option value="Dep. de Frota">Dep. de Frota</option>
                                                    <option value="Department of Defense">Department of Defense</option>
                                                    <option value="Department of Economy & Finance">Department of Economy & Finance</option>
                                                    <option value="Department of Maritime Law">Department of Maritime Law</option>
                                                    <option value="Department of Port Management">Department of Port Management</option>
                                                    <option value="Department of Sea Transportations">Department of Sea Transportations</option>
                                                    <option value="Deployable Ops">Deployable Ops</option>
                                                    <option value="Derivatives & Research">Derivatives & Research</option>
                                                    <option value="Design">Design</option>
                                                    <option value="Design Department">Design Department</option>
                                                    <option value="Developement Department">Developement Department</option>
                                                    <option value="Development">Development</option>
                                                    <option value="Director Office">Director Office</option>
                                                    <option value="Directorate">Directorate</option>
                                                    <option value="Disbursements">Disbursements</option>
                                                    <option value="Dispute Resolution">Dispute Resolution</option>
                                                    <option value="Doc Centre">Doc Centre</option>
                                                    <option value="Documentary">Documentary</option>
                                                    <option value="Documentation Dept.">Documentation Dept.</option>
                                                    <option value="Dry Bulk Operations">Dry Bulk Operations</option>
                                                    <option value="Dry Cargo">Dry Cargo</option>
                                                    <option value="Dry Cargo Chartering ">Dry Cargo Chartering </option>
                                                    <option value="Dry Cargo Handy Operations">Dry Cargo Handy Operations</option>
                                                    <option value="Drybulk Planning Division">Drybulk Planning Division</option>
                                                    <option value="Engineering">Engineering</option>
                                                    <option value="Environment">Environment</option>
                                                    <option value="Export">Export</option>
                                                    <option value="Export & Import">Export & Import</option>
                                                    <option value="External Affairs">External Affairs</option>
                                                    <option value="Fleet ">Fleet </option>
                                                    <option value="Fleet & Chartering">Fleet & Chartering</option>
                                                    <option value="Fleet and Terminals / Business Developme">Fleet and Terminals / Business Developme</option>
                                                    <option value="Fleet and terminals, Business Developmen">Fleet and terminals, Business Developmen</option>
                                                    <option value="Fleet Chartering">Fleet Chartering</option>
                                                    <option value="Fleet Coorination and Quality Team, Cont">Fleet Coorination and Quality Team, Cont</option>
                                                    <option value="Fleet Executive Controller">Fleet Executive Controller</option>
                                                    <option value="Fleet Management">Fleet Management</option>
                                                    <option value="Fleet Marine Safety & Quality">Fleet Marine Safety & Quality</option>
                                                    <option value="Fleet Operations">Fleet Operations</option>
                                                    <option value="Fleet Personnel">Fleet Personnel</option>
                                                    <option value="Fleet Personnel Group">Fleet Personnel Group</option>
                                                    <option value="Fleet Safety">Fleet Safety</option>
                                                    <option value="Fleet Support">Fleet Support</option>
                                                    <option value="Fleet Technical">Fleet Technical</option>
                                                    <option value="Fog">Fog</option>
                                                    <option value="Food Bulk Department">Food Bulk Department</option>
                                                    <option value="Forwarding">Forwarding</option>
                                                    <option value="Freight">Freight</option>
                                                    <option value="Freight & Claims">Freight & Claims</option>
                                                    <option value="Freight & Laytime">Freight & Laytime</option>
                                                    <option value="Freight and Laytime">Freight and Laytime</option>
                                                    <option value="Freight Desk">Freight Desk</option>
                                                    <option value="Front Office">Front Office</option>
                                                    <option value="General Business Affairs">General Business Affairs</option>
                                                    <option value="General Department">General Department</option>
                                                    <option value="General Management">General Management</option>
                                                    <option value="General Planning Department">General Planning Department</option>
                                                    <option value="GL Claims Policy, Support & Insurance De">GL Claims Policy, Support & Insurance De</option>
                                                    <option value="Group Business Development">Group Business Development</option>
                                                    <option value="Group Commercial">Group Commercial</option>
                                                    <option value="Group Legal">Group Legal</option>
                                                    <option value="Group Management ">Group Management </option>
                                                    <option value="Group Operations">Group Operations</option>
                                                    <option value="Group Personnel/Administration">Group Personnel/Administration</option>
                                                    <option value="Group Procurement">Group Procurement</option>
                                                    <option value="Guy">Guy</option>
                                                    <option value="Handy">Handy</option>
                                                    <option value="Handy-Handymax">Handy-Handymax</option>
                                                    <option value="Handymax">Handymax</option>
                                                    <option value="Handymax Team">Handymax Team</option>
                                                    <option value="Handysize Desk">Handysize Desk</option>
                                                    <option value="Handysize Fleet">Handysize Fleet</option>
                                                    <option value="Hard Ware">Hard Ware</option>
                                                    <option value="Homeland Security">Homeland Security</option>
                                                    <option value="HR & QA">HR & QA</option>
                                                    <option value="Hub Services">Hub Services</option>
                                                    <option value="Human Resources">Human Resources</option>
                                                    <option value="Human Resources & Administration">Human Resources & Administration</option>
                                                    <option value="Idea">Idea</option>
                                                    <option value="Import & Export Forwarding">Import & Export Forwarding</option>
                                                    <option value="Import and Export">Import and Export</option>
                                                    <option value="Import Department">Import Department</option>
                                                    <option value="Industrial Projects">Industrial Projects</option>
                                                    <option value="Information Services">Information Services</option>
                                                    <option value="Information/Administration">Information/Administration</option>
                                                    <option value="Infrastructure Project Finance">Infrastructure Project Finance</option>
                                                    <option value="Inspection">Inspection</option>
                                                    <option value="Installation Services Department">Installation Services Department</option>
                                                    <option value="Institute of Transportation Studies">Institute of Transportation Studies</option>
                                                    <option value="Insurance & Legal Affairs">Insurance & Legal Affairs</option>
                                                    <option value="Insurance / Average Adjusting">Insurance / Average Adjusting</option>
                                                    <option value="Insurance and FD&D">Insurance and FD&D</option>
                                                    <option value="Insurance/Claims/Legal/Operations">Insurance/Claims/Legal/Operations</option>
                                                    <option value="Intelligence maritime Center Coast Guard">Intelligence maritime Center Coast Guard</option>
                                                    <option value="Interior ">Interior </option>
                                                    <option value="Internaional Business Department">Internaional Business Department</option>
                                                    <option value="Internal">Internal</option>
                                                    <option value="Internal Audit">Internal Audit</option>
                                                    <option value="International Affairs ">International Affairs </option>
                                                    <option value="International Affairs Team">International Affairs Team</option>
                                                    <option value="International Fleet">International Fleet</option>
                                                    <option value="International Freight">International Freight</option>
                                                    <option value="International Law">International Law</option>
                                                    <option value="International Logistics">International Logistics</option>
                                                    <option value="International Operations">International Operations</option>
                                                    <option value="International Practice Group">International Practice Group</option>
                                                    <option value="International Relation">International Relation</option>
                                                    <option value="International Relations and Marketing ">International Relations and Marketing </option>
                                                    <option value="International Relations Department">International Relations Department</option>
                                                    <option value="international Trade">international Trade</option>
                                                    <option value="International Transport Department">International Transport Department</option>
                                                    <option value="International Transportation">International Transportation</option>
                                                    <option value="International Marine Sales">International Marine Sales</option>
                                                    <option value="Investment & Finance">Investment & Finance</option>
                                                    <option value="IS Management & Support">IS Management & Support</option>
                                                    <option value="ISM Dept">ISM Dept</option>
                                                    <option value="ISM/ISPS Dept">ISM/ISPS Dept</option>
                                                    <option value="ISM/QA">ISM/QA</option>
                                                    <option value="ISPS">ISPS</option>
                                                    <option value="IT">IT</option>
                                                    <option value="IT - Personnel">IT - Personnel</option>
                                                    <option value="Law">Law</option>
                                                    <option value="Law Faculty (student)">Law Faculty (student)</option>
                                                    <option value="Legal">Legal</option>
                                                    <option value="Legal & Claims">Legal & Claims</option>
                                                    <option value="Legal & Contracts">Legal & Contracts</option>
                                                    <option value="Legal & Risk Management ">Legal & Risk Management </option>
                                                    <option value="Legal & Secretarial">Legal & Secretarial</option>
                                                    <option value="Legal Affairs">Legal Affairs</option>
                                                    <option value="Legal Affairs Team">Legal Affairs Team</option>
                                                    <option value="Legal and Documentary">Legal and Documentary</option>
                                                    <option value="Legal and Insurance">Legal and Insurance</option>
                                                    <option value="Legal and Risk Management">Legal and Risk Management</option>
                                                    <option value="Legal Assets">Legal Assets</option>
                                                    <option value="Legal, Insurance & Claim">Legal, Insurance & Claim</option>
                                                    <option value="Legal/Contracts">Legal/Contracts</option>
                                                    <option value="Legislation">Legislation</option>
                                                    <option value="Library">Library</option>
                                                    <option value="Library & Information Services">Library & Information Services</option>
                                                    <option value="Library and Information Services">Library and Information Services</option>
                                                    <option value="Lighterage">Lighterage</option>
                                                    <option value="LIMA">LIMA</option>
                                                    <option value="Line ">Line </option>
                                                    <option value="Liner">Liner</option>
                                                    <option value="Liner Div. PEL+PAL">Liner Div. PEL+PAL</option>
                                                    <option value="Liner Operations">Liner Operations</option>
                                                    <option value="Liner Operations Department">Liner Operations Department</option>
                                                    <option value="Lines Management SAF">Lines Management SAF</option>
                                                    <option value="Lines Management SANMEX">Lines Management SANMEX</option>
                                                    <option value="Lines Management SANMEX / Cont.Logistic">Lines Management SANMEX / Cont.Logistic</option>
                                                    <option value="LIS">LIS</option>
                                                    <option value="LNG">LNG</option>
                                                    <option value="Logistics">Logistics</option>
                                                    <option value="Logistics & Felison Terminal">Logistics & Felison Terminal</option>
                                                    <option value="Logistics & Shipping">Logistics & Shipping</option>
                                                    <option value="Logistics + LC">Logistics + LC</option>
                                                    <option value="Logistics Projects">Logistics Projects</option>
                                                    <option value="Logistics Projects Department">Logistics Projects Department</option>
                                                    <option value="Loss Prevention">Loss Prevention</option>
                                                    <option value="LPG">LPG</option>
                                                    <option value="LPSQ">LPSQ</option>
                                                    <option value="Main Engine,S&P">Main Engine,S&P</option>
                                                    <option value="Maintenance Department">Maintenance Department</option>
                                                    <option value="Manage Department">Manage Department</option>
                                                    <option value="Manageing Department">Manageing Department</option>
                                                    <option value="Management">Management</option>
                                                    <option value="Management  / Chartering">Management  / Chartering</option>
                                                    <option value="Management Studies">Management Studies</option>
                                                    <option value="Management Support">Management Support</option>
                                                    <option value="Managing">Managing</option>
                                                    <option value="Management Ship">Management Ship</option>
                                                    <option value="Manning Team">Manning Team</option>
                                                    <option value="Marine">Marine</option>
                                                    <option value="Marine & HSSEQ">Marine & HSSEQ</option>
                                                    <option value="Marine & Safety">Marine & Safety</option>
                                                    <option value="MARINE &SAFETY">MARINE &SAFETY</option>
                                                    <option value="Marine Accounting">Marine Accounting</option>
                                                    <option value="Marine Administration">Marine Administration</option>
                                                    <option value="marine affair Department">marine affair Department</option>
                                                    <option value="Marine Affairs">Marine Affairs</option>
                                                    <option value="Marine and Safety">Marine and Safety</option>
                                                    <option value="Marine and Waterways Logistic">Marine and Waterways Logistic</option>
                                                    <option value="Marine Assurance">Marine Assurance</option>
                                                    <option value="Marine Business Department">Marine Business Department</option>
                                                    <option value="Marine Business Development">Marine Business Development</option>
                                                    <option value="Marine Business Solutions">Marine Business Solutions</option>
                                                    <option value="Marine Capital">Marine Capital</option>
                                                    <option value="Marine Claims">Marine Claims</option>
                                                    <option value="Marine Engineering">Marine Engineering</option>
                                                    <option value="Marine HR">Marine HR</option>
                                                    <option value="Marine Inspection Logistic">Marine Inspection Logistic</option>
                                                    <option value="Marine Insurance">Marine Insurance</option>
                                                    <option value="Marine Insurance & Claims Dept">Marine Insurance & Claims Dept</option>
                                                    <option value="Marine Investigations">Marine Investigations</option>
                                                    <option value="Marine Legal Services">Marine Legal Services</option>
                                                    <option value="Marine Marketing Dep.">Marine Marketing Dep.</option>
                                                    <option value="Marine Operations">Marine Operations</option>
                                                    <option value="Marine Personnel">Marine Personnel</option>
                                                    <option value="Marine Projects">Marine Projects</option>
                                                    <option value="Marine Safety Division">Marine Safety Division</option>
                                                    <option value="Marine Safety Team">Marine Safety Team</option>
                                                    <option value="Marine Services">Marine Services</option>
                                                    <option value="Marine Technology & Engineering Team">Marine Technology & Engineering Team</option>
                                                    <option value="Marine Towage">Marine Towage</option>
                                                    <option value="Marine Transport">Marine Transport</option>
                                                    <option value="Marine Transportation">Marine Transportation</option>
                                                    <option value="Marine Underwriting">Marine Underwriting</option>
                                                    <option value="Marine, Shipmanagement and operation">Marine, Shipmanagement and operation</option>
                                                    <option value="Mariners">Mariners</option>
                                                    <option value="Maritime">Maritime</option>
                                                    <option value="Maritime">Maritime</option>
                                                    <option value="Maritime Administration">Maritime Administration</option>
                                                    <option value="Maritime and Logistics Management">Maritime and Logistics Management</option>
                                                    <option value="Maritime Business and Logistics">Maritime Business and Logistics</option>
                                                    <option value="Maritime Industrial Control Division">Maritime Industrial Control Division</option>
                                                    <option value="Maritime Law">Maritime Law</option>
                                                    <option value="Maritime Law Team">Maritime Law Team</option>
                                                    <option value="Maritime Personnel">Maritime Personnel</option>
                                                    <option value="Maritime Safety & Security">Maritime Safety & Security</option>
                                                    <option value="Maritime Safety Department">Maritime Safety Department</option>
                                                    <option value="Maritime Sales">Maritime Sales</option>
                                                    <option value="Maritime Security Council">Maritime Security Council</option>
                                                    <option value="Maritime Security Service">Maritime Security Service</option>
                                                    <option value="Maritime Supervisory Department">Maritime Supervisory Department</option>
                                                    <option value="Maritime Trans Management Eng.">Maritime Trans Management Eng.</option>
                                                    <option value="Maritime Transport">Maritime Transport</option>
                                                    <option value="Market">Market</option>
                                                    <option value="Marketing">Marketing</option>
                                                    <option value="Marketing - Sales">Marketing - Sales</option>
                                                    <option value="Marketing & Commercial">Marketing & Commercial</option>
                                                    <option value="Marketing & Communication">Marketing & Communication</option>
                                                    <option value="Marketing & Human Resources">Marketing & Human Resources</option>
                                                    <option value="Marketing & Operations">Marketing & Operations</option>
                                                    <option value="Marketing / Sales">Marketing / Sales</option>
                                                    <option value="Marketing Business and Development">Marketing Business and Development</option>
                                                    <option value="Marketing Executive">Marketing Executive</option>
                                                    <option value="Marketing Protective Coatings">Marketing Protective Coatings</option>
                                                    <option value="Marketing, Singapore">Marketing, Singapore</option>
                                                    <option value="Marketing/Sales/PR">Marketing/Sales/PR</option>
                                                    <option value="Material Managment and Logistics">Material Managment and Logistics</option>
                                                    <option value="Membership">Membership</option>
                                                    <option value="Naut.-Tech.">Naut.-Tech.</option>
                                                    <option value="Nautical">Nautical</option>
                                                    <option value="Nautical Department">Nautical Department</option>
                                                    <option value="Nautical Science">Nautical Science</option>
                                                    <option value="Naval">Naval</option>
                                                    <option value="Navigation Department">Navigation Department</option>
                                                    <option value="Navigational">Navigational</option>
                                                    <option value="New building & Sales & Purchase">New building & Sales & Purchase</option>
                                                    <option value="New building / Technical">New building / Technical</option>
                                                    <option value="New Building Project">New Building Project</option>
                                                    <option value="Newbuild. Projects">Newbuild. Projects</option>
                                                    <option value="Newbuilding">Newbuilding</option>
                                                    <option value="Ocean">Ocean</option>
                                                    <option value="Ocean and Construction">Ocean and Construction</option>
                                                    <option value="Ocean Department">Ocean Department</option>
                                                    <option value="Ocean Freight">Ocean Freight</option>
                                                    <option value="Ocean Import">Ocean Import</option>
                                                    <option value="Ocean Liner Services">Ocean Liner Services</option>
                                                    <option value="Ocean Project">Ocean Project</option>
                                                    <option value="Ocean Towing Section">Ocean Towing Section</option>
                                                    <option value="Ocean Transportation">Ocean Transportation</option>
                                                    <option value="Offshore">Offshore</option>
                                                    <option value="Offshore Equipment Team">Offshore Equipment Team</option>
                                                    <option value="Offshore Fleet">Offshore Fleet</option>
                                                    <option value="Offshore Operations">Offshore Operations</option>
                                                    <option value="Operation Post Fixture">Operation Post Fixture</option>
                                                    <option value="Operations">Operations</option>
                                                    <option value="Operations & Business Development">Operations & Business Development</option>
                                                    <option value="Operations / Accounting ">Operations / Accounting </option>
                                                    <option value="Operations / Fleet">Operations / Fleet</option>
                                                    <option value="Operations / S & Q / Manning">Operations / S & Q / Manning</option>
                                                    <option value="Operations and Commercial">Operations and Commercial</option>
                                                    <option value="Operations and Insurance Department">Operations and Insurance Department</option>
                                                    <option value="Operations/ Sale & Purchase">Operations/ Sale & Purchase</option>
                                                    <option value="Ops. & Tech.">Ops. & Tech.</option>
                                                    <option value="Overseas">Overseas</option>
                                                    <option value="Owner & Chairman office & Operations Dpt">Owner & Chairman office & Operations Dpt</option>
                                                    <option value="P&I">P&I</option>
                                                    <option value="P&I Claims">P&I Claims</option>
                                                    <option value="Panamax">Panamax</option>
                                                    <option value="Panamax Operations">Panamax Operations</option>
                                                    <option value="Personnel">Personnel</option>
                                                    <option value="Personnel & ISM">Personnel & ISM</option>
                                                    <option value="Planning Dept.">Planning Dept.</option>
                                                    <option value="Port Department">Port Department</option>
                                                    <option value="Ports and Terminals ">Ports and Terminals </option>
                                                    <option value="Post Fixture">Post Fixture</option>
                                                    <option value="PR & Marketing Support">PR & Marketing Support</option>
                                                    <option value="PR Team">PR Team</option>
                                                    <option value="Procurement">Procurement</option>
                                                    <option value="Project">Project</option>
                                                    <option value="Project and Planning">Project and Planning</option>
                                                    <option value="Project Department">Project Department</option>
                                                    <option value="Projects and Period Chartering">Projects and Period Chartering</option>
                                                    <option value="Purchasing">Purchasing</option>
                                                    <option value="Quality ">Quality </option>
                                                    <option value="Quality & Nautical">Quality & Nautical</option>
                                                    <option value="Quality & Safety">Quality & Safety</option>
                                                    <option value="Quality Assurance">Quality Assurance</option>
                                                    <option value="Research">Research</option>
                                                    <option value="Risk Management">Risk Management</option>
                                                    <option value="Safety">Safety</option>
                                                    <option value="Safety & Insurance Department">Safety & Insurance Department</option>
                                                    <option value="Safety & Loss Prevention">Safety & Loss Prevention</option>
                                                    <option value="Safety & Security">Safety & Security</option>
                                                    <option value="Safety & superintendency">Safety & superintendency</option>
                                                    <option value="Safety and Quality">Safety and Quality</option>
                                                    <option value="Safety Management">Safety Management</option>
                                                    <option value="Sale">Sale</option>
                                                    <option value="Sale and export">Sale and export</option>
                                                    <option value="Sale and Purchase">Sale and Purchase</option>
                                                    <option value="Sales">Sales</option>
                                                    <option value="Sales and Marketing">Sales and Marketing</option>
                                                    <option value="Sales, Marketing & PR">Sales, Marketing & PR</option>
                                                    <option value="Salvage">Salvage</option>
                                                    <option value="Salvage & Chartering">Salvage & Chartering</option>
                                                    <option value="Salvage & Emergency Response">Salvage & Emergency Response</option>
                                                    <option value="Salvage & Marine Operations">Salvage & Marine Operations</option>
                                                    <option value="Salvage & Shipping Dept">Salvage & Shipping Dept</option>
                                                    <option value="Sea Department">Sea Department</option>
                                                    <option value="Secretarial">Secretarial</option>
                                                    <option value="Security">Security</option>
                                                    <option value="Ship & Offshore Dept.">Ship & Offshore Dept.</option>
                                                    <option value="Ship broking & agent dept">Ship broking & agent dept</option>
                                                    <option value="Ship Finance">Ship Finance</option>
                                                    <option value="Ship Management">Ship Management</option>
                                                    <option value="Ship New Building Division">Ship New Building Division</option>
                                                    <option value="Ship Operations">Ship Operations</option>
                                                    <option value="Ship Planning ">Ship Planning </option>
                                                    <option value="Ship Recycling">Ship Recycling</option>
                                                    <option value="Ship&Business Management Dept">Ship&Business Management Dept</option>
                                                    <option value="Shipbuilding Dept.">Shipbuilding Dept.</option>
                                                    <option value="Shipping">Shipping</option>
                                                    <option value="Shipping & Chartering">Shipping & Chartering</option>
                                                    <option value="Shipping & Logistics">Shipping & Logistics</option>
                                                    <option value="Shipping / Brokerage department">Shipping / Brokerage department</option>
                                                    <option value="Shipping and finance">Shipping and finance</option>
                                                    <option value="Ships Department">Ships Department</option>
                                                    <option value="Shortsea">Shortsea</option>
                                                    <option value="Special Fleet Department">Special Fleet Department</option>
                                                    <option value="Special Projects">Special Projects</option>
                                                    <option value="Special Projects & Planning">Special Projects & Planning</option>
                                                    <option value="Specialized Carriers Dept.">Specialized Carriers Dept.</option>
                                                    <option value="Statistics">Statistics</option>
                                                    <option value="Strategic Planning">Strategic Planning</option>
                                                    <option value="Student Administration">Student Administration</option>
                                                    <option value="Supply Marine Dept">Supply Marine Dept</option>
                                                    <option value="Supply Unit/Distribution Department">Supply Unit/Distribution Department</option>
                                                    <option value="Supply-Operations">Supply-Operations</option>
                                                    <option value="Support Services">Support Services</option>
                                                    <option value="Survey">Survey</option>
                                                    <option value="Systems">Systems</option>
                                                    <option value="Tanker Chartering">Tanker Chartering</option>
                                                    <option value="Tanker control">Tanker control</option>
                                                    <option value="Tanker Department">Tanker Department</option>
                                                    <option value="Tanker Operations">Tanker Operations</option>
                                                    <option value="Tariffs Department">Tariffs Department</option>
                                                    <option value="Tax Dept">Tax Dept</option>
                                                    <option value="Team Offshore">Team Offshore</option>
                                                    <option value="techncial & Crewing ">techncial & Crewing </option>
                                                    <option value="Technical">Technical</option>
                                                    <option value="Technical / Documentary">Technical / Documentary</option>
                                                    <option value="Technical and Commercial">Technical and Commercial</option>
                                                    <option value="Technical Bulk">Technical Bulk</option>
                                                    <option value="Technical Operations">Technical Operations</option>
                                                    <option value="Telecomms">Telecomms</option>
                                                    <option value="Towage and Salvage">Towage and Salvage</option>
                                                    <option value="Traffic">Traffic</option>
                                                    <option value="Tramp & Agency Dept.">Tramp & Agency Dept.</option>
                                                    <option value="Tramp & Liner Department">Tramp & Liner Department</option>
                                                    <option value="Tramp & Liner Operarions">Tramp & Liner Operarions</option>
                                                    <option value="Tramp Department">Tramp Department</option>
                                                    <option value="Transportation">Transportation</option>
                                                    <option value="Underwriting">Underwriting</option>
                                                    <option value="Vessels and Procurement">Vessels and Procurement</option>
                                                    <option value="Vetting">Vetting</option>
                                                    <option value="Web">Web</option>
                                                    <option value="World Ports">World Ports</option>
                                                    <option value="Yacht Management ">Yacht Management </option>
                                                </select></td>
                                        </tr>
                                        <tr>
                                            <td class='label'>Gender</td>
                                            <td class='form'>
                                                <select name="gender" class="sbox" id="gender">
                                                    <option value="">Unspecified</option>
                                                    <option value="Male">Male</option>
                                                    <option value="Female">Female</option>
                                              </select></td>
                                        </tr>
                                        <tr>
                                            <td class='label'>
                                            	Date of Birth<br />
                                                <span style="font-size:10px;">(mm/dd/yyyy)</span></td>
                                            <td class='form'>
                                                <select id="month" name="month" style="width:80px;" class="sbox">
                                                    <option value="Jan" selected="selected">Jan</option>
                                                    <option value="Feb">Feb</option>
                                                    <option value="Mar">Mar</option>
                                                    <option value="Apr">Apr</option>
                                                    <option value="May">May</option>
                                                    <option value="Jun">Jun</option>
                                                    <option value="Jul">Jul</option>
                                                    <option value="Aug">Aug</option>
                                                    <option value="Sep">Sep</option>
                                                    <option value="Oct">Oct</option>
                                                    <option value="Nov">Nov</option>
                                                    <option value="Dec">Dec</option>
                                                </select>
                                                <select id="day" name="day" style="width:80px;" class="sbox">
                                                    <?php
                                                    for($z02=1; $z02<=31; $z02++){
                                                        if($z02>=10){echo '<option value="'.$z02.'">'.$z02.'</option>';}
                                                        else{echo '<option value="0'.$z02.'">0'.$z02.'</option>';}
                                                    }
                                                    ?>
                                                </select>
                                                <select id="year" name="year" style="width:90px;" class="sbox">
													<?php for($z03=1920; $z03<=date('Y'); $z03++){echo '<option value="'.$z03.'">'.$z03.'</option>';} ?>
                                                </select>
                                            <!--<input name='dob' type='text' class='tbox' id="dob" readonly="readonly" />-->
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td class="label"><strong>Company Details</strong> </td>
                                            <td class='form'>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td class='label'><span class="required">*</span>Company Name:</td>
                                            <td class='form'>
                                                <input name="company_name" type='text' class='tbox' id="company_name">
                                                <div class='error' id='error_company_name'>Please Input Company Name</div></td>
                                        </tr>
                                        <?php if($_GET['purchase']!="Trial Account (7 Days Trial Account)"){ ?>
                                        <tr>
                                            <td class='label'>Company Name 2: </td>
                                            <td class='form'><input name="company_name2" type='text' class='tbox' id="company_name2" /></td>
                                        </tr>
                                        <?php } ?>
                                        <tr>
                                            <td class='label'><span class="required">*</span>Company Type:</td>
                                            <td class='form'>
                                                <select name="company_type" class="sbox" id="company_type">
                                                    <option value="">Select Company Type</option>
                                                    <option value="Academic Institution">Academic Institution</option>
                                                    <option value="Agency">Agency</option>
                                                    <option value="Associate">Associate</option>
                                                    <option value="Broker">Broker</option>
                                                    <option value="Broker/Agent">Broker/Agent</option>
                                                    <option value="Club/PI">Club/PI</option>
                                                    <option value="Educational Institution">Educational Institution</option>
                                                    <option value="National Association">National Association</option>
                                                    <option value="Other">Other</option>
                                                    <option value="Owner">Owner</option>
                                                    <option value="Private">Private</option>
                                                </select>
                                                <div class='error' id='error_company_type'>Please Select Company Type</div></td>
                                        </tr>
                                        <tr>
                                            <td class='label'>Number of Employees:</td>
                                            <td class='form'>
                                                <select name="num_of_emp" class="sbox" id="num_of_emp">
                                                    <option value="">Select Number of Employees</option>
                                                    <option value="Individual">Individual</option>
                                                    <option value="2-10">2-10 employees</option>
                                                    <option value="11-50">11-50 employees</option>
                                                    <option value="51-100">51-100 employees</option>
                                                    <option value="101+">101+ employees</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <?php if($_GET['purchase']=="Trial Account (7 Days Trial Account)"){ ?>
                                        <tr>
                                            <td class='label'>Do you plan to purchase a ship search solution?</td>
                                            <td class='form'>
                                                <select name="purchase_sss" class="sbox" id="purchase_sss">
                                                    <option value="Yes, immediately">Yes, immediately</option>
                                                    <option value="Yes, in the next 3 months">Yes, in the next 3 months</option>
                                                    <option value="Yes, in the next 6 months">Yes, in the next 6 months</option>
                                                    <option value="Yes, in the next year">Yes, in the next year</option>
                                                    <option value="No, I am just researching">No, I am just researching</option>
                                                    <option value="Already have a solution">Already have a solution</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class='label'>What is your role in purchasing decision?</td>
                                            <td class='form'>
                                                <select name="role_in_purchase" class="sbox" id="role_in_purchase">
                                                    <option value="Researcher">Researcher</option>
                                                    <option value="Decision Maker">Decision Maker</option>
                                                    <option value="Influencer">Influencer</option>
                                                    <option value="Buyer">Buyer</option>
                                                    <option value="Purchasing Agent">Purchasing Agent</option>
                                                    <option value="Consultant">Consultant</option>
                                                    <option value="Owner/Executive">Owner/Executive</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        <tr>
                                            <td class='label'><span class="required">*</span>Address 1:</td>
                                            <td class='form'>
                                                <input class='tbox' type='text' name="address_1" id="address_1">
                                                <div class='error' id='error_address_1'>Please Input Address 1</div></td>
                                        </tr>
                                        <?php if($_GET['purchase']!="Trial Account (7 Days Trial Account)"){ ?>
                                        <tr>
                                            <td class='label'>Address 2:</td>
                                            <td class='form'><input class='tbox' type='text' name='address_2'></td>
                                        </tr>
                                        <tr>
                                            <td class='label'>Address 3:</td>
                                            <td class='form'><input class='tbox' type='text' name='address_3' /></td>
                                        </tr>
                                        <?php } ?>
                                        <tr>
                                            <td class='label'><span class="required">*</span>City:</td>
                                            <td class='form'>
                                                <input class='tbox' type='text' name="city" id="city">
                                                <div class='error' id='error_city'>Please Input City</div></td>
                                        </tr>
                                        <tr>
                                            <td class='label'><span class="required">*</span>Zip / Postal Code:</td>
                                            <td class='form'>
                                                <input class='tbox' type='text' name="postal_code" id="postal_code">
                                                <div class='error' id='error_postal_code'>Please Input Zip / Postal Code</div></td>
                                        </tr>
                                        <tr>
                                            <td class='label'><span class="required">*</span>Country:</td>
                                            <td class='form'>
                                                <div class="container1"><input class='tbox' type='text' id="countryField" name="countryField"></div>
                                                <div class='error' id='error_countryField'>Please Input Country</div></td>
                                        </tr>
                                        <tr>
                                            <td class='label'><span class="required">*</span>Phone Number<br />
                                                <span style="font-size:10px;">(country  + phone number)</span></td>
                                            <td class='form'>
                                                <input name='p_country_code' type='text' class='tbox50' id="p_country_code" /> 
                                                - 
                                                <!--<input name='p_area_code' type='text' class='tbox50' id="p_area_code" maxlength="4" /> 
                                                - -->
                                                <input name='phone_number' type='text' class='tbox155' id="phone_number" />
                                                <div class='error' id='error_phone'>Invalid Phone Number</div></td>
                                        </tr>
                                        <?php if($_GET['purchase']!="Trial Account (7 Days Trial Account)"){ ?>
                                        <tr>
                                            <td class='label'>Fax:</td>
                                            <td class='form'>
                                                <input name='f_country_code' type='text' class='tbox50' id="f_country_code"> 
                                                - 
                                                <!--<input name='f_area_code' type='text' class='tbox50' id="f_area_code" maxlength="4" /> 
                                                - -->
                                                <input name='fax_number' type='text' class='tbox155' id="fax_number" /></td>
                                        </tr>
                                        <tr>
                                            <td class='label'>Number of Licenses:</td>
                                            <td class='form'><input class='tbox' type='text' name='num_of_license' /></td>
                                        </tr>
                                        <tr>
                                            <td class='label'>Website:</td>
                                            <td class='form'><input class='tbox' type='text' name='website' /></td>
                                        </tr>
                                        <tr>
                                            <td class='label'>Years of Experience:</td>
                                            <td class='form'><input class='tbox' type='text' name='work_experience' /></td>
                                        </tr>
                                        <?php } ?>
                                        <tr>
                                            <td class='label'>&nbsp;</td>
                                            <td class='form'></td>
                                        </tr>										
                                        <tr>
                                            <td class='label'>&nbsp;</td>
                                            <td class='form'><img src="captcha/CaptchaSecurityImages.php?width=100&height=40&characters=5" id="captcha_image" /><!--<img id="captcha_image" align="left" style="padding-right: 5px; border: 0" src="captcha/securimage_show.php?sid=<?php echo md5(time()) ?>" />--></td>
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
                                            	<input type="checkbox" name="newsletters" id="newsletters" value="Yes" /> Subscribe to our newsletter
                                             </td>
                                        </tr>
                                        <tr>
                                            <td class='signme'>&nbsp;</td>
                                            <td class='signme'>
                                                <?php if($_GET['purchase']=="Trial Account (7 Days Trial Account)"){ ?>
                                                	<input type="checkbox" name="agreement" id="agreement" value="1" /> I have read and agreed to the Trial Agreement<br />
                                                	&nbsp; <a href="subscriptionagreement2.php" class="z_links" target="_blank">Trial Agreement</a>
                                                <?php }else{ ?>
                                                	<input type="checkbox" name="agreement" id="agreement" value="1" /> I have read and agreed to the Agreement<br />
                                                	&nbsp; <a href="subscriptionagreement.php" class="z_links" target="_blank">Subscription Agreement</a>
                                                <?php } ?>
                                                <div class='error' id='error_agreement'>Please read and agree to the Agreement</div>
                                             </td>
                                        </tr>									
                                        <tr>
                                            <td class='signme'>&nbsp;</td>
                                            <td class='signme'>
                                            	<span class="signme">
                                                    <input type='submit' name="signmebutt" value='Send Request' id='signmebutt' />
                                                </span></td>
                                        </tr>
                                    </table>
                                </form>																				
                            </div>
                            <div style="float:left; width:365px; height:auto; padding-left:10px;"><?php include("includesidebarterms.php"); ?></div>
                        </td>
                    </tr>
                </table>
			</div>
			<?php include("includefooter.php"); ?>
		</div>
		<script type="text/javascript">
			var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
			document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
		</script>
		<script type="text/javascript">
			try {
			var pageTracker = _gat._getTracker("UA-15532283-1");
			pageTracker._setDomainName(".s-bis.com");
			pageTracker._trackPageview();
			} catch(err) {}
		</script>
	</body>
</html>