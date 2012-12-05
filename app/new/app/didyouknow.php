<center>
<?php
include_once(dirname(__FILE__)."/includes/bootstrap.php");

$n =  rand ( 1 , 3 );

if($n==1){
	?>
	<div style='padding-bottom:30px; vertical-align:middle;'>
	<img src = "images/misc/did_you_know_blue.gif" width="600">
	</div>
	<?php
}
else if($n==2){
	?>
	<div style='padding-bottom:30px; vertical-align:middle;'>
	<img src = "images/misc/did_you_know_green.gif" width="600">
	</div>
	<?php
}
else{
	?>
	<div style='padding-bottom:30px; vertical-align:middle;'>
	<img src = "images/misc/did_you_know_orange.gif" width="600">
	</div>
	<?php
}
?>
</center>