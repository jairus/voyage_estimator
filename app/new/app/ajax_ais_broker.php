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
	if(jQuery('#suggest1').val()){
		jQuery('#pleasewait').show();
	
		jQuery.ajax({
			type: "POST",
			url: "ajax.php?new_search=1",
			data: jQuery("#searchform").serialize(),
	
			success: function(data) {
				alert("Scenario Saved!");
			
				self.location = "cargospotter.php?new_search=1";
			}
		});
	}else{
		alert("Please select a ship.");
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
			
				self.location = "cargospotter.php?new_search=1";
			}
		});
	}
}

function newScenario(){
	jQuery('#pleasewait').show();
	
	self.location = "cargospotter.php?new_search=1";
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
if(!isset($_GET['new_search']) || isset($_GET['tabid'])){
	if(isset($_GET['tabid'])){
		$sql = "SELECT * FROM `_user_tabs` WHERE `id`='".$_GET['tabid']."'";
		$r = dbQuery($sql, $link);
	}else{
		$sql = "SELECT * FROM `_user_tabs` WHERE `uid`='".$user['uid']."' AND `page`='aisbroker' ORDER BY `dateadded` DESC LIMIT 0,1";
		$r = dbQuery($sql, $link);
	}
	
	if(trim($r)){
		$tabid = $r[0]['id'];
		$tabname = $r[0]['tabname'];
		$tabdata = unserialize($r[0]['tabdata']);
		
		$option_num = $tabdata['option_num'];
		
		if($option_num==1){
			?><script>showTable(1, 2);</script><?php
			$destination_port = $tabdata['destination_port'];
			$destination_port_from = $tabdata['destination_port_from'];
			$destination_port_to = $tabdata['destination_port_to'];
			$dwt_range = $tabdata['dwt_range'];
		}else if($option_num==2){
			?><script>showTable(2, 1);</script><?php
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
		<div>
		&nbsp;
		<input id='option_num_id' name="option_num" type="hidden" value="1" />
		<input type="hidden" id="tabid" name="tabid" value="<?php echo $tabid; ?>" />
		&nbsp;
		</div>
		<div><input type="button" value="Save Scenario" onclick="saveScenario();" style="border:1px solid #666666; background-color:#333333; color:#FFFFFF; cursor:pointer; padding:5px 10px;" /></div>
		<div style="border-bottom:1px dotted #FFF;">&nbsp;</div>
		<div>&nbsp;</div>
		<table width="1000" border="0" cellpadding="0" cellspacing="0" id="table_1">
		  
			<?php
			$sql = "SELECT * FROM `_user_tabs` WHERE `uid`='".$user['uid']."' AND `page`='aisbroker' ORDER BY `dateadded` DESC";
			$r = dbQuery($sql, $link);
			
			$t = count($r);
			
			if(trim($t)){
				echo '<tr>';
				echo '<td colspan="2" style="border-bottom:none; padding-top:10px;">';
				echo '<div style="float:left; width:auto; height:auto; padding-right:30px;"><input type="button" value="+ New Scenario" onclick="newScenario();" style="border:1px solid #666666; background-color:#333333; color:#FFFFFF; cursor:pointer; padding:5px 10px;" /></div>';
				
				for($i=0; $i<$t; $i++){
					$tabdata = unserialize($r[$i]['tabdata']);
					
					if($tabdata['option_num']==1){
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
									echo '<div onclick="location.href=\'cargospotter.php?new_search=3&tabid='.$r[$i]['id'].'\'" class="clickable" style="float:left; width:auto; height:auto; color:#FFF;">'.$r[$i]['tabname'].'</div>';
									echo '</div>';
								}
							}else{
								if($i==0){
									if(isset($_GET['new_search'])){
										if($_GET['new_search']==3){
											echo '<div style="float:left; width:auto; height:auto; background-color:#666; color:#FFF; padding:5px 10px; border:1px solid #000;">';
											echo '<div style="float:left; width:15px; height:auto;"><img src="images/close.png" width="14" height="14" border="0" alt="Delete this scenario" title="Delete this scenario" style="cursor:pointer;" onclick="deleteScenario(\''.$r[$i]['id'].'\');" /></div>';
											echo '<div onclick="location.href=\'cargospotter.php?new_search=3&tabid='.$r[$i]['id'].'\'" class="clickable" style="float:left; width:auto; height:auto; color:#FFF;">'.$r[$i]['tabname'].'</div>';
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
									echo '<div onclick="location.href=\'cargospotter.php?new_search=3&tabid='.$r[$i]['id'].'\'" class="clickable" style="float:left; width:auto; height:auto; color:#FFF;">'.$r[$i]['tabname'].'</div>';
									echo '</div>';
								}
							}
						}
					}
					
					echo '<div style="float:left; width:auto; height:auto;">&nbsp;&nbsp;</div>';
				}
				
				echo '</td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td colspan="2">&nbsp;</td>';
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
					<select class="input_1" name="dwt_range" id='dwt_range_id' size="7">
						<option value="0|10" selected="selected">(0-10,000) Minibulk</option>
						<option value="10|35">(10,000-35,000) Handy</option>
						<option value="35|60">(35,000-60,000) Handymax</option>
						<option value="60|75">(60,000-75,000) Handysize</option>
						<option value="75|110">(75,000-110,000) Over Panamax</option>
						<option value="110|150">(110,000-150,000) Small Capesize</option>
						<option value="150|550">(150,000+) Large Capesize</option>
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
					<select name="vessel_type[]" multiple="multiple" size="21" id='vessel_type_id' class="input_1" style="width:220px;">
						<optgroup label="BULK CARRIER">
							<option value="BULK CARRIER">BULK CARRIER</option>
							<option value="ORE CARRIER">ORE CARRIER</option>
							<option value="WOOD CHIPS CARRIER">WOOD CHIPS CARRIER</option>
						</optgroup>
						<optgroup label="CARGO">
							<option value="BARGE CARRIER">BARGE CARRIER</option>
							<option value="CARGO">CARGO</option>
							<option value="CARGO/PASSENGER SHIP">CARGO/PASSENGER SHIP</option>
							<option value="HEAVY LOAD CARRIER">HEAVY LOAD CARRIER</option>
							<option value="LIVESTOCK CARRIER">LIVESTOCK CARRIER</option>
							<option value="MOTOR HOPPER">MOTOR HOPPER</option>
							<option value="NUCLEAR FUEL CARRIER">NUCLEAR FUEL CARRIER</option>
							<option value="SLUDGE CARRIER">SLUDGE CARRIER</option>
						</optgroup>
						<optgroup label="CEMENT CARRIER">
							<option value="CEMENT CARRIER">CEMENT CARRIER</option>
						</optgroup>
						<optgroup label="OBO CARRIER">
							<option value="OBO CARRIER">OBO CARRIER</option>
						</optgroup>
						<optgroup label="RO-RO CARGO">
							<option value="RO-RO CARGO">RO-RO CARGO</option>
							<option value="RO-RO/CONTAINER CARRIER">RO-RO/CONTAINER CARRIER</option>
							<option value="RO-RO/PASSENGER SHIP">RO-RO/PASSENGER SHIP</option>
						</optgroup>
					</select>
					<br />
					To add more than one type<br />use the 'Ctrl' key and select
				  </td>
				</tr>
			  </table>
			</td>
		  </tr>
		  <tr>
			<td height="10" colspan="2"></td>
		  </tr>
		  <tr>
			<td colspan="2" align="center">
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
		  <tr>
			<td height="10" colspan="2"></td>
		  </tr>
		</table>
		
		<table width="1000" border="0" cellpadding="0" cellspacing="0" id="table_2" style="display:none;">
		
			<?php
			$sql = "SELECT * FROM `_user_tabs` WHERE `uid`='".$user['uid']."' AND `page`='aisbroker' ORDER BY `dateadded` DESC";
			$r = dbQuery($sql, $link);
			
			$t = count($r);
			
			if(trim($t)){
				echo '<tr>';
				echo '<td colspan="2" style="border-bottom:none; padding-top:10px;">';
				echo '<div style="float:left; width:auto; height:auto; padding-right:30px;"><input type="button" value="+ New Scenario" onclick="newScenario();" style="border:1px solid #666666; background-color:#333333; color:#FFFFFF; cursor:pointer; padding:5px 10px;" /></div>';
				
				for($i=0; $i<$t; $i++){
					$tabdata = unserialize($r[$i]['tabdata']);
					
					if($tabdata['option_num']==2){
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
									echo '<div onclick="location.href=\'cargospotter.php?new_search=3&tabid='.$r[$i]['id'].'\'" class="clickable" style="float:left; width:auto; height:auto; color:#FFF;">'.$r[$i]['tabname'].'</div>';
									echo '</div>';
								}
							}else{
								if($i==0){
									if(isset($_GET['new_search'])){
										if($_GET['new_search']==3){
											echo '<div style="float:left; width:auto; height:auto; background-color:#666; color:#FFF; padding:5px 10px; border:1px solid #000;">';
											echo '<div style="float:left; width:15px; height:auto;"><img src="images/close.png" width="14" height="14" border="0" alt="Delete this scenario" title="Delete this scenario" style="cursor:pointer;" onclick="deleteScenario(\''.$r[$i]['id'].'\');" /></div>';
											echo '<div onclick="location.href=\'cargospotter.php?new_search=3&tabid='.$r[$i]['id'].'\'" class="clickable" style="float:left; width:auto; height:auto; color:#FFF;">'.$r[$i]['tabname'].'</div>';
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
									echo '<div onclick="location.href=\'cargospotter.php?new_search=3&tabid='.$r[$i]['id'].'\'" class="clickable" style="float:left; width:auto; height:auto; color:#FFF;">'.$r[$i]['tabname'].'</div>';
									echo '</div>';
								}
							}
						}
					}
					
					echo '<div style="float:left; width:auto; height:auto;">&nbsp;&nbsp;</div>';
				}
				
				echo '</td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td colspan="2">&nbsp;</td>';
				echo '</tr>';
			}
			?>
		
		  <tr>
			<td width="400" valign="top">
			  <table width="400" border="0" cellpadding="0" cellspacing="0">
				<tr>
				  <td>LAYCAN</td>
				  <td width="10">&nbsp;</td>
				  <td>
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
					<select class="input_1" name="dwt_range2" id='dwt_range_id2' size="7">
						<option value="0|10" selected="selected">(0-10,000) Minibulk</option>
						<option value="10|35">(10,000-35,000) Handy</option>
						<option value="35|60">(35,000-60,000) Handymax</option>
						<option value="60|75">(60,000-75,000) Handysize</option>
						<option value="75|110">(75,000-110,000) Over Panamax</option>
						<option value="110|150">(110,000-150,000) Small Capesize</option>
						<option value="150|550">(150,000+) Large Capesize</option>
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
					<select name="vessel_type2[]" multiple="multiple" size="21" id='vessel_type_id2' class="input_1" style="width:220px;">
						<optgroup label="BULK CARRIER">
							<option value="BULK CARRIER">BULK CARRIER</option>
							<option value="ORE CARRIER">ORE CARRIER</option>
							<option value="WOOD CHIPS CARRIER">WOOD CHIPS CARRIER</option>
						</optgroup>
						<optgroup label="CARGO">
							<option value="BARGE CARRIER">BARGE CARRIER</option>
							<option value="CARGO">CARGO</option>
							<option value="CARGO/PASSENGER SHIP">CARGO/PASSENGER SHIP</option>
							<option value="HEAVY LOAD CARRIER">HEAVY LOAD CARRIER</option>
							<option value="LIVESTOCK CARRIER">LIVESTOCK CARRIER</option>
							<option value="MOTOR HOPPER">MOTOR HOPPER</option>
							<option value="NUCLEAR FUEL CARRIER">NUCLEAR FUEL CARRIER</option>
							<option value="SLUDGE CARRIER">SLUDGE CARRIER</option>
						</optgroup>
						<optgroup label="CEMENT CARRIER">
							<option value="CEMENT CARRIER">CEMENT CARRIER</option>
						</optgroup>
						<optgroup label="OBO CARRIER">
							<option value="OBO CARRIER">OBO CARRIER</option>
						</optgroup>
						<optgroup label="RO-RO CARGO">
							<option value="RO-RO CARGO">RO-RO CARGO</option>
							<option value="RO-RO/CONTAINER CARRIER">RO-RO/CONTAINER CARRIER</option>
							<option value="RO-RO/PASSENGER SHIP">RO-RO/PASSENGER SHIP</option>
						</optgroup>
					</select>
					<br />
					To add more than one type<br />use the 'Ctrl' key and select
				  </td>
				</tr>
			  </table>
			</td>
			<td width="600" valign="top">
			  <table width="600" border="0" cellpadding="0" cellspacing="0">
				<tr>
				  <td width="50">ZONE</td>
				  <td width="10">&nbsp;</td>
				  <td>
				  	<select name='zone' id='zones_id' onchange='showMinimap(this.value)' style="width:440px;" class="input_1">
						<option value='z1'>[z1] AUSTRALIA</option>
						<option value='z2'>[z2] BALTIC SEA</option>
						<option value='z3'>[z3] BLACK SEA</option>
						<option value='z4'>[z4] CARIB</option>
						<option value='z5'>[z5] EC CAN</option>
						<option value='z6'>[z6] ECCA</option>
						<option value='z7'>[z7] ECEC</option>
						<option value='z8'>[z8] ECI</option>
						<option value='z9'>[z9] ECSA</option>
						<option value='z10'>[z10] FAR EAST</option>
						<option value='z11'>[z11] FRENCH ATLANTIC</option>
						<option value='z12'>[z12] MEDITERRANEAN</option>
						<option value='z13'>[z13] N EUROPE</option>
						<option value='z14'>[z14] NCSA</option>
						<option value='z15'>[z15] NEW ZEALAND</option>
						<option value='z16'>[z16] NOPAC</option>
						<option value='z17'>[z17] NORTH SEA</option>
						<option value='z18'>[z18] NORWEGIAN SEA</option>
						<option value='z19'>[z19] PERSIAN GULF</option>
						<option value='z20'>[z20] PG +WCI</option>
						<option value='z21'>[z21] RED SEA</option>
						<option value='z22'>[z22] SA</option>
						<option value='z23'>[z23] SE AFRICA</option>
						<option value='z24'>[z24] SE ASIA</option>
						<option value='z25'>[z25] SPAIN ATLANTIC</option>
						<option value='z26'>[z26] ST LAWRENCE</option>
						<option value='z27'>[z27] SW AFRICA</option>
						<option value='z28'>[z28] UK AND EIRE</option>
						<option value='z29'>[z29] USG</option>
						<option value='z30'>[z30] WCCA</option>
						<option value='z31'>[z31] WCSA</option>
						<option value='z32'>[z32] WEST COAST INDIA</option>
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
		  <tr>
			<td colspan="2" align="center">
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