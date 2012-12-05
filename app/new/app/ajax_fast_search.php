<!--FAST SEARCH-->
<?php
include_once(dirname(__FILE__)."/includes/bootstrap.php");

global $user;

$tab     = $tabsys->getTab("shipsearch", $_GET['tab']);
$tabid   = $tab['id'];
$tabdata = unserialize($tab['tabdata']);
?>

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

	jQuery.ajax({
		type: 'GET',
		url: "search_ajax1ve.php",
		data: jQuery("#searchform").serialize(),

		success: function(data) {
			jQuery("#sbutton").val("SEARCH");
			jQuery("#sbutton")[0].disabled = false;

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

function newSearchParam(){
	jQuery('#pleasewait').show();
	
	jQuery.ajax({
		type: "POST",
		url: "ajax.php?new_search=1",
		data: "",

		success: function(data) {
			self.location = "cargospotter.php?new_search=1";
		}
	});
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

<div style="width:1200px; height:auto; margin:0 auto;">
<form id='mapformid' target='mapname' method="post">
	<input type='hidden' name='details' id='detailsid' />
</form>

<table width="1200" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200">
        <div style="padding-bottom:10px;">
        <form method="post" action="">
          <input type='hidden' name='newtab' value='shipsearch'>
          <input value="+ new search" class="form-button" type="button" onclick="newSearchParam();" />
        </form>
        </div>
        <div>
            <ul class="menu sbis-tabmenu">
                <?php $tabsys->showTabs("shipsearch",'',true); ?>
            </ul>
        </div>
    </td>
    <td width="1000" align="left">
    	<div style='cursor:pointer;' onclick="jQuery('#searchform').slideToggle('slow', function(){ toggleParams(); })"><a name='params' style='font-size:18px; font-weight:bold;'>PARAMETERS</a> &nbsp; <img src='images/up.png' id='paramicon'></div>
        <div>&nbsp;</div>
    	<form id='searchform'>
            <input type='hidden' name='dry' value='1' >
            <input type='hidden' name='tabid' value='<?php echo $tabid; ?>' >
            
            <table width="1000" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td width="400" valign="top">
                  <table width="400" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td width="90">LOAD PORT</td>
                      <td width="10">&nbsp;</td>
                      <td>
                      	<input id='suggest1' type="text" name="load_port" value='<?php echo $tabdata['load_port']; ?>' class="input_1" style='width:210px;' />
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
                                url: "search_ajax1ve.php?dry=1&load_port="+lp+"&action=getzones&dwt_range="+dwt,
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
                      <td height="5" colspan="3"></td>
                    </tr>
                    <tr>
                      <td>LAYCAN</td>
                      <td width="10">&nbsp;</td>
                      <td>
                        <input type="text" name="load_port_from" value="<?php
                        if(!trim($tabdata['load_port_from'])){
                            echo date("M d, Y", time());
                        }else{
                            echo $tabdata['load_port_from'];
                        }
                        ?>" readonly="readonly" onclick="showCalendar('',this,null,'','',0,5,1)" class="input_1" style="width:90px;" />
            
                        to 
            
                        <input type="text" name="load_port_to" value="<?php
                        if(!trim($tabdata['load_port_from'])){
                            echo date("M d, Y", time()+(7*24*60*60));
                        }else{ 
                            echo $tabdata['load_port_to'];
                        }
                        ?>" readonly="readonly" onclick="showCalendar('',this,null,'','',0,5,1)" class="input_1" style="width:90px;" />
                      </td>
                    </tr>
                    <tr>
                      <td height="5" colspan="3"></td>
                    </tr>
                    <tr>
                      <td valign="top">DRY VESSELS</td>
                      <td width="10">&nbsp;</td>
                      <td>
                      	<select name="vessel_type[]" multiple="multiple" size="18" id='vessel_type_id' class="input_1" style="width:220px;">
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
                <td width="600" valign="top">
                  <table width="600" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td width="90">DWT RANGE</td>
                      <td width="10">&nbsp;</td>
                      <td>
                      	<select class="input_1" name="dwt_range" id='dwt_range_id' onchange='showZones(jQuery("#suggest1").val(), this.value)'>
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
                      <td height="5" colspan="3"></td>
                    </tr>
                    <tr>
                      <td>ZONE</td>
                      <td width="10">&nbsp;</td>
                      <td><div id='zones'></div></td>
                    </tr>
                    <tr>
                      <td height="3" colspan="3"></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td width="10">&nbsp;</td>
                      <td>
                      	<div id='minimaps'>
                        	<img id='minimap' style='cursor:pointer; display:none' onclick="openZoneMap(this.alt)" alt='<?php echo $tabdata['zone']; ?>'  src='map/minimaps/<?php echo $tabdata['zone']; ?>.jpg' width="440">
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
                	CHOOSE THE NUMBER OF SHIPS YOU WANT TO SEARCH FOR&nbsp;
                    <select name="slimit" style='width:70px;' class="input_1">
                        <option value="">ALL</option>
                        <option value="5" selected="selected">5</option>
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="500">500</option>
                    </select>
                </td>
              </tr>
              <tr>
                <td height="10" colspan="2"></td>
              </tr>
              <tr>
                <td colspan="2" align="center">
                	<table border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td>
                          <table border="0" cellpadding="0" cellspacing="0">
                            <?php if($tabdata['sshore']){ ?>
                            	<tr>
                                  <td width="20"><input type='checkbox' id='sshore1check' name='sshore' value='1' checked="checked" style='height:30px; width:30px' onclick="changeCssClass('sshore1div')"></td>
                                  <td><div id='sshore1div' class="divclass_active" style='font-family:Arial, Helvetica, sans-serif; font-size:11px; font-weight:bold;'>AIS SHOREsearch</div></td>
                                </tr>
                            <?php }else if(trim($tabdata['load_port'])){ ?>
                            	<tr>
                                  <td width="20"><input type='checkbox' id='sshore1check' name='sshore' value='1' style='height:30px; width:30px' onclick="changeCssClass('sshore1div')"></td>
                                  <td><div id="sshore1div" class="divclass" style='font-family:Arial, Helvetica, sans-serif; font-size:11px; font-weight:bold;'>AIS SHOREsearch</div></td>
                                </tr>
                            <?php }else{ ?>
                            	<tr>
                                  <td width="20"><input type='checkbox' id='sshore1check' name='sshore' value='1' checked="checked" style='height:30px; width:30px' onclick="changeCssClass('sshore1div')"></td>
                                  <td><div id='sshore1div' class="divclass_active" style='font-family:Arial, Helvetica, sans-serif; font-size:11px; font-weight:bold;'>AIS SHOREsearch</div></td>
                                </tr>
                            <?php } ?>
                            <tr>
                              <td width="20">&nbsp;</td>
                              <td><a onclick='showLearnDialog("aisshore");' class="clickable" style="font-size:10px;">CLICK TO LEARN MORE</a></td>
                            </tr>
                          </table>
                        </td>
                        <td width="50">&nbsp;</td>
                        <td>
                          <table border="0" cellpadding="0" cellspacing="0">
                           <?php if($tabdata['sbroker']){ ?>
                            	<tr>
                                  <td width="20"><input type='checkbox' name='sbroker' value='1' checked="checked" style='height:30px; width:30px' onclick="changeCssClass('sbroker1')"></td>
                                  <td><div id="sbroker1" class="divclass_active" style='font-family:Arial, Helvetica, sans-serif; font-size:11px; font-weight:bold;'>BROKERSintelligence</div></td>
                                </tr>
                            <?php }else if(trim($tabdata['load_port'])){ ?>
                            	<tr>
                                  <td width="20"><input type='checkbox' name='sbroker' value='1' style='height:30px; width:30px' onclick="changeCssClass('sbroker1')"></td>
                                  <td><div id="sbroker1" class="divclass" style='font-family:Arial, Helvetica, sans-serif; font-size:11px; font-weight:bold;'>BROKERSintelligence</div></td>
                                </tr>
                            <?php }else{ ?>
                            	<tr>
                                  <td width="20"><input type='checkbox' name='sbroker' value='1' checked="checked" style='height:30px; width:30px' onclick="changeCssClass('sbroker1')"></td>
                                  <td><div id="sbroker1" class="divclass_active" style='font-family:Arial, Helvetica, sans-serif; font-size:11px; font-weight:bold;'>BROKERSintelligence</div></td>
                                </tr>
                            <?php } ?>
                            <tr>
                              <td width="20">&nbsp;</td>
                              <td><a onclick='showLearnDialog("brokersintelligence");' class="clickable" style="font-size:10px;">CLICK TO LEARN MORE</a></td>
                            </tr>
                          </table>
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
                            shipSearchx();
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
</div>
<!--END OF FAST SEARCH-->