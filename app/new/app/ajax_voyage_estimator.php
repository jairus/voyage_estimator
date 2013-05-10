<?php
@include_once(dirname(__FILE__)."/includes/bootstrap.php");
date_default_timezone_set('UTC');

//SAVE SESSION
if($_GET['autosave']){
	$_SESSION['data'] = $_POST['data'];

	exit();
}
//END OF SAVE SESSION

//GET DWT DATA
if($_GET['search_dwt']){
	$imo = explode(' - ', $_GET['imo']);
	$imo = $imo[0];
	
	$sql = "select * from  _xvas_parsed2_dry where imo='".$imo."' limit 1";
	$r = dbQuery($sql);
	
	$dwt = $r[0]['summer_dwt'];
	
	echo json_encode($dwt);

	exit();
}
//END OF GET DWT DATA

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
		
		$sql4 = "SELECT * FROM _xvas_shipdata_dry_user WHERE imo='".trim($r[$i]['imo'])."' LIMIT 0,1";
		$r4 = dbQuery($sql4);
		
		$ship = array();

		$ship['name'] = $r[$i]['imo']." - ".$r[$i]['name']." - ".number_format($r[$i]['summer_dwt']);
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
		
		//BUNKER FUEL
		$data2 = unserialize($r4[0]['data']);
		
		$ship['SPEED1_1'] = $data2['BUNKER_FUEL']['SPEED1_1'];
		$ship['SPEED2_1'] = $data2['BUNKER_FUEL']['SPEED2_1'];
		$ship['SPEED1_2'] = $data2['BUNKER_FUEL']['SPEED1_2'];
		$ship['SPEED2_2'] = $data2['BUNKER_FUEL']['SPEED2_2'];
		$ship['SPEED1_3'] = $data2['BUNKER_FUEL']['SPEED1_3'];
		$ship['SPEED2_3'] = $data2['BUNKER_FUEL']['SPEED2_3'];
		$ship['SPEED1_4'] = $data2['BUNKER_FUEL']['SPEED1_4'];
		$ship['SPEED2_4'] = $data2['BUNKER_FUEL']['SPEED2_4'];
		$ship['SPEED1_5'] = $data2['BUNKER_FUEL']['SPEED1_5'];
		$ship['SPEED2_5'] = $data2['BUNKER_FUEL']['SPEED2_5'];
		$ship['SPEED1_6'] = $data2['BUNKER_FUEL']['SPEED1_6'];
		$ship['SPEED2_6'] = $data2['BUNKER_FUEL']['SPEED2_6'];
		$ship['SPEED1_7'] = $data2['BUNKER_FUEL']['SPEED1_7'];
		$ship['SPEED2_7'] = $data2['BUNKER_FUEL']['SPEED2_7'];
		$ship['SPEED_TEXT1_1'] = $data2['BUNKER_FUEL']['SPEED_TEXT1_1'];
		$ship['SPEED_TEXT2_1'] = $data2['BUNKER_FUEL']['SPEED_TEXT2_1'];
		$ship['SPEED_TEXT1_2'] = $data2['BUNKER_FUEL']['SPEED_TEXT1_2'];
		$ship['SPEED_TEXT2_2'] = $data2['BUNKER_FUEL']['SPEED_TEXT2_2'];
		$ship['SPEED_TEXT1_3'] = $data2['BUNKER_FUEL']['SPEED_TEXT1_3'];
		$ship['SPEED_TEXT2_3'] = $data2['BUNKER_FUEL']['SPEED_TEXT2_3'];
		$ship['SPEED_TEXT1_4'] = $data2['BUNKER_FUEL']['SPEED_TEXT1_4'];
		$ship['SPEED_TEXT2_4'] = $data2['BUNKER_FUEL']['SPEED_TEXT2_4'];
		$ship['SPEED_TEXT1_5'] = $data2['BUNKER_FUEL']['SPEED_TEXT1_5'];
		$ship['SPEED_TEXT2_5'] = $data2['BUNKER_FUEL']['SPEED_TEXT2_5'];
		$ship['SPEED_TEXT1_6'] = $data2['BUNKER_FUEL']['SPEED_TEXT1_6'];
		$ship['SPEED_TEXT2_6'] = $data2['BUNKER_FUEL']['SPEED_TEXT2_6'];
		$ship['SPEED_TEXT1_7'] = $data2['BUNKER_FUEL']['SPEED_TEXT1_7'];
		$ship['SPEED_TEXT2_7'] = $data2['BUNKER_FUEL']['SPEED_TEXT2_7'];
		$ship['CONSUMPTION1_1'] = $data2['BUNKER_FUEL']['CONSUMPTION1_1'];
		$ship['CONSUMPTION2_1'] = $data2['BUNKER_FUEL']['CONSUMPTION2_1'];
		$ship['CONSUMPTION1_2'] = $data2['BUNKER_FUEL']['CONSUMPTION1_2'];
		$ship['CONSUMPTION2_2'] = $data2['BUNKER_FUEL']['CONSUMPTION2_2'];
		$ship['CONSUMPTION1_3'] = $data2['BUNKER_FUEL']['CONSUMPTION1_3'];
		$ship['CONSUMPTION2_3'] = $data2['BUNKER_FUEL']['CONSUMPTION2_3'];
		$ship['CONSUMPTION1_4'] = $data2['BUNKER_FUEL']['CONSUMPTION1_4'];
		$ship['CONSUMPTION2_4'] = $data2['BUNKER_FUEL']['CONSUMPTION2_4'];
		$ship['CONSUMPTION1_5'] = $data2['BUNKER_FUEL']['CONSUMPTION1_5'];
		$ship['CONSUMPTION2_5'] = $data2['BUNKER_FUEL']['CONSUMPTION2_5'];
		$ship['CONSUMPTION1_6'] = $data2['BUNKER_FUEL']['CONSUMPTION1_6'];
		$ship['CONSUMPTION2_6'] = $data2['BUNKER_FUEL']['CONSUMPTION2_6'];
		$ship['CONSUMPTION1_7'] = $data2['BUNKER_FUEL']['CONSUMPTION1_7'];
		$ship['CONSUMPTION2_7'] = $data2['BUNKER_FUEL']['CONSUMPTION2_7'];
		$ship['CONSUMPTION_TEXT1_1'] = $data2['BUNKER_FUEL']['CONSUMPTION_TEXT1_1'];
		$ship['CONSUMPTION_TEXT2_1'] = $data2['BUNKER_FUEL']['CONSUMPTION_TEXT2_1'];
		$ship['CONSUMPTION_TEXT1_2'] = $data2['BUNKER_FUEL']['CONSUMPTION_TEXT1_2'];
		$ship['CONSUMPTION_TEXT2_2'] = $data2['BUNKER_FUEL']['CONSUMPTION_TEXT2_2'];
		$ship['CONSUMPTION_TEXT1_3'] = $data2['BUNKER_FUEL']['CONSUMPTION_TEXT1_3'];
		$ship['CONSUMPTION_TEXT2_3'] = $data2['BUNKER_FUEL']['CONSUMPTION_TEXT2_3'];
		$ship['CONSUMPTION_TEXT1_4'] = $data2['BUNKER_FUEL']['CONSUMPTION_TEXT1_4'];
		$ship['CONSUMPTION_TEXT2_4'] = $data2['BUNKER_FUEL']['CONSUMPTION_TEXT2_4'];
		$ship['CONSUMPTION_TEXT1_5'] = $data2['BUNKER_FUEL']['CONSUMPTION_TEXT1_5'];
		$ship['CONSUMPTION_TEXT2_5'] = $data2['BUNKER_FUEL']['CONSUMPTION_TEXT2_5'];
		$ship['CONSUMPTION_TEXT1_6'] = $data2['BUNKER_FUEL']['CONSUMPTION_TEXT1_6'];
		$ship['CONSUMPTION_TEXT2_6'] = $data2['BUNKER_FUEL']['CONSUMPTION_TEXT2_6'];
		$ship['CONSUMPTION_TEXT1_7'] = $data2['BUNKER_FUEL']['CONSUMPTION_TEXT1_7'];
		$ship['CONSUMPTION_TEXT2_7'] = $data2['BUNKER_FUEL']['CONSUMPTION_TEXT2_7'];
		//END OF BUNKER FUEL

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
var SPEED1_1 = [];
var SPEED2_1 = [];
var SPEED1_2 = [];
var SPEED2_2 = [];
var SPEED1_3 = [];
var SPEED2_3 = [];
var SPEED1_4 = [];
var SPEED2_4 = [];
var SPEED1_5 = [];
var SPEED2_5 = [];
var SPEED1_6 = [];
var SPEED2_6 = [];
var SPEED1_7 = [];
var SPEED2_7 = [];
var SPEED_TEXT1_1 = [];
var SPEED_TEXT2_1 = [];
var SPEED_TEXT1_2 = [];
var SPEED_TEXT2_2 = [];
var SPEED_TEXT1_3 = [];
var SPEED_TEXT2_3 = [];
var SPEED_TEXT1_4 = [];
var SPEED_TEXT2_4 = [];
var SPEED_TEXT1_5 = [];
var SPEED_TEXT2_5 = [];
var SPEED_TEXT1_6 = [];
var SPEED_TEXT2_6 = [];
var SPEED_TEXT1_7 = [];
var SPEED_TEXT2_7 = [];
var CONSUMPTION1_1 = [];
var CONSUMPTION2_1 = [];
var CONSUMPTION1_2 = [];
var CONSUMPTION2_2 = [];
var CONSUMPTION1_3 = [];
var CONSUMPTION2_3 = [];
var CONSUMPTION1_4 = [];
var CONSUMPTION2_4 = [];
var CONSUMPTION1_5 = [];
var CONSUMPTION2_5 = [];
var CONSUMPTION1_6 = [];
var CONSUMPTION2_6 = [];
var CONSUMPTION1_7 = [];
var CONSUMPTION2_7 = [];
var CONSUMPTION_TEXT1_1 = [];
var CONSUMPTION_TEXT2_1 = [];
var CONSUMPTION_TEXT1_2 = [];
var CONSUMPTION_TEXT2_2 = [];
var CONSUMPTION_TEXT1_3 = [];
var CONSUMPTION_TEXT2_3 = [];
var CONSUMPTION_TEXT1_4 = [];
var CONSUMPTION_TEXT2_4 = [];
var CONSUMPTION_TEXT1_5 = [];
var CONSUMPTION_TEXT2_5 = [];
var CONSUMPTION_TEXT1_6 = [];
var CONSUMPTION_TEXT2_6 = [];
var CONSUMPTION_TEXT1_7 = [];
var CONSUMPTION_TEXT2_7 = [];
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

//GET DWT TYPE
function getDwtType(imo){
	jQuery.ajax({
		type: 'POST',
		url: "ajax_voyage_estimator.php?search_dwt=1&imo="+imo,
		data: '',

		success: function(data) {
			setValue(jQuery("#div_dwt_id"), fNum(data));
		}
	});	
}
//END OF GET DWT TYPE

$(function(){
	//DETAILS COMING FROM SHIP NAME
	$("#vessel_name_or_imo_id").autocomplete({
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
					SPEED1_1[val.imo] = val.SPEED1_1;
					SPEED2_1[val.imo] = val.SPEED2_1;
					SPEED1_2[val.imo] = val.SPEED1_2;
					SPEED2_2[val.imo] = val.SPEED2_2;
					SPEED1_3[val.imo] = val.SPEED1_3;
					SPEED2_3[val.imo] = val.SPEED2_3;
					SPEED1_4[val.imo] = val.SPEED1_4;
					SPEED2_4[val.imo] = val.SPEED2_4;
					SPEED1_5[val.imo] = val.SPEED1_5;
					SPEED2_5[val.imo] = val.SPEED2_5;
					SPEED1_6[val.imo] = val.SPEED1_6;
					SPEED2_6[val.imo] = val.SPEED2_6;
					SPEED1_7[val.imo] = val.SPEED1_7;
					SPEED2_7[val.imo] = val.SPEED2_7;
					SPEED_TEXT1_1[val.imo] = val.SPEED_TEXT1_1;
					SPEED_TEXT2_1[val.imo] = val.SPEED_TEXT2_1;
					SPEED_TEXT1_2[val.imo] = val.SPEED_TEXT1_2;
					SPEED_TEXT2_2[val.imo] = val.SPEED_TEXT2_2;
					SPEED_TEXT1_3[val.imo] = val.SPEED_TEXT1_3;
					SPEED_TEXT2_3[val.imo] = val.SPEED_TEXT2_3;
					SPEED_TEXT1_4[val.imo] = val.SPEED_TEXT1_4;
					SPEED_TEXT2_4[val.imo] = val.SPEED_TEXT2_4;
					SPEED_TEXT1_5[val.imo] = val.SPEED_TEXT1_5;
					SPEED_TEXT2_5[val.imo] = val.SPEED_TEXT2_5;
					SPEED_TEXT1_6[val.imo] = val.SPEED_TEXT1_6;
					SPEED_TEXT2_6[val.imo] = val.SPEED_TEXT2_6;
					SPEED_TEXT1_7[val.imo] = val.SPEED_TEXT1_7;
					SPEED_TEXT2_7[val.imo] = val.SPEED_TEXT2_7;
					CONSUMPTION1_1[val.imo] = val.CONSUMPTION1_1;
					CONSUMPTION2_1[val.imo] = val.CONSUMPTION2_1;
					CONSUMPTION1_2[val.imo] = val.CONSUMPTION1_2;
					CONSUMPTION2_2[val.imo] = val.CONSUMPTION2_2;
					CONSUMPTION1_3[val.imo] = val.CONSUMPTION1_3;
					CONSUMPTION2_3[val.imo] = val.CONSUMPTION2_3;
					CONSUMPTION1_4[val.imo] = val.CONSUMPTION1_4;
					CONSUMPTION2_4[val.imo] = val.CONSUMPTION2_4;
					CONSUMPTION1_5[val.imo] = val.CONSUMPTION1_5;
					CONSUMPTION2_5[val.imo] = val.CONSUMPTION2_5;
					CONSUMPTION1_6[val.imo] = val.CONSUMPTION1_6;
					CONSUMPTION2_6[val.imo] = val.CONSUMPTION2_6;
					CONSUMPTION1_7[val.imo] = val.CONSUMPTION1_7;
					CONSUMPTION2_7[val.imo] = val.CONSUMPTION2_7;
					CONSUMPTION_TEXT1_1[val.imo] = val.CONSUMPTION_TEXT1_1;
					CONSUMPTION_TEXT2_1[val.imo] = val.CONSUMPTION_TEXT2_1;
					CONSUMPTION_TEXT1_2[val.imo] = val.CONSUMPTION_TEXT1_2;
					CONSUMPTION_TEXT2_2[val.imo] = val.CONSUMPTION_TEXT2_2;
					CONSUMPTION_TEXT1_3[val.imo] = val.CONSUMPTION_TEXT1_3;
					CONSUMPTION_TEXT2_3[val.imo] = val.CONSUMPTION_TEXT2_3;
					CONSUMPTION_TEXT1_4[val.imo] = val.CONSUMPTION_TEXT1_4;
					CONSUMPTION_TEXT2_4[val.imo] = val.CONSUMPTION_TEXT2_4;
					CONSUMPTION_TEXT1_5[val.imo] = val.CONSUMPTION_TEXT1_5;
					CONSUMPTION_TEXT2_5[val.imo] = val.CONSUMPTION_TEXT2_5;
					CONSUMPTION_TEXT1_6[val.imo] = val.CONSUMPTION_TEXT1_6;
					CONSUMPTION_TEXT2_6[val.imo] = val.CONSUMPTION_TEXT2_6;
					CONSUMPTION_TEXT1_7[val.imo] = val.CONSUMPTION_TEXT1_7;
					CONSUMPTION_TEXT2_7[val.imo] = val.CONSUMPTION_TEXT2_7;
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
			
			//BUNKER FUEL
			if(SPEED1_1[imo] || SPEED2_1[imo] || SPEED1_2[imo] || SPEED2_2[imo] || SPEED1_3[imo] || SPEED2_3[imo] || SPEED1_4[imo] || SPEED2_4[imo] || SPEED1_5[imo] || SPEED2_5[imo] || SPEED1_6[imo] || SPEED2_6[imo] || SPEED1_7[imo] || SPEED2_7[imo] || SPEED_TEXT1_1[imo] || SPEED_TEXT2_1[imo] || SPEED_TEXT1_2[imo] || SPEED_TEXT2_2[imo] || SPEED_TEXT1_3[imo] || SPEED_TEXT2_3[imo] || SPEED_TEXT1_4[imo] || SPEED_TEXT2_4[imo] || SPEED_TEXT1_5[imo] || SPEED_TEXT2_5[imo] || SPEED_TEXT1_6[imo] || SPEED_TEXT2_6[imo] || SPEED_TEXT1_7[imo] || SPEED_TEXT2_7[imo] || CONSUMPTION1_1[imo] || CONSUMPTION2_1[imo] || CONSUMPTION1_2[imo] || CONSUMPTION2_2[imo] || CONSUMPTION1_3[imo] || CONSUMPTION2_3[imo] || CONSUMPTION1_4[imo] || CONSUMPTION2_4[imo] || CONSUMPTION1_5[imo] || CONSUMPTION2_5[imo] || CONSUMPTION1_6[imo] || CONSUMPTION2_6[imo] || CONSUMPTION1_7[imo] || CONSUMPTION2_7[imo] || CONSUMPTION_TEXT1_1[imo] || CONSUMPTION_TEXT2_1[imo] || CONSUMPTION_TEXT1_2[imo] || CONSUMPTION_TEXT2_2[imo] || CONSUMPTION_TEXT1_3[imo] || CONSUMPTION_TEXT2_3[imo] || CONSUMPTION_TEXT1_4[imo] || CONSUMPTION_TEXT2_4[imo] || CONSUMPTION_TEXT1_5[imo] || CONSUMPTION_TEXT2_5[imo] || CONSUMPTION_TEXT1_6[imo] || CONSUMPTION_TEXT2_6[imo] || CONSUMPTION_TEXT1_7[imo] || CONSUMPTION_TEXT2_7[imo]){
				jQuery("#bunker_fuel_info").show();
			}
			
			if(SPEED1_1[imo]){ setValue(jQuery("#SPEED1_1"), SPEED1_1[imo]); }
			if(SPEED2_1[imo]){ setValue(jQuery("#SPEED2_1"), SPEED2_1[imo]); }
			if(SPEED1_2[imo]){ setValue(jQuery("#SPEED1_2"), SPEED1_2[imo]); }
			if(SPEED2_2[imo]){ setValue(jQuery("#SPEED2_2"), SPEED2_2[imo]); }
			if(SPEED1_3[imo]){ setValue(jQuery("#SPEED1_3"), SPEED1_3[imo]); }
			if(SPEED2_3[imo]){ setValue(jQuery("#SPEED2_3"), SPEED2_3[imo]); }
			if(SPEED1_4[imo]){ setValue(jQuery("#SPEED1_4"), SPEED1_4[imo]); }
			if(SPEED2_4[imo]){ setValue(jQuery("#SPEED2_4"), SPEED2_4[imo]); }
			if(SPEED1_5[imo]){ setValue(jQuery("#SPEED1_5"), SPEED1_5[imo]); }
			if(SPEED2_5[imo]){ setValue(jQuery("#SPEED2_5"), SPEED2_5[imo]); }
			if(SPEED1_6[imo]){ setValue(jQuery("#SPEED1_6"), SPEED1_6[imo]); }
			if(SPEED2_6[imo]){ setValue(jQuery("#SPEED2_6"), SPEED2_6[imo]); }
			if(SPEED1_7[imo]){ setValue(jQuery("#SPEED1_7"), SPEED1_7[imo]); }
			if(SPEED2_7[imo]){ setValue(jQuery("#SPEED2_7"), SPEED2_7[imo]); }
			if(SPEED_TEXT1_1[imo]){ setValue(jQuery("#SPEED_TEXT1_1"), SPEED_TEXT1_1[imo]); }
			if(SPEED_TEXT2_1[imo]){ setValue(jQuery("#SPEED_TEXT2_1"), SPEED_TEXT2_1[imo]); }
			if(SPEED_TEXT1_2[imo]){ setValue(jQuery("#SPEED_TEXT1_2"), SPEED_TEXT1_2[imo]); }
			if(SPEED_TEXT2_2[imo]){ setValue(jQuery("#SPEED_TEXT2_2"), SPEED_TEXT2_2[imo]); }
			if(SPEED_TEXT1_3[imo]){ setValue(jQuery("#SPEED_TEXT1_3"), SPEED_TEXT1_3[imo]); }
			if(SPEED_TEXT2_3[imo]){ setValue(jQuery("#SPEED_TEXT2_3"), SPEED_TEXT2_3[imo]); }
			if(SPEED_TEXT1_4[imo]){ setValue(jQuery("#SPEED_TEXT1_4"), SPEED_TEXT1_4[imo]); }
			if(SPEED_TEXT2_4[imo]){ setValue(jQuery("#SPEED_TEXT2_4"), SPEED_TEXT2_4[imo]); }
			if(SPEED_TEXT1_5[imo]){ setValue(jQuery("#SPEED_TEXT1_5"), SPEED_TEXT1_5[imo]); }
			if(SPEED_TEXT2_5[imo]){ setValue(jQuery("#SPEED_TEXT2_5"), SPEED_TEXT2_5[imo]); }
			if(SPEED_TEXT1_6[imo]){ setValue(jQuery("#SPEED_TEXT1_6"), SPEED_TEXT1_6[imo]); }
			if(SPEED_TEXT2_6[imo]){ setValue(jQuery("#SPEED_TEXT2_6"), SPEED_TEXT2_6[imo]); }
			if(SPEED_TEXT1_7[imo]){ setValue(jQuery("#SPEED_TEXT1_7"), SPEED_TEXT1_7[imo]); }
			if(SPEED_TEXT2_7[imo]){ setValue(jQuery("#SPEED_TEXT2_7"), SPEED_TEXT2_7[imo]); }
			if(CONSUMPTION1_1[imo]){ setValue(jQuery("#CONSUMPTION1_1"), CONSUMPTION1_1[imo]); }
			if(CONSUMPTION2_1[imo]){ setValue(jQuery("#CONSUMPTION2_1"), CONSUMPTION2_1[imo]); }
			if(CONSUMPTION1_2[imo]){ setValue(jQuery("#CONSUMPTION1_2"), CONSUMPTION1_2[imo]); }
			if(CONSUMPTION2_2[imo]){ setValue(jQuery("#CONSUMPTION2_2"), CONSUMPTION2_2[imo]); }
			if(CONSUMPTION1_3[imo]){ setValue(jQuery("#CONSUMPTION1_3"), CONSUMPTION1_3[imo]); }
			if(CONSUMPTION2_3[imo]){ setValue(jQuery("#CONSUMPTION2_3"), CONSUMPTION2_3[imo]); }
			if(CONSUMPTION1_4[imo]){ setValue(jQuery("#CONSUMPTION1_4"), CONSUMPTION1_4[imo]); }
			if(CONSUMPTION2_4[imo]){ setValue(jQuery("#CONSUMPTION2_4"), CONSUMPTION2_4[imo]); }
			if(CONSUMPTION1_5[imo]){ setValue(jQuery("#CONSUMPTION1_5"), CONSUMPTION1_5[imo]); }
			if(CONSUMPTION2_5[imo]){ setValue(jQuery("#CONSUMPTION2_5"), CONSUMPTION2_5[imo]); }
			if(CONSUMPTION1_6[imo]){ setValue(jQuery("#CONSUMPTION1_6"), CONSUMPTION1_6[imo]); }
			if(CONSUMPTION2_6[imo]){ setValue(jQuery("#CONSUMPTION2_6"), CONSUMPTION2_6[imo]); }
			if(CONSUMPTION1_7[imo]){ setValue(jQuery("#CONSUMPTION1_7"), CONSUMPTION1_7[imo]); }
			if(CONSUMPTION2_7[imo]){ setValue(jQuery("#CONSUMPTION2_7"), CONSUMPTION2_7[imo]); }
			if(CONSUMPTION_TEXT1_1[imo]){ setValue(jQuery("#CONSUMPTION_TEXT1_1"), CONSUMPTION_TEXT1_1[imo]); }
			if(CONSUMPTION_TEXT2_1[imo]){ setValue(jQuery("#CONSUMPTION_TEXT2_1"), CONSUMPTION_TEXT2_1[imo]); }
			if(CONSUMPTION_TEXT1_2[imo]){ setValue(jQuery("#CONSUMPTION_TEXT1_2"), CONSUMPTION_TEXT1_2[imo]); }
			if(CONSUMPTION_TEXT2_2[imo]){ setValue(jQuery("#CONSUMPTION_TEXT2_2"), CONSUMPTION_TEXT2_2[imo]); }
			if(CONSUMPTION_TEXT1_3[imo]){ setValue(jQuery("#CONSUMPTION_TEXT1_3"), CONSUMPTION_TEXT1_3[imo]); }
			if(CONSUMPTION_TEXT2_3[imo]){ setValue(jQuery("#CONSUMPTION_TEXT2_3"), CONSUMPTION_TEXT2_3[imo]); }
			if(CONSUMPTION_TEXT1_4[imo]){ setValue(jQuery("#CONSUMPTION_TEXT1_4"), CONSUMPTION_TEXT1_4[imo]); }
			if(CONSUMPTION_TEXT2_4[imo]){ setValue(jQuery("#CONSUMPTION_TEXT2_4"), CONSUMPTION_TEXT2_4[imo]); }
			if(CONSUMPTION_TEXT1_5[imo]){ setValue(jQuery("#CONSUMPTION_TEXT1_5"), CONSUMPTION_TEXT1_5[imo]); }
			if(CONSUMPTION_TEXT2_5[imo]){ setValue(jQuery("#CONSUMPTION_TEXT2_5"), CONSUMPTION_TEXT2_5[imo]); }
			if(CONSUMPTION_TEXT1_6[imo]){ setValue(jQuery("#CONSUMPTION_TEXT1_6"), CONSUMPTION_TEXT1_6[imo]); }
			if(CONSUMPTION_TEXT2_6[imo]){ setValue(jQuery("#CONSUMPTION_TEXT2_6"), CONSUMPTION_TEXT2_6[imo]); }
			if(CONSUMPTION_TEXT1_7[imo]){ setValue(jQuery("#CONSUMPTION_TEXT1_7"), CONSUMPTION_TEXT1_7[imo]); }
			if(CONSUMPTION_TEXT2_7[imo]){ setValue(jQuery("#CONSUMPTION_TEXT2_7"), CONSUMPTION_TEXT2_7[imo]); }
			//END OF BUNKER FUEL
			
			//SPEED FOR VOYAGE LEGS
			jQuery(".speed").each(function(){
				setValue(jQuery(this), fNum(speeds[imo]));
			});
			//END OF SPEED FOR VOYAGE LEGS
			
			iframeve = document.getElementById('map_iframeve');
  			iframeve.src = "map/map_voyage_estimator.php?imo="+imo;
			
			setValue(jQuery("#div_dwt_id"), fNum(dwts[imo]));
		},
	});
	//END OF DETAILS COMING FROM SHIP NAME
	
	//GET PORTS
	$(".port_from").autocomplete({
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
			setValue(jQuery(".port_from"), ui.item.value);
		},
	});
	
	$("#port_to1_id").autocomplete({
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
			setValue(jQuery("#port_to1_id"), ui.item.value);
			
			portTo1Calc(true);
			calculateDates();
			calculateSeaPortDays();
			addPort(1, ui.item.value);
		},
	});
	//END OF GET PORTS
});

//SHOW DATE PICKER
$(".date").datepicker({ 
	dateFormat: "dd/mm/yy, DD",
	onSelect: function(date) {
		jQuery(this).val(date);
		
		calculateDates();
		calculateBunkerConsumption();
	},
});
//END OF SHOW DATE PICKER

//PORTS CALCULATIONS
function portTo1Calc(triggerajax){
	from = getValue(jQuery("#port_from1_id"));

	if(from){
		from = jQuery.trim(from);
		to = getValue(jQuery("#port_to1_id"));
		to = jQuery.trim(to);

		if(from&&to){ portTo1DistCalc(to, from, triggerajax); }
	}
}

function portTo1DistCalc(to, from, triggerajax){
	fromx = getValue(jQuery("#port_from1_id"));
	fromx = jQuery.trim(fromx);
	tox = getValue(jQuery("#port_to1_id"));
	tox = jQuery.trim(tox);

	distance = valueU(jQuery("#div_distance_miles1_id"));
	if(getValue(jQuery("#div_sea_margin1_id"))){
		distance = valueU(jQuery("#div_sea_margin1_id"));
	}

	if(to!=tox||from!=fromx||!distance||triggerajax){
		setValue(jQuery("#div_distance_miles1_id"), 'calculating...');

		jQuery.ajax({
			type: 'POST',
			url: "ajax_voyage_estimator.php?dc=1&from="+from+"&to="+to,
			data:  '',

			success: function(data) {
				setValue(jQuery("#div_distance_miles1_id"), fNum(data));

				distance = valueU(jQuery("#div_distance_miles1_id"));
				if(getValue(jQuery("#div_sea_margin1_id"))){
					distance = valueU(jQuery("#div_sea_margin1_id"));
				}
				speed = valueU(jQuery("#speed1_id"));

				if(speed == 0){
					speed = 13;
					setValue(jQuery("#speed1_id"), fNum(speed));
				}
				
				sea = ( distance / valueU(jQuery("#speed1_id")) / 24);

				setValue(jQuery("#div_voyage_days1_id"), fNum(sea));

				calculateDates();
				calculateSeaPortDays();
				calculateBunkerConsumption();
			}
		});
	}else{
		distance = valueU(jQuery("#div_distance_miles1_id"));
		if(getValue(jQuery("#div_sea_margin1_id"))){
			distance = valueU(jQuery("#div_sea_margin1_id"));
		}
		speed = valueU(jQuery("#speed1_id"));

		if(speed == 0){
			speed = 13;
			setValue(jQuery("#speed1_id"), fNum(speed));
		}

		sea = ( distance / valueU(jQuery("#speed1_id")) / 24);

		setValue(jQuery("#div_voyage_days1_id"), fNum(sea));

		calculateDates();
		calculateSeaPortDays();
		calculateBunkerConsumption();
	}
}

function portToCalc(triggerajax, row){
	from = getValue(jQuery("#div_port_from"+row+"_id"));

	if(from){
		from = jQuery.trim(from);
		to = getValue(jQuery("#port_to"+row+"_id"));
		to = jQuery.trim(to);

		if(from&&to){ portToDistCalc(to, from, triggerajax, row); }
	}
}

function portToDistCalc(to, from, triggerajax, row){
	fromx = getValue(jQuery("#div_port_from"+row+"_id"));
	fromx = jQuery.trim(fromx);
	tox = getValue(jQuery("#port_to"+row+"_id"));
	tox = jQuery.trim(tox);

	distance = valueU(jQuery("#div_distance_miles"+row+"_id"));
	if(getValue(jQuery("#div_sea_margin"+row+"_id"))){
		distance = valueU(jQuery("#div_sea_margin"+row+"_id"));
	}

	if(to!=tox||from!=fromx||!distance||triggerajax){
		setValue(jQuery("#div_distance_miles"+row+"_id"), 'calculating...');

		jQuery.ajax({
			type: 'POST',
			url: "ajax_voyage_estimator.php?dc=1&from="+from+"&to="+to,
			data:  '',

			success: function(data) {
				setValue(jQuery("#div_distance_miles"+row+"_id"), fNum(data));

				distance = valueU(jQuery("#div_distance_miles"+row+"_id"));
				if(getValue(jQuery("#div_sea_margin"+row+"_id"))){
					distance = valueU(jQuery("#div_sea_margin"+row+"_id"));
				}
				speed = valueU(jQuery("#speed"+row+"_id"));

				if(speed == 0){
					speed = 13;
					setValue(jQuery("#speed"+row+"_id"), fNum(speed));
				}
				
				sea = ( distance / valueU(jQuery("#speed"+row+"_id")) / 24);

				setValue(jQuery("#div_voyage_days"+row+"_id"), fNum(sea));

				calculateDates2(row);
				calculateSeaPortDays();
				calculateBunkerConsumption();
			}
		});
	}else{
		distance = valueU(jQuery("#div_distance_miles"+row+"_id"));
		if(getValue(jQuery("#div_sea_margin"+row+"_id"))){
			distance = valueU(jQuery("#div_sea_margin"+row+"_id"));
		}
		speed = valueU(jQuery("#speed"+row+"_id"));

		if(speed == 0){
			speed = 13;
			setValue(jQuery("#speed"+row+"_id"), fNum(speed));
		}

		sea = ( distance / valueU(jQuery("#speed"+row+"_id")) / 24);

		setValue(jQuery("#div_voyage_days"+row+"_id"), fNum(sea));

		calculateDates2(row);
		calculateSeaPortDays();
		calculateBunkerConsumption();
	}
}
//END OF PORTS CALCULATIONS

//CALCULATE DATES
function calculateDates(){
	date = getValue(jQuery("#date_from1_id"));
	days = valueU(jQuery("#div_tie_days1_id")) + valueU(jQuery("#div_voyage_days1_id")) + valueU(jQuery("#canal1_id")) + valueU(jQuery("#weather_extra1_id"));

	adate = addDays(date, days);

	setValue(jQuery("#div_date_to1_id"), adate);
}
//END OF CALCULATE DATES

//CALCULATE DATES 2
function calculateDates2(row){
	date = getValue(jQuery("#div_date_from"+row+"_id"));
	days = valueU(jQuery("#div_tie_days"+row+"_id")) + valueU(jQuery("#div_voyage_days"+row+"_id")) + valueU(jQuery("#canal"+row+"_id")) + valueU(jQuery("#weather_extra"+row+"_id"));

	adate = addDays(date, days);

	setValue(jQuery("#div_date_to"+row+"_id"), adate);
}
//END OF CALCULATE DATES 2

//DISTANCE MILES CALCULATIONS
function computeDistanceMiles1(percent){
	if(percent){
		ans = uNum(getValue(jQuery("#div_distance_miles1_id")))*((percent*0.01)+1);
		
		setValue(jQuery("#div_sea_margin1_id"), fNum(ans));
	}else{
		setValue(jQuery("#div_sea_margin1_id"), '');
	}
	
	calculateDates();
	portTo1Calc(true);
	calculateSeaPortDays();
	calculateBunkerConsumption();
}

function computeDistanceMiles(percent, row){
	if(percent){
		ans = uNum(getValue(jQuery("#div_distance_miles"+row+"_id")))*((percent*0.01)+1);
		
		setValue(jQuery("#div_sea_margin"+row+"_id"), fNum(ans));
	}else{
		setValue(jQuery("#div_sea_margin"+row+"_id"), '');
	}
	
	calculateDates2(row);
	portToCalc(true, row);
	calculateSeaPortDays();
	calculateBunkerConsumption();
}
//END OF DISTANCE MILES CALCULATIONS

//LOADING/DISCHARGING CARGO CALCULATIONS
function loadingDischargingCalc(row){
	volume = valueU(jQuery('#cargo_quantity'+row+'_id')) * valueU(jQuery('#div_sf'+row+'_id'));

	setValue(jQuery('#div_cargo_volume'+row+'_id'), fNum(volume));

	ld = 0;
	ld = valueU(jQuery('#cargo_quantity'+row+'_id')) / valueU(jQuery('#ld_rate'+row+'_id'));

	setValue(jQuery('#div_load_days'+row+'_id'), fNum(ld));
}
//END OF LOADING/DISCHARGING CARGO CALCULATIONS

//CALCULATE DWCC
function calculateDWCC(){
	var dwcc_amount1 = uNum(jQuery('#div_ifo_ballast_consumption').text()) + uNum(jQuery('#div_ifo_loading_consumption').text()) + uNum(jQuery('#div_ifo_bunker_stop_consumption').text()) + uNum(jQuery('#div_ifo_laden_consumption').text()) + uNum(jQuery('#div_ifo_discharging_consumption').text()) + uNum(jQuery('#div_ifo_repositioning_consumption').text()) + uNum(jQuery('#div_ifo_port_consumption').text());
	var dwcc_amount2 = uNum(jQuery('#div_mdo_ballast_consumption').text()) + uNum(jQuery('#div_mdo_loading_consumption').text()) + uNum(jQuery('#div_mdo_bunker_stop_consumption').text()) + uNum(jQuery('#div_mdo_laden_consumption').text()) + uNum(jQuery('#div_mdo_discharging_consumption').text()) + uNum(jQuery('#div_mdo_repositioning_consumption').text()) + uNum(jQuery('#div_mdo_port_consumption').text());
	var dwcc_amount5 = dwcc_amount1 + dwcc_amount2 + uNum(jQuery("#ifo_reserve_id").val()) + uNum(jQuery("#mdo_reserve_id").val()) + uNum(jQuery("#dwcc_fw1_id").val()) + uNum(jQuery("#dwcc_constant1_id").val());
	var dwcc_amount6 = uNum(jQuery("#div_dwt_id").text()) - dwcc_amount5;

	setValue(jQuery('#div_dwcc_amount1_id'), fNum(dwcc_amount1));
	setValue(jQuery('#div_dwcc_amount2_id'), fNum(dwcc_amount2));
	setValue(jQuery('#div_dwcc_amount3_id'), fNum(jQuery("#ifo_reserve_id").val()));
	setValue(jQuery('#div_dwcc_amount4_id'), fNum(jQuery("#mdo_reserve_id").val()));
	setValue(jQuery('#div_dwcc_amount5_id'), fNum(dwcc_amount5));
	setValue(jQuery('#div_dwcc_amount6_id'), fNum(dwcc_amount6));
}
//END OF CALCULATE DWCC

//CANAL CALCULATIONS
function canalTotal(){
	cbook1 = uNum(getValue(jQuery("#cbook1_id")));
	ctug1 = uNum(getValue(jQuery("#ctug1_id")));
	cline1 = uNum(getValue(jQuery("#cline1_id")));
	cmisc1 = uNum(getValue(jQuery("#cmisc1_id")));

	ctotal1 = cbook1 + ctug1 + cline1 + cmisc1;

	setValue(jQuery("#div_ctotal1_id"), fNum(ctotal1))

	cbook2 = uNum(getValue(jQuery("#cbook2_id")));
	ctug2 = uNum(getValue(jQuery("#ctug2_id")));
	cline2 = uNum(getValue(jQuery("#cline2_id")));
	cmisc2 = uNum(getValue(jQuery("#cmisc2_id")));

	ctotal2 = cbook2 + ctug2 + cline2 + cmisc2;

	setValue(jQuery("#div_ctotal2_id"), fNum(ctotal2))

	canal_total = ctotal1 + ctotal2;

	setValue(jQuery("#div_canal_total_id"), fNum(canal_total));
}
//END OF CANAL CALCULATIONS

//PORT SETUP
function setupPortInterface(rowCount){
	//CALCULATE DEMURRAGE/DESPATCH
	dem = uNum(getValue(jQuery("#dem"+rowCount+"_id")));
	
	div_des = dem/2;
	
	setValue(jQuery("#div_des"+rowCount+"_id"), fNum(div_des));
	
	var load_days = 0;
	load_days = sumClass('load_days');
	load_days = load_days / 24;
	
	var des = 0;
	des = sumClass('des');
	
	if(load_days<0){
		despatch = -1 * load_days * (des);
		demurrage = 0;
	}else{
		despatch = 0;
		demurrage = load_days * (des);
	}
	
	setValue(jQuery("#div_demurrage_total_id"), fNum(demurrage));
	setValue(jQuery("#div_despatch_total_id"), fNum(despatch));
	//END OF CALCULATE DEMURRAGE/DESPATCH
	
	//GET PORT TOTALS
	var ports_total = 0;
	ports_total = sumClass('da_quick_input');
	ports_total = ports_total + (demurrage - despatch);
	
	setValue(jQuery("#div_ports_total_id"), fNum(ports_total));
	setValue(jQuery("#div_port_total_id"), fNum(ports_total));
	//END OF GET PORT TOTALS
}
//END OF PORT SETUP

//FREIGHT RATE CALCULATIONS
function result1(){
	//GROSS FREIGHT ($) - RESULT 1
	var cargo_quantity = 0;
	var loadingCount = 1;
	
	jQuery(".voyage_type").each(function(){
		if(jQuery(this).val()=="Loading"){
			cargo_quantity += valueU(jQuery("#cargo_quantity"+loadingCount+"_id"));
		}
		
		loadingCount++;
	});

	div_gross_freight1_id = uNum(cargo_quantity) * uNum(getValue(jQuery("#freight_rate1_id")));
	setValue(jQuery("#div_gross_freight1_id"), fNum(div_gross_freight1_id));
	//END OF GROSS FREIGHT ($) - RESULT 1
	
	//BROKER COMMISSION - RESULT 1
	div_broker_comm1_id = (uNum(getValue(jQuery("#broker_comm1_id"))) + uNum(getValue(jQuery("#add_comm1_id")))) / 100 * div_gross_freight1_id;
	setValue(jQuery("#div_broker_comm1_id"), fNum(div_broker_comm1_id));
	//END OF BROKER COMMISSION - RESULT 1
	
	//INCOME - RESULT 1
	div_income1_id = div_gross_freight1_id - div_broker_comm1_id - uNum(getValue(jQuery("#div_total_voyage_disbursment_id")));
	if(div_income1_id>0){
		setValue(jQuery("#div_income1_id"), '<span style="color:#006000;">'+fNum(div_income1_id)+'</span>');
	}else{
		setValue(jQuery("#div_income1_id"), '<span style="color:#ff0000;">'+fNum(div_income1_id)+'</span>');
	}
	//END OF INCOME - RESULT 1
	
	//TCE ($/DAY) - RESULT 1
	div_tce1_id = div_income1_id / uNum(getValue(jQuery("#voyage_total_days")));
	setValue(jQuery("#div_tce1_id"), fNum(div_tce1_id));
	//END OF TCE ($/DAY) - RESULT 1
}
//END OF FREIGHT RATE CALCULATIONS

//TCE CALCULATIONS
function result2(){
	//INCOME - RESULT 2
	div_income2_id = uNum(getValue(jQuery("#tce2_id"))) * uNum(getValue(jQuery("#voyage_total_days")));
	setValue(jQuery("#div_income2_id"), fNum(div_income2_id));
	//END OF INCOME - RESULT 2
	
	//GROSS FREIGHT ($) - RESULT 2
	div_gross_freight2_id = (div_income2_id + uNum(getValue(jQuery("#div_total_voyage_disbursment_id"))) ) / (100 - uNum(getValue(jQuery("#broker_comm2_id"))) - uNum(getValue(jQuery("#add_comm2_id")))) * 100;
	setValue(jQuery("#div_gross_freight2_id"), fNum(div_gross_freight2_id));
	//END OF GROSS FREIGHT ($) - RESULT 2
	
	//FREIGHT RATE - RESULT 2
	var cargo_quantity2 = 0;
	var loadingCount2 = 1;
	
	jQuery(".voyage_type").each(function(){
		if(jQuery(this).val()=="Loading"){
			cargo_quantity2 += valueU(jQuery("#cargo_quantity"+loadingCount2+"_id"));
		}
		
		loadingCount2++;
	});
	
	div_freight_rate2_id = div_gross_freight2_id / uNum(cargo_quantity2);
	setValue(jQuery("#div_freight_rate2_id"), fNum(div_freight_rate2_id));
	//END OF FREIGHT RATE - RESULT 2
	
	//BROKER COMMISSION - RESULT 2
	div_broker_comm2_id = (uNum(getValue(jQuery("#broker_comm2_id"))) + uNum(getValue(jQuery("#add_comm2_id"))) ) / 100 * div_gross_freight2_id;
	setValue(jQuery("#div_broker_comm2_id"), fNum(div_broker_comm2_id));
	//END OF BROKER COMMISSION - RESULT 2
}
//END OF TCE CALCULATIONS

//OTHER FUNCTIONS
function thread(){
	calculateDates();
	portTo1Calc(true);
	calculateSeaPortDays();
	calculateBunkerConsumption();
	calculateDWCC();
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
	});
});

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
	if(elem.prop("tagName")=="DIV"){
		return fNum(elem.html());
	}else{
		return fNum(elem.val());
	}
}

function valueU(elem){
	if(elem.prop("tagName")=="DIV"){
		return uNum(elem.html());
	}else{
		return uNum(elem.val());
	}
}

function setValue(elem, value){
	if(elem.prop("tagName")=="DIV"){
		elem.html(value);
	}else{
		elem.val(value);
	}
}

function getValue(elem){
	if(elem.prop("tagName")=="DIV"){
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

//GET VESSEL BY
function getVesselBy(val){
	jQuery('#vessel_by_1').hide();
	jQuery('#vessel_by_2').hide();
	jQuery("#ship_info").hide();
	jQuery("#dwt_type_id").val(0);
	jQuery("#vessel_name_or_imo_id").val("");
	setValue(jQuery("#div_dwt_id"), "");
	
	jQuery("#shipdetailshref").html("");
	jQuery(".speed").each(function(){
		setValue(jQuery(this), '');
	});
	
	if(val!=0){ jQuery('#vessel_by_'+val).show(); }
}
//END OF GET VESSEL BY

//ADD SEQUENCE
function addSequenceCargo(){
	var rowCount = jQuery('#voyage_legs_id tr#voyage_legs_row:last').attr('class');
	rowCount = parseInt(rowCount.replace(/[^0-9]/g, ''));

	jQuery('#div_cargo_legs_type'+rowCount+'_id').text(jQuery('#voyage_type'+rowCount+'_id').val());
	
	if(jQuery("#voyage_type"+rowCount+"_id").val()=='Loading' || jQuery("#voyage_type"+rowCount+"_id").val()=='Discharging' || jQuery("#voyage_type"+rowCount+"_id").val()=='Bunker Stop'){
		jQuery('#div_cargo'+rowCount+'_id').text('');
		jQuery('#div_sf'+rowCount+'_id').text('');
		jQuery('#div_cargo_quantity'+rowCount+'_id').text('');
		jQuery('#div_cargo_volume'+rowCount+'_id').text('');
		jQuery('#div_ld_rate'+rowCount+'_id').text('');
		jQuery('#div_load_days'+rowCount+'_id').text('');
		jQuery('#div_wdt'+rowCount+'_id').text('');
		jQuery('#div_wadt'+rowCount+'_id').text('');
		jQuery('#div_tie_days'+rowCount+'_id').text('');
		jQuery('#div_canal'+rowCount+'_id').text('');
		jQuery('#div_weather_extra'+rowCount+'_id').text('');
	
		if(jQuery("#voyage_type"+rowCount+"_id").val()=='Discharging'){
			var cargos = jQuery('.cargo:last').attr('id');
			cargos = parseInt(cargos.replace(/[^0-9]/g, ''));
		
			jQuery('#div_cargo'+rowCount+'_id').append('<input type="text" id="cargo'+rowCount+'_id" name="cargo'+rowCount+'" style="width:130px;" class="req cargo" value="'+jQuery('#cargo'+cargos+'_id').val()+'" />');
			jQuery('#div_sf'+rowCount+'_id').append(jQuery('#div_sf'+cargos+'_id').text());
			jQuery('#div_cargo_quantity'+rowCount+'_id').append('<input type="text" id="cargo_quantity'+rowCount+'_id" name="cargo_quantity'+rowCount+'" style="width:80px;" class="req" value="'+jQuery('#cargo_quantity'+cargos+'_id').val()+'" onblur="this.value=fNum(this.value);" onkeyup="loadingDischargingCalc('+rowCount+'); calculateSeaPortDays(); calculateBunkerConsumption();" />');
			jQuery('#div_cargo_volume'+rowCount+'_id').append(jQuery('#div_cargo_volume'+cargos+'_id').text());
			jQuery('#div_ld_rate'+rowCount+'_id').append('<input type="text" id="ld_rate'+rowCount+'_id" name="ld_rate'+rowCount+'" style="width:80px;" class="req" onblur="this.value=fNum(this.value);" onkeyup="loadingDischargingCalc('+rowCount+'); calculateSeaPortDays(); calculateBunkerConsumption();" />');
		}else if(jQuery("#voyage_type"+rowCount+"_id").val()=='Loading'){
			jQuery('#div_cargo'+rowCount+'_id').append('<input type="text" id="cargo'+rowCount+'_id" name="cargo'+rowCount+'" style="width:130px;" class="req cargo" />');
			jQuery('#div_cargo_quantity'+rowCount+'_id').append('<input type="text" id="cargo_quantity'+rowCount+'_id" name="cargo_quantity'+rowCount+'" style="width:80px;" class="req" onblur="this.value=fNum(this.value);" onkeyup="loadingDischargingCalc('+rowCount+'); calculateSeaPortDays(); calculateBunkerConsumption();" />');
			jQuery('#div_ld_rate'+rowCount+'_id').append('<input type="text" id="ld_rate'+rowCount+'_id" name="ld_rate'+rowCount+'" style="width:80px;" class="req" onblur="this.value=fNum(this.value);" onkeyup="loadingDischargingCalc('+rowCount+'); calculateSeaPortDays(); calculateBunkerConsumption();" />');
		}
		
		var wdt = "";
		wdt += '<select id="wdt'+rowCount+'_id" name="wdt'+rowCount+'" style="width:80px;">';
		wdt += '<option value="SHINC">SHINC</option>';
		wdt += '<option value="SATSHINC or SSHINC">SATSHINC or SSHINC</option>';
		wdt += '<option value="SHEX">SHEX</option>';
		wdt += '<option value="SA/SHEX or SATPMSHEX">SA/SHEX or SATPMSHEX</option>';
		wdt += '<option value="SHEXEIU or SHEXEIUBE or SHEXUU">SHEXEIU or SHEXEIUBE or SHEXUU</option>';
		wdt += '<option value="FHINC">FHINC</option>';
		wdt += '<option value="FHEX">FHEX</option>';
		wdt += '</select>';
		jQuery('#div_wdt'+rowCount+'_id').append(wdt);
		
		jQuery('#div_wadt'+rowCount+'_id').append('<input type="text" id="wadt'+rowCount+'_id" name="wadt'+rowCount+'" style="width:40px;" class="req wadt" onblur="this.value=fNum(this.value);" onkeyup="calculateSeaPortDays(); calculateBunkerConsumption();" />');
		jQuery('#div_tie_days'+rowCount+'_id').append('<input type="text" id="tie_days'+rowCount+'_id" name="tie_days'+rowCount+'" style="width:40px;" onblur="this.value=fNum(this.value);" onkeyup="calculateSeaPortDays(); calculateBunkerConsumption();" class="tie_days" />');
		
		if(jQuery("#voyage_type"+rowCount+"_id").val()=='Bunker Stop'){
			jQuery('#div_canal'+rowCount+'_id').append('<input type="text" id="canal'+rowCount+'_id" name="canal'+rowCount+'" style="width:40px;" onblur="this.value=fNum(this.value);" onkeyup="calculateDates2('+rowCount+'); portToCalc(true, '+rowCount+'); calculateSeaPortDays();" class="canal" />');
			jQuery('#div_weather_extra'+rowCount+'_id').append('<input type="text" id="weather_extra'+rowCount+'_id" name="weather_extra'+rowCount+'" style="width:40px;" onblur="this.value=fNum(this.value);" onkeyup="calculateDates2('+rowCount+'); portToCalc(true, '+rowCount+'); calculateSeaPortDays();" class="weather_extra" />');
		}
		
		$(function(){
			//LOADING/DISCHARGING CARGO
			$("#cargo"+rowCount+"_id").autocomplete({
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
						setValue(jQuery("#div_sf"+rowCount+"_id"), fNum(sf));
					}
					
					loadingDischargingCalc(rowCount);
					calculateSeaPortDays();
					thread();
				},
			});
			//END OF LOADING/DISCHARGING CARGO
		});
	}else{
		jQuery('#div_cargo'+rowCount+'_id').text('');
		jQuery('#div_sf'+rowCount+'_id').text('');
		jQuery('#div_cargo_quantity'+rowCount+'_id').text('');
		jQuery('#div_cargo_volume'+rowCount+'_id').text('');
		jQuery('#div_ld_rate'+rowCount+'_id').text('');
		jQuery('#div_load_days'+rowCount+'_id').text('');
		jQuery('#div_wdt'+rowCount+'_id').text('');
		jQuery('#div_wadt'+rowCount+'_id').text('');
		jQuery('#div_tie_days'+rowCount+'_id').text('');
		jQuery('#div_canal'+rowCount+'_id').text('');
		jQuery('#div_weather_extra'+rowCount+'_id').text('');
		
		if(jQuery("#voyage_type"+rowCount+"_id").val()!=''){
			if(rowCount==1){
				jQuery('#div_canal'+rowCount+'_id').append('<input type="text" id="canal'+rowCount+'_id" name="canal'+rowCount+'" style="width:40px;" onblur="this.value=fNum(this.value);" onkeyup="calculateDates(); portTo1Calc(true); calculateSeaPortDays();" class="canal" />');
				jQuery('#div_weather_extra'+rowCount+'_id').append('<input type="text" id="weather_extra'+rowCount+'_id" name="weather_extra'+rowCount+'" style="width:40px;" onblur="this.value=fNum(this.value);" onkeyup="calculateDates(); portTo1Calc(true); calculateSeaPortDays();" class="weather_extra" />');
			}else{
				jQuery('#div_canal'+rowCount+'_id').append('<input type="text" id="canal'+rowCount+'_id" name="canal'+rowCount+'" style="width:40px;" onblur="this.value=fNum(this.value);" onkeyup="calculateDates2('+rowCount+'); portToCalc(true, '+rowCount+'); calculateSeaPortDays();" class="canal" />');
				jQuery('#div_weather_extra'+rowCount+'_id').append('<input type="text" id="weather_extra'+rowCount+'_id" name="weather_extra'+rowCount+'" style="width:40px;" onblur="this.value=fNum(this.value);" onkeyup="calculateDates2('+rowCount+'); portToCalc(true, '+rowCount+'); calculateSeaPortDays();" class="weather_extra" />');
			}
		}
	}
}

function addSequence(){
	var rowCount = jQuery('#voyage_legs_id tr#voyage_legs_row:last').attr('class');
	rowCount = parseInt(rowCount.replace(/[^0-9]/g, ''));
	
	var ballast = 0;
	var loading = 0;
	var discharging = 0;
	var repositioning = 0;
	jQuery(".voyage_type").each(function(){
		if(jQuery(this).val()=="Ballast"){ ballast = 1; }
		if(jQuery(this).val()=="Loading"){ loading = 1; }
		if(jQuery(this).val()=="Discharging"){ discharging = 1; }
		if(jQuery(this).val()=="Repositioning"){ repositioning = 1; }
	});
	
	if(!repositioning){
		if(jQuery('#voyage_type'+rowCount+'_id').val()!=0 && jQuery('#port_from1_id').val()!='' && jQuery('#date_from1_id').val()!='' && jQuery('#speed1_id').val()!=''){
			var nextRowCount = rowCount+1;
			var bgColor = "";
			var nextRowVoyageLegs = "";
			var nextRowCargoLegs = "";
			
			if(rowCount%2==0){ bgColor = "#f5f5f5"; }
			else{ bgColor = "#e9e9e9"; }
			
			//VOYAGE LEGS
			nextRowVoyageLegs += '<tr id="voyage_legs_row" class="voyage_legs_row'+nextRowCount+'" bgcolor="'+bgColor+'">';
				nextRowVoyageLegs += '<td><div class="dp" style="padding-right:0px;"><a style="cursor:pointer;" onclick="deleteSequence(\''+nextRowCount+'\');"><img src="images/delete.png" border="0" /></a></div></td>';
				nextRowVoyageLegs += '<td>';
					nextRowVoyageLegs += '<div class="dp" id="div_voyage_type'+nextRowCount+'_id">';
						nextRowVoyageLegs += '<select id="voyage_type'+nextRowCount+'_id" name="voyage_type'+nextRowCount+'" style="width:110px;" class="req voyage_type" onchange="addSequenceCargo();">';
							
							if(ballast && !loading){
								nextRowVoyageLegs += '<option value="">- Select Type -</option>';
								nextRowVoyageLegs += '<option value="Loading">Loading</option>';
								nextRowVoyageLegs += '<option value="Bunker Stop">Bunker Stop</option>';
								nextRowVoyageLegs += '<option value="Laden">Laden</option>';
							}else if(loading && !discharging){
								nextRowVoyageLegs += '<option value="">- Select Type -</option>';
								nextRowVoyageLegs += '<option value="Loading">Loading</option>';
								nextRowVoyageLegs += '<option value="Bunker Stop">Bunker Stop</option>';
								nextRowVoyageLegs += '<option value="Laden">Laden</option>';
								nextRowVoyageLegs += '<option value="Discharging">Discharging</option>';
							}else if(discharging){
								nextRowVoyageLegs += '<option value="">- Select Type -</option>';
								nextRowVoyageLegs += '<option value="Loading">Loading</option>';
								nextRowVoyageLegs += '<option value="Bunker Stop">Bunker Stop</option>';
								nextRowVoyageLegs += '<option value="Laden">Laden</option>';
								nextRowVoyageLegs += '<option value="Discharging">Discharging</option>';
								nextRowVoyageLegs += '<option value="Repositioning">Repositioning</option>';
							}
							
						nextRowVoyageLegs += '</select>';
					nextRowVoyageLegs += '</div>';
				nextRowVoyageLegs += '</td>';
				nextRowVoyageLegs += '<td><div class="dp" id="div_port_from'+nextRowCount+'_id">'+jQuery('#port_to'+rowCount+'_id').val()+'</div></td>';
				nextRowVoyageLegs += '<td><div class="dp" id="div_date_from'+nextRowCount+'_id">'+jQuery('#div_date_to'+rowCount+'_id').text()+'</div></td>';
				nextRowVoyageLegs += '<td><div class="dp" id="div_port_to'+nextRowCount+'_id"><input type="text" id="port_to'+nextRowCount+'_id" name="port_to'+nextRowCount+'" style="width:150px;" class="req port_to" /></div></td>';
				nextRowVoyageLegs += '<td><div class="dp" id="div_date_to'+nextRowCount+'_id">'+jQuery('#div_date_to'+rowCount+'_id').text()+'</div></td>';
				nextRowVoyageLegs += '<td><div class="dp" id="div_speed'+nextRowCount+'_id"><input type="text" id="speed'+nextRowCount+'_id" name="speed'+nextRowCount+'" style="width:40px;" value="'+jQuery('#speed1_id').val()+'" class="speed" onblur="this.value=fNum(this.value);" onkeyup="calculateDates2('+nextRowCount+'); portToCalc(true, '+nextRowCount+'); calculateSeaPortDays();" /></div></td>';
				nextRowVoyageLegs += '<td><div class="dp" id="div_distance_miles'+nextRowCount+'_id"></div></td>';
				nextRowVoyageLegs += '<td><div class="dp" id="div_input_percent'+nextRowCount+'_id"><input type="text" id="input_percent'+nextRowCount+'_id" name="input_percent'+nextRowCount+'" style="width:40px;" class="number" onkeyup="computeDistanceMiles(this.value, '+nextRowCount+');" onblur="this.value=fNum(this.value);" onkeyup="calculateDates2('+nextRowCount+'); portToCalc(true, '+nextRowCount+'); calculateSeaPortDays();" /></div></td>';
				nextRowVoyageLegs += '<td><div class="dp" id="div_sea_margin'+nextRowCount+'_id"></div></td>';
			nextRowVoyageLegs += '</tr>';
			
			jQuery('#voyage_legs_id tr:last').after(nextRowVoyageLegs);
			//END OF VOYAGE LEGS
			
			//CARGO LEGS
			nextRowCargoLegs += '<tr id="cargo_legs_row" class="cargo_legs_row'+nextRowCount+'" bgcolor="'+bgColor+'">';
				nextRowCargoLegs += '<td><div id="div_cargo_legs_type'+nextRowCount+'_id" class="dp" style="font-weight:bold;">&nbsp;</div></td>';
				nextRowCargoLegs += '<td><div class="dp" id="div_cargo'+nextRowCount+'_id">&nbsp;</div></td>';
				nextRowCargoLegs += '<td><div class="dp" id="div_sf'+nextRowCount+'_id">&nbsp;</div></td>';
				nextRowCargoLegs += '<td><div class="dp" id="div_cargo_quantity'+nextRowCount+'_id">&nbsp;</div></td>';
				nextRowCargoLegs += '<td><div class="dp" id="div_cargo_volume'+nextRowCount+'_id">&nbsp;</div></td>';
				nextRowCargoLegs += '<td><div class="dp" id="div_ld_rate'+nextRowCount+'_id">&nbsp;</div></td>';
				nextRowCargoLegs += '<td><div class="dp load_days" id="div_load_days'+nextRowCount+'_id">&nbsp;</div></td>';
				nextRowCargoLegs += '<td><div class="dp" id="div_wdt'+nextRowCount+'_id">&nbsp;</div></td>';
				nextRowCargoLegs += '<td><div class="dp" id="div_wadt'+nextRowCount+'_id">&nbsp;</div></td>';
				nextRowCargoLegs += '<td><div class="dp" id="div_tie_days'+nextRowCount+'_id">&nbsp;</div></td>';
				nextRowCargoLegs += '<td><div class="dp voyage_days" id="div_voyage_days'+nextRowCount+'_id">&nbsp;</div></td>';
				nextRowCargoLegs += '<td><div class="dp" id="div_canal'+nextRowCount+'_id">&nbsp;</div></td>';
				nextRowCargoLegs += '<td><div class="dp" id="div_weather_extra'+nextRowCount+'_id">&nbsp;</div></td>';
			nextRowCargoLegs += '</tr>';
			
			jQuery('#cargo_legs_id tr:last').after(nextRowCargoLegs);
			//END OF CARGO LEGS
			
			$(function(){
				$("#port_to"+nextRowCount+"_id").autocomplete({
					source: function(req, add){
						$.getJSON("ajax_voyage_estimator.php?port=1", req, function(data) {
							var suggestions = [];
			
							$.each(data, function(i, val){		
								suggestions.push(val.name);
								
								if(jQuery("#voyage_type"+nextRowCount+"_id").val()=='Bunker Stop'){
									average_price_ifo380s[val.name] = val.average_price_ifo380;
									average_price_mdos[val.name] = val.average_price_mdo;
									average_price_ifo180s[val.name] = val.average_price_ifo180;
									average_price_mgos[val.name] = val.average_price_mgo;
									average_price_ls180_1s[val.name] = val.average_price_ls180_1;
									average_price_ls380_1s[val.name] = val.average_price_ls380_1;
									average_price_lsmgos[val.name] = val.average_price_lsmgo;
									dateupdateds[val.name] = val.dateupdated;
								}
							});
			
							add(suggestions);
						});
					},
					select: function(e, ui) {
						str = ui.item.value;
					
						setValue(jQuery("#port_to"+nextRowCount+"_id"), str);
						
						if(jQuery("#voyage_type"+nextRowCount+"_id").val()=='Bunker Stop'){
							if(average_price_ifo380s[str]){
								setValue(jQuery('#div_ifo1_id'), fNum(average_price_ifo380s[str]));
							}else{
								setValue(jQuery('#div_ifo1_id'), fNum(0));
							}
							
							if(average_price_ifo180s[str]){
								setValue(jQuery('#div_ifo2_id'), fNum(average_price_ifo180s[str]));
							}else{
								setValue(jQuery('#div_ifo2_id'), fNum(0));
							}
							
							if(average_price_ls380_1s[str]){
								setValue(jQuery('#div_ifo3_id'), fNum(average_price_ls380_1s[str]));
							}else{
								setValue(jQuery('#div_ifo3_id'), fNum(0));
							}
							
							if(average_price_ls180_1s[str]){
								setValue(jQuery('#div_ifo4_id'), fNum(average_price_ls180_1s[str]));
							}else{
								setValue(jQuery('#div_ifo4_id'), fNum(0));
							}
							
							if(average_price_mdos[str]){
								setValue(jQuery('#div_mdo1_id'), fNum(average_price_mdos[str]));
							}else{
								setValue(jQuery('#div_mdo1_id'), fNum(0));
							}
							
							if(average_price_mgos[str]){
								setValue(jQuery('#div_mdo2_id'), fNum(average_price_mgos[str]));
							}else{
								setValue(jQuery('#div_mdo2_id'), fNum(0));
							}
							
							if(average_price_lsmgos[str]){
								setValue(jQuery('#div_mdo3_id'), fNum(average_price_lsmgos[str]));
							}else{
								setValue(jQuery('#div_mdo3_id'), fNum(0));
							}
							
							if(dateupdateds[str]){
								jQuery('#bunker_price_dateupdated').text('Correct as of '+dateupdateds[str]);
							}else{
								jQuery('#bunker_price_dateupdated').text('');
							}
						}
						
						portToCalc(true, nextRowCount);
						addPort(nextRowCount, ui.item.value);
					},
				});
			});
		}else{
			alert('Please complete the current sequence.');
		}
	}else{
		alert('You have reached the end of your sequence.');
	}
}
//END OF ADD SEQUENCE

//ADD PORT
function addPort(rowCount, portname){
	if(jQuery("#voyage_type"+rowCount+"_id").val()=='Loading' || jQuery("#voyage_type"+rowCount+"_id").val()=='Discharging' || jQuery("#voyage_type"+rowCount+"_id").val()=='Bunker Stop'){
		jQuery(".row_ports"+rowCount).remove();
	
		var bgColor = "";
		var nextRowPorts = "";
	
		if(rowCount%2==0){ bgColor = "#f5f5f5"; }
		else{ bgColor = "#e9e9e9"; }
		
		//PORTS
		nextRowPorts += '<tr id="row_ports" class="row_ports'+rowCount+'" bgcolor="'+bgColor+'">';
			nextRowPorts += '<td><div class="dp"><input type="text" id="dem'+rowCount+'_id" name="dem'+rowCount+'" style="width:150px;" onblur="this.value=fNum(this.value);" onkeyup="setupPortInterface('+rowCount+');" /></div></td>';
			nextRowPorts += '<td>';
				nextRowPorts += '<div class="dp">';
					nextRowPorts += '<select id="term'+rowCount+'_id" name="term'+rowCount+'" style="width:150px;">';
						nextRowPorts += '<option value="DHDLTSBENDS">DHDLTSBENDS</option>';
						nextRowPorts += '<option value="DHDATSBENDS">DHDATSBENDS</option>';
						nextRowPorts += '<option value="DHDWTSBENDS">DHDWTSBENDS</option>';
					nextRowPorts += '</select>';
				nextRowPorts += '</div>';
			nextRowPorts += '</td>';
			nextRowPorts += '<td><div class="dp des" id="div_des'+rowCount+'_id">&nbsp;</div></td>';
			nextRowPorts += '<td>';
				nextRowPorts += '<div class="dp">';
					nextRowPorts += '<select id="linerterms'+rowCount+'_id" name="linerterms'+rowCount+'" style="width:150px;">';
						nextRowPorts += '<option value="FILO">FILO</option>';
						nextRowPorts += '<option value="FILTD">FILTD</option>';
						nextRowPorts += '<option value="FIOLS">FIOLS</option>';
						nextRowPorts += '<option value="FIOSLSD">FIOSLSD</option>';
						nextRowPorts += '<option value="FIOSPT">FIOSPT</option>';
						nextRowPorts += '<option value="FIOST">FIOST</option>';
						nextRowPorts += '<option value="LIFO">LIFO</option>';
						nextRowPorts += '<option value="BTBT">BTBT</option>';
					nextRowPorts += '</select>';
				nextRowPorts += '</div>';
			nextRowPorts += '</td>';
			nextRowPorts += '<td><div class="dp"><a onclick="showPortDetails(\''+portname+'\', '+rowCount+', 0);" class="clickable">'+portname+'</a></div></td>';
			nextRowPorts += '<td><div class="dp"><input type="text" id="da_quick_input'+rowCount+'_id" name="da_quick_input'+rowCount+'" class="da_quick_input" style="width:150px;" onblur="this.value=fNum(this.value);" onkeyup="setupPortInterface('+rowCount+');" /></div></td>';
		nextRowPorts += '</tr>';
		
		jQuery('#row_ports_id tr#row_ports:last').after(nextRowPorts);
	}
	//END OF PORTS
}
//END OF ADD PORT

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
	
	sum += sumClass('load_days');
	sum += sumClass('wadt');
	sum += sumClass('tie_days');
	
	return sum;
}
//END OF CALCULATE PORT DAYS

//CALCULATE SEA DAYS
function calculateSeaDays(){
	sum = 0;
	
	sum += sumClass('voyage_days');
	sum += sumClass('canal');
	sum += sumClass('weather_extra');

	return sum;
}
//END OF CALCULATE SEA DAYS

//CALCULATE SEA PORT DAYS
function calculateSeaPortDays(){
	totalportdays = calculatePortDays();
	totalseadays = calculateSeaDays();

	setValue(jQuery("#voyage_port_days"), fNum(totalportdays));
	setValue(jQuery("#voyage_sea_days"), fNum(totalseadays));
	setValue(jQuery("#voyage_total_days"), fNum(totalseadays+totalportdays));
	
	voyageDisbursement();
}
//END OF CALCULATE SEA PORTDAYS

//CALCULATE BUNKER CONSUMPTION
function calculateBunkerConsumption(){
	var sum_ballast = 0;
	var sum_loading = 0;
	var sum_bunker_stop = 0;
	var sum_laden = 0;
	var sum_discharging = 0;
	var sum_repositioning = 0;

	jQuery(".voyage_type").each(function(){
		var row = jQuery(this).attr("name");
		row = parseInt(row.replace(/[^0-9]/g, ''));
	
		if(jQuery(this).val()=="Ballast"){
			sum_ballast += valueU(jQuery("#div_voyage_days"+row+"_id"));
			sum_ballast += valueU(jQuery("#canal"+row+"_id"));
			sum_ballast += valueU(jQuery("#weather_extra"+row+"_id"));
		}
		
		if(jQuery(this).val()=="Loading"){
			sum_loading += valueU(jQuery("#div_load_days"+row+"_id"));
			sum_loading += valueU(jQuery("#wadt"+row+"_id"));
			sum_loading += valueU(jQuery("#tie_days"+row+"_id"));
			sum_loading += valueU(jQuery("#div_voyage_days"+row+"_id"));
		}
		
		if(jQuery(this).val()=="Bunker Stop"){
			sum_bunker_stop += valueU(jQuery("#wadt"+row+"_id"));
			sum_bunker_stop += valueU(jQuery("#tie_days"+row+"_id"));
			sum_bunker_stop += valueU(jQuery("#div_voyage_days"+row+"_id"));
			sum_bunker_stop += valueU(jQuery("#canal"+row+"_id"));
			sum_bunker_stop += valueU(jQuery("#weather_extra"+row+"_id"));
		}
		
		if(jQuery(this).val()=="Laden"){
			sum_laden += valueU(jQuery("#div_voyage_days"+row+"_id"));
			sum_laden += valueU(jQuery("#canal"+row+"_id"));
			sum_laden += valueU(jQuery("#weather_extra"+row+"_id"));
		}
		
		if(jQuery(this).val()=="Discharging"){
			sum_discharging += valueU(jQuery("#div_load_days"+row+"_id"));
			sum_discharging += valueU(jQuery("#wadt"+row+"_id"));
			sum_discharging += valueU(jQuery("#tie_days"+row+"_id"));
			sum_discharging += valueU(jQuery("#div_voyage_days"+row+"_id"));
		}
		
		if(jQuery(this).val()=="Repositioning"){
			sum_repositioning += valueU(jQuery("#div_voyage_days"+row+"_id"));
			sum_repositioning += valueU(jQuery("#canal"+row+"_id"));
			sum_repositioning += valueU(jQuery("#weather_extra"+row+"_id"));
		}
	});
	
	//IFO/BALLAST
	div_ifo_ballast_consumption = getValue(jQuery("#ifo_ballast_id"))*sum_ballast;
	div_ifo_ballast_expense = div_ifo_ballast_consumption*(uNum(getValue(jQuery("#ifo1_id")))+uNum(getValue(jQuery("#ifo2_id")))+uNum(getValue(jQuery("#ifo3_id")))+uNum(getValue(jQuery("#ifo4_id"))));

	setValue(jQuery("#div_ifo_ballast_consumption"), fNum(div_ifo_ballast_consumption));
	setValue(jQuery("#div_ifo_ballast_expense"), fNum(div_ifo_ballast_expense));
	//END OF IFO/BALLAST
	
	//MDO/BALLAST
	div_mdo_ballast_consumption = getValue(jQuery("#mdo_ballast_id"))*sum_ballast;
	div_mdo_ballast_expense = div_mdo_ballast_consumption*(uNum(getValue(jQuery("#mdo1_id")))+uNum(getValue(jQuery("#mdo2_id")))+uNum(getValue(jQuery("#mdo3_id"))));

	setValue(jQuery("#div_mdo_ballast_consumption"), fNum(div_mdo_ballast_consumption));
	setValue(jQuery("#div_mdo_ballast_expense"), fNum(div_mdo_ballast_expense));
	//END OF MDO/BALLAST
	
	//IFO/LOADING
	div_ifo_loading_consumption = getValue(jQuery("#ifo_loading_id"))*sum_loading;
	div_ifo_loading_expense = div_ifo_loading_consumption*(uNum(getValue(jQuery("#ifo1_id")))+uNum(getValue(jQuery("#ifo2_id")))+uNum(getValue(jQuery("#ifo3_id")))+uNum(getValue(jQuery("#ifo4_id"))));

	setValue(jQuery("#div_ifo_loading_consumption"), fNum(div_ifo_loading_consumption));
	setValue(jQuery("#div_ifo_loading_expense"), fNum(div_ifo_loading_expense));
	//END OF IFO/LOADING
	
	//MDO/LOADING
	div_mdo_loading_consumption = getValue(jQuery("#mdo_loading_id"))*sum_loading;
	div_mdo_loading_expense = div_mdo_loading_consumption*(uNum(getValue(jQuery("#mdo1_id")))+uNum(getValue(jQuery("#mdo2_id")))+uNum(getValue(jQuery("#mdo3_id"))));

	setValue(jQuery("#div_mdo_loading_consumption"), fNum(div_mdo_loading_consumption));
	setValue(jQuery("#div_mdo_loading_expense"), fNum(div_mdo_loading_expense));
	//END OF MDO/LOADING
	
	//IFO/BUNKER STOP
	div_ifo_bunker_stop_consumption = getValue(jQuery("#ifo_bunker_stop_id"))*sum_bunker_stop;
	div_ifo_bunker_stop_expense = div_ifo_bunker_stop_consumption*(uNum(getValue(jQuery("#ifo1_id")))+uNum(getValue(jQuery("#ifo2_id")))+uNum(getValue(jQuery("#ifo3_id")))+uNum(getValue(jQuery("#ifo4_id"))));

	setValue(jQuery("#div_ifo_bunker_stop_consumption"), fNum(div_ifo_bunker_stop_consumption));
	setValue(jQuery("#div_ifo_bunker_stop_expense"), fNum(div_ifo_bunker_stop_expense));
	//END OF IFO/BUNKER STOP
	
	//MDO/BUNKER STOP
	div_mdo_bunker_stop_consumption = getValue(jQuery("#mdo_bunker_stop_id"))*sum_bunker_stop;
	div_mdo_bunker_stop_expense = div_mdo_bunker_stop_consumption*(uNum(getValue(jQuery("#mdo1_id")))+uNum(getValue(jQuery("#mdo2_id")))+uNum(getValue(jQuery("#mdo3_id"))));

	setValue(jQuery("#div_mdo_bunker_stop_consumption"), fNum(div_mdo_bunker_stop_consumption));
	setValue(jQuery("#div_mdo_bunker_stop_expense"), fNum(div_mdo_bunker_stop_expense));
	//END OF MDO/BUNKER STOP
	
	//IFO/LADEN
	div_ifo_laden_consumption = getValue(jQuery("#ifo_laden_id"))*sum_laden;
	div_ifo_laden_expense = div_ifo_laden_consumption*(uNum(getValue(jQuery("#ifo1_id")))+uNum(getValue(jQuery("#ifo2_id")))+uNum(getValue(jQuery("#ifo3_id")))+uNum(getValue(jQuery("#ifo4_id"))));

	setValue(jQuery("#div_ifo_laden_consumption"), fNum(div_ifo_laden_consumption));
	setValue(jQuery("#div_ifo_laden_expense"), fNum(div_ifo_laden_expense));
	//END OF IFO/LADEN
	
	//MDO/LADEN
	div_mdo_laden_consumption = getValue(jQuery("#mdo_laden_id"))*sum_laden;
	div_mdo_laden_expense = div_mdo_laden_consumption*(uNum(getValue(jQuery("#mdo1_id")))+uNum(getValue(jQuery("#mdo2_id")))+uNum(getValue(jQuery("#mdo3_id"))));

	setValue(jQuery("#div_mdo_laden_consumption"), fNum(div_mdo_laden_consumption));
	setValue(jQuery("#div_mdo_laden_expense"), fNum(div_mdo_laden_expense));
	//END OF MDO/LADEN
	
	//IFO/DISCHARGING
	div_ifo_discharging_consumption = getValue(jQuery("#ifo_discharging_id"))*sum_discharging;
	div_ifo_discharging_expense = div_ifo_discharging_consumption*(uNum(getValue(jQuery("#ifo1_id")))+uNum(getValue(jQuery("#ifo2_id")))+uNum(getValue(jQuery("#ifo3_id")))+uNum(getValue(jQuery("#ifo4_id"))));

	setValue(jQuery("#div_ifo_discharging_consumption"), fNum(div_ifo_discharging_consumption));
	setValue(jQuery("#div_ifo_discharging_expense"), fNum(div_ifo_discharging_expense));
	//END OF IFO/DISCHARGING
	
	//MDO/DISCHARGING
	div_mdo_discharging_consumption = getValue(jQuery("#mdo_discharging_id"))*sum_discharging;
	div_mdo_discharging_expense = div_mdo_discharging_consumption*(uNum(getValue(jQuery("#mdo1_id")))+uNum(getValue(jQuery("#mdo2_id")))+uNum(getValue(jQuery("#mdo3_id"))));

	setValue(jQuery("#div_mdo_discharging_consumption"), fNum(div_mdo_discharging_consumption));
	setValue(jQuery("#div_mdo_discharging_expense"), fNum(div_mdo_discharging_expense));
	//END OF MDO/DISCHARGING
	
	//IFO/REPOSITIONING
	div_ifo_repositioning_consumption = getValue(jQuery("#ifo_repositioning_id"))*sum_repositioning;
	div_ifo_repositioning_expense = div_ifo_repositioning_consumption*(uNum(getValue(jQuery("#ifo1_id")))+uNum(getValue(jQuery("#ifo2_id")))+uNum(getValue(jQuery("#ifo3_id")))+uNum(getValue(jQuery("#ifo4_id"))));

	setValue(jQuery("#div_ifo_repositioning_consumption"), fNum(div_ifo_repositioning_consumption));
	setValue(jQuery("#div_ifo_repositioning_expense"), fNum(div_ifo_repositioning_expense));
	//END OF IFO/REPOSITIONING
	
	//MDO/REPOSITIONING
	div_mdo_repositioning_consumption = getValue(jQuery("#mdo_repositioning_id"))*sum_repositioning;
	div_mdo_repositioning_expense = div_mdo_repositioning_consumption*(uNum(getValue(jQuery("#mdo1_id")))+uNum(getValue(jQuery("#mdo2_id")))+uNum(getValue(jQuery("#mdo3_id"))));

	setValue(jQuery("#div_mdo_repositioning_consumption"), fNum(div_mdo_repositioning_consumption));
	setValue(jQuery("#div_mdo_repositioning_expense"), fNum(div_mdo_repositioning_expense));
	//END OF MDO/REPOSITIONING
	
	portdays = calculatePortDays();
	
	//IFO/PORT
	div_ifo_port_consumption = getValue(jQuery("#ifo_port_id"))*portdays;
	div_ifo_port_expense = div_ifo_port_consumption*(uNum(getValue(jQuery("#ifo1_id")))+uNum(getValue(jQuery("#ifo2_id")))+uNum(getValue(jQuery("#ifo3_id")))+uNum(getValue(jQuery("#ifo4_id"))));

	setValue(jQuery("#div_ifo_port_consumption"), fNum(div_ifo_port_consumption));
	setValue(jQuery("#div_ifo_port_expense"), fNum(div_ifo_port_expense));
	//END OF IFO/PORT
	
	//MDO/PORT
	div_mdo_port_consumption = getValue(jQuery("#mdo_port_id"))*portdays;
	div_mdo_port_expense = div_mdo_port_consumption*(uNum(getValue(jQuery("#mdo1_id")))+uNum(getValue(jQuery("#mdo2_id")))+uNum(getValue(jQuery("#mdo3_id"))));

	setValue(jQuery("#div_mdo_port_consumption"), fNum(div_mdo_port_consumption));
	setValue(jQuery("#div_mdo_port_expense"), fNum(div_mdo_port_expense));
	//END OF MDO/PORT
	
	//IFO/RESERVE
	div_ifo_reserve_consumption = uNum(getValue(jQuery("#ifo_reserve_id")));
	div_ifo_reserve_expense = div_ifo_reserve_consumption*(uNum(getValue(jQuery("#ifo1_id")))+uNum(getValue(jQuery("#ifo2_id")))+uNum(getValue(jQuery("#ifo3_id")))+uNum(getValue(jQuery("#ifo4_id"))));

	setValue(jQuery("#div_ifo_reserve_consumption"), fNum(div_ifo_reserve_consumption));
	setValue(jQuery("#div_ifo_reserve_expense"), fNum(div_ifo_reserve_expense));
	//END OF IFO/RESERVE
	
	//MDO/RESERVE
	div_mdo_reserve_consumption = uNum(getValue(jQuery("#mdo_reserve_id")));
	div_mdo_reserve_expense = div_mdo_reserve_consumption*(uNum(getValue(jQuery("#mdo1_id")))+uNum(getValue(jQuery("#mdo2_id")))+uNum(getValue(jQuery("#mdo3_id"))));

	setValue(jQuery("#div_mdo_reserve_consumption"), fNum(div_mdo_reserve_consumption));
	setValue(jQuery("#div_mdo_reserve_expense"), fNum(div_mdo_reserve_expense));
	//END OF MDO/RESERVE
	
	//IFO TOTAL EXPENSE
	div_ifo_total_expense = uNum(div_ifo_ballast_expense) + uNum(div_ifo_loading_expense) + uNum(div_ifo_bunker_stop_expense) + uNum(div_ifo_laden_expense) + uNum(div_ifo_discharging_expense) + uNum(div_ifo_repositioning_expense) + uNum(div_ifo_port_expense) + uNum(div_ifo_reserve_expense);
	setValue(jQuery("#div_ifo_total_expense"), fNum(div_ifo_total_expense));
	//END OF IFO TOTAL EXPENSE
	
	//MDO TOTAL EXPENSE
	div_mdo_total_expense = uNum(div_mdo_ballast_expense) + uNum(div_mdo_loading_expense) + uNum(div_mdo_bunker_stop_expense) + uNum(div_mdo_laden_expense) + uNum(div_mdo_discharging_expense) + uNum(div_mdo_repositioning_expense) + uNum(div_mdo_port_expense) + uNum(div_mdo_reserve_expense);
	setValue(jQuery("#div_mdo_total_expense"), fNum(div_mdo_total_expense));
	//END OF MDO TOTAL EXPENSE
	
	bunker_total = div_ifo_total_expense + div_mdo_total_expense;
	setValue(jQuery("#div_bunker_total_id"), fNum(bunker_total));
	
	voyageDisbursement();
}
//END OF CALCULATE BUNKER CONSUMPTION

//VOYAGE DISBURESMENT
function voyageDisbursement(){
	vb1 = uNum(getValue(jQuery("#div_bunker_total_id")));
	vb2 = uNum(getValue(jQuery("#div_port_total_id")));
	vb3 = uNum(getValue(jQuery("#div_canal_total_id")));
	vb4 = uNum(getValue(jQuery("#add_insurance_id")));
	vb5 = uNum(getValue(jQuery("#ilohc_id")));
	vb6 = uNum(getValue(jQuery("#ilow_id")));
	vb7 = uNum(getValue(jQuery("#cve_id")));
	vb8 = uNum(getValue(jQuery("#ballast_bonus_id")));
	vb9 = uNum(getValue(jQuery("#miscellaneous_id")));

	div_total_voyage_disbursment_id = vb1 + vb2 + vb3 + vb4 + vb5 + vb6 + vb7 + vb8 + vb9;
	setValue(jQuery("#div_total_voyage_disbursment_id"), fNum(div_total_voyage_disbursment_id));
	
	result1();
	result2();
}
//END OF VOYAGE DISBURSMENT

//DELETE SEQUENCE
function deleteSequence(num){
	jQuery(".voyage_legs_row"+num).remove();
	jQuery(".cargo_legs_row"+num).remove();
}
//END OF DELETE SEQUENCE

//SHOW SHIP DETAILS
function showShipDetails(){
	jQuery("#shipdetails").dialog("close");
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
function showShipDetails2(){
	var iframe = $("#shipdetailiframe");

	$(iframe).contents().find("body").html("");

	jQuery("#shipdetailiframe")[0].src='misc/ship_data_update.php?imo='+gimo;
	jQuery("#shipdetails2").dialog("open");
}

jQuery("#shipdetails2").dialog( { autoOpen: false, width: '90%', height: jQuery(window).height()*0.9 });
jQuery("#shipdetails2").dialog("close");
//END OF SHOW EDITABLE SHIP DETAILS

//SHOW SHIP SPEED HISTORY
function showShipSpeedHistory(){
	var iframe2 = $("#shipspeedhistoryiframe");

	$(iframe2).contents().find("body").html("");

	jQuery("#shipspeedhistoryiframe")[0].src='misc/shipspeedhistory.php?imo='+gimo;
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

//MAIL/PRINT SHIP DETAILS
function mailItVe_2(){
	var imo = jQuery('#vessel_name_or_imo_id').val().substring(0,7);

	jQuery("#misciframe")[0].src="misc/email_ve_2.php?imo="+imo;
	jQuery("#miscdialog").dialog("open");
}

function printItVe_2(){
	var imo = jQuery('#vessel_name_or_imo_id').val().substring(0,7);

	jQuery("#misciframe")[0].src="misc/print_ve_2.php?imo="+imo;
	jQuery("#miscdialog").dialog("open");
}
//END OF MAIL/PRINT SHIP DETAILS

//MAIL/PRINT DETAILS
jQuery( "#miscdialog" ).dialog( { autoOpen: false, width: 1100, height: 500 });
jQuery( "#miscdialog" ).dialog("close");
//END OF MAIL/PRINT DETAILS

//SHOW PORT DETAILS
function showPortDetails(portname, rowCount, num_of_days){
	var vessel_name = jQuery("#vessel_name_or_imo_id").val();
	if(!vessel_name){ vessel_name = ""; }
	
	var cargo_type = jQuery(".cargo").val();
	if(!cargo_type){ cargo_type = ""; }
	
	if(rowCount==1){ var date_from = jQuery("#date_from"+rowCount+"_id").val(); }
	else{ var date_from = jQuery("#div_date_from"+rowCount+"_id").text(); }
	if(!date_from){ date_from = ""; }
	
	var date_to = jQuery("#div_date_to"+rowCount+"_id").text();
	if(!date_to){ date_to = ""; }
	
	var dwt = jQuery("#div_dwt_id").text();
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

//NEW SCENARIO
function newScenario(){
	jQuery('#pleasewait').show();
	
	self.location = "s-bis.php?new_search=3";
}
//END OF NEW SCENARIO

//SAVE SCENARIO
function saveScenario(){
	jQuery('#pleasewait').show();

	jQuery.ajax({
		type: "POST",
		url: "ajax.php?new_search=2",
		data: jQuery("#voyageestimatorform").serialize(),

		success: function(data) {
			alert("Scenario Saved!");
		
			self.location = "s-bis.php";
		}
	});
}
//END OF SAVE SCENARIO

//DELETE SCENARIO
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
//END OF DELETE SCENARIO
//END OF OTHER FUNCTIONS
</script>

<style>
td{
	border:0px;
}

input{
	border:1px solid #CCCCCC;
	padding:2px;
}

select{
	border:1px solid #CCCCCC;
	padding:2px;
}

.req{
	border-color:#FF0000;
}

.div_all{
	float:left;
	width:1194px;
	height:auto;
	padding:3px;
}

.div_title{
	float:left;
	width:135px;
	height:auto;
}

.div_content{
	float:left;
	width:1059px;
	height:auto;
}

.dp{
	padding:3px;
}
</style>

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
		
		foreach($tabdata as $key => $val) {
			$$key = $val;
		}
	}
}

if(!trim($broker_comm1)){
	$broker_comm1 = "1.25";
}

if(!trim($add_comm1)){
	$add_comm1 = "2.50";
}

if(!trim($broker_comm2)){
	$broker_comm2 = "1.25";
}

if(!trim($add_comm2)){
	$add_comm2 = "2.50";
}

if($vessel_by==1){
	$display1 = 'block';
	$display2 = 'none';
	$display3 = 'none';
	$display4 = 'none';
}else if($vessel_by==2){
	$display1 = 'none';
	$display2 = 'block';
	
	$ship_name_imo = explode(' - ', $vessel_name_or_imo);
	$ship_imo = $ship_name_imo[0];
	
	$sql = "select * from _xvas_parsed2_dry where imo <> '' and imo='".trim($ship_imo)."' limit 1";
	$r = dbQuery($sql);
	
	$sql2 = "select * from _xvas_shipdata_dry where imo <> '' and imo='".trim($ship_imo)."' limit 1";
	$r2 = dbQuery($sql2);
	
	$sql3 = "select * from _xvas_siitech_cache where xvas_imo <> '' and xvas_imo='".trim($ship_imo)."' limit 1";
	$r3 = dbQuery($sql3);
	
	$sql4 = "SELECT * FROM _xvas_shipdata_dry_user WHERE imo='".trim($ship_imo)."' LIMIT 0,1";
	$r4 = dbQuery($sql4);

	$ship_mmsi = $r[0]['mmsi'];
	$ship_dwt = $r[0]['summer_dwt'];
	$ship_gross_tonnage = getValue($r2[0]['data'], 'GROSS_TONNAGE');
	$ship_net_tonnage = getValue($r2[0]['data'], 'NET_TONNAGE');
	$ship_built_year = getValue($r2[0]['data'], 'BUILD');
	
	$ship_flag = getValue($r2[0]['data'], 'LAST_KNOWN_FLAG');
	if($ship_flag==""){
		$ship_flag = getValue($r2[0]['data'], 'FLAG');
		$ship_flag_image = getFlagImage($ship_flag);
		
		$flag = '<img src="'.$ship_flag_image.'" alt="'.$ship_flag.'" title="'.$ship_flag.'" />';
	}else{
		$ship_flag = $ship_flag;
		$ship_flag_image = getFlagImage($ship_flag);
		
		$flag = '<img src="'.$ship_flag_image.'" alt="'.$ship_flag.'" title="'.$ship_flag.'" />';
	}
	
	$ship_loa = getValue($r2[0]['data'], 'LENGTH_OVERALL');
	$ship_draught = getValue($r2[0]['data'], 'DRAUGHT');
	$ship_speed = $r[0]['speed'];
	$ship_breadth = getValue($r2[0]['data'], 'BREADTH_EXTREME');
	$ship_cranes = getValue($r2[0]['data'], 'CRANES');
	$ship_grain = getValue($r2[0]['data'], 'GRAIN');
	$ship_cargo_handling = getValue($r2[0]['data'], 'CARGO_HANDLING');
	$ship_decks_number = getValue($r2[0]['data'], 'DECKS_NUMBER');
	$ship_bulkheads = getValue($r2[0]['data'], 'BULKHEADS');
	$ship_class_notation = getValue($r2[0]['data'], 'CLASS_NOTATION');
	$ship_lifting_equipment = getValue($r2[0]['data'], 'LIFTING_EQUIPMENT');
	$ship_bale = getValue($r2[0]['data'], 'BALE');
	$ship_fuel_oil = getValue($r2[0]['data'], 'FUEL_OIL');
	$ship_fuel = getValue($r2[0]['data'], 'FUEL');
	$ship_fuel_consumption = getValue($r2[0]['data'], 'FUEL_CONSUMPTION');
	$ship_fuel_type = getValue($r2[0]['data'], 'FUEL_TYPE');
	
	$ship_manager_owner = getValue($r2[0]['data'], 'MANAGER');
	if(!trim($ship_manager_owner)){ $ship_manager_owner = getValue($r2[0]['data'], 'MANAGER_OWNER'); }
	if(!trim($ship_manager_owner)){ $ship_manager_owner = getValue($r2[0]['data'], 'OWNER'); }
	
	$ship_manager_owner_email = getValue($r2[0]['data'], 'MANAGER_OWNER_EMAIL');
	$ship_class_society = htmlentities(getValue($r2[0]['data'], 'CLASS_SOCIETY'));
	$ship_holds = htmlentities(getValue($r2[0]['data'], 'HOLDS'));
	$ship_largest_hatch = htmlentities(getValue($r2[0]['data'], 'LARGEST_HATCH'));
	
	//AIS DATA
	if($r3[0]){
		$ship_speed_ais = getValue($r3[0]['siitech_shipstat_data'], 'speed_ais');
		$ship_NavigationalStatus = getValue($r3[0]['siitech_shippos_data'], 'NavigationalStatus');
		$ship_aisdateupdated = $r3[0]['dateupdated'];
	}
	//END OF AIS DATA
	
	$display3 = 'block';
	
	//BUNKER FUEL
	$data2 = unserialize($r4[0]['data']);
	
	$SPEED1_1 = $data2['BUNKER_FUEL']['SPEED1_1'];
	$SPEED2_1 = $data2['BUNKER_FUEL']['SPEED2_1'];
	$SPEED1_2 = $data2['BUNKER_FUEL']['SPEED1_2'];
	$SPEED2_2 = $data2['BUNKER_FUEL']['SPEED2_2'];
	$SPEED1_3 = $data2['BUNKER_FUEL']['SPEED1_3'];
	$SPEED2_3 = $data2['BUNKER_FUEL']['SPEED2_3'];
	$SPEED1_4 = $data2['BUNKER_FUEL']['SPEED1_4'];
	$SPEED2_4 = $data2['BUNKER_FUEL']['SPEED2_4'];
	$SPEED1_5 = $data2['BUNKER_FUEL']['SPEED1_5'];
	$SPEED2_5 = $data2['BUNKER_FUEL']['SPEED2_5'];
	$SPEED1_6 = $data2['BUNKER_FUEL']['SPEED1_6'];
	$SPEED2_6 = $data2['BUNKER_FUEL']['SPEED2_6'];
	$SPEED1_7 = $data2['BUNKER_FUEL']['SPEED1_7'];
	$SPEED2_7 = $data2['BUNKER_FUEL']['SPEED2_7'];
	$SPEED_TEXT1_1 = $data2['BUNKER_FUEL']['SPEED_TEXT1_1'];
	$SPEED_TEXT2_1 = $data2['BUNKER_FUEL']['SPEED_TEXT2_1'];
	$SPEED_TEXT1_2 = $data2['BUNKER_FUEL']['SPEED_TEXT1_2'];
	$SPEED_TEXT2_2 = $data2['BUNKER_FUEL']['SPEED_TEXT2_2'];
	$SPEED_TEXT1_3 = $data2['BUNKER_FUEL']['SPEED_TEXT1_3'];
	$SPEED_TEXT2_3 = $data2['BUNKER_FUEL']['SPEED_TEXT2_3'];
	$SPEED_TEXT1_4 = $data2['BUNKER_FUEL']['SPEED_TEXT1_4'];
	$SPEED_TEXT2_4 = $data2['BUNKER_FUEL']['SPEED_TEXT2_4'];
	$SPEED_TEXT1_5 = $data2['BUNKER_FUEL']['SPEED_TEXT1_5'];
	$SPEED_TEXT2_5 = $data2['BUNKER_FUEL']['SPEED_TEXT2_5'];
	$SPEED_TEXT1_6 = $data2['BUNKER_FUEL']['SPEED_TEXT1_6'];
	$SPEED_TEXT2_6 = $data2['BUNKER_FUEL']['SPEED_TEXT2_6'];
	$SPEED_TEXT1_7 = $data2['BUNKER_FUEL']['SPEED_TEXT1_7'];
	$SPEED_TEXT2_7 = $data2['BUNKER_FUEL']['SPEED_TEXT2_7'];
	$CONSUMPTION1_1 = $data2['BUNKER_FUEL']['CONSUMPTION1_1'];
	$CONSUMPTION2_1 = $data2['BUNKER_FUEL']['CONSUMPTION2_1'];
	$CONSUMPTION1_2 = $data2['BUNKER_FUEL']['CONSUMPTION1_2'];
	$CONSUMPTION2_2 = $data2['BUNKER_FUEL']['CONSUMPTION2_2'];
	$CONSUMPTION1_3 = $data2['BUNKER_FUEL']['CONSUMPTION1_3'];
	$CONSUMPTION2_3 = $data2['BUNKER_FUEL']['CONSUMPTION2_3'];
	$CONSUMPTION1_4 = $data2['BUNKER_FUEL']['CONSUMPTION1_4'];
	$CONSUMPTION2_4 = $data2['BUNKER_FUEL']['CONSUMPTION2_4'];
	$CONSUMPTION1_5 = $data2['BUNKER_FUEL']['CONSUMPTION1_5'];
	$CONSUMPTION2_5 = $data2['BUNKER_FUEL']['CONSUMPTION2_5'];
	$CONSUMPTION1_6 = $data2['BUNKER_FUEL']['CONSUMPTION1_6'];
	$CONSUMPTION2_6 = $data2['BUNKER_FUEL']['CONSUMPTION2_6'];
	$CONSUMPTION1_7 = $data2['BUNKER_FUEL']['CONSUMPTION1_7'];
	$CONSUMPTION2_7 = $data2['BUNKER_FUEL']['CONSUMPTION2_7'];
	$CONSUMPTION_TEXT1_1 = $data2['BUNKER_FUEL']['CONSUMPTION_TEXT1_1'];
	$CONSUMPTION_TEXT2_1 = $data2['BUNKER_FUEL']['CONSUMPTION_TEXT2_1'];
	$CONSUMPTION_TEXT1_2 = $data2['BUNKER_FUEL']['CONSUMPTION_TEXT1_2'];
	$CONSUMPTION_TEXT2_2 = $data2['BUNKER_FUEL']['CONSUMPTION_TEXT2_2'];
	$CONSUMPTION_TEXT1_3 = $data2['BUNKER_FUEL']['CONSUMPTION_TEXT1_3'];
	$CONSUMPTION_TEXT2_3 = $data2['BUNKER_FUEL']['CONSUMPTION_TEXT2_3'];
	$CONSUMPTION_TEXT1_4 = $data2['BUNKER_FUEL']['CONSUMPTION_TEXT1_4'];
	$CONSUMPTION_TEXT2_4 = $data2['BUNKER_FUEL']['CONSUMPTION_TEXT2_4'];
	$CONSUMPTION_TEXT1_5 = $data2['BUNKER_FUEL']['CONSUMPTION_TEXT1_5'];
	$CONSUMPTION_TEXT2_5 = $data2['BUNKER_FUEL']['CONSUMPTION_TEXT2_5'];
	$CONSUMPTION_TEXT1_6 = $data2['BUNKER_FUEL']['CONSUMPTION_TEXT1_6'];
	$CONSUMPTION_TEXT2_6 = $data2['BUNKER_FUEL']['CONSUMPTION_TEXT2_6'];
	$CONSUMPTION_TEXT1_7 = $data2['BUNKER_FUEL']['CONSUMPTION_TEXT1_7'];
	$CONSUMPTION_TEXT2_7 = $data2['BUNKER_FUEL']['CONSUMPTION_TEXT2_7'];
	//END OF BUNKER FUEL
	
	if($SPEED1_1 || $SPEED2_1 || $SPEED1_2 || $SPEED2_2 || $SPEED1_3 || $SPEED2_3 || $SPEED1_4 || $SPEED2_4 || $SPEED1_5 || $SPEED2_5 || $SPEED1_6 || $SPEED2_6 || $SPEED1_7 || $SPEED2_7 || $SPEED_TEXT1_1 || $SPEED_TEXT2_1 || $SPEED_TEXT1_2 || $SPEED_TEXT2_2 || $SPEED_TEXT1_3 || $SPEED_TEXT2_3 || $SPEED_TEXT1_4 || $SPEED_TEXT2_4 || $SPEED_TEXT1_5 || $SPEED_TEXT2_5 || $SPEED_TEXT1_6 || $SPEED_TEXT2_6 || $SPEED_TEXT1_7 || $SPEED_TEXT2_7 || $CONSUMPTION1_1 || $CONSUMPTION2_1 || $CONSUMPTION1_2 || $CONSUMPTION2_2 || $CONSUMPTION1_3 || $CONSUMPTION2_3 || $CONSUMPTION1_4 || $CONSUMPTION2_4 || $CONSUMPTION1_5 || $CONSUMPTION2_5 || $CONSUMPTION1_6 || $CONSUMPTION2_6 || $CONSUMPTION1_7 || $CONSUMPTION2_7 || $CONSUMPTION_TEXT1_1 || $CONSUMPTION_TEXT2_1 || $CONSUMPTION_TEXT1_2 || $CONSUMPTION_TEXT2_2 || $CONSUMPTION_TEXT1_3 || $CONSUMPTION_TEXT2_3 || $CONSUMPTION_TEXT1_4 || $CONSUMPTION_TEXT2_4 || $CONSUMPTION_TEXT1_5 || $CONSUMPTION_TEXT2_5 || $CONSUMPTION_TEXT1_6 || $CONSUMPTION_TEXT2_6 || $CONSUMPTION_TEXT1_7 || $CONSUMPTION_TEXT2_7){
		$display4 = 'block';
	}else{
		$display4 = 'none';
	}
}else{
	$display1 = 'none';
	$display2 = 'none';
	$display3 = 'none';
	$display4 = 'none';
}
?>

<form method="post" id="voyageestimatorform" name="voyageestimatorform" enctype="multipart/form-data">
<table width="1200" border="0" cellspacing="0" cellpadding="0">
  <tr style="position:fixed;">
	<td bgcolor="#CCCCCC">
		<table width="1200" border="0" cellspacing="0" cellpadding="0">
		  <tr bgcolor="cddee5">
			<td><div class="dp"><input type="button" id="btn_new_id" name="btn_new" value="NEW SCENARIO" class="btn_1" onClick="newScenario();" style="cursor:pointer;" /> &nbsp;&nbsp; <input type="button" id="btn_save_id" name="btn_save" value="SAVE SCENARIO" class="btn_1" onClick="saveScenario();" style="cursor:pointer;" /></div></td>
		  </tr>
		  
			<?php
			$sql = "SELECT * FROM `_user_tabs` WHERE `uid`='".$user['uid']."' AND `page`='voyageestimator' ORDER BY `dateadded` DESC";
			$r = dbQuery($sql, $link);
			
			$t = count($r);
			
			if(trim($t)){
				echo '<tr>';
				echo '<td>';
				echo '<div class="dp">';
				
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
				}
				
				echo '</div>';
				echo '</td>';
				echo '</tr>';
			}
			?>
		  
		</table>
		
		<div style="border-bottom:3px dotted #fff;">&nbsp;</div>
		<div>&nbsp;</div>
		
		<!-- TOTALS -->
		<table width="1200" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #333333;">
		  <tr bgcolor="cddee5">
			<td colspan="7"><div class="dp"><b>FREIGHT RATE CALCULATION</b></div></td>
		  </tr>
		  <tr>
			<td width="171"><div class="dp"><span style="font-size:14px; color:#0066FF; font-weight:bold;">Freight Rate ($/MT)</span></div></td>
			<td width="171"><div class="dp"><b>Gross Freight ($)</b></div></td>
			<td width="171"><div class="dp"><b>Brok. Comm ($)</b></div></td>
			<td width="171"><div class="dp"><b>Add. Comm ($)</b></div></td>
			<td width="172" style="border-left:1px solid #000000; border-top:1px solid #000000; border-right:1px solid #000000;"><div class="dp"><b>Income ($)</b></div></td>
			<td width="172" style="border-left:1px solid #002060; border-top:1px solid #002060; border-right:1px solid #002060;"><div class="dp"><b>TCE ($/day)</b></div></td>
			<td width="172"><div class="dp"><b>Broker Commission</b></div></td>
		  </tr>
		  <tr bgcolor="f5f5f5">
			<td><div class="dp"><input type='text' class='number' id='freight_rate1_id' name="freight_rate1" value="<?php echo $freight_rate1; ?>" style="width:100px; border:1px solid #FF0000;" /></div></td>
			<td><div class="dp" id='div_gross_freight1_id'>&nbsp;</div></td>
			<td><div class="dp"><input type='text' class='number' id='broker_comm1_id' name="broker_comm1" value="<?php echo $broker_comm1; ?>" style="width:100px;" /></div></td>
			<td><div class="dp"><input type='text' class='number' id='add_comm1_id' name='add_comm1' value="<?php echo $add_comm1; ?>" style="width:100px;" /></div></td>
			<td style="border-left:1px solid #000000; border-bottom:1px solid #000000; border-right:1px solid #000000;"><div class="dp" id="div_income1_id">&nbsp;</div></td>
			<td style="border-left:1px solid #002060; border-bottom:1px solid #002060; border-right:1px solid #002060;"><div class="dp" id="div_tce1_id">&nbsp;</div></td>
			<td><div class="dp" id="div_broker_comm1_id">&nbsp;</div></td>
		  </tr>
		  <tr bgcolor="d6d6d6">
			<td colspan="7"><div class="dp">&nbsp;</div></td>
		  </tr>
		  <tr bgcolor="cddee5">
			<td colspan="7"><div class="dp"><b>TCE CALCULATION</b></div></td>
		  </tr>
		  <tr>
			<td width="171"><div class="dp"><b>Freight Rate ($/MT)</b></div></td>
			<td width="171"><div class="dp"><b>Gross Freight ($)</b></div></td>
			<td width="171"><div class="dp"><b>Brok. Comm ($)</b></div></td>
			<td width="171"><div class="dp"><b>Add. Comm ($)</b></div></td>
			<td width="172"><div class="dp"><b>Income ($)</b></div></td>
			<td width="172"><div class="dp"><span style="font-size:14px; color:#0066FF; font-weight:bold;">TCE ($/day)</span></div></td>
			<td width="172"><div class="dp"><b>Broker Commission</b></div></td>
		  </tr>
		  <tr bgcolor="f5f5f5">
			<td><div class="dp" id="div_freight_rate2_id">&nbsp;</div></td>
			<td><div class="dp" id="div_gross_freight2_id">&nbsp;</div></td>
			<td><div class="dp"><input type='text' class='number' id='broker_comm2_id' name="broker_comm2" value="<?php echo $broker_comm2; ?>" style="width:100px;" /></div></td>
			<td><div class="dp"><input type='text' class='number' id='add_comm2_id' name='add_comm2' value="<?php echo $add_comm2; ?>" style="width:100px;" /></div></td>
			<td><div class="dp" id="div_income2_id">&nbsp;</div></td>
			<td><div class="dp"><input type='text' class='number' id='tce2_id' name='tce2' value="<?php echo $tce2; ?>" style="width:100px;" /></div></td>
			<td><div class="dp" id="div_broker_comm2_id">&nbsp;</div></td>
		  </tr>
		</table>
		<!-- END OF TOTALS -->
	</td>
  </tr>
  <tr>
  	<td>
		<div style="height:280px; border-bottom:3px dotted #fff;">&nbsp;</div>
		<div>&nbsp;</div>
		<!-- CHOOSE VESSEL BY DWT TYPE OR VESSEL NAME / IMO# -->
		<table width="1200" border="0" cellspacing="0" cellpadding="0">
			<tr bgcolor="cddee5">
				<td>
					<div class="div_all">
						<div class="div_title"><b>Vessel by:</b></div>
						<div class="div_content">
							<input type="hidden" id="tabid" name="tabid" value="<?php echo $tabid; ?>" />
							<select id="vessel_by_id" name="vessel_by" style="width:300px;" onchange="getVesselBy(this.value);" class="req">
								<option value="0">- Select Vessel By -</option>
								
								<?php if($vessel_by==1){ ?>
									<option value="1" selected="selected">DWT Type</option>
									<option value="2">Vessel Name / IMO # / DWT</option>
								<?php }else if($vessel_by==2){ ?>
									<option value="1">DWT Type</option>
									<option value="2" selected="selected">Vessel Name / IMO # / DWT</option>
								<?php }else{ ?>
									<option value="1">DWT Type</option>
									<option value="2">Vessel Name / IMO # / DWT</option>
								<?php } ?>
							</select>
						</div>
					</div>
				</td>
			</tr>
			<tr bgcolor="d6d6d6">
				<td>
					<div class="div_all" style="display:<?php echo $display1; ?>;" id="vessel_by_1">
						<div class="div_title"><b>DWT Type:</b></div>
						<div class="div_content">
							<?php
							$dwt_typearr = array(
										1=>array(1=>"7208728 - Mini Bulker", 2=>"(0-9,999) Mini Bulker"), 
										2=>array(1=>"9177791 - Handysize", 2=>"(10,000-39,999) Handysize"), 
										3=>array(1=>"9547805 - Handymax / Supramax", 2=>"(40,000-59,999) Handymax / Supramax"), 
										4=>array(1=>"9111577 - Panamax", 2=>"(60,000-99,999) Panamax"), 
										5=>array(1=>"9587386 - Capesize", 2=>"(100,000-219,999) Capesize"), 
										6=>array(1=>"9565065 - Very Large Ore Carrier", 2=>"(220,000+) Very Large Ore Carrier")
									);
									
							$dwt_typet = count($dwt_typearr);
							?>
							<select id="dwt_type_id" name="dwt_type" style="width:300px;" class="req" onchange="getDwtType(this.value);">
								<option value="0">- Select DWT Type -</option>
								
								<?php
								for($dwt_typei=1; $dwt_typei<=$dwt_typet; $dwt_typei++){
									if($dwt_typearr[$dwt_typei][1]==$dwt_type){
										echo '<option value="'.$dwt_typearr[$dwt_typei][1].'" selected="selected">'.$dwt_typearr[$dwt_typei][2].'</option>';
									}else{
										echo '<option value="'.$dwt_typearr[$dwt_typei][1].'">'.$dwt_typearr[$dwt_typei][2].'</option>';
									}
								}
								?>
							</select>
						</div>
					</div>
					<div class="div_all" style="display:<?php echo $display2; ?>;" id="vessel_by_2">
						<div class="div_title"><b>Vessel Name / IMO # / DWT:</b></div>
						<div class="div_content"><input type="text" id="vessel_name_or_imo_id" name="vessel_name_or_imo" value="<?php echo $vessel_name_or_imo; ?>" style="width:295px;" class="req" /> &nbsp; <span id='shipdetailshref' style="color:#F00;"></span></div>
					</div>
				</td>
			</tr>
		</table>
		<div id="ship_info" style="display:<?php echo $display3; ?>;">
			<table width="1200" border="0" cellspacing="0" cellpadding="0">
			  <tr bgcolor="f5f5f5">
				<td width="140" valign="top"><div style="padding:3px;"><b>IMO</b> #</div></td>
				<td width="160" valign="top"><div style="padding:3px;" id="ship_imo">&nbsp;<?php echo $ship_imo; ?></div></td>
				<td width="140" valign="top"><div style="padding:3px;"><b>LOA</b></div></td>
				<td width="160" valign="top"><div style="padding:3px;" id="ship_loa">&nbsp;<?php echo $ship_loa; ?></div></td>
				<td width="140" valign="top"><div style="padding:3px;"><b>Grain</b></div></td>
				<td width="160" valign="top"><div style="padding:3px;" id="ship_grain">&nbsp;<?php echo $ship_grain; ?></div></td>
				<td width="140" valign="top"><div style="padding:3px;"><b>Class Notation</b></div></td>
				<td width="160" valign="top"><div style="padding:3px;" id="ship_class_notation">&nbsp;<?php echo $ship_class_notation; ?></div></td>
			  </tr>
			  <tr bgcolor="e9e9e9">
				<td valign="top"><div style="padding:3px;"><b>Summer DWT</b></div></td>
				<td valign="top"><div style="padding:3px;" id="ship_summer_dwt">&nbsp;<?php echo $ship_dwt; ?></div></td>
				<td valign="top"><div style="padding:3px;"><b>Draught</b></div></td>
				<td valign="top"><div style="padding:3px;" id="ship_draught">&nbsp;<?php echo $ship_draught; ?></div></td>
				<td valign="top"><div style="padding:3px;"><b>Lifting Equipment</b></div></td>
				<td valign="top"><div style="padding:3px;" id="ship_lifting_equipment">&nbsp;<?php echo $ship_lifting_equipment; ?></div></td>
				<td valign="top"><div style="padding:3px;"><b>Fuel Oil</b></div></td>
				<td valign="top"><div style="padding:3px;" id="ship_fuel_oil">&nbsp;<?php echo $ship_fuel_oil; ?></div></td>
			  </tr>
			  <tr bgcolor="f5f5f5">
				<td valign="top"><div style="padding:3px;"><b>Gross Tonnage</b></div></td>
				<td valign="top"><div style="padding:3px;" id="ship_gross_tonnage">&nbsp;<?php echo $ship_gross_tonnage; ?></div></td>
				<td valign="top"><div style="padding:3px;"><b>Net Tonnage</b></div></td>
				<td valign="top"><div style="padding:3px;" id="ship_net_tonnage">&nbsp;<?php echo $ship_net_tonnage; ?></div></td>
				<td valign="top"><div style="padding:3px; color:#FF0000;"><b>Speed</b></div></td>
				<td valign="top"><div style="padding:3px; color:#FF0000;" id="ship_speed">&nbsp;<?php echo $ship_speed; ?></div></td>
				<td valign="top"><div style="padding:3px;"><b>Cargo Handling</b></div></td>
				<td valign="top"><div style="padding:3px;" id="ship_cargo_handling">&nbsp;<?php echo $ship_cargo_handling; ?></div></td>
			  </tr>
			  <tr bgcolor="e9e9e9">
				<td valign="top"><div style="padding:3px;"><b>Fuel</b></div></td>
				<td valign="top"><div style="padding:3px;" id="ship_fuel">&nbsp;<?php echo $ship_fuel; ?></div></td>
				<td valign="top"><div style="padding:3px;"><b>Built Year</b></div></td>
				<td valign="top"><div style="padding:3px;" id="ship_built_year">&nbsp;<?php echo $ship_built_year; ?></div></td>
				<td valign="top"><div style="padding:3px; color:#FF0000;"><b>Speed AIS</b></div></td>
				<td valign="top"><div style="padding:3px; color:#FF0000;" id="ship_speed_ais">&nbsp;<?php echo $ship_speed_ais; ?></div></td>
				<td valign="top"><div style="padding:3px;"><b>Breadth</b></div></td>
				<td valign="top"><div style="padding:3px;" id="ship_breadth">&nbsp;<?php echo $ship_breadth; ?></div></td>
			  </tr>
			  <tr bgcolor="f5f5f5">
				<td valign="top"><div style="padding:3px;"><b>Decks Number</b></div></td>
				<td valign="top"><div style="padding:3px;" id="ship_decks_number">&nbsp;<?php echo $ship_decks_number; ?></div></td>
				<td valign="top"><div style="padding:3px;"><b>Fuel Consumption</b></div></td>
				<td valign="top"><div style="padding:3px;" id="ship_fuel_consumption">&nbsp;<?php echo $ship_fuel_consumption; ?></div></td>
				<td valign="top"><div style="padding:3px; color:#FF0000;"><b>Movement Status</b></div></td>
				<td valign="top"><div style="padding:3px; color:#FF0000;" id="ship_NavigationalStatus">&nbsp;<?php echo $ship_NavigationalStatus; ?></div></td>
				<td valign="top"><div style="padding:3px;"><b>Bale</b></div></td>
				<td valign="top"><div style="padding:3px;" id="ship_bale">&nbsp;<?php echo $ship_bale; ?></div></td>
			  </tr>
			  <tr bgcolor="e9e9e9">
				<td valign="top"><div style="padding:3px;"><b>Cranes</b></div></td>
				<td valign="top"><div style="padding:3px;" id="ship_cranes">&nbsp;<?php echo $ship_cranes; ?></div></td>
				<td valign="top"><div style="padding:3px;"><b>Bulkheads</b></div></td>
				<td valign="top"><div style="padding:3px;" id="ship_bulkheads">&nbsp;<?php echo $ship_bulkheads; ?></div></td>
				<td valign="top"><div style="padding:3px; color:#FF0000;"><b>AIS Date Updated</b></div></td>
				<td valign="top"><div style="padding:3px; color:#FF0000;" id="ship_aisdateupdated">&nbsp;<?php echo $ship_aisdateupdated; ?></div></td>
				<td valign="top"><div style="padding:3px;"><b>Fuel Type</b></div></td>
				<td valign="top"><div style="padding:3px;" id="ship_fuel_type">&nbsp;<?php echo $ship_fuel_type; ?></div></td>
			  </tr>
			  <tr bgcolor="f5f5f5">
				<td valign="top"><div style="padding:3px;"><b>Manager Owner</b></div></td>
				<td valign="top"><div style="padding:3px;" id="ship_manager_owner">&nbsp;<?php echo $ship_manager_owner; ?></div></td>
				<td valign="top"><div style="padding:3px;"><b>Manager Owner Email</b></div></td>
				<td valign="top"><div style="padding:3px;" id="ship_manager_owner_email">&nbsp;<?php echo $ship_manager_owner_email; ?></div></td>
				<td valign="top"><div style="padding:3px;"><b>Class Society</b></div></td>
				<td valign="top"><div style="padding:3px;" id="ship_class_society">&nbsp;<?php echo $ship_class_society; ?></div></td>
				<td valign="top"><div style="padding:3px;"><b>Largest Hatch</b></div></td>
				<td valign="top"><div style="padding:3px;" id="ship_largest_hatch">&nbsp;<?php echo $ship_largest_hatch; ?></div></td>
			  </tr>
			  <tr bgcolor="e9e9e9">
				<td valign="top"><div style="padding:3px;"><b>Holds</b></div></td>
				<td valign="top"><div style="padding:3px;" id="ship_holds">&nbsp;<?php echo $ship_holds; ?></div></td>
				<td valign="top"><div style="padding:3px;"><b>Flag</b></div></td>
				<td valign="top"><div style="padding:3px;" id="ship_flag">&nbsp;<?php echo $flag; ?></div></td>
				<td valign="top"><div style="padding:3px;"><b>&nbsp;</b></div></td>
				<td valign="top"><div style="padding:3px;">&nbsp;</div></td>
				<td valign="top"><div style="padding:3px;"><b>&nbsp;</b></div></td>
				<td valign="top"><div style="padding:3px;">&nbsp;</div></td>
			  </tr>
			</table>
		</div>
		<div>&nbsp;</div>
		<div id="bunker_fuel_info" style="display:<?php echo $display4; ?>;">
			<table width="1200" border="0" cellspacing="0" cellpadding="0">
			  <tr bgcolor="d6d6d6">
				<td width="104"><div style="padding:3px;"><b>Bunker Fuel Type</b></div></td>
				<td width="137"><div style="padding:3px;"><b>Speed 1</b></div></td>
				<td width="137"><div style="padding:3px;"><b>Info</b></div></td>
				<td width="137"><div style="padding:3px;"><b>Consumption MT/Day</b></div></td>
				<td width="137"><div style="padding:3px;"><b>Info</b></div></td>
				<td width="137"><div style="padding:3px;"><b>Speed 2</b></div></td>
				<td width="137"><div style="padding:3px;"><b>Info</b></div></td>
				<td width="137"><div style="padding:3px;"><b>Consumption MT/Day</b></div></td>
				<td width="137"><div style="padding:3px;"><b>Info</b></div></td>
			  </tr>
			  <tr bgcolor="f5f5f5">
				<td><div style="padding:3px;">IFO 380</div></td>
				<td><div style="padding:3px;" id="SPEED1_1">&nbsp;<?php echo $SPEED1_1; ?></div></td>
				<td><div style="padding:3px;" id="SPEED_TEXT1_1">&nbsp;<?php echo $SPEED_TEXT1_1; ?></div></td>
				<td><div style="padding:3px;" id="CONSUMPTION1_1">&nbsp;<?php echo $CONSUMPTION1_1; ?></div></td>
				<td><div style="padding:3px;" id="CONSUMPTION_TEXT1_1">&nbsp;<?php echo $CONSUMPTION_TEXT1_1; ?></div></td>
				<td><div style="padding:3px;" id="SPEED2_1">&nbsp;<?php echo $SPEED2_1; ?></div></td>
				<td><div style="padding:3px;" id="SPEED_TEXT2_1">&nbsp;<?php echo $SPEED_TEXT2_1; ?></div></td>
				<td><div style="padding:3px;" id="CONSUMPTION2_1">&nbsp;<?php echo $CONSUMPTION2_1; ?></div></td>
				<td><div style="padding:3px;" id="CONSUMPTION_TEXT2_1">&nbsp;<?php echo $CONSUMPTION_TEXT2_1; ?></div></td>
			  </tr>
			  <tr bgcolor="e9e9e9">
				<td><div style="padding:3px;">IFO 180</div></td>
				<td><div style="padding:3px;" id="SPEED1_2">&nbsp;<?php echo $SPEED1_2; ?></div></td>
				<td><div style="padding:3px;" id="SPEED_TEXT1_2">&nbsp;<?php echo $SPEED_TEXT1_2; ?></div></td>
				<td><div style="padding:3px;" id="CONSUMPTION1_2">&nbsp;<?php echo $CONSUMPTION1_2; ?></div></td>
				<td><div style="padding:3px;" id="CONSUMPTION_TEXT1_2">&nbsp;<?php echo $CONSUMPTION_TEXT1_2; ?></div></td>
				<td><div style="padding:3px;" id="SPEED2_2">&nbsp;<?php echo $SPEED2_2; ?></div></td>
				<td><div style="padding:3px;" id="SPEED_TEXT2_2">&nbsp;<?php echo $SPEED_TEXT2_2; ?></div></td>
				<td><div style="padding:3px;" id="CONSUMPTION2_2">&nbsp;<?php echo $CONSUMPTION2_2; ?></div></td>
				<td><div style="padding:3px;" id="CONSUMPTION_TEXT2_2">&nbsp;<?php echo $CONSUMPTION_TEXT2_2; ?></div></td>
			  </tr>
			  <tr bgcolor="f5f5f5">
				<td><div style="padding:3px;">LS IFO 380 1%</div></td>
				<td><div style="padding:3px;" id="SPEED1_3">&nbsp;<?php echo $SPEED1_3; ?></div></td>
				<td><div style="padding:3px;" id="SPEED_TEXT1_3">&nbsp;<?php echo $SPEED_TEXT1_3; ?></div></td>
				<td><div style="padding:3px;" id="CONSUMPTION1_3">&nbsp;<?php echo $CONSUMPTION1_3; ?></div></td>
				<td><div style="padding:3px;" id="CONSUMPTION_TEXT1_3">&nbsp;<?php echo $CONSUMPTION_TEXT1_3; ?></div></td>
				<td><div style="padding:3px;" id="SPEED2_3">&nbsp;<?php echo $SPEED2_3; ?></div></td>
				<td><div style="padding:3px;" id="SPEED_TEXT2_3">&nbsp;<?php echo $SPEED_TEXT2_3; ?></div></td>
				<td><div style="padding:3px;" id="CONSUMPTION2_3">&nbsp;<?php echo $CONSUMPTION2_3; ?></div></td>
				<td><div style="padding:3px;" id="CONSUMPTION_TEXT2_3">&nbsp;<?php echo $CONSUMPTION_TEXT2_3; ?></div></td>
			  </tr>
			  <tr bgcolor="e9e9e9">
				<td><div style="padding:3px;">LS IFO 180 1%</div></td>
				<td><div style="padding:3px;" id="SPEED1_4">&nbsp;<?php echo $SPEED1_4; ?></div></td>
				<td><div style="padding:3px;" id="SPEED_TEXT1_4">&nbsp;<?php echo $SPEED_TEXT1_4; ?></div></td>
				<td><div style="padding:3px;" id="CONSUMPTION1_4">&nbsp;<?php echo $CONSUMPTION1_4; ?></div></td>
				<td><div style="padding:3px;" id="CONSUMPTION_TEXT1_4">&nbsp;<?php echo $CONSUMPTION_TEXT1_4; ?></div></td>
				<td><div style="padding:3px;" id="SPEED2_4">&nbsp;<?php echo $SPEED2_4; ?></div></td>
				<td><div style="padding:3px;" id="SPEED_TEXT2_4">&nbsp;<?php echo $SPEED_TEXT2_4; ?></div></td>
				<td><div style="padding:3px;" id="CONSUMPTION2_4">&nbsp;<?php echo $CONSUMPTION2_4; ?></div></td>
				<td><div style="padding:3px;" id="CONSUMPTION_TEXT2_4">&nbsp;<?php echo $CONSUMPTION_TEXT2_4; ?></div></td>
			  </tr>
			  <tr bgcolor="f5f5f5">
				<td><div style="padding:3px;">MDO</div></td>
				<td><div style="padding:3px;" id="SPEED1_5">&nbsp;<?php echo $SPEED1_5; ?></div></td>
				<td><div style="padding:3px;" id="SPEED_TEXT1_5">&nbsp;<?php echo $SPEED_TEXT1_5; ?></div></td>
				<td><div style="padding:3px;" id="CONSUMPTION1_5">&nbsp;<?php echo $CONSUMPTION1_5; ?></div></td>
				<td><div style="padding:3px;" id="CONSUMPTION_TEXT1_5">&nbsp;<?php echo $CONSUMPTION_TEXT1_5; ?></div></td>
				<td><div style="padding:3px;" id="SPEED2_5">&nbsp;<?php echo $SPEED2_5; ?></div></td>
				<td><div style="padding:3px;" id="SPEED_TEXT2_5">&nbsp;<?php echo $SPEED_TEXT2_5; ?></div></td>
				<td><div style="padding:3px;" id="CONSUMPTION2_5">&nbsp;<?php echo $CONSUMPTION2_5; ?></div></td>
				<td><div style="padding:3px;" id="CONSUMPTION_TEXT2_5">&nbsp;<?php echo $CONSUMPTION_TEXT2_5; ?></div></td>
			  </tr>
			  <tr bgcolor="e9e9e9">
				<td><div style="padding:3px;">MGO</div></td>
				<td><div style="padding:3px;" id="SPEED1_6">&nbsp;<?php echo $SPEED1_6; ?></div></td>
				<td><div style="padding:3px;" id="SPEED_TEXT1_6">&nbsp;<?php echo $SPEED_TEXT1_6; ?></div></td>
				<td><div style="padding:3px;" id="CONSUMPTION1_6">&nbsp;<?php echo $CONSUMPTION1_6; ?></div></td>
				<td><div style="padding:3px;" id="CONSUMPTION_TEXT1_6">&nbsp;<?php echo $CONSUMPTION_TEXT1_6; ?></div></td>
				<td><div style="padding:3px;" id="SPEED2_6">&nbsp;<?php echo $SPEED2_6; ?></div></td>
				<td><div style="padding:3px;" id="SPEED_TEXT2_6">&nbsp;<?php echo $SPEED_TEXT2_6; ?></div></td>
				<td><div style="padding:3px;" id="CONSUMPTION2_6">&nbsp;<?php echo $CONSUMPTION2_6; ?></div></td>
				<td><div style="padding:3px;" id="CONSUMPTION_TEXT2_6">&nbsp;<?php echo $CONSUMPTION_TEXT2_6; ?></div></td>
			  </tr>
			  <tr bgcolor="f5f5f5">
				<td><div style="padding:3px;">LS MGO 1%</div></td>
				<td><div style="padding:3px;" id="SPEED1_7">&nbsp;<?php echo $SPEED1_7; ?></div></td>
				<td><div style="padding:3px;" id="SPEED_TEXT1_7">&nbsp;<?php echo $SPEED_TEXT1_7; ?></div></td>
				<td><div style="padding:3px;" id="CONSUMPTION1_7">&nbsp;<?php echo $CONSUMPTION1_7; ?></div></td>
				<td><div style="padding:3px;" id="CONSUMPTION_TEXT1_7">&nbsp;<?php echo $CONSUMPTION_TEXT1_7; ?></div></td>
				<td><div style="padding:3px;" id="SPEED2_7">&nbsp;<?php echo $SPEED2_7; ?></div></td>
				<td><div style="padding:3px;" id="SPEED_TEXT2_7">&nbsp;<?php echo $SPEED_TEXT2_7; ?></div></td>
				<td><div style="padding:3px;" id="CONSUMPTION2_7">&nbsp;<?php echo $CONSUMPTION2_7; ?></div></td>
				<td><div style="padding:3px;" id="CONSUMPTION_TEXT2_7">&nbsp;<?php echo $CONSUMPTION_TEXT2_7; ?></div></td>
			  </tr>
			</table>
		</div>
		
		<div style="border-bottom:3px dotted #fff;">&nbsp;</div>
		<div>&nbsp;</div>
		<!-- CHOOSE VESSEL BY DWT TYPE OR VESSEL NAME / IMO# -->
		
		<!-- VOYAGE LEGS -->
		<table width="1200" border="0" cellspacing="0" cellpadding="0" id="voyage_legs_id">
			<tr bgcolor="cddee5">
				<td colspan="10">
					<div class="div_all">
						<table width="1194" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="100"><b>VOYAGE LEGS</b></td>
								<td width="25" align="center"><a style="cursor:pointer;" onclick="addSequence();"><img src="images/plus.png" border="0" /></a></td>
								<td><a style="cursor:pointer; color:#FF0000;" onclick="addSequence();">add new sequence</a></td>
							</tr>
						</table>
					</div>
				</td>
			</tr>
			<tr bgcolor="d6d6d6">
				<td width="130" colspan="2"><div class="dp"><b>Type</b></div></td>
				<td width="200"><div class="dp"><b>Port</b></div></td>
				<td width="200"><div class="dp"><b>Date</b></div></td>
				<td width="200"><div class="dp"><b>Port</b></div></td>
				<td width="200"><div class="dp"><b>Date</b></div></td>
				<td width="167"><div class="dp"><b>Speed (knts)</b></div></td>
				<td width="167"><div class="dp"><b>Distance (miles)</b></div></td>
				<td width="167"><div class="dp"><b>Input %</b></div></td>
				<td width="169"><div class="dp"><b>% Sea Margin</b></div></td>
			</tr>
			<tr bgcolor="f5f5f5" id="voyage_legs_row" class="voyage_legs_row1">
				<td><div class="dp">&nbsp;</div></td>
				<td>
					<div class="dp" id="div_voyage_type1_id">
						<select id="voyage_type1_id" name="voyage_type1" style="width:110px;" class="req voyage_type" onchange="addSequenceCargo();">
							<option value="">- Select Type -</option>
							
							<?php if($voyage_type1=="Ballast"){ ?>
								<option value="Ballast" selected="selected">Ballast</option>
								<option value="Loading">Loading</option>
							<?php }else if($voyage_type1=="Loading"){ ?>
								<option value="Ballast">Ballast</option>
								<option value="Loading" selected="selected">Loading</option>
							<?php }else{ ?>
								<option value="Ballast">Ballast</option>
								<option value="Loading">Loading</option>
							<?php } ?>
						</select>
					</div>
				</td>
				<td><div class="dp" id="div_port_from1_id"><input type="text" id="port_from1_id" name="port_from1" value="<?php echo $port_from1; ?>" style="width:150px;" class="req port_from" /></div></td>
				<td><div class="dp" id="div_date_from1_id"><input type="text" id="date_from1_id" name="date_from1" value="<?php echo $date_from1; ?>" style="width:150px;" class="req date" readonly="readonly" /></div></td>
				<td><div class="dp" id="div_port_to1_id"><input type="text" id="port_to1_id" name="port_to1" value="<?php echo $port_to1; ?>" style="width:150px;" class="req port_to" /></div></td>
				<td><div class="dp" id="div_date_to1_id">&nbsp;</div></td>
				<td><div class="dp" id="div_speed1_id"><input type="text" id="speed1_id" name="speed1" value="<?php echo $speed1; ?>" style="width:40px;" class="speed number" /></div></td>
				<td><div class="dp" id="div_distance_miles1_id">&nbsp;</div></td>
				<td><div class="dp" id="div_input_percent1_id"><input type="text" id="input_percent1_id" name="input_percent1" value="<?php echo $input_percent1; ?>" style="width:40px;" class="number" onkeyup="computeDistanceMiles1(this.value);" /></div></td>
				<td><div class="dp" id="div_sea_margin1_id"></div></td>
			</tr>
		</table>
		
		<div style="border-bottom:3px dotted #fff;">&nbsp;</div>
		<div>&nbsp;</div>
		<!-- END OF VOYAGE LEGS -->
		
		<!-- CARGO LEGS -->
		<table width="1200" border="0" cellspacing="0" cellpadding="0" id="cargo_legs_id">
			<tr bgcolor="cddee5">
				<td colspan="6"><div class="dp"><b>CARGO LEGS</b></div></td>
				<td colspan="2"><div class="dp"><b>* Option to Load & Bunker concurrently</b></div></td>
				<td colspan="2"><div class="dp"><b>Port Days</b></div></td>
				<td colspan="3"><div class="dp"><b>Sea Days</b></div></td>
			</tr>
			<tr bgcolor="d6d6d6">
				<td width="85"><div class="dp"><b>Type</b></div></td>
				<td width="205"><div class="dp"><b>Cargo</b></div></td>
				<td width="30"><div class="dp"><b>SF</b></div></td>
				<td width="205"><div class="dp"><b>Quantity (MT)</b></div></td>
				<td width="80"><div class="dp"><b>Volume (M3)</b></div></td>
				<td width="205"><div class="dp"><b>L/D Rate (MT/day)</b></div></td>
				<td width="30"><div class="dp"><b>Load Days</b></div></td>
				<td width="90"><div class="dp"><b>Working Days TERMS</b></div></td>
				<td width="60"><div class="dp"><b>Working Aditional Days TERMS</b></div></td>
				<td width="60"><div class="dp"><b>Turn/Idle/Extra Days</b></div></td>
				<td width="30"><div class="dp"><b>Voyage Days</b></div></td>
				<td width="60"><div class="dp"><b>Canal Days</b></div></td>
				<td width="60"><div class="dp"><b>Weather/Extra Days</b></div></td>
			</tr>
			<tr bgcolor="f5f5f5" id="cargo_legs_row" class="cargo_legs_row1">
				<td><div class="dp" id="div_cargo_legs_type1_id" style="font-weight:bold;">&nbsp;</div></td>
				<td><div class="dp" id="div_cargo1_id">&nbsp;</div></td>
				<td><div class="dp" id="div_sf1_id">&nbsp;</div></td>
				<td><div class="dp" id="div_cargo_quantity1_id">&nbsp;</div></td>
				<td><div class="dp" id="div_cargo_volume1_id">&nbsp;</div></td>
				<td><div class="dp" id="div_ld_rate1_id">&nbsp;</div></td>
				<td><div class="dp load_days" id="div_load_days1_id">&nbsp;</div></td>
				<td><div class="dp" id="div_wdt1_id">&nbsp;</div></td>
				<td><div class="dp" id="div_wadt1_id">&nbsp;</div></td>
				<td><div class="dp" id="div_tie_days1_id">&nbsp;</div></td>
				<td><div class="dp voyage_days" id="div_voyage_days1_id">&nbsp;</div></td>
				<td><div class="dp" id="div_canal1_id">&nbsp;</div></td>
				<td><div class="dp" id="div_weather_extra1_id">&nbsp;</div></td>
			</tr>
		</table>
		
		<div style="border-bottom:3px dotted #fff;">&nbsp;</div>
		<div>&nbsp;</div>
		<!-- END OF CARGO LEGS -->
		
		<!-- VOYAGE TIME -->
		<table width="1200" border="0" cellspacing="0" cellpadding="0">
		  <tr bgcolor="cddee5">
			<td colspan="3"><div class="dp"><b>VOYAGE TIME</b></div></td>
		  </tr>
		  <tr>
			<td width="400"><div class="dp"><b>PORT DAYS</b></div></td>
			<td width="400"><div class="dp"><b>SEA DAYS</b></div></td>
			<td width="400"><div class="dp"><b>TOTAL VOYAGE DAYS</b></div></td>
		  </tr>
		  <tr bgcolor="f5f5f5">
			<td><div class="dp" id='voyage_port_days'>&nbsp;</div></td>
			<td><div class="dp" id='voyage_sea_days'>&nbsp;</div></td>
			<td><div class="dp" id='voyage_total_days'>&nbsp;</div></td>
		  </tr>
		</table>
		
		<div style="border-bottom:3px dotted #fff;">&nbsp;</div>
		<div>&nbsp;</div>
		<!-- END OF VOYAGE TIME -->
		
		<!-- BUNKER PRICING -->
		<table width="1200" border="0" cellspacing="0" cellpadding="0">
		  <tr bgcolor="cddee5">
			<td><div class="dp"><b>BUNKER PRICING - Data from Bunkerworld</b> <span id="bunker_price_dateupdated" style="color:#FF0000;">&nbsp;</span></div></td>
		  </tr>
		</table>
		
		<table width="1200" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td valign="top">
				<table width="595" border="0" cellspacing="0" cellpadding="0">
				  <tr bgcolor="d6d6d6">
					<td width="198"><div class="dp"><b>Type IFO</b></div></td>
					<td width="199"><div class="dp"><b>Price Input ($)</b></div></td>
					<td width="198"><div class="dp"><b>Price Available ($)</b></div></td>
				  </tr>
				  <tr bgcolor="f5f5f5">
					<td><div class="dp"><b>IFO 380</b></div></td>
					<td><div class="dp"><input type="text" id="ifo1_id" name="ifo1" value="<?php echo $ifo1; ?>" class="number" style="width:150px;" onkeyup="calculateBunkerConsumption();" /></div></td>
					<td><div class="dp" id="div_ifo1_id" style="color:#FF0000; font-weight:bold;">&nbsp;</div></td>
				  </tr>
				  <tr bgcolor="e9e9e9">
					<td><div class="dp"><b>IFO 180</b></div></td>
					<td><div class="dp"><input type="text" id="ifo2_id" name="ifo2" value="<?php echo $ifo2; ?>" class="number" style="width:150px;" onkeyup="calculateBunkerConsumption();" /></div></td>
					<td><div class="dp" id="div_ifo2_id" style="color:#FF0000; font-weight:bold;">&nbsp;</div></td>
				  </tr>
				  <tr bgcolor="f5f5f5">
					<td><div class="dp"><b>LS IFO 380 1%</b></div></td>
					<td><div class="dp"><input type="text" id="ifo3_id" name="ifo3" value="<?php echo $ifo3; ?>" class="number" style="width:150px;" onkeyup="calculateBunkerConsumption();" /></div></td>
					<td><div class="dp" id="div_ifo3_id" style="color:#FF0000; font-weight:bold;">&nbsp;</div></td>
				  </tr>
				  <tr bgcolor="e9e9e9">
					<td><div class="dp"><b>LS IFO 180 1%</b></div></td>
					<td><div class="dp"><input type="text" id="ifo4_id" name="ifo4" value="<?php echo $ifo4; ?>" class="number" style="width:150px;" onkeyup="calculateBunkerConsumption();" /></div></td>
					<td><div class="dp" id="div_ifo4_id" style="color:#FF0000; font-weight:bold;">&nbsp;</div></td>
				  </tr>
				</table>
			</td>
			<td width="10"></td>
			<td valign="top">
				<table width="595" border="0" cellspacing="0" cellpadding="0">
				  <tr bgcolor="d6d6d6">
					<td width="198"><div class="dp"><b>Type MDO</b></div></td>
					<td width="199"><div class="dp"><b>Price Input ($)</b></div></td>
					<td width="198"><div class="dp"><b>Price Available ($)</b></div></td>
				  </tr>
				  <tr bgcolor="f5f5f5">
					<td><div class="dp"><b>MDO</b></div></td>
					<td><div class="dp"><input type="text" id="mdo1_id" name="mdo1" value="<?php echo $mdo1; ?>" class="number" style="width:150px;" onkeyup="calculateBunkerConsumption();" /></div></td>
					<td><div class="dp" id="div_mdo1_id" style="color:#FF0000; font-weight:bold;">&nbsp;</div></td>
				  </tr>
				  <tr bgcolor="e9e9e9">
					<td><div class="dp"><b>MGO</b></div></td>
					<td><div class="dp"><input type="text" id="mdo2_id" name="mdo2" value="<?php echo $mdo2; ?>" class="number" style="width:150px;" onkeyup="calculateBunkerConsumption();" /></div></td>
					<td><div class="dp" id="div_mdo2_id" style="color:#FF0000; font-weight:bold;">&nbsp;</div></td>
				  </tr>
				  <tr bgcolor="f5f5f5">
					<td><div class="dp"><b>LS MGO 1%</b></div></td>
					<td><div class="dp"><input type="text" id="mdo3_id" name="mdo3" value="<?php echo $mdo3; ?>" class="number" style="width:150px;" onkeyup="calculateBunkerConsumption();" /></div></td>
					<td><div class="dp" id="div_mdo3_id" style="color:#FF0000; font-weight:bold;">&nbsp;</div></td>
				  </tr>
				  <tr bgcolor="e9e9e9">
					<td><div class="dp"><b>&nbsp;</b></div></td>
					<td><div class="dp">&nbsp;</div></td>
					<td><div class="dp" style="color:#FF0000; font-weight:bold;">&nbsp;</div></td>
				  </tr>
				</table>
			</td>
		  </tr>
		</table>
		
		<div style="border-bottom:3px dotted #fff;">&nbsp;</div>
		<div>&nbsp;</div>
		<!-- END OF BUNKER PRICING -->
		
		<!-- BUNKER CONSUMPTIONS -->
		<table width="1200" border="0" cellspacing="0" cellpadding="0">
		  <tr bgcolor="cddee5">
			<td><div class="dp"><b>BUNKER CONSUMPTIONS</b></div></td>
		  </tr>
		</table>
		
		<table width="1200" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td valign="top">
				<table width="595" border="0" cellspacing="0" cellpadding="0">
				  <tr bgcolor="d6d6d6">
					<td width="151"><div class="dp"><b>Voyage Type</b></div></td>
					<td width="148"><div class="dp"><b>Consumption (MT/day)</b></div></td>
					<td width="148"><div class="dp"><b>Total Consumption (MT)</b></div></td>
					<td width="148"><div class="dp"><b>Voyage Expense ($)</b></div></td>
				  </tr>
				  <tr bgcolor="f5f5f5">
					<td><div class="dp" style="color:#ff0000;"><b>IFO/Ballast</b></div></td>
					<td><div class="dp"><input type="text" id="ifo_ballast_id" name="ifo_ballast" value="<?php echo $ifo_ballast; ?>" class="number" style="width:120px;" onkeyup="calculateBunkerConsumption();" /></div></td>
					<td><div class="dp" id="div_ifo_ballast_consumption">&nbsp;</div></td>
					<td><div class="dp" id="div_ifo_ballast_expense">&nbsp;</div></td>
				  </tr>
				  <tr bgcolor="e9e9e9">
					<td><div class="dp" style="color:#00b050;"><b>IFO/Loading</b></div></td>
					<td><div class="dp"><input type="text" id="ifo_loading_id" name="ifo_loading" value="<?php echo $ifo_loading; ?>" class="number" style="width:120px;" onkeyup="calculateBunkerConsumption();" /></div></td>
					<td><div class="dp" id="div_ifo_loading_consumption">&nbsp;</div></td>
					<td><div class="dp" id="div_ifo_loading_expense">&nbsp;</div></td>
				  </tr>
				  <tr bgcolor="f5f5f5">
					<td><div class="dp" style="color:#ff0000;"><b>IFO/Bunker Stop</b></div></td>
					<td><div class="dp"><input type="text" id="ifo_bunker_stop_id" name="ifo_bunker_stop" value="<?php echo $ifo_bunker_stop; ?>" class="number" style="width:120px;" onkeyup="calculateBunkerConsumption();" /></div></td>
					<td><div class="dp" id="div_ifo_bunker_stop_consumption">&nbsp;</div></td>
					<td><div class="dp" id="div_ifo_bunker_stop_expense">&nbsp;</div></td>
				  </tr>
				  <tr bgcolor="e9e9e9">
					<td><div class="dp" style="color:#00b050;"><b>IFO/Laden</b></div></td>
					<td><div class="dp"><input type="text" id="ifo_laden_id" name="ifo_laden" value="<?php echo $ifo_laden; ?>" class="number" style="width:120px;" onkeyup="calculateBunkerConsumption();" /></div></td>
					<td><div class="dp" id="div_ifo_laden_consumption">&nbsp;</div></td>
					<td><div class="dp" id="div_ifo_laden_expense">&nbsp;</div></td>
				  </tr>
				  <tr bgcolor="f5f5f5">
					<td><div class="dp" style="color:#ff0000;"><b>IFO/Discharging</b></div></td>
					<td><div class="dp"><input type="text" id="ifo_discharging_id" name="ifo_discharging" value="<?php echo $ifo_discharging; ?>" class="number" style="width:120px;" onkeyup="calculateBunkerConsumption();" /></div></td>
					<td><div class="dp" id="div_ifo_discharging_consumption">&nbsp;</div></td>
					<td><div class="dp" id="div_ifo_discharging_expense">&nbsp;</div></td>
				  </tr>
				  <tr bgcolor="e9e9e9">
					<td><div class="dp" style="color:#00b050;"><b>IFO/Repositioning</b></div></td>
					<td><div class="dp"><input type="text" id="ifo_repositioning_id" name="ifo_repositioning" value="<?php echo $ifo_repositioning; ?>" class="number" style="width:120px;" onkeyup="calculateBunkerConsumption();" /></div></td>
					<td><div class="dp" id="div_ifo_repositioning_consumption">&nbsp;</div></td>
					<td><div class="dp" id="div_ifo_repositioning_expense">&nbsp;</div></td>
				  </tr>
				  <tr bgcolor="f5f5f5">
					<td><div class="dp" style="color:#ff0000;"><b>IFO/Port</b></div></td>
					<td><div class="dp"><input type="text" id="ifo_port_id" name="ifo_port" value="<?php echo $ifo_port; ?>" class="number" style="width:120px;" onkeyup="calculateBunkerConsumption();" /></div></td>
					<td><div class="dp" id="div_ifo_port_consumption">&nbsp;</div></td>
					<td><div class="dp" id="div_ifo_port_expense">&nbsp;</div></td>
				  </tr>
				  <tr bgcolor="e9e9e9">
					<td><div class="dp"><b>IFO/Reserve</b></div></td>
					<td><div class="dp">&nbsp;</div></td>
					<td><div class="dp" id="div_ifo_ifo_reserve_id"><input type="text" id="ifo_reserve_id" name="ifo_reserve" value="<?php echo $ifo_reserve; ?>" class="number" style="width:120px;" onkeyup="calculateBunkerConsumption();" /></div></td>
					<td><div class="dp" id="div_ifo_reserve_expense">&nbsp;</div></td>
				  </tr>
				  <tr bgcolor="e9e9e9">
					<td><div class="dp"><b>IFO Total Expense ($)</b></div></td>
					<td colspan="3"><div class="dp" id="div_ifo_total_expense">&nbsp;</div></td>
				  </tr>
				</table>
			</td>
			<td width="10"></td>
			<td valign="top">
				<table width="595" border="0" cellspacing="0" cellpadding="0">
				  <tr bgcolor="d6d6d6">
					<td width="151"><div class="dp"><b>Voyage Type</b></div></td>
					<td width="148"><div class="dp"><b>Consumption (MT/day)</b></div></td>
					<td width="148"><div class="dp"><b>Total Consumption (MT)</b></div></td>
					<td width="148"><div class="dp"><b>Voyage Expense ($)</b></div></td>
				  </tr>
				  <tr bgcolor="f5f5f5">
					<td><div class="dp" style="color:#ff0000;"><b>MDO/Ballast</b></div></td>
					<td><div class="dp"><input type="text" id="mdo_ballast_id" name="mdo_ballast" value="<?php echo $mdo_ballast; ?>" class="number" style="width:120px;" onkeyup="calculateBunkerConsumption();" /></div></td>
					<td><div class="dp" id="div_mdo_ballast_consumption">&nbsp;</div></td>
					<td><div class="dp" id="div_mdo_ballast_expense">&nbsp;</div></td>
				  </tr>
				  <tr bgcolor="e9e9e9">
					<td><div class="dp" style="color:#00b050;"><b>MDO/Loading</b></div></td>
					<td><div class="dp"><input type="text" id="mdo_loading_id" name="mdo_loading" value="<?php echo $mdo_loading; ?>" class="number" style="width:120px;" onkeyup="calculateBunkerConsumption();" /></div></td>
					<td><div class="dp" id="div_mdo_loading_consumption">&nbsp;</div></td>
					<td><div class="dp" id="div_mdo_loading_expense">&nbsp;</div></td>
				  </tr>
				  <tr bgcolor="f5f5f5">
					<td><div class="dp" style="color:#ff0000;"><b>MDO/Bunker Stop</b></div></td>
					<td><div class="dp"><input type="text" id="mdo_bunker_stop_id" name="mdo_bunker_stop" value="<?php echo $mdo_bunker_stop; ?>" class="number" style="width:120px;" onkeyup="calculateBunkerConsumption();" /></div></td>
					<td><div class="dp" id="div_mdo_bunker_stop_consumption">&nbsp;</div></td>
					<td><div class="dp" id="div_mdo_bunker_stop_expense">&nbsp;</div></td>
				  </tr>
				  <tr bgcolor="e9e9e9">
					<td><div class="dp" style="color:#00b050;"><b>MDO/Laden</b></div></td>
					<td><div class="dp"><input type="text" id="mdo_laden_id" name="mdo_laden" value="<?php echo $mdo_laden; ?>" class="number" style="width:120px;" onkeyup="calculateBunkerConsumption();" /></div></td>
					<td><div class="dp" id="div_mdo_laden_consumption">&nbsp;</div></td>
					<td><div class="dp" id="div_mdo_laden_expense">&nbsp;</div></td>
				  </tr>
				  <tr bgcolor="f5f5f5">
					<td><div class="dp" style="color:#ff0000;"><b>MDO/Discharging</b></div></td>
					<td><div class="dp"><input type="text" id="mdo_discharging_id" name="mdo_discharging" value="<?php echo $mdo_discharging; ?>" class="number" style="width:120px;" onkeyup="calculateBunkerConsumption();" /></div></td>
					<td><div class="dp" id="div_mdo_discharging_consumption">&nbsp;</div></td>
					<td><div class="dp" id="div_mdo_discharging_expense">&nbsp;</div></td>
				  </tr>
				  <tr bgcolor="e9e9e9">
					<td><div class="dp" style="color:#00b050;"><b>MDO/Repositioning</b></div></td>
					<td><div class="dp"><input type="text" id="mdo_repositioning_id" name="mdo_repositioning" value="<?php echo $mdo_repositioning; ?>" class="number" style="width:120px;" onkeyup="calculateBunkerConsumption();" /></div></td>
					<td><div class="dp" id="div_mdo_repositioning_consumption">&nbsp;</div></td>
					<td><div class="dp" id="div_mdo_repositioning_expense">&nbsp;</div></td>
				  </tr>
				  <tr bgcolor="f5f5f5">
					<td><div class="dp" style="color:#ff0000;"><b>MDO/Port</b></div></td>
					<td><div class="dp"><input type="text" id="mdo_port_id" name="mdo_port" value="<?php echo $mdo_port; ?>" class="number" style="width:120px;" onkeyup="calculateBunkerConsumption();" /></div></td>
					<td><div class="dp" id="div_mdo_port_consumption">&nbsp;</div></td>
					<td><div class="dp" id="div_mdo_port_expense">&nbsp;</div></td>
				  </tr>
				  <tr bgcolor="e9e9e9">
					<td><div class="dp"><b>MDO/Reserve</b></div></td>
					<td><div class="dp">&nbsp;</div></td>
					<td><div class="dp" id="div_mdo_mdo_reserve_id"><input type="text" id="mdo_reserve_id" name="mdo_reserve" value="<?php echo $mdo_reserve; ?>" class="number" style="width:120px;" onkeyup="calculateBunkerConsumption();" /></div></td>
					<td><div class="dp" id="div_mdo_reserve_expense">&nbsp;</div></td>
				  </tr>
				  <tr bgcolor="e9e9e9">
					<td><div class="dp"><b>MDO Total Expense ($)</b></div></td>
					<td colspan="3"><div class="dp" id="div_mdo_total_expense">&nbsp;</div></td>
				  </tr>
				</table>
			</td>
		  </tr>
		</table>
		
		<div style="border-bottom:3px dotted #fff;">&nbsp;</div>
		<div>&nbsp;</div>
		<!-- END OF BUNKER CONSUMPTIONS -->
		
		<!-- DWCC AND CANAL -->
		<table width="1200" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="595" bgcolor="cddee5"><div class="dp"><b>DWCC</b></div></td>
			<td width="10">&nbsp;</td>
			<td width="595" bgcolor="cddee5"><div class="dp"><b>CANAL</b></div></td>
		  </tr>
		</table>
		
		<table width="1200" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td valign="top">
				<table width="595" border="0" cellspacing="0" cellpadding="0">
				  <tr bgcolor="d6d6d6">
					<td width="297" colspan="2"><div class="dp"><b>DW (MT)</b></div></td>
					<td width="298"><div class="dp" id="div_dwt_id" style="font-weight:bold;">&nbsp;</div></td>
				  </tr>
				  <tr bgcolor="f5f5f5">
					<td rowspan="2"><div class="dp"><b>Consumption (MT)</b></div></td>
					<td><div class="dp"><b>FO</b></div></td>
					<td><div class="dp" id="div_dwcc_amount1_id">&nbsp;</div></td>
				  </tr>
				  <tr bgcolor="f5f5f5">
					<td><div class="dp"><b>DO</b></div></td>
					<td><div class="dp" id="div_dwcc_amount2_id">&nbsp;</div></td>
				  </tr>
				  <tr bgcolor="e9e9e9">
					<td rowspan="2"><div class="dp"><b>Reserve (MT)</b></div></td>
					<td><div class="dp"><b>FO</b></div></td>
					<td><div class="dp" id="div_dwcc_amount3_id">&nbsp;</div></td>
				  </tr>
				  <tr bgcolor="e9e9e9">
					<td><div class="dp"><b>DO</b></div></td>
					<td><div class="dp" id="div_dwcc_amount4_id">&nbsp;</div></td>
				  </tr>
				  <tr bgcolor="f5f5f5">
					<td colspan="2"><div class="dp"><b>FW (MT)</b></div></td>
					<td><div class="dp"><input type="text" id="dwcc_fw1_id" name="dwcc_fw1" value="<?php echo $dwcc_fw1; ?>" class="number" style="width:150px;" /></div></td>
				  </tr>
				  <tr bgcolor="e9e9e9">
					<td colspan="2"><div class="dp"><b>Constant (MT)</b></div></td>
					<td><div class="dp"><input type="text" id="dwcc_constant1_id" name="dwcc_constant1" value="<?php echo $dwcc_constant1; ?>" class="number" style="width:150px;" /></div></td>
				  </tr>
				  <tr bgcolor="f5f5f5">
					<td colspan="2"><div class="dp"><b>Used DW (MT)</b></div></td>
					<td><div class="dp" id="div_dwcc_amount5_id">&nbsp;</div></td>
				  </tr>
				  <tr bgcolor="e9e9e9">
					<td colspan="2"><div class="dp"><b>DWCC (MT)</b></div></td>
					<td><div class="dp" id="div_dwcc_amount6_id">&nbsp;</div></td>
				  </tr>
				</table>
			</td>
			<td width="10"></td>
			<td valign="top">
				<table width="595" border="0" cellspacing="0" cellpadding="0">
				  <tr bgcolor="d6d6d6">
					<td><div class="dp"><b>Canal</b></div></td>
					<td colspan="2">
						<div class="dp">
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
							<select id='canal_list_id' name="canal_list" style="width:200px;">
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
						</div>
					</td>
				  </tr>
				  <tr bgcolor="e9e9e9">
					<td width="199"><div class="dp"><b>Booking Fee ($)</b></div></td>
					<td width="198"><div class="dp"><input type="text" id="cbook1_id" name="cbook1" value="<?php echo $cbook1; ?>" class="number" style="width:150px;" /></div></td>
					<td width="198"><div class="dp"><input type="text" id="cbook2_id" name="cbook2" value="<?php echo $cbook2; ?>" class="number" style="width:150px;" /></div></td>
				  </tr>
				  <tr bgcolor="f5f5f5">
					<td><div class="dp"><b>Tugs ($)</b></div></td>
					<td><div class="dp"><input type="text" id="ctug1_id" name="ctug1" value="<?php echo $ctug1; ?>" class="number" style="width:150px;" /></div></td>
					<td><div class="dp"><input type="text" id="ctug2_id" name="ctug2" value="<?php echo $ctug2; ?>" class="number" style="width:150px;" /></div></td>
				  </tr>
				  <tr bgcolor="e9e9e9">
					<td><div class="dp"><b>Line Handlers ($)</b></div></td>
					<td><div class="dp"><input type="text" id="cline1_id" name="cline1" value="<?php echo $cline1; ?>" class="number" style="width:150px;" /></div></td>
					<td><div class="dp"><input type="text" id="cline2_id" name="cline2" value="<?php echo $cline2; ?>" class="number" style="width:150px;" /></div></td>
				  </tr>
				  <tr bgcolor="f5f5f5">
					<td><div class="dp"><b>Miscellaneous ($)</b></div></td>
					<td><div class="dp"><input type="text" id="cmisc1_id" name="cmisc1" value="<?php echo $cmisc1; ?>" class="number" style="width:150px;" /></div></td>
					<td><div class="dp"><input type="text" id="cmisc2_id" name="cmisc2" value="<?php echo $cmisc2; ?>" class="number" style="width:150px;" /></div></td>
				  </tr>
				  <tr bgcolor="e9e9e9">
					<td><div class="dp"><b>Total ($)</b></div></td>
					<td><div class="dp" id="div_ctotal1_id">&nbsp;</div></td>
					<td><div class="dp" id="div_ctotal2_id">&nbsp;</div></td>
				  </tr>
				</table>
			</td>
		  </tr>
		</table>
		
		<div style="border-bottom:3px dotted #fff;">&nbsp;</div>
		<div>&nbsp;</div>
		<!-- END OF DWCC AND CANAL -->
		
		<!-- PORTS -->
		<table width="1200" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="1200" bgcolor="cddee5"><div class="dp"><b>PORT(S)</b></div></td>
		  </tr>
		</table>
		
		<table width="1200" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td valign="top">
				<table width="1200" border="0" cellspacing="0" cellpadding="0" id="row_ports_id">
				  <tr id="row_ports" class="row_ports0" bgcolor="d6d6d6">
					<td width="200"><div class="dp"><b>Dem ($/day)</b> <span style="font-size:10px;">Pro Rated</span></div></td>
					<td width="200"><div class="dp"><b>Term</b></div></td>
					<td width="200"><div class="dp"><b>Des ($/day)</b></div></td>
					<td width="200"><div class="dp"><b>Liner Terms</b></div></td>
					<td width="200"><div class="dp"><b>Port</b></div></td>
					<td width="200"><div class="dp"><b>DA Quick Input ($)</b></div></td>
				  </tr>
				  <tr bgcolor="f5f5f5">
					<td><div class="dp"><b>Demurrage ($)</b></div></td>
					<td colspan="6"><div class="dp" id="div_demurrage_total_id">&nbsp;</div></td>
				  </tr>
				  <tr bgcolor="e9e9e9">
					<td><div class="dp"><b>Despatch ($)</b></div></td>
					<td colspan="6"><div class="dp" id="div_despatch_total_id">&nbsp;</div></td>
				  </tr>
				  <tr bgcolor="f5f5f5">
					<td><div class="dp"><b>Total ($)</b></div></td>
					<td colspan="6"><div class="dp" id="div_ports_total_id">&nbsp;</div></td>
				  </tr>
				</table>
			</td>
		  </tr>
		</table>
		
		<div style="border-bottom:3px dotted #fff;">&nbsp;</div>
		<div>&nbsp;</div>
		<!-- END OF PORTS -->
		
		<!-- VOYAGE DISBURSMENT -->
		<table width="1200" border="0" cellspacing="0" cellpadding="0">
		  <tr bgcolor="cddee5">
			<td colspan="4"><div class="dp"><b>VOYAGE DISBURSMENTS</b></div></td>
			<td colspan="5"><div class="dp"><b>VOYAGE</b></div></td>
		  </tr>
		  <tr>
			<td width="133"><div class="dp"><b>Bunker ($)</b></div></td>
			<td width="133"><div class="dp"><b>Port ($)</b></div></td>
			<td width="133"><div class="dp"><b>Canal($)</b></div></td>
			<td width="133"><div class="dp"><b>Add. Insurance ($)</b></div></td>
			<td width="133"><div class="dp"><b>ILOHC</b></div></td>
			<td width="133"><div class="dp"><b>ILOW</b></div></td>
			<td width="134"><div class="dp"><b>CVE</b></div></td>
			<td width="134"><div class="dp"><b>Ballast Bonus</b></div></td>
			<td width="134"><div class="dp"><b>Miscellaneous</b></div></td>
		  </tr>
		  <tr bgcolor="f5f5f5">
			<td><div class="dp" id='div_bunker_total_id'>&nbsp;</div></td>
			<td><div class="dp" id='div_port_total_id'>&nbsp;</div></td>
			<td><div class="dp" id='div_canal_total_id'>&nbsp;</div></td>
			<td><div class="dp" id='div_add_insurance_id'><input type="text" id="add_insurance_id" name="add_insurance" value="<?php echo $add_insurance; ?>" class="number" style="width:100px;" onkeyup="calculateBunkerConsumption();" /></div></td>
			<td><div class="dp" id='div_ilohc_id'><input type="text" id="ilohc_id" name="ilohc" value="<?php echo $ilohc; ?>" class="number" style="width:100px;" onkeyup="calculateBunkerConsumption();" /></div></td>
			<td><div class="dp" id='div_ilow_id'><input type="text" id="ilow_id" name="ilow" value="<?php echo $ilow; ?>" class="number" style="width:100px;" onkeyup="calculateBunkerConsumption();" /></div></td>
			<td><div class="dp" id='div_cve_id'><input type="text" id="cve_id" name="cve" value="<?php echo $cve; ?>" class="number" style="width:100px;" onkeyup="calculateBunkerConsumption();" /></div></td>
			<td><div class="dp" id='div_ballast_bonus_id'><input type="text" id="ballast_bonus_id" name="ballast_bonus" value="<?php echo $ballast_bonus; ?>" class="number" style="width:100px;" onkeyup="calculateBunkerConsumption();" /></div></td>
			<td><div class="dp" id='div_miscellaneous_id'><input type="text" id="miscellaneous_id" name="miscellaneous" value="<?php echo $miscellaneous; ?>" class="number" style="width:100px;" onkeyup="calculateBunkerConsumption();" /></div></td>
		  </tr>
		  <tr>
			<td colspan="9"><div class="dp" id='div_total_voyage_disbursment_id'>&nbsp;</div></td>
		  </tr>
		</table>
		
		<div style="border-bottom:3px dotted #fff;">&nbsp;</div>
		<div>&nbsp;</div>
		<!-- END OF VOYAGE DISBURSMENT -->
		
		<!-- AIS MAP -->
		<table width="1200" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="1200" bgcolor="cddee5"><div class="dp"><b>MAP - Data from AIS</b></div></td>
		  </tr>
		  <tr bgcolor="#000000">
			<td><iframe src='http://www.openstreetmap.org/export/embed.html?bbox=10.4,22,81.8,61.4&amp;layer=mapnik' id="map_iframeve" width='1200' height='400' frameborder="0"></iframe></td>
		  </tr>
		</table>
		
		<div>&nbsp;</div>
		<!-- END OF AIS MAP -->
	</td>
  </tr>
</table>
</form>
<script type="text/javascript">
$(document).ready(function(){
	getDwtType(jQuery("#dwt_type_id").val());
	thread();
});
</script>