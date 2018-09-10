<?php
class Option {
	
	public $options;
	
	public function __construct() {
		$db = Config::MySQL();
		$conn = mysqli_connect($db['host'], $db['user'], $db['pass'], $db['name'], $db['port']);
		$rs = mysqli_query($conn, "SELECT * FROM `{$db['name']}`.`option`");
		while($rw = mysqli_fetch_row($rs)) {
			$this->options[$rw[1]] = $rw[2];
		}
	}
	
	public function getOption($name) {
		if(preg_match("/^[A-Za-z0-9\-\_]+$/", $name)) {
			if(isset($this->options[$name])) {
				return $this->options[$name];
			} else {
				return null;
			}
		}
	}
	
	public function saveConfig($SiteName, $Description, $Theme) {
		$db = Config::MySQL();
		$conn = mysqli_connect($db['host'], $db['user'], $db['pass'], $db['name'], $db['port']);
		mysqli_query($conn, "UPDATE `{$db['name']}`.`option` SET `value`='{$SiteName}' WHERE `key`='SiteName'");
		mysqli_query($conn, "UPDATE `{$db['name']}`.`option` SET `value`='{$Description}' WHERE `key`='Description'");
		mysqli_query($conn, "UPDATE `{$db['name']}`.`option` SET `value`='{$Theme}' WHERE `key`='Theme'");
		return true;
	}
	
	public function updateOption($key, $value) {
		$db = Config::MySQL();
		$conn = mysqli_connect($db['host'], $db['user'], $db['pass'], $db['name'], $db['port']);
		mysqli_query($conn, "UPDATE `{$db['name']}`.`option` SET `value`='{$value}' WHERE `key`='{$key}'");
		return true;
	}
}