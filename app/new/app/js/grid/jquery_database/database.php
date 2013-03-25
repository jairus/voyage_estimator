<?php
$DATABASE_HOST     = "s-bis.cfclysrb91of.us-east-1.rds.amazonaws.com";
$DATABASE_USER     = "sbis";
$DATABASE_PASSWORD = "roysbis";
$DATABASE          = "sbis";

function dbQuery($query){
	global $DATABASE_HOST;
	global $DATABASE_USER;
	global $DATABASE_PASSWORD;
	global $DATABASE;
	
	$returnArr = array();

	$link = mysql_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASSWORD) or die("Could not connect : " . mysql_error());
	mysql_select_db($DATABASE) or die("Could not select database");

	$result = mysql_query($query) or die("Query failed : " . mysql_error() . "<br>Query: <b>$query</b>");

	if(@mysql_num_rows($result)){
		while ($row = mysql_fetch_assoc($result)){array_push($returnArr, $row);}		
	}else if(@mysql_insert_id()){
		$returnArr["mysql_insert_id"] = @mysql_insert_id();
	}else{
		mysql_close($link);	
		return $returnArr;
	}
	
	@mysql_free_result($result);

	mysql_close($link);	
	
	return $returnArr;
}

class mySql {
	public function mySql() {}

	public function mySql_insert($tablename, $fields=array(), $values=array()) {
		$temp_f	= array();
		$temp_v	= array();
		$t		= count($fields);
		$t2		= count($values);
		
		if($tablename&&$t&&$t2){
			foreach($fields as $key => $val) {$temp_f[]	= "`".$val."`";}
			
			$i_fields = implode(", ", $temp_f);
			
			foreach($values as $key => $val) {$temp_v[]	= "'".mysql_escape_string($val)."'";}
			
			$i_values = implode(", ", $temp_v);
			$sql      = "INSERT INTO ".$tablename." (`datecreated`, ".$i_fields.") VALUES (NOW(), ".$i_values.")";
			$return   = dbQuery($sql);
			
			return $return;
		}else{return false;}
	}
	
	public function mySql_select($tablename, $fields=array(), $where) {
		if( $tablename && count($fields) ){
			$temp_f	= array();
			
			foreach($fields as $key => $val) {$temp_f[]	= "`".$val."`";}
			
			$i_fields = implode(", ", $temp_f);
			$sql      = "SELECT ".$i_fields." FROM ".$tablename." ".$where;
			$return   = dbQuery($sql);
			return $return;
			
		}else{return false;	}
	}
	
	public function mySql_update($tablename, $fields=array(), $values=array(), $where) {
		$temp_f	= array();
		$temp_v	= array();
		$t		= count($fields);
		$t2		= count($values);
		
		if($tablename&&$t&&$t2&&$where){
			foreach($fields as $key => $val) {$temp_f[]	= "`".$val."` = '".mysql_escape_string($values[$key])."'";}

			$i_fields_val = implode(", ", $temp_f);
			$sql          = "UPDATE ".$tablename." SET ".$i_fields_val.", `datemodified` = NOW() ".$where;
			$return       = dbQuery($sql);
			
			return $return;
		}else{return false;}
	}
}
?>