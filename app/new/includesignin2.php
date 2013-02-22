<? @session_start(); ?>
<div id="show-success-dialog" title="International Law" style="display:none;">Sorry due to International Law we are prohibited from supplying data to your countries.</div>
<style>
.bookmark_link{
	font-family:Arial, Helvetica, sans-serif;
	font-size:14px;
	color:#FFF;
	width:auto;
	height:auto;
	padding:5px 10px;
	background-color:#69B3E3;
	text-decoration:none;
	-moz-border-radius:5px;
	border-radius:5px;
	cursor:pointer;
}

.bookmark_link:hover{
	color:#333;
	background-color:#88bfe3;
}

.live_ship_position_link{
	font-family:Arial, Helvetica, sans-serif;
	font-size:14px;
	color:#FFF;
	width:auto;
	height:auto;
	padding:5px 10px;
	background-color:#0CF;
	text-decoration:none;
	-moz-border-radius:5px;
	border-radius:5px;
}

.live_ship_position_link:hover{
	color:#333;
	background-color:#0FF;
}
</style>
<div id="signin">
    <?php if(!$_SESSION['user']){ ?>
    	<div style='padding-top:60px;'><a onclick="window.external.AddFavorite(location.href, document.title);" class="bookmark_link"><img src="app/images/icon_book.png" /> BOOKMARK THIS PAGE</a> &nbsp; <a href="portagents.php" class="live_ship_position_link">PORT AGENTS</a></div>
	<?php } ?>
</div>