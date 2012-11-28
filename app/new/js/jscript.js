function showPopup(action, userid) {
	jQuery('#popup').fadeIn('fast');
	jQuery('#window').fadeIn('fast');
}
function Close_Popup() {
	jQuery('#popup').fadeOut('fast');
	jQuery('#window').fadeOut('fast');
}

function toggleParams(t){
	icon = $("#paramicon")[0].src;
	if(t=='up'){
		$("#paramicon")[0].src = 'images/down.png';
	}
	else{
		if(icon.indexOf('images/up.png')!=-1){
			$("#paramicon")[0].src = 'images/down.png';
		}
		else{
			$("#paramicon")[0].src = 'images/up.png';
		}
	}
}
function toggleSearchResults(t){
	icon = $("#searchricon")[0].src;
	if(t=='up'){
		$("#searchricon")[0].src = 'images/down.png';
	}
	else{
		if(icon.indexOf('images/up.png')!=-1){
			$("#searchricon")[0].src = 'images/down.png';
		}
		else{
			$("#searchricon")[0].src = 'images/up.png';
		}
	}
}

function openMap(xfocus, details, xcategory){
	jQuery("#mapiframe")[0].src='map/index.php?focus='+xfocus+'&details='+details+"&xcategory="+xcategory+"&t="+(new Date()).getTime();
	jQuery("#mapdialog").dialog("open");
}

function openMap2(xfocus, details, xcategory){
	jQuery("#mapiframe_brokers")[0].src='map/index_brokers.php?focus='+xfocus+'&details='+details+"&xcategory="+xcategory+"&t="+(new Date()).getTime();
	jQuery("#mapdialog_brokers").dialog("open");
}

function openCargoMap(record_id){
	jQuery("#mapcargoiframe")[0].src='map/cargo_map.php?record_id='+record_id;
	jQuery("#mapcargodialog").dialog("open");
}

function contactOwner(imo){
	jQuery("#contactiframe")[0].src='search_ajax.php?contact=1&imo='+imo;
	jQuery("#contactdialog").dialog("open");
}
