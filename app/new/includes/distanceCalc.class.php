<?php
@session_start();
class distanceCalc{
	var $soapClient;
	var $loginTicket;
	var $link;
	function distanceCalc($loginTicket=""){
		$this->loginTicket = $loginTicket;
		if(!trim($this->loginTicket)){
			$this->soapLogin();
		}
		$this->link = dbConnect();
		
	}
	
	function soapLogin(){
		//login to soap server
		$this->soapClient = new SoapClient("http://www.veslink.com/distances/distancerouteservice.asmx?WSDL"); 
		$soapClient = $this->soapClient;
		$param['username'] = "rdevlin@maritimeinfosys.com";
		$param['password'] = "d1stanc3ap1";
		try	{
			if(trim($_SESSION['loginTicket'])){
				$this->loginTicket = $_SESSION['loginTicket'];
				return 1;
			}		
			$info = $soapClient->__call("Login", array($param));
			$this->loginTicket = $info->LoginResult;
			$_SESSION['loginTicket'] = $this->loginTicket;
		} 
		catch (SoapFault $fault){
			if($_GET['json']){
				ob_end_clean();
				echo json_encode($fault);
			}
			else{
				echo "";
				//echo "<pre>";
				//print_r($fault);
				//echo "</pre>";
			}
		}	
	}
	
	function genericSoap($params){
		$soapClient = $this->soapClient;
		$loginTicket = $this->loginTicket;
		$param = $params;
		$sh_param['loginTicket'] = $loginTicket;
		$headers = new SoapHeader('http://veson.com/webservices/', 'DistanceHeader', $sh_param); 
		//setup soap headers
		$soapClient->__setSoapHeaders(array($headers)); 
		try{
			$function = $param['function'];
			unset($param['function']);
			$info = $soapClient->__call($function, array($param));
			if($_GET['json']){
				ob_end_clean();
				echo json_encode($info);
			}
			else{
				return $info;
			}
		} 
		catch (SoapFault $fault){
			if($_GET['json']){
				ob_end_clean();
				echo json_encode($fault);
			}
			else{
				//echo "<pre>";
				//print_r($fault);
				//echo "</pre>";
			}
		}
	}
	
	function getPortByPoint($lat, $long){
		$sql = "select * from _veson_ports where `latitude`='".mysql_escape_string($lat)."'
		and `longitude`='".mysql_escape_string($long)."'
		";
		$r = dbQuery($sql, $this->link);
		return $r[0]['name'];
	}
	
	function getPortById($id){
		$paramsx['function']="GetPortById";
		$paramsx['id']=trim(strtoupper($id));
		$infox = $this->genericSoap($paramsx);
		return $infox;
	}
	
	function getRoutesPointToPort($lat, $long, $to, $prefs=""){
		//check cache
		if(($lat+0)==0&&($long+0)==0){
			return false;
		}
		
		$param_md5_id = md5($lat.$long.$to.base64_encode(serialize($prefs)));
		
		$sql = "select `routes` from `_veson_distance_cache2` where 
		`param_md5_id` = '".mysql_escape_string($param_md5_id)."' limit 1";
		
		//echo "<hr>".$this->link."<br>$sql<hr>";
		//echo "<pre>".print_r($prefs, 1)."<pre>";
		$d = dbQuery($sql, $this->link);
		$d = $d[0];
		if($d['routes']!=""){
			//echo "<br>from db yes...<br>";
			return $d['routes'];
		}
		
		if(!is_numeric($to)){
			$params['function'] = "GetPortByName";
				$params['name'] = strtoupper($to);
			$portToName = $params['name'];
			$info = $this->genericSoap($params);
			$portTo = $info->GetPortByNameResult->id;
		}
		else{
			$portTo = $to;
		}
		
		$params['function'] = "PointToPort";
		$params['latFrom'] = $lat;
		$params['lonFrom'] = $long;
		$params['portTo'] = $portTo;

		if($prefs){
			$params['prefs'] = $prefs;
		}		
		
		//print_r($params);
		
		$info = $this->genericSoap($params);

		//cache it
		$sql = "insert into `_veson_distance_cache2` (
			`param_md5_id`,
			`distance`,
			`routes`,
			`dateadded`
		)
		values(
			'".mysql_escape_string($param_md5_id)."',
			'".mysql_escape_string($info->PointToPortResult->distance)."',
			'".mysql_escape_string(base64_encode(serialize($info->PointToPortResult->routePoints->Location)))."',
			NOW()
		)
		";
		dbQuery($sql, $this->link);

		//print_r($info);
		return base64_encode(serialize($info->PointToPortResult->routePoints->Location));

	}
	function getDistancePointToPort($lat, $long, $to, $prefs=""){
		
		//get cache
		$param_md5_id = md5($lat.$long.$to.base64_encode(serialize($prefs)));
		
		$sql = "select `distance` from `_veson_distance_cache2` where 
		`param_md5_id` = '".mysql_escape_string($param_md5_id)."' limit 1";
		
		$d = dbQuery($sql, $this->link);
		$d = $d[0];
		if($d['distance']!=""){
			//echo "<br>from db yes...<br>";
			return $d['distance'];
		}
		
		
		if(!is_numeric($to)){
			$params['function'] = "GetPortByName";
				$params['name'] = strtoupper($to);
			$portToName = $params['name'];
			$info = $this->genericSoap($params);
			$portTo = $info->GetPortByNameResult->id;
		}
		else{
			$portTo = $to;
		}
		
		$params['function'] = "PointToPort";
		$params['latFrom'] = $lat;
		$params['lonFrom'] = $long;
		$params['portTo'] = $portTo;
		
		if($prefs){
			$params['prefs'] = $prefs;
		}		
		
		//print_r($params);
		
		$info = $this->genericSoap($params);
		//cache it
		$sql = "insert into `_veson_distance_cache2` (
			`param_md5_id`,
			`distance`,
			`routes`,
			`dateadded`
		)
		values(
			'".mysql_escape_string($param_md5_id)."',
			'".mysql_escape_string($info->PointToPortResult->distance)."',
			'".mysql_escape_string(base64_encode(serialize($info->PointToPortResult->routePoints->Location)))."',
			NOW()
		)
		";
		dbQuery($sql, $this->link);

		//print_r($info);
		return $info->PointToPortResult->distance;
	}
	
	function getPortByName($name){
		$paramsx['function']="GetPortByName";
		$paramsx['name']=strtoupper($name);
		$infox = $this->genericSoap($paramsx);
		$portid = $infox->GetPortByNameResult->id;	
		return $portid;
	}
	
	function getRoutesPortToPort($from, $to, $prefs=""){
		
		$param_md5_id = md5($from.$to.base64_encode(serialize($prefs)));
		//check cache
		$sql = "select `routes` from `_veson_distance_cache2` where 
		`param_md5_id` = '".mysql_escape_string($param_md5_id)."' limit 1";
		
		//echo "<hr>".$this->link."<br>$sql<hr>";
		//echo "<pre>".print_r($prefs, 1)."<pre>";
		$d = dbQuery($sql, $this->link);
		$d = $d[0];
		if($d['routes']!=""){
			//echo "<br>from db yes...<br>";
			return $d['routes'];
		}
		
		
		
		
		$d = dbQuery($sql, $this->link);
		$d = $d[0];
		if($d['routes']!=""){
			return $d['routes'];
		}
			
		if(!is_numeric($from)){
			$params['function'] = "GetPortByName";
			$params['name'] = strtoupper($from);
			$portFromName = $params['name'];
			$info = $this->genericSoap($params);
			$portFrom = $info->GetPortByNameResult->id;
		}
		else{
			$portFrom = $from;
		}
		if(!is_numeric($to)){
			$params['function'] = "GetPortByName";
				$params['name'] = strtoupper($to);
			$portToName = $params['name'];
			$info = $this->genericSoap($params);
			$portTo = $info->GetPortByNameResult->id;
		}
		else{
			$portTo = $to;
		}
		$params['function'] = "FindDistance";
		$params['portFrom'] = $portFrom;
		$params['portTo'] = $portTo;
	
		if(strtolower($params['function'])=='finddistance'&&$prefs){
			$params['prefs'] = $prefs;
		}
		
		$info = $this->genericSoap($params);
		
		//cache it
		$sql = "insert into `_veson_distance_cache2` (
			`param_md5_id`,
			`distance`,
			`routes`,
			`dateadded`
		)
		values(
			'".mysql_escape_string($param_md5_id)."',
			'".mysql_escape_string($info->FindDistanceResult->distance)."',
			'".mysql_escape_string(base64_encode(serialize($info->FindDistanceResult->routePoints->Location)))."',
			NOW()
		)
		";
		dbQuery($sql, $this->link);	
		return base64_encode(serialize($info->FindDistanceResult->routePoints->Location));
	}
	
	
	function getDistancePortToPort($from, $to, $prefs="", $test=0){
		
		//check cache
		//get cache
		$param_md5_id = md5($from.$to.base64_encode(serialize($prefs)));
		$sql = "select `distance` from `_veson_distance_cache2` where 
		`param_md5_id` = '".mysql_escape_string($param_md5_id)."' limit 1";
		
		
		//echo "<hr>".$this->link."<br>$sql<hr>";
		//echo "<pre>".$sql."<pre>";
		if(!$test)
			$d = dbQuery($sql, $this->link);
		$d = $d[0];
		if($d['distance']!=""){
			//echo "<br>from db yes...<br>";
			return $d['distance'];
		}
			
		if(!is_numeric($from)){
			$params['function'] = "GetPortByName";
			$params['name'] = strtoupper($from);
			$portFromName = $params['name'];
			$info = $this->genericSoap($params);
			$portFrom = $info->GetPortByNameResult->id;
		}
		else{
			$portFrom = $from;
		}
		if(!is_numeric($to)){
			$params['function'] = "GetPortByName";
				$params['name'] = strtoupper($to);
			$portToName = $params['name'];
			$info = $this->genericSoap($params);
			$portTo = $info->GetPortByNameResult->id;
		}
		else{
			$portTo = $to;
		}
		$params['function'] = "FindDistance";
		$params['portFrom'] = $portFrom;
		$params['portTo'] = $portTo;
		
		
		if(strtolower($params['function'])=='finddistance'&&$prefs){
			$params['prefs'] = $prefs;
		}
		$info = $this->genericSoap($params);
		//cache it
		$sql = "insert into `_veson_distance_cache2` (
			`param_md5_id`,
			`distance`,
			`routes`,
			`dateadded`
		)
		values(
			'".mysql_escape_string($param_md5_id)."',
			'".mysql_escape_string($info->FindDistanceResult->distance)."',
			'".mysql_escape_string(base64_encode(serialize($info->FindDistanceResult->routePoints->Location)))."',
			NOW()
		)
		";
		dbQuery($sql, $this->link);	
		return $info->FindDistanceResult->distance;
	}
	
	
	function getDefaultPrefs(){
		$params['function'] = "GetDefaultPrefs";
		//$params['name'] = strtoupper($from);
		//$portFromName = $params['name'];
				
		$info = $this->genericSoap($params);
		return $info;
	}	
	
	function getViaRegions(){
		$r = array();
		$sql = "select * from `_veson_via_routes` where 1";
		$r = dbQuery($sql, $this->link);
		if($r[0]){
			return $r;
		}
		
		$params['function'] = "GetViaRegionIds";
		$info = $this->genericSoap($params);
		$arr = $info->GetViaRegionIdsResult->int;
		$t = count($arr);
		for($i=0;$i<$t; $i++){
			$info = $this->getPortById($arr[$i]);
			$temparr = array();
			$temparr['portid'] = $info->GetPortByIdResult->id;
			$temparr['name'] = $info->GetPortByIdResult->name;
			$temparr['latitude'] = $info->GetPortByIdResult->latitude;
			$temparr['longitude'] = $info->GetPortByIdResult->longitude;
			$temparr['waterwayCode'] = $info->GetPortByIdResult->waterwayCode;
			$temparr['waterwayMarker'] = $info->GetPortByIdResult->waterwayMarker;
			$r[] = $temparr;
			$sql = "insert into `_veson_via_routes` (
				`portid`,
				`name`,
				`latitude`,
				`longitude`,
				`waterwayCode`,
				`waterwayMarker`
			)
			values (
				'".$info->GetPortByIdResult->id."',
				'".$info->GetPortByIdResult->name."',
				'".$info->GetPortByIdResult->latitude."',
				'".$info->GetPortByIdResult->longitude."',
				'".$info->GetPortByIdResult->waterwayCode."',
				'".$info->GetPortByIdResult->waterwayMarker."'
			)
			";
			dbQuery($sql, $this->link);
		}
		
		return $r;
	}
	
	function test(){
		$from = "Manila";
		$to = "Amsterdam";

		$params['function'] = "GetPortByName";
		$params['name'] = strtoupper($from);
		$portToName = $params['name'];
		$info = $this->genericSoap($params);
		$portFrom = $info->GetPortByNameResult->id;

		$params['function'] = "GetPortByName";
		$params['name'] = strtoupper($to);
		$portToName = $params['name'];
		$info = $this->genericSoap($params);
		$portTo = $info->GetPortByNameResult->id;


		$params['function'] = "FindDistance";
		$params['portFrom'] = $portFrom;
		$params['portTo'] = $portTo;
	
		//if(strtolower($params['function'])=='finddistance'&&$via){
			
		//	$paramsx['function']="GetPortByName";
		//	$paramsx['name']=strtoupper($via);
		//	$infox = $this->genericSoap($paramsx);
		//	$viaid = $infox->GetPortByNameResult->id;
			//echo $viaid;
			
		//	$params['prefs']['viaList']['int'] = $viaid;
			//$params['prefs']['viaPrefs']['int'] = $_GET['via'];
		//	$params['prefs']['avoidInshore'] = true;
		//	$params['prefs']['avoidRivers'] = true;
		//	$params['prefs']['deepWaterFactor'] = 1;
		//	$params['prefs']['minDepth'] = 0;
		//	$params['prefs']['minHeight'] = 0;
			
		//}
		$params['prefs']['viaList'] = array();
		$params['prefs']['viaList'][] = 4040;
		$params['prefs']['avoidInshore'] = true;
		$params['prefs']['avoidRivers'] = true;
		$params['prefs']['deepWaterFactor'] = 1;
		$params['prefs']['minDepth'] = 0;
		$params['prefs']['minHeight'] = 0;		
		$params['prefs']['speed'] = 14;
		$info = $this->genericSoap($params);	
		
		//return $this->getDefaultPrefs();
		
		return $info;
	}
}
?>