<?php
set_time_limit(10);
error_reporting(0);
$pathinfo = pathinfo($_SERVER['PHP_SELF']);
$path = str_replace("/" . $pathinfo['basename'], "", $_SERVER['PHP_SELF']);
define("ROOT", str_replace("\\", "/", __DIR__));
define("DOCROOT", $path);
if(!file_exists(ROOT . "/include/data/config.php") && !isset($_GET['installed'])) {
	echo "<script>location='install/';</script>";
	exit;
}
include(ROOT . "/include/loader.php");
$Loader = new Loader();
$Loader->router();
$Loader->frame();