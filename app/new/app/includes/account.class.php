<?php
class account{
	var $link;
	var $user;
	function account(){
		$this->link = dbConnect();
		$sql = "select * from `_sbis_users` where `id`='".$_SESSION['user']['id']."'";
		$r = $this->query($sql);
		$this->user = $r[0];
	}
	function updateAccount($post){
		if(trim($post['password'])){
			$sql = "update `_sbis_users` set 
			`firstname`='".mysql_escape_string($post['firstname'])."',
			`lastname`='".mysql_escape_string($post['lastname'])."',
			`company`='".mysql_escape_string($post['company'])."',
			`password`='".mysql_escape_string(md5($post['password']))."'
			where 
			`id` = '".$this->user['id']."'
			 ";
		}
		else{
			$sql = "update `_sbis_users` set 
			`firstname`='".mysql_escape_string($post['firstname'])."',
			`company`='".mysql_escape_string($post['company'])."',
			`lastname`='".mysql_escape_string($post['lastname'])."'
			where 
			`id` = '".$this->user['id']."'
			 ";
		}
		$this->query($sql);
	}
	function searchUsers($search_cat, $country, $searchfor){
		$searchfor  = mysql_escape_string(trim($searchfor));
		
		if($search_cat=='name'){
			$sql = "select * from `_sbis_users` where (`firstname` like '%".$searchfor."%' or
			`lastname` like '%".$searchfor."%' or
			`email` like '%".$searchfor."%' ) and `country` like '%".$country."%' and `id`<>'".$this->user['id']."'
			";
		}else{
			$sql = "select * from `_sbis_users` where (`company_name` like '%".$searchfor."%')
			and `country` like '%".$country."%' and `id`<>'".$this->user['id']."'
			";
		}
		
		$r = $this->query($sql);
		return $r;
	}
	
	function getNetwork(){
		$sql = "select * from `_sbis_users` where `id` in 
		(
			select `userid1` from `_network` where `userid2`='".$this->user['id']."' and (`confirmed`='1' or `confirmed`='2')
		)
		or `id` in
		(
			select `userid2` from `_network` where `userid1`='".$this->user['id']."' and (`confirmed`='1' or `confirmed`='2')
		)
		";
		$r = $this->query($sql);
		return $r;
	}
	
	function getNetworkRequests(){
		$sql = "select * from `_sbis_users` where `id` in 
		(
			select `userid1` from `_network` where `userid2`='".$this->user['id']."' and `confirmed`='2'
		)
		";
		$r = $this->query($sql);
		return $r;
	}
	
	function inMyNetwork($id){
		$sql = "select * from `_network` where (
		(`userid1`='".$this->user['id']."' and  `userid2`='".$id."') or
		(`userid2`='".$this->user['id']."' and  `userid1`='".$id."')
		) limit 1
		";
		$r = $this->query($sql);
		if($r[0]['confirmed']!=0){ //1 is confirmed 2 is awaiting
			if($r[0]['userid1']==$this->user['id']){
				if($r[0]['confirmed']==1){
					return 'confirmed';
				}
				else if($r[0]['confirmed']==2){
					return 'awaiting';
				}
			}
			else{
				if($r[0]['confirmed']==1){
					return 'confirmed';
				}
				else if($r[0]['confirmed']==2){
					return 'confirm';
				}
			}
		}
		return false;
	}
	function confirmUser($id){
		if($id == $this->user['id']){
			return false;
		}
		$sql = "update `_network` set `confirmed` = 1 where (
		(`userid1`='".$this->user['id']."' and  `userid2`='".$id."') or
		(`userid2`='".$this->user['id']."' and  `userid1`='".$id."')
		)";
		$this->query($sql);
		
		$sql = "select * from  `_sbis_users` where `id` = '".mysql_escape_string($this->user['id'])."'";
		$r = $this->query($sql);
		$r = $r[0];
		$sql = "select * from  `_sbis_users` where `id` = '".mysql_escape_string($id)."'";
		$r2 = $this->query($sql);
		$r2 = $r2[0];
		$from = "mailer@s-bisonline.com";
		$fromname = "S-BIS Mailer";
		$bouncereturn = "mailer@s-bisonline.com"; //where the email will forward in cases of bounced email
		$subject = "S-BIS Network Request Confirmation";
		$emails = array();
		$email = array();
		$email['email'] = $r2['email'];
		$email['name'] = $r2['firstname']." ".$r2['lastname'];
		$emails[] = $email;
		$message = "
		Hello ".strtoupper($r2['firstname'])."

		".$r['firstname']." ".$r['lastname']." (".$r['email'].") has confirmed your request you to be in his network.
		
		Please login to <a href='http://www.s-bisonline.com'>http://www.s-bisonline.com</a> to see.

		";
	
		$message = nl2br($message);
		emailBlast($from, $fromname, $subject, $message, $emails, $bouncereturn, 0);			
		
		return $this->inMyNetwork($id);
	}
	function removeUser($id){
		if($id == $this->user['id']){
			return false;
		}
		$sql = "delete from `_network` where (
		(`userid1`='".$this->user['id']."' and  `userid2`='".$id."') or
		(`userid2`='".$this->user['id']."' and  `userid1`='".$id."')
		)";
		$this->query($sql);
		return $this->inMyNetwork($id);
	}
	
	function addUser($id){
		if($id == $this->user['id']){
			return false;
		}
		if(!$this->inMyNetwork($id)){
			$sql = "insert into `_network` (`userid1`, `userid2`, `confirmed`) values (
			'".mysql_escape_string($this->user['id'])."',
			'".mysql_escape_string($id)."',
			'2'
			)";
			$this->query($sql);

			$sql = "select * from  `_sbis_users` where `id` = '".mysql_escape_string($this->user['id'])."'";
			$r = $this->query($sql);
			$r = $r[0];
			$sql = "select * from  `_sbis_users` where `id` = '".mysql_escape_string($id)."'";
			$r2 = $this->query($sql);
			$r2 = $r2[0];
			$from = "mailer@s-bisonline.com";
			$fromname = "S-BIS Mailer";
			$bouncereturn = "mailer@s-bisonline.com"; //where the email will forward in cases of bounced email
			$subject = "S-BIS Network Request";
			$emails = array();
			$email = array();
			$email['email'] = $r2['email'];
			$email['name'] = $r2['firstname']." ".$r['lastname'];
			$emails[] = $email;
			$message = "
			Hello ".strtoupper($r2['firstname'])."
	
			".$r['firstname']." ".$r['lastname']." (".$r['email'].") has requested you to be in his network.
			
			Please login to <a href='http://www.s-bisonline.com'>http://www.s-bisonline.com</a> to see.

	
			";
		
			$message = nl2br($message);
			emailBlast($from, $fromname, $subject, $message, $emails, $bouncereturn, 0);			
			
			
			return $this->inMyNetwork($id);
		}
		else{
			return $this->confirmUser($id);
		}
		
	}
	function query($sql){
		return dbQuery($sql, $this->link);
	}
}
?>