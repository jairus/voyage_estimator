<?php
@session_start();
date_default_timezone_set('UTC'); 
$DATABASE_HOST = "192.168.0.195";

if($_SERVER['HTTP_HOST']=='portal.s-bis.com'||$_SERVER['HTTP_HOST']=='s-bis.com'||$_SERVER['HTTP_HOST']=='s-bisonline.com'||
	$_SERVER['HTTP_HOST']=='www.s-bis.com'||$_SERVER['HTTP_HOST']=='www.s-bisonline.com'||$_SERVER['HTTP_HOST']=='dev.s-bisonline.com'||1){
	$adjusthours = -7*60*60; //depending on the server default time zone, this server is -7
	/*
	$DATABASE_HOST = "localhost";
	$DATABASE_USER = "sbiscom_root";
	$DATABASE_PASSWORD = "roy123!";
	$DATABASE_G = "sbiscom_sbis";	
	*/
	$DATABASE_WRITE = "s-bis.cfclysrb91of.us-east-1.rds.amazonaws.com";
	$DATABASE_READ1 = "s-bis-replica1.cfclysrb91of.us-east-1.rds.amazonaws.com";
	$DATABASE_READ2 = "s-bis-replica1.cfclysrb91of.us-east-1.rds.amazonaws.com";
	$DATABASE_HOST = $DATABASE_WRITE;
	
	$DATABASE_USER = "sbis";
	$DATABASE_PASSWORD = "roysbis";
	$DATABASE_G = "sbis";	

	
	
	
	
	
}
else{
	$adjusthours = 0;
	$DATABASE_USER = "root";
	$DATABASE_PASSWORD = "mow357burn437";
	$DATABASE_G = "s-bis";
}

if($_SERVER['HTTP_HOST']=='www2.s-bisonline.com'){
	$DATABASE_HOST = "localhost";
	$DATABASE_USER = "root";
	$DATABASE_PASSWORD = "mow357burn437";
	$DATABASE_G = "s-bis";
}
$MS_DATABASE_HOST = "JAI-VAIO\SQLEXPRESS";
$MS_DATABASE_USER = "root";
$MS_DATABASE_PASSWORD = "";

$MS_DATABASE = "AIS".date("y").date("m").date("d");	


$JAILINK = "";

function str2time($time){
	global $adjusthours;
	date_default_timezone_set('UTC'); 
	return strtotime($time);
}

function dbConnectWrite(){
    global $DATABASE_WRITE;
    global $DATABASE_USER;
    global $DATABASE_PASSWORD;
    global $DATABASE;
    /* Connecting, selecting database */
    $link = mysql_connect($DATABASE_WRITE, $DATABASE_USER, $DATABASE_PASSWORD)
        or die("Could not connect : " . mysql_error());
	return $link;
}
if(!trim($_SESSION['writelink'])){
	$writelink = dbConnectWrite();
	$_SESSION['writelink'] = $writelink;
}
function dbConnectRead(){
    global $DATABASE_READ1, $DATABASE_READ2;
    global $DATABASE_USER;
    global $DATABASE_PASSWORD;
    global $DATABASE;
	
	if(time()%2){
		$DATABASE_READ = $DATABASE_READ1;
	}
	else{
		$DATABASE_READ = $DATABASE_READ2;
	}
    /* Connecting, selecting database */
    $link = mysql_connect($DATABASE_READ, $DATABASE_USER, $DATABASE_PASSWORD)
        or die("Could not connect : " . mysql_error());
	return $link;
}

if(!trim($_SESSION['readlink'])){
	$readlink = dbConnectRead();
	$_SESSION['readlink'] = $readlink;
}

function dbConnect(){
    return "";
}
function dbQuery($query, $link = "", $forcewrite="")
{
    global $DATABASE_HOST, $DATABASE_READ, $DATABASE_WRITE;
    global $DATABASE_USER;
    global $DATABASE_PASSWORD;
    global $DATABASE_G;
	global $JAILINK;
	global $readlink;
	$DATABASE = $DATABASE_G;
	
	$querysample = strtolower(trim($query));
	
	if($forcewrite){
		$link = $_SESSION['writelink'];
	}
	else{
		if(strpos($querysample, "select")===0){
			$DATABASE_HOST = $DATABASE_READ;
			$link = $_SESSION['readlink'];
		}
		else{
			$DATABASE_HOST = $DATABASE_WRITE;
			$link = $_SESSION['writelink'];
		}
	}	
	if(strpos($query, '_xvas_siitech_cache')!==false&&0){
		if($JAILINK==""){
			//link to jai's database
			$dhost = '122.49.210.170';
			$duser = 'root';
			$dpass = 'hawkhost123!';
			$JAILINK = mysql_connect($dhost, $duser, $dpass) or die("Could not connect : " . mysql_error());		
		}
		$link = $JAILINK;
		$DATABASE = 's-bis';
	}
    $returnArr = array();

    /* Connecting, selecting database */
	if(!$link){
	    $link = mysql_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASSWORD) or die("Could not connect : " . mysql_error());
	}
	
    mysql_select_db($DATABASE, $link) or die("Could not select database");

    /* Performing SQL query */
    $result = mysql_query($query, $link) or die("Query failed : " . mysql_error() . "<br>Query: <b>$query</b>");

    
    //if query is select
    if(@mysql_num_rows($result))
    {
        while ($row = mysql_fetch_assoc($result))
        {
            array_push($returnArr, $row);
        }       
    }
    //if query is insert
    else if(@mysql_insert_id($link))
    {
        $returnArr["mysql_insert_id"] = @mysql_insert_id($link);
    }
    //other queries
    else
    {
		if(!$link){
        	/* Closing connection */
        	mysql_close($link); 
		}
        return $returnArr;
    }
        

    /* Free resultset */
    @mysql_free_result($result);

	if(!$link){
		/* Closing connection */
		mysql_close($link); 
	}
    
    //return array
    return $returnArr;
}

function msDbConnect(){
	global $MS_DATABASE_HOST, $MS_DATABASE_USER, $MS_DATABASE_PASSWORD;
	$link = mssql_connect($MS_DATABASE_HOST, $MS_DATABASE_USER, $MS_DATABASE_PASSWORD);
	return $link;
}

function msDbQuery($tsql, $link=""){
	global $MS_DATABASE;
	$rows = array();
	// Connect to MSSQL
	if($link==""){
		$link = msDbConnect();
	}
	if (!$link || !mssql_select_db($MS_DATABASE, $link)) {
		die('Unable to connect or select database!');
	}
	
	// Do a simple query, select the version of 
	// MSSQL and print it.
	$handle = mssql_query($tsql);
	while($row = mssql_fetch_assoc($handle)){
		$rows[] = $row;
	}
			
	// Clean up
	mssql_free_result($handle);
		
	return $rows;
	
	


	/* Connect to the local server using Windows Authentication and
	specify the AdventureWorks database as the database in use. */
	$serverName = "JAI-PC\SQLEXPRESS";
	$connectionInfo = array( "Database"=>"AIS");
	$conn = sqlsrv_connect( $serverName, $connectionInfo);
	if( $conn === false )
	{
		 echo "Could not connect.\n";
		 die( print_r( sqlsrv_errors(), true));
	}
	
	/* Set parameter values. */
	//$params = array(75123, 5, 741, 1, 818.70, 0.00);
	
	/* Prepare and execute the query. */
	$stmt = sqlsrv_query( $conn, $tsql, $params);
	
	while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
	{
		$rows[] = $row;
	}
	/* Free statement and connection resources. */
	sqlsrv_free_stmt( $stmt);
	sqlsrv_close( $conn);
	return $rows;
}

?>