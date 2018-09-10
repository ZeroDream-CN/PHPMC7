<?php
class User {

	
	public function isLogin() {
		SESSION_START();
		if(empty($_SESSION['user'])) {
			return false;
		} else {
			return true;
		}
	}
	
	public function Logout() {
		SESSION_START();
		$_SESSION['user'] = "";
	}
	
	public function getLoginUser() {
		SESSION_START();
		return new Profile($_SESSION['user']);
	}
	
	public function Login($username, $password) {
		$db = Config::MySQL();
		$conn = mysqli_connect($db['host'], $db['user'], $db['pass'], $db['name'], $db['port']);
		$rs = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `{$db['name']}`.`users` WHERE `username`='{$username}'"));
		if($rs) {
			return password_verify(md5($password), $rs['password']);
		} else {
			return false;
		}
	}
	
	public function getCounts() {
		$db = Config::MySQL();
		$conn = mysqli_connect($db['host'], $db['user'], $db['pass'], $db['name'], $db['port']);
		$rs = mysqli_query($conn, "SELECT * FROM `{$db['name']}`.`users`");
		$i = 0;
		while($rw = mysqli_fetch_row($rs)) {
			$i++;
		}
		return $i;
	}
	
	/**
	 * 获取数据库中的所有用户并生成列表
	 *
	 * @return String 返回用户列表
	 */
	public function getOptionList($isModifyList = false) {
		$db = Config::MySQL();
		$conn = mysqli_connect($db['host'], $db['user'], $db['pass'], $db['name'], $db['port']);
		$rs = mysqli_query($conn, "SELECT * FROM `{$db['name']}`.`users`");
		$data = "";
		while($rw = mysqli_fetch_row($rs)) {
			if($isModifyList) {
				$data .= "<option value='{$rw[0]}' id='User_{$rw[0]}'>{$rw[1]} ({$rw[3]})</option>";
			} else {
				$data .= "<option value='{$rw[0]}'>{$rw[1]} ({$rw[3]})</option>";
			}
		}
		return $data;
	}
	
	/**
	 * 输出管理用户列表
	 *
	 * @return String 用户列表
	 */
	public function getUserListAdmin() {
		$db = Config::MySQL();
		$conn = mysqli_connect($db['host'], $db['user'], $db['pass'], $db['name'], $db['port']);
		$rs = mysqli_query($conn, "SELECT * FROM `{$db['name']}`.`users`");
		$data = "";
		while($rw = mysqli_fetch_row($rs)) {
			$data .= "<div class='server-hover' onclick='selectUser({$rw[0]}, this)'>
				<h5>{$rw[1]}</h5>
				<p>{$rw[3]} | 权限：{$rw[4]}</p>
			</div>";
		}
		mysqli_close($conn);
		return $data;
	}
	
	/**
	 * 在数据库中创建新的用户
	 *
	 * @param $username 	用户名
	 * @param $password 	用户密码
	 * @param $email		用户邮箱
	 * @param $permission	用户权限
	 * @return Boolean		创建状态
	 */
	public function createUser($username, $password, $email, $permission) {
		$uuid = md5(md5(time() . rand(0, 999999)));
		$db = Config::MySQL();
		$conn = mysqli_connect($db['host'], $db['user'], $db['pass'], $db['name'], $db['port']);
		mysqli_query($conn, "INSERT INTO `{$db['name']}`.`users` (`id`, `username`, `password`, `email`, `permission`) "
			. "VALUES (NULL, '{$username}', '{$password}', '{$email}', '{$permission}')");
		return true;
	}
	
	/**
	 * 更新数据库中的用户数据
	 *
	 * @param $id			用户 ID
	 * @param $username 	用户名
	 * @param $password 	用户密码
	 * @param $email		用户邮箱
	 * @param $permission	用户权限
	 * @return Boolean		更新状态
	 */
	public function updateUser($id, $username, $password = false, $email, $permission) {
		$db = Config::MySQL();
		$conn = mysqli_connect($db['host'], $db['user'], $db['pass'], $db['name'], $db['port']);
		if($password) {
			mysqli_query($conn, "UPDATE `{$db['name']}`.`users` SET `username`='{$username}', `password`='{$password}', " 
				. "`email`='{$email}', `permission`='{$permission}' WHERE `id`='{$id}'");
		} else {
			mysqli_query($conn, "UPDATE `{$db['name']}`.`users` SET `username`='{$username}', " 
				. "`email`='{$email}', `permission`='{$permission}' WHERE `id`='{$id}'");
		}
		return true;
	}
	
	/**
	 * 删除数据库中的用户
	 *
	 * @param $id		用户 ID
	 * @return Boolean	删除状态
	 */
	public function deleteUser($id) {
		$db = Config::MySQL();
		$conn = mysqli_connect($db['host'], $db['user'], $db['pass'], $db['name'], $db['port']);
		mysqli_query($conn, "DELETE FROM `{$db['name']}`.`users` WHERE `id`='{$id}'");
		return true;
	}
}
