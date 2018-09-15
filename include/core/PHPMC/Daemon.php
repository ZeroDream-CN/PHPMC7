<?php
class Daemon {
	
	public $daemon;
	public $id;
	public $name;
	public $host;
	public $pass;
	public $type;
	public $fqdn;
	
	/**
	 * 选择要操作的 Daemon
	 *
	 * @param $daemon Daemon ID
	 * @return Boolean 返回执行结果
	 */
	public function setDaemon($daemon) {
		if(!empty($daemon)) {
			$this->daemon = $daemon;
			$db = Config::MySQL();
			$conn = mysqli_connect($db['host'], $db['user'], $db['pass'], $db['name'], $db['port']);
			$rs = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `{$db['name']}`.`daemon` WHERE `id`='{$daemon}'"));
			if($rs) {
				$this->id = $rs['id'];
				$this->name = $rs['name'];
				$this->host = $rs['host'];
				$this->pass = $rs['pass'];
				$this->type = $rs['type'];
				$this->fqdn = $rs['fqdn'];
				return true;
			} else {
				return null;
			}
		}
	}
	
	/**
	 * 设置 FTP 用户信息
	 *
	 * @param $user 用户名
	 * @param $pass 密码
	 * @param $home 目录
	 * @return String/Boolean 返回执行结果
	 */
	public function setUser($user, $pass, $home) {
		if(empty($this->daemon)) {
			return false;
		}
		$Http = new Http();
		return $Http->Request($this->host . "?action=setuser&token=" . md5($this->pass) . "&user=" . urlencode($user) . "&pass=" . urlencode($pass) . "&home=" . urlencode($home));
	}
	
	/**
	 * 获取数据库中的 Daemon 数量
	 *
	 * @return Int 返回 Daemon 数量
	 */
	public function getCounts() {
		$db = Config::MySQL();
		$conn = mysqli_connect($db['host'], $db['user'], $db['pass'], $db['name'], $db['port']);
		$rs = mysqli_query($conn, "SELECT * FROM `{$db['name']}`.`daemon`");
		$i = 0;
		while($rw = mysqli_fetch_row($rs)) {
			$i++;
		}
		return $i;
	}
	
	/**
	 * 获取数据库中的所有 Daemon 并生成列表
	 *
	 * @return String 返回 Daemon 列表
	 */
	public function getOptionList() {
		$db = Config::MySQL();
		$conn = mysqli_connect($db['host'], $db['user'], $db['pass'], $db['name'], $db['port']);
		$rs = mysqli_query($conn, "SELECT * FROM `{$db['name']}`.`daemon`");
		$data = "";
		while($rw = mysqli_fetch_row($rs)) {
			$data .= "<option value='{$rw[0]}'>{$rw[1]} ({$rw[5]})</option>";
		}
		return $data;
	}
	
	/**
	 * 在数据库中创建新的 Daemon
	 *
	 * @param $name 		Daemon 显示名称
	 * @param $host 		AJAX 请求连接地址
	 * @param $pass			Daemon 连接密码
	 * @param $fqdn			域名或 IP 地址
	 * @param $type			服务器操作系统类型
	 * @return Boolean		创建状态
	 */
	public function createDaemon($name, $host, $pass, $fqdn, $type) {
		$uuid = md5(md5(time() . rand(0, 999999)));
		$db = Config::MySQL();
		$conn = mysqli_connect($db['host'], $db['user'], $db['pass'], $db['name'], $db['port']);
		mysqli_query($conn, "INSERT INTO `{$db['name']}`.`daemon` (`id`, `name`, `host`, `pass`, `fqdn`, `type`) "
			. "VALUES (NULL, '{$name}', '{$host}', '{$pass}', '{$fqdn}', '{$type}')");
		return true;
	}
	
	/**
	 * 更新数据库中的 Daemon 数据
	 *
	 * @param $id			服务器 ID
	 * @param $name 		Daemon 显示名称
	 * @param $host 		AJAX 请求连接地址
	 * @param $pass			Daemon 连接密码
	 * @param $fqdn			域名或 IP 地址
	 * @param $type			服务器操作系统类型
	 * @return Boolean		更新状态
	 */
	public function updateDaemon($id, $name, $host, $pass, $fqdn, $type) {
		$db = Config::MySQL();
		$conn = mysqli_connect($db['host'], $db['user'], $db['pass'], $db['name'], $db['port']);
		mysqli_query($conn, "UPDATE `{$db['name']}`.`daemon` SET `name`='{$name}', `host`='{$host}', " 
			. "`pass`='{$pass}', `fqdn`='{$fqdn}', `type`='{$type}' WHERE `id`='{$id}'");
		return true;
	}
	
	/**
	 * 删除数据库中的 Daemon
	 *
	 * @param $id		Daemon ID
	 * @return Boolean	删除状态
	 */
	public function deleteDaemon($id) {
		$db = Config::MySQL();
		$conn = mysqli_connect($db['host'], $db['user'], $db['pass'], $db['name'], $db['port']);
		mysqli_query($conn, "DELETE FROM `{$db['name']}`.`daemon` WHERE `id`='{$id}'");
		return true;
	}
	
	/**
	 * 输出管理 Daemon 列表
	 *
	 * @return String Daemon 列表
	 */
	public function getDaemonListAdmin() {
		$db = Config::MySQL();
		$conn = mysqli_connect($db['host'], $db['user'], $db['pass'], $db['name'], $db['port']);
		$rs = mysqli_query($conn, "SELECT * FROM `{$db['name']}`.`daemon`");
		$data = "";
		while($rw = mysqli_fetch_row($rs)) {
			$data .= "<div class='server-hover' onclick='selectDaemon({$rw[0]}, this)'>
				<h5>{$rw[1]}</h5>
				<p>{$rw[5]} | 操作系统：{$rw[4]}</p>
			</div>";
		}
		mysqli_close($conn);
		return $data;
	}
}