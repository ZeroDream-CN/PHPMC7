<?php
class Update {
	
	public function checkUpdate() {
		$data = json_decode(PHPMC::Http()->Request("https://www.phpmc.cn/update.php?version=" . PHPMC_VERSION), true);
		if(!$data) {
			return false;
		} else {
			if($data['version'] == PHPMC_VERSION) {
				return false;
			} else {
				return true;
			}
		}
	}
	
	public function getUpdateInfo() {
		$data = json_decode(PHPMC::Http()->Request("https://www.phpmc.cn/update.php?version=" . PHPMC_VERSION), true);
		if(!$data) {
			return false;
		} else {
			if($data['version'] == PHPMC_VERSION) {
				return false;
			} else {
				return $data;
			}
		}
	}
	
	public function updateExecute() {
		$data = $this->getUpdateInfo();
		if(!$data) {
			PHPMC::Error()->Println("无法更新，请检查网络是否正常。");
		} elseif(!$this->checkPermission("./")) {
			PHPMC::Error()->Println("网站目录不可写，请修改权限或手动更新。");
		} elseif(!class_exists("ZipArchive")) {
			PHPMC::Error()->Println("未检测到 ZipArchive 组件，请先修改 php.ini 启用 php_zip 扩展。");
		} else {
			$file = @PHPMC::Http()->Request($data['download']);
			if(strlen($file) == 0) {
				PHPMC::Error()->Println("下载的文件长度为 0，请检查网络是否正常。");
			} elseif(file_put_contents('update-temp.zip', $file) === false) {
				PHPMC::Error()->Println("写入文件时发生错误，请检查目录是否有读写权限。");
			} elseif(md5_file('update-temp.zip') !== $data['filemd5']) {
				@unlink('update-temp.zip');
				PHPMC::Error()->Println("文件 MD5 验证失败，请尝试重新更新。");
			} else {
				if($this->unzipUpdateFiles('update-temp.zip', './')) {
					@unlink('update-temp.zip');
					PHPMC::Error()->Println("PHPMC 更新成功，请刷新网页。");
				} else {
					@unlink('update-temp.zip');
					PHPMC::Error()->Println("解压文件时发生错误，无法打开文件或解压失败。");
				}
			}
		}
	}
	
	public function checkPermission($file) {
		if(is_dir($file)){
			$dir = $file;
			if($fp = @fopen("{$dir}/.writetest", 'w')) {
				@fclose($fp);
				@unlink("{$dir}/.writetest");
				return true;
			} else {
				return false;
			}
		} else {
			if($fp = @fopen($file, 'a+')) {
				@fclose($fp);
				return true;
			} else {
				return false;
			}
		}
	}
	
	public function unzipUpdateFiles($fileName, $unzipPath) {
		$zip = new ZipArchive();
		$open = $zip->open($fileName);
		if($open === true) {
			return $zip->extractTo($unzipPath);
		}
		return false;
	}
}