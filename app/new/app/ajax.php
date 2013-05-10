<?php
include_once(dirname(__FILE__)."/includes/bootstrap.php");

global $user;

if($_GET['new_search']==1){
	$option_num = $_POST['option_num'];

	if($option_num==1){
		if($_GET['tabid1']){
			$tabid = $_GET['tabid1'];
		}else if($_POST['tabid1']){
			$tabid = $_POST['tabid1'];
		}
	
		$tabname = $_POST['destination_port'].'<br />'.date('M d, Y h:s:i');
		
		$tabarr = array();
		$tabarr['destination_port'] = $_POST['destination_port'];
		$tabarr['destination_port_from'] = $_POST['destination_port_from'];
		$tabarr['destination_port_to'] = $_POST['destination_port_to'];
		$tabarr['dwt_range'] = $_POST['dwt_range'];
		$tabarr['vessel_type'] = $_POST['vessel_type'];
		$tabdata = serialize($tabarr);
	}else if($option_num==2){
		if($_GET['tabid2']){
			$tabid = $_GET['tabid2'];
		}else if($_POST['tabid2']){
			$tabid = $_POST['tabid2'];
		}
		
		$zones = array(
			'z1'=>'[z1] AUSTRALIA', 
			'z2'=>'[z2] BALTIC SEA', 
			'z3'=>'[z3] BLACK SEA', 
			'z4'=>'[z4] CARIB', 
			'z5'=>'[z5] EC CAN', 
			'z6'=>'[z6] ECCA', 
			'z7'=>'[z7] ECEC', 
			'z8'=>'[z8] ECI', 
			'z9'=>'[z9] ECSA', 
			'z10'=>'[z10] FAR EAST', 
			'z11'=>'[z11] FRENCH ATLANTIC', 
			'z12'=>'[z12] MEDITERRANEAN', 
			'z13'=>'[z13] MED-RS-PG-WCI', 
			'z14'=>'[z14] N EUROPE', 
			'z15'=>'[z15] NCSA', 
			'z16'=>'[z16] NEW ZEALAND', 
			'z17'=>'[z17] NOPAC', 
			'z18'=>'[z18] NORTH SEA', 
			'z19'=>'[z19] NORWEGIAN SEA', 
			'z20'=>'[z20] PERSIAN GULF', 
			'z21'=>'[z21] PG +WCI', 
			'z22'=>'[z22] RED SEA', 
			'z23'=>'[z23] SA', 
			'z24'=>'[z24] SE AFRICA', 
			'z25'=>'[z25] SE ASIA', 
			'z26'=>'[z26] SPAIN ATLANTIC', 
			'z27'=>'[z27] ST LAWRENCE', 
			'z28'=>'[z28] SW AFRICA', 
			'z29'=>'[z29] UK AND EIRE', 
			'z30'=>'[z30] USG', 
			'z31'=>'[z31] WCCA', 
			'z32'=>'[z32] WCSA', 
			'z33'=>'[z33] WEST COAST INDIA'
		);
	
		$tabname = $zones[$_POST['zone']].'<br />'.date('M d, Y h:s:i');
		
		$tabarr = array();
		$tabarr['destination_port_from2'] = $_POST['destination_port_from2'];
		$tabarr['destination_port_to2'] = $_POST['destination_port_to2'];
		$tabarr['dwt_range2'] = $_POST['dwt_range2'];
		$tabarr['vessel_type2'] = $_POST['vessel_type2'];
		$tabarr['zone'] = $_POST['zone'];
		$tabdata = serialize($tabarr);
	}
	
	if($tabid){
		$sql = "UPDATE `_user_tabs`
			SET `tabname`='".mysql_escape_string($tabname)."', 
				`tabdata`='".mysql_escape_string($tabdata)."'
			WHERE `id`='".$tabid."'";
		dbQuery($sql, $link);
	}else{
		$sql = "INSERT INTO `_user_tabs` (`uid`, `page`, `tabname`, `tabdata`, `option`, `dateadded`) VALUES('".$user['uid']."', 'aisbroker', '".mysql_escape_string($tabname)."', '".mysql_escape_string($tabdata)."', '".mysql_escape_string($option_num)."', NOW())";
		dbQuery($sql, $link);
	}
	
	exit();
}

if($_GET['new_search']==2){
	if($_POST['dwt_type']){
		$dwt_type = explode(' - ', $_POST['dwt_type']);
		$dwt_type = $dwt_type[1];
	
		$tabname = $dwt_type.'<br />'.date('M d, Y h:s:i');
	}else if($_POST['vessel_name_or_imo']){
		$vessel_name_or_imo = explode(' - ', $_POST['vessel_name_or_imo']);
		$vessel_name = $vessel_name_or_imo[1];
	
		$tabname = $vessel_name.'<br />'.date('M d, Y h:s:i');
	}else{
		$tabname = 'New Tab<br />'.date('M d, Y h:s:i');
	}

	$tabarr = array();
	
	$tabarr['dry'] = 1;
	foreach($_POST as $key => $val){
		$tabarr[$key] = $val;
	}
	
	$tabdata = serialize($tabarr);
	
	if($_GET['tabid']){
		$sql = "UPDATE `_user_tabs`
			SET `tabname`='".mysql_escape_string($tabname)."', 
				`tabdata`='".mysql_escape_string($tabdata)."'
			WHERE `id`='".$_GET['tabid']."'";
		dbQuery($sql, $link);
	}else if($_POST['tabid']){
		$sql = "UPDATE `_user_tabs`
			SET `tabname`='".mysql_escape_string($tabname)."', 
				`tabdata`='".mysql_escape_string($tabdata)."'
			WHERE `id`='".$_POST['tabid']."'";
		dbQuery($sql, $link);
	}else{
		$sql = "INSERT INTO `_user_tabs` (`uid`, `page`, `tabname`, `tabdata`, `dateadded`) VALUES('".$user['uid']."', 'voyageestimator', '".mysql_escape_string($tabname)."', '".mysql_escape_string($tabdata)."', NOW())";
		dbQuery($sql, $link);
	}
	
	exit();
}

if($_GET['new_search']==1 || $_GET['new_search']==3 && isset($_GET['tabid'])){
	$sql = "DELETE FROM `_user_tabs` WHERE `id`='".$_GET['tabid']."'";
	dbQuery($sql, $link);
	
	exit();
}

if($_GET['portname']){
	$dwt = str_replace(' tons', '', $_GET['dwt']);
	$dwt = intval(str_replace(',', '', $dwt));
	
	if($dwt>=0 && $dwt<=10000){
		$dwt_low = 0;
		$dwt_high = 10000;
	}else if($dwt>=10000 && $dwt<=35000){
		$dwt_low = 10000;
		$dwt_high = 35000;
	}else if($dwt>=35000 && $dwt<=60000){
		$dwt_low = 35000;
		$dwt_high = 60000;
	}else if($dwt>=60000 && $dwt<=75000){
		$dwt_low = 60000;
		$dwt_high = 75000;
	}else if($dwt>=75000 && $dwt<=110000){
		$dwt_low = 75000;
		$dwt_high = 110000;
	}else if($dwt>=110000 && $dwt<=150000){
		$dwt_low = 110000;
		$dwt_high = 150000;
	}else if($dwt>=150000 && $dwt<=555000){
		$dwt_low = 150000;
		$dwt_high = 555000;
	}else{
		$dwt_low = 0;
		$dwt_high = 555000;
	}

	$sql = "select * from _port_details where port_name='".mysql_escape_string($_GET['portname'])."' order by dateadded desc limit 0,1";
	$r = dbQuery($sql);
	
	if($r[0]['id']){
		$details = unserialize($r[0]['port_details']);
	
		$dwt_rec = str_replace(' tons', '', $details['dwt']);
		$dwt_rec = intval(str_replace(',', '', $dwt_rec));
	
		if($dwt_rec>=$dwt_low && $dwt_rec<=$dwt_high){
			$total_over_all = $details['total_over_all'];
			if($total_over_all==0 || $total_over_all==''){
				echo $details['quick_total_charges'];
			}
		}
	}
	
	exit();
}
?>