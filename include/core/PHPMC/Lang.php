<?php
class Lang {
	
	public $lang;
	public $data;
	
	public function setLang($lang) {
		$this->lang = $lang;
		if(file_exists(ROOT . "/include/langs/{$lang}.php")) {
			include(ROOT . "/include/langs/{$lang}.php");
			$this->data = $langdata;
		} else {
			$this->data = Array();
		}
	}
	
	public function str($text) {
		$data = $this->data;
		//file_put_contents(ROOT . "/lang.txt", "'{$text}' => '{$text}'", FILE_APPEND);
		return isset($data[$text]) ? $data[$text] : $text;
	}
}