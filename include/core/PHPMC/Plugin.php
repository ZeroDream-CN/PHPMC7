<?php
class Plugin {
	
	public function __construct() {
		if(!file_exists("plugins/")) {
			mkdir("plugins/", 0775);
		}
	}
	
	public function load($path = "./") {
		$realpath = realpath($path);
		$handle = opendir($path);
		while($file = readdir($handle)) {
			if($file !== "." && $file !== ".." && pathinfo($file)['extension'] == "php") {
				$target = pathinfo($file)['filename'] . ".json";
				$data = $this->pluginReader("{$realpath}/{$target}");
				$info = json_decode($data, true);
				if(!$info) {
					PHPMC::Error()->Println("Error when load plugin: " . $file . ": No such plugin info file: " . $target . "<br>" . $data);
				}
				include("{$realpath}/{$file}");
				eval('$' . $info['main'] . ' = new ' . $info['main'] . '();');
				eval('$' . $info['main'] . '->onload();');
			}
		}
		closedir($handle);
	}
	
	public function pluginReader($name) {
		$file = fopen($name, "r") or die("Unable to open file!");
		$data = fread($file, filesize($name));
		fclose($file);
		return $data;
	}
}

