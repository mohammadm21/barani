<?php $admin = session('admin'); ?>var user = new Array();
user['id'] = '<?php echo(session('id')); ?>';
user['name'] = '<?php echo(session('name')); ?>';
user['color'] = '#<?php echo(session('color')); ?>';
user['bold'] = 'normal';
user['italic'] = 'normal';
user['room'] = '<?php echo(db::get('onlines', 'roomid', "`id`='".session('id')."'")); ?>';
var errorajax = 0;

var GetTime;
var load = new Array();
load['smilies'] = false;
load['colors'] = false;


var elements = new Array();
var _startX = 0,_startY = 0,_offsetX = 0,_offsetY = 0,_dragElement,_oldZIndex = 0;

$(document).ready(function(){
    document.onmousedown = OnMouseDown;
	document.onmouseup = OnMouseUp;
    get(true);
    $('form').attr('autocomplete', 'off');
    document.oncontextmenu = function(){
        //return false;
    }
});
function get(settime){
    $.ajax({
        type: "GET",
        url: '<?php echo(url('ajax/get')); ?>',
        success: function(data) {
            xml_analize(data);
        },
        error:function(){
            adderrorajax();
        },
        cache: false,
        dataType: "xml"
    });
    if(settime == true)setTimeout("get(true);", 4000);
}
function adderrorajax(){
    errorajax++;
    if(errorajax > 5){
        PopUp('show', 'errorajax');
    }
}
function PopUp(re, elem){
    $(document).ready(function(){
        if(re == 'show'){
            $('#thickBlack').fadeIn(1000, function(){
                $('#'+elem).show(1000);
            });
        }
        if(re == 'hide'){
            $('#'+elem).hide(1000, function(){
                $('#thickBlack').fadeOut(1000);
            });
        }
    });
}
function xml_analize(data){
    errorajax = 0;
    PopUp('hide', 'errorajax');
    var benn = $('benned', data).text(),
    refresh = $('refresh', data).text(),
    Alert =  $('alert', data).text(),
    exit = $('exit', data).text(),
    clear = $('clear', data).text(),
    onlines = $('user', data),
    massages = $('massage', data),
    rooms = $('room', data);
    
    if(benn == '1'){Go('<?php echo(url('benned')); ?>');return false;}
    if(refresh == '1'){top.location.reload();return false;}
    if(Alert != 'null'){if(Alert.length > 2){alert(Alert);}}
    if(exit == 'true'){Go('<?php echo(url('logout')); ?>');return false;}
    if(clear == '1'){document.getElementById('main').innerHTML = '';}
    
    $('#onliens').html('');
    for(i=0;i!=rooms.length;i++){
        var roomid = $('id', rooms[i]).text();
        var roomname = $('name', rooms[i]).text();
        var code = "<div id=\"room-"+roomid+"\"><div class=\"title-room\" onclick=\"chengeRoom('"+roomid+"', '');\"><h4>"+roomname+"</h4></div><ul class=\"ulOnliens\"></ul></div>";
        $("#onliens").append(code);
    }
    
    //start onlines
    
    for(i=0;i!=onlines.length;i++){
        addOnline($('id', onlines[i]).text(), $('name', onlines[i]).text(),$('hide', onlines[i]).text(), $('type', onlines[i]).text(), $('gender', onlines[i]).text(), $('roomid', onlines[i]).text());
    }
    $('#num').html(onlines.length);
    //end onlines
    
    //start messages
    var Massage = new Array();
    for(i=0;i!=massages.length;i++){
        Massage['userid'] = $('userid', massages[i]).text();
        Massage['username'] = $('username', massages[i]).text();
        Massage['time'] = $('time', massages[i]).text();
        Massage['to'] = $('to', massages[i]).text();
        Massage['toname'] = $('toname', massages[i]).text();
        Massage['text'] = $('text', massages[i]).text();
        Massage['color'] = $('color', massages[i]).text();
        Massage['type'] = $('type', massages[i]).text();
        Massage['into'] = $('into', massages[i]).text();
        Massage['admin'] = $('admin', massages[i]).text();
        addMassage(Massage);
    }
    //end messages
    
}
function addOnline(id, name, hide, type, gender, roomid){
    switch(gender){
        case('1'):gender = '<img src="<?php echo(url("theme/ver1/images/user_comment_m.png")); ?>">';break;
        case('2'):gender = '<img src="<?php echo(url("theme/ver1/images/user_comment_w.png")); ?>">';break;
    }
    type = '<img src="<?php echo(url("theme/ver1/images/users-icon/'+type+'.png")); ?>">';
    var thisuser = id == user['id'] ? true : false;
    var code = "<li class=\"name\" onclick=\"OpenUserWindow(event, '"+id+"', '"+name+"');\">";
    code +="<div class=\"type\">"+type+"</div>";
    if(thisuser == true){code += '<b>';}
    code += gender+name;
    if(hide == '1'){code += '[مخفی]';}
    if(thisuser == true){code += '</b>';}
    
    code +="</li>";
    if($('#room-'+roomid).length > 0){
        
        $("#room-"+roomid+" .ulOnliens").append(code);
    } 
}
function chengeRoom(room_id, password){
    if(user['room'] != room_id){
        
        $.ajax({
            type: "POST",
            url: "<?php echo(url('ajax/chengeroom')); ?>",
            data: { room_id: room_id, password:password},
            success:function(data){
                alert
                switch(data){
                    case('ok'):document.getElementById('main').innerHTML = '';user['room'] = room_id;get(false);break;
                    case('permissien'):alert('شما حق دسترسی به این اتاق را ندارید');break;
                    case('getpass'):var newpass =  window.prompt('کلمه عبور:');if(newpass != '' && newpass != false && newpass != null){chengeRoom(room_id, newpass);};break;
                    case('pass'):alert('کلمه عبور اشتباه است');break;
                    default:alert(data);break;
                }
            },
            error: function(){
                adderrorajax();
                chengeRoom(room_id);
            }
        });
    }
}
function send(form){
    var text = form.text.value;
    if(text != ""){
        if(text.match(/mihanchat|golchat|parschat/gi)){Go("<?php echo(url('logout')); ?>");return false;}
        form.text.value="";
        var to = form.to.value.split('|'); 
        var date = new Date();
        var Massage = new Array();
        Massage['userid'] = user['id'];
        Massage['username'] = user['name'];
        Massage['time'] = date.getHours()+':'+date.getMinutes()+':'+date.getSeconds();
        Massage['to'] = to[0];
        Massage['toname'] = to[1];
        
        if(form.className == 'pm')to[2] = 'pm';
        Massage['into'] = to[2];
        Massage['text'] = text;
        if(user['italic'] == 'italic'){Massage['text'] = '<i>'+Massage['text']+'</i>';}
        if(user['bold'] == 'bold'){Massage['text'] = '<b>'+Massage['text']+'</b>';}
        Massage['color'] = user['color'].substr(1);
        Massage['type'] = 'send';
        Massage['admin'] = '<?php echo(session('admin')); ?>';
        addMassage(Massage);
        $.ajax({
          type: "POST",
          url: "<?php echo(url('ajax/get')); ?>",
          data: { text: text, to: to[0], into:to[2]},
          success: function(data){
            xml_analize(data);
            if(to[2] == 'pm'){
                $('#taskbarchat #send input[name=to]').val("-1|عمومی|window");
                $('#ToSpan').text("عمومی");
            }
            form.text.focus();
          },
          error:function(){
            adderrorajax();
          }
        });
    }
    return false;
}
function addMassage(Massage){
    Massage['text'] = Massage['text'].replace(/\(s(\d{1,4})\)/gi, "<img src=\"<?php echo(url("theme/ver1/images/smilies/(s$1).gif")); ?>\" alt=\"(s$1)\">");
    Massage['text'] = '<span style="color: #'+Massage['color']+';">'+Massage['text']+'</span>';
    if(Massage['to'] != '-1'){
        if(Massage['type'] == 'send'){Massage['pmid'] = Massage['to'];}else if(Massage['type'] == 'get'){Massage['pmid'] = Massage['userid'];}
        if(Massage['into'] == 'pm'){
            if($('#pmwindow-'+Massage['pmid']).length > 0){
                var code = '<p>('+Massage['time']+')=&gt; <b>'+Massage['username']+'</b>: '+Massage['text']+'</p>';
                $(code).appendTo('#pmwindow-'+Massage['pmid']+' .contect');  
            }else{
                if(Massage['type'] == 'send'){
                    var to = Massage['to']+'|'+Massage['toname']+'|'+Massage['pmid'];
                }else if(Massage['type'] == 'get'){
                    var to = Massage['userid']+'|'+Massage['username']+'|'+Massage['pmid'];
                }
                $('<div class="pmwindow" id="pmwindow-'+Massage['pmid']+'"></div>').appendTo('body');
                var code = '<div class="title"><b class="titleB">' + Massage['toname'] + '</b> <div class="close" onclick="CloseWindow(this.offsetParent);"></div></div><div class="contect" style="margin-top: 10px;"><p>('+Massage['time']+')=&gt; <b>'+Massage['username']+'</b>: '+Massage['text']+'<\/p></div><div class="form-pmwindow" id="form-pmwindow-'+Massage['pmid']+'"><form onsubmit="return send(this);" autocomplete="off" class="pm"><input type="hidden" name="to" value="'+to+'"><input type="text" name="text" value=""><input id="submit" type="image" name="submit" src="<?php echo(url('theme/ver1/images/Checked_small.png')); ?>" ></form>\
                <img class="iconchat" id="smilieicon" src="<?php echo(url('theme/ver1/images/Smiley_small.png')); ?>" alt="شکلک ها"></div>';
                document.getElementById('pmwindow-'+Massage['pmid']).innerHTML = code;
                var topp = Math.floor((Math.random()*5)+10) + 5;
                var leftt = Math.floor((Math.random()*10)+1) + 10;
                $('#pmwindow-'+Massage['pmid']).css('top',topp+'%').css('right', leftt+'%');
            }
            
            $('#pmwindow-'+Massage['pmid']).css('display', 'block');
            var contect = $('#pmwindow-'+Massage['pmid']+' .contect');
            contect[0].scrollTop = contect[0].scrollHeight;
        }else if(Massage['into'] == 'main'){
            var main = "<p style='background: red;'>("+Massage['time']+")=&gt;<b class=\"name\" onclick=\"OpenUserWindow(event,'"+Massage['userid']+"' '"+Massage['username']+"');\">"+Massage['username']+"</b> به <b>شما</b> :"+Massage['text']+"</p>";
            $('#main').append(main);
            document.getElementById('main').scrollTop = document.getElementById('main').scrollHeight;
        }
    }else{
        var main = "<p>("+Massage['time']+")=&gt;";
        if(Massage['admin'] != '0'){
            var icon;
            switch(Massage['admin']){
                case('1'):icon ='admin';break;
                case('2'):icon ='mod';break;
                default:icon = Massage['admin'];break;
            }
            main += '<img src="<?php echo(url("theme/ver1/images/users-icon/'+icon+'.png")); ?>">';
        }
        main += "<b class=\"name\" onclick=\"OpenUserWindow(event,'"+Massage['userid']+"', '"+Massage['username']+"');\">"+Massage['username']+"</b> :"+Massage['text']+"</p>";
        $('#main').append(main);
        document.getElementById('main').scrollTop = document.getElementById('main').scrollHeight;
    } 
}

function Clear(){
     <?php if($admin == '1' or $admin== '2'){ ?>
     var forall = window.confirm("پاک کردن صفحه همه کاربران؟");
     if(forall == true){
        $.ajax({
          type: "POST",
          url: "<?php echo(url('ajax/clear')); ?>",
          success: function(data){
            switch(data){
                case('ok'):alert('صفحه نمایش همه کاربران پاک شد.');break;
                default:alert(data);break;
            }
          },error:function(){
            adderrorajax();
          }
        });
     }
     document.getElementById('main').innerHTML = '';
     <?php }else{ ?>
     document.getElementById('main').innerHTML = '';
     <?php } ?>
     
}


function OpenUserWindow(e, userid, username){
    $('#UserWindow').css('left', (e.clientX-320)+"px").css('top', (e.clientY-10)+"px").css('display', 'block');
    $('#UserWindow #title b').text('کاربر:'+user['name']);
    if(user['id'] == userid){
        var code = "<ul>\
        <li style=\"cursor: pointer;\" onclick=\"profile('"+userid+"');\">پروفایل</li>\
        <li style=\"cursor: pointer;\" onclick=\"ChengeName('"+userid+"', '"+username+"');\">تغییرنام</li>\
        <li style=\"cursor: pointer;\" onclick=\"ChengePass('"+userid+"');\">تغییر رمز</li>\
        <li style=\"cursor: pointer;\" onclick=\"register('"+userid+"');\">عضو شدن</li>\
        <?php if($admin == '1' or $admin == '2'): ?>
        <li  style=\"cursor: pointer;\" onclick=\"hide('"+userid+"');\">مخفی</li>\
        <li  style=\"cursor: pointer;\" onclick=\"show('"+userid+"');\">آشکار</li>\<?php endif; ?>
        </ul>";
    }else{
        var code = "<ul>\
        <li id=\"pmSend\" style=\"cursor: pointer;\" onclick=\"pmSend('"+userid+"', '"+username+"', 'pm');\">پیام خصوصی در بنجره جدید</li>\
        <li style=\"cursor: pointer;\" onclick=\"profile('"+userid+"');\">پروفایل</li>\
        <?php if($admin == '1' or $admin == '2'): ?>
        <li style=\"cursor: pointer;\" onclick=\"ChengeName('"+userid+"', '"+username+"');\">تغییرنام</li>\
        <li style=\"cursor: pointer;\" onclick=\"benn('"+userid+"');\">اخراج</li>\
        <li style=\"cursor: pointer;\" onclick=\"refresh('"+userid+"');\">رفرش صفحه</li>\
        <li style=\"cursor: pointer;\" onclick=\"warning('"+userid+"');\">اخطار</li>\
        <li style=\"cursor: pointer;\" onclick=\"exit('"+userid+"');\">بیرون کردن</li>\
        <li style=\"cursor: pointer;\" onclick=\"register('"+userid+"');\">عضو کردن</li>\
        <?php if($admin == '1'): ?>
        <li style=\"cursor: pointer;\" onclick=\"mod('"+userid+"');\">ناظر کردن</li>\
        <li style=\"cursor: pointer;\" onclick=\"admin('"+userid+"');\">مدیر کردن</li>\
        <li style=\"cursor: pointer;\" onclick=\"ChengePass('"+userid+"');\">تغییر رمز</li>\
        <?php endif;endif; ?>
        </ul>";
    }
    $('#UserWindow ul').html(code);
}
function pmSend(userid, username, into){
    if($('#pmwindow-'+userid).length > 0){
        $('#pmwindow-'+userid).css('display', 'block');
    }else{
        $('#taskbarchat #send input[name=to]').val(userid+"|"+username+"|"+into);
        $('#ToSpan').text(username);
    }
    CloseUserWindow();
}
function profile(user_id){
    window.open('<?php echo(url("profile/'+user_id+'")); ?>',"_blank","height=600,width=870,resizable=no,scrollbars=yes,toolbar=no,titlebar=no")
    CloseUserWindow();
}
function history(){
    window.open('<?php echo(url("history")); ?>',"_blank","height=600,width=870,resizable=no,scrollbars=yes,toolbar=no,titlebar=no");
    CloseoptionsWindow();
}
function ChengeName(user_id, user_name){
    <?php if($admin != '1' and $admin != '2'){ ?>if(user_id == user['id']){<?php } ?>
        var newname = prompt('نام جدید را وارد کنید', '', 'تغییر نام');
        if(newname != '' && newname != false && newname != null){
            $.ajax({
                type: "POST",
                url: "<?php echo(url('ajax/rename')); ?>",
                data: { newname: newname, id: user_id},
                success: function(data){
                    switch(data){
                        case('ok'):alert('با موفقیت تغییر یافت');break;
                        case('running'):alert('این نام در انحصار شخص دیگری است');break;
                        case('badname'):alert('این نام در لیست سیاه قرار دارد');break;
                        default:alert(data);break;
                    }
                },error:function(){
                    adderrorajax();
                }
            });
        }
        CloseUserWindow();
    <?php if($admin != '1' and $admin != '2'){?>}else{
        alert('شما فقط میتونید اسم خودتان را تغییر دهید');
    }<?php } ?>
}

function register(user_id){
    var password = window.prompt('کلمه عبور:');
    if(password == null || password == ''){return false;}
    $.ajax({
        type: "POST",
        url: "<?php echo(url('ajax/thisregister')); ?>",
        data: {user: user_id, password:password},
        success: function(data){
            switch(data){
                case('ok'):alert('با موفقیت کاربر سیستم شدید.');break;
                case('lastregister'):alert('شما قبلا عضو شده اید');break;
                case('lastemail'):alert('این ایمیل قبلا در پایگاه داده ثبت شده.');break;
                case('email'):alert('لطفا یک ایمیل معتبر وارد کنید.');break;
                default:alert(data);break;
            }
        },
        error:function(){
            adderrorajax();
        }
    });
    CloseUserWindow();
}
function ChengePass(user_id){
    var password = window.prompt('کلمه عبور جدید:');
    if(password == null || password == ''){return false;}
    $.ajax({
        type: "POST",
        url: "<?php echo(url('ajax/chengepass')); ?>",
        data: {  user: user_id, password:password},
        success: function(data){
            switch(data){
                case('ok'):alert('با موفقیت رمز کاربر عوض شد.');break;
                case('noregister'):alert('شما عضو چت روم نیسیتید.');
                case('nopermision'):alert('شما اجازه تعویض رمز این شخص را ندارید.');
                default:alert(data);break;
            }
        },
        error:function(){
            adderrorajax();
        }
    });
    CloseUserWindow();
}
function CloseUserWindow(){
    $('#UserWindow').css('display', 'none');
}
function CloseWindow(element){
    $(element).css('display', 'none');
}
function colorWindowOpen(e){
    if(load['colors'] == false){
        var code = "<iframe scrolling=\"no\" frameborder=\"0\"   width=\"500\" height=\"500\" style=\"width:500px;height:500px;\" src=\"<?php echo(url('ajax/colormap')); ?>\"></iframe>";
        $('#ColorWindow .contect-smallWindow').html(code);
        load['colors'] = true;
    }
    $('#ColorWindow').css('left', (e.clientX-140)+"px").css('top', (e.clientY-250)+"px").css('display', 'block').css('height', "360px");
}
function optionsWindowOpen(e){
  $('#OptionsWindow').css('left', (e.clientX-70)+"px").css('top', (e.clientY-0)+"px").css('display', 'block');
  
}
function CloseoptionsWindow(){
    $('#OptionsWindow').css('display', 'none');
}
function clickColor(hex){
    var c = hex;
    if (c.substr(0,1) == "#"){c = c.substr(1);}
    user['color']="#" + c;
    $('input[name=text]').css('color', user['color']);
    $('#divpreview').css('display', 'block').css('background', user['color']);
    
    $.ajax({
        type: "POST",
        url: "<?php echo(url('ajax/UpColor')); ?>",
        data:{color:c},
        error:function(){
            adderrorajax();
        }
    });
    CloseWindow($('#ColorWindow'));
}
function bold(element){
    if(element.checked){
        $.ajax({
            type: "POST",
            url: "<?php echo(url('ajax/UpColor')); ?>",
            data:{color:'bold'},
            error:function(){
                adderrorajax();
            }
        });
        user['bold'] = 'bold';
    }else{
        $.ajax({
            type: "POST",
            url: "<?php echo(url('ajax/UpColor')); ?>",
            data:{color:'nobold'},
            error:function(){
                adderrorajax();
            }
        });
        user['bold'] = 'normal';
    }
}
function italic(element){
    if(element.checked){
        $.ajax({
            type: "POST",
            url: "<?php echo(url('ajax/UpColor')); ?>",
            data:{color:'italic'},
            error:function(){
                adderrorajax();
            }
        });
        user['italic'] = 'italic';
    }else{
        $.ajax({
            type: "POST",
            url: "<?php echo(url('ajax/UpColor')); ?>",
            data:{color:'noitalic'},
            error:function(){
                adderrorajax();
            }
        });
        user['italic'] = 'normal';
    }
}
function smiliesWindowOpen(e){
    if(load['smilies'] == false){
        $.ajax({
            type: "POST",
            url: "<?php echo(url('ajax/smilies')); ?>",
            success: function(data){
                var smilies = $('smily', data);
                for(i=0;i!=smilies.length;i++){
                    var code = $('code', smilies[i]).text();
                    
                    var img = $('img', smilies[i]).text();
                    $('<img src="'+img+'" class="smilies"  onclick="SelectSmile(\''+code+'\');">').appendTo('#contect-smiliesWindow');  
                    load['smilies'] = true;
                }
            },
            dataType:'xml',
            error:function(){
                adderrorajax();
            }
        });
    }
    $('#smiliesWindow').css('left', (e.clientX-140)+"px").css('top', (e.clientY-250)+"px").css('display', 'block');
}
function SelectSmile(code){
    $('input[name=text]', elements['form'])[0].value += code;
    CloseWindow($('#smiliesWindow'));
}
function OnMouseDown(e){
	if (e == null) 
		e = window.event; 
	var target = e.target != null ? e.target : e.srcElement;
    if(target.id == 'smilieicon'){
        elements['form'] = $('form', target.offsetParent)[0];
        smiliesWindowOpen(e);
    }
    
    
    
    
	if ((e.button == 1 && window.event != null || e.button == 0) && 
		(target.className == 'title' || target.className == 'title-smallWindow'))
	{
	  target = target.offsetParent;
		_startX = e.clientX;
		_startY = e.clientY;
		_offsetX = ExtractNumber(target.style.left);
		_offsetY = ExtractNumber(target.style.top);
		_oldZIndex = target.style.zIndex;
		target.style.zIndex = 10000;
		_dragElement = target;
		document.onmousemove = OnMouseMove;
		document.body.focus();
		document.onselectstart = function () { return false; };
		document.ondragstart = function() { return false; };
		return false;
	}
}
function ExtractNumber(value){
	var n = parseInt(value);
	return n == null || isNaN(n) ? 0 : n;
}
function OnMouseMove(e){
	if (e == null) var e = window.event; 
	_dragElement.style.left = (_offsetX + e.clientX - _startX) + 'px';
	_dragElement.style.top = (_offsetY + e.clientY - _startY) + 'px';
}
function OnMouseUp(e){
	if (_dragElement != null)
	{
		_dragElement.style.zIndex = _oldZIndex;
		document.onmousemove = null;
		document.onselectstart = null;
		_dragElement.ondragstart = null;
	}
}
function Go(url){
    window.open(url,"_blank");
    //top.location.href = url;
}