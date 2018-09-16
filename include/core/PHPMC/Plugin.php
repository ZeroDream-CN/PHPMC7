<?php
class Plugin {
	
	public $plugins;
	
	public function __construct() {
		if(!file_exists("plugins/")) {
			mkdir("plugins/", 0775);
		}
	}
	
	public function load($path = "./") {
		$realpath = realpath($path);
		$handle = opendir($path);
		while($file = readdir($handle)) {
			if($file !== "." && $file !== "..") {
				if(is_dir("{$realpath}/{$file}/")) {
					if(file_exists("{$realpath}/{$file}/{$file}.php") && file_exists("{$realpath}/{$file}/{$file}.json")) {
						$files = "{$file}.php";
						$target = pathinfo($files)['filename'] . ".json";
						$data = file_get_contents("{$realpath}/{$file}/{$target}");
						$info = json_decode($data, true);
						if(!$info) {
							PHPMC::Error()->Println("Error when load plugin: " . $files . ": No such plugin info file: " . $target . "<br>" . $data);
						}
						if(stristr($this->plugins, $info['package'] . ";")) {
							continue;
						}
						include("{$realpath}/{$file}/{$files}");
						$this->plugin .= $info['package'] . ";";
						eval('$' . $info['main'] . ' = new ' . $info['main'] . '();');
						eval('$' . $info['main'] . '->onload();');
					} else {
						echo "Error: {$realpath}/{$file}/{$file}.php";
						exit;
					}
				} else {
					if(pathinfo($file)['extension'] == "php") {
						$target = pathinfo($file)['filename'] . ".json";
						$data = file_get_contents("{$realpath}/{$target}");
						$info = json_decode($data, true);
						if(!$info) {
							PHPMC::Error()->Println("Error when load plugin: " . $file . ": No such plugin info file: " . $target . "<br>" . $data);
						}
						if(stristr($this->plugins, $info['package'] . ";")) {
							continue;
						}
						include("{$realpath}/{$file}");
						$this->plugin .= $info['package'] . ";";
						eval('$' . $info['main'] . ' = new ' . $info['main'] . '();');
						eval('$' . $info['main'] . '->onload();');
					}
				}
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

