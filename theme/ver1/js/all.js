var ajax = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
var load = new Array();
<?php if(isset($_SESSION['id'])) : ?>
var user = new Array();
user['id'] = '<?php echo($_SESSION['id']); ?>';
user['name'] = '<?php echo($_SESSION['name']); ?>';
user['color'] = '#<?php echo($_SESSION['color']); ?>';
load['smilies'] = false;
load['colors'] = false;
var GetTime;
<?php endif; ?>
load['links'] = false;
load['LastUsers'] = false;
BgTime = new Date().getTime()+10000;
var start = 'hide';
var nextback = 0;
var _startX = 0,_startY = 0,_offsetX = 0,_offsetY = 0,_dragElement,_oldZIndex = 0;

function Go(url){
    top.location.href = url;
}

function getTime(){
    var date = new Date();
    var time = date.getTime();
    if(time >= BgTime){ChengeBackground();}
    var html = date.getHours()+':'+date.getMinutes();+"<br>"+date.getFullYear();
    document.getElementById('time').innerHTML = html;
    setTimeout('getTime();', 1000);
}

function Start(re){
    if(re == 'close'){
        
        $('#start').css('display', 'none');
        $('#startbtn').css('backgroundImage', 'url(images/newtheme/startbtn.png)');
        start = 'hide';
        Power('close');
    }
    if(re == 'hover'){
        if(start == 'hide')$('#startbtn').css('backgroundImage', 'url(images/newtheme/startbtn.hover.png)');
    }
    if(re == 'out'){
        if(start == 'hide')$('#startbtn').css('backgroundImage', 'url(images/newtheme/startbtn.png)');
    }
    if(re == 'open'){
        if(start == 'hide'){
            Start('getLastUsers');
            $('#start').css('display', 'block');
            $('#startbtn').css('backgroundImage', 'url(images/newtheme/startbtn.click.png)');
            start = 'show';
        }else Start('close');
    }
    if(re == 'openAllprograms'){
        $('#items').css('display', 'none');
        $('#allprograms').css('display', 'block');
    }
    if(re == 'CloseAllprograms'){
        $('#items').css('display', 'block');
        $('#allprograms').css('display', 'none');
    }
    if(re == 'search'){
        var txt = $('#search_input').val();
        url =  "http://www.bing.com/search?q="+txt+"&qs=n&form=QBLH&pq="+txt+"&sc=8-2&sp=-1&sk=";
        Browser('open', url);
        return false;
    }
    if(re == 'getLastUsers'){
        if(load['LastUsers'] == false){
            $.ajax({
                type: "GET",
                url: '?i=lastUsers',
                success: function(data) {
                    $('#items_allprograms').html(data);
                    load['LastUsers'] = true;
                },
                cache: false
            });
        }
    }
}
function Power(re, e){
    if(re == 'hover'){
        $('#otheroption').css('border', '1px white solid');
    }
    if(re == 'normal'){
        $('#otheroption').css('border', 'none');
    }
    if(re == 'close'){
        Power('normal');
        Power('CloseOptions');
    }
    if(re == 'openOptions'){
        $('#options').css('top', (e.clientY-100)+"px").css('left', (e.clientX+00)+"px").css('display', 'block');
    }
    if(re == 'CloseOptions'){
        $('#options').css('display', 'none');
    }
}
function rightclick(re,e){
    if(re == 'open'){
        $('#rightclick').css('top', e.clientY+'px').css('left', e.clientX+'px').css('display', 'block');
    }
    if(re == 'check'){
        var show = document.getElementById('rightclick').style.display;
        if(show == 'block'){return true;}
        if(show == 'none'){return false;}
    }
    if(re == 'close'){
        $('#rightclick').css('display', 'none');
    }
    return false;
}
function Taskbar(re, obj){
    if(re == 'hoverItem'){
        obj.className = "itemtaskbarhover";
    }
    if(re == 'normalItem'){
        obj.className = "itemtaskbar";
    }
}
function ChengeBackground(){
    document.body.style.backgroundImage = "url(images/newtheme/backgrounds/"+nextback+".jpg)";
    if(nextback != 7){
        nextback++;  
    }else{
        nextback=0;
    }
    BgTime = new Date().getTime()+20000;
}



function windows(re, window, itemtaskbar){
    if(re == 'close'){
        window.css('display', 'none');
        itemtaskbar[0].className = 'itemtaskbar';
    }
    if(re == 'open'){
        Start('close');
        var topp = Math.floor((Math.random()*5)+10) + 5;
        var leftt = Math.floor((Math.random()*10)+1) + 10;
        window.css('display', 'block').css('top',topp+'%').css('right', leftt+'%');
        itemtaskbar[0].className = 'itemtaskbaractive';
        itemtaskbar.css('display', 'block');
    }
    if(re == 'minimize'){
        window.css('display', 'none');
    }
}
function register(form){
    var user = form.username.value;
    var pass = form.password.value;
    var email= form.email.value;
    var gender = form.gender.value;
    if(!user || !pass || !email || !gender){showmass();alert('لطفا همه موارد را پر کنید');return false;}
    $.ajax({
        type: "POST",
        url: '?i=register',
        data:{user:user, pass:pass, email:email, gender:gender},
        success: function(data) {
            switch(data){
                case('ok'):Go('?i=index.graphic');break;
                case('badname'):alert('این نام در لیست سیاه قرار دارد');break;
                case('running'):alert('این نام در حال استفاده است');break;
                case('benn'):alert('شما اجازه ورود به چتروم را ندارید');Go('?i=select');break;
                case('email'):alert('این ایمیل قبلا در بانک اطلاعاتی ثبت شده');break;
                default:alert(data);break;
            }
        }
    });
    return false;
}
function login(form){
    var user = form.username.value;
    var type = $('input:radio[name=type]:checked', form).val();
    if(user == '' || user == ' '){alert('لطفا نام خود را وارد کنید');return false;}
    if(type == 'guest'){
        var gender = form.gender.value;
        user = window.encodeURIComponent(user);
        $.ajax({
            type: "POST",
            url: '?i=guestrigis',
            data:{user:user, gender:gender},
            success: function(data) {
                switch(data){
                    case('ok'):Go('?i=index.graphic');break;
                    case('badname'):alert('این نام در لیست سیاه قرار دارد');break;
                    case('running'):alert('این نام در حال استفاده است');break;
                    case('benn'):alert('شما اجازه ورود به چتروم را ندارید');Go('?i=select');break;
                    default:alert(data);break;
                }
            }
        });   
    }
    if(type == 'user'){
        var password = window.encodeURIComponent(form.password.value);
        user = window.encodeURIComponent(user);
        $.ajax({
            type: "POST",
            url: '?i=checkpass',
            data:{user:user, pass:password},
            success: function(data) {
                switch(data){
                    case('ok'):Go('?i=index.graphic');break;
                    case('pass'):alert('کلمه عبور اشتباه است');break;
                    case('user'):alert('این نام وجود ندارد');break;
                    case('benn'):alert('شما اجازه ورود به چتروم را ندارید');Go('?i=select');break;
                    default:alert(data);break;
                }
            }
        });
    }
    
    return false;
}
function repass(form){
    var email = form.email.value;
    if(email == ''){alert('لطفا ایمیل خود را وارد کنید');return false;}
    $.ajax({
        type: "POST",
        url: '?i=repass',
        data:{email:email},
        success: function(data) {
            switch(data){
                case('send'):alert('کلمه عبور برایتان ارسال شد');break;
                case('user'):alert('این ایمیل در بانک اطلاعاتی یافت نشد');break;
                default:alert(data);break;
            }
        }
    });
    return false; 
}
function WindowLinks(re){
    if(re == 'open'){
        windows('open', $('#Windowlinks'), $('#taskbaricons #icon-links'));
        if(load['links'] == false){
            $('#ullinks').text('لطفا صبر کنید...');
            $.ajax({
                type: "GET",
                url: '?i=links',
                dataType: "xml",
                success: function(data) {
                    $('#ullinks').text('');
                    var links = $('link', data);
                    for(x=0;x!=links.length;x++){
                        var name = $('name', links[x]).text();
                        var id = $('id', links[x]).text()
                        var html = '<li class="lilinks" onclick="Browser(\'open\', \'about:links&'+id+'\');">'+name+'</li>';
                        $(html).appendTo('#ullinks');
                    }
                    load['links'] = true;
                }
            });
        }
    }
}
function Browser(re, url){
    if(re == 'close'){
        document.getElementById('browser').style.display = 'none';
        document.getElementById('browserframe').src = '';
        var itemstaskbar = document.getElementById('taskbaricons').innerHTML;
        itemstaskbar = itemstaskbar.replace("<div class=\"itemtaskbaractive\" onclick=\"Browser('maximize', '');\"><img src=\"images/newtheme/icon/InternetExplorer.png\"></div>", "<div class=\"itemtaskbar\" onclick=\"Browser('open', 'about:home');\"><img src=\"images/newtheme/icon/InternetExplorer.png\"></div>");
        document.getElementById('taskbaricons').innerHTML = itemstaskbar;
    }
    if(re == 'minimize'){
        document.getElementById('browser').style.display = 'none';
    }
    if(re == 'maximize'){
        document.getElementById('browser').style.display = 'block';
    }
    if(re == 'checkURL'){
        if(url == 'about:home'){url = 'http://ahy.ir/search';}
        url = url.replace("about:links&", "http://ahy.ir/system/iframe/links/");
        url = url.replace("about:profile&", "http://ahy.ir/panel/profile/");
        return(url);
    }
    if(re == 'open'){
        $('adressbarbrowser_adress').val(url);
        url = Browser('checkURL', url);
        $('#browserframe').attr('src', url);
        $('#browser').css('display', 'block');
        windows('open', $('#browser'), $('#taskbaricons #icon-browser'));
    }
    if(re == 'popup'){
        url = Browser('checkURL', url);
        windows('open', $('browserpopup'), $('#taskbaricons #icon-browser'));
        $('browserpopupframe').attr('src', url);
    }
    if(re == 'popupclose'){
        $('browserpopupframe').attr('src', '');
        $('browserpopup').css('display', 'none');
    }
    if(re == 'go'){
        url =  $('adressbarbrowser_adress').val();
        url = Browser('checkURL', url);
        $('#browserframe').attr('src', url);
        return false;
    }
    if(re == 'search'){
        url =  "http://ahy.ir/search/"+$('#adressbarbrowser_txt').val();
        $('#adressbarbrowser_adress').val(url);
        $('#browserframe').attr('src', url);
        return false;
    }
}
function WindowCode(re){
    if(re == 'open'){
        windows('open', $('#Windowcode'), $('#taskbaricons #icon-code'));
        ShowDemo('red');
    }
    if(re == 'close'){
        windows('close', $('#Windowcode'), $('#taskbaricons #icon-code'));
    }
    if(re == 'form'){
        var title = document.getElementById('title_code').value;
        var url = document.getElementById('url_code').value;
        if(title == '' || url == 'http://'){alert('لطفا تمامی فیلد ها را پر کنید');}else{
            var color = document.getElementById('color_code').value;
            var code = "<script type=\"text/javascript\" src=\"<?php echo(url); ?>?i=showcode&color="+color+"&url="+url+"&title="+title+"\"></\script>";
            document.getElementById('code_code').innerHTML = code;
            document.getElementById('code_code').style.display = 'block';
            document.getElementById('Windowcode').style.height = "400px";
        }
        return false;
    }
}
function loginblogpopup(){
    var name = document.getElementById('chatname').value;
    var namelength = document.getElementById('chatname').value.length;
    var gender = document.getElementById('chatgender').value;
    if(name == '' || namelength < 2){alert('لطفا نام خود را صحیح وارد کنید');}else{
        top.location.href ='<?php echo(url); ?>?i=loginblogpopup&user='+name+'&gender='+gender+'<?php echo(isset($_REQUEST['url'])? '&url='.$_REQUEST['url'] : ''); ?><?php echo(isset($_REQUEST['title'])? '&title='.$_REQUEST['title'] : ''); ?>';
    }
    return false;
}
function ShowDemo(color){
    var hashColor;
    switch(color){
        case('red'):hashColor = 'd42b2a';break;
        case('white'):hashColor = 'ffffff';break;
        case('orange'):hashColor = 'ff7907';break;
        case('yellow'):hashColor = 'fed700';break;
        case('green'):hashColor = '7ac614';break;
        case('blue'):hashColor = '2591e1';break;
        case('pink'):hashColor = 'e124a2';break;
        case('cyan'):hashColor = '9a24e1';break;
        case('brown'):hashColor = 'd56900';break;
        case('silver'):hashColor = '949292';break;
        case('black'):hashColor = '000000';break;
        default:hashColor = 'd42b2a';break;
    }
    document.getElementById('demo').innerHTML = "\
    <table width='150'  cellspacing='0' cellpadding='0' style='background-color: #FFFFFF' height='144'>\
        <tr>\
            <td height='28' bgcolor='#"+hashColor+"' background='<?php echo(url); ?>images/code/bg/"+hashColor+".gif'>\
                <p align='right' dir='rtl'><font face='Tahoma' color='#FFFFFF'><span style='font-size: 9pt'>بزرگترین چت متفرقه</span></font></p>\
            </td>\
        </tr>\
        <tr>\
            <td style='border: 1px solid #"+hashColor+"' bgcolor='#FFFFFF'>\
                <form method='post' action='<?php echo(url); ?>?i=loginblogpopup' target='_blank'  onsubmit=\"return loginblogpopup();\">\
                <p style='margin-top: 0; margin-bottom: 0'>&nbsp;</p>\
                <table border='0' dir='rtl' cellpadding='1' cellspacing='1' style='font:12px tahoma;margin:auto;padding:0;' align='center'>\
                    <tbody>\
                        <tr>\
                            <td align='center' style='font:12px tahoma;' align='center'><font style='color:black'>نام:</font></td>\
                            <td align='center'><input name='user' id='chatname' style='border:1px Solid #d7e4ee; background:White; font-family:Tahoma;font-size:10pt; height:23px; color:#222233; width:100px;' size='10' class='select'></td>\
                        </tr>\
                        <tr>\
                            <td align='center' style='font:12px tahoma;' align='center'><font style='color:black'>جنسیت</font></td>\
                            <td align='center'><select id='chatgender' style='border:1px Solid #d7e4ee; background:White; font-family:Tahoma;font-size:10pt; height:23px; color:#222233; width:100px;' name=\"gender\"><option value=\"1\">مرد</option><option value=\"2\">زن</option></select></td>\
                        </tr>\
                        <tr>\
                            <td colspan='2' align='center'>\
                                <p style='margin-top: 0; margin-bottom: 0'>&nbsp;<input type='submit' name='submit' style='BORDER:0px; PADDING: 5 0 9 0; BACKGROUND:url(<?php echo(url); ?>images/code/submit/"+hashColor+".gif) #"+hashColor+" no-repeat left top; MARGIN:5px; FONT:9pt tahoma; WIDTH:125px; CURSOR:pointer; COLOR:white;'  onmousemove='this.style.backgroundPosition=0px -33px;' onmouseout='this.style.backgroundPosition=0px 0px;' value='ورود' ></p>\
                                <p style='margin-top: 0; margin-bottom: 0'>	افراد آنلاین : <?php echo(stats('onlines')); ?> نفر</p>\
                            </td>\
                        </tr>\
                    </tbody>\
                </table>\
                </form>\
            </td>\
        </tr>\
    </table>";
}

<?php if(isset($_SESSION['id'])) { ?>
function Chat(re){
    if(re == 'open'){
        windows('open', $('#chat'), $('#taskbaricons #icon-chat'));
    }
    if(re == 'close'){
        windows('close', $('#chat'), $('#taskbaricons #icon-chat'));
    }
    if(re == 'minimize'){
        document.getElementById('chat').style.display = 'none';
    }
    if(re == 'maximize'){
        document.getElementById('chat').style.display = 'block';
    }
    if(re == 'clear'){
        document.getElementById('main').innerHTML = '';
    }
}
function get(){
    $.ajax({
        type: "GET",
        url: '?i=get',
        success: function(data) {
            xml_analize(data);
            
        },
        cache: false,
        dataType: "xml"
    });
}
function xml_analize(data){
    clearTimeout(GetTime);
    GetTime = setTimeout("get();", 4000);
    data = $(data);
    var benn = data.find("benned").text(),
    refresh = data.find("refresh").text(),
    Alert = data.find("alert").text(),
    exit = data.find("exit").text(),
    onlines = data.find("user"),
    massages = data.find("massage");
    
    if(benn == '1'){Go('?i=benned');return false;}
    if(refresh == '1'){top.location.reload();return false;}
    if(Alert != 'null'){alert(Alert);}
    
    //start onlines
    $('#ulOnliens').html('');
    for(i=0;i!=onlines.length;i++){
        addOnline($('id', onlines[i]).text(), $('name', onlines[i]).text(),$('hide', onlines[i]).text(), $('admin', onlines[i]).text(), $('gender', onlines[i]).text());
    }
    $('#num').html(onlines.length);
    //end onlines
    
    //start messages
    for(i=0;i!=massages.length;i++){
        var Massage = new Array();
        Massage['userid'] = $('userid', massages[i]).text();
        Massage['username'] = $('username', massages[i]).text();
        Massage['time'] = $('time', massages[i]).text();
        Massage['to'] = $('to', massages[i]).text();
        Massage['toname'] = $('toname', massages[i]).text();
        Massage['text'] = $('text', massages[i]).text();
        Massage['color'] = $('color', massages[i]).text();
        Massage['type'] = $('type', massages[i]).text();
        addMassage(Massage);
    }
    //end messages
}
function addOnline(id, name, hide, admin, gender, num){
    switch(gender){
        case('1'):gender = '<img src="images/user_comment_m.png">';break;
        case('2'):gender = '<img src="images/user_comment_w.png">';break;
    }
    switch(admin){
        case('1'):admin = '<img src="images/admin_i.png">';break;
        case('2'):admin = '<img src="images/mod_i.png">';break;
        default:admin = '';break;
    }
    var thisuser = id == user['id'] ? true : false;
    var code = "<li class=\"name\" onclick=\"OpenUserWindow(event, '"+id+"', '"+name+"');\">";
    code +="<div class=\"admin\">"+admin+"</div>";
    if(thisuser == true){code += '<b>';}
    code += gender+name;
    if(hide == '1'){code += '[مخفی]';}
    if(thisuser == true){code += '</b>';}
    
    code +="</li>";
    $("#ulOnliens").append(code);
}
function send(form){
    var text = form.text.value;
    if(text != ""){
        if(text.match(/mihanchat|golchat|parschat/gi)){Go("?i=logout");return false;}
        var to = form.to.value.split('|'); 
        var date = new Date();
        var Massage = new Array();
        Massage['userid'] = user['id'];
        Massage['username'] = user['name'];
        Massage['time'] = date.getHours()+':'+date.getMinutes()+':'+date.getSeconds();
        Massage['to'] = to[0];
        Massage['toname'] = to[1];
        Massage['text'] = text;
        Massage['color'] = user['color'].substr(1);
        Massage['type'] = 'send';
        addMassage(Massage);
        $.ajax({
          type: "POST",
          url: "?i=get",
          data: { text: text, to: to[0]},
          success: function(data){
            xml_analize(data);
            $('#taskbarchat #send input[name=to]').val("-1|عمومی");
            $('#ToSpan').text("عمومی");
            form.text.focus();
          }
        });
    }
    return false;
}
function Logout(){
    ajax.open("GET", "?i=unsetlogin", true);ajax.send();
    ajax.onreadystatechange = function(){
        if(ajax.readyState == 4 || ajax.readyState == 0){
            if(ajax.responseText == 'ok'){top.location.href='<?php echo(url); ?>?i=index.graphic';
            }else{alert(ajax.responseText);}
        }
    }
}
function addMassage(Massage){
    Massage['text'] = Massage['text'].replace(/\_s(\d{4})\_/gi, "<img src=\"<?php echo(url); ?>images/smilies/_s$1_.gif\">");
    Massage['text'] = '<span style="color: #'+Massage['color']+';">'+Massage['text']+'</span>';
    if(Massage['to'] != '-1'){
        if(Massage['type'] == 'send'){Massage['pmid'] = Massage['to'];}else if(Massage['type'] == 'get'){Massage['pmid'] = Massage['userid'];}
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
            var code = '<div class="title"><b class="titleB" style="color: white;">' + Massage['toname'] + '</b> <div class="close-smallWindow" onclick="CloseWindow(this.offsetParent);"></div></div><div class="contect" style="margin-top: 10px;"><p>('+Massage['time']+')=&gt; <b>'+Massage['username']+'</b>: '+Massage['text']+'<\/p></div><div class="form-pmwindow" id="form-pmwindow-'+Massage['pmid']+'"><form onsubmit="return send(this);"><input type="hidden" name="to" value="'+to+'"><input type="text" name="text" value=""><input id="submit" type="image" name="submit" src="images/Checked.png" ></form>\
            <img class="iconchat" id="smilieicon" src="images/Smiley_Cool.png" alt="شکلک ها"></div>';
            document.getElementById('pmwindow-'+Massage['pmid']).innerHTML = code;
            var topp = Math.floor((Math.random()*5)+10) + 5;
            var leftt = Math.floor((Math.random()*10)+1) + 10;
            $('#pmwindow-'+Massage['pmid']).css('top',topp+'%').css('right', leftt+'%');
        }
        
        $('#pmwindow-'+Massage['pmid']).css('display', 'block');
        var contect = $('#pmwindow-'+Massage['pmid']+' .contect');
        contect[0].scrollTop = contect[0].scrollHeight;
    }else{
        var main = "<p>("+Massage['time']+")=&gt;<b class=\"name\" onclick=\"OpenUserWindow(event,'"+Massage['userid']+"' '"+Massage['username']+"');\">"+Massage['username']+"</b> :"+Massage['text']+"</p>";
        $('#main').append(main);
        document.getElementById('main').scrollTop = document.getElementById('main').scrollHeight;
    } 
}
function OpenUserWindow(e, userid, username){
    $('#UserWindow').css('left', (e.clientX-320)+"px").css('top', (e.clientY-10)+"px").css('display', 'block');
    $('#UserWindow #title b').text('کاربر:'+user['name']);
    if(user['id'] == userid){
        var code = "<ul>\
        <li style=\"cursor: pointer;\" onclick=\"profile('"+userid+"');\">پروفایل</li>\
        <li style=\"cursor: pointer;\" onclick=\"ChengeName('"+userid+"', '"+username+"');\">تغییرنام</li>\
        <?php if($_SESSION['admin'] == '1' or $_SESSION['admin'] == '2'): ?>
        <li  style=\"cursor: pointer;\" onclick=\"hide('"+userid+"');\">مخفی</li>\
        <li  style=\"cursor: pointer;\" onclick=\"show('"+userid+"');\">آشکار</li>\<?php endif; ?>
        </ul>";
    }else{
        var code = "<ul>\
        <li id=\"pmSend\" style=\"cursor: pointer;\" onclick=\"pmSend('"+userid+"', '"+username+"');\">پیام خصوصی</li>\
        <li style=\"cursor: pointer;\" onclick=\"profile('"+userid+"');\">پروفایل</li>\
        <?php if($_SESSION['admin'] == '1' or $_SESSION['admin'] == '2'): ?>
        <li style=\"cursor: pointer;\" onclick=\"ChengeName('"+userid+"', '"+username+"');\">تغییرنام</li>\
        <li style=\"cursor: pointer;\" onclick=\"benn('"+userid+"');\">اخراج</li>\
        <?php if($_SESSION['admin'] == '1'): ?>
        <li style=\"cursor: pointer;\" onclick=\"mod('"+userid+"');\">ناظر کردن</li>\
        <?php endif;endif; ?>
        </ul>";
    }
    $('#UserWindow ul').html(code);
}
function pmSend(userid, username){
    if($('#pmwindow-'+userid).length > 0){
        $('#pmwindow-'+userid).css('display', 'block');
    }else{
        $('#taskbarchat #send input[name=to]').val(userid+"|"+username);
        $('#ToSpan').text(username);
    }
    CloseUserWindow();
}
function profile(user_id){
    window.open('http://ahy.ir/panel/profile/'+user_id,"_blank","height=600,width=870,resizable=no,scrollbars=no,toolbar=no,titlebar=no")
    CloseUserWindow();
}
function ChengeName(user_id, user_name){
    <?php if($_SESSION['admin'] != '1' and $_SESSION['admin'] != '2'){ ?>if(user_id == user['id']){<?php } ?>
        var newname = prompt('نام جدید را وارد کنید', '', 'تغییر نام');
        if(newname != '' && newname != false && newname != null){
            $.ajax({
                type: "POST",
                url: "?i=rename",
                data: { newname: newname, id: user_id},
                success: function(data){
                    if(data == 'ok'){alert('با موفقیت تغییر یافت');}
                    if(data == 'running'){alert('این نام در انحصار شخص دیگری است');}
                    if(data == 'badname'){alert('این نام در لیست سیاه قرار دارد');}
                }
            });
        }
        CloseUserWindow();
    <?php if($_SESSION['admin'] != '1' and $_SESSION['admin'] != '2'){?>}else{
        alert('شما فقط میتونید اسم خودتان را تغییر دهید');
    }<?php } ?>
}
<?php if($_SESSION['admin'] == '1' or $_SESSION['admin'] == '2'){ ?>
function hide(user_id){
    if(user_id == user['id']){
        $.ajax({
            type: "POST",
            url: "?i=hide",
        });
        CloseUserWindow();
    }else{
        alert('شما فقط میتوانید فقط خودتان مخفی شوید');
    }
}
function show(user_id){
    if(user_id == user['id']){
        $.ajax({
            type: "POST",
            url: "?i=show",
        });
        CloseUserWindow();
    }else{
        alert('شما فقط میتوانید فقط خودتان را آشکار کنید');
    }
}
function benn(user_id){
    if(user_id != user['id']){
        $.ajax({
            type: "POST",
            url: "?i=benn",
            data: {  user: user_id},
            success: function(data){
                switch(data){
                    case('ok'):alert('با موفقیت به صفحه اخراجی ها منتقل شد');break;
                    case('admin'):alert('این شخص از مدیران و ناظران چت روم است');break;
                    default:alert(ajax.responseText);break;
                }
            }
        });
        CloseUserWindow();
    }else{
        alert('شما نمیتوانید خودتان را اخراج کنید');
    }
}

<?php if($_SESSION['admin'] == '1'){ ?>
function mod(user_id){
    if(user_id != user['id']){
        $.ajax({
            type: "POST",
            url: "?i=mod",
            data: { action: 'add', user: user_id},
            success: function(data){
                switch(data){
                    case('ok'):alert('با موفقیت تبدیل به ناظر شد');break;
                    case('lastmod'):alert('این شخص در حال حاضر ناظر است');break;
                    case('lastadmin'):alert('این شخص در حال حاضر مدیر چت روم است');break;
                    case('notrigis'):alert('این شخص عضو سیستم نیست');break;
                    default:alert(data);break;
                }
            }
        });
        CloseUserWindow();
    }else{
        alert('شما خودتان مدیر هستید');
    }
}
<?php }} ?>

function CloseUserWindow(){
    $('#UserWindow').css('display', 'none');
}
function CloseWindow(element){
    $(element).css('display', 'none');
}
function colorWindowOpen(e){
    if(load['colors'] == false){
        $.ajax({
            type: "POST",
            url: "?i=colormap",
            success: function(data){
                $('#ColorWindow .contect-smallWindow').html(data);
                load['colors'] = true;
            }
        });
    }
    $('#ColorWindow').css('left', (e.clientY-250)+"px").css('top', (e.clientX-140)+"px").css('display', 'block').css('height', "360px");
}
function mouseOverColor(hex){
    $('#divpreview').css('display', 'block').css('background', hex);
}
function mouseOutMap(){
    $('#divpreview').css('display', 'block').css('background', user['color']);
}
function clickColor(hex,seltop,selleft){
    var c = hex;
    if (c.substr(0,1) == "#"){c = c.substr(1);}
    user['color']="#" + c;
    $('input[name=text]').css('color', user['color']);
    $('#divpreview').css('display', 'block').css('background', user['color']);
    
    
    $.ajax({
        type: "POST",
        url: "?i=UpColor",
        data:{color:c}
    });
    CloseWindow($('#ColorWindow'));
}
function smiliesWindowOpen(e){
    if(load['smilies'] == false){
        $.ajax({
            type: "POST",
            url: "?i=smilies",
            success: function(data){
                data = $(data);
                var smilies = $('smilies', data);
                smilies = $('name', smilies[0]);
                for(i=0;i!=smilies.length;i++){
                    var namesmilie = $(smilies[i]).text();
                    $('<img src="images/smilies/'+namesmilie+'.gif" class="smilies" id="smilie-'+namesmilie+'">').appendTo('#contect-smiliesWindow');  
                    
                }
                load['smilies'] = true;  
            }
        });
    }
    $('#smiliesWindow').css('left', (e.clientY-250)+"px").css('top', (e.clientX-140)+"px").css('display', 'block');
}
<?php } ?>
function stats(re){
    if(re == 'open'){
        document.getElementById('stats').style.display = 'block';
        var numele = 8;numele++;
        for(x=1;x!=numele;x++){
            document.getElementById('statsp'+x).style.display = 'none';
        }    
        for(x=1;x!=numele;x++){
            setTimeout("document.getElementById('statsp"+x+"').style.display = 'block';", 1500*x);
            if(x==8){
                setTimeout("document.getElementById('statspan').innerHTML = 'e<blink>_</blink>';", 2100*x);
                setTimeout("document.getElementById('statspan').innerHTML = 'ex<blink>_</blink>';", 2200*x);
                setTimeout("document.getElementById('statspan').innerHTML = 'exi<blink>_</blink>';", 2300*x);
                setTimeout("document.getElementById('statspan').innerHTML = 'exit<blink>_</blink>';", 2400*x);
                setTimeout("document.getElementById('statspan').innerHTML = 'exit .<blink>_</blink>';", 2500*x);
                setTimeout("document.getElementById('statspan').innerHTML = 'exit ..<blink>_</blink>';", 2600*x);
                setTimeout("document.getElementById('statspan').innerHTML = 'exit ...<blink>_</blink>';", 2700*x);
                setTimeout("document.getElementById('stats').style.display = 'none';", 25000);
            }
        }
    }
    if(re == 'close'){
        document.getElementById('stats').style.display = 'none';
    }
}

$(document).ready(function(){
    $('#desktop').click(function(){
        if(start == 'show'){Start('close');}
        if(rightclick('check', '') == true){rightclick('close', '');}
    });
    getTime();
    $('body').css('backgroundImage', 'url(images/newtheme/backgrounds/9.jpg)');
    <?php if(isset($_SESSION['id'])){ ?>Chat('open'); GetTime = setTimeout("get();", 400);<?php }else{?>stats('open');<?php } ?> 
});
document.oncontextmenu = function(e){
    if(e.target.id == 'desktop'){
        return rightclick('open', e);
    }else{
        return false;
    } 
}
document.onmousedown = OnMouseDown;
document.onmouseup = OnMouseUp;
document.onclick = OnClick;
document.onmouseover = OnMouseOver;
document.onmouseout = OnMouseOut;

function OnClick(e){
    var element = e.target;
    switch(element.id){
        case('startbtn'):Start('open');break;
        case('icon-browser'):Browser('open', 'about:home');break;
        case('icon-login'):windows('open', $('#windowlogin'), $('#taskbaricons #icon-login'));break;
        case('icon-register'):windows('open', $('#Windowregiser'), $('#taskbaricons #icon-register'));break;
        case('icon-links'):WindowLinks('open');break;
    }
}
function OnMouseOver(e){
    var element = e.target;
    switch(element.id){
        case('startbtn'):Start('hover');break;
    }
    switch(element.className){
        case('itemtaskbar'):Taskbar('hoverItem', element);break;
    }
}
function OnMouseOut(e){
   var element = e.target;
    switch(element.id){
        case('startbtn'):Start('out');break;
    }
    switch(element.className){
        case('itemtaskbarhover'):Taskbar('normalItem', element);break;
    }
}
function OnMouseDown(e){
	if (e == null) 
		e = window.event; 
	var target = e.target != null ? e.target : e.srcElement;
    if(target.className == 'smilies'){
        var id_element = target.id;
        id_element = id_element.replace('smilie-', '');
        SelectSmile(id_element);
    }
    if(target.id == 'smilieicon'){
        elements['form'] = target.offsetParent;
        smiliesWindowOpen(e);
    }
	if ((e.button == 1 && window.event != null || e.button == 0) && 
		(target.className == 'top' || target.className == 'title' || target.className == 'title-smallWindow'))
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