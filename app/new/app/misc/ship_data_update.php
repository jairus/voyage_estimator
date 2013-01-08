<?php
@session_start();
include_once(dirname(__FILE__)."/../includes/bootstrap.php");
date_default_timezone_set('UTC');

if($_POST['submitok']==1){
	$print = array();

	$print['MAIN']['IMO_NUMBER'] = $_POST['imo'];
	$print['MAIN']['MMSI_CODE'] = $_POST['MMSI_CODE'];
	$print['MAIN']['STATUS'] = $_POST['STATUS'];
	$print['MAIN']['NAME'] = $_POST['NAME'];
	$print['MAIN']['VESSEL_TYPE'] = $_POST['VESSEL_TYPE'];
	$print['MAIN']['GROSS_TONNAGE'] = $_POST['GROSS_TONNAGE'];
	$print['MAIN']['SUMMER_DWT'] = $_POST['SUMMER_DWT'];
	$print['MAIN']['BUILD'] = $_POST['BUILD'];
	$print['MAIN']['BUILDER'] = $_POST['BUILDER'];
	$print['MAIN']['HOME_PORT'] = $_POST['HOME_PORT'];
	$print['MAIN']['MANAGER'] = $_POST['MANAGER'];
	$print['MAIN']['OWNER'] = $_POST['OWNER'];
	$print['MAIN']['CLASS_SOCIETY'] = $_POST['CLASS_SOCIETY'];
	$print['MAIN']['DUAL_CLASS_SOCIETY'] = $_POST['DUAL_CLASS_SOCIETY'];
	$print['MAIN']['INSURER'] = $_POST['INSURER'];
	$print['GENERAL']['ALTERATION'] = $_POST['ALTERATION'];
	$print['GENERAL']['DEAD_REASON'] = $_POST['DEAD_REASON'];
	$print['GENERAL']['GEAR'] = $_POST['GEAR'];
	$print['GENERAL']['HOME_PORT'] = $_POST['HOME_PORT'];
	$print['GENERAL']['NAVIGATION_AREA'] = $_POST['NAVIGATION_AREA'];
	$print['GENERAL']['REGISTRATION_NUMBER'] = $_POST['REGISTRATION_NUMBER'];
	$print['GENERAL']['SERVICE_LIMIT'] = $_POST['SERVICE_LIMIT'];
	$print['GENERAL']['SPEED_AVERAGE'] = $_POST['SPEED_AVERAGE'];
	$print['GENERAL']['SPEED_ECON'] = $_POST['SPEED_ECON'];
	$print['GENERAL']['SPEED_MAX'] = $_POST['SPEED_MAX'];
	$print['GENERAL']['SPEED_SERVICE'] = $_POST['SPEED_SERVICE'];
	$print['GENERAL']['SPEED_TRIAL'] = $_POST['SPEED_TRIAL'];
	$print['GENERAL']['TRADING_AREAS'] = $_POST['TRADING_AREAS'];
	$print['HISTORICAL']['ALTERATION_DATE'] = $_POST['ALTERATION_DATE'];
	$print['HISTORICAL']['BROKEN_UP'] = $_POST['BROKEN_UP'];
	$print['HISTORICAL']['BUILD_END'] = $_POST['BUILD_END'];
	$print['HISTORICAL']['BUILD_START'] = $_POST['BUILD_START'];
	$print['HISTORICAL']['DATE_OF_ORDER'] = $_POST['DATE_OF_ORDER'];
	$print['HISTORICAL']['DELIVERY_DATE'] = $_POST['DELIVERY_DATE'];
	$print['HISTORICAL']['FIRST_MOVEMENT'] = $_POST['FIRST_MOVEMENT'];
	$print['HISTORICAL']['KEEL_LAID'] = $_POST['KEEL_LAID'];
	$print['HISTORICAL']['LAUNCH_DATE'] = $_POST['LAUNCH_DATE'];
	$print['HISTORICAL']['LOSS_DATE'] = $_POST['LOSS_DATE'];
	$print['HISTORICAL']['PLACE_OF_BUILD'] = $_POST['PLACE_OF_BUILD'];
	$print['HISTORICAL']['STEEL_CUTTING'] = $_POST['STEEL_CUTTING'];
	$print['HISTORICAL']['YARD_NUMBER'] = $_POST['YARD_NUMBER'];
	$print['ANCHOR']['ANCHOR_CHAIN_DIAMETER'] = $_POST['ANCHOR_CHAIN_DIAMETER'];
	$print['ANCHOR']['ANCHOR_HOLDING_ABILITY'] = $_POST['ANCHOR_HOLDING_ABILITY'];
	$print['ANCHOR']['ANCHOR_STRENGTH_LEVEL'] = $_POST['ANCHOR_STRENGTH_LEVEL'];
	$print['CAPACITIES']['ASPHALT'] = $_POST['ASPHALT'];
	$print['CAPACITIES']['BALE'] = $_POST['BALE'];
	$print['CAPACITIES']['BALLAST'] = $_POST['BALLAST'];
	$print['CAPACITIES']['BALLAST_CLEAN'] = $_POST['BALLAST_CLEAN'];
	$print['CAPACITIES']['BALLAST_SEGREGATED'] = $_POST['BALLAST_SEGREGATED'];
	$print['CAPACITIES']['BERTHS'] = $_POST['BERTHS'];
	$print['CAPACITIES']['BUNKER'] = $_POST['BUNKER'];
	$print['CAPACITIES']['CABINS'] = $_POST['CABINS'];
	$print['CAPACITIES']['CARGO_CAPACITY'] = $_POST['CARGO_CAPACITY'];
	$print['CAPACITIES']['CARS'] = $_POST['CARS'];
	$print['CAPACITIES']['CRUDE_CAPACITY'] = $_POST['CRUDE_CAPACITY'];
	$print['CAPACITIES']['DIESEL_OIL'] = $_POST['DIESEL_OIL'];
	$print['CAPACITIES']['FISH_HOLD_VOLUME'] = $_POST['FISH_HOLD_VOLUME'];
	$print['CAPACITIES']['FRESHWATER'] = $_POST['FRESHWATER'];
	$print['CAPACITIES']['FUEL'] = $_POST['FUEL'];
	$print['CAPACITIES']['FUEL_OIL'] = $_POST['FUEL_OIL'];
	$print['CAPACITIES']['GRAIN'] = $_POST['GRAIN'];
	$print['CAPACITIES']['GRAIN_LIQUID'] = $_POST['GRAIN_LIQUID'];
	$print['CAPACITIES']['HOPPERS'] = $_POST['HOPPERS'];
	$print['CAPACITIES']['HYDRAULIC_OIL_CAPACITY'] = $_POST['HYDRAULIC_OIL_CAPACITY'];
	$print['CAPACITIES']['INSULATED'] = $_POST['INSULATED'];
	$print['CAPACITIES']['LIQUID_GAS'] = $_POST['LIQUID_GAS'];
	$print['CAPACITIES']['LIQUID_OIL'] = $_POST['LIQUID_OIL'];
	$print['CAPACITIES']['LORRIES'] = $_POST['LORRIES'];
	$print['CAPACITIES']['LUBE_OIL'] = $_POST['LUBE_OIL'];
	$print['CAPACITIES']['ORE'] = $_POST['ORE'];
	$print['CAPACITIES']['PASSENGERS'] = $_POST['PASSENGERS'];
	$print['CAPACITIES']['RAIL_WAGONS'] = $_POST['RAIL_WAGONS'];
	$print['CAPACITIES']['SLOPS'] = $_POST['SLOPS'];
	$print['CAPACITIES']['TEU'] = $_POST['TEU'];
	$print['CAPACITIES']['TRAILERS'] = $_POST['TRAILERS'];
	$print['CARGO']['CARGO_HANDLING'] = $_POST['CARGO_HANDLING'];
	$print['CARGO']['CARGO_PUMPS'] = $_POST['CARGO_PUMPS'];
	$print['CARGO']['CARGO_SPACE'] = $_POST['CARGO_SPACE'];
	$print['CARGO']['CARGO_TANKS'] = $_POST['CARGO_TANKS'];
	$print['CARGO']['CRANES'] = $_POST['CRANES'];
	$print['CARGO']['DERRICKS'] = $_POST['DERRICKS'];
	$print['CARGO']['HATCHWAYS'] = $_POST['HATCHWAYS'];
	$print['CARGO']['HOLDS'] = $_POST['HOLDS'];
	$print['CARGO']['LARGEST_HATCH'] = $_POST['LARGEST_HATCH'];
	$print['CARGO']['LIFTING_EQUIPMENT'] = $_POST['LIFTING_EQUIPMENT'];
	$print['CLASSIFICATIONS']['CLASS_ASSIGNMENT'] = $_POST['CLASS_ASSIGNMENT'];
	$print['CLASSIFICATIONS']['CLASS_NOTATION'] = $_POST['CLASS_NOTATION'];
	$print['CLASSIFICATIONS']['LAST_DRYDOCK_SURVEY'] = $_POST['LAST_DRYDOCK_SURVEY'];
	$print['CLASSIFICATIONS']['LAST_HULL_SURVEY'] = $_POST['LAST_HULL_SURVEY'];
	$print['CLASSIFICATIONS']['LAST_SPECIAL_SURVEY'] = $_POST['LAST_SPECIAL_SURVEY'];
	$print['CLASSIFICATIONS']['NEXT_DRYDOCK_SURVEY'] = $_POST['NEXT_DRYDOCK_SURVEY'];
	$print['CLASSIFICATIONS']['NEXT_HULL_SURVEY'] = $_POST['NEXT_HULL_SURVEY'];
	$print['CLASSIFICATIONS']['NEXT_SPECIAL_SURVEY'] = $_POST['NEXT_SPECIAL_SURVEY'];
	$print['CREW']['ACTUAL_MANNING_OFFICERS'] = $_POST['ACTUAL_MANNING_OFFICERS'];
	$print['CREW']['ACTUAL_MANNING_RATINGS'] = $_POST['ACTUAL_MANNING_RATINGS'];
	$print['CREW']['LANGUAGE_USED_COMMON'] = $_POST['LANGUAGE_USED_COMMON'];
	$print['CREW']['LANGUAGE_USED_VESSEL_OPERATOR'] = $_POST['LANGUAGE_USED_VESSEL_OPERATOR'];
	$print['CREW']['MINIMUM_MANNING_REQUIRED_OFFICERS'] = $_POST['MINIMUM_MANNING_REQUIRED_OFFICERS'];
	$print['CREW']['MINIMUM_MANNING_REQUIRED_RATINGS'] = $_POST['MINIMUM_MANNING_REQUIRED_RATINGS'];
	$print['CREW']['TOTAL_CREW'] = $_POST['TOTAL_CREW'];
	$print['DIMENSIONS']['BOW_TO_BRIDGE'] = $_POST['BOW_TO_BRIDGE'];
	$print['DIMENSIONS']['BOW_TO_CENTER_MANIFOLD'] = $_POST['BOW_TO_CENTER_MANIFOLD'];
	$print['DIMENSIONS']['BREADTH_EXTREME'] = $_POST['BREADTH_EXTREME'];
	$print['DIMENSIONS']['BREADTH_MOULDED'] = $_POST['BREADTH_MOULDED'];
	$print['DIMENSIONS']['BREADTH_REGISTERED'] = $_POST['BREADTH_REGISTERED'];
	$print['DIMENSIONS']['BRIDGE'] = $_POST['BRIDGE'];
	$print['DIMENSIONS']['BULB_LENGTH_FROM_FP'] = $_POST['BULB_LENGTH_FROM_FP'];
	$print['DIMENSIONS']['DEPTH'] = $_POST['DEPTH'];
	$print['DIMENSIONS']['DRAUGHT'] = $_POST['DRAUGHT'];
	$print['DIMENSIONS']['FORECASTLE'] = $_POST['FORECASTLE'];
	$print['DIMENSIONS']['HEIGHT'] = $_POST['HEIGHT'];
	$print['DIMENSIONS']['KEEL_TO_MASTHEAD'] = $_POST['KEEL_TO_MASTHEAD'];
	$print['DIMENSIONS']['LENGTH_B_W_PERPENDICULARS'] = $_POST['LENGTH_B_W_PERPENDICULARS'];
	$print['DIMENSIONS']['LENGTH_ON_DECK'] = $_POST['LENGTH_ON_DECK'];
	$print['DIMENSIONS']['LENGTH_OVERALL'] = $_POST['LENGTH_OVERALL'];
	$print['DIMENSIONS']['LENGTH_REGISTERED'] = $_POST['LENGTH_REGISTERED'];
	$print['DIMENSIONS']['LENGTH_WATERLINE'] = $_POST['LENGTH_WATERLINE'];
	$print['DIMENSIONS']['LIGHTSHIP_PARALLEL_BODY'] = $_POST['LIGHTSHIP_PARALLEL_BODY'];
	$print['DIMENSIONS']['NORMAL_BALLAST_PARALLEL_BODY'] = $_POST['NORMAL_BALLAST_PARALLEL_BODY'];
	$print['DIMENSIONS']['PARALLEL_BODY_LENGTH_AT_SUMMER_DWT'] = $_POST['PARALLEL_BODY_LENGTH_AT_SUMMER_DWT'];
	$print['DIMENSIONS']['POOP'] = $_POST['POOP'];
	$print['DIMENSIONS']['QUARTERDECK'] = $_POST['QUARTERDECK'];
	$print['ENGINE']['ENGINE_#'] = $_POST['ENGINE_NUMBER'];
	$print['ENGINE']['ENGINE_BORE'] = $_POST['ENGINE_BORE'];
	$print['ENGINE']['ENGINE_BUILD_YEAR'] = $_POST['ENGINE_BUILD_YEAR'];
	$print['ENGINE']['ENGINE_BUILDER'] = $_POST['ENGINE_BUILDER'];
	$print['ENGINE']['ENGINE_CYLINDERS'] = $_POST['ENGINE_CYLINDERS'];
	$print['ENGINE']['ENGINE_MODEL'] = $_POST['ENGINE_MODEL'];
	$print['ENGINE']['ENGINE_POWER'] = $_POST['ENGINE_POWER'];
	$print['ENGINE']['ENGINE_RATIO'] = $_POST['ENGINE_RATIO'];
	$print['ENGINE']['ENGINE_RPM'] = $_POST['ENGINE_RPM'];
	$print['ENGINE']['ENGINE_STROKE'] = $_POST['ENGINE_STROKE'];
	$print['ENGINE']['ENGINE_TYPE'] = $_POST['ENGINE_TYPE'];
	$print['ENGINE']['FUEL_CONSUMPTION'] = $_POST['FUEL_CONSUMPTION'];
	$print['ENGINE']['FUEL_TYPE'] = $_POST['FUEL_TYPE'];
	$print['ENGINE']['PROPELLER'] = $_POST['PROPELLER'];
	$print['ENGINE']['PROPELLING_TYPE'] = $_POST['PROPELLING_TYPE'];
	$print['LOADLINES']['DEADWEIGHT_LIGHTSHIP'] = $_POST['DEADWEIGHT_LIGHTSHIP'];
	$print['LOADLINES']['DEADWEIGHT_MAXIMUM_ASSIGNED'] = $_POST['DEADWEIGHT_MAXIMUM_ASSIGNED'];
	$print['LOADLINES']['DEADWEIGHT_NORMAL_BALLAST'] = $_POST['DEADWEIGHT_NORMAL_BALLAST'];
	$print['LOADLINES']['DEADWEIGHT_SEGREGATED_BALLAST'] = $_POST['DEADWEIGHT_SEGREGATED_BALLAST'];
	$print['LOADLINES']['DEADWEIGHT_TROPICAL'] = $_POST['DEADWEIGHT_TROPICAL'];
	$print['LOADLINES']['DEADWEIGHT_WINTER'] = $_POST['DEADWEIGHT_WINTER'];
	$print['LOADLINES']['DISPLACEMENT_LIGHTSHIP'] = $_POST['DISPLACEMENT_LIGHTSHIP'];
	$print['LOADLINES']['DISPLACEMENT_NORMAL_BALLAST'] = $_POST['DISPLACEMENT_NORMAL_BALLAST'];
	$print['LOADLINES']['DISPLACEMENT_SEGREGATED_BALLAST'] = $_POST['DISPLACEMENT_SEGREGATED_BALLAST'];
	$print['LOADLINES']['DISPLACEMENT_SUMMER'] = $_POST['DISPLACEMENT_SUMMER'];
	$print['LOADLINES']['DISPLACEMENT_TROPICAL'] = $_POST['DISPLACEMENT_TROPICAL'];
	$print['LOADLINES']['DISPLACEMENT_WINTER'] = $_POST['DISPLACEMENT_WINTER'];
	$print['LOADLINES']['DRAFT_LIGHTSHIP'] = $_POST['DRAFT_LIGHTSHIP'];
	$print['LOADLINES']['DRAFT_NORMAL_BALLAST'] = $_POST['DRAFT_NORMAL_BALLAST'];
	$print['LOADLINES']['DRAFT_SEGREGATED_BALLAST'] = $_POST['DRAFT_SEGREGATED_BALLAST'];
	$print['LOADLINES']['DRAFT_SUMMER'] = $_POST['DRAFT_SUMMER'];
	$print['LOADLINES']['DRAFT_TROPICAL'] = $_POST['DRAFT_TROPICAL'];
	$print['LOADLINES']['DRAFT_WINTER'] = $_POST['DRAFT_WINTER'];
	$print['LOADLINES']['DRAUGHT_AFT_NORMAL_BALLAST'] = $_POST['DRAUGHT_AFT_NORMAL_BALLAST'];
	$print['LOADLINES']['DRAUGHT_FORE_NORMAL_BALLAST'] = $_POST['DRAUGHT_FORE_NORMAL_BALLAST'];
	$print['LOADLINES']['FREEBOARD_C1'] = $_POST['FREEBOARD_C1'];
	$print['LOADLINES']['FREEBOARD_LIGHTSHIP'] = $_POST['FREEBOARD_LIGHTSHIP'];
	$print['LOADLINES']['FREEBOARD_NORMAL_BALLAST'] = $_POST['FREEBOARD_NORMAL_BALLAST'];
	$print['LOADLINES']['FREEBOARD_SEGREGATED_BALLAST'] = $_POST['FREEBOARD_SEGREGATED_BALLAST'];
	$print['LOADLINES']['FREEBOARD_SUMMER'] = $_POST['FREEBOARD_SUMMER'];
	$print['LOADLINES']['FREEBOARD_TROPICAL'] = $_POST['FREEBOARD_TROPICAL'];
	$print['LOADLINES']['FREEBOARD_WINTER'] = $_POST['FREEBOARD_WINTER'];
	$print['LOADLINES']['FWA_SUMMER_DRAFT'] = $_POST['FWA_SUMMER_DRAFT'];
	$print['LOADLINES']['TPC_IMMERSION_SUMMER_DRAFT'] = $_POST['TPC_IMMERSION_SUMMER_DRAFT'];
	$print['STRUCTURES']['BULKHEADS'] = $_POST['BULKHEADS'];
	$print['STRUCTURES']['CONTINUOUS_DECKS'] = $_POST['CONTINUOUS_DECKS'];
	$print['STRUCTURES']['DECK_ERECTIONS'] = $_POST['DECK_ERECTIONS'];
	$print['STRUCTURES']['DECKS_NUMBER'] = $_POST['DECKS_NUMBER'];
	$print['STRUCTURES']['HULL_MATERIAL'] = $_POST['HULL_MATERIAL'];
	$print['STRUCTURES']['HULL_TYPE'] = $_POST['HULL_TYPE'];
	$print['STRUCTURES']['LONGITUDINAL_BULKHEADS'] = $_POST['LONGITUDINAL_BULKHEADS'];
	$print['STRUCTURES']['LONGITUDINAL_FRAMES'] = $_POST['LONGITUDINAL_FRAMES'];
	$print['STRUCTURES']['RO-RO_LANES'] = $_POST['RO_RO_LANES'];
	$print['STRUCTURES']['RO-RO_RAMPS'] = $_POST['RO_RO_RAMPS'];
	$print['STRUCTURES']['SUPERSTRUCTURES'] = $_POST['SUPERSTRUCTURES'];
	$print['STRUCTURES']['TRANSVERSE_BULKHEADS'] = $_POST['TRANSVERSE_BULKHEADS'];
	$print['STRUCTURES']['WATERTIGHT_BULKHEADS'] = $_POST['WATERTIGHT_BULKHEADS'];
	$print['STRUCTURES']['WATERTIGHT_COMPARTMENTS'] = $_POST['WATERTIGHT_COMPARTMENTS'];
	$print['TONNAGES']['NET_TONNAGE'] = $_POST['NET_TONNAGE'];
	$print['TONNAGES']['PANAMA_GROSS_TONNAGE'] = $_POST['PANAMA_GROSS_TONNAGE'];
	$print['TONNAGES']['PANAMA_NET_TONNAGE'] = $_POST['PANAMA_NET_TONNAGE'];
	$print['TONNAGES']['PANAMA_TONNAGE'] = $_POST['PANAMA_TONNAGE'];
	$print['TONNAGES']['SUEZ_GROSS_TONNAGE'] = $_POST['SUEZ_GROSS_TONNAGE'];
	$print['TONNAGES']['SUEZ_NET_TONNAGE'] = $_POST['SUEZ_NET_TONNAGE'];
	$print['TONNAGES']['SUEZ_TONNAGE'] = $_POST['SUEZ_TONNAGE'];
	$print['COMMUNICATION']['CALL_SIGN'] = $_POST['CALL_SIGN'];
	$print['COMMUNICATION']['SATCOM_ANSWER_BACK'] = $_POST['SATCOM_ANSWER_BACK'];
	$print['COMMUNICATION']['SATCOM_ID'] = $_POST['SATCOM_ID'];
	$print['PORT STATE CONTROLS']['DATE'] = $_POST['DATE'];
	$print['PORT STATE CONTROLS']['TYPE'] = $_POST['TYPE'];
	$print['PORT STATE CONTROLS']['ORGANIZATION'] = $_POST['ORGANIZATION'];
	$print['PORT STATE CONTROLS']['AUTHORITY'] = $_POST['AUTHORITY'];
	$print['PORT STATE CONTROLS']['PLACE'] = $_POST['PLACE'];
	$print['PORT STATE CONTROLS']['DETENTION'] = $_POST['DETENTION'];
	$print['PORT STATE CONTROLS']['DEFICIENCY'] = $_POST['DEFICIENCY'];
	$print['PORT STATE CONTROLS']['PSC'] = $_POST['PSC'];
	$print['PORT STATE CONTROLS']['DETENTIONS'] = $_POST['DETENTIONS'];
	$print['PORT STATE CONTROLS']['DEFICIENCIES'] = $_POST['DEFICIENCIES'];
	$print['CERTIFICATES']['CERTIFICATE_TYPE'] = $_POST['CERTIFICATE_TYPE'];
	$print['CERTIFICATES']['ISSUED'] = $_POST['ISSUED'];
	$print['CERTIFICATES']['FROM'] = $_POST['FROM'];
	$print['CERTIFICATES']['EXPIRES'] = $_POST['EXPIRES'];
	$print['CERTIFICATES']['CERTIFICATE'] = $_POST['CERTIFICATE'];
	
	echo '<pre>';
	die(print_r($print));
	
	header('Location: ship_data_update.php?msg=Update successfull!');
}

if(!trim($_GET['imo'])){
	echo 'IMO is not found!';
}else{
	$sql = "SELECT * FROM _xvas_shipdata_dry WHERE imo='".$_GET['imo']."' LIMIT 0,1";
	$r = dbQuery($sql);
	
	if(!trim($r[0]['imo'])){
		echo 'IMO does not exist!';
	}else{
		$IMO_NUMBER = $r[0]['imo'];
		$MMSI_CODE = getValue($r[0]['data'], 'MMSI_CODE');
		$STATUS	 = getValue($r[0]['data'], 'STATUS');
		$NAME = getValue($r[0]['data'], 'NAME');
		$VESSEL_TYPE = getValue($r[0]['data'], 'VESSEL_TYPE');
		$GROSS_TONNAGE = getValue($r[0]['data'], 'GROSS_TONNAGE');
		$SUMMER_DWT = getValue($r[0]['data'], 'SUMMER_DWT');
		$BUILD = getValue($r[0]['data'], 'BUILD');
		$BUILDER = getValue($r[0]['data'], 'BUILDER');
		
		$FLAG = getValue($r[0]['data'], 'LAST_KNOWN_FLAG');
		if($FLAG==""){
			$FLAG = getValue($r[0]['data'], 'FLAG');
			$FLAG_IMAGE = getFlagImage($FLAG);
		}else{
			$FLAG = $FLAG;
			$FLAG_IMAGE = getFlagImage($FLAG);
		}
		
		$HOME_PORT = getValue($r[0]['data'], 'HOME_PORT');
		$MANAGER = getValue($r[0]['data'], 'MANAGER');
		$OWNER = getValue($r[0]['data'], 'OWNER');
		$CLASS_SOCIETY = getValue($r[0]['data'], 'CLASS_SOCIETY');
		$DUAL_CLASS_SOCIETY = getValue($r[0]['data'], 'DUAL_CLASS_SOCIETY');
		$INSURER = getValue($r[0]['data'], 'INSURER');
		$ALTERATION = getValue($r[0]['data'], 'ALTERATION');
		$DEAD_REASON = getValue($r[0]['data'], 'DEAD_REASON');
		$GEAR = getValue($r[0]['data'], 'GEAR');
		$HOME_PORT = getValue($r[0]['data'], 'HOME_PORT');
		$NAVIGATION_AREA = getValue($r[0]['data'], 'NAVIGATION_AREA');
		$REGISTRATION_NUMBER = getValue($r[0]['data'], 'REGISTRATION_NUMBER');
		$SERVICE_LIMIT = getValue($r[0]['data'], 'SERVICE_LIMIT');
		$SPEED_AVERAGE = getValue($r[0]['data'], 'SPEED_AVERAGE');
		$SPEED_ECON = getValue($r[0]['data'], 'SPEED_ECON');
		$SPEED_MAX = getValue($r[0]['data'], 'SPEED_MAX');
		$SPEED_SERVICE = getValue($r[0]['data'], 'SPEED_SERVICE');
		$SPEED_TRIAL = getValue($r[0]['data'], 'SPEED_TRIAL');
		$TRADING_AREAS = getValue($r[0]['data'], 'TRADING_AREAS');
		$ALTERATION_DATE = getValue($r[0]['data'], 'ALTERATION_DATE');
		$BROKEN_UP = getValue($r[0]['data'], 'BROKEN_UP');
		$BUILD_END = getValue($r[0]['data'], 'BUILD_END');
		$BUILD_START = getValue($r[0]['data'], 'BUILD_START');
		$DATE_OF_ORDER = getValue($r[0]['data'], 'DATE_OF_ORDER');
		$DELIVERY_DATE = getValue($r[0]['data'], 'DELIVERY_DATE');
		$FIRST_MOVEMENT = getValue($r[0]['data'], 'FIRST_MOVEMENT');
		$KEEL_LAID = getValue($r[0]['data'], 'KEEL_LAID');
		$LAUNCH_DATE = getValue($r[0]['data'], 'LAUNCH_DATE');
		$LOSS_DATE = getValue($r[0]['data'], 'LOSS_DATE');
		$PLACE_OF_BUILD = getValue($r[0]['data'], 'PLACE_OF_BUILD');
		$STEEL_CUTTING = getValue($r[0]['data'], 'STEEL_CUTTING');
		$YARD_NUMBER = getValue($r[0]['data'], 'YARD_NUMBER');
		$ANCHOR_CHAIN_DIAMETER = getValue($r[0]['data'], 'ANCHOR_CHAIN_DIAMETER');
		$ANCHOR_HOLDING_ABILITY = getValue($r[0]['data'], 'ANCHOR_HOLDING_ABILITY');
		$ANCHOR_STRENGTH_LEVEL = getValue($r[0]['data'], 'ANCHOR_STRENGTH_LEVEL');
		$ASPHALT = getValue($r[0]['data'], 'ASPHALT');
		$BALE = getValue($r[0]['data'], 'BALE');
		$BALLAST = getValue($r[0]['data'], 'BALLAST');
		$BALLAST_CLEAN = getValue($r[0]['data'], 'BALLAST_CLEAN');
		$BALLAST_SEGREGATED = getValue($r[0]['data'], 'BALLAST_SEGREGATED');
		$BERTHS = getValue($r[0]['data'], 'BERTHS');
		$BUNKER = getValue($r[0]['data'], 'BUNKER');
		$CABINS = getValue($r[0]['data'], 'CABINS');
		$CARGO_CAPACITY = getValue($r[0]['data'], 'CARGO_CAPACITY');
		$CARS = getValue($r[0]['data'], 'CARS');
		$CRUDE_CAPACITY = getValue($r[0]['data'], 'CRUDE_CAPACITY');
		$DIESEL_OIL = getValue($r[0]['data'], 'DIESEL_OIL');
		$FISH_HOLD_VOLUME = getValue($r[0]['data'], 'FISH_HOLD_VOLUME');
		$FRESHWATER = getValue($r[0]['data'], 'FRESHWATER');
		$FUEL = getValue($r[0]['data'], 'FUEL');
		$FUEL_OIL = getValue($r[0]['data'], 'FUEL_OIL');
		$GRAIN = getValue($r[0]['data'], 'GRAIN');
		$GRAIN_LIQUID = getValue($r[0]['data'], 'GRAIN_LIQUID');
		$HOPPERS = getValue($r[0]['data'], 'HOPPERS');
		$HYDRAULIC_OIL_CAPACITY = getValue($r[0]['data'], 'HYDRAULIC_OIL_CAPACITY');
		$INSULATED = getValue($r[0]['data'], 'INSULATED');
		$LIQUID_GAS = getValue($r[0]['data'], 'LIQUID_GAS');
		$LIQUID_OIL = getValue($r[0]['data'], 'LIQUID_OIL');
		$LORRIES = getValue($r[0]['data'], 'LORRIES');
		$LUBE_OIL = getValue($r[0]['data'], 'LUBE_OIL');
		$ORE = getValue($r[0]['data'], 'ORE');
		$PASSENGERS = getValue($r[0]['data'], 'PASSENGERS');
		$RAIL_WAGONS = getValue($r[0]['data'], 'RAIL_WAGONS');
		$SLOPS = getValue($r[0]['data'], 'SLOPS');
		$TEU = getValue($r[0]['data'], 'TEU');
		$TRAILERS = getValue($r[0]['data'], 'TRAILERS');
		$CARGO_HANDLING = getValue($r[0]['data'], 'CARGO_HANDLING');
		$CARGO_PUMPS = getValue($r[0]['data'], 'CARGO_PUMPS');
		$CARGO_SPACE = getValue($r[0]['data'], 'CARGO_SPACE');
		$CARGO_TANKS = getValue($r[0]['data'], 'CARGO_TANKS');
		$CRANES = getValue($r[0]['data'], 'CRANES');
		$DERRICKS = getValue($r[0]['data'], 'DERRICKS');
		$HATCHWAYS = getValue($r[0]['data'], 'HATCHWAYS');
		$HOLDS = getValue($r[0]['data'], 'HOLDS');
		$LARGEST_HATCH = getValue($r[0]['data'], 'LARGEST_HATCH');
		$LIFTING_EQUIPMENT = getValue($r[0]['data'], 'LIFTING_EQUIPMENT');
		$CLASS_ASSIGNMENT = getValue($r[0]['data'], 'CLASS_ASSIGNMENT');
		$CLASS_NOTATION = getValue($r[0]['data'], 'CLASS_NOTATION');
		$LAST_DRYDOCK_SURVEY = getValue($r[0]['data'], 'LAST_DRYDOCK_SURVEY');
		$LAST_HULL_SURVEY = getValue($r[0]['data'], 'LAST_HULL_SURVEY');
		$LAST_SPECIAL_SURVEY = getValue($r[0]['data'], 'LAST_SPECIAL_SURVEY');
		$NEXT_DRYDOCK_SURVEY = getValue($r[0]['data'], 'NEXT_DRYDOCK_SURVEY');
		$NEXT_HULL_SURVEY = getValue($r[0]['data'], 'NEXT_HULL_SURVEY');
		$NEXT_SPECIAL_SURVEY = getValue($r[0]['data'], 'NEXT_SPECIAL_SURVEY');
		$ACTUAL_MANNING_OFFICERS = getValue($r[0]['data'], 'ACTUAL_MANNING_OFFICERS');
		$ACTUAL_MANNING_RATINGS = getValue($r[0]['data'], 'ACTUAL_MANNING_RATINGS');
		$LANGUAGE_USED_COMMON = getValue($r[0]['data'], 'LANGUAGE_USED_COMMON');
		$LANGUAGE_USED_VESSEL_OPERATOR = getValue($r[0]['data'], 'LANGUAGE_USED_VESSEL_OPERATOR');
		$MINIMUM_MANNING_REQUIRED_OFFICERS = getValue($r[0]['data'], 'MINIMUM_MANNING_REQUIRED_OFFICERS');
		$MINIMUM_MANNING_REQUIRED_RATINGS = getValue($r[0]['data'], 'MINIMUM_MANNING_REQUIRED_RATINGS');
		$TOTAL_CREW = getValue($r[0]['data'], 'TOTAL_CREW');
		$BOW_TO_BRIDGE = getValue($r[0]['data'], 'BOW_TO_BRIDGE');
		$BOW_TO_CENTER_MANIFOLD = getValue($r[0]['data'], 'BOW_TO_CENTER_MANIFOLD');
		$BREADTH_EXTREME = getValue($r[0]['data'], 'BREADTH_EXTREME');
		$BREADTH_MOULDED = getValue($r[0]['data'], 'BREADTH_MOULDED');
		$BREADTH_REGISTERED = getValue($r[0]['data'], 'BREADTH_REGISTERED');
		$BRIDGE = getValue($r[0]['data'], 'BRIDGE');
		$BULB_LENGTH_FROM_FP = getValue($r[0]['data'], 'BULB_LENGTH_FROM_FP');
		$DEPTH = getValue($r[0]['data'], 'DEPTH');
		$DRAUGHT = getValue($r[0]['data'], 'DRAUGHT');
		$FORECASTLE = getValue($r[0]['data'], 'FORECASTLE');
		$HEIGHT = getValue($r[0]['data'], 'HEIGHT');
		$KEEL_TO_MASTHEAD = getValue($r[0]['data'], 'KEEL_TO_MASTHEAD');
		$LENGTH_B_W_PERPENDICULARS = getValue($r[0]['data'], 'LENGTH_B_W_PERPENDICULARS');
		$LENGTH_ON_DECK = getValue($r[0]['data'], 'LENGTH_ON_DECK');
		$LENGTH_OVERALL = getValue($r[0]['data'], 'LENGTH_OVERALL');
		$LENGTH_REGISTERED = getValue($r[0]['data'], 'LENGTH_REGISTERED');
		$LENGTH_WATERLINE = getValue($r[0]['data'], 'LENGTH_WATERLINE');
		$LIGHTSHIP_PARALLEL_BODY = getValue($r[0]['data'], 'LIGHTSHIP_PARALLEL_BODY');
		$NORMAL_BALLAST_PARALLEL_BODY = getValue($r[0]['data'], 'NORMAL_BALLAST_PARALLEL_BODY');
		$PARALLEL_BODY_LENGTH_AT_SUMMER_DWT = getValue($r[0]['data'], 'PARALLEL_BODY_LENGTH_AT_SUMMER_DWT');
		$POOP = getValue($r[0]['data'], 'POOP');
		$QUARTERDECK = getValue($r[0]['data'], 'QUARTERDECK');
		$ENGINE_NUMBER = getValue($r[0]['data'], 'ENGINE_#');
		$ENGINE_BORE = getValue($r[0]['data'], 'ENGINE_BORE');
		$ENGINE_BUILD_YEAR = getValue($r[0]['data'], 'ENGINE_BUILD_YEAR');
		$ENGINE_BUILDER = getValue($r[0]['data'], 'ENGINE_BUILDER');
		$ENGINE_CYLINDERS = getValue($r[0]['data'], 'ENGINE_CYLINDERS');
		$ENGINE_MODEL = getValue($r[0]['data'], 'ENGINE_MODEL');
		$ENGINE_POWER = getValue($r[0]['data'], 'ENGINE_POWER');
		$ENGINE_RATIO = getValue($r[0]['data'], 'ENGINE_RATIO');
		$ENGINE_RPM = getValue($r[0]['data'], 'ENGINE_RPM');
		$ENGINE_STROKE = getValue($r[0]['data'], 'ENGINE_STROKE');
		$ENGINE_TYPE = getValue($r[0]['data'], 'ENGINE_TYPE');
		$FUEL_CONSUMPTION = getValue($r[0]['data'], 'FUEL_CONSUMPTION');
		$FUEL_TYPE = getValue($r[0]['data'], 'FUEL_TYPE');
		$PROPELLER = getValue($r[0]['data'], 'PROPELLER');
		$PROPELLING_TYPE = getValue($r[0]['data'], 'PROPELLING_TYPE');
		$DEADWEIGHT_LIGHTSHIP = getValue($r[0]['data'], 'DEADWEIGHT_LIGHTSHIP');
		$DEADWEIGHT_MAXIMUM_ASSIGNED = getValue($r[0]['data'], 'DEADWEIGHT_MAXIMUM_ASSIGNED');
		$DEADWEIGHT_NORMAL_BALLAST = getValue($r[0]['data'], 'DEADWEIGHT_NORMAL_BALLAST');
		$DEADWEIGHT_SEGREGATED_BALLAST = getValue($r[0]['data'], 'DEADWEIGHT_SEGREGATED_BALLAST');
		$DEADWEIGHT_TROPICAL = getValue($r[0]['data'], 'DEADWEIGHT_TROPICAL');
		$DEADWEIGHT_WINTER = getValue($r[0]['data'], 'DEADWEIGHT_WINTER');
		$DISPLACEMENT_LIGHTSHIP = getValue($r[0]['data'], 'DISPLACEMENT_LIGHTSHIP');
		$DISPLACEMENT_NORMAL_BALLAST = getValue($r[0]['data'], 'DISPLACEMENT_NORMAL_BALLAST');
		$DISPLACEMENT_SEGREGATED_BALLAST = getValue($r[0]['data'], 'DISPLACEMENT_SEGREGATED_BALLAST');
		$DISPLACEMENT_SUMMER = getValue($r[0]['data'], 'DISPLACEMENT_SUMMER');
		$DISPLACEMENT_TROPICAL = getValue($r[0]['data'], 'DISPLACEMENT_TROPICAL');
		$DISPLACEMENT_WINTER = getValue($r[0]['data'], 'DISPLACEMENT_WINTER');
		$DRAFT_LIGHTSHIP = getValue($r[0]['data'], 'DRAFT_LIGHTSHIP');
		$DRAFT_NORMAL_BALLAST = getValue($r[0]['data'], 'DRAFT_NORMAL_BALLAST');
		$DRAFT_SEGREGATED_BALLAST = getValue($r[0]['data'], 'DRAFT_SEGREGATED_BALLAST');
		$DRAFT_SUMMER = getValue($r[0]['data'], 'DRAFT_SUMMER');
		$DRAFT_TROPICAL = getValue($r[0]['data'], 'DRAFT_TROPICAL');
		$DRAFT_WINTER = getValue($r[0]['data'], 'DRAFT_WINTER');
		$DRAUGHT_AFT_NORMAL_BALLAST = getValue($r[0]['data'], 'DRAUGHT_AFT_NORMAL_BALLAST');
		$DRAUGHT_FORE_NORMAL_BALLAST = getValue($r[0]['data'], 'DRAUGHT_FORE_NORMAL_BALLAST');
		$FREEBOARD_C1 = getValue($r[0]['data'], 'FREEBOARD_C1');
		$FREEBOARD_LIGHTSHIP = getValue($r[0]['data'], 'FREEBOARD_LIGHTSHIP');
		$FREEBOARD_NORMAL_BALLAST = getValue($r[0]['data'], 'FREEBOARD_NORMAL_BALLAST');
		$FREEBOARD_SEGREGATED_BALLAST = getValue($r[0]['data'], 'FREEBOARD_SEGREGATED_BALLAST');
		$FREEBOARD_SUMMER = getValue($r[0]['data'], 'FREEBOARD_SUMMER');
		$FREEBOARD_TROPICAL = getValue($r[0]['data'], 'FREEBOARD_TROPICAL');
		$FREEBOARD_WINTER = getValue($r[0]['data'], 'FREEBOARD_WINTER');
		$FWA_SUMMER_DRAFT = getValue($r[0]['data'], 'FWA_SUMMER_DRAFT');
		$TPC_IMMERSION_SUMMER_DRAFT = getValue($r[0]['data'], 'TPC_IMMERSION_SUMMER_DRAFT');
		$BULKHEADS = getValue($r[0]['data'], 'BULKHEADS');
		$CONTINUOUS_DECKS = getValue($r[0]['data'], 'CONTINUOUS_DECKS');
		$DECK_ERECTIONS = getValue($r[0]['data'], 'DECK_ERECTIONS');
		$DECKS_NUMBER = getValue($r[0]['data'], 'DECKS_NUMBER');
		$HULL_MATERIAL = getValue($r[0]['data'], 'HULL_MATERIAL');
		$HULL_TYPE = getValue($r[0]['data'], 'HULL_TYPE');
		$LONGITUDINAL_BULKHEADS = getValue($r[0]['data'], 'LONGITUDINAL_BULKHEADS');
		$LONGITUDINAL_FRAMES = getValue($r[0]['data'], 'LONGITUDINAL_FRAMES');
		$RO_RO_LANES = getValue($r[0]['data'], 'RO-RO_LANES');
		$RO_RO_RAMPS = getValue($r[0]['data'], 'RO-RO_RAMPS');
		$SUPERSTRUCTURES = getValue($r[0]['data'], 'SUPERSTRUCTURES');
		$TRANSVERSE_BULKHEADS = getValue($r[0]['data'], 'TRANSVERSE_BULKHEADS');
		$WATERTIGHT_BULKHEADS = getValue($r[0]['data'], 'WATERTIGHT_BULKHEADS');
		$WATERTIGHT_COMPARTMENTS = getValue($r[0]['data'], 'WATERTIGHT_COMPARTMENTS');
		$NET_TONNAGE = getValue($r[0]['data'], 'NET_TONNAGE');
		$PANAMA_GROSS_TONNAGE = getValue($r[0]['data'], 'PANAMA_GROSS_TONNAGE');
		$PANAMA_NET_TONNAGE = getValue($r[0]['data'], 'PANAMA_NET_TONNAGE');
		$PANAMA_TONNAGE = getValue($r[0]['data'], 'PANAMA_TONNAGE');
		$SUEZ_GROSS_TONNAGE = getValue($r[0]['data'], 'SUEZ_GROSS_TONNAGE');
		$SUEZ_NET_TONNAGE = getValue($r[0]['data'], 'SUEZ_NET_TONNAGE');
		$SUEZ_TONNAGE = getValue($r[0]['data'], 'SUEZ_TONNAGE');
		$CALL_SIGN = getValue($r[0]['data'], 'CALL_SIGN');
		$SATCOM_ANSWER_BACK = getValue($r[0]['data'], 'SATCOM_ANSWER_BACK');
		$SATCOM_ID = getValue($r[0]['data'], 'SATCOM_ID');
		$DATE = getValue($r[0]['data'], 'DATE');
		$TYPE = getValue($r[0]['data'], 'TYPE');
		$ORGANIZATION = getValue($r[0]['data'], 'ORGANIZATION');
		$AUTHORITY = getValue($r[0]['data'], 'AUTHORITY');
		$PLACE = getValue($r[0]['data'], 'PLACE');
		$DETENTION = getValue($r[0]['data'], 'DETENTION');
		$DEFICIENCY = getValue($r[0]['data'], 'DEFICIENCY');
		$PSC = getValue($r[0]['data'], 'PSC');
		$DETENTIONS = getValue($r[0]['data'], 'DETENTIONS');
		$DEFICIENCIES = getValue($r[0]['data'], 'DEFICIENCIES');
		$CERTIFICATE_TYPE = getValue($r[0]['data'], 'CERTIFICATE_TYPE');
		$ISSUED = getValue($r[0]['data'], 'ISSUED');
		$FROM = getValue($r[0]['data'], 'FROM');
		$EXPIRES = getValue($r[0]['data'], 'EXPIRES');
		$CERTIFICATE = getValue($r[0]['data'], 'CERTIFICATE');
		?>
		<script language="JavaScript">
		function saveForm(){
			var submitok = 1;
			
			alertmsg = "";
			
			if(document.inputfrm.IMO_NUMBER.value==""){ 
				alertmsg="Please enter the IMO NUMBER\n"; submitok = 0; 
				document.inputfrm.submitok.value=0
			}else if(document.inputfrm.IMO_NUMBER.value.length!=7){
				alertmsg="IMO NUMBER should be 7 digits\n"; submitok = 0; 
				document.inputfrm.submitok.value=0
			}else{
				document.inputfrm.submitok.value=1
			}
			
			if(submitok==1){document.inputfrm.submit();}
			else{alert(alertmsg);}
		}
		</script>
		<style>
		*{
			font-size:11px;
			font-family:Verdana, Arial, Helvetica, sans-serif;
		}
		
		.main_title{
			font-weight:bold;
			font-size:24px;
		}
		
		.title{
			font-weight:bold;
		}
		
		.btn_1{
			border:1px solid #333333;
			background-color:#000000;
			color:#CCCCCC;
			padding:3px 10px;
			cursor:pointer;
		}
		</style>
		<form id="inputfrm_id" name="inputfrm" method="post" enctype="multipart/form-data">
		<table width="1000" border="0" cellspacing="0" cellpadding="0">
		  <?php if(isset($_GET['msg'])){ ?>
		  <tr>
			<td colspan="2" style="color:#FF0000; font-weight:bold;"><?php echo $_GET['msg']; ?></td>
		  </tr>
		  <?php }else{ ?>
		  <tr>
			<td colspan="2" class="main_title">MAIN</td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td width="300" class="title">IMO NUMBER</td>
			<td>: <input type="text" id="IMO_NUMBER_ID" name="IMO_NUMBER" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $IMO_NUMBER; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td width="100" class="title">MMSI</td>
			<td>: <input type="text" id="MMSI_CODE_ID" name="MMSI_CODE" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $MMSI_CODE; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">STATUS</td>
			<td>: <input type="text" id="STATUS_ID" name="STATUS" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $STATUS; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
		    <td class="title">SHIP NAME</td>
			<td>: <input type="text" id="NAME_ID" name="NAME" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $NAME; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
		    <td class="title">VESSEL TYPE</td>
			<td>: <input type="text" id="VESSEL_TYPE_ID" name="VESSEL_TYPE" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $VESSEL_TYPE; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
		    <td class="title">GROSS TONNAGE</td>
			<td>: <input type="text" id="GROSS_TONNAGE_ID" name="GROSS_TONNAGE" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $GROSS_TONNAGE; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
		    <td class="title">SUMMER DWT</td>
			<td>: <input type="text" id="SUMMER_DWT_ID" name="SUMMER_DWT" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $SUMMER_DWT; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
		    <td class="title">BUILD</td>
			<td>: <input type="text" id="BUILD_ID" name="BUILD" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $BUILD; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
		    <td class="title">BUILDER</td>
			<td>: <input type="text" id="BUILDER_ID" name="BUILDER" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $BUILDER; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
		    <td class="title">COUNTRY</td>
			<td>: <img src="../<?php echo $FLAG_IMAGE; ?>" alt="<?php echo $FLAG; ?>" title="<?php echo $FLAG; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
		    <td class="title">HOME PORT</td>
			<td>: <input type="text" id="HOME_PORT_ID" name="HOME_PORT" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $HOME_PORT; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
		    <td class="title">MANAGER</td>
			<td>: <input type="text" id="MANAGER_ID" name="MANAGER" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $MANAGER; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
		    <td class="title">OWNER</td>
			<td>: <input type="text" id="OWNER_ID" name="OWNER" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $OWNER; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
		    <td class="title">CLASS SOCIETY</td>
			<td>: <input type="text" id="CLASS_SOCIETY_ID" name="CLASS_SOCIETY" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $CLASS_SOCIETY; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
		    <td class="title">DUAL CLASS SOCIETY</td>
			<td>: <input type="text" id="DUAL_CLASS_SOCIETY_ID" name="DUAL_CLASS_SOCIETY" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $DUAL_CLASS_SOCIETY; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
		    <td class="title">INSURER</td>
			<td>: <input type="text" id="INSURER_ID" name="INSURER" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $INSURER; ?>" /></td>
		  </tr>
		  <tr>
			<td height="20"></td>
		  </tr>
		  <tr>
			<td colspan="2" class="main_title">GENERAL</td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">ALTERATION</td>
			<td>: <input type="text" id="ALTERATION_ID" name="ALTERATION" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $ALTERATION; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">DEAD REASON</td>
			<td>: <input type="text" id="DEAD_REASON_ID" name="DEAD_REASON" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $DEAD_REASON; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">GEAR</td>
			<td>: <input type="text" id="GEAR_ID" name="GEAR" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $GEAR; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">HOME PORT</td>
			<td>: <input type="text" id="HOME_PORT_ID" name="HOME_PORT" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $HOME_PORT; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">NAVIGATION AREA</td>
			<td>: <input type="text" id="NAVIGATION_AREA_ID" name="NAVIGATION_AREA" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $NAVIGATION_AREA; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">REGISTRATION NUMBER</td>
			<td>: <input type="text" id="REGISTRATION_NUMBER_ID" name="REGISTRATION_NUMBER" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $REGISTRATION_NUMBER; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">SERVICE LIMIT</td>
			<td>: <input type="text" id="SERVICE_LIMIT_ID" name="SERVICE_LIMIT" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $SERVICE_LIMIT; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">SPEED (AVERAGE)</td>
			<td>: <input type="text" id="SPEED_AVERAGE_ID" name="SPEED_AVERAGE" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $SPEED_AVERAGE; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">SPEED (ECON)</td>
			<td>: <input type="text" id="SPEED_ECON_ID" name="SPEED_ECON" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $SPEED_ECON; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">SPEED (MAX)</td>
			<td>: <input type="text" id="SPEED_MAX_ID" name="SPEED_MAX" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $SPEED_MAX; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">SPEED (SERVICE)</td>
			<td>: <input type="text" id="SPEED_SERVICE_ID" name="SPEED_SERVICE" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $SPEED_SERVICE; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">SPEED (TRIAL)</td>
			<td>: <input type="text" id="SPEED_TRIAL_ID" name="SPEED_TRIAL" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $SPEED_TRIAL; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">TRADING AREAS</td>
			<td>: <input type="text" id="TRADING_AREAS_ID" name="TRADING_AREAS" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $TRADING_AREAS; ?>" /></td>
		  </tr>
		  <tr>
			<td height="20"></td>
		  </tr>
		  <tr>
			<td colspan="2" class="main_title">HISTORICAL</td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">ALTERATION DATE</td>
			<td>: <input type="text" id="ALTERATION_DATE_ID" name="ALTERATION_DATE" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $ALTERATION_DATE; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">BROKEN UP</td>
			<td>: <input type="text" id="BROKEN_UP_ID" name="BROKEN_UP" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $BROKEN_UP; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">BUILD END</td>
			<td>: <input type="text" id="BUILD_END_ID" name="BUILD_END" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $BUILD_END; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">BUILD START</td>
			<td>: <input type="text" id="BUILD_START_ID" name="BUILD_START" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $BUILD_START; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">DATE OF ORDER</td>
			<td>: <input type="text" id="DATE_OF_ORDER_ID" name="DATE_OF_ORDER" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $DATE_OF_ORDER; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">DELIVERY DATE</td>
			<td>: <input type="text" id="DELIVERY_DATE_ID" name="DELIVERY_DATE" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $DELIVERY_DATE; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">FIRST MOVEMENT</td>
			<td>: <input type="text" id="FIRST_MOVEMENT_ID" name="FIRST_MOVEMENT" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $FIRST_MOVEMENT; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">KEEL LAID</td>
			<td>: <input type="text" id="KEEL_LAID_ID" name="KEEL_LAID" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $KEEL_LAID; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">LAUNCH DATE</td>
			<td>: <input type="text" id="LAUNCH_DATE_ID" name="LAUNCH_DATE" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $LAUNCH_DATE; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">LOSS DATE</td>
			<td>: <input type="text" id="LOSS_DATE_ID" name="LOSS_DATE" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $LOSS_DATE; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">PLACE OF BUILD</td>
			<td>: <input type="text" id="PLACE_OF_BUILD_ID" name="PLACE_OF_BUILD" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $PLACE_OF_BUILD; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">STEEL CUTTING</td>
			<td>: <input type="text" id="STEEL_CUTTING_ID" name="STEEL_CUTTING" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $STEEL_CUTTING; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">YARD NUMBER</td>
			<td>: <input type="text" id="YARD_NUMBER_ID" name="YARD_NUMBER" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $YARD_NUMBER; ?>" /></td>
		  </tr>
		  <tr>
			<td height="20"></td>
		  </tr>
		  <tr>
			<td colspan="2" class="main_title">ANCHOR</td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">ANCHOR CHAIN DIAMETER</td>
			<td>: <input type="text" id="ANCHOR_CHAIN_DIAMETER_ID" name="ANCHOR_CHAIN_DIAMETER" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $ANCHOR_CHAIN_DIAMETER; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">ANCHOR HOLDING ABILITY</td>
			<td>: <input type="text" id="ANCHOR_HOLDING_ABILITY_ID" name="ANCHOR_HOLDING_ABILITY" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $ANCHOR_HOLDING_ABILITY; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">ANCHOR STRENGTH LEVEL</td>
			<td>: <input type="text" id="ANCHOR_STRENGTH_LEVEL_ID" name="ANCHOR_STRENGTH_LEVEL" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $ANCHOR_STRENGTH_LEVEL; ?>" /></td>
		  </tr>
		  <tr>
			<td height="20"></td>
		  </tr>
		  <tr>
			<td colspan="2" class="main_title">CAPACITIES</td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">ASPHALT</td>
			<td>: <input type="text" id="ASPHALT_ID" name="ASPHALT" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $ASPHALT; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">BALE</td>
			<td>: <input type="text" id="BALE_ID" name="BALE" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $BALE; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">BALLAST</td>
			<td>: <input type="text" id="BALLAST_ID" name="BALLAST" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $BALLAST; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">BALLAST (CLEAN)</td>
			<td>: <input type="text" id="BALLAST_CLEAN_ID" name="BALLAST_CLEAN" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $BALLAST_CLEAN; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">BALLAST (SEGREGATED)</td>
			<td>: <input type="text" id="BALLAST_SEGREGATED_ID" name="BALLAST_SEGREGATED" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $BALLAST_SEGREGATED; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">BERTHS</td>
			<td>: <input type="text" id="BERTHS_ID" name="BERTHS" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $BERTHS; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">BUNKER</td>
			<td>: <input type="text" id="BUNKER_ID" name="BUNKER" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $BUNKER; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">CABINS</td>
			<td>: <input type="text" id="CABINS_ID" name="CABINS" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $CABINS; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">CARGO CAPACITY</td>
			<td>: <input type="text" id="CARGO_CAPACITY_ID" name="CARGO_CAPACITY" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $CARGO_CAPACITY; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">CARS</td>
			<td>: <input type="text" id="CARS_ID" name="CARS" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $CARS; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">CRUDE CAPACITY</td>
			<td>: <input type="text" id="CRUDE_CAPACITY_ID" name="CRUDE_CAPACITY" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $CRUDE_CAPACITY; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">DIESEL OIL</td>
			<td>: <input type="text" id="DIESEL_OIL_ID" name="DIESEL_OIL" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $DIESEL_OIL; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">FISH HOLD VOLUME</td>
			<td>: <input type="text" id="FISH_HOLD_VOLUME_ID" name="FISH_HOLD_VOLUME" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $FISH_HOLD_VOLUME; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">FRESHWATER</td>
			<td>: <input type="text" id="FRESHWATER_ID" name="FRESHWATER" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $FRESHWATER; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">FUEL</td>
			<td>: <input type="text" id="FUEL_ID" name="FUEL" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $FUEL; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">FUEL OIL</td>
			<td>: <input type="text" id="FUEL_OIL_ID" name="FUEL_OIL" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $FUEL_OIL; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">GRAIN</td>
			<td>: <input type="text" id="GRAIN_ID" name="GRAIN" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $GRAIN; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">GRAIN LIQUID</td>
			<td>: <input type="text" id="GRAIN_LIQUID_ID" name="GRAIN_LIQUID" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $GRAIN_LIQUID; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">HOPPERS</td>
			<td>: <input type="text" id="HOPPERS_ID" name="HOPPERS" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $HOPPERS; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">HYDRAULIC OIL CAPACITY</td>
			<td>: <input type="text" id="HYDRAULIC_OIL_CAPACITY_ID" name="HYDRAULIC_OIL_CAPACITY" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $HYDRAULIC_OIL_CAPACITY; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">INSULATED</td>
			<td>: <input type="text" id="INSULATED_ID" name="INSULATED" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $INSULATED; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">LIQUID GAS</td>
			<td>: <input type="text" id="LIQUID_GAS_ID" name="LIQUID_GAS" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $LIQUID_GAS; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">LIQUID/OIL</td>
			<td>: <input type="text" id="LIQUID_OIL_ID" name="LIQUID_OIL" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $LIQUID_OIL; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">LORRIES</td>
			<td>: <input type="text" id="LORRIES_ID" name="LORRIES" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $LORRIES; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">LUBE OIL</td>
			<td>: <input type="text" id="LUBE_OIL_ID" name="LUBE_OIL" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $LUBE_OIL; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">ORE</td>
			<td>: <input type="text" id="ORE_ID" name="ORE" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $ORE; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">PASSENGERS</td>
			<td>: <input type="text" id="PASSENGERS_ID" name="PASSENGERS" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $PASSENGERS; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">RAIL WAGONS</td>
			<td>: <input type="text" id="RAIL_WAGONS_ID" name="RAIL_WAGONS" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $RAIL_WAGONS; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">SLOPS</td>
			<td>: <input type="text" id="SLOPS_ID" name="SLOPS" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $SLOPS; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">TEU</td>
			<td>: <input type="text" id="TEU_ID" name="TEU" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $TEU; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">TRAILERS</td>
			<td>: <input type="text" id="TRAILERS_ID" name="TRAILERS" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $TRAILERS; ?>" /></td>
		  </tr>
		  <tr>
			<td height="20"></td>
		  </tr>
		  <tr>
			<td colspan="2" class="main_title">CARGO</td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">CARGO HANDLING</td>
			<td>: <input type="text" id="CARGO_HANDLING_ID" name="CARGO_HANDLING" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $CARGO_HANDLING; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">CARGO PUMPS</td>
			<td>: <input type="text" id="CARGO_PUMPS_ID" name="CARGO_PUMPS" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $CARGO_PUMPS; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">CARGO SPACE</td>
			<td>: <input type="text" id="CARGO_SPACE_ID" name="CARGO_SPACE" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $CARGO_SPACE; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">CARGO TANKS</td>
			<td>: <input type="text" id="CARGO_TANKS_ID" name="CARGO_TANKS" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $CARGO_TANKS; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">CRANES</td>
			<td>: <input type="text" id="CRANES_ID" name="CRANES" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $CRANES; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">DERRICKS</td>
			<td>: <input type="text" id="DERRICKS_ID" name="DERRICKS" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $DERRICKS; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">HATCHWAYS</td>
			<td>: <input type="text" id="HATCHWAYS_ID" name="HATCHWAYS" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $HATCHWAYS; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">HOLDS</td>
			<td>: <input type="text" id="HOLDS_ID" name="HOLDS" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $HOLDS; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">LARGEST HATCH</td>
			<td>: <input type="text" id="LARGEST_HATCH_ID" name="LARGEST_HATCH" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $LARGEST_HATCH; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">LIFTING EQUIPMENT</td>
			<td>: <input type="text" id="LIFTING_EQUIPMENT_ID" name="LIFTING_EQUIPMENT" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $LIFTING_EQUIPMENT; ?>" /></td>
		  </tr>
		  <tr>
			<td height="20"></td>
		  </tr>
		  <tr>
			<td colspan="2" class="main_title">CLASSIFICATIONS</td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">CLASS ASSIGNMENT</td>
			<td>: <input type="text" id="CLASS_ASSIGNMENT_ID" name="CLASS_ASSIGNMENT" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $CLASS_ASSIGNMENT; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">CLASS NOTATION</td>
			<td>: <input type="text" id="CLASS_NOTATION_ID" name="CLASS_NOTATION" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $CLASS_NOTATION; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">LAST DRYDOCK SURVEY</td>
			<td>: <input type="text" id="LAST_DRYDOCK_SURVEY_ID" name="LAST_DRYDOCK_SURVEY" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $LAST_DRYDOCK_SURVEY; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">LAST HULL SURVEY</td>
			<td>: <input type="text" id="LAST_HULL_SURVEY_ID" name="LAST_HULL_SURVEY" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $LAST_HULL_SURVEY; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">LAST SPECIAL SURVEY</td>
			<td>: <input type="text" id="LAST_SPECIAL_SURVEY_ID" name="LAST_SPECIAL_SURVEY" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $LAST_SPECIAL_SURVEY; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">NEXT DRYDOCK SURVEY</td>
			<td>: <input type="text" id="NEXT_DRYDOCK_SURVEY_ID" name="NEXT_DRYDOCK_SURVEY" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $NEXT_DRYDOCK_SURVEY; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">NEXT HULL SURVEY</td>
			<td>: <input type="text" id="NEXT_HULL_SURVEY_ID" name="NEXT_HULL_SURVEY" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $NEXT_HULL_SURVEY; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">NEXT SPECIAL SURVEY</td>
			<td>: <input type="text" id="NEXT_SPECIAL_SURVEY_ID" name="NEXT_SPECIAL_SURVEY" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $NEXT_SPECIAL_SURVEY; ?>" /></td>
		  </tr>
		  <tr>
			<td height="20"></td>
		  </tr>
		  <tr>
			<td colspan="2" class="main_title">CREW</td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">ACTUAL MANNING (OFFICERS)</td>
			<td>: <input type="text" id="ACTUAL_MANNING_OFFICERS_ID" name="ACTUAL_MANNING_OFFICERS" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $ACTUAL_MANNING_OFFICERS; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">ACTUAL MANNING (RATINGS)</td>
			<td>: <input type="text" id="ACTUAL_MANNING_RATINGS_ID" name="ACTUAL_MANNING_RATINGS" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $ACTUAL_MANNING_RATINGS; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">LANGUAGE USED (COMMON)</td>
			<td>: <input type="text" id="LANGUAGE_USED_COMMON_ID" name="LANGUAGE_USED_COMMON" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $LANGUAGE_USED_COMMON; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">LANGUAGE USED (VESSEL OPERATOR)</td>
			<td>: <input type="text" id="LANGUAGE_USED_VESSEL_OPERATOR_ID" name="LANGUAGE_USED_VESSEL_OPERATOR" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $LANGUAGE_USED_VESSEL_OPERATOR; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">MINIMUM MANNING REQUIRED (OFFICERS)</td>
			<td>: <input type="text" id="MINIMUM_MANNING_REQUIRED_OFFICERS_ID" name="MINIMUM_MANNING_REQUIRED_OFFICERS" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $MINIMUM_MANNING_REQUIRED_OFFICERS; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">MINIMUM MANNING REQUIRED (RATINGS)</td>
			<td>: <input type="text" id="MINIMUM_MANNING_REQUIRED_RATINGS_ID" name="MINIMUM_MANNING_REQUIRED_RATINGS" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $MINIMUM_MANNING_REQUIRED_RATINGS; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">TOTAL CREW</td>
			<td>: <input type="text" id="TOTAL_CREW_ID" name="TOTAL_CREW" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $TOTAL_CREW; ?>" /></td>
		  </tr>
		  <tr>
			<td height="20"></td>
		  </tr>
		  <tr>
			<td colspan="2" class="main_title">DIMENSIONS</td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">BOW TO BRIDGE</td>
			<td>: <input type="text" id="BOW_TO_BRIDGE_ID" name="BOW_TO_BRIDGE" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $BOW_TO_BRIDGE; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">BOW TO CENTER MANIFOLD</td>
			<td>: <input type="text" id="BOW_TO_CENTER_MANIFOLD_ID" name="BOW_TO_CENTER_MANIFOLD" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $BOW_TO_CENTER_MANIFOLD; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">BREADTH EXTREME</td>
			<td>: <input type="text" id="BREADTH_EXTREME_ID" name="BREADTH_EXTREME" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $BREADTH_EXTREME; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">BREADTH MOULDED</td>
			<td>: <input type="text" id="BREADTH_MOULDED_ID" name="BREADTH_MOULDED" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $BREADTH_MOULDED; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">BREADTH REGISTERED</td>
			<td>: <input type="text" id="BREADTH_REGISTERED_ID" name="BREADTH_REGISTERED" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $BREADTH_REGISTERED; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">BRIDGE</td>
			<td>: <input type="text" id="BRIDGE_ID" name="BRIDGE" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $BRIDGE; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">BULB LENGTH FROM FP</td>
			<td>: <input type="text" id="BULB_LENGTH_FROM_FP_ID" name="BULB_LENGTH_FROM_FP" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $BULB_LENGTH_FROM_FP; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">DEPTH</td>
			<td>: <input type="text" id="DEPTH_ID" name="DEPTH" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $DEPTH; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">DRAUGHT</td>
			<td>: <input type="text" id="DRAUGHT_ID" name="DRAUGHT" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $DRAUGHT; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">FORECASTLE</td>
			<td>: <input type="text" id="FORECASTLE_ID" name="FORECASTLE" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $FORECASTLE; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">HEIGHT</td>
			<td>: <input type="text" id="HEIGHT_ID" name="HEIGHT" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $HEIGHT; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">KEEL TO MASTHEAD</td>
			<td>: <input type="text" id="KEEL_TO_MASTHEAD_ID" name="KEEL_TO_MASTHEAD" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $KEEL_TO_MASTHEAD; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">LENGTH B/W PERPENDICULARS</td>
			<td>: <input type="text" id="LENGTH_B_W_PERPENDICULARS_ID" name="LENGTH_B_W_PERPENDICULARS" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $LENGTH_B_W_PERPENDICULARS; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">LENGTH ON DECK</td>
			<td>: <input type="text" id="LENGTH_ON_DECK_ID" name="LENGTH_ON_DECK" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $LENGTH_ON_DECK; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">LENGTH OVERALL</td>
			<td>: <input type="text" id="LENGTH_OVERALL_ID" name="LENGTH_OVERALL" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $LENGTH_OVERALL; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">LENGTH REGISTERED</td>
			<td>: <input type="text" id="LENGTH_REGISTERED_ID" name="LENGTH_REGISTERED" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $LENGTH_REGISTERED; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">LENGTH WATERLINE</td>
			<td>: <input type="text" id="LENGTH_WATERLINE_ID" name="LENGTH_WATERLINE" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $LENGTH_WATERLINE; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">LIGHTSHIP PARALLEL BODY</td>
			<td>: <input type="text" id="LIGHTSHIP_PARALLEL_BODY_ID" name="LIGHTSHIP_PARALLEL_BODY" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $LIGHTSHIP_PARALLEL_BODY; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">NORMAL BALLAST PARALLEL BODY</td>
			<td>: <input type="text" id="NORMAL_BALLAST_PARALLEL_BODY_ID" name="NORMAL_BALLAST_PARALLEL_BODY" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $NORMAL_BALLAST_PARALLEL_BODY; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">PARALLEL BODY LENGTH AT SUMMER DWT</td>
			<td>: <input type="text" id="PARALLEL_BODY_LENGTH_AT_SUMMER_DWT_ID" name="PARALLEL_BODY_LENGTH_AT_SUMMER_DWT" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $PARALLEL_BODY_LENGTH_AT_SUMMER_DWT; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">POOP</td>
			<td>: <input type="text" id="POOP_ID" name="POOP" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $POOP; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">QUARTERDECK</td>
			<td>: <input type="text" id="QUARTERDECK_ID" name="QUARTERDECK" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $QUARTERDECK; ?>" /></td>
		  </tr>
		  <tr>
			<td height="20"></td>
		  </tr>
		  <tr>
			<td colspan="2" class="main_title">ENGINE</td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">ENGINE #</td>
			<td>: <input type="text" id="ENGINE_NUMBER_ID" name="ENGINE_NUMBER" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $ENGINE_NUMBER; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">ENGINE BORE</td>
			<td>: <input type="text" id="ENGINE_BORE_ID" name="ENGINE_BORE" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $ENGINE_BORE; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">ENGINE BUILD YEAR</td>
			<td>: <input type="text" id="ENGINE_BUILD_YEAR_ID" name="ENGINE_BUILD_YEAR" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $ENGINE_BUILD_YEAR; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">ENGINE BUILDER</td>
			<td>: <input type="text" id="ENGINE_BUILDER_ID" name="ENGINE_BUILDER" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $ENGINE_BUILDER; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">ENGINE CYLINDERS</td>
			<td>: <input type="text" id="ENGINE_CYLINDERS_ID" name="ENGINE_CYLINDERS" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $ENGINE_CYLINDERS; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">ENGINE MODEL</td>
			<td>: <input type="text" id="ENGINE_MODEL_ID" name="ENGINE_MODEL" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $ENGINE_MODEL; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">ENGINE POWER</td>
			<td>: <input type="text" id="ENGINE_POWER_ID" name="ENGINE_POWER" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $ENGINE_POWER; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">ENGINE RATIO</td>
			<td>: <input type="text" id="ENGINE_RATIO_ID" name="ENGINE_RATIO" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $ENGINE_RATIO; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">ENGINE RPM</td>
			<td>: <input type="text" id="ENGINE_RPM_ID" name="ENGINE_RPM" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $ENGINE_RPM; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">ENGINE STROKE</td>
			<td>: <input type="text" id="ENGINE_STROKE_ID" name="ENGINE_STROKE" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $ENGINE_STROKE; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">ENGINE TYPE</td>
			<td>: <input type="text" id="ENGINE_TYPE_ID" name="ENGINE_TYPE" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $ENGINE_TYPE; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">FUEL CONSUMPTION</td>
			<td>: <input type="text" id="FUEL_CONSUMPTION_ID" name="FUEL_CONSUMPTION" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $FUEL_CONSUMPTION; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">FUEL TYPE</td>
			<td>: <input type="text" id="FUEL_TYPE_ID" name="FUEL_TYPE" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $FUEL_TYPE; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">PROPELLER</td>
			<td>: <input type="text" id="PROPELLER_ID" name="PROPELLER" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $PROPELLER; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">PROPELLING TYPE</td>
			<td>: <input type="text" id="PROPELLING_TYPE_ID" name="PROPELLING_TYPE" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $PROPELLING_TYPE; ?>" /></td>
		  </tr>
		  <tr>
			<td height="20"></td>
		  </tr>
		  <tr>
			<td colspan="2" class="main_title">LOADLINES</td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">DEADWEIGHT (LIGHTSHIP)</td>
			<td>: <input type="text" id="DEADWEIGHT_LIGHTSHIP_ID" name="DEADWEIGHT_LIGHTSHIP" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $DEADWEIGHT_LIGHTSHIP; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">DEADWEIGHT (MAXIMUM ASSIGNED)</td>
			<td>: <input type="text" id="DEADWEIGHT_MAXIMUM_ASSIGNED_ID" name="DEADWEIGHT_MAXIMUM_ASSIGNED" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $DEADWEIGHT_MAXIMUM_ASSIGNED; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">DEADWEIGHT (NORMAL BALLAST)</td>
			<td>: <input type="text" id="DEADWEIGHT_NORMAL_BALLAST_ID" name="DEADWEIGHT_NORMAL_BALLAST" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $DEADWEIGHT_NORMAL_BALLAST; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">DEADWEIGHT (SEGREGATED BALLAST)</td>
			<td>: <input type="text" id="DEADWEIGHT_SEGREGATED_BALLAST_ID" name="DEADWEIGHT_SEGREGATED_BALLAST" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $DEADWEIGHT_SEGREGATED_BALLAST; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">DEADWEIGHT (TROPICAL)</td>
			<td>: <input type="text" id="DEADWEIGHT_TROPICAL_ID" name="DEADWEIGHT_TROPICAL" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $DEADWEIGHT_TROPICAL; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">DEADWEIGHT (WINTER)</td>
			<td>: <input type="text" id="DEADWEIGHT_WINTER_ID" name="DEADWEIGHT_WINTER" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $DEADWEIGHT_WINTER; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">DISPLACEMENT (LIGHTSHIP)</td>
			<td>: <input type="text" id="DISPLACEMENT_LIGHTSHIP_ID" name="DISPLACEMENT_LIGHTSHIP" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $DISPLACEMENT_LIGHTSHIP; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">DISPLACEMENT (NORMAL BALLAST)</td>
			<td>: <input type="text" id="DISPLACEMENT_NORMAL_BALLAST_ID" name="DISPLACEMENT_NORMAL_BALLAST" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $DISPLACEMENT_NORMAL_BALLAST; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">DISPLACEMENT (SEGREGATED BALLAST)</td>
			<td>: <input type="text" id="DISPLACEMENT_SEGREGATED_BALLAST_ID" name="DISPLACEMENT_SEGREGATED_BALLAST" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $DISPLACEMENT_SEGREGATED_BALLAST; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">DISPLACEMENT (SUMMER)</td>
			<td>: <input type="text" id="DISPLACEMENT_SUMMER_ID" name="DISPLACEMENT_SUMMER" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $DISPLACEMENT_SUMMER; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">DISPLACEMENT (TROPICAL)</td>
			<td>: <input type="text" id="DISPLACEMENT_TROPICAL_ID" name="DISPLACEMENT_TROPICAL" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $DISPLACEMENT_TROPICAL; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">DISPLACEMENT (WINTER)</td>
			<td>: <input type="text" id="DISPLACEMENT_WINTER_ID" name="DISPLACEMENT_WINTER" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $DISPLACEMENT_WINTER; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">DRAFT (LIGHTSHIP)</td>
			<td>: <input type="text" id="DRAFT_LIGHTSHIP_ID" name="DRAFT_LIGHTSHIP" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $DRAFT_LIGHTSHIP; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">DRAFT (NORMAL BALLAST)</td>
			<td>: <input type="text" id="DRAFT_NORMAL_BALLAST_ID" name="DRAFT_NORMAL_BALLAST" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $DRAFT_NORMAL_BALLAST; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">DRAFT (SEGREGATED BALLAST)</td>
			<td>: <input type="text" id="DRAFT_SEGREGATED_BALLAST_ID" name="DRAFT_SEGREGATED_BALLAST" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $DRAFT_SEGREGATED_BALLAST; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">DRAFT (SUMMER)</td>
			<td>: <input type="text" id="DRAFT_SUMMER_ID" name="DRAFT_SUMMER" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $DRAFT_SUMMER; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">DRAFT (TROPICAL)</td>
			<td>: <input type="text" id="DRAFT_TROPICAL_ID" name="DRAFT_TROPICAL" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $DRAFT_TROPICAL; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">DRAFT (WINTER)</td>
			<td>: <input type="text" id="DRAFT_WINTER_ID" name="DRAFT_WINTER" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $DRAFT_WINTER; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">DRAUGHT AFT (NORMAL BALLAST)</td>
			<td>: <input type="text" id="DRAUGHT_AFT_NORMAL_BALLAST_ID" name="DRAUGHT_AFT_NORMAL_BALLAST" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $DRAUGHT_AFT_NORMAL_BALLAST; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">DRAUGHT FORE (NORMAL BALLAST)</td>
			<td>: <input type="text" id="DRAUGHT_FORE_NORMAL_BALLAST_ID" name="DRAUGHT_FORE_NORMAL_BALLAST" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $DRAUGHT_FORE_NORMAL_BALLAST; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">FREEBOARD (C1)</td>
			<td>: <input type="text" id="FREEBOARD_C1_ID" name="FREEBOARD_C1" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $FREEBOARD_C1; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">FREEBOARD (LIGHTSHIP)</td>
			<td>: <input type="text" id="FREEBOARD_LIGHTSHIP_ID" name="FREEBOARD_LIGHTSHIP" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $FREEBOARD_LIGHTSHIP; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">FREEBOARD (NORMAL BALLAST)</td>
			<td>: <input type="text" id="FREEBOARD_NORMAL_BALLAST_ID" name="FREEBOARD_NORMAL_BALLAST" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $FREEBOARD_NORMAL_BALLAST; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">FREEBOARD (SEGREGATED BALLAST)</td>
			<td>: <input type="text" id="FREEBOARD_SEGREGATED_BALLAST_ID" name="FREEBOARD_SEGREGATED_BALLAST" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $FREEBOARD_SEGREGATED_BALLAST; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">FREEBOARD (SUMMER)</td>
			<td>: <input type="text" id="FREEBOARD_SUMMER_ID" name="FREEBOARD_SUMMER" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $FREEBOARD_SUMMER; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">FREEBOARD (TROPICAL)</td>
			<td>: <input type="text" id="FREEBOARD_TROPICAL_ID" name="FREEBOARD_TROPICAL" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $FREEBOARD_TROPICAL; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">FREEBOARD (WINTER)</td>
			<td>: <input type="text" id="FREEBOARD_WINTER_ID" name="FREEBOARD_WINTER" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $FREEBOARD_WINTER; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">FWA (SUMMER DRAFT)</td>
			<td>: <input type="text" id="FWA_SUMMER_DRAFT_ID" name="FWA_SUMMER_DRAFT" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $FWA_SUMMER_DRAFT; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">TPC IMMERSION (SUMMER DRAFT)</td>
			<td>: <input type="text" id="TPC_IMMERSION_SUMMER_DRAFT_ID" name="TPC_IMMERSION_SUMMER_DRAFT" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $TPC_IMMERSION_SUMMER_DRAFT; ?>" /></td>
		  </tr>
		  <tr>
			<td height="20"></td>
		  </tr>
		  <tr>
			<td colspan="2" class="main_title">STRUCTURES</td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">BULKHEADS</td>
			<td>: <input type="text" id="BULKHEADS_ID" name="BULKHEADS" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $BULKHEADS; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">CONTINUOUS DECKS</td>
			<td>: <input type="text" id="CONTINUOUS_DECKS_ID" name="CONTINUOUS_DECKS" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $CONTINUOUS_DECKS; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">DECK ERECTIONS</td>
			<td>: <input type="text" id="DECK_ERECTIONS_ID" name="DECK_ERECTIONS" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $DECK_ERECTIONS; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">DECKS NUMBER</td>
			<td>: <input type="text" id="DECKS_NUMBER_ID" name="DECKS_NUMBER" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $DECKS_NUMBER; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">HULL MATERIAL</td>
			<td>: <input type="text" id="HULL_MATERIAL_ID" name="HULL_MATERIAL" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $HULL_MATERIAL; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">HULL TYPE</td>
			<td>: <input type="text" id="HULL_TYPE_ID" name="HULL_TYPE" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $HULL_TYPE; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">LONGITUDINAL BULKHEADS</td>
			<td>: <input type="text" id="LONGITUDINAL_BULKHEADS_ID" name="LONGITUDINAL_BULKHEADS" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $LONGITUDINAL_BULKHEADS; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">LONGITUDINAL FRAMES</td>
			<td>: <input type="text" id="LONGITUDINAL_FRAMES_ID" name="LONGITUDINAL_FRAMES" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $LONGITUDINAL_FRAMES; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">RO-RO LANES</td>
			<td>: <input type="text" id="RO_RO_LANES_ID" name="RO_RO_LANES" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $RO_RO_LANES; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">RO-RO RAMPS</td>
			<td>: <input type="text" id="RO_RO_RAMPS_ID" name="RO_RO_RAMPS" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $RO_RO_RAMPS; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">SUPERSTRUCTURES</td>
			<td>: <input type="text" id="SUPERSTRUCTURES_ID" name="SUPERSTRUCTURES" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $SUPERSTRUCTURES; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">TRANSVERSE BULKHEADS</td>
			<td>: <input type="text" id="TRANSVERSE_BULKHEADS_ID" name="TRANSVERSE_BULKHEADS" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $TRANSVERSE_BULKHEADS; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">WATERTIGHT BULKHEADS</td>
			<td>: <input type="text" id="WATERTIGHT_BULKHEADS_ID" name="WATERTIGHT_BULKHEADS" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $WATERTIGHT_BULKHEADS; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">WATERTIGHT COMPARTMENTS</td>
			<td>: <input type="text" id="WATERTIGHT_COMPARTMENTS_ID" name="WATERTIGHT_COMPARTMENTS" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $WATERTIGHT_COMPARTMENTS; ?>" /></td>
		  </tr>
		  <tr>
			<td height="20"></td>
		  </tr>
		  <tr>
			<td colspan="2" class="main_title">TONNAGES</td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">NET TONNAGE</td>
			<td>: <input type="text" id="NET_TONNAGE_ID" name="NET_TONNAGE" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $NET_TONNAGE; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">PANAMA GROSS TONNAGE</td>
			<td>: <input type="text" id="PANAMA_GROSS_TONNAGE_ID" name="PANAMA_GROSS_TONNAGE" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $PANAMA_GROSS_TONNAGE; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">PANAMA NET TONNAGE</td>
			<td>: <input type="text" id="PANAMA_NET_TONNAGE_ID" name="PANAMA_NET_TONNAGE" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $PANAMA_NET_TONNAGE; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">PANAMA TONNAGE</td>
			<td>: <input type="text" id="PANAMA_TONNAGE_ID" name="PANAMA_TONNAGE" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $PANAMA_TONNAGE; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">SUEZ GROSS TONNAGE</td>
			<td>: <input type="text" id="SUEZ_GROSS_TONNAGE_ID" name="SUEZ_GROSS_TONNAGE" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $SUEZ_GROSS_TONNAGE; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">SUEZ NET TONNAGE</td>
			<td>: <input type="text" id="SUEZ_NET_TONNAGE_ID" name="SUEZ_NET_TONNAGE" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $SUEZ_NET_TONNAGE; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">SUEZ TONNAGE</td>
			<td>: <input type="text" id="SUEZ_TONNAGE_ID" name="SUEZ_TONNAGE" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $SUEZ_TONNAGE; ?>" /></td>
		  </tr>
		  <tr>
			<td height="20"></td>
		  </tr>
		  <tr>
			<td colspan="2" class="main_title">COMMUNICATION</td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">CALL SIGN</td>
			<td>: <input type="text" id="CALL_SIGN_ID" name="CALL_SIGN" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $CALL_SIGN; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">SATCOM ANSWER BACK</td>
			<td>: <input type="text" id="SATCOM_ANSWER_BACK_ID" name="SATCOM_ANSWER_BACK" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $SATCOM_ANSWER_BACK; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">SATCOM ID</td>
			<td>: <input type="text" id="SATCOM_ID_ID" name="SATCOM_ID" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $SATCOM_ID; ?>" /></td>
		  </tr>
		  <tr>
			<td height="20"></td>
		  </tr>
		  <tr>
			<td colspan="2" class="main_title">PORT STATE CONTROLS</td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">DATE</td>
			<td>: <input type="text" id="DATE_ID" name="DATE" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $DATE; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">TYPE OF INSPECTION</td>
			<td>: <input type="text" id="TYPE_ID" name="TYPE" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $TYPE; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">INSPECTING ORGANIZATION</td>
			<td>: <input type="text" id="ORGANIZATION_ID" name="ORGANIZATION" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $ORGANIZATION; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">AUTHORITY</td>
			<td>: <input type="text" id="AUTHORITY_ID" name="AUTHORITY" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $AUTHORITY; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">PLACE</td>
			<td>: <input type="text" id="PLACE_ID" name="PLACE" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $PLACE; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">DETENTION DESCRIPTION</td>
			<td>: <input type="text" id="DETENTION_ID" name="DETENTION" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $DETENTION; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">DEFICIENCY DESCRIPTION</td>
			<td>: <input type="text" id="DEFICIENCY_ID" name="DEFICIENCY" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $DEFICIENCY; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">START OF PSC DESCRIPTION</td>
			<td>: <input type="text" id="PSC_ID" name="PSC" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $PSC; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">START OF DETENTIONS LIST</td>
			<td>: <input type="text" id="DETENTIONS_ID" name="DETENTIONS" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $DETENTIONS; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">START OF DEFICIENCIES LIST</td>
			<td>: <input type="text" id="DEFICIENCIES_ID" name="DEFICIENCIES" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $DEFICIENCIES; ?>" /></td>
		  </tr>
		  <tr>
			<td height="20"></td>
		  </tr>
		  <tr>
			<td colspan="2" class="main_title">CERTIFICATES</td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">TYPE OF CERTIFICATE</td>
			<td>: <input type="text" id="CERTIFICATE_TYPE_ID" name="CERTIFICATE_TYPE" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $CERTIFICATE_TYPE; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">ISSUE DATE</td>
			<td>: <input type="text" id="ISSUED_ID" name="ISSUED" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $ISSUED; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">ISSUER AUTHORITY</td>
			<td>: <input type="text" id="FROM_ID" name="FROM" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $FROM; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">EXPIRE DATE</td>
			<td>: <input type="text" id="EXPIRES_ID" name="EXPIRES" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $EXPIRES; ?>" /></td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td class="title">START OF CERTIFICATE</td>
			<td>: <input type="text" id="CERTIFICATE_ID" name="CERTIFICATE" style="width:250px; border:1px solid #CCCCCC; padding:3px;" value="<?php echo $CERTIFICATE; ?>" /></td>
		  </tr>
		  <tr>
			<td height="20"></td>
		  </tr>
		  <tr>
			<td><input type="hidden" name="submitok" value="1"><input type="button" id="btn_save_id" name="btn_save" value="save" class="btn_1" onClick="saveForm();" /></td>
		  </tr>
		  <?php } ?>
		</table>
		</form>
		<?php
	}
}
?>