<?php
class Event {
	
	public function LoginEvent($Data) {
		if(preg_match("/^[A-Za-z0-9\-\_]+$/", $Data['username'])) {
			if(PHPMC::User()->Login($Data['username'], $Data['password'])) {
				SESSION_START();
				$_SESSION['user'] = $Data['username'];
				echo "Successful";
				PHPMC::Csrf()->createCsrfToken();
				exit;
			} else {
				$Option = new Option();
				$newValue = Intval($Option->getOption('loginFailed')) + 1;
				$Option->updateOption("loginFailed", $newValue);
				PHPMC::Error()->Println("用户名或密码错误！");
			}
		}
	}
	
	public function LogoutEvent() {
		if(PHPMC::User()->isLogin()) {
			PHPMC::User()->Logout();
		}
		echo "<script>location='./';</script>";
	}
	
	public function getServerEvent($data) {
		if(isset($data['id']) && preg_match("/^[0-9]+$/", $data['id'])) {
			$Server = new Server();
			$Server->setServer($data['id']);
			if(empty($Server->uuid)) {
				PHPMC::Error()->Println("404 Not Found");
			}
			$Daemon = new Daemon();
			if($Daemon->setDaemon($Server->daemon) == null) {
				PHPMC::Error()->Println("500 Server Internal Error");
			}
			$info = Array(
				'id' => $Server->id,
				'name' => $Server->name,
				'uuid' => $Server->uuid,
				'daemon' => $Server->daemon,
				'host' => $Daemon->host,
				'gamehost' => $Daemon->fqdn . ":" . $Server->port,
				'ftpuser' => mb_substr($Server->uuid, 0, 8),
				'ftppass' => $Server->ftppass,
				'token' => $Server->getToken()
			);
			echo json_encode($info);
		} else {
			$Loader = new Loader();
			$Option = new Option();
			echo $Loader->loadPage("404.html", ROOT . "/content/" . $Option->getOption("Theme") . "/error/");
		}
	}
	
	public function getServerInfoEvent($data) {
		if(isset($data['id']) && preg_match("/^[0-9]+$/", $data['id'])) {
			$Server = new Server();
			$Server->setServer($data['id']);
			if(empty($Server->uuid)) {
				PHPMC::Error()->Println("404 Not Found");
			}
			$info = Array(
				'id' => $Server->id,
				'name' => $Server->name,
				'maxram' => $Server->maxram,
				'jar' => $Server->jar,
				'startcommand' => $Server->startcommand,
				'stopcommand' => $Server->stopcommand,
				'port' => $Server->port,
				'ftppass' => $Server->ftppass,
				'owner' => $Server->owner
			);
			echo json_encode($info);
		} else {
			$Loader = new Loader();
			$Option = new Option();
			echo $Loader->loadPage("404.html", ROOT . "/content/" . $Option->getOption("Theme") . "/error/");
		}
	}
	
	public function getDaemonInfoEvent($data) {
		if(isset($data['id']) && preg_match("/^[0-9]+$/", $data['id'])) {
			$Daemon = new Daemon();
			$Daemon->setDaemon($data['id']);
			if(empty($Daemon->name)) {
				PHPMC::Error()->Println("404 Not Found");
			}
			$info = Array(
				'id' => $Daemon->id,
				'name' => $Daemon->name,
				'host' => $Daemon->host,
				'pass' => $Daemon->pass,
				'fqdn' => $Daemon->fqdn,
				'type' => $Daemon->type
			);
			echo json_encode($info);
		} else {
			$Loader = new Loader();
			$Option = new Option();
			echo $Loader->loadPage("404.html", ROOT . "/content/" . $Option->getOption("Theme") . "/error/");
		}
	}
	
	public function getUserInfoEvent($data) {
		if(isset($data['id']) && preg_match("/^[0-9]+$/", $data['id'])) {
			$Profile = new Profile($data['id']);
			if(empty($Profile->username)) {
				PHPMC::Error()->Println("404 Not Found");
			}
			$info = Array(
				'id' => $Profile->id,
				'username' => $Profile->username,
				'email' => $Profile->email,
				'permission' => $Profile->permission
			);
			echo json_encode($info);
		} else {
			$Loader = new Loader();
			$Option = new Option();
			echo $Loader->loadPage("404.html", ROOT . "/content/" . $Option->getOption("Theme") . "/error/");
		}
	}
	
	public function startServerEvent($data) {
		if(isset($data['id']) && preg_match("/^[0-9]+$/", $data['id'])) {
			$Server = new Server();
			$Server->setServer($data['id']);
			if(empty($Server->uuid)) {
				PHPMC::Error()->Println("404 Not Found");
			}
			$Server->Init();
			$startCommand = $Server->startcommand;
			$startCommand = str_replace("{maxram}", $Server->maxram, $startCommand);
			$startCommand = str_replace("{jar}", $Server->jar, $startCommand);
			$Server->sendCommand($startCommand);
			echo "Successful";
		} else {
			echo "Not Found";
			exit;
		}
	}
	
	public function stopServerEvent($data) {
		if(isset($data['id']) && preg_match("/^[0-9]+$/", $data['id'])) {
			$Server = new Server();
			$Server->setServer($data['id']);
			if(empty($Server->uuid)) {
				PHPMC::Error()->Println("404 Not Found");
			}
			$stopcommand = $Server->stopcommand;
			$Server->sendCommand($stopcommand);
			echo "Successful";
		} else {
			echo "Not Found";
			exit;
		}
	}
	
	public function restartServerEvent($data) {
		if(isset($data['id']) && preg_match("/^[0-9]+$/", $data['id'])) {
			$Server = new Server();
			$Server->setServer($data['id']);
			if(empty($Server->uuid)) {
				PHPMC::Error()->Println("404 Not Found");
			}
			$stopcommand = $Server->stopcommand;
			$Server->sendCommand($stopcommand);
			sleep(5);
			$startCommand = $Server->startcommand;
			$startCommand = str_replace("{maxram}", $Server->maxram, $startCommand);
			$startCommand = str_replace("{jar}", $Server->jar, $startCommand);
			$Server->sendCommand($startCommand);
			echo "Successful";
		} else {
			echo "Not Found";
			exit;
		}
	}
	
	public function onCommandEvent($data) {
		if(isset($data['id']) && preg_match("/^[0-9]+$/", $data['id'])) {
			$Server = new Server();
			$Server->setServer($data['id']);
			if(empty($Server->uuid)) {
				PHPMC::Error()->Println("404 Not Found");
			}
			if(empty($data['cmd'])) {
				PHPMC::Error()->Println("命令内容不能为空。");
			}
			$Server->Init();
			$Server->sendCommand($data['cmd']);
			echo "Successful";
		} else {
			echo "Not Found";
			exit;
		}
	}
	
	public function getStatusEvent($data) {
		if(isset($data['id']) && preg_match("/^[0-9]+$/", $data['id'])) {
			$Server = new Server();
			$Server->setServer($data['id']);
			if(empty($Server->uuid)) {
				PHPMC::Error()->Println("404 Not Found");
			}
			$Daemon = new Daemon();
			if($Daemon->setDaemon($Server->daemon) == null) {
				PHPMC::Error()->Println("500 Server Internal Error");
			}
			$Utils = new Utils();
			$sinfo = $Utils->Query($Daemon->fqdn, $Server->port);
			echo $sinfo['online'] . "/" . $sinfo['max'] . "/" . $Daemon->fqdn . ":" . $Server->port;
			exit;
		} else {
			echo "/";
			exit;
		}
	}
	
	public function createServerEvent($data) {
		if(!preg_match("/^[0-9]+$/", $data['daemon'])) {
			PHPMC::Error()->Println("请填写字段：Daemon");
		}
		if(!preg_match("/^[A-Za-z0-9\-\_ ]+$/", $data['name'])) {
			PHPMC::Error()->Println("请填写字段：服务器名称，只能包含英文大小写、数字、下划线和 -"); 
		}
		if(!preg_match("/^[0-9]+$/", $data['maxram'])) {
			PHPMC::Error()->Println("请填写字段：最大内存");
		}
		if(!preg_match("/^[A-Za-z0-9\-\_\.]+$/", $data['jar'])) {
			PHPMC::Error()->Println("请填写字段：核心文件名字，只能包含英文大小写、数字、_、. 和 -");
		}
		if(!preg_match("/^[A-Za-z0-9\-\_\.\{\} ]+$/", $data['startcommand'])) {
			PHPMC::Error()->Println("请填写字段：核心启动命令");
		}
		if(!preg_match("/^[A-Za-z0-9\-\_\.\{\} ]+$/", $data['stopcommand'])) {
			PHPMC::Error()->Println("请填写字段：停止命令");
		}
		if(!preg_match("/^[0-9]+$/", $data['port'])) {
			PHPMC::Error()->Println("请填写字段：服务器端口");
		}
		if(!preg_match("/^[A-Za-z0-9\-\_ ]+$/", $data['ftppass'])) {
			PHPMC::Error()->Println("请填写字段：服务器 FTP 密码，只能包含英文大小写、数字、下划线和 -"); 
		}
		if(!preg_match("/^[0-9]+$/", $data['owner'])) {
			PHPMC::Error()->Println("请填写字段：服务器所有者");
		}
		$Server = new Server();
		$Server->setServer($data['name']);
		if($Server->uuid !== null) {
			PHPMC::Error()->Println("相同名字的服务器已经存在。");
		}
		$Server->unselectServer();
		$Server->setServer($data['port'], $data['daemon']);
		if($Server->uuid !== null) {
			PHPMC::Error()->Println("相同端口、相同 Daemon 的服务器已经存在。");
		}
		$Daemon = new Daemon();
		if($Daemon->setDaemon($data['daemon']) == null) {
			PHPMC::Error()->Println("Daemon 不存在，请检查参数是否正确。");
		}
		PHPMC::Server()->createServer($data['name'], $data['daemon'], $data['maxram'], 
			$data['jar'], $data['startcommand'], $data['stopcommand'], $data['owner'], "normal", $data['port'], $data['ftppass']);
		echo "服务器创建成功！";
		exit;
	}
	
	public function updateServerEvent($data) {
		if(!preg_match("/^[0-9]+$/", $data['id'])) {
			PHPMC::Error()->Println("请填写字段：服务器 ID");
		}
		if(!preg_match("/^[A-Za-z0-9\-\_ ]+$/", $data['name'])) {
			PHPMC::Error()->Println("请填写字段：服务器名称，只能包含英文大小写、空格、数字、下划线和 -"); 
		}
		if(!preg_match("/^[0-9]+$/", $data['maxram'])) {
			PHPMC::Error()->Println("请填写字段：最大内存");
		}
		if(!preg_match("/^[A-Za-z0-9\-\_\.]+$/", $data['jar'])) {
			PHPMC::Error()->Println("请填写字段：核心文件名字，只能包含英文大小写、数字、_、. 和 -");
		}
		if(!preg_match("/^[A-Za-z0-9\-\_\.\{\} ]+$/", $data['startcommand'])) {
			PHPMC::Error()->Println("请填写字段：核心启动命令");
		}
		if(!preg_match("/^[A-Za-z0-9\-\_\.\{\} ]+$/", $data['stopcommand'])) {
			PHPMC::Error()->Println("请填写字段：停止命令");
		}
		if(!preg_match("/^[0-9]+$/", $data['port'])) {
			PHPMC::Error()->Println("请填写字段：服务器端口");
		}
		if(!preg_match("/^[A-Za-z0-9\-\_ ]+$/", $data['ftppass'])) {
			PHPMC::Error()->Println("请填写字段：服务器 FTP 密码，只能包含英文大小写、数字、下划线和 -"); 
		}
		if(!preg_match("/^[0-9]+$/", $data['owner'])) {
			PHPMC::Error()->Println("请填写字段：服务器所有者");
		}
		$Server = new Server();
		$Server2 = new Server();
		$Server->setServer($data['id']);
		if($Server->uuid == null) {
			PHPMC::Error()->Println("Server Not Found");
		}
		$Server2->setServer($data['name']);
		if($Server2->uuid !== null) {
			PHPMC::Error()->Println("相同名字的服务器已经存在。");
		}
		$Server2->unselectServer();
		$Server2->setServer($data['port'], $Server->daemon);
		if($Server->uuid !== null) {
			PHPMC::Error()->Println("相同端口、相同 Daemon 的服务器已经存在。");
		}
		PHPMC::Server()->updateServer($data['id'], $data['name'], $data['maxram'], $data['jar'], $data['startcommand'], 
			$data['stopcommand'], $data['owner'], "normal", $data['port'], $data['ftppass']);
		echo "服务器设置更改成功，您需要刷新网页后设置才会生效。";
		exit;
	}
	
	public function deleteServerEvent($data) {
		if(!preg_match("/^[0-9]+$/", $data['id'])) {
			PHPMC::Error()->Println("请填写字段：服务器 ID");
		}
		PHPMC::Server()->deleteServer($data['id']);
		echo "服务器删除成功！";
		exit;
	}
	
	public function createDaemonEvent($data) {
		if(!preg_match("/^[A-Za-z0-9\-\_ ]+$/", $data['name'])) {
			PHPMC::Error()->Println("请填写字段：Daemon 名称");
		}
		if(!preg_match('/^^((https|http)?:\/\/)[^\s]+$/', $data['host'])) {
			PHPMC::Error()->Println("请填写字段：AJAX 请求地址"); 
		}
		if(!preg_match("/^[A-Za-z0-9\-\_ ]+$/", $data['pass'])) {
			PHPMC::Error()->Println("请填写字段：Daemon 密码，只能包含英文大小写、数字、下划线和 -");
		}
		if(!preg_match("/^[A-Za-z0-9\-\_\.]+$/", $data['fqdn'])) {
			PHPMC::Error()->Println("请填写字段：域名或 IP 地址");
		}
		if(!preg_match("/^[a-z]+$/", $data['type'])) {
			PHPMC::Error()->Println("请填写字段：服务器操作系统类型");
		}
		PHPMC::Daemon()->createDaemon($data['name'], $data['host'], $data['pass'], $data['fqdn'], $data['type']);
		echo "Daemon 创建成功！";
		exit;
	}
	
	public function updateDaemonEvent($data) {
		if(!preg_match("/^[0-9]+$/", $data['id'])) {
			PHPMC::Error()->Println("请填写字段：Daemon ID");
		}
		if(!preg_match("/^[A-Za-z0-9\-\_ ]+$/", $data['name'])) {
			PHPMC::Error()->Println("请填写字段：Daemon 名称");
		}
		if(!preg_match('/^^((https|http)?:\/\/)[^\s]+$/', $data['host'])) {
			PHPMC::Error()->Println("请填写字段：AJAX 请求地址"); 
		}
		if(!preg_match("/^[A-Za-z0-9\-\_ ]+$/", $data['pass'])) {
			PHPMC::Error()->Println("请填写字段：Daemon 密码，只能包含英文大小写、数字、下划线和 -");
		}
		if(!preg_match("/^[A-Za-z0-9\-\_\.]+$/", $data['fqdn'])) {
			PHPMC::Error()->Println("请填写字段：域名或 IP 地址");
		}
		if(!preg_match("/^[a-z]+$/", $data['type'])) {
			PHPMC::Error()->Println("请填写字段：服务器操作系统类型");
		}
		$Daemon = new Daemon();
		if($Daemon->setDaemon($data['id']) == null) {
			PHPMC::Error()->Println("Daemon Not Found");
		}
		PHPMC::Daemon()->updateDaemon($data['id'], $data['name'], $data['host'], $data['pass'], $data['fqdn'], $data['type']);
		echo "Daemon 设置更改成功，您需要刷新网页后设置才会生效。";
		exit;
	}
	
	public function deleteDaemonEvent($data) {
		if(!preg_match("/^[0-9]+$/", $data['id'])) {
			PHPMC::Error()->Println("请填写字段：Daemon ID");
		}
		$Daemon = new Daemon();
		if($Daemon->setDaemon($data['id']) == null) {
			PHPMC::Error()->Println("Daemon Not Found");
		}
		if(PHPMC::Server()->getCountsByDaemon($data['id']) > 0) {
			PHPMC::Error()->Println("当前 Daemon 中有服务器，请删除服务器后再删除 Daemon。");
		}
		PHPMC::Daemon()->deleteDaemon($data['id']);
		echo "Daemon 删除成功！";
		exit;
	}
	
	public function createUserEvent($data) {
		if(!preg_match("/^[A-Za-z0-9\-\_]+$/", $data['username'])) {
			PHPMC::Error()->Println("请填写字段：用户名");
		}
		if(empty($data['password'])) {
			PHPMC::Error()->Println("请填写字段：用户密码"); 
		}
		if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
			PHPMC::Error()->Println("请填写字段：用户邮箱");
		}
		if(!preg_match("/^[A-Za-z0-9\_\-\;\:]+$/", $data['permission'])) {
			PHPMC::Error()->Println("请填写字段：用户权限");
		}
		$Profile = new Profile($data['username']);
		if($Profile->username == $data['username'] && $Profile->id !== $data['id']) {
			PHPMC::Error()->Println("此用户名已经存在。");
		}
		$Profile = new Profile($data['email']);
		if($Profile->email == $data['email'] && $Profile->id !== $data['id']) {
			PHPMC::Error()->Println("此邮箱已经存在。");
		}
		$password = password_hash(md5($data['password']), PASSWORD_BCRYPT);
		PHPMC::User()->createUser($data['username'], $password, $data['email'], $data['permission']);
		echo "用户创建成功！";
		exit;
	}
	
	public function updateUserEvent($data) {
		if(!preg_match("/^[0-9]+$/", $data['id'])) {
			PHPMC::Error()->Println("请填写字段：用户 ID");
		}
		if(!preg_match("/^[A-Za-z0-9\-\_]+$/", $data['username'])) {
			PHPMC::Error()->Println("请填写字段：用户名");
		}
		if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
			PHPMC::Error()->Println("请填写字段：用户邮箱");
		}
		if(!preg_match("/^[A-Za-z0-9\_\-\;\:]+$/", $data['permission'])) {
			PHPMC::Error()->Println("请填写字段：用户权限");
		}
		$Profile = new Profile($data['id']);
		if($Profile->username == null) {
			PHPMC::Error()->Println("User Not Found");
		}
		$Profile = new Profile($data['username']);
		if($Profile->username == $data['username'] && $Profile->id !== $data['id']) {
			PHPMC::Error()->Println("此用户名已经存在。");
		}
		$Profile = new Profile($data['email']);
		if($Profile->email == $data['email'] && $Profile->id !== $data['id']) {
			PHPMC::Error()->Println("此邮箱已经存在。");
		}
		if(empty($data['password'])) {
			PHPMC::User()->updateUser($data['id'], $data['username'], false, $data['email'], $data['permission']);
		} else {
			$password = password_hash(md5($data['password']), PASSWORD_BCRYPT);
			PHPMC::User()->updateUser($data['id'], $data['username'], $password, $data['email'], $data['permission']);
		}
		echo "用户设置更改成功，您需要刷新网页后设置才会生效。";
		exit;
	}
	
	public function deleteUserEvent($data) {
		if(!preg_match("/^[0-9]+$/", $data['id'])) {
			PHPMC::Error()->Println("请填写字段：用户 ID");
		}
		$Profile = new Profile($data['id']);
		if($Profile->username == null) {
			PHPMC::Error()->Println("User Not Found");
		}
		if(PHPMC::Server()->getCountsByOwner($data['id']) > 0) {
			PHPMC::Error()->Println("当前用户还拥有服务器，请删除服务器后再删除用户。");
		}
		if($data['id'] == "1") {
			PHPMC::Error()->Println("此用户为超级管理员用户，无法删除。");
		}
		PHPMC::User()->deleteUser($data['id']);
		echo "用户删除成功！";
		exit;
	}
	
	public function saveConfigEvent($data) {
		if(!preg_match("/^[a-zA-Z0-9_\x7f-\xff ]+$/", $data['SiteName'])) {
			PHPMC::Error()->Println("请填写字段：站点名称");
		}
		if(!preg_match("/^[a-zA-Z0-9_\x7f-\xff ]+$/", $data['Description'])) {
			PHPMC::Error()->Println("请填写字段：站点简介");
		}
		if(!preg_match("/^[a-zA-Z0-9\-\_]+$/", $data['Theme'])) {
			PHPMC::Error()->Println("请填写字段：系统主题");
		}
		PHPMC::Option()->saveConfig($data['SiteName'], $data['Description'], $data['Theme']);
		echo "系统设置更改成功，您需要刷新网页后设置才会生效。";
		exit;
	}
	
	public function updateEvent() {
		if(PHPMC::Update()->checkUpdate()) {
			PHPMC::Update()->updateExecute();
		}
	}
}