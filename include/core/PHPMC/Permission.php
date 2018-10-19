<?php
class Permission {
	
	public function check($permission, $user = false) {
		switch($permission) {
			case 'page:login':
				return true;
				break;
			case 'page:404':
				return true;
				break;
			case 'page:403':
				return true;
				break;
			case 'action:login':
				return true;
				break;
			default:
				if(!$user) {
					if(!PHPMC::User()->isLogin()) {
						return false;
					} else {
						$user = PHPMC::User()->getLoginUser();
					}
				}
				if(stristr($user->permission, $permission . ";")) {
					return true;
				} else {
					if(stristr($user->permission, "admin;")) {
						return true;
					} elseif(stristr($permission, "server:")) {
						$exp = explode(":", $permission);
						return $this->serverControlPerm($user->permission, $exp[1]);
					} else {
						return false;
					}
				}
		}
	}
	
	public function checkSession($permission) {
		if(!$this->check($permission)) {
			$Option = new Option();
			$Loader = new Loader();
			echo $this->check($permission);//$Loader->loadPage("403.html", ROOT . "/content/" . $Option->getOption("Theme") . "/error/");
			exit;
		}
	}
	
	/**
	 *
	 * 服务器权限检测
	 *
	 * @param $permission	用户权限
	 * @param $server		服务器 ID
	 * @return Boolean		是否拥有权限
	 *
	 */
	public function serverControlPerm($permission, $server) {
		$gettag = explode(";", $permission);
		$s = 0;
		$User = new User();
		$Profile = $User->getLoginUser();
		$Server = new Server();
		$Server->setServer($server);
		if($Server->uuid == null) {
			return false;
		}
		if($Server->owner == $Profile->id) {
			return true;
		} else {
			for($i = 0;$i < count($gettag);$i++) {
				$getkey = explode(":", $gettag[$i]);
				if($getkey[0] == "server") {
					if($getkey[1] == $server) {
						return true;
					}
				}
			}
		}
		return false;
	}
}