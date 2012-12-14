<?php
@include_once(dirname(__FILE__)."/includes/bootstrap.php");

if($_GET['autosave']){
	$_SESSION['data'] = $_POST['data'];

	exit();
}

if($_GET['dc']){
	$dc = new distanceCalc();
	$from = $_GET['from'];
	$to = $_GET['to'];

	echo $dc->getDistancePortToPort($from, $to);

	exit();
}

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
		
		$ship = array();

		$ship['name'] = $r[$i]['imo']." - ".$r[$i]['name'];
		$ship['mmsi'] = $r[$i]['mmsi'];
		$ship['imo'] = $r[$i]['imo'];
		$ship['dwt'] = $r[$i]['summer_dwt'];
		$ship['gross_tonnage'] = getValue($r2[0]['data'], 'GROSS_TONNAGE');
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
		$ship['manager_owner'] = getValue($r2[0]['data'], 'MANAGER_OWNER');
		$ship['manager_owner_email'] = getValue($r2[0]['data'], 'MANAGER_OWNER_EMAIL');
		$ship['class_society'] = htmlentities(getValue($r2[0]['data'], 'CLASS_SOCIETY'));
		$ship['holds'] = htmlentities(getValue($r2[0]['data'], 'HOLDS'));
		$ship['largest_hatch'] = htmlentities(getValue($r2[0]['data'], 'LARGEST_HATCH'));

		$ships[] = $ship;
	}

	echo json_encode($ships);

	exit();
}

if($_GET['sf']){
	$search = $_GET['term'];

	$sql = "select * from  ve_sf where cargo_name like '%".mysql_escape_string($search)."%' limit 20";

	$items = array();

	$r = dbQuery($sql);

	$t = count($r);

	for($i=0; $i<$t; $i++){
		$item = array();

		$item['cargo_name'] = $r[$i]['cargo_name']." - ".$r[$i]['sf'];

		$items[] = $item;
	}

	echo json_encode($items);

	exit();
}

if($_GET['wd']){
	$search = $_GET['term'];

	$sql = "select * from  ve_wd where working_day like '%' limit 20";

	$items = array();

	$r = dbQuery($sql);

	$t = count($r);

	for($i=0; $i<$t; $i++){
		$item = array();

		$item['working_day'] = $r[$i]['working_day'];

		$items[] = $item;
	}

	echo json_encode($items);

	exit();
}

if($_GET['port']){
	$search = $_GET['term'];

	$sql = "select * from _veson_ports where name like '%".mysql_escape_string($search)."%' limit 20";

	$items = array();

	$r = dbQuery($sql);

	$t = count($r);

	for($i=0; $i<$t; $i++){
		$item = array();

		//$item['name'] = $r[$i]['name']." - ".$r[$i]['portid'];
		$item['name'] = $r[$i]['name'];
		$item['latitude'] = $r[$i]['latitude'];
		$item['longitude'] = $r[$i]['longitude'];
		$item['portid'] = $r[$i]['portid'];

		$items[] = $item;
	}

	echo json_encode($items);

	exit();
}
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
	if(daystoadd==""){
		daystoadd = 0;
	}

	daystoadd = Math.ceil(daystoadd);

	if(date){
		date = date.split(",");
		date = date[0].split("/");
		date = date[1]+"/"+date[0]+"/"+date[2];

		try{
			thedate = new Date(date);
			thedate.setDate(thedate.getDate()+daystoadd);
			
			return dateFormat(thedate, "dd/mm/yyyy, dddd");
		}catch(e){

		}
	}
}

/***************************************************************************************************************************************************/

var suggestions = [];
var imos = [];
var dwts = [];
var gross_tonnages = [];
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
var sfs = [];
var gimo = "";

$(function(){
	jQuery( "#miscdialog" ).dialog( { autoOpen: false, width: 1100, height: 500 });
	jQuery( "#miscdialog" ).dialog("close");
	
	jQuery("#shipdetails").dialog( { autoOpen: false, width: '90%', height: jQuery(window).height()*0.9 });
	jQuery("#shipdetails").dialog("close");
	
	jQuery("#contactdialog").dialog( { autoOpen: false, width: 900, height: 460 });
	jQuery("#contactdialog").dialog("close");	

	//ballast
	$(".d31, .d32, .d33, .d34, .d35").datepicker({ 
		dateFormat: "dd/mm/yy, DD",
		onSelect: function(date) {
				jQuery(this).val(date);

            	calculateDates();
        	},
		});

	$(".c31").autocomplete({
		//define callback to format results
		source: function(req, add){
			//pass request to server
			$.getJSON("ajax_voyage_estimator.php?port=1", req, function(data) {
				//create array for response objects
				var suggestions = [];

				//process response
				$.each(data, function(i, val){		
					suggestions.push(val.name);
				});

				//pass array to callback
				add(suggestions);
			});
		},

		//define select handler
		select: function(e, ui) {
			str = ui.item.value;
			idx = jQuery(this).parent().parent().attr('id');

			setValue(jQuery("#"+idx+" .c31"), str);

			ballastCalc(true);

			calculateDates();
		},
	});

	$(".e31").autocomplete({
		//define callback to format results
		source: function(req, add){
			//pass request to server
			$.getJSON("ajax_voyage_estimator.php?port=1", req, function(data) {

				//create array for response objects
				var suggestions = [];

				//process response
				$.each(data, function(i, val){
					suggestions.push(val.name);
				});

				//pass array to callback
				add(suggestions);
			});
		},

		//define select handler
		select: function(e, ui) {
			str = ui.item.value;
			idx = jQuery(this).parent().parent().attr('id');

			setValue(jQuery("#"+idx+" .e31"), str);

			ballastCalc(true);

			calculateDates();
		},
	});

	//laden
	$(".c34").autocomplete({

		//define callback to format results
		source: function(req, add){
			//pass request to server
			$.getJSON("ajax_voyage_estimator.php?port=1", req, function(data) {

				//create array for response objects
				var suggestions = [];

				//process response
				$.each(data, function(i, val){
					suggestions.push(val.name);
				});

				//pass array to callback
				add(suggestions);
			});
		},

		//define select handler
		select: function(e, ui) {
			str = ui.item.value;
			idx = jQuery(this).parent().parent().attr('id');

			setValue(jQuery("#"+idx+" .c34"), str);

			ladenCalc(true);

			calculateDates();
		},
	});

	$(".e34").autocomplete({
		//define callback to format results
		source: function(req, add){

			//pass request to server
			$.getJSON("ajax_voyage_estimator.php?port=1", req, function(data) {

				//create array for response objects
				var suggestions = [];

				//process response
				$.each(data, function(i, val){
					suggestions.push(val.name);
				});

				//pass array to callback
				add(suggestions);
			});
		},

		//define select handler
		select: function(e, ui) {
			str = ui.item.value;
			idx = jQuery(this).parent().parent().attr('id');

			setValue(jQuery("#"+idx+" .e34"), str);

			ladenCalc(true);

			calculateDates();
		},
	});

	//bunkerstop
	$(".c33").autocomplete({
		//define callback to format results
		source: function(req, add){

			//pass request to server
			$.getJSON("ajax_voyage_estimator.php?port=1", req, function(data) {

				//create array for response objects
				var suggestions = [];

				//process response
				$.each(data, function(i, val){
					suggestions.push(val.name);
				});

				//pass array to callback
				add(suggestions);
			});
		},

		//define select handler
		select: function(e, ui) {
			str = ui.item.value;
			idx = jQuery(this).parent().parent().attr('id');

			setValue(jQuery("#"+idx+" .c33"), str);

			bunkerstopCalc2(true);

			calculateDates();
		},
	});

	$(".e33").autocomplete({
		//define callback to format results
		source: function(req, add){
			//pass request to server
			$.getJSON("ajax_voyage_estimator.php?port=1", req, function(data) {
				//create array for response objects
				var suggestions = [];

				//process response
				$.each(data, function(i, val){								
					suggestions.push(val.name);
				});

				//pass array to callback
				add(suggestions);
			});
		},

		//define select handler
		select: function(e, ui) {
			str = ui.item.value;
			idx = jQuery(this).parent().parent().attr('id');

			setValue(jQuery("#"+idx+" .e33"), str);

			bunkerstopCalc2(true);

			calculateDates();
		},
	});

	//loading, Bunker Stop, discharging
	$(".c32, .e32, .c35, .e35").autocomplete({
		//define callback to format results
		source: function(req, add){
			//pass request to server
			$.getJSON("ajax_voyage_estimator.php?port=1", req, function(data) {

				//create array for response objects
				var suggestions = [];

				//process response
				$.each(data, function(i, val){
					suggestions.push(val.name);
				});

				//pass array to callback
				add(suggestions);
			});
		},

		//define select handler
		select: function(e, ui) {
			calculateDates();
		},
	});

	$(".i35").autocomplete({

		//define callback to format results
		source: function(req, add){

			//pass request to server
			$.getJSON("ajax_voyage_estimator.php?sf=1", req, function(data) {

				//create array for response objects
				var suggestions = [];
				var sfs = [];

				//process response
				$.each(data, function(i, val){
					suggestions.push(val.cargo_name);

					sfs[val.cargo_name] = val.sf;
				});

				//pass array to callback
				add(suggestions);
			});
		},

		//define select handler
		select: function(e, ui) {
			str = ui.item.value;
			pcs = str.split("-");
			cargo = pcs[0];
			cargo = jQuery.trim(cargo);
			sf = pcs[1];
			sf = jQuery.trim(sf);
			idx = jQuery(this).parent().parent().attr('id');

			if(sf){
				setValue(jQuery("#"+idx+" .j35"), fNum(sf));
			}

			thread("sf");
		},
	});

	$(".i32").autocomplete({
		//define callback to format results
		source: function(req, add){
			//pass request to server
			$.getJSON("ajax_voyage_estimator.php?sf=1", req, function(data) {
				//create array for response objects
				var suggestions = [];
				var sfs = [];

				//process response
				$.each(data, function(i, val){
					suggestions.push(val.cargo_name);
					sfs[val.cargo_name] = val.sf;
				});

				//pass array to callback
				add(suggestions);
			});
		},

		//define select handler
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
			}

			thread("sf");
		},
	});

	$("#ship").autocomplete({
		//define callback to format results

		source: function(req, add){
			jQuery("#shipdetailshref").html("");

			//pass request to server
			$.getJSON("ajax_voyage_estimator.php?search=1", req, function(data) {
				//create array for response objects
				var suggestions = [];
				var imos = [];

				//process response
				$.each(data, function(i, val){
					suggestions.push(val.name);

					imos.push(val.imo);

					dwts[val.imo] = val.dwt;
					gross_tonnages[val.imo] = val.gross_tonnage;
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
				});

				//pass array to callback
				add(suggestions);
			});
		},

		//define select handler
		select: function(e, ui) {
			str = ui.item.value;
			pcs = str.split("-");
			imo = pcs[0];
			imo = jQuery.trim(imo);
			gimo = imo;

			jQuery("#shipdetailshref").html("<a style='cursor:pointer;' onclick='showShipDetails()'><u>Click here for full specs</u></a>");
			setValue(jQuery("#d18"), fNum(dwts[imo]));
			
			//Zoi's Code
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
				setValue(jQuery(this), fNum(fuel_consumptions[imo]) + ' kts');
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
			//End of Zoi's Code

			jQuery(".g31").each(function(){
				setValue(jQuery(this), fNum(speeds[imo]));
			});

			jQuery(".g33").each(function(){
				setValue(jQuery(this), fNum(speeds[imo]));
			});

			jQuery(".g34").each(function(){
				setValue(jQuery(this), fNum(speeds[imo]));
			});
			
			iframeve = document.getElementById('map_iframeve');
  			iframeve.src = "map/map_voyage_estimator.php?imo="+imo;
			
			thread();
		},
	});
});

function showShipDetails(imo){
	jQuery("#shipdetails").dialog("close")
	jQuery('#pleasewait').show();

	jQuery.ajax({
		type: 'POST',
		url: "search_ajax1ve.php?imo="+gimo,
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

	if(num==0){
		return "";
	}

	num = num.toFixed(2);

	return addCommas(num);
}

function uNum(num){
	if(!num){
		num = 0;
	}else if(isNaN(num)){
		num = num.replace(/[^0-9\.]/g, "");

		if(isNaN(num)){
			num = 0;
		}
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

	for(i=num1; i<=num2; i++){
		sum += valueU(jQuery("#"+alpha+i));
	}

	return fNum(sum);
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
					speed = 13; //default speed is 13knots
					
					setValue(jQuery(tmp+".g31"), fNum(speed));
				}

				//seadays

				//jQuery(tmp+".h31") is distance

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

			speed = 13; //default speed is 13knots

			setValue(jQuery(tmp+".g31"), fNum(speed));

		}

		//seadays

		//jQuery(tmp+".h31") is distance

		sea = ( distance / valueU(jQuery(tmp+".g31")) / 24);

		setValue(

			jQuery(tmp+".r31"), 

			fNum(sea)

		);

		calculateSeaPortDays();

		calculateDates();

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

					speed = 13; //default speed is 13knots

					setValue(jQuery(tmp+".g33"), fNum(speed));

				}

				

				//seadays

				//jQuery(tmp+".h31") is distance

				sea = ( distance / valueU(jQuery(tmp+".g33")) / 24);

				setValue(

					jQuery(tmp+".r33"), 

					fNum(sea)

				);

				calculateSeaPortDays();

				calculateDates();

				

			}

		});

	}

	else{

		distance = valueU(jQuery(tmp+".h33"));

		speed = valueU(jQuery(tmp+".g33"));

		

		if(speed == 0){

			speed = 13; //default speed is 13knots

			setValue(jQuery(tmp+".g33"), fNum(speed));

		}

		

		//seadays

		//jQuery(tmp+".h31") is distance

		sea = ( distance / valueU(jQuery(tmp+".g33")) / 24);

		setValue(

			jQuery(tmp+".r33"), 

			fNum(sea)

		);

		calculateSeaPortDays();

		calculateDates();

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



	//alert("triggerajax = "+triggerajax);



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

					speed = 13; //default speed is 13knots

					setValue(jQuery(tmp+".g34"), fNum(speed));

				}

				

				//seadays

				//jQuery(tmp+".h31") is distance

				sea = ( distance / valueU(jQuery(tmp+".g34")) / 24);

				setValue(

					jQuery(tmp+".r34"), 

					fNum(sea)

				);

				calculateSeaPortDays();

				calculateDates();

				

			}

		});

	}

	else{

		distance = valueU(jQuery(tmp+".h34"));

		speed = valueU(jQuery(tmp+".g34"));

		

		if(speed == 0){

			speed = 13; //default speed is 13knots

			setValue(jQuery(tmp+".g34"), fNum(speed));

		}

		

		//seadays

		//jQuery(tmp+".h31") is distance

		sea = ( distance / valueU(jQuery(tmp+".g34")) / 24);

		setValue(

			jQuery(tmp+".r34"), 

			fNum(sea)

		);

		calculateSeaPortDays();

		calculateDates();

	}

}



function ballastCalc(triggerajax){

	n = 1;

	while(jQuery("#ballast"+n)[0]){



		tmp = "#ballast"+n+" ";

		//distance calc

		str = getValue(jQuery(tmp+".c31"));

		if(str){

			pcs = str.split("-");

			from = pcs[pcs.length-1];

			from = jQuery.trim(from);



			str = getValue(jQuery(tmp+".e31")); 

			pcs = str.split("-");

			to = pcs[pcs.length-1];

			to = jQuery.trim(to);



			if(from&&to){



				ballastDistCalc(tmp, to, from, triggerajax);	

			}

		}

		n++;

	}

}



function bunkerstopCalc2(triggerajax){

	n = 1;

	while(jQuery("#bunkerstop"+n)[0]){



		tmp = "#bunkerstop"+n+" ";

		//distance calc

		str = getValue(jQuery(tmp+".c33"));

		

		if(str){

			pcs = str.split("-");

			from = pcs[pcs.length-1];

			from = jQuery.trim(from);



			str = getValue(jQuery(tmp+".e33")); 

			pcs = str.split("-");

			to = pcs[pcs.length-1];

			to = jQuery.trim(to);



			if(from&&to){



				bunkerstopDistCalc(tmp, to, from, triggerajax);	

			}

		}

		n++;

	}

}



function ladenCalc(triggerajax){

	n = 1;

	while(jQuery("#laden"+n)[0]){

		

		tmp = "#laden"+n+" ";

		//distance calc

		str = getValue(jQuery(tmp+".c34"));

		if(str){

			pcs = str.split("-");

			from = pcs[pcs.length-1];

			from = jQuery.trim(from);



			str = getValue(jQuery(tmp+".e34")); 

			pcs = str.split("-");

			to = pcs[pcs.length-1];

			to = jQuery.trim(to);



			if(from&&to){



				ladenDistCalc(tmp, to, from, triggerajax);	

			}

		}

		n++;

	}



}



function bunkerstopCalc(){

	n = 1;

	seadays = 0;

	portdays = 0;

	while(jQuery("#bunkerstop"+n)[0]){

		tmp = "#bunkerstop"+n+" ";



		

		//calculate ld

		ld = 0;

		ld = valueU(jQuery(tmp+".l33")) / valueU(jQuery(tmp+".m33")) / 24;

		setValue(jQuery(tmp+".o33"), fNum(ld));

		

		//seadays

		seadays += ( valueU(jQuery(tmp+".s33")) + valueU(jQuery(tmp+".t33")) );

		

		//portdays

		portdays += ( ld + valueU(jQuery(tmp+".p33")) + valueU(jQuery(tmp+".q33")) );

		n++;

	}



	bunkerstopCalc2();





}



function loadingCalc(){

	n = 1;

	seadays = 0;

	portdays = 0;

	while(jQuery("#loading"+n)[0]){

		tmp = "#loading"+n+" ";

		

		//calculate volume

		volume = valueU(jQuery(tmp+".k32")) * valueU(jQuery(tmp+".j32"));

		setValue(jQuery(tmp+".l32"), fNum(volume));

		

		//calculate ld

		ld = 0;

		ld = valueU(jQuery(tmp+".k32")) / valueU(jQuery(tmp+".m32"));

		setValue(jQuery(tmp+".o32"), fNum(ld));

		

		//seadays

		seadays += ( valueU(jQuery(tmp+".s32")) + valueU(jQuery(tmp+".t32")) );

		

		//portdays

		portdays += ( ld + valueU(jQuery(tmp+".p32")) + valueU(jQuery(tmp+".q32")) );

		n++;

	}

	





}



function dischargingCalc(){

	n = 1;

	while(jQuery("#discharging"+n)[0]){

		tmp = "#discharging"+n+" ";

		

		//calculate volume

		volume = valueU(jQuery(tmp+".k35")) * valueU(jQuery(tmp+".j35"));

		setValue(jQuery(tmp+".l35"), fNum(volume));

		

		//calculate ld

		ld = 0;

		ld = valueU(jQuery(tmp+".k35")) / valueU(jQuery(tmp+".m35"));

		setValue(jQuery(tmp+".o35"), fNum(ld));

		

		n++;

	}

	



}



function sumClass(clas){

	sum = 0;

	jQuery("."+clas).each(function(){

		sum += valueU(jQuery(this));

	});

	return sum;

}



function calculatePortDays(){

	sum = 0;



	sum += sumClass("o31");

	sum += sumClass("o32");

	sum += sumClass("o33");

	sum += sumClass("o34");

	sum += sumClass("o35");



	sum += sumClass("p31");

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







function calculateSeaDays(){

	sum = 0;



	sum += sumClass("r31");

	sum += sumClass("r32");

	sum += sumClass("r33");

	sum += sumClass("r34");

	sum += sumClass("r35");



	sum += sumClass("s31");

	sum += sumClass("s32");

	sum += sumClass("s33");

	sum += sumClass("s34");

	sum += sumClass("s35");




	sum += sumClass("t31");

	sum += sumClass("t32");

	sum += sumClass("t33");

	sum += sumClass("t34");

	sum += sumClass("t35");



	return sum;

}



function calculateSeaPortDays(){

	totalportdays = calculatePortDays();

	totalseadays = calculateSeaDays();



	setValue(jQuery("#r36"), fNum(totalseadays));

	setValue(jQuery("#o36"), fNum(totalportdays));

	setValue(jQuery("#o37"), fNum(totalseadays+totalportdays));

}



function calculateDates(){

	//ballast

	n = 1;

	while(jQuery("#ballast"+n)[0]){



		tmp = "#ballast"+n+" ";

		//initial date

		date = getValue(jQuery(tmp+".d31"));



		//port days and sea days

		days = valueU(jQuery(tmp+".o31")) + valueU(jQuery(tmp+".p31")) + valueU(jQuery(tmp+".q31")) + valueU(jQuery(tmp+".r31")) + valueU(jQuery(tmp+".s31")) + valueU(jQuery(tmp+".t31"));



		adate = addDays(date, days);

		setValue(jQuery(tmp+".f31"), adate);



		n++;

	}



	c45 = uNum(getValue(jQuery("#c44")))*days;

	c46 = c45*uNum(getValue(jQuery("#d42")));

	setValue(jQuery("#c45"), fNum(c45));

	setValue(jQuery("#c46"), fNum(c46));





	//loading

	n = 1;

	while(jQuery("#loading"+n)[0]){

		

		num = 32;

		

		tmp = "#ballast"+n+" ";

		date = getValue(jQuery(tmp+".f"+(num-1)));

		portto = getValue(jQuery(tmp+".e"+(num-1)));



		tmp = "#loading"+n+" ";

		

		//ports

		setValue(jQuery(tmp+".c"+num), portto);

		setValue(jQuery(tmp+".e"+num), portto);



		//dates

		setValue(jQuery(tmp+".d"+num), date);

		date = getValue(jQuery(tmp+".d"+num));

		//port days and sea days

		days = valueU(jQuery(tmp+".o"+num)) + valueU(jQuery(tmp+".p"+num)) + valueU(jQuery(tmp+".q"+num)) + valueU(jQuery(tmp+".r"+num)) + valueU(jQuery(tmp+".s"+num)) + valueU(jQuery(tmp+".t"+num));

		adate = addDays(date, days);

		setValue(jQuery(tmp+".f"+num), adate);

		

		n++;

	}



	//bunkerstop

	n = 1;

	while(jQuery("#bunkerstop"+n)[0]){

		

		num = 33;

		

		tmp = "#loading"+n+" ";

		date = getValue(jQuery(tmp+".f"+(num-1)));

		portto = getValue(jQuery(tmp+".e"+(num-1)));



		tmp = "#bunkerstop"+n+" ";

		

		//ports

		setValue(jQuery(tmp+".c"+num), portto);



		//dates

		setValue(jQuery(tmp+".d"+num), date);

		date = getValue(jQuery(tmp+".d"+num));

		//port days and sea days

		days = valueU(jQuery(tmp+".o"+num)) + valueU(jQuery(tmp+".p"+num)) + valueU(jQuery(tmp+".q"+num)) + valueU(jQuery(tmp+".r"+num)) + valueU(jQuery(tmp+".s"+num)) + valueU(jQuery(tmp+".t"+num));

		adate = addDays(date, days);

		setValue(jQuery(tmp+".f"+num), adate);

		

		n++;

	}



	//laden

	n = 1;

	while(jQuery("#laden"+n)[0]){

		

		num = 34;

		

		tmp = "#bunkerstop"+n+" ";

		date = getValue(jQuery(tmp+".f"+(num-1)));

		portto = getValue(jQuery(tmp+".e"+(num-1)));



		tmp = "#laden"+n+" ";

		

		//ports

		setValue(jQuery(tmp+".c"+num), portto);



		//dates

		setValue(jQuery(tmp+".d"+num), date);

		date = getValue(jQuery(tmp+".d"+num));

		//port days and sea days

		days = valueU(jQuery(tmp+".o"+num)) + valueU(jQuery(tmp+".p"+num)) + valueU(jQuery(tmp+".q"+num)) + valueU(jQuery(tmp+".r"+num)) + valueU(jQuery(tmp+".s"+num)) + valueU(jQuery(tmp+".t"+num));

		adate = addDays(date, days);

		setValue(jQuery(tmp+".f"+num), adate);

		

		n++;

	}



	d45 = uNum(getValue(jQuery("#d44")))*days;

	d46 = d45*uNum(getValue(jQuery("#d42")));

	setValue(jQuery("#d45"), fNum(d45));

	setValue(jQuery("#d46"), fNum(d46));



	//discharging

	n = 1;

	while(jQuery("#discharging"+n)[0]){

		

		num = 35;

		

		tmp = "#laden"+n+" ";

		date = getValue(jQuery(tmp+".f"+(num-1)));

		portto = getValue(jQuery(tmp+".e"+(num-1)));



		tmp = "#discharging"+n+" ";

		

		//ports

		setValue(jQuery(tmp+".c"+num), portto);

		setValue(jQuery(tmp+".e"+num), portto);



		//dates

		setValue(jQuery(tmp+".d"+num), date);

		date = getValue(jQuery(tmp+".d"+num));

		//port days and sea days

		days = valueU(jQuery(tmp+".o"+num)) + valueU(jQuery(tmp+".p"+num)) + valueU(jQuery(tmp+".q"+num)) + valueU(jQuery(tmp+".r"+num)) + valueU(jQuery(tmp+".s"+num)) + valueU(jQuery(tmp+".t"+num));

		adate = addDays(date, days);

		setValue(jQuery(tmp+".f"+num), adate);

		

		n++;

	}



	portdays = calculatePortDays();

	e45 = uNum(getValue(jQuery("#e44")))*portdays;

	e46 = e45*uNum(getValue(jQuery("#d42")));

	setValue(jQuery("#e45"), fNum(e45));

	setValue(jQuery("#e46"), fNum(e46));



	f45 = uNum(getValue(jQuery("#f45")));

	f46 = f45*uNum(getValue(jQuery("#d42")));

	setValue(jQuery("#f46"), fNum(f46));



	c47 = c46+d46+e46+f46;

	setValue(jQuery("#c47"), fNum(c47));



	d19b = c45+d45+e45;

	setValue(jQuery("#d19b"), fNum(d19b));



	d21b = f45;

	setValue(jQuery("#d21b"), fNum(d21b));



	seadays = calculateSeaDays();

	g45 = uNum(getValue(jQuery("#g44")))*seadays;

	setValue(jQuery("#g45"), fNum(g45));

	g46 = g45 * uNum(getValue(jQuery("#h42")));

	setValue(jQuery("#g46"), fNum(g46));



	h45 = uNum(getValue(jQuery("#h44")))*(portdays+uNum(getValue(jQuery("#s34"))));

	setValue(jQuery("#h45"), fNum(h45));

	h46 = h45 * uNum(getValue(jQuery("#h42")));



	setValue(jQuery("#h46"), fNum(h46));



	i46 = uNum(getValue(jQuery("#h42")))*uNum(getValue(jQuery("#i45")));

	setValue(jQuery("#i46"), fNum(i46));



	g47 = g46+h46+i46;

	setValue(jQuery("#g47"), fNum(g47));	



	d20b = g45+h45;

	setValue(jQuery("#d20b"), fNum(d20b));



	d22b = uNum(getValue(jQuery("#i45")));

	setValue(jQuery("#d22b"), fNum(d22b));	

	

	//b74

	b74 = c47 + g47

	setValue(jQuery("#b74"), fNum(b74));

	

	

}



function setupPortInterface(){

	setValue(jQuery("#port1"), getValue(jQuery(".e31")));

	setValue(jQuery("#port2"), getValue(jQuery(".e33")));

	setValue(jQuery("#port3"), getValue(jQuery(".e34")));

	

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
	c51 = uNum(getValue(jQuery("#c51")));
	c52 = uNum(getValue(jQuery("#c52")));
	c54 = uNum(getValue(jQuery("#c54")));

	num = (o32 + o33 + o35 - c51) / 24;

	if(num<0){
		despatch = -1 * num * c54;

		demurrage = 0;
	}else{
		despatch = 0;

		demurrage = num * c52;
	}
	
	setValue(jQuery("#c66"), fNum(demurrage));
	setValue(jQuery("#c67"), fNum(despatch));

	sum = port1+port2+port3;

	c67 = 0;

	c68 = sum - c67;

	setValue(jQuery("#c68"), fNum(c68));

	total = sum+demurrage-despatch;

	setValue(jQuery("#c68"), fNum(total));

	c54 = c52 / 2;

	setValue(jQuery("#c54"), fNum(c54));
	setValue(jQuery("#c74"), fNum(c68));
}

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

function result1(){
	c80 = uNum(getValue(jQuery(".k32"))) * uNum(getValue(jQuery("#b80")))
	setValue(jQuery("#c80"), fNum(c80));

	d81 = (uNum(getValue(jQuery("#d80"))) + uNum(getValue(jQuery("#e80")))) / 100 * uNum(getValue(jQuery("#c80")));
	setValue(jQuery("#d81"), fNum(d81));

	f80 = uNum(getValue(jQuery("#c80"))) - uNum(getValue(jQuery("#d81"))) - uNum(getValue(jQuery("#b75")));
	setValue(jQuery("#f80"), fNum(f80));

	g80 = uNum(getValue(jQuery("#f80"))) / uNum(getValue(jQuery("#o37")));
	setValue(jQuery("#g80"), fNum(g80)); 
}

function result2(){
	//=G85*O37

	f85 = uNum(getValue(jQuery("#g85"))) * uNum(getValue(jQuery("#o37")));

	setValue(jQuery("#f85"), fNum(f85));

	

	//=(F85+B75)/(100-D85-E85)*100

	c85 = (uNum(getValue(jQuery("#f85"))) + uNum(getValue(jQuery("#b75"))) ) / (100 - uNum(getValue(jQuery("#d85"))) - uNum(getValue(jQuery("#e85")))) * 100;

	setValue(jQuery("#c85"), fNum(c85));

	b85 = uNum(getValue(jQuery("#c85"))) / uNum(getValue(jQuery(".k32")));

	setValue(jQuery("#b85"), fNum(b85));

	//=(D85+E85)/100*C85

	d86 = ( uNum(getValue(jQuery("#d85"))) + uNum(getValue(jQuery("#e85"))) ) / 100 * uNum(getValue(jQuery("#c85")));

	setValue(jQuery("#d86"), fNum(d86));
}

var totalseadays = 0;

var totalportdays = 0;

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
	}

	loadingCalc();
	dischargingCalc();
	bunkerstopCalc();

	//sf

	if(skip!="sf"){

		jQuery(".i32").each(function(){

			str = jQuery(this).val();

			pcs = str.split("-");

			cargo = pcs[0];

			cargo = jQuery.trim(cargo);

			sf = pcs[1];

			sf = jQuery.trim(sf);

			idx = jQuery(this).parent().parent().attr('id');

			if(sf){

				setValue(jQuery("#"+idx+" .j32"), fNum(sf));

			}

		});

		jQuery(".i35").each(function(){

			str = jQuery(this).val();

			pcs = str.split("-");

			cargo = pcs[0];

			cargo = jQuery.trim(cargo);

			sf = pcs[1];

			sf = jQuery.trim(sf);

			idx = jQuery(this).parent().parent().attr('id');

			if(sf){

				setValue(jQuery("#"+idx+" .j35"), fNum(sf));

			}

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

function autoSave(){

	str = "";

	jQuery('input[type="text"]').each(function(){

		str+=jQuery(this).val()+"\n";

	});

	jQuery.ajax({

		type: 'POST',

		url: "ajax_voyage_estimator.php?autosave=1",

		data:  'data='+str,

		

		success: function(data) {

		}

	});	

	setTimeout(function(){ autoSave(); }, 6000*10);

}

setTimeout(function(){ autoSave(); }, 1000*10);

jQuery(function(){
	jQuery('.number').keyup(function(){
		thread();
	});

	jQuery('.number').blur(function(){

		fnum = fNum(jQuery(this).val());

		setValue(jQuery(this), fnum);

		w = jQuery(this).val().length * 8;

		if(w > jQuery(this).parent().width()){

			//jQuery(this).width(w);

		}
	});

	jQuery('.general').blur(function(){

		w = jQuery(this).val().length * 8;

		if(w > jQuery(this).parent().width()){

			//jQuery(this).width(w);

		}

		thread();

		

	});

	jQuery('.number').each(function(){

		fnum = valueF(jQuery(this));

		setValue(jQuery(this), fnum);

	});

	<?php
	if($_SESSION['data']){

		$data = explode("\n", $_SESSION['data']);

		$t = count($data);

		for($i=0; $i<($t-1); $i++){

			/*

			?>jQuery('input[type="text"]')[<?php echo $i; ?>].value = "<?php echo htmlentities($data[$i]); ?>";<?php

			echo "\n";

			*/

		}

	}
	?>

	jQuery('.number').each(function(){
		fnum = fNum(jQuery(this).val());

		setValue(jQuery(this), fnum);

		w = jQuery(this).val().length * 8;

		if(w > jQuery(this).parent().width()){
			jQuery(this).width(w);
		}
	});

	jQuery('.general').each(function(){
		w = jQuery(this).val().length * 8;

		if(w > jQuery(this).parent().width()){
			jQuery(this).width(w);
		}

	});

	thread();
});

function saveScenario(){
	if(jQuery('#ship').val()){
		jQuery('#pleasewait').show();
	
		jQuery.ajax({
			type: "POST",
			url: "ajax.php?new_search=2",
			data: jQuery("#voyageestimatorform").serialize(),
	
			success: function(data) {
				alert("Scenario Saved!");
			
				self.location = "cargospotter.php";
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
			
				self.location = "cargospotter.php";
			}
		});
	}
}

function newScenario(){
	jQuery('#pleasewait').show();
	
	self.location = "cargospotter.php?new_search=3";
}

function mailItVe(){
	jQuery("#misciframe")[0].src="misc/email_ve.php";
	jQuery("#miscdialog").dialog("open");
}

function printItVe(){
	jQuery("#misciframe")[0].src="misc/print_ve.php";
	jQuery("#miscdialog").dialog("open");
}
</script>

<div id="miscdialog" title=""  style='display:none'>
	<iframe id='misciframe' frameborder='0' height="100%" width="1100px" style='border:0px; height:100%; width:1050px;'></iframe>
</div>

<div id="shipdetails" title="SHIP DETAILS" style='display:none;'>
	<div id='shipdetails_in'></div>
</div>

<div id="contactdialog" title="CONTACT"  style='display:none'>
	<iframe id='contactiframe' frameborder="0" height="100%" width="100%"></iframe>
</div>

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
		$c31 = $tabdata['c31'];
		$d31 = $tabdata['d31'];
		$e31 = $tabdata['e31'];
		$g31 = $tabdata['g31'];
		$e33 = $tabdata['e33'];
		$g33 = $tabdata['g33'];
		$e34 = $tabdata['e34'];
		$g34 = $tabdata['g34'];
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
		$d42 = $tabdata['d42'];
		$h42 = $tabdata['h42'];
		$c44 = $tabdata['c44'];
		$d44 = $tabdata['d44'];
		$e44 = $tabdata['e44'];
		$g44 = $tabdata['g44'];
		$h44 = $tabdata['h44'];
		$f45 = $tabdata['f45'];
		$i45 = $tabdata['i45'];
		$d19 = $tabdata['d19'];
		$d20 = $tabdata['d20'];
		$d21 = $tabdata['d21'];
		$d22 = $tabdata['d22'];
		$d23 = $tabdata['d23'];
		$d24 = $tabdata['d24'];
		$c51 = $tabdata['c51'];
		$c52 = $tabdata['c52'];
		$term = $tabdata['term'];
		$linerterms = $tabdata['linerterms'];
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
		$canal = $tabdata['canal'];
		$cbook1 = $tabdata['cbook1'];
		$cbook2 = $tabdata['cbook2'];
		$ctug1 = $tabdata['ctug1'];
		$ctug2 = $tabdata['ctug2'];
		$cline1 = $tabdata['cline1'];
		$cline2 = $tabdata['cline2'];
		$cmisc1 = $tabdata['cmisc1'];
		$cmisc2 = $tabdata['cmisc2'];
		$e74 = $tabdata['e74'];
		$f74 = $tabdata['f74'];
		$g74 = $tabdata['g74'];
		$h74 = $tabdata['h74'];
		$i74 = $tabdata['i74'];
		$j74 = $tabdata['j74'];
		$b80 = $tabdata['b80'];
		$d80 = $tabdata['d80'];
		$e80 = $tabdata['e80'];
		$d85 = $tabdata['d85'];
		$e85 = $tabdata['e85'];
		$g85 = $tabdata['g85'];
	}
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
						echo '<div onclick="location.href=\'cargospotter.php?new_search=3&tabid='.$r[$i]['id'].'\'" class="clickable" style="float:left; width:auto; height:auto; color:#FFF;">'.$r[$i]['tabname'].'</div>';
						echo '</div>';
					}
				}else{
					if($i==0){
						if(isset($_GET['new_search'])){
							if($_GET['new_search']==3){
								echo '<div style="float:left; width:auto; height:auto; background-color:#666; color:#FFF; padding:5px 10px; border:1px solid #000;">';
								echo '<div style="float:left; width:15px; height:auto;"><img src="images/close.png" width="14" height="14" border="0" alt="Delete this scenario" title="Delete this scenario" style="cursor:pointer;" onclick="deleteScenario(\''.$r[$i]['id'].'\');" /></div>';
								echo '<div onclick="location.href=\'cargospotter.php?new_search=3&tabid='.$r[$i]['id'].'\'" class="clickable" style="float:left; width:auto; height:auto; color:#FFF;">'.$r[$i]['tabname'].'</div>';
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
						echo '<div onclick="location.href=\'cargospotter.php?new_search=3&tabid='.$r[$i]['id'].'\'" class="clickable" style="float:left; width:auto; height:auto; color:#FFF;">'.$r[$i]['tabname'].'</div>';
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
		  <tr bgcolor="cddee5">
			<td class="text_1"><div style="padding:3px;"><b>VESSEL NAME / IMO #</b> &nbsp; <input type="hidden" id="tabid" name="tabid" value="<?php echo $tabid; ?>" /><input type="text" id="ship" name="ship" class="input_1" style="max-width:300px; width:300px;" value="<?php echo $ship; ?>" /> &nbsp; <span id='shipdetailshref' style="color:#F00;"></span></div></td>
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
				<td valign="top"><div style="padding:3px;"><b>Speed</b></div></td>
				<td valign="top" style="padding:3px;" id="ship_speed"></td>
				<td valign="top"><div style="padding:3px;"><b>Cargo Handling</b></div></td>
				<td valign="top" style="padding:3px;" id="ship_cargo_handling"></td>
				<td valign="top"><div style="padding:3px;"><b>Fuel</b></div></td>
				<td valign="top" style="padding:3px;" id="ship_fuel"></td>
			  </tr>
			  <tr bgcolor="e9e9e9">
				<td valign="top"><div style="padding:3px;"><b>Built Year</b></div></td>
				<td valign="top" style="padding:3px;" id="ship_built_year"></td>
				<td valign="top"><div style="padding:3px;"><b>Breadth</b></div></td>
				<td valign="top" style="padding:3px;" id="ship_breadth"></td>
				<td valign="top"><div style="padding:3px;"><b>Decks Number</b></div></td>
				<td valign="top" style="padding:3px;" id="ship_decks_number"></td>
				<td valign="top"><div style="padding:3px;"><b>Fuel Consumption</b></div></td>
				<td valign="top" style="padding:3px;" id="ship_fuel_consumption"></td>
			  </tr>
			  <tr bgcolor="f5f5f5">
				<td valign="top"><div style="padding:3px;"><b>Bale</b></div></td>
				<td valign="top" style="padding:3px;" id="ship_bale"></td>
				<td valign="top"><div style="padding:3px;"><b>Cranes</b></div></td>
				<td valign="top" style="padding:3px;" id="ship_cranes"></td>
				<td valign="top"><div style="padding:3px;"><b>Bulkheads</b></div></td>
				<td valign="top" style="padding:3px;" id="ship_bulkheads"></td>
				<td valign="top"><div style="padding:3px;"><b>Fuel Type</b></div></td>
				<td valign="top" style="padding:3px;" id="ship_fuel_type"></td>
			  </tr>
			  <tr bgcolor="e9e9e9">
				<td valign="top"><div style="padding:3px;"><b>Manager Owner</b></div></td>
				<td valign="top" style="padding:3px;" id="ship_manager_owner"></td>
				<td valign="top"><div style="padding:3px;"><b>Manager Owner Email</b></div></td>
				<td valign="top" style="padding:3px;" id="ship_manager_owner_email"></td>
				<td valign="top"><div style="padding:3px;"><b>Class Society</b></div></td>
				<td valign="top" style="padding:3px;" id="ship_class_society"></td>
				<td valign="top"><div style="padding:3px;"><b>Largest Hatch</b></div></td>
				<td valign="top" style="padding:3px;" id="ship_largest_hatch"></td>
			  </tr>
			  <tr bgcolor="f5f5f5">
				<td valign="top"><div style="padding:3px;"><b>Holds</b></div></td>
				<td valign="top" style="padding:3px;" id="ship_holds"></td>
				<td valign="top"><div style="padding:3px;"><b>Flag</b></div></td>
				<td valign="top" style="padding:3px;" id="ship_flag"></td>
				<td valign="top"><div style="padding:3px;"><b>&nbsp;</b></div></td>
				<td valign="top" style="padding:3px;"></td>
				<td valign="top"><div style="padding:3px;"><b>&nbsp;</b></div></td>
				<td valign="top" style="padding:3px;"></td>
			  </tr>
			</table>
		</div>
		
		<div style="border-bottom:3px dotted #fff;">&nbsp;</div>
		<div>&nbsp;</div>
		
		<table width="1000" border="0" cellspacing="0" cellpadding="0">
		  <tr bgcolor="cddee5">
			<td width="120" class="text_1"><div style="padding:3px;"><b>VOYAGE LEGS</b></div></td>
			<td width="200"></td>
			<td width="190"></td>
			<td width="100"></td>
			<td width="190"></td>
			<td width="100"></td>
			<td width="100"></td>
		  </tr>
		  <tr>
			<td class="text_1 label"><div style="padding:3px;"><i><strong>Type</strong></i></div></td>
			<td class="text_1 label"><div style="padding:3px;"><i><strong> Port</strong></i></div></td>
			<td class="text_1 label"><div style="padding:3px;"><i><strong>Date</strong></i></div></td>
			<td class="text_1 label"><div style="padding:3px;"><i><strong> Port</strong></i></div></td>
			<td class="text_1 label"><div style="padding:3px;"><i><strong>Date</strong></i></div></td>
			<td class="text_1 label"><div style="padding:3px;"><i><strong>Speed (knts)</strong></i></div></td>
			<td class="text_1 label"><div style="padding:3px;"><i><strong>Distance (miles)</strong></i></div></td>
		  </tr>
		  <tr id='ballast1' bgcolor="f5f5f5">
			<td class='general b31' style="padding:3px;"><strong>Ballast</strong></td>
			<td class='input'><div style="padding:3px;"><input type='text' class='input_1 general c31' id="c31" name="c31" value="<?php echo $c31; ?>" style="max-width:190px;" /></div></td>
			<td class="input"><div style="padding:3px;"><input type='text' class='input_1 general d31' name="d31" value="<?php echo $d31; ?>" style="max-width:170px;" /></div></td>
			<td class='input'><div style="padding:3px;"><input type='text' class='input_1 general e31' name="e31" value="<?php echo $e31; ?>" style="max-width:190px;" /></div></td>
			<td class='calculated general f31' style="padding:3px;"></td>
			<td class='input'><div style="padding:3px;"><input type='text' class='input_1 number g31' name="g31" value="<?php echo $g31; ?>" style="max-width:90px;" /></div></td>
			<td class="calculated number h31" style="padding:3px;"></td>
		  </tr>
		  <tr id='loading1' bgcolor="e9e9e9">
			<td class='general b32' style="padding:3px;"><strong>Loading</strong></td>
			<td class='general c32' style="padding:3px;"></td>
			<td class='general d32' style="padding:3px;"></td>
			<td class='general e32' style="padding:3px;"></td>
			<td class="calculated f32" style="padding:3px;"></td>
			<td class='number g32' style="padding:3px;"></td>
			<td class="number h32" style="padding:3px;"></td>
		  </tr>
		  <tr id='bunkerstop1' bgcolor="f5f5f5">
			<td class='general b33' style="padding:3px;"><strong>Bunker Stop</strong></td>
			<td class='input general c33' style="padding:3px;"></td>
			<td class='general d33' style="padding:3px;"></td>
			<td class='input' style="padding:3px;"><input type='text' class='input_1 general e33' name="e33" value="<?php echo $e33; ?>"  style="max-width:190px;" /></td>
			<td class="calculated f33" style="padding:3px;"></td>
			<td class='input' style="padding:3px;"><input type='text' class='input_1 number g33' name="g33" value="<?php echo $g33; ?>"  style="max-width:90px;" /></td>
			<td class="calculated h33" style="padding:3px;"></td>
		  </tr>
		  <tr id='laden1' bgcolor="e9e9e9">
			<td class='general b34' style="padding:3px;"><strong>Laden</strong></td>
			<td class='input general c34' style="padding:3px;"></td>
			<td class='general d34' style="padding:3px;"></td>
			<td class='input' style="padding:3px;"><input type='text' class='input_1 general e34' name="e34" value="<?php echo $e34; ?>" style="max-width:190px;" /></td>
			<td class="calculated f34" style="padding:3px;"></td>
			<td class='input' style="padding:3px;"><input type='text' class='input_1 number g34' name="g34" value="<?php echo $g34; ?>" style="max-width:90px;" /></td>
			<td class="calculated number h34" style="padding:3px;"></td>
		  </tr>
		  <tr id='discharging1' bgcolor="f5f5f5">
			<td class='general b35' style="padding:3px;"><strong>Discharging</strong></td>
			<td class='input general c35' style="padding:3px;"></td>
			<td class='general d35' style="padding:3px;"></td>
			<td class='general e35' style="padding:3px;"></td>
			<td class="calculated f35" style="padding:3px;"></td>
			<td class='number g35' style="padding:3px;"></td>
			<td class="number h35" style="padding:3px;"></td>
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
			<td width="167" class="text_1 label"><div style="padding:3px;"><i><strong>Working Days</strong></i></div></td>
			<td width="45" class="text_1 label"><div style="padding:3px;"><i><strong>L/D</strong></i></div></td>
			<td width="102" class="text_1 label"><div style="padding:3px;"><i><strong>Turn Time</strong></i></div></td>
			<td width="102" class="text_1 label"><div style="padding:3px;"><i><strong>Idle/Extra Days Sea</strong></i></div></td>
			<td width="7" class="text_1 label"><div style="padding:3px;"><i><strong>&nbsp;</strong></i></div></td>
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
			<td class="number o31" style="padding:3px;"></td>
			<td class='number p31' style="padding:3px;"></td>
			<td class='number q31' style="padding:3px;"></td>
			<td class="calculated number r31" style="padding:3px;"></td>
			<td class='empty' style="padding:3px;"><input type='text' class='input_1 number s31' name="s31" value="<?php echo $s31; ?>" style="max-width:50px;" /></td>
			<td class='empty' style="padding:3px;"><input type='text' class='input_1 number t31' name="t31" value="<?php echo $t31; ?>" style="max-width:50px;" /></td>
		  </tr>
		  <tr id='loading1' bgcolor="e9e9e9">
			<td class='general b32' style="padding:3px;"><strong>Loading</strong></td>
			<td class='input' style="padding:3px;"><input type='text' class='input_1 general i32' name="i32" value="<?php echo $i32; ?>" style="max-width:140px;" /></td>
			<td class='number j32' style="padding:3px;"></td>
			<td class='input' style="padding:3px;"><input type='text' class='input_1 number k32' name="k32" value="<?php echo $k32; ?>" style="max-width:70px;" /></td>
			<td class='calculated number l32' style="padding:3px;"></td>
			<td class='input' style="padding:3px;"><input type='text' class='input_1 number m32' name="m32" value="<?php echo $m32; ?>" style="max-width:70px;" /></td>
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
				<select class='input_1 general n32' name="n32" style="max-width:100px; min-width:100px;">
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
			<td class="calculated number o32" style="padding:3px;"></td>
			<td class='input' style="padding:3px;"><input type='text' class='input_1 number p32' name="p32" value="<?php echo $p32; ?>" style="max-width:70px;" /></td>
			<td class='input' style="padding:3px;"><input type='text' class='input_1 number q32' name="q32" value="<?php echo $q32; ?>" style="max-width:70px;" /></td>
			<td class="number r32" style="padding:3px;"></td>
			<td class='empty' style="padding:3px;"><input type='text' class='input_1 number s32' name="s32" value="<?php echo $s32; ?>" style="max-width:50px;" /></td>
			<td class='empty' style="padding:3px;"><input type='text' class='input_1 number t32' name="t32" value="<?php echo $t32; ?>" style="max-width:50px;" /></td>
		  </tr>
		  <tr id='bunkerstop1' bgcolor="f5f5f5">
			<td class='general b33' style="padding:3px;"><strong>Bunker Stop</strong></td>
			<td class='number i33' style="padding:3px;"></td>
			<td class='number j33' style="padding:3px;"></td>
			<td class='number k33' style="padding:3px;"></td>
			<td class='input' style="padding:3px;"><input type='text' class='input_1 number l33' name="l33" value="<?php echo $l33; ?>" style="max-width:70px;"  /></td>
			<td class='input' style="padding:3px;"><input type='text' class='input_1 number m33' name="m33" value="<?php echo $m33; ?>" style="max-width:70px;" /></td>
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
				<select class='input_1 general n33' name="n33" style="max-width:100px; min-width:100px;">
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
			<td class="calculated o33" style="padding:3px;"></td>
			<td class='input' style="padding:3px;"><input type='text' class='input_1 number p33' name="p33" value="<?php echo $p33; ?>" style="max-width:70px;" /></td>
			<td class='input' style="padding:3px;"><input type='text'  class='input_1 number q33' name="q33" value="<?php echo $q33; ?>" style="max-width:70px;"  /></td>
			<td class="calculated number r33" style="padding:3px;"></td>
			<td class='empty' style="padding:3px;"><input type='text'  class='input_1 number s33' name="s33" value="<?php echo $s33; ?>" style="max-width:50px;" /></td>
			<td class='empty' style="padding:3px;"><input type='text'  class='input_1 number t33' name="t33" value="<?php echo $t33; ?>" style="max-width:50px;" /></td>
		  </tr>
		  <tr id='laden1' bgcolor="e9e9e9">
			<td class='general b34' style="padding:3px;"><strong>Laden</strong></td>
			<td class='number i34' style="padding:3px;"></td>
			<td class='number j34' style="padding:3px;"></td>
			<td class='number k34' style="padding:3px;"></td>
			<td class='number l34' style="padding:3px;"></td>
			<td class='number m34' style="padding:3px;"></td>
			<td class='number n34' style="padding:3px;"></td>
			<td class="number o34" style="padding:3px;"></td>
			<td class='number p34' style="padding:3px;"></td>
			<td class='number q34' style="padding:3px;"></td>
			<td class="calculated number r34" style="padding:3px;"></td>
			<td class='empty' style="padding:3px;"><input type='text' class='input_1 number s34' name="s34" value="<?php echo $s34; ?>" style="max-width:50px;" /></td>
			<td class='empty' style="padding:3px;"><input type='text' class='input_1 number t34' name="t34" value="<?php echo $t34; ?>" style="max-width:50px;" /></td>
		  </tr>
		  <tr id='discharging1' bgcolor="f5f5f5">
			<td class='general b35' style="padding:3px;"><strong>Discharging</strong></td>
			<td class='input' style="padding:3px;"><input type='text' class='input_1 general i35' name="i35" value="<?php echo $i35; ?>" style="max-width:140px;" /></td>
			<td class='number j35' style="padding:3px;"></td>
			<td class='input' style="padding:3px;"><input type='text' class='input_1 number k35' name="k35" value="<?php echo $k35; ?>" style="max-width:70px;" /></td>
			<td class='calculated number l35' style="padding:3px;"></td>
			<td class='input' style="padding:3px;"><input type='text'  class='input_1 number m35' name="m35" value="<?php echo $m35; ?>" style="max-width:70px;" /></td>
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
				<select class='input_1 general n35' name="n35" style="max-width:100px; min-width:100px;">
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
			<td class="calculated number o35" style="padding:3px;"></td>
			<td class='input' style="padding:3px;"><input type='text' class='input_1 number p35' name="p35" value="<?php echo $p35; ?>" style="max-width:70px;" /></td>
			<td class='input' style="padding:3px;"><input type='text'  class='input_1 number q35' name="q35" value="<?php echo $q35; ?>" style="max-width:70px;" /></td>
			<td class="number r35" style="padding:3px;"></td>
			<td class='empty' style="padding:3px;"><input type='text' class='input_1 number s35' name="s35" value="<?php echo $s35; ?>" style="max-width:50px;" /></td>
			<td class='empty' style="padding:3px;"><input type='text'  class='input_1 number t35' name="t35" value="<?php echo $t35; ?>" style="max-width:50px;" /></td>
		  </tr>
		</table>
		
		<div style="border-bottom:3px dotted #fff;">&nbsp;</div>
		<div>&nbsp;</div>
		
		<table width="1000" border="0" cellspacing="0" cellpadding="0">
		  <tr bgcolor="cddee5">
			<td width="100" class="text_1"><div style="padding:3px;"><b>VOYAGE TIME</b></div></td>
			<td width="132" class="text_1"><div style="padding:3px;">&nbsp;</div></td>
			<td width="18" class="text_1"><div style="padding:3px;">&nbsp;</div></td>
			<td width="122" class="text_1"><div style="padding:3px;">&nbsp;</div></td>
			<td width="102" class="text_1"><div style="padding:3px;">&nbsp;</div></td>
			<td width="102" class="text_1"><div style="padding:3px;">&nbsp;</div></td>
			<td width="100" class="text_1"><div style="padding:3px;">&nbsp;</div></td>
			<td width="45" class="text_1"><div style="padding:3px;">&nbsp;</div></td>
			<td width="132" class="text_1"><div style="padding:3px;">&nbsp;</div></td>
			<td width="100" class="text_1"><div style="padding:3px;">&nbsp;</div></td>
			<td width="7" class="text_1"><div style="padding:3px;">&nbsp;</div></td>
			<td width="132" class="text_1"><div style="padding:3px;">&nbsp;</div></td>
			<td width="38" class="text_1"><div style="padding:3px;">&nbsp;</div></td>
		  </tr>
		  <tr>
			<td colspan="7" class="label" style="padding:3px;"><strong>PORT/SEA DAYS</strong></td>
			<td colspan="3" class="label calculated" id='o36' style="padding:3px;">&nbsp;</td>
			<td colspan="3" class="label calculated" id='r36' style="padding:3px;">&nbsp;</td>
		  </tr>
		  <tr>
			<td colspan="7" class="label" style="padding:3px;"><strong>TOTAL VOYAGE DAYS</strong></td>
			<td colspan="6" class="label calculated" id='o37' style="padding:3px;">&nbsp;</td>
		  </tr>
		</table>
		
		<div style="border-bottom:3px dotted #fff;">&nbsp;</div>
		<div>&nbsp;</div>
		
		<table width="1000" border="0" cellspacing="0" cellpadding="0">
		  <tr bgcolor="cddee5">
			<td class="text_1" colspan="8"><div style="padding:3px;"><b>BUNKER PRICING - Data from Bunkerworld</b></div></td>
		  </tr>
		  <tr bgcolor="f5f5f5">
			<td width="100" style="padding:3px;"><b>FO Type</b></td>
			<td width="450" colspan="3" style="padding:3px;"></td>
			<td width="200" style="padding:3px;"><b>DO Type</b></td>
			<td width="250" colspan="3" style="padding:3px;"></td>
		  </tr>
		  <tr bgcolor="e9e9e9">
			<td style="padding:3px;"><b>FO Price ($)</b></td>
			<td colspan="3" class="input" style="padding:3px;"><input type='text'  id='d42' name="d42" value="<?php echo $d42; ?>" class='input_1 number' style="max-width:150px;" /></td>
			<td style="padding:3px;"><b>DO Price ($)</b></td>
			<td colspan="3" class="input" style="padding:3px;"><input type='text'  id='h42' name="h42" value="<?php echo $h42; ?>" class='input_1 number' style="max-width:150px;" /></td>
		  </tr>
		  <tr>
			<td class="text_1 label" style="padding:3px;"><b><i>&nbsp;</i></b></td>
			<td class="text_1 label" style="padding:3px;"><b><i>FO/Ballast</i></b></td>
			<td class="text_1 label" style="padding:3px;"><b><i>FO/Laden</i></b></td>
			<td class="text_1 label" style="padding:3px;"><b><i>FO/Port</i></b></td>
			<td class="text_1 label" style="padding:3px;"><b><i>FO/Reserve</i></b></td>
			<td class="text_1 label" style="padding:3px;"><b><i>DO/Sea</i></b></td>
			<td class="text_1 label" style="padding:3px;"><b><i>DO/Port</i></b></td>
			<td class="text_1 label" style="padding:3px;" colspan="2"><b><i>DO/Reserve</i></b></td>
		  </tr>
		  <tr bgcolor="f5f5f5">
			<td style="padding:3px;"><b>Consumption (MT/day)</b></td>
			<td class='input' style="padding:3px;"><input type='text'  id='c44' name="c44" value="<?php echo $c44; ?>" class='input_1 number' style="max-width:100px;" /></td>
			<td class='input' style="padding:3px;"><input type='text'  id='d44' name="d44" value="<?php echo $d44; ?>" class='input_1 number' style="max-width:100px;" /></td>
			<td class='input' style="padding:3px;"><input type='text'  id='e44' name="e44" value="<?php echo $e44; ?>" class='input_1 number' style="max-width:100px;" /></td>
			<td class='input number' id='f44' style="padding:3px;"></td>
			<td class='input' style="padding:3px;"><input type='text'  id='g44' name="g44" value="<?php echo $g44; ?>" class='input_1 number' style="max-width:70px;" /></td>
			<td class='input' style="padding:3px;"><input type='text'  id='h44' name="h44" value="<?php echo $h44; ?>" class='input_1 number' style="max-width:70px;" /></td>
			<td class='general' id='i44' style="padding:3px;"></td>
		  </tr>
		  <tr>
			<td class="label" style="padding:3px;"><strong>Total Consumption (MT)</strong></td>
			<td class="label calculated" id='c45' style="padding:3px;"></td>
			<td class="label calculated" id='d45' style="padding:3px;"></td>
			<td class="label calculated" id='e45' style="padding:3px;"></td>
			<td class='label input' style="padding:3px;"><input type='text' id='f45' name="f45" value="<?php echo $f45; ?>" class='input_1 number' style="max-width:100px;" /></td>
			<td class="label calculated" id='g45' style="padding:3px;"></td>
			<td class="label calculated" id='h45' style="padding:3px;"></td>
			<td class='label input' style="padding:3px;"><input type='text' id='i45' name="i45" value="<?php echo $i45; ?>" class='input_1 number' style="max-width:70px;" /></td>
		  </tr>
		</table>
		
		<div style="border-bottom:3px dotted #fff;">&nbsp;</div>
		<div>&nbsp;</div>
		
		<table width="1000" border="0" cellspacing="0" cellpadding="0">
		  <tr bgcolor="cddee5">
			<td class="text_1" colspan="8"><div style="padding:3px;"><b>VOYAGE EXPENSES</b></div></td>
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
				  <tr bgcolor="f5f5f5">
					<td width="122" style="padding:3px;"><strong>Laytime (hrs)</strong></td>
					<td width="122" class='input' style="padding:3px;"><input type='text' id='c51' name="c51" value="<?php echo $c51; ?>" class='input_1 number' style="max-width:100px;" /></td>
					<td width="123" style="padding:3px;"></td>
					<td width="123" style="padding:3px;"></td>
				  </tr>
				  <tr bgcolor="e9e9e9">
					<td style="padding:3px;"><strong>Dem ($/day)</strong></td>
					<td class='input' style="padding:3px;"><input type='text' id='c52' name="c52" value="<?php echo $c52; ?>" class='input_1 number' style="max-width:100px;" /></td>
					<td style="padding:3px;"><strong>Pro rated</strong></td>
					<td style="padding:3px;"></td>
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
					<td style="padding:3px;"></td>
					<td style="padding:3px;"></td>
				  </tr>
				  <tr bgcolor="e9e9e9">
					<td style="padding:3px;"><strong>Des ($/day)</strong></td>
					<td class="calculated" id='c54' style="padding:3px;">&nbsp;</td>
					<td style="padding:3px;"></td>
					<td style="padding:3px;"></td>
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
					<td style="padding:3px;"></td>
					<td style="padding:3px;"></td>
				  </tr>
				  <tr bgcolor="e9e9e9">
					<td style="padding:3px;"><strong>Port</strong></td>
					<td class='port1' id='port1' style="padding:3px;"><strong>Port 1</strong></td>
					<td class='port2' id='port2' style="padding:3px;"><strong>Port 2</strong></td>
					<td class='port3' id='port3' style="padding:3px;"><strong>Port 3 </strong></td>
				  </tr>
				  <tr bgcolor="f5f5f5">
					<td style="padding:3px;"><strong>Dues ($)</strong></td>
					<td class='input port1' style="padding:3px;"><input type='text' class='input_1 number dues' name="dues1" value="<?php echo $dues1; ?>" style="max-width:100px;" /></td>
					<td class='input port2' style="padding:3px;"><input type='text' class='input_1 number dues' name="dues2" value="<?php echo $dues2; ?>" style="max-width:100px;" /></td>
					<td class='input port3' style="padding:3px;"><input type='text' class='input_1 number dues' name="dues3" value="<?php echo $dues3; ?>" style="max-width:100px;" /></td>
				  </tr>
				  <tr bgcolor="e9e9e9">
					<td style="padding:3px;"><strong>Pilotage ($)</strong></td>
					<td class='input port1' style="padding:3px;"><input type='text' class='input_1 number pilotage' name="pilotage1" value="<?php echo $pilotage1; ?>" style="max-width:100px;" /></td>
					<td class='input port2' style="padding:3px;"><input type='text' class='input_1 number pilotage' name="pilotage2" value="<?php echo $pilotage2; ?>" style="max-width:100px;" /></td>
					<td class='input port3' style="padding:3px;"><input type='text' class='input_1 number pilotage' name="pilotage3" value="<?php echo $pilotage3; ?>" style="max-width:100px;" /></td>
				  </tr>
				  <tr bgcolor="f5f5f5">
					<td style="padding:3px;"><strong>Tugs ($)</strong></td>
					<td class='input port1' style="padding:3px;"><input type='text' class='input_1 number tugs' name="tugs1" value="<?php echo $tugs1; ?>" style="max-width:100px;" /></td>
					<td class='input port2' style="padding:3px;"><input type='text' class='input_1 number tugs' name="tugs2" value="<?php echo $tugs2; ?>" style="max-width:100px;" /></td>
					<td class='input port3' style="padding:3px;"><input type='text' class='input_1 number tugs' name="tugs3" value="<?php echo $tugs3; ?>" style="max-width:100px;" /></td>
				  </tr>
				  <tr bgcolor="e9e9e9">
					<td style="padding:3px;"><strong>Bunker Adjustment ($)</strong></td>
					<td class='input port1' style="padding:3px;"><input type='text' class='input_1 number bunkeradjustment' name="bunkeradjustment1" value="<?php echo $bunkeradjustment1; ?>" style="max-width:100px;" /></td>
					<td class='input port2' style="padding:3px;"><input type='text' class='input_1 number bunkeradjustment' name="bunkeradjustment2" value="<?php echo $bunkeradjustment2; ?>" style="max-width:100px;" /></td>
					<td class='input port3' style="padding:3px;"><input type='text' class='input_1 number bunkeradjustment' name="bunkeradjustment3" value="<?php echo $bunkeradjustment3; ?>" style="max-width:100px;" /></td>
				  </tr>
				  <tr bgcolor="f5f5f5">
					<td style="padding:3px;"><strong>Mooring ($)</strong></td>
					<td class='input port1' style="padding:3px;"><input type='text' class='input_1 number mooring' name="mooring1" value="<?php echo $mooring1; ?>" style="max-width:100px;" /></td>
					<td class='input port2' style="padding:3px;"><input type='text' class='input_1 number mooring' name="mooring2" value="<?php echo $mooring2; ?>" style="max-width:100px;" /></td>
					<td class='input port3' style="padding:3px;"><input type='text' class='input_1 number mooring' name="mooring3" value="<?php echo $mooring3; ?>" style="max-width:100px;" /></td>
				  </tr>
				  <tr bgcolor="e9e9e9">
					<td style="padding:3px;"><strong>Dockage ($)</strong></td>
					<td class='input port1' style="padding:3px;"><input type='text' class='input_1 number dockage' name="dockage1" value="<?php echo $dockage1; ?>" style="max-width:100px;" /></td>
					<td class='input port2' style="padding:3px;"><input type='text' class='input_1 number dockage' name="dockage2" value="<?php echo $dockage2; ?>" style="max-width:100px;" /></td>
					<td class='input port3' style="padding:3px;"><input type='text' class='input_1 number dockage' name="dockage3" value="<?php echo $dockage3; ?>" style="max-width:100px;" /></td>
				  </tr>
				  <tr bgcolor="f5f5f5">
					<td style="padding:3px;"><strong>Load/Discharge ($)</strong></td>
					<td class='input port1' style="padding:3px;"><input type='text' class='input_1 number loaddischarge' name="loaddischarge1" value="<?php echo $loaddischarge1; ?>" style="max-width:100px;" /></td>
					<td class='input port2' style="padding:3px;"><input type='text' class='input_1 number loaddischarge' name="loaddischarge2" value="<?php echo $loaddischarge2; ?>" style="max-width:100px;" /></td>
					<td height="12" class='input port3' style="height: 12px; padding:3px;"><span class="input port3" style="padding:3px;"><input type='text' class='input_1 number loaddischarge' name="loaddischarge3" value="<?php echo $loaddischarge3; ?>" style="max-width:100px;" /></span></td>
				  </tr>
				  <tr bgcolor="e9e9e9">
					<td style="padding:3px;"><strong>Agency Fee ($)</strong></td>
					<td class='input port1' style="padding:3px;"><input type='text' class='input_1 number agencyfee' name="agencyfee1" value="<?php echo $agencyfee1; ?>" style="max-width:100px;" /></td>
					<td class='input port2' style="padding:3px;"><input type='text' class='input_1 number agencyfee' name="agencyfee2" value="<?php echo $agencyfee2; ?>" style="max-width:100px;" /></td>
					<td class='input port3' style="padding:3px;"><input type='text' class='input_1 number agencyfee' name="agencyfee3" value="<?php echo $agencyfee3; ?>" style="max-width:100px;" /></td>
				  </tr>
				  <tr bgcolor="f5f5f5">
					<td style="padding:3px;"><strong>Miscellaneous ($)</strong></td>
					<td class='input port1' style="padding:3px;"><input type='text' class='input_1 number miscellaneous' name="miscellaneous1" value="<?php echo $miscellaneous1; ?>" style="max-width:100px;" /></td>
					<td class='input port2' style="padding:3px;"><input type='text' class='input_1 number miscellaneous' name="miscellaneous2" value="<?php echo $miscellaneous2; ?>" style="max-width:100px;" /></td>
					<td class='input port3' style="padding:3px;"><input type='text' class='input_1 number miscellaneous' name="miscellaneous3" value="<?php echo $miscellaneous3; ?>" style="max-width:100px;" /></td>
				  </tr>
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
			
				<table width="490" height='460' border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td bgcolor="#000000"><iframe src='' id="map_iframeve" width='490' height='460' frameborder="0"></iframe></td>
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
								<td class="label" style="padding:3px;"><strong>Freight Rate ($/MT)</strong></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td class='empty' style="padding:3px;"><input type='text' class='input_1 number' id='b80' name="b80" value="<?php echo $b80; ?>" style="max-width:100px;" /></td>
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
								<td style="padding:3px;"><input type='text' class='input_1 number' id='d80' name="b80" value="<?php echo $b80; ?>" style="max-width:100px;" /></td>
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
								<td class="label" style="padding:3px;"><strong>Gross Income ($)</strong></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td class="calculated" id='f80' style="padding:3px;"></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td height="5"></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td class="label" style="padding:3px;"><strong>TCE ($/day)</strong></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td class="calculated" id='g80' style="padding:3px;"></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td height="5"></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td class="label" style="padding:3px;"><strong>Total</strong></td>
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
								<td style="padding:3px;"><strong>Freight Rate ($/MT)</strong></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td class="calculated" id='b85' style="padding:3px;"></td>
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
								<td class="label" style="padding:3px;"><strong>Gross Income ($)</strong></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td class="calculated"  id='f85' style="padding:3px;"></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td height="5"></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td class="label" style="padding:3px;"><strong>TCE ($/day)</strong></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td class='empty' style="padding:3px;"><input type='text' class='input_1 number' id='g85' name='g85' value="<?php echo $g85; ?>" style="max-width:100px;" /></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td height="5"></td>
							</tr>
							<tr bgcolor="f5f5f5">
								<td class="label" style="padding:3px;"><strong>Total</strong></td>
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
$(document).ready(function(){
	$("#c31").focus();
	$("#c31").blur();
});
</script>