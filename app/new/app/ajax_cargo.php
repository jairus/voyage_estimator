<?php
@include_once(dirname(__FILE__)."/includes/bootstrap.php");
date_default_timezone_set('UTC');

$dbhost = 's-bis.cfclysrb91of.us-east-1.rds.amazonaws.com';
$dbuser = 'sbis';
$dbpass = 'roysbis';
$dbname = 'sbis';

$conn   = mysql_connect($dbhost,$dbuser,$dbpass) or die('Error connecting to mysql');
mysql_select_db($dbname, $conn);

if($_GET['confirmdelete']){
	mysql_query("DELETE FROM cargos WHERE id='".$_GET['id']."'");
	
	header("Location: s-bis.php");
}

if($_GET['edit']){
	$result = mysql_query("SELECT * FROM cargos WHERE id='".$_GET['id']."'");
	if($result!=false){
		while($data = mysql_fetch_assoc($result)){
			$id = $data['id'];
			$load_port = trim(htmlentities($data['load_port']));
			$discharge_port = trim(htmlentities($data['discharge_port']));
			$cargo_date = date('M d, Y', strtotime($data['cargo_date']));
			$dwt_or_ship_type = trim(htmlentities($data['dwt_or_ship_type']));
			$cargo_type = trim(htmlentities($data['cargo_type']));
			$cargo_quantity = trim(htmlentities($data['cargo_quantity']));
			$port_costs = trim(htmlentities($data['port_costs']));
			$load_port2 = trim(htmlentities($data['load_port2']));
			$load_port_quantity = trim(htmlentities($data['load_port_quantity']));
			$channel = trim(htmlentities($data['channel']));
			$anchorage = trim(htmlentities($data['anchorage']));
			$cargo_pier = trim(htmlentities($data['cargo_pier']));
			$discharge_port2 = trim(htmlentities($data['discharge_port2']));
			$discharge_port_quantity = trim(htmlentities($data['discharge_port_quantity']));
			$channel2 = trim(htmlentities($data['channel2']));
			$anchorage2 = trim(htmlentities($data['anchorage2']));
			$cargo_pier2 = trim(htmlentities($data['cargo_pier2']));
			$notes = trim(htmlentities($data['notes']));
		}
	}else{echo mysql_error();}
}
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
	width:150px;
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
	width: 100px;
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
<script type='text/javascript' src='js/jscript.js'></script>
<script language="javascript" src="../app/jquery_ui/jquery.js"></script>
<script language="javascript" src="../app/jquery_ui/ui.js"></script>

<script type='text/javascript' src='js/jquery-autocomplete/lib/jquery.bgiframe.min.js'></script>
<script type='text/javascript' src='js/jquery-autocomplete/lib/jquery.ajaxQueue.js'></script>
<script type='text/javascript' src='js/jquery-autocomplete/lib/thickbox-compressed.js'></script>
<script type='text/javascript' src='js/jquery-autocomplete/jquery.autocomplete.js'></script>
<script type='text/javascript' src='js/ports.php'></script>
<link rel="stylesheet" type="text/css" href="js/jquery-autocomplete/jquery.autocomplete.css" />
<link rel="stylesheet" type="text/css" href="js/jquery-autocomplete/lib/thickbox.css" />

<script type="text/javascript" src="js/calendar/xc2_default.js"></script>
<script type="text/javascript" src="js/calendar/xc2_inpage.js"></script>
<link type="text/css" rel="stylesheet" href="js/calendar/xc2_default.css" />

<link type="text/css" href="js/grid/jquery_css/flexigrid.css" rel="stylesheet" />
<script type="text/javascript" src="js/grid/jquery_javascript/flexigrid.js"></script>
<script language="javascript">
function deleteitem() {
	<?php
	if($_GET['del']){
		$num    = $_GET['num'];
		$sql    = "SELECT id FROM cargos WHERE id='".$_GET['id']."'";
		$result = mysql_query($sql);
		if($result!=false){
			while($data = mysql_fetch_assoc($result)){
				$id = $data['id'];
			}
		}else{echo mysql_error();}

		echo 'if(confirm("Are you sure you want to DELETE this record?\n Record Number : '.$id.'")){'."\n";
		echo "location = 's-bis.php?confirmdelete=1&id=".$id."&page=11';"."\n";
		echo '}'."\n";
	}
	?>
}

function addCommas(nStr){
	nStr += '';

	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';

	var rgx = /(\d+)(\d{3})/;

	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}

	return x1 + x2;
}

function uNum(num){
	if(!num){
		num = 0;
	}else if(isNaN(num)){
		num = num.replace(/[^0-9\.]/g, "");

		if(isNaN(num)){ num = 0; }
	}

	return num*1;
}

function fNum(num){
	num = uNum(num);

	if(num==0){ return ""; }

	num = num.toFixed(2);

	return addCommas(num);
}

$(document).ready(function() {
	deleteitem();
});

$(function(){				
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
		
		//fields validation
		//arr_fields = ['load_port', 'discharge_port', 'cargo_date', 'dwt_or_ship_type', 'cargo_type', 'cargo_quantity', 'port_costs', 'load_port2', 'channel', 'anchorage', 'cargo_pier', 'discharge_port2', 'channel2', 'anchorage2', 'cargo_pier2', 'notes'];
		arr_fields = [];
		$.each(arr_fields, function(index, value){
			if( $('#' + value).val() == '' ){
				$('#error_' + value).show();
				error_count++;
			}
			else
				$('#error_' + value).hide();
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
					url: 'signup_ajax1.php',
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
			//saving to dabase && send to email;
			$.post('signup_ajax1.php', $('#cargoform').serializeArray(), function(data){
				alert('You have successfully added/updated a cargo');
				
				window.location.reload();
			});
		}
		else
			alert('Errors found: ' + error_count);
		return false;
	});	
});

function getPortDepth(type){
	jQuery.ajax({
		type: 'POST',
		url: "signup_ajax1.php?trigger=get_port_depth&type="+type,
		data:  jQuery("#cargoform").serialize(),
		dataType : 'json',

		success: function(data) {
			jQuery('#channel'+type).val(data['channel_depth']);
			jQuery('#anchorage'+type).val(data['anchorage_depth']);
			jQuery('#cargo_pier'+type).val(data['cargo_pier_depth']);
			
			if(data['channel_depth'] == 'Unknown' ){
				$('#error_channel' + type).show();
			}else{
				$('#error_channel' + type).hide();
			}
			
			if(data['anchorage_depth'] == 'Unknown' ){
				$('#error_anchorage' + type).show();
			}else{
				$('#error_anchorage' + type).hide();
			}
			
			if(data['cargo_pier_depth'] == 'Unknown' ){
				$('#error_cargo_pier' + type).show();
			}else{
				$('#error_cargo_pier' + type).hide();
			}
		}
	});
}

function copyLoadPort(val){
	jQuery('#load_port2').val(val);
}	
</script>
<form id='cargoform' method="post">
<input type="hidden" name="trigger" value="save_new_cargo"  />
<input type='hidden' name='id' id="id" value="<?php echo $id; ?>">   
<div style="float:left; width:300px; height:auto;">                           
<table style='width:300px' id="signup">								
	<tr>
		<td colspan="2">
			<h2>Cargo Card Details:</h2>
			<?php if(isset($_GET['view'])){ ?>
				<input type='submit' name="reload" value='New' id='signmebutt' style="width:200px;" onclick="window.location.reload();" />
			<?php } ?>
		</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td class='label'>Load Port:</td>
		<td class='form'>
			<input class='tbox' type='text' name='load_port' id="load_port" value="<?php echo $load_port; ?>" onblur="copyLoadPort(this.value); getPortDepth(1);" />
			<div class='error' id='error_load_port'>Please Input Load Port</div>
			
			<script type="text/javascript">
			jQuery("#load_port").focus().autocomplete(ports);
			jQuery("#load_port").setOptions({
				scrollHeight: 180
			});
			</script>
		</td>
	</tr>
	<tr style="display:none;">
		<td class='label'>Discharge Port:</td>
		<td class='form'>
			<input class='tbox' type='text' name='discharge_port' id="discharge_port" value="<?php echo $discharge_port; ?>" />
			<div class='error' id='error_discharge_port'>Please Input Discharge Port</div>
			
			<script type="text/javascript">
			jQuery("#discharge_port").focus().autocomplete(ports);
			jQuery("#discharge_port").setOptions({
				scrollHeight: 180
			});
			</script>
		</td>
	</tr>
	<tr>
		<td class='label'>Date:</td>
		<td class='form'>
		  <?php
		  if($cargo_date){
			?>
			<input type="text" name="cargo_date" value="<?php echo $cargo_date; ?>" readonly="readonly" onclick="showCalendar('',this,null,'','',0,5,1)" class="tbox" style="width:90px;" />
			<?php
		  }else{
			?>
			<input type="text" name="cargo_date" value="<?php echo date("M d, Y", time()); ?>" readonly="readonly" onclick="showCalendar('',this,null,'','',0,5,1)" class="tbox" style="width:90px;" />
			<?php
		  }
		  ?>
		</td>
	</tr>
	<tr>
		<td class='label'>DWT or Ship Type:</td>
		<td class='form'>
			<input class='tbox' type='text' name='dwt_or_ship_type' id="dwt_or_ship_type" value="<?php echo $dwt_or_ship_type; ?>" onblur="this.value=fNum(this.value);">
			<div class='error' id='error_dwt_or_ship_type'>Please Input DWT or Ship Type</div></td>
	</tr>
	<tr>
		<td class='label'>Cargo Type:</td>
		<td class='form'>
			<input class='tbox' type='text' name='cargo_type' id="cargo_type" value="<?php echo $cargo_type; ?>">
			<div class='error' id='error_cargo_type'>Please Input Cargo Type</div></td>
	</tr>
	<tr>
		<td class='label'>Cargo Quantity:</td>
		<td class='form'>
			<input class='tbox' type='text' name='cargo_quantity' id="cargo_quantity" value="<?php echo $cargo_quantity; ?>">
			<div class='error' id='error_cargo_quantity'>Please Input Cargo Quantity</div></td>
	</tr>
	<tr>
		<td class='label'>Port Costs:</td>
		<td class='form'>
			<input class='tbox' type='text' name='port_costs' id="port_costs" value="<?php echo $port_costs; ?>" onblur="this.value=fNum(this.value);">
			<div class='error' id='error_port_costs'>Please Input Port Costs</div></td>
	</tr>
	<tr>
		<td class='label'>AVR Intake:</td>
		<td class='form'>
			<input class='tbox' type='text' name='load_port2' id="load_port2" value="<?php echo $load_port2; ?>" onblur="getPortDepth(1);" />
			<div class='error' id='error_load_port2'>Please Input Load Port - AVR Intake</div>
			
			<script type="text/javascript">
			jQuery("#load_port2").focus().autocomplete(ports);
			jQuery("#load_port2").setOptions({
				scrollHeight: 180
			});
			</script>
		</td>
	</tr>
	<tr>
		<td class='label'> Quantity MT:</td>
		<td class='form'><input class='tbox' type='text' name='load_port_quantity' id="load_port_quantity" value="<?php echo $load_port_quantity; ?>" /></td>
	</tr>
	<tr>
		<td class='label'>Channel M:</td>
		<td class='form'>
			<input class='tbox' type='text' name='channel' id="channel1" value="<?php echo $channel; ?>" />
			<div class='error' id='error_channel1'>Add data if known</div>
		</td>
	</tr>
	<tr>
		<td class='label'>Anchorage M:</td>
		<td class='form'>
			<input class='tbox' type='text' name='anchorage' id="anchorage1" value="<?php echo $anchorage; ?>" />
			<div class='error' id='error_anchorage1'>Add data if known</div>
		</td>
	</tr>
	<tr>
		<td class='label'>Cargo Pier M:</td>
		<td class='form'>
			<input class='tbox' type='text' name='cargo_pier' id="cargo_pier1" value="<?php echo $cargo_pier; ?>" />
			<div class='error' id='error_cargo_pier1'>Add data if known</div>
		</td>
	</tr>
	<tr style="display:none;">
		<td class='label'>Discharge Port Name <br />
	    AVR Arrival Intake:  </td>
		<td class='form'>
			<input class='tbox' type='text' name='discharge_port2' id="discharge_port2" value="<?php echo $discharge_port2; ?>" onblur="getPortDepth(2);" />
			<div class='error' id='error_discharge_port2'>Please Input Discharge Port - AVR Arrival Intake</div>
			
			<script type="text/javascript">
			jQuery("#discharge_port2").focus().autocomplete(ports);
			jQuery("#discharge_port2").setOptions({
				scrollHeight: 180
			});
			</script>
		</td>
	</tr>
	<tr style="display:none;">
		<td class='label'> Quantity MT:</td>
		<td class='form'><input class='tbox' type='text' name='discharge_port_quantity' id="discharge_port_quantity" value="<?php echo $discharge_port_quantity; ?>" /></td>
	</tr>
	<tr style="display:none;">
		<td class='label'>Channel M:</td>
		<td class='form'>
			<input class='tbox' type='text' name='channel2' id="channel2" value="<?php echo $channel2; ?>" />
			<div class='error' id='error_channel2'>Add data if known</div>
		</td>
	</tr>
	<tr style="display:none;">
		<td class='label'>Anchorage M:</td>
		<td class='form'>
			<input class='tbox' type='text' name='anchorage2' id="anchorage2" value="<?php echo $anchorage2; ?>" />
			<div class='error' id='error_anchorage2'>Add data if known</div>
		</td>
	</tr>
	<tr style="display:none;">
		<td class='label'>Cargo Pier M:</td>
		<td class='form'>
			<input class='tbox' type='text' name='cargo_pier2' id="cargo_pier2" value="<?php echo $cargo_pier2; ?>" />
			<div class='error' id='error_cargo_pier2'>Add data if known</div>
		</td>
	</tr>
	<tr>
		<td class='label'>Notes:<br />
		  (Visible to all users)
	    :</td>
		<td class='form'><textarea class='tbox' type='text' name='notes' id="notes" style="height:50px;"><?php echo $notes; ?></textarea>
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
		<td class='form'><b>This code is case sensitive <br />
	    (This is a security measure)</b></td>
	</tr>
	<tr>
		<td class='label'>Image Code:</td>
		<td class='form'>
			<input class='tbox' type='text' name="captcha_code" id="captcha_code">
			<div class='error' id='error_captcha_code'>Please Input Captcha Code</div></td>
	</tr>									
	<tr>
		<td class='signme'>&nbsp;</td>
		<td class='signme'>
			<span class="signme">
				<input type='submit' name="signmebutt" value='Save' id='signmebutt' />
			</span></td>
	</tr>
</table>
</div>
</form>
<div style="float:left; width:980px; height:auto; padding-left:20px;">  
<table id="flexigrid" align="left"></table>
<script type="text/javascript">
$("document").ready(function(){
vars = {
		url: 'js/grid/jquery_post/post_cargos.php',
		dataType: 'json',
		colModel : [
			{display: '-', name : 'actions', width : 50, sortable : false, searchable: false, align: 'center'},
			{display: '#', name : 'id', width : 50, sortable : true, align: 'center'},
			{display: 'Load Port', name : 'load_port', width : 130, sortable : true, align: 'left'}, 
			/*{display: 'Discharge Port', name : 'discharge_port', width : 130, sortable : true, align: 'left'}, */
			{display: 'Date', name : 'cargo_date', width : 100, sortable : true, align: 'left'}, 
			{display: 'DWT or Ship Type', name : 'dwt_or_ship_type', width : 130, sortable : true, align: 'left'}, 
			{display: 'Cargo', name : 'cargo_type', width : 130, sortable : true, align: 'left'}, 
			{display: 'Cargo Qty', name : 'cargo_quantity', width : 50, sortable : true, align: 'left'}, 
			{display: 'Port Costs', name : 'port_costs', width : 50, sortable : true, align: 'left'}, 
			{display: 'AVR Intake', name : 'load_port2', width : 130, sortable : true, align: 'left'}, 
			{display: 'Qty MT', name : 'load_port_quantity', width : 130, sortable : true, align: 'left'}, 
			{display: 'Channel M', name : 'channel', width : 130, sortable : true, align: 'left'}, 
			{display: 'Anchorage M', name : 'anchorage', width : 130, sortable : true, align: 'left'}, 
			{display: 'Cargo Pier M', name : 'cargo_pier', width : 130, sortable : true, align: 'left'}, 
			/*{display: 'Discharge Port AVR Intake', name : 'discharge_port2', width : 130, sortable : true, align: 'left'}, 
			{display: 'Qty MT', name : 'discharge_port_quantity', width : 130, sortable : true, align: 'left'}, 
			{display: 'Channel M', name : 'channel2', width : 130, sortable : true, align: 'left'}, 
			{display: 'Anchorage M', name : 'anchorage2', width : 130, sortable : true, align: 'left'}, 
			{display: 'Cargo Pier M', name : 'cargo_pier2', width : 130, sortable : true, align: 'left'}, */
			{display: 'Agent', name : 'by_agent', width : 130, sortable : true, align: 'left'}, 
			{display: 'Date Added', name : 'dateadded', width : 130, sortable : true, align: 'left'}
			/*, 
			{display: 'Date Updated', name : 'dateupdated', width : 130, sortable : true, align: 'left'}*/
		],
		buttons : [],
		resizable: false,
		sortname: "id",
		sortorder: "asc",
		usepager: true,
		title: "Cargo Card Lists",
		useRp: true,
		rp: 20,
		showTableToggleBtn: false,
		autoload: true,
		width: 980,
		height: 725,
		singleSelect: true,
		useInlineSearch: true
		};
				
$("#flexigrid").flexigrid( vars );							 
});
</script>
</div>