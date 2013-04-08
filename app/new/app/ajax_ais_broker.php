<!--AIS BROKER SEARCH-->
<?php @include_once(dirname(__FILE__)."/includes/bootstrap.php"); ?>
<style>
body{
	margin-top:10px;
}

td{
	border-bottom:none;
}

.divclass_active{
	float:left;
	background-color: #FFFFFF;
	color: #3ac3d3;
	border: 2px solid #c9c9c9;
	padding: 5px;
}
.divclass{
	float:left;
	background-color: #c9c9c9;
	color: #666666;
	border: 2px solid #c9c9c9;
	padding: 5px;
}
</style>
<link rel="stylesheet" href="css/stylesheets.css">
<script type='text/javascript' src='js/jscript.js'></script>
<link rel="stylesheet" href="js/development-bundle/themes/base/jquery.ui.all.css">
<script type="text/javascript" src="js/development-bundle/ui/jquery.ui.core.js"></script>
<script type="text/javascript" src="js/development-bundle/ui/jquery.ui.widget.js"></script>
<script type="text/javascript" src="js/development-bundle/ui/jquery.ui.dialog.js"></script>

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

<script>
$(document).ready(function() {
	<?php
	if(isset($_GET['action'])){
		if($_GET['action']==1){
			?>showTable(1, 2);<?php
		}else if($_GET['action']==2){
			?>showTable(2, 1);<?php
		}
	}
	?>
});

function jTabs(d){
	lis = jQuery(d+" li[name|=fragment]");
	lis.css({"cursor":"pointer"});

	jQuery(d+" ul").addClass("tabs-nav");

	if(document.all){
		jQuery(d+" ul.tabs-nav").css({height:"39px"}); 
	}

	jQuery(d+" li[name|=fragment]").removeClass("tabs-selected");
	jQuery("[id|=fragment]").hide();

	firstx = jQuery(d+" li[name|=fragment]")[0];
	firstx.className = "tabs-selected";		

	namex = jQuery(d+" li[name|=fragment]").attr('name');

	jQuery('#'+namex).show();	

	lis.click( 
		function(){
			jQuery(d+" li[name|=fragment]").removeClass("tabs-selected");
			jQuery("[id|=fragment]").hide();

			namex = jQuery(this).attr('name');

			jQuery(this).addClass("tabs-selected");
			jQuery('#'+namex).show();
		}
	);
}

function shipSearchx(num){
	jQuery("#container-1").html("");

	globalfetch = false;

	jQuery("#shipdetails").dialog("close");
	jQuery('#sresults').hide();
	jQuery('#pleasewait').show();

	jQuery("#sbutton").val("SEARCHING...");
	jQuery("#sbutton")[0].disabled = true;
	
	jQuery("#sbutton2").val("SEARCHING...");
	jQuery("#sbutton2")[0].disabled = true;

	jQuery.ajax({
		type: 'GET',
		url: "search_ajax8ve.php?num="+num,
		data: jQuery("#searchform").serialize(),

		success: function(data) {
			jQuery("#sbutton").val("SEARCH");
			jQuery("#sbutton")[0].disabled = false;
			
			jQuery("#sbutton2").val("SEARCH");
			jQuery("#sbutton2")[0].disabled = false;

			if(data.indexOf("<b>ERROR")!=0){
				jQuery("#container-1").html(data);
				jQuery("#searchtabdata").val(jQuery("#searchform").serialize());

				try{
					jTabs('#container-1');
				}

				catch(e){

				}

				jQuery('#sresults').show();
				jQuery('#pleasewait').hide();

				setTimeout("jQuery('#searchform').slideUp('slow')", 500);

				toggleParams('up');

				globalfetch = true;
			}else{
				data = data.replace("<b>ERROR</b>:", "");

				alert(data);

				jQuery('#pleasewait').hide();
			}
		}
	});
}

function changeCssClass(objDivID){
	if(document.getElementById(objDivID).className=='divclass_active'){
		document.getElementById(objDivID).className = 'divclass';
	}else{
		document.getElementById(objDivID).className = 'divclass_active';

	}
}

function showShipDetails(imo){
	jQuery("#shipdetails").dialog("close")
	jQuery('#pleasewait').show();

	jQuery.ajax({
		type: 'POST',
		url: "search_ajax1ve.php?imo="+imo,
		data:  '',

		success: function(data) {
			if(data.indexOf("<b>ERROR")!=0){
				jQuery("#shipdetails_in").html(data);
				jQuery("#shipdetails").dialog("open")
				jQuery('#pleasewait').hide();
			}else{
				alert(data)
			}
		}
	});	
}

function ownerDetails(owner, owner_id){
	var iframe = $("#contactiframe");

	$(iframe).contents().find("body").html("");

	jQuery("#contactiframe")[0].src='search_ajax1ve.php?contact=1&owner='+owner+'&owner_id='+owner_id;
	jQuery("#contactdialog").dialog("open");
}

function csvIt(report){
	chk = jQuery("#positions input[type=checkbox]");
	g = "";

	for(i=0; i<chk.length; i++){
		if(chk[i].checked&&chk[i].value){
			g += "imo[]="+chk[i].value+"&";
		}
	}

	if(g!=""){
		self.location = "misc/csv_ab.php?"+g+"report="+report;
	}else{
		alert("You must select ships to download. Check checkboxes to select.")
	}

}

function mailIt(report){
	chk = jQuery("#positions input[type=checkbox]");
	g = "";

	for(i=0; i<chk.length; i++){
		if(chk[i].checked&&chk[i].value){
			g += "imo[]="+chk[i].value+"&";
		}

	}

	if(g!=""){
		jQuery("#misciframe")[0].src="misc/email_ab.php?"+g+"report="+report;
		jQuery("#miscdialog").dialog("open");
	}else{
		alert("You must select ships to print. Check checkboxes to select.")
	}
}

function printIt(report){
	chk = jQuery("#positions input[type=checkbox]");
	g = "";

	for(i=0; i<chk.length; i++){
		if(chk[i].checked&&chk[i].value){
			g += "imo[]="+chk[i].value+"&";
		}
	}

	if(g!=""){
		jQuery("#misciframe")[0].src="misc/print_ab.php?"+g+"report="+report;
		jQuery("#miscdialog").dialog("open");
	}else{
		alert("You must select ships to print. Check checkboxes to select.")
	}
}

function csvIt1(report){
	chk = jQuery("#fixtures input[type=checkbox]");
	g = "";

	for(i=0; i<chk.length; i++){
		if(chk[i].checked&&chk[i].value){
			g += "imo[]="+chk[i].value+"&";
		}
	}

	if(g!=""){
		self.location = "misc/csv1_ab.php?"+g+"report="+report;
	}else{
		alert("You must select ships to download. Check checkboxes to select.")
	}

}

function mailIt1(report){
	chk = jQuery("#fixtures input[type=checkbox]");
	g = "";

	for(i=0; i<chk.length; i++){
		if(chk[i].checked&&chk[i].value){
			g += "imo[]="+chk[i].value+"&";
		}

	}

	if(g!=""){
		jQuery("#misciframe")[0].src="misc/email1_ab.php?"+g+"report="+report;
		jQuery("#miscdialog").dialog("open");
	}else{
		alert("You must select ships to print. Check checkboxes to select.")
	}
}

function printIt1(report){
	chk = jQuery("#fixtures input[type=checkbox]");
	g = "";

	for(i=0; i<chk.length; i++){
		if(chk[i].checked&&chk[i].value){
			g += "imo[]="+chk[i].value+"&";
		}
	}

	if(g!=""){
		jQuery("#misciframe")[0].src="misc/print1_ab.php?"+g+"report="+report;
		jQuery("#miscdialog").dialog("open");
	}else{
		alert("You must select ships to print. Check checkboxes to select.")
	}
}

function mailItVe_2(imo){
	jQuery("#misciframe")[0].src="misc/email_ve_2.php?imo="+imo;
	jQuery("#miscdialog").dialog("open");
}

function printItVe_2(imo){
	jQuery("#misciframe")[0].src="misc/print_ve_2.php?imo="+imo;
	jQuery("#miscdialog").dialog("open");
}

function checkAll(idx, obj){
	if(obj.checked){
		jQuery("#"+idx+" input[type=checkbox]").attr("checked", true)
	}else{
		jQuery("#"+idx+" input[type=checkbox]").attr("checked", false)
	}
}

function oUpdate(id){
	jQuery("#oUpdate"+id).attr("width", "100%");
	jQuery("#oUpdate"+id).toggle();
}

function oUpdatev(id){
	jQuery("#oUpdatev"+id).attr("width", "100%");
	jQuery("#oUpdatev"+id).toggle();
}

function oUpdateo1(id){
	jQuery("#oUpdateo1"+id).attr("width", "100%");
	jQuery("#oUpdateo1"+id).toggle();
}

function oUpdateo2(id){
	jQuery("#oUpdateo2"+id).attr("width", "100%");
	jQuery("#oUpdateo2"+id).toggle();
}

function oUpdateo3(id){
	jQuery("#oUpdateo3"+id).attr("width", "100%");
	jQuery("#oUpdateo3"+id).toggle();
}

function oUpdater1(id){
	jQuery("#oUpdater1"+id).attr("width", "100%");
	jQuery("#oUpdater1"+id).toggle();
}

function oUpdater2(id){
	jQuery("#oUpdater2"+id).attr("width", "100%");
	jQuery("#oUpdater2"+id).toggle();
}

function showLearnDialog(learnwhat){
	jQuery.ajax({
	  type: 'GET',
	  url: "learn.php?topic="+learnwhat+"&t="+(new Date()).getTime(),
	  data:  "",

	  success: function(data) {
		jQuery('#learncontent').html(data);
		jQuery( "#learndialog" ).dialog("open");
	  }
	});
}

function openZoneMap(zone){
	jQuery("#zonemapiframe")[0].src='map/zone2.php?zone='+zone+"&t="+(new Date()).getTime();
	jQuery( "#zonemapdialog" ).dialog( { autoOpen: false, width: '90%', height: jQuery(window).height()*0.9 });
	jQuery("#zonemapdialog").dialog("open");
}

function openOptMap(opt){
	opt = opt.replace("_", ",")

	jQuery("#zonemapiframe")[0].src='map/zone2.php?zone='+opt+"&t="+(new Date()).getTime();
	jQuery( "#zonemapdialog" ).dialog( { autoOpen: false, width: '90%', height: jQuery(window).height()*0.9 });
	jQuery("#zonemapdialog").dialog("open");
}

globalfetch = false;

function fetchMessagesCron(){
	if(globalfetch){
		fetchMessages();

		setTimeout("fetchMessagesCron()", 60000);
	}
}

function fetchMessages(){
	pmessages = jQuery(".pmessages");

	pmstr  = "";

	for(i=0; i<pmessages.length; i++){
		pmstr += pmessages[i].value+"|";
	}

	nmessages = jQuery(".nmessages");

	nmstr  = "";

	for(i=0; i<nmessages.length; i++){
		nmstr += nmessages[i].value+"|";
	}

	mids = pmstr+nmstr;

	jQuery.ajax({
		type: 'POST',
		url: "search_ajax1ve.php?action=getmessages&task=fetchmessages",
		data:  "mids="+mids,
		dataType: "json",

		success: function(data) {
			for(x in data){
				if(x.indexOf("useremail")==0&&data[x].user_email){
					jQuery("#"+x).html(data[x].user_email);
					
					if(data[x].opened==false){
						jQuery("#"+x).css({"color":"red"});
					}else{
						jQuery("#"+x).css({"color":"black"});
					}
				}else{
					jQuery("#"+x).html(data[x].short);
					jQuery("#"+x).parent().attr("title", data[x].long);
					jQuery("#"+x).parent().attr("alt", data[x].long);
					jQuery("#"+x).parent().attr("id", data[x].mid);

					if(data[x].opened==false){
						jQuery("#"+x).css({"color":"red"});
					}else{
						jQuery("#"+x).css({"color":"black"});
					}
				}
			}
		}
	});	
}

function openMessageDialog(mid, imo, type){
	jQuery("#messageiframe")[0].src="search_ajax1ve.php?action=getmessages&type="+type+"&mid="+mid+"&imo="+imo+"&t="+(new Date()).getTime();
	jQuery( "#messagedialog" ).dialog( { autoOpen: false, width: '920', height: jQuery(window).height()*0.9 });
	jQuery("#messagedialog").dialog("open");
}

jQuery( "#didyouknowdialog" ).dialog( { autoOpen: false, width: 650, height: 390 });
jQuery( "#didyouknowdialog" ).dialog("close");

jQuery( "#learndialog" ).dialog( { autoOpen: false, width: 600, height: 360 });
jQuery( "#learndialog" ).dialog("close");	

jQuery("#shipdetails").dialog( { autoOpen: false, width: '90%', height: jQuery(window).height()*0.9 });
jQuery("#shipdetails").dialog("close");

jQuery( "#mapdialog" ).dialog( { autoOpen: false, width: '90%', height: jQuery(window).height()*0.9 });
jQuery("#mapdialog").dialog("close");

jQuery( "#zonemapdialog" ).dialog( { autoOpen: false, width: '90%', height: jQuery(window).height()*0.9 });
jQuery("#zonemapdialog").dialog("close");

jQuery( "#miscdialog" ).dialog( { autoOpen: false, width: 1100, height: 500 });
jQuery( "#miscdialog" ).dialog("close");

jQuery("#contactdialog").dialog( { autoOpen: false, width: 900, height: 460 });
jQuery("#contactdialog").dialog("close");

jQuery( "#messagedialog" ).dialog( { autoOpen: false, width: 920, height: 460,
	close: function (event, ui){
		fetchMessages();
	}
});
jQuery( "#messagedialog" ).dialog("close");

function expand(tid, imo, type){
	if(type=='shore'){
		if($('#'+tid+'_img').attr('src')=='images/icon_pullup_warning_shore.png'){
			$('#'+tid+'_img').attr('src', 'images/icon_dropdown_warning_shore.png');
			
			jQuery('#'+tid).hide();
			
			return 0;
		}else if($('#'+tid+'_img').attr('src')=='images/icon_pullup.png'){
			$('#'+tid+'_img').attr('src', 'images/icon_dropdown.png');
			
			jQuery('#'+tid).hide();
			
			return 0;
		}
	}else if(type=='broker'){
		if($('#'+tid+'_img').attr('src')=='images/icon_pullup_warning_broker.png'){
			$('#'+tid+'_img').attr('src', 'images/icon_dropdown_warning_broker.png');
			
			jQuery('#'+tid).hide();
			
			return 0;
		}else if($('#'+tid+'_img').attr('src')=='images/icon_pullup.png'){
			$('#'+tid+'_img').attr('src', 'images/icon_dropdown.png');
			
			jQuery('#'+tid).hide();
			
			return 0;
		}
	}else if(type=='email'){
		if($('#'+tid+'_img').attr('src')=='images/icon_pullup_warning_email.png'){
			$('#'+tid+'_img').attr('src', 'images/icon_dropdown_warning_email.png');
			
			jQuery('#'+tid).hide();
			
			return 0;
		}else if($('#'+tid+'_img').attr('src')=='images/icon_pullup.png'){
			$('#'+tid+'_img').attr('src', 'images/icon_dropdown.png');
			
			jQuery('#'+tid).hide();
			
			return 0;
		}
	}
	
	jQuery('#pleasewait').show();
	
	jQuery.ajax({
		type: 'GET',
		url: 'updates_ajax.php?imo='+imo+'&type='+type,
		data: '',

		success: function(data) {
			jQuery('#pleasewait').hide();
			
			if(type=='shore'){
				if($('#'+tid+'_img').attr('src')=='images/icon_dropdown_warning_shore.png'){
					$('#'+tid+'_img').attr('src', 'images/icon_pullup_warning_shore.png');
				}else if($('#'+tid+'_img').attr('src')=='images/icon_dropdown.png'){
					$('#'+tid+'_img').attr('src', 'images/icon_pullup.png');
				}
			}else if(type=='broker'){
				if($('#'+tid+'_img').attr('src')=='images/icon_dropdown_warning_broker.png'){
					$('#'+tid+'_img').attr('src', 'images/icon_pullup_warning_broker.png');
				}else if($('#'+tid+'_img').attr('src')=='images/icon_dropdown.png'){
					$('#'+tid+'_img').attr('src', 'images/icon_pullup.png');
				}
			}else if(type=='email'){
				if($('#'+tid+'_img').attr('src')=='images/icon_dropdown_warning_email.png'){
					$('#'+tid+'_img').attr('src', 'images/icon_pullup_warning_email.png');
				}else if($('#'+tid+'_img').attr('src')=='images/icon_dropdown.png'){
					$('#'+tid+'_img').attr('src', 'images/icon_pullup.png');
				}
			}
			
			jQuery('#'+tid).html(data);
			jQuery('#'+tid).show();
			jQuery('#'+tid).fadeIn(200);
		}
	});
}

function showTable(s, h){
	if(jQuery('#paramicon').attr('src')=='images/down.png'){
		setTimeout("jQuery('#searchform').slideDown('slow')", 500);
		toggleParams();
	}
	
	jQuery('#sresults').hide();

	jQuery("#option_"+h).attr('class', 'content_link');
	jQuery("#option_"+s).attr('class', 'content_link_selected');

	jQuery('#table_'+h).hide();
	jQuery('#table_'+s).show();
	
	jQuery("#option_num_id").val(s);
}

function saveScenario(){
	if(jQuery('#option_num_id').val()==1){
		if(jQuery('#suggest1').val()){
			jQuery('#pleasewait').show();
		
			jQuery.ajax({
				type: "POST",
				url: "ajax.php?new_search=1",
				data: jQuery("#searchform").serialize(),
		
				success: function(data) {
					alert("Scenario Saved!");
				
					self.location = "s-bis.php?new_search=1&action="+jQuery('#option_num_id').val();
				}
			});
		}else{
			alert("Please select a destination port.");
		}
	}else if(jQuery('#option_num_id').val()==2){
		jQuery('#pleasewait').show();
	
		jQuery.ajax({
			type: "POST",
			url: "ajax.php?new_search=1",
			data: jQuery("#searchform").serialize(),
	
			success: function(data) {
				alert("Scenario Saved!");
			
				self.location = "s-bis.php?new_search=1&action="+jQuery('#option_num_id').val();
			}
		});
	}
}

function deleteScenario(tabid){
	if (confirm("Are you sure you want to delete?")) {
		jQuery('#pleasewait').show();
		
		jQuery.ajax({
			type: "POST",
			url: "ajax.php?new_search=1&tabid="+tabid,
			data: jQuery("#searchform").serialize(),
	
			success: function(data) {
				alert("Scenario Deleted!");
			
				self.location = "s-bis.php?new_search=1";
			}
		});
	}
}

function newScenario(){
	jQuery('#pleasewait').show();
	
	self.location = "s-bis.php?new_search=0&action="+jQuery('#option_num_id').val();
}
</script>

<center>
<div id="shipdetails" title="SHIP DETAILS" style='display:none;'>
	<div id='shipdetails_in'></div>
</div>

<div id="contactdialog" title="CONTACT"  style='display:none'>
	<iframe id='contactiframe' frameborder="0" height="100%" width="100%"></iframe>
</div>

<div id="mapdialog" title="MAP - CLICK ON THE SHIP IMAGE BELOW TO SHOW DETAILS" style='display:none'>
	<iframe id='mapiframe' name='mapname' frameborder=0 height="100%" width="100%" style='border:0px; height:100%; width:100%'></iframe>
</div>

<div id="didyouknowdialog" title=""  style='display:none'>
	<div id='didyouknowcontent'></div>
</div>

<div id="learndialog" title=""  style='display:none'>
	<div id='learncontent' style='font-size:11px;'></div>
</div>

<div id="zonemapdialog" title="ZONE MAP" style='display:none'>
	<iframe id='zonemapiframe' frameborder=0 height="100%" width="100%" style='border:0px; height:100%; width:100%'></iframe>
</div>

<div id="miscdialog" title=""  style='display:none'>
	<iframe id='misciframe' frameborder='0' height="100%" width="1100px" style='border:0px; height:100%; width:1050px;'></iframe>
</div>

<div id="messagedialog" title="MESSAGES"  style='display:none'>
	<iframe id='messageiframe' frameborder=0 height="100%" width="100%" style='border:0px; height:100%; width:100%'></iframe>
</div>
</center>

<?php
if(isset($_GET['tabid'])){
	if($_GET['action']==1){
		$sql1 = "SELECT * FROM `_user_tabs` WHERE `id`='".$_GET['tabid']."'";
		$r1 = dbQuery($sql1, $link);
	}else if($_GET['action']==2){
		$sql2 = "SELECT * FROM `_user_tabs` WHERE `id`='".$_GET['tabid']."'";
		$r2 = dbQuery($sql2, $link);
	}
}else{
	$sql1 = "SELECT * FROM `_user_tabs` WHERE `uid`='".$user['uid']."' AND `page`='aisbroker' AND `option`='1' ORDER BY `dateadded` DESC LIMIT 0,1";
	$r1 = dbQuery($sql1, $link);
	
	$sql2 = "SELECT * FROM `_user_tabs` WHERE `uid`='".$user['uid']."' AND `page`='aisbroker' AND `option`='2' ORDER BY `dateadded` DESC LIMIT 0,1";
	$r2 = dbQuery($sql2, $link);
}

if(trim($r1)){
	$tabid1 = $r1[0]['id'];
	$tabname1 = $r1[0]['tabname'];
	$tabdata1 = unserialize($r1[0]['tabdata']);
	
	$destination_port = $tabdata1['destination_port'];
	$destination_port_from = $tabdata1['destination_port_from'];
	$destination_port_to = $tabdata1['destination_port_to'];
	$dwt_range = $tabdata1['dwt_range'];
	$vessel_type = $tabdata1['vessel_type'];
}

if(trim($r2)){
	$tabid2 = $r2[0]['id'];
	$tabname2 = $r2[0]['tabname'];
	$tabdata2 = unserialize($r2[0]['tabdata']);
	
	$destination_port_from2 = $tabdata2['destination_port_from2'];
	$destination_port_to2 = $tabdata2['destination_port_to2'];
	$dwt_range2 = $tabdata2['dwt_range2'];
	$vessel_type2 = $tabdata2['vessel_type2'];
	$zone = $tabdata2['zone'];
}

if(isset($_GET['new_search'])){
	if($_GET['new_search']==0){
		if($_GET['action']==1){
			$tabid1 = '';
			$tabname1 = '';
			$destination_port = '';
			$destination_port_from = '';
			$destination_port_to = '';
			$dwt_range = '';
			$vessel_type = '';
		}else if($_GET['action']==2){
			$tabid2 = '';
			$tabname2 = '';
			$destination_port_from2 = '';
			$destination_port_to2 = '';
			$dwt_range2 = '';
			$vessel_type2 = '';
			$zone = '';
		}
	}
}
?>

<div style="width:1000px; height:auto; margin:0 auto;">
	<div>
		<a class="content_link_selected" id="option_1" onclick="showTable(1, 2);">SEARCH USING PORT NAME</a> &nbsp; 
		<a class="content_link" id="option_2" onclick="showTable(2, 1);">SEARCH USING ZONE/REGION</a>
	</div>
	<div style="border-bottom:1px dotted #FFF;">&nbsp;</div>
	<div>&nbsp;</div>
	<div style='cursor:pointer;' onclick="jQuery('#searchform').slideToggle('slow', function(){ toggleParams(); })"><a name='params' style='font-size:18px; font-weight:bold;'>PARAMETERS</a> &nbsp; <img src='images/up.png' id='paramicon'></div>
	<form method="post" id='searchform' name='searchform' enctype="multipart/form-data">
		<div style="border-bottom:1px dotted #FFF;">&nbsp;</div>
		<div>&nbsp;<input id='option_num_id' name="option_num" type="hidden" value="1" />&nbsp;</div>
		<div><input type="button" value="Save Scenario" onclick="saveScenario();" style="border:1px solid #666666; background-color:#333333; color:#FFFFFF; cursor:pointer; padding:5px 10px;" /></div>
		<div style="border-bottom:1px dotted #FFF;">&nbsp;</div>
		<div>&nbsp;</div>
		<table width="1000" border="0" cellpadding="0" cellspacing="0" id="table_1">
		
			<?php
			$sql = "SELECT * FROM `_user_tabs` WHERE `uid`='".$user['uid']."' AND `page`='aisbroker' AND `option`='1' ORDER BY `dateadded` DESC";
			$r = dbQuery($sql, $link);
			
			$t = count($r);
			
			if(trim($t)){
				echo '<tr>';
				echo '<td colspan="2" style="border-bottom:none; padding-top:10px;">';
				echo '<div style="float:left; width:auto; height:auto; padding-right:30px;"><input type="button" value="+ New Scenario" onclick="newScenario();" style="border:1px solid #666666; background-color:#333333; color:#FFFFFF; cursor:pointer; padding:5px 10px;" /></div>';
				
				for($i=0; $i<$t; $i++){
					$tabdata = unserialize($r[$i]['tabdata']);
					
					if($r[$i]['tabname']){
						if(isset($_GET['tabid'])){
							if($_GET['tabid']==$r[$i]['id']){
								echo '<div style="float:left; width:auto; height:auto; background-color:#CCC; color:#666; padding:5px 10px; border:1px solid #FFF;">';
								echo '<div style="float:left; width:15px; height:auto;"><img src="images/close.png" width="14" height="14" border="0" alt="Delete this scenario" title="Delete this scenario" style="cursor:pointer;" onclick="deleteScenario(\''.$r[$i]['id'].'\');" /></div>';
								echo '<div style="float:left; width:auto; height:auto;">'.$r[$i]['tabname'].'</div>';
								echo '</div>';
							}else{
								echo '<div style="float:left; width:auto; height:auto; background-color:#666; color:#FFF; padding:5px 10px; border:1px solid #000;">';
								echo '<div style="float:left; width:15px; height:auto;"><img src="images/close.png" width="14" height="14" border="0" alt="Delete this scenario" title="Delete this scenario" style="cursor:pointer;" onclick="deleteScenario(\''.$r[$i]['id'].'\');" /></div>';
								echo '<div onclick="location.href=\'s-bis.php?new_search=1&action=1&tabid='.$r[$i]['id'].'\'" class="clickable" style="float:left; width:auto; height:auto; color:#FFF;">'.$r[$i]['tabname'].'</div>';
								echo '</div>';
							}
						}else{
							if($i==0){
								if(isset($_GET['new_search'])){
									if($_GET['new_search']==0){
										echo '<div style="float:left; width:auto; height:auto; background-color:#666; color:#FFF; padding:5px 10px; border:1px solid #000;">';
										echo '<div style="float:left; width:15px; height:auto;"><img src="images/close.png" width="14" height="14" border="0" alt="Delete this scenario" title="Delete this scenario" style="cursor:pointer;" onclick="deleteScenario(\''.$r[$i]['id'].'\');" /></div>';
										echo '<div onclick="location.href=\'s-bis.php?new_search=1&action=1&tabid='.$r[$i]['id'].'\'" class="clickable" style="float:left; width:auto; height:auto; color:#FFF;">'.$r[$i]['tabname'].'</div>';
										echo '</div>';
									}
								}else{
									echo '<div style="float:left; width:auto; height:auto; background-color:#CCC; color:#666; padding:5px 10px; border:1px solid #FFF;">';
									echo '<div style="float:left; width:15px; height:auto;"><img src="images/close.png" width="14" height="14" border="0" alt="Delete this scenario" title="Delete this scenario" style="cursor:pointer;" onclick="deleteScenario(\''.$r[$i]['id'].'\');" /></div>';
									echo '<div style="float:left; width:auto; height:auto;">'.$r[$i]['tabname'].'</div>';
									echo '</div>';
								}
							}else{
								echo '<div style="float:left; width:auto; height:auto; background-color:#666; padding:5px 10px; border:1px solid #000;">';
								echo '<div style="float:left; width:15px; height:auto;"><img src="images/close.png" width="14" height="14" border="0" alt="Delete this scenario" title="Delete this scenario" style="cursor:pointer;" onclick="deleteScenario(\''.$r[$i]['id'].'\');" /></div>';
								echo '<div onclick="location.href=\'s-bis.php?new_search=1&action=1&tabid='.$r[$i]['id'].'\'" class="clickable" style="float:left; width:auto; height:auto; color:#FFF;">'.$r[$i]['tabname'].'</div>';
								echo '</div>';
							}
						}
					}
					
					echo '<div style="float:left; width:auto; height:auto;">&nbsp;&nbsp;</div>';
				}
				
				echo '</td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td colspan="2">
					<div style="border-bottom:1px dotted #FFF;">&nbsp;</div>
					<div>&nbsp;</div>
				</td>';
				echo '</tr>';
			}
			?>
		
		  <tr>
			<td width="450" valign="top">
			  <table width="450" border="0" cellpadding="0" cellspacing="0">
				<tr>
				  <td width="160">DESTINATION PORT</td>
				  <td width="10">&nbsp;</td>
				  <td>
				  	<input type="hidden" id="tabid1" name="tabid1" value="<?php echo $tabid1; ?>" />
					<input id='suggest1' type="text" name="destination_port" class="input_1" style='width:210px;' value="<?php echo $destination_port; ?>" />
					
					<script type="text/javascript">
					jQuery("#suggest1").focus().autocomplete(ports);
					jQuery("#suggest1").setOptions({
						scrollHeight: 180
					});
					</script>
				  </td>
				</tr>
				<tr>
				  <td height="5" colspan="3"></td>
				</tr>
				<tr>
				  <td>LAYCAN</td>
				  <td width="10">&nbsp;</td>
				  <td>
				  <?php
				  if($destination_port_from){
				  	?>
					<input type="text" name="destination_port_from" value="<?php echo $destination_port_from; ?>" readonly="readonly" onclick="showCalendar('',this,null,'','',0,5,1)" class="input_1" style="width:90px;" />
					<?php
				  }else{
				  	?>
					<input type="text" name="destination_port_from" value="<?php echo date("M d, Y", time()); ?>" readonly="readonly" onclick="showCalendar('',this,null,'','',0,5,1)" class="input_1" style="width:90px;" />
					<?php
				  }
				  ?>
		
					to 
					
				  <?php
				  if($destination_port_to){
				  	?>
					<input type="text" name="destination_port_to" value="<?php echo $destination_port_to; ?>" readonly="readonly" onclick="showCalendar('',this,null,'','',0,5,1)" class="input_1" style="width:90px;" />
					<?php
				  }else{
				  	?>
					<input type="text" name="destination_port_to" value="<?php echo date("M d, Y", time()+(7*24*60*60)); ?>" readonly="readonly" onclick="showCalendar('',this,null,'','',0,5,1)" class="input_1" style="width:90px;" />
					<?php
				  }
				  ?>
				  </td>
				</tr>
				<tr>
				  <td height="5" colspan="3"></td>
				</tr>
				<tr>
				  <td>DWT RANGE</td>
				  <td width="10">&nbsp;</td>
				  <td>
				  	<?php
					$dwt_ranges = array(
						0=>array(0=>'0|10', 1=>'(0-9,999) Mini - Bulker'), 
						1=>array(0=>'10|40', 1=>'(10,000-39,999) Handysize'), 
						2=>array(0=>'40|60', 1=>'(40,000-59,999) Handymax/Supramax'), 
						3=>array(0=>'60|100', 1=>'(60,000-99,999) Panamax'), 
						4=>array(0=>'100|220', 1=>'(100,000-219,999) Capesize'), 
						5=>array(0=>'220|550', 1=>'(220,000+) Very Large Ore Carrier - "VLOC"')
					);
					
					$t_d = count($dwt_ranges);
					?>
				  
					<select class="input_1" name="dwt_range" id='dwt_range_id' size="6">
						<?php
						for($i_d=0; $i_d<$t_d; $i_d++){
							if($dwt_ranges[$i_d][0]==$dwt_range){
								echo '<option value="'.$dwt_ranges[$i_d][0].'" selected="selected">'.$dwt_ranges[$i_d][1].'</option>';
							}else{
								echo '<option value="'.$dwt_ranges[$i_d][0].'">'.$dwt_ranges[$i_d][1].'</option>';
							}
						}
						?>
					</select>
				  </td>
				</tr>
			  </table>
			</td>
			<td width="550" valign="top">
			  <table width="550" border="0" cellpadding="0" cellspacing="0">
				<tr>
				  <td valign="top" width="80">DRY VESSELS</td>
				  <td width="10">&nbsp;</td>
				  <td>
				  	<?php
					$bulk_carrier = array(
						0=>'BULK CARRIER', 
						1=>'ORE CARRIER', 
						2=>'WOOD CHIPS CARRIER'
					);
					$t1 = count($bulk_carrier);
					
					$cargo = array(
						0=>'BARGE CARRIER', 
						1=>'CARGO', 
						2=>'CARGO/PASSENGER SHIP', 
						3=>'HEAVY LOAD CARRIER', 
						4=>'LIVESTOCK CARRIER', 
						5=>'MOTOR HOPPER', 
						6=>'NUCLEAR FUEL CARRIER', 
						7=>'SLUDGE CARRIER'
					);
					$t2 = count($cargo);
					
					$cement_carrier = array(
						0=>'CEMENT CARRIER'
					);
					$t3 = count($cement_carrier);
					
					$obo_carrier = array(
						0=>'OBO CARRIER'
					);
					$t4 = count($obo_carrier);
					
					$ro_ro_cargo = array(
						0=>'RO-RO CARGO', 
						1=>'RO-RO/CONTAINER CARRIER', 
						2=>'RO-RO/PASSENGER SHIP'
					);
					$t5 = count($ro_ro_cargo);
					?>
				  
					<select name="vessel_type[]" multiple="multiple" size="21" id='vessel_type_id' class="input_1" style="width:220px;">
						<optgroup label="BULK CARRIER">
							<?php
							for($i1=0; $i1<$t1; $i1++){
								if(in_array($bulk_carrier[$i1], $vessel_type)){
									echo '<option value="'.$bulk_carrier[$i1].'" selected="selected">'.$bulk_carrier[$i1].'</option>';
								}else{
									echo '<option value="'.$bulk_carrier[$i1].'">'.$bulk_carrier[$i1].'</option>';
								}
							}
							?>
						</optgroup>
						<optgroup label="CARGO">
							<?php
							for($i2=0; $i2<$t2; $i2++){
								if(in_array($cargo[$i2], $vessel_type)){
									echo '<option value="'.$cargo[$i2].'" selected="selected">'.$cargo[$i2].'</option>';
								}else{
									echo '<option value="'.$cargo[$i2].'">'.$cargo[$i2].'</option>';
								}
							}
							?>
						</optgroup>
						<optgroup label="CEMENT CARRIER">
							<?php
							for($i3=0; $i3<$t3; $i3++){
								if(in_array($cement_carrier[$i3], $vessel_type)){
									echo '<option value="'.$cement_carrier[$i3].'" selected="selected">'.$cement_carrier[$i3].'</option>';
								}else{
									echo '<option value="'.$cement_carrier[$i3].'">'.$cement_carrier[$i3].'</option>';
								}
							}
							?>
						</optgroup>
						<optgroup label="OBO CARRIER">
							<?php
							for($i4=0; $i4<$t4; $i4++){
								if(in_array($obo_carrier[$i4], $vessel_type)){
									echo '<option value="'.$obo_carrier[$i4].'" selected="selected">'.$obo_carrier[$i4].'</option>';
								}else{
									echo '<option value="'.$obo_carrier[$i4].'">'.$obo_carrier[$i4].'</option>';
								}
							}
							?>
						</optgroup>
						<optgroup label="RO-RO CARGO">
							<?php
							for($i5=0; $i5<$t5; $i5++){
								if(in_array($ro_ro_cargo[$i5], $vessel_type)){
									echo '<option value="'.$ro_ro_cargo[$i5].'" selected="selected">'.$ro_ro_cargo[$i5].'</option>';
								}else{
									echo '<option value="'.$ro_ro_cargo[$i5].'">'.$ro_ro_cargo[$i5].'</option>';
								}
							}
							?>
						</optgroup>
					</select>
					<br />
					To add more than one type<br />use the 'Ctrl' key and select
				  </td>
				  <td valign="top">
				  	<input class='searchbutton' type="button" name="search" value="SEARCH"  style='cursor:pointer' id='sbutton'  />

					<script>
					jQuery("#sbutton").click(
						function(){
							shipSearchx(1);
						}
					)
					</script>
				  </td>
				</tr>
			  </table>
			</td>
		  </tr>
		  <tr>
			<td height="10" colspan="2"></td>
		  </tr>
		</table>
		
		<table width="1000" border="0" cellpadding="0" cellspacing="0" id="table_2" style="display:none;">
		
			<?php
			$sql = "SELECT * FROM `_user_tabs` WHERE `uid`='".$user['uid']."' AND `page`='aisbroker' AND `option`='2' ORDER BY `dateadded` DESC";
			$r = dbQuery($sql, $link);
			
			$t = count($r);
			
			if(trim($t)){
				echo '<tr>';
				echo '<td colspan="2" style="border-bottom:none; padding-top:10px;">';
				echo '<div style="float:left; width:auto; height:auto; padding-right:30px;"><input type="button" value="+ New Scenario" onclick="newScenario();" style="border:1px solid #666666; background-color:#333333; color:#FFFFFF; cursor:pointer; padding:5px 10px;" /></div>';
				
				for($i=0; $i<$t; $i++){
					$tabdata = unserialize($r[$i]['tabdata']);
					
					if($r[$i]['tabname']){
						if(isset($_GET['tabid'])){
							if($_GET['tabid']==$r[$i]['id']){
								echo '<div style="float:left; width:auto; height:auto; background-color:#CCC; color:#666; padding:5px 10px; border:1px solid #FFF;">';
								echo '<div style="float:left; width:15px; height:auto;"><img src="images/close.png" width="14" height="14" border="0" alt="Delete this scenario" title="Delete this scenario" style="cursor:pointer;" onclick="deleteScenario(\''.$r[$i]['id'].'\');" /></div>';
								echo '<div style="float:left; width:auto; height:auto;">'.$r[$i]['tabname'].'</div>';
								echo '</div>';
							}else{
								echo '<div style="float:left; width:auto; height:auto; background-color:#666; color:#FFF; padding:5px 10px; border:1px solid #000;">';
								echo '<div style="float:left; width:15px; height:auto;"><img src="images/close.png" width="14" height="14" border="0" alt="Delete this scenario" title="Delete this scenario" style="cursor:pointer;" onclick="deleteScenario(\''.$r[$i]['id'].'\');" /></div>';
								echo '<div onclick="location.href=\'s-bis.php?new_search=1&action=2&tabid='.$r[$i]['id'].'\'" class="clickable" style="float:left; width:auto; height:auto; color:#FFF;">'.$r[$i]['tabname'].'</div>';
								echo '</div>';
							}
						}else{
							if($i==0){
								if(isset($_GET['new_search'])){
									if($_GET['new_search']==0){
										echo '<div style="float:left; width:auto; height:auto; background-color:#666; color:#FFF; padding:5px 10px; border:1px solid #000;">';
										echo '<div style="float:left; width:15px; height:auto;"><img src="images/close.png" width="14" height="14" border="0" alt="Delete this scenario" title="Delete this scenario" style="cursor:pointer;" onclick="deleteScenario(\''.$r[$i]['id'].'\');" /></div>';
										echo '<div onclick="location.href=\'s-bis.php?new_search=1&action=2&tabid='.$r[$i]['id'].'\'" class="clickable" style="float:left; width:auto; height:auto; color:#FFF;">'.$r[$i]['tabname'].'</div>';
										echo '</div>';
									}
								}else{
									echo '<div style="float:left; width:auto; height:auto; background-color:#CCC; color:#666; padding:5px 10px; border:1px solid #FFF;">';
									echo '<div style="float:left; width:15px; height:auto;"><img src="images/close.png" width="14" height="14" border="0" alt="Delete this scenario" title="Delete this scenario" style="cursor:pointer;" onclick="deleteScenario(\''.$r[$i]['id'].'\');" /></div>';
									echo '<div style="float:left; width:auto; height:auto;">'.$r[$i]['tabname'].'</div>';
									echo '</div>';
								}
							}else{
								echo '<div style="float:left; width:auto; height:auto; background-color:#666; padding:5px 10px; border:1px solid #000;">';
								echo '<div style="float:left; width:15px; height:auto;"><img src="images/close.png" width="14" height="14" border="0" alt="Delete this scenario" title="Delete this scenario" style="cursor:pointer;" onclick="deleteScenario(\''.$r[$i]['id'].'\');" /></div>';
								echo '<div onclick="location.href=\'s-bis.php?new_search=1&action=2&tabid='.$r[$i]['id'].'\'" class="clickable" style="float:left; width:auto; height:auto; color:#FFF;">'.$r[$i]['tabname'].'</div>';
								echo '</div>';
							}
						}
					}
					
					echo '<div style="float:left; width:auto; height:auto;">&nbsp;&nbsp;</div>';
				}
				
				echo '</td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td colspan="2">
					<div style="border-bottom:1px dotted #FFF;">&nbsp;</div>
					<div>&nbsp;</div>
				</td>';
				echo '</tr>';
			}
			?>
		
		  <tr>
			<td width="350" valign="top">
			  <table width="350" border="0" cellpadding="0" cellspacing="0">
				<tr>
				  <td>LAYCAN</td>
				  <td width="10">&nbsp;</td>
				  <td>
				  	  <input type="hidden" id="tabid2" name="tabid2" value="<?php echo $tabid2; ?>" />
					  <?php
					  if($destination_port_from2){
						?>
						<input type="text" name="destination_port_from2" value="<?php echo $destination_port_from2; ?>" readonly="readonly" onclick="showCalendar('',this,null,'','',0,5,1)" class="input_1" style="width:90px;" />
						<?php
					  }else{
						?>
						<input type="text" name="destination_port_from2" value="<?php echo date("M d, Y", time()); ?>" readonly="readonly" onclick="showCalendar('',this,null,'','',0,5,1)" class="input_1" style="width:90px;" />
						<?php
					  }
					  ?>
			
						to 
						
					  <?php
					  if($destination_port_to2){
						?>
						<input type="text" name="destination_port_to2" value="<?php echo $destination_port_to2; ?>" readonly="readonly" onclick="showCalendar('',this,null,'','',0,5,1)" class="input_1" style="width:90px;" />
						<?php
					  }else{
						?>
						<input type="text" name="destination_port_to2" value="<?php echo date("M d, Y", time()+(7*24*60*60)); ?>" readonly="readonly" onclick="showCalendar('',this,null,'','',0,5,1)" class="input_1" style="width:90px;" />
						<?php
					  }
					  ?>
				  </td>
				</tr>
				<tr>
				  <td height="5" colspan="3"></td>
				</tr>
				<tr>
				  <td>DWT RANGE</td>
				  <td>&nbsp;</td>
				  <td>
				  	<?php
					$dwt_range2s = array(
						0=>array(0=>'0|10', 1=>'(0-9,999) Mini - Bulker'), 
						1=>array(0=>'10|40', 1=>'(10,000-39,999) Handysize'), 
						2=>array(0=>'40|60', 1=>'(40,000-59,999) Handymax/Supramax'), 
						3=>array(0=>'60|100', 1=>'(60,000-99,999) Panamax'), 
						4=>array(0=>'100|220', 1=>'(100,000-219,999) Capesize'), 
						5=>array(0=>'220|550', 1=>'(220,000+) Very Large Ore Carrier - "VLOC"')
					);
					
					$t_d = count($dwt_range2s);
					?>
				  
					<select class="input_1" name="dwt_range2" id='dwt_range2_id' size="6">
						<?php
						for($i_d=0; $i_d<$t_d; $i_d++){
							if($dwt_range2s[$i_d][0]==$dwt_range2){
								echo '<option value="'.$dwt_range2s[$i_d][0].'" selected="selected">'.$dwt_range2s[$i_d][1].'</option>';
							}else{
								echo '<option value="'.$dwt_range2s[$i_d][0].'">'.$dwt_range2s[$i_d][1].'</option>';
							}
						}
						?>
					</select>
				  </td>
				</tr>
				<tr>
				  <td height="5" colspan="3"></td>
				</tr>
				<tr>
				  <td valign="top">DRY VESSELS</td>
				  <td width="10">&nbsp;</td>
				  <td>
					<?php
					$bulk_carrier = array(
						0=>'BULK CARRIER', 
						1=>'ORE CARRIER', 
						2=>'WOOD CHIPS CARRIER'
					);
					$t1 = count($bulk_carrier);
					
					$cargo = array(
						0=>'BARGE CARRIER', 
						1=>'CARGO', 
						2=>'CARGO/PASSENGER SHIP', 
						3=>'HEAVY LOAD CARRIER', 
						4=>'LIVESTOCK CARRIER', 
						5=>'MOTOR HOPPER', 
						6=>'NUCLEAR FUEL CARRIER', 
						7=>'SLUDGE CARRIER'
					);
					$t2 = count($cargo);
					
					$cement_carrier = array(
						0=>'CEMENT CARRIER'
					);
					$t3 = count($cement_carrier);
					
					$obo_carrier = array(
						0=>'OBO CARRIER'
					);
					$t4 = count($obo_carrier);
					
					$ro_ro_cargo = array(
						0=>'RO-RO CARGO', 
						1=>'RO-RO/CONTAINER CARRIER', 
						2=>'RO-RO/PASSENGER SHIP'
					);
					$t5 = count($ro_ro_cargo);
					?>
				  
					<select name="vessel_type2[]" multiple="multiple" size="21" id='vessel_type2_id' class="input_1" style="width:220px;">
						<optgroup label="BULK CARRIER">
							<?php
							for($i1=0; $i1<$t1; $i1++){
								if(in_array($bulk_carrier[$i1], $vessel_type2)){
									echo '<option value="'.$bulk_carrier[$i1].'" selected="selected">'.$bulk_carrier[$i1].'</option>';
								}else{
									echo '<option value="'.$bulk_carrier[$i1].'">'.$bulk_carrier[$i1].'</option>';
								}
							}
							?>
						</optgroup>
						<optgroup label="CARGO">
							<?php
							for($i2=0; $i2<$t2; $i2++){
								if(in_array($cargo[$i2], $vessel_type2)){
									echo '<option value="'.$cargo[$i2].'" selected="selected">'.$cargo[$i2].'</option>';
								}else{
									echo '<option value="'.$cargo[$i2].'">'.$cargo[$i2].'</option>';
								}
							}
							?>
						</optgroup>
						<optgroup label="CEMENT CARRIER">
							<?php
							for($i3=0; $i3<$t3; $i3++){
								if(in_array($cement_carrier[$i3], $vessel_type2)){
									echo '<option value="'.$cement_carrier[$i3].'" selected="selected">'.$cement_carrier[$i3].'</option>';
								}else{
									echo '<option value="'.$cement_carrier[$i3].'">'.$cement_carrier[$i3].'</option>';
								}
							}
							?>
						</optgroup>
						<optgroup label="OBO CARRIER">
							<?php
							for($i4=0; $i4<$t4; $i4++){
								if(in_array($obo_carrier[$i4], $vessel_type2)){
									echo '<option value="'.$obo_carrier[$i4].'" selected="selected">'.$obo_carrier[$i4].'</option>';
								}else{
									echo '<option value="'.$obo_carrier[$i4].'">'.$obo_carrier[$i4].'</option>';
								}
							}
							?>
						</optgroup>
						<optgroup label="RO-RO CARGO">
							<?php
							for($i5=0; $i5<$t5; $i5++){
								if(in_array($ro_ro_cargo[$i5], $vessel_type2)){
									echo '<option value="'.$ro_ro_cargo[$i5].'" selected="selected">'.$ro_ro_cargo[$i5].'</option>';
								}else{
									echo '<option value="'.$ro_ro_cargo[$i5].'">'.$ro_ro_cargo[$i5].'</option>';
								}
							}
							?>
						</optgroup>
					</select>
					<br />
					To add more than one type<br />use the 'Ctrl' key and select
				  </td>
				</tr>
			  </table>
			</td>
			<td width="650" valign="top">
			  <table width="650" border="0" cellpadding="0" cellspacing="0">
				<tr>
				  <td width="40">ZONE</td>
				  <td width="10">&nbsp;</td>
				  <td>
				  	<?php
					$zones = array(
						0=>array(0=>'z1', 1=>'[z1] AUSTRALIA'), 
						1=>array(0=>'z2', 1=>'[z2] BALTIC SEA'), 
						2=>array(0=>'z3', 1=>'[z3] BLACK SEA'), 
						3=>array(0=>'z4', 1=>'[z4] CARIB'), 
						4=>array(0=>'z5', 1=>'[z5] EC CAN'), 
						5=>array(0=>'z6', 1=>'[z6] ECCA'), 
						6=>array(0=>'z7', 1=>'[z7] ECEC'), 
						7=>array(0=>'z8', 1=>'[z8] ECI'), 
						8=>array(0=>'z9', 1=>'[z9] ECSA'), 
						9=>array(0=>'z10', 1=>'[z10] FAR EAST'), 
						10=>array(0=>'z11', 1=>'[z11] FRENCH ATLANTIC'), 
						11=>array(0=>'z12', 1=>'[z12] MEDITERRANEAN'), 
						12=>array(0=>'z13', 1=>'[z13] MED-RS-PG-WCI'), 
						13=>array(0=>'z14', 1=>'[z14] N EUROPE'), 
						14=>array(0=>'z15', 1=>'[z15] NCSA'), 
						15=>array(0=>'z16', 1=>'[z16] NEW ZEALAND'), 
						16=>array(0=>'z17', 1=>'[z17] NOPAC'), 
						17=>array(0=>'z18', 1=>'[z18] NORTH SEA'), 
						18=>array(0=>'z19', 1=>'[z19] NORWEGIAN SEA'), 
						19=>array(0=>'z20', 1=>'[z20] PERSIAN GULF'), 
						20=>array(0=>'z21', 1=>'[z21] PG +WCI'), 
						21=>array(0=>'z22', 1=>'[z22] RED SEA'), 
						22=>array(0=>'z23', 1=>'[z23] SA'), 
						23=>array(0=>'z24', 1=>'[z24] SE AFRICA'), 
						24=>array(0=>'z25', 1=>'[z25] SE ASIA'), 
						25=>array(0=>'z26', 1=>'[z26] SPAIN ATLANTIC'), 
						26=>array(0=>'z27', 1=>'[z27] ST LAWRENCE'), 
						27=>array(0=>'z28', 1=>'[z28] SW AFRICA'), 
						28=>array(0=>'z29', 1=>'[z29] UK AND EIRE'), 
						29=>array(0=>'z30', 1=>'[z30] USG'), 
						30=>array(0=>'z31', 1=>'[z31] WCCA'), 
						31=>array(0=>'z32', 1=>'[z32] WCSA'), 
						32=>array(0=>'z33', 1=>'[z33] WEST COAST INDIA')
					);
					
					$t_z = count($zones);
					?>
				  
					<select name='zone' id='zones_id' onchange='showMinimap(this.value)' style="width:440px;" class="input_1">
						<?php
						for($i_z=0; $i_z<$t_z; $i_z++){
							if($zones[$i_z][0]==$zone){
								echo '<option value="'.$zones[$i_z][0].'" selected="selected">'.$zones[$i_z][1].'</option>';
							}else{
								echo '<option value="'.$zones[$i_z][0].'">'.$zones[$i_z][1].'</option>';
							}
						}
						?>
					</select>
					
					<script>
					$(document).ready(function() {
						showMinimap('z1');
					});
					
					function showMinimap(zone){
						if(!zone){
							zone = jQuery(".blackzone")[0].value;
							jQuery(".blackzone")[0].selected = true;
						}
		
						jQuery('#zonedescs div').hide();
						jQuery("#minimap").show();
						jQuery("#minimap")[0].src='map/minimaps/'+zone+".jpg";
						jQuery("#minimap")[0].alt = zone;
		
						if(zone)
							jQuery('.click').show();
		
						jQuery('#zonedescs').show();
						jQuery('#zonedescs #zd'+zone).show();
					}
					
					function openZoneMap(zone){
						jQuery("#zonemapiframe")[0].src='map/zone2.php?zone='+zone+"&t="+(new Date()).getTime();
						jQuery( "#zonemapdialog" ).dialog( { autoOpen: false, width: '90%', height: jQuery(window).height()*0.9 });
						jQuery("#zonemapdialog").dialog("open");
					}
					</script>
				  </td>
				  <td rowspan="3" valign="top" align="right">
				  	<input class='searchbutton' type="button" name="search" value="SEARCH"  style='cursor:pointer' id='sbutton2'  />

					<script>
					jQuery("#sbutton2").click(
						function(){
							shipSearchx(2);
						}
					)
					</script>
				  </td>
				</tr>
				<tr>
				  <td height="3" colspan="3"></td>
				</tr>
				<tr>
				  <td>&nbsp;</td>
				  <td width="10">&nbsp;</td>
				  <td>
					<div id='minimaps'>
						<img id='minimap' style='cursor:pointer; display:none' onclick="openZoneMap(this.alt)" width="440" />
						<div style='text-align:center; display:none; margin-bottom:0px' class='click'>Click on the Map to Enlarge</div>
					</div>
				  </td>
				</tr>
			  </table>
			</td>
		  </tr>
		  <tr>
			<td height="10" colspan="2"></td>
		  </tr>
		</table>
	</form>
	
	<div id='sresults' style='display:none;'>
		<div id="records_tab_wrapper">
			<div id="container-1"></div>
		</div>
	</div>
</div>
<!--END OF AIS BROKER SEARCH-->