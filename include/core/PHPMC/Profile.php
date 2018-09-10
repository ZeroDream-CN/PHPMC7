<?php
class Profile {
	
	public $id;
	public $username;
	public $email;
	public $permission;
	
	public function __construct($username) {
		if(!empty($username) && preg_match("/^[A-Za-z0-9\-\_]+$/", $username)) {
			$db = Config::MySQL();
			$conn = mysqli_connect($db['host'], $db['user'], $db['pass'], $db['name'], $db['port']);
			$rs = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `{$db['name']}`.`users` WHERE `username`='{$username}'"));
			if($rs) {
				$this->id = $rs['id'];
				$this->username = $rs['username'];
				$this->email = $rs['email'];
				$this->permission = $rs['permission'];
			} else {
				$rs = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `{$db['name']}`.`users` WHERE `id`='{$username}'"));
				if($rs) {
					$this->id = $rs['id'];
					$this->username = $rs['username'];
					$this->email = $rs['email'];
					$this->permission = $rs['permission'];
				}
			}
		}
	}
}