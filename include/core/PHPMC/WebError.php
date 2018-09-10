<?php
class WebError {
	public static function Println($str) {
		Header("Content-type: text/html;charset=UTF-8", true, 500);
		echo $str;
		exit;
	}
}