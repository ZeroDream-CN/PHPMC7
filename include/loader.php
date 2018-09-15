<?php
include(ROOT . "/include/core/PHPMC/Main.php");
include(ROOT . "/include/data/config.php");
class Loader {
	
	/**
	 *
	 *	页面框架显示控制函数
	 *
	 **/
	public function frame() {
		echo $this->loadPage("panel.html", ROOT . "/content/" . Config::Theme() . "/");
	}
	
	/**
	 *
	 *	页面加载函数
	 *
	 *	$pageName	页面文件名
	 *
	 *	$pagePath	页面所在路径
	 *
	 **/
	public function loadPage($pageName, $pagePath) {
		SESSION_START();
		$Option = new Option();
		$Profile = new Profile($_SESSION["user"]);
		if(!file_exists($pagePath . $pageName)) {
			$pageName = "404.html";
			$pagePath = ROOT . "/content/" . $Option->getOption("Theme") . "/error/";
		}
		if(!PHPMC::User()->isLogin() && $pageName !== "login.html" && $pageName !== "404.html" && $pageName !== "403.html") {
			$pageName = "login.html";
		}
		$str = file_get_contents($pagePath . $pageName);
		$str = str_replace("{CONTENTDIR}", "./content", $str);
		$str = str_replace("{USERNAME}", $Profile->username, $str);
		$str = str_replace("{USERMAIL}", $Profile->email, $str);
		$str = str_replace("{AVATAR_HASH}", md5($Profile->email), $str);
		$str = str_replace("{CSRF_TOKEN}", $_SESSION['token'], $str);
		preg_match_all("/\{User\:(.*)\}/U", $str, $arr);
		for($i = 0;$i < count($arr[0]);$i++) {
			$User = new User();
			$str = str_replace($arr[0][$i], call_user_func(Array($User, $arr[1][$i])), $str);
		}
		preg_match_all("/\{Daemon\:(.*)\}/U", $str, $arr);
		for($i = 0;$i < count($arr[0]);$i++) {
			$Daemon = new Daemon();
			$str = str_replace($arr[0][$i], call_user_func(Array($Daemon, $arr[1][$i])), $str);
		}
		preg_match_all("/\{Server\:(.*)\}/U", $str, $arr);
		for($i = 0;$i < count($arr[0]);$i++) {
			$Server = new Server();
			$str = str_replace($arr[0][$i], call_user_func(Array($Server, $arr[1][$i])), $str);
		}
		preg_match_all("/\{System\:(.*)\}/U", $str, $arr);
		for($i = 0;$i < count($arr[0]);$i++) {
			$System = new System();
			$str = str_replace($arr[0][$i], call_user_func(Array($System, $arr[1][$i])), $str);
		}
		preg_match_all("/\{Option\:(.*)\}/U", $str, $arr);
		for($i = 0;$i < count($arr[0]);$i++) {
			$str = str_replace($arr[0][$i], $Option->getOption($arr[1][$i]), $str);
		}
		preg_match_all("/\{\{(.*)\}\}/U", $str, $arr);
		for($i = 0;$i < count($arr[0]);$i++) {
			$code = "return {$arr[1][$i]}; ";
			$str = str_replace($arr[0][$i], eval($code), $str);
		}
		return $str;
	}
	
	/**
	 *
	 *	页面主路由函数
	 *
	 **/
	public function router() {
		if(PHPMC::Csrf()->isemptyCsrfToken()) {
			PHPMC::Csrf()->createCsrfToken();
		}
		$Option = new Option();
		if(preg_match("/^[A-Za-z0-9\-\_]+$/", $_GET["page"])) {
			PHPMC::Permission()->checkSession("page:" . $_GET['page']);
			echo $this::loadPage($_GET["page"] . ".html", ROOT . "/content/" . $Option->getOption("Theme") . "/");
			exit;
		} elseif($_GET['action']) {
			switch($_GET['action']) {
				case 'login':
					if(!PHPMC::Csrf()->verifyCsrfToken($_POST)) {
						PHPMC::Error()->Println("Csrf 验证失败，请刷新页面重试。");
					}
					PHPMC::Event()->LoginEvent($_POST);
					break;
				case 'logout':
					PHPMC::Event()->LogoutEvent();
					break;
				case 'getserver':
					PHPMC::Permission()->checkSession("server:" . $_GET['id']);
					PHPMC::Event()->getServerEvent($_GET);
					break;
				case 'start':
					PHPMC::Permission()->checkSession("server:" . $_GET['id']);
					PHPMC::Event()->startServerEvent($_GET);
					break;
				case 'stop':
					PHPMC::Permission()->checkSession("server:" . $_GET['id']);
					PHPMC::Event()->stopServerEvent($_GET);
					break;
				case 'restart':
					PHPMC::Permission()->checkSession("server:" . $_GET['id']);
					PHPMC::Event()->restartServerEvent($_GET);
					break;
				case 'sendcommand':
					PHPMC::Permission()->checkSession("server:" . $_GET['id']);
					PHPMC::Event()->onCommandEvent($_GET);
					break;
				case 'status':
					PHPMC::Permission()->checkSession("server:" . $_GET['id']);
					PHPMC::Event()->getStatusEvent($_GET);
					break;
				case 'getserverinfo':
					PHPMC::Permission()->checkSession("server:" . $_GET['id']);
					PHPMC::Event()->getServerInfoEvent($_GET);
					break;
				case 'getdaemoninfo':
					PHPMC::Permission()->checkSession("action:" . $_GET['action']);
					PHPMC::Event()->getDaemonInfoEvent($_GET);
					break;
				case 'getuserinfo':
					PHPMC::Permission()->checkSession("action:" . $_GET['action']);
					PHPMC::Event()->getUserInfoEvent($_GET);
					break;
				case 'saveconfig':
					PHPMC::Permission()->checkSession("action:" . $_GET['action']);
					PHPMC::Event()->saveConfigEvent($_GET);
					break;
				case 'createserver':
					PHPMC::Permission()->checkSession("action:" . $_GET['action']);
					PHPMC::Event()->createServerEvent($_GET);
					break;
				case 'updateserver':
					PHPMC::Permission()->checkSession("action:" . $_GET['action']);
					PHPMC::Event()->updateServerEvent($_GET);
					break;
				case 'deleteserver':
					PHPMC::Permission()->checkSession("action:" . $_GET['action']);
					PHPMC::Event()->deleteServerEvent($_GET);
					break;
				case 'createdaemon':
					PHPMC::Permission()->checkSession("action:" . $_GET['action']);
					PHPMC::Event()->createDaemonEvent($_GET);
					break;
				case 'updatedaemon':
					PHPMC::Permission()->checkSession("action:" . $_GET['action']);
					PHPMC::Event()->updateDaemonEvent($_GET);
					break;
				case 'deletedaemon':
					PHPMC::Permission()->checkSession("action:" . $_GET['action']);
					PHPMC::Event()->deleteDaemonEvent($_GET);
					break;
				case 'createuser':
					PHPMC::Permission()->checkSession("action:" . $_GET['action']);
					PHPMC::Event()->createUserEvent($_GET);
					break;
				case 'updateuser':
					PHPMC::Permission()->checkSession("action:" . $_GET['action']);
					PHPMC::Event()->updateUserEvent($_GET);
					break;
				case 'deleteuser':
					PHPMC::Permission()->checkSession("action:" . $_GET['action']);
					PHPMC::Event()->deleteUserEvent($_GET);
					break;
				case 'update':
					PHPMC::Permission()->checkSession("action:" . $_GET['action']);
					PHPMC::Event()->updateEvent();
					break;
				default:
					echo $this::loadPage("404.html", ROOT . "/content/" . $Option->getOption("Theme") . "/error/");
			}
			exit;
		} elseif(empty($_GET['page'])) {
			echo $this::loadPage("panel.html", ROOT . "/content/" . $Option->getOption("Theme") . "/");
			exit;
		}
	}
}