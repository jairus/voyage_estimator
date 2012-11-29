<?php
@session_start();
include_once(dirname(__FILE__)."/database.php");
function jsredirect($url){
	@ob_end_clean();
	?>
	<script>
	self.location = '<?php echo $url; ?>';
	</script>
	<?php
	exit();
}
function my_escape($str){
	return mysql_escape_string($str);
}
class tabs{
	function addTab($page, $tabdata, $tabname=''){
		global $user;
		$uid = $user['uid'];	
		if($tabname==''){
			$sql = "insert into `_user_tabs` (
				`uid`,
				`page`,
				`tabdata`,
				`tabname`,
				`dateadded`
			)
			values(
				'".my_escape($uid)."',
				'".my_escape($page)."',
				'".my_escape($tabdata)."',
				'New Tab',
				NOW()
			)
			";
		}
		else{
			$sql = "insert into `_user_tabs` (
				`uid`,
				`page`,
				`tabdata`,
				`tabname`,
				`dateadded`
			)
			values(
				'".my_escape($uid)."',
				'".my_escape($page)."',
				'".my_escape($tabdata)."',
				'".my_escape($tabname)."',
				NOW()
			)
			";		
		}
		$r = dbQuery($sql);	
		return $r;		
	}
	function delTab($id){
		global $user;
		$uid = $user['uid'];	
		$sql = "delete from `_user_tabs` where `uid`='".my_escape($uid)."' and `id`='".my_escape($id)."'";
		dbQuery($sql);
	}
	function updateTab($page, $tabid="", $tabdata="", $tabname=""){
		global $user;
		$uid = $user['uid'];
		$tab = $this->getTab($page, $tabid);
		if($tab){
			/*$sql = "select * from `_user_tabs` where `uid`='".my_escape($uid)."' and `page`='".my_escape($page)."' and `tabname` like '".my_escape($tabname)." [%' order by `id` desc";
			$tabs = dbQuery($sql);
			$num = count($tabs) + 1;
			*/
			$tabid = $tab['id'];
			$sql = "update `_user_tabs` set `tabname`='".my_escape($tabname)."', `tabdata`='".my_escape($tabdata)."' where `uid`='".my_escape($uid)."' and `page`='".my_escape($page)."' and `id`='".my_escape($tabid)."'";
			dbQuery($sql);
		}
	}
	function getTab($page, $tabid=""){
		global $user;
		$uid = $user['uid'];
		if($tabid){
			$sql = "select * from `_user_tabs` where `uid`='".my_escape($uid)."' and `page`='".my_escape($page)."' and `id`='".my_escape($tabid)."' order by `id` desc";
			$tab = dbQuery($sql);
		}
		if(!$tab[0]){
			$sql = "select * from `_user_tabs` where `uid`='".my_escape($uid)."' and `page`='".my_escape($page)."' order by `id` desc";
			$tab = dbQuery($sql);
		}
		return $tab[0];
	}
	function countTabs($page){
		global $user;
		if($page){
			$uid = $user['uid'];		
			$sql = "select count(*) as `cnt` from `_user_tabs` where `uid`='".my_escape($uid)."' and `page`='".my_escape($page)."' order by `id` desc";
			$tabs = dbQuery($sql);
		}
		return $tabs[0]['cnt'];
	}
	function getTabs($page){
		global $user;
		if($page){
			$uid = $user['uid'];
			$sql = "select * from `_user_tabs` where `uid`='".my_escape($uid)."' and `page`='".my_escape($page)."' order by `id` desc";
			$tabs = dbQuery($sql);
		}
		return $tabs;
	}
	function showTabs($page, $tabid="", $autoadd=false){
		global $user;
		$tabs = $this->getTabs($page);
		$t = count($tabs);
		if($_GET['tab']){
			$tabid = $_GET['tab'];
		}
		if(!$t&&$autoadd){
			$tabdata = array();
			$tabdata['title'] = 'Search Tab';
			$tabdata = serialize($tabdata);
			$r = $this->addTab($page, $tabdata);
			$tabs = $this->getTabs($page);
			$t = count($tabs);					
		}
		for($i=0; $i<$t; $i++){
			$tabdata = unserialize($tabs[$i]['tabdata']);
			if($tabid==""&&$i==0){
				?>
				<li class="sbis-tab">
				  <div class="tab active" style="border: 1px solid rgb(211, 211, 211); padding: 7px 5px 7px 0px; -moz-border-radius-topleft: 8px; -moz-border-radius-bottomleft: 8px;"> <span id="tabclose" onclick='self.location="?new_search=1&deltab=<?php echo $tabs[$i]['id']; ?>"'></span>
					<div class="middle" id="tabtitle"><?php echo $tabs[$i]['tabname'];?></div>
				  </div>
				</li>
				<p>&nbsp;</p>
				<?php
			}
			else if($tabs[$i]['id']!=$tabid){
				?>
				<li class="sbis-tab" >
				  <div class="tab" style="border: 1px solid rgb(211, 211, 211); padding: 7px 0px 7px 10px; width:142px; -moz-border-radius-topleft: 8px; -moz-border-radius-bottomleft: 8px;">
				  <span id="tabclose" style='left:-10px; position:relative;' onclick='self.location="?new_search=1&deltab=<?php echo $tabs[$i]['id']; ?>"'></span>
					<div onclick="self.location='?new_search=1&tab=<?php echo $tabs[$i]['id']; ?>'" class="middle"><?php echo $tabs[$i]['tabname']?></div>
				  </div>
				</li>
				<p>&nbsp;</p>			
				<?php
			}
			else{
				?>
				<li class="sbis-tab">
				  <div class="tab active" style="cursor: default; border: 1px solid rgb(211, 211, 211); padding: 7px 5px 7px 0px; -moz-border-radius-topleft: 8px; -moz-border-radius-bottomleft: 8px;"> <span id="tabclose" onclick='self.location="?deltab=<?php echo $tabs[$i]['id']; ?>"'></span>
					<div class="middle" id="tabtitle"><?php echo $tabs[$i]['tabname']?></div>
				  </div>
				</li>
				<p>&nbsp;</p>
				<?php
			}
		}
	}
}
$tabsys = new tabs();

if($_GET['deltab']){
	$tabsys->delTab($_GET['deltab']);
	jsredirect("?new_search=1");
}
else if($_POST['newtab']&&$user['uid']!=""){
	$uid = $user['uid'];
	$page = trim(strtolower($_POST['newtab']));
	if($page=='shipsearch'){
		$tabdata = array();
		$tabdata = serialize($tabdata);
		$r = $tabsys->addTab($page, $tabdata);
		jsredirect("?tab=".$r['mysql_insert_id']);
	}
}

?>