<?php
    if(!session('id') || session('admin')=='0'){
        exit();
    }
    adminpanel::check_Permission('exit','addvisualuser');
    $token=isset($_POST['token'])?safe::string($_POST['token']):false;
    if(!$token || $token!=session('changepw')){
        exit('لطفا صفحه خود را رفرش کنيد');
    }
    $count=isset($_POST['count'])?safe::number($_POST['count']):0;
    if($count=='0'){
        exit('count');
    }
    if(!in_array($count,array('10','20','50','70','100','130','160','180','200','220','270','320','380','400','450','500'))){
        exit('تعداد ارسالی شما تو لیست ما تعریف نشده است');
    }
    $vuser = $sql->where('time', '0')->getValue('onlines', 'count(*)');
    $allvuser=($vuser+$count);
    if($allvuser>500){
        exit('max500');
    }
    $type=isset($_POST['type'])?(int)safe::number($_POST['type']):null;
    if(!isset($usertype[$type])){
        $type=null;
    }
    $roomid=isset($_POST['room'])?safe::number($_POST['room']):chatroom::defaultRoom();
    if(!chatroom::checkroomid($roomid)){
        exit('roomid');
    }
	$statuschk = (isset($_POST['status']) and safe::string($_POST['status']) == 'true') ? true : false;
    $roomname=chatroom::getNameRoom($roomid);
    $list=array(
        'autoanswer'=>array('true','false'),
        'os'=>array('pc','tablet','phone'),
		'ubrowser'=>array('Camino','Netscape','Opera','Safari','Chrome','Firefox','IE'),
        'status_private'=>array('pmme','nopm'),
        'status'=>array('آنلاین','باب','پاتریک','مشغول','نیستم','مایل به چت','خوابم میاد','عصبانی هستم','همه چی آرومه من چقد خوشحالم','ناراحتم','دارم گریه میکنم','شیطونم','دارم آهنگ گوش میدم','دلشکسته','دارم غذا میخورم','دارم آشپزی میکنم','فقط خدا','سرما خوردم','دارم کار میکنم','خجالتی هستم','جذاب هستم','متاهل هستم','دنبال دختر خوب برای آشنایی','دنبال پسر خوب برای آشنایی','لات چت روم','پولدار چت روم','بچه پررو','در حال مخ زدن','بی حالم','کانتر باز','کانتر تروریست','کانتر پلیس','خوشگل روم','زیر آبم','کمک میخوام','روز تولدمه','با شخصیت','باجنبه','بی جنبه','جدی و مغرور','دوست داشتنی','در حال بچه داری','لایکم کن تا لایکت کنم','عزراییل','جادوگر روم','در حال تور کردن','اسکلتم','خفاش روم','فرشته روم','درحال بازی','تلفن','عاشقم','مخفی شدن'),
        'male'=>explode(',','آباریس,آتروپات,آیدین,آرمان,آذین,آذرخش,آراد,آروین,آراز,آرام,آرتین,آرش,آرشام,آرمین,آرشاوین,آریا,آزاد,ادنا,اردیان,احمد,احمدرضا,امین,امیر,اسد,ارام,امیرعلی,احد,اریس,اشکان,اشکبوس,افشین,اکبر,الکوس,امید,امیرارسلان,انوش,انوشیروان,اروند,اهورا,ایرانیار,ایرج,ایلیا,بهنام,بهادر,بهرام,باربد,بابک,بختیار,برزو,برزین,برمک,بهداد,یاسر,بهبود,بهروز,بهزاد,بهمن,بهمنش,بیژن,پیمان,پرهام,پویا,پدرام,پارسا,پژمان,پژند,پویان,پیروز,پولاد,تکاور,تکتا,تیرداد,تهمورث,جواد,جمشید,جاوید,جاهد,جانیار,جهان,جهانگیر,چنگیز,حمید,حامد,حاتم,حبیب,حجت,حسین,حسن,حشمت,حمزه,حکیم,حنیف,خداداد,خسرو,خشایار,خوشنود,دارا,داریوش,دامون,دیاکو,دماوند,ذوالفقار,ذبیح الله,راد,رادمهر,رادنوش,رادین,رامبد,رامیار,رامین,روزبه,رستم,رهام,ریو,سینا,سحیل,ساسان,سام,سامیار,سپهر,سدرا,سورنا,سوشا,سهراب,سهند,سیامک,سیاوش,سیکا,شهاب,شاهرخ,شایان,شاهین,شبان,شروین,شکیب,شهباز,شهرام,شهرخ,شهرداد,شهروز,شهریار,شهیار,شهیاد,شیرزاد,صادق,صالح,صدرا,صفر,صفرعلی,صمد,صیاد,ضحاک,طاهر,طاها,طغرل,علی,عابد,عارف,عباد,عیسی,عزیز,عطا,عرشیا,عدنان,عزت الله,عادل,عبدالله,فیروز,فرزاد,فرامرز,فربد,فرخ,فرهاد,فریبرز,قدرت,قاسم,قدیر,قربان,قنبر,قیصر,کامران,کامبیز,کیکابوس,کامیاب,کامیار,کارن,کسری,کوروش,کوشا,کیارش,کیانوش,کیومرث,مهدی,محمد,موسی,مجتبی,مازیار,مانی,ماهان,مهرداد,ماهور,مزدک,مهران,مهرام,مهبد,مهراد,مهنام,میلاد,مهریار,مهیاد,نوید,نوروز,نوذر,نهاد,نیما,نریمان,وحید,هرمز,همایون,هوتن,هومن,هوشیار,هوشنگ,هورمزد,هویدا'),
        'famale'=>explode(',','آترین,آتوسا,آرزو,آوا,آوین,آیلین,السا,النا,الناز,باران,بنیتا,بهار,بهاره,بیتا,ترانه,ترمه,ترنم,دنیا,دیانا,رها,روشا,راسا,رویا,زهرا,زهره,سارا,سارینا,سحر,سوگند,شایلی,شیرین,عسل,غزل,غزاله,فاطمه,فرزانه,فرهزاد,فریبا,فرشته,فهیمه,لیلا,لعیا,لاله,لیدا,لیندا,مارال,مینا,مونا,ملینا,مرسانا,مهرناز,مهسا,محدثه,میترا,نهال,نگین,نیلوفر,نیوشا,نیکا,هانه,هانیه,هستی,هلیا,پریسا,پارمیس,پانته آ,کیمیا,گندم,گیتا,گلارا,یاسمن,یاسمین,یلدا,آبدیس,آتریسا,آترین,آتاناز,آتنا,آتش,آرشیدا,آرمیس,اختر,ارشیدا,ارغوان,ارکیده,اریکا,اسرا,افرا,افسانه,افروغ,افسون,المیرا,بادامک,بنفشه,بلوط,بلقیس,بهدیس,بهناز,پارمیدا,پانیذ,پاییز,پردیس,پرستش,پرستو,پرند,ترنج,توسکا,تهمینه,ثریا,ثمر,ثمین,ثنا,جاسمین,جازمین,جوهر,جانا,چکاوک,چلچله,چیستا,حانیه,حدیث,حدیثه,حسنا,حنانه,حوریه,خورشید,خزان,خرم بانو,خرم بهاره,خرم ناز,دیانا,دل آرا,دل آرام,دل آسا,دلارام,دلارا,دلبر,دلناز,رادا,راز,رز,رخساره,رستاک,روجاا,ریکا,ریرا,زینب,زیبا,زکیه,زینت,زیور,زمرد,ژیلا,ژیژیس,ژرفا,ژاله,ژاوا,سکینه,سایه,ساینه,ستاره,سپیده,ستایش,ستوده,سوسن,سودابه,سوزان,سیمین,شیما,شهین,شیرین,شهربانو,شاپرک,شادلین,شادی,شرمین,شبنم,شکیلا,شکوه,شکوفه,صوفیا,صنوبر,طوبی,طاهره,طاووس,طلوع,طهورا,طیبه,ظریف,ظهیره,عرفانه,غنچه,غدیره,غوغا,فرخنده,فرناز,فرنگیس,فروغ,فروزان,فریبا,فریماه,فیروزه,فوژان,قمرالملوک,قصیده,قدسی,کرانه,کتایون,کبری,کمند,کلاله,کیمیا,کیناز,گوهر ,گیتی,گیتا,گوهرشاد,گلیار,گلی,گلناز,گلنوش,گلندام,گلنام,گلنار,گلشن,لادن,لیسا,لیانا,ماندانا,مانیا,ماجان,ماه پری,مهتاب,ماهدیس,ماهرخ,ماهرو,مروارید,مرمر,مژده,مرسده ,مدیا,مدیسه,منیژه,مستانه,مهرناز,نادیا,نرجس,نسترن,نسرین,نرگس,نگار,نورا,نوا,نگارین,نوش افرین,نیایش,وانیا,ورتاج,مرجا,ونوشه,ویدا,ویونا,همتا,هما'),
        'type'=>array()
    );
    foreach($usertype as $key=>$val){
        if($val['admin']==0){
            $list['type'][]=$key;
        }
    }
    if(empty($list['type'])){
        if(!isset($usertype[1])){
            $list['type']=$options['defaultusertype'];
        }else{
            $list['type'][]=1;
        }
    }
    for($i=0;$i<$count;$i++){
        $gender=rand(1,2);
        if($gender==1){
            $username=$list['male'][rand(0,count($list['male']))];
        }else{
            $username=$list['famale'][rand(0,count($list['famale']))];
        }
        if(Users::Checkrunning($username)){
            $last_username=$username.' '.rand(0,10000);
        }else{
            $last_username=$username;
        }
        if($type==null || $type==0 || $type==false){
            $typess=$list['type'][array_rand($list['type'])];
        }
        $user=Users::addUser($last_username,$last_username,$typess,$gender);
        Users::addOnline($user,$last_username,$last_username,$gender,$typess,'0',$roomid,$roomname,true);
		if(!Users::guestType($type))Users::CheckProfile($user);
        Users::Inputs($user,'newvisualuser');

        $sql->where("id", $user);
        $inputs = json_decode($sql->getValue('onlines', "inputs"), true);

        $inputs['autoanswer']=($list['autoanswer'][array_rand($list['autoanswer'])]=='true'?true:false);
        $inputs['myOS']=$list['os'][array_rand($list['os'])];
		$inputs['ubrowser']=$list['ubrowser'][array_rand($list['ubrowser'])];
		$status=$list['status'][array_rand($list['status'])];
        if($status!='' and $statuschk!=false){
            $inputs['simg']='status_notification';
            $inputs['stext']=Message::badwords($status);
        }
        $inputs['status_private']=$list['status_private'][array_rand($list['status_private'])];
        $firstTimee=time();
        $inputs['firstTime']="00:00";
        $inputs['lastTime']="00:00";
        $inputs=json_encode($inputs,JSON_UNESCAPED_UNICODE);
        $sql->where("id", $user)->update('onlines', array(
        'inputs' => $inputs
        ));
    }
    $founded=false;
    $sql->where('time',"0");
    foreach($sql->get('onlines', null, array('id', 'inputs')) as $row) { 
        $inputs=json_decode($row['inputs'],true);
        if(isset($inputs['autoanswer']) && $inputs['autoanswer']==true){
        if ($sql->has('autoanswer')) {
            $sql->where("name", "autoanswer")->update('options', array(
                'value' => 1
            ));
                chatroom::reloadconfig();
            }
            $founded=true;
            break;
        }
    }
    if(!$founded){
    $sql->where("name", "autoanswer")->update('options', array(
        'value' => 0
    ));
        chatroom::reloadconfig();
    }

$sql->where("id", session('id'));
$hide = $sql->getOne('onlines', array('id','hide'));
if ($hide['hide'] == '1' and session("admin")==1){
}elseif ($hide['hide'] == '0' and session("admin")!=0){
chatroom::addlog('9', "تعداد ($count)کاربری مجازی تصادفی افزود ");
}
    echo('ok');
    ?>