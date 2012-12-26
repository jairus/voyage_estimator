<?php
//global $row; 

$dbhost = 's-bis.cfclysrb91of.us-east-1.rds.amazonaws.com';
$dbuser = 'sbis';
$dbpass = 'roysbis';
$dbname = 'sbis';

$conn   = mysql_connect($dbhost,$dbuser,$dbpass) or die('Error connecting to mysql');
mysql_select_db($dbname, $conn);

$sql = mysql_query("SELECT * FROM _sbis_users WHERE id = '".$_SESSION['user']['id']."' LIMIT 1 ");
$row = mysql_fetch_assoc($sql);

$month = substr($row['date_of_birth'], 0,3);
$day = substr($row['date_of_birth'], -8,2);
$year = substr($row['date_of_birth'], -4,4);

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

$fax = explode("-",$row['fax']);

$ext = array('.jpg', '.gif', '.png');
foreach($ext as $value){
	if( file_exists("images/user_images/company_".$_SESSION['user']['id'].$value) ){
		$photo1 = "company_".$_SESSION['user']['id'].$value;
		//break;
	}
	
	if( file_exists("images/user_images/".$_SESSION['user']['id'].$value) ){
		$photo  = $_SESSION['user']['id'].$value;
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
							
$m_sql = mysql_query("SELECT * FROM _sbis_users WHERE id = '".$_SESSION['user']['id']."' LIMIT 1");
$m_row = mysql_fetch_assoc($m_sql);
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
	
	$edit_table_memberships .= '<tr>
									<td width="20"><input type="checkbox" name="member_'.$value.'" id="member_'.$value.'" value="Yes"'.($row['member_bim'] == 'yes' ? 'checked="checked"' : '').' /></td>
									<td width="50" valign="center"><img width="30" height="30" src="images/'.$arr_member_img[$value].'" align="absmiddle" /></td>						
									<td class="field">'.$arr_member_names[$value].'</td>
								</tr>';
}
$table_memberships .= '</table>';
							


?>
<link href="app/js/ui.css" rel="stylesheet" />
<style>
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
	
	.error{
		border:1px solid red;
		width:405px;
		padding:4px;
		font-size:10px;
		background:#FFD4D4;
		display:none;
	}	
	#countryField{
		z-index:10;
	}
	
</style>

<script language="javascript" src="js/AutoCountry.js"></script>
<script language="javascript" src="jquery_ui/jquery.js"></script>
<script language="javascript" src="jquery_ui/ui.js"></script>
<script language="javascript" src="jquery_ui/ajaxupload.js"></script>
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
	//window.onload = AC.init("countryField");	
	$(document).ready(function(){
			
		//AC.init("countryField");
		
		var imageUploader = new AjaxUpload($('#photo1'), {
			//action: 'upload.php',
			action: 'account_ajax.php', // I disabled uploads in this example for security reaaons
			name: 'user_photo1',
			onSubmit: function() {
			// allow only 1 upload
				this.setData({
					'trigger' : 'upload_image1'
				});
				$('#modal-overlay').html('<span style="position:relative;"><img src="images/loadingAnimation.gif" /><br />Uploading...</span>');					
				formDisabler('upload-image1');
			},
			onComplete : function(file, response){
				if( response.match("Failed") ){
					//$('#view-upload-image').prepend('<span class="error">' + response + '</span><br />');
					$('#view-upload-image1').find('.error').html(response);
					$('#view-upload-image1').find('.error').show();
				}
				else
					$('#view-upload-image1').find('p').html(response);
					
				var t = setTimeout("formReverter('upload-image1')", 1000);	
				$('#modal-overlay').html('<span style="position:relative;"><img src="images/loadingAnimation.gif" /><br />Updating..</span>');																										
			}	
		});
		
		var imageUploader = new AjaxUpload($('#photo'), {
			//action: 'upload.php',
			action: 'account_ajax.php', // I disabled uploads in this example for security reaaons
			name: 'user_photo',
			onSubmit: function() {
			// allow only 1 upload
				this.setData({
					'trigger' : 'upload_image'
				});
				$('#modal-overlay').html('<span style="position:relative;"><img src="images/loadingAnimation.gif" /><br />Uploading...</span>');					
				formDisabler('upload-image');
			},
			onComplete : function(file, response){
				if( response.match("Failed") ){
					//$('#view-upload-image').prepend('<span class="error">' + response + '</span><br />');
					$('#view-upload-image').find('.error').html(response);
					$('#view-upload-image').find('.error').show();
				}
				else
					$('#view-upload-image').find('p').html(response);
					
				var t = setTimeout("formReverter('upload-image')", 1000);	
				$('#modal-overlay').html('<span style="position:relative;"><img src="images/loadingAnimation.gif" /><br />Updating..</span>');																										
			}	
		});
		
		$('#countryField').autocomplete({
			source: countries,
			appendTo: "#profile",
			minLength: 2
		});
		
		/*$('#dob').datepicker({
			minDate: '01/01/1930',
			maxDate: '12/31/1983',
			changeYear: true,
			changeMonth: true
		});
		$('#dob').focus(function(){
			$('.ui-datepicker').css('z-index', 10);
		});*/		
		$('input:button').button();
		$('.cancel-bt').click(function(){
			$(this).parents('.edit-profile-table').prev('.profile-table').show();
			$(this).parents('.edit-profile-table').prev('.profile-table').find('.error').hide();
			$(this).parents('.edit-profile-table').hide();
			initDropdownMenu();
		});
		$('.delete-bt').click(function(){
			phoneDelete( this.rel );
		});
		$('.action-bt').click(function(){
			if( this.rel != 'contact-numbers' ){
				$('#view-' + this.rel).hide();
				$('#edit-' + this.rel).show();
			}
			else{
				$('#edit-' + this.rel + ' input:lt(3)').each(function(index){
					$(this).val('');
				});			
				$('#edit-' + this.rel).show();
			}
			var c = $('#countryField');
			var offset = c.position();
			var c_height = $('#countryField').innerHeight();
			$('#helper').css('top', (offset.top + c_height + 2) + "px");
			$('#helper').css({'left': offset.left, 'width': '238px'});
		});
		$('.save-bt').click(function(){
			var error_count = 0;
			if( $(this).attr('disabled') == false ){
				var bt_id = this.id;
				if( bt_id == 'password-details' ){
					if( $('#cur_password').val() == '' ){
						$('#view-' + bt_id).html('Please Input Current Password');					
						error_count++;
					}
					else if( $('#new_password').val() == '' ){
						$('#view-' + bt_id).html('Please Input New Password');					
						error_count++;				
					}	
					else if( $('#ver_password').val() == '' ){
						$('#view-' + bt_id).html('Please Confirm New Password');					
						error_count++;	
					}			
					else if( $('#new_password').val() != $('#ver_password').val() ){
						$('#view-' + bt_id).html('Passwords doesn\'t match');	
						$('#edit-' + bt_id + ' input[type=password]').val('');									
						error_count++;		
					}							
					else{
						$('#modal-overlay').html('<span style="position:relative;"><img src="images/loadingAnimation.gif" /><br />Checking...</span>');
						formDisabler(bt_id);
						check_pass = $.ajax({
							url: 'account_ajax.php',
							type: 'POST',
							data: {'trigger' : 'check_password_details', 'cur_password': $('#cur_password').val()}, 
							async: false
						}).responseText;
						
						if( check_pass != 'checked' ){
							$('#view-' + bt_id).html(check_pass);
							$('#edit-' + bt_id + ' input[type=password]').val('');											
							error_count++;
						}
					}
					//alert(error_count);
					if( error_count == 0 ){
						$('#edit-' + bt_id + ' input[name=trigger]').val('update_password_details');
						$('#modal-overlay').html('<span style="position:relative;"><img src="images/loadingAnimation.gif" /><br />Updating...</span>');					
						$.post('account_ajax.php', $('#form-' + bt_id).serializeArray(), function(data){
							$('#view-' + bt_id).html(data);
							$('#edit-' + bt_id + ' input[type=password]').val('');					
						});
					}
					else
						$('#view-' + bt_id).show();
					var t = setTimeout("formReverter('" + bt_id + "')", 1000);																					
				}
				else{
					$('#edit-' + bt_id).innerHeight();
					formDisabler(bt_id);
					//var t = setTimeout("formReverter('" + bt_id + "')", 10000);
					$.post('account_ajax.php', $('#form-' + bt_id).serializeArray(), function(data){
						if( bt_id == 'membership-details' ){
							if( data == 'no memberships' ){
								$('#view-' + bt_id).html('<p>' + data + '</p>');
								$('#view-membership-details').prev('p').find('.action-container').hide();
								$('#edit-' + bt_id).show();								
							}	
							else{
								$('#view-' + bt_id).html(data);
								$('#view-' + bt_id).show();
								$('#view-membership-details').prev('p').find('.action-container').show();
								$('#edit-' + bt_id).hide();																
							}
						}
						else
							$('#view-' + bt_id).html( data );
						var t = setTimeout("formReverter('" + bt_id + "')", 2000);
					});
				}
			}
		});
		$('#edit-contact-numbers input').blur(function(){
			arr_phone_fields = ['p_country_code', 'p_area_code', 'phone_number'];
			var p_err_count = 0;
			$.each(arr_phone_fields, function(index, value){
				if( $('#' + value).val() == '' )
					p_err_count++;
			});
			if( p_err_count == 0 )
				$('#contact-numbers').button("enable");
			else
				$('#contact-numbers').button("disable");
				
			//alert( $('#p_country_code').val() + " == " + $('#p_area_code').val() + " == " + $('#phone_number').val() );
		});
		$('#p_area_code').keyup(function(){
			if( this.value.length == 4 )
				$('#phone_number').focus();
		});
		$('#p_country_code, #p_area_code, #f_area_code').keyup(function(){
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
		$('#edit-password-details').show();
		$('#view-password-details').hide();
		<?php
			if( $count == 0 ){
		?>
			$('#edit-membership-details').show();
			$('#view-membership-details').hide();
			$('#view-membership-details').prev('p').find('.action-container').hide();
		<?php
		}
		?>
		
		initDropdownMenu();
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
	
	function initDropdownMenu(){
		$('#company_type option').each(function(index){
			if( $(this).val() == "<?=$row['company_type']?>" )
				$(this).attr('selected', true);
		});	
		
		$('#department option').each(function(index){
			if( $(this).val() == "<?=$row['department']?>" )
				$(this).attr('selected', true);
		});	
		
		$('#title option').each(function(index){
			if( $(this).val() == "<?=$row['title']?>" )
				$(this).attr('selected', true);
		});
		
		$('#gender option').each(function(index){
			if( $(this).val() == "<?=$row['gender']?>" )
				$(this).attr('selected', true);
		});
		
		$('#month option').each(function(index){
			if( $(this).val() == "<?=$month?>" )
				$(this).attr('selected', true);
		});
		$('#day option').each(function(index){
			if( $(this).val() == "<?=$day?>" )
				$(this).attr('selected', true);
		});
		$('#year option').each(function(index){
			if( $(this).val() == "<?=$year?>" )
				$(this).attr('selected', true);
		});
	}
	
	function formDisabler(table_id){
		t_offset = $('#edit-' + table_id).position();
		t_width = $('#edit-' + table_id).width() - 30;
		t_height = $('#edit-' + table_id).innerHeight()
		t_pdtop = Math.ceil(t_height / 3);
		
		$('#modal-overlay').css({'top': t_offset.top, 'left': t_offset.left, 'width': t_width + "px", 'height': (t_height - t_pdtop) + "px", 'padding-top': t_pdtop + "px"});
		//$('#modal-overlay span').css('margin-top', t_pdtop);
		$('#modal-overlay').show(); 
		$('#edit-' + table_id + ' input[type=button]').attr('disabled', true);
		$('#edit-' + table_id).css('color', '#cccccc');	
		$('#edit-' + table_id + ' input, #edit-' + table_id + ' select').css({'color' : '#cccccc', 'border-color' : '#cccccc'});
	}
	
	function formReverter(table_id){
		$('#modal-overlay').hide();
		$('#edit-' + table_id + ' input[type=button]').attr('disabled', false);		
		$('#edit-' + table_id).css('color', '#000000');	
		$('#edit-' + table_id + ' input, #edit-' + table_id + ' select').css({'color' : '#000000', 'border-color' : '#69B3E3'});
		if( !(table_id == 'password-details' || table_id == 'membership-details') )
			$('#edit-' + table_id).hide();
		if( !(table_id == 'membership-details') )
			$('#view-' + table_id).show();
	}
	
	function phoneDelete(key){
		var phone_key = key;
		$.post('account_ajax.php', {'trigger': 'delete_phone_number', 'phone_key': phone_key}, function(data){
			$('#view-contact-numbers').html( data );
		});
	}
	
	
</script>
<div id="profile">
	<h1><strong>Profile</strong></h1>
	<div class="box">
	<div id="modal-overlay"><span style="position:relative;"><img src="images/loadingAnimation.gif" /><br />Updating...</span></div>
		<p><span class="profile-head">Company Details</span> (<a class="action-bt" rel="company-details">edit</a>) </p>
		<div class="profile-table" id="view-company-details">
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="145" class="field">Company Name:</td>
					<td><?=$row['company_name']?></td>
				</tr>
				<tr>
					<td width="145" class="field">Company Name2:</td>
					<td><?=$row['company_name2']?></td>
				</tr>                
				<tr>
					<td width="145" class="field">Business Type:</td>
					<td><?=$row['company_type']?></td>
				</tr>
				<tr>
					<td width="145" class="field">Address1:</td>
					<td><?=$row['address1']?></td>
				</tr>
				<tr>
					<td width="145" class="field">Address2:</td>
					<td><?=$row['address2']?></td>
				</tr>
				<tr>
					<td width="145" class="field">Address3:</td>
					<td><?=$row['address3']?></td>
				</tr>
				<tr>
					<td width="145" class="field">City:</td>
					<td><?=$row['city']?></td>
				</tr>
				<tr>
					<td width="145" class="field">Postal Code: </td>
					<td><?=$row['postal_code']?></td>
				</tr>
				<tr>
					<td width="145" class="field">Country:</td>
					<td><?=$row['country']?></td>
				</tr>
				<tr>
                  <td class="field">Fax:</td>
				  <td><?=$row['fax']?></td>
			  </tr>
				<tr>
                  <td class="field">Website:</td>
				  <td><?=$row['website']?></td>
			  </tr>
				<tr>
                  <td class="field">Number of Licences:</td>
				  <td><?=$row['licenses']?></td>
			  </tr>
				<tr>
                  <td class="field">Years of Experience:</td>
				  <td><?=$row['work_experience']?></td>
			  </tr>
			</table>
		</div>
		<div class="edit-profile-table" id="edit-company-details">
        	<form id="form-company-details" method="post">
                <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="145" class="field">Company Name:</td>
                        <td><input name="company_name" type="text" class="tbox" id="company_name" value="<?=$row['company_name']?>" /></td>
                    </tr>
                    <tr>
                        <td width="145" class="field">Company Name2:</td>
                        <td><input name="company_name2" type="text" class="tbox" id="company_name2" value="<?=$row['company_name2']?>" /></td>
                    </tr>                    
                    <tr>
                        <td width="145" class="field">Business Type:</td>
                        <td>
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
							</select></td>
                    </tr>
                    <tr>
                        <td width="145" class="field">Address1:</td>
                        <td><input name="address1" type="text" class="tbox" id="address1" value="<?=$row['address1']?>" /></td>
                    </tr>
                    <tr>
                        <td width="145" class="field">Address2:</td>
                        <td><input name="address2" type="text" class="tbox" id="address2" value="<?=$row['address2']?>" /></td>
                    </tr>    
                    <tr>
                        <td width="145" class="field">Address3:</td>
                        <td><input name="address3" type="text" class="tbox" id="address3" value="<?=$row['address3']?>" /></td>
                    </tr>                                    
                    <tr>
                        <td width="145" class="field">City:</td>
                        <td><input name="city" type="text" class="tbox" id="city"  value="<?=$row['city']?>" /></td>
                    </tr>
                    <tr>
                        <td width="145" class="field">Postal Code: </td>
                        <td><input name="postal_code" type="text" class="tbox" id="postal_code"  value="<?=$row['postal_code']?>" /></td>
                    </tr>
                    <tr>
                        <td width="145" class="field">Country:</td>
                        <td>
							<div class="container1"><input class='tbox' type='text' id="countryField" name="countryField" value="<?=$row['country']?>" /></div></td>
                    </tr>
                    <tr>
                      <td class="field">Fax:</td>
                      <td><input name='f_country_code' type='text' class='tbox50' id="f_country_code" value="<?=$fax[0]?>"> 
							
							- 
							<input name='fax_number' type='text' class='tbox155' id="fax_number" value="<?=$fax[2]?>" /></td>
                    </tr>
                    <tr>
                      <td class="field">Website:</td>
                      <td><input name="website" type="text" class="tbox" id="website" value="<?=$row['website']?>" /></td>
                    </tr>
                    <tr>
                      <td class="field">Number of Licences:</td>
                      <td><input name="licenses" type="text" class="tbox" id="licences" value="<?=$row['licenses']?>" /></td>
                    </tr>
                    <tr>
                      <td class="field">Years of Experience:</td>
                      <td><input name="work_experience" type="text" class="tbox" id="work_experience" value="<?=$row['work_experience']?>" /></td>
                    </tr>
                </table>
                <input type="hidden" name="trigger" value="update_company_details" />
                <input class="save-bt" type="button" value="Save Changes" id="company-details" />
                <input class="cancel-bt" type="button" value="Cancel" />  
          	</form>
		</div>
		<p><span class="profile-head">Contact Details</span> (<a class="action-bt" rel="contact-details">edit</a>)</p>
		<div class="profile-table" id="view-contact-details">
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="145" class="field">Title:</td>
					<td><?=$row['title']?></td>
				</tr>			
				<tr>
					<td width="145" class="field">First Name:</td>
					<td><?=$row['firstname']?></td>
				</tr>
				<tr>
					<td width="145" class="field">Last Name:</td>
					<td><?=$row['lastname']?></td>
				</tr>				
				<tr>
					<td width="145" class="field">Gender:</td>
					<td><?=$row['gender']?></td>
				</tr>
				<tr>
					<td width="145" class="field">Date of Birth:</td>
					<td><?=$row['date_of_birth']?></td>
				</tr>				
				<tr>
					<td width="145" class="field">Email Address: </td>
					<td><?=$row['email']?></td>
				</tr>
				<tr>
					<td width="145" class="field">Position:</td>
					<td><?=$row['position']?></td>
				</tr>		
				<tr>
					<td width="145" class="field">Department</td>
					<td><?=$row['department']?></td>
				</tr>		
				<tr>
					<td width="145" class="field">Skype ID</td>
					<td><?=$row['skype']?></td>
				</tr>								
				<tr>
					<td width="145" class="field">Yahoo ID</td>
					<td><?=$row['yahoo']?></td>
				</tr>								
				<tr>
					<td width="145" class="field">MSN ID</td>
					<td><?=$row['msn']?></td>
				</tr>																		
			</table>
		</div>
		<div class="edit-profile-table" id="edit-contact-details">
			<form id="form-contact-details" method="post">		
				<table border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td width="145" class="field">Title:</td>
						<td>
							<select class="sbox" name="title" id="title">
								<option value="">Select Title</option>                                                
								<option value="Mr">Mr.</option>
								<option value="Mrs">Mrs.</option>
								<option value="Mrs">Ms.</option>     
								<option value="Sir">Sir</option>                                                         
								<option value="Dr">Dr.</option>
								<option value="Capt">Capt.</option>
							</select></td>
					</tr>				
					<tr>
						<td width="145" class="field">First Name:</td>
						<td><input name="firstname" type="text" class="tbox" id="firstname" value="<?=$row['firstname']?>" /></td>
					</tr>
					<tr>
						<td width="145" class="field">Last Name:</td>
						<td><input name="lastname" type="text" class="tbox" id="lastname" value="<?=$row['lastname']?>" /></td>
					</tr>
					<tr>
						<td width="145" class="field">Gender:</td>
						<td>
                        	<select class="sbox" name="gender" id="gender">
								<option value="Male">Male</option>                                                
								<option value="Female">Female</option>
							</select>
                        </td>
					</tr>
					<tr>
						<td width="145" class="field">Date of Birth:</td>
						<td>
                        	<select id="month" name="month" style="width:80px;" class="sbox">
                                <option value="Jan">Jan</option>
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
                        <!--<input name="dob" type="text" class="tbox view-calendar" id="dob" value="<?=$row['date_of_birth']?>" readonly="readonly" />-->
                        </td>
					</tr>					
					<tr>
						<td width="145" class="field">Position: </td>
						<td><input name="position" type="text" class="tbox" id="position" value="<?=$row['position']?>" /></td>
					</tr>
					<tr>
						<td width="145" class="field">Department: </td>
						<td>
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
						<td width="145" class="field">Skype ID: </td>
						<td><input name="skype" type="text" class="tbox" id="skype" value="<?=$row['skype']?>" /></td>
					</tr>
					<tr>
						<td width="145" class="field">Yahoo ID: </td>
						<td><input name="yahoo" type="text" class="tbox" id="yahoo" value="<?=$row['yahoo']?>" /></td>
					</tr>																	
					<tr>
						<td width="145" class="field">MSN ID: </td>
						<td><input name="msn" type="text" class="tbox" id="msn" value="<?=$row['msn']?>" /></td>
					</tr>																																							
				</table>
				<input type="hidden" name="trigger" value="update_contact_details" />
				<input class="save-bt" type="button" value="Save Changes" id="contact-details">
				<input class="cancel-bt" type="button" value="Cancel" />    
			</form>
		</div>
		<p><span class="profile-head">Contact Numbers </span> (<a class="action-bt" rel="contact-numbers">add number</a>) </p>
		<div class="profile-table" id="view-contact-numbers">
			<?php
				$ex_phone_nos = explode("|~~|", $row['contact_nos']);
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
							<td>'.$value.' (<a class="delete-bt" rel="'.($key+1).'">delete</a>)</td>
						 </tr>';
				}
				echo '</table>';
			?>
		</div>
		<div class="edit-profile-table" id="edit-contact-numbers">
			<form id="form-contact-numbers" method="post">		
				<table border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td width="145" class="field">&nbsp;</td>
						<td>&nbsp;</td>
					</tr>								
					<tr>
						<td width="145" class="field">Add Number:</td>
						<td>
						<input name='p_country_code' type='text' class='tbox50' id="p_country_code" /> 
						- 
						<input name='phone_number' type='text' class='tbox155' id="phone_number" /></td>
					</tr>				
				</table>
				<input type="hidden" name="trigger" value="update_contact_numbers" />
				<input class="save-bt" type="button" value="Save Number" id="contact-numbers" disabled="disabled">
				<input class="cancel-bt" type="button" value="Cancel" />    				
			</form>
		</div>
		<p><span class="profile-head">Password Details</span></p>
		<div class="profile-table error" id="view-password-details">&nbsp;</div>
		<div class="edit-profile-table" id="edit-password-details">
			<form id="form-password-details" method="post">
				<table border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td width="145" class="field">Current Password </td>
						<td width="175"><input name="cur_password" type="password" class="tbox" id="cur_password" style="width:150px;"></td>
					</tr>
					<tr>
						<td width="145" class="field">New Password: </td>
						<td><input name="new_password" type="password" class="tbox" id="new_password" style="width:150px;" /></td>
					</tr>
					<tr>
						<td width="145" class="field">Verify Password: </td>
						<td><input name="ver_password" type="password" class="tbox" id="ver_password" style="width:150px;"></td>
					</tr>
				</table>
				<input type="hidden" name="trigger" id="trigger" value="update_password_details" />
				<input class="save-bt" type="button" id="password-details" value="Change Password">
			</form>
		</div>
	</div>
	<div class="box">
		<p><span class="profile-head">Company Logo</span> (<a class="action-bt" rel="upload-image1">upload new company logo</a>) </p>
		<div class="profile-table" id="view-upload-image1">
        	<?php
			if( file_exists("images/user_images/".$photo1."") ){
				$imgsize1   = getimagesize("images/user_images/".$photo1."");
				$imgwidth1  = $imgsize1[0];
				$imgheight1 = $imgsize1[1];
			}
			?>
			<span class="error"></span>
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
        <div class="edit-profile-table" id="edit-upload-image1">
			<form id="form-upload-image1" method="post">		
				<table border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td width="145" class="field">&nbsp;</td>
						<td>&nbsp;</td>
					</tr>																	
					<tr>
						<td width="145" class="field">Upload Your Image:</td>
						<td><input class="upload-bt" id="photo1" type="button" value="Browse" /> <input class="cancel-bt" type="button" value="Cancel" /></td>
					</tr>
					<tr>
						<td width="145" class="field">&nbsp;</td>
						<td>&nbsp;</td>
					</tr>																	
				</table>
			</form>
		</div>
        <p><span class="profile-head">Profile Photo</span> (<a class="action-bt" rel="upload-image">upload new profile photo</a>) </p>
		<div class="profile-table" id="view-upload-image">
        	<?php
			if( file_exists("images/user_images/".$photo."") ){
				$imgsize   = getimagesize("images/user_images/".$photo."");
				$imgwidth  = $imgsize[0];
				$imgheight = $imgsize[1];
			}
			?>
			<span class="error"></span>
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
		<div class="edit-profile-table" id="edit-upload-image">
			<form id="form-upload-image" method="post">		
				<table border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td width="145" class="field">&nbsp;</td>
						<td>&nbsp;</td>
					</tr>																	
					<tr>
						<td width="145" class="field">Upload Your Image:</td>
						<td><input class="upload-bt" id="photo" type="button" value="Browse" /> <input class="cancel-bt" type="button" value="Cancel" /></td>
					</tr>
					<tr>
						<td width="145" class="field">&nbsp;</td>
						<td>&nbsp;</td>
					</tr>																	
				</table>
			</form>
		</div>
		<p><span class="profile-head">Miscellaneous Information </span> (<a class="action-bt" rel="miscellaneous-details">edit</a>) </p>
        <div class="profile-table" id="view-miscellaneous-details">  
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="220" class="field" valign="bottom">Subscribe to our Newsletters and recieve information from Maritime Infosys or its partners:</td>
                    <td valign="bottom" style="padding:2px 0px;"><?=ucfirst($row['subscribe_newsletter'])?></td>
                </tr>			
            </table>            
        </div>
        <div id="edit-miscellaneous-details" class="edit-profile-table">
            <form id="form-miscellaneous-details" method="post">
                <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="20" valign="top" style="padding:2px 0px;"><input type="checkbox" name="newsletters" value="Yes"<?=$row['subscribe_newsletter'] == 'yes' ? 'checked="checked"' : ''?> /></td>
                        <td class="field" valign="top">Subscribe to our Newsletters and recieve information from Maritime Infosys or its partners</td>
                    </tr>			
                </table>        
				<input type="hidden" name="trigger" id="trigger" value="update_miscellaneous_details" />                    
                <input class="save-bt" type="button" value="Save Changes" id="miscellaneous-details">
                <input class="cancel-bt" type="button" value="Cancel">      
            </form>                   
        </div>
		<p><span class="profile-head">Membership</span> <span class="action-container">(<a class="action-bt" rel="membership-details">edit</a>)</span></p>
        <div class="profile-table" id="view-membership-details">  
			<!--            <table border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td width="300" class="field"><img src="images/logo_baltic_international_maritime.gif" align="absmiddle" style="padding-right:20px;" />Baltic International Maritime: </td>
									<td><?=ucfirst($row['member_bim'])?></td>
								</tr>		
								<tr>
									<td width="300" class="field"><img src="images/logo_international_maritime_organization.gif" align="absmiddle" style="padding-right:20px;" />International Maritime Organization:</td>
									<td><?=ucfirst($row['member_imo'])?></td>
								</tr>			
								<tr>
									<td width="300" class="field"><img src="images/logo_global_mobile_satelite_communication.gif" align="absmiddle" style="padding-right:20px;" />Global Mobile Satellite Communcations:</td>
									<td><?=ucfirst($row['member_gmsc'])?></td>
								</tr>			
								<tr>
									<td width="300" class="field"><img src="images/logo_international_association_of_independependent_tanker_owner.gif" align="absmiddle" style="padding-right:20px;" />The International Association of Independent Tanker Owners: </td>
									<td><?=ucfirst($row['member_iaito'])?></td>
								</tr>
								<tr>
									<td width="300" class="field"><img src="images/logo_internation_ship_suppliers_association.gif" align="absmiddle" style="padding-right:20px;" />International Ship Suppliers Association: </td>
									<td><?=ucfirst($row['member_issa'])?></td>
								</tr>										
						  </table>            
			-->
			<?php echo $table_memberships; ?> 
		</div>
        <div id="edit-membership-details" class="edit-profile-table">
            <form id="form-membership-details" method="post">
                <table border="0" cellpadding="0" cellspacing="0">
					<?php echo $edit_table_memberships; ?>
					<!--                    <tr>
											<td width="20"><input type="checkbox" name="member_bim" id="member_bim" value="Yes"<?=$row['member_bim'] == 'yes' ? 'checked="checked"' : ''?> /></td>
											<td class="field">Baltic International Maritime</td>
										</tr>	
										<tr>
											<td width="20"><input type="checkbox" name="member_imo" id="member_imo" value="Yes"<?=$row['member_imo'] == 'yes' ? 'checked="checked"' : ''?> /></td>
											<td class="field">International Maritime Organization</td>
										</tr>			
										<tr>
											<td width="20"><input type="checkbox" name="member_gmsc" id="member_gmsc" value="Yes"<?=$row['member_gmsc'] == 'yes' ? 'checked="checked"' : ''?> /></td>
											<td class="field">Global Mobile Satellite Communications</td>
										</tr>	
										<tr>
											<td width="20"><input type="checkbox" name="member_iaito" id="member_iaito" value="Yes"<?=$row['member_iaito'] == 'yes' ? 'checked="checked"' : ''?> /></td>
											<td class="field">The International Association of Independent Tanker Owners: </td>
										</tr>
										<tr>
											<td width="20"><input type="checkbox" name="member_issa" id="member_issa" value="Yes"<?=$row['member_issa'] == 'yes' ? 'checked="checked"' : ''?> /></td>
											<td class="field">International Ship Suppliers Association</td>
										</tr>	
										<tr>
											<td width="20"><input type="checkbox" name="member_issa" id="member_issa" value="Yes"<?=$row['member_imo2'] == 'yes' ? 'checked="checked"' : ''?> /></td>
											<td class="field">IMO</td>
										</tr>																		
										<tr>
											<td width="20"><input type="checkbox" name="member_issa" id="member_issa" value="Yes"<?=$row['member_intertanko'] == 'yes' ? 'checked="checked"' : ''?> /></td>
											<td class="field">INTERTANKO</td>
										</tr>																		
										<tr>
											<td width="20"><input type="checkbox" name="member_issa" id="member_issa" value="Yes"<?=$row['member_ics'] == 'yes' ? 'checked="checked"' : ''?> /></td>
											<td class="field">Institute of Chartered ShipBrokers</td>
										</tr>																		
					-->																						
                </table>            
				<input type="hidden" name="trigger" id="trigger" value="update_membership_details" />                
                <input class="save-bt" type="button" value="Save Changes" id="membership-details">
                <input class="cancel-bt" type="button" value="Cancel">      
            </form>                   
        </div>
	</div>	
</div>