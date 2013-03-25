<?php
@include_once(dirname(__FILE__)."/includes/bootstrap.php");
date_default_timezone_set('UTC');
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
		arr_fields = ['dwt', 'cargo', 'costs', 'load_port', 'discharge_port'];
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
			$('#captcha_image').attr('src', 'captcha/securimage_show.php?sid=' + Math.random());								
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
				$('#captcha_image').attr('src', 'captcha/securimage_show.php?sid=' + Math.random());
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
</script>
<form id='cargoform' method="post">
<input type="hidden" name="trigger" value="save_new_cargo"  />
<input type='hidden' name='id' id="id" value="<?php echo $r[0]['id']; ?>">   
<div style="float:left; width:500px; height:auto;">                           
<table style='width:500px' id="signup">								
	<tr>
		<td colspan="2"><h2>Cargo Details</h2></td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td class='label'><span class="required">*</span>DWT:</td>
		<td class='form'>
			<input class='tbox' type='text' name='dwt' id="dwt" value="<?php echo $r[0]['dwt']; ?>">
			<div class='error' id='error_dwt'>Please Input DWT</div></td>
	</tr>
	<tr>
		<td class='label'><span class="required">*</span>Cargo:</td>
		<td class='form'>
			<input class='tbox' type='text' name='cargo' id="cargo" value="<?php echo $r[0]['cargo']; ?>">
			<div class='error' id='error_cargo'>Please Input Cargo</div></td>
	</tr>		
	<tr>
		<td class='label'><span class="required">*</span>Costs:</td>
		<td class='form'>
			<input class='tbox' type='text' name='costs' id="costs" value="<?php echo $r[0]['costs']; ?>">
			<div class='error' id='error_costs'>Please Input Costs</div></td>
	</tr>
	<tr>
		<td class='label'><span class="required">*</span>Date:</td>
		<td class='form'>
		  <?php
		  if($r[0]['cargo_date']){
			?>
			<input type="text" name="cargo_date" value="<?php echo $r[0]['cargo_date']; ?>" readonly="readonly" onclick="showCalendar('',this,null,'','',0,5,1)" class="tbox" style="width:90px;" />
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
		<td class='label'><span class="required">*</span>Load Port</td>
		<td class='form'>
			<input class='tbox' type='text' name='load_port' id="load_port" value="<?php echo $r[0]['load_port']; ?>" />
			<div class='error' id='error_load_port'>Please Input Load Port</div>
			
			<script type="text/javascript">
			jQuery("#load_port").focus().autocomplete(ports);
			jQuery("#load_port").setOptions({
				scrollHeight: 180
			});
			</script>
		</td>
	</tr>
	<tr>
		<td class='label'><span class="required">*</span>Discharge Port</td>
		<td class='form'>
			<input class='tbox' type='text' name='discharge_port' id="discharge_port" value="<?php echo $r[0]['discharge_port']; ?>" />
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
				<input type='submit' name="signmebutt" value='Save' id='signmebutt' />
			</span></td>
	</tr>
</table>
</div>
</form>
<div style="float:left; width:780px; height:auto; padding-left:20px;">  
<table id="flexigrid" align="left"></table>
<script type="text/javascript">
$("document").ready(function(){
vars = {
		url: 'js/grid/jquery_post/post_cargos.php',
		dataType: 'json',
		colModel : [
			{display: '-', name : 'actions', width : 50, sortable : false, searchable: false, align: 'center'},
			{display: '#', name : 'id', width : 50, sortable : true, align: 'center'},
			{display: 'DWT', name : 'dwt', width : 50, sortable : true, align: 'left'},
			{display: 'Cargo', name : 'cargo', width : 200, sortable : true, align: 'left'},
			{display: 'Costs', name : 'costs', width : 50, sortable : true, align: 'left'},
			{display: 'By Agent', name : 'by_agent', width : 100, sortable : true, align: 'left'},
			{display: 'Date Updated', name : 'dateupdated ', width : 130, sortable : true, align: 'left'}
		],
		buttons : [],
		resizable: false,
		sortname: "id",
		sortorder: "asc",
		usepager: true,
		title: "Cargo Lists",
		useRp: true,
		rp: 20,
		showTableToggleBtn: false,
		autoload: true,
		width: 780,
		height: 400,
		singleSelect: true,
		useInlineSearch: true
		};
				
$("#flexigrid").flexigrid( vars );							 
});
</script>
</div>