<?php
include_once(dirname(__FILE__)."/includes/bootstrap.php");
include_once(dirname(__FILE__)."/layout/header.php");

?>
  <div id="bodycontainer">

    <div id="leftmenu">

      <div class="block block-sbis" id="block-sbis-0">

        <h2 class="title"></h2>

        <div class="content">

          <ul class="menu sbis-tabmenu">

            <form method="post" action="">

              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

			  <input type='hidden' name='newtab' value='shipsearch'>

              <input value="+ new search" class="form-button"  type="submit">

            </form>

            <br>

			<?php

			$tabsys->showTabs("shipsearch",'',true); //auto add tab if no tabs

			?>

          </ul>

        </div>

      </div>

    </div>

    <div id="contentarea"> 

	<?php

	include_once(dirname(__FILE__)."/topmessage.php");

	if($user['dry']==1){
		include_once(dirname(__FILE__)."/search_ext_dry.php");
	}elseif($user['dry']==2){
		include_once(dirname(__FILE__)."/search_ext_container.php");
	}elseif($user['dry']==3){
		include_once(dirname(__FILE__)."/search_ext_osv.php");
	}elseif($user['dry']==4){
		include_once(dirname(__FILE__)."/search_ext_gas.php");
	}elseif($user['dry']==5){
		include_once(dirname(__FILE__)."/search_ext_passenger.php");
	}elseif($user['dry']==6){
		include_once(dirname(__FILE__)."/search_ext_others.php");
	}elseif($user['dry']==0){
		include_once(dirname(__FILE__)."/search_ext.php");
	}

	?>

	</div>

<?php

include_once(dirname(__FILE__)."/layout/footer.php");

?>
<center>
<table width="100%" height="100%" id='pleasewait2' style='display:none; position:fixed; top:0; left:0; z-index:100; background-image:url("images/loading_img/<?php echo rand(1, 17); ?>.gif"); background-position:center; background-repeat:no-repeat; background-attachment:scroll; filter:alpha(opacity=90); opacity:0.9;'>
    <tr>
        <td style='text-align:center'><img src='images/searching.gif' ></td>
    </tr>
</table>
</center>