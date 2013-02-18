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
}

if($_GET['new_search']==2){
	$tabname = $_POST['ship'].'<br />'.date('M d, Y h:s:i');
	
	$tabarr = array();
	$tabarr['ship'] = $_POST['ship'];
	$tabarr['c31'] = $_POST['c31'];
	$tabarr['d31'] = $_POST['d31'];
	$tabarr['e31'] = $_POST['e31'];
	$tabarr['g31'] = $_POST['g31'];
	$tabarr['e33'] = $_POST['e33'];
	$tabarr['g33'] = $_POST['g33'];
	$tabarr['e34'] = $_POST['e34'];
	$tabarr['g34'] = $_POST['g34'];
	$tabarr['s31'] = $_POST['s31'];
	$tabarr['t31'] = $_POST['t31'];
	$tabarr['i32'] = $_POST['i32'];
	$tabarr['k32'] = $_POST['k32'];
	$tabarr['m32'] = $_POST['m32'];
	$tabarr['n32'] = $_POST['n32'];
	$tabarr['p32'] = $_POST['p32'];
	$tabarr['q32'] = $_POST['q32'];
	$tabarr['s32'] = $_POST['s32'];
	$tabarr['t32'] = $_POST['t32'];
	$tabarr['l33'] = $_POST['l33'];
	$tabarr['m33'] = $_POST['m33'];
	$tabarr['n33'] = $_POST['n33'];
	$tabarr['p33'] = $_POST['p33'];
	$tabarr['q33'] = $_POST['q33'];
	$tabarr['s33'] = $_POST['s33'];
	$tabarr['t33'] = $_POST['t33'];
	$tabarr['s34'] = $_POST['s34'];
	$tabarr['t34'] = $_POST['t34'];
	$tabarr['i35'] = $_POST['i35'];
	$tabarr['k35'] = $_POST['k35'];
	$tabarr['m35'] = $_POST['m35'];
	$tabarr['n35'] = $_POST['n35'];
	$tabarr['p35'] = $_POST['p35'];
	$tabarr['q35'] = $_POST['q35'];
	$tabarr['s35'] = $_POST['s35'];
	$tabarr['t35'] = $_POST['t35'];
	$tabarr['d42'] = $_POST['d42'];
	$tabarr['h42'] = $_POST['h42'];
	$tabarr['c44'] = $_POST['c44'];
	$tabarr['d44'] = $_POST['d44'];
	$tabarr['e44'] = $_POST['e44'];
	$tabarr['g44'] = $_POST['g44'];
	$tabarr['h44'] = $_POST['h44'];
	$tabarr['f45'] = $_POST['f45'];
	$tabarr['i45'] = $_POST['i45'];
	$tabarr['d19'] = $_POST['d19'];
	$tabarr['d20'] = $_POST['d20'];
	$tabarr['d21'] = $_POST['d21'];
	$tabarr['d22'] = $_POST['d22'];
	$tabarr['d23'] = $_POST['d23'];
	$tabarr['d24'] = $_POST['d24'];
	$tabarr['c51'] = $_POST['c51'];
	$tabarr['c52'] = $_POST['c52'];
	$tabarr['term'] = $_POST['term'];
	$tabarr['linerterms'] = $_POST['linerterms'];
	$tabarr['dues1'] = $_POST['dues1'];
	$tabarr['dues2'] = $_POST['dues2'];
	$tabarr['dues3'] = $_POST['dues3'];
	$tabarr['pilotage1'] = $_POST['pilotage1'];
	$tabarr['pilotage2'] = $_POST['pilotage2'];
	$tabarr['pilotage3'] = $_POST['pilotage3'];
	$tabarr['tugs1'] = $_POST['tugs1'];
	$tabarr['tugs2'] = $_POST['tugs2'];
	$tabarr['tugs3'] = $_POST['tugs3'];
	$tabarr['bunkeradjustment1'] = $_POST['bunkeradjustment1'];
	$tabarr['bunkeradjustment2'] = $_POST['bunkeradjustment2'];
	$tabarr['bunkeradjustment3'] = $_POST['bunkeradjustment3'];
	$tabarr['mooring1'] = $_POST['mooring1'];
	$tabarr['mooring2'] = $_POST['mooring2'];
	$tabarr['mooring3'] = $_POST['mooring3'];
	$tabarr['dockage1'] = $_POST['dockage1'];
	$tabarr['dockage2'] = $_POST['dockage2'];
	$tabarr['dockage3'] = $_POST['dockage3'];
	$tabarr['loaddischarge1'] = $_POST['loaddischarge1'];
	$tabarr['loaddischarge2'] = $_POST['loaddischarge2'];
	$tabarr['loaddischarge3'] = $_POST['loaddischarge3'];
	$tabarr['agencyfee1'] = $_POST['agencyfee1'];
	$tabarr['agencyfee2'] = $_POST['agencyfee2'];
	$tabarr['agencyfee3'] = $_POST['agencyfee3'];
	$tabarr['miscellaneous1'] = $_POST['miscellaneous1'];
	$tabarr['miscellaneous2'] = $_POST['miscellaneous2'];
	$tabarr['miscellaneous3'] = $_POST['miscellaneous3'];
	$tabarr['canal'] = $_POST['canal'];
	$tabarr['cbook1'] = $_POST['cbook1'];
	$tabarr['cbook2'] = $_POST['cbook2'];
	$tabarr['ctug1'] = $_POST['ctug1'];
	$tabarr['ctug2'] = $_POST['ctug2'];
	$tabarr['cline1'] = $_POST['cline1'];
	$tabarr['cline2'] = $_POST['cline2'];
	$tabarr['cmisc1'] = $_POST['cmisc1'];
	$tabarr['cmisc2'] = $_POST['cmisc2'];
	$tabarr['e74'] = $_POST['e74'];
	$tabarr['f74'] = $_POST['f74'];
	$tabarr['g74'] = $_POST['g74'];
	$tabarr['h74'] = $_POST['h74'];
	$tabarr['i74'] = $_POST['i74'];
	$tabarr['j74'] = $_POST['j74'];
	$tabarr['b80'] = $_POST['b80'];
	$tabarr['d80'] = $_POST['d80'];
	$tabarr['e80'] = $_POST['e80'];
	$tabarr['d85'] = $_POST['d85'];
	$tabarr['e85'] = $_POST['e85'];
	$tabarr['g85'] = $_POST['g85'];
	
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
}

if($_GET['new_search']==1 || $_GET['new_search']==3 && isset($_GET['tabid'])){
	$sql = "DELETE FROM `_user_tabs` WHERE `id`='".$_GET['tabid']."'";
	dbQuery($sql, $link);
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
}
?>