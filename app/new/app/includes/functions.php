<?php
@session_start();
include_once(dirname(__FILE__)."/database.php");
function cleanXML2($data){
	$str = $data;
	$r = "/(<[^\/]{1}[^>]+)\/([^>]+>)/iUs";
	$matches = array();
	preg_match_all($r, $str, $matches);
	$matches = $matches[0];
	$t = count($matches);
	for($i=0; $i<$t; $i++){
		$replacement = str_replace("/", "_", $matches[$i]);
		$str = str_replace($matches[$i], $replacement, $str);
	}

	
	$r = "/(<\/[^>]+)\/([^>]+>)/iUs";
	$matches = array();
	preg_match_all($r, $str, $matches);
	$matches = $matches[0];
	$t = count($matches);
	for($i=0; $i<$t; $i++){
		$replacement = str_replace("</", "-=jairus=-", $matches[$i]);
		$replacement = str_replace("/", "_", $replacement);
		$replacement = str_replace("-=jairus=-", "</", $replacement);
		$str = str_replace($matches[$i], $replacement, $str);
	}
	return $str;
}

function getValue($data, $id){

	$reg = "/<".$id.".*>(.*)<\/".$id.">/iUs";

	$matches = array();

	preg_match_all($reg, $data, $matches);

	return $matches[1][0];

}

function getValue2($data, $id, $type){
	$reg = "/<".$id.".*".$type.".*>(.*)<\/".$id.">/iUs";

	$matches = array();

	preg_match_all($reg, $data, $matches);

	return $matches[1][0];
}

function getXMLtoArr($xml){

	$reg = "/<(.*)>(.*)<\/(.*)>/iUs";

	$matches = array();

	preg_match_all($reg, $xml, $matches);

	$arr = array();

	$t = count($matches[1]);

	for($i=0; $i<$t; $i++){

		$arr[$matches[1][$i]] = $matches[2][$i];

	}

	return $arr;

}

function parseXML($data){
	$data = str_replace("&", "&amp;", $data);
	$data = utf8_encode($data);
	$data = cleanXML2($data);
	$data = "<?xml version='1.0'?><document>".$data."</document>";
	return simplexml_load_string($data);
}

function dateToTs($str){

	$lpf = $str;

	$lpf = explode("/", $lpf);

	$lpfts = strtotime($lpf[1]."-".$lpf[0]."-".$lpf[2]." 00:00:00");

	return $lpfts;

}

function redirectjs($url){

	ob_end_clean();

	?>

	<script>

	self.location = "<?php echo $url; ?>";

	</script>

	<?php

	exit();

}
function killSession($sid){
	session_start();
	session_start();
	$mysessionid = session_id(); //get current session id
	if($mysessionid!=$sid){
		//kill the other session
		session_id($sid);
		session_start();
		session_unset();
		session_destroy();
		
		session_id($mysessionid); //retain session id
		session_start();
	}
}

function handleSession(){
	if(!trim($_SESSION['user']['email'])){
		return false;
	}
	$sql = "select * from  `_sessions` where `user_email` = '".$_SESSION['user']['email']."' AND `session_id` = '".session_id()."' and `active`='1' LIMIT 1";
	$r = dbQuery($sql);
	$r = $r[0];
	
	if(!$r['id']){
		//get all other session that are active
		$sql = "select * from  `_sessions` where `user_email` = '".$_SESSION['user']['email']."' and `active`='1'";
		$r = dbQuery($sql);
		$t = count($r);
		$killedsomebody = false;
		for($i=0; $i<$t; $i++){
			//kill other sessions
			killSession($r[$i]['session_id']);
			//update database
			$sql = "update `_sessions` set `active`=0 where `id`='".$r[$i]['id']."'";
			dbQuery($sql);
			$killedsomebody = true;
		}
		
		$sql = "insert into `_sessions` (`user_email`, `session_id`, `ip`, `country`, `active`, `dateadded`) 
		values
		(
			'".$_SESSION['user']['email']."',
			'".session_id()."',
			'".$_SERVER['REMOTE_ADDR']."',
			'',
			'1',
			NOW()
		)
		";
		
		dbQuery($sql);
		
		if($killedsomebody){
			redirectjs("/app/");
			exit();
		}
	}
}

function checklogin(){
	global $user;
	
	if($user['expired']==1){
		//echo "account expired";
	}
	
	if($user['uid']!=""){
		handleSession();
	}

	if($user['is_owner']&&(

		(strpos($_SERVER['PHP_SELF'], "index.php")!==false&&strpos($_SERVER['PHP_SELF'], "map/index.php")===false)||

		strpos($_SERVER['PHP_SELF'], "registration.php")!==false||

		strpos($_SERVER['PHP_SELF'], "forgotpassword.php")!==false||

		strpos($_SERVER['PHP_SELF'], "login.php")!==false||

		strpos($_SERVER['PHP_SELF'], "search.php")!==false||

		strpos($_SERVER['PHP_SELF'], "fixtures.php")!==false

		)

	){
			//echo "here..";
			//exit();
			redirectjs("/app/owners.php");

	}

	else if(

		(strpos($_SERVER['PHP_SELF'], "index.php")!==false&&strpos($_SERVER['PHP_SELF'], "map/index.php")===false)||

		strpos($_SERVER['PHP_SELF'], "registration.php")!==false||

		strpos($_SERVER['PHP_SELF'], "forgotpassword.php")!==false||

		strpos($_SERVER['PHP_SELF'], "login.php")!==false

	){

		if($user['uid']!=""){

			redirectjs("/app/cargospotter.php");

		}

		return false;

	}



	if($user['uid']==""){

		redirectjs("/cargospotter.php");

	}

}

function getFlagImage($name){

	global $link;

	$name = mysql_escape_string(trim($name));

	$sql = "select * from `_country_flag` where `country_desc`='".$name."'";

	//echo $sql;

	$country = dbQuery($sql, $link);

	$country = $country[0];

	$id = strtolower($country['flag_id']);

	$f = dirname(__FILE__)."/../images/flags/".$id.".png";

	

	if(file_exists($f)){

		return "images/flags/".$id.".png";	

	}

	else{

		return false;	

	}

}



function createThumb($src, $dest, $thumbWidth, $thumbHeight) 

{

	$info = pathinfo($src);

	// load image and get image size

	$img = @imagecreatefromjpeg( $src );

	if(!$img){

		return false;

	}

	$width = imagesx( $img );

	$height = imagesy( $img );

	$new_width = $width;

	$new_height = $height;

	// calculate thumbnail size

	if($width>$height)

	{

		if($thumbWidth<$width)

		{

			$new_width = $thumbWidth;

			$new_height = floor( $height * ( $thumbWidth / $width ) );

		}

	}

	else

	{

		if($thumbHeight<$height)

		{

			$new_height = $thumbHeight;

			$new_width = floor( $width * ( $thumbHeight / $height ) );

		}

	}

	// create a new temporary image

	$tmp_img = imagecreatetruecolor( $new_width, $new_height );

	

	// copy and resize old image into new image 

	imagecopyresampled( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );

	

	// save thumbnail into a file

	imagejpeg( $tmp_img, $dest );

}
/*
echo "<pre>";
print_r($_SESSION);
echo session_id();
exit();
*/
if(!$_GET['__ve']){
	checklogin();
}

?>