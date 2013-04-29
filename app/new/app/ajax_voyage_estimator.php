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
	$imo = $_GET['imo'];
	
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

//OTHER FUNCTIONS
function thread(){
	calculateDates();
	portTo1Calc(true);
	calculateSeaPortDays();
	calculateBunkerConsumption();
	calculateDWCC();
	canalTotal();
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
		
		jQuery('#div_wadt'+rowCount+'_id').append('<input type="text" id="wadt'+rowCount+'_id" name="wadt'+rowCount+'" style="width:80px;" class="req wadt" onblur="this.value=fNum(this.value);" onkeyup="calculateSeaPortDays(); calculateBunkerConsumption();" />');
		jQuery('#div_tie_days'+rowCount+'_id').append('<input type="text" id="tie_days'+rowCount+'_id" name="tie_days'+rowCount+'" style="width:80px;" onblur="this.value=fNum(this.value);" onkeyup="calculateSeaPortDays(); calculateBunkerConsumption();" class="tie_days" />');
		
		if(jQuery("#voyage_type"+rowCount+"_id").val()=='Bunker Stop'){
			jQuery('#div_canal'+rowCount+'_id').append('<input type="text" id="canal'+rowCount+'_id" name="canal'+rowCount+'" style="width:50px;" onblur="this.value=fNum(this.value);" onkeyup="calculateDates2('+rowCount+'); portToCalc(true, '+rowCount+'); calculateSeaPortDays();" class="canal" />');
			jQuery('#div_weather_extra'+rowCount+'_id').append('<input type="text" id="weather_extra'+rowCount+'_id" name="weather_extra'+rowCount+'" style="width:50px;" onblur="this.value=fNum(this.value);" onkeyup="calculateDates2('+rowCount+'); portToCalc(true, '+rowCount+'); calculateSeaPortDays();" class="weather_extra" />');
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
		
		if(rowCount==1){
			jQuery('#div_canal'+rowCount+'_id').append('<input type="text" id="canal'+rowCount+'_id" name="canal'+rowCount+'" style="width:50px;" onblur="this.value=fNum(this.value);" onkeyup="calculateDates(); portTo1Calc(true); calculateSeaPortDays();" class="canal" />');
			jQuery('#div_weather_extra'+rowCount+'_id').append('<input type="text" id="weather_extra'+rowCount+'_id" name="weather_extra'+rowCount+'" style="width:50px;" onblur="this.value=fNum(this.value);" onkeyup="calculateDates(); portTo1Calc(true); calculateSeaPortDays();" class="weather_extra" />');
		}else{
			jQuery('#div_canal'+rowCount+'_id').append('<input type="text" id="canal'+rowCount+'_id" name="canal'+rowCount+'" style="width:50px;" onblur="this.value=fNum(this.value);" onkeyup="calculateDates2('+rowCount+'); portToCalc(true, '+rowCount+'); calculateSeaPortDays();" class="canal" />');
			jQuery('#div_weather_extra'+rowCount+'_id').append('<input type="text" id="weather_extra'+rowCount+'_id" name="weather_extra'+rowCount+'" style="width:50px;" onblur="this.value=fNum(this.value);" onkeyup="calculateDates2('+rowCount+'); portToCalc(true, '+rowCount+'); calculateSeaPortDays();" class="weather_extra" />');
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
						nextRowVoyageLegs += '<select id="voyage_type'+nextRowCount+'_id" name="voyage_type'+nextRowCount+'" style="width:140px;" class="req voyage_type" onchange="addSequenceCargo();">';
							
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
				nextRowVoyageLegs += '<td><div class="dp" id="div_port_to'+nextRowCount+'_id"><input type="text" id="port_to'+nextRowCount+'_id" name="port_to'+nextRowCount+'" style="width:181px;" class="req port_to" /></div></td>';
				nextRowVoyageLegs += '<td><div class="dp" id="div_date_to'+nextRowCount+'_id">'+jQuery('#div_date_to'+rowCount+'_id').text()+'</div></td>';
				nextRowVoyageLegs += '<td><div class="dp" id="div_speed'+nextRowCount+'_id"><input type="text" id="speed'+nextRowCount+'_id" name="speed'+nextRowCount+'" style="width:70px;" value="'+jQuery('#speed1_id').val()+'" class="speed" onblur="this.value=fNum(this.value);" onkeyup="calculateDates2('+nextRowCount+'); portToCalc(true, '+nextRowCount+'); calculateSeaPortDays();" /></div></td>';
				nextRowVoyageLegs += '<td><div class="dp" id="div_distance_miles'+nextRowCount+'_id"></div></td>';
				nextRowVoyageLegs += '<td><div class="dp" id="div_input_percent'+nextRowCount+'_id"><input type="text" id="input_percent'+nextRowCount+'_id" name="input_percent'+nextRowCount+'" style="width:70px;" class="number" onkeyup="computeDistanceMiles(this.value, '+nextRowCount+');" onblur="this.value=fNum(this.value);" onkeyup="calculateDates2('+nextRowCount+'); portToCalc(true, '+nextRowCount+'); calculateSeaPortDays();" /></div></td>';
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
}
//END OF CALCULATE BUNKER CONSUMPTION

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
	width:1480px;
	height:auto;
	padding:10px;
}

.div_title{
	float:left;
	width:135px;
	height:auto;
}

.div_content{
	float:left;
	width:1345px;
	height:auto;
}

.dp{
	padding:10px;
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

<form method="post" id="voyageestimatorform" name="voyageestimatorform" enctype="multipart/form-data">
<table width="1800" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="1500">
			<!-- CHOOSE VESSEL BY DWT TYPE OR VESSEL NAME / IMO# -->
			<table width="1500" border="0" cellspacing="0" cellpadding="0">
				<tr bgcolor="cddee5">
					<td>
						<div class="div_all">
							<div class="div_title"><b>Vessel by:</b></div>
							<div class="div_content">
								<select id="vessel_by_id" name="vessel_by" style="width:300px;" onchange="getVesselBy(this.value);" class="req">
									<option value="0">- Select Vessel By -</option>
									<option value="1">DWT Type</option>
									<option value="2">Vessel Name / IMO #</option>
								</select>
							</div>
						</div>
					</td>
				</tr>
				<tr bgcolor="d6d6d6">
					<td>
						<div class="div_all" style="display:none;" id="vessel_by_1">
							<div class="div_title"><b>DWT Type:</b></div>
							<div class="div_content">
								<select id="dwt_type_id" name="dwt_type" style="width:300px;" class="req" onchange="getDwtType(this.value);">
									<option value="0">- Select DWT Type -</option>
									<option value="7208728">(0-9,999) Mini Bulker</option>
									<option value="9177791">(10,000-39,999) Handysize</option>
									<option value="9547805">(40,000-59,999) Handymax / Supramax</option>
									<option value="9111577">(60,000-99,999) Panamax</option>
									<option value="9587386">(100,000-219,999) Capesize</option>
									<option value="9565065">(220,000+) Very Large Ore Carrier</option>
								</select>
							</div>
						</div>
						<div class="div_all" style="display:none;" id="vessel_by_2">
							<div class="div_title"><b>Vessel Name / IMO #:</b></div>
							<div class="div_content"><input type="text" id="vessel_name_or_imo_id" name="vessel_name_or_imo" style="width:295px;" value="<?php echo $vessel_name_or_imo; ?>" class="req" /> &nbsp; <span id='shipdetailshref' style="color:#F00;"></span></div>
						</div>
					</td>
				</tr>
			</table>
			<div id="ship_info" style="display:none;">
				<table width="1500" border="0" cellspacing="0" cellpadding="0">
				  <tr bgcolor="f5f5f5">
					<td width="140" valign="top"><div style="padding:3px;"><b>IMO</b> #</div></td>
					<td width="235" valign="top"><div style="padding:3px;" id="ship_imo">&nbsp;</div></td>
					<td width="140" valign="top"><div style="padding:3px;"><b>LOA</b></div></td>
					<td width="235" valign="top"><div style="padding:3px;" id="ship_loa">&nbsp;</div></td>
					<td width="140" valign="top"><div style="padding:3px;"><b>Grain</b></div></td>
					<td width="235" valign="top"><div style="padding:3px;" id="ship_grain">&nbsp;</div></td>
					<td width="140" valign="top"><div style="padding:3px;"><b>Class Notation</b></div></td>
					<td width="235" valign="top"><div style="padding:3px;" id="ship_class_notation">&nbsp;</div></td>
				  </tr>
				  <tr bgcolor="e9e9e9">
					<td valign="top"><div style="padding:3px;"><b>Summer DWT</b></div></td>
					<td valign="top"><div style="padding:3px;" id="ship_summer_dwt">&nbsp;</div></td>
					<td valign="top"><div style="padding:3px;"><b>Draught</b></div></td>
					<td valign="top"><div style="padding:3px;" id="ship_draught">&nbsp;</div></td>
					<td valign="top"><div style="padding:3px;"><b>Lifting Equipment</b></div></td>
					<td valign="top"><div style="padding:3px;" id="ship_lifting_equipment">&nbsp;</div></td>
					<td valign="top"><div style="padding:3px;"><b>Fuel Oil</b></div></td>
					<td valign="top"><div style="padding:3px;" id="ship_fuel_oil">&nbsp;</div></td>
				  </tr>
				  <tr bgcolor="f5f5f5">
					<td valign="top"><div style="padding:3px;"><b>Gross Tonnage</b></div></td>
					<td valign="top"><div style="padding:3px;" id="ship_gross_tonnage">&nbsp;</div></td>
					<td valign="top"><div style="padding:3px;"><b>Net Tonnage</b></div></td>
					<td valign="top"><div style="padding:3px;" id="ship_net_tonnage">&nbsp;</div></td>
					<td valign="top"><div style="padding:3px; color:#FF0000;"><b>Speed</b></div></td>
					<td valign="top"><div style="padding:3px; color:#FF0000;" id="ship_speed">&nbsp;</div></td>
					<td valign="top"><div style="padding:3px;"><b>Cargo Handling</b></div></td>
					<td valign="top"><div style="padding:3px;" id="ship_cargo_handling">&nbsp;</div></td>
				  </tr>
				  <tr bgcolor="e9e9e9">
					<td valign="top"><div style="padding:3px;"><b>Fuel</b></div></td>
					<td valign="top"><div style="padding:3px;" id="ship_fuel">&nbsp;</div></td>
					<td valign="top"><div style="padding:3px;"><b>Built Year</b></div></td>
					<td valign="top"><div style="padding:3px;" id="ship_built_year">&nbsp;</div></td>
					<td valign="top"><div style="padding:3px; color:#FF0000;"><b>Speed AIS</b></div></td>
					<td valign="top"><div style="padding:3px; color:#FF0000;" id="ship_speed_ais">&nbsp;</div></td>
					<td valign="top"><div style="padding:3px;"><b>Breadth</b></div></td>
					<td valign="top"><div style="padding:3px;" id="ship_breadth">&nbsp;</div></td>
				  </tr>
				  <tr bgcolor="f5f5f5">
					<td valign="top"><div style="padding:3px;"><b>Decks Number</b></div></td>
					<td valign="top"><div style="padding:3px;" id="ship_decks_number">&nbsp;</div></td>
					<td valign="top"><div style="padding:3px;"><b>Fuel Consumption</b></div></td>
					<td valign="top"><div style="padding:3px;" id="ship_fuel_consumption">&nbsp;</div></td>
					<td valign="top"><div style="padding:3px; color:#FF0000;"><b>Movement Status</b></div></td>
					<td valign="top"><div style="padding:3px; color:#FF0000;" id="ship_NavigationalStatus">&nbsp;</div></td>
					<td valign="top"><div style="padding:3px;"><b>Bale</b></div></td>
					<td valign="top"><div style="padding:3px;" id="ship_bale">&nbsp;</div></td>
				  </tr>
				  <tr bgcolor="e9e9e9">
					<td valign="top"><div style="padding:3px;"><b>Cranes</b></div></td>
					<td valign="top"><div style="padding:3px;" id="ship_cranes">&nbsp;</div></td>
					<td valign="top"><div style="padding:3px;"><b>Bulkheads</b></div></td>
					<td valign="top"><div style="padding:3px;" id="ship_bulkheads">&nbsp;</div></td>
					<td valign="top"><div style="padding:3px; color:#FF0000;"><b>AIS Date Updated</b></div></td>
					<td valign="top"><div style="padding:3px; color:#FF0000;" id="ship_aisdateupdated">&nbsp;</div></td>
					<td valign="top"><div style="padding:3px;"><b>Fuel Type</b></div></td>
					<td valign="top"><div style="padding:3px;" id="ship_fuel_type">&nbsp;</div></td>
				  </tr>
				  <tr bgcolor="f5f5f5">
					<td valign="top"><div style="padding:3px;"><b>Manager Owner</b></div></td>
					<td valign="top"><div style="padding:3px;" id="ship_manager_owner">&nbsp;</div></td>
					<td valign="top"><div style="padding:3px;"><b>Manager Owner Email</b></div></td>
					<td valign="top"><div style="padding:3px;" id="ship_manager_owner_email">&nbsp;</div></td>
					<td valign="top"><div style="padding:3px;"><b>Class Society</b></div></td>
					<td valign="top"><div style="padding:3px;" id="ship_class_society">&nbsp;</div></td>
					<td valign="top"><div style="padding:3px;"><b>Largest Hatch</b></div></td>
					<td valign="top"><div style="padding:3px;" id="ship_largest_hatch">&nbsp;</div></td>
				  </tr>
				  <tr bgcolor="e9e9e9">
					<td valign="top"><div style="padding:3px;"><b>Holds</b></div></td>
					<td valign="top"><div style="padding:3px;" id="ship_holds">&nbsp;</div></td>
					<td valign="top"><div style="padding:3px;"><b>Flag</b></div></td>
					<td valign="top"><div style="padding:3px;" id="ship_flag">&nbsp;</div></td>
					<td valign="top"><div style="padding:3px;"><b>&nbsp;</b></div></td>
					<td valign="top"><div style="padding:3px;">&nbsp;</div></td>
					<td valign="top"><div style="padding:3px;"><b>&nbsp;</b></div></td>
					<td valign="top"><div style="padding:3px;">&nbsp;</div></td>
				  </tr>
				</table>
			</div>
			
			<div style="border-bottom:3px dotted #fff;">&nbsp;</div>
			<div>&nbsp;</div>
			<!-- CHOOSE VESSEL BY DWT TYPE OR VESSEL NAME / IMO# -->
			
			<!-- VOYAGE LEGS -->
			<table width="1500" border="0" cellspacing="0" cellpadding="0" id="voyage_legs_id">
				<tr bgcolor="cddee5">
					<td colspan="10">
						<div class="div_all">
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
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
					<td width="188" colspan="2"><div class="dp"><b>Type</b></div></td>
					<td width="208"><div class="dp"><b>Port</b></div></td>
					<td width="208"><div class="dp"><b>Date</b></div></td>
					<td width="208"><div class="dp"><b>Port</b></div></td>
					<td width="208"><div class="dp"><b>Date</b></div></td>
					<td width="120"><div class="dp"><b>Speed (knts)</b></div></td>
					<td width="120"><div class="dp"><b>Distance (miles)</b></div></td>
					<td width="120"><div class="dp"><b>Input %</b></div></td>
					<td width="120"><div class="dp"><b>% Sea Margin</b></div></td>
				</tr>
				<tr bgcolor="f5f5f5" id="voyage_legs_row" class="voyage_legs_row1">
					<td><div class="dp">&nbsp;</div></td>
					<td>
						<div class="dp" id="div_voyage_type1_id">
							<select id="voyage_type1_id" name="voyage_type1" style="width:140px;" class="req voyage_type" onchange="addSequenceCargo();">
								<option value="">- Select Type -</option>
								<option value="Ballast">Ballast</option>
								<option value="Loading">Loading</option>
							</select>
						</div>
					</td>
					<td><div class="dp" id="div_port_from1_id"><input type="text" id="port_from1_id" name="port_from1" style="width:181px;" class="req port_from" /></div></td>
					<td><div class="dp" id="div_date_from1_id"><input type="text" id="date_from1_id" name="date_from1" style="width:181px;" class="req date" readonly="readonly" /></div></td>
					<td><div class="dp" id="div_port_to1_id"><input type="text" id="port_to1_id" name="port_to1" style="width:181px;" class="req port_to" /></div></td>
					<td><div class="dp" id="div_date_to1_id">&nbsp;</div></td>
					<td><div class="dp" id="div_speed1_id"><input type="text" id="speed1_id" name="speed1" style="width:70px;" class="speed number" /></div></td>
					<td><div class="dp" id="div_distance_miles1_id">&nbsp;</div></td>
					<td><div class="dp" id="div_input_percent1_id"><input type="text" id="input_percent1_id" name="input_percent1" style="width:70px;" class="number" onkeyup="computeDistanceMiles1(this.value);" /></div></td>
					<td><div class="dp" id="div_sea_margin1_id"></div></td>
				</tr>
			</table>
			
			<div style="border-bottom:3px dotted #fff;">&nbsp;</div>
			<div>&nbsp;</div>
			<!-- END OF VOYAGE LEGS -->
			
			<!-- CARGO LEGS -->
			<table width="1500" border="0" cellspacing="0" cellpadding="0" id="cargo_legs_id">
				<tr bgcolor="cddee5">
					<td colspan="6"><div class="dp"><b>CARGO LEGS</b></div></td>
					<td colspan="2"><div class="dp"><b>* Option to Load & Bunker concurrently</b></div></td>
					<td colspan="2"><div class="dp"><b>Port Days</b></div></td>
					<td colspan="3"><div class="dp"><b>Sea Days</b></div></td>
				</tr>
				<tr bgcolor="d6d6d6">
					<td width="116"><div class="dp"><b>Type</b></div></td>
					<td width="192"><div class="dp"><b>Cargo</b></div></td>
					<td width="70"><div class="dp"><b>SF</b></div></td>
					<td width="116"><div class="dp"><b>Quantity (MT)</b></div></td>
					<td width="116"><div class="dp"><b>Volume (M3)</b></div></td>
					<td width="115"><div class="dp"><b>L/D Rate (MT/day)</b></div></td>
					<td width="115"><div class="dp"><b>Load Days</b></div></td>
					<td width="115"><div class="dp"><b>Working Days TERMS</b></div></td>
					<td width="115"><div class="dp"><b>Working Aditional Days TERMS</b></div></td>
					<td width="115"><div class="dp"><b>Turn/Idle/Extra Days</b></div></td>
					<td width="115"><div class="dp"><b>Voyage Days</b></div></td>
					<td width="100"><div class="dp"><b>Canal Days</b></div></td>
					<td width="100"><div class="dp"><b>Weather/Extra Days</b></div></td>
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
			<table width="1500" border="0" cellspacing="0" cellpadding="0">
			  <tr bgcolor="cddee5">
				<td colspan="3"><div class="dp"><b>VOYAGE TIME</b></div></td>
			  </tr>
			  <tr>
				<td width="30%"><div class="dp"><b>PORT DAYS</b></div></td>
				<td width="30%"><div class="dp"><b>SEA DAYS</b></div></td>
				<td width="40%"><div class="dp"><b>TOTAL VOYAGE DAYS</b></div></td>
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
			<table width="1500" border="0" cellspacing="0" cellpadding="0">
			  <tr bgcolor="cddee5">
				<td><div class="dp"><b>BUNKER PRICING - Data from Bunkerworld</b> <span id="bunker_price_dateupdated" style="color:#FF0000;">&nbsp;</span></div></td>
			  </tr>
			</table>
			
			<table width="1500" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td valign="top">
					<table width="738" border="0" cellspacing="0" cellpadding="0">
					  <tr bgcolor="d6d6d6">
						<td width="246"><div class="dp"><b>Type IFO</b></div></td>
						<td width="246"><div class="dp"><b>Price Input ($)</b></div></td>
						<td width="246"><div class="dp"><b>Price Available ($)</b></div></td>
					  </tr>
					  <tr bgcolor="f5f5f5">
						<td><div class="dp"><b>IFO 380</b></div></td>
						<td><div class="dp"><input type="text" id="ifo1_id" name="ifo1" class="number" style="width:200px;" onkeyup="calculateBunkerConsumption();" /></div></td>
						<td><div class="dp" id="div_ifo1_id" style="color:#FF0000; font-weight:bold;">&nbsp;</div></td>
					  </tr>
					  <tr bgcolor="e9e9e9">
						<td><div class="dp"><b>IFO 180</b></div></td>
						<td><div class="dp"><input type="text" id="ifo2_id" name="ifo2" class="number" style="width:200px;" onkeyup="calculateBunkerConsumption();" /></div></td>
						<td><div class="dp" id="div_ifo2_id" style="color:#FF0000; font-weight:bold;">&nbsp;</div></td>
					  </tr>
					  <tr bgcolor="f5f5f5">
						<td><div class="dp"><b>LS IFO 380 1%</b></div></td>
						<td><div class="dp"><input type="text" id="ifo3_id" name="ifo3" class="number" style="width:200px;" onkeyup="calculateBunkerConsumption();" /></div></td>
						<td><div class="dp" id="div_ifo3_id" style="color:#FF0000; font-weight:bold;">&nbsp;</div></td>
					  </tr>
					  <tr bgcolor="e9e9e9">
						<td><div class="dp"><b>LS IFO 180 1%</b></div></td>
						<td><div class="dp"><input type="text" id="ifo4_id" name="ifo4" class="number" style="width:200px;" onkeyup="calculateBunkerConsumption();" /></div></td>
						<td><div class="dp" id="div_ifo4_id" style="color:#FF0000; font-weight:bold;">&nbsp;</div></td>
					  </tr>
					</table>
				</td>
				<td width="24"></td>
				<td valign="top">
					<table width="738" border="0" cellspacing="0" cellpadding="0">
					  <tr bgcolor="d6d6d6">
						<td width="246"><div class="dp"><b>Type MDO</b></div></td>
						<td width="246"><div class="dp"><b>Price Input ($)</b></div></td>
						<td width="246"><div class="dp"><b>Price Available ($)</b></div></td>
					  </tr>
					  <tr bgcolor="f5f5f5">
						<td><div class="dp"><b>MDO</b></div></td>
						<td><div class="dp"><input type="text" id="mdo1_id" name="mdo1" class="number" style="width:200px;" onkeyup="calculateBunkerConsumption();" /></div></td>
						<td><div class="dp" id="div_mdo1_id" style="color:#FF0000; font-weight:bold;">&nbsp;</div></td>
					  </tr>
					  <tr bgcolor="e9e9e9">
						<td><div class="dp"><b>MGO</b></div></td>
						<td><div class="dp"><input type="text" id="mdo2_id" name="mdo2" class="number" style="width:200px;" onkeyup="calculateBunkerConsumption();" /></div></td>
						<td><div class="dp" id="div_mdo2_id" style="color:#FF0000; font-weight:bold;">&nbsp;</div></td>
					  </tr>
					  <tr bgcolor="f5f5f5">
						<td><div class="dp"><b>LS MGO 1%</b></div></td>
						<td><div class="dp"><input type="text" id="mdo3_id" name="mdo3" class="number" style="width:200px;" onkeyup="calculateBunkerConsumption();" /></div></td>
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
			<table width="1500" border="0" cellspacing="0" cellpadding="0">
			  <tr bgcolor="cddee5">
				<td><div class="dp"><b>BUNKER CONSUMPTIONS</b></div></td>
			  </tr>
			</table>
			
			<table width="1500" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td valign="top">
					<table width="738" border="0" cellspacing="0" cellpadding="0">
					  <tr bgcolor="d6d6d6">
						<td width="184"><div class="dp"><b>Voyage Type</b></div></td>
						<td width="184"><div class="dp"><b>Consumption (MT/day)</b></div></td>
						<td width="185"><div class="dp"><b>Total Consumption (MT)</b></div></td>
						<td width="185"><div class="dp"><b>Voyage Expense ($)</b></div></td>
					  </tr>
					  <tr bgcolor="f5f5f5">
						<td><div class="dp" style="color:#ff0000;"><b>IFO/Ballast</b></div></td>
						<td><div class="dp"><input type="text" id="ifo_ballast_id" name="ifo_ballast" class="number" style="width:150px;" onkeyup="calculateBunkerConsumption();" /></div></td>
						<td><div class="dp" id="div_ifo_ballast_consumption">&nbsp;</div></td>
						<td><div class="dp" id="div_ifo_ballast_expense">&nbsp;</div></td>
					  </tr>
					  <tr bgcolor="e9e9e9">
						<td><div class="dp" style="color:#00b050;"><b>IFO/Loading</b></div></td>
						<td><div class="dp"><input type="text" id="ifo_loading_id" name="ifo_loading" class="number" style="width:150px;" onkeyup="calculateBunkerConsumption();" /></div></td>
						<td><div class="dp" id="div_ifo_loading_consumption">&nbsp;</div></td>
						<td><div class="dp" id="div_ifo_loading_expense">&nbsp;</div></td>
					  </tr>
					  <tr bgcolor="f5f5f5">
						<td><div class="dp" style="color:#ff0000;"><b>IFO/Bunker Stop</b></div></td>
						<td><div class="dp"><input type="text" id="ifo_bunker_stop_id" name="ifo_bunker_stop" class="number" style="width:150px;" onkeyup="calculateBunkerConsumption();" /></div></td>
						<td><div class="dp" id="div_ifo_bunker_stop_consumption">&nbsp;</div></td>
						<td><div class="dp" id="div_ifo_bunker_stop_expense">&nbsp;</div></td>
					  </tr>
					  <tr bgcolor="e9e9e9">
						<td><div class="dp" style="color:#00b050;"><b>IFO/Laden</b></div></td>
						<td><div class="dp"><input type="text" id="ifo_laden_id" name="ifo_laden" class="number" style="width:150px;" onkeyup="calculateBunkerConsumption();" /></div></td>
						<td><div class="dp" id="div_ifo_laden_consumption">&nbsp;</div></td>
						<td><div class="dp" id="div_ifo_laden_expense">&nbsp;</div></td>
					  </tr>
					  <tr bgcolor="f5f5f5">
						<td><div class="dp" style="color:#ff0000;"><b>IFO/Discharging</b></div></td>
						<td><div class="dp"><input type="text" id="ifo_discharging_id" name="ifo_discharging" class="number" style="width:150px;" onkeyup="calculateBunkerConsumption();" /></div></td>
						<td><div class="dp" id="div_ifo_discharging_consumption">&nbsp;</div></td>
						<td><div class="dp" id="div_ifo_discharging_expense">&nbsp;</div></td>
					  </tr>
					  <tr bgcolor="e9e9e9">
						<td><div class="dp" style="color:#00b050;"><b>IFO/Repositioning</b></div></td>
						<td><div class="dp"><input type="text" id="ifo_repositioning_id" name="ifo_repositioning" class="number" style="width:150px;" onkeyup="calculateBunkerConsumption();" /></div></td>
						<td><div class="dp" id="div_ifo_repositioning_consumption">&nbsp;</div></td>
						<td><div class="dp" id="div_ifo_repositioning_expense">&nbsp;</div></td>
					  </tr>
					  <tr bgcolor="f5f5f5">
						<td><div class="dp" style="color:#ff0000;"><b>IFO/Port</b></div></td>
						<td><div class="dp"><input type="text" id="ifo_port_id" name="ifo_port" class="number" style="width:150px;" onkeyup="calculateBunkerConsumption();" /></div></td>
						<td><div class="dp" id="div_ifo_port_consumption">&nbsp;</div></td>
						<td><div class="dp" id="div_ifo_port_expense">&nbsp;</div></td>
					  </tr>
					  <tr bgcolor="e9e9e9">
						<td><div class="dp"><b>IFO/Reserve</b></div></td>
						<td><div class="dp">&nbsp;</div></td>
						<td><div class="dp" id="div_ifo_ifo_reserve_id"><input type="text" id="ifo_reserve_id" name="ifo_reserve" class="number" style="width:150px;" onkeyup="calculateBunkerConsumption();" /></div></td>
						<td><div class="dp" id="div_ifo_reserve_expense">&nbsp;</div></td>
					  </tr>
					  <tr bgcolor="e9e9e9">
						<td><div class="dp"><b>IFO Total Expense ($)</b></div></td>
						<td colspan="3"><div class="dp" id="div_ifo_total_expense">&nbsp;</div></td>
					  </tr>
					</table>
				</td>
				<td width="24"></td>
				<td valign="top">
					<table width="738" border="0" cellspacing="0" cellpadding="0">
					  <tr bgcolor="d6d6d6">
						<td width="184"><div class="dp"><b>Voyage Type</b></div></td>
						<td width="184"><div class="dp"><b>Consumption (MT/day)</b></div></td>
						<td width="185"><div class="dp"><b>Total Consumption (MT)</b></div></td>
						<td width="185"><div class="dp"><b>Voyage Expense ($)</b></div></td>
					  </tr>
					  <tr bgcolor="f5f5f5">
						<td><div class="dp" style="color:#ff0000;"><b>MDO/Ballast</b></div></td>
						<td><div class="dp"><input type="text" id="mdo_ballast_id" name="mdo_ballast" class="number" style="width:150px;" onkeyup="calculateBunkerConsumption();" /></div></td>
						<td><div class="dp" id="div_mdo_ballast_consumption">&nbsp;</div></td>
						<td><div class="dp" id="div_mdo_ballast_expense">&nbsp;</div></td>
					  </tr>
					  <tr bgcolor="e9e9e9">
						<td><div class="dp" style="color:#00b050;"><b>MDO/Loading</b></div></td>
						<td><div class="dp"><input type="text" id="mdo_loading_id" name="mdo_loading" class="number" style="width:150px;" onkeyup="calculateBunkerConsumption();" /></div></td>
						<td><div class="dp" id="div_mdo_loading_consumption">&nbsp;</div></td>
						<td><div class="dp" id="div_mdo_loading_expense">&nbsp;</div></td>
					  </tr>
					  <tr bgcolor="f5f5f5">
						<td><div class="dp" style="color:#ff0000;"><b>MDO/Bunker Stop</b></div></td>
						<td><div class="dp"><input type="text" id="mdo_bunker_stop_id" name="mdo_bunker_stop" class="number" style="width:150px;" onkeyup="calculateBunkerConsumption();" /></div></td>
						<td><div class="dp" id="div_mdo_bunker_stop_consumption">&nbsp;</div></td>
						<td><div class="dp" id="div_mdo_bunker_stop_expense">&nbsp;</div></td>
					  </tr>
					  <tr bgcolor="e9e9e9">
						<td><div class="dp" style="color:#00b050;"><b>MDO/Laden</b></div></td>
						<td><div class="dp"><input type="text" id="mdo_laden_id" name="mdo_laden" class="number" style="width:150px;" onkeyup="calculateBunkerConsumption();" /></div></td>
						<td><div class="dp" id="div_mdo_laden_consumption">&nbsp;</div></td>
						<td><div class="dp" id="div_mdo_laden_expense">&nbsp;</div></td>
					  </tr>
					  <tr bgcolor="f5f5f5">
						<td><div class="dp" style="color:#ff0000;"><b>MDO/Discharging</b></div></td>
						<td><div class="dp"><input type="text" id="mdo_discharging_id" name="mdo_discharging" class="number" style="width:150px;" onkeyup="calculateBunkerConsumption();" /></div></td>
						<td><div class="dp" id="div_mdo_discharging_consumption">&nbsp;</div></td>
						<td><div class="dp" id="div_mdo_discharging_expense">&nbsp;</div></td>
					  </tr>
					  <tr bgcolor="e9e9e9">
						<td><div class="dp" style="color:#00b050;"><b>MDO/Repositioning</b></div></td>
						<td><div class="dp"><input type="text" id="mdo_repositioning_id" name="mdo_repositioning" class="number" style="width:150px;" onkeyup="calculateBunkerConsumption();" /></div></td>
						<td><div class="dp" id="div_mdo_repositioning_consumption">&nbsp;</div></td>
						<td><div class="dp" id="div_mdo_repositioning_expense">&nbsp;</div></td>
					  </tr>
					  <tr bgcolor="f5f5f5">
						<td><div class="dp" style="color:#ff0000;"><b>MDO/Port</b></div></td>
						<td><div class="dp"><input type="text" id="mdo_port_id" name="mdo_port" class="number" style="width:150px;" onkeyup="calculateBunkerConsumption();" /></div></td>
						<td><div class="dp" id="div_mdo_port_consumption">&nbsp;</div></td>
						<td><div class="dp" id="div_mdo_port_expense">&nbsp;</div></td>
					  </tr>
					  <tr bgcolor="e9e9e9">
						<td><div class="dp"><b>MDO/Reserve</b></div></td>
						<td><div class="dp">&nbsp;</div></td>
						<td><div class="dp" id="div_mdo_mdo_reserve_id"><input type="text" id="mdo_reserve_id" name="mdo_reserve" class="number" style="width:150px;" onkeyup="calculateBunkerConsumption();" /></div></td>
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
			<table width="1500" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td width="738" bgcolor="cddee5"><div class="dp"><b>DWCC</b></div></td>
				<td width="24">&nbsp;</td>
				<td width="738" bgcolor="cddee5"><div class="dp"><b>CANAL</b></div></td>
			  </tr>
			</table>
			
			<table width="1500" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td valign="top">
					<table width="738" border="0" cellspacing="0" cellpadding="0">
					  <tr bgcolor="d6d6d6">
						<td width="369" colspan="2"><div class="dp"><b>DW (MT)</b></div></td>
						<td width="369"><div class="dp" id="div_dwt_id" style="font-weight:bold;">&nbsp;</div></td>
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
						<td><div class="dp"><input type="text" id="dwcc_fw1_id" name="dwcc_fw1" class="number" style="width:150px;" /></div></td>
					  </tr>
					  <tr bgcolor="e9e9e9">
						<td colspan="2"><div class="dp"><b>Constant (MT)</b></div></td>
						<td><div class="dp"><input type="text" id="dwcc_constant1_id" name="dwcc_constant1" class="number" style="width:150px;" /></div></td>
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
				<td width="24"></td>
				<td valign="top">
					<table width="738" border="0" cellspacing="0" cellpadding="0">
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
										echo '<option value="'.$canalarr[$canali].'">'.$canalarr[$canali].'</option>';
									}
									?>
								</select>
							</div>
						</td>
					  </tr>
					  <tr bgcolor="e9e9e9">
						<td width="246"><div class="dp"><b>Booking Fee ($)</b></div></td>
						<td width="246"><div class="dp"><input type="text" id="cbook1_id" name="cbook1" class="number" style="width:150px;" /></div></td>
						<td width="246"><div class="dp"><input type="text" id="cbook2_id" name="cbook2" class="number" style="width:150px;" /></div></td>
					  </tr>
					  <tr bgcolor="f5f5f5">
						<td width="246"><div class="dp"><b>Tugs ($)</b></div></td>
						<td width="246"><div class="dp"><input type="text" id="ctug1_id" name="ctug1" class="number" style="width:150px;" /></div></td>
						<td width="246"><div class="dp"><input type="text" id="ctug2_id" name="ctug2" class="number" style="width:150px;" /></div></td>
					  </tr>
					  <tr bgcolor="e9e9e9">
						<td width="246"><div class="dp"><b>Line Handlers ($)</b></div></td>
						<td width="246"><div class="dp"><input type="text" id="cline1_id" name="cline1" class="number" style="width:150px;" /></div></td>
						<td width="246"><div class="dp"><input type="text" id="cline2_id" name="cline2" class="number" style="width:150px;" /></div></td>
					  </tr>
					  <tr bgcolor="f5f5f5">
						<td width="246"><div class="dp"><b>Miscellaneous ($)</b></div></td>
						<td width="246"><div class="dp"><input type="text" id="cmisc1_id" name="cmisc1" class="number" style="width:150px;" /></div></td>
						<td width="246"><div class="dp"><input type="text" id="cmisc2_id" name="cmisc2" class="number" style="width:150px;" /></div></td>
					  </tr>
					  <tr bgcolor="e9e9e9">
						<td width="246"><div class="dp"><b>Total ($)</b></div></td>
						<td width="246"><div class="dp" id="div_ctotal1_id">&nbsp;</div></td>
						<td width="246"><div class="dp" id="div_ctotal2_id">&nbsp;</div></td>
					  </tr>
					</table>
				</td>
			  </tr>
			</table>
			
			<div style="border-bottom:3px dotted #fff;">&nbsp;</div>
			<div>&nbsp;</div>
			<!-- END OF DWCC AND CANAL -->
			
			<!-- PORTS -->
			<table width="1500" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td width="1500" bgcolor="cddee5"><div class="dp"><b>PORT(S)</b></div></td>
			  </tr>
			</table>
			
			<table width="1500" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td valign="top">
					<table width="1500" border="0" cellspacing="0" cellpadding="0" id="row_ports_id">
					  <tr id="row_ports" class="row_ports0" bgcolor="d6d6d6">
						<td><div class="dp"><b>Dem ($/day)</b> <span style="font-size:10px;">Pro Rated</span></div></td>
						<td><div class="dp"><b>Term</b></div></td>
						<td><div class="dp"><b>Des ($/day)</b></div></td>
						<td><div class="dp"><b>Liner Terms</b></div></td>
						<td width="200"><div class="dp"><b>Port</b></div></td>
						<td><div class="dp"><b>DA Quick Input ($)</b></div></td>
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
			
			<!-- VOYAGE TIME -->
			<table width="1500" border="0" cellspacing="0" cellpadding="0">
			  <tr bgcolor="cddee5">
				<td colspan="4"><div class="dp"><b>VOYAGE DISBURSMENTS</b></div></td>
				<td colspan="5"><div class="dp"><b>VOYAGE</b></div></td>
			  </tr>
			  <tr>
				<td width="166"><div class="dp"><b>Bunker ($)</b></div></td>
				<td width="166"><div class="dp"><b>Port ($)</b></div></td>
				<td width="166"><div class="dp"><b>Canal($)</b></div></td>
				<td width="167"><div class="dp"><b>Add. Insurance ($)</b></div></td>
				<td width="167"><div class="dp"><b>ILOHC</b></div></td>
				<td width="167"><div class="dp"><b>ILOW</b></div></td>
				<td width="167"><div class="dp"><b>CVE</b></div></td>
				<td width="167"><div class="dp"><b>Ballast Bonus</b></div></td>
				<td width="167"><div class="dp"><b>Miscellaneous</b></div></td>
			  </tr>
			  <tr bgcolor="f5f5f5">
				<td><div class="dp" id='div_bunker_total_id'>&nbsp;</div></td>
				<td><div class="dp" id='div_port_total_id'>&nbsp;</div></td>
				<td><div class="dp" id='div_canal_total_id'>&nbsp;</div></td>
				<td><div class="dp" id='div_add_insurance_id'><input type="text" id="add_insurance_id" name="add_insurance" class="number" style="width:125px;" /></div></td>
				<td><div class="dp" id='div_ilohc_id'><input type="text" id="ilohc_id" name="ilohc" class="number" style="width:125px;" /></div></td>
				<td><div class="dp" id='div_ilow_id'><input type="text" id="ilow_id" name="ilow" class="number" style="width:125px;" /></div></td>
				<td><div class="dp" id='div_cve_id'><input type="text" id="cve_id" name="cve" class="number" style="width:125px;" /></div></td>
				<td><div class="dp" id='div_ballast_bonus_id'><input type="text" id="ballast_bonus_id" name="ballast_bonus" class="number" style="width:125px;" /></div></td>
				<td><div class="dp" id='div_miscellaneous_id'><input type="text" id="miscellaneous_id" name="miscellaneous" class="number" style="width:125px;" /></div></td>
			  </tr>
			</table>
			
			<div style="border-bottom:3px dotted #fff;">&nbsp;</div>
			<div>&nbsp;</div>
			<!-- END OF VOYAGE TIME -->
			
			<!-- AIS MAP -->
			<table width="1500" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td width="1500" bgcolor="cddee5"><div class="dp"><b>MAP - Data from AIS</b></div></td>
			  </tr>
			  <tr bgcolor="#000000">
				<td><iframe src='map/world_map.php' id="map_iframeve" width='1500' height='400' frameborder="0"></iframe></td>
			  </tr>
			</table>
			
			<div>&nbsp;</div>
			<!-- END OF AIS MAP -->
		</td>
		<td width="300">
			<!--<div style="position:fixed;">-->
				<table width="300" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td width="150" style="border:none;">
						<div style="padding-left:10px;">
							<table width="140" border="0" cellspacing="0" cellpadding="0">
								<tr bgcolor="cddee5">
									<td><div class="dp"><b>FREIGHT RATE</b></div></td>
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
									<td><div class="dp"><b>TCE</b></div></td>
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
			<!--</div>-->
		</td>
	</tr>
</table>
</form>