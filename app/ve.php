<?php
/*@session_start();
@include_once(dirname(__FILE__)."/includes/database.php");
@include_once(dirname(__FILE__)."/includes/distanceCalc.class.php");

$link = dbConnect();

function getValue($data, $id){
	$reg = "/<".$id.".*>(.*)<\/".$id.">/iUs";

	$matches = array();

	preg_match_all($reg, $data, $matches);

	return $matches[1][0];
}*/

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

		//$str = $r[$i]['data'];
		//$matches = array();
		//preg_match_all("/<name>(.*)<\/name>/iUs", $str, $matches);

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
		//$str = $r[$i]['data'];
		//$matches = array();
		//preg_match_all("/<name>(.*)<\/name>/iUs", $str, $matches);

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
		//$str = $r[$i]['data'];
		//$matches = array();
		//preg_match_all("/<name>(.*)<\/name>/iUs", $str, $matches);

		$item = array();

		$item['working_day'] = $r[$i]['working_day'];

		$items[] = $item;
	}

	echo json_encode($items);

	exit();
}

if($_GET['port']){
	$search = $_GET['term'];

	$sql = "select * from    _veson_ports where name like '%".mysql_escape_string($search)."%' limit 20";

	$items = array();

	$r = dbQuery($sql);

	$t = count($r);

	for($i=0; $i<$t; $i++){
		//$str = $r[$i]['data'];
		//$matches = array();
		//preg_match_all("/<name>(.*)<\/name>/iUs", $str, $matches);

		$item = array();

		$item['name'] = $r[$i]['name']." - ".$r[$i]['portid'];
		$item['latitude'] = $r[$i]['latitude'];
		$item['longitude'] = $r[$i]['longitude'];
		$item['portid'] = $r[$i]['portid'];

		$items[] = $item;
	}

	echo json_encode($items);

	exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="js_ve/jquery-1.7.2.min.js"></script>
<link rel="stylesheet" href="js_ve/development-bundle/themes/base/jquery.ui.all.css">
<script src="js_ve/development-bundle/jquery-1.8.0.js"></script>
<script src="js_ve/development-bundle/ui/jquery.ui.core.js"></script>
<script src="js_ve/development-bundle/ui/jquery.ui.widget.js"></script>
<script src="js_ve/development-bundle/ui/jquery.ui.position.js"></script>
<script src="js_ve/development-bundle/ui/jquery.ui.autocomplete.js"></script>
<script src="js_ve/development-bundle/ui/jquery.ui.mouse.js"></script>
<script src="js_ve/development-bundle/ui/jquery.ui.draggable.js"></script>
<script src="js_ve/development-bundle/ui/jquery.ui.position.js"></script>
<script src="js_ve/development-bundle/ui/jquery.ui.resizable.js"></script>
<script src="js_ve/development-bundle/ui/jquery.ui.dialog.js"></script>
<script src="js_ve/development-bundle/ui/jquery.ui.datepicker.js"></script>
<title>CargoSpotter VOYAGE ESTIMATOR</title>
<style>
td{
	vertical-align:top;
	border-bottom:1px solid #FFF;
	color: #000;
}

th{
	vertical-align:top;
	padding:2px;
	font-weight:bold;
}

*{
	font-size:10px;
	font-family:Verdana, Arial, Helvetica, sans-serif
}

table{
	border-collapse: collapse;
	background-color: #F5F5F5;
}

.input{
	/*background:#DDDDDD;*/
}

.calculated{
	/*background:#e9e9e9;*/
	/*text-align: center;*/
}

.empty{
	/*background:#BBB57B;*/
}

.label{
	background:#e9e9e9;
	font-weight:bold;
}

.bold{
	font-weight:bold;
}

input[type="text"]{
	/*
	border: 0px;
	background:yellow;
	*/
	/*height:100%;
	width:90%;*/
	/*text-align:center;*/
}

.number{
}

.general{
	background-color: F5F5F5;
}

.text_1{
	color:#333;
}

.input_1{
	font-size:10px;
	padding:3px;
	border:1px solid #DDDDDD;
}

body {
	background-color: #dddddd;
}

.text_1 div b {
	color: #333333;
}

/*body,td,th {
	color: #610067;
	font-size: 10px;
	background-color: #dddddd;
}*/

.content_link{
	padding:10px 15px;
	background-color:#f5f5f5;
	color:#333;
	cursor:pointer;
}

.content_link_selected{
	padding:10px 15px;
	background-color:#a6a6a6;
	color:#fff;
	cursor:pointer;
}

.clickable{
	cursor:pointer;
}
</style>

<script>

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

		}

		catch(e){

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

var sfs = [];



var gimo = "";

$(function(){



	jQuery( "#shipdetails" ).dialog( { width: '90%', height: jQuery(window).height()*0.9 });

	jQuery( "#shipdetails" ).dialog("close");	

	

	jQuery( "#contactdialog" ).dialog( { width: 900, height: 460 });

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
			$.getJSON("ve.php?port=1", req, function(data) {
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

			$.getJSON("ve.php?port=1", req, function(data) {

				

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

			$.getJSON("ve.php?port=1", req, function(data) {

				

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

			$.getJSON("ve.php?port=1", req, function(data) {

				

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

			$.getJSON("ve.php?port=1", req, function(data) {

				

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

			$.getJSON("ve.php?port=1", req, function(data) {

				

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

			$.getJSON("ve.php?port=1", req, function(data) {

				

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



	/*

	$(".n32, .n33, .n35").autocomplete({

		

		//define callback to format results

		source: function(req, add){

			

			//pass request to server

			$.getJSON("ve.php?wd==1", req, function(data) {

				

				//create array for response objects

				var suggestions = [];

				

				//process response

				$.each(data, function(i, val){								

					suggestions.push(val.working_day);

				});

				

				//pass array to callback

				add(suggestions);

			});

		},

		





	});

	*/







	$(".i35").autocomplete({

		

		//define callback to format results

		source: function(req, add){

			

			//pass request to server

			$.getJSON("ve.php?sf=1", req, function(data) {

				

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

			//setValue(jQuery("#"+idx+" .j35"), fNum(sf));



			thread("sf");

		},

		

		/*

		//define select handlers

		change: function() {

			

			//prevent 'to' field being updated and correct position

			$("#to").val("").css("top", 2);

		}

		*/

	});



	$(".i32").autocomplete({

		

		//define callback to format results

		source: function(req, add){

			

			//pass request to server

			$.getJSON("ve.php?sf=1", req, function(data) {

				

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

			//setValue(jQuery("#"+idx+" .j32"), fNum(sf));



			thread("sf");

		},

		

		/*

		//define select handlers

		change: function() {

			

			//prevent 'to' field being updated and correct position

			$("#to").val("").css("top", 2);

		}

		*/

	});



	$("#ship").autocomplete({
		//define callback to format results

		source: function(req, add){

			

			jQuery("#shipdetailshref").html("");

			//pass request to server

			$.getJSON("ve.php?search=1", req, function(data) {

				

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

			thread();

		},

		

		/*

		//define select handlers

		change: function() {

			

			//prevent 'to' field being updated and correct position

			$("#to").val("").css("top", 2);

		}

		*/

	});

});



function showShipDetails(imo){

	jQuery("#shipdetails").dialog("close")

	jQuery('#pleasewait2').show();



	jQuery.ajax({

		type: 'POST',

		url: "search_ajax.php?imo="+gimo+"&__ve=1",

		data:  '',

		

		success: function(data) {

			if(data.indexOf("<b>ERROR")!=0){

				jQuery("#shipdetails_in").html(data);

				jQuery("#shipdetails").dialog("open")

				jQuery('#pleasewait2').hide();

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

function addCommas(nStr)

{

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

	}

	else if(isNaN(num)){

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

	}

	else{

		return fNum(elem.val());

	}

}



function valueU(elem){

	if(elem.prop("tagName")=="TD"){

		return uNum(elem.html());

	}

	else{

		return uNum(elem.val());

	}

}



function setValue(elem, value){

	if(elem.prop("tagName")=="TD"){

		elem.html(value);

	}

	else{

		elem.val(value);

	}

}



function getValue(elem){

	if(elem.prop("tagName")=="TD"){

		return elem.html();

	}

	else{

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

			url: "ve.php?dc=1&from="+from+"&to="+to,

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

	}

	else{

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

			url: "ve.php?dc=1&from="+from+"&to="+to,

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

			url: "ve.php?dc=1&from="+from+"&to="+to,

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

	}

	else{

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

	//

	//setTimeout(function(){ thread(); }, 500);

}



function autoSave(){

	str = "";

	jQuery('input[type="text"]').each(function(){

		str+=jQuery(this).val()+"\n";

	});

	jQuery.ajax({

		type: 'POST',

		url: "ve.php?autosave=1",

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


function displayContent(content){
	jQuery('#voyage_estimator_id').hide();
	jQuery('#fleet_positions_id').hide();
	jQuery('#ships_coming_into_ports_id').hide();
	jQuery('#live_ship_position_id').hide();
	jQuery('#ports_intelligence_id').hide();
	jQuery('#piracy_notices_id').hide();
	jQuery('#bunker_pricing_id').hide();
	jQuery('#weather_id').hide();
	
	jQuery('#voyage_estimator_id_link').removeClass('content_link_selected');
	jQuery('#fleet_positions_id_link').removeClass('content_link_selected');
	jQuery('#ships_coming_into_ports_id_link').removeClass('content_link_selected');
	jQuery('#live_ship_position_id_link').removeClass('content_link_selected');
	jQuery('#ports_intelligence_id_link').removeClass('content_link_selected');
	jQuery('#piracy_notices_id_link').removeClass('content_link_selected');
	jQuery('#bunker_pricing_id_link').removeClass('content_link_selected');
	jQuery('#weather_id_link').removeClass('content_link_selected');
	
	jQuery('#voyage_estimator_id_link').addClass('content_link');
	jQuery('#' + content + '_link').addClass('content_link_selected');
	
	if(content=='piracy_notices_id'){
		iframe = document.getElementById('map_iframe');
  		iframe.src = 'map/index4.php';
	}else if(content=='weather_id'){
		iframe = document.getElementById('map_iframew');
  		iframe.src = 'http://map.openseamap.org/map/weather.php';
	}else{
		iframe1 = document.getElementById('map_iframe');
  		iframe1.src = '';
		
		iframe2 = document.getElementById('map_iframew');
  		iframe2.src = '';
	}
	
	jQuery('#' + content).show();
}
</script>

</head>

<body marginwidth="0">

<div id="shipdetails" title="SHIP DETAILS" style='display:none; padding-bottom:10px'>
	<div id='shipdetails_in' ></div>
</div>

<div id="contactdialog" title="CONTACT"  style='display:none'>
	<iframe id='contactiframe' frameborder=0 height="100%" width="100%" style='border:0px; height:100%; width:100%'></iframe>
</div>

<div id="mapdialogpiracyalert" title="PIRACY ALERT" style='display:none'>
	<iframe id='mapiframepiracyalert' name='mapname' frameborder=0 height="100%" width="100%" style='border:0px; height:100%; width:100%'></iframe>
</div>

<div id="bunkerpricedialog" title="BUNKER PRICE HISTORY"  style='display:none'>
	<div id='bunkerpricecontent'></div>
</div>

<script>
function getBunkerPriceHistory(port_code){
	jQuery('#pleasewait2').show();
	
	jQuery.ajax({
		type: 'GET',
		url: "bunkerpricehistory.php?port_code="+port_code,
		data:  "",

		success: function(data) {
			jQuery('#pleasewait2').hide();
			
			jQuery('#bunkerpricecontent').html(data);
			jQuery( "#bunkerpricedialog" ).dialog("open"); 
		}
	});
}

jQuery( "#mapdialogpiracyalert" ).dialog( { width: '90%', height: jQuery(window).height()*0.9 });
jQuery("#mapdialogpiracyalert").dialog("close");

jQuery( "#bunkerpricedialog" ).dialog( { width: 700, height: 600 });
jQuery( "#bunkerpricedialog" ).dialog("close");
</script>

<div style="max-width:1300px; height:auto; margin:0 auto;">
	<div>&nbsp;</div>
    <div>
        <a onclick="displayContent('voyage_estimator_id');" id='voyage_estimator_id_link' class="content_link_selected">Voyage Estimator</a> &nbsp;&nbsp; 
        <a onclick="displayContent('fleet_positions_id');" id='fleet_positions_id_link' class="content_link">Fleet Positions</a> &nbsp;&nbsp; 
        <a onclick="displayContent('ships_coming_into_ports_id');" id='ships_coming_into_ports_id_link' class="content_link">Ships Coming Into Ports</a> &nbsp;&nbsp; 
        <a onclick="displayContent('live_ship_position_id');" id='live_ship_position_id_link' class="content_link">Live Ship Position</a> &nbsp;&nbsp; 
        <a onclick="displayContent('ports_intelligence_id');" id='ports_intelligence_id_link' class="content_link">Ports Intelligence</a> &nbsp;&nbsp; 
        <a onclick="displayContent('piracy_notices_id');" id='piracy_notices_id_link' class="content_link">Piracy Notices</a> &nbsp;&nbsp; 
        <a onclick="displayContent('bunker_pricing_id');" id='bunker_pricing_id_link' class="content_link">Bunker Pricing</a> &nbsp;&nbsp; 
        <a onclick="displayContent('weather_id');" id='weather_id_link' class="content_link">Weather</a>
    </div>
    <div>&nbsp;</div>
    <div style="border-bottom:3px dotted #fff;">&nbsp;</div>
	<div>&nbsp;</div>
</div>

<div id="voyage_estimator_id" style="max-width:1300px; height:auto; margin:0 auto;">
    <table width="1300" border="0" cellspacing="0" cellpadding="0">
      <tr bgcolor="cddee5">
        <td class="text_1"><div style="padding:2px; color: #E9E9E9;"><b>VESSEL NAME / IMO #</b> &nbsp; <input type="text" id="ship" class="input_1" style="max-width:250px;" /> &nbsp; <span id='shipdetailshref' style="color:#F00;"></span></div></td>
      </tr>
    </table>
    <div id="ship_info" style="display:none;">
        <table width="1300" border="0" cellspacing="0" cellpadding="0">
          <tr bgcolor="f5f5f5">
            <td width="110" valign="top"><div style="padding:2px;"><b>IMO</b> #</div></td>
            <td width="160" valign="top" style="padding:2px;" id="ship_imo"></td>
            <td width="105" valign="top"><div style="padding:2px;"><b>LOA</b></div></td>
            <td width="200" valign="top" style="padding:2px;" id="ship_loa"></td>
            <td width="145" valign="top"><div style="padding:2px;"><b>Grain</b></div></td>
            <td width="160" valign="top" style="padding:2px;" id="ship_grain"></td>
            <td width="120" valign="top"><div style="padding:2px;"><b>Class Notation</b></div></td>
            <td width="300" valign="top" style="padding:2px;" id="ship_class_notation"></td>
          </tr>
          <tr bgcolor="e9e9e9">
            <td valign="top"><div style="padding:2px;"><b>Summer DWT</b></div></td>
            <td valign="top" style="padding:2px;" id="ship_summer_dwt"></td>
            <td valign="top"><div style="padding:2px;"><b>Draught</b></div></td>
            <td valign="top" style="padding:2px;" id="ship_draught"></td>
            <td valign="top"><div style="padding:2px;"><b>Lifting Equipment</b></div></td>
            <td valign="top" style="padding:2px;" id="ship_lifting_equipment"></td>
            <td valign="top"><div style="padding:2px;"><b>Fuel Oil</b></div></td>
            <td valign="top" style="padding:2px;" id="ship_fuel_oil"></td>
          </tr>
          <tr bgcolor="f5f5f5">
            <td valign="top"><div style="padding:2px;"><b>Gross Tonnage</b></div></td>
            <td valign="top" style="padding:2px;" id="ship_gross_tonnage"></td>
            <td valign="top"><div style="padding:2px;"><b>Speed</b></div></td>
            <td valign="top" style="padding:2px;" id="ship_speed"></td>
            <td valign="top"><div style="padding:2px;"><b>Cargo Handling</b></div></td>
            <td valign="top" style="padding:2px;" id="ship_cargo_handling"></td>
            <td valign="top"><div style="padding:2px;"><b>Fuel</b></div></td>
            <td valign="top" style="padding:2px;" id="ship_fuel"></td>
          </tr>
          <tr bgcolor="e9e9e9">
            <td valign="top"><div style="padding:2px;"><b>Built Year</b></div></td>
            <td valign="top" style="padding:2px;" id="ship_built_year"></td>
            <td valign="top"><div style="padding:2px;"><b>Breadth</b></div></td>
            <td valign="top" style="padding:2px;" id="ship_breadth"></td>
            <td valign="top"><div style="padding:2px;"><b>Decks Number</b></div></td>
            <td valign="top" style="padding:2px;" id="ship_decks_number"></td>
            <td valign="top"><div style="padding:2px;"><b>Fuel Consumption</b></div></td>
            <td valign="top" style="padding:2px;" id="ship_fuel_consumption"></td>
          </tr>
          <tr bgcolor="f5f5f5">
            <td valign="top"><div style="padding:2px;"><b>Bale</b></div></td>
            <td valign="top" style="padding:2px;" id="ship_bale"></td>
            <td valign="top"><div style="padding:2px;"><b>Cranes</b></div></td>
            <td valign="top" style="padding:2px;" id="ship_cranes"></td>
            <td valign="top"><div style="padding:2px;"><b>Bulkheads</b></div></td>
            <td valign="top" style="padding:2px;" id="ship_bulkheads"></td>
            <td valign="top"><div style="padding:2px;"><b>Fuel Type</b></div></td>
            <td valign="top" style="padding:2px;" id="ship_fuel_type"></td>
          </tr>
          <tr bgcolor="e9e9e9">
          	<td valign="top"><div style="padding:2px;"><b>Manager Owner</b></div></td>
            <td valign="top" style="padding:2px;" id="ship_manager_owner"></td>
            <td valign="top"><div style="padding:2px;"><b>Manager Owner Email</b></div></td>
            <td valign="top" style="padding:2px;" id="ship_manager_owner_email"></td>
            <td valign="top"><div style="padding:2px;"><b>Class Society</b></div></td>
            <td valign="top" style="padding:2px;" id="ship_class_society"></td>
            <td valign="top"><div style="padding:2px;"><b>Flag</b></div></td>
            <td valign="top" style="padding:2px;" id="ship_flag"></td>
          </tr>
        </table>
    </div>
    <div style="border-bottom:3px dotted #fff;">&nbsp;</div>
    <div>&nbsp;</div>
    
    <div style='display:none'>
    
        <table cellspacing="0" cellpadding="0">
    
          <col width="14" />
    
          <col width="125" />
    
          <col width="159" />
    
          <col width="138" />
    
          <tr height="18">
    
            <td width="138" height="18" class="bold">QUICK INPUT</td>
    
            <td width="182"></td>
    
            <td width="201" class="bold">QUICK RESULT</td>
    
            <td width="156"></td>
    
          </tr>
    
          <tr height="17">
    
            <td height="17" class="label">Port Extra Days</td>
    
            <td width="182" class="input" dir="ltr">
    
            <input type='text' class="general" id='c3' /></td>
    
            <td rowspan="3" class="label"><strong>Voyage Duration</strong></td>
    
            <td rowspan="3" class="calculated number" id='e3'>&nbsp;</td>
    
          </tr>
    
          <tr height="17">
    
            <td height="17" class="label">Sea Extra    Days</td>
    
            <td width="182" class="input" dir="ltr">
    
            <input type='text' class="general" id='c4' /></td>
    
          </tr>
    
          <tr height="17">
    
            <td height="17" class="label">Sea Canal    Days</td>
    
            <td width="182" class="input" dir="ltr">
    
            <input type='text' class="general" id='c5' /></td>
    
          </tr>
    
          <tr height="17">
    
            <td height="17" class="label">Canal Cost    ($)</td>
    
            <td width="182" class="input" dir="ltr"><input type='text' class="number" id='c6' /></td>
    
            <td rowspan="7" class="label"><strong>Voyage Cost ($)</strong></td>
    
            <td rowspan="7" class="calculated number" id='e6'>&nbsp;</td>
    
          </tr>
    
          <tr height="18">
    
            <td height="18" class="label">Add. Insurance    ($)</td>
    
            <td width="182" class="input" dir="ltr"><input type='text' class="number" id='c7' /></td>
    
          </tr>
    
          <tr height="17">
    
            <td height="17" class="label">ILOHC ($)</td>
    
            <td width="182" class="input" dir="ltr"><input type='text' class="number"  id='c8'/></td>
    
          </tr>
    
          <tr height="18">
    
            <td height="18" class="label">ILOW ($)</td>
    
            <td width="182" class="input" dir="ltr"><input type='text' class="number" id='c9' /></td>
    
          </tr>
    
          <tr height="17">
    
            <td height="17" class="label">CVE ($)</td>
    
            <td width="182" class="input" dir="ltr"><input type='text' class="number" id='c10' /></td>
    
          </tr>
    
          <tr height="17">
    
            <td height="17" class="label">Ballast Bonus    ($)</td>
    
            <td width="182" class="input" dir="ltr"><input type='text' class="number" id='c11' /></td>
    
          </tr>
    
          <tr height="17">
    
            <td height="17" class="label">Miscellaneous    ($)</td>
    
            <td width="182" class="input" dir="ltr"><input type='text' class="number" id='c12' /></td>
    
          </tr>
    
          <tr height="17">
    
            <td height="17" class="label">Freight Rate    ($/MT)</td>
    
            <td width="182" class="input" dir="ltr"><input type='text' class="number" id='c13' /></td>
    
            <td class="label"><strong>TCE ($/day)</strong></td>
    
            <td width="156" class="calculated number" dir="ltr" id='e13'>&nbsp;</td>
    
          </tr>
    
          <tr height="18">
    
            <td height="18" class="label">TCE ($/day)</td>
    
            <td width="182" class="input" dir="ltr"><input type='text' class="number" id='c14' /></td>
    
            <td class="label"><strong>Freight Rate ($/MT)</strong></td>
    
            <td width="156" class="calculated number" dir="ltr" id='e14'>&nbsp;</td>
    
          </tr>
    
        </table>
    
        <div style="border-bottom:3px dotted #fff;">&nbsp;</div>
        <div>&nbsp;</div>
    
    </div>
    
    <table width="1300" border="0" cellspacing="0" cellpadding="0">
      <tr bgcolor="cddee5">
        <td width="120" class="text_1"><div style="padding:2px;"><b>VOYAGE LEGS</b></div></td>
        <td width="200"></td>
        <td width="190"></td>
        <td width="200"></td>
        <td width="190"></td>
        <td width="100"></td>
        <td width="300"></td>
      </tr>
      <tr>
        <td class="text_1 label"><div style="padding:2px;"><i><strong>Type</strong></i></div></td>
        <td class="text_1 label"><div style="padding:2px;"><i><strong> Port</strong></i></div></td>
        <td class="text_1 label"><div style="padding:2px;"><i><strong>Date</strong></i></div></td>
        <td class="text_1 label"><div style="padding:2px;"><i><strong> Port</strong></i></div></td>
        <td class="text_1 label"><div style="padding:2px;"><i><strong>Date</strong></i></div></td>
        <td class="text_1 label"><div style="padding:2px;"><i><strong>Speed (knts)</strong></i></div></td>
        <td class="text_1 label"><div style="padding:2px;"><i><strong>Distance (miles)</strong></i></div></td>
      </tr>
      <tr id='ballast1' bgcolor="f5f5f5">
        <td class='general b31' style="padding:2px;"><strong>Ballast</strong></td>
        <td class='input'><div style="padding:2px;"><input type='text' class='input_1 general c31' style="max-width:190px;" /></div></td>
        <td class="input"><div style="padding:2px;"><input type='text' class='input_1 general d31' style="max-width:170px;" /></div></td>
        <td class='input'><div style="padding:2px;"><input type='text' class='input_1 general e31' style="max-width:190px;" /></div></td>
        <td class='calculated general f31' style="padding:2px;"></td>
        <td class='input'><div style="padding:2px;"><input type='text' class='input_1 number g31' style="max-width:90px;" /></div></td>
        <td class="calculated number h31" style="padding:2px;"></td>
      </tr>
      <tr id='loading1' bgcolor="e9e9e9">
    
        <td class='general b32' style="padding:2px;"><strong>Loading</strong></td>
    
        <td class='general c32' style="padding:2px;"></td>
    
        <td class='general d32' style="padding:2px;"></td>
    
        <td class='general e32' style="padding:2px;"></td>
    
        <td class="calculated f32" style="padding:2px;"></td>
    
        <td class='number g32' style="padding:2px;"></td>
    
        <td class="number h32" style="padding:2px;"></td>
    
      </tr>
    
      <tr id='bunkerstop1' bgcolor="f5f5f5">
    
        <td class='general b33' style="padding:2px;"><strong>Bunker Stop</strong></td>
    
        <td class='input general c33' style="padding:2px;"></td>
    
        <td class='general d33' style="padding:2px;"></td>
    
        <td class='input' style="padding:2px;"><input type='text' class='input_1 general e33' style="max-width:190px;" /></td>
    
        <td class="calculated f33" style="padding:2px;"></td>
    
        <td class='input' style="padding:2px;"><input type='text' class='input_1 number g33' style="max-width:90px;" /></td>
    
        <td class="calculated h33" style="padding:2px;"></td>
    
      </tr>
    
      <tr id='laden1' bgcolor="e9e9e9">
    
        <td class='general b34' style="padding:2px;"><strong>Laden</strong></td>
    
        <td class='input general c34' style="padding:2px;"></td>
    
        <td class='general d34' style="padding:2px;"></td>
    
        <td class='input' style="padding:2px;"><input type='text' class='input_1 general e34' style="max-width:190px;" /></td>
    
        <td class="calculated f34" style="padding:2px;"></td>
    
        <td class='input' style="padding:2px;"><input type='text' class='input_1 number g34' style="max-width:90px;" /></td>
    
        <td class="calculated number h34" style="padding:2px;"></td>
    
      </tr>
    
      <tr id='discharging1' bgcolor="f5f5f5">
    
        <td class='general b35' style="padding:2px;"><strong>Discharging</strong></td>
    
        <td class='input general c35' style="padding:2px;"></td>
    
        <td class='general d35' style="padding:2px;"></td>
    
        <td class='general e35' style="padding:2px;"></td>
    
        <td class="calculated f35" style="padding:2px;"></td>
    
        <td class='number g35' style="padding:2px;"></td>
    
        <td class="number h35" style="padding:2px;"></td>
    
      </tr>
    
    </table>
    
    <div style="border-bottom:3px dotted #fff;">&nbsp;</div>
    <div>&nbsp;</div>
    
    <table width="1300" border="0" cellspacing="0" cellpadding="0">
      <tr bgcolor="cddee5">
        <td class="text_1" colspan="2"><div style="padding:2px;"><b>CARGO LEGS</b></div></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td class="text_1" colspan="2"><div style="padding:2px;"><b>* Option to Load &amp; Bunker concurrently</b></div></td>
        <td class="text_1" colspan="3"><div style="padding:2px;"><b>Port Days</b></div></td>
        <td class="text_1" colspan="3"><div style="padding:2px;"><b>Sea Days</b></div></td>
      </tr>
      <tr>
        <td width="71" class="text_1 label"><div style="padding:2px;"><i><strong>Type</strong></i></div></td>
        <td width="132" class="text_1 label"><div style="padding:2px;"><i><strong>Cargo</strong></i></div></td>
        <td width="18" class="text_1 label"><div style="padding:2px;"><i><strong>SF</strong></i></div></td>
        <td width="132" class="text_1 label"><div style="padding:2px;"><i><strong>Quantity (MT)</strong></i></div></td>
        <td width="132" class="text_1 label"><div style="padding:2px;"><i><strong>Volume (M3)</strong></i></div></td>
        <td width="132" class="text_1 label"><div style="padding:2px;"><i><strong>L/D Rate (MT/day)</strong></i></div></td>
        <td width="227" class="text_1 label"><div style="padding:2px;"><i><strong>Working Days</strong></i></div></td>
        <td width="45" class="text_1 label"><div style="padding:2px;"><i><strong>L/D</strong></i></div></td>
        <td width="132" class="text_1 label"><div style="padding:2px;"><i><strong>Turn Time</strong></i></div></td>
        <td width="132" class="text_1 label"><div style="padding:2px;"><i><strong>Idle/Extra Days Sea</strong></i></div></td>
        <td width="7" class="text_1 label"><div style="padding:2px;"><i><strong>&nbsp;</strong></i></div></td>
        <td width="132" class="text_1 label"><div style="padding:2px;"><i><strong>Canal Days</strong></i></div></td>
        <td width="138" class="text_1 label"><div style="padding:2px;"><i><strong>Weather/Extra Days</strong></i></div></td>
      </tr>
      <tr id='ballast1' bgcolor="f5f5f5">
    
        <td class='general b31' style="padding:2px;"><strong>Ballast</strong></td>
    
        <td class='number i31' style="padding:2px;"></td>
    
        <td class='number j31' style="padding:2px;"></td>	
    
        <td class='number k31' style="padding:2px;"></td>
    
        <td class='number l31' style="padding:2px;"></td>
    
        <td class='number m31' style="padding:2px;"></td>
    
        <td class='number n31' style="padding:2px;"></td>
    
        <td class="number o31" style="padding:2px;"></td>
    
        <td class='number p31' style="padding:2px;"></td>
    
        <td class='number q31' style="padding:2px;"></td>
    
        <td class="calculated number r31" style="padding:2px;"></td>
    
        <td class='empty' style="padding:2px;"><input type='text' class='input_1 number s31' style="max-width:80px;" /></td>
    
        <td class='empty' style="padding:2px;"><input type='text' class='input_1 number t31' style="max-width:80px;" /></td>
    
      </tr>
    
      <tr id='loading1' bgcolor="e9e9e9">
    
        <td class='general b32' style="padding:2px;"><strong>Loading</strong></td>
    
        <td class='input' style="padding:2px;"><input type='text' class='input_1 general i32' style="max-width:190px;" /></td>
    
        <td class='number j32' style="padding:2px;"></td>
    
        <td class='input' style="padding:2px;"><input type='text' class='input_1 number k32' style="max-width:70px;" /></td>
    
        <td class='calculated number l32' style="padding:2px;"></td>
    
        <td class='input' style="padding:2px;"><input type='text' class='input_1 number m32' style="max-width:70px;" /></td>
    
        <td class='input' style="padding:2px;">
    
            <select class='input_1 general n32' style="max-width:130px;">
    
                <option value='SHINC'>SHINC</option>
    
                <option value='SATSHINC or SSHINC'>SATSHINC or SSHINC</option>
    
                <option value='SHEX'>SHEX</option>
    
                <option value='SA/SHEX or SATPMSHEX'>SA/SHEX or SATPMSHEX</option>
    
                <option value='SATSHEX or SSHEX'>SATSHEX or SSHEX</option>
    
                <option value='SHEXEIU or SHEXEIUBE or SHEXUU'>SHEXEIU or SHEXEIUBE or SHEXUU</option>
    
                <option value='FHINC'>FHINC</option>
    
                <option value='FHEX'>FHEX</option>
    
            </select>
    
        </td>
    
        <td class="calculated number o32" style="padding:2px;"></td>
    
        <td class='input' style="padding:2px;"><input type='text' class='input_1 number p32' style="max-width:70px;" /></td>
    
        <td class='input' style="padding:2px;"><input type='text' class='input_1 number q32' style="max-width:70px;" /></td>
    
        <td class="number r32" style="padding:2px;"></td>
    
        <td class='empty' style="padding:2px;"><input type='text' class='input_1 number s32' style="max-width:80px;" /></td>
    
        <td class='empty' style="padding:2px;"><input type='text' class='input_1 number t32' style="max-width:80px;" /></td>
    
      </tr>
    
      <tr id='bunkerstop1' bgcolor="f5f5f5">
    
        <td class='general b33' style="padding:2px;"><strong>Bunker Stop</strong></td>
    
        <td class='number i33' style="padding:2px;"></td>
    
        <td class='number j33' style="padding:2px;"></td>
    
        <td class='number k33' style="padding:2px;"></td>
    
        <td class='input' style="padding:2px;"><input type='text' class='input_1 number l33' style="max-width:70px;"  /></td>
    
        <td class='input' style="padding:2px;"><input type='text' class='input_1 number m33' style="max-width:70px;" /></td>
    
        <td class='input' style="padding:2px;">
    
            <select class='input_1 general n33' style="max-width:130px;">
    
                <option value='SHINC'>SHINC</option>
    
                <option value='SATSHINC or SSHINC'>SATSHINC or SSHINC</option>
    
                <option value='SHEX'>SHEX</option>
    
                <option value='SA/SHEX or SATPMSHEX'>SA/SHEX or SATPMSHEX</option>
    
                <option value='SATSHEX or SSHEX'>SATSHEX or SSHEX</option>
    
                <option value='SHEXEIU or SHEXEIUBE or SHEXUU'>SHEXEIU or SHEXEIUBE or SHEXUU</option>
    
                <option value='FHINC'>FHINC</option>
    
                <option value='FHEX'>FHEX</option>
    
            </select>
    
        </td>
    
        <td class="calculated o33" style="padding:2px;"></td>
    
        <td class='input' style="padding:2px;"><input type='text' class='input_1 number p33' style="max-width:70px;" /></td>
    
        <td class='input' style="padding:2px;"><input type='text'  class='input_1 number q33' style="max-width:70px;"  /></td>
    
        <td class="calculated number r33" style="padding:2px;"></td>
    
        <td class='empty' style="padding:2px;"><input type='text'  class='input_1 number s33' style="max-width:80px;" /></td>
    
        <td class='empty' style="padding:2px;"><input type='text'  class='input_1 number t33' style="max-width:80px;" /></td>
    
      </tr>
    
      <tr id='laden1' bgcolor="e9e9e9">
    
        <td class='general b34' style="padding:2px;"><strong>Laden</strong></td>
    
        <td class='number i34' style="padding:2px;"></td>
    
        <td class='number j34' style="padding:2px;"></td>
    
        <td class='number k34' style="padding:2px;"></td>
    
        <td class='number l34' style="padding:2px;"></td>
    
        <td class='number m34' style="padding:2px;"></td>
    
        <td class='number n34' style="padding:2px;"></td>
    
        <td class="number o34" style="padding:2px;"></td>
    
        <td class='number p34' style="padding:2px;"></td>
    
        <td class='number q34' style="padding:2px;"></td>
    
        <td class="calculated number r34" style="padding:2px;"></td>
    
        <td class='empty' style="padding:2px;"><input type='text' class='input_1 number s34' style="max-width:80px;" /></td>
    
        <td class='empty' style="padding:2px;"><input type='text' class='input_1 number t34' style="max-width:80px;" /></td>
    
      </tr>
    
      <tr id='discharging1' bgcolor="f5f5f5">
    
        <td class='general b35' style="padding:2px;"><strong>Discharging</strong></td>
    
        <td class='input' style="padding:2px;"><input type='text' class='input_1 general i35' style="max-width:190px;" /></td>
    
        <td class='number j35' style="padding:2px;"></td>
    
        <td class='input' style="padding:2px;"><input type='text' class='input_1 number k35' style="max-width:70px;" /></td>
    
        <td class='calculated number l35' style="padding:2px;"></td>
    
        <td class='input' style="padding:2px;"><input type='text'  class='input_1 number m35' style="max-width:70px;" /></td>
    
        <td class='input' style="padding:2px;">
    
            <select class='input_1 general n35' style="max-width:130px;">
    
                <option value='SHINC'>SHINC</option>
    
                <option value='SATSHINC or SSHINC'>SATSHINC or SSHINC</option>
    
                <option value='SHEX'>SHEX</option>
    
                <option value='SA/SHEX or SATPMSHEX'>SA/SHEX or SATPMSHEX</option>
    
                <option value='SATSHEX or SSHEX'>SATSHEX or SSHEX</option>
    
                <option value='SHEXEIU or SHEXEIUBE or SHEXUU'>SHEXEIU or SHEXEIUBE or SHEXUU</option>
    
                <option value='FHINC'>FHINC</option>
    
                <option value='FHEX'>FHEX</option>
    
            </select>
    
        </td>
    
        <td class="calculated number o35" style="padding:2px;"></td>
    
        <td class='input' style="padding:2px;"><input type='text' class='input_1 number p35' style="max-width:70px;" /></td>
    
        <td class='input' style="padding:2px;"><input type='text'  class='input_1 number q35' style="max-width:70px;" /></td>
    
        <td class="number r35" style="padding:2px;"></td>
    
        <td class='empty' style="padding:2px;"><input type='text' class='input_1 number s35' style="max-width:80px;" /></td>
    
        <td class='empty' style="padding:2px;"><input type='text'  class='input_1 number t35' style="max-width:80px;" /></td>
    
      </tr>
    </table>
    
    <div style="border-bottom:3px dotted #fff;">&nbsp;</div>
    <div>&nbsp;</div>
    
    <table width="1300" border="0" cellspacing="0" cellpadding="0">
      <tr bgcolor="cddee5">
        <td width="200" class="text_1"><div style="padding:2px;"><b>VOYAGE TIME</b></div></td>
        <td width="132" class="text_1"><div style="padding:2px;">&nbsp;</div></td>
        <td width="18" class="text_1"><div style="padding:2px;">&nbsp;</div></td>
        <td width="132" class="text_1"><div style="padding:2px;">&nbsp;</div></td>
        <td width="132" class="text_1"><div style="padding:2px;">&nbsp;</div></td>
        <td width="132" class="text_1"><div style="padding:2px;">&nbsp;</div></td>
        <td width="200" class="text_1"><div style="padding:2px;">&nbsp;</div></td>
        <td width="45" class="text_1"><div style="padding:2px;">&nbsp;</div></td>
        <td width="132" class="text_1"><div style="padding:2px;">&nbsp;</div></td>
        <td width="130" class="text_1"><div style="padding:2px;">&nbsp;</div></td>
        <td width="7" class="text_1"><div style="padding:2px;">&nbsp;</div></td>
        <td width="132" class="text_1"><div style="padding:2px;">&nbsp;</div></td>
        <td width="38" class="text_1"><div style="padding:2px;">&nbsp;</div></td>
      </tr>
      <tr>
        <td colspan="7" class="label" style="padding:2px;"><strong>PORT/SEA DAYS</strong></td>
        <td colspan="3" class="label calculated" id='o36' style="padding:2px;">&nbsp;</td>
        <td colspan="3" class="label calculated" id='r36' style="padding:2px;">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="7" class="label" style="padding:2px;"><strong>TOTAL VOYAGE DAYS</strong></td>
        <td colspan="6" class="label calculated" id='o37' style="padding:2px;">&nbsp;</td>
      </tr>
    </table>
    <div style="border-bottom:3px dotted #fff;">&nbsp;</div>
    <div>&nbsp;</div>
    
    <table width="1300" border="0" cellspacing="0" cellpadding="0">
    
      <tr bgcolor="cddee5">
    
        <td class="text_1" colspan="8"><div style="padding:2px;"><b>BUNKER PRICING - Data from Bunkerworld</b></div></td>
    
      </tr>
    
      <tr bgcolor="f5f5f5">
    
        <td width="200" style="padding:2px;"><b>FO Type</b></td>
    
        <td width="450" colspan="3" style="padding:2px;"></td>
    
        <td width="200" style="padding:2px;"><b>DO Type</b></td>
    
        <td width="450" colspan="3" style="padding:2px;"></td>
    
      </tr>
    
      <tr bgcolor="e9e9e9">
    
        <td style="padding:2px;"><b>FO Price ($)</b></td>
    
        <td colspan="3" class="input" style="padding:2px;"><input type='text'  id='d42' class='input_1 number' style="max-width:150px;" /></td>
    
        <td style="padding:2px;"><b>DO Price ($)</b></td>
    
        <td colspan="3" class="input" style="padding:2px;"><input type='text'  id='h42' class='input_1 number' style="max-width:150px;" /></td>
    
      </tr>
    
      <tr>
    
        <td class="text_1 label" style="padding:2px;"><b><i>FO/Ballast</i></b></td>
    
        <td class="text_1 label" style="padding:2px;"><b><i>FO/Laden</i></b></td>
    
        <td class="text_1 label" style="padding:2px;"><b><i>FO/Port</i></b></td>
    
        <td class="text_1 label" style="padding:2px;"><b><i>FO/Reserve</i></b></td>
    
        <td class="text_1 label" style="padding:2px;"><b><i>DO/Sea</i></b></td>
    
        <td class="text_1 label" style="padding:2px;"><b><i>DO/Port</i></b></td>
    
        <td class="text_1 label" style="padding:2px;" colspan="2"><b><i>DO/Reserve</i></b></td>
    
      </tr>
    
      <tr bgcolor="f5f5f5">
    
        <td style="padding:2px;"><b>Consumption (MT/day)</b></td>
    
        <td class='input' style="padding:2px;"><input type='text'  id='c44' class='input_1 number' style="max-width:100px;" /></td>
    
        <td class='input' style="padding:2px;"><input type='text'  id='d44' class='input_1 number' style="max-width:100px;" /></td>
    
        <td class='input' style="padding:2px;"><input type='text'  id='e44' class='input_1 number' style="max-width:100px;" /></td>
    
        <td class='input number' id='f44' style="padding:2px;"></td>
    
        <td class='input' style="padding:2px;"><input type='text'  id='g44' class='input_1 number' style="max-width:100px;" /></td>
    
        <td class='input' style="padding:2px;"><input type='text'  id='h44' class='input_1 number' style="max-width:100px;" /></td>
    
        <td class='general' id='i44' style="padding:2px;"></td>
    
      </tr>
    
      <tr>
    
        <td class="label" style="padding:2px;"><strong>Total Consumption (MT)</strong></td>
    
        <td class="label calculated" id='c45' style="padding:2px;"></td>
    
        <td class="label calculated" id='d45' style="padding:2px;"></td>
    
        <td class="label calculated" id='e45' style="padding:2px;"></td>
    
        <td class='label input' style="padding:2px;"><input type='text' id='f45' class='input_1 number' style="max-width:100px;" /></td>
    
        <td class="label calculated" id='g45' style="padding:2px;"></td>
    
        <td class="label calculated" id='h45' style="padding:2px;"></td>
    
        <td class='label input' style="padding:2px;"><input type='text' id='i45' class='input_1 number' style="max-width:100px;" /></td>
    
      </tr>
    </table>
    
    <div style="border-bottom:3px dotted #fff;">&nbsp;</div>
    <div>&nbsp;</div>
    
    <table width="1300" border="0" cellspacing="0" cellpadding="0">
      <tr bgcolor="cddee5">
        <td class="text_1" colspan="8"><div style="padding:2px;"><b>VOYAGE EXPENSES</b></div></td>
      </tr>
      <tr>
        <td class="label" style="padding:2px;"><strong>Expense ($)</strong></td>
        <td class="label calculated" id='c46' style="padding:2px;">&nbsp;</td>
        <td class="label calculated" id='d46' style="padding:2px;">&nbsp;</td>
        <td class="label calculated" id='e46' style="padding:2px;">&nbsp;</td>
        <td class="label calculated" id='f46' style="padding:2px;">&nbsp;</td>
        <td class="label calculated" id='g46' style="padding:2px;">&nbsp;</td>
        <td class="label calculated" id='h46' style="padding:2px;">&nbsp;</td>
        <td class="label calculated" id='i46' style="padding:2px;">&nbsp;</td>
      </tr>
      <tr>
        <td class="label" style="padding:2px;"><strong>Total ($)</strong></td>
        <td colspan="4" class="label calculated" id='c47' style="padding:2px;">&nbsp;</td>
        <td colspan="4" class="label calculated" id='g47' style="padding:2px;">&nbsp;</td>
      </tr>
    </table>
    <div style="border-bottom:3px dotted #fff;">&nbsp;</div>
    <div>&nbsp;</div>
    
    <div style="float:left; width:1300px; height:auto;">
        <div style="float:left; width:640px; height:auto; padding-right:10px;">
            <table width="640" border="0" cellspacing="0" cellpadding="0">
        
              <tr bgcolor="cddee5">
        
                <td class="text_1" colspan="8"><div style="padding:2px;"><b>DWCC</b></div></td>
        
              </tr>
        
              <tr bgcolor="f5f5f5">
        
                <td colspan="2" style="padding:2px;"><strong>DW (MT)</strong></td>
        
                <td width="155" class='calculated number' id='d18' style="padding:2px;"></td>
        
                <td width="180" style="padding:2px;"><strong>Calculated Amount  </strong></td>
        
              </tr>
        
              <tr bgcolor="e9e9e9">
        
                <td width="200" height="34" rowspan="2" style="padding:2px;"><b>Consumption (MT)</b></td>
        
                <td width="30" style="padding:2px;"><b>FO</b></td>
        
                <td height="12" class='input' style="padding:2px;"><input type='text' class='input_1 number' id='d19' style="max-width:100px;" /></td>
        
                <td class='calculated general' id='d19b' style="padding:2px;"></td>
        
              </tr>
        
              <tr bgcolor="f5f5f5">
        
                <td style="padding:2px;"><b>DO</b></td>
        
                <td class='input' style="padding:2px;"><input type='text' class='input_1 number' id='d20' style="max-width:100px;" /></td>
        
                <td class='calculated general' id='d20b' style="padding:2px;"></td>
        
              </tr>
        
              <tr bgcolor="e9e9e9">
        
                <td rowspan="2" style="padding:2px;"><b>Reserve (MT)</b></td>
        
                <td style="padding:2px;"><b>FO</b></td>
        
                <td class='input' style="padding:2px;"><input type='text' class='input_1 number' id='d21' style="max-width:100px;" /></td>
        
                <td class='calculated general' id='d21b' style="padding:2px;"></td>
        
              </tr>
        
              <tr bgcolor="f5f5f5">
        
                <td style="padding:2px;"><b>DO</b></td>
        
                <td class='input' style="padding:2px;"><input type='text' class='input_1 number' id='d22' style="max-width:100px;" /></td>
        
                <td class='calculated general' id='d22b' style="padding:2px;"></td>
        
              </tr>
        
              <tr bgcolor="e9e9e9">
        
                <td height="17" colspan="2" style="padding:2px;"><b>FW (MT)</b></td>
        
                <td class='input' style="padding:2px;"><input type='text' class='input_1 number' id='d23' style="max-width:100px;" /></td>
        
                <td class='calculated general' id='d23b' style="padding:2px;"></td>
        
              </tr>
        
              <tr bgcolor="f5f5f5">
        
                <td height="18" colspan="2" style="padding:2px;"><b>Constant (MT)</b></td>
        
                <td class='input' style="padding:2px;"><input type='text' class='input_1 number' id='d24' style="max-width:100px;" /></td>
        
                <td class='calculated general' id='d24b' style="padding:2px;"></td>
        
              </tr>
        
              <tr>
        
                <td colspan="2" class="label" style="padding:2px;"><strong>Used DW (MT)</strong></td>
        
                <td colspan="2" class='label calculated number' id='d25' style="padding:2px;"></td>
        
              </tr>
        
              <tr>
        
                <td colspan="2" class="label" style="padding:2px;"><strong>DWCC (MT)</strong></td>
        
                <td colspan="2" class='label calculated number' id='d26' style="padding:2px;"></td>
        
              </tr>
        
            </table>
        
            <div style="border-bottom:3px dotted #fff;">&nbsp;</div>
        
            <div>&nbsp;</div>
        
            <table width="640" border="0" cellspacing="0" cellpadding="0">
        
              <tr bgcolor="cddee5">
        
                <td class="text_1" colspan="5"><div style="padding:2px;"><b>PORT/S</b></div></td>
        
              </tr>
        
              <tr bgcolor="f5f5f5">
        
                <td width="160" style="padding:2px;"><strong>Laytime (hrs)</strong></td>
        
                <td width="160" class='input' style="padding:2px;"><input type='text' id='c51' class='input_1 number' style="max-width:100px;" /></td>
        
                <td width="160" style="padding:2px;"></td>
        
                <td width="160" style="padding:2px;"></td>
        
              </tr>
        
              <tr bgcolor="e9e9e9">
        
                <td style="padding:2px;"><strong>Dem ($/day)</strong></td>
        
                <td class='input' style="padding:2px;"><input type='text' id='c52' class='input_1 number' style="max-width:100px;" /></td>
        
                <td style="padding:2px;"><strong>Pro rated</strong></td>
        
                <td style="padding:2px;"></td>
        
              </tr>
        
              <tr bgcolor="f5f5f5">
        
                <td style="padding:2px;"><strong>Term</strong></td>
        
                <td style="padding:2px;">
        
                    <select id='term' class="input_1" style="max-width:100px;">
        
                        <option value='DHDLTSBENDS' >DHDLTSBENDS</option>
        
                        <option value='DHDATSBENDS' >DHDATSBENDS</option>
        
                        <option value='DHDWTSBENDS' >DHDWTSBENDS</option>
        
                    </select>
        
                </td>
        
                <td style="padding:2px;"></td>
        
                <td style="padding:2px;"></td>
        
              </tr>
        
              <tr bgcolor="e9e9e9">
        
                <td style="padding:2px;"><strong>Des ($/day)</strong></td>
        
                <td class="calculated" id='c54' style="padding:2px;">&nbsp;</td>
        
                <td style="padding:2px;"></td>
        
                <td style="padding:2px;"></td>
        
              </tr>
        
              <tr bgcolor="f5f5f5">
        
                <td style="padding:2px;"><strong>Liner Terms</strong></td>
        
                <td style="padding:2px;">
        
                <select id='linerterms' class="input_1" style="max-width:100px;">
        
                    <option value='FILO' >FILO</option>
        
                    <option value='FILTD' >FILTD</option>
        
                    <option value='FIOLS' >FIOLS</option>
        
                    <option value='FIOSLSD' >FIOSLSD</option>
        
                    <option value='FIOSPT' >FIOSPT</option>
        
                    <option value='FIOST' >FIOST</option>
        
                    <option value='LIFO' >LIFO</option>
        
                    <option value='BTBT' >BTBT</option>
        
                </select>
        
                </td>
        
                <td style="padding:2px;"></td>
        
                <td style="padding:2px;"></td>
        
              </tr>
        
              <tr bgcolor="e9e9e9">
        
                <td style="padding:2px;"><strong>Port</strong></td>
        
                <td class='port1' id='port1' style="padding:2px;"><strong>Port 1</strong></td>
        
                <td class='port2' id='port2' style="padding:2px;"><strong>Port 2</strong></td>
        
                <td class='port3' id='port3' style="padding:2px;"><strong>Port 3 </strong></td>
        
              </tr>
        
              <tr bgcolor="f5f5f5">
        
                <td style="padding:2px;"><strong>Dues ($)</strong></td>
        
                <td class='input port1' style="padding:2px;"><input type='text' class='input_1 number dues' style="max-width:100px;" /></td>
        
                <td class='input port2' style="padding:2px;"><input type='text' class='input_1 number dues' style="max-width:100px;" /></td>
        
                <td class='input port3' style="padding:2px;"><input type='text' class='input_1 number dues' style="max-width:100px;" /></td>
        
              </tr>
        
              <tr bgcolor="e9e9e9">
        
                <td style="padding:2px;"><strong>Pilotage ($)</strong></td>
        
                <td class='input port1' style="padding:2px;"><input type='text' class='input_1 number pilotage' style="max-width:100px;" /></td>
        
                <td class='input port2' style="padding:2px;"><input type='text' class='input_1 number pilotage' style="max-width:100px;" /></td>
        
                <td class='input port3' style="padding:2px;"><input type='text' class='input_1 number pilotage' style="max-width:100px;" /></td>
        
              </tr>
        
              <tr bgcolor="f5f5f5">
        
                <td style="padding:2px;"><strong>Tugs ($)</strong></td>
        
                <td class='input port1' style="padding:2px;"><input type='text' class='input_1 number tugs' style="max-width:100px;" /></td>
        
                <td class='input port2' style="padding:2px;"><input type='text' class='input_1 number tugs' style="max-width:100px;" /></td>
        
                <td class='input port3' style="padding:2px;"><input type='text' class='input_1 number tugs' style="max-width:100px;" /></td>
        
              </tr>
        
              <tr bgcolor="e9e9e9">
        
                <td style="padding:2px;"><strong>Bunker Adjustment ($)</strong></td>
        
                <td class='input port1' style="padding:2px;"><input type='text' class='input_1 number bunkeradjustment' style="max-width:100px;" /></td>
        
                <td class='input port2' style="padding:2px;"><input type='text' class='input_1 number bunkeradjustment' style="max-width:100px;" /></td>
        
                <td class='input port3' style="padding:2px;"><input type='text' class='input_1 number bunkeradjustment' style="max-width:100px;" /></td>
        
              </tr>
        
              <tr bgcolor="f5f5f5">
        
                <td style="padding:2px;"><strong>Mooring ($)</strong></td>
        
                <td class='input port1' style="padding:2px;"><input type='text' class='input_1 number mooring' style="max-width:100px;" /></td>
        
                <td class='input port2' style="padding:2px;"><input type='text' class='input_1 number mooring' style="max-width:100px;" /></td>
        
                <td class='input port3' style="padding:2px;"><input type='text' class='input_1 number mooring' style="max-width:100px;" /></td>
        
              </tr>
        
              <tr bgcolor="e9e9e9">
        
                <td style="padding:2px;"><strong>Dockage ($)</strong></td>
        
                <td class='input port1' style="padding:2px;"><input type='text' class='input_1 number dockage' style="max-width:100px;" /></td>
        
                <td class='input port2' style="padding:2px;"><input type='text' class='input_1 number dockage' style="max-width:100px;" /></td>
        
                <td class='input port3' style="padding:2px;"><input type='text' class='input_1 number dockage' style="max-width:100px;" /></td>
        
              </tr>
        
              <tr bgcolor="f5f5f5">
        
                <td style="padding:2px;"><strong>Load/Discharge ($)</strong></td>
        
                <td class='input port1' style="padding:2px;"><input type='text' class='input_1 number loaddischarge' style="max-width:100px;" /></td>
        
                <td class='input port2' style="padding:2px;"><input type='text' class='input_1 number loaddischarge' style="max-width:100px;" /></td>
        
                <td height="12" class='input port3' style="height: 12px; padding:2px;"><span class="input port3" style="padding:2px;">
        
                  <input type='text' class='input_1 number loaddischarge' style="max-width:100px;" />
        
                </span></td>
        
              </tr>
        
              <tr bgcolor="e9e9e9">
        
                <td style="padding:2px;"><strong>Agency Fee ($)</strong></td>
        
                <td class='input port1' style="padding:2px;"><input type='text' class='input_1 number agencyfee' style="max-width:100px;" /></td>
        
                <td class='input port2' style="padding:2px;"><input type='text' class='input_1 number agencyfee' style="max-width:100px;" /></td>
        
                <td class='input port3' style="padding:2px;"><input type='text' class='input_1 number agencyfee' style="max-width:100px;" /></td>
        
              </tr>
        
              <tr bgcolor="f5f5f5">
        
                <td style="padding:2px;"><strong>Miscellaneous ($)</strong></td>
        
                <td class='input port1' style="padding:2px;"><input type='text' class='input_1 number miscellaneous' style="max-width:100px;" /></td>
        
                <td class='input port2' style="padding:2px;"><input type='text' class='input_1 number miscellaneous' style="max-width:100px;" /></td>
        
                <td class='input port3' style="padding:2px;"><input type='text' class='input_1 number miscellaneous' style="max-width:100px;" /></td>
        
              </tr>
        
              <tr>
        
                <td class="label" style="padding:2px;"><strong>Demurrage ($)</strong></td>
        
                <td colspan="3" class="label calculated" id='c66' style="padding:2px;"><strong>0.00</strong></td>
        
              </tr>
        
              <tr>
        
                <td class="label" style="padding:2px;"><strong>Despatch ($)</strong></td>
        
                <td colspan="3" class="label calculated" id='c67' style="padding:2px;"><strong>48,849.31</strong></td>
        
              </tr>
        
              <tr>
        
                <td class="label" style="padding:2px;"><strong>Total ($)</strong></td>
        
                <td colspan="3" class="label calculated" id='c68' style="padding:2px;"></td>
        
              </tr>
        
            </table>
        </div>
        <div style="float:left; width:640px; height:auto; padding-left:10px;">
            <table width="640" border="0" cellspacing="0" cellpadding="0">
        
              <tr bgcolor="cddee5">
        
                <td class="text_1" colspan="8"><div style="padding:2px;"><b>CANAL</b></div></td>
        
              </tr>
        
              <tr bgcolor="f5f5f5">
        
                <td width="140" style="padding:2px;"><b>Canal</b></td>
        
                <td width="250" style="padding:2px;">&nbsp;</td>
        
                <td width="250" style="padding:2px;">		
        
                    <select id='canal' class="input_1" style="max-width:200px;">
        
                        <option value='White Sea - Baltic Canal' >White Sea - Baltic Canal</option>
        
                        <option value='Rhine - Main- Danube Canal' >Rhine - Main- Danube Canal</option>
        
                        <option value='Volga - Don Canal' >Volga - Don Canal</option>
        
                        <option value='Kiel Canal' >Kiel Canal</option>
        
                        <option value='Houston Ship Channel' >Houston Ship Channel</option>
        
                        <option value='Alphonse Xlll Canal' >Alphonse Xlll Canal</option>
        
                        <option value='Panama Canal' >Panama Canal</option>
        
                        <option value='Danube Black - Sea Canal' >Danube Black - Sea Canal</option>
        
                        <option value='Manchester Ship Canal' >Manchester Ship Canal</option>
        
                        <option value='Welland Canal' >Welland Canal</option>
        
                        <option value='Saint Lawrence Seaway' >Saint Lawrence Seaway</option>
        
                        <option value='Suez Canal' >Suez Canal</option>
        
                    </select>
        
                </td>
        
              </tr>
        
              <tr bgcolor="e9e9e9">
        
                <td style="padding:2px;"><b>Booking Fee ($)</b></td>
        
                <td class='empty' style="padding:2px;"><input type='text' id='cbook1' class='input_1 number' style="max-width:200px;" /></td>
        
                <td class='empty' style="padding:2px;"><input type='text' id='cbook2' class='input_1 number' style="max-width:200px;" /></td>
        
              </tr>
        
              <tr bgcolor="f5f5f5">
        
                <td style="padding:2px;"><b>Tugs ($)</b></td>
        
                <td class='empty' style="padding:2px;"><input type='text' id='ctug1' class='input_1 number' style="max-width:200px;" /></td>
        
                <td class='empty' style="padding:2px;"><input type='text' id='ctug2' class='input_1 number' style="max-width:200px;" /></td>
        
              </tr>
        
              <tr bgcolor="e9e9e9">
        
                <td style="padding:2px;"><b>Line Handlers ($)</b></td>
        
                <td class='empty' style="padding:2px;"><input type='text' id='cline1' class='input_1 number' style="max-width:200px;" /></td>
        
                <td class='empty' style="padding:2px;"><span class="empty" style="padding:2px;">
        
                  <input type='text' id='cline2' class='input_1 number' style="max-width:200px;" />
        
                </span></td>
        
              </tr>
        
              <tr bgcolor="f5f5f5">
        
                <td style="padding:2px;"><b>Miscellaneous ($)</b></td>
        
                <td class='empty' style="padding:2px;"><input type='text' id='cmisc1' class='input_1 number' style="max-width:200px;" /></td>
        
                <td class='empty' style="padding:2px;"><input type='text' id='cmisc2' class='input_1 number' style="max-width:200px;" /></td>
        
              </tr>
        
              <tr bgcolor="e9e9e9">
        
                <td class="label" style="padding:2px;"><strong>Total ($)</strong></td>
        
                <td class="label calculated" id='ctotal1' style="padding:2px;"></td>
        
                <td class="label calculated" id='ctotal2' style="padding:2px;"></td>
        
              </tr>
        
            </table>
        
            <div style="border-bottom:3px dotted #fff;">&nbsp;</div>
        
            <div>&nbsp;</div>
        
            <table width="639" height='460' border="0" cellspacing="0" cellpadding="0">
        
              <tr>
        
                <td width="0" height="0" bgcolor="#000000"><iframe src='map/ve.php' width='640' height='460' frameborder="0"></iframe></td>
        
              </tr>
        
            </table>
        </div>
    </div>
    
    <div style="float:left; width:100%; height:auto; border-bottom:3px dotted #fff;">&nbsp;</div>
    <div style="float:left; width:100%; height:auto;">&nbsp;</div>
    
    <table width="" border="0" cellspacing="0" cellpadding="0">
    
      <tr bgcolor="cddee5">
    
        <td width="148" class="text_1"><div style="padding:2px;"><b>VOYAGE DISBURSMENTS</b></div></td>
    
        <td width="144"></td>
    
        <td width="144"></td>
    
        <td width="144"></td>
    
        <td width="144" class="text_1"><div style="padding:2px;"><b>VOYAGE</b></div></td>
    
        <td width="144"></td>
    
        <td width="144"></td>
    
        <td width="144"></td>
    
        <td width="144"></td>
    
      </tr>
    
      <tr bgcolor="f5f5f5">
    
        <td class="label" style="padding:2px;"><strong>Bunker ($)</strong></td>
    
        <td class="label" style="padding:2px;"><strong>Port ($)</strong></td>
    
        <td class="label" style="padding:2px;"><strong>Canal($)</strong></td>
    
        <td class="label" style="padding:2px;"><strong>Add. Insurance ($)</strong></td>
    
        <td class="label" style="padding:2px;"><strong>ILOHC</strong></td>
    
        <td class="label" style="padding:2px;"><strong>ILOW</strong></td>
    
        <td class="label" style="padding:2px;"><strong>CVE</strong></td>
    
        <td class="label" style="padding:2px;"><strong>Ballast Bonus</strong></td>
    
        <td class="label" style="padding:2px;"><strong>Miscellaneous</strong></td>
    
      </tr>
    
      <tr bgcolor="e9e9e9">
    
        <td class="calculated" id='b74' style="padding:2px;"></td>
    
        <td class="calculated" id='c74' style="padding:2px;"><strong>161,150.69</strong></td>
    
        <td class="calculated" id='d74' style="padding:2px;"><strong>150,000.00</strong></td>
    
        <td class='input' style="padding:2px;"><input type='text' class='input_1 number' id='e74' style="max-width:100px;" /></td>
    
        <td class='input' style="padding:2px;"><input type='text' class='input_1 number' id='f74' style="max-width:100px;" /></td>
    
        <td class='input' style="padding:2px;"><input type='text' class='input_1 number' id='g74' style="max-width:100px;" /></td>
    
        <td class='input' style="padding:2px;"><input type='text' class='input_1 number' id='h74' style="max-width:100px;" /></td>
    
        <td class='input' style="padding:2px;"><input type='text' class='input_1 number' id='i74' style="max-width:100px;" /></td>
    
        <td class='input' style="padding:2px;"><input type='text' class='input_1 number' id='j74' style="max-width:100px;" /></td>
    
      </tr>
    
      <tr>
    
        <td colspan="9" class="label calculated" id='b75' style="padding:2px;"></td>
    
      </tr>
    
    </table>
    
    <div style="float:left; width:100%; height:auto; border-bottom:3px dotted #fff;">&nbsp;</div>
    <div style="float:left; width:100%; height:auto;">&nbsp;</div>
    
    
    
    <table width="1300" border="0" cellspacing="0" cellpadding="0">
    
      <tr bgcolor="cddee5">
    
        <td width="216" class="text_1"><div style="padding:2px;"><b>FREIGHT RATE</b></div></td>
    
        <td width="216"></td>
    
        <td width="216"></td>
    
        <td width="216"></td>
    
        <td width="216"></td>
    
        <td width="220"></td>
    
      </tr>
    
      <tr bgcolor="f5f5f5">
    
        <td class="label" style="padding:2px;"><strong>Freight Rate ($/MT)</strong></td>
    
        <td class="label" style="padding:2px;"><strong>Gross Freight ($)</strong></td>
    
        <td class="label" style="padding:2px;"><strong>Brok. Comm ($)</strong></td>
    
        <td class="label" style="padding:2px;"><strong>Add. Comm ($)</strong></td>
    
        <td class="label" style="padding:2px;"><strong>Gross Income ($)</strong></td>
    
        <td class="label" style="padding:2px;"><strong>TCE ($/day)</strong></td>
    
      </tr>
    
      <tr bgcolor="e9e9e9">
    
        <td class='empty' style="padding:2px;"><input type='text' class='input_1 number' id='b80' style="max-width:200px;" /></td>
    
        <td class="calculated" id='c80' style="padding:2px;"></td>
    
        <td style="padding:2px;"><input type='text' class='input_1 number' id='d80' style="max-width:200px;" /></td>
    
        <td style="padding:2px;"><input type='text' class='input_1 number' id='e80' style="max-width:200px;" /></td>
    
        <td class="calculated" id='f80' style="padding:2px;"></td>
    
        <td class="calculated" id='g80' style="padding:2px;"></td>
    
      </tr>
    
      <tr>
    
        <td colspan="6" class="label calculated" id='d81' style="padding:2px;"></td>
    
      </tr>
    
    </table>
    
    <div style="float:left; width:100%; height:auto; border-bottom:3px dotted #fff;">&nbsp;</div>
    <div style="float:left; width:100%; height:auto;">&nbsp;</div>
    
    <table width="1300" border="0" cellspacing="0" cellpadding="0">
    
      <tr bgcolor="cddee5">
    
        <td width="216" class="text_1"><div style="padding:2px;"><b>TCE</b></div></td>
    
        <td width="216"></td>
    
        <td width="216"></td>
    
        <td width="216"></td>
    
        <td width="216"></td>
    
        <td width="220"></td>
    
      </tr>
    
      <tr bgcolor="f5f5f5">
    
        <td style="padding:2px;"><strong>Freight Rate ($/MT)</strong></td>
    
        <td class="label" style="padding:2px;"><strong>Gross Freight ($)</strong></td>
    
        <td class="label" style="padding:2px;"><strong>Brok. Comm ($)</strong></td>
    
        <td class="label" style="padding:2px;"><strong>Add. Comm ($)</strong></td>
    
        <td class="label" style="padding:2px;"><strong>Gross Income ($)</strong></td>
    
        <td class="label" style="padding:2px;"><strong>TCE ($/day)</strong></td>
    
      </tr>
    
      <tr bgcolor="e9e9e9">
    
        <td class="calculated" id='b85' style="padding:2px;"></td>
    
        <td class="calculated"  id='c85' style="padding:2px;"></td>
    
        <td style="padding:2px;"><input type='text' class='input_1 number' id='d85' style="max-width:200px;" /></td>
    
        <td style="padding:2px;"><input type='text' class='input_1 number' id='e85' style="max-width:200px;" /></td>
    
        <td class="calculated"  id='f85' style="padding:2px;"></td>
    
        <td class='empty' style="padding:2px;"><input type='text' class='input_1 number' id='g85' style="max-width:200px;" /></td>
    
      </tr>
    
      <tr>
    
        <td colspan="6" class="label calculated"  id='d86' style="padding:2px;"></td>
    
      </tr>
    
    </table>
    <div>&nbsp;</div>
</div>

<div id="fleet_positions_id" style="max-width:1300px; height:auto; margin:0 auto; display:none;">
	<!--FLEET POSITIONS-->
    <table width="100%" border="0" cellpadding="0" cellspacing="0" style='margin-bottom:5px;'>
        <tr>
            <td>
                <script>
                function fleetPositions(){
                    jQuery("#fleetpositionsdetails").hide();
                    jQuery('#fleetpositionsresults').hide();

                    jQuery('#pleasewait4').show();

                    jQuery("#sbutton3").val("SEARCHING...");
                    jQuery("#sbutton3")[0].disabled = true;
                    
                    jQuery("#voyage_estimator_id").attr("disabled", true);
					jQuery("#fleet_positions_id").attr("disabled", true);
					jQuery("#ships_coming_into_ports_id").attr("disabled", true);
					jQuery("#live_ship_position_id").attr("disabled", true);
					jQuery("#ports_intelligence_id").attr("disabled", true);
					jQuery("#piracy_notices_id").attr("disabled", true);
					jQuery("#bunker_pricing_id").attr("disabled", true);
					jQuery("#weather_id").attr("disabled", true);
                    
                    jQuery('#cancelsearch3').show();

                    jQuery.ajax({
                        type: 'GET',
                        url: "search_ajax3.php",
                        data:  jQuery("#fleetpositions").serialize(),

                        success: function(data) {
                            jQuery("#fleetpositions_records_tab_wrapperonly").html(data);
                            jQuery('#fleetpositionsresults').fadeIn(200);

                            jQuery("#sbutton3").val("SEARCH");	
                            jQuery("#sbutton3")[0].disabled = false;
                            
                            jQuery('#pleasewait4').hide();

                            jQuery("#voyage_estimator_id").attr("disabled", false);
							jQuery("#fleet_positions_id").attr("disabled", false);
							jQuery("#ships_coming_into_ports_id").attr("disabled", false);
							jQuery("#live_ship_position_id").attr("disabled", false);
							jQuery("#ports_intelligence_id").attr("disabled", false);
							jQuery("#piracy_notices_id").attr("disabled", false);
							jQuery("#bunker_pricing_id").attr("disabled", false);
							jQuery("#weather_id").attr("disabled", false);
                            
                            jQuery('#cancelsearch3').hide();
                        }
                    });
                }
                </script>

                <form id='fleetpositions' onsubmit="fleetPositions(); return false;">
                <center>
                <table>
                    <tr>
                        <td><div style="padding:2px;">MANAGER / MANAGER OWNER</div></td>
                        <td><div style="padding:2px;"><input type='text' name='operator' class='text' style='width:200px'></div></td>
                        <td><div style="padding:2px;">SHIP NAME, IMO, MMSI, CALLSIGN</div></td>
                        <td><div style="padding:2px;"><input type='text' name='ship' class='text' style='width:200px'></div></td>
                    </tr>
                    <tr>
                        <td colspan='4' style="text-align:center;"><div style="padding:2px;"><input class='cancelbutton' type="button" id='cancelsearch3' name="cancelsearch3" value="CANCEL SEARCH"  style='cursor:pointer; display:none;'  /> &nbsp;&nbsp;&nbsp; <input class='searchbutton' type="button" id='sbutton3' name="search" value="SEARCH" style='cursor:pointer;' onclick='fleetPositions();'  /></div></td>
                    </tr>
                </table>
                </center>
                </form>
            </td>
        </tr>
        
        <script>
        $("#cancelsearch3").click(function(){
            jQuery("#cancelsearch3").val("CANCELING SEARCH...");
            jQuery("#sbutton3").hide();
            location.reload();
        });
        </script>
        
        <tr>
            <td>
                <div id='pleasewait4' style='display:none; text-align:center'>
                    <center>
                    <table>
                        <tr>
                            <td style='text-align:center'><img src='images/searching.gif' ></td>
                        </tr>
                    </table>
                    </center>
                </div>
            </td>
        </tr>
    </table>
    
    <div id="mapdialogfleet" title="MAP" style='display:none;'>
        <iframe id="mapiframefleet" name='mapname' frameborder=0 height="100%" width="100%" style='border:0px; height:100%; width:100%'></iframe>
    </div>
    
    <script type="text/javascript">
    jQuery("#mapdialogfleet" ).dialog( { width: '100%', height: jQuery(window).height()*0.9 });
    jQuery("#mapdialogfleet").dialog("close");
    
    function showMapFP(){
        jQuery('#pleasewait4').show();

        jQuery.ajax({
            type: 'GET',
            url: "search_ajax3.php",
            data:  jQuery("#fleetpositions").serialize(),

            success: function(data) {
                jQuery("#mapiframefleet")[0].src='map/index2.php';
                jQuery("#mapdialogfleet").dialog("open");
                
                jQuery('#pleasewait4').hide();
            }
        });
    }
    </script>
    
    <div id='fleetpositionsresults'>
        <div id='fleetpositions_records_tab_wrapperonly'></div>
    </div>
    <!--END OF FLEET POSITIONS-->
</div>

<div id="ships_coming_into_ports_id" style="max-width:1300px; height:auto; margin:0 auto; display:none;">
	<!--SHIPS COMING INTO PORTS-->
    <table width="100%" border="0" cellpadding="0" cellspacing="0" style='margin-bottom:5px;'>
        <tr>
            <td>
                <script>
                function shipsComingIntoPorts(){
                    jQuery("#shipscomingintoportsdetails").hide();
                    jQuery('#shipscomingintoportsresults').hide();

                    jQuery('#pleasewait5').show();

                    jQuery("#sbutton5").val("SEARCHING...");
                    jQuery("#sbutton5")[0].disabled = true;
                    
                    jQuery("#voyage_estimator_id").attr("disabled", true);
					jQuery("#fleet_positions_id").attr("disabled", true);
					jQuery("#ships_coming_into_ports_id").attr("disabled", true);
					jQuery("#live_ship_position_id").attr("disabled", true);
					jQuery("#ports_intelligence_id").attr("disabled", true);
					jQuery("#piracy_notices_id").attr("disabled", true);
					jQuery("#bunker_pricing_id").attr("disabled", true);
					jQuery("#weather_id").attr("disabled", true);
                    
                    jQuery('#cancelsearch5').show();

                    jQuery.ajax({
                        type: 'GET',
                        url: "search_ajax4.php",
                        data:  jQuery("#shipscomingintoports").serialize(),

                        success: function(data) {
                            jQuery("#shipscomingintoports_records_tab_wrapperonly").html(data);
                            jQuery('#shipscomingintoportsresults').fadeIn(200);

                            jQuery("#sbutton5").val("SEARCH");	
                            jQuery("#sbutton5")[0].disabled = false;
                            
                            jQuery('#pleasewait5').hide();

                            jQuery("#voyage_estimator_id").attr("disabled", false);
							jQuery("#fleet_positions_id").attr("disabled", false);
							jQuery("#ships_coming_into_ports_id").attr("disabled", false);
							jQuery("#live_ship_position_id").attr("disabled", false);
							jQuery("#ports_intelligence_id").attr("disabled", false);
							jQuery("#piracy_notices_id").attr("disabled", false);
							jQuery("#bunker_pricing_id").attr("disabled", false);
							jQuery("#weather_id").attr("disabled", false);
                            
                            jQuery('#cancelsearch5').hide();
                        }
                    });
                }
                </script>

                <form id='shipscomingintoports' onsubmit="shipsComingIntoPorts(); return false;">
                <center>
                <table>
                    <tr>
                        <td><b>PORT NAME</b></td>
                        <td width="5">&nbsp;</td>
                        <td>
                            <input type='text' id="suggest3" name='port_name' class='text' style='width:200px; padding:3px;'>
                            <script type="text/javascript">
                            jQuery("#suggest3").focus().autocomplete(ports);
                            jQuery("#suggest3").setOptions({
                                scrollHeight: 180
                            });
                            </script>
                        </td>
                        <td width="10">&nbsp;</td>
                        <td><b>DATE FROM</b></td>
                        <td width="5">&nbsp;</td>
                        <td>
                            <input type="text" name="date_from" value="<?php echo date("M d, Y", time()); ?>" readonly="readonly" onclick="showCalendar('',this,null,'','',0,5,1)" class="text" style="width:90px; padding:3px;" />
                
                            <b>TO</b>
                
                            <input type="text" name="date_to" value="<?php echo date("M d, Y", time()+(7*24*60*60)); ?>" readonly="readonly" onclick="showCalendar('',this,null,'','',0,5,1)" class="text" style="width:90px; padding:3px;" />
                        </td>
                    </tr>
                    <tr>
                        <td colspan="7">&nbsp;</td>
                    </tr>
                    <tr>
                        <td valign="top"><b>SHIP TYPE</b></td>
                        <td width="5">&nbsp;</td>
                        <td>
                            <select name="p_vessel_type[]" multiple="multiple" size="16" id='p_vessel_type_id' style="width:200px;">
                                <optgroup label="BULK CARRIER">
                                    <option value="ORE CARRIER">ORE CARRIER</option>
                                    <option value="WOOD CHIPS CARRIER">WOOD CHIPS CARRIER</option>
                                </optgroup>
                                <optgroup label="CARGO">
                                    <option value="BARGE CARRIER">BARGE CARRIER</option>
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
                                    <option value="RO-RO/CONTAINER CARRIER">RO-RO/CONTAINER CARRIER</option>
                                    <option value="RO-RO/PASSENGER SHIP">RO-RO/PASSENGER SHIP</option>
                                </optgroup>
                            </select>
                        </td>
                        <td width="10">&nbsp;</td>
                        <td><b>&nbsp;</b></td>
                        <td width="5">&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="7">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="7" style="text-align:center; padding-top:15px;"><input class='cancelbutton' type="button" id='cancelsearch5' name="cancelsearch5" value="CANCEL SEARCH"  style='cursor:pointer; display:none;'  /> &nbsp;&nbsp;&nbsp; <input class='searchbutton' type="button" id='sbutton5' name="search" value="SEARCH" style='cursor:pointer;' onclick='shipsComingIntoPorts();'  /></td>
                    </tr>
                    <tr>
                        <td colspan="7">&nbsp;</td>
                    </tr>
                </table>
                </center>
                </form>
            </td>
        </tr>
        
        <script>
        $("#cancelsearch5").click(function(){
            jQuery("#cancelsearch5").val("CANCELING SEARCH...");
            jQuery("#sbutton5").hide();
            location.reload();
        });
        </script>
        
        <tr>
            <td>
                <div id='pleasewait5' style='display:none; text-align:center'>
                    <center>
                    <table>
                        <tr>
                            <td style='text-align:center'><img src='images/searching.gif' ></td>
                        </tr>
                    </table>
                    </center>
                </div>
            </td>
        </tr>
    </table>
    <div id='shipscomingintoportsresults'>
        <div id='shipscomingintoports_records_tab_wrapperonly'></div>
    </div>
    <!--END OF SHIPS COMING INTO PORTS-->
</div>

<div id="live_ship_position_id" style="max-width:1300px; height:auto; margin:0 auto; display:none;">
	<!--LIVE SHIP POSITION-->
	<script>
    function viewLiveShipPosition(){
        jQuery('#liveshippositionresults').hide();

        jQuery('#pleasewait2').show();
        
        jQuery("#voyage_estimator_id").attr("disabled", true);
		jQuery("#fleet_positions_id").attr("disabled", true);
		jQuery("#ships_coming_into_ports_id").attr("disabled", true);
		jQuery("#live_ship_position_id").attr("disabled", true);
		jQuery("#ports_intelligence_id").attr("disabled", true);
		jQuery("#piracy_notices_id").attr("disabled", true);
		jQuery("#bunker_pricing_id").attr("disabled", true);
		jQuery("#weather_id").attr("disabled", true);

        jQuery.ajax({
            type: 'GET',
            url: "search_ajax7.php",
            data:  jQuery("#live_ship_position").serialize(),

            success: function(data) {
                jQuery("#liveshipposition_records_tab_wrapperonly").html(data);
                jQuery('#liveshippositionresults').fadeIn(200);
                
                jQuery('#pleasewait2').hide();

                jQuery("#voyage_estimator_id").attr("disabled", false);
				jQuery("#fleet_positions_id").attr("disabled", false);
				jQuery("#ships_coming_into_ports_id").attr("disabled", false);
				jQuery("#live_ship_position_id").attr("disabled", false);
				jQuery("#ports_intelligence_id").attr("disabled", false);
				jQuery("#piracy_notices_id").attr("disabled", false);
				jQuery("#bunker_pricing_id").attr("disabled", false);
				jQuery("#weather_id").attr("disabled", false);
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
    <table width="100%" border="0" cellpadding="0" cellspacing="0" style='margin-bottom:5px;'>
        <tr>
            <td>
                <center>
                <table>
                    <tr>
                        <td>
                            <div style='padding:5px;'>
                                <table width="990">
                                    <tr>
                                        <td width="80"><h2><a style="cursor:pointer;" onclick="toggleCategories();">CATEGORIES</a></h2></td>
                                        <td><a style="cursor:pointer;" onclick="toggleCategories();"><img src='images/up.png' width="15" height="15" id='paramicon1' /></a></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">&nbsp;</td>
                                    </tr>
                                </table>
                                <table id="live_ship_positions_categories" width="990">
                                    <tr>
                                        <td colspan="8">
                                            <table width="990">
                                                <tr>
                                                    <td colspan="18" style="padding-top:10px;">&nbsp;</td>
                                                </tr>
                                                <tr style="padding-top:25px; font-family:Arial, Helvetica, sans-serif; font-size:10px;">
                                                    <td valign="top"><div style='padding:5px; text-align:right;'>&laquo; 90 DAYS</div></td>
                                                    <td valign="top"><div style='padding:2px 5px 5px 0px;'><input type="checkbox" id="pos_daterange_id" name="pos_daterange[]" onclick="viewLiveShipPosition();" value="bd90" /></div></td>
                                                    <td valign="top"><div style='padding:5px; text-align:right;'>&laquo; 60 DAYS</div></td>
                                                    <td valign="top"><div style='padding:2px 5px 5px 0px;'><input type="checkbox" id="pos_daterange_id" name="pos_daterange[]" onclick="viewLiveShipPosition();" value="bd60" /></div></td>
                                                    <td valign="top"><div style='padding:5px; text-align:right;'>&laquo; 30 DAYS</div></td>
                                                    <td valign="top"><div style='padding:2px 5px 5px 0px;'><input type="checkbox" id="pos_daterange_id" name="pos_daterange[]" onclick="viewLiveShipPosition();" value="bd30" /></div></td>
                                                    <td valign="top"><div style='padding:5px; color:#F00; text-align:right;'>TODAY &raquo;</div></td>
                                                    <td valign="top"><div style='padding:2px 5px 5px 0px;'><input type="checkbox" id="pos_daterange_id" name="pos_daterange[]" onclick="viewLiveShipPosition();" value="t" /></div></td>
                                                    <td valign="top"><div style='padding:5px; text-align:right;'>1 DAY &raquo;</div></td>
                                                    <td valign="top"><div style='padding:2px 5px 5px 0px;'><input type="checkbox" id="pos_daterange_id" name="pos_daterange[]" onclick="viewLiveShipPosition();" value="fd1" /></div></td>
                                                    <td valign="top"><div style='padding:5px; text-align:right;'>7 DAYS &raquo;</div></td>
                                                    <td valign="top"><div style='padding:2px 5px 5px 0px;'><input type="checkbox" id="pos_daterange_id" name="pos_daterange[]" onclick="viewLiveShipPosition();" value="fd7" /></div></td>
                                                    <td valign="top"><div style='padding:5px; text-align:right;'>30 DAYS &raquo;</div></td>
                                                    <td valign="top"><div style='padding:2px 5px 5px 0px;'><input type="checkbox" id="pos_daterange_id" name="pos_daterange[]" onclick="viewLiveShipPosition();" value="fd30" /></div></td>
                                                    <td valign="top"><div style='padding:5px; text-align:right;'>60 DAYS &raquo;</div></td>
                                                    <td valign="top"><div style='padding:2px 5px 5px 0px;'><input type="checkbox" id="pos_daterange_id" name="pos_daterange[]" onclick="viewLiveShipPosition();" value="fd60" /></div></td>
                                                    <td valign="top"><div style='padding:5px; text-align:right;'>90 DAYS &raquo;</div></td>
                                                    <td valign="top"><div style='padding:2px 5px 5px 0px;'><input type="checkbox" id="pos_daterange_id" name="pos_daterange[]" onclick="viewLiveShipPosition();" value="fd90" /></div></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="18" style="padding-top:10px;">&nbsp;</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="top" width="20"><div style='padding:5px 0px 5px 5px;'><input type="checkbox" id="pos_vessel_type_id" name="pos_vessel_type[]" onclick="viewLiveShipPosition();" value="ORE CARRIER" /></div></td>
                                        <td valign="top" width="228"><div style='padding:5px;'>ORE CARRIER</div></td>
                                        <td valign="top" width="20"><div style='padding:5px 0px 5px 5px;'><input type="checkbox" id="pos_vessel_type_id" name="pos_vessel_type[]" onclick="viewLiveShipPosition();" value="WOOD CHIPS CARRIER" /></div></td>
                                        <td valign="top" width="227"><div style='padding:5px;'>WOOD CHIPS CARRIER</div></td>
                                        <td valign="top" width="20"><div style='padding:5px 0px 5px 5px;'><input type="checkbox" id="pos_vessel_type_id" name="pos_vessel_type[]" onclick="viewLiveShipPosition();" value="BARGE CARRIER" /></div></td>
                                        <td valign="top" width="227"><div style='padding:5px;'>BARGE CARRIER</div></td>
                                        <td valign="top" width="20"><div style='padding:5px 0px 5px 5px;'><input type="checkbox" id="pos_vessel_type_id" name="pos_vessel_type[]" onclick="viewLiveShipPosition();" value="CARGO/PASSENGER SHIP" /></div></td>
                                        <td valign="top" width="228"><div style='padding:5px;'>CARGO/PASSENGER SHIP</div></td>
                                    </tr>
                                    <tr>
                                        <td valign="top" width="20"><div style='padding:5px 0px 5px 5px;'><input type="checkbox" id="pos_vessel_type_id" name="pos_vessel_type[]" onclick="viewLiveShipPosition();" value="HEAVY LOAD CARRIER" /></div></td>
                                        <td valign="top" width="228"><div style='padding:5px;'>HEAVY LOAD CARRIER</div></td>
                                        <td valign="top" width="20"><div style='padding:5px 0px 5px 5px;'><input type="checkbox" id="pos_vessel_type_id" name="pos_vessel_type[]" onclick="viewLiveShipPosition();" value="LIVESTOCK CARRIER" /></div></td>
                                        <td valign="top" width="227"><div style='padding:5px;'>LIVESTOCK CARRIER</div></td>
                                        <td valign="top" width="20"><div style='padding:5px 0px 5px 5px;'><input type="checkbox" id="pos_vessel_type_id" name="pos_vessel_type[]" onclick="viewLiveShipPosition();" value="MOTOR HOPPER" /></div></td>
                                        <td valign="top" width="227"><div style='padding:5px;'>MOTOR HOPPER</div></td>
                                        <td valign="top" width="20"><div style='padding:5px 0px 5px 5px;'><input type="checkbox" id="pos_vessel_type_id" name="pos_vessel_type[]" onclick="viewLiveShipPosition();" value="NUCLEAR FUEL CARRIER" /></div></td>
                                        <td valign="top" width="228"><div style='padding:5px;'>NUCLEAR FUEL CARRIER</div></td>
                                    </tr>
                                    <tr>
                                        <td valign="top" width="20"><div style='padding:5px 0px 5px 5px;'><input type="checkbox" id="pos_vessel_type_id" name="pos_vessel_type[]" onclick="viewLiveShipPosition();" value="SLUDGE CARRIER" /></div></td>
                                        <td valign="top" width="228"><div style='padding:5px;'>SLUDGE CARRIER</div></td>
                                        <td valign="top" width="20"><div style='padding:5px 0px 5px 5px;'><input type="checkbox" id="pos_vessel_type_id" name="pos_vessel_type[]" onclick="viewLiveShipPosition();" value="CEMENT CARRIER" /></div></td>
                                        <td valign="top" width="227"><div style='padding:5px;'>CEMENT CARRIER</div></td>
                                        <td valign="top" width="20"><div style='padding:5px 0px 5px 5px;'><input type="checkbox" id="pos_vessel_type_id" name="pos_vessel_type[]" onclick="viewLiveShipPosition();" value="OBO CARRIER" /></div></td>
                                        <td valign="top" width="227"><div style='padding:5px;'>OBO CARRIER</div></td>
                                        <td valign="top" width="20"><div style='padding:5px 0px 5px 5px;'><input type="checkbox" id="pos_vessel_type_id" name="pos_vessel_type[]" onclick="viewLiveShipPosition();" value="RO-RO/CONTAINER CARRIER" /></div></td>
                                        <td valign="top" width="228"><div style='padding:5px;'>RO-RO/CONTAINER CARRIER</div></td>
                                    </tr>
                                    <tr>
                                        <td valign="top" width="20"><div style='padding:5px 0px 5px 5px;'><input type="checkbox" id="pos_vessel_type_id" name="pos_vessel_type[]" onclick="viewLiveShipPosition();" value="RO-RO/PASSENGER SHIP" /></div></td>
                                        <td valign="top" width="228"><div style='padding:5px;'>RO-RO/PASSENGER SHIP</div></td>
                                        <td valign="top" width="20"><div style='padding:5px 0px 5px 5px;'>&nbsp;</div></td>
                                        <td valign="top" width="227"><div style='padding:5px;'>&nbsp;</div></td>
                                        <td valign="top" width="20"><div style='padding:5px 0px 5px 5px;'>&nbsp;</div></td>
                                        <td valign="top" width="227"><div style='padding:5px;'>&nbsp;</div></td>
                                        <td valign="top" width="20"><div style='padding:5px 0px 5px 5px;'>&nbsp;</div></td>
                                        <td valign="top" width="228"><div style='padding:5px;'>&nbsp;</div></td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                    
                    <div id="mapdialog1" title="MAP" style='display:none;'>
                        <iframe id="mapiframe1" name='mapname' frameborder=0 height="100%" width="100%" style='border:0px; height:100%; width:100%'></iframe>
                    </div>
                    
                    <script type="text/javascript">
                    jQuery("#mapdialog1" ).dialog( { width: '100%', height: jQuery(window).height()*0.9 });
                    jQuery("#mapdialog1").dialog("close");
                    
                    function showMap(){
                        jQuery('#pleasewait2').show();
    
                        jQuery.ajax({
                            type: 'GET',
                            url: "search_ajax7.php",
                            data:  jQuery("#live_ship_position").serialize(),
    
                            success: function(data) {
                                jQuery("#mapiframe1")[0].src='map/index10_online.php';
                                jQuery("#mapdialog1").dialog("open");
                                
                                jQuery('#pleasewait2').hide();
                            }
                        });
                    }
                    </script>
                    
                    <tr>
                        <td style="padding-top:20px;">
                            <div id='liveshippositionresults'>
                                <div id='liveshipposition_records_tab_wrapperonly'></div>
                            </div>
                        </td>
                    </tr>
                </table>
                </center>
            </td>
        </tr>
    </table>
    </form>
    <!--END OF LIVE SHIP POSITION-->
</div>

<div id="ports_intelligence_id" style="max-width:1300px; height:auto; margin:0 auto; display:none;">
	<!--PORT INTELLIGENCE-->
    <div id="mapdialogportintelligence" title="MAP" style='display:none;'>
        <iframe id="mapiframeportintelligence" name='mapname' frameborder=0 height="100%" width="100%" style='border:0px; height:100%; width:100%'></iframe>
    </div>
    
    <script>
    jQuery("#mapdialogportintelligence" ).dialog( { width: '100%', height: jQuery(window).height()*0.9 });
    jQuery("#mapdialogportintelligence").dialog("close");
    
    function showMapPI(){
        jQuery('#pleasewait_portintelligence').show();

        jQuery.ajax({
            type: 'GET',
            url: "search_ajax_portintelligence.php",
            data:  jQuery("#portintelligence_form").serialize(),

            success: function(data) {
                jQuery("#mapiframeportintelligence")[0].src='map/index11.php';
                jQuery("#mapdialogportintelligence").dialog("open");
                
                jQuery('#pleasewait_portintelligence').hide();
            }
        });
    }
    
    function portIntelligenceSubmit(){
        jQuery('#portintelligenceresults').hide();
    
        jQuery('#pleasewait_portintelligence').show();
        
        jQuery("#voyage_estimator_id").attr("disabled", true);
		jQuery("#fleet_positions_id").attr("disabled", true);
		jQuery("#ships_coming_into_ports_id").attr("disabled", true);
		jQuery("#live_ship_position_id").attr("disabled", true);
		jQuery("#ports_intelligence_id").attr("disabled", true);
		jQuery("#piracy_notices_id").attr("disabled", true);
		jQuery("#bunker_pricing_id").attr("disabled", true);
		jQuery("#weather_id").attr("disabled", true);
    
        jQuery("#btn_search_portintelligence_id").val("SEARCHING...");
        jQuery("#btn_search_portintelligence_id")[0].disabled = true;
        
        jQuery('#btn_cancelsearch_portintelligence_id').show();
    
        jQuery.ajax({
            type: 'GET',
            url: "search_ajax_portintelligence.php",
            data:  jQuery("#portintelligence_form").serialize(),
    
            success: function(data) {
                jQuery("#portintelligence_tab_wrapperonly").html(data);
                jQuery('#portintelligenceresults').fadeIn(200);
    
                jQuery("#btn_search_portintelligence_id").val("SEARCH");	
                jQuery("#btn_search_portintelligence_id")[0].disabled = false;
                
                jQuery('#pleasewait_portintelligence').hide();
                
                jQuery("#voyage_estimator_id").attr("disabled", false);
                jQuery("#fleet_positions_id").attr("disabled", false);
				jQuery("#ships_coming_into_ports_id").attr("disabled", false);
				jQuery("#live_ship_position_id").attr("disabled", false);
                jQuery("#ports_intelligence_id").attr("disabled", false);
                jQuery("#piracy_notices_id").attr("disabled", false);
                jQuery("#bunker_pricing_id").attr("disabled", false);
                jQuery("#weather_id").attr("disabled", false);
                
                jQuery('#btn_cancelsearch_portintelligence_id').hide();
            }
        });
    }
    </script>
    <form id='portintelligence_form' onsubmit="portIntelligenceSubmit(); return false;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style='margin-bottom:5px;'>
      <tr>
        <td><div style="padding:2px;">PORT NAME: <input id='portname_id' type="text" name="portname" class="text" style='width:200px;' /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>OR</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; COUNTRY NAME: <input id='countryname_id' type="text" name="countryname" class="text" style='width:200px;' /></div></td>
      </tr>
      <tr>
        <td style="padding:2px 0px;" align="center" colspan="2"><input class='cancelbutton' type="button" id='btn_cancelsearch_portintelligence_id' name="btn_cancelsearch_portintelligence" value="CANCEL SEARCH"  style='cursor:pointer; display:none;'  /> &nbsp;&nbsp;&nbsp; <input class='searchbutton' type="button" id='btn_search_portintelligence_id' name="btn_search_portintelligence" value="SEARCH" style='cursor:pointer;' onclick='portIntelligenceSubmit();'  /></td>
      </tr>
      <tr>
        <td colspan="2">
            <div id='pleasewait_portintelligence' style='display:none; text-align:center'>
                <center>
                <table>
                    <tr>
                        <td style='text-align:center'><img src='images/searching.gif' ></td>
                    </tr>
                </table>
                </center>
            </div>
        </td>
      </tr>
      <tr>
        <td colspan="2">
        	<div style="padding:2px;">
                <div id='portintelligenceresults'>
                    <div id='portintelligence_tab_wrapperonly'></div>
                </div>
            </div>
        </td>
      </tr>
    </table>
    </form>
    
    <script type="text/javascript">
    jQuery("#portname_id").focus().autocomplete(wpi_ports);
    jQuery("#portname_id").setOptions({
        scrollHeight: 180
    });
    
    jQuery("#countryname_id").focus().autocomplete(wpi_countries);
    jQuery("#countryname_id").setOptions({
        scrollHeight: 180
    });
    
    $("#btn_cancelsearch_portintelligence_id").click(function(){
        jQuery("#btn_cancelsearch_portintelligence_id").val("CANCELING SEARCH...");
        jQuery("#btn_search_portintelligence_id").hide();
        location.reload();
    });
    </script>
    <!--END OF PORT INTELLIGENCE-->
</div>

<div id="piracy_notices_id" style="max-width:1300px; height:auto; margin:0 auto; display:none;">
	<!--PIRACY ALERTS-->
	<script>
    function openMapPiracyAlert(date, lat, long, text){
        jQuery("#mapiframepiracyalert")[0].src='map/index3.php?date='+date+'&lat='+lat+'&long='+long+'&text='+text;
        jQuery("#mapdialogpiracyalert").dialog("open");
    }
    </script>
    
    <table width="100%" border="0" cellpadding="2" cellspacing="2" style='margin-bottom:5px;'>
        <tr style="padding-bottom:10px;">
            <td width="100%" align="center" colspan="3"><div style='padding:20px;'><a onclick='showMapPA();' class='clickable'>view larger map</a></div></td>
        </tr>
        <tr style='background:#999;'>
            <th width="100%" align="center" colspan="3"><div style='padding:20px;'><iframe src='' id="map_iframe" width='990' height='500'></iframe></div></th>
        </tr>
        <tr style='background:#999;'>
            <th width="150" align="left"><div style='padding:20px;'>DATE</div></th>
            <th width="150" align="left"><div style='padding:20px;'>TIME</div></th>
            <th><div style='padding:20px;'>ALERT</div></th>
        </tr>
        
        <?php
        $sql = "SELECT * FROM _sbis_piracy_alerts ORDER BY dateadded DESC LIMIT 0,10";
        $data = dbQuery($sql);
        $t = count($data);
    
        for($i1=0; $i1<$t; $i1++){
            if($data[$i1]['alert']!=$data[$i1-1]['alert']){
                $lines = explode("<ALERT>", $data[$i1]['alert']);
                
                if($lines){
                    $i = 1;
                    foreach($lines as $line){
                        if(getValue($lines[$i], 'TEXT')!=""){
                            echo "<tr style='background:#e5e5e5;'>
                                <td align='left'><div style='padding:20px;'>".date("M d, Y", strtotime(getValue($lines[$i], 'DATE')))."</div></td>
                                <td align='left'><div style='padding:20px;'>".date("G:i:s", strtotime(getValue($lines[$i], 'DATE')))." UTC</div></td>
                                <td align='left'><div style='padding:20px;'><a onclick='openMapPiracyAlert(\"".date("M d, Y G:i:s", strtotime(getValue($lines[$i], 'DATE')))." UTC\", \"".getValue($lines[$i], 'LATITUDE')."\", \"".getValue($lines[$i], 'LONGITUDE')."\", \"".addslashes(getValue($lines[$i], 'TEXT'))."\")' class='clickable'>".getValue($lines[$i], 'TEXT')."</a></div></td>
                            </tr>";
                        }
                        
                        $i++;
                    }
                }
            }
        }
        ?>
        
        <div id="mapdialog2" title="MAP" style='display:none;'>
            <iframe id="mapiframe2" name='mapname' frameborder=0 height="100%" width="100%" style='border:0px; height:100%; width:100%'></iframe>
        </div>
        
        <script type="text/javascript">
        jQuery("#mapdialog2" ).dialog( { width: '100%', height: jQuery(window).height()*0.9 });
        jQuery("#mapdialog2").dialog("close");
        
        function showMapPA(){
            jQuery("#mapiframe2")[0].src='map/index4.php';
            jQuery("#mapdialog2").dialog("open");
        }
        </script>
        
    </table>
    <!--END OF PIRACY ALERTS-->
</div>

<div id="bunker_pricing_id" style="max-width:1300px; height:auto; margin:0 auto; display:none;">
	<div id="mapdialogbunkerprice" title="BUNKER PRICE" style='display:none'>
        <iframe id='mapiframebunkerprice' name='mapname' frameborder=0 height="100%" width="100%" style='border:0px; height:100%; width:100%'></iframe>
    </div>
	
	<!--BUNKER PRICE-->
	<script>
    jQuery("#mapdialogbunkerprice" ).dialog( { width: '100%', height: jQuery(window).height()*0.9 });
    jQuery("#mapdialogbunkerprice").dialog("close");
    
    function showMapBP(){
        jQuery('#pleasewait_bunkerprice').show();

        jQuery.ajax({
            type: 'GET',
            url: "search_ajax_bunkerprice.php",
            data:  jQuery("#bunkerprice_form").serialize(),

            success: function(data) {
                jQuery("#mapiframebunkerprice")[0].src='map/index12.php';
                jQuery("#mapdialogbunkerprice").dialog("open");
                
                jQuery('#pleasewait_bunkerprice').hide();
            }
        });
    }
    
    function bunkerPriceSubmit(){
        jQuery('#bunkerpriceresults').hide();
    
        jQuery('#pleasewait_bunkerprice').show();
        
        jQuery("#voyage_estimator_id").attr("disabled", true);
		jQuery("#fleet_positions_id").attr("disabled", true);
		jQuery("#ships_coming_into_ports_id").attr("disabled", true);
		jQuery("#live_ship_position_id").attr("disabled", true);
		jQuery("#ports_intelligence_id").attr("disabled", true);
		jQuery("#piracy_notices_id").attr("disabled", true);
		jQuery("#bunker_pricing_id").attr("disabled", true);
		jQuery("#weather_id").attr("disabled", true);
    
        jQuery("#btn_search_bunkerprice_id").val("SEARCHING...");
        jQuery("#btn_search_bunkerprice_id")[0].disabled = true;
        
        jQuery('#btn_cancelsearch_bunkerprice_id').show();
    
        jQuery.ajax({
            type: 'GET',
            url: "search_ajax_bunkerprice.php",
            data:  jQuery("#bunkerprice_form").serialize(),
    
            success: function(data) {
                jQuery("#bunkerprice_tab_wrapperonly").html(data);
                jQuery('#bunkerpriceresults').fadeIn(200);
    
                jQuery("#btn_search_bunkerprice_id").val("SEARCH");	
                jQuery("#btn_search_bunkerprice_id")[0].disabled = false;
                
                jQuery('#pleasewait_bunkerprice').hide();
                
                jQuery("#voyage_estimator_id").attr("disabled", false);
                jQuery("#fleet_positions_id").attr("disabled", false);
				jQuery("#ships_coming_into_ports_id").attr("disabled", false);
				jQuery("#live_ship_position_id").attr("disabled", false);
                jQuery("#ports_intelligence_id").attr("disabled", false);
                jQuery("#piracy_notices_id").attr("disabled", false);
                jQuery("#bunker_pricing_id").attr("disabled", false);
                jQuery("#weather_id").attr("disabled", false);
                
                jQuery('#btn_cancelsearch_bunkerprice_id').hide();
            }
        });
    }
    </script>
    <form id='bunkerprice_form' onsubmit="bunkerPriceSubmit(); return false;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style='margin-bottom:5px;'>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><div style="padding:2px;">PORT NAME: <input id='bunkerportname_id' type="text" name="bunkerportname" class="text" style='width:200px;' /></div></td>
      </tr>
      <tr>
        <td style="padding:2px 0px;" align="center" colspan="2"><input class='cancelbutton' type="button" id='btn_cancelsearch_bunkerprice_id' name="btn_cancelsearch_bunkerprice" value="CANCEL SEARCH"  style='cursor:pointer; display:none;'  /> &nbsp;&nbsp;&nbsp; <input class='searchbutton' type="button" id='btn_search_bunkerprice_id' name="btn_search_bunkerprice" value="SEARCH" style='cursor:pointer;' onclick='bunkerPriceSubmit();'  /></td>
      </tr>
      <tr>
        <td colspan="2">
            <div id='pleasewait_bunkerprice' style='display:none; text-align:center'>
                <center>
                <table>
                    <tr>
                        <td style='text-align:center'><img src='images/searching.gif' ></td>
                    </tr>
                </table>
                </center>
            </div>
        </td>
      </tr>
      <tr>
        <td colspan="2">
        	<div style="padding:2px;">
                <div id='bunkerpriceresults'>
                    <div id='bunkerprice_tab_wrapperonly'></div>
                </div>
            </div>
        </td>
      </tr>
    </table>
    </form>
    
    <script type="text/javascript">
    jQuery("#bunkerportname_id").focus().autocomplete(ports);
    jQuery("#bunkerportname_id").setOptions({
        scrollHeight: 180
    });
    
    $("#btn_cancelsearch_bunkerprice_id").click(function(){
        jQuery("#btn_cancelsearch_bunkerprice_id").val("CANCELING SEARCH...");
        jQuery("#btn_search_bunkerprice_id").hide();
        location.reload();
    });
    </script>
    <!--END OF BUNKER PRICE-->
</div>

<div id="weather_id" style="max-width:1300px; height:auto; margin:0 auto; display:none;">
	<!--WEATHER-->
    <table width="100%" border="0" cellpadding="2" cellspacing="2" style='margin-bottom:5px;'>
        <tr style="padding-bottom:10px;">
            <td width="100%" align="center" colspan="3"><div style='padding:20px;'><a onclick='showMapW();' class='clickable'>view larger map</a></div></td>
        </tr>
        <tr style='background:#999;'>
            <th width="100%" align="center" colspan="3"><div style='padding:20px;'><iframe src='' id="map_iframew" width='990' height='500'></iframe></div></th>
        </tr>
        
        <div id="mapdialog2w" title="MAP" style='display:none;'>
            <iframe id="mapiframe2w" name='mapnamew' frameborder=0 height="100%" width="100%" style='border:0px; height:100%; width:100%'></iframe>
        </div>
        
        <script type="text/javascript">
        jQuery("#mapdialog2w" ).dialog( { width: '100%', height: jQuery(window).height()*0.9 });
        jQuery("#mapdialog2w").dialog("close");
        
        function showMapW(){
            jQuery("#mapiframe2w")[0].src='http://map.openseamap.org/map/weather.php';
            jQuery("#mapdialog2w").dialog("open");
        }
        </script>
        
    </table>
    <!--END OF WEATHER-->
</div>
</body>
</html>