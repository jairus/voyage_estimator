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
	
	jQuery('#cancelsearch').show();

	jQuery.ajax({
		type: 'GET',
		url: "search_ajax.php",
		data: jQuery("#searchform").serialize(),

		success: function(data) {
			jQuery("#sbutton")[0].disabled = false;
			
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
		url: "search_ajax.php?imo="+imo,
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

	jQuery("#contactiframe")[0].src='search_ajax.php?contact=1&owner='+owner+'&owner_id='+owner_id;
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

jQuery( "#didyouknowdialog" ).dialog( { autoOpen: false, width: 700, height: 600 });
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
</script>

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

<center>
<form id='mapformid' target='mapname' method="post">
	<input type='hidden' name='details' id='detailsid' />
</form>

<form id='searchform'>
<input type='hidden' name='dry' value='1' >
<input type='hidden' name='tabid' value='<?php echo $tabid; ?>' >
<table width="1000" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td valign="top" width="400">
            <table width="400" border="0" cellpadding="3" cellspacing="3">
                <tr>
                    <td valign="top" class="title">LOAD PORT</td>
                    <td valign="top">
                        <input id='suggest1' type="text" name="load_port" value='<?php echo $tabdata['load_port']; ?>' class="input_1" style='width:200px;' />
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
                    <td valign="top" class="title">HULL TYPE</td>
                    <td valign="top">
                        <select name="hull_type" class="input_1" id='hull_type_id' style="width:200px;">
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
                        <select name="vessel_type[]" multiple="multiple" size="16" id='vessel_type_id' class="input_1" style="width:200px;">
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
            <table width="600" border="0" cellpadding="3" cellspacing="3">
                <tr>
                    <td valign="top" class="title">DWT RANGE</td>
                    <td valign="top">
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
        <td colspan='2'>
            <div id='pleasewait_fastsearch' style='display:none; text-align:center;'>
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
</center>
<!--END OF FAST SEARCH-->