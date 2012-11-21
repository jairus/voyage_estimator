<!--LIVE SHIP POSITION-->
<link rel="stylesheet" href="js/development-bundle/themes/base/jquery.ui.all.css">
<script type="text/javascript" src="js/development-bundle/ui/jquery.ui.core.js"></script>
<script type="text/javascript" src="js/development-bundle/ui/jquery.ui.widget.js"></script>
<script type="text/javascript" src="js/development-bundle/ui/jquery.ui.dialog.js"></script>
<script>
function viewLiveShipPosition(){
    jQuery('#liveshippositionresults').hide();

    jQuery('#pleasewait').show();

    jQuery.ajax({
        type: 'GET',
        url: "search_ajax7ve.php",
        data:  jQuery("#live_ship_position").serialize(),

        success: function(data) {
            jQuery("#liveshipposition_records_tab_wrapperonly").html(data);
            jQuery('#liveshippositionresults').fadeIn(200);
            
            jQuery('#pleasewait').hide();
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
<table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td align="center">
            <table>
                <tr>
                    <td style="border-bottom:none;">
                        <div style='padding:3px;'>
                            <table width="990">
                                <tr>
                                    <td width="80"><h2><a style="cursor:pointer;" onclick="toggleCategories();">CATEGORIES</a></h2></td>
                                    <td><a style="cursor:pointer;" onclick="toggleCategories();"><img src='images/up.png' width="15" height="15" id='paramicon1' /></a></td>
                                </tr>
                            </table>
                            <table id="live_ship_positions_categories" width="990">
                                <tr>
                                    <td colspan="8" style="border-bottom:none;">
                                        <table width="990">
                                            <tr style="padding-top:25px; font-family:Arial, Helvetica, sans-serif; font-size:10px;">
                                                <td valign="top"><div style='padding:3px; text-align:right;'>&laquo; 90 DAYS</div></td>
                                                <td valign="top"><div style='padding:2px 5px 5px 0px;'><input type="checkbox" id="pos_daterange_id" name="pos_daterange[]" onclick="viewLiveShipPosition();" value="bd90" /></div></td>
                                                <td valign="top"><div style='padding:3px; text-align:right;'>&laquo; 60 DAYS</div></td>
                                                <td valign="top"><div style='padding:2px 5px 5px 0px;'><input type="checkbox" id="pos_daterange_id" name="pos_daterange[]" onclick="viewLiveShipPosition();" value="bd60" /></div></td>
                                                <td valign="top"><div style='padding:3px; text-align:right;'>&laquo; 30 DAYS</div></td>
                                                <td valign="top"><div style='padding:2px 5px 5px 0px;'><input type="checkbox" id="pos_daterange_id" name="pos_daterange[]" onclick="viewLiveShipPosition();" value="bd30" /></div></td>
                                                <td valign="top"><div style='padding:3px; color:#F00; text-align:right;'>TODAY &raquo;</div></td>
                                                <td valign="top"><div style='padding:2px 5px 5px 0px;'><input type="checkbox" id="pos_daterange_id" name="pos_daterange[]" onclick="viewLiveShipPosition();" value="t" /></div></td>
                                                <td valign="top"><div style='padding:3px; text-align:right;'>1 DAY &raquo;</div></td>
                                                <td valign="top"><div style='padding:2px 5px 5px 0px;'><input type="checkbox" id="pos_daterange_id" name="pos_daterange[]" onclick="viewLiveShipPosition();" value="fd1" /></div></td>
                                                <td valign="top"><div style='padding:3px; text-align:right;'>7 DAYS &raquo;</div></td>
                                                <td valign="top"><div style='padding:2px 5px 5px 0px;'><input type="checkbox" id="pos_daterange_id" name="pos_daterange[]" onclick="viewLiveShipPosition();" value="fd7" /></div></td>
                                                <td valign="top"><div style='padding:3px; text-align:right;'>30 DAYS &raquo;</div></td>
                                                <td valign="top"><div style='padding:2px 5px 5px 0px;'><input type="checkbox" id="pos_daterange_id" name="pos_daterange[]" onclick="viewLiveShipPosition();" value="fd30" /></div></td>
                                                <td valign="top"><div style='padding:3px; text-align:right;'>60 DAYS &raquo;</div></td>
                                                <td valign="top"><div style='padding:2px 5px 5px 0px;'><input type="checkbox" id="pos_daterange_id" name="pos_daterange[]" onclick="viewLiveShipPosition();" value="fd60" /></div></td>
                                                <td valign="top"><div style='padding:3px; text-align:right;'>90 DAYS &raquo;</div></td>
                                                <td valign="top"><div style='padding:2px 5px 5px 0px;'><input type="checkbox" id="pos_daterange_id" name="pos_daterange[]" onclick="viewLiveShipPosition();" value="fd90" /></div></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top" colspan="8" style="border-bottom:none; font-weight:bold;"><div style='padding:3px 0px 3px 3px;'>BULK CARRIER</div></td>
                                </tr>
                                <tr>
                                    <td valign="top" width="20"><div style='padding:3px 0px 3px 3px;'><input type="checkbox" id="pos_vessel_type_id" name="pos_vessel_type[]" onclick="viewLiveShipPosition();" value="BULK CARRIER" /></div></td>
                                    <td valign="top" width="228"><div style='padding:3px;'>BULK CARRIER</div></td>
                                    <td valign="top" width="20"><div style='padding:3px 0px 3px 3px;'><input type="checkbox" id="pos_vessel_type_id" name="pos_vessel_type[]" onclick="viewLiveShipPosition();" value="ORE CARRIER" /></div></td>
                                    <td valign="top" width="227"><div style='padding:3px;'>ORE CARRIER</div></td>
                                    <td valign="top" width="20"><div style='padding:3px 0px 3px 3px;'><input type="checkbox" id="pos_vessel_type_id" name="pos_vessel_type[]" onclick="viewLiveShipPosition();" value="WOOD CHIPS CARRIER" /></div></td>
                                    <td valign="top" width="227"><div style='padding:3px;'>WOOD CHIPS CARRIER</div></td>
                                    <td valign="top" width="20"><div style='padding:3px 0px 3px 3px;'>&nbsp;</div></td>
                                    <td valign="top" width="228"><div style='padding:3px;'>&nbsp;</div></td>
                                </tr>
                                <tr>
                                    <td valign="top" colspan="8" style="border-bottom:none; font-weight:bold;"><div style='padding:3px 0px 3px 3px;'>CARGO</div></td>
                                </tr>
                                <tr>
                                    <td style="border-bottom:none;" valign="top" width="20"><div style='padding:3px 0px 3px 3px;'><input type="checkbox" id="pos_vessel_type_id" name="pos_vessel_type[]" onclick="viewLiveShipPosition();" value="BARGE CARRIER" /></div></td>
                                    <td style="border-bottom:none;" valign="top" width="228"><div style='padding:3px;'>BARGE CARRIER</div></td>
                                    <td style="border-bottom:none;" valign="top" width="20"><div style='padding:3px 0px 3px 3px;'><input type="checkbox" id="pos_vessel_type_id" name="pos_vessel_type[]" onclick="viewLiveShipPosition();" value="CARGO" /></div></td>
                                    <td style="border-bottom:none;" valign="top" width="227"><div style='padding:3px;'>CARGO</div></td>
                                    <td style="border-bottom:none;" valign="top" width="20"><div style='padding:3px 0px 3px 3px;'><input type="checkbox" id="pos_vessel_type_id" name="pos_vessel_type[]" onclick="viewLiveShipPosition();" value="CARGO/PASSENGER SHIP" /></div></td>
                                    <td style="border-bottom:none;" valign="top" width="227"><div style='padding:3px;'>CARGO/PASSENGER SHIP</div></td>
                                    <td style="border-bottom:none;" valign="top" width="20"><div style='padding:3px 0px 3px 3px;'><input type="checkbox" id="pos_vessel_type_id" name="pos_vessel_type[]" onclick="viewLiveShipPosition();" value="HEAVY LOAD CARRIER" /></div></td>
                                    <td style="border-bottom:none;" valign="top" width="228"><div style='padding:3px;'>HEAVY LOAD CARRIER</div></td>
                                </tr>
                                <tr>
                                    <td valign="top" width="20"><div style='padding:3px 0px 3px 3px;'><input type="checkbox" id="pos_vessel_type_id" name="pos_vessel_type[]" onclick="viewLiveShipPosition();" value="LIVESTOCK CARRIER" /></div></td>
                                    <td valign="top" width="228"><div style='padding:3px;'>LIVESTOCK CARRIER</div></td>
                                    <td valign="top" width="20"><div style='padding:3px 0px 3px 3px;'><input type="checkbox" id="pos_vessel_type_id" name="pos_vessel_type[]" onclick="viewLiveShipPosition();" value="MOTOR HOPPER" /></div></td>
                                    <td valign="top" width="227"><div style='padding:3px;'>MOTOR HOPPER</div></td>
                                    <td valign="top" width="20"><div style='padding:3px 0px 3px 3px;'><input type="checkbox" id="pos_vessel_type_id" name="pos_vessel_type[]" onclick="viewLiveShipPosition();" value="NUCLEAR FUEL CARRIER" /></div></td>
                                    <td valign="top" width="227"><div style='padding:3px;'>NUCLEAR FUEL CARRIER</div></td>
                                    <td valign="top" width="20"><div style='padding:3px 0px 3px 3px;'><input type="checkbox" id="pos_vessel_type_id" name="pos_vessel_type[]" onclick="viewLiveShipPosition();" value="SLUDGE CARRIER" /></div></td>
                                    <td valign="top" width="228"><div style='padding:3px;'>SLUDGE CARRIER</div></td>
                                </tr>
                                <tr>
                                    <td valign="top" colspan="8" style="border-bottom:none; font-weight:bold;"><div style='padding:3px 0px 3px 3px;'>CEMENT CARRIER</div></td>
                                </tr>
                                <tr>
                                    <td valign="top" width="20"><div style='padding:3px 0px 3px 3px;'><input type="checkbox" id="pos_vessel_type_id" name="pos_vessel_type[]" onclick="viewLiveShipPosition();" value="CEMENT CARRIER" /></div></td>
                                    <td valign="top" width="228"><div style='padding:3px;'>CEMENT CARRIER</div></td>
                                    <td valign="top" width="20"><div style='padding:3px 0px 3px 3px;'>&nbsp;</div></td>
                                    <td valign="top" width="227"><div style='padding:3px;'>&nbsp;</div></td>
                                    <td valign="top" width="20"><div style='padding:3px 0px 3px 3px;'>&nbsp;</div></td>
                                    <td valign="top" width="227"><div style='padding:3px;'>&nbsp;</div></td>
                                    <td valign="top" width="20"><div style='padding:3px 0px 3px 3px;'>&nbsp;</div></td>
                                    <td valign="top" width="228"><div style='padding:3px;'>&nbsp;</div></td>
                                </tr>
                                <tr>
                                    <td valign="top" colspan="8" style="border-bottom:none; font-weight:bold;"><div style='padding:3px 0px 3px 3px;'>OBO CARRIER</div></td>
                                </tr>
                                <tr>
                                    <td valign="top" width="20"><div style='padding:3px 0px 3px 3px;'><input type="checkbox" id="pos_vessel_type_id" name="pos_vessel_type[]" onclick="viewLiveShipPosition();" value="OBO CARRIER" /></div></td>
                                    <td valign="top" width="228"><div style='padding:3px;'>OBO CARRIER</div></td>
                                    <td valign="top" width="20"><div style='padding:3px 0px 3px 3px;'>&nbsp;</div></td>
                                    <td valign="top" width="227"><div style='padding:3px;'>&nbsp;</div></td>
                                    <td valign="top" width="20"><div style='padding:3px 0px 3px 3px;'>&nbsp;</div></td>
                                    <td valign="top" width="227"><div style='padding:3px;'>&nbsp;</div></td>
                                    <td valign="top" width="20"><div style='padding:3px 0px 3px 3px;'>&nbsp;</div></td>
                                    <td valign="top" width="228"><div style='padding:3px;'>&nbsp;</div></td>
                                </tr>
                                <tr>
                                    <td valign="top" colspan="8" style="border-bottom:none; font-weight:bold;"><div style='padding:3px 0px 3px 3px;'>RO-RO CARGO</div></td>
                                </tr>
                                <tr>
                                    <td valign="top" width="20"><div style='padding:3px 0px 3px 3px;'><input type="checkbox" id="pos_vessel_type_id" name="pos_vessel_type[]" onclick="viewLiveShipPosition();" value="RO-RO CARGO" /></div></td>
                                    <td valign="top" width="228"><div style='padding:3px;'>RO-RO CARGO</div></td>
                                    <td valign="top" width="20"><div style='padding:3px 0px 3px 3px;'><input type="checkbox" id="pos_vessel_type_id" name="pos_vessel_type[]" onclick="viewLiveShipPosition();" value="RO-RO/CONTAINER CARRIER" /></div></td>
                                    <td valign="top" width="227"><div style='padding:3px;'>RO-RO/CONTAINER CARRIER</div></td>
                                    <td valign="top" width="20"><div style='padding:3px 0px 3px 3px;'><input type="checkbox" id="pos_vessel_type_id" name="pos_vessel_type[]" onclick="viewLiveShipPosition();" value="RO-RO/PASSENGER SHIP" /></div></td>
                                    <td valign="top" width="227"><div style='padding:3px;'>RO-RO/PASSENGER SHIP</div></td>
                                    <td valign="top" width="20"><div style='padding:3px 0px 3px 3px;'>&nbsp;</div></td>
                                    <td valign="top" width="228"><div style='padding:3px;'>&nbsp;</div></td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
                
                <div id="mapdialog1" title="MAP" style='display:none;'>
                    <iframe id="mapiframe1" name='mapname' frameborder="0" height="100%" width="100%" style='border:0px; height:100%; width:100%'></iframe>
                </div>
                
                <script type="text/javascript">
                jQuery("#mapdialog1" ).dialog( { autoOpen: false, width: '100%', height: jQuery(window).height()*0.9 });
                jQuery("#mapdialog1").dialog("close");
                
                function showMap(){
                    jQuery('#pleasewait').show();

                    jQuery.ajax({
                        type: 'GET',
                        url: "search_ajax7ve.php",
                        data:  jQuery("#live_ship_position").serialize(),

                        success: function(data) {
                            jQuery("#mapiframe1")[0].src='map/index10_online.php';
                            jQuery("#mapdialog1").dialog("open");
                            
                            jQuery('#pleasewait').hide();
                        }
                    });
                }
                </script>
                
                <tr>
                    <td style="padding-top:3px; border-bottom:none;">
                        <div id='liveshippositionresults'>
                            <div id='liveshipposition_records_tab_wrapperonly'></div>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</form>
<!--END OF LIVE SHIP POSITION-->