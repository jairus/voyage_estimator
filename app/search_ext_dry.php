<?php
include_once(dirname(__FILE__)."/includes/bootstrap.php");

global $user;

$tab     = $tabsys->getTab("shipsearch", $_GET['tab']);
$tabid   = $tab['id'];
$tabdata = unserialize($tab['tabdata']);
?>

<style>
.message{
	width: 150px;
	cursor:pointer;
	background: #f0f0f0;
}

#sresults .xmessages *{
	font-size:10px;
	padding:0px;
}

#sresults .brokerentry *{
	font-size:10px;
	padding:0px;
}

#sresults td{
	padding:1px;
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

#searchform *{
	font-size:11px;
	font-family:Arial, Helvetica, sans-serif;
}

#shipsearchonly *{
	font-size:11px;
	font-family:Arial, Helvetica, sans-serif;
}

#fleetpositions *{
	font-size:11px;
	font-family:Arial, Helvetica, sans-serif;
}

#shipscomingintoports *{
	font-size:11px;
	font-family:Arial, Helvetica, sans-serif;
}

#live_ship_position *{
	font-size:11px;
	font-family:Arial, Helvetica, sans-serif;
}

#cargo_form *{
	font-size:11px;
	font-family:Arial, Helvetica, sans-serif;
}

#portintelligence_form *{
	font-size:11px;
	font-family:Arial, Helvetica, sans-serif;
}

#bunkerprice_form *{
	font-size:11px;
	font-family:Arial, Helvetica, sans-serif;
}

.style3 {font-size: 9px}
</style>

<script>
function ownerDetails(owner, owner_id){
	var iframe = $("#contactiframe");

	$(iframe).contents().find("body").html("");
	
	jQuery("#contactiframe")[0].src='search_ajax.php?dry=1&contact=1&owner='+owner+'&owner_id='+owner_id;
	jQuery("#contactdialog").dialog("open");
}
</script>

<center>
<div id="shipdetails" title="SHIP DETAILS" style='display:none; padding-bottom:10px'>
	<div id='shipdetails_in' ></div>
</div>

<div id="mapdialog" title="MAP - CLICK ON THE SHIP IMAGE BELOW TO SHOW DETAILS" style='display:none'>
	<iframe id='mapiframe' name='mapname' frameborder=0 height="100%" width="100%" style='border:0px; height:100%; width:100%'></iframe>
</div>

<div id="mapdialog_brokers" title="MAP - CLICK ON THE SHIP IMAGE BELOW TO SHOW DETAILS" style='display:none'>
	<iframe id='mapiframe_brokers' name='mapname_brokers' frameborder=0 height="100%" width="100%" style='border:0px; height:100%; width:100%'></iframe>
</div>

<div id="mapdialogpiracyalert" title="PIRACY ALERT" style='display:none'>
	<iframe id='mapiframepiracyalert' name='mapname' frameborder=0 height="100%" width="100%" style='border:0px; height:100%; width:100%'></iframe>
</div>

<div id="zonemapdialog" title="ZONE MAP" style='display:none'>
	<iframe id='zonemapiframe' frameborder=0 height="100%" width="100%" style='border:0px; height:100%; width:100%'></iframe>
</div>

<div id="contactdialog" title="CONTACT"  style='display:none'>
	<iframe id='contactiframe' frameborder=0 height="100%" width="100%" style='border:0px; height:100%; width:100%'></iframe>
</div>

<div id="miscdialog" title=""  style='display:none'>
	<iframe id='misciframe' frameborder='0' height="100%" width="1100px" style='border:0px; height:100%; width:1050px;'></iframe>
</div>

<div id="messagedialog" title="MESSAGES"  style='display:none'>
	<iframe id='messageiframe' frameborder=0 height="100%" width="100%" style='border:0px; height:100%; width:100%'></iframe>
</div>

<div id="didyouknowdialog" title=""  style='display:none'>
	<div id='didyouknowcontent'></div>
</div>

<div id="learndialog" title=""  style='display:none'>
	<div id='learncontent' style='font-size:11px;'></div>
</div>

<div id="lastporthistorydialog" title="LAST PORT HISTORY"  style='display:none'>
	<div id='lastporthistorycontent'></div>
</div>

<div id="bunkerpricedialog" title="BUNKER PRICE HISTORY"  style='display:none'>
	<div id='bunkerpricecontent'></div>
</div>

<div id="mapcargodialog" title="CARGO MAP" style='display:none'>
	<iframe id='mapcargoiframe' name='cargomapname' frameborder=0 height="100%" width="100%" style='border:0px; height:100%; width:100%'></iframe>
</div>

<script>
function disableAllSearch(){
	jQuery("#searchby1")[0].className = "form-button";
	jQuery("#searchby2")[0].className = "form-button";
	jQuery("#searchby3")[0].className = "form-button";
	jQuery("#searchby4")[0].className = "form-button";
	jQuery("#searchby5")[0].className = "form-button";
	jQuery("#searchby6")[0].className = "form-button";
	jQuery("#searchby7")[0].className = "form-button";
	jQuery("#searchby8")[0].className = "form-button";
	jQuery("#searchby9")[0].className = "form-button";

	jQuery("#parameters_table").hide();
	jQuery("#parameters_table2").hide();
	jQuery("#parameters_table3").hide();
	jQuery("#parameters_table4").hide();
	jQuery("#parameters_table5").hide();
	jQuery("#parameters_table6").hide();
	jQuery("#parameters_table7").hide();
	jQuery("#parameters_table8").hide();
	jQuery("#parameters_table9").hide();

	jQuery('#sresults').hide();
	jQuery('#shipsearchonlyresults').hide();
	jQuery('#fleetpositionsresults').hide();
	jQuery('#shipscomingintoportsresults').hide();
	jQuery('#liveshippositionresults').hide();
	jQuery('#cargoresults').hide();
	jQuery('#portintelligenceresults').hide();
	jQuery('#bunkerpriceresults').hide();
}

function searchToggle(n){
	if(n==1){
		disableAllSearch();
		
		jQuery("#searchby1")[0].className = "form-button-clicked";

		jQuery("#parameters_table").show();

		if(jQuery.trim(jQuery("#container-1").html())){
			jQuery('#sresults').show();
		}
	}else if(n==2){
		disableAllSearch();
		
		jQuery("#searchby2")[0].className = "form-button-clicked";
		
		jQuery("#parameters_table2").show();

		jQuery('#shipsearchonlyresults').show();
	}else if(n==3){
		disableAllSearch();
		
		jQuery("#searchby3")[0].className = "form-button-clicked";
		
		jQuery("#parameters_table3").show();

		jQuery('#fleetpositionsresults').show();
	}else if(n==4){
		disableAllSearch();
		
		jQuery("#searchby4")[0].className = "form-button-clicked";
		
		jQuery("#parameters_table4").show();
		
		iframe = document.getElementById('map_iframe');
  		iframe.src = 'map/index4.php';
	}else if(n==5){
		disableAllSearch();
		
		jQuery("#searchby5")[0].className = "form-button-clicked";
		
		jQuery("#parameters_table5").show();

		jQuery('#shipscomingintoportsresults').show();
	}else if(n==6){
		disableAllSearch();
		
		jQuery("#searchby6")[0].className = "form-button-clicked";
		
		jQuery("#parameters_table6").show();
		
		jQuery('#liveshippositionresults').show();
	}else if(n==7){
		disableAllSearch();
		
		jQuery("#searchby7")[0].className = "form-button-clicked";
		
		jQuery("#parameters_table7").show();
		
		jQuery('#cargoresults').show();
	}else if(n==8){
		disableAllSearch();
		
		jQuery("#searchby8")[0].className = "form-button-clicked";
		
		jQuery("#parameters_table8").show();
		
		jQuery('#portintelligenceresults').show();
	}else if(n==9){
		disableAllSearch();
		
		jQuery("#searchby9")[0].className = "form-button-clicked";
		
		jQuery("#parameters_table9").show();
		
		jQuery('#bunkerpriceresults').show();
	}
}
</script>

<script type="text/javascript">
jQuery( "#mapdialog" ).dialog( { width: '90%', height: jQuery(window).height()*0.9 });
jQuery("#mapdialog").dialog("close");

jQuery("#mapdialog_brokers" ).dialog( { width: '90%', height: jQuery(window).height()*0.9 });
jQuery("#mapdialog_brokers").dialog("close");

jQuery( "#mapdialogpiracyalert" ).dialog( { width: '90%', height: jQuery(window).height()*0.9 });
jQuery("#mapdialogpiracyalert").dialog("close");

jQuery("#mapcargodialog").dialog( { width: '95%', height: jQuery(window).height()*0.9 });
jQuery("#mapcargodialog").dialog("close");

jQuery( "#zonemapdialog" ).dialog( { width: '90%', height: jQuery(window).height()*0.9 });
jQuery("#zonemapdialog").dialog("close");

jQuery( "#contactdialog" ).dialog( { width: 900, height: 460 });
jQuery("#contactdialog").dialog("close");		

jQuery( "#miscdialog" ).dialog( { width: 1100, height: 500 });
jQuery( "#miscdialog" ).dialog("close");

jQuery( "#miscdialogpiracyalert" ).dialog( { width: 1100, height: 500 });
jQuery( "#miscdialogpiracyalert" ).dialog("close");

jQuery( "#didyouknowdialog" ).dialog( { width: 700, height: 600 });
jQuery( "#didyouknowdialog" ).dialog("close");

jQuery( "#lastporthistorydialog" ).dialog( { width: 700, height: 600 });
jQuery( "#lastporthistorydialog" ).dialog("close");

jQuery( "#bunkerpricedialog" ).dialog( { width: 700, height: 600 });
jQuery( "#bunkerpricedialog" ).dialog("close");

jQuery( "#learndialog" ).dialog( { width: 600, height: 360 });
jQuery( "#learndialog" ).dialog("close");	

jQuery( "#shipdetails" ).dialog( { width: '90%', height: jQuery(window).height()*0.9 });
jQuery( "#shipdetails" ).dialog("close");	

jQuery( "#messagedialog" ).dialog( { width: 920, height: 460,
	close: function (event, ui){
		fetchMessages();
	}
});
jQuery( "#messagedialog" ).dialog("close");	    

function openZoneMap(zone){
	jQuery("#zonemapiframe")[0].src='map/zone2.php?zone='+zone+"&t="+(new Date()).getTime();
	jQuery( "#zonemapdialog" ).dialog( { width: '90%', height: jQuery(window).height()*0.9 });
	jQuery("#zonemapdialog").dialog("open");
}

function openOptMap(opt){
	opt = opt.replace("_", ",")

	jQuery("#zonemapiframe")[0].src='map/zone2.php?zone='+opt+"&t="+(new Date()).getTime();
	jQuery( "#zonemapdialog" ).dialog( { width: '90%', height: jQuery(window).height()*0.9 });
	jQuery("#zonemapdialog").dialog("open");
}	

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
		url: "search_ajax.php?action=getmessages&task=fetchmessages",
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

function getHistory(imo, category, details, heading_title){
	jQuery('#pleasewait2').show();
	
	jQuery.ajax({
		type: 'GET',
		url: "lastporthistory.php?imo_num="+imo+"&category="+category+"&details="+details+"&heading_title="+heading_title,
		data:  "",

		success: function(data) {
			jQuery('#pleasewait2').hide();
			
			jQuery('#lastporthistorycontent').html(data);
			jQuery( "#lastporthistorydialog" ).dialog("open"); 
		}
	});
}

function getBunkerPriceHistory(port_code){
	jQuery('#pleasewait2').show();
	
	jQuery.ajax({
		type: 'GET',
		url: "bunkerpricehistory.php?port_code="+port_code,
		data:  "",

		success: function(data) {
			jQuery('#pleasewait2').hide();
			
			jQuery('#bunkerpricecontent').html(data);
			jQuery( "#bunkerpricedialog" ).dialog("open"); 
		}
	});
}

function shipSearchx(){
	jQuery.ajax({
		type: 'GET',
		url: "didyouknow.php?t="+(new Date()).getTime(),
		data:  "",

		success: function(data) {
			jQuery('#didyouknowcontent').html(data);
			jQuery( "#didyouknowdialog" ).dialog("open"); 
		}
	});

	jQuery("#container-1").html("");

	globalfetch = false;

	jQuery("#shipdetails").dialog("close");
	jQuery('#sresults').hide();
	jQuery('#pleasewait').show();

	jQuery("#sbutton").val("SEARCHING...");
	jQuery("#sbutton")[0].disabled = true;
	
	jQuery("#searchby1").attr("disabled", true);
	jQuery("#searchby2").attr("disabled", true);
	jQuery("#searchby3").attr("disabled", true);
	jQuery("#searchby4").attr("disabled", true);
	jQuery("#searchby5").attr("disabled", true);
	jQuery("#searchby6").attr("disabled", true);
	jQuery("#searchby7").attr("disabled", true);
	jQuery("#searchby8").attr("disabled", true);
	jQuery("#searchby9").attr("disabled", true);
	
	jQuery('#cancelsearch').show();

	jQuery.ajax({
		type: 'GET',
		url: "search_ajax.php",
		data: jQuery("#searchform").serialize(),

		success: function(data) {
			jQuery("#sbutton")[0].disabled = false;
			
			jQuery("#searchby1").attr("disabled", false);
			jQuery("#searchby2").attr("disabled", false);
			jQuery("#searchby3").attr("disabled", false);
			jQuery("#searchby4").attr("disabled", false);
			jQuery("#searchby5").attr("disabled", false);
			jQuery("#searchby6").attr("disabled", false);
			jQuery("#searchby7").attr("disabled", false);
			jQuery("#searchby8").attr("disabled", false);
			jQuery("#searchby9").attr("disabled", false);
			
			jQuery('#cancelsearch').hide();

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

				fetchMessagesCron();
				fetchMessages();
			}else{
				data = data.replace("<b>ERROR</b>:", "");

				alert(data);

				jQuery('#pleasewait').hide();
			}

			jQuery("#sbutton").val("SEARCH");
		}
	});
}

function showShipDetails(imo){
	jQuery("#shipdetails").dialog("close")
	jQuery('#pleasewait2').show();

	jQuery.ajax({
		type: 'POST',
		url: "search_ajax.php?imo="+imo,
		data:  '',
		
		success: function(data) {
			if(data.indexOf("<b>ERROR")!=0){
				jQuery("#shipdetails_in").html(data);
				jQuery("#shipdetails").dialog("open")
				jQuery('#pleasewait2').hide();
			}else{
				alert(data)
			}
		}
	});	
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
</script>

<script>
function csvIt(report){
	chk = jQuery("#positions input[type=checkbox]");
	g = "";

	for(i=0; i<chk.length; i++){
		if(chk[i].checked&&chk[i].value){
			g += "imo[]="+chk[i].value+"&";
		}
	}

	if(g!=""){
		self.location = "misc/csv.php?"+g+"report="+report;
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
		jQuery("#misciframe")[0].src="misc/email.php?"+g+"report="+report;
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
		jQuery("#misciframe")[0].src="misc/print.php?"+g+"report="+report;
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
		self.location = "misc/csv.php?"+g+"report="+report;
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
		jQuery("#misciframe")[0].src="misc/email1.php?"+g+"report="+report;
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
		jQuery("#misciframe")[0].src="misc/print1.php?"+g+"report="+report;
		jQuery("#miscdialog").dialog("open");
	}else{
		alert("You must select ships to print. Check checkboxes to select.")
	}
}

function checkAll(idx, obj){
	if(obj.checked){
		jQuery("#"+idx+" input[type=checkbox]").attr("checked", true)
	}else{
		jQuery("#"+idx+" input[type=checkbox]").attr("checked", false)
	}
}

function openMessageDialog(mid, imo, type){
	jQuery("#messageiframe")[0].src="search_ajax.php?action=getmessages&type="+type+"&mid="+mid+"&imo="+imo+"&t="+(new Date()).getTime();
	jQuery( "#messagedialog" ).dialog( { width: '920', height: jQuery(window).height()*0.9 });
	jQuery("#messagedialog").dialog("open");
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

function changeCssClass(objDivID){
	if(document.getElementById(objDivID).className=='divclass_active'){
		document.getElementById(objDivID).className = 'divclass';
	}else{
		document.getElementById(objDivID).className = 'divclass_active';
	}
}
</script>

<form id='mapformid' target='mapname' method="post">
	<input type='hidden' name='details' id='detailsid' />
</form>

<div style="width:1000px; padding:0px 10px;">
<table width="1000" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td>
			<div id="content_wrapper" style='margin-bottom:50px'>
				<div id="content_main">
					<h1 class="title" style='margin-bottom:0px; cursor:pointer;' onclick="jQuery('#searchform').slideToggle('slow', function(){ toggleParams(); })"><a name='params'>PARAMETERS</a> <img src='images/up.png' id='paramicon'></h1>
                    
                    <table width="1000" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td style='text-align:left; padding:10px 0px;'>
                                <input type='button' onclick="searchToggle(1);" id='searchby1' value='fast search' class='form-button-clicked' style='font-size:11px; font-weight:bold;'>
                                <input type='button' onclick="searchToggle(2);" id='searchby2' value='ship search / register' class='form-button' style='font-size:11px; font-weight:bold;'>
                                <input type='button' onclick="searchToggle(3);" id='searchby3' value='fleet positions' class='form-button' style='font-size:11px; font-weight:bold;'>
                                <input type='button' onclick="searchToggle(4);" id='searchby4' value='piracy alerts' class='form-button' style='font-size:11px; font-weight:bold;'>
                                <input type='button' onclick="searchToggle(5);" id='searchby5' value='ships coming into ports' class='form-button' style='font-size:11px; font-weight:bold;'>
                                <input type='button' onclick="searchToggle(6);" id='searchby6' value='live ship position' class='form-button' style='font-size:11px; font-weight:bold;'>
                                <input type='button' onclick="searchToggle(7);" id='searchby7' value='cargo' class='form-button' style='font-size:11px; font-weight:bold;'>
                                <input type='button' onclick="searchToggle(8);" id='searchby8' value='port intelligence' class='form-button' style='font-size:11px; font-weight:bold;'>
                                <input type='button' onclick="searchToggle(9);" id='searchby9' value='bunker price' class='form-button' style='font-size:11px; font-weight:bold;'>
                            </td>
                        </tr>
                    </table>
                    
                    <!--FAST SEARCH-->
                    <form id='searchform' style='margin:0px;'>
                    <input type='hidden' name='dry' value='1' >
					<input type='hidden' name='tabid' value='<?php echo $tabid; ?>' >
                    <table width="1000" border="0" cellpadding="0" cellspacing="0" id="parameters_table">
                        <tr>
                            <td valign="top" width="400">
                                <table width="400" border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td valign="top" class="title">LOAD PORT</td>
                                        <td valign="top">
                                            <input id='suggest1' type="text" name="load_port" value='<?php echo $tabdata['load_port']; ?>' class="text" style='width:200px;' />
                                            <input type='hidden' name='zone2' value='<?php echo $tabdata['zone']; ?>' >
                                            
                                            <script type="text/javascript">
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
                                
                                            function showZones(lp, dwt){
                                                jQuery("#minimap").hide();
                                
                                                if(!dwt){
                                                    dwt = jQuery('#dwt_range_id').val();
                                                }
                                
                                                jQuery("#zones").html('loading zones...');
                                
                                                jQuery.ajax({
                                                    type: 'POST',
                                                    url: "search_ajax.php?dry=1&load_port="+lp+"&action=getzones&dwt_range="+dwt,
                                                    data:  '',
                                                    
                                                    success: function(data) {
                                                        if(data!=""&&data.indexOf("<b>ERROR")!=0){
                                                            jQuery("#zones").html(data);
                                                        }else{
                                                            jQuery("#zones").html(data);
                                                        }
                                
                                                        jQuery("#zones_id").val('<?php echo $tabdata['zone']; ?>');
                                
                                                        showMinimap('<?php echo $tabdata['zone']; ?>');
                                                    }
                                                });
                                            }
                                
                                            jQuery("#suggest1").focus().autocomplete(ports);
                                            jQuery("#suggest1").setOptions({
                                                scrollHeight: 180
                                            });
                                
                                            jQuery("#suggest1").result(
                                                function(){
                                                    showZones(jQuery(this).val())
                                                }
                                            );	
                                
                                            jQuery("#suggest2").focus().autocomplete(ports);
                                            jQuery("#suggest2").setOptions({
                                                scrollHeight: 180
                                            });
                                            </script>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="top" class="title">LOAD PORT DATE RANGE</td>
                                        <td valign="top">
                                            <input type="text" name="load_port_from" value="<?php
                                            if(!trim($tabdata['load_port_from'])){
                                                echo date("M d, Y", time());
                                            }else{
                                                echo $tabdata['load_port_from'];
                                            }
                                            ?>" readonly="readonly" onclick="showCalendar('',this,null,'','',0,5,1)" class="text" style="width:90px;" />
                                
                                            to 
                                
                                            <input type="text" name="load_port_to" value="<?php
                                            if(!trim($tabdata['load_port_from'])){
                                                echo date("M d, Y", time()+(7*24*60*60));
                                            }else{ 
                                                echo $tabdata['load_port_to'];
                                            }
                                            ?>" readonly="readonly" onclick="showCalendar('',this,null,'','',0,5,1)" class="text" style="width:90px;" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="top" class="title">HULL TYPE</td>
                                        <td valign="top">
                                            <select name="hull_type" class="selection" id='hull_type_id' style="width:200px;">
                                                <option selected="selected">SINGLE HULL</option>
                                                <option>DOUBLE HULL</option>
                                            </select>
                                            
                                            <?php if($tabdata['hull_type']!=""){ ?>
                                                <script>jQuery("#hull_type_id").val('<?php echo $tabdata['hull_type']; ?>');</script>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="top" class="title">CATEGORY <strong>DRY</strong></td>
                                        <td valign="top" id='foovt'>
                                            <select name="vessel_type[]" multiple="multiple" size="16" id='vessel_type_id' style="width:200px;">
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
                    
                                            <script>
                                            function resetVT(){
                                                arr = jQuery("#vessel_type_id option");
                        
                                                for(i=0; i<arr.length; i++){
                                                    arr[i].selected = false;
                                                }
                                            }
                        
                                            function setSelectVT(val){
                                                arr = jQuery("#vessel_type_id option");
                        
                                                for(i=0; i<arr.length; i++){
                                                    if(arr[i].innerHTML==val){
                                                        arr[i].selected = true;
                                                    }
                                                }
                                            }
                        
                                            <?php 
                                                $vts = $tabdata['vessel_type']; 
                        
                                                if($vts[0]){
                                                    ?>resetVT();<?php
                                                }
                        
                                                if(is_array($vts)){
                                                    foreach($vts as $value){
                                                        ?>setSelectVT("<?php echo $value; ?>");<?php
                                                    }
                                                }
                                            ?>
                                            </script>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td valign="top" width="600">
                                <table width="600" border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td valign="top" class="title">DWT RANGE</td>
                                        <td valign="top">
                                            <select class="valid" name="dwt_range" id='dwt_range_id' onchange='showZones(jQuery("#suggest1").val(), this.value)'>
                                                <option value="5|35">(5,000-35,000) Handysize</option>
                                                <option value="40|50" selected="selected">(40,000-50,000) Handymax</option>
                                                <option value="50|60">(50,000-60,000) Supramax</option>
                                                <option value="60|90">(60,000-90,000) Panamax</option>
                                                <option value="90|120">(90,000-120,000) Post Panamax</option>
                                                <option value="120|350">(120,000-350,000) Capesize</option>
                                            </select>
                    
                                            <?php if($tabdata['dwt_range']!=""){ ?>
                                                <script>jQuery("#dwt_range_id").val('<?php echo $tabdata['dwt_range']; ?>');</script>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="top" class="title">ZONE</td>
                                        <td valign="top">
                                            <div id='zones'></div>
                                            <div id='minimaps' style='padding-top:5px;'>
                                            <table width='400px'>
                                                <tr>
                                                    <td>
                                                        <img id='minimap' style='cursor:pointer; display:none' onclick="openZoneMap(this.alt)" alt='<?php echo $tabdata['zone']; ?>'  src='map/minimaps/<?php echo $tabdata['zone']; ?>.jpg' width="420">
                                                        <div style='text-align:center; display:none; margin-bottom:0px' class='click'>Click on the Map to Enlarge</div>
                                                    </td>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style='text-align:center; font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#333;'>
                            	<b>CHECK BOXES TO ADD SEARCH OPTIONS TO YOUR SEARCH</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>CHOOSE THE NUMBER OF SHIPS YOU WANT TO SEARCH FOR</b>
                                <script>
								function notAllowed(id_val){
									if(id_val!=5){
										alert("As you are using a \"Trial Account\" you are only allowed to view 5 ships. A Subscription account allows unlimited access and facilities to search.");
										
										$('#id_slimit').val('5');
									}
								}
								</script>
                                <?php
								if($user['purchase']=="Trial Account (7 Days Trial Account)"){
								?>
                                <select id="id_slimit" name="slimit" style='height:20px; width:70px; font-size:12px;' onchange='notAllowed(this.value);'>
                                    <option value="">ALL</option>
                                    <option value="5" selected="selected">5</option>
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="500">500</option>
                                </select>
                                <?php }else{ ?>
                                <select name="slimit" style='height:20px; width:70px; font-size:12px;'>
                                    <option value="">ALL</option>
                                    <option value="5" selected="selected">5</option>
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="500">500</option>
                                </select>
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                        	<td colspan="2">
                            	<center>
                            	<table border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td>
                                            <div style="padding-bottom:20px; width:170px;">
												<?php if($tabdata['sshore']){ ?>
                                                    <div style="float:left;"><input type='checkbox' id='sshore1check' name='sshore' value='1' checked="checked" style='height:30px; width:30px' onclick="changeCssClass('sshore1div')"></div>
                                                    <div id='sshore1div' class="divclass_active" style='font-family:Arial, Helvetica, sans-serif; font-size:11px; font-weight:bold;'>AIS SHOREsearch</div>
                                                <?php }else if(trim($tabdata['load_port'])){ ?>
                                                    <div style="float:left;"><input type='checkbox' id='sshore1check' name='sshore' value='1' style='height:30px; width:30px' onclick="changeCssClass('sshore1div')"></div>
                                                    <div id="sshore1div" class="divclass" style='font-family:Arial, Helvetica, sans-serif; font-size:11px; font-weight:bold;'>AIS SHOREsearch</div>
                                                <?php }else{ ?>
                                                    <div style="float:left;"><input type='checkbox' id='sshore1check' name='sshore' value='1' checked="checked" style='height:30px; width:30px' onclick="changeCssClass('sshore1div')"></div>
                                                    <div id='sshore1div' class="divclass_active" style='font-family:Arial, Helvetica, sans-serif; font-size:11px; font-weight:bold;'>AIS SHOREsearch</div>
                                                <?php } ?>
                                            </div>
                                            <div class='clickable3' style='padding-left:20px;' onclick='showLearnDialog("aisshore")'>CLICK TO LEARN MORE</div>
                                        </td>
                                        <td>
                                            <div style="padding-bottom:20px; width:170px;">
												<?php if($tabdata['sbroker']){ ?>
                                                    <div style="float:left;"><input type='checkbox' name='sbroker' value='1' checked="checked" style='height:30px; width:30px' onclick="changeCssClass('sbroker1')"></div>
                                                    <div id="sbroker1" class="divclass_active" style='font-family:Arial, Helvetica, sans-serif; font-size:11px; font-weight:bold;'>BROKERSintelligence</div>
                                                <?php }else if(trim($tabdata['load_port'])){ ?>
                                                    <div style="float:left;"><input type='checkbox' name='sbroker' value='1' style='height:30px; width:30px' onclick="changeCssClass('sbroker1')"></div>
                                                    <div id="sbroker1" class="divclass" style='font-family:Arial, Helvetica, sans-serif; font-size:11px; font-weight:bold;'>BROKERSintelligence</div>
                                                <?php }else{ ?>
                                                    <div style="float:left;"><input type='checkbox' name='sbroker' value='1' checked="checked" style='height:30px; width:30px' onclick="changeCssClass('sbroker1')"></div>
                                                    <div id="sbroker1" class="divclass_active" style='font-family:Arial, Helvetica, sans-serif; font-size:11px; font-weight:bold;'>BROKERSintelligence</div>
                                                <?php } ?>
                                            </div>
                                            <div class='clickable3' style='padding-left:20px;' onclick='showLearnDialog("brokersintelligence")'>CLICK TO LEARN MORE</div>
                                        </td>
                                        <td>
                                            <div style="padding-bottom:20px; width:170px;">
												<?php if($tabdata['semail']){ ?>
                                                    <div style="float:left;"><input type='checkbox' name='semail' value='1' checked="checked" style='height:30px; width:30px' onclick="changeCssClass('semail1')"></div>
                                                    <div id="semail1" class="divclass_active" style='font-family:Arial, Helvetica, sans-serif; font-size:11px; font-weight:bold;'>EMAILintelligence</div>
                            <?php }else if(trim($tabdata['load_port'])){ ?>
                                                    <div style="float:left;"><input type='checkbox' name='semail' value='1' style='height:30px; width:30px' onclick="changeCssClass('semail1')"></div>
                                                    <div id="semail1" class="divclass" style='font-family:Arial, Helvetica, sans-serif; font-size:11px; font-weight:bold;'>EMAILintelligence</div>
                            <?php }else{ ?>
                                                    <div style="float:left;"><input type='checkbox' name='semail' value='1' checked="checked" style='height:30px; width:30px' onclick="changeCssClass('semail1')"></div>
                                                    <div id="semail1" class="divclass_active" style='font-family:Arial, Helvetica, sans-serif; font-size:11px; font-weight:bold;'>EMAILintelligence</div>
                                              <?php } ?>
                                            </div>
                                            <div class='clickable3' style='padding-left:20px;' onclick='showLearnDialog("emailintelligence")'>CLICK TO LEARN MORE</div>
                                        </td>
                                    </tr>
                                </table>
                                </center>
                            </td>
                        </tr>	
                        <tr>
                            <td style='padding-top:10px; text-align:center;' colspan='2' align="center" >
                                <input class='cancelbutton' type="button" name="cancelsearch" value="CANCEL SEARCH"  style='cursor:pointer; display:none;' id='cancelsearch'  />
                                &nbsp;&nbsp;&nbsp;
                                <input class='searchbutton' type="button" name="search" value="SEARCH"  style='cursor:pointer' id='sbutton'  />
                    
                                <script>
								$("#cancelsearch").click(function(){
									jQuery("#cancelsearch").val("CANCELING SEARCH...");
									jQuery("#sbutton").hide();
									location.reload();
								});
								
								jQuery("#sbutton").click(
									function(){
										shipSearchx();
									}
								)
                                </script>
                            </td>
                        </tr>
                        <tr>
                            <td colspan='2'>
                                <div id='pleasewait' style='display:none; text-align:center'>
                                    <center>
                                    <table width="400" style="border:1px solid #06F;">
                                        <tr>
                                            <td style='text-align:left; padding:5px;'>Please be patient as S-BIS is doing millions of calculations to get your data. But be assured it is quicker than any other method!</td>
                                        </tr>
                                        <tr>
                                            <td style='text-align:center'>
                                                <div id='didyouknow'></div>
                                                <img src='images/searching.gif' >
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style='text-align:left; padding:5px;'>
                                                <p><b>Tips:</b></p>
                                                <p>&bull; Search by a narrow Date Range.</p>
                                                <p>&bull; Choose the Ship Type/s.</p>
                                                <p>&bull; Select a Region rather than the Whole World. (use your experience and knowledge of trade routes.)</p>
                                                <p>&bull; Number of Ships that display use 20 to start, HOWEVER to get all he possibilities increase that number.</p>
                                                <p>&bull; The first search of a new port is slower than the subsequent searches.</p>
                                                <p>&bull; These are very complicated Searches and take considerable time, so be very patient as the combinations to find the Right Ship for you are multiple millions of calculations.</p>
                                            </td>
                                        </tr>
                                    </table>
                                    </center>
                                </div>	
                            </td>
                        </tr>
                    </table>
                    
                    <script>
                    <?php
                    if($tabdata['load_port']){
                        ?>showZones("<?php echo $tabdata['load_port']; ?>");<?php
                    }
                    ?>
                    </script>
                    
                    </form>
                    
                    <div id='sresults' style='display:none;'>
						<h1 class="title" id='ssr' style='cursor:pointer; margin-bottom:0px;'>SHIP SEARCH RESULTS <img style='display:none' src='images/up.png' id='searchricon' ></h1>
                        <div id="records_tab_wrapper">
                            <div id="container-1"></div>
                        </div>
                    </div>
                    <!--END OF FAST SEARCH-->
                    
                    <!--SHIP SEARCH ONLY-->
                    <table width="100%" border="0" cellpadding="0" cellspacing="0" id="parameters_table2" style='display:none; margin-bottom:5px;'>
                        <tr>
                            <td>
                                <script>
								function shipSearchOnly(){
									jQuery("#shipdetails").hide();
									jQuery('#shipsearchonlyresults').hide();

									jQuery('#pleasewait3').show();

									jQuery("#sbutton2").val("SEARCHING...");
									jQuery("#sbutton2")[0].disabled = true;
									
									jQuery("#searchby1").attr("disabled", true);
									jQuery("#searchby2").attr("disabled", true);
									jQuery("#searchby3").attr("disabled", true);
									jQuery("#searchby4").attr("disabled", true);
									jQuery("#searchby5").attr("disabled", true);
									jQuery("#searchby6").attr("disabled", true);
									jQuery("#searchby7").attr("disabled", true);
									jQuery("#searchby8").attr("disabled", true);
									jQuery("#searchby9").attr("disabled", true);
									
									jQuery('#cancelsearch2').show();

									jQuery.ajax({
										type: 'GET',
										url: "search_ajax2.php",
										data:  jQuery("#shipsearchonly").serialize(),

										success: function(data) {
											jQuery("#records_tab_wrapperonly").html(data);
											jQuery('#shipsearchonlyresults').fadeIn(200);

											jQuery("#sbutton2").val("SEARCH");	
											jQuery("#sbutton2")[0].disabled = false;
											
											jQuery('#pleasewait3').hide();

											jQuery("#searchby1").attr("disabled", false);
											jQuery("#searchby2").attr("disabled", false);
											jQuery("#searchby3").attr("disabled", false);
											jQuery("#searchby4").attr("disabled", false);
											jQuery("#searchby5").attr("disabled", false);
											jQuery("#searchby6").attr("disabled", false);
											jQuery("#searchby7").attr("disabled", false);
											jQuery("#searchby8").attr("disabled", false);
											jQuery("#searchby9").attr("disabled", false);
											
											jQuery('#cancelsearch2').hide();
										}
									});
								}
                                </script>

                                <form id='shipsearchonly' onsubmit="shipSearchOnly(); return false;">
                                <center>
                                <table>
                                    <tr>
                                        <td>SHIP NAME, IMO, MMSI, CALLSIGN</td>
                                        <td><input type='text' name='ship' class='text' style='width:200px'></td>
                                        <td>MANAGER / MANAGER OWNER</td>
                                        <td><input type='text' name='operator' class='text' style='width:200px'></td>
                                    </tr>
                                    <tr>
                                        <td colspan='4' style="text-align:center;"><input class='cancelbutton' type="button" id='cancelsearch2' name="cancelsearch2" value="CANCEL SEARCH"  style='cursor:pointer; display:none;'  /> &nbsp;&nbsp;&nbsp; <input class='searchbutton' type="button" id='sbutton2' name="search" value="SEARCH" style='cursor:pointer;' onclick='shipSearchOnly();'  /></td>
                                    </tr>
                                </table>
                                </center>
                                </form>
                            </td>
                        </tr>
						
                        <script>
						$("#cancelsearch2").click(function(){
							jQuery("#cancelsearch2").val("CANCELING SEARCH...");
							jQuery("#sbutton2").hide();
							location.reload();
						});
						</script>
                        
                        <tr>
                            <td>
                                <div id='pleasewait3' style='display:none; text-align:center'>
                                    <center>
                                    <table>
                                        <tr>
                                            <td style='text-align:center'><img src='images/searching.gif' ></td>
                                        </tr>
                                    </table>
                                    </center>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <div id='shipsearchonlyresults'>
                        <div id='records_tab_wrapperonly'></div>
                    </div>
                    <!--END OF SHIP SEARCH ONLY-->
                    
                    <!--FLEET POSITIONS-->
                    <table width="100%" border="0" cellpadding="0" cellspacing="0" id="parameters_table3" style='display:none; margin-bottom:5px;'>
                        <tr>
                            <td>
                                <script>
								function fleetPositions(){
									jQuery("#fleetpositionsdetails").hide();
									jQuery('#fleetpositionsresults').hide();

									jQuery('#pleasewait4').show();

									jQuery("#sbutton3").val("SEARCHING...");
									jQuery("#sbutton3")[0].disabled = true;
									
									jQuery("#searchby1").attr("disabled", true);
									jQuery("#searchby2").attr("disabled", true);
									jQuery("#searchby3").attr("disabled", true);
									jQuery("#searchby4").attr("disabled", true);
									jQuery("#searchby5").attr("disabled", true);
									jQuery("#searchby6").attr("disabled", true);
									jQuery("#searchby7").attr("disabled", true);
									jQuery("#searchby8").attr("disabled", true);
									jQuery("#searchby9").attr("disabled", true);
									
									jQuery('#cancelsearch3').show();

									jQuery.ajax({
										type: 'GET',
										url: "search_ajax3.php",
										data:  jQuery("#fleetpositions").serialize(),

										success: function(data) {
											jQuery("#fleetpositions_records_tab_wrapperonly").html(data);
											jQuery('#fleetpositionsresults').fadeIn(200);

											jQuery("#sbutton3").val("SEARCH");	
											jQuery("#sbutton3")[0].disabled = false;
											
											jQuery('#pleasewait4').hide();

											jQuery("#searchby1").attr("disabled", false);
											jQuery("#searchby2").attr("disabled", false);
											jQuery("#searchby3").attr("disabled", false);
											jQuery("#searchby4").attr("disabled", false);
											jQuery("#searchby5").attr("disabled", false);
											jQuery("#searchby6").attr("disabled", false);
											jQuery("#searchby7").attr("disabled", false);
											jQuery("#searchby8").attr("disabled", false);
											jQuery("#searchby9").attr("disabled", false);
											
											jQuery('#cancelsearch3').hide();
										}
									});
								}
                                </script>

                                <form id='fleetpositions' onsubmit="fleetPositions(); return false;">
                                <center>
                                <table>
                                    <tr>
                                    	<td>MANAGER / MANAGER OWNER</td>
                                        <td><input type='text' name='operator' class='text' style='width:200px'></td>
                                        <td>SHIP NAME, IMO, MMSI, CALLSIGN</td>
                                        <td><input type='text' name='ship' class='text' style='width:200px'></td>
                                    </tr>
                                    <tr>
                                        <td colspan='4' style="text-align:center;"><input class='cancelbutton' type="button" id='cancelsearch3' name="cancelsearch3" value="CANCEL SEARCH"  style='cursor:pointer; display:none;'  /> &nbsp;&nbsp;&nbsp; <input class='searchbutton' type="button" id='sbutton3' name="search" value="SEARCH" style='cursor:pointer;' onclick='fleetPositions();'  /></td>
                                    </tr>
                                </table>
                                </center>
                                </form>
                            </td>
                        </tr>
						
                        <script>
						$("#cancelsearch3").click(function(){
							jQuery("#cancelsearch3").val("CANCELING SEARCH...");
							jQuery("#sbutton3").hide();
							location.reload();
						});
						</script>
                        
                        <tr>
                            <td>
                                <div id='pleasewait4' style='display:none; text-align:center'>
                                    <center>
                                    <table>
                                        <tr>
                                            <td style='text-align:center'><img src='images/searching.gif' ></td>
                                        </tr>
                                    </table>
                                    </center>
                                </div>
                            </td>
                        </tr>
                    </table>
                    
                    <div id="mapdialogfleet" title="MAP" style='display:none;'>
                        <iframe id="mapiframefleet" name='mapname' frameborder=0 height="100%" width="100%" style='border:0px; height:100%; width:100%'></iframe>
                    </div>
                    
                    <script type="text/javascript">
                    jQuery("#mapdialogfleet" ).dialog( { width: '100%', height: jQuery(window).height()*0.9 });
                    jQuery("#mapdialogfleet").dialog("close");
                    
                    function showMapFP(){
                        jQuery('#pleasewait4').show();
    
                        jQuery.ajax({
                            type: 'GET',
                            url: "search_ajax3.php",
                            data:  jQuery("#fleetpositions").serialize(),
    
                            success: function(data) {
                                jQuery("#mapiframefleet")[0].src='map/index2.php';
                                jQuery("#mapdialogfleet").dialog("open");
                                
                                jQuery('#pleasewait4').hide();
                            }
                        });
                    }
                    </script>
                    
                    <div id='fleetpositionsresults'>
                        <div id='fleetpositions_records_tab_wrapperonly'></div>
                    </div>
                    <!--END OF FLEET POSITIONS-->
                    
                    <!--PIRACY ALERTS-->
                    <script>
					function openMapPiracyAlert(date, lat, long, text){
						jQuery("#mapiframepiracyalert")[0].src='map/index3.php?date='+date+'&lat='+lat+'&long='+long+'&text='+text;
						jQuery("#mapdialogpiracyalert").dialog("open");
					}
					</script>
                    
                    <table width="100%" border="0" cellpadding="2" cellspacing="2" id="parameters_table4" style='display:none; margin-bottom:5px;'>
                        <tr style="padding-bottom:10px;">
                            <td width="100%" align="center" colspan="3"><div style='padding:5px;'><a onclick='showMapPA();' class='clickable'>view larger map</a></div></td>
                        </tr>
                        <tr style='background:#999;'>
                        	<th width="100%" align="center" colspan="3"><div style='padding:5px;'><iframe src='' id="map_iframe" width='990' height='500'></iframe></div></th>
                        </tr>
                        <tr style='background:#999;'>
                        	<th width="100" align="left"><div style='padding:5px;'>DATE</div></th>
                            <th width="100" align="left"><div style='padding:5px;'>TIME</div></th>
                            <th><div style='padding:5px;'>ALERT</div></th>
                        </tr>
                        
                        <?php
						$sql = "SELECT * FROM _sbis_piracy_alerts ORDER BY dateadded DESC LIMIT 0,180";
						$data = dbQuery($sql);
						$t = count($data);
					
						for($i1=0; $i1<$t; $i1++){
							if($data[$i1]['alert']!=$data[$i1-1]['alert']){
								$lines = explode("<ALERT>", $data[$i1]['alert']);
								
								if($lines){
									$i = 1;
									foreach($lines as $line){
										if(getValue($lines[$i], 'TEXT')!=""){
											echo "<tr style='background:#e5e5e5;'>
												<td align='left'><div style='padding:5px;'>".date("M d, Y", strtotime(getValue($lines[$i], 'DATE')))."</div></td>
												<td align='left'><div style='padding:5px;'>".date("G:i:s", strtotime(getValue($lines[$i], 'DATE')))." UTC</div></td>
												<td align='left'><div style='padding:5px;'><a onclick='openMapPiracyAlert(\"".date("M d, Y G:i:s", strtotime(getValue($lines[$i], 'DATE')))." UTC\", \"".getValue($lines[$i], 'LATITUDE')."\", \"".getValue($lines[$i], 'LONGITUDE')."\", \"".addslashes(getValue($lines[$i], 'TEXT'))."\")' class='clickable'>".getValue($lines[$i], 'TEXT')."</a></div></td>
											</tr>";
										}
										
										$i++;
									}
								}
							}
						}
						?>
                        
                        <div id="mapdialog2" title="MAP" style='display:none;'>
                            <iframe id="mapiframe2" name='mapname' frameborder=0 height="100%" width="100%" style='border:0px; height:100%; width:100%'></iframe>
                        </div>
                        
                        <script type="text/javascript">
                        jQuery("#mapdialog2" ).dialog( { width: '100%', height: jQuery(window).height()*0.9 });
                        jQuery("#mapdialog2").dialog("close");
                        
                        function showMapPA(){
                            jQuery("#mapiframe2")[0].src='map/index4.php';
                            jQuery("#mapdialog2").dialog("open");
                        }
                        </script>
                        
                    </table>
                    <!--END OF PIRACY ALERTS-->
                    
                    <!--SHIPS COMING INTO PORTS-->
                    <table width="100%" border="0" cellpadding="0" cellspacing="0" id="parameters_table5" style='display:none; margin-bottom:5px;'>
                        <tr>
                            <td>
                                <script>
								function shipsComingIntoPorts(){
									jQuery("#shipscomingintoportsdetails").hide();
									jQuery('#shipscomingintoportsresults').hide();

									jQuery('#pleasewait5').show();

									jQuery("#sbutton5").val("SEARCHING...");
									jQuery("#sbutton5")[0].disabled = true;
									
									jQuery("#searchby1").attr("disabled", true);
									jQuery("#searchby2").attr("disabled", true);
									jQuery("#searchby3").attr("disabled", true);
									jQuery("#searchby4").attr("disabled", true);
									jQuery("#searchby5").attr("disabled", true);
									jQuery("#searchby6").attr("disabled", true);
									jQuery("#searchby7").attr("disabled", true);
									jQuery("#searchby8").attr("disabled", true);
									jQuery("#searchby9").attr("disabled", true);
									
									jQuery('#cancelsearch5').show();

									jQuery.ajax({
										type: 'GET',
										url: "search_ajax4.php",
										data:  jQuery("#shipscomingintoports").serialize(),

										success: function(data) {
											jQuery("#shipscomingintoports_records_tab_wrapperonly").html(data);
											jQuery('#shipscomingintoportsresults').fadeIn(200);

											jQuery("#sbutton5").val("SEARCH");	
											jQuery("#sbutton5")[0].disabled = false;
											
											jQuery('#pleasewait5').hide();

											jQuery("#searchby1").attr("disabled", false);
											jQuery("#searchby2").attr("disabled", false);
											jQuery("#searchby3").attr("disabled", false);
											jQuery("#searchby4").attr("disabled", false);
											jQuery("#searchby5").attr("disabled", false);
											jQuery("#searchby6").attr("disabled", false);
											jQuery("#searchby7").attr("disabled", false);
											jQuery("#searchby8").attr("disabled", false);
											jQuery("#searchby9").attr("disabled", false);
											
											jQuery('#cancelsearch5').hide();
										}
									});
								}
                                </script>

                                <form id='shipscomingintoports' onsubmit="shipsComingIntoPorts(); return false;">
                                <center>
                                <table>
                                    <tr>
                                    	<td><b>PORT NAME</b></td>
                                        <td width="5">&nbsp;</td>
                                        <td>
                                        	<input type='text' id="suggest3" name='port_name' class='text' style='width:200px; padding:3px;'>
                                            <script type="text/javascript">
                                            jQuery("#suggest3").focus().autocomplete(ports);
                                            jQuery("#suggest3").setOptions({
                                                scrollHeight: 180
                                            });
                                            </script>
                                        </td>
                                        <td width="10">&nbsp;</td>
                                        <td><b>DATE FROM</b></td>
                                        <td width="5">&nbsp;</td>
                                        <td>
                                        	<input type="text" name="date_from" value="<?php echo date("M d, Y", time()); ?>" readonly="readonly" onclick="showCalendar('',this,null,'','',0,5,1)" class="text" style="width:90px; padding:3px;" />
                                
                                            <b>TO</b>
                                
                                            <input type="text" name="date_to" value="<?php echo date("M d, Y", time()+(7*24*60*60)); ?>" readonly="readonly" onclick="showCalendar('',this,null,'','',0,5,1)" class="text" style="width:90px; padding:3px;" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="7">&nbsp;</td>
                                    </tr>
                                    <tr>
                                    	<td valign="top"><b>SHIP TYPE</b></td>
                                        <td width="5">&nbsp;</td>
                                        <td>
                                        	<select name="p_vessel_type[]" multiple="multiple" size="16" id='p_vessel_type_id' style="width:200px;">
                                                <optgroup label="BULK CARRIER">
                                                	<option value="ORE CARRIER">ORE CARRIER</option>
                                                    <option value="WOOD CHIPS CARRIER">WOOD CHIPS CARRIER</option>
                                                </optgroup>
                                                <optgroup label="CARGO">
                                                	<option value="BARGE CARRIER">BARGE CARRIER</option>
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
                                                	<option value="RO-RO/CONTAINER CARRIER">RO-RO/CONTAINER CARRIER</option>
                                                    <option value="RO-RO/PASSENGER SHIP">RO-RO/PASSENGER SHIP</option>
                                                </optgroup>
                                            </select>
                                        </td>
                                        <td width="10">&nbsp;</td>
                                        <td><b>&nbsp;</b></td>
                                        <td width="5">&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td colspan="7">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td colspan="7" style="text-align:center; padding-top:15px;"><input class='cancelbutton' type="button" id='cancelsearch5' name="cancelsearch5" value="CANCEL SEARCH"  style='cursor:pointer; display:none;'  /> &nbsp;&nbsp;&nbsp; <input class='searchbutton' type="button" id='sbutton5' name="search" value="SEARCH" style='cursor:pointer;' onclick='shipsComingIntoPorts();'  /></td>
                                    </tr>
                                    <tr>
                                        <td colspan="7">&nbsp;</td>
                                    </tr>
                                </table>
                                </center>
                                </form>
                            </td>
                        </tr>
						
                        <script>
						$("#cancelsearch5").click(function(){
							jQuery("#cancelsearch5").val("CANCELING SEARCH...");
							jQuery("#sbutton5").hide();
							location.reload();
						});
						</script>
                        
                        <tr>
                            <td>
                                <div id='pleasewait5' style='display:none; text-align:center'>
                                    <center>
                                    <table>
                                        <tr>
                                            <td style='text-align:center'><img src='images/searching.gif' ></td>
                                        </tr>
                                    </table>
                                    </center>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <div id='shipscomingintoportsresults'>
                        <div id='shipscomingintoports_records_tab_wrapperonly'></div>
                    </div>
                    <!--END OF SHIPS COMING INTO PORTS-->
                    
                    <!--LIVE SHIP POSITION-->
                    <script>
					function viewLiveShipPosition(){
						jQuery('#liveshippositionresults').hide();

						jQuery('#pleasewait2').show();
						
						jQuery("#searchby1").attr("disabled", true);
						jQuery("#searchby2").attr("disabled", true);
						jQuery("#searchby3").attr("disabled", true);
						jQuery("#searchby4").attr("disabled", true);
						jQuery("#searchby5").attr("disabled", true);
						jQuery("#searchby6").attr("disabled", true);
						jQuery("#searchby7").attr("disabled", true);
						jQuery("#searchby8").attr("disabled", true);
						jQuery("#searchby9").attr("disabled", true);

						jQuery.ajax({
							type: 'GET',
							url: "search_ajax7.php",
							data:  jQuery("#live_ship_position").serialize(),

							success: function(data) {
								jQuery("#liveshipposition_records_tab_wrapperonly").html(data);
								jQuery('#liveshippositionresults').fadeIn(200);
								
								jQuery('#pleasewait2').hide();

								jQuery("#searchby1").attr("disabled", false);
								jQuery("#searchby2").attr("disabled", false);
								jQuery("#searchby3").attr("disabled", false);
								jQuery("#searchby4").attr("disabled", false);
								jQuery("#searchby5").attr("disabled", false);
								jQuery("#searchby6").attr("disabled", false);
								jQuery("#searchby7").attr("disabled", false);
								jQuery("#searchby8").attr("disabled", false);
								jQuery("#searchby9").attr("disabled", false);
							}
						});
					}
					
					function toggleCategories(){
						if(document.getElementById('live_ship_positions_categories').style.display == "none"){
							document.getElementById('paramicon1').src = "images/down.png";
							document.getElementById('live_ship_positions_categories').style.display = "block";
						}else{
							document.getElementById('paramicon1').src = "images/up.png";
							document.getElementById('live_ship_positions_categories').style.display = "none";
						}
					}
					</script>
                    
                    <form id='live_ship_position' onsubmit="viewLiveShipPosition(); return false;">
                    <table width="100%" border="0" cellpadding="0" cellspacing="0" id="parameters_table6" style='display:none; margin-bottom:5px;'>
                        <tr>
                            <td>
                                <center>
                                <table>
                                	<tr>
                                        <td>
                                            <div style='padding:5px;'>
                                            	<table width="990">
                                                    <tr>
                                                    	<td width="80"><h2><a style="cursor:pointer;" onclick="toggleCategories();">CATEGORIES</a></h2></td>
                                                        <td><a style="cursor:pointer;" onclick="toggleCategories();"><img src='images/up.png' width="15" height="15" id='paramicon1' /></a></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">&nbsp;</td>
                                                    </tr>
                                                </table>
                                            	<table id="live_ship_positions_categories" width="990">
                                                    <tr>
                                                        <td colspan="8">
                                                        	<table width="990">
                                                                <tr>
                                                                    <td colspan="18" style="padding-top:10px;">&nbsp;</td>
                                                                </tr>
                                                                <tr style="padding-top:25px; font-family:Arial, Helvetica, sans-serif; font-size:10px;">
                                                                    <td valign="top"><div style='padding:5px; text-align:right;'>&laquo; 90 DAYS</div></td>
                                                                    <td valign="top"><div style='padding:2px 5px 5px 0px;'><input type="checkbox" id="pos_daterange_id" name="pos_daterange[]" onclick="viewLiveShipPosition();" value="bd90" /></div></td>
                                                                    <td valign="top"><div style='padding:5px; text-align:right;'>&laquo; 60 DAYS</div></td>
                                                                    <td valign="top"><div style='padding:2px 5px 5px 0px;'><input type="checkbox" id="pos_daterange_id" name="pos_daterange[]" onclick="viewLiveShipPosition();" value="bd60" /></div></td>
                                                                    <td valign="top"><div style='padding:5px; text-align:right;'>&laquo; 30 DAYS</div></td>
                                                                    <td valign="top"><div style='padding:2px 5px 5px 0px;'><input type="checkbox" id="pos_daterange_id" name="pos_daterange[]" onclick="viewLiveShipPosition();" value="bd30" /></div></td>
                                                                    <td valign="top"><div style='padding:5px; color:#F00; text-align:right;'>TODAY &raquo;</div></td>
                                                                    <td valign="top"><div style='padding:2px 5px 5px 0px;'><input type="checkbox" id="pos_daterange_id" name="pos_daterange[]" onclick="viewLiveShipPosition();" value="t" /></div></td>
                                                                    <td valign="top"><div style='padding:5px; text-align:right;'>1 DAY &raquo;</div></td>
                                                                    <td valign="top"><div style='padding:2px 5px 5px 0px;'><input type="checkbox" id="pos_daterange_id" name="pos_daterange[]" onclick="viewLiveShipPosition();" value="fd1" /></div></td>
                                                                    <td valign="top"><div style='padding:5px; text-align:right;'>7 DAYS &raquo;</div></td>
                                                                    <td valign="top"><div style='padding:2px 5px 5px 0px;'><input type="checkbox" id="pos_daterange_id" name="pos_daterange[]" onclick="viewLiveShipPosition();" value="fd7" /></div></td>
                                                                    <td valign="top"><div style='padding:5px; text-align:right;'>30 DAYS &raquo;</div></td>
                                                                    <td valign="top"><div style='padding:2px 5px 5px 0px;'><input type="checkbox" id="pos_daterange_id" name="pos_daterange[]" onclick="viewLiveShipPosition();" value="fd30" /></div></td>
                                                                    <td valign="top"><div style='padding:5px; text-align:right;'>60 DAYS &raquo;</div></td>
                                                                    <td valign="top"><div style='padding:2px 5px 5px 0px;'><input type="checkbox" id="pos_daterange_id" name="pos_daterange[]" onclick="viewLiveShipPosition();" value="fd60" /></div></td>
                                                                    <td valign="top"><div style='padding:5px; text-align:right;'>90 DAYS &raquo;</div></td>
                                                                    <td valign="top"><div style='padding:2px 5px 5px 0px;'><input type="checkbox" id="pos_daterange_id" name="pos_daterange[]" onclick="viewLiveShipPosition();" value="fd90" /></div></td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="18" style="padding-top:10px;">&nbsp;</td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                    	<td valign="top" width="20"><div style='padding:5px 0px 5px 5px;'><input type="checkbox" id="pos_vessel_type_id" name="pos_vessel_type[]" onclick="viewLiveShipPosition();" value="ORE CARRIER" /></div></td>
                                                        <td valign="top" width="228"><div style='padding:5px;'>ORE CARRIER</div></td>
                                                        <td valign="top" width="20"><div style='padding:5px 0px 5px 5px;'><input type="checkbox" id="pos_vessel_type_id" name="pos_vessel_type[]" onclick="viewLiveShipPosition();" value="WOOD CHIPS CARRIER" /></div></td>
                                                        <td valign="top" width="227"><div style='padding:5px;'>WOOD CHIPS CARRIER</div></td>
                                                        <td valign="top" width="20"><div style='padding:5px 0px 5px 5px;'><input type="checkbox" id="pos_vessel_type_id" name="pos_vessel_type[]" onclick="viewLiveShipPosition();" value="BARGE CARRIER" /></div></td>
                                                        <td valign="top" width="227"><div style='padding:5px;'>BARGE CARRIER</div></td>
                                                        <td valign="top" width="20"><div style='padding:5px 0px 5px 5px;'><input type="checkbox" id="pos_vessel_type_id" name="pos_vessel_type[]" onclick="viewLiveShipPosition();" value="CARGO/PASSENGER SHIP" /></div></td>
                                                        <td valign="top" width="228"><div style='padding:5px;'>CARGO/PASSENGER SHIP</div></td>
                                                    </tr>
                                                    <tr>
                                                    	<td valign="top" width="20"><div style='padding:5px 0px 5px 5px;'><input type="checkbox" id="pos_vessel_type_id" name="pos_vessel_type[]" onclick="viewLiveShipPosition();" value="HEAVY LOAD CARRIER" /></div></td>
                                                        <td valign="top" width="228"><div style='padding:5px;'>HEAVY LOAD CARRIER</div></td>
                                                        <td valign="top" width="20"><div style='padding:5px 0px 5px 5px;'><input type="checkbox" id="pos_vessel_type_id" name="pos_vessel_type[]" onclick="viewLiveShipPosition();" value="LIVESTOCK CARRIER" /></div></td>
                                                        <td valign="top" width="227"><div style='padding:5px;'>LIVESTOCK CARRIER</div></td>
                                                        <td valign="top" width="20"><div style='padding:5px 0px 5px 5px;'><input type="checkbox" id="pos_vessel_type_id" name="pos_vessel_type[]" onclick="viewLiveShipPosition();" value="MOTOR HOPPER" /></div></td>
                                                        <td valign="top" width="227"><div style='padding:5px;'>MOTOR HOPPER</div></td>
                                                        <td valign="top" width="20"><div style='padding:5px 0px 5px 5px;'><input type="checkbox" id="pos_vessel_type_id" name="pos_vessel_type[]" onclick="viewLiveShipPosition();" value="NUCLEAR FUEL CARRIER" /></div></td>
                                                        <td valign="top" width="228"><div style='padding:5px;'>NUCLEAR FUEL CARRIER</div></td>
                                                    </tr>
                                                    <tr>
                                                    	<td valign="top" width="20"><div style='padding:5px 0px 5px 5px;'><input type="checkbox" id="pos_vessel_type_id" name="pos_vessel_type[]" onclick="viewLiveShipPosition();" value="SLUDGE CARRIER" /></div></td>
                                                        <td valign="top" width="228"><div style='padding:5px;'>SLUDGE CARRIER</div></td>
                                                        <td valign="top" width="20"><div style='padding:5px 0px 5px 5px;'><input type="checkbox" id="pos_vessel_type_id" name="pos_vessel_type[]" onclick="viewLiveShipPosition();" value="CEMENT CARRIER" /></div></td>
                                                        <td valign="top" width="227"><div style='padding:5px;'>CEMENT CARRIER</div></td>
                                                        <td valign="top" width="20"><div style='padding:5px 0px 5px 5px;'><input type="checkbox" id="pos_vessel_type_id" name="pos_vessel_type[]" onclick="viewLiveShipPosition();" value="OBO CARRIER" /></div></td>
                                                        <td valign="top" width="227"><div style='padding:5px;'>OBO CARRIER</div></td>
                                                        <td valign="top" width="20"><div style='padding:5px 0px 5px 5px;'><input type="checkbox" id="pos_vessel_type_id" name="pos_vessel_type[]" onclick="viewLiveShipPosition();" value="RO-RO/CONTAINER CARRIER" /></div></td>
                                                        <td valign="top" width="228"><div style='padding:5px;'>RO-RO/CONTAINER CARRIER</div></td>
                                                    </tr>
                                                    <tr>
                                                    	<td valign="top" width="20"><div style='padding:5px 0px 5px 5px;'><input type="checkbox" id="pos_vessel_type_id" name="pos_vessel_type[]" onclick="viewLiveShipPosition();" value="RO-RO/PASSENGER SHIP" /></div></td>
                                                        <td valign="top" width="228"><div style='padding:5px;'>RO-RO/PASSENGER SHIP</div></td>
                                                        <td valign="top" width="20"><div style='padding:5px 0px 5px 5px;'>&nbsp;</div></td>
                                                        <td valign="top" width="227"><div style='padding:5px;'>&nbsp;</div></td>
                                                        <td valign="top" width="20"><div style='padding:5px 0px 5px 5px;'>&nbsp;</div></td>
                                                        <td valign="top" width="227"><div style='padding:5px;'>&nbsp;</div></td>
                                                        <td valign="top" width="20"><div style='padding:5px 0px 5px 5px;'>&nbsp;</div></td>
                                                        <td valign="top" width="228"><div style='padding:5px;'>&nbsp;</div></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                    
                                    <div id="mapdialog1" title="MAP" style='display:none;'>
                                        <iframe id="mapiframe1" name='mapname' frameborder=0 height="100%" width="100%" style='border:0px; height:100%; width:100%'></iframe>
                                    </div>
                                    
                                    <script type="text/javascript">
                                    jQuery("#mapdialog1" ).dialog( { width: '100%', height: jQuery(window).height()*0.9 });
                                    jQuery("#mapdialog1").dialog("close");
                                    
                                    function showMap(){
                                        jQuery('#pleasewait2').show();
                    
                                        jQuery.ajax({
                                            type: 'GET',
                                            url: "search_ajax7.php",
                                            data:  jQuery("#live_ship_position").serialize(),
                    
                                            success: function(data) {
                                                jQuery("#mapiframe1")[0].src='map/index10_online.php';
                                                jQuery("#mapdialog1").dialog("open");
                                                
                                                jQuery('#pleasewait2').hide();
                                            }
                                        });
                                    }
                                    </script>
                                    
                                    <tr>
                                        <td style="padding-top:20px;">
                                            <div id='liveshippositionresults'>
                                                <div id='liveshipposition_records_tab_wrapperonly'></div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                                </center>
                            </td>
                        </tr>
                    </table>
                    </form>
                    <!--END OF LIVE SHIP POSITION-->
                    
                    <!--CARGO-->
                    <script>
                    function cargoSubmit(){
                        jQuery('#cargoresults').hide();
                    
                        jQuery('#pleasewait_cargo').show();
						
						jQuery("#searchby1").attr("disabled", true);
						jQuery("#searchby2").attr("disabled", true);
						jQuery("#searchby3").attr("disabled", true);
						jQuery("#searchby4").attr("disabled", true);
						jQuery("#searchby5").attr("disabled", true);
						jQuery("#searchby6").attr("disabled", true);
						jQuery("#searchby7").attr("disabled", true);
						jQuery("#searchby8").attr("disabled", true);
						jQuery("#searchby9").attr("disabled", true);
                    
                        jQuery("#btn_search_cargo_id").val("SEARCHING...");
                        jQuery("#btn_search_cargo_id")[0].disabled = true;
                        
                        jQuery('#btn_cancelsearch_cargo_id').show();
                    
                        jQuery.ajax({
                            type: 'GET',
                            url: "search_ajax_cargo.php",
                            data:  jQuery("#cargo_form").serialize(),
                    
                            success: function(data) {
                                jQuery("#cargo_tab_wrapperonly").html(data);
                                jQuery('#cargoresults').fadeIn(200);
                    
                                jQuery("#btn_search_cargo_id").val("SEARCH");	
                                jQuery("#btn_search_cargo_id")[0].disabled = false;
                                
                                jQuery('#pleasewait_cargo').hide();
								
								jQuery("#searchby1").attr("disabled", false);
								jQuery("#searchby2").attr("disabled", false);
								jQuery("#searchby3").attr("disabled", false);
								jQuery("#searchby4").attr("disabled", false);
								jQuery("#searchby5").attr("disabled", false);
								jQuery("#searchby6").attr("disabled", false);
								jQuery("#searchby7").attr("disabled", false);
								jQuery("#searchby8").attr("disabled", false);
								jQuery("#searchby9").attr("disabled", false);
                                
                                jQuery('#btn_cancelsearch_cargo_id').hide();
                            }
                        });
                    }
                    </script>
                    
                    <form id='cargo_form' onsubmit="cargoSubmit(); return false;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" id="parameters_table7" style='display:none; margin-bottom:5px;'>
                      <tr>
                        <td>
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td width="90" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">PORT NAME</td>
                                <td width="10" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">:</td>
                                <td><input id='port_id' type="text" name="port" class="text" style='width:200px;' /></td>
                              </tr>
                            </table>
                        </td>
                        <td>
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td width="130" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">LOAD DATE</td>
                                <td width="10" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">:</td>
                                <td><input type="text" name="load_cargo_date_from" value="<?php echo date("M d, Y", time()); ?>" onclick="showCalendar('',this,null,'','',0,5,1)" class="text" style="width:90px;" /> to <input type="text" name="load_cargo_date_to" value="<?php echo date("M d, Y", time()+(7*24*60*60)); ?>" onclick="showCalendar('',this,null,'','',0,5,1)" class="text" style="width:90px;" /></td>
                              </tr>
                              <tr>
                                <td colspan="3">&nbsp;</td>
                              </tr>
                              <tr>
                                <td style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">DISCHARGE DATE</td>
                                <td style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">:</td>
                                <td><input type="text" name="discharge_cargo_date_from" value="<?php echo date("M d, Y", time()); ?>" onclick="showCalendar('',this,null,'','',0,5,1)" class="text" style="width:90px;" /> to <input type="text" name="discharge_cargo_date_to" value="<?php echo date("M d, Y", time()+(7*24*60*60)); ?>" onclick="showCalendar('',this,null,'','',0,5,1)" class="text" style="width:90px;" /></td>
                              </tr>
                            </table>
                        </td>
                      </tr>
                      <tr>
                        <td style="padding:30px 0px;" align="center" colspan="2"><input class='cancelbutton' type="button" id='btn_cancelsearch_cargo_id' name="btn_cancelsearch_cargo" value="CANCEL SEARCH"  style='cursor:pointer; display:none;'  /> &nbsp;&nbsp;&nbsp; <input class='searchbutton' type="button" id='btn_search_cargo_id' name="btn_search_cargo" value="SEARCH" style='cursor:pointer;' onclick='cargoSubmit();'  /></td>
                      </tr>
                      <tr>
                        <td colspan="2">
                            <div id='pleasewait_cargo' style='display:none; text-align:center'>
                                <center>
                                <table>
                                    <tr>
                                        <td style='text-align:center'><img src='images/searching.gif' ></td>
                                    </tr>
                                </table>
                                </center>
                            </div>
                        </td>
                      </tr>
                      <tr>
                        <td colspan="2">
                        	<div id='cargoresults'>
                                <div id='cargo_tab_wrapperonly'></div>
                            </div>
                        </td>
                      </tr>
                    </table>
                    </form>
                    
                    <script type="text/javascript">
					jQuery("#port_id").focus().autocomplete(ports);
					jQuery("#port_id").setOptions({
						scrollHeight: 180
					});
					
					$("#cancelsearch").click(function(){
						jQuery("#btn_cancelsearch_cargo_id").val("CANCELING SEARCH...");
						jQuery("#btn_search_cargo_id").hide();
						location.reload();
					});
					</script>
                    <!--END OF CARGO-->
                    
                    <!--PORT INTELLIGENCE-->
                    <div id="mapdialogportintelligence" title="MAP" style='display:none;'>
						<iframe id="mapiframeportintelligence" name='mapname' frameborder=0 height="100%" width="100%" style='border:0px; height:100%; width:100%'></iframe>
					</div>
                    
                    <script>
					jQuery("#mapdialogportintelligence" ).dialog( { width: '100%', height: jQuery(window).height()*0.9 });
					jQuery("#mapdialogportintelligence").dialog("close");
					
					function showMapPI(){
						jQuery('#pleasewait_portintelligence').show();
	
						jQuery.ajax({
							type: 'GET',
							url: "search_ajax_portintelligence.php",
							data:  jQuery("#portintelligence_form").serialize(),
	
							success: function(data) {
								jQuery("#mapiframeportintelligence")[0].src='map/index11.php';
								jQuery("#mapdialogportintelligence").dialog("open");
								
								jQuery('#pleasewait_portintelligence').hide();
							}
						});
					}
					
                    function portIntelligenceSubmit(){
                        jQuery('#portintelligenceresults').hide();
                    
                        jQuery('#pleasewait_portintelligence').show();
						
						jQuery("#searchby1").attr("disabled", true);
						jQuery("#searchby2").attr("disabled", true);
						jQuery("#searchby3").attr("disabled", true);
						jQuery("#searchby4").attr("disabled", true);
						jQuery("#searchby5").attr("disabled", true);
						jQuery("#searchby6").attr("disabled", true);
						jQuery("#searchby7").attr("disabled", true);
						jQuery("#searchby8").attr("disabled", true);
						jQuery("#searchby9").attr("disabled", true);
                    
                        jQuery("#btn_search_portintelligence_id").val("SEARCHING...");
                        jQuery("#btn_search_portintelligence_id")[0].disabled = true;
                        
                        jQuery('#btn_cancelsearch_portintelligence_id').show();
                    
                        jQuery.ajax({
                            type: 'GET',
                            url: "search_ajax_portintelligence.php",
                            data:  jQuery("#portintelligence_form").serialize(),
                    
                            success: function(data) {
                                jQuery("#portintelligence_tab_wrapperonly").html(data);
                                jQuery('#portintelligenceresults').fadeIn(200);
                    
                                jQuery("#btn_search_portintelligence_id").val("SEARCH");	
                                jQuery("#btn_search_portintelligence_id")[0].disabled = false;
                                
                                jQuery('#pleasewait_portintelligence').hide();
								
								jQuery("#searchby1").attr("disabled", false);
								jQuery("#searchby2").attr("disabled", false);
								jQuery("#searchby3").attr("disabled", false);
								jQuery("#searchby4").attr("disabled", false);
								jQuery("#searchby5").attr("disabled", false);
								jQuery("#searchby6").attr("disabled", false);
								jQuery("#searchby7").attr("disabled", false);
								jQuery("#searchby8").attr("disabled", false);
								jQuery("#searchby9").attr("disabled", false);
                                
                                jQuery('#btn_cancelsearch_portintelligence_id').hide();
                            }
                        });
                    }
                    </script>
                    <form id='portintelligence_form' onsubmit="portIntelligenceSubmit(); return false;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" id="parameters_table8" style='display:none; margin-bottom:5px;'>
                      <tr>
                        <td>PORT NAME: <input id='portname_id' type="text" name="portname" class="text" style='width:200px;' /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>OR</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; COUNTRY NAME: <input id='countryname_id' type="text" name="countryname" class="text" style='width:200px;' /><!--  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; REGION: <input id='regionname_id' type="text" name="regionname" class="text" style='width:200px;' />--></td>
                      </tr>
                      <tr>
                        <td style="padding:30px 0px;" align="center" colspan="2"><input class='cancelbutton' type="button" id='btn_cancelsearch_portintelligence_id' name="btn_cancelsearch_portintelligence" value="CANCEL SEARCH"  style='cursor:pointer; display:none;'  /> &nbsp;&nbsp;&nbsp; <input class='searchbutton' type="button" id='btn_search_portintelligence_id' name="btn_search_portintelligence" value="SEARCH" style='cursor:pointer;' onclick='portIntelligenceSubmit();'  /></td>
                      </tr>
                      <tr>
                        <td colspan="2">
                            <div id='pleasewait_portintelligence' style='display:none; text-align:center'>
                                <center>
                                <table>
                                    <tr>
                                        <td style='text-align:center'><img src='images/searching.gif' ></td>
                                    </tr>
                                </table>
                                </center>
                            </div>
                        </td>
                      </tr>
                      <tr>
                        <td colspan="2">
                        	<div id='portintelligenceresults'>
                                <div id='portintelligence_tab_wrapperonly'></div>
                            </div>
                        </td>
                      </tr>
                    </table>
                    </form>
                    
                    <script type="text/javascript">
					jQuery("#portname_id").focus().autocomplete(wpi_ports);
					jQuery("#portname_id").setOptions({
						scrollHeight: 180
					});
					
					jQuery("#countryname_id").focus().autocomplete(wpi_countries);
					jQuery("#countryname_id").setOptions({
						scrollHeight: 180
					});
					
					/*jQuery("#regionname_id").focus().autocomplete(wpi_regions);
					jQuery("#regionname_id").setOptions({
						scrollHeight: 180
					});*/
					
					$("#btn_cancelsearch_portintelligence_id").click(function(){
						jQuery("#btn_cancelsearch_portintelligence_id").val("CANCELING SEARCH...");
						jQuery("#btn_search_portintelligence_id").hide();
						location.reload();
					});
					</script>
                    <!--END OF PORT INTELLIGENCE-->
                    
                    <!--BUNKER PRICE-->
                    <script>
					jQuery("#mapdialogbunkerprice" ).dialog( { width: '100%', height: jQuery(window).height()*0.9 });
					jQuery("#mapdialogbunkerprice").dialog("close");
					
					function showMapBP(){
						jQuery('#pleasewait_bunkerprice').show();
	
						jQuery.ajax({
							type: 'GET',
							url: "search_ajax_bunkerprice.php",
							data:  jQuery("#bunkerprice_form").serialize(),
	
							success: function(data) {
								jQuery("#mapiframebunkerprice")[0].src='map/index12.php';
								jQuery("#mapdialogbunkerprice").dialog("open");
								
								jQuery('#pleasewait_bunkerprice').hide();
							}
						});
					}
					
                    function bunkerPriceSubmit(){
                        jQuery('#bunkerpriceresults').hide();
                    
                        jQuery('#pleasewait_bunkerprice').show();
						
						jQuery("#searchby1").attr("disabled", true);
						jQuery("#searchby2").attr("disabled", true);
						jQuery("#searchby3").attr("disabled", true);
						jQuery("#searchby4").attr("disabled", true);
						jQuery("#searchby5").attr("disabled", true);
						jQuery("#searchby6").attr("disabled", true);
						jQuery("#searchby7").attr("disabled", true);
						jQuery("#searchby8").attr("disabled", true);
						jQuery("#searchby9").attr("disabled", true);
                    
                        jQuery("#btn_search_bunkerprice_id").val("SEARCHING...");
                        jQuery("#btn_search_bunkerprice_id")[0].disabled = true;
                        
                        jQuery('#btn_cancelsearch_bunkerprice_id').show();
                    
                        jQuery.ajax({
                            type: 'GET',
                            url: "search_ajax_bunkerprice.php",
                            data:  jQuery("#bunkerprice_form").serialize(),
                    
                            success: function(data) {
                                jQuery("#bunkerprice_tab_wrapperonly").html(data);
                                jQuery('#bunkerpriceresults').fadeIn(200);
                    
                                jQuery("#btn_search_bunkerprice_id").val("SEARCH");	
                                jQuery("#btn_search_bunkerprice_id")[0].disabled = false;
                                
                                jQuery('#pleasewait_bunkerprice').hide();
								
								jQuery("#searchby1").attr("disabled", false);
								jQuery("#searchby2").attr("disabled", false);
								jQuery("#searchby3").attr("disabled", false);
								jQuery("#searchby4").attr("disabled", false);
								jQuery("#searchby5").attr("disabled", false);
								jQuery("#searchby6").attr("disabled", false);
								jQuery("#searchby7").attr("disabled", false);
								jQuery("#searchby8").attr("disabled", false);
								jQuery("#searchby9").attr("disabled", false);
                                
                                jQuery('#btn_cancelsearch_bunkerprice_id').hide();
                            }
                        });
                    }
                    </script>
                    <form id='bunkerprice_form' onsubmit="bunkerPriceSubmit(); return false;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" id="parameters_table9" style='display:none; margin-bottom:5px;'>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td>PORT NAME: <input id='bunkerportname_id' type="text" name="bunkerportname" class="text" style='width:200px;' /></td>
                      </tr>
                      <tr>
                        <td style="padding:30px 0px;" align="center" colspan="2"><input class='cancelbutton' type="button" id='btn_cancelsearch_bunkerprice_id' name="btn_cancelsearch_bunkerprice" value="CANCEL SEARCH"  style='cursor:pointer; display:none;'  /> &nbsp;&nbsp;&nbsp; <input class='searchbutton' type="button" id='btn_search_bunkerprice_id' name="btn_search_bunkerprice" value="SEARCH" style='cursor:pointer;' onclick='bunkerPriceSubmit();'  /></td>
                      </tr>
                      <tr>
                        <td colspan="2">
                            <div id='pleasewait_bunkerprice' style='display:none; text-align:center'>
                                <center>
                                <table>
                                    <tr>
                                        <td style='text-align:center'><img src='images/searching.gif' ></td>
                                    </tr>
                                </table>
                                </center>
                            </div>
                        </td>
                      </tr>
                      <tr>
                        <td colspan="2">
                        	<div id='bunkerpriceresults'>
                                <div id='bunkerprice_tab_wrapperonly'></div>
                            </div>
                        </td>
                      </tr>
                    </table>
                    </form>
                    
                    <script type="text/javascript">
					jQuery("#bunkerportname_id").focus().autocomplete(ports);
					jQuery("#bunkerportname_id").setOptions({
						scrollHeight: 180
					});
					
					$("#btn_cancelsearch_bunkerprice_id").click(function(){
						jQuery("#btn_cancelsearch_bunkerprice_id").val("CANCELING SEARCH...");
						jQuery("#btn_search_bunkerprice_id").hide();
						location.reload();
					});
					</script>
                    <!--END OF BUNKER PRICE-->
                    
                </div>
            </div>
        </td>
    </tr>
</table>
</div>