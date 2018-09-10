<?php
class Utils {
	/**
	 *
	 *	Ini 格式配置文件读取函数
	 *
	 *	$config		配置文件内容
	 *
	 *	$need		需要获取的键名
	 *
	 **/
	public function getIniTag($config, $need) {
		if(stristr($config, "\r\n")) {
			$gettag = explode("\r\n", $config);
		} else {
			$gettag = explode("\n", $config);
		}
		for($i = 0;$i < count($gettag);$i++) {
			$getkey = explode("=", $gettag[$i]);
			if($getkey[0] == $need) {
				return $getkey[1];
			}
		}
	}
	
	/**
	 *
	 *	Minecraft 服务器信息查询函数
	 *
	 *	$addres		服务器 IP 地址
	 *
	 *	$port		服务器端口
	 *
	 *	$timeout	超时时间
	 *
	 **/
	public function Query($addres, $port = 25565, $timeout = 2) {
		$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, array('sec' => (int)$timeout, 'usec' => 0));
        socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array('sec' => (int)$timeout, 'usec' => 0));
        if($socket === false || @socket_connect($socket, $addres, (int)$port) === false) {
            return false;
        }
        socket_send($socket, "\xFE\x01", 2, 0);
        $length = socket_recv($socket, $data, 512, 0);
        socket_close($socket);
        if($length < 4 || $data[0] !== "\xFF") {
            return false;
        }
        $data = substr($data, 3);
        $data = iconv('UTF-16BE', 'UTF-8', $data);
        if($data[1] === "\xA7" && $data[2] === "\x31") {
            $data = explode("\x00", $data);
            return Array(
                'motd' => $data[3],
                'online' => intval($Data[4]),
                'max' => intval($data[5]),
                'protocol' => intval($data[1]),
                'version' => $data[2],
            );
        }
        $data = explode("\xA7", $data);
        return Array(
            'motd' => substr($data[0], 0, -1),
            'online'  => isset($data[1]) ? intval($data[1]) : 0,
            'max' => isset($data[2]) ? intval($data[2]) : 0,
            'protocol' => 0,
            'version' => '1.3',
        );
    }
}