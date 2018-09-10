var oldlog;
var ConnectURL;
var server;
var errorLevel = 0;
var Interval;

function ajaxload() {
	if(ConnectURL == undefined) {
		return;
	}
	try {
		$(document).ready(function(){
			var start = new Date();
			var htmlobj = $.ajax({url:ConnectURL, async:true, timeout:5000, error: function(){
				$("#ping").html("连接超时");
				window.parent.frames.showmsg("与 Daemon 服务器的连接已断开。");
				clearInterval(Interval);
			}, success: function() {
				var end = new Date() - start;
				$("#ping").html(end + " 毫秒");
				if(oldlog != htmlobj.responseText) {
					$("#debug").html("<code style='color: #FFF;background-color: none;padding: 0px;'>" 
					+ htmlobj.responseText.replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/\n/g,"<br />")
					.replace(/INFO\]/g, "<span style='color: #00B100'>信息</span>]").replace(/WARN\]/g, "<span style='color: #FF8700'>警告</span>]")
					.replace(/ERROR\]/g, "<span style='color: #FF0000'>错误</span>]").replace(/\[Server/g, "[服务器").replace(/thread\//g, "主线程/")
					.replace(/Done \(/g, "<span style='color: #00B100'>启动完成，耗时 (")
					.replace(/s\)\! For help\, type \"help\" or \"\?\"/g, " 秒)！需要帮助，请输入 “help” 或 “?”</span>")
					.replace(/Unknown command\. Type \"\/help\" for help\./g, "未知命令，请输入 “help” 查看帮助。")
					.replace(/Usage\:/g, "使用方法：").replace(/Stopping the server/g, "正在关闭服务器")
					.replace(/You need to agree to the EULA in order to run the server. Go to eula.txt for more info./, 
					"<span style='color: #FF8700'>你需要接受 EULA 协议才能开启服务器，编辑服务端的 eula.txt ，将 eula=false 改为 eula=true 并保存即可。</span>")
					.replace(/Stopping server/, "正在终止服务器进程").replace(/Loading properties/, "正在加载配置文件")
					.replace(/Failed to load/, "无法加载").replace(/Starting minecraft server version/, "正在启动 Minecraft 服务器，版本：")
					.replace(/Default game type:/, "默认游戏模式：") + "</code>");
					if(autoflush.checked == true) {
						debug.scrollTop = debug.scrollHeight;
					}
					oldlog += htmlobj.responseText.replace(oldlog, "");
				}
				return;
			}});
		});
	} catch(Exception) {
		if(errorLevel >= 5) {
			window.parent.frames.showmsg("与 Daemon 服务器的连接已断开。");
			clearInterval(Interval);
		} else {
			errorLevel++;
			return;
		}
	}
};

window.onkeydown = function(event){
	if(event.keyCode == 13) {
		var command = $("#command").val();
		sendCommand(command);
		$("#command").val("");
		return false;                               
	}
};

function sendCommand(cmd) {
	var htmlobj = $.ajax({url:"?action=sendcommand&id=" + server + "&cmd=" + encodeURIComponent(cmd), async:true, timeout:5000, error: function(){
		window.parent.frames.showmsg(htmlobj.responseText);
	}});
}

window.onload = function() {
	$("#debug").html("<code style='color: #FFF;background-color: none;padding: 0px;'>欢迎使用 PHPMC <span class='text-success'>7</span> Minecraft 服务器管理器。<br>请选择一个服务器。</code>");
	ajaxload();
	serverStatus();
};

function startServer() {
	var htmlobj = $.ajax({url:"?action=start&id=" + server, async:true, timeout:5000, error: function(){
		window.parent.frames.showmsg(htmlobj.responseText);
	}});
};

function stopServer() {
	var htmlobj = $.ajax({url:"?action=stop&id=" + server, async:true, timeout:5000, error: function(){
		window.parent.frames.showmsg(htmlobj.responseText);
	}});
};

function restartServer() {
	var htmlobj = $.ajax({url:"?action=restart&id=" + server, async:true, timeout:5000, error: function(){
		window.parent.frames.showmsg(htmlobj.responseText);
	}});
};

function selectServer(id, element) {
	clearInterval(Interval);
	$(".server-hover").attr("style", "");
	element.style.border = "1px solid rgba(255,255,255,0.3)";
	var htmlobj = $.ajax({
		url:"?action=getserver&id=" + id,
		async:true,
		timeout:5000,
		error: function() {
			window.parent.frames.showmsg(htmlobj.responseText);
		},
		success: function() {
			var result = htmlobj.responseText;
			var obj = JSON.parse(result);
			console.log(obj);
			server = obj.id;
			gamehost.innerHTML = obj.gamehost;
			ftpuser.innerHTML = obj.ftpuser;
			ftppass.innerHTML = obj.ftppass;
			oldlog = "";
			ConnectURL = obj.host + "?action=getlogs&token=" + obj.token + "&name=" + obj.uuid;
			Interval = setInterval("ajaxload()", 1000);
			return;
		}
	});
};

function serverStatus() {
	var htmlobjs = $.ajax({url:"?action=status&id=" + server, async:true, timeout:5000, success: function(){
		var rpt = htmlobjs.responseText;
		var fallback = rpt.split("\/");
		$("#online").html(fallback[0]);
		$("#max").html(fallback[1]);
		setTimeout(serverStatus, 10000);
	}});
};