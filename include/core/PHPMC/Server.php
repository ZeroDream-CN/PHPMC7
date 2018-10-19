<?php
class Server {
	
	public $server;
	public $id;
	public $name;
	public $daemon;
	public $maxram;
	public $jar;
	public $startcommand;
	public $stopcommand;
	public $owner;
	public $status;
	public $port;
	public $ftppass;
	public $uuid;
	
	/**
	 * 选择要操作的服务器，这里写的很杂
	 *
	 * @param $server 服务器 ID
	 * @param $daemon 服务器所在 Daemon
	 */
	public function setServer($server, $daemon = false) {
		$this->server = $server;
		$db = Config::MySQL();
		$conn = mysqli_connect($db['host'], $db['user'], $db['pass'], $db['name'], $db['port']);
		mysqli_query($conn, "set names 'utf8mb4'");
		// Method 1 通过服务器 ID 查找服务器
		$rs = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `{$db['name']}`.`servers` WHERE `id`='" . $this->server . "'"));
		if($rs) {
			$this->id = $rs['id'];
			$this->name = $rs['name'];
			$this->daemon = $rs['daemon'];
			$this->maxram = $rs['maxram'];
			$this->jar = $rs['jar'];
			$this->startcommand = $rs['startcommand'];
			$this->stopcommand = $rs['stopcommand'];
			$this->owner = $rs['owner'];
			$this->status = $rs['status'];
			$this->port = $rs['port'];
			$this->ftppass = $rs['ftppass'];
			$this->uuid = $rs['uuid'];
		} else {
			// Method 2 通过服务器 UUID 查找服务器
			$rs = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `{$db['name']}`.`servers` WHERE `uuid`='" . $this->server . "'"));
			if($rs) {
				$this->id = $rs['id'];
				$this->name = $rs['name'];
				$this->daemon = $rs['daemon'];
				$this->maxram = $rs['maxram'];
				$this->jar = $rs['jar'];
				$this->startcommand = $rs['startcommand'];
				$this->stopcommand = $rs['stopcommand'];
				$this->owner = $rs['owner'];
				$this->status = $rs['status'];
				$this->port = $rs['port'];
				$this->ftppass = $rs['ftppass'];
				$this->uuid = $rs['uuid'];
			} else {
				// Method 3 通过服务器名字查找服务器
				$rs = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `{$db['name']}`.`servers` WHERE `name`='" . $this->server . "'"));
				if($rs) {
					$this->id = $rs['id'];
					$this->name = $rs['name'];
					$this->daemon = $rs['daemon'];
					$this->maxram = $rs['maxram'];
					$this->jar = $rs['jar'];
					$this->startcommand = $rs['startcommand'];
					$this->stopcommand = $rs['stopcommand'];
					$this->owner = $rs['owner'];
					$this->status = $rs['status'];
					$this->port = $rs['port'];
					$this->ftppass = $rs['ftppass'];
					$this->uuid = $rs['uuid'];
				} else {
					// Method 4 通过服务器端口查找服务器
					if($daemon) {
						$rs = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `{$db['name']}`.`servers` WHERE `port`='" . $this->server . "' AND `daemon`='{$daemon}'"));
						if($rs) {
							$this->id = $rs['id'];
							$this->name = $rs['name'];
							$this->daemon = $rs['daemon'];
							$this->maxram = $rs['maxram'];
							$this->jar = $rs['jar'];
							$this->startcommand = $rs['startcommand'];
							$this->stopcommand = $rs['stopcommand'];
							$this->owner = $rs['owner'];
							$this->status = $rs['status'];
							$this->port = $rs['port'];
							$this->ftppass = $rs['ftppass'];
							$this->uuid = $rs['uuid'];
						} else {
							// 未找到任何数据，返回 null
							$this->id = null;
							$this->name = null;
							$this->daemon = null;
							$this->maxram = null;
							$this->jar = null;
							$this->startcommand = null;
							$this->stopcommand = null;
							$this->owner = null;
							$this->status = null;
							$this->port = null;
							$this->ftppass = null;
							$this->uuid = null;
						}
					} else {
						// 未找到任何数据，返回 null
						$this->id = null;
						$this->name = null;
						$this->daemon = null;
						$this->maxram = null;
						$this->jar = null;
						$this->startcommand = null;
						$this->stopcommand = null;
						$this->owner = null;
						$this->status = null;
						$this->port = null;
						$this->ftppass = null;
						$this->uuid = null;
					}
				}
			}
		}
	}
	
	/**
	 * 取消选择服务器
	 */
	public function unselectServer() {
		$this->id = null;
		$this->name = null;
		$this->daemon = null;
		$this->maxram = null;
		$this->jar = null;
		$this->startcommand = null;
		$this->stopcommand = null;
		$this->owner = null;
		$this->status = null;
		$this->port = null;
		$this->ftppass = null;
		$this->uuid = null;
	}
	
	/**
	 * 在数据库中创建新的服务器
	 *
	 * @param $name 		服务器显示名称
	 * @param $daemon 		服务器所在的 Daemon
	 * @param $maxram		服务器最大内存
	 * @param $jar			服务器核心 Jar 名称
	 * @param $startcommand	服务器启动命令
	 * @param $stopcommand	服务器停止命令
	 * @param $owner		服务器所有者用户 ID
	 * @param $status		服务器状态
	 * @param $port			服务器端口
	 * @param $ftppass		服务器 FTP 密码
	 * @return Boolean		创建状态
	 */
	public function createServer($name, $daemon, $maxram, $jar, $startcommand, $stopcommand, $owner, $status, $port, $ftppass) {
		$uuid = md5(uniqid(rand(0, 10000000), TRUE));
		$db = Config::MySQL();
		$conn = mysqli_connect($db['host'], $db['user'], $db['pass'], $db['name'], $db['port']);
		mysqli_query($conn, "set names 'utf8mb4'");
		mysqli_query($conn, "INSERT INTO `{$db['name']}`.`servers` (`id`, `name`, `daemon`, `maxram`, `jar`, `startcommand`, `stopcommand`, `owner`, `status`, `port`, `uuid`, `ftppass`) "
			. "VALUES (NULL, '{$name}', '{$daemon}', '{$maxram}', '{$jar}', '{$startcommand}', '{$stopcommand}', '{$owner}', '{$status}', '{$port}', '{$uuid}', '{$ftppass}')");
		$this->setServer($uuid);
		$this->Init();
		return true;
	}
	
	/**
	 * 更新数据库中的服务器数据
	 *
	 * @param $id			服务器 ID
	 * @param $name 		服务器显示名称
	 * @param $maxram		服务器最大内存
	 * @param $jar			服务器核心 Jar 名称
	 * @param $startcommand	服务器启动命令
	 * @param $stopcommand	服务器停止命令
	 * @param $owner		服务器所有者用户 ID
	 * @param $status		服务器状态
	 * @param $port			服务器端口
	 * @param $ftppass		服务器 FTP 密码
	 * @return Boolean		更新状态
	 */
	public function updateServer($id, $name, $maxram, $jar, $startcommand, $stopcommand, $owner, $status, $port, $ftppass) {
		$db = Config::MySQL();
		$conn = mysqli_connect($db['host'], $db['user'], $db['pass'], $db['name'], $db['port']);
		mysqli_query($conn, "set names 'utf8mb4'");
		mysqli_query($conn, "UPDATE `{$db['name']}`.`servers` SET `name`='{$name}', `maxram`='{$maxram}', `jar`='{$jar}', `startcommand`='{$startcommand}', "
			."`stopcommand`='{$stopcommand}', `owner`='{$owner}', `status`='{$status}', `port`='{$port}', `ftppass`='{$ftppass}' WHERE `id`='{$id}'");
		return true;
	}
	
	/**
	 * 删除数据库中的服务器以及所有数据
	 *
	 * @param $id		服务器 ID
	 * @return Boolean	删除状态
	 */
	public function deleteServer($id) {
		$this->setServer($id);
		$db = Config::MySQL();
		$conn = mysqli_connect($db['host'], $db['user'], $db['pass'], $db['name'], $db['port']);
		mysqli_query($conn, "set names 'utf8mb4'");
		$Daemon = new Daemon();
		if($Daemon->setDaemon($this->daemon) == null) {
			return false;
		}
		$this->sendCommand($this->stopcommand);
		sleep(1);
		$Http = new Http();
		$result = $Http->Request($Daemon->host . "?action=file-exist&name=" . urlencode("data/" . $this->uuid) . "&token=" . md5($Daemon->pass));
		echo "Delete file: {$result}<br>";
		if($result == 'true') {
			if($Daemon->type == "linux") {
				$this->sendCommand("cd ../");
				$this->sendCommand("rm -rf " . $this->uuid);
			} else {
				$this->sendCommand("cd ../");
				$this->sendCommand("rmdir /s/q " . $this->uuid);
			}
		}
		sleep(1);
		$this->close();
		mysqli_query($conn, "DELETE FROM `{$db['name']}`.`servers` WHERE `id`='{$id}'");
		return true;
	}
	
	/**
	 * 判断服务器是否已初始化
	 *
	 * @return Boolean 初始化状态
	 */
	public function isCreated() {
		if(empty($this->server)) {
			return false;
		}
		$Daemon = new Daemon();
		if($Daemon->setDaemon($this->daemon) == null) {
			return false;
		}
		$Http = new Http();
		$result = $Http->Request($Daemon->host . "?action=exist&name=" . $this->uuid . "&token=" . md5($Daemon->pass));
		return $result == 'true' ? true : false;
	}
	
	/**
	 * 初始化服务器通讯管道
	 *
	 * @return String/Boolean 返回执行结果
	 */
	public function Init() {
		if(empty($this->server)) {
			return false;
		}
		$Daemon = new Daemon();
		if($Daemon->setDaemon($this->daemon) == null) {
			return false;
		}
		if($this->isCreated()) {
			return false;
		}
		$Daemon->setUser(mb_substr($this->uuid, 0, 8), $this->ftppass, "data/" . $this->uuid);
		$Http = new Http();
		return $Http->Request($Daemon->host . "?action=create&name=" . $this->uuid . "&token=" . md5($Daemon->pass));
	}
	
	/**
	 * 向服务器发送命令
	 *
	 * @param $cmd 需要执行的命令
	 * @return String/Boolean 返回执行结果
	 */
	public function sendCommand($cmd) {
		if(empty($this->server)) {
			return false;
		}
		$Daemon = new Daemon();
		if($Daemon->setDaemon($this->daemon) == null) {
			return false;
		}
		if(!$this->isCreated()) {
			return false;
		}
		$Http = new Http();
		return $Http->Request($Daemon->host . "?action=command&name=" . $this->uuid . "&token=" . md5($Daemon->pass) . "&cmd=" . urlencode($cmd));
	}
	
	/**
	 * 关闭服务器的管道
	 *
	 * @return String/Boolean 返回执行结果
	 */
	public function close() {
		if(empty($this->server)) {
			return false;
		}
		$Daemon = new Daemon();
		if($Daemon->setDaemon($this->daemon) == null) {
			return false;
		}
		if(!$this->isCreated()) {
			return false;
		}
		$Http = new Http();
		return $Http->Request($Daemon->host . "?action=close&name=" . $this->uuid . "&token=" . md5($Daemon->pass));
	}
	
	/**
	 * 获得服务器日志输出 Token
	 *
	 * @return String/Boolean 返回 Token
	 */
	public function getToken() {
		if(empty($this->server)) {
			return false;
		}
		$Daemon = new Daemon();
		if($Daemon->setDaemon($this->daemon) == null) {
			return false;
		}
		return md5($Daemon->pass . $this->uuid);
	}
	
	/**
	 * 获得数据库中的服务器数量
	 *
	 * @return Int 服务器总数
	 */
	public function getCounts() {
		$db = Config::MySQL();
		$conn = mysqli_connect($db['host'], $db['user'], $db['pass'], $db['name'], $db['port']);
		mysqli_query($conn, "set names 'utf8mb4'");
		$rs = mysqli_query($conn, "SELECT * FROM `{$db['name']}`.`servers`");
		$i = 0;
		while($rw = mysqli_fetch_row($rs)) {
			$i++;
		}
		mysqli_close($conn);
		return $i;
	}
	
	/**
	 * 获得数据库中指定 Daemon 的服务器数量
	 *
	 * @return Int 服务器总数
	 */
	public function getCountsByDaemon($id) {
		$db = Config::MySQL();
		$conn = mysqli_connect($db['host'], $db['user'], $db['pass'], $db['name'], $db['port']);
		mysqli_query($conn, "set names 'utf8mb4'");
		$rs = mysqli_query($conn, "SELECT * FROM `{$db['name']}`.`servers` WHERE `daemon`='{$id}'");
		$i = 0;
		while($rw = mysqli_fetch_row($rs)) {
			$i++;
		}
		mysqli_close($conn);
		return $i;
	}
	
	/**
	 * 获得数据库中指定用户的服务器数量
	 *
	 * @return Int 服务器总数
	 */
	public function getCountsByOwner($id) {
		$db = Config::MySQL();
		$conn = mysqli_connect($db['host'], $db['user'], $db['pass'], $db['name'], $db['port']);
		mysqli_query($conn, "set names 'utf8mb4'");
		$rs = mysqli_query($conn, "SELECT * FROM `{$db['name']}`.`servers` WHERE `owner`='{$id}'");
		$i = 0;
		while($rw = mysqli_fetch_row($rs)) {
			$i++;
		}
		mysqli_close($conn);
		return $i;
	}
	
	/**
	 * 输出用户可管理的服务器列表
	 *
	 * @return String 服务器列表
	 */
	public function getServerList() {
		$db = Config::MySQL();
		$conn = mysqli_connect($db['host'], $db['user'], $db['pass'], $db['name'], $db['port']);
		mysqli_query($conn, "set names 'utf8mb4'");
		$User = new User();
		$Profile = $User->getLoginUser();
		$ownerid = $Profile->id;
		$rs = mysqli_query($conn, "SELECT * FROM `{$db['name']}`.`servers`");
		$data = "";
		while($rw = mysqli_fetch_row($rs)) {
			if(PHPMC::Permission()->check("server:" . $rw[0]) || $rw[7] == $ownerid) {
				$Daemon = new Daemon();
				if($Daemon->setDaemon($rw[2]) == null) {
					PHPMC::Error()->Println("500 Server Internal Error");
				}
				$data .= "<div class='server-hover' onclick='selectServer({$rw[0]}, this)'>
					<h5>{$rw[1]}</h5>
					<p>" . $Daemon->fqdn . ":{$rw[9]}</p>
				</div>";
			}
		}
		mysqli_close($conn);
		return $data;
	}
	
	/**
	 * 输出管理服务器列表
	 *
	 * @return String 服务器列表
	 */
	public function getServerListAdmin() {
		$db = Config::MySQL();
		$conn = mysqli_connect($db['host'], $db['user'], $db['pass'], $db['name'], $db['port']);
		mysqli_query($conn, "set names 'utf8mb4'");
		$User = new User();
		$Profile = $User->getLoginUser();
		$ownerid = $Profile->id;
		$rs = mysqli_query($conn, "SELECT * FROM `{$db['name']}`.`servers`");
		$data = "";
		while($rw = mysqli_fetch_row($rs)) {
			$Daemon = new Daemon();
			if($Daemon->setDaemon($rw[2]) == null) {
				PHPMC::Error()->Println("500 Server Internal Error");
			}
			$Profile = new Profile($rw[7]);
			$data .= "<div class='server-hover' onclick='selectServer({$rw[0]}, this)'>
				<h5>ID：{$rw[0]} 名称：{$rw[1]}</h5>
				<p>" . $Daemon->fqdn . ":{$rw[9]} | 所有者：" . $Profile->username . "</p>
			</div>";
		}
		mysqli_close($conn);
		return $data;
	}
}