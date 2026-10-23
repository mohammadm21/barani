<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo(site_title); ?> | اخراج</title>
		<link rel="stylesheet" href="<?php echo(theme::url('css/banned.css')); ?>" type="text/css" media="all"/>
        <link rel="stylesheet" id="colorcss" href="<?php echo(theme::url('css/banned.blue.css')); ?>" type="text/css" media="all"/>
		<script type="text/javascript" src="<?php echo(theme::url('js/jquery.js')); ?>"></script>
		<script type="text/javascript">
        $(function(){
            $(".colorblue").click(function(){$("#colorcss").attr("href","<?php echo(theme::url('css/banned.blue.css')); ?>");return false});
            $(".colorred").click(function(){$("#colorcss").attr("href","<?php echo(theme::url('css/banned.red.css')); ?>");return false});
            $(".colorgrey").click(function(){$("#colorcss").attr("href","<?php echo(theme::url('css/banned.grey.css')); ?>");return false});
            $(".colorbrown").click(function(){$("#colorcss").attr("href","<?php echo(theme::url('css/banned.brown.css')); ?>");return false});
            $(".colorgreen").click(function(){$("#colorcss").attr("href","<?php echo(theme::url('css/banned.green.css')); ?>");return false});
            $("#colors").hide();
            $("a#showChanger").click(function(){$("#colors").slideToggle("slow")});
            $.ajax({
                url:'<?php echo(url('ajax/banned.codes')); ?>',
                dataType:'json',
                success:function(data){
                    for(var i = 0;i!=data.codes.length;i++){
                        if(data.codes[i].location == 'top'){
                            $('body').prepend(data.codes[i].code);
                        }else if(data.codes[i].location == 'bottom'){
                            $('body').append(data.codes[i].code);
                        }
                    }
                    
                    
                    var $addcenter = false,$openpop = false;
                    for(var i=0;i!=data.ads.length;i++){
                        switch(data.ads[i].type){
                            case('fixedbanner'):
                                var $css;
                                switch(data.ads[i].location){
                                    case('top-right'):$css = 'right:0;top:0';break;
                                    case('top-left'):$css = 'left:0;top:0';break;
                                    case('bottom-left'):$css = 'left:0;bottom:0';break;
                                    case('bottom-right'):$css = 'right:0;bottom:0';break;
                                }
                                var $closebtn = '';
                                if(data.ads[i].inputs.closeable == 'true')$closebtn = '<div style="height: 20px; line-height: 23px; background-color: #FFD8EA; cursor: pointer; text-align: center;font-size:11px" onclick="$(this).parent().hide();">بستن [x]</div>';
                                var $code = '<div id="ad-'+data.ads[i].id+'" style="position: fixed;'+$css+';background-color:transparent; z-index: 1000;">'+$closebtn+'<a target="_blank" href="'+data.ads[i].url+'"><img src="'+theme_url(data.ads[i].inputs.banner)+'" border="0"></a></div>';
                                $('body').append($code);
                                $('#ad-'+data.ads[i].id+' img').load(function(){
                                    $(this).parent().parent().css({width:$(this).width()+'px', height: ($(this).height()+20)+'px'});
                                });
                            break;
                            case('banner'):
                                if(data.ads[i].location == 'top' && $('#bannerrow'+data.ads[i].location).length == 0){
                                    $('#warp').before("<p id='bannerrow"+data.ads[i].location+"' style='text-align:center'></p>");
                                }else if(data.ads[i].location == 'bottom' && $('#bannerrow'+data.ads[i].location).length == 0){
                                    $('#warp').after("<p id='bannerrow"+data.ads[i].location+"' style='text-align:center'></p>");
                                }
                                var $code = ' <a target="_blank" href="'+data.ads[i].url+'"><img src="/'+(data.ads[i].inputs.banner)+'" border="0"></a>';
                                $('#bannerrow'+data.ads[i].location).append($code);
                            break;
                            case('popup'):case('newtab'):
                                if($('#popuplink').length == 0){
                                    var $code = '<div id="popuplink" style="position: fixed;left:0;top:0;width:100%;height:100%;z-index:99999"></div>';
                                    $('body').append($code);
                                }
                                if(!$('#popuplink').attr('ads'))var $adslink = new Array();
                                else{
                                    var $adslink = $('#popuplink').attr('ads');
                                    $adslink = $adslink.split(',');
                                }
                                $adslink.push(i);
                                $('#popuplink').attr('ads', $adslink.join(','));
                                $('#popuplink').one('click', function(){
                                    if($openpop == true)return false;
                                    $openpop = true;
                                    var $adslink = $(this).attr('ads');
                                    $adslink = $adslink.split(',');
                                    for(var ii = 0;ii!=$adslink.length;ii++){
                                        if(data.ads[$adslink[ii]].type == 'popup'){
                                            window.open(data.ads[$adslink[ii]].url, "link"+data.ads[$adslink[ii]].id,"height=400,width=600");
                                            window.focus();
                                        }else if(data.ads[$adslink[ii]].type == 'newtab'){
                                            window.open(data.ads[$adslink[ii]].url, "_blank");
                                            window.focus();
                                        }
                                    }
                                    $('#popuplink').remove();
                                });
                            break;
                        }
                    }
                    
                }
            });
        });
        </script>
	</head>
<body>
	<div id="colorchanger">
		<div id="colors">
			<a class="colorbox colorblue" href="?theme=blue" title="Blue Theme"></a>
			<a class="colorbox colorgrey" href="?theme=grey" title="Grey Theme"></a>
			<a class="colorbox colorred" href="?theme=red" title="Red Theme"></a>
			<a class="colorbox colorbrown" href="?theme=brown" title="Brown Theme"></a>
			<a class="colorbox colorgreen" href="?theme=green" title="Green Theme"></a>
		</div>
		<a href="#" id="showChanger"><img src="<?php echo(theme::url('images/banned/colortab.png')); ?>" alt="Change Theme" /></a>
	</div>
	<div id="warp">
		<div id="header_top"></div>
		<div id="header">
			<h2>متاسفیم!شما اخراج شدید</h2>
			<h5>یکی از ناظران و یا مدیران چت روم شما را اخراج کردند.</h5>
		</div>
		<div id="content">
			<div id="text">
				<p>متاسفیم بابت اخراج شما.شما را <span style="font-family: b;font-size: 15px;color:red"><?php if($to['author'] == '-1'){echo $options['chat_systemname'];}else{ ?><?php echo(Users::get('name', $to['author'])); ?><?php } ?></span> تا <span style="font-family: b;font-size: 15px;color:blue"><?php if($to[1] == '0'){echo('همیشه');}else{echo(jdate::start('تاریخ Y/m/d ساعت H:i:s', $to[1]));} ?></span> اخراج نمود</p>
                <br />
				<h3 style="font-family: b;font-size: 15px;color: brown">آیا اشتباه شده؟اعتراض دارید؟</h3>
				<ul>
					<li><a href="<?php echo(url()); ?>">&raquo; صفحه اصلی</a></li>
					<li><a href="<?php echo(url('contact')); ?>">&raquo; پیگیری اخراج ها</a></li>
					<li><a href="<?php echo(url('users/ban')); ?>">&raquo; لیست اخراج ها</a></li>
					<li><a href="<?php echo(url('terms')); ?>">&raquo; قوانین</a></li>
				</ul>	
			</div>
			<img id="book" src="<?php echo(theme::url('images/banned/banned.jpg')); ?>" alt="اخراج" />
			<div style="clear:both;"></div>
		</div>
		<div id="footer">
			<p id="twitter_text">
			</p>
			<form action="#" method="post">
			
			</form>
			<div style="clear:both;"></div>
		</div>
		<div id="footer_bottom"></div>
		<div style="clear:both;"></div>
	</div>
</body>
</html>