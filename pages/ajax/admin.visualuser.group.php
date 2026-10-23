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
    $gender=isset($_POST['gender'])?(int)safe::number($_POST['gender']):1;
    if(!in_array($gender,array(1,2))){
        $gender=1;
    }
    $type=isset($_POST['type'])?(int)safe::number($_POST['type']):1;
    if(!isset($usertype[$type])){
        $type=$options['defaultusertype'];
    }
    $firstTime=(isset($_POST['firstTime']) && !empty($_POST['firstTime']))?safe::string($_POST['firstTime']):rand(0,23).':'.rand(0,23);
    $tttt=explode(':',$firstTime);
    $tttt[0]=($tttt[0]+rand(0,23))%24;
    $tttt[1]=($tttt[1]+rand(0,59))%60;
    $lastTime=(isset($_POST['lastTime']) && !empty($_POST['lastTime']))?safe::string($_POST['lastTime']):("{$tttt[0]}:{$tttt[1]}");
    $roomid=isset($_POST['room'])?safe::number($_POST['room']):chatroom::defaultRoom();
    if(!chatroom::checkroomid($roomid)){
        exit('roomid');
    }
    $roomname=chatroom::getNameRoom($roomid);
    $all_names_female='آترین,آتوسا,آرزو,آوا,آوین,آیلین,السا,النا,الناز,باران,بنیتا,بهار,بهاره,بیتا,ترانه,ترمه,ترنم,دنیا,دیانا,رها,روشا,راسا,رویا,زهرا,زهره,سارا,سارینا,سحر,سوگند,شایلی,شیرین,عسل,غزل,غزاله,فاطمه,فرزانه,فرهزاد,فریبا,فرشته,فهیمه,لیلا,لعیا,لاله,لیدا,لیندا,مارال,مینا,مونا,ملینا,مرسانا,مهرناز,مهسا,محدثه,میترا,نهال,نگین,نیلوفر,نیوشا,نیکا,هانه,هانیه,هستی,هلیا,پریسا,پارمیس,پانته آ,کیمیا,گندم,گیتا,گلارا,یاسمن,یاسمین,یلدا,آبدیس,آتریسا,آترین,آتاناز,آتنا,آتش,آرشیدا,آرمیس,اختر,ارشیدا,ارغوان,ارکیده,اریکا,اسرا,افرا,افسانه,افروغ,افسون,المیرا,بادامک,بنفشه,بلوط,بلقیس,بهدیس,بهناز,پارمیدا,پانیذ,پاییز,پردیس,پرستش,پرستو,پرند,ترنج,توسکا,تهمینه,ثریا,ثمر,ثمین,ثنا,جاسمین,جازمین,جوهر,جانا,چکاوک,چلچله,چیستا,حانیه,حدیث,حدیثه,حسنا,حنانه,حوریه,خورشید,خزان,خرم بانو,خرم بهاره,خرم ناز,دیانا,دل آرا,دل آرام,دل آسا,دلارام,دلارا,دلبر,دلناز,رادا,راز,رز,رخساره,رستاک,روجاا,ریکا,ریرا,زینب,زیبا,زکیه,زینت,زیور,زمرد,ژیلا,ژیژیس,ژرفا,ژاله,ژاوا,سکینه,سایه,ساینه,ستاره,سپیده,ستایش,ستوده,سوسن,سودابه,سوزان,سیمین,شیما,شهین,شیرین,شهربانو,شاپرک,شادلین,شادی,شرمین,شبنم,شکیلا,شکوه,شکوفه,صوفیا,صنوبر,طوبی,طاهره,طاووس,طلوع,طهورا,طیبه,ظریف,ظهیره,عرفانه,غنچه,غدیره,غوغا,فرخنده,فرناز,فرنگیس,فروغ,فروزان,فریبا,فریماه,فیروزه,فوژان,قمرالملوک,قصیده,قدسی,کرانه,کتایون,کبری,کمند,کلاله,کیمیا,کیناز,گوهر ,گیتی,گیتا,گوهرشاد,گلیار,گلی,گلناز,گلنوش,گلندام,گلنام,گلنار,گلشن,لادن,لیسا,لیانا,ماندانا,مانیا,ماجان,ماه پری,مهتاب,ماهدیس,ماهرخ,ماهرو,مروارید,مرمر,مژده,مرسده ,مدیا,مدیسه,منیژه,مستانه,مهرناز,نادیا,نرجس,نسترن,نسرین,نرگس,نگار,نورا,نوا,نگارین,نوش افرین,نیایش,وانیا,ورتاج,مرجا,ونوشه,ویدا,ویونا,همتا,هما';
    $all_names_male='آباریس,آتروپات,آیدین,آرمان,آذین,آذرخش,آراد,آروین,آراز,آرام,آرتین,آرش,آرشام,آرمین,آرشاوین,آریا,آزاد,ادنا,اردیان,احمد,احمدرضا,امین,امیر,اسد,ارام,امیرعلی,احد,اریس,اشکان,اشکبوس,افشین,اکبر,الکوس,امید,امیرارسلان,انوش,انوشیروان,اروند,اهورا,ایرانیار,ایرج,ایلیا,بهنام,بهادر,بهرام,باربد,بابک,بختیار,برزو,برزین,برمک,بهداد,یاسر,بهبود,بهروز,بهزاد,بهمن,بهمنش,بیژن,پیمان,پرهام,پویا,پدرام,پارسا,پژمان,پژند,پویان,پیروز,پولاد,تکاور,تکتا,تیرداد,تهمورث,جواد,جمشید,جاوید,جاهد,جانیار,جهان,جهانگیر,چنگیز,حمید,حامد,حاتم,حبیب,حجت,حسین,حسن,حشمت,حمزه,حکیم,حنیف,خداداد,خسرو,خشایار,خوشنود,دارا,داریوش,دامون,دیاکو,دماوند,ذوالفقار,ذبیح الله,راد,رادمهر,رادنوش,رادین,رامبد,رامیار,رامین,روزبه,رستم,رهام,ریو,سینا,سحیل,ساسان,سام,سامیار,سپهر,سدرا,سورنا,سوشا,سهراب,سهند,سیامک,سیاوش,سیکا,شهاب,شاهرخ,شایان,شاهین,شبان,شروین,شکیب,شهباز,شهرام,شهرخ,شهرداد,شهروز,شهریار,شهیار,شهیاد,شیرزاد,صادق,صالح,صدرا,صفر,صفرعلی,صمد,صیاد,ضحاک,طاهر,طاها,طغرل,علی,عابد,عارف,عباد,عیسی,عزیز,عطا,عرشیا,عدنان,عزت الله,عادل,عبدالله,فیروز,فرزاد,فرامرز,فربد,فرخ,فرهاد,فریبرز,قدرت,قاسم,قدیر,قربان,قنبر,قیصر,کامران,کامبیز,کیکابوس,کامیاب,کامیار,کارن,کسری,کوروش,کوشا,کیارش,کیانوش,کیومرث,مهدی,محمد,موسی,مجتبی,مازیار,مانی,ماهان,مهرداد,ماهور,مزدک,مهران,مهرام,مهبد,مهراد,مهنام,میلاد,مهریار,مهیاد,نوید,نوروز,نوذر,نهاد,نیما,نریمان,وحید,هرمز,همایون,هوتن,هومن,هوشیار,هوشنگ,هورمزد,هویدا';
    $all_usernames=array();
    $all_usernames_female=array();
    $all_usernames_male=array();
    if($gender==2){
        $gender_names=$all_names_female;
        $all_usernames=explode(',',$gender_names);
    }else if($gender==1){
	$gender_names=$all_names_male;
        $all_usernames=explode(',',$gender_names);
    }else{
        $all_usernames_female=explode(',',$all_names_female);
        $all_usernames_male=explode(',',$all_names_male);
    }
    for($i=0;$i<$count;$i++){
        $ggender=$gender;
        $username='';
        if($gender!=1 && $gender!=0){
            $ggender=rand(1,2);
            if($ggender==1){
                $username=$all_usernames_male[rand(0,count($all_usernames_male))];
            }else{
                $username=$all_usernames_female[rand(0,count($all_usernames_female))];
            }
        }else{
            $username=$all_usernames[rand(0,count($all_usernames))];
        }
        if(Users::Checkrunning($username)){
            $last_username=$username.' '.rand(0,10000);
        }else{
            $last_username=$username;
        }
        $user=Users::addUser($last_username,$last_username,$type,$ggender);
        Users::addOnline($user,$last_username,$last_username,$ggender,$type,'0',$roomid,$roomname,true);
		if(!Users::guestType($type))Users::CheckProfile($user);
        Users::Inputs($user,'newvisualuser');

        $sql->where("id", $user);
        $inputs = json_decode($sql->getValue('onlines', "inputs"), true);
        $inputs['autoanswer']=(isset($_POST['autoanswer']) || safe::number($_POST['autoanswer'])=='true')?true:false;
        $inputs['myOS']=(isset($_POST['myOS']) && in_array($_POST['myOS'],array('pc','tablet','phone')))?safe::string($_POST['myOS']):'pc';
		$inputs['ubrowser']=(isset($_POST['ubrowser']) && in_array($_POST['ubrowser'],array('Camino','Netscape','Opera','Safari','Chrome','Firefox','IE')))?safe::string($_POST['ubrowser']):'Chrome';
        $status=isset($_POST['status'])?safe::string($_POST['status']):'';
        if($status!=''){
            $inputs['simg']='status_notification';
            $inputs['stext']=Message::badwords($status);
        }
        $inputs['status_private']=isset($_POST['status_private']) && in_array($_POST['status_private'],array('pmme','nopm'))?safe::string($_POST['status_private']):'pmme';
        $inputs['firstTime']=$firstTime;
        $inputs['lastTime']=$lastTime;
        $inputs['countries'] = safe::string('IR');
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
chatroom::addlog('9', "تعداد ($count)کاربری مجازی افزود ");
}
    echo('ok');
    ?>