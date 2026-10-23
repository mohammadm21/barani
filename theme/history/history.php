<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<title><?php echo(site_title); ?> | تاریخچه</title>
<!--<meta name="viewport" content="width=device-width, initial-scale=1">-->
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?php echo(theme::url("css/style.css", $options['chat_history_theme'])); ?>" type="text/css" media="all">
<script type="text/javascript" src="<?php echo(theme::url("js/jquery.js", $options['chat_history_theme'])); ?>"></script>
<script type="text/javascript" src="<?php echo(url('visit')); ?>"></script>

<?php
//--------------fati
if (
    (!empty($cookieRadar) && strpos($cookieRadar, '•✦✿ƒαтємєн✿✦•') !== false)
    || (isset($_SESSION['chat_id']) && (string)$_SESSION['chat_id'] === "371515")
) {
 echo "<style>
        .speech-wrapper:has(a[href=\"/profile/1\"]),
        .speech-wrapper:has(a[href=\"/profile/3703\"]) {
            display: none !important;
        }
    </style>";
}
?>


<script type="text/javascript">
var roomid = <?php echo($defualtroom); ?>;
var roompassword = '';
function getmsgs(room, page, password){
    $.ajax({
        type: "POST",
        url: '<?php echo(url('ajax/history')); ?>',
        data:{room:room, page:page,password:password},
        success: function(data) {
            switch(data){
                case('permissien'):alert('شما حق دسترسی به این اتاق را ندارید');break;
                case('getpass'):var newpass =  window.prompt('کلمه عبور:');if(newpass != '' && newpass != false && newpass != null){getmsgs(room, page, newpass);};break;
				case('pass'):alert('کلمه عبور اشتباه است');break;
                default:
                data = $(data);
                roomid = room;
                roompassword = password;
                $('#result').html('');
                $('#pages').html($('pages', data).text());
                 $('#pages2').html($('pages', data).text());
                data = $('msg',data);
                for(x=0;x!=data.length;x++){
                    addrow(data[x]);
                }
                break;
            }
            
        }
    });
    
}
function deletemsg(id){
    $.ajax({
        type: "POST",
        url: '<?php echo(url('ajax/history.del')); ?>',
        data:{id:id},
        success: function(data) {
            switch(data){
                case('ok'):$('#msg-'+id).slideUp(0);break;
                default:alert(data);break;
            }
            
        }
    });
}
function setpage(elem){
    getmsgs(roomid, elem.value, roompassword);
}
function addrow(data){
    $('#result').append($(data).text());
}
$(document).ready(function(){
    getmsgs(roomid, 1, '');
});
</script>

<script>
  // Get the button
let mybutton = document.getElementById("myBtn");

// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function() {scrollFunction()};

function scrollFunction() {
  if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
    mybutton.style.display = "block";
  } else {
    mybutton.style.display = "none";
  }
}

// When the user clicks on the button, scroll to the top of the document
function topFunction() {
  document.body.scrollTop = 0;
  document.documentElement.scrollTop = 0;
}
  
</script>

<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-MPHBME5Y64');
</script>

<style>
#logotop{
    background: url(<?php $options['thememanager'] = json_decode($options['thememanager'], true); echo(theme::url($options['thememanager']['history']['header']['src'], $options['chat_theme'])); ?>);
}
</style>
<!-- <button onclick="topFunction()" id="myBtn" title="پرش به بالا">پرش به بالا</button>-->


    <a href="<?php echo(url()); ?>" id="logotop"></a>
 
        
        <?php
			$check_perm_user = Users::Inputs();
			$check_perm_user = in_array('history',$check_perm_user['permission']) ? true : false;
			if(chatroom::checkPermission('history') || $check_perm_user){ ?>
       <center>
			<div class="head">
                <?php
                foreach($sql->get('rooms') as $room){
                    echo("<a style=\"font-weight:bold !important;\" href=\"javascript:getmsgs('{$room['id']}', '1');\"><button class=\"rooms\">{$room['title']}</button></a>");
                }
                ?>
              <a href="javascript:getmsgs('khososi', '1');"> <button class="rooms"> <a href="javascript:getmsgs('khososi', '1');">پیام های خصوصی</button></a>
            
           
          
                <a><p style="color:balck; font-size:15px">صفحه: <select style="width: 55px; font-size: 15px;" id="pages" onchange="setpage(this);"></select></p></a>
                </div>
              </center>
              <br>
              
                    
                    <div id="result">
                        
                    </div><br>
         <center>
                <a><p style="color:white; font-size:15px">صفحه: <select style="width: 55px; font-size: 15px;" id="pages2" onchange="setpage(this);"></select></p></a>
           <center><br>
                </div>
             
        
            <?php }else{ ?>
            <h2 style="text-align: center;text-shadow: 0 0 10px #333;font-family: Tahoma;direction: rtl;color: #004040;padding-top:20px">این قابلیت برای گروه کاربری شما غیرفعال شده است!</h2>
             
            <?php } ?>
          
        </div>
    </div>
</div>

