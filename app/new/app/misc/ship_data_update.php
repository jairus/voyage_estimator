<?php
@session_start();
include_once(dirname(__FILE__)."/../includes/bootstrap.php");
date_default_timezone_set('UTC');

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
		<table width="1000" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td colspan="2" class="main_title">MAIN</td>
		  </tr>
		  <tr>
			<td height="5"></td>
		  </tr>
		  <tr>
			<td width="165" class="title">IMO NUMBER</td>
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
			<td height="20"></td>
		  </tr>
		  <tr>
			<td><input type="button" id="btn_save_id" name="btn_save" value="save" class="btn_1" /></td>
		  </tr>
		</table>
		<?php
	}
}
?>