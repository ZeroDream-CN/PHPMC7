<?php
class Csrf {
	
	public function isemptyCsrfToken() {
		SESSION_START();
		return $_SESSION['token'] == "";
	}
	
	public function createCsrfToken() {
		SESSION_START();
		$_SESSION['token'] = md5(uniqid(rand(0, 10000000), TRUE));
	}
	
	public function verifyCsrfToken($data) {
		SESSION_START();
		if(empty($_SESSION['token'])) {
			return false;
		}
		if(empty($data['csrf_token'])) {
			return false;
		}
		return $data['csrf_token'] == $_SESSION['token'];
	}
}