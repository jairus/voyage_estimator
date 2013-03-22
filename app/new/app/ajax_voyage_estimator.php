<?php
@include_once(dirname(__FILE__)."/includes/bootstrap.php");
date_default_timezone_set('UTC');

//SAVE SESSION
if($_GET['autosave']){
	$_SESSION['data'] = $_POST['data'];

	exit();
}
//END OF SAVE SESSION

//GET SHIP DATA
if($_GET['search']){
	$search = $_GET['term'];

	$sql = "select * from  _xvas_parsed2_dry where imo <> '' and (imo like '%".mysql_escape_string($search)."%' or name like '%".mysql_escape_string($search)."%') limit 20";

	$ships = array();
	$imos = array();

	$r = dbQuery($sql);

	$t = count($r);

	for($i=0; $i<$t; $i++){
		$sql2 = "select * from _xvas_shipdata_dry where imo <> '' and imo='".trim($r[$i]['imo'])."' limit 1";
		$r2 = dbQuery($sql2);
		
		$sql3 = "select * from _xvas_siitech_cache where xvas_imo <> '' and xvas_imo='".trim($r[$i]['imo'])."' limit 1";
		$r3 = dbQuery($sql3);
		
		$ship = array();

		$ship['name'] = $r[$i]['imo']." - ".$r[$i]['name'];
		$ship['mmsi'] = $r[$i]['mmsi'];
		$ship['imo'] = $r[$i]['imo'];
		$ship['dwt'] = $r[$i]['summer_dwt'];
		$ship['gross_tonnage'] = getValue($r2[0]['data'], 'GROSS_TONNAGE');
		$ship['net_tonnage'] = getValue($r2[0]['data'], 'NET_TONNAGE');
		$ship['built_year'] = getValue($r2[0]['data'], 'BUILD');
		
		$flag = getValue($r2[0]['data'], 'LAST_KNOWN_FLAG');
		if($flag==""){
			$ship['flag'] = getValue($r2[0]['data'], 'FLAG');
			$ship['flag_image'] = getFlagImage($ship['flag']);
		}else{
			$ship['flag'] = $flag;
			$ship['flag_image'] = getFlagImage($ship['flag']);
		}
		
		$ship['loa'] = getValue($r2[0]['data'], 'LENGTH_OVERALL');
		$ship['draught'] = getValue($r2[0]['data'], 'DRAUGHT');
		$ship['speed'] = $r[$i]['speed'];
		$ship['breadth'] = getValue($r2[0]['data'], 'BREADTH_EXTREME');
		$ship['cranes'] = getValue($r2[0]['data'], 'CRANES');
		$ship['grain'] = getValue($r2[0]['data'], 'GRAIN');
		$ship['cargo_handling'] = getValue($r2[0]['data'], 'CARGO_HANDLING');
		$ship['decks_number'] = getValue($r2[0]['data'], 'DECKS_NUMBER');
		$ship['bulkheads'] = getValue($r2[0]['data'], 'BULKHEADS');
		$ship['class_notation'] = getValue($r2[0]['data'], 'CLASS_NOTATION');
		$ship['lifting_equipment'] = getValue($r2[0]['data'], 'LIFTING_EQUIPMENT');
		$ship['bale'] = getValue($r2[0]['data'], 'BALE');
		$ship['fuel_oil'] = getValue($r2[0]['data'], 'FUEL_OIL');
		$ship['fuel'] = getValue($r2[0]['data'], 'FUEL');
		$ship['fuel_consumption'] = getValue($r2[0]['data'], 'FUEL_CONSUMPTION');
		$ship['fuel_type'] = getValue($r2[0]['data'], 'FUEL_TYPE');
		
		$ship['manager_owner'] = getValue($r2[0]['data'], 'MANAGER');
		if(!trim($ship['manager_owner'])){ $ship['manager_owner'] = getValue($r2[0]['data'], 'MANAGER_OWNER'); }
		if(!trim($ship['manager_owner'])){ $ship['manager_owner'] = getValue($r2[0]['data'], 'OWNER'); }
		
		$ship['manager_owner_email'] = getValue($r2[0]['data'], 'MANAGER_OWNER_EMAIL');
		$ship['class_society'] = htmlentities(getValue($r2[0]['data'], 'CLASS_SOCIETY'));
		$ship['holds'] = htmlentities(getValue($r2[0]['data'], 'HOLDS'));
		$ship['largest_hatch'] = htmlentities(getValue($r2[0]['data'], 'LARGEST_HATCH'));
		
		//AIS DATA
		if($r3[0]){
			$ship['speed_ais'] = getValue($r3[0]['siitech_shipstat_data'], 'speed_ais');
			$ship['NavigationalStatus'] = getValue($r3[0]['siitech_shippos_data'], 'NavigationalStatus');
			$ship['aisdateupdated'] = $r3[0]['dateupdated'];
		}
		//END OF AIS DATA

		$ships[] = $ship;
	}

	echo json_encode($ships);

	exit();
}
//END OF GET SHIP DATA

//GET PORT DATA
if($_GET['port']){
	$search = $_GET['term'];

	$sql = "select * from _veson_ports where name like '%".mysql_escape_string($search)."%' limit 20";
	$r = dbQuery($sql);

	$t = count($r);
	
	$items = array();
	for($i=0; $i<$t; $i++){
		$item = array();

		$item['name'] = $r[$i]['name'];
		$item['latitude'] = $r[$i]['latitude'];
		$item['longitude'] = $r[$i]['longitude'];
		$item['portid'] = $r[$i]['portid'];
		
		$sql_2 = "select average_price, dateupdated from bunker_price where port_name='".mysql_escape_string($r[$i]['name'])."' and grade='IFO380' limit 1";
		$r_2 = dbQuery($sql_2);
		
		$sql_3 = "select average_price from bunker_price where port_name='".mysql_escape_string($r[$i]['name'])."' and grade='MDO' limit 1";
		$r_3 = dbQuery($sql_3);
		
		$sql_4 = "select average_price from bunker_price where port_name='".mysql_escape_string($r[$i]['name'])."' and grade='IFO180' limit 1";
		$r_4 = dbQuery($sql_4);
		
		$sql_5 = "select average_price from bunker_price where port_name='".mysql_escape_string($r[$i]['name'])."' and grade='MGO' limit 1";
		$r_5 = dbQuery($sql_5);
		
		$sql_6 = "select average_price from bunker_price where port_name='".mysql_escape_string($r[$i]['name'])."' and grade='LS180 1%' limit 1";
		$r_6 = dbQuery($sql_6);
		
		$sql_7 = "select average_price from bunker_price where port_name='".mysql_escape_string($r[$i]['name'])."' and grade='LS380 1%' limit 1";
		$r_7 = dbQuery($sql_7);
		
		$sql_8 = "select average_price from bunker_price where port_name='".mysql_escape_string($r[$i]['name'])."' and grade='LSMGO 0.1%' limit 1";
		$r_8 = dbQuery($sql_8);
		
		$item['dateupdated'] = $r_2[0]['dateupdated'];
		$item['average_price_ifo380'] = $r_2[0]['average_price'];
		$item['average_price_mdo'] = $r_3[0]['average_price'];
		$item['average_price_ifo180'] = $r_4[0]['average_price'];
		$item['average_price_mgo'] = $r_5[0]['average_price'];
		$item['average_price_ls180_1'] = $r_6[0]['average_price'];
		$item['average_price_ls380_1'] = $r_7[0]['average_price'];
		$item['average_price_lsmgo'] = $r_8[0]['average_price'];

		$items[] = $item;
	}

	echo json_encode($items);

	exit();
}
//END OF GET PORT DATA

//GET DISTANCE MILES
if($_GET['dc']){
	$dc = new distanceCalc();
	$from = $_GET['from'];
	$to = $_GET['to'];

	echo $dc->getDistancePortToPort($from, $to);

	exit();
}
//END OF GET DISTANCE MILES

//GET CARGO
if($_GET['sf']){
	$search = $_GET['term'];

	$sql = "select * from  ve_sf where cargo_name like '%".mysql_escape_string($search)."%' limit 20";
	$r = dbQuery($sql);

	$t = count($r);
	
	$items = array();
	for($i=0; $i<$t; $i++){
		$item = array();

		$item['cargo_name'] = $r[$i]['cargo_name']." - ".$r[$i]['sf'];

		$items[] = $item;
	}

	echo json_encode($items);

	exit();
}
//END OF GET CARGO
?>

<link rel="stylesheet" href="js/development-bundle/themes/base/jquery.ui.all.css">
<script src="js/development-bundle/ui/jquery.ui.core.js"></script>
<script src="js/development-bundle/ui/jquery.ui.widget.js"></script>
<script src="js/development-bundle/ui/jquery.ui.position.js"></script>
<script src="js/development-bundle/ui/jquery.ui.autocomplete.js"></script>
<script src="js/development-bundle/ui/jquery.ui.mouse.js"></script>
<script src="js/development-bundle/ui/jquery.ui.draggable.js"></script>
<script src="js/development-bundle/ui/jquery.ui.position.js"></script>
<script src="js/development-bundle/ui/jquery.ui.resizable.js"></script>
<script src="js/development-bundle/ui/jquery.ui.dialog.js"></script>
<script src="js/development-bundle/ui/jquery.ui.datepicker.js"></script>
<script type="text/javascript">
//SAVE SESSION
setTimeout(function(){ autoSave(); }, 1000*10);

function autoSave(){
	str = "";

	jQuery('input[type="text"]').each(function(){
		str+=jQuery(this).val()+"\n";
	});

	jQuery.ajax({
		type: 'POST',
		url: "ajax_voyage_estimator.php?autosave=1",
		data: 'data='+str,
		success: function(data) { }
	});	

	setTimeout(function(){ autoSave(); }, 6000*10);
}
//END OF SAVE SESSION

//OTHER FUNCTIONS
function addCommas(nStr){
	nStr += '';

	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';

	var rgx = /(\d+)(\d{3})/;

	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}

	return x1 + x2;
}

function fNum(num){
	num = uNum(num);

	if(num==0){ return ""; }

	num = num.toFixed(2);

	return addCommas(num);
}

function uNum(num){
	if(!num){
		num = 0;
	}else if(isNaN(num)){
		num = num.replace(/[^0-9\.]/g, "");

		if(isNaN(num)){ num = 0; }
	}

	return num*1;
}

function valueF(elem){
	if(elem.prop("tagName")=="TD"){
		return fNum(elem.html());
	}else{
		return fNum(elem.val());
	}
}

function valueU(elem){
	if(elem.prop("tagName")=="TD"){
		return uNum(elem.html());
	}else{
		return uNum(elem.val());
	}
}

function setValue(elem, value){
	if(elem.prop("tagName")=="TD"){
		elem.html(value);
	}else{
		elem.val(value);
	}
}

function getValue(elem){
	if(elem.prop("tagName")=="TD"){
		return elem.html();
	}else{
		return elem.val();
	}
}

function sumF(id1, id2){
	alpha = id1.replace(/[0-9]/ig, "");
	num1 = id1.replace(/[a-z]/ig, "")*1;
	num2 = id2.replace(/[a-z]/ig, "")*1;
	sum = 0;

	for(i=num1; i<=num2; i++){ sum += valueU(jQuery("#"+alpha+i)); }

	return fNum(sum);
}

function thread(skip){
	totalseadays = 0;
	totalportdays = 0;

	setValue(jQuery("#e3"), valueF(jQuery("#o37")));
	setValue(jQuery("#e6"), valueF(jQuery("#b75")));
	setValue(jQuery("#e13"), valueF(jQuery("#g80")));
	setValue(jQuery("#e14"), valueF(jQuery("#b85")));
	setValue(jQuery("#d25"), sumF("d19", "d24"));
	setValue(jQuery("#d26"), fNum(valueU(jQuery("#d18")) - valueU(jQuery("#d25"))));

	if(skip!="seacalc"){
		ballastCalc();
		ladenCalc();
		repositioningCalc();
	}

	loadingCalc();
	dischargingCalc();
	bunkerstopCalc();

	if(skip!="sf"){
		jQuery(".i32").each(function(){
			str = jQuery(this).val();
			pcs = str.split("-");
			cargo = pcs[0];
			cargo = jQuery.trim(cargo);
			sf = pcs[1];
			sf = jQuery.trim(sf);
			idx = jQuery(this).parent().parent().attr('id');

			if(sf){ setValue(jQuery("#"+idx+" .j32"), fNum(sf)); }
		});

		jQuery(".i35").each(function(){
			str = jQuery(this).val();
			pcs = str.split("-");
			cargo = pcs[0];
			cargo = jQuery.trim(cargo);
			sf = pcs[1];
			sf = jQuery.trim(sf);
			idx = jQuery(this).parent().parent().attr('id');

			if(sf){ setValue(jQuery("#"+idx+" .j35"), fNum(sf)); }
		});
	}

	calculateSeaPortDays();
	calculateDates();
	setupPortInterface();
	canalTotal();
	voyageDisbursement();
	result1();
	result2();
}

jQuery(function(){
	jQuery('.number').keyup(function(){
		thread();
	});

	jQuery('.number').blur(function(){
		fnum = fNum(jQuery(this).val());
		setValue(jQuery(this), fnum);

		w = jQuery(this).val().length * 8;
	});

	jQuery('.general').blur(function(){
		w = jQuery(this).val().length * 8;

		thread();
	});

	jQuery('.number').each(function(){
		fnum = valueF(jQuery(this));
		setValue(jQuery(this), fnum);
	});

	jQuery('.number').each(function(){
		fnum = fNum(jQuery(this).val());
		setValue(jQuery(this), fnum);

		w = jQuery(this).val().length * 8;

		if(w > jQuery(this).parent().width()){ jQuery(this).width(w); }
	});

	jQuery('.general').each(function(){
		w = jQuery(this).val().length * 8;

		if(w > jQuery(this).parent().width()){ jQuery(this).width(w); }
	});

	thread();
});

$(".calendar").datepicker({ 
	dateFormat: "dd/mm/yy, DD",
	onSelect: function(date) {
		jQuery(this).val(date);
	},
});
//END OF OTHER FUNCTIONS

//SHIP DETAIL VARIABLES
var suggestions = [];
var imos = [];
var dwts = [];
var gross_tonnages = [];
var net_tonnages = [];
var built_years = [];
var flags = [];
var flag_images = [];
var loas = [];
var draughts = [];
var speeds = [];
var breadths = [];
var craness = [];
var grains = [];
var cargo_handlings = [];
var decks_numbers = [];
var bulkheadss = [];
var class_notations = [];
var lifting_equipments = [];
var bales = [];
var fuel_oils = [];
var fuels = [];
var fuel_consumptions = [];
var fuel_types = [];
var manager_owners = [];
var manager_owner_emails = [];
var class_societys = [];
var holdss = [];
var largest_hatchs = [];
var speed_aiss = [];
var NavigationalStatuss = [];
var aisdateupdateds = [];
//END OF SHIP DETAIL VARIABLES

//PORT DETAIL VARIABLES
var average_price_ifo380s = [];
var average_price_mdos = [];
var average_price_ifo180s = [];
var average_price_mgos = [];
var average_price_ls180_1s = [];
var average_price_ls380_1s = [];
var average_price_lsmgos = [];
var dateupdateds = [];
//END OF PORT DETAIL VARIABLES

$(function(){
	//DETAILS COMING FROM SHIP NAME
	$("#ship").autocomplete({
		source: function(req, add){
			jQuery("#shipdetailshref").html("");

			$.getJSON("ajax_voyage_estimator.php?search=1", req, function(data) {
				var suggestions = [];
				var imos = [];

				$.each(data, function(i, val){
					suggestions.push(val.name);
					imos.push(val.imo);

					dwts[val.imo] = val.dwt;
					gross_tonnages[val.imo] = val.gross_tonnage;
					net_tonnages[val.imo] = val.net_tonnage;
					built_years[val.imo] = val.built_year;
					flags[val.imo] = val.flag;
					flag_images[val.imo] = val.flag_image;
					loas[val.imo] = val.loa;
					draughts[val.imo] = val.draught;
					speeds[val.imo] = val.speed;
					breadths[val.imo] = val.breadth;
					craness[val.imo] = val.cranes;
					grains[val.imo] = val.grain;
					cargo_handlings[val.imo] = val.cargo_handling;
					decks_numbers[val.imo] = val.decks_number;
					bulkheadss[val.imo] = val.bulkheads;
					class_notations[val.imo] = val.class_notation;
					lifting_equipments[val.imo] = val.lifting_equipment;
					bales[val.imo] = val.bale;
					fuel_oils[val.imo] = val.fuel_oil;
					fuels[val.imo] = val.fuel;
					fuel_consumptions[val.imo] = val.fuel_consumption;
					fuel_types[val.imo] = val.fuel_type;
					manager_owners[val.imo] = val.manager_owner;
					manager_owner_emails[val.imo] = val.manager_owner_email;
					class_societys[val.imo] = val.class_society;
					holdss[val.imo] = val.holds;
					largest_hatchs[val.imo] = val.largest_hatch;
					speed_aiss[val.imo] = val.speed_ais;
					NavigationalStatuss[val.imo] = val.NavigationalStatus;
					aisdateupdateds[val.imo] = val.aisdateupdated;
				});

				add(suggestions);
			});
		},
		select: function(e, ui) {
			str = ui.item.value;
			pcs = str.split("-");
			imo = pcs[0];
			imo = jQuery.trim(imo);
			gimo = imo;
			
			//SHIP DETAILS
			jQuery("#shipdetailshref").html("<a style='cursor:pointer;' onclick='showShipDetails()'><u>Click here for full specs</u></a> | <a style='cursor:pointer;' onclick='showShipDetails2()'><u>Click for your ships info</u></a> | <a style='cursor:pointer;' onclick='showShipSpeedHistory(\""+imo+"\")'><u>Click for ships speed history</u></a>");
			
			jQuery("#ship_info").show();
			jQuery("#ship_imo").each(function(){
				setValue(jQuery(this), imo);
			});
			jQuery("#ship_summer_dwt").each(function(){
				setValue(jQuery(this), fNum(dwts[imo]) + ' tons');
			});
			jQuery("#ship_gross_tonnage").each(function(){
				setValue(jQuery(this), fNum(gross_tonnages[imo]) + ' tons');
			});
			jQuery("#ship_net_tonnage").each(function(){
				setValue(jQuery(this), fNum(net_tonnages[imo]) + ' tons');
			});
			jQuery("#ship_built_year").each(function(){
				setValue(jQuery(this), built_years[imo]);
			});
			jQuery("#ship_flag").each(function(){
				setValue(jQuery(this), '<img src="'+ flag_images[imo] +'" alt="'+ flags[imo] +'" title="'+ flags[imo] +'">');
			});
			jQuery("#ship_loa").each(function(){
				setValue(jQuery(this), fNum(loas[imo]) + ' m');
			});
			jQuery("#ship_draught").each(function(){
				setValue(jQuery(this), fNum(draughts[imo]) + ' m');
			});
			jQuery("#ship_speed").each(function(){
				setValue(jQuery(this), fNum(speeds[imo]) + ' knts');
			});
			jQuery("#ship_breadth").each(function(){
				setValue(jQuery(this), fNum(breadths[imo]) + ' m');
			});
			jQuery("#ship_cranes").each(function(){
				setValue(jQuery(this), craness[imo]);
			});
			jQuery("#ship_grain").each(function(){
				setValue(jQuery(this), fNum(grains[imo]));
			});
			jQuery("#ship_cargo_handling").each(function(){
				setValue(jQuery(this), cargo_handlings[imo]);
			});
			jQuery("#ship_decks_number").each(function(){
				setValue(jQuery(this), decks_numbers[imo]);
			});
			jQuery("#ship_bulkheads").each(function(){
				setValue(jQuery(this), bulkheadss[imo]);
			});
			jQuery("#ship_class_notation").each(function(){
				setValue(jQuery(this), class_notations[imo]);
			});
			jQuery("#ship_lifting_equipment").each(function(){
				setValue(jQuery(this), lifting_equipments[imo]);
			});
			jQuery("#ship_bale").each(function(){
				setValue(jQuery(this), fNum(bales[imo]));
			});
			jQuery("#ship_fuel_oil").each(function(){
				setValue(jQuery(this), fNum(fuel_oils[imo]) + ' m');
			});
			jQuery("#ship_fuel").each(function(){
				setValue(jQuery(this), fNum(fuels[imo]) + ' t');
			});
			jQuery("#ship_fuel_consumption").each(function(){
				setValue(jQuery(this), fuel_consumptions[imo]);
			});
			jQuery("#ship_fuel_type").each(function(){
				setValue(jQuery(this), fuel_types[imo]);
			});
			jQuery("#ship_manager_owner").each(function(){
				setValue(jQuery(this), manager_owners[imo]);
			});
			jQuery("#ship_manager_owner_email").each(function(){
				setValue(jQuery(this), manager_owner_emails[imo]);
			});
			jQuery("#ship_class_society").each(function(){
				setValue(jQuery(this), class_societys[imo]);
			});
			jQuery("#ship_holds").each(function(){
				setValue(jQuery(this), holdss[imo]);
			});
			jQuery("#ship_largest_hatch").each(function(){
				setValue(jQuery(this), largest_hatchs[imo]);
			});
			jQuery("#ship_speed_ais").each(function(){
				setValue(jQuery(this), speed_aiss[imo]);
			});
			jQuery("#ship_NavigationalStatus").each(function(){
				setValue(jQuery(this), NavigationalStatuss[imo]);
			});
			jQuery("#ship_aisdateupdated").each(function(){
				setValue(jQuery(this), aisdateupdateds[imo]);
			});
			//END OF SHIP DETAILS
			
			//SPEED FOR VOYAGE LEGS
			jQuery(".g31").each(function(){
				setValue(jQuery(this), fNum(speeds[imo]));
			});

			jQuery(".g33").each(function(){
				setValue(jQuery(this), fNum(speeds[imo]));
			});

			jQuery(".g34").each(function(){
				setValue(jQuery(this), fNum(speeds[imo]));
			});
			
			jQuery(".g36").each(function(){
				setValue(jQuery(this), fNum(speeds[imo]));
			});
			//END OF SPEED FOR VOYAGE LEGS
			
			iframeve = document.getElementById('map_iframeve');
  			iframeve.src = "map/map_voyage_estimator.php?imo="+imo;
			
			setValue(jQuery("#d18"), fNum(dwts[imo]));
			
			thread();
		},
	});
	//END OF DETAILS COMING FROM SHIP NAME
	
	//FROM PORT BALLAST VOYAGE LEGS
	$(".c31").autocomplete({
		source: function(req, add){
			$.getJSON("ajax_voyage_estimator.php?port=1", req, function(data) {
				var suggestions = [];

				$.each(data, function(i, val){		
					suggestions.push(val.name);
				});

				add(suggestions);
			});
		},
		select: function(e, ui) {
			str = ui.item.value;
			idx = jQuery(this).parent().parent().attr('id');

			setValue(jQuery("#"+idx+" .c31"), str);

			ballastCalc(true);
			calculateDates();
		},
	});
	//END OF FROM PORT BALLAST VOYAGE LEGS
	
	//DATE BALLAST VOYAGE LEGS
	$(".d31").datepicker({ 
		dateFormat: "dd/mm/yy, DD",
		onSelect: function(date) {
			jQuery(this).val(date);

			calculateDates();
        },
	});
	//END OF DATE BALLAST VOYAGE LEGS
	
	//TO PORT BALLAST VOYAGE LEGS
	$(".e31").autocomplete({
		source: function(req, add){
			$.getJSON("ajax_voyage_estimator.php?port=1", req, function(data) {
				var suggestions = [];

				$.each(data, function(i, val){
					suggestions.push(val.name);
				});

				add(suggestions);
			});
		},
		select: function(e, ui) {
			str = ui.item.value;
			idx = jQuery(this).parent().parent().attr('id');

			setValue(jQuery("#"+idx+" .e31"), str);
			setValue(jQuery(".e33"), str);

			ballastCalc(true);
			calculateDates();
		},
	});
	//END OF TO PORT BALLAST VOYAGE LEGS
	
	//TO PORT BANKER STOP VOYAGE LEGS
	$(".e33").autocomplete({
		source: function(req, add){
			$.getJSON("ajax_voyage_estimator.php?port=1", req, function(data) {
				var suggestions = [];

				$.each(data, function(i, val){
					suggestions.push(val.name);
					
					average_price_ifo380s[val.name] = val.average_price_ifo380;
					average_price_mdos[val.name] = val.average_price_mdo;
					average_price_ifo180s[val.name] = val.average_price_ifo180;
					average_price_mgos[val.name] = val.average_price_mgo;
					average_price_ls180_1s[val.name] = val.average_price_ls180_1;
					average_price_ls380_1s[val.name] = val.average_price_ls380_1;
					average_price_lsmgos[val.name] = val.average_price_lsmgo;
					dateupdateds[val.name] = val.dateupdated;
				});

				add(suggestions);
			});
		},
		select: function(e, ui) {
			str = ui.item.value;
			idx = jQuery(this).parent().parent().attr('id');

			setValue(jQuery("#"+idx+" .e33"), str);
			
			//1st ROW
			if(average_price_ifo380s[str] || average_price_mdos[str]){
				jQuery("#d42").each(function(){
					setValue(jQuery(this), fNum(average_price_ifo380s[str]));
				});
				
				jQuery("#h42").each(function(){
					setValue(jQuery(this), fNum(average_price_mdos[str]));
				});
				jQuery('#bunker_first_row').show();
			}else{
				jQuery('#bunker_first_row').hide();
			}
			//1st ROW
			
			//2nd ROW
			if(average_price_ifo180s[str] || average_price_mgos[str]){
				jQuery("#d42_180").each(function(){
					setValue(jQuery(this), fNum(average_price_ifo180s[str]));
				});
				jQuery("#h42_mgo").each(function(){
					setValue(jQuery(this), fNum(average_price_mgos[str]));
				});
				jQuery('#bunker_second_row').show();
			}else{
				jQuery('#bunker_second_row').hide();
			}
			//2nd ROW
			
			//3rd ROW
			if(average_price_ls380_1s[str] || average_price_lsmgos[str]){
				jQuery("#d42_lsifo380").each(function(){
					setValue(jQuery(this), fNum(average_price_ls380_1s[str]));
				});
				jQuery("#h42_lsmgo").each(function(){
					setValue(jQuery(this), fNum(average_price_lsmgos[str]));
				});
				jQuery('#bunker_third_row').show();
			}else{
				jQuery('#bunker_third_row').hide();
			}
			//3rd ROW
			
			//4th ROW
			if(average_price_ls180_1s[str]){
				jQuery("#d42_lsifo180").each(function(){
					setValue(jQuery(this), fNum(average_price_ls180_1s[str]));
				});
				jQuery('#bunker_fourth_row').show();
			}else{
				jQuery('#bunker_fourth_row').hide();
			}
			//4th ROW
			
			if(dateupdateds[str]){
				jQuery('#bunker_price_dateupdated').text('Correct as of '+dateupdateds[str]);
			}else{
				jQuery('#bunker_price_dateupdated').text('');
			}

			bunkerstopCalc2(true);
			ladenCalc(true);
			repositioningCalc(true);
			calculateDates();
		},
	});
	//END OF TO PORT BANKER STOP VOYAGE LEGS
	
	//TO PORT LADEN VOYAGE LEGS
	$(".e34").autocomplete({
		source: function(req, add){
			$.getJSON("ajax_voyage_estimator.php?port=1", req, function(data) {
				var suggestions = [];

				$.each(data, function(i, val){
					suggestions.push(val.name);
				});

				add(suggestions);
			});
		},
		select: function(e, ui) {
			str = ui.item.value;
			idx = jQuery(this).parent().parent().attr('id');

			setValue(jQuery("#"+idx+" .e34"), str);
			setValue(jQuery("#c36"), str);

			ballastCalc(true);
			ladenCalc(true);
			repositioningCalc(true);
			calculateDates();
		},
	});
	//END OF TO PORT LADEN VOYAGE LEGS
	
	//TO PORT REPOSITIONING VOYAGE LEGS
	$(".e36").autocomplete({
		source: function(req, add){
			$.getJSON("ajax_voyage_estimator.php?port=1", req, function(data) {
				var suggestions = [];

				$.each(data, function(i, val){
					suggestions.push(val.name);
				});

				add(suggestions);
			});
		},
		select: function(e, ui) {
			str = ui.item.value;
			idx = jQuery(this).parent().parent().attr('id');

			setValue(jQuery("#"+idx+" .e36"), str);

			ballastCalc(true);
			ladenCalc(true);
			repositioningCalc(true);
			calculateDates();
		},
	});
	//END OF TO PORT REPOSITIONING VOYAGE LEGS
	
	//LOADING CARGO CARGO LEGS
	$(".i32").autocomplete({
		source: function(req, add){
			$.getJSON("ajax_voyage_estimator.php?sf=1", req, function(data) {
				var suggestions = [];
				var sfs = [];

				$.each(data, function(i, val){
					suggestions.push(val.cargo_name);
					sfs[val.cargo_name] = val.sf;
				});

				add(suggestions);
			});
		},
		select: function(e, ui) {
			str = ui.item.value;
			pcs = str.split("-");
			cargo = pcs[0];
			cargo = jQuery.trim(cargo);
			sf = pcs[1];
			sf = jQuery.trim(sf);
			idx = jQuery(this).parent().parent().attr('id');

			if(sf){
				setValue(jQuery("#"+idx+" .j32"), fNum(sf));
				setValue(jQuery("#i35"), str);
				setValue(jQuery("#j35"), fNum(sf));
			}

			thread("sf");
		},
	});
	//END OF LOADING CARGO CARGO LEGS
	
	//DISCHARGING CARGO CARGO LEGS
	$(".i35").autocomplete({
		source: function(req, add){
			$.getJSON("ajax_voyage_estimator.php?sf=1", req, function(data) {
				var suggestions = [];
				var sfs = [];

				$.each(data, function(i, val){
					suggestions.push(val.cargo_name);

					sfs[val.cargo_name] = val.sf;
				});

				add(suggestions);
			});
		},
		select: function(e, ui) {
			str = ui.item.value;
			pcs = str.split("-");
			cargo = pcs[0];
			cargo = jQuery.trim(cargo);
			sf = pcs[1];
			sf = jQuery.trim(sf);
			idx = jQuery(this).parent().parent().attr('id');

			if(sf){ setValue(jQuery("#"+idx+" .j35"), fNum(sf)); }

			thread("sf");
		},
	});
	//END OF DISCHARGING CARGO CARGO LEGS
});

//SHOW SHIP DETAILS
function showShipDetails(imo){
	jQuery("#shipdetails").dialog("close")
	jQuery('#pleasewait').show();

	jQuery.ajax({
		type: 'POST',
		url: "search_ajax1ve.php?imo="+gimo,
		data: '',

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

jQuery("#shipdetails").dialog( { autoOpen: false, width: '90%', height: jQuery(window).height()*0.9 });
jQuery("#shipdetails").dialog("close");
//END OF SHOW SHIP DETAILS

//SHOW EDITABLE SHIP DETAILS
function showShipDetails2(imo){
	var iframe = $("#shipdetailiframe");

	$(iframe).contents().find("body").html("");

	jQuery("#shipdetailiframe")[0].src='misc/ship_data_update.php?imo='+gimo;
	jQuery("#shipdetails2").dialog("open");
}

jQuery("#shipdetails2").dialog( { autoOpen: false, width: '90%', height: jQuery(window).height()*0.9 });
jQuery("#shipdetails2").dialog("close");
//END OF SHOW EDITABLE SHIP DETAILS

//SHOW SHIP SPEED HISTORY
function showShipSpeedHistory(imo){
	var iframe2 = $("#shipspeedhistoryiframe");

	$(iframe2).contents().find("body").html("");

	jQuery("#shipspeedhistoryiframe")[0].src='misc/shipspeedhistory.php?imo='+imo;
	jQuery("#shipspeedhistory").dialog("open");
}

jQuery("#shipspeedhistory").dialog( { autoOpen: false, width: '90%', height: jQuery(window).height()*0.9 });
jQuery("#shipspeedhistory").dialog("close");
//END OF SHOW SHIP SPEED HISTORY

//SHOW OWNER'S CONTACT DETAILS
function ownerDetails(owner, owner_id){
	var iframe = $("#contactiframe");

	$(iframe).contents().find("body").html("");

	jQuery("#contactiframe")[0].src='search_ajax1ve.php?contact=1&owner='+owner+'&owner_id='+owner_id;
	jQuery("#contactdialog").dialog("open");
}

jQuery("#contactdialog").dialog( { autoOpen: false, width: 900, height: 460 });
jQuery("#contactdialog").dialog("close");
//END OF SHOW OWNER'S CONTACT DETAILS

//FORMAT DAYS
var dateFormat = function () {
	var	token = /d{1,4}|m{1,4}|yy(?:yy)?|([HhMsTt])\1?|[LloSZ]|"[^"]*"|'[^']*'/g,

		timezone = /\b(?:[PMCEA][SDP]T|(?:Pacific|Mountain|Central|Eastern|Atlantic) (?:Standard|Daylight|Prevailing) Time|(?:GMT|UTC)(?:[-+]\d{4})?)\b/g,
		timezoneClip = /[^-+\dA-Z]/g,

		pad = function (val, len) {

			val = String(val);

			len = len || 2;

			while (val.length < len) val = "0" + val;

			return val;

		};
	// Regexes and supporting functions are cached through closure

	return function (date, mask, utc) {
		var dF = dateFormat;
		// You can't provide utc if you skip other args (use the "UTC:" mask prefix)
		if (arguments.length == 1 && Object.prototype.toString.call(date) == "[object String]" && !/\d/.test(date)) {
			mask = date;

			date = undefined;
		}
		
		// Passing date through Date applies Date.parse, if necessary
		date = date ? new Date(date) : new Date;

		if (isNaN(date)) throw SyntaxError("invalid date");
		
		mask = String(dF.masks[mask] || mask || dF.masks["default"]);

		// Allow setting the utc argument via the mask
		if (mask.slice(0, 4) == "UTC:") {
			mask = mask.slice(4);

			utc = true;
		}

		var	_ = utc ? "getUTC" : "get",
			d = date[_ + "Date"](),
			D = date[_ + "Day"](),
			m = date[_ + "Month"](),
			y = date[_ + "FullYear"](),
			H = date[_ + "Hours"](),
			M = date[_ + "Minutes"](),
			s = date[_ + "Seconds"](),
			L = date[_ + "Milliseconds"](),
			o = utc ? 0 : date.getTimezoneOffset(),

			flags = {
				d:    d,
				dd:   pad(d),
				ddd:  dF.i18n.dayNames[D],
				dddd: dF.i18n.dayNames[D + 7],
				m:    m + 1,
				mm:   pad(m + 1),
				mmm:  dF.i18n.monthNames[m],
				mmmm: dF.i18n.monthNames[m + 12],
				yy:   String(y).slice(2),
				yyyy: y,
				h:    H % 12 || 12,
				hh:   pad(H % 12 || 12),
				H:    H,
				HH:   pad(H),
				M:    M,
				MM:   pad(M),
				s:    s,
				ss:   pad(s),
				l:    pad(L, 3),
				L:    pad(L > 99 ? Math.round(L / 10) : L),
				t:    H < 12 ? "a"  : "p",
				tt:   H < 12 ? "am" : "pm",
				T:    H < 12 ? "A"  : "P",
				TT:   H < 12 ? "AM" : "PM",
				Z:    utc ? "UTC" : (String(date).match(timezone) || [""]).pop().replace(timezoneClip, ""),
				o:    (o > 0 ? "-" : "+") + pad(Math.floor(Math.abs(o) / 60) * 100 + Math.abs(o) % 60, 4),
				S:    ["th", "st", "nd", "rd"][d % 10 > 3 ? 0 : (d % 100 - d % 10 != 10) * d % 10]
			};

		return mask.replace(token, function ($0) {

			return $0 in flags ? flags[$0] : $0.slice(1, $0.length - 1);

		});

	};

}();

// Some common format strings
dateFormat.masks = {
	"default":      "ddd mmm dd yyyy HH:MM:ss",
	shortDate:      "m/d/yy",
	mediumDate:     "mmm d, yyyy",
	longDate:       "mmmm d, yyyy",
	fullDate:       "dddd, mmmm d, yyyy",
	shortTime:      "h:MM TT",
	mediumTime:     "h:MM:ss TT",
	longTime:       "h:MM:ss TT Z",
	isoDate:        "yyyy-mm-dd",
	isoTime:        "HH:MM:ss",
	isoDateTime:    "yyyy-mm-dd'T'HH:MM:ss",
	isoUtcDateTime: "UTC:yyyy-mm-dd'T'HH:MM:ss'Z'"
};

// Internationalization strings
dateFormat.i18n = {
	dayNames: [
		"Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat",
		"Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"
	],
	monthNames: [
		"Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec",
		"January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"
	]
};

// For convenience...
Date.prototype.format = function (mask, utc) {
	return dateFormat(this, mask, utc);
};

function addDays(date, daystoadd){
	if(daystoadd==""){ daystoadd = 0; }

	daystoadd = Math.ceil(daystoadd);

	if(date){
		date = date.split(",");
		date = date[0].split("/");
		date = date[1]+"/"+date[0]+"/"+date[2];

		try{
			thedate = new Date(date);
			thedate.setDate(thedate.getDate()+daystoadd);
			
			return dateFormat(thedate, "dd/mm/yyyy, dddd");
		}catch(e){ }
	}
}
//END OF FORMAT DAYS

//SUM OF CLASS
function sumClass(clas){
	sum = 0;

	jQuery("."+clas).each(function(){
		sum += valueU(jQuery(this));
	});

	return sum;
}
//END OF SUM OF CLASS

//CALCULATE PORT DAYS
function calculatePortDays(){
	sum = 0;

	sum += sumClass("o32");
	sum += sumClass("o33");
	sum += sumClass("o34");
	sum += sumClass("o35");
	sum += sumClass("p32");
	sum += sumClass("p33");
	sum += sumClass("p34");
	sum += sumClass("p35");
	sum += sumClass("q31");
	sum += sumClass("q32");
	sum += sumClass("q33");
	sum += sumClass("q34");
	sum += sumClass("q35");

	return sum;
}
//END OF CALCULATE PORT DAYS

//CALCULATE SEA DAYS
function calculateSeaDays(){
	sum = 0;

	sum += sumClass("r31");
	sum += sumClass("r32");
	sum += sumClass("r33");
	sum += sumClass("r34");
	sum += sumClass("r35");
	sum += sumClass("r36");
	sum += sumClass("s31");
	sum += sumClass("s32");
	sum += sumClass("s33");
	sum += sumClass("s34");
	sum += sumClass("s35");
	sum += sumClass("s36");
	sum += sumClass("t31");
	sum += sumClass("t32");
	sum += sumClass("t33");
	sum += sumClass("t34");
	sum += sumClass("t35");
	sum += sumClass("t36");

	return sum;
}
//END OF CALCULATE SEA DAYS

//CALCULATE DATES
function calculateDates(){
	//BALLAST
	n = 1;

	while(jQuery("#ballast"+n)[0]){
		tmp = "#ballast"+n+" ";

		date = getValue(jQuery(tmp+".d31"));
		days = valueU(jQuery(tmp+".p31")) + valueU(jQuery(tmp+".q31")) + valueU(jQuery(tmp+".r31")) + valueU(jQuery(tmp+".s31")) + valueU(jQuery(tmp+".t31"));

		adate = addDays(date, days);

		setValue(jQuery(tmp+".f31"), adate);

		n++;
	}

	c45 = getValue(jQuery("#c44"))*days;
	c46 = c45*(uNum(getValue(jQuery("#d42_input")))+uNum(getValue(jQuery("#d42_180_input")))+uNum(getValue(jQuery("#d42_lsifo380_input")))+uNum(getValue(jQuery("#d42_lsifo180_input"))));

	setValue(jQuery("#c45"), fNum(c45));
	setValue(jQuery("#c46"), fNum(c46));
	//END OF BALLAST
	
	//LOADING
	n = 1;

	while(jQuery("#loading"+n)[0]){
		num = 32;
		
		tmp = "#ballast"+n+" ";
		date = getValue(jQuery(tmp+".f"+(num-1)));
		portto = getValue(jQuery(tmp+".e"+(num-1)));
		
		tmp = "#loading"+n+" ";

		setValue(jQuery(tmp+".c"+num), portto);
		setValue(jQuery(tmp+".e"+num), portto);
		setValue(jQuery(tmp+".d"+num), date);

		date = getValue(jQuery(tmp+".d"+num));
		days = valueU(jQuery(tmp+".o"+num)) + valueU(jQuery(tmp+".p"+num)) + valueU(jQuery(tmp+".q"+num)) + valueU(jQuery(tmp+".r"+num)) + valueU(jQuery(tmp+".s"+num)) + valueU(jQuery(tmp+".t"+num));

		adate = addDays(date, days);

		setValue(jQuery(tmp+".f"+num), adate);

		n++;
	}
	//END OF LOADING
	
	//BUNKER STOP
	n = 1;

	while(jQuery("#bunkerstop"+n)[0]){
		num = 33;
		
		tmp = "#loading"+n+" ";
		date = getValue(jQuery(tmp+".f"+(num-1)));
		portto = getValue(jQuery(tmp+".e"+(num-1)));

		tmp = "#bunkerstop"+n+" ";
		setValue(jQuery(tmp+".c"+num), portto);
		setValue(jQuery(tmp+".d"+num), date);

		date = getValue(jQuery(tmp+".d"+num));
		days = valueU(jQuery(tmp+".o"+num)) + valueU(jQuery(tmp+".p"+num)) + valueU(jQuery(tmp+".q"+num)) + valueU(jQuery(tmp+".r"+num)) + valueU(jQuery(tmp+".s"+num)) + valueU(jQuery(tmp+".t"+num));

		adate = addDays(date, days);

		setValue(jQuery(tmp+".f"+num), adate);
		
		n++;
	}
	//END OF BUNKER STOP
	
	//LADEN
	n = 1;

	while(jQuery("#laden"+n)[0]){
		num = 34;

		tmp = "#bunkerstop"+n+" ";
		date = getValue(jQuery(tmp+".f"+(num-1)));
		portto = getValue(jQuery(tmp+".e"+(num-1)));

		tmp = "#laden"+n+" ";
		
		setValue(jQuery(tmp+".c"+num), portto);
		setValue(jQuery(tmp+".d"+num), date);

		date = getValue(jQuery(tmp+".d"+num));
		days = valueU(jQuery(tmp+".o"+num)) + valueU(jQuery(tmp+".p"+num)) + valueU(jQuery(tmp+".q"+num)) + valueU(jQuery(tmp+".r"+num)) + valueU(jQuery(tmp+".s"+num)) + valueU(jQuery(tmp+".t"+num));

		adate = addDays(date, days);

		setValue(jQuery(tmp+".f"+num), adate);

		n++;
	}

	d45 = uNum(getValue(jQuery("#d44")))*days;
	d46 = d45*(uNum(getValue(jQuery("#d42_input")))+uNum(getValue(jQuery("#d42_180_input")))+uNum(getValue(jQuery("#d42_lsifo380_input")))+uNum(getValue(jQuery("#d42_lsifo180_input"))));

	setValue(jQuery("#d45"), fNum(d45));
	setValue(jQuery("#d46"), fNum(d46));
	//END OF LADEN
	
	//DISCHARGING
	n = 1;

	while(jQuery("#discharging"+n)[0]){
		num = 35;

		tmp = "#laden"+n+" ";
		date = getValue(jQuery(tmp+".f"+(num-1)));
		portto = getValue(jQuery(tmp+".e"+(num-1)));

		tmp = "#discharging"+n+" ";

		setValue(jQuery(tmp+".c"+num), portto);
		setValue(jQuery(tmp+".e"+num), portto);
		setValue(jQuery(tmp+".d"+num), date);

		date = getValue(jQuery(tmp+".d"+num));
		days = valueU(jQuery(tmp+".o"+num)) + valueU(jQuery(tmp+".p"+num)) + valueU(jQuery(tmp+".q"+num)) + valueU(jQuery(tmp+".r"+num)) + valueU(jQuery(tmp+".s"+num)) + valueU(jQuery(tmp+".t"+num));

		adate = addDays(date, days);

		setValue(jQuery(tmp+".f"+num), adate);

		n++;
	}
	//END OF DISCHARGING
	
	//REPOSITIONING
	n = 1;

	while(jQuery("#repositioning"+n)[0]){
		num = 36;

		tmp = "#discharging"+n+" ";
		date = getValue(jQuery(tmp+".f"+(num-1)));
		portto = getValue(jQuery(tmp+".e"+(num-1)));

		tmp = "#repositioning"+n+" ";

		setValue(jQuery(tmp+".c"+num), portto);
		setValue(jQuery(tmp+".d"+num), date);

		date = getValue(jQuery(tmp+".d"+num));
		days = valueU(jQuery(tmp+".o"+num)) + valueU(jQuery(tmp+".p"+num)) + valueU(jQuery(tmp+".q"+num)) + valueU(jQuery(tmp+".r"+num)) + valueU(jQuery(tmp+".s"+num)) + valueU(jQuery(tmp+".t"+num));

		adate = addDays(date, days);

		setValue(jQuery(tmp+".f"+num), adate);

		n++;
	}
	//END OF REPOSITIONING
	
	portdays = calculatePortDays();
	
	e45 = uNum(getValue(jQuery("#e44")))*portdays;
	e46 = e45*(uNum(getValue(jQuery("#d42_input")))+uNum(getValue(jQuery("#d42_180_input")))+uNum(getValue(jQuery("#d42_lsifo380_input")))+uNum(getValue(jQuery("#d42_lsifo180_input"))));

	setValue(jQuery("#e45"), fNum(e45));
	setValue(jQuery("#e46"), fNum(e46));

	f45 = uNum(getValue(jQuery("#f45")));
	f46 = f45*(uNum(getValue(jQuery("#d42_input")))+uNum(getValue(jQuery("#d42_180_input")))+uNum(getValue(jQuery("#d42_lsifo380_input")))+uNum(getValue(jQuery("#d42_lsifo180_input"))));

	setValue(jQuery("#f46"), fNum(f46));

	c47 = c46+d46+e46+f46;
	setValue(jQuery("#c47"), fNum(c47));

	d19b = c45+d45+e45;
	setValue(jQuery("#d19b"), fNum(d19b));
	setValue(jQuery("#d19"), fNum(d19b));

	d21b = f45;
	setValue(jQuery("#d21b"), fNum(d21b));
	setValue(jQuery("#d21"), fNum(d21b));

	seadays = calculateSeaDays();
	g45 = uNum(getValue(jQuery("#g44")))*seadays;

	setValue(jQuery("#g45"), fNum(g45));
	g46 = g45 * (uNum(getValue(jQuery("#h42_input")))+uNum(getValue(jQuery("#h42_mgo_input")))+uNum(getValue(jQuery("#h42_lsmgo_input"))));
	setValue(jQuery("#g46"), fNum(g46));

	h45 = uNum(getValue(jQuery("#h44")))*(portdays+uNum(getValue(jQuery("#s34"))));
	setValue(jQuery("#h45"), fNum(h45));

	h46 = h45 * (uNum(getValue(jQuery("#h42_input")))+uNum(getValue(jQuery("#h42_mgo_input")))+uNum(getValue(jQuery("#h42_lsmgo_input"))));
	setValue(jQuery("#h46"), fNum(h46));

	i46 = (uNum(getValue(jQuery("#h42")))+uNum(getValue(jQuery("#h42_mgo_input")))+uNum(getValue(jQuery("#h42_lsmgo_input"))))*uNum(getValue(jQuery("#i45")));
	setValue(jQuery("#i46"), fNum(i46));

	g47 = g46+h46+i46;
	setValue(jQuery("#g47"), fNum(g47));	

	d20b = g45+h45;
	setValue(jQuery("#d20b"), fNum(d20b));
	setValue(jQuery("#d20"), fNum(d20b));

	d22b = uNum(getValue(jQuery("#i45")));
	setValue(jQuery("#d22b"), fNum(d22b));
	setValue(jQuery("#d22"), fNum(d22b));	

	b74 = c47 + g47
	setValue(jQuery("#b74"), fNum(b74));
}
//END OF CALCULATE DATES

//BALLAST CALCULATIONS
function ballastCalc(triggerajax){
	n = 1;

	while(jQuery("#ballast"+n)[0]){
		tmp = "#ballast"+n+" ";
		str = getValue(jQuery(tmp+".c31"));

		if(str){
			pcs = str.split("-");
			from = pcs[pcs.length-1];
			from = jQuery.trim(from);
			str = getValue(jQuery(tmp+".e31")); 
			pcs = str.split("-");
			to = pcs[pcs.length-1];
			to = jQuery.trim(to);

			if(from&&to){ ballastDistCalc(tmp, to, from, triggerajax); }
		}

		n++;
	}
}

function ballastDistCalc(tmp, to, from, triggerajax){
	fromx = getValue(jQuery(tmp+".c31"));
	pcs = fromx.split("-");
	fromx = pcs[pcs.length-1];
	fromx = jQuery.trim(fromx);
	tox = getValue(jQuery(tmp+".e31")); 
	pcs = str.split("-");
	tox = pcs[pcs.length-1];
	tox = jQuery.trim(tox);

	distance = valueU(jQuery(tmp+".h31"));

	if(to!=tox||from!=fromx||!distance||triggerajax){
		setValue(jQuery(tmp+".h31"), 'calculating...');

		jQuery.ajax({
			type: 'POST',
			url: "ajax_voyage_estimator.php?dc=1&from="+from+"&to="+to,
			data:  '',

			success: function(data) {
				setValue(jQuery(tmp+".h31"), fNum(data));

				distance = valueU(jQuery(tmp+".h31"));
				speed = valueU(jQuery(tmp+".g31"));

				if(speed == 0){
					speed = 13;
					setValue(jQuery(tmp+".g31"), fNum(speed));
				}
				
				sea = ( distance / valueU(jQuery(tmp+".g31")) / 24);

				setValue(
					jQuery(tmp+".r31"), 
					fNum(sea)
				);

				calculateSeaPortDays();
				calculateDates();
			}
		});
	}else{
		distance = valueU(jQuery(tmp+".h31"));
		speed = valueU(jQuery(tmp+".g31"));

		if(speed == 0){
			speed = 13;
			setValue(jQuery(tmp+".g31"), fNum(speed));
		}

		sea = ( distance / valueU(jQuery(tmp+".g31")) / 24);

		setValue(
			jQuery(tmp+".r31"), 
			fNum(sea)
		);

		calculateSeaPortDays();
		calculateDates();
	}
}
//END OF BALLAST CALCULATIONS

//BUNKER STOP CALCULATIONS 2
function bunkerstopCalc2(triggerajax){
	n = 1;

	while(jQuery("#bunkerstop"+n)[0]){
		tmp = "#bunkerstop"+n+" ";
		str = getValue(jQuery(tmp+".c33"));

		if(str){
			pcs = str.split("-");
			from = pcs[pcs.length-1];
			from = jQuery.trim(from);
			str = getValue(jQuery(tmp+".e33")); 
			pcs = str.split("-");
			to = pcs[pcs.length-1];
			to = jQuery.trim(to);

			if(from&&to){ bunkerstopDistCalc(tmp, to, from, triggerajax); }
		}

		n++;
	}
}

function bunkerstopDistCalc(tmp, to, from, triggerajax){
	fromx = getValue(jQuery(tmp+".c33"));
	pcs = fromx.split("-");
	fromx = pcs[pcs.length-1];
	fromx = jQuery.trim(fromx);
	tox = getValue(jQuery(tmp+".e33")); 
	pcs = str.split("-");
	tox = pcs[pcs.length-1];
	tox = jQuery.trim(tox);
	distance = valueU(jQuery(tmp+".h33"));

	if(to!=tox||from!=fromx||!distance||triggerajax){
		setValue(jQuery(tmp+".h33"), 'calculating...');

		jQuery.ajax({
			type: 'POST',
			url: "ajax_voyage_estimator.php?dc=1&from="+from+"&to="+to,
			data:  '',

			success: function(data) {
				setValue(jQuery(tmp+".h33"), fNum(data));

				distance = valueU(jQuery(tmp+".h33"));
				speed = valueU(jQuery(tmp+".g33"));

				if(speed == 0){
					speed = 13;
					setValue(jQuery(tmp+".g33"), fNum(speed));
				}

				sea = ( distance / valueU(jQuery(tmp+".g33")) / 24);

				setValue(
					jQuery(tmp+".r33"), 
					fNum(sea)
				);

				calculateSeaPortDays();
				calculateDates();
			}
		});
	}else{
		distance = valueU(jQuery(tmp+".h33"));
		speed = valueU(jQuery(tmp+".g33"));

		if(speed == 0){
			speed = 13;
			setValue(jQuery(tmp+".g33"), fNum(speed));
		}

		sea = ( distance / valueU(jQuery(tmp+".g33")) / 24);

		setValue(
			jQuery(tmp+".r33"), 
			fNum(sea)
		);

		calculateSeaPortDays();
		calculateDates();
	}
}
//END OF BUNKER STOP CALCULATIONS 2

//LADEN CALCULATIONS
function ladenCalc(triggerajax){
	n = 1;

	while(jQuery("#laden"+n)[0]){
		tmp = "#laden"+n+" ";
		str = getValue(jQuery(tmp+".c34"));
			
		if(str){
			pcs = str.split("-");
			from = pcs[pcs.length-1];
			from = jQuery.trim(from);
			str = getValue(jQuery(tmp+".e34")); 
			pcs = str.split("-");
			to = pcs[pcs.length-1];
			to = jQuery.trim(to);

			if(from&&to){ ladenDistCalc(tmp, to, from, triggerajax); }
		}

		n++;
	}
}

function ladenDistCalc(tmp, to, from, triggerajax){
	fromx = getValue(jQuery(tmp+".c34"));
	pcs = fromx.split("-");
	fromx = pcs[pcs.length-1];
	fromx = jQuery.trim(fromx);
	tox = getValue(jQuery(tmp+".e34")); 
	pcs = str.split("-");
	tox = pcs[pcs.length-1];
	tox = jQuery.trim(tox);
	distance = valueU(jQuery(tmp+".h34"));

	if(to!=tox||from!=fromx||!distance||triggerajax){
		setValue(jQuery(tmp+".h34"), 'calculating...');

		jQuery.ajax({
			type: 'POST',
			url: "ajax_voyage_estimator.php?dc=1&from="+from+"&to="+to,
			data:  '',

			success: function(data) {
				setValue(jQuery(tmp+".h34"), fNum(data));

				distance = valueU(jQuery(tmp+".h34"));
				speed = valueU(jQuery(tmp+".g34"));

				if(speed == 0){
					speed = 13;
					setValue(jQuery(tmp+".g34"), fNum(speed));
				}

				sea = ( distance / valueU(jQuery(tmp+".g34")) / 24);

				setValue(
					jQuery(tmp+".r34"), 

					fNum(sea)
				);

				calculateSeaPortDays();
				calculateDates();
			}
		});
	}else{
		distance = valueU(jQuery(tmp+".h34"));
		speed = valueU(jQuery(tmp+".g34"));

		if(speed == 0){
			speed = 13;
			setValue(jQuery(tmp+".g34"), fNum(speed));
		}

		sea = ( distance / valueU(jQuery(tmp+".g34")) / 24);

		setValue(
			jQuery(tmp+".r34"), 
			fNum(sea)
		);

		calculateSeaPortDays();
		calculateDates();
	}
}
//END OF LADEN CALCULATIONS

//REPOSITIONING CALCULATIONS
function repositioningCalc(triggerajax){
	n = 1;

	while(jQuery("#repositioning"+n)[0]){
		tmp = "#repositioning"+n+" ";
		str = getValue(jQuery(tmp+".c36"));
			
		if(str){
			pcs = str.split("-");
			from = pcs[pcs.length-1];
			from = jQuery.trim(from);
			str = getValue(jQuery(tmp+".e36")); 
			pcs = str.split("-");
			to = pcs[pcs.length-1];
			to = jQuery.trim(to);

			if(from&&to){ repositioningDistCalc(tmp, to, from, triggerajax); }
		}

		n++;
	}
}

function repositioningDistCalc(tmp, to, from, triggerajax){
	fromx = getValue(jQuery(tmp+".c36"));
	pcs = fromx.split("-");
	fromx = pcs[pcs.length-1];
	fromx = jQuery.trim(fromx);
	tox = getValue(jQuery(tmp+".e36")); 
	pcs = str.split("-");
	tox = pcs[pcs.length-1];
	tox = jQuery.trim(tox);
	distance = valueU(jQuery(tmp+".h36"));

	if(to!=tox||from!=fromx||!distance||triggerajax){
		setValue(jQuery(tmp+".h36"), 'calculating...');

		jQuery.ajax({
			type: 'POST',
			url: "ajax_voyage_estimator.php?dc=1&from="+from+"&to="+to,
			data:  '',

			success: function(data) {
				setValue(jQuery(tmp+".h36"), fNum(data));

				distance = valueU(jQuery(tmp+".h36"));
				speed = valueU(jQuery(tmp+".g36"));

				if(speed == 0){
					speed = 13;
					setValue(jQuery(tmp+".g36"), fNum(speed));
				}

				sea = ( distance / valueU(jQuery(tmp+".g36")) / 24);

				setValue(
					jQuery(tmp+".r36"), 
					fNum(sea)
				);

				calculateSeaPortDays();
				calculateDates();
			}
		});
	}else{
		distance = valueU(jQuery(tmp+".h36"));
		speed = valueU(jQuery(tmp+".g36"));

		if(speed == 0){
			speed = 13;
			setValue(jQuery(tmp+".g36"), fNum(speed));
		}

		sea = ( distance / valueU(jQuery(tmp+".g36")) / 24);

		setValue(
			jQuery(tmp+".r36"), 
			fNum(sea)
		);

		calculateSeaPortDays();
		calculateDates();
	}
}
//END OF REPOSITIONING CALCULATIONS

//BUNKER CALCULATIONS
function bunkerstopCalc(){
	n = 1;
	seadays = 0;
	portdays = 0;

	while(jQuery("#bunkerstop"+n)[0]){
		tmp = "#bunkerstop"+n+" ";

		ld = 0;
		ld = valueU(jQuery(tmp+".l33")) / valueU(jQuery(tmp+".m33"));

		setValue(jQuery(tmp+".o33"), fNum(ld));
		setValue(jQuery("#laytime2"), fNum(ld));

		seadays += ( valueU(jQuery(tmp+".s33")) + valueU(jQuery(tmp+".t33")) );
		portdays += ( ld + valueU(jQuery(tmp+".p33")) + valueU(jQuery(tmp+".q33")) );

		n++;
	}

	bunkerstopCalc2();
}
//END OF BUNKER CALCULATIONS

//LOADING CARGO CALCULATIONS
function loadingCalc(){
	n = 1;
	seadays = 0;
	portdays = 0;

	while(jQuery("#loading"+n)[0]){
		tmp = "#loading"+n+" ";

		volume = valueU(jQuery(tmp+".k32")) * valueU(jQuery(tmp+".j32"));

		setValue(jQuery(tmp+".l32"), fNum(volume));

		ld = 0;
		ld = valueU(jQuery(tmp+".k32")) / valueU(jQuery(tmp+".m32"));

		setValue(jQuery(tmp+".o32"), fNum(ld));
		setValue(jQuery("#laytime1"), fNum(ld*24));

		seadays += ( valueU(jQuery(tmp+".s32")) + valueU(jQuery(tmp+".t32")) );
		portdays += ( ld + valueU(jQuery(tmp+".p32")) + valueU(jQuery(tmp+".q32")) );

		n++;
	}
}
//END OF LOADING CARGO CALCULATIONS

//DISCHARGING CARGO CALCULATIONS
function dischargingCalc(){
	n = 1;

	while(jQuery("#discharging"+n)[0]){
		tmp = "#discharging"+n+" ";

		volume = valueU(jQuery(tmp+".k35")) * valueU(jQuery(tmp+".j35"));

		setValue(jQuery(tmp+".l35"), fNum(volume));

		ld = 0;
		ld = valueU(jQuery(tmp+".k35")) / valueU(jQuery(tmp+".m35"));

		setValue(jQuery(tmp+".o35"), fNum(ld));
		setValue(jQuery("#laytime3"), fNum(ld*24));

		n++;
	}
}
//END OF DISCHARGING CARGO CALCULATIONS

//CALCULATE SEA PORT DAYS
function calculateSeaPortDays(){
	totalportdays = calculatePortDays();
	totalseadays = calculateSeaDays();
	
	setValue(jQuery("#o36"), fNum(totalportdays));
	setValue(jQuery("#t37"), fNum(totalseadays));
	setValue(jQuery("#o37"), fNum(totalseadays+totalportdays));
}
//END OF CALCULATE SEA PORTDAYS

//COPY LOADING QUANTITY TO DISCHARGING QUANTITY
function populatek35(val){
	setValue(jQuery("#k35"), fNum(val));
	setValue(jQuery("#l35"), jQuery("#l32").text());
}
//END OF COPY LOADING QUANTITY TO DISCHARGING QUANTITY

//CANAL CALCULATIONS
function canalTotal(){
	ctoll1 = uNum(getValue(jQuery("#ctoll1")));
	cbook1 = uNum(getValue(jQuery("#cbook1")));
	ctug1 = uNum(getValue(jQuery("#ctug1")));
	cline1 = uNum(getValue(jQuery("#cline1")));
	cmisc1 = uNum(getValue(jQuery("#cmisc1")));

	ctotal1 = ctoll1 + cbook1 + ctug1 + cline1 + cmisc1;

	setValue(jQuery("#ctotal1"), fNum(ctotal1))

	ctoll2 = uNum(getValue(jQuery("#ctoll2")));
	cbook2 = uNum(getValue(jQuery("#cbook2")));
	ctug2 = uNum(getValue(jQuery("#ctug2")));
	cline2 = uNum(getValue(jQuery("#cline2")));
	cmisc2 = uNum(getValue(jQuery("#cmisc2")));

	ctotal2 = ctoll2 + cbook2 + ctug2 + cline2 + cmisc2;

	setValue(jQuery("#ctotal2"), fNum(ctotal2))

	d74 = ctotal1 + ctotal2;

	setValue(jQuery("#d74"), fNum(d74));
}
//END OF CANAL CALCULATIONS

//PORT SETUP
function setupPortInterface(){
	var numberOfDaysToAdd = parseInt(jQuery("#p33").val());

	setValue(jQuery("#port1"), '<a onclick="showPortDetails(\''+getValue(jQuery(".e31"))+'\', \''+jQuery("#f31").text()+'\', \''+jQuery("#f32").text()+'\', 0);" class="clickable">'+getValue(jQuery(".e31"))+'</a>');
	setValue(jQuery("#port2"), '<a onclick="showPortDetails(\''+getValue(jQuery(".e33"))+'\', \''+jQuery("#f33").text()+'\', \''+jQuery("#f33").text()+'\', \''+numberOfDaysToAdd+'\');" class="clickable">'+getValue(jQuery(".e33"))+'</a>');
	setValue(jQuery("#port3"), '<a onclick="showPortDetails(\''+getValue(jQuery(".e34"))+'\', \''+jQuery("#f34").text()+'\', \''+jQuery("#f35").text()+'\', 0);" class="clickable">'+getValue(jQuery(".e34"))+'</a>');
	
	getPortDetails(jQuery(".e31").val(), 1);
	getPortDetails(jQuery(".e33").val(), 2);
	getPortDetails(jQuery(".e34").val(), 3);

	jQuery(".port1 input").hide();
	jQuery(".port2 input").hide();
	jQuery(".port3 input").hide();

	if(getValue(jQuery("#port1"))){
		jQuery(".port1 input").show();
	}

	if(getValue(jQuery("#port2"))){
		jQuery(".port2 input").show();
	}

	if(getValue(jQuery("#port3"))){
		jQuery(".port3 input").show();
	}

	port1 = 0;

	jQuery(".port1 input").each(function(){
		if(jQuery(this).is(":visible")){
			port1 += uNum(getValue(jQuery(this)));
		} 
	});

	port2 = 0;
	jQuery(".port2 input").each(function(){
		if(jQuery(this).is(":visible")){
			port2 += uNum(getValue(jQuery(this)));
		} 
	});

	port3 = 0;
	jQuery(".port3 input").each(function(){
		if(jQuery(this).is(":visible")){
			port3 += uNum(getValue(jQuery(this)));
		} 
	});

	o32 = uNum(getValue(jQuery(".o32")));
	o33 = uNum(getValue(jQuery(".o33")));
	o35 = uNum(getValue(jQuery(".o35")));
	c52 = uNum(getValue(jQuery("#c52")));
	c52_2 = uNum(getValue(jQuery("#c52_2")));
	c52_3 = uNum(getValue(jQuery("#c52_3")));
	c54 = uNum(getValue(jQuery("#c54")));
	c54_2 = uNum(getValue(jQuery("#c54_2")));
	c54_3 = uNum(getValue(jQuery("#c54_3")));

	//num = (o32 + o33 + o35 - c51) / 24;
	num = 0;

	if(num<0){
		despatch = -1 * num * (c54 + c54_2 + c54_3);

		demurrage = 0;
	}else{
		despatch = 0;

		demurrage = num *  (c52 + c52_2 + c52_3);
	}
	
	setValue(jQuery("#c66"), fNum(demurrage));
	setValue(jQuery("#c67"), fNum(despatch));

	sum = port1+port2+port3;
	
	if($('#arrow1').attr('src')=='images/icon_dropdown_warning_shore.png'){
		sum = 0;
		
		da_quick_input1 = uNum(getValue(jQuery("#da_quick_input1")));
		da_quick_input2 = uNum(getValue(jQuery("#da_quick_input2")));
		da_quick_input3 = uNum(getValue(jQuery("#da_quick_input3")));
		
		sum = da_quick_input1 + da_quick_input2 + da_quick_input3;
	}

	c67 = 0;

	c68 = sum - c67;

	setValue(jQuery("#c68"), fNum(c68));
	
	total = sum + demurrage - despatch;

	setValue(jQuery("#c68"), fNum(total));

	c54 = c52 / 2;
	c54_2 = c52_2 / 2;
	c54_3 = c52_3 / 2;

	setValue(jQuery("#c54"), fNum(c54));
	setValue(jQuery("#c54_2"), fNum(c54_2));
	setValue(jQuery("#c54_3"), fNum(c54_3));
	setValue(jQuery("#c74"), fNum(c68));
}
//END OF PORT SETUP

//SHOW/HIDE OTHER INPUTS
function expand(){
	if($('#arrow1').attr('src')=='images/icon_pullup_warning_shore.png'){
		$('#arrow1').attr('src', 'images/icon_dropdown_warning_shore.png');
		
		jQuery('#other_input_table').hide();
	}else{
		$('#arrow1').attr('src', 'images/icon_pullup_warning_shore.png');
		
		jQuery('#other_input_table').show();
	}
	
	setupPortInterface();
}
//END OF SHOW/HIDE OTHER INPUTS

//SHOW PORT DETAILS
function showPortDetails(portname, date_from, date_to, num_of_days){
	var vessel_name = jQuery("#ship").val();
	var cargo_type = jQuery("#i32").val();
	var dwt = jQuery("#ship_summer_dwt").text();
	var gross_tonnage = jQuery("#ship_gross_tonnage").text();
	var net_tonnage = jQuery("#ship_net_tonnage").text();
	var owner = jQuery("#ship_manager_owner").text();

	var iframe = $("#portdetailsiframe");

	$(iframe).contents().find("body").html("");

	jQuery("#portdetailsiframe")[0].src='misc/port_details.php?portname='+portname+'&vessel_name='+vessel_name+'&cargo_type='+cargo_type+'&dwt='+dwt+'&gross_tonnage='+gross_tonnage+'&net_tonnage='+net_tonnage+'&owner='+owner+'&date_from='+date_from+'&date_to='+date_to+'&num_of_days='+num_of_days;
	jQuery("#portdetails").dialog("open");
}

jQuery("#portdetails").dialog( { autoOpen: false, width: '90%', height: jQuery(window).height()*0.9 });
jQuery("#portdetails").dialog("close");
//END OF SHOW PORT DETAILS

//GET PORT DETAILS
function getPortDetails(portname, port_num){
	var dwt = jQuery("#ship_summer_dwt").text();

	jQuery.ajax({
		type: "POST",
		url: "ajax.php?portname="+portname+"&dwt="+dwt,
		data: '',

		success: function(data) {
			jQuery("#record"+port_num).html(data);
			
			if(jQuery("#record1").text()!='' || jQuery("#record2").text()!='' || jQuery("#record3").text()!=''){
				jQuery('#quick_total_charges_row_id').show();
			}else{
				jQuery('#quick_total_charges_row_id').hide();
			}
		}
	});
}
//END OF GET PORT DETAILS

//VOYAGE DISBURSEMENT CALCULATIONS
function voyageDisbursement(){
	b74 = uNum(getValue(jQuery("#b74")));
	c74 = uNum(getValue(jQuery("#c74")));
	d74 = uNum(getValue(jQuery("#d74")));
	e74 = uNum(getValue(jQuery("#e74")));
	f74 = uNum(getValue(jQuery("#f74")));
	g74 = uNum(getValue(jQuery("#g74")));
	h74 = uNum(getValue(jQuery("#h74")));
	i74 = uNum(getValue(jQuery("#i74")));
	j74 = uNum(getValue(jQuery("#j74")));

	b75 = b74 + c74 + d74 + e74 + f74 + g74 + h74 + i74 + j74;

	setValue(jQuery("#b75"), fNum(b75));
}
//END OF VOYAGE DISBURSEMENT CALCULATIONS

//FREIGHT RATE CALCULATIONS
function result1(){
	c80 = uNum(getValue(jQuery(".k32"))) * uNum(getValue(jQuery("#b80")));
	setValue(jQuery("#c80"), fNum(c80));

	d81 = (uNum(getValue(jQuery("#d80"))) + uNum(getValue(jQuery("#e80")))) / 100 * uNum(getValue(jQuery("#c80")));
	setValue(jQuery("#d81"), fNum(d81));

	f80 = uNum(getValue(jQuery("#c80"))) - uNum(getValue(jQuery("#d81"))) - uNum(getValue(jQuery("#b75")));
	if(f80>0){
		setValue(jQuery("#f80"), '<span style="color:#006000;">'+fNum(f80)+'</span>');
	}else{
		setValue(jQuery("#f80"), '<span style="color:#ff0000;">'+fNum(f80)+'</span>');
	}

	g80 = f80 / uNum(getValue(jQuery("#o37")));
	setValue(jQuery("#g80"), fNum(g80)); 
}
//END OF FREIGHT RATE CALCULATIONS

//TCE CALCULATIONS
function result2(){
	f85 = uNum(getValue(jQuery("#g85"))) * uNum(getValue(jQuery("#o37")));
	setValue(jQuery("#f85"), fNum(f85));

	c85 = (f85 + uNum(getValue(jQuery("#b75"))) ) / (100 - uNum(getValue(jQuery("#d85"))) - uNum(getValue(jQuery("#e85")))) * 100;
	setValue(jQuery("#c85"), fNum(c85));

	b85 = uNum(getValue(jQuery("#c85"))) / uNum(getValue(jQuery(".k32")));
	setValue(jQuery("#b85"), fNum(b85));

	d86 = (uNum(getValue(jQuery("#d85"))) + uNum(getValue(jQuery("#e85"))) ) / 100 * uNum(getValue(jQuery("#c85")));
	setValue(jQuery("#d86"), fNum(d86));
}
//END OF TCE CALCULATIONS

//DISTANCE MILES CALCULATIONS
function computeDistanceMiles1(percent){
	if(percent){
		ans = uNum(getValue(jQuery("#h31")))*percent;
		
		setValue(jQuery("#i31x"), fNum(ans));
	}
}

function computeDistanceMiles2(percent){
	if(percent){
		ans = uNum(getValue(jQuery("#h33")))*percent;
		
		setValue(jQuery("#i33x"), fNum(ans));
	}
}

function computeDistanceMiles3(percent){
	if(percent){
		ans = uNum(getValue(jQuery("#h34")))*percent;
		
		setValue(jQuery("#i34x"), fNum(ans));
	}
}

function computeDistanceMiles4(percent){
	if(percent){
		ans = uNum(getValue(jQuery("#h36")))*percent;
		
		setValue(jQuery("#i36x"), fNum(ans));
	}
}
//END OF DISTANCE MILES CALCULATIONS

//MAIL/PRINT SHIP DETAILS
function mailItVe_2(){
	var imo = jQuery('#ship').val().substring(0,7);

	jQuery("#misciframe")[0].src="misc/email_ve_2.php?imo="+imo;
	jQuery("#miscdialog").dialog("open");
}

function printItVe_2(){
	var imo = jQuery('#ship').val().substring(0,7);

	jQuery("#misciframe")[0].src="misc/print_ve_2.php?imo="+imo;
	jQuery("#miscdialog").dialog("open");
}
//END OF MAIL/PRINT SHIP DETAILS

//MAIL/PRINT VOYAGE ESTIMATOR PAGE
function mailItVe(){
	var data = jQuery('form').serialize();
	
	//VOYAGE LEGS
	var f31 = jQuery("#f31").text();
	var h31 = jQuery("#h31").text();
	var c32 = jQuery("#c32").text();
	var d32 = jQuery("#d32").text();
	var e32 = jQuery("#e32").text();
	var f32 = jQuery("#f32").text();
	var g32 = jQuery("#g32").text();
	var h32 = jQuery("#h32").text();
	var c33 = jQuery("#c33").text();
	var d33 = jQuery("#d33").text();
	var f33 = jQuery("#f33").text();
	var h33 = jQuery("#h33").text();
	var c34 = jQuery("#c34").text();
	var d34 = jQuery("#d34").text();
	var f34 = jQuery("#f34").text();
	var h34 = jQuery("#h34").text();
	var c35 = jQuery("#c35").text();
	var d35 = jQuery("#d35").text();
	var e35 = jQuery("#e35").text();
	var f35 = jQuery("#f35").text();
	var g35 = jQuery("#g35").text();
	var h35 = jQuery("#h35").text();
	//END OF VOYAGE LEGS
	
	//CARGO LEGS
	var r31 = jQuery("#r31").text();
	var j32 = jQuery("#j32").text();
	var l32 = jQuery("#l32").text();
	var o32 = jQuery("#o32").text();
	var o33 = jQuery("#o33").text();
	var r34 = jQuery("#r34").text();
	var j35 = jQuery("#j35").text();
	var l35 = jQuery("#l35").text();
	var o35 = jQuery("#o35").text();
	//END OF CARGO LEGS
	
	//VOYAGE TIME
	var o36 = jQuery("#o36").text();
	var t37 = jQuery("#t37").text();
	var o37 = jQuery("#o37").text();
	//END OF VOYAGE TIME
	
	//BUNKER PRICING
	var c45 = jQuery("#c45").text();
	var d45 = jQuery("#d45").text();
	var e45 = jQuery("#e45").text();
	var g45 = jQuery("#g45").text();
	var h45 = jQuery("#h45").text();
	//END OF BUNKER PRICING
	
	//VOYAGE EXPENSES
	var c46 = jQuery("#c46").text();
	var d46 = jQuery("#d46").text();
	var e46 = jQuery("#e46").text();
	var f46 = jQuery("#f46").text();
	var g46 = jQuery("#g46").text();
	var h46 = jQuery("#h46").text();
	var i46 = jQuery("#i46").text();
	var c47 = jQuery("#c47").text();
	var g47 = jQuery("#g47").text();
	//END OF VOYAGE EXPENSES
	
	//DWCC
	var d19b = jQuery("#d19b").text();
	var d20b = jQuery("#d20b").text();
	var d21b = jQuery("#d21b").text();
	var d22b = jQuery("#d22b").text();
	var d25 = jQuery("#d25").text();
	var d26 = jQuery("#d26").text();
	//END OF DWCC
	
	//CANAL
	var ctotal1 = jQuery("#ctotal1").text();
	var ctotal2 = jQuery("#ctotal2").text();
	//END OF CANAL
	
	//PORTS
	var c54 = jQuery("#c54").text();
	var c54_2 = jQuery("#c54_2").text();
	var c54_3 = jQuery("#c54_3").text();
	var port1 = jQuery("#port1").text();
	var port2 = jQuery("#port2").text();
	var port3 = jQuery("#port3").text();
	var c66 = jQuery("#c66").text();
	var c67 = jQuery("#c67").text();
	var c68 = jQuery("#c68").text();
	//END OF PORTS
	
	//VOYAGE DISBURSMENTS
	var b74 = jQuery("#b74").text();
	var c74 = jQuery("#c74").text();
	var d74 = jQuery("#d74").text();
	var b75 = jQuery("#b75").text();
	//END OF VOYAGE DISBURSMENTS
	
	//FRIEGHT RATE
	var c80 = jQuery("#c80").text();
	var f80 = jQuery("#f80").text();
	var g80 = jQuery("#g80").text();
	var d81 = jQuery("#d81").text();
	var b85 = jQuery("#b85").text();
	var c85 = jQuery("#c85").text();
	var f85 = jQuery("#f85").text();
	var d86 = jQuery("#d86").text();
	//END OF FRIEGHT RATE
	
	jQuery("#misciframe")[0].src="misc/email_ve.php?"+data+'&f31='+f31+'&h31='+h31+'&c32='+c32+'&d32='+d32+'&e32='+e32+'&f32='+f32+'&g32='+g32+'&h32='+h32+'&c33='+c33+'&d33='+d33+'&f33='+f33+'&h33='+h33+'&c34='+c34+'&d34='+d34+'&f34='+f34+'&h34='+h34+'&c35='+c35+'&d35='+d35+'&e35='+e35+'&f35='+f35+'&g35='+g35+'&h35='+h35+'&r31='+r31+'&j32='+j32+'&l32='+l32+'&o32='+o32+'&o33='+o33+'&r34='+r34+'&j35='+j35+'&l35='+l35+'&o35='+o35+'&o36='+o36+'&t37='+t37+'&o37='+o37+'&c45='+c45+'&d45='+d45+'&e45='+e45+'&g45='+g45+'&h45='+h45+'&c46='+c46+'&d46='+d46+'&e46='+e46+'&f46='+f46+'&g46='+g46+'&h46='+h46+'&i46='+i46+'&c47='+c47+'&g47='+g47+'&d19b='+d19b+'&d20b='+d20b+'&d21b='+d21b+'&d22b='+d22b+'&d25='+d25+'&d26='+d26+'&ctotal1='+ctotal1+'&ctotal2='+ctotal2+'&c54='+c54+'&c54_2='+c54_2+'&c54_3='+c54_3+'&port1='+port1+'&port2='+port2+'&port3='+port3+'&c66='+c66+'&c67='+c67+'&c68='+c68+'&b74='+b74+'&c74='+c74+'&d74='+d74+'&b75='+b75+'&c80='+c80+'&f80='+f80+'&g80='+g80+'&d81='+d81+'&b85='+b85+'&c85='+c85+'&f85='+f85+'&d86='+d86;
	jQuery("#miscdialog").dialog("open");
}

function printItVe(){
	var data = jQuery('form').serialize();
	
	//VOYAGE LEGS
	var f31 = jQuery("#f31").text();
	var h31 = jQuery("#h31").text();
	var c32 = jQuery("#c32").text();
	var d32 = jQuery("#d32").text();
	var e32 = jQuery("#e32").text();
	var f32 = jQuery("#f32").text();
	var g32 = jQuery("#g32").text();
	var h32 = jQuery("#h32").text();
	var c33 = jQuery("#c33").text();
	var d33 = jQuery("#d33").text();
	var f33 = jQuery("#f33").text();
	var h33 = jQuery("#h33").text();
	var c34 = jQuery("#c34").text();
	var d34 = jQuery("#d34").text();
	var f34 = jQuery("#f34").text();
	var h34 = jQuery("#h34").text();
	var c35 = jQuery("#c35").text();
	var d35 = jQuery("#d35").text();
	var e35 = jQuery("#e35").text();
	var f35 = jQuery("#f35").text();
	var g35 = jQuery("#g35").text();
	var h35 = jQuery("#h35").text();
	//END OF VOYAGE LEGS
	
	//CARGO LEGS
	var r31 = jQuery("#r31").text();
	var j32 = jQuery("#j32").text();
	var l32 = jQuery("#l32").text();
	var o32 = jQuery("#o32").text();
	var o33 = jQuery("#o33").text();
	var r34 = jQuery("#r34").text();
	var j35 = jQuery("#j35").text();
	var l35 = jQuery("#l35").text();
	var o35 = jQuery("#o35").text();
	//END OF CARGO LEGS
	
	//VOYAGE TIME
	var o36 = jQuery("#o36").text();
	var t37 = jQuery("#t37").text();
	var o37 = jQuery("#o37").text();
	//END OF VOYAGE TIME
	
	//BUNKER PRICING
	var c45 = jQuery("#c45").text();
	var d45 = jQuery("#d45").text();
	var e45 = jQuery("#e45").text();
	var g45 = jQuery("#g45").text();
	var h45 = jQuery("#h45").text();
	//END OF BUNKER PRICING
	
	//VOYAGE EXPENSES
	var c46 = jQuery("#c46").text();
	var d46 = jQuery("#d46").text();
	var e46 = jQuery("#e46").text();
	var f46 = jQuery("#f46").text();
	var g46 = jQuery("#g46").text();
	var h46 = jQuery("#h46").text();
	var i46 = jQuery("#i46").text();
	var c47 = jQuery("#c47").text();
	var g47 = jQuery("#g47").text();
	//END OF VOYAGE EXPENSES
	
	//DWCC
	var d19b = jQuery("#d19b").text();
	var d20b = jQuery("#d20b").text();
	var d21b = jQuery("#d21b").text();
	var d22b = jQuery("#d22b").text();
	var d25 = jQuery("#d25").text();
	var d26 = jQuery("#d26").text();
	//END OF DWCC
	
	//CANAL
	var ctotal1 = jQuery("#ctotal1").text();
	var ctotal2 = jQuery("#ctotal2").text();
	//END OF CANAL
	
	//PORTS
	var c54 = jQuery("#c54").text();
	var c54_2 = jQuery("#c54_2").text();
	var c54_3 = jQuery("#c54_3").text();
	var port1 = jQuery("#port1").text();
	var port2 = jQuery("#port2").text();
	var port3 = jQuery("#port3").text();
	var c66 = jQuery("#c66").text();
	var c67 = jQuery("#c67").text();
	var c68 = jQuery("#c68").text();
	//END OF PORTS
	
	//VOYAGE DISBURSMENTS
	var b74 = jQuery("#b74").text();
	var c74 = jQuery("#c74").text();
	var d74 = jQuery("#d74").text();
	var b75 = jQuery("#b75").text();
	//END OF VOYAGE DISBURSMENTS
	
	//FRIEGHT RATE
	var c80 = jQuery("#c80").text();
	var f80 = jQuery("#f80").text();
	var g80 = jQuery("#g80").text();
	var d81 = jQuery("#d81").text();
	var b85 = jQuery("#b85").text();
	var c85 = jQuery("#c85").text();
	var f85 = jQuery("#f85").text();
	var d86 = jQuery("#d86").text();
	//END OF FRIEGHT RATE
	
	jQuery("#misciframe")[0].src="misc/print_ve.php?"+data+'&f31='+f31+'&h31='+h31+'&c32='+c32+'&d32='+d32+'&e32='+e32+'&f32='+f32+'&g32='+g32+'&h32='+h32+'&c33='+c33+'&d33='+d33+'&f33='+f33+'&h33='+h33+'&c34='+c34+'&d34='+d34+'&f34='+f34+'&h34='+h34+'&c35='+c35+'&d35='+d35+'&e35='+e35+'&f35='+f35+'&g35='+g35+'&h35='+h35+'&r31='+r31+'&j32='+j32+'&l32='+l32+'&o32='+o32+'&o33='+o33+'&r34='+r34+'&j35='+j35+'&l35='+l35+'&o35='+o35+'&o36='+o36+'&t37='+t37+'&o37='+o37+'&c45='+c45+'&d45='+d45+'&e45='+e45+'&g45='+g45+'&h45='+h45+'&c46='+c46+'&d46='+d46+'&e46='+e46+'&f46='+f46+'&g46='+g46+'&h46='+h46+'&i46='+i46+'&c47='+c47+'&g47='+g47+'&d19b='+d19b+'&d20b='+d20b+'&d21b='+d21b+'&d22b='+d22b+'&d25='+d25+'&d26='+d26+'&ctotal1='+ctotal1+'&ctotal2='+ctotal2+'&c54='+c54+'&c54_2='+c54_2+'&c54_3='+c54_3+'&port1='+port1+'&port2='+port2+'&port3='+port3+'&c66='+c66+'&c67='+c67+'&c68='+c68+'&b74='+b74+'&c74='+c74+'&d74='+d74+'&b75='+b75+'&c80='+c80+'&f80='+f80+'&g80='+g80+'&d81='+d81+'&b85='+b85+'&c85='+c85+'&f85='+f85+'&d86='+d86;
	jQuery("#miscdialog").dialog("open");
}
//END OF MAIL/PRINT VOYAGE ESTIMATOR PAGE

//MAIL/PRINT DETAILS
jQuery( "#miscdialog" ).dialog( { autoOpen: false, width: 1100, height: 500 });
jQuery( "#miscdialog" ).dialog("close");
//END OF MAIL/PRINT DETAILS

//SCENARIO FUNCTIONALITY
function saveScenario(){
	if(jQuery('#ship').val()){
		jQuery('#pleasewait').show();
	
		jQuery.ajax({
			type: "POST",
			url: "ajax.php?new_search=2",
			data: jQuery("#voyageestimatorform").serialize(),
	
			success: function(data) {
				alert("Scenario Saved!");
			
				self.location = "s-bis.php?new_search=3";
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
			url: "ajax.php?new_search=3&tabid="+tabid,
			data: jQuery("#voyageestimatorform").serialize(),
	
			success: function(data) {
				alert("Scenario Deleted!");
			
				self.location = "s-bis.php?new_search=3";
			}
		});
	}
}

function newScenario(){
	jQuery('#pleasewait').show();
	
	self.location = "s-bis.php?new_search=3";
}
//END OF SCENARIO FUNCTIONALITY
</script>

<!--SHOW SHIP DETAILS-->
<div id="shipdetails" title="SHIP DETAILS" style='display:none;'>
	<div id='shipdetails_in'></div>
</div>
<!--END OF SHOW SHIP DETAILS-->

<!--SHOW EDITABLE SHIP DETAILS-->
<div id="shipdetails2" title="USER'S SHIP DETAILS" style='display:none;'>
	<iframe id='shipdetailiframe' frameborder="0" height="100%" width="100%"></iframe>
</div>
<!--END OF SHOW EDITABLE SHIP DETAILS-->

<!--SHOW SHIP SPEED HISTORY-->
<div id="shipspeedhistory" title="SHIP SPEED HISTORY" style='display:none;'>
	<iframe id='shipspeedhistoryiframe' frameborder="0" height="100%" width="100%"></iframe>
</div>
<!--END OF SHOW SHIP SPEED HISTORY-->

<!--SHOW OWNER'S CONTACT DETAILS-->
<div id="contactdialog" title="CONTACT"  style='display:none'>
	<iframe id='contactiframe' frameborder="0" height="100%" width="100%"></iframe>
</div>
<!--END OF SHOW OWNER'S CONTACT DETAILS-->

<!--MAIL/PRINT DETAILS-->
<div id="miscdialog" title=""  style='display:none'>
	<iframe id='misciframe' frameborder='0' height="100%" width="1100px" style='border:0px; height:100%; width:1050px;'></iframe>
</div>
<!--END OF MAIL/PRINT DETAILS-->

<!--PORT DETAILS-->
<div id="portdetails" title="PORTS D/A CHARGES" style='display:none;'>
	<iframe id='portdetailsiframe' frameborder="0" height="100%" width="100%"></iframe>
</div>
<!--END OF PORT DETAILS-->

<?php
if(!isset($_GET['new_search']) || isset($_GET['tabid'])){
	if(isset($_GET['tabid'])){
		$sql = "SELECT * FROM `_user_tabs` WHERE `id`='".$_GET['tabid']."'";
		$r = dbQuery($sql, $link);
	}else{
		$sql = "SELECT * FROM `_user_tabs` WHERE `uid`='".$user['uid']."' AND `page`='voyageestimator' ORDER BY `dateadded` DESC LIMIT 0,1";
		$r = dbQuery($sql, $link);
	}
	
	if(trim($r)){
		$tabid = $r[0]['id'];
		$tabname = $r[0]['tabname'];
		$tabdata = unserialize($r[0]['tabdata']);
		
		$ship = $tabdata['ship'];

		//VOYAGE LEGS
		$c31 = $tabdata['c31'];
		$d31 = $tabdata['d31'];
		$e31 = $tabdata['e31'];
		$g31 = $tabdata['g31'];
		$e33 = $tabdata['e33'];
		$g33 = $tabdata['g33'];
		$e34 = $tabdata['e34'];
		$g34 = $tabdata['g34'];
		//END OF VOYAGE LEGS
		
		//CARGO LEGS
		$calendar = $tabdata['calendar'];
		$s31 = $tabdata['s31'];
		$t31 = $tabdata['t31'];
		$i32 = $tabdata['i32'];
		$k32 = $tabdata['k32'];
		$m32 = $tabdata['m32'];
		$n32 = $tabdata['n32'];
		$p32 = $tabdata['p32'];
		$q32 = $tabdata['q32'];
		$s32 = $tabdata['s32'];
		$t32 = $tabdata['t32'];
		$l33 = $tabdata['l33'];
		$m33 = $tabdata['m33'];
		$n33 = $tabdata['n33'];
		$p33 = $tabdata['p33'];
		$q33 = $tabdata['q33'];
		$s33 = $tabdata['s33'];
		$t33 = $tabdata['t33'];
		$s34 = $tabdata['s34'];
		$t34 = $tabdata['t34'];
		$i35 = $tabdata['i35'];
		$k35 = $tabdata['k35'];
		$m35 = $tabdata['m35'];
		$n35 = $tabdata['n35'];
		$p35 = $tabdata['p35'];
		$q35 = $tabdata['q35'];
		$s35 = $tabdata['s35'];
		$t35 = $tabdata['t35'];
		//END OF CARGO LEGS
		
		//BUNKER PRICING
		$d42 = $tabdata['d42'];
		$h42 = $tabdata['h42'];
		$d42_180 = $tabdata['d42_180'];
		$h42_mgo = $tabdata['h42_mgo'];
		$d42_lsifo380 = $tabdata['d42_lsifo380'];
		$h42_lsmgo = $tabdata['h42_lsmgo'];
		$d42_lsifo180 = $tabdata['d42_lsifo180'];
		$c44 = $tabdata['c44'];
		$d44 = $tabdata['d44'];
		$e44 = $tabdata['e44'];
		$g44 = $tabdata['g44'];
		$h44 = $tabdata['h44'];
		$f45 = $tabdata['f45'];
		$i45 = $tabdata['i45'];
		//END OF BUNKER PRICING
		
		//DWCC
		$d19 = $tabdata['d19'];
		$d20 = $tabdata['d20'];
		$d21 = $tabdata['d21'];
		$d22 = $tabdata['d22'];
		$d23 = $tabdata['d23'];
		$d24 = $tabdata['d24'];
		//END OF DWCC
		
		//CANAL
		$canal = $tabdata['canal'];
		$cbook1 = $tabdata['cbook1'];
		$cbook2 = $tabdata['cbook2'];
		$ctug1 = $tabdata['ctug1'];
		$ctug2 = $tabdata['ctug2'];
		$cline1 = $tabdata['cline1'];
		$cline2 = $tabdata['cline2'];
		$cmisc1 = $tabdata['cmisc1'];
		$cmisc2 = $tabdata['cmisc2'];
		//END OF CANAL
		
		//PORTS
		$c52 = $tabdata['c52'];
		$c52_2 = $tabdata['c52_2'];
		$c52_3 = $tabdata['c52_3'];
		$term = $tabdata['term'];
		$term2 = $tabdata['term2'];
		$term3 = $tabdata['term3'];
		$linerterms = $tabdata['linerterms'];
		$linerterms2 = $tabdata['linerterms2'];
		$linerterms3 = $tabdata['linerterms3'];
		$da_quick_input1 = $tabdata['da_quick_input1'];
		$da_quick_input2 = $tabdata['da_quick_input2'];
		$da_quick_input3 = $tabdata['da_quick_input3'];
		$laytime1 = $tabdata['laytime1'];
		$laytime2 = $tabdata['laytime2'];
		$laytime3 = $tabdata['laytime3'];
		$disbursments1 = $tabdata['disbursments1'];
		$disbursments2 = $tabdata['disbursments2'];
		$disbursments3 = $tabdata['disbursments3'];
		$dues1 = $tabdata['dues1'];
		$dues2 = $tabdata['dues2'];
		$dues3 = $tabdata['dues3'];
		$pilotage1 = $tabdata['pilotage1'];
		$pilotage2 = $tabdata['pilotage2'];
		$pilotage3 = $tabdata['pilotage3'];
		$tugs1 = $tabdata['tugs1'];
		$tugs2 = $tabdata['tugs2'];
		$tugs3 = $tabdata['tugs3'];
		$bunkeradjustment1 = $tabdata['bunkeradjustment1'];
		$bunkeradjustment2 = $tabdata['bunkeradjustment2'];
		$bunkeradjustment3 = $tabdata['bunkeradjustment3'];
		$mooring1 = $tabdata['mooring1'];
		$mooring2 = $tabdata['mooring2'];
		$mooring3 = $tabdata['mooring3'];
		$dockage1 = $tabdata['dockage1'];
		$dockage2 = $tabdata['dockage2'];
		$dockage3 = $tabdata['dockage3'];
		$loaddischarge1 = $tabdata['loaddischarge1'];
		$loaddischarge2 = $tabdata['loaddischarge2'];
		$loaddischarge3 = $tabdata['loaddischarge3'];
		$agencyfee1 = $tabdata['agencyfee1'];
		$agencyfee2 = $tabdata['agencyfee2'];
		$agencyfee3 = $tabdata['agencyfee3'];
		$miscellaneous1 = $tabdata['miscellaneous1'];
		$miscellaneous2 = $tabdata['miscellaneous2'];
		$miscellaneous3 = $tabdata['miscellaneous3'];
		//END OF PORTS
		
		//VOYAGE DISBURSMENTS
		$e74 = $tabdata['e74'];
		$f74 = $tabdata['f74'];
		$g74 = $tabdata['g74'];
		$h74 = $tabdata['h74'];
		$i74 = $tabdata['i74'];
		$j74 = $tabdata['j74'];
		//END OF VOYAGE DISBURSMENTS
		
		//FREIGHT RATE
		$b80 = $tabdata['b80'];
		$d80 = $tabdata['d80'];
		$e80 = $tabdata['e80'];
		//END OF FREIGHT RATE
		
		//TCE
		$d85 = $tabdata['d85'];
		$e85 = $tabdata['e85'];
		$g85 = $tabdata['g85'];
		//END OF TCE
	}
}

if(!trim($d80)){
	$d80 = "1.25";
}

if(!trim($e80)){
	$e80 = "2.50";
}

if(!trim($d85)){
	$d85 = "1.25";
}

if(!trim($e85)){
	$e85 = "2.50";
}
?>

<form method="post" id="voyageestimatorform" name="voyageestimatorform" enctype="multipart/form-data">
<table width="1300" border="0" cellspacing="0" cellpadding="0">
  <tr>
  	<td style="border-bottom:none;">
		<table width="1300" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="125" style="border-bottom:none;"><input type="button" value="Save Scenario" onclick="saveScenario();" style="border:1px solid #666666; background-color:#333333; color:#FFFFFF; cursor:pointer; padding:5px 10px;" /></td>
				<td width="50" style="border-bottom:none;"><a class='clickable' onclick="printItVe();"><img src='images/print.jpg'></a></td>
				<td style="border-bottom:none;"><a class='clickable' onclick="mailItVe();"><img src='images/email_small.jpg'></a></td>
			</tr>
		</table>
	</td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  </tr>
  
	<?php
	$sql = "SELECT * FROM `_user_tabs` WHERE `uid`='".$user['uid']."' AND `page`='voyageestimator' ORDER BY `dateadded` DESC";
	$r = dbQuery($sql, $link);
	
	$t = count($r);
	
	if(trim($t)){
		echo '<tr>';
		echo '<td style="border-bottom:none; padding-top:10px;">';
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
						echo '<div onclick="location.href=\'s-bis.php?new_search=3&tabid='.$r[$i]['id'].'\'" class="clickable" style="float:left; width:auto; height:auto; color:#FFF;">'.$r[$i]['tabname'].'</div>';
						echo '</div>';
					}
				}else{
					if($i==0){
						if(isset($_GET['new_search'])){
							if($_GET['new_search']==3){
								echo '<div style="float:left; width:auto; height:auto; background-color:#666; color:#FFF; padding:5px 10px; border:1px solid #000;">';
								echo '<div style="float:left; width:15px; height:auto;"><img src="images/close.png" width="14" height="14" border="0" alt="Delete this scenario" title="Delete this scenario" style="cursor:pointer;" onclick="deleteScenario(\''.$r[$i]['id'].'\');" /></div>';
								echo '<div onclick="location.href=\'s-bis.php?new_search=3&tabid='.$r[$i]['id'].'\'" class="clickable" style="float:left; width:auto; height:auto; color:#FFF;">'.$r[$i]['tabname'].'</div>';
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
						echo '<div onclick="location.href=\'s-bis.php?new_search=3&tabid='.$r[$i]['id'].'\'" class="clickable" style="float:left; width:auto; height:auto; color:#FFF;">'.$r[$i]['tabname'].'</div>';
						echo '</div>';
					}
				}
			}
			
			echo '<div style="float:left; width:auto; height:auto;">&nbsp;&nbsp;</div>';
		}
		
		echo '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td>&nbsp;</td>';
		echo '</tr>';
	}
	?>
	
  <tr>
  	<td style="border-bottom:none;">&nbsp;</td>
  </tr>
</table>
<table width="1300" border="0" cellspacing="0" cellpadding="0">
  <tr>
  	<td width="1000">
		<table width="1000" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td class="text_1"><div style="padding:3px;"><span style="color:#FF0000; font-weight:bold; font-size:14px;">*</span> <span style="color:#FF0000; font-size:12px;">- REQUIRED FIELDS</span><br />&nbsp;</div></td>
		  </tr>
		  <tr bgcolor="cddee5">
			<td class="text_1"><div style="padding:3px;"><b>VESSEL NAME / IMO #</b> &nbsp; <input type="hidden" id="tabid" name="tabid" value="<?php echo $tabid; ?>" /><input type="text" id="ship" name="ship" class="input_1" style="max-width:300px; width:300px; border:1px solid #FF0000;" value="<?php echo $ship; ?>" /> <span style="color:#FF0000; font-weight:bold; font-size:14px;">*</span> &nbsp; <span id='shipdetailshref' style="color:#F00;"></span></div></td>
		  </tr>
		</table>
		<div id="ship_info" style="display:none;">
			<table width="1000" border="0" cellspacing="0" cellpadding="0">
			  <tr bgcolor="f5f5f5">
				<td width="110" valign="top"><div style="padding:3px;"><b>IMO</b> #</div></td>
				<td width="160" valign="top" style="padding:3px;" id="ship_imo"></td>
				<td width="105" valign="top"><div style="padding:3px;"><b>LOA</b></div></td>
				<td width="100" valign="top" style="padding:3px;" id="ship_loa"></td>
				<td width="145" valign="top"><div style="padding:3px;"><b>Grain</b></div></td>
				<td width="160" valign="top" style="padding:3px;" id="ship_grain"></td>
				<td width="120" valign="top"><div style="padding:3px;"><b>Class Notation</b></div></td>
				<td width="100" valign="top" style="padding:3px;" id="ship_class_notation"></td>
			  </tr>
			  <tr bgcolor="e9e9e9">
				<td valign="top"><div style="padding:3px;"><b>Summer DWT</b></div></td>
				<td valign="top" style="padding:3px;" id="ship_summer_dwt"></td>
				<td valign="top"><div style="padding:3px;"><b>Draught</b></div></td>
				<td valign="top" style="padding:3px;" id="ship_draught"></td>
				<td valign="top"><div style="padding:3px;"><b>Lifting Equipment</b></div></td>
				<td valign="top" style="padding:3px;" id="ship_lifting_equipment"></td>
				<td valign="top"><div style="padding:3px;"><b>Fuel Oil</b></div></td>
				<td valign="top" style="padding:3px;" id="ship_fuel_oil"></td>
			  </tr>
			  <tr bgcolor="f5f5f5">
				<td valign="top"><div style="padding:3px;"><b>Gross Tonnage</b></div></td>
				<td valign="top" style="padding:3px;" id="ship_gross_tonnage"></td>
				<td valign="top"><div style="padding:3px;"><b>Net Tonnage</b></div></td>
				<td valign="top" style="padding:3px;" id="ship_net_tonnage"></td>
				<td valign="top"><div style="padding:3px; color:#FF0000;"><b>Speed</b></div></td>
				<td valign="top" style="padding:3px; color:#FF0000;" id="ship_speed"></td>
				<td valign="top"><div style="padding:3px;"><b>Cargo Handling</b></div></td>
				<td valign="top" style="padding:3px;" id="ship_cargo_handling"></td>
			  </tr>
			  <tr bgcolor="e9e9e9">
			  	<td valign="top"><div style="padding:3px;"><b>Fuel</b></div></td>
				<td valign="top" style="padding:3px;" id="ship_fuel"></td>
				<td valign="top"><div style="padding:3px;"><b>Built Year</b></div></td>
				<td valign="top" style="padding:3px;" id="ship_built_year"></td>
				<td valign="top"><div style="padding:3px; color:#FF0000;"><b>Speed AIS</b></div></td>
				<td valign="top" style="padding:3px; color:#FF0000;" id="ship_speed_ais"></td>
				<td valign="top"><div style="padding:3px;"><b>Breadth</b></div></td>
				<td valign="top" style="padding:3px;" id="ship_breadth"></td>
			  </tr>
			  <tr bgcolor="f5f5f5">
			  	<td valign="top"><div style="padding:3px;"><b>Decks Number</b></div></td>
				<td valign="top" style="padding:3px;" id="ship_decks_number"></td>
			  	<td valign="top"><div style="padding:3px;"><b>Fuel Consumption</b></div></td>
				<td valign="top" style="padding:3px;" id="ship_fuel_consumption"></td>
				<td valign="top"><div style="padding:3px; color:#FF0000;"><b>Movement Status</b></div></td>
				<td valign="top" style="padding:3px; color:#FF0000;" id="ship_NavigationalStatus"></td>
				<td valign="top"><div style="padding:3px;"><b>Bale</b></div></td>
				<td valign="top" style="padding:3px;" id="ship_bale"></td>
			  </tr>
			  <tr bgcolor="e9e9e9">
			  	<td valign="top"><div style="padding:3px;"><b>Cranes</b></div></td>
				<td valign="top" style="padding:3px;" id="ship_cranes"></td>
			  	<td valign="top"><div style="padding:3px;"><b>Bulkheads</b></div></td>
				<td valign="top" style="padding:3px;" id="ship_bulkheads"></td>
				<td valign="top"><div style="padding:3px; color:#FF0000;"><b>AIS Date Updated</b></div></td>
				<td valign="top" style="padding:3px; color:#FF0000;" id="ship_aisdateupdated"></td>
			  	<td valign="top"><div style="padding:3px;"><b>Fuel Type</b></div></td>
				<td valign="top" style="padding:3px;" id="ship_fuel_type"></td>
			  </tr>
			  <tr bgcolor="f5f5f5">
			  	<td valign="top"><div style="padding:3px;"><b>Manager Owner</b></div></td>
				<td valign="top" style="padding:3px;" id="ship_manager_owner"></td>
			  	<td valign="top"><div style="padding:3px;"><b>Manager Owner Email</b></div></td>
				<td valign="top" style="padding:3px;" id="ship_manager_owner_email"></td>
			  	<td valign="top"><div style="padding:3px;"><b>Class Society</b></div></td>
				<td valign="top" style="padding:3px;" id="ship_class_society"></td>
			  	<td valign="top"><div style="padding:3px;"><b>Largest Hatch</b></div></td>
				<td valign="top" style="padding:3px;" id="ship_largest_hatch"></td>
			  </tr>
			  <tr bgcolor="e9e9e9">
			  	<td valign="top"><div style="padding:3px;"><b>Holds</b></div></td>
				<td valign="top" style="padding:3px;" id="ship_holds"></td>
			  	<td valign="top"><div style="padding:3px;"><b>Flag</b></div></td>
				<td valign="top" style="padding:3px;" id="ship_flag"></td>
			  	<td valign="top"><div style="padding:3px;"><b>&nbsp;</b></div></td>
				<td valign="top" style="padding:3px;">&nbsp;</td>
				<td valign="top"><div style="padding:3px;"><b>&nbsp;</b></div></td>
				<td valign="top" style="padding:3px;">&nbsp;</td>
			  </tr>
			</table>
		</div>
		
		<div style="border-bottom:3px dotted #fff;">&nbsp;</div>
		<div>&nbsp;</div>
		
		<table width="1000" border="0" cellspacing="0" cellpadding="0">
		  <tr bgcolor="cddee5">
			<td class="text_1" colspan="9"><div style="padding:3px;"><b>VOYAGE LEGS</b></div></td>
		  </tr>
		  <tr>
			<td width="100" class="text_1 label"><div style="padding:3px;"><i><strong>Type</strong></i></div></td>
			<td width="200" class="text_1 label"><div style="padding:3px;"><i><strong>Port</strong></i></div></td>
			<td width="140" class="text_1 label"><div style="padding:3px;"><i><strong>Date</strong></i></div></td>
			<td width="180" class="text_1 label"><div style="padding:3px;"><i><strong>Port</strong></i></div></td>
			<td width="100" class="text_1 label"><div style="padding:3px;"><i><strong>Date</strong></i></div></td>
			<td width="100" class="text_1 label"><div style="padding:3px;"><i><strong>Speed (knts)</strong></i></div></td>
			<td width="80" class="text_1 label"><div style="padding:3px;"><i><strong>Distance (miles)</strong></i></div></td>
			<td width="50" class="text_1 label"><div style="padding:3px;"><i><strong>Input %</strong></i></div></td>
			<td width="50" class="text_1 label"><div style="padding:3px;"><i><strong>% Sea Margin</strong></i></div></td>
		  </tr>
		  <tr id='ballast1' bgcolor="f5f5f5">
			<td class='general b31' style="padding:3px;"><strong>Ballast</strong></td>
			<td class='input'><div style="padding:3px;"><input type='text' class='input_1 general c31' id="c31" name="c31" value="<?php echo $c31; ?>" style="max-width:190px; border:1px solid #FF0000;" /> <span style="color:#FF0000; font-weight:bold; font-size:14px;">*</span></div></td>
			<td class="input"><div style="padding:3px;"><input type='text' class='input_1 general d31' id="d31" name="d31" value="<?php echo $d31; ?>" style="max-width:120px; border:1px solid #FF0000;" /> <span style="color:#FF0000; font-weight:bold; font-size:14px;">*</span></div></td>
			<td class='input'><div style="padding:3px;"><input type='text' class='input_1 general e31' id="e31" name="e31" value="<?php echo $e31; ?>" style="max-width:190px; border:1px solid #FF0000;" /> <span style="color:#FF0000; font-weight:bold; font-size:14px;">*</span></div></td>
			<td class='calculated general f31' id="f31" style="padding:3px;"></td>
			<td class='input'><div style="padding:3px;"><input type='text' class='input_1 number g31' id="g31" name="g31" value="<?php echo $g31; ?>" style="max-width:70px;" /></div></td>
			<td class="calculated number h31" id="h31" style="padding:3px;"></td>
			<td class='input'><div style="padding:3px;"><input type='text' class='input_1 number i31' id="i31" name="i31" value="<?php echo $i31; ?>" style="max-width:30px;" onchange="computeDistanceMiles1(this.value);" /></div></td>
			<td class="calculated number i31x" id="i31x" style="padding:3px;"></td>
		  </tr>
		  <tr id='loading1' bgcolor="e9e9e9">
			<td class='general b32' style="padding:3px;"><strong>Loading</strong></td>
			<td class='general c32' id="c32" style="padding:3px;"></td>
			<td class='general d32' id="d32" style="padding:3px;"></td>
			<td class='general e32' id="e32" style="padding:3px;"></td>
			<td class="calculated f32" id="f32" style="padding:3px;"></td>
			<td class='number g32' id="g32" style="padding:3px;"></td>
			<td class="number h32" id="h32" style="padding:3px;"></td>
			<td><div style="padding:3px;">&nbsp;</div></td>
			<td><div style="padding:3px;">&nbsp;</div></td>
		  </tr>
		  <tr id='bunkerstop1' bgcolor="f5f5f5">
			<td class='general b33' style="padding:3px;"><strong>Bunker Stop</strong></td>
			<td id="c33" class='input general c33' style="padding:3px;"></td>
			<td id="d33" class='general d33' style="padding:3px;"></td>
			<td class='input' style="padding:3px;"><input type='text' class='input_1 general e33' id="e33" name="e33" value="<?php echo $e33; ?>"  style="max-width:190px; border:1px solid #FF0000;" /> <span style="color:#FF0000; font-weight:bold; font-size:14px;">*</span></td>
			<td id="f33" class="calculated f33" style="padding:3px;"></td>
			<td class='input' style="padding:3px;"><input type='text' class='input_1 number g33' id="g33" name="g33" value="<?php echo $g33; ?>"  style="max-width:70px;" /></td>
			<td id="h33" class="calculated h33" style="padding:3px;"></td>
			<td class='input'><div style="padding:3px;"><input type='text' class='input_1 number i33' id="i33" name="i33" value="<?php echo $i33; ?>" style="max-width:30px;" onchange="computeDistanceMiles2(this.value);" /></div></td>
			<td class="calculated number i33x" id="i33x" style="padding:3px;"></td>
		  </tr>
		  <tr id='laden1' bgcolor="e9e9e9">
			<td class='general b34' style="padding:3px;"><strong>Laden</strong></td>
			<td id="c34" class='input general c34' style="padding:3px;"></td>
			<td id="d34" class='general d34' style="padding:3px;"></td>
			<td class='input' style="padding:3px;"><input type='text' class='input_1 general e34' id="e34" name="e34" value="<?php echo $e34; ?>" style="max-width:190px; border:1px solid #FF0000;" /> <span style="color:#FF0000; font-weight:bold; font-size:14px;">*</span></td>
			<td id="f34" class="calculated f34" style="padding:3px;"></td>
			<td class='input' style="padding:3px;"><input type='text' class='input_1 number g34' id="g34" name="g34" value="<?php echo $g34; ?>" style="max-width:70px;" /></td>
			<td id="h34" class="calculated number h34" style="padding:3px;"></td>
			<td class='input'><div style="padding:3px;"><input type='text' class='input_1 number i34' id="i34" name="i34" value="<?php echo $i34; ?>" style="max-width:30px;" onchange="computeDistanceMiles3(this.value);" /></div></td>
			<td class="calculated number i34x" id="i34x" style="padding:3px;"></td>
		  </tr>
		  <tr id='discharging1' bgcolor="f5f5f5">
			<td class='general b35' style="padding:3px;"><strong>Discharging</strong></td>
			<td id="c35" class='input general c35' style="padding:3px;"></td>
			<td id="d35" class='general d35' style="padding:3px;"></td>
			<td id="e35" class='general e35' style="padding:3px;"></td>
			<td id="f35" class="calculated f35" style="padding:3px;"></td>
			<td id="g35" class='number g35' style="padding:3px;"></td>
			<td id="h35" class="number h35" style="padding:3px;"></td>
			<td><div style="padding:3px;">&nbsp;</div></td>
			<td><div style="padding:3px;">&nbsp;</div></td>
		  </tr>
		  <tr id='repositioning1' bgcolor="e9e9e9">
			<td class='general b36' style="padding:3px;"><strong>Repositioning</strong></td>
			<td id="c36" class='input general c36' style="padding:3px;"></td>
			<td id="d36" class='general d36' style="padding:3px;"></td>
			<td class='input' style="padding:3px;"><input type='text' class='input_1 general e36' id="e36" name="e36" value="<?php echo $e36; ?>" style="max-width:190px; border:1px solid #FF0000;" /> <span style="color:#FF0000; font-weight:bold; font-size:14px;">*</span></td>
			<td id="f36" class="calculated f36" style="padding:3px;"></td>
			<td class='input' style="padding:3px;"><input type='text' class='input_1 number g36' id="g36" name="g36" value="<?php echo $g36; ?>" style="max-width:70px;" /></td>
			<td id="h36" class="calculated number h36" style="padding:3px;"></td>
			<td class='input'><div style="padding:3px;"><input type='text' class='input_1 number i36' id="i36" name="i36" value="<?php echo $i36; ?>" style="max-width:30px;" onchange="computeDistanceMiles4(this.value);" /></div></td>
			<td class="calculated number i36x" id="i36x" style="padding:3px;"></td>
		  </tr>
		</table>
		
		<div style="border-bottom:3px dotted #fff;">&nbsp;</div>
		<div>&nbsp;</div>
		
		<table width="1000" border="0" cellspacing="0" cellpadding="0">
		  <tr bgcolor="cddee5">
			<td class="text_1" colspan="2"><div style="padding:3px;"><b>CARGO LEGS</b></div></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td class="text_1" colspan="2"><div style="padding:3px;"><b>* Option to Load &amp; Bunker concurrently</b></div></td>
			<td class="text_1" colspan="3"><div style="padding:3px;"><b>Port Days</b></div></td>
			<td class="text_1" colspan="3"><div style="padding:3px;"><b>Sea Days</b></div></td>
		  </tr>
		  <tr>
			<td width="71" class="text_1 label"><div style="padding:3px;"><i><strong>Type</strong></i></div></td>
			<td width="102" class="text_1 label"><div style="padding:3px;"><i><strong>Cargo</strong></i></div></td>
			<td width="18" class="text_1 label"><div style="padding:3px;"><i><strong>SF</strong></i></div></td>
			<td width="102" class="text_1 label"><div style="padding:3px;"><i><strong>Quantity (MT)</strong></i></div></td>
			<td width="102" class="text_1 label"><div style="padding:3px;"><i><strong>Volume (M3)</strong></i></div></td>
			<td width="102" class="text_1 label"><div style="padding:3px;"><i><strong>L/D Rate (MT/day)</strong></i></div></td>
			<td width="45" class="text_1 label"><div style="padding:3px;"><i><strong>Load Days</strong></i></div></td>
			<td width="167" class="text_1 label"><div style="padding:3px;"><i><strong>Working Days TERMS</strong></i></div></td>
			<td width="102" class="text_1 label"><div style="padding:3px; color:#FF0000;"><i><strong>Working Aditional Days TERMS</strong></i></div></td>
			<td width="102" class="text_1 label"><div style="padding:3px;"><i><strong>Turn/Idle/Extra Days</strong></i></div></td>
			<td width="7" class="text_1 label"><div style="padding:3px;"><i><strong>Voyage Days</strong></i></div></td>
			<td width="102" class="text_1 label"><div style="padding:3px;"><i><strong>Canal Days</strong></i></div></td>
			<td width="108" class="text_1 label"><div style="padding:3px;"><i><strong>Weather/Extra Days</strong></i></div></td>
		  </tr>
		  <tr id='ballast1' bgcolor="f5f5f5">
			<td class='general b31' style="padding:3px;"><strong>Ballast</strong></td>
			<td class='number i31' style="padding:3px;"></td>
			<td class='number j31' style="padding:3px;"></td>	
			<td class='number k31' style="padding:3px;"></td>
			<td class='number l31' style="padding:3px;"></td>
			<td class='number m31' style="padding:3px;"></td>
			<td class='number n31' style="padding:3px;"></td>
			<td style="padding:3px; color:#FF0000;"><a title="Please check the WORKING DAYS Calendar to ensure the Working Days TERMS are complied with. ADD additional days to compensate for the TERMS">Working Days Calendar</a></td>
			<td style="padding:3px;">
				<?php
				if($calendar){
					?><input type='text' class='input_1 general calendar' id="calendar" name="calendar" value="<?php echo $calendar; ?>" style="max-width:50px; border:1px solid #FF0000;" /><?php
				}else{
					?><input type='text' class='input_1 general calendar' id="calendar" name="calendar" value="<?php echo date('d/m/Y, l'); ?>" style="max-width:50px; border:1px solid #FF0000;" /><?php
				}
				?>
			</td>
			<td class='number q31' style="padding:3px;"></td>
			<td id="r31" class="calculated number r31" style="padding:3px;"></td>
			<td class='empty' style="padding:3px;"><input type='text' class='input_1 number s31' id="s31" name="s31" value="<?php echo $s31; ?>" style="max-width:50px;" /></td>
			<td class='empty' style="padding:3px;"><input type='text' class='input_1 number t31' id="t31" name="t31" value="<?php echo $t31; ?>" style="max-width:50px;" /></td>
		  </tr>
		  <tr id='loading1' bgcolor="e9e9e9">
			<td class='general b32' style="padding:3px;"><strong>Loading</strong></td>
			<td class='input' style="padding:3px;"><input type='text' class='input_1 general i32' id="i32" name="i32" value="<?php echo $i32; ?>" style="max-width:100px; border:1px solid #FF0000;" /><!-- <span style="color:#FF0000; font-weight:bold; font-size:14px;">*</span>--></td>
			<td id="j32" class='number j32' style="padding:3px;"></td>
			<td class='input' style="padding:3px;"><input type='text' class='input_1 number k32' id="k32" name="k32" value="<?php echo $k32; ?>" style="max-width:50px; border:1px solid #FF0000;" onblur="populatek35(this.value);" /> <span style="color:#FF0000; font-weight:bold; font-size:14px;">*</span></td>
			<td id="l32" class='calculated number l32' style="padding:3px;"></td>
			<td class='input' style="padding:3px;"><input type='text' class='input_1 number m32' id="m32" name="m32" value="<?php echo $m32; ?>" style="max-width:50px; border:1px solid #FF0000;" /> <span style="color:#FF0000; font-weight:bold; font-size:14px;">*</span></td>
			<td id="o32" class='calculated number o32' style="padding:3px;"></td>
			<td class='input' style="padding:3px;">
				<?php
				$n32arr = array(
							1=>"SHINC", 
							2=>"SATSHINC or SSHINC", 
							3=>"SHEX", 
							4=>"SA/SHEX or SATPMSHEX", 
							5=>"SHEXEIU or SHEXEIUBE or SHEXUU", 
							6=>"FHINC", 
							7=>"FHEX"
						);
						
				$n32t = count($n32arr);
				?>
				<select class='input_1 general n32' id="n32" name="n32" style="max-width:80px; min-width:80px;">
					<?php
					for($n32i=1; $n32i<=$n32t; $n32i++){
						if($n32arr[$n32i]==$n32){
							echo '<option value="'.$n32arr[$n32i].'" selected="selected">'.$n32arr[$n32i].'</option>';
						}else{
							echo '<option value="'.$n32arr[$n32i].'">'.$n32arr[$n32i].'</option>';
						}
					}
					?>
				</select>
			</td>
			<td class='input' style="padding:3px;"><input type='text' class='input_1 number p32' id="p32" name="p32" value="<?php echo $p32; ?>" style="max-width:50px; border:1px solid #FF0000;" /></td>
			<td class='input' style="padding:3px;"><input type='text' class='input_1 number q32' id="q32" name="q32" value="<?php echo $q32; ?>" style="max-width:50px;" /></td>
			<td class="number r32" style="padding:3px;"></td>
			<td class='empty' style="padding:3px;"><input type='text' class='input_1 number s32' id="s32" name="s32" value="<?php echo $s32; ?>" style="max-width:50px;" /></td>
			<td class='empty' style="padding:3px;"><input type='text' class='input_1 number t32' id="t32" name="t32" value="<?php echo $t32; ?>" style="max-width:50px;" /></td>
		  </tr>
		  <tr id='bunkerstop1' bgcolor="f5f5f5">
			<td class='general b33' style="padding:3px;"><strong>Bunker Stop</strong></td>
			<td class='number i33' style="padding:3px;"></td>
			<td class='number j33' style="padding:3px;"></td>
			<td class='number k33' style="padding:3px;"></td>
			<td class='input' style="padding:3px;"><input type='text' class='input_1 number l33' id="l33" name="l33" value="<?php echo $l33; ?>" style="max-width:50px;"  /></td>
			<td class='input' style="padding:3px;"><input type='text' class='input_1 number m33' id="m33" name="m33" value="<?php echo $m33; ?>" style="max-width:50px;" /></td>
			<td id="o33" class='calculated number o33' style="padding:3px;"></td>
			<td class='input' style="padding:3px;">
				<?php
				$n33arr = array(
							1=>"SHINC", 
							2=>"SATSHINC or SSHINC", 
							3=>"SHEX", 
							4=>"SA/SHEX or SATPMSHEX", 
							5=>"SHEXEIU or SHEXEIUBE or SHEXUU", 
							6=>"FHINC", 
							7=>"FHEX"
						);
						
				$n33t = count($n33arr);
				?>
				<select class='input_1 general n33' id="n33" name="n33" style="max-width:80px; min-width:80px;">
					<?php
					for($n33i=1; $n33i<=$n33t; $n33i++){
						if($n33arr[$n33i]==$n33){
							echo '<option value="'.$n33arr[$n33i].'" selected="selected">'.$n33arr[$n33i].'</option>';
						}else{
							echo '<option value="'.$n33arr[$n33i].'">'.$n33arr[$n33i].'</option>';
						}
					}
					?>
				</select>
			</td>
			<td class='input' style="padding:3px;"><input type='text' class='input_1 number p33' id="p33" name="p33" value="<?php echo $p33; ?>" style="max-width:50px; border:1px solid #FF0000;" /></td>
			<td class='input' style="padding:3px;"><input type='text'  class='input_1 number q33' id="q33" name="q33" value="<?php echo $q33; ?>" style="max-width:50px;"  /></td>
			<td id="r33" class="calculated number r33" style="padding:3px;"></td>
			<td class='empty' style="padding:3px;"><input type='text'  class='input_1 number s33' id="s33" name="s33" value="<?php echo $s33; ?>" style="max-width:50px;" /></td>
			<td class='empty' style="padding:3px;"><input type='text'  class='input_1 number t33' id="t33" name="t33" value="<?php echo $t33; ?>" style="max-width:50px;" /></td>
		  </tr>
		  <tr id='laden1' bgcolor="e9e9e9">
			<td class='general b34' style="padding:3px;"><strong>Laden</strong></td>
			<td class='number i34' style="padding:3px;"></td>
			<td class='number j34' style="padding:3px;"></td>
			<td class='number k34' style="padding:3px;"></td>
			<td class='number l34' style="padding:3px;"></td>
			<td class='number m34' style="padding:3px;"></td>
			<td class="number o34" style="padding:3px;"></td>
			<td class='number n34' style="padding:3px;"></td>
			<td class='number p34' style="padding:3px;"></td>
			<td class='number q34' style="padding:3px;"></td>
			<td id="r34" class="calculated number r34" style="padding:3px;"></td>
			<td class='empty' style="padding:3px;"><input type='text' class='input_1 number s34' id="s34" name="s34" value="<?php echo $s34; ?>" style="max-width:50px;" /></td>
			<td class='empty' style="padding:3px;"><input type='text' class='input_1 number t34' id="t34" name="t34" value="<?php echo $t34; ?>" style="max-width:50px;" /></td>
		  </tr>
		  <tr id='discharging1' bgcolor="f5f5f5">
			<td class='general b35' style="padding:3px;"><strong>Discharging</strong></td>
			<td class='input' style="padding:3px;"><input type='text' class='input_1 general i35' id="i35" name="i35" value="<?php echo $i35; ?>" style="max-width:100px; border:1px solid #FF0000;" /><!-- <span style="color:#FF0000; font-weight:bold; font-size:14px;">*</span>--></td>
			<td id="j35" class='number j35' style="padding:3px;"></td>
			<td class='input' style="padding:3px;"><input type='text' class='input_1 number k35' id="k35" name="k35" value="<?php echo $k35; ?>" style="max-width:50px; border:1px solid #FF0000;" /> <span style="color:#FF0000; font-weight:bold; font-size:14px;">*</span></td>
			<td id="l35" class='calculated number l35' style="padding:3px;"></td>
			<td class='input' style="padding:3px;"><input type='text'  class='input_1 number m35' id="m35" name="m35" value="<?php echo $m35; ?>" style="max-width:50px; border:1px solid #FF0000;" /> <span style="color:#FF0000; font-weight:bold; font-size:14px;">*</span></td>
			<td id="o35" class='calculated number o35' style="padding:3px;"></td>
			<td class='input' style="padding:3px;">
				<?php
				$n35arr = array(
							1=>"SHINC", 
							2=>"SATSHINC or SSHINC", 
							3=>"SHEX", 
							4=>"SA/SHEX or SATPMSHEX", 
							5=>"SHEXEIU or SHEXEIUBE or SHEXUU", 
							6=>"FHINC", 
							7=>"FHEX"
						);
						
				$n35t = count($n35arr);
				?>
				<select class='input_1 general n35' id="n35" name="n35" style="max-width:80px; min-width:80px;">
					<?php
					for($n35i=1; $n35i<=$n35t; $n35i++){
						if($n35arr[$n35i]==$n35){
							echo '<option value="'.$n35arr[$n35i].'" selected="selected">'.$n35arr[$n35i].'</option>';
						}else{
							echo '<option value="'.$n35arr[$n35i].'">'.$n35arr[$n35i].'</option>';
						}
					}
					?>
				</select>
			</td>
			<td class='input' style="padding:3px;"><input type='text' class='input_1 number p35' id="p35" name="p35" value="<?php echo $p35; ?>" style="max-width:50px; border:1px solid #FF0000;" /></td>
			<td class='input' style="padding:3px;"><input type='text'  class='input_1 number q35' id="q35" name="q35" value="<?php echo $q35; ?>" style="max-width:50px;" /></td>
			<td class="number r35" style="padding:3px;"></td>
			<td class='empty' style="padding:3px;"><input type='text' class='input_1 number s35' id="s35" name="s35" value="<?php echo $s35; ?>" style="max-width:50px;" /></td>
			<td class='empty' style="padding:3px;"><input type='text'  class='input_1 number t35' id="t35" name="t35" value="<?php echo $t35; ?>" style="max-width:50px;" /></td>
		  </tr>
		  <tr id='repositioning1' bgcolor="e9e9e9">
			<td class='general b36' style="padding:3px;"><strong>Repositioning</strong></td>
			<td class='number i36' style="padding:3px;"></td>
			<td class='number j36' style="padding:3px;"></td>
			<td class='number k36' style="padding:3px;"></td>
			<td class='number l36' style="padding:3px;"></td>
			<td class='number m36' style="padding:3px;"></td>
			<td class="number o36" style="padding:3px;"></td>
			<td class='number n36' style="padding:3px;"></td>
			<td class='number p36' style="padding:3px;"></td>
			<td class='number q36' style="padding:3px;"></td>
			<td id="r36" class="calculated number r36" style="padding:3px;"></td>
			<td class='empty' style="padding:3px;"><input type='text' class='input_1 number s36' id="s36" name="s36" value="<?php echo $s36; ?>" style="max-width:50px;" /></td>
			<td class='empty' style="padding:3px;"><input type='text' class='input_1 number t36' id="t36" name="t36" value="<?php echo $t36; ?>" style="max-width:50px;" /></td>
		  </tr>
		</table>
		
		<div style="border-bottom:3px dotted #fff;">&nbsp;</div>
		<div>&nbsp;</div>
		
		<table width="1000" border="0" cellspacing="0" cellpadding="0">
		  <tr bgcolor="cddee5">
			<td class="text_1" colspan="3"><div style="padding:3px;"><b>VOYAGE TIME</b></div></td>
		  </tr>
		  <tr>
			<td class="label" width="30%" style="padding:3px;"><strong>PORT DAYS</strong></td>
			<td class="label" width="30%" style="padding:3px;"><strong>SEA DAYS</strong></td>
			<td class="label" width="40%" style="padding:3px;"><strong>TOTAL VOYAGE DAYS</strong></td>
		  </tr>
		  <tr bgcolor="f5f5f5">
			<td class="label calculated" id='o36' style="padding:3px;">&nbsp;</td>
			<td class="label calculated" id='t37' style="padding:3px;">&nbsp;</td>
			<td class="label calculated" id='o37' style="padding:3px;">&nbsp;</td>
		  </tr>
		</table>
		
		<div style="border-bottom:3px dotted #fff;">&nbsp;</div>
		<div>&nbsp;</div>
		
		<table width="1000" border="0" cellspacing="0" cellpadding="0">
		  <tr bgcolor="cddee5">
			<td class="text_1"><div style="padding:3px;"><b>BUNKER PRICING - Data from Bunkerworld</b> <span id="bunker_price_dateupdated"><?php echo $bunker_price_dateupdated; ?></span></div></td>
		  </tr>
		</table>
		<table width="1000" border="0" cellspacing="0" cellpadding="0">
		  <tr bgcolor="f5f5f5">
			<td width="500" colspan="5" style="padding:3px;"><b>IFO Type</b></td>
			<td width="500" colspan="4" style="padding:3px;"><b>MDO Type</b></td>
		  </tr>
		  <tr bgcolor="e9e9e9">
			<td style="padding:3px;"><b>IFO 380 Price ($)</b></td>
			<td colspan="4" class="input" style="padding:3px;"><input type='text' id="d42_input" name="d42" value="<?php echo $d42; ?>" class='input_1 number' style="max-width:150px; border:1px solid #FF0000;" /> <span style="color:#FF0000; font-weight:bold; font-size:14px;">*</span></td>
			<td style="padding:3px;"><b>MDO Price ($)</b></td>
			<td colspan="3" class="input" style="padding:3px;"><input type='text' id="h42_input" name="h42" value="<?php echo $h42; ?>" class='input_1 number' style="max-width:150px;" /></td>
		  </tr>
		  <tr id="bunker_first_row" bgcolor="e9e9e9" style="display:none;">
			<td style="padding:3px;"><b>IFO 380 Price ($)</b></td>
			<td colspan="4" class="input" style="padding:3px;" id='d42'></td>
			<td style="padding:3px;"><b>MDO Price ($)</b></td>
			<td colspan="3" class="input" style="padding:3px;" id='h42'></td>
		  </tr>
		  <tr bgcolor="e9e9e9">
			<td style="padding:3px;"><b>IFO 180 Price ($)</b></td>
			<td colspan="4" class="input" style="padding:3px;"><input type='text' id="d42_180_input" name="d42_180" value="<?php echo $d42_180; ?>" class='input_1 number' style="max-width:150px;" /></td>
			<td style="padding:3px;"><b>MGO Price ($)</b></td>
			<td colspan="3" class="input" style="padding:3px;"><input type='text' id="h42_mgo_input" name="h42_mgo" value="<?php echo $h42_mgo; ?>" class='input_1 number' style="max-width:150px;" /></td>
		  </tr>
		  <tr id="bunker_second_row" bgcolor="e9e9e9" style="display:none;">
			<td style="padding:3px;"><b>IFO 180 Price ($)</b></td>
			<td colspan="4" class="input" style="padding:3px;" id='d42_180'></td>
			<td style="padding:3px;"><b>MGO Price ($)</b></td>
			<td colspan="3" class="input" style="padding:3px;" id='h42_mgo'></td>
		  </tr>
		  <tr bgcolor="e9e9e9">
			<td style="padding:3px;"><b>LS IFO 380 1% Price ($)</b></td>
			<td colspan="4" class="input" style="padding:3px;"><input type='text' id="d42_lsifo380_input" name="d42_lsifo380" value="<?php echo $d42_lsifo380; ?>" class='input_1 number' style="max-width:150px;" /></td>
			<td style="padding:3px;"><b>LS MGO 1% Price ($)</b></td>
			<td colspan="3" class="input" style="padding:3px;"><input type='text' id="h42_lsmgo_input" name="h42_lsmgo" value="<?php echo $h42_lsmgo; ?>" class='input_1 number' style="max-width:150px;" /></td>
		  </tr>
		  <tr id="bunker_third_row" bgcolor="e9e9e9" style="display:none;">
			<td style="padding:3px;"><b>LS IFO 380 1% Price ($)</b></td>
			<td colspan="4" class="input" style="padding:3px;" id='d42_lsifo380'></td>
			<td style="padding:3px;"><b>LS MGO 1% Price ($)</b></td>
			<td colspan="3" class="input" style="padding:3px;" id='h42_lsmgo'></td>
		  </tr>
		  <tr bgcolor="e9e9e9">
			<td style="padding:3px;"><b>LS IFO 180 1% Price ($)</b></td>
			<td colspan="4" class="input" style="padding:3px;"><input type='text' id="d42_lsifo180_input" name="d42_lsifo180" value="<?php echo $d42_lsifo180; ?>" class='input_1 number' style="max-width:150px;" /></td>
			<td style="padding:3px;">&nbsp;</td>
			<td colspan="3" class="input" style="padding:3px;">&nbsp;</td>
		  </tr>
		  <tr id="bunker_fourth_row" bgcolor="e9e9e9" style="display:none;">
			<td style="padding:3px;"><b>LS IFO 180 1% Price ($)</b></td>
			<td colspan="4" class="input" style="padding:3px;" id='d42_lsifo180'></td>
			<td style="padding:3px;">&nbsp;</td>
			<td colspan="3" class="input" style="padding:3px;">&nbsp;</td>
		  </tr>
		  <tr>
			<td class="text_1 label" style="padding:3px;"><b><i>&nbsp;</i></b></td>
			<td class="text_1 label" style="padding:3px;"><b><i>IFO/Ballast/Repositioning</i></b></td>
			<td class="text_1 label" style="padding:3px;"><b><i>IFO/Laden</i></b></td>
			<td class="text_1 label" style="padding:3px;"><b><i>IFO/Port</i></b></td>
			<td class="text_1 label" style="padding:3px;"><b><i>IFO/Reserve</i></b></td>
			<td class="text_1 label" style="padding:3px;"><b><i>&nbsp;</i></b></td>
			<td class="text_1 label" style="padding:3px;"><b><i>MDO/Laden</i></b></td>
			<td class="text_1 label" style="padding:3px;"><b><i>MDO/Port</i></b></td>
			<td class="text_1 label" style="padding:3px;"><b><i>MDO/Reserve</i></b></td>
		  </tr>
		  <tr bgcolor="f5f5f5">
			<td style="padding:3px;"><b>Consumption (MT/day)</b></td>
			<td class='input' style="padding:3px;"><input type='text'  id='c44' name="c44" value="<?php echo $c44; ?>" class='input_1 number' style="max-width:50px; border:1px solid #FF0000;" /> <span style="color:#FF0000; font-weight:bold; font-size:14px;">*</span></td>
			<td class='input' style="padding:3px;"><input type='text'  id='d44' name="d44" value="<?php echo $d44; ?>" class='input_1 number' style="max-width:50px; border:1px solid #FF0000;" /> <span style="color:#FF0000; font-weight:bold; font-size:14px;">*</span></td>
			<td class='input' style="padding:3px;"><input type='text'  id='e44' name="e44" value="<?php echo $e44; ?>" class='input_1 number' style="max-width:50px; border:1px solid #FF0000;" /> <span style="color:#FF0000; font-weight:bold; font-size:14px;">*</span></td>
			<td class="input" style="padding:3px;">&nbsp;</td>
			<td class="input" style="padding:3px;">&nbsp;</td>
			<td class='input' style="padding:3px;"><input type='text'  id='g44' name="g44" value="<?php echo $g44; ?>" class='input_1 number' style="max-width:50px; border:1px solid #FF0000;" /> <span style="color:#FF0000; font-weight:bold; font-size:14px;">*</span></td>
			<td class='input' style="padding:3px;"><input type='text'  id='h44' name="h44" value="<?php echo $h44; ?>" class='input_1 number' style="max-width:50px;" /></td>
			<td class='general' id='i44' style="padding:3px;"></td>
		  </tr>
		  <tr>
			<td class="label" style="padding:3px;"><strong>Total Consumption (MT)</strong></td>
			<td class="label calculated" id='c45' style="padding:3px;"></td>
			<td class="label calculated" id='d45' style="padding:3px;"></td>
			<td class="label calculated" id='e45' style="padding:3px;"></td>
			<td class='label' style="padding:3px;"><input type='text' id='f45' name="f45" value="<?php echo $f45; ?>" class='input_1 number' style="max-width:50px;" /></td>
			<td class="label" style="padding:3px;"></td>
			<td class="label calculated" id='g45' style="padding:3px;"></td>
			<td class="label calculated" id='h45' style="padding:3px;"></td>
			<td class='label input' style="padding:3px;"><input type='text' id='i45' name="i45" value="<?php echo $i45; ?>" class='input_1 number' style="max-width:50px;" /></td>
		  </tr>
		</table>
		
		<div style="border-bottom:3px dotted #fff;">&nbsp;</div>
		<div>&nbsp;</div>
		
		<table width="1000" border="0" cellspacing="0" cellpadding="0">
		  <tr bgcolor="cddee5">
			<td class="text_1"><div style="padding:3px;"><b>VOYAGE EXPENSES</b></div></td>
			<td class="text_1" style="padding:3px;">IFO/Ballast</td>
			<td class="text_1" style="padding:3px;">IFO/Laden</td>
			<td class="text_1" style="padding:3px;">IFO/Port</td>
			<td class="text_1" style="padding:3px;">IFO/Reserve</td>
			<td class="text_1" style="padding:3px;">MDO/Laden</td>
			<td class="text_1" style="padding:3px;">MDO/Port</td>
			<td class="text_1" style="padding:3px;">MDO/Reserve</td>
		  </tr>
		  <tr>
			<td class="label" style="padding:3px;"><strong>Expense ($)</strong></td>
			<td class="label calculated" id='c46' style="padding:3px;">&nbsp;</td>
			<td class="label calculated" id='d46' style="padding:3px;">&nbsp;</td>
			<td class="label calculated" id='e46' style="padding:3px;">&nbsp;</td>
			<td class="label calculated" id='f46' style="padding:3px;">&nbsp;</td>
			<td class="label calculated" id='g46' style="padding:3px;">&nbsp;</td>
			<td class="label calculated" id='h46' style="padding:3px;">&nbsp;</td>
			<td class="label calculated" id='i46' style="padding:3px;">&nbsp;</td>
		  </tr>
		  <tr>
			<td class="label" style="padding:3px;"><strong>Total ($)</strong></td>
			<td colspan="4" class="label calculated" id='c47' style="padding:3px;">&nbsp;</td>
			<td colspan="4" class="label calculated" id='g47' style="padding:3px;">&nbsp;</td>
		  </tr>
		</table>
		
		<div style="border-bottom:3px dotted #fff;">&nbsp;</div>
		<div>&nbsp;</div>
		
		<div style="float:left; width:1000px; height:auto;">
			<div style="float:left; width:490px; height:auto; padding-right:10px;">
				<table width="490" border="0" cellspacing="0" cellpadding="0">
				  <tr bgcolor="cddee5">
					<td class="text_1" colspan="8"><div style="padding:3px;"><b>DWCC</b></div></td>
				  </tr>
				  <tr bgcolor="f5f5f5">
					<td colspan="2" style="padding:3px;"><strong>DW (MT)</strong></td>
					<td width="105" class='calculated number' id='d18' style="padding:3px;"></td>
					<td width="180" style="padding:3px;"><strong>Calculated Amount  </strong></td>
				  </tr>
				  <tr bgcolor="e9e9e9">
					<td width="100" height="34" rowspan="2" style="padding:3px;"><b>Consumption (MT)</b></td>
					<td width="30" style="padding:3px;"><b>FO</b></td>
					<td height="12" class='input' style="padding:3px;"><input type='text' class='input_1 number' id='d19' name="d19" value="<?php echo $d19; ?>" style="max-width:100px;" /></td>
					<td class='calculated general' id='d19b' style="padding:3px;"></td>
				  </tr>
				  <tr bgcolor="f5f5f5">
					<td style="padding:3px;"><b>DO</b></td>
					<td class='input' style="padding:3px;"><input type='text' class='input_1 number' id='d20' name="d20" value="<?php echo $d20; ?>" style="max-width:100px;" /></td>
					<td class='calculated general' id='d20b' style="padding:3px;"></td>
				  </tr>
				  <tr bgcolor="e9e9e9">
					<td rowspan="2" style="padding:3px;"><b>Reserve (MT)</b></td>
					<td style="padding:3px;"><b>FO</b></td>
					<td class='input' style="padding:3px;"><input type='text' class='input_1 number' id='d21' name="d21" value="<?php echo $d21; ?>" style="max-width:100px;" /></td>
					<td class='calculated general' id='d21b' style="padding:3px;"></td>
				  </tr>
				  <tr bgcolor="f5f5f5">
					<td style="padding:3px;"><b>DO</b></td>
					<td class='input' style="padding:3px;"><input type='text' class='input_1 number' id='d22' name="d22" value="<?php echo $d22; ?>" style="max-width:100px;" /></td>
					<td class='calculated general' id='d22b' style="padding:3px;"></td>
				  </tr>
				  <tr bgcolor="e9e9e9">
					<td height="17" colspan="2" style="padding:3px;"><b>FW (MT)</b></td>
					<td class='input' style="padding:3px;"><input type='text' class='input_1 number' id='d23' name="d23" value="<?php echo $d23; ?>" style="max-width:100px;" /></td>
					<td class='calculated general' id='d23b' style="padding:3px;"></td>
				  </tr>
				  <tr bgcolor="f5f5f5">
					<td height="18" colspan="2" style="padding:3px;"><b>Constant (MT)</b></td>
					<td class='input' style="padding:3px;"><input type='text' class='input_1 number' id='d24' name="d24" value="<?php echo $d24; ?>" style="max-width:100px;" /></td>
					<td class='calculated general' id='d24b' style="padding:3px;"></td>
				  </tr>
				  <tr>
					<td colspan="2" class="label" style="padding:3px;"><strong>Used DW (MT)</strong></td>
					<td colspan="2" class='label calculated number' id='d25' style="padding:3px;"></td>
				  </tr>
				  <tr>
					<td colspan="2" class="label" style="padding:3px;"><strong>DWCC (MT)</strong></td>
					<td colspan="2" class='label calculated number' id='d26' style="padding:3px;"></td>
				  </tr>
				</table>
			
				<div style="border-bottom:3px dotted #fff;">&nbsp;</div>
				<div>&nbsp;</div>
			
				<table width="490" border="0" cellspacing="0" cellpadding="0">
				  <tr bgcolor="cddee5">
					<td class="text_1" colspan="5"><div style="padding:3px;"><b>PORT/S</b></div></td>
				  </tr>
				  <tr bgcolor="e9e9e9">
					<td style="padding:3px;" width="133"><strong>Dem ($/day)</strong> <span style="font-size:10px;">Pro Rated</span></td>
					<td class='input' style="padding:3px;"><input type='text' id='c52' name="c52" value="<?php echo $c52; ?>" class='input_1 number' style="max-width:100px;" /></td>
					<td class='input' style="padding:3px;"><input type='text' id='c52_2' name="c52_2" value="<?php echo $c52_2; ?>" class='input_1 number' style="max-width:100px;" /></td>
					<td class='input' style="padding:3px;"><input type='text' id='c52_3' name="c52_3" value="<?php echo $c52_3; ?>" class='input_1 number' style="max-width:100px;" /></td>
				  </tr>
				  <tr bgcolor="f5f5f5">
					<td style="padding:3px;"><strong>Term</strong></td>
					<td style="padding:3px;">
						<?php
						$termarr = array(
									1=>"DHDLTSBENDS", 
									2=>"DHDATSBENDS", 
									3=>"DHDWTSBENDS"
								);
								
						$termt = count($termarr);
						?>
						<select id='term' name="term" class="input_1" style="max-width:100px;">
							<?php
							for($termi=1; $termi<=$termt; $termi++){
								if($termarr[$termi]==$term){
									echo '<option value="'.$termarr[$termi].'" selected="selected">'.$termarr[$termi].'</option>';
								}else{
									echo '<option value="'.$termarr[$termi].'">'.$termarr[$termi].'</option>';
								}
							}
							?>
						</select>
					</td>
					<td style="padding:3px;">
						<?php
						$termarr = array(
									1=>"DHDLTSBENDS", 
									2=>"DHDATSBENDS", 
									3=>"DHDWTSBENDS"
								);
								
						$termt = count($termarr);
						?>
						<select id='term2' name="term2" class="input_1" style="max-width:100px;">
							<?php
							for($termi=1; $termi<=$termt; $termi++){
								if($termarr[$termi]==$term){
									echo '<option value="'.$termarr[$termi].'" selected="selected">'.$termarr[$termi].'</option>';
								}else{
									echo '<option value="'.$termarr[$termi].'">'.$termarr[$termi].'</option>';
								}
							}
							?>
						</select>
					</td>
					<td style="padding:3px;">
						<?php
						$termarr = array(
									1=>"DHDLTSBENDS", 
									2=>"DHDATSBENDS", 
									3=>"DHDWTSBENDS"
								);
								
						$termt = count($termarr);
						?>
						<select id='term3' name="term3" class="input_1" style="max-width:100px;">
							<?php
							for($termi=1; $termi<=$termt; $termi++){
								if($termarr[$termi]==$term){
									echo '<option value="'.$termarr[$termi].'" selected="selected">'.$termarr[$termi].'</option>';
								}else{
									echo '<option value="'.$termarr[$termi].'">'.$termarr[$termi].'</option>';
								}
							}
							?>
						</select>
					</td>
				  </tr>
				  <tr bgcolor="e9e9e9">
					<td style="padding:3px;"><strong>Des ($/day)</strong></td>
					<td class="calculated" id='c54' style="padding:3px;">&nbsp;</td>
					<td class="calculated" id='c54_2' style="padding:3px;">&nbsp;</td>
					<td class="calculated" id='c54_3' style="padding:3px;">&nbsp;</td>
				  </tr>
				  <tr bgcolor="f5f5f5">
					<td style="padding:3px;"><strong>Liner Terms</strong></td>
					<td style="padding:3px;">
						<?php
						$linertermsarr = array(
									1=>"FILO", 
									2=>"FILTD", 
									3=>"FIOLS",
									4=>"FIOSLSD",
									5=>"FIOSPT",
									6=>"FIOST",
									7=>"LIFO",
									8=>"BTBT"
								);
								
						$linertermst = count($linertermsarr);
						?>
						<select id='linerterms' name="linerterms" class="input_1" style="max-width:100px;">
							<?php
							for($linertermsi=1; $linertermsi<=$linertermst; $linertermsi++){
								if($linertermsarr[$linertermsi]==$linerterms){
									echo '<option value="'.$linertermsarr[$linertermsi].'" selected="selected">'.$linertermsarr[$linertermsi].'</option>';
								}else{
									echo '<option value="'.$linertermsarr[$linertermsi].'">'.$linertermsarr[$linertermsi].'</option>';
								}
							}
							?>
						</select>
					</td>
					<td style="padding:3px;">
						<?php
						$linertermsarr = array(
									1=>"FILO", 
									2=>"FILTD", 
									3=>"FIOLS",
									4=>"FIOSLSD",
									5=>"FIOSPT",
									6=>"FIOST",
									7=>"LIFO",
									8=>"BTBT"
								);
								
						$linertermst = count($linertermsarr);
						?>
						<select id='linerterms2' name="linerterms2" class="input_1" style="max-width:100px;">
							<?php
							for($linertermsi=1; $linertermsi<=$linertermst; $linertermsi++){
								if($linertermsarr[$linertermsi]==$linerterms){
									echo '<option value="'.$linertermsarr[$linertermsi].'" selected="selected">'.$linertermsarr[$linertermsi].'</option>';
								}else{
									echo '<option value="'.$linertermsarr[$linertermsi].'">'.$linertermsarr[$linertermsi].'</option>';
								}
							}
							?>
						</select>
					</td>
					<td style="padding:3px;">
						<?php
						$linertermsarr = array(
									1=>"FILO", 
									2=>"FILTD", 
									3=>"FIOLS",
									4=>"FIOSLSD",
									5=>"FIOSPT",
									6=>"FIOST",
									7=>"LIFO",
									8=>"BTBT"
								);
								
						$linertermst = count($linertermsarr);
						?>
						<select id='linerterms3' name="linerterms3" class="input_1" style="max-width:100px;">
							<?php
							for($linertermsi=1; $linertermsi<=$linertermst; $linertermsi++){
								if($linertermsarr[$linertermsi]==$linerterms){
									echo '<option value="'.$linertermsarr[$linertermsi].'" selected="selected">'.$linertermsarr[$linertermsi].'</option>';
								}else{
									echo '<option value="'.$linertermsarr[$linertermsi].'">'.$linertermsarr[$linertermsi].'</option>';
								}
							}
							?>
						</select>
					</td>
				  </tr>
				</table>
				<table width="490" border="0" cellspacing="0" cellpadding="0">
				  <tr bgcolor="e9e9e9">
					<td style="padding:3px;" width="133"><strong>Port</strong> <img src='images/icon_dropdown_warning_shore.png' width='20' height='18' style='cursor:pointer;' onclick="expand();" id="arrow1" /></td>
					<td class='port1' id='port1' style="padding:3px;"><strong>Port 1</strong></td>
					<td class='port2' id='port2' style="padding:3px;"><strong>Port 2</strong></td>
					<td class='port3' id='port3' style="padding:3px;"><strong>Port 3 </strong></td>
				  </tr>
				  <tr bgcolor="f5f5f5">
					<td style="padding:3px;"><strong>DA Quick Input ($)</strong></td>
					<td class='input port1' style="padding:3px;"><input type='text' class='input_1 number da_quick_input1' id="da_quick_input1" name="da_quick_input1" value="<?php echo $da_quick_input1; ?>" style="max-width:100px;" /></td>
					<td class='input port2' style="padding:3px;"><input type='text' class='input_1 number da_quick_input2' id="da_quick_input2" name="da_quick_input2" value="<?php echo $da_quick_input2; ?>" style="max-width:100px;" /></td>
					<td class='input port3' style="padding:3px;"><input type='text' class='input_1 number da_quick_input3' id="da_quick_input3" name="da_quick_input3" value="<?php echo $da_quick_input3; ?>" style="max-width:100px;" /></td>
				  </tr>
				  <tr bgcolor="e9e9e9" style="display:none;" id="quick_total_charges_row_id">
					<td style="padding:3px;"><strong>Quick Total Charges ($)</strong></td>
					<td class='input' style="padding:3px;" id="quick_total_charges1_id"><div id='record1'></div></td>
					<td class='input' style="padding:3px;" id="quick_total_charges2_id"><div id='record2'></div></td>
					<td class='input' style="padding:3px;" id="quick_total_charges3_id"><div id='record3'></div></td>
				  </tr>
				</table>
				<table width="490" border="0" cellspacing="0" cellpadding="0" id="other_input_table" style="display:none;">
				  <tr bgcolor="f5f5f5">
					<td style="padding:3px;"><strong>Laytime (Hrs)</strong></td>
					<td class='input port1' style="padding:3px;"><input type='text' class='input_1 number laytime' id="laytime1" name="laytime1" value="<?php echo $laytime1; ?>" style="max-width:100px;" /></td>
					<td class='input port2' style="padding:3px;"><input type='text' class='input_1 number laytime' id="laytime2" name="laytime2" value="<?php echo $laytime2; ?>" style="max-width:100px;" /></td>
					<td class='input port3' style="padding:3px;"><input type='text' class='input_1 number laytime' id="laytime3" name="laytime3" value="<?php echo $laytime3; ?>" style="max-width:100px;" /></td>
				  </tr>
				  <tr bgcolor="e9e9e9">
					<td style="padding:3px;"><strong>Disbursments</strong></td>
					<td class='input port1' style="padding:3px;"><input type='text' class='input_1 number disbursments' id="disbursments1" name="disbursments1" value="<?php echo $disbursments1; ?>" style="max-width:100px;" /></td>
					<td class='input port2' style="padding:3px;"><input type='text' class='input_1 number disbursments' id="disbursments2" name="disbursments2" value="<?php echo $disbursments2; ?>" style="max-width:100px;" /></td>
					<td class='input port3' style="padding:3px;"><input type='text' class='input_1 number disbursments' id="disbursments3" name="disbursments3" value="<?php echo $disbursments3; ?>" style="max-width:100px;" /></td>
				  </tr>
				  <tr bgcolor="f5f5f5">
					<td style="padding:3px;"><strong>Dues ($)</strong></td>
					<td class='input port1' style="padding:3px;"><input type='text' class='input_1 number dues' id="dues1" name="dues1" value="<?php echo $dues1; ?>" style="max-width:100px;" /></td>
					<td class='input port2' style="padding:3px;"><input type='text' class='input_1 number dues' id="dues2" name="dues2" value="<?php echo $dues2; ?>" style="max-width:100px;" /></td>
					<td class='input port3' style="padding:3px;"><input type='text' class='input_1 number dues' id="dues3" name="dues3" value="<?php echo $dues3; ?>" style="max-width:100px;" /></td>
				  </tr>
				  <tr bgcolor="e9e9e9">
					<td style="padding:3px;"><strong>Pilotage ($)</strong></td>
					<td class='input port1' style="padding:3px;"><input type='text' class='input_1 number pilotage' id="pilotage1" name="pilotage1" value="<?php echo $pilotage1; ?>" style="max-width:100px;" /></td>
					<td class='input port2' style="padding:3px;"><input type='text' class='input_1 number pilotage' id="pilotage2" name="pilotage2" value="<?php echo $pilotage2; ?>" style="max-width:100px;" /></td>
					<td class='input port3' style="padding:3px;"><input type='text' class='input_1 number pilotage' id="pilotage3" name="pilotage3" value="<?php echo $pilotage3; ?>" style="max-width:100px;" /></td>
				  </tr>
				  <tr bgcolor="f5f5f5">
					<td style="padding:3px;"><strong>Tugs ($)</strong></td>
					<td class='input port1' style="padding:3px;"><input type='text' class='input_1 number tugs' id="tugs1" name="tugs1" value="<?php echo $tugs1; ?>" style="max-width:100px;" /></td>
					<td class='input port2' style="padding:3px;"><input type='text' class='input_1 number tugs' id="tugs2" name="tugs2" value="<?php echo $tugs2; ?>" style="max-width:100px;" /></td>
					<td class='input port3' style="padding:3px;"><input type='text' class='input_1 number tugs' id="tugs3" name="tugs3" value="<?php echo $tugs3; ?>" style="max-width:100px;" /></td>
				  </tr>
				  <tr bgcolor="e9e9e9">
					<td style="padding:3px;"><strong>Bunker Adjustment ($)</strong></td>
					<td class='input port1' style="padding:3px;"><input type='text' class='input_1 number bunkeradjustment' id="bunkeradjustment1" name="bunkeradjustment1" value="<?php echo $bunkeradjustment1; ?>" style="max-width:100px;" /></td>
					<td class='input port2' style="padding:3px;"><input type='text' class='input_1 number bunkeradjustment' id="bunkeradjustment2" name="bunkeradjustment2" value="<?php echo $bunkeradjustment2; ?>" style="max-width:100px;" /></td>
					<td class='input port3' style="padding:3px;"><input type='text' class='input_1 number bunkeradjustment' id="bunkeradjustment3" name="bunkeradjustment3" value="<?php echo $bunkeradjustment3; ?>" style="max-width:100px;" /></td>
				  </tr>
				  <tr bgcolor="f5f5f5">
					<td style="padding:3px;"><strong>Mooring ($)</strong></td>
					<td class='input port1' style="padding:3px;"><input type='text' class='input_1 number mooring' id="mooring1" name="mooring1" value="<?php echo $mooring1; ?>" style="max-width:100px;" /></td>
					<td class='input port2' style="padding:3px;"><input type='text' class='input_1 number mooring' id="mooring2" name="mooring2" value="<?php echo $mooring2; ?>" style="max-width:100px;" /></td>
					<td class='input port3' style="padding:3px;"><input type='text' class='input_1 number mooring' id="mooring3" name="mooring3" value="<?php echo $mooring3; ?>" style="max-width:100px;" /></td>
				  </tr>
				  <tr bgcolor="e9e9e9">
					<td style="padding:3px;"><strong>Dockage ($)</strong></td>
					<td class='input port1' style="padding:3px;"><input type='text' class='input_1 number dockage' id="dockage1" name="dockage1" value="<?php echo $dockage1; ?>" style="max-width:100px;" /></td>
					<td class='input port2' style="padding:3px;"><input type='text' class='input_1 number dockage' id="dockage2" name="dockage2" value="<?php echo $dockage2; ?>" style="max-width:100px;" /></td>
					<td class='input port3' style="padding:3px;"><input type='text' class='input_1 number dockage' id="dockage3" name="dockage3" value="<?php echo $dockage3; ?>" style="max-width:100px;" /></td>
				  </tr>
				  <tr bgcolor="f5f5f5">
					<td style="padding:3px;"><strong>Load/Discharge ($)</strong></td>
					<td class='input port1' style="padding:3px;"><input type='text' class='input_1 number loaddischarge' id="loaddischarge1" name="loaddischarge1" value="<?php echo $loaddischarge1; ?>" style="max-width:100px;" /></td>
					<td class='input port2' style="padding:3px;"><input type='text' class='input_1 number loaddischarge' id="loaddischarge2" name="loaddischarge2" value="<?php echo $loaddischarge2; ?>" style="max-width:100px;" /></td>
					<td height="12" class='input port3' style="height: 12px; padding:3px;"><span class="input port3" style="padding:3px;"><input type='text' class='input_1 number loaddischarge' id="loaddischarge3" name="loaddischarge3" value="<?php echo $loaddischarge3; ?>" style="max-width:100px;" /></span></td>
				  </tr>
				  <tr bgcolor="e9e9e9">
					<td style="padding:3px;"><strong>Agency Fee ($)</strong></td>
					<td class='input port1' style="padding:3px;"><input type='text' class='input_1 number agencyfee' id="agencyfee1" name="agencyfee1" value="<?php echo $agencyfee1; ?>" style="max-width:100px;" /></td>
					<td class='input port2' style="padding:3px;"><input type='text' class='input_1 number agencyfee' id="agencyfee2" name="agencyfee2" value="<?php echo $agencyfee2; ?>" style="max-width:100px;" /></td>
					<td class='input port3' style="padding:3px;"><input type='text' class='input_1 number agencyfee' id="agencyfee3" name="agencyfee3" value="<?php echo $agencyfee3; ?>" style="max-width:100px;" /></td>
				  </tr>
				  <tr bgcolor="f5f5f5">
					<td style="padding:3px;"><strong>Miscellaneous ($)</strong></td>
					<td class='input port1' style="padding:3px;"><input type='text' class='input_1 number miscellaneous' id="miscellaneous1" name="miscellaneous1" value="<?php echo $miscellaneous1; ?>" style="max-width:100px;" /></td>
					<td class='input port2' style="padding:3px;"><input type='text' class='input_1 number miscellaneous' id="miscellaneous2" name="miscellaneous2" value="<?php echo $miscellaneous2; ?>" style="max-width:100px;" /></td>
					<td class='input port3' style="padding:3px;"><input type='text' class='input_1 number miscellaneous' id="miscellaneous3" name="miscellaneous3" value="<?php echo $miscellaneous3; ?>" style="max-width:100px;" /></td>
				  </tr>
				</table>
				<table width="490" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td class="label" style="padding:3px;"><strong>Demurrage ($)</strong></td>
					<td colspan="3" class="label calculated" id='c66' style="padding:3px;"><strong>0.00</strong></td>
				  </tr>
				  <tr>
					<td class="label" style="padding:3px;"><strong>Despatch ($)</strong></td>
					<td colspan="3" class="label calculated" id='c67' style="padding:3px;"><strong>48,849.31</strong></td>
				  </tr>
				  <tr>
					<td class="label" style="padding:3px;"><strong>Total ($)</strong></td>
					<td colspan="3" class="label calculated" id='c68' style="padding:3px;"></td>
				  </tr>
				</table>
			</div>
			<div style="float:left; width:490px; height:auto; padding-left:10px;">
				<table width="490" border="0" cellspacing="0" cellpadding="0">
				  <tr bgcolor="cddee5">
					<td class="text_1" colspan="8"><div style="padding:3px;"><b>CANAL</b></div></td>
				  </tr>
				  <tr bgcolor="f5f5f5">
					<td width="115" style="padding:3px;"><b>Canal</b></td>
					<td width="100" style="padding:3px;">&nbsp;</td>
					<td width="125" style="padding:3px;">
						<?php
						$canalarr = array(
									1=>"White Sea - Baltic Canal", 
									2=>"Rhine - Main- Danube Canal", 
									3=>"Volga - Don Canal",
									4=>"Kiel Canal",
									5=>"Houston Ship Channel",
									6=>"Alphonse Xlll Canal",
									7=>"Panama Canal",
									8=>"Danube Black - Sea Canal",
									9=>"Manchester Ship Canal",
									10=>"Welland Canal",
									11=>"Saint Lawrence Seaway",
									12=>"Suez Canal"
								);
								
						$canalt = count($canalarr);
						?>
						<select id='canal' name="canal" class="input_1" style="max-width:100px;">
							<?php
							for($canali=1; $canali<=$canalt; $canali++){
								if($canalarr[$canali]==$canal){
									echo '<option value="'.$canalarr[$canali].'" selected="selected">'.$canalarr[$canali].'</option>';
								}else{
									echo '<option value="'.$canalarr[$canali].'">'.$canalarr[$canali].'</option>';
								}
							}
							?>
						</select>
					</td>
				  </tr>
				  <tr bgcolor="e9e9e9">
					<td style="padding:3px;"><b>Booking Fee ($)</b></td>
					<td class='empty' style="padding:3px;"><input type='text' id='cbook1' name="cbook1" value="<?php echo $cbook1; ?>"  class='input_1 number' style="max-width:200px;" /></td>
					<td class='empty' style="padding:3px;"><input type='text' id='cbook2' name="cbook2" value="<?php echo $cbook2; ?>"  class='input_1 number' style="max-width:200px;" /></td>
				  </tr>
				  <tr bgcolor="f5f5f5">
					<td style="padding:3px;"><b>Tugs ($)</b></td>
					<td class='empty' style="padding:3px;"><input type='text' id='ctug1' name="ctug1" value="<?php echo $ctug1; ?>" class='input_1 number' style="max-width:200px;" /></td>
					<td class='empty' style="padding:3px;"><input type='text' id='ctug2' name="ctug2" value="<?php echo $ctug2; ?>" class='input_1 number' style="max-width:200px;" /></td>
				  </tr>
				  <tr bgcolor="e9e9e9">
					<td style="padding:3px;"><b>Line Handlers ($)</b></td>
					<td class='empty' style="padding:3px;"><input type='text' id='cline1' name="cline1" value="<?php echo $cline1; ?>" class='input_1 number' style="max-width:200px;" /></td>
					<td class='empty' style="padding:3px;"><span class="empty" style="padding:3px;"><input type='text' id='cline2' name="cline2" value="<?php echo $cline2; ?>" class='input_1 number' style="max-width:200px;" /></span></td>
				  </tr>
				  <tr bgcolor="f5f5f5">
					<td style="padding:3px;"><b>Miscellaneous ($)</b></td>
					<td class='empty' style="padding:3px;"><input type='text' id='cmisc1' name="cmisc1" value="<?php echo $cmisc1; ?>" class='input_1 number' style="max-width:200px;" /></td>
					<td class='empty' style="padding:3px;"><input type='text' id='cmisc2' name="cmisc2" value="<?php echo $cmisc2; ?>" class='input_1 number' style="max-width:200px;" /></td>
				  </tr>
				  <tr bgcolor="e9e9e9">
					<td class="label" style="padding:3px;"><strong>Total ($)</strong></td>
					<td class="label calculated" id='ctotal1' style="padding:3px;"></td>
					<td class="label calculated" id='ctotal2' style="padding:3px;"></td>
				  </tr>
				</table>
			
				<div style="border-bottom:3px dotted #fff;">&nbsp;</div>
				<div>&nbsp;</div>
				<!--height='460'-->
				<table width="490" height='315' border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td bgcolor="#000000"><iframe src='' id="map_iframeve" width='490' height='315' frameborder="0"></iframe></td>
				  </tr>
				</table>
			</div>
		</div>
		
		<div style="float:left; width:100%; height:auto; border-bottom:3px dotted #fff;">&nbsp;</div>
		<div style="float:left; width:100%; height:auto;">&nbsp;</div>
		
		<table width="" border="0" cellspacing="0" cellpadding="0">
		  <tr bgcolor="cddee5">
			<td width="148" class="text_1"><div style="padding:3px;"><b>VOYAGE DISBURSMENTS</b></div></td>
			<td width="124"></td>
			<td width="104"></td>
			<td width="104"></td>
			<td width="104" class="text_1"><div style="padding:3px;"><b>VOYAGE</b></div></td>
			<td width="104"></td>
			<td width="104"></td>
			<td width="104"></td>
			<td width="104"></td>
		  </tr>
		  <tr bgcolor="f5f5f5">
			<td class="label" style="padding:3px;"><strong>Bunker ($)</strong></td>
			<td class="label" style="padding:3px;"><strong>Port ($)</strong></td>
			<td class="label" style="padding:3px;"><strong>Canal($)</strong></td>
			<td class="label" style="padding:3px;"><strong>Add. Insurance ($)</strong></td>
			<td class="label" style="padding:3px;"><strong>ILOHC</strong></td>
			<td class="label" style="padding:3px;"><strong>ILOW</strong></td>
			<td class="label" style="padding:3px;"><strong>CVE</strong></td>
			<td class="label" style="padding:3px;"><strong>Ballast Bonus</strong></td>
			<td class="label" style="padding:3px;"><strong>Miscellaneous</strong></td>
		  </tr>
		  <tr bgcolor="e9e9e9">
			<td class="calculated" id='b74' style="padding:3px;"></td>
			<td class="calculated" id='c74' style="padding:3px;"><strong>161,150.69</strong></td>
			<td class="calculated" id='d74' style="padding:3px;"><strong>150,000.00</strong></td>
			<td class='input' style="padding:3px;"><input type='text' class='input_1 number' id='e74' name="e74" value="<?php echo $e74; ?>" style="max-width:70px;" /></td>
			<td class='input' style="padding:3px;"><input type='text' class='input_1 number' id='f74' name="f74" value="<?php echo $f74; ?>" style="max-width:70px;" /></td>
			<td class='input' style="padding:3px;"><input type='text' class='input_1 number' id='g74' name="g74" value="<?php echo $g74; ?>" style="max-width:70px;" /></td>
			<td class='input' style="padding:3px;"><input type='text' class='input_1 number' id='h74' name="h74" value="<?php echo $h74; ?>" style="max-width:70px;" /></td>
			<td class='input' style="padding:3px;"><input type='text' class='input_1 number' id='i74' name="i74" value="<?php echo $i74; ?>" style="max-width:70px;" /></td>
			<td class='input' style="padding:3px;"><input type='text' class='input_1 number' id='j74' name="j74" value="<?php echo $j74; ?>" style="max-width:70px;" /></td>
		  </tr>
		  <tr>
			<td colspan="9" class="label calculated" id='b75' style="padding:3px;"></td>
		  </tr>
		</table>
	</td>
	<td width="300">
		<div style="position:fixed;">
			<table width="300" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td width="150" style="border:none;">
					<div style="padding-left:10px;">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr bgcolor="cddee5">
								<td class="text_1"><div style="padding:3px;"><b>FREIGHT RATE</b></div></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td height="5"></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td class="label" style="padding:3px;"><span style="font-size:14px; color:#0066FF; font-weight:bold;">Freight Rate ($/MT)</span></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td class='empty' style="padding:3px;"><input type='text' class='input_1 number' id='b80' name="b80" value="<?php echo $b80; ?>" style="max-width:100px; border:1px solid #FF0000;" /> <span style="color:#FF0000; font-weight:bold; font-size:14px;">*</span></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td height="5"></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td class="label" style="padding:3px;"><strong>Gross Freight ($)</strong></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td class="calculated" id='c80' style="padding:3px;"></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td height="5"></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td class="label" style="padding:3px;"><strong>Brok. Comm ($)</strong></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td style="padding:3px;"><input type='text' class='input_1 number' id='d80' name="d80" value="<?php echo $d80; ?>" style="max-width:100px;" /></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td height="5"></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td class="label" style="padding:3px;"><strong>Add. Comm ($)</strong></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td style="padding:3px;"><input type='text' class='input_1 number' id='e80' name='e80' value="<?php echo $e80; ?>" style="max-width:100px;" /></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td height="5"></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td class="label" style="padding:3px; border-left:1px solid #000000; border-top:1px solid #000000; border-right:1px solid #000000;"><strong>Income ($)</strong></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td class="calculated" id='f80' style="padding:3px; border-left:1px solid #000000; border-bottom:1px solid #000000; border-right:1px solid #000000;"></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td height="5"></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td class="label" style="padding:3px; border-left:1px solid #002060; border-top:1px solid #002060; border-right:1px solid #002060;"><strong>TCE ($/day)</strong></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td class="calculated" id='g80' style="padding:3px; border-left:1px solid #002060; border-bottom:1px solid #002060; border-right:1px solid #002060;"></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td height="5"></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td class="label" style="padding:3px;"><strong>Broker Commission</strong></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td class="label calculated" id='d81' style="padding:3px;"></td>
							</tr>
						</table>
					</div>
				</td>
				<td width="150" style="border:none;">
					<div style="padding-left:10px;">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr bgcolor="cddee5">
								<td class="text_1"><div style="padding:3px;"><b>TCE</b></div></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td height="5"></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td style="padding:3px; border-left:1px solid #002060; border-top:1px solid #002060; border-right:1px solid #002060;"><strong>Freight Rate ($/MT)</strong></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td class="calculated" id='b85' style="padding:3px; border-left:1px solid #002060; border-bottom:1px solid #002060; border-right:1px solid #002060;"></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td height="5"></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td class="label" style="padding:3px;"><strong>Gross Freight ($)</strong></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td class="calculated"  id='c85' style="padding:3px;"></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td height="5"></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td class="label" style="padding:3px;"><strong>Brok. Comm ($)</strong></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td style="padding:3px;"><input type='text' class='input_1 number' id='d85' name='d85' value="<?php echo $d85; ?>" style="max-width:100px;" /></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td height="5"></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td class="label" style="padding:3px;"><strong>Add. Comm ($)</strong></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td style="padding:3px;"><input type='text' class='input_1 number' id='e85' name='e85' value="<?php echo $e85; ?>" style="max-width:100px;" /></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td height="5"></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td class="label" style="padding:3px;"><strong>Income ($)</strong></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td class="calculated"  id='f85' style="padding:3px;"></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td height="5"></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td class="label" style="padding:3px;"><span style="font-size:14px; color:#0066FF; font-weight:bold;">TCE ($/day)</span></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td class='empty' style="padding:3px;"><input type='text' class='input_1 number' id='g85' name='g85' value="<?php echo $g85; ?>" style="max-width:100px; border:1px solid #FF0000;" /> <span style="color:#FF0000; font-weight:bold; font-size:14px;">*</span></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td height="5"></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td class="label" style="padding:3px;"><strong>Broker Commission</strong></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td class="label calculated"  id='d86' style="padding:3px;"></td>
							</tr>
						</table>
					</div>
				</td>
			  </tr>
			</table>
		</div>
	</td>
  </tr>
</table>
<div>&nbsp;</div>
</form>

<script type="text/javascript">
//MAKE SHIP DETAILS SHOW ONLOAD
$(document).ready(function(){
	$("#c31").focus();
	$("#c31").blur();
});
//END OF MAKE SHIP DETAILS SHOW ONLOAD
</script>