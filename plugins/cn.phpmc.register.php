<?php
class Register {
	
	public static function onload() {
		global $Loader;
		$Loader->Event->registerClass("defaultActionEvent", new Register()); // 注册 defaultActionEvent 事件
	}
	
	public function defaultActionEvent($test) {
		// print_r($test); // 输出 GET 参数数组
		return false; // 不取消事件
	}
}