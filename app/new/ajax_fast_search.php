<!--FAST SEARCH-->
<script type='text/javascript' src='../js/jquery-autocomplete/lib/jquery.bgiframe.min.js'></script>
<script type='text/javascript' src='../js/jquery-autocomplete/lib/jquery.ajaxQueue.js'></script>
<script type='text/javascript' src='../js/jquery-autocomplete/lib/thickbox-compressed.js'></script>
<script type='text/javascript' src='../js/jquery-autocomplete/jquery.autocomplete.js'></script>
<script type='text/javascript' src='../js/ports.php'></script>
<link rel="stylesheet" type="text/css" href="../js/jquery-autocomplete/jquery.autocomplete.css" />
<link rel="stylesheet" type="text/css" href="../js/jquery-autocomplete/lib/thickbox.css" />

<script type="text/javascript" src="../js/calendar/xc2_default.js"></script>
<script type="text/javascript" src="../js/calendar/xc2_inpage.js"></script>
<link type="text/css" rel="stylesheet" href="../js/calendar/xc2_default.css" />

<form id='searchform'>
<input type='hidden' name='dry' value='1' >
<table width="1000" border="0" cellpadding="0" cellspacing="0">
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