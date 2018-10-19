<?php
/**
 *
 * PHPMC 7 Install
 *
 */
error_reporting(E_ALL);
function install() {
	$db_host = $_POST['db_host'];
	$db_port = $_POST['db_port'];
	$db_user = $_POST['db_user'];
	$db_pass = $_POST['db_pass'];
	$db_name = $_POST['db_name'];
	$SiteName = $_POST['SiteName'];
	$Description = $_POST['Description'];
	$username = $_POST['username'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$password2 = $_POST['password2'];
	if(!preg_match("/^[A-Za-z0-9\-\_\.]+$/", $db_host)) {
		echo "<script>alert('数据库地址不合法！');location='?step=2';</script>";
		exit;
	}
	if(!preg_match("/^[0-9]+$/", $db_port)) {
		echo "<script>alert('数据库端口不合法！');location='?step=2';</script>";
		exit;
	}
	if(!preg_match("/^[A-Za-z0-9\-\_\.]+$/", $db_user)) {
		echo "<script>alert('数据库账号不合法！');location='?step=2';</script>";
		exit;
	}
	if(empty($db_pass)) {
		echo "<script>alert('数据库密码不能为空！');location='?step=2';</script>";
		exit;
	}
	if(!preg_match("/^[A-Za-z0-9\-\_\.]+$/", $db_name)) {
		echo "<script>alert('数据库名称不合法！');location='?step=2';</script>";
		exit;
	}
	if(empty($SiteName)) {
		$SiteName = "PHPMC 7";
	}
	if(empty($Description)) {
		$Description = "Minecraft 服务器管理系统";
	}
	if(!preg_match("/^[A-Za-z0-9\-\_\.]+$/", $username)) {
		echo "<script>alert('管理员账号不合法！');location='?step=2';</script>";
		exit;
	}
	if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		echo "<script>alert('管理员邮箱不合法！');location='?step=2';</script>";
		exit;
	}
	if(empty($password)) {
		echo "<script>alert('管理员密码不能为空！');location='?step=2';</script>";
		exit;
	}
	if($password !== $password2) {
		echo "<script>alert('两次密码输入不一致！');location='?step=2';</script>";
		exit;
	}
	$db_port = Intval($db_port);
	$password = password_hash(md5($password), PASSWORD_BCRYPT);
	$conn = mysqli_connect($db_host, $db_user, $db_pass, "", $db_port) or die("<script>alert('无法连接到 MySQL 数据库，请检查。错误：" . mysqli_error($conn) . "');location='?step=2';</script>");
	mysqli_select_db($conn, $db_name) or die("<script>alert('数据库 {$db_name} 不存在！');location='?step=2';</script>");
	mysqli_query($conn, "set names 'utf8mb4'");
	mysqli_query($conn, "SET FOREIGN_KEY_CHECKS=0;") or die("<script>alert('错误：" . mysqli_error($conn) . "');location='?step=2';</script>");
	mysqli_query($conn, "CREATE TABLE `daemon` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `host` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pass` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fqdn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;") or die("<script>alert('错误：" . mysqli_error($conn) . "');location='?step=2';</script>");
	mysqli_query($conn, "CREATE TABLE `option` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;") or die("<script>alert('错误：" . mysqli_error($conn) . "');location='?step=2';</script>");
	mysqli_query($conn, "CREATE TABLE `servers` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `daemon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `maxram` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `startcommand` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stopcommand` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `port` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ftppass` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;") or die("<script>alert('错误：" . mysqli_error($conn) . "');location='?step=2';</script>");
	mysqli_query($conn, "CREATE TABLE `users` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `permission` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;") or die("<script>alert('错误：" . mysqli_error($conn) . "');location='?step=2';</script>");
	mysqli_query($conn, "INSERT INTO `option` VALUES ('1', 'loginFailed', '0');") or die("<script>alert('错误：" . mysqli_error($conn) . "');location='?step=2';</script>");
	mysqli_query($conn, "INSERT INTO `option` VALUES ('2', 'SiteName', '{$SiteName}');") or die("<script>alert('错误：" . mysqli_error($conn) . "');location='?step=2';</script>");
	mysqli_query($conn, "INSERT INTO `option` VALUES ('3', 'Description', '{$Description}');") or die("<script>alert('错误：" . mysqli_error($conn) . "');location='?step=2';</script>");
	mysqli_query($conn, "INSERT INTO `option` VALUES ('4', 'Theme', 'PHPMC7');") or die("<script>alert('错误：" . mysqli_error($conn) . "');location='?step=2';</script>");
	mysqli_query($conn, "INSERT INTO `users` VALUES ('1', '{$username}', '{$password}', '{$email}', 'admin;');") or die("<script>alert('错误：" . mysqli_error($conn) . "');location='?step=2';</script>");
	mysqli_query($conn, "INSERT INTO `daemon` VALUES ('1', 'Example Daemon', 'http://127.0.0.1:21567/', '123456789', 'windows', '127.0.0.1');") or die("<script>alert('错误：" . mysqli_error($conn) . "');location='?step=2';</script>");
	@file_put_contents("../include/data/config.php", '<?php
class Config {
	
	public $conf = Array(
		"MySQL" => Array(
			"host" => "' . $db_host . '",
			"port" => ' . $db_port . ',
			"user" => "' . $db_user . '",
			"pass" => "' . $db_pass . '",
			"name" => "' . $db_name . '"
		)
	);
	
	public function __call($method, $args) {
		if(isset($this->conf[$method])) {
			return $this->conf[$method];
		} else {
			return "";
		}
	}
	
	public static function __callStatic($method, $args) {
		$Config = new Config();
		if(isset($Config->conf[$method])) {
			return $Config->conf[$method];
		} else {
			return "";
		}
	}
}');
	@file_put_contents("install.lock", "");
}

if(file_exists("install.lock")) {
	echo @file_get_contents("template/locked.html");
	exit;
}

if(isset($_GET['step'])) {
	switch($_GET['step']) {
		case '1':
			echo @file_get_contents("template/1.html");
			break;
		case '2':
			echo @file_get_contents("template/2.html");
			break;
		case '3':
			install();
			$type = $_SERVER['SERVER_PORT'] == 443 ? "https://" : "http://";
			$self = str_replace("index.php", "?installed=true", str_replace("install/", "", $_SERVER['PHP_SELF']));
			$connect = "{$type}{$_SERVER['HTTP_HOST']}{$self}";
			echo str_replace("{HOME}", $connect, @file_get_contents("template/3.html"));
			break;
		default:
			echo @file_get_contents("template/1.html");
			break;
	}
	exit;
} else {
	echo @file_get_contents("template/1.html");
	exit;
}