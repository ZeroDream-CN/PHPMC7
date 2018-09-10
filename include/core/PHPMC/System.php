<?php
class System {
	public function get_PHP_Version() {
		return PHP_VERSION;
	}
	
	public function get_php_sapi_name() {
		return php_sapi_name();
	}
	
	public function get_php_os() {
		return php_uname('s') . php_uname('r');
	}
	
	public function get_server_software() {
		return $_SERVER['SERVER_SOFTWARE'];
	}
	
	public function get_phpmc_version() {
		return PHPMC_VERSION;
	}
}