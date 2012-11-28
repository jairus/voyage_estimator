<style>
.history_link{
	font-family:Arial, Helvetica, sans-serif;
	font-size:10px;
	font-weight:bold;
	color:#900;
	text-decoration:none;
	cursor:pointer;
}

.history_link:hover{
	color:#F00;
}

.history_link_nothing{
	font-family:Arial, Helvetica, sans-serif;
	font-size:10px;
	font-weight:bold;
	color:#000;
	text-decoration:none;
	cursor:pointer;
}
</style>
<?php
echo "<form id='positions' method='POST' style='margin:0px;' action='fixtures.php'>";
echo "<input type='hidden' name='searchtabdata' id='searchtabdata'>";
echo "<div style='text-align:left; padding:5px;'><b>CURRENT DATE/TIME: ".date("M j, Y G:i e", time())."</b></div>";
include_once(dirname(__FILE__)."/positionsA1.php");
include_once(dirname(__FILE__)."/positionsA2.php");
include_once(dirname(__FILE__)."/positionsA3.php");
include_once(dirname(__FILE__)."/positionsA4.php");
include_once(dirname(__FILE__)."/positionsA5.php");
echo "</form>";
?>