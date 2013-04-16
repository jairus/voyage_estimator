<?php
include_once(dirname(__FILE__)."/../bootstrap.php");

$link = dbConnect();

function openMessage($mid){
	global $link;

	$mid = mysql_escape_string($mid);
	
	$sql = "select * from `_message_opened` where `message_id`='".$mid."' and `user_id`='".$_SESSION['user']['id']."'";
	$r = dbQuery($sql, $link);

	if(!$r[0]){
		$sql = "insert into `_message_opened` (`message_id`, `user_id`) values ('".$mid."', '".$_SESSION['user']['id']."')";
		$r = dbQuery($sql, $link);
	}
}

$messages = getMessages($_GET['imo'], $_GET['type']);
$t = count($messages);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Ship Search | S-BIS Portal</title>
<script type='text/javascript' src='js/jquery-ui-1.8.4.custom/js/jquery-1.4.2.min.js'></script>

<!-- start Calendar -->
<script type="text/javascript" src="js/calendar/xc2_default.js"></script>
<script type="text/javascript" src="js/calendar/xc2_inpage.js"></script>
<link type="text/css" rel="stylesheet" href="js/calendar/xc2_default.css" />
<!-- end Calendar -->

<!-- auto complete -->
<script type='text/javascript' src='js/jquery-autocomplete/lib/jquery.bgiframe.min.js'></script>
<script type='text/javascript' src='js/jquery-autocomplete/lib/jquery.ajaxQueue.js'></script>
<script type='text/javascript' src='js/jquery-autocomplete/lib/thickbox-compressed.js'></script>
<script type='text/javascript' src='js/jquery-autocomplete/jquery.autocomplete.js'></script>
<script type='text/javascript' src='js/ports.php'></script>
<link rel="stylesheet" type="text/css" href="js/jquery-autocomplete/jquery.autocomplete.css" />
<link rel="stylesheet" type="text/css" href="js/jquery-autocomplete/lib/thickbox.css" />
<script>
var type = "<?php echo $_GET['type']; ?>";
var imo  = "<?php echo $_GET['imo']; ?>";

function addMessage(){
	qs = jQuery("#messageform").serialize()+"&type="+type+"&imo="+imo;
	jQuery("#messageform *").attr("disabled", true);
	jQuery.ajax({
	  type: 'POST',
	  url: "search_ajax.php?action=getmessages&task=addmessage",
	  data: qs,
	  dataType: "json",
	  success: function(data) {

	  	if(data.message){
			html = "";
			html += "<table width='850px' id='message"+data.id+"' style='display:none'>";
			html += "<tr>";
			html += "<td style='width:450px'>";
			html += data.message;
			html += "</td>";
			html += "<td style='width: 200px; text-align:right'>";
			html += data.user_email;
			html += "</td>";
			html += "<td style='width: 200px; text-align:right'>";
			html += data.dateadded;
			html += "</td>";
			html += "</tr>";
			html += "</table>";
			htmlold = jQuery("#messages").html();
			html += htmlold; 
			jQuery("#messages").html(html);
			jQuery("#message"+data.id).css({width:'100%'});
			jQuery("#message"+data.id).slideDown(200);
		}else if(data.messagearr.openpport||data.messagearr.opendate||data.messagearr.remark){
			html = "";
			html += "<table width='870' style='border:1px solid #000; display:none;' id='message"+data.id+"'>";
				html += "<tr>";
					html += "<td>";
						html += "<table width='870'>";
							html += "<tr style='background:#FEDFD8'>";
								html += "<td colspan='2'><b>By:</b> "+data.user_email+"</td>";
								html += "<td colspan='3'><b>Date Added:</b> "+data.dateadded+"</td>";
							html += "</tr>";
							html += "<tr style='background:#FEDFD8'>";
								html += "<td width='170'>"+data.messagearr.openport+"&nbsp;</td>";
								html += "<td width='110'>"+data.messagearr.opendate+"&nbsp;</td>";
								html += "<td width='110'>"+data.messagearr.destinationregion+"&nbsp;</td>";
								html += "<td width='90'>"+data.messagearr.destinationdate+"&nbsp;</td>";
								html += "<td width='80'>"+data.messagearr.charterer+"&nbsp;</td>";
								html += "<td width='310'>"+data.messagearr.remark+"&nbsp;</td>";
							html += "</tr>";
						html += "</table>";
						html += "<table width='870'>";
							html += "<tr style='background:#FEDFD8'>";
								html += "<td width='126'>"+data.messagearr.cargotype+"&nbsp;</td>";
								html += "<td width='124'>"+data.messagearr.quantity+"&nbsp;</td>";
								html += "<td width='124'>"+data.messagearr.status+"&nbsp;</td>";
								html += "<td width='124'>"+data.messagearr.cbm+"&nbsp;</td>";
								html += "<td width='124'>"+data.messagearr.rate+"&nbsp;</td>";
								html += "<td width='124'>"+data.messagearr.tce+"&nbsp;</td>";
								html += "<td width='124'>"+data.messagearr.ws+"&nbsp;</td>";
							html += "</tr>";
						html += "</table>";
					html += "</td>";
				html += "</tr>";
			html += "</table>";
			htmlold = jQuery("#messages").html();
			html += htmlold; 
			jQuery("#messages").html(html);
			jQuery("#message"+data.id).css({width:'100%'});
			jQuery("#message"+data.id).slideDown(200);
		}
		jQuery("#nomessages").hide();
		jQuery("#messageform *").attr("disabled", false);
	  }
	});		
}

function addMessageDry(){
	qs = jQuery("#messageform").serialize()+"&type="+type+"&imo="+imo;
	jQuery("#messageform *").attr("disabled", true);
	jQuery.ajax({
	  type: 'POST',
	  url: "search_ajax.php?action=getmessages&task=addmessagedry",
	  data: qs,
	  dataType: "json",
	  success: function(data) {

	  	if(data.message){
			html = "";
			html += "<table width='850px' id='message"+data.id+"' style='display:none'>";
			html += "<tr>";
			html += "<td style='width:450px'>";
			html += data.message;
			html += "</td>";
			html += "<td style='width: 200px; text-align:right'>";
			html += data.user_email;
			html += "</td>";
			html += "<td style='width: 200px; text-align:right'>";
			html += data.dateadded;
			html += "</td>";
			html += "</tr>";
			html += "</table>";
			htmlold = jQuery("#messages").html();
			html += htmlold; 
			jQuery("#messages").html(html);
			jQuery("#message"+data.id).css({width:'100%'});
			jQuery("#message"+data.id).slideDown(200);
		}else if(data.messagearr.dely||data.messagearr.remarks){
			html = "";
			html += "<table cellpading='0' cellspacing='0' border='0' style='display:none;' id='message"+data.id+"'>";
				html += "<tr>";
					html += "<td width='208'><b>by:</b> "+data.user_email+"</td>";
					html += "<td width='207'><b>date added:</b> "+data.dateadded+"</td>";
				html += "</tr>";
				html += "<tr>";
					html += "<td height='15' colspan='2'></td>";
				html += "</tr>";
				html += "<tr>";
					html += "<td width='165'><b>Delivery</b></td>";
					html += "<td width='250'>: "+data.messagearr.dely+"&nbsp;</td>";
				html += "</tr>";
				html += "<tr>";
					html += "<td height='2' colspan='2'></td>";
				html += "</tr>";
				html += "<tr>";
					html += "<td width='165'><b>Delivery Date From</b></td>";
					html += "<td width='250'>: "+data.messagearr.delydate_from+"&nbsp;</td>";
				html += "</tr>";
				html += "<tr>";
					html += "<td height='2' colspan='2'></td>";
				html += "</tr>";
				html += "<tr>";
					html += "<td width='165'><b>Delivery Date To</b></td>";
					html += "<td width='250'>: "+data.messagearr.delydate_to+"&nbsp;</td>";
				html += "</tr>";
				html += "<tr>";
					html += "<td height='2' colspan='2'></td>";
				html += "</tr>";
				html += "<tr>";
					html += "<td width='165'><b>Re-delivery 1</b></td>";
					html += "<td width='250'>: "+data.messagearr.redely1+"&nbsp;</td>";
				html += "</tr>";
				html += "<tr>";
					html += "<td height='2' colspan='2'></td>";
				html += "</tr>";
				html += "<tr>";
					html += "<td width='165'><b>Re-delivery Date 1</b></td>";
					html += "<td width='250'>: "+data.messagearr.redelydate1+"&nbsp;</td>";
				html += "</tr>";
				html += "<tr>";
					html += "<td height='2' colspan='2'></td>";
				html += "</tr>";
				html += "<tr>";
					html += "<td width='165'><b>Rate</b></td>";
					html += "<td width='250'>: "+data.messagearr.rate+"&nbsp;</td>";
				html += "</tr>";
				html += "<tr>";
					html += "<td height='2' colspan='2'></td>";
				html += "</tr>";
				html += "<tr>";
					html += "<td width='165'><b>Charterer</b></td>";
					html += "<td width='250'>: "+data.messagearr.charterer+"&nbsp;</td>";
				html += "</tr>";
				html += "<tr>";
					html += "<td height='2' colspan='2'></td>";
				html += "</tr>";
				html += "<tr>";
					html += "<td width='165'><b>Period</b></td>";
					html += "<td width='250'>: "+data.messagearr.period+"&nbsp;</td>";
				html += "</tr>";
				html += "<tr>";
					html += "<td height='2' colspan='2'></td>";
				html += "</tr>";
				html += "<tr>";
					html += "<td width='165'><b>Dur Min</b></td>";
					html += "<td width='250'>: "+data.messagearr.dur_min+"&nbsp;</td>";
				html += "</tr>";
				html += "<tr>";
					html += "<td height='2' colspan='2'></td>";
				html += "</tr>";
				html += "<tr>";
					html += "<td width='165'><b>Dur Max</b></td>";
					html += "<td width='250'>: "+data.messagearr.dur_max+"&nbsp;</td>";
				html += "</tr>";
				html += "<tr>";
					html += "<td height='2' colspan='2'></td>";
				html += "</tr>";
				html += "<tr>";
					html += "<td width='165'><b>Relet</b></td>";
					html += "<td width='250'>: "+data.messagearr.relet+"&nbsp;</td>";
				html += "</tr>";
				html += "<tr>";
					html += "<td height='2' colspan='2'></td>";
				html += "</tr>";
				html += "<tr>";
					html += "<td width='165'><b>Remarks</b></td>";
					html += "<td width='250'>: "+data.messagearr.remarks+"&nbsp;</td>";
				html += "</tr>";
			html += "</table>";
			htmlold = jQuery("#messages").html();
			html += htmlold; 
			jQuery("#messages").html(html);
			jQuery("#message"+data.id).css({width:'100%'});
			jQuery("#message"+data.id).slideDown(200);
		}
		jQuery("#nomessages").hide();
		jQuery("#messageform *").attr("disabled", false);
	  }
	});		
}

function deleteMessage(id, obj){
	if(confirm("Are you sure you want to delete this message?")){
		obj.innerHTML = "Deleting...";
		jQuery.ajax({
		  type: 'POST',
		  url: "search_ajax.php?action=getmessages&task=deletemessage",
		  data: "messageid="+id,
		  dataType: "json",
		  
		  success: function(data) {
			jQuery("#message"+data.messageid).fadeOut(200);
		  }
		});
	}
}
</script>
<style>
#mcontainer{
	width:850px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size:11px;
}

#message{
	border: 1px solid #c0c0c0;
	width: 740px;
}

#remark{

}

#messager{
	padding-bottom: 20px;
}

#submitbutt{
	height: 50px;
	width:100px;
}

#messages td{
	vertical-align:top;
}

.clickable{
	cursor:pointer;
}

.title{
	font-weight:bold;
	font-size:14px;
	margin-bottom:20px;
}
</style>
</head>
<body>
<center>
<?php if($_SESSION['user']['dry']==1){ ?>
<table>
	<tr>
		<td id='mcontainer'>
			<?php
            if($_GET['type']=='private'){
                echo "<div class='title'>Private Mesages</div>";
            }else if($_GET['type']=='network'){
                echo "<div class='title'>Broker Updates</div>";
            }
            
            $mid = preg_replace("/[^0-9]/", "", $_GET['mid']);
            
            openMessage($mid);
            
            echo "<div id='messager'>";
            echo "<form id='messageform'>";
            
            if($_GET['type']=='private'){
            
                echo "<table cellpading=0; cellspacing=0>
                    <tr>
                        <td style='vertical-align:top'><textarea name='message' id='message'></textarea></td>
                        <td style='vertical-align:top'><input type='button' id='submitbutt' value='Submit' onclick='addMessageDry()'></td>
                    </tr>
                </table>";
				echo "<div id='messages'>";
            
				if($t){
					for($i=0; $i<$t; $i++){
						echo "<table width='850px' id='message".$messages[$i]['id']."'>
							<tr>
								<td style='width: 450px'>".stripslashes($messages[$i]['message'])."</td>
								<td style='width: 200px; text-align:right'>".$messages[$i]['user_email']."</td>
								<td style='width: 200px; text-align:right'>".date("M d, 'y", convertDateToTs($messages[$i]['dateadded']))."</td>
							</tr>
						</table>";
					}
				}else{
					echo "<div id='nomessages'>No Messages</div>";
				}
				
				echo "</div>";
            }else if($_GET['type']=='network'){
                $messagearr =  unserialize($messages[0]['message']);
            
                echo "<table cellpading='0' cellspacing='0' width='870'>
                    <tr>
                        <td width='415' style='padding-right:20px;' valign='top'>
							<table cellpading='0' cellspacing='0' border='0'>
								<tr>
									<td width='165'><b>Delivery</b></td>
									<td width='250'><input id='dely1' type='text' name='dely' value=\"".htmlentities(stripslashes($messagearr['dely']))."\" style='width:240px; border:1px solid #CCC; padding:3px;' /></td>
								</tr>
								<tr>
									<td height='2' colspan='2'></td>
								</tr>
								<tr>
									<td width='165'><b>Delivery Date From</b></td>
									<td width='250'><input type='text' name='delydate_from' value=\"".htmlentities(stripslashes($messagearr['delydate_from']))."\" style='width:120px; border:1px solid #CCC; padding:3px;' onclick=\"showCalendar('',this,null,'','',0,5,1)\" /></td>
								</tr>
								<tr>
									<td height='2' colspan='2'></td>
								</tr>
								<tr>
									<td width='165'><b>Delivery Date To</b></td>
									<td width='250'><input type='text' name='delydate_to' value=\"".htmlentities(stripslashes($messagearr['delydate_to']))."\" style='width:120px; border:1px solid #CCC; padding:3px;' onclick=\"showCalendar('',this,null,'','',0,5,1)\" /></td>
								</tr>
								<tr>
									<td height='2' colspan='2'></td>
								</tr>
								<tr>
									<td width='165'><b>Re-delivery 1</b></td>
									<td width='250'><input type='text' name='redely1' value=\"".htmlentities(stripslashes($messagearr['redely1']))."\" style='width:240px; border:1px solid #CCC; padding:3px;' /></td>
								</tr>
								<tr>
									<td height='2' colspan='2'></td>
								</tr>
								<tr>
									<td width='165'><b>Re-delivery Date 1</b></td>
									<td width='250'><input type='text' name='redelydate1' value=\"".htmlentities(stripslashes($messagearr['redelydate1']))."\" style='width:120px; border:1px solid #CCC; padding:3px;' onclick=\"showCalendar('',this,null,'','',0,5,1)\" /></td>
								</tr>
								<tr>
									<td height='2' colspan='2'></td>
								</tr>";
								
								/*echo "<tr>
									<td width='165'><b>Re-delivery 2</b></td>
									<td width='250'><input type='text' name='redely2' value=\"".htmlentities(stripslashes($messagearr['redely2']))."\" style='width:240px; border:1px solid #CCC; padding:3px;' /></td>
								</tr>
								<tr>
									<td height='2' colspan='2'></td>
								</tr>
								<tr>
									<td width='165'><b>Re-delivery Date 2</b></td>
									<td width='250'><input type='text' name='redelydate2' value=\"".htmlentities(stripslashes($messagearr['redelydate2']))."\" style='width:120px; border:1px solid #CCC; padding:3px;' onclick=\"showCalendar('',this,null,'','',0,5,1)\" /></td>
								</tr>
								<tr>
									<td height='2' colspan='2'></td>
								</tr>
								<tr>
									<td width='165'><b>Re-delivery 3</b></td>
									<td width='250'><input type='text' name='redely3' value=\"".htmlentities(stripslashes($messagearr['redely3']))."\" style='width:240px; border:1px solid #CCC; padding:3px;' /></td>
								</tr>
								<tr>
									<td height='2' colspan='2'></td>
								</tr>
								<tr>
									<td width='165'><b>Re-delivery Date 3</b></td>
									<td width='250'><input type='text' name='redelydate3' value=\"".htmlentities(stripslashes($messagearr['redelydate3']))."\" style='width:120px; border:1px solid #CCC; padding:3px;' onclick=\"showCalendar('',this,null,'','',0,5,1)\" /></td>
								</tr>
								<tr>
									<td height='2' colspan='2'></td>
								</tr>
								<tr>
									<td width='165'><b>Re-delivery 4</b></td>
									<td width='250'><input type='text' name='redely4' value=\"".htmlentities(stripslashes($messagearr['redely4']))."\" style='width:240px; border:1px solid #CCC; padding:3px;' /></td>
								</tr>
								<tr>
									<td height='2' colspan='2'></td>
								</tr>
								<tr>
									<td width='165'><b>Re-delivery Date 4</b></td>
									<td width='250'><input type='text' name='redelydate4' value=\"".htmlentities(stripslashes($messagearr['redelydate4']))."\" style='width:120px; border:1px solid #CCC; padding:3px;' onclick=\"showCalendar('',this,null,'','',0,5,1)\" /></td>
								</tr>
								<tr>
									<td height='2' colspan='2'></td>
								</tr>";*/
								
								echo "<tr>
									<td width='165'><b>Rate</b></td>
									<td width='250'><input type='text' name='rate' value=\"".htmlentities(stripslashes($messagearr['rate']))."\" style='width:240px; border:1px solid #CCC; padding:3px;' /></td>
								</tr>
								<tr>
									<td height='2' colspan='2'></td>
								</tr>
								<tr>
									<td width='165'><b>Charterer</b></td>
									<td width='250'><input type='text' name='charterer' value=\"".htmlentities(stripslashes($messagearr['charterer']))."\" style='width:240px; border:1px solid #CCC; padding:3px;' /></td>
								</tr>
								<tr>
									<td height='2' colspan='2'></td>
								</tr>
								<tr>
									<td width='165'><b>Period</b></td>
									<td width='250'><input type='text' name='period' value=\"".htmlentities(stripslashes($messagearr['period']))."\" style='width:240px; border:1px solid #CCC; padding:3px;' /></td>
								</tr>
								<tr>
									<td height='2' colspan='2'></td>
								</tr>
								<tr>
									<td width='165'><b>Dur Min</b></td>
									<td width='250'><input type='text' name='dur_min' value=\"".htmlentities(stripslashes($messagearr['dur_min']))."\" style='width:240px; border:1px solid #CCC; padding:3px;' /></td>
								</tr>
								<tr>
									<td height='2' colspan='2'></td>
								</tr>
								<tr>
									<td width='165'><b>Dur Max</b></td>
									<td width='250'><input type='text' name='dur_max' value=\"".htmlentities(stripslashes($messagearr['dur_max']))."\" style='width:240px; border:1px solid #CCC; padding:3px;' /></td>
								</tr>
								<tr>
									<td height='2' colspan='2'></td>
								</tr>
								<tr>
									<td width='165'><b>Relet</b></td>
									<td width='250'><input type='text' name='relet' value=\"".htmlentities(stripslashes($messagearr['relet']))."\" style='width:240px; border:1px solid #CCC; padding:3px;' /></td>
								</tr>
								<tr>
									<td height='2' colspan='2'></td>
								</tr>
								<tr>
									<td width='165'><b>Remarks</b></td>
									<td width='250'><input type='text' name='remarks' value=\"".htmlentities(stripslashes($messagearr['remarks']))."\" style='width:240px; border:1px solid #CCC; padding:3px;' /></td>
								</tr>
								<tr>
									<td height='2' colspan='2'></td>
								</tr>
								<tr>
									<td colspan='2' align='right'><input type='button' id='submitbutt' value='Submit' onclick='addMessageDry()'></td>
								</tr>
							</table>
						</td>
						<td width='415' style='padding-left:20px;' valign='top'>
							<div id='messages'>";
						
							if($t){
								for($i=0; $i<$t; $i++){
									$messagearr = unserialize($messages[$i]['message']);
									$date = convertDateToTs($messagearr['opendate']);
									
									echo "<table cellpading='0' cellspacing='0' border='0' id='message".$messages[$i]['id']."'>
										<tr>
											<td width='208'><b>by:</b> ".$messages[$i]['user_email']."</td>
											<td width='207'><b>date added:</b> ".date("M d, 'y", strtotime($messages[$i]['dateadded']))."</td>
										</tr>
										<tr>
											<td height='15' colspan='2'></td>
										</tr>
										<tr>
											<td width='165'><b>Delivery</b></td>
											<td width='250'>: ".stripslashes($messagearr['dely'])."&nbsp;</td>
										</tr>
										<tr>
											<td height='2' colspan='2'></td>
										</tr>
										<tr>
											<td width='165'><b>Delivery Date From</b></td>
											<td width='250'>: ".stripslashes($messagearr['delydate_from'])."&nbsp;</td>
										</tr>
										<tr>
											<td height='2' colspan='2'></td>
										</tr>
										<tr>
											<td width='165'><b>Delivery Date To</b></td>
											<td width='250'>: ".stripslashes($messagearr['delydate_to'])."&nbsp;</td>
										</tr>
										<tr>
											<td height='2' colspan='2'></td>
										</tr>
										<tr>
											<td width='165'><b>Re-delivery 1</b></td>
											<td width='250'>: ".stripslashes($messagearr['redely1'])."&nbsp;</td>
										</tr>
										<tr>
											<td height='2' colspan='2'></td>
										</tr>
										<tr>
											<td width='165'><b>Re-delivery Date 1</b></td>
											<td width='250'>: ".stripslashes($messagearr['redelydate1'])."&nbsp;</td>
										</tr>
										<tr>
											<td height='2' colspan='2'></td>
										</tr>";
										
										/*echo "<tr>
											<td width='165'><b>Re-delivery 2</b></td>
											<td width='250'>: ".stripslashes($messagearr['redely2'])."&nbsp;</td>
										</tr>
										<tr>
											<td height='2' colspan='2'></td>
										</tr>
										<tr>
											<td width='165'><b>Re-delivery Date 2</b></td>
											<td width='250'>: ".stripslashes($messagearr['redelydate2'])."&nbsp;</td>
										</tr>
										<tr>
											<td height='2' colspan='2'></td>
										</tr>
										<tr>
											<td width='165'><b>Re-delivery 3</b></td>
											<td width='250'>: ".stripslashes($messagearr['redely3'])."&nbsp;</td>
										</tr>
										<tr>
											<td height='2' colspan='2'></td>
										</tr>
										<tr>
											<td width='165'><b>Re-delivery Date 3</b></td>
											<td width='250'>: ".stripslashes($messagearr['redelydate3'])."&nbsp;</td>
										</tr>
										<tr>
											<td height='2' colspan='2'></td>
										</tr>
										<tr>
											<td width='165'><b>Re-delivery 4</b></td>
											<td width='250'>: ".stripslashes($messagearr['redely4'])."&nbsp;</td>
										</tr>
										<tr>
											<td height='2' colspan='2'></td>
										</tr>
										<tr>
											<td width='165'><b>Re-delivery Date 4</b></td>
											<td width='250'>: ".stripslashes($messagearr['redelydate4'])."&nbsp;</td>
										</tr>
										<tr>
											<td height='2' colspan='2'></td>
										</tr>";*/
										
										echo "<tr>
											<td width='165'><b>Rate</b></td>
											<td width='250'>: ".stripslashes($messagearr['rate'])."&nbsp;</td>
										</tr>
										<tr>
											<td height='2' colspan='2'></td>
										</tr>
										<tr>
											<td width='165'><b>Charterer</b></td>
											<td width='250'>: ".stripslashes($messagearr['charterer'])."&nbsp;</td>
										</tr>
										<tr>
											<td height='2' colspan='2'></td>
										</tr>
										<tr>
											<td width='165'><b>Period</b></td>
											<td width='250'>: ".stripslashes($messagearr['period'])."&nbsp;</td>
										</tr>
										<tr>
											<td height='2' colspan='2'></td>
										</tr>
										<tr>
											<td width='165'><b>Dur Min</b></td>
											<td width='250'>: ".stripslashes($messagearr['dur_min'])."&nbsp;</td>
										</tr>
										<tr>
											<td height='2' colspan='2'></td>
										</tr>
										<tr>
											<td width='165'><b>Dur Max</b></td>
											<td width='250'>: ".stripslashes($messagearr['dur_max'])."&nbsp;</td>
										</tr>
										<tr>
											<td height='2' colspan='2'></td>
										</tr>
										<tr>
											<td width='165'><b>Relet</b></td>
											<td width='250'>: ".stripslashes($messagearr['relet'])."&nbsp;</td>
										</tr>
										<tr>
											<td height='2' colspan='2'></td>
										</tr>
										<tr>
											<td width='165'><b>Remarks</b></td>
											<td width='250'>: ".stripslashes($messagearr['remarks'])."&nbsp;</td>
										</tr>
									</table>";
								}
							}else{
								echo "<div id='nomessages'>No Messages</div>";
							}
							
							echo "</div>
						</td>
                    </tr>
                </table>";
            }
            
            echo "</form>";
            echo "</div>";
            ?>
		</td>
	</tr>
</table>
<script>
jQuery("#dely1").focus().autocomplete(ports);
jQuery("#dely1").setOptions({
	scrollHeight: 180
});
</script>
<?php }else{ ?>
<table>
	<tr>
		<td id='mcontainer'>
			<?php
            if($_GET['type']=='private'){
                echo "<div class='title'>Private Mesages</div>";
            }else if($_GET['type']=='network'){
                echo "<div class='title'>Broker Updates</div>";
            }
            
            $mid = preg_replace("/[^0-9]/", "", $_GET['mid']);
            
            openMessage($mid);
            
            echo "<div id='messager'>";
            echo "<form id='messageform'>";
            
            if($_GET['type']=='private'){
            
                echo "<table cellpading=0; cellspacing=0>
                    <tr>
                        <td style='vertical-align:top'><textarea name='message' id='message'></textarea></td>
                        <td style='vertical-align:top'><input type='button' id='submitbutt' value='Submit' onclick='addMessage()'></td>
                    </tr>
                </table>";
            }else if($_GET['type']=='network'){
                $messagearr =  unserialize($messages[0]['message']);
            
                echo "<table cellpading='0' cellspacing='0' width='870'>
                    <tr>
                        <td style='vertical-align:top'>
                            <table cellpading='0' cellspacing='0' style='padding-bottom:10px;'>
                                <tr>
                                    <td style='vertical-align:top' width='170'>Open Port</td>
                                    <td style='vertical-align:top' width='110'>Open Date (UTC)</td>
                                    <td style='vertical-align:top' width='110'>Destination</td>
									<td style='vertical-align:top' width='90'>Date</td>
                                    <td style='vertical-align:top' width='80'>Charterer</td>
                                    <td style='vertical-align:top' width='310'>Remark</td>
                                </tr>
                                <tr>
                                    <td style='vertical-align:top'><div id='openport'><input id='openport1' type='text' name='openport' value=\"".htmlentities(stripslashes($messagearr['openport']))."\" style='width:165px;' /></div></td>
                                    <td style='vertical-align:top'><div id='opendate'><input type='text' name='opendate'  readonly=\"readonly\" value=\"".htmlentities(stripslashes($messagearr['opendate']))."\" onclick=\"showCalendar('',this,null,'','',0,5,1)\" style='width:105px;' /></div></td>
                                    <td style='vertical-align:top'><div id='destinationregion'><input id='destinationregion1' type='text' name='destinationregion' value=\"".htmlentities(stripslashes($messagearr['destinationregion']))."\" style='width:105px;' /></div></td>
									<td style='vertical-align:top'><div id='destinationdate'><input type='text' name='destinationdate' value=\"".htmlentities(stripslashes($messagearr['destinationdate']))."\" onclick=\"showCalendar('',this,null,'','',0,5,1)\" style='width:85px;' /></div></td>
                                    <td style='vertical-align:top'><input type='text' name='charterer' id='charterer' style='width:75px;' value=\"".htmlentities(stripslashes($messagearr['charterer']))."\" /></td>
                                    <td style='vertical-align:top'><input type='text' name='remark' id='remark' style='width:305px;' value=\"".htmlentities(stripslashes($messagearr['remark']))."\" /></td>
                                </tr>
                            </table>
                            <table cellpading='0' cellspacing='0' style='padding-bottom:15px;'>
                                <tr>
                                    <td style='vertical-align:top' width='126'>Cargo Type</td>
									<td style='vertical-align:top' width='124'>Quantity</td>
                                    <td style='vertical-align:top' width='124'>Status</td>
                                    <td style='vertical-align:top' width='124'>CBM</td>
                                    <td style='vertical-align:top' width='124'>Rate</td>
                                    <td style='vertical-align:top' width='124'>TCE</td>
                                    <td style='vertical-align:top' width='124'>WS</td>
                                </tr>
                                <tr>
                                    <td style='vertical-align:top'><input type='text' name='cargotype' id='cargotype' style='width:121px;' value=\"".htmlentities(stripslashes($messagearr['cargo_type']))."\" /></td>
									<td style='vertical-align:top'><input type='text' name='quantity' id='quantity' style='width:119px;' value=\"".htmlentities(stripslashes($messagearr['quantity']))."\" /></td>
                                    <td style='vertical-align:top'><input type='text' name='status' id='status' style='width:119px;' value=\"".htmlentities(stripslashes($messagearr['status']))."\" /></td>
                                    <td style='vertical-align:top'><input type='text' name='cbm' id='cbm' style='width:119px;' value=\"".htmlentities(stripslashes($messagearr['cbm']))."\" /></td>
                                    <td style='vertical-align:top'><input type='text' name='rate' id='rate' style='width:119px;' value=\"".htmlentities(stripslashes($messagearr['rate']))."\" /></td>
                                    <td style='vertical-align:top'><input type='text' name='tce' id='tce' style='width:119px;' value=\"".htmlentities(stripslashes($messagearr['tce']))."\" /></td>
                                    <td style='vertical-align:top'><input type='text' name='ws' id='ws' style='width:119px;' value=\"".htmlentities(stripslashes($messagearr['ws']))."\" /></td>
                                </tr>
                            </table>
                            <table cellpading='0' cellspacing='0' width='870' style='padding-bottom:20px;'>
                                <tr>
                                    <td style='vertical-align:top;' align='center'><input type='button' id='submitbutt' value='Submit' onclick='addMessage()'></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>";
                ?>
                <script>
                jQuery("#openport1").focus().autocomplete(ports);
                jQuery("#openport1").setOptions({
                    scrollHeight: 180
                });
                
                jQuery("#destinationregion1").focus().autocomplete(ports);
                jQuery("#destinationregion1").setOptions({
                    scrollHeight: 180
                });
                </script>	
                <?php
            }
            
            echo "</form>";
            echo "</div>";
            
            echo "<div id='messages'>";
            
            if($t){
                for($i=0; $i<$t; $i++){
                    if($_GET['type']=='private'){
                        echo "<table width='850px' id='message".$messages[$i]['id']."'>
							<tr>
								<td style='width: 450px'>".stripslashes($messages[$i]['message'])."</td>
								<td style='width: 200px; text-align:right'>".$messages[$i]['user_email']."</td>
								<td style='width: 200px; text-align:right'>".date("M d, 'y", convertDateToTs($messages[$i]['dateadded']))."</td>
							</tr>
						</table>";
                    }else if($_GET['type']=='network'){
						$messagearr = unserialize($messages[$i]['message']);
						$date = convertDateToTs($messagearr['opendate']);
						
						echo "<table width='870' style='border:1px solid #000;' id='message".$messages[$i]['id']."'>
							<tr>
								<td>
									<table width='870'>
										<tr style='background:#FEDFD8'>
											<td colspan='2'><b>By:</b> ".$messages[$i]['user_email']."</td>
											<td colspan='3'><b>Date Added:</b> ".date("M d, 'y", strtotime($messages[$i]['dateadded']))."</td>
										</tr>
										<tr style='background:#FEDFD8'>
											<td width='170'>".stripslashes($messagearr['openport'])."&nbsp;</td>
											<td width='110'>".date("M, d 'y", $date)."&nbsp;</td>
											<td width='110'>".stripslashes($messagearr['destinationregion'])."&nbsp;</td>
											<td width='90'>".stripslashes($messagearr['destinationdate'])."&nbsp;</td>
											<td width='80'>".stripslashes($messagearr['charterer'])."&nbsp;</td>
											<td width='310'>".stripslashes($messagearr['remark'])."&nbsp;</td>
										</tr>
									</table>
									<table width='870'>
										<tr style='background:#FEDFD8'>
											<td width='126'>".stripslashes($messagearr['cargotype'])."&nbsp;</td>
											<td width='124'>".stripslashes($messagearr['quantity'])."&nbsp;</td>
											<td width='124'>".stripslashes($messagearr['status'])."&nbsp;</td>
											<td width='124'>".stripslashes($messagearr['cbm'])."&nbsp;</td>
											<td width='124'>".stripslashes($messagearr['rate'])."&nbsp;</td>
											<td width='124'>".stripslashes($messagearr['tce'])."&nbsp;</td>
											<td width='124'>".stripslashes($messagearr['ws'])."&nbsp;</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>";
                    }
                }
            }else{
                echo "<div id='nomessages'>No Messages</div>";
            }
            
            echo "</div>";
            ?>
		</td>
	</tr>
</table>
<?php } ?>
</center>
</body>
</html>