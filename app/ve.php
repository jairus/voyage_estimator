<?php
@session_start();
@include_once(dirname(__FILE__)."/includes/database.php");
@include_once(dirname(__FILE__)."/includes/distanceCalc.class.php");
$link = dbConnect();


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
		//$str = $r[$i]['data'];
		//$matches = array();
		//preg_match_all("/<name>(.*)<\/name>/iUs", $str, $matches);
		$ship = array();
		$ship['name'] = $r[$i]['imo']." - ".$r[$i]['name'];
		$ship['mmsi'] = $r[$i]['mmsi'];
		$ship['imo'] = $r[$i]['imo'];
		$ship['dwt'] = $r[$i]['summer_dwt'];
		$ship['speed'] = $r[$i]['speed'];
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
	vertical-align:middle;
	padding:2px;
	border: 1px #C4C4C4;
	text-align:left;
}
th{
	vertical-align:top;
	padding:2px;
	font-weight:bold;
}
*{
	font-size:11px;
	font-family:Verdana, Arial, Helvetica, sans-serif
}
table{
	border-collapse: collapse;
}
.input{
	background:#FEFEFE;
}
.calculated{
	background:#bbd4de;
	text-align: center;
}
.empty{
	background:#BBB57B;
}
.label{
	background:#e9e9e9;
}
.bold{
	font-weight:bold;
}
input[type="text"]{
	/*
	border: 0px;
	background:yellow;
	*/
	height:100%;
	width:90%;
	text-align:center;
}

.number{
}

.general{
}
</style>
<script>
function ownerDetails(owner, owner_id){
	var iframe = $("#contactiframe");

	$(iframe).contents().find("body").html("");
	
	jQuery("#contactiframe")[0].src='search_ajax.php?contact=1&owner='+owner+'&owner_id='+owner_id;
	jQuery("#contactdialog").dialog("open");
}

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
var speeds = [];
var sfs = [];

var gimo = "";
$(function(){

	jQuery( "#shipdetails" ).dialog( { width: '90%', height: jQuery(window).height()*0.9 });
	jQuery( "#shipdetails" ).dialog("close");	


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
					speeds[val.imo] = val.speed;
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
			jQuery("#shipdetailshref").html("<a style='cursor:pointer;' onclick='showShipDetails()'><u>VIEW VESSEL DETAILS</u></a>");
			setValue(jQuery("#d18"), fNum(dwts[imo]));
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
	ctotal1 = ctoll1 + cbook1 + ctug1 + cmisc1;
	setValue(jQuery("#ctotal1"), fNum(ctotal1))
	 
	ctoll2 = uNum(getValue(jQuery("#ctoll2")));
	cbook2 = uNum(getValue(jQuery("#cbook2")));
	ctug2 = uNum(getValue(jQuery("#ctug2")));
	cline2 = uNum(getValue(jQuery("#cline2")));
	cmisc2 = uNum(getValue(jQuery("#cmisc2")));
	ctotal2 = ctoll2 + cbook2 + ctug2 + cmisc2;
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
			jQuery(this).width(w);
		}
		
		
	});

	jQuery('.general').blur(function(){
		w = jQuery(this).val().length * 8;
		if(w > jQuery(this).parent().width()){
			jQuery(this).width(w);
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

</script>
</head>
<body>
<div id="shipdetails" title="SHIP DETAILS" style='display:none; padding-bottom:10px'>
	<div id='shipdetails_in' ></div>
</div>
<table width="696"  border="1">
  <tr>
    <td width="258"><strong>VESSEL SELECT<br />
    (IMO or VESSEL NAME)</strong></td>
    <td width="422"><input type='text' id='ship' style='width:200px;' />&nbsp;<span id='shipdetailshref'></span></td>
  </tr>
</table>

<br />
<br />
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
<br />
<br />
</div>

<table cellspacing="0" cellpadding="0">
  <col width="164" />
  <col width="125" />
  <col width="159" />
  <col width="138" />
  <col width="159" />
  <col width="126" />
  <col width="193" />
  <col width="119" />
  <col width="105" />
  <col width="83" />
  <col width="180" />
  <col width="119" />
  <col width="90" />
  <col width="70" />
  <col width="63" />
  <col width="71" />
  <col width="62" />
  <col width="72" />
  <col width="95" />
  <tr height="21">
    <td width="164" height="21" class="bold">Voyage    Legs</td>
    <td width="125"></td>
    <td width="159"></td>
    <td width="138"></td>
    <td width="159"></td>
    <td width="126"></td>
    <td width="193"></td>
    <td width="119"></td>
    <td width="105"></td>
    <td width="83"></td>
    <td width="180"></td>
    <td width="209" colspan="2" class="bold">*Option    to Load &amp; Bunker concurrently</td>
    <td width="204" colspan="3" class="bold">PORT DAYS</td>
    <td width="229" colspan="3" class="bold">SEA DAYS</td>
  </tr>
  <tr height="20">
    <td height="20" class="label"><strong>TYPE</strong></td>
    <td class="label"><strong>LOAD PORT</strong></td>
    <td class="label"><strong>DATE</strong></td>
    <td class="label"><strong>DESTINATION PORT</strong></td>
    <td class="label"><strong>DATE</strong></td>
    <td class="label"><strong>SPEED (knts)</strong></td>
    <td class="label"><strong>DISTANCE (miles)</strong></td>
    <td class="label"><strong>CARGO</strong></td>
    <td class="label">SF</td>
    <td class="label"><strong>QUANTITY (MT)</strong></td>
    <td class="label"><strong>VOLUME (M3)</strong></td>
    <td class="label"><strong>L/D RATE (MT/day)</strong></td>
    <td class="label"><strong>WORKING DAYS</strong></td>
    <td class="label">L/D</td>
    <td class="label"><strong>TURN TIME</strong></td>
    <td class="label"><strong>IDLE/EXTRA DAYS</strong></td>
    <td class="label"><strong>SEA </strong></td>
    <td class="label"><strong>CANAL DAYS</strong></td>
    <td class="label"><strong>WEATHER/EXTRA DAYS</strong></td>
  </tr>
  <tr height="17" id='ballast1'>
    <td height="17" class='general b31'><strong>Ballast</strong></td>
    <td class='input'><input type='text' class='general c31' />
    </td>
    <td class="input"><input type='text' class='general d31' /></td>
    <td class='input'><input type='text' class='general e31' />
    </td>
    <td class='calculated general f31'></td>
    <td class='input'><input type='text' class='number g31' /></td>
    <td class="calculated number h31" ></td>
    <td class='number i31'></td>
    <td class='number j31'></td>	
    <td class='number k31'></td>
    <td class='number l31'></td>
    <td class='number m31'></td>
    <td class='number n31'></td>
    <td class="number o31"></td>
    <td class='number p31'></td>
    <td class='number q31'></td>
    <td class="calculated number r31" ></td>
    <td class='empty'><input type='text' class='number s31' /></td>
    <td class='empty'><input type='text' class='number t31' /></td>
  </tr>
  <tr height="17" id='loading1'>
    <td height="17" class='general b32'><strong>Loading</strong></td>
    <td class='general c32'></td>
    <td class='general d32'></td>
    <td class='general e32'></td>
    <td class="calculated f32"></td>
    <td class='number g32'></td>
    <td class="number h32"></td>
    <td class='input'><input type='text' class='general i32' /></td>
    <td class='number j32' ></td>
    <td class='input'><input type='text' class='number k32' /></td>
    <td class='calculated number l32'></td>
    <td class='input'><input type='text'  class='number m32'  /></td>
    <td class='input'>
	<select class='general n32' >
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
    <td class="calculated number o32"></td>
    <td class='input'><input type='text' class='number p32' /></td>
    <td class='input'><input type='text' class='number q32' /></td>
    <td class="number r32"></td>
    <td class='empty'><input type='text' class='number s32'  /></td>
    <td class='empty'><input type='text' class='number t32' /></td>
  </tr>
  <tr height="17" id='bunkerstop1'>
    <td height="17" class='general b33'><strong>Bunker Stop</strong></td>
    <td class='input general c33'></td>
    <td class='general d33'></td>
    <td class='input'><input type='text' class='general e33' /></td>
    <td class="calculated f33"></td>
    <td class='input'><input type='text' class='number g33' /></td>
    <td class="calculated h33"></td>
    <td class='number i33'></td>
    <td class='number j33'></td>
    <td class='number k33'></td>
    <td class='input'><input type='text'  class='number l33'  /></td>
    <td class='input'><input type='text'  class='number m33'  /></td>
    <td class='input'><select class='general n33' >
	<option value='SHINC'>SHINC</option>
	<option value='SATSHINC or SSHINC'>SATSHINC or SSHINC</option>
	<option value='SHEX'>SHEX</option>
	<option value='SA/SHEX or SATPMSHEX'>SA/SHEX or SATPMSHEX</option>
	<option value='SATSHEX or SSHEX'>SATSHEX or SSHEX</option>
	<option value='SHEXEIU or SHEXEIUBE or SHEXUU'>SHEXEIU or SHEXEIUBE or SHEXUU</option>
	<option value='FHINC'>FHINC</option>
	<option value='FHEX'>FHEX</option>
	</select></td>
    <td class="calculated o33"></td>
    <td class='input'><input type='text' class='number p33' /></td>
    <td class='input'><input type='text'  class='number q33'  /></td>
    <td class="calculated number r33"></td>
    <td class='empty'><input type='text'  class='number s33' /></td>
    <td class='empty'><input type='text'  class='number t33' /></td>
  </tr>
  <tr height="17" id='laden1'>
    <td height="17" class='general b34'><strong>Laden</strong></td>
    <td class='input general c34'></td>
    <td class='general d34'></td>
    <td class='input'><input type='text' class='general e34' /></td>
    <td class="calculated f34"></td>
    <td class='input'><input type='text' class='number g34' /></td>
    <td class="calculated number h34"></td>
    <td class='number i34'></td>
    <td class='number j34'></td>
    <td class='number k34'></td>
    <td class='number l34'></td>
    <td class='number m34'></td>
    <td class='number n34'></td>
    <td class="number o34"></td>
    <td class='number p34'></td>
    <td class='number q34'></td>
    <td class="calculated number r34"></td>
    <td class='empty'><input type='text' class='number s34' /></td>
    <td class='empty'><input type='text' class='number t34' /></td>
  </tr>
  <tr height="18" id='discharging1'>
    <td height="18" class='general b35'><strong>Discharging</strong></td>
    <td class='input general c35'></td>
    <td class='general d35'></td>
    <td class='general e35'></td>
    <td class="calculated f35"></td>
    <td class='number g35'></td>
    <td class="number h35"></td>
    <td class='input'><input type='text' class='general i35' /></td>
    <td class='number j35'></td>
    <td class='input'><input type='text' class='number k35' /></td>
    <td class='calculated number l35'></td>
    <td class='input'><input type='text'  class='number m35'  /></td>
    <td class='input'><select class='general n35' >
	<option value='SHINC'>SHINC</option>
	<option value='SATSHINC or SSHINC'>SATSHINC or SSHINC</option>
	<option value='SHEX'>SHEX</option>
	<option value='SA/SHEX or SATPMSHEX'>SA/SHEX or SATPMSHEX</option>
	<option value='SATSHEX or SSHEX'>SATSHEX or SSHEX</option>
	<option value='SHEXEIU or SHEXEIUBE or SHEXUU'>SHEXEIU or SHEXEIUBE or SHEXUU</option>
	<option value='FHINC'>FHINC</option>
	<option value='FHEX'>FHEX</option>
	</select></td>
    <td class="calculated number o35"></td>
    <td class='input'><input type='text' class='number p35' /></td>
    <td class='input'><input type='text'  class='number q35' /></td>
    <td class="number r35"></td>
    <td class='empty'><input type='text' class='number s35' /></td>
    <td class='empty'><input type='text'  class='number t35' /></td>
  </tr>
  <tr height="21">
    <td height="21" colspan="13" class="label"><strong>PORT/SEA DAYS</strong></td>
    <td colspan="3" class="calculated" id='o36'></td>
    <td colspan="3" class="calculated" id='r36'></td>
  </tr>
  <tr height="21">
    <td height="21" colspan="13" class="label"><strong>TOTAL VOYAGE DAYS</strong></td>
    <td colspan="6" class="calculated" id='o37'></td>
  </tr>
</table>
<br ><br>


<table cellspacing="0" cellpadding="0">
  <col width="164" />
  <col width="125" />
  <col width="159" />
  <col width="138" />
  <col width="159" />
  <col width="126" />
  <col width="193" />
  <col width="119" />
  <tr height="17">
    <td width="164" height="53" rowspan="3" class="bold">BUNKER</td>
    <td width="125" class="label">FO Type</td>
    <td colspan="3" width="456"></td>
    <td width="126" class="label">DO Type</td>
    <td colspan="2" width="312"></td>
  </tr>
  <tr height="18">
    <td height="18" class="label">FO Price ($)</td>
    <td colspan="3" class="input"><input type='text'  id='d42' class='number' /></td>
    <td class="label">DO Price ($)</td>
    <td colspan="2" class="input"><input type='text'  id='h42' class='number' /></td>
  </tr>
  <tr height="18">
    <td height="18" class="label">FO/Ballast</td>
    <td class="label">FO/Laden</td>
    <td class="label">FO/Port</td>
    <td class="label">FO/Reserve</td>
    <td class="label">DO/Sea</td>
    <td class="label">DO/Port</td>
    <td class="label">DO/Reserve</td>
  </tr>
  <tr height="17">
    <td height="17" class="label">Consumption    (MT/day)</td>
    <td class='input'><input type='text'  id='c44' class='number' /></td>
    <td class='input'><input type='text'  id='d44' class='number' /></td>
    <td class='input'><input type='text'  id='e44' class='number' /></td>
    <td class='input number' id='f44'></td>
    <td class='input'><input type='text'  id='g44' class='number' /></td>
    <td class='input'><input type='text'  id='h44' class='number' /></td>
    <td class='general' id='i44'></td>
  </tr>
  <tr height="17">
    <td height="17" class="label"><strong>Total Consumption (MT)</strong></td>
    <td class="calculated" id='c45'></td>
    <td class="calculated" id='d45' ></td>
    <td class="calculated" id='e45'></td>
    <td class='input'><input type='text'  id='f45' class='number' /></td>
    <td class="calculated" id='g45'></td>
    <td class="calculated" id='h45'></td>
    <td class='input'><input type='text'  id='i45' class='number' /></td>
  </tr>
  <tr height="18">
    <td height="18" class="label"><strong>Expense ($)</strong></td>
    <td class="calculated" id='c46'></td>
    <td class="calculated" id='d46'></td>
    <td class="calculated" id='e46'></td>
    <td class="calculated" id='f46'></td>
    <td class="calculated" id='g46'></td>
    <td class="calculated" id='h46'></td>
    <td class="calculated"  id='i46'></td>
  </tr>
  <tr height="21">
    <td height="21" class="label"><strong>Total ($)</strong></td>
    <td colspan="4" class="calculated" id='c47'></td>
    <td colspan="3" class="calculated" id='g47'></td>
  </tr>
</table>
<br />
<br />

<table cellspacing="0" cellpadding="0">
      <col width="164" />
      <col width="125" />
      <col width="140" />
      <col width="140" />
      <tr height="18">
        <td height="18" colspan="3" class="bold">DWCC</td>
      </tr>
	  <tr height="18">
        <td height="18" colspan="2" class="label"><strong>DW (MT)</strong></td>
        <td width="155" class='calculated number' id='d18'></td>
        <td width="140" class='label'><strong>Calculated Amount Reference</strong></td>
      </tr>
      <tr height="17">
        <td width="144" height="34" rowspan="2" class="label">Consumption (MT)</td>
        <td width="151" class="label">FO</td>
        <td class='input'><input type='text' class='number' id='d19'/></td>
        <td class='calculated general' id='d19b'></td>
      </tr>
      <tr height="17">
        <td height="17" class="label">DO</td>
        <td class='input'><input type='text' class='number'  id='d20' /></td>
        <td class='calculated general' id='d20b'></td>
      </tr>
      <tr height="17">
        <td height="34" rowspan="2" class="label">Reserve (MT)</td>
        <td class="label">FO</td>
        <td class='input'><input type='text' class='number'  id='d21'/></td>
        <td class='calculated general' id='d21b'></td>
      </tr>
      <tr height="17">
        <td height="17" class="label">DO</td>
        <td class='input'><input type='text' class='number'  id='d22'/></td>
        <td class='calculated general' id='d22b'></td>
      </tr>
      <tr height="17">
        <td height="17" colspan="2" class="label">FW (MT)</td>
        <td class='input'><input type='text' class='number'  id='d23' /></td>
        <td class='calculated general' id='d23b'></td>
      </tr>
      <tr height="18">
        <td height="18" colspan="2" class="label">Constant (MT)</td>
        <td class='input'><input type='text' class='number'  id='d24'/></td>
        <td class='calculated general' id='d24b'></td>
      </tr>
      <tr height="18">
        <td height="18" colspan="2" class="label"><strong>Used DW (MT)</strong></td>
        <td class='calculated number' id='d25'></td>
      </tr>
      <tr height="18">
        <td height="18" colspan="2" class="label"><strong>DWCC (MT)</strong></td>
        <td class='calculated number' id='d26'></td>

      </tr>
</table>
<br><br>


	<table cellspacing="0" cellpadding="0">
	  <col width="164" />
	  <col width="125" />
	  <col width="159" />
	  <col width="138" />
	  <col width="159" />
	  <col width="126" />
	  <col width="193" />
	  <tr height="18">
	    <td width="164" height="18" align="center" class="bold">PORT</td>
	    <td width="125">&nbsp;</td>
	    <td width="159"></td>
	    <td width="138"></td>
	    <td width="138"></td>
	    <td width="159" class="bold">Canal</td>
	    <td width="126">&nbsp;</td>
	    <td width="193"></td>
	  </tr>
	  <tr height="18">
	    <td height="18" class="label">Laytime (hrs)</td>
	    <td class='input'><input type='text' id='c51' class='number'  /></td>
	    <td></td>
	    <td></td>
	    <td></td>
	    <td class="label">Canal</td>
	    <td></td>
	    <td>		
		<select id='canal'>
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
		</select></td>
	  </tr>
	  <tr height="17">
	    <td height="17" class="label">Dem ($/day)</td>
	    <td class='input'><input type='text' id='c52' class='number'  /></td>
	    <td>pro rated</td>
	    <td></td>
	    <td></td>
	    <td class="label">Tolls ($)</td>
	    <td class='empty'><input type='text' id='ctoll1' class='number' /></td>
	    <td class='empty'><input type='text' id='ctoll2' class='number' /></td>
	  </tr>
	  <tr height="17">
	    <td height="17" class="label">Term</td>
	    <td><select id='term'>
			<option value='DHDLTSBENDS' >DHDLTSBENDS</option>
			<option value='DHDATSBENDS' >DHDATSBENDS</option>
			<option value='DHDWTSBENDS' >DHDWTSBENDS</option>
		</select>
		</td>
	    <td></td>
	    <td></td>
	    <td></td>
	    <td class="label">Booking Fee ($)</td>
	    <td class='empty'><input type='text' id='cbook1' class='number' /></td>
	    <td class='empty'><input type='text' id='cbook2' class='number' /></td>
	  </tr>
	  <tr height="17">
	    <td height="17" class="label"><strong>Des ($/day)</strong></td>
	    <td class="calculated" id='c54'>&nbsp;</td>
	    <td></td>
	    <td></td>
	    <td></td>
	    <td class="label">Tugs ($)</td>
	    <td class='empty'><input type='text' id='ctug1' class='number'  /></td>
	    <td class='empty'><input type='text' id='ctug2' class='number' /></td>
	  </tr>
	  <tr height="18">
	    <td height="18" class="label">Liner Terms</td>
	    <td>
		<select id='linerterms'>
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
	    <td></td>
	    <td></td>
	    <td></td>
	    <td class="label">Line Handlers ($)</td>
	    <td class='empty'><input type='text' id='cline1' class='number' /></td>
	    <td class='empty'><input type='text' id='cline2' class='number' /></td>
	  </tr>
	  <tr height="18">
	    <td height="18" class="label">Port</td>
	    <td class='port1' id='port1'>Port 1 </td>
	    <td class='port2' id='port2'>Port 2</td>
	    <td class='port3' id='port3'>Port 3 </td>
	    <td></td>
	    <td class="label">Miscellaneous ($)</td>
	    <td class='empty'><input type='text' id='cmisc1' class='number'  /></td>
	    <td class='empty'><input type='text' id='cmisc1' class='number' /></td>
	  </tr>
	  <tr height="18">
	    <td height="18" class="label">Dues ($)</td>
	    <td class='input port1'><input type='text' class='number dues' /></td>
	    <td class='input port2'><input type='text' class='number dues' /></td>
	    <td class='input port3'><input type='text' class='number dues' /></td>
	    <td></td>
	    <td class="label"><strong>Total ($)</strong></td>
	    <td class="calculated" id='ctotal1'></td>
	    <td class="calculated" id='ctotal2'></td>
	  </tr>
	  <tr height="17">
	    <td height="17" class="label">Pilotage ($)</td>
	    <td class='input port1'><input type='text' class='number pilotage' /></td>
	    <td class='input port2'><input type='text' class='number pilotage' /></td>
	    <td class='input port3'><input type='text' class='number pilotage' /></td
	    ><td></td>
	    <td></td>
	    <td></td>
	    <td></td>
	  </tr>
	  <tr height="17">
	    <td height="17" class="label">Tugs ($)</td>
	    <td class='input port1'><input type='text' class='number tugs' /></td>
	    <td class='input port2'><input type='text' class='number tugs' /></td>
	    <td class='input port3'><input type='text' class='number tugs' /></td
	    ><td></td>
	    <td></td>
	    <td></td>
	    <td></td>
	  </tr>
	  <tr height="17">
	    <td height="17" class="label">Bunker    Adjustment ($)</td>
	    <td class='input port1'><input type='text' class='number bunkeradjustment' /></td>
	    <td class='input port2'><input type='text' class='number bunkeradjustment' /></td>
	    <td class='input port3'><input type='text' class='number bunkeradjustment' /></td
	    ><td></td>
	    <td></td>
	    <td></td>
	    <td></td>
	  </tr>
	  <tr height="17">
	    <td height="17" class="label">Mooring ($)</td>
	    <td class='input port1'><input type='text' class='number mooring' /></td>
	    <td class='input port2'><input type='text' class='number mooring' /></td>
	    <td class='input port3'><input type='text' class='number mooring' /></td
	    ><td></td>
	    <td></td>
	    <td></td>
	    <td></td>
	  </tr>
	  <tr height="17">
	    <td height="17" class="label">Dockage ($)</td>
	    <td class='input port1'><input type='text' class='number dockage' /></td>
	    <td class='input port2'><input type='text' class='number dockage' /></td>
	    <td class='input port3'><input type='text' class='number dockage' /></td
	    ><td></td>
	    <td></td>
	    <td></td>
	    <td></td>
	  </tr>
	  <tr height="17">
	    <td height="17" class="label">Load/Discharge    ($)</td>
	    <td class='input port1'><input type='text' class='number loaddischarge' /></td>
	    <td class='input port2'><input type='text' class='number loaddischarge' /></td>
	    <td class='input port3'><input type='text' class='number loaddischarge' /></td
	    ><td></td>
	    <td></td>
	    <td></td>
	    <td></td>
	  </tr>
	  <tr height="17">
	    <td height="17" class="label">Agency Fee    ($)</td>
	    <td class='input port1'><input type='text' class='number agencyfee' /></td>
	    <td class='input port2'><input type='text' class='number agencyfee' /></td>
	    <td class='input port3'><input type='text' class='number agencyfee' /></td
	    ><td></td>
	    <td></td>
	    <td></td>
	    <td></td>
	  </tr>
	  <tr height="17">
	    <td height="17" class="label">Miscellaneous    ($)</td>
	    <td class='input port1'><input type='text' class='number miscellaneous' /></td>
	    <td class='input port2'><input type='text' class='number miscellaneous' /></td>
	    <td class='input port3'><input type='text' class='number miscellaneous' /></td
	    ><td></td>
	    <td></td>
	    <td></td>
	    <td></td>
	  </tr>
	  <tr height="17">
	    <td height="17" class="label"><strong>Demurrage ($)</strong></td>
	    <td colspan="2" class="calculated" id='c66'><strong>0.00</strong></td>
	    <td></td>
	    <td></td>
	    <td></td>
	    <td></td>
	    <td></td>
	  </tr>
	  <tr height="18">
	    <td height="18" class="label"><strong>Despatch ($)</strong></td>
	    <td colspan="2" class="calculated" id='c67'><strong>48,849.31</strong></td>
	    <td></td>
	    <td></td>
	    <td></td>
	    <td></td>
	    <td></td>
	  </tr>
	  <tr height="18">
	    <td height="18" class="label"><strong>Total ($)</strong></td>
	    <td colspan="2" class="calculated" id='c68'></td>
	    <td></td>
	    <td></td>
	    <td></td>
	    <td></td>
	    <td></td>
	  </tr>
	</table>
	<br />
	<br />
	<table cellspacing="0" cellpadding="0">
	  <col width="164" />
	  <col width="125" />
	  <col width="159" />
	  <col width="138" />
	  <col width="159" />
	  <col width="126" />
	  <col width="193" />
	  <col width="119" />
	  <col width="105" />
	  <col width="83" />
	  <col width="180" />
	  <col width="119" />
	  <tr height="21">
	    <td width="164" height="21" class="bold">VOYAGE DISBURSMENTS</td>
	    <td width="125">&nbsp;</td>
	    <td width="159">&nbsp;</td>
	    <td width="138">&nbsp;</td>
	    <td width="159" class="bold">Voyage</td>
	    <td width="126">&nbsp;</td>
	    <td width="193">&nbsp;</td>
	    <td width="119"></td>
	    <td width="105"></td>
	    <!--
		<td width="83" class="bold">Operating</td>
	    <td width="299" colspan="2" class="bold">Capital</td>
	  	-->
	  </tr>
	  <tr height="18">
	    <td height="18" class="label"><strong>Bunker ($)</strong></td>
	    <td class="label"><strong>Port ($)</strong></td>
	    <td class="label"><strong>Canal($)</strong></td>
	    <td class="label">Add. Insurance ($)</td>
	    <td class="label">ILOHC</td>
	    <td class="label">ILOW</td>
	    <td class="label">CVE</td>
	    <td class="label">Ballast Bonus</td>
	    <td class="label">Miscellaneous</td>
	    <!--
		<td class="label">Running Cost ($/day)</td>
	    <td class="label">Loan Repayment / Charter Hire ($/mth)</td>
	    <td class="label">Interest Exp ($/yr)</td>
	  	-->
	  </tr>
	  <tr height="18">
	    <td height="18" class="calculated" id='b74'></td>
	    <td class="calculated" id='c74'><strong>161,150.69</strong></td>
	    <td class="calculated" id='d74'><strong>150,000.00</strong></td>
	    <td class='input'><input type='text' class='number' id='e74'  /></td>
	    <td class='input'><input type='text' class='number' id='f74' /></td>
	    <td class='input'><input type='text' class='number' id='g74' /></td>
	    <td class='input'><input type='text' class='number' id='h74' /></td>
	    <td class='input'><input type='text' class='number' id='i74' /></td>
	    <td class='input'><input type='text' class='number' id='j74' /></td>
	    <!--
		<td class='input'><input type='text'  /></td>
	    <td class='input'><input type='text'  /></td>
	    <td class='input'><input type='text'  /></td>
	  `-->
	  </tr>
	  <tr height="21">
	    <td height="21" colspan="9" class="calculated" id='b75'></td>
	    <!--
		<td class="calculated" id='k75'><strong>202,893.32</strong></td>
	    <td colspan="2" class="calculated" id='l75'><strong>2,067,625.00</strong></td>
	  	-->
	  </tr>
	</table>
	<br />
	<br />
	<table cellspacing="0" cellpadding="0">
	  <col width="164" />
	  <col width="125" />
	  <col width="159" />
	  <col width="138" />
	  <col width="159" />
	  <col width="126" />
	  <col width="193" />
	  <col width="119" />
	  <tr height="21">
	    <td width="164" height="21" class="bold">RESULT    1</td>
	    <td width="125"></td>
	    <td width="159"></td>
	    <td width="138"></td>
	    <td width="159"></td>
	    <td width="126"></td>
	    <td width="193"></td>
	    <td width="119"></td>
	  </tr>
	  <tr height="18">
	    <td height="18" class="label">Freight Rate ($/MT)</td>
	    <td class="label">Gross Freight ($)</td>
	    <td class="label">Brok. Comm ($)</td>
	    <td class="label">Add. Comm ($)</td>
	    <td class="label">Gross Income ($)</td>
	    <td class="label">TCE ($/day)</td>
	    <!--
		<td class="label">BE TCE ($/day)</td>
	    <td class="label">Market TCE ($/day)</td>
	 	-->
	  </tr>
	  <tr height="18">
	    <td height="18" class='empty'><input type='text' class='number' id='b80' /></td>
	    <td class="calculated" id='c80'></td>
	    <td><input type='text' class='number' id='d80' /></td>
	    <td><input type='text' class='number' id='e80' /></td>
	    <td class="calculated" id='f80'></td>
	    <td align="right" class="calculated" id='g80'></td>
	    <!--
		<td class="calculated" id='h80'></td>
	    <td class='empty'><input type='text' class='number' id='i80' /></td>
	 	-->
	  </tr>
	  <tr height="18">
	    <td height="18"></td>
	    <td></td>
	    <td colspan="2" class="calculated" id='d81'></td>
	    <td></td>
	    <td></td>
	    <td></td>
	    <td></td>
	  </tr>
	</table>
	<br />
	<br />
	<table cellspacing="0" cellpadding="0">
	  <col width="164" />
	  <col width="125" />
	  <col width="159" />
	  <col width="138" />
	  <col width="159" />
	  <col width="126" />
	  <col width="193" />
	  <col width="119" />
	  <tr height="21">
	    <td width="164" height="21" class="bold">RESULT    2</td>
	    <td width="125"></td>
	    <td width="159"></td>
	    <td width="138"></td>
	    <td width="159"></td>
	    <td width="126"></td>
	    <td width="193"></td>
	    <td width="119"></td>
	  </tr>
	  <tr height="21">
	    <td height="21" class="label">Freight Rate ($/MT)</td>
	    <td class="label">Gross Freight ($)</td>
	    <td class="label">Brok. Comm ($)</td>
	    <td class="label">Add. Comm ($)</td>
	    <td class="label">Gross Income ($)</td>
	    <td class="label">TCE ($/day)</td>
	    <!--
		<td class="label">BE TCE ($/day)</td>
	    <td class="label">Market TCE ($/day)</td>
	 	-->
	  </tr>
	  <tr height="21">
	    <td height="21" class="calculated" id='b85'></td>
	    <td class="calculated"  id='c85'></td>
	    <td><input type='text' class='number' id='d85'/></td>
	    <td><input type='text' class='number' id='e85'/></td>
	    <td class="calculated"  id='f85'></td>
	    <td class='empty'><input type='text' class='number' id='g85'/></td>
	    <!--
		<td class="calculated"  id='h85'>55,953.50</td>
	    <td class='empty'><input type='text'  /></td>
	 	-->
	  </tr>
	  <tr height="21">
	    <td height="21"></td>
	    <td></td>
	    <td colspan="2" class="calculated"  id='d86'></td>
	    <td></td>
	    <td></td>
	    <td></td>
	    <td></td>
	  </tr>
	</table>

</body>
</html>
