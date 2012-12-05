<?php
@session_start();
date_default_timezone_set('UTC');

include_once(dirname(__FILE__)."/includes/bootstrap.php");

$link = dbConnect();

if(trim($_GET['portname']) && !trim($_GET['countryname'])){
	$sql = "SELECT 
		wpi_data.main_port_name, 
		wpi_data.latitude_degrees, 
		wpi_data.latitude_minutes, 
		wpi_data.latitude_hemisphere, 
		wpi_data.longitude_degrees, 
		wpi_data.longitude_minutes, 
		wpi_data.longitude_hemisphere, 
		wpi_data.entrance_restriction_tide, 
		wpi_data.entrance_restriction_swell, 
		wpi_data.entrance_restriction_ice, 
		wpi_data.entrance_restriction_other, 
		wpi_data.overhead_limits, 
		wpi_data.channel_depth, 
		wpi_data.anchorage_depth, 
		wpi_data.cargo_pier_depth, 
		wpi_data.oil_terminal_depth, 
		wpi_data.tide, 
		wpi_data.maxsize_vessel_code, 
		wpi_data.good_holding_ground, 
		wpi_data.turning_area, 
		wpi_data.first_port_of_entry, 
		wpi_data.us_representative, 
		wpi_data.eta_message, 
		wpi_data.pilotage_compulsory, 
		wpi_data.pilotage_available, 
		wpi_data.pilotage_local_assist, 
		wpi_data.pilotage_advisable, 
		wpi_data.tugs_salvage, 
		wpi_data.tugs_assist, 
		wpi_data.quarantine_pratique, 
		wpi_data.quarantine_deratt_cert, 
		wpi_data.quarantine_other, 
		wpi_data.communications_telephone, 
		wpi_data.communications_telegraph, 
		wpi_data.communications_radio, 
		wpi_data.communications_radio_tel, 
		wpi_data.communications_air, 
		wpi_data.communications_rail, 
		wpi_data.load_offload_wharves, 
		wpi_data.load_offload_anchor, 
		wpi_data.load_offload_med_moor, 
		wpi_data.load_offload_beach_moor, 
		wpi_data.load_offload_ice_moor, 
		wpi_data.medical_facilities, 
		wpi_data.garbage_disposal, 
		wpi_data.degauss, 
		wpi_data.dirty_ballast, 
		wpi_data.cranes_fixed, 
		wpi_data.cranes_mobile, 
		wpi_data.cranes_floating, 
		wpi_data.services_longshore, 
		wpi_data.services_elect, 
		wpi_data.services_steam, 
		wpi_data.services_navig_equip, 
		wpi_data.services_elect_repair, 
		wpi_data.supplies_provisions, 
		wpi_data.supplies_water, 
		wpi_data.supplies_fuel_oil, 
		wpi_data.supplies_diesel_oil, 
		wpi_data.supplies_deck, 
		wpi_data.supplies_engine, 
		
		wpi_country_codes.country_name, 
		
		wpi_region.area_name, 
		
		wpi_harbor_size_lut.harbor_size, 
		
		wpi_harbor_type_lut.harbor_type_description, 
		
		wpi_shelter_afforded_lut.shelter_afforded_description, 
		
		wpi_repairs_code_lut.repairs_code_description, 
		
		wpi_drydock_code_lut.drydock_marine_railway_code_description, 
		
		wpi_marine_railway_code_lut.drydock_marine_railway_code_description 
		
		FROM 
		
		(`wpi_data` INNER JOIN `wpi_country_codes` ON wpi_data.wpi_country_code=wpi_country_codes.country_code) 
		INNER JOIN `wpi_region` ON wpi_data.region_index=wpi_region.world_port_index_number 
		INNER JOIN `wpi_harbor_size_lut` ON wpi_data.harbor_size_code=wpi_harbor_size_lut.harbor_size_code 
		INNER JOIN `wpi_harbor_type_lut` ON wpi_data.harbor_type_code=wpi_harbor_type_lut.harbor_type_code 
		INNER JOIN `wpi_shelter_afforded_lut` ON wpi_data.shelter_afforded_code=wpi_shelter_afforded_lut.shelter_afforded_code 
		INNER JOIN `wpi_repairs_code_lut` ON wpi_data.repair_code=wpi_repairs_code_lut.repairs_code 
		INNER JOIN `wpi_drydock_code_lut` ON wpi_data.drydock=wpi_drydock_code_lut.drydock_marine_railway_code 
		INNER JOIN `wpi_marine_railway_code_lut` ON wpi_data.railway=wpi_marine_railway_code_lut.drydock_marine_railway_code 
		
		WHERE 
		
		wpi_data.main_port_name='".trim($_GET['portname'])."' 
		
		ORDER BY wpi_data.id DESC LIMIT 0,50";
	$ports = dbQuery($sql, $link);
	
	$t = count($ports);
	
	if(trim($t)){
		$portint = array();
		
		for($i=0;$i<$t;$i++){
			$print = array();
			
			$port = $ports[$i];
			
			$sql = "SELECT latitude, longitude FROM `_veson_ports` WHERE name='".mysql_escape_string(trim($port['main_port_name']))."' ORDER BY id DESC LIMIT 0,1";
			$_sbis_port = dbQuery($sql, $link);
			$_sbis_port = $_sbis_port[0];
			
			if(trim($_sbis_port['latitude']) && trim($_sbis_port['longitude'])){
				$print['port_latitude']  = $_sbis_port['latitude'];
				$print['port_longitude'] = $_sbis_port['longitude'];
			}else{
				if($port['latitude_hemisphere']=="S"){
					$print['port_latitude'] = "-".$port['latitude_degrees'].".".$port['latitude_minutes'];
				}else if($port['latitude_hemisphere']=="N"){
					$print['port_latitude'] = $port['latitude_degrees'].".".$port['latitude_minutes'];
				}
				
				if($port['longitude_hemisphere']=="W"){
					$print['port_longitude'] = "-".$port['longitude_degrees'].".".$port['longitude_minutes'];
				}else if($port['longitude_hemisphere']=="E"){
					$print['port_longitude'] = $port['longitude_degrees'].".".$port['longitude_minutes'];
				}
				
				$print['port_latitude']  = $print['port_latitude'];
				$print['port_longitude'] = $print['port_longitude'];
			}
			
			$print['main_port_name']                            = $port['main_port_name'];
			$print['country_name']                              = $port['country_name'];
			$print['area_name']                                 = $port['area_name'];
			$print['harbor_size']                               = $port['harbor_size'];
			$print['harbor_type_description']                   = $port['harbor_type_description'];
			$print['shelter_afforded_description']              = $port['shelter_afforded_description'];
			$print['repairs_code_description']                  = $port['repairs_code_description'];
			$print['drydock_marine_railway_code_description_a'] = $port['drydock_marine_railway_code_description'];
			$print['drydock_marine_railway_code_description_b'] = $port['drydock_marine_railway_code_description'];
			
			if($port['entrance_restriction_tide']=='Y'){
				$print['entrance_restriction_tide'] = 'Yes';
			}else if($port['entrance_restriction_tide']=='N'){
				$print['entrance_restriction_tide'] = 'No';
			}else{
				$print['entrance_restriction_tide'] = 'N/A';
			}
			
			if($port['entrance_restriction_swell']=='Y'){
				$print['entrance_restriction_swell'] = 'Yes';
			}else if($port['entrance_restriction_swell']=='N'){
				$print['entrance_restriction_swell'] = 'No';
			}else{
				$print['entrance_restriction_swell'] = 'N/A';
			}
			
			if($port['entrance_restriction_ice']=='Y'){
				$print['entrance_restriction_ice'] = 'Yes';
			}else if($port['entrance_restriction_ice']=='N'){
				$print['entrance_restriction_ice'] = 'No';
			}else{
				$print['entrance_restriction_ice'] = 'N/A';
			}
			
			if($port['entrance_restriction_other']=='Y'){
				$print['entrance_restriction_other'] = 'Yes';
			}else if($port['entrance_restriction_other']=='N'){
				$print['entrance_restriction_other'] = 'No';
			}else{
				$print['entrance_restriction_other'] = 'N/A';
			}
			
			if($port['overhead_limits']=='Y'){
				$print['overhead_limits'] = 'Yes';
			}else if($port['overhead_limits']=='N'){
				$print['overhead_limits'] = 'No';
			}else{
				$print['overhead_limits'] = 'N/A';
			}
			
			if(trim($port['channel_depth'])=='A'){
				$print['channel_depth_f'] = '76ft - OVER';
				$print['channel_depth_m'] = '23.2m - OVER';
			}else if(trim($port['channel_depth'])=='B'){
				$print['channel_depth_f'] = '71ft - 75ft';
				$print['channel_depth_m'] = '21.6m - 22.9m';
			}else if(trim($port['channel_depth'])=='C'){
				$print['channel_depth_f'] = '66ft - 75ft';
				$print['channel_depth_m'] = '20.1m - 21.3m';
			}else if(trim($port['channel_depth'])=='D'){
				$print['channel_depth_f'] = '61ft - 65ft';
				$print['channel_depth_m'] = '18.6m - 19.8m';
			}else if(trim($port['channel_depth'])=='E'){
				$print['channel_depth_f'] = '56ft - 60ft';
				$print['channel_depth_m'] = '17.1m - 18.2m';
			}else if(trim($port['channel_depth'])=='F'){
				$print['channel_depth_f'] = '51ft - 55ft';
				$print['channel_depth_m'] = '15.5m - 16m';
			}else if(trim($port['channel_depth'])=='G'){
				$print['channel_depth_f'] = '46ft - 50ft';
				$print['channel_depth_m'] = '14m - 15.2m';
			}else if(trim($port['channel_depth'])=='H'){
				$print['channel_depth_f'] = '41ft - 45ft';
				$print['channel_depth_m'] = '12.5m - 13.7m';
			}else if(trim($port['channel_depth'])=='J'){
				$print['channel_depth_f'] = '36ft - 40ft';
				$print['channel_depth_m'] = '11m - 12.2m';
			}else if(trim($port['channel_depth'])=='K'){
				$print['channel_depth_f'] = '31ft - 35ft';
				$print['channel_depth_m'] = '9.4m - 10m';
			}else if(trim($port['channel_depth'])=='L'){
				$print['channel_depth_f'] = '26ft - 30ft';
				$print['channel_depth_m'] = '7.1m - 9.1m';
			}else if(trim($port['channel_depth'])=='M'){
				$print['channel_depth_f'] = '21ft - 25ft';
				$print['channel_depth_m'] = '6.4m - 7.6m';
			}else if(trim($port['channel_depth'])=='N'){
				$print['channel_depth_f'] = '16ft - 20ft';
				$print['channel_depth_m'] = '4.9m - 6.1m';
			}else if(trim($port['channel_depth'])=='O'){
				$print['channel_depth_f'] = '11ft - 15ft';
				$print['channel_depth_m'] = '3.4m - 4.6m';
			}else if(trim($port['channel_depth'])=='P'){
				$print['channel_depth_f'] = '6ft - 10ft';
				$print['channel_depth_m'] = '1.8m - 3m';
			}else if(trim($port['channel_depth'])=='Q'){
				$print['channel_depth_f'] = '0ft - 5ft';
				$print['channel_depth_m'] = '0m - 1.5m';
			}else{
				$print['channel_depth_f'] = 'N/A';
				$print['channel_depth_m'] = 'N/A';
			}
			
			if(trim($port['anchorage_depth'])=='A'){
				$print['anchorage_depth_f'] = '76ft - OVER';
				$print['anchorage_depth_m'] = '23.2m - OVER';
			}else if(trim($port['anchorage_depth'])=='B'){
				$print['anchorage_depth_f'] = '71ft - 75ft';
				$print['anchorage_depth_m'] = '21.6m - 22.9m';
			}else if(trim($port['anchorage_depth'])=='C'){
				$print['anchorage_depth_f'] = '66ft - 75ft';
				$print['anchorage_depth_m'] = '20.1m - 21.3m';
			}else if(trim($port['anchorage_depth'])=='D'){
				$print['anchorage_depth_f'] = '61ft - 65ft';
				$print['anchorage_depth_m'] = '18.6m - 19.8m';
			}else if(trim($port['anchorage_depth'])=='E'){
				$print['anchorage_depth_f'] = '56ft - 60ft';
				$print['anchorage_depth_m'] = '17.1m - 18.2m';
			}else if(trim($port['anchorage_depth'])=='F'){
				$print['anchorage_depth_f'] = '51ft - 55ft';
				$print['anchorage_depth_m'] = '15.5m - 16m';
			}else if(trim($port['anchorage_depth'])=='G'){
				$print['anchorage_depth_f'] = '46ft - 50ft';
				$print['anchorage_depth_m'] = '14m - 15.2m';
			}else if(trim($port['anchorage_depth'])=='H'){
				$print['anchorage_depth_f'] = '41ft - 45ft';
				$print['anchorage_depth_m'] = '12.5m - 13.7m';
			}else if(trim($port['anchorage_depth'])=='J'){
				$print['anchorage_depth_f'] = '36ft - 40ft';
				$print['anchorage_depth_m'] = '11m - 12.2m';
			}else if(trim($port['anchorage_depth'])=='K'){
				$print['anchorage_depth_f'] = '31ft - 35ft';
				$print['anchorage_depth_m'] = '9.4m - 10m';
			}else if(trim($port['anchorage_depth'])=='L'){
				$print['anchorage_depth_f'] = '26ft - 30ft';
				$print['anchorage_depth_m'] = '7.1m - 9.1m';
			}else if(trim($port['anchorage_depth'])=='M'){
				$print['anchorage_depth_f'] = '21ft - 25ft';
				$print['anchorage_depth_m'] = '6.4m - 7.6m';
			}else if(trim($port['anchorage_depth'])=='N'){
				$print['anchorage_depth_f'] = '16ft - 20ft';
				$print['anchorage_depth_m'] = '4.9m - 6.1m';
			}else if(trim($port['anchorage_depth'])=='O'){
				$print['anchorage_depth_f'] = '11ft - 15ft';
				$print['anchorage_depth_m'] = '3.4m - 4.6m';
			}else if(trim($port['anchorage_depth'])=='P'){
				$print['anchorage_depth_f'] = '6ft - 10ft';
				$print['anchorage_depth_m'] = '1.8m - 3m';
			}else if(trim($port['anchorage_depth'])=='Q'){
				$print['anchorage_depth_f'] = '0ft - 5ft';
				$print['anchorage_depth_m'] = '0m - 1.5m';
			}else{
				$print['anchorage_depth_f'] = 'N/A';
				$print['anchorage_depth_m'] = 'N/A';
			}
			
			if(trim($port['cargo_pier_depth'])=='A'){
				$print['cargo_pier_depth_f'] = '76ft - OVER';
				$print['cargo_pier_depth_m'] = '23.2m - OVER';
			}else if(trim($port['cargo_pier_depth'])=='B'){
				$print['cargo_pier_depth_f'] = '71ft - 75ft';
				$print['cargo_pier_depth_m'] = '21.6m - 22.9m';
			}else if(trim($port['cargo_pier_depth'])=='C'){
				$print['cargo_pier_depth_f'] = '66ft - 75ft';
				$print['cargo_pier_depth_m'] = '20.1m - 21.3m';
			}else if(trim($port['cargo_pier_depth'])=='D'){
				$print['cargo_pier_depth_f'] = '61ft - 65ft';
				$print['cargo_pier_depth_m'] = '18.6m - 19.8m';
			}else if(trim($port['cargo_pier_depth'])=='E'){
				$print['cargo_pier_depth_f'] = '56ft - 60ft';
				$print['cargo_pier_depth_m'] = '17.1m - 18.2m';
			}else if(trim($port['cargo_pier_depth'])=='F'){
				$print['cargo_pier_depth_f'] = '51ft - 55ft';
				$print['cargo_pier_depth_m'] = '15.5m - 16m';
			}else if(trim($port['cargo_pier_depth'])=='G'){
				$print['cargo_pier_depth_f'] = '46ft - 50ft';
				$print['cargo_pier_depth_m'] = '14m - 15.2m';
			}else if(trim($port['cargo_pier_depth'])=='H'){
				$print['cargo_pier_depth_f'] = '41ft - 45ft';
				$print['cargo_pier_depth_m'] = '12.5m - 13.7m';
			}else if(trim($port['cargo_pier_depth'])=='J'){
				$print['cargo_pier_depth_f'] = '36ft - 40ft';
				$print['cargo_pier_depth_m'] = '11m - 12.2m';
			}else if(trim($port['cargo_pier_depth'])=='K'){
				$print['cargo_pier_depth_f'] = '31ft - 35ft';
				$print['cargo_pier_depth_m'] = '9.4m - 10m';
			}else if(trim($port['cargo_pier_depth'])=='L'){
				$print['cargo_pier_depth_f'] = '26ft - 30ft';
				$print['cargo_pier_depth_m'] = '7.1m - 9.1m';
			}else if(trim($port['cargo_pier_depth'])=='M'){
				$print['cargo_pier_depth_f'] = '21ft - 25ft';
				$print['cargo_pier_depth_m'] = '6.4m - 7.6m';
			}else if(trim($port['cargo_pier_depth'])=='N'){
				$print['cargo_pier_depth_f'] = '16ft - 20ft';
				$print['cargo_pier_depth_m'] = '4.9m - 6.1m';
			}else if(trim($port['cargo_pier_depth'])=='O'){
				$print['cargo_pier_depth_f'] = '11ft - 15ft';
				$print['cargo_pier_depth_m'] = '3.4m - 4.6m';
			}else if(trim($port['cargo_pier_depth'])=='P'){
				$print['cargo_pier_depth_f'] = '6ft - 10ft';
				$print['cargo_pier_depth_m'] = '1.8m - 3m';
			}else if(trim($port['cargo_pier_depth'])=='Q'){
				$print['cargo_pier_depth_f'] = '0ft - 5ft';
				$print['cargo_pier_depth_m'] = '0m - 1.5m';
			}else{
				$print['cargo_pier_depth_f'] = 'N/A';
				$print['cargo_pier_depth_m'] = 'N/A';
			}
			
			if(trim($port['oil_terminal_depth'])=='A'){
				$print['oil_terminal_depth_f'] = '76ft - OVER';
				$print['oil_terminal_depth_m'] = '23.2m - OVER';
			}else if(trim($port['oil_terminal_depth'])=='B'){
				$print['oil_terminal_depth_f'] = '71ft - 75ft';
				$print['oil_terminal_depth_m'] = '21.6m - 22.9m';
			}else if(trim($port['oil_terminal_depth'])=='C'){
				$print['oil_terminal_depth_f'] = '66ft - 75ft';
				$print['oil_terminal_depth_m'] = '20.1m - 21.3m';
			}else if(trim($port['oil_terminal_depth'])=='D'){
				$print['oil_terminal_depth_f'] = '61ft - 65ft';
				$print['oil_terminal_depth_m'] = '18.6m - 19.8m';
			}else if(trim($port['oil_terminal_depth'])=='E'){
				$print['oil_terminal_depth_f'] = '56ft - 60ft';
				$print['oil_terminal_depth_m'] = '17.1m - 18.2m';
			}else if(trim($port['oil_terminal_depth'])=='F'){
				$print['oil_terminal_depth_f'] = '51ft - 55ft';
				$print['oil_terminal_depth_m'] = '15.5m - 16m';
			}else if(trim($port['oil_terminal_depth'])=='G'){
				$print['oil_terminal_depth_f'] = '46ft - 50ft';
				$print['oil_terminal_depth_m'] = '14m - 15.2m';
			}else if(trim($port['oil_terminal_depth'])=='H'){
				$print['oil_terminal_depth_f'] = '41ft - 45ft';
				$print['oil_terminal_depth_m'] = '12.5m - 13.7m';
			}else if(trim($port['oil_terminal_depth'])=='J'){
				$print['oil_terminal_depth_f'] = '36ft - 40ft';
				$print['oil_terminal_depth_m'] = '11m - 12.2m';
			}else if(trim($port['oil_terminal_depth'])=='K'){
				$print['oil_terminal_depth_f'] = '31ft - 35ft';
				$print['oil_terminal_depth_m'] = '9.4m - 10m';
			}else if(trim($port['oil_terminal_depth'])=='L'){
				$print['oil_terminal_depth_f'] = '26ft - 30ft';
				$print['oil_terminal_depth_m'] = '7.1m - 9.1m';
			}else if(trim($port['oil_terminal_depth'])=='M'){
				$print['oil_terminal_depth_f'] = '21ft - 25ft';
				$print['oil_terminal_depth_m'] = '6.4m - 7.6m';
			}else if(trim($port['oil_terminal_depth'])=='N'){
				$print['oil_terminal_depth_f'] = '16ft - 20ft';
				$print['oil_terminal_depth_m'] = '4.9m - 6.1m';
			}else if(trim($port['oil_terminal_depth'])=='O'){
				$print['oil_terminal_depth_f'] = '11ft - 15ft';
				$print['oil_terminal_depth_m'] = '3.4m - 4.6m';
			}else if(trim($port['oil_terminal_depth'])=='P'){
				$print['oil_terminal_depth_f'] = '6ft - 10ft';
				$print['oil_terminal_depth_m'] = '1.8m - 3m';
			}else if(trim($port['oil_terminal_depth'])=='Q'){
				$print['oil_terminal_depth_f'] = '0ft - 5ft';
				$print['oil_terminal_depth_m'] = '0m - 1.5m';
			}else{
				$print['oil_terminal_depth_f'] = 'N/A';
				$print['oil_terminal_depth_m'] = 'N/A';
			}
			
			if(trim($port['maxsize_vessel_code'])=='L'){
				$print['maxsize_vessel_code'] = 'Over 500 feet in length';
			}else if(trim($port['maxsize_vessel_code'])=='M'){
				$print['maxsize_vessel_code'] = 'Up to 500 feet in length';
			}else{
				$print['maxsize_vessel_code'] = 'N/A';
			}
			
			if(trim($port['good_holding_ground'])=='Y'){
				$print['good_holding_ground'] = 'Yes';
			}else if(trim($port['good_holding_ground'])=='N'){
				$print['good_holding_ground'] = 'No';
			}else{
				$print['good_holding_ground'] = 'N/A';
			}
			
			if(trim($port['turning_area'])=='Y'){
				$print['turning_area'] = 'Yes';
			}else if(trim($port['turning_area'])=='N'){
				$print['turning_area'] = 'No';
			}else{
				$print['turning_area'] = 'N/A';
			}
			
			if(trim($port['first_port_of_entry'])=='Y'){
				$print['first_port_of_entry'] = 'Yes';
			}else if(trim($port['first_port_of_entry'])=='N'){
				$print['first_port_of_entry'] = 'No';
			}else{
				$print['first_port_of_entry'] = 'N/A';
			}
			
			if(trim($port['us_representative'])=='Y'){
				$print['us_representative'] = 'Yes';
			}else if(trim($port['us_representative'])=='N'){
				$print['us_representative'] = 'No';
			}else{
				$print['us_representative'] = 'N/A';
			}
			
			if(trim($port['eta_message'])=='Y'){
				$print['eta_message'] = 'Yes';
			}else if(trim($port['eta_message'])=='N'){
				$print['eta_message'] = 'No';
			}else{
				$print['eta_message'] = 'N/A';
			}
			
			if(trim($port['pilotage_compulsory'])=='Y'){
				$print['pilotage_compulsory'] = 'Yes';
			}else if(trim($port['pilotage_compulsory'])=='N'){
				$print['pilotage_compulsory'] = 'No';
			}else{
				$print['pilotage_compulsory'] = 'N/A';
			}
			
			if(trim($port['pilotage_available'])=='Y'){
				$print['pilotage_available'] = 'Yes';
			}else if(trim($port['pilotage_available'])=='N'){
				$print['pilotage_available'] = 'No';
			}else{
				$print['pilotage_available'] = 'N/A';
			}
			
			if(trim($port['pilotage_local_assist'])=='Y'){
				$print['pilotage_local_assist'] = 'Yes';
			}else if(trim($port['pilotage_local_assist'])=='N'){
				$print['pilotage_local_assist'] = 'No';
			}else{
				$print['pilotage_local_assist'] = 'N/A';
			}
			
			if(trim($port['pilotage_advisable'])=='Y'){
				$print['pilotage_advisable'] = 'Yes';
			}else if(trim($port['pilotage_advisable'])=='N'){
				$print['pilotage_advisable'] = 'No';
			}else{
				$print['pilotage_advisable'] = 'N/A';
			}
			
			if(trim($port['tugs_salvage'])=='Y'){
				$print['tugs_salvage'] = 'Yes';
			}else if(trim($port['tugs_salvage'])=='N'){
				$print['tugs_salvage'] = 'No';
			}else{
				$print['tugs_salvage'] = 'N/A';
			}
			
			if(trim($port['tugs_assist'])=='Y'){
				$print['tugs_assist'] = 'Yes';
			}else if(trim($port['tugs_assist'])=='N'){
				$print['tugs_assist'] = 'No';
			}else{
				$print['tugs_assist'] = 'N/A';
			}
			
			if(trim($port['quarantine_pratique'])=='Y'){
				$print['quarantine_pratique'] = 'Yes';
			}else if(trim($port['quarantine_pratique'])=='N'){
				$print['quarantine_pratique'] = 'No';
			}else{
				$print['quarantine_pratique'] = 'N/A';
			}
			
			if(trim($port['quarantine_deratt_cert'])=='Y'){
				$print['quarantine_deratt_cert'] = 'Yes';
			}else if(trim($port['quarantine_deratt_cert'])=='N'){
				$print['quarantine_deratt_cert'] = 'No';
			}else{
				$print['quarantine_deratt_cert'] = 'N/A';
			}
			
			if(trim($port['quarantine_other'])=='Y'){
				$print['quarantine_other'] = 'Yes';
			}else if(trim($port['quarantine_other'])=='N'){
				$print['quarantine_other'] = 'No';
			}else{
				$print['quarantine_other'] = 'N/A';
			}
			
			if(trim($port['communications_telephone'])=='Y'){
				$print['communications_telephone'] = 'Yes';
			}else if(trim($port['communications_telephone'])=='N'){
				$print['communications_telephone'] = 'No';
			}else{
				$print['communications_telephone'] = 'N/A';
			}
			
			if(trim($port['communications_telegraph'])=='Y'){
				$print['communications_telegraph'] = 'Yes';
			}else if(trim($port['communications_telegraph'])=='N'){
				$print['communications_telegraph'] = 'No';
			}else{
				$print['communications_telegraph'] = 'N/A';
			}
			
			if(trim($port['communications_radio'])=='Y'){
				$print['communications_radio'] = 'Yes';
			}else if(trim($port['communications_radio'])=='N'){
				$print['communications_radio'] = 'No';
			}else{
				$print['communications_radio'] = 'N/A';
			}
			
			if(trim($port['communications_radio_tel'])=='Y'){
				$print['communications_radio_tel'] = 'Yes';
			}else if(trim($port['communications_radio_tel'])=='N'){
				$print['communications_radio_tel'] = 'No';
			}else{
				$print['communications_radio_tel'] = 'N/A';
			}
			
			if(trim($port['communications_air'])=='Y'){
				$print['communications_air'] = 'Yes';
			}else if(trim($port['communications_air'])=='N'){
				$print['communications_air'] = 'No';
			}else{
				$print['communications_air'] = 'N/A';
			}
			
			if(trim($port['communications_rail'])=='Y'){
				$print['communications_rail'] = 'Yes';
			}else if(trim($port['communications_rail'])=='N'){
				$print['communications_rail'] = 'No';
			}else{
				$print['communications_rail'] = 'N/A';
			}
			
			if(trim($port['load_offload_wharves'])=='Y'){
				$print['load_offload_wharves'] = 'Yes';
			}else if(trim($port['load_offload_wharves'])=='N'){
				$print['load_offload_wharves'] = 'No';
			}else{
				$print['load_offload_wharves'] = 'N/A';
			}
			
			if(trim($port['load_offload_anchor'])=='Y'){
				$print['load_offload_anchor'] = 'Yes';
			}else if(trim($port['load_offload_anchor'])=='N'){
				$print['load_offload_anchor'] = 'No';
			}else{
				$print['load_offload_anchor'] = 'N/A';
			}
			
			if(trim($port['load_offload_med_moor'])=='Y'){
				$print['load_offload_med_moor'] = 'Yes';
			}else if(trim($port['load_offload_med_moor'])=='N'){
				$print['load_offload_med_moor'] = 'No';
			}else{
				$print['load_offload_med_moor'] = 'N/A';
			}
			
			if(trim($port['load_offload_beach_moor'])=='Y'){
				$print['load_offload_beach_moor'] = 'Yes';
			}else if(trim($port['load_offload_beach_moor'])=='N'){
				$print['load_offload_beach_moor'] = 'No';
			}else{
				$print['load_offload_beach_moor'] = 'N/A';
			}
			
			if(trim($port['load_offload_ice_moor'])=='Y'){
				$print['load_offload_ice_moor'] = 'Yes';
			}else if(trim($port['load_offload_ice_moor'])=='N'){
				$print['load_offload_ice_moor'] = 'No';
			}else{
				$print['load_offload_ice_moor'] = 'N/A';
			}
			
			if(trim($port['medical_facilities'])=='Y'){
				$print['medical_facilities'] = 'Yes';
			}else if(trim($port['medical_facilities'])=='N'){
				$print['medical_facilities'] = 'No';
			}else{
				$print['medical_facilities'] = 'N/A';
			}
			
			if(trim($port['garbage_disposal'])=='Y'){
				$print['garbage_disposal'] = 'Yes';
			}else if(trim($port['garbage_disposal'])=='N'){
				$print['garbage_disposal'] = 'No';
			}else{
				$print['garbage_disposal'] = 'N/A';
			}
			
			if(trim($port['degauss'])=='Y'){
				$print['degauss'] = 'Yes';
			}else if(trim($port['degauss'])=='N'){
				$print['degauss'] = 'No';
			}else{
				$print['degauss'] = 'N/A';
			}
			
			if(trim($port['dirty_ballast'])=='Y'){
				$print['dirty_ballast'] = 'Yes';
			}else if(trim($port['dirty_ballast'])=='N'){
				$print['dirty_ballast'] = 'No';
			}else{
				$print['dirty_ballast'] = 'N/A';
			}
			
			if(trim($port['cranes_fixed'])=='Y'){
				$print['cranes_fixed'] = 'Yes';
			}else if(trim($port['cranes_fixed'])=='N'){
				$print['cranes_fixed'] = 'No';
			}else{
				$print['cranes_fixed'] = 'N/A';
			}
			
			if(trim($port['cranes_mobile'])=='Y'){
				$print['cranes_mobile'] = 'Yes';
			}else if(trim($port['cranes_mobile'])=='N'){
				$print['cranes_mobile'] = 'No';
			}else{
				$print['cranes_mobile'] = 'N/A';
			}
			
			if(trim($port['cranes_floating'])=='Y'){
				$print['cranes_floating'] = 'Yes';
			}else if(trim($port['cranes_floating'])=='N'){
				$print['cranes_floating'] = 'No';
			}else{
				$print['cranes_floating'] = 'N/A';
			}
			
			if(trim($port['services_longshore'])=='Y'){
				$print['services_longshore'] = 'Yes';
			}else if(trim($port['services_longshore'])=='N'){
				$print['services_longshore'] = 'No';
			}else{
				$print['services_longshore'] = 'N/A';
			}
			
			if(trim($port['services_elect'])=='Y'){
				$print['services_elect'] = 'Yes';
			}else if(trim($port['services_elect'])=='N'){
				$print['services_elect'] = 'No';
			}else{
				$print['services_elect'] = 'N/A';
			}
			
			if(trim($port['services_steam'])=='Y'){
				$print['services_steam'] = 'Yes';
			}else if(trim($port['services_steam'])=='N'){
				$print['services_steam'] = 'No';
			}else{
				$print['services_steam'] = 'N/A';
			}
			
			if(trim($port['services_navig_equip'])=='Y'){
				$print['services_navig_equip'] = 'Yes';
			}else if(trim($port['services_navig_equip'])=='N'){
				$print['services_navig_equip'] = 'No';
			}else{
				$print['services_navig_equip'] = 'N/A';
			}
			
			if(trim($port['services_elect_repair'])=='Y'){
				$print['services_elect_repair'] = 'Yes';
			}else if(trim($port['services_elect_repair'])=='N'){
				$print['services_elect_repair'] = 'No';
			}else{
				$print['services_elect_repair'] = 'N/A';
			}
			
			if(trim($port['supplies_provisions'])=='Y'){
				$print['supplies_provisions'] = 'Yes';
			}else if(trim($port['supplies_provisions'])=='N'){
				$print['supplies_provisions'] = 'No';
			}else{
				$print['supplies_provisions'] = 'N/A';
			}
			
			if(trim($port['supplies_water'])=='Y'){
				$print['supplies_water'] = 'Yes';
			}else if(trim($port['supplies_water'])=='N'){
				$print['supplies_water'] = 'No';
			}else{
				$print['supplies_water'] = 'N/A';
			}
			
			if(trim($port['supplies_fuel_oil'])=='Y'){
				$print['supplies_fuel_oil'] = 'Yes';
			}else if(trim($port['supplies_fuel_oil'])=='N'){
				$print['supplies_fuel_oil'] = 'No';
			}else{
				$print['supplies_fuel_oil'] = 'N/A';
			}
			
			if(trim($port['supplies_diesel_oil'])=='Y'){
				$print['supplies_diesel_oil'] = 'Yes';
			}else if(trim($port['supplies_diesel_oil'])=='N'){
				$print['supplies_diesel_oil'] = 'No';
			}else{
				$print['supplies_diesel_oil'] = 'N/A';
			}
			
			if(trim($port['supplies_deck'])=='Y'){
				$print['supplies_deck'] = 'Yes';
			}else if(trim($port['supplies_deck'])=='N'){
				$print['supplies_deck'] = 'No';
			}else{
				$print['supplies_deck'] = 'N/A';
			}
			
			if(trim($port['supplies_engine'])=='Y'){
				$print['supplies_engine'] = 'Yes';
			}else if(trim($port['supplies_engine'])=='N'){
				$print['supplies_engine'] = 'No';
			}else{
				$print['supplies_engine'] = 'N/A';
			}
			
			$portint[] = $print;
		}
			
		$_SESSION['portIntelligence'] = $portint;
		
		if($_SESSION['portIntelligence']){
			echo "<table width='100%'>
				<tr style='background:#e5e5e5; padding:10px 0px;'>
					<td><div style='padding:5px; text-align:center;'><a onclick='showMapPI();' class='clickable'>view larger map</a></div></td>
				</tr>
				<tr style='background:#e5e5e5;'>
					<td><div style='padding:5px; text-align:center;'><iframe src='map/index11.php' width='990' height='700'></iframe></div></td>
				</tr>";
				
			$t = count($_SESSION['portIntelligence']);
			
			for($i=0; $i<$t; $i++){
				$port = $_SESSION['portIntelligence'][$i];
				
				echo "<tr style='background:#fff;'>
					<td><div style='padding:5px;'>&nbsp;</div></td>
				</tr>
				<tr>
					<td style='padding-top:25px;'>
						<table width='100%' border='1' cellspacing='5' cellpadding='5'>
							<tr style='background:#f4f5f6; color:#000; padding:10px 0px;'>
								<td align='right'><div style='padding:5px;color:#073262;'><b>PORT NAME:</b></div></td>
								<td><div style='padding:5px;'>".$port['main_port_name']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>COUNTRY:</b></div></td>
								<td><div style='padding:5px;'>".$port['country_name']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>REGION:</b></div></td>
								<td colspan='2'><div style='padding:5px;'>".$port['area_name']."</div></td>
							</tr>
						</table>
						<table width='100%' border='1' cellspacing='5' cellpadding='5'>
							<tr>
								<td colspan='5' style='background:#fff;'>&nbsp;</td>
							</tr>
							<tr style='background:#dedddd; color:#000;'>
								<td><div style='padding:5px; color:#623200;'><b>PORT ATTRIBUTES</b></div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>HARBOR SIZE:</b></div></td>
								<td><div style='padding:5px;'>".$port['harbor_size']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>HARBOR TYPE:</b></div></td>
								<td><div style='padding:5px;'>".$port['harbor_type_description']."</div></td>
							</tr>
							<tr style='background:#dedddd; color:#000;'>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>SHELTER AFFORDED:</b></div></td>
								<td><div style='padding:5px;'>".$port['shelter_afforded_description']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>OVERHEAD LIMIT:</b></div></td>
								<td><div style='padding:5px;'>".$port['overhead_limits']."</div></td>
							</tr>
							<tr>
								<td colspan='5' style='background:#fff;'>&nbsp;</td>
							</tr>
							<tr style='background:#f4f5f6; color:#000;'>
								<td><div style='padding:5px; color:#623200;'><b>ENTRANCE RESTRICTIONS</b></div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>TIDE:</b></div></td>
								<td><div style='padding:5px;'>".$port['entrance_restriction_tide']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>SWELL:</b></div></td>
								<td><div style='padding:5px;'>".$port['entrance_restriction_swell']."</div></td>
							</tr>
							<tr style='background:#f4f5f6; color:#000;'>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>ICE:</b></div></td>
								<td><div style='padding:5px;'>".$port['entrance_restriction_ice']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>OTHER</b></div></td>
								<td><div style='padding:5px;'>".$port['entrance_restriction_other']."</div></td>
							</tr>
							<tr>
								<td colspan='5' style='background:#fff;'>&nbsp;</td>
							</tr>
							<tr style='background:#dedddd; color:#000;'>
								<td><div style='padding:5px; color:#623200;'><b>DEPTH (M)</b></div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>CHANNEL (feet):</b></div></td>
								<td><div style='padding:5px;'>".$port['channel_depth_f']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>CHANNEL (meters):</b></div></td>
								<td><div style='padding:5px;'>".$port['channel_depth_m']."</div></td>
							</tr>
							<tr style='background:#dedddd; color:#000;'>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>ANCHORAGE (feet):</b></div></td>
								<td><div style='padding:5px;'>".$port['anchorage_depth_f']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>ANCHORAGE (meters):</b></div></td>
								<td><div style='padding:5px;'>".$port['anchorage_depth_m']."</div></td>
							</tr>
							<tr style='background:#dedddd; color:#000;'>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>CARGO PIER (feet):</b></div></td>
								<td><div style='padding:5px;'>".$port['cargo_pier_depth_f']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>CARGO PIER (meters):</b></div></td>
								<td><div style='padding:5px;'>".$port['cargo_pier_depth_m']."</div></td>
							</tr>
							<tr style='background:#dedddd; color:#000;'>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>OIL TERMINAL (feet):</b></div></td>
								<td><div style='padding:5px;'>".$port['oil_terminal_depth_f']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>OIL TERMINAL (meters):</b></div></td>
								<td><div style='padding:5px;'>".$port['oil_terminal_depth_m']."</div></td>
							</tr>
							<tr style='background:#dedddd; color:#000;'>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>TIDE:</b></div></td>
								<td><div style='padding:5px;'>".$port['tide']."</div></td>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td><div style='padding:5px;'>&nbsp;</div></td>
							</tr>
							<tr>
								<td colspan='5' style='background:#fff;'>&nbsp;</td>
							</tr>
							<tr style='background:#f4f5f6; color:#000;'>
								<td><div style='padding:5px; color:#623200;'><b>LENGTH (FT)</b></div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>MAXSIZE VESSEL:</b></div></td>
								<td><div style='padding:5px;'>".$port['maxsize_vessel_code']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>GOOD HOLDING GROUND:</b></div></td>
								<td><div style='padding:5px;'>".$port['good_holding_ground']."</div></td>
							</tr>
							<tr style='background:#f4f5f6; color:#000;'>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>TURNING AREA:</b></div></td>
								<td><div style='padding:5px;'>".$port['turning_area']."</div></td>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td><div style='padding:5px;'>&nbsp;</div></td>
							</tr>
							<tr>
								<td colspan='5' style='background:#fff;'>&nbsp;</td>
							</tr>
							<tr style='background:#dedddd; color:#000;'>
								<td><div style='padding:5px; color:#623200;'><b>ENTRY REQUIREMENTS</b></div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>FIRST PORT OF ENTRY:</b></div></td>
								<td><div style='padding:5px;'>".$port['first_port_of_entry']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>USA REPRESENTATIVE:</b></div></td>
								<td><div style='padding:5px;'>".$port['us_representative']."</div></td>
							</tr>
							<tr style='background:#dedddd; color:#000;'>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>ETA MESSAGE:</b></div></td>
								<td><div style='padding:5px;'>".$port['eta_message']."</div></td>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td><div style='padding:5px;'>&nbsp;</div></td>
							</tr>
							<tr>
								<td colspan='5' style='background:#fff;'>&nbsp;</td>
							</tr>
							<tr style='background:#f4f5f6; color:#000;'>
								<td><div style='padding:5px; color:#623200;'><b>PILOTAGE</b></div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>COMPULSORY:</b></div></td>
								<td><div style='padding:5px;'>".$port['pilotage_compulsory']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>AVAILABLE:</b></div></td>
								<td><div style='padding:5px;'>".$port['pilotage_available']."</div></td>
							</tr>
							<tr style='background:#f4f5f6; color:#000;'>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>LOCAL ASSIST:</b></div></td>
								<td><div style='padding:5px;'>".$port['pilotage_local_assist']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>ADVISABLE:</b></div></td>
								<td><div style='padding:5px;'>".$port['pilotage_advisable']."</div></td>
							</tr>
							<tr>
								<td colspan='5' style='background:#fff;'>&nbsp;</td>
							</tr>
							<tr style='background:#dedddd; color:#000;'>
								<td><div style='padding:5px; color:#623200;'><b>TUGS</b></div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>SALVAGE:</b></div></td>
								<td><div style='padding:5px;'>".$port['tugs_salvage']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>ASSIST:</b></div></td>
								<td><div style='padding:5px;'>".$port['tugs_assist']."</div></td>
							</tr>
							<tr>
								<td colspan='5' style='background:#fff;'>&nbsp;</td>
							</tr>
							<tr style='background:#f4f5f6; color:#000;'>
								<td><div style='padding:5px; color:#623200;'><b>QUARANTINE</b></div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>PRATIQUE:</b></div></td>
								<td><div style='padding:5px;'>".$port['quarantine_pratique']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>SSCC CERT:</b></div></td>
								<td><div style='padding:5px;'>".$port['quarantine_deratt_cert']."</div></td>
							</tr>
							<tr style='background:#f4f5f6; color:#000;'>
								<td><div style='padding:5px;'></div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>OTHER:</b></div></td>
								<td><div style='padding:5px;'>".$port['quarantine_other']."</div></td>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td><div style='padding:5px;'>&nbsp;</div></td>
							</tr>
							<tr>
								<td colspan='5' style='background:#fff;'>&nbsp;</td>
							</tr>
							<tr style='background:#dedddd; color:#000;'>
								<td><div style='padding:5px; color:#623200;'><b>COMMUNICATION</b></div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>TELEPHONE:</b></div></td>
								<td><div style='padding:5px;'>".$port['communications_telephone']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>TELEGRAPH:</b></div></td>
								<td><div style='padding:5px;'>".$port['communications_telegraph']."</div></td>
							</tr>
							<tr style='background:#dedddd; color:#000;'>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>RADIO:</b></div></td>
								<td><div style='padding:5px;'>".$port['communications_radio']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>RADIO TELEPHONE:</b></div></td>
								<td><div style='padding:5px;'>".$port['communications_radio_tel']."</div></td>
							</tr>
							<tr style='background:#dedddd; color:#000;'>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>AIR:</b></div></td>
								<td><div style='padding:5px;'>".$port['communications_air']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>RAIL:</b></div></td>
								<td><div style='padding:5px;'>".$port['communications_rail']."</div></td>
							</tr>
							<tr>
								<td colspan='5' style='background:#fff;'>&nbsp;</td>
							</tr>
							<tr style='background:#f4f5f6; color:#000;'>
								<td><div style='padding:5px; color:#623200;'><b>LOAD/OFFLOAD</b></div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>WHARVES:</b></div></td>
								<td><div style='padding:5px;'>".$port['load_offload_wharves']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>ANCHOR:</b></div></td>
								<td><div style='padding:5px;'>".$port['load_offload_anchor']."</div></td>
							</tr>
							<tr style='background:#f4f5f6; color:#000;'>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>MED MOOR:</b></div></td>
								<td><div style='padding:5px;'>".$port['load_offload_med_moor']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>BEACH MOOR:</b></div></td>
								<td><div style='padding:5px;'>".$port['load_offload_beach_moor']."</div></td>
							</tr>
							<tr style='background:#f4f5f6; color:#000;'>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>ICE MOOR:</b></div></td>
								<td><div style='padding:5px;'>".$port['load_offload_ice_moor']."</div></td>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td><div style='padding:5px;'>&nbsp;</div></td>
							</tr>
							<tr>
								<td colspan='5' style='background:#fff;'>&nbsp;</td>
							</tr>
							<tr style='background:#dedddd; color:#000;'>
								<td><div style='padding:5px; color:#623200;'><b>FACILITIES</b></div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>MEDICAL:</b></div></td>
								<td><div style='padding:5px;'>".$port['medical_facilities']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>GARBAGE DISPOSAL:</b></div></td>
								<td><div style='padding:5px;'>".$port['garbage_disposal']."</div></td>
							</tr>
							<tr style='background:#dedddd; color:#000;'>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>DEGAUSS:</b></div></td>
								<td><div style='padding:5px;'>".$port['degauss']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>DIRTY BALLAST:</b></div></td>
								<td><div style='padding:5px;'>".$port['dirty_ballast']."</div></td>
							</tr>
							<tr>
								<td colspan='5' style='background:#fff;'>&nbsp;</td>
							</tr>
							<tr style='background:#f4f5f6; color:#000;'>
								<td><div style='padding:5px; color:#623200;'><b>CRAINS</b></div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>FIXED:</b></div></td>
								<td><div style='padding:5px;'>".$port['cranes_fixed']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>MOBILE:</b></div></td>
								<td><div style='padding:5px;'>".$port['cranes_mobile']."</div></td>
							</tr>
							<tr style='background:#f4f5f6; color:#000;'>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>FLOATING:</b></div></td>
								<td><div style='padding:5px;'>".$port['cranes_floating']."</div></td>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td><div style='padding:5px;'>&nbsp;</div></td>
							</tr>
							<tr>
								<td colspan='5' style='background:#fff;'>&nbsp;</td>
							</tr>
							<tr style='background:#dedddd; color:#000;'>
								<td><div style='padding:5px; color:#623200;'><b>SERVICES</b></div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>LONGSHORE:</b></div></td>
								<td><div style='padding:5px;'>".$port['services_longshore']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>ELECT:</b></div></td>
								<td><div style='padding:5px;'>".$port['services_elect']."</div></td>
							</tr>
							<tr style='background:#dedddd; color:#000;'>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>STEAM:</b></div></td>
								<td><div style='padding:5px;'>".$port['services_steam']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>NAV EQUIP:</b></div></td>
								<td><div style='padding:5px;'>".$port['services_navig_equip']."</div></td>
							</tr>
							<tr style='background:#dedddd; color:#000;'>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>ELECT REPAIR:</b></div></td>
								<td><div style='padding:5px;'>".$port['services_elect_repair']."</div></td>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td><div style='padding:5px;'>".$port['supplies_provisions']."</div></td>
							</tr>
							<tr>
								<td colspan='5' style='background:#fff;'>&nbsp;</td>
							</tr>
							<tr style='background:#f4f5f6; color:#000;'>
								<td><div style='padding:5px; color:#623200;'><b>SUPPLIES</b></div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>PROVISIONS:</b></div></td>
								<td><div style='padding:5px;'>".$port['supplies_provisions']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>WATER:</b></div></td>
								<td><div style='padding:5px;'>".$port['supplies_water']."</div></td>
							</tr>
							<tr style='background:#f4f5f6; color:#000;'>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>FUEL OIL:</b></div></td>
								<td><div style='padding:5px;'>".$port['supplies_fuel_oil']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>DIESEL OIL:</b></div></td>
								<td><div style='padding:5px;'>".$port['supplies_diesel_oil']."</div></td>
							</tr>
							<tr style='background:#f4f5f6; color:#000;'>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>DECK:</b></div></td>
								<td><div style='padding:5px;'>".$port['supplies_deck']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>ENGINE:</b></div></td>
								<td><div style='padding:5px;'>".$port['supplies_engine']."</div></td>
							</tr>
							<tr>
								<td colspan='5' style='background:#fff;'>&nbsp;</td>
							</tr>
							<tr style='background:#dedddd; color:#000;'>
								<td><div style='padding:5px; color:#623200;'><b>REPAIR</b></div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>DESCRIPTION:</b></div></td>
								<td><div style='padding:5px;'>".$port['repairs_code_description']."</div></td>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td><div style='padding:5px;'>&nbsp;</div></td>
							</tr>
							<tr>
								<td colspan='5' style='background:#fff;'>&nbsp;</td>
							</tr>
							<tr style='background:#f4f5f6; color:#000;'>
								<td><div style='padding:5px; color:#623200;'><b>DRYDOCK/MARINE RAILWAY</b></div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>DRYDOCK:</b></div></td>
								<td><div style='padding:5px;'>".$port['drydock_marine_railway_code_description_a']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>MARINE RAILWAY:</b></div></td>
								<td><div style='padding:5px;'>".$port['drydock_marine_railway_code_description_b']."</div></td>
							</tr>
						</table>
					</td>
				</tr>";
			}
			
			echo "</table>";
		}else{
			echo '<b><center>NO DATA AVAILABLE YET.</center></b>';
		}
	}else{
		echo '<b><center>NO DATA AVAILABLE YET.</center></b>';
	}
}else if(!trim($_GET['portname']) && trim($_GET['countryname'])){
	$sql = "SELECT 
		wpi_data.main_port_name, 
		wpi_data.latitude_degrees, 
		wpi_data.latitude_hemisphere, 
		wpi_data.longitude_degrees, 
		wpi_data.longitude_hemisphere, 
		wpi_data.entrance_restriction_tide, 
		wpi_data.entrance_restriction_swell, 
		wpi_data.entrance_restriction_ice, 
		wpi_data.entrance_restriction_other, 
		wpi_data.overhead_limits, 
		wpi_data.channel_depth, 
		wpi_data.anchorage_depth, 
		wpi_data.cargo_pier_depth, 
		wpi_data.oil_terminal_depth, 
		wpi_data.tide, 
		wpi_data.maxsize_vessel_code, 
		wpi_data.good_holding_ground, 
		wpi_data.turning_area, 
		wpi_data.first_port_of_entry, 
		wpi_data.us_representative, 
		wpi_data.eta_message, 
		wpi_data.pilotage_compulsory, 
		wpi_data.pilotage_available, 
		wpi_data.pilotage_local_assist, 
		wpi_data.pilotage_advisable, 
		wpi_data.tugs_salvage, 
		wpi_data.tugs_assist, 
		wpi_data.quarantine_pratique, 
		wpi_data.quarantine_deratt_cert, 
		wpi_data.quarantine_other, 
		wpi_data.communications_telephone, 
		wpi_data.communications_telegraph, 
		wpi_data.communications_radio, 
		wpi_data.communications_radio_tel, 
		wpi_data.communications_air, 
		wpi_data.communications_rail, 
		wpi_data.load_offload_wharves, 
		wpi_data.load_offload_anchor, 
		wpi_data.load_offload_med_moor, 
		wpi_data.load_offload_beach_moor, 
		wpi_data.load_offload_ice_moor, 
		wpi_data.medical_facilities, 
		wpi_data.garbage_disposal, 
		wpi_data.degauss, 
		wpi_data.dirty_ballast, 
		wpi_data.cranes_fixed, 
		wpi_data.cranes_mobile, 
		wpi_data.cranes_floating, 
		wpi_data.services_longshore, 
		wpi_data.services_elect, 
		wpi_data.services_steam, 
		wpi_data.services_navig_equip, 
		wpi_data.services_elect_repair, 
		wpi_data.supplies_provisions, 
		wpi_data.supplies_water, 
		wpi_data.supplies_fuel_oil, 
		wpi_data.supplies_diesel_oil, 
		wpi_data.supplies_deck, 
		wpi_data.supplies_engine, 
		
		wpi_country_codes.country_name, 
		
		wpi_region.area_name, 
		
		wpi_harbor_size_lut.harbor_size, 
		
		wpi_harbor_type_lut.harbor_type_description, 
		
		wpi_shelter_afforded_lut.shelter_afforded_description, 
		
		wpi_repairs_code_lut.repairs_code_description, 
		
		wpi_drydock_code_lut.drydock_marine_railway_code_description, 
		
		wpi_marine_railway_code_lut.drydock_marine_railway_code_description 
		
		FROM 
		
		(`wpi_data` INNER JOIN `wpi_country_codes` ON wpi_data.wpi_country_code=wpi_country_codes.country_code) 
		INNER JOIN `wpi_region` ON wpi_data.region_index=wpi_region.world_port_index_number 
		INNER JOIN `wpi_harbor_size_lut` ON wpi_data.harbor_size_code=wpi_harbor_size_lut.harbor_size_code 
		INNER JOIN `wpi_harbor_type_lut` ON wpi_data.harbor_type_code=wpi_harbor_type_lut.harbor_type_code 
		INNER JOIN `wpi_shelter_afforded_lut` ON wpi_data.shelter_afforded_code=wpi_shelter_afforded_lut.shelter_afforded_code 
		INNER JOIN `wpi_repairs_code_lut` ON wpi_data.repair_code=wpi_repairs_code_lut.repairs_code 
		INNER JOIN `wpi_drydock_code_lut` ON wpi_data.drydock=wpi_drydock_code_lut.drydock_marine_railway_code 
		INNER JOIN `wpi_marine_railway_code_lut` ON wpi_data.railway=wpi_marine_railway_code_lut.drydock_marine_railway_code 
		
		WHERE 
		
		wpi_country_codes.country_name='".trim($_GET['countryname'])."' 
		
		ORDER BY wpi_data.id DESC LIMIT 0,50";
	$ports = dbQuery($sql, $link);
	
	$t = count($ports);
	
	if(trim($t)){
		$portint = array();
		
		for($i=0;$i<$t;$i++){
			$print = array();
			
			$port = $ports[$i];
			
			$sql = "SELECT latitude, longitude FROM `_veson_ports` WHERE name='".mysql_escape_string(trim($port['main_port_name']))."' ORDER BY id DESC LIMIT 0,1";
			$_sbis_port = dbQuery($sql, $link);
			$_sbis_port = $_sbis_port[0];
			
			if(trim($_sbis_port['latitude']) && trim($_sbis_port['longitude'])){
				$print['port_latitude']  = $_sbis_port['latitude'];
				$print['port_longitude'] = $_sbis_port['longitude'];
			}else{
				if($port['latitude_hemisphere']=="S"){
					$print['port_latitude'] = "-".$port['latitude_degrees'].".".$port['latitude_minutes'];
				}else if($port['latitude_hemisphere']=="N"){
					$print['port_latitude'] = $port['latitude_degrees'].".".$port['latitude_minutes'];
				}
				
				if($port['longitude_hemisphere']=="W"){
					$print['port_longitude'] = "-".$port['longitude_degrees'].".".$port['longitude_minutes'];
				}else if($port['longitude_hemisphere']=="E"){
					$print['port_longitude'] = $port['longitude_degrees'].".".$port['longitude_minutes'];
				}
				
				$print['port_latitude']  = $print['port_latitude'];
				$print['port_longitude'] = $print['port_longitude'];
			}
			
			$print['main_port_name']                            = $port['main_port_name'];
			$print['country_name']                              = $port['country_name'];
			$print['area_name']                                 = $port['area_name'];
			$print['harbor_size']                               = $port['harbor_size'];
			$print['harbor_type_description']                   = $port['harbor_type_description'];
			$print['shelter_afforded_description']              = $port['shelter_afforded_description'];
			$print['repairs_code_description']                  = $port['repairs_code_description'];
			$print['drydock_marine_railway_code_description_a'] = $port['drydock_marine_railway_code_description'];
			$print['drydock_marine_railway_code_description_b'] = $port['drydock_marine_railway_code_description'];
			
			if($port['entrance_restriction_tide']=='Y'){
				$print['entrance_restriction_tide'] = 'Yes';
			}else if($port['entrance_restriction_tide']=='N'){
				$print['entrance_restriction_tide'] = 'No';
			}else{
				$print['entrance_restriction_tide'] = 'N/A';
			}
			
			if($port['entrance_restriction_swell']=='Y'){
				$print['entrance_restriction_swell'] = 'Yes';
			}else if($port['entrance_restriction_swell']=='N'){
				$print['entrance_restriction_swell'] = 'No';
			}else{
				$print['entrance_restriction_swell'] = 'N/A';
			}
			
			if($port['entrance_restriction_ice']=='Y'){
				$print['entrance_restriction_ice'] = 'Yes';
			}else if($port['entrance_restriction_ice']=='N'){
				$print['entrance_restriction_ice'] = 'No';
			}else{
				$print['entrance_restriction_ice'] = 'N/A';
			}
			
			if($port['entrance_restriction_other']=='Y'){
				$print['entrance_restriction_other'] = 'Yes';
			}else if($port['entrance_restriction_other']=='N'){
				$print['entrance_restriction_other'] = 'No';
			}else{
				$print['entrance_restriction_other'] = 'N/A';
			}
			
			if($port['overhead_limits']=='Y'){
				$print['overhead_limits'] = 'Yes';
			}else if($port['overhead_limits']=='N'){
				$print['overhead_limits'] = 'No';
			}else{
				$print['overhead_limits'] = 'N/A';
			}
			
			if(trim($port['channel_depth'])=='A'){
				$print['channel_depth_f'] = '76ft - OVER';
				$print['channel_depth_m'] = '23.2m - OVER';
			}else if(trim($port['channel_depth'])=='B'){
				$print['channel_depth_f'] = '71ft - 75ft';
				$print['channel_depth_m'] = '21.6m - 22.9m';
			}else if(trim($port['channel_depth'])=='C'){
				$print['channel_depth_f'] = '66ft - 75ft';
				$print['channel_depth_m'] = '20.1m - 21.3m';
			}else if(trim($port['channel_depth'])=='D'){
				$print['channel_depth_f'] = '61ft - 65ft';
				$print['channel_depth_m'] = '18.6m - 19.8m';
			}else if(trim($port['channel_depth'])=='E'){
				$print['channel_depth_f'] = '56ft - 60ft';
				$print['channel_depth_m'] = '17.1m - 18.2m';
			}else if(trim($port['channel_depth'])=='F'){
				$print['channel_depth_f'] = '51ft - 55ft';
				$print['channel_depth_m'] = '15.5m - 16m';
			}else if(trim($port['channel_depth'])=='G'){
				$print['channel_depth_f'] = '46ft - 50ft';
				$print['channel_depth_m'] = '14m - 15.2m';
			}else if(trim($port['channel_depth'])=='H'){
				$print['channel_depth_f'] = '41ft - 45ft';
				$print['channel_depth_m'] = '12.5m - 13.7m';
			}else if(trim($port['channel_depth'])=='J'){
				$print['channel_depth_f'] = '36ft - 40ft';
				$print['channel_depth_m'] = '11m - 12.2m';
			}else if(trim($port['channel_depth'])=='K'){
				$print['channel_depth_f'] = '31ft - 35ft';
				$print['channel_depth_m'] = '9.4m - 10m';
			}else if(trim($port['channel_depth'])=='L'){
				$print['channel_depth_f'] = '26ft - 30ft';
				$print['channel_depth_m'] = '7.1m - 9.1m';
			}else if(trim($port['channel_depth'])=='M'){
				$print['channel_depth_f'] = '21ft - 25ft';
				$print['channel_depth_m'] = '6.4m - 7.6m';
			}else if(trim($port['channel_depth'])=='N'){
				$print['channel_depth_f'] = '16ft - 20ft';
				$print['channel_depth_m'] = '4.9m - 6.1m';
			}else if(trim($port['channel_depth'])=='O'){
				$print['channel_depth_f'] = '11ft - 15ft';
				$print['channel_depth_m'] = '3.4m - 4.6m';
			}else if(trim($port['channel_depth'])=='P'){
				$print['channel_depth_f'] = '6ft - 10ft';
				$print['channel_depth_m'] = '1.8m - 3m';
			}else if(trim($port['channel_depth'])=='Q'){
				$print['channel_depth_f'] = '0ft - 5ft';
				$print['channel_depth_m'] = '0m - 1.5m';
			}else{
				$print['channel_depth_f'] = 'N/A';
				$print['channel_depth_m'] = 'N/A';
			}
			
			if(trim($port['anchorage_depth'])=='A'){
				$print['anchorage_depth_f'] = '76ft - OVER';
				$print['anchorage_depth_m'] = '23.2m - OVER';
			}else if(trim($port['anchorage_depth'])=='B'){
				$print['anchorage_depth_f'] = '71ft - 75ft';
				$print['anchorage_depth_m'] = '21.6m - 22.9m';
			}else if(trim($port['anchorage_depth'])=='C'){
				$print['anchorage_depth_f'] = '66ft - 75ft';
				$print['anchorage_depth_m'] = '20.1m - 21.3m';
			}else if(trim($port['anchorage_depth'])=='D'){
				$print['anchorage_depth_f'] = '61ft - 65ft';
				$print['anchorage_depth_m'] = '18.6m - 19.8m';
			}else if(trim($port['anchorage_depth'])=='E'){
				$print['anchorage_depth_f'] = '56ft - 60ft';
				$print['anchorage_depth_m'] = '17.1m - 18.2m';
			}else if(trim($port['anchorage_depth'])=='F'){
				$print['anchorage_depth_f'] = '51ft - 55ft';
				$print['anchorage_depth_m'] = '15.5m - 16m';
			}else if(trim($port['anchorage_depth'])=='G'){
				$print['anchorage_depth_f'] = '46ft - 50ft';
				$print['anchorage_depth_m'] = '14m - 15.2m';
			}else if(trim($port['anchorage_depth'])=='H'){
				$print['anchorage_depth_f'] = '41ft - 45ft';
				$print['anchorage_depth_m'] = '12.5m - 13.7m';
			}else if(trim($port['anchorage_depth'])=='J'){
				$print['anchorage_depth_f'] = '36ft - 40ft';
				$print['anchorage_depth_m'] = '11m - 12.2m';
			}else if(trim($port['anchorage_depth'])=='K'){
				$print['anchorage_depth_f'] = '31ft - 35ft';
				$print['anchorage_depth_m'] = '9.4m - 10m';
			}else if(trim($port['anchorage_depth'])=='L'){
				$print['anchorage_depth_f'] = '26ft - 30ft';
				$print['anchorage_depth_m'] = '7.1m - 9.1m';
			}else if(trim($port['anchorage_depth'])=='M'){
				$print['anchorage_depth_f'] = '21ft - 25ft';
				$print['anchorage_depth_m'] = '6.4m - 7.6m';
			}else if(trim($port['anchorage_depth'])=='N'){
				$print['anchorage_depth_f'] = '16ft - 20ft';
				$print['anchorage_depth_m'] = '4.9m - 6.1m';
			}else if(trim($port['anchorage_depth'])=='O'){
				$print['anchorage_depth_f'] = '11ft - 15ft';
				$print['anchorage_depth_m'] = '3.4m - 4.6m';
			}else if(trim($port['anchorage_depth'])=='P'){
				$print['anchorage_depth_f'] = '6ft - 10ft';
				$print['anchorage_depth_m'] = '1.8m - 3m';
			}else if(trim($port['anchorage_depth'])=='Q'){
				$print['anchorage_depth_f'] = '0ft - 5ft';
				$print['anchorage_depth_m'] = '0m - 1.5m';
			}else{
				$print['anchorage_depth_f'] = 'N/A';
				$print['anchorage_depth_m'] = 'N/A';
			}
			
			if(trim($port['cargo_pier_depth'])=='A'){
				$print['cargo_pier_depth_f'] = '76ft - OVER';
				$print['cargo_pier_depth_m'] = '23.2m - OVER';
			}else if(trim($port['cargo_pier_depth'])=='B'){
				$print['cargo_pier_depth_f'] = '71ft - 75ft';
				$print['cargo_pier_depth_m'] = '21.6m - 22.9m';
			}else if(trim($port['cargo_pier_depth'])=='C'){
				$print['cargo_pier_depth_f'] = '66ft - 75ft';
				$print['cargo_pier_depth_m'] = '20.1m - 21.3m';
			}else if(trim($port['cargo_pier_depth'])=='D'){
				$print['cargo_pier_depth_f'] = '61ft - 65ft';
				$print['cargo_pier_depth_m'] = '18.6m - 19.8m';
			}else if(trim($port['cargo_pier_depth'])=='E'){
				$print['cargo_pier_depth_f'] = '56ft - 60ft';
				$print['cargo_pier_depth_m'] = '17.1m - 18.2m';
			}else if(trim($port['cargo_pier_depth'])=='F'){
				$print['cargo_pier_depth_f'] = '51ft - 55ft';
				$print['cargo_pier_depth_m'] = '15.5m - 16m';
			}else if(trim($port['cargo_pier_depth'])=='G'){
				$print['cargo_pier_depth_f'] = '46ft - 50ft';
				$print['cargo_pier_depth_m'] = '14m - 15.2m';
			}else if(trim($port['cargo_pier_depth'])=='H'){
				$print['cargo_pier_depth_f'] = '41ft - 45ft';
				$print['cargo_pier_depth_m'] = '12.5m - 13.7m';
			}else if(trim($port['cargo_pier_depth'])=='J'){
				$print['cargo_pier_depth_f'] = '36ft - 40ft';
				$print['cargo_pier_depth_m'] = '11m - 12.2m';
			}else if(trim($port['cargo_pier_depth'])=='K'){
				$print['cargo_pier_depth_f'] = '31ft - 35ft';
				$print['cargo_pier_depth_m'] = '9.4m - 10m';
			}else if(trim($port['cargo_pier_depth'])=='L'){
				$print['cargo_pier_depth_f'] = '26ft - 30ft';
				$print['cargo_pier_depth_m'] = '7.1m - 9.1m';
			}else if(trim($port['cargo_pier_depth'])=='M'){
				$print['cargo_pier_depth_f'] = '21ft - 25ft';
				$print['cargo_pier_depth_m'] = '6.4m - 7.6m';
			}else if(trim($port['cargo_pier_depth'])=='N'){
				$print['cargo_pier_depth_f'] = '16ft - 20ft';
				$print['cargo_pier_depth_m'] = '4.9m - 6.1m';
			}else if(trim($port['cargo_pier_depth'])=='O'){
				$print['cargo_pier_depth_f'] = '11ft - 15ft';
				$print['cargo_pier_depth_m'] = '3.4m - 4.6m';
			}else if(trim($port['cargo_pier_depth'])=='P'){
				$print['cargo_pier_depth_f'] = '6ft - 10ft';
				$print['cargo_pier_depth_m'] = '1.8m - 3m';
			}else if(trim($port['cargo_pier_depth'])=='Q'){
				$print['cargo_pier_depth_f'] = '0ft - 5ft';
				$print['cargo_pier_depth_m'] = '0m - 1.5m';
			}else{
				$print['cargo_pier_depth_f'] = 'N/A';
				$print['cargo_pier_depth_m'] = 'N/A';
			}
			
			if(trim($port['oil_terminal_depth'])=='A'){
				$print['oil_terminal_depth_f'] = '76ft - OVER';
				$print['oil_terminal_depth_m'] = '23.2m - OVER';
			}else if(trim($port['oil_terminal_depth'])=='B'){
				$print['oil_terminal_depth_f'] = '71ft - 75ft';
				$print['oil_terminal_depth_m'] = '21.6m - 22.9m';
			}else if(trim($port['oil_terminal_depth'])=='C'){
				$print['oil_terminal_depth_f'] = '66ft - 75ft';
				$print['oil_terminal_depth_m'] = '20.1m - 21.3m';
			}else if(trim($port['oil_terminal_depth'])=='D'){
				$print['oil_terminal_depth_f'] = '61ft - 65ft';
				$print['oil_terminal_depth_m'] = '18.6m - 19.8m';
			}else if(trim($port['oil_terminal_depth'])=='E'){
				$print['oil_terminal_depth_f'] = '56ft - 60ft';
				$print['oil_terminal_depth_m'] = '17.1m - 18.2m';
			}else if(trim($port['oil_terminal_depth'])=='F'){
				$print['oil_terminal_depth_f'] = '51ft - 55ft';
				$print['oil_terminal_depth_m'] = '15.5m - 16m';
			}else if(trim($port['oil_terminal_depth'])=='G'){
				$print['oil_terminal_depth_f'] = '46ft - 50ft';
				$print['oil_terminal_depth_m'] = '14m - 15.2m';
			}else if(trim($port['oil_terminal_depth'])=='H'){
				$print['oil_terminal_depth_f'] = '41ft - 45ft';
				$print['oil_terminal_depth_m'] = '12.5m - 13.7m';
			}else if(trim($port['oil_terminal_depth'])=='J'){
				$print['oil_terminal_depth_f'] = '36ft - 40ft';
				$print['oil_terminal_depth_m'] = '11m - 12.2m';
			}else if(trim($port['oil_terminal_depth'])=='K'){
				$print['oil_terminal_depth_f'] = '31ft - 35ft';
				$print['oil_terminal_depth_m'] = '9.4m - 10m';
			}else if(trim($port['oil_terminal_depth'])=='L'){
				$print['oil_terminal_depth_f'] = '26ft - 30ft';
				$print['oil_terminal_depth_m'] = '7.1m - 9.1m';
			}else if(trim($port['oil_terminal_depth'])=='M'){
				$print['oil_terminal_depth_f'] = '21ft - 25ft';
				$print['oil_terminal_depth_m'] = '6.4m - 7.6m';
			}else if(trim($port['oil_terminal_depth'])=='N'){
				$print['oil_terminal_depth_f'] = '16ft - 20ft';
				$print['oil_terminal_depth_m'] = '4.9m - 6.1m';
			}else if(trim($port['oil_terminal_depth'])=='O'){
				$print['oil_terminal_depth_f'] = '11ft - 15ft';
				$print['oil_terminal_depth_m'] = '3.4m - 4.6m';
			}else if(trim($port['oil_terminal_depth'])=='P'){
				$print['oil_terminal_depth_f'] = '6ft - 10ft';
				$print['oil_terminal_depth_m'] = '1.8m - 3m';
			}else if(trim($port['oil_terminal_depth'])=='Q'){
				$print['oil_terminal_depth_f'] = '0ft - 5ft';
				$print['oil_terminal_depth_m'] = '0m - 1.5m';
			}else{
				$print['oil_terminal_depth_f'] = 'N/A';
				$print['oil_terminal_depth_m'] = 'N/A';
			}
			
			if(trim($port['maxsize_vessel_code'])=='L'){
				$print['maxsize_vessel_code'] = 'Over 500 feet in length';
			}else if(trim($port['maxsize_vessel_code'])=='M'){
				$print['maxsize_vessel_code'] = 'Up to 500 feet in length';
			}else{
				$print['maxsize_vessel_code'] = 'N/A';
			}
			
			if(trim($port['good_holding_ground'])=='Y'){
				$print['good_holding_ground'] = 'Yes';
			}else if(trim($port['good_holding_ground'])=='N'){
				$print['good_holding_ground'] = 'No';
			}else{
				$print['good_holding_ground'] = 'N/A';
			}
			
			if(trim($port['turning_area'])=='Y'){
				$print['turning_area'] = 'Yes';
			}else if(trim($port['turning_area'])=='N'){
				$print['turning_area'] = 'No';
			}else{
				$print['turning_area'] = 'N/A';
			}
			
			if(trim($port['first_port_of_entry'])=='Y'){
				$print['first_port_of_entry'] = 'Yes';
			}else if(trim($port['first_port_of_entry'])=='N'){
				$print['first_port_of_entry'] = 'No';
			}else{
				$print['first_port_of_entry'] = 'N/A';
			}
			
			if(trim($port['us_representative'])=='Y'){
				$print['us_representative'] = 'Yes';
			}else if(trim($port['us_representative'])=='N'){
				$print['us_representative'] = 'No';
			}else{
				$print['us_representative'] = 'N/A';
			}
			
			if(trim($port['eta_message'])=='Y'){
				$print['eta_message'] = 'Yes';
			}else if(trim($port['eta_message'])=='N'){
				$print['eta_message'] = 'No';
			}else{
				$print['eta_message'] = 'N/A';
			}
			
			if(trim($port['pilotage_compulsory'])=='Y'){
				$print['pilotage_compulsory'] = 'Yes';
			}else if(trim($port['pilotage_compulsory'])=='N'){
				$print['pilotage_compulsory'] = 'No';
			}else{
				$print['pilotage_compulsory'] = 'N/A';
			}
			
			if(trim($port['pilotage_available'])=='Y'){
				$print['pilotage_available'] = 'Yes';
			}else if(trim($port['pilotage_available'])=='N'){
				$print['pilotage_available'] = 'No';
			}else{
				$print['pilotage_available'] = 'N/A';
			}
			
			if(trim($port['pilotage_local_assist'])=='Y'){
				$print['pilotage_local_assist'] = 'Yes';
			}else if(trim($port['pilotage_local_assist'])=='N'){
				$print['pilotage_local_assist'] = 'No';
			}else{
				$print['pilotage_local_assist'] = 'N/A';
			}
			
			if(trim($port['pilotage_advisable'])=='Y'){
				$print['pilotage_advisable'] = 'Yes';
			}else if(trim($port['pilotage_advisable'])=='N'){
				$print['pilotage_advisable'] = 'No';
			}else{
				$print['pilotage_advisable'] = 'N/A';
			}
			
			if(trim($port['tugs_salvage'])=='Y'){
				$print['tugs_salvage'] = 'Yes';
			}else if(trim($port['tugs_salvage'])=='N'){
				$print['tugs_salvage'] = 'No';
			}else{
				$print['tugs_salvage'] = 'N/A';
			}
			
			if(trim($port['tugs_assist'])=='Y'){
				$print['tugs_assist'] = 'Yes';
			}else if(trim($port['tugs_assist'])=='N'){
				$print['tugs_assist'] = 'No';
			}else{
				$print['tugs_assist'] = 'N/A';
			}
			
			if(trim($port['quarantine_pratique'])=='Y'){
				$print['quarantine_pratique'] = 'Yes';
			}else if(trim($port['quarantine_pratique'])=='N'){
				$print['quarantine_pratique'] = 'No';
			}else{
				$print['quarantine_pratique'] = 'N/A';
			}
			
			if(trim($port['quarantine_deratt_cert'])=='Y'){
				$print['quarantine_deratt_cert'] = 'Yes';
			}else if(trim($port['quarantine_deratt_cert'])=='N'){
				$print['quarantine_deratt_cert'] = 'No';
			}else{
				$print['quarantine_deratt_cert'] = 'N/A';
			}
			
			if(trim($port['quarantine_other'])=='Y'){
				$print['quarantine_other'] = 'Yes';
			}else if(trim($port['quarantine_other'])=='N'){
				$print['quarantine_other'] = 'No';
			}else{
				$print['quarantine_other'] = 'N/A';
			}
			
			if(trim($port['communications_telephone'])=='Y'){
				$print['communications_telephone'] = 'Yes';
			}else if(trim($port['communications_telephone'])=='N'){
				$print['communications_telephone'] = 'No';
			}else{
				$print['communications_telephone'] = 'N/A';
			}
			
			if(trim($port['communications_telegraph'])=='Y'){
				$print['communications_telegraph'] = 'Yes';
			}else if(trim($port['communications_telegraph'])=='N'){
				$print['communications_telegraph'] = 'No';
			}else{
				$print['communications_telegraph'] = 'N/A';
			}
			
			if(trim($port['communications_radio'])=='Y'){
				$print['communications_radio'] = 'Yes';
			}else if(trim($port['communications_radio'])=='N'){
				$print['communications_radio'] = 'No';
			}else{
				$print['communications_radio'] = 'N/A';
			}
			
			if(trim($port['communications_radio_tel'])=='Y'){
				$print['communications_radio_tel'] = 'Yes';
			}else if(trim($port['communications_radio_tel'])=='N'){
				$print['communications_radio_tel'] = 'No';
			}else{
				$print['communications_radio_tel'] = 'N/A';
			}
			
			if(trim($port['communications_air'])=='Y'){
				$print['communications_air'] = 'Yes';
			}else if(trim($port['communications_air'])=='N'){
				$print['communications_air'] = 'No';
			}else{
				$print['communications_air'] = 'N/A';
			}
			
			if(trim($port['communications_rail'])=='Y'){
				$print['communications_rail'] = 'Yes';
			}else if(trim($port['communications_rail'])=='N'){
				$print['communications_rail'] = 'No';
			}else{
				$print['communications_rail'] = 'N/A';
			}
			
			if(trim($port['load_offload_wharves'])=='Y'){
				$print['load_offload_wharves'] = 'Yes';
			}else if(trim($port['load_offload_wharves'])=='N'){
				$print['load_offload_wharves'] = 'No';
			}else{
				$print['load_offload_wharves'] = 'N/A';
			}
			
			if(trim($port['load_offload_anchor'])=='Y'){
				$print['load_offload_anchor'] = 'Yes';
			}else if(trim($port['load_offload_anchor'])=='N'){
				$print['load_offload_anchor'] = 'No';
			}else{
				$print['load_offload_anchor'] = 'N/A';
			}
			
			if(trim($port['load_offload_med_moor'])=='Y'){
				$print['load_offload_med_moor'] = 'Yes';
			}else if(trim($port['load_offload_med_moor'])=='N'){
				$print['load_offload_med_moor'] = 'No';
			}else{
				$print['load_offload_med_moor'] = 'N/A';
			}
			
			if(trim($port['load_offload_beach_moor'])=='Y'){
				$print['load_offload_beach_moor'] = 'Yes';
			}else if(trim($port['load_offload_beach_moor'])=='N'){
				$print['load_offload_beach_moor'] = 'No';
			}else{
				$print['load_offload_beach_moor'] = 'N/A';
			}
			
			if(trim($port['load_offload_ice_moor'])=='Y'){
				$print['load_offload_ice_moor'] = 'Yes';
			}else if(trim($port['load_offload_ice_moor'])=='N'){
				$print['load_offload_ice_moor'] = 'No';
			}else{
				$print['load_offload_ice_moor'] = 'N/A';
			}
			
			if(trim($port['medical_facilities'])=='Y'){
				$print['medical_facilities'] = 'Yes';
			}else if(trim($port['medical_facilities'])=='N'){
				$print['medical_facilities'] = 'No';
			}else{
				$print['medical_facilities'] = 'N/A';
			}
			
			if(trim($port['garbage_disposal'])=='Y'){
				$print['garbage_disposal'] = 'Yes';
			}else if(trim($port['garbage_disposal'])=='N'){
				$print['garbage_disposal'] = 'No';
			}else{
				$print['garbage_disposal'] = 'N/A';
			}
			
			if(trim($port['degauss'])=='Y'){
				$print['degauss'] = 'Yes';
			}else if(trim($port['degauss'])=='N'){
				$print['degauss'] = 'No';
			}else{
				$print['degauss'] = 'N/A';
			}
			
			if(trim($port['dirty_ballast'])=='Y'){
				$print['dirty_ballast'] = 'Yes';
			}else if(trim($port['dirty_ballast'])=='N'){
				$print['dirty_ballast'] = 'No';
			}else{
				$print['dirty_ballast'] = 'N/A';
			}
			
			if(trim($port['cranes_fixed'])=='Y'){
				$print['cranes_fixed'] = 'Yes';
			}else if(trim($port['cranes_fixed'])=='N'){
				$print['cranes_fixed'] = 'No';
			}else{
				$print['cranes_fixed'] = 'N/A';
			}
			
			if(trim($port['cranes_mobile'])=='Y'){
				$print['cranes_mobile'] = 'Yes';
			}else if(trim($port['cranes_mobile'])=='N'){
				$print['cranes_mobile'] = 'No';
			}else{
				$print['cranes_mobile'] = 'N/A';
			}
			
			if(trim($port['cranes_floating'])=='Y'){
				$print['cranes_floating'] = 'Yes';
			}else if(trim($port['cranes_floating'])=='N'){
				$print['cranes_floating'] = 'No';
			}else{
				$print['cranes_floating'] = 'N/A';
			}
			
			if(trim($port['services_longshore'])=='Y'){
				$print['services_longshore'] = 'Yes';
			}else if(trim($port['services_longshore'])=='N'){
				$print['services_longshore'] = 'No';
			}else{
				$print['services_longshore'] = 'N/A';
			}
			
			if(trim($port['services_elect'])=='Y'){
				$print['services_elect'] = 'Yes';
			}else if(trim($port['services_elect'])=='N'){
				$print['services_elect'] = 'No';
			}else{
				$print['services_elect'] = 'N/A';
			}
			
			if(trim($port['services_steam'])=='Y'){
				$print['services_steam'] = 'Yes';
			}else if(trim($port['services_steam'])=='N'){
				$print['services_steam'] = 'No';
			}else{
				$print['services_steam'] = 'N/A';
			}
			
			if(trim($port['services_navig_equip'])=='Y'){
				$print['services_navig_equip'] = 'Yes';
			}else if(trim($port['services_navig_equip'])=='N'){
				$print['services_navig_equip'] = 'No';
			}else{
				$print['services_navig_equip'] = 'N/A';
			}
			
			if(trim($port['services_elect_repair'])=='Y'){
				$print['services_elect_repair'] = 'Yes';
			}else if(trim($port['services_elect_repair'])=='N'){
				$print['services_elect_repair'] = 'No';
			}else{
				$print['services_elect_repair'] = 'N/A';
			}
			
			if(trim($port['supplies_provisions'])=='Y'){
				$print['supplies_provisions'] = 'Yes';
			}else if(trim($port['supplies_provisions'])=='N'){
				$print['supplies_provisions'] = 'No';
			}else{
				$print['supplies_provisions'] = 'N/A';
			}
			
			if(trim($port['supplies_water'])=='Y'){
				$print['supplies_water'] = 'Yes';
			}else if(trim($port['supplies_water'])=='N'){
				$print['supplies_water'] = 'No';
			}else{
				$print['supplies_water'] = 'N/A';
			}
			
			if(trim($port['supplies_fuel_oil'])=='Y'){
				$print['supplies_fuel_oil'] = 'Yes';
			}else if(trim($port['supplies_fuel_oil'])=='N'){
				$print['supplies_fuel_oil'] = 'No';
			}else{
				$print['supplies_fuel_oil'] = 'N/A';
			}
			
			if(trim($port['supplies_diesel_oil'])=='Y'){
				$print['supplies_diesel_oil'] = 'Yes';
			}else if(trim($port['supplies_diesel_oil'])=='N'){
				$print['supplies_diesel_oil'] = 'No';
			}else{
				$print['supplies_diesel_oil'] = 'N/A';
			}
			
			if(trim($port['supplies_deck'])=='Y'){
				$print['supplies_deck'] = 'Yes';
			}else if(trim($port['supplies_deck'])=='N'){
				$print['supplies_deck'] = 'No';
			}else{
				$print['supplies_deck'] = 'N/A';
			}
			
			if(trim($port['supplies_engine'])=='Y'){
				$print['supplies_engine'] = 'Yes';
			}else if(trim($port['supplies_engine'])=='N'){
				$print['supplies_engine'] = 'No';
			}else{
				$print['supplies_engine'] = 'N/A';
			}
			
			$portint[] = $print;
		}
			
		$_SESSION['portIntelligence'] = $portint;
		
		if($_SESSION['portIntelligence']){
			echo "<table width='100%'>
				<tr style='background:#e5e5e5; padding:10px 0px;'>
					<td><div style='padding:5px; text-align:center;'><a onclick='showMapPI();' class='clickable'>view larger map</a></div></td>
				</tr>
				<tr style='background:#e5e5e5;'>
					<td><div style='padding:5px; text-align:center;'><iframe src='map/index11.php' width='990' height='700'></iframe></div></td>
				</tr>";
				
			$t = count($_SESSION['portIntelligence']);
			
			for($i=0; $i<$t; $i++){
				$port = $_SESSION['portIntelligence'][$i];
				
				echo "<tr style='background:#fff;'>
					<td><div style='padding:5px;'>&nbsp;</div></td>
				</tr>
				<tr>
					<td style='padding-top:25px;'>
						<table width='100%' border='1' cellspacing='5' cellpadding='5'>
							<tr style='background:#f4f5f6; color:#000; padding:10px 0px;'>
								<td align='right'><div style='padding:5px;color:#073262;'><b>PORT NAME:</b></div></td>
								<td><div style='padding:5px;'>".$port['main_port_name']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>COUNTRY:</b></div></td>
								<td><div style='padding:5px;'>".$port['country_name']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>REGION:</b></div></td>
								<td colspan='2'><div style='padding:5px;'>".$port['area_name']."</div></td>
							</tr>
						</table>
						<table width='100%' border='1' cellspacing='5' cellpadding='5'>
							<tr>
								<td colspan='5' style='background:#fff;'>&nbsp;</td>
							</tr>
							<tr style='background:#dedddd; color:#000;'>
								<td><div style='padding:5px; color:#623200;'><b>PORT ATTRIBUTES</b></div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>HARBOR SIZE:</b></div></td>
								<td><div style='padding:5px;'>".$port['harbor_size']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>HARBOR TYPE:</b></div></td>
								<td><div style='padding:5px;'>".$port['harbor_type_description']."</div></td>
							</tr>
							<tr style='background:#dedddd; color:#000;'>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>SHELTER AFFORDED:</b></div></td>
								<td><div style='padding:5px;'>".$port['shelter_afforded_description']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>OVERHEAD LIMIT:</b></div></td>
								<td><div style='padding:5px;'>".$port['overhead_limits']."</div></td>
							</tr>
							<tr>
								<td colspan='5' style='background:#fff;'>&nbsp;</td>
							</tr>
							<tr style='background:#f4f5f6; color:#000;'>
								<td><div style='padding:5px; color:#623200;'><b>ENTRANCE RESTRICTIONS</b></div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>TIDE:</b></div></td>
								<td><div style='padding:5px;'>".$port['entrance_restriction_tide']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>SWELL:</b></div></td>
								<td><div style='padding:5px;'>".$port['entrance_restriction_swell']."</div></td>
							</tr>
							<tr style='background:#f4f5f6; color:#000;'>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>ICE:</b></div></td>
								<td><div style='padding:5px;'>".$port['entrance_restriction_ice']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>OTHER</b></div></td>
								<td><div style='padding:5px;'>".$port['entrance_restriction_other']."</div></td>
							</tr>
							<tr>
								<td colspan='5' style='background:#fff;'>&nbsp;</td>
							</tr>
							<tr style='background:#dedddd; color:#000;'>
								<td><div style='padding:5px; color:#623200;'><b>DEPTH (M)</b></div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>CHANNEL (feet):</b></div></td>
								<td><div style='padding:5px;'>".$port['channel_depth_f']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>CHANNEL (meters):</b></div></td>
								<td><div style='padding:5px;'>".$port['channel_depth_m']."</div></td>
							</tr>
							<tr style='background:#dedddd; color:#000;'>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>ANCHORAGE (feet):</b></div></td>
								<td><div style='padding:5px;'>".$port['anchorage_depth_f']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>ANCHORAGE (meters):</b></div></td>
								<td><div style='padding:5px;'>".$port['anchorage_depth_m']."</div></td>
							</tr>
							<tr style='background:#dedddd; color:#000;'>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>CARGO PIER (feet):</b></div></td>
								<td><div style='padding:5px;'>".$port['cargo_pier_depth_f']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>CARGO PIER (meters):</b></div></td>
								<td><div style='padding:5px;'>".$port['cargo_pier_depth_m']."</div></td>
							</tr>
							<tr style='background:#dedddd; color:#000;'>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>OIL TERMINAL (feet):</b></div></td>
								<td><div style='padding:5px;'>".$port['oil_terminal_depth_f']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>OIL TERMINAL (meters):</b></div></td>
								<td><div style='padding:5px;'>".$port['oil_terminal_depth_m']."</div></td>
							</tr>
							<tr style='background:#dedddd; color:#000;'>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>TIDE:</b></div></td>
								<td><div style='padding:5px;'>".$port['tide']."</div></td>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td><div style='padding:5px;'>&nbsp;</div></td>
							</tr>
							<tr>
								<td colspan='5' style='background:#fff;'>&nbsp;</td>
							</tr>
							<tr style='background:#f4f5f6; color:#000;'>
								<td><div style='padding:5px; color:#623200;'><b>LENGTH (FT)</b></div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>MAXSIZE VESSEL:</b></div></td>
								<td><div style='padding:5px;'>".$port['maxsize_vessel_code']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>GOOD HOLDING GROUND:</b></div></td>
								<td><div style='padding:5px;'>".$port['good_holding_ground']."</div></td>
							</tr>
							<tr style='background:#f4f5f6; color:#000;'>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>TURNING AREA:</b></div></td>
								<td><div style='padding:5px;'>".$port['turning_area']."</div></td>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td><div style='padding:5px;'>&nbsp;</div></td>
							</tr>
							<tr>
								<td colspan='5' style='background:#fff;'>&nbsp;</td>
							</tr>
							<tr style='background:#dedddd; color:#000;'>
								<td><div style='padding:5px; color:#623200;'><b>ENTRY REQUIREMENTS</b></div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>FIRST PORT OF ENTRY:</b></div></td>
								<td><div style='padding:5px;'>".$port['first_port_of_entry']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>USA REPRESENTATIVE:</b></div></td>
								<td><div style='padding:5px;'>".$port['us_representative']."</div></td>
							</tr>
							<tr style='background:#dedddd; color:#000;'>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>ETA MESSAGE:</b></div></td>
								<td><div style='padding:5px;'>".$port['eta_message']."</div></td>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td><div style='padding:5px;'>&nbsp;</div></td>
							</tr>
							<tr>
								<td colspan='5' style='background:#fff;'>&nbsp;</td>
							</tr>
							<tr style='background:#f4f5f6; color:#000;'>
								<td><div style='padding:5px; color:#623200;'><b>PILOTAGE</b></div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>COMPULSORY:</b></div></td>
								<td><div style='padding:5px;'>".$port['pilotage_compulsory']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>AVAILABLE:</b></div></td>
								<td><div style='padding:5px;'>".$port['pilotage_available']."</div></td>
							</tr>
							<tr style='background:#f4f5f6; color:#000;'>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>LOCAL ASSIST:</b></div></td>
								<td><div style='padding:5px;'>".$port['pilotage_local_assist']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>ADVISABLE:</b></div></td>
								<td><div style='padding:5px;'>".$port['pilotage_advisable']."</div></td>
							</tr>
							<tr>
								<td colspan='5' style='background:#fff;'>&nbsp;</td>
							</tr>
							<tr style='background:#dedddd; color:#000;'>
								<td><div style='padding:5px; color:#623200;'><b>TUGS</b></div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>SALVAGE:</b></div></td>
								<td><div style='padding:5px;'>".$port['tugs_salvage']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>ASSIST:</b></div></td>
								<td><div style='padding:5px;'>".$port['tugs_assist']."</div></td>
							</tr>
							<tr>
								<td colspan='5' style='background:#fff;'>&nbsp;</td>
							</tr>
							<tr style='background:#f4f5f6; color:#000;'>
								<td><div style='padding:5px; color:#623200;'><b>QUARANTINE</b></div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>PRATIQUE:</b></div></td>
								<td><div style='padding:5px;'>".$port['quarantine_pratique']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>SSCC CERT:</b></div></td>
								<td><div style='padding:5px;'>".$port['quarantine_deratt_cert']."</div></td>
							</tr>
							<tr style='background:#f4f5f6; color:#000;'>
								<td><div style='padding:5px;'></div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>OTHER:</b></div></td>
								<td><div style='padding:5px;'>".$port['quarantine_other']."</div></td>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td><div style='padding:5px;'>&nbsp;</div></td>
							</tr>
							<tr>
								<td colspan='5' style='background:#fff;'>&nbsp;</td>
							</tr>
							<tr style='background:#dedddd; color:#000;'>
								<td><div style='padding:5px; color:#623200;'><b>COMMUNICATION</b></div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>TELEPHONE:</b></div></td>
								<td><div style='padding:5px;'>".$port['communications_telephone']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>TELEGRAPH:</b></div></td>
								<td><div style='padding:5px;'>".$port['communications_telegraph']."</div></td>
							</tr>
							<tr style='background:#dedddd; color:#000;'>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>RADIO:</b></div></td>
								<td><div style='padding:5px;'>".$port['communications_radio']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>RADIO TELEPHONE:</b></div></td>
								<td><div style='padding:5px;'>".$port['communications_radio_tel']."</div></td>
							</tr>
							<tr style='background:#dedddd; color:#000;'>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>AIR:</b></div></td>
								<td><div style='padding:5px;'>".$port['communications_air']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>RAIL:</b></div></td>
								<td><div style='padding:5px;'>".$port['communications_rail']."</div></td>
							</tr>
							<tr>
								<td colspan='5' style='background:#fff;'>&nbsp;</td>
							</tr>
							<tr style='background:#f4f5f6; color:#000;'>
								<td><div style='padding:5px; color:#623200;'><b>LOAD/OFFLOAD</b></div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>WHARVES:</b></div></td>
								<td><div style='padding:5px;'>".$port['load_offload_wharves']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>ANCHOR:</b></div></td>
								<td><div style='padding:5px;'>".$port['load_offload_anchor']."</div></td>
							</tr>
							<tr style='background:#f4f5f6; color:#000;'>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>MED MOOR:</b></div></td>
								<td><div style='padding:5px;'>".$port['load_offload_med_moor']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>BEACH MOOR:</b></div></td>
								<td><div style='padding:5px;'>".$port['load_offload_beach_moor']."</div></td>
							</tr>
							<tr style='background:#f4f5f6; color:#000;'>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>ICE MOOR:</b></div></td>
								<td><div style='padding:5px;'>".$port['load_offload_ice_moor']."</div></td>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td><div style='padding:5px;'>&nbsp;</div></td>
							</tr>
							<tr>
								<td colspan='5' style='background:#fff;'>&nbsp;</td>
							</tr>
							<tr style='background:#dedddd; color:#000;'>
								<td><div style='padding:5px; color:#623200;'><b>FACILITIES</b></div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>MEDICAL:</b></div></td>
								<td><div style='padding:5px;'>".$port['medical_facilities']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>GARBAGE DISPOSAL:</b></div></td>
								<td><div style='padding:5px;'>".$port['garbage_disposal']."</div></td>
							</tr>
							<tr style='background:#dedddd; color:#000;'>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>DEGAUSS:</b></div></td>
								<td><div style='padding:5px;'>".$port['degauss']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>DIRTY BALLAST:</b></div></td>
								<td><div style='padding:5px;'>".$port['dirty_ballast']."</div></td>
							</tr>
							<tr>
								<td colspan='5' style='background:#fff;'>&nbsp;</td>
							</tr>
							<tr style='background:#f4f5f6; color:#000;'>
								<td><div style='padding:5px; color:#623200;'><b>CRAINS</b></div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>FIXED:</b></div></td>
								<td><div style='padding:5px;'>".$port['cranes_fixed']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>MOBILE:</b></div></td>
								<td><div style='padding:5px;'>".$port['cranes_mobile']."</div></td>
							</tr>
							<tr style='background:#f4f5f6; color:#000;'>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>FLOATING:</b></div></td>
								<td><div style='padding:5px;'>".$port['cranes_floating']."</div></td>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td><div style='padding:5px;'>&nbsp;</div></td>
							</tr>
							<tr>
								<td colspan='5' style='background:#fff;'>&nbsp;</td>
							</tr>
							<tr style='background:#dedddd; color:#000;'>
								<td><div style='padding:5px; color:#623200;'><b>SERVICES</b></div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>LONGSHORE:</b></div></td>
								<td><div style='padding:5px;'>".$port['services_longshore']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>ELECT:</b></div></td>
								<td><div style='padding:5px;'>".$port['services_elect']."</div></td>
							</tr>
							<tr style='background:#dedddd; color:#000;'>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>STEAM:</b></div></td>
								<td><div style='padding:5px;'>".$port['services_steam']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>NAV EQUIP:</b></div></td>
								<td><div style='padding:5px;'>".$port['services_navig_equip']."</div></td>
							</tr>
							<tr style='background:#dedddd; color:#000;'>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>ELECT REPAIR:</b></div></td>
								<td><div style='padding:5px;'>".$port['services_elect_repair']."</div></td>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td><div style='padding:5px;'>".$port['supplies_provisions']."</div></td>
							</tr>
							<tr>
								<td colspan='5' style='background:#fff;'>&nbsp;</td>
							</tr>
							<tr style='background:#f4f5f6; color:#000;'>
								<td><div style='padding:5px; color:#623200;'><b>SUPPLIES</b></div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>PROVISIONS:</b></div></td>
								<td><div style='padding:5px;'>".$port['supplies_provisions']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>WATER:</b></div></td>
								<td><div style='padding:5px;'>".$port['supplies_water']."</div></td>
							</tr>
							<tr style='background:#f4f5f6; color:#000;'>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>FUEL OIL:</b></div></td>
								<td><div style='padding:5px;'>".$port['supplies_fuel_oil']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>DIESEL OIL:</b></div></td>
								<td><div style='padding:5px;'>".$port['supplies_diesel_oil']."</div></td>
							</tr>
							<tr style='background:#f4f5f6; color:#000;'>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>DECK:</b></div></td>
								<td><div style='padding:5px;'>".$port['supplies_deck']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>ENGINE:</b></div></td>
								<td><div style='padding:5px;'>".$port['supplies_engine']."</div></td>
							</tr>
							<tr>
								<td colspan='5' style='background:#fff;'>&nbsp;</td>
							</tr>
							<tr style='background:#dedddd; color:#000;'>
								<td><div style='padding:5px; color:#623200;'><b>REPAIR</b></div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>DESCRIPTION:</b></div></td>
								<td><div style='padding:5px;'>".$port['repairs_code_description']."</div></td>
								<td><div style='padding:5px;'>&nbsp;</div></td>
								<td><div style='padding:5px;'>&nbsp;</div></td>
							</tr>
							<tr>
								<td colspan='5' style='background:#fff;'>&nbsp;</td>
							</tr>
							<tr style='background:#f4f5f6; color:#000;'>
								<td><div style='padding:5px; color:#623200;'><b>DRYDOCK/MARINE RAILWAY</b></div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>DRYDOCK:</b></div></td>
								<td><div style='padding:5px;'>".$port['drydock_marine_railway_code_description_a']."</div></td>
								<td align='right'><div style='padding:5px;color:#073262;'><b>MARINE RAILWAY:</b></div></td>
								<td><div style='padding:5px;'>".$port['drydock_marine_railway_code_description_b']."</div></td>
							</tr>
						</table>
					</td>
				</tr>";
			}
			
			echo "</table>";
		}else{
			echo '<b><center>NO DATA AVAILABLE YET.</center></b>';
		}
	}else{
		echo '<b><center>NO DATA AVAILABLE YET.</center></b>';
	}
}else if(trim($_GET['portname']) && trim($_GET['countryname'])){
	echo '<b><center>YOU CAN ONLY INPUT EITHER PORT NAME OR COUNTRY NAME.</center></b>';
}else{
	echo '<b><center>PLEASE INPUT EITHER PORT NAME OR COUNTRY NAME.</center></b>';
}
?>