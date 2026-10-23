<?php
session_start();
header('Content-Type: application/javascript');
$base = "http://".$_SERVER['SERVER_NAME']."/game/multi/";
// allowNetworking="internal"
?>
var chat98_playerID = "<?php echo $_SESSION['my_id']; ?>";
var chat98_playerName = "<?php echo $_SESSION['my_name']; ?>";
document.write('<object id="chat98_flashGame" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" width="600" height="400">');
document.write('<param name="movie" value="' + chat98_gameURL + '" />');
document.write('<param name="allowScriptAccess" value="always" />');
document.write('<param name="allowFullScreen" value="true" />');
document.write('<param name="loop" value="false" />');
document.write('<param name="menu" value="false" />');
document.write('<param name="quality" value="high" />');
document.write('<param name="base" value="<?php echo $base; ?>" />');
document.write('<param name="wmode" value="' + (window.chat98_rightClickEnabled ? 'opaque' : 'window') + '" />');
document.write('<embed name="chat98_flashGame" src="' + chat98_gameURL + '" ');
document.write('width="600" height="400" ');
document.write('allowScriptAccess="always" ');
document.write('allowFullScreen="true" ');
document.write('FlashVars="playerID='+chat98_playerID+'&playerName='+chat98_playerName+'&gameID='+chat98_gameID+'" ');
document.write('loop="false" menu="false" quality="high"  wmode="' + (window.chat98_rightClickEnabled ? 'opaque' : 'window') + '" ');
document.write('type="application/x-shockwave-flash" ');
document.write('pluginspage="http://www.macromedia.com/go/getflashplayer" base="<?php echo $base; ?>">');
document.write('</embed>');
document.write('</object>');
	

if(window.chat98_rightClickEnabled) {
	chat98_leftMouseIsDown = false;
	chat98_rightMouseIsDown = false;
	chat98_middleMouseIsDown = false;

	function chat98_mouseDown(e) {
		if (navigator.appName == 'Netscape') {
			//if(e.pageX < 0 || e.pageX >= 800 || e.pageY < 0 || e.pageY >= 600) return;
			if(e.target.name != "chat98_flashGame") return;
			
			if(e.which == 3) {
				document.chat98_flashGame.onRightMouseDown();
				e.stopPropagation();
				return false;
			} else if(e.which == 2) {
				document.chat98_flashGame.onMiddleMouseDown();
				e.stopPropagation();
				return false;
			}
		} else if (navigator.appName == 'Microsoft Internet Explorer') {
			//if(event.clientX < 0 || event.clientX >= 800 || event.clientY < 0 || event.clientY >=600) return;
			if(event.srcElement.id != "chat98_flashGame") return;
			
			if(event.button & 1 && !chat98_leftMouseIsDown) {
				chat98_leftMouseIsDown = true;
			}
			
			if(event.button & 4 && !chat98_middleMouseIsDown) {
				chat98_middleMouseIsDown = true;
				window.chat98_flashGame.onMiddleMouseDown();
			}
			
			if(event.button & 2 && !chat98_rightMouseIsDown) {
				chat98_rightMouseIsDown = true;
				window.chat98_flashGame.onRightMouseDown();
				event.cancelBubble = true;//this actually does nothing to stop the context menu in Flash from appearing
				event.stopPropagation();//this actually generates an error, thus stopping the context menu in Flash from appearing!
				return false;
			}
		}
		return true;
	}

	function chat98_mouseUp(e) {
		if (navigator.appName == 'Netscape') {
			//if(e.pageX < 0 || e.pageX >= 800 || e.pageY < 0 || e.pageY >= 600) return;
			if(e.target.name != "chat98_flashGame") return;
			
			if(e.which == 3) {
				document.chat98_flashGame.onRightMouseUp();
				e.stopPropagation();
				return false;
			} else if(e.which == 2) {
				document.chat98_flashGame.onMiddleMouseUp();
				e.stopPropagation();
				return false;
			}
		} else if (navigator.appName == 'Microsoft Internet Explorer') {
			//if(event.clientX < 0 || event.clientX >= 800 || event.clientY < 0 || event.clientY >=600) return;
			if(event.srcElement.id != "chat98_flashGame") return;
			
			if(event.button & 1 && chat98_leftMouseIsDown) {
				chat98_leftMouseIsDown = false;
			}
			
			if(event.button & 4 && chat98_middleMouseIsDown) {
				chat98_middleMouseIsDown = false;
				window.chat98_flashGame.onMiddleMouseUp();
			}
			
			if(event.button & 2 && chat98_rightMouseIsDown) {
				chat98_rightMouseIsDown = false;
				window.chat98_flashGame.onRightMouseUp();
				event.cancelBubble = true;//this actually does nothing to stop the context menu in Flash from appearing
				event.stopPropagation();//this actually generates an error, thus stopping the context menu in Flash from appearing!
				return false;
			}
		}
		return true;
	}

	document.onmousedown = chat98_mouseDown;
	document.onmouseup = chat98_mouseUp;
	if (document.layers) window.captureEvents(Event.MOUSEDOWN);
	if (document.layers) window.captureEvents(Event.MOUSEUP);
	window.onmousedown = chat98_mouseDown;
	window.onmouseup = chat98_mouseUp;
	document.captureEvents(Event.MOUSEDOWN);
	document.getElementById("chat98_flashGame").addEventListener("mousedown", chat98_mouseDown, true);
}