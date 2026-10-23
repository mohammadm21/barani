<?php

$el3 = giu('el3');
switch ($el3) {
 
    case('tlgsendedpm'):include(theme::file('settings.tlgsendedpm.php'));
     break;
    case('general'):include(theme::file('settings.general.php'));
        break;
    case('msg'):include(theme::file('settings.msg.php'));
        break;
 case('adsbuy'):include(theme::file('settings.adsbuy.php'));
        break;
    case('defualts'):include(theme::file('settings.defualts.php'));
   break;
    case('pmwlc'):include(theme::file('settings.pmwlc.php'));
        break;
    case('wlclogin'):include(theme::file('settings.wlclogin.php'));
        break;
    case('seo'):include(theme::file('settings.seo.php'));
   break;
    case('cmd'):include(theme::file('settings.cmd.php'));
        break;
    case('fillprint'):include(theme::file('settings.fillprint.php'));
        break;
    case('texts'):include(theme::file('settings.texts.php'));
        break;
    case('mobilesite'):include(theme::file('settings.mobilesite.php'));
        break;

}
?>
