<?php
/*Header( "HTTP/1.1 301 Moved Permanently" );
Header( "Location: http://main.s-bisonline.com" ); */

@session_start();

/*include('app/includes/Snoopy.class.php');

$snoopy = new Snoopy();

$snoopy->httpmethod = "GET";
$snoopy->submit("http://www.bunkerworld.com/prices/feeds/fairplay");

$contents = $snoopy->results;

$lines = explode('"', $contents);
$counterall = 1;
$counter = 1;
if ($lines) {
	foreach ($lines as $line) {
		if($counterall!=2){
			if($counterall!=4){
				if($counterall!=6){
					if($counterall!=8){
						if($counter==5){
							$counter = 1;
						}
						
						if(trim($line)!=""){
							if($line!=","){
								echo $counter.".)".strtoupper($line)."<br />";
								
								$counter++;
							}
						}
					}
				}
			}
		}
		
		$counterall++;
	}
}*/


/*echo "<pre>";
print_r($snoopy->results);
*/
if($_SESSION['user']){



	?>

<script>



	self.location = '/app/';



	</script>

<?php



	exit();



}



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<TITLE>S-BIS Ship Brokering Intelligence Solutions</TITLE>

<META NAME="author" CONTENT="Roy Devlin ">

<META NAME="subject" CONTENT="Ship brokering finding the right ship at the right time at the right place">

<META NAME="Description" CONTENT="Intelligence and Maritime Information focused on the maritime industry. Maintaining databases on ships, real-time ship AIS tracking, for brokers, owners, managers. Data includes casualties, detentions, vessel tracking, PSC, ports and maritime/shipping/brokerage companies">

<META NAME="Classification" CONTENT="Maritime Information, Vessel Tracking, Ship Tracking AIS, Ship Register, Brokers, Real Time, AIS Data, Vessel Movements, Maritime Intelligence, Piracy, Safety, Green Shipping, Vessel and Ships Database, Worldscale, Fixture, Recap, Charter Party Forms, Q88, OCIMF, IMO, Ports, Weather and Satellite AIS.  ">

<META NAME="Keywords" CONTENT=" ship, ais,brokering,maritime,tracking,s-bis,intelligence">

<META NAME="Geography" CONTENT="6 Eu Tong Sen Street, 10-10 The Central, Singapore, 059817 ">

<META NAME="Language" CONTENT="English">

<META HTTP-EQUIV="Expires" CONTENT="never">

<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">

<META NAME="Copyright" CONTENT="Maritime Infosys Pte Ltd">

<META NAME="Designer" CONTENT="Gruntwerkz">

<META NAME="Publisher" CONTENT="Onwer">

<META NAME="Revisit-After" CONTENT="yes">

<META NAME="distribution" CONTENT="Global">

<META NAME="Robots" CONTENT="INDEX,FOLLOW">

<META NAME="city" CONTENT="Singapore">

<META NAME="country" CONTENT="Singapore">

<meta name="abstract" content="Ship Brokering Intelligence.">

</meta>

<meta name="alexa" content="100">

</meta>

<meta name="classification" content="maritime information, shipping news, ship tracking, maritime security, vessel tracking, ship movements, ship register, ship database, maritime intelligence, vessel database.">

</meta>

<meta name="googlebot" content="all, index, follow">

</meta>

<meta name="googlebot" content="all, index, follow, none, noindex, nofollow, noarchive, nosnippet">

</meta>

<meta name="pagerank™" content="10">

</meta>

<meta name="revisit" content="2 days">

</meta>

<meta name="revisit-after" content="2 days">

</meta>

<meta name="robots" content="all, index, follow">

</meta>

<meta name="robots" content="all, index, follow, none, noindex, nofollow">

</meta>

<meta name="serps" content="1, 2, 3, 10, 11, 12, 13, ATF">

</meta>

<meta name="seoconsultantsdirectory" content="5">

</meta>

<?php include("includehead.php"); ?>

</head>

<body>

<div id="bodytop">

  <?php include("includesignin.php"); ?>

  <div id="bodytopgradient"></div>

</div>

<div id="container">

  <?php include("includenavbar.php"); ?>

  <div class="mainimg"> <img src="images/home/img_main-png.png" title="SHIP BROKERING AND AIS SHIP TRACKING" alt="SHIP BROKERING AND AIS SHIP TRACKING" width="940" height="361" border="0" /></div>

  <div id="colourboxes">

    <div id="colourbox" class="colourbox1"> <a href="search.php" class="colorboxbtn"></a>

      <div class="boxplacement">

        <div class="boxdetail">
        	<table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="35" valign="top"><img src="images/home/iconsmall_positions.gif" title="AIS VESSEL TRACKING" alt="AIS VESSEL TRACKING" name="Ship  or Vessel Tracking finding their position in S-BIS" width="35" height="35" /></td>
                <td valign="top">
                	<h6><a href="search.php#positions">AIS SHOREsearch</a></h6>
          			<p> AIS SHOREsearch  finds all the ships in every major port.</p>
                </td>
              </tr>
            </table>
        </div>

        <div class="boxdetail">
        	<table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="35" valign="top"><img src="images/home/iconsmall_ports.gif" title="AIS SATELLITE VESSEL TRACKING" alt="AIS SATELLITE VESSEL TRACKING" name="Ports Data and Ports Information including Lat &amp; Long" width="35" height="35" /></td>
                <td valign="top">
                	<h6><a href="search.php#positions">AIS SATELLITEsearch</a></h6>
          			<p>AIS SATELLITEsearch finds EVERY ship in the ocean.</p>
                </td>
              </tr>
            </table>
        </div>

        <div class="boxdetail">
        	<table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="35" valign="top"><img src="images/home/iconsmall_cargo.gif" title="SHIP FIXTURE MANAGEMENT" alt="SHIP FIXTURE MANAGEMENT" name="Match your cargo to the Right Ship using S-BIS" width="35" height="35" /></td>
                <td valign="top">
                	<h6><a href="search.php#cargo">BROKERSintelligence</a></h6>
          			<p>BROKERSintelligence adds priceless market knowledge</p>
                </td>
              </tr>
            </table>
        </div>

      </div>

    </div>

    <div id="colourbox" class="colourbox2"> <a href="fixture.php" class="colorboxbtn"></a>

      <div class="boxplacement">

        <div class="boxdetail">
        	<table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="35" valign="top"><img src="images/home/iconsmall_quicklook.gif" title="SHIP BROKERING TOOLS" alt="SHIP BROKERING TOOLS" name="S-BIS Fixtures Module takes Brokering to the next level" width="35" height="35" /></td>
                <td valign="top">
                	<h6><a href="fixture.php#quicklook">Quick Look </a></h6>
          			<p>Reviewing probably the widest choice of ships available</p>
                </td>
              </tr>
            </table>
        </div>

        <div class="boxdetail">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="35" valign="top"><img src="images/home/iconsmall_voyage.gif" title="VOYAGE ESTIMATION" alt="VOYAGE ESTIMATION" name="S-BIS Voyage Estimator making intelligent choices based on accurate information" width="35" height="35" /></td>
                <td valign="top">
                	<h6><a href="fixture.php#voyestimator">Voyage Estimator</a></h6>
          			<p>Getting down to the business of closing the charter</p>
                </td>
              </tr>
            </table>
        </div>

        <div class="boxdetail">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="35" valign="top"><img src="images/home/iconsmall_offerbuilder.gif" title="SHIP CHARTERING" alt="SHIP CHARTERING" name="S-BIS Offer Builder closing the deal doing the trade and not forgetting the details" width="35" height="35" /></td>
                <td valign="top">
                	<h6> <a href="fixture.php#offerbuilder">Offer Builder </a></h6>
          			<p>Getting an &quot;Offer' ready to email the ‘rate idea’ .</p>
                </td>
              </tr>
            </table>
        </div>

      </div>

    </div>

    <div id="colourbox" class="colourbox3"> <a href="recap.php" class="colorboxbtn"></a>

      <div class="boxplacement">

        <div class="boxdetail">
        	<table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="35" valign="top"><img src="images/home/iconsmall_vetting.gif" title="SHIP VETTING" alt="SHIP VETTING" name="S-BIS using Ocean Intelligence for credit risk analysis" width="35" height="35" /></td>
                <td valign="top">
                	<h6><a href="recap.php#vetting">Vetting Information</a></h6>
          			<p>Ocean Intelligence &amp; Ship reliability analytics</p>
                </td>
              </tr>
            </table>
        </div>

        <div class="boxdetail">
        	<table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="35" valign="top"><img src="images/home/iconsmall_charter.gif" title="CHARTER PARTY SOFTWARE" alt="CHARTER PARTY SOFTWARE" name="S-BIS Charter Party forms, keeping the information close at hand." width="35" height="35" /></td>
                <td valign="top">
                	<h6><a href="recap.php#charter">Charter Party Editor</a></h6>
          			<p>Quick way to execute freight contracts</p>
                </td>
              </tr>
            </table>
        </div>

        <div class="boxdetail">
        	<table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="35" valign="top"><img src="images/home/iconsmall_archive.gif" title="Q88 FORMS" alt="Q88 FORMS" name="S-BIS keeps track if you want to of information of Searches and data" width="35" height="35" /></td>
                <td valign="top">
                	<h6><a href="recap.php#archives">Archives</a></h6>
          			<p>History of your fixtures and AIS data</p>
                </td>
              </tr>
            </table>
        </div>

      </div>

    </div>

  </div>

  <div id="boxlower">

    <div class="boxlowerleft">

      <div class="boxlowerleftcontent"> <img src="images/home/ship4.jpg"  title="AIS SATELLITE SHIP TRACKING" alt="AIS SATELLITE SHIP TRACKING" name="S-BIS find you the Right Ship using technology along with your experience. AIS Tracking Ships data is only as good as its accuracy." width="200" height="150" class="floatright" />
		
        <p>The right information, available at the right time, can give a priceless advantage in planning and execution. S-BIS, Ship Brokering Intelligence Solutions, is the world’s first, fully integrated AIS live web and mobile application with both <strong>AIS SATELLITE & SHORE tracking</strong>. With 165,000 ships classified and cross-referenced by 624 data fields, it’s as comprehensive as it gets. With S-BIS, brokering is now smooth sailing.</p>
        <!--<p>The right information, available at the right time, can give a priceless advantage in planning and execution. For ship-brokering professionals the tool that can give them this competitive edge is available now. S-BIS, Ship Brokering Intelligence Solutions, is the world’s first, fully integrated AIS live web and mobile application. With 165,000 ships classified and cross-referenced by 624 data fields, it’s as comprehensive as it gets. And it’s also mobile, which means brokers need no longer be chained to their desks and desktops. With S-BIS, brokering is now smooth sailing.</p>

        <p>Intelligence and Maritime Information focused on the maritime industry. Maintaining databases on ships, <strong>real-time ship AIS SATELLITE &amp; SHORE tracking</strong>, for brokers, owners/managers. Data includes casualties, detentions, vessel tracking, PSC, ports and maritime/shipping/brokerage companies.

        </p>

        <p>&nbsp;</p>-->

      </div>

    </div>

    <div class="boxlowerright"> <img src="images/home/header_boxlowerright.gif" alt="Finding the Vessel by vessel tracking" name="Finding the Vessel by vessel tracking" width="275" height="80" />

      <p>Imagine knowing a ship’s details even when it’s thousands of miles away.  Imagine the head start it can give you to plan and execute a deal. That is what S-BIS offers you now. It is the one-stop ship-brokering resource every professional in the industry needs. </p>

      <p>Why wait? <a href="signup.php">Get Started Now.</a> Register here to enjoy the introductory low monthly fee offer.</p>

    </div>

  </div>

</div>

</body>

</html>

