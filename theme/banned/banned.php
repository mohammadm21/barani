<!DOCTYPE html>
<html>
<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo(site_title); ?> | اخراج</title>
		<link rel="stylesheet" href="<?php echo(theme::url('css/banned.css')); ?>" type="text/css" media="all"/>
        <link rel="stylesheet" id="colorcss" href="<?php echo(theme::url('css/banned.blue.css')); ?>" type="text/css" media="all"/>
		<script type="text/javascript" src="<?php echo(theme::url('theme/js/jquery.js')); ?>"></script>
		<link rel="stylesheet" href="<?php echo(url('theme/fonts/fonts.css')); ?>" type="text/css" media="all"/>
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
				<p>متاسفیم بابت اخراج شما.شما را <span style="font-family: b;font-size: 15px;color:red"><?php if($to['author'] == '-1') echo 'سیستم به صورت هوشمند';
 else { 
if ($options[noekhrajer]=='false') {
echo(Users::get('name', $to['author'])); }
 else echo $options[ekhrajername];
}
?>
</span> تا <span style="font-family: b;font-size: 15px;color:blue"><?php if($to['to'] == '0'){echo('همیشه');}else{echo(jdate::start('تاریخ l d F Y ساعت H:i:s', $to['to']));} ?></span> اخراج نمود</p>
				<p>دلیل اخراج شما :‌ <span style="font-family: b;font-size: 15px;color:red"><?php 
if ($options['nodalil']=='false') { echo $to['reason']; } else echo $options['dalil']; ?></span></p>
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

<?php if (!empty($bannCaptureToken)) { ?>
<script type="text/javascript">
// ضبطِ اثر انگشتِ دستگاهِ اخراجی در همین لحظه — با توکنِ یک‌بارمصرفِ سرور
// (چون session پس از بن پاک شده، از خودِ token استفاده می‌کنیم).
(function(){
	var CAPTURE_TOKEN = <?php echo json_encode((string)$bannCaptureToken); ?>;
	function h(s){var i,_h=0;for(i=0;i<s.length;i++){_h=(_h<<5)-_h+s.charCodeAt(i);_h|=0;}return (_h>>>0).toString(36);}
	function collect(){
		var d={};
		d.userAgent=navigator.userAgent; d.language=navigator.language||''; d.platform=navigator.platform||'';
		d.hardwareConcurrency=navigator.hardwareConcurrency||0; d.deviceMemory=navigator.deviceMemory||0;
		d.maxTouchPoints=navigator.maxTouchPoints||0; d.timezone=(Intl.DateTimeFormat().resolvedOptions().timeZone)||'';
		d.timezoneOffset=new Date().getTimezoneOffset(); d.screenWidth=screen.width; d.screenHeight=screen.height;
		d.screenColorDepth=screen.colorDepth; d.pixelRatio=window.devicePixelRatio||1;
		try{var c=document.createElement('canvas'),x=c.getContext('2d');c.width=220;c.height=30;x.textBaseline='top';x.font="14px 'Arial'";x.fillStyle='#f60';x.fillRect(0,0,100,20);x.fillStyle='#069';x.fillText('fingerprint-check-🔒',2,15);d.canvasFp=h(c.toDataURL());}catch(e){d.canvasFp='unsupported';}
		var gl;try{gl=document.createElement('canvas').getContext('webgl')||document.createElement('canvas').getContext('experimental-webgl');}catch(e){gl=null;}
		if(gl&&gl.getExtension){var gi;try{gi=gl.getExtension('WEBGL_debug_renderer_info');}catch(e){gi=null;}d.webglVendor=gi?gl.getParameter(gi.UNMASKED_VENDOR_WEBGL):'';d.webglRenderer=gi?gl.getParameter(gi.UNMASKED_RENDERER_WEBGL):'';}
		d.cookiesEnabled=navigator.cookieEnabled; return d;
	}
	// توکنِ دستگاه — هشِ سیگنال‌های سخت‌عوض (همان فرمولِ fillprint.js)، عمداً از کوکی
	// خوانده نمی‌شود تا با پاک‌شدنِ کوکی، دستگاهِ بازگشتی ناشناس نماند.
	var TOK=h((collect().canvasFp||'')+'|'+(collect().webglRenderer||'')+'|'+(collect().platform||'')+'|'+screen.width+'x'+screen.height);
	setTimeout(function(){
		var p=collect(); p.fpCapture=true; p.fpToken=TOK; p.bannCaptureToken=CAPTURE_TOKEN;
		try{ fetch(<?php echo json_encode(url('ajax/fillprint.collect')); ?>,{method:'POST',headers:{'Content-Type':'application/json'},credentials:'same-origin',body:JSON.stringify(p)}); }catch(err){}
	},600);
})();
</script>
<?php } ?>
</body>
</html>